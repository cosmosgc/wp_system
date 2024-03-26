@extends('layouts.app')

@section('content')

<style>
    .two-column-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.two-column-form .form-group {
    flex: 1 1 50%;
    padding-right: 15px;
}
.two-column-form button{
    flex: 1 1 100%;
    margin-top: 10px;
}
.two-column-form label {
    cursor: pointer;
}

@media (max-width: 768px) {
    .two-column-form .form-group {
        flex: 1 1 100%;
        padding-right: 0;
    }
}

</style>
    <div class="dashboard-content">
        <div class="dashboard-content">
            <h1>Cadastro de Usuário</h1>
            <div class="col-md-12">
                <!-- Formulário usando Bootstrap -->
                <form method="POST" action="{{ route('processEditor') }}" class="needs-validation card card-medium" novalidate>
                    <div class="card-body ">
                        @csrf

                        <div class="form-row two-column-form">
                            <div class="form-group col-md-12">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="name" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="sobrenome">Sobrenome</label>
                                <input type="text" class="form-control" id="sobrenome" name="surname" required>
                            </div>
                        </div>

                        <div class="form-row two-column-form">
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf">
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nick">Apelido (Nickname)</label>
                                <input type="text" class="form-control" id="nick" name="nickname" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="senha">Senha</label>
                                <input type="password" class="form-control" id="senha" name="password" required>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="cnpj">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj">
                            <h4>Opcional</h4>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="isAdmin">
                            <label class="form-check-label" for="flexCheckDefault">Admin</label>
                        </div>

                        <button type="submit" class="btn btn-primary custom-sub">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Formulário de Cadastro de Usuário -->
    </div>
@endsection
