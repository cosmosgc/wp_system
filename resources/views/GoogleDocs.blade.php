<!DOCTYPE html>
<html>
<head>
    <title>Upload Docs</title>
</head>
<body>
    <form action="/process_doc" method="post">
        @csrf
        <input type="text" name="google_docs" id="">
        <button type="submit">Inserir texto do documento</button>
    </form>
</body>
</html>