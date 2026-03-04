<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Reset Password</title>
  @include('partials.public-head')
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <main class="mr-auth-page">
    <div class="container">
      <div class="mr-form-card">

        <h2>Set New Password</h2>
        <p class="mr-form-sub">Choose a strong password for your account</p>

        @if (session('status'))
          <div class="mr-alert mr-alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
          <div class="mr-alert mr-alert-error">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="mr-form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Your email" tabindex="1" :value="old('email', $request->email)">
          </div>

          <div class="mr-form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" placeholder="New password" tabindex="2">
          </div>

          <div class="mr-form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" tabindex="3">
          </div>

          <div class="mr-form-group">
            <button type="submit" class="mr-btn mr-btn-primary" tabindex="4">Reset Password <i class="fa fa-arrow-right"></i></button>
          </div>

          <div class="mr-form-footer">
            <a href="{{ route('login') }}">Back to Login</a>
          </div>
        </form>

      </div>
    </div>
  </main>

  @include('partials.public-footer')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>
