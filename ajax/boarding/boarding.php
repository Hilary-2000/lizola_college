<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        include("../../connections/conn2.php");
        if (isset($_GET['get_dorm_captain'])) {
            //get the teacher list in the dormitory table
            $select = "SELECT `dorm_captain` FROM `dorm_list` WHERE `deleted` = 0 AND `activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $trlists = "";
                while ($row = $result->fetch_assoc()) {
                    $trlists.=$row['dorm_captain'].",";
                }
                $trlists = removeComma($trlists);
                include("../../connections/conn1.php");
                //get the school teachers list
                $select = "SELECT `fullname`, `user_id` FROM  `user_tbl` WHERE `school_code` = ? AND `deleted` = 0 AND `activated` = 1";
                $stmt = $conn->prepare($select);
                $schoolcode = $_SESSION['schoolcode'];
                $stmt->bind_param("s",$schoolcode);
                $stmt->execute();
                $result = $stmt->get_result();
                $tr_list_2 = "";
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $tr_list_2.=$row['user_id'].",";
                    }
                    $tr_list_2 = removeComma($tr_list_2);
                }
                if (isset($_GET['class_name'])) {
                    $strin_to_display = "<select name='".$_GET['class_name']."' id='".$_GET['class_name']."'><option value='' hidden>Select..</option>";
                }else {
                    $strin_to_display = "<select name='dorm_captain' id='dorm_captain'><option value='' hidden>Select..</option>";
                }
                if (strlen($trlists) > 0) {
                    $tr_list_1 = explode(",",$trlists);
                    $tr_lists_2 = explode(",",$tr_list_2);
                    for ($ind=0; $ind < count($tr_lists_2); $ind++) { 
                        $present = checkPresnt($tr_list_1,$tr_lists_2[$ind]);
                        if ($present == 0) {
                            $strin_to_display.="<option value='".$tr_lists_2[$ind]."'>".getTeacherName($tr_lists_2[$ind])."</option>";
                        }
                    }
                    $strin_to_display.="</select>";
                    echo $strin_to_display;
                }else {
                    $tr_lists_2 = explode(",",$tr_list_2);
                    for ($ind=0; $ind < count($tr_lists_2); $ind++) { 
                        $strin_to_display.="<option value='".$tr_lists_2[$ind]."'>".getTeacherName($tr_lists_2[$ind])."</option>";
                    }
                    $strin_to_display.="</select>";
                    echo $strin_to_display;
                }
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>No teachers available to assign the dormitory</p>";
            }
        }elseif (isset($_GET['add_dormitory'])) {
            $dorm_capacity = $_GET['dorm_capacity'];
            $dorm_name = $_GET['dorm_name'];
            $dorm_captain = $_GET['dorm_captain'];
            $select = "INSERT INTO `dorm_list` (`dorm_name`,`dorm_capacity`,`dorm_captain`,`activated`,`deleted`) VALUES (?,?,?,?,?)";
            $stmt = $conn2->prepare($select);
            $activated = 1;
            $deleted = 0;
            $stmt->bind_param("sssss",$dorm_name,$dorm_capacity,$dorm_captain,$activated,$deleted);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600;'>Dormitory registered successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during registration!</p>";
            }
        }elseif (isset($_GET['get_dormitory_list'])) {
            $select = "SELECT `dorm_id`, `dorm_name` ,`dorm_capacity`,`dorm_captain` FROM `dorm_list` WHERE `deleted` = 0 and `activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<h6 style='font-size:17px;font-weight:500;text-align:center;margin: 5px 0;'><u>Dormitory List</u></h6><div class='table_holders'><table class='table'>
                <tr>
                    <th>No. </th>
                    <th>House Name</th>
                    <th>House Matron</th>
                    <th>Capacity</th>
                    <th>Occupied</th>
                    <th>Available</th>
                    <th>Option</th>
                    <th>Occupancy</th>
                </tr>";
                $xs=0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $capacity = $row['dorm_capacity'];
                    $dorm_id = $row['dorm_id'];
                    $occupied = getOccupancy($dorm_id,$conn2);
                    $available = $capacity - $occupied;
                    $trname = "None";
                    if (strlen($row['dorm_captain']) > 0) {
                        $trname = getTeacherName($row['dorm_captain']);
                    }
                    $data_to_display.="
                    <tr>
                        <td>".$xs.". </td>
                        <td id = 'dn".$dorm_id."' >".ucwords(strtolower($row['dorm_name']))."</td>
                        <td id = 'dc".$dorm_id."' >".ucwords(strtolower($trname))."</td>
                        <td id = 'cap".$dorm_id."' >".$row['dorm_capacity']."</td>
                        <td>".$occupied."</td>
                        <td>".$available."</td>
                        <td><p class = 'dorm_edit link'  id = 'd_nm".$dorm_id."' style='font-size:12px;' ><i class='fa fa-pen'></i> Edit</p></td>
                        <td> <p id='occupied".$dorm_id."' class = 'link linked_occupancy' style='font-size:12px;'><i class='fa fa-eye'></i> View</p></td>
                    </tr>";
                }
                $data_to_display.="</table></div>";
                if ($xs>0) {
                    echo $data_to_display;
                }else {
                    echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p style='color:red;font-size:12px;font-weight:600;'>No dormitory results!</p>
                        </div>";
                }
            }
        }elseif (isset($_GET['change_dorm_data'])) {
            $update = "UPDATE `dorm_list` SET `dorm_name` = ?,`dorm_capacity` = ?, `dorm_captain` = ? WHERE `dorm_id` = ?";
            $update2 = "UPDATE `dorm_list` SET `dorm_name` = ?,`dorm_capacity` = ? WHERE `dorm_id` = ?";
            if (isset($_GET['dorm_captain'])) {
                $stmt = $conn2->prepare($update);
                $dorm_name = $_GET['dorm_name'];
                $dorm_capacity = $_GET['dorm_capacity'];
                $dorm_captain = $_GET['dorm_captain'];
                $dorm_id = $_GET['dorm_id'];
                $stmt->bind_param("ssss",$dorm_name,$dorm_capacity,$dorm_captain,$dorm_id);
                if($stmt->execute()){
                    echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
                }else {
                    echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
                }
            }else {
                $stmt = $conn2->prepare($update2);
                $dorm_name = $_GET['dorm_name'];
                $dorm_capacity = $_GET['dorm_capacity'];
                $dorm_id = $_GET['dorm_id'];
                $stmt->bind_param("sss",$dorm_name,$dorm_capacity,$dorm_id);
                if($stmt->execute()){
                    echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
                }else {
                    echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
                }
            }
        }elseif (isset($_GET['un_assign_dorm'])) {
            $dorm_id = $_GET['un_assign_dorm'];
            $update = "UPDATE `dorm_list` SET `dorm_captain` = '' WHERE `dorm_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$dorm_id);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600;'>Change done successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600;'>An error occured during Updating!</p>";
            }
        }elseif (isset($_GET['get_enrolled_boarders'])) {
            $select = "SELECT `adm_no`, `first_name`,`second_name` , `gender` ,`stud_class`,`boarding` FROM `student_data` WHERE `boarding` = 'enroll' AND deleted = 0 AND activated = 1";
            $select2 = "SELECT `adm_no`, `first_name`,`second_name` , `gender` ,`stud_class`,`boarding` FROM `student_data` WHERE `adm_no` = ? AND `boarding` = 'enroll' AND deleted = 0 AND activated = 1";
            $result;
            if (isset($_GET['use_adm'])){
                $stmt = $conn2->prepare($select2);
                $admno = $_GET['use_adm'];
                $stmt->bind_param("s",$admno);
                $stmt->execute();
                $result = $stmt->get_result();
            }else{
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            if ($result) {
                $data_to_display = "<h6 style='margin-top:10px;text-align:center;font-size:17px;font-weight:500;'>Students to enroll</h6><div class='table_holders'><table class=''>
                                        <tr>
                                            <th>#</th>
                                            <th>Adm no</th>
                                            <th>Student Name</th>
                                            <th>Gender</th>
                                            <th>Select dormitory</th>
                                            <th>Save</th>
                                        </tr>";
                                        $xs = 0;
                                        $number = 1;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $adm_no = $row['adm_no'];
                    $first_name = $row['first_name'];
                    $second_name = $row['second_name'];
                    $gender = $row['gender'];
                    $stud_class = $row['stud_class'];
                    $boarding = $row['boarding'];
                    $sel_id = "select".$adm_no;
                    $data_to_display.="<tr>
                                        <td>".$number.". </td>
                                        <td>".$adm_no.". </td>
                                        <td>".ucwords(strtolower($first_name." ".$second_name))."</td>
                                        <td>".$gender."</td>
                                        <td id='outer".$adm_no."'>".getDormitory($conn2,$sel_id)."</td>
                                        <td><span class='save_boarder link' id='sd".$adm_no."' style='margin:0;font-size:12px;'><i class='fa fa-save'></i> Save</span></td>
                                    </tr>";
                                    $number++;
                }
                $data_to_display.="</table></div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p style='color:red;font-size:12px;font-weight:600;'>No results!</p>
                        </div>";
                }
            }
        }elseif (isset($_GET['save_boarder_infor'])) {
            $boarder_id = $_GET['boarder_id'];
            $house_id = $_GET['house_id'];
            $insert = "update `student_data` set `boarding` = 'enrolled' , `dormitory` = ? WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ss",$house_id,$boarder_id);
            if($stmt->execute()){
                $insert = "INSERT INTO `boarding_list` (`student_id`,`dorm_id`,`date_of_enrollment`,`deleted`,`activated`) values (?,?,?,?,?)";
                $date = date("Y-m-d");
                $stmt = $conn2->prepare($insert);
                $deleted = 0;
                $activated = 1;
                $stmt->bind_param("sssss",$boarder_id,$house_id,$date,$deleted,$activated);
                if($stmt->execute()){
                    echo "<p style='color:green;'>Enrolled ✔</p>";
                }
            }
        }elseif (isset($_GET['get_occupancy'])) {
            $dorm_id = $_GET['dormitory_id'];
            $select = "SELECT `id`, `student_id`, `dorm_id`,`date_of_enrollment` FROM `boarding_list` WHERE `dorm_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$dorm_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $dorm_name = getDormName($dorm_id,$conn2);
                $data_to_display = "<h6 style='text-align:center;'>".$dorm_name." Members</h6>
                                    <div class='tableHolder'>
                                        <table class='table'>
                                            <tr>
                                                <th>No.</th>
                                                <th>Adm no</th>
                                                <th>Student Name</th>
                                                <th>Gender</th>
                                                <th>Date Enrolled</th>
                                                <th>Change dormitory</th>
                                            </tr>";
                                            $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $student = getStudentName($row['student_id'],$conn2);
                    $date = $row['date_of_enrollment'];
                    $date = date("M-d-Y",strtotime($date));
                    $data_to_display.="<tr>
                                        <td>".($xs)."</td>
                                        <td>".$row['student_id']."</td>
                                        <td id='mystud".$row['student_id']."'>".$student[0]."</td>
                                        <td >".$student[1]."</td>
                                        <td>".$date."</td>
                                        <td style='text-align:center;'><span class='link change_dormitory' id='".$dorm_id."|".$row['student_id']."' style='font-size:12px;text-align:center;' ><i class='fa fa-pen'>change</i></span></td>
                                    </tr>";
                }
                $data_to_display.="</table></div>
                                    <div class='btns'>
                                        <button type='button' id='back_to_dormlist'>Back</button>
                                    </div>";
                                    if ($xs > 0) {
                                        echo $data_to_display;
                                    }else {
                                        echo "<p class='red_notice'>No student occupied ".$dorm_name."</p>
                                        <div class='btns'>
                                            <button type='button' id='back_to_dormlist'>Back</button>
                                        </div>";
                                    }
            }
        }elseif (isset($_GET['get_dorm_list'])) {
            $current_dorm = $_GET['current_dorm'];
            $select = "SELECT  `dorm_id`,`dorm_name`,`dorm_capacity` FROM `dorm_list` WHERE `dorm_id` != ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$current_dorm);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $return_string = "<select style='min-width:150px;max-width:150px;' name='dorm_list_change' id='dorm_list_change'><option value='' hidden>Select..</option>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $return_string.="<option value='".$row['dorm_id']."'>".$row['dorm_name']." <small>(".($row['dorm_capacity']-getOccupancy($row['dorm_id'],$conn2)).")</small></option>";
                }
                $return_string.="</select>";
                if ($xs > 0) {
                    echo $return_string;
                }else {
                    echo "<p style='color:red;font-size:13px;font-weight:500;'>No other dormitory</p>";
                }
            }
        }elseif (isset($_GET['change_student_dorm'])) {
            $student_id = $_GET['student_id'];
            $new_dorm_id = $_GET['new_dorm_id'];
            $current_dorm_id = $_GET['current_dorm_id'];
            $select = "UPDATE `boarding_list` SET `dorm_id` = ? WHERE `dorm_id` = ? AND `student_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("sss",$new_dorm_id,$current_dorm_id,$student_id);
            if($stmt->execute()){
                $update = "UPDATE `student_data` SET `dormitory` = ? WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ss",$new_dorm_id,$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }elseif (isset($_GET['delete_student_information'])) {
            $student_id = $_GET['student_id'];
            $dormitory_id = $_GET['dormitory_id'];
            $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `dorm_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("ss",$student_id,$dormitory_id);
            if($stmt->execute()){
                $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'none' WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }elseif (isset($_GET['un_assign_dormitory'])) {
            $student_id = $_GET['student_id'];
            $dormids = $_GET['dormids'];
            $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ? AND `dorm_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("ss",$student_id,$dormids);
            if($stmt->execute()){
                $update = "UPDATE `student_data` set `dormitory` = 'none', `boarding` = 'enroll' WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$student_id);
                if($stmt->execute()){
                    echo "<p class='errors' style='color:green;'>Changes done successfully!</p>";
                }else {
                    echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
                }
            }else {
                echo "<p class='errors' style='color:red;'>An error has occured during updating!</p>";
            }
        }
    }

    function removeComma($string){
        if (strlen($string) > 1) {
            return substr($string,0,strlen($string)-1);
        }
        return $string;
    }
    function checkPresnt($array, $string){
        if (count($array)>0) {
            for ($i=0; $i < count($array); $i++) { 
                if ($string == $array[$i]) {
                    return 1;
                    break;
                }
            }
        }
        return 0;
    }
    function getTeacherName($tr_id){
        $schoolcode = $_SESSION['schoolcode'];
        include("../../connections/conn1.php");
        $select = "SELECT `fullname`, `gender` FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ss",$schoolcode,$tr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if ($row['gender'] == "F") {
                    return "Mrs. ".$row['fullname'];
                }elseif($row['gender'] == "M") {
                    return "Mr. ".$row['fullname'];
                }
            }
        }
        return "Null";
    }
    function getOccupied($dorm_id,$conn2){
        $select = "SELECT COUNT(`dorm_id`) AS 'Dorm_count' FROM `boarding_list` WHERE  `deleted` = 0 AND `activated` = 1";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Dorm_count'];
            }
        }
        return 0;
    }
    function getOccupancy($dorm_id,$conn2){
        $select = "SELECT COUNT(`dorm_id`) AS 'Dorm_count' FROM `boarding_list` WHERE `dorm_id` = ? AND `deleted` = 0 AND `activated` = 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$dorm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Dorm_count'];
            }
        }
        return 0;
    }
    function getDormitory($conn2,$object_id){
        $select = "SELECT `dorm_id`,`dorm_name`,`dorm_capacity` FROM `dorm_list` WHERE `activated` = 1 and `deleted` = 0";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            $string_return = "<select class='my_dorms_name'  name='".$object_id."' id='".$object_id."'><option value='' hidden>Select..</option>";
            $xdc = 0;
            while ($row = $res->fetch_assoc()) {
                $occupancy = $row['dorm_capacity']-getOccupancy($row['dorm_id'],$conn2);
                if ($occupancy > 0) {
                    $xdc++;
                    $string_return.="<option value='".$row['dorm_id']."'>".$row['dorm_name']. " - (<small>".$occupancy."</small>) </option>";
                }
            }
            $string_return.="</select>";
            if ($xdc > 0) {
                return $string_return;
            }else {
                return "<p style='color:red;font-size:12px;font-weight:600;'>All dormitories are occupied!</p>";
            }
        }
        return "<p style='color:red;font-size:12px;font-weight:600;'>No dormitories available!</p>";
    }
    function getStudentName($student_id,$conn2){
        $select = "SELECT `first_name`,`second_name`,`gender` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return explode(",",ucwords(strtolower($row['first_name']." ".$row['second_name'])).",".$row['gender']);
            }
        }
        return explode(",","Null,Unknown");
    }
    function getDormName($dorm_id,$conn2){
        $select = "SELECT `dorm_name` FROM `dorm_list` WHERE `dorm_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$dorm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['dorm_name'];
            }
        }
        return "Null";
    }
?>