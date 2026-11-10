<?php

namespace App\Console\Commands;

use App\Http\Controllers\StatusController;
use App\Services\StatusAlertPolicy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

/**
 * Emails the operators when a subsystem on the status page stays down.
 *
 * Run every five minutes from cron, as the web server's user:
 *   * /5 * * * *  cd /var/www/martianrepublic.org && php artisan status:watch
 * Recipients: STATUS_ALERT_EMAILS (comma-separated) in .env.
 */
class StatusWatch extends Command
{
    protected $signature = 'status:watch {--dry-run : Show what would be sent without sending or saving}';

    protected $description = 'Email the operators when a subsystem on the status page stays down';

    private const STATE_KEY = 'status_watch_state';

    private const COMPONENTS = [
        'web_status' => 'Web server',
        'mysql_status' => 'Database',
        'marscoind_status' => 'Marscoin node',
        'blockexplorer' => 'Block explorer',
        'pebas_status' => 'Pebas (chain data for the web wallet)',
        'ipfs_status' => 'IPFS node',
        'blockchain_tracker_status' => 'BlockAnalyzer (records civic transactions)',
        'ballot_server_status' => 'Ballot server',
    ];

    public function handle(): int
    {
        $data = app(StatusController::class)->collectStatusData();
        $up = [];
        foreach (self::COMPONENTS as $key => $label) {
            $up[$label] = ($data[$key] ?? 'danger') === 'success';
        }

        $result = StatusAlertPolicy::evaluate($up, Cache::get(self::STATE_KEY, []), time());
        $failing = array_keys(array_filter($up, fn ($healthy) => ! $healthy));
        $this->line('Down: '.($failing ? implode(', ', $failing) : 'nothing'));

        if ($this->option('dry-run')) {
            $this->line('Would report as down: '.($result['down'] ? implode(', ', $result['down']) : 'nothing'));
            $this->line('Would report as recovered: '.($result['recovered'] ? implode(', ', $result['recovered']) : 'nothing'));

            return self::SUCCESS;
        }
        Cache::forever(self::STATE_KEY, $result['state']);

        if (! $result['down'] && ! $result['recovered']) {
            return self::SUCCESS;
        }
        $recipients = array_values(array_filter(array_map('trim', explode(',', (string) config('blockchain.status_alert_emails')))));
        if (! $recipients) {
            $this->warn('STATUS_ALERT_EMAILS is not set; nobody was told.');

            return self::SUCCESS;
        }

        $lines = [];
        if ($result['down']) {
            $lines[] = 'Down for at least '.(StatusAlertPolicy::DOWN_FOR_SECONDS / 60).' minutes:';
            foreach ($result['down'] as $name) {
                $lines[] = '  - '.$name;
            }
        }
        if ($result['recovered']) {
            $lines[] = ($lines ? "\n" : '').'Recovered:';
            foreach ($result['recovered'] as $name) {
                $lines[] = '  - '.$name;
            }
        }
        $stillDown = array_diff($failing, $result['down']);
        if ($stillDown) {
            $lines[] = "\nAlso down (already reported, or only just failed): ".implode(', ', $stillDown);
        }
        $lines[] = "\nStatus page: ".url('/status');

        $subject = $result['down']
            ? 'Martian Republic: '.implode(', ', $result['down']).' down'
            : 'Martian Republic: '.implode(', ', $result['recovered']).' recovered';
        Mail::raw(implode("\n", $lines), fn ($message) => $message->to($recipients)->subject($subject));
        $this->info('Sent: '.$subject);

        return self::SUCCESS;
    }
}
