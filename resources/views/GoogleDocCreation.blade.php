<!DOCTYPE html>
<html>
<head>
    <title>Create Docs</title>
</head>
<body>
    <form action="/create_doc" method="post">
        @csrf
        <input type="text" name="title" id="title">
        <input type="text" name="content" id="content">
        <button type="submit">Inserir texto do documento</button>
    </form>
</body>
</html>