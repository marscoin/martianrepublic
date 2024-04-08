<div wire:poll.120s="fetchBlockNumber">
    <h5 class="content-title"><u>Block Time</u></h5>
    <div class="list-group">
        <div class="list-group-item">
            <h3 class="pull-right"><img src="/assets/landing/img/blocks.gif" style="height: 50px;"></h3>
            <h4 class="list-group-item-heading">{{ $blockNumber }}</h4>
            <p class="list-group-item-text"><small>Last Mined:</small>
                <small id="timeSinceLastBlock">{{ $timeSinceLastBlock }}</small>
            </p>
        </div>
    </div>
</div>
<script>
    timeSinceLastBlock = 0;
    function updateTime() {
        const minedAt = @json($this->lastBlockMinedAt->getTimestamp()); // Pass the timestamp from PHP to JS
        const now = Math.floor(Date.now() / 1000); // Get current timestamp in seconds
        timeSinceLastBlock = now - minedAt; // Calculate the difference in seconds
        let minutes = Math.floor(timeSinceLastBlock / 60);
        let seconds = timeSinceLastBlock % 60;
        
        // Update the time display
        document.getElementById('timeSinceLastBlock').textContent = minutes + " minutes " + seconds + " seconds ago";
    }

    window.addEventListener('block-update', event => {
        timeSinceLastBlock = 0;
    });
    
    // Initial call to function to set correct time immediately
    updateTime();

    // Update the time display every second
    setInterval(updateTime, 1000);
</script>