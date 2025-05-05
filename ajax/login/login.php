<?php
session_start();
date_default_timezone_set('Africa/Nairobi');

require("../../assets/encrypt/functions.php");
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['log'])){
            $username = $_GET['username'];
            $_SESSION['unames'] = $username;
            include ("../../connections/conn1.php");
            $select = "SELECT `fullname` , `school_code`, `gender` ,`auth`,`user_id` ,`deleted` ,`activated` FROM `user_tbl` WHERE `username` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            if($rnum>0){
                $stmt->execute();
                $results = $stmt->get_result();
                if($results){
                    $data="";
                    if ($rows=$results->fetch_assoc()) {
                        $deleted = $rows['deleted'];
                        $activated = $rows['activated'];
                        if ($deleted==0 && $activated == 1) {
                            $usernames = $rows['fullname'];
                            $schcode = $rows['school_code'];
                            $userauth = $rows['auth'];
                            $checkcoded = checkCode($schcode,$conn);
                            if ($checkcoded) {
                                $allowed = allowedIn($userauth,$conn,$schcode);
                                if($allowed){
                                    $_SESSION['username'] = $usernames;
                                    $_SESSION['schcode'] = $schcode;
                                    $_SESSION['authority'] = $userauth;
                                    $_SESSION['gender'] = $rows['gender'];
                                    $_SESSION['userids'] = $rows['user_id'];
                                    $select = "SELECT * from `school_information` where `school_code` = ?";
                                    $stmt->close();
                                    $stmt = $conn->prepare($select);
                                    $stmt->bind_param("s",$schcode);
                                    $stmt->execute();
                                    $stmt->store_result();
                                    $rnums = $stmt->num_rows;
                                    if($rnums>0){
                                        $stmt->execute();
                                        $results = $stmt->get_result();
                                        if($results){
                                            $data = "";
                                            if($row = $results->fetch_assoc()){
                                                $snames=" ".$row['school_name'];
                                                $smotto =" ".$row['school_motto'];
                                                $schmission=" ".$row['sch_mission'];
                                                $dbnames ="".$row['database_name'];
                                                $schvission =" ".$row['sch_vision'];
                                                $school_mail = $row['school_mail'];
                                                $school_contact = $row['school_contact'];
                                                $sch_mgs_name = $row['sch_message_name'];
                                                $admin_name = $row['school_admin_name'];
                                                $po_box = $row['po_box'];
                                                $box_code = $row['box_code'];
                                                $sch_country = $row['country'];
                                                $sch_county = $row['county'];
                                                $ct_cg = $row['ct_cg'];
                                                $school_profile_image = $row['school_profile_image'];
                                                $physicall_address = $row['physicall_address'];
                                                $website_name = $row['website_name'];
                                            }
                                            $_SESSION['ct_cg'] = $ct_cg;
                                            $_SESSION['sch_countrys'] = $sch_country;
                                            $_SESSION['sch_countys'] = $sch_county;
                                            $_SESSION['schname'] = $snames;
                                            $_SESSION['smotto'] = $smotto;
                                            $_SESSION['schmission'] = $schmission;
                                            $_SESSION['dbname'] = $dbnames;
                                            $_SESSION['schvission'] = $schvission;
                                            $_SESSION['school_contact'] = $school_contact;
                                            $_SESSION['school_mail'] = $school_mail;
                                            $_SESSION['sch_mgs_name'] = $sch_mgs_name;
                                            $_SESSION['admin_name'] = $admin_name;
                                            $_SESSION['po_boxs'] = $po_box;
                                            $_SESSION['box_codes'] = $box_code;
                                            $_SESSION['school_profile_image'] = strlen(trim($school_profile_image)) > 1 ? $school_profile_image: "images/no-image.png";
                                            $_SESSION['physicall_address'] = $physicall_address;
                                            $_SESSION['website_name'] = $website_name;
                                            if ($userauth == 5) {
                                                $dbname = $_SESSION['dbname'];
                                                $hostname = 'localhost';
                                                $dbusername ='root';
                                                // $dbpassword = '2000hILARY';
                                                $dbpassword = '';
                                                if (isset($dbname)) {
                                                    $conn2 = new mysqli($hostname,$dbusername,$dbpassword,$dbname);
                                                    if(mysqli_connect_error()){
                                                        echo "<p style='color:red;'>Connection was lost.</p>";
                                                        //die("Connect Error ( ".mysqli_connect_errno()." ) ".mysqli_connect_error());
                                                    }else{
                                                        
                                                    }
                                                }
                                                //get the class the class teacher is assigned
                                                $select = "SELECT `class_assigned` FROM `class_teacher_tbl` WHERE `class_teacher_id` = ?";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->bind_param("s",$_SESSION['userids']);
                                                $stmt->execute();
                                                $results = $stmt->get_result();
                                                if ($results) {
                                                    if ($row = $results->fetch_assoc()) {
                                                        $_SESSION['class_taughts'] = $row['class_assigned'];
                                                    }
                                                }
                                            }
                                            exit();
                                        }else {
                                            echo "<p>An error occured!</p>";
                                        }
                                    }else{
                                        echo "<p class='data' style='color:rgb(121, 19, 19);'>Access denied!!<br>Contact your administrator to be allowed back in</p>";
                                    }
                                }else {
                                    echo "<p style='color:red;font-size:13px;font-weight:500;'>Access denied!<br>You cannot be allowed in at this time.<br>Try again <br>".getActiveHours($_SESSION['schcode'],$conn)."</p>";
                                }
                            }else {
                                echo "<p style='color:red;font-size:13px;font-weight:500;'>Access denied!<br>Your school isn`t active right now.<br>Try again later<br></p>";
                            }
                        }else {
                            echo "<p class='data' style='color:rgb(121, 19, 19);'>Access denied!!<br>Contact your administrator to be allowed back in</p>";
                        }
                    }
                }else{
                    echo "<p>An error occured!</p>";
                }
                
            }else{
                echo "<p class='data' style='color:rgb(121, 19, 19);'>Invalid username!</p>";
            }
        }elseif (isset($_GET['password'])) {
            include_once("../../assets/encrypt/encrypt.php");
            $passwords = encryptCode( $_GET['password']);
            $username = $_GET['usernames'];
            include ("../../connections/conn1.php");
            $select = "SELECT `fullname` , `school_code`, `gender` ,`auth` FROM `user_tbl` WHERE `username` = ? and `password` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$username,$passwords);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if($rnums>0){
                $data = "<p style = 'color:green;'>Correct credentials <br>Access granted as:";
                $auth = $_SESSION['authority'];
                $this_authority = "NULL";
                if ($auth == 0) {
                    $data .= "<br>". "System Administrator </p>";
                    $this_authority = "System Administrator";
                } else if ($auth == "1") {
                    $data .= "<br>". "Principal </p>";
                    $this_authority = "Principal";
                } else if ($auth == "2") {
                    $data .= "<br>". "Deputy Principal Academics </p>";
                    $this_authority = "Deputy Principal Academics";
                } else if ($auth == "3") {
                    $data .= "<br>". "Deputy Principal Administration </p>";
                    $this_authority = "Deputy Principal Administration";
                } else if ($auth == "4") {
                    $data .= "<br>". "Dean of Students </p>";
                    $this_authority = "Dean of Students";
                } else if ($auth == "5") {
                    $data .= "<br>". "Finance Office </p>";
                    $this_authority = "Finance Office";
                } else if ($auth == "6") {
                    $data .= "<br>". "Human Resource Officer </p>";
                    $this_authority = "Human Resource Officer";
                } else if ($auth == "7") {
                    $data .= "<br>". "Head of Department </p>";
                    $this_authority = "Head of Department";
                } else if ($auth == "8") {
                    $data .= "<br>". "Trainer/Lecturer </p>";
                    $this_authority = "Trainer/Lecturer";
                } else if ($auth == "9") {
                    $data .= "<br>". "Admissions </p>";
                    $this_authority = "Admissions";
                }  else {
                    $data .= "<br>". ucwords(strtolower($auth))."</p>";
                    $this_authority = ucwords(strtolower($auth));
                }
                echo $data;
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if($row = $result->fetch_assoc()){
                        $log_text = $row['fullname']." successfully logged into the system as ".$this_authority;
                        log_login($log_text);
                    }
                }
            }else {
                echo "<p style = 'color:red;'>Inorrect credentials <br>Access denied</p>";
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['getSchoolInformation'])) {
            echo "[\"".trim($_SESSION['schoolcode'])."\",\"".trim($_SESSION['schoolname'])."\",\"".trim($_SESSION['schoolmotto'])."\",\"".trim($_SESSION['schoolmission'])."\",\"".trim($_SESSION['databasename'])."\",\"".trim($_SESSION['schoolvission'])."\",\"".trim($_SESSION['school_contacts'])."\",\"".trim($_SESSION['school_mails'])."\",\"".trim($_SESSION['school_message_name'])."\",\"".trim($_SESSION['administrator_name'])."\",\"".trim($_SESSION['po_boxs'])."\",\"".trim($_SESSION['box_codes'])."\",\"".trim($_SESSION['sch_countrys'])."\",\"".trim($_SESSION['sch_countys'])."\",\"".trim($_SESSION['physicall_address'])."\",\"".trim($_SESSION['website_name'])."\"]";
        }elseif (isset($_GET['update_school_information'])) {
            include ("../../connections/conn1.php");
            $school_name = $_GET['school_name'];
            $school_motto = $_GET['school_motto'];
            $school_mail = $_GET['administrator_email'];
            $school_message = $_GET['school_message_name'];
            $school_admin = $_GET['administrator_name'];
            $school_admin_contact = $_GET['administrator_contacts'];
            $school_code = $_GET['school_codes'];
            $school_vission = $_GET['school_vission'];
            $postalcode = $_GET['postalcode'];
            $sch_box_no = $_GET['sch_box_no'];
            $sch_country = $_GET['sch_country'];
            $sch_county = $_GET['sch_county'];
            $physicall_address = $_GET['physicall_address'];
            $school_website = $_GET['school_website'];
            $update = "UPDATE `school_information` SET `school_name` = ?, `sch_message_name` = ?,`school_motto` = ?, `school_admin_name` =?, `school_contact` = ?, `school_mail` = ?,`sch_vision` = ?, `po_box` = ?,`box_code` = ?, `county` = ?, `country` = ?, `physicall_address` = ?, `website_name` = ? WHERE `school_code` = ?";
            //$update = "UPDATE `school_information` SET `school_name` = ?, `sch_message_name`=?, `school_admin_name` =?, `school_motto` = ?, `school_mail` = ?,`school_mail` = ? , `sch_vision` = ?, `po_box` = ?,`box_code` = ?, `county` = ?,`country` = ? WHERE `school_code` = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ssssssssssssss",$school_name,$school_message,$school_motto,$school_admin,$school_admin_contact,$school_mail,$school_vission,$sch_box_no,$postalcode,$sch_county,$sch_country,$physicall_address,$school_website,$_SESSION['schoolcode']);
            if($stmt->execute()){
                // school information changed successfully!
                $log_text = "School information changed successfully!";
                log_login($log_text);
                echo "<p class='green_notice fa-sm'>Update has been done sucessfully<br>The changes will take effect next time you login!</p>";
            }else {
                echo "<p class='red_notice fa-xs'>An error has occured during update!</p>";
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['get_my_information'])) {
            include ("../../connections/conn1.php");
            $user_id = $_SESSION['userids'];
            $select = "SELECT `fullname`,`dob`,`school_code`,`phone_number`,`gender`,`address`,`nat_id`,`tsc_no`,`username`,`auth`,`email` FROM `user_tbl` WHERE `user_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$user_id);
            $stmt->execute();
            $results = $stmt->get_result();
            if ($results) {
                if ($row = $results->fetch_assoc()) {
                        $authority = $_SESSION['auth'];
                        $data ="<p style = 'margin-top:10px;'>Your Role: ";
                        $class = '0';
                        $classasigned = '0';

                        // authority
                        $auth = $authority;
                        if ($auth == 0) {
                            $data .= "<b>". "System Administrator </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "1") {
                            $data .= "<b>". "Principal </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "2") {
                            $data .= "<b>". "Deputy Principal Academics </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "3") {
                            $data .= "<b>". "Deputy Principal Administration </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "4") {
                            $data .= "<b>". "Dean of Students </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "5") {
                            $data .= "<b>". "Finance Office </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "6") {
                            $data .= "<b>". "Human Resource Officer </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "7") {
                            $data .= "<b>". "Head of Department </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "8") {
                            $data .= "<b>". "Trainer/Lecturer </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else if ($auth == "9") {
                            $data .= "<b>". "Admissions </b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        } else {
                            $data .= "<b>". ucwords(strtolower($auth))."</b>";
                            $data .= "<br> ".getSubjectsAndClassTaught($user_id)."";
                        }
                        
                        // echo data
                        echo $data.="<span class='hide' id='my_information'>".$row['fullname']."|".$row['dob']."|".$row['school_code']."|".$row['phone_number']."|".$row['gender']."|".$row['address']."|".$row['nat_id']."|".$row['tsc_no']."|".$row['username']."|".$row['auth']."|".$row['email']."</span>";
                }
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['change_my_information'])) {
            include ("../../connections/conn1.php");
            $user_id = $_SESSION['userids'];
            $update = "UPDATE `user_tbl` SET `fullname` = ?, `dob` = ?, `phone_number` = ? , `gender` = ?, `address` = ?, `nat_id` = ?, `tsc_no` = ?, `username` = ?, `email` = ? WHERE `user_id` = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ssssssssss",$_GET['my_name'],$_GET['my_dob'],$_GET['my_phone'],$_GET['my_gender'],$_GET['my_address'],$_GET['my_nat_id'],$_GET['my_tsc_code'],$_GET['my_username'],$_GET['my_mail'],$user_id);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:13px;font-weight:600;'>Update has been done sucessfully<br>The changes will take effect next time you login!</p>";
            }else {
                echo "<p style='color:red;font-size:13px;font-weight:600;'>An error has occured during update!</p>";
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['update_password'])) {
            $old_pass = $_GET['old_pass'];
            $newpass = $_GET['newpass'];
            $user_id = $_SESSION['userids'];
            include_once("../../assets/encrypt/encrypt.php");
            $old_pass = encryptCode($old_pass);
            //check id the password is correct
            include ("../../connections/conn1.php");
            $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ? AND `password` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$user_id,$old_pass);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                //update the old with the new password
                $update = "UPDATE `user_tbl` SET `password` = ? WHERE `user_id` = ?";
                $stmt = $conn->prepare($update);
                $newpass = encryptCode($newpass);
                $stmt->bind_param("ss",$newpass,$user_id);
                if($stmt->execute()){
                    echo "<p style='color:green;font-size:13px;font-weight:600;'>Password Changed successfully!<br>Use it next time you login</p>";
                }else {
                    echo "<p style='color:red;font-size:13px;font-weight:600;'>An error has occured during updating<br>Try again later!</p>";
                }
            }else {
                echo "<p style='color:red;font-size:13px;font-weight:600;'>Your old password is in-correct</p>";
            }
        }
    }
    function redirect($links){
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.$links);
    }
    function getClassTaught($user_id){
        include ("../../connections/conn2.php");
        $select = "SELECT `class_assigned` FROM `class_teacher_tbl` WHERE `class_teacher_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results) {
            if ($row = $results->fetch_assoc()) {
                return "<b>".className($row['class_assigned'])."</b>";
            }
        }
        return "Not Assigned";
        $stmt->close();
        $conn2->close();
    }
    function getActiveHours($school_code,$conn){
        $select = "SELECT `from_time` , to_time FROM `school_information` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$school_code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return "From ".$row['from_time']." hrs to ".$row['to_time']." hrs.";
            }
        }
        return ".";
    }
    function className($data){
        $datas = "Grade ".$data;
        if (strlen($data)>1) {
            $datas = $data;
        }
        return $datas;
    }
    function getSubjectsAndClassTaught($user_id){
        include ("../../connections/conn2.php");
        $use_ids = $user_id;
        $user_id = "%(".$user_id.":%";
        $select = "SELECT `subject_name`,`teachers_id` FROM `table_subject` WHERE `teachers_id` like ? AND  `sub_activated` = 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$user_id);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results) {
            $data_to_display = "<strong >Subject and Classes you teach:</strong><table style='margin:0;margin-left:5px;'><tr><th>No</th><th>Subject Name</th><th>Class Taught</th></tr>";
            $xs = 0;
            while ($row = $results->fetch_assoc()) {
                $xs++;
                $data_to_display.="<tr><td>".$xs."</td><td>".$row['subject_name']."</td>";
                $split_class = explode("|",$row['teachers_id']);
                $data_to_display.="<td>";
                $class_list = "";
                if (count($split_class) > 0) {
                    for ($ind=0; $ind < count($split_class); $ind++) { 
                        $split_data = explode(":",rBkts($split_class[$ind]));
                        if (trim($split_data[0]) == trim($use_ids)) {
                            $class_list.=className($split_data[1]).",";
                        }
                    }
                    $class_list= substr($class_list,0, strlen($class_list)-1);
                }
                $data_to_display.="".$class_list."</td></tr>";
            }
            $data_to_display.="</table><br>If the above information is incorrect contact your administrator to change";
            if ($xs>0) {
                return $data_to_display;
            }
        }
        $stmt->close();
        $conn2->close();
        return "<p style='color:green;font-weight:600;'>You teach no subject!<br>Contact your administrator to assign you a class to teach!</p>";
    }
    function rBkts($string){
        if (strlen($string)>1) {
            return substr($string,1,strlen($string)-2);
        }else {
            return $string;
        }
    }
    function allowedIn($userauth,$conn,$schcode){
        if (($userauth == 1 || $userauth == 0)) {
            return true;
        }else {
            $date = date("H:i:s");
            $select = "SELECT `from_time`, `to_time` FROM `school_information` WHERE `from_time` <= ? AND `to_time` >= ? AND `school_code` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("sss",$date,$date,$schcode);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums>0) {
                return true;
            }else {
                $stmt->execute();
                $results = $stmt->get_result();
                if ($results) {
                    if ($row = $results->fetch_assoc()) {
                        $_SESSION['from_times'] = $row['from_time'];
                        $_SESSION['to_time'] = $row['to_time'];
                        echo "<p>Your active hours for login is from ".$row['from_time']." to ".$row['to_time']."</p>";
                    }
                }
            }
        }
        return false;
    }
    function checkCode($schcode,$conn){
        $select = "SELECT * FROM `school_information` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$schcode);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            return true;
        }else {
            return false;
        }
    }
    function log_login($text){
        $full_text = date("dS M Y H:i:sA")." : ".$text." - {".$_SESSION['username']."}\n";
        $file_location = "../../ajax/logs/".$_SESSION['dbname']."/logs.txt";
        if (file_exists($file_location)) {
            $content = file_get_contents($file_location);

            // Open the file for writing
            $file = fopen($file_location, 'w');
            
            if ($file) {
                fwrite($file, $full_text.$content);
                fclose($file);
            }else {
                return "File not found!";
            }
        } else {
            $directory = dirname($file_location);
            if (!file_exists($directory)) {
                $pwu_data = posix_getpwuid(posix_geteuid());
                $username = $pwu_data['name'];
                mkdir($directory, 0777, true);

                // Change ownership of the directory to daemon
                chown($directory, $username);
            }
    
            // Open the file for writing
            $file = fopen($file_location, 'w');
            
            if ($file){
                fwrite($file, $full_text);
                fclose($file);
            }else {
                return "File not found!";
            }
        }
    }
?>
