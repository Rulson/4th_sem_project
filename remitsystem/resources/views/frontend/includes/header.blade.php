<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/img/favicon.png')}}"/>

    <!-- All CSS -->
    <!-- fontAwesome -->
    <link rel="stylesheet" href="{{asset('assets/css/all.min.css')}}">
    <!-- 7 stroke icon -->
    <link rel="stylesheet" href="{{asset('assets/css/pe-icon-7-stroke.css')}}">
    <!-- Roysha icon -->
    <link rel="stylesheet" href="{{asset('assets/css/roysha-icons.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/xss/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

    <title>{{$application_obj->name}}</title>
  </head>
  <body class="home-two">

    <!- Header start -->
    <header class="header">
      <div class="container d-flex align-items-center">
          <a class="logo" href="#">
            <img src="{{asset('assets/images/home/logo-new-2.png')}}" alt="">
          </a>
          <h4 class="text-capitalize alt-font text-white font-weight-300 sm-width-100 xs-width-100" style="display: flex;
  align-items: center; color: #fff !important;
  justify-content: center;  margin: 0; font-size: 20px; margin-left: 10px;">
                        {{$application_obj->name}} </h4>
          <nav class="primary-menu text-md-right">
            <a id="mobile-menu-toggler" href="#"><i class="fas fa-bars"></i></a>
            <ul>
              <li class="current-menu-item "><a href="#"> Home</a></li>
              <li><a href="#">Send Money</a></li>
              <li><a href="#">Receive Money</a></li>
              <li class="">
                <a href="#"> Features </a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </nav>
      </div>
    </header>
