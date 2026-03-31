<?php

namespace App\Includes;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

/**
 * LegislationRepo — Git-based proposal versioning for the Martian Republic
 *
 * Every proposal gets a branch. Amendments are commits. Diffs are automatic.
 * When enacted, the branch merges into main. The git history IS the legislative record.
 *
 * Storage: storage/legislation/
 */
class LegislationRepo
{
    private string $repoPath;

    public function __construct()
    {
        $this->repoPath = storage_path('legislation');
        $this->ensureRepo();
    }

    /**
     * Initialize the repo if it doesn't exist
     */
    private function ensureRepo(): void
    {
        if (is_dir($this->repoPath.'/.git')) {
            return;
        }

        $this->git('init');
        $this->git('config user.name "Martian Congress"');
        $this->git('config user.email "congress@martianrepublic.org"');

        $proposalsDir = $this->repoPath.'/proposals';
        if (! is_dir($proposalsDir)) {
            mkdir($proposalsDir, 0775, true);
        }

        file_put_contents($this->repoPath.'/README.md',
            "# Martian Republic Legislation\n\nEvery proposal is a branch. Every amendment is a commit. Every enactment is a merge.\n");
        file_put_contents($proposalsDir.'/.gitkeep', '');

        $this->git('add -A');
        $this->git('commit -m "Initialize legislative repository"');
        $this->git('branch -M main');

        Log::info('LegislationRepo: Initialized at '.$this->repoPath);
    }

    /**
     * Submit a new proposal — creates a branch and commits the text
     */
    public function submitProposal(int $proposalId, string $title, string $description, string $author, string $tier, array $metadata = []): string
    {
        $branch = $this->branchName($proposalId);

        $this->git('checkout main');
        $this->git("checkout -b {$branch}");

        $this->writeProposalFile($proposalId, $title, $description, $author, $tier, $metadata);

        $this->git('add -A');
        $this->gitCommit("Submit proposal MR-{$proposalId}: {$title}");
        $hash = trim($this->git('rev-parse HEAD'));

        $this->git('checkout main');

        Log::info("LegislationRepo: MR-{$proposalId} submitted [{$hash}]");

        return $hash;
    }

    /**
     * Amend a proposal during screening — new commit on the proposal branch
     */
    public function amendProposal(int $proposalId, string $title, string $description, string $author, string $tier, array $metadata = [], string $note = ''): string
    {
        $branch = $this->branchName($proposalId);

        $this->git("checkout {$branch}");

        $this->writeProposalFile($proposalId, $title, $description, $author, $tier, $metadata);

        $this->git('add -A');
        $suffix = $note ? ": {$note}" : '';
        $this->gitCommit("Amend proposal MR-{$proposalId}{$suffix}");
        $hash = trim($this->git('rev-parse HEAD'));

        $this->git('checkout main');

        Log::info("LegislationRepo: MR-{$proposalId} amended [{$hash}]");

        return $hash;
    }

    /**
     * Get unified diff between original and latest amendment
     */
    public function getAmendmentDiff(int $proposalId): ?string
    {
        $branch = $this->branchName($proposalId);
        if (! $this->branchExists($branch)) {
            return null;
        }

        $count = (int) trim($this->git("rev-list --count main..{$branch}"));
        if ($count < 2) {
            return null;
        }

        $commits = array_filter(explode("\n", trim($this->git("rev-list --reverse main..{$branch}"))));
        $first = reset($commits);
        $last = end($commits);
        if ($first === $last) {
            return null;
        }

        $diff = $this->git("diff {$first} {$last} -- ".$this->proposalFilePath($proposalId));

        return ! empty(trim($diff)) ? $diff : null;
    }

