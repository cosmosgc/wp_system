@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-content">
            <h1>Cadastro de Usuário</h1>
        
            <!-- Formulário usando Bootstrap -->
            <form method="POST" action="{{ route('processEditor') }}">
                @csrf <!-- {{ csrf_field() }} -->

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" class="form-control" id="sobrenome" name="surname" required>
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
        
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nick">Nick</label>
                        <input type="text" class="form-control" id="nick" name="nickname" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" id="senha" name="password" required>
                    </div>
                </div>
        
                <div class="form-group col-md-6">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="isAdmin">
                    <label class="form-check-label" for="flexCheckDefault">
                      Admin
                    </label>
                  </div>
        
                <button type="submit" class="btn btn-primary">Cadastrar</button>


            </form>
        </div>
        <!-- Formulário de Cadastro de Usuário -->
    </div>
@endsection