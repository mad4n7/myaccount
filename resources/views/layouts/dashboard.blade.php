<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom_fonts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flat-admin.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="32x32">
    
    <!-- jQuery -->
    <script src="{{ asset('js/plugins/jquery.min.js') }}"></script> 
    <script src="{{ asset('js/plugins/jquery-ui.min.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('assets/js/vendor.js') }}"></script>  
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme/red.css') }}">
    <!-- Theme 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme/blue-sky.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme/blue.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme/yellow.css') }}">
    -->

    <title>{{ $page_title }} - {{ config('app.name', 'Cat & Mouse') }}</title>

    <!-- Styles -->
    

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    
        $(function() {
            /* select theme */
            $(".app").removeClass("app-blue-sky").removeClass("app-yellow").removeClass("app-red").removeClass("app-green").removeClass("app-default").addClass("app-green");    
            
            /* menu */
            $(".sidebar-toggle").bind("click", function (e) {
              $("#sidebar").toggleClass("active");
              $(".app-container").toggleClass("__sidebar");
            });

            $(".navbar-toggle").bind("click", function (e) {
              $("#navbar").toggleClass("active");
              $(".app-container").toggleClass("__navbar");
            });
            /* end menu */
        });    

    </script>
    @yield('header_tags')
</head>
<body>
  <div class="app app-default">

<aside class="app-sidebar" id="sidebar">
  <div class="sidebar-header">
      <a class="sidebar-brand" href="{{ url('/home') }}"><img src="{{ asset('images/catmouse-logo.png') }}" style="width: auto; height: 90px;" /></a>
    <button type="button" class="sidebar-toggle">
      <i class="fa fa-times"></i>
    </button>
  </div>
    <!-- menu -->
 
  <div class="sidebar-menu">
    <ul class="sidebar-nav">
        <li <?php if ( Request::is('home') || Request::is('home/*') ){ echo 'class="active"'; } ?> >
        <a href="{{ url('/home') }} ">
          <div class="icon">
            <i class="fa fa-tasks" aria-hidden="true"></i>
          </div>
          <div class="title">Dashboard</div>
        </a>
      </li>      
    <li class="dropdown <?php if ( Request::is('orders') || Request::is('orders/*') ){ echo ' active'; } ?> " >
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div class="icon">
            <i class="fa fa-cube" aria-hidden="true"></i>
          </div>
          <div class="title">Orders</div>
        </a>
        <div class="dropdown-menu">
          <ul>
            <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Options</li>
            <li><a href="{{ url('orders/create') }}">Add New Order</a></li>
            <li><a href="{{ url('orders') }}">My Orders</a></li>                        
          </ul>
        </div>
      </li>
    <li class="dropdown <?php if ( Request::is('invoices') || Request::is('invoices/*') ){ echo ' active'; } ?> " >
        <a href="{{ url('invoices') }}">
          <div class="icon">
            <i class="fa fa-money" aria-hidden="true"></i>
          </div>
          <div class="title">Invoices</div>
        </a>
        
      </li>          
      <li <?php if (Request::is('profile') || Request::is('profile/*')){ echo 'class="active"'; } ?> >
        <a href="{{ url('/profile') }}">
          <div class="icon">
            <i class="fa fa-user" aria-hidden="true"></i>
          </div>
          <div class="title">Profile</div>
        </a>
      </li>        
        <!-- end menu -->
    </ul>
  </div>
  <div class="sidebar-footer">
    <ul class="menu">
      <li>
        <a href="/" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-cogs" aria-hidden="true"></i>
        </a>
      </li>
      
      <li><a href="#"><span class="flag-icon flag-icon-us flag-icon-squared"></span></a></li>
    </ul>
  </div>
</aside>


