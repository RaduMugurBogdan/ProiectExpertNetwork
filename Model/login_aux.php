<?php
    session_start();
    include "account_model.php";

    $_SESSION['login_email_value']=$_POST['email'];
    $_SESSION['login_password_value']=$_POST['password'];
    unset($_SESSION['login_email_error']);
    unset($_SESSION['login_password_error']);
    
    if(empty($_POST['email'])){
        $_SESSION['login_email_error']="The email is required";
    } 
    if(empty($_POST['password'])){
        $_SESSION['login_password_error']="The password is required";
    }
    
    if(!isset($_SESSION['login_email_error']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)==false){
        $_SESSION['login_email_error']="The string is not an e-mail address";
    }else if($acc_object->check_for_email($_POST['email'])==true){
        $_SESSION['login_email_error']="Unknown email address";
    }else if(!isset($_SESSION['login_password_error'])){
        $action=$acc_object->perform_login($_POST['email'],$_POST['password']);
        if($action!=null){
            unset($_SESSION['logged_in']);
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email_address']);
            unset($_SESSION['user_last_name']);
            unset($_SESSION['user_first_name']);
            unset($_SESSION['user_address']);
            unset($_SESSION['user_phone']);

            $_SESSION['logged_in']=true;
            $_SESSION['user_id']=$action[0]['id'];
            $_SESSION['user_email_address']=$action[0]['email'];
            $_SESSION['user_last_name']=$action[0]['last_name'];
            $_SESSION['user_first_name']=$action[0]['first_name'];
            $_SESSION['user_address']=$action[0]['address'];
            $_SESSION['user_phone']=$action[0]['phone'];
            header("Location: ../View/home_page_view/home_page.php");
            exit;
        }else{
            $_SESSION['login_email_error']="";
            $_SESSION['login_password_error']="The password is wrong";
        }
    }
    header("Location: ../View/login_page_view/login_page.php");
    exit;


?>