<?php

if(session_status()==PHP_SESSION_NONE){
    //session is not started
    session_start();
}
date_default_timezone_set('Africa/Nairobi');

$databases = ['nuelas_college', 'lizola_college'];
foreach ($databases as $database) {
    // $_SESSION['databasename'] = $database;
    // include_once("/var/www/html/lizola_college/college_sims/ajax/finance/financial.php");
    // include_once("/var/www/html/lizola_college/college_sims/connections/module_stepper_conn.php");
    include_once("../../connections/module_stepper_conn.php");
    include_once("../../ajax/finance/financial.php");
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

                // student_course
                $student_course = isJson($row['my_course_list']) ? json_decode($row['my_course_list']) : [];

                // check if its to be updated or not.
                $stepped = false;
                foreach ($student_course as $key => $course) {
                    if ($course->course_status == 1) {
                        $modules = $course->module_terms;
                        foreach ($modules as $key_mod => $value) {
                            if($value->status == 1 && date("Ymd") >= date("Ymd", strtotime($value->end_date))){
                                // echo $row['first_name']." - ".$row['second_name']." ".$row['adm_no']." < = > ".$value->status." ".date("Ymd", strtotime($value->end_date))." ".(isset($modules[$key_mod+1]) ? "true" : "false")."<br>";
                                if(!$stepped){
                                    $student_course[$key]->module_terms[$key_mod]->status = 2;
                                    if ($key_mod < count($modules)-1) {
                                        $student_course[$key]->module_terms[$key_mod+1]->status = 1;
                                        $student_course[$key]->module_terms[$key_mod+1]->start_date = date("YmdHis");
                                        $student_course[$key]->module_terms[$key_mod+1]->end_date = date("YmdHis", strtotime($course_duration));

                                        // add the balance to the student
                                        $term = "TERM_1";
                                        $update = "UPDATE student_data SET balance_carry_forward = ?, my_course_list = ? WHERE adm_no = ?";
                                        $stmt = $conn2->prepare($update);
                                        $student_balance = getBalanceReports($row['adm_no'], $term, $conn2);
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
}
?>