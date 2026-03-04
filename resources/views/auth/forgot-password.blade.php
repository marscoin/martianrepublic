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

        <h2>Reset Password</h2>
        <p class="mr-form-sub">Enter your email and we'll send you a reset link</p>

        <x-auth-validation-errors class="mr-alert mr-alert-error" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
          @csrf

          <div class="mr-form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
          </div>

          <div class="mr-form-group">
            <button type="submit" class="mr-btn mr-btn-primary">Send Reset Link</button>
          </div>

          <div class="mr-form-footer">
            <a href="{{ route('login') }}">Back to Login</a>
          </div>

        </form>

      </div>
    </div>
  </main>

  @include('partials.public-footer')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>
