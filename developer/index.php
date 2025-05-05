<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="/sims/assets/CSS/font-awesome/css/all.css">
    <link rel="shortcut icon" href="../images/ladybird.png" type="image/x-icon">
    <title>Developer</title>
</head>
<body>
    <div class="mainpage">
        <div class="top_bar">
            <div class="logo">
                <img src="../images/ladybird.png" alt="Ladybird logo">
                <h3> Ladybird <i class = "fa fa-code"></i> Developer</h3>
            </div>
            <div class="side_menu">
                <p id="return_home">Return to mainpage</p>
            </div>
        </div>
        <div class="middles_section">
            <div class="sign-in-window">
            <h2>Developer Login</h2>
            <p class='ft-sz-12px col-gray'>To be connected with us please login with your username and password. <br>If you are not a developer <a href="#" id="home_runner" class="insite">return to homepage.</a></p>
                <form  >
                    <div class="title">
                        <h3>Sign In</h3>
                    </div>
                    <div class="dev-cont">
                        <label for="username">Username: <br></label>
                        <input type="text" name="username" id="username" placeholder="Enter username">
                    </div>
                    <div class="dev-cont">
                        <label for="password">Password <br></label>
                        <input type="password" name="password" id="password" placeholder = "Enter Password">
                    </div>
                    <div class="dev-cont">
                        <p id="login-err-handler"></p>
                    </div>
                    <div class="dev-cont">
                        <button class="sign-in-btn" id="sign-in-btn" type="button">Sign in</button>
                        <img class="hide" src="../images/ajax_clock_small.gif" id="clock-login"  >
                    </div>
                </form>
            </div>
        </div>
        <div class="footer">
        </div>
    </div>
    <script src="../assets/JS/functions.js"></script>
    <script src="assets/js/index.js"></script>
</body>
</html>