<?php
    include '../connect_db.php';

    $email = $_POST['email'];
    $password = $_POST['pass'];

    $queryEmailCheck = $db->prepare("SELECT * FROM user WHERE email= :email");
    $queryEmailCheck->bindParam(":email", $email);
    $queryEmailCheck->execute();
    
    $user = $queryEmailCheck->fetch();

    if($queryEmailCheck->rowCount() > 0){
        $encrypted_password_salt = md5($password . $user['salt']); 

        if($user['pass'] === $encrypted_password_salt){
            $_SESSION['id_user'] = $email;

            if($user['salt'] == "admin")
                $_SESSION['admin'] = true;
            else
                $_SESSION['admin'] = false;

            // header("location: $base_url/loginRegisPage/loginRegister.php"); 
            header("location: $base_url/index.php");include "../index.php"; //Admin View - CRUD
        }
        else{
            $SESSION['error_login_message'] = 'Password yang dimasukkan salah';
            header("location: $base_url/loginRegisPage/loginRegister.php");
        }
    }
    else{
        $_SESSION['error_login_message'] = 'Email yang dimasukkan salah';
        header("location: $base_url/loginRegisPage/loginRegister.php");  
    }
?>