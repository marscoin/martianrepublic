<div wire:poll.60s="fetchIntervals">
    @php
        $points = $intervals;
        $count = count($points);
    @endphp

    @if($count > 1)
        @php
            $maxVal = max($points) ?: 1;
            $minVal = min($points);
            $w = 240;
            $h = 40;
            $padding = 2;
            $usableH = $h - ($padding * 2);
            $usableW = $w - ($padding * 2);
            $step = $usableW / max($count - 1, 1);

            // Build SVG polyline points
            $svgPoints = [];
            $areaPoints = [];
            foreach ($points as $i => $val) {
                $x = $padding + ($i * $step);
                $y = $padding + $usableH - (($val - $minVal) / max($maxVal - $minVal, 1) * $usableH);
                $svgPoints[] = round($x, 1) . ',' . round($y, 1);
                $areaPoints[] = round($x, 1) . ',' . round($y, 1);
            }
            // Close area path
            $lastX = $padding + (($count - 1) * $step);
            $firstX = $padding;
            $areaPoints[] = round($lastX, 1) . ',' . ($h - $padding);
            $areaPoints[] = round($firstX, 1) . ',' . ($h - $padding);
            $lineStr = implode(' ', $svgPoints);
            $areaStr = implode(' ', $areaPoints);
            $targetInterval = 120; // 2 min target
        @endphp

        <div style="margin-top: 6px;">
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-bottom: 6px;">
                Block Intervals <span style="color: var(--mr-text-faint, #5a5968);">(last {{ $count }})</span>
            </div>
            <svg width="100%" viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none" style="display: block; border-radius: 4px; background: var(--mr-dark, #0c0c16);">
                {{-- Target line at 2min --}}
                @php
                    $targetY = $padding + $usableH - (($targetInterval - $minVal) / max($maxVal - $minVal, 1) * $usableH);
                    $targetY = max($padding, min($h - $padding, $targetY));
                @endphp
                <line x1="{{ $padding }}" y1="{{ round($targetY, 1) }}" x2="{{ $w - $padding }}" y2="{{ round($targetY, 1) }}" stroke="var(--mr-cyan, #00e4ff)" stroke-width="0.5" stroke-dasharray="3,3" opacity="0.4"/>

                {{-- Area fill --}}
                <polygon points="{{ $areaStr }}" fill="url(#sparkGrad)" opacity="0.3"/>
                {{-- Line --}}
                <polyline points="{{ $lineStr }}" fill="none" stroke="var(--mr-cyan, #00e4ff)" stroke-width="1.5" stroke-linejoin="round" stroke-linecap="round"/>
                {{-- Last point dot --}}
                @php
                    $lastPoint = end($svgPoints);
                    $coords = explode(',', $lastPoint);
                @endphp
                <circle cx="{{ $coords[0] }}" cy="{{ $coords[1] }}" r="2.5" fill="var(--mr-cyan, #00e4ff)">
                    <animate attributeName="opacity" values="1;0.4;1" dur="2s" repeatCount="indefinite"/>
                </circle>

                <defs>
                    <linearGradient id="sparkGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--mr-cyan, #00e4ff)" stop-opacity="0.5"/>
                        <stop offset="100%" stop-color="var(--mr-cyan, #00e4ff)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>

            <div style="display: flex; justify-content: space-between; margin-top: 6px; font-family: 'JetBrains Mono', monospace; font-size: 10px;">
                <span style="color: var(--mr-text-faint, #5a5968);">
                    Avg <span style="color: var(--mr-cyan, #00e4ff);">{{ gmdate('i\ms\s', (int) round($avgInterval)) }}</span>
                </span>
                @if($currentDifficulty > 0)
                    <span style="color: var(--mr-text-faint, #5a5968);">
                        Diff <span style="color: var(--mr-text-dim, #8a8998);">{{ number_format($currentDifficulty, 2) }}</span>
                    </span>
                @endif
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 12px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 10px;">
            Gathering block data...
        </div>
    @endif
</div>
