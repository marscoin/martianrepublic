<div>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <div wire:init="generateQRCode">
        <div id="spinner" wire:loading>
            <div class="loader" style="border: 16px solid #f3f3f3; /* Light grey */
                                        border-top: 16px solid #3498db; /* Blue */
                                        border-radius: 50%;
                                        width: 120px;
                                        height: 120px;
                                        animation: spin 2s linear infinite;">
            </div>
        </div>
        <div wire:loading.remove wire:poll.30000ms="generateQRCode">
            @if($qrCodeImage)
                <img src="data:image/png;base64,{{ $qrCodeImage }}" height="250" width="250">
            @endif
        </div>
        
    </div>
</div>
