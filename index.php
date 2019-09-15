<?php

require_once 'DB.php';
$db = new DB();

$users = $db->getRows('users');

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP CRUD</title>

    <script src="js/jquery.min.js"></script>

    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <script src="bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/style.css">

    <script>
        // Update the users data list
        function getUsers(){
            $.ajax({
                type: 'POST',
                url: 'userAction.php',
                data: 'action_type=view',
                success:function(html){
                    $('#userData').html(html);
                }
            });
        }

        // Send CRUD requests to the server-side script
        function userAction(type, id){
            id = (typeof id == "undefined")?'':id;
            var userData = '', frmElement = '';
            if(type == 'add'){
                frmElement = $("#modalUserAddEdit");
                userData = frmElement.find('form').serialize()+'&action_type='+type+'&id='+id;
            }else if (type == 'edit'){
                frmElement = $("#modalUserAddEdit");
                userData = frmElement.find('form').serialize()+'&action_type='+type;
            }else{
                frmElement = $(".row");
                userData = 'action_type='+type+'&id='+id;
            }
            frmElement.find('.statusMsg').html('');
            $.ajax({
                type: 'POST',
                url: 'userAction.php',
                dataType: 'JSON',
                data: userData,
                beforeSend: function(){
                    frmElement.find('form').css("opacity", "0.5");
                },
                success:function(resp){
                    frmElement.find('.statusMsg').html(resp.msg);
                    if(resp.status == 1){
                        if(type == 'add'){
                            frmElement.find('form')[0].reset();
                        }
                        getUsers();
                    }
                    frmElement.find('form').css("opacity", "");
                }
            });
        }

        // Fill the user's data in the edit form
        function editUser(id){
            $.ajax({
                type: 'POST',
                url: 'userAction.php',
                dataType: 'JSON',
                data: 'action_type=data&id='+id,
                success:function(data){
                    $('#id').val(data.id);
                    $('#first_name').val(data.first_name);
                    $('#second_name').val(data.second_name);
                    $('#email').val(data.email);
                }
            });
        }

        // Actions on modal show and hidden events
        $(function(){
            $('#modalUserAddEdit').on('show.bs.modal', function(e){
                var type = $(e.relatedTarget).attr('data-type');
                var userFunc = "userAction('add');";
                if(type == 'edit'){
                    userFunc = "userAction('edit');";
                    var rowId = $(e.relatedTarget).attr('rowID');
                    editUser(rowId);
                }
                $('#userSubmit').attr("onclick", userFunc);
            });

            $('#modalUserAddEdit').on('hidden.bs.modal', function(){
                $('#userSubmit').attr("onclick", "");
                $(this).find('form')[0].reset();
                $(this).find('.statusMsg').html('');
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 head">
            <h5>Пользователи</h5>
            <!-- Add link -->
            <div class="float-right">
                <a href="javascript:void(0);" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i> New User</a>
            </div>
        </div>
        <div class="statusMsg"></div>
        <!-- List the users -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>First name</th>
                <th>Second Namel</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="userData">
            <?php if(!empty($users)){ foreach($users as $row){ ?>
                <tr>
                    <td><?php echo '#'.$row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['second_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-warning" rowID="<?php echo $row['id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit">edit</a>
                        <a href="javascript:void(0);" class="btn btn-danger" onclick="return confirm('Вы действительно хотите удалить данные пользователя?')?userAction('delete', '<?php echo $row['id']; ?>'):false;">delete</a>
                    </td>
                </tr>
            <?php } }else{ ?>
                <tr><td colspan="5">Пользователи не найдены...</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>



<!-- Modal Add and Edit Form -->
<div class="modal fade" id="modalUserAddEdit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Добавить нового пользователя</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="statusMsg"></div>
                <form role="form">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Введите свое имя">
                    </div>
                    <div class="form-group">
                        <label for="second_name">Second Name</label>
                        <input type="text" class="form-control" name="second_name" id="second_name" placeholder="Введите свою фамилию">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Введите свой email">
                    </div>
                    <input type="hidden" class="form-control" name="id" id="id"/>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-success" id="userSubmit">SUBMIT</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>