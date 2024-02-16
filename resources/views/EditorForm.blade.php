<form method="POST" action="{{ route('processEditor') }}">

    @csrf <!-- {{ csrf_field() }} -->

    <input type="text" name="name" placeholder="nome">
    <input type="text" name="surname" placeholder="sobrenome">
    <input type="text" name="cpf" placeholder="cpf">
    <input type="text" name="nickname" placeholder="nick">
    <input type="text" name="password" placeholder="senha">
    <input type="text" name="email" placeholder="email">

    <input type="submit" value="salvar">
</form>