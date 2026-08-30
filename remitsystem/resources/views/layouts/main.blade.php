<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.main.head')
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
@include('layouts.partials.main.header')
<div class="app-body">
@include('layouts.partials.main.sidebar')
@include('layouts.partials.main.content')
</div>
@include('layouts.partials.main.footer')
@yield('page-script')
</body>

</html>
