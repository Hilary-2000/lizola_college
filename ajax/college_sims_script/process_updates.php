<?php
/**PROCESS INCOMING UPDATES
 * DOWNLOAD FILES FROM THE ONLINE SERVER
 */
    // $apiEndpoint = "http://192.168.88.237:81/college_sims_script/check_updates.php";
    $apiEndpoint = "https://college.ladybirdsmis.com/college_sims/ajax/college_sims_script/check_updates.php";
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
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'file_updates=true');

    // Execute the cURL session and store the content in $data
    $data = curl_exec($ch);

    // // Close the cURL session
    curl_close($ch);

    
    if (isJson($data)) {
        // echo the reponse
        echo $data;
        $data = json_decode($data,true);
        foreach ($data as $key => $value) {
            copyFileContents($value['new_file_location'], $value['replace_file']);
        }
    }

    function isJson($string)
    {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    // copy of file contents
    function copyFileContents($sourceFile, $destinationFile) {
        // Extract the directory path from the destination file
        $destinationDirectory = dirname($destinationFile);
    
        // Create the directory if it doesn't exist
        if (!is_dir($destinationDirectory)) {
            // Create the directory recursively
            if (!mkdir($destinationDirectory, 0777, true)) {
                return false; // Unable to create directory
            }
        }
    
        // Open the source file for reading
        $sourceHandle = fopen($sourceFile, "r");
        if ($sourceHandle === false) {
            echo "unable to open file";
            return false; // Unable to open source file
        }
        
        // Open or create the destination file for writing
        $destinationHandle = fopen($destinationFile, "w+");
        if ($destinationHandle === false) {
            fclose($sourceHandle);
            return false; // Unable to open or create destination file
        }
        
        // Read from source and write to destination
        while (!feof($sourceHandle)) {
            $buffer = fread($sourceHandle, 8192);
            fwrite($destinationHandle, $buffer);
        }
        
        // Close file handles
        fclose($sourceHandle);
        fclose($destinationHandle);
        
        echo "Create file successfully.";
        return true; // File copied successfully
    }

?>