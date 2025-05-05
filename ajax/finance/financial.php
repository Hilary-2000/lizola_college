<?php
    // session_start();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    date_default_timezone_set('Africa/Nairobi');
    if ($_SERVER['REQUEST_METHOD'] =='GET') {
        include("../../connections/conn1.php");
        include("../../connections/conn2.php");
        include("../../comma.php");
        if (isset($_GET['payfordetails'])) {
            $class = "";
            $course_value = isset($_GET['course_value']) ? $_GET['course_value'] : "0";
            if (isset($_GET['class_use'])) {
                // object id
                $object_id = "payfor";
                if (isset($_GET['object_id'])) {
                    $object_id = $_GET['object_id'];
                }
                $class = "".$_GET['class_use']."";
                $student_admission = $_GET['student_admission'];
                if ($_GET['class_use'] == "-1" || $_GET['class_use'] == "-2") {
                    $select = "<select class='payments_options' id='$object_id'><option value='' hidden>Select option..</option>";
                    $select.="<option value='balance'>Balance</option>";
                    $select.="</select>";
                    echo $select;
                }else{
                    $select = "SELECT * FROM `fees_structure` WHERE `course` = '".$course_value."' AND `classes` = ? and `activated` = 1";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$class);
                    $stmt->execute();
                    $results = $stmt->get_result();
                    $select = "<p style='color:green;'>There is no payment option set by the administrator</p>";
                    if($results){
                        $select = "<select class='payments_options' id='$object_id'><option value='' hidden>Select option..</option>";
                        $xs = 0;
                        $pup = array();
                        while ($row = $results->fetch_assoc()) {
                            $xs++;
                            $in = 0;
                            //first check if the array is present
                            for ($i=0; $i < count($pup); $i++) { 
                                if($pup[$i]== $row['expenses']){
                                    $in=1;
                                }
                            }
                            if ($in==0) {
                                array_push($pup,$row['expenses']);
                                $select.="<option value='".$row['expenses']."'>".ucwords(strtolower($row['expenses']))."</option>";
                            }
                        }
                            
                        if (isTransport($conn2,$student_admission) == true) {
                            $termed = getTermV2($conn2);
                            $get_route = routeName($conn2,$student_admission,$termed);
                            $select.="<option value='Transport :".ucwords(strtolower($get_route[0]))."'><b>Transport</b> : ".ucwords(strtolower($get_route[0]))." @ Kes ".number_format($get_route[1])."</option>";
                        }
                        $select.="</select>";
                        if($xs>0){
                            echo $select;
                        }else {
                            echo "<p style='color:green;'>There is no payment option set by the administrator</p>";
                        }
                    }else {
                        echo "<p style='color:green;'>There is no payment option set by the administrator</p>";
                    }
                }
            }else {
                echo "<p style='color:green;'>Display a student with their admission number to display their available votehead!</p>";
            }
        }elseif(isset($_GET['dispose_asset'])){
            $asset_id = $_GET['asset_id'];
            $set_disposed_date = $_GET['set_disposed_date'];
            $dispose_value = $_GET['dispose_value'];
            $select = "SELECT * FROM `asset_table` WHERE `asset_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s", $_GET['asset_id']);
            $stmt->execute();
            
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    echo date("Y",strtotime($row['date_of_acquiry'])) ." == ". date("Y",strtotime($set_disposed_date));
                    if(date("Y",strtotime($row['date_of_acquiry'])) <= date("Y",strtotime($set_disposed_date))){
                        // if the asset if found update the dispose date and the dispose status
                        $update = "UPDATE `asset_table` SET `disposed_on` = ?, `disposed_status` = ?, `disposed_value` = ? WHERE `asset_id` = ?";
                        $stmt = $conn2->prepare($update);
                        $dispose_status = 1;
                        $disposed_on = date("Ymd",strtotime($set_disposed_date)).date("His"); // now
                        $stmt->bind_param("ssss", $disposed_on, $dispose_status, $dispose_value, $asset_id);
                        $stmt->execute();
                        echo "<p class='text-success' id='asset-dispose-success'>The asset (".ucwords(strtolower($row['asset_name'])).") has been successfully disposed!</p>";
                        return 0;
                    }else{
                        echo "<p class='text-danger'>The dispose date can`t be earlier than the acquisition date!!</p>";
                        return 0;
                    }
                }
            }

            echo "<p class='text-danger'>Invalid asset!</p>";
        }elseif (isset($_GET['update_academic_balance'])) {
            // select the last taransaction made by the stsudent
            $student_admission = $_GET['student_admission'];
            $student_balance = $_GET['student_balance'];

            // get the student term they are in
            $student_data = students_details($student_admission,$conn2);
            $term_they_are_in = "TERM_1";
    
            // decode the json format
            $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
            for($index = 0; $index < count($my_course_list); $index++){
                if($my_course_list[$index]->course_status == 1){
                    // module terms
                    $module_terms = $my_course_list[$index]->module_terms;
                    for ($ind=0; $ind < count($module_terms); $ind++) {
                        if($module_terms[$ind]->status == 1){
                            $term_they_are_in = $module_terms[$ind]->term_name;
                            break;
                        }
                    }
                }
            }
            
            // get when this academic year is starting
            $SELECT = "SELECT * FROM `academic_calendar` WHERE `term` = '$term_they_are_in'";
            $stmt = $conn2->prepare($SELECT);
            $stmt->execute();
            $result = $stmt->get_result();
            $start_date = date("Y-m-d");
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $start_date = $row['start_time'];
                }
            }
            // select
            $select = "SELECT * FROM `finance` WHERE `stud_admin` = '".$student_admission."' AND `date_of_transaction` < '".$start_date."' ORDER BY `transaction_id` DESC LIMIT 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $updated = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $transaction_id = $row['transaction_id'];
                    echo "Updated successfully!";
                    $updated = 1;

                    // update the current tranasctio
                    $update = "UPDATE `finance` SET `balance` = '".$student_balance."' WHERE `stud_admin` = '".$student_admission."' AND `transaction_id`  = '$transaction_id'";
                    $stmt = $conn2->prepare($update);
                    $stmt->execute();

                    $student_data = students_details($student_admission,$conn2);
                    $log_text = (is_array($student_data) ? ucwords(strtolower($student_data['first_name']." ". $student_data['first_name'])): "N/A") . " of adm no ".$student_admission." last year academic balance has been updated successfully!";
                    log_finance($log_text);
                }
            }

            // insert if not update
            if($updated == 0){
                // add date
                $date = date_create($start_date);
                date_add($date,date_interval_create_from_date_string("-1 day"));
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
                $stmt->bind_param("sssssssssss",$student_admission,$time,$new_date,$code,$amount,$student_balance,$paymentfor,$_SESSION['userids'],$code,$status,$status);
                $stmt->execute();
                echo "<p class='text-success border border-success p-2 my-1'>Update done successfully!</p>";

                $student_data = students_details($student_admission,$conn2);
                $log_text = (is_array($student_data) ? ucwords(strtolower($student_data['first_name']." ". $student_data['second_name'])): "N/A") . " of adm no (".$student_admission.") last year academic balance has been updated successfully!";
                log_finance($log_text);
            }
        }elseif (isset($_GET['findadmno'])) {
            $admnos = $_GET['findadmno'];
            $admnopresent = checkadmno($admnos);
            if ($admnopresent==1) {
                $last_paying = getLastTimePaying($conn2,$admnos);
                $names = getName($admnos);
                $term = getTermV2($conn2);
                $classes = explode("^",$names)[1];
                $added_fees = checkFeesChange($term,$conn2,$classes,$last_paying);
                $transport_change = changeTransport($conn2,$admnos);
                $fees_change = "";
                if (strlen($added_fees) > 0) {
                    $fees_change = "<hr><span class='text-primary'>We have noticed fees structure has been changed below are the changes:".$added_fees."</span><br>";
                }
                if (strlen($transport_change) > 0) {
                    $fees_change.=$transport_change."<br>";
                }
                $name = explode("^",$names)[0];
                $date = date("Y-m-d");
                $times = date("H:i:s");
                $balance = getBalance($admnos,$term,$conn2);
                $student_data = students_details($admnos,$conn2);
                $select = "SELECT `stud_admin` , `transaction_id`, `status`, `transaction_code`, `mode_of_pay` , (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' ,  `date_of_transaction` , `time_of_transaction` , `amount` , `balance`, `payment_for` FROM `finance` WHERE `stud_admin` = ? ORDER BY `transaction_id` DESC LIMIT 5 ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$admnos);
                $stmt->execute();
                $results = $stmt->get_result();
                $date = date("l dS \of M Y",strtotime($date));
                if($results){
                    $xss =0;
                    $boarding = "";
                    if (isBoarding($admnos,$conn2) && ($classes != "-2" && $classes != "-1")) {
                        $boarding = "<span class='green_notice'> -(boarder)- </span>";
                    }
                    $transporter = "";
                    if (isTransport($conn2,$admnos) && ($classes != "-2" && $classes != "-1")) {
                        $transporter = "<span class='green_notice'> -(Transport)- </span>";
                    }
                    $daro_ss = getName($admnos);
                    $getclass = explode("^",$daro_ss)[1];
                    // when we move to a new term we will want to add the new term fees
                    $date_term_began = date("Ymd",strtotime(getTermStart($conn2,"TERM_1")));

                    $last_paid_time  = date("Ymd",strtotime(getLastTimePaying($conn2,$admnos)));
                    $term_report = "";

                    $fees_to_pay = getFeesAsFromTermAdmited($term,$conn2,$getclass,$admnos);
                    $last_academic_balance = lastACADyrBal($admnos,$conn2);
                    if ($date_term_began > $last_paid_time) {
                        $current_term = $fees_to_pay - $balance;
                        if ($last_academic_balance > 0) {
                            $current_term = $fees_to_pay;
                        }
                        $term_report = "<hr><span class='text-primary'><b>".ucwords(strtolower($name))."</b> made their last payments on ".date("dS M Y",strtotime($last_paid_time)).". The payments was made before ".$term." started, as a result Fees of Kes ".number_format($current_term)." will be added to the existing balance so the new balance will be <b>Kes ".number_format($balance)."</b></span>";
                        // echo $term_report;
                        $fees_change .= $term_report;
                    }
                    $headings= strlen($fees_change)>5 ?"<h6 class='text-center'>Notice</h6>":"";
                    
                    // dont display the fees change information at the momment
                    $headings = "";
                    $fees_change = "";
                    
                    // end of information
                    $balancecalc = getBalanceReports($admnos,$term,$conn2);
                    $fees_paid = getFeespaidByStudent($admnos,$conn2);
                    $discounts = getDiscount($admnos,$conn2);
                    $discount = $discounts[0] > 0 ? $discounts[0] : "Kes ".$discounts[1];
                    $default_student_ids = "std_names";
                    if (isset($_GET['student_name_cr'])) {
                        $default_student_ids = $_GET['student_name_cr'];
                    }

                    // get the course name
                    $course_name = "N/A";
                    $select_course = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                    $statement = $conn2->prepare($select_course);
                    $statement->execute();
                    $result = $statement->get_result();
                    if($result){
                        if($row = $result->fetch_assoc()){
                            $valued = $row['valued'];
                            $courses_list = isJson($valued) ? json_decode($valued) : [];

                            // loop through  the course
                            for ($index=0; $index < count($courses_list); $index++) { 
                                if ($courses_list[$index]->id == $student_data['course_done']) {
                                    $course_name = $courses_list[$index]->course_name;
                                }
                            }
                        }
                    }

                    // get the term the student is in
                    $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
                    $term_enrolled = "Not-Set";
                    for ($index=0; $index < count($my_course_list); $index++) { 
                        if($my_course_list[$index]->course_status == 1){
                            $module_terms = $my_course_list[$index]->module_terms;
                            for ($ind=0; $ind < count($module_terms); $ind++) { 
                                if($module_terms[$ind]->status == 1){
                                    $term_enrolled = $module_terms[$ind]->term_name;
                                    break;
                                }
                            }
                        }
                    }
                    // end of getting course name
                    // removed text details
                    // "<br> <b>Student Reg-No</b>.: <strong id = 'students_id_ddds'>" . $admnos . "</strong><br><b>Student Course Level</b> : " . className($getclass) . "<br><b>Course Enrolled</b> : " . ucwords(strtolower($course_name)) . "<br><span id=''><b>Discount</b> : " . $discount . "</span>"

                    $tableinformation1 = "<p style='text-align:center;margin-bottom:10px;'>Displaying results for <strong class='student_names' id='$default_student_ids'>" . $name . "</strong>" . $boarding . " " . $transporter . "</p>";
                    $tableinformation1.="<div class='tableme p-1'>
                                            <table class='tableme'>
                                                <tr>
                                                    <th>Student Details</th>
                                                    <th>Value</th>
                                                </tr>
                                                <tr>
                                                    <td><b>Student Reg-No</b>.</td>
                                                    <td><strong id = 'students_id_ddds'>" . $admnos . "</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Student Course Level</b></td>
                                                    <td>" . className($getclass) . "</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Course Enrolled</b></td>
                                                    <td><input hidden id='course_value_finance' value='".$student_data['course_done']."'>" . ucwords(strtolower($course_name)) . "</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Term Currently Enrolled</b></td>
                                                    <td>" . $term_enrolled . "</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Discount</b> </td>
                                                    <td>" . $discount . "</td>
                                                </tr>
                                            </table>
                                        </div><hr>";
                    $tableinformation1 .= "<p style='margin:10px 0;' >As at <b>" . $times . "</b> on <b>" . $date . "</b> <br>Current Term Enrolled: <b>" . $term_enrolled . "</b><br><span style='color:gray;' ><b>Total Fees Paid this term (without provisionals) : Kes " . number_format($fees_paid) . "</b><br><span style='color:gray;' ><b>Last Active Term balance : Kes " . number_format($last_academic_balance) . "</b><br><span style='color:gray;' ><b>Total fees to be paid as per Current Term: <b>" . $term_enrolled . "</b>: " . $fees_to_pay . "</b></span><br><span style='color:gray;'><b>System calculated balance: Ksh</b> " . $balancecalc . ".</span>" . $headings . $fees_change . "<hr><strong>Current Balance is: Ksh <span id='closed_balance'  class='queried' title='click to change the student balance'>" . $balance . "</span></strong><input type='text' value='" . $admnos . "'  id='presented' hidden></p>";
                    $tableinformation1 .= "<p class='red_notice fa-sm hide' id='read_note'>Changing of the student balance is not encouraged, its to be done only when the student is newly registered to the system or there is change in the fees structure</p><br>";
                    $tableinformation1 .= "<div class='hide' id='fee_balance_new'><input type='number' id='new_bala_ces' placeholder='Enter New Balance'> <div class='acc_rej'><p class = 'redAcc' id='accBalance'>✔</p><p class='greenRej' id='rejectBalances' >✖</p></div></div>";
                    $tableinformation = "<p>- Below are the last 5 transactions recorded or less<br>- Find all the transaction made by the student by clicking the <b>Manage transaction</b> button at the menu.</p><p id='reversehandler'></p><p style = 'font-weight:550;font-size:17px;text-align:center;'><u>Finance table</u></p>";
                    $tableinformation .= "<p class = 'hide class_studs_in'>" . explode("^", $names)[1] . "</p>";
                    // set class and fees balances
                    if (isset($_GET['class_id']) && isset($_GET['fees_bal_id'])) {
                        $tableinformation1.="<input type ='hidden' id='".$_GET['class_id']."' value='$getclass'>";
                        $tableinformation1.="<input type ='hidden' id='".$_GET['fees_bal_id']."' value='$balance'>";
                    }
                    $tableinformation .= "<div class='tableme'><table class='table'><tr>
                                        <th>No.</th>
                                        <th>Paid Amount</th>
                                        <th>D.O.P</th>
                                        <th>T.O.P</th>
                                        <th>Balance</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        </tr>
                                        ";
                                        $transaction_code = "";
                                        $modeofpay = "";
                                        $amount_recieved = "0";
                    while ($row = $results->fetch_assoc()) {
                        $statuses = $row['status'];
                        if($statuses == "0" && ($row['amount']*1) > 0){
                            if ($xss == 0) {
                                $transaction_code = $row['transaction_code'];
                                $modeofpay = $row['mode_of_pay'];
                                $amount_recieved = $row['amount'];
                            }
                            $xss++;
                            $tableinformation.="<tr><td>".$xss."</td>";
                            $tableinformation.="<td id='reverse_amount".$row['transaction_id']."'>".comma($row['amount'])."</td>";
                            $tableinformation.="<td>".$row['date_of_transaction']."</td>";
                            $tableinformation.="<td>".$row['time_of_transaction']."</td>";
                            $tableinformation.="<td>".comma($row['balance'])."</td>";
                            $tableinformation.="<td>".$row['payment_for']."</td>";
                            $status = "<p>confirmed</p>";
                            if($row['date_of_transaction'] == date('Y-m-d') && $row['time_of_transaction'] > date("H:i:s",strtotime("30 minutes")) ){
                                if ($row['mode_of_pay'] != "mpesa" && $statuses != "1") {
                                    $status = "<button class='reverse' style='margin:0 auto;' id='".$row['transaction_id']."'>reverse</button>";
                                }elseif ($statuses == "1") {
                                    $status = "<p class='text-danger'>Reversed</p>";
                                }
                            }
                            $tableinformation.="<td>".$status."</td></tr>";
                        }
                    }
                    $tableinformation.="</table></div>";
                    $tableinformation.="<p class= 'hide' id='transaction_code'>".$transaction_code."</p><p class = 'hide' id ='mode_use_pay'>".$modeofpay."</p><p class='hide' id = 'amount_recieved'>".$amount_recieved."</p><p style='margin-top:10px;'>Note: <br> <small>D.O.P = Date of Payment <br>T.O.P = Time of Payment</small></p>";
                    
                    
                    $select = "SELECT max(`transaction_id`) AS 'max' FROM `finance`;";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $last_index = 1;
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $last_index = ($row['max']*1);
                        }
                    }

                    echo $tableinformation1."<input type='hidden' id='last_receipt_id' value='".$last_index."'>";
                    if($xss>0){
                        echo $tableinformation."";
                    }else {
                        echo "<p class = 'hide class_studs_in'>".explode("^",$names)[1]."</p><p>No records found!.</p>";
                    }
                }else {
                    echo "<p style='color:red;'>An error occured!</p>";
                }
            }else {
                echo "<p style='color:red;'>Admission number entered is invalid!</p>";
            }

        }elseif (isset($_GET['insertpayments'])) {
            include("../../sms_apis/sms.php");
            $studadmin = $_GET['stuadmin'];
            $fees_payment_opt_holder = $_GET['fees_payment_opt_holder'];
            $time_of_payment_fees = $_GET['time_of_payment_fees'];
            $date_of_payments_fees = $_GET['date_of_payments_fees'];
            $time = $fees_payment_opt_holder == "auto" ? date("H:i:s") : $time_of_payment_fees;
            $date = $fees_payment_opt_holder == "auto" ? date("Y-m-d") : $date_of_payments_fees;
            $trancode = $_GET['transcode'];
            $amount = $_GET['amount'];
            $term = getTerm();
            $payfor = $_GET['payfor'];
            $balance = $_GET['balances'];
            $newbalance = $balance-$amount;
            $supporting_documents_list = isset($_GET['supporting_documents_list']) ? $_GET['supporting_documents_list'] : "[]";

            $getProvisionalPayments = getProvisionalPayments($studadmin,$conn2);
            // var_dump($getProvisionalPayments);
            if (isPresent($getProvisionalPayments,trim(strtolower($payfor)))) {
                $newbalance = $balance;
            }
            
            // check if the last year academic balance is reduced and reduce it
            // $last_academic_balance = lastACADyrBal($studadmin,$conn2);
            // if ($last_academic_balance != 0 && !isPresent($getProvisionalPayments,trim(strtolower($payfor)))) {
            //     // echo $amount." ".$last_academic_balance." ".$newbalance;
            //     if ($amount > $last_academic_balance) {
            //         // clear it to zero
            //         $new_bal = 0;
            //         // if the last academic balance is a negative
            //         // deduct whats left and update the remeaining
            //         $select = "SELECT * FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` < ? ORDER BY `transaction_id` DESC LIMIT 1;";
            //         $stmt = $conn2->prepare($select);
            //         $beginyear = getTermStart($conn2,$term);
            //         $stmt->bind_param("ss",$studadmin,$beginyear);
            //         $stmt->execute();
            //         $result = $stmt->get_result();
            //         if ($result) {
            //             if ($row = $result->fetch_assoc()) {
            //                 if (isset($row['balance'])) {
            //                     $transaction_id = $row['transaction_id'];
            //                     $update = "UPDATE `finance` SET `balance` = '$new_bal' WHERE `transaction_id` = '$transaction_id'";
            //                     $stmt = $conn2->prepare($update);
            //                     $stmt->execute();
            //                 }
            //             }
            //         }
            //     }else {
            //         // deduct whats left and update the remaining
            //         $new_bal = $last_academic_balance - $amount;
            //         $select = "SELECT * FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` < ? ORDER BY `transaction_id` DESC LIMIT 1;";
            //         $stmt = $conn2->prepare($select);
            //         $beginyear = getTermStart($conn2,$term);
            //         $stmt->bind_param("ss",$studadmin,$beginyear);
            //         $stmt->execute();
            //         $result = $stmt->get_result();
            //         if ($result) {
            //             if ($row = $result->fetch_assoc()) {
            //                 if (isset($row['balance'])) {
            //                     $transaction_id = $row['transaction_id'];
            //                     $update = "UPDATE `finance` SET `balance` = '$new_bal' WHERE `transaction_id` = '$transaction_id'";
            //                     $stmt = $conn2->prepare($update);
            //                     $stmt->execute();
            //                 }
            //             }
            //         }
            //     }
            // }

            $payby = isset($_GET['paidby']) ? $_GET['paidby'] : $_SESSION['userids'];
            // // if the student last academic balance is less than zero this means its a balance carry forward
            // if ($last_academic_balance < 0) {
            //     $modeofpay = $_GET['modeofpay'];
            //     $times = date("H:i:s");
            //     $balance_carry_forward = $last_academic_balance * -1;
            //     $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`,`support_document`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            //     $stmt = $conn2->prepare($insert);
            //     $trans_code = "BCF";
            //     $balances = $newbalance + $amount;
            //     $stmt->bind_param("ssssssssss",$studadmin,$times,$date,$trans_code,$balance_carry_forward,$balances,$payfor,$payby,$modeofpay,$supporting_documents_list);
            //     $stmt->execute();
            // }

            $modeofpay = $_GET['modeofpay'];
            $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`,`support_document`) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssssssss",$studadmin,$time,$date,$trancode,$amount,$newbalance,$payfor,$payby,$modeofpay,$supporting_documents_list);
            if($stmt->execute()){
                $student_name = getName1($studadmin);
                //administrator notification
                    $messageName = "Confirmed payment for ".$student_name."";
                    $messagecontent = "Confirmed Ksh ".comma($amount)." has been recieved from ".$student_name." Adm No: ".$studadmin." for <b>".$payfor."</b>, on ".date("M-d-Y",strtotime($date))." at ".$time." hrs.<br>The payment mode used was <b>".$modeofpay."</b>";
                    $notice_stat = "0";
                    $reciever_id = "all";
                    $reciever_auth = "1";
                    $sender_id = "Payment system";
                    insertNotifcation($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_id);
                    
                    //send sms
                    $send_sms = $_GET['send_sms'];
                    if (isset($send_sms) && $send_sms != "none") {
                        $phone_number = getPhoneNumber($conn2,$studadmin);
                        if ($phone_number != 0) {
                            if ($send_sms == "first_parent") {
                                $phone_number = explode(",",$phone_number)[0];
                            }else if ($send_sms == "second_parent") {
                                $phone_number = explode(",",$phone_number)[1];
                            }elseif ($send_sms == "both_parent") {
                                $phone_number = $phone_number;
                            }else {
                                $phone_number = "";
                            }
                            $message = "Confirmed Kes ".comma($amount)." has been successfully paid for ".$student_name.", New fee balance is Kes ".comma($newbalance)." as at ".date("H:i:s")." on ".date("d-M-Y").".";
                            // echo $message;
                            $api_key = getApiKey($conn2);
                            //check if the school has its own api keys
                            $school = 1;
                            if ($api_key == 0) {
                                $school = 0;
                                $api_key = getApiKey($conn);
                            }
                            //echo $api_key;
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
                                }
                                //recorded the sms information to the sms server
                                $message_type = "Multicast";
                                $message_count = count(explode(",",$phone_number));
                                $recipient_no = $phone_number;
                                $text_message = $message;
                                $message_desc = strlen($message) > 45 ? substr($message,0,45)."..." : $message;
                                $date = date("Y-m-d");
                                $select = "INSERT INTO `sms_table` (`message_count`,`date_sent`,`message_sent_succesfully`,`message_undelivered`,`message_type`,`sender_no`,`message_description`,`message`) VALUES ('$message_count','$date','$message_count','$message_count','$message_type','$recipient_no','$message_desc','$text_message')";
                                $stmt = $conn2->prepare($select);
                                // echo $select;
                                $stmt->execute();
                                // echo "Inserted successfull!";
                                // }else{
                                //     echo "Inserted not successfull!";
                                // }
                            }else {
                                echo "<p class='red_notice'>Activate your sms account!</p>";
                            }
                        }else {
                            echo "Invalid parents phone number!";
                        }
                        //end of sms
                    }
                echo "<p style='color:green;font-size:13px;'>Transaction completed successfully!</p>";

                // LOG TEXT
                $log_text = "Transaction for \"".ucwords(strtolower($student_name))."\" Reg No \"".$studadmin."\" has been completed successfully!";
                log_finance($log_text);

                // if the credit note id is set get it and update the assignment status and the student getting the credit
                if(isset($_GET['credit_id'])){
                    $select = "SELECT * FROM `finance` WHERE `stud_admin` = '".$studadmin."' ORDER BY transaction_id DESC LIMIT 1";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $last_transaction_id = 0;
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $last_transaction_id = $row['transaction_id'];
                        }
                    }
                    $update = "UPDATE `fees_credit_note` SET `status` = '1', `assigned` = ?, `transaction_id` = ? WHERE `id` = ?";
                    $stmt  = $conn2->prepare($update);
                    $stmt->bind_param("sss",$studadmin,$last_transaction_id,$_GET['credit_id']);
                    $stmt->execute();
                }
            }else{
                echo "<p style='color:red;font-size:13px;'>An error has occured!</p>";
            }


            // if the mpesa transaction change the transaction to assigned
            if (isset($_GET['mpesa_id'])) {
                $update = "UPDATE `mpesa_transactions` SET `std_adm` = ?, `transaction_status` = '1' WHERE `transaction_id` = ?";
                $stuadmin = $_GET['stuadmin'];
                $mpesa_id = $_GET['mpesa_id'];
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ss",$stuadmin,$mpesa_id);
                $stmt->execute();
            }
        }elseif(isset($_GET['recover_asset'])){
            $asset_id = $_GET['asset_id'];
            $select = "SELECT * FROM `asset_table` WHERE `asset_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$asset_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    // update the asset dispose status
                    $update = "UPDATE `asset_table` SET `disposed_status` = ?, `disposed_on` = ? WHERE `asset_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $disposed_status = "0";
                    $disposed_on = "";
                    $stmt->bind_param("sss", $disposed_status,$disposed_on,$asset_id);
                    $stmt->execute();

                    echo "<p class='text-success' id='asset-recover-success'>Assets has been recovered successfully!</p>";
                    return 0;
                }
            }

            echo "<p class='text-danger'>The asset is invalid!</p>";
        }elseif(isset($_GET['asset_data'])){
            $select = "SELECT * FROM `asset_table` WHERE `asset_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$_GET['asset_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $row['real_acquisition_date'] = $row['date_of_acquiry'];
                    $row['real_asset_category'] = $row['asset_category'];
                    $row['real_orginal_value'] = $row['orginal_value'];
                    $row['disposed_on'] = date("D dS M Y", strtotime($row['disposed_on']));
                    
                    // get the asset category
                    $asset_category = "N/A";
                    if($row['asset_category'] == "1"){
                        $asset_category = "Land";
                    }elseif($row['asset_category'] == "2"){
                        $asset_category = "Buildings";
                    }elseif($row['asset_category'] == "3"){
                        $asset_category = "Motor Vehicle";
                    }elseif($row['asset_category'] == "4"){
                        $asset_category = "Furniture & Fittings";
                    }elseif($row['asset_category'] == "5"){
                        $asset_category = "Computer & ICT Equipments";
                    }elseif($row['asset_category'] == "6"){
                        $asset_category = "Plant & Equipments";
                    }elseif($row['asset_category'] == "7"){
                        $asset_category = "Capital Work in Progress";
                    }

                    $row['asset_category'] = $asset_category;

                    // get the current value
                    $value_acquisition = get_current_value($row);
                    
                    // real new value
                    $row['real_new_value'] = $value_acquisition['new_value'];
                    
                    // get the date difference
                    $financial_year_end = date("Y")."1231235959";
                    $date_acquired = date("YmdHis",strtotime($row['date_of_acquiry']));
                    $date1 = date_create($financial_year_end);
                    $date2 = date_create($date_acquired);
                    $diff = date_diff($date1,$date2);
                    $difference_year = $diff->format("%y");

                    $row['years'] = $difference_year;
                    
                    // change date
                    $row['date_of_acquiry'] = date("D dS M Y",strtotime($row['date_of_acquiry']));
                    $row['new_value'] = number_format($value_acquisition['new_value']);
                    $row['value_acquisition'] = $value_acquisition['value_acquisition'];
                    $row['orginal_value'] = number_format($row['orginal_value']);
                    $row['disposed_value'] = "Kes ".number_format($row['disposed_value']);
                    echo json_encode($row);
                    return 0;
                }
            }
            echo "Null";
        }elseif (isset($_GET['findtransactions'])) {
            $period = $_GET['period'];
            $students = $_GET['studentstype'];
            $today = date("Y-m-d");
            $time = date("H:i:s");
            $startdate = '';
            $enddate;
            $dates;
            if($period =="today"){
                $startdate = date("Y-m-d");
                $dates = "<p>Displaying results of <b>".date("l dS \of M Y")."</b></p>";
            }elseif($period =="last2days"){
                $startdate = date("Y-m-d",strtotime("-24 hours"));
                $enddate = date("Y-m-d",strtotime("-48 hours"));
                $dates = "<p>Displaying results as from <b>".date("l dS \of M Y",strtotime("-48 hours"))." </b>to <b>".date("l dS \of M Y")."</b></p>";
            }elseif($period =="last5days"){
                $startdate = date("Y-m-d",strtotime("-96 hours"));
                $enddate = date("Y-m-d",strtotime("-120 hours"));
                $dates = "<p>Displaying results as from <b>".date("l dS \of M Y",strtotime("-120 hours"))."</b> to<b> ".date("l dS \of M Y")."</b></p>";
            }elseif($period =="lastoneweek"){
                $startdate = date("Y-m-d",strtotime("-144 hours"));
                $enddate = date("Y-m-d",strtotime("-168 hours"));
                $dates = "<p>Displaying results as from <b>".date("l dS \of M Y",strtotime("-168 hours"))."</b> to<b> ".date("l dS \of M Y")."</b></p>";
            }
            $select1 = "SELECT * , (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction BETWEEN ? and ? OR (date_of_transaction = ? and `time_of_transaction` > ?) ORDER BY `transaction_id` DESC";
            $select2 = "SELECT * ,(SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction = ? ORDER BY `transaction_id` DESC ";
            $stmt;
            if(!isset($enddate)){
                $stmt = $conn2->prepare($select2);
                $stmt->bind_param("s",$today);
                $stmt->execute();
                $resulted = $stmt->get_result();
            }else {
                $stmt = $conn2->prepare($select1);
                $stmt->bind_param("ssss",$startdate,$today,$enddate,$time);
                $stmt->execute();
                $resulted = $stmt->get_result();
            }
            //create the table
            $table = createtablefinance($resulted);
            $table3 = createTotal2($stmt);
            $data1 = "<div class='tablecarriers'>".createTotal($stmt).$table3."</div>";
            //add the selection of caharts or table
            $selections = "<div class='selectoptions' id='solace'>
                                <div class='view_opt'>
                                    <p>View:</p>
                                </div>
                                <div class='avail_view_options'>
                                    <div class='tables' id='tabular'>
                                        <p>Table</p>
                                    </div>
                                    <div class='tables'  id='chartlike'>
                                        <p>Chart</p>
                                    </div>
                                    <div class='selected_Option'  id='hide_chart_table'>
                                        <p>Hide</p>
                                    </div>
                                </div>
                            </div><p id='noticeHold' style='text-align:center;' class='red_notice hide'>Some values may not appear on the chart because they are equivalent to \"0\"<br>View their values at the table view</p>";
            $data = $dates."<br>".$selections."<br>".$data1." ".$table;
            echo $data;
        }elseif (isset($_GET['findtransbtndates'])) {
            $startperiod = $_GET['startfrom'];
            $endperiod = $_GET['endperiod'];
            $time = date("H:i:s");
            $dates;$dated;
            $stmt;
            if ($startperiod!=$endperiod) {
                if ($startperiod<$endperiod) {

                    $date=date_create($startperiod);
                    date_add($date,date_interval_create_from_date_string("1 day"));
                    $dates = date_format($date,"Y-m-d");
                    $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction BETWEEN ? and ? OR (date_of_transaction = ? and `time_of_transaction` > ?) ORDER BY `transaction_id` DESC";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("ssss",$dates,$endperiod,$startperiod,$time);
                    $stmt->execute();
                    $dated = "<p>Displaying results as from <b>".date("l dS \of M Y",strtotime($startperiod))."</b> to<b> ".date("l dS \of M Y",strtotime($endperiod))."</b></p>";
                    

                }elseif ($startperiod>$endperiod) {

                    $date=date_create($endperiod);
                    date_add($date,date_interval_create_from_date_string("1 day"));
                    $dates = date_format($date,"Y-m-d");
                    $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction BETWEEN ? and ? OR (date_of_transaction = ? and `time_of_transaction` > ?) ORDER BY `transaction_id` DESC";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("ssss",$dates,$startperiod,$endperiod,$time);
                    $stmt->execute();
                    $dated = "<p>Displaying results as from <b>".date("l dS \of M Y",strtotime($endperiod))."</b> to<b> ".date("l dS \of M Y",strtotime($startperiod))."</b></p>";
                    
                }
            }else {
                $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction = ? ORDER BY `transaction_id` DESC";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$startperiod);
                $stmt->execute();
                $dated = "<p>Displaying results of <b>".date("l dS \of M Y",strtotime($endperiod))."</b>";

            }
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            // echo $rnums;

            $stmt->execute();
            //create the table
            $resulted = $rnums>0 ? $stmt->get_result(): null;
            $table = createtablefinance($resulted);
            $table3 = createTotal2($stmt);
            $data1 = "<div class='tablecarriers'>".createTotal($stmt).$table3."</div>";
            //add the selection of caharts or table
            $selections = "<div class='selectoptions' id='solace'>
                                <div class='view_opt'>
                                    <p>View:</p>
                                </div>
                                <div class='avail_view_options'>
                                    <div class='tables' id='tabular'>
                                        <p>Table</p>
                                    </div>
                                    <div class='tables'  id='chartlike'>
                                        <p>Chart</p>
                                    </div>
                                    <div class='selected_Option'  id='hide_chart_table'>
                                        <p>Hide</p>
                                    </div>
                                </div>
                            </div><p id='noticeHold' style='text-align:center;' class='red_notice hide'>Some values may not appear on the chart because they are equivalent to \"0\"<br>View their values at the table view</p>";
            $data = $dated."<br>".$selections."<br>".$data1." ".$table;
            echo $data;
        }elseif (isset($_GET['findtransbtndatesandadmno'])) {
            $startperiod = $_GET['startfrom'];
            $endperiod = $_GET['endperiod'];
            $admno = $_GET['admnos'];
            $time = date("H:i:s");
            $dates;$dated;
            $name = getName($admno);
            $stmt;
            if($name!="null"){
                $name = explode("^",$name)[0];
                if ($startperiod!=$endperiod) {
                    if ($startperiod<$endperiod) {

                        $date=date_create($startperiod);
                        date_add($date,date_interval_create_from_date_string("1 day"));
                        $dates = date_format($date,"Y-m-d");
                        $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE (date_of_transaction BETWEEN ? and ?  OR (date_of_transaction = ? and `time_of_transaction` > ?)) AND `stud_admin` = ? ORDER BY `transaction_id` DESC";
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("sssss",$dates,$endperiod,$startperiod,$time,$admno);
                        $stmt->execute();
                        $dated = "<p>Displaying results of <b>'".$name."'</b>.<br>As from <b>".date("l dS \of M Y",strtotime($startperiod))."</b> to<b> ".date("l dS \of M Y",strtotime($endperiod))."</b></p>";
                        

                    }elseif ($startperiod>$endperiod) {

                        $date=date_create($endperiod);
                        date_add($date,date_interval_create_from_date_string("1 day"));
                        $dates = date_format($date,"Y-m-d");
                        $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE (date_of_transaction BETWEEN ? and ? OR (date_of_transaction = ? and `time_of_transaction` > ?))  AND `stud_admin` = ?  ORDER BY `transaction_id` DESC";
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("sssss",$dates,$startperiod,$endperiod,$time,$admno);
                        $stmt->execute();
                        $dated = "<p>Displaying results of <b>'".$name."'</b>.<br> As from <b>".date("l dS \of M Y",strtotime($endperiod))."</b> to<b> ".date("l dS \of M Y",strtotime($startperiod))."</b></p>";
                        
                    }
                }else {
                    $select = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE date_of_transaction = ? AND `stud_admin` = ? ORDER BY `transaction_id` DESC";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("ss",$startperiod,$admno);
                    $stmt->execute();
                    $dated = "<p>Displaying results of of <b>'".$name."'</b>.<br>On <b>".date("l dS \of M Y",strtotime($endperiod))."</b>";

                }
                //create the table
                $resulted = $stmt->get_result();
                $table = createtablefinance($resulted);
                $table3 = createTotal2($stmt);
                $term = getTerm();
                $feespaid = getFeespaidByStudent($admno,$conn2);
                $balance = getBalance($admno,$term,$conn2);
                $data1 = "<p>Amount paid this academic year: <strong>Ksh ".comma($feespaid)." </strong></p><p>Balance as of ".$term.":<strong> Ksh ".comma($balance)." </strong></p><br><div class='tablecarriers'>".createTotal($stmt).$table3."</div>";
                //add the selection of caharts or table
                $selections = "<div class='selectoptions' id='solace'>
                                    <div class='view_opt'>
                                        <p>View:</p>
                                    </div>
                                    <div class='avail_view_options'>
                                        <div class='tables' id='tabular'>
                                            <p>Table</p>
                                        </div>
                                        <div class='tables'  id='chartlike'>
                                            <p>Chart</p>
                                        </div>
                                        <div class='selected_Option'  id='hide_chart_table'>
                                            <p>Hide</p>
                                        </div>
                                    </div>
                                </div><p id='noticeHold' style='text-align:center;' class='red_notice hide'>Some values may not appear on the chart because they are equivalent to \"0\"<br>View their values at the table view</p>";
                $data = $dated."<br>".$selections."<br>".$data1." ".$table;
                echo $data;
            }else {
                echo "<p style='color:red;'>Invalid admission number!</p>";
            }
        }elseif (isset($_GET['findtransbtncontsdatesandadmno'])) {
            $period = $_GET['period'];
            $adminno = $_GET['admnos'];
            $today = date("Y-m-d");
            $time = date("H:i:s");
            $startdate = '';
            $enddate;
            $dates;
            $name = getName($adminno);
            if($name!="null"){
                $name = explode("^",$name)[0];
                if($period =="today"){
                    $startdate = date("Y-m-d");
                    $dates = "<p>Displaying results of <b>".$name."</b><br> <b>".date("l dS \of M Y")."</b></p>";
                }elseif($period =="last2days"){
                    $startdate = date("Y-m-d",strtotime("-24 hours"));
                    $enddate = date("Y-m-d",strtotime("-48 hours"));
                    $dates = "<p>Displaying results of <b>".$name."</b><br> As from <b>".date("l dS \of M Y",strtotime("-48 hours"))." </b>to <b>".date("l dS \of M Y")."</b></p>";
                }elseif($period =="last5days"){
                    $startdate = date("Y-m-d",strtotime("-96 hours"));
                    $enddate = date("Y-m-d",strtotime("-120 hours"));
                    $dates = "<p>Displaying results of <b>".$name."</b><br> As from <b>".date("l dS \of M Y",strtotime("-120 hours"))."</b> to<b> ".date("l dS \of M Y")."</b></p>";
                }elseif($period =="lastoneweek"){
                    $startdate = date("Y-m-d",strtotime("-144 hours"));
                    $enddate = date("Y-m-d",strtotime("-168 hours"));
                    $dates = "<p>Displaying results of <b>".$name."</b><br> As from <b>".date("l dS \of M Y",strtotime("-168 hours"))."</b> to<b> ".date("l dS \of M Y")."</b></p>";
                }
                $select1 = "SELECT *, (SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' FROM `finance` WHERE (date_of_transaction BETWEEN ? and ? OR (date_of_transaction = ? and `time_of_transaction` > ?)) AND `stud_admin` = ?  ORDER BY `transaction_id` DESC";
                $select2 = "SELECT `stud_admin` , `mode_of_pay`,(SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' ,  date_of_transaction , time_of_transaction , `amount` , balance, payment_for  FROM `finance` WHERE date_of_transaction = ? AND `stud_admin` = ?  ORDER BY `transaction_id` DESC ";
                $stmt;
                if(!isset($enddate)){
                    $stmt = $conn2->prepare($select2);
                    $stmt->bind_param("ss",$today,$adminno);
                    $stmt->execute();
                    $resulted = $stmt->get_result();
                }else {
                    $stmt = $conn2->prepare($select1);
                    $stmt->bind_param("sssss",$startdate,$today,$enddate,$time,$adminno);
                    $stmt->execute();
                    $resulted = $stmt->get_result();
                }
                //create the table
                $table = createtablefinance($resulted);
                $table3 = createTotal2($stmt);
                $term = getTerm();
                $feespaid = getFeespaidByStudent($adminno,$conn2);
                $balance = getBalance($adminno,$term,$conn2);
                $data1 = "<p>Amount paid this academic year: <strong>Ksh ".comma($feespaid)." </strong></p><p>Balance as of ".$term.":<strong> Ksh ".comma($balance)." </strong></p><br><div class='tablecarriers'>".createTotal($stmt).$table3."</div>";
                //add the selection of caharts or table
                $selections = "<div class='selectoptions' id='solace'>
                                    <div class='view_opt'>
                                        <p>View:</p>
                                    </div>
                                    <div class='avail_view_options'>
                                        <div class='tables' id='tabular'>
                                            <p>Table</p>
                                        </div>
                                        <div class='tables'  id='chartlike'>
                                            <p>Chart</p>
                                        </div>
                                        <div class='selected_Option'  id='hide_chart_table'>
                                            <p>Hide</p>
                                        </div>
                                    </div>
                                </div><p id='noticeHold' style='text-align:center;' class='red_notice hide'>Some values may not appear on the chart because they are equivalent to \"0\"<br>View their values at the table view</p>";
                $data = $dates."<br>".$selections."<br>".$data1." ".$table;
                echo $data;
            }else {
                echo "<p style='color:red;'>Invalid admission number!</p>";
            }
        }elseif (isset($_GET['findtransindates'])) {
            $class = $_GET['class'];
            $students = studentInclass($class,$conn2);
            $classd = $class;
            $term = getTerm();
            $feespaidbyc = getFeesAsPerTerm($term,$conn2,$class);
            $classd = className($class);
            if (count($students)>0) {
                $tablein4 = "<p style='text-align:center;'><span style='font-size:20px;font-weight:500;'><u>Displaying results for ".$classd."</u></span><br>By term <b>".$term."</b> they are to pay Ksh <b>".comma($feespaidbyc)."</b> each. ";
                if (getBoardingFees($conn2,$class) > 0) {
                    $tablein4.="<b>AND</b> if boarding <b>".comma(getBoardingFees($conn2,$class))."</b> is added to their fees.<br> Boarders name are preceeded with <span style='color:green;font-size:12px;font-weight:600;'> -b</span> for easy identification.";
                }
                $tablein4.="<br>Those enrolled in transport their names are preceeded with <span style='color:green;font-size: 13px;font-weight:100;'>-t</span> for easy identification!";
                $tablein4.="<p style='text-align:center;' >Scroll down to find the <b>print fees reminder</b> button and print fee reminders</p>";
                $tablein4.= "</p><p id='pleasewait23' style='color:green;text-align:center;' >Preparing please wait...</p><div class='tableme'><table class='table'><tr><th>No.</th><th>Name</th><th>Adm no.</th><th>Paid amounts</th><th>Balance</th><th>Options</th><th title='select to print fees reminder'><label for='select_all_reminders' class='text-sm' style='unset:all;'>Select All</label> <input style='align-items:center;margin:auto;cursor:pointer;' type='checkbox' id='select_all_reminders'></th></tr>";
                $total = 0;
                $totbal = 0;
                for ($i=0; $i < count($students); $i++) { 
                    $data = explode("^",$students[$i]);
                    $feespaid = getFeespaidByStudent($data[1],$conn2);
                    $balance = getBalance($data[1],$term,$conn2);
                    $balancetxt;
                    if ($balance==0) {
                        $balancetxt="<p style='color:green;font-size: 13px;font-weight:100;'>Cleared!</p>";
                    }else {
                        $balancetxt="Ksh ".comma($balance)."";
                    }
                    $total+=$feespaid;
                    $totbal+=$balance;
                    $tablein4.="<tr><td>".($i+1)."</td>"."<td><label style='all:unset;cursor:pointer;' for='sutid".$data[1]."'>".ucwords(strtolower($data[0]))."</label>";
                    // checj for bording
                    if (isBoarding($data[1],$conn2)) {
                        $tablein4.="<span style='color:green;font-size:12px;font-weight:600;'> -b</span>";
                    }
                    if (isTransport($conn2,$data[1])) {
                        $tablein4.="<span style='color:green;font-size:12px;font-weight:600;'> -t</span>";
                    }
                    $tablein4.="</td><td class='text-center'>".$data[1]."</td><td style='color:green; font-size:12px;font-weight:600;'>Ksh ".comma($feespaid)."</td><td style='color:rgb(71,0, 26);font-size:13px;font-weight:600;'>".$balancetxt."</td><td>"."<button class='finbtns' id='finbtn".$data[1]."'>More..</button>"."</td><td ><input style='align-items:center;margin:auto;cursor:pointer;' type='checkbox' class='sutid' id='sutid".$data[1]."'></td></tr>";
                }
                $tablein4.="<tr><td style='border:none;'></td><td style='border:none;'></td><td><b>Total</b></td><td style='color:green; font-size:13px;' ><b>Ksh ".comma($total)."</b></td><td style='color:rgb(71,0, 26); font-size:13px;' ><b>Ksh ".comma($totbal)."</b></td><td style='border:none;'></td></tr></table></div>";
                $tablein4.="<hr><div class='conts'> <br><h4 class='text-center'>Invoicing</h4><br><p>At this window you will be able to print invoices or send invoices to parents via email.<br>- Start by selecting the students you want to send the email to their parents.</p>";
                $tablein4.="<div class='container'>
                    <form action='reports/reports.php' target='_blank' class='form-group my-1 border border-dark bg-white p-2 rounded-lg' method='POST'>
                        <h6 id='image_omens' class='text-center'>Send Invoices</h6>
                        <label class='form-control-label text-bold'> Select an option</label>
                        <select id='email_selections' name='email_selections' class='form-control w-50'>
                            <option hidden value =''>Select an option</option>
                            <option value ='print_invoices'> Print Invoices</option>
                            <option selected value ='send_email_invoices'>Send Email Invoices</option>
                        </select>
                        <div class='container_ones'>
                            <label class='form-control-label my-1'>Message Subject</label>
                            <input type='text' name='message_subjects' class='form-control w-50' value = '".ucwords(strtolower($_SESSION['schname']))." Fees Invoice' placeholder='Message Subject'>
                        </div>
                        <div class='container_ones'>
                            <label class='form-control-label my-1'>CC</label>
                            <input type='text' name='cc_email' class='form-control w-50' placeholder='CC'>
                        </div>
                        <div class='container_ones'>
                            <label class='form-control-label my-1'>BCC</label>
                            <input type='text' name='bcc_email' class='form-control w-50' placeholder='BCC'>
                        </div>
                        <div class='container_ones'>
                            <input type='hidden' name='students_ids' id='students_ids' value=''>
                            <label class='form-control-label my-1'>Send to whom?</label>
                            <select id='send_to_whom' name='send_to_whom' class='form-control w-50'>
                                <option hidden value =''>Select an option</option>
                                <option value ='primary_parents'> Primary Parent</option>
                                <option value ='secondary_parent'>Secondary Parents</option>
                                <option selected value ='both_parent'>Both Parents</option>
                            </select>
                        </div>
                        <div class='container_ones'>
                            <label class='form-control-label my-1'>Write a message</label>
                            <textarea rows='10' id='invoice_message' name='invoice_message' class='form-control invoice_message' placeholder='Write a message to the parent to complement the Invoice you are sending (Optional)!'>Dear Parent,

                            We hope you are doing fine and healthy, Please find the attached invoice for your child`s fees.
                            Kind regards,
                            Headteacher</textarea>
                        </div>
                        <div class='container'>
                            <label class='form-control-label my-1'>Payment Details</label>
                            <textarea rows='4' id='invoice_email_message' name='invoice_email_message' class='form-control' placeholder='Write your payment details below eg (Equity Paybill : 247247, Account Number : 11001100110. KCB Paybill : 522522 , Account Number : 110011001100 etc)'>Equity Paybill : 247247, Account Number : 11001100110. KCB Paybill : 522522 , Account Number : 110011001100</textarea>
                        </div>
                        <button id='print_or_send_invoice_btn' name='print_or_send_invoice_btn' class='btn btn-sm btn-primary'><i class='fas fa-paper-plane'></i> Send</button>
                        <p style='color:green;'>When sending the email, The process may take a while please be patient.</p>
                    </form>
                    
                ";
                $tablein4.="</div>";
                $tablein4.="<hr>";

                echo $tablein4;
            }else {
                echo "<p style='color:red;'>There are no members in ".className($class)."</p>";
            }
        }elseif (isset($_GET['find_transaction_with_code'])) {
            $transaction_code = $_GET['find_transaction_with_code'];
            $select2 = "SELECT `stud_admin` , `mode_of_pay`,(SELECT(concat(`first_name`,' ',`second_name`)) FROM `student_data` WHERE `adm_no` = `stud_admin`) AS 'Name' ,  date_of_transaction , time_of_transaction , `amount` , balance, payment_for  FROM `finance` WHERE `transaction_code` = ?  ORDER BY `transaction_id` DESC ";
            $stmt = $conn2->prepare($select2);
            $stmt->bind_param("s",$transaction_code);
            $stmt->execute();
            $resulted = $stmt->get_result();
            if ($resulted) {
                //create the table
                $table = createtablefinance($resulted);
                $table3 = createTotal2($stmt);
                $data1 = "<div class='tablecarriers' id='hide_datas'>".createTotal($stmt).$table3."</div>";
                //add the selection of caharts or table
                $selections = "<div class='selectoptions' id='solace'>
                                    <div class='view_opt'>
                                        <p>View:</p>
                                    </div>
                                    <div class='avail_view_options'>
                                        <div class='tables' id='tabular'>
                                            <p>Table</p>
                                        </div>
                                        <div class='tables'  id='chartlike'>
                                            <p>Chart</p>
                                        </div>
                                        <div class='selected_Option'  id='hide_chart_table'>
                                            <p>Hide</p>
                                        </div>
                                    </div>
                                </div><p id='noticeHold' style='text-align:center;' class='red_notice hide'>Some values may not appear on the chart because they are equivalent to \"0\"<br>View their values at the table view</p>";
                $data = $selections."<br>".$data1." ".$table;
                echo $data;
                
            }
        }
        elseif (isset($_GET['transactionid'])) {
            $transactionid = $_GET['transactionid'];
            $amount_reverse = $_GET['amount_reverse'];
            $select = "SELECT * FROM `finance` WHERE `transaction_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$transactionid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                     $stud_admin = $row['stud_admin'];
                     $transaction_id = $row['transaction_id'];
                     $amount = $row['amount'];
                     $amount2 = $row['amount'];
                     $balance = $row['balance'];
                     $balance2 = $row['balance'];
                     $payment_for = $row['payment_for'];
                     $payBy = $row['payBy'];
                     $mode_of_pay = $row['mode_of_pay'];
                     $status = $row['status'];
                     $idsd = $row['idsd'];
                     $amount_paid = getFeespaidByStudent($stud_admin,$conn2);
                     $lastacad = lastACADyrBal($stud_admin,$conn2);
                     $daro = getName($stud_admin);

                    // //  get to know if the payment is provissional or not
                    // $classd = explode("^",getClass($stud_admin))[1];
                    // $isprov = isProvisional($payment_for,$conn2,$classd);
                    // if($isprov != "true"){
                    //     // get the balance do not add
                    //     $balance = $balance+$amount;
                    // }

                    // provisional payments
                    $provisonal_pays = getProvisionalPayments($stud_admin,$conn2);
                    $payments_for = "reverse";
                    if(isPresent($provisonal_pays,strtolower($payment_for))){
                        $balance = $balance+$amount;
                        $payments_for = $payment_for;
                    }
                    // insert a new record showing the amount was reversed
                    $insert = "INSERT INTO `finance` (`stud_admin`,`time_of_transaction`,`date_of_transaction`,`transaction_code`,`amount`,`balance`,`payment_for`,`payBy`,`mode_of_pay`,`status`) VALUES (?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn2->prepare($insert);
                    $time = date("H:i:s",strtotime("3 hours"));
                    $dates = date("Y-m-d",strtotime("3 hours"));
                    $transaction_code = "reverse";
                    $amount = "-".$amount;
                    $status = "1";
                    $stmt->bind_param("ssssssssss",$stud_admin,$time,$dates,$transaction_code,$amount,$balance,$payments_for,$payBy,$transaction_code,$status);
                    $stmt->execute();

                    // update the current transaction data status to reveresed
                    $update = "UPDATE `finance` SET `status` = '1' WHERE `transaction_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$transactionid);
                    if($stmt->execute()){
                        $students_id_ddds = $stud_admin;
                        $student_name = getName1($students_id_ddds);
                        $messageName = "Reversal of payment for ".$student_name."";
                        $messagecontent = "Reversal of Ksh ".$amount_reverse." for ".$student_name." Adm No: ".$students_id_ddds." has been done successfully on ".date("M-d-Y")." at ".date("H:i:s")." hrs";
                        $notice_stat = "0";
                        $reciever_id = "all";
                        $reciever_auth = "1";
                        $sender_id = "Payment system";
                        insertNotifcation($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_id);
                        // check if the last academic balance is above zero or the paid amount and the balance is greater than the required payment
                        $term = getTermV2($conn2);
                        $balance = $balance2;
                        $getclass = explode("^",$daro);
                        $dach = $getclass[1];
                        $feestopay = getFeesAsFromTermAdmited($term,$conn2,$dach,$students_id_ddds);
                        // echo $balance." ".$lastacad." ".$amount_paid." ".$feestopay."<br>";
                        if ($feestopay < ($balance+$amount_paid && !isPresent($provisonal_pays,strtolower($payment_for)))) {
                            // if the balance and the amount paid is greater than the fees to pay 
                            // that means that there was a last academic balance
                            // get the amount paid that academic year
                            $last_acad_bal = ($balance+$amount_paid) - $feestopay;
                            if ($amount2 < $last_acad_bal) {
                                // clear it to zero
                                $new_bal = $amount2+$lastacad;
                                // echo $new_bal." ".$amount2." UP";
                                // deduct whats left and update the remeaining
                                $select = "SELECT * FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` < ? ORDER BY `transaction_id` DESC LIMIT 1;";
                                $stmt = $conn2->prepare($select);
                                $beginyear = getAcademicStart($conn2);
                                $stmt->bind_param("ss",$students_id_ddds,$beginyear);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        if (isset($row['balance'])) {
                                            $transaction_id = $row['transaction_id'];
                                            $update = "UPDATE `finance` SET `balance` = '$new_bal' WHERE `transaction_id` = '$transaction_id'";
                                            $stmt = $conn2->prepare($update);
                                            $stmt->execute();
                                        }
                                    }
                                }
                            }else {
                                // if the amount2 reversed is greater than the last academic balance
                                // take fees to pay minus balance to get how much was paid to the current academic year
                                $newbalance = $amount2 - ($feestopay - $balance);
                                // echo $amount2." ".$newbalance." ".$feestopay." ".$balance."DOWN";
                                // deduct whats left and update the remeaining
                                $select = "SELECT * FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` < ? ORDER BY `transaction_id` DESC LIMIT 1;";
                                $stmt = $conn2->prepare($select);
                                $beginyear = getAcademicStart($conn2);
                                $stmt->bind_param("ss",$students_id_ddds,$beginyear);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        if (isset($row['balance'])) {
                                            $transaction_id = $row['transaction_id'];
                                            $update = "UPDATE `finance` SET `balance` = '$newbalance' WHERE `transaction_id` = '$transaction_id'";
                                            $stmt = $conn2->prepare($update);
                                            $stmt->execute();
                                        }
                                    }
                                }
                            }
                        }
                        echo "<p style='color:green;'>Reverse was successfull</p>";
                    }else {
                        echo "<p style='color:red;'>Reverse was not successfull</p>";
                    }
                }
            }
            // $students_id_ddds = $_GET['students_id_ddds'];
            // $delete = "DELETE FROM `finance` WHERE `transaction_id` = ?";
            // $stmt = $conn2->prepare($delete);
            // $stmt->bind_param("i",$transactionid);
            
        }elseif(isset($_GET['delete_transaction'])){
            $transactions_id = $_GET['transactions_id'];

            // get the transaction details and delete all the supporting documents first
            $select = "SELECT * FROM `finance` WHERE `transaction_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$transactions_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $support_document = $row['support_document'];
                    // echo $support_document;
                    if (isJson_report_fin($support_document)) {
                        $support_document = json_decode($support_document);

                        // loop and delete the files
                        for ($index=0; $index < count($support_document); $index++) {
                            $fileLocation = $support_document[$index]->fileLocation;

                            // delete the supporting documents
                            $deleted = deleteFile("../../../".$fileLocation);
                            // echo $deleted." is deleted";
                        }
                    }
                }
            }

            $delete = "DELETE FROM `finance` WHERE `transaction_id` = '".$transactions_id."'";
            $stmt = $conn2->prepare($delete);
            if($stmt->execute()){
                // transaction deleted successfully!
                $log_text = "Transaction has been deleted successfully!";
                log_finance($log_text);
                echo "<p class='border border-success p-1 m-1 text-success'>Transaction deleted successfully!</p>";
            }else{
                echo "<p class='border border-danger p-1 m-1 text-danger'>An error occured!</p>";
            }
        }elseif(isset($_GET['get_fees_struct_courses'])){
            // store course level
            $course_level_name = $_GET['course_level'];
            $student_course_id = $_GET['course_id'];

            // get all classes
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $course_levels = [];
            
            // get results
            if($result){
                if($row = $result->fetch_assoc()){
                    $course_levels = isJson($row['valued']) ? json_decode($row['valued']) : [];
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
            $selector = "<select class='form-control' id='course_chosen_fees_structure'><option hidden value=''>Select course</option>";
            if($result){
                if($row = $result->fetch_assoc()){
                    $my_courses = isJson($row['valued']) ? json_decode($row['valued']) : [];
                    for($index = 0; $index < count($my_courses); $index++){
                        // get courses
                        $courses_levels = isJson($my_courses[$index]->course_levels) ? json_decode($my_courses[$index]->course_levels) : [];

                        // loop through all courses to see if its the one in this level
                        $present = false;
                        for($in = 0; $in < count($courses_levels); $in++){
                            if($courses_levels[$in] == $course_id){
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
        }elseif (isset($_GET['feesstructurefind'])) {
            $class = $_GET['class'];
            $course_id = $_GET['course_id'];
            $select = "SELECT * FROM `fees_structure` WHERE `classes` = ? AND `course` = '".$course_id."'";
            $daros = "".$class."";
            if($class == "-3"){
                // get all the course levels that dont have a class and a course
                $select = "SELECT * FROM `settings` WHERE `sett` = 'class';";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $where_clause = "";
                if($result){
                    if($row = $result->fetch_assoc()){
                        // valued
                        $valued = isJson($row['valued']) ? json_decode($row['valued']) : [];
                        for($index = 0; $index < count($valued); $index++){
                            $where_clause .= " AND `classes` != '".$valued[$index]->classes."'";
                        }
                    }
                }
                $select = "SELECT * FROM `fees_structure` WHERE `classes` != ? AND `classes` != '-1' AND `classes` != '-2' ".$where_clause;
                $daros = "-3";
            }
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$daros);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res) {
                $dat = "Class ".$class;
                if (strlen($class)>1) {
                    $dat=$class;
                }
                if($class == "-3"){
                    $dat = "Un-assigned Payments";
                }
                $table = "<h6 style='text-align:center;'>Fees structure for <span id='class_display_fees'>".$dat."</span> </h6>";
                $table.="<div class='tableme'><table class='table'>";
                $table.="<tr>
                        <th>No.</th>
                        <th>Votehead</th>
                        <th>TERM ONE</th>
                        <th>TERM TWO</th>
                        <th>TERM THREE</th>
                        <th>Role</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        </tr>";
                        $total1 =0;
                        $total2 =0;
                        $total3 =0;
                        $index = 1;
                while ($row = $res->fetch_assoc()) {
                    $table.="<tr><td><input hidden id='fees_structure_value_".$row['ids']."' value='".json_encode($row)."'>".$index."</td><td class='vote_heads' id = 'expense_name".$row['ids']."'>".$row['expenses']."</td>";
                    $table.="<td class = 't-one' id = 't_one".$row['ids']."'>".$row['TERM_1']."</td>";
                    $table.="<td class = 't-two' id = 't_two".$row['ids']."'>".$row['TERM_2']."</td>";
                    $table.="<td class = 't-three' id = 't_three".$row['ids']."'>".$row['TERM_3']."</td>";
                    $total1+=$row['TERM_1'];
                    $total2+=$row['TERM_2'];
                    $total3+=$row['TERM_3'];
                    $roles = $row['roles'];
                    $table.="<td class='roles_in'>".$roles."</td>";
                    $button = "<p class='link edit_feeser' style='margin:0 auto;font-size:11px;' id='eed".$row['ids']."'><i class='fa fa-pen'></i></p>";
                    $button2 = "<p class='link removef_ee' style='margin:0 auto;font-size:11px;' id='remover".$row['ids']."'><i class='fa fa-trash'></i></p>";
                    $table.="<p class='hide' id='proles".$row['ids']."'>".$row['roles']."</p>";
                    $table.="<td>".$button."</td><td>".$button2."</td></tr>";
                    $index++;
                }
                $table.="<tr><td colspan='2'><b>Total</b></td><td>Ksh ".$total1."</td><td>Ksh ".$total2."</td><td>Ksh ".$total3."</td></tr><tr><td colspan='2' ><b>Grand total </b></td><td>Ksh ".($total1+$total2+$total3)."</td></tr></table></div>";
                echo $table;
            }
        }elseif(isset($_GET['get_levels_fees_structure'])){
            // course level
            $get_levels_fees_structure = $_GET['get_levels_fees_structure'];
            $course_level = $_GET['course_level'];

            // get the courses level
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();

            // hold the select
            $string_to_display = "<select class='form-control ' id='fees_structure_edit_level'> <option value='' hidden>Select..</option>";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }
                    
                    // create the select
                    for ($index=count($all_classes)-1; $index >= 0; $index--) {
                        $string_to_display.="<option ".($course_level == $all_classes[$index] ? "selected" : "")." value='".$all_classes[$index]."'>".className($all_classes[$index])."</option>";
                    }
                }
            }
            $string_to_display.="</select>";
            echo $string_to_display;
        }elseif (isset($_GET['m_pesa_code'])) {
            $mpesa_code = $_GET['m_pesa_code'];
            $select = "SELECT `transaction_code` FROM finance WHERE `transaction_code` = ? AND `mode_of_pay` = 'mpesa' ";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$mpesa_code);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "<p style='font-size:12px;color:red;'>Transaction code already used!</p>";
            }else {
                echo "";
            }
        }elseif (isset($_GET['bank_codes'])) {
            $mpesa_code = $_GET['bank_codes'];
            $select = "SELECT `transaction_code` FROM finance WHERE `transaction_code` = ? AND `mode_of_pay` = 'bank' ";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$mpesa_code);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "<p style='font-size:12px;color:red;'>Transaction code already used!</p>";
            }else {
                echo "";
            }
        }elseif (isset($_GET['get_fee_reminders'])) {
            $class_to_remind = $_GET['get_fee_reminders'];
            $deadline = $_GET['deadline'];
            $date = date_create($deadline);
            $date = date_format($date,"Y-m-d");
            $date = date("D dS M Y",strtotime($date));
            $split_students = explode(",",$class_to_remind);
            $data_to_display = "";
            $xfg = 0;
            for ($xc=0; $xc < count($split_students); $xc++) {
                $xfg++;
                $reminder = getBalance($split_students[$xc],getTerm(),$conn2);
                //create the string to move
                $name_class = explode("^",getName($split_students[$xc]));
                $data_to_display.="<div class='printable_page'>
                    <div class='page_titles'>
                        <h2>".$_SESSION['schoolname']."</h2>
                        <p>P.O BOX ".$_SESSION['po_boxs']." - ".$_SESSION['box_codes']." (".$_SESSION['sch_countys'].")</p>
                        <h4> Motto:".$_SESSION['schoolmotto']."</h4>
                    </div>
                    <div class='student_data'>
                        <p><strong>Student Name:</strong> ".$name_class[0]."</p>
                        <p><strong>Student Id:</strong> ".$split_students[$xc]."</p>
                        <p><strong>Student Class: </strong>".className($name_class[1])."</p>
                    </div>
                    <div class='message_remider'>
                        <p>Dear Parent, <br>You are kindly reminded to clear your fee arrears of Kes <strong>".comma($reminder)."</strong> by <strong>".$date."</strong> .</p><br>
                        <p> <strong> Yours Failthfully <br>Headteacher, <br> ". $_SESSION['schoolname']."</strong></p>
                    </div>
                </div>";
            }
            if ($xfg > 0) {
                echo $data_to_display;
            }else {
                echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p class='' >No students to display! </p>
                        </div>";
            }
        }elseif (isset($_GET['send_message'])) {
            include("../../sms.php");
            $phone_number = $_GET['to'];
            $err = 0;
            if (strlen($phone_number) == 10 || strlen($phone_number) == 9) {
                $phone_number = substr($phone_number,1,10);
            }elseif (strlen($phone_number) == 12) {
                $phone_number = substr($phone_number,4,13);
            }else {
                echo "<p style='color:green;font-size:13px;font-weight:600;margin-top:10px;'>Invalid phone number</p>";
                $err++;
            }
            if ($err == 0) {
                //sendMessage($country_code,$phone_number);
                $school_code = "SchoolSMS";
                $message = "This is a test message!<br> Hilary!";
                // $invalid = sendSmsToClient($phone_number,$message,$school_code);
                // echo $invalid;
            }
        }elseif (isset($_GET['get_class_add_expense'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $classlist = isJson($row['valued']) ? json_decode($row['valued']) : [];
                    $exp_class = [];
                    for ($index=0; $index < count($classlist); $index++) { 
                        array_push($exp_class,$classlist[$index]->classes);
                    }
                    if (count($exp_class) > 0) {
                        $data_to_display = "<div class='classlist'>";
                        $xs = 0;
                        for ($ind=count($exp_class)-1; $ind >=0; $ind--) { 
                            $xs++;
                            $data_to_display.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                                    <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='cl_ass".$exp_class[$ind]."'>".className($exp_class[$ind])."</label>
                                                    <input class='add_expense_check' type='checkbox' name='cl_ass".$xs."' id='cl_ass".$exp_class[$ind]."'>
                                                </div>";
                        }
                        $data_to_display.="</div>";
                        if ($xs>0) {
                            echo $data_to_display;
                        }else {
                            echo "<p class = 'red_notice'>No classes present!</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['add_expense'])) {
            $insert = "INSERT INTO `fees_structure` (`expenses`,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`roles`,`course`) VALUES (?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssssss",$_GET['expense_name'],$_GET['term_one'],$_GET['term_two'],$_GET['term_three'],$_GET['course_level'],$_GET['roles'],$_GET['course']);
            if($stmt->execute()){
                // log text
                $log_message = "Fees structure has been modified. Votehead \"".ucwords(strtolower($_GET['expense_name']))."\" has been added successfully!";
                log_finance($log_message);
                echo "<p class='text-success'>Fees structure has been set successfully!</p>";
            }else{
                echo "<p class='text-danger'>An error occured! Try again later</p>";
            }
        }elseif (isset($_GET['delete_fee'])) {
            $fees_id = $_GET['delete_fee'];
            $delete = "DELETE FROM `fees_structure` WHERE `ids` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$fees_id);
            if($stmt->execute()){

                // log text
                $log_message = "Fees structure has been modified. Votehead deleted successfully!";
                log_finance($log_message);
                echo  "<p class = 'green_notice'>Deleted successfully!</p>";
            }else {
                echo  "<p class = 'red_notice'>Action was not successfull!</p>";
            }
        }elseif (isset($_GET['getclasslist2'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $fees_id = $_GET['fees_id'];
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $classlist = isJson($row['valued']) ? json_decode($row['valued']) : [];
                    $exp_class = [];
                    for ($index=0; $index < count($classlist); $index++) { 
                        array_push($exp_class,$classlist[$index]->classes);
                    }
                    if (count($exp_class) > 0) {
                        $data_to_display = "<div class='classlist'>";
                        $xs = 0;
                        for ($ind=count($exp_class)-1; $ind >=0; $ind--) { 
                            $xs++;
                            $data_to_display.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                                    <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='cla_sse_d".$exp_class[$ind]."'>".className($exp_class[$ind])."</label>
                                                    <input class='update_expense_check_rebound' type='checkbox' name='cla_sse_d".$xs."' id='cla_sse_d".$exp_class[$ind]."'>
                                                </div>";
                        }
                        $data_to_display.="</div><p id='class_fees_ass' class='hide'>".getClassAssignFee($fees_id,$conn2)."</p>";
                        if ($xs>0) {
                            echo $data_to_display;
                        }else {
                            echo "<p class = 'red_notice'>No classes present!</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['update_fees_information'])) {
            // get the data passed
            $expensename = $_GET['fees_name'];
            $old_expense_name = $_GET['old_names'];
            $t_one = $_GET['t_one'];
            $t_two = $_GET['t_two'];
            $t_three = $_GET['t_three'];
            $fee_ids = $_GET['fee_ids'];
            $course = $_GET['course'];
            $course_level = $_GET['course_level'];
            $roles = $_GET['roles'];

            // get the previous fees structures for the entity
            $select = "SELECT `TERM_1`,`TERM_2`,`TERM_3` FROM `fees_structure` WHERE `ids` = ?;";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$fee_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            $term_1_old = "0";
            $term_2_old = "0";
            $term_3_old = "0";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $term_1_old = $row['TERM_1'];
                    $term_2_old = $row['TERM_2'];
                    $term_3_old = $row['TERM_3'];
                }
            }

            $update = "UPDATE `fees_structure` SET `expenses` = ?,`TERM_1` = ? , `TERM_2` = ?, `TERM_3` = ?, `classes` = ?, `course` = ?, `roles` = ? , `date_changed` = ? ,`term_1_old` = ? , `term_2_old` = ?, `term_3_old` = ? WHERE `ids` = ?";
            $stmt = $conn2->prepare($update);
            $date_changed = date("Y-m-d",strtotime("3 hours"));
            $stmt->bind_param("ssssssssssss",$expensename,$t_one,$t_two,$t_three,$course_level,$course,$roles,$date_changed,$term_1_old,$term_2_old,$term_3_old,$fee_ids);
            $execute = $stmt->execute();
            if ($execute) {
                $update = "UPDATE `finance` SET `payment_for` = ? WHERE `payment_for` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("ss",$expensename,$old_expense_name);
                $stmt->execute();
                echo "<p class = 'green_notice'>Update done successfully!</p>";
                

                // log text
                $log_message = "Fees structure has been modified. Votehead \"".ucwords(strtolower($expensename))."\" changed successfully!";
                log_finance($log_message);
            }else {
                echo "<p class = 'red_notice'>An error occured during update!</p>";
            }
        }elseif (isset($_GET['check_expense_name'])) {
            $check_expense_name = $_GET['check_expense_name'];
            $select = "SELECT * FROM `fees_structure` WHERE `expenses` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$check_expense_name);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            if ($rnums > 0) {
                echo "<p class='red_notice'>The votehead name is already used!<br>Try using amother name</p>";
            }else {
                echo "";
            }
        }elseif (isset($_GET['addExpenses'])) {
            $exp_name = $_GET['exp_name'];
            $exp_cat = $_GET['expensecat'];
            $exp_quant = $_GET['quantity'];
            $exp_unit = $_GET['unitcost'];
            $exp_totcost = $_GET['total'];
            $unit_name = $_GET['unit_name'];
            $expense_cash_activity = $_GET['expense_cash_activity'];
            $expense_record_date = $_GET['expense_record_date'];
            $document_number = $_GET['document_number'];
            $new_expense_description = $_GET['new_expense_description'];
            $expense_sub_category = $_GET['expense_sub_category'];
            $date = date("Y-m-d",strtotime($expense_record_date));
            $time = date("H:i:s");
            $insert = "INSERT INTO `expenses` (`expid`,`exp_name`,`exp_category`,`unit_name`,`exp_quantity`,`exp_unit_cost`,`exp_amount`,`expense_date`,`exp_time`,`exp_active`,`expense_categories`,`exp_sub_category`,`document_number`,`expense_description`)VALUES (null,?,?,?,?,?,?,?,?,0,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssssssssss",$exp_name,$exp_cat,$unit_name,$exp_quant,$exp_unit,$exp_totcost,$date,$time,$expense_cash_activity,$expense_sub_category,$document_number,$expense_description);
            if($stmt->execute()){
                // log text
                $log_message = "Expense \"".ucwords(strtolower($exp_name))."\" uploaded successfully!";
                log_finance($log_message);
                echo "<p class='green_notice'>Expense uploaded successfully!<span id='uploaded'></span></p>";
            }else {
                echo "<p class='red_notice'>Error occured during upload!</p>";
            }

        }elseif (isset($_GET['todays_expense'])) {
            // $select = "SELECT `exp_name`,`exp_category`,`unit_name`,`exp_quantity`,`exp_unit_cost`,`exp_amount`,`expense_date`,`exp_time` FROM `expenses` WHERE `expense_date` = ?";
            $select = "SELECT * FROM `expenses` ORDER BY `expid` DESC LIMIT 1000";
            $stmt = $conn2->prepare($select);
            $date = date("Y-m-d");
            // $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $json_2 = "<p class='hide' id='expenses_data_json'>[";
                $data_to_display = "<div class='conts'>
                                    <h6 style='text-align:center;font-size:14px;'><u>Expenses Table</u></h6>
                                </div>
                                <div class='table_holders'>
                                    <table  class='table'>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Name</th>
                                            <th>Expense Category</th>
                                            <th>Units</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                        </tr>";
                                        $xs = 0;
                                        $total_pay = 0;
                while($rows = $result->fetch_assoc()){
                    $xs++;
                    $expense_name = get_expense($rows['exp_category'],$conn2);
                    $data_to_display.="<tr>
                                        <td>".$xs."</td>
                                        <td>".($expense_name != null ? $expense_name['expense_name'] : $rows['exp_name'])."</td>
                                        <td>".$rows['exp_category']."</td>
                                        <td>".$rows['exp_quantity']." ".$rows['unit_name']."</td>
                                        <td>Ksh ".$rows['exp_unit_cost']."</td>
                                        <td><b>Ksh ".$rows['exp_amount']."</b></td>
                                    </tr>";
                                    $total_pay+=$rows['exp_amount'];
                                    // change some fields
                                    $rows['exp_category'] = ucwords(strtolower($rows['exp_category']));
                                    $rows['exp_quantity'] = trim($rows['exp_quantity']);
                                    $rows['date'] = $rows['expense_date'];
                                    $rows['expense_date'] = date("D dS M Y",strtotime($rows['expense_date']));
                                    $rows['expense_name'] = ucwords(strtolower($expense_name != null ? $expense_name['expense_name'] : $rows['exp_category']));
                                    $json_2.=json_encode($rows).",";
                    // $json_2.="{\"exp_name\":\"".trim(ucwords(strtolower($rows['exp_name'])))."\",\"exp_category\":\"".trim(ucwords(strtolower($rows['exp_category'])))."\",\"exp_quantity\":".trim($rows['exp_quantity']).",\"exp_unit_cost\":".trim($rows['exp_unit_cost']).",\"exp_amount\":".trim($rows['exp_amount']).",\"expense_date\":\"".trim(date("dS M Y",strtotime($rows['expense_date'])))."\",\"exp_time\":\"".$rows['exp_time']."\",\"unit_name\":\"".ucwords(strtolower($rows['unit_name']))."\",\"exp_ids\":\"".trim($rows['expid'])."\"},";
                }

                $data_to_display.="<tr><td></td><td></td><td></td><td></td><td>Total</td><td>Ksh ".$total_pay."</td></tr>";
                $data_to_display.="</table></div>";
                $json_2 = substr($json_2,0,(strlen($json_2)-1));
                $json_2.="]</p>";
                if ($xs > 0) {
                    // echo $data_to_display.$json_2;
                    echo $json_2;
                }else {
                    echo "<p class='green_notice' style='text-align:center;'>No expenses recorded today!</p>";
                }
                //get current year
                $startdate = date("Y-m")."-01";
                $enddate = date("Y-m")."-31";
                $select = "SELECT `exp_category`, sum(`exp_amount`) as 'Total', COUNT(`exp_category`) AS 'Record' FROM `expenses`  GROUP BY `exp_category`;";
                // $select = "SELECT `exp_category`, sum(`exp_amount`) as 'Total', COUNT(`exp_category`) AS 'Record' FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`;";
                $stmt = $conn2->prepare($select);
                // $stmt->bind_param("ss",$startdate,$enddate);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    $xs = 0;
                    $data_to_display = "<div class='modepayChartHolder' style='width:400px;height:400px;margin:auto;'><canvas id='expense-charted-in' width = '200px' height='200px'></canvas></div><h5 style='text-align:center;' id='title-charts2'>Expenses Categories</h5><table  class='table'>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Category</th>
                                            <th>Amount</th>
                                            <th>Record(s)</th>
                                        </tr>";
                                        $myjson = "{";
                    while ($row = $result->fetch_assoc()) {
                        $xs++;
                        $expense_name = get_expense($row['exp_category'],$conn2);
                        $data_to_display.="<tr>
                                            <td>".$xs." .</td>
                                            <td>".($expense_name != null ? $expense_name['expense_name'] : $row['exp_category'])."</td>
                                            <td>Kes ".comma($row['Total'])."</td>
                                            <td>".$row['Record']."</td>
                                        </tr>";
                        $myjson.="\"".($expense_name != null ? $expense_name['expense_name'] : $row['exp_category'])."\":\"".trim($row['Total'])."\",";
                    }
                    $myjson = substr($myjson,0,strlen($myjson)-1);
                    $myjson.="}";
                    $data_to_display.="</table><p class='hide' id='table_values2'>$myjson</p>";
                    if ($xs > 0) {
                        echo $data_to_display;
                    }else {
                        echo "<p class='red_notice'>No records found for ".date("M - Y",strtotime($startdate))." !</p>";
                    }
                }
            }
        }elseif (isset($_GET['date_display'])) {
            $select = "SELECT `exp_name`,`exp_category`,`unit_name`,`exp_quantity`,`exp_unit_cost`,`exp_amount`,`expense_date`,`exp_time` FROM `expenses` WHERE `expense_date` = ?";
            $stmt = $conn2->prepare($select);
            $date = $_GET['date_display'];
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $dating = date("l dS M Y",strtotime($date));
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<div class='conts'>
                                    <h6 style='text-align:center;font-size:12px;'><u>".$dating." expenses</u></h6>
                                </div>
                                <div class='table_holders'>
                                    <table  class='table'>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Name</th>
                                            <th>Expense Category</th>
                                            <th>Units</th>
                                            <th>Unit Price</th>
                                            <th>Total Amount</th>
                                        </tr>";
                                        $xs = 0;
                                        $total_pay = 0;
                while($rows = $result->fetch_assoc()){
                    $xs++;
                    $data_to_display.="<tr>
                                        <td>".$xs."</td>
                                        <td>".$rows['exp_name']."</td>
                                        <td>".$rows['exp_category']."</td>
                                        <td>".$rows['exp_quantity']." ".$rows['unit_name']."</td>
                                        <td>Ksh ".$rows['exp_unit_cost']."</td>
                                        <td><b>Ksh ".$rows['exp_amount']."</b></td>
                                    </tr>";
                                    $total_pay+=$rows['exp_amount'];
                }
                $data_to_display.="<tr><td></td><td></td><td></td><td>Total</td><td>Ksh ".$total_pay."</td></tr>";
                $data_to_display.="</table></div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No expenses recorded on the selected date!</p>";
                }
            }
        }elseif (isset($_GET['get_credit_notes'])) {
            // get the credit notes
            $select = "SELECT * FROM `fees_credit_note`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            // display the data
            $credit_note_data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['date_registered'] = date("D dS M Y @ H:i:s",strtotime($row['date_registered']));
                    $row['staff_id'] = ucwords(strtolower(getStaffName($conn,$row['staff_id'])));
                    $student_datas = students_details($row['assigned'],$conn2);
                    $row['assigned'] = $row['status'] == 1 ? (count($student_datas) > 0 ? ucwords(strtolower($student_datas['first_name']." ".$student_datas['second_name'])) : "Null {".$row['assigned']."}") : "Un-assigned";
                    array_push($credit_note_data,$row);
                }
            }
            echo json_encode($credit_note_data);
        }elseif (isset($_GET['un_assign_data'])) {
            $un_assign_data = $_GET['un_assign_data'];
            $un_assign_id = $_GET['un_assign_id'];

            // get the details of payments
            $select = "SELECT * FROM `fees_credit_note` WHERE `id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$un_assign_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // get the transaction id to delete
                    $transaction_id = $row['transaction_id'];

                    // delete that transaction associated with the credit note
                    $delete = "DELETE FROM `finance` WHERE `transaction_id` = ?";
                    $stmt = $conn2->prepare($delete);
                    $stmt->bind_param("s",$transaction_id);
                    $stmt->execute();

                    // update the credit note record
                    $update = "UPDATE `fees_credit_note` SET `assigned` = '0', `status` = '0', `transaction_id` = '0' WHERE `id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$un_assign_id);
                    $stmt->execute();
                }
            }
            echo "<p class='text-success'>Data has been updated successfully!</p>";
        }elseif(isset($_GET['cashflow_statement'])){
            // report type
            $report_type = $_GET['report_type'];

            // get the current and the previous financial year
            $year = date("Y", strtotime($_GET['year']."0101")) * 1;
            if($_GET['report_type'] == "annual_report"){
                $year_1 = ($year*1 - 1);
                $year_2 = ($year_1*1 - 1);
                $year_3 = ($year_2*1 - 1);
                $previous_financial_year_1 = [$year_3,$year_2];
                $previous_financial_year = [$year_2,$year_1];
                $current_financial_year = [$year_1,$year];
                $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
                $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
                $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
                
                $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
                $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
            }elseif($_GET['report_type'] == "quarterly_report_sep"){
                $year_1 = ($year*1 - 1);
                $year_2 = ($year_1*1 - 1);
                $year_3 = ($year_2*1 - 1);
                $previous_financial_year_1 = [$year_3,$year_2];
                $previous_financial_year = [$year_2,$year_1];
                $current_financial_year = [$year_1,$year];
                $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-09-30"))];
                $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-09-30"))];
                $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-09-30"))];
                // echo json_encode($curr_year);
                // return 0;
                $current_display_year = date("M dS Y",strtotime($curr_year[1]));
                $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
            }elseif($_GET['report_type'] == "quarterly_report_dec"){
                $year_1 = ($year*1 - 1);
                $year_2 = ($year_1*1 - 1);
                $year_3 = ($year_2*1 - 1);
                $previous_financial_year_1 = [$year_3,$year_2];
                $previous_financial_year = [$year_2,$year_1];
                $current_financial_year = [$year_1,$year];
                $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-12-31"))];
                $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-12-31"))];
                $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-12-31"))];
                // echo json_encode($curr_year);
                // return 0;
                $current_display_year = date("M dS Y",strtotime($curr_year[1]));
                $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
            }elseif($_GET['report_type'] == "quarterly_report_mar"){
                $year_1 = ($year*1 - 1);
                $year_2 = ($year_1*1 - 1);
                $year_3 = ($year_2*1 - 1);
                $previous_financial_year_1 = [$year_3,$year_2];
                $previous_financial_year = [$year_2,$year_1];
                $current_financial_year = [$year_1,$year];
                $curr_year = [date("Ymd",strtotime((($current_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($current_financial_year[1]*1))."-03-31"))];
                $prev_year = [date("Ymd",strtotime((($previous_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($previous_financial_year[1]*1))."-03-31"))];
                $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-03-31"))];
                // echo json_encode($curr_year);
                // return 0;
                $current_display_year = date("M dS Y",strtotime($curr_year[1]));
                $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
            }else{
                $year_1 = ($year*1 - 1);
                $year_2 = ($year_1*1 - 1);
                $year_3 = ($year_2*1 - 1);
                $previous_financial_year_1 = [$year_3,$year_2];
                $previous_financial_year = [$year_2,$year_1];
                $current_financial_year = [$year_1,$year];
                $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
                $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
                $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
                
                $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
                $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
            }
            
            // start getting the revenue catgories present
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $revenue_category = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $revenue_category = json_decode($row['valued']);
                }
            }

            // start with operating activities
            $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `cash_flow_activities` = '1' AND `reportable_status` = '1' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
            
            // current year operating activities
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $curr_operating_activities = [];
            $operating_revenue_categories = [];
            $fees_id = 2000;
            $fees_category_added = false;
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($operating_revenue_categories,$revenue);
                    }
                    array_push($curr_operating_activities,$row);
                }
            }

            // get the fees for this year
            $student_fees = "SELECT COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
            $stmt = $conn2->prepare($student_fees);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $fees_category = [];
                    $fees_category['revenue_category'] = $fees_id;
                    $fees_category['Records'] = $row['Records'];
                    $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                    $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                    array_push($curr_operating_activities,$fees_category);

                    $revenue = new stdClass();
                    $revenue->category_id = $fees_id;
                    $revenue->category_name = "Rendering of services- fees from students";
                    array_push($operating_revenue_categories,$revenue);
                    $fees_category_added = true;
                }
            }

            // operating revenue previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_operating_activities = [];
            // $max_id = 0;
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($operating_revenue_categories,$revenue);
                    }
                    array_push($prev_operating_activities,$row);
                }
            }

            // get the previous year student fees
            $stmt = $conn2->prepare($student_fees);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $fees_category = [];
                    $fees_category['revenue_category'] = $fees_id;
                    $fees_category['Records'] = $row['Records'];
                    $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                    $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                    array_push($prev_operating_activities,$fees_category);

                    if(!$fees_category_added){
                        $revenue = new stdClass();
                        $revenue->category_id = $fees_id;
                        $revenue->category_name = "Rendering of services- fees from students";
                        array_push($operating_revenue_categories,$revenue);
                    }
                }
            }

            // operating revenue previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_operating_activities_1 = [];
            // $max_id = 0;
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($operating_revenue_categories,$revenue);
                    }
                    array_push($prev_operating_activities_1,$row);
                }
            }

            // get the previous year student fees
            $stmt = $conn2->prepare($student_fees);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $fees_category = [];
                    $fees_category['revenue_category'] = $fees_id;
                    $fees_category['Records'] = $row['Records'];
                    $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                    $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                    array_push($prev_operating_activities_1,$fees_category);

                    if(!$fees_category_added){
                        $revenue = new stdClass();
                        $revenue->category_id = $fees_id;
                        $revenue->category_name = "Rendering of services- fees from students";
                        array_push($operating_revenue_categories,$revenue);
                    }
                }
            }
            // echo $fees_id;

            // start with investing activities
            $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `cash_flow_activities` = '2' AND `reportable_status` = '1' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
            
            // current year investing activities
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $curr_investing_activities = [];
            $investing_revenue_categories = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($investing_revenue_categories,$revenue);
                    }
                    array_push($curr_investing_activities,$row);
                }
            }

            // operating investing previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_investing_activities = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($investing_revenue_categories,$revenue);
                    }
                    array_push($prev_investing_activities,$row);
                }
            }

            // operating investing previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_investing_activities_1 = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($investing_revenue_categories,$revenue);
                    }
                    array_push($prev_investing_activities_1,$row);
                }
            }

            // start with financing activities
            $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `cash_flow_activities` = '3' AND `reportable_status` = '1' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
            
            // current year financing activities
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $curr_financing_activities = [];
            $financing_revenue_categories = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($financing_revenue_categories,$revenue);
                    }
                    array_push($curr_financing_activities,$row);
                }
            }

            // financing activity previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_finance_activities = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($financing_revenue_categories,$revenue);
                    }
                    array_push($prev_finance_activities,$row);
                }
            }

            // financing activity previous year
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $prev_finance_activities_1 = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $row['revenue_category_name'] = "N/A";
                    foreach ($revenue_category as $key => $value) {
                        if($value->category_id == $row['revenue_category']){
                            $row['revenue_category_name'] = $value->category_name;
                        }
                    }
                    if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                        $revenue = new stdClass();
                        $revenue->category_id = $row['revenue_category'];
                        $revenue->category_name = $row['revenue_category_name'];
                        array_push($financing_revenue_categories,$revenue);
                    }
                    array_push($prev_finance_activities_1,$row);
                }
            }

            // get the operating expenses of the previous years and this year
            $curr_year_operating_expenses = [];
            $operating_expense_categories = [];
            $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '1' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($curr_year_operating_expenses,$row);
                    if(!in_array($row['exp_category'],$operating_expense_categories)){
                        array_push($operating_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the previoud years
            $prev_year_operating_expenses = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_operating_expenses,$row);
                    if(!in_array($row['exp_category'],$operating_expense_categories)){
                        array_push($operating_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the second previous years
            $prev_year_operating_expenses_1 = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_operating_expenses_1,$row);
                    if(!in_array($row['exp_category'],$operating_expense_categories)){
                        array_push($operating_expense_categories,$row['exp_category']);
                    }
                }
            }


            // get the operating expenses of the previous years and this year
            $curr_year_investing_expenses = [];
            $investing_expense_categories = [];
            $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '2' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($curr_year_investing_expenses,$row);
                    if(!in_array($row['exp_category'],$investing_expense_categories)){
                        array_push($investing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the previoud years
            $prev_year_investing_expenses = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_investing_expenses,$row);
                    if(!in_array($row['exp_category'],$investing_expense_categories)){
                        array_push($investing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the previoud years
            $prev_year_investing_expenses_1 = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_investing_expenses_1,$row);
                    if(!in_array($row['exp_category'],$investing_expense_categories)){
                        array_push($investing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the operating expenses of the previous years and this year
            $curr_year_financing_expenses = [];
            $financing_expense_categories = [];
            $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '3' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($curr_year_financing_expenses,$row);
                    if(!in_array($row['exp_category'],$financing_expense_categories)){
                        array_push($financing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the previoud years
            $prev_year_financing_expenses = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_financing_expenses,$row);
                    if(!in_array($row['exp_category'],$financing_expense_categories)){
                        array_push($financing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // get the previoud years
            $prev_year_financing_expenses_1 = [];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($prev_year_financing_expenses_1,$row);
                    if(!in_array($row['exp_category'],$financing_expense_categories)){
                        array_push($financing_expense_categories,$row['exp_category']);
                    }
                }
            }

            // display the data now since its ready
            $current_acad_year = [date("Y-m-d",strtotime($curr_year[0]."-07-01")),date("Y-m-d",strtotime($curr_year[1]."-06-30"))];
            $previous_acad_year = [date("Y-m-d",strtotime($prev_year[0]."-07-01")),date("Y-m-d",strtotime($prev_year[1]."-06-30"))];
            // echo json_encode($previous_acad_year[0]);
            $data_to_display = "<div class='financial_statements'>
                                    <h3 class='text-center my-2 fs-16px'><u>Statement of Cashflow From ".date("D dS M Y",strtotime($curr_year[0]."-06-30"))." to year end of ".date("dS M",strtotime($curr_year[1]))." ".date("Y",strtotime($current_acad_year[1]))."</u></h3>
                                    <div class='row'>
                                        <div class='col-md-8'>
                                        </div>
                                        <div class='col-md-2'>
                                            <form target='_blank' action='reports/reports.php' method='post'>
                                                <input type='hidden' name='generate_annual' value='true'>
                                                <input type='hidden' name='year' value='".$_GET['year']."'>
                                                <input type='hidden' name='report_type' value='".$_GET['report_type']."'>
                                                <button type='submit'><i class='fa fa-file-pdf'></i> PDF</button>
                                            </form>
                                        </div>
                                        <div class='col-md-2'>
                                            <form target='_blank' action='reports/reports.php' method='post'>
                                                <input type='hidden' name='generate_annual_excel' value='true'>
                                                <input type='hidden' name='year' value='".$_GET['year']."'>
                                                <input type='hidden' name='report_type' value='".$_GET['report_type']."'>
                                                <button type='submit'><i class='fa fa-file-excel'></i> Excel</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class='finace_headers p-2'>
                                        <div class='conts'><p style='text-align:left;'>Date Generated: ".date("l dS M Y")."</p></div><hr>
                                        ";
            $data_to_display.="<div class='row'>
                                    <div class='col-md-6'><p class='fs-12px text-left' style='text-align:left;'><b>Financial Statements</b></p></div>
                                    <div class='col-md-3'>
                                        <h6 class='fs-12px'><b><small>".$current_display_year."</small></b></h6>
                                    </div>
                                    <div class='col-md-3'>
                                        <h6 class='fs-12px'><b><small>".$previous_display_year."</small></b></h6>
                                    </div>
                                </div>
                                <div class='titles rows'>
                                    <div class='col-md-6'></div>
                                    <div class='col-md-3'>
                                        <h6 class='fs-12px'><b><small>Kes</small></b></h6>
                                    </div>
                                    <div class='col-md-3'>
                                        <h6 class='fs-12px'><b><small>Kes</small></b></h6>
                                    </div>
                                </div>
                            </div>";

                        /**CASH FLOW FROM OPERATING ACTIVITIES */

                        $data_to_display.="<div class='finance_header'>
                                                <p class='title_name'>Cashflow from Operating Activities</p>
                                            </div>";
                        $index = 1;
                        $total_current = 0;
                        $total_previous = 0;
                        $total_previous_1 = 0;

                        // Net increase/(decrease) in cash and cash equivalents
                        $net_increase_curr_year = 0;
                        $net_increase_prev_year = 0;
                        $net_increase_prev_year_1 = 0;
                        if(count($operating_revenue_categories) > 0 ){
                            foreach($operating_revenue_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                // get the current year
                                foreach ($curr_operating_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value->category_id) {
                                        $current_year = $key_value['Total'];
                                        $total_current += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_operating_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value->category_id) {
                                        $previous_year = $key_value['Total'];
                                        $total_previous+=$previous_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_operating_activities_1 as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value->category_id) {
                                        $previous_year_1 = $key_value['Total'];
                                        $total_previous_1+=$previous_year_1;
                                    }
                                }
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                        <div class='col-md-6'>
                                                            <p class=''>".$index.". ".$value->category_name."</p></div>";
                                    $data_to_display.="<div class='col-md-3 text-center'>
                                                            <p>Ksh ".number_format($current_year)."</p>
                                                        </div>";
                                    $data_to_display.="<div class='col-md-3 text-center'>
                                                            <p>Ksh ".number_format($previous_year)."</p>
                                                        </div>
                                                    </div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                                    <p class='text-danger'>No cash flow from operating activities record!</p>
                                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center px-2'>
                                                    <p>Ksh ".number_format($total_current)."</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous)."</p>
                                                </div>
                                            </div>";
                    

                        /**PAYMENTS / EXPENSES **/

                        $data_to_display.="<div class='finance_header mt-2'>
                                            <p class='title_name'>Cashflow Used in Operating Activity</p>
                                        </div>";
                        
                        $index = 1;
                        $total_current_expense = 0;
                        $total_previous_expense = 0;
                        $total_previous_expense_1 = 0;

                        // echo json_encode($operating_expense_categories);
                        // return 0;
                        if(count($operating_expense_categories) > 0 ){
                            foreach($operating_expense_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                $previous_year_1 = 0;
                                // get the current year
                                foreach ($curr_year_operating_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $current_year = $key_value['expense_amount'];
                                        $total_current_expense += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_year_operating_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year = $key_value['expense_amount'];
                                        $total_previous_expense+=$previous_year;
                                    }
                                }
                                // get the previous year
                                foreach ($prev_year_operating_expenses_1 as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year_1 = $key_value['expense_amount'];
                                        $total_previous_expense_1+=$previous_year_1;
                                    }
                                }
                                $expense_name = get_expense($value,$conn2);
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                    <div class='col-md-6'>
                                                        <p class=''>".$index.". ".ucwords(strtolower(($expense_name != null ? $expense_name['expense_name'] : $value)))."</p></div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($current_year)."</p>
                                                    </div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($previous_year)."</p>
                                                    </div></div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                <p class='text-danger'>No Operating Activity Expenses!</p>
                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous_expense)."</p>
                                                </div>
                                            </div>";
                        $data_to_display.="<div class='finance_body_total mt-4 text-primary'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Net Cashflow From Operating Revenue</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current - $total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous  - $total_previous_expense)."</p>
                                                </div>
                                            </div>";
                                            $net_increase_curr_year+=($total_current - $total_current_expense);
                                            $net_increase_prev_year+=($total_previous  - $total_previous_expense);
                                            $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);
                    

                        /**NET CASHFLOW FROM INVESTING ACTIVITIES **/

                        $data_to_display.="<div class='finance_header mt-4'>
                                            <p class='title_name'>Net Cashflow from Investing Activities</p>
                                        </div>";
                        
                        $index = 1;
                        $total_current = 0;
                        $total_previous = 0;
                        $total_previous_1 = 0;
                        // echo json_encode($investing_revenue_categories);
                        // return 0;
                        if(count($investing_revenue_categories) > 0 ){
                            foreach($investing_revenue_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                $previous_year_1 = 0;
                                // get the current year
                                foreach ($curr_investing_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value->category_id) {
                                        $current_year = $key_value['Total'];
                                        $total_current += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_investing_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value) {
                                        $previous_year = $key_value['Total'];
                                        $total_previous+=$previous_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_investing_activities_1 as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value) {
                                        $previous_year_1 = $key_value['Total'];
                                        $total_previous_1+=$previous_year_1;
                                    }
                                }
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                    <div class='col-md-6'>
                                                        <p class=''>".$index.". ".ucwords(strtolower($value->category_name))."</p></div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($current_year)."</p>
                                                    </div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($previous_year)."</p>
                                                    </div></div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                <p class='text-danger'>No Cashflow from Investing Activities!</p>
                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous)."</p>
                                                </div>
                                            </div>";
                    

                        /**PAYMENTS / EXPENSES **/

                        $data_to_display.="<div class='finance_header mt-2'>
                                            <p class='title_name'>Cashflow Used in Investing Activity</p>
                                        </div>";
                        
                        $index = 1;
                        $total_current_expense = 0;
                        $total_previous_expense = 0;
                        $total_previous_expense_1 = 0;

                        // echo json_encode($operating_expense_categories);
                        // return 0;
                        if(count($investing_expense_categories) > 0 ){
                            foreach($investing_expense_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                $previous_year_1 = 0;
                                // get the current year
                                foreach ($curr_year_investing_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $current_year = $key_value['expense_amount'];
                                        $total_current_expense += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_year_investing_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year = $key_value['expense_amount'];
                                        $total_previous_expense+=$previous_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_year_investing_expenses_1 as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year_1 = $key_value['expense_amount'];
                                        $total_previous_expense_1+=$previous_year_1;
                                    }
                                }
                                // expense name
                                $expense_name = get_expense($value,$conn2);
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                    <div class='col-md-6'>
                                                        <p class=''>".$index.". ". (($expense_name != null) ? $expense_name['expense_name'] : $value) ."</p></div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($current_year)."</p>
                                                    </div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($previous_year)."</p>
                                                    </div></div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                <p class='text-danger'>No Investing Activity Expenses!</p>
                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous_expense)."</p>
                                                </div>
                                            </div>";
                        $data_to_display.="<div class='finance_body_total mt-4 text-primary'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Net Cashflow From Investing Revenue</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current - $total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous  - $total_previous_expense)."</p>
                                                </div>
                                            </div>";
                                            $net_increase_curr_year+=($total_current - $total_current_expense);
                                            $net_increase_prev_year+=($total_previous  - $total_previous_expense);
                                            $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);
                    

                        /**NET CASHFLOW FROM FINANCING ACTIVITIES **/

                        $data_to_display.="<div class='finance_header mt-4'>
                                            <p class='title_name'>Net Cashflow from Financing Activities</p>
                                        </div>";
                        
                        $index = 1;
                        $total_current = 0;
                        $total_previous = 0;
                        $total_previous_1 = 0;
                        // echo json_encode($curr_financing_activities);
                        // return 0;
                        if(count($financing_revenue_categories) > 0 ){
                            foreach($financing_revenue_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                $previous_year_1 = 0;
                                // get the current year
                                foreach ($curr_financing_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value->category_id) {
                                        $current_year = $key_value['Total'];
                                        $total_current += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_finance_activities as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value) {
                                        $previous_year = $key_value['Total'];
                                        $total_previous+=$previous_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_finance_activities_1 as $key_activity => $key_value) {
                                    if ($key_value['revenue_category'] == $value) {
                                        $previous_year_1 = $key_value['Total'];
                                        $total_previous_1+=$previous_year_1;
                                    }
                                }
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                    <div class='col-md-6'>
                                                        <p class=''>".$index.". ".ucwords(strtolower($value->category_name))."</p></div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($current_year)."</p>
                                                    </div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($previous_year)."</p>
                                                    </div></div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                <p class='text-danger'>No Financing Activity records!</p>
                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous)."</p>
                                                </div>
                                            </div>";
                    

                        /**PAYMENTS / EXPENSES **/

                        $data_to_display.="<div class='finance_header mt-2'>
                                            <p class='title_name'>Cashflow Used in Financing Activity</p>
                                        </div>";
                        
                        $index = 1;
                        $total_current_expense = 0;
                        $total_previous_expense = 0;
                        $total_previous_expense_1 = 0;

                        // echo json_encode($operating_expense_categories);
                        // return 0;
                        if(count($financing_expense_categories) > 0 ){
                            foreach($financing_expense_categories as $key => $value){
                                $current_year = 0;
                                $previous_year = 0;
                                $previous_year_1 = 0;
                                // get the current year
                                foreach ($curr_year_financing_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $current_year = $key_value['expense_amount'];
                                        $total_current_expense += $current_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_year_financing_expenses as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year = $key_value['expense_amount'];
                                        $total_previous_expense+=$previous_year;
                                    }
                                }
    
                                // get the previous year
                                foreach ($prev_year_financing_expenses_1 as $key_activity => $key_value) {
                                    if ($key_value['exp_category'] == $value) {
                                        $previous_year_1 = $key_value['expense_amount'];
                                        $total_previous_expense_1+=$previous_year_1;
                                    }
                                }
                                
                                // expense name
                                $expense_name = get_expense($value,$conn2);
    
                                // set the display values
                                $data_to_display.="<div class='finance_body'>
                                                    <div class='col-md-6'>
                                                        <p class=''>".$index.". ".(($expense_name != null) ? $expense_name['expense_name'] : $value)."</p></div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($current_year)."</p>
                                                    </div>";
                                $data_to_display.="<div class='col-md-3 text-center'>
                                                        <p>Ksh ".number_format($previous_year)."</p>
                                                    </div></div>";
                                $index++;
                            }
                        }else{
                            $data_to_display.="<div class='finance_body'>
                                <p class='text-danger'>No Financing Activity Expenses!</p>
                                </div>";
                        }
                        // get the totals
                        $data_to_display.="";
                        $data_to_display.="<div class='finance_body_total_2'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Total</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous_expense)."</p>
                                                </div>
                                            </div>";
                        $data_to_display.="<div class='finance_body_total mt-4 text-primary'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Net Cashflow From Financing Revenue</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_current - $total_current_expense)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($total_previous  - $total_previous_expense)."</p>
                                                </div>
                                            </div>";
                                            $net_increase_curr_year+=($total_current - $total_current_expense);
                                            $net_increase_prev_year+=($total_previous  - $total_previous_expense);
                                            $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);
                        $data_to_display.="<div class='finance_body_total mt-4 text-dark'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Net increase/(decrease) in cash and cash equivalents</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_curr_year)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_prev_year)."</p>
                                                </div>
                                            </div>";
                        $data_to_display.="<div class='finance_header mt-2'>
                                                <p class='title_name'>Cash and Cash Equivalents at the Beginning and End of the Period</p>
                                            </div>";
                        $data_to_display.="<div class='finance_body_total mt-4 text-dark'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Cash and Cash Equivalents at the Beginning of the Period</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_prev_year+$net_increase_prev_year_1)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_prev_year_1)."</p>
                                                </div>
                                            </div>";
                        $data_to_display.="<div class='finance_body_total mt-4 text-dark'>
                                                <div class='col-md-6'>
                                                    <p class='name_title'>Cash and Cash Equivalents at the end of the Period</p>
                                                </div>
                                                <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_prev_year_1+$net_increase_prev_year+$net_increase_curr_year)."</p>
                                                </div>
                                                    <div class='col-md-3 text-center'>
                                                    <p>Ksh ".number_format($net_increase_prev_year_1+$net_increase_prev_year)."</p>
                                                </div>
                                            </div>";
                    
            echo $data_to_display;
        }elseif(isset($_GET['income_statement_quarterly'])){
            // annual quater array
            $year = $_GET['year'];
            $year_1 = ($_GET['year']*1)-1;
            $annual_quaters = [];
            $q1a = date("Y-m-d",strtotime($year_1."0701"));
            $q1b = date("Y-m-d",strtotime($year_1."0930"));
            array_push($annual_quaters,[$q1a,$q1b]);
            $q2a = date("Y-m-d",strtotime($year_1."1001"));
            $q2b = date("Y-m-d",strtotime($year_1."1231"));
            array_push($annual_quaters,[$q2a,$q2b]);
            $q3a = date("Y-m-d",strtotime($year."0101"));
            $q3b = date("Y-m-d",strtotime($year."0331"));
            array_push($annual_quaters,[$q3a,$q3b]);
            $q4a = date("Y-m-d",strtotime($year."0401"));
            $q4b = date("Y-m-d",strtotime($year."0630"));
            array_push($annual_quaters,[$q4a,$q4b]);

            // get the term incomes
            $revenue = getOtherRevenueQuaterly($conn2,$year,$annual_quaters);
            
            // get the term income
            $term_income = getTermIncomeQuaterly($annual_quaters,$conn2);
            // echo json_encode($revenue);
            // return 0;
            
            // get the expenses per term
            $term_expense = getExpensesQuaterly($annual_quaters,$conn2);
            
            //get all the expenses names
            $all_expenses = getAllExpenseNames($term_expense);
            
            //get taxes
            $all_taxes = getTaxesQuaterly($annual_quaters,$conn2);
            
            //1. start with the table header
            $data_to_display = "<div class='financial_statements'>
                                <h3 class='text-center my-2 fs-16px'><u>Income Statement Quaterly ".$year."</u></h3>
                                <div class='row'>
                                    <div class='col-md-9'>
                                    </div>
                                    <div class='col-md-3'>
                                        <form target='_blank' action='reports/reports.php' method='post'>
                                            <input type='hidden' name='generate_income_statement_quaterly' value='true'>
                                            <input type='hidden' name='year' value='".$year."'>
                                            <button type='submit'><i class='fa fa-print'></i> Print</button>
                                        </form>
                                    </div>
                                </div>
                                <div class='finace_headers p-2'>
                                    <div class='conts'><p style='text-align:left;'>Date Generated: ".date("l dS M Y")."</p></div><hr>
                                    ".
                                    // <div class='financial_year'><h6>Financial Year <select name='fin_year' id='fin_year'>
                                    //     <option value='2021'>2021</option>
                                    //     <option value='2020'>2020</option>
                                    //     <option value='2019'>2019</option>
                                    //     <option value='2018'>2018</option>
                                    // </select></h6></div>
                                    "<div class='titles '>
                                        <h2 class='fs-16px'>Financial Statements</h2>
                                        <div class='t1'>
                                            <h6 class='fs-12px'><b>Q1 (<small>".date("M-d-Y",strtotime($annual_quaters[0][0]))." - ".date("M-d-Y",strtotime($annual_quaters[0][1]))."</small>)</b></h6>
                                        </div>
                                        <div class='t2'>
                                            <h6 class='fs-12px'><b>Q2 (<small>".date("M-d-Y",strtotime($annual_quaters[1][0]))." - ".date("M-d-Y",strtotime($annual_quaters[1][1]))."</small>)</b></h6>
                                        </div>
                                        <div class='t3'>
                                            <h6 class='fs-12px'><b>Q3 (<small>".date("M-d-Y",strtotime($annual_quaters[2][0]))." - ".date("M-d-Y",strtotime($annual_quaters[2][1]))."</small>)</b></h6>
                                        </div>
                                        <div class='t3'>
                                            <h6 class='fs-12px'><b>Q4 (<small>".date("M-d-Y",strtotime($annual_quaters[3][0]))." - ".date("M-d-Y",strtotime($annual_quaters[3][1]))."</small>)</b></h6>
                                        </div>
                                    </div>
                                </div>";
            $data_to_display.="<div class='finance_header '>
                <div class='conts'>
                    <h5 class='title_statements fs-14px bg-cadet px-2'>Income Statement Quaterly ".$year."</h5>
                </div>
            </div>";

            //the income statement start by displaying the primary Income
            $data_to_display.="<div class='finance_header'>
                                    <p class='title_name'>Primary Income</p>
                                </div>";
            $data_to_display.="<div class='finance_body'>
                                    <p class='name_title'>Operating revenue</p>";
            for ($indes=0; $indes < count($term_income); $indes++) {
                $data_to_display.="<div class='t1'>
                                    <p>Ksh ".comma($term_income[$indes])."</p>
                                </div>";
            }
            $data_to_display.="</div>";
            //end of primary income and start of secondary income even though there is nothing at the moment
            $data_to_display.="<div class='finance_body'>
                                <p class='name_title'>Other Income</p>
                                <div class='t1'>
                                    <p>Ksh ".number_format($revenue[0])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".number_format($revenue[1])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".number_format($revenue[2])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".number_format($revenue[3])."</p>
                                </div>
                            </div>";
            //total the income
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Total Income</p>";
            for ($indes=0; $indes < count($term_income); $indes++) {
                $term_income[$indes] += $revenue[$indes];
                $data_to_display.="<div class='t1'>
                                    <p>Ksh ".comma($term_income[$indes])."</p>
                                </div>";
            }
            $data_to_display.= "</div>";

            //ENTER THE EXPENSES SECTION
            $data_to_display.="<div class='finance_header'>
                                <p class='title_name'>Expenses</p>
                            </div>";
            //create an array with all the expense array list
            $expenses_val = [];
            for ($index=0; $index <= count($all_expenses); $index++) { 
                if ($index == count($all_expenses)) {
                    $expenses_val["Salaries"] = [];
                    break;
                }else {
                    $expenses_val[$all_expenses[$index]] = [];
                }
            }

            //get values per the period given
            $totalExpenses = [];
            for ($index=0; $index < count($term_expense); $index++) {
                //echo "term ".($index+1)." Size is ".count($term_expense[$index])."<br>";
                $total = 0;
                for ($index1=0; $index1 < count($all_expenses); $index1++) {
                    if (checkPresent($term_expense[$index],$all_expenses[$index1])) {
                        $my_val = getValues($term_expense[$index],$all_expenses[$index1]);
                        //echo "- ".$all_expenses[$index1]." = ".$my_val."<br>";
                        array_push($expenses_val[$all_expenses[$index1]],$my_val);
                        $total+=($my_val*1);
                    }else {
                        //echo "- ".$all_expenses[$index1]." = 0<br>";
                        array_push($expenses_val[$all_expenses[$index1]],0);
                    }
                }
                array_push($totalExpenses,$total);
            }
            

            //add a category called salaries and this includes all the salaries the institution distributes
            $salaries = getSalaryExpQuaterly($conn2,$annual_quaters);
            //ADD THE SALARIES ARRAY TO THE GROUP
            array_push($all_expenses,"Salaries");
            array_push($expenses_val["Salaries"],$salaries[0],$salaries[1],$salaries[2],$salaries[3]);
            //add the salaries value to the total value
            for ($intex=0; $intex < count($totalExpenses); $intex++) { 
                $totalExpenses[$intex]+=$salaries[$intex];
            }

            for ($indexes=0; $indexes < count($all_expenses); $indexes++) { 
                $expense_name = get_expense($all_expenses[$indexes],$conn2);
                $data_to_display.="<div class='finance_body'>
                                        <p class='name_title'>". ($expense_name != null ? $expense_name['expense_name'] : $all_expenses[$indexes]) ."</p>
                                        <div class='t1'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][0])."</p>
                                        </div>
                                        <div class='t1'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][1])."</p>
                                        </div>
                                        <div class='t1'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][2])."</p>
                                        </div>
                                        <div class='t1'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][3])."</p>
                                        </div>
                                    </div>";
            }
            //TOTAL ALL THE EXPENSES
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Total Expenses</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($totalExpenses[0])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($totalExpenses[1])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($totalExpenses[2])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($totalExpenses[3])."</p>
                                    </div>
                                </div>";
            //CALCULATE EARNINGS BEFORE TAXES
            //deduct term expenses from term income
            $before_taxes = [];
            for ($index=0; $index < count($term_income); $index++) {
                // add other revenue
                $term_income[$index] += $revenue[$index];

                // add before tx
                $befo_taxes = $term_income[$index] - $totalExpenses[$index];
                array_push($before_taxes,$befo_taxes);
            }
            $data_to_display.= "<div class='finance_body'>
                                    <p class='name_title'>Earning before Tax</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($before_taxes[0])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($before_taxes[1])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($before_taxes[2])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($before_taxes[3])."</p>
                                    </div>
                                </div>";
            
            
            //GET THE TAXES
            $data_to_display.="<div class='finance_header'>
                                <p class='title_name'>Taxes</p>
                            </div>";

            $data_to_display.="<div class='finance_body'>
                                <p class='name_title'>Taxes</p>
                                <div class='t1'>
                                    <p>Ksh ".comma($all_taxes[0])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".comma($all_taxes[1])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".comma($all_taxes[2])."</p>
                                </div>
                                <div class='t1'>
                                    <p>Ksh ".comma($all_taxes[3])."</p>
                                </div>
                            </div>";
            //GET THE NET INCOME
            //net income = income before tax - taxes
            $net_income = [];
            for ($index=0; $index < count($all_taxes); $index++) { 
                $netincome = $before_taxes[$index] - $all_taxes[$index];
                // add other revenues
                array_push($net_income,$netincome);
            }
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Net Income</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($net_income[0])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($net_income[1])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($net_income[2])."</p>
                                    </div>
                                    <div class='t1'>
                                        <p>Ksh ".comma($net_income[3])."</p>
                                    </div>
                                </div>";
            $data_to_display.= "</div>";
            echo $data_to_display;

        }elseif (isset($_GET['incomestatement'])) {
            $year = $_GET['year'] == "null" ? date("Y") : $_GET['year'];
            // echo $year;
            
            // get the term incomes
            $revenue = getOtherRevenue($conn2,$year);
            
            //get the time periods between terms
            $term_arrays = getTermPeriods($conn2, $year);
            foreach ($term_arrays as $key => $value) {
                $term_arrays[$key] = date("Y-m-d",strtotime($year.substr($term_arrays[$key],4)));
            }
            
            //get the income based on the period above
            $term_income = getTermIncome($term_arrays,$conn2);
            
            //get the expenses per term
            $term_expense = getExpenses($term_arrays,$conn2);
            
            //get all the expenses names
            $all_expenses = getAllExpenseNames($term_expense);
            
            //get taxes
            $all_taxes = getTaxes($term_arrays,$conn2);
            
            //term periods 
            $term_per = getTermPeriod($conn2);
            foreach ($term_per as $key => $value) {
                $term_per[$key] = date("Y-m-d",strtotime($year.substr($term_per[$key],4)));
            }

            //get the current term period
            // $years = date("Y");

            //create the table now
            //1. start with the table header
            $data_to_display = "<div class='financial_statements'>
                                <h3 class='text-center my-2 fs-16px'><u>Income Statement Termly ".$year."</u></h3>
                                <div class='row'>
                                    <div class='col-md-9'>
                                    </div>
                                    <div class='col-md-3'>
                                        <form target='_blank' action='reports/reports.php' method='post'>
                                            <input type='hidden' name='generate_income_statement' value='true'>
                                            <input type='hidden' name='year' value='".$year."'>
                                            <button type='submit'><i class='fa fa-print'></i> Print</button>
                                        </form>
                                    </div>
                                </div>
                                <div class='finace_headers p-2'>
                                    <div class='conts'><p style='text-align:left;'>Date Generated: ".date("l dS M Y")."</p></div><hr>
                                    ".
                                    // <div class='financial_year'><h6>Financial Year <select name='fin_year' id='fin_year'>
                                    //     <option value='2021'>2021</option>
                                    //     <option value='2020'>2020</option>
                                    //     <option value='2019'>2019</option>
                                    //     <option value='2018'>2018</option>
                                    // </select></h6></div>
                                    "<div class='titles '>
                                        <h2 class='fs-16px'>Financial Statements</h2>
                                        <div class='t1'>
                                            <h6 class='fs-12px'>Term One (<small>".date("M-d-Y",strtotime($term_per[0]))." - ".date("M-d-Y",strtotime($term_per[1]))."</small>)</h6>
                                        </div>
                                        <div class='t2'>
                                            <h6 class='fs-12px'>Term two (<small>".date("M-d-Y",strtotime($term_per[2]))." - ".date("M-d-Y",strtotime($term_per[3]))."</small>)</h6>
                                        </div>
                                        <div class='t3'>
                                            <h6 class='fs-12px'>Term Three (<small>".date("M-d-Y",strtotime($term_per[4]))." - ".date("M-d-Y",strtotime($term_per[5]))."</small>)</h6>
                                        </div>
                                    </div>
                                </div>";
            $data_to_display.="<div class='finance_header '>
                                    <div class='conts'>
                                        <h2 class='title_statements fs-16px bg-cadet px-2'>Income Statement</h2>
                                    </div>
                                </div>";
            //the income statement start by displaying the primary Income
            $data_to_display.="<div class='finance_header'>
                                    <p class='title_name'>Primary Income</p>
                                </div>";
            $data_to_display.="<div class='finance_body'>
                                    <p class='name_title'>Operating revenue</p>";
            for ($indes=0; $indes < count($term_income); $indes++) {
                $data_to_display.="<div class='t1'>
                                        <p>Ksh ".comma($term_income[$indes])."</p>
                                    </div>";
            }
            $data_to_display.="</div>";
            //end of primary income and start of secondary income even though there is nothing at the moment
            $data_to_display.="<div class='finance_body'>
                                <p class='name_title'>Other Income</p>
                                <div class='t1'>
                                    <p>Ksh ".number_format($revenue[0])."</p>
                                </div>
                                <div class='t2'>
                                    <p>Ksh ".number_format($revenue[1])."</p>
                                </div>
                                <div class='t3'>
                                    <p>Ksh ".number_format($revenue[2])."</p>
                                </div>
                            </div>";
            //total the income
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Total Income</p>";
            for ($indes=0; $indes < count($term_income); $indes++) {
                $term_income[$indes] += $revenue[$indes];
                $data_to_display.="<div class='t1'>
                                    <p>Ksh ".comma($term_income[$indes])."</p>
                                </div>";
            }
            $data_to_display.= "</div>";

            //ENTER THE EXPENSES SECTION
            $data_to_display.="<div class='finance_header'>
                                <p class='title_name'>Expenses</p>
                            </div>";
            //create an array with all the expense array list
            $expenses_val = [];
            for ($index=0; $index <= count($all_expenses); $index++) { 
                if ($index == count($all_expenses)) {
                    $expenses_val["Salaries"] = [];
                    break;
                }else {
                    $expenses_val[$all_expenses[$index]] = [];
                }
            }

            //get values per the period given
            $totalExpenses = [];
            for ($index=0; $index < count($term_expense); $index++) {
                //echo "term ".($index+1)." Size is ".count($term_expense[$index])."<br>";
                $total = 0;
                for ($index1=0; $index1 < count($all_expenses); $index1++) {
                    if (checkPresent($term_expense[$index],$all_expenses[$index1])) {
                        $my_val = getValues($term_expense[$index],$all_expenses[$index1]);
                        //echo "- ".$all_expenses[$index1]." = ".$my_val."<br>";
                        array_push($expenses_val[$all_expenses[$index1]],$my_val);
                        $total+=($my_val*1);
                    }else {
                        //echo "- ".$all_expenses[$index1]." = 0<br>";
                        array_push($expenses_val[$all_expenses[$index1]],0);
                    }
                }
                array_push($totalExpenses,$total);
            }
            

            //add a category called salaries and this includes all the salaries the institution distributes
            $salaries = getSalaryExp($conn2,$term_arrays);
            //ADD THE SALARIES ARRAY TO THE GROUP
            array_push($all_expenses,"Salaries");
            array_push($expenses_val["Salaries"],$salaries[0],$salaries[1],$salaries[2]);
            //add the salaries value to the total value
            for ($intex=0; $intex < count($totalExpenses); $intex++) { 
                $totalExpenses[$intex]+=$salaries[$intex];
            }


            for ($indexes=0; $indexes < count($all_expenses); $indexes++) {
                $expense_name = get_expense($all_expenses[$indexes],$conn2);
                $data_to_display.="<div class='finance_body'>
                                        <p class='name_title'>".($expense_name != null ? $expense_name['expense_name'] : $all_expenses[$indexes])."</p>
                                        <div class='t1'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][0])."</p>
                                        </div>
                                        <div class='t2'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][1])."</p>
                                        </div>
                                        <div class='t3'>
                                            <p>Ksh ".comma($expenses_val[$all_expenses[$indexes]][2])."</p>
                                        </div>
                                    </div>";
            }
            //TOTAL ALL THE EXPENSES
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Total Expenses</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($totalExpenses[0])."</p>
                                    </div>
                                    <div class='t2'>
                                        <p>Ksh ".comma($totalExpenses[1])."</p>
                                    </div>
                                    <div class='t3'>
                                        <p>Ksh ".comma($totalExpenses[2])."</p>
                                    </div>
                                </div>";
            //CALCULATE EARNINGS BEFORE TAXES
            //deduct term expenses from term income
            $before_taxes = [];
            for ($index=0; $index < count($term_income); $index++) {
                // add other revenue
                // $term_income[$index] += $revenue[$index];

                // add before tx
                $befo_taxes = $term_income[$index] - $totalExpenses[$index];
                array_push($before_taxes,$befo_taxes);
            }
            $data_to_display.= "<div class='finance_body'>
                                    <p class='name_title'>Earning before Tax</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($before_taxes[0])."</p>
                                    </div>
                                    <div class='t2'>
                                        <p>Ksh ".comma($before_taxes[1])."</p>
                                    </div>
                                    <div class='t3'>
                                        <p>Ksh ".comma($before_taxes[2])."</p>
                                    </div>
                                </div>";
            
            
            //GET THE TAXES
            $data_to_display.="<div class='finance_header'>
                                <p class='title_name'>Taxes</p>
                            </div>";

            $data_to_display.="<div class='finance_body'>
                                <p class='name_title'>Taxes</p>
                                <div class='t1'>
                                    <p>Ksh ".comma($all_taxes[0])."</p>
                                </div>
                                <div class='t2'>
                                    <p>Ksh ".comma($all_taxes[1])."</p>
                                </div>
                                <div class='t3'>
                                    <p>Ksh ".comma($all_taxes[2])."</p>
                                </div>
                            </div>";
            //GET THE NET INCOME
            //net income = income before tax - taxes
            $net_income = [];
            for ($index=0; $index < count($all_taxes); $index++) { 
                $netincome = $before_taxes[$index] - $all_taxes[$index];
                // add other revenues
                array_push($net_income,$netincome);
            }
            $data_to_display.="<div class='finance_body_total'>
                                    <p class='name_title'>Net Income</p>
                                    <div class='t1'>
                                        <p>Ksh ".comma($net_income[0])."</p>
                                    </div>
                                    <div class='t2'>
                                        <p>Ksh ".comma($net_income[1])."</p>
                                    </div>
                                    <div class='t3'>
                                        <p>Ksh ".comma($net_income[2])."</p>
                                    </div>
                                </div>";
            
            $data_to_display.= "</div>";
            echo $data_to_display;

        }elseif (isset($_GET['mystaff'])) {
            $select = "SELECT `fullname`,`user_id` FROM `user_tbl` WHERE `payroll` = 'disabled' AND `school_code` = ?;";
            $stmt = $conn->prepare($select);
            $school_code = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$school_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='red_notice'>No staff present to enroll!</p>";
            if ($result) {
                $data_to_display = "<select class='form-control' name='staff_l' id='staff_l'>
                                        <option value='' hidden>Select staff</option>";
                                        $xs =0;
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="<option value='".$row['user_id']."'>".ucwords(strtolower($row['fullname']))."</option>";
                    $xs++;
                }
                $data_to_display.="</select>";
            }
            echo $data_to_display;
        }elseif (isset($_GET['enroll_payroll'])) {
            $insert = "INSERT INTO `payroll_information` (`staff_id`,`current_balance`,`current_balance_monNyear`,`salary_amount`,`effect_month`,`salary_breakdown`) VALUES (?,?,?,?,?,?)";
            $staff_id = $_GET['staff_id'];
            $salary_amount = $_GET['salary_amount'];
            $effect_year = $_GET['effect_year'];
            $balance = $_GET['balance'];
            $effect_month = $_GET['effect_month'];
            $salary_breakdown = $_GET['salary_breakdown'];
            $monYear = $effect_month.":".$effect_year;
            $present = checkEnrolled($conn2,$staff_id);
            if (!$present) {
                $stmt = $conn2->prepare($insert);
                $stmt->bind_param("ssssss",$staff_id,$balance,$monYear,$salary_amount,$monYear,$salary_breakdown);
                if($stmt->execute()){
                    $update = "UPDATE `user_tbl` SET `payroll` = 'enabled' WHERE `user_id` = ?";
                    $stmt = $conn->prepare($update);
                    $stmt->bind_param("s",$staff_id);
                    if($stmt->execute()){
                        echo "<p class='green_notice'>Staff information uploaded successfully!</p>";
                    }else {
                        echo "<p class='red_notice'>An error occured during update!</p>";
                    }
                }else {
                    echo "<p class='red_notice'>An error occured during update!</p>";
                }
            }else {
                echo "<p class='red_notice border border-danger p-1 my-2'>The user is already enrolled!</p>";
            }
        }elseif (isset($_GET['getEnrolled'])) {
            $select = "SELECT * FROM `payroll_information`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<table class='table'>
                                    <tr>
                                        <th>No.</th>
                                        <th>Staff Name</th>
                                        <th>Date Enrolled</th>
                                        <th>Gross Salary</th>
                                        <th>P.A.Y.E</th>
                                        <th>N.H.I.F</th>
                                        <th>N.S.S.F</th>
                                        <th>Allowances</th>
                                        <th>Deductions</th>
                                        <th>Net Pay</th>
                                        <th>Options</th>
                                    </tr>";
                                    $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    //get the last one on the list for year and time
                    $xs++;
                    $balance_for = explode(",",$row['current_balance_monNyear']);
                    $month_N_Year = explode(":",$balance_for[(count($balance_for)-1)]);
                    //GET THE LAST ONE ON SALARIES
                    $salary_amount = explode(",",$row['salary_amount']);
                    $curr_salary = $salary_amount[(count($salary_amount)-1)];
                    //get the KRA NHIF NSSF Allowances Deductions Net Pay
                    $paye = 0;
                    $nhif = 0;
                    $nssf = 0;
                    $allowances = 0;
                    $deduction = 0;
                    $net_pay = $curr_salary;
                    $salary_breakdown = $row['salary_breakdown'];
                    $gross_salary = 0;
                    $year = date("Y");
                    $paye_relief = 0;
                    $nhif_relief = 0;

                    // get the breakdown
                    if (isJson_report_fin($salary_breakdown)) {
                        $salary_breakdown = json_decode($salary_breakdown);
                        $salary_break = is_array($salary_breakdown) ? $salary_breakdown[count($salary_breakdown)-1] : $salary_breakdown;

                        // echo json_encode($salary_break);
                        // assign the deduction and contributions
                        $gross_salary = $salary_break->gross_salary;
                        $nhif = $salary_break->deduct_nhif == "yes" ? getNHIFContribution($gross_salary) : 0;
                        $nhif_relief = ($salary_break->deduct_nhif == "yes" && $salary_break->nhif_relief == "yes") ? (($nhif*0.15) > 255 ? 255 : ($nhif*0.15)) : 0;
                        $allowances = $salary_break->allowances;
                        $personal_relief = $salary_break->personal_relief;
                        $deduct_paye = $salary_break->deduct_paye;
                        $year = $salary_break->year;
                        $deductions = isset($salary_break->deductions) ? $salary_break->deductions : [];

                        if (is_array($deductions)) {
                            for ($index=0; $index < count($deductions); $index++) { 
                                $deduction += $deductions[$index]->value;
                            }
                        }

                        // get NSSF amount
                        if($salary_break->nssf_rates == "teir_1"){
                            $nssf = 360;
                            $nssf_type = "Teir 1";
                        }elseif($salary_break->nssf_rates == "teir_1_2"){
                            $nssf = 1080;
                            $nssf_type = "Teir 1 & 2";
                        }elseif($salary_break->nssf_rates == "teir_old"){
                            $nssf = 200;
                            $nssf_type = "Old Rates";
                        }else{
                            $nssf = 0;
                            $nssf_type = "none";
                        }

                        // get total allowances
                        $total_allowance = 0;
                        if (is_array($allowances)) {
                            for ($index=0; $index < count($allowances); $index++) { 
                                $total_allowance += $allowances[$index]->value;
                            }
                        }
                        $allowances = $total_allowance;
                        
                        // get taxable income 
                        $taxable_income = ($gross_salary + $total_allowance) - $nssf;
                        
                        // calculate P.A.Y.E
                        $paye = ($salary_break->deduct_paye == "yes") ? getPaye($taxable_income,$year) : 0;
                        
                        // get reliefs
                        $paye_relief = ($salary_break->deduct_paye == "yes" && $salary_break->personal_relief == "yes") ? 2400 : 0;
                    }
                    $data_to_display.="<tr>
                                            <td>".$xs.". </td>
                                            <td id='namd".$row['staff_id']."'>".ucwords(strtolower(getStaffName($conn,$row['staff_id'])))."</td>
                                            <td>".date("M Y",strtotime("01-".str_replace(":","-",explode(",",$row['effect_month'])[0])))."</td>
                                            <span class='hide' id='montly_sal".$row['staff_id']."'>Kes ".comma($curr_salary)."</span>
                                            <span class='hide' id='salo_balance".$row['staff_id']."'>Kes ".comma($row['current_balance'])."</span>
                                            <td>Kes ".number_format($gross_salary)."</td>
                                            <td><b>PAYE</b>: Kes ".number_format($paye)." <br><b>Relief</b>: Kes ".number_format($paye_relief)." </td>
                                            <td><b>NHIF</b>: Kes ".number_format($nhif)." <br><b>Relief</b>: Kes ".number_format($nhif_relief)."</td>
                                            <td>Kes ".number_format($nssf)."</td>
                                            <td>Kes ".number_format($allowances)."</td>
                                            <td>Kes ".number_format($deduction)."</td>
                                            <td>Kes ".number_format($net_pay)."</td>
                                            <span class='hide' id='salo".$row['staff_id']."'>".$curr_salary."</span>
                                            <span class='hide' id='lastpay".$row['staff_id']."'>".$month_N_Year[0]." ".$month_N_Year[1]."</span>
                                            <td '><span  class='edit_salary link'  id = 'stf".$row['staff_id']."' style='font-size:12px;'> <i class='fa fa-pen'></i> Edit</span> / <span class='link view_salos_pay' style='font-size:12px;'  id='viw".$row['staff_id']."'>  <i class='fa fa-eye'></i> View</span> / <span class='link pay_staff_salo' style='font-size:12px';  id='lipa".$row['staff_id']."'>  <i class='fa fa-coins'></i> Pay</span></td>
                                        </tr>";
                }
                $data_to_display.="</table>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<div class='conts' style='margin:auto;width:250px;display:flex;flex-direction:column;align-items:center;'><p class='green_notice' style='text-align:center;'>There are no staff enrolled in the payroll system currently!<br><p class='block_btn enroll_pays' id='enroll_staff_btn'><i class=' fa fa-plus'></i> Enroll staff</p></p>";
                }
            }else {
                echo "<p class='red_notice' style='text-align:center;'>There are no staff enrolled in the payroll system currently!</p>";
            }
        }elseif (isset($_GET['change_salo'])) {
            $id = $_GET['id'];
            $new_amnt = $_GET['new_amnt'];
            $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $str = $row['salary_amount'];
                    $old_period = $row['effect_month'];
                    $mon = date("M");
                    $year = date("Y");
                    $new_period = $old_period.",".$mon.":".$year;
                    $new_sal = $str.",".$new_amnt;

                    // take the old salary breakdown, update it withe the new salary breakdown 
                    // if its an array add the new salary breadown if not make it and array and add the new salary breakdown
                    $salary_breakdown = $row['salary_breakdown'];
                    $salo_breakdown = $_GET['salo_breakdown'];

                    // change to json data
                    $salary_data_update = [];
                    if (isJson($salary_breakdown)) {
                        $salary_breakdown = json_decode($salary_breakdown);
                        // check if its an array
                        if (is_array($salary_breakdown)) {
                            $new_json = json_decode($salo_breakdown);
                            array_push($salary_breakdown, $new_json);
                            $salary_data_update = $salary_breakdown;
                        }else{
                            array_push($salary_data_update, $salary_breakdown);
                            $new_json = json_decode($salo_breakdown);
                            array_push($salary_data_update, $new_json);
                        }
                    }
                    $salary_data_update = count($salary_data_update) > 0 ? json_encode($salary_data_update) : "";
                    $update = "UPDATE `payroll_information` set `salary_amount` = ?, `effect_month` = ?, `salary_breakdown` = ? WHERE `staff_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ssss",$new_sal,$new_period,$salary_data_update,$id);
                    if($stmt->execute()){
                        echo "<p class='green_notice'>Update was done successfully!</p>";
                    }else {
                        echo "<p class='red_notice'>An error occured!</p>";
                    }
                }else {
                    echo "<p class='red_notice'>An error occured!</p>";
                }
            }else {
                echo "<p class='red_notice'>An error occured!</p>";
            }
        }elseif (isset($_GET['unenroll_user'])) {
            $update = "DELETE FROM `payroll_information` WHERE `staff_id` = ?";
            $userids = $_GET['userids'];
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$userids);
            if($stmt->execute()){
                // delete all salary payments done to that staff
                $delete = "DELETE FROM `salary_payment` WHERE `staff_paid` = '".$userids."'";
                $stmt = $conn2->prepare($delete);
                $stmt->execute();

                // update their profile
                $update = "UPDATE `user_tbl` SET `payroll` = 'disabled' WHERE `user_id` = ?";
                $stmt = $conn->prepare($update);
                $stmt->bind_param("s",$userids);
                if($stmt->execute()){
                    echo "<p class='green_notice'>The staff has been successfully un-enrolled!</p>";
                    
                }else {
                    echo "<p class='green_notice'>An error has occured!</p>";
                }
            }else {
                echo "<p class='green_notice'>An error has occured!</p>";
            }
        }elseif (isset($_GET['checkBalance'])) {
            $id = $_GET['ids'];
            $tot = salaryBalanceToBePaid($id,$conn2);
            echo "Kes ".comma($tot);
        }elseif (isset($_GET['salary_details'])) {
            $salary_details = $_GET['salary_details'];
            $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$salary_details);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $row['date_today'] = date("YmdHis");
                    $row['salary_breakdown'] = str_replace('"',"'",$row['salary_breakdown']);
                    $data_to_display = json_encode($row);
                }
            }
            echo $data_to_display;
        }
        elseif (isset($_GET['pay_staff'])) {
            //values from the users
            $staff_id = $_GET['staff_id'];
            $mode_of_pay = $_GET['mode_of_pay'];
            $transactioncode = $_GET['transactioncode'];
            $amount = $_GET['amount'];
            $amount_recieved = $amount;
            $staffname = getStaffName($conn,$staff_id);

            // get the balance after the new amount is added
            $last_paid = "";
            $balance_left = 0;
            $current_month = date("Y-m-d",strtotime("-1 month"));

            // get the staff payroll information
            $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $last_paid = $row['current_balance_monNyear'];
                    $balance_left = $row['current_balance'];
                }
            }

            // set the balance
            $balance_that_was_left = $balance_left;

            $last_paid = explode(":",$last_paid);
            $start_date = date("Y-m-d",strtotime("01-".$last_paid[0]."-".$last_paid[1]));
            $lastdate_paid = date("Y-m-d",strtotime("01-".$last_paid[0]."-".$last_paid[1]));
            // this is the current date where salaries are to be paid
            $current_salary_month = date("Y-m-d",strtotime("01-".date("m")."-".date("Y")));

            // get where the amount paid will reach
            $salo_balance = 0;
            // hold advances
            $hold_advances = 0;
            $months_increase = 0;
            while (true) {
                // salary is either the normal monthly salo or 
                $salary = ($start_date==$lastdate_paid) ? $balance_left : getSalary($start_date,$conn2,$staff_id);
                // echo $salary." salaries ".$balance_left." ".$start_date." -- ".$lastdate_paid."<br>";
                // if salary is less than the amount paid deduct it and move to the next month
                if ($amount_recieved >= $salary) {
                    $amount_recieved-=$salary;
                    
                    // check if the salary continues beyond the current date to determine advance
                    if ($start_date >= $current_salary_month) {
                        $hold_advances += $salary;
                        // echo "Advance dates start ".$start_date." current ".$current_salary_month." amount ".$salary."<br>";
                    }
                    
                    // add a month to the current date
                    $date = date_create($start_date);
                    date_add($date,date_interval_create_from_date_string("1 Month"));
                    $start_date = date_format($date,"Y-m-d");
                    $months_increase++;
                }else{
                    $salo_balance = $salary - $amount_recieved;
                    if ($start_date >= $current_salary_month) {
                        $hold_advances += $amount_recieved;
                        // echo "Advance dates start ".$start_date." current ".$current_salary_month." amount ".$amount_recieved."<br>";
                    }
                    $amount_recieved = 0;
                }
                if ($amount_recieved == 0) {
                    break;
                }
            }


            // when next the client is to be paid
            $next_pay_date = date("M:Y",strtotime($start_date));

            // this is the salary balance
            $new_balance = 0;
            if ($salo_balance > 0) {
                $new_balance = $salo_balance;
            }else{
                $new_balance = getSalary($next_pay_date,$conn2,$staff_id);
            }
            $update = "UPDATE `payroll_information` SET `current_balance` = ?,`current_balance_monNyear` = ? WHERE `staff_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("sss",$new_balance,$next_pay_date,$staff_id);
            if($stmt->execute()){
                //insert the payments
                $insert = "INSERT INTO `salary_payment` (`staff_paid`,`amount_paid`,`mode_of_payment`,`payment_code`,`date_paid`,`time_paid`) VALUES (?,?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $dates = date("Y-m-d");
                $time = date("H:i:s");
                $stmt->bind_param("ssssss",$staff_id,$amount,$mode_of_pay,$transactioncode,$dates,$time);
                if($stmt->execute()){
                    echo "<p class='green_notice'>Payments successfully done</p>";
                }else {
                    echo "<p class='red_notice'>An error has occured!<br>Try again later!</p>";
                }
            }else {
                echo "<p class='red_notice'>An error has occured!<br>Try again later!</p>";
            }
            echo "<p><b>".$staffname."</b> next balance is <b>Kes ".comma($new_balance)."</b> for <b>".date("M Y",strtotime($start_date))."</b> and an advance of <b>Kes ".comma($hold_advances)."</b></p>";

            // process advances
            // if the months that have been added are more than one we can check for all advances that are for that month
            if ($months_increase > 0) {
                // check for all advances that the staff has received
                $select = "SELECT * FROM `advance_pay` WHERE `employees_id` = '".$staff_id."' AND `balance_left` > '0'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $advances = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($advances,$row);
                    }
                }

                // process the advances
                $start_month = $lastdate_paid;
                // loop through each advance and process the payment
                for ($indexes=0; $indexes < count($advances); $indexes++) {
                    $individual_start = $start_month;
                    // start by checking the months effect date
                    $monthly_amount = $advances[$indexes]['amount'];
                    $installments = $advances[$indexes]['installments'];
                    $payment_breakdown = $advances[$indexes]['payment_breakdown'];
                    $month_effect = $advances[$indexes]['month_effect'];
                    $advance_id = $advances[$indexes]['advance_id'];
                    // if the time they were last paid is greater than the month effect
                    // process that advance
                    $month_effect = date("Y-m-d",strtotime($month_effect."-01"));
                    // echo $start_month." > ".$month_effect;
                    if ($start_month >= $month_effect) {
                        // check how many installments are left to complete the payment
                        $pay_breakdown = ($payment_breakdown != null && $payment_breakdown != "") ? json_decode($payment_breakdown) : [];

                        // get the payments breakdown size, that is the time the client has paid for their advance
                        $number_of_times_paid = count($pay_breakdown);
                        if ($installments > $number_of_times_paid) {
                            // do the deductions of the advances
                            $remaining_balance = $installments - $number_of_times_paid;

                            // get installment price by deviding all the installments to the amount
                            $payment = round($monthly_amount/$installments,2);

                            // create a loop to add the lastest payments to the payment breakdowns
                            $total_salo_paid = $number_of_times_paid * $payment;
                            for ($indexing=0; $indexing < $remaining_balance; $indexing++) {
                                if ($indexing == $months_increase) {
                                    break;
                                }
                                $total_salo_paid += $payment;
                                $pay_data = array("paydate"=>date("YmdHis"),"payment_for"=>date("M:Y",strtotime($individual_start)),"amount_paid" => $payment);

                                // increase the months by 1
                                $date = date_create($individual_start);
                                date_add($date,date_interval_create_from_date_string("1 Month"));
                                $individual_start = date_format($date,"Y-m-d");
                                array_push($pay_breakdown,$pay_data);
                            }
                            $payment_data = json_encode($pay_breakdown);
                            $balance = $monthly_amount - $total_salo_paid;

                            // proceed and update the payment details for the employee balance and payment breakdown
                            $update = "UPDATE `advance_pay` SET `balance_left` = '".$balance."', `payment_breakdown` = '".$payment_data."' WHERE `advance_id` = '".$advance_id."'";
                            $stmt = $conn2->prepare($update);
                            $stmt->execute();
                        }
                    }
                    // break;
                }
                // check for fees credit note on the deductions of this user
                $start_dates = $lastdate_paid;

                // loop through the salary months and get the fees credit note deduction
                // its limited by the number of months that the payment has been made for..
                for ($index=0; $index < $months_increase; $index++) {
                    if ($balance_that_was_left == 0 && $index == 0) {
                        // add a month to the current date
                        $date = date_create($start_dates);
                        date_add($date,date_interval_create_from_date_string("1 Month"));
                        $start_dates = date_format($date,"Y-m-d");
                        continue;
                    }
                    $salary_breakdown = getMySalaryBreakdown($staff_id,$conn2,$start_dates);
                    // check if the user pays for credit note in the deductions and by how much
                    if ($salary_breakdown != null) {
                        $deduct = $salary_breakdown->deductions;
                        $fee_credit_note = 0;
                        // echo json_encode($deduct)." ".$deduct[0]->deductions;
                        if (is_array($deduct)) {
                            for($indx = 0; $indx < count($deduct); $indx++){
                                if ($deduct[$indx]->name == "Fees Credit Note") {
                                    $fee_credit_note = $deduct[$indx]->value;
                                }
                            }
                        }
                        // deduct credit note
                        if ($fee_credit_note > 0) {
                            // record the payment in the database
                            $un_assigned = 0;
                            $date_reg = date("YmdHis");
                            $month = date("M:Y",strtotime($start_dates));
                            $insert = "INSERT INTO `fees_credit_note` (`amount`,`month`,`staff_id`,`assigned`,`date_registered`) VALUES (?,?,?,?,?)";
                            $stmt  = $conn2->prepare($insert);
                            $stmt->bind_param("sssss",$fee_credit_note,$month,$staff_id,$un_assigned,$date_reg);
                            $stmt->execute();
                        }
                    }

                    // add a month to the current date
                    $date = date_create($start_dates);
                    date_add($date,date_interval_create_from_date_string("1 Month"));
                    $start_dates = date_format($date,"Y-m-d");
                }
            }
        }elseif (isset($_GET['get_expenses'])) {
            $years = $_GET['years'];
            $months = $_GET['months'];
            $startdate = date("Y-m-d",strtotime($years."-".$months."-01"));
            $enddate = date("Y-m-d",strtotime($years."-".$months."-31"));
            $select = "SELECT `exp_category`, sum(`exp_amount`) as 'Total', COUNT(`exp_category`) AS 'Record' FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`;";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$startdate,$enddate);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $xs = 0;
                $data_to_display = "<hr><div class='modepayChartHolder' style='width:400px;height:400px;margin:auto;'><canvas id='expense-charts-in' width = '200px' height='200px'></canvas></div><h2 style='text-align:center;' id='title-charts'>Expenses for ".date("M-Y",strtotime($startdate))."</h2><table class='table'>
                                    <tr>
                                        <th>No.</th>
                                        <th>Expense Category</th>
                                        <th>Amount</th>
                                        <th>Record(s)</th>
                                    </tr>";
                                    $myjson = "{";
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<tr>
                                        <td>".$xs." .</td>
                                        <td>".$row['exp_category']."</td>
                                        <td>Kes ".comma($row['Total'])."</td>
                                        <td>".$row['Record']."</td>
                                    </tr>";
                    $myjson.="\"".$row['exp_category']."\":\"".$row['Total']."\",";
                }
                $myjson = substr($myjson,0,strlen($myjson)-1);
                $myjson.="}";
                $data_to_display.="</table><p class='hide' id='table_values'>$myjson</p>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No records found for ".date("M - Y",strtotime($startdate))." !</p>";
                }
            }
        }elseif (isset($_GET['view_salo_history'])) {
            $staff_id = $_GET['staff_id'];
            $curr_year = $_GET['curr_year'];
            $salary_details_per_month = getSalaryDetails($conn2,$staff_id);
            // echo json_encode($salary_details_per_month);
            //get all the amount the staff has been paid as salo
            $total_salo = getTotalSalo($conn2,$staff_id);
            //get the first month staff was paid
            $firstpay_record = getFirstPayDate($conn2,$staff_id);
            $current_bal = getCurrentBalTime($conn2,$staff_id);
            $lasttimepaid = explode(",",$current_bal);
            $times = explode(":",$firstpay_record);
            //if the current year is less than the given year we display the else code
            if ($times[1] <= $curr_year) {
                //get the first time the staff was paid
                if ($total_salo > 0) {
                    $data_to_display = "";
                    $data_to_display .="
                    <div class='conts' style='margin:10px 0;'>
                        <p class='embold'>Staff name: <span class='color_brown'>".getStaffName($conn,$staff_id)."</span></p>
                        <p class='embold'>Year : <span class='color_brown'>$curr_year</span></p>
                        <p class='embold'>Total salary paid: <span class='color_brown'>Kes ".comma($total_salo)."</span></p>
                    </div><div class='my_salo-flexbox'>";
                    // loop through the salary breakdown of each user
                    $start_dates = date("Y-m-d",strtotime("01-01-".$curr_year));
                    for ($counter=0; $counter < 12; $counter++) { 
                        $keys = date("M-Y",strtotime($start_dates));

                        // look if the key has some data in the salary breakdown
                        $salo_brek_down = getMonthlySaloBreak($salary_details_per_month,$keys);
                        $total_paid = 0;
                        $fund_details = "<p class='text-secondary'><u>Payment Breakdown</u></p>";
                        if($salo_brek_down != null){
                            if (count($salo_brek_down) > 0) {
                                $fund_details.="";
                                for ($indx=0; $indx < count($salo_brek_down); $indx++) {
                                    $fund_dets = $salo_brek_down[$indx];
                                    $mode_of_payment = ($fund_dets['mode_of_payment'] != "cash" && $fund_dets['mode_of_payment'] != "mpesa") ?"<b class='text-success'>-b</b>":($fund_dets['mode_of_payment'] == "mpesa" ? "<b class='text-success'>-m</b>":"<b class='text-success'>-c</b>");
                                    $fund_details.="<p>- ".comma($fund_dets['amount_paid'])." (".date("M dS y",strtotime($fund_dets['date_paid']))." - ".date("H:iA",strtotime($fund_dets['time_paid'])).") ".$mode_of_payment."</p>";
                                    $total_paid+=($fund_dets['amount_paid']*1);
                                }
                            }else{
                                $fund_details .= "<p class='green_notice p-1 border border-success'> No payment records found!</p>";
                            }
                        }else{
                            $fund_details .= "<p class='green_notice p-1 border border-success'> No payment records found!</p>";
                        }
                        $emp_salary = getMySalary($staff_id,$conn2,$start_dates);
                        $balance = $emp_salary-$total_paid;
                        // get allowances
                        $allowances_bonus = getAllowanceBonusRelief($staff_id,$conn2,$start_dates);
                        // echo $keys." ".json_encode($allowances_bonus)."<br>count ".count($allowances_bonus[0])."<hr>";
                        $allowance_display = "";
                        $total_allowance = 0;
                        if (count($allowances_bonus[0]) > 0 && $total_paid > 0) {
                            for ($ind=0; $ind < count($allowances_bonus[0]); $ind++) { 
                                foreach ($allowances_bonus[0][$ind] as $key => $value) {
                                    $allowance_display .= "<p>- ".ucwords(strtolower(str_replace("_"," ",$key)))." => Kes ".comma($value)."</p>";
                                    $total_allowance+=$value;
                                }
                            }
                            // for ($counting=0; $counting < count($allowances_bonus[0]); $counting++) { 
                            //     $allowance_display.="<p>- ".$allowances_bonus[0][$counting]."</p>";
                            // }
                            $allowance_display.="<p class='bordered_bottom'></p><p><b>Total Allowances: Kes ".comma($total_allowance)."</b></p>";
                        }else{
                            $allowance_display = "<p class='text-success p-1 border border-success'>No allowance records available!</p>";
                        }
                        // get reliefs
                        $display_reliefs = "";
                        $staff_reliefs = $allowances_bonus[1];
                        // echo json_encode($staff_reliefs)."<br>";
                        if(count($staff_reliefs) > 0 && $total_paid > 0){
                            $jumla2 = 0;
                            for ($ind=0; $ind < count($staff_reliefs); $ind++) { 
                                foreach ($staff_reliefs[$ind] as $key => $value) {
                                    $display_reliefs .= "<p>- ".ucwords(strtolower(str_replace("_"," ",$key)))." => Kes ".comma($value)."</p>";
                                    $jumla2+=$value;
                                }
                            }
                            $display_reliefs.="<p class='bordered_bottom'></p><p><b>Total Reliefs: Kes ".comma($jumla2)."</b></p>";
                        }else{
                            $display_reliefs = "<p class='text-success p-1 border border-success'>No reliefs records available!</p>";
                        }
                        // get deductions
                        $display_deductions = "";
                        $deductions_dis = $allowances_bonus[2];
                        $advances_deduct = getAdvacesDeductions($staff_id,$conn2,$start_dates);
                        $deductions_dis = array_merge($deductions_dis,$advances_deduct);
                        // echo json_encode($deductions_dis);
                        if(count($deductions_dis) > 0 && $total_paid > 0){
                            $jumla3 = 0;
                            for ($ind=0; $ind < count($deductions_dis); $ind++) { 
                                foreach ($deductions_dis[$ind] as $key => $value) {
                                    $display_deductions .= "<p>- ".((str_replace("_"," ",$key)))." => Kes ".comma($value)."</p>";
                                    $jumla3+=$value;
                                }
                            }
                            $display_deductions.="<p class='bordered_bottom'></p><p><b>Total Deductions: Kes ".comma($jumla3)."</b></p>";
                        }else{
                            $display_deductions = "<p class='text-success p-1 border border-success'>No deductions records available!</p>";
                        }
                        $data_to_display.="
                                    <div class='year_card'>
                                        <div class='margin-bottom-5px width_100per bordered_bottom'>
                                            <p class='embold'>Month: <span class='color_brown'>".date("M - Y",strtotime($start_dates))."</span></p>
                                        </div>
                                        <div class='salary-amount bordered_bottom'>
                                            <p class='embold'>Net Salary : <span class='color_brown'> Kes ".comma($emp_salary)."</span></p>
                                        </div>
                                        <div class='payments-details'>".$fund_details."</div>
                                        <div class='total_payments'>
                                            <p class='embold bordered_bottom'>Total paid: <span class='color_brown'> Kes ".comma($total_paid)."</span></p>
                                            <p class='embold bordered_bottom'>Balance : <span class='color_brown'>Kes ".comma($balance)."</span></p>
                                        </div>
                                        <p class='link show_salo_break_down' id='".date("M_Y",strtotime($start_dates))."'><i class='fas fa-eye'></i> See More</p>
                                        <div class='total_payments hide border border-secondary p-1 rounded' id='".date("M_Y",strtotime($start_dates))."_1'>
                                            <p class='text-secondary p-0 text-center my-1'><u>Allowances & Bonus</u></p>
                                            ".$allowance_display."
                                            <p class='text-secondary p-0 text-center my-1'><u>Reliefs</u></p>
                                            ".$display_reliefs."
                                            <p class='text-secondary p-0 text-center my-1'><u>Deductions</u></p>
                                            ".$display_deductions."
                                        </div>
                                    </div>
                        ";

                        $date=date_create($start_dates);
                        date_add($date,date_interval_create_from_date_string("1 Month"));
                        $start_dates = date_format($date,"Y-m-d");
                    }
                    $data_to_display.="</div>";
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No records found for this year for ".getStaffName($conn,$staff_id)."</p>";
                }
            }else {
                echo "<p class='red_notice'>No records found because the staff first payment was recorded in ".$firstpay_record." and the current selected year is ".$curr_year.".</p>";
            }
        }elseif (isset($_GET['mpesaTransaction'])) {
            $select = "SELECT `transaction_id`,`mpesa_id`,`amount`,`std_adm`,`transaction_time`,`short_code`,`payment_number`,`fullname`,`transaction_status` FROM `mpesa_transactions` ORDER BY transaction_id DESC;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "";
                while ($row = $result->fetch_assoc()) {
                    $paymentDate = $row['transaction_time'];
                    $year = substr($paymentDate, 0, 4);
                    $month = substr($paymentDate, 4, 2);
                    $day = substr($paymentDate, 6, 2);
                    $hour = substr($paymentDate, 8, 2);
                    $min = substr($paymentDate, 10, 2);
                    $sec = substr($paymentDate, 12, 2);
                    $d = mktime($hour, $min, $sec, $month, $day, $year);
                    $transactionDate =  date("D-dS-M-Y  h.i.s A", $d);
                    $data_to_display.=$row['mpesa_id'].":".$row['amount'].":".getName1($row['std_adm'])." (".$row['std_adm']."):".$transactionDate.":".$row['short_code'].":".$row['payment_number'].":".$row['fullname'].":".$row['transaction_status'].":".$row['transaction_id']."|";
                }
                $data_to_display = substr($data_to_display,0,(strlen($data_to_display)-1));
                echo $data_to_display;
            }
        }elseif (isset($_GET['mpesa_transaction_id'])) {
            $select = "SELECT `transaction_id`,`mpesa_id`,`amount`,`std_adm`,`transaction_time`,`short_code`,`payment_number`,`fullname`,`transaction_status` FROM `mpesa_transactions` WHERE `transaction_id` = ?";
            $mpesa_transaction_id = $_GET['mpesa_transaction_id'];
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$mpesa_transaction_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // panga the user data
                    $paymentDate = $row['transaction_time'];
                    $year = substr($paymentDate, 0, 4);
                    $month = substr($paymentDate, 4, 2);
                    $day = substr($paymentDate, 6, 2);
                    $hour = substr($paymentDate, 8, 2);
                    $min = substr($paymentDate, 10, 2);
                    $sec = substr($paymentDate, 12, 2);
                    $d = mktime($hour, $min, $sec, $month, $day, $year);
                    $transactionDate =  date("D-dS-M-Y  h.i.s A", $d);
                    $mpesa_data =$row['transaction_id'].":".$row['mpesa_id'].":".$row['amount'].":".$row['std_adm'].":".$transactionDate.":".$row['short_code'].":".$row['payment_number'].":".$row['fullname'].":".$row['transaction_status'];
                    echo $mpesa_data;
                }
            }
        }elseif (isset($_GET['getstudentdetails'])) {
            // get the students data
            $select = "SELECT * FROM `student_data` WHERE `deleted` = 0;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $first_name = $row['first_name'];
                    $second_name = $row['second_name'];
                    $surname = $row['surname'];
                    $stud_class = $row['stud_class'];
                    $adm_no = $row['adm_no'];
                    $data_to_display.=$first_name.":".$second_name.":".$surname.":".$adm_no.":".className($stud_class)."|";
                }
                $data_to_display = substr($data_to_display,0,(strlen($data_to_display)-1));
            }
            echo $data_to_display;
        }elseif (isset($_GET['getdrivers'])) {
            // get the user ids that have been used in the school van section
            $select = "SELECT `driver_name` FROM `school_vans`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $driver_ids = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($driver_ids,$row['driver_name']);
                }
            }
            // var_dump($driver_ids);
            $select = "SELECT * FROM `user_tbl` WHERE `auth` = 6 AND `school_code` = ?";
            $stmt = $conn->prepare($select);
            $school_code = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$school_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $driver_list = "<select name='van_driver' id='van_driver' class='form-control'><option value='' hidden>Select a driver.</option>";
            $driver_count = 0;
            if($result){
                while ($row = $result->fetch_assoc()) {
                    $present = 0;
                    for ($i=0; $i < count($driver_ids); $i++) { 
                        if (trim($driver_ids[$i]) == trim($row['user_id'])) {
                            $present = 1;
                            break;
                        }
                    }
                    if ($present == 0) {
                        $driver_list.= "<option value = '".$row['user_id']."' >".$row['fullname'].".</option>";
                        $driver_count++;
                    }
                }
            }
            $driver_list .= "</select>";
            if ($driver_count != 0) {
                echo $driver_list;
            }else {
                echo "<p class='text-danger text-xxs'>No drivers present in the school at the moment!</p>";
            }
        }elseif (isset($_GET['getdrivers_update'])) {
            // get the user ids that have been used in the school van section
            $select = "SELECT `driver_name` FROM `school_vans`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $driver_ids = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($driver_ids,$row['driver_name']);
                }
            }
            // var_dump($driver_ids);
            $select = "SELECT * FROM `user_tbl` WHERE `auth` = 6 AND `school_code` = ?";
            $stmt = $conn->prepare($select);
            $school_code = $_SESSION['schoolcode'];
            $stmt->bind_param("s",$school_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $driver_list = "<select style='width:100%;' name='van_driver' id='van_driver_up' class='form-control'><option value='' hidden>Select a driver.</option>";
            $driver_count = 0;
            if($result){
                while ($row = $result->fetch_assoc()) {
                    $present = 0;
                    for ($i=0; $i < count($driver_ids); $i++) { 
                        if (trim($driver_ids[$i]) == trim($row['user_id'])) {
                            $present = 1;
                            break;
                        }
                    }
                    if ($present == 0) {
                        $driver_list.= "<option value = '".$row['user_id']."' >".$row['fullname'].".</option>";
                        $driver_count++;
                    }
                }
            }
            $driver_list .= "</select>";
            if ($driver_count != 0) {
                echo $driver_list;
            }else {
                echo "<p class='text-danger text-xxs'>No drivers present in the school at the moment!</p>";
            }
        }
        elseif (isset($_GET['getRoutes'])) {
            $select = "SELECT * FROM `van_routes`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_counts = 0;
            $route_list = "<select style='width:100%;' name='routed_lists' id='routed_lists' class='form-control'><option value='' hidden>Select a Route.</option>";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $route_list.= "<option value = '".$row['route_id']."' > ".$row['route_name'].".</option>";
                    $route_counts++;
                }
            }
            $route_list .= "</select>";
            if ($route_counts > 0) {
                echo $route_list;
            }else {
                echo "<p  class='text-danger text-xxs'>No routes registered in the school at the moment. Register routes in order to proceed!</p>";
            }
        }
        elseif (isset($_GET['getRoutes_update'])) {
            $select = "SELECT * FROM `van_routes`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_counts = 0;
            $route_list = "<select name='routed_lists' style='width:100%;' id='routed_lists_inside' class='form-control'><option value='' hidden>Select a Route.</option>";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $route_list.= "<option value = '".$row['route_id']."' > ".$row['route_name'].".</option>";
                    $route_counts++;
                }
            }
            $route_list .= "</select>";
            if ($route_counts > 0) {
                echo $route_list;
            }else {
                echo "<p class='text-danger text-xxs'>No routes registered in the school at the moment. Register routes in order to proceed!</p>";
            }
        }elseif(isset($_GET['getRoutes_enroll_trans'])){
            $select = "SELECT * FROM `van_routes`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_counts = 0;
            $route_list = "<select name='routed_lists' id='enroll_studs_routes' style='width:100%;' class='form-control'><option value='' hidden>Select a Route.</option>";
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $route_list.= "<option value = '".$row['route_id']."' > ".$row['route_name']." (Kes ".$row['route_price'].").</option>";
                    $route_counts++;
                }
            }
            $route_list .= "</select>";
            if ($route_counts > 0) {
                echo $route_list;
            }else {
                echo "<p class='text-danger text-xxs'>No routes registered in the school at the moment. Register routes in order to proceed!</p>";
            }
        }elseif(isset($_GET['getroute_view_information'])){
            $select = "SELECT * FROM `van_routes`;";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $route_counts = 0;
            $route_list = "<select name='routed_lists' id='update_studs_routes' style='width:100%;' class='form-control'><option value='' hidden>Select a Route.</option>";
            $route_details = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $route_list.= "<option value = '".$row['route_id']."' > ".$row['route_name']." (Kes ".$row['route_price'].").</option>";
                    $route_counts++;
                    array_push($route_details,$row);
                }
            }
            $route_list .= "</select>";
            if ($route_counts > 0) {
                $route_data = json_encode($route_details);
                echo $route_list."<input type='hidden' value='".$route_data."' id='route_details_lists'>";
            }else {
                echo "<p class='text-danger text-xxs'>No routes registered in the school at the moment. Register routes in order to proceed!</p>";
            }
        }elseif (isset($_GET['update_student_route'])) {
            $student_id = $_GET['student_id'];
            $term = $_GET['term'];
            $router_id = $_GET['router_id'];
            
            // update the student data
            $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $deregistered = $row['deregistered'];

                    if(isJson($deregistered)){
                        $deregistered = json_decode($deregistered);
                        for ($i=0; $i < count($deregistered); $i++) { 
                            $elem = $deregistered[$i];
                            if ($elem->term == $term) {
                                $elem->route = $router_id;
                            }
                        }

                        // update the table
                        $update = "UPDATE `transport_enrolled_students` SET `deregistered` = ? WHERE `student_id` = ?";
                        $stmt = $conn2->prepare($update);
                        $deregistered = json_encode($deregistered);
                        $stmt->bind_param("ss",$deregistered,$student_id);
                        $stmt->execute();

                        echo "<p class='text-success'>Data updated successfully!</p>";
                    }else{
                        echo "<p class='text-danger'>An error occured!</p>";
                    }
                }else{
                    echo "<p class='text-danger'>An error1 occured!</p>";
                }
            }else{
                echo "<p class='text-danger'>An error occured!</p>";
            }
        }
        elseif (isset($_GET['get_my_users'])) {
            // $select = "SELECT * FROM `user_tbl` WHERE `deleted` = '0'";
            $select = "SELECT * FROM `payroll_information`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<select class='w-100 form-control border border-gray rounded p-2 text-xs font-weight-bold' name='employees_id_advances' id='employees_id_advances'><option value='' hidden >Select..</option>";
                $indexing = 1;
                while ($row = $result->fetch_assoc()) {
                    $data_to_display.="<option value='".$row['staff_id']."'>".$indexing.". ".ucwords(strtolower(getStaffName($conn,$row['staff_id'])))."</option>";
                    $indexing++;
                }
                $data_to_display.="</select>";
                echo $data_to_display;
            }else{
                echo "<p class='border border-danger p-2'>No staff present to be displayed!</p>";
            }
        }elseif (isset($_GET['define_advance'])) {
            $define_advance = $_GET['define_advance'];
            $employees_name = $_GET['employees_name'];
            $advance_amount = $_GET['advance_amount'];
            $effect_month = $_GET['effect_month'];
            $advance_installments = $_GET['advance_installments'];

            $today = date("Y-m-d");
            $balance = $advance_amount;
            
            // $insert = "INSERT INTO `advance_pay` (`month_effect`,`amount`,`installments`,`date_taken`,`employees_id`,`balance_left`) VALUES ('".$effect_month."','".$advance_amount."','".$advance_installments."','".$today."','".$employees_name."','".$balance."');";
            $insert = "INSERT INTO `advance_pay` (`month_effect`,`amount`,`installments`,`date_taken`,`employees_id`,`balance_left`) VALUES (?,?,?,?,?,?);";
            // echo $insert;
            $stmt  = $conn2->prepare($insert);
            $stmt->bind_param("ssssss",$effect_month,$advance_amount,$advance_installments,$today,$employees_name,$balance);
            if($stmt->execute()){
                echo "<p id='advance_payments_in' class='text-success border border-success my-2'>Advance has been added successfully!!</p>";
            }else{
                echo "<p class='text-danger border border-danger my-2'>An error has occured !</p>";
            }
        }elseif(isset($_GET['get_advances'])){
            $select = "SELECT * FROM `advance_pay` ORDER BY `advance_id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $staff_information = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $row['employees_id'] = ucwords(strtolower(getStaffName($conn,$row['employees_id'])));
                    $row['date_taken'] = date("D dS M Y",strtotime($row['date_taken']));
                    array_push($staff_information,$row);
                }
            }
            echo json_encode($staff_information);
        }elseif (isset($_GET['get_nssf_reports'])) {
            // get staff 
            $selected_month = $_GET['selected_month'];
            // echo $selected_month;
            $select = "SELECT * FROM `payroll_information`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    // check if the staff was paid by the month the user has chosen
                    $effect_month = explode(",",$row['effect_month'])[0];
                    $current_balance = $row['current_balance'];
                    $current_balance_monNyear = $row['current_balance_monNyear'];

                    // get the joined_date 
                    $joined_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$effect_month)));
                    $last_paid_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$current_balance_monNyear)));
                    // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";
                    
                    // selected month
                    $selected_month = date("Y-m-d",strtotime($selected_month."-01"));
                    /** TEST WITH THIS**/ 
                    // $staff_information = getStaffInformations($conn,$row['staff_id']);
                    // $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                    // echo $staff_name." ||(".$selected_month.">". $joined_date ."&&". $selected_month ."<". $last_paid_date.") || (".$joined_date." == ". $selected_month." && ".$last_paid_date." > ".$joined_date.") || (".$last_paid_date." == ".$selected_month." && ".$current_balance." == 0).||<br>";
                    /** ENDS HERE**/

                    // if the selected month is between the two date
                    if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                        // with the staff data create a table showing
                        $row_data = [];
                        $staff_information = getStaffInformations($conn,$row['staff_id']);
                        // echo json_encode($staff_information);
                        $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                        $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                        $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                        $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                        // get if the staff gets the nssf deduction
                        $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                        $nssf_amounts = 0;
                        $nssf_type = "none";
                        $salary_details = getMySalaryBreakdown($row['staff_id'],$conn2,$selected_month);

                        if ($salary_details != null) {
                            // decode the salary details to get the nssf amount
                            $decode_salary = ($salary_details);
                            if($decode_salary->nssf_rates == "teir_1"){
                                $nssf_amounts = 360;
                                $nssf_type = "Teir 1";
                            }elseif($decode_salary->nssf_rates == "teir_1_2"){
                                $nssf_amounts = 1080;
                                $nssf_type = "Teir 1 & 2";
                            }elseif($decode_salary->nssf_rates == "teir_old"){
                                $nssf_amounts = 200;
                                $nssf_type = "Old Rates";
                            }else{
                                $nssf_amounts = 0;
                                $nssf_type = "none";
                            }
                        }
                        // employees amounts
                        $employers_amount = $nssf_amounts;
                        $total_to_pay = $employers_amount + $nssf_amounts;
                        array_push($row_data,$staff_name,$id_no,$nssf_no,$nssf_type,$total_to_pay,$employers_amount,$employers_amount,$total_to_pay);
                        array_push($data,$row_data);
                        // break;
                    }
                }
            }
            // display table
            $data_to_display = "<hr><h5 class='my-1 text-center'><u>N.S.S.F reports for ".date("M Y",strtotime($selected_month."-01"))."</u></h5><a target='_blank' href='reports/reports.php?get_nssf_reports=true&effect_month=".$selected_month."' class='btn btn-sm btn-secondary text-white'><i class='fas fa-file-pdf'></i> PDF</a><table class='table'><tr>
                                    <th>#</th>
                                    <th>Staff Name</th>
                                    <th>Id No.</th>
                                    <th>NSSF No.</th>
                                    <th>NSSF Category</th>
                                    <th>NSSF Payments</th>
                                    <th>Employer Amount</th>
                                    <th>Employees Amount</th>
                                    <th>Total</th>
                                </tr>";
                                // loop through the data
                                $total = 0;
                                $total1 = 0;
                                $total2 = 0;
                                $total3 = 0;
            for ($index=0; $index < count($data); $index++) { 
                $data_to_display.="<tr>
                                    <td>".($index+1).". </td>
                                    <td>".$data[$index][0]."</td>
                                    <td>".$data[$index][1]."</td>
                                    <td>".$data[$index][2]."</td>
                                    <td>".$data[$index][3]."</td>
                                    <td>Kes ".comma($data[$index][4])."</td>
                                    <td>Kes ".comma($data[$index][5])."</td>
                                    <td>Kes ".comma($data[$index][6])."</td>
                                    <td>Kes ".comma($data[$index][7])."</td>
                                </tr>";
                                $total3 += $data[$index][4];
                                $total2 += $data[$index][5];
                                $total1 += $data[$index][6];
                                $total += $data[$index][7];
            }
            $data_to_display .= "
                                <tr><td colspan='4'></td><td><b>Total</b></td><td><b>Kes ".comma($total3)."</b></td><td><b>Kes ".comma($total2)."</b></td><td><b>Kes ".comma($total1)."</b></td><td><b>Kes ".comma($total)."</b></td></table>";
            if (count($data) > 0) {
                echo $data_to_display;
            }else{
                echo "<p class='border border-danger text-danger p-2 my-2'>No data to display!<br> Only your staff in the payroll system will be displayed here <b>OR</b> <br>The staff who have been cleared by the month of ".date("M Y",strtotime($selected_month."-01"))."!</p>";
            }
        }elseif (isset($_GET['get_kra_reports'])) {
            // get staff 
            $selected_month = $_GET['selected_months'];
            // echo $selected_month;
            $select = "SELECT * FROM `payroll_information`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    // check if the staff was paid by the month the user has chosen
                    $effect_month = explode(",",$row['effect_month'])[0];
                    $current_balance = $row['current_balance'];
                    $current_balance_monNyear = $row['current_balance_monNyear'];

                    // get the joined_date 
                    $joined_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$effect_month)));
                    $last_paid_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$current_balance_monNyear)));
                    // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";
                    
                    // selected month
                    $selected_month = date("Y-m-d",strtotime($selected_month."-01"));
                    /** TEST WITH THIS**/ 
                    // $staff_information = getStaffInformations($conn,$row['staff_id']);
                    // $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                    // echo $staff_name." ||(".$selected_month.">". $joined_date ."&&". $selected_month ."<". $last_paid_date.") || (".$joined_date." == ". $selected_month." && ".$last_paid_date." > ".$joined_date.") || (".$last_paid_date." == ".$selected_month." && ".$current_balance." == 0).||<br>";
                    /** ENDS HERE**/

                    // if the selected month is between the two date
                    if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                        // with the staff data create a table showing
                        $row_data = [];
                        $staff_information = getStaffInformations($conn,$row['staff_id']);
                        // echo json_encode($staff_information);
                        $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                        $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                        $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                        $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                        // get if the staff gets the nssf deduction
                        $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                        $salary_details = getMySalaryBreakdown($row['staff_id'],$conn2,$selected_month);
                        $gross_salary = getSalary($selected_month,$conn2,$row['staff_id']);
                        $nssf_amounts = 0;
                        $nssf_type = "none";
                        $contributions = 0;
                        $nhif_amounts = 0;
                        $taxable_income = 0;
                        $deductions = 0;
                        $reliefs = 0;
                        if ($salary_details != null) {
                            // decode the salary details to get the nssf amount
                            $decode_salary = ($salary_details);
                            if($decode_salary->nssf_rates == "teir_1"){
                                $nssf_amounts = 360;
                                $nssf_type = "Teir 1";
                            }elseif($decode_salary->nssf_rates == "teir_1_2"){
                                $nssf_amounts = 1080;
                                $nssf_type = "Teir 1 & 2";
                            }elseif($decode_salary->nssf_rates == "teir_old"){
                                $nssf_amounts = 200;
                                $nssf_type = "Old Rates";
                            }else{
                                $nssf_amounts = 0;
                                $nssf_type = "none";
                            }
                            // year 
                            $year = $decode_salary->year;
                            // get allowances
                            $total_allowances = 0;
                            $allowances = $decode_salary->allowances;
                            if (is_array($allowances)) {
                                for ($in=0; $in < count($allowances); $in++) { 
                                    $total_allowances+= $allowances[$in]->value;
                                }
                            }

                            // get gross salary
                            $gross_salary = $decode_salary->gross_salary;

                            // get the nhif contribution
                            $nhif_status = $decode_salary->deduct_nhif;
                            $nhif_amounts = ($nhif_status == "yes") ? getNHIFContribution($gross_salary) : 0;

                            // nssf & nhif
                            $contributions = $nssf_amounts + $nhif_amounts;

                            // get taxable income 
                            $taxable_income = ($gross_salary + $total_allowances) - $nssf_amounts;

                            // calculate P.A.Y.E
                            $paye = ($decode_salary->deduct_paye == "yes") ? getPaye($taxable_income,$year) : 0;

                            // get reliefs
                            $paye_relief = ($decode_salary->deduct_paye == "yes" && $decode_salary->personal_relief == "yes") ? 2400 : 0;
                            $nhif_relief = ($decode_salary->deduct_nhif == "yes" && $decode_salary->nhif_relief == "yes") ? (($nhif_amounts*0.15) > 255 ? 255 : ($nhif_amounts*0.15)) : 0;
                            
                            // total reliefs
                            $reliefs = $paye_relief;
                            // get deductions
                            $deductions = $nhif_amounts + $paye;
                        }
                        $final_paye = $paye-$paye_relief;
                        // employees amounts
                        $employers_amount = $nssf_amounts;
                        $total_to_pay = $employers_amount + $nssf_amounts;
                        array_push($row_data,$staff_name,round($gross_salary),$total_allowances,round($taxable_income),round($contributions),round($deductions),round($paye),round($reliefs),round($final_paye),$row['staff_id']);
                        array_push($data,$row_data);
                        // break;
                    }
                }
            }
            // display table
            $data_to_display = "<hr><h5 class='my-1 text-center'><u>KRA reports for ".date("M Y",strtotime($selected_month."-01"))."</u></h5><a target='_blank' href='reports/reports.php?get_kra_reports=true&effect_month=".$selected_month."' class='btn btn-sm btn-secondary text-white'><i class='fas fa-file-pdf'></i> PDF</a><table class='table'><tr>
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>Gross Salary.</th>
                                <th>Allowances.</th>
                                <th>Taxable Income</th>
                                <th>P.A.Y.E</th>
                                <th>Relief</th>
                                <th>Final PAYE</th>
                                <th>Action</th>
                                </tr>";
                                // loop through the data
                                $total = 0;
                                $total1 = 0;
                                $total2 = 0;
                                $total3 = 0;
                                $total4 = 0;
                                $total5 = 0;
                                $total6 = 0;
        $total_1 = 0;
            for ($index=0; $index < count($data); $index++) { 
                $data_to_display.="<tr>
                                    <td>".($index+1).". </td>
                                    <td>".$data[$index][0]."</td>
                                    <td>Kes ".comma($data[$index][1])."</td>
                                    <td>Kes ".comma($data[$index][2])."</td>
                                    <td>Kes ".comma($data[$index][3])."</td>
                                    <td>Kes ".comma($data[$index][6])."</td>
                                    <td>Kes ".comma($data[$index][7])."</td>
                                    <td>Kes ".comma($data[$index][8])."</td>
                                    <td><a href='reports/reports.php?generate_slip=true&staff_slip=".$data[$index][9]."&selected_month=".$selected_month."' target='_blank'class='btn btn-secondary btn-sm text-white'><i class='fas fa-print'></i> Print</a></td>
                                </tr>";
                                $total_1 += $data[$index][1];
                                $total6 += $data[$index][2];
                                $total5 += $data[$index][3];
                                $total3 += $data[$index][4];
                                $total2 += $data[$index][5];
                                $total1 += $data[$index][6];
                                $total += $data[$index][7];
                                $total4 += $data[$index][8];
            }
            $data_to_display .= "
                                <tr><td colspan='1'></td><td><b>Total</b></td><td><b>Kes ".comma($total_1)."</b></td><td><b>Kes ".comma($total6)."</b></td><td><b>Kes ".comma($total5)."</b></td><td><b>Kes ".comma($total1)."</b></td><td><b>Kes ".comma($total)."</b></td><td><b>Kes ".comma($total4)."</b></td></table>";
            if (count($data) > 0) {
                echo $data_to_display;
            }else{
                echo "<p class='border border-danger text-danger p-2 my-2'>No data to display!<br> Only your staff in the payroll system will be displayed here <b>OR</b> <br>The staff who have been cleared by the month of ".date("M Y",strtotime($selected_month."-01"))."!</p>";
            }
        }elseif (isset($_GET['get_nhif_reports'])) {
            // get staff 
            $selected_month = $_GET['selected_months'];
            // echo $selected_month;
            $select = "SELECT * FROM `payroll_information`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    // check if the staff was paid by the month the user has chosen
                    $effect_month = explode(",",$row['effect_month'])[0];
                    $current_balance = $row['current_balance'];
                    $current_balance_monNyear = $row['current_balance_monNyear'];

                    // get the joined_date 
                    $joined_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$effect_month)));
                    $last_paid_date = date("Y-m-d",strtotime("01-".str_replace(":","-",$current_balance_monNyear)));
                    // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";
                    
                    // selected month
                    $selected_month = date("Y-m-d",strtotime($selected_month."-01"));
                    /** TEST WITH THIS**/ 
                    // $staff_information = getStaffInformations($conn,$row['staff_id']);
                    // $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                    // echo $staff_name." ||(".$selected_month.">". $joined_date ."&&". $selected_month ."<". $last_paid_date.") || (".$joined_date." == ". $selected_month." && ".$last_paid_date." > ".$joined_date.") || (".$last_paid_date." == ".$selected_month." && ".$current_balance." == 0).||<br>";
                    /** ENDS HERE**/

                    // if the selected month is between the two date
                    if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                        // with the staff data create a table showing
                        $row_data = [];
                        $staff_information = getStaffInformations($conn,$row['staff_id']);
                        // echo json_encode($staff_information);
                        $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                        $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                        $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                        $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                        // get if the staff gets the nssf deduction
                        $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                        $nhif_amounts = 0;
                        $nhif_reliefs = 0;
                        $salary_details = getMySalaryBreakdown($row['staff_id'],$conn2,$selected_month);
                    $gross_salary = 0;

                        if ($salary_details != null) {
                            // decode the salary details to get the nssf amount
                            $decode_salary = ($salary_details);
                            $deduct_nhif = $salary_details->deduct_nhif;
                            $nhif_relief = $salary_details->nhif_relief;
                            $gross_salary = $salary_details->gross_salary;

                            if ($deduct_nhif == "yes") {
                                $nhif_amounts = getNHIFContribution($gross_salary);
                                $nhif_reliefs = $nhif_relief == "yes" ? (($nhif_amounts * 0.15) > 255 ? 255 : ($nhif_amounts * 0.15)) : 0;

                            }

                        }
                        // add data to row
                        array_push($row_data,$staff_name,$gross_salary,$nhif_no,$nhif_amounts,$nhif_reliefs,$deduct_nhif,$nhif_relief,$row['staff_id']);
                        array_push($data,$row_data);
                        // break;
                    }
                }
            }

            
            // display table
            $data_to_display = "<hr><h5 class='my-1 text-center'><u>N.H.I.F reports for ".date("M Y",strtotime($selected_month."-01"))."</u></h5><a target='_blank' href='reports/reports.php?get_nhif_reports=true&effect_month=".$selected_month."' class='btn btn-sm btn-secondary text-white'><i class='fas fa-file-pdf'></i> PDF</a><table class='table'><tr>
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>NHIF No.</th>
                                <th>Gross Salary.</th>
                                <th>NHIF Contribution</th>
                                <th>NHIF Relief</th>
                                <th>Final NHIF Contribution</th>
                                <th>Deduct NHIF</th>
                                <th>Relief</th>
                                <th>Action</th>
                                </tr>";
                                // loop through the data
                                $total = 0;
                                $total1 = 0;
                                $total2 = 0;
                                $total3 = 0;
                                $total4 = 0;
                                $total5 = 0;
                                $total6 = 0;
            $total_1 = 0;
            for ($index=0; $index < count($data); $index++) { 
                $data_to_display.="<tr>
                                    <td>".($index+1).". </td>
                                    <td>".$data[$index][0]."</td>
                                    <td>".($data[$index][2])."</td>
                                    <td>Kes ".comma($data[$index][1])."</td>
                                    <td>Kes ".comma($data[$index][3])."</td>
                                    <td>Kes ".comma($data[$index][4])."</td>
                                    <td>Kes ".comma($data[$index][3] - $data[$index][4])."</td>
                                    <td>".ucwords($data[$index][5])."</td>
                                    <td>".ucwords($data[$index][6])."</td>
                                    <td><a href='reports/reports.php?generate_slip=true&staff_slip=".$data[$index][7]."&selected_month=".$selected_month."' target='_blank'class='btn btn-secondary btn-sm text-white'><i class='fas fa-print'></i> Print</a></td>
                                </tr>";
                                $total_1 += $data[$index][1];
                                // $total6 += $data[$index][2];
                                $total5 += $data[$index][3];
                                $total3 += $data[$index][4];
                                $total2 += ($data[$index][3] - $data[$index][4]);
                                // $total1 += $data[$index][6];
                                // $total += $data[$index][7];
                                // $total4 += $data[$index][8];
            }
            $data_to_display .= "
                                <tr><td colspan='2'></td><td><b>Total</b></td><td><b>Kes ".comma($total_1)."</b></td><td><b>Kes ".comma($total5)."</b></td><td><b>Kes ".comma($total3)."</b></td><td><b>Kes ".comma($total2)."</b></td></tr></table>";
            if (count($data) > 0) {
                echo $data_to_display;
            }else{
                echo "<p class='border border-danger text-danger p-2 my-2'>No data to display!<br> Only your staff in the payroll system will be displayed here <b>OR</b> <br>The staff who have been cleared by the month of ".date("M Y",strtotime($selected_month."-01"))."!</p>";
            }
        }elseif (isset($_GET['delete_file'])){
            $delete_file = $_GET['delete_file'];
            $file_details = $_GET['file_details'];
            if(isJson_report_fin($file_details)){
                $file_details = json_decode($file_details);

                // delete the file
                $delete_file = deleteFile("../../../".$file_details->fileLocation);
                echo $delete_file ? "<p class='text-success'>File deleted successfully!</p>" : "<p class='text-danger'>An error has occured!</p>";
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
            }
        }

        //CLOSE ALL CONNECTION
        // if ($conn2) {
        //     $conn2->close();
        // }
        // if ($conn) {
        //     $conn->close();
        // }
    }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['student_admission'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $targetDirectory = "../../FinanceSupportingDocuments/".$_SESSION['dbname']."/".$_POST['student_admission']."/"; // Directory to store uploaded files
            $file = $_FILES["file"];
            $customFileName = isset($_POST['file_name']) ? replaceSpacesAndSpecialChars($_POST['file_name'])."_".date("YmdHis") : date("YmdHis")."_".$_POST["student_admission"];
            // echo $targetDirectory;
            $fl_name = (isset($_POST['file_name']) && strlen($_POST['file_name']) > 0) ? ($_POST['file_name']) : date("YmdHis")."_".$_POST["student_admission"];

            // Check if the file type is allowed
            $allowedExtensions = array('png', 'jpeg', 'jpg', 'pdf', 'docx');
            $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo "Error: Invalid file type.";
                exit();
            }

            // Create the target directory and necessary subdirectories if they don't exist
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            $fileName = $customFileName . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
            $fileLocation = $targetDirectory . $fileName;

            if (move_uploaded_file($file["tmp_name"], $fileLocation)) {
                $fileLocation = "/college_sims/FinanceSupportingDocuments/".$_SESSION['dbname']."/".$_POST['student_admission']."/".$fileName;
                $response = array("fileName" => $fl_name, "fileLocation" => $fileLocation, "date_created" => date("YmdHis"));
                echo json_encode($response);
            } else {
                echo "Error uploading the file.";
            }
        }elseif (isset($_POST['add_revenue'])) {
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            // REVENUE NAME
            $revenue_name = $_POST['revenue_name'];
            $revenue_amount = $_POST['revenue_amount'];
            $revenue_date = date("Ymd",strtotime($_POST['revenue_date']));
            $customer_name = $_POST['customer_name'];
            $customer_contacts_revenue = $_POST['customer_contacts_revenue'];
            $contact_person = $_POST['contact_person'];
            $revenue_description = $_POST['revenue_description'];
            $revenue_categories = $_POST['revenue_categories'];
            $revenue_cash_activity = $_POST['revenue_cash_activity'];
            $reportable_status = $_POST['reportable_status'];
            $mode_of_revenue_payment = $_POST['mode_of_revenue_payment'];
            $payment_code = $_POST['payment_code'];
            $revenue_sub_category = $_POST['revenue_sub_category'];

            // SAVE THE DATA TO THE DATABASE
            $insert = "INSERT INTO `school_revenue` (`name`,`amount`,`mode_of_payment`,`payment_code`,`date_recorded`,`customer_name`,`customer_contact`,`contact_person`,`revenue_description`,`revenue_category`,`cash_flow_activities`,`reportable_status`, `revenue_sub_category`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("sssssssssssss",$revenue_name,$revenue_amount,$mode_of_revenue_payment,$payment_code,$revenue_date,$customer_name,$customer_contacts_revenue,$contact_person,$revenue_description,$revenue_categories,$revenue_cash_activity,$reportable_status,$revenue_sub_category);
            $stmt->execute();
            
            $log_text = "Revenue \"".$revenue_name."\" has been added successfully!";
            log_finance($log_text);
            echo "<p class='text-success'>Revenue has been successfully recorded!</p>";
        }elseif(isset($_POST['get_revenue'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            // get the page limit
            $page_req = $_POST['page_req'];
            $limit_1 = $_POST['page_req'] * 1 > 1 ? (($_POST['page_req']*1) - 1) * 50 : 0;
            $limit_2 = $_POST['page_req'] * 1 > 1 ? (($_POST['page_req']*1)) * 50 : 50;

            // get the page numbers and current page
            $select = "SELECT COUNT(*) AS 'Total' FROM `school_revenue`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $count = $row['Total'];
                }
            }

            $total_pages = round($count/50);
            $total_pages += $count%50 == 0 ? 0 : 1;

            // select
            $select = "SELECT * FROM `school_revenue` ORDER BY `id` DESC LIMIT $limit_1,$limit_2";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $revenue = [];
            if($result){
                while($row = $result->fetch_assoc()){
                    $row['date_recorded'] = date("Y-m-d",strtotime($row['date_recorded']));
                    array_push($revenue,$row);
                }
            }

            // get the revenue categories
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $revenue_categories = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    if(isJson($row['valued'])){
                        $valued = json_decode($row['valued']);
                        $revenue_categories = $valued;
                    }
                }
            }
            foreach($revenue as $key_revenue => $value){
                $revenue_category = $value['revenue_category'];
                $revenue[$key_revenue]['revenue_category_name'] = "Not-Set";
                foreach($revenue_categories as $key_rev => $value_1){
                    if($value_1->category_id == $revenue_category){
                        $revenue[$key_revenue]['revenue_category_name'] = ucwords(strtolower($value_1->category_name));
                        break;
                    }
                }
            }

            // store in assoc array
            $revenue_data = new stdClass();
            $revenue_data->total_pages = $total_pages;
            $revenue_data->current_page = $page_req;
            $revenue_data->data = $revenue;
            $revenue_data->total_record = $count;
            $revenue_data->start_from = $limit_1;
            $revenue_data->revenue_categories = $revenue_categories;

            // return the json encoded string to the front end
            echo json_encode($revenue_data);

        }elseif(isset($_POST['get_suppliers'])){
            $get_suppliers = $_POST['get_suppliers'];
            // get the page limit
            $page_req = $_POST['get_suppliers'];
            $limit_1 = $_POST['get_suppliers'] * 1 > 1 ? (($_POST['get_suppliers']*1) - 1) * 50 : 0;
            $limit_2 = $_POST['get_suppliers'] * 1 > 1 ? (($_POST['get_suppliers']*1)) * 50 : 50;

            // save text
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // get the page numbers and current page
            $select = "SELECT COUNT(*) AS 'Total' FROM `suppliers`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $count = $row['Total'];
                }
            }

            $total_pages = round($count/50);
            $total_pages += $count%50 == 0 ? 0 : 1;

            // select
            $select = "SELECT * FROM `suppliers` ORDER BY `supplier_id` DESC LIMIT $limit_1,$limit_2";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $suppliers = [];
            if($result){
                while($row = $result->fetch_assoc()){
                    // $select = "SELECT SUM(SB.bill_amount) AS 'Due', SUM((SELECT SUM(SBP.amount) AS 'Paid' FROM `supplier_bill_payments` AS SBP WHERE SBP.payment_for = SB.bill_id )) AS 'Paid' FROM `supplier_bills` AS SB WHERE SB.supplier_id = '".$row['supplier_id']."'";
                    // echo $select;
                    $select = "SELECT SUM(SB.bill_amount) AS 'Due', CONCAT('0') AS 'Paid' FROM `supplier_bills` AS SB WHERE `supplier_id` = '".$row['supplier_id']."' UNION ALL (SELECT CONCAT('0') AS 'Due', SUM(SBP.amount) AS 'Paid' FROM `supplier_bill_payments` AS SBP LEFT JOIN supplier_bills AS SBILL ON SBILL.bill_id = SBP.payment_for WHERE SBP.approval_status = 1 AND SBILL.supplier_id = '".$row['supplier_id']."');";
                    $statement = $conn2->prepare($select);
                    $statement->execute();
                    $res = $statement->get_result();
                    $billing_amount = 0;
                    $paid_amount = 0;
                    if ($res) {
                        while($rows = $res->fetch_assoc()){
                            $billing_amount += $rows['Due'];
                            $paid_amount += $rows['Paid'];
                        }
                    }

                    $amount_owed = $billing_amount-$paid_amount;

                    // amount owed
                    $row['amount_owed'] = number_format($amount_owed);
                    
                    // change date
                    $row['date_registered'] = date("D dS M Y",strtotime($row['date_registered']));
                    array_push($suppliers,$row);
                }
            }
            $data = array("suppliers" => $suppliers, "total_pages" => $total_pages);

            // return the json encoded string to the front end
            echo json_encode($data);
        }elseif(isset($_POST['get_payment_requests'])){
            $get_payment_requests = $_POST['get_payment_requests'];
            // get the page limit
            $page_req = $_POST['get_payment_requests'];
            $limit_1 = $_POST['get_payment_requests'] * 1 > 1 ? (($_POST['get_payment_requests']*1) - 1) * 50 : 0;
            $limit_2 = $_POST['get_payment_requests'] * 1 > 1 ? (($_POST['get_payment_requests']*1)) * 50 : 50;

            // save text
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // get the page numbers and current page
            $select = "SELECT COUNT(*) AS 'Total' FROM `suppliers`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $count = $row['Total'];
                }
            }

            // GET THE TOTAL PAGES
            $select = "(SELECT payment_id, (SELECT `bill_name` FROM `supplier_bills` WHERE `bill_id` = payment_for) AS 'exp_name', (SELECT `expense_name` FROM `expense_category` WHERE `expense_id` = (SELECT `expense_category` FROM `supplier_bills` WHERE `bill_id` = payment_for)) AS 'exp_category', amount,date_paid,document_number,`payment_description`, CONCAT('supplier') AS 'table_name' FROM supplier_bill_payments WHERE approval_status = '0' UNION ALL SELECT expid, exp_name, (SELECT expense_name FROM expense_category WHERE expense_id = exp_category) AS 'exp_category', exp_amount, CONCAT(expense_date,' ',exp_time) AS 'time', document_number, expense_description, CONCAT('running_expense') AS 'table_name' FROM expenses WHERE  approval_status = '0')";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while($row = $result->fetch_assoc()){
                    $count++;
                }
            }

            $total_pages = round($count/50);
            $total_pages += $count%50 == 0 ? 0 : 1;

            // select
            $select = "(SELECT payment_id, (SELECT `bill_name` FROM `supplier_bills` WHERE `bill_id` = payment_for) AS 'exp_name', (SELECT `expense_name` FROM `expense_category` WHERE `expense_id` = (SELECT `expense_category` FROM `supplier_bills` WHERE `bill_id` = payment_for)) AS 'exp_category', amount,date_paid,document_number,`payment_description`, CONCAT('supplier') AS 'table_name' FROM supplier_bill_payments WHERE approval_status = '0' UNION ALL SELECT expid, exp_name, (SELECT expense_name FROM expense_category WHERE expense_id = exp_category) AS 'exp_category', exp_amount, CONCAT(expense_date,' ',exp_time) AS 'time', document_number, expense_description, CONCAT('running_expense') AS 'table_name' FROM expenses WHERE  approval_status = '0') ORDER BY `payment_id` DESC LIMIT $limit_1,$limit_2";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $pay_requests = [];
            if($result){
                while($row = $result->fetch_assoc()){
                    $row['date_paid'] = date("D dS M Y H:i:sA",strtotime($row['date_paid']));
                    $row['amount'] = "Kes ".number_format($row['amount']);
                    array_push($pay_requests,$row);
                }
            }
            $data = array("pay_requests" => $pay_requests, "total_pages" => $total_pages);

            // return the json encoded string to the front end
            echo json_encode($data);
        }elseif(isset($_POST['get_assets'])){
            $get_assets = $_POST['get_assets'];
            // get the page limit
            $page_req = $_POST['get_assets'];
            $limit_1 = $_POST['get_assets'] * 1 > 1 ? (($_POST['get_assets']*1) - 1) * 50 : 0;
            $limit_2 = $_POST['get_assets'] * 1 > 1 ? (($_POST['get_assets']*1)) * 50 : 50;

            // save text
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // get the page numbers and current page
            $select = "SELECT COUNT(*) AS 'Total' FROM `asset_table`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $count = $row['Total'];
                }
            }

            $total_pages = round($count/50);
            $total_pages += $count%50 == 0 ? 0 : 1;

            // select
            $select = "SELECT * FROM `asset_table` ORDER BY `asset_id` DESC LIMIT $limit_1,$limit_2";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $assets = [];
            if($result){
                while($row = $result->fetch_assoc()){
                    $row['real_acquisition_date'] = $row['date_of_acquiry'];
                    $row['real_asset_category'] = $row['asset_category'];
                    $row['real_orginal_value'] = $row['orginal_value'];
                    $row['disposed_on'] = date("D dS M Y", strtotime($row['disposed_on']));
                    
                    // get the asset category
                    $asset_category = "N/A";
                    if($row['asset_category'] == "1"){
                        $asset_category = "Land";
                    }elseif($row['asset_category'] == "2"){
                        $asset_category = "Buildings";
                    }elseif($row['asset_category'] == "3"){
                        $asset_category = "Motor Vehicle";
                    }elseif($row['asset_category'] == "4"){
                        $asset_category = "Furniture & Fittings";
                    }elseif($row['asset_category'] == "5"){
                        $asset_category = "Computer & ICT Equipments";
                    }elseif($row['asset_category'] == "6"){
                        $asset_category = "Plant & Equipments";
                    }elseif($row['asset_category'] == "7"){
                        $asset_category = "Capital Work in Progress";
                    }

                    $row['asset_category'] = $asset_category;

                    // get the current value
                    $value_acquisition = get_current_value($row);
                    
                    // real new value
                    $row['real_new_value'] = $value_acquisition['new_value'];
                    
                    // get the date difference
                    $financial_year_end = date("Y")."1231235959";
                    $date_acquired = date("YmdHis",strtotime($row['date_of_acquiry']));
                    $date1 = date_create($financial_year_end);
                    $date2 = date_create($date_acquired);
                    $diff = date_diff($date1,$date2);
                    $difference_year = $diff->format("%y");

                    $row['years'] = $difference_year;
                    
                    // change date
                    $row['date_of_acquiry'] = date("D dS M Y",strtotime($row['date_of_acquiry']));
                    $row['new_value'] = number_format($value_acquisition['new_value']);
                    $row['value_acquisition'] = $value_acquisition['value_acquisition'];
                    $row['orginal_value'] = number_format($row['orginal_value']);
                    $row['disposed_value'] = "Kes ".number_format($row['disposed_value']);
                    array_push($assets,$row);
                }
            }
            $data = array("assets" => $assets, "total_pages" => $total_pages);

            // return the json encoded string to the front end
            echo json_encode($data);
        }elseif(isset($_POST['get_asset_account'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // select statement
            $select = "SELECT * FROM `asset_table` WHERE `asset_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$_POST['get_asset_account']);
            $stmt->execute();
            $result = $stmt->get_result();
            $asset_account = [];
            if($result){
                if($row = $result->fetch_assoc()){
                    $orginal_value = $row['orginal_value'];
                    $acquisition_rate = $row['acquisition_rate'];
                    $date_of_acquiry = $row['date_of_acquiry'];
                    $acquisition_option = $row['acquisition_option'];
                    $current_value = get_current_value($row);
                    
                    // echo current value
                    echo json_encode($current_value);
                }else{
                    echo "[]";
                }
            }else{
                echo "[]";
            }
        }elseif(isset($_POST['save_supplier_bill'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $supplier_id = $_POST['supplier_id'];
            $supplier_bill_name = $_POST['supplier_bill_name'];
            $supplier_bill_amount = $_POST['supplier_bill_amount'];
            $supplier_expense_category = $_POST['supplier_expense_category'];
            $supplier_expense_sub_category = $_POST['supplier_expense_sub_category'];
            $date_assigned = date("YmdHis",strtotime($_POST['date_assigned']));
            $supplier_document_number = $_POST['supplier_document_number'];
            $supplier_bill_due_date = date("YmdHis",strtotime($_POST['supplier_bill_due_date']));

            // asset data
            $asset_expense_category = $_POST['asset_expense_category'];
            $asset_acquisition_method = $_POST['asset_acquisition_method'];
            $asset_acquisition_rates = $_POST['asset_acquisition_rates'];
            $supplier_expense_type = $_POST['supplier_expense_type'];
            
            if($supplier_expense_type == "capital"){
                // insert the data into asset
                $supplier_expense_sub_category = 0;

                $insert = "INSERT INTO `asset_table` (`asset_name`,`asset_category`,`date_of_acquiry`,`acquisition_option`,`acquisition_rate`,`orginal_value`) VALUES (?,?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $today = date("YmdHis");
                $stmt->bind_param("ssssss",$supplier_bill_name, $asset_expense_category, $today, $asset_acquisition_method, $asset_acquisition_rates, $supplier_bill_amount);
                $stmt->execute();

                // supplier expense category
                $supplier_expense_category = $asset_expense_category;
            }
            
            // insert the data to the database
            $insert = "INSERT INTO `supplier_bills` (`supplier_id`,`bill_name`,`bill_amount`,`document_number`,`expense_category`,`expense_sub_category`,`due_date`,`date_assigned`,`expense_type`,`acquisition_method`) VALUES (?,?,?,?,?,?,'".$supplier_bill_due_date."','".$date_assigned."',?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssssss",$supplier_id,$supplier_bill_name,$supplier_bill_amount,$supplier_document_number,$supplier_expense_category,$supplier_expense_sub_category,$supplier_expense_type,$asset_acquisition_method);
            $stmt->execute();
            
            echo "<p class='text-success'>Supplier bill has been successfully added!</p>";
        }elseif(isset($_POST['update_bill'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // get the data
            $update_bill = $_POST['update_bill'];
            $bill_name = $_POST['bill_name'];
            $bill_amount = $_POST['bill_amount'];
            $date_assigned = date("YmdHis",strtotime($_POST['date_assigned']));
            $due_date = date("YmdHis",strtotime($_POST['due_date']));
            $supplier_bill_id_edit = $_POST['supplier_bill_id_edit'];
            $supplier_document_number = $_POST['supplier_document_number'];
            $supplier_expense_category_edit = $_POST['supplier_expense_category_edit'];
            $supplier_expense_sub_category_edit = $_POST['supplier_expense_sub_category_edit'];
            $asset_expense_category = $_POST['asset_expense_category'];
            $expense_type = $_POST['expense_type'];

            $asset_expense_category = $expense_type == "capital" ? $asset_expense_category : $supplier_expense_category_edit;

            // SELECT 
            $UPDATE = "UPDATE `supplier_bills` SET `bill_name` = ?, `expense_type` = ?, `expense_category` = ?,`bill_amount` = ?,date_assigned = ?, due_date = ?, `document_number` = ?, `expense_sub_category` = ? WHERE `bill_id` = ?";
            $stmt = $conn2->prepare($UPDATE);
            $stmt->bind_param("sssssssss",$bill_name,$expense_type,$asset_expense_category,$bill_amount,$date_assigned,$due_date,$supplier_document_number,$supplier_expense_sub_category_edit,$supplier_bill_id_edit);
            $stmt->execute();

            echo "<p class='text-success'>Supplier bill updated successfully!</p>";
        }elseif(isset($_POST['save_assets'])){
            // connections
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // data passed
            $save_assets = $_POST['save_assets'];
            $acquiry_date = $_POST['acquiry_date'];
            $asset_original_value = $_POST['asset_original_value'];
            $value_acquisition = $_POST['value_acquisition'];
            $value_acquisition_rate = $_POST['value_acquisition_percentage'];
            $asset_name = $_POST['asset_name'];
            $asset_description = $_POST['asset_description'];
            $asset_category = $_POST['asset_category'];
            $asset_acquiry_date = date("YmdHis", strtotime($_POST['asset_acquiry_date']));
            
            // insert
            $insert = "INSERT INTO `asset_table` (`asset_name`,`asset_category`,`date_of_acquiry`,`acquisition_option`,`acquisition_rate`,`orginal_value`) VALUES (?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $stmt->bind_param("ssssss",$asset_name,$asset_category,$asset_acquiry_date,$value_acquisition,$value_acquisition_rate,$asset_original_value);
            $stmt->execute();

            echo "<p class='text-success'>Asset registered successfully!</p>";
        }elseif(isset($_POST['get_payment_for'])){
            // connections
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // values
            $option_id = $_POST['option_id'];
            $option_value = $_POST['option_value'];
            $supplier_id = $_POST['supplier_id'];

            // SELECT
            $select = "SELECT * FROM `supplier_bills` WHERE `supplier_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$supplier_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<select class='form-control w-75' id='".$option_id."'><option hidden value=''>Select an Option</option>";
            if($result){
                while($row = $result->fetch_assoc()){
                    $sel = "SELECT SUM(`amount`) AS 'Total' FROM `supplier_bill_payments` WHERE `payment_for` = ?";
                    $statement = $conn2->prepare($sel);
                    $statement->bind_param("s",$row['bill_id']);
                    $statement->execute();
                    $res = $statement->get_result();
                    $total = 0;
                    if ($res) {
                        if($rows = $res->fetch_assoc()){
                            $total = $rows['Total'];
                        }
                    }
                    $balance = $row['bill_amount']*1 - $total*1;
                    $selected = $option_value == $row['bill_id'] ? "selected" : "";

                    // show balance
                    if($balance == 0){
                        // continue;
                    }
                    $data_to_display.="<option ".$selected." value='".$row['bill_id']."'>".ucwords(strtolower($row['bill_name']))." - (Bal: Kes ".number_format($balance).")</option>";
                }
            }
            $data_to_display .= "</select>";
            echo $data_to_display;
        }elseif(isset($_POST['save_supplier_data'])){
            // connection
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            $payment_amount = $_POST['payment_amount'];
            $payment_date = date("Ymd",strtotime($_POST['payment_date'])).date("His");
            $document_number = $_POST['document_number'];
            $payment_description = $_POST['payment_description'];
            $supplier_payment_for = $_POST['supplier_payment_for'];
            $supplier_payment_id = $_POST['supplier_payment_id'];
            $payment_method = $_POST['payment_method'];

            // select
            $select = "SELECT SUM(`amount`) AS 'Total' FROM `supplier_bill_payments` WHERE `payment_for` = '".$supplier_payment_for."' AND `payment_id` != '".$supplier_payment_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $paid_amount = 0;
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $paid_amount = $row['Total'];
                }
            }

            $select = "SELECT * FROM `supplier_bills` WHERE `bill_id` = '".$supplier_payment_for."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $bill_amount = 0;
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $bill_amount = $row['bill_amount'];
                }
            }

            $balance = $bill_amount - $paid_amount;
            if($balance >= $payment_amount){
                $update = "UPDATE `supplier_bill_payments` SET `payment_for` = ?, `amount` = ?,`payment_method` = ?, `date_paid` = ?, `payment_description` = ?, `document_number` = ? WHERE `payment_id` = ?";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("sssssss", $supplier_payment_for, $payment_amount, $payment_method, $payment_date, $payment_description, $document_number, $supplier_payment_id);
                $stmt->execute();
                echo "<p class='text-success'>Payment has been updated successfully!</p>";
            }else{
                echo "<p class='text-danger'>You cannot add more than the balance of Kes ".number_format($balance)."! <input type='hidden' id='supplier_payment_error_edit' value='false'></p>";
            }

            // get the amount of payment if its more than the balance
        }elseif(isset($_POST['save_payment'])){
            // connection
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // data
            $supplier_payment_for = $_POST['supplier_payment_for'];
            $supplier_payment_amount = $_POST['supplier_payment_amount'];
            $supplier_payment_date = date("YmdHis", strtotime($_POST['supplier_payment_date']));
            $supplier_payment_description = $_POST['supplier_payment_description'];
            $payment_method = $_POST['payment_method'];
            $supplier_payment_document_no = $_POST['supplier_payment_document_no'];

            // get what you are paying for
            $select = "SELECT * FROM `supplier_bills` WHERE `bill_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$supplier_payment_for);
            $stmt->execute();
            $result = $stmt->get_result();
            $bill_amount = 0;
            if($result){
                if($row = $result->fetch_assoc()){
                    $bill_amount = $row['bill_amount'];
                }
            }

            // get how much has been paid for that bill.
            $select = "SELECT SUM(`amount`) AS 'Total' FROM `supplier_bill_payments` WHERE `payment_for` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$supplier_payment_for);
            $stmt->execute();
            $result = $stmt->get_result();
            $paid_amount = 0;
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $paid_amount = $row['Total'];
                }
            }

            $balance = $bill_amount - $paid_amount;

            // check if the amount paid is greater than the balance
            if($balance >= $supplier_payment_amount){
                // SAVE THE PAYMENT
                $insert = "INSERT INTO `supplier_bill_payments` (`payment_for`,`payment_method`,`amount`,`date_paid`,`payment_description`,`document_number`) VALUES (?,?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $stmt->bind_param("ssssss",$supplier_payment_for,$payment_method,$supplier_payment_amount,$supplier_payment_date,$supplier_payment_description,$supplier_payment_document_no);
                $stmt->execute();
                echo "<p class='text-success'>Payment has been made successfully!</p>";
            }else{
                echo "<p class='text-danger'>You cannot pay more than the balance left!<input type='hidden' id='supplier_payment_error' value='false'></p>";
            }
        }elseif(isset($_POST['delete_supplier_payment'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $delete_supplier_payment = $_POST['delete_supplier_payment'];
            $delete = "DELETE FROM `supplier_bill_payments` WHERE `payment_id` = '".$delete_supplier_payment."'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();
            
            echo "<p class='text-success'>Data has been deleted successfully!</p>";
        }elseif(isset($_POST['delete_supplier_bill'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // DELETE THE SUPPLIER BILL
            $delete_supplier_bill = $_POST['delete_supplier_bill'];
            $delete = "DELETE FROM `supplier_bills` WHERE `bill_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$delete_supplier_bill);
            $stmt->execute();

            // delete the payments linked to that bill
            $delete_supplier = "DELETE FROM `supplier_bill_payments` WHERE `payment_for` = ?";
            $stmt = $conn2->prepare($delete_supplier);
            $stmt->bind_param("s",$delete_supplier_bill);
            $stmt->execute();
            
            echo "<p class='text-success'>Bill has been successfully deleted!</p>";
        }elseif(isset($_POST['send_payment_request'])){
            $payment_id = $_POST['payment_id'];
            $payment_type = $_POST['payment_type'];
            $comment = $_POST['comment'];
            $request_status = $_POST['request_status'];

            // include the connectiom
            include("../../connections/conn2.php");

            // if it running expense go to expense table
            if($payment_type == "running_expense"){
                // select statement
                $select = "SELECT * FROM `expenses` WHERE `expid` = '".$payment_id."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        // update the expense
                        $update = "UPDATE `expenses` SET `approval_status` = '".$request_status."', `approval_comment` = ? WHERE `expid` = '".$payment_id."'";
                        $stmt = $conn2->prepare($update);
                        $stmt->bind_param("s",$comment);
                        $stmt->execute();
                        
                        if($request_status == 1){
                            echo "<p class='text-success'>Payment request successfully accepted!</p>";
                        }else{
                            echo "<p class='text-success'>Payment request successfully declined!</p>";
                        }
                        return 0;
                    }
                }
                echo "<p class='text-danger'>Invalid payment request! It must have been deleted!</p>";
                return 0;
            }elseif($payment_type == "supplier"){
                $select = "SELECT * FROM `supplier_bill_payments` WHERE `payment_id` = '".$payment_id."'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    if($row = $result->fetch_assoc()){
                        // update
                        $update = "UPDATE `supplier_bill_payments` SET `approval_status` = '".$request_status."', `approval_comment` = ? WHERE `payment_id` = '".$payment_id."'";
                        $stmt = $conn2->prepare($update);
                        $stmt->bind_param("s",$comment);
                        $stmt->execute();
                        
                        if($request_status == 1){
                            echo "<p class='text-success'>Payment request successfully accepted!</p>";
                        }else{
                            echo "<p class='text-success'>Payment request successfully declined!</p>";
                        }
                        return 0;
                    }
                }
                echo "<p class='text-danger'>Invalid payment request! It must have been deleted!</p>";
                return 0;
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
                return 0;
            }
        }elseif(isset($_POST['delete_supplier'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $delete_supplier = $_POST['delete_supplier'];
            $select = "SELECT * FROM `suppliers` WHERE `supplier_id` = '".$delete_supplier."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                while($row = $result->fetch_assoc()){
                    // get the supplier bill
                    $sel = "SELECT * FROM `supplier_bills` WHERE `supplier_id` = ?";
                    $stmt1 = $conn2->prepare($sel);
                    $stmt1->bind_param("s",$row['supplier_id']);
                    $stmt1->execute();
                    $res = $stmt1->get_result();
                    if($res){
                        while($row1 = $res->fetch_assoc()){
                            // delete the supplier payment
                            $delete = "DELETE FROM `supplier_bill_payments` WHERE `payment_for` = '".$row['bill_id']."'";
                            $stmt = $conn2->prepare($delete);
                            $stmt->execute();
                        }
                    }
                }
                // delete the supplier bill
                $delete = "DELETE FROM `supplier_bills` WHERE `supplier_id` = '".$delete_supplier."'";
                $stmt = $conn2->prepare($delete);
                $stmt->execute();
            }

            // delete the supplier id
            $delete = "DELETE FROM `suppliers` WHERE `supplier_id` = '".$delete_supplier."'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();

            // show success message
            echo "<p class='text-success'>Supplier has been deleted successfully!</p>";
        }elseif(isset($_POST['get_expense_category'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");

            // passed data
            $get_expense_category = $_POST['get_expense_category'];
            $get_expense_subcategory = $_POST['expense_category_id'];
            $id = $_POST['id'];
            $value = $_POST['value'];
            
            $select = "SELECT * FROM `expense_category` WHERE `expense_id` = '".$get_expense_subcategory."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $sub_categories = [];
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $expense_sub_categories = $row['expense_sub_categories'];
                    $sub_categories = isJson($expense_sub_categories) ? json_decode($expense_sub_categories) : [];
                }
            }

            // expense subcategories
            $data_to_display = "<select class='form-control w-75' id='".$id."'><option hidden value=''>Select an Option</option>";
            for($index = 0; $index < count($sub_categories); $index++){
                $id = $sub_categories[$index]->id;
                $name = $sub_categories[$index]->name;
                $selected = $value == $id ? "selected" : "";
                $data_to_display .= "<option ".$selected." value='".$id."' >".$name."</option>";
            }
            $data_to_display .= "</select>";

            // display select
            echo $data_to_display;
        }elseif(isset($_POST['update_revenue'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $revenue_id = $_POST['revenue_id'];
            $revenue_name = $_POST['revenue_name'];
            $revenue_amount = $_POST['revenue_amount'];
            $revenue_date = date("Ymd",strtotime($_POST['revenue_date']));
            $customer_name = $_POST['customer_name'];
            $customer_contacts_revenue = $_POST['customer_contacts_revenue'];
            $contact_person = $_POST['contact_person'];
            $revenue_description = $_POST['revenue_description'];
            $edit_revenue_cash_activity = $_POST['edit_revenue_cash_activity'];
            $reportable_status_edit = $_POST['reportable_status_edit'];
            $revenue_category = $_POST['revenue_category'];
            $mode_of_revenue_payment_edit = $_POST['mode_of_revenue_payment_edit'];
            $payment_code_edit = $_POST['payment_code_edit'];
            $revenue_sub_category = $_POST['revenue_sub_category'];

            // UPDATE THE DATABASES ACCORDINGLY 
            $update = "UPDATE `school_revenue` SET `name` = ?, `amount` = ?, `date_recorded` = ?, `customer_name` = ?, `customer_contact` = ?, `contact_person` = ?, `revenue_description` = ?, `revenue_category` = ?, `cash_flow_activities` = ?, `reportable_status` = ?, `mode_of_payment` = ?, `payment_code` = ?, `revenue_sub_category` = ? WHERE `id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("ssssssssssssss",$revenue_name,$revenue_amount,$revenue_date,$customer_name,$customer_contacts_revenue,$contact_person,$revenue_description,$revenue_category,$edit_revenue_cash_activity, $reportable_status_edit, $mode_of_revenue_payment_edit, $payment_code_edit, $revenue_sub_category, $revenue_id);
            $stmt->execute();

            // echo results
            $log_text = "Revenue \"".$revenue_name."\" has been updated successfully!";
            log_finance($log_text);
            echo "<p class='text-success'>Revenue records updated successfully!</p>";
        }elseif(isset($_POST['delete_revenue'])){
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $revenue_id = $_POST['revenue_id'];
            // select the revenue name
            $select = "SELECT * FROM school_revenue WHERE id = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$revenue_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $revenue_name = "N/A";
            if($result){
                if($row = $result->fetch_assoc()){
                    $revenue_name = ucwords(strtolower($row['name']));
                }
            }

            // delete from school revenue
            $delete = "DELETE FROM `school_revenue` WHERE `id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$revenue_id);
            $stmt->execute();

            echo "<p class='text-success'>Revenue record has been successfully deleted!</p>";
            $log_text = "Revenue \"".$revenue_name."\" has been deleted successfully!";
            log_finance($log_text);

        }elseif(isset($_POST['get_revenue_categories'])){
            include("../../connections/conn2.php");
            // get the expense categories
            $revenue_id = $_POST['revenue_id'];
            $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'; ";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<p class='text-danger'>No revenue categories set!</p>";
            if($result){
                if($row = $result->fetch_assoc()){
                    $valued = $row['valued'];
                    if(isJson($valued)){
                        $data_to_display = "<select class='form-control w-100' id='".$revenue_id."'><option hidden >Select an option!</option>";
                        $valued = json_decode($valued);
                        foreach ($valued as $key => $value) {
                            $data_to_display.="<option value='".$value->category_id."'>".$value->category_name."</option>";
                        }
                        $data_to_display.="</select>";
                    }
                }
            }
            echo $data_to_display;

        }elseif(isset($_POST['update_asset'])){
            include("../../connections/conn2.php");
            $update = "UPDATE `asset_table` SET `asset_name` = ?, `asset_category` = ?, `date_of_acquiry` = ?, `description` = ?, `acquisition_option` = ?, `acquisition_rate` = ?, `orginal_value` = ? WHERE `asset_id` = ?";
            $stmt = $conn2->prepare($update);
            $acquiry_date = date("YmdHis",strtotime($_POST['acquiry_date']));
            $stmt->bind_param("ssssssss",$_POST['asset_name'],$_POST['asset_category'],$acquiry_date,$_POST['description'],$_POST['acquisition_option'],$_POST['rate'],$_POST['original_value'],$_POST['asset_id']);
            $stmt->execute();

            echo "<p class='text-success'>Asset has been updated successfully!</p>";
        }
    }

    function deleteFile($filePath) {
        if (file_exists($filePath)) {
          if (unlink($filePath)) {
            return true; // File deletion successful
          } else {
            return false; // Failed to delete the file
          }
        } else {
          return false; // File does not exist
        }
      }

    function replaceSpacesAndSpecialChars($string) {
        // Replace spaces with underscores
        $string = str_replace(' ', '_', $string);
        
        // Define special characters
        $specialChars = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+', '=', '[', ']', '{', '}', '|', '\\', '/', ':', ';', '"', "'", '<', '>', ',', '.', '?');
        
        // Replace special characters with underscores
        $string = str_replace($specialChars, '_', $string);
        
        // Remove consecutive underscores
        $string = preg_replace('/_+/', '_', $string);
        
        return $string;
      }

    function getSalaryExpQuaterly($conn2,$term_period){
        $salaries = [];
        $select = "SELECT SUM(`amount_paid`) AS 'Total' FROM `salary_payment` WHERE `date_paid` BETWEEN ? AND ?;";
        for ($index=0; $index < count($term_period); $index++) {
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$term_period[$index][0],$term_period[$index][1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    if (isset($row['Total'])) {
                        array_push($salaries,$row['Total']);
                    }else {
                        array_push($salaries,"0");
                    }
                }
            }
        }
        return $salaries;
    }
    function getSalaryExp($conn2,$term_period){
        $select = "SELECT SUM(`amount_paid`) AS 'Total' FROM `salary_payment` WHERE `date_paid` BETWEEN ? AND ?;";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$term_period[0],$term_period[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $salaries = [];
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['Total'])) {
                    array_push($salaries,$row['Total']);
                }else {
                    array_push($salaries,"0");
                }
            }
        }
        $stmt->bind_param("ss",$term_period[2],$term_period[3]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['Total'])) {
                    array_push($salaries,$row['Total']);
                }else {
                    array_push($salaries,"0");
                }
            }
        }
        $stmt->bind_param("ss",$term_period[4],$term_period[5]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['Total'])) {
                    array_push($salaries,$row['Total']);
                }else {
                    array_push($salaries,"0");
                }
            }
        }
        return $salaries;
    }

    function payMode($value){
        if ($value == "bank") {
            return "<span class='green_notice'>-b</span>";
        }elseif ($value == "cash") {
            return "<span class='green_notice'>-c</span>";
        }elseif ($value == "m-pesa") {
            return "<span class='green_notice'>-m</span>";
        }else {
            return "<span class='green_notice'>-u</span>";
        }
    }
    function checkIN($array,$element){
        if (count($array) > 0) {
            for ($index=0; $index < count($array); $index++) { 
                if ($array[$index] == $element) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }
    function getSalary($dates,$conn2,$staff_id,$first_salary = -1){
        $first_pay = getFirstPayDate($conn2,$staff_id);
        $select = "SELECT `effect_month`, `salary_amount` FROM `payroll_information` WHERE `staff_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $times = "";
        $salary = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $times = $row['effect_month'];
                $salary = $row['salary_amount'];
            }
        }
        $f_date = explode(":",$first_pay);
        $f_d_date = date("Y-m-d",strtotime("01-".$f_date[0]."-".$f_date[1]));
        if ($f_d_date == $dates && $first_salary != -1) {
            // echo $first_salary." ".$f_d_date;
            return $first_salary;
        }
        if (isset($times) && strlen($times) > 0) {
            $time_divide = explode(",",$times);
            if (count($time_divide) == 1) {
                return $salary;
            }elseif (count($time_divide) > 1) {
                $exploded_salo = explode(",",$salary);
                for ($index=0; $index < count($time_divide); $index++) {
                    $epl_time = explode(":",$time_divide[$index]);
                    if ($index+1 < count($time_divide)) {
                        $nextMonth = explode(":",$time_divide[$index+1]);
                    }else {
                        $count = count($exploded_salo);
                        return $exploded_salo[$count-1];
                        break;
                    }
                    $date_now = date("Y-m-d",strtotime("01-".$epl_time[0]."-".$epl_time[1]));
                    $next_mon = date("Y-m-d",strtotime("01-".$nextMonth[0]."-".$nextMonth[1]));
                    if ($dates >=$date_now && $dates<$next_mon) {
                        return $exploded_salo[$index];
                        break;
                    }
                }
            }
        }else {
            return 0;
        }
    }

    function getCurrentBalTime($conn2,$staff_id){
        $select = "SELECT `current_balance_monNyear`,`current_balance` FROM `payroll_information` WHERE `staff_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_return = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $data_return = "0,0";
                if (isset($row['current_balance_monNyear'])) {
                    $data_return = $row['current_balance_monNyear'].",".$row['current_balance'];
                }
            }else {
                $data_return = 0;
            }
        }
        return $data_return;
    }

    function getFirstPayDate($conn2,$staff_id){
        $select = "SELECT `effect_month` FROM `payroll_information` WHERE `staff_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $first_month = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $first_month = $row['effect_month'];
            }
        }
        if (strlen($first_month) > 0) {
            $divide_mon = explode(",",$first_month);
            $first_month = $divide_mon[0];
        }
        return $first_month;
    }

    function getTotalSalo($conn2,$staff_id){
        $select = "SELECT SUM(`amount_paid`) AS 'Total' FROM `salary_payment` WHERE `staff_paid` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_salo = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['Total'])) {
                    $total_salo = $row['Total'];
                }else {
                    $total_salo = 0;
                }
            }
        }
        return $total_salo;
    }

    function salaryBalanceToBePaid($id,$conn2){
        $data = getTotalBalance($id,$conn2);
        // $tot = getTotalSalaryBalance($data);
        // $tot = 0;
        $ids = $_GET['ids'];
        $tot = totalSalaryBal($ids,$conn2);
        return $tot;
    }
    function addMonthTOdate($date,$months){
        $dated = date_create($date);
        $times = $months." months";
        date_add($dated, date_interval_create_from_date_string($times));
        return date_format($dated, 'Y-m-d');
    }

    function getCurrentSalo($data){
        $mysalo = explode(",",$data);
        $counted = count($mysalo);
        $current_salo = $mysalo[$counted-1];
        return $current_salo;
    }
    function getBalanceOfAdvancement($salary,$exceed){
        $remainder = $exceed%$salary;
        return $remainder;
    }

    function getTotalSalaryBalance($data){
        //check where the last balance falls into what salary category
        // array_push($data,$row['current_balance'],$row['current_balance_monNyear'],$row['salary_amount'],$row['effect_month']);
        // date=0 = current balance amount for the single month
        // date=1 = where the current balance is at (month and year)
        // date=2 = salary amount, what he is to be paid monthly
        // date=3 = when the payment of the client was first recorded
        if (count($data) > 0) {
            $salary_evo = $data[3];
            $salary_index = getSalaryIndex($data[1],$data[2],$salary_evo);
            //get how manytime its going to be paid
            $payPlan = getPayPlan($data[1],$salary_index,$data[2],$data[3]);
            //after getting plan calculate the balance
            $sum_total = 0;
            for ($payind=0; $payind < count($payPlan); $payind++) { 
                $salo_times = explode(":",$payPlan[$payind]);
                $product=($salo_times[1]*$salo_times[0]);
                $sum_total+=$product;
            }
            //split salary
            $salarysplit = explode(",",$data[2]);
            $salarytodeduct = $salarysplit[$salary_index];
            //deduct the salary amount from the total and add the last balance
            $salary_total = ($sum_total-$salarytodeduct) + ($data[0] * 1);

            return $salary_total;
        }
        return 0;
    }
    function totalSalaryBal($staff_id,$conn2){
        // get the staff salary information and check the last time they were paid and the balance
        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_balance_date = date("M:Y");
        $curr_balance = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $curr_balance_date = $row['current_balance_monNyear'];
                $curr_balance = $row['current_balance'];
            }
        }
        $curr_date = explode(":",$curr_balance_date);
        // start from the last time they were paid to today

        $start_month = date("Y-m-d",strtotime("01-".$curr_date[0]."-".$curr_date[1]));
        $today = date("Y-m-d",strtotime("01-".date("m")."-".date("Y")));
        // echo "Between ". $start_month." and ". $today;
        // get the difference from today to when he was last paid
        $date1=date_create($start_month);
        $date2=date_create($today);
        $diff=date_diff($date1,$date2);
        $difference_months = $diff->format("%R%m");
        $total_to_be_paid = $curr_balance;
        if ($difference_months >= 0) {
            for ($index=0; $index < ($difference_months); $index++) { 
                // add the date by months
                $date=date_create($start_month);
                date_add($date,date_interval_create_from_date_string("1 month"));
                $start_month = date_format($date,"Y-m-d");
                if ($today == $start_month) {
                    break;
                }

                // get salary to be paid for that month
                $salary = getSalary($start_month,$conn2,$staff_id);
                // echo "<br>".$start_month." salo = ".$salary;
                $total_to_be_paid += $salary;
            }
            // echo "<br>Total = ".($total_to_be_paid);
            return ($total_to_be_paid);
        }
        // echo $today;
        return 0;

    }
    function getPayPlan($last_paid,$salary_index,$salary_list,$effect_month){
        $salary_arr = explode(",",$salary_list);
        $lastpay = explode(":",$last_paid);
        $last_paids = date("Y-m-d",strtotime("01-".$lastpay[0]."-".$lastpay[1]));
        //split the months to arrays
        $salo_evo_per = explode(",",$effect_month);
        $nextMonth = $last_paids;
        $payPlan = [];
        for ($index=$salary_index; $index < count($salo_evo_per); $index++) {
            //take the last time he was paid and add one month to it
            //if there is a next month
            if ($index+1 < count($salo_evo_per)) {
                //count the number of months to that month
                $last_pay1 = explode(":",$salo_evo_per[$index+1]);
                $last_paider = date("Y-m-d",strtotime("01-".$last_pay1[0]."-".$last_pay1[1]));
                //echo "<br>".$salary_arr[$index];
                //go to the nexmonth
                //if the next month is not greater or equal to today or the next month
                $xs=0;
                for(;;){
                    //echo $nextMonth."<br>";
                    if ($nextMonth < $last_paider) {
                        //echo "<br>".$xs." ".$nextMonth." > to ".$last_paider;
                        //echo $nextMonth;
                        $xs++;
                    }else {
                        break;
                    }
                    $no = 1;
                    $nextMonth = addMonthsFinance($no,$nextMonth);
                }
                $string = $salary_arr[$index].":".$xs;
                array_push($payPlan,$string);
            }else{
                $create_date = date("Y-m");
                $newDate = $create_date."-01";
                $last_paider = $newDate;
                //echo "<br>".$salary_arr[$index];
                //go to the nexmonth
                //if the next month is not greater or equal to today or the next month
                $xs=0;
                for(;;){
                    //echo $nextMonth."<br>";
                    if ($nextMonth < $last_paider) {
                        //echo "<br>".$xs." ".$nextMonth." > to ".$last_paider;
                        //echo $nextMonth;
                        $xs++;
                    }else {
                        break;
                    }
                    $no = 1;
                    $nextMonth = addMonthsFinance($no,$nextMonth);
                }
                $string = $salary_arr[$index].":".$xs;
                array_push($payPlan,$string);
            }
        }
        //echo $salary_arr[$salary_index];
        return $payPlan;
    }

    function addMonthsFinance($mon,$month){
        $nt_mon = date_create($month);
        $no_of_mons = $mon." Months";
        $nxt_mon = date_add($nt_mon, date_interval_create_from_date_string($no_of_mons));
        $nextMonth = date_format($nxt_mon, 'Y-m-d');
        return $nextMonth;
    }

    function addYears($number,$year){
        $init_year = date_create($year);
        $no_of_mons = $number." Year";
        $nxt_yr = date_add($init_year, date_interval_create_from_date_string($no_of_mons));
        $next_year = date_format($nxt_yr, 'Y');
        return $next_year;
    }

    function getSalaryIndex($last_paid,$curr_salo,$salary_evo){
        if (strlen($salary_evo) > 0) {
            //explode the salary evolve to different time frames
            $salo_evo_arr = explode(",",$salary_evo);
            for ($index=0; $index < count($salo_evo_arr); $index++) { 
                $salary = explode(":",$salo_evo_arr[$index]);
                $date = date("Y-m-d",strtotime("01-".$salary[0]."-".$salary[1]));
                $last_pay = explode(":",$last_paid);
                $lastpay = date("Y-m-d",strtotime("01-".$last_pay[0]."-".$last_pay[1]));
                if ($lastpay >= $date) {
                    return $index;
                    break;
                }
            }
        }
    }
    function getTotalBalance($id,$conn2){
        $select = "SELECT `current_balance`,`current_balance_monNyear`,`salary_amount`,`effect_month` FROM `payroll_information` WHERE `staff_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                array_push($data,$row['current_balance'],$row['current_balance_monNyear'],$row['salary_amount'],$row['effect_month']);
            }
        }
        return $data;
    }
    function getStaffName($conn,$id){
        $select = "SELECT `fullname`,`gender` FROM `user_tbl` WHERE `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $prefix = "Mrs. ";
                if ($row['gender'] == "M") {
                    $prefix = "Mr. ";
                }
                $name = $prefix.$row['fullname'];
                return $name;
            }
        }
        return "Null";
    }
    function getStaffInformations($conn,$id){
        $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row;
            }
        }
        return [];
    }
    function checkEnrolled($conn2,$id){
        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums > 0) {
            return true;
        }
        return false;
    }
    function getTermPeriod($conn2){
        // $select = "SELECT `start_time`,`end_time` FROM `academic_calendar` WHERE 
        //             (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
        //             OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
        //             OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
        $select = "SELECT `start_time`,`end_time` FROM `academic_calendar` WHERE 
                    (`term` = 'TERM_1') 
                    OR (`term` = 'TERM_2') 
                    OR (`term` = 'TERM_3');";
        $stmt = $conn2->prepare($select);
        $date = date("Y");
        // $stmt->bind_param("sss",$date,$date,$date);
        $stmt->execute();
        $result = $stmt->get_result();
        $dates = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($dates,$row['start_time'],$row['end_time']);
            }
        }
        //echo count($dates);
        return $dates;
    }

    function getLastTimePaying($conn2,$stud_id){
        $select = "SELECT * FROM `finance` WHERE `stud_admin` = ? ORDER BY `transaction_id` DESC LIMIT 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$stud_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['date_of_transaction'];
            }
        }
        return date("Y-m-d",strtotime("3 hours"));
    }
    function getPeriods($years,$conn2){
        $select = "";
    }

    function checkPresent($array,$string){
        if (count($array) > 0) {
            for ($index=0; $index < count($array); $index++) { 
                $my_str = $array[$index];
                if (strlen($my_str) > 0) {
                    $my_str_split = explode(":",$my_str);
                    if ($my_str_split[0] == $string) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    function getValues($array,$string){
        if (count($array) > 0) {
            for ($index=0; $index < count($array); $index++) { 
                $my_str = $array[$index];
                if (strlen($my_str) > 0) {
                    $my_str_split = explode(":",$my_str);
                    if ($my_str_split[0] == $string) {
                        return $my_str_split[1];
                    }
                }
            }
        }
        return "0";
    }
    function getAllExpenseNames($term_expense){
        //its a multilevel array
        $allitems = [];
        for ($index1=0; $index1 < count($term_expense); $index1++) { 
            for ($index2=0; $index2 < count($term_expense[$index1]); $index2++) { 
                $object = $term_expense[$index1][$index2];
                //array_push($allitems,$object);
                //split the text
                if (strlen($object) > 0) {
                    $stringExp = explode(":",$object);
                    if (!isPresent($allitems,$stringExp[0])) {
                        array_push($allitems,$stringExp[0]);
                    }
                }
            }
        }
        return $allitems;
    }
    function isPresent($array,$string){
        if (count($array) > 0 ) {
            for ($indexes=0; $indexes <count($array) ; $indexes++) { 
                if ($string == $array[$indexes]) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    // get taxes quaterly
    function getTaxesQuaterly($array_period,$conn2){
        $termExp = [];
        $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` = 'taxes'  GROUP BY `Expense`";
        for ($index=0; $index < count($array_period); $index++) {
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$array_period[$index][0],$array_period[$index][1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $taxes = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $taxes = $row['Total'];
                }
            }
            array_push($termExp,$taxes);
        }
        return $termExp;
    }
    function getTaxes($arrayPeriod,$conn2){
        $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` = 'taxes'  GROUP BY `Expense`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
        $stmt->execute();
        $termExp = [];
        $result = $stmt->get_result();
        $taxes = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $taxes = $row['Total'];
            }
        }
        array_push($termExp,$taxes);
        //second term
        $taxes = 0;
        $stmt->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $taxes = $row['Total'];
            }
        }
        array_push($termExp,$taxes);
        //third term
        $taxes = 0;
        $stmt->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $taxes = $row['Total'];
            }
        }
        array_push($termExp,$taxes);
        //echo $arrayPeriod[4]." - ".$arrayPeriod[5];

        return $termExp;
    }

    function getExpensesQuaterly($array_period,$conn2){
        $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` != 'taxes'  GROUP BY `Expense`";
        $termExp = [];
        for ($index=0; $index < count($array_period); $index++) { 
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$array_period[$index][0],$array_period[$index][1]);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $termPexp1 = [];
                while ($row = $result->fetch_assoc()) {
                    array_push($termPexp1,$row['Expense'].":".$row['Total']);
                }
                array_push($termExp,$termPexp1);
            }
        }

        // term expense
        return $termExp;
    }

    function getExpenses($arrayPeriod,$conn2){
        $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` != 'taxes'  GROUP BY `Expense`";
        $termExp = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $termPexp1 = [];
            while ($row = $result->fetch_assoc()) {
                array_push($termPexp1,$row['Expense'].":".$row['Total']);
            }
            array_push($termExp,$termPexp1);
        }
        //second term
        $stmt->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $termPexp1 = [];
            while ($row = $result->fetch_assoc()) {
                array_push($termPexp1,$row['Expense'].":".$row['Total']);
            }
            array_push($termExp,$termPexp1);
        }
        //third term
        $stmt->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $termPexp1 = [];
            while ($row = $result->fetch_assoc()) {
                array_push($termPexp1,$row['Expense'].":".$row['Total']);
            }
            array_push($termExp,$termPexp1);
        }
        return $termExp;
    }

    function getOtherRevenueQuaterly($conn2, $year, $annual_quaters = null){
        if($annual_quaters == null){
            $annual_quaters = [];
            $year_1 = (($year*1) - 1);
            $q1a = date("Ymd",strtotime($year_1."0701"));
            $q1b = date("Ymd",strtotime($year_1."0930"));
            array_push($annual_quaters,[$q1a,$q1b]);
            $q2a = date("Ymd",strtotime($year_1."1001"));
            $q2b = date("Ymd",strtotime($year_1."1231"));
            array_push($annual_quaters,[$q2a,$q2b]);
            $q3a = date("Ymd",strtotime($year."0101"));
            $q3b = date("Ymd",strtotime($year."0331"));
            array_push($annual_quaters,[$q3a,$q3b]);
            $q4a = date("Ymd",strtotime($year."0401"));
            $q4b = date("Ymd",strtotime($year."0630"));
            array_push($annual_quaters,[$q4a,$q4b]);
        }else{
            $annual_quaters_edit = [];
            $q1a = date("Ymd",strtotime($annual_quaters[0][0]));
            $q1b = date("Ymd",strtotime($annual_quaters[0][1]));
            array_push($annual_quaters_edit,[$q1a,$q1b]);
            $q2a = date("Ymd",strtotime($annual_quaters[1][0]));
            $q2b = date("Ymd",strtotime($annual_quaters[1][1]));
            array_push($annual_quaters_edit,[$q2a,$q2b]);
            $q3a = date("Ymd",strtotime($annual_quaters[2][0]));
            $q3b = date("Ymd",strtotime($annual_quaters[2][1]));
            array_push($annual_quaters_edit,[$q3a,$q3b]);
            $q4a = date("Ymd",strtotime($annual_quaters[3][0]));
            $q4b = date("Ymd",strtotime($annual_quaters[3][1]));
            array_push($annual_quaters_edit,[$q4a,$q4b]);
            $annual_quaters = $annual_quaters_edit;
        }


        $select = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE  `reportable_status` = '1' AND  `date_recorded` BETWEEN ? AND ?";
        $school_revenue = [];
        for ($index=0; $index < count($annual_quaters); $index++) {
            $term_start = $annual_quaters[$index][0];
            $term_end = $annual_quaters[$index][1];

            // prepare select
            $revenue = 0;
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$term_start,$term_end);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $revenue = $row['Total']*1;
                }
            }

            // array push
            array_push($school_revenue,$revenue);
        }
        // $stmt->bind_param("ss",)
        return $school_revenue;
    }

    function getOtherRevenue($conn2, $year = null){
        $year = $year == null ? date("Y") : $year;
        $get_term_period = getTermPeriods($conn2, $year);
        $select = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `date_recorded` BETWEEN ? AND ?";
        $school_revenue = [];
        for ($index=0; $index < count($get_term_period)/2; $index++) {
            $time_period = $index == 0 ? [$get_term_period[0],$get_term_period[1]] : ($index == 1 ? [$get_term_period[2],$get_term_period[3]] : [$get_term_period[4],$get_term_period[5]]);
            $term_start = date("Ymd",strtotime($time_period[0]));
            $term_end = date("Ymd",strtotime($time_period[1]));

            // prepare select
            $revenue = 0;
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$term_start,$term_end);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $revenue = $row['Total']*1;
                }
            }

            // array push
            array_push($school_revenue,$revenue);
        }
        // $stmt->bind_param("ss",)
        return $school_revenue;
    }

    function getTermIncomeQuaterly($array_period,$conn2){
        $term_pay = [];
        $select = "SELECT sum(`amount`)  AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
        for ($index=0; $index < count($array_period); $index++) {
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$array_period[$index][0],$array_period[$index][1]);
            $stmt->execute();
            $result = $stmt->get_result();
            $err = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $total = strlen(trim($row['Total'])) > 0 ? $row['Total'] : 0;
                    if ($total != 0 && $total != null) {
                        array_push($term_pay,($row['Total'] != null ? $row['Total'] : 0));
                    }else {
                        $err++;
                        array_push($term_pay,0);
                    }
                }else {
                    array_push($term_pay,"0");
                }
            }else {
                array_push($term_pay,"0");
            }
        }
        return $term_pay;
    }

    function getTermIncome($arrayPeriod,$conn2){
        $term_pay = [];
        $select = "SELECT sum(`amount`)  AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
        $stmt = $conn2->prepare($select);
        $stmt ->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $err = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $total = $row['Total'];
                if ($total >= 0 || $total != null) {
                    array_push($term_pay,($row['Total'] != null ? $row['Total'] : 0));
                }else {
                    $err++;
                    array_push($term_pay,0);
                }
            }else {
                array_push($term_pay,"0");
            }
        }else {
            array_push($term_pay,"0");
        }
        $stmt ->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $total = $row['Total'];
                if ($total >= 0 || $total != null) {
                    array_push($term_pay,($row['Total'] != null ? $row['Total'] : 0));
                }else {
                    array_push($term_pay,0);
                    $err++;
                }
            }else {
                array_push($term_pay,"0");
            }
        }else {
            array_push($term_pay,"0");
        }
        $stmt ->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $total = $row['Total'];
                if ($total >= 0 || $total != null) {
                    array_push($term_pay,($row['Total'] != null ? $row['Total'] : 0));
                }else {
                    $err++;
                    array_push($term_pay,0);
                }
            }else {
                array_push($term_pay,"0");
            }
        }else {
            array_push($term_pay,"0");
        }
        if ($err == 3) {
            echo "<p class='red_notice'>Edit your school academic calender first before generating your financial statement</p>";
        }
        return $term_pay;
    }
    function getTermPeriods($conn2, $year = null){
        $date = $year == null ? date("Y")."0101" : $year."0101";
        // $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE 
        // (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
        // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
        // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
        $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE 
        (`term` = 'TERM_1') 
        OR (`term` = 'TERM_2') 
        OR (`term` = 'TERM_3');";
        $stmt = $conn2->prepare($select);
        // $stmt->bind_param("sss",$date,$date,$date);
        $stmt->execute();
        $period = [];
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($period,date("Y",strtotime($date)).substr($row['start_time'],4),date("Y",strtotime($date)).substr($row['end_time'],4));
            }
        }
        return $period;
    }
    function getClassAssignFee($fees_id,$conn2){
        $select = "SELECT `classes` FROM `fees_structure` WHERE `ids` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$fees_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $classlist = $row['classes'];
                $cl_ist = explode(",",$classlist);
                $newlist = "";
                for ($ind=0; $ind < count($cl_ist); $ind++) {
                    $newlist.=rBkts($cl_ist[$ind]).",";
                }
                $newlist = substr($newlist,0,strlen($newlist)-1);
                return $newlist;
            }
        }
    }
    function rBkts($string){
        $string = trim($string);
        if (strlen($string)>1) {
            return substr($string,1,strlen($string)-2);
        }else {
            return $string;
        }
    }
    function className($data){
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

    function createtablefinance($results){
        $tableinformation ="<hr><h6 style='text-align:center;font-weight:600;'>Transaction Detals Results</h6>";
        $tableinformation .= "<div class='tableme' id='fin_tables'><table  class='table'><tr>
                            <th>No.</th>
                            <th>Adm no</th>
                            <th>Paid Amount</th>
                            <th>D.O.P</th>
                            <th>T.O.P</th>
                            <th>M.O.P</th>
                            <th>Purpose</th></tr>
                            ";
        if ($results) {
            $transaction_data = "[";
            $xss =0;
            $my_second_data = [];
            while ($row = $results->fetch_assoc()) {
                if ($row['amount'] != "0") {
                    $row['student_name'] = ucwords(strtolower(getName1($row['stud_admin'])));
                    $row['amount_sort'] = $row['amount'];
                    $row['balance'] = "Kes ".number_format($row['balance']);
                    $row['trans_date_sort'] = date("YmdHis",strtotime($row['date_of_transaction']."".$row['time_of_transaction']));
                    $row['date_of_transaction_1'] = date("D dS M Y",strtotime($row['date_of_transaction']));
                    $row['time_of_transaction_1'] = date("H:i:s",strtotime($row['time_of_transaction']));
                    array_push($my_second_data,$row);
                    $xss++;
                    $tableinformation.="<tr><td>".$xss."</td>";
                    $tableinformation.="<td>".$row['stud_admin']."</td>";
                    $tableinformation.="<td>".comma($row['amount'])."</td>";
                    $tableinformation.="<td>".$row['date_of_transaction']."</td>";
                    $tableinformation.="<td>".$row['time_of_transaction']."</td>";
                    $tableinformation.="<td>".$row['mode_of_pay']."</td>";
                    $tableinformation.="<td>".$row['payment_for']."</td></tr>";
                    $trans_date = date("dS M Y H:i:s A",strtotime($row['date_of_transaction']."".$row['time_of_transaction']));
                    $trans_date_sort = date("YmdHis",strtotime($row['date_of_transaction']."".$row['time_of_transaction']));
                    $student_name = getName1($row['stud_admin']);
                    $transaction_data.="{\"stud_admin\":\"".$row['stud_admin']."\",\"amount\":\"".comma($row['amount'])."\",\"date_of_transaction\":\"".$trans_date."\",\"student_name\":\"".ucwords(strtolower($student_name))."\",\"mode_of_pay\":\"".$row['mode_of_pay']."\",\"payment_for\":\"".ucwords(strtolower($row['payment_for']))."\",\"amount_sort\":".$row['amount'].",\"trans_date_sort\":".$trans_date_sort.",\"support_document\":".$row['support_document']."},";
                }
            }
            $transaction_data = substr($transaction_data,0,-1)."]";
            $tableinformation.="</table></div>";
            $tableinformation.="<p style='margin-top:10px;'>HINT: <br> <small>D.O.P = Date of Payment <br>T.O.P = Time of Payment <br>M.O.P = Mode of Payment</small></p>";
            
            if ($xss>0) {
                $json_dec = json_encode($my_second_data);
                return "<p class='hide' id='fees_data'>".$json_dec."</p>";
            }else {
                return "<p class='hide' id='fees_data'></p>";
                return "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p class='' >No records found! </p>
                        </div>" ;
            }
        }else {
            return "<p class='hide' id='fees_data'></p>";
            return "Null";
        }
    }
    function createTotal($stmt){
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results) {
            $table1 = "<div id='my_purpose_table2' ><p>Sorted by mode of pay:</p><div  class='hide' id='purpChartHolder'><canvas id='purpChart' width = '300px' height='300px'></canvas></div><br><table id='mode_table'>";
            $table1.="<tr><th>Mode of pay</th>";
            $table1.="<th>Amount in (ksh)</th></tr>";
            $mpesa =0;
            $cash = 0;
            $bank = 0;
            $reverse = 0;
            $total = 0;
            while ($row = $results->fetch_assoc()) {
                if ($row['mode_of_pay']=='cash') {
                    $cash+=$row['amount'];
                }elseif ($row['mode_of_pay']=='bank') {
                    $bank+=$row['amount'];
                }elseif ($row['mode_of_pay']=='mpesa') {
                    $mpesa+=$row['amount'];
                }elseif ($row['mode_of_pay']=='reverse') {
                    $reverse+=$row['amount'];
                }
            }
            $total = $mpesa+$cash+$bank+$reverse;
            $table1.="<tr><td>M-Pesa</td><td>".comma($mpesa)."</td></tr>";
            $table1.="<tr><td>Cash</td><td>".comma($cash)."</td></tr>";
            $table1.="<tr><td>Bank</td><td>".comma($bank)."</td></tr>";
            $table1.="<tr><td>Reverse</td><td>".comma($reverse)."</td></tr>";
            $table1.="<tr><td><b>Total</b></td><td>".comma($total)."</td></tr>";
            $table1.="</table></div>";
            $table1.="<p id='purpose_values_in' class = 'hide' >{\"MPesa\":".$mpesa.",\"Cash\":".$cash.",\"Bank\":".$bank.",\"reverse\":".$reverse."}</p>";
            if($total>0){
                return $table1;
            }
        }else {
            return "";
        }
    }
    function createTotal2($stmt){
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results) {
            $mpesa =0;
            $cash = 0;
            $bank = 0;
            $total = 0;
            $purposes = getModesOfPay();
            $purpose1= array();
            //create arrays depending on the size of the array and initialize with value 0
            for($d =0;$d<count($purposes);$d++){
                array_push($purpose1,$purposes[$d].($d+1));
                $purpose1[$d] = 0;
            }
            //on instance of the array be found present the amount is assigned to the respective array
            $totals=0;
            while ($row = $results->fetch_assoc()) {
                for ($i=0; $i < count($purposes); $i++) {
                    if (trim($purposes[$i]) == trim($row['payment_for'])) {
                        $purpose1[$i]+=$row['amount'];
                        $totals+=$row['amount'];
                        break;
                    }
                }
            }
            $table1 = "<div  id='my_purpose_table1'><p>Sorted by purpose of pay:</p><div  class='hide' id='modepayChartHolder'><canvas id='modeChart' width = '300px' height='300px'></canvas></div><br><table id='purp_table'>";
            $table1.="<tr><th>Purpose of pay</th>";
            $table1.="<th>Amount in (ksh)</th></tr>";
            $total = $mpesa+$cash+$bank;
            $jsonData = "";
            for ($i=0; $i < count($purpose1); $i++) { 
                if ($purpose1[$i] != 0) {
                    $table1.="<tr><td>".ucwords(strtolower($purposes[$i]))."</td><td>".comma($purpose1[$i])."</td></tr>";
                    $jsonData.="\"".ucwords(strtolower($purposes[$i]))."\":\"".$purpose1[$i]."\",";
                }
            }
            $table1.="<tr><td><b>Total</b></td><td>".comma($totals)."</td></tr>";
            $table1.="</table></div>";
            if (strlen($jsonData) > 0) {
                $jsonData = substr($jsonData,0,(strlen($jsonData) - 1));
                $jsonData = "{".$jsonData."}";
            }
            $table1.="<p class='hide' id='modepay_jsondata'>".$jsonData."</p>";
            if ($totals>0) {
                return $table1;
            }else {
                return "";
            }
        }else {
            return "";
        }
    }
    function getModesOfPay(){
        include("../../connections/conn2.php");
        $selected = "SELECT `payment_for`, sum(`amount`) AS 'Total' FROM `finance` GROUP BY `payment_for`;";
        $stmt = $conn2->prepare($selected);
        $stmt->execute();
        $purposeofpay = array("admission fees");
        $res = $stmt->get_result();
        if ($res) {    
            while ($row = $res->fetch_assoc()) {
                $present = 0;
                for ($i=0; $i < count($purposeofpay); $i++) { 
                    if ($purposeofpay[$i]==$row['payment_for']) {
                        $present=1;
                    }
                }
                if ($present==0) {
                    array_push($purposeofpay,$row['payment_for']);
                }
            }
            return $purposeofpay;
        }
    }
    function checkadmno($admno){
        include("../../connections/conn2.php");
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = ? LIMIT 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if($rnums>0){
            return 1;
        }else {
            return 0;
        }
        $stmt->close();
        $conn2->close();
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
    function getName($admno){
        include("../../connections/conn2.php");
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
        $conn2->close();
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
    function getClass($admno){
        include("../../connections/conn2.php");
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
        $conn2->close();
    }
    function getClassV2reports($admno,$conn2){
        // include_once("../../sims/ajax/connections/conn2.php");
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
        
        // $stmt->close();
        // $conn2->close();
    }
    function studentInclass($class,$conn2){
        $select = "SELECT concat(`first_name`,' ',`second_name`) AS 'Names',`adm_no` FROM `student_data` WHERE `stud_class` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $result = $stmt->get_result();
        $students = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datas = $row['Names']."^".$row['adm_no'];
                array_push($students,$datas);
            }
        }
        return $students;
    }
    function getTerm(){
        $date = date("Y-m-d");
        $select = "SELECT `term` FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
        include("../../connections/conn2.php");
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
    function getTermV2($conn2){
        $date = date("Y-m-d");
        $select = "SELECT * FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
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
    }
    function checkNewlyBoard($admno,$conn2){
        $select = "SELECT * FROM `boarding_list` WHERE `date_of_enrollment` > ?  and `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $date = date("Y-m-d", strtotime("-719 hour"));
        $stmt->bind_param("ss",$date,$admno);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums > 0) {
            return true;
        }else {
            return false;
        }
    }
    function getBalance($admno,$term,$conn2){
        $balance = calculatedBalanceReport($admno,$term,$conn2);
        return $balance;
    }

    function getBalanceReports($admno,$term,$conn2){
        // //get the fee balance from the latest transaction record if not found then calculate how much the students is to pay
        // $lastbal = lastBalance($admno,$conn2);
        // // get the student is enrolled in the transport system
        // $is_trans = isTransport($conn2,$admno);
        // $check_recent_boarding = checkNewlyBoard($admno,$conn2);
        // // get the fees payment per term for the transport system
        // $transport_payment = 0;
        // if($is_trans == 1){
        //     // $transport_payment = transportBalanceSinceAdmission($conn2,$admno);
        // }
        // // check if the student has made any payments before the term started
        // $date_term_began = date("Ymd",strtotime(getTermStart($conn2,$term)));
        // $last_paid_time  = date("Ymd",strtotime(getLastTimePaying($conn2,$admno)));
        // // add next term balance
        // $current_term = 0;
        // if ($date_term_began > $last_paid_time) {
        //     $daro_ss = getNameReport($admno,$conn2);
        //     $getclass = explode("^",$daro_ss)[1];
        //     $current_term = getFeesTerm($term,$conn2,$getclass,$admno);
        //     // echo $current_term;
        // }

        // // IF THE CURRENT DATE IS PAST THE LAST ACADEMIC YEAR ADD THE BALANCE TO THIS YEAR
        // $date_term_began = date("Ymd",strtotime(getTermStart($conn2,"TERM_1")));

        // $last_paid_time  = date("Ymd",strtotime(getLastTimePaying($conn2,$admno)));

        // $balanceds = 0;
        // if($date_term_began > $last_paid_time){
        //     $balanceds += getFeesAsPerTermBoarders($term,$conn2,$getclass,$admno);
        // }

        // if ($lastbal > 0 && !$check_recent_boarding) {
        //     return $lastbal + $transport_payment ;
        // }else {
        //     $balance = calculatedBalanceReport($admno,$term,$conn2);
        //     return $balance + $transport_payment + $balanceds;
        // }
        // $term = getTermV2($conn2);
        // echo $term;
        $balance = calculatedBalanceReport($admno,$term,$conn2);
        return $balance;
    }
    function getBalanceAdm($admno,$term){
        include("../../connections/conn2.php");
        $balance = calculatedBalanceReport($admno,$term,$conn2);
        return $balance;
    }
    function isTransport($conn2,$admno){
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?;";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            return true;
        }
        return false;
    }
    // get the student payment of transport per term if joined the same term the payment is taken for the only term
    function transportBalance($conn2,$admno,$termed = "null"){
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        // get the date joined
        // get the amount of the student route
        if ($result) {
            if($row = $result->fetch_assoc()){
                $route_id = $row['route_id'];
                $date_of_reg = $row['date_of_reg'];
                $route_val = routeAmount($conn2,$route_id,$date_of_reg);
                // echo $termed;
                if ($termed != "null") {
                    // echo $route_val;
                    if ($termed == "TERM_1") {
                        // echo $route_price;
                        return ($route_val*1);
                    }else if($termed == "TERM_2"){
                        return ($route_val*2);
                    }else if($termed == "TERM_3"){
                        return ($route_val*3);
                    }
                    return 0;
                }
                return $route_val;
                // get the amount the students is supposed to pay depending on the dae of registration and term they in
            }
        }
        return 0;
    }
    function routeName($conn2,$admno,$termed = "null"){
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        // get the date joined
        // get the amount of the student route
        if ($result) {
            if($row = $result->fetch_assoc()){
                $route_id = $row['route_id'];
                $date_of_reg = $row['date_of_reg'];
                $route_val = routeDetailsPay($conn2,$route_id,$date_of_reg);
                $route_name = $route_val['route_name'];
                $route_price = $route_val['route_price'];
                return [$route_name,$route_price];
                // get the amount the students is supposed to pay depending on the dae of registration and term they in
            }
        }
        return ["Null",0];
    }
    /**
     * This function is used to check if the user has been enrolled in the transport field before
     * and if they were when were they enrolled and how much is to be deducted to their fees to show the amount paid for the term
     */
    function TransportDeduction($conn2,$students_adm){
        // get the date of transport registration
        $transport_data = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = '".$students_adm."'";
        $stmt = $conn2->prepare($transport_data);
        $stmt->execute();
        $result = $stmt->get_result();
        $present = 0;
        $date_enrolled = date("Y-m-d");
        $route_amount = 0;
        $deregestration = [];
        if($result){
            if($row = $result->fetch_assoc()){
                $present = 1;
                $date_enrolled = $row['date_of_reg'];
                $route_amount = routeAmount($conn2,$row['route_id']);
                $deregestration = json_decode($row['deregistered'] == null ? "[]":$row['deregistered']);
            }
        }
        if ($present == 1) {
            // know what term the student was enrolled
            $select = "SELECT * FROM `academic_calendar` WHERE `start_time` >= '$date_enrolled' AND `end_time` = '$date_enrolled'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $term = "TERM_1";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $term = $row['term'];
                }
            }

            // get the current term
            // if there is no deregistration details return 0
            // echo $deregestration."ps s";
            if (count($deregestration) == 0) {
                return 0;
            }

            // proceed and calcultate the route amount
            if ($deregestration[0] != $term) {
                if($deregestration[0] == "TERM_1"){
                    return 0;
                }elseif($deregestration[0] == "TERM_2"){
                    if ($term == "TERM_1") {
                        return $route_amount;
                    }
                }elseif($deregestration[0] == "TERM_3"){
                    if ($term == "TERM_1") {
                        return $route_amount*2;
                    }elseif ($term == "TERM_2") {
                        return $route_amount;
                    }
                }
            }
            
        }else{
            return 0;
        }
        return 0;
    }
    function get_expense($expense_id,$conn2){
        $select = "SELECT * FROM `expense_category` WHERE `expense_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$expense_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                return $row;
            }
        }
        return null;
    }
    function check_revenue_category($array,$id){
        foreach ($array as $key => $value) {
            if ($value->category_id == $id) {
                return true;
            }
        }
        return false;
    }
    // get the student payment of transport per term if joined the same term the payment is taken for the only term
    function transportBalanceSinceAdmission($conn2,$admno){
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        // get the date joined
        // get the amount of the student route
        if ($result) {
            if($row = $result->fetch_assoc()){
                $route_id = $row['route_id'];
                $date_of_reg = $row['date_of_reg'];
                $deregistered = $row['deregistered'];
                $router_t1 = 0;
                $router_t2 = 0;
                $router_t3 = 0;
                if (isJson_report_fin($deregistered)) {
                    $deregistered = json_decode($deregistered);
                    for ($index=0; $index < count($deregistered); $index++) { 
                        $elems = $deregistered[$index];
                        if($elems->term == "TERM_1"){
                            $router_t1 = routeAmount($conn2,$elems->route);
                        }
                        if($elems->term == "TERM_2"){
                            $router_t2 = routeAmount($conn2,$elems->route);
                        }
                        if($elems->term == "TERM_3"){
                            $router_t3 = routeAmount($conn2,$elems->route);
                        }
                    }
                }

                // get the current term
                $current_term = getTermV2($conn2);
                if ($current_term == "TERM_1") {
                    return $router_t1;
                }elseif ($current_term == "TERM_2") {
                    return $router_t1+$router_t2;
                }elseif ($current_term == "TERM_3") {
                    return $router_t1+$router_t2+$router_t3;
                }else{
                    return $router_t1;
                }
            }
        }
        return 0;
    }
    // get route amount
    function routeAmount($conn2,$route_id,$date_of_reg="null"){
        $select = "SELECT * FROM `van_routes` WHERE `route_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$route_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_price = 0;
        if ($result) {
            if($row = $result->fetch_assoc()){
                $route_price = $row['route_price'];
                return $route_price;
            }
        }
        return 0;
    }
    function routeDetailsPay($conn2,$route_id,$date_of_reg){
        $select = "SELECT * FROM `van_routes` WHERE `route_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$route_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_price = 0;
        if ($result) {
            if($row = $result->fetch_assoc()){
                return $row;
            }
        }
        return [];
    }
    function calculatedBalance($admno,$term,$conn2){
        $daro = getName($admno);
        $getclass = explode("^",$daro);
        $dach = $getclass[1];
        $feestopay = getFeesAsFromTermAdmited($term,$conn2,$dach,$admno);
        $feespaidbystud = getFeespaidByStudent($admno,$conn2);
        $balance = $feestopay - $feespaidbystud;
        
        $balance += lastACADyrBal($admno,$conn2);
        return $balance;
    }
    function calculatedBalanceReport($admno,$term,$conn2){
        $daro = getNameReport($admno,$conn2);
        $getclass = explode("^",$daro);
        $dach = $getclass[1];
        $feestopay = getFeesAsFromTermAdmited($term,$conn2,$dach,$admno);
        $feespaidbystud = getFeespaidByStudent($admno,$conn2);

        // know if they paid this term
        $lastbal = lastBalance($admno,$conn2);
        // $lastacad = lastACADyrBal($admno,$conn2);

        // get balance
        $feestopay += $lastbal;
        $balance = $feestopay - $feespaidbystud;

        // if class is transfered or alumni last
        if ($dach == "-2" || $dach == "-1") {
            return $lastbal;
        }
        return $balance;
    }

    function lastACADyrBal($admno,$conn2){
        $student_data = students_details($admno,$conn2);
        $previous_term = null;
        $current_term = "TERM_1";
        $begin_term = getTermStart($conn2,$current_term);
        $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
        for($index = 0; $index < count($my_course_list); $index++){
            if($my_course_list[$index]->course_status == 1){
                $module_terms = $my_course_list[$index]->module_terms;
                for($in = 0; $in < count($module_terms); $in++){
                    if($module_terms[$in]->status == 1){
                        $current_term = $module_terms[$in]->term_name;
                        $begin_term = date("Y-m-d",strtotime($module_terms[$in]->start_date));
                        break;
                    }
                }
            }
        }

        // select statement
        $select = "SELECT `balance` FROM `finance` WHERE `stud_admin` = ? AND `date_of_transaction` <= ? ORDER BY `transaction_id` DESC LIMIT 1;";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$admno,$begin_term);
        $stmt->execute();
        $balance = 0;
        // echo $begin_term;
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['balance'])) {
                    $balance = $row['balance'];
                }
            }
        }
        // echo $begin_term;
        return $balance;
    }

    function lastBalance($admno,$conn2){
        // get the student term they are in
        $student_data = students_details($admno,$conn2);

        // decode the json format
        $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
        $start_time = date("Y-m-d");
        for($index = 0; $index < count($my_course_list); $index++){
            if($my_course_list[$index]->course_status == 1){
                // module terms
                $module_terms = $my_course_list[$index]->module_terms;
                for ($ind=0; $ind < count($module_terms); $ind++) {
                    if($module_terms[$ind]->status == 1){
                        $start_time = date("Y-m-d", strtotime($module_terms[$ind]->start_date));
                        break;
                    }
                }
            }
        }

        $select = "SELECT `balance` ,`date_of_transaction` FROM `finance` WHERE `stud_admin` = ?  AND date_of_transaction < ? ORDER BY `transaction_id` DESC LIMIT 1";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$admno, $start_time);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $last_paid = date("YmdHis",strtotime($row['date_of_transaction']));
                $beginyear = date("YmdHis",strtotime(getAcademicStart($conn2)));
                // KIBWEZI WEST
                // if ($beginyear < $last_paid) {
                //     return $row['balance'];
                // }
                return $row['balance'];
            }
        }
        return 0;
    }
    function getFeesAsPerTerm($term,$conn2,$classes){
        $select = '';
        $class = "%|".$classes."|%";
        if($term == "TERM_1"){
            $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' and not `roles` = 'boarding';";
        }elseif($term == "TERM_2"){
            $select = "SELECT sum(`TERM_1`)+sum(TERM_2) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' and not `roles` = 'boarding';";
        }elseif($term == "TERM_3"){
            $select = "SELECT sum(`TERM_1`)+sum(TERM_2)+sum(`TERM_3`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' and not `roles` = 'boarding';";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                return strlen($row['TOTALS'])>0 ? $row['TOTALS'] : 0;
            }else{
                return 0;
            }
        }
        return 0;
        $stmt->close();
    }
    function isProvisional($purpose,$conn2,$clas_s){
        $class = "%".$clas_s."%";
        $select = "SELECT `expenses` FROM `fees_structure` WHERE `expenses` = ? AND  `classes` LIKE ? AND `roles` = 'provisional';";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$purpose,$class);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums > 0) {
            return "true";
        }else {
            return "false";
        }
    }
    function getFeesAsOfTermBoarders($term,$conn2,$classes,$admno){
        $select = '';
        $class = "%|".$classes."|%";
        if($term == "TERM_1"){
            $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term == "TERM_2"){
            $select = "SELECT sum(`TERM_2`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term == "TERM_3"){
            $select = "SELECT sum(`TERM_3`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                $fees_to_pay = $row['TOTALS'];
                if (isBoarding($admno,$conn2)) {
                    $boarding_fees = getBoardingFeesOfTerm($conn2,$classes);
                    $fees_to_pay = $fees_to_pay+$boarding_fees;
                }
                // echo isBoarding($admno,$conn2);
                if (isTransport($conn2,$admno)) {
                    $transport = transportBalanceSinceAdmission($conn2,$admno,$term);
                    $fees_to_pay+=$transport;
                }
                if (strlen($fees_to_pay) < 1) {
                    return 0;
                }
                return $fees_to_pay;
            }else{
                return 0;
            }
        }
        return 0;
        $stmt->close();
    }
    function getFeesAsPerTermBoarders($term,$conn2,$classes,$admno){
        $select = '';
        $class = "%|".$classes."|%";
        if($term == "TERM_1"){
            $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term == "TERM_2"){
            $select = "SELECT sum(`TERM_1`)+sum(TERM_2) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term == "TERM_3"){
            $select = "SELECT sum(`TERM_1`)+sum(TERM_2)+sum(`TERM_3`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                $fees_to_pay = $row['TOTALS'];
                
                // echo $fees_to_pay;

                if (isBoarding($admno,$conn2)) {
                    $boarding_fees = getBoardingFees($conn2,$classes);
                    $fees_to_pay = $fees_to_pay+$boarding_fees;
                }
                // echo isBoarding($admno,$conn2);
                
                // get dicounts
                $discounts = getDiscount($admno,$conn2);
                if ($discounts[0] > 0 || $discounts[1] > 0) {
                    if ($discounts[0] > 0) {
                        $discounts = 100 - $discounts[0];
                        $fees_to_pay = round(($fees_to_pay * $discounts) / 100);
                    }else{
                        $fees_to_pay = $fees_to_pay - $discounts[1];
                    }
                }
                if (isTransport($conn2,$admno)) {
                    $transport = transportBalanceSinceAdmission($conn2,$admno);
                    $fees_to_pay+=$transport;
                }
                if (strlen($fees_to_pay) < 1) {
                    return 0;
                }
                return $fees_to_pay;
            }else{
                return 0;
            }
        }
        return 0;
        $stmt->close();
    }
    function getFeesAsFromTermAdmited($current_term,$conn2,$classes,$admno){
        // get the student term they are in
        $student_data = students_details($admno,$conn2);
        $term_they_are_in = null;

        // decode the json format
        $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
        for($index = 0; $index < count($my_course_list); $index++){
            if($my_course_list[$index]->course_status == 1){
                // module terms
                $module_terms = $my_course_list[$index]->module_terms;
                for ($ind=0; $ind < count($module_terms); $ind++) {
                    if($module_terms[$ind]->status == 1){
                        $term_they_are_in = $module_terms[$ind]->term_name;
                        break;
                    }
                }
            }
        }

        if ($term_they_are_in == null || $classes == "-2" || $classes == "-1") {
            return 0;
        }

        $class = "".$classes."";
        $course_enrolled = $student_data['course_done'];

        // get the term they are in
        if($term_they_are_in == "TERM_1"){
            $select = "SELECT sum(`TERM_1`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` = ? AND `course` = ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term_they_are_in == "TERM_2"){
            $select = "SELECT sum(`TERM_2`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` = ? AND `course` = ? AND `activated` = 1  and `roles` = 'regular';";
        }elseif($term_they_are_in == "TERM_3"){
            $select = "SELECT sum(`TERM_3`) AS 'TOTALS' FROM `fees_structure`  WHERE `classes` = ? AND `course` = ? AND `activated` = 1  and `roles` = 'regular';";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$class,$course_enrolled);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                $fees_to_pay = $row['TOTALS'];
                
                // get dicounts
                $discounts = getDiscount($admno,$conn2);
                if ($discounts[0] > 0 || $discounts[1] > 0) {
                    if ($discounts[0] > 0) {
                        $discounts = 100 - $discounts[0];
                        $fees_to_pay = round(($fees_to_pay * $discounts) / 100);
                    }else{;
                        $fees_to_pay = $fees_to_pay - $discounts[1];
                    }
                }

                if (strlen($fees_to_pay) < 1) {
                    return 0;
                }
                return $fees_to_pay;
            }else{
                return 0;
            }
        }
        return 0;
        $stmt->close();
    }

    function getFeesTerm($term,$conn2,$classes,$admno){
        $select = '';
        $class = "%|".$classes."|%";
        $select = "SELECT sum(`".$term."`) AS 'TOTALS' FROM `fees_structure` WHERE `classes` LIKE ? AND `activated` = 1  and `roles` = 'regular';";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if ($row = $res->fetch_assoc()) {
                $fees_to_pay = $row['TOTALS'];
                if (isBoarding($admno,$conn2)) {
                    $boarding_fees = getBoardingFees($conn2,$classes,$term);
                    $fees_to_pay = $fees_to_pay+$boarding_fees;
                }
                // echo isBoarding($admno,$conn2);
                if (isTransport($conn2,$admno)) {
                    $transport = transportBalanceSinceAdmission($conn2,$admno,$term);
                    $fees_to_pay+=$transport;
                }
                if (strlen($fees_to_pay) < 1) {
                    return 0;
                }
                return $fees_to_pay;
            }else{
                return 0;
            }
        }
        return 0;
        $stmt->close();
    }
    function checkFeesChange($term,$conn2,$classes,$last_paid_time){
        $select = '';
        $class = "%|".$classes."|%";
        if($term == "TERM_1"){
            $select = "SELECT `expenses`, (SUM(`TERM_1`)) - (SUM(`term_1_old`)) AS 'Increase' FROM `fees_structure`WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' AND `date_changed` > ?  GROUP BY `expenses`;";
        }elseif($term == "TERM_2"){
            $select = "SELECT `expenses`, (SUM(`TERM_1`)+SUM(`TERM_2`)) - (SUM(`term_1_old`)+SUM(`term_2_old`)) AS 'Increase' FROM `fees_structure`WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' AND `date_changed` > ?  GROUP BY `expenses`;";
        }elseif($term == "TERM_3"){
            $select = "SELECT `expenses`, (SUM(`TERM_1`)+SUM(`TERM_2`)+SUM(`TERM_3`)) - (SUM(`term_1_old`)+SUM(`term_2_old`)+SUM(`term_3_old`)) AS 'Increase' FROM `fees_structure`WHERE `classes` LIKE ? AND `activated` = 1  and not `roles` = 'provisional' AND `date_changed` > ?  GROUP BY `expenses`;";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$class,$last_paid_time);
        $stmt->execute();
        $result = $stmt->get_result();
        $data_to_display = "";
        if ($result) {
            $data_to_display.= "<ol  type='1'>";
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $data_to_display .= "<li>".$row['expenses']." : ".$row['Increase']."</li>";
                $total += ($row['Increase']*1);
            }
            $data_to_display .= "<strong>Total : <span id='increased_fees'>".$total."</span><br></strong>";
            $data_to_display.= "</ol>You are advised to add the total you are shown above to the student`s current fees balance below or Ignore if already changed.||";
            if ($total == 0) {
                $data_to_display = "";
                return $data_to_display;
            }
        }
        return $data_to_display;
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
    function getFeespaidByStudent($admno,$conn2){
        // get the student details
        $student_data = students_details($admno,$conn2);
        
        // get the current term so that we start counting from there
        $my_course_list = isJson($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
        $start_time = date("Y-m-d");
        for ($index=0; $index < count($my_course_list); $index++) { 
            if($my_course_list[$index]->course_status == 1){
                $module_terms = $my_course_list[$index]->module_terms;
                for($ind = 0; $ind < count($module_terms); $ind++){
                    if($module_terms[$ind]->status == 1){
                        $start_time = date("Y-m-d", strtotime($module_terms[$ind]->start_date));
                        break;
                    }
                }
            }
        }
        
        $select = "SELECT * FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND `payment_for` != 'admission fees'";
        $stmt = $conn2->prepare($select);
        
        // echo $begin_term;
        $currentdate = date("Y-m-d");
        $stmt->bind_param("sss",$admno,$start_time,$currentdate);
        $stmt->execute();
        $res = $stmt->get_result();
        $last_acad_balance  = lastACADyrBal($admno,$conn2);
        if($res){
            $total_amounts = 0;
            while($row = $res->fetch_assoc()){
                $payment_for = strtolower($row['payment_for']);
                // $prov_amount = provisionalPays($admno,$conn2,$prov_roles,$beginyear);
                $provisonal_pays = getProvisionalPayments($admno,$conn2);
                if (!isPresent($provisonal_pays,$payment_for)) {
                    $total_amounts += ($row['amount']*1);
                }
            }
            if ($last_acad_balance < 0) {
                // $total_amounts -= $last_acad_balance;
            }
            return $total_amounts;
        }
        return 0;
    }
    function getFeespaidByStudent_with_prov($admno,$conn2){
        $select = "SELECT * FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND `payment_for` != 'admission fees'";
        $stmt = $conn2->prepare($select);
        $beginyear = getAcademicStart($conn2);//start date of the academic year
        $currentdate = date("Y-m-d");
        $stmt->bind_param("sss",$admno,$beginyear,$currentdate);
        $stmt->execute();
        $res = $stmt->get_result();
        echo $admno."tRIAL AND ERRO";
        if($res){
            $total_amounts = 0;
            while($row = $res->fetch_assoc()){
                // $payment_for = strtolower($row['payment_for']);
                // $prov_amount = provisionalPays($admno,$conn2,$prov_roles,$beginyear);
                // $provisonal_pays = getProvisionalPayments($admno,$conn2);
                $total_amounts += ($row['amount']*1);
            }
            return $total_amounts;
        }
        return 0;
    }
    function total_fees_paid($admno,$conn2){
        $select = "SELECT sum(amount) AS 'TOTAL' FROM `finance` where `stud_admin` = ?";
        $stmt = $conn2->prepare($select);
        $beginyear = getAcademicStart($conn2);//start date of the academic year
        $currentdate = date("Y-m-d");
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res){
            if($row = $res->fetch_assoc()){
                if (isset($row['TOTAL'])) {
                    $total_pay = $row['TOTAL'];
                    return $total_pay;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else {
            return 0;
        }
        return 0;
    }
    function getFeespaidByStudentAdm($admno){
        include("../../connections/conn2.php");
        $select = "SELECT sum(amount) AS 'TOTAL' FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND `payment_for` != 'admission fees'";
        $stmt = $conn2->prepare($select);
        $beginyear = getAcademicStart($conn2);//start date of the academic year
        $currentdate = date("Y-m-d");
        $stmt->bind_param("sss",$admno,$beginyear,$currentdate);
        $stmt->execute();
        $res = $stmt->get_result();
        // $conn2->close();
        if($res){
            if($row = $res->fetch_assoc()){
                if (isset($row['TOTAL'])) {
                    $total_pay = $row['TOTAL'];
                    $class = explode("^",getClassV2reports($admno,$conn2))[1];
                    $prov_roles = getProvisionalRole($class,$conn2);
                    $prov_amount = provisionalPays($admno,$conn2,$prov_roles,$beginyear);
                    $total_pay = $total_pay-$prov_amount;
                    return $total_pay;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else {
            return 0;
        }
        return 0;
    }
    /**
     * The following function returns the total amount paid for the provisional payments
     */
    function provisionalPays($admno,$conn2,$prov_pays,$beginyear){
        $provisional_amount = 0;
        if (count($prov_pays) > 0) {
            for ($i=0; $i < count($prov_pays); $i++) {
                $select = "SELECT sum(amount) AS 'TOTAL' FROM `finance` where `stud_admin` = ?  AND `date_of_transaction` BETWEEN ? and ? AND  `payment_for` = ?;";
                $stmt = $conn2->prepare($select);
                $today = date("Y-m-d");
                $stmt->bind_param("ssss",$admno,$beginyear,$today,$prov_pays[$i]);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $provisional_amount = ($row['TOTAL']*1);
                    }
                }
            }
        }
        return $provisional_amount;
    }
    // get provisional payments
    function getProvisionalPayments($adm_no,$conn2){
        // get the student class
        $select = "SELECT * FROM `student_data` WHERE `adm_no` = '".$adm_no."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $class_student = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $class_student = $row['stud_class'];
            }
        }
        // echo $class_student."<br>";

        // get all the provisional payments for that class
        $select = "SELECT * FROM `fees_structure` WHERE `roles` = 'provisional' AND `classes` LIKE '%|".$class_student."|%';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $roles = [];
        $result = $stmt->get_result();
        if($result){
            while($row = $result->fetch_assoc()){
                array_push($roles,strtolower($row['expenses']));
                // echo strtolower($row['expenses'])." <br>";
            }
        }
        return $roles;
    }
    /**
     * @return Arrays of all provisional payments
     */
    function getProvisionalRole($stud_class,$conn2){
        $class = "%|".$stud_class."|%";
        $select = "SELECT `expenses` FROM `fees_structure` WHERE `roles` = 'provisional' AND  `classes` LIKE ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $result = $stmt->get_result();
        $roles = "";
        $roles_arr = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $roles.=$row['expenses'].",";
            }
        }
        if (strlen($roles) > 0) {
            $roles = substr($roles,0,(strlen($roles)-1));
            $roles_arr = explode(",",$roles);
        }
        return $roles_arr;
    }
    function getMonthlySaloBreak($salo_break,$month_yr){
        if (count($salo_break) > 0) {
            for ($indexq=0; $indexq < count($salo_break); $indexq++) { 
                // if(isset($salo_break[$indexq]->$month_yr)){
                //     return $salo_break[$indexq]->$month_yr;
                // }
                foreach ($salo_break[$indexq] as $key => $value) {
                    if ($key == $month_yr) {
                        return $value;
                    }
                }
            }
        }else{
            return [];
        }
    }
    function getAdvacesDeductions($staff_id,$conn2,$time){
        $select = "SELECT * FROM `advance_pay` WHERE `employees_id` = '".$staff_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $specific_month = date("M:Y",strtotime($time));
        $deduction_breakdown = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (isJson($row['payment_breakdown'])) {
                    $payment_breakdown = json_decode($row['payment_breakdown']);
                    // get the payments breadown for the months that have been defined
                    for ($index=0; $index < count($payment_breakdown); $index++) {
                        if ($payment_breakdown[$index]->payment_for == $specific_month) {
                            $advances_array = array("Advance_Pay_".$specific_month."" => $payment_breakdown[$index]->amount_paid);
                            array_push($deduction_breakdown,$advances_array);
                        }
                    }
                }
            }
        }
        return $deduction_breakdown;
    }
    function getAllowanceBonusRelief($staff_id,$conn2,$start_dates){
        $payroll_data = getMySalaryBreakdown($staff_id,$conn2,$start_dates);
        // check payroll data if its json format
        // bonuses and allowances || deductions
        // echo json_encode($payroll_data);
        $allowance_bonus = [];
        $deductions = [];
        $reliefs = [];
        if ($payroll_data != null) {
            // $payroll_data = json_decode($payroll_data);
            // gross salary
            $gross_salary = $payroll_data->gross_salary;
            // get allowances
            $sum_allowances = 0;
            $allowances = $payroll_data->allowances;
            if(is_array($allowances)){
                for ($i=0; $i < count($allowances); $i++) { 
                    $sum_allowances += $allowances[$i]->value;
                    $all_data = (($allowances[$i]->name))." - Kes ".comma($allowances[$i]->value);
                    $allowance = array($allowances[$i]->name => $allowances[$i]->value);
                    array_push($allowance_bonus,$allowance);
                    // echo $all_data;
                }
            }
            // personal realief
            $personal_relief = $payroll_data->personal_relief;
            if ($personal_relief == "yes") {
                $relief = array("personal_relief" => 2400);
                array_push($reliefs,$relief);
            }
            // nhif reliefs
            $nhif_relief = $payroll_data->nhif_relief;
            if ($nhif_relief == "yes") {
                // get the nhif contribution
                $nhif_contribution = getNHIFContribution($gross_salary);
                $nhif_contribution = $nhif_contribution*0.15;
                $nhif_relief = $nhif_contribution>255 ? 255 : $nhif_contribution;
                $nhif_relief = array("nhif_relief" => $nhif_relief);
                array_push($reliefs,$nhif_relief);
            }
            // nssf deductions 
            $nssf_data = $payroll_data->nssf_rates;
            $nssf_amount = ($nssf_data != "teir_1" && $nssf_data != "teir_1_2") ? 200: ($nssf_data == "teir_1_2" ? 1080:360);

            // deductions
            $payes = $payroll_data->deduct_paye;
            if ($payes == "yes") {
                $taxable_income = ($gross_salary+$sum_allowances) - $nssf_amount;
                $paye = round(getPaye($taxable_income,$payroll_data->year));
                $paye = array("P.A.Y.E" => $paye);
                array_push($deductions,$paye);
            }
            // get other deductions
            $other_deductions = isset($payroll_data->deductions) ? $payroll_data->deductions : "";
            if (is_array($other_deductions)) {
                for ($indexing=0; $indexing < count($other_deductions); $indexing++) {
                    $deduction = array($other_deductions[$indexing]->name => $other_deductions[$indexing]->value);
                    array_push($deductions,$deduction);
                }
            }
            return [$allowance_bonus,$reliefs,$deductions];
        }
        return [[],[],[]];
    }
    // get payes
    function getPaye($taxable_income,$year){
        // console.log(taxable_income);
        if ($year == "2022" || $year == "2023") {
            if ($taxable_income > 24000) {
                $tax = 0;
                // calculate the income $tax
                if ($taxable_income >= 12298) {
                    $first_ten = 12298 * 0.1; //10%
                    $tax += $first_ten;
                    if ($taxable_income >= 23885) {
                        $second = (23885 - 12298) * 0.15;//15%
                        $tax += $second;
                        if ($taxable_income >= 35472) {
                            $third = (35472 - 23885) * 0.2;//20%
                            $tax += $third;
                            if ($taxable_income >= 47059) {
                                $fourth = (47059 - 35472) * 0.25;//25%
                                $tax += $fourth;
                                if ($taxable_income > 47059) {
                                    $fifth = ($taxable_income - 47059) * 0.3;
                                    $tax += $fifth;
                                }
                            } else {
                                $fourth = ($taxable_income - 35472) * 0.20;//20%
                                $tax += $fourth;
                            }
                        } else {
                            $third = ($taxable_income - 23885) * 0.20;//20%
                            $tax += $third;
                        }
                    } else {
                        $second = ($taxable_income - 12299) * 0.15;//15%
                        $tax += $second;
                    }
                } else {
                    $tax += $taxable_income * 0.1;
                }
                return $tax;
            } else { return 0; }
        } else if ($year == "2021") {
            $tax = 0;
            if ($taxable_income >= 24000) {
                $tax += (24000 * 0.1);
                if ($taxable_income >= 32333) {
                    $tax += (8333 * 0.25);
                    if ($taxable_income > 32333) {
                        $tax += ($taxable_income - 32333) * 0.3;
                    }
                } else {
                    $tax += ($taxable_income - 24000) * 0.25;
                }
            }
            return $tax;
        }
    }
    function getNHIFContribution($gross_salary) {
        if ($gross_salary > 0 && $gross_salary <= 5999) {
            return 150;
        } elseif ($gross_salary > 5999 && $gross_salary <= 7999) {
            return 300;
        } elseif ($gross_salary > 7999 && $gross_salary <= 11999) {
            return 400;
        } elseif ($gross_salary > 11999 && $gross_salary <= 14999) {
            return 500;
        } elseif ($gross_salary > 14999 && $gross_salary <= 19999) {
            return 600;
        } elseif ($gross_salary > 19999 && $gross_salary <= 24999) {
            return 750;
        } elseif ($gross_salary > 24999 && $gross_salary <= 29999) {
            return 850;
        } elseif ($gross_salary > 29999 && $gross_salary <= 34999) {
            return 900;
        } elseif ($gross_salary > 34999 && $gross_salary <= 39999) {
            return 950;
        } elseif ($gross_salary > 39999 && $gross_salary <= 44999) {
            return 1000;
        } elseif ($gross_salary > 44999 && $gross_salary <= 49999) {
            return 1100;
        } elseif ($gross_salary > 49999 && $gross_salary <= 59999) {
            return 1200;
        } elseif ($gross_salary > 59999 && $gross_salary <= 69999) {
            return 1300;
        } elseif ($gross_salary > 69999 && $gross_salary <= 79999) {
            return 1400;
        } elseif ($gross_salary > 79999 && $gross_salary <= 89999) {
            return 1500;
        } elseif ($gross_salary > 89999 && $gross_salary <= 99999) {
            return 1600;
        } elseif ($gross_salary > 99999) {
            return 1700;
        } else {
            return 0;
        }
    }
    function isJson($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    function getSalaryDetails($conn2,$staff_id){
        $total_salo = getTotalSalo($conn2,$staff_id);
        if ($total_salo > 0) {
            // get last time he was paid
            $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_balance = 0;
            $current_balance_monNyear = 0;
            $first_time_paid = date("Y-m-d");
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $current_balance = ($row['current_balance']*1);
                    $current_balance_monNyear = $row['current_balance_monNyear'];
                    $first_time_paid = date("Y-m-d",strtotime("01-".(explode(":",explode(",",$row['effect_month'])[0])[0])."-".explode(":",explode(",",$row['effect_month'])[0])[1]));
                }
            }
            // echo $current_balance."<br>";
            // echo $current_balance_monNyear."<br>";
            $last_time_paid = explode(":",$current_balance_monNyear);
            $last_time_paid =  date("Y-m-d",strtotime("01-".$last_time_paid[0]."-".$last_time_paid[1]));
            
            // get all the previous salary payment that have been done
            $select = "SELECT * FROM `salary_payment` WHERE `staff_paid` = '".$staff_id."' ORDER BY `pay_id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $previous_payments = [];
            $total_salary_paid = 0;
            if ($result) {
                while($row = $result->fetch_assoc()){
                    array_push($previous_payments,$row);
                    $total_salary_paid += $row['amount_paid'];
                }
            }
            // echo json_encode($previous_payments);
            // a loop from the last time he was paid to when he was first paid
            // echo $first_time_paid." ".$last_time_paid;
            // go through the months since he was last paid to the time he was first paid
            $monthly_dates = $last_time_paid;
            // echo $monthly_dates;
            $carry_forward = 0;
            $payment_history_index = 0;
            $used_salos = 0;
            $FINANCE_DATA = [];
            while(true){
                if ($monthly_dates < $first_time_paid) {
                    break;
                }
                

                // if the date is equal to the last time piad get the salary of the month and
                // deduct the balance to get the monthly last payment
                $emp_salary = 0;
                if ($monthly_dates == $last_time_paid) {
                    $emp_salary = getMySalary($staff_id,$conn2,$monthly_dates);
                    $emp_salary = $emp_salary - $current_balance;
                    $used_salos+=$emp_salary;
                }elseif($monthly_dates == $first_time_paid){
                    $emp_salary = $total_salary_paid - $used_salos;
                    // $emp_salary = $first_salary;
                    // echo $emp_salary.$monthly_dates."";
                }else{
                    $emp_salary = getMySalary($staff_id,$conn2,$monthly_dates);
                    $used_salos+=$emp_salary;
                }

                // echo $emp_salary." ".date("D dS M Y",strtotime($monthly_dates))."<br>";
                $salary_payers = "";
                $salo_details = [];
                // lets get each months break down
                while($emp_salary > 0){
                    if ($carry_forward > 0) {
                        $carry_forward*=1;
                        $pr_pay = $previous_payments[$payment_history_index]['amount_paid'];
                        if ($carry_forward >= $emp_salary) {
                            $carry_forward-=$emp_salary;
                            $salary_payers.="Amount Paid is ".$emp_salary." ".$previous_payments[$payment_history_index]['mode_of_payment']." in ".$previous_payments[$payment_history_index]['date_paid']." prpay ".$pr_pay."<br>";
                            $data = array("amount_paid" => $emp_salary,"date_paid" => $previous_payments[$payment_history_index]['date_paid'],"time_paid" => $previous_payments[$payment_history_index]['time_paid'],"mode_of_payment" => $previous_payments[$payment_history_index]['mode_of_payment']);
                            $emp_salary = 0;
                            array_push($salo_details,$data);
                        }elseif($carry_forward < $emp_salary){
                            $emp_salary -= $carry_forward;
                            $salary_payers.="Amount Paid is ".$carry_forward." ".$previous_payments[$payment_history_index]['mode_of_payment']." in ".$previous_payments[$payment_history_index]['date_paid']." prpay ".$pr_pay."<br>";
                            $data = array("amount_paid" => $carry_forward,"date_paid" => $previous_payments[$payment_history_index]['date_paid'],"time_paid" => $previous_payments[$payment_history_index]['time_paid'],"mode_of_payment" => $previous_payments[$payment_history_index]['mode_of_payment']);
                            array_push($salo_details,$data);
                            // continue;
                            $carry_forward = 0;
                            $payment_history_index++;
                            $pr_pay = $previous_payments[$payment_history_index]['amount_paid'];
                            // echo $pr_pay." here <br>";
                        }
                    }
                    if ($emp_salary > 0) {
                        $pr_pay = $previous_payments[$payment_history_index]['amount_paid'];
                        if ($emp_salary >= $pr_pay) {
                            $pr_pay = $previous_payments[$payment_history_index]['amount_paid'];
                            $emp_salary-=$pr_pay;
                            // echo json_encode($previous_payments);
                            // echo $pr_pay."<br>";
                            $salary_payers.="Amount Paid is ".$pr_pay." ".$previous_payments[$payment_history_index]['mode_of_payment']." in ".$previous_payments[$payment_history_index]['date_paid']." prpay ".$pr_pay."<br>";
                            // echo json_encode($previous_payments);
                            $data = array("amount_paid" => $pr_pay,"date_paid" => $previous_payments[$payment_history_index]['date_paid'],"time_paid" => $previous_payments[$payment_history_index]['time_paid'],"mode_of_payment" => $previous_payments[$payment_history_index]['mode_of_payment']);
                            array_push($salo_details,$data);
                            $payment_history_index++;
                        }elseif ($emp_salary < $pr_pay) {
                            $carry_forward = $pr_pay - $emp_salary;
                            $salary_payers.="Amount Paid is ".$emp_salary." ".$previous_payments[$payment_history_index]['mode_of_payment']." in ".$previous_payments[$payment_history_index]['date_paid']." prpay ".$pr_pay."<br>";
                            $data = array("amount_paid" => $emp_salary,"date_paid" => $previous_payments[$payment_history_index]['date_paid'],"time_paid" => $previous_payments[$payment_history_index]['time_paid'],"mode_of_payment" => $previous_payments[$payment_history_index]['mode_of_payment']);
                            array_push($salo_details,$data);
                            $emp_salary=0;
                        }
                    }
                    // echo $salary_payers;
                }
                $salary_arr = array(date("M-Y",strtotime($monthly_dates)) => $salo_details);
                array_push($FINANCE_DATA,$salary_arr);


                // echo $emp_salary." ".date("D dS M Y",strtotime($monthly_dates))."<br>";
                // add a month from the current datae
                $deets=date_create($monthly_dates);
                date_sub($deets,date_interval_create_from_date_string("1 Month"));
                $monthly_dates = date_format($deets,"Y-m-d");
            }
            // echo json_encode($FINANCE_DATA);
            return $FINANCE_DATA;
            
        }
        return [];
    }

    function getMySalary($staff_id,$conn2,$date){
        $first_salary = getFirstPaymentAmount($conn2,$staff_id);
        // get last time he was paid
        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $first_time_paid = date("Y-m-d",strtotime("01-".(explode(":",explode(",",$row['effect_month'])[0])[0])."-".explode(":",explode(",",$row['effect_month'])[0])[1]));
            }
        }
        if ($first_time_paid == $date) {
            return $first_salary;
        }
        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $effect_month = $row['effect_month'];
                $salary_amount = $row['salary_amount'];
                $effect_month = $row['effect_month'];
                
                // all salaries
                $all_salaries = explode(",",$effect_month);
                $salary_amount = explode(",",$salary_amount);
                // first recorded date

                // loop until today and when we reach the month he was paid 
                $salo_amount = 0;
                for ($index=0; $index < count($all_salaries); $index++) {
                    $salary_month = explode(":",$all_salaries[$index]);
                    $salo_date = date("Y-m-d",strtotime("01-".$salary_month[0]."-".$salary_month[1]));
                    // echo $date ." salo date-> ". $salo_date."<br>";
                    if ($date >= $salo_date) {
                        $salo_amount = $salary_amount[$index];
                    }
                }
                return $salo_amount;
            }
        }
        return 0;
    }
    function getMySalaryBreakdown($staff_id,$conn2,$date){
        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '".$staff_id."';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $salary_breakdown_index = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $effect_month = $row['effect_month'];
                $salary_amount = $row['salary_amount'];
                
                // all salaries
                $all_salaries = explode(",",$effect_month);
                $salary_amount = explode(",",$salary_amount);
                // first recorded date

                // loop until today and when we reach the month he was paid 
                $salo_amount = 0;
                for ($index=0; $index < count($all_salaries); $index++) {
                    $salary_month = explode(":",$all_salaries[$index]);
                    $salo_date = date("Y-m-d",strtotime($salary_month[1]."-".$salary_month[0]."-01"));
                    if ($date >= $salo_date) {
                        if ($index+1 == count($all_salaries)) {
                            $salo_amount = $salary_amount[$index];
                            $salary_breakdown_index = $index;
                            // echo $date ." salo date-> ". $salo_date." == Kes ".$salo_amount."<br>";
                        }else{
                            $salary_month = explode(":",$all_salaries[$index+1]);
                            $salo_date = date("Y-m-d",strtotime($salary_month[1]."-".$salary_month[0]."-01"));
                            if ($date <= $salo_date){
                                $salo_amount = $salary_amount[$index];
                                $salary_breakdown_index = $index;
                                // echo $date ." salo date-> ". $salo_date." == Kes ".$salo_amount."<br>";
                            }
                        }
                    }
                }
                // get the salary breakdown
                $salary_breakdown = $row['salary_breakdown'];

                // check if it has json structure
                if (isJson($salary_breakdown)) {
                    $new_salo_breakdown = json_decode($salary_breakdown);
                    if (is_array($new_salo_breakdown)) {
                        // check if the salary size is the same as the salary breaks size
                        if (count($all_salaries) == count($new_salo_breakdown)) {
                            return $new_salo_breakdown[$salary_breakdown_index];
                        }else{
                            // give the last salary index
                            return $new_salo_breakdown[count($new_salo_breakdown) - 1];
                        }
                    }else {
                        return $new_salo_breakdown;
                    }
                }
            }
        }
        return null;
    }
    function getAcademicStart($conn2){
        $select = "SELECT `start_time` FROM `academic_calendar` WHERE `term` = 'TERM_1';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['start_time'];
            }
        }
        return date('Y')."-01-01";
    }
    function getTermStart($conn2,$term){
        $select = "SELECT `start_time` FROM `academic_calendar` WHERE `term` = '".$term."';";
        $stmt =$conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['start_time'];
            }
        }
        return date('Y')."-01-01";
    }
    function isBoarding($admno,$conn2){
        $select = "SELECT * FROM `boarding_list` WHERE `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums > 0) {
            return true;
        }
        return false;
    }
    function getBoardingFeesOfTerm($conn2,$class,$termed = "null"){
        $class = "%|".$class."|%";
        $term = getTermV2($conn2);
        // echo $class;
        $select = "";
        if ($term == "TERM_1" && $termed == "null") {
            $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        }elseif ($term == "TERM_2" && $termed == "null") {
            $select = "SELECT sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        }elseif ($term == "TERM_3" && $termed == "null") {
            $select = "SELECT sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        }elseif ($termed != "null") {
            $select = "SELECT sum(`".$termed."`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Total'];
            }
        }
        return 0;
    }
    function getBoardingFeesFromTermAdmitted($conn2,$class,$admitted_term = "null"){
        $class = "%|".$class."|%";
        $term = getTermV2($conn2);
        // echo $class;
        if ($admitted_term == "TERM_1" || $admitted_term == "null") {
            if($term == "TERM_1"){
                $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_2"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }elseif($admitted_term == "TERM_2"){
            if($term == "TERM_2"){
                $select = "SELECT sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }elseif($admitted_term == "TERM_3"){
            if($term == "TERM_3"){
                $select = "SELECT sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }else {
            if($term == "TERM_1"){
                $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_2"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }

        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Total'];
            }
        }
        return 0;
    }
    function getBoardingFees($conn2,$class,$admitted_term = "null",$admission_no = ""){
        $class = "%|".$class."|%";
        $term = getTermV2($conn2);
        if (strlen($admission_no) > 0) {
            $student_data = students_details($admission_no,$conn2);
            // get the date of registration is in what term
            $date_of_reg = count($student_data) > 0 ? $student_data['D_O_A'] : date("Y-m-d");
            $select = "SELECT * FROM `academic_calendar` WHERE `start_time` <= ? AND `end_time` >= ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$date_of_reg,$date_of_reg);
            $stmt->execute();
            $result = $stmt->get_result();
            $admitted_term = "null";
            if ($result) {
                if($row = $result->fetch_assoc()){
                    $admitted_term = $row['term'];
                }
            }
        }
        // echo $class;
        // $select = "";
        // if ($term == "TERM_1" && $termed == "null") {
        //     $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        // }elseif ($term == "TERM_2" && $termed == "null") {
        //     $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        // }elseif ($term == "TERM_3" && $termed == "null") {
        //     $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        // }elseif ($termed != "null") {
        //     $select = "SELECT sum(`".$termed."`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
        // }
        // echo $class;
        if ($admitted_term == "TERM_1" || $admitted_term == "null") {
            if($term == "TERM_1"){
                $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_2"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }elseif($admitted_term == "TERM_2"){
            if($term == "TERM_2"){
                $select = "SELECT sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }elseif($admitted_term == "TERM_3"){
            if($term == "TERM_3"){
                $select = "SELECT sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }else {
            if($term == "TERM_1"){
                $select = "SELECT sum(`TERM_1`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_2"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }elseif($term == "TERM_3"){
                $select = "SELECT sum(`TERM_1`)+sum(`TERM_2`)+sum(`TERM_3`) AS 'Total' FROM `fees_structure` WHERE `roles` = 'boarding' AND `activated` = 1 AND `classes` like ?";
            }
        }
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$class);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['Total'];
            }
        }
        return 0;
    }
    function getName1($admno){
        include("../../connections/conn2.php");
        $select = "SELECT concat(`first_name`,' ',`second_name`) AS `Names` FROM `student_data` where `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $results = $stmt->get_result();
        if($results){
            $xs =0;
            $name = '';
            while ($row=$results->fetch_assoc()) {
                $xs++;
                $name = $row['Names']."";
                break;
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
        $conn2->close();
    }
    
    function insertNotifcation($conn2,$messageName,$messagecontent,$notice_stat,$reciever_id,$reciever_auth,$sender_id){
        $insert = "INSERT INTO `tblnotification`  (`notification_name`,`Notification_content`,`sender_id`,`notification_status`,`notification_reciever_id`,`notification_reciever_auth`) VALUES (?,?,?,?,?,?)";
        $stmt = $conn2->prepare($insert);
        $stmt->bind_param("ssssss",$messageName,$messagecontent,$sender_id,$notice_stat,$reciever_id,$reciever_auth);
        $stmt->execute();
    }
    
    function getApiKey($conn){
        $select = "SELECT `sms_api_key` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['sms_api_key'];
            }
        }
        return 0;
    }
    function getPatnerId($conn){
        $select = "SELECT `patner_id` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['patner_id'];
            }
        }
        return 0;
    }
    function getShortCode($conn){
        $select = "SELECT `short_code` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['short_code'];
            }
        }
        return 0;
    }
    function getUrl($conn){
        $select = "SELECT `send_sms_url` FROM `sms_api`";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['send_sms_url'];
            }
        }
        return 0;
    }
    function getPhoneNumber($conn2,$stud_id){
        $select = "SELECT `parentContacts`,`parent_contact2` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$stud_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['parentContacts'].",".$row['parent_contact2'];
            }
        }
        return 0;
    }
    function changeTransport($conn2,$admno){
        // first check the students date of last payment and check if transport amount has been changed
        $last_paid_time = getLastTimePaying($conn2,$admno);
        // check the date the route was changed or added if it is greater than the date the student last paid
        $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$admno);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_id = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $route_id = $row['route_id'];
            }
        }
        // get the route price change
        $select = "SELECT * FROM `van_routes` WHERE `route_id` = ? AND `route_date_change` >= ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$route_id,$last_paid_time);
        $stmt->execute();
        $result = $stmt->get_result();
        $route_price = "0";
        $old_price = "0";
        $data_to_display = "";
        if($result){
            if ($row = $result->fetch_assoc()) {
                $route_price = ($row['route_price']*1);
                $old_price = ($row['route_prev_price']*1);
                $data_to_display = "<hr><span class='text-primary'>Route fees seemed to have been changed. It has changed by <b>Ksh ".($route_price-$old_price)."</b>. Please change accordingly</span>.";
            }
        }
        // there will be change in price if there is no result
        return $data_to_display;
    }
    function getFirstPaymentAmount($conn2,$staff_id){
        $total_paid = getTotalSalo($conn2,$staff_id);
        // get the last month balance
        $firstpay_record = getFirstPayDate($conn2,$staff_id);
        $times = explode(":",$firstpay_record);
        $firstpay_dated = date("Y-m-d",strtotime("01-".$times[0]."-".$times[1]));
        // get the current balnce, amount and month
        $curr_balance = getCurrentBalTime($conn2,$staff_id);
        $times = explode(":",explode(",",$curr_balance)[0]);
        $last_date_paid = date("Y-m-d",strtotime("01-".$times[0]."-".$times[1]));
        // echo $last_date_paid;
        // get the first pay as a date
        // loop through the dates untill the last paydate to get 
        // the total amount paid that period and the first payment amount
        $total_salary = 0;
        $overrall_salo = 0;
        $date = addMonthsFinance(1,$firstpay_dated);
        // echo $date;
        // if the last paid date is the same as tthe date with the balance the first amount 
        // paid is the balance plus the amount paid
        if ($firstpay_dated == $last_date_paid) {
            $balance = explode(",",$curr_balance);
            return $balance[1] + $total_paid;
        }
        if($date < $last_date_paid){
            for(;;){
                if ($last_date_paid == $date) {
                    break;
                }
                $total_salary+=getSalary($date,$conn2,$staff_id);
                $date = addMonthsFinance(1,$date);
            }
        }
        $overrall_salo = 0;
        $overall_date = addMonthsFinance(1,$firstpay_dated);
        for(;;){
            if ($last_date_paid < $overall_date) {
                break;
            }
            $overrall_salo+=getSalary($overall_date,$conn2,$staff_id);
            $overall_date = addMonthsFinance(1,$overall_date);
        }
        $last_time_salo = getSalary($last_date_paid,$conn2,$staff_id) - explode(",",$curr_balance)[1];
        $total_salary+=$last_time_salo;
        return $total_paid - $total_salary;

        // return 0;
    }
    function getPaymentBreakdown($conn2,$staff_id,$first_pay_amount,$firstpay_dated){
        $select = "SELECT * FROM `salary_payment` WHERE `staff_paid` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $data2 = [];
                array_push($data2,$row['amount_paid']);
                array_push($data2,$row['mode_of_payment']);
                array_push($data2,$row['payment_code']);
                array_push($data2,$row['date_paid']);
                array_push($data2,$row['time_paid']);
                // final array
                array_push($data,$data2);
            }
        }

        $months_n_salary = [];
        $breakdown = [];
        $salary_rem = 0;
        $amount_rem = 0;
        // break down the payment from the first payment to the last
        $date_explode = explode(":",$firstpay_dated);
        $fdate = date("Y-m-d",strtotime("01-".$date_explode[0]."-".$date_explode[1]));
        for ($index=0; $index < count($data); $index++) { 
            $stringdata = "";
            // first store the salary amount
            $amount_paid = $data[$index][0];
            if ($amount_rem > 0) {
                $amount_paid+=$amount_rem;
            }
            // echo $amount_paid." index ".$index." ".$salary_rem."<br>";
            if ($salary_rem < 1) {
                $salary = getSalary($fdate,$conn2,$staff_id,$first_pay_amount);
                if ($index == 0) {
                    $salary = $first_pay_amount;
                }
            }else{
                $salary = $salary_rem;
            }
            // echo $amount_paid." ".$salary."<br>";
            // start dividing the salary
            if ($salary > $amount_paid) {
                // echo $salary." above ".$amount_paid."<br>";
                // break;
                $salary -= $amount_paid;
                $stringdata = "<p>-Kes ".comma($amount_paid)." ".payMode($data[$index][1])."  (".date("d-M-Y",strtotime($data[$index][3])).") </p>";
                array_push($breakdown,$stringdata);
                $stringdata = "";
                $salary_rem = $salary;
                $amount_paid = 0;
                if (count($data) == $index+1) {
                    $m_date = date("M - Y",strtotime($fdate));
                    $months_n_salary += [$m_date => $breakdown];
                }
                // check if there is any salary other payment value so that you may break or continue
                continue;
            }else {
                // echo $salary;break;
                while($amount_paid >= $salary){
                    // if there is a salary remaining show the amount that remained has been paid of
                    if ($amount_rem > 0) {
                        // $salary-=$amount_rem;
                        $stringdata .= "<p>-Kes ".comma($amount_rem)." ".payMode($data[$index-1][1])."  (".date("d-M-Y",strtotime($data[$index-1][3])).") </p>";
                        // array_push($breakdown,$stringdata);
                    }
                    $amount_paid -= $salary;
                    if ($amount_rem > 0) {
                        $salary-=$amount_rem;
                        $amount_rem = 0;
                    }
                    $stringdata .= "<p>-Kes ".comma($salary)." ".payMode($data[$index][1])."  (".date("d-M-Y",strtotime($data[$index][3])).") </p>";
                    array_push($breakdown,$stringdata);
                    $stringdata = "";
                    $m_date = date("M - Y",strtotime($fdate));
                    $months_n_salary += [$m_date => $breakdown];
                    $breakdown = [];
                    $fdate = addMonthsFinance(1,$fdate);
                    if ($salary_rem > 0) {
                        $salary_rem = 0;
                        $fdate = date("Y-m-d",strtotime($fdate));
                    }
                    $salary = getSalary($fdate,$conn2,$staff_id);
                }
                if ($amount_paid > 0) {
                    // echo $amount_paid;
                    $amount_rem = $amount_paid;
                    if (count($data) == ($index+1)) {
                        // echo $amount_rem." ".$salary." rem <br>";
                        $salary -= $amount_rem;
                        $stringdata = "<p>-Kes ".comma($amount_rem)." ".payMode($data[$index][1])."  (".date("d-M-Y",strtotime($data[$index][3])).") </p>";
                        array_push($breakdown,$stringdata);
                        $stringdata = "";
                        $m_date = date("M - Y",strtotime($fdate));
                        $months_n_salary += [$m_date => $breakdown];
                        $breakdown = [];
                    }else {
                        // echo $amount_rem." ".$salary." rem <br>";
                        $salary -= $amount_rem;
                        $stringdata = "<p>-Kes ".comma($amount_rem)." ".payMode($data[$index][1])."  (".date("d-M-Y",strtotime($data[$index][3])).") </p>";
                        array_push($breakdown,$stringdata);
                        $stringdata = "";
                        $m_date = date("M - Y",strtotime($fdate));
                        // $months_n_salary += [$m_date => $breakdown];
                        // $breakdown = [];
                        $amount_rem = 0;
                        // echo $salary." mine<br>";
                    }
                }
            }
        }
        // var_dump($breakdown);
        return $months_n_salary;
    }  
    function isJson_report_fin($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    function log_finance($text){
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

    function get_current_value($row, $final_year = null){
        $value_acquisition_option = $row['acquisition_option'];
        $value_acquisition_rate = $row['acquisition_rate'];
        $original_value = $row['orginal_value'];
        $date_acquired = $row['date_of_acquiry'];
        $disposed_status = $row['disposed_status'];
        $disposed_on = $row['disposed_on'];

        // final year
        if($final_year == null){
            $final_year = date("Y");
        }
        $financial_year_end = date("Y")."0630235959";
        $date_acquired = date("YmdHis",strtotime($date_acquired));

        // get the date difference
        $date1 = date_create($financial_year_end);
        $date2 = date_create($date_acquired);
        $diff = date_diff($date1,$date2);
        $difference_year = $diff->format("%y");

        $year = date("Y",strtotime($date_acquired));
        
        if($value_acquisition_option == "1"){
            // straight line method increase
            $reduction = 0;
            $accounts = [];
            $values = array("account" => "debit", "name" => "Purchase", "amount" => "Kes ".number_format($original_value), "balance" => "Kes ".number_format($original_value), "year" => $year);
            $balance = $original_value;
            array_push($accounts,$values);
            for($index = 0; $index < $difference_year; $index++){
                $reduce = round((($value_acquisition_rate / 100) * $original_value) , 2);
                $reduction += $reduce;

                // accounting
                $balance -= $reduce;
                $year += 1;
                $values = array("account" => "credit", "name" => "Depreciation", "amount" => "Kes ".number_format($reduce), "balance" => "Kes ".number_format($balance), "year" => $year);
                array_push($accounts,$values);


                // see if its diposable
                if($disposed_status == "1"){
                    if(date("Y", strtotime($disposed_on)) == $year){
                        $values = array("account" => "credit", "name" => "Disposed (". date("D dS M Y",strtotime($row['disposed_on'])).")", "amount" => "Kes ".number_format($balance), "balance" => "Kes 0", "year" => $year);
                        array_push($accounts,$values);
                        $reduction += $balance;
                        $balance = 0;
                        break;
                    }
                }

                // final year break
                if($final_year == $year){
                    break;
                }
                
                // break if balance is 0
                if($balance <= 0){
                    break;
                }
            }

            // reduce from the original
            $balance_left = $original_value - $reduction;
            return array("years" => $difference_year, "new_value" => $balance_left, "reduction_amount" => $reduction, "original_value" => $original_value, "value_acquisition_method" => "Straight Line Method (-ve)", "value_acquisition" => "decrease", "account" => $accounts);
        }elseif($value_acquisition_option == "2"){
            // straight line method increase
            $reduction = 0;
            $store_original_value = $original_value;

            // account
            $accounts = [];
            $values = array("account" => "debit", "name" => "Purchase", "amount" => "Kes ".number_format($original_value), "balance" => "Kes ".number_format($original_value), "year" => $year);
            $balance = $original_value;
            array_push($accounts,$values);
            for($index = 0; $index < $difference_year; $index++){
                $reduce = round((($value_acquisition_rate / 100) * $original_value) , 2);
                $reduction += $reduce;

                // get the original value
                $original_value = $original_value - $reduce;

                // accounting
                $year += 1;
                $values = array("account" => "credit", "name" => "Depreciation", "amount" => "Kes ".number_format($reduce), "balance" => "Kes ".number_format($original_value), "year" => $year);
                array_push($accounts,$values);

                // see if its diposable
                if($disposed_status == "1"){
                    if(date("Y", strtotime($disposed_on)) == $year){
                        $values = array("account" => "credit", "name" => "Disposed (". date("D dS M Y @ H:i:s",strtotime($row['disposed_on'])).")", "amount" => "Kes ".number_format($balance), "balance" => "Kes 0", "year" => $year);
                        array_push($accounts,$values);
                        $reduction += $balance;
                        $balance = 0;
                        break;
                    }
                }

                // final year break
                if($final_year == $year){
                    break;
                }
                
                // balance
                if($balance <= 0){
                    break;
                }
            }

            // reduce from the original
            $balance_left = $original_value;
            $original_value = $store_original_value;
            return array("years" => $difference_year, "new_value" => $balance_left, "reduction_amount" => $reduction, "original_value" => $original_value, "value_acquisition_method" => "Reducing Balance Method (-ve)", "value_acquisition" => "decrease", "account" => $accounts);
        }elseif($value_acquisition_option == "3"){
            // straight line method increase
            $reduction = 0;
            $accounts = [];
            $values = array("account" => "debit", "name" => "Purchase", "amount" => "Kes ".number_format($original_value), "balance" => "Kes ".number_format($original_value), "year" => $year);
            $balance = $original_value;
            array_push($accounts,$values);
            for($index = 0; $index < $difference_year; $index++){
                $reduce = round((($value_acquisition_rate / 100) * $original_value) , 2);
                $reduction += $reduce;

                // accounting
                $balance += $reduce;
                $year += 1;
                $values = array("account" => "debit", "name" => "Appreciation", "amount" => "Kes ".number_format($reduce), "balance" => "Kes ".number_format($balance), "year" => $year);
                array_push($accounts,$values);

                // see if its diposable
                if($disposed_status == "1"){
                    if(date("Y", strtotime($disposed_on)) == $year){
                        $values = array("account" => "credit", "name" => "Disposed (". date("D dS M Y @ H:i:s",strtotime($row['disposed_on'])).")", "amount" => "Kes ".number_format($balance), "balance" => "Kes 0", "year" => $year);
                        array_push($accounts,$values);
                        $reduction += $balance;
                        $balance = 0;
                        break;
                    }
                }

                // final year break
                if($final_year == $year){
                    break;
                }

                // balance
                if($balance <= 0){
                    break;
                }
            }

            // reduce from the original
            $balance_left = $original_value + $reduction;
            return array("years" => $difference_year, "new_value" => $balance_left, "reduction_amount" => $reduction, "original_value" => $original_value, "value_acquisition_method" => "Straight Line Method (+ve)", "value_acquisition" => "increase", "account" => $accounts);
        }elseif($value_acquisition_option == "4"){
            // reducing method increase
            $reduction = 0;
            $store_original_value = $original_value;

            // account
            $accounts = [];
            $values = array("account" => "debit", "name" => "Purchase", "amount" => "Kes ".number_format($original_value), "balance" => "Kes ".number_format($original_value), "year" => $year);
            $balance = $original_value;
            array_push($accounts,$values);
            for($index = 0; $index < $difference_year; $index++){
                $reduce = round((($value_acquisition_rate / 100) * $original_value) , 2);
                $reduction += $reduce;

                // get the original value
                $original_value = round(($original_value + $reduce), 2);

                // accounting
                $year += 1;
                $values = array("account" => "debit", "name" => "Appreciation", "amount" => "Kes ".number_format($reduce), "balance" => "Kes ".number_format($original_value), "year" => $year);
                array_push($accounts,$values);
                
                // see if its diposable
                if($disposed_status == "1"){
                    if(date("Y", strtotime($disposed_on)) == $year){
                        $values = array("account" => "credit", "name" => "Disposed (". date("D dS M Y @ H:i:s",strtotime($row['disposed_on'])).")", "amount" => "Kes ".number_format($balance), "balance" => "Kes 0", "year" => $year);
                        array_push($accounts,$values);
                        $reduction += $balance;
                        $balance = 0;
                        break;
                    }
                }

                // final year break
                if($final_year == $year){
                    break;
                }

                // balance
                if($balance <= 0){
                    break;
                }
            }

            // reduce from the original
            $balance_left = $original_value;
            $original_value = $store_original_value;
            return array("years" => $difference_year, 'disposed_value' => $row['disposed_value'] , "new_value" => $balance_left, "reduction_amount" => $reduction, "original_value" => $original_value, "value_acquisition_method" => "Reducing Balance Method (+ve)", "value_acquisition" => "increase", "account" => $accounts);
        }else{
            return array("years" => $difference_year, 'disposed_value' => $row['disposed_value'] , "new_value" => $original_value, "reduction_amount" => 0, "original_value" => $original_value, "value_acquisition_method" => "No Method", "value_acquisition" => "increase", "account" => []);
        }
    }
