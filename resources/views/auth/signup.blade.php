<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Create Account</title>
  @include('partials.public-head')
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <main class="mr-auth-page">
    <div class="container">
      <div class="mr-form-card">

        <h2>Join the Republic, Citizen</h2>
        <p class="mr-form-sub">Create your account and begin your journey to Mars.</p>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mr-alert mr-alert-error" :errors="$errors" />

        @if(Session::has('message'))
          <div class="mr-alert mr-alert-success">{{ Session::get('message') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
          @csrf

          <div class="mr-form-group">
            <label for="name">Your Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" tabindex="1" required>
          </div>

          <div class="mr-form-group">
            <label for="email">Email Address</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Your Email" tabindex="2" required>
          </div>

          <div class="mr-form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="new-password" tabindex="3" required>
          </div>

          <div class="mr-form-group">
            <button type="submit" class="mr-btn mr-btn-primary" tabindex="4">Create My Account</button>
          </div>

          <div class="mr-form-footer">
            Already have an account? <a href="{{ route('login') }}">Login</a>
          </div>

        </form>

      </div>
    </div>
  </main>

  @include('partials.public-footer')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>
