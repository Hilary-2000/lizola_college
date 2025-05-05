<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if (isset($_GET['rqst'])) {
            include("../../connections/conn1.php");
            $insert = "INSERT INTO `requested_user` (`f_name`,`l_name`,`email`,`sch_type`,`phone_no`,`sch_name`) VALUES (?,?,?,?,?,?);"; 
            $stmt = $conn->prepare($insert);
            $lname = $_GET['lname'];
            $f_name = $_GET['f_name'];
            $email = $_GET['email'];
            $sch_type = $_GET['sch_type'];
            $phone_no = $_GET['phone_no'];
            $school_name = $_GET['school_name'];
            $stmt->bind_param("ssssss",$f_name,$lname,$email,$sch_type,$phone_no,$school_name);
            if ($stmt->execute()) {
                echo "<p class='green_notice'>Notice sent successfully.<br> We will get back to you soon</p>";
            }
        }
    }
?>