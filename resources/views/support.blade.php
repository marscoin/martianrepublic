<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Support</title>
  @include('partials.public-head')
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <div class="mr-page-header">
    <div class="container">
      <h1>Support</h1>
      <p>Get in touch with the Martian Republic team</p>
    </div>
  </div>

  <div class="mr-content">
    <div class="container">
      <div class="mr-form-card" style="max-width: 640px; margin: 0 auto;">

        @if ($errors->any())
          <div class="mr-alert mr-alert-error">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(session('message_sent'))
          <div class="mr-alert mr-alert-success">
            {{ session('message_sent') }}
          </div>
        @endif

        <form method="POST" action="{{ route('contact.send') }}">
          @csrf

          <div class="mr-form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your name" required>
          </div>

          <div class="mr-form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Your email address" required>
          </div>

          <div class="mr-form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Subject" required>
          </div>

          <div class="mr-form-group">
            <label for="text">Message</label>
            <textarea id="text" name="text" rows="6" placeholder="Your message" required>{{ old('text') }}</textarea>
          </div>

          <div class="mr-form-group">
            <button type="submit" class="mr-btn mr-btn-primary">Submit Message</button>
          </div>

        </form>

      </div>
    </div>
  </div>

  @include('partials.public-footer')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>
