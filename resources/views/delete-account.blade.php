<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Deleting your account</title>
  @include('partials.public-head')
</head>

<body class="mr-theme">

  @include('partials.public-nav')

  <div class="mr-page-header">
    <div class="container">
      <h1>Deleting your account</h1>
      <p>Martian Republic app and martianrepublic.org, by the Marscoin Foundation</p>
    </div>
  </div>

  <div class="mr-content">
    <div class="container">
      <div style="max-width: 800px; margin: 0 auto;">

        <h2>How to delete your account</h2>
        <ol>
          <li>Open the Martian Republic app.</li>
          <li>Go to <strong>Settings</strong>.</li>
          <li>Choose <strong>Delete account</strong> and confirm.</li>
        </ol>
        <p>You can also write to <a href="mailto:hello@marscoinfoundation.org">hello@marscoinfoundation.org</a> or use the <a href="/support">contact form</a>, and we will close the account for you.</p>

        <h2>What happens</h2>
        <ul>
          <li>Your Republic account is closed and its sign-in is revoked.</li>
          <li>Every wallet is removed from your phone.</li>
          <li>Your coins stay on the Marscoin blockchain. You need your twelve words to reach them again; we do not have them and cannot recover them.</li>
        </ul>

        <h2>What is voluntary</h2>
        <p>You can use the wallet without giving the Republic any personal information. Applying for citizenship is voluntary. If you apply, you choose to publish a name, a photo, a short video and a statement as your public record.</p>

        <h2>What cannot be removed</h2>
        <p>The Martian Republic keeps its civic records on a public blockchain and on IPFS, so that anyone can audit them. A citizenship application, endorsements and votes that you published are part of that permanent public record. Nobody, including the Republic and the Marscoin Foundation, can alter or delete them. Closing your account does not remove them.</p>

        <h2>Questions</h2>
        <p>Write to <a href="mailto:hello@marscoinfoundation.org">hello@marscoinfoundation.org</a>. See also our <a href="/privacy">Privacy Policy</a>.</p>

      </div>
    </div>
  </div>

  @include('partials.public-footer')

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>
