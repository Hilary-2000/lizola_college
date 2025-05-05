<?php
    session_start();
    date_default_timezone_set('Africa/Nairobi');
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        include("../../connections/conn2.php");
        if (isset($_GET['notices'])) {
            $select = "SELECT COUNT(*) AS 'Total' FROM `tblnotification` WHERE `notification_status` = 0  AND (`notification_reciever_id` = ? OR `notification_reciever_id` = 'all') AND (`notification_reciever_auth` = ? OR `notification_reciever_auth` = 'all')";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$_SESSION['userids'],$_SESSION['authority']);
            $stmt->execute();
            $result = $stmt->get_result();
            $totals_num = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $totals_num = $row['Total'];
                }
            }
            echo $totals_num;
        }elseif (isset($_GET['getNoticeTitles'])) {
            $select = "SELECT `notification_id`,`notification_name` FROM `tblnotification` WHERE `notification_status` = 0 AND (`notification_reciever_id` = ? OR `notification_reciever_id` = 'all') AND (`notification_reciever_auth` = ? OR `notification_reciever_auth` = 'all') ORDER BY `notification_id` DESC LIMIT 10";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$_SESSION['userids'],$_SESSION['authority']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<div class='notice set_notify' id='not".$row['notification_id']."' title = 'Click to open message'>
                                        <p>".$xs.". ".$row['notification_name']."</p>
                                    </div>";
                }
                if ($xs>0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No notices present!</p>";
                }
            }
        }elseif (isset($_GET['getAllMessages'])) {
            $select = "SELECT `notification_id`,`notification_name`,`notification_status`,`Notification_content` FROM `tblnotification` where (`notification_reciever_id` = ? OR `notification_reciever_id` = 'all') AND (`notification_reciever_auth` = ? OR `notification_reciever_auth` = 'all') ORDER BY `notification_id` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ss",$_SESSION['userids'],$_SESSION['authority']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display="<div class = 'table_holders'><table '>
                                        <!--ROW-->
                                        <tr class=''>
                                            <th>No</th>
                                            <th>Notice Title</th>
                                            <th>Notice Content</th>
                                            <th>Status</th>
                                            <th>Option</th>
                                            <th>Delete</th>
                                        </tr>";
                                        $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $status = $row['notification_status'];
                    if ($status == 0) {
                        $status = "Not Read";
                        $bolder = "bolder";
                    }else {
                        $status = "Read";
                        $bolder = "";
                    }
                    $data_to_display.="<tr class='".$bolder."'>
                                            <!--Headings-->
                                            <td>".$xs.".</td> 
                                            <td>".$row['notification_name']."</td>
                                            <td>".$row['Notification_content']."</td>
                                            <td>".$status."</td>
                                            <td><div class='link read_message' id='red".$row['notification_id']."'><p>Read..</p></div></td>
                                            <td><div class='link delete_notice' id='dele".$row['notification_id']."'><p>Delete</p></div>
                                            </td>
                                        </tr>";
                }
                $data_to_display.="</table></div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>You have no notification !</p>";
                }
            }else {
                echo "<p class='red_notice'>You have no notification !</p>";
            }
        }elseif (isset($_GET['getMyNoticeid'])) {
            $select = "SELECT `notification_id`,`notification_name`,`Notification_content`,`sender_id`,`notification_reciever_id`,`notification_reciever_auth` FROM `tblnotification` WHERE `notification_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$_GET['getMyNoticeid']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "";
                $xs = 0;
                if ($row = $result->fetch_assoc()) {
                    $xs++;
                    $data_to_display.="<div class='message_header'>
                                        <label class='hide'>Message type : <span>Broadcast</span><br></label>
                                        <label class=''>Message id : <span id='notify_id'>".$row['notification_id']."</span><br></label>
                                        <label>Message Title : <span>".$row['notification_name']."</span><br></label>
                                        <label>Message From : <span>".getTeacherName($row['sender_id'])."</span><br></label>
                                    </div>
                                    <div class='message_contents'>
                                        <label><u>Message content</u></label>
                                        <p style='color:midnightblue;font-weight:400px;font-size:12px;'>".$row['Notification_content']."</p>
                                    </div>";
                    $update = "UPDATE `tblnotification` set `notification_status` = 1 WHERE `notification_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$_GET['getMyNoticeid']);
                    $stmt->execute();
                    
                }
                if ($xs>0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>This message has been deleted!</p>";
                }
            }else {
                echo "<p class='red_notice'>An error has occured!</p>";
            }
        }elseif (isset($_GET['delete_notice'])) {
            $notice_id = $_GET['delete_notice'];    
            $delete = "DELETE FROM `tblnotification` WHERE `notification_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$notice_id);
            if($stmt->execute()){
            }else {
                echo "<p class='red_notice'>Error occured!</p>";
            }
        }
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
        return $tr_id;
    }
?>