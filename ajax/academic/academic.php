<?php
    session_start();
    date_default_timezone_set('Africa/Nairobi');
    
    // var_dump($_FILES);
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        include("../../connections/conn2.php");
        if (isset($_GET['showsubjects'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $class_explode = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }
                    $class_explode = $all_classes;
                }
            }
            
            $data = "<div class ='classlist form-control' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
            $xs = 0;
            if (count($class_explode) > 0){
                $arr = $class_explode;
                for ($i=0; $i < count($arr); $i++) {
                    $xs++;
                    $datas = "Class ".$arr[$i];
                    if (strlen($arr[$i])>1) {
                        $datas = $arr[$i];
                    }
                    $data.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                    $data.="<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='".$arr[$i]."'>".$datas."</label>";
                    $data.="<input class='subjectclass' type='checkbox' name='".$arr[$i]."' id='".$arr[$i]."'>";
                    $data.="</div>";
                }
            }
            $data.="</div>";
            if ($xs > 0) {
                echo $data;
            }else {
                echo "<p class='red_notice'>No classes are available<br>Contact your administrator to rectify that!</p>";
            }
            $stmt->close();
            $conn2->close();
        }else if (isset($_GET['showsubjected'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $class_explode = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }
                    $class_explode = $all_classes;
                }
            }
            
            $data = "<div class ='classlist' style='height:200px;overflow:auto;' name='selectsubs' id='selectsubs'>";
            if (count($class_explode)>0){
                $arr = $class_explode;
                for ($i=0; $i < count($arr); $i++) {
                    $datas = "Class ".$arr[$i];
                    if (strlen($arr[$i])>1) {
                        $datas = $arr[$i];
                    }
                    $data.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                    $data.="<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='check".$arr[$i]."'>".$datas."</label>";
                    $data.="<input class='checkclas' type='checkbox' value='".$arr[$i]."' name='check".$arr[$i]."' id='check".$arr[$i]."'>";
                    $data.="</div>";
                }
            }
            $data.="</div>";
            echo $data;
            $stmt->close();
            $conn2->close();
        }elseif (isset($_GET['add_another_user'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = "";
            $inside = 0;
            if ($result) {
                if ($row= $result->fetch_assoc()) {
                    $data = $row['valued'];
                    $inside = 1;
                }
            }
            $role_name = $_GET['role_name'];
            $role_doing = $_GET['role_doing'];
            if(strlen($data) > 0){
                $data = substr($data,0,(strlen($data)-1));
                $data.=",{\"name\":\"".$role_name."\",\"roles\":".$role_doing."}]";
                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'user_roles'";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$data);
                if($stmt->execute()){
                    echo "<p class='text-success'>Role Updates successfully!</p>";
                }else{
                    echo "<p class='text-success'>An error occured during update!</p>";
                }
            }else{
                if ($inside == 1) {
                    $data ="[{\"name\":\"".$role_name."\",\"roles\":".$role_doing."}]";
                    $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'user_roles'";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("s",$data);
                    if($stmt->execute()){
                        echo "<p class='text-success'>Role Updates successfully!</p>";
                    }else{
                        echo "<p class='text-success'>An error occured during update!</p>";
                    }
                }else{
                    $data ="[{\"name\":\"".$role_name."\",\"roles\":".$role_doing."}]";
                    $insert = "INSERT INTO `settings` (`sett`,`valued`) VALUES ('user_roles',?)";
                    $stmt = $conn2->prepare($insert);
                    $stmt->bind_param("s",$data);
                    if($stmt->execute()){
                        echo "<p class='text-success'>Role Updates successfully!</p>";
                    }else{
                        echo "<p class='text-success'>An error occured during update!</p>";
                    }
                }
            }
            // create the log text
            $log_text = "Role \"".ucwords(strtolower($role_name))."\" has been added successfully!";
            log_academic($log_text);
        }elseif (isset($_GET['staff_roles'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles'";
            $stmt= $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo "".$row['valued']."";
                }
            }
        }
        elseif (isset($_GET['get_user_roles'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['valued'];
                }
            }
            echo "";
        }elseif (isset($_GET['showsubject'])) {
            $select = "SELECT `valued` FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $class_explode = [];
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }
                    $class_explode = $all_classes;
                }
            }
            
            
            $data = "<select id='classtaughts'> <option value='' hidden>Select..</option>";
            if (count($class_explode) > 0){
                $arr = $class_explode;
                for ($i=count($arr)-1; $i >= 0; $i--) {
                    $datas = "Class ".$arr[$i];
                    if (strlen($arr[$i])>1) {
                        $datas = $arr[$i];
                    }
                    $data.="<option value='".$arr[$i]."' >".$datas."</option>";
                }
            }
            $data.="</select>";
            echo $data;
            $stmt->close();
            $conn2->close();
            
        } elseif (isset($_GET['addsubject'])) {
            $subjectname = $_GET['subjectname'];
            $present = isPresent($subjectname,$conn2);
            if ($present != true) {
                $subjmax = $_GET['subjectmax'];
                $classes = $_GET['claslist'];
                $sundids = $_GET['subids'];
                $grades_lists = $_GET['grades_lists'];
                $subject_display_name = $_GET['subject_display_name'];
                $subactive = 1;
                $insert = "insert INTO `table_subject` (`subject_name`,`max_marks`,`classes_taught`,`sub_activated`,`timetable_id`,`grading`,`display_name`) VALUES (?,?,?,?,?,?,?)";
                $stmt = $conn2->prepare($insert);
                $stmt->bind_param("sssssss",$subjectname,$subjmax,$classes,$subactive,$sundids,$grades_lists,$subject_display_name);
                if($stmt->execute()){
                    echo "<p style='color:green;'>Subject inserted successfully!</p>";
                }else {
                    echo "<p style='color:red;'>Error occured during insertion!</p>";
                }
            }else {
                echo "<p style='color:red;'>The subject name is already used!<br>Try using another name.</p>";
            }
        }elseif (isset($_GET['findname'])) {
            $select = "SELECT * FROM `table_subject` WHERE `subject_name` = ?";
            $name = $_GET['name'];
            $present = isPresent($name,$conn2);
            if ($present == true) {
                echo "<p style='color:red;'>Subject name is present!</p>";
            }else {
                echo "<p></p>";
            }
            $conn2->close();
        }elseif (isset($_GET['searchsubjby'])) {
            $find = $_GET['searchsubjby'];
            $select = "";
            $result;
            $errors="";
            $search = "";
            if ($find == "byname") {
                $name = "%".$_GET['subjename']."%";
                $select = 'SELECT * FROM `table_subject` WHERE `subject_name` like ?';
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$name);
                $stmt->execute();
                $result = $stmt->get_result();
                $search = "Subject name containing letters <span style='color:brown;'>\"".$_GET['subjename']."\"</span>";
                $errors = "Subject name containing letters <b>\"".$_GET['subjename']."\"</b>!<br>Check your spelling or you can also search by class";
            }elseif ($find == "byclass") {
                $class = "%".$_GET['class']."%";
                $select = 'SELECT * FROM `table_subject` WHERE `classes_taught` LIKE ?';
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$class);
                $stmt->execute();
                $result = $stmt->get_result();
                $search = "Subjects taught in <span style='color:brown;'>Class \"".$_GET['class']."\"</span>";
                $errors.=" Subjects taught in Class \"".$_GET['class']."\"!";
            }
            if ($result) {
                $tableinformation = "<div class='information' id ='information'><h6 style='font-size:17px;font-weight:500;text-align:center;'>Result for ".$search."<br><u>Subjects table</u></h6><p id='pleasewait3' style='color:green;text-align:center;'>Preparing please wait before viewing!</p><div class='tableme'><table><tr><th>No</th><th>Subject name</th><th>Display Name</th><th>TT Id</th><th>Maximum marks</th><th>Classes taught</th><th>Options</th></tr>";
                $xs =0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $ttsd = $row['timetable_id'];
                    if (strlen(trim($ttsd))<1) {
                        $ttsd = "N/A";
                    }
                    $tableinformation.="<tr><td>".$xs."</td><td>".ucwords(strtolower($row['subject_name']))."</td><td>".(strlen(trim($row['display_name'])) > 0 ? trim($row['display_name']):"<span class='text-danger'>Not Set</span>")."</td><td>".$ttsd."</td><td>".$row['max_marks']."</td><td>".$row['classes_taught']."</td><td><p style='font-size:12px;;margin:0 auto;' class ='viewsubj link' value='".$row['subject_id']."' id='class".$row['subject_id']."'><i class='fas fa-pen'></i> Edit</p></td></tr>";
                }
                $tableinformation.="</table></div></div>";
                if ($xs>0) {
                    echo $tableinformation;   
                }else {
                    echo "<p style='color:red;'>No results for ".$errors."</p>";
                }
            }
        }elseif (isset($_GET['subjectids'])) {
            $subids = $_GET['subjectids'];
            $select = "SELECT * FROM `table_subject` WHERE `subject_id`=?";
            $stmt=$conn2->prepare($select);
            $stmt->bind_param("s",$subids);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $data.=$row['subject_id']."&".$row['subject_name']."&".$row['max_marks']."&".$row['classes_taught']."&".$row['sub_activated']."&".$row['timetable_id']."&".$row['grading']."&".$row['display_name'];
                }
            }
            if (strlen($data)>0) {
                echo $data;
            }else {
                echo "null";
            }
        }
        elseif (isset($_GET['updatesubjects'])) {
            $subname = $_GET['subname'];
            $ttid = $_GET['subttid'];
            $maxmark = $_GET['submaxmarks'];
            $classtaught = $_GET['classtaught'];
            $subid = $_GET['subjeid'];
            $subject_grade = $_GET['subject_grade'];
            $sub_display_name = $_GET['sub_display_name'];
            $update = "UPDATE `table_subject` SET `subject_name` = ?,`timetable_id` = ?,`max_marks` = ?, `classes_taught` = ?, `grading` = ?,`display_name` = ? WHERE `subject_id` =?";
            $stmt= $conn2->prepare($update);
            $stmt->bind_param("sssssss",$subname,$ttid,$maxmark,$classtaught,$subject_grade,$sub_display_name,$subid);
            if($stmt->execute()){
                //remove the missing classes that are present on the teacher id column
                $select = "SELECT `teachers_id` FROM `table_subject` WHERE subject_id = ?";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("i",$subid);
                $stmt->execute();
                $res = $stmt->get_result();
                $teachid = "";
                if ($res) {
                    if ($row = $res->fetch_assoc()) {
                        $teachid = $row['teachers_id'];
                    }
                }
                if (strlen($teachid)>0) {
                    $datasplit = explode(",",$classtaught);
                    $trsplit = explode("|",$teachid);
                    for ($d=0; $d < count($trsplit); $d++) { 
                        $clasd = explode( ":", substr($trsplit[$d],1,(strlen($trsplit[$d]) - 2)));
                        $present = 0;
                        for ($i=0; $i < count($datasplit); $i++) { 
                            $newclasslist = $datasplit[$i];
                            if ($clasd[1] == $newclasslist) {
                                $present = 1;
                            }
                        }
                        if ($present == 0) {
                            unset($trsplit[$d]);
                        }
                    }
                    $trsplit = array_values($trsplit);
                    $trids = "";
                    for ($tts=0; $tts < count($trsplit); $tts++) { 
                        if ($tts+1 == count($trsplit)) {
                            $trids.=$trsplit[$tts];
                        }else{
                            $trids.=$trsplit[$tts]."|";
                        }
                    }
                    //update the classes with the teacher ids
                    $update = "UPDATE `table_subject` set `teachers_id` = ? WHERE `subject_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$trids,$subid);
                    if($stmt->execute()){
                        echo "<p style='color:green;'>Subject information updated successfully!</p>";
                    }else{
                        echo "<p style='color:red;'>Ann error occured!<br>Try again later!</p>";
                    }
                }elseif ($res) {
                    echo "<p style='color:green;'>Subject information updated successfully!</p>";
                }
            }else {
                echo "<p style='color:red;'>An error occured during updating</p>";
            }

        }elseif (isset($_GET['findsubjects'])) {
            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $tableinformation = "<div class='information' id ='information'><h6 style='text-align:center;font-size:17px;font-weight:500;'><u>Subjects table</u></h6><p id='pleasewait3' style='color:green;text-align:center;'>Preparing please wait before viewing!</p><div class='tableme'><table class='table'><tr><th>No</th><th>Subject name</th><th>Display Name</th><th>TT Id</th><th>Maximum marks</th><th>Classes taught</th><th>Options</th></tr>";
                $xs =0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $ttsd = $row['timetable_id'];
                    if (strlen(trim($ttsd))<1) {
                        $ttsd = "N/A";
                    }
                    $tableinformation.="<tr><td>".$xs."</td><td>".ucwords(strtolower($row['subject_name']))."</td><td>".(strlen(trim($row['display_name'])) > 0 ? trim($row['display_name']):"<span class='text-danger'>Not Set</span>")."</td><td>".ucwords(strtolower($ttsd))."</td><td>".$row['max_marks']."</td><td>".ucwords(strtolower($row['classes_taught']))."</td><td><span style='font-size:12px;;margin:0 auto;' class ='viewsubj link' value='".$row['subject_id']."' id='class".$row['subject_id']."'><i class='fa fa-pen'></i> Edit</span></td></tr>";
                }
                $tableinformation.="</table></div></div>";
                if ($xs>0) {
                    echo $tableinformation;   
                }else {
                    echo "<p style='color:red;margin-top:10px;text-align:center;font-size:12px;font-weight:600;'>No results!</p>";
                }
            }
        }elseif (isset($_GET['seachby'])) {
            $searchby = $_GET['seachby'];
            $select = "SELECT `fullname`,`gender`,`user_id` FROM `user_tbl` WHERE ";
            $parse = "";
            $schoolcode;
            if(isset($_SESSION['schoolcode'])){
                $schoolcode = $_SESSION['schoolcode'];
            }else{
                $schoolcode = '';
            }
            if ($searchby=='byname') {
                $select.="`fullname` LIKE ? AND `school_code` = ?";
                $parse = "%".$_GET['name']."%";
            }elseif ($searchby=='byidno') {
                $select.="`nat_id` like ? AND `school_code` = ?";
                $parse = "%".$_GET['idnos']."%";
                echo "in me";
            }elseif ($searchby == 'all_trs') {
                $select.=" `deleted` = ? AND `school_code` = ?";
                $parse = 0;
            }
            include("../../connections/conn1.php");
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$parse,$schoolcode);
            $stmt->execute();
            $results = $stmt->get_result();
            if ($results) {
                //create a table
                $xs = 0;
                $tableinformation="<h6 class='text-center'><b>Teachers And Subjects</b></h6><div class='tableme' ><table class='table'><tr><th>No</th><th>Fullnames</th><th>Subject Taught</th><th>Option</th></tr>";
                while ($row = $results->fetch_assoc()) {
                    $xs++;
                    $trid = trim($row['user_id']);
                    $subjectlist = getSubjectsTaught($trid,$conn2);
                    $tableinformation.="<tr><td>".$xs."</td><td>".ucwords(strtolower($row['fullname']))."</td><td>".$subjectlist."</td><td>"."<span class='setSubclass link' id='sub".$row['user_id']."' ><i class ='fa fa-pen'></i> Edit </span>"."</td></tr>";
                }
                $tableinformation.="</table></div>";
                if ($xs>0) {
                    echo $tableinformation;
                }else {
                    echo "<p style='color:red;'>No results!<br>When using name to search, an incomplete name can help you locate the teacher for example  'JO' for 'Joel, Joseph or John'</p>";
                }
            }
            $stmt->close();
            $conn->close();
        }elseif (isset($_GET['getbyid'])) {
            $idnumber = substr($_GET['getbyid'],3);
            $select = "SELECT `subject_id`,`subject_name`,`timetable_id`,`classes_taught`,`teachers_id` FROM `table_subject` WHERE `teachers_id` like ?";
            $parse = "%(".$idnumber.":%";
            $stmt= $conn2->prepare($select);
            $stmt->bind_param("s",$parse);
            $stmt->execute();
            $results = $stmt->get_result();
            $starttext = "<p class='hide' id ='namenid'>".getNameWithId($idnumber)."</p>";
            if($results){
                $xd =0;
                $classTableinfor = "<div class='tableme'><table class='table'><tr><th>Subject name</th><th>Classes Taught</th><th>Options</th></tr>";
                while ($row=$results->fetch_assoc()) {
                    $xd++;
                    $madaro = "none";
                    $classtaught = $row['teachers_id']; //returns a string in form of (trid:classtaught)|(trid:classtaught)
                    if (strlen(trim($classtaught))>0) {
                        $classes = explode("|",$classtaught);//split (trid:classtaught)|(trid:classtaught) into (trid:classtaught) strings
                        $madaro = "";
                        if (count($classes)>0) {
                            $xsd = 0;
                            for ($i=0; $i < count($classes); $i++) { 
                                //check if the class is similar to what the teacher is teaching
                                $wholeStr = explode(":",$classes[$i]);//splits this (trid:classtaught) into this (trid and this classtaught)
                                $tris = substr($wholeStr[0],1);
                                if ($tris == $idnumber) {
                                    $xsd++;
                                    $daro = substr($wholeStr[1],0,(strlen($wholeStr[1])-1));
                                    if ($xsd==1) {
                                        $madaro.=$daro;   
                                    }else {
                                        $madaro.=",".$daro;
                                    }
                                }
                            }   
                        }
                    }
                    $classTableinfor.="<tr><td>".$row['subject_name']."</td><td id ='classlist".$row['subject_id']."'>".$madaro."</td><td>"."<span class ='subsbtns link' id='cld".$row['subject_id']."' value='".$row['subject_id']."' ><i class='fa fa-pen'></i> Edit</span>"."</td></tr>";
                }
                $classTableinfor.="</table></div>";
                if ($xd>0) {
                    echo  $starttext."".$classTableinfor;
                }else {
                    echo $starttext."<p style='color:red;'>No subjects added yet</p>";
                }
            }
        }elseif (isset($_GET['askClasses'])) {
            $subid = $_GET['subid'];
            $select = "SELECT `classes_taught`,`subject_name` FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subid);
            $stmt->execute();
            $result = $stmt->get_result();
            $classl = "";
            if ($result) {
                $subanem = "";
                if ($row = $result->fetch_assoc()) {
                    $classl = $row['classes_taught'];
                    $subanem = $row['subject_name'];
                }

                $data = "<p id='subnameholder' class='hide'>".$subanem."</p><div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                if (strlen($classl)>0){
                    $arr = explode(",",$classl);
                    for ($i=0; $i < count($arr); $i++) {
                        $datas = "Class ".$arr[$i];
                        if (strlen($arr[$i])>1) {
                            $datas = $arr[$i];
                        }
                        $data.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $data.="<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='chek".$arr[$i]."'>".$datas."</label>";
                        $data.="<input class='checkclases' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>";
                        $data.="</div>";
                    }
                }
                $data.="</div>";
                echo $data;
            }else{
                echo "<p>No classes selected yet</p>";
            }
        }elseif (isset($_GET['sendSubjectInform'])) {
            $subjectdata = $_GET['finaldata'];
            $teacherid = $_GET['teacherid'];
            $subjectid = $_GET['subjectid'];
            //get the subject teacher information and sort it
            $select = "SELECT `teachers_id` FROM`table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subjectid);
            $stmt->execute();
            $res = $stmt->get_result();
            $subjects = "";
            if ($res) {
                if ($row = $res->fetch_assoc()) {
                    $subjects = $row['teachers_id'];
                }
                if (strlen($subjects) > 0) {
                    //check if the same class is taught by the same teacher

                    //get classes from the new data
                    $madarasa ="" ;
                    $subjectd = explode("|",$subjectdata);
                    if (strlen($subjectdata)>0) {
                        for ($vs=0; $vs < count($subjectd); $vs++) {
                            $madarasa.=explode(":",substr($subjectd[$vs],1, strlen($subjectd[$vs])-2))[1].",";
                        }
                        $madarasa = substr($madarasa,0,strlen($madarasa)-1);
                    }
                    //get the classes from the old data
                    $madarado = explode("|",$subjects);
                    $classed = explode(",",$madarasa);
                    for ($vxc=0; $vxc < count($madarado); $vxc++) { 
                        //loop from the classes added to see if the class is present
                        for ($s=0; $s < count($classed); $s++) { 
                            //get class from madarado
                            $onec = explode(":",substr($madarado[$vxc],1,strlen($madarado[$vxc])-2))[1];
                            if ($onec == $classed[$s]) {
                                $madarado[$vxc] = "(".$teacherid.":".$onec.")";
                            }
                        }
                    }
                    $newstring = [];
                    for ($dsc=0; $dsc < count($subjectd); $dsc++) {
                        $subdata = $subjectd[$dsc];
                        $err = checkPresnt($newstring,$subdata);
                        if ($err == 0) {
                            array_push($newstring,$subdata);
                        }
                    }
                    for ($vcs=0; $vcs < count($madarado); $vcs++) { 
                        $subdata1 = $madarado[$vcs];
                        $err = checkPresnt($newstring,$subdata1);
                        if ($err == 0) {
                            array_push($newstring,$subdata1);
                        }
                    }
                    $newdatalist = "";
                    for ($xcx=0; $xcx < count($newstring); $xcx++) { 
                        $newdatalist.=$newstring[$xcx]."|";
                    }
                    $newdatalist = substr($newdatalist,0,strlen($newdatalist)-1);
                    //check the classes and the id of the completed class list of the teacher
                    $finishedlist = explode("|",$newdatalist);
                    for ($dc=0; $dc < count($finishedlist); $dc++) { 
                        if (strlen($finishedlist[$dc])) {
                            $mclass = explode(":",substr($finishedlist[$dc],1,strlen($finishedlist[$dc])-2))[1];
                            $id = explode(":",substr($finishedlist[$dc],1,strlen($finishedlist[$dc])-2))[0];
                            if ($id == $teacherid) {
                                $presented = 0;
                                for ($ind=0; $ind < count($classed); $ind++) { 
                                    if ($classed[$ind] == $mclass) {
                                        $presented = 1;
                                    }
                                }
                                if ($presented == 0) {
                                    $finishedlist[$dc] = "";
                                }
                            }                            
                        }else {
                            continue;
                        }
                    }

                    //set the newdatalist once again
                    $newdatalist = "";
                    for ($ede=0; $ede < count($finishedlist); $ede++) { 
                        if (strlen($finishedlist[$ede]) > 1) {
                            $newdatalist.=$finishedlist[$ede]."|";
                        }
                    }
                    if (strlen($newdatalist)>0) {
                        $newdatalist = substr($newdatalist,0,strlen($newdatalist)-1);
                    }
                    //update the new subject information
                    $update = "UPDATE `table_subject` SET `teachers_id` = ? WHERE `subject_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$newdatalist,$subjectid);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:12px;'>Information updated successfully!</p>";
                    }else {
                       echo "<p style='color:red;font-size:12px;'>An error has occured!<br> Try again later</p>";
                    }
                }
            }
        }elseif (isset($_GET['getsubjects'])) {
            $teacherid = $_GET['teacherid'];
            //check the subjects the teacher is teaching
            $vars = "%(".$teacherid.":%";
            $select = "SELECT `subject_id` FROM `table_subject` WHERE `teachers_id` LIKE ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$vars);
            $stmt->execute();
            $res = $stmt->get_result();
            $data="";
            //get the class and store it as array;
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $data.=$row['subject_id'].",";
                }
                if (strlen($data) > 0) {//if the data got from the database is atleast 1 letter long
                    $data = substr($data,0,strlen($data)-1);
                    //split the subjects id into array
                    $subjectsid = explode(",",$data);
                    //get the subjects list again so that it can be populated
                    $select = "SELECT `subject_id`, `classes_taught`,`subject_name` FROM `table_subject` WHERE `sub_activated` = 1";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        $dcvx = 1;
                        $echodata = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs1' id='selectsubs1'>";
                        $xs = 0;
                        while ($row = $result->fetch_assoc()) {
                            $subid = $row['subject_id'];
                            $subjectname = $row['subject_name'];
                            //check the if the subject is taught by the teacher
                            $ispresent = 0;
                            for ($dcv=0; $dcv < count($subjectsid); $dcv++) { 
                                if (strlen($subjectsid[$dcv])>0) {
                                    if ($subjectsid[$dcv] == $subid) {
                                        $ispresent = 1;
                                    }
                                }else {
                                    continue;
                                }
                            }
                            //add the data string 
                            if ($ispresent == 0) {//if the subject is present dont add it to the list
                                $echodata .="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label style='margin-right:5px;cursor:pointer;font-size:12px;' for='subc".$subid."'>".$dcvx.".  ".$subjectname."</label>
                                <input class='checksubjects hide' type='checkbox' value='".$subjectname."|".$subid."'  id='subc".$subid."'></div>";
                                $xs++;
                                $dcvx++;
                            }
                        }
                        $echodata .= "</div>";
                        if ($xs>0) {
                            echo $echodata;
                        }else {
                            echo "<p style='color:green;font-size:12px;'>The teacher teaches all subjects</p>";
                        }
                    }
                }else {
                    //get the subjects list again so that it can be populated
                    $select = "SELECT `subject_id`, `classes_taught`,`subject_name` FROM `table_subject` WHERE `sub_activated` = 1";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        $dcvx = 1;
                        $echodata = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs1' id='selectsubs1'>";
                        $xs = 0;
                        while ($row = $result->fetch_assoc()) {
                            $subid = $row['subject_id'];
                            $subjectname = $row['subject_name'];
                            $echodata .="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                    <label style='margin-right:5px;cursor:pointer;font-size:12px;'  for='subc".$subid."'>".$dcvx.".  ".$subjectname."</label>
                                    <input class='checksubjects hide' type='checkbox' value='".$subjectname."|".$subid."'  id='subc".$subid."'></div>";
                            $xs++; 
                            $dcvx++;                           
                        }
                        $echodata .= "</div>";
                        if ($xs>0) {
                            echo $echodata;
                        }else {
                            echo "<p style='color:green;font-size:12px;'>No subjects to populate</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['getsubjectsclass'])) {
            $subid = $_GET['subids'];
            $select = "SELECT `classes_taught`,`subject_name` FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subid);
            $stmt->execute();
            $result = $stmt->get_result();
            $classl = "";
            if ($result) {
                $subanem = "";
                if ($row = $result->fetch_assoc()) {
                    $classl = $row['classes_taught'];
                    $subanem = $row['subject_name'];
                }

                $data = "<p id='subnameholder' class='hide'>".$subanem."</p><div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                if (strlen($classl)>0){
                    $arr = explode(",",$classl);
                    for ($i=0; $i < count($arr); $i++) {
                        $datas = "Class ".$arr[$i];
                        if (strlen($arr[$i])>1) {
                            $datas = $arr[$i];
                        }
                        $data.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $data.="<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='cheksd".$arr[$i]."'>".$datas."</label>";
                        $data.="<input class='checkclassess' type='checkbox' value='".$arr[$i]."' id='cheksd".$arr[$i]."'>";
                        $data.="</div>";
                    }
                }
                $data.="</div>";
                echo $data;
            }else{
                echo "<p>No classes selected yet</p>";
            }
        }elseif (isset($_GET['setTeacherSubjects'])) {
            $subjectid = $_GET['subdidds'];
            $teacherid = $_GET['teacheridds'];
            $classesselecetd = $_GET['selectedclasses'];
            $select = "SELECT `subject_name` , `teachers_id`  FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subjectid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $daroselection = explode(",",$classesselecetd);
                $daropresent = "";
                $subinformation = "";
                if ($row = $result->fetch_assoc()) {
                    $subinformation = $row['teachers_id'];
                }
                if (strlen($subinformation) > 2) {
                    //split the data
                    $splitinform = explode("|",$subinformation);
                    for ($t=0; $t < count($splitinform); $t++) {
                    }
                    //alter the details if a class is found to be one of the selected
                    for ($index=0; $index < count($splitinform); $index++) { 
                        $class = explode(":",substr($splitinform[$index],1,strlen($splitinform[$index])-2))[1];
                        $trid = explode(":",substr($splitinform[$index],1,strlen($splitinform[$index])-2))[0];
                        //loop inside the selected class to check if any is selected
                        for ($ind=0; $ind < count($daroselection); $ind++) { 
                            if ($daroselection[$ind] == $class) {
                                $splitinform[$index] = "(".$teacherid.":".$class.")";
                                $daropresent.=$class.",";
                            }
                        }
                    }
                    if (strlen($daropresent) > 1) {
                        $daropresent = substr($daropresent,0,strlen($daropresent)-1);
                    }
                    for ($t=0; $t < count($splitinform); $t++) {
                    }
                    //after change if there are other classes we need to add them to the equation
                    //split daropresent to show all classes that were already present
                    $finaloutput = "";                    
                    //add the changed subject details
                    $presentclass = "";
                    for ($t=0; $t < count($splitinform); $t++) {
                        $presentclass.=explode(":",substr($splitinform[$t],1,strlen($splitinform[$t])-2))[1].",";
                        $finaloutput.= $splitinform[$t]."|";
                    }
                    $presentclass = substr($presentclass,0,strlen($presentclass)-1);
                    $pressplit = explode(",",$presentclass);
                    $daroselection = explode(",",$classesselecetd);
                    for ($zx=0; $zx < count($daroselection); $zx++) { 
                        $onecla = $daroselection[$zx];
                        $present = 0;
                        for ($cd=0; $cd < count($pressplit); $cd++) { 
                            if ($onecla == $pressplit[$cd]) {
                                $present = 1;
                            }
                        }
                        if ($present == 0) {
                            $finaloutput.="(".$teacherid.":".$onecla.")|";
                        }
                    }
                    //format the final outpput
                    $finaloutput = substr($finaloutput,0,strlen($finaloutput)-1);
                    $update = "UPDATE `table_subject` set `teachers_id` = ? WHERE `subject_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$finaloutput,$subjectid);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:12px;'>Subject added successfully</p>";
                    }else {
                        echo "<p style='color:red;font-size:12px;'>Subject added successfully</p>";
                    }
                }else {
                    $finaloutput = "";
                    $daroselection = explode(",",$classesselecetd);
                    for ($zx=0; $zx < count($daroselection); $zx++) { 
                        $onecla = $daroselection[$zx];
                        $present = 0;
                        if ($present == 0) {
                            $finaloutput.="(".$teacherid.":".$onecla.")|";
                        }
                    }
                    //format the final outpput
                    $finaloutput = substr($finaloutput,0,strlen($finaloutput)-1);
                    $update = "UPDATE `table_subject` set `teachers_id` = ? WHERE `subject_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("ss",$finaloutput,$subjectid);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:12px;'>Subject added successfully</p>";
                    }else {
                        echo "<p style='color:red;font-size:12px;'>Subject added successfully</p>";
                    }
                }
            }
        }elseif (isset($_GET['retrievsubjectlist'])) {
            $select = "SELECT `subject_id`, `classes_taught`,`subject_name` FROM `table_subject` WHERE `sub_activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();            
            if ($result) {
                $dcvx = 1;
                $echodata = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs1' id='selesub'>";
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $subid = $row['subject_id'];
                    $subjectname = $row['subject_name'];
                    $echodata .="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:11px;'  for='mysub".$subid."'>".$dcvx.".  ".$subjectname."</label>
                            <input class='mysubjects' type='checkbox' value='".$subjectname."|".$subid."'  id='mysub".$subid."'></div>";
                    $xs++; 
                    $dcvx++;                           
                }
                $echodata .= "</div>";
                if ($xs>0) {
                    echo $echodata;
                }else {
                    echo "<p style='color:green;font-size:12px;'>No subjects to populate</p>";
                }
            }
        }elseif (isset($_GET['getClassesWithSubject'])) {
            if (strlen(trim($_GET['getClassesWithSubject']))>1) {
                $subjectid = substr(trim($_GET['getClassesWithSubject']),1,strlen(trim($_GET['getClassesWithSubject']))-2);
                $splitids = explode(",",$subjectid);
                $select = "SELECT `classes_taught` FROM `table_subject` WHERE `subject_id` = ? AND `sub_activated` = 1";
                $stmt = $conn2->prepare($select);
                $classlist = "";
                $newclasslist = [];
                for ($ind=0; $ind < count($splitids); $ind++) { 
                    $stmt->bind_param("s",$splitids[$ind]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if($row = $result->fetch_assoc()) {
                            $daro = $row['classes_taught'];
                            $splitdaros = explode(",",$daro);
                            for ($xs=0; $xs < count($splitdaros); $xs++) { 
                                $oneclass = $splitdaros[$xs];
                                $present = checkPresnt($newclasslist,$oneclass);
                                if ($present == 0) {
                                    array_push($newclasslist,$oneclass);
                                    $classlist.=$oneclass.",";
                                }
                            }
                        }
                    }
                }
                $classlist = substr($classlist,0,strlen($classlist)-1);
                $classlisted = explode(",",$classlist);
                $datatoecho = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='sel'>";
                for ($indexed=0; $indexed < count($classlisted); $indexed++) { 
                    $datas = "Class ".$classlisted[$indexed];
                    if (strlen($classlisted[$indexed])>1) {
                        $datas = $classlisted[$indexed];
                    }
                    $datatoecho.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                    $datatoecho.="<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='clded".$classlisted[$indexed]."'>".$datas."</label>";
                    $datatoecho.="<input class='subjectcls' type='checkbox' name='clded".$classlisted[$indexed]."' id='clded".$classlisted[$indexed]."'>";
                    $datatoecho.="</div>";
                }
                $datatoecho."</div>";
                echo $datatoecho;
            }
        }elseif (isset($_GET['registerExams'])) {
            $examname = $_GET['examname'];
            $examstartdate = $_GET['examstartdate'];
            $examenddate = $_GET['examenddate'];
            $subjects = $_GET['subjects'];
            $classes = $_GET['classes'];
            $curriculum = $_GET['curriculum'];
            $targetms = $_GET['targetms'];
            $students_sitting = getStudSitting($conn2,$classes);
            $classes_sitting = json_encode($students_sitting);
            $insert = "INSERT INTO `exams_tbl` (`exams_name`,`curriculum`,`class_sitting`,`start_date`,`end_date`,`subject_done`,`target_mean_score`,`deleted`,`students_sitting`) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $deleted = 0;
            $stmt->bind_param("sssssssss",$examname,$curriculum,$classes,$examstartdate,$examenddate,$subjects,$targetms,$deleted,$classes_sitting);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:13px; margin-top:10px;'>Exams added successfuly</p>";
            }else {
                echo "<p style='color:red;font-size:13px;margin-top:10px;'>An error occured</p>";
            }
        }elseif (isset($_GET['getExamination'])) {
            $stmt = false;
            if ($_GET['getExamination'] == "allactive") {
                $select = "SELECT `exams_id`,`exams_name`,`start_date`,`end_date` FROM `exams_tbl` WHERE `end_date` >= ? AND `deleted` = 0";
                $stmt = $conn2->prepare($select);
                $date = date("Y-m-d");
                $stmt->bind_param("s",$date);
                $stmt->execute();
            }elseif ($_GET['getExamination'] == "byname") {
                $select = "SELECT `exams_id`,`exams_name`,`start_date`,`end_date` FROM `exams_tbl` WHERE `exams_name` LIKE ? AND deleted = 0";
                $name = "%".$_GET['subjectnames']."%";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$name);
                $stmt->execute();
            }elseif ($_GET['getExamination'] == "byperiod") {
                $select = "SELECT `exams_id`,`exams_name`,`start_date`,`end_date` FROM `exams_tbl` WHERE `start_date` BETWEEN ? AND ? OR `end_date` BETWEEN ? AND ?";
                $sdate = $_GET['sdate'];
                $edate = $_GET['enddate'];
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ssss",$sdate,$edate,$sdate,$edate);
                $stmt->execute();
            }elseif ($_GET['getExamination'] == "bystatus") {
                $select = "SELECT `exams_id`,`exams_name`,`start_date`,`end_date` FROM `exams_tbl` ";
                $datetoday = date("Y-m-d");
                if ($_GET['status'] == "completed") {
                    $select.=" WHERE `end_date` < ? AND `deleted` = 0 ";
                }elseif ($_GET['status'] == "incompleted") {
                    $select.=" WHERE `end_date` >= ? AND `deleted` = 0 ";
                }
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("s",$datetoday);
                $stmt->execute();
            }elseif ($_GET['getExamination'] == "onetermexams") {
                //get term we are
                $datetoday = date("Y-m-d");
                $select = "SELECT `start_time`,`end_time` FROM `academic_calendar` WHERE `start_time` <= ? AND `end_time` >= ?";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$datetoday,$datetoday);
                $stmt->execute();
                $res = $stmt->get_result();
                $starttime = "";
                $endtime = "";
                if ($res) {
                    if ($row = $res->fetch_assoc()) {
                        $starttime = $row['start_time'];
                        $endtime = $row['end_time'];
                    }
                    //get the exams between that period
                    $select = "SELECT `exams_id`,`exams_name`,`start_date`,`end_date` FROM `exams_tbl` WHERE (`start_date` >= ? AND `start_date` <= ?) OR `end_date` > ? ";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("sss",$starttime,$endtime,$starttime);
                    $stmt->execute();
                    echo "<p style='text-align:center;'></p>";
                }
            }
            if ($stmt) {
                $results = $stmt->get_result();
                if ($results) {
                    $datatoecho = "<h5 class='text-center mt-2'><b>Examination Table</b> <img class='hide' id='delete_exams_loaders' src='images/ajax_clock_small.gif'></h5><div class='table_holders'><table class='table'>
                                    <tr>
                                        <th>#</th>
                                        <th>Exam name</th>
                                        <th>Status</th>
                                        <th>Start date</th>
                                        <th>End date</th>
                                        <th>Option</th>
                                    </tr>";
                                    $xs = 0;
                    while ($row = $results->fetch_assoc()) {
                        $enddate =  $row['end_date'];
                        $startdate = $row['start_date'];

                        $xs++;
                        $startdaten = date_create($startdate);
                        $enddaten = date_create($enddate);
                        $todaysdate = date_create(date("Y-m-d"));
                        $active = "<p style='color:green;font-size:13px;'>Closed!</p>";
                        if ($enddaten >= $todaysdate) {
                            $active = "<p style='color:rgb(99, 36, 36);font-size:13px;'>On-going</p>";
                            if($startdaten > $todaysdate){
                                $active = "<p style='color:rgb(3, 129, 179);font-size:13px;'>Not-Started</p>";
                            }
                        }
                        $startingday = $row['start_date'];
                        $endingday = $row['end_date'];
                        $datatoecho.="<tr>
                                        <td>".$xs."</td>
                                        <td id='exams_names_edit".$row['exams_id']."' >".$row['exams_name']."</td>
                                        <td>".$active."</td>
                                        <td>".date("D dS M, Y",strtotime($startingday))."</td>
                                        <td>".date("D dS M, Y",strtotime($endingday))."</td>
                                        <td><span type='button' style='font-size:12px;' class='viewExams link mx-1' id='examview".$row['exams_id']."' ><i class ='fa fa-pen-fancy'></i> Edit</span>  <span type='button' style='font-size:12px;' id='prints_exams".$row['exams_id']."' class='link prints_exams mx-1'><i class ='fa fa-print'></i> Print</span> <span type='button' style='font-size:12px;' id='view_exam_result".$row['exams_id']."' class='link view_exam_result mx-1'><i class ='fa fa-eye'></i> View</span><span type='button' style='font-size:12px;' id='delete_exams_".$row['exams_id']."' class='link delete_exams_ mx-1'><i class ='fa fa-trash'></i> Delete</span></td>
                                    </tr>";
                    }
                    $datatoecho.="</table></div>";
                    if ($xs > 0) {
                        echo $datatoecho;
                    }else {
                        echo "<div class='displaydata'>
                                <img class='' src='images/error.png'>
                                <p class='' >No records found! </p>
                            </div>";
                        //echo "<p style='color:red;font-size:12px;text-align:center;'>No results to dsplay!</p>";
                    }
                }
            }else {
                echo "Nothing";
            }
        }elseif (isset($_GET['getexams_classes'])) {
            $exam_id = $_GET['getexams_classes'];
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '".$exam_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "";
            if ($result) {
                $data_to_display.="<select required name='classes_for_exams' id='classes_for_exams' class='form-control'><option value='' hidden>Select option</option>";
                $counter  = 0;
                if ($row = $result->fetch_assoc()) {
                    $data = strlen($row['class_sitting']) > 0 ? explode(",",substr($row['class_sitting'],1,(strlen($row['class_sitting'])-2))) : [];
                    for ($ind=0; $ind < count($data); $ind++) { 
                        $data_to_display.="<option value='".$data[$ind]."'>".className($data[$ind])."</option>";
                        $counter++;
                    }
                    $data_to_display.="</select>";
                }
                if ($counter > 0) {
                    echo $data_to_display;
                }else{
                    echo "<p class='text-danger'>No class present to display!</p>";
                }
            }
        }elseif (isset($_GET['getExamsSubjects'])) {
            $examid = $_GET['getExamsSubjects'];
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $results = $stmt->get_result();
            if ($results) {
                if ($row = $results->fetch_assoc()) {
                    $today = date("Ymd");
                    $end_date = date("Ymd",strtotime($row['end_date']));
                    $allow = $end_date >= $today ? "" :"disabled";
                    $subjectdone = substr($row['subject_done'],1,strlen($row['subject_done'])-2);
                    if (strlen($subjectdone) > 0){
                        $subjectidsplit = explode(",",$subjectdone);
                        $subjectnames = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        for ($vin=0; $vin < count($subjectidsplit); $vin++) { 
                            $subjectjina = getSubjectName($subjectidsplit[$vin],$conn2);
                            $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                                $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>".$subjectjina."</label>";
                                if ($end_date >= $today) {
                                    $subjectnames.="<p title='Click to remove this subject' class='fungasubjects' id='funzo".$subjectidsplit[$vin]."'>&times</p>";
                                }
                            $subjectnames.="</div>";
                        }
                        $subjectnames.="</div>";
                        echo $subjectnames;    
                    }else {
                        $subjectnames = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>No subjects present</label>";
                        $subjectnames.="</div>";
                        $subjectnames.="</div>";
                        echo $subjectnames;
                    }
                }
            }
        }elseif (isset($_GET['getExamsClasses'])) {
            $examid = $_GET['getExamsClasses'];
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $results = $stmt->get_result();
            if ($results) {
                if ($row = $results->fetch_assoc()) {
                    if (strlen($row['class_sitting']) > 2) {
                        $today = date("Ymd");
                        $end_date = date("Ymd",strtotime($row['end_date']));
                        $classiting = substr($row['class_sitting'],1,strlen($row['class_sitting'])-2);
                        $classitingsplit = explode(",",$classiting);
                        $subjectnames = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        for ($i=0; $i < count($classitingsplit); $i++) { 
                            $datas = "Class ".$classitingsplit[$i];
                            if (strlen($classitingsplit[$i])>1) {
                                $datas = $classitingsplit[$i];
                            }                        
                            $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                                $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='classid".$classitingsplit[$i]."'>".$datas."</label>";
                                if ($end_date >= $today) {
                                    $subjectnames.="<p title='Click to remove this subject' class='toasubjects' id='toa".$classitingsplit[$i]."'>&times</p>";
                                }
                            $subjectnames.="</div>";                        
                        }
                        $subjectnames.="</div>";
                        echo $subjectnames;
                    }else {
                        $subjectnames = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>No classes present</label>";
                        $subjectnames.="</div>";
                        $subjectnames.="</div>";
                        echo $subjectnames;
                    }
                }else {
                    $subjectnames = "<div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                    $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                    $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>No classes present</label>";
                    $subjectnames.="</div>";
                    $subjectnames.="</div>";
                    echo $subjectnames;
                }
            }
        }elseif (isset($_GET['removeSubject'])) {
            $subjectid = $_GET['removeSubject'];
            echo "Removing...<br>";
            $examid = $_GET['examinationId'];
            $select = "SELECT `subject_done`, `class_sitting` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res) {
                $subjectsdone = "";
                $classatempt = "";
                if ($row = $res->fetch_assoc()) {
                    $subjectsdone = $row['subject_done'];
                    $classatempt = $row['class_sitting'];
                }
                if (strlen($subjectsdone) > 2) {
                    $subjectsdone = substr($subjectsdone,1,strlen($subjectsdone)-2);
                    $splitsub = explode(",",$subjectsdone);
                    for ($i=0; $i < count($splitsub); $i++) { 
                        if (trim($splitsub[$i]) == $subjectid) {
                            $splitsub[$i] = "";
                        }
                    }
                    $newsubjectlist = "(";
                    for ($ind=0; $ind < count($splitsub); $ind++) { 
                        if (strlen($splitsub[$ind]) > 0) {
                            $newsubjectlist.=$splitsub[$ind].",";
                        }
                    }
                    if (strlen($newsubjectlist) > 1) {
                        $newsubjectlist = substr($newsubjectlist,0,strlen($newsubjectlist)-1);
                        $newsubjectlist.=")";                        
                    }else {
                        $newsubjectlist = "";
                    }
                    //get the classes that are available
                    $newsublist = substr($newsubjectlist,1,strlen($newsubjectlist)-2);
                    $existingsubs = [];
                    if (strlen($newsublist) > 0) {
                        $explodesub = explode(",",$newsublist);
                        for ($xe=0; $xe < count($explodesub); $xe++) { 
                            $classes = getSubjectClasses($explodesub[$xe],$conn2);
                            if ($classes != "Null") {
                                //split the classes
                                $explodedclass = explode(",",$classes);
                                for ($ed=0; $ed < count($explodedclass); $ed++) { 
                                    $present = checkPresnt($existingsubs,trim($explodedclass[$ed]));
                                    if ($present == 0) {
                                        array_push($existingsubs,$explodedclass[$ed]);
                                    }
                                }
                            }
                        }
                    }
                    //remove the classes that the subjects are not offered
                    $classatempt = substr($classatempt,1,strlen($classatempt)-2);
                    $classatemptsplit = explode(",",$classatempt);
                    $look = "";
                    for ($eds=0; $eds < count($existingsubs); $eds++) { 
                        $cpresent = checkPresnt($classatemptsplit,$existingsubs[$eds]);
                        if ($cpresent == 1) {                                        
                            $look.=$existingsubs[$eds].",";
                        }
                    }
                    if (strlen($look) > 1) {
                        $look = "(".substr($look,0,strlen($look)-1).")";
                    }
                    //update the new class list
                    $update = "UPDATE `exams_tbl` SET `subject_done` = ?, `class_sitting` = ? WHERE `exams_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("sss",$newsubjectlist,$look,$examid);
                    $stmt->execute();
                }
            }
        }elseif (isset($_GET['getnewsubjectdata'])) {
            $examsid = $_GET['getnewsubjectdata'];
            $select = "SELECT `subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examsid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $subjectlist = substr($row['subject_done'],1,strlen($row['subject_done'])-2);
                    $splitsubjects = explode(",",$subjectlist);
                    $select = "SELECT `subject_id`,`subject_name` FROM `table_subject` WHERE `sub_activated` = 1";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        $subjectnames = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        $exs = 1;
                        while ($row = $result->fetch_assoc()) {
                            $present = checkPresnt($splitsubjects,$row['subject_id']);
                            if ($present == 0) {
                                $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                                $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='add".$row['subject_id']."'>".$exs.". ".$row['subject_name']."</label>";
                                $subjectnames.="<input type='checkbox' class='addsubjects hide' value = '".$row['subject_id']."|".$row['subject_name']."'  id='add".$row['subject_id']."'>";
                                $subjectnames.="</div>";
                                $exs++;
                            }
                        }
                        $subjectnames.="</div>";
                        if ($exs > 0) {
                            echo $subjectnames;
                        }else {
                            $subjectnames = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                            $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                            $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>All subjects are selected</label>";
                            $subjectnames.="</div>";
                            $subjectnames.="</div>";
                            echo $subjectnames;
                        }
                    }
                }
            }
        }elseif (isset($_GET['getAddsubjectClass'])) {
            $subject_id = $_GET['getAddsubjectClass'];
            $select = "SELECT `classes_taught` FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subject_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $classes = substr($row['classes_taught'],0,strlen($row['classes_taught']));
                    $explodeclass = explode(",",$classes);
                    $cbc = 0;
                    $subjectnames = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                    for ($ind=0; $ind < count($explodeclass); $ind++) {
                        $datas = "Class ".$explodeclass[$ind];
                        if (strlen($explodeclass[$ind])>1) {
                            $datas = $explodeclass[$ind];
                        }  
                        $cbc++;
                        $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='mdach".$explodeclass[$ind]."'>".$datas."</label>";
                        $subjectnames.="<input type='checkbox' class='selectclasseshere' value = '".$explodeclass[$ind]."'  id='mdach".$explodeclass[$ind]."'>";
                        $subjectnames.="</div>";
                    }
                    $subjectnames.="</div>";
                    if ($cbc > 0) {
                        echo $subjectnames;
                    }else {
                        $subjectnames = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                        $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='abc'>No classes present</label>";
                        $subjectnames.="</div>";
                        $subjectnames.="</div>";
                        echo $subjectnames;
                    }
                }
            }
        }elseif (isset($_GET['subject_id'])) {
            $sub_id = $_GET['subject_id'];
            $class_sel = $_GET['class_selected'];
            $exam_id = $_GET['exam_id'];
            //retrieve the subject ids and the classes sitting
            $select = "SELECT `class_sitting`,`subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$exam_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $class_sitting = "";
                $subjects_done = "";
                if ($row = $result->fetch_assoc()) {
                    $class_sitting = $row['class_sitting'];
                    $subjects_done = $row['subject_done'];
                    echo $class_sitting."<br>";
                    //start with class
                    if (strlen($class_sitting) > 2 ) {
                        $class_sitting = substr($row['class_sitting'],1,strlen($row['class_sitting'])-2);
                        //split the class into array
                        $split_class = explode(",",$class_sitting);
                        //check if the classes I do have are present
                        //split the classes
                        $splitNew_classes = explode(",",$class_sel);
                        for ($i=0; $i < count($splitNew_classes); $i++) { 
                            $present = checkPresnt($split_class,$splitNew_classes[$i]);
                            if ($present == 0) {
                                //if not present add it to the class list
                                $class_sitting.=",".$splitNew_classes[$i];
                            }
                        }
                        $class_sitting = "(".$class_sitting.")";
                    }else {
                        $class_sitting = "(".$class_sel.")";
                    }

                    //subjects
                    if (strlen($subjects_done) > 2) {
                        $subjects_done = substr($row['subject_done'],1,strlen($row['subject_done'])-2);
                        //split the subjects
                        $split_subjects = explode(",",$subjects_done);
                        //check if the subjects i have is present
                        $present = checkPresnt($split_subjects,$sub_id);
                        if ($present == 0) {
                            $subjects_done.=",".$sub_id;
                        }
                        $subjects_done = "(".$subjects_done.")";
                    }else {
                        $subjects_done = "(".$sub_id.")";
                    }
                    $update = "UPDATE `exams_tbl` SET `class_sitting` = ? , `subject_done` = ? WHERE `exams_id` = ? ";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("sss",$class_sitting,$subjects_done,$exam_id);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:13px;'>Exam information updated successfully!</p>";
                    }else {
                        echo "<p style='color:red;font-size:13px;'>Sorry!<br>Updating wasn`t successful!</p>";
                    }
                }
            }
        }elseif (isset($_GET['remove_class'])) {
            $clas_s = $_GET['remove_class'];
            $exam_s_id = $_GET['exam_s_id'];
            $select = "SELECT `class_sitting` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$exam_s_id);
            $stmt->execute();
            $results = $stmt->get_result();
            if ($results) {
                if ($row = $results->fetch_assoc()) {
                    $class_sitting = substr($row['class_sitting'],1,strlen($row['class_sitting'])-2);
                    $splitclas = explode(",",$class_sitting);
                    for ($x=0; $x < count($splitclas); $x++) { 
                        if($clas_s == $splitclas[$x]){
                            $splitclas[$x] = "";
                            break;
                        }
                    }
                    $class_sitting = "";
                    for ($dvs=0; $dvs < count($splitclas); $dvs++) { 
                        if (strlen($splitclas[$dvs]) > 0) {
                            $class_sitting.=$splitclas[$dvs].",";
                        }
                    }
                    $class_sitting = "(".substr($class_sitting,0,strlen($class_sitting)-1).")";
                    $students_sitting = getStudSitting($conn2,$class_sitting);
                    $my_students = json_encode($students_sitting);
                    $update = "UPDATE `exams_tbl` SET `class_sitting` = ?, `students_sitting` = ? WHERE `exams_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("sss",$class_sitting,$my_students,$exam_s_id);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:12px;'>Class removed successfully!</p>";
                    }else {
                        echo "<p style='color:green;font-size:12px;'>Error occured please try again later!</p>";
                    }
                }
            }
        }elseif (isset($_GET['exam_id_s'])) {
            $examid = $_GET['exam_id_s'];
            $select = "SELECT `subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $dar_list = [];
                    $subject_list = rBkts($row['subject_done']);
                    $split_subjects = explode(",",$subject_list);
                    for ($inted=0; $inted < count($split_subjects); $inted++) { 
                        $subjectClass = getSubjectClassArr($split_subjects[$inted],$conn2 );
                        for ($ind=0; $ind < count($subjectClass); $ind++) { 
                            $present = checkPresnt($dar_list,$subjectClass[$ind]);
                            if ($present == 0) {
                                array_push($dar_list,$subjectClass[$ind]);
                            }
                        }
                    }
                    $c_lasslists = "";
                    for ($ex=0; $ex < count($dar_list); $ex++) { 
                        $c_lasslists.=$dar_list[$ex].",";
                    }
                    $c_lasslists = substr($c_lasslists,0,strlen($c_lasslists)-1);
                    $new_classlist = explode(",",$c_lasslists);

                    $subjectnames = "<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>";
                    $counter = 0;
                    for ($do=0; $do < count($new_classlist); $do++) {
                        $datas = "Class ".$new_classlist[$do];
                        if (strlen($new_classlist[$do])>1) {
                            $datas = $new_classlist[$do];
                        }
                        $subjectnames.="<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>";
                        $subjectnames.="<label style='margin-right:5px;cursor:pointer;font-size:13px;' for='a_dd".$new_classlist[$do]."'>".$datas."</label>";
                        $subjectnames.="<input type='checkbox' class='add_class_check' value = '".$new_classlist[$do]."'  id='a_dd".$new_classlist[$do]."'>";
                        $subjectnames.="</div>";
                        $counter++;
                    }
                    $subjectnames.="</div>";
                    if ($counter > 0) {
                        echo $subjectnames;
                    }else {
                        
                    }
                }
            }
        }elseif (isset($_GET['add_classes'])) {
            $class_list = $_GET['add_classes'];
            $my_class = explode(",",$class_list);
            $exam_id = $_GET['ex_am_id'];
            //retrieve the class lists from the exam and add the class to the list if its not present
            $select = "SELECT `class_sitting` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$exam_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $class_sitting = "";
                    if(strlen($row['class_sitting']) > 2){
                        echo "in";
                        $class_sitting = rBkts($row['class_sitting']);
                        //split the class list into array
                        $exp_lode_class = explode(",",$class_sitting);
                        for ($js=0; $js < count($my_class); $js++) { 
                            $present = checkPresnt($exp_lode_class,$my_class[$js]);
                            if ($present == 0) {
                                $class_sitting.=",".$my_class[$js];
                            }
                        }
                        $class_sitting = "(".$class_sitting.")";
                    }else {
                        $class_sitting = "(".$class_list.")";
                    }
                    $students_sitting = getStudSitting($conn2,$class_sitting);
                    $my_students = json_encode($students_sitting);
                    $update = "UPDATE `exams_tbl` SET `class_sitting` = ?,`students_sitting` = ? WHERE `exams_id` = ?";
                    $stmt = $conn2->prepare($update);
                    $stmt->bind_param("sss",$class_sitting,$my_students,$exam_id);
                    if($stmt->execute()){
                        echo "<p style='color:green;font-size:13px;'>Classes added successfully!</p>";
                    }else {
                        echo "<p style='color:red;font-size:13px;'>An error occured<br>Please try again!</p>";
                    }
                }
            }
        }elseif (isset($_GET['get_Exam_Information'])) {
            $string_data = "Null";
            $exam_ids = $_GET['get_Exam_Information'];
            $select = "SELECT `exams_name`,`curriculum`,`end_date`,`target_mean_score`,`deleted` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$exam_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $string_data = "";
                if ($row = $result->fetch_assoc()) {
                    $string_data = $row['exams_name'].",".$row['curriculum'].",".$row['end_date'].",".$row['target_mean_score'].",".$row['deleted'];
                }
                if (strlen($string_data) > 0) {
                    echo $string_data;
                }else {
                    "Null";
                }
            }
        }elseif (isset($_GET['update_exams'])) {
            $exam_name = $_GET['exam_name'];
            $exam_end_date = $_GET['exam_enddate'];
            $exam_curriculum = $_GET['exam_curriculum'];
            $target_ms = $_GET['target_ms'];
            $exam_ids = $_GET['exam_i_d'];
            $select = "UPDATE `exams_tbl` SET `exams_name` = ? , `end_date` = ? ,`target_mean_score` = ? , `curriculum` = ? WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("sssss",$exam_name,$exam_end_date,$target_ms,$exam_curriculum,$exam_ids);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:13px;'>Update was done successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:13px;'>An error has occured!</p>";
            }
        }elseif (isset($_GET['get_exam_available'])) {
            $select = "SELECT `exams_id`,`exams_name` FROM `exams_tbl` WHERE `end_date` >= ?";
            $date = date("Y-m-d");
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $string_display = "<label class='form-control-label' for='exam_list'>Select exam: <br></label><select class='form-control' name='exam_list' id='exam_list'>
                                        <option value='' hidden>Select an option..</option>";
                                    $xd = 0;
                while ($row = $result->fetch_assoc()) {
                    $xd++;
                    $exam_name = $row['exams_name'];
                    $exam_id = $row['exams_id'];
                    $string_display.="<option value='".$exam_id."'>".$exam_name."</option>";
                }
                $string_display.="</select>";

                if ($xd > 0) {
                    echo $string_display;
                }else {
                    echo "<p style='color:red;font-size:13px;font-weight:500;'>Active exams are not available at the moment!</p>";
                }
            }
        }elseif (isset($_GET['get_exam_class'])) {
            $examid = $_GET['get_exam_class'];
            $select = "SELECT `exams_id`, `subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $subject_done = $row['subject_done'];
                    $subject_split = [];
                    $tr_subjects2 = "";
                    if (strlen($subject_done) > 0) {
                        $subject_done = substr($subject_done,1,strlen($subject_done)-2);
                        $subject_split = explode(",",$subject_done);
                    }

                    for ($indexes=0; $indexes < count($subject_split); $indexes++) {
                        $tr_subjects2 .= $subject_split[$indexes].",";
                    }
                    //select subjects the teacher teaches
                    $select = "SELECT `subject_id`,`subject_name` FROM `table_subject` WHERE `teachers_id` LIKE ?";
                    $user_id = "%(".$_SESSION['userids'].":%";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $tr_subjects = "";
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $tr_subjects.=$row['subject_id'].",";
                        }
                    }
                    if (strlen($tr_subjects)>0 || ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0")) {
                        $tr_subjects = ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0") ? substr($tr_subjects2,0,strlen($tr_subjects2)-1) : substr($tr_subjects,0,strlen($tr_subjects)-1);
                        $split_tr_sub = explode(",",$tr_subjects);
                        $string_display = "";
                        if (count($subject_split) > 0) {
                            $string_display = "<label class='form-control-label' for='sub_jectlists' >Select the subject: <br></label><select class='form-control' name='sub_jectlists' id='sub_jectlists'>
                            <option value='' hidden>Select an option..</option>";
                            $xr = 0;
                            sort($subject_split);
                            for ($xc=0; $xc < count($subject_split); $xc++) {
                                $present = checkPresnt($split_tr_sub,$subject_split[$xc]);
                                if ($present == 1 || ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0") ) {
                                    $sub_id = $subject_split[$xc];
                                    $xr++;
                                    $string_display.="<option value='".$sub_id."'>".getSubjectName($sub_id,$conn2)."</option>";
                                }
                            }
                            $string_display.="</select>";
                            // echo $_SESSION['authority'];
                            if ($xr > 0) {
                                echo $string_display;
                            }else {
                                echo "<p style='color:red;font-size:12px;'>No subjects you teach is examined!</p>";
                            }
                        }else {
                            echo "<p class = 'red_notice'>No classes or subjects you teach is examined!</p>";
                        }
                    }else{
                        echo "<p class = 'red_notice'>No classes or subjects you teach is examined!</p>";
                    }
                }else {
                    echo "<p class = 'red_notice'>No classes or subjects you teach is examined!</p>";
                }
            }else {
                echo "<p class = 'red_notice'>No classes or subjects you teach is examined!</p>";
            }
        }elseif (isset($_GET['subjects_id_ds'])) {
            $subject_ud = $_GET['subjects_id_ds'];
            $exam_ud = $_GET['exams_id_ids'];
            $select = "SELECT * FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subject_ud);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $subject_list = "";
                $classes_taught = "";
                if ($row = $result->fetch_assoc()) {
                    $subject_list = $row['teachers_id'];
                    $classes_taught = $row['classes_taught'];

                    // get the classes taugh
                    $all_classes = [];
                    if ($_SESSION['authority'] == 1 || $_SESSION['authority'] == 0){
                        // if the are an administrator get all classes present
                        $exam_classes = getExamClasses($conn2,$exam_ud);
                        $exploded_classes_taught = explode(",",$classes_taught);
                        
                        for ($index=0; $index < count($exploded_classes_taught); $index++) { 
                            if (checkPresnt($exam_classes,$exploded_classes_taught[$index])) {
                                array_push($all_classes,$exploded_classes_taught[$index]);
                            }
                        }

                        // all classes
                    }else{
                        // get classes that the teacher has taught
                        if (strlen($subject_list) > 0) {
                            $user_id = $_SESSION['userids'];

                            $split_class_tr = explode("|",$subject_list);
                            $my_classes = [];
                            for ($spl=0; $spl < count($split_class_tr); $spl++) { 
                                $subject_n_teacher = substr($split_class_tr[$spl], 1, strlen($split_class_tr[$spl]) - 2);
                                $in42 = explode(":",$subject_n_teacher);
                                $tr_id = $in42[0];
                                $sub_cl = $in42[1];
                                $exam_classes = getExamClasses($conn2,$exam_ud);
                                if ($tr_id == $user_id) {
                                    // array_push($my_classes,$sub_cl);
                                    if (checkPresnt($exam_classes,$sub_cl)) {
                                        array_push($my_classes,$sub_cl);
                                    }
                                }
                            }
                            $all_classes = $my_classes;
                        }
                        // echo json_encode($all_classes);
                    }

                    if (count($all_classes) > 0) {
                        $class_to_display = "<label class='form-control-label' for='cls_lists' >Select the class to record marks: <br></label><select class='form-control' name='cls_lists' id='cls_lists'>
                                        <option value='' hidden>Select an option..</option>";
                        for ($df=0; $df < count($all_classes); $df++) {
                            $datas = "Grade ".$all_classes[$df];
                            if (strlen($all_classes[$df])>1) {
                                $datas = $all_classes[$df];
                            }
                            $class_to_display.="<option value='".$all_classes[$df]."'>".$datas."</option>";
                        }
                        $class_to_display.="</select>";
                        echo $class_to_display;
                    }else {
                        echo "<p style='color:green;'>You dont teach any class of the selected subject!</p>";
                    }
                }
                
            
            }
        }elseif (isset($_GET['get_class_for_exams'])) {
            $class_to_display = $_GET['get_class_for_exams'];
            // echo $class_to_display." name in";
            $exam_ids = $_GET['exam__id'];
            $subject_id = $_GET['subject__id'];
            $curriculum = $_GET['grd_mode'];
            $select = "SELECT * FROM `student_data` WHERE `stud_class` = ? AND `deleted` = 0 AND `activated` = 1";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$class_to_display);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $datas = "Class ".$class_to_display;
                if (strlen($class_to_display)>1) {
                    $datas = $class_to_display;
                }
                $sub_max_marks = getSubMaxMarks($conn2,$subject_id);
                $subject_grades = getSubjectGrades($conn2,$subject_id);
                $grade_table = "";
                if (strlen($subject_grades) > 0) {
                    $mygrades = json_decode($subject_grades);
                    $grade_table .= "<h6 class='text-center'><b>Grade List</b></h6><table class='table'><tr><th>#</th><th>Grade</th><th>Range</th></tr>";
                    for ($i=0; $i < count($mygrades); $i++) {
                        $grade_table.= "<tr><td>".($i+1)."</td><td>".$mygrades[$i]->grade_name."</td><td>".$mygrades[$i]->max." - ".$mygrades[$i]->min."</td></tr>";
                    }
                    $grade_table.="</table><hr>";
                }
                if ($curriculum == "iPrimary") {
                    $grade_table = "<h6 class='text-center'><b>Grade List</b></h6><table class='table'><tr><th>#</th><th>Grade</th><th>Range</th></tr>";
                    $grade_table.= "<tr><td>1. </td><td>A* </td><td>100 - 91</td></tr>";
                    $grade_table.= "<tr><td>2. </td><td>A </td><td>90 - 81</td></tr>";
                    $grade_table.= "<tr><td>3. </td><td>B </td><td>80 - 71</td></tr>";
                    $grade_table.= "<tr><td>4. </td><td>C </td><td>70 - 61</td></tr>";
                    $grade_table.= "<tr><td>5. </td><td>D </td><td>60 - 51</td></tr>";
                    $grade_table.= "<tr><td>6. </td><td>E </td><td>50 - 41</td></tr>";
                    $grade_table.= "<tr><td>7. </td><td>F </td><td>40 - 31</td></tr>";
                    $grade_table.= "<tr><td>8. </td><td>U </td><td>30 - 0</td></tr>";
                    $grade_table.="</table><hr>";
                }

                if ($curriculum == "IGCSE") {
                    $grade_table = "<h6 class='text-center'><b>Grade List</b></h6><table class='table'><tr><th>#</th><th>Points</th><th>Range</th></tr>";
                    $grade_table.= "<tr><td>1. </td><td>9 </td><td>100 - 91</td></tr>";
                    $grade_table.= "<tr><td>2. </td><td>8 </td><td>90 - 81</td></tr>";
                    $grade_table.= "<tr><td>3. </td><td>7 </td><td>80 - 74</td></tr>";
                    $grade_table.= "<tr><td>4. </td><td>6 </td><td>73 - 68</td></tr>";
                    $grade_table.= "<tr><td>5. </td><td>5 </td><td>67 - 60</td></tr>";
                    $grade_table.= "<tr><td>6. </td><td>4 </td><td>59 - 54</td></tr>";
                    $grade_table.= "<tr><td>7. </td><td>3 </td><td>53 - 47</td></tr>";
                    $grade_table.= "<tr><td>8. </td><td>2 </td><td>46 - 40</td></tr>";
                    $grade_table.= "<tr><td>9. </td><td>1 </td><td>39 - 35</td></tr>";
                    $grade_table.= "<tr><td>10. </td><td>U </td><td>34 - 0</td></tr>";
                    $grade_table.="</table><hr>";
                }
                $cbc_marks = "";
                if ($curriculum == "cbc") {
                    $grade_table = "<h6 class='text-center'><b>Grade List</b></h6><table class='table'><tr><th>#</th><th>Grade</th><th>Acronym</th></tr>";
                    $grade_table.= "<tr><td>4. </td><td>Exceeding Expectation </td><td>E.E</td></tr>";
                    $grade_table.= "<tr><td>3. </td><td>Meeting Expectation </td><td>M.E</td></tr>";
                    $grade_table.= "<tr><td>2. </td><td>Aproaching Expectation </td><td>A.E</td></tr>";
                    $grade_table.= "<tr><td>1. </td><td>Below Expectation </td><td>B.E</td></tr>";
                    $grade_table.= "<tr><td>A </td><td>Absent </td><td>A</td></tr>";
                    $grade_table.="</table><hr>";
                    $cbc_marks = "<p>Subject Marks</p>";
                }
                // echo $mygrades[$i]->grade_name."<br>";
                $data_to_display = $grade_table."<input hidden id='grade_modes_holder' value='".$curriculum."'><h6 style='text-align:center;'>Exam : <span>".getExamName($exam_ids,$conn2)."</span> <span class='hide' id = 'exams_grades' >".$subject_grades."</span></h6>
                                    <h6 style='text-align:center;'>Subject : ".getSubjectName($subject_id,$conn2)."</h6>
                                    <p class='hide' id='max-marks-hold'>$sub_max_marks</p>
                                    <h6 style='text-align:center;'>Class : <span class='hide' id='class_siter'>".$class_to_display."</span>".$datas."</h6>
                                    <hr class='my-2'>
                                    <div class='row'>
                                    <div class = 'col-md-6'></div>
                                    <div class = 'col-md-6'>
                                        <input type='text' id='search_results_2' class ='form-control w-75' placeholder = 'Search students here...'>
                                    </div>
                                    </div>
                                    <div class='table_holders'>
                                        <div class='table_fill'>
                                        <div class='table_header'>
                                            <div class='td'>
                                                <p>No</p>
                                                <p>Student Name</p>
                                                ".$cbc_marks."
                                                <p>Subject Score</p>
                                                <p>Subject Grade</p>
                                                <p>Option</p>
                                            </div>
                                        </div><div class='table_body'>";
                                        $xd = 0;
                                        $absent = 0;
                                        $hesabu = 0;
                while ($row = $result->fetch_assoc()) {
                    $fname = $row['first_name'];
                    $hesabu++;
                    $adm_no = $row['adm_no'];
                    $second_name = $row['second_name'];
                    $stud_class = $row['stud_class'];
                    $subjects_attempting = isJson_report($row['subjects_attempting']) ? json_decode($row['subjects_attempting']) : [];
                    
                    // check if the subject is present in the list or it should be removed
                    if(count($subjects_attempting) > 0){
                        if (!checkPresnt($subjects_attempting,$subject_id)) {
                            continue;
                        }
                    }
                    //check if already recorded
                    $present = checkExamRecorde($conn2,$exam_ids,$subject_id,$adm_no);
                    if ($present == 0) {

                        $xd++;
                        $selection = "";
                        if ($curriculum == "844" || $curriculum == "IGCSE" || $curriculum == "iPrimary") {
                            $selection = "<input class='form-control w-100 mb-2 manual_grading' style='min-width:100px' type='number' name='marks_enter' min='0' max='100' id='input".$adm_no."' placeholder ='Enter Marks'>";
                        }elseif ($curriculum == "cbc") {
                            $selection = "
                                    <input class='form-control w-100 mb-2 manual_grades' style='min-width:100px' type='number' name='marks_enter' min='0' max='100' id='input_2".$adm_no."' placeholder ='Enter Marks'>
                                    <select class='selected_grade form-control mb-2' style='min-width:100px' name='input".$adm_no."' id='input".$adm_no."'>
                                        <option value='' hidden>Select..</option>
                                        <option value='4'>4</option>
                                        <option value='3'>3</option>
                                        <option value='2'>2</option>
                                        <option value='1'>1</option>
                                        <option value='A'>A</option>
                                    </select>";
                        }
                        
                        $data_to_display.="<div class='table_row py-0' id='table_data_entry".$adm_no."'>
                                            <div class='td'>
                                                <p class='my_adm_no' id='my_adm_no".$adm_no."'>".$xd.". <small>(".$adm_no.")</small> </p>
                                                <p class='my_students_names' style='font-size:12px;font-weight:600;'>".ucwords(strtolower($fname." ".$second_name))."</p>
                                                ".$selection."
                                                <div class='imagers hide' id='imagered".$adm_no."'>
                                                    <img src='images/load2.gif' alt='loading'>
                                                </div>
                                                <div class='imagers hide' id='imager2_e".$adm_no."'>
                                                    <img src='images/check.gif' alt='loading'>
                                                </div>
                                                <div class='imagers hide' id='imager3_e".$adm_no."'>
                                                    <img src='images/check2.jpg' alt='loading'>
                                                </div>
                                                <p class='hide' id='errhandler_".$adm_no."'></p>
                                                <p id='grade".$adm_no."'>-</p>
                                                <button type='button' class='save_marks_butns' id='savemarks".$adm_no."'>Save</button>
                                            </div>
                                        </div>";
                    }else {
                        $absent++;
                    }
                }
                $data_to_display.="</div></div></div>";
                if ($xd > 0) {
                    echo $data_to_display;
                }else {
                    if ($absent>0) {
                        echo "<p class='' style='color:green;text-align:center;margin-top:10px;font-size:12px; font-weight:600;' >All ".$datas." ".getSubjectName($subject_id,$conn2)." marks have been recorded! </p>";
                    }else {
                        echo "<div class='displaydata'>
                            <img class='' src='images/error.png'>
                            <p class='' >No members in the class selected! </p>
                        </div>";
                    }
                }
            }
        }elseif (isset($_GET['save_student_marks'])) {
            $subject_marks = $_GET['save_student_marks'];
            $subject_id = $_GET['subjectidds'];
            $examination_id = $_GET['examidds'];
            $subject_grade = $_GET['subject_grade'];
            $student_id = $_GET['student_ids'];
            $class_name = $_GET['class_name'];
            $grade_modes_holder = $_GET['grade_modes_holder'];
            $insert = "INSERT INTO `exam_record_tbl` (`exam_id`,`student_id`,`subject_id`,`exam_marks`,`exam_grade`,`filled_by`,`class name`,`grade_method`) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn2->prepare($insert);
            $userids = $_SESSION['userids'];
            $stmt->bind_param("ssssssss",$examination_id,$student_id,$subject_id,$subject_marks,$subject_grade,$userids,$class_name,$grade_modes_holder);
            $stmt->execute();
        }elseif (isset($_GET['show_terms'])) {
            $select = "SELECT `term`,`start_time`,`end_time` FROM `academic_calendar`WHERE 
                            (`term` = 'TERM_1') 
                            OR (`term` = 'TERM_2') 
                            OR (`term` = 'TERM_3');";
            $date = date("Y");
            $stmt = $conn2->prepare($select);
            // $stmt->bind_param("sss",$date,$date,$date);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res) {
                $string_to_show="<label class='form-control-label' for='term_selection'>Select term<br></label>
                                <select class='form-control' name='term_selection' id='term_selection'>
                                    <option value='' hidden>Select term</option>";
                                    $xs=0;
                while ($row = $res->fetch_assoc()) {
                    $xs++;
                    $term = $row['term'];
                    $start_time = $row['start_time'];
                    $end_time = $row['end_time'];
                    $string_to_show.="<option value='".$start_time."|".$end_time."'>".$term."</option>";
                }
                $string_to_show.="</select>";
                if ($xs > 0) {
                    echo $string_to_show;
                }else {
                    echo "<label class='form-control-label' for='term_selection'>Select term..<br></label>
                    <select class='form-control' name='term_selection' id='term_selection'>
                        <option value='' hidden>Select term</option>
                    </select>";
                }
            }
        }elseif (isset($_GET['get_exams_attempt'])) {
            $start_time = $_GET['time_start'];
            $end_time = $_GET['time_ends'];
            $select = "SELECT `exams_id`, `exams_name` FROM `exams_tbl` WHERE `start_date` BETWEEN ? AND ? or `end_date` BETWEEN ? AND ? and deleted = 0";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ssss",$start_time,$end_time,$start_time,$end_time);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $exam_list_interm = "<label class='form-control-label' for='exam_selected'>Select exam..<br></label>
                                    <select class='form-control' name='exam_selected' id='exam_selected'>
                                        <option value='' hidden>Select exam</option>";
                                        $xs=0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $exam_list_interm.="<option value='exam_id_".$row['exams_id']."'>".$row['exams_name']."</option>";
                }
                $exam_list_interm.="</select>";
                if ($xs > 0) {
                    echo $exam_list_interm;
                }else {
                    echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No exam has been done in the selected term!</p>";
                }
            }
        }elseif (isset($_GET['get_exam_subjects'])) {
            $examid = $_GET['get_exam_subjects'];
            $select = "SELECT `subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$examid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $subject_ids = $row['subject_done'];
                    if (strlen($subject_ids) >0 ) {
                        $subject_ids = rBkts($subject_ids);
                        //split the class into arrays of subject ids
                        $split_subject = explode(",",$subject_ids);
                        //string to hold the select and the label
                        $string_to_show = "<label class='form-control-label' for='subjects_done_mine'>Select subject..<br></label>
                                            <select class='form-control' name='subjects_done_mine' id='subjects_done_mine'>
                                            <option value='' hidden>Select subject</option>";
                                            $xs = 0;
                        for ($i=0; $i < count($split_subject); $i++) {
                            $xs++;
                            $string_to_show.="<option value='".$split_subject[$i]."'>".getSubjectName($split_subject[$i],$conn2)."</option>";
                        }
                        $string_to_show.="</select>";
                        if ($xs > 0) {
                            echo $string_to_show;
                        }else {
                            echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No subject has been done!</p>";
                        }
                    }
                }
            }
        }elseif (isset($_GET['get_subject_class'])) {
            $subject_id = $_GET['subject_exam_id'];
            $exam_id = $_GET['exam_ids_sub'];
            $select = "SELECT `classes_taught` FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$subject_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $split_sub_class = [];
            $split_sub_exams = [];
            if ($result) {
                if ($row =  $result->fetch_assoc()) {
                    $classes = $row['classes_taught'];
                    if (strlen($classes) > 0) {
                        $split_sub_class = explode(",",$classes);
                    }
                    $select = "SELECT `class_sitting` FROM `exams_tbl` WHERE `exams_id` = ?";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$exam_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $exam_subs = $row['class_sitting'];
                            if (strlen($classes) > 0) {
                                $split_sub_exams = explode(",",rBkts($exam_subs));
                            }
                            
                        }
                    }
                    $class_done_by_sub = "";
                    if (count($split_sub_exams) > 0) {
                        for ($index=0; $index < count($split_sub_exams); $index++) { 
                            // $present = checkPresnt($split_sub_exams,trim($split_sub_class[$index]));
                            $present = 1;
                            if ($present == 1) {
                                $class_done_by_sub.=$split_sub_exams[$index].",";
                            }
                        }
                    }
                    // echo json_encode($split_sub_exams);
                    $class_done_by_sub = substr($class_done_by_sub,0,strlen($class_done_by_sub)-1);
                    // $class_done_by_sub = substr($split_sub_exams,0,strlen($split_sub_exams)-1);
                    if (strlen($class_done_by_sub) > 0) {
                        $splitclasses = explode(",",$class_done_by_sub);
                        $string_to_show = "<label class='form-control-label' for='classes_sitting'>Select class..<br></label>
                                            <select class='form-control' name='classes_sitting' id='classes_sitting'>
                                            <option value='' hidden>Select class</option>";
                                            $xs = 0;
                        for ($ind=0; $ind < count($splitclasses); $ind++) {
                            $datas = "Class ".$splitclasses[$ind];
                            if (strlen($splitclasses[$ind])>1) {
                                $datas = $splitclasses[$ind];
                            }
                            $xs++;
                            $string_to_show.="<option value='".$splitclasses[$ind]."'>".$datas."</option>";
                        }
                        $string_to_show.="</select>";
                        // echo $string_to_show;
                        if ($xs > 0) {
                            echo $string_to_show;
                        }else {
                            echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No subject has been done!</p>";
                        }
                    }else{

                    }
                }
            }
        }elseif (isset($_GET['ex_am_ids'])) {
            $exam_id = $_GET['ex_am_ids'];
            $select = "SELECT `curriculum` FROM `exams_tbl` WHERE `exams_id` = '".$exam_id."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $curriculum = "Null";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $curriculum = $row['curriculum'];
                }
            }
            $sub_ject_ids = $_GET['sub_ject_ids'];
            // get subject maximum marks
            $select = "SELECT * FROM `table_subject` WHERE `subject_id` = ? ";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$sub_ject_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            $max_marks = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $max_marks = $row['max_marks'];
                }
            }

            $class_sit_ting = $_GET['class_sit_ting'];
            $select = "SELECT * FROM `exam_record_tbl` WHERE `subject_id` = ? AND `exam_id` = ? AND `class name` = ? ORDER BY `exam_marks` DESC";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("sss",$sub_ject_ids,$exam_id,$class_sit_ting);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $datas = "Grade ".$class_sit_ting;
                if (strlen($class_sit_ting)>1) {
                    $datas = $class_sit_ting;
                }
                //check if the teacher teaches that subject being viewed
                $tr_n_sub = "%(".$_SESSION['userids'].":".$class_sit_ting.")%";
                $select = "SELECT * FROM `table_subject` WHERE `teachers_id` LIKE ?  and `subject_id` = ? ";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$tr_n_sub,$sub_ject_ids);
                $stmt->execute();
                $stmt->store_result();
                $rnums = $stmt->num_rows;
                $change_options = "";

                //check if the exam end date was reached
                $exam_end = getExamDate($exam_id,$conn2);
                //create it into a date
                $exam_end = date_create($exam_end);
                $today = date_create(date("Y-m-d"));
                $subject_grades = getSubjectGrades($conn2,$sub_ject_ids);
                $grade_table = "";
                if (strlen($subject_grades) > 0) {
                    $mygrades = json_decode($subject_grades);
                    $grade_table .= "<h6 class='text-center'><b>Grade List</b></h6><table class='table'><tr><th>#</th><th>Grade</th><th>Range</th></tr>";
                    for ($i=0; $i < count($mygrades); $i++) {
                        $grade_table.= "<tr><td>".($i+1)."</td><td>".$mygrades[$i]->grade_name."</td><td>".$mygrades[$i]->max." - ".$mygrades[$i]->min."</td></tr>";
                    }
                    $grade_table.="</table><hr>";
                }
                $subject_name = getSubjectName($sub_ject_ids,$conn2);
                $data_to_display = $grade_table."<p class='hide' id='my_max_marks'>".$max_marks."</p><p class='hide' id='my_curriculum'>".$curriculum."</p><div id='in_titles'><h6 style='text-align:center;'>Results for : <span id='exam_name_record'>".getExamName($exam_id,$conn2)."</span> <span class='hide' id='exam_grades_editing'>".$subject_grades."</span></h6>
                                    <h6 style='text-align:center;'>Subject : <span id='subject_name_record'>".$subject_name."</h6>
                                    <h6 style='text-align:center;'>Class : <span class='hide' id='class_sit'>".$class_sit_ting."</span>".$datas."</h6></div>
                                    <hr class='my-2'>
                                    <div class='row'>
                                    <div class = 'col-md-6'></div>
                                    <div class = 'col-md-6'>
                                        <input type='text' id='search_results' class ='form-control w-75' placeholder = 'Search students here...'>
                                    </div>
                                    </div>
                                    <div class='table_holders'>
                                    <div class='table_fill'>
                                        <div class='table_header'>
                                            <div class='td'>
                                                <p>No</p>
                                                <p>Student Name</p>
                                                <p>Subject Score</p>
                                                <p>Subject Grade</p>
                                                <p>Option</p>
                                            </div>
                                        </div><div class='table_body'>";
                                        $xd=0;
                                        $marks = [];
                                        $studentid = [];
                while ($row = $result->fetch_assoc()) {
                    $xd++;
                    $resultid = $row['result_id'];
                    $exam_id = $row['exam_id'];
                    $student_id = $row['student_id'];
                    $subject_id = $row['subject_id'];
                    $exam_marks = $row['exam_marks'];
                    $exam_grade = $row['exam_grade'];
                    $filled_by = $row['filled_by'];
                    
                    if ($rnums > 0 || ($_SESSION['authority'] == 1 || $_SESSION['authority'] == 0)) {
                        if ($exam_end >= $today) {
                            $change_options = "<button type='button' class='change_marks' id='change_marks".$resultid."'>Change</button>";
                        }else {
                            $change_options = "<p style='color:green;font-size:12px;font-weight:600;'>Confirmed ✔</p>";
                        }
                    }else {
                        $change_options = "<p style='color:green;font-size:12px;font-weight:600;'>Confirmed ✔</p>";
                    }
                    //variable for storemarks
                    $data_to_display.="<div class='table_row' id='table_data".$resultid."'>
                                        <div class='td'><input id='curriculum".$resultid."' hidden value='".$row["grade_method"]."'>
                                            <p class='numbers'>".$xd.". (".$student_id.") </p>
                                            <p class='student_names_out' style='font-size:12px;font-weight:600;' id = 'stud_name".$resultid."'>".getStudentName($student_id,$conn2)."</p>
                                            <p class='subjects_scores' id='subject_marks".$resultid."'>".$exam_marks."</p>
                                            <p class='subjects_grade' id='grd".$resultid."'>".$exam_grade."</p>
                                            ".$change_options."
                                        </div>
                                    </div>";
                                    array_push($marks,$exam_marks);
                                    array_push($studentid,$student_id);
                }
                $data_to_display.="</div></div></div>";
                $total_marks = 0;
                $mean_scores = 0;
                if ($xd > 0) {
                //get meanscores
                for ($dd=0; $dd < count($marks); $dd++) { 
                    $total_marks+=$marks[$dd];
                }
                $mean_scores = round($total_marks/count($marks),2);
                    if ($mean_scores < 5) {
                        if ($mean_scores >= 4) {
                            $mean_scores = "Exceeding Expectation";
                        }elseif ($mean_scores >= 3) {
                            $mean_scores = "Meeting Expectation";
                        }elseif ($mean_scores >= 2) {
                            $mean_scores = "Aproaching Expectation";
                        }else {
                            $mean_scores = "Below Expectation";
                        }
                    }
                }
                $top3id = [];
                $botton3id = [];
                $data_to_display.="<div class='meanscores' id='top_bottom_3'>
                                        <p><strong>Mean score:</strong> ".$mean_scores."</p>";
                if ($xd >= 6) {
                    //get top 3
                    for ($dfd=0; $dfd < count($studentid); $dfd++) { 
                        if ($dfd > 2) {
                            break;
                        }
                        array_push($top3id,$studentid[$dfd]);
                    }
                    for ($fg=(count($studentid)-1); $fg >0 ; $fg--) { 
                        if ($fg < (count($studentid) - 3)) {
                            break;
                        }
                        array_push($botton3id,$studentid[$fg]);
                    }
                    $data_to_display.="<div ><div class='meanscore2'>
                                            <div class='meanscores' style='font-size:12px;'>
                                            <p><strong>Top 3:</strong></p>
                                                <table>
                                                    <tr><th>No. </th><th>Student Name</th><th>Score</th></tr>
                                                    <tr><td>1.🏆 </td><td>".getStudentName($top3id[0],$conn2)."</td><td>".grade($marks[0])."</td></tr>
                                                    <tr><td>2.🏆 </td><td>".getStudentName($top3id[1],$conn2)."</td><td>".grade($marks[1])."</td></tr>
                                                    <tr><td>3.🏆 </td><td>".getStudentName($top3id[2],$conn2)."</td><td>".grade($marks[2])."</td></tr>
                                                </table>
                                            </div>
                                            <div class='meanscores'>
                                            <p><strong>Bottom 3:</strong></p>
                                                <table>
                                                    <tr><th>No. </th><th>Student Name</th><th>Score</th></tr>
                                                    <tr><td>".(count($marks)-2).".🚩 </td><td>".getStudentName($botton3id[2],$conn2)."</td><td>".grade($marks[count($marks)-3])."</td></tr>
                                                    <tr><td>".(count($marks)-1).".🚩 </td><td>".getStudentName($botton3id[1],$conn2)."</td><td>".grade($marks[count($marks)-2])."</td></tr>
                                                    <tr><td>".(count($marks)-0).".🚩 </td><td>".getStudentName($botton3id[0],$conn2)."</td><td>".grade($marks[count($marks)-1])."</td></tr>
                                                </table>
                                            </div>
                                        </div></div>";
                }
                $data_to_display.="</div>";
                if ($xd > 0) {
                    echo $data_to_display;
                }else {
                    echo "<div class='displaydata'>
                        <img class='' src='images/error.png'>
                        <p class='' >No marks have been recorded for ".ucwords(strtolower($subject_name))." for ".$datas."! </p>
                    </div>";
                }
            }
        }elseif (isset($_GET['change_marks'])) {
            $record_id = $_GET['change_marks'];
            $valued = $_GET['valued'];
            $grade = $_GET['grade'];
            $update = "UPDATE `exam_record_tbl` SET `exam_marks` = ?, `exam_grade` = ? WHERE `result_id` = ?";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("sss",$valued,$grade,$record_id);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600px;'>Update was done successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600px;'>An error has occured during update!</p>";
            }
        }elseif (isset($_GET['deleteData'])) {
            $deleteData = $_GET['deleteData'];
            $delete = "DELETE FROM `exam_record_tbl` WHERE `result_id` = ?";
            $stmt = $conn2->prepare($delete);
            $stmt->bind_param("s",$deleteData);
            if($stmt->execute()){
                echo "<p style='color:green;font-size:12px;font-weight:600px;'>Deleted successfully!</p>";
            }else {
                echo "<p style='color:red;font-size:12px;font-weight:600px;'>An error has occured during delete!</p>";
            }
        }elseif (isset($_GET['get_term_of_class'])) {
            // $select = "SELECT  `term`, `start_time` , `end_time`  FROM `academic_calendar`WHERE 
            //                 (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
            //                 OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
            //                 OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
            //                 $date = date("Y");
            $select = "SELECT  `term`, `start_time` , `end_time`  FROM `academic_calendar`WHERE 
                            (`term` = 'TERM_1') 
                            OR (`term` = 'TERM_2') 
                            OR (`term` = 'TERM_3');";
                            $date = date("Y");
            $stmt = $conn2->prepare($select);
            // $stmt->bind_param("sss",$date,$date,$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<label class='form-control-label' for='term_select'>Select term <br></label>
                                    <select class='form-control' name='term_select' id='term_select'>
                                        <option value='' hidden>Select term..</option>";
                                        $sd = 0;
                while ($row = $result->fetch_assoc()) {
                    $sd++;
                    $data_to_display.="<option value='".$row['start_time']."|".$row['end_time']."' >".$row['term']."</option>";
                }
                $data_to_display.="</select>";
                if ($sd > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p style='color:red;font-size:12px;font-weight:600px;'>No terms to display!<br>Contact your administrator for error 458!</p>";
                }
            }
        }elseif (isset($_GET['return_examlist'])) {
            $startedtime = $_GET['startedtime'];
            $endingtimes = $_GET['endingtimes'];
            $select = "SELECT  `exams_id`, `exams_name` FROM `exams_tbl` WHERE `start_date` BETWEEN ? AND ? AND `end_date` BETWEEN ? AND ? AND `deleted` = 0";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("ssss",$startedtime,$endingtimes,$startedtime,$endingtimes);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<label class='form-control-label' for='examination_selection'>Select exam <br></label>
                                    <select class='form-control' name='examination_selection' id='examination_selection'>
                                        <option value='' hidden>Select exam..</option>";
                                        $sd = 0;
                while ($row = $result->fetch_assoc()) {
                    $sd++;
                    $data_to_display.="<option value='".$row['exams_id']."' >".$row['exams_name']."</option>";
                }
                $data_to_display.="</select>";
                if ($sd > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No exam has been done in the selected term!</p>";
                }
            }
        }elseif (isset($_GET['get_classes_sitting'])) {
            $get_classes_sitting = $_GET['get_classes_sitting'];
            $select = "SELECT `class_sitting` FROM `exams_tbl` WHERE `exams_id` = ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$get_classes_sitting);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $data_to_display = "<label class='form-control-label' for='classes_sat'>Select Class <br></label>
                                    <select class='form-control' name='classes_sat' id='classes_sat'>
                                        <option value='' hidden>Select class..</option>";
                                        $sd = 0;
                if ($row = $result->fetch_assoc()) {
                    $class_list = explode(",",rBkts($row['class_sitting']));
                    for ($dfd=0; $dfd < count($class_list); $dfd++) { 
                        $sd++;
                        $data_to_display.="<option value='".$class_list[$dfd]."' >".className($class_list[$dfd])."</option>";
                    }
                }
                $data_to_display.="</select>";
                if ($sd > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No exam has been done in the selected term!</p>";
                }
            }else {
                echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>Connection timed out<br> Try again!</p>";
            }
        }elseif (isset($_GET['active_exams_lts'])) {
            $select = "SELECT COUNT(*) AS 'Total' FROM `exams_tbl` WHERE `end_date` > ?";
            $stmt = $conn2->prepare($select);
            $date = date("Y-m-d", strtotime("3 hour"));
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    echo $row['Total'];
                }else {
                    echo "0";
                }
            }else {
                echo "0";
            }
        }elseif (isset($_GET['subs_lists'])) {
            $id = "%(".$_SESSION['userids'].":%";
            $select = "SELECT  COUNT(*) AS 'Total' FROM `table_subject` WHERE `teachers_id` LIKE ?";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $my_total_sub = 0;
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $my_total_sub = $row['Total'];
                }
            }
            echo $my_total_sub;
        }
        elseif (isset($_GET['get_perfomance_for_class'])) {
            $class_sat = $_GET['class_sat'];
            $exam_id = $_GET['exam_id'];
            //get the subjects done by class
            $select = "SELECT `subject_id`,`subject_name` FROM `table_subject` WHERE `classes_taught` like ? AND `sub_activated` = 1";
            $class = "%".$class_sat."%";
            $stmt = $conn2->prepare($select);
            $stmt->bind_param("s",$class);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $subject_id = [];
                while ($row = $result->fetch_assoc()) {
                    array_push($subject_id,$row['subject_id']);
                }
                if (count($subject_id) > 0) {
                    //the get the subjects done in the exam
                    $select = "SELECT `subject_done` FROM `exams_tbl` WHERE `exams_id` = ?";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s",$exam_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        $subject_id2 = [];
                        if ($row = $result->fetch_assoc()) {
                            $subject_id2 = explode(",",rBkts($row['subject_done']));
                        }
                        if (count($subject_id2) > 0) {
                            //the last subject list
                            $last_subjects = [];
                            for ($ind=0; $ind < count($subject_id); $ind++) { 
                                $present = checkPresnt($subject_id2,$subject_id[$ind]);
                                if ($present == 1) {
                                    array_push($last_subjects,$subject_id[$ind]);
                                }
                            }
                            if (count($last_subjects) > 0) {
                                //get the students for class six and their subject marks
                                $select = "SELECT `first_name`,`second_name`,`adm_no` FROM `student_data` WHERE `deleted` = 0 AND `activated` = 1 AND `stud_class` = ?";
                                $stmt = $conn2->prepare($select);
                                $stmt->bind_param("s",$class_sat);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    $student_ids = [];
                                    while ($row = $result->fetch_assoc()) {
                                        array_push($student_ids,$row['adm_no']);
                                    }
                                    if (count($student_ids) > 0) {
                                        $row_data = [];
                                        //for each student get each student marks for each subject
                                        for ($stud_index=0; $stud_index < count($student_ids); $stud_index++) { 
                                            $data_column = [];
                                            array_push($data_column,$student_ids[$stud_index]);
                                            for ($sub_index=0; $sub_index < count($last_subjects); $sub_index++) {
                                                $select = "SELECT `exam_marks`,`exam_grade` FROM `exam_record_tbl` WHERE `student_id` = ? AND `exam_id` = ? and `subject_id` = ?";
                                                $stmt = $conn2->prepare($select);
                                                $stmt->bind_param("sss",$student_ids[$stud_index],$exam_id,$last_subjects[$sub_index]);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result) {
                                                    if ($row = $result->fetch_assoc()) {
                                                        array_push($data_column,$row['exam_marks']);
                                                    }else {
                                                        array_push($data_column,"101");
                                                    }
                                                }else {
                                                    array_push($data_column,"101");
                                                }
                                            }
                                            array_push($row_data,$data_column);
                                        }
                                        if (count($row_data) > 0) {
                                            //get the totals
                                            $grand_total = [];
                                            for ($rowindex=0; $rowindex < count($row_data); $rowindex++) { 
                                                $tot = 0;
                                                $col_data = $row_data[$rowindex];
                                                for ($col_index=0; $col_index < count($col_data); $col_index++) { 
                                                    $columns_data = $col_data[$col_index];
                                                    if ($columns_data >4.9 && $columns_data <= 100 && $col_index > 0) {
                                                        $tot=$tot + $columns_data;
                                                    }elseif($columns_data <= 4 && $col_index > 0) {
                                                        $tot+=$columns_data;
                                                    }
                                                }
                                                $grand_total[$rowindex] = $tot;
                                            }
                                            foreach ($grand_total as $keys => $values) {
                                                //echo "Key = ".$keys.", ".$values."<br>";
                                            }
                                            arsort($grand_total);
                                            //echo "<br>Sorted<br>";
                                            foreach ($grand_total as $keys => $values) {
                                                //echo "Key = ".$keys.", ".$values."<br>";
                                            }

                                            $data_to_display="<div class='my-2 ' id='exam-results-in' style='text-align:center;'><h6 class='my-0'>".getExamName($exam_id,$conn2)."</h6>
                                                            <h6>".className($class_sat)." Results</h6>
                                                            </div>";
                                            $data_to_display.="<div class='tableHolder'><table class='table'><tr><th>Pos.</th><th>Student Name</th>";

                                            for ($subj_index=0; $subj_index < count($last_subjects); $subj_index++) { 
                                                if (count($last_subjects)>4) {
                                                    $data_to_display.="<th title='".getSubjectName($last_subjects[$subj_index],$conn2)."'>".getSubjectTT($last_subjects[$subj_index],$conn2)."</th>";
                                                }else {
                                                    $data_to_display.="<th>".getSubjectName($last_subjects[$subj_index],$conn2)."</th>";
                                                }
                                                
                                            }$data_to_display.="<th>Total</th></tr>";
                                            $xsd=0;
                                            $total_marked = [];
                                            foreach ($grand_total as $keys => $values) {
                                                $row_ind = $keys;
                                            }
                                            $cbc_1 = 0;
                                            $ei44 = 0;
                                            //for ($row_ind=0; $row_ind < count($row_data); $row_ind++) { 
                                            foreach ($grand_total as $keys => $values) {
                                                $row_ind = $keys;
                                                $xsd++;
                                                //ROW
                                                $column_data = $row_data[$row_ind];
                                                $data_to_display.="<tr>";
                                                $totals = 0;
                                                for ($inter=0; $inter < count($column_data); $inter++) { 
                                                    $datacol = $column_data[$inter];
                                                    //COLUMN
                                                    if ($inter == 0) {
                                                        if ($xsd <= 3) {
                                                            $data_to_display.="<td>".$xsd." 🏆</td>";
                                                        }else {
                                                            $data_to_display.="<td>".$xsd." </td>";
                                                        }
                                                        $stud_name = getStudentName($datacol,$conn2);
                                                        $data_to_display.="<td>".ucwords(strtolower($stud_name))."<small style='color:brown;'> (".$datacol.")</small></td>";
                                                    }else {
                                                        if ($datacol > 4 && $datacol <= 100 ) {
                                                            if ($datacol>0) {
                                                                $ei44++;
                                                            }
                                                            $data_to_display.="<td>".$datacol."%</td>";
                                                        }else {
                                                            if ($datacol>0 && $datacol != 101) {
                                                                $cbc_1++;
                                                            }
                                                            if ($datacol == 4) {
                                                                $data_to_display.="<td>"."E.E"."</td>";
                                                            }elseif ($datacol == 3) {
                                                                $data_to_display.="<td>"."M.E"."</td>";
                                                            }elseif ($datacol == 2) {
                                                                $data_to_display.="<td>"."A.E"."</td>";
                                                            }elseif ($datacol == 1) {
                                                                $data_to_display.="<td>"."B.E"."</td>";
                                                            }elseif ($datacol == 0) {
                                                                $data_to_display.="<td>"."A"."</td>";
                                                            }elseif ($datacol == 101){
                                                                $data_to_display.="<td>"."-"."</td>";
                                                            }
                                                        }
                                                    }
                                                    if ($datacol >4.9 && $datacol <= 100 && $inter > 0) {
                                                        $totals=$totals + $datacol;
                                                    }elseif($datacol <= 4 && $inter > 0) {
                                                        $totals+=$datacol;
                                                    }
                                                }
                                                if ($totals/count($last_subjects) <= 4) {
                                                    $cbc = round($totals/count($last_subjects),2);
                                                    if ($cbc >= 3.5 && $cbc < 4) {
                                                        $data_to_display.="<td>"."E.E"."</td>";
                                                    }elseif ($cbc >= 2.8) {
                                                        $data_to_display.="<td>"."M.E"."</td>";
                                                    }elseif ($cbc >= 1.8) {
                                                        $data_to_display.="<td>"."A.E"."</td>";
                                                    }elseif ($cbc >= 0.8) {
                                                        $data_to_display.="<td>"."B.E"."</td>";
                                                    }elseif ($cbc >= 0) {
                                                        $data_to_display.="<td>"."A"."</td>";
                                                    }elseif ($cbc == 101){
                                                        $data_to_display.="<td>"."-"."</td>";
                                                    }
                                                    //$data_to_display.="<td>".$cbc."</td></tr>";
                                                    array_push($total_marked,round($totals/count($last_subjects)));
                                                }else {
                                                    $data_to_display.="<td>".$totals."</td></tr>";
                                                    array_push($total_marked,$totals);
                                                }
                                                //$total_marked[$xsd] = $totals;
                                            }
                                            $data_to_display.="</table></div>";
                                            $mean_scored = round(arrayCounter($total_marked,0)/count($total_marked),2);
                                            if ($mean_scored <= 5) {
                                                if ($mean_scored >= 4) {
                                                    $mean_scored="<b>Exceeding Expectation</b>";
                                                }elseif ($mean_scored >=3) {
                                                    $mean_scored="<b>Meeting Expectation</b>";
                                                }elseif ($mean_scored >= 2) {
                                                    $mean_scored="<b>Approaching Expectation</b>";
                                                }elseif ($mean_scored >= 1) {
                                                    $mean_scored="<b>Below Expectation</b>";
                                                }elseif ($mean_scored >= 0) {
                                                    $mean_scored="<b>Not recorded!</b>";
                                                }elseif ($mean_scored == 101){
                                                    $mean_scored="<b>-</b>";
                                                }
                                            }
                                            $data_to_display.="<div class='meanscores'>
                                                                <p><strong>Overall Mean score:</strong> ".$mean_scored."</p>";
                                            if ($xsd > 6) {
                                                $data_to_display.="<div class='meanscores123'><div>
                                                <p><strong>Top 3</strong></p><table><tr><th>No</th><th>Student Name</th><th>Marks Scored</th></tr>";
                                                                    $xm = 0;
                                                foreach ($grand_total as $keys => $values) {
                                                    $xm++;
                                                    if ($xm > 3) {
                                                        break;
                                                    }
                                                    $rowindexs = $keys;
                                                    $student_name = ucwords(strtolower(getStudentName($row_data[$rowindexs][0],$conn2)));
                                                    if ($grand_total[$keys]/count($last_subjects) < 5) {
                                                        $final_total = $grand_total[$keys]/count($last_subjects);
                                                        $my_totals = "-";
                                                        if ($final_total >= 3.5 && $final_total < 4) {
                                                            $my_totals ="E.E";
                                                        }elseif ($final_total >= 2.8) {
                                                            $my_totals ="M.E";
                                                        }elseif ($final_total >= 1.8) {
                                                            $my_totals ="A.E";
                                                        }elseif ($final_total >= .8) {
                                                            $my_totals ="B.E";
                                                        }elseif ($final_total >= 0) {
                                                            $my_totals ="A";
                                                        }
                                                        $data_to_display.="<tr><td>".$xm." 🏆 </td> <td><span style='color:green;font-size:14px;'>".$student_name."</span> <small> (".$row_data[$rowindexs][0].")</small></td> <td>".$my_totals."</td></tr>";
                                                    }else {
                                                        $data_to_display.="<tr><td>".$xm." 🏆 </td> <td><span style='color:green;font-size:14px;'>".$student_name."</span> <small> (".$row_data[$rowindexs][0].")</small></td> <td>".$grand_total[$keys]."</td></tr>";
                                                    }
                                                }
    
                                                $data_to_display.="</table></div><div>";
                                                $data_to_display.="<p><strong> Bottom 3</strong></p>";
                                                $data_to_display.="<table><tr><th>No.</th><th>Student Name</th><th>Marks Scored</th></tr>";
                                                $xmd = 0;
                                                $xmd2 = 2;
                                                foreach ($grand_total as $keys => $values) {
                                                    $xmd++;
                                                    if (count($grand_total)-$xmd == $xmd2) {
                                                        $student_name = ucwords(strtolower(getStudentName($row_data[$keys][0],$conn2)));
                                                        if ($grand_total[$keys]/count($last_subjects) < 5) {
                                                            $final_total = $grand_total[$keys]/count($last_subjects);
                                                            $my_totals = "-";
                                                            if ($final_total >= 3.5 && $final_total < 4) {
                                                                $my_totals ="E.E";
                                                            }elseif ($final_total >= 2.8) {
                                                                $my_totals ="M.E";
                                                            }elseif ($final_total >= 1.8) {
                                                                $my_totals ="A.E";
                                                            }elseif ($final_total >= 0.8) {
                                                                $my_totals ="B.E";
                                                            }elseif ($final_total >= 0) {
                                                                $my_totals ="A";
                                                            }
                                                            $data_to_display.="<tr><td>".$xmd." 🚩</td><td><span style='color:brown;font-size:14px;'>".$student_name."</span> <small>(".$row_data[$keys][0].")</small></td><td>".$my_totals."</td></tr>";
                                                        }else {
                                                            $data_to_display.="<tr><td>".$xmd." 🚩</td><td><span style='color:brown;font-size:14px;'>".$student_name."</span> <small>(".$row_data[$keys][0].")</small></td><td>".$grand_total[$keys]."</td></tr>";                                                            
                                                        }
                                                        $xmd2--;
                                                    }
                                                }
                                                $data_to_display.="</table></div></div>";                                                
                                            }
                                            $data_to_display.="<p><br><strong>Hint</strong><br>\t- Hover your mouse over the subject name to get its full name</p>
                                                                </div>";
                                            if ($xsd > 0) {
                                                if ($cbc_1 > 0 || $ei44 > 0) {
                                                    if ($cbc_1 > 0 && $ei44 > 0) {
                                                        echo "<p class='fa-fw fa-xs red_notice ' style='line-height:15px;text-align:center;'>Different grading system used the results might be in-accurate<br>Changes should be done to the subjects with different grading method.</p>";
                                                    }
                                                }
                                                echo $data_to_display;
                                            }
                                            
                                        }
                                    }else {
                                        echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No students present in ".className($class_sat)."!</p>";
                                    }
                                }
                            }else {
                                echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No subjects were done by ".className($class_sat)."!</p>";
                            }
                        }
                    }
                }else {
                    echo "<p style='margin-top:10px;font-size:12px;font-weight:600;color:red;'>No subjects done by that class!</p>";
                }
            }
        }elseif (isset($_GET['deletesubject'])) {
            $delete = "DELETE FROM `table_subject` WHERE `subject_id` = ?;";
            $stmt = $conn2->prepare($delete);
            $subid = $_GET['subjectid'];
            $stmt->bind_param("s",$subid);
            if($stmt->execute()){
                echo "<p class='green_notice'>You have successfully deleted the subject.</p>";
            }else {
                echo "<p class='red_notice'>An error has occured!</p>";
            }
        }elseif (isset($_GET['get_class_informations'])) {
            $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            //class code starts at 101
            $class_code = 101;
            $data_to_display = "<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;'>No classes available</label></div>";
            if ($result) {
                $classinfor = "";
                if ($row = $result->fetch_assoc()) {
                    // retrieve class lists from the database
                    $class = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
                    $all_classes = [];
                    for ($index=0; $index < count($class); $index++) { 
                        array_push($all_classes,$class[$index]->classes);
                    }
                    
                    $data_to_display = "<div class='classlist'>";
                    $classlisted = $all_classes;
                    for($index = 0;$index<count($classlisted);$index++){
                        // $data_to_display.="";
                        $data_to_display.="<div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='".$class_code."'>".className($classlisted[$index])."</label><input class='ttt_class' type='checkbox' name = '".$classlisted[$index]."' value = '".$class_code.",".$classlisted[$index]."' id='".$class_code."'></div>";
                        $class_code++;
                    }
                    $data_to_display.="</div>";
                }
                echo $data_to_display;
            }else {
                echo $data_to_display;
            }
        }elseif (isset($_GET['get_class_subjects'])) {
            $select = "SELECT * FROM `table_subject` WHERE `classes_taught` LIKE ?";
            $stmt = $conn2->prepare($select);
            $classes = $_GET['get_class_subjects'];
            $class_arr = explode(",",$classes);
            $subject_list = [];
            for ($index=0; $index < count($class_arr); $index++) { 
                $data = "%".$class_arr[$index]."%";
                $stmt->bind_param("s",$data);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $display_name = strlen(trim($row['display_name'])) > 0 ? $row['display_name'] : $row['subject_name'];
                        $string_data = $row['subject_id'].":".$display_name.":".$row['timetable_id'];
                        if (checkPresnt($subject_list,$string_data) == 0) {
                            array_push($subject_list,$string_data);
                        }
                    }
                }
            }
            //display the subject list
            $data_to_display = "<div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'><label class='text-danger' style='margin-right:5px;cursor:pointer;font-size:12px;'>No Subjects available</label></div>";
            if (count($subject_list) > 0) {
                $data_to_display = "<div class='classlist'>";
                for ($index=0; $index < count($subject_list); $index++) {
                    $datas = explode(":",$subject_list[$index]);
                    $data_to_display.="<div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label class='mysubjectallin' style='margin-right:5px;cursor:pointer;font-size:12px;' for='".$datas[0].$datas[2]."'>".$datas[1]."</label><input class='ttt_class2' type='checkbox' name = '".$datas[0]."' value = '".$datas[0]."' id='".$datas[0].$datas[2]."'></div>";
                }
            }
            $data_to_display.="</div>";
            echo $data_to_display;

        }elseif (isset($_GET['preview_data'])) {
            include("../../connections/conn1.php");
            $subject_list = $_GET['subject_list'];
            $classlist = $_GET['classlist'];
            $exp_sub = explode(",",$subject_list);
            $exp_cls = explode(",",$classlist);
            $select = "SELECT * FROM `table_subject` WHERE `subject_id` = ?";
            $stmt = $conn2->prepare($select);
            $data_to_display = "<div><p>";
            for ($index=0; $index < count($exp_sub); $index++) { 
                $stmt->bind_param("s",$exp_sub[$index]);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $classes_taught = $row['classes_taught'];
                        $cl_taught = explode(",",$classes_taught);
                        $display_name = strlen(trim($row['display_name'])) > 0 ? $row['display_name'] : $row['subject_name'];
                        $data_to_display.=($index+1).". <strong>".$display_name."</strong>: <br>";
                        $classes_taught = $row['classes_taught'];
                        $cl_taught = explode(",",$classes_taught);
                        for ($ind=0; $ind < count($exp_cls); $ind++) { 
                            if (checkPresnt($cl_taught,$exp_cls[$ind]) == 1) {
                                $trname = getTeacher($exp_sub[$index],$exp_cls[$ind],$conn2,$conn);
                                $data_to_display.="<span style='margin-left:10px;'>".className($exp_cls[$ind]).":  <span class='trnamed'>".$trname."</span><br></span>";
                            }else {
                                $data_to_display.="<span style='margin-left:10px;'>".className($exp_cls[$ind])."<span class='invinsible'>-i</span>:  <span class='trnamed'>N/A</span><br></span>";
                            }
                        }
                    }
                }
            }
            $data_to_display.="</p></div>";
            echo $data_to_display;
            $conn->close();
        }elseif (isset($_GET['generate_tt'])) {
            include("../../connections/conn1.php");
            //get the data 
            $classlist = $_GET['class_selected'];
            $subjects_in = $_GET['subjects_in'];
            $morning_hours = $_GET['morning_hours'];
            $number_of_lessons = $_GET['number_of_lessons'];
            $daysoftheweek = $_GET['daysoftheweek'];

            //at this segment we want to get the subjects taught the teacher teaching the subject and the ckasses taught
            $teacher_taught = [];
            //get the classes 
            $exp_classes = explode(":",$classlist);
            $classeslist = [];
            for ($index=0; $index < count($exp_classes); $index++) { 
                $string_cls = $exp_classes[$index];
                $expl_cl = explode(",",$string_cls);
                $class = $expl_cl[1];
                array_push($classeslist,$class);
            }
            //subject list
            $subject_arr = explode(",",$subjects_in);
            //get the teachers list
            $teacher_id = [];
            $teacher_name = [];
            for ($index=0; $index < count($classeslist); $index++) { 
                //for all the classes 
                for ($ind=0; $ind < count($subject_arr); $ind++) { 
                    $trid = getTeacherIdd($subject_arr[$ind],$classeslist[$index],$conn2,$conn);
                    if (checkPresnt($teacher_id,$trid) == 0) {
                        array_push($teacher_id,$trid);
                        $trnamer = getTeacherName($conn,$trid);
                        array_push($teacher_name,$trnamer);
                    }
                }
            }
            //show the classes present
            echo "<p><strong>Classes Choosen</strong></p>";
            for ($index=0; $index < count($exp_classes); $index++) {
                $clased = explode(",",$exp_classes[$index]);
                echo ($index+1).". ".className($clased[1])."<br>";
            }
            //show the subjects present
            echo "<br><p><strong>Subjects Choosen</strong></p>";
            for ($index=0; $index < count($subject_arr); $index++) { 
                echo ($index+1).". ".getSubjectName($subject_arr[$index],$conn2)."<br>";
            }
            //show the teachers present
            echo "<br><p><strong>Teachers Present</strong></p>";
            //get number of teachers
            for ($index=0; $index < count($teacher_name); $index++) { 
                echo ($index+1).". ".$teacher_name[$index]."<br>";
            }
            ///GET THE MORNING HOUR SUBJECTS
            echo "<br><p><strong>Morning hour subjects</strong></p>";
            //break subjects
            $class_sub = explode(",",$morning_hours);
            for ($index=0; $index < count($class_sub); $index++) { 
                echo ($index+1).". ".getSubjectName($class_sub[$index],$conn2)."<br>";
            }

            // get the number of lessons a day
            echo "<br><p><strong>Number of lessons.</strong></p>";
            echo "<p>Number of lessons a day: ".$number_of_lessons."</p>";

            //get the days of the week
            echo "<br><p><strong>Days of the week choosen.</strong></p>";
            //break week days
            $weekdays = explode(",",$daysoftheweek);
            for ($index=0; $index < count($weekdays); $index++) { 
                echo ($index+1).". ".$weekdays[$index]."<br>";
            }
            echo "<br><p style='color:green;'><i class = 'fa fa-check'></i>All is good to go!</p>";
            $conn->close();
        }elseif (isset($_GET['generate_tt_insidein'])) {
            include("../../connections/conn1.php");
            $class_selected = $_GET['class_selected'];
            // echo $class_selected;
            $subjects_in = $_GET['subjects_in'];
            $number_of_lessons = $_GET['number_of_lessons'];
            $daysoftheweek = $_GET['daysoftheweek'];
            $morning_hours = $_GET['morning_hours'];
            
            //get the class list and the subject list
            $classlist = explode(":",$class_selected);
            $myownclass = [];
            $classcodes = [];
            for ($index=0; $index < count($classlist); $index++) { 
                $classes = explode(",",$classlist[$index]);
                array_push($myownclass,$classes[1]);
                array_push($classcodes,$classes[0].":".$classes[1]);
            }
            //get the subjects
            $subject_arr = explode(",",$subjects_in);
            //get the teachers

            $teacher_id = [];
            for ($index=0; $index < count($myownclass); $index++) { 
                for ($ind=0; $ind < count($subject_arr); $ind++) { 
                    $teachid = getTeacherIdd($subject_arr[$ind],$myownclass[$index],$conn2,$conn);
                    if (checkPresnt($teacher_id,$teachid) == 0) {
                        array_push($teacher_id,$teachid);
                    }
                }
            }
            //get the subjects the teacher is teaching and the classes
            //for all the teachers
            $teachersnclasses = [];
            for ($index=0; $index < count($teacher_id); $index++) { 
                // echo ($index+1).". ".getTeacherName($conn,$teacher_id[$index])."<br>";
                $stringdata = "";
                for ($inde=0; $inde < count($subject_arr); $inde++) { 
                    for ($ind=0; $ind < count($myownclass); $ind++) { 
                        $teachid = getTeacherIdd($subject_arr[$inde],$myownclass[$ind],$conn2,$conn);
                        if ($teacher_id[$index] == $teachid) {
                            //get the subject and the class
                            $stringdata.=$subject_arr[$inde].":".$myownclass[$ind].",";
                        }
                    }
                }
                $stringdata = substr($stringdata,0,strlen($stringdata)-1);
                array_push($teachersnclasses,$stringdata);
            }
            $jsondata = "{\"TEACHERS\":[";
            //creates teacher data
            for ($index=0; $index < count($teacher_id); $index++) { 
                $jsondata.="{\"NAME\":\"".getTeacherName($conn,$teacher_id[$index])."\",\"SUBJECTS\":[";
                    $string_data = explode(",",$teachersnclasses[$index]);
                    for ($ind=0; $ind < count($string_data); $ind++) { 
                        //split the classes and subjects
                        $data = explode(":",$string_data[$ind]);
                        $subdets = getSubjectDetails($conn2,$data[0]);
                        $jsondata.="{\"SUBNAME\":\"".$subdets[0]."\",\"CLASS_TAUGHT\":[{\"CLASSNAME\":\"".className($data[1])."\",\"CLASSCODE\":\"".getClsCode($data[1],$classcodes)."\"}],\"SUBJECT_CODE\":\"".$subdets[1]."\"},";
                    }
                    $jsondata = substr($jsondata,0,strlen($jsondata)-1);
                    $jsondata.="],\"TEACHER_CODE\":\"".trCode(getTeachercode($conn,$teacher_id[$index]))."\"},";
            }
            $jsondata = substr($jsondata,0,strlen($jsondata)-1);
            $jsondata.="]";
            //add the final results
            $strmorning = explode(",",$morning_hours);
            $strin = "";
            for ($index=0; $index < count($strmorning); $index++) { 
                $strin.=getSubjectName($strmorning[$index],$conn2).",";
            }
            $strin = substr($strin,0,strlen($strin)-1);
            $jsondata.=",\"MORNING_HOUR_SUBJECTS\":\"".$strin."\",\"NUMBER_OF_LESSONS\":\"".$number_of_lessons."\",\"DAY_OF_WEEK\":\"".$daysoftheweek."\"}";
            // echo $jsondata;


            //create a file
            $timetablenames = str_replace(" ","_", $_GET['ttnames']);
            $dbname = getDatabase($conn);
            
            //create a directory 
            // $filename = "/home/hilary/Desktop/timetabled/$dbname/requests/$timetablenames";
            $filename = str_replace(" ","_", "../../../timetabled/$dbname/requests/$timetablenames");
            if (!folder_exist($filename)) {
                if(mkdir($filename,0777,true)){
                    //write in the file name
                    $filenems = $filename."/$timetablenames.json";
                    $jsonfile = fopen($filenems,"w");
                    chmod($filenems,0777);
                    fwrite($jsonfile,$jsondata);
                    fclose($jsonfile);
                    $filenems = realpath($filenems);
                    $insert = "INSERT INTO `timetable_req` (`time_request`,`tt_name`,`date_req`,`school_id`,`req_json`,`status`) VALUES (?,?,?,?,?,?)";
                    $stmt = $conn->prepare($insert);
                    $time = date("H:i:s");
                    $date = date("Y-m-d");
                    $getSchid = getSchid($conn);
                    $status = "0";
                    $stmt->bind_param("ssssss",$time,$timetablenames,$date,$getSchid,$filenems,$status);
                    if($stmt->execute()){
                        echo "<p class='green_notice'>Check after a few seconds your timetable will be ready!</p>";

                        // write curl to handle the request
                        // get ip
                        // Initiate curl session in a variable (resource)
                        $curl_handle = curl_init();

                        $url = "https://lsims.ladybirdsmis.com/sims/create_timetable/create_tt.php";

                        // Set the curl URL option
                        curl_setopt($curl_handle, CURLOPT_URL, $url);

                        // This option will return data as a string instead of direct output
                        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

                        // Execute curl & store data in a variable
                        $curl_data = curl_exec($curl_handle);

                        curl_close($curl_handle);
                    }else {
                        echo "<p class='red_notice'>An error has occured: Data not successfully sent!</p>";
                    }
                }else {
                    echo "<p class='red_notice'>An error has occured: file error Restart the process!</p>";
                }
            }else {
                echo "<p class='red_notice'>Use another name the timetable name already exists</p>";
            }
            //update the database in file

            $update = "";
            $conn->close();

        }elseif (isset($_GET['regenerate_tt'])){
            include("../../connections/conn1.php");
            $regenerate_tt = $_GET['regenerate_tt'];
            $update = "UPDATE `timetable_req` SET `status` = '0' WHERE `ids` = '".$regenerate_tt."'";
            $stmt = $conn->prepare($update);
            if($stmt->execute()){
                echo "<p class='text-success'>Regeneration done successfully!</p>";
                $curl_handle = curl_init();

                $url = "http://localhost:81/sims/create_timetable/create_tt.php";

                // Set the curl URL option
                curl_setopt($curl_handle, CURLOPT_URL, $url);

                // This option will return data as a string instead of direct output
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

                // Execute curl & store data in a variable
                $curl_data = curl_exec($curl_handle);

                curl_close($curl_handle);
            }else{
                echo "<p class='text-danger'>An error has occured!</p>";
            }
        }elseif (isset($_GET['getMyTimetable'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `tt_name`,`ids`,`time_request`,`date_req`,`school_id`,`status` FROM `timetable_req` WHERE `school_id` = ?";
            $stmt = $conn->prepare($select);
            $schid = getSchid($conn);
            $stmt->bind_param("s",$schid);
            $stmt->execute();
            $result = $stmt->get_result();
            $data_to_display = "<h6 style='text-align:center;'>Timetable List </h6><div class='table_holders'>
                                    <table class='table'>
                                        <tr>
                                            <th>No.</th>
                                            <th>Timetable Name</th>
                                            <th>Date Generated</th>
                                            <th>Status</th>
                                            <th>Options</th>
                                        </tr>";
            if ($result) {
                $xs = 0;
                while ($row = $result->fetch_assoc()) {
                    $xs++;
                    $status= $row['status'];
                    $ttstatus = "Attended";
                    $inget = "<td><span class='link delete_tt_files'  id='".$row['ids']."'><i class='fa fa-trash'></i> Delete</span> || <span class='link view_timetables' id='".$row['ids']."'><i class='fa fa-eye'></i> View</span> || <span class='link regenerate_tt' id='regenerate_tt".$row['ids']."'><i class='fas fa-redo'></i> Regen</span></td>";
                    if ($status == 0) {
                        $ttstatus = "Un-attended";
                        $inget = "<td><p style='color:red;'>Not generated!</p></td>";
                    }
                    $data_to_display.="<tr>
                                        <td>".$xs.".</td>
                                        <td id='tt_names".$row['ids']."'>".ucwords(strtolower($row['tt_name']))."</td>
                                        <td>".date("D-dS-M-Y",strtotime($row['date_req']))."</td>
                                        <td>".$ttstatus."</td>
                                        ".$inget."
                                    </tr>";
                }
                $data_to_display.="</table></div>";
                if ($xs > 0) {
                    echo $data_to_display;
                }else {
                    echo "<p class='red_notice'>No timetable generated yet!</p>";
                }
            }else {
                echo "<p class='red_notice'>An error occured!</p>";
            }
            $conn->close();
        }elseif (isset($_GET['display_tt'])) {
            include("../../connections/conn1.php");
            $select = "SELECT `return_json` FROM `timetable_req` WHERE `ids` = ?";
            $tt_ids = $_GET['tt_ids'];
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$tt_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $return_json = $row['return_json'];
                    //read the file
                    if (strlen($return_json)>0) {
                        if (!is_dir($return_json)) {
                            if (file_exists($return_json)) {
                                // echo "<p id='error_msg_timetabled'>is file!</p>";
                                // chmod($return_json,0755);
                                $_SESSION['timetable_id'] = $tt_ids;
                            }
                            if($myfile = fopen($return_json, "r")){
                                $read = fread($myfile,filesize($return_json));
                                //read the data as a json file
                                $jsonreaddata = json_decode($read);
                                // echo $jsonreaddata->metadata['subjects'][0]->subjectname;
                                echo $read;
                            }else {
                                echo "<p id='error_msg_timetabled'>Cannot read the file!</p>";
                            }
                        }else {
                            echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                        }
                    }else {
                        echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                    }
                }else {
                    echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                }
            }else {
                echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
            }
            $conn->close();
        }elseif (isset($_GET['deletedtt'])) {
            $delete = "DELETE FROM `timetable_req` WHERE `ids` = ?";
            //get the file location
            include("../../connections/conn1.php");
            $select = "SELECT `req_json`,`return_json` FROM `timetable_req` WHERE `ids` = ?";
            $tt_ids = $_GET['timetable_id'];
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s",$tt_ids);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $return_json = $row['return_json'];
                    //delete the return json
                    unlink($return_json);
                    $folder = explode("/",$return_json);
                    $folder_name = "/";
                    for ($index=0; $index < (count($folder)-1); $index++) {
                        $folder_name.=$folder[$index]."/";
                    }
                    $folder_name = substr($folder_name,0,(strlen($folder_name)-1));
                    if (is_dir($folder_name)) {
                        rmdir($folder_name);
                        // $stmt = $conn->prepare($delete);
                        // $stmt->bind_param("s",$tt_ids);
                        // if($stmt->execute()){
                        //     echo "<p class='green_notice' style='text-align:center;'>Deleted successfully!</p>";
                        // }else {
                        //     echo "<p class='red_notice'>Error occured</p>";
                        // }
                    }
                    $stmt = $conn->prepare($delete);
                    $stmt->bind_param("s",$tt_ids);
                    if($stmt->execute()){
                        echo "<p class='green_notice' style='text-align:center;'>Deleted successfully!</p>";
                    }
                }
            }
        }elseif (isset($_GET['get_exams_results'])) {
            $select_class_id = "class_label_exams_result";
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '".$_GET['get_exams_results']."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $students_sitting = "";
            $class_sitting_b = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $students_sitting = $row['students_sitting'];
                    $class_sitting_b = $row['class_sitting'];
                }
            }

            // get the class sitting
            if (strlen(trim($students_sitting)) > 0) {
                // means that students are present
                $data = json_decode(trim($students_sitting));
                $my_classes = [];
                // loop through the data and get classes
                $string_to_display = "<select class='form-control w-100' name='".$select_class_id."' id='".$select_class_id."'> <option value='' hidden>Select..</option>";
                for ($indexed=0; $indexed < count($data); $indexed++) { 
                    $class_names = $data[$indexed]->classname;
                    $string_to_display.="<option id='".$class_names."' value='".$class_names."'>".className($class_names)."</option>";
                }
                $string_to_display.="</select>";
                echo $string_to_display;
            }else{
                // means that students are not present
                $class_sitting_b = trim($class_sitting_b);
                if (strlen($class_sitting_b) > 0) {
                    $studentd_data = explode(",",substr($class_sitting_b,1,(strlen($class_sitting_b)-2)));
                    $my_classes = $studentd_data;
                    // loop through the data and get classes
                    $string_to_display = "<select class='form-control w-100' name='".$select_class_id."' id='".$select_class_id."'> <option value='' hidden>Select..</option>";
                    for ($indexed=0; $indexed < count($my_classes); $indexed++) { 
                        $class_names = $my_classes[$indexed];
                        $string_to_display.="<option id='".$class_names."' value='".$class_names."'>".className($class_names)."</option>";
                    }
                    $string_to_display.="</select>";
                    echo $string_to_display;
                }
            }
        }elseif (isset($_GET['get_custom_table'])) {
            include("../../connections/conn1.php");
            $get_custom_table = $_SESSION['timetable_id'];
            $select = "SELECT * FROM `timetable_req` WHERE `ids` = '".$get_custom_table."'";
            $stmt = $conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $file_location = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $file_location = $row['return_json'];
                }
            }
            if (strlen($file_location) > 0) {
                if (strlen($file_location)>0) {
                    if (!is_dir($file_location)) {
                        if (file_exists($file_location)) {
                            // chmod($file_location,0755);
                        }
                        if($myfile = fopen($file_location, "r")){
                            $read = fread($myfile,filesize($file_location));
                            //read the data as a json file
                            $jsonreaddata = json_decode($read);
                            // first get all classes available in the timetable
                            // block timetable
                            $block_timetable = $jsonreaddata->timetables[0]->blocktimetable;
                            // classes data
                            $metadata_classes = $jsonreaddata->metadata[1]->classes;
                            // teacher data
                            $metadata_teachers = $jsonreaddata->metadata[2]->teachers;
                            // subject information
                            $metadata_subjects = $jsonreaddata->metadata[0]->subjects;
                            $class_list = [];
                            for ($index=0; $index < count($block_timetable); $index++) { 
                                $classes = $block_timetable[$index]->classes;
                                for ($index2=0; $index2 < count($classes); $index2++) { 
                                    $present = checkPresnt($class_list,trim($classes[$index2]->classname));
                                    if ($present == 0) {
                                        array_push($class_list,trim($classes[$index2]->classname));
                                    }
                                }
                            }
                            sort($class_list);

                            // get all posible lessons for the different classes
                            $posible_lessons = [];
                            for ($index=0; $index < count($class_list); $index++) { 
                                $posible_lessons[$class_list[$index]] = [];
                            }

                            // get the possible lessons for the arrays present
                            $lesson_count = 0;
                            for ($index=0; $index < count($block_timetable); $index++) { 
                                // get classes and lessons
                                $classes = $block_timetable[$index]->classes;
                                for ($index2=0; $index2 < count($classes); $index2++) { 
                                    // loop through lessons in a class
                                    $class_names = trim($classes[$index2]->classname);
                                    $lessons = $classes[$index2]->lessons;
                                    $lesson_count = count($lessons);
                                    // loop through lessons for a particular class and find if the have ever occured
                                    for ($index3=0; $index3 < count($lessons); $index3++) { 
                                        $single_lesson = trim($lessons[$index3]);
                                        $present = checkPresnt($posible_lessons[$class_names],$single_lesson);
                                        if ($present == 0) {
                                            array_push($posible_lessons[$class_names],$single_lesson);
                                        }
                                    }
                                }
                            }
                            // custom table display
                            // echo $read;
                            // var_dump($posible_lessons);

                            // GO THROUGH THE DAYS OF THE WEEK
                            $data_to_display = "<span class='hide' id='default_json_tt'>".json_encode($block_timetable)."</span><span id='timetable_blocks' class='hide'>".json_encode($block_timetable)."</span>";
                            // display data classes
                            $data_to_display.="<div class='row'><div class='col-md-4'><h6><u><b>Classes Present</b></u></h6>";
                            $class_names = "<span class='hide' id='classes_present'>[";
                            for ($index=0; $index < count($metadata_classes); $index++) { 
                                $data_to_display.="<span class='list_my_classes'>".$metadata_classes[$index]->classname." - ".$metadata_classes[$index]->classid."</span><br>";
                                $class_names .= "\"".$metadata_classes[$index]->classname."\",";
                            }
                            $class_names = substr($class_names,0,strlen($class_names)-1)."]</span>";
                            $subject_ids = [];
                            // add classes here +===++==>
                            $data_to_display.="</div>".$class_names."<div class='col-md-4'><h6><u><b>Subject Present</b></u></h6>";
                            for ($index=0; $index < count($metadata_subjects); $index++) { 
                                if (checkPresnt($subject_ids,$metadata_subjects[$index]->subject_id) == 0) {
                                    array_push($subject_ids,$metadata_subjects[$index]->subject_id);
                                    $data_to_display.=$metadata_subjects[$index]->subjectname." - <b>".$metadata_subjects[$index]->subject_id."</b><br>";
                                }
                            }
                            // teachers list 
                            $data_to_display.="</div><div class='col-md-4'><h6><u><b>Teachers Present</b></u></h6>";
                            for ($index=0; $index < count($metadata_teachers); $index++) { 
                                // if (checkPresnt($subject_ids,$metadata_teachers[$index]->subject_id) == 0) {
                                //     array_push($subject_ids,$metadata_teachers[$index]->subject_id);
                                // }
                                $data_to_display.=$metadata_teachers[$index]->teachername." - (<b>".$metadata_teachers[$index]->teacherid."</b>)<br>";
                            }
                            $data_to_display.="</div></div><hr><div id='reset_tt'>";
                            // display the block time table here is where you can edit the timetable
                            for ($index=0; $index < count($block_timetable); $index++) { 
                                $data_to_display.="<p><b>Day: </b> ".$block_timetable[$index]->Day."</p><div class='table_holders'><table class='table'><tr><th>Class Name</th>";
                                for ($i=0; $i < $lesson_count; $i++) { 
                                    $data_to_display.="<th>Pr".($i+1)."</th>";
                                }
                                $data_to_display.="</tr>";
                                $classes = $block_timetable[$index]->classes;

                                for ($index2=0; $index2 < count($classes); $index2++) { 
                                    $data_to_display.="<tr><td>".$classes[$index2]->classname."</td>";
                                    $lessons = $classes[$index2]->lessons;
                                    // get all lessons of that time in text
                                    // all lessons for a day 
                                    // know all lessons that will cause conflict
                                    $all_lessons = $block_timetable[$index]->classes;
                                    $my_list = [];
                                    $my_list2 = []; // list of unwanted teachers
                                    $my_list3 = "";
                                    for ($index3=0; $index3 < count($lessons); $index3++) { 
                                        $current_lesson = trim($lessons[$index3]);
                                        for ($index4=0; $index4 < count($all_lessons); $index4++) { 
                                            $class_lessons = $all_lessons[$index4]->lessons;
                                            // if the current lesson is equal to the lesson iterated do not add it to the unwanted list
                                            if ($current_lesson != trim($class_lessons[$index3])) {
                                                if (strlen($class_lessons[$index3]) > 0) {
                                                    array_push($my_list,trim($class_lessons[$index3]));
                                                    array_push($my_list2,substr(explode("{",$class_lessons[$index3])[1],0,-1));
                                                    $my_list3.=substr(explode("{",$class_lessons[$index3])[1],0,-1).",";
                                                }
                                            }
                                        }
                                        $my_list3 = substr($my_list3,0,-1);
                                        $possible_lesson = $posible_lessons[$classes[$index2]->classname];
                                        $blanks = 0;
                                        $select = "<select class='subject_select custom_tt'><option value='' hidden >Select Option</option>";
                                        for ($index5=0; $index5 < count($possible_lesson); $index5++) { 
                                            $single_lesson = trim($possible_lesson[$index5]);
                                            $is_present = checkPresntContain($my_list2,$single_lesson);
                                            if ($is_present == 0) {
                                                $selected = "";
                                                if ($current_lesson == $single_lesson) {
                                                    $selected = "selected";
                                                    // $selects = "selected";
                                                }
                                                if (strlen($single_lesson) > 0) {
                                                    $select.="<option ".$selected." value='".$index3."|".$block_timetable[$index]->Day."|".$classes[$index2]->classname."|".$single_lesson."'>".$single_lesson."</option>";
                                                }else{
                                                    $select.="<option ".$selected." value='".$index3."|".$block_timetable[$index]->Day."|".$classes[$index2]->classname."|".$single_lesson."'>".$single_lesson."</option>";
                                                    $blanks++;
                                                }
                                            }
                                        }
                                        if ($blanks == 0) {
                                            $select.="<option  value='".$index3."|".$block_timetable[$index]->Day."|".$classes[$index2]->classname."|'> </option>";
                                        }
                                        $select.="</select>";
                                        $data_to_display.="<td>".$select."</td>";
                                        $my_list = [];
                                        $my_list2 = [];
                                        $my_list3 = "";
                                    }
                                    $data_to_display."</tr>";
                                }
                                $data_to_display.="</table></div><hr>";
                                // add lessons here
                            }
                            $data_to_display."</div>";
                            echo $data_to_display;
                        }else {
                            echo "<p id='error_msg_timetabled'>Cannot read the file!</p>";
                        }
                    }else {
                        echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                    }
                }else {
                    echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                }
            }else{
                echo "<p class='text-danger'>You cannot customize the timetable at the moment because its file path is invalid!</p>";
            }
        }elseif (isset($_GET['delete_exams'])) {
            // delete the exams record then delete the exams record history
            $delete = "DELETE FROM `exams_tbl` WHERE `exams_id` = '".$_GET['exams_id']."'";
            $stmt = $conn2->prepare($delete);
            $stmt->execute();


            // delete the examination marks recorded
            $delete = "DELETE FROM `exam_record_tbl` WHERE `exam_id` = '".$_GET['exams_id']."'";
            $stmt = $conn2->prepare($delete);
            if($stmt->execute()){
                echo "<p class='text-success my-2 p-1 border border-success'>Exam has been deleted successfully!</p>";
            }else{
                echo "<p class='text-danger my-2 p-1 border border-danger'>An error has occured!</p>";
            }
        }
    }elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['new_tt_data'])) {
            include("../../connections/conn1.php");
            $new_tt_data = $_POST['new_tt_data'];
            if (strlen($new_tt_data) > 0) {
                $new_tt_data = json_decode($new_tt_data);
                $block_tt = [];
                // get all classes
                $all_classes = [];
                
                for ($index=0; $index < count($new_tt_data); $index++) { 
                    $in_class = $new_tt_data[$index]->classes;
                    for ($index2=0; $index2 < count($in_class); $index2++) { 
                        $classname = trim($in_class[$index2]->classname);
                        $present = checkPresnt($all_classes,$classname);
                        if ($present == 0) {
                            array_push($all_classes,$classname);
                        }
                    }
                }
                sort($all_classes);

                // loop through the classes as you loop through the block timetable to get class timetables
                $class_timetables = [];
                for ($index=0; $index < count($all_classes); $index++) { 
                    $class_array = ["classname" => $all_classes[$index],"daysoftheweek" => []];
                    for ($index2=0; $index2 < count($new_tt_data); $index2++) {
                        $Day = ["Day" => $new_tt_data[$index2]->Day,"lessons" => []];
                        $classes = $new_tt_data[$index2]->classes;
                        for ($index3=0; $index3 < count($classes); $index3++) { 
                            $classname = $classes[$index3]->classname;
                            $lessons = $classes[$index3]->lessons;
                            if ($classname == $all_classes[$index]) {
                                for ($index4=0; $index4 < count($lessons); $index4++) { 
                                    array_push($Day['lessons'],$lessons[$index4]);
                                }
                            }
                        }
                        array_push($class_array['daysoftheweek'],$Day);
                    }
                    array_push($class_timetables,$class_array);
                }
                $class_timetable = json_encode($class_timetables);
                // echo json_encode($new_tt_data);

                
                // change the block timetable to class timetable
                // we want to save the metadata
                $timetable_id = $_SESSION['timetable_id'];
                $select = "SELECT * FROM `timetable_req` WHERE `ids` = '".$timetable_id."'";
                $stmt = $conn->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $timetable_loc = "";
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $timetable_loc = $row['return_json'];
                    }
                }
                if (strlen($timetable_loc) > 0) {
                    if (strlen($timetable_loc)>0) {
                        if (!is_dir($timetable_loc)) {
                            if (file_exists($timetable_loc)) {
                                // chmod($timetable_loc,0755);
                            }
                            if($myfile = fopen($timetable_loc, "r")){
                                $read = fread($myfile,filesize($timetable_loc));
                                //read the data as a json file
                                $jsonreaddata = json_decode($read);
                                $metadata = "{\"metadata\":".json_encode($jsonreaddata->metadata).",\"timetables\":[{\"blocktimetable\":".json_encode($new_tt_data)."},{\"classtimetable\":".$class_timetable."}]}";
                                // echo $metadata;
                                // write on the file
                                $parent_folder = explode("/",$timetable_loc);
                                $parent_locale = "";
                                for ($ind=0; $ind < count($parent_folder)-1; $ind++) { 
                                    $parent_locale.="/".$parent_folder[$ind];
                                }
                                // check the ownership of the folder that the timetable will be placed
                                // chmod($parent_locale,0777);
                                $write = fopen($timetable_loc,"w");
                                $writer = fwrite($write,$metadata);
                                if($writer > 0){
                                    echo "<p class='text-success'>Timetable data has been changed successfully!</p>";
                                }else{
                                    echo "<p class='text-danger'>An error occured while updating, please try again later!</p>";
                                }
                                fclose($write);
                            }else {
                                echo "<p id='error_msg_timetabled'>Cannot read the file!</p>";
                            }
                        }else {
                            echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                        }
                    }else {
                        echo "<p id='error_msg_timetabled' class='red_notice'>File not found!</p>";
                    }
                }else{
                    echo "<p class='text-danger'>You cannot customize the timetable at the moment because its file path is invalid!</p>";
                }
            }else{
                echo "<p class='text-danger'>We cannot save the timetable at the moment because of lack of enough information accompanied with it.</p>";
            }
        }elseif (isset($_POST['edit_another_user'])) {
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $role_name = $_POST['role_name'];
            $role_values = $_POST['role_values'];
            $update = "UPDATE `settings` set `valued` = ? WHERE `sett` = 'user_roles'";
            $stmt = $conn2->prepare($update);
            $stmt->bind_param("s",$role_values);
            if($stmt->execute()){
                // update everywhere to the school users where the role is available
                $schcodes = $_SESSION['schcode'];
                $update = "UPDATE `user_tbl` SET `auth` = ? WHERE `auth` = ? AND `school_code` = ?";
                $stmt = $conn->prepare($update);
                $role_name = $_POST['role_name'];
                $old_role_name = $_POST['old_role_name'];
                $stmt->bind_param("sss",$role_name,$old_role_name,$schcodes);
                if($stmt->execute()){
                    // create the log text
                    $log_text = "Role \"".(($role_name))."\" has been updated successfully!";
                    log_academic($log_text);
                    echo "<p class='text-success'>You have successfully updated information!</p>";
                }else{
                    echo "<p class='text-danger'>An error has occured during update out</p>";
                }
            }else{
                echo "<p class='text-danger'>An error has occured during update</p>";
            }
        }elseif (isset($_POST['delete_roles'])) {
            include("../../connections/conn1.php");
            include("../../connections/conn2.php");
            $delete_roles = $_POST['delete_roles'];
            $schcodes = $_SESSION['schcode'];
            $select = "SELECT * FROM `user_tbl` WHERE `auth` = ? AND `school_code` = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("ss",$delete_roles,$schcodes);
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            $raw_data = $_POST['raw_data'];
            // echo $raw_data;
            if ($rnums < 1) {
                // delete the record
                $update = "UPDATE `settings` set `valued` = ? WHERE `sett` = 'user_roles'";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$raw_data);
                if($stmt->execute()){
                    echo "<p class='text-success'>Updates done successfully!</p>";
                    
                    // create the log text
                    $log_text = "Role has been deleted successfully!";
                    log_academic($log_text);
                }else {
                    echo "<p class='text-danger'>An error occured during update!</p>";
                }
            }else{
                echo "<p class='text-danger'>Cannot delete role because one or more users in your school is assigned the role!</p>";
            }
        }
    }

    function folder_exist($folder)
        {
            // Get canonicalized absolute pathname
            $path = realpath($folder);

            // If it exist, check if it's a directory
            if($path !== false AND is_dir($path))
            {
                // Return canonicalized absolute pathname
                return $path;
            }

            // Path/folder does not exist
            return false;
        }

    function getClsCode($class,$clscode){
        for ($index=0; $index < count($clscode); $index++) { 
            if (strpos($clscode[$index],$class) == true) {
                return explode(":",$clscode[$index])[0];
            }
        }
        return $class;
    }
    
    function getExamDate($exam_id,$conn2){
        $select = "SELECT `end_date` FROM `exams_tbl` WHERE `exams_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$exam_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            if ($row = $res->fetch_assoc()) {
                return $row['end_date'];
            }
        }
        return date("Y-m-d");

    }
    function getDatabase($conn){
        $select = "SELECT `database_name` from `school_information` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $dbname = $_SESSION['schoolcode'];
        $stmt->bind_param("s",$dbname);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $dbnames = $row['database_name'];
                return $dbnames;
            }
        }
        return "nulldb";
    }
    
    function getSchid($conn){
        $select = "SELECT `sch_id` from `school_information` WHERE `school_code` = ?";
        $stmt = $conn->prepare($select);
        $dbname = $_SESSION['schoolcode'];
        $stmt->bind_param("s",$dbname);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $dbnames = $row['sch_id'];
                return $dbnames;
            }
        }
        return "0";
    }
    function getSubjectDetails($conn2,$subject_id){
        $select = "SELECT * FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $returndata = [];
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $subject_name = strlen(trim($row['display_name'])) > 0 ? $row['display_name']:$row['subject_name'];
                $subject_name = ucwords(strtolower($subject_name));
                array_push($returndata,$subject_name,ucwords(strtolower($row['timetable_id'])));
            }
        }
        return $returndata;

    }
    function getTeacher($subid,$myclass,$conn2,$conn){
        $select = "SELECT `teachers_id` FROM `table_subject` WHERE `teachers_id` LIKE ? AND `subject_id` = ?";
        $sub_class = "%:".$myclass.")%";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$sub_class,$subid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $subjectlist = $row['teachers_id'];
                $exp_lst = explode("|",$subjectlist);
                for ($index=0; $index < count($exp_lst); $index++) {
                    $mycl = ":$myclass)";
                    if (strpos($exp_lst[$index],$mycl) == true) {
                        $strdata = $exp_lst[$index];
                        $trdat = substr($strdata,1,(strlen($strdata)-1));
                        $exploded = explode(":",$trdat);
                        $trnames = getTeacherName($conn,$exploded[0]);
                        return $trnames;
                    }
                }
            }
        }
        return "N/A";
    }
    function getTeacherIdd($subid,$myclass,$conn2,$conn){
        $select = "SELECT `teachers_id` FROM `table_subject` WHERE `teachers_id` LIKE ? AND `subject_id` = ?";
        $sub_class = "%:".$myclass.")%";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$sub_class,$subid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $subjectlist = $row['teachers_id'];
                $exp_lst = explode("|",$subjectlist);
                for ($index=0; $index < count($exp_lst); $index++) {
                    $mycl = ":$myclass)";
                    if (strpos($exp_lst[$index],$mycl) == true) {
                        $strdata = $exp_lst[$index];
                        $trdat = substr($strdata,1,(strlen($strdata)-1));
                        $exploded = explode(":",$trdat);
                        return $exploded[0];
                    }
                }
            }
        }
        return "0";
    }
    function getTeacherName($conn,$tr_id){
        $schoolcode = $_SESSION['schoolcode'];
        $select = "SELECT * FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ss",$schoolcode,$tr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if ($row['gender'] == "F") { 
                    return "Mrs. ".ucwords(strtolower($row['fullname']));
                }elseif($row['gender'] == "M") {
                    return "Mr. ".ucwords(strtolower($row['fullname']));
                }
            }
        }
        return "Null";
    }
    
    function getTeachercode($conn,$tr_id){
        $schoolcode = $_SESSION['schoolcode'];
        $select = "SELECT `nat_id` FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ss",$schoolcode,$tr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                // $natid = $row['nat_id'];
                return rand(100,999);
            }
        }
        return "AAAA";
    }
    function trCode($text){
        $arr = str_split($text);
        $str = "";
        for ($i=0; $i < count($arr); $i++) { 
            $str.= encods($arr[$i]);
        }
        return $text;
    }
    function encods($char){
        if ($char == '1') {
            return "A";
        }elseif ($char == '2') {
            return "B";
        }elseif ($char == '3') {
            return "C";
        }elseif ($char == '4') {
            return "D";
        }elseif ($char == '5') {
            return "E";
        }elseif ($char == '6') {
            return "F";
        }elseif ($char == '7') {
            return "G";
        }elseif ($char == '8') {
            return "H";
        }elseif ($char == '9') {
            return "I";
        }elseif ($char == '0') {
            return "J";
        }
    }
    function checkExamRecorde($conn2,$exam__id,$subject_id,$student__id){
        $select = "SELECT * FROM `exam_record_tbl` WHERE `exam_id` = ? AND `student_id` = ? AND `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("sss",$exam__id,$student__id,$subject_id);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;
        if ($rnum > 0) {
            return 1;
        }
        return 0;
    }
    function arrayCounter($array_to_count,$count_from){
        if (count($array_to_count) > 0 && count($array_to_count) > $count_from) {
            $totals = 0;
            for ($index=$count_from; $index < count($array_to_count); $index++) { 
                $totals+=$array_to_count[$index];
            }
            return $totals;
        }else {
            return 0;
        }
    }
    function rBkts($string){
        if (strlen($string)>1) {
            return substr($string,1,strlen($string)-2);
        }else {
            return $string;
        }
    }
    function getSubjectClassArr($subjectid,$conn2){
        $select = "SELECT `classes_taught` FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subjectid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            if ($row = $res->fetch_assoc()) {
                return explode(",",$row['classes_taught']);
            }
        }
        return [];
    }
    function getSubjectName($subjectid,$conn2){
        $select = "SELECT * FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subjectid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            if ($row = $res->fetch_assoc()) {
                return strlen(trim($row['display_name'])) > 0 ? trim($row['display_name']) : $row['subject_name'];
                // return $row['subject_name'];
            }
        }
        return "Null";
    }
    function getSubjectTT($subjectid,$conn2){
        $select = "SELECT `timetable_id` FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subjectid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            if ($row = $res->fetch_assoc()) {
                return $row['timetable_id'];
            }
        }
        return "Null";
    }
    function getExamClasses($conn2,$exam_id){
        $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '".$exam_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $classes = $row['class_sitting'];
                // echo $classes." ino ";
                if (strlen($classes) > 2) {
                    $my_classes = substr($classes,1,-1);
                    return explode(",",$my_classes);
                }
            }
        }
        return [];
    }
    function isJson_report($string)
    {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    function getSubjectClasses($subjectid,$conn2){
        $select = "SELECT `classes_taught` FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subjectid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            if ($row = $res->fetch_assoc()) {
                return $row['classes_taught'];
            }
        }
        return "Null";
    }
    function checkPresnt($array, $string){
        for ($i=0; $i < count($array); $i++) { 
            if ($string == $array[$i]) {
                return 1;
                break;
            }
        }
        return 0;
    }
    function checkPresntContain($array, $string){
        for ($i=0; $i < count($array); $i++) {
            if (strlen($array[$i]) > 0) {
                if (str_contains($string,$array[$i]) == 1) {
                    return 1;
                    break;
                }
            }
        }
        return 0;
    }
    function getSubjectsTaught($id,$conn){
        $select = "SELECT `subject_id`,`subject_name`,`timetable_id` FROM `table_subject` WHERE `teachers_id` like ?";
        $parse = "%(".$id.":%";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s",$parse);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums>0) {
            $subjects="";
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $xs=0;
                while ($row=$result->fetch_assoc()) {
                    $xs++;
                    if ($xs==1) {
                    $subjects.=$row['timetable_id'];
                    }else {
                        $subjects.=", ".$row['timetable_id'];
                    }
                }
            }
            return $subjects;
        }else {
            return  "none";
        }
        
    }

    function isPresent($subjectname, $conn2){
        $select = "SELECT * FROM `table_subject` WHERE `subject_name` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subjectname);
        $stmt->execute();
        $stmt->store_result();
        $rnums = $stmt->num_rows;
        if ($rnums>0) {
            return true;
        }else {
            return false;
        }
    }
    function getNameWithId($id){
        include("../../connections/conn1.php");
        $select = "SELECT `fullname`,`user_id` FROM `user_tbl` WHERE `school_code` = ? AND `user_id` = ?";
        $stmt = $conn->prepare($select);
        $schoolcode =$_SESSION['schoolcode'];
        $stmt->bind_param("ss",$schoolcode,$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $trname = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $trname = $row['fullname'].",".$row['user_id'];
            }
            return $trname;
        }
        return "";
        $stmt->close();
        $conn->close();
    }
    function getExamName($examid,$conn2){
        $select = "SELECT `exams_name` FROM `exams_tbl` WHERE `exams_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$examid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return $row['exams_name'];
            }
        }
        return "Null";
    }
    function getStudentName($student_id,$conn2){
        $select = "SELECT `first_name`,`second_name` FROM `student_data` WHERE `adm_no` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                return ucwords(strtolower($row['first_name']." ".$row['second_name']));
            }
        }
        return "Null";
    }
    function log_academic($text){
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
    function className($data){
        $datas = "Grade  ".$data;
        if (strlen($data)>1) {
            $datas = $data;
        }
        return $datas;
    }
    function grade($my_totals){
        if ($my_totals == 4) {
            return "E.E";
        }elseif ($my_totals == 3) {
            return "M.E";
        }elseif ($my_totals == 2) {
            return "A.E";
        }elseif ($my_totals == 1) {
            return "B.E";
        }elseif ($my_totals == 0) {
            return  "A";
        }
        return $my_totals."%";
    }
    function getSubMaxMarks($conn2,$subject_id){
        $select = "SELECT `max_marks` FROM `table_subject` WHERE `subject_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sub_max_marks = 0;
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                if (isset($row['max_marks'])) {
                    $sub_max_marks = $row['max_marks'];
                }
            }
        }
        return $sub_max_marks;
    }
    function getSubjectGrades($conn2,$subject_id){
        $select = "SELECT * FROM `table_subject` WHERE `subject_id` = '".$subject_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['grading'];
        }
        return "[]";
    }

    function getStudSitting($conn2,$classes){
        // get the students in the different classes and use their students ids
        // split the students class
        $class_trim = explode(",",substr($classes,1,strlen($classes)-2));
        $classes_sitting = [];
        for ($index=0; $index < count($class_trim); $index++) { 
            // create the array json to store this data
            // get the classnames
            $students = [];
            $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$class_trim[$index]."'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    array_push($students,$row['adm_no']);
                }
            }
            $one_class = array("classname" => $class_trim[$index],"classlist" => $students);
            // array_push($one_class,array("classlist"=>$students));
            array_push($classes_sitting,$one_class);
        }
        return $classes_sitting;
    }
?>