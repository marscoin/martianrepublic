<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - 2FA Challenge</title>
  @include('partials.public-head')
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
    .mr-2fa-hint {
      text-align: center;
      font-size: 13px;
      color: var(--mr-text-faint);
      margin-top: 16px;
      font-family: var(--mr-font-mono);
    }
    .mr-spinner .fa-arrow-right { display: inline; }
    .mr-spinner .fa-spinner { display: none; }
    .mr-spinner.submitting .fa-arrow-right { display: none; }
    .mr-spinner.submitting .fa-spinner { display: inline; }
  </style>
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <main class="mr-auth-page">
    <div class="container">
      <div class="mr-form-card" style="max-width: 440px;">

        <div class="mr-2fa-icon">
          <i class="fa fa-shield-halved"></i>
        </div>

        <h2>2FA Verification</h2>
        <p class="mr-form-sub">Enter the 6-digit code from your authenticator app</p>

        <form id="form" method="POST" action="/twofachallenge">
          @csrf

          <div class="mr-form-group">
            <input name="secret" id="secret" type="text" class="mr-2fa-input"
                   placeholder="000000" maxlength="6" inputmode="numeric"
                   autocomplete="one-time-code" tabindex="1" autofocus>
          </div>

          <div class="mr-form-group">
            <button type="submit" class="mr-btn mr-btn-primary mr-spinner" id="submitBtn" tabindex="2">
              Verify &nbsp;
              <i class="fa fa-arrow-right"></i>
              <i class="fa fa-spinner fa-spin"></i>
            </button>
          </div>

          <input type="hidden" value="meow" class="local" name="local" />

          <p class="mr-2fa-hint">Code auto-submits when 6 digits entered</p>
        </form>

        <div class="mr-form-footer">
          <a href="/logout"><i class="fa fa-arrow-left"></i> &nbsp;Back to Login</a>
        </div>

      </div>
    </div>
  </main>

  @include('partials.public-footer')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      // Check for local wallet key
      let item = localStorage.getItem("key");
      if (item != null && item.split(" ").length === 12) {
        $(".local").val("true");
      } else {
        $(".local").val("false");
      }

      // Auto-submit on 6 digits
      $("#secret").on("keyup input", function() {
        if ($(this).val().length >= 6) {
          $("#submitBtn").addClass("submitting");
          $("#form").submit();
        }
      });
    });
  </script>
</body>
</html>
