<?php
// start session
 session_start();
 include("../../../assets/encrypt/encrypt.php");
 include("../../../connections/conn1.php");
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['login-system'])) {
            $username = $_POST['username'];
            $password = encryptCode($_POST['password']);
            $select = "SELECT  `developer_name`,`role` FROM `developers` WHERE `dev_username` = ?  AND `dev_password` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$username,$password);
            $stmt->execute();
            $stmt->store_result();
            $rnums = 10;
            $rnums = $stmt->num_rows;
            if ($rnums > 0 && $rnums != 10) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $_SESSION['fullname'] = $row['developer_name'];
                    $_SESSION['role'] = $row['role'];
                }
                //the user credentials are correct
                //assign values to the user_information
                //echo "<p class='green_notice'>Nice logged in!<span id='logged'></span></p>";
            }else {
                echo "<p class='red_notice'>Incorrect credentials please try again!</p>";
            }
        }elseif (isset($_POST['insert_dev'])) {
            $insert = "INSERT INTO `developers` (`developer_name`,`dev_username`,`dev_password`,`role`,`active`) VALUES (?,?,?,?,?)";
            $fullname = $_POST['fullname'];
            $roles = $_POST['roles'];
            $username = $_POST['username'];
            $passcode = encryptCode($_POST['passcode']);
            $active = "1";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sssss",$fullname,$username,$passcode,$roles,$active);
            if($stmt->execute()){
                echo "<p class='green_notice'>Registration done successfully!</p>";
            }else {
                echo "<p class='red_notice'An error occured during registration!></p>";
            }
        }elseif (isset($_POST['logout'])) {
            unset($_SESSION['fullname']);
            unset($_SESSION['role']);
            $_SESSION['fullname'] = "0";
        }elseif (isset($_POST['getSchools'])) {
            $select = "SELECT COUNT(*) as 'Total' FROM `school_information`;";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $total = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $total = $row['Total'];
                }
            }
            echo $total." Schools";
        }elseif (isset($_POST['getUserNUmber'])) {
            $select = "SELECT COUNT(*) AS 'Total' from user_tbl;";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $total = 0;
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $total = $row['Total'];
                }
            }
            echo $total." users";
        }elseif (isset($_POST['getactiveusers'])) {
            //get the schools database names and go the school name and get the user logs
            //assume all unworking databases
            $select = "SELECT `database_name` FROM `school_information`";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $db_array = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($db_array,$row['database_name']);
                }
            }
            $total = 0;
            for ($index=0; $index < count($db_array); $index++) { 
                $dbname = $db_array[$index];
                $conn3 = createConnection($dbname);
                $users = activeUser($conn3);
                $total+= $users;
                //echo $users."nns";
            }
            echo $total." User(s)";
        }elseif (isset($_POST['getSchoolInformation'])) {
            $select = "SELECT `school_code`,`school_name`,`school_admin_name`,`school_contact`,`school_mail`,`database_name`,`sch_id` from `school_information`";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<table>
                                    <tr>
                                        <th>Id</th>
                                        <th>School Name</th>
                                        <th>User(s)</th>
                                        <th>Administrator</th>
                                        <th>Contact</th>
                                        <th>School Mail</th>
                                        <th>DB Name</th>
                                    </tr>";
                                    $xs= 0;
                while ($res = $result->fetch_assoc()) {
                    $xs++;
                    $num1 = $res['sch_id'];
                    $data_to_display.="<tr class='row-hover'>
                                            <td>".$xs.".</td>
                                            <td id = 'schid".$num1."' class='view_opt sch_informations' >".ucfirst($res['school_name'])."</td>
                                            <td class='view_opt user-sch' id='use-no".$num1."'>".getUserCount($conn,$res['school_code'])."</td>
                                            <td>".ucwords($res['school_admin_name'])."</td>
                                            <td class='view_opt'>"."<a href='tel:".$res['school_contact']."'>".$res['school_contact']."</a>"."</td>
                                            <td class='view_opt'>"."<a href='mailto:".$res['school_mail']."'>".$res['school_mail']."</td>
                                            <td class='view_opt'>".$res['database_name']."</td>
                                        </tr>";
                }
                $data_to_display.="</table>";
                echo $data_to_display;
            }
        }elseif (isset($_POST['sch_id'])) {
            $sch_id = $_POST['sch_id'];
            $select = "SELECT `school_code`,`school_name`,`sch_message_name`,`school_motto`,`school_admin_name`,`school_contact`,`school_mail`,`school_location`,`database_name`,`activated`,`sch_vision`,`sch_mission`,`po_box`,`box_code`,`school_profile_image`,`county`,`country` FROM `school_information` WHERE `sch_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$sch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $data = $row['school_code']."|".$row['school_name']."|".$row['sch_message_name']."|".$row['school_motto']."|".$row['school_admin_name']."|".$row['school_contact']."|".$row['school_mail']."|".$row['school_location']."|".$row['database_name']."|".$row['activated']."|".$row['sch_vision']."|".$row['sch_mission']."|".$row['po_box']."|".$row['box_code']."|".$row['school_profile_image']."|".$row['county']."|".$row['country'];
                }
            }
            echo $data;
        }elseif (isset($_POST['change_field'])) {
            $sch_id = $_POST['sch_idds'];
            $col_name = $_POST['column_name'];
            $value = $_POST['field_values'];
            $update = "UPDATE `school_information` SET `".$col_name."` = '".$value."' WHERE `sch_id` = ".$sch_id."";
            $stmt = $conn->prepare($update);
            if($stmt->execute()){
                echo "<p class='green_notice fa-xs'>Update was done successfully!</p>";
            }else {
                echo "<p class='red_notice fa-xs'>An error occured during update!</p>";
            }
        }elseif (isset($_POST['user_infor'])) {
            $sch_id = $_POST['school_id'];
            $select = "SELECT `fullname`,`gender`,`user_id`,`deleted`,`activated` FROM `user_tbl` WHERE `school_code` = ?";
            //get the school code
            $school_code = getSchCode($conn,$sch_id);
            if ($school_code != 0) {
                $stmt = $conn->prepare($select);
                $stmt->bind_param("s",$school_code);
                $stmt->execute();
                $result = $stmt->get_result();
                $data_to_display = "<h3 class='fa-fw'>Staff list</h3><table>
                                    <tr>
                                        <th>No</th>
                                        <th>Fullname</th>
                                        <th>Gender</th>
                                        <th>Deleted</th>
                                        <th>Activated</th>
                                    </tr>";
                                    $xs = 0;
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $xs++;
                        $user_id = $row['user_id'];
                        $del = "Yes";
                        $delete = $row['deleted'];
                        if ($delete == 0) {
                            $del = "No";
                        }
                        $active = "No";
                        $activated = $row['activated'];
                        if ($activated == 1) {
                            $active = "Yes";
                        }
                        $data_to_display.="<tr>
                                            <td>$xs.</td>
                                            <td class='view_opt use-dets' id='userd".$user_id."'>".$row['fullname']."</td>
                                            <td>".$row['gender']."</td>
                                            <td>".$del."</td>
                                            <td>".$active."</td>
                                        </tr>";
                    }
                    $data_to_display.="</table>";
                }
                if ($xs > 0) {
                    echo $data_to_display;
                }
            }
        }elseif (isset($_POST['get_my_user'])) {
            $get_id = $_POST['user_id'];
            $select = "SELECT `fullname`,`dob`,`school_code`,`phone_number`,`gender`,`address`,`nat_id`,`tsc_no`,`username`,`deleted`,`auth`,`email`,`activated`,`profile_loc` FROM `user_tbl` WHERE `user_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$get_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = "";
            
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    //gender
                    $gender = "Female";
                    $gen = $row['gender'];
                    if($gen == "M"){
                        $gender = "Male";
                    }
                    //figure out auth
                    $auth = $row['auth'];
                    $authority = "N/A";
                    if($auth==0){
                        $authority = "admin";
                    }elseif ($auth==1) {
                        $authority = "Headteacher";
                    }elseif ($auth ==2) {
                        $authority = "Teacher";
                    }elseif ($auth == 3) {
                        $authority = "Deputy principal";
                    }elseif ($auth == 4) {
                        $authority = "Staff";
                    }elseif ($auth == 6) {
                        $authority = "Student";
                    }elseif ($auth == 5) {
                        $authority = "Class Teacher";
                    }
                    //figure out deleted
                    $del = $row['deleted'];
                    $delete = "Yes";
                    if ($del == "0") {
                        $delete = "No";
                    }
                    //figure out active
                    $active = $row['activated'];
                    $activated = "No";
                    if ($active == "1") {
                        $activated = "Yes";
                    }
                    $data=$row['fullname']."|".$row['dob']."|".$row['school_code']."|".$row['phone_number']."|".$gender."|".$row['address']."|".$row['nat_id']."|".$row['tsc_no']."|".$row['username']."|".$delete."|".$authority."|".$row['email']."|".$activated."|".$row['profile_loc'];
                }
            }
            echo $data;
        }elseif (isset($_POST['changeUserDetails'])) {
            $column_name = $_POST['tb_col_name'];
            $column_val = $_POST['col_val'];
            $user_id = $_POST['id_user'];
            if ($column_name == "password") {
                $column_val = encryptCode($column_val);
            }
            $update = "UPDATE `user_tbl` SET `$column_name` = ? WHERE `user_id` = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ss",$column_val,$user_id);
            if($stmt->execute()){
                echo "<p class = 'green_notice fa-xs'>Data updated successfully!</p>";
            }else {
                echo "<p class='red_notice fa-xs'>Error occured during update!</p>";
            }
        }
    }
    function getSchCode($conn,$id){
        $select = "SELECT `school_code` FROM `school_information` WHERE `sch_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sch_code = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $school_code = $row['school_code'];
            }
        }
        return $school_code;
    }
    function getUserCount($conn,$school_code){
        $select = "SELECT COUNT(*) AS 'Total' FROM `user_tbl` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$school_code);
        $stmt->execute();
        $count = 0;
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $count = $row['Total'];
            }
        }
        return $count;
    }

    function activeUser($conn3){
        $select = "SELECT COUNT(user_id) AS 'totals' FROM `logs` where `date`= ? and `active_time` >= ?";
        $stmt = $conn3->prepare($select);
        $date = date("Y-m-d",strtotime("3 hour"));
        $time = date("H:i:s",strtotime("3598 seconds"));
        $stmt->bind_param("ss",$date,$time);
        $stmt->execute();
        $result = $stmt->get_result();
        $activeusers = 0;
        if($result){
            if($row=$result->fetch_assoc()){
                $activeusers = $row['totals'];
            }
        }
        return $activeusers;
    }

    function createConnection($db_name){
        $conn3 = null;
        $dbname = $db_name;
        $hostname = 'localhost';
        $dbusername ='root';
        $dbpassword = '2000hILARY';
        if (isset($dbname)) {            
        $conn3 = new mysqli($hostname,$dbusername,$dbpassword,$dbname);
            if(mysqli_connect_error()){
                //echo "<p style='color:red;'>Connection was lost.</p>";
                //die("Connect Error ( ".mysqli_connect_errno()." ) ".mysqli_connect_error());
            }
        }
        return $conn3;
    }


?>