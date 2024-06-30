<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="text-center mb-4">
        <img src="<?= base_url('logo/logo.jpg'); ?>" alt="Logo" style="max-height: 100px;">
    </div>
    <div class="mb-4">
        <button id="addUserButton" class="btn btn-primary ml-3" data-toggle="modal" data-target="#addUserModal">Adicionar Usuário</button>
    </div>

    <div id="loadingSpinner" class="spinner-border text-primary" role="status" style="display: none;">
        <span class="sr-only">Loading...</span>
    </div>

    <table id="usersTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CEP</th>
                <th>Estado</th>
                <th>Cidade</th>
                <th>Rua</th>
                <th>Criado</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Adicionar Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Telefone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="zip_code">CEP</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state">Estado</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number">Número</label>
                                <input type="text" class="form-control" id="number" name="number" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="street">Rua</label>
                                <input type="text" class="form-control" id="street" name="street" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            $('#loadingSpinner').css({
                display: 'block',
                position: 'fixed',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)',
                zIndex: 1000
            });
        }

        function hideLoading() {
            $('#loadingSpinner').hide();
        }

        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= site_url('users/getUsers'); ?>",
                    "type": "POST"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "zip_code"
                    },
                    {
                        "data": "state"
                    },
                    {
                        "data": "city"
                    },
                    {
                        "data": "street"
                    },
                    {
                        "data": "created_at",
                        "render": function(data) {
                            return new Date(data).toLocaleDateString('pt-BR') + ' ' + new Date(data).toLocaleTimeString('pt-BR');
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-success editButton">Editar</button> <button class="btn btn-sm btn-danger deleteButton">Excluir</button>';
                        }
                    }
                ],
                "order": [
                    [0, "asc"]
                ]
            });

            $('#phone').mask('(00) 00000-0000');
            $('#zip_code').mask('00000-000');

            $('#zip_code').on('blur', function() {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                        if (!("erro" in data)) {
                            $('#street').val(data.logradouro);
                            $('#state').val(data.uf);
                            $('#city').val(data.localidade);
                        } else {
                            alert('CEP não encontrado.');
                        }
                    });
                }
            });

            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                showLoading();
                $.ajax({
                    url: "<?= site_url('users/create'); ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Usuário criado com sucesso!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#addUserModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro ao criar usuário!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            });

            $('#usersTable').on('click', '.editButton', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#zip_code').val(data.zip_code);
                $('#state').val(data.state);
                $('#city').val(data.city);
                $('#number').val(data.number);
                $('#street').val(data.street);
                $('#addUserModal').modal('show');

                $('#addUserForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    showLoading();
                    $.ajax({
                        url: "<?= site_url('users/update'); ?>/" + data.id,
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Usuário atualizado com sucesso!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#addUserModal').modal('hide');
                            table.ajax.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro ao atualizar usuário!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                });
            });

            $('#usersTable').on('click', '.deleteButton', function() {
                var data = table.row($(this).parents('tr')).data();
                Swal.fire({
                    title: 'Você tem certeza?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.ajax({
                            url: "<?= site_url('users/delete'); ?>/" + data.id,
                            type: "DELETE",
                            success: function(response) {
                                Swal.fire(
                                    'Excluído!',
                                    'Usuário foi excluído.',
                                    'success'
                                );
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire(
                                    'Erro!',
                                    'Erro ao excluir usuário.',
                                    'error'
                                );
                            },
                            complete: function() {
                                hideLoading();
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>