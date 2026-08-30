<!DOCTYPE html>
<html lang="en">
<?php
$color = '';
if(str_contains(request()->getHttpHost() ,'nepalpaisa')){
    $color = 'nepal-paisa';
} elseif (str_contains(request()->getHttpHost() , 'dollarrupiya')){
    $color = 'dollar-rupiya';
} else {
    $color = 'cash-nepal';
}

?>
@include('layouts.partials.min.header')
<body class="app flex-row align-items-center {{ $color }}">
@yield('content')
@include('layouts.partials.min.footer')
</body>
</html>
