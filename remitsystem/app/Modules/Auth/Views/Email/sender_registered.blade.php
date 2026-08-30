<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Sender Registered</title>
</head>
<body>
@php
$route = route('sender.show',['sender_id' => $senderDetails->sender_id]);
@endphp
<h1>New Sender Registered</h1>
    <p>Sender Name:  {{ $senderDetails->full_name }}</p>
    <p>Sender Email: {{ $senderDetails->email }}</p>
    <p>Sender Phone: {{ $senderDetails->number }}</p>
    <p>Url: <a href="{{$route}}">{{$route}}</a></p>
</body>
</html>
