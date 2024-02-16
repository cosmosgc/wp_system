<!-- resources/views/upload.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV</title>
</head>
<body>
    <form action="/submit_file" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">Converter para JSON</button>
    </form>
</body>
</html>
