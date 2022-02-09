<div class="mainnav">
  <div class="container">
    <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="fa fa-bars"></i>
    </a>
    <nav class="collapse mainnav-collapse" role="navigation">
      <form class="mainnav-form pull-right" role="search">
        <input type="text" class="form-control input-md mainnav-search-query" placeholder="Search">
        <button class="btn btn-sm mainnav-form-btn"><i class="fa fa-search"></i></button>
      </form>
      <ul class="mainnav-menu">
        @if ($active === "dashboard")
        <li class="dropdown active">
        @else
        <li class="dropdown">
        @endif
        	<a href="https://martianrepublic.org/wallet/dashboard">
        	Dashboard
        	</a>
        </li>

      </ul>
    </nav>
  </div> <!-- /.container -->
</div> <!-- /.mainnav -->
