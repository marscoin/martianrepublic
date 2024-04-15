<h5>Time Left: <div style="float: right;" id="timer-{{ $proposalId }}">00:00</div></h5>
<div class="progress progress-striped active">
    <div id="progressBar-{{ $proposalId }}" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
        <span class="sr-only">100% Complete</span>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const proposalId = "{{ $proposalId }}";
    const endTimeString = "{{ $endTime }}";
    const startTimeString = "{{ $startTime }}";
    const endTime = new Date(endTimeString).getTime();
    const startTime = new Date(startTimeString).getTime();
    
    const timer = document.getElementById('timer-' + proposalId);
    const progressBar = document.getElementById('progressBar-' + proposalId);


    const updateTimer = () => {
        const now = new Date().getTime();
        const totalDuration = endTime - startTime; // Total duration from the start to the end
        let remainingTime = endTime - now; // Time left until the end
        remainingTime = Math.max(0, remainingTime);
        let elapsedTime = totalDuration - remainingTime;

         // Calculate days, hours, minutes, and seconds
        const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        // Format the string to include days if there are any
        timer.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s";


        // Update progress bar
        let percentage = (1 - (remainingTime / totalDuration)) * 100;
        let elapsedPercentage = (elapsedTime / totalDuration) * 100;
        progressBar.style.width = elapsedPercentage + "%";
        progressBar.setAttribute("aria-valuenow", Math.round(elapsedPercentage));
        console.log("P"+ percentage);
        console.log("P2 "+ elapsedPercentage);
        

        // Change progress bar color based on time left
        if (elapsedPercentage >= 80) {
            progressBar.className = "progress-bar progress-bar-danger";
            progressBar.innerHTML = "Time almost up!";
        } else if (elapsedPercentage >= 50) {
            progressBar.className = "progress-bar progress-bar-warning";
            progressBar.innerHTML = Math.round(elapsedPercentage) + "% Complete";
        } else {
            progressBar.className = "progress-bar progress-bar-success";
            progressBar.innerHTML = Math.round(elapsedPercentage) + "% Complete";
        }

        if (remainingTime <= 0) {
            clearInterval(x);
            timer.innerHTML = "EXPIRED";
            progressBar.closest('.progress').classList.remove('progress-striped', 'active');
            progressBar.className = "progress-bar progress-bar-danger";
            progressBar.innerHTML = "Expired";
            progressBar.style.width = "100%";
        }
    };

    // Update the countdown every 1 second
    const x = setInterval(updateTimer, 1000);

    // Initialize the timer once at start
    updateTimer();
});
</script>