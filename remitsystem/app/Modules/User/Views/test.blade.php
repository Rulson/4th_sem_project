<html>
<head>
    <title>
        Test
    </title>
</head>
<body>
<form method="post" action="{{route('store.test')}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="image" class="form-control"
           accept="image/jpeg , image/jpg, image/gif, image/png ,application/pdf"
          >
    <button type="submit">Submit</button>
</form>
</body>
</html>