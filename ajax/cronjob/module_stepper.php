<?php

if(session_status()==PHP_SESSION_NONE){
    //session is not started
    session_start();
}
date_default_timezone_set('Africa/Nairobi');

$_SESSION['databasename'] = 'lizola_college';
// include("../../connections/conn2.php");
include("/var/www/html/lizola_college/college_sims/connections/conn2.php");
if ($conn2) {
    // include("../finance/financial.php");
    // GET ALL COURSE
    $select = "SELECT * FROM `settings` WHERE `sett` = 'courses';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $course_list = [];
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $course_list = isJson($row['valued']) ? json_decode($row['valued']) : [];
        }
    }


    $select = "SELECT * FROM student_data WHERE course_progress_status = '1'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $course_done = $row['course_done'];
            $course_duration = "0 Days";
            foreach ($course_list as $key => $value) {
                if ($value->id == $course_done) {
                    $course_duration = $value->term_duration." ".$value->duration_intervals;
                }
            }

            // student course
            $student_course = isJson($row['my_course_list']) ? json_decode($row['my_course_list']) : [];

            // check if its to be updated or not.
            $stepped = false;
            foreach ($student_course as $key => $course) {
                if ($course->course_status == 1) {
                    $modules = $course->module_terms;
                    foreach ($modules as $key_mod => $value) {
                        // echo $value->status." ".date("Ymd", strtotime($value->end_date))." ".(isset($modules[$key_mod+1]) ? "true" : "false")."<br>";
                        if($value->status == 1 && date("Ymd") >= date("Ymd", strtotime($value->end_date))){
                            if(!$stepped){
                                $student_course[$key]->module_terms[$key_mod]->status = 2;
                                if ($key_mod < count($modules)-1) {
                                    $student_course[$key]->module_terms[$key_mod+1]->status = 1;
                                    $student_course[$key]->module_terms[$key_mod+1]->start_date = date("YmdHis");
                                    $student_course[$key]->module_terms[$key_mod+1]->end_date = date("YmdHis", strtotime($course_duration));

                                    // add the balance to the student
                                    $term = "TERM_1";
                                    $student_balance = getBalanceReports($row['adm_no'], $term, $conn2);
                                    $update = "UPDATE student_data SET balance_carry_forward = ?, my_course_list = ? WHERE adm_no = ?";
                                    $stmt = $conn2->prepare($update);
                                    $student_course = json_encode($student_course);
                                    $stmt->bind_param("sss", $student_balance, $student_course, $row['adm_no']);
                                    $stmt->execute();
                                    $stepped = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

function getBalanceReports($admno,$term,$conn2){
    $balance = calculatedBalanceReport($admno,$term,$conn2);
    return $balance;
}
function students_details($admno,$conn2){
    $select = "SELECT * FROM `student_data` WHERE `adm_no` = ? LIMIT 1";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s",$admno);
    $stmt->execute();
    $stmt->store_result();
    $rnums = $stmt->num_rows;
    if($rnums>0){
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return [];
}
function getDiscount($admno,$conn2){
    $select = "SELECT * FROM `student_data` WHERE `adm_no` = '".$admno."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return [($row['discount_percentage']*1),($row['discount_value']*1)];
        }
    }
    return [0,0];
}
function getFeesAsFromTermAdmited($current_term,$conn2,$classes,$admno){
    // get the student term they are in
    $student_data = students_details($admno,$conn2);
    
    // GET THE COURSE FEES
    $course_fees = 0;
    $active_course = false;
    $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
    for($index = 0; $index < count($my_course_list); $index++){
        if($my_course_list[$index]->course_status == 1){
            // module terms
            $module_terms = $my_course_list[$index]->module_terms;
            for ($ind=0; $ind < count($module_terms); $ind++) {
                if($module_terms[$ind]->status == 1){
                    $course_fees = $module_terms[$ind]->termly_cost;
                    $active_course = true;
                    break;
                }
            }
        }
    }
    // echo json_encode($my_course_list[0]);

    // GET THE STUDENT STANDING BALANCE.
    $student_balance = $student_data['balance_carry_forward'];

    // FEES STRUCTURE FEES
    $fees_structure = 0;

    if($active_course){
        $class = "".$classes."";
        $course_enrolled = $student_data['course_done'];

        // get the term they are in
        $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` = ? AND `course` = ? AND `activated` = 1  and `roles` = 'regular';";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$class,$course_enrolled);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                $fees_structure = $row['TOTALS'];
            }
        }
        $stmt->close();

        // add fees structure fees to course fees
        $fees_structure += $course_fees;
                
        // get dicounts
        $discounts = getDiscount($admno,$conn2);
        if ($discounts[0] > 0 || $discounts[1] > 0) {
            if ($discounts[0] > 0) {
                $discounts = 100 - $discounts[0];
                $fees_structure = round(($fees_structure * $discounts) / 100);
            }else{;
                $fees_structure = $fees_structure - $discounts[1];
            }
        }
    }

    // add student balance carry forward
    $fees_structure += $student_balance;
    
    return $fees_structure;
}
function calculatedBalanceReport($admno,$term,$conn2){
    $daro = getNameReport($admno,$conn2);
    $getclass = explode("^",$daro);
    $dach = $getclass[1];
    $feestopay = getFeesAsFromTermAdmited($term,$conn2,$dach,$admno);
    $feespaidbystud = getFeespaidByStudent($admno,$conn2);

    // get balance
    $balance = $feestopay - $feespaidbystud;

    // return balance
    return $balance;
}

function isJson($string)
{
    return ((is_string($string) &&
        (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}
function getFeespaidByStudent($admno,$conn2){
    // get the student details
    $student_data = students_details($admno,$conn2);
    
    // get the current term so that we start counting from there
    $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d");
    for ($index=0; $index < count($my_course_list); $index++) { 
        if($my_course_list[$index]->course_status == 1){
            $module_terms = $my_course_list[$index]->module_terms;
            for($ind = 0; $ind < count($module_terms); $ind++){
                if($module_terms[$ind]->status == 1){
                    $start_date = date("Y-m-d", strtotime($module_terms[$ind]->start_date));
                    $end_date = date("Y-m-d", strtotime($module_terms[$ind]->end_date));
                    break;
                }
            }
        }
    }
    
    // $select = "SELECT * FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND `payment_for` != 'admission fees'";
    $select = "SELECT * FROM `finance` WHERE finance.stud_admin = ? AND finance.date_of_transaction BETWEEN ? and ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("sss",$admno,$start_date,$end_date);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res){
        $total_amounts = 0;
        while($row = $res->fetch_assoc()){
            $payment_for = isJson($row['payment_for']) ? json_decode($row['payment_for'], true) : [];
            if (count($payment_for) > 0) {
                foreach ($payment_for as $key => $payment) {
                    if($payment['roles'] != "provisional"){
                        $total_amounts += ($payment['amount_paid']*1);
                    }
                }
            }else{
                $total_amounts += ($row['amount']*1);
            }
        }
        return $total_amounts;
    }
    return 0;
}
function getNameReport($admno,$conn2){
    // include_once("../../sims/ajax/finance/financial.php");
    $select = "SELECT concat(`first_name`,' ',`second_name`) AS `Names`, `stud_class` FROM `student_data` where `adm_no` = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s",$admno);
    $stmt->execute();
    $results = $stmt->get_result();
    if($results){
        $xs =0;
        $name = '';
        while ($row=$results->fetch_assoc()) {
            $xs++;
            $name = $row['Names']."^".$row['stud_class'];
        }
        if($xs!=0){
            return $name;
        }else{
            return "null";
        }
    }else {
        return "null";
    }
    
    $stmt->close();
    // $conn2->close();
}
?>