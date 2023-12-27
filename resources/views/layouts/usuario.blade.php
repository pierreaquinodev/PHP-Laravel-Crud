<!doctype html>
<html lang="en">
  <head>
    <title>Crud</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
  </head>

  <body>
        <table class="table data-table display nowrap" cellsspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th width="150px">Data de Criação</th>
                    <th width="120px">Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <a class="btn btn-success" href="javascript:void(0)" id="adicionarUsuario">Adicionar Usuário</a>
        
        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="userForm" name="userForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">

                                <div class="form-group">
                                    <label id="label_data_criacao" for="name" class="col-sm-6 control-label">Data de Criação</label>
                                    <div class="col-sm-12">
                                        <input  type="text" class="form-control" id="data_criacao" name="data_criacao" value="" maxlength="255" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Nome</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="nome" name="nome" value="" maxlength="255" placeholder="Campo obrigatório">
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">E-mail</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="email" name="email" value="" maxlength="255" placeholder="Campo obrigatório">
                                    </div>
                                </div>
            
                                <div class="form-group">
                                    <label for="name" class="col-sm-6 control-label">Data de Nascimento</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="data" name="data" value="" autocomplete="false" maxlength="10">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Senha</label>
                                    <div class="col-sm-12">
                                        <input type="password" class="form-control" id="senha" name="senha" value="" placeholder="Informe no mínimo 8 caracteres">
                                    </div>
                                </div>

                            <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Salvar</button>
                            <button type="button" class="btn btn-danger" id="cancelBtn" value="">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var table = $('.data-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('usuarios.index') }}",
            columns: [
                {data: 'nome', name: 'nome'},
                {data: 'email', name: 'email'},
                {data: 'created_at', name: 'data_de_criacao'},
                {data: 'action', name: 'action', orderable: false, searchable: false},     
            ]
        });
        $('#adicionarUsuario').click(function () {
            $('#saveBtn').val("create-user");
            $('#id').val('');
            $('#userForm').trigger("reset");
            $('#modelHeading').html("Cadastrar Usuário");
            $('#ajaxModel').modal('show');
            $('#label_data_criacao').hide();
            $('#data_criacao').hide();
        });
    
        $(document).ready(function($){
            $('#data').mask('00/00/0000');
        });
    
    
        $('#cancelBtn').click(function () {
            $('#userForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();
        });
    
        $('body').on('click', '.editUser', function () {
        var id = $(this).data('id');
        
        $.get("{{ route('usuarios.index') }}" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Alterar Usuário");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#id').val(data.id);
            $('#nome').val(data.nome);
            $('#email').val(data.email);
            $('#data').val(data.data);
            $('#senha').val(data.senha);
    
            var d = data.created_at;
            d = new Date();
            function dataFormatada(){
                dia  = d.getDate().toString(),
                diaF = (dia.length == 1) ? '0'+dia : dia,
                mes  = (d.getMonth()+1).toString(),
                mesF = (mes.length == 1) ? '0'+mes : mes,
                anoF = d.getFullYear();
                return diaF+"/"+mesF+"/"+anoF;
            }
    
            $('#data_criacao').val(dataFormatada);
            $('#label_data_criacao').show();
            $('#data_criacao').show();
     
        })
        });
    
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Salvar'); 
            
            let $nome = $('#nome').val();
            let $email = $('#email').val();
            let $senha = $('#senha').val();
    
            if($nome.length == 0 || $email.length == 0 || $senha.length < 8) {
                alert('Verifique os campos preenchidos e tente novamente.');
            }else{
                    $.ajax({
                    data: $('#userForm').serialize(),
                    url: "{{ route('usuarios.store') }}",
                    type: "POST",
                    dataType: 'json',
            
                    success: function (data) {
                    alert('Registro inserido com suceeso!');
                    $('#userForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();                    
                    },
    
                    error: function (data) {
                    console.log('Error:', data);
                    alert('Ocorreu um erro ao registrar informação.');
                    $('#saveBtn').html('Salvar');
                    }
                });
            }
        });
    
        $('body').on('click', '.deleteUser', function () {
    
            var id = $(this).data("id");
            confirm("Tem certeza que deseja excluir o registro ?");
    
            $.ajax({
                type: "DELETE",
                url: "{{ route('usuarios.store') }}"+'/'+id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
        });
        </script>
  </body>
</html>