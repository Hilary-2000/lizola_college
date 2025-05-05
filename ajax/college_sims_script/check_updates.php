<?php
    /**THIS IS A PHP SCRIPT TO CHECK FOR THE UPDATES
     * INCASE THERE ARE ANY UPDATES IT SHOULD UPDATE FROM THE CLOUD SERVER
     * CREATE A TABLE IN THE LADYBIRD DATABASE TO GET THE UPDATED FILES.
     */

    if($_SERVER['REQUEST_METHOD'] =='POST'){
        if(isset($_POST['file_updates'])){
            // LOCAL
            $dbname = 'ladybird_smis';
            $hostname = 'localhost';
            $dbusername = 'root';
            $dbpassword = '2000hILARY';
            if(!isset($_SESSION)) {
                session_start(); 
            }
            $conn = new mysqli($hostname, $dbusername, $dbpassword, $dbname);
            // Check connection
            if (mysqli_connect_errno()) {
                die("Failed to connect to MySQL: " . mysqli_connect_error());
                exit();
            }
        
            // if can`t connect
            if ($conn) {
                $select = "SELECT * FROM `updated_files` WHERE `active` = '1'";
                $stmt = $conn->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $file_locations = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $files = $row['changed_files'];
                        if (isJson($files)) {
                            $files = json_decode($files,true);
                            foreach ($files as $key_file => $file) {
                                // download files and store them in the folder it should be in
                                $download_file = $file['download_file'];
                                $replace_file = $file['replace_file'];
                                // echo $download_file." ".$replace_file."<br>";
        
                                // new file location
                                $new_file_location = move_files($download_file);
                                if (!empty($new_file_location)) {
                                    $file_locale = ['new_file_location' => $new_file_location['new_file_location'], "replace_file" => $replace_file];
                                    array_push($file_locations,$file_locale);
                                }
                            }
                        }
        
                        // update and set the updates as done.
                        $update = "UPDATE `updated_files` SET `active` = '0' WHERE `id` = ?";
                        $stmt = $conn->prepare($update);
                        $stmt->bind_param("s",$row['id']);
                        $stmt->execute();
                    }
                }
                echo json_encode($file_locations);
            }
        }
    }

    function copyFileContents($sourceFile, $destinationFile) {
        // Open the source file for reading
        $sourceHandle = fopen($sourceFile, "r");
        if ($sourceHandle === false) {
            return false; // Unable to open source file
        }
        
        // Open the destination file for writing
        $destinationHandle = fopen($destinationFile, "w");
        if ($destinationHandle === false) {
            fclose($sourceHandle);
            return false; // Unable to open destination file
        }
        
        // Read from source and write to destination
        while (!feof($sourceHandle)) {
            $buffer = fread($sourceHandle, 8192);
            fwrite($destinationHandle, $buffer);
        }
        
        // Close file handles
        fclose($sourceHandle);
        fclose($destinationHandle);
        
        return true; // File copied successfully
    }

    function isJson($string)
    {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
    
    function updateFile($localFile, $apiEndpoint, $fileLocation) {
        // Initialize a new cURL session
        $ch = curl_init();

        // Set the URL of the API endpoint
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);

        // Set the option to return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Set the option to send a POST request
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the POST fields
        // curl_setopt($ch, CURLOPT_POSTFIELDS, );
        $file_name_with_extension = basename($localFile);
        $file_name_without_extension = pathinfo($file_name_with_extension, PATHINFO_FILENAME);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'file_updates=true&'.'file_name='.$file_name_without_extension.'&file_location=' . urlencode($fileLocation).'&move_to='.urlencode($localFile));

        // Execute the cURL session and store the content in $data
        $data = curl_exec($ch);

        // // Close the cURL session
        curl_close($ch);

        // echo the reponse
        return $data;
        
    }

    function move_files($file_location){
        // file location 

        // new file name
        $file_name_with_extension = basename($file_location);
        $file_name_without_extension = pathinfo($file_name_with_extension, PATHINFO_FILENAME);
        $new_file_name = $file_name_without_extension.".txt";

        // Check if the file exists
        if (file_exists($file_location)) {
            // The file is valid

            // folder to store the changes
            $upgrades_folder = "/var/www/college_sims/college_sims/ajax/college_sims_script/upgrades";

            // Check if the folder doesn't exist
            if (!is_dir($upgrades_folder)) {
                // Create the folder
                if (!mkdir($upgrades_folder, 0777, true)) {
                    // If mkdir fails, handle the error
                    return [];
                }
            }

            // Define the new file location
            $new_file_location = $upgrades_folder ."/". $new_file_name;

            // Move the file to the upgrades folder
            if (rename($file_location, $new_file_location)) {
                // Return the new file location in the response
                $file_location = "https://college.ladybirdsmis.com/college_sims/ajax/college_sims_script/upgrades/".$new_file_name;
                // echo $file_location."<br>";
                return array('new_file_location' => $file_location);
            }
        }
        return [];
    }
?>