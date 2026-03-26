<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - 2FA Setup</title>
  @include('partials.public-head')
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .mr-2fa-input {
      width: 100%;
      height: 72px;
      font-size: 36px;
      font-weight: 700;
      font-family: var(--mr-font-mono);
      letter-spacing: 12px;
      text-align: center;
      background: var(--mr-void);
      border: 2px solid var(--mr-border-bright);
      border-radius: 12px;
      color: var(--mr-cyan);
      outline: none;
      transition: all 0.3s ease;
    }
    .mr-2fa-input:focus {
      border-color: var(--mr-cyan);
      box-shadow: 0 0 0 4px var(--mr-cyan-dim), 0 0 32px rgba(0,228,255,0.1);
    }
    .mr-2fa-input::placeholder {
      color: var(--mr-text-faint);
      font-size: 18px;
      letter-spacing: 2px;
    }
    .mr-2fa-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 64px;
      height: 64px;
      border-radius: 16px;
      background: var(--mr-cyan-dim);
      border: 1px solid rgba(0,228,255,0.2);
      margin: 0 auto 24px;
      font-size: 28px;
      color: var(--mr-cyan);
    }
    .mr-qr-display {
      display: flex;
      justify-content: center;
      margin-bottom: 24px;
    }
    .mr-qr-display img {
      background: #fff;
      padding: 12px;
      border-radius: 12px;
    }
    .mr-2fa-step {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 20px;
      font-size: 14px;
      color: var(--mr-text-dim);
    }
    .mr-2fa-step-num {
      flex-shrink: 0;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--mr-cyan-dim);
      border: 1px solid rgba(0,228,255,0.2);
      color: var(--mr-cyan);
      font-family: var(--mr-font-mono);
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .mr-result-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 72px;
      height: 72px;
      border-radius: 50%;
      margin: 0 auto 20px;
      font-size: 32px;
    }
    .mr-result-icon.success {
      background: rgba(52,211,153,0.1);
      border: 2px solid rgba(52,211,153,0.3);
      color: var(--mr-green);
    }
    .mr-result-icon.error {
      background: rgba(239,68,68,0.1);
      border: 2px solid rgba(239,68,68,0.3);
      color: var(--mr-red);
    }
  </style>
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <main class="mr-auth-page">
    <div class="container">

      @if (!is_null($qrcode_image))
        <div class="mr-form-card" style="max-width: 480px;">
          <div class="mr-2fa-icon">
            <i class="fa fa-shield-halved"></i>
          </div>

          <h2 style="font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Setup 2FA</h2>
          <p class="mr-form-sub" style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">Secure your account with two-factor authentication</p>

          <div class="mr-2fa-step">
            <span class="mr-2fa-step-num">1</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px;">Open Google Authenticator or Authy on your phone</span>
          </div>
          <div class="mr-2fa-step">
            <span class="mr-2fa-step-num">2</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px;">Scan the QR code below</span>
          </div>

          <div class="mr-qr-display">
            <div style="position: relative; display: inline-block; padding: 16px; background: var(--mr-void, #06060c); border: 1px solid rgba(0,228,255,0.2); border-radius: 10px;">
              <div style="position: absolute; top: 6px; left: 6px; width: 16px; height: 16px; border-top: 2px solid var(--mr-cyan, #00e4ff); border-left: 2px solid var(--mr-cyan, #00e4ff); border-top-left-radius: 4px;"></div>
              <div style="position: absolute; top: 6px; right: 6px; width: 16px; height: 16px; border-top: 2px solid var(--mr-cyan, #00e4ff); border-right: 2px solid var(--mr-cyan, #00e4ff); border-top-right-radius: 4px;"></div>
              <div style="position: absolute; bottom: 26px; left: 6px; width: 16px; height: 16px; border-bottom: 2px solid var(--mr-cyan, #00e4ff); border-left: 2px solid var(--mr-cyan, #00e4ff); border-bottom-left-radius: 4px;"></div>
              <div style="position: absolute; bottom: 26px; right: 6px; width: 16px; height: 16px; border-bottom: 2px solid var(--mr-cyan, #00e4ff); border-right: 2px solid var(--mr-cyan, #00e4ff); border-bottom-right-radius: 4px;"></div>
              <img src="data:image/png;base64, {{ $qrcode_image }}" alt="2FA QR Code">
              <div style="font-family: 'JetBrains Mono', monospace; font-size: 7px; letter-spacing: 3px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968); text-align: center; margin-top: 10px;">Scan with Authenticator</div>
            </div>
          </div>

          <div class="mr-2fa-step">
            <span class="mr-2fa-step-num">3</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px;">Enter the 6-digit code from the app</span>
          </div>

          <form method="POST" action="/twofa">
            @csrf
            <div class="mr-form-group">
              <input name="secret" id="secret" type="text" class="mr-2fa-input"
                     placeholder="000000" maxlength="6" inputmode="numeric"
                     autocomplete="one-time-code" tabindex="1" autofocus>
            </div>

            <div class="mr-form-group">
              <button type="submit" class="mr-btn mr-btn-primary" tabindex="2" style="font-family: 'JetBrains Mono', monospace; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase;">
                Verify &amp; Enable 2FA <i class="fa fa-check"></i>
              </button>
            </div>

            <input type="hidden" value="meow" class="local" name="local" />
          </form>

          <div class="mr-form-footer">
            <a href="/logout"><i class="fa fa-arrow-left"></i> &nbsp;Back to Login</a>
          </div>
        </div>
      @endif

      @if (!is_null($isvalid) && $isvalid)
        <div class="mr-form-card" style="max-width: 440px;">
          <div class="mr-result-icon success">
            <i class="fa fa-check"></i>
          </div>
          <h2>2FA Enabled</h2>
          <p class="mr-form-sub">Your account is now protected with two-factor authentication</p>

          <form method="POST" action="/check">
            @csrf
            <div class="mr-form-group">
              <a href="/wallet/dashboard" class="mr-btn mr-btn-primary">
                Go to Dashboard <i class="fa fa-arrow-right"></i>
              </a>
            </div>
            <input type="hidden" value="meow" class="local" name="local" />
          </form>
        </div>
      @endif

      @if (!is_null($isvalid) && !$isvalid)
        <div class="mr-form-card" style="max-width: 440px;">
          <div class="mr-result-icon error">
            <i class="fa fa-xmark"></i>
          </div>
          <h2>Setup Failed</h2>
          <p class="mr-form-sub">The verification code was incorrect. Please try again.</p>

          <form method="POST" action="/twofa">
            @csrf
            <div class="mr-form-group">
              <a href="/wallet/dashboard" class="mr-btn mr-btn-primary">
                Try Again <i class="fa fa-rotate"></i>
              </a>
            </div>
            <input type="hidden" value="meow" class="local" name="local" />
          </form>
        </div>
      @endif

    </div>
  </main>

  @include('partials.public-footer')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      let item = localStorage.getItem("key");
      if (item != null) {
        $(".local").val("true");
      } else {
        $(".local").val("false");
      }
    });
  </script>
</body>
</html>
