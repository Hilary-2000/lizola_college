<?php
// create timetable from here
// read data from the timetable request file

include("../connections/conn1.php");
include("../connections/conn2.php");


// read all the timetable requests made
$select = "SELECT * FROM `timetable_req` WHERE  `status` = 0";
$stmt = $conn->prepare($select);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // read where the file has been stored and create it
        $file_location = $row['req_json'];
        $request_id = $row['ids'];
        $school_id = $row['school_id'];
        $tt_name = $row['tt_name'];
        generateTT($file_location, $request_id,$conn, $school_id, $tt_name);
        // break;
    }
}



/**
 * Summary of generateTT
 * @param string $tt_request_locale
 * @param string $request_id
 * @return json
 */ 

function generateTT($tt_request_locale,$request_id,$conn, $school_id,$tt_name){
    // check if the file exists in php
    if (file_exists($tt_request_locale)) {
      // when done generating the timetable update change its status
      $json_data_request = file_get_contents($tt_request_locale);
      // echo $json_data_request;
      // return "";
      // first check if its a json file
      if (isJson($json_data_request)) {
          // conver to json the we use it to generate the tt
          $time_table_req = json_decode($json_data_request);
          // echo $time_table_req;

          // breakdown the data first
          $time_table_data = breakdown_data($time_table_req);

          // get the days of the week
          $days_of_week = explode(",",$time_table_req->DAY_OF_WEEK);
          $MORNING_HOUR_SUBJECTS = explode(",",$time_table_req->MORNING_HOUR_SUBJECTS);
          $lessons = $time_table_req->NUMBER_OF_LESSONS*1;
          $maximum_repeat = 2;

          // loop through the days of the week and get lessons for each day and each class
          $blocktimetable['blocktimetable'] = [];
          for ($index=0; $index < count($days_of_week); $index++) { 
              // go through each class
              $days_work = array("Day"=>$days_of_week[$index],"classes" => []);
              $classes = $time_table_data[0];
              for ($index2=0; $index2 < count($classes); $index2++) {
                  // get the total number of lessons in a day
                  $a_day_lessons = [];
                  for ($index3=0; $index3 < $lessons; $index3++) {

                      // while the repeat no is zero add the subjects
                      while (true) {
                        $one_lesson = ($index3 < ($lessons / 2) && rand(0,1) == 0) ? getSpecificLesson($MORNING_HOUR_SUBJECTS[rand(0,count($MORNING_HOUR_SUBJECTS)-1)],$time_table_req,$classes[$index2]) : getLesson($time_table_req,$classes[$index2]);
                        // $one_lesson = getLesson($time_table_req,$classes[$index2]);
                        $repeat_no = checkRepeat($a_day_lessons,$one_lesson);
                        if ($repeat_no <= $maximum_repeat) {
                          break;
                        }
                      }
                      // echo $repeat_no." ".$one_lesson."<br>";

                      array_push($a_day_lessons,$one_lesson);
                  }
                  // var_dump($a_day_lessons);
                  $class_data = array("classname" => $classes[$index2],"lessons" => $a_day_lessons);
                  array_push($days_work['classes'],$class_data);
              }
              // check for conflict
              $days_work = checkConflict($days_work,$lessons,$classes,$time_table_req);
              array_push($blocktimetable['blocktimetable'],$days_work);
              // break;
              // break;
          }
          // echo json_encode($blocktimetable);

          // with the block timetable lets create the class timetable
          $classtimetable['classtimetable'] = [];
          for ($index=0; $index < count($time_table_data[0]); $index++) { 
            $class_data = array("classname" => $time_table_data[0][$index], "daysoftheweek" => []);
            for ($index2=0; $index2 < count($days_of_week); $index2++) { 
              // go through the classes and fill all the data
              for ($index3=0; $index3 < count($blocktimetable['blocktimetable']); $index3++) { 
                $Day = $blocktimetable['blocktimetable'][$index3]['Day'];
                $classes = $blocktimetable['blocktimetable'][$index3]['classes'];
                if ($Day == $days_of_week[$index2]) {
                  for ($index4=0; $index4 < count($classes); $index4++) { 
                    // check if its the current class
                    $classname = $classes[$index4]['classname'];
                    $lessons = $classes[$index4]['lessons'];
                    if ($classname == $time_table_data[0][$index]) {
                      $day_data = array("Day" => $Day,"lessons" => $lessons);
                      array_push($class_data['daysoftheweek'],$day_data);
                    }
                  }
                }
              }
            }
            array_push($classtimetable['classtimetable'],$class_data);
          }
          // echo json_encode($classtimetable);

          // create timetables
          $timetables['timetables'] = [];
          array_push($timetables['timetables'],$blocktimetable,$classtimetable);
          // echo json_encode($timetables);

          // create classes metadata
          $me_classes['classes'] = [];
          for ($index=0; $index < count($time_table_data[3]); $index++) { 
            $my_class_data = array("classname" => $time_table_data[3][$index][0],"classid" => $time_table_data[3][$index][1]);
            array_push($me_classes['classes'],$my_class_data);
          }

          // teachers data
          $teachers['teachers'] = [];
          for ($index=0; $index < count($time_table_data[2]); $index++) { 
            $tr_data = array("teachername" => $time_table_data[2][$index][1],"teacherid" => $time_table_data[2][$index][0]);
            array_push($teachers['teachers'],$tr_data);
          }

          // get the subjects data
          $subject['subjects'] = [];
          for ($index=0; $index < count($time_table_data[1]); $index++) { 
            $subject_data = array("subjectname" => $time_table_data[1][$index][0], "subject_id" => $time_table_data[1][$index][1]);
            array_push($subject['subjects'],$subject_data);
          }

          $metadata['metadata'] = [];
          array_push($metadata['metadata'],$subject,$me_classes,$teachers);

          // echo json_encode($metadata);
          $final_data = array_merge($metadata,$timetables);
          // echo json_encode($final_data);


          // create a file and write all this data and save it then update the database
          $school_data = school_data($conn,$school_id);
          $folder_location = "/home/hilary/Desktop/timetable/".$school_data['database_name']."/".$tt_name."";
          if (!file_exists($folder_location)) {
            mkdir($folder_location,0777,true);
            chmod($folder_location, 0777);
          }
          
          // create file and add the data
          $file = $folder_location.'/'.$tt_name.'.json';
          $data = json_encode($final_data);
          file_put_contents($file, $data);
          chmod($file, 0777);

          // update the database with the new location
          $update = "UPDATE `timetable_req` SET `return_json` = '".$file."' , `status` = '1' WHERE `ids` = '$request_id'";
          $stmt = $conn->prepare($update);
          if($stmt->execute()){
            echo "Generated successfully!".$tt_name."";
          }else{
            echo "An error occured while generating !".$tt_name."";
          }
      }else {
  
          // update the table request
          $delete = "DELETE FROM `timetable_req` WHERE `ids` = '".$request_id."'";
          $stmt = $conn->prepare($delete);
          $stmt->execute();
  
          // delete the file & folder
          $parent_locale = dirname($tt_request_locale);
          deleteDirectory($parent_locale);
      }
    }else {
  
      // update the table request
      $delete = "DELETE FROM `timetable_req` WHERE `ids` = '".$request_id."'";
      $stmt = $conn->prepare($delete);
      $stmt->execute();
  }

    // include the file request id

}

