<?php
session_start();
date_default_timezone_set('Africa/Nairobi');
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['changeval'])){
            $data = "0";
            $_SESSION['username'] = $data;
            $_SESSION['schcode'] = $data;
            $_SESSION['authority'] = $data;
            $_SESSION['gender'] = $data;
            $_SESSION['schname'] = $data;
            $_SESSION['smotto'] = $data;
            $_SESSION['schmission'] = $data;
            $_SESSION['dbname'] = $data;
            $_SESSION['schvission'] = $data;
            $_SESSION['fullnames'] = $data;
            $_SESSION['schoolcode'] = $data;
            $_SESSION['auth'] = $data;
            $_SESSION['gen'] = $data;
            $_SESSION['schoolname'] = $data;
            $_SESSION['schoolmotto'] = $data;
            $_SESSION['schoolmission'] = $data;
            $_SESSION['databasename'] = $data;
            $_SESSION['schoolvission'] = $data;
            $_SESSION['school_contacts'] = $data;
            $_SESSION['school_contact'] = $data;
            $_SESSION['school_mails'] = $data;
            $_SESSION['school_mail'] = $data;
            $_SESSION['class_taughts'] = "N/A";
        }elseif ($_GET['newvalues']) {
            $_SESSION['fullnames'] =$_SESSION['username'];
            $_SESSION['schoolcode'] =$_SESSION['schcode'];
            $_SESSION['auth'] =$_SESSION['authority'];
            $_SESSION['gen'] =$_SESSION['gender'];
            $_SESSION['schoolname'] =$_SESSION['schname'];
            $_SESSION['schoolmotto'] =$_SESSION['smotto'];
            $_SESSION['schoolmission'] =$_SESSION['schmission'];
            $_SESSION['databasename'] =$_SESSION['dbname'];
            $_SESSION['schoolvission'] =$_SESSION['schvission'];
            $_SESSION['school_contacts'] = $_SESSION['school_contact'];
            $_SESSION['school_mails'] = $_SESSION['school_mail'];
            $_SESSION['school_message_name'] = $_SESSION['sch_mgs_name'];
            $_SESSION['administrator_name'] = $_SESSION['admin_name'];
        }

    }
?>