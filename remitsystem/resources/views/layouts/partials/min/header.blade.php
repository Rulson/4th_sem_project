<head>
    <meta charset="utf-8">
    <title>{{getAppDetailsGeneral()->name}} @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.png')}}">
    <title>@yield('title') | {{$application_obj->name}}</title>
    <link href="{{asset('assets/coreUI/vendors/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/coreUI/vendors/css/simple-line-icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/coreUI/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('assets/coreUI/vendors/css/daterangepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/coreUI/vendors/css/select2.min.css')}}" rel="stylesheet">

</head>