function checkConflict($days_work,$number_of_lessons,$all_classes,$time_table_data){
  // check if there are conflicts in that day if there are replace them
  $classes = $days_work['classes'];

  // get the classname
  for ($index=0; $index < $number_of_lessons; $index++) {
    // go through the classes
    $teachers = [];
    $teachers_present = [];
    // create arrays for the teachers in every clas
    for ($index2=0; $index2 < count($classes); $index2++) {
      $one_lessons = $classes[$index2]['lessons'][$index];
      // echo $one_lessons.":<br>";
      // break;
      if(strlen($one_lessons) > 0){
        $teachers[explode(" ",$one_lessons)[1]] = 0;
        if (!isPresent($teachers_present,explode(" ",$one_lessons)[1])) {
          array_push($teachers_present,explode(" ",$one_lessons)[1]);
        }
      }
    }

    // echo "<hr style='border:red;'>";
    // if the teachers are present add by how many time
    // echo json_encode($teachers)."<br>";
    for ($index2=0; $index2 < count($all_classes); $index2++) { 
      $one_lessons = $classes[$index2]['lessons'][$index];
      if(strlen($one_lessons) > 0){
        $teachers[explode(" ",$one_lessons)[1]] ++;
  
        $load_breaker = 2000;
  
        // if the teachers are present more than ones there will be a conflict
        if ($teachers[explode(" ",$one_lessons)[1]] > 1) {
          $one_lessons = getLesson($time_table_data,$classes[$index2]['classname']);
          while(isPresent($teachers_present,explode(" ",$one_lessons)[1])){
            $one_lessons = getLesson($time_table_data,$classes[$index2]['classname']);
            $load_breaker --;
            if ($load_breaker == 0) {
              break;
            }
          }
          $days_work['classes'][$index2]['lessons'][$index] = $one_lessons;
        }
      }
      // echo $one_lessons."<br>";
    }
    // if they are present more than one replace the entry by looking for a different teacher for that class
    // echo json_encode($teachers);
    // echo "<hr>";
  }
  // echo json_encode($days_work);
  return $days_work;
}

function school_data($conn,$school_id){
  $select = "SELECT * FROM `school_information` WHERE `sch_id` = '".$school_id."'";
  $stmt = $conn->prepare($select);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result) {
    if ($row = $result->fetch_assoc()) {
      return $row;
    }
  }
  return [];
}

function checkRepeat($lessons,$a_lesson){
  $lesson_count = [];
  for ($index=0; $index < count($lessons); $index++) { 
    $lesson_count[$lessons[$index]] = 1;
  }
  
  for ($index=0; $index < count($lessons); $index++) { 
    $lesson_count[$lessons[$index]] ++;
  }

  return isset($lesson_count[$a_lesson]) ? $lesson_count[$a_lesson] : 1;
}

function getSpecificLesson($lesson_name,$time_table_data,$class){
  $teacher_data = $time_table_data->TEACHERS;
  $all_lessons = [];
  for ($index=0; $index < count($teacher_data); $index++) { 
    $SUBJECTS = $teacher_data[$index]->SUBJECTS;
    $TEACHER_CODE = $teacher_data[$index]->TEACHER_CODE;
    for ($index2=0; $index2 < count($SUBJECTS); $index2++) { 
      $SUBJECT_CODE = $SUBJECTS[$index2]->SUBJECT_CODE;
      $CLASS_TAUGHT = $SUBJECTS[$index2]->CLASS_TAUGHT;
      $SUBNAME = $SUBJECTS[$index2]->SUBNAME;
      if ($lesson_name == $SUBNAME) {
        for ($index3=0; $index3 < count($CLASS_TAUGHT); $index3++) { 
          $CLASSNAME = $CLASS_TAUGHT[$index3]->CLASSNAME;
          $CLASSCODE = $CLASS_TAUGHT[$index3]->CLASSCODE;
          if ($CLASSNAME == $class) {
            $a_lesson = $SUBJECT_CODE." {".$TEACHER_CODE."}";
            // array_push($all_lessons,$a_lesson);
            return $a_lesson;
          }
        }
      }
    }
  }
}

function getLesson($time_table_data, $class){
  $teacher_data = $time_table_data->TEACHERS;
  $all_lessons = [];
  for ($index=0; $index < count($teacher_data); $index++) { 
    $SUBJECTS = $teacher_data[$index]->SUBJECTS;
    $TEACHER_CODE = $teacher_data[$index]->TEACHER_CODE;
    for ($index2=0; $index2 < count($SUBJECTS); $index2++) { 
      $SUBJECT_CODE = $SUBJECTS[$index2]->SUBJECT_CODE;
      $CLASS_TAUGHT = $SUBJECTS[$index2]->CLASS_TAUGHT;
      for ($index3=0; $index3 < count($CLASS_TAUGHT); $index3++) { 
        $CLASSNAME = $CLASS_TAUGHT[$index3]->CLASSNAME;
        $CLASSCODE = $CLASS_TAUGHT[$index3]->CLASSCODE;
        if ($CLASSNAME == $class) {
          $a_lesson = $SUBJECT_CODE." {".$TEACHER_CODE."}";
          array_push($all_lessons,$a_lesson);
        }
      }
    }
  }
  
  $index = rand(0,(count($all_lessons)-1));
  return $all_lessons[$index];
}

function breakdown_data($data){
  // get all classes first
  $all_classes = [];
  $all_classes2 = [];
  $all_teachers = [];
  $teacher_data = $data->TEACHERS;
  for ($index=0; $index < count($teacher_data); $index++) {
      $SUBJECTS = $teacher_data[$index]->SUBJECTS;
      for ($index2=0; $index2 < count($SUBJECTS); $index2++) { 
          $CLASS_TAUGHT = $SUBJECTS[$index2]->CLASS_TAUGHT;
          for ($index3=0; $index3 < count($CLASS_TAUGHT); $index3++) { 
            $CLASSNAME = $CLASS_TAUGHT[$index3]->CLASSNAME;
            $CLASSCODE = $CLASS_TAUGHT[$index3]->CLASSCODE;
            $class_data = [$CLASSNAME,$CLASSCODE];
            if (!isPresent($all_classes,$CLASSNAME)) {
                array_push($all_classes,$CLASSNAME);
                array_push($all_classes2,$class_data);
            }
          }
      }
  }
  // var_dump($all_classes);

  // get all teachers
  $teacher_data = $data->TEACHERS;
  for ($index=0; $index < count($teacher_data); $index++) {
      $NAME = $teacher_data[$index]->NAME;
      $TEACHER_CODE = $teacher_data[$index]->TEACHER_CODE;
      $tr_data = [$TEACHER_CODE,$NAME];
      if (!isPresent($all_teachers,$tr_data)) {
        array_push($all_teachers,$tr_data);
      }
  }

  // var_dump($all_teachers);

  // get all the subjects
  $all_subjects = [];
  $teacher_data = $data->TEACHERS;
  for ($index=0; $index < count($teacher_data); $index++) {
      $SUBJECTS = $teacher_data[$index]->SUBJECTS;
      for ($index2=0; $index2 < count($SUBJECTS); $index2++) { 
          $SUBNAME = $SUBJECTS[$index2]->SUBNAME;
          $SUBJECT_CODE = $SUBJECTS[$index2]->SUBJECT_CODE;
          $subject_data = [$SUBNAME,$SUBJECT_CODE];
          if (!isPresent($all_subjects,$subject_data)) {
              array_push($all_subjects,$subject_data);
          }
      }
  }
  
  sort($all_classes);

  return[$all_classes,$all_subjects,$all_teachers,$all_classes2];
}

function isPresent($array,$entry){
  for ($i=0; $i < count($array); $i++) { 
    if ($entry == $array[$i]) {
      return true;
    }
  }
  return false;
}

function isJson($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}


function deleteDirectory($dir) {
    if (!file_exists($dir)) {
      return true;
    }

    if (!is_dir($dir)) {
      return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
      if ($item == '.' || $item == '..') {
        continue;
      }

      if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
        return false;
      }
    }
    return rmdir($dir);
  }

  

?>