<html>
<head>
    <title>
        Import Excel
    </title>
</head>
<body>
<h1>Import Excel</h1>
<form method="post" action="{{route('store.import')}}" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="import_file" class="form-control">
    <button type="submit">Submit</button>
</form>
</body>
</html>
