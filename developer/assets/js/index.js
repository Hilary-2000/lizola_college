

cObj("return_home").addEventListener("click",returnHome);
function returnHome() {
    window.location = "/sims/";
}
cObj("home_runner").addEventListener("click",returnHome);

//submit btn during login
cObj("sign-in-btn").addEventListener("click",checkUserDetails);
function checkUserDetails() {
    //get the values and if there are any blank spaces;
    var err = 0;
    err+=checkBlank("username");
    err+=checkBlank("password");
    if (err == 0) {
        cObj("login-err-handler").innerHTML = "";
        var datapass = "login-system=true&username="+cObj("username").value+"&password="+cObj("password").value;
        sendDataPost("POST","assets/ajax/developer.php",datapass,cObj("login-err-handler"),cObj("clock-login"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("clock-login").classList.contains("hide")) {
                    if (cObj("login-err-handler").innerText.length == 0) {
                        window.location = "dashboard/";
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("login-err-handler").innerHTML = "<p class='red_notice'>Please fill all the fields to proceed!</p>";
    }
}