    /**
     * Get proposal text: 'latest' or 'original'
     */
    public function getProposalText(int $proposalId, string $version = 'latest'): ?string
    {
        $branch = $this->branchName($proposalId);
        $filePath = $this->proposalFilePath($proposalId);
        if (! $this->branchExists($branch)) {
            return null;
        }

        if ($version === 'original') {
            $commits = array_filter(explode("\n", trim($this->git("rev-list --reverse main..{$branch}"))));
            $first = reset($commits);
            if (! $first) {
                return null;
            }
            $result = $this->git("show {$first}:{$filePath} 2>/dev/null");

            return ! empty(trim($result)) ? $result : null;
        }

        $result = $this->git("show {$branch}:{$filePath} 2>/dev/null");

        return ! empty(trim($result)) ? $result : null;
    }

    /**
     * Get commit log: [{hash, message, date, author}]
     */
    public function getProposalHistory(int $proposalId): array
    {
        $branch = $this->branchName($proposalId);
        if (! $this->branchExists($branch)) {
            return [];
        }

        $sep = '|||';
        $log = $this->git("log {$branch} --not main --format=\"%H{$sep}%s{$sep}%aI{$sep}%an\" --reverse");
        $entries = [];

        foreach (explode("\n", trim($log)) as $line) {
            if (empty(trim($line))) {
                continue;
            }
            $parts = explode($sep, $line, 4);
            if (count($parts) === 4) {
                $entries[] = [
                    'hash' => substr($parts[0], 0, 12),
                    'hash_full' => $parts[0],
                    'message' => $parts[1],
                    'date' => $parts[2],
                    'author' => $parts[3],
                ];
            }
        }

        return $entries;
    }

    public function isAmended(int $proposalId): bool
    {
        return $this->getAmendmentCount($proposalId) > 0;
    }

    public function getAmendmentCount(int $proposalId): int
    {
        $branch = $this->branchName($proposalId);
        if (! $this->branchExists($branch)) {
            return 0;
        }
        $count = (int) trim($this->git("rev-list --count main..{$branch}"));

        return max(0, $count - 1);
    }

    public function enactProposal(int $proposalId, string $title): string
    {
        $branch = $this->branchName($proposalId);
        $this->git('checkout main');
        $this->git("merge --no-ff {$branch} -m ".escapeshellarg("Enact MR-{$proposalId}: {$title}"));
        $hash = trim($this->git('rev-parse HEAD'));
        Log::info("LegislationRepo: MR-{$proposalId} enacted [{$hash}]");

        return $hash;
    }

    public function sunsetProposal(int $proposalId, string $title): string
    {
        $this->git('checkout main');
        $this->git('rm -f '.$this->proposalFilePath($proposalId));
        $this->gitCommit("Sunset MR-{$proposalId}: {$title}");
        $hash = trim($this->git('rev-parse HEAD'));
        Log::info("LegislationRepo: MR-{$proposalId} sunset [{$hash}]");

        return $hash;
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    private function branchName(int $id): string
    {
        return "proposal/MR-{$id}";
    }

    private function proposalFilePath(int $id): string
    {
        return "proposals/MR-{$id}.md";
    }

    private function branchExists(string $branch): bool
    {
        $result = trim($this->git("branch --list \"{$branch}\""));

        return ! empty($result);
    }

    private function writeProposalFile(int $id, string $title, string $desc, string $author, string $tier, array $meta = []): void
    {
        $dir = $this->repoPath.'/proposals';
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $t = strtoupper($tier);
        $d = now()->format('Y-m-d H:i:s T');

        $doc = "---\nproposal: MR-{$id}\ntitle: \"{$title}\"\ntier: {$t}\nsponsor: \"{$author}\"\ndate: {$d}\n";
        foreach ($meta as $k => $v) {
            $doc .= "{$k}: {$v}\n";
        }
        $doc .= "---\n\n# MR-{$id}: {$title}\n\n**Tier:** {$t} | **Sponsor:** {$author}\n\n---\n\n{$desc}\n";

        file_put_contents($this->repoPath.'/'.$this->proposalFilePath($id), $doc);
    }

    private function gitCommit(string $msg): void
    {
        $this->git('commit -m '.escapeshellarg($msg));
    }

    private function git(string $cmd): string
    {
        $result = Process::timeout(30)->path($this->repoPath)->run("git {$cmd} 2>&1");

        return $result->output() ?? '';
    }
}
