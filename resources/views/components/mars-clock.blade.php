{{-- Mars Clock Widget - Live MTC display with Darian calendar --}}
<div id="mars-clock-widget" class="mars-clock">
    <div class="mars-clock-header">
        <span class="mars-clock-live-dot"></span>
        <span class="mars-clock-label">MARS TIME</span>
    </div>
    <div class="mars-clock-body">
        <div class="mars-clock-row mars-clock-row-primary">
            <span class="mars-clock-mtc" id="mars-clock-mtc">--:--:-- MTC</span>
            <span class="mars-clock-sol" id="mars-clock-sol">Sol ---,---</span>
        </div>
        <div class="mars-clock-row mars-clock-row-secondary">
            <span class="mars-clock-darian" id="mars-clock-darian">--- --, ---</span>
            <span class="mars-clock-season" id="mars-clock-season">---</span>
        </div>
    </div>
    <div class="mars-clock-footer">
        <a href="/academy/mars-timekeeping" class="mars-clock-link">Learn about Mars Time</a>
    </div>
</div>

<style>
    .mars-clock {
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 10px;
        padding: 14px 18px 10px;
        font-family: 'Inter', sans-serif;
        color: var(--mr-text, #e4e4e7);
        max-width: 420px;
        position: relative;
        overflow: hidden;
    }
    .mars-clock::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg,
            transparent,
            var(--mr-cyan, #00e4ff) 30%,
            var(--mr-mars, #c84125) 70%,
            transparent
        );
        opacity: 0.5;
    }
    .mars-clock-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }
    .mars-clock-live-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--mr-cyan, #00e4ff);
        box-shadow: 0 0 6px rgba(0,228,255,0.6);
        animation: marsClockPulse 2s ease-in-out infinite;
    }
    .mars-clock-label {
        font-family: 'Orbitron', 'Inter', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2.5px;
        color: var(--mr-text-dim, #8a8998);
        text-transform: uppercase;
    }
    .mars-clock-body {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .mars-clock-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 12px;
    }
    .mars-clock-mtc {
        font-family: 'Orbitron', 'JetBrains Mono', monospace;
        font-size: 20px;
        font-weight: 600;
        color: var(--mr-cyan, #00e4ff);
        letter-spacing: 1px;
        text-shadow: 0 0 12px rgba(0,228,255,0.25);
        white-space: nowrap;
    }
    .mars-clock-sol {
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 13px;
        font-weight: 500;
        color: var(--mr-text, #e4e4e7);
        white-space: nowrap;
    }
    .mars-clock-darian {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: var(--mr-text-dim, #8a8998);
        white-space: nowrap;
    }
    .mars-clock-season {
        font-size: 12px;
        color: var(--mr-text-dim, #8a8998);
        white-space: nowrap;
    }
    .mars-clock-season .season-indicator {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        margin-right: 4px;
        vertical-align: middle;
        position: relative;
        top: -1px;
    }
    .mars-clock-season .season-indicator.season-spring { background: #4ade80; box-shadow: 0 0 4px rgba(74,222,128,0.4); }
    .mars-clock-season .season-indicator.season-summer { background: #facc15; box-shadow: 0 0 4px rgba(250,204,21,0.4); }
    .mars-clock-season .season-indicator.season-autumn { background: #f97316; box-shadow: 0 0 4px rgba(249,115,22,0.4); }
    .mars-clock-season .season-indicator.season-winter { background: #93c5fd; box-shadow: 0 0 4px rgba(147,197,253,0.4); }

    .mars-clock-footer {
        margin-top: 8px;
        text-align: right;
    }
    .mars-clock-link {
        font-size: 11px;
        color: var(--mr-text-faint, #5a5968);
        text-decoration: none;
        transition: color 0.2s;
    }
    .mars-clock-link:hover {
        color: var(--mr-cyan, #00e4ff);
    }

    /* Colon pulse on MTC time */
    .mars-clock-mtc .mtc-colon {
        animation: marsClockColonPulse 1.5s ease-in-out infinite;
    }

    @keyframes marsClockPulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 6px rgba(0,228,255,0.6); }
        50% { opacity: 0.4; box-shadow: 0 0 2px rgba(0,228,255,0.2); }
    }
    @keyframes marsClockColonPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>

<script>
(function() {
    'use strict';

    // ── NASA GISS: Earth time → Mars time ───────────────────────────────
    function earthToMarsTime(earthDate) {
        var millis = earthDate.getTime();
        var jdUT = 2440587.5 + (millis / 86400000);
        var ttMinusUtc = 69.184; // TT-UTC offset in seconds (current as of 2025)
        var jdTT = jdUT + (ttMinusUtc / 86400);
        var deltaJ2000 = jdTT - 2451545.0;

        // Mars mean anomaly
        var M = (19.3871 + 0.52402073 * deltaJ2000) % 360;
        // Angle of Fictitious Mean Sun
        var alphaFMS = (270.3871 + 0.524038496 * deltaJ2000) % 360;

        // Perturbers
        var perturbers = [
            [0.0071, 2.2353, 49.409],  [0.0057, 2.7543, 168.173],
            [0.0039, 1.1177, 191.837], [0.0037, 15.7866, 21.736],
            [0.0021, 2.1354, 15.704],  [0.0020, 2.4694, 95.528],
            [0.0018, 32.8493, 49.095]
        ];
        var toRad = Math.PI / 180;
        var PBS = 0;
        for (var p = 0; p < perturbers.length; p++) {
            var A = perturbers[p][0], tau = perturbers[p][1], phi = perturbers[p][2];
            PBS += A * Math.cos(((0.985626 * deltaJ2000 / tau) + phi) * toRad);
        }

        // Equation of Center
        var Mrad = M * toRad;
        var nuMinusM = (10.691 + 3.0e-7 * deltaJ2000) * Math.sin(Mrad)
            + 0.623 * Math.sin(2 * Mrad)
            + 0.050 * Math.sin(3 * Mrad)
            + 0.005 * Math.sin(4 * Mrad)
            + 0.0005 * Math.sin(5 * Mrad)
            + PBS;

        // Areocentric solar longitude (season indicator)
        var Ls = ((alphaFMS + nuMinusM) % 360 + 360) % 360;

        // Mars Sol Date
        var MSD = ((jdTT - 2451549.5) / 1.0274912517) + 44796.0 - 0.0009626;

        // Coordinated Mars Time (hours 0-24)
        var MTC = (24 * (((jdTT - 2451549.5) / 1.0274912517) + 44796.0 - 0.0009626)) % 24;
        if (MTC < 0) MTC += 24;

        return { MSD: MSD, MTC: MTC, Ls: Ls };
    }

    // ── Darian Calendar conversion ──────────────────────────────────────
    function msdToDarian(MSD) {
        var solsSinceEpoch = MSD - 94129.0;

        var marsYear = Math.floor(solsSinceEpoch / 668.5921);
        var solOfYear = Math.floor(solsSinceEpoch - marsYear * 668.5921);

        var months = [
            'Sagittarius','Dhanus','Capricornus','Makara','Aquarius','Kumbha',
            'Pisces','Mina','Aries','Mesha','Taurus','Rishabha',
            'Gemini','Mithuna','Cancer','Karka','Leo','Simha',
            'Virgo','Kanya','Libra','Tula','Scorpius','Vrishika'
        ];

        var remaining = solOfYear;
        var monthIndex = 0;
        for (var i = 0; i < 24; i++) {
            var isLastInQuarter = (i % 6 === 5);
            var daysInMonth = isLastInQuarter ? 27 : 28;
            if (remaining < daysInMonth) {
                monthIndex = i;
                break;
            }
            remaining -= daysInMonth;
            monthIndex = i + 1;
        }
        if (monthIndex >= 24) monthIndex = 23;

        return {
            year: marsYear,
            month: months[monthIndex],
            monthIndex: monthIndex,
            sol: remaining + 1
        };
    }

    // ── Season from Ls ──────────────────────────────────────────────────
    function getMarsSeason(Ls) {
        // Northern hemisphere seasons based on Ls
        if (Ls < 90)  return { name: 'Northern Spring', css: 'season-spring' };
        if (Ls < 180) return { name: 'Northern Summer', css: 'season-summer' };
        if (Ls < 270) return { name: 'Northern Autumn', css: 'season-autumn' };
        return { name: 'Northern Winter', css: 'season-winter' };
    }

    // ── Format helpers ──────────────────────────────────────────────────
    function pad2(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    function formatMTC(mtcHours) {
        var totalSeconds = Math.floor(mtcHours * 3600);
        var h = Math.floor(totalSeconds / 3600);
        var m = Math.floor((totalSeconds % 3600) / 60);
        var s = totalSeconds % 60;
        return '<span>' + pad2(h) + '</span>'
            + '<span class="mtc-colon">:</span>'
            + '<span>' + pad2(m) + '</span>'
            + '<span class="mtc-colon">:</span>'
            + '<span>' + pad2(s) + '</span>'
            + ' <span style="font-size:0.7em;opacity:0.7;">MTC</span>';
    }

    function formatSol(msd) {
        var solNum = Math.floor(msd);
        return 'Sol ' + solNum.toLocaleString('en-US');
    }

    function formatDarian(darian) {
        return darian.month + ' ' + darian.sol + ', ' + darian.year + ' DR';
    }

    function formatSeason(season) {
        return '<span class="season-indicator ' + season.css + '"></span>' + season.name;
    }

    // ── DOM references ──────────────────────────────────────────────────
    var elMTC     = document.getElementById('mars-clock-mtc');
    var elSol     = document.getElementById('mars-clock-sol');
    var elDarian  = document.getElementById('mars-clock-darian');
    var elSeason  = document.getElementById('mars-clock-season');

    // ── Update loop ─────────────────────────────────────────────────────
    function tick() {
        var now = new Date();
        var mars = earthToMarsTime(now);
        var darian = msdToDarian(mars.MSD);
        var season = getMarsSeason(mars.Ls);

        elMTC.innerHTML    = formatMTC(mars.MTC);
        elSol.textContent  = formatSol(mars.MSD);
        elDarian.textContent = formatDarian(darian);
        elSeason.innerHTML = formatSeason(season);
    }

    // Initial tick, then update every ~1.027 Earth seconds (1 Mars second)
    tick();
    setInterval(tick, 1027);
})();
</script>
