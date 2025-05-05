<?php
session_start();
//WHAT DOES THIS FILE RECIEVE
var_dump($_FILES);
date_default_timezone_set('Africa/Nairobi');

//check if the folder exists
if (!folder_exist("images/profile_pics")) {
    mkdir("../../images/profile_pics");
    chmod("../../images/profile_pics",0777);
    mkdir("../../images/profile_pics/".$_SESSION['databasename']."");
    chmod("../../images/profile_pics/".$_SESSION['databasename']."",0777);
    mkdir("../../images/profile_pics/".$_SESSION['databasename']."/".$_SESSION['userids']);
    chmod("../../images/profile_pics/".$_SESSION['databasename']."/".$_SESSION['userids'],0777);
}
$uploadOk = 1;
foreach ($_FILES["myFiles"]["tmp_name"] as $key => $value) {
    $targetpath = "../../images/profile_pics/".$_SESSION['databasename']."/".$_SESSION['userids']."/".basename($_FILES["myFiles"]["name"][$key]);
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
    chmod($targetpath,0777);
    
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