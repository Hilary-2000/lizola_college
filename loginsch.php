<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login || <?php if(isset($_SESSION['schname'])){ echo $_SESSION['schname']; }else{  
        echo "School name";
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: login.php');
        ;}?></title>
    <link rel="stylesheet" href="assets/CSS/homepage.css">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="shortcut icon" href="images/ladybird.png" type="image/x-icon">
</head>
<body>
    <div class="mainpagePass">
        <div class="top">
            <div class="left">
                <div class="iconNname">
                    <img class="icons" src="images/ladybird.png" alt="icon">
                    <h3>ladybird School MIS</h3>
                </div>
                <div class="content">
                    <h3>Welcome to LadyBird School MIS</h3>
                    <p>An all-in-one school management information system with a suite of portals for parents, students and staff, giving your school full control of all academic, finance, wellbeing, and administrative information.</p>
                </div>
                <div class="content contacts">
                    <h3>Contact us</h3>
                    <div class="contact">
                        <p title="Click to call us">Phone 1: <a href="tel://+254743551250">+254743551250</a></p>
                        <p title="Click to call us">Phone 2: <a href="tel://+254783840449">+254783840449</a></p>
                        <p>Email: <a href="mailto:ladybirdsmis@gmail.com">ladybirdsmis@gmail.com</a></p>
                    </div>
                </div>
            </div>
            <div class="right" id="loginwin">
                <div class="login">
                    <div class="school_logo">
                        <img src='<?php echo $_SESSION['school_profile_image']; ?>' alt="popop">
                    </div>
                    <div class="conts">
                        <h2><?php if(isset($_SESSION['schname'])){ echo $_SESSION['schname'];}else{ echo "School name";}?></h2>
                    </div>
                    <div class="datacollect">
                        <div class="conts">
                            <input type="text" name="uname" id="uname" hidden value= <?php if(isset($_SESSION['unames'])){ echo $_SESSION['unames']; }else{ echo "null";} ?>>
                            <p><strong>School motto:</strong> <?php if(isset($_SESSION['smotto'])){ echo $_SESSION['smotto']; }else{ echo "School name";}?></p>
                        </div>
                        <div class="conts ">
                            <p><?php 
                                $date = date("H");
                                if($date<=10){
                                    echo "Good morning";
                                }elseif ($date>10 && $date<=16) {
                                    echo "Hello";
                                }elseif ($date > 16) {
                                    echo "Good evening";
                                }
                            ?> <strong><?php if(isset($_SESSION['username'])){
                                $salute = "";
                                if($_SESSION['gender']=='M'){
                                    $salute = 'Mr. ';
                                }elseif ($_SESSION['gender'] == 'F') {
                                    $salute = 'Mrs. ';
                                }else{
                                    $salute = "";
                                }
                                $named = explode(" ",$_SESSION['username']);
                                echo $salute.$named[0];
                            }else {
                                echo "Username ";
                            }?>,</strong></p>
                            <div class="notify">
                                <p class="smallfonts">  <small>Proceed if that`s your correct <strong>name</strong> and <strong>School name.</strong></small> </p>
                                <p class="smallfonts"> <small> If not, please contact your admin or re-enter your username </small></p>
                            </div>
                        </div>
                        <div class="inforwin">
                            <div class="conts">
                                <label for="pass">Enter your password:</label>
                                <p id = "errors"></p>
                                <input type="password" name="pass" id="pass" placeholder="Enter password">
                            </div>
                            <div class="conts">
                                <button type="button" id="subpwd">Login!</button>
                            </div>
                        </div>
                        <div class="conts">
                            <p><a href="login.php">Re-enter username</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="anonymus hide" id="anonymus" title="Click to dismis">
    </div>
    <script src="assets/JS/functions.js"></script>
    <script>
        cObj("subpwd").onclick = function () {
            let passwds = valObj("pass");
            if(passwds.length>1){
                cObj("errors").innerHTML = "<p class='data' style='color:red;display:none;'>done</p>"
                grayBorder(cObj("pass"));
                let datapas = "?password="+passwds+"&usernames="+valObj("uname");
                sendData('GET','login/login.php',datapas,cObj("errors"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout==1200) {
                            stopInterval(ids);                        
                        }
                        //set data recieved from the database
                        let fback = cObj("errors").innerText;
                        let substrs = fback.substr(0,7);
                        if(substrs=="Correct"){
                            let datapassed = "?newvalues=true";
                            sendData2("GET","login/changevalues.php",datapassed,cObj("subpwd"),cObj("anonymus"));
                            setTimeout(() => {
                                var timeout = 0;
                                var ids2 = setInterval(() => {
                                    timeout++;
                                    //after two minutes of slow connection the next process wont be executed
                                    if (timeout==1200) {
                                        stopInterval(ids2);                        
                                    }
                                    if (cObj("anonymus").classList.contains("hide")) {
                                        redirect("homepage.php");
                                        stopInterval(ids2);
                                    }
                                }, 100);
                            }, 200);
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            }else{
                cObj("errors").innerHTML = "<p class='data' style='color:red;'>Enter your password to proceed!</p>";
                redBorder(cObj("pass"));
            }
        }
        cObj("pass").addEventListener("keydown", function (e) {
            if (e.key == "Enter") {
                cObj("subpwd").click();
            }
        });

    </script>
</body>
</html>