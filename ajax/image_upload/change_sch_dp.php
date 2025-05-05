<?php

session_start();
//WHAT DOES THIS FILE RECIEVE
var_dump($_FILES);
date_default_timezone_set('Africa/Nairobi');

//check if the folder exists
if (!folder_exist("images/sch_profiles")) {
    mkdir("../../images/sch_profiles");
    mkdir("../../images/sch_profiles/".$_SESSION['databasename']."");
}else {
    $folder_path = "../../images/sch_profiles/".$_SESSION['databasename']."";
    $files = glob($folder_path.'/*'); 
   
    // Deleting all the files in the list
    foreach($files as $file) {
        if(is_file($file)){
            // Delete the given file
            unlink($file); 
        }
    }
}
$uploadOk = 1;
foreach ($_FILES["myFiles"]["tmp_name"] as $key => $value) {
    $targetpath = "../../images/sch_profiles/".$_SESSION['databasename']."/".basename($_FILES["myFiles"]["name"][$key]);
    echo $targetpath;
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
    $_SESSION['imagepath2'] = $targetpath;
//change file access permission
    chmod($targetpath,0755);
    
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
