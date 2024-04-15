<div wire:init="loadVotes">
<h5>Voting Progress</h5>
    <div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" style="width: {{ $yayPercent }}%" aria-valuenow="{{ $yayPercent }}" aria-valuemin="0" aria-valuemax="100">Yay {{ $yayPercent }}%</div>
    <div class="progress-bar progress-bar-danger" role="progressbar" style="width: {{ $nayPercent }}%" aria-valuenow="{{ $nayPercent }}" aria-valuemin="0" aria-valuemax="100">Nay {{ $nayPercent }}%</div>
    </div>
</div>