<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Login</title>
  @include('partials.public-head')
  @livewireStyles
  <style>
    .mr-qr-toggle-btn {
      position: absolute;
      top: 16px;
      right: 16px;
      background: rgba(255,255,255,0.04);
      border: 1px solid var(--mr-border-bright);
      border-radius: 8px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--mr-text-dim);
      transition: all 0.25s ease;
      padding: 0;
      font-size: 16px;
    }
    .mr-qr-toggle-btn:hover {
      color: var(--mr-cyan);
      border-color: rgba(0,228,255,0.3);
      background: var(--mr-cyan-dim);
    }
    .mr-form-card { position: relative; }
    .mr-form-check {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--mr-text-dim);
      font-size: 14px;
    }
    .mr-form-check input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: var(--mr-mars);
      cursor: pointer;
    }
  </style>
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <div class="mr-auth-page">
    <div class="mr-auth-content">
      <div style="width: 100%; max-width: 480px;">

        <form method="POST" action="{{ route('login') }}">
          @csrf

          {{-- ===== LOGIN FORM CARD (Front) ===== --}}
          <div id="cardFront" class="mr-form-card">
            @if(false) {{-- QR Login: hidden until mobile app is ready --}}
            <button type="button" id="flip-btn" class="mr-qr-toggle-btn" title="Login with QR code">
              <i class="fa fa-qrcode"></i>
            </button>

            <h2>Welcome Back</h2>
            <p class="mr-form-sub">Sign in to the Martian Republic</p>

            @if(Session::has('message'))
              <div class="mr-alert mr-alert-success">
                <strong>{{ Session::get('message') }}</strong>
              </div>
            @endif

            <x-auth-validation-errors class="mr-alert mr-alert-error" :errors="$errors" />

            <div class="mr-form-group">
              <label for="login-email">Email</label>
              <input type="text" name="email" id="login-email" placeholder="Enter your email" tabindex="1">
            </div>

            <div class="mr-form-group">
              <label for="login-password">Password</label>
              <input type="password" name="password" id="login-password" placeholder="Enter your password" tabindex="2">
            </div>

            <div class="mr-form-row">
              <label class="mr-form-check">
                <input type="checkbox" name="remember" tabindex="3"> Remember me
              </label>
              <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            <button type="submit" class="mr-btn mr-btn-primary" tabindex="4">
              Sign In <i class="fa fa-arrow-right"></i>
            </button>

            <div class="mr-form-footer">
              Don't have an account? <a href="{{ route('register') }}">Create an account</a>
            </div>
          </div>

        </form>

        {{-- ===== QR LOGIN CARD (Back) ===== --}}
        <div id="cardBack" class="mr-qr-card">
          <button type="button" id="flip-btn4" class="mr-qr-toggle-btn" title="Back to login form">
            <i class="fa fa-arrow-left"></i>
          </button>

          <h2>QR Login</h2>
          <p class="mr-qr-desc">
            Scan with the Martian Republic Mobile App to securely log in.
          </p>

          <div class="mr-qr-wrap">
            @livewire('q-r-login')
          </div>

          <div class="mr-qr-secured">
            <img src="/assets/landing/img/secured.avif" alt="Secured">
            <span>Biometrically &amp;<br>Cryptographically Secured</span>
          </div>

          <div class="mr-form-footer">
            No account yet? <a href="{{ route('register') }}">Sign up with MartianRepublic Mobile App</a>
          </div>
        </div>

      </div>
    </div>

    @include('partials.public-footer')
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  @livewireScripts

  @endif

    <script>
    const front = document.getElementById('cardFront');
    const back = document.getElementById('cardBack');
    const btn = document.getElementById('flip-btn');
    const btn4 = document.getElementById('flip-btn4');

    function handleFlip() {
      front.style.display = (front.style.display === 'none') ? '' : 'none';
      if (front.style.display === 'none') {
        back.classList.add('visible');
      } else {
        back.classList.remove('visible');
      }
    }

    btn.addEventListener('click', handleFlip);
    btn4.addEventListener('click', handleFlip);
  </script>

  <script>
    let currentSid = '';

    document.addEventListener('DOMContentLoaded', () => {
      window.Livewire.on('sidUpdated', (data) => {
        currentSid = data[0].sid;
      });

      const checkAuthStatus = () => {
        if (!currentSid) return;

        fetch(`/api/checkauth?sid=${currentSid}`)
          .then(response => response.json())
          .then(data => {
            if (data.authenticated) {
              window.location.href = `/api/wauth?sid=${currentSid}`;
            }
          })
          .catch(error => console.error('Error checking authentication status:', error));
      };

      setInterval(checkAuthStatus, 5000);
    });
  </script>

</body>
</html>
