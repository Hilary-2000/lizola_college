<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=yes">
    <link rel="stylesheet" href="assets/CSS/homepage.css">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="shortcut icon" href="images/ladybird.png" type="image/x-icon">
    <title>Ladybird SMIS</title>
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-243578000-1"></script> -->
    <!-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-243578000-1');
    </script> -->

    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-K5H4YCK02K"></script> -->
    <!-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-K5H4YCK02K');
    </script> -->

</head>
<body>
    <div class="mainpage">
        <div class="top">
            <div class="left">
                <div class="iconNname" id="icon_age">
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
            <div class="right">
                <div class="login">
                    <h2>Login</h2>
                    <div class="datacollect" >
                        <div class="conts">
                            <div class="notify">
                                <p>Provide your unique username to proceed!</p>
                                <p>username should <u><strong>NOT</strong></u> be <u><strong>shared</strong></u> with anyone else. <br>Its private</p>
                            </div>
                        </div>
                        <div class="conts">
                            <p id="err"></p>
                        </div>
                        <div class="conts">
                            <input type="text" style="background-color: white;" name="username" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="conts">
                            <button id="submitUname" type = "button">Submit</button>
                            <p>Don`t have an account ? <a href="#">Learn more</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>Copyright © LadyBird School MIS 2020 - <?php echo date("Y");?></p>
        </div>
    </div>
    <div class="loadwindow hide" id="loadings">
        <div class="loadingcontents">
            <img src="images/load2.gif" alt="loading">
        </div>
    </div>
    <script src="assets/JS/functions.js"></script>
    <script>
        cObj("submitUname").onclick = function () {
            let username = valObj("username");
            if(username.length>1){
                grayBorder(cObj("username"));
                cObj("err").innerHTML = "<p class='data' style='color:green;text-align:center;'></p>"
                let datapass = "?log=true&username="+valObj("username");
                sendData1('GET','login/login.php',datapass,cObj("err"));
                 setTimeout(() => {
                     var timeout = 0;
                     var ids = setInterval(() => {
                         timeout++;
                         //after two minutes of slow connection the next process wont be executed
                         if (timeout==1200) {
                             stopInterval(ids);                        
                         }
                         if (cObj("loadings").classList.contains("hide")) {
                             if(cObj("err").innerText.length==0){
                                 redirect("loginsch.php");
                             }
                             stopInterval(ids);
                         }
                     }, 100);
                 }, 200);
                 setTimeout(() => {
                 }, 100);
            }else{
                redBorder(cObj("username"));
                cObj("err").innerHTML = "<p class='data' style='color:rgb(121, 19, 19);text-align:center;'>Enter your username to proceed!</p>"
            }
        }
        cObj("username").addEventListener("keydown", function (e) {
            if (e.key == "Enter") {
                cObj("submitUname").click();
            }
        });
        cObj("icon_age").onclick = function (){
            window.location = "https://ladybirdsmis.com/";
        }
    </script>
</body>

</html>