


document.getElementById("logged_ind").addEventListener("click",loginPage);
function loginPage() {
    //redirect from the current page to the login page
    window.location = "login.php";
}
cObj("login-sch-btn").addEventListener("click",loginPage);
document.getElementById("submit-btn").addEventListener("click",sendRegisterRequest);
function sendRegisterRequest() {
    //get all the information about the user
    var err  = checkErrors();
    if (err == 0) {
        cObj("check_blanks").innerHTML = "";
        var datapass = "?rqst=true&f_name="+cObj("firstName").value+"&lname="+cObj("lastName").value+"&email="+cObj("email_addr").value+"&school_name="+cObj("sch_name").value+"&sch_type="+cObj("sch_type").value+"&phone_no="+cObj("p-number").value;
        sendData1("GET","outer/rqst_user.php",datapass,cObj("check_blanks"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("check_blanks").innerHTML = "";
                    }, 10000);
                    cObj("your-details-id").reset();
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("check_blanks").innerHTML = "<p style='color:red'>Fill all the fields colored with red border!</p>";
    }
}

function checkErrors() {
    var det = document.getElementsByClassName("det-s");
    var err = 0;
    for (let index = 0; index < det.length; index++) {
        const element = det[index];
        if (element.value.length == 0) {
            err++;
            redBorder(element);
        }else{
            grayBorder(element);
        }
    }
    return err;
}
cObj("home-return").addEventListener("click",returnHome);
function returnHome() {
    hideallWindows();
    cObj("home-paged").classList.remove("hide");
}
cObj("reg-sch").addEventListener("click",registerPage);
function registerPage() {
    hideallWindows();
    cObj("register_school").classList.remove("hide");
}
cObj("aboutladybird").addEventListener("click",aboutUs);
function aboutUs(params) {
    hideallWindows();
    cObj("about-us").classList.remove("hide");
}
cObj("hom-sch-btn").addEventListener("click",returnHome);
function hideallWindows() {
    var allwins = document.getElementsByClassName("win");
    for (let int = 0; int < allwins.length; int++) {
        const element = allwins[int];
        element.classList.add("hide");
    }
}
cObj("developer").addEventListener("click",developerPage);
function developerPage() {
    window.location = "developer/";
}