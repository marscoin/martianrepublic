<footer class="mr-footer">
  <div class="container">
    <div class="mr-footer-grid">
      <div>
        <a href="/" class="mr-footer-brand">
          <img src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic">
          Martian Republic
        </a>
        <p class="mr-footer-desc">An initiative of the Marscoin&trade; Foundation, Inc., dedicated to advancing blockchain technology for space exploration and Martian self-governance.</p>
        <ul class="mr-footer-social">
          <li><a href="https://twitter.com/marscoinorg" target="_blank" title="X"><i class="fa-brands fa-x-twitter"></i></a></li>
          <li><a href="https://facebook.com/marscoin" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a></li>
          <li><a href="https://discord.gg/6vVKH6QdYb" target="_blank" title="Discord"><i class="fa-brands fa-discord"></i></a></li>
          <li><a href="https://github.com/marscoin/martianrepublic" target="_blank" title="GitHub"><i class="fa-brands fa-github"></i></a></li>
        </ul>
      </div>
      <div>
        <h5>Platform</h5>
        <ul class="mr-footer-links">
          <li><a href="/signup">Create Account</a></li>
          <li><a href="/login">Login</a></li>
          <li><a href="/status">Server Status</a></li>
        </ul>
      </div>
      <div>
        <h5>Resources</h5>
        <ul class="mr-footer-links">
          <li><a href="https://marscoin.gitbook.io/marscoin-documentation/" target="_blank">Documentation</a></li>
          <li><a href="https://github.com/marscoin/martianrepublic" target="_blank">Source Code</a></li>
          <li><a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" target="_blank">iOS App</a></li>
        </ul>
      </div>
      <div>
        <h5>Legal</h5>
        <ul class="mr-footer-links">
          <li><a href="/privacy">Privacy Policy</a></li>
          <li><a href="/tos">Terms of Service</a></li>
          <li><a href="/support">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <div class="mr-footer-bottom">
      <p>Copyright &copy; 2014&ndash;<?=date('Y')?> The Marscoin Foundation, Inc.</p>
      <div class="mr-footer-bottom-links">
        <a href="/status">Server Status</a>
        <a href="/privacy">Privacy</a>
      </div>
    </div>
  </div>
</footer>
<script>
(function() {
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle) {
    toggle.addEventListener('click', function() {
      nav.classList.toggle('mr-nav-mobile-open');
    });
  }
})();
</script>
