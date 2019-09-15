<?php
// Include and initialize DB class
require_once 'DB.php';
$db = new DB();

// Database table name
$tblName = 'users';

// If the form is submitted
if(!empty($_POST['action_type'])){
    if($_POST['action_type'] == 'data'){
        // Fetch data based on row ID
        $conditions['where'] = array('id' => $_POST['id']);
        $conditions['return_type'] = 'single';
        $user = $db->getRows($tblName, $conditions);

        // Return data as JSON format
        echo json_encode($user);
    }elseif($_POST['action_type'] == 'view'){
        // Fetch all records
        $users = $db->getRows($tblName);

        // Render data as HTML format
        if(!empty($users)){
            foreach($users as $row){
                echo '<tr>';
                echo '<td>#'.$row['id'].'</td>';
                echo '<td>'.$row['first_name'].'</td>';
                echo '<td>'.$row['second_name'].'</td>';
                echo '<td>'.$row['email'].'</td>';
                echo '<td><a href="javascript:void(0);" class="btn btn-warning" rowID="'.$row['id'].'" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit">edit</a>
                <a href="javascript:void(0);" class="btn btn-danger" onclick="return confirm(\'Are you sure to delete data?\')?userAction(\'delete\', \''.$row['id'].'\'):false;">delete</a></td>';
                echo '</tr>';
            }
        }else{
            echo '<tr><td colspan="5">Пользователей не найдено</td></tr>';
        }
    }elseif($_POST['action_type'] == 'add'){
        $msg = '';
        $status = $verr = 0;

        // Get user's input
        $firstName = $_POST['first_name'];
        $secondName = $_POST['second_name'];
        $email = $_POST['email'];

        // Validate form fields
        if(empty($firstName)){
            $verr = 1;
            $msg .= 'Введите свое имя.<br/>';
        }
        if(empty($secondName)){
            $verr = 1;
            $msg .= 'Введите свою фамилию.<br/>';
        }
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $verr = 1;
            $msg .= 'Введите коректный email.<br/>';
        }


        if($verr == 0){
            // Insert data in the database
            $userData = array(
                'first_name'  => $firstName,
                'second_name' => $secondName,
                'email' => $email
            );
            $insert = $db->insert($tblName, $userData);

            if($insert){
                $status = 1;
                $msg .= 'Данные пользователя были успешно добавлены.';
            }else{
                $msg .= 'Возникла ошибка. Пожалуйста, попробуйте еще раз.';
            }
        }

        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
    }elseif($_POST['action_type'] == 'edit'){
        $msg = '';
        $status = $verr = 0;

        if(!empty($_POST['id'])){
            // Get user's input
            $firstName = $_POST['first_name'];
            $secondName = $_POST['second_name'];
            $email = $_POST['email'];

            // Validate form fields
            if(empty($firstName)){
                $verr = 1;
                $msg .= 'Введите свое имя.<br/>';
            }
            if(empty($secondName)){
                $verr = 1;
                $msg .= 'Введите свою фамилию.<br/>';
            }
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $verr = 1;
                $msg .= 'Введит коректный email.<br/>';
            }

            if($verr == 0){
                // Update data in the database
                $userData = array(
                    'first_name'  => $firstName,
                    'second_name' => $secondName,
                    'email' => $email
                );
                $condition = array('id' => $_POST['id']);
                $update = $db->update($tblName, $userData, $condition);

                if($update){
                    $status = 1;
                    $msg .= 'Данные пользователя были успешно добавлены.';
                }else{
                    $msg .= 'Возникла ошибка. Пожалуйста, попробуйте еще раз.';
                }
            }
        }else{
            $msg .= 'Возникла ошибка. Пожалуйста, попробуйте еще раз.';
        }

        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
    }elseif($_POST['action_type'] == 'delete'){
        $msg = '';
        $status = 0;

        if(!empty($_POST['id'])){
            // Delate data from the database
            $condition = array('id' => $_POST['id']);
            $delete = $db->delete($tblName, $condition);

            if($delete){
                $status = 1;
                $msg .= 'Данные успешно удалены.';
            }else{
                $msg .= 'Возникла ошибка. Пожалуйста, попробуйте еще раз.';
            }
        }else{
            $msg .= 'Возникла ошибка. Пожалуйста, попробуйте еще раз.';
        }

        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
    }
}

exit;
?>