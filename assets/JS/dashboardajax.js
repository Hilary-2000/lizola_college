/*******start of dashboard ajax******* */
var auth = cObj("authoriti").value;
if (auth == '1') {
    cObj("sch_logos").onclick = function () {
        cObj("update_school_profile").click();
    }
    //get number of students
    var datapass = "?getStudentCount=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
        }
    }, 900000);

    //get number of students registerd today
    var datapass = "?studentscounttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?studentscounttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
        }
    }, 900000);

    //get number of students present in school today
    var datapass = "?studentspresenttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));

    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
        }
    }, 900000);

    //get number off students absent

    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var total = cObj("studentscount").innerText.split(" ");
            var present = cObj("studpresenttoday").innerText.split(" ");
            var total1 = total[0];
            var present1 = present[0];
            if (present1!=0) {
                cObj("absentstuds").innerText = (total1-present1)+" Student(s)";
            }else{
                cObj("absentstuds").innerText = "Roll call not taken.";
            }
        }
    }, 900000);

    //number of active users
    var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
    sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 300000);

    //number of school fees recieved
    var datapass = "?schoolfeesrecieved=true";
    sendData("GET","administration/admissions.php",datapass,cObj("schoolfeesrecieved"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?schoolfeesrecieved=true";
            sendData("GET","administration/admissions.php",datapass,cObj("schoolfeesrecieved"));
        }
    }, 300000);

    //number of transfered students
    var datapass = "?transfered_students=true";
    sendData("GET","administration/admissions.php",datapass,cObj("transfered_studs"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?transfered_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("transfered_studs"));
        }
    }, 900000);
    //number of alumnis students
    var datapass = "?alumnis_number=true";
    sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?alumnis_number=true";
            sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number"));
        }
    }, 900000);


        
    //head teacher dashboard
    cObj("totalstuds").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("regtoday").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("regtodays").selected = true;
        cObj("findingstudents").click();
    }

    cObj("prestoday").onclick = function () {
        cObj("callregister").click();
        cObj("view_atts").selected = true;
        cObj("optd").click();
        cObj("display_attendance_class").click();
    }
    cObj("studentabs").onclick = function () {
        cObj("callregister").click();
        cObj("prestoday").click();
    }
    cObj("schoolfee").onclick = function () {
        cObj("findtrans").click();
        cObj("todayfees").selected = true;
        cObj("allstudents").selected = true;
        cObj("searchtransaction").click();
    }

    //get the logs
    var datapass = "?get_loggers=true";
    sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //get the active exams
    var datapass = "?active_exams_lts=true";
    sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 60000);

    //view active exams
    cObj("view_active_exams").onclick = function () {
        cObj("viewexams").click();
        cObj("all_active").selected = true;
        cObj("examanagement").click();
        cObj("displaysubjects").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("htdash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }
    
cObj("showfees").onclick = function () {
    cObj("hidefees").classList.toggle("hide");

    if(!cObj("se_e").classList.contains("hide")){

        cObj("hidefees").classList.add("hide");
        cObj("se_e").classList.add("hide");
        cObj("unse_e").classList.remove("hide");

    }else if (!cObj("unse_e").classList.contains("hide")) {

        cObj("hidefees").classList.remove("hide");
        cObj("se_e").classList.remove("hide");
        cObj("unse_e").classList.add("hide");

    }
}

//head teacher dashboard end
}
//deputy prncipal
if (auth == 3) {
    cObj("sch_logos").onclick = function () {
        cObj("update_school_profile").click();
    }
    //get number of students
    var datapass = "?getStudentCount=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscount"));
        }
    }, 900000);

    //get number of students registerd today
    var datapass = "?studentscounttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?studentscounttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studentscounttoday"));
        }
    }, 900000);

    //get number of students present in school today
    var datapass = "?studentspresenttoday=true";
    sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"));
        }
    }, 900000);

    //get number off students absent

    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var total = cObj("studentscount").innerText.split(" ");
            var present = cObj("studpresenttoday").innerText.split(" ");
            var total1 = total[0];
            var present1 = present[0];
            if (present1!=0) {
                cObj("absentstuds").innerText = (total1-present1)+" Student(s)";
            }else{
                cObj("absentstuds").innerText = "Roll call not taken.";
            }
        }
    }, 900000);

    //number of active users
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 900000);
        
    //deputy head teacher dashboard
    cObj("totalstuds").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("regtoday").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("regtodays").selected = true;
        cObj("findingstudents").click();
    }

    cObj("prestoday").onclick = function () {
        cObj("callregister").click();
        cObj("view_atts").selected = true;
        cObj("optd").click();
        cObj("display_attendance_class").click();
    }
    cObj("studentabs").onclick = function () {
        cObj("callregister").click();
        cObj("prestoday").click();
    }

    //get the logs
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //get the active exams
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 900000);

    //view active exams
    cObj("view_active_exams").onclick = function () {
        cObj("viewexams").click();
        cObj("all_active").selected = true;
        cObj("examanagement").click();
        cObj("displaysubjects").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("dp_dash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }
    //end of the deputy principal
}

//administrator dashboard = 0
if (auth == 0) {
    //get number of students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?getStudentCount=true";
            sendData("GET","administration/admissions.php",datapass,cObj("students"));
        }
    }, 900000);

    //get number of users present in school
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?totaluserspresent=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studpresenttoday"))
        }
    }, 900000);
    
    //number of active users
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?checkactive=true&userid="+cObj("useriddds").value;
            sendData("GET","administration/admissions.php",datapass,cObj("activeusers"));
        }
    }, 900000);
    
    //get number of students present in school today
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?studentspresenttoday=true";
            sendData("GET","administration/admissions.php",datapass,cObj("rollcalnumber"))
        }
    }, 900000);

    cObj("admin_students").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("alstuds").selected = true;
        cObj("findingstudents").click();
    }
    cObj("my_employees").onclick = function () {
        cObj("managestaf").click();
        cObj("view_my_stf").selected = true;
        viewstaffavailablebtn();
    }
    cObj("view_logs").onclick = function () {
        hideWindow();
        cObj("loggers_page").classList.remove("hide");
    }
    //get the logs
    setInterval(() => {
        if (!cObj("loggers_page").classList.contains("hide")) {
            var datapass = "?get_loggers=true";
            sendData("GET","administration/admissions.php",datapass,cObj("loggers_table"));
        }
    }, 2000);
    //number of transfered students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            var datapass = "?transfered_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("transfered_stud2"));
        }
    }, 900000);
    //number of alumnis students
    setInterval(() => {
        if (!cObj("adminsdash").classList.contains("hide")){
            // console.log("WE ARE HERE");
            var datapass = "?alumnis_number=true";
            sendData("GET","administration/admissions.php",datapass,cObj("alumnis_number2"));
        }
    }, 900000);
}
//classteacher dashboard = 5
if (auth == 5) {
    //get total number of students in my class
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?number_of_me_studnets=true";
            sendData("GET","administration/admissions.php",datapass,cObj("studclass"));
        }
    }, 900000);
    //get total number of students regestered today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?reg_today_my_class=true";
            sendData("GET","administration/admissions.php",datapass,cObj("reg_tod_mine"));
        }
    }, 900000);
    //get total number of students present in school today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?today_attendance=true";
            sendData("GET","administration/admissions.php",datapass,cObj("my_att_clas"));
        }
    }, 900000);
    //get total number of students present in school today in my class 
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")) {
            var datapass = "?absent_students=true";
            sendData("GET","administration/admissions.php",datapass,cObj("my_absent_list"));
        }
    }, 900000);
    cObj("view_my_tt").onclick = function () {
        cObj("generate_tt_btn").click();
    }
    cObj("my_students_populate").onclick = function () {
        cObj("findstudsbtn").click();
        cObj("display_my_students").click();
    }
    //my subjects
    setInterval(() => {
        if (!cObj("ctdash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }

}
//the teachers` dashboard
if (auth == 2) {
    //get the active exams
    setInterval(() => {
        if (!cObj("tr_dash").classList.contains("hide")){
            var datapass = "?active_exams_lts=true";
            sendData("GET","academic/academic.php",datapass,cObj("active_examination"));
        }
    }, 900000);
    //my subjects
    setInterval(() => {
        if (!cObj("tr_dash").classList.contains("hide")){
            var datapass = "?subs_lists=true";
            sendData("GET","academic/academic.php",datapass,cObj("my_subjects"));
        }
    }, 900000);
    cObj("view_my_subs").onclick = function () {
        cObj("update_personal_profile").click();
    }

}

//tracks a user if they are active
setInterval(() => {
    var datapass = "?activeuser=true&userid="+cObj("useriddds").value;
    sendData("GET","administration/admissions.php",datapass,cObj("nulled"));
}, 60000);

//check for notifications
setInterval(() => {
    var datapass = "?notices=true";
    sendData("GET","notices/notices.php",datapass,cObj("note_2"));
}, 60000);