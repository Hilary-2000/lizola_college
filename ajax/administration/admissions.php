<?php
    // Include PhpSpreadsheet library
    require '../../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    use PhpOffice\PhpSpreadsheet\IOFactory;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    session_start();
    date_default_timezone_set('Africa/Nairobi');
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        include("../../connections/conn2.php");
        if(isset($_GET['admit'])){
            echo "<p class='text-danger'>Wrong place wrong time! ;-( </p>";
        }elseif(isset($_GET['get_course_fees_structure'])){
            $course_level = $_GET['course_level'];
            
            // get the course levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            // course levels
            $course_levels = [];
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // get the selected course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }
            
            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $select = "<select class='form-control' id='course_lists_fees_structure'><option hidden value=''>Select Courses!</option>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // check if the course is present in the level selected
                    for($index = 0; $index < count($valued); $index++){
                        // loop through course levels
                        $course_levels = isJson_report($valued[$index]->course_levels) ? json_decode($valued[$index]->course_levels) : [];
                        
                        // add course flag after looping through the course levele the course is offered
                        $proceed = false;
                        // for ($ind=0; $ind < count($course_levels); $ind++) { 
                        //     if($course_levels[$ind] == $course_id){
                        //         $proceed = true;
                        //         break;
                        //     }
                        // }
                        if(isset($valued[$index]->course_level)){
                            if($valued[$index]->course_level == $course_id){
                                $proceed = true;
                            }
                        }

                        // proceed!
                        if($proceed){
                            $select .= "<option value='".$valued[$index]->id."'>".$valued[$index]->course_name."</option>";
                        }
                    }
                }
            }
            $select .= "</select>";

            // select statement
            echo $select;
        }elseif(isset($_GET['get_course_list_search'])){
            $course_level = $_GET['course_levels'];
            
            // get the course levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            // course levels
            $course_levels = [];
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // get the selected course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }
            
            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $select = "<select class='form-control w-100' id='course_chosen_search'><option hidden value=''>Select Courses!</option>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // check if the course is present in the level selected
                    for($index = 0; $index < count($valued); $index++){
                        // loop through course levels
                        $course_levels = isJson_report($valued[$index]->course_levels) ? json_decode($valued[$index]->course_levels) : [];
                        // add course flag after looping through the course levele the course is offered
                        $proceed = false;
                        if(isset($valued[$index]->course_level) && $valued[$index]->course_level == $course_id){
                            $proceed = true;
                        }

                        // proceed!
                        if($proceed){
                            $select .= "<option value='".$valued[$index]->id."'>".$valued[$index]->course_name."</option>";
                        }
                    }
                }
            }
            $select .= "</select>";

            // select statement
            echo $select;
        }elseif(isset($_GET['getCoursesEdit'])){
            // store course level
            $course_level_name = $_GET['course_level'];
            $student_course_id = $_GET['course_id'];

            // get all classes
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            // course levels
            $course_levels = [];
            
            // get results
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level_name){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }

            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            // generate the select option
            $selector = "<select class='form-control w-100' id='course_chosen_edit'><option hidden>Select course</option>";
            if($result){
                if($row = $result->fetch_assoc()){
                    $my_courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    for($index = 0; $index < count($my_courses); $index++){
                        // get courses
                        $courses_levels = isJson_report($my_courses[$index]->course_levels) ? json_decode($my_courses[$index]->course_levels) : [];

                        // loop through all courses to see if its the one in this level
                        $present = false;
                        if(isset($my_courses[$index]->course_level)){
                            if($my_courses[$index]->course_level == $course_id){
                                $present = true;
                            }
                        }

                        if($present){
                            $selector .= "<option ".($student_course_id == $my_courses[$index]->id ? "selected" : "")." value='".$my_courses[$index]->id."'>".$my_courses[$index]->course_name."</option>";
                        }
                    }
                }
            }
            $selector.="</select>";
            echo $selector;
        }elseif (isset($_GET['checkbcno'])) {
            $bcno = $_GET['checkbcno'];
            $select = "SELECT `BCNo` FROM `student_data` WHERE BCNo = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$bcno);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result){
                    if($row=$result->fetch_assoc()){
                        $bcn = $row['BCNo'];
                        if($bcn==$bcno){
                            echo "<p style='color:red;'>The birth certificate number entered is present<p>";
                        }else {
                            echo "<p style='red'><p>";
                        }
                    }else {
                        echo "<p style='red'><p>";
                    }
                }else {
                    echo "<p style='red'><p>";
                }
            }else {
                echo "<p style='red'><p>";
            }
            $stmt->close();
            $conn2->close();
            
        }elseif (isset($_GET['completeadmit'])) {
            $disabled = $_GET['disabled'];
            $describe = $_GET['description'];
            $paymode = $_GET['paymode'];
            $payamount = $_GET['payamount'];
            $paycode = $_GET['paycode'];
            $boarded = $_GET['boarded'];
            $admno = $_GET['admno'];
            $paymentfor = "admission fees";
            $admissionessentials = $_GET['admissionessentials'];

            $medical_historys = $_GET['medical_history'];
            $source_of_funding_datas = $_GET['source_of_funding_data'];
            $previous_schools = $_GET['previous_schools'];
            $clubs_n_sports = $_GET['clubs_n_sports'];
            // echo $previous_schools;

            //checking for the admission number if its present
            $select = "SELECT * from `student_data` where `adm_no` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$admno);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            // echo $rnums;
            if($rnums>0){
                // 
                // $admno,
                $update = "UPDATE `student_data` SET `disabled` = ? , `boarding` = ?, `disable_describe` = ?, `admissionessentials` = ? ,`prev_sch_attended` = ? , `medical_history` = ?, `source_funding` = ?, `clubs_id` = ? WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                // echo $update;
                $stmt->bind_param("sssssssss",$disabled,$boarded,$describe,$admissionessentials,$previous_schools,$medical_historys,$source_of_funding_datas,$clubs_n_sports,$admno);
                if($stmt->execute()){
                    // echo $_GET['fees_paid']; 
                    if ($_GET['fees_paid'] == "Yes") {
                        //go ahead and store the student payment information
                        $inserts = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`) VALUES(?,?,?,?,?,?,?,?,?)";
                        $time = date("H:i:s");
                        $date = date("Y-m-d");
                        $balance=0;
                        $paidby = $_SESSION['username'];
                        $stmt = $conn2->prepare($inserts);
                        $stmt->bind_param("sssssssss",$admno,$time,$date,$paycode,$payamount,$balance,$paymentfor,$paidby,$paymode);
                        if($stmt->execute()){
                            echo "<p style= 'color:green; font-size:12px;'>Registration was completed successfuly!</p>";
                        }else{
                            echo "<p style= 'color:green; font-size:12px;'>The registration process was inturupted<br>Try again!</p>";
                        }
                    }else {
                        echo "<p style= 'color:green; font-size:12px;'>Registration was completed successfuly!</p>";
                    }
                }else {
                    echo "<p style= 'color:red; font-size:12px;'>Completion wasn`t successfull<br>Try again later!</p>";
                }
            }else {
                echo "<p style= 'color:red; font-size:12px;'>Student admission is not present!</p>";
            }

            // get the last academic year balance
            $last_year_academic_balance = $_GET['last_year_academic_balance']*1;
            if ($last_year_academic_balance > 0) {
                // get when the academic year started 
                $SELECT = "SELECT * FROM `academic_calendar` WHERE `term` = 'TERM_1';";
                $stmt = $conn2->prepare($SELECT);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        // start of academic year
                        $start_time = $row['start_time'];
                        
                        // add date
                        $date = date_create($start_time);
                        date_add($date,date_interval_create_from_date_string("-2 day"));
                        $new_date = date_format($date,"Y-m-d");

                        // insert into finance and set the date of payment to the one above
                        $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`,`status`,`idsd`)";
                        $insert.=" VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $conn2->prepare($insert);
                        $time = date("H:i:s");
                        $code ="cash";
                        $amount = 0;
                        $paymentfor = "update academic balance";
                        $status = 0;
                        $stmt->bind_param("sssssssssss",$admno,$time,$new_date,$code,$amount,$last_year_academic_balance,$paymentfor,$_SESSION['userids'],$code,$status,$status);
                        $stmt->execute();
                    }
                }
            }
            $stmt->close();
            $conn2->close();
            
        }elseif (isset($_GET['getStudentCount'])) {
            $count = "SELECT COUNT(activated) as 'Total' FROM `student_data` WHERE `activated` = 1 and `deleted` =0";
            $stmt = $conn2->prepare($count);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $counts = $row['Total'];
                    echo  "<p>".$counts." student(s)</p>";
                }
            }
            $stmt->close();
            $conn2->close();
        }elseif (isset($_GET['studentscounttoday'])) {
            $date = date("Y-m-d");
            $count = "SELECT COUNT(activated) as 'Total' FROM `student_data` WHERE `activated` = 1 and `deleted` =0 and D_O_A = ?";
            $stmt = $conn2->prepare($count);
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $counts = $row['Total'];
                    echo  "<p>".$counts." student(s)</p>";
                }
            }
            $stmt->close();
            $conn2->close();
        }elseif (isset($_GET['getessentials'])) {
            $select = "SELECT `valued` from `settings` WHERE `sett` = 'admissionessentials'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $data = "";
            $result = $stmt->get_result();
            if($result){
                if($row=$result->fetch_assoc()){
                    $data=$row['valued'];
                }
                
                $datasplit = explode(",",$data);
                $elemenum = count($datasplit);
                $echodata="<p style='color:rgb(36, 36, 36);font-size:12px;'>Select below.</p>";
                if($elemenum>0){
                    for ($xs=0; $xs < $elemenum; $xs++) { 
                        $echodata.="<div style='width:70%;display:flex;justify-content: space-between;position:relative;' class='conts'>";
                        $echodata.="<label style='font-size:12px;'  for='elements".($xs+1)."'>".$datasplit[$xs]."</label>";
                        $echodata.="<input type='checkbox' class='elementsadm' name='' value='".$datasplit[$xs]."' id='elements".($xs+1)."'>";
                        $echodata.="</div>";
                    }
                }else {
                    $echodata.="No addmission essentials";
                }
                echo $echodata;
            }else {
                
            }
        }elseif (isset($_GET['get_departments'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<select class='form-control' id='department_options'><option hidden>Select the department!</option>";
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];

                    // loop
                    for ($index=0; $index < count($valued); $index++) { 
                        $data_to_display.="<option value='".$valued[$index]->code."'>".$valued[$index]->name."</option>";
                    }
                }
            }
            $data_to_display.="</select>";
            echo $data_to_display;
        }elseif (isset($_GET['find'])) {
            if(isset($_GET['bynametype'])){
                $name = "%".$_GET['bynametype']."%";
                $select = "SELECT  * FROM `student_data` WHERE ( CONCAT(`first_name`,' ',`second_name`,' ',`surname`) LIKE ? OR (`first_name` like ? or `surname` LIKE ? or `second_name` like ?) )and `deleted` = 0 and `activated` =1 ";
                // $select = "SELECT * from `student_data` WHERE (`first_name` like ? or `surname` LIKE ? or `second_name` like ?) ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ssss",$name,$name,$name,$name);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Student name = <span style='color:brown;'>\"".$_GET['bynametype']."\"</span>";
                createStudentn4($conn2,$result,$searh);
            }elseif (isset($_GET['usingadmno'])) {
                $admno = $_GET['usingadmno'];
                $select = "SELECT * FROM `student_data` WHERE `adm_no` = ? AND deleted=0 and activated=1 ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$admno);
                $stmt->execute();
                $msg="";
                $result = $stmt->get_result();
                $data_array = [];
                if($result){
                    if($row=$result->fetch_assoc()){
                        include_once("../finance/financial.php");
                        array_push($data_array,ucwords(strtolower($row['surname'])));
                        array_push($data_array,ucwords(strtolower($row['first_name'])));
                        array_push($data_array,ucwords(strtolower($row['second_name'])));
                        array_push($data_array,$row['index_no']);
                        array_push($data_array,$row['D_O_B']);
                        array_push($data_array,$row['gender']);
                        array_push($data_array,$row['stud_class']);
                        array_push($data_array,$row['adm_no']);
                        array_push($data_array,$row['D_O_A']);
                        array_push($data_array,ucwords(strtolower($row['parentName'])));
                        array_push($data_array,$row['parentContacts']);
                        array_push($data_array,$row['parent_relation']);
                        array_push($data_array,$row['parent_email']);
                        array_push($data_array,$row['address']);
                        array_push($data_array,$row['BCNo']);
                        array_push($data_array,$row['disabled']);
                        array_push($data_array,ucwords(strtolower($row['parent_name2'])));
                        array_push($data_array,$row['parent_contact2']);
                        array_push($data_array,$row['parent_relation2']);
                        array_push($data_array,$row['parent_email2']);
                        array_push($data_array,$row['disable_describe']);
                        array_push($data_array,$row['boarding']);
                        array_push($data_array,ucwords(strtolower($row['admissionessentials'])));
                        array_push($data_array,$row['medical_history']);
                        array_push($data_array,$row['prev_sch_attended']);
                        array_push($data_array,$row['source_funding']);
                        array_push($data_array,$row['primary_parent_occupation']);
                        array_push($data_array,$row['secondary_parent_occupation']);
                        $term = getTerm();
                        array_push($data_array,"Kes ".number_format(getFeespaidByStudent($row['adm_no'],$conn2)));
                        array_push($data_array,"Kes ".number_format(lastACADyrBal($row['adm_no'],$conn2)));
                        array_push($data_array,"Kes ".number_format(getFeesAsPerTermBoarders($term,$conn2,$row['stud_class'],$row['adm_no'])));
                        array_push($data_array,"Kes ".number_format(getBalance($row['adm_no'],$term,$conn2)));
                        array_push($data_array,"Kes ".number_format(total_fees_paid($row['adm_no'],$conn2)));
                        array_push($data_array,$term);
                        array_push($data_array,(isTransport($conn2,$row['adm_no'])?"<b>Yes</b> : ".getRouteEnrolled($conn2,$row['adm_no']):"No"));
                        array_push($data_array,(isBoarding($row['adm_no'],$conn2)?"Yes":"No"));
                        array_push($data_array,$row['clubs_id']);

                        // set the attendances
                        $attendance_this_term = presentStats($conn2,$admno,$row['stud_class']);
                        $attendance_this_year = presentStatsYear($conn2,$admno,$row['stud_class']);
                        array_push($data_array,$attendance_this_term);
                        array_push($data_array,$attendance_this_year);
                        array_push($data_array,strlen($row['transfered_comment'])> 0 ? $row['transfered_comment'] : "");
                        array_push($data_array,(($row['discount_percentage'] * 1) || ($row['discount_value']*1) > 0) ? (($row['discount_value']*1) > 0 ? "Kes ".$row['discount_value'] : $row['discount_percentage']."%") : "Not Set");
                        array_push($data_array,$row['course_done']);
                        
                        // get the course details
                        $course_details = null;
                        $mycourselist_raw = null;
                        $course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                        $mycourse_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                        for($index = 0; $index < count($course_list); $index++){
                            if($course_list[$index]->course_status == 1){
                                // course details
                                $course_details = $course_list[$index];
                                $mycourselist_raw = $mycourse_list[$index];

                                // get the level name
                                $l_i = $course_details->course_level;
                                $l_n = "N/A";
                                $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if($rw = $result->fetch_assoc()){
                                        $valued = isJson_report($rw['valued']) ? json_decode($rw['valued']) : [];
                                        for($ind = 0; $ind < count($valued); $ind++){
                                            if($valued[$ind]->id == $l_i){
                                                $l_n = $valued[$ind]->classes;
                                                break;
                                            }
                                        }
                                    }
                                }
                                $course_details->course_level_name = $l_n;
                                
                                // get the course name
                                $c_n = "N/A";
                                $c_i = $course_details->course_name;
                                $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if($rw = $result->fetch_assoc()){
                                        $valued = isJson_report($rw['valued']) ? json_decode($rw['valued']) : [];
                                        for($ind = 0; $ind < count($valued); $ind++){
                                            if($valued[$ind]->id == $c_i){
                                                $c_n = $valued[$ind]->course_name;
                                                break;
                                            }
                                        }
                                    }
                                }

                                // course details
                                $course_details->course_id = $c_i;
                                $course_details->course_name = $c_n;

                                // break
                                break;
                            }
                        }

                        // array push
                        array_push($data_array,$course_details);
                        array_push($data_array,$mycourselist_raw);
                        array_push($data_array,$row['intake_year']);
                        array_push($data_array,$row['intake_month']);
                        array_push($data_array,$row['course_progress_status']);
                        array_push($data_array,$row['balance_carry_forward']);
                    }else{
                    }
                }else {
                }

                // show array
                echo json_encode($data_array);
            }elseif (isset($_GET['admnoincomplete'])) {
                $admin = "%".$_GET['admnoincomplete']."%";
                $select = "SELECT * from `student_data` WHERE `adm_no` like ? and `deleted` = 0 and activated =1 ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$admin);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Admission no = <span style='color:brown;'>\"".$_GET['admnoincomplete']."\"</span>";
                createStudentn4($conn2,$result,$searh);

            }elseif (isset($_GET['classelected'])) {
                $classenroled = $_GET['classelected'];
                $select = "SELECT * from `student_data` WHERE  `stud_class` = ? and `deleted` = 0 and activated =1 ";
                $stmt=$conn2->prepare($select);
                $stmt->bind_param("s",$classenroled);
                $stmt->execute();
                $result=$stmt->get_result();
                $searh = " Class selected = <span style='color:brown;'>\"".classNameAdms($_GET['classelected'])."\"</span>";
                if($_GET['classelected'] == "-1"){
                    $searh = "<span style='color:brown;'>\"Alumni\"</span>";
                }
                createStudentn4($conn2,$result,$searh);//creates table
            }elseif (isset($_GET['comname'])) {
                $name = "%".$_GET['comname']."%";
                $select = "SELECT  * FROM `student_data` WHERE ( CONCAT(`first_name`,' ',`second_name`,' ',`surname`) LIKE ? OR (`first_name` like ? or `surname` LIKE ? or `second_name` like ?) )";
                // $select = "SELECT * from `student_data` WHERE (`first_name` like ? or `surname` LIKE ? or `second_name` like ?) ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ssss",$name,$name,$name,$name);
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    $searh = "Student name = <span style='color:brown;'>\"".$_GET['comname']."\"</span>";
                    createStudentn4($conn2,$result,$searh);
                }else {
                    echo "<p>Not Executed!</p>";
                }
            }elseif (isset($_GET['comadm'])) {
                $select = "SELECT * from `student_data` WHERE (`adm_no` = ? or `adm_no` like ?) and `deleted` = 0 and activated =1 ";
                $comadm = "%".$_GET['comadm']."%";
                $comadim = $_GET['comadm'];
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$comadim,$comadm);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Admission no =  <span style='color:brown;'>\"".$_GET['comadm']."\"</span>";
                createStudentn4($conn2,$result,$searh);
            }elseif (isset($_GET['combcno'])) {
                $combcno = $_GET['combcno'];
                $compbcno = "%".$_GET['combcno']."%";
                $select = "SELECT * from `student_data` WHERE (`BCNo` = ? or `BCNo` like ?) and `deleted` = 0 and activated =1 ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$combcno,$compbcno);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Birth certificate no containing <span style='color:brown;'>\"".$_GET['combcno']."\"</span>";
                createStudentn4($conn2,$result,$searh);
            }elseif (isset($_GET['bybcntype'])) {
                $select = "SELECT * from `student_data` WHERE `BCNo` like ? and `deleted` = 0 and activated =1 ";
                $bcno = "%".$_GET['bybcntype']."%";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$bcno);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Birth certificate no containing <span style='color:brown;'>\"".$_GET['bybcntype']."\"</span>";
                createStudentn4($conn2,$result,$searh);
            }elseif (isset($_GET['classes'])) {
                $classenroled = $_GET['classes'];
                if($classenroled != "others"){
                    $course_chosen = !isset($_GET['course_chosen']) ? "" : $_GET['course_chosen'];
                    $select = strlen(trim($course_chosen)) == 0 ? "SELECT * from `student_data` WHERE  `stud_class` = ? and `deleted` = 0 and activated =1" : "SELECT * from `student_data` WHERE `course_done` = '".$course_chosen."' AND `stud_class` = ? AND `deleted` = 0 AND activated =1";
                    $stmt=$conn2->prepare($select);
                    $stmt->bind_param("s",$classenroled);
                    $stmt->execute();
                    $result=$stmt->get_result();
    
                    // get the course names
                    $course_name = "N/A";
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'courses';";
                    $statement = $conn2->prepare($select);
                    $statement->execute();
                    $res = $statement->get_result();
                    if($res){
                        if($row = $res->fetch_assoc()){
                            $course_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
    
                            // loop to get the course name
                            for($index = 0; $index < count($course_list); $index++){
                                if($course_chosen == $course_list[$index]->id){
                                    $course_name = $course_list[$index]->course_name;
                                }
                            }
                        }
                    }
                    $searh = strlen(trim($course_chosen)) == 0 ? "<span style='color:brown;'>\"".classNameAdms($_GET['classes'])."\"</span>" : "<span style='color:brown;'>\"".classNameAdms($_GET['classes'])."\" in \"".ucwords(strtolower($course_name))."\"</span>";
                    if($_GET['classes'] == "-1"){
                        $searh = "<span style='color:brown;'>\"Alumni\"</span>";
                    }
                    createStudentn4($conn2,$result,$searh);//creates table
                }else{
                    // get the whole class list
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'class';";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $where_clause = "`stud_class` != '-1' AND `stud_class` != '-2'";
                    $select = "SELECT * FROM `student_data` WHERE ";
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $valued = $row['valued'];
                            $valued = isJson_report($valued) ? json_decode($valued) : [];
                            for ($index=0; $index < count($valued); $index++) { 
                                $where_clause.=" AND `stud_class` != '".$valued[$index]->classes."'";
                            }
                        }
                    }
                    $select.=$where_clause;
                    $stmt=$conn2->prepare($select);
                    $stmt->execute();
                    $result=$stmt->get_result();
                    $searh = "<span style='color:brown;'>\"Students with Un-Assigned Class\"</span>";
                    createStudentn4($conn2,$result,$searh);//creates table
                }
            }elseif (isset($_GET['allstudents'])) {
                $select = "SELECT * from `student_data`";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res){
                    $tablein4 = "<div class='tableme'><table class='table table-striped align-items-center '><tr><th>No.</th><th>Class</th><th><i class='fa fa-male'></i> Male</th><th><i class='fa fa-female'></i> Female</th><th><i class='fa fa-male'></i> + <i class='fa fa-female'></i> Total</th><th>Action</th></tr>";
                    $classes = getClasses($conn2);
                    $classholder = array();
                    $classholdermale = array();
                    $classholderfemale = array();
                    array_push($classes,"-1","-2");
                    if (count($classes)>0) {
                        for ($i=0; $i < count($classes); $i++) { 
                            $counted = 0;
                            array_push($classholder,$counted);
                            array_push($classholdermale,$counted);
                            array_push($classholderfemale,$counted);
                        }
                    }
                    $males=0;
                    $female = 0;
                    while ($row=$res->fetch_assoc()) {
                        for ($i=0; $i < count($classes); ++$i) {
                            if ($classes[$i] == trim($row['stud_class'])) {
                                $classholder[$i]+=1;
                                if ($row['gender']=='Female') {
                                    $classholderfemale[$i]+=1;
                                    $female++;
                                }
                                if ($row['gender']=='Male') {
                                    $classholdermale[$i]+=1;
                                    $males++;
                                }
                                break;
                            }
                        }
                    }
                    $totaled = 0;
                    for ($i=0; $i < count($classes); $i++) {
                        $totaled+=$classholder[$i];
                        $daros = $classes[$i];
                        if($classes[$i] == "-1"){
                            $daros = "Alumni";
                        }
                        if($classes[$i] == "-2"){
                            $daros = "Transfered";
                        }
                        if (strlen($daros)==1){
                            $daros = "Grade ".$classes[$i];
                        }
                        $tablein4.="<tr><td>".($i+1)."</td><td style='font-size:13px;font-weight:bold;'>".$daros."</td><td>".$classholdermale[$i]." Student(s)</td><td>".$classholderfemale[$i]." Student(s)</td><td>".$classholder[$i]." Student(s)</td><td>"."<span class='link viewclass' style='font-size:12px;' id='".$classes[$i]."'><i class='fa fa-eye'></i> View</span>"."</td></tr>";
                    }
                    $tablein4.="</table></div>";
                    $table_2 = "<div class = 'table_holders'><table class='align-items-center'>
                                <tr><th>Gender</th><th>Total</th></tr>
                                <tr><td><i class='fa fa-male'></i> - Male</td><td>".$males."</td></tr>
                                <tr><td><i class='fa fa-female'></i> - Female</td><td>".$female."</td></tr>
                                <tr><td><b>Total</b></td><td><b>".$totaled."</b></td></tr>
                                </table></div>";
                    $datas = "<span class='text-dark text-lg'>Displaying all students recognized by the system</span><br><spans style='text-align:center;'><u>Gender count table</u> ".$table_2." <br> </span>";
                    echo $datas." <p><u>Student count table</u></p>".$tablein4;
                }else {
                    
                }
            }elseif (isset($_GET['todayreg'])) {
                $date = date("Y-m-d");
                $select = "SELECT * from `student_data` WHERE `D_O_A` = ? and `deleted` = 0 and activated =1";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$date);
                $stmt->execute();
                $result = $stmt->get_result();
                $searh = "Students Registered = <span style='color:brown;'>".date("M - dS - Y",strtotime($date))."</span>";
                createStudentn4($conn2,$result,$searh);
            }
        }elseif (isset($_GET['delete_staff'])) {
            include("../../connections/conn1.php");
            $staff_ids = $_GET['staff_ids'];
            $select = "SELECT * FROM user_tbl WHERE `user_id` = '".$staff_ids."'";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $fullname = $row['fullname'];
                }
            }
            $select = "DELETE FROM `user_tbl` WHERE `user_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$staff_ids);
            if($stmt->execute()){
                echo "<p class='text-success'>Staff data deleted successfully!</p>";

                // log text
                $log_text = "Staff \"".ucwords(strtolower($fullname))."\" information has been deleted successfully!";
                log_administration($log_text);

                // message name
                $messageName = "Staff has been deleted";
                $messagecontent = ucwords(strtolower($fullname))." has been deleted on ".date("dS M Y")." by ".$_SESSION['username'].".";
                $notice_stat = 0;
                $reciever_id = "all";
                $reciever_auth = 1;
                $sender_ids = "Administration system";
                insertNotice($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids);
            }else{
                echo "<p class='text-success'>An error occured during update!</p>";
            }
        }elseif (isset($_GET['updatestudinfor'])) {
            $class = $_GET['class'];
            $index = $_GET['index'];
            $bcnos = $_GET['bcnos'];
            $course_level_hidden = $_GET['course_level_hidden'];
            $course_chosen_level_hidden = $_GET['course_chosen_level_hidden'];
            $yearOfStudy = studentYOS($conn2,$_GET['adminnumber']);
            $oldYear = withoutLatest($conn2,$_GET['adminnumber']);
            $newYOS = explode(":",$yearOfStudy)[0].":".$class;
            $reason_for_leaving = $_GET['reason_for_leaving'];
            if (strlen($oldYear) > 0) {
                $newYOS = $oldYear."|".explode(":",$yearOfStudy)[0].":".$class;
            }else{
                $newYOS = "";
            }
            // echo $newYOS;
            if ($bcnos == "N/A") {
                $bcnos = 0;
            }
            if ($index == "N/A") {
                $index = 0;
            }
            $dob = $_GET['dob'];
            $genders = $_GET['genders'];
            $disabled = $_GET['disabled'];
            $describe = $_GET['describe'];
            $address = $_GET['address'];
            $pnamed = $_GET['pnamed'];
            $pcontacts = $_GET['pcontacts'];
            $paddress = $_GET['paddress'];
            $pemail = $_GET['pemail'];
            $prelation = $_GET['prelation'];
            $adminno = $_GET['adminnumber'];
            $snamed = $_GET['snamed'];
            $fnamed = $_GET['fnamed'];
            $lnamed = $_GET['lnamed'];
            // &parentname2="+parname2+"&parentcontact="+parconts2+"&parentrelation="+parrelation2+"&pemails="+pemail2
            $parentname2 = $_GET['parentname2'];
            $parentcontact = $_GET['parentcontact'];
            $parentrelation = $_GET['parentrelation'];
            $pemails = $_GET['pemails'];
            $occupation1 = $_GET['occupation1'];
            $occupation2 = $_GET['occupation2'];
            $medical_history = $_GET['medical_history'];
            $clubs_in_sporters = $_GET['clubs_in_sporters'];
            $previous_schools = $_GET['previous_schools'];
            $doas = $_GET['doas'];
            $course_chosen = $_GET['course_chosen'];
            $existing_course_details = $_GET['existing_course_details'];
            $intake_year_edit = $_GET['intake_year_edit'];
            $intake_month_edit = $_GET['intake_month_edit'];
            $course_progress = $_GET['course_progress'];
            // echo $doas." in null";

            // process the student course progress
            // if they have changed the course update the new course
            $changed = 0;
            $course_level_hidden = $_GET['course_level_hidden'];
            $course_chosen_level_hidden = $_GET['course_chosen_level_hidden'];
            if($course_level_hidden != $class || $course_chosen_level_hidden != $course_chosen){
                $changed = 1;
            }

            if($changed == 1){
                // get the course id
                $course_chosen_id = isJson_report($existing_course_details) ? json_decode($existing_course_details)->id : null;
                // select option
                $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$adminno);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        $my_course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                        
                        // loop through course list to deactivate it and create a new one that will be appended
                        $highest_id = 0;
                        for($ind = 0; $ind < count($my_course_list); $ind++){
                            // change the course active status
                            $my_course_list[$ind]->course_status = 0;

                            // get the course highest id
                            if($my_course_list[$ind]->id > $highest_id){
                                $highest_id = $my_course_list[$ind]->id;
                            }
                        }
                        

                        // get the level id
                        $level_id = null;
                        $module_terms = 0;
                        $module_period = "0 days";
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'class';";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if($res){
                            if($row = $res->fetch_assoc()){
                                $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                                for($index = 0; $index < count($valued); $index++){
                                    if($class == $valued[$index]->classes){
                                        $level_id = $valued[$index]->id;
                                        $module_terms = isset($valued[$index]->no_of_terms) ? $valued[$index]->no_of_terms : 0;
                                        $module_period = isset($valued[$index]->term_duration) ? $valued[$index]->term_duration ." ". $valued[$index]->duration_intervals : "0 days";
                                        break;
                                    }
                                }
                            }
                        }

                        // get the level id
                        $module_terms = 0;
                        $module_period = "0 days";
                        $term_cost = 0;
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses';";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if($res){
                            if($row = $res->fetch_assoc()){
                                $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                                for($index = 0; $index < count($courses); $index++){
                                    if($course_chosen == $courses[$index]->id){
                                        $module_terms = isset($courses[$index]->no_of_terms) ? $courses[$index]->no_of_terms : 0;
                                        $module_period = isset($courses[$index]->term_duration) ? $courses[$index]->term_duration ." ". $courses[$index]->duration_intervals : "0 days";
                                        $term_cost = isset($courses[$index]->termly_fees) ? $courses[$index]->termly_fees : 0;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        // ------------------------SET COURSE DETAILS----------------------------

                        // get the course name
                        $course_name_chosen = $course_chosen;

                        // set up the course details
                        $course_detail = new stdClass();
                        $course_detail->course_level = $level_id;
                        $course_detail->course_name = $course_name_chosen;
                        $course_detail->course_status = 1;
                        $course_detail->id = $highest_id+1;
                        $course_detail->module_terms = [];
                        
                        for($index = 0; $index < $module_terms; $index++){
                            $term = new stdClass();
                            $term->id = $index + 1;
                            $term->term_name = "MODULE ". ($index + 1);
                            $term->status =  ($index == 0) ? 1 : 0;
                            $term->start_date =  ($index == 0) ? date("YmdHis") : "";
                            $term->end_date =  ($index == 0) ? date("YmdHis", strtotime("+".$module_period)) : "";
                            $term->termly_cost = $term_cost;
                            array_push($course_detail->module_terms, $term);
                        }

                        // stringify the JSON data
                        // replace the course progress if its presnet
                        $present = 0;
                        for($in = 0; $in < count($my_course_list); $in++){
                            if($my_course_list[$in]->course_level == $level_id && $my_course_list[$in]->course_name == $course_chosen){
                                // change the course id because the one set is the increament one
                                $course_detail->id = $my_course_list[$in]->id;
                                $my_course_list[$in] = $course_detail;
                                $present = 1;
                            }else{
                                $my_course_list[$in]->course_status = 2;
                                foreach($my_course_list[$in]->module_terms as $key => $module_term){
                                    if($module_term->status == "1"){
                                        $my_course_list[$in]->module_terms[$key]->status = 2;
                                    }
                                }
                            }
                        }

                        // if not present add it to the array list
                        if($present == 0){
                            array_push($my_course_list,$course_detail);
                        }

                        // ----------------END OF SETTING COURSE DETAILS--------------

                        // $course_details_string = json_encode([$course_detail]);

                        // update the student course progress list
                        $update = "UPDATE `student_data` SET `my_course_list` = ? WHERE `adm_no` = ?";
                        $stmt = $conn2->prepare($update);
                        $this_data = json_encode($my_course_list);
                        $stmt->bind_param("ss",$this_data,$adminno);
                        $stmt->execute();
                    }
                }
            }

            // echo $previous_schools;
            $update = "UPDATE `student_data` SET `year_of_study` = ?,`stud_class` = ?, `BCNo`= ?,`index_no` = ?,`gender` = ?, `disabled` = ? , `disable_describe` = ? , `address` = ? ,`parentName` = ?,`parentContacts` = ?,`parent_relation` = ?,`parent_email` = ?,`parent_name2` = ?,`parent_contact2` = ?, `parent_relation2` = ?, `parent_email2` = ?, `first_name` = ? ,`surname` = ? ,`second_name` = ? ,`primary_parent_occupation` = ?, `secondary_parent_occupation` = ?, `medical_history` = ?, `clubs_id` = ?, `prev_sch_attended` = ?,`D_O_A` = ?, `transfered_comment` = ?, `course_done` = ?,`intake_year` = ?, `intake_month` = ?, `course_progress_status` = ? WHERE `adm_no`=?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("sssssssssssssssssssssssssssssss",$newYOS,$class,$bcnos,$index,$genders,$disabled,$describe,$address,$pnamed,$pcontacts,$prelation,$pemail,$parentname2,$parentcontact,$parentrelation,$pemails,$fnamed,$snamed,$lnamed,$occupation1,$occupation2,$medical_history,$clubs_in_sporters,$previous_schools,$doas,$reason_for_leaving,$course_chosen,$intake_year_edit,$intake_month_edit,$course_progress,$adminno);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;'>Student  data updated successfully!</p>";
                $log_text = $fnamed." ".$lnamed." - of Reg No. (".$adminno.") data has been updated successfully!";
                log_administration($log_text);
            }else{
                echo "<p style='color:red;font-size:12px;'>Error occured while updating<br>Try restarting your the system!</p>";
            }
        }elseif (isset($_GET['getclassinformation'])) {
            $class = $_GET['daro'];
            $select = "SELECT * from `student_data` WHERE  `stud_class` = ? and `deleted` = 0 and activated =1";
            $stmt=$conn2->prepare($select);
            $stmt->bind_param("s",$class);
            $stmt->execute();
            $result=$stmt->get_result();
            createStudentclass($result,$class,$conn2);
        }elseif (isset($_GET['add_club'])) {
            // check if there are other clubnames
            // if the usernames are present add the new array to the list
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            if (strlen($clubs_sports) > 0) {
                // if the clubs are present change it to json and add the new data
                // echo $clubs_sports;
                $club_data = json_decode($clubs_sports);
                $ids = "0";
                for ($indexes=0; $indexes < count($club_data); $indexes++) { 
                    if (($indexes+1) == count($club_data)) {
                        $ids = $club_data[$indexes]->id;
                    }
                }
                $ids+=1;
                $new_clubs = array("id" => $ids,"Name" => $_GET['club_name']);
                array_push($club_data,$new_clubs);
                $clubs = json_encode($club_data);

                // echo $clubs;
                // update clubs
                $update = "UPDATE `settings` SET `valued` = '".$clubs."' WHERE `sett` = 'clubs/sports_house'";
                $stmt = $conn2->prepare($update);
                if($stmt->execute()){
                    echo "<p class='text-success'>The Sports House / Clubs has been successfully added!</p>";
                    
                    // log text
                    $log_text = "The Sports House / Clubs \"".$_GET['club_name']."\" has been added successfully!";
                    log_administration($log_text);
                }else{
                    echo "<p class='text-danger'>The process has not been completed successfully please contact your administrator!!</p>";
                }
            }else {
                $clubs = [];
                $new_clubs = array("id" => "1","Name" => $_GET['club_name']);
                array_push($clubs,$new_clubs);
                // insert data
                $clubs = json_encode($clubs);
                $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?)";
                $stmt = $conn2->prepare($insert);
                $sett = "clubs/sports_house";
                $stmt->bind_param("ss",$sett,$clubs);
                if($stmt->execute()){
                    echo "<p class='text-success'>The Sports House / CLubs has been successfully added!</p>";
                }else{
                    echo "<p class='text-danger'>The process has not been completed successfully please contact your administrator!!</p>";
                }
            }
        }elseif (isset($_GET['getClubHouses'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            // check if the clubs are present
            if (strlen($clubs_sports) > 0) {
                // decode it to json data
                $clubs_data = json_decode($clubs_sports);
                $data_to_display = "<table class='table'><tr><th>No.</th><th>Sports House / Clubs</th><th>Options</th></tr>";
                for ($indexed=0; $indexed < count($clubs_data); $indexed++) { 
                    $data_to_display.="<tr><td>".($indexed+1)."</td><td id='club_named".$clubs_data[$indexed]->id."'>".$clubs_data[$indexed]->Name."</td><td><span class='link edit_clubs' id='edit_clubs".$clubs_data[$indexed]->id."' ><i class='fa fa-pen'></i> Edit</span> <span class='link delete_clubs' id='delete_clubs".$clubs_data[$indexed]->id."'><i class='fa fa-trash'></i> Delete</span></td></tr>";
                }
                $data_to_display.="</table>";
                echo $data_to_display;
            }else {
                "<p class = 'text-danger'>There are no clubs at the momment!</p>";
            }
        }elseif (isset($_GET['delete_clubs'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            // check if the clubs are present
            if (strlen($clubs_sports) > 0) {
                $ids = $_GET['ided'];
                $club_data = json_decode($clubs_sports);
                $clubs_data = [];
                $count = 0;
                $club_name = "N/A";
                for ($indexes=0; $indexes < count($club_data); $indexes++) { 
                    if ($club_data[$indexes]->id != $ids) {
                        $new_clubs = array("id" => $club_data[$indexes]->id,"Name" => $club_data[$indexes]->Name);
                        array_push($clubs_data,$new_clubs);
                        $count++;
                    }
                    if($club_data[$indexes]->id == $ids){
                        $club_name = $club_data[$indexes]->Name;
                    }
                }
                $club_dt = ($count>0) ? json_encode($clubs_data):"";
                $update = "UPDATE `settings` SET `valued` = '".$club_dt."' WHERE `sett` = 'clubs/sports_house'";
                $stmt = $conn2->prepare($update);
                if($stmt->execute()){
                    // log text
                    $log_text = "The Sports House / Clubs \"".$club_name."\" has been deleted successfully!";
                    log_administration($log_text);
                    echo "<p class='text-success'>The Sports House / Clubs has been successfully deleted!</p>";
                }else{
                    echo "<p class='text-danger'>The process has not been completed successfully please contact your administrator!!</p>";
                }
            }else {
                "<p class = 'text-danger'>There are no clubs at the momment!</p>";
            }
        }elseif (isset($_GET['getmyclubs'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            if (strlen($clubs_sports) > 0) {
                $club_in_data = json_decode($clubs_sports);
                $data_to_display = "<select name='source_of_funding' class='border border-dark text-xxs form-control bg-light w-50' id='select_clubs_sports' class='form-control'><option value='' hidden>Select an option</option>";
                for ($ind=0; $ind < count($club_in_data); $ind++) { 
                    $data_to_display.="<option value='".$club_in_data[$ind]->id."'>".$club_in_data[$ind]->Name."</option>";
                }
                $data_to_display.="</select>";
                echo $data_to_display;
            }else {
                $data_to_display = "<select name='source_of_funding' class='border border-dark text-xxs form-control bg-light w-50' id='select_clubs_sports' class='form-control'><option value='' hidden>Select an option</option></select>";
                echo $data_to_display;
            }
        }elseif (isset($_GET['getmyclubs2'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            if (strlen($clubs_sports) > 0) {
                $club_in_data = json_decode($clubs_sports);
                $data_to_display = "<select name='' class='border border-dark text-xxs form-control bg-light w-100' id='clubs_in_sporters' class='form-control'><option value='' id='select_clubs_sports_def' hidden>Select an option</option>";
                for ($ind=0; $ind < count($club_in_data); $ind++) { 
                    $data_to_display.="<option class='clubs_in_sporter' value='".$club_in_data[$ind]->id."'>".$club_in_data[$ind]->Name."</option>";
                }
                $data_to_display.="</select>";
                echo $data_to_display;
            }else {
                $data_to_display = "<select name='' class='border border-dark text-xxs form-control bg-light w-100' id='clubs_in_sporters' class='form-control'><option value='' id='select_clubs_sports_def' hidden>Select an option</option></select>";
                echo $data_to_display;
            }
        }elseif (isset($_GET['edit_clubs'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $clubs_sports = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $clubs_sports = $row['valued'];
                }
            }
            // check if the clubs are present
            if (strlen($clubs_sports) > 0) {
                $club_data = json_decode($clubs_sports);
                $name = $_GET['club_name'];
                $id = $_GET['club_id'];
                for ($indexed=0; $indexed < count($club_data); $indexed++) { 
                    if ($id == $club_data[$indexed]->id) {
                        $club_data[$indexed]->Name = $name;
                        break;
                    }
                }
                $club_data = json_encode($club_data);
                $update = "UPDATE `settings` SET `valued` = '".$club_data."' WHERE `sett` = 'clubs/sports_house'";
                $stmt = $conn2->prepare($update);
                if($stmt->execute()){
                    // log text
                    $log_text = "The Sports House / Clubs \"".$_GET['club_name']."\" has been updated successfully!";
                    log_administration($log_text);
                    echo "<p class='text-success'>The Sports House / Clubs has been successfully Updated!</p>";
                }else{
                    echo "<p class='text-danger'>The process has not been completed successfully please contact your administrator!!</p>";
                }
            }else {
                "<p class = 'text-danger'>There are no clubs at the momment!</p>";
            }
        }
        elseif (isset($_GET['insertattendance'])) {
            $data = $_GET['insertattendance'];
            $datasplit = explode(",",$data);
            $name = $datasplit[0];
            $daro = $datasplit[1];
            /*****check if class register already called** */
            $select = "SELECT * FROM `attendancetable` WHERE `class`=? AND `date` = ? ";
            $stmt = $conn2->prepare($select);
            $date = date("Y-m-d",strtotime($_GET['calldate']));
            $stmt->bind_param("ss",$daro,$date);
            $stmt->execute();
            $stmt->store_result();
            $rnums=0;
            $rnums = $stmt->num_rows;
            /**********end**** */
            if($rnums==0){
                $insert = "INSERT INTO `attendancetable` (`admission_no`,`date`,`signedby`,`class`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $counter = 2;
                for ($i=2; $i < count($datasplit) ; $i++) { 
                    $stmt->bind_param("ssss",$datasplit[$i],$date,$name,$daro);
                    $stmt->execute();
                    $counter++;
                }
                if($counter==count($datasplit)){
                    echo "<p style='color:green;'>Register successfully called for ".date("D dS M Y",strtotime($date))."!</p>";
                }
            }else {
                $delete = "DELETE FROM `attendancetable` WHERE `class` = ? AND `date` = ?";
                $stmt= $conn2->prepare($delete);
                $stmt->bind_param("ss",$daro,$date);
                $stmt->execute();
                // proceed and insert
                $insert = "INSERT INTO `attendancetable` (`admission_no`,`date`,`signedby`,`class`) VALUES (?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $counter = 2;
                for ($i=2; $i < count($datasplit) ; $i++) { 
                    $stmt->bind_param("ssss",$datasplit[$i],$date,$name,$daro);
                    $stmt->execute();
                    $counter++;
                }
                if($counter==count($datasplit)){
                    echo "<p style='color:green;'>Register successfully called for ".date("D dS M Y",strtotime($date))."!</p>";
                }
                echo "<p style='color:red;'>Register was already called!</p>";
            }
        }elseif (isset($_GET['class'])) {
            $class = $_GET['class'];
            $date = $_GET['dates'];
            if($date=="today"){
                $date=date("Y-m-d");
            }
            $datas = classNameAdms($class);
            $dated = date_create($date);
            $dated = date_format($dated,"Y-m-d");
            echo "<p style='font-size:15px;text-align:center;margin-top:5px;'><u>Displaying <span style='color:brown;font-weight:600;'>".$datas."</span> attendance on : <span style='color:brown;font-weight:600;'>".date("l dS \of M Y",strtotime($dated))."</span></u></p>";
            $select = "SELECT `adm_no` FROM `student_data` WHERE `stud_class` = ? AND `deleted`=0 AND activated=1";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$class);
            $stmt->execute();
            $result = $stmt->get_result();
            $datas = "";
            $datanew;
            if($result){
                while ($row = $result->fetch_assoc()){
                    $datas.="".$row['adm_no'].",";
                }
                $datanew = explode(",",substr($datas,0,(strlen($datas)-1)));
            }
            //retrieve data of the class from the database
            $select = "SELECT `student_data`.`surname` AS 'surname' ,`student_data`.`first_name` AS 'first_name' ,`student_data`.`second_name` AS 'second_name' ,`student_data`.`adm_no` AS 'adm_no' , `student_data`.`gender` AS 'gender' ,`student_data`.`stud_class` AS 'stud_class' ,`student_data`.`BCNo` AS 'BCNo' from `student_data` JOIN `attendancetable` ON `student_data`.`adm_no` = `attendancetable`.`admission_no` where `attendancetable`.`class` = ? and `attendancetable`.`date` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$class,$date);
            $stmt->execute();
            $results = $stmt->get_result();
            $tata = createTable($results,$datanew,$conn2);
            $stmt->close();
            if(count($tata)>0){
                $select = "SELECT * from `student_data` where `adm_no` = ? and `deleted` = 0 AND `activated` = 1";
                $unattendedtable = "";
                $stmt = $conn2->prepare($select);
                $absentno = 0;
                //Unattendace table
                $unattendedtable="<h6 style='font-size:15px;text-align:center;margin-top:20px;'><u>Students Absent</u></h6>";
                $unattendedtable.="<div class='tableme'><table class='table' >";
                $unattendedtable.="<tr><th>No</th>";
                $unattendedtable.="<th>Student Name</th>";
                $unattendedtable.="<th>Adm no.</th>";
                $unattendedtable.="<th>Gender</th>";
                $unattendedtable.="<th>Attendance Stats</th>";
                $unattendedtable.="<th>Class</th>";
                $unattendedtable.="<th>Status</th></tr>";
                for ($i=0; $i < count($tata); $i++) {
                    $admno = $tata[$i];
                    $stmt->bind_param("s",$admno);
                    $stmt->execute();
                    $res = $stmt->get_result();      
                    if($res){
                        while ($rows = $res->fetch_assoc()){
                            $unattendedtable.="<tr><td>".($i+1)."</td>";
                            $unattendedtable.="<td>".ucwords(strtolower($rows['first_name']))." ".ucwords(strtolower($rows['second_name']))."</td>";
                            $unattendedtable.="<td>".$rows['adm_no']."</td>";
                            $unattendedtable.="<td>".$rows['gender']."</td>";
                            $unattendedtable.="<td>".presentStats($conn2,$rows['adm_no'],$rows['stud_class'])."</td>";
                            $unattendedtable.="<td>".classNameAdms($rows['stud_class'])."</td>";
                            $unattendedtable.="<td>"."Absent"."</td></tr>"; 
                            $absentno++;
                        }
                    }
                }

                $unattendedtable.="</table></div>";
                if($absentno>0){
                    echo $unattendedtable;
                }else {
                    echo "<p style='margin-top:20px;text-align:center;font-size:12px;font-weight:600;color: rgb(23, 72, 73);'>Students are all present!</p>";
                }

            }else {
                echo "<p style='text-align:center;margin-top:20px;font-size:12px;font-weight:600;color: rgb(23, 72, 73);'>All students are present!</p>";
            }
        }elseif (isset($_GET['findphone'])) {
            $phonenumber = $_GET['findphone'];
            include("../../connections/conn1.php");
            $conn2->close();
            $select = "SELECT * FROM `user_tbl` where `phone_number` = ? and `school_code` = ? and deleted = 0 and activated=1";
            $schoolcode;
            if(isset($_SESSION['schoolcode'])){
                $schoolcode = $_SESSION['schoolcode'];
            }else{
                $schoolcode = '';
            }
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$phonenumber,$schoolcode);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if($rnums>0){
                echo  "<p style='color:red;font-size:12px;'>The phone number entered is present</p>";
            }else{
                echo  "<p></p>";
            }
            
        }elseif (isset($_GET['findidpass'])) {
            $natid = $_GET['findidpass'];
            include("../../connections/conn1.php");
            $conn2->close();
            $select = "SELECT * FROM `user_tbl` where `nat_id` = ? and `school_code` = ? and deleted = 0 and activated=1";
            $schoolcode;
            if(isset($_SESSION['schoolcode'])){
                $schoolcode = $_SESSION['schoolcode'];
            }else{
                $schoolcode = '';
            }
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$natid,$schoolcode);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if($rnums>0){
                echo  "<p style='color:red;font-size:12px;'>The id / passport number entered is present</p>";
            }else{
                echo  "<p></p>";
            }
        }elseif (isset($_GET['findtscno'])) {
            $tscnos = $_GET['findtscno'];
            include("../../connections/conn1.php");
            $conn2->close();
            $select = "SELECT * FROM `user_tbl` where `tsc_no` = ? and `school_code` = ? and deleted = 0 and activated=1";
            $schoolcode;
            if(isset($_SESSION['schoolcode'])){
                $schoolcode = $_SESSION['schoolcode'];
            }else{
                $schoolcode = '';
            }
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$tscnos,$schoolcode);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if($rnums>0){
                echo  "<p style='color:red;font-size:12px;'>The TSC number entered is present</p>";
            }else{
                echo  "<p></p>";
            }
        }elseif (isset($_GET['findemail'])) {
            $emails = $_GET['findemail'];
            include("../../connections/conn1.php");
            $conn2->close();
            $select = "SELECT * FROM `user_tbl` where `email` = ? and `school_code` = ? and deleted = 0 and activated=1";
            $schoolcode;
            if(isset($_SESSION['schoolcode'])){
                $schoolcode = $_SESSION['schoolcode'];
            }else{
                $schoolcode = '';
            }
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$emails,$schoolcode);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if($rnums>0){
                echo  "<p style='color:red;font-size:12px;'>The email entered is present</p>";
            }else{
                echo  "<p></p>";
            }
        }elseif (isset($_GET['insertstaff'])) {
            $fullname = $_GET['fullnames'];
            $dobo = $_GET['dobos'];
            $schoolcodes = $_SESSION['schoolcode'];
            $phonenumber = $_GET['phonenumbers'];
            $gender = $_GET['genders'];
            $address = $_GET['address'];
            $natids = $_GET['idnumber'];
            $tscno = $_GET['tscnumber'];
            include("../../assets/encrypt/encrypt.php");
            include("../../connections/conn1.php");
            $password = encryptCode($_GET['password']);
            $email = $_GET['emails'];
            $username = $_GET['username'];
            $authority = $_GET['authority'];
            $nhif_number = $_GET['nhif_number'];
            $nssf_number = $_GET['nssf_number'];
            $kin_fullname = $_GET['kin_fullname'];
            $kin_relation = $_GET['kin_relation'];
            $kin_contacts = $_GET['kin_contacts'];
            $kin_location = $_GET['kin_location'];
            $delete = 0;
            $activated = 1;
            if ($authority == "6") {
                $delete = 1;
                $activated = 0;
            }


            $insert = "INSERT INTO `user_tbl` (`fullname`,`dob`,`doe`,`school_code`,`phone_number`,`gender`,`auth`,`address`,`nat_id`,`tsc_no`,`username`,`password`,`email`,`activated`,`nssf_number`,`nhif_number`,`deleted`,`kin_fullname`,`kin_contact`,`kin_relation`,`kin_location`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt=$conn->prepare($insert);
            $doe = date("Y-m-d");
            $stmt->bind_param("sssssssssssssssssssss",$fullname,$dobo,$doe,$schoolcodes,$phonenumber,$gender,$authority,$address,$natids,$tscno,$username,$password,$email,$activated,$nssf_number,$nhif_number,$delete,$kin_fullname,$kin_contacts,$kin_relation,$kin_location);
            if($stmt->execute()){
                //administrator notification
                    $messageName = "Registration of <b>".$fullname."</b> as a new staff was successfull";
                    $messagecontent = "Registration of ".$fullname." as <b>".getAuthority($authority)."</b> has been done successfully<br>The user is to use their username and password you assigned them to login";
                    $notice_stat = 0;
                    $reciever_id = "all";
                    $reciever_auth = 1;
                    $sender_ids = "Administration system";
                    insertNotice($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids);
                    ///staff notification
                    $messageName = "Hello <b>".$fullname."</b>. Welcome!";
                    $messagecontent = "Hello <b>".$fullname."</b>, Welcome to <b>".$_SESSION['schoolname']." SMIS</b>. <br>You are assigned <b>".getAuthority($authority)."</b> by your administrator.<br>Use the menu on your left to navigate the system and the home button on the top to view your dashboard.";
                    $notice_stat = 0;
                    //latest id
                    $staff_id = latestStaffId();
                    if ($staff_id > 0) {
                        $reciever_id = $staff_id;
                        $reciever_auth = $authority;
                        insertNotice($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids);
                    }
                    $log_text = "Registration of ".$fullname." as <b>".getAuthority($authority)."</b> has been done successfully";
                    log_administration($log_text);
                    echo "<p style='color:green;'>"."Registration was completed successfull!!"."</p>";
            }else {
                echo "<p style='color:red;'>"."An error occured during registration!"."</p>";
            }
        }elseif (isset($_GET['getavalablestaff'])) {
            $select = "SELECT * FROM `user_tbl` where `school_code` = ? AND `user_id` != ?";
            $schoolcodes = $_SESSION['schoolcode'];
            $userid = $_SESSION['userids'];
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$schoolcodes,$userid);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                $xs =0;
                $data="<h6 style='font-size:17px;text-align:center;font-weight:550;font-family:'Rockwell';'>My Staff List</h6>";
                $data.="<p style='display:none;' id='errorsviewing'>Pop</p>";
                $data.="<div class='container'><table class='table output1' >";
                $data.="<tr><th>No.</th>";
                $data.="<th>Fullname</th>";
                $data.="<th>Role</th>";
                $data.="<th>Gender</th>";
                $data.="<th>National id</th>";
                $data.="<th>Employee Type</th>";
                $data.="<th>Activated</th>";
                $data.="<th>Option</th></tr>";
                $xs2=0;
                $number = 1;
                while ($rowed = $result->fetch_assoc()) {
                    $data.="<tr><td>".$number."</td><td>".ucwords(strtolower($rowed['fullname']))."</td>";
                    if(isset($rowed['auth'])){
                        $auth = $rowed['auth'];
                        if ($auth == 0) {
                            $data .= "<td>". "System Administrator"."</td>";
                        } else if ($auth == "1") {
                            $data .= "<td>". "Principal"."</td>";
                        } else if ($auth == "2") {
                            $data .= "<td>". "Deputy Principal Academics"."</td>";
                        } else if ($auth == "3") {
                            $data .= "<td>". "Deputy Principal Administration"."</td>";
                        } else if ($auth == "4") {
                            $data .= "<td>". "Dean of Students"."</td>";
                        } else if ($auth == "5") {
                            $data .= "<td>". "Finance Office"."</td>";
                        } else if ($auth == "6") {
                            $data .= "<td>". "Human Resource Officer"."</td>";
                        } else if ($auth == "7") {
                            $data .= "<td>". "Head of Department"."</td>";
                        } else if ($auth == "8") {
                            $data .= "<td>". "Trainer/Lecturer"."</td>";
                        } else if ($auth == "9") {
                            $data .= "<td>". "Admissions"."</td>";
                        } else {
                            $data .= "<td>". ucwords(strtolower($auth))."</td>";
                        }
                    }else {
                        $data.="<td>"."N/A"."</td>";
                    }
                    
                    $data.="<td>".$rowed['gender']."</td>";
                    $data.="<td>".$rowed['nat_id']."</td>";
                    $data.="<td>". (strlen($rowed['employees_type']) > 0 ? "<span class='text-success'>".$rowed['employees_type']."</span>" : "Not-Set") ."</td>";
                    if (isset($rowed['activated'])) {
                        $activated = $rowed['activated'];
                        if($activated=='1'){
                            $data.="<td>"."Active"."</td>";
                        }else {
                            $data.="<td style='color:red;'>"."Not active"."</td>";
                        }
                    }
                    
                    // my user id
                    $my_user_ids = $rowed['user_id'];
                    
                    $number++;
                    $data.="<td>"."<p class='link viewtr' style='font-size:12px;' id='".$my_user_ids."'><i class='fa fa-eye'></i> View</p>"."</td></tr>";
                }
                $data.="</table></div>";
                echo $data;
            }
        }elseif(isset($_GET['get_admission_prefix'])){
            // get admission prefix
            $get_admission_prefix = $_GET['get_admission_prefix'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'admission_prefix'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $admission_prefix = "";
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    $admission_prefix = $valued;
                }
            }
            echo $admission_prefix;
        }elseif(isset($_GET['save_admission_prefix'])){
            // save the admission prefix
            $admission_prefix = $_GET['admission_prefix'];
            $update = "SELECT * FROM `settings` WHERE `sett` = 'admission_prefix'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;

            // if the rnums is more than 0 that means the admission prefix is already set
            if($rnums > 0){
                // update the setting
                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'admission_prefix'";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$admission_prefix);
                $stmt->execute();
            }else{
                $insert = "INSERT INTO `settings` (`sett`, `valued`) VALUES (?,?)";
                $stmt = $conn2->prepare($insert);
                $sett = "admission_prefix";
                $stmt->bind_param("ss",$sett,$admission_prefix);
                $stmt->execute();
            }

            // the success message
                    
            // log text
            $log_text = "Admission prefix has has been updated successfully!";
            log_administration($log_text);

            // the success message
            echo "<p class='text-success'>Admission prefix has been set successfully!</p>";
        }elseif(isset($_GET['get_admission_prefix_details'])){
            // get admission prefix details
            $update = "SELECT * FROM `settings` WHERE `sett` = 'admission_prefix'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();

            // results
            $results = $stmt->get_result();
            $data_to_display = "<p class='text-primary'>Not-Set</p>";
            if($results){
                if($row = $results->fetch_assoc()){
                    if(strlen($row['valued']) > 0){
                        $data_to_display = "<p class='text-primary'>Admission Prefix : <b>".$row['valued']."</b> <br><span class='text-secondary'>Example : ".$row['valued']."001</span></p>";
                    }
                }
            }

            // data to display
            echo $data_to_display;
        }elseif (isset($_GET['staffdata'])) {
            $id = $_GET['staffdata'];
            include("../../connections/conn1.php");
            $select = 'SELECT * FROM `user_tbl` WHERE `user_id`=?';
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$id);
            $stmt->execute();
            $results = $stmt->get_result();
            if($results){
                // create a string array
                $data = "[";
                if ($rows = $results->fetch_assoc()) {
                    $data.="\"".$rows['fullname']."\",";
                    $data.="\"".$rows['dob']."\",";
                    $data.="\"".$rows['school_code']."\",";
                    $data.="\"".$rows['phone_number']."\",";
                    $data.="\"".$rows['gender']."\",";
                    $data.="\"".$rows['address']."\",";
                    $data.="\"".$rows['nat_id']."\",";
                    $data.="\"".$rows['tsc_no']."\",";
                    $data.="\"".$rows['username']."\",";
                    $data.="\"".$rows['deleted']."\",";
                    $data.="\"".$rows['activated']."\",";
                    $data.="\"".$rows['auth']."\",";
                    $data.="\"".$rows['email']."\",";
                    $data.="\"".$rows['user_id']."\",";
                    $data.="\"".$rows['nssf_number']."\",";
                    $data.="\"".$rows['nhif_number']."\",";
                    $data.="\"".$rows['doe']."\",";
                    $data.="\"".$rows['job_number']."\",";
                    $data.="\"".$rows['job_title']."\",";
                    $data.="\"".$rows['employees_type']."\",";
                    $data.="\"".$rows['kin_fullname']."\",";
                    $data.="\"".$rows['kin_contact']."\",";
                    $data.="\"".$rows['kin_relation']."\",";
                    $data.="\"".$rows['kin_location']."\",";
                    $data.="\"".$rows['reason_inactive']."\"";
                }
                // $data = strlen($data) > 1 ? substr($data,0,strlen($data)-1) : $data;
                $data.="]";
                echo $data;
            }
        }elseif (isset($_GET['updatestaff'])) {
            $fullname = $_GET['fullnames'];
            $dob = $_GET['dob'];
            $natids = $_GET['natids'];
            $phonenumber = $_GET['phonenumber'];
            $address = $_GET['address'];
            $emails = $_GET['emails'];
            $tscno = $_GET['tscno'];
            $username = $_GET['username'];
            $genders = $_GET['genders'];
            $activated = $_GET['activated'];
            $authorities = $_GET['authorities'];
            $staffid = $_GET['staffid'];
            $deleted = $_GET['deleted'];
            $nssf_numbers = $_GET['nssf_numbers'];
            $nhif_numbers = $_GET['nhif_numbers'];
            $d_o_e_input = $_GET['d_o_e_input'];
            $job_title = $_GET['job_title'];
            $job_number = $_GET['job_number'];
            $employees_type = $_GET['employees_type'];
            $kin_fullnames = $_GET['kin_fullnames'];
            $kin_relationship_edit = $_GET['kin_relationship_edit'];
            $kin_contacts_edit = $_GET['kin_contacts_edit'];
            $kin_location_edit = $_GET['kin_location_edit'];
            $reason_inactive = ($activated == "0") ? $_GET['reason_inactive'] : "";
            include("../../connections/conn1.php");

            $update = "UPDATE `user_tbl` SET `fullname` = ?,`dob` = ?,`phone_number` = ?,`gender` =?,`address` = ?,`nat_id`=?,`tsc_no`=?,`username` =?,`deleted`=?,`auth`=?,`email`=?,`activated` =?, `nssf_number` = ?, `nhif_number` = ?,`doe` = ?, `job_title` = ?, `job_number` = ?, `employees_type` = ?,`kin_fullname` = ?,`kin_contact` = ?, `kin_relation` = ?, `kin_location` = ?, `reason_inactive` = ? WHERE `user_id` = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param('ssssssssssssssssssssssss',$fullname,$dob,$phonenumber,$genders,$address,$natids,$tscno,$username,$deleted,$authorities,$emails,$activated,$nssf_numbers,$nhif_numbers,$d_o_e_input,$job_title,$job_number,$employees_type,$kin_fullnames,$kin_contacts_edit,$kin_relationship_edit,$kin_location_edit,$reason_inactive,$staffid);
            if($stmt->execute()){
                if ($authorities != "5") {
                    $delete = "DELETE FROM `class_teacher_tbl` WHERE `class_teacher_id` = ?";
                    $stmt = $conn2->prepare($delete);
                    $stmt->bind_param("s",$staffid);
                    if($stmt->execute()){
                        echo "<p style='color:green;'>Staff information updated successfully!</p>";
                    }else {
                        echo "<p style='color:red;'>Error occured during updating!</p>";
                    }
                }else {
                    echo "<p style='color:green;'>Staff information updated successfully!</p>";
                }
                // delete the staff
                $log_text = "Staff \"".$fullname."\" information has been updated successfully!";
                log_administration($log_text);
                echo $log_text;
            }else {
                echo "<p style='color:red;'>Error occured during updating!</p>";
            }
        }elseif (isset($_GET['findnationalid'])) {
            $nationalid = $_GET['findnationalid'];
            $userids = $_GET['userids'];
            $select = "SELECT * FROM `user_tbl` WHERE `nat_id` = ? and NOT `user_id` = ? ";
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$nationalid,$userids);
            $stmt->execute();
            $stmt->store_result();
            $snums = $stmt->num_rows;
            if($snums>0){
                echo "<p style='color:red;'><small>The id or passport number entered is already used!</small></p>";
            }else {
                echo "<p></p>";
            }
        }elseif (isset($_GET['findphonenumberd'])) {
            $phonenumber = $_GET['findphonenumberd'];
            $userid = $_GET['userids'];
            $select = "SELECT * FROM `user_tbl` WHERE `phone_number` = ? and NOT `user_id` = ? ";
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$phonenumber,$userid);
            $stmt->execute();
            $stmt->store_result();
            $snums = $stmt->num_rows;
            if($snums>0){
                echo "<p style='color:red;'><small>The phone number entered is already used!</small></p>";
            }else {
                echo "<p></p>";
            }
        }elseif (isset($_GET['findstafsemails'])) {
            $emails = $_GET['findstafsemails'];
            $userid = $_GET['userids'];
            $select = "SELECT * FROM `user_tbl` WHERE `email` = ? and NOT `user_id` = ? ";
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$emails,$userid);
            $stmt->execute();
            $stmt->store_result();
            $snums = $stmt->num_rows;
            if($snums>0){
                echo "<p style='color:red;'><small>The email entered is already used!</small></p>";
            }else {
                echo "<p></p>";
            }
        }elseif (isset($_GET['findusername'])) {
            $emails = $_GET['findusername'];
            $userid = $_GET['userids'];
            $select = "SELECT * FROM `user_tbl` WHERE  `username` = ? and NOT `user_id` = ? ";
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$emails,$userid);
            $stmt->execute();
            $stmt->store_result();
            $snums = $stmt->num_rows;
            if($snums>0){
                echo "<p style='color:red;'><small>The username entered is already used!</small></p>";
            }else {
                echo "<p></p>";
            }
        }elseif (isset($_GET['studentspresenttoday'])) {
            $date = date("Y-m-d");
            $select = "SELECT COUNT(id) as 'Totals' FROM `attendancetable` WHERE `date` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($rows = $result->fetch_assoc()){
                    echo $rows['Totals']." Student(s)";
                }
            }
        }elseif (isset($_GET['activeuser'])) {
            $userid = $_GET['userid'];
            $insert = "INSERT INTO `logs` (`login_time`,`active_time`,`date`,`user_id`) VALUES (?,?,?,?)";
            $update = "UPDATE `logs` SET `active_time` = ? WHERE `user_id`=? AND `date`=?";
            $select = "SELECT * FROM `logs` WHERE `date` = ? and `user_id`= ? ";
            //check if there is a record for today
            //if present update the time, if not update
            $smt = $conn2->prepare($select);
            $date = date("Y-m-d");
            $smt->bind_param("ss",$date,$userid);
            $smt->execute();
            $smt->store_result();
            $rnums = $smt->num_rows;
            if($rnums>0){
                $smt->close();
                $smt = $conn2->prepare($update);
                $time = date("H:i:s");
                $smt->bind_param("sss",$time,$userid,$date);
                $smt->execute();
                echo  "TIME = ".$time;
                $smt->close();
                $conn2->close();
            }else {
                $smt->close();
                $smt = $conn2->prepare($insert);
                $time = date("H:i:s");
                $smt->bind_param("ssss",$time,$time,$date,$userid);
                $smt->execute();
                echo  "TIME = ".$time;
                $smt->close();
                $conn2->close();
            }
        }elseif (isset($_GET['checkactive'])) {
            $select = "SELECT COUNT(user_id) AS 'totals' FROM `logs` where `date`= ? and `active_time` >= ?";
            $stmt = $conn2->prepare($select);
            $date = date("Y-m-d");
            $time = date("H:i:s",strtotime("3598 seconds"));
            $stmt->bind_param("ss",$date,$time);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row=$result->fetch_assoc()){
                    echo $row['totals']." User(s)";
                }
            }
            $stmt->close();
            $conn2->close();
        }elseif (isset($_GET['totaluserspresent'])) {
            $select = "SELECT COUNT(fullname) AS 'Total' FROM `user_tbl` where `school_code` = ?";
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $schoolcodes = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$schoolcodes);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row=$result->fetch_assoc()){
                    echo $row['Total']." user(s)";
                }
            }
        }elseif (isset($_GET['schoolfeesrecieved'])) {
            $select = "SELECT sum(`amount`) AS 'Amount' FROM `finance` WHERE date_of_transaction = ?";
            include("../../comma.php");
            $stmt = $conn2->prepare($select);
            $date = date("Y-m-d");
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $res = $stmt->get_result();
            $total_all = "Ksh 0";
            if($res){
                if ($rowed = $res->fetch_assoc()) {
                    if (isset($rowed['Amount'])) {
                        $total_all = "Ksh ".comma($rowed['Amount']);   
                    }
                }
            }
            echo $total_all;
        }elseif (isset($_GET['updatingpassword'])) {
            $password = $_GET['updatingpassword'];
            $userid = $_GET['usersids'];
            include("../../connections/conn1.php");
            include("../../assets/encrypt/encrypt.php");
            $update = 'UPDATE `user_tbl` SET `password` = ? WHERE `user_id` = ?';
            $stmt = $conn->prepare($update);
            $password = encryptCode($password);
            $stmt->bind_param("ss",$password,$userid);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Password update was successfull!</p>";
                $staff_infor = getMyStaffIn4($conn,$userid);
                $log_text = "Changed the password of ".(is_array($staff_infor) ? ucwords(strtolower($staff_infor['fullname'])) : "N/A")." successfully!";
                log_administration($log_text);
            }else {
                echo "<p style='color:red;'>Password update wasn`t successfull!</p>";
            }
        }elseif (isset($_GET['get_CLassteacher'])) {
            $select = "SELECT `class_teacher_id`,`class_assigned`,`active` FROM `class_teacher_tbl`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            include("../../connections/conn1.php");
            if ($result) {
                $table_information = "<div class='tableme'><table>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Class Assigned</th>
                                            <th>Option</th>
                                        </tr>";
                                        $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    //get teacher name
                    $xs++;
                    $tr_name = getTeacherName($conn,$row['class_teacher_id']);
                    $daros = $row['class_assigned'];
                    if (strlen($daros)==1){
                        $daros = "Class ".$row['class_assigned'];
                    }
                    $table_information.="<tr>
                                            <td>".$xs.". </td>
                                            <td id= 'ccN".$row['class_teacher_id']."'>".$tr_name."</td>
                                            <td id= 'ccD".$row['class_teacher_id']."'>".$daros."</td>
                                            <td><p style='margin:1px;font-size:12px;' class='change_classteacher link' id='cc".$row['class_teacher_id']."'><i class='fa fa-pen'></i> Edit</p></td>
                                        </tr>";
                }
                $table_information.="</table></div>";
                if ($xs > 0) {
                    echo $table_information;
                }else {
                    echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p class='' >No records found! </p>
                        </div>";
                }
            }
        }elseif (isset($_GET['get_available_teacher'])) {
            //get the teachers with classes
            $select = "SELECT `class_teacher_id` FROM `class_teacher_tbl`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $tr_with_class = "";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $tr_with_class.=$row['class_teacher_id'].",";
                }
                $split_p_tr = [];
                if (strlen($tr_with_class) > 0) {
                    //remove comma
                    $tr_with_class = substr($tr_with_class,0,strlen($tr_with_class)-1);
                    $split_p_tr = explode(",",$tr_with_class);
                }
                include("../../connections/conn1.php");
                //get the class teachers
                $select = "SELECT `user_id` FROM `user_tbl` WHERE `school_code` = ? AND `auth` = 5";
                $schoolcode = $_SESSION['schoolcode'];
                $stmt = $conn->prepare($select);
                $stmt->bind_param("s",$schoolcode);
                $stmt->execute();
                $result = $stmt->get_result();
                $tr_class = "";
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $tr_class.=$row['user_id'].",";
                    }
                }
                if (strlen($tr_class) > 0) {
                    //remove comma
                    $tr_class = substr($tr_class,0,strlen($tr_class)-1);
                }
                //of all the teachers dont include those who are not present
                $newstring = "";
                $split_tr = explode(",",$tr_class);
                for ($xd=0; $xd < count($split_tr); $xd++) { 
                    $present = checkPresnt($split_p_tr,$split_tr[$xd]);
                    if ($present == 0) {
                        $newstring.=$split_tr[$xd].",";
                    }
                }
                $tr_with_out_class = [];
                if (strlen($newstring) > 0) {
                    //remove comma
                    $newstring = substr($newstring,0,strlen($newstring)-1);
                    $tr_with_out_class = explode(",",$newstring);
                }
                
                //completed teacher list who are class teachers
                if (count($tr_with_out_class) > 0) {
                    $datatoshow="<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id=''>";
                    for ($index=0; $index < count($tr_with_out_class); $index++) { 
                        $trnames = getTeacherName($conn,$tr_with_out_class[$index]);
                        $datatoshow.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                            <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='data".$tr_with_out_class[$index]."' id=''>".($index+1).". ".$trnames."</label>
                                            <input class='check_subjects hide' type='checkbox' value='".$trnames."' name='' id='data".$tr_with_out_class[$index]."'>
                                        </div>";
                    }
                    $datatoshow.="</div>";
                    echo $datatoshow;
                }else {
                    echo "<p class='text-danger' >No other staff assigned a class teacher role found! </p>";
                }
                
            }
        }elseif (isset($_GET['get_Class_available'])) {
            $select = "SELECT `class_assigned` FROM `class_teacher_tbl`";
            $stmt=$conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $class_assigned = "";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $class_assigned.=$row['class_assigned'].",";
                }
                $class_with_tr = [];
                if(strlen($class_assigned) > 0){
                    $class_assigned = substr($class_assigned,0,strlen($class_assigned)-1);
                    $class_with_tr = explode(",",$class_assigned);
                }
                //found array of classes with teachers

                //find array of all classes that are not present
                $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $class_lists = [];
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        // convert to json
                        $all_classes = isJson_report($row['valued']) ? json_decode($row['valued']) : [];

                        // loop to push class name to the classlist array
                        for ($index=0; $index < count($all_classes); $index++) {
                            array_push($class_lists,$all_classes[$index]->classes);
                        }
                    }
                }
                $new_list = "";
                if (count($class_with_tr) > 0) {
                    for ($ind=0; $ind < count($class_lists); $ind++) { 
                        $present = checkPresnt($class_with_tr,$class_lists[$ind]);
                        if ($present == 0) {
                            $new_list.=$class_lists[$ind].",";
                        }
                    }
                    $new_list = substr($new_list,0,strlen($new_list)-1);
                }else {
                    //display the classlist
                    $new_list = $class_list;
                }
                $classlist_display = explode(",",$new_list);
                $data_to_display = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id=''>";
                $xs = 0;
                if (strlen($new_list) > 0){
                    for ($ind=0; $ind < count($classlist_display); $ind++) { 
                        $xs++;
                        $daros = $classlist_display[$ind];
                        if (strlen($daros)==1){
                            $daros = "Class ".$classlist_display[$ind];
                        }
                        $data_to_display.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='cl_ass".$classlist_display[$ind]."'>".$daros."</label>
                            <input class='check_class' type='checkbox' value='".$classlist_display[$ind]."' name='' id='cl_ass".$classlist_display[$ind]."'>
                        </div>";
                    }
                    $data_to_display.="</div>";
                }
                if ($xs>0) {
                    echo $data_to_display;
                }else {
                    $data_to_display = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                    $data_to_display.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                    $data_to_display.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>No Classes present</label>";
                    $data_to_display.="</div>";
                    $data_to_display.="</div>";
                    echo $data_to_display;
                }
            }
        }elseif (isset($_GET['add_classteacher'])) {
            $class = $_GET['clas_s'];
            $teacher_ids = $_GET['teacher_ids'];
            $insert = "INSERT INTO class_teacher_tbl (class_teacher_id,class_assigned,active) VALUES (?,?,?)";
            $active = 1;
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sss",$teacher_ids,$class,$active);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;'>Class teacher assigned successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;'>An error occured!<br>Please try again later!</p>";
            }
        }elseif (isset($_GET['teacher_unassign_id'])) {
            $tr_id = $_GET['teacher_unassign_id'];
            $delete = "DELETE FROM `class_teacher_tbl` WHERE `class_teacher_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$tr_id);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;'>Unassignement was successfull</p>";
            }else {
                echo "<p style='color:red;font-size:12px;'>An error has occured.<br>Please try again later!</p>";
            }
        }elseif (isset($_GET['get_teacher_for_subject'])) {
            //get the teachers with classes
            $select = "SELECT `class_teacher_id` FROM `class_teacher_tbl`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $tr_with_class = "";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $tr_with_class.=$row['class_teacher_id'].",";
                }
                $split_p_tr = [];
                if (strlen($tr_with_class) > 0) {
                    //remove comma
                    $tr_with_class = substr($tr_with_class,0,strlen($tr_with_class)-1);
                    $split_p_tr = explode(",",$tr_with_class);
                }
                include("../../connections/conn1.php");
                //get the class teachers
                $select = "SELECT `user_id` FROM `user_tbl` WHERE `school_code` = ? AND `auth` = 5";
                $schoolcode = $_SESSION['schoolcode'];
                $stmt = $conn->prepare($select);
                $stmt->bind_param("s",$schoolcode);
                $stmt->execute();
                $result = $stmt->get_result();
                $tr_class = "";
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $tr_class.=$row['user_id'].",";
                    }
                }
                if (strlen($tr_class) > 0) {
                    //remove comma
                    $tr_class = substr($tr_class,0,strlen($tr_class)-1);
                }
                //of all the teachers dont include those who are not present
                $newstring = "";
                $split_tr = explode(",",$tr_class);
                for ($xd=0; $xd < count($split_tr); $xd++) { 
                    $present = checkPresnt($split_p_tr,$split_tr[$xd]);
                    if ($present == 0) {
                        $newstring.=$split_tr[$xd].",";
                    }
                }
                $tr_with_out_class = [];
                if (strlen($newstring) > 0) {
                    //remove comma
                    $newstring = substr($newstring,0,strlen($newstring)-1);
                    $tr_with_out_class = explode(",",$newstring);
                }
                
                //completed teacher list who are class teachers
                if (count($tr_with_out_class) > 0) {
                    $datatoshow="<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id=''>";
                    for ($index=0; $index < count($tr_with_out_class); $index++) { 
                        $trnames = getTeacherName($conn,$tr_with_out_class[$index]);
                        $datatoshow.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                            <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='tr_subs".$tr_with_out_class[$index]."' id=''>".($index+1).". ".$trnames."</label>
                                            <input class='check_teachers_subjects ' type='checkbox' value='".$trnames."' name='' id='tr_subs".$tr_with_out_class[$index]."'>
                                        </div>";
                    }
                    $datatoshow.="</div>";
                    echo $datatoshow;
                }else {
                    echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p class='' >No class teachers found! </p>
                        </div>";
                }
                
            }
        }elseif (isset($_GET['replace_tr_id'])) {
            $replace_tr = $_GET['replace_tr_id'];
            $existing_id = $_GET['existing_id'];
            $select = "UPDATE `class_teacher_tbl` SET `class_teacher_id` = ? WHERE `class_teacher_id` = ?";
            $smt = $conn2->prepare($select);
            $smt->bind_param("ss",$replace_tr,$existing_id);
            if($smt->execute()){
                echo "<p style='color:green;font-size:12px;'>Update was done successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;'>An error has occured!<br>Please try again later!</p>";
            }
        }elseif (isset($_GET['getclass'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $select_class_id = $_GET['select_class_id'];
            $value_prefix = $_GET['value_prefix'];
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }

                    // class explode
                    $class_explode = $all_classes;


                    $counter = 0;
                    $string_to_display = "<select class='form-control w-100' name='".$select_class_id."' id='".$select_class_id."'> <option value='' hidden>Select..</option>";

                    if($select_class_id == "selclass"){
                        $string_to_display.="<option value='others'>Other Students</option>";
                    }
                    
                    if($select_class_id != "daros"){
                        $string_to_display.="<option  id='".$value_prefix."-2' value='-2'>Transfered</option>";
                        $string_to_display.="<option  id='".$value_prefix."-1' value='-1'>Alumni</option>";
                    }

                    for ($xs=count($class_explode)-1; $xs >= 0; $xs--) { 
                        $counter++;
                        if (strlen($value_prefix) > 0) {
                            $string_to_display.="<option id='".$value_prefix.$class_explode[$xs]."' value='".$class_explode[$xs]."'>".myClassName($class_explode[$xs])."</option>";
                        }else {
                            $string_to_display.="<option value='".$class_explode[$xs]."'>".myClassName($class_explode[$xs])."</option>";
                        }
                    }
                    if($select_class_id == "daros"){
                        $string_to_display.="<option  id='".$value_prefix."-3' value='-3'>Un-Assigned Payments</option>";
                    }
                    $string_to_display.="</select>";
                    if ($counter > 1) {
                        echo $string_to_display;
                    }else {
                        echo "<p class='red_notice'>No classes to choose<br>Contact your administrator to rectify the issue!</p>";
                    }
                }
            }
        }elseif (isset($_GET['getmyClassList'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "";
                if ($row = $result->fetch_assoc()) {
                    // check if the class is json
                    $class_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    if (count($class_list) > 0) {
                        // create the data to display
                        $data_to_display.="<div class='w-100 tableme'><div class='table_holder'><table class='table'><tr>
                                <th>No.</th>
                                <th>Class</th>
                                <th>Arrange</th>
                                <th>Options</th>
                            </tr>";
                        for ($index=0; $index < count($class_list); $index++) { 
                            $select_options = "";
                            for ($ind=0; $ind < count($class_list); $ind++) {
                                if ($class_list[$ind]->id != $class_list[$index]->id) {
                                    $select_options .= "<option value ='[".$ind.",".$class_list[$index]->id."]'>After ".myClassName($class_list[$ind]->classes)."</option>";
                                }
                            }
                            $at_beginning = "<option value='[-1,".$class_list[$index]->id."]'>At the beginning</option>";
                            $data_to_display.="<tr>
                                                <td> <input hidden value='".json_encode($class_list[$index])."' id='class_value_".$class_list[$index]->id."'>".($index+1).". </td>
                                                <td id='cll".$class_list[$index]->id."'>".myClassName($class_list[$index]->classes)."</td>
                                                <td>
                                                    <select class='form-control arrange_class'>
                                                        <option hidden value=''>Select an option</option>
                                                        ".$select_options."
                                                    </select>
                                                </td>
                                                <td><span class='link remove_class mx-2' = id='clm".$class_list[$index]->id."' style='font-size:12px; color:brown;'><i class='fa fa-trash'></i></span><span class='link change_classes' = id='change_classes".$class_list[$index]->id."' style='font-size:12px; color:brown;'><i class='fa fa-pen-fancy'></i></span></td>
                                                </tr>";
                        }
                        $data_to_display.="</table></div></div>";
                    }else {
                        $data_to_display.="<p class='red_notice'>No classes to display!</p>";
                    }
                }
                echo $data_to_display;
            }
        }elseif (isset($_GET['loginHours'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `from_time`, `to_time` FROM `school_information` WHERE `school_code` = ? ";
            $stmt=$conn->prepare($select);
            $stmt->bind_param("s",$_SESSION['schoolcode']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    if (strlen($row['from_time']) > 0) {
                        echo $row['from_time']."|".$row['to_time'];
                    }else {
                        echo "";
                    }
                }
            }
        }elseif (isset($_GET['academicCalender'])) {
            $select = "SELECT `term`,`start_time` , `end_time` ,`closing_date` FROM  `academic_calendar`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<div class='table_holders'><table>
                                        <tr>
                                            <th>No. </th>
                                            <th>Term</th>
                                            <th>Opening day</th>
                                            <th>Closing date:</th>
                                            <th>Ending date</th>
                                        </tr>";
                                        $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<tr>
                                        <td>".$xs.".</td>
                                        <td>".$row['term']."</td>
                                        <td>".date("M-d-Y",strtotime($row['start_time']))."</td>
                                        <td>".date("M-d-Y",strtotime($row['closing_date']))."</td>
                                        <td>".date("M-d-Y",strtotime($row['end_time']))."</td>
                                    </tr>";
                }
                $data_to_display.="</table></div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='green_notice'>No academic calender!</p>";
                }
            }
        }elseif (isset($_GET['staff_role_changes'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $roles_sets = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $roles_sets = $row['valued'];
                }
            }

            if (strlen($roles_sets) > 0) {
                $roles = json_decode($roles_sets);
                if (count($roles) > 0) {
                    // echo "<br>Counters ".count($roles)."in".var_dump($roles);
                    // loop through the data
                    for ($index1=0; $index1 < count($roles); $index1++) { 
                        $user_roles = $roles[$index1]->roles;
                        $present = checkRolePresent($user_roles,"humanresource");
                        if (!$present) {
                            $new_roles_array = [];
                            // loop through the roles and add the human resource button after the sixth button
                            $user_data = array("name" => "humanresource","Status" => "no");
                            for ($index2=0; $index2 < count($user_roles); $index2++) { 
                                // add arrays in this list
                                array_push($new_roles_array,$user_roles[$index2]);
                                if ($index2 == 5) {
                                    array_push($new_roles_array,$user_data);
                                }
                            }
                            // replace the new roles from the old role
                            $roles[$index1]->roles = $new_roles_array;
                        }
                    }
                    $new_changes = json_encode($roles);
                    $update = "UPDATE `settings` SET `valued` = '$new_changes' WHERE `sett` = 'user_roles'";
                    // $update = "UPDATE `settings` set `valued` = '".$new_changes."' WHERE `sett` = 'user_roles'";
                    $stmt = $conn2->prepare($update);
                    if ($stmt->execute()) {
                        // echo "<span class='text-success'>Update has been done successfully!</span>";
                    }else{
                        // echo "<span class='text-danger'>An error has occured during update!</span>";
                    }
                }
            }
        }elseif (isset($_GET['get_adm_essential'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'admissionessentials'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $valued = $row['valued'];
                    $data_to_display = "";
                    if (strlen($valued) > 0) {
                        $data_to_display.="<table>
                                            <tr>
                                                <th>No. </th>
                                                <th>Admission item</th>
                                                <th>Delete</th>
                                            </tr>";
                        $split_val = explode(",",$valued);
                        $xs = 0;
                        for ($dc=0; $dc < count($split_val); $dc++) {
                            $xs++;
                            $data_to_display.="<tr>
                                                <td>".$xs.". </td>
                                                <td>".$split_val[$dc]."</td>
                                                <td><p class='link adms_essent' id='vals".$split_val[$dc]."' style='color:brown;font-size:12px;'><i class='fa fa-trash'></i></p></td>
                                            </tr>";
                        }
                        $data_to_display.="</table>";
                    }else {
                        $data_to_display.="<p class='red_notice'>No admission essentials present!</p>";
                    }
                }
            }
            echo $data_to_display;
        }elseif (isset($_GET['add_class'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $old_string_class = $row['valued'];
                    $all_classes = isJson_report($old_string_class) ? json_decode($old_string_class) : [];

                    // get the highest id
                    $high_id = 0;
                    for($index = 0; $index < count($all_classes); $index++){
                        if($all_classes[$index]->id > $high_id){
                            $high_id = $all_classes[$index]->id;
                        }
                    }

                    // push the new json array yo the existing one
                    $new_class = new stdClass();
                    $new_class->id = ($high_id+1);
                    $new_class->classes = $_GET['add_class'];
                    array_push($all_classes,$new_class);

                    // decode the json to string
                    $new_classes_list = json_encode($all_classes);

                    // update the database
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'class'";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$new_classes_list);
                    if($stmt->execute()){
                        echo "<p class='green_notice'>Class has been added succesfully!</p>";
                        $log_text = "Class \"".$_GET['add_class']."\" added successfully";
                        log_administration($log_text);
                    }else {
                        echo "<p class='red_notice'>An error has occured!</p>";
                    }
                }
            }
        }elseif (isset($_GET['remove_class'])) {
            $class_remove = $_GET['remove_class'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $class_removed = "N/A";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $class_list = $row['valued'];
                    if (strlen($class_list) > 0) {
                        // get all the classes
                        $json_list = isJson_report($class_list) ? json_decode($class_list) : [];

                        // loop and remove the existing class
                        $new_class = [];
                        for($index = 0; $index < count($json_list); $index++){
                            if($class_remove == $json_list[$index]->id){
                                $class_removed = $json_list[$index]->classes;
                                continue;
                            }
                            array_push($new_class,$json_list[$index]);
                        }

                        // new list to string
                        $new_list = json_encode($new_class);

                        // update the database
                        $update = "UPDATE `settings` set `valued` = ? WHERE `sett` = 'class'";
                        $stmt = $conn2->prepare($update);
                        $stmt->bind_param("s",$new_list);
                        if($stmt->execute()){
                            echo "<p class='green_notice'><b>".myClassName($class_removed)."</b> removed successfully</p>";
                        }else {
                            echo "<p class='red_notice'>An error occured during deleting..<br>Please try again later!</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['change_active_hours'])) {
            include("../../connections/conn1.php");
            $from = $_GET['from'];
            $to = $_GET['to'];
            $update = "UPDATE `school_information` SET `from_time` = ?, `to_time` = ? WHERE `school_code` = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("sss",$from,$to,$_SESSION['schoolcode']);
            if($stmt->execute()){
                $date_from = date_create($from);
                $date_to = date_create($to);
                $difference = date_diff($date_from,$date_to);
                echo $difference->format("Active login period is successfully set to <span class='green_notice'>%H hours %i mins per day</span>");
            }else {
                echo "<p class='red_notice'>An error occured during update!</p>";
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['update_sch_cal'])) {
            $term_one_start = $_GET['term_one_start'];
            $term_one_close = $_GET['term_one_close'];
            $term_one_end = $_GET['term_one_end'];
            $term_two_start = $_GET['term_two_start'];
            $term_two_close = $_GET['term_two_close'];
            $term_two_end = $_GET['term_two_end'];
            $term_three_start = $_GET['term_three_start'];
            $term_three_close = $_GET['term_three_close'];
            $term_three_end = $_GET['term_three_end'];
            // get if when term one starts is greater then when the last year term 3 ended
            $term = "TERM_3";
            $academic_cal = getAcademicStartV1($conn2,$term);
            if ($term_one_start > $academic_cal[1]) {
                // set academic calender to old
                $term = "TERM_1";
                $academic_cal1 = getAcademicStartV1($conn2,$term);
                $term = "TERM_2";
                $academic_cal2 = getAcademicStartV1($conn2,$term);
                $term = "TERM_3";
                $academic_cal3 = getAcademicStartV1($conn2,$term);
                $term_data = '{"TERM_1":{"START_DATE":"'.$academic_cal1[0].'","END_DATE":"'.$academic_cal1[1].'"},"TERM_2":{"START_DATE":"'.$academic_cal2[0].'","END_DATE":"'.$academic_cal2[1].'"},"TERM_3":{"START_DATE":"'.$academic_cal3[0].'","END_DATE":"'.$academic_cal3[1].'"}}';
                // var_dump(json_decode($term_data));
                // get the data from the database
                $select= "SELECT * FROM `settings` WHERE `sett` = 'last_acad_yr'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $settings = $row['valued'];
                        $last_year = json_decode($settings);
                        array_push($last_year,json_decode($term_data));
                        $last_year = json_encode($last_year);
                        // echo $last_year;
                        $update = "UPDATE `settings` SET `valued` = '".$last_year."' WHERE `sett` = 'last_acad_yr'";
                        $stmt = $conn2->prepare($update);
                        $stmt->execute();
                    }else{
                        // meaning its not present we insert
                        $term_data = "[".$term_data."]";
                        $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('last_acad_yr','".$term_data."')";
                        $stmt = $conn2->prepare($insert);
                        $stmt->execute();
                    }
                }else{
                    // meaning its not present we insert
                    $term_data = "[".$term_data."]";
                    $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('last_acad_yr','".$term_data."')";
                    $stmt = $conn2->prepare($insert);
                    $stmt->execute();
                }
                // echo var_dump($term_data);

            }
            $update = "UPDATE `academic_calendar` SET `start_time` = ? , `end_time` = ? , `closing_date` = ? WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            //term one
            $term = 1;
            $stmt->bind_param("ssss",$term_one_start,$term_one_end,$term_one_close,$term);
            $stmt->execute();
            //term two
            $term = 2;
            $stmt->bind_param("ssss",$term_two_start,$term_two_end,$term_two_close,$term);
            $stmt->execute();
            //term three
            $term = 3;
            $stmt->bind_param("ssss",$term_three_start,$term_three_end,$term_three_close,$term);
            $stmt->execute();
            // log text
            $log_text = "Academic calender has been updated successfully!";
            log_administration($log_text);
        }elseif (isset($_GET['add_admission_ess'])) {
            $component = $_GET['component'];
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'admissionessentials'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $admission_components = $row['valued'];
                    if (strlen($admission_components) > 0) {
                        $admission_components.=",".$component;
                    }else {
                        $admission_components.=$component;
                    }
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'admissionessentials'";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$admission_components);
                    $stmt->execute();
                }
            }
        }elseif (isset($_GET['remove_components'])) {
            $component_name = $_GET['component_rem'];
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'admissionessentials'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $comps = $row['valued'];
                    if (strlen($comps) > 0) {
                        $split_comps = explode(",",$comps);
                        $new_list = "";
                        for ($xsd=0; $xsd < count($split_comps); $xsd++) { 
                            if ($split_comps[$xsd] == $component_name) {
                            }else {
                                $new_list.=$split_comps[$xsd].",";
                            }
                        }
                        $new_list = substr($new_list,0,strlen($new_list)-1);
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'admissionessentials'";
                        $stmt = $conn2->prepare($update);
                        $stmt->bind_param("s",$new_list);
                        $stmt->execute();
                    }
                }
            }
        }elseif (isset($_GET['usernames_value'])) {
            include("../../connections/conn1.php");
            $usernames_value = $_GET['usernames_value'];
            $select = "SELECT * FROM `user_tbl` WHERE `username` = ? AND `user_id` != ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$usernames_value,$_SESSION['userids']);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "<p class='red_notice'>The username is already used!</p>";
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['transfered_students'])) {
            // get the total number of transfered students
            $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` = '-2';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['Total']." Transfered Student(s)";
                }else {
                    echo "0 Transfered Student(s)";
                }
            }else {
                echo "0 Transfered Student(s)";
            }
        }elseif (isset($_GET['alumnis_number'])) {
            // get the total number of transfered students
            $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` = '-1';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['Total']." Alumni(s)";
                }else {
                    echo "0 Alumni(s)";
                }
            }else {
                echo "0 Alumni(s)";
            }
        }elseif(isset($_GET['get_courses'])){
            // get the levels present
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $levels = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $data = $row['valued'];
                    $levels = isJson_report($data) ? json_decode($data) : [];
                }
            }
            // data to display
            $data_to_display = "<p class='text-danger'>No levels present!</p>";

            // loop
            if(count($levels) > 0){
                $data_to_display = "<select class='form-control' id='course_list_setup'><option value='' hidden>Select course level</option>";
                for ($index=0; $index < count($levels); $index++) { 
                    $data_to_display .= "<option value='".$levels[$index]->id."' >".$levels[$index]->classes."</option>";
                }
                $data_to_display .= "</select>";
            }

            // echo
            echo $data_to_display;
        }elseif(isset($_GET['get_courses_edit'])){
            // get the levels present
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $levels = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $data = $row['valued'];
                    $levels = isJson_report($data) ? json_decode($data) : [];
                }
            }

            // $data_to_display = "<p class='text-danger'>No levels present!</p>";

            // // loop
            // if(count($levels) > 0){
            //     $data_to_display = "";
            //     for ($index=0; $index < count($levels); $index++) { 
            //         $data_to_display.=
            //         "<p>
            //             <input type='checkbox' class='course_level_edit' name='level_edit_".$index."' value='".$levels[$index]->id."' id='level_edit_".$levels[$index]->id."'>
            //             <label for='level_edit_".$levels[$index]->id."' class='form-control-label'>".$levels[$index]->classes."</label>
            //         </p>";
            //     }
            // }

            // // echo
            // echo $data_to_display;

            // data_to_display
            $data_to_display = "<p class='text-danger'>No levels present!</p>";
            $course_level = isset($_GET['course_level']) ? $_GET['course_level'] : "";
            
            // loop
            if(count($levels) > 0){
                $data_to_display = "<select class='form-control' id='course_list_setup_edit'><option value='' hidden>Select course level</option>";
                for ($index=0; $index < count($levels); $index++) { 
                    $selected = $course_level == $levels[$index]->id ? "selected" : "";
                    $data_to_display .= "<option value='".$levels[$index]->id."' ".$selected." >".$levels[$index]->classes."</option>";
                }
                $data_to_display .= "</select>";
            }
            
            // echo
            echo $data_to_display;
        }elseif(isset($_GET['get_supplier_data'])){
            $select_payments = "SELECT SBP.* FROM `supplier_bill_payments` AS SBP
                                LEFT JOIN `supplier_bills` AS SB
                                ON SB.bill_id = SBP.payment_for
                                WHERE SB.supplier_id = '".$_GET['get_supplier_data']."' ORDER BY SBP.date_paid DESC";
                                
            $select_bill = "SELECT * FROM `supplier_bills` WHERE `supplier_id` = '".$_GET['get_supplier_data']."' ORDER BY date_assigned DESC";
            // echo $select_bill;
            // get the bill
            $stmt = $conn2->prepare($select_bill);
            $stmt->execute();
            $result = $stmt->get_result();
            $supplier_bill = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['real_date'] = $row['date_assigned'];
                    $row['date'] = date("D dS M Y",strtotime($row['date_assigned']));
                    $row['real_amount'] = $row['bill_amount'];
                    $row['bill_amount'] = "".number_format($row['bill_amount']);
                    array_push($supplier_bill,$row);
                }
            }

            // get the payments
            $stmt = $conn2->prepare($select_payments);
            $stmt->execute();
            $result = $stmt->get_result();
            $supplier_payment = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['real_date'] = strtotime($row['date_paid']);
                    $row['date'] = date("D dS M Y",strtotime($row['date_paid']));
                    $row['real_amount'] = $row['amount'];
                    $row['amount'] = number_format($row['amount']);
                    array_push($supplier_payment,$row);
                }
            }

            // return data
            $data = array("supplier_bill" => $supplier_bill, "supplier_payment" => $supplier_payment);
            echo json_encode($data);
        }elseif(isset($_GET['edit_course'])){
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            $course_name = $_GET['course_name'];
            $course_levels = $_GET['course_levels'];
            $department_name = $_GET['department_name'];
            $course_id = $_GET['course_id'];
            $course_level = $_GET['course_level'];
            $no_of_terms = $_GET['no_of_terms'];
            $termly_fees = $_GET['termly_fees'];
            $term_duration = $_GET['term_duration'];
            $duration_intervals = $_GET['duration_intervals'];


            if ($result) {
                if($row = $result->fetch_assoc()){
                    // arrays
                    $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];

                    // update where neccessary
                    $new_courses = new stdClass();
                    $new_courses->id = $course_id;
                    $new_courses->course_name = $course_name;
                    $new_courses->course_levels = $course_levels;
                    $new_courses->department = $department_name;
                    $new_courses->course_level = $course_level;
                    $new_courses->no_of_terms = $no_of_terms;
                    $new_courses->termly_fees = $termly_fees;
                    $new_courses->term_duration = $term_duration;
                    $new_courses->duration_intervals = $duration_intervals;

                    // add the courses
                    $new_course_data = [];
                    for($ind = 0; $ind < count($courses); $ind++){
                        if($courses[$ind]->id == $_GET['course_id']){
                            array_push($new_course_data,$new_courses);
                            continue;
                        }
                        array_push($new_course_data,$courses[$ind]);
                    }

                    // update the data in the database
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = ?";
                    $stmt = $conn2->prepare($update);
                    $sett = "courses";
                    $valued = json_encode($new_course_data);
                    $stmt->bind_param("ss",$valued,$sett);
                    $stmt->execute();

                    // log text
                    $log_text = "Course \"".$course_name."\" has been updated successfully!";
                    log_administration($log_text);
                    echo "<p class='text-success'>Update has been done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>An error has occured!</p>";
                }
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
            }
        }elseif(isset($_GET['get_departments_course_reg'])){
            $department_id = $_GET['dept_id'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger'>No departments have been set yet!</p>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];

                    // is it valued
                    $data_to_display = "<select id='".$department_id."' class='form-control w-100' required><option hidden value=''>Select department</option>";
                    for ($index=0; $index < count($valued); $index++) { 
                        $data_to_display.="<option value='".$valued[$index]->code."' >".$valued[$index]->name."</option>";
                    }
                    $data_to_display .="</select>";
                }
            }
            echo $data_to_display;
        }elseif(isset($_GET['add_course'])){
            // add the new course to the list of courses there
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    // loop to get the last course index
                    $last_id = 0;
                    for ($index=0; $index < count($courses); $index++) { 
                        if($courses[$index]->id > $last_id){
                           $last_id = $courses[$index] ->id;
                        }
                    }
                    // update the system
                    $new_course = new stdClass();
                    $new_course->id = ($last_id+1);
                    $new_course->course_name = $_GET['course_name'];
                    $new_course->course_levels = "[]";
                    $new_course->department = $_GET['department_name'];
                    $new_course->course_level = $_GET['course_level'];
                    $new_course->no_of_terms = $_GET['no_of_terms'];
                    $new_course->termly_fees = $_GET['termly_fees'];
                    $new_course->term_duration = $_GET['term_duration'];
                    $new_course->duration_intervals = $_GET['duration_intervals'];

                    // add  that to the list of courses present
                    array_push($courses,$new_course);

                    // update the table
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'courses'";
                    $stmt = $conn2->prepare($update);
                    $courses = json_encode($courses);
                    $stmt->bind_param("s",$courses);
                    $stmt->execute();
                }else{
                    // add the new record
                    $new_course = new stdClass();
                    $new_course->id = 1;
                    $new_course->course_name = $_GET['course_name'];
                    $new_course->course_levels = $_GET['course_levels'];
                    $new_course->department = $_GET['department_name'];
                    $new_course->course_level = $_GET['course_level'];
                    $new_course->no_of_terms = $_GET['no_of_terms'];
                    $new_course->termly_fees = $_GET['termly_fees'];
                    $new_course->term_duration = $_GET['term_duration'];
                    $new_course->duration_intervals = $_GET['duration_intervals'];

                    // save to the database
                    $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?)";
                    $stmt = $conn2->prepare($insert);
                    $new_course = json_encode([$new_course]);
                    $sett_value = 'courses';
                    $stmt->bind_param("ss",$sett_value,$new_course);
                    $stmt->execute();
                }

                // success message
                $log_text = "Course \"".$_GET['course_name']."\" has been added successfully!";
                log_administration($log_text);
                echo "<p class='text-success'>Course has been added successfully!</p>";
            }else{
                // error message
                echo "<p class='text-danger'>Course has not been added, an error occured!</p>";
            }
        }elseif(isset($_GET['get_courses_list'])){
            // get the departments
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            // departments
            $department = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // departments
                    $department = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // get levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            // departments
            $course_levels = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // departments
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // select the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $data_to_display = "<div class='w-100 table_holder p-0'>
                                            <table class='table'>
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Course</th>
                                                        <th>Levels Offered</th>
                                                        <th>Department</th>
                                                        <th>Options</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";
                    for ($index=0; $index < count($course_list); $index++) {
                        // get the department
                        $department_name = "Null";
                        for ($ind=0; $ind < count($department); $ind++) { 
                            if($course_list[$index]->department == $department[$ind]->code){
                                $department_name = $department[$ind]->name;
                                break;
                            }
                        }
                        
                        // get the levels
                        // $levels = "No classes selected";
                        // $level_ids = isJson_report($course_list[$index]->course_levels) ? json_decode($course_list[$index]->course_levels) : [];
                        // // echo $course_list[$index]->course_levels."<br>";
                        // if(count($level_ids) > 0){
                        //     $levels = "<ul>";
                        //     for($ind = 0; $ind < count($level_ids); $ind++){
                        //         $level_name = $level_ids[$ind];
                        //         for($in = 0; $in < count($course_levels); $in++){
                        //             // echo $course_levels[$in]->id." ".$level_name." ".$course_levels[$in]->classes."<br>";
                        //             if($level_name == $course_levels[$in]->id){
                        //                 $level_name = $course_levels[$in]->classes;
                        //                 break;
                        //             }
                        //         }
                        //         $levels .= "<li>".$level_name."</li>";
                        //     }
                        // }
                        // $levels.="</ul>";

                        $level = "Level not defined!";
                        if(isset($course_list[$index]->course_level)){
                            for($in = 0; $in < count($course_levels); $in++){
                                // echo $course_levels[$in]->id." ".$level_name." ".$course_levels[$in]->classes."<br>";
                                if($course_list[$index]->course_level == $course_levels[$in]->id){
                                    $level = $course_levels[$in]->classes;
                                    break;
                                }
                            }
                        }


                        // data to display
                        $data_to_display .= "<tr>
                                                <td>".($index+1).". </td>
                                                <td>".$course_list[$index]->course_name."</td>
                                                <td>".$level."</td>
                                                <td>".$department_name."</td>
                                                <td>
                                                    <input hidden value='".json_encode($course_list[$index])."' id='hidden_value_courses_".$course_list[$index]->id."'>
                                                    <span class='link remove_course mx-2' id='remove_course_".$course_list[$index]->id."' style='font-size:12px; color:brown;'><i class='fa fa-trash'></i></span>
                                                    <span class='link edit_courses' id='edit_course_".$course_list[$index]->id."' style='font-size:12px; color:brown;'><i class='fa fa-pen-fancy'></i></span>
                                                </td>
                                            </tr>";
                    }
                }
            }

            // display the data
            echo $data_to_display;
        }elseif(isset($_GET['delete_course'])){
            // delete course
            $delete_course = $_GET['delete_course'];
            $course_id = $_GET['course_id'];

            // get all the course
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // get result
            if($result){
                if($row = $result->fetch_assoc()){
                    // valued
                    $course_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // new_arrays to hold the new arrays
                    $new_array = [];
                    $course_name = "N/A";
                    for($index = 0; $index < count($course_list); $index++){
                        if($course_list[$index]->id == $course_id){
                            $course_name = $course_list[$index]->course_name;
                            continue;
                        }

                        // push the other courses
                        array_push($new_array,$course_list[$index]);
                    }

                    // echo
                    // echo json_encode($new_array);

                    // update the database
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = ?";
                    $stmt = $conn2->prepare($update);
                    $valued = json_encode($new_array);
                    $sett = "courses";
                    $stmt->bind_param("ss",$valued,$sett);
                    $stmt->execute();

                    // echo
                    
                    // log text
                    $log_text = "Course \"".$course_name."\" has been deleted successfully!";
                    log_administration($log_text);
                    echo "<p class='text-success'>Update has been done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>An error has occured, Try again later!</p>";
                }
            }else{
                echo "<p class='text-danger'>An error has occured, Try again later!</p>";
            }
        }elseif (isset($_GET['get_loggers'])) {
            include("../../connections/conn1.php");
            $get_loggers = $_GET['get_loggers'];
            $select = "SELECT `id` , `login_time`,`active_time`,`date`,`user_id` FROM `logs` WHERE `date` = ?";
            $date = date("Y-m-d");
            $time = date("H:i:s",strtotime("59 minutes"));
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<h6>Active logs for Today (<u>".date("M-dS-Y",strtotime($date))."</u>)</h6><table style='margin-left:10px;'>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Time Login</th>
                                        <th>Last time Active</th>
                                        <th>Status</th>
                                    </tr>";
                                    $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $time = date("H:i:s");
                    $time1 = date_create($time);
                    $time2 = date_create($row['active_time']);
                    $time3 = date_diff($time2,$time1);
                    $timeval = $time3->format("%s");
                    $status = "<td>In-Active</td>";
                    $hour = $time3->format("%h");
                    $min = $time3->format("%i");
                    if ($timeval <= 2) {
                        if ($hour < 1) {
                            if ($min < 1) {
                                $status = "<td class='bg_green'>Active</td>";                                
                            }
                        }
                    }
                    $data_to_display.="<tr>
                                        <td>".$xs.".</td>
                                        <td>".getTeacherName($conn,$row['user_id'])."</td>
                                        <td>".$row['login_time']."</td>
                                        <td>".$row['active_time']."</td>
                                        ".$status."
                                    </tr>";
                }
                $data_to_display.="</table>";
                if ($xs > 0) {
                    echo $data_to_display;
                }
            }
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['date_logs'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `id` , `login_time`,`active_time`,`date`,`user_id` FROM `logs` WHERE `date` = ?";
            $date = $_GET['date_logs'];
            $time = date("H:i:s",strtotime("59 minutes"));
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<h6>Logs for (<u>".date("l M-dS-Y",strtotime($date))."</u>)</h6><table style='margin-left:10px;'>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Time Login</th>
                                        <th>Last time Active</th>
                                        <th>Date</th>
                                    </tr>";
                                    $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $time = date("H:i:s");
                    $time1 = date_create($time);
                    $time2 = date_create($row['active_time']);
                    $time3 = date_diff($time2,$time1);
                    $timeval = $time3->format("%s");
                    $data_to_display.="<tr>
                                        <td>".$xs.".</td>
                                        <td>".getTeacherName($conn,$row['user_id'])."</td>
                                        <td>".$row['login_time']."</td>
                                        <td>".$row['active_time']."</td>
                                        <td>".date("M-d-Y",strtotime($date))."</td>
                                    </tr>";
                }
                $data_to_display.="</table>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No logs present on : ".date("M-d-Y",strtotime($date))."</p>";
                }
            }
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['change_dp_local'])) {
            include("../../connections/conn1.php");
            $new_locale = $_SESSION['imagepath1'];
            $update = "UPDATE `user_tbl` SET `profile_loc` = ? WHERE `user_id` = ?";
            $stmt = $conn->prepare($update);
            $imagepath = $_SESSION['imagepath1'];
            $myids = $_SESSION['userids'];
            $stmt->bind_param("ss",$imagepath,$myids);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['getImages_dp'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `profile_loc` FROM `user_tbl` WHERE `user_id` = ?";
            $stmt = $conn->prepare($select);
            $myids = $_SESSION['userids'];
            $stmt->bind_param("s",$myids);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if ($row = $result->fetch_assoc()) {
                    echo $row['profile_loc'];
                }
            }
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['change_dp_school'])) {
            include("../../connections/conn1.php");
            $update = "UPDATE `school_information` SET `school_profile_image` = ? WHERE `school_code` = ?";
            $stmt = $conn->prepare($update);
            $path = $_SESSION['imagepath2'];
            $stmt->bind_param("ss",$path,$_SESSION['schoolcode']);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['bring_me_sch_dp'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `school_profile_image` FROM `school_information` WHERE `school_code` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$_SESSION['schoolcode']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['school_profile_image'];
                }
            }
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['number_of_me_studnets'])) {
            $class_taught = getClassTaught($conn2);
            if ($class_taught != "Null") {
                //get the total number of students
                $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` = ?";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$class_taught);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        echo myClassName($class_taught).":<br> ".$row['Total']." student(s)";
                    }else {
                        echo "Err";
                    }
                }else {
                    echo "Err";
                }
            }else {
                echo "<p class='red_notice'>Class not assigned!</p>";
            }
        }elseif (isset($_GET['reg_today_my_class'])) {
            $class_taught = getClassTaught($conn2);
            if ($class_taught != "Null") {
                $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` = ? AND `D_O_A` = ?";
                $stmt = $conn2->prepare($select);
                $date = date("Y-m-d");
                $stmt->bind_param("ss",$class_taught,$date);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        echo myClassName($class_taught).":<br> ".$row['Total']." student(s)";
                    }else {
                        echo "Err";
                    }
                }else {
                    echo "Err";
                }
            }else {
                echo "<p class='red_notice'>Class not assigned!</p>";
            }
        }elseif (isset($_GET['today_attendance'])) {
            $class_taught = getClassTaught($conn2);
            if ($class_taught != "Null"){
                $select = "SELECT COUNT(*) AS 'Total' FROM `attendancetable` WHERE `class` = ? AND `date` = ?";
                $stmt = $conn2->prepare($select);
                $date = date("Y-m-d");
                $stmt->bind_param("ss",$class_taught,$date);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        echo myClassName($class_taught).":<br>".$row['Total']." student(s)";
                    }else {
                        echo "Err";
                    }
                }else {
                    echo "Err";
                }
            }else {
                echo "<p class='red_notice'>Class not assigned!</p>";
            }
        }elseif (isset($_GET['absent_students'])) {
            $class_taught = getClassTaught($conn2);
            if ($class_taught != "Null"){
                $select = "SELECT COUNT(*) AS 'Total' FROM `attendancetable` WHERE `class` = ? AND `date` = ?";
                $stmt = $conn2->prepare($select);
                $date = date("Y-m-d");
                $stmt->bind_param("ss",$class_taught,$date);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $total1 = $row['Total'];
                        $select = "SELECT COUNT(*) AS 'Totals' FROM `student_data` WHERE `stud_class` = ?";
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("s",$class_taught);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $total2 = $row['Totals'];
                                $total3 = $total2-$total1;
                                echo myClassName($class_taught).":<br>".$total3." student(s)";
                            }
                        }else {
                            echo "Err";
                        }
                    }else {
                        echo "Err";
                    }
                }else {
                    echo "Err";
                }
            }else {
                echo "<p class='red_notice'>Class not assigned!</p>";
            }
        }elseif (isset($_GET['feedback_message'])) {
            include("../../connections/conn1.php");
            $feedback_message = $_GET['feedback_message'];
            $insert = "INSERT INTO `user_feedback` (`from_id`,`feedback`,`deleted`) VALUES (?,?,?)";
            $stmt = $conn->prepare($insert);
            $deleted = 0;
            $userids = $_SESSION['userids'];
            $stmt->bind_param("sss",$userids,$feedback_message,$deleted);
            if($stmt->execute()){
                $authers = getAuthority1($conn,$userids);
                if ($authers == "Null") {
                    $authers = "all";
                }
                //insert the notification to the database 
                $notice_stat = 0;
                $reciever_id = $userids;
                $reciever_auth = $authers;
                $messageName = "Thanks for the feedback!";
                $messagecontent = "We really value your feedback, we`ll review it and use it to make your experience better as we go.<br><b>Thank you!</b>";
                $sender_ids = "Ladybird SMIS";
                insertNotice($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids);
                echo "<p class='green_notice'>Feedback sent successfully!</p>";
            }else {
                echo "<p class='red_notice'>An error has occured<br> Please try again later!</p>";
            }
            $stmt->close();
            $conn->close();
            $conn2->close();
        }elseif (isset($_GET['get_attendance_school'])) {
            $class_list = getClasses($conn2);
            $select = "SELECT COUNT(`admission_no`) AS 'Total' FROM `attendancetable` WHERE `class` = ? AND `date` = ?";
            $stmt = $conn2->prepare($select);
            $data_to_display = "<h3 class='my-2'>View Attendances</h3><br><h6>School`s students attendance on <span class='text-primary'>(".date("D M-d-Y",strtotime($_GET['dated'])).")</span></h6><table class='table'>
                                <tr>
                                    <th>No.</th>
                                    <th>Class</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>option</th>
                                </tr>";
                                $xs = 0;
                                $total_present = 0;
                                $total_absent = 0;
            for ($index=0; $index < count($class_list); $index++) { 
                $xs++;
                $stmt->bind_param("ss",$class_list[$index],$_GET['dated']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $present_total = $row['Total'];
                        $class_pop = getClassCount($conn2,$class_list[$index]);
                        $absent_no = $class_pop - $present_total;
                        $total_absent+=$absent_no;
                        $total_present+=$present_total;
                        $bgs = "color:green;";
                        if ($absent_no > 0) {
                            $bgs = "color:red;";
                        }
                        $data_to_display.="<tr>
                                            <td>".$xs.".</td>
                                            <td>".myClassName($class_list[$index])."</td>
                                            <td>".$present_total." Student(s)</td>
                                            <td style='".$bgs."'>".$absent_no." Student(s)</td>
                                            <td><p class='link view_stud_attendance' style='font-size:12px;' id='".$class_list[$index]."'><i class='fa fa-eye'></i> View</p></td>
                                        </tr>";
                    }
                }
            }
            $data_to_display.="<tr><td></td><td>Total</td><td>".$total_present." Student(s)</td><td>".$total_absent." Student(s)</td></tr></table>";
            echo $data_to_display;
        }elseif (isset($_GET['allowct'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `ct_cg` FROM `school_information` WHERE `school_code` = ?";
            $stmt = $conn->prepare($select);
            $schoolcode = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$schoolcode);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['ct_cg'];
                }
            }
        }elseif (isset($_GET['update_ct'])) {
            include("../../connections/conn1.php");
            $ct_cg_value = $_GET['ct_cg_value'];
            $update = "UPDATE `school_information` SET `ct_cg` = ? WHERE `school_information`.`school_code` = ?";
            $stmt = $conn->prepare($update);
            $schoolcode = $_SESSION['schoolcode'];
            $stmt->bind_param("ss",$ct_cg_value,$schoolcode);
            if ($stmt->execute()) {
                echo "<p class='green_notice'>Data updated successfully!</p>";
            }else {
                echo "<p class='red_notice'>Error occured during update!</p>";
            }
        }elseif (isset($_GET['generate_adm_auto'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'lastadmgen';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $admno = $row['valued'];
                    $admission_number = checkAdmUsed($conn2,$admno);
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'admission_prefix'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();

                    // get results
                    $res = $stmt->get_result();
                    $prefix = "";
                    if($res){
                        if($row = $res->fetch_assoc()){
                            $valued = $row['valued'];
                            $prefix = $valued;
                        }
                    }

                    // prefix
                    echo $prefix.$admno;
                }
            }
        }elseif (isset($_GET['genmanuall'])) {
            $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($select);
            $admno = $_GET['admno'];
            $stmt->bind_param("s",$admno);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "<p>The admission number is already used!</p>";
            }else {
                echo "";
            }
        }elseif (isset($_GET['getWholeSchool'])) {
            $select = "SELECT * from `student_data`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res){
                $tablein4 = "<div class='tableme'><table class='table table-striped align-items-center '><tr><th>No.</th><th>Class</th><th><i class='fa fa-male'></i> Male</th><th><i class='fa fa-female'></i> Female</th><th><i class='fa fa-male'></i> + <i class='fa fa-female'></i> Total</th><th>Action</th></tr>";
                $classes = getClasses($conn2);
                $classholder = array();
                $classholdermale = array();
                $classholderfemale = array();
                if (count($classes)>0) {
                    for ($i=0; $i < count($classes); $i++) { 
                        $counted = 0;
                        array_push($classholder,$counted);
                        array_push($classholdermale,$counted);
                        array_push($classholderfemale,$counted);
                    }
                }
                $males=0;
                $female = 0;
                while ($row=$res->fetch_assoc()) {
                    for ($i=0; $i < count($classes); ++$i) {
                        if ($classes[$i] == trim($row['stud_class'])) {
                            $classholder[$i]+=1;
                            if ($row['gender']=='Female') {
                                $classholderfemale[$i]+=1;
                                $female++;
                            }
                            if ($row['gender']=='Male') {
                                $classholdermale[$i]+=1;
                                $males++;
                            }
                            break;
                        }
                    }
                }
                $totaled = 0;
                for ($i=0; $i < count($classes); $i++) {
                    $totaled+=$classholder[$i];
                    $daros = $classes[$i];
                    if (strlen($daros)==1){
                        $daros = "Class ".$classes[$i];
                    }
                    $tablein4.="<tr><td>".($i+1)."</td><td style='font-size:13px;font-weight:bold;'>".$daros."</td><td>".$classholdermale[$i]." Student(s)</td><td>".$classholderfemale[$i]." Student(s)</td><td>".$classholder[$i]." Student(s)</td><td>"."<span class='link promoteclass' style='font-size:12px;' id='pm".$classes[$i]."'><i class='fa fa-arrow-up'></i> Promote Class</span>"."</td></tr>";
                }
                $tablein4.="</table></div>";
                $table_2 = "<div class = 'table_holders'><table class='align-items-center'>
                            <tr><th>Gender</th><th>Total</th></tr>
                            <tr><td><i class='fa fa-male'></i> - Male</td><td>".$males."</td></tr>
                            <tr><td><i class='fa fa-female'></i> - Female</td><td>".$female."</td></tr>
                            <tr><td><b>Total</b></td><td><b>".$totaled."</b></td></tr>
                            </table></div>";
                $datas = "<h6 class='text-center w-100'>Displaying all students recognized by the system</h6><br><span style='text-align:center;'><u>Gender count table</u> ".$table_2." <br> </span>";
                echo $datas." <p><u>Student count table</u></p>".$tablein4;
            }else {
                
            }
        }elseif (isset($_GET['getclassData'])) {
            $className = $_GET['classname'];
            $academicYear = "%".getAcadYear($conn2).":".$className;
            $select = "SELECT `surname`,`first_name`,`second_name`,`gender`,`D_O_A`,`stud_class`,`adm_no`,`year_of_study` FROM `student_data` WHERE `stud_class` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$className);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                $data_to_display = "<input type='hidden' id='theClass' value='".$className."'><button class='btn btn-secondary btn-sm my-2' id='goBack3'><i class='fas fa-arrow-left'></i> Back</button><table class='table'><tr><th>No.</th><th>Fullnames</th><th>Date Of Admissions</th><th>Student Class</th><th>Gender</th><th>Select All <input type='checkbox' id='promoSelect'></th></tr>";
                $counter = 1;
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="<tr><td>".$counter."</td><td>".ucwords(strtolower($row['surname']." ".$row['first_name']." ".$row['second_name']))." - {".$row['adm_no']."}</td>";
                    $data_to_display.="<td>".$row['D_O_A']."</td>";
                    $data_to_display.="<td>".myClassName($row['stud_class'])."</td>";
                    $data_to_display.="<td>".$row['gender']."</td>";
                    $data_to_display.="<td>"."<input type='checkbox' class='promotionCheck' id='promo".$row['adm_no']."'>"."</td></tr>";
                    $counter++;
                }
                if ($counter > 1) {
                    $data_to_display.="</table><button class='btn btn-secondary btn-sm my-2' id='promoteStudents'><i class='fas fa-arrow-up'></i> Promote Selected students</button><div class='container'><span id='errHandler44'></span></div>";
                    echo $data_to_display;
                }else {
                    echo "<button class='btn btn-secondary btn-sm my-2' id='goBack3'><i class='fas fa-arrow-left'></i> Back</button><br><span class='text-danger'>No students to be promoted at the moment in Class : ".myClassName($className)."</span>";
                }
            }
        }elseif (isset($_GET['last_admno_used'])) {
            $select = "SELECT * FROM `student_data` ORDER BY `ids` DESC LIMIT 1;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo "Last RegNo. - <b>".$row['adm_no']."</b>";
                }
            }
        }
        elseif (isset($_GET['promote'])) {
            // get the class to know where to promote the student next
            $studClass = $_GET['classselected'];
            $unselected = explode(",",$_GET['unselected']);
            $selectedStd = explode(",",$_GET['selectedStd']);
            //get the class list
            $classList = getTheClass($conn2);
            $classIndex = 0;
            for ($index=0; $index < count($classList); $index++) { 
                $classIndex++;
                if($studClass == $classList[$index]){
                    break;
                }
            }
            $nextClass = -1; // the negative one means that the student is an alumni
            if ($classIndex != count($classList)) {
                $nextClass = $classList[$classIndex];
            }
            // here we update the student data |class|academic year
            // echo "prev class ".$studClass." next class ".$nextClass." next academic year ".getAcadYear($conn2);

            // parameters
            $academicYear = getAcadYear($conn2);

            // update the class
            $updated = 0;
            for ($i=0; $i < count($selectedStd); $i++) { 
                $academicYearStud = studCurrentAcadYear($conn2,$selectedStd[$i])."|".$academicYear.":".$nextClass;
                $update = "UPDATE `student_data` SET `stud_class` = ?, `year_of_study` = ? WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sss",$nextClass,$academicYearStud,$selectedStd[$i]);
                if($stmt->execute()){
                    $updated++;
                }
                // echo $academicYearStud;
            }
            for ($i=0; $i < count($unselected); $i++) {
                $academicYearStud = studCurrentAcadYear($conn2,$unselected[$i])."|".$academicYear.":".$studClass;
                $update = "UPDATE `student_data` SET `stud_class` = ?, `year_of_study` = ? WHERE `adm_no` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sss",$studClass,$academicYearStud,$unselected[$i]);
                if($stmt->execute()){
                    // $updated++;
                }
            }
            if ($updated == count($selectedStd)) {
                echo "<p class='text-success'>".$updated." student(s) successfully promoted to ".myClassName($nextClass)."</p>";
            }else{
                echo "<p class='text-danger'>An error occured during update!</p>";
            }
        }elseif (isset($_GET['enroll_boarding_this'])) {
            $admno = $_GET['enroll_boarding_this'];
            $update = "UPDATE `student_data` SET `boarding` = 'enroll' WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$admno);
            if($stmt->execute()){
                echo "<p class='text-success'>Student has been successfully enrolled for boarding. Proceed to the boarding section to assign the student his/her dormitory!</p>";
            }else {
                echo "<p class='text-danger'>An error has occured. Please try again later!</p>";
            }
        }elseif(isset($_GET['get_expense_subcategory'])){
            $get_expense_subcategory = $_GET['get_expense_subcategory'];
            $select = "SELECT * FROM `expense_category` WHERE `expense_id` = '".$get_expense_subcategory."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $sub_categories = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $expense_sub_categories = $row['expense_sub_categories'];
                    $sub_categories = isJson_report($expense_sub_categories) ? json_decode($expense_sub_categories) : [];
                }
            }

            // expense subcategories
            $data_to_display = "<select class='form-control w-100' id='expense_sub_category'><option hidden value=''>Select an Option</option>";
            for($index = 0; $index < count($sub_categories); $index++){
                $id = $sub_categories[$index]->id;
                $name = $sub_categories[$index]->name;
                $data_to_display .= "<option value='".$get_expense_subcategory.":".$id."' >".$name."</option>";
            }
            $data_to_display .= "</select>";

            // display select
            echo $data_to_display;
        }elseif (isset($_GET['unenroll_boarding_this'])) {
            $admno = $_GET['unenroll_boarding_this'];
            // delete the student from the dormitory
            $delete = "DELETE FROM `boarding_list` WHERE `student_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$admno);
            $stmt->execute();
            $update = "UPDATE `student_data` SET `boarding` = 'none', `boarding` = 'none' WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$admno);
            if($stmt->execute()){
                echo "<p class='text-success'>Student has been successfully unenrolled from boarding!</p>";
            }else {
                echo "<p class='text-danger'>An error has occured. Please try again later!</p>";
            }
        }elseif (isset($_GET['get_profile_image'])) {
            $adm_no = $_GET['admissions_no'];
            $select = "SELECT * FROM `student_data` WHERE `adm_no` = '".$adm_no."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    echo strlen($row['student_image']) > 0 ? $row['student_image'] :"Null";
                }else{
                    echo "Null";
                }
            }else{
                echo "Null";
            }
        }elseif(isset($_GET['delete_leave_apply'])){
            $delete_leave_apply = $_GET['delete_leave_apply'];
            $application_id = $_GET['application_id'];
            $delete = "DELETE FROM `apply_leave` WHERE `id` = '".$application_id."'";
            $stmt = $conn2->prepare($delete);
            if($stmt->execute()){
                echo "<p class='text-success border border-success m-1 p-1'>Application deleted successfully!</p>";
            }else{
                echo "<p class='text-danger border border-danger m-1 p-1'>An error has occured!</p>";
            }
        }elseif (isset($_GET['delete_dps_student'])) {
            $dps = $_GET['delete_dps_student'];
            $select = "SELECT * FROM `student_data` WHERE `adm_no` = '".$dps."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $student_image = $row['student_image'];
                    // get the location of the file
                    if (file_exists("../../".$student_image)) {
                        unlink("../../".$student_image);
                    }else{
                        echo "My niggah";
                    }
                }
            }
            $update = "UPDATE `student_data` SET `student_image` = '' WHERE `adm_no` = '".$dps."'";
            $stmt = $conn2->prepare($update);
            if($stmt->execute()){
                echo "<p class='text-success'>Student image has been deleted successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error occured!</p>";
            }
        }elseif (isset($_GET['sender_name'])) {
            $sender_name = $_GET['sender_name'];
            $email_host_addr = $_GET['email_host_addr'];
            $email_username = $_GET['email_username'];
            $email_password = $_GET['email_password'];
            $tester_mail = $_GET['tester_mail'];

            // first check if the email is already set
            $select = "SELECT * FROM `settings` WHERE `sett` = ?";
            $stmt = $conn2->prepare($select);
            $email = "email_setup";
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            $present = $rnums>0?1:0;
            if ($present == 0) {
                // its absent
                // insert the data of the email setup
                $data = array("sender_name" => $sender_name,"email_host_addr" => $email_host_addr,"email_username"=>$email_username,"email_password"=>$email_password,"tester_mail"=>$tester_mail);
                $newdata = json_encode($data);
                $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?);";
                $stmt = $conn2->prepare($insert);
                $stmt->bind_param("ss",$email,$newdata);
                $stmt->execute();
                echo "<p class='text-success p-1 border border-success my-1'>Email has been set-up successfully!</p>";
            }else {
                $data = array("sender_name" => $sender_name,"email_host_addr" => $email_host_addr,"email_username"=>$email_username,"email_password"=>$email_password,"tester_mail"=>$tester_mail);
                $newdata = json_encode($data);
                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ss",$newdata,$email);
                $stmt->execute();
                echo "<p class='text-success p-1 border border-success my-1'>Email has been set-up successfully!</p>";
            }
        }elseif (isset($_GET['get_email_setups'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        echo $row['valued'];
                    }
                }
            }else{
                echo "";
            }
        }elseif (isset($_GET['remove_email'])) {
            $delete = "DELETE FROM `settings` WHERE `sett` = 'email_setup';";
            $stmt = $conn2->prepare($delete);
            if($stmt->execute()){
                echo "<p class='text-success'>Email set-up removed successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error occured!</p>";
            }
        }elseif (isset($_GET['test_email'])) {
            // collect the email settings first
            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $email_settings = $row['valued'];
                        if (strlen($email_settings) > 0) {
                            // retrieve the email settings
                            $email_sets = json_decode($email_settings);
                            $sender_name = $email_sets->sender_name;
                            $email_host_addr = $email_sets->email_host_addr;
                            $email_username = $email_sets->email_username;
                            $email_password = $email_sets->email_password;
                            $tester_mail = $email_sets->tester_mail;

                            // try sending an email
                            try {
                                $mail = new PHPMailer(true);
                        
                                $mail->isSMTP();
                                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                // $mail->Host = 'smtp.gmail.com';
                                $mail->Host = $email_host_addr;
                                $mail->SMTPAuth = true;
                                // $mail->Username = "hilaryme45@gmail.com";
                                // $mail->Password = "cmksnyxqmcgtncxw";
                                $mail->Username = $email_username;
                                $mail->Password = $email_password;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
                                $mail->Port = 587;
                                
                                
                                $mail->setFrom($email_username,$sender_name);
                                $mail->addAddress($tester_mail);
                                $mail->isHTML(true);
                                $mail->Subject = "Test Message";
                                $mail->Body = "Hello, <br> If you have recieved this E-Mail your E-Mail setup was successfull!";
                        
                                $mail->send();
                                
                                echo 
                                "
                                <p class='text-success border border-success p-1'><b>Note</b>: <br>Test has been done successfully!<br> You are good to go! <br>Kindly check mail sent to <b>".$tester_mail."</b></p>
                                ";
                            } catch (Exception $th) {
                                echo "<p class='text-danger p-1 border border-danger'>Error : ". $mail->ErrorInfo."</p>";
                            }
                            
                        }else{
                            echo "<p class='text-danger border border-danger p-1'>The Email address has not been set up properly, Delete the current setting and redo the process again!</p>";
                        }
                    }
                }
            }else{
                echo "<p class='text-danger border border-danger'>The Email address has not been set up properly, Delete the current setting and redo the process again!</p>";
            }
        }elseif (isset($_GET['update_discounts'])) {
            $update_discounts = $_GET['update_discounts'];
            $discount_value = $_GET['discount_value'];
            $discount_option = $_GET['discount_option'];

            if ($discount_option == "percentage") {
                $update = "UPDATE `student_data` SET `discount_value` = '0',`discount_percentage` = '".$discount_value."' WHERE `adm_no` = '".$update_discounts."'";
            }else{
                $update = "UPDATE `student_data` SET `discount_value` = '".$discount_value."',`discount_percentage` = '0' WHERE `adm_no` = '".$update_discounts."'";
            }
            $stmt = $conn2->prepare($update);
            if($stmt->execute()){
                echo "<p class='text-success'>Discount updates successfully!</p>";
                $student_data = getStudentData($update_discounts, $conn2);
                $log_text = ((is_array($student_data)) ? ucwords(strtolower($student_data['first_name']." ". $student_data['second_name'])) : "N/A") ." of Reg No (".$update_discounts.") discounts have been updated successfully";
                log_administration($log_text);
            }else{
                echo "<p class='text-danger'>An error occured during update!</p>";
            }
        }
        elseif (isset($_GET['check_email_setup'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $data = $row['valued'];
                    $strlen = strlen(trim($data));
                    if ($strlen > 1) {
                        echo "1";
                    }else{
                        echo "0";
                    }
                }else {
                    echo "0";
                }
            }else {
                echo "0";
            }
        }elseif (isset($_GET['save_leave_cat'])) {
            // echo var_dump($_GET);
            $save_leave_cat = $_GET['save_leave_cat'];
            $leave_title = $_GET['leave_title'];
            $max_days = $_GET['max_days'];
            $leave_status = $_GET['leave_status'];
            $leave_yr = $_GET['leave_yr'];
            $days_accrued = $_GET['days_accrued'];
            $period_accrued = $_GET['period_accrued'];
            $carry_forward = $_GET['carry_forward'];
            $gender_eligible = $_GET['gender_eligible'];
            $insert = "INSERT INTO `leave_categories` (`leave_title`,`gender`,`max_days`,`leave_year_starts`,`days_are_accrued`,`period_accrued`,`max_days_carry_forward`,`active`) VALUES (?,?,?,?,?,?,?,?);";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssssss",$leave_title,$gender_eligible,$max_days,$leave_yr,$days_accrued,$period_accrued,$carry_forward,$leave_status);
            if($stmt->execute()){
                echo "<p class='text-success'>Update has been done successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error has occured during update!</p>";
            }
        }elseif (isset($_GET['get_leave_categories'])) {
            $select = "SELECT * FROM `leave_categories`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                $data_to_display.="<div class='tableme'><table class='table'><tr><th>#</th><th>Name</th><th>Accrued</th><th>Genders</th><th>Max Days</th><th>Actions</th></tr>";
                $index = 1;
                $inactive = "";
                while ($row = $result->fetch_assoc()) {
                    if ($row['active'] == "1") {
                        $data_to_display.="<tr class=''><td>".$index." <small class='text-success'><i class='fas fa-info'></i></small></td><td>".$row['leave_title']."</td><td>".$row['days_are_accrued']."</td><td>".$row['gender']."</td><td>".$row['max_days']." Days</td><td><small><span class='link edit_leaves' id='edit_leave_".$row['id']."'><i class='fas fa-pen-fancy'></i> Edit</span></small></td></tr>";
                    }else{
                        $data_to_display.="<tr class=''><td>".$index." <small class='text-danger'><i class='fas fa-info'></i></small></td><td>".$row['leave_title']."</td><td>".$row['days_are_accrued']."</td><td>".$row['gender']."</td><td>".$row['max_days']." Days</td><td><small><span class='link edit_leaves' id='edit_leave_".$row['id']."'><i class='fas fa-pen-fancy'></i> Edit</span></small></td></tr>";
                    }
                    $index++;
                }
                $data_to_display.=$inactive."</table></div>
                <p>Showing 1 to ".($index-1)." of ".($index-1)." records</p>";
                echo $data_to_display;
            }else{
                echo "<p class='text-danger'>An error occured while displaying Leave Category tables</p>";
            }
        }elseif (isset($_GET['get_leave_data'])) {
            $leave_id = $_GET['get_leave_data'];
            $select = "SELECT * FROM `leave_categories` WHERE `id` = '".$leave_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo json_encode($row);
                }else{
                    echo "{}";
                }
            }else{
                echo "{}";
            }
        }elseif (isset($_GET['update_leaves'])) {
            $update_leaves = $_GET['update_leaves'];
            $leave_title = $_GET['leave_title'];
            $gender_eligible = $_GET['gender_eligible'];
            $max_days = $_GET['max_days'];
            $leave_status = $_GET['leave_status'];
            $leave_year_starts = $_GET['leave_year_starts'];
            $days_accrued = $_GET['days_accrued'];
            $period_accrued = $_GET['period_accrued'];
            $days_carry_forward = $_GET['days_carry_forward'];

            // var_dump($_GET);
            // update the data
            $update = "UPDATE `leave_categories` SET `leave_title` = ?, `gender`= ?, `max_days` = ?,`leave_year_starts` = ?, `days_are_accrued` = ?, `period_accrued` = ?, `max_days_carry_forward` = ?, `active` = ? WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("sssssssss",$leave_title,$gender_eligible,$max_days,$leave_year_starts,$days_accrued,$period_accrued,$days_carry_forward,$leave_status,$update_leaves);
            if($stmt->execute()){
                echo "<p class='text-success'>Updates has been done successfully!</p>";
            }else {
                echo "<p class='text-danger'>An error occured during update!</p>";
            }
        }elseif (isset($_GET['get_working_days'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'working_days'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $days = $row['valued'];
                    $split_days = explode(",",$days);
                    $week_days = ['Mon','Tue','Wed','Thur','Fri','Sat','Sun'];
                    for ($index=0; $index < count($week_days); $index++) { 
                        if (checkPresnt($split_days,$week_days[$index]) == 1) {
                            $data_to_display.="<span id='wd_1' class='wd_btn btn btn-sm btn-success mx-2'>".$week_days[$index]."</span>";
                        }else{
                            $data_to_display.="<span id='wd_1' class='wd_btn btn btn-sm btn-secondary mx-2'>".$week_days[$index]."</span>";
                        }
                    }
                }else{
                    $data_to_display=
                    "   <span id='wd_1' class='wd_btn btn btn-sm btn-secondary mx-2'>Mon</span>
                        <span id='wd_2' class='wd_btn btn btn-sm btn-secondary mx-2'>Tue</span>
                        <span id='wd_3' class='wd_btn btn btn-sm btn-secondary mx-2'>Wed</span>
                        <span id='wd_4' class='wd_btn btn btn-sm btn-secondary mx-2'>Thur</span>
                        <span id='wd_5' class='wd_btn btn btn-sm btn-secondary mx-2'>Fri</span>
                        <span id='wd_6' class='wd_btn btn btn-sm btn-secondary mx-2'>Sat</span>
                        <span id='wd_7' class='wd_btn btn btn-sm btn-secondary mx-2'>Sun</span>
                    ";
                }
            }else{
                $data_to_display=
                "   <span id='wd_1' class='wd_btn btn btn-sm btn-secondary mx-2'>Mon</span>
                    <span id='wd_2' class='wd_btn btn btn-sm btn-secondary mx-2'>Tue</span>
                    <span id='wd_3' class='wd_btn btn btn-sm btn-secondary mx-2'>Wed</span>
                    <span id='wd_4' class='wd_btn btn btn-sm btn-secondary mx-2'>Thur</span>
                    <span id='wd_5' class='wd_btn btn btn-sm btn-secondary mx-2'>Fri</span>
                    <span id='wd_6' class='wd_btn btn btn-sm btn-secondary mx-2'>Sat</span>
                    <span id='wd_7' class='wd_btn btn btn-sm btn-secondary mx-2'>Sun</span>
                ";
            }
            echo $data_to_display;
        }elseif (isset($_GET['working_day_change'])) {
            $working_day_change = $_GET['working_day_change'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'working_days'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result){
                if ($row = $result->fetch_assoc()) {
                    $days = $row['valued'];
                    if (strlen(trim($days)) > 0) {
                        // update the day
                        if (checkPresnt(explode(",",$days),$working_day_change) == 1) {
                            $new_days = removeData($days,$working_day_change);
                        }else{
                            $new_days = $days.",".$working_day_change;
                        }
                        // echo $new_days;
                        $update = "UPDATE `settings` SET `valued` = '".$new_days."' WHERE `sett` = 'working_days'";
                        $stmt = $conn2->prepare($update);
                        if($stmt->execute()){
                            echo "<p class='text-success'>Update done successfully!</p>";
                        }else{
                            echo "<p class='text-danger'>An error occurred during update!</p>";
                        }
                    }else{
                        $new_days = $working_day_change;
                        $update = "UPDATE `settings` SET  `valued` = '".$new_days."' WHERE `sett` = 'working_days'";
                        $stmt = $conn2->prepare($update);
                        if($stmt->execute()){
                            echo "<p class='text-success'>Update done successfully!</p>";
                        }else{
                            echo "<p class='text-danger'>An error occurred during update!</p>";
                        }
                    }
                }else{
                    $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?)";
                    $stmt = $conn2->prepare($insert);
                    $value1 = "working_days";
                    $stmt->bind_param("ss",$value1,$working_day_change);
                    if($stmt->execute()){
                        echo "<p class='text-success'>Update done successfully!</p>";
                    }else{
                        echo "<p class='text-danger'>An error occurred during update!</p>";
                    }
                }
            }else{
                $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?)";
                $stmt = $conn2->prepare($insert);
                $value1 = "working_days";
                $stmt->bind_param("ss",$value1,$working_day_change);
                if($stmt->execute()){
                    echo "<p class='text-success'>Update done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>An error occurred during update!</p>";
                }
            }
        }elseif (isset($_GET['get_leaves_cat'])) {
            $select = "SELECT * FROM `leave_categories` WHERE `active` = '1' AND (`gender` = '".($_SESSION['gender'] == "M"?"Male":"Female")."' OR `gender` = 'All');";
            // echo $select;
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<select name='".$_GET['select_ids']."' id='".$_GET['select_ids']."' class='form-control w-100'><option value='' hidden>Select Category</option>";
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="<option value='".$row['id']."'>".$row['leave_title']."</option>";
                }
                $data_to_display.="</select>";
                echo $data_to_display;
            }
        }elseif(isset($_GET['save_revenue_category'])){
            $revenue_sub_category = $_GET['revenue_sub_category'];
            $revenue_notes = $_GET['revenue_notes'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    // add to the existing array
                    if(isJson_report($valued)){
                        $new_array = json_decode($valued);

                        // GET THE REVENUE_NOTE
                        $present_note = false;
                        $category = "N/A";
                        for ($index = 0; $index < count($new_array); $index++) {
                            if(isset($new_array[$index]->revenue_notes)){
                                if($new_array[$index]->revenue_notes == $revenue_notes){
                                    $present_note = true;
                                    $category = $new_array[$index]->category_name;
                                    break;
                                }
                            }
                        }

                        // check for the present note
                        if(!$present_note){

                            $present = false;
                            for ($ind=0; $ind < count($new_array); $ind++) {
                                $category_name = $new_array[$ind]->category_name;
                                if(strtolower($category_name) == strtolower($_GET['category_name'])){
                                    $present = true;
                                    break;
                                }
                            }
    
                            $id = 0;
                            for ($ind=0; $ind < count($new_array); $ind++) {
                                $category_id = $new_array[$ind]->category_id;
                                if($category_id > $id){
                                    $id = $category_id;
                                }
                            }
    
                            // id
                            $id+=1;
    
                            // is present
                            if(!$present){
                                // category name
                                $category_details = [];
                                $category_details['category_id'] = $id;
                                $category_details['category_name'] = $_GET['category_name'];
                                $category_details['revenue_notes'] = $_GET['revenue_notes'];
                                $category_details['sub_categories'] = isJson_report($revenue_sub_category) ? json_decode($revenue_sub_category) : [];
                                array_push($new_array,$category_details);
    
                                // update 
                                $new_array = json_encode($new_array);
                                $update = "UPDATE `settings` SET `valued` = '$new_array' WHERE `sett` = 'revenue_categories'";
                                $stmt = $conn2->prepare($update);
                                $stmt->execute();
                                echo "<p class='text-success'>\"".$_GET['category_name']."\" has been added successfully!</p>";
                            }else{
                                echo "<p class='text-danger'>\"".$_GET['category_name']."\" is already used! Use another name.</p>";
                            }
                        }else{
                            echo "<p class='text-danger'>Revenue note is already used by \"".ucwords(strtolower($category))."\".</p>";
                        }
                    }else{
                        // update
                        // category name
                        $category_details = [];
                        $category_details['category_id'] = $id;
                        $category_details['category_name'] = $_GET['category_name'];
                        $category_details['revenue_notes'] = $_GET['revenue_notes'];
                        $category_details['sub_categories'] = isJson_report($revenue_sub_category) ? json_decode($revenue_sub_category) : [];
                        $new_array = json_encode([$category_details]);
                        $update = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('revenue_categories','".$new_array."')";
                        $stmt = $conn2->prepare($update);
                        $stmt->execute();
                        echo "<p class='text-success'>\"".$_GET['category_name']."\" has been added successfully!</p>";
                    }
                }else{
                    // update
                    // category name
                    $category_details = [];
                    $category_details['category_id'] = $id;
                    $category_details['category_name'] = $_GET['category_name'];
                    $category_details['revenue_notes'] = $_GET['revenue_notes'];
                    $category_details['sub_categories'] = isJson_report($revenue_sub_category) ? json_decode($revenue_sub_category) : [];
                    $new_array = json_encode([$category_details]);
                    $update = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('revenue_categories','".$new_array."')";
                    $stmt = $conn2->prepare($update);
                    $stmt->execute();
                    echo "<p class='text-success'>\"".$_GET['category_name']."\" has been added successfully!</p>";
                }
            }else{
                // update 
                // category name
                $category_details = [];
                $category_details['category_id'] = $id;
                $category_details['category_name'] = $_GET['category_name'];
                $category_details['revenue_notes'] = $_GET['revenue_notes'];
                $category_details['sub_categories'] = isJson_report($revenue_sub_category) ? json_decode($revenue_sub_category) : [];
                $new_array = json_encode([$category_details]);
                $update = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('revenue_categories','".$new_array."')";
                $stmt = $conn2->prepare($update);
                $stmt->execute();
                echo "<p class='text-success'>\"".$_GET['category_name']."\" has been added successfully!</p>";
            }
            
            // log text
            $log_text = "Revenue category \"".$_GET['category_name']."\" has been added successfully!";
            log_administration($log_text);
        } 
        elseif(isset($_GET['show_revenue_category'])){
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $present = 0;
            $expense_cats = "";
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Add Revenue categories, they will appear here.</p>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $exp_cat = trim($row['valued']);
                    $expense_cats = $exp_cat;
                    if(isJson_report($exp_cat)){
                        $data_to_display = "<table class='table'><tr><th>No.</th><th>Revenue Category.</th><th>Revenue Sub-Category.</th><th>Actions.</th></tr>";
                        // get if the name is used before
                        $exp_cats = json_decode($exp_cat);
                        for ($index=0; $index < count($exp_cats); $index++) {
                            $data_to_display.="<tr><td>".($index+1).".<input type='hidden' id='revenue_note_".$exp_cats[$index]->category_id."' value='".(isset($exp_cats[$index]->revenue_notes) ? $exp_cats[$index]->revenue_notes : "0")."'> <input type='hidden' id=expense_sub_category_".$exp_cats[$index]->category_id." value='".((isset($exp_cats[$index]->sub_categories) ? json_encode($exp_cats[$index]->sub_categories) : "[]"))."'> </td><td id='revenue_name_".$exp_cats[$index]->category_id."'>".$exp_cats[$index]->category_name."</td><td>".count($exp_cats[$index]->sub_categories)." Sub Categories</td><td><p><span class='mx-1 link edit_revenue_cat' id='edit_revenue_cat_".$exp_cats[$index]->category_id."'><i class='fas fa-pen-fancy'></i></span> <span class='mx-1 link delete_revenue_cat' id = 'delete_revenue_cat_".$exp_cats[$index]->category_id."'><i class='fas fa-trash'></i></span></p></td></tr>";
                        }
                        $data_to_display .= "</table>";
                    }
                }
            }
            echo $data_to_display;
        }elseif(isset($_GET['get_revenue_sub_category'])){
            $get_revenue_sub_category = $_GET['get_revenue_sub_category'];
            $revenue_value = $_GET['revenue_value'];
            $element_id = $_GET['element_id'];
            $sub_revenue_id = $_GET['sub_revenue_id'];

            // select
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $expense_sub_category = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    if(isJson_report($valued)){
                        $valued = json_decode($valued);
                        foreach ($valued as $key => $value) {
                            if($value->category_id == $revenue_value){
                                $expense_sub_category = isset($value->sub_categories) ? $value->sub_categories : [];
                            }
                        }
                    }
                }
            }

            // display the options
            $data_to_display = "<select class='form-control w-100' id='".$element_id."'><option hidden value=''>Select an Option!</option>";
            foreach($expense_sub_category as $value){
                $selected = $value->id == $sub_revenue_id ? "selected" : "";
                $data_to_display .= "<option value='".$value->id."' ".$selected." >".$value->name."</option>";
            }
            $data_to_display .= "</select>";
            echo $data_to_display;
        }elseif(isset($_GET['show_expense_cat'])){
            $select = "SELECT * FROM `expense_category`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Add expense categories, they will appear here.</p>";
            if($result){
                $data_to_display = "<table class='table'><tr><th>No.</th><th>Expense Category</th><th>Maximum Budget</th><th>Used Budget (%)</th><th>Expense Sub-Categories</th><th>Actions.</th></tr>";
                $index = 1;
                while($row = $result->fetch_assoc()){
                    $selected = "SELECT SUM(`exp_amount`) AS 'expense_amount' FROM `expenses` WHERE `exp_category` = '".$row['expense_id']."' AND `expense_date` BETWEEN '".$row['start_date']."' AND '".$row['end_date']."'";
                    $statement = $conn2->prepare($selected);
                    $statement->execute();
                    $res = $statement->get_result();
                    $used_amount = 0;
                    if($res){
                        if($inner_row = $res->fetch_assoc()){
                            $used_amount = $inner_row['expense_amount']*1;
                        }
                    }

                    // start date and end date
                    $row['start_date'] = date("Y-m-d",strtotime($row['start_date']));
                    $row['end_date'] = date("Y-m-d",strtotime($row['end_date']));

                    // get the used budget from the expense record table
                    $percentage = ($used_amount > 0 && ($row['expense_budget']*1) > 0) ? round((($used_amount/$row['expense_budget']) * 100),1)."%" : "0%";
                    $row['expense_sub_categories'] = isJson_report($row['expense_sub_categories']) ? json_decode($row['expense_sub_categories']) : [];
                    $subcategories = count($row['expense_sub_categories']);
                    $row_value = str_replace("'", "`", json_encode($row));
                    $data_to_display.="<tr><td>".($index).". </td><td><input type='hidden' value='".$row_value."' id='exp_name_".$row['expense_id']."'>".$row['expense_name']."</td><td>Kes ".(number_format($row['expense_budget']))."</td><td>Kes ".number_format($used_amount)." (".$percentage.")</td><td>".$subcategories." subcategories</td><td><p><span class='mx-1 link edit_exp_cat' id='edit_exp_cat_".$row['expense_id']."'><i class='fas fa-pen-fancy'></i></span> <span class='mx-1 link delete_exp_cat' id = 'delete_exp_cat_".$row['expense_id']."'><i class='fas fa-trash'></i></span></p></td></tr>";
                    $index++;
                }
                $data_to_display .= "</table>";
            }
            echo $data_to_display;
        }
        elseif(isset($_GET['change_expense_categories'])){
            $new_exp_name = ($_GET['new_exp_name']);
            $exp_indexes = $_GET['exp_indexes'];
            $budget_start_time_edit = date("Ymd",strtotime($_GET['budget_start_time_edit']));
            $budget_end_date_edit = date("Ymd",strtotime($_GET['budget_end_date_edit']));
            $expense_category_budget_edit = $_GET['expense_category_budget_edit'];
            $change_date = date("YmdHis");

            // update
            $update = "UPDATE `expense_category` SET `expense_name` = ?, `expense_budget` = ?, `start_date` = ?, `end_date` = ?, `updated_at` = ? WHERE `expense_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ssssss",$new_exp_name,$expense_category_budget_edit,$budget_start_time_edit,$budget_end_date_edit,$change_date,$exp_indexes);
            $stmt->execute();
            
            // display success message
            echo "<p class='text-success border border-success my-2 p-2'>Expense has been updated successfully!</p>";
        }
        elseif(isset($_GET['change_revenue_categories'])){
            $change_revenue_categories = ($_GET['change_revenue_categories']);
            $new_revenue_name = $_GET['new_revenue_name'];
            $revenue_indexes = $_GET['revenue_indexes'];
            $revenue_sub_categories = $_GET['revenue_sub_categories'];
            $revenue_notes = $_GET['revenue_notes'];
            
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    if(isJson_report($row['valued'])){
                        // valued data
                        $valued = json_decode($valued);
                        
                        // present note
                        $present_note = false;
                        $note_used = "N.A";
                        for ($index=0; $index < count($valued); $index++) {
                            if(isset($valued[$index]->revenue_notes)){
                                if($valued[$index]->category_id != $revenue_indexes && $valued[$index]->revenue_notes == $revenue_notes){
                                    $present_note = true;
                                    $note_used = $valued[$index]->category_name;
                                }
                            }
                        }

                        if(!$present_note){
                        
                            $present = false;
                            for ($index=0; $index < count($valued); $index++) {
                                if($valued[$index]->category_id != $revenue_indexes && $valued[$index]->category_name == $new_revenue_name){
                                    $present = true;
                                }
                            }
    
                            // if the new revenue category name is reused twice, refuse it
                            if(!$present){
                                // proceed and add update in the database
                                for($index = 0; $index < count($valued); $index++){
                                    if($valued[$index]->category_id == $revenue_indexes){
                                        $valued[$index]->category_name = $new_revenue_name;
                                        $valued[$index]->revenue_notes = $revenue_notes;
                                        $valued[$index]->sub_categories = isJson_report($revenue_sub_categories) ? json_decode($revenue_sub_categories) : [];
                                        break;
                                    }
                                }
    
                                // update the database
                                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'revenue_categories'";
                                $stmt = $conn2->prepare($update);
                                $valued = json_encode($valued);
                                $stmt->bind_param("s",$valued);
                                $stmt->execute();
    
                                // log text
                                $log_text = "Revenue category \"".ucwords(strtolower($new_revenue_name))."\" updated successfully!";
                                log_administration($log_text);
                                echo "<p class='text-success'>".ucwords(strtolower($new_revenue_name))." has been successfully updated!</p>";
                            }else{
                                // otherwise refuse
                                echo "<p class='text-danger'>".ucwords(strtolower($new_revenue_name))." is already used! Use another name!</p>";
                            }
                        }else{
                            echo "<p class='text-danger' id='income_note_error'>Data not saved <br>The selected note is already used by \"".ucwords(strtolower($note_used))."\". <br> Select another one to save!</p>";
                        }
                    }else{
                        // otherwise refuse
                        echo "<p class='text-danger'>An error has occured!</p>";
                    }
                }
            }
            // echo "<p class='text-success border border-success my-2 p-2'>Expense has been updated successfully!</p>";
        }elseif(isset($_GET['get_expense_cats'])){
            $select = "SELECT * FROM `expense_category`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Set expense categories in the settings before proceeding!.</p>";
            if($result){
                $data_to_display = "<select class='form-control w-100' name='exp_cat' id='exp_cat'><option value='' id='main_sele' hidden >Select..</option>";
                $expenses = [];
                while($row = $result->fetch_assoc()){
                    // get how much is left in the budget
                    // if its less that zero you cant user it.
                    $selected = "SELECT SUM(`exp_amount`) AS 'expense_amount' FROM `expenses` WHERE `exp_category` = '".$row['expense_id']."' AND `expense_date` BETWEEN '".$row['start_date']."' AND '".$row['end_date']."'";
                    $statement = $conn2->prepare($selected);
                    $statement->execute();
                    $res = $statement->get_result();
                    $used_amount = 0;
                    if($res){
                        if($inner_row = $res->fetch_assoc()){
                            $used_amount = $inner_row['expense_amount']*1;
                        }
                    }
                    // get the used budget from the expense record table
                    $percentage = ($used_amount > 0 && ($row['expense_budget']*1) > 0) ? round((($used_amount/$row['expense_budget']) * 100),2) : 0;
                    $percentage = 100 - $percentage;
                    if($percentage != 0){
                        $row['running_balance'] = $row['expense_budget'] - $used_amount;
                        array_push($expenses,$row);
                        $data_to_display .= "<option value='".$row['expense_id']."'>".ucwords(strtolower($row['expense_name']))." - | Kes ".number_format($row['expense_budget'] - $used_amount)." | (".$percentage."%)</option>";
                    }
                }
                $data_to_display.="</select><input type='hidden' value=\"".htmlspecialchars(json_encode($expenses))."\" id='expense_categories_value'>";
            }
            echo $data_to_display;
        }elseif(isset($_GET['delete_expense_category'])){
            $index_id = $_GET['index_id'];
            $delete = "DELETE FROM `expense_category` WHERE `expense_id` = '".$index_id."'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    echo "<p class='text-success border border-success my-2 p-2'>Expense has been removed successfully!.</p>";
                }
            }
        }elseif (isset($_GET['get_leave_balance'])) {
            include("../../connections/conn1.php");
            // get if the users doe is set
            $staff_information = getMyStaffIn4($conn,$_SESSION['userids']);
            $staff_information = count($staff_information) > 0 ? $staff_information : [];
            if (count($staff_information) > 0) {
                $doe = $staff_information['doe'];
                if (strlen($doe) > 0 && $doe != null) {
                    // first get the days the user is obligated to
                    $leave_id = $_GET['get_leave_balance'];
                    // $days_entitled = round(getLeave_Balance($conn,$conn2,$leave_id));
                    $our_days = getLeaveBalance_2($conn,$conn2,$leave_id);
                    echo $our_days." Days";
                    echo "<span class='hide' id='days_entittled'>".$our_days."</span><br>";
                }else{
                    echo "<p class='border border-danger text-danger p-2'>Your date of employment has not been set by your administrator.<br>Kindly contact your administrator first before you proceed.</p>";
                }
            }else{
                echo "<p class='border border-danger text-danger  p-2'>Invalid User! Contact your administrator.</p>";
            }

        }elseif (isset($_GET['count_days'])) {
            $from_date = $_GET['from_date'];
            $to_date = $_GET['to_date'];
            // difference in days
            if ($to_date >= $from_date) {
                $date1=date_create($from_date);
                $date2=date_create($to_date);
                $diff=date_diff($date1,$date2);
                $date_differences = $diff->format("%a");

                // GET THE WORKING DAYS OF THE WEEK
                $select = "SELECT * FROM `settings` WHERE `sett` = 'working_days'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result  = $stmt->get_result();
                $working_days = [];
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $working_days = explode(",",trim($row['valued']));
                    }
                }
                
                // loop through the dates
                // var_dump($working_days);
                $days_that_are_holiday_or_weekend = 0;
                for ($index=0; $index <= $date_differences; $index++) {
                    $is_date_or_holiday = checkDate_Holiday($from_date,$working_days);
                    if ($is_date_or_holiday > 0) {
                        $days_that_are_holiday_or_weekend+=$is_date_or_holiday;
                        // echo "Today is holiday ".date("D dS M Y",strtotime($from_date))." => ";
                    }
                    // echo $from_date."<br>";
                    // add one day to from date
                    $date = date_create($from_date);
                    date_add($date , date_interval_create_from_date_string("1 day"));
                    $from_date = date_format($date,"Y-m-d");
                }
                $date_differences-=$days_that_are_holiday_or_weekend;
                echo "<span class='hide' id='date_differences_leave_holder'>".$date_differences."</span>";
            }else{
                echo 0;
            }
        }elseif (isset($_GET['apply_leaves'])) {
            // var_dump($_GET);
            $leave_category_applied = $_GET['leave_category_applied'];
            $from_date = $_GET['from_date'];
            $to_date = $_GET['to_date'];
            $leave_duration = $_GET['leave_duration'];
            $leave_description = $_GET['leave_description'];

            $insert = "INSERT INTO `apply_leave` (`leave_category`,`employee_id`,`days_duration`,`from`,`to`,`date_applied`,`leave_description`,`status`) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $today = date("Y-m-d");
            $status = 0;
            $stmt->bind_param("ssssssss",$leave_category_applied,$_SESSION['userids'],$leave_duration,$from_date,$to_date,$today,$leave_description,$status);
            if($stmt->execute()){
                echo "<p class='text-success'>Leave applied successfully!<br> Kindly wait for confirmation from the administrator or Human Resource</p>";
            }else{
                echo "<p class='text-danger'>An error occured during application!</p>";
            }
        }elseif (isset($_GET['get_all_leaves'])) {
            include("../../connections/conn1.php");
            $select = "SELECT * FROM `apply_leave` ORDER BY `id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['leave_category'] = ucwords(strtolower(getLeaveDetails($conn2,$row['leave_category'])['leave_title']));
                    $row['employee_id'] = ucwords(strtolower(getMyStaffIn4($conn,$row['employee_id'])['fullname']));
                    $row['days_duration'] = strval($row['days_duration']);
                    $row['from'] = date("D dS M Y",strtotime($row['from']));
                    $row['to'] = date("D dS M Y",strtotime($row['to']));
                    $row['status'] = strval($row['status']);
                    $row['id'] = strval($row['id']);
                    $row['date_applied'] = date("D dS M Y",strtotime($row['date_applied']));
                    $row['date_applied'] = date("D dS M Y",strtotime($row['date_applied']));
                    array_push($data_to_display,$row);
                }
            }
            // return the encoded data
            echo json_encode($data_to_display);
        }elseif (isset($_GET['accept_leaves'])) {
            include("../../connections/conn1.php");
            $leaves_id = $_GET['leaves_id'];
            // echo $leaves_id;
            // echo json_encode(getLeaveDetails($conn2,$leaves_id));
            $select = "SELECT * FROM `apply_leave` WHERE `id` = '".$leaves_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $reciever_id = 0;
            $leave_category = 0;
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $reciever_id = $row['employee_id'];
                    $leave_category = $row['leave_category'];
                }
            }
            $user_auth = getMyStaffIn4($conn,$reciever_id)['auth'];
            $update = "UPDATE `apply_leave` SET `status` = '1' WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$leaves_id);
            if($stmt->execute()){
                echo "<p class='text-success' id='accept_leaf_badge'>Accepted successfully!</p>";
                $notice_stat = 0;
                $leave_details = count(getLeaveDetails($conn2,$leave_category)) > 0 ? getLeaveDetails($conn2,$leave_category):['leave_title' => "Null"];
                $messages = "Your ".ucwords(strtolower($leave_details['leave_title']))." has been successfully approved!";
                insertNotice($conn2,"Leave Approved",$messages,$notice_stat,$reciever_id,$user_auth,$_SESSION['userids']);
            }else{
                echo "<p class='text-danger'>An error has occurred!</p>";
            }
        }elseif (isset($_GET['decline_leaves'])) {
            include("../../connections/conn1.php");
            $leaves_id = $_GET['leaves_id'];
            // echo $leaves_id;
            // echo json_encode(getLeaveDetails($conn2,$leaves_id));
            $select = "SELECT * FROM `apply_leave` WHERE `id` = '".$leaves_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $reciever_id = 0;
            $leave_category = 0;
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $reciever_id = $row['employee_id'];
                    $leave_category = $row['leave_category'];
                }
            }
            $user_auth = getMyStaffIn4($conn,$reciever_id)['auth'];
            $update = "UPDATE `apply_leave` SET `status` = '2' WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            $leaves_id = $_GET['leaves_id'];
            $stmt->bind_param("s",$leaves_id);
            if($stmt->execute()){
                echo "<p class='text-success' id='reject_leaf_badge'>Changes have been done successfully!</p>";
                $notice_stat = 0;
                $leave_details = count(getLeaveDetails($conn2,$leave_category)) > 0 ? getLeaveDetails($conn2,$leave_category):['leave_title' => "Null"];
                $messages = "Your ".ucwords(strtolower($leave_details['leave_title']))." has been declined.<br>Kindly contact your administrator for more information.!";
                insertNotice($conn2,"Leave Declined",$messages,$notice_stat,$reciever_id,$user_auth,$_SESSION['userids']);
            }else{
                echo "<p class='text-danger'>An error has occurred!</p>";
            }
        }elseif (isset($_GET['my_leaves_application'])){
            $select = "SELECT * FROM `apply_leave` WHERE `employee_id` = '".$_SESSION['userids']."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $win_display = "";
            if ($result) {
                $counter = 1;
                $win_display.="<table class='table'><tr><th>#</th><th>Leave Title</th><th>Date Applied</th><th>Dates</th><th>Days Duration</th><th>Status</th><th>Action</th></tr>";
                while($row = $result->fetch_assoc()){
                    $status = $row['status'];
                    if ($status == "0") {
                        $status = "<p class='text-secondary'>Pending</p>";
                    }elseif($status == "1"){
                        $status = "<p class='text-success'>Approved</p>";
                    }elseif($status == "2"){
                        $status = "<p class='text-danger'>Declined</p>";
                    }
                    $win_display.="<tr><td>".$counter."</td><td>".ucwords(strtolower(getLeaveDetails($conn2,$row['leave_category'])['leave_title']))."</td><td>".date("D dS M Y",strtotime($row['date_applied']))."</td><td>".date("D dS M Y",strtotime($row['from']))." to ".date("D dS M Y",strtotime($row['to']))."</td><td>".$row['days_duration']." Days</td><td>".$status."</td><td><p class='link view_emp_leaves' id='view_emp_leaves".$row['id']."'><i class='fas fa-eye'></i> View</p></td></tr>";
                    $counter++;
                }
                $win_display.="</table>";
                echo $win_display;
            }
        }elseif (isset($_GET['get_my_leave_data'])) {
            $select = "SELECT * FROM `apply_leave` WHERE `id` = '".$_GET['get_my_leave_data']."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $row['date_applied'] = date("D dS M Y",strtotime($row['date_applied']));
                    $row['from'] = date("D dS M Y",strtotime($row['from']));
                    $row['to'] = date("D dS M Y",strtotime($row['to']));
                    $row['leave_category'] = ucwords(strtolower(getLeaveDetails($conn2,$row['leave_category'])['leave_title']));
                    echo json_encode($row);
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
        }elseif (isset($_GET['job_number_checker'])) {
            include("../../connections/conn1.php");
            $job_number_checker = $_GET['job_number_checker'];
            $employee_id = $_GET['employee_id'];
            $select = "SELECT * FROM `user_tbl` WHERE `job_number` = '".$job_number_checker."' AND NOT `user_id` = '".$employee_id."' AND `school_code` = '".$_SESSION['schcode']."'";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $data_to_display = "";
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $name = $row['fullname'];
                    $data_to_display = "<p class='text-danger' id='err_job_number_back'>This job number is used by <b>".ucwords(strtolower($name))."<b></p>";
                }
            }
            echo $data_to_display;
        }elseif (isset($_GET['get_class_exams_report'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $arr_class = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $class_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    for ($index=0; $index < count($class_list); $index++) { 
                        array_push($arr_class,$class_list[$index]->classes);
                    }
                    // $arr_class = explode(",",$class_list);
                }
            }
            // display the classes
            if (count($arr_class) > 0) {
                $data_to_display = "<select class='form-control' id='".$_GET['its_id']."'><option hidden value='' >Select Class</option>";
                for ($index=0; $index < count($arr_class); $index++) { 
                    $data_to_display.="<option value='".$arr_class[$index]."' >".myClassName($arr_class[$index])."</option>";
                }
                $data_to_display.="</select>";
                echo $data_to_display;
            }else {
                echo "<p class='text-danger border border-danger p-2'>No classes to display at the moment, Contact your administrator to set them up!</p>";
            }
        }elseif(isset($_GET['get_course_list_edit'])){
            $course_level = $_GET['course_level'];
            
            // get the course levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            // course levels
            $course_levels = [];
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // get the selected course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }
            
            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $select = "<select class='form-control w-100' id='course_chosen_edit'><option hidden value=''>Select Courses!</option>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // check if the course is present in the level selected
                    for($index = 0; $index < count($valued); $index++){
                        // loop through course levels
                        $course_levels = isJson_report($valued[$index]->course_levels) ? json_decode($valued[$index]->course_levels) : [];
                        
                        // add course flag after looping through the course levele the course is offered
                        $proceed = false;
                        if(isset($valued[$index]->course_level) && $valued[$index]->course_level == $course_id){
                            $proceed = true;
                        }

                        // proceed!
                        if($proceed){
                            $select .= "<option value='".$valued[$index]->id."'>".$valued[$index]->course_name."</option>";
                        }
                    }
                }
            }
            $select .= "</select>";

            // select statement
            echo $select;
        }elseif(isset($_GET['get_course_list_fees_struct'])){
            // get the fees structure for class
            $get_course_list = $_GET['get_course_list_fees_struct'];
            $course_level = $_GET['course_level'];

            // get fees structure
            // get the course levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            // course levels
            $course_levels = [];
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }


            // get the selected course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }
            
            
            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $select = "<select class='form-control' id='search_fees_course_list'><option hidden value=''>Select Courses!</option>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // check if the course is present in the level selected
                    for($index = 0; $index < count($valued); $index++){
                        // loop through course levels
                        $course_levels = isJson_report($valued[$index]->course_levels) ? json_decode($valued[$index]->course_levels) : [];
                        
                        // add course flag after looping through the course levele the course is offered
                        $proceed = false;
                        // for ($ind=0; $ind < count($course_levels); $ind++) { 
                        //     if($course_levels[$ind] == $course_id){
                        //         $proceed = true;
                        //         break;
                        //     }
                        // }
                        if(isset($valued[$index]->course_level)){
                            if($valued[$index]->course_level == $course_id){
                                $proceed = true;
                            }
                        }

                        // proceed!
                        if($proceed){
                            $select .= "<option value='".$valued[$index]->id."'>".$valued[$index]->course_name."</option>";
                        }
                    }
                }
            }
            // if the course level is unassigned you add this option
            if($course_level == "-3"){
                $select .= "<option value='-3'>All Un-assigned Voteheads</option>";
            }
            $select .= "</select>";
            echo $select;
        }elseif(isset($_GET['get_course_list'])){
            $course_level = $_GET['course_level'];
            
            // get the course levels
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            // course levels
            $course_levels = [];
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // get the selected course id
            $course_id = null;
            for($index = 0; $index < count($course_levels); $index++){
                if($course_levels[$index]->classes == $course_level){
                    $course_id = $course_levels[$index]->id;
                    break;
                }
            }
            
            // get the courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $select = "<select class='form-control' id='course_chosen'><option hidden value=''>Select Courses!</option>";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // check if the course is present in the level selected
                    for($index = 0; $index < count($valued); $index++){
                        // loop through course levels
                        $course_levels = isJson_report($valued[$index]->course_levels) ? json_decode($valued[$index]->course_levels) : [];
                        
                        // add course flag after looping through the course levele the course is offered
                        $proceed = false;
                        // for ($ind=0; $ind < count($course_levels); $ind++) { 
                        //     if($course_levels[$ind] == $course_id){
                        //         $proceed = true;
                        //         break;
                        //     }
                        // }
                        if(isset($valued[$index]->course_level) && $valued[$index]->course_level == $course_id){
                            $proceed = true;
                        }

                        // proceed!
                        if($proceed){
                            $select .= "<option value='".$valued[$index]->id."'>".$valued[$index]->course_name."</option>";
                        }
                    }
                }
            }
            $select .= "</select>";

            // select statement
            echo $select;
        }elseif (isset($_GET['get_terms_date'])) {
            $select = "SELECT * FROM `academic_calendar`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            // get the current term
            $curr_term = getTermV3($conn2);
            // $curr_term = "TERM_1";
            $counter = 0;
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $counter ++;
                    $data_to_display .= "<div class='d-flex flex-row justify-content-between p-2'><label for='term_".$counter."_check'>Term ".$counter."</label><input type='checkbox' value='".$row['term']."' id='term_".$counter."_check' class='check_boxes_ex_report'></div>";
                    if ($curr_term == $row['term']) {
                        break;
                    }
                }
            }
            if ($counter > 0) {
                echo $data_to_display;
            }else {
                echo "<p class='text-danger border border-danger p-2'>Terms have not been set up appropriately, Contact your administrator to set them up for you!</p>";
            }
        }elseif (isset($_GET['get_exams_done'])) {
            $terms_selected = $_GET['terms_selected'];
            $class_selected = $_GET['class_selected'];
            // get the exams done in the period of the terms selected
            $exams_list = [];
            $terms = json_decode($terms_selected);
            for ($index=0; $index < count($terms); $index++) {
                $select = "SELECT * FROM `academic_calendar` WHERE `term` = '".$terms[$index]."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $term_start = "";
                $term_end = "";
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $term_start = $row['start_time'];
                        $term_end = $row['end_time'];
                    }
                }

                // get the exams done between those two dates
                $select = "SELECT * FROM `exams_tbl` WHERE (`start_date` BETWEEN '".$term_start."' AND '".$term_end."') AND `class_sitting` LIKE '%".$class_selected."%'";
                $stmt2 = $conn2->prepare($select);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ($result2) {
                    while ($row = $result2->fetch_assoc()) {
                        array_push($exams_list, $row);
                    }
                }
            }
            // display the exams found in form of checkboxes value is their id and display their name
            $data_to_display = "";
            for ($index=0; $index < count($exams_list); $index++) { 
                $data_to_display .= "<div class='d-flex flex-row justify-content-between p-2'><label for='exams_name_".$exams_list[$index]['exams_id']."'>".($index+1).". ".ucwords(strtolower($exams_list[$index]['exams_name']))."</label><input type='checkbox' value='".$exams_list[$index]['exams_id']."' id='exams_name_".$exams_list[$index]['exams_id']."' class='check_boxes_exams_name'></div>";
            }
            if (count($exams_list) > 0) {
                echo $data_to_display;
            }else {
                echo "<p class='text-danger border border-danger p-2 my-1'>No exams were done in the terms you selected</p>";
            }
        }elseif (isset($_GET['get_student_attendance'])) {
            $student_admno = $_GET['student_admno'];
            $selected_month = $_GET['selected_month']."-01";
            
            $start_date = date("Y-m-d",strtotime($selected_month));
            $date=date_create($start_date);
            date_add($date,date_interval_create_from_date_string("1 month"));
            $end_date = date_format($date,"Y-m-d");
            
            // get difference between start and end date in days
            $date1=date_create($start_date);
            $date2=date_create($end_date);
            $diff=date_diff($date1,$date2);
            $days = $diff->format("%R%a");

            // student details
            $student_details = getStudentData($student_admno, $conn2);
            $student_class = count($student_details) > 0 ? $student_details['stud_class']:"-1";

            $full_student_name = count($student_details) > 0 ? $student_details['first_name']." ".$student_details['second_name']:"Null";
            $data_to_display = "<h4 class'text-center my-2'>".ucwords(strtolower($full_student_name))."`s Attendance for ".date("M Y",strtotime($selected_month))."</h4><div class='table_holders w-100 p-1'><table class='table'>
            <tr>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thur</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Sun</th>
            </tr>";
            $roll_call_taken = 0;
            $roll_call_taken_present = 0;

            // store the date details
            $begin_date = $start_date;
            $data = [];
            for ($index=0; $index < $days; $index++) {
                $begin_date;
                $select = "SELECT * FROM `attendancetable` WHERE `admission_no` = '".$student_admno."' AND `date` = '".$begin_date."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $present = 0;
                $class_in = classNameAdms($student_details['stud_class']);
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $present = 1;
                        $class_in = classNameAdms($row['class']);
                    }
                }

                $select = "SELECT * FROM `attendancetable` WHERE `date` = '".$begin_date."' AND `class` = '".$student_class."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $done = 0;
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $done = 1;
                        $roll_call_taken++;
                    }
                }
                if ($done == 1) {
                    $roll_call_taken_present++;
                }
                // check if they are present in the following days
                $date_detail = array("date"=>$begin_date, "present" => "$present", "done" => $done, "class" => $class_in);
                array_push($data,$date_detail);

                // add one day to the date
                $date=date_create($begin_date);
                date_add($date,date_interval_create_from_date_string("1 day"));
                $begin_date = date_format($date,"Y-m-d");
            }
            // echo json_encode($data);

            $number_weeks = $days%7 > 1 ? round($days/7) + 1 : round($days/7);
            // echo $number_weeks;
            $week_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $counter = 0;
            $proceed = false;

            $present_tot = 0;
            $absent_tot = 0;

            for ($index=1; $index <= $number_weeks; $index++) {
                // echo $start_date . "<br>";

                $data_to_display .="<tr>";
                for ($index1=0; $index1 < 7; $index1++) {
                    $day = $week_days[$index1];
                    $start_day = date("D",strtotime($data[0]['date']));

                    // proceed only if the day that starts is the same as the current day if wen it should be web on the day that is starting
                    $proceed = $proceed == false ? (($day == $start_day) ? true : false) : true;
                    // echo $proceed . " $day $start_day <br>";

                    if ($counter < $days && $proceed) {
                        $done = $data[$counter]['done'] == "1" ? "<span class='text-success'><i class='fas fa-check'></i></span>" : "<span class='text-danger'><i class='fas fa-times'></i></span>";
                        $class_selected = ($data[$counter]['done'] == "1" && $data[$counter]['present'] == "1") ? "" : "<span class='text-secondary'>(".$data[$counter]['class'].")</span>";
                        if($data[$counter]['present'] == "1"){
                            $data_to_display.="<td>
                                <div class='container my-0 p-1 d-flex flex-column'>
                                    <div class='container p-0 text-primary'>
                                        <small>".date("D dS M y",strtotime($start_date))." ".$done."</small>
                                    </div>
                                    <hr class='my-1'>
                                    <div class='container p-1 text-dark'>
                                        <p class='text-success'>Present <i class='fas fa-user-check'></i> $class_selected</p>
                                    </div>
                                </div>
                            </td>";
                        $present_tot++;
                        }else{
                            $data_to_display.="<td>
                                <div class='container my-0 p-1 d-flex flex-column'>
                                    <div class='container p-0 text-primary'>
                                        <small>".date("D dS M y",strtotime($start_date))." ".$done."</small>
                                    </div>
                                    <hr class='my-1'>
                                    <div class='container p-1 text-dark'>
                                        <p class='text-danger'>Absent <i class='fas fa-user-times'></i></p>
                                    </div>
                                </div>
                            </td>";
                        $absent_tot++;
                        }

                    
                        // add one day to the date
                        $date=date_create($start_date);
                        date_add($date,date_interval_create_from_date_string("1 day"));
                        $start_date = date_format($date,"Y-m-d");
    
                        $counter++;
                    }else{
                        $data_to_display.="<td></td>";
                    }
                }
                if ($counter < $days && ($index) == $number_weeks) {
                    // echo $counter . " " . $days." $index  $number_weeks <br>";
                    $number_weeks+=1;
                }
                $data_to_display .="</tr>";
            }
            $data_to_display."</table></div>";
            $data_to_display .= "
            <div class='container'>
                <p><strong><u>Statistics</u></strong></p>
                <p><b>Present This Month</b> : ".$present_tot." time(s)</p>
                <p><b>Absent This Month</b> : ".$absent_tot." time(s)</p>
                <p><b>Present (%)</b> : ".(($absent_tot > 0 && $present_tot > 0) ? round(($present_tot/($absent_tot+$present_tot)) * 100) : 0)." %</p><hr>
                <p><b>Present when Roll Call taken</b> : ".$roll_call_taken_present." time(s)</p>
                <p><b>Number of times Roll Call taken</b> : ".$roll_call_taken." time(s)</p>
                <p><b>Attendance when Roll Call was taken</b> : ".($roll_call_taken>0 ? round(($roll_call_taken_present/$roll_call_taken) * 100):0)."%</p><hr>
            </div>";
            echo $data_to_display;

            // loop to display all dates till the end of the month
        }elseif (isset($_GET['arrange_class'])) {
            $class_index = htmlspecialchars_decode($_GET['class_index']);
            $classes = isJson_report($class_index) ? json_decode($class_index):[0,0];
            // echo json_encode($classes);
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $class_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    
                    // first get the value of the class and the position its in
                    $class_dets = null;
                    $position_we_want_it_to_be = $classes[0];
                    $position_it_is_in = 0;
                    for ($index=0; $index < count($class_list); $index++) { 
                        if($class_list[$index]->id == $classes[1]){
                            $class_dets = $class_list[$index];
                            $position_it_is_in = $index;
                            break;
                        }
                    }

                    // after getting the position its in move it to the position it should be
                    $new_array = [];
                    for($index = 0; $index<count($class_list); $index++){
                        // first skip the position its is in to replace it
                        if ($position_it_is_in == $index) {
                            continue;
                        }
                        
                        // add the other class details
                        array_push($new_array,$class_list[$index]);

                        // then add the selected class in the wanted position
                        if ($position_we_want_it_to_be == $index && $class_dets != null) {
                            array_push($new_array,$class_dets);
                        }
                    }

                    // update the database
                    $new_array_to_string = json_encode($new_array);
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'class'";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$new_array_to_string);
                    if($stmt->execute()){
                        echo "<p class='text-success'>Changes have been done successfully!</p>";
                    }else{
                        echo "<p class='text-danger'>Changes not done, an error occured!</p>";
                    }
                }else{
                    echo "<p class='text-danger'>Changes not done, an error occured!</p>";
                }
            }else{
                echo "<p class='text-danger'>Changes not done, an error occured!</p>";
            }
        }elseif (isset($_GET['change_class_name'])) {
            $new_class_name = trim($_GET['new_class_name']);
            $old_class_name = trim($_GET['old_class_name']);
            $class_id = trim($_GET['class_id']);

            // attendance table 
            $update = "UPDATE `attendancetable` SET `class` = '".$new_class_name."' WHERE `class` = '".$old_class_name."'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();

            // class teacher_tbl
            $update = "UPDATE `class_teacher_tbl` SET `class_assigned` = '".$new_class_name."' WHERE `class_assigned` = '".$old_class_name."'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();

            // exams_tbl
            // first change class sitting then change the students sitting
            $select = "SELECT * FROM `exams_tbl`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $classes = substr(trim($row['class_sitting']),1,(strlen(trim($row['class_sitting']))-2));
                    // split class\
                    $class_split = explode(",",$classes);
                    $new_class = "{";
                    for ($index=0; $index < count($class_split); $index++) { 
                        if ($class_split[$index] == $old_class_name) {
                            $new_class.=$new_class_name.",";
                        }else{
                            $new_class.=$class_split[$index].",";
                        }
                    }
                    $new_class = strlen($new_class) > 0 ? substr($new_class,0,strlen($new_class)-1).")" : "";

                    // edit the json data containing the student data
                    $students_sitting = $row['students_sitting'];

                    if (isJson_report($students_sitting)) {
                        $students_sitting = json_decode($students_sitting);
                        for ($index=0; $index < count($students_sitting); $index++) { 
                            if ($students_sitting[$index]->classname == $old_class_name) {
                                $students_sitting[$index]->classname = $new_class_name;
                            }
                        }

                        // change back to string
                        $students_sitting = json_encode($students_sitting);
                    }
                    
                    // update the changes
                    $update = "UPDATE `exams_tbl` SET `class_sitting` = '".$new_class."' , `students_sitting` = '".$students_sitting."' WHERE `exams_id` = '".$row['exams_id']."'";
                    $stmt = $conn2->prepare($update);
                    $stmt->execute();
                }
            }

            // exams record table
            $update = "UPDATE `exam_record_tbl` SET `class name` = '".$new_class_name."' WHERE `class name` = '".$old_class_name."'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();

            // fees structure
            $select = "SELECT * FROM `fees_structure`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                     $classes = $row['classes'];

                     $classes_split = explode(",",$classes);
                     $new_class_list = "";
                     for ($ind=0; $ind < count($classes_split); $ind++) { 
                        if($classes_split[$ind] == "|".$old_class_name."|"){
                            $new_class_list .= "|".$new_class_name."|,";
                        }
                     }

                     $new_class_list = strlen($new_class_list) > 0 ? substr($new_class_list,0,strlen($new_class_list)-1):"";
                     $update = "UPDATE `fees_structure` SET `classes` = '".$new_class_list."' WHERE `ids` = '".$row['ids']."'";
                     $stmt = $conn2->prepare($update);
                     $stmt->execute();
                }
            }

            // update the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $valued = $row['valued'];

                    // split classes
                    $classes = isJson_report($valued) ? json_decode($valued) : [];
                    for($index = 0; $index < count($classes); $index++){
                        // change the classname
                        if($class_id == $classes[$index]->id){
                            $classes[$index]->classes = $new_class_name;
                            break;
                        }
                    }

                    // update the classes
                    $new_class_list = json_encode($classes);

                    // update the classes
                    $update = "UPDATE `settings` SET `valued` = '".$new_class_list."' WHERE `sett` = 'class'";
                    $stmt = $conn2->prepare($update);
                    $stmt->execute();
                }
            }

            // get the student data
            $update = "UPDATE `student_data` SET `stud_class` = '".$new_class_name."' WHERE `stud_class` = '".$old_class_name."'";
            $stmt = $conn2->prepare($update);
            $stmt->execute();

            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $classes_taught = $row['classes_taught'];

                    // split the classes
                    $split_classes = explode(",",$classes_taught);
                    $new_class_list = "";
                    for ($index=0; $index < count($split_classes); $index++) {
                        if ($split_classes[$index] == $old_class_name) {
                            $new_class_list .= $new_class_name.",";
                        }else{
                            $new_class_list .= $split_classes[$index].",";
                        }
                    }

                    $new_class_list = strlen($new_class_list) > 0 ? substr($new_class_list,0,strlen($new_class_list)-1):"";

                    // get the exams classlist
                    $teachers_id = $row['teachers_id'];
                    $teacher_id = explode("|",$teachers_id);
                    $new_data = "";
                    for ($ind=0; $ind < count($teacher_id); $ind++) {
                        if(strlen($teacher_id[$ind]) > 0){
                            $class_n_teacher = substr($teacher_id[$ind],1,strlen($teacher_id[$ind])-2);
                            // split the teacher and class
                            $class_tr = explode(":",$class_n_teacher);
                            if ($class_tr[1] == $old_class_name) {
                                $new_data.="(".$class_tr[0].":".$new_class_name.")|";
                            }else{
                                $new_data.="(".$class_tr[0].":".$class_tr[1].")|";
                            }
                        }
                    }
                    
                    $new_data = strlen($new_data) > 0 ? substr($new_data,0,strlen($new_data)-1):"";

                    // update the table
                    $update = "UPDATE `table_subject` SET `classes_taught` = '".$new_class_list."' , `teachers_id` = '".$new_data."' WHERE `subject_id` = '".$row['subject_id']."'";
                    $stmt = $conn2->prepare($update);
                    $stmt->execute();
                }
            }
            
            // log text
            $log_text = "Class \"".$new_class_name."\" updated successfully";
            log_administration($log_text);
            echo "<p class='text-success'>Class has been successfully changed!</p>";
        }elseif (isset($_GET['get_student_search'])) {
            $class_selected = $_GET['class_selected'];

            // get all the subjects taught in that class
            $select = "SELECT * FROM `table_subject` WHERE `classes_taught` LIKE '%".$class_selected."%';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $subjects = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($subjects,$row);
                }
            }

            // get the student data
            $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$class_selected."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $student_data = [];
            if($result){
                while($row = $result->fetch_assoc()){
                    array_push($student_data,$row);
                }
            }

            // display the table subject
            $data_to_display = "<h6 class='text-center'>Subject Selection Table : ". classNameAdms($class_selected)."</h6>";

            // loop through the subjects list to create the table
            $data_to_display.="<div class='tableme p-2'><table class='table'>";
            
            // start adding the column headers
            $data_to_display .= "<tr><th>No</th><th>Student Name</th>";

            // loop
            for ($index=0; $index < count($subjects); $index++) { 
                $data_to_display.="<th>".ucwords(strtolower($subjects[$index]['display_name']))."</th>";
            }
            $data_to_display.="<th>Action</th></tr>";

            // loop to the students
            for($index = 0; $index<count($student_data); $index++){
                $data_to_display.="<tr><td>".($index+1)."</td><td>".ucwords(strtolower($student_data[$index]['first_name']." ".$student_data[$index]['second_name']))." - ".$student_data[$index]['adm_no']."</td>";
                $disabled = count($subjects) > 0 ? "" : "disabled";
                for($index_2=0;$index_2<count($subjects);$index_2++){
                    $checked = "";
                    if(count(json_decode($student_data[$index]['subjects_attempting'])) == 0){
                        $checked = "checked";
                    }elseif(count(json_decode($student_data[$index]['subjects_attempting'])) > 0 && checkPresnt(json_decode($student_data[$index]['subjects_attempting']),$subjects[$index_2]['subject_id'])){
                        $checked = "checked";
                    }else{
                        $checked = "";
                    }
                    
                    $data_to_display.="<td><input type='checkbox' ".$checked." class='ch_".$student_data[$index]['adm_no']."' value='".$subjects[$index_2]['subject_id']."'></td>";
                }
                $data_to_display.="<td><button $disabled class='btn btn-primary subject_selection_buttons' type='button' id='save_button_subjects_".$student_data[$index]['adm_no']."'><i class='fa fa-save'></i> Save </button>  <span><img src='images/ajax_clock_small.gif' class='image-real-size hide' id='exams_data_loaders_".$student_data[$index]['adm_no']."'></span></td></tr>";
            }

            // body of the table
            $data_to_display.="</table></div>";
            echo $data_to_display;
        }elseif(isset($_GET['get_course_module_terms'])){
            $select = "SELECT * FROM settings WHERE sett = 'courses';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            if(count($courses) > 0){
                foreach($courses as $course){
                    if($course->id == $_GET['get_course_module_terms']){
                        $data_to_display = "<select class='form-control' id='course_module_terms'><option hidden value=''>Select Term to start from</option>";
                        $module_terms = isset($course->no_of_terms) ? $course->no_of_terms : 3;
                        for($index = 0; $index < $module_terms; $index++){
                            $data_to_display .= "<option value='".($index+1)."'>Module Term ".($index+1)."</option>";
                        }
                        $data_to_display .= "</select>";
                        echo $data_to_display;
                        break;
                    }
                }
            }else{
                echo "<p class='text-danger'>No courses available</p>";
            }
        }
    }elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");
        // DELETE THE STUDENT
        if(isset($_POST['admit'])){
            // echo $_POST['surname'];
            $suname = $_POST['surname'];
            $fname = $_POST['fname'];
            $sname = $_POST['sname'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $classenrol = $_POST['enrolment'];
            $parentname = $_POST['parentname'];
            $parentcontact = $_POST['parentconts'];
            $parentrelation = $_POST['parentrela'];
            $course_chosen = $_POST["course_chosen"];

            $parentname2 = $_POST['parentname2'];
            $parentcontact2 = $_POST['parentconts2'];
            $parentrelation2 = $_POST['parentrela2'];
            $course_module_terms = $_POST['course_module_terms'];
            $pmail2 = $_POST['pemail2'];
 
            if (strlen($parentname2) < 1) {
                $parentname2 = "none";
            }
            if (strlen($parentcontact2) < 1) {
                $parentcontact2 = "none";
            }
            if (strlen($parentrelation2) < 1) {
                $parentrelation2 = "none";
            }
            if (strlen($pmail2) < 1) {
                $pmail2 = "none";
            }
 
            $admno = $_POST['admnos'];
            $upis = $_POST['upis'];
            $bcno = 0;
            if(isset($_POST['bcno'])){
                $bcno = $_POST['bcno'];
            }
                $parentemail = 'none';
            if(isset($_POST['pemail'])){
                $parentemail = $_POST['pemail'];
            }
            
                $address = 0;
            if(isset($_POST['address'])){
                $address = $_POST['address'];
            }
            $parent_accupation1 = $_POST['parent_accupation1'];
            $parent_accupation2 = $_POST['parent_accupation2'];
            $intake_year = $_POST['intake_year'];
            $intake_month = $_POST['intake_month'];

            // get the level id
            $level_id = null;
            $module_terms = 0;
            $module_period = "0 days";
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res){
                if($row = $res->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    for($index = 0; $index < count($valued); $index++){
                        if($classenrol == $valued[$index]->classes){
                            $level_id = $valued[$index]->id;
                            $module_terms = isset($valued[$index]->no_of_terms) ? $valued[$index]->no_of_terms : 0;
                            $module_period = isset($valued[$index]->term_duration) ? $valued[$index]->term_duration ." ". $valued[$index]->duration_intervals : "0 days";
                            break;
                        }
                    }
                }
            }

            // get the level id
            $module_terms = 0;
            $module_period = "0 days";
            $term_cost = 0;
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res){
                if($row = $res->fetch_assoc()){
                    $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    for($index = 0; $index < count($courses); $index++){
                        if($course_chosen == $courses[$index]->id){
                            $module_terms = isset($courses[$index]->no_of_terms) ? $courses[$index]->no_of_terms : 0;
                            $module_period = isset($courses[$index]->term_duration) ? $courses[$index]->term_duration ." ". $courses[$index]->duration_intervals : "0 days";
                            $term_cost = isset($courses[$index]->termly_fees) ? $courses[$index]->termly_fees : 0;
                            break;
                        }
                    }
                }
            }
            
            // ------------------------SET COURSE DETAILS----------------------------

            // get the course name
            $course_name_chosen = $course_chosen;

            // set up the course details
            $course_detail = new stdClass();
            $course_detail->course_level = $level_id;
            $course_detail->course_name = $course_name_chosen;
            $course_detail->course_status = 1;
            $course_detail->id = 1;
            $course_detail->module_terms = [];
            
            for($index = 0; $index < $module_terms; $index++){
                $term = new stdClass();
                $term->id = $index + 1;
                $term->term_name = "MODULE ". ($index + 1);
                $term->status = $course_module_terms == ($index+1) ? 1 : 0;
                $term->start_date = $course_module_terms == ($index+1) ? date("YmdHis") : "";
                $term->end_date = $course_module_terms == ($index+1) ? date("YmdHis", strtotime("+".$module_period)) : "";
                $term->termly_cost = $term_cost;
                array_push($course_detail->module_terms, $term);
            }

            // stringify the JSON data
            $course_details_string = json_encode([$course_detail]);

            // ----------------END OF SETTING COURSE DETAILS--------------
            $doa = date("Y-m-d");
            $insert = "INSERT INTO `student_data` (`surname`,`adm_no`,`first_name`,`second_name`,`student_upi`,`D_O_B`,`gender`,`stud_class`,`D_O_A`,`parentName`,`parentContacts`,`parent_relation`,`parent_email`,`parent_name2`,`parent_contact2`,`parent_relation2`,`parent_email2`,`address`,`BCNo`,`primary_parent_occupation`,`secondary_parent_occupation`,`course_done`,`my_course_list`,`intake_year`,`intake_month`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssssssssssssssssssssssss",$suname,$admno,$fname,$sname,$upis,$dob,$gender,$classenrol,$doa,$parentname,$parentcontact,$parentrelation,$parentemail,$parentname2,$parentcontact2,$parentrelation2,$pmail2,$address,$bcno,$parent_accupation1,$parent_accupation2,$course_chosen,$course_details_string,$intake_year,$intake_month);
            if($stmt->execute()){
                $data = "<p style ='color:green;font-size:12px;'>".$fname." ".$sname." has been admitted successfully<br>Use their admission number to search their information</p>";
                $stmt->close();
                $select = "SELECT `surname`,`first_name`,`second_name`,`adm_no` FROM `student_data` order by `ids` DESC LIMIT 1";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $admissionNumber = 0;
                if($result){
                    if($row=$result->fetch_assoc()){
                        $admissionNumber = $row['adm_no'];
                        $name = $row['first_name']." ".$row['second_name'];
                    }
                    // increase the admission number if the admission number has been autogenerated
                    if($_POST['adm_option'] == "automate_adm"){
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'lastadmgen'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if($res){
                            if($row = $res->fetch_assoc()){
                                $valued = $row['valued'] * 1;
                                $valued+=1;

                                // update the database
                                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'lastadmgen'";
                                $stmt = $conn2->prepare($update);
                                $stmt->bind_param("s",$valued);
                                $stmt->execute();
                            }
                        }
                    }
                    $data.= "<input type='text' id='admnohold' value=".$admissionNumber." hidden> <input type='text' id='namehold' value='".$name."' hidden>";
                    echo $data;

                    // proceed and log this event
                    $log_text = $fname." ".$sname." has been admitted successfully";
                    log_administration($log_text);
                }else {
                    echo "Search for the latest students to see their admission number";
                }
            }else{
                echo "<p style ='color:red;font-size:12px;'>Student data not submitted<br>There seem to be an error please try again later</p>";
            }
            $stmt->close();
            $conn2->close();
        }elseif (isset($_POST['delete_student'])) {
            $std_id = $_POST['delete_student'];
            $student_data = getStudentData($std_id, $conn2);
            $delete = "DELETE FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$std_id);
            if($stmt->execute()){
                echo "<p class='text-success'>You have successfully deleted this student.</p>";
                $log_text = ((is_array($student_data)) ? ucwords(strtolower($student_data['first_name']." ". $student_data['second_name'])) : "N/A") ." of Reg No (".$std_id.") discounts have been updated successfully";
                log_administration($log_text);
            }else {
                echo "<p class='text-danger'>An error occured while trying to delete the student. Try again later</p>";
            }
        }elseif(isset($_POST['save_expense_category'])){
            $category_name = $_POST['category_name'];
            $expense_category_budget = $_POST['expense_category_budget'];
            $budget_start_time = $_POST['budget_start_time'];
            $budget_end_date = $_POST['budget_end_date'];
            $expense_categories = $_POST['expense_categories'];
            $expense_notes = $_POST['expense_notes'];

            // check if the note is used
            $select = "SELECT * FROM `expense_category` WHERE `expense_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$expense_notes);
            $stmt->execute();
            $result = $stmt->get_result();
            $present = false;
            $note_name = "N/A";
            if($result){
                if($row = $result->fetch_assoc()){
                    $present = true;
                    $note_name = ucwords(strtolower($row['expense_name']));
                }
            }

            if(!$present){
                $insert = "INSERT INTO `expense_category` (`expense_name`,`expense_budget`,`start_date`,`end_date`,`created_at`,`expense_sub_categories`,`updated_at`,`expense_note`) VALUES (?,?,?,?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $date = date("YmdHis");
                $stmt->bind_param("ssssssss",$category_name,$expense_category_budget,$budget_start_time,$budget_end_date,$date,$expense_categories,$date,$expense_notes);
                $stmt->execute();
    
                // log text
                $log_text = "Expense category \"".$_POST['category_name']."\" added successfully!";
                log_administration($log_text);
                echo "<p class='text-success border border-success my-2 p-2'>Expense name added successfully!.</p>";
            }else{
                echo "<p class='text-danger' id='expense_error_supplier'>The note already used by \"".$note_name."\", try again with another one!</p>";
            }
        }elseif(isset($_POST['update_suppliers'])){
            $company_name = $_POST['company_name'];
            $supplier_prefix = $_POST['supplier_prefix'];
            $supplier_name = $supplier_prefix.". ".$_POST['supplier_name'];
            $supplier_phone = $_POST['supplier_phone'];
            $supplier_email = $_POST['supplier_email'];
            $supplier_address = $_POST['supplier_address'];
            $supplier_bank_name = $_POST['supplier_bank_name'];
            $account_no = $_POST['account_no'];
            $supplier_id = $_POST['supplier_id'];
            $supplier_note = $_POST['supplier_note'];
            
            // update the supplier`s details
            $update = "UPDATE `suppliers` SET `supplier_name` = ?, `company_name` = ?, `supplier_phone` = ?, `supplier_address` = ?, `supplier_bank_name` = ?, `account_no` = ?, `supplier_note` = ? WHERE `supplier_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ssssssss",$supplier_name,$company_name,$supplier_phone,$supplier_address,$supplier_bank_name,$account_no,$supplier_note,$supplier_id);
            $stmt->execute();

            echo "<p class='text-success'>Supplier updated successfully!</p>";
        }elseif(isset($_POST['save_supplier'])){
            $save_supplier = $_POST['save_supplier'];
            $supplier_name = $_POST['supplier_name'];
            $company_name = $_POST['company_name'];
            $supplier_phone = $_POST['supplier_phone'];
            $supplier_email = $_POST['supplier_email'];
            $supplier_address = $_POST['supplier_address'];
            $supplier_openning_balance = $_POST['supplier_openning_balance'];
            $supplier_bank_name = $_POST['supplier_bank_name'];
            $account_no = $_POST['account_no'];
            $supplier_prefix = $_POST['supplier_prefix'];

            // insert statement
            $date_registered = date("YmdHis");
            $insert = "INSERT INTO `suppliers` (`supplier_name`,`company_name`,`supplier_phone`,`supplier_email`,`supplier_address`,`supplier_bank_name`,`account_no`,`date_registered`) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $supplier_name = ($supplier_prefix.". ".$supplier_name);
            $stmt->bind_param("ssssssss",$supplier_name, $company_name, $supplier_phone, $supplier_email, $supplier_address, $supplier_bank_name, $account_no, $date_registered);
            $stmt->execute();
            
            // also check if there is an opening balance
            if($supplier_openning_balance > 0){
                // get the supplier id
                $select = "SELECT * FROM `suppliers` ORDER BY supplier_id DESC LIMIT 1";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $supplier_id = 0;
                if($result){
                    if($row = $result->fetch_assoc()){
                        $supplier_id = $row['supplier_id'];
                    }
                }

                // insert supplier bill
                $insert = "INSERT INTO `supplier_bills` (`supplier_id`,`bill_name`,`bill_amount`,`date_assigned`,`due_date`) VALUES (?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $bill_name = "Opening Balance";
                $stmt->bind_param("sssss",$supplier_id,$bill_name,$supplier_openning_balance,$date_registered,$date_registered);
                $stmt->execute();
            }
            
            echo "<p class='text-success'>Supplier has been registered successfully!</p>";

        }elseif(isset($_POST['subjects_for_student'])){
            // echo $_POST['student_admission'];
            $student_admission = $_POST['student_admission'];
            $student_subjects_chosen = $_POST['student_subjects_chosen'];
            
            // update the students chosen subjects
            $update = "UPDATE `student_data` SET `subjects_attempting` = ? WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ss",$student_subjects_chosen,$student_admission);
            if($stmt->execute()){
                echo "<p class='text-success'>Update done successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error occured!</p>";
            }
        }elseif(isset($_POST['save_payment_options'])){
            $select = "SELECT * FROM `settings` WHERE `sett` = 'payment details'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;

            $payment_data = $_POST['payment_data'];
            if($rnums > 0){
                // update
                $update = "UPDATE `settings` SET `valued` = '".$payment_data."' WHERE `sett` = 'payment details'";
                $stmt = $conn2->prepare($update);
                $stmt->execute();
            }else{
                $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES (?,?)";
                $stmt = $conn2->prepare($insert);
                $paydets = "payment details";
                $stmt->bind_param("ss",$paydets,$payment_data);
                $stmt->execute();
            }
            
            echo "<p class='text-success p-1 my-1 border border-success'>Update done successfully!</p>";
        }elseif (isset($_POST['getPaymentOptions'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'payment details'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['valued'];
                }
            }
        }elseif(isset($_POST['add_route'])){
            $select = "SELECT * FROM `van_routes` ORDER BY route_id DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_id = 1;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $route_id = ($row['route_id']*1) + 1;
                }
            }
            $insert = "INSERT INTO `van_routes` (`route_id`,`route_name`,`route_price`,`route_areas`) VALUES (?,?,?,?)";
            $route_name = $_POST['route_name'];
            $route_price = $_POST['route_price'];
            $route_area_coverage = $_POST['route_area_coverage'];
            $stmt=$conn2->prepare($insert);
            $stmt->bind_param("ssss",$route_id,$route_name,$route_price,$route_area_coverage);
            if($stmt->execute()){
                echo "<p class='text-success'>Route added successfully!</p>";
            }else {
                echo "<p class='text-danger'>Route was not added.Please try again!</p>";
            }
        }elseif (isset($_POST['get_routes'])) {
            $select = "SELECT * FROM `van_routes`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.=$row['route_id']."^".ucwords(strtolower($row['route_name']))."^".$row['route_price']."^".ucwords(strtolower($row['route_areas']))."^".$row['route_status']."|";
                }
                $data_to_display = substr($data_to_display,0,(strlen($data_to_display)-1));
            }
            echo $data_to_display;
        }elseif (isset($_POST['getroute_infor'])) {
            $getroute_infor = $_POST['getroute_infor'];
            $select = "SELECT * FROM `van_routes` WHERE `route_id` = ?;";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$getroute_infor);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $data_to_display.=$row['route_id']."^".$row['route_name']."^".$row['route_price']."^".$row['route_areas']."^".$row['route_vans']."^".$row['route_status'];
                }
            }
            echo $data_to_display;
        }elseif (isset($_POST['update_routes'])) {
            $routes_names = $_POST['routes_names'];
            $routes_price = $_POST['routes_price'];
            $routes_areas = $_POST['routes_areas'];
            $route_ids = $_POST['route_ids'];
            $route_prev_price = $_POST['route_prev_price'];
            if ($route_prev_price == $routes_price) {
                $update = "UPDATE `van_routes` SET `route_name` = ?, `route_price` = ?, `route_areas` = ? WHERE `route_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ssss",$routes_names,$routes_price,$routes_areas,$route_ids);
                if($stmt->execute()){
                    // get the result
                    echo "<p class='text-success'>Route updated successfully!</p>";
                }else {
                    // get the error
                    echo "<p class='text-danger'>Route was not updated.Please try again!</p>";
                }
            }else{
                $update = "UPDATE `van_routes` SET `route_name` = ?, `route_price` = ?, `route_areas` = ?, `route_date_change` = ?, `route_prev_price` = ? WHERE `route_id` = ?";
                $stmt = $conn2->prepare($update);
                $date = date("Y-m-d");
                $stmt->bind_param("ssssss",$routes_names,$routes_price,$routes_areas,$date,$route_prev_price,$route_ids);
                if($stmt->execute()){
                    // get the result
                    echo "<p class='text-success'>Route updated successfully!</p>";
                }else {
                    // get the error
                    echo "<p class='text-danger'>Route was not updated.Please try again!</p>";
                }
            }
        }elseif (isset($_POST['delete_route'])) {
            $r_id = $_POST['delete_route'];
            $delete = "DELETE FROM `van_routes` WHERE `route_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$r_id);
            if($stmt->execute()){
                $update = "UPDATE `school_vans` SET `route_id` = '' WHERE `route_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$r_id);
                $stmt->execute();
                echo "<p class='text-success'>Route deleted successfully!</p>";
            }else {
                echo "<p class='text-danger'>An error occured during operation.Please try again later!</p>";
            }
        }elseif (isset($_POST['save_van'])) {
            $bus_name = $_POST['bus_name'];
            $van_regno = $_POST['van_regno'];
            $van_model = $_POST['van_model'];
            $van_seater_size = $_POST['van_seater_size'];
            $insurance_date = $_POST['insurance_date'] ? $_POST['insurance_date']:"";
            $service_date = $_POST['service_date'] ? $_POST['service_date']:"";
            $routed_lists = $_POST['routed_lists'] ? $_POST['routed_lists']:"";
            $van_driver = $_POST['van_driver'] ? $_POST['van_driver']:"";
            // get the latest bus route
            $select = "SELECT * FROM `school_vans` ORDER BY `van_id` DESC LIMIT 1;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $van_id = 1;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $van_id = ($row['van_id']*1)+1;
                }
            }
            $insert = "INSERT INTO `school_vans` (`van_id`,`van_name`,`van_reg_no`,`model_name`,`van_seater_size`,`route_id`,`insurance_expiration`,`next_service_date`,`driver_name`) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssssssss",$van_id,$bus_name,$van_regno,$van_model,$van_seater_size,$routed_lists,$insurance_date,$service_date,$van_driver);
            if($stmt->execute()){
                echo "<p class='text-success'>Van added successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error occured during operation.Please try again later!</p>";
            }
        }elseif (isset($_POST['get_vans'])) {
            $select = "SELECT * FROM `school_vans`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $driver_id = $row['driver_name'];
                    $driverName = ucwords(strtolower(getNameAdm($driver_id,$conn)));
                    $data_to_display.=$row['van_id']."^".ucwords(strtolower($row['van_name']))."^".$row['van_reg_no']."^".ucwords(strtolower($row['model_name']))."^".$row['van_seater_size']."^".$row['route_id']."^".$row['insurance_expiration']."^".$row['next_service_date']."^".$driverName."|";
                }
                $data_to_display = substr($data_to_display,0,(strlen($data_to_display)-1));
            }
            echo $data_to_display;
        }elseif(isset($_POST['van_infor'])){
            $van_id = $_POST['van_infor'];
            $select = "SELECT * FROM `school_vans` WHERE `van_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$van_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $driver_name = getNameAdm($row['driver_name'],$conn);
                    $route_name = getRoute($row['route_id'],$conn2);
                    $data_to_display.=$row['van_id']."|".$row['van_name']."|".$row['van_reg_no']."|".$row['model_name']."|".$row['van_seater_size']."|".$route_name."|".$row['insurance_expiration']."|".$row['next_service_date']."|".$driver_name;
                }
            }
            echo $data_to_display;
        }elseif (isset($_POST['update_van'])) {
            $van_name = $_POST['van_name'];
            $van_regno = $_POST['van_regno'];
            $van_model = $_POST['van_model'];
            $van_seater_size = $_POST['van_seater_size'];
            $insurance_date = $_POST['insurance_date'];
            $service_date = $_POST['service_date'];
            $van_id = $_POST['van_id'];
            $van_driver = ($_POST['van_driver'] != "Null") ? $_POST['van_driver'] : "";
            $van_route = ($_POST['van_route'] != "Null") ? $_POST['van_route']: "";
            if($_POST['van_driver'] == "Null" && $_POST['van_route'] != "Null"){
                echo "Null driver";
                $update = "UPDATE `school_vans` SET `van_name` = ?, `van_reg_no` = ?, `model_name` = ?, `van_seater_size` = ?, `route_id` = ?, `insurance_expiration` = ?, `next_service_date` = ? WHERE `van_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ssssssss",$van_name,$van_regno,$van_model,$van_seater_size,$van_route,$insurance_date,$service_date,$van_id);
                if($stmt->execute()){
                    echo "<p class='text-success'>Van updates done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>Van updates failed due to an error occurance. Please try again late!</p>";
                }
            }elseif ($_POST['van_driver'] == "Null" && $_POST['van_route'] != "Null") {
                $update = "UPDATE `school_vans` SET `van_name` = ?, `van_reg_no` = ?, `model_name` = ?, `van_seater_size` = ?, `insurance_expiration` = ?, `next_service_date` = ?, `driver_name` = ? WHERE `van_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ssssssss",$van_name,$van_regno,$van_model,$van_seater_size,$insurance_date,$service_date,$van_driver,$van_id);
                if($stmt->execute()){
                    echo "<p class='text-success'>Van updates done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>Van updates failed due to an error occurance. Please try again late!</p>";
                }
            }elseif ($_POST['van_driver'] == "Null" && $_POST['van_route'] == "Null") {
                $update = "UPDATE `school_vans` SET `van_name` = ?, `van_reg_no` = ?, `model_name` = ?, `van_seater_size` = ?, `insurance_expiration` = ?, `next_service_date` = ? WHERE `van_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sssssss",$van_name,$van_regno,$van_model,$van_seater_size,$insurance_date,$service_date,$van_id);
                if($stmt->execute()){
                    echo "<p class='text-success'>Van updates done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>Van updates failed due to an error occurance. Please try again late!</p>";
                }
            }else{
                $update = "UPDATE `school_vans` SET `van_name` = ?, `van_reg_no` = ?, `model_name` = ?, `van_seater_size` = ?, `route_id` = ?, `insurance_expiration` = ?, `next_service_date` = ?, `driver_name` = ? WHERE `van_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sssssssss",$van_name,$van_regno,$van_model,$van_seater_size,$van_route,$insurance_date,$service_date,$van_driver,$van_id);
                if($stmt->execute()){
                    echo "<p class='text-success'>Van updates done successfully!</p>";
                }else{
                    echo "<p class='text-danger'>Van updates failed due to an error occurance. Please try again late!</p>";
                }
            }
        }elseif (isset($_POST['delete_van'])) {
            $van_id = $_POST['delete_van'];
            $delete = "DELETE FROM `school_vans` WHERE `van_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$van_id);
            if($stmt->execute()){
                echo "<p class='text-success'>Van deleted done successfully!</p>";
            }else{
                echo "<p class='text-danger'>Van updates failed due to an error occurance. Please try again late!</p>";
            }
        }elseif (isset($_POST['get_std_enroll_trans'])) {
            $get_std_enroll_trans = $_POST['get_std_enroll_trans'];
            // get the student if they are already enrolled in the transport system
            $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$get_std_enroll_trans);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "-1";
            }else{
                $select = "SELECT * FROM `student_data` WHERE `adm_no` = ? AND `stud_class` != '-1';";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$get_std_enroll_trans);
                $stmt->execute();
                $result = $stmt->get_result();
                $data_to_display = "";
                if ($result) {
                    if($row = $result->fetch_assoc()){
                        $classname = myClassName($row['stud_class']);
                        $data_to_display.=$row['surname']." ".$row['first_name']." ".$row['second_name']."|".$row['address']."|".$classname;
                    }
                }
                echo $data_to_display;
            }
        }elseif(isset($_POST['enroll_students'])){
            $student_id = $_POST['student_id'];
            $route_id = $_POST['route_id'];
            $stoppage = $_POST['stoppage'];
            $date_selected = $_POST['date_selected'];
            $date = $date_selected;
            $status = "1";

            // check if the student has been enrolled before
            $delete = "DELETE FROM `transport_enrolled_students` WHERE `student_id` = '$student_id'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();

            // create the termly routes
            $route_list = [];
            $routes = new stdClass();
            $routes->term = "TERM_1";
            $routes->route = $route_id;

            // add term one
            array_push($route_list,$routes);

            // add term two
            $routes_2 = new stdClass();
            $routes_2->term = "TERM_2";
            $routes_2->route = $route_id;
            array_push($route_list,$routes_2);

            // ADD TERM 2
            $routes_3 = new stdClass();
            $routes_3->term = "TERM_3";
            $routes_3->route = $route_id;
            array_push($route_list,$routes_3);

            // CHANGE IT TO A STRING
            $routes_to_string = json_encode($route_list);
            
            $insert = "INSERT INTO `transport_enrolled_students` (`student_id`,`route_id`,`stoppage`,`date_of_reg`,`status`,`deregistered`) VALUES (?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssss",$student_id,$route_id,$stoppage,$date,$status,$routes_to_string);
            // echo $insert;
            if($stmt->execute()){
                echo "<p class='text-success'>You have successfully enrolled the student in transport system!</p>";
            }else{
                echo "<p class='text-danger'>An error occured, Please try again later!</p>";
            }
        }elseif (isset($_POST['getStudents_enrolled'])) {
            $select = "SELECT * FROM `transport_enrolled_students` ORDER BY `id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            $term = getTermV3($conn2);
            if ($result) {
                $data_to_display1 = "[";
                while($row = $result->fetch_assoc()){
                    $std_name = ucwords(strtolower(getNamestd($row['student_id'],$conn2)));
                    $route_name = ucwords(strtolower(getRoute($row['route_id'],$conn2)));
                    // get the current route name
                    $deregistered = $row['deregistered'];
                    if(isJson_report($deregistered)){
                        $deregistered = json_decode($deregistered);
                        for($index=0;$index<count($deregistered);$index++){
                            $elem = $deregistered[$index];
                            if($elem->term == $term){
                                $route_name = ucwords(strtolower(getRoute($elem->route,$conn2)));
                            }
                        }
                    }
                    $data_to_display.=$row['id']."^".$std_name." (".$row['student_id'].")"."^".$route_name."^".ucwords(strtolower($row['stoppage']))."^".$row['date_of_reg']."|";
                    $data_to_display1.="[\"".$row['id']."\",\"".$std_name." (".$row['student_id'].")\"".",\"".$route_name."\",\"".ucwords(strtolower($row['stoppage']))."\",\"".$row['date_of_reg']."\"],";
                }
                $data_to_display = substr($data_to_display,0,(strlen($data_to_display)-1));
                $data_to_display1 = substr($data_to_display1,0,(strlen($data_to_display1)-1));
                $data_to_display1.="]";
            }
            // echo $data_to_display;
            echo $data_to_display1;
        }elseif (isset($_POST['get_statistics'])) {
            $select = "SELECT COUNT(*) AS 'Total' FROM `transport_enrolled_students`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $enrolled_count = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $enrolled_count = $row['Total'];
                }
            }
            $select = "SELECT COUNT(*) AS 'Total' FROM `school_vans`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $van_count = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $van_count = $row['Total'];
                }
            }
            $select = "SELECT COUNT(*) AS 'Total' FROM `van_routes`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_count = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $route_count = $row['Total'];
                }
            }
            echo $enrolled_count."|".$van_count."|".$route_count;
        }elseif (isset($_POST['student_data'])) {
            $std_id = $_POST['student_data'];
            $select = "SELECT * FROM `transport_enrolled_students` WHERE `id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$std_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "[]";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $stud_name = getNamestd($row['student_id'],$conn2);
                    $getclassname = getmyClassName($row['student_id'],$conn2);
                    $route_id = getRoute($row['route_id'],$conn2);
                    // $data_to_display.=$stud_name."|".$route_id."|".$row['stoppage']."|".$row['date_of_reg']."|".$row['id']."|".$getclassname."|".$row['student_id'];
                    $data_to_display = "[\"".$stud_name."\",\"".$route_id."\",\"".$row['stoppage']."\",\"".$row['date_of_reg']."\",\"".$row['id']."\",\"".$getclassname."\",\"".$row['student_id']."\",\"".replaceDoubleQuotes($row['deregistered'])."\"]";
                }
            }
            echo $data_to_display;
        }elseif (isset($_POST['update_student_trans'])) {
            $data_id = $_POST['data_id'];
            $route_id = $_POST['route_id'];
            $stud_stoppage_trans = $_POST['stud_stoppage_trans'];
            $update = "UPDATE `transport_enrolled_students` SET `stoppage` = ?,`route_id` = ? WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("sss",$stud_stoppage_trans,$route_id,$data_id);
            if($stmt->execute()){
                echo "<p class='text-success'>Student data updated successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
            }
        }elseif(isset($_POST['update_course_progress'])){
            // COURSE UPDATED
            $course_updated = isJson_report($_POST['course_updated']) ? json_decode($_POST['course_updated']) : json_decode("{}");
            $student_id = $_POST['student_id'];
            // study the student`s course progress
            
            //GET THE ACTIVE MODULE
            $active_module = new stdClass();
            $active_module->course_id = null;
            $active_module->active_term = null;
            $active_module->status = 0;
            $active_module->term_details = null;
            $completed_modules = [];
            $inactive_active_modules = [];

            $student_data = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($student_data);
            $stmt->bind_param("s",$student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $course_list = [];
            $student_balance = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                    $student_balance = $row['balance_carry_forward'];
                }
            }

            // flag to disable all active courses after the first active one following the
            // principle that no two active courses can be at the same time.
            $disable = false;
            foreach($course_list as $key => $course){
                if($course->course_status == 1){
                    $modules = $course->module_terms;
                    foreach($modules as $key_terms => $module_term){
                        if($disable){
                            if($course_list[$key]->module_terms[$key_terms]->status == 1){
                                $course_list[$key]->module_terms[$key_terms]->status = 0;
                            }
                        }
                        if($module_term->status == 1){ 
                            $active_module->course_id = $course->id;
                            $active_module->active_term = $module_term->id;
                            $active_module->term_details = $module_term;
                            $disable = true;
                        }
                    }
                }

                if($course->id == $course_updated->id){
                    $module_term = $course->module_terms;
                    foreach($module_term as $term){
                        if($term->status == 2){
                            array_push($completed_modules, $term->id);
                        }else{
                            array_push($inactive_active_modules, $term->id);
                        }
                    }
                }
            }
            // echo json_encode($course_list);
            // return 0;

            // UPDATE THE COURSE BEFORE APPLYING NEW CHANGES
            $update = "UPDATE student_data SET my_course_list= ? WHERE adm_no = ?";
            $stmt = $conn2->prepare($update);
            $course_list = json_encode($course_list);
            $stmt->bind_param("ss", $course_list, $student_id);
            $stmt->execute();

            // PROCEED AND APPLY NEW CHANGES PUSHED BY THE USER!


            // get the courses
            $select = "SELECT * FROM `settings` WHERE sett = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }
            
            $course_duration = "0 days";
            $course_cost = 0;
            if(count($courses) > 0 && isset($course_updated->id)){
                foreach($courses as $course){
                    if($course->id == $course_updated->course_name){
                        $course_duration = $course->term_duration." ".$course->duration_intervals;
                        $course_cost = $course->termly_fees;
                    }
                }
            }

            // configure those that are active with date and those inactive remove the time
            $deactivate = false; //THIS HOLDS THE FLAG TO DEACTIVATE OTHER MODULE TERMS AFTER THE FIRST ACTIVE ONE.
            $updated_completed_modules = [];
            $count_module_fees = 0;
            $count_activated_modules_from_completed = 0;
            if($course_updated->module_terms != null){
                $module_terms = $course_updated->module_terms;
                for($index = 0; $index < count($module_terms); $index++){
                    if($module_terms[$index]->status == 2 && !in_array($module_terms[$index]->id, $completed_modules)){
                        array_push($updated_completed_modules, $module_terms[$index]->id);
                        $count_module_fees += 1;
                    }

                    if($module_terms[$index]->status != 2 && in_array($module_terms[$index]->id, $completed_modules)){
                        $count_activated_modules_from_completed+=1;
                    }

                    // module terms
                    if($module_terms[$index]->status == 1){
                        // term
                        if(!$deactivate){
                            $module_terms[$index]->start_date = date("YmdHis");
                            $module_terms[$index]->end_date = date("YmdHis",strtotime($course_duration));
                            $module_terms[$index]->termly_cost = $course_cost*1;
                        }else{
                            $module_terms[$index]->start_date = "";
                            $module_terms[$index]->end_date = "";
                            $module_terms[$index]->termly_cost = $course_cost*1;
                        }
                        $deactivate = true;
                    }

                    // module terms
                    if($module_terms[$index]->status == 0){
                        // term
                        $module_terms[$index]->start_date = "";
                        $module_terms[$index]->end_date = "";
                        $module_terms[$index]->termly_cost = $course_cost*1;
                    }
                }
            }

            // go through the admin changes and see what courses have been deactivated and they were once active
            $active_module_deactivated = false;
            if($active_module->course_id == $course_updated->id){
                $modules = $course_updated->module_terms;
                foreach($modules as $key => $module){
                    if($module->id == $active_module->active_term && $module->status == 2){
                        $active_module_deactivated = true;
                    }
                    if($module->id == $active_module->active_term && $module->status == 1){
                        $course_updated->module_terms[$key] = $active_module->term_details;
                    }
                }
            }

            $student_data = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
            $stmt = $conn2->prepare($student_data);
            $stmt->bind_param("s",$student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $my_course_list = [];
            $balance_cf = 0;
            $class = "";
            if($result){
                if($row = $result->fetch_assoc()){
                    $class = $row['stud_class'];
                    $balance_cf = $row['balance_carry_forward']*1;
                    $my_course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                }
            }
            // my course list
            foreach($my_course_list as $key => $course){
                if($course->id == $course_updated->id){
                    $my_course_list[$key] = $course_updated;
                }
            }

            include("../finance/financial.php");

            // GET FEES THE STUDENT PAYS PER TERM
            $term = "TERM_1";
            $fees_to_pay = getFeesAsFromTermAdmited($term,$conn2,$class,$student_id);

            $balance_to_add = $count_module_fees*$fees_to_pay;
            $balance_to_deduct = $count_activated_modules_from_completed*$fees_to_pay;

            if($active_module_deactivated){
                // update the user balance and course list
                $term = "TERM_1";
                // $student_balance = getBalanceReports($student_id,$term,$conn2);
                $student_balance = $balance_to_add;
                $student_balance-=$balance_to_deduct;
                
                // update the course modules alone incase its active or inactive
                $update = "UPDATE `student_data` SET `my_course_list` = ?, `balance_carry_forward` = ? WHERE `adm_no` = ?";
                $my_course_list = json_encode($my_course_list);
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sss",$my_course_list, $student_balance, $student_id);
                $stmt->execute();
                echo "<p class='text-success'>Course updates have been done successfully!</p>";
            }else{
                $balance_cf+=$balance_to_add;
                $balance_cf-=$balance_to_deduct;
                
                // update the course modules alone incase its active or inactive
                $update = "UPDATE `student_data` SET `my_course_list` = ?, `balance_carry_forward` = ?  WHERE `adm_no` = ?";
                $my_course_list = json_encode($my_course_list);
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sss",$my_course_list,$balance_cf,$student_id);
                $stmt->execute();
                echo "<p class='text-success'>Course updates have been done successfully!</p>";
            }
        }elseif(isset($_POST['add_modules'])){
            $student_admno = $_POST['student_id'];
            // GET THE STUDENT DATA
            $select = "SELECT * FROM `student_data` WHERE adm_no = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $student_admno);
            $stmt->execute();
            $result = $stmt->get_result();
            $course_list = [];
            $course_chosen = null;
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                    $course_chosen = $row['course_done'];
                }
            }
            
            if(is_array($course_list) && count($course_list) > 0){
                $module_index = 1;
                $module_id = 1;
                $module_termly_cost = 0;
                foreach($course_list as $key_init => $course){
                    if($course->course_status == 1){
                        $modules = $course->module_terms;
                        foreach($modules as $key => $module){
                            if($key == (count($modules)-1)){
                                $module_index = $key+1;
                                $module_id = $module->id;
                                $module_termly_cost = $module->termly_cost;
                            }
                        }
    
                        $term = new stdClass();
                        $term->id = $module_id + 1;
                        $term->term_name = "MODULE ". ($module_index + 1);
                        $term->status = 0;
                        $term->start_date = "";
                        $term->end_date = "";
                        $term->termly_cost = $module_termly_cost;
    
                        // course list
                        array_push($course_list[$key_init]->module_terms,$term);
                        break;
                    }
                }
            }else{
                // INSERT A BRAND NEW COURSE ACCORDING TO WHAT THE PURSUE
                $select = "SELECT * FROM `settings` WHERE sett = 'courses'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $courses = [];
                if($result){
                    if($row = $result->fetch_assoc()){
                        $courses = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    }
                }

                $no_of_term = 0;
                $cost_per_term = 0;
                $course_level = null;
                $term_duration = 0;
                $duration_intervals = "days";
                foreach($courses as $course){
                    if($course->id == $course_chosen){
                        $course_level = $course->course_level;
                        $no_of_term = $course->no_of_terms;
                        $cost_per_term = $course->termly_fees;
                        $duration_intervals = $course->duration_intervals;
                        $term_duration = $course->term_duration;
                    }
                }
                
                $new_course = new stdClass();
                $new_course->course_level = $course_level;
                $new_course->course_name = $course_chosen;
                $new_course->course_status = 1;
                $new_course->id = 1;
                $new_course->module_terms = [];
                $module_terms = [];
                for($index = 0; $index < $no_of_term; $index++){
                    $new_module_term = new stdClass();
                    $new_module_term->id = $index+1;
                    $new_module_term->term_name = "MODULE ".$index+1;
                    $new_module_term->status = $index == 0 ? 1 : 0;
                    $new_module_term->start_date = $index == 0 ? date("YmdHis") : "";
                    $new_module_term->end_date = $index == 0 ? date("YmdHis", strtotime($term_duration." ".$duration_intervals)) : "";
                    $new_module_term->termly_cost = $cost_per_term;
                    array_push($module_terms, $new_module_term);
                }

                // module_terms
                $new_course->module_terms = $module_terms;

                // course_list
                array_push($course_list, $new_course);
            }
            
            // UPDATE THE COURSE LIST
            $update = "UPDATE student_data SET my_course_list = ? WHERE adm_no = ?";
            $stmt = $conn2->prepare($update);
            $my_course_list = json_encode($course_list);
            $stmt->bind_param("ss", $my_course_list, $student_admno);
            $stmt->execute();


            echo "<p class='text-success'>Module has been added successfully!</p>";
        }elseif(isset($_POST['delete_course_module'])){
            $admission_number = $_POST['admission_number'];
            // DELETE THE COURSE MODULE OF THE ACTIVE COURSE
            $select = "SELECT * FROM student_data WHERE adm_no = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $admission_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $courses = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                }
            }
            foreach($courses as $key => $course){
                if($course->course_status == "1"){
                    $modules = $course->module_terms;
                    $new_modules = [];
                    foreach($modules as $key_init => $module){
                        if($key_init < (count($modules)-1)){
                            array_push($new_modules, $module);
                        }
                    }
                    $course->module_terms = $new_modules;

                    $courses[$key]->module_terms = $new_modules;
                    break;
                }
            }

            $update = "UPDATE student_data SET my_course_list = ? WHERE adm_no = ?";
            $stmt = $conn2->prepare($update);
            $my_course = json_encode($courses);
            $stmt->bind_param("ss", $my_course, $admission_number);
            $stmt->execute();

            echo "<p class='text-success'>Student term module has been deleted successfully!</p>";
        }elseif(isset($_POST['deregister_stud'])){
            $deregister_stud = $_POST['deregister_stud'];
            $select_term_deregister = $_POST['select_term_deregister'];
            // update the student data so that it can show when to derigister them

            // get the academic calender
            $acad_calender = "SELECT * FROM `academic_calendar`";
            $stmt = $conn2->prepare($acad_calender);
            $stmt->execute();
            $result = $stmt->get_result();
            $start_yr = date("Y");
            $end_yr = date("Y");
            if ($result) {
                while($row = $result->fetch_assoc()){
                    if($row['term'] == "TERM_1"){
                        $start_yr = date("Y",strtotime($row['start_time']));
                    }

                    if($row['term'] == "TERM_3"){
                        $end_yr = date("Y",strtotime($row['closing_date']));
                    }
                }
            }
            $academic_calender = $start_yr.":".$end_yr;

            // update the student data
            $term_data = "[\"".$select_term_deregister."\",\"".$academic_calender."\"]";
            $update = "UPDATE `transport_enrolled_students` SET `deregistered` = '$term_data', `status` = '0' WHERE `id` = '$deregister_stud'";
            $stmt = $conn2->prepare($update);
            if($stmt->execute()){
                echo "<p class='text-success'>You have successfully unenrolled the student from the transport system!</p>";
            }else{
                echo "<p class='text-danger'>An error occured.Please try again later!</p>";
            }

            // $delete = "DELETE FROM `transport_enrolled_students` WHERE `id` = ?";
            // $stmt = $conn2->prepare($delete);
            // $stmt->bind_param("s",$deregister_stud);
            // if($stmt->execute()){
            //     echo "<p class='text-success'>You have successfully unenrolled the student from the transport system!</p>";
            // }else{
            //     echo "<p class='text-danger'>An error occured.Please try again later!</p>";
            // }
        }elseif(isset($_POST['get_expense_sub_category'])){
            $get_expense_sub_category = $_POST['get_expense_sub_category'];
            $expense_sub_category = $_POST['expense_sub_category'];
            $select = "SELECT * FROM `expense_category` WHERE `expense_id` = '".$get_expense_sub_category."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $sub_categories = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $expense_sub_categories = $row['expense_sub_categories'];
                    $sub_categories = isJson_report($expense_sub_categories) ? json_decode($expense_sub_categories) : [];
                }
            }

            // expense subcategories
            $data_to_display = "<select class='form-control w-75 border border-dark rounded' id='expense_sub_category'><option hidden value=''>Select an Option</option>";
            for($index = 0; $index < count($sub_categories); $index++){
                $id = $sub_categories[$index]->id;
                $name = $sub_categories[$index]->name;
                $selected = $expense_sub_category == $id ? "selected" : "";
                $data_to_display .= "<option value='".$get_expense_sub_category.":".$id."' ".$selected." >".$name."</option>";
            }
            $data_to_display .= "</select>";

            // display select
            echo $data_to_display;
        }elseif(isset($_POST['change_expense_categories'])){
            $new_exp_name = ($_POST['new_exp_name']);
            $exp_indexes = $_POST['exp_indexes'];
            $budget_start_time_edit = date("Ymd",strtotime($_POST['budget_start_time_edit']));
            $budget_end_date_edit = date("Ymd",strtotime($_POST['budget_end_date_edit']));
            $expense_category_budget_edit = $_POST['expense_category_budget_edit'];
            $expense_categories = $_POST['expense_categories'];
            $edit_expense_notes = $_POST['edit_expense_notes'];
            $change_date = date("YmdHis");

            // check if the note is present
            $select = "SELECT * FROM `expense_category` WHERE `expense_id` != ? AND `expense_note` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$exp_indexes, $edit_expense_notes);
            $stmt->execute();
            $result = $stmt->get_result();
            $present = false;
            $note_name = "N/A";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $present = true;
                    $note_name = ucwords(strtolower($row['expense_name']));
                }
            }

            if(!$present){
                // update
                $update = "UPDATE `expense_category` SET `expense_name` = ?, `expense_budget` = ?, `start_date` = ?, `end_date` = ?, `updated_at` = ?, `expense_sub_categories` = ?, `expense_note` = ? WHERE `expense_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ssssssss",$new_exp_name,$expense_category_budget_edit,$budget_start_time_edit,$budget_end_date_edit,$change_date,$expense_categories,$edit_expense_notes,$exp_indexes);
                $stmt->execute();

                // display success message
                echo "<p class='text-success border border-success my-2 p-2'>Expense has been updated successfully!</p>";
            }else{
                echo "<p id='expense_note_selected_edit' class='text-danger border border-danger my-2 p-2'>Expense note selected is already used by \"".$note_name."\". Try again with another one</p>";
            }
            
        }elseif (isset($_POST['set_report_button'])) {
            // get if the report button is set
            $roles = "";
            $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $roles = $row['valued'];
                $new_roled = strlen($roles)> 0 ? json_decode($roles): [];
                // loop through the roles
                for ($index=0; $index < count($new_roled); $index++) { 
                    $role_decode = $new_roled[$index]->roles;
                    // echo $roles;
                    $present = false;
                    for ($i=0; $i < count($role_decode); $i++) { 
                        $btn = $role_decode[$i];
                        if ($btn->name == "my_reports") {
                            $present = !$present;
                            break;
                        }
                    }
                    if (!$present) {
                        echo "not present";
                        $my_reports_role = array("name" => "my_reports","Status" => "no");
                        array_push($role_decode,$my_reports_role);
                        $new_roled[$index]->roles = $role_decode;
                        $updeted_role = json_encode($new_roled);
                        $update = "UPDATE `settings` SET `valued` = '$updeted_role' WHERE `sett` = 'user_roles'";
                        $stmt = $conn2->prepare($update);
                        $stmt->execute();
                    }
                }
            }
        }elseif (isset($_POST['getmystudents'])) {
            $getmystudents = $_POST['getmystudents'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $valued = $row['valued'];
                // get the classes
                $classes = isJson_report($valued) ? json_decode($valued) : [];
                $split_classes = [];
                for($index = 0; $index < count($classes); $index++){
                    array_push($split_classes,$classes[$index]->classes);
                }

                // split classes
                $my_class = $split_classes;
                $data_to_display = "<select class='form-control' name='".$getmystudents."' id='".$getmystudents."'><option value='' hidden>Select an option</option>";
                for ($index=0; $index < count($my_class); $index++) { 
                    $data_to_display.="<option value='".$my_class[$index]."' >".myClassName($my_class[$index])."</option>";
                }

                // all classes
                $data_to_display.="<option value='all' >All Students</option>";
                $data_to_display.="</select>";
                echo $data_to_display;
            }else{
                echo "<p>No classes to display!</p>";
            }
            // echo "p";
        }elseif(isset($_POST['upload_new_students'])){
            $targetDirectory = "../../csv/"; // Directory to store uploaded files$file = $_FILES["file"];
            $customFileName = date("YmdHis"); //Make the custom file name be time

            // file name
            $file = $_FILES["file"];

            // Check if the file type is allowed
            $allowedExtensions = array('xls', 'xlsx', 'csv');
            $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                $message = ['success' => false, "message" => "The only allowed files are the following .'xls', 'xlsx', 'csv'"];
                echo json_encode($message);
                exit();
            }

            // Create the target directory and necessary subdirectories if they don't exist
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            $fileName = $customFileName . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
            $fileLocation = $targetDirectory . $fileName;

            if (move_uploaded_file($file["tmp_name"], $fileLocation)) {

                // read the files details so that you add the student data to the database.
                // Load the Excel file
                $spreadsheet = IOFactory::load($fileLocation);

                // Select the first worksheet
                $worksheet = $spreadsheet->getActiveSheet();

                // Get the highest row and column numbers containing data
                $highestColumn = $worksheet->getHighestColumn(); // e.g., 'F'
                $highestRow = $worksheet->getHighestRow($highestColumn); // e.g., 10

                // Convert the highest column letter to a number
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                $found = false;
                $columns = ['No', 'Student Name','Reg no', 'Sex', 'Course', 'Level', 'Department', 'Intake Month', 'Intake Year', 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts','2nd Parent Name', 'Contacts', 'Term Active'];
                
                // go through the first columns and see if they match the required columns
                $match_counter = 0;
                $columns_read = "";
                for($column_index = 1; $column_index <= count($columns); $column_index++){
                    $cellValue = $worksheet->getCellByColumnAndRow($column_index, 1)->getValue();
                    $columns_read.=$cellValue." == ".$columns[$column_index-1].", ";
                    if(strtolower(trim($cellValue)) == strtolower(trim($columns[$column_index-1]))){
                        $match_counter++;
                    }
                }
                $columns_read.=$match_counter." ".count($columns);

                if($match_counter != count($columns)){
                    $message = ['success' => false, "message" => "Check your columns, they don`t match the template you were given!"];
                    echo json_encode($message);
                    return 0;
                }

                // get the department

                $data = [];
                for($row = 2; $row <= $highestRow; $row++){
                    // get the student data and add to the database
                    $student_name = explode(" ",$worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $reg_no = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $gender = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $course = get_course_id($worksheet->getCellByColumnAndRow(5, $row)->getValue(),$conn2);
                    $level = get_course_level_valid($worksheet->getCellByColumnAndRow(6, $row)->getValue(),$conn2);
                    $intake_month = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $intake_year = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $dob = date("Y-m-d", strtotime(explode("|",$worksheet->getCellByColumnAndRow(10, $row)->getValue())[0]));
                    $doa = date("Y-m-d",strtotime(explode("|",$worksheet->getCellByColumnAndRow(11, $row)->getValue())[0]));
                    $parent_name_1 = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $contact_1 = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $parent_name_2 = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $contact_2 = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $term_active = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    
                    $new_array = [$student_name,$reg_no,$gender,$course,$level,$intake_month,$intake_year,$dob,$doa,$parent_name_1,$contact_1,$parent_name_2,$contact_2,$term_active];
                    array_push($data,$new_array);
                    $student_name[1] = isset($student_name[1]) ? $student_name[1] : "";
                    $student_name[2] = isset($student_name[2]) ? $student_name[2] : "";
                    $student_course = '[{"course_level":1,"course_name":"5","course_status":1,"id":1,"module_terms":[{"id":1,"term_name":"TERM_1","status":0,"start_date":"","end_date":""},{"id":2,"term_name":"TERM_2","status":0,"start_date":"","end_date":""},{"id":3,"term_name":"TERM_3","status":0,"start_date":"","end_date":""}]}]';
                    $insert = "INSERT INTO `student_data` (`first_name`,`second_name`,`surname`,`adm_no`,`course_done`,`stud_class`,`intake_month`,`intake_year`,`D_O_B`,`D_O_A`,`parentName`,`parentContacts`,`parent_name2`,`parent_contact2`,`gender`,`subjects_attempting`,`my_course_list`) 
                    VALUES ('".$student_name[0]."','".$student_name[1]."','".$student_name[2]."','".$reg_no."','".$course."','".$level."','".$intake_month."','".$intake_year."','".$dob."','".$doa."','".$parent_name_1."','".$contact_1."','".$parent_name_2."','$contact_2','".$gender."','[]','$student_course')";
                    $stmt = $conn2->prepare($insert);
                    $stmt->execute();
                }

                // Check if the file exists before attempting to delete it
                if (file_exists($fileLocation)) {
                    // Attempt to delete the file
                    unlink($fileLocation);
                }

                $message = ['success' => true, "message" => "File uploaded successfully!", "file_location" => $fileLocation];
                echo json_encode($message);
            } else {
                $message = ['success' => false, "message" => "Error uploading the file!"];
                echo json_encode($message);
            }
        }elseif(isset($_POST['show_courses'])){
            // get the passed data
            $course_name = $_POST['course_level'];
            $course_list_id = $_POST['course_list_id'];

            // get the level id
            $level_id = null;
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    for($index = 0; $index < count($valued); $index++){
                        if($valued[$index]->classes == $course_name){
                            $level_id = $valued[$index]->id;
                            break;
                        }
                    }
                }
            }

            // get the course list with the course id
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $course_list = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_list = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }

            // loop through the course list to get the course department
            $data_to_display = "<select id='".$course_list_id."' name='course_name' class='form-control'><option value='' hidden>Select Course...</option>";
            for($index = 0; $index < count($course_list); $index++){
                // course levels
                $course_levels = isJson_report($course_list[$index]->course_levels) ? json_decode($course_list[$index]->course_levels) : [];
                // check if the course is present in the array
                $is_present = checkPresnt($course_levels,$level_id);
                if(isset($course_list[$index]->course_level) && $level_id == $course_list[$index]->course_level){
                    $data_to_display.="<option value='".$course_list[$index]->id."'>".$course_list[$index]->course_name."</option>";
                }
            }
            $data_to_display .= "</select>";
            echo $data_to_display;
        }elseif (isset($_POST['get_me_staff'])) {
            $select = "SELECT * FROM `user_tbl` WHERE `school_code` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$_SESSION['schcode']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='color:red;'>No staff present in your school</p>";
            if ($result) {
                $data_to_display = "<select name='mystaff_lists_select' id='mystaff_lists_select' class='form-control'><option value='' hidden>Select Staff</option><option value='-1'><b>All Staff</b></option>";
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="<option value='".$row['user_id']."'>".ucwords(strtolower($row['fullname']))."</option>";
                }
                $data_to_display.="</select>";
            }
            echo $data_to_display;
        }elseif(isset($_POST['getExpenseCategory'])){
            $select = "SELECT * FROM `expense_category`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Set expense categories in the settings before proceeding!.</p>";
            if($result){
                $data_to_display = "<select class='form-control ' name='expense_category' id='expense_category'><option value='' id='main_sele' hidden >Select..</option><option value='All'>All</option>";
                while($row = $result->fetch_assoc()){
                    $data_to_display .= "<option value='".$row['expense_id']."'>".$row['expense_name']."</option>";
                }
                $data_to_display.="</select>";
            }
            echo $data_to_display;
        }elseif(isset($_POST['getExpenseCategories'])){
            $select = "SELECT * FROM `expense_category`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Set expense categories in the settings before proceeding!.</p>";
            if($result){
                $data_to_display = "<select class='form-control border border-dark rounded w-75' name='edit_expense_category' id='edit_expense_category'><option value='' id='main_sele' hidden >Select..</option>";
                while($row = $result->fetch_assoc()){
                    $data_to_display .= "<option class='exp_cats_exp' value='".$row['expense_id']."'>".ucwords(strtolower($row['expense_name']))."</option>";
                }
                $data_to_display.="</select>";
            }
            echo $data_to_display;
        }elseif(isset($_POST['get_expense_categories'])){
            $get_expense_categories = $_POST['get_expense_categories'];
            $expense_category_id = $_POST['expense_category_id'];
            $selected_value = $_POST['selected_value'];

            // expense_categories
            $select = "SELECT * FROM `expense_category`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger border border-danger my-2 p-2'>Set expense categories in the settings before proceeding!.</p>";
            if($result){
                $data_to_display = "<select class='form-control w-75' name='".$expense_category_id."' id='".$expense_category_id."'><option value='' id='main_sele' hidden >Select..</option>";
                while($row = $result->fetch_assoc()){
                    $selected = $selected_value == $row['expense_id'] ? "selected" : "";
                    $data_to_display .= "<option class='exp_cats_exp' ".$selected." value='".$row['expense_id']."'>".ucwords(strtolower($row['expense_name']))."</option>";
                }
                $data_to_display.="</select>";
            }
            echo $data_to_display;
        }elseif(isset($_POST['update_expense'])){
            // echo $_POST['expense_name'];
            $expense_name = $_POST['expense_name'];
            $expense_category = $_POST['expense_category'];
            $document_number = $_POST['document_number'];
            $expense_description = $_POST['expense_description'];
            $total_unit_cost = $_POST['total_unit_cost'];
            $expense_ids_in = $_POST['expense_ids_in'];
            $expense_cash_activity = $_POST['expense_cash_activity'];
            $edit_expense_record_date = $_POST['edit_expense_record_date'];
            $expense_sub_category = $_POST['expense_sub_category'];
            

            $update = "UPDATE `expenses` SET `exp_sub_category` = '".$expense_sub_category."', `exp_name` = '".$expense_name."' , `exp_category` = '".$expense_category."', `document_number` = '".$document_number."', `expense_description` = '".$expense_description."', `exp_amount` = '".$total_unit_cost."', `expense_date` = '".$edit_expense_record_date."', `expense_categories` = '".$expense_cash_activity."' WHERE `expid` = '".$expense_ids_in."'";
            $stmt = $conn2->prepare($update);
            // echo $update;
            // $stmt->bind_param("sssssssss",$expense_name,$expense_category,$unit_name,$expense_quantity,$unit_cost,$total_unit_cost,$edit_expense_record_date,$expense_cash_activity,$expense_ids_in);
            if($stmt->execute()){
                // log text
                $log_message = "Expense \"".$expense_name."\" updated successfully!";
                log_administration($log_message);
                echo "<p class='text-success p-2 border border-success rounded'>Data has been saved successfully!</p>";
            }else{
                echo "<p class='text-danger p-2 border border-danger rounded'>An error occured!</p>";
            }

        }elseif(isset($_POST['delete_expense'])){
            $exp_ids = $_POST['exp_ids'];
            $delete = "DELETE FROM `expenses` WHERE `expid` = '".$exp_ids."'";
            $stmt = $conn2->prepare($delete);
            if($stmt->execute()){
                // log text
                $log_message = "Expense deleted successfully!";
                log_finance($log_message);
                echo "<p class='text-success p-2 border border-success rounded'>Data has been deleted!</p>";
            }else{
                echo "<p class='text-danger p-2 border border-danger rounded'>An error occured!</p>";
            }
        }elseif (isset($_POST['send_mail_to'])) {
            $email_header = $_POST['email_header'];
            $send_mail_to = $_POST['send_mail_to'];
            $cc = $_POST['cc'];
            $bcc = $_POST['bcc'];
            $message = $_POST['message'];


            $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                // contimue to send email
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $email_sets = $row['valued'];
                        $lengths = strlen($email_sets);
                        
                        if($lengths > 0){
                            // send email
                            $json_mail = json_decode($email_sets);
                            $sender_name = $json_mail->sender_name;
                            $email_host_addr = $json_mail->email_host_addr;
                            $email_username = $json_mail->email_username;
                            $email_password = $json_mail->email_password;
                            $tester_mail = $json_mail->tester_mail;

                            // send email
                            try {
                                $mail = new PHPMailer(true);
                        
                                $mail->isSMTP();
                                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                // $mail->Host = 'smtp.gmail.com';
                                $mail->Host = $email_host_addr;
                                $mail->SMTPAuth = true;
                                // $mail->Username = "hilaryme45@gmail.com";
                                // $mail->Password = "cmksnyxqmcgtncxw";
                                $mail->Username = $email_username;
                                $mail->Password = $email_password;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
                                $mail->Port = 587;
                                
                                
                                $mail->setFrom($email_username,$sender_name);
                                strlen(trim($_POST['bcc'])) > 1 ?  $mail->addBCC($bcc,$sender_name) : "";
                                strlen(trim($_POST['cc'])) > 1 ?  $mail->addCC($cc,$sender_name) : "";
                                $mail->addAddress($send_mail_to);
                                $mail->isHTML(true);
                                $mail->Subject = $email_header;
                                $mail->Body = $message;
                        
                                $mail->send();

                                // save the email address sent
                                $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`) VALUES (?,?,?,?,?,?,?)";
                                $stmt = $conn2->prepare($insert);
                                $dates = date("YmdHis",strtotime("3 hours"));
                                $stmt->bind_param("sssssss",$email_username,$send_mail_to,$bcc,$dates,$email_header,$message,$cc);
                                $stmt->execute();
                                // end of saving

                                echo 
                                "
                                <p class='text-success border border-success p-1'><b>Note</b>: <br>Email has been sent successfully. Check your sent E-Mails in your email account to read it.</b>.</p>
                                ";
                            } catch (Exception $th) {
                                echo "<p class='text-danger p-1 border border-danger'>Error : ". $mail->ErrorInfo."</p>";
                            }
                        }else{
                            echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
                        }
                    }
                }
            }else{
                echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
            }
        }elseif (isset($_POST['display_students_in_exams'])) {
            $exams_done = $_POST['exams_done'];
            
            $exams_list = json_decode($exams_done);
            $students_list = [];
            $class_name = $_POST['display_students_in_exams'];
            
            for ($index=0; $index < count($exams_list); $index++) { 
                // get all students who sat for that exams
                $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '".$exams_list[$index]."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $exams_data = $row['students_sitting'];
                        if ($exams_data != null && strlen($exams_data) > 2 && isJson_report($exams_data)) {
                            $exams_data_json = json_decode($exams_data);
                            for ($ind=0; $ind < count($exams_data_json); $ind++) { 
                                if ($exams_data_json[$ind]->classname == $class_name) {
                                    // loop through the classlist and add the students only once
                                    $class_data_list = $exams_data_json[$ind]->classlist;
                                    for ($inde=0; $inde < count($class_data_list); $inde++) { 
                                        if (!checkPresnt($students_list,$class_data_list[$inde])) {
                                            array_push($students_list, $class_data_list[$inde]);
                                        }
                                    }
                                }
                            }
                        }else {
                            // get the students in that particular class
                            $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$class_name."'";
                            $stmt = $conn2->prepare($select);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result) {
                                while ($row = $result->fetch_assoc()) { 
                                    if (!checkPresnt($students_list,$row['adm_no'])) {
                                        array_push($students_list, $row['adm_no']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // loop through the student admission number and get their details
            if (count($students_list) > 0) {
                $data_to_display = "";
                for ($index=0; $index < count($students_list); $index++) {
                    $select = "SELECT * FROM `student_data` WHERE `adm_no` = '".$students_list[$index]."'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $data_to_display .= "
                            <div class='container bg-transparent p-2 my-2 bordered_bottom'>
                                <input type='hidden' id='student_gender_".str_replace(" ","_",$row['adm_no'])."' value=''>
                                <p class='my-1'><b class='students_names' id='student_names_full_".str_replace(" ","_",$row['adm_no'])."'>".($index+1).". ".ucwords(strtolower($row['first_name']." ".$row['second_name']." ".$row['surname']))."</b> (".$row['adm_no'].")</p>
                                <div class='row'>
                                    <div class='col-md-8'>
                                        <label for='student_id_' class='form-control-label'>Comment here:</label>
                                        <textarea id='student_id_".str_replace(" ","_",$row['adm_no'])."' cols='30' rows='3' class='form-control student_exam_commentator' placeholder='Comments go here'></textarea>
                                    </div>
                                    <div class='col-md-4 container-fluid border border-secondary rounded'>
                                        <p class='text-primary student_exams_comments_previewer' id='preview_comments_exams_".str_replace(" ","_",$row['adm_no'])."'>Previews Appear here..</p>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                }
                echo $data_to_display;
            }else{
                echo "<p class='text-danger border border-danger rounded'>No students available during the time of attempting the exams you selected!</p>";
            }
        }elseif (isset($_POST['save_department'])) {
            // save the data
            $save_department = $_POST['save_department'];
            $department_name = $_POST['department_name'];
            $department_code = $_POST['department_code'];
            $department_description = $_POST['department_description'];

            // try saving the data
            // echo $department_code;

            // get the data on the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $present = 0;

            // set the department data
            $department_data = new stdClass();
            $department_data->name = $department_name;
            $department_data->code = $department_code;
            $department_data->subjects = [];
            $department_data->description = $department_description;
            $department_data->date_created = date("YmdHis");
            $department_data->members = [];
            $department_data->hod = "";


            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // if present get the data
                    $dept_data = $row['valued'];

                    if (isJson_report($dept_data)) {
                        $department_json = json_decode($dept_data);

                        // get the id for the lates t departments
                        $max_id = 0;
                        for ($index=0; $index < count($department_json); $index++) { 
                            if($department_json[$index]->id >= $max_id){
                                $max_id = $department_json[$index]->id;
                            }
                        }

                        $department_data->id = $max_id+1;

                        // add it to the list
                        array_push($department_json,$department_data); 

                        // update the data
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $department_details = json_encode($department_json);
                        $stmt->bind_param("s",$department_details);
                        $stmt->execute();
                    }else{
                        $department_json = [];
                        $department_data->id = 1;
                        array_push($department_json,$department_data);

                        // update the data
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $department_details = json_encode($department_json);
                        $stmt->bind_param("s",$department_details);
                        $stmt->execute();
                    }
                }else{
                    $department_json = [];
                    $department_data->id = 1;
                    array_push($department_json,$department_data);

                    // insert the data
                    $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('departments',?)";
                    $stmt = $conn2->prepare($insert);
                    $department_details = json_encode($department_json);
                    $stmt->bind_param("s",$department_details);
                    $stmt->execute();
                }
            }else{
                $department_json = [];
                $department_data->id = 1;
                array_push($department_json,$department_data);

                // insert the data
                $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('departments',?)";
                $stmt = $conn2->prepare($insert);
                $department_details = json_encode($department_json);
                $stmt->bind_param("s",$department_details);
                $stmt->execute();
            }
            $log_text = "Departments (".$department_name.") have been added successfully!";
            log_administration($log_text);
            echo "<p class='text-success'>Departments (".$department_name.") have been added successfully!</p>";
        }elseif(isset($_POST['getData'])){
            // get the department data

            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $department_data = "[]";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    if (isJson_report($row['valued'])) {
                        $department_data = $row['valued'];
                    }
                }
            }

            echo $department_data;
        }elseif(isset($_POST['getStaffAndSubjectDataDept'])){
            // get all subjects taught by the school
            $subject_data = [];
            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($subject_data,$row);
                }
            }

            // get the teacher data
            $teacher_data = [];
            $select = "SELECT * FROM `user_tbl` WHERE `school_code` = '".$_SESSION['schcode']."'";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['fullname'] = ucwords(strtolower($row['fullname']));
                    array_push($teacher_data,$row);
                }
            }

            // teacher details
            $teacher_n_subject = new stdClass();
            $teacher_n_subject->subjects = $subject_data;
            $teacher_n_subject->teacher_data = $teacher_data;

            echo "<input type='hidden'value='".json_encode($teacher_n_subject)."' id='subject_n_students'>";
        }elseif(isset($_POST['update_departments'])){
            // values
            $head_of_dept = $_POST['head_of_dept'];
            $department_id = $_POST['department_id'];
            $department_name = $_POST['department_name'];
            $department_code = $_POST['department_code'];
            $description = $_POST['description'];

            // update the data
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $depts = $row['valued'];
                    if(isJson_report($depts)){
                        $depts = json_decode($depts);
                        for ($index=0; $index < count($depts); $index++) { 
                            if ($depts[$index]->id == $department_id) {
                                // echo $depts[$index]->id;
                                // update its values
                                $depts[$index]->name = $department_name;
                                $depts[$index]->code = $department_code;
                                $depts[$index]->description = $description;
                                $depts[$index]->hod = $head_of_dept;
                            }
                        }


                        // update the database
                        $depts = json_encode($depts);
                        // echo $head_of_dept;
                        $update = "UPDATE `settings` SET `valued` = '".$depts."' WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $stmt->execute();
                    }
                }
            }
            $log_text = "Departments (".$department_name.") with a code of (".$department_code.") have been updated successfully!";
            log_administration($log_text);

            echo "<p class='text-success'>Update done successfully!</p>";
        }elseif(isset($_POST['getDepartments'])){
            $getDepartments = $_POST['getDepartments'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                    }
                }
            }
            
            // get the teacher data
            $teacher_data = [];
            $select = "SELECT * FROM `user_tbl` WHERE `school_code` = '".$_SESSION['schcode']."'";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['fullname'] = ucwords(strtolower($row['fullname']));
                    array_push($teacher_data,$row);
                }
            }
            
            $my_data = new stdClass();
            $my_data->teachers = $teacher_data;
            $my_data->departments = $departments;
            // proceed and return the value
            echo "<input type='hidden' value='".json_encode($my_data)."' id='departments_value'>";
        }elseif(isset($_POST['save_new_members_data'])){
            $department_code = $_POST['department_code'];
            $member_list = $_POST['member_list'];
            // echo $member_list;

            // get the departments list
            $my_members = isJson_report($member_list) ? json_decode($member_list) : [];
            $members_lists = [];
            for ($index=0; $index < count($my_members); $index++) { 
                $lists = new stdClass();
                $lists->name = $my_members[$index];
                $lists->date_joined = date("YmdHis");
                
                array_push($members_lists,$lists);
            }


            // get the departments
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                    }
                }
            }
            // echo json_encode($members_lists);
            
            for ($index=0; $index < count($departments); $index++) { 
                if($departments[$index]->id == $department_code){
                    for ($ind=0; $ind < count($members_lists); $ind++) {
                        if (!isPresent_dept($departments[$index]->members,$members_lists[$ind])) {
                            array_push($departments[$index]->members,$members_lists[$ind]);
                        }
                    }
                    break;
                }
            }

            // update table
            $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($update);
            $my_depts = json_encode($departments);
            // echo $my_depts;
            $stmt->bind_param("s",$my_depts);
            $stmt->execute();

            echo "<p class='text-success'>Departments updated successfully!</p>";
        }elseif(isset($_POST['delete_member'])){
            $delete_member = $_POST['delete_member'];
            $department_id = $_POST['department_id'];

            // get the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                    }
                }
            }

            for ($ind=0; $ind < count($departments); $ind++) { 
                $members = $departments[$ind]->members;
                $id = $departments[$ind]->id;
                $new_members = [];
                for ($index=0; $index < count($members); $index++) {
                    if ($delete_member != $members[$index]->name) {
                        array_push($new_members,$members[$index]);
                    }
                }
                $departments[$ind]->members = $new_members;
            }

            // update table
            $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($update);
            $my_depts = json_encode($departments);
            // echo $my_depts;
            $stmt->bind_param("s",$my_depts);
            $stmt->execute();

            echo "<p class='text-success'>Update has been done successfully!</p>";
        }elseif (isset($_POST['remove_staff'])) {
            $remove_staff = $_POST['remove_staff'];
            $staff_lists = $_POST['staff_lists'];

            // echo $staff_lists;

            // get the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                    }
                }
            }

            if(isJson_report($staff_lists)){
                // staff list
                $staff_lists = json_decode($staff_lists);
                for ($ind=0; $ind < count($departments); $ind++){
                    $members = $departments[$ind]->members;
                    $new_members = [];
                    for ($index=0; $index < count($members); $index++){
                        if(!checkPresnt($staff_lists,$members[$index]->name)){
                            array_push($new_members,$members[$index]);
                        }
                    }
                    $departments[$ind]->members = $new_members;
                }
            }

            // update table
            $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($update);
            $my_depts = json_encode($departments);
            // echo $my_depts;
            $stmt->bind_param("s",$my_depts);
            $stmt->execute();

            echo "<p class='text-success'>Update has been done successfully!</p>";
        }elseif(isset($_POST['getOurSubjectsList'])){
            // get all subjets taught in schools
            $my_subjects = [];
            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($my_subjects,$row);
                }
            }

            // get the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                    }
                }
            }

            // return the data as json
            $data = new stdClass();
            $data->subjects = $my_subjects;
            $data->departments = $departments;

            echo "<input type='hidden' value='".json_encode($data)."' id='department_data_subjects'>";
        }elseif(isset($_POST['addSubjectInDept'])){
            // add subject in department
            $subject_list = $_POST['subject_list'];
            // echo $subject_list;
            $subjects_lists = isJson_report($_POST['subjects_lists']) ? json_decode($_POST['subjects_lists']) : [];
            
            // get the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                        for ($index=0; $index < count($departments); $index++) { 
                            // subject list
                            if ($departments[$index]->id == $subject_list) {
                                $subjects = $departments[$index]->subjects;
                                for ($ind=0; $ind < count($subjects_lists); $ind++) { 
                                    $new_std = new stdClass();
                                    $new_std->name = $subjects_lists[$ind];

                                    if(!isPresent_dept($subjects,$new_std)){
                                        array_push($subjects,$new_std);
                                    }
                                }
                                // echo json_encode($subjects);
                                $departments[$index]->subjects = $subjects;
                            }
                        }

                        // update the database
                        // echo json_encode($departments);
                        // update table
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $my_depts = json_encode($departments);
                        // echo $my_depts;
                        $stmt->bind_param("s",$my_depts);
                        $stmt->execute();
                    }
                }
            }
            echo "<p class='text-success'>Updates done successfully!</p>";
        }elseif(isset($_POST['removeSubject'])){
            $subject_id = $_POST['subject_id'];
            $department_id = $_POST['department'];
            

            // get the settings
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                        for ($index=0; $index < count($departments); $index++) { 
                            if ($departments[$index]->id == $department_id) {
                                $subjects = $departments[$index]->subjects;
                                $new_subjects = [];
                                for ($ind=0; $ind < count($subjects); $ind++) { 
                                    if($subjects[$ind]->name != $subject_id){
                                        array_push($new_subjects,$subjects[$ind]);
                                    }
                                }
                                $departments[$index]->subjects = $new_subjects;
                            }
                        }
                        // update table
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $my_depts = json_encode($departments);
                        // echo $my_depts;
                        $stmt->bind_param("s",$my_depts);
                        $stmt->execute();
                    }
                }
            }
            echo "<p class='text-success'>Updates done successfully!</p>";
        }elseif(isset($_POST['removeSubjects'])){
            $department_id = $_POST['department_id'];
            $subject_list = $_POST['subject_list'];

            $subject_lists = isJson_report($subject_list) ? json_decode($subject_list) : [];

            // get the selected data
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                        for ($index=0; $index < count($departments); $index++) {
                            $subjects = $departments[$index]->subjects;
                            $new_subject = [];
                            for ($ind=0; $ind < count($subjects); $ind++) {
                                if (!checkPresnt($subject_lists,$subjects[$ind]->name)) {
                                    array_push($new_subject,$subjects[$ind]);
                                }
                            }
                            $departments[$index]->subjects = $new_subject;
                        }

                        // update table
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $my_depts = json_encode($departments);
                        // echo $my_depts;
                        $stmt->bind_param("s",$my_depts);
                        $stmt->execute();
                    }
                }
            }
            echo "<p class='text-success'>Updates done successfully!</p>";
        }elseif(isset($_POST['delete_department'])){
            $delete_department = $_POST['delete_department'];
            // get the selected data
            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $departments = [];
            $department_name = "N/A";
            $department_code = "N/A";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    if(isJson_report($row['valued'])){
                        $departments = json_decode($row['valued']);
                        $new_department = [];
                        for ($index=0; $index < count($departments); $index++) {
                            $id = $departments[$index]->id;
                            if ($id == $delete_department) {
                                $department_name = $departments[$index]->name;
                                $department_code = $departments[$index]->code;
                            }
                            if ($id != $delete_department) {
                                array_push($new_department,$departments[$index]);
                            }
                            
                        }
                        $departments = $new_department;

                        // update table
                        $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'departments'";
                        $stmt = $conn2->prepare($update);
                        $my_depts = json_encode($departments);
                        // echo $my_depts;
                        $stmt->bind_param("s",$my_depts);
                        $stmt->execute();
                    }
                }
            }

            // log text
            $log_text = "Departments (".$department_name.") with a code of (".$department_code.") have been deleted successfully!";
            log_administration($log_text);
            echo "<p class='text-success'>Department deleted successfully!</p>";
        }elseif(isset($_POST['delete_revenue_category'])){
            $delete_revenue_category = $_POST['delete_revenue_category'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    $new_values = [];
                    $revenue_name = "N/A";
                    if(isJson_report($valued)){
                        $valued = json_decode($valued);
                        for($index = 0; $index < count($valued); $index++){
                            if($delete_revenue_category == $valued[$index]->category_id){
                                $revenue_name = $valued[$index]->category_name;
                            }
                            if($delete_revenue_category != $valued[$index]->category_id){
                                array_push($new_values,$valued[$index]);
                            }
                        }
                    }

                    // update the database
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'revenue_categories'";
                    $stmt = $conn2->prepare($update);
                    $new_values = json_encode($new_values);
                    $stmt->bind_param("s",$new_values);
                    $stmt->execute();
                    echo "<p class='text-success'>Revenue category has been deleted successfully!</p>";

                    // log text
                    $log_text = "Revenue category \"".$revenue_name."\" deleted successfully!";
                    log_administration($log_text);
                }else{
                    echo "<p class='text-danger'>An error has occured!</p>";
                }
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
            }
        }
    }
    function isPresent_dept($array,$string){
        if (count($array) > 0 ) {
            for ($indexes=0; $indexes <count($array) ; $indexes++) { 
                if ($string->name == $array[$indexes]->name) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }
    
function isJson_report($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}
    function getNameAdm($userid,$conn){
        $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $fullname = "Null";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $fullname = $row['fullname'];
            }
        }
        return $fullname;
    }
    function getNamestd($userid,$conn){
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $fullname = "Null";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $fullname = $row['first_name']." ".$row['second_name']." ".$row['surname'];
            }
        }
        return $fullname;
    }
    function getStudentData($userid,$conn){
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row;
            }
        }
        return [];
    }
    function getmyClassName($userid,$conn){
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $classname = "Null";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $classname = myClassName($row['stud_class']);
            }
        }
        return $classname;
    }
    function getRoute($route_id,$conn){
        $select = "SELECT * FROM `van_routes` WHERE `route_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$route_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_name = "Not Set!";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $route_name = $row['route_name'];
            }
        }
        return $route_name;
    }
    function getTheClass($conn2){
        $select = "SELECT * FROM `settings` WHERE `sett` = 'class';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                // get the class list
                $valued = $row['valued'];
                $classes = isJson_report($valued) ? json_decode($valued) : [];

                // all classes
                $new_classes = [];
                for($index = 0; $index < count($classes); $index++){
                    array_push($new_classes,$classes[$index]->classes);
                }
                // split the string to aray
                // $classes = explode(",",$valued);
                return $new_classes;
            }
        }
    }
    function studCurrentAcadYear($conn2,$studid){
        $select = "SELECT `year_of_study` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$studid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $yearOfStudy = $row['year_of_study'];
                return $yearOfStudy;
            }
        }
   }
    function getAcadYear($conn2){
        $select = "SELECT `academic_year` FROM `academic_calendar` LIMIT 1;";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $academic_year = date("Y");
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                // get the result
                $academic_year = $row['academic_year'];
            }
        }
        return $academic_year;
    }
    function checkAdmUsed($conn2,$admno){
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        for(;;){
            $stmt->bind_param("s",$admno);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                $admno++;
            }else {
                return $admno;
            }
        }
    }
    function getClassCount($conn2,$classes){
        $select = "SELECT COUNT(*) AS 'Total' FROM `student_data` WHERE `stud_class` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$classes);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Total'];
            }
        }
        return 0;
    }
    function getClassTaught($conn2){
        $select = "SELECT `class_assigned` FROM `class_teacher_tbl` WHERE `class_teacher_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$_SESSION['userids']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['class_assigned'];
            }
        }
        return "Null";
    }
    function myClassName($data){
        if($data == "-i"){
            return "Alumni";
        }
        if (strlen($data)>1) {
            return $data;
        }else {
            return "Grade ".$data;
        }
        return $data;
    }
    function getAuthority1($conn,$userid){
        $select = "SELECT `auth` FROM `user_tbl` WHERE `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['auth'];
            }
        }
        return "Null";
    }
    function getMyStaffIn4($conn,$userid){
        if($userid != null){
            $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$userid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    return $row;
                }
            }
        }
        return [];
    }
    function checkPresnt($array, $string){
        if (count($array)>0) {
            for ($i=0; $i < count($array); $i++) { 
                if ($string == $array[$i]) {
                    return 1;
                }
            }
        }
        return 0;
    }
    function getTeacherName($conn,$tr_id){
        $schoolcode = $_SESSION['schoolcode'];
        $select = "SELECT `fullname`, `gender` FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ss",$schoolcode,$tr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if ($row['gender'] == "F") { 
                    return "Mrs. ".ucfirst($row['fullname']);
                }elseif($row['gender'] == "M") {
                    return "Mr. ".ucfirst($row['fullname']);
                }
            }
        }
        return "Null";
    }

    function splitSpace($data){
        return explode(" ",$data)[1];
    }
    function getClasses($conn2){
        $select = "SELECT `sett`,`valued` FROM `settings` WHERE `sett` = 'class'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $res = $stmt->get_result();
        $classes = [];
        if ($res) {
            if($row = $res->fetch_assoc()) {
                $all_classes = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                for($index = 0; $index < count($all_classes); $index++){
                    array_push($classes,$all_classes[$index]->classes);
                }
            }
        }
        if (count($classes)>0) {
            return $classes;
        }else {
            return $classes;
        }
    }
    function checkIfCallRegister($class){
        include("../../connections/conn2.php");
        $select = "SELECT * FROM `attendancetable` WHERE `class`=? AND `date` = ? ";
        $stmt = $conn2->prepare($select);
        $date = date("Y-m-d");
        $stmt->bind_param("ss",$class,$date);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        $stmt->close();
        $conn2->close();
        if($rnums>0){
            return true;
        }else{
            return false;
        }
        return false;
    }
    function getSportHouses($conn2,$clubs_id){
        $select = "SELECT * FROM `settings` WHERE `sett` = 'clubs/sports_house';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $json_data = $row['valued'];
                if (isJson($json_data)) {
                    $json_data = json_decode($json_data);
                    for ($index=0; $index < count($json_data); $index++) { 
                        if ($json_data[$index]->id == $clubs_id) {
                            return $json_data[$index]->Name;
                        }
                    }
                }
            }
        }
        return "Null";
    }
    function createStudentn4($conn2,$result,$searchinfor){
        if($result){
            $xs =0;
            $data="<h6 style='font-size:17px;text-align:center;font-weight:500;'>Results for ".$searchinfor."</h6><div class='row'><div class='col-md-6'></div><div class='col-md-6'><input class='form-control border border-primary' placeholder='Search here' id='search_student_tables'></div></div>";
            $data.="<div class='tableme'><table class='table' >";
            $data.="<tr><th>No.</th>";
            $data.="<th>Student Name</th>";
            //$data.="<th>Middle Name</th>";
            $data.="<th>Adm no.</th>";
            //$data.="<th>BC no.</th>";
            $data.="<th>Gender</th>";
            // $data.="<th>Fees Balance</th>";
            $data.="<th>Intake</th>";
            // $data.="<th>Department</th>";
            $data.="<th>Course Level</th>";
            $data.="<th>Courses</th>";
            $data.="<th>Actions</th></tr>";
            include("../finance/financial.php");

            // courses
            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
            $statement = $conn2->prepare($select);
            $statement->execute();
            $res = $statement->get_result();
            $valued = [];
            if($res){
                if($row = $res->fetch_assoc()){
                    $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                }
            }
            
            // clients

            while($row = $result->fetch_assoc()){
                // setting
                $course_name = "N/A";
                for ($index=0; $index < count($valued); $index++) { 
                    if($valued[$index]->id == $row['course_done']){
                        $course_name = $valued[$index]->course_name;
                        break;
                    }
                }

                // check when the course list will be over
                $course_list = isJson_report($row['my_course_list']) ? json_decode($row['my_course_list']) : [];
                $course_status = "In-Active";
                if (count($course_list) > 0) {
                    foreach($course_list as $course){
                        if($course->course_status == "1"){
                            // get the active module
                            foreach($course->module_terms as $module_term){
                                if($module_term->status == "1"){
                                    $course_status = ucfirst(strtolower($module_term->term_name." ".timeUntilCourseCompletion($module_term->end_date)));
                                    break;
                                }
                            }
                        }
                    }
                }

                // echo json_encode($row);
                $xs++;
                $data.="<tr class='search_this_main' id='search_this_main".($xs)."'><td>".($xs)."</td>";
                $data.="<td class='search_this' id='one".($xs)."'>".ucwords(strtolower($row['first_name']." ".$row['second_name']))."</td>";
                //$data.="<td>".$row['second_name']."</td>";
                $data.="<td class='search_this' id='two".($xs)."'>".$row['adm_no']."</td>";
                //$data.="<td>".$row['BCNo']."</td>";
                $data.="<td class='search_this' id='f_r".($xs)."' >".$row['gender']."</td>";
                $classes = classNameAdms($row['stud_class']);
                // $fees_paid = getFeespaidByStudentAdm($row['adm_no']);
                // $balance = getBalanceAdm($row['adm_no'],getTerm(),$conn2);
                // $data.="<td>Kes ".number_format($fees_paid)."</td>";
                // $data.="<td>Kes ".number_format($balance)."</td>";
                $data.="<td>".$row['intake_month']." ".$row['intake_year']."</td>";
                // $data.="<td class='search_this' id='thr".($xs)."'>".ucwords(strtolower(getSportHouses($conn2,$row['clubs_id'])))."</td>";
                $data.="<td class='search_this' id='lvl".($xs)."'>".$classes."<br><small class='text-primary'>".$course_status."</small></td>";
                $data.="<td class='search_this' id='cse".($xs)."' >".$course_name."</td>";
                $data.="<td>"."<p style='display:flex;'><span style='font-size:12px;' class='link view_students' id='view".$row['adm_no']."'><i class='fa fa-pen-fancy'></i> Edit </span>"."</td></tr>";
            }
            $data.="</table></div>";
            if($xs>0){
                echo $data;
            }else{
                echo "<p style='font-size:15px;color:red;'>No results for:<br> <b>".$searchinfor."</b>..</p>";
            }
        }else{
            echo "<p style='font-size:15px;'>No results..</p>";
        }
    }

    function timeUntilCourseCompletion($rawDate) {
        // Parse the custom format: YYYYmmddHHiiss
        $target = DateTime::createFromFormat('YmdHis', $rawDate);
        if (!$target) {
            return "Invalid date format.";
        }
    
        $now = new DateTime();
        $diff = $now->diff($target);
        $isFuture = $target > $now;
    
        if ($diff->days === 0) {
            return "Today";
        }
    
        $prefix = $isFuture ? "TO BE COMPLETED IN " : "COMPLETED ";
        $suffix = $isFuture ? "" : " AGO";
    
        if ($diff->y > 0) {
            return $prefix . $diff->y . " year" . ($diff->y > 1 ? "s" : "") . $suffix;
        } elseif ($diff->m > 0) {
            return $prefix . $diff->m . " month" . ($diff->m > 1 ? "s" : "") . $suffix;
        } elseif ($diff->d >= 7) {
            $weeks = floor($diff->d / 7);
            return $prefix . $weeks . " week" . ($weeks > 1 ? "s" : "") . $suffix;
        } else {
            return $prefix . $diff->d . " day" . ($diff->d > 1 ? "s" : "") . $suffix;
        }
    }
    
    function createStudentclass($result,$class,$conn2){
        $daros = classNameAdms($class);
        $date_used = $_GET['date_used'];
        if($result){
            $xs =0;
            $data="<h6 style='font-size:17px;text-align:center;margin-bottom:5px;'><u>Check attendance for ".$daros." Members.</u></h6>";
            $data.="<p>Tick the checkbox "."<input type='checkbox' checked readonly>"." if present or leave blank "."<input type='checkbox' readonly>"." when absent, then <strong>Submit</strong></p>";
            $data.="<p id ='tablein'></p>";
            $data.="<div class='tableme'><table >";
            $data.="<tr><th>No</th>";
            $data.="<th>Student name</th>";
            $data.="<th>Adm no.</th>";
            $data.="<th>Gender</th>";
            $data.="<th>Attendance Stats</th>";
            $data.="<th>Class</th>";
            $data.="<th>Present <input type='checkbox' class='present' id='present_all'></th></tr>";
            while($row = $result->fetch_assoc()){
                $xs++;
                $data.="<tr><td>".$xs."</td>";
                $data.="<td><label for='".$row['adm_no']."'>".ucwords(strtolower($row['first_name']." ".$row['second_name']))."</label></td>";
                $data.="<td>".$row['adm_no']."</td>";
                $data.="<td>".$row['gender']."</td>";
                $data.="<td><small>".presentStats($conn2,$row['adm_no'],$row['stud_class'])."</small></td>";
                $data.="<td>".classNameAdms($row['stud_class'])."</td>";
                $data.="<td>"."<input type='checkbox' class='present' id='".$row['adm_no']."'>"."</td></tr>";
            }
            $data.="</table></div>";
            $data.="<span class='text-danger'>Always confirm the date before submitting!</span>";
            if($xs>0){
                echo $data;
            }else {
                echo "<p style='font-size:15px;color:red;'>No students present in ".$daros.".</p>";
            }
        }else{
            echo "<p style='font-size:15px;'>No results after results..</p>";
        }
    }
    function presentStatsYear($conn2,$admno,$class_student){
        // get the current term its starting period and ending period
        $term = getTermV3($conn2);
        // get when the term is starting and ending
        $calender = yearCalenders($conn2);
        // return $calender[0]." - ".$calender[1];
        // get the total number of days this term we have called register
        $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `class` = '".$class_student."'";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$calender[0],$calender[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_attendance = 0;
        if($result){
            if ($row = $result->fetch_assoc()) {
                $total_attendance = $row['Totals'];
            }
        }
        // get the students attendance report
        $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `admission_no` = '".$admno."' AND `class` = '".$class_student."'";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$calender[0],$calender[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_attendance = 0;
        if($result){
            if ($row = $result->fetch_assoc()) {
                $student_attendance = $row['Totals'];
            }
        }
        $percentages = ($total_attendance > 0 ? round(($student_attendance/$total_attendance)*100,1):0);
        return "".$student_attendance." out of ". $total_attendance.".  <span class='text-primary'>(".$percentages."%)</small>";
    }

    function presentStats($conn2,$admno,$class_student){
        // get the current term its starting period and ending period
        $term = getTermV3($conn2);
        // get when the term is starting and ending
        $calender = getAcademicStartV1($conn2,$term);
        // return $calender[0]." - ".$calender[1];
        // get the total number of days this term we have called register
        $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `class` = '".$class_student."'";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$calender[0],$calender[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_attendance = 0;
        if($result){
            if ($row = $result->fetch_assoc()) {
                $total_attendance = $row['Totals'];
            }
        }
        // get the students attendance report
        $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `admission_no` = '".$admno."' AND `class` = '".$class_student."'";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$calender[0],$calender[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_attendance = 0;
        if($result){
            if ($row = $result->fetch_assoc()) {
                $student_attendance = $row['Totals'];
            }
        }
        $percentages = ($total_attendance > 0 ? round(($student_attendance/$total_attendance)*100,1):0);
        return "".$student_attendance." out of ". $total_attendance.".  <span class='text-primary'>(".$percentages."%)</small>";
    }
    function getAcademicStartV1($conn2,$term = "TERM_1"){
        $select = "SELECT * FROM `academic_calendar` WHERE `term` = '".$term."';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return [$row['start_time'],$row['end_time']];
            }
        }
        return [date('Y')."-01-01",date('Y')."-01-30"];
    }
    function yearCalenders($conn2){
        $select = "SELECT * FROM `academic_calendar` WHERE `term` = 'TERM_1';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $start_time = date("Y")."-01-01";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $start_time = $row['start_time'];
            }
        }
        $end_time = date("Y")."-12-31";
        $select = "SELECT * FROM `academic_calendar` WHERE `term` = 'TERM_3';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $end_time = $row['end_time'];
            }
        }
        return [$start_time,$end_time];
    }
    function getTermV3($conn2){
        $date = date("Y-m-d");
        $select = "SELECT `term` FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
        // include("../../connections/conn2.php");
        $stmt= $conn2->prepare($select);
        $stmt->bind_param("ss",$date,$date);
        $stmt->execute();
        $results = $stmt->get_result();
        if($results){
            if ($rowed = $results->fetch_assoc()) {
              $term = $rowed['term'];
              return $term;
            }else {
              return "TERM_1";
            }
        }else {
            return "TERM_1";
          }
        $stmt->close();
        $conn2->close();
    }

    function createTable($results,$arrays,$conn2){
        //Attendace table
        $attendedtable="<h6 style='font-size:15px;text-align:center;margin-top:10px;'><u>Students Present</u></h6>";
        $attendedtable.="<div class='tableme' style = 'border-bottom:1px dashed gray;' ><table class='table' >";
        $attendedtable.="<tr><th>No</th>";
        $attendedtable.="<th>Student Name</th>";
        $attendedtable.="<th>Adm no.</th>";
        $attendedtable.="<th>Gender</th>";
        $attendedtable.="<th>Attendance Stats</th>";
        $attendedtable.="<th>Class</th>";
        $attendedtable.="<th>Status</th></tr>";
        
        $xs = 0;
        if($results){
            while ($rows = $results->fetch_assoc()) {
                $arrays = checkadmissionno($rows['adm_no'],$arrays);
                $xs++;
                $attendedtable.="<tr><td>".$xs."</p></td>";
                $attendedtable.="<td>".ucwords(strtolower($rows['first_name']))." ".ucwords(strtolower($rows['second_name']))."</p></td>";
                $attendedtable.="<td>".$rows['adm_no']."</p></td>";
                $attendedtable.="<td>".$rows['gender']."</p></td>";
                $attendedtable.="<td>".presentStats($conn2,$rows['adm_no'],$rows['stud_class'])."</p></td>";
                $attendedtable.="<td>".classNameAdms($rows['stud_class'])."</p></td>";
                $attendedtable.="<td>"."Present"."</p></td></tr>";
            }
        }
        $attendedtable.="</table></div>";
        if($xs>0){
            echo "<p>".$attendedtable."</p>";
        }else {
            echo "<h6 style='font-size:15px;text-align:center;margin-top:10px;'><u>Students Present</u></h6><p style='text-align:center;margin-top:20px;font-size:12px;font-weight:600;color:red;'>No students present!</p><hr>";            
        }

        return $arrays;
    }
    function checkadmissionno($check,$arrays){

        for ($i=0; $i < count($arrays); $i++) { 
            if ($arrays[$i]==$check){
                unset($arrays[$i]);
                return array_values($arrays);
                break;
            }
        }
        return $arrays;
    }
    function getClassTeacher($conn2,$classname){
        $select = "SELECT `class_teacher_id` FROM `class_teacher_tbl` WHERE `class_assigned` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$classname);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['class_teacher_id'];
            }
        }
        return "Null";
    }
    function insertNotice($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_ids){
        $insert = "INSERT INTO `tblnotification`  (`notification_name`,`Notification_content`,`sender_id`,`notification_status`,`notification_reciever_id`,`notification_reciever_auth`) VALUES (?,?,?,?,?,?)";
        $stmt = $conn2->prepare($insert);
        $stmt->bind_param("ssssss",$messageName,$messagecontent,$sender_ids,$notice_stat,$reciever_id,$reciever_auth);
        $stmt->execute();
    }
    function getAuthority($auth){
        $data = "";
        if ($auth == 0) {
            $data .= "System Administrator";
        } else if ($auth == "1") {
            $data .= "Principal";
        } else if ($auth == "2") {
            $data .= "Deputy Principal Academics";
        } else if ($auth == "3") {
            $data .= "Deputy Principal Administration";
        } else if ($auth == "4") {
            $data .= "Dean of Students";
        } else if ($auth == "5") {
            $data .= "Finance Office";
        } else if ($auth == "6") {
            $data .= "Human Resource Officer";
        } else if ($auth == "7") {
            $data .= "Head of Department";
        } else if ($auth == "8") {
            $data .= "Trainer/Lecturer";
        } else if ($auth == "9") {
            $data .= "Admissions";
        } else {
            $data .= ucwords(strtolower($auth));
        }
        return $data;
    }
    function latestStaffId(){
        include("../../connections/conn1.php");
        $select = "SELECT `user_id` FROM `user_tbl` WHERE `school_code` = ? ORDER BY `user_id` DESC LIMIT 1";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$_SESSION['schoolcode']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['user_id'];
            }
        }
        return 0;
        $stmt->close();
        $conn->close();
    }
    function getRouteEnrolled($conn2,$admno){
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = '".$admno."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_name = "Null";
        $route_price = "Kes 0";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $route_id = $row['route_id'];
                $select = "SELECT * FROM `van_routes` WHERE `route_id` = '".$route_id."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($rowd = $result->fetch_assoc()) {
                        $route_name = ucwords(strtolower($rowd['route_name']));
                        $route_price = "Kes ".number_format($rowd['route_price']);
                    }
                }
            }
        }
        return "<b>".$route_name."</b> @ <b>".$route_price."</b> Per Term";
    }
    function studentYOS($conn2,$admno){
        $select = "SELECT `year_of_study` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $yearOfStudy = explode("|",$row['year_of_study']);
                // explode the data to get the latest year of study 
                $year = $yearOfStudy[(count($yearOfStudy)-1)];
                return $year;
            }
        }
    }
    function withoutLatest($conn2,$admno){
        $select = "SELECT `year_of_study` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $yearOfStudy = explode("|",$row['year_of_study']);
                // explode the data to get the latest year of study 
                $YOS = "";
                for ($i=0; $i < (count($yearOfStudy)-1); $i++) { 
                    $YOS.=$yearOfStudy[$i]."|";
                }
                $YOS = substr($YOS,0,(strlen($YOS)-1));
                return $YOS;
            }
        }
    }
    function checkRolePresent($array_list,$role_name){
        if (count($array_list) > 0) {
            for ($index=0; $index < count($array_list); $index++) { 
                $name = $array_list[$index]->name;
                if ($name == $role_name) {
                    return true;
                }
            }
        }
        return false;
    }
    function classNameAdms($data){
        if ($data == "-1") {
            return "Alumni";
        }
        if ($data == "-2") {
            return "Transfered";
        }
        $datas = "Grade ".$data;
        if (strlen($data)>1) {
            $datas = $data;
        }
        return $datas;
    }
    function removeData($str_string,$word){
        $arr_list = explode(",",$str_string);
        $all_string = "";
        $counter = 0;
        for ($index=0; $index < count($arr_list); $index++) { 
            if ($word != $arr_list[$index]) {
                $all_string.=$arr_list[$index].",";
                $counter++;
            }
        }
        if ($counter > 0) {
            return substr($all_string,0,strlen($all_string)-1);
        }else{
            return "";
        }
    }
    function isHoliday($date){
        $month_dates = date("m",strtotime($date));
        $days = date("d",strtotime($date));

        // Initialize cURL.
        $ch = curl_init();

        // Set the URL that you want to GET by using the CURLOPT_URL option.
        curl_setopt($ch, CURLOPT_URL, 'https://holidays.abstractapi.com/v1/?api_key=112b90958fb74d73a03f555e12986444&country=KE&year='.date("Y").'&month='.$month_dates.'&day='.$days.'');
        // curl_setopt($ch, CURLOPT_URL, 'https://holidays.abstractapi.com/v1/?api_key=112b90958fb74d73a03f555e12986444&country=KE&year=2022');

        // Set CURLOPT_RETURNTRANSFER so that the content is returned as a variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set CURLOPT_FOLLOWLOCATION to true to follow redirects.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute the request.
        $data = curl_exec($ch);

        // Close the cURL handle.
        curl_close($ch);

        // Print the data out onto the page.
        return $data;
    }
    function isHoliday2($date){
        $month_dates = date("m",strtotime($date));
        $days = date("d",strtotime($date));
        $holidays = 
        [
            "Happy New Year" => "01-01",
            "Labour Day/May Day" => "01-05",
            "Madaraka Day" => "01-06",
            "Huduma Day" => "10-10",
            "Mashujaa Day" => "20-10",
            "Jamhuri Day" => "12-12",
            "Christmas Day" => "25-12",
            "Boxing Day" => "26-12"
        ];
        
        // return $data;
        foreach ($holidays as $key => $value) {
            if ($value == $days."-".$month_dates) {
                return [$key,$value];
            }
        }
        return [];
    }
    function differenceDatesMonth($date1,$date2){
        // Declare and define two dates
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        
        // Formulate the Difference between two dates
        $diff = abs($date2 - $date1);
        
        // To get the year divide the resultant date into
        // total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));
        
        // To get the month, subtract it with years and
        // divide the resultant date into
        // total seconds in a month (30*60*60*24)
        $months = floor(($diff) / (30*60*60*24));
        return $months;
    }
    function differenceDatesWeek($date1,$date2){
        // Declare and define two dates
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        
        // Formulate the Difference between two dates
        $diff = abs($date2 - $date1);
        
        // To get the year divide the resultant date into
        // total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));
        
        // To get the month, subtract it with years and
        // divide the resultant date into
        // total seconds in a month (30*60*60*24)
        $week = floor(($diff) / (7*60*60*24));
        return $week;
    }
    function getLeave_Blance($conn,$conn2,$leave_id){
        $monthly_accrual = 0;
        $select = "SELECT * from `leave_categories` WHERE `id` = '".$leave_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $days_entitled = 0;
        $max_days = 0;
        $max_days_carry_forward = 0;
        $leave_year_starts = "";
        $days_are_accrued = "";
        $when_accrued = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $max_days = $row['max_days'];
                $leave_year_starts = $row['leave_year_starts'];
                $days_are_accrued = $row['days_are_accrued'];
                $when_accrued = $row['period_accrued'];
                $max_days_carry_forward = $row['max_days_carry_forward'];

                if ($leave_year_starts == "Start Of Academic Yr") {
                    $term_1 = "TERM_1";
                    $term_one_start = getAcademicStartV1($conn2,$term_1);
                    if ($days_are_accrued == "Monthly") {
                        // devide the maximum days per month and get an accrual of every month
                        $monthly_accrual = $max_days > 0 ? round($max_days/12,2) : 0;
                        // echo $monthly_accrual." Accrued <br>";
                        // check when the accrual is done at the start or end month
                        if ($when_accrued == "Start Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $months_diff * $monthly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled+=$max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;
                            }
                        }elseif ($when_accrued == "End Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today) - 1;
                                $days_entitled = $months_diff * $monthly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled+=$max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today)-1;
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_entitled;
                            }
                        }
                    }elseif ($days_are_accrued == "Yearly") {
                        $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                        $doe = $user_information['doe'];
                        $days_entitled = $max_days;
                        if ($doe <= $term_one_start[0]) {
                            // if its after the start if the academic year then there is carry forward balance

                            // get the days accrued from when term one started
                            $today = date("Y-m-d");
                            $months_diff = differenceDatesMonth($term_one_start[0],$today);
                            // $days_entitled = $months_diff * $monthly_accrual;

                            // below it add from when he was employed a maximum of the carry forward balance 
                            // if we are under three months from the start of academic year they are not emtittled
                            
                            if ($months_diff <= 3) {
                                $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                $days_entitled+=$max_days_carry_forward;
                            }
                        }
                        // echo $days_entitled;
                    }elseif ($days_are_accrued == "Weekly") {
                        $weekly_accrual = $max_days > 0 ? round($max_days/52,2) : 0;
                        if ($when_accrued == "Start Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                $days_entitled = $week_diff * $weekly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    $new_days_accrued = $weekly_accrual * $week_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled += $max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_entitled;
                            }
                        }elseif ($when_accrued == "End Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                $days_entitled = $week_diff * $weekly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                    $new_days_accrued = $weekly_accrual * $week_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled += $max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_entitled;
                            }
                        }
                    }
                }elseif ($leave_year_starts == "Start of january") {
                    $term_1 = "TERM_1";
                    $term_one_start = [date("Y-m-d",strtotime(date("Y")."-01-01")),date("Y-m-d",strtotime(date("Y")."-12-31"))];
                    if ($days_are_accrued == "Monthly") {
                        // devide the maximum days per month and get an accrual of every month
                        $monthly_accrual = $max_days > 0 ? round($max_days/12,2) : 0;
                        // check when the accrual is done at the start or end month
                        if ($when_accrued == "Start Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $months_diff * $monthly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled+=$max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;
                            }
                        }elseif ($when_accrued == "End Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today) - 1;
                                $days_entitled = $months_diff * $monthly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled+=$max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today)-1;
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_entitled;
                            }
                        }
                    }elseif ($days_are_accrued == "Yearly") {
                        $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                        $doe = $user_information['doe'];
                        $days_entitled = $max_days;
                        if ($doe <= $term_one_start[0]) {
                            // if its after the start if the academic year then there is carry forward balance

                            // get the days accrued from when term one started
                            $today = date("Y-m-d");
                            $months_diff = differenceDatesMonth($term_one_start[0],$today);
                            // $days_entitled = $months_diff * $monthly_accrual;

                            // below it add from when he was employed a maximum of the carry forward balance 
                            // if we are under three months from the start of academic year they are not emtittled
                            
                            if ($months_diff <= 3) {
                                $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                $new_days_accrued = $monthly_accrual*$last_yr_diff;
                                $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                $days_entitled+=$max_days_carry_forward;
                            }
                        }
                        // echo $days_entitled;
                    }elseif ($days_are_accrued == "Weekly") {
                        $weekly_accrual = $max_days > 0 ? round($max_days/52,2) : 0;
                        if ($when_accrued == "Start Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                $days_entitled = $week_diff * $weekly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    $new_days_accrued = $weekly_accrual * $week_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled += $max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_entitled;
                            }
                        }elseif ($when_accrued == "End Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                $days_entitled = $week_diff * $weekly_accrual;

                                // below it add from when he was employed a maximum of the carry forward balance 
                                // if we are under three months from the start of academic year they are not emtittled
                                
                                if ($months_diff <= 3) {
                                    $last_yr_diff = differenceDatesMonth($doe,$term_one_start[0]);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                    $new_days_accrued = $weekly_accrual * $week_diff;
                                    $max_days_carry_forward = ($new_days_accrued > $max_days_carry_forward) ? $max_days_carry_forward : $new_days_accrued;
                                    $days_entitled += $max_days_carry_forward;
                                }
                                // echo $days_entitled;
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today) - 1;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_entitled;
                            }
                        }
                    }
                }
            }
        }
        // maximum carryforward 
        
        // check if he used up all the balance for the last academic year
        $select = "SELECT * FROM `settings` WHERE `sett` = 'last_acad_yr'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_used_days = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $last_academic_yr = $row['valued'];
                if (strlen($last_academic_yr) > 0) {
                    $encode_txt = json_decode($last_academic_yr);
                    if (count($encode_txt) > 0) {
                        $last_year = $encode_txt[count($encode_txt)-1];
                        $term_start = $last_year->TERM_1->START_DATE;
                        $term_end = $last_year->TERM_3->END_DATE;
                        // go between the dates and get how much the user has used
                        $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                        if ($user_information['doe'] > $term_start) {
                            // take between doe and term end
                            $date_today = date("Y-m-d");
                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = ? AND `employee_id` = ? AND (`date_applied` >= ? AND `date_applied` <= ?) AND `status` = '1';";
                            $stmt = $conn2->prepare($select);
                            $stmt->bind_param("ssss",$leave_id,$_SESSION['userids'],$user_information['doe'],$term_end);
                            $stmt->execute();
                        }else{
                            // take from the whole year
                            $date_today = date("Y-m-d");
                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = ? AND `employee_id` = ? AND (`date_applied` >= ? AND `date_applied` <= ?)  AND `status` = '1';";
                            $stmt = $conn2->prepare($select);
                            $stmt->bind_param("ssss",$leave_id,$_SESSION['userids'],$term_start,$term_end);
                            $stmt->execute();
                        }
                        $result = $stmt->get_result();
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $total_used_days += $row['days_duration'];
                            }
                        }
                    }else{
                        // there has been no previous academic year
                    }
                    // get the start and the end
                }
            }
        }
        $carry_forward_balance = 0;
        if($total_used_days > 0 && $total_used_days <= $max_days){
            $carry_forward_balance = ($max_days-$total_used_days) <= $max_days_carry_forward ?  ($max_days-$total_used_days) : $max_days_carry_forward;
        }
        // echo $total_used_days." in ".$carry_forward_balance;
        // get the days used by the user in this current year and the last year
        $used_days = 0;
        // get how much has been used this year
        $term = "TERM_1";
        $term_one_start = getAcademicStartV1($conn2,$term);
        $date_today = date("Y-m-d");
        $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = ? AND `employee_id` = ? AND (`date_applied` BETWEEN ? AND  ?);";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ssss",$leave_id,$_SESSION['userids'],$term_one_start[0],$date_today);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                if ($leave_year_starts == "Start Of Academic Yr") {
                    // get if the day applied is three months before the deadlines
                    // get term 1 start
                    $term_1 = "TERM_1";
                    $term_one_start = getAcademicStartV1($conn2,$term_1);
                    // get the date they applied
                    $date_they_applied = $row['date_applied'];
                    $difference_in_months = differenceDatesMonth($term_one_start[0],$date_they_applied);
                    if ($difference_in_months <= 3) {
                        $balance = $carry_forward_balance - ($row['days_duration'] * 1);
                        $used_days += $balance>0 ? 0 : $balance;
                        $used_days+=($row['days_duration']-$carry_forward_balance);
                    }
                }
            }
        }
        $days_entitled -= $used_days;
        return $days_entitled;
    }
    function getLeaveBalance_2($conn,$conn2,$leave_id){
        // get the staff leaf balance
        // start with the month
        $staff_id = $_SESSION['userids'];
        $my_staff_infor = getMyStaffIn4($conn,$staff_id);
        $select = "SELECT * FROM `leave_categories` WHERE `id` = '".$leave_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $leave_year_starts = $row['leave_year_starts'];
                $max_days = $row['max_days'];
                $days_are_accrued = $row['days_are_accrued'];
                $period_accrued = $row['period_accrued'];
                $max_days_carry_forward = $row['max_days_carry_forward'];
                // echo $leave_year_starts;
                
                if ($leave_year_starts == "Start of january") {
                    // get when the client was registered.
                    // if the client was registered after last year started 
                    // start counting days accrued since then
                    // if the client was registered before last year get the days the staff if entitled

                    $leave_days_entitled = 0;
                    $start_this_year = date("Ymd",strtotime("01-01-".date("Y")));
                    $doe = date("Ymd",strtotime($my_staff_infor['doe']));
                    if (($start_this_year*1) > ($doe*1)) {
                        // meaning that he was registered last year

                        // we want to get the number of days used last year then get the balance.
                        // Carry forward the balance if its before or march this year

                        // first get if the staff was registered last year
                        $doe = date("Ym",strtotime($doe))."01";
                        $last_yr_start = date("Y",strtotime("-1 Year"))."0101";
                        if (($doe*1) > ($last_yr_start*1)) {
                            // echo $doe;
                            // this means the the staff was registered last year

                            if($days_are_accrued == "Monthly"){
                                // get the number of that were entitled to the staff in last year
                                $employment_month = date("Ym",strtotime($doe))."01";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";

                                // days entitled last year
                                $days_entitled = differenceDatesMonth($employment_month,$end_of_last_year);
                                
                                // days accrued each month
                                $days_accrued_each_month = round($max_days/12,2);

                                // days_to_use_current_year
                                $days_to_use_current_year = 0;
                                if ($period_accrued == "Start Of Month") {
                                    $days_to_use_current_year = round($days_entitled*$days_accrued_each_month);
                                }elseif ($period_accrued == "End Of Month") {
                                    $days_to_use_current_year =  round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_to_use_current_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = date("Y")."0101";
                                $today = date("Ym",strtotime("1 Month"))."01";
                                
                                $days_entitled = differenceDatesMonth($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Month") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_month);
                                }elseif($period_accrued == "End Of Month"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }

                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Yearly"){
                                $employment_month = date("Ym",strtotime($doe))."01";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";
                                
                                $days_to_use_current_year = $max_days;

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = $max_days;
                                $start_year = date("Y")."0101";
                                $today = date("Ym",strtotime("1 Month"))."01";

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Weekly"){
                                // get the number of that were entitled to the staff in last year
                                $employment_month = date("Ym",strtotime($doe))."01";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";

                                // days entitled last year
                                $days_entitled = differenceDatesWeek($employment_month,$end_of_last_year);
                                // days accrued each month
                                $days_accrued_each_week = round($max_days/$days_entitled,2);

                                // days_to_use_current_year
                                $days_to_use_last_year = 0;
                                if ($period_accrued == "Start Of Week") {
                                    $days_to_use_last_year = round($days_entitled*$days_accrued_each_week);
                                }elseif ($period_accrued == "End Of Week") {
                                    $days_to_use_last_year =  round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_to_use_last_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_last_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = date("Y")."0101";
                                $today = date("Ymd");
                                
                                $days_entitled = differenceDatesWeek($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Week") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_week);
                                }elseif($period_accrued == "End Of Week"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                return $days_ent;
                            }
                        }else{
                            // echo $doe;
                            // this means the the staff was registered year(s) last year
                            if($days_are_accrued == "Monthly"){
                                // get the number of that were entitled to the staff in last year
                                $start_year = date("Y",strtotime($doe))."0101";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";

                                // days entitled last year
                                $days_entitled = differenceDatesMonth($start_year,$end_of_last_year);
                                
                                // days accrued each month
                                $days_accrued_each_month = round($max_days/12,2);

                                // days_to_use_current_year
                                $days_to_use_current_year = 0;
                                if ($period_accrued == "Start Of Month") {
                                    $days_to_use_current_year = round($days_entitled*$days_accrued_each_month);
                                }elseif ($period_accrued == "End Of Month") {
                                    $days_to_use_current_year =  round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_to_use_current_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = date("Y")."0101";
                                $today = date("Ym",strtotime("1 Month"))."01";
                                
                                $days_entitled = differenceDatesMonth($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Month") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_month);
                                }elseif($period_accrued == "End Of Month"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }

                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Yearly"){
                                $start_year = date("Y",strtotime($doe))."0101";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";
                                
                                $days_to_use_current_year = $max_days;

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = $max_days;
                                $start_year = date("Y")."0101";
                                $today = date("Ym",strtotime("1 Month"))."01";

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Weekly"){
                                // get the number of that were entitled to the staff in last year
                                $start_year = date("Y",strtotime($doe))."0101";
                                $end_of_last_year = date("Y",strtotime("-1 Year"))."1231";

                                // days entitled last year
                                $days_entitled = differenceDatesWeek($start_year,$end_of_last_year);
                                // days accrued each month
                                $days_accrued_each_week = round($max_days/$days_entitled,2);

                                // days_to_use_current_year
                                $days_to_use_last_year = 0;
                                if ($period_accrued == "Start Of Week") {
                                    $days_to_use_last_year = round($days_entitled*$days_accrued_each_week);
                                }elseif ($period_accrued == "End Of Week") {
                                    $days_to_use_last_year =  round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_to_use_last_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_last_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = date("Y")."0101";
                                $today = date("Ymd");
                                
                                $days_entitled = differenceDatesWeek($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Week") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_week);
                                }elseif($period_accrued == "End Of Week"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                return $days_ent;
                            }
                        }
                        
                    }else{
                        // meaning he was registered this year.
                        // get the date he was registered to today and get the days the user is entitled.
                        $doe = date("Ym",strtotime($doe))."01";

                        // get the number of days used this year by the staff
                        $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `employee_id` = '".$staff_id."' AND `leave_category` = '".$leave_id."' AND `status` != '1'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $days_used = 0;
                        if($result){
                            if($row = $result->fetch_assoc()){
                                $days_used = ($row['Total']*1);
                            }
                        }

                        
                        if ($days_are_accrued == "Monthly") {
                            $today = date("Ym",strtotime("1 month"))."01";
                            // echo $today;
                            // get the difference in months between this dates
                            $days_entitled = differenceDatesMonth($doe,$today)+1;
                            // echo $days_entitled;

                            $days_accrued_each_month = round($max_days/12,2);
                            if ($period_accrued == "Start Of Month") {
                                return round($days_entitled * $days_accrued_each_month) - $days_used;
                            }elseif($period_accrued == "End Of Month"){
                                return round(($days_entitled-1) * $days_accrued_each_month) - $days_used;
                            }else{
                                return "<p class='text-danger'>Invalid Leave Setup</p>";
                            }
                        }elseif($days_are_accrued == "Yearly"){
                            return $max_days;
                        }elseif($days_are_accrued == "Weekly"){
                            // number of weeks this year
                            $start_yr = date("Ymd",strtotime(date("Y")."0101"));
                            $end_yr = date("Ymd",strtotime(date("Y")."1231"));

                            $no_of_weeks = differenceDatesWeek($start_yr,$end_yr);
                            $days_accrued_each_week = round($max_days/$no_of_weeks,2);
                            
                            $today = date("Ymd");
                            $days_entitled = differenceDatesWeek($doe,$today)+1;

                            // echo $days_entitled;
                            if ($period_accrued == "Start Of Week") {
                                return round($days_entitled * $days_accrued_each_week) - $days_used;
                            }elseif($period_accrued == "End Of Week"){
                                return round(($days_entitled-1) * $days_accrued_each_week) - $days_used;
                            }else{
                                return "<p class='text-danger'>Invalid Leave Setup</p>";
                            }
                        }
                    }
                }elseif ($leave_year_starts == "Start Of Academic Yr") {
                    // get when the client was registered.
                    // if the client was registered after last year started 
                    // start counting days accrued since then
                    // if the client was registered before last year get the days the staff if entitled

                    // get the start of this academic year
                    $calender = yearCalenders($conn2);
                    $leave_days_entitled = 0;
                    $start_this_year = date("Ymd",strtotime($calender[0]));
                    $doe = date("Ymd",strtotime($my_staff_infor['doe']));
                    if (($start_this_year*1) > ($doe*1)) {
                        // meaning that he was registered last year

                        // we want to get the number of days used last year then get the balance.
                        // Carry forward the balance if its before or march this year

                        // first get if the staff was registered last year
                        $doe = date("Ym",strtotime($doe))."01";
                        $last_yr = ((date("Y",strtotime($calender[0]))*1) - 1);
                        $last_yr_start = $last_yr.date("md",strtotime($calender[0]));
                        if (($doe*1) > ($last_yr_start*1)) {
                            // echo $doe;
                            // this means the the staff was registered last year

                            if($days_are_accrued == "Monthly"){
                                // get the number of that were entitled to the staff in last year
                                $employment_month = date("Ym",strtotime($doe))."01";
                                $end_of_last_year = addDays($calender[0],-1);

                                // days entitled last year
                                $days_entitled = differenceDatesMonth($employment_month,$end_of_last_year);
                                
                                // days accrued each month
                                $days_accrued_each_month = round($max_days/12,2);

                                // days_to_use_current_year
                                $days_to_use_last_year = 0;
                                if ($period_accrued == "Start Of Month") {
                                    $days_to_use_last_year = round($days_entitled*$days_accrued_each_month);
                                }elseif ($period_accrued == "End Of Month") {
                                    $days_to_use_last_year =  round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_to_use_last_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_last_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = date("Ymd",strtotime($calender[0]));
                                $today = date("Ym",strtotime("1 Month"))."01";
                                $end_of_this_yr = date("Ymd",strtotime($calender[1]));
                                
                                $days_entitled = differenceDatesMonth($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Month") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_month);
                                }elseif($period_accrued == "End Of Month"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_this_yr))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (addMonths($calender[0],3))){
                                    $days_ent += $carry_forward_bal;
                                }

                                return $days_ent;
                            }elseif($days_are_accrued == "Yearly"){
                                $employment_month = date("Ym",strtotime($doe))."01";
                                $end_of_last_year = date("Ymd",strtotime(addDays($calender[0],-1)));
                                // echo $end_of_last_year;
                                $days_to_use_current_year = $max_days;

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = $max_days;
                                $start_year = date("Ymd",strtotime($calender[0]));
                                $end_years = date("Ymd",strtotime($calender[1]));

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_years))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;
                                // echo $days_used_this_yr." days";

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (addMonths($calender[0],3))){
                                    $days_ent += $days_ent;
                                }
                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Weekly"){
                                // get the number of that were entitled to the staff in last year
                                $employment_month = date("Ymd",strtotime($doe))."01";
                                $end_of_last_year = addDays($calender[0],-1);

                                // days entitled last year
                                $days_entitled = differenceDatesWeek($employment_month,$end_of_last_year);
                                // days accrued each month
                                $days_accrued_each_week = round($max_days/$days_entitled,2);

                                // days_to_use_current_year
                                $days_to_use_last_year = 0;
                                if ($period_accrued == "Start Of Week") {
                                    $days_to_use_last_year = round($days_entitled*$days_accrued_each_week);
                                }elseif ($period_accrued == "End Of Week") {
                                    $days_to_use_last_year =  round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_to_use_last_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($employment_month))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_last_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = $calender[0];
                                $today = date("Ymd");
                                
                                $days_entitled = differenceDatesWeek($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Week") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_week);
                                }elseif($period_accrued == "End Of Week"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($today))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (addMonths($calender[0],3))){
                                    $days_ent += $carry_forward_bal;
                                }
                                return $days_ent;
                            }
                        }else{
                            // echo $doe;
                            // this means the the staff was registered year(s) last year
                            if($days_are_accrued == "Monthly"){
                                // get the number of that were entitled to the staff in last year
                                $start_year = addYearsAdministration($calender[0],-1);
                                $end_of_last_year = addDays($calender[0],-1);

                                // days entitled last year
                                $days_entitled = differenceDatesMonth($start_year,$end_of_last_year);
                                
                                // days accrued each month
                                $days_accrued_each_month = round($max_days/$days_entitled,2);

                                // days_to_use_current_year
                                $days_to_use_current_year = 0;
                                if ($period_accrued == "Start Of Month") {
                                    $days_to_use_current_year = round($days_entitled*$days_accrued_each_month);
                                }elseif ($period_accrued == "End Of Month") {
                                    $days_to_use_current_year =  round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_to_use_current_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = $calender[0];
                                $today = date("Ym",strtotime("1 Month"))."01";
                                $end_of_this_yr = date("Ymd",strtotime($calender[1]));
                                
                                $days_entitled = differenceDatesMonth($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Month") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_month);
                                }elseif($period_accrued == "End Of Month"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_month);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_this_yr))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (addMonths($calender[0],3))){
                                    $days_ent += $carry_forward_bal;
                                }

                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Yearly"){
                                $start_year = addYearsAdministration($calender[0],-1);
                                $end_of_last_year = addDays($calender[0],-1);
                                
                                $days_to_use_current_year = $max_days;

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_current_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = $max_days;
                                $start_year = date("Ymd",strtotime($calender[0]));
                                $end_of_this_yr = date("Ymd",strtotime($calender[1]));

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_this_yr))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                // echo $days_ent;
                                return $days_ent;
                            }elseif($days_are_accrued == "Weekly"){
                                // get the number of that were entitled to the staff in last year
                                $start_year = addYearsAdministration($calender[0],-1);
                                $end_of_last_year = addDays($calender[0],-1);

                                // days entitled last year
                                $days_entitled = differenceDatesWeek($start_year,$end_of_last_year);
                                // days accrued each month
                                $days_accrued_each_week = round($max_days/$days_entitled,2);

                                // days_to_use_current_year
                                $days_to_use_last_year = 0;
                                if ($period_accrued == "Start Of Week") {
                                    $days_to_use_last_year = round($days_entitled*$days_accrued_each_week);
                                }elseif ($period_accrued == "End Of Week") {
                                    $days_to_use_last_year =  round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_to_use_last_year = 0;
                                }

                                // get the number of date used last year
                                $days_used = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_last_year))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = ($row['Total']*1);
                                    }
                                }

                                // get the carry forward balance
                                $carry_forward_bal = $days_to_use_last_year - $days_used;

                                // get how many days the user is eligible this year
                                $days_entitled_this_yr = 0;
                                $start_year = $calender[0];
                                $today = date("Ymd");
                                $end_of_this_yr = $calender[1];
                                
                                $days_entitled = differenceDatesWeek($start_year,$today)+1;
                                
                                if ($period_accrued == "Start Of Week") {
                                    $days_entitled_this_yr = round($days_entitled * $days_accrued_each_week);
                                }elseif($period_accrued == "End Of Week"){
                                    $days_entitled_this_yr = round(($days_entitled-1) * $days_accrued_each_week);
                                }else{
                                    $days_entitled_this_yr = 0;
                                }

                                // get the number of days used this year
                                $days_used_this_yr = 0;
                                $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$staff_id."' AND `status` = '1' AND (`from` BETWEEN '".date("Y-m-d",strtotime($start_year))."'  AND '".date("Y-m-d",strtotime($end_of_this_yr))."');";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result){
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used_this_yr = ($row['Total']*1);
                                    }
                                }

                                // get the days entitled
                                $days_ent = $days_entitled_this_yr - $days_used_this_yr;
                                $carry_forward_bal = $carry_forward_bal > $max_days_carry_forward ? $max_days_carry_forward : $carry_forward_bal;

                                // add carryforward balance if the user its after the third month
                                if((date("Ymd",strtotime(date("Ym")."01"))*1) < (date("Ymd",strtotime(date("Y")."0301"))*1)){
                                    $days_ent += $carry_forward_bal;
                                }
                                return $days_ent;
                            }
                        }
                        
                    }else{
                        // meaning he was registered this year.
                        // get the date he was registered to today and get the days the user is entitled.
                        $doe = date("Ym",strtotime($doe))."01";

                        // get the number of days used this year by the staff
                        $select = "SELECT sum(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `employee_id` = '".$staff_id."' AND `leave_category` = '".$leave_id."' AND `status` != '1'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $days_used = 0;
                        if($result){
                            if($row = $result->fetch_assoc()){
                                $days_used = ($row['Total']*1);
                            }
                        }

                        
                        if ($days_are_accrued == "Monthly") {
                            $today = date("Ym",strtotime("1 month"))."01";
                            // echo $today;
                            // get the difference in months between this dates
                            $days_entitled = differenceDatesMonth($doe,$today)+1;
                            // echo $days_entitled;

                            $days_accrued_each_month = round($max_days/12,2);
                            if ($period_accrued == "Start Of Month") {
                                return round($days_entitled * $days_accrued_each_month) - $days_used;
                            }elseif($period_accrued == "End Of Month"){
                                return round(($days_entitled-1) * $days_accrued_each_month) - $days_used;
                            }else{
                                return "<p class='text-danger'>Invalid Leave Setup</p>";
                            }
                        }elseif($days_are_accrued == "Yearly"){
                            return $max_days;
                        }elseif($days_are_accrued == "Weekly"){
                            // number of weeks this year
                            $start_yr = $calender[0];
                            $end_yr = $calender[1];

                            $no_of_weeks = differenceDatesWeek($start_yr,$end_yr);
                            $days_accrued_each_week = round($max_days/$no_of_weeks,2);
                            
                            $today = date("Ymd");
                            $days_entitled = differenceDatesWeek($doe,$today)+1;

                            // echo $days_entitled;
                            if ($period_accrued == "Start Of Week") {
                                return round($days_entitled * $days_accrued_each_week) - $days_used;
                            }elseif($period_accrued == "End Of Week"){
                                return round(($days_entitled-1) * $days_accrued_each_week) - $days_used;
                            }else{
                                return "<p class='text-danger'>Invalid Leave Setup</p>";
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }
    function getLeave_Balance($conn,$conn2,$leave_id){
        $monthly_accrual = 0;
        $select = "SELECT * from `leave_categories` WHERE `id` = '".$leave_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $days_entitled = 0;
        $days_balance = 0;
        $max_days = 0;
        $max_days_carry_forward = 0;
        $leave_year_starts = "";
        $days_are_accrued = "";
        $when_accrued = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $max_days = $row['max_days'];
                $leave_year_starts = $row['leave_year_starts'];
                $days_are_accrued = $row['days_are_accrued'];
                $when_accrued = $row['period_accrued'];
                $max_days_carry_forward = $row['max_days_carry_forward'];

                if ($leave_year_starts == "Start Of Academic Yr") {
                    $term_1 = "TERM_1";
                    $term_one_start = getAcademicStartV1($conn2,$term_1);
                    if ($days_are_accrued == "Monthly") {
                        // devide the maximum days per month and get an accrual of every month
                        $monthly_accrual = $max_days > 0 ? round($max_days/12,2) : 0;
                        // echo $monthly_accrual." Accrued <br>";
                        // check when the accrual is done at the start or end month
                        if ($when_accrued == "Start Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // echo $unjsoned;
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = count($jsoned_cal); $index>0; $index--){
                                        $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed < count($jsoned_cal); $indexed++) { 
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    $days_balance = $days_entitled;
                                    // echo $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND `status` = '1'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                        }elseif ($when_accrued == "End Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied) - 1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied) - 1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today) - 1;
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']) - 1;
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today) - 1;
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today) - 1;
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                        }
                    }elseif ($days_are_accrued == "Yearly") {
                        $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                        $doe = $user_information['doe'];
                        $days_entitled = $max_days;
                        if ($doe >= $term_one_start[0]) {
                            $days_used = 0;
                            $today = date("Y-m-d");
                            // get days used by the user
                            $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                            $stmt = $conn2->prepare($select);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result) {
                                if ($row = $result->fetch_assoc()) {
                                    $days_used = $row['Total'];
                                }
                            }
                            $balance = $days_entitled - $days_used;
                            $days_balance = $balance;
                        }elseif($doe <= $term_one_start[0]){
                            // get what year the employee was registered
                            $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                            $stmt = $conn2->prepare($get_acad_cal);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $unjsoned = "";
                            if ($result) {
                                if ($row = $result->fetch_assoc()) {
                                    $unjsoned = $row['valued'];
                                }
                            }
                            
                                // get when employee was employed
                            $carry_forward = 0;
                            if (strlen(trim($unjsoned)) > 0) {
                                $jsoned_cal = json_decode($unjsoned);
                                $academicyr_term_admitted = -1;
                                for($index = 0; $index<count($jsoned_cal); $index++){
                                    $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                    $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                    if ($doe >=  $term_1 && $doe <= $term_3) {
                                        $academicyr_term_admitted = $index;
                                    }else{
                                        continue;
                                    }
                                }
                                // if the academic year employed is know calculate the carry forward balances
                                $carry_forward = 0;
                                if ($academicyr_term_admitted > -1) {
                                    // loop and calculate the carry forward balance\
                                    for ($index=$academicyr_term_admitted; $index <= count($jsoned_cal); $index++) { 
                                        // loop through the years
                                        if($academicyr_term_admitted == $index){
                                            $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                            $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                            $difference_in_months = differenceDatesMonth($doe,$term_1);
                                            $leave_days_entitled = $max_days;
                                            // get the amount used
                                            // get the days that the user has used to get the balance remaining
                                            $days_used = 0;
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                if ($row = $result->fetch_assoc()) {
                                                    $days_used = $row['Total'];
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled-$days_used;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }else{
                                            $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                            $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                            $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                            $leave_days_entitled = $max_days;
                                            
                                            // get the applications time and date
                                            // if its three months or less before the start of the year
                                            // get the carry forward balance deduct it first then the days entitled that year
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $days_duration = $row['days_duration'];
                                                    $date_applied = $row['date_applied'];

                                                    // get the date difference if its between three months
                                                    $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                    $whats_left_of_these_months = 0;
                                                    if ($months_differences <= 3) {
                                                        if ($carry_forward > 0) {
                                                            $new_carry_forward_balance = $carry_forward - $days_duration;
                                                            $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? $max_days : ($max_days + $new_carry_forward_balance);
                                                        }else{
                                                            $whats_left_of_these_months = $max_days - $days_duration;
                                                        }
                                                    }else{
                                                        $whats_left_of_these_months = $max_days - $days_duration;
                                                    }
                                                    $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - $max_days;
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }
                                    }
                                }else{
                                    for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                        {
                                            $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                            $leave_days_entitled = $max_days;
                                            
                                            // get the applications time and date
                                            // if its three months or less before the start of the year
                                            // get the carry forward balance deduct it first then the days entitled that year
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $days_duration = $row['days_duration'];
                                                    $date_applied = $row['date_applied'];

                                                    // get the date difference if its between three months
                                                    $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                    $whats_left_of_these_months = 0;
                                                    if ($months_differences <= 3) {
                                                        if ($carry_forward > 0) {
                                                            $new_carry_forward_balance = $carry_forward - $days_duration;
                                                            $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? $max_days : ($max_days + $new_carry_forward_balance);
                                                        }else{
                                                            $whats_left_of_these_months = $max_days - $days_duration;
                                                        }
                                                    }else{
                                                        $whats_left_of_these_months = $max_days - $days_duration;
                                                    }
                                                    $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - $max_days;
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }
                                    }
                                }
                                // echo $carry_forward;
                                // get the users balance for that academic year and use the carry forward balance where neccessary
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $max_days;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                // get days used by the user
                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        // if the month they took the leaf is less than three months the term they started use the link
                                        $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                        if ($months_differences <= 3) {
                                            $balance = ($carry_forward - ($row['days_duration']*1));
                                            $carry_forward -= ($row['days_duration']*1);
                                            $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                        }else{
                                            $days_entitled -= ($row['days_duration']*1);
                                        }
                                    }
                                }
                                $days_balance = $days_entitled;
                                // echo $days_entitled;
                            }else{
                                // get the users balance for that academic year and use the carry forward balance where neccessary
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $max_days;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                // get days used by the user
                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        // if the month they took the leaf is less than three months the term they started use the link
                                        $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                        if ($months_differences <= 3) {
                                            $balance = ($carry_forward - ($row['days_duration']*1));
                                            $carry_forward -= ($row['days_duration']*1);
                                            $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                        }else{
                                            $days_entitled -= ($row['days_duration']*1);
                                        }
                                    }
                                }
                                // echo $days_entitled;
                                $days_balance = $days_entitled;
                            }
                        }
                        // end here
                    }elseif ($days_are_accrued == "Weekly") {
                        $weekly_accrual = $max_days > 0 ? round($max_days/52,2) : 0;
                        if ($when_accrued == "Start Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            
                            // STARTS HERE
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                        // $leave_days_entitled = $week_diff * $weekly_accrual;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // get as if they were employed this year
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                // $leave_days_entitled = $week_diff * $weekly_accrual;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                            // ENDS HERE
                        }elseif ($when_accrued == "End Of Week") {
                            // starts here
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($doe,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                        // $leave_days_entitled = $week_diff * $weekly_accrual;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $difference_in_months = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE);
                                                $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$jsoned_cal[$indexed]->TERM_3->END_DATE) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied);
                                                        $week_diff = differenceDatesWeek($jsoned_cal[$indexed]->TERM_1->START_DATE,$date_applied)-1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied'])-1;
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // get as if they were employed this year
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                // $leave_days_entitled = $week_diff * $weekly_accrual;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                            // ends here
                        }
                    }
                }elseif ($leave_year_starts == "Start of january") {
                    $term_1 = "TERM_1";
                    $term_one_start = [date("Y-m-d",strtotime(date("Y"."-01-01"))),date("Y-m-d",strtotime(date("Y"."-12-31")))];
                    if ($days_are_accrued == "Monthly") {
                        // devide the maximum days per month and get an accrual of every month
                        $monthly_accrual = $max_days > 0 ? round($max_days/12,2) : 0;
                        // echo $monthly_accrual." Accrued <br>";
                        // check when the accrual is done at the start or end month
                        if ($when_accrued == "Start Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = count($jsoned_cal); $index>0; $index--){
                                        $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_1->START_DATE))."-01-01");
                                        $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_3->END_DATE))."-12-31");
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed < count($jsoned_cal); $indexed++) { 
                                            $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                            $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$term_3);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$jsoned_cal[$indexed]->TERM_1->START_DATE."' AND '".$jsoned_cal[$indexed]->TERM_3->END_DATE."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$term_3);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_1,$today);
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_1,$row['date_applied']);
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '' AND `employee_id` = '' AND (`date_applied` BETWEEN '' AND '') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                        }elseif ($when_accrued == "End Of Month") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        // $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        // $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_1->START_DATE))."-01-01");
                                        $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_3->END_DATE))."-12-31");
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                            $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$term_3) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied) - 1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                                $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3) - 1;
                                                $leave_days_entitled = $difference_in_months * $monthly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied) - 1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($months_differences * $monthly_accrual) : (($months_differences * $monthly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($months_differences*$monthly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($months_differences*$monthly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today) - 1;
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']) - 1;
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today) - 1;
                                    $days_entitled = $months_diff * $monthly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // if its after the start if the academic year then there is carry forward balance

                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today) - 1;
                                $days_entitled = $months_diff * $monthly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                        }
                    }elseif ($days_are_accrued == "Yearly") {
                        $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                        $doe = $user_information['doe'];
                        $days_entitled = $max_days;
                        if ($doe >= $term_one_start[0]) {
                            $days_used = 0;
                            $today = date("Y-m-d");
                            // get days used by the user
                            $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                            $stmt = $conn2->prepare($select);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result) {
                                if ($row = $result->fetch_assoc()) {
                                    $days_used = $row['Total'];
                                }
                            }
                            $balance = $days_entitled - $days_used;
                        }elseif($doe <= $term_one_start[0]){
                            // get what year the employee was registered
                            $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                            $stmt = $conn2->prepare($get_acad_cal);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $unjsoned = "";
                            if ($result) {
                                if ($row = $result->fetch_assoc()) {
                                    $unjsoned = $row['valued'];
                                }
                            }
                            
                                // get when employee was employed
                            $carry_forward = 0;
                            if (strlen(trim($unjsoned)) > 0) {
                                $jsoned_cal = json_decode($unjsoned);
                                $academicyr_term_admitted = -1;
                                for($index = 0; $index<count($jsoned_cal); $index++){
                                    $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                    $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                    if ($doe >=  $term_1 && $doe <= $term_3) {
                                        $academicyr_term_admitted = $index;
                                    }else{
                                        continue;
                                    }
                                }
                                // if the academic year employed is know calculate the carry forward balances
                                $carry_forward = 0;
                                if ($academicyr_term_admitted > -1) {
                                    // loop and calculate the carry forward balance\
                                    for ($index=$academicyr_term_admitted; $index <= count($jsoned_cal); $index++) { 
                                        // loop through the years
                                            $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_1->START_DATE))."-01-01");
                                            $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_3->END_DATE))."-12-31");
                                        if($academicyr_term_admitted == $index){
                                            // $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                            // $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                            $difference_in_months = differenceDatesMonth($doe,$term_1);
                                            $leave_days_entitled = $max_days;
                                            // get the amount used
                                            // get the days that the user has used to get the balance remaining
                                            $days_used = 0;
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                if ($row = $result->fetch_assoc()) {
                                                    $days_used = $row['Total'];
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled-$days_used;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }else{
                                            // $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                            // $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                            $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                            $leave_days_entitled = $max_days;
                                            
                                            // get the applications time and date
                                            // if its three months or less before the start of the year
                                            // get the carry forward balance deduct it first then the days entitled that year
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $days_duration = $row['days_duration'];
                                                    $date_applied = $row['date_applied'];

                                                    // get the date difference if its between three months
                                                    $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                    $whats_left_of_these_months = 0;
                                                    if ($months_differences <= 3) {
                                                        if ($carry_forward > 0) {
                                                            $new_carry_forward_balance = $carry_forward - $days_duration;
                                                            $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? $max_days : ($max_days + $new_carry_forward_balance);
                                                        }else{
                                                            $whats_left_of_these_months = $max_days - $days_duration;
                                                        }
                                                    }else{
                                                        $whats_left_of_these_months = $max_days - $days_duration;
                                                    }
                                                    $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - $max_days;
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }
                                    }
                                }else{
                                    for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                        {
                                            $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_1->START_DATE))."-01-01");
                                            $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_3->END_DATE))."-12-31");
                                            $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                            $leave_days_entitled = $max_days;
                                            
                                            // get the applications time and date
                                            // if its three months or less before the start of the year
                                            // get the carry forward balance deduct it first then the days entitled that year
                                            $today = date("Y-m-d");
                                            // get days used by the user
                                            $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                            $stmt = $conn2->prepare($select);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $days_duration = $row['days_duration'];
                                                    $date_applied = $row['date_applied'];

                                                    // get the date difference if its between three months
                                                    $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                    $whats_left_of_these_months = 0;
                                                    if ($months_differences <= 3) {
                                                        if ($carry_forward > 0) {
                                                            $new_carry_forward_balance = $carry_forward - $days_duration;
                                                            $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? $max_days : ($max_days + $new_carry_forward_balance);
                                                        }else{
                                                            $whats_left_of_these_months = $max_days - $days_duration;
                                                        }
                                                    }else{
                                                        $whats_left_of_these_months = $max_days - $days_duration;
                                                    }
                                                    $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - $max_days;
                                                }
                                            }
                                            $carry_forward = $leave_days_entitled;
                                            $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                        }
                                    }
                                }
                                // echo $carry_forward;
                                // get the users balance for that academic year and use the carry forward balance where neccessary
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $max_days;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                // get days used by the user
                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        // if the month they took the leaf is less than three months the term they started use the link
                                        $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                        if ($months_differences <= 3) {
                                            $balance = ($carry_forward - ($row['days_duration']*1));
                                            $carry_forward -= ($row['days_duration']*1);
                                            $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                        }else{
                                            $days_entitled -= ($row['days_duration']*1);
                                        }
                                    }
                                }
                                // echo $days_entitled;
                                $days_balance = $days_entitled;
                            }else{
                                // get the users balance for that academic year and use the carry forward balance where neccessary
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                $days_entitled = $max_days;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                // get days used by the user
                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        // if the month they took the leaf is less than three months the term they started use the link
                                        $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                        if ($months_differences <= 3) {
                                            $balance = ($carry_forward - ($row['days_duration']*1));
                                            $carry_forward -= ($row['days_duration']*1);
                                            $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                        }else{
                                            $days_entitled -= ($row['days_duration']*1);
                                        }
                                    }
                                }
                                // echo $days_entitled;
                                $days_balance = $days_entitled;
                            }
                        }
                        // end here
                    }elseif ($days_are_accrued == "Weekly") {
                        $weekly_accrual = $max_days > 0 ? round($max_days/52,2) : 0;
                        if ($when_accrued == "Start Of Week") {
                            // when is the term started
                            // first check when  the user was employed
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            
                            // STARTS HERE
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                            $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$term_3);
                                                $week_diff = differenceDatesWeek($doe,$term_3);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $week_diff = differenceDatesWeek($term_1,$term_3);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                        $week_diff = differenceDatesWeek($term_1,$term_3);
                                                        // $leave_days_entitled = $week_diff * $weekly_accrual;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                                $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $week_diff = differenceDatesWeek($term_1,$term_3);
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                        $week_diff = differenceDatesWeek($term_1,$date_applied);
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied']);
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // get as if they were employed this year
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today);
                                // $leave_days_entitled = $week_diff * $weekly_accrual;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                            // ENDS HERE
                        }elseif ($when_accrued == "End Of Week") {
                            // starts here
                            $user_information = getMyStaffIn4($conn,$_SESSION['userids']);
                            $doe = $user_information['doe'];
                            // if they were employed before the academic year started check how much they are entittle to.
                            if ($doe <= $term_one_start[0]) {
                                // go back to the academic calenders and check when the user was last registered
                                $get_acad_cal = "SELECT * FROM `settings` WHERE `valued` = 'last_acad_yr'";
                                $stmt = $conn2->prepare($get_acad_cal);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $unjsoned = "";
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $unjsoned = $row['valued'];
                                    }
                                }
                                // get when employee was employed
                                if (strlen(trim($unjsoned)) > 0) {
                                    $jsoned_cal = json_decode($unjsoned);
                                    $academicyr_term_admitted = -1;
                                    for($index = 0; $index<count($jsoned_cal); $index++){
                                        // $term_1 = $jsoned_cal[$index]->TERM_1->START_DATE;
                                        // $term_3 = $jsoned_cal[$index]->TERM_3->END_DATE;
                                        $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_1->START_DATE))."-01-01");
                                        $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$index]->TERM_3->END_DATE))."-12-31");
                                        if ($doe >=  $term_1 && $doe <= $term_3) {
                                            $academicyr_term_admitted = $index;
                                        }else{
                                            continue;
                                        }
                                    }
                                    // if the academic year is found then start the calculations for the balance carry forward
                                    $carry_forward = 0;
                                    if ($academicyr_term_admitted > -1) {
                                        // start calculating amount carry forward and the days used for the leaf
                                        for ($indexed=$academicyr_term_admitted; $indexed <= count($jsoned_cal); $indexed++) { 
                                            if($academicyr_term_admitted == $indexed){
                                                $difference_in_months = differenceDatesMonth($doe,$term_3);
                                                $week_diff = differenceDatesWeek($doe,$term_3) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                // get the amount used
                                                // get the days that the user has used to get the balance remaining
                                                $days_used = 0;
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        $days_used = $row['Total'];
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled-$days_used;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }else{
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $week_diff = differenceDatesWeek($term_1,$term_3) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                        $week_diff = differenceDatesWeek($term_1,$term_3) - 1;
                                                        // $leave_days_entitled = $week_diff * $weekly_accrual;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }else{
                                        for ($indexed=0; $indexed <= count($jsoned_cal); $indexed++) { 
                                            {
                                                $term_1 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_1->START_DATE))."-01-01");
                                                $term_3 = date("Y-m-d",date("Y",strtotime($jsoned_cal[$indexed]->TERM_3->END_DATE))."-12-31");
                                                $difference_in_months = differenceDatesMonth($term_1,$term_3);
                                                $week_diff = differenceDatesWeek($term_1,$term_3) - 1;
                                                $leave_days_entitled = $week_diff * $weekly_accrual;
                                                
                                                // get the applications time and date
                                                // if its three months or less before the start of the year
                                                // get the carry forward balance deduct it first then the days entitled that year
                                                $today = date("Y-m-d");
                                                // get days used by the user
                                                $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$term_1."' AND '".$term_3."') AND (`status` = '1'  OR  `status` = '0')";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $days_duration = $row['days_duration'];
                                                        $date_applied = $row['date_applied'];

                                                        // get the date difference if its between three months
                                                        $months_differences = differenceDatesMonth($term_1,$date_applied);
                                                        $week_diff = differenceDatesWeek($term_1,$date_applied)-1;
                                                        $whats_left_of_these_months = 0;
                                                        if ($months_differences <= 3) {
                                                            if ($carry_forward > 0) {
                                                                $new_carry_forward_balance = $carry_forward - $days_duration;
                                                                $whats_left_of_these_months = $new_carry_forward_balance >= 0 ? ($week_diff * $weekly_accrual) : (($week_diff * $weekly_accrual) + $new_carry_forward_balance);
                                                            }else{
                                                                $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                            }
                                                        }else{
                                                            $whats_left_of_these_months = ($week_diff * $weekly_accrual) - $days_duration;
                                                        }
                                                        $leave_days_entitled = ($leave_days_entitled + $whats_left_of_these_months) - ($week_diff * $weekly_accrual);
                                                    }
                                                }
                                                $carry_forward = $leave_days_entitled;
                                                $carry_forward = $carry_forward <= $max_days_carry_forward ? $carry_forward : $max_days_carry_forward;
                                            }
                                        }
                                    }
                                    // echo $carry_forward;
                                    // get the users balance for that academic year and use the carry forward balance where neccessary
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($term_one_start[0],$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    // get days used by the user
                                    $select = "SELECT * FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            // if the month they took the leaf is less than three months the term they started use the link
                                            $months_differences = differenceDatesMonth($term_one_start[0],$row['date_applied'])-1;
                                            if ($months_differences <= 3) {
                                                $balance = ($carry_forward - ($row['days_duration']*1));
                                                $carry_forward -= ($row['days_duration']*1);
                                                $days_entitled = $balance >= 0 ? $days_entitled : ($days_entitled + $balance);
                                            }else{
                                                $days_entitled -= ($row['days_duration']*1);
                                            }
                                        }
                                    }
                                    // echo $days_entitled;
                                    $days_balance = $days_entitled;
                                }else{
                                    // get as if they were employed this year
                                    // get the days accrued from when term one started
                                    $today = date("Y-m-d");
                                    $months_diff = differenceDatesMonth($doe,$today);
                                    $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                    // $leave_days_entitled = $week_diff * $weekly_accrual;
                                    $days_entitled = $week_diff * $weekly_accrual;
                                    // echo $days_accrued;

                                    // get the days that the user has used to get the balance remaining
                                    $days_used = 0;
                                    // get days used by the user
                                    $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND (`status` = '1'  OR  `status` = '0')";
                                    $stmt = $conn2->prepare($select);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result) {
                                        if ($row = $result->fetch_assoc()) {
                                            $days_used = $row['Total'];
                                        }
                                    }
                                    // get balance
                                    $days_entitled -= $days_used;
                                    $days_balance = $days_entitled;
                                }
                            }elseif ($doe > $term_one_start[0]) {
                                // get as if they were employed this year
                                // get the days accrued from when term one started
                                $today = date("Y-m-d");
                                $months_diff = differenceDatesMonth($doe,$today);
                                $week_diff = differenceDatesWeek($term_one_start[0],$today)-1;
                                // $leave_days_entitled = $week_diff * $weekly_accrual;
                                $days_entitled = $week_diff * $weekly_accrual;
                                // echo $days_accrued;

                                // get the days that the user has used to get the balance remaining
                                $days_used = 0;
                                // get days used by the user
                                $select = "SELECT SUM(`days_duration`) AS 'Total' FROM `apply_leave` WHERE `leave_category` = '".$leave_id."' AND `employee_id` = '".$_SESSION['userids']."' AND (`date_applied` BETWEEN '".$doe."' AND '".$today."') AND `status` = '1'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $days_used = $row['Total'];
                                    }
                                }
                                // get balance
                                $days_entitled -= $days_used;
                                $days_balance = $days_entitled;
                            }
                            // ends here
                        }
                    }
                }
            }
        }
        return $days_balance;
    }
    function checkDate_Holiday($date,$working_days){
        // get the working days of the week
        $month_dates = date("m",strtotime($date));
        $days = date("d",strtotime($date));
        $holidays = 
        [
            "Happy New Year" => "01-01",
            "Labour Day/May Day" => "01-05",
            "Madaraka Day" => "01-06",
            "Huduma Day" => "10-10",
            "Mashujaa Day" => "20-10",
            "Jamhuri Day" => "12-12",
            "Christmas Day" => "25-12",
            "Boxing Day" => "26-12"
        ];
        
        // return $data;
        $is_holiday = [];
        foreach ($holidays as $key => $value) {
            if ($value == $days."-".$month_dates) {
                $is_holiday = [$key,$value];
            }
        }
        $weekend  = [];
        $week_day = date("D",strtotime($date));
        $week_day = $week_day == "Thu"? $week_day."r" : $week_day;
        $is_present = checkPresnt($working_days,$week_day);
        if ($is_present == 0) {
            $weekend = ["Non-working Day",$days."-".$month_dates];
        }
        if (count($is_holiday) > 0 && count($weekend) > 0) {
            return 2;
        }elseif (count($is_holiday) > 0 || count($weekend) > 0) {
            return 1;
        }else{
            return 0;
        }
        return 0;

    }
    function getLeaveDetails($conn2,$leave_id){
        $select = "SELECT * FROM `leave_categories` WHERE `id` = '".$leave_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result  = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                return $row;
            }
        }
        return [];
    }
    function addDays($date,$days){
        $date = date_create($date);
        date_add($date,date_interval_create_from_date_string($days." day"));
        return date_format($date,"YmdHis");
    }

    function addMonths($date,$months){
        $date = date_create($date);
        date_add($date,date_interval_create_from_date_string($months." Month"));
        return date_format($date,"YmdHis");
    }
    function addYearsAdministration($date,$years){
        $date = date_create($date);
        date_add($date,date_interval_create_from_date_string($years." Years"));
        return date_format($date,"YmdHis");
    }
    function replaceDoubleQuotes($string) {
        $result = str_replace('"', "'", $string);
        return $result;
    }

    function log_administration($text){
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

    function readFromFile($filename) {
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            return $content;
        } else {
            return "File not found!";
        }
    }
    
    // Function to write content to a text file
    function writeToFile($filename, $content) {
        // Create directory if it doesn't exist
        $directory = dirname($filename);
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    
        // Open the file for writing
        $file = fopen($filename, 'w');
        
        if ($file) {
            fwrite($file, $content);
            fclose($file);
            return "Content written to $filename successfully!";
        } else {
            return "Unable to open file!";
        }
    }

    // get the course id when given the name
    function get_course_id($course_name, $conn2){
        // get all courses
        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $course_levels = [];
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
            }
        }

        foreach ($course_levels as $key => $value) {
            if(strtolower($course_name) == strtolower($value->course_name)){
                return $value->id;
            }
        }
        return "";
    }

    // get the course id when given the name
    function get_course_level_valid($course_level, $conn2){
        // get all courses
        $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $course_levels = [];
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $course_levels = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
            }
        }

        foreach ($course_levels as $key => $value) {
            if(strtolower($course_level) == strtolower($value->classes)){
                return $value->classes;
            }
        }
        return "";
    }
?>