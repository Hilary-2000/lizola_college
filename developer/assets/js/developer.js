
cObj("menu-btn-show").addEventListener("click", showMenu);
function showMenu() {
    //shows the menu window
    cObj("menu-btn-show").classList.add("scale-h");
    setTimeout(() => {
        cObj("menu-btn-show").classList.add("hide");
        cObj("menu-btn-show").classList.remove("scale-h");
        cObj("menu-window").classList.remove("hide");
        cObj("menu-window").classList.add("scale-v");
        setTimeout(() => {
            cObj("menu-window").classList.remove("scale-v");
        }, 300);
    }, 250);
}
cObj("close-menu-win").addEventListener("click",hideMenu);
function hideMenu() {
    cObj("menu-window").classList.add("scale-h");
    setTimeout(() => {
        cObj("menu-window").classList.add("hide");
        cObj("menu-window").classList.remove("scale-h");
        cObj("menu-btn-show").classList.remove("hide");
        cObj("menu-btn-show").classList.add("scale-v");
        setTimeout(() => {
            cObj("menu-btn-show").classList.remove("scale-v");
        }, 300);
    }, 250);
}
cObj("enter-dev-infor").addEventListener("click",registerUser);
function registerUser() {
    //check for blank spaces
    var err = 0;
    err+=checkBlank("FullName");
    err+=checkBlank("main-admin");
    err+=checkBlank("username");
    err+=checkBlank("passcode");
    err+=checkBlank("re-passcode");
    if (err == 0) {
        cObj("err-handler").innerHTML = "";
        if (cObj("passcode").value == cObj("re-passcode").value) {
            cObj("err-handler").innerHTML = "";
            grayBorder(cObj("passcode"));
            grayBorder(cObj("re-passcode"));
            var datapass = "insert_dev=true&fullname="+cObj("FullName").value+"&roles="+cObj("main-admin").value+"&username="+cObj("username").value+"&passcode="+cObj("passcode").value;
            sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("err-handler"),cObj("clock_ajax1"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("clock_ajax1").classList.contains("hide")) {
                        //clear the form
                        cObj("add-user-frm").reset();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            redBorder(cObj("passcode"));
            redBorder(cObj("re-passcode"));
            cObj("err-handler").innerHTML = "<p class='red_notice'>Passwords don`t match!</p>";
        }
    }else{
        cObj("err-handler").innerHTML = "<p class='red_notice'>Fill all fields marked with a red border!</p>";
    }
}

//show more options for the user
cObj("profile_user").addEventListener("click", showProfile);

function showProfile() {
    if (cObj("more-opt-win").classList.contains("hide")) {
        cObj("more-opt-win").classList.remove("hide");
        cObj("more-opt-win").classList.add("slideDown")
        setTimeout(() => {
            cObj("more-opt-win").classList.remove("slideDown");
        }, 1000);
    }else{
        hideProfile();
    }
}
function hideProfile() {
    cObj("more-opt-win").classList.add("slideUp");
    setTimeout(() => {
        cObj("more-opt-win").classList.add("hide");
        cObj("more-opt-win").classList.remove("slideUp");
    }, 900);
    
}
//logout
cObj("logout-in").addEventListener("click",logout);
function logout() {
    var datapass = "logout=true";
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass);
    window.location = "/sims/developer/";
    
}
//get active schools
getDahboardData();
function getDahboardData() {
    //get active schools
    var datapass = "getSchools=true";
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("school_present"));
    setInterval(() => {
        var datapass = "getSchools=true";
        sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("school_present"));
    }, 30000);

    //get the number of user in the system
        var datapass = "getUserNUmber=true";
        sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("user_present"));
    setInterval(() => {
        var datapass = "getUserNUmber=true";
        sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("user_present"));
    }, 20000);

    setInterval(() => {
        datapass = "getactiveusers=true";
        sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("active_user"));
    }, 2000);
}
//show the register developer panel
cObj("reg-dev-btn").addEventListener("click",showDevWIn);
function showDevWIn() {
    hideAllWindows();
    cObj("reg-dev").classList.remove("hide");
}
//show all schools window
cObj("enrol-sch-btn").addEventListener("click",showSchWIn);
function showSchWIn() {
    hideAllWindows();
    cObj("sch-wind").classList.remove("hide");
    //show schools information
    var datapass = "getSchoolInformation=true";
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("sch-inform"),cObj("school_loads"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("school_loads").classList.contains("hide")) {
                //clear the form
                //add the transition animation
                var sch_informations = document.getElementsByClassName("sch_informations");
                for (let index = 0; index < sch_informations.length; index++) {
                    const element = sch_informations[index];
                    element.addEventListener("click",viewschDetails);
                }
                //DISPLAY ALL USERS IN THE SYSTEM
                var user_sch = document.getElementsByClassName("user-sch");
                for (let index = 0; index < user_sch.length; index++) {
                    const element = user_sch[index];
                    element.addEventListener("click",displayUsers)
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function hideAllWindows() {
    var windows = document.getElementsByClassName("win-pin");
    for (let index = 0; index < windows.length; index++) {
        const element = windows[index];
        element.classList.add("hide");
    }
}
cObj("close-win-field").addEventListener("click",hideChangeField);
function hideChangeField() {
    cObj("change-field-win").classList.add("hide");
}

function viewschDetails() {
    //push away the table holder
    cObj("sch-tbl-info").classList.add("animate-out");
    setTimeout(() => {
        cObj("sch-tbl-info").classList.remove("animate-out");
        cObj("sch-tbl-info").classList.add("hide");
        //the other window
        cObj("sch-details").classList.remove("hide");
        cObj("sch-details").classList.add("animate");
        setTimeout(() => {
            cObj("sch-details").classList.remove("animate");
        }, 400);
    }, 350);

    //get the school information
    getSChoolinfor(this.id);
}
//go back to table
cObj("go-to-sch-table").addEventListener("click",viewTable);
function viewTable() {
    //push away the table holder
    cObj("sch-details").classList.add("animate-out");
    setTimeout(() => {
        cObj("sch-details").classList.remove("animate-out");
        cObj("sch-details").classList.add("hide");
        //the other window
        cObj("sch-tbl-info").classList.remove("hide");
        cObj("sch-tbl-info").classList.add("animate");
        setTimeout(() => {
            cObj("sch-tbl-info").classList.remove("animate");
        }, 400);
    }, 350);
}
function getSChoolinfor(sch_id) {
    var datapass = "sch_id="+sch_id.substr(5);
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("sch-data-infor"),cObj("sch-data-retrieve"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("school_loads").classList.contains("hide")) {
                //clear the form
                //get the strings and devide them with delimeter
                var data = cObj("sch-data-infor").innerText;
                var splitdata = data.split("|");
                //assign the value to the table
                cObj("box-no").innerText = splitdata[12];
                cObj("box-code").innerText = splitdata[13];
                cObj("sch-mail").innerText = splitdata[6];
                cObj("sch-contact").innerText = splitdata[5];
                cObj("sch-code").innerText = splitdata[0];
                cObj("sch-name").innerText = splitdata[1];
                cObj("sch-admin").innerText = splitdata[4];
                cObj("sch-county").innerText = splitdata[15]
                cObj("sch-country").innerText = splitdata[16];
                cObj("sch-vission").innerText = splitdata[10];
                cObj("sch-mission").innerText = splitdata[11];
                cObj("sch-dbname").innerText = splitdata[8];
                cObj("sch-id-inside").innerText = sch_id.substr(5);
                cObj("sch-motto").innerText = splitdata[3];
                cObj("sch_dp_inform").src = "../../"+splitdata[14];

                //my data
                cObj("my-sch-name").innerText = splitdata[1];
                cObj("my-motto").innerText = splitdata[3];

                var activated = "De-activated";
                if (splitdata[9] == 1) {
                    activated = "Activated";
                }
                cObj("activated").innerText = activated;

                //set eventlistener
                var objects = document.getElementsByClassName("ch");
                for (let index = 0; index < objects.length; index++) {
                    const element = objects[index];
                    element.addEventListener("click",changeVal);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

//change values of the field
function changeVal() {
    var id = this.id;
    //show the window
    cObj("change-field-win").classList.remove("hide");
    cObj("field_name").value = cObj(id).innerText;
    //field name
    cObj("field_names").innerText = fieldNames(id);
    cObj("field_col").innerText = columnNames(id);
}

cObj("save-sub-infor").addEventListener("click",changeField);
function changeField() {
    var datapass = "change_field=true&field_values="+cObj("field_name").value+"&column_name="+cObj("field_col").innerText+"&sch_idds="+cObj("sch-id-inside").innerText;
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("output-form"),cObj("loads_in"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("school_loads").classList.contains("hide")) {
                //remove the form
                setTimeout(() => {
                    cObj("output-form").innerText = "";
                    cObj("change-field-win").classList.add("hide");
                }, 500);
                //UPDATE THE NEW CHANGES
                var id = cObj("sch-id-inside").innerText;
                getSChoolinfor("SCHID"+id);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

//field names compared to id
function fieldNames(field_id) {
    var field_name = "Null";
    var id = field_id;
    if (id == "box-no") {
        field_name = "BOX Number";
    }else if (id == "sch-mail") {
        field_name = "School Mail";
    }else if (id == "sch-contact") {
        field_name = "School Contact";
    }else if (id == "sch-code") {
        field_name = "KNEC code";
    }else if (id == "sch-name") {
        field_name = "School Name";
    }else if (id == "sch-admin") {
        field_name = "Administrator name";
    }else if (id == "sch-county") {
        field_name = "County";
    }else if (id == "sch-country") {
        field_name = "Country";
    }else if (id == "sch-vission") {
        field_name = "School Vision";
    }else if (id == "sch-mission") {
        field_name = "School Mission";
    }else if (id == "sch-dbname") {
        field_name = "Database name";
    }else if (id == "box-code") {
        field_name = "BOX code";
    }else if (id == "sch-motto") {
        field_name = "School Motto";
    }else {
        field_name = "null";
    }
    return field_name;
}
//the column names
function columnNames(field_id) {
    var field_name = "Null";
    var id = field_id;
    if (id == "box-no") {
        field_name = "po_box";
    }else if (id == "sch-mail") {
        field_name = "school_mail";
    }else if (id == "sch-contact") {
        field_name = "school_contact";
    }else if (id == "sch-code") {
        field_name = "school_code";
    }else if (id == "sch-name") {
        field_name = "school_name";
    }else if (id == "sch-admin") {
        field_name = "school_admin_name";
    }else if (id == "sch-county") {
        field_name = "county";
    }else if (id == "sch-country") {
        field_name = "country";
    }else if (id == "sch-vission") {
        field_name = "sch_vision";
    }else if (id == "sch-mission") {
        field_name = "sch_mission";
    }else if (id == "sch-dbname") {
        field_name = "database_name";
    }else if (id == "box-code") {
        field_name = "box_code";
    }else if (id == "sch-motto") {
        field_name = "school_motto";
    }else {
        field_name = "Null";
    }
    return field_name;
}

function displayUsers() {
    //get the user information
    //start the animation
    cObj("sch-tbl-info").classList.add("animate-out");
    setTimeout(() => {
        cObj("sch-tbl-info").classList.add("hide");
        cObj("sch-tbl-info").classList.remove("animate-out");
        cObj("user_information").classList.remove("hide");
        cObj("user_information").classList.add("animate");
        setTimeout(() => {
            cObj("user_information").classList.remove("animate");
        }, 400);
    }, 350);

    //get the user information
    getStaff(this.id.substr(6));
}

//go back to list
cObj("go-to-sch").addEventListener("click",returnToList);
function returnToList() {
    //get the user information
    //start the animation
    cObj("user_information").classList.add("animate-out");
    setTimeout(() => {
        cObj("user_information").classList.add("hide");
        cObj("user_information").classList.remove("animate-out");
        cObj("sch-tbl-info").classList.remove("hide");
        cObj("sch-tbl-info").classList.add("animate");
        setTimeout(() => {
            cObj("sch-tbl-info").classList.remove("animate");
        }, 400);
    }, 350);
}

function getStaff(id) {
    var datapass = "user_infor=true&school_id="+id;
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("users-inform"),cObj("schl_oad"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("schl_oad").classList.contains("hide")) {
                //set listeners to the user to get their detail
                var users = document.getElementsByClassName("use-dets");
                for (let index = 0; index < users.length; index++) {
                    const element = users[index];
                    element.addEventListener("click",showUserDetailInfor);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
//sticky top navbar
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
    //alert(window.pageYOffset);
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

//open the window
cObj("activated").addEventListener("click",showActivated);
function showActivated() {
    cObj("activated-win").classList.remove("hide");
    //selected value
    if(this.innerText == "Activated"){
        cObj("yes-active").selected = true;
    }else{
        cObj("no-active").selected = true;
    }

    //column name
    cObj("field_col1").innerText = "activated";
}
//close the window
cObj("close-win-field1").addEventListener("click",closeActivate);
function closeActivate() {
    cObj("activated-win").classList.add("hide");
}

//save the information
cObj("save-sub-infor2").onclick = function () {
    //get the data
    var datapass = "change_field=true&field_values="+cObj("sctive").value+"&column_name="+cObj("field_col1").innerText+"&sch_idds="+cObj("sch-id-inside").innerText;
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("output-form2"),cObj("loads_in2"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loads_in2").classList.contains("hide")) {
                //remove the form
                setTimeout(() => {
                    cObj("output-form2").innerText = "";
                    cObj("activated-win").classList.add("hide");
                }, 500);
                //UPDATE THE NEW CHANGES
                var id = cObj("sch-id-inside").innerText;
                getSChoolinfor("SCHID"+id);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

//switch from the staff list to the staff detail window
function showUserDetailInfor() {
    cObj("user_information").classList.add("animate-out");
    setTimeout(() => {
        cObj("user_information").classList.add("hide");
        cObj("user_information").classList.remove("animate-out");
        cObj("user-detail").classList.remove("hide");
        cObj("user-detail").classList.add("animate");
        setTimeout(() => {
            cObj("user-detail").classList.remove("animate");
        }, 400);
    }, 350);
    var id = this.id.substr(5);
    cObj("sch-id-inside1").innerText = id;
    getUserInformation(id);
}

cObj("go-to-user-list").onclick = function () {
    cObj("user-detail").classList.add("animate-out");
    setTimeout(() => {
        cObj("user-detail").classList.add("hide");
        cObj("user-detail").classList.remove("animate-out");
        cObj("user_information").classList.remove("hide");
        cObj("user_information").classList.add("animate");
        setTimeout(() => {
            cObj("user_information").classList.remove("animate");
        }, 400);
    }, 350);
}
function getUserInformation(user_id) {
    //get the user information
    var datapass = "get_my_user=true&user_id="+user_id;
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj("sch-data-infor1"),cObj("user_loadings"))
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("user_loadings").classList.contains("hide")) {
                //assign the field the value
                var data = cObj("sch-data-infor1").innerText;
                var splitdata = data.split("|");

                cObj("user-name").innerText = NA(splitdata[0]);
                cObj("user-gender").innerText = NA(splitdata[4]);
                cObj("user-dob").innerText = NA(splitdata[1]);
                cObj("user-id-no").innerText = NA(splitdata[6]);
                cObj("user-phone").innerText = NA(splitdata[3]);
                cObj("user-address").innerText = NA(splitdata[5]);
                cObj("user-gmail").innerText = NA(splitdata[11]);
                cObj("user-tsc").innerText = NA(splitdata[7]);
                cObj("user-authority").innerText = NA(splitdata[10]);
                cObj("user-del").innerText = NA(splitdata[9]);
                cObj("user-active").innerText = NA(splitdata[12]);
                cObj("user-u-name").innerText = NA(splitdata[8]);
                if (splitdata[13].length > 0) {
                    cObj("user_dp_inform").src = "../../"+splitdata[13];
                }else{
                    cObj("user_dp_inform").src = "../../images/dp.png";
                }
                cObj("mynames-user").innerText = splitdata[0];
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("close_window3").onclick = function () {
    cObj("remove-inside").classList.add("hide");
}
cObj("close_window4").onclick = function () {
    cObj("remove-inside1").classList.add("hide");
}
cObj("close_window5").onclick = function () {
    cObj("remove-inside2").classList.add("hide");
}
//give na for empty data
function NA(strings) {
    if (strings.length > 0) {
        return strings;
    }else{
        return "N/A";
    }
}
changeText();
//show change windows
function changeText() {
    var text_input = document.getElementsByClassName("tx");
    for (let index = 0; index < text_input.length; index++) {
        const element = text_input[index];
        element.addEventListener("click",showTexts);
    }

    // NUMBER INPUT
    var number_input = document.getElementsByClassName("num");
    for (let index = 0; index < number_input.length; index++) {
        const element = number_input[index];
        element.addEventListener("click",showNUmbers);
    }
    //date inputs
    var date_input = document.getElementsByClassName("dt");
    for (let index = 0; index < date_input.length; index++) {
        const element = date_input[index];
        element.addEventListener("click",showDates);
    }
    //gender select
    var gen_input = document.getElementsByClassName("gen1");
    for (let index = 0; index < gen_input.length; index++) {
        const element = gen_input[index];
        element.addEventListener("click",selectGenChange);
    }
    //yes no option
    var yesno = document.getElementsByClassName("yesno");
    for (let index = 0; index < yesno.length; index++) {
        const element = yesno[index];
        element.addEventListener("click",changeYesNo);
    }
}
function changeYesNo() {
    var ids = this.id;
    var field_name = fieldName(ids);
    var column_name = idValue(ids);
    cObj("fld_val5").innerText = field_name;
    cObj("column_names5").innerText = column_name;
    //show the yes no window
    cObj("remove-inside4").classList.remove("hide");
    

    //different options for activated and deleted
    var f_value = this.innerText;
    if (f_value == "Yes") {
        cObj("yes_opt").selected = true;
    }else{
        cObj("no_opt").selected = true;
    }
    // if (field_name == "Activated") {
    // }else if (field_name == "Delete") {
    //     if (f_value == "Yes") {
    //         cObj("").selected = true;
    //     }else{
    //         cObj("").selected = true;
    //     }
    // }
}
//change gender
function selectGenChange() {
    var id = this.id;
    var field_name = fieldName(id);
    var column_name = idValue(id);
    cObj("fld_val4").innerText = field_name;
    cObj("column_names4").innerText = column_name;
    //field value
    var f_value = this.innerText;
    // show the gender change window
    cObj("remove-inside3").classList.remove("hide");
    //assign the value
    if (f_value == "Female") {
        cObj("female_user").selected = true;
    }else{
        cObj("male_user").selected = true;
    }
}
//save the yes no options

function showTexts() {
    cObj("remove-inside").classList.remove("hide");
    //assign the value to the field using their id
    cObj("input_text_user1").value = this.innerText;
    //assign column name
    cObj("column_names1").innerText = idValue(this.id);
    //assign field name
    cObj("fld_val1").innerText = fieldName(this.id);
}
function showNUmbers() {
    cObj("remove-inside1").classList.remove("hide");
    // assign number values to the fields
    cObj("input_text_user2").value = this.innerText;
    // assign column
    cObj("column_names2").innerText = idValue(this.id);
    //assign field name
    cObj("fld_val2").innerText = fieldName(this.id);
}
function showDates() {
    cObj("remove-inside2").classList.remove("hide");
    cObj("input_text_user3").value = this.innerText;
    //assign column
    cObj("column_names3").innerText = idValue(this.id);
    //assign field name
    cObj("fld_val3").innerText = fieldName(this.id);
}
function idValue(id) {
    var return_val = "Null";
    if (id == "user-name") {
        return_val = "fullname";
    }else if (id == "user-gender") {
        return_val = "gender";
    }else if (id == "user-dob") {
        return_val = "dob";
    }else if (id =="user-id-no") {
        return_val = "nat_id";
    }else if (id == "user-phone") {
        return_val = "phone_number";
    }else if (id =="user-address") {
        return_val = "address";
    }else if (id == "user-gmail") {
        return_val = "email";
    }else if (id == "user-tsc") {
        return_val = "tsc_no";
    }else if (id == "user-authority") {
        return_val = "auth";
    }else if (id == "user-del") {
        return_val = "deleted";
    }else if (id == "user-active") {
        return_val = "activated";
    }else if (id == "user-u-name") {
        return_val = "username";
    }else if (id == "user-password") {
        return_val = "password";
    }
    return return_val;
}
function fieldName(id) {
    var return_val = "Null";
    if (id == "user-name") {
        return_val = "Full Name";
    }else if (id == "user-gender") {
        return_val = "Gender";
    }else if (id == "user-dob") {
        return_val = "Date Of Birth";
    }else if (id == "user-id-no") {
        return_val = "National Id";
    }else if (id == "user-phone") {
        return_val = "Phone Number";
    }else if (id == "user-address") {
        return_val = "Address";
    }else if (id == "user-gmail") {
        return_val = "Email";
    }else if (id == "user-tsc") {
        return_val = "TSC no";
    }else if (id == "user-authority") {
        return_val = "Authority";
    }else if (id == "user-del") {
        return_val = "Delete";
    }else if (id == "user-active") {
        return_val = "Activated";
    }else if (id == "user-u-name") {
        return_val = "Username";
    }else if (id == "user-password") {
        return_val = "Password";
    }
    return return_val;
}
//date
cObj("save-sub-infor4").addEventListener("click",changeValues);
function changeValues() {
    // get the values
    var column_name = cObj("column_names3").innerText;
    var column_value = cObj("input_text_user3").value;
    var user_id = cObj("sch-id-inside1").innerText;
    var loadid = "loads_in4";
    var displayid = "output-form5";
    var windowid = "remove-inside2";
    changeUserData(column_name,column_value,user_id,displayid,loadid,windowid);
}
//number values
cObj("save-sub-infor5").addEventListener("click",changeValues2);
function changeValues2() {
    // get the values
    var column_name = cObj("column_names2").innerText;
    var column_value = cObj("input_text_user2").value;
    var user_id = cObj("sch-id-inside1").innerText;
    var loadid = "loads_in3";
    var displayid = "output-form4";
    var windowid = "remove-inside1";
    changeUserData(column_name,column_value,user_id,displayid,loadid,windowid);
}

//text values
cObj("save-sub-infor3").addEventListener("click",changeValues1);
function changeValues1() {
    // get the values
    var column_name = cObj("column_names1").innerText;
    var column_value = cObj("input_text_user1").value;
    var user_id = cObj("sch-id-inside1").innerText;
    var loadid = "loads_in2";
    var displayid = "output-form3";
    var windowid = "remove-inside";
    changeUserData(column_name,column_value,user_id,displayid,loadid,windowid);
}
cObj("save-sub-infor6").addEventListener("click",changeValues3);
//gender value
function changeValues3() {
    //get the values
    var column_name = cObj("column_names4").innerText;
    var column_value = cObj("input_sel_gen").value;
    var user_id = cObj("sch-id-inside1").innerText;
    var loadid = "loads_in5";
    var displayid = "output-form6";
    var windowid = "remove-inside3";
    changeUserData(column_name,column_value,user_id,displayid,loadid,windowid);
}
//change the yes no values
cObj("save-sub-infor7").addEventListener("click",changeValues4);
function changeValues4() {
    //get the values
    var column_name = cObj("column_names5").innerText;
    var column_value = cObj("change_on_off").value;
    var user_id = cObj("sch-id-inside1").innerText;
    var loadid = "loads_in6";
    var displayid = "output-form7";
    var windowid = "remove-inside4";
    changeUserData(column_name,column_value,user_id,displayid,loadid,windowid);
}
//function to change user data
function changeUserData(column_name,column_value,user_id,displayer,leader,windows_inc) {
    var datapass = "changeUserDetails=true&tb_col_name="+column_name+"&col_val="+column_value+"&id_user="+user_id;
    sendDataPost("POST","/sims/developer/assets/ajax/developer.php",datapass,cObj(displayer),cObj(leader));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj(leader).classList.contains("hide")) {
               // remove the notification after minutes
               setTimeout(() => {
                   cObj(displayer).innerText = "";
                   cObj(windows_inc).classList.add("hide");
                   getUserInformation(user_id);
               }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

//close gender chooser
cObj("close_window6").onclick = function () {
    cObj("remove-inside3").classList.add("hide");
}

cObj("close_window7").onclick = function () {
    cObj("remove-inside4").classList.add("hide");
}