<div class="app-container">

  <nav class="navbar navbar-default" id="navbar">
  <div class="container-fluid">
    <div class="navbar-collapse collapse in">
      <ul class="nav navbar-nav navbar-mobile">
        <li>
          <button type="button" class="sidebar-toggle">
            <i class="fa fa-bars"></i>
          </button>
        </li>
        <li class="logo">
          <a class="navbar-brand" href="{{ url('/home') }}"><img src="{{ asset('images/catmouse-logo-type.png') }}" style="height: 60%;" /> &nbsp;&nbsp;&nbsp; My Account</a>
        </li>
        
      </ul>
      <ul class="nav navbar-nav navbar-left">
        <li class="navbar-title">Dashboard</li>
        <br />
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
        <!--
        <li class="dropdown notification danger">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
            <div class="title">System Notifications</div>
            <div class="count">10</div>
          </a>
          <div class="dropdown-menu">
            <ul>
              <li class="dropdown-header">Notification</li>
              <li>
                <a href="#">
                  <span class="badge badge-danger pull-right">8</span>
                  <div class="message">
                    <div class="content">
                      <div class="title">New Order</div>
                      <div class="description">$400 total</div>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a href="#">
                  <span class="badge badge-danger pull-right">14</span>
                  Inbox
                </a>
              </li>
              <li>
                <a href="#">
                  <span class="badge badge-danger pull-right">5</span>
                  Issues Report
                </a>
              </li>
              <li class="dropdown-footer">
                <a href="#">View All <i class="fa fa-angle-right" aria-hidden="true"></i></a>
              </li>
            </ul>
          </div>
        </li>
        -->
        
        <?php if (Auth::check()) {  ?>
        <li class="dropdown profile">
          <a href="{{ url('/profile') }}" class="dropdown-toggle"  data-toggle="dropdown">
              <img class="profile-img" src="<?php echo \App\Http\Controllers\UserController::get_gravatar( Auth::user()->email, 80, 'mm','g', false, null ); ?>">
            <div class="title">Profile</div>
          </a>
          <div class="dropdown-menu">
            <div class="profile-info">
              <h4 class="username">{{ Auth::user()->name }}</h4>
            </div>
            <ul class="action">
              <li>
                <a href="{{ url('/profile') }}">
                  Profile
                </a>
              </li>                        
              <li>
                <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                  Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>                  
              </li>
            </ul>
          </div>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

  <div class="btn-floating" id="help-actions">
  <div class="btn-bg"></div>
  <!--
  <button type="button" class="btn btn-default btn-toggle" data-toggle="toggle" data-target="#help-actions">
    <i class="icon fa fa-plus"></i>
    <span class="help-text">Shortcut</span>
  </button>
  -->
  <div class="toggle-content">
    <ul class="actions">
      <li><a href="#">Website</a></li>
      <li><a href="#">Documentation</a></li>
      <li><a href="#">Issues</a></li>
      <li><a href="#">About</a></li>
    </ul>
  </div>
</div>

    <?php

    if( Session::get('msg_error') !== null){
        $msg_error = Session::get('msg_error');
    }
    elseif(Session::get('msg') !== null){
        $msg = Session::get('msg');
    } 

    if(!empty($msg) ) {
        $msg_modal = $msg;    
        echo '<div class="row">';
        echo '<div class="col-xs-1">&nbsp;</div>';
        echo '<div class="alert alert-success alert-dismissable"  role="alert">';
        echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>';
        echo '<span class="sr-only">Close</span></button>';
        echo $msg_modal;
        echo '</div><div class="col-xs-1">&nbsp;</div>';
        echo '</div>';

    }
    elseif(!empty($msg_error)) {
        $msg_modal = $msg_error;
        echo '<div class="row">';
        echo '<div class="col-xs-1">&nbsp;</div>';
        echo '<div class="alert alert-danger alert-dismissable"  role="alert">';
        echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>';
        echo '<span class="sr-only">Close</span></button>';
        echo $msg_modal;
        echo '</div><div class="col-xs-1">&nbsp;</div>';
        echo '</div>';
    }   
    else {
        if (Auth::check()) {
            //check e-mail confirmation
            $tmp_user_details = \App\User_detail::where('user_id', Auth::user()->id)->first();
            if($tmp_user_details->activated != 1) {        
                echo '<div class="row">';
                echo '<div class="col-xs-1">&nbsp;</div>';
                echo '<div class="alert alert-danger alert-dismissable"  role="alert">';
                echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>';
                echo '<span class="sr-only">Close</span></button>';
                echo 'We could not confirm your e-mail address.Please, check your e-mail to confirm your account.';
                echo '<br /> <a href="'.url('safe/token/resend').'">Click here to resend the confirmation code e-mail.</a>';
                echo '</div><div class="col-xs-1">&nbsp;</div>';
                echo '</div>';
            }     
        }
    }
    ?>    
    
<div class="row">
  <div class="col-xs-12">
    @yield('content')
  </div>
</div>

  <footer class="app-footer"> 
  <div class="row">
    <div class="col-xs-12">
      <div class="footer-copyright">
        Copyright © 2017 Cat & Mouse
      </div>
    </div>
  </div>
</footer>
</div>

  </div>
    
</body>
</html>

