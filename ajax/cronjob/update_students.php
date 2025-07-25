<?php
    // session_start();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    date_default_timezone_set('Africa/Nairobi');

    $_SESSION['databasename'] = 'lizola_college';
    include("/var/www/html/lizola_college/college_sims/connections/conn2.php");
    // include "../../connections/conn1.php";
    // include "../../connections/conn2.php";

    if($conn2){
        $course_list = [];
        $sql = "SELECT * FROM settings WHERE sett = 'courses'";
        $stmt = $conn2->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $course_list = isJson($row['valued']) ? json_decode($row['valued']) : [];
            }
        }
        

        foreach ($course_list as $key => $value) {
            $value->fulltime_fees = 9000;
            $value->evening_fees = 8500;
            $value->weekend_fees = 7500;
        }

        // update the course list
        $update = "UPDATE settings SET valued='".json_encode($course_list)."' WHERE sett = 'courses'";
        $stmt = $conn2->prepare($update);
        $stmt->execute();

        // get student data
        $students = "SELECT * FROM `student_data`";
        $stmt = $conn2->prepare($students);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                $my_course_list = isJson($row['my_course_list']) ? json_decode($row['my_course_list']): [];
                foreach ($my_course_list as $course) {
                    if ($course->course_status != 2) {
                        $course_costs = course_costs($course_list,$course->id);
                        foreach ($course->module_terms as $module_terms) {
                            unset($module_terms->termly_cost);
                            $module_terms->fulltime_cost = $course_costs['fulltime_fees'] ?? 0;
                            $module_terms->evening_cost = $course_costs['evening_fees'] ?? 0;
                            $module_terms->weekend_cost = $course_costs['weekend_fees'] ?? 0;
                        }
                    }
                }

                // update the student course list
                $update = "UPDATE student_data SET my_course_list = '".json_encode($my_course_list)."' WHERE adm_no = '".$row['adm_no']."'";
                $statement = $conn2->prepare($update);
                $statement->execute();
            }
        }
    }

    function course_costs($course_list, $course_id){
        foreach ($course_list as $key => $value) {
            if($value->id == $course_id){
                return array(
                "fulltime_fees" => $value->fulltime_fees,
                "weekend_fees" => $value->weekend_fees,
                "evening_fees" => $value->evening_fees
                );
            }
        }
    }


    function isJson($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

?>