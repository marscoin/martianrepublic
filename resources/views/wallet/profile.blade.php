<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>My Profile - Martian Republic</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <link rel="shortcut icon" href="/assets/favicon.ico">
</head>

<body class=" ">

<div id="wrapper">

  <header class="navbar navbar-inverse" role="banner">

    <div class="container">

      <div class="navbar-header">
        @include('wallet.header')
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">
         @include('wallet.navbarleft')
         @include('wallet.navbarright')
      </nav>
    </div> <!-- /.container -->

  </header>
 @include('wallet.mainnav', array('active'=>'dashboard'))

  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-5">

          <div class="profile-avatar">
            @php
              $avatarLink = $citizen->avatar_link ?? '/assets/citizen/generic_profile.jpg';
            @endphp
            <img src="{{ $avatarLink }}" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-width: 200px;">
          </div> <!-- /.profile-avatar -->

          <div class="list-group">

            <a href="/wallet/dashboard" class="list-group-item">
              <i class="fa fa-dashboard text-primary"></i> &nbsp;&nbsp;Dashboard
              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="/wallet/dashboard/hd" class="list-group-item">
              <i class="fa fa-bitcoin text-primary"></i> &nbsp;&nbsp;Wallet
              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="/citizen/all" class="list-group-item">
              <i class="fa fa-users text-primary"></i> &nbsp;&nbsp;Citizen Registry
              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="/forum" class="list-group-item">
              <i class="fa fa-comments text-primary"></i> &nbsp;&nbsp;Forum
              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

            <a href="/logbook/all" class="list-group-item">
              <i class="fa fa-book text-primary"></i> &nbsp;&nbsp;Logbook
              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a>

          </div> <!-- /.list-group -->

        </div> <!-- /.col -->


        <div class="col-md-6 col-sm-7">

          <h3>{{ $user->fullname ?? $user->email }}</h3>

          @if($citizen)
            <h5 class="text-muted">
              @if($profile->citizen)
                <span class="label label-success"><i class="fa fa-check"></i> Citizen</span>
              @elseif($profile->general_public)
                <span class="label label-info"><i class="fa fa-user"></i> General Public</span>
              @else
                <span class="label label-default"><i class="fa fa-user"></i> Member</span>
              @endif
              @if($citizen->displayname)
                &nbsp; {{ $citizen->displayname }}
              @endif
            </h5>
          @else
            <h5 class="text-muted">
              <span class="label label-default"><i class="fa fa-user"></i> Member</span>
            </h5>
          @endif

          <hr>

          <ul class="icons-list">
            <li><i class="icon-li fa fa-envelope"></i> {{ $user->email }}</li>
            @if($citizen && $citizen->public_address)
              <li><i class="icon-li fa fa-key"></i> <small>{{ $citizen->public_address }}</small></li>
            @endif
            <li><i class="icon-li fa fa-calendar"></i> Member since {{ $user->created_at ? $user->created_at->format('F Y') : 'N/A' }}</li>
            @if($profile && $profile->endorse_cnt > 0)
              <li><i class="icon-li fa fa-thumbs-up"></i> {{ $profile->endorse_cnt }} endorsement(s)</li>
            @endif
          </ul>

          <hr>

          <h4 class="content-title">Account Status</h4>

          <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-6">
              <div class="well text-center" style="margin-bottom: 10px;">
                <i class="fa fa-shield fa-2x text-{{ $profile && $profile->twofaset ? 'success' : 'danger' }}"></i>
                <h5>2FA {{ $profile && $profile->twofaset ? 'Enabled' : 'Not Set' }}</h5>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="well text-center" style="margin-bottom: 10px;">
                <i class="fa fa-{{ $profile && $profile->wallet_open ? 'unlock' : 'lock' }} fa-2x text-{{ $profile && $profile->wallet_open ? 'success' : 'warning' }}"></i>
                <h5>Wallet {{ $profile && $profile->wallet_open ? 'Open' : 'Locked' }}</h5>
              </div>
            </div>
          </div>

          @if($citizen)
          <h4 class="content-title">Citizen Details</h4>
          <table class="table table-striped">
            <tbody>
              @if($citizen->firstname)
              <tr>
                <td><strong>First Name</strong></td>
                <td>{{ $citizen->firstname }}</td>
              </tr>
              @endif
              @if($citizen->lastname)
              <tr>
                <td><strong>Last Name</strong></td>
                <td>{{ $citizen->lastname }}</td>
              </tr>
              @endif
              @if($citizen->displayname)
              <tr>
                <td><strong>Display Name</strong></td>
                <td>{{ $citizen->displayname }}</td>
              </tr>
              @endif
              @if($citizen->public_address)
              <tr>
                <td><strong>Public Address</strong></td>
                <td><small>{{ $citizen->public_address }}</small></td>
              </tr>
              @endif
            </tbody>
          </table>
          @endif

        </div> <!-- /.col -->


        <div class="col-md-3">
          <h5 class="content-title">Quick Links</h5>

          <div class="list-group">

            <a href="/wallet/dashboard/hd" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-bitcoin text-primary"></i></h3>
                <h5 class="list-group-item-heading">Wallet</h5>
                <p class="list-group-item-text">Manage your Marscoin</p>
            </a>

            <a href="/congress/voting" class="list-group-item">
              <h3 class="pull-right"><i class="fa fa-gavel text-primary"></i></h3>
              <h5 class="list-group-item-heading">Congress</h5>
              <p class="list-group-item-text">Vote on proposals</p>
            </a>

            <a href="/logbook/all" class="list-group-item">
              <h3 class="pull-right"><i class="fa fa-book text-primary"></i></h3>
              <h5 class="list-group-item-heading">Logbook</h5>
              <p class="list-group-item-text">Research entries</p>
            </a>
          </div> <!-- /.list-group -->

          <br>

          @if($profile && !$profile->citizen && $citizen)
          <div class="alert alert-info">
            <h5><i class="fa fa-info-circle"></i> Become a Citizen</h5>
            <p>You need endorsements from existing citizens to become a full Citizen of the Martian Republic.</p>
            <p><strong>Endorsements: {{ $profile->endorse_cnt ?? 0 }}</strong></p>
            <a href="/citizen/all" class="btn btn-info btn-sm">View Citizen Registry</a>
          </div>
          @endif

        </div> <!-- /.col -->

      </div> <!-- /.row -->

      <br><br>

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->


@include('footer')

<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>


</body>
</html>
