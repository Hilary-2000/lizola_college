<?php
/**
 * These are the steps to follow when a transaction is recieved
 * 1. Check if the paybill number is registered
 * if the paybill is registered to a school pull the school database and check if the school has the student
 * admission number used  as the account number
 * 
 * IF THE PAYBILL IS REGISTERED
 * check if the student admission number used as the account number is present
 * 
 * IF THE STUDENT IS PRESENT:
 * record the transaction and the state will be assigned
 * send the parent an sms showing the student new balance and the amount they have payed
 * 
 * IF THE STUDENT IS NOT PRESENT 
 * record the transaction and the state will be unassigned
 * send the parent an sms showing that they have sent the payment the wrong account number
 * 
 * IF THE PAYBILL IS NOT REGISTERED
 * the system wont record the transaction
 */
    header("content-Type: application/json");

        //get the first database connection
        include("../../connections/conn1.php");
        
        // recieve the payment from safaricom
        $response = '{
         "ResultCode":0,
         "ResultDesc": "Confirmation Recieved Successfully"
         }';
        //  echo $response;

         //data
		// $mpesaResponse = file_get_contents('php://input');
        $mpesaResponse = "{
            \"TransactionType\": \"Pay Bill\",
            \"TransID\": \"PLR0QR0V56\",
            \"TransTime\": \"20220118121323\",
            \"TransAmount\": \"600.00\",
            \"BusinessShortCode\": \"4061913\",
            \"BillRefNumber\": \"54\",
            \"InvoiceNumber\": \"\",
            \"OrgAccountBalance\": \"5.00\",
            \"ThirdPartyTransID\": \"\",
            \"MSISDN\": \"254743551250\",
            \"FirstName\": \"OWEN\",
            \"MiddleName\": \"MALINGU\",
            \"LastName\": \"ADALA\" }";
            // echo $mpesaResponse;
         $logFile = "M_PESAConfimationResponse.txt";
         $jsonMpesaResponse = json_decode($mpesaResponse, true);

         //write to file
        //  $log = fopen($logFile, "a");

        //  fwrite($log, $mpesaResponse);
        //  fclose($log);
         //check if the statement has the transaction id 
         

         if (isset($jsonMpesaResponse['TransID'])) {
            //  check if the paybill used is present in the database
             $select = "SELECT `database_name`,`school_name`,`school_contact` FROM `school_information` WHERE `paybill` = ?";
             $stmt = $conn->prepare($select);
             $stmt->bind_param("s",$jsonMpesaResponse['BusinessShortCode']);
             $stmt->execute();
             $result = $stmt->get_result();
             if ($result) {
                 if ($row = $result->fetch_assoc()) {
                    $dbnamed = $row['database_name'];
                    $schoolName = $row['school_name'];
                    $school_contact = $row['school_contact'];
                 }
             }
             if (isset($dbnamed)) {
                //  proceed and record the student payment information
                // set the database name
                include("../../connections/mpesaConn.php");
                include("financial.php");
                if ($conn2) {
                    // get the students information
                    $studentName = "Null";
                    $select = "SELECT `first_name`,`second_name`,`surname` FROM `student_data` WHERE `adm_no` = ?";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$jsonMpesaResponse['BillRefNumber']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $studentName = $row['first_name']." ".$row['second_name']." ".$row['surname'];
                            // get the students balance
                            $term = getTerm($conn2);
                            $studentBalance = getBalance($jsonMpesaResponse['BillRefNumber'],$term,$conn2);
                            $newBalance = $studentBalance-$jsonMpesaResponse['TransAmount'];
                            // insert the payments for the student
                            $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`) VALUES (?,?,?,?,?,?,?,?,?)";
                            $stmt = $conn2->prepare($insert);
                            $stud_admin = $jsonMpesaResponse['BillRefNumber'];
                            $transactionCode = $jsonMpesaResponse['TransID'];
                            $TransAmount = $jsonMpesaResponse['TransAmount']*1;
                            $time = date("H:i:s", strtotime("3 hour"));
                            $date = date("Y-m-d");
                            $paymentFor = "Tuition";
                            $paidBy = "mpesa";
                            $stmt->bind_param("sssssssss",$stud_admin,$time,$date,$transactionCode,$TransAmount,$newBalance,$paymentFor,$paidBy,$paidBy);
                            $stmt->execute();

                            // RECORD MPESA TRANSACTIONS
                            $fullnames = $jsonMpesaResponse['FirstName']." ".$jsonMpesaResponse['MiddleName']." ".$jsonMpesaResponse['LastName'];
                            $trans_status = "1";
                            recordMpesaTrans($conn2,$transactionCode,$TransAmount,$stud_admin,$jsonMpesaResponse['TransTime'],$jsonMpesaResponse['BusinessShortCode'],$jsonMpesaResponse['MSISDN'],$fullnames,$trans_status);
                            // send the parent an sms telling them the transaction was successfull with the balance
                            include("../../sms_apis/sms.php");
                            // include("../../comma.php");
                            $phone_number = $jsonMpesaResponse['MSISDN'];
                            $message = "Confirmed Kes ".comma($TransAmount)." has been successfully paid to ".$studentName.".New Fees balance is ".$newBalance." as at ".$time." on ".$date.".";
                            // echo $message;
                            $dont_send = 0;
                            $parentPhone = getPhoneNumber($conn2,$stud_admin);
                            if (substr($phone_number,3,strlen($phone_number)) == substr($parentPhone,1,strlen($parentPhone)))  {
                                // send the parent an sms and the one making the payment
                                $dont_send = 1;
                            }
                            $api_key = getApiKey($conn2);
                            //check if the school has its own api keys
                            $school = 1;
                            if ($api_key == 0) {
                                $school = 0;
                                $api_key = getApiKey($conn);
                            }
                            // echo $api_key;
                            if ($api_key !== 0) {
                                    if ($school == 0) {
                                        $partnerID = getPatnerId($conn);
                                        $shortcodes = getShortCode($conn);
                                        $send_sms_url = getUrl($conn);
                                    }else {
                                        $partnerID = getPatnerId($conn2);
                                        $shortcodes = getShortCode($conn2);
                                        $send_sms_url = getUrl($conn2);
                                    }
                                //send sms
                                $response = sendSmsToClient($phone_number,$message,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                if ($dont_send == 0) {
                                    $response = sendSmsToClient($parentPhone,$message,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                }
                                $decoded = json_decode($response);
                                if (isset($decoded->{'message'})) {
                                    // echo $decoded->{'message'};
                                }elseif (isset($decoded->{'response-description'})) {
                                    // echo $decoded->{'response-description'};
                                }else {
                                    //recorded the sms information to the sms server
                                    //$select = "INSERT INTO `sms_table` (`message_count`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`) VALUES (?,?,?,?,?,?,?)";
                                    $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_undelivered`,`message_sent_succesfully`,`message_type`,`sender_no`,`message_description`,`message`) VALUES (?,?,?,?,?,?,?,?)";
                                    $message_type = "Multicast";
                                    $message_count = "1";
                                    $recipient_no = $phone_number;
                                    $text_message = $message;
                                    $message_desc = substr($message,0,45)."...";
                                    $stmt = $conn2->prepare($select);
                                    $date = date("Y-m-d", strtotime("3 hour"));
                                    $stmt->bind_param("ssssssss",$message_count,$date,$message_count,$message_count,$message_type,$recipient_no,$message_desc,$text_message);
                                    $stmt->execute();
                                }
                            }
                        }else {
                            $TransAmount = $jsonMpesaResponse['TransAmount']*1;
                            include("../../sms_apis/sms.php");
                            // include("../../comma.php");
                            $message = "Confirmed Kes ".comma($TransAmount)." has successfully been recieved by ".$schoolName.".The admission number you gave was not valid. Visit us or call ".$school_contact.".";
                            // record transactions
                            $fullnames = $jsonMpesaResponse['FirstName']." ".$jsonMpesaResponse['MiddleName']." ".$jsonMpesaResponse['LastName'];
                            $trans_status = "0";
                            recordMpesaTrans($conn2,$jsonMpesaResponse['TransID'],$jsonMpesaResponse['TransAmount'],$jsonMpesaResponse['BillRefNumber'],$jsonMpesaResponse['TransTime'],$jsonMpesaResponse['BusinessShortCode'],$jsonMpesaResponse['MSISDN'],$fullnames,$trans_status);
                            $phone_number = $jsonMpesaResponse['MSISDN'];
                            $api_key = getApiKey($conn2);
                            //check if the school has its own api keys
                            $school = 1;
                            if ($api_key == 0) {
                                $school = 0;
                                $api_key = getApiKey($conn);
                            }
                            // echo $api_key;
                            if ($api_key !== 0) {
                                if ($school == 0) {
                                    $partnerID = getPatnerId($conn);
                                    $shortcodes = getShortCode($conn);
                                    $send_sms_url = getUrl($conn);
                                }else {
                                    $partnerID = getPatnerId($conn2);
                                    $shortcodes = getShortCode($conn2);
                                    $send_sms_url = getUrl($conn2);
                                }
                                //send sms
                                $response = sendSmsToClient($phone_number,$message,$api_key,$partnerID,$shortcodes,$send_sms_url);
                                $decoded = json_decode($response);
                                if (isset($decoded->{'message'})) {
                                    // echo $decoded->{'message'};
                                }elseif (isset($decoded->{'response-description'})) {
                                    // echo $decoded->{'response-description'};
                                }else {
                                    //recorded the sms information to the sms server
                                    //$select = "INSERT INTO `sms_table` (`message_count`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`message_description`,`sender_no`,`message`) VALUES (?,?,?,?,?,?,?)";
                                    $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_undelivered`,`message_sent_succesfully`,`message_type`,`sender_no`,`message_description`,`message`) VALUES (?,?,?,?,?,?,?,?)";
                                    $message_type = "Multicast";
                                    $message_count = "1";
                                    $recipient_no = $phone_number;
                                    $text_message = $message;
                                    $message_desc = substr($message,0,45)."...";
                                    $stmt = $conn2->prepare($select);
                                    $date = date("Y-m-d", strtotime("3 hour"));
                                    $stmt->bind_param("ssssssss",$message_count,$date,$message_count,$message_count,$message_type,$recipient_no,$message_desc,$text_message);
                                    $stmt->execute();
                                }
                            }
                        }
                    }
                }else {
                    // echo "No connection";
                }
             }else {
                //  echo "Database connected to that paybill is not found";
             }
         }
         
        function recordMpesaTrans($conn2,$mpesa_id,$amount,$std_adm,$trans_time,$shortcode,$MSIND,$fullnames,$trans_status){
        // RECORD MPESA TRANSACTION
            $insert = "INSERT INTO `mpesa_transactions` (`mpesa_id`,`amount`,`std_adm`,`transaction_time`,`short_code`,`payment_number`,`fullname`,`transaction_status`) VALUES ('$mpesa_id','$amount','$std_adm','$trans_time','$shortcode','$MSIND','$fullnames','$trans_status')";
            $stmt = $conn2->prepare($insert);
        //  $stmt->bind_param("ssssssss",$mpesa_id,$amount,$std_adm,$trans_time,$shortcode,$MSIND,$fullnames,$trans_status);
            if($stmt->execute()){
            //  echo "executed";
            }else{
            //  echo "Not executed!";
            }
        }
    // function getBalance($admno,$term,$conn2){
    //     //get the fee balance from the latest transaction record if not found then calculate how much the students is to pay
    //     $lastbal = lastBalance($admno,$conn2);
    //     $check_recent_boarding = checkNewlyBoard($admno,$conn2);
    //     if ($lastbal > 0 && !$check_recent_boarding) {
    //         return $lastbal;
    //     }else {
    //         $balance = calculatedBalance($admno,$term,$conn2);
    //         return $balance; 
    //     }
    // }
    // function calculatedBalance($admno,$term,$conn2){
    //     $daro = getName($admno,$conn2);
    //     $getclass = explode("^",$daro);
    //     $dach = $getclass[1];
    //     $feestopay = getFeesAsPerTermBoarders($term,$conn2,$dach,$admno);
    //     $feespaidbystud = getFeespaidByStudent($admno,$conn2);
    //     $balance = 0;
    //     if ($feestopay>$feespaidbystud) {
    //         $balance = $feestopay - $feespaidbystud;
    //     }elseif ($feestopay<$feespaidbystud) {
    //         $balance = $feestopay - $feespaidbystud;
    //     }else {
    //         $balance = 0;
    //     }
    //     $balance += lastACADyrBal($admno,$conn2);
    //     return $balance;
    // }
    // function getFeespaidByStudent($admno,$conn2){
    //     $select = "SELECT sum(amount) AS 'TOTAL' FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND `payment_for` != 'admission fees'";
    //     $stmt = $conn2->prepare($select);
    //     $beginyear = getAcademicStart($conn2);//start date of the academic year
    //     $currentdate = date("Y-m-d", strtotime("3 hour"));
    //     $stmt->bind_param("sss",$admno,$beginyear,$currentdate);
    //     $stmt->execute();
    //     $res = $stmt->get_result();
    //     if($res){
    //         if($row = $res->fetch_assoc()){
    //             if (isset($row['TOTAL'])) {
    //                 $total_pay = $row['TOTAL'];
    //                 $class = explode("^",getClass($admno))[1];
    //                 $prov_roles = getProvisionalRole($class,$conn2);
    //                 $prov_amount = provisionalPays($admno,$conn2,$prov_roles,$beginyear);
    //                 $total_pay = $total_pay-$prov_amount;
    //                 return $total_pay;
    //             }else{
    //                 return 0;
    //             }
    //         }else{
    //             return 0;
    //         }
    //     }else {
    //         return 0;
    //     }
    //     return 0;
    // }
    // function lastACADyrBal($admno,$conn2){
    //     $select = "SELECT `balance` FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` < ? ORDER BY `transaction_id` DESC LIMIT 1;";
    //     $stmt = $conn2->prepare($select);
    //     $beginyear = getAcademicStart($conn2);
    //     $stmt->bind_param("ss",$admno,$beginyear);
    //     $stmt->execute();
    //     $balance = 0;
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             if (isset($row['balance'])) {
    //                 $balance = $row['balance'];
    //             }
    //         }
    //     }
    //     return $balance;
    // }
    // function provisionalPays($admno,$conn2,$prov_pays,$beginyear){
    //     $provisional_amount = 0;
    //     if (count($prov_pays) > 0) {
    //         for ($i=0; $i < count($prov_pays); $i++) {
    //             $select = "SELECT sum(amount) AS 'TOTAL' FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND  `payment_for` = ?;";
    //             $stmt = $conn2->prepare($select);
    //             $today = date("Y-m-d");
    //             $stmt->bind_param("ssss",$admno,$beginyear,$today,$prov_pays[$i]);
    //             $stmt->execute();
    //             $result = $stmt->get_result();
    //             if ($result) {
    //                 if ($row = $result->fetch_assoc()) {
    //                     $provisional_amount+=($row['TOTAL']*1);
    //                 }
    //             }
    //         }
    //     }
    //     return $provisional_amount;
    // }
    // function getProvisionalRole($stud_class,$conn2){
    //     $class = "%|".$stud_class."|%";
    //     $select = "SELECT `expenses` FROM `fees_structure` WHERE `roles` = 'boarding' AND  `classes` LIKE ?";
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$class);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $roles = "";
    //     $roles_arr = [];
    //     if ($result) {
    //         while($row = $result->fetch_assoc()){
    //             $roles.=$row['expenses'].",";
    //         }
    //     }
    //     if (strlen($roles) > 0) {
    //         $roles = substr($roles,0,(strlen($roles)-1));
    //         $roles_arr = explode(",",$roles);
    //     }
    //     return $roles_arr;
    // }
    // function getAcademicStart($conn2){
    //     $select = "SELECT `start_time` FROM `academic_calendar` WHERE `term` = 'TERM_1';";
    //     $stmt =$conn2->prepare($select);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['start_time'];
    //         }
    //     }
    //     return date('Y')."-01-01";
    // }
    // function getFeesAsPerTermBoarders($term,$conn2,$classes,$admno){
    //     $select = '';
    //     $class = "%|".$classes."|%";
    //     if($term == "TERM_1"){
    //         $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'boarding';";
    //     }elseif($term == "TERM_2"){
    //         $select = "SELECT sum(`TERM_1`)+sum(TERM_2) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'boarding';";
    //     }elseif($term == "TERM_3"){
    //         $select = "SELECT sum(`TERM_1`)+sum(TERM_2)+sum(`TERM_3`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'boarding';";
    //     }
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$class);
    //     $stmt->execute();
    //     $res = $stmt->get_result();
    //     if($res){
    //         if ($row = $res->fetch_assoc()) {
    //             $fees_to_pay = $row['TOTALS'];
    //             if (isBoarding($admno,$conn2)) {
    //                 $boarding_fees = getBoardingFees($conn2,$classes);
    //                 return $fees_to_pay+$boarding_fees;
    //             }
    //             return $fees_to_pay;
    //         }else{
    //             return 0;
    //         }
    //     }
    //     return 0;
    //     $stmt->close();
    // }
    // function getName($admno,$conn2){
    //     $select = "SELECT concat(`first_name`,' ',`second_name`) AS `Names`, `stud_class` FROM `student_data` where `adm_no` = ?";
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$admno);
    //     $stmt->execute();
    //     $results = $stmt->get_result();
    //     if($results){
    //         $xs =0;
    //         $name = '';
    //         while ($row=$results->fetch_assoc()) {
    //             $xs++;
    //             $name = $row['Names']."^".$row['stud_class'];
    //         }
    //         if($xs!=0){
    //             return $name;
    //         }else{
    //             return "null";
    //         }
    //     }else {
    //         return "null";
    //     }
        
    // }
    // function lastBalance($admno,$conn2){
    //     $select = "SELECT `balance` FROM `finance` WHERE `stud_admin` = ? ORDER BY `transaction_id` DESC LIMIT 1";
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$admno);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['balance'];
    //         }
    //     }
    //     return 0;
    // }
    // function getTerm($conn2){
    //     $date = date("Y-m-d");
    //     $select = "SELECT `term` FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
    //     $stmt= $conn2->prepare($select);
    //     $stmt->bind_param("ss",$date,$date);
    //     $stmt->execute();
    //     $results = $stmt->get_result();
    //     if($results){
    //         if ($rowed = $results->fetch_assoc()) {
    //           $term = $rowed['term'];
    //           return $term;
    //         }else {
    //           return "TERM_1";
    //         }
    //     }else {
    //         return "TERM_1";
    //       }
        
    //     $stmt->close();
    // }
    // function checkNewlyBoard($admno,$conn2){
    //     $select = "SELECT * FROM `boarding_list` WHERE `date_of_enrollment` > ?  and `student_id` = ?";
    //     $stmt = $conn2->prepare($select);
    //     $date = date("Y-m-d", strtotime("-719 hour"));
    //     $stmt->bind_param("ss",$date,$admno);
    //     $stmt->execute();
    //     $stmt->store_result();
    //     $rnums = $stmt->num_rows;
    //     if ($rnums > 0) {
    //         return true;
    //     }else {
    //         return false;
    //     }
    // }
    // function isBoarding($admno,$conn2){
    //     $select = "SELECT * FROM `boarding_list` WHERE `student_id` = ?";
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$admno);
    //     $stmt->execute();
    //     $stmt->store_result();
    //     $rnums = $stmt->num_rows;
    //     if ($rnums > 0) {
    //         return true;
    //     }
    //     return false;
    // }
    // function getBoardingFees($conn2,$class){
    //     $class = "%|".$class."|%";
    //     $term = getTerm($conn2);
    //     $select = "";
    //     if ($term == "TERM_1") {
    //         $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
    //     }elseif ($term == "TERM_2") {
    //         $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
    //     }elseif ($term == "TERM_3") {
    //         $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
    //     }
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$class);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['Total'];
    //         }
    //     }
    //     return 0;
    // }
    // function getApiKey($conn){
    //     $select = "SELECT `sms_api_key` FROM `sms_api`";
    //     $stmt = $conn->prepare($select);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['sms_api_key'];
    //         }
    //     }
    //     return 0;
    // }
    // function getPatnerId($conn){
    //     $select = "SELECT `patner_id` FROM `sms_api`";
    //     $stmt = $conn->prepare($select);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['patner_id'];
    //         }
    //     }
    //     return 0;
    // }
    // function getShortCode($conn){
    //     $select = "SELECT `short_code` FROM `sms_api`";
    //     $stmt = $conn->prepare($select);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['short_code'];
    //         }
    //     }
    //     return 0;
    // }
    // function getPhoneNumber($conn2,$stud_id){
    //     $select = "SELECT `parentContacts` FROM `student_data` WHERE `adm_no` = ?";
    //     $stmt = $conn2->prepare($select);
    //     $stmt->bind_param("s",$stud_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     if ($result) {
    //         if ($row = $result->fetch_assoc()) {
    //             return $row['parentContacts'];
    //         }
    //     }
    //     return 0;
    // }