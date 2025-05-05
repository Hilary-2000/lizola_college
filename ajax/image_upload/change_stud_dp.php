<?php

session_start();
//WHAT DOES THIS FILE RECIEVE
// var_dump($_FILES);
date_default_timezone_set('Africa/Nairobi');

//check if the folder exists
if (!folder_exist("images/students_profiles")) {
    mkdir("../../images/students_profiles");
    mkdir("../../images/students_profiles/".$_SESSION['databasename']."");
    mkdir("../../images/students_profiles/".$_SESSION['databasename']."/".$_POST['admission_no']);

    chmod("../../images/students_profiles",0777);
    chmod("../../images/students_profiles/".$_SESSION['databasename']."",0777);
    chmod("../../images/students_profiles/".$_SESSION['databasename']."/".$_POST['admission_no'],0777);
}
$uploadOk = 1;
foreach ($_FILES["myFiles"]["tmp_name"] as $key => $value) {
    $targetpath = "../../images/students_profiles/".$_SESSION['databasename']."/".$_POST['admission_no']."/".basename($_FILES["myFiles"]["name"][$key]);
    $imageFileType = strtolower(pathinfo($targetpath,PATHINFO_EXTENSION));
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
    }
    if (!file_exists($targetpath) && $uploadOk == 1) {
        move_uploaded_file($value,$targetpath);
      }else {
          echo "Not uploaded!<br>";
      }
    $targetpath = substr($targetpath,6);
    $_SESSION['imagepath1'] = $targetpath;
    // echo $targetpath;
    // include the connection
    include_once("../../connections/conn2.php");
    // delete the files that are existing
    $select = "SELECT * FROM `student_data` WHERE `adm_no` = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s",$_POST['admission_no']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            // delete the file in that are
            if (file_exists("../../".$row['student_image'])) {
                unlink("../../".$row['student_image']);
            }
        }
    }

    $update = "UPDATE `student_data` SET `student_image` = ? WHERE `adm_no` = ?";
    $stmt = $conn2->prepare($update);
    $stmt->bind_param("ss",$targetpath,$_POST['admission_no']);
    $stmt->execute();
    // change the file permission
    // echo $targetpath;
    // include_once("../../images/students_profiles/testimonytbl1/1/20220923_134750.jpg");
    chmod("../../".$targetpath,0777);
}



  /**
 * Checks if a folder exist and return canonicalized absolute pathname (long version)
 * @param string $folder the path being checked.
 * @return mixed returns the canonicalized absolute pathname on success otherwise FALSE is returned
 */
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