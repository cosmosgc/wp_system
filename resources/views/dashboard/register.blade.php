@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-content">
            <h1>Cadastro de Usuário</h1>
            <div class="col-md-6 offset-md-3 mt-5">
                <!-- Formulário usando Bootstrap -->
                <form method="POST" action="{{ route('processEditor') }}" class="needs-validation card card-medium" novalidate>
                    <div class="card-body">
                        @csrf
                    
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="name" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="sobrenome">Sobrenome</label>
                                <input type="text" class="form-control" id="sobrenome" name="surname" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf">
                                <h4>Optional</h4>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nick">Nick</label>
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
                            <h4>Optional</h4>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="isAdmin">
                            <label class="form-check-label" for="flexCheckDefault">Admin</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Formulário de Cadastro de Usuário -->
    </div>
@endsection