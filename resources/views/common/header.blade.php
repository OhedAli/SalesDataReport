<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Slim">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/slim/img/slim-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/slim">
    <meta property="og:title" content="Slim">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://themepixels.me/slim/img/slim-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/slim/img/slim-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">

    <title>AutoProtect USA</title>

    <!-- vendor css -->
    <link href="{{asset('app/lib/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/Ionicons/css/ionicons.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('app/lib/datatables/css/jquery.dataTables.css')}}" rel="stylesheet">
    <!-- Slim CSS -->

    <link rel="stylesheet" href="{{asset('app/css/slim.css')}}">


  </head>
  <body>
  
 <div class="slim-header">
      <div class="container">
        <div class="slim-header-left">
          <h2 class="slim-logo"><a href="#"><img src="/images/logo.png" width="" height="" alt="Site Logo"></a></h2>

      
        </div><!-- slim-header-left -->
        <div class="slim-header-right">
          
          
          <div class="dropdown dropdown-c">
            <a href="#" class="logged-user" data-toggle="dropdown">
             @if (Auth::user()->avatar != '')
                <img  src="/storage/{{Auth::user()->avatar}}" alt="profile-avatar">
                @else
                <img src="/images/icon_profile.png" alt="">
                @endif
              
              <span>{{ Auth::user()->name }}</span>
              <i class="fa fa-angle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <nav class="nav">
                <a href="{{ route('view.profile') }}" class="nav-link"><i class="icon ion-person"></i> View Profile</a>
                <!--<a href="page-edit-profile.html" class="nav-link"><i class="icon ion-compose"></i> Edit Profile</a>
                <a href="page-activity.html" class="nav-link"><i class="icon ion-ios-bolt"></i> Activity Log</a>
                <a href="page-settings.html" class="nav-link"><i class="icon ion-ios-gear"></i> Account Settings</a>-->
                <form method="POST" action="{{ route('logout') }}">
                @csrf
            <a href="javascript:void(0);" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link"><i class="icon ion-forward"></i>  {{ __('Logout') }}</a>
                </form>
              </nav>
            </div><!-- dropdown-menu -->
          </div><!-- dropdown -->
        </div><!-- header-right -->
      </div><!-- container -->
    </div><!-- slim-header -->