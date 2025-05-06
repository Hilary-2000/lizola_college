cObj("registersub").onclick = function () {
    var err=0;
    err += checkBlank("subname");
    err += checkBlank("submarks");
    err += checkBlank("sundids");
    err += checkBlank("subject_display_name");
    if (err==0) {
        cObj("errregsub").innerHTML = "<p style='color:red;'></p>";
        var classes = document.getElementsByClassName("subjectclass");
        var select = 0;
        var classed = new Array();
        for (let index = 0; index < classes.length; index++) {
            const element = classes[index];
            if (element.checked == true) {
                select+=1;
                classed.push(element.id);
            }
        }
        if (select>0) {
            var set_my_grades_list = cObj("set_my_grades_list").innerText;
            var datastring = "?addsubject=true&subjectname="+valObj("subname")+"&subjectmax="+valObj("submarks")+"&claslist="+classed+"&subids="+valObj("sundids")+"&grades_lists="+set_my_grades_list+"&subject_display_name="+valObj("subject_display_name");
            sendData1("GET","academic/academic.php",datastring,cObj("errregsub"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")){
                        cObj("formpay").reset();
                        cObj("display_tables_list").innerHTML = "";
                        setTimeout(() => {
                            cObj("errregsub").innerHTML = "";
                        }, 3000);
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("errregsub").innerHTML = "<p style='color:red;'>Select the classes the subject is to be taught!</p>";
        }
    }else{
        cObj("errregsub").innerHTML = "<p style='color:red;'>Please fill all the fields that have a red border!</p>";
    }
}
cObj("subname").onblur = function () {
    var data = "?findname=true&name="+this.value;
    if (this.value.length>0) {
        sendData("GET","academic/academic.php",data,cObj("subnameerr"));
    }else{
        cObj("subnameerr").innerHTML = "<p></p>";
    }
}
cObj("serchby").onchange = function () {
    var val1 = this.value;
    if (val1=="byname") {
        cObj("classtaught").classList.add("hide");
        cObj("byname").classList.remove("hide");
    }else if (val1=="byclass") {
        cObj("byname").classList.add("hide");
        cObj("classtaught").classList.remove("hide");
    }
}
cObj("finder").onclick = function () {
    
    cObj("subjectdets").classList.add("hide");
    var val1 = valObj("serchby");
    var datapass = "?searchsubjby=";
    var err =0;
    err+=checkBlank("serchby");
    if (err==0) {
        cObj("resulthold").innerHTML = "<p style='color:red;'></p>";
        if (val1=="byname") {
            err+=checkBlank("subnamed");
            if (err==0) {
                datapass+=""+val1+"&subjename="+valObj("subnamed");   
            }
        }else if (val1=="byclass") {
            if (typeof(cObj("classtaughts")) != 'undefined' || cObj("classtaughts") != null) {
                err+=checkBlank(cObj("classtaughts").id);

                if (err==0) {
                    datapass+=""+val1+"&class="+valObj("classtaughts");
                }
            }else{
                cObj("errorhand").innerHTML = "<p style='color:red;' >Contact your administrator your system needs classes configuration.</p>";
            }
        }   
    }

    if (err==0) {
        cObj("errorhand").innerHTML = "<p style='color:red;'></p>";
        sendData1("GET","academic/academic.php",datapass,cObj("resulthold"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var collectbtn = document.getElementsByClassName('viewsubj');
                    for (let index = 0; index < collectbtn.length; index++) {
                        const element = collectbtn[index];
                        setTableListenersub(element.id);
                    }
                    if(typeof(cObj("pleasewait3")) != 'undefined' && cObj("pleasewait3") != null){
                        cObj("pleasewait3").classList.add("hide");
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);

    }else{
        cObj("errorhand").innerHTML = "<p style='color:red;'>Fill all the fields with red borders!</p>";
    }
}

cObj("cancelsubs").onclick = function () {
    cObj("information").classList.remove("hide");
    cObj("subjectdets").classList.add("hide");
    if (valObj("subjects_option") == "search_subjects") {
        cObj("finder").click();
    }else{
        displayAllSubjects();
    }
}
function setTableListenersub(id) {
    cObj(id).addEventListener("click" , finders);
}
function finders() {
    var subjectid = this.id.substr(5);
    var datapass = "?subjectids="+subjectid;
    sendData1("GET","academic/academic.php",datapass,cObj("subinform"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                var dats =  cObj("subinform").innerText;
                if (dats!="null") {
                    var data = dats.split("&");
                    if (data.length>0) {
                        cObj("subids").innerText = data[0];
                        setDatalen("subnam",data[1]);
                        setDatalen("subidss",data[5]);
                        setDatalen("submarksd",data[2]);
                        var classes = data[3].split(",");
                        var checks = document.getElementsByClassName("checkclas");
                        for (let index = 0; index < checks.length; index++) {
                            const alem = checks[index];
                            alem.checked = false;
                        }
                        var counts="";
                        if (classes.length>0) {
                            for (let index = 0; index < classes.length; index++) {
                                const element = classes[index];
                                if (checks.length>0) {
                                    for (let index = 0; index < checks.length; index++) {
                                        const elem = checks[index];
                                        if (elem.id == "check"+element) {
                                            elem.checked = true;
                                        }
                                    }
                                }
                            }
                        }
                        // get data accordingly
                        var user_data = data[6];
                        cObj("subjects_grades_hidden").innerText = user_data;
                        if (user_data.length > 0) {
                            create_grade_list(user_data,"my_grade_lists_subject");
                        }else{
                            cObj("my_grade_lists_subject").innerHTML = "<p class='text-success'>Grades for this subjects are not set!</p>";
                        }
                        // set the display name
                        cObj("sub_display_name").value = data[7];
                    }
                    cObj("subjectdets").classList.remove("hide");
                    cObj("information").classList.add("hide");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
// set grades
cObj("updatesubs").onclick = function () {
    var err = 0;
    err+=checkBlank("subnam");
    err+=checkBlank("subidss");
    err+=checkBlank("submarksd");
    err+=checkBlank("sub_display_name");
    var classel = document.getElementsByClassName("checkclas");
    var counts=0;
    for (let index = 0; index < classel.length; index++) {
        const elem = classel[index];
        if (elem.checked == true) {
            counts++;
            break;
        }
    }
    if (counts==0) {
        err++;
    }
    if (err==0) {
        cObj("errhandlers").innerHTML = "<p style='color:red;'></p>";
        var classelected ="";
        var c = 0;
        for (let index = 0; index < classel.length; index++) {
            const ele = classel[index];
            if (ele.checked == true) {
                c++;
                if (c==1) {
                    classelected+=ele.value;                    
                }else{
                    classelected+=","+ele.value;
                }
            }
        }
        var subject_grade = cObj("subjects_grades_hidden").innerText;
        var datapass = "?updatesubjects=true&subname="+valObj("subnam")+"&subttid="+valObj("subidss")+"&submaxmarks="+valObj("submarksd")+"&classtaught="+classelected+"&subjeid="+cObj("subids").innerText+"&subject_grade="+subject_grade+"&sub_display_name="+valObj("sub_display_name");
        sendData1("GET","academic/academic.php",datapass,cObj("errhandlers"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var infor = cObj("errhandlers").innerText.substr(0,7);
                    if (infor=="Subject") {
                        // cObj("subjectdets").reset();
                        // cObj("finder").click();
                        setTimeout(() => {
                            cObj("errhandlers").innerHTML = "";
                        }, 5000);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
        
    }else{
        cObj("errhandlers").innerHTML = "<p style='color:red;'>Fill all the field to proceed</p>";
    }
}
cObj("edit_grading_subject").onclick = function () {
    var err = checkBlank("subnam");
    err+=checkBlank("subidss");
    err+=checkBlank("submarksd");
    if (err == 0) {
        cObj("edit_grades_win").classList.remove("hide");
        cObj("errhandlers").innerHTML = "";

        // set name and the maximum grade
        cObj("edit_grades_subject_name").innerText = cObj("subnam").value;
        cObj("edit_maximum_marks").value = cObj("submarksd").value;
    }else{
        cObj("errhandlers").innerHTML = "<p class='text-danger'>Please fill all fields with red border before filling the grades</p>";
    }
}


cObj("edit_add_grades_in_cancels").onclick = function () {
    cObj("edit_grades_win").classList.add("hide");
    grayBorder(cObj("edit_maximum_marks"));
    grayBorder(cObj("edit_minimum_marks"));
    grayBorder(cObj("edit_grade_score"));
    cObj("edit_error_handler_graders").innerText = "";
    cObj("edit_grades_lists").innerHTML = "<p>Kindly add the grades for the respective subject <br> Grades will appear here.</p>";
}


function displayAllSubjects () {
    if (valObj("subjects_option") == "search_subjects") {
        
    }else{
        cObj("seachsub").classList.add("hide");
        cObj("subjectdets").classList.add("hide");
        var datapas = "?findsubjects=true";
        sendData1("GET","academic/academic.php",datapas,cObj("resulthold"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    stopInterval(ids);
                    var collectbtn = document.getElementsByClassName('viewsubj');
                    for (let index = 0; index < collectbtn.length; index++) {
                        const element = collectbtn[index];
                        setTableListenersub(element.id);
                    }
                    if(typeof(cObj("pleasewait3")) != 'undefined' && cObj("pleasewait3") != null){
                        cObj("pleasewait3").classList.add("hide");
                    }
                }
            }, 100);
        }, 200);
    }
}
cObj("opt12").onchange = function () {
    var options = this.value;
    if (options=="byname") {
        cObj("tridnum").classList.add("hide");
        cObj("trnames").classList.remove("hide");
    }else if (options=="byidno") {
        cObj("tridnum").classList.remove("hide");
        cObj("trnames").classList.add("hide");        
    }
}
cObj("findersd").onclick = function () {
    cObj("editsubinfor").classList.add("hide");
    cObj("viewsubinformations").classList.remove("hide");
    var err = 0;
    err+=checkBlank("opt12");
    var options = cObj("opt12").value;
    if (options.length>0) {
        if (options=="byname") {
            err+=checkBlank("nameds");
        }else if (options=="byidno") {
            err+=checkBlank("idnumbers");
        }
    }
    if (err==0) {
        cObj("managesubsteacherr").innerHTML = "<p style='color:red;'></p>";
        var datapass = "?seachby=";
        if (options=="byname") {
            datapass+="byname&name="+cObj("nameds").value;
        }else if (options=="byidno") {
            datapass+="byidno&idnos="+cObj("idnumbers").value;
        }
        sendData1("GET","academic/academic.php",datapass,cObj("managesubstr"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var classbtns = document.getElementsByClassName("setSubclass");
                    for (let index = 0; index < classbtns.length; index++) {
                        const elem = classbtns[index];
                        setListenes(elem.id);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);

    }else{
        cObj("managesubsteacherr").innerHTML = "<p style='color:red;'>Please fill all the field with red borders.</p>";
    }
}
function setListenes(id) {
    cObj(id).addEventListener("click", setClassAndsubj);
}
function setClassAndsubj() {
    var datapass = "?getbyid="+this.id;
    sendData1("GET","academic/academic.php",datapass,cObj("outputsubs"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (typeof(cObj("namenid"))!='undefined' || cObj("namenid")!=null) {
                    var datas = document.getElementById("namenid").innerText;
                    cObj("teachname").value = datas.split(",")[0];
                    cObj("editsubinfor").classList.remove("hide");
                    cObj("viewsubinformations").classList.add("hide");
                    //set the teacher id
                    cObj("useridentity").innerText = this.id.substr(3);
                    //set the edit buttons with listeners
                    var classbutns = document.getElementsByClassName("subsbtns");
                    for (let index = 0; index < classbutns.length; index++) {
                        const elem = classbutns[index];
                        setListeners(elem.id);
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function setListeners(id) {
    cObj(id).addEventListener("click", editbtns);
}
function editbtns() {
    var indexes = this.id.substr(3);
    var datapas = "?askClasses=true&subid="+indexes;
    sendData1("GET","academic/academic.php",datapas,cObj("claslistd"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("namesub").value = cObj("subnameholder").innerText;
                //SET THE CLASS THE TEACHER SELECTED
                //checkclas = CLASS
                var classes = cObj("classlist"+indexes).innerText;
                if (classes.length>0) {
                    var eachclass = classes.split(",");
                    for (let index = 0; index < eachclass.length; index++) {
                        const element = eachclass[index];
                        //cycle trough the checkbox to see if the class is selected
                        var checks = document.getElementsByClassName("checkclases");
                        for (let ind = 0; ind < checks.length; ind++) {
                            const elem = checks[ind];
                            if (elem.value == element) {
                                elem.checked = true;
                                break;
                            }
                        }
                        //set the subject id
                        cObj("subidentity").innerText = indexes;
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    cObj("changesubjclass").classList.remove("hide");
}

cObj("changeclasslist").onclick = function () {
    var selectedid = cObj("useridentity").innerText;
    //get the classes
    var clases = document.getElementsByClassName("checkclases");
    var daros = "";
    var check = 0;
    for (let index = 0; index < clases.length; index++) {
        const element = clases[index];
        if (element.checked == true) {
            daros+=element.value+"^";
            check++;
        }
    }
    //change it to the understandable form of the database
    var finaldata = "";
    if (check > 0) {
        var splitdata = daros.substr(0,(daros.length-1)).split("^");
        for (let ind = 0; ind < splitdata.length; ind++) {
            const element = splitdata[ind];
            finaldata+="("+selectedid+":"+element+")|";
        }
        finaldata = finaldata.substr(0,finaldata.length-1);
    }
    //teachers id
    //send the data to the database
    var datapas = "?sendSubjectInform=true&finaldata="+finaldata+"&subjectid="+cObj("subidentity").innerText+"&teacherid="+selectedid;
    sendData1("GET","academic/academic.php",datapas,cObj("geterrors"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //close the window
                cObj("changesubjclass").classList.add("hide");

                //click the set button that reffers to that teacher
                cObj("sub"+selectedid).click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    
}

cObj("backtosubs").onclick = function () {
    cObj("editsubinfor").classList.add("hide");
    cObj("viewsubinformations").classList.remove("hide");
    allTeachers();
}
cObj("knows").onclick = function () {
    cObj("clicker").classList.add("hide");
    cObj("informations").classList.remove("hide");
}
cObj("closed").onclick = function () {
    cObj("changesubjclass").classList.add("hide");
}
cObj("cancelclasschange").onclick = function () {
    cObj("changesubjclass").classList.add("hide");
}
cObj("funga1").onclick = function () {
    cObj("addteachsubject").classList.add("hide");
}
cObj("close2").onclick = function () {
    cObj("addteachsubject").classList.add("hide");


    cObj("selectclass1").classList.add("hide");
    cObj("selectsub1").classList.remove("hide");
    cObj("saves1").classList.add("hide");
    
    var checksubs = document.getElementsByClassName("checksubjects");
    for (let ind = 0; ind < checksubs.length; ind++) {
        const elem = checksubs[ind];
        elem.checked = false;
    }
}
cObj("addsubsbutn").onclick = function () {
    cObj("selectclass1").classList.add("hide");
    cObj("selectsub1").classList.remove("hide");
    cObj("addteachsubject").classList.remove("hide");
    //get the teacher id.
    var teacherid = cObj("useridentity").innerText;
    cObj("trid12").innerText = teacherid;
    var datapass = "?getsubjects=true&teacherid="+teacherid;
    //get the classes that the teacher aint teaching
    sendData1("GET","academic/academic.php",datapass,cObj("subslist"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //set listeners for the checkbox
                var checkboxes = document.getElementsByClassName("checksubjects");
                for (let ind = 0; ind < checkboxes.length; ind++) {
                    const element = checkboxes[ind];
                    setCheckListener(element.id);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function setCheckListener(id) {
    cObj(id).addEventListener("change",changeCheck);
}
function changeCheck() {
    if (this.checked == true) {
        var data = this.value.split("|");
        var subjectname = data[0];
        var subjectid = data[1];
        cObj("subjectname2").innerText = subjectname;
        cObj("subjectid2").innerText = subjectid;
        cObj("selectclass1").classList.remove("hide");
        cObj("selectsub1").classList.add("hide");
        cObj("saves1").classList.remove("hide");
        var datapass = "?getsubjectsclass=true&subids="+subjectid;
        sendData2("GET","academic/academic.php",datapass,cObj("classlist_1"),cObj("loadings23"));
    }
}
cObj("return1").onclick = function () {
    cObj("selectclass1").classList.add("hide");
    cObj("selectsub1").classList.remove("hide");
    cObj("saves1").classList.add("hide");
    
    var checksubs = document.getElementsByClassName("checksubjects");
    for (let ind = 0; ind < checksubs.length; ind++) {
        const elem = checksubs[ind];
        elem.checked = false;
    }
}

cObj("saves1").onclick = function () {
    //check if a checkbox is selected
    var checkers = document.getElementsByClassName("checkclassess");
    var selcted = 0;
    if (checkers.length > 0 ){
        for (let index = 0; index < checkers.length; index++) {
            const element = checkers[index];
            if (element.checked == true) {
                selcted++;
            }
        }
    }
    if (selcted>0) {
        //take the data and send it to the database, teacher id subject id and classes selected
        var selectedclass = '';
        if (checkers.length > 0 ){
            for (let index = 0; index < checkers.length; index++) {
                const element = checkers[index];
                if (element.checked == true) {
                    selcted++;
                    selectedclass+=element.value+",";
                }
            }
        }
        selectedclass = selectedclass.substr(0,selectedclass.length-1);
        var teacherid = cObj("useridentity").innerText;
        var subjectid = cObj("subjectid2").innerText;
        var datapass = "?setTeacherSubjects=true&subdidds="+subjectid+"&teacheridds="+teacherid+"&selectedclasses="+selectedclass;
        sendData1("GET","academic/academic.php",datapass,cObj("geterrors12"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("geterrors12").innerText = "";
                    var checksubs = document.getElementsByClassName("checksubjects");
                    for (let ind = 0; ind < checksubs.length; ind++) {
                        const elem = checksubs[ind];
                        elem.checked = false;
                    }
                    //close the window 
                    cObj("addteachsubject").classList.add("hide");
                    //click the button show the teachers information
                    cObj("sub"+teacherid).click();
                    
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        //display an error message
        alert("No class is selected!");
    }
}

var on_off = 0;
cObj("viewexams").onclick = function () {
    if (on_off == 0) {
        cObj("viewexam").classList.remove("hide");
        on_off = 1;
        if (currentSlideexams == 0) {
            cObj("generate_exams_reports_window").classList.add("hide");
        }else{
            cObj("finish_generating_reports").click();
            cObj("generate_exams_reports_window").classList.toggle("hide");
            // hide all slides
            for (let index = 0; index < all_slides.length; index++) {
                const element = all_slides[index];
                element.classList.add("hide");
            }
            // display the next slide
            all_slides[0].classList.remove("hide");
            cObj("generate_exams_reports_window").classList.add("hide");
            currentSlideexams = 0;
        }
    }else{
        cObj("viewexam").classList.add("hide");
        on_off = 0;
    }
}
cObj("option1").onchange = function () {
    if (this.value == "bystatus") {
        cObj("statuses").classList.remove("hide");
        cObj("usingname").classList.add("hide");
        cObj("btnperiods").classList.remove("flexed");
        cObj("btnperiods").classList.add("hide");
    }else if(this.value == "byname"){
        cObj("statuses").classList.add("hide");
        cObj("usingname").classList.remove("hide");
        cObj("btnperiods").classList.remove("flexed");
        cObj("btnperiods").classList.add("hide");
    }else if(this.value == "byperiod"){
        cObj("statuses").classList.add("hide");
        cObj("usingname").classList.add("hide");
        cObj("btnperiods").classList.remove("hide");
        cObj("btnperiods").classList.add("flexed");
    }else{
        cObj("statuses").classList.add("hide");
        cObj("usingname").classList.add("hide");
        cObj("btnperiods").classList.remove("flexed");
        cObj("btnperiods").classList.add("hide");
    }
}

cObj("nextexams").onclick = function () {
    if (cObj("examform2").classList.contains("hide")) {


        //CHECK FOR ERRORS
        var err = 0;
        err+=checkBlank("examstartdate");
        err+=checkBlank("examenddate");
        err+=checkBlank("curriculum");
        err+=checkBlank("examjina");
        if (err == 0) {
            //check if subject is selected
            var subjects = document.getElementsByClassName("mysubjects");
            var checkedsub = 0;
            for (let ind = 0; ind < subjects.length; ind++) {
                const element = subjects[ind];
                if (element.checked == true) {
                    checkedsub++;
                }
            }
            if (checkedsub > 0) {
                cObj("errhandlers1203").innerHTML = "<p style='color:red;font-size:13px;'></p>";
                cObj("savebuttons").classList.remove("hide");
                //animation
                cObj("examform1").classList.add("animate7");
                cObj("examform2").classList.add("animate6");
                cObj("examform2").classList.remove("hide");
                setTimeout(() => {
                    cObj("examform1").classList.add("hide"); 
                    cObj("examform1").classList.remove("animate7");
                    cObj("examform2").classList.remove("animate6");
                }, 400);

                
                if (cObj("curriculum").value == "cbc") {
                    cObj("844m").classList.add("hide");
                    cObj("cbcm").classList.remove("hide");
                }else if (cObj("curriculum").value == "844" || cObj("curriculum").value == "IGCSE" || cObj("curriculum").value == "iPrimary") {
                    cObj("cbcm").classList.add("hide");
                    cObj("844m").classList.remove("hide");
                }

                //retrieve the classes that can attempt the exams
                var subjects = document.getElementsByClassName("mysubjects");
                var subjectids = "(";
                for (let ind = 0; ind < subjects.length; ind++) {
                    const element = subjects[ind];
                    if (element.checked == true) {
                        var ids = element.value.split("|")[1]+",";
                        subjectids+=ids;
                    }
                }
                subjectids = subjectids.substr(0,subjectids.length-1);
                subjectids+=")";
                var datapass = "?getClassesWithSubject="+subjectids;
                sendData2("GET","academic/academic.php",datapass,cObj("classeslisted"),cObj("loadings214"));
            }else{
                alert("Select a subject to proceed!");
            }
        }else{
            cObj("errhandlers1203").innerHTML = "<p style='color:red;font-size:14px;margin-top:10px;'>Check all fields with red border!</p>";
        }
    }else{
        alert("This is the last page");
    }
}

cObj("saveexams").onclick = function () {
    //check errors for the complete window
    var err = 0;
    if (valObj("curriculum") == "cbc") {
        err+=checkBlank("targetmscbc");
    }else if (valObj("curriculum") == "844") {
        err+=checkBlank("targetms");
    }
    if (err == 0) {
        //check if any class is selected
        var classavail = 0;
        var classesl = document.getElementsByClassName("subjectcls");
        for (let dd = 0; dd < classesl.length; dd++) {
            const element = classesl[dd];
            if (element.checked == true) {
                classavail++;
            }
        }
        if (classavail > 0) {
            cObj("errhandlers1203").innerHTML = "<p style='color:red;font-size:14px;'></p>";
            //GET SUBJECTS SELECTED
            var subjects = document.getElementsByClassName("mysubjects");
            var mysubjectlist = "(";
            for (let ind = 0; ind < subjects.length; ind++) {
                const element = subjects[ind];
                if (element.checked == true) {
                    mysubjectlist+=element.value.split("|")[1]+",";
                }
            }
            mysubjectlist = mysubjectlist.substr(0,mysubjectlist.length-1);
            mysubjectlist+=")";
            //GET CLASSLIST
            var myclasslist = document.getElementsByClassName("subjectcls");
            var classl = "(";
            for (let inf = 0; inf < myclasslist.length; inf++) {
                const elem = myclasslist[inf];
                if (elem.checked == true) {
                    classl+=elem.id.substr(5)+",";
                }
            }
            classl = classl.substr(0, classl.length-1);
            classl+=")";

            //get the data from the form
            var examname = cObj("examjina").value;
            var examstartdate = cObj("examstartdate").value;
            var examenddate = cObj("examenddate").value;
            var curriculum = cObj("curriculum").value;
            var targetms ="";        
            if (curriculum == "cbc") {
                targetms = valObj("targetmscbc");
            }else if (curriculum == "844") {
                targetms = valObj("targetms");
            }else{
                targetms = valObj("targetms");
            }
            var datapass = "?registerExams=true&examname="+examname+"&examstartdate="+examstartdate+"&examenddate="+examenddate+"&subjects="+mysubjectlist+"&classes="+classl+"&curriculum="+curriculum+"&targetms="+targetms;
            sendData1("GET","academic/academic.php",datapass,cObj("errhandlers1203"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("formsexams1").reset();
                        cObj("errhandlers1203").innerText = "";

                        //remove any animation classes and return the first window
                        cObj("examform1").classList.remove("hide");
                        cObj("examform2").classList.add("hide");
                        cObj("examform2").classList.remove("animate8");
                        cObj("examform2").classList.remove("animate6");
                        cObj("examform2").classList.remove("animate7");
                        cObj("examform1").classList.remove("animate8");
                        cObj("examform1").classList.remove("animate6");
                        cObj("examform1").classList.remove("animate7");
                        cObj("savebuttons").classList.add("hide");
                        cObj("regexams").classList.add("hide");

                        cObj("examanagement").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            alert("Select atleast one class to save!");
        }
    }else{
        cObj("errhandlers1203").innerHTML = "<p style='color:red;font-size:14px;margin-top:10px;'>Check all fields that are marked with red border!</p>";
    }
}
cObj("previousexams").onclick = function () {
    if (cObj("examform1").classList.contains("hide")) {
        cObj("savebuttons").classList.add("hide");


        //animation
        cObj("examform2").classList.add("animate8");
        cObj("examform1").classList.add("animate8");
        cObj("examform1").classList.remove("hide");
        setTimeout(() => {
            cObj("examform2").classList.add("hide"); 
            cObj("examform2").classList.remove("animate8");
            cObj("examform1").classList.remove("animate8");
        }, 400);
    }else{
        alert("This is the first page")
    }
}

cObj("cancelexams").onclick = function () {
    //remove any animation classes and return the first window
        cObj("examform1").classList.remove("hide");
        cObj("examform2").classList.add("hide");
        cObj("examform2").classList.remove("animate8");
        cObj("examform2").classList.remove("animate6");
        cObj("examform2").classList.remove("animate7");
        cObj("examform1").classList.remove("animate8");
        cObj("examform1").classList.remove("animate6");
        cObj("examform1").classList.remove("animate7");
        cObj("savebuttons").classList.add("hide");
        cObj("regexams").classList.add("hide");
}
cObj("fungash").onclick = function () {
    //remove any animation classes and return the first window
        cObj("examform1").classList.remove("hide");
        cObj("examform2").classList.add("hide");
        cObj("examform2").classList.remove("animate8");
        cObj("examform2").classList.remove("animate6");
        cObj("examform2").classList.remove("animate7");
        cObj("examform1").classList.remove("animate8");
        cObj("examform1").classList.remove("animate6");
        cObj("examform1").classList.remove("animate7");
        cObj("savebuttons").classList.add("hide");
        cObj("regexams").classList.add("hide");
}

cObj("registerexamsbtn").onclick = function () {
    cObj("regexams").classList.remove("hide");
    var datapass = "?retrievsubjectlist=true";
    sendData2("GET","academic/academic.php",datapass,cObj("subjectslists"),cObj("loadings213"));
}
//VIEW AVAILABLE EXAMS AND EDITING
cObj("displaysubjects").onclick = function () {
    //check if any option is selected
    cObj("err123d").innerHTML = "";
    var err = 0;
    err+=checkBlank("option1");
    if (err == 0) {
        cObj("err123d").innerHTML = "";
        var option = cObj("option1").value;
        if (option == "allactive") {
            var datapass = "?getExamination="+option;
            sendData1("GET","academic/academic.php",datapass,cObj("holdExaminfor"));
        }else if (option == "byname") {
            var er = 0;
            er+=checkBlank("usenames2");
            if (er == 0) {
                cObj("err123d").innerHTML = "";
                var datapass = "?getExamination="+option+"&subjectnames="+cObj("usenames2").value;
                sendData1("GET","academic/academic.php",datapass,cObj("holdExaminfor"));
            }else{
                cObj("err123d").innerHTML = "<p style='color:red;font-size:13px;text-align:left;'>Enter the subject name and try again!</p>";
            }
        }else if (option == "byperiod") {
            var er = 0;
            er+=checkBlank("btnperiod");
            er+=checkBlank("endsperiod");
            if (er == 0) {
                cObj("err123d").innerHTML = "<p style='color:red;font-size:13px;text-align:left;'></p>";
                var startdate = cObj("btnperiod").value;
                var enddate = cObj("endsperiod").value;
                if (startdate > enddate) {
                    sday = startdate;
                    startdate = enddate;
                    enddate = sday;
                }
                var datapass = "?getExamination="+option+"&sdate="+startdate+"&enddate="+enddate;
                sendData1("GET","academic/academic.php",datapass,cObj("holdExaminfor"));
            }else{
                cObj("err123d").innerHTML = "<p style='color:red;font-size:13px;text-align:left;'>Fill both dates and try again!</p>";
            }
        }else if (option == "bystatus") {
            var er = 0;
            er+=checkBlank("status1");
            if (er == 0) {
                var status = cObj("status1").value;
                var datapass = "?getExamination="+option+"&status="+status;
                sendData1("GET","academic/academic.php",datapass,cObj("holdExaminfor"));
            }else{
                cObj("err123d").innerHTML = "<p style='color:red;font-size:13px;text-align:left;'>Select an option and try again!</p>";
            }
        }
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var viewExams = document.getElementsByClassName("viewExams");
                    for (let dc = 0; dc < viewExams.length; dc++) {
                        const element = viewExams[dc];
                        setExamListener(element.id);
                    }
                    var prints_exams = document.getElementsByClassName("prints_exams");
                    for (let ind = 0; ind < prints_exams.length; ind++) {
                        const element = prints_exams[ind];
                        element.addEventListener("click",printExamsFunc);
                    }
                    var view_exam_result = document.getElementsByClassName("view_exam_result");
                    for (let index = 0; index < view_exam_result.length; index++) {
                        const element = view_exam_result[index];
                        element.addEventListener("click",getExamsInfor);
                    }
                    var delete_exams_ = document.getElementsByClassName("delete_exams_");
                    for (let index = 0; index < delete_exams_.length; index++) {
                        const element = delete_exams_[index];
                        element.addEventListener("click",delete_exams);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);

    }else{
        cObj("err123d").innerHTML = "<p style='color:red;font-size:14px;'>Select an option to display the exams!</p>";
    }
}

cObj("display_exams_for_classes").onclick = function () {
    // first check if the class list button is working
    if (cObj("class_label_exams_result") != null && cObj("class_label_exams_result") != undefined) {
        cObj("results_output").innerHTML = "";
        var err = 0;
        err+=checkBlank("class_label_exams_result");
        if (err == 0) {
            var datapass = "?get_perfomance_for_class=true&class_sat="+valObj("class_label_exams_result")+"&exam_id="+cObj("exams_id_result").innerText;
            sendData2("GET","academic/academic.php",datapass,cObj("exams_window_display"),cObj("exams_details_loader"));
        }else{
            cObj("results_output").innerHTML = "<p class='text-danger'>Select a class to proceed</p>";
        }
    }else{
        cObj("results_output").innerHTML = "<p class='text-danger'>Class sitting for this exams has not been uploaded yet</p>";
    }
}

function setExamListener(id){
    cObj(id).addEventListener("click",viewExamslistener);
}
function printExamsFunc() {
    var ids = this.id.substring(12);
    cObj("exsms_name").innerText = cObj("exams_names_edit"+ids).innerText;
    cObj("printer_window").classList.remove("hide");
    var datapass = "?getexams_classes="+ids;
    cObj("exam_ids_printing").value = ids;
    sendData1("GET","academic/academic.php",datapass,cObj("all_classes_here"));
}
function viewExamslistener() {
    var examid = this.id.substr(8);
    cObj("examidsd").innerText = examid;
    //get the exam details
    var datapass = "?get_Exam_Information="+examid;
    sendData1("GET","academic/academic.php",datapass,cObj("exams_infor"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                var exams_infor = cObj("exams_infor").innerText;
                if (exams_infor != "Null") {
                    var split = exams_infor.split(",");
                    cObj("examjina1").value = split[0];
                    cObj("examenddate1").value = split[2];
                    if(split[1] == "844"){
                        cObj("844m1").classList.remove("hide");
                        cObj("cbcm1").classList.add("hide");
                        cObj("targetms1").value = split[3];
                        cObj("84412").selected = true;
                    }else if (split[1] == "cbc") {
                        cObj("cbc12").selected = true;
                        cObj("844m1").classList.add("hide");
                        cObj("cbcm1").classList.remove("hide");
                        var opts = document.getElementsByClassName("my_option");
                        for (let undo = 0; undo < opts.length; undo++) {
                            const element = opts[undo];
                            if (element.value == split[3]) {
                                element.selected = true;
                                break;
                            }
                        }
                    }else if (split[1] == "IGCSE"){
                        cObj("844m1").classList.remove("hide");
                        cObj("cbcm1").classList.add("hide");
                        cObj("targetms1").value = split[3];
                        cObj("IGCSE12").selected = true;
                    }else if (split[1] == "iPrimary"){
                        cObj("844m1").classList.remove("hide");
                        cObj("cbcm1").classList.add("hide");
                        cObj("targetms1").value = split[3];
                        cObj("iPrimary12").selected = true;
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    //get the subject information
    reloadClassAndSubjects(examid);
    //display the exams window
    cObj("editexams").classList.remove("hide");
    //get the examination details

}
function setDeleteSubject(id){
    cObj(id).addEventListener("click",deleteSubject);
}
function deleteSubject() {
    var subjectid = this.id.substr(5);
    var examid = cObj("examidsd").innerText;
    var datapass = "?removeSubject="+subjectid+"&examinationId="+examid;
    sendData1("GET","academic/academic.php",datapass,cObj("errhandlers12031"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //removed 
                cObj("errhandlers12031").innerHTML = "<p style='color:green;font-size:14px;'>Removed successfully..</p>";
                    //reload the new class list
                reloadClassAndSubjects(examid);
                setTimeout(() => {
                    cObj("errhandlers12031").innerHTML = "";
                }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 400);
}
cObj("cancelexams1").onclick = function () {
    cObj("editexams").classList.add("hide");
}
cObj("fungash1").onclick = function () {
    cObj("editexams").classList.add("hide");
}
cObj("cancelexams2").onclick = function () {
    cObj("part1").classList.remove("hide");
    cObj("part2").classList.add("hide");
    cObj("savebuttons2").classList.add("hide");
    var addsubj = document.getElementsByClassName("addsubjects");
    for (let ind = 0; ind < addsubj.length; ind++) {
        const element = addsubj[ind];
        element.checked = false;
    }
    cObj("addsubjects").classList.add("hide");
}
cObj("fungash2").onclick = function () {
    cObj("addsubjects").classList.add("hide");
}
cObj("addsubjbtn").onclick = function () {
    cObj("addsubjects").classList.remove("hide");
    //get the exam id
    var examid = cObj("examidsd").innerText;
    var datapass = "?getnewsubjectdata="+examid;
    sendData2("GET","academic/academic.php",datapass,cObj("subjectslists2"),cObj("loadings2132"));
    setTimeout(() => {
        var timeout = 0;
        var idss = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(idss);                        
            }
            if (cObj("loadings2132").classList.contains("hide")) {
                //setting listener to the delete button of the subject list
                var addsubj = document.getElementsByClassName("addsubjects");
                for (let ind = 0; ind < addsubj.length; ind++) {
                    const element = addsubj[ind];
                    setAddsubjectNew(element.id);
                }
                stopInterval(idss);
            }
        }, 100);
    }, 200);
}
function setAddsubjectNew(ids) {
    cObj(ids).addEventListener("change",addSubjectNew);
}
function addSubjectNew() {
    if (this.checked == true) {
        //set the name and the subject id
        var subjectname = this.value.split("|")[1];
        var subjectid = this.value.split("|")[0];
        cObj("subject_name").innerText = subjectname;
        cObj("subject_id").innerText = subjectid;
        //go to part two
        cObj("part2").classList.remove("hide");
        cObj("part1").classList.add("hide"); 
        cObj("savebuttons2").classList.remove("hide");
        var datapass = "?getAddsubjectClass="+subjectid;
        sendData2("GET","academic/academic.php",datapass,cObj("classlist45332"),cObj("loadings2132ss"));
    }
}

cObj("returnback").onclick = function () {
    cObj("part1").classList.remove("hide");
    cObj("part2").classList.add("hide");
    cObj("savebuttons2").classList.add("hide");
    var addsubj = document.getElementsByClassName("addsubjects");
    for (let ind = 0; ind < addsubj.length; ind++) {
        const element = addsubj[ind];
        element.checked = false;
    }
}

cObj("saveexams2").onclick = function () {
    //check if a subject is selected
    var selext_count = 0
    var selectclasseshere = document.getElementsByClassName("selectclasseshere");
    for (let index = 0; index < selectclasseshere.length; index++) {
        const element = selectclasseshere[index];
        if (element.checked == true) {
            selext_count++;
        }
    }
    if (selext_count > 0) {
        cObj("errorhandler1203").innerHTML = "<p style='color:red;font-size:13px;'></p>";
        var classes = "";
        var subjectid = cObj("subject_id").innerText;
        for (let index = 0; index < selectclasseshere.length; index++) {
            const element = selectclasseshere[index];
            if (element.checked == true) {
                classes+=element.value+",";
            }
        }
        classes = classes.substr(0,classes.length-1);
        var datapass = "?subject_id="+subjectid+"&class_selected="+classes+"&exam_id="+cObj("examidsd").innerText;
        sendData1("GET","academic/academic.php",datapass,cObj("errorhandler1203"));
        setTimeout(() => {
            var timeout = 0;
            var idss = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(idss);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //reload the new class list
                    reloadClassAndSubjects(cObj("examidsd").innerText);
                    //few resetings
                    cObj("errorhandler1203").innerHTML = "";
                    cObj("part1").classList.remove("hide");
                    cObj("part2").classList.add("hide");
                    cObj("savebuttons2").classList.add("hide");
                    var addsubj = document.getElementsByClassName("addsubjects");
                    for (let ind = 0; ind < addsubj.length; ind++) {
                        const element = addsubj[ind];
                        element.checked = false;
                    }
                    //close the window
                    cObj("addsubjects").classList.add("hide");
                    stopInterval(idss);
                }
            }, 100);
        }, 200);
    }else{
        cObj("errorhandler1203").innerHTML = "<p style='color:red;font-size:13px;'>Select a class to proceed!</p>";
    }
}
function reloadClassAndSubjects(examid) {
    var datapass = "?getExamsSubjects="+examid;
    sendData2("GET","academic/academic.php",datapass,cObj("subjectslists1"),cObj("loadings2131"));
    setTimeout(() => {
        var timeout = 0;
        var idss = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(idss);                        
            }
            if (cObj("loadings2131").classList.contains("hide")) {
                //setting listener to the delete button of the subject list
                var deleted = document.getElementsByClassName("fungasubjects");
                for (let ind = 0; ind < deleted.length; ind++) {
                    const element = deleted[ind];
                    setDeleteSubject(element.id);
                }
                stopInterval(idss);
            }
        }, 100);
    }, 200);
    //reload classes
    var datapass = "?getExamsClasses="+examid;
    sendData2("GET","academic/academic.php",datapass,cObj("classeslisted1"),cObj("loadings2141"));
    setTimeout(() => {
        var timeout = 0;
        var idss = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(idss);                        
            }
            if (cObj("loadings2141").classList.contains("hide")) {
                //setting listener to the delete button of the subject list
                var deleted = document.getElementsByClassName("toasubjects");
                for (let ind = 0; ind < deleted.length; ind++) {
                    const element = deleted[ind];
                    setDeleteClass(element.id);
                }
                stopInterval(idss);
            }
        }, 100);
    }, 200);
}
function setDeleteClass(ids) {
    cObj(ids).addEventListener("click",deleteClass);
}
function deleteClass() {
    var clas_s = this.id.substr(3);
    var datapass = "?remove_class="+clas_s+"&exam_s_id="+cObj("examidsd").innerText;
    sendData1("GET","academic/academic.php",datapass,cObj("err102_op"));
    setTimeout(() => {
        var timeout = 0;
        var idss = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(idss);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                reloadClassAndSubjects(cObj("examidsd").innerText);
                stopInterval(idss);
            }
        }, 100);
    }, 200);
}

cObj("cancelexams232").onclick = function () {
    cObj("addclasswin").classList.add("hide");
}
cObj("fungash232").onclick = function () {
    cObj("addclasswin").classList.add("hide");
}
cObj("addclassbtn").onclick = function () {
    //set eaxm id 
    cObj("exam_sid").innerText = cObj("examidsd").innerText;
    //get class data from the database
    var datapass = "?exam_id_s="+cObj("examidsd").innerText;
    sendData2("GET","academic/academic.php",datapass,cObj("classlist45221"),cObj("loadingskh87"));
    cObj("addclasswin").classList.remove("hide");
}
cObj("saveexams232").onclick = function () {
    //check if any check box is checked
    var checkcount = 0;
    var add_class = document.getElementsByClassName("add_class_check");
    for (let index = 0; index < add_class.length; index++) {
        const element = add_class[index];
        if (element.checked == true) {
            checkcount++;
        }
    }
    if (checkcount > 0) {
        cObj("errorhandler78h7").innerHTML = "";
        var class_list = "";
        for (let index = 0; index < add_class.length; index++) {
            const element = add_class[index];
            if (element.checked == true) {
                class_list+=element.value+",";
            }
        }
        class_list = class_list.substr(0,class_list.length-1);
        var datapass = "?add_classes="+class_list+"&ex_am_id="+cObj("examidsd").innerText;
        sendData1("GET","academic/academic.php",datapass,cObj("errorhandler78h7"));
        setTimeout(() => {
            var timeout = 0;
            var idss = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(idss);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    reloadClassAndSubjects(cObj("examidsd").innerText);
                    cObj("errorhandler78h7").innerHTML = "";
                    cObj("addclasswin").classList.add("hide");
                    stopInterval(idss);
                }
            }, 100);
        }, 200);
    }else{
        cObj("errorhandler78h7").innerHTML = "<p style='color:red;font-size:13px;'>Select a class to proceed!</p>";
    }

}
cObj("populate_btn").onclick = function () {
    cObj("display_result").classList.add("hide");
    cObj("record_exams_id").classList.remove("hide");
    if(typeof(cObj("cls_lists")) != 'undefined' && cObj("cls_lists") != null){
        cObj("exma_record_err").innerHTML = "<p style='color:red;font-size:13px'></p>";
        //check for errors first
        var err = checkBlank("cls_lists");
        err+=checkBlank("grade_mode");
        if (err == 0) {
            cObj("resulters").classList.remove("hide");
            cObj("finded").classList.add("hide");
            cObj("exma_record_err").innerHTML = "<p style='color:red;font-size:13px'></p>";
            //send the data to the database and retrieve the class requested
            var datapass = "?get_class_for_exams="+cObj("cls_lists").value+"&subject__id="+cObj("sub_jectlists").value+"&exam__id="+cObj("exam_list").value+"&grd_mode="+cObj("grade_mode").value;
            sendData1("GET","academic/academic.php",datapass,cObj("record_exams_id"));
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("loadings").classList.contains("hide")) {
                        //set butns with listeners
                        var btns = document.getElementsByClassName("save_marks_butns");
                        for (let indf = 0; indf < btns.length; indf++) {
                            const element = btns[indf];
                            setViewClassListeners(element.id);
                        }
                        var btns2 = document.getElementsByClassName("selected_grade");
                        for (let index = 0; index < btns2.length; index++) {
                            const element = btns2[index];
                            setGraderListener(element.id);
                        }
                        var manual_grading = document.getElementsByClassName("manual_grading");
                        for (let manuals = 0; manuals < manual_grading.length; manuals++) {
                            const element = manual_grading[manuals];
                            element.addEventListener("keyup",setGrades);
                            element.addEventListener("change",setGrades);
                        }

                        if (cObj("search_results_2") != null && cObj("search_results_2") != undefined) {
                            cObj("search_results_2").addEventListener("keyup", searchExamEtry);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("exma_record_err").innerHTML = "<p style='color:red;font-size:13px'>Check all fields colored with red-border!</p>";
        }
    }else{
        cObj("exma_record_err").innerHTML = "<p style='color:red;font-size:13px'>Select a class to proceed!</p>";
    }
}
function setGrades() {
    var values = this.value;
    var grade_mode = valObj("grade_mode");
    if (grade_mode != "iPrimary" && grade_mode != "IGCSE") {
        if (values.length > 0) {
            var exams_grades = cObj("exams_grades").innerText;
            if (exams_grades.length > 0) {
                var grades = JSON.parse(exams_grades);
                // console.log(grades);
                var scored_grade = "N/A";
                for (let index = 0; index < grades.length; index++) {
                    const element = grades[index];
                    if ((values*1) <= (element.max*1) && (values*1) >= (element.min*1)) {
                        scored_grade = element.grade_name;
                        break;
                    }
                }
                cObj("grade"+this.id.substr(5)).innerHTML = scored_grade;
            }else{
                cObj("grade"+this.id.substr(5)).innerHTML = "N/A";
            }
        }else{
            cObj("grade"+this.id.substr(5)).innerHTML = "N/A";
        }
    }else if (grade_mode == "IGCSE") {
        if (values.length > 0) {
            values*=1;
            var scored_grade = "N/A";
            if (values <= 100 && values > 90) {
                scored_grade = "9";
            }else if (values <= 90 && values > 80) {
                scored_grade = "8";
            }else if (values <= 80 && values > 73) {
                scored_grade = "7";
            }else if (values <= 73 && values > 67) {
                scored_grade = "6";
            }else if (values <= 67 && values > 59) {
                scored_grade = "5";
            }else if (values <= 59 && values > 53) {
                scored_grade = "4";
            }else if (values <= 53 && values > 46) {
                scored_grade = "3";
            }else if (values <= 46 && values > 39) {
                scored_grade = "2";
            }else if (values <= 39 && values > 34) {
                scored_grade = "1";
            }else if (values <= 34 && values >= 0) {
                scored_grade = "U";
            }else{
                scored_grade = "NA"
            }
            cObj("grade"+this.id.substr(5)).innerHTML = scored_grade;
        }else{
            cObj("grade"+this.id.substr(5)).innerHTML = "N/A";
        }
    }else if (grade_mode == "iPrimary") {
        if (values.length > 0) {
            values*=1;
            var scored_grade = "N/A";
            if (values <= 100 && values > 90) {
                scored_grade = "A*";
            }else if (values <= 90 && values > 80) {
                scored_grade = "A";
            }else if (values <= 80 && values > 70) {
                scored_grade = "B";
            }else if (values <= 70 && values > 60) {
                scored_grade = "C";
            }else if (values <= 60 && values > 50) {
                scored_grade = "D";
            }else if (values <= 50 && values > 40) {
                scored_grade = "E";
            }else if (values <= 40 && values > 30) {
                scored_grade = "F";
            }else if (values <= 30 && values >= 0) {
                scored_grade = "U";
            }else{
                scored_grade = "NA"
            }
            cObj("grade"+this.id.substr(5)).innerHTML = scored_grade;
        }else{
            cObj("grade"+this.id.substr(5)).innerHTML = "N/A";
        }
    }
}

function setViewClassListeners(ids) {
    cObj(ids).addEventListener("click",viewClassListener);
}
function viewClassListener() {
    var idds = this.id.substr(9);
    if (cObj("imager3_e"+idds).classList.contains("hide")) {
        var err = checkBlank("input"+idds);
        var data = cObj("grade"+idds).innerText;
        if (data.length  > 10) {
            err++;
        }
        var manual_grades = document.getElementsByClassName("manual_grades");
        // console.log(manual_grades);
        var marks_marks = cObj("max-marks-hold").innerText*1;
        if (manual_grades.length > 0) {
            if (cObj("input_2"+idds).value > marks_marks) {
                err++;
                alert("Marks can`t be more than "+marks_marks)
            }
            err+=checkBlank("input_2"+idds);
        }else{
            if (cObj("input"+idds).value > marks_marks) {
                err++;
                alert("Marks can`t be more than "+marks_marks)
            }
        }
        if (err == 0) {
            var subject_marks = manual_grades.length > 0 ? cObj("input_2"+idds).value : cObj("input"+idds).value;
            var grade_modes_holder = (cObj("grade_modes_holder") != null && cObj("grade_modes_holder") != undefined) ? valObj("grade_modes_holder") : "844";
            var datapassing = "?save_student_marks="+subject_marks+"&examidds="+cObj("exam_list").value+"&subjectidds="+cObj("sub_jectlists").value+"&subject_grade="+data+"&grade_method="+cObj("grade_mode").value+"&student_ids="+idds+"&class_name="+cObj("class_siter").innerText+"&grade_modes_holder="+grade_modes_holder;
            sendData2("GET","academic/academic.php",datapassing,cObj("errhandler_"+idds),cObj("imagered"+idds));
            if (manual_grades.length > 0) {
                cObj("input_2"+idds).classList.add("hide");
            }
            cObj("input"+idds).classList.add("hide");
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("imagered"+idds).classList.contains("hide")) {
                        cObj("imager2_e"+idds).classList.remove("hide");
                        setTimeout(() => {
                            cObj("imager3_e"+idds).classList.remove("hide");
                            cObj("imager2_e"+idds).classList.add("hide");
                            cObj("errhandler_"+idds).innerHTML = "<p style='color:green;font-size:12px;font-weight:600;'>Saved ✔</p>";
                            cObj("errhandler_"+idds).classList.remove("hide");
                        }, 200);
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    }else{

    }
}
function setGraderListener(ids) {
    cObj(ids).addEventListener("change",graderListner);
}
function graderListner() {
    if (this.value == "4") {
        cObj("grade"+this.id.substr(5)).innerHTML = "<span >E.E</span>";
    }else if (this.value == "3") {
        cObj("grade"+this.id.substr(5)).innerHTML = "<span >M.E</span>";
    }else if (this.value == "2") {
        cObj("grade"+this.id.substr(5)).innerHTML = "<span >A.E</span>";
    }else if (this.value == "1") {
        cObj("grade"+this.id.substr(5)).innerHTML = "<span >B.E</span>";
    }else if (this.value == "A") {
        cObj("grade"+this.id.substr(5)).innerHTML = "<span >Absent</span>";
    }
}
cObj("saveexams1").onclick = function () {
    //get the inputs and check if they are blank
    var err = 0;
    err+=checkBlank("examjina1");
    err+=checkBlank("examenddate1");
    err+=checkBlank("curriculum1");
    var targems = "";
    if (cObj("curriculum1").value == "844") {
        err+=checkBlank("targetms1");
        targems = cObj("targetms1").value;
    }else if (cObj("curriculum1").value == "cbc") {
        err+=checkBlank("targetmscbc1");
        targems = cObj("targetmscbc1").value;
    }
    if (err == 0) {
        cObj("error_1201").innerHTML = "";
        var datapassing = "?update_exams=true&exam_i_d="+cObj("examidsd").innerText+"&exam_name="+cObj("examjina1").value+"&exam_enddate="+cObj("examenddate1").value+"&exam_curriculum="+cObj("curriculum1").value+"&target_ms="+targems;
        sendData1("GET","academic/academic.php",datapassing,cObj("error_1201"));
        setTimeout(() => {
            var timeout = 0;
            var idss = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(idss);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("editexams").classList.add("hide");
                    cObj("examanagement").click();
                    cObj("error_1201").innerHTML = "";
                    stopInterval(idss);
                }
            }, 100);
        }, 200);
    }else{
        cObj("error_1201").innerHTML = "<p style='color:red;font-size:13px;'>Fill all the fields marked with red borders!</p>";
    }
}
cObj("curriculum1").onchange = function () {
    if (this.value.length > 0) {
        if (this.value == "844") {
            cObj("cbcm1").classList.add("hide");
            cObj("844m1").classList.remove("hide");
        }else if (this.value == "cbc") {
            cObj("844m1").classList.add("hide");
            cObj("cbcm1").classList.remove("hide");
        }
    }
}
cObj("option_exams").onchange = function () {
    if (this.value == "view_exams") {
        displayTerms();
        cObj("exam_fill").classList.add("hide");
        cObj("view_exams_record").classList.remove("hide");
        cObj("view_exams_class_record").classList.add("hide");
        cObj("display_class_result").classList.add("hide");
    }else if (this.value == "fill_in_exams") {
        displayExams();
        cObj("exam_fill").classList.remove("hide");
        cObj("view_exams_record").classList.add("hide");
        cObj("view_exams_class_record").classList.add("hide");
        cObj("display_class_result").classList.add("hide");
    }else if (this.value == "view_per_class") {
        getTerms_ofClass();
        cObj("exam_fill").classList.add("hide");
        cObj("view_exams_record").classList.add("hide");
        cObj("view_exams_class_record").classList.remove("hide");
        cObj("select_one_exams").classList.add("hide");
        cObj("select_one_class_siting").classList.add("hide");
        cObj("display_btns").classList.add("hide");
        cObj("display_class_result").classList.remove("hide");
        cObj("record_exams_id").classList.add("hide");
        cObj("display_result").classList.add("hide");
    }
}
function displayExams() {
    //if (cObj("record_exams_id").innerText.length < 10) {
        cObj("exam_select").classList.remove("hide");
        var datapass = "?get_exam_available=true";
        sendData1("GET","academic/academic.php",datapass,cObj("exam_select"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    if (typeof(cObj("exam_list")) != 'undefined' && cObj("exam_list") != null){
                        cObj("exam_list").addEventListener("change",selectListeners);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
        cObj("btn_panel").classList.add("hide");
        cObj("subject_list").classList.add("hide");
        cObj("classes_list").classList.add("hide");
        cObj("grading_methods").classList.add("hide");
    //}
}
function displayTerms() {
    cObj("exam_attempt").classList.add("hide");
    cObj("subjects_done").classList.add("hide");
    cObj("class_sitters").classList.add("hide");
    cObj("display_results").classList.add("hide");
    var datapass = "?show_terms=true";
    sendData1("GET","academic/academic.php",datapass,cObj("select_term"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("term_selection").addEventListener("change",termSelection);
                cObj("exam_attempt").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function termSelection() {
    cObj("subjects_done").classList.add("hide");
    cObj("class_sitters").classList.add("hide");
    cObj("display_results").classList.add("hide");
    var timestart = this.value.split("|")[0];
    var timeend = this.value.split("|")[1];
    var datapass = "?get_exams_attempt=true&time_start="+timestart+"&time_ends="+timeend;
    sendData1("GET","academic/academic.php",datapass,cObj("exam_attempt"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (typeof(cObj("exam_selected")) != 'undefined' && cObj("exam_selected") != null){
                    cObj("exam_selected").addEventListener("change",examSelection);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function examSelection() {
    cObj("subjects_done").classList.add("hide");
    cObj("class_sitters").classList.add("hide");
    cObj("display_results").classList.add("hide");
    //get the subjects done so that we can get the classes that sat for the exam
    var exam_id = this.value.substr(8);
    var datapass = "?get_exam_subjects="+exam_id;
    sendData1("GET","academic/academic.php",datapass,cObj("subjects_done"),cObj("anonymus"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (typeof(cObj("subjects_done_mine")) != 'undefined' && cObj("subjects_done_mine") != null){
                    cObj("subjects_done_mine").addEventListener("change",classSelection);
                }
                cObj("subjects_done").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function classSelection() {
    cObj("class_sitters").classList.add("hide");
    cObj("display_results").classList.add("hide");
    var exam_id = cObj("exam_selected").value.substr(8);
    var subjectid = this.value;
    var datapass = "?get_subject_class=true&subject_exam_id="+subjectid+"&exam_ids_sub="+exam_id;
    sendData2("GET","academic/academic.php",datapass,cObj("class_sitters"),cObj("anonymus"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("anonymus").classList.contains("hide")) {
                if (typeof(cObj("subjects_done_mine")) != 'undefined' && cObj("subjects_done_mine") != null){
                    cObj("subjects_done_mine").addEventListener("change",classSelection);
                }
                cObj("class_sitters").classList.remove("hide");
                cObj("display_results").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("display_results").onclick = function () {
    cObj("record_exams_id").classList.add("hide");
    cObj("display_result").classList.remove("hide");
    //check for blanks
    if (typeof(cObj("classes_sitting")) != undefined && cObj("classes_sitting") != null){
        cObj("error_handlers").innerHTML = "";
        var err = 0;
        err+=checkBlank("classes_sitting");
        err+=checkBlank("subjects_done_mine");
        err+=checkBlank("exam_selected");
        err+=checkBlank("term_selection");
        if (err == 0) {
            cObj("resulters").classList.remove("hide");
            cObj("finded").classList.add("hide");
            cObj("error_handlers").innerHTML = "";
            var datapass= "?ex_am_ids="+cObj("exam_selected").value.substr(8)+"&sub_ject_ids="+cObj("subjects_done_mine").value+"&class_sit_ting="+cObj("classes_sitting").value;
            sendData1("GET","academic/academic.php",datapass,cObj("display_result"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //set listeners
                        var change_marks = document.getElementsByClassName("change_marks");
                        for (let ind = 0; ind < change_marks.length; ind++) {
                            const element = change_marks[ind];
                            setChangeMarksListener(element.id);
                        }

                        if (cObj("search_results") != "null" && cObj("search_results") != undefined) {
                            cObj("search_results").addEventListener("keyup", searchTable);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("error_handlers").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Check errors of the field with red borders!</p>";
        }
    }else{
        cObj("error_handlers").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>No classes is available.!</p>";
    }
}
function setChangeMarksListener(id) {
    cObj(id).addEventListener("click",changeMarksListener);
}
function changeMarksListener() {
    var recordid = this.id.substr(12);
    cObj("change_record_marks").classList.remove("hide");
    cObj("record_id").innerText = recordid;
    cObj("student_names").innerText = cObj("stud_name"+recordid).innerText;
    cObj("subjectsNames").innerText = cObj("subject_name_record").innerText;
    cObj("examsName").innerText = cObj("exam_name_record").innerText;
    var grade = cObj("grd"+recordid).innerText;

    cObj("cbcmode1").classList.add("hide");
    cObj("cbcmode2").classList.remove("hide");
    cObj("gradeMethod").innerText = valObj("curriculum"+recordid);
    cObj("844_mode1").value = cObj("subject_marks"+recordid).innerText.trim();
    cObj("grade_scored").innerText = grade;
    if (valObj("curriculum"+recordid) == "cbc") {
        cObj("change_cbc_marks").value = cObj("subject_marks"+recordid).innerText.trim();
        if (grade.length > 2 && (grade == "E.E" || grade == "M.E" || grade == "A.E" || grade == "B.E")) {
            cObj("gradeMethod").innerText = valObj("curriculum"+recordid);
            cObj("cbcmode1").classList.remove("hide");
            cObj("cbcmode2").classList.add("hide");
            var grade_scores = 0;
            if (grade == "E.E") {
                cObj("grade_scored").innerText = "Exceeding Expectation";
                grade_scores = 4;
            }else if (grade == "M.E") {
                cObj("grade_scored").innerText = "Meeting Expectation";
                grade_scores = 3;
            }else if (grade == "A.E") {
                cObj("grade_scored").innerText = "Approaching Expectation";
                grade_scores = 2;
            }else if (grade == "B.E") {
                cObj("grade_scored").innerText = "Below Expectation";
                grade_scores = 1;
            }else{
                cObj("grade_scored").innerText = "Absent";
            }
            if (grade!="Absent") {
                cObj("idd"+grade_scores).selected = true;
            }else{
                cObj("iddA").selected = true;
            }
        }
    }
}

function searchExamEtry() {
    var values = this.value.toLowerCase();
    var my_adm_no = document.getElementsByClassName("my_adm_no");
    var my_students_names = document.getElementsByClassName("my_students_names");
    
    for (let index = 0; index < my_adm_no.length; index++) {
        var our_id = my_adm_no[index].id.substring(9);
        const number = my_adm_no[index].innerText.toLowerCase();
        var student_names = my_students_names[index].innerText.toLowerCase();
        if (number.includes(values) || student_names.includes(values)) {
            cObj("table_data_entry"+our_id).classList.remove("hide");
        }else{
            cObj("table_data_entry"+our_id).classList.add("hide");
        }
    }
}

function searchTable() {
    var values = this.value.toLowerCase();
    var numbers = document.getElementsByClassName("numbers");
    var student_names_out = document.getElementsByClassName("student_names_out");
    var subjects_scores = document.getElementsByClassName("subjects_scores");
    var subjects_grade = document.getElementsByClassName("subjects_grade");
    
    for (let index = 0; index < numbers.length; index++) {
        var our_id = student_names_out[index].id.substring(9);
        const number = numbers[index].innerText.toLowerCase();
        var student_names = student_names_out[index].innerText.toLowerCase();
        var grades = subjects_grade[index].innerText.toLowerCase();
        var scores = subjects_scores[index].innerText.toLowerCase();
        if (number.includes(values) || student_names.includes(values) || grades.includes(values) || scores.includes(values)) {
            cObj("table_data"+our_id).classList.remove("hide");
        }else{
            cObj("table_data"+our_id).classList.add("hide");
        }
    }
}



cObj("844_mode1").onkeyup = function () {
    var values = this.value;
    var grading_method = cObj("gradeMethod").innerText;
    if (grading_method == "844") {
        if (this.value.length > 0) {
            var scored_grade = "N/A";
            if (hasJsonStructure(cObj("exam_grades_editing").innerText)) {
                var exam_grades_editing = JSON.parse(cObj("exam_grades_editing").innerText);
                for (let index = 0; index < exam_grades_editing.length; index++) {
                    const element = exam_grades_editing[index];
                    if ((values*1) <= (element.max*1) && (values*1) >= (element.min*1)) {
                        scored_grade = element.grade_name;
                        break;
                    }
                }
            }
            // console.log(scored_grade);
            cObj("grade_scored").innerHTML = scored_grade;
        }
    }else if (grading_method == "IGCSE") {
        if (values.length > 0) {
            values*=1;
            var scored_grade = "N/A";
            if (values <= 100 && values >= 91) {
                scored_grade = "9";
            }else if (values <= 90 && values >= 81) {
                scored_grade = "8";
            }else if (values <= 80 && values >= 74) {
                scored_grade = "7";
            }else if (values <= 73 && values >= 68) {
                scored_grade = "6";
            }else if (values <= 67 && values >= 60) {
                scored_grade = "5";
            }else if (values <= 59 && values >= 54) {
                scored_grade = "4";
            }else if (values <= 53 && values >= 47) {
                scored_grade = "3";
            }else if (values <= 46 && values >= 40) {
                scored_grade = "2";
            }else if (values <= 39 && values >= 35) {
                scored_grade = "1";
            }else if (values <= 34 && values >= 0) {
                scored_grade = "U";
            }else{
                scored_grade = "NA"
            }
            cObj("grade_scored").innerHTML = scored_grade;
        }else{
            cObj("grade_scored").innerHTML = scored_grade;
        }
    }else if (grading_method == "iPrimary") {
        if (values.length > 0) {
            values*=1;
            var scored_grade = "N/A";
            if (values <= 100 && values >= 91) {
                scored_grade = "A*";
            }else if (values <= 90 && values >= 81) {
                scored_grade = "A";
            }else if (values <= 80 && values >= 71) {
                scored_grade = "B";
            }else if (values <= 70 && values >= 61) {
                scored_grade = "C";
            }else if (values <= 60 && values >= 51) {
                scored_grade = "D";
            }else if (values <= 50 && values >= 41) {
                scored_grade = "E";
            }else if (values <= 40 && values >= 31) {
                scored_grade = "F";
            }else if (values <= 30 && values >= 0) {
                scored_grade = "U";
            }else{
                scored_grade = "NA"
            }
            cObj("grade_scored").innerHTML = scored_grade;
        }else{
            cObj("grade_scored").innerHTML = scored_grade;
        }
    }
}
cObj("close_change_marks").onclick = function () {
    cObj("change_record_marks").classList.add("hide");
}
cObj("close_change_marks2").onclick = function () {
    cObj("change_record_marks").classList.add("hide");
}

cObj("save_marks_change").onclick = function () {
    //get the marks of the student
    var over_err = 0;
    var datapass = "?change_marks="+cObj("record_id").innerText;
    if (cObj("cbcmode1").classList.contains("hide")) {
        var err = checkBlank("844_mode1");
        if (err == 0) {
            datapass+="&valued="+cObj("844_mode1").value+"&grade="+cObj("grade_scored").innerText;
            over_err = 1;
        }
    }else if(cObj("cbcmode2").classList.contains("hide")){
        var err = checkBlank("change_cbc_marks");
        err += checkBlank("cbc_mode1");
        if (err == 0) {
            datapass+="&valued="+cObj("change_cbc_marks").value;
            var graded = cObj("cbc_mode1").value;
            if (graded == "4") {
                datapass+="&grade=E.E";
            }else if (graded == "3") {
                datapass+="&grade=M.E";
            }else if (graded == "2") {
                datapass+="&grade=A.E";
            }else if (graded == "1") {
                datapass+="&grade=B.E";
            }else{
                datapass+="&grade=Absent";
            }
            over_err = 1;
        }
    }
    if (over_err  == 1) {
        var max_marks = cObj("my_max_marks").innerText*1;
        if (cObj("844_mode1").value <= max_marks) {
            sendData1("GET","academic/academic.php",datapass,cObj("set_class_err2"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //remove the window
                        cObj("change_record_marks").classList.add("hide");
                        //click on the display btn
                        cObj("display_results").click();
    
                        setTimeout(() => {
                            cObj("set_class_err2").innerHTML = "";
                        }, 5000);
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("set_class_err2").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Marks can`t be more than "+max_marks+"</p>";
        }
    }else{
        cObj("set_class_err2").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Fill all fields filled with red border!</p>";
    }
}
cObj("cbc_mode1").onchange = function () {
    var grade = this.value;
    if (grade == "4") {
        cObj("grade_scored").innerText = "Exceeding Expectation";
    }else if (grade == "3") {
        cObj("grade_scored").innerText = "Meeting Expectation";
    }else if (grade == "2") {
        cObj("grade_scored").innerText = "Approaching Expectation";
    }else if (grade == "1") {
        cObj("grade_scored").innerText = "Below Expectation";
    }else{
        cObj("grade_scored").innerText = "Absent";
    }
}

cObj("delete_marks").onclick = function () {
    cObj("confirm_delete").classList.remove("hide");
    cObj("name_of_students").innerText = cObj("student_names").innerText;
}
cObj("confirm_no").onclick = function () {
    cObj("confirm_delete").classList.add("hide");
}
cObj("confirm_yes").onclick = function () {
    var datapass = "?deleteData="+cObj("record_id").innerText;
    sendData1("GET","academic/academic.php",datapass,cObj("set_class_err2"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //remove the window
                cObj("confirm_delete").classList.add("hide");
                cObj("change_record_marks").classList.add("hide");
                //click on the display btn
                cObj("display_results").click();

                setTimeout(() => {
                    cObj("set_class_err2").innerHTML = "";
                }, 5000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function getTerms_ofClass() {
    var datapass = "?get_term_of_class=true";
    sendData1("GET","academic/academic.php",datapass,cObj("select_one_term"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (cObj("term_select") != null) {
                    cObj("term_select").addEventListener("change", showExamsSelect);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function showExamsSelect() {
    //send the different period so the its is returned with exams done
    cObj("select_one_class_siting").classList.add("hide");
    cObj("select_one_exams").classList.remove("hide");
    var newdata = this.value;
    var starttime = newdata.split("|")[0];
    var endtime = newdata.split("|")[1];
    var datapass = "?return_examlist=true&startedtime="+starttime+"&endingtimes="+endtime;
    sendData1("GET","academic/academic.php",datapass,cObj("select_one_exams"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (typeof(cObj("examination_selection")) != 'undefined' && cObj("examination_selection") != null){
                    cObj("examination_selection").addEventListener("change", showClassesSitting);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function showClassesSitting() {
    cObj("select_one_class_siting").classList.remove("hide");
    cObj("display_btns").classList.remove("hide");
    var datapass = "?get_classes_sitting="+this.value;
    sendData1("GET","academic/academic.php",datapass,cObj("select_one_class_siting"));
}
cObj("display_results_per_class").onclick = function () {
    //check for errors
    if (typeof(cObj("classes_sat")) != 'undefined' && cObj("classes_sat") != null){
        cObj("view_subjects_err").innerHTML = "";
        //check if a class is selected
        var err = checkBlank("classes_sat");
        if (err == 0) {
            cObj("resulters").classList.remove("hide");
            cObj("finded").classList.add("hide");
            var datapass = "?get_perfomance_for_class=true&class_sat="+cObj("classes_sat").value+"&exam_id="+cObj("examination_selection").value;
            sendData1("GET","academic/academic.php",datapass,cObj("display_class_result"));
        }else{
            cObj("view_subjects_err").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Select a class!</p>";
        }
    }else{
        cObj("view_subjects_err").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>No class is available for selection!</p>";
    }
}
cObj("go_back").onclick = function () {
    cObj("resulters").classList.add("hide");
    cObj("finded").classList.remove("hide");
}

cObj("subjects_option").onchange = function () {
    cObj("backtosubs").click();
    if (this.value == "search_subjects") {
        cObj("seachsub").classList.remove("hide");
    }else if (this.value == "display_subjects") {
        displayAllSubjects();
    }
}
cObj("option_ed").onchange = function () {
    if (this.value == "finding_a_tr") {
        cObj("searchteach").classList.remove("hide");
    }else if (this.value == "displaying_all_trs") {
        allTeachers();
    }
}
function allTeachers() {
    var selection = valObj("finding_a_tr");
    if (selection == "finding_a_tr") {
        cObj("findersd").click();
    }else{
        cObj("trnames").classList.add("hide");
        cObj("tridnum").classList.add("hide");
        cObj("searchteach").classList.add("hide");
        var datapass = "?seachby=all_trs";
        sendData1("GET","academic/academic.php",datapass,cObj("managesubstr"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var classbtns = document.getElementsByClassName("setSubclass");
                    for (let index = 0; index < classbtns.length; index++) {
                        const elem = classbtns[index];
                        setListenes(elem.id);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

cObj("print_results").addEventListener("click",printResultExams);
function printResultExams() {
    //get the data to be printed
    if (!cObj("display_class_result").classList.contains("hide")) {
        var inndata = cObj("display_class_result").innerHTML;
        cObj("print_results_page12").innerHTML = inndata;
    }
    if (!cObj("display_result").classList.contains("hide")) {
        //get the titles
        var inndata = "<div id='h4s'>"+cObj("in_titles").innerHTML+"</div>";
        inndata += "<table><tr><th>Pos.(Reg no)</th><th>Student Name</th><th>Subject Score</th><th>Grade</th></tr>";
        //get the elements with the class
        var numbers = document.getElementsByClassName("numbers");
        var student_names_out = document.getElementsByClassName("student_names_out");
        var subjects_scores = document.getElementsByClassName("subjects_scores");
        var subjects_grade = document.getElementsByClassName("subjects_grade");
        for (let index = 0; index < numbers.length; index++) {
            inndata+="<tr><td>"+numbers[index].innerText+"</td><td>"+student_names_out[index].innerText+"</td><td>"+subjects_scores[index].innerText+"</td><td>"+subjects_grade[index].innerText+"</td></tr>";
        }
        inndata+="</table>";
        if (cObj("top_bottom_3") != null) {
            inndata+=cObj("top_bottom_3").innerHTML;
        }
        cObj("print_results_page12").innerHTML = inndata;
    }

    //hide all window and show the print page
    hideWindow();
    cObj("resul_printer_page").classList.remove("hide");
}

var d;
function printResultsWindow() {
    d = window.open('','','height=500px, width=700px');
    d.document.write("<html><head><link rel='stylesheet' href='/sims/assets/CSS/homepage2.css'></head><body>");
    d.document.write(cObj("print_result_out").innerHTML);
    d.document.write("</body></html>");
    d.document.close();
    setTimeout(() => {
        d.print();
    }, 2000);
}
function closeWinC() {
    if (d != "undefined" && d != null) {
        d.close();
    }
    cObj("exam_fill_btn").click();
}

cObj("delsubno").onclick = function () {
    cObj("delsubconfirmwin").classList.add("hide");
}
cObj("delete-subject").onclick = function () {
    cObj("mssg_name").innerHTML = "Are you sure you want to delete <b>"+cObj("subnam").value+"</b> ?";
    cObj("delsubconfirmwin").classList.remove("hide");
}
cObj("delsubyes").onclick = function () {
    var datapass = "?deletesubject=true&subjectid="+cObj("subids").innerText;
    sendData1("GET","academic/academic.php",datapass,cObj("errhandlers"));
    cObj("delsubconfirmwin").classList.add("hide");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("cancelsubs").click();
                displayAllSubjects();
                setTimeout(() => {
                    cObj("errhandlers").innerHTML = "";
                }, 5000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

// cObj("click_messages").onclick = function () {
//     //connect to server port 7070
//     alert(cObj("message-me").value);
//     if ("WebSocket" in window){
//         const ws = new WebSocket("ws://127.0.0.1:7070");
//         ws.addEventListener("open",function () {
//             console.log("I am connected!");
//         });
//         ws.addEventListener("error", function () {
//             console.log("We have an error!");
//         });
//         ws.addEventListener("message", function () {
//             ws.send("My name is hillary");
//             console.log("We have an error!");
//         });
//         ws.addEventListener("close", function () {
//             ws.send("Connection interupted");
//             console.log("We have an error!");
//         });
//         console.log(ws.readyState);
//         // ws.close();
//     }else{
//         console.log("Windows is not suported")
//     }
// }
cObj("create_tt_in").onclick = function () {
    if (!cObj("create_timetabled").classList.contains("hide")) {
        // var datapass = "?get_class_informations=true";
        // sendData1("GET","academic/academic.php",datapass,cObj("class_datas_12"));
        // ask if they want to restart the process.. if they say yes restart the process
        if (cObj("create_tt_inside").classList.contains("hide")){
            cObj("prompt_timetable").classList.remove("hide");
        }
        
    }else{
        cObj("create_timetabled").classList.remove("hide");
        cObj("create_tt_inside").classList.remove("hide");
        cObj("view_tt_inxide").classList.add("hide");
        cObj("return_timetable_list").click();
        var datapass = "?get_class_informations=true";
        sendData1("GET","academic/academic.php",datapass,cObj("class_datas_12"));
        
        var hind = document.getElementsByClassName("hind");
        for (let indexes = 0; indexes < hind.length; indexes++) {
            const element = hind[indexes];
            element.classList.add("hide");
        }
        cObj("create_tt_inside").classList.remove("hide");
        cObj("create_timetabled").classList.remove("hide");
    }
}
cObj("next_infor").onclick = function () {
    var next_infor = document.getElementsByClassName("ttt_class");
    var data = "";
    var numbers = 0;
    for (let index = 0; index < next_infor.length; index++) {
        const element = next_infor[index];
        if (element.checked == true) {
            data+=element.value;
            numbers++;
        }
    }
    if (numbers > 1) {
        //get the subjects taught in the classes chosen
        var classes = "";
        var classlust = document.getElementsByClassName("ttt_class");
        for (let index = 0; index < classlust.length; index++) {
            const element = classlust[index];
            if (element.checked == true) {
                classes+=element.name+",";
            }
        }
        classes = classes.substr(0,classes.length-1);
        //get the subjects
        var datapass = "?get_class_subjects="+classes;
        sendData1("GET","academic/academic.php",datapass,cObj("class_datas_13"));
        //create an animation to fade out and in the other window
        cObj("create_tt_inside").classList.add("hide");
        cObj("create_tt_inside2").classList.remove("hide");
    }else{
        alert("Select more than one class to proceed!");
    }
}
cObj("prev_infor1").onclick = function () {
    cObj("create_tt_inside").classList.remove("hide");
    cObj("create_tt_inside2").classList.add("hide");
}
cObj("next_infor2").onclick = function () {

    //get the data from the first two windows
    var classes = "";
    var classlust = document.getElementsByClassName("ttt_class");
    for (let index = 0; index < classlust.length; index++) {
        const element = classlust[index];
        if (element.checked == true) {
            classes+=element.name+",";
        }
    }
    //get classes choosen
    classes = classes.substr(0,classes.length-1);
    // alert(classes);
    //get subjects choosen 
    var subjects_list = "";
    var counter = 0;
    var classlust = document.getElementsByClassName("ttt_class2");
    for (let index = 0; index < classlust.length; index++) {
        const element = classlust[index];
        if (element.checked == true) {
            subjects_list+=element.name+",";
            counter++;
        }
    }
    if (counter > 1) {
        cObj("create_tt_inside2").classList.add("hide");
        cObj("create_tt_inside3").classList.remove("hide");
        //get subjects_list choosen
        subjects_list = subjects_list.substr(0,subjects_list.length-1);
        // alert(subjects_list);
        var datapass = "?preview_data=true&subject_list="+subjects_list+"&classlist="+classes;
        sendData1("GET","academic/academic.php",datapass,cObj("class_datas_14"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //GET THE NUMBER OF SUBJECTS MISSING THEIR SUBJECT TEACHER
                    var trnamed = document.getElementsByClassName("trnamed");
                    var count = 0;
                    for (let index = 0; index < trnamed.length; index++) {
                        const element = trnamed[index];
                        if (element.innerText == "N/A") {
                            count++;
                        }
                    }
                    if (count > 0) {
                        var str = cObj("class_datas_14").innerHTML;
                        str+="<p><strong class='red_notice'>Note:</strong> <br><i>- Please assign the subjects with <strong>N/A</strong> a subject teacher.</i><p>";
                        str+="<p><i>Class name preceeded with  this \"<span class='invinsible'>-i</span>\" Symbol means that the subject is not taught in that class</i></p>"
                        cObj("class_datas_14").innerHTML = str;
                    }else{
                        var str = cObj("class_datas_14").innerHTML;
                        str+="<p><strong class=''>Note:</strong> <br><i>- <span id='goodtogo' style='color:green;'>All good to go  <i class='fa fa-check'></span></i>.</i><p>";
                        cObj("class_datas_14").innerHTML = str;
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        alert("Select more than one subject to proceed!");
    }
}
cObj("prev_infor2").onclick = function () {
    cObj("create_tt_inside2").classList.remove("hide");
    cObj("create_tt_inside3").classList.add("hide");
}
cObj("next_infor3").onclick = function () {
    if (cObj("goodtogo") != null || cObj("goodtogo") == "undefined") {
        cObj("create_tt_inside3").classList.add("hide");
        cObj("create_tt_inside4").classList.remove("hide");


        var ttt_class2 = document.getElementsByClassName("ttt_class2");
        var count = 0;
        for (let index = 0; index < ttt_class2.length; index++) {
            const element = ttt_class2[index];
            if (element.checked == true) {
                count++;
            }
        }
        cObj("max_lessons_in").innerText = ""+((count*2)-2)+"";
        cObj("number_of_lessons").max = (count*2)-2;
    }else{
        alert (" Please assign the subjects with N/A a subject teacher before proceeding!")
    }
}
cObj("prev_infor3").onclick = function () {
    cObj("create_tt_inside3").classList.remove("hide");
    cObj("create_tt_inside4").classList.add("hide");
}

cObj("next_infor4").onclick = function () {
    var str = "";
    str+="<div class='classlist'>";
    if (cObj("number_of_lessons").value <= (cObj("max_lessons_in").innerText*1)) {
        var ttt_class2 = document.getElementsByClassName("ttt_class2");
        var mysubjectallin = document.getElementsByClassName("mysubjectallin");
        for (let index = 0; index < ttt_class2.length; index++) {
            const element = ttt_class2[index];
            if (element.checked == true) {
                var name = mysubjectallin[index].innerText;
                var id = element.id;
                var values = element.value;
                str+="<div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='"+id+values+"'>"+name+"</label><input class='ttt_class3' type='checkbox' name = '"+id+"' value = '"+values+"' id='"+id+values+"'></div>";
            }
        }
        str+="</div>";
        cObj("morning_less").innerHTML = str;

        //switch windows
        cObj("create_tt_inside5").classList.remove("hide");
        cObj("create_tt_inside4").classList.add("hide");
    }else{
        alert("Number of lessons cannot be more than "+cObj("max_lessons_in").innerText);
    }

}
cObj("prev_infor4").onclick = function () {
    cObj("create_tt_inside4").classList.remove("hide");
    cObj("create_tt_inside5").classList.add("hide");
}
cObj("next_infor5").onclick = function () {
    cObj("create_tt_inside6").classList.remove("hide");
    cObj("create_tt_inside5").classList.add("hide");
}
cObj("prev_infor5").onclick = function () {
    cObj("create_tt_inside5").classList.remove("hide");
    cObj("create_tt_inside6").classList.add("hide");
}

cObj("next_infor6").onclick = function () {
    cObj("create_tt_inside7").classList.remove("hide");
    cObj("create_tt_inside6").classList.add("hide");
    //send all the data recieved from the system
    var ttt_class = document.getElementsByClassName("ttt_class");
    var classes = "";
    for (let index = 0; index < ttt_class.length; index++) {
        const element = ttt_class[index];
        if (element.checked == true) {
            classes+=element.value+":";
        }
    }
    classes = classes.substr(0,classes.length-1);
    //get all subjects that were chosen
    var ttt_class2 = document.getElementsByClassName("ttt_class2");
    var subject_in = "";
    for (let index = 0; index < ttt_class2.length; index++) {
        const element = ttt_class2[index];
        if (element.checked == true) {
            subject_in+=element.value+",";
        }
    }
    subject_in = subject_in.substr(0,subject_in.length-1);
    //send the morning hour, number of lessons a day and days of the week
    var morninghours = "";
    var ttt_class3 = document.getElementsByClassName("ttt_class3");
    for (let index = 0; index < ttt_class3.length; index++) {
        const element = ttt_class3[index];
        if (element.checked == true) {
            morninghours+=element.value+",";
        }
    }
    morninghours = morninghours.substr(0,morninghours.length-1);

    //number of lessons a day
    var numberoflessons = cObj("number_of_lessons").value;

    //days of the weeks
    var daysof_the_week = "";
    var weekdays = document.getElementsByClassName("ttt_class4");
    for (let index = 0; index < weekdays.length; index++) {
        const element = weekdays[index];
        if (element.checked == true) {
            daysof_the_week+=element.value+",";
        }
    }
    daysof_the_week = daysof_the_week.substr(0,daysof_the_week.length-1);
    var datapass = "?generate_tt=true&class_selected="+classes+"&subjects_in="+subject_in+"&morning_hours="+morninghours+"&number_of_lessons="+numberoflessons+"&daysoftheweek="+daysof_the_week;
    // alert(datapass);
    sendData1("GET","academic/academic.php",datapass,cObj("class_datas_16"));

}
cObj("prev_infor6").onclick = function () {
    cObj("create_tt_inside7").classList.add("hide");
    cObj("create_tt_inside6").classList.remove("hide");
}
cObj("create_tt_complete").onclick = function () { //send all the data recieved from the system
    var ttt_class = document.getElementsByClassName("ttt_class");
    var classes = "";
    for (let index = 0; index < ttt_class.length; index++) {
        const element = ttt_class[index];
        if (element.checked == true) {
            classes+=element.value+":";
        }
    }
    classes = classes.substr(0,classes.length-1);
    //get all subjects that were chosen
    var ttt_class2 = document.getElementsByClassName("ttt_class2");
    var subject_in = "";
    for (let index = 0; index < ttt_class2.length; index++) {
        const element = ttt_class2[index];
        if (element.checked == true) {
            subject_in+=element.value+",";
        }
    }
    subject_in = subject_in.substr(0,subject_in.length-1);
    //send the morning hour, number of lessons a day and days of the week
    var morninghours = "";
    var ttt_class3 = document.getElementsByClassName("ttt_class3");
    for (let index = 0; index < ttt_class3.length; index++) {
        const element = ttt_class3[index];
        if (element.checked == true) {
            morninghours+=element.value+",";
        }
    }
    morninghours = morninghours.substr(0,morninghours.length-1);

    //number of lessons a day
    var numberoflessons = cObj("number_of_lessons").value;

    //days of the weeks
    var daysof_the_week = "";
    var weekdays = document.getElementsByClassName("ttt_class4");
    for (let index = 0; index < weekdays.length; index++) {
        const element = weekdays[index];
        if (element.checked == true) {
            daysof_the_week+=element.value+",";
        }
    }
    daysof_the_week = daysof_the_week.substr(0,daysof_the_week.length-1);
    var err = checkBlank("tt_named");
    if (err > 0) {
        alert("Insert the timetable name first before generating!");
    }else{
        var datapass = "?generate_tt_insidein=true&class_selected="+classes+"&subjects_in="+subject_in+"&morning_hours="+morninghours+"&number_of_lessons="+numberoflessons+"&daysoftheweek="+daysof_the_week+"&ttnames="+cObj("tt_named").value;
        // alert(datapass);
        sendData1("GET","academic/academic.php",datapass,cObj("class_in_87"));
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
                        if (cObj("class_in_87").innerText == "Check after a few seconds your timetable will be ready!") {
                            cObj("class_in_87").innerText = "";
                            cObj("create_tt_inside7").classList.add("hide");
                            cObj("create_tt_inside").classList.remove("hide");
                            cObj("view_tt_in").click();
                        }
                    }, 2000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }

}
cObj("promptnott").onclick = function () {
    cObj("prompt_timetable").classList.add("hide");
}
cObj("promptyestt").onclick = function () {
    var hind = document.getElementsByClassName("hind");
    for (let index = 0; index < hind.length; index++) {
        const element = hind[index];
        element.classList.add("hide");
    }
    cObj("create_tt_in").click();
    cObj("prompt_timetable").classList.add("hide");
}

cObj("view_tt_in").addEventListener("click",populateTT);
//view all the timetables present
function populateTT() {
    //change windows
    cObj("view_tt_inxide").classList.remove("hide");
    cObj("create_timetabled").classList.add("hide");
    //get the list of the timetables that are present in the database
    var datapass = "?getMyTimetable=true";
    sendData1("GET","academic/academic.php",datapass,cObj("timetable_lists"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //give the view button a listener
                var view_timetables = document.getElementsByClassName("view_timetables");
                var delete_tt_files = document.getElementsByClassName("delete_tt_files");
                var regenerate_tt = document.getElementsByClassName("regenerate_tt");
                for (let index = 0; index < view_timetables.length; index++) {
                    const element = view_timetables[index];
                    element.addEventListener("click",viewMyTimetable);
                    //inside
                    var deleted = delete_tt_files[index];
                    deleted.addEventListener("click",deletedTT);
                    // regenerate_tt
                    var regenerate_tts = regenerate_tt[index];
                    regenerate_tts.addEventListener("click",regenerate_timetable);

                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function regenerate_timetable() {
    // get the tt id
    cObj("regenerate_timetables").classList.remove("hide");
    var this_id = this.id.substr(13);
    cObj("regen_id").value = this_id;
    cObj("regen_tts").innerText = cObj("tt_names"+this_id).innerText;
}

cObj("confirm_regen").onclick = function () {
    var datapass = "?regenerate_tt="+valObj("regen_id");
    sendData2("GET","academic/academic.php",datapass,cObj("name_tags"),cObj("load_regen_tt"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("view_tt_in").click();
                cObj("cancel_regen").click();
                setTimeout(() => {
                    cObj("name_tags").innerText = "";
                }, 10000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("cancel_regen").onclick = function () {
    cObj("regenerate_timetables").classList.add("hide");
}

function deletedTT() {
    var id = this.id;
    cObj("ttimetableid").innerText = id;
    var userid = cObj("authoriti").value;
    if (userid == 1|| userid == 0 || userid == 5) {
        cObj("prompt_del_timetable").classList.remove("hide");
    }else{
        alert("You are not allowed to delete the timetable!")
    }
}
cObj("promptyesttt").onclick = function () {
    var id = cObj("ttimetableid").innerText;
    var datapass = "?deletedtt=true&timetable_id="+id;
    sendData1("GET","academic/academic.php",datapass,cObj("name_tags"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("view_tt_in").click();
                setTimeout(() => {
                    cObj("name_tags").innerText = "";
                }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    cObj("prompt_del_timetable").classList.add('hide');
}
function viewMyTimetable() {
    cObj("timetable_ids_holders").value = this.id;
    var datapass = "?display_tt=true&tt_ids="+this.id;
    sendData1("GET","academic/academic.php",datapass,cObj("view_my_tt_ids"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                stopInterval(ids);
                cObj("table_lists").classList.add("hide");
                cObj("mytimetable").classList.remove("hide");
                // decode the javascript code
                if (cObj("error_msg_timetabled") == null) {
                    var jsondata = JSON.parse(cObj("view_my_tt_ids").innerText);
                    var readdata = jsondata.metadata[0].subjects.length;
                    var sub_ids = [];
                    var subjectspresent = [];


                    var data_to_display = "";
                    //get the subjects present
                    for (let index = 0; index < jsondata.metadata[0].subjects.length; index++) {
                        const element = jsondata.metadata[0].subjects[index];
                        if (!checkPresent(sub_ids,element.subject_id)) {
                            subjectspresent.push(element.subjectname);
                            sub_ids.push(element.subject_id);
                        }
                    }
                    cObj("timetable_title_name").innerText = cObj("tt_names"+this.id).innerText;
                    data_to_display+="<div class='row'><div class='conts col-md-4'><h6 style='text-align:left;'><u><b>Subjects Present</b></u></h6>";
                    //read the subjects present
                    for (let index = 0; index < sub_ids.length; index++) {
                        const element = sub_ids[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+subjectspresent[index]+" - {"+element+"}</p></div>";
                    }
                    data_to_display+="</div>";
                    //GET THE classes PRESENT
                    data_to_display+="<div class='conts col-md-4'><h6 style='text-align:left;' class='my-1'><u><b>Classes Present</b></u></h6>";
                    for (let index = 0; index < jsondata.metadata[1].classes.length; index++) {
                        const element = jsondata.metadata[1].classes[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+element.classname+" - {"+element.classid+"}</p></div>";
                    }
                    data_to_display+="</div>";
                    //get the teachers present
                    var teachers_data_sel = "<select name='specific_tr_tt' id='specific_tr_tt' class='form-control'><option value='' hidden>Select teacher</option>";
                    data_to_display+="<div class='conts col-md-4'><h6 style='text-align:left;' class='my-1'><u><b>Teachers Present</b></u></h6>";
                    for (let index = 0; index < jsondata.metadata[2].teachers.length; index++) {
                        const element = jsondata.metadata[2].teachers[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+element.teachername+" - {"+element.teacherid+"}</p></div>";
                        teachers_data_sel+="<option value='"+element.teacherid+"'>"+element.teachername+"</option>";
                    }
                    teachers_data_sel+="</select>";
                    data_to_display+="</div></div><hr><span class='block_btn' id='edit_timetables'><i class='fas fa-pen-fancy'></i> Customize Timetable</span>";
                    //block timetable
                    var timetables = jsondata.timetables;
                    var block = timetables[0].blocktimetable;
                    //get the block timetable
                    data_to_display+="<div style='margin-top:20px;' class='conts'><h3 style='text-align:center;'>Timetables </h3> <h6>1. <u>Block Timetable:</u></h6>";
                    
                    for (let index = 0; index < block.length; index++) {
                        const element = block[index];
                        data_to_display+="<div class='conts'><p><strong>Day</strong>: "+element.Day+"</p>";
                        data_to_display+="<div class='table_holders'>";
                        //table here
                        //get the number of periods
                        var lesson = element.classes[0].lessons.length;
                        var periods = "";
                        for (let ind = 0; ind < lesson; ind++) {                        
                            periods+="<th>Pr "+(ind+1)+"</th>";
                        }
                        data_to_display+="<table class='table' ><tr><th>Classname</th>"+periods+"</tr>";
                        for (let ind = 0; ind < element.classes.length; ind++) {
                            const elements = element.classes[ind];
                            data_to_display+="<tr><td>"+elements.classname+"</td>";

                            for (let indexes = 0; indexes < elements.lessons.length; indexes++) {
                                const elemented = elements.lessons[indexes];
                                var data_change = elemented == null ? "" : elemented;
                                if (data_change != null && data_change != undefined) {
                                    data_change = data_change.split("=").length > 1? data_change.split("=")[0].trim()+" -> <small class = \"text-success\">"+data_change.split("=")[1].trim()+"</small>":data_change;
                                    data_change = data_change.replace(/{/g,"<small class='text-sm text-primary'>(");
                                    data_change = data_change.replace(/}/g,")</small>");
                                }
                                data_to_display+="<td>"+data_change+"</td>";
                            }
                            data_to_display+="</tr>";
                        }
                        data_to_display+="</table></div></div>";
                    }
                    data_to_display+="</div>";

                    //get the class timetable
                    data_to_display+="<div style='margin-top:20px;' class='conts'><h6 style='text-align:center;'>2. <u>Classes Timetable:</u></h6>";

                    var lesson_length = 0;
                    var classtimetabled = timetables[1].classtimetable;
                    for (let index = 0; index < classtimetabled.length; index++) {
                        const element = classtimetabled[index];
                        data_to_display+="<div class='conts'><p><strong>Class</strong>:"+element.classname+"</p><div class='table_holders'>";
                        var lessoncount = element.daysoftheweek[0].lessons.length;
                        var prstring = "";
                        for (let ind = 0; ind < lessoncount; ind++) {
                            prstring+="<th>Pr "+(ind+1)+"</th>";
                        }
                        data_to_display+="<table class='table'><tr><th>Day</th>"+prstring+"</tr>";
                        var days = element.daysoftheweek;
                        for (let indexes = 0; indexes < days.length; indexes++) {
                            const elements = days[indexes];
                            data_to_display+="<tr><td>"+elements.Day+"</td>";
                            lesson_length = elements.lessons.length;
                                for (let indexed = 0; indexed < elements.lessons.length; indexed++) {
                                    const elemented = elements.lessons[indexed];
                                    var data_change = elemented == null ? "" : elemented;
                                    if (data_change != null && data_change != undefined) {
                                        data_change = data_change.split("=").length > 1? data_change.split("=")[0].trim()+" -> <small class = \"text-success\">"+data_change.split("=")[1].trim()+"</small>":data_change;
                                        data_change = data_change.replace(/{/g,"<small class='text-sm text-primary'>(");
                                        data_change = data_change.replace(/}/g,")</small>");
                                    }
                                    data_to_display+="<td>"+data_change+"</td>";
                                }
                            data_to_display+="</tr>";
                        }
                        data_to_display+="</table></div></div>";
                    }
                    data_to_display+="</div>";
                    cObj("lesson_length_holder").innerText = lesson_length;
                    var data_tt = "<select name='break_1' id='break_1' class='form-control'><option value='' hidden>Select Lesson</option>";
                    for (let i = 0; i < lesson_length; i++) {
                        data_tt+="<option value='"+(i+1)+"'>Lesson "+(i+1)+"</option>";
                    }
                    data_tt+="</select>";
                    cObj("break_1_period_select").innerHTML = data_tt;
                    cObj("read_timetable").innerHTML = data_to_display;
                    cObj("specific_tr_tt_lists_select").innerHTML = teachers_data_sel;
                    cObj("edit_timetables").addEventListener("click",customize_tt);
                }else{
                    data_to_display = "<div class='conts' style='display:flex;flex-direction:column;align-items:center'><img src='images/error.png' alt='' srcset=''><p style='color:red;text-align:center;margin-top:0px;'>Sorry your timetable cannot be viewed at the moment! "+cObj("error_msg_timetabled").innerText+"</p> </div>";
                    cObj("read_timetable").innerHTML = data_to_display;
                }
            }
        }, 100);
    }, 200);
}
cObj("what_tt").onchange = function () {
    var values = this.value;
    if (values != "specific_tr_timetable") {
        cObj("teacher_lists_select").classList.add("hide");
    }else{
        cObj("teacher_lists_select").classList.remove("hide");
    }
}
function viewMyTimetable2(timetable_id) {
    var datapass = "?display_tt=true&tt_ids="+timetable_id;
    sendData1("GET","academic/academic.php",datapass,cObj("view_my_tt_ids"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                stopInterval(ids);
                cObj("table_lists").classList.add("hide");
                cObj("mytimetable").classList.remove("hide");
                // decode the javascript code
                if (cObj("error_msg_timetabled") == null) {
                    var jsondata = JSON.parse(cObj("view_my_tt_ids").innerText);
                    var readdata = jsondata.metadata[0].subjects.length;
                    var sub_ids = [];
                    var subjectspresent = [];


                    var data_to_display = "";
                    //get the subjects present
                    for (let index = 0; index < jsondata.metadata[0].subjects.length; index++) {
                        const element = jsondata.metadata[0].subjects[index];
                        if (!checkPresent(sub_ids,element.subject_id)) {
                            subjectspresent.push(element.subjectname);
                            sub_ids.push(element.subject_id);
                        }
                    }
                    // cObj("timetable_title_name").innerText = cObj("tt_names"+this.id).innerText;
                    data_to_display+="<div class='row'><div class='conts col-md-4'><h6 style='text-align:left;'><u><b>Subjects Present</b></u></h6>";
                    //read the subjects present
                    for (let index = 0; index < sub_ids.length; index++) {
                        const element = sub_ids[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+subjectspresent[index]+" - {"+element+"}</p></div>";
                    }
                    data_to_display+="</div>";
                    //GET THE classes PRESENT
                    data_to_display+="<div class='conts col-md-4'><h6 style='text-align:left;' class='my-1'><u><b>Classes Present</b></u></h6>";
                    for (let index = 0; index < jsondata.metadata[1].classes.length; index++) {
                        const element = jsondata.metadata[1].classes[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+element.classname+" - {"+element.classid+"}</p></div>";
                    }
                    data_to_display+="</div>";
                    //get the teachers present
                    data_to_display+="<div class='conts col-md-4'><h6 style='text-align:left;' class='my-1'><u><b>Teachers Present</b></u></h6>";
                    for (let index = 0; index < jsondata.metadata[2].teachers.length; index++) {
                        const element = jsondata.metadata[2].teachers[index];
                        data_to_display+="<div class='conts'><p>"+(index+1)+". "+element.teachername+" - {"+element.teacherid+"}</p></div>";
                    }
                    data_to_display+="</div></div><hr><span class='block_btn' id='edit_timetables'><i class='fas fa-pen-fancy'></i> Customize Timetable</span>";
                    //block timetable
                    var timetables = jsondata.timetables;
                    var block = timetables[0].blocktimetable;
                    //get the block timetable
                    data_to_display+="<div style='margin-top:20px;' class='conts'><h3 style='text-align:center;'>Timetables </h3> <h6>1. <u>Block Timetable:</u></h6>";
                    
                    for (let index = 0; index < block.length; index++) {
                        const element = block[index];
                        data_to_display+="<div class='conts'><p><strong>Day</strong>: "+element.Day+"</p>";
                        data_to_display+="<div class='table_holders'>";
                        //table here
                        //get the number of periods
                        var lesson = element.classes[0].lessons.length;
                        var periods = "";
                        for (let ind = 0; ind < lesson; ind++) {                        
                            periods+="<th>Pr "+(ind+1)+"</th>";
                        }
                        data_to_display+="<table class='table'><tr><th>Classname</th>"+periods+"</tr>";
                        for (let ind = 0; ind < element.classes.length; ind++) {
                            const elements = element.classes[ind];
                            data_to_display+="<tr><td>"+elements.classname+"</td>";

                            for (let indexes = 0; indexes < elements.lessons.length; indexes++) {
                                // const elemented = elements.lessons[indexes];
                                // var data_change = elemented;
                                // data_change = data_change.replace("{","<small class='text-sm'>(");
                                // data_change = data_change.replace("}",")</small>");
                                // data_to_display+="<td>"+data_change+"</td>";
                                const elemented = elements.lessons[indexes];
                                var data_change = elemented;
                                if (data_change != null && data_change != undefined) {
                                    data_change = data_change.split("=").length > 1? data_change.split("=")[0].trim()+" -> <small class = \"text-success\">"+data_change.split("=")[1].trim()+"</small>":data_change;
                                    data_change = data_change.replace(/{/g,"<small class='text-sm text-primary'>(");
                                    data_change = data_change.replace(/}/g,")</small>");
                                }
                                data_to_display+="<td>"+data_change+"</td>";
                            }
                            data_to_display+="</tr>";
                        }
                        data_to_display+="</table></div></div>";
                    }
                    data_to_display+="</div>";

                    //get the class timetable
                    data_to_display+="<div style='margin-top:20px;' class='conts'><h6 style='text-align:center;'>2. <u>Classes Timetable:</u></h6>";

                    var classtimetabled = timetables[1].classtimetable;
                    for (let index = 0; index < classtimetabled.length; index++) {
                        const element = classtimetabled[index];
                        data_to_display+="<div class='conts'><p><strong>Class</strong>:"+element.classname+"</p><div class='table_holders'>";
                        var lessoncount = element.daysoftheweek[0].lessons.length;
                        var prstring = "";
                        for (let ind = 0; ind < lessoncount; ind++) {
                            prstring+="<th>Pr "+(ind+1)+"</th>";
                        }
                        data_to_display+="<table class='table'><tr><th>Day</th>"+prstring+"</tr>";
                        var days = element.daysoftheweek;
                        for (let indexes = 0; indexes < days.length; indexes++) {
                            const elements = days[indexes];
                            data_to_display+="<tr><td>"+elements.Day+"</td>";
                                for (let indexed = 0; indexed < elements.lessons.length; indexed++) {
                                    // const elemented = elements.lessons[indexed];
                                    // var data_change = elemented;
                                    // data_change = data_change.replace("{","<small class='text-sm'>(");
                                    // data_change = data_change.replace("}",")</small>");
                                    // data_to_display+="<td>"+data_change+"</td>";
                                    const elemented = elements.lessons[indexed];
                                    var data_change = elemented;
                                    if (data_change != null && data_change != undefined) {
                                        data_change = data_change.split("=").length > 1? data_change.split("=")[0].trim()+" -> <small class = \"text-success\">"+data_change.split("=")[1].trim()+"</small>":data_change;
                                        data_change = data_change.replace(/{/g,"<small class='text-sm text-primary'>(");
                                        data_change = data_change.replace(/}/g,")</small>");
                                    }
                                    data_to_display+="<td>"+data_change+"</td>";
                                }
                            data_to_display+="</tr>";
                        }
                        data_to_display+="</table></div></div>";
                    }
                    data_to_display+="</div>";
                    cObj("read_timetable").innerHTML = data_to_display;
                    cObj("edit_timetables").addEventListener("click",customize_tt);
                }else{
                    data_to_display = "<div class='conts' style='display:flex;flex-direction:column;align-items:center'><img src='images/error.png' alt='' srcset=''><p style='color:red;text-align:center;margin-top:0px;'>Sorry your timetable cannot be viewed at the moment! "+cObj("error_msg_timetabled").innerText+"</p> </div>";
                    cObj("read_timetable").innerHTML = data_to_display;
                }
            }
        }, 100);
    }, 200);
}

cObj("add_breaks").onclick = function () {
    var err = checkBlank("brake_name");
    err+=checkBlank("break_1_period_in_minutes");
    err+=checkBlank("break_1");
    if (valObj("break_1_period_in_minutes") > 60) {
        err++;
    }
    if (err == 0) {
        cObj("table_breaktime").innerHTML = "";
        var breaks_lists = cObj("breaks_lists").value;
        if (breaks_lists.length > 0) {
            breaks_lists = JSON.parse(breaks_lists);
            var present = 0;
            for (let index = 0; index < breaks_lists.breaks.length; index++) {
                const element = breaks_lists.breaks[index];
                if (element.after == valObj("break_1")) {
                    present = 1;
                }
            }
            if (present == 0) {
                var elem2 = {brake_name:valObj("brake_name"),period:valObj("break_1_period_in_minutes"),after:valObj("break_1")};
                breaks_lists.breaks.push(elem2);
                cObj("breaks_lists").value = JSON.stringify(breaks_lists);
                set_break_table();
            }else{
                cObj("table_breaktime").innerHTML = "<p class='text-danger'>The position you place the current brake is already occupied by another!</p>";
            }
        }else{
            // its the first breaks set
            var elem = {breaks:[]};
            var elem2 = {brake_name:valObj("brake_name"),period:valObj("break_1_period_in_minutes"),after:valObj("break_1")};
            elem.breaks.push(elem2);
            cObj("breaks_lists").value = JSON.stringify(elem);
            set_break_table();
        }
    }else{
        var break_err = "";
        if (valObj("break_1_period_in_minutes") > 60) {
            err++;
            break_err = "Minutes cannot be greater than 60 minute";
        }
        cObj("table_breaktime").innerHTML = "<p class='text-danger'>Please fill all fields with red border!<br>"+break_err+"</p>";
    }
}

function set_break_table() {
    var break_table = cObj("breaks_lists").value;
    if (break_table.length > 0) {
        break_table = JSON.parse(break_table);
        var breaks = break_table.breaks;
        var count = 0;
        var datapass = "<table class='table'><tr><th>#</th><th>Break Name</th><th>Break Duration</th><th>Break Position</th><th>Action</th></tr>";
        for (let index = 0; index < breaks.length; index++) {
            const element = breaks[index];
            datapass+="<tr><td>"+(index+1)+"</td><td>"+element.brake_name+"</td><td>"+element.period+" minutes</td><td>After lesson "+element.after+"</td><td><p id='delete_brake"+(index+1)+"' class='link delete_brake'><i class='fas fa-trash-alt'></i></p></td></tr>";
            count++;
        }
        datapass+="</table>";
        if (count > 0) {
            cObj("table_breaktime").innerHTML = datapass;
        }else{
            cObj("table_breaktime").innerHTML = "<p class='text-success'>When you add breaks they will appear here in table form.</p>";
        }
        var delete_brake = document.getElementsByClassName("delete_brake");
        for (let index = 0; index < delete_brake.length; index++) {
            const element = delete_brake[index];
            element.addEventListener("click", delete_brakes);
        }
    }else{
        cObj("table_breaktime").innerHTML = "<p class='text-danger'>Please fill all fields with red border!</p>";
    }
    
}

function delete_brakes() {
    var values = this.id.substr(12);
    var break_table = cObj("breaks_lists").value;
    if (break_table.length > 0) {
        break_table = JSON.parse(break_table);
        var json_data = {breaks:[]};
        for (let index = 0; index < break_table.breaks.length; index++) {
            const element = break_table.breaks[index];
            if (index != (values*1 - 1)) {
                json_data.breaks.push(element);
            }
        }
        console.log(json_data);
        cObj("breaks_lists").value = JSON.stringify(json_data);
        set_break_table();
    }
}

cObj("advanced_options").onclick = function () {
    if(cObj("advanced_window").classList.contains("hide")){
        cObj("advanced_window").classList.remove("hide");
    }else{
        cObj("advanced_window").classList.add("hide");
    }
}

cObj("skip_combinations").onclick = function () {
    cObj("step_1_tt").classList.add("hide");
    cObj("step_2_tt").classList.remove("hide");
}
cObj("back_step2_tt").onclick = function () {
    cObj("step_1_tt").classList.remove("hide");
    cObj("step_2_tt").classList.add("hide");
    cObj("rooms_set").innerHTML = "";
    cObj("set_tables").innerHTML = "";
    displayTable();
}

function customize_tt() {
    cObj("customize_tt").classList.remove("hide");
    cObj("mytimetable").classList.add("hide");
    cObj("error_handler_customize_tt").innerText = "";
    cObj("customize_my_tables_tt").innerText = cObj("timetable_title_name").innerText;
    var datastring = "?get_custom_table=true";
    sendData1("GET","academic/academic.php",datastring,cObj("custom_window_tt"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                stopInterval(ids);
                // get the select classes so that when it changes options for the classes also changes
                var subject_select = document.getElementsByClassName("subject_select");
                for (let index = 0; index < subject_select.length; index++) {
                    const element = subject_select[index];
                    element.addEventListener("change",setPosibleSubjects);
                }
                var list_my_classes = cObj("classes_present").innerText;
                var classlist = JSON.parse(list_my_classes);
                classlist.sort();
                var data_to_display = "<select class='form-control' id='my_class_list'><option value='' hidden >Select Option</option>";
                for (let index = 0; index < classlist.length; index++) {
                    const element = classlist[index];
                    data_to_display+="<option value='"+element+"' >"+element+"</option>";
                }
                data_to_display+="</select>";
                cObj("class_lists_holder").innerHTML = data_to_display;
                cObj("my_class_list").addEventListener("change",valueChange);
            }
        }, 100);
    }, 200);
    cObj("rooms_set").innerHTML = "";
    cObj("room_names").value = "";
    cObj("shorts_names").value = "";
}

cObj("print_tt").onclick = function () {
    if (cObj("print_tt_windows").classList.contains("hide")) {
        cObj("print_tt_windows").classList.remove("hide");
    }else{
        cObj("print_tt_windows").classList.add("hide");
    }
}

cObj("set_rooms").onclick = function () {
    var err = 0;
    err+=checkBlank("room_names");
    err+=checkBlank("shorts_names");
    if (err == 0) {
        cObj("err_handles_rooms").innerHTML = "";
        var rooms_set = cObj("rooms_set").innerText;
        if (rooms_set.length > 0) {
            rooms_set = JSON.parse(rooms_set);
            var present = 0;
            for (let index = 0; index < rooms_set.rooms.length; index++) {
                const element = rooms_set.rooms[index];
                if (element.room_short == valObj("shorts_names")) {
                    present = 1;
                    break;
                }
            }
            // set present
            if (present == 0) {
                cObj("err_handles_rooms").innerHTML = "";
                var rooms = rooms_set.rooms;
                var room = {room_name:""+valObj("room_names")+"",room_short:""+valObj("shorts_names")+""};
                rooms_set.rooms.push(room);
                cObj("rooms_set").innerText = JSON.stringify(rooms_set);
                cObj("room_names").value = ""
                cObj("shorts_names").value = ""
                cObj("err_handles_rooms").innerHTML = "<p class='text-success'>Room Set Successfully!</p>";
                setTimeout(() => {
                    cObj("err_handles_rooms").innerHTML = "";
                }, 2000);
            }else{
                cObj("err_handles_rooms").innerHTML = "<p class='text-danger'>Use another room short name!</p>";
            }
        }else{
            var rooms = {rooms:[]};
            var room = {room_name:""+valObj("room_names")+"",room_short:""+valObj("shorts_names")+""};
            rooms.rooms.push(room);
            var roomed = JSON.stringify(rooms);
            cObj("rooms_set").innerText = roomed;
            cObj("room_names").value = ""
            cObj("shorts_names").value = ""
            cObj("err_handles_rooms").innerHTML = "<p class='text-success'>Room Set Successfully!</p>";
            setTimeout(() => {
                cObj("err_handles_rooms").innerHTML = "";
            }, 2000);
        }
    }else{
        cObj("err_handles_rooms").innerHTML = "<p class='text-danger'>Please fill all fields covered with red border!</p>";
    }
    rooms_table()
}

function rooms_table() {
    var set_tables = cObj("rooms_set").innerText;
    if (set_tables.length > 0) {
        var rooms_set = JSON.parse(set_tables);
        var data_to_display = "<h6>Rooms Table</h6><table class='table'><tr><th>#</th><th>Room Name</th><th>Room Short</th><th>Actions</th></tr>";
        for (let index = 0; index < rooms_set.rooms.length; index++) {
            const element = rooms_set.rooms[index];
            data_to_display+="<tr><td>"+(index+1)+"</td><td>"+element.room_name+"</td><td>"+element.room_short+"</td><td><span id='delete_room"+index+"' class='delete_room link'><i class='fas fa-trash-alt'></i></span></td></tr>";
        }
        data_to_display+="</table>";
        if (rooms_set.rooms.length > 0) {
            cObj("set_tables").innerHTML = data_to_display;
            var delete_room = document.getElementsByClassName("delete_room");
            for (let index = 0; index < delete_room.length; index++) {
                const element = delete_room[index];
                element.addEventListener("click",delete_rooms);
            }
        }else{
            cObj("set_tables").innerHTML = "<p class='text-success'>No rooms are set yet!</p>";
        }
    }else{
        cObj("set_tables").innerHTML = "";
    }
    displayTable();
}

function delete_rooms() {
    var id =  this.id.substr(11,this.id.length);
    var set_tables = cObj("rooms_set").innerText;
    if (set_tables.length > 0) {
        var rooms_set  = JSON.parse(set_tables);
        rooms_set = rooms_set.rooms;
        var rooms = {rooms:[]};
        var room_jina = "";
        for (let index = 0; index < rooms_set.length; index++) {
            const element = rooms_set[index];
            if (index != id) {
                var one_rooms = {room_name:""+element.room_name+"",room_short:""+element.room_short+""};
                rooms.rooms.push(one_rooms);
            }else{
                room_jina = element.room_short;
            }
        }
        cObj("rooms_set").innerText = JSON.stringify(rooms);
        console.log(room_jina);
        delete_class(room_jina);
    }
    rooms_table();
}

function valueChange() {
    var data_json = cObj("timetable_blocks").innerText;
    if (data_json.length > 0) {
        var counter = 0;
        var my_json = JSON.parse(data_json);
        for (let index = 0; index < my_json.length; index++) {
            const element = my_json[index];
            var classes =element.classes;
            for (let index2 = 0; index2 < classes.length; index2++) {
                const element2 = classes[index2];
                var lessons = element2.lessons;
                for (let index3 = 0; index3 < lessons.length; index3++) {
                    const element3 = lessons[index3];
                    // split lesson is its above one it has a class
                    if (element3.split("=").length > 1) {
                        counter++;
                    }
                }
            }
        }
        if (counter == 0) {
            // console.log("We are secure "+counter);
            var class_selected = cObj("my_class_list").value;
            var class_list = [];
            var timetable_blocks = cObj("timetable_blocks").innerText;
            if (timetable_blocks.length > 0) {
                var json_tt = JSON.parse(timetable_blocks);
                for (let index = 0; index < json_tt.length; index++) {
                    var classes = json_tt[index].classes;
                    for (let index2 = 0; index2 < classes.length; index2++) {
                        var classname = classes[index2].classname;
                        if (classname == class_selected) {
                            var lessons = classes[index2].lessons;
                            for (let index3 = 0; index3 < lessons.length; index3++) {
                                if (!checkPresent(class_list,lessons[index3].trim().split(" ")[0])) {
                                    if (lessons[index3].trim().split(" ")[0].length > 0) {
                                        // console.log(lessons[index3].trim().split("|"));
                                        if(lessons[index3].trim().split("|").length <= 1){
                                            class_list.push(lessons[index3].trim().split(" ")[0]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // check the subjects that have been selected already
            var combination_json = cObj("combination_json").innerText;
            var class_combos = [];
            var class_list2 = class_list;
            if (combination_json.length > 0) {
                var combinations = JSON.parse(combination_json);
                var combos = combinations.combinations;
                for (let index = 0; index < combos.length; index++) {
                    const element = combos[index];
                    var curr_class = element.class;
                    if (curr_class == class_selected) {
                        class_list2 = [];
                        var class_combo = element.combo;
                        for (let index2 = 0; index2 < class_combo.length; index2++) {
                            const element2 = class_combo[index2];
                            for (let index3 = 0; index3 < class_list.length; index3++) {
                                const element3 = class_list[index3].trim();
                                var is_present = checkPresent(class_list2,element3);
                                if (!is_present) {
                                    if (element2.includes(element3)) {
                                        // class_list = remove_array(class_list,element3);
                                        class_list2.push(element3);
                                    }
                                }
                            }
                            // class_list = class_list2;
                        }
                        break;
                    }
                }
            }
            // class_list = class_list2;
            if (class_list2 != class_list) {
                var new_class_list = [];
                for (let index = 0; index < class_list.length; index++) {
                    const element = class_list[index];
                    var is_present = checkPresent(class_list2,element);
                    if (!is_present) {
                        new_class_list.push(element);
                    }
                }
                class_list = new_class_list;
            }
            var datapass = "<div class='row'>";
            // display the subjects as check boxes
            for (let index = 0; index < class_list.length; index++) {
                const element = class_list[index];
                datapass+="<div class='col-md-5'><span>"+(index+1)+". </span><label for='subjects_lists"+(index+1)+"' class='form-control-label'>"+element+"</label></div><div class='col-md-5'><input type='checkbox' class='subjects_lists ml-2' id='subjects_lists"+(index+1)+"' value='"+element+"'></div>";
            }
            datapass += "</div>"
            if (class_list.length > 0) {
                cObj("subjects_listing").innerHTML = datapass;
            }else{
                cObj("subjects_listing").innerHTML = "<p class='text-danger'>No subjects list present all have been combined</p>";
            }
        }else{
            cObj("subjects_listing").innerHTML = "<p class='text-danger'>We have noticed that there are rooms set.<br>Skip this step & delete all rooms then get back and set the combination.</p>";
        }
    }
}

cObj("set_combination").onclick = function set_combination() {
    var subjects_lists = document.getElementsByClassName("subjects_lists");
    var selected_subjects = "";
    var select_count = 0;
    for (let index = 0; index < subjects_lists.length; index++) {
        const element = subjects_lists[index];
        if (element.checked == true) {
            selected_subjects+=element.value+"|";
            select_count++;
        }
    }
    if (select_count > 1) {
        selected_subjects = selected_subjects.length > 0 ? selected_subjects.substr(0,(selected_subjects.length-1)):"";
        // set the json data so that it can be displayed as a table
        var data_json = cObj("combination_json").innerText;
        if (data_json.length > 0) {
            // decode it to json format
            // get the class that we ate adding the combination lists
            var data_decode = JSON.parse(data_json);
            var my_class_list = cObj("my_class_list").value;
            // check present
            var present = 0;
            for (let index = 0; index < data_decode.combinations.length; index++) {
                const element = data_decode.combinations[index];
                if (element.class == my_class_list) {
                    element.combo.push(selected_subjects);
                    present = 1;
                }
            }
            // if the subject is not present 
            if (present == 0) {
                var data_class = {class:""+my_class_list+"",combo:[selected_subjects]};
                data_decode.combinations.push(data_class);
            }
            cObj("combination_json").innerText = JSON.stringify(data_decode);
            cObj("display_notice").innerHTML = "<p class='text-success'>Subject data added successfully!</p>";
            setTimeout(() => {
                cObj("display_notice").innerHTML = "";
            }, 2000);
            valueChange();
        }else{
            var combo_data = {combinations:[]};
            // create classes
            var my_class_list = cObj("my_class_list").value;
            var data_class = {class:""+my_class_list+"",combo:[selected_subjects]};
            combo_data.combinations.push(data_class);
            cObj("combination_json").innerText = JSON.stringify(combo_data);
            cObj("display_notice").innerHTML = "<p class='text-success'>Subject data added successfully!</p>";
            setTimeout(() => {
                cObj("display_notice").innerHTML = "";
            }, 2000);
            valueChange();
        }
    }else{
        alert("Please select atleast two subjects before proceeding")
    }
    setCombinationTable();
    process_combinations();
}

function setCombinationTable() {
    var json_data = cObj("combination_json").innerText;
    if (json_data.length > 0) {
        json_data = JSON.parse(json_data);
        var data_to_display = "<h6>Combination Table</h6><table class='table'><tr><th>Class Name</th><th>Combination</th></tr>";
        for (let index = 0; index < json_data.combinations.length; index++) {
            const element = json_data.combinations[index];
            data_to_display+="<tr><td>"+element.class+"</td><td>";
            var combos = element.combo;
            for (let index2 = 0; index2 < combos.length; index2++) {
                const element2 = combos[index2];
                data_to_display+=""+element2+" - <span id='"+element.class.replace(/ /g, "_")+"|"+index2+"' class='remove_subs_combo link ml-3'><i class='fas fa-trash-alt'></i></span><hr class='my-0'>";
            }
            data_to_display = data_to_display.substring(0,data_to_display.length-17)+"</td></tr>";
        }
        data_to_display+="</table>";
        if (json_data.combinations.length > 0) {
            cObj("table_combinations").innerHTML = data_to_display;
        }else{
            cObj("table_combinations").innerHTML = "<p class='border border-success p-1 my-1 text-success'>Please select two or more subjects in a specific class to make a combination</p>";
        }
        
        var remove_subs_combo = document.getElementsByClassName("remove_subs_combo");
        for (let index = 0; index < remove_subs_combo.length; index++) {
            const element = remove_subs_combo[index];
            element.addEventListener("click",clickRemove_Combo);
        }
    }else{
        cObj("table_combinations").innerHTML = "<p class='border border-success p-1 my-1 text-success'>Please select two or more subjects in a specific class to make a combination</p>";
    }
}

function clickRemove_Combo() {
    var my_own_data = this.id.replace(/_/g, " ");
    // get the classes the
    var own_classes = my_own_data.split("|");
    var json_data = cObj("combination_json").innerText;
    if (json_data.length > 0) {
        json_data = JSON.parse(json_data);
        var combinations = json_data.combinations;
        var remover = -1;
        // remove
        for (let index = 0; index < combinations.length; index++) {
            const element = combinations[index];
            var combos = element.combo;
            if(own_classes[0] == element.class){
                combos = remove_array2(combos,own_classes[1]);
                element.combo = combos;
            }
        }

        for (let index = 0; index < combinations.length; index++) {
            const element = combinations[index];
            // console.log("Element len "+element.combo.length);
            if (element.combo.length == 0) {
                remover = index;
            }
        }
        // console.log(remover);
        json_data.combinations = remove_array2(combinations,remover);
        cObj("combination_json").innerText = JSON.stringify(json_data);
    }
    setCombinationTable();
    process_combinations();
    valueChange();
}

function remove_array2(array,index1) {
    var array_store = [];
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        if (index != index1) {
            array_store.push(element);
        }
    }
    return array_store;
}
function remove_array(array,string) {
    var new_array = [];
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        if (element != string) {
            new_array.push(element);
        }
    }
    return new_array;
}

function delete_class(params = "") {
    // if class is blank delete all classes
    if (params.length > 0) {
        var timetable_blocks = cObj("timetable_blocks").innerText;
        if (timetable_blocks.length > 0) {
            timetable_blocks = JSON.parse(timetable_blocks);
            // change the value with the position you have been given
            // loop through the data and get the day we are changing
            for (let index = 0; index < timetable_blocks.length; index++) {
                var element = timetable_blocks[index];
                var classes = element.classes;
                for (let index2 = 0; index2 < classes.length; index2++) {
                    const element2 = classes[index2];
                    var lessons = element2.lessons;
                    for (let index3 = 0; index3 < lessons.length; index3++) {
                        const element3 = lessons[index3];
                        // split the lesson to check if there are any rooms
                        var split_lesson = element3.split("=");
                        if (split_lesson.length > 1) {
                            if (split_lesson[1].trim() == params.trim()) {
                                lessons[index3] = split_lesson[0].trim();
                            }
                        }
                    }
                }
            }
            cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
        }
    }
}

function delete_all() {
    // delete all classes
    var timetable_blocks = cObj("timetable_blocks").innerText;
    if (timetable_blocks.length > 0) {
        timetable_blocks = JSON.parse(timetable_blocks);
        // change the value with the position you have been given
        // loop through the data and get the day we are changing
        for (let index = 0; index < timetable_blocks.length; index++) {
            var element = timetable_blocks[index];
            var classes = element.classes;
            for (let index2 = 0; index2 < classes.length; index2++) {
                const element2 = classes[index2];
                var lessons = element2.lessons;
                for (let index3 = 0; index3 < lessons.length; index3++) {
                    const element3 = lessons[index3];
                    // split the lesson to check if there are any rooms
                    var split_lesson = element3.split("=");
                    if (split_lesson.length > 1) {
                        lessons[index3] = split_lesson[0].trim();
                    }
                }
            }
        }
        cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
    }
}

function displayTable(){
    // change the block timetable on the top of the table id: timetable_blocks
    var timetable_blocks = cObj("timetable_blocks").innerText;
    // console.log(timetable_blocks);
    if (timetable_blocks.length > 0 && hasJsonStructure(timetable_blocks)) {
        timetable_blocks = timetable_blocks.replace(/null/g,"\"\"");
        timetable_blocks = JSON.parse(timetable_blocks);
        
        // get class list for my class
        var class_list = [];
        for (let index=0; index < timetable_blocks.length; index++) { 
            classes = timetable_blocks[index].classes;
            for (let index2=0; index2 < classes.length; index2++) { 
                var present = checkPresent(class_list,classes[index2].classname.trim());
                if (!present) {
                    class_list.push(classes[index2].classname.trim());
                }
            }
        }
        // end of getting classes
        
        // get all posible lessons for the different classes
        var posible_lessons = [];
        for (let index=0; index < class_list.length; index++) { 
            posible_lessons[class_list[index]] = [];
        }
        // end of setting possible lesson
        
        
        // get the possible lessons for the arrays present
        var lesson_count = 0;
        for (let index=0; index < timetable_blocks.length; index++) { 
            // get classes and lessons
            var classes = timetable_blocks[index].classes;
            for (let index2=0; index2 < classes.length; index2++) {
                // set the 
                // loop through lessons in a class
                var class_names = classes[index2].classname.trim();
                var lessons = classes[index2].lessons;
                lesson_count = lessons.length;
                // loop through lessons for a particular class and find if they have ever occured
                for (let index3=0; index3 < lessons.length; index3++) {
                    if (lessons[index3] != null && lessons[index3] != undefined) {
                        var single_lesson = lessons[index3].trim().split("=")[0].trim();
                        var room = lessons[index3].trim().split("=") > 1 ? lessons[index3].trim().split("=")[1].trim():"";
                        let present = checkPresent(posible_lessons[class_names],single_lesson);
                        if (!present) {
                            posible_lessons[class_names].push(single_lesson);
                        }
                    }
                }
            }
        }
        // end of possible classes
        // console.log(posible_lessons);
        // proceed and reset the whole timetable
        var data_to_display = "";
        // display the days of the week
        // console.log(timetable_blocks.length);
        for (let index = 0; index < timetable_blocks.length; index++) {
            // go through the days of the week
            data_to_display+="<p><b>Day: </b> "+timetable_blocks[index].Day+"</p><div class='table_holders'><table class='table'><tr><th>Class Name</th>";
            for (let i=0; i < lesson_count; i++) { 
                data_to_display+="<th>Pr"+(i+1)+"</th>";
            }
            data_to_display+="</tr>";
            var classes = timetable_blocks[index].classes;

            for (let index2=0; index2 < classes.length; index2++) { 
                // create used rooms
                var used_rooms = [];
                for (let c = 0; c < class_list.length; c++) {
                    const el = class_list[c];
                    used_rooms[el] = [];
                }
                for (let d = 0; d < classes.length; d++) {
                    const elc = classes[d];
                    var class_names = elc.classname;
                    for (let e = 0; e < elc.lessons.length; e++) {
                        const eld = elc.lessons[e];
                        var new_lesson = eld != null ? (eld.trim().split("=").length > 1 ? eld.trim().split("=")[1].trim():"") : "";
                        used_rooms[class_names].push(new_lesson);
                    }
                    
                }
                // console.log(used_rooms);
                
                data_to_display+="<tr><td>"+classes[index2].classname+"</td>";
                var lessons = classes[index2].lessons;
                // get all lessons of that time in text
                // all lessons for a day 
                // know all lessons that will cause conflict
                var all_lessons = timetable_blocks[index].classes;
                var my_list = [];
                var my_list2 = []; // list of unwanted teachers
                var my_list3 = "";
                for (let index3=0; index3 < lessons.length; index3++) {
                    var current_lesson = lessons[index3].trim();
                    // set_rooms = true
                    var sel_room = "";
                    var my_rooms = [];
                    for (let ef = 0; ef < class_list.length; ef++) {
                        const elf = class_list[ef];
                        my_rooms.push(used_rooms[elf][index3]);
                    }
                    // console.log(my_rooms);
                    // break;
                    var selected_room = current_lesson.split("=").length > 1 ? current_lesson.split("=")[1].trim():"";
                    var select_rooms = "";
                    // console.log(selected_room);
                    var rooms_set = cObj("rooms_set").innerText;
                    if (rooms_set.length > 0) {
                        rooms_set = JSON.parse(rooms_set);
                        rooms = rooms_set.rooms;
                        var selected = "";
                        var counter = 0;
                         select_rooms = "<select  style='color:blue;' class='my_room_list my-1 custom_tt'><option value='' hidden >Select Room</option>";
                        for (let index6 = 0; index6 < rooms.length; index6++) {
                            const element = rooms[index6];
                            if (!checkPresent(my_rooms,element.room_short) || element.room_short == selected_room) {
                                selected = "";
                                if (element.room_short == selected_room && selected_room.length > 0) {
                                    selected = "selected";
                                    sel_room = element.room_short;
                                }
                                counter++;
                                select_rooms+="<option "+selected+" value='"+index3+"|"+timetable_blocks[index].Day+"|"+classes[index2].classname+"|"+element.room_short+"'>"+element.room_name+"</option>";
                            }
                        }
                        select_rooms+="<option value='"+index3+"|"+timetable_blocks[index].Day+"|"+classes[index2].classname+"|'></option></select>";
                        select_rooms = counter > 0 ? select_rooms:"";
                    }

                    // end of rooms
                    // set rooms used
                    var lessons_only1 = current_lesson.split("=")[0].trim();
                    for (let index4=0; index4 < all_lessons.length; index4++) { 
                        var class_lessons = all_lessons[index4].lessons;
                        var detailed_lesson = class_lessons[index3] != null ? class_lessons[index3].trim().split("=") : [];

                        if (detailed_lesson.length > 0) {
                            // if the current lesson is equal to the lesson iterated do not add it to the unwanted list
                            if (lessons_only1 != detailed_lesson[0].trim()) {
                                my_list.push(class_lessons[index3].trim());
                                var one_lesson = detailed_lesson[0].trim().split("|");
                                var text = "";
                                for (let index7 = 0; index7 < one_lesson.length; index7++) {
                                    text = one_lesson[index7].length>0 ?one_lesson[index7].trim().split("{")[1].trim() : "";
                                    text = text.length > 0 ? text.substr(0,text.length-1):"";
                                    my_list2.push(text);
                                    my_list3+=text+",";
                                }
                            }
                        }
                    }
                    my_list3 = my_list3.substring(0,(my_list3.length-1));
                    var possible_lesson = posible_lessons[classes[index2].classname];
                    var select = "<select class='my_class_list custom_tt subject_select'><option value='' hidden >Select Option</option>";
                    let blanks = 0;
                    for (let index5=0; index5 < possible_lesson.length; index5++) { 
                        var single_lesson = possible_lesson[index5].trim();
                        var is_present = checkPresntContain(my_list2,single_lesson);
                        if (!is_present) {
                            var selected = "";
                            if (lessons_only1 == single_lesson) {
                                selected = "selected";
                            }
                            var subject_data = sel_room.length > 0 ? single_lesson+" = "+sel_room:single_lesson;
                            if (single_lesson.length > 0) {
                                select+="<option "+selected+" value='"+index3+"|"+timetable_blocks[index].Day+"|"+classes[index2].classname+"|"+subject_data+"'>"+single_lesson+"</option>";
                            }else{
                                select+="<option "+selected+" value='"+index3+"|"+timetable_blocks[index].Day+"|"+classes[index2].classname+"|"+subject_data+"'>"+single_lesson+"</option>";
                                blanks++;
                            }
                            
                        }
                    }
                    if (blanks == 0) {
                        select+="<option value='"+index3+"|"+timetable_blocks[index].Day+"|"+classes[index2].classname+"|'> </option></select>";
                    }else{
                        select+="</select>";
                    }
                    data_to_display+="<td>"+select+" "+select_rooms+"</td>";
                    my_list = [];
                    my_list2 = [];
                    my_list3 = "";
                }
                data_to_display+="</tr>";
            }
            // add lessons here
            data_to_display+="</table></div><hr>";
        }
        cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
        cObj("reset_tt").innerHTML = data_to_display;
        var subject_select = document.getElementsByClassName("subject_select");
        for (let index = 0; index < subject_select.length; index++) {
            const element = subject_select[index];
            element.addEventListener("change",setPosibleSubjects);
        }
        var my_room_list = document.getElementsByClassName("my_room_list");
        for (let index = 0; index < my_room_list.length; index++) {
            const element = my_room_list[index];
            element.addEventListener("change",setPosibleSubjects2);
        }
    }
}
cObj("delete_all_rooms").onclick = function () {
    cObj("set_tables").innerHTML = "";
    delete_all();
    displayTable();
}

function setPosibleSubjects2() {
    var my_room = this.value;
    var values = my_room.split("|");
    // change the block timetable on the top of the table id: timetable_blocks
    var timetable_blocks = cObj("timetable_blocks").innerText;
    if (timetable_blocks.length > 0) {
        timetable_blocks = JSON.parse(timetable_blocks);
        // change the value with the position you have been given
        // loop through the data and get the day we are changing
        for (let index = 0; index < timetable_blocks.length; index++) {
            var element = timetable_blocks[index];
            if(element.Day == values[1].trim()){
                var classes = element.classes;
                // go through the classes and get the class that the information is to be changed
                for (let index2 = 0; index2 < classes.length; index2++) {
                    var element2 = classes[index2];
                    if (element2.classname == values[2]) {
                        var lessons = element2.lessons;
                        // console.log(timetable_blocks[index].classes[index2].lessons[values[0]]);
                        // change the lessons in that window
                        // timetable_blocks[index].classes[index2].lessons[values[0]] = values[3];

                        // lessons[values[0]] = values[2];
                        // console.log(timetable_blocks[index].classes[index2].lessons[values[0]]);

                        var mew_lesson = lessons[values[0]].split("=")[0].trim()+" = "+values[3];
                        lessons[values[0]] = mew_lesson;
                    }
                }
            }
        }
        cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
        displayTable();
    }
}

function setPosibleSubjects() {
    var all_value = this.value.split("=");
    var first_value = all_value[0];
    var values = first_value.split("|");
    // change the block timetable on the top of the table id: timetable_blocks
    var timetable_blocks = cObj("timetable_blocks").innerText;
    if (timetable_blocks.length > 0) {
        timetable_blocks = JSON.parse(timetable_blocks);
        // change the value with the position you have been given
        // loop through the data and get the day we are changing
        for (let index = 0; index < timetable_blocks.length; index++) {
            var element = timetable_blocks[index];
            if(element.Day == values[1].trim()){
                var classes = element.classes;
                // go through the classes and get the class that the information is to be changed
                for (let index2 = 0; index2 < classes.length; index2++) {
                    var element2 = classes[index2];
                    if (element2.classname == values[2]) {
                        var lessons = element2.lessons;
                        // console.log(timetable_blocks[index].classes[index2].lessons[values[0]]);
                        // change the lessons in that window
                        // timetable_blocks[index].classes[index2].lessons[values[0]] = values[3];
                        var subject_value = "";
                        for (let index3 = 3; index3 < values.length; index3++) {
                            const element3 = values[index3];
                            subject_value +=element3+"|";
                        }
                        subject_value = subject_value.substr(0,subject_value.length-1);
                        var second_value = (all_value.length > 1) ? all_value[1].trim().length > 0 ? all_value[1]:"":"";
                        lessons[values[0]] = second_value.trim().length > 0? subject_value+" = "+second_value:subject_value;
                        // lessons[values[0]] = values[2];
                        // console.log(timetable_blocks[index].classes[index2].lessons[values[0]]);
                    }
                }
            }
        }
        cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
        displayTable();
    }
}

function process_combinations() {
    // combination first then the rest follows
    // cObj("timetable_blocks").innerText = cObj("default_json_tt").innerText;
    var timetable_blocks = cObj("default_json_tt").innerText;
    if (timetable_blocks.length > 0) {
        timetable_blocks = JSON.parse(timetable_blocks);
        // get class list for my class
        var class_list = [];
        for (let index=0; index < timetable_blocks.length; index++) { 
            classes = timetable_blocks[index].classes;
            for (let index2=0; index2 < classes.length; index2++) { 
                var present = checkPresent(class_list,classes[index2].classname.trim());
                if (!present) {
                    class_list.push(classes[index2].classname.trim());
                }
            }
        }
        class_list.sort();
        // get all posible lessons for the different classes
        var posible_lessons = [];
        for (let index=0; index < class_list.length; index++) { 
            posible_lessons[class_list[index]] = [];
        }

        // get all subjects for every class then replace where possible
        
        // get the possible lessons for the arrays present
        var lesson_count = 0;
        for (let index=0; index < timetable_blocks.length; index++) { 
            // get classes and lessons
            var classes = timetable_blocks[index].classes;
            for (let index2=0; index2 < classes.length; index2++) {
                // set the 
                // loop through lessons in a class
                var class_names = classes[index2].classname.trim();
                var lessons = classes[index2].lessons;
                lesson_count = lessons.length;
                // loop through lessons for a particular class and find if they have ever occured
                for (let index3=0; index3 < lessons.length; index3++) { 
                    var single_lesson = lessons[index3] != null ? lessons[index3].trim() : "";
                    let present = checkPresent(posible_lessons[class_names],single_lesson);
                    if (!present) {
                        posible_lessons[class_names].push(single_lesson);
                    }
                }
            }
        }
        // console.log(posible_lessons);
        // possible lessons will be used to set the subjects for the teacher
        // proceed and reset the whole timetable
        var new_block_tt = {blocktimetable:[]};
        
        for (let index = 0; index < timetable_blocks.length; index++) {
            var day_lessons = {Day:timetable_blocks[index].Day,classes:[]};
            
            var classes = timetable_blocks[index].classes;

            for (let index2=0; index2 < classes.length; index2++) { 
                var each_class = {classname:classes[index2].classname,lessons:[]};
                
                var lessons = classes[index2].lessons;
                // get all lessons of that time in text
                // all lessons for a day 
                // know all lessons that will cause conflict
                var all_lessons = timetable_blocks[index].classes;
                var my_list = [];
                var my_list2 = []; // list of unwanted teachers
                var my_list3 = "";
                for (let index3=0; index3 < lessons.length; index3++) {
                    var lessons_allowed_holder = [];
                    var current_lesson = lessons[index3].trim();
                    for (let index4=0; index4 < all_lessons.length; index4++) {
                        var class_lessons = all_lessons[index4].lessons;
                        // if the current lesson is equal to the lesson iterated do not add it to the unwanted list
                        if (class_lessons[index3] != null) {
                            if (current_lesson != class_lessons[index3].trim()) {
                                my_list.push(class_lessons[index3].trim());
                                var one_lesson = class_lessons[index3].split("|");
                                var text = "";
                                for (let index7 = 0; index7 < one_lesson.length; index7++) {
                                    text = one_lesson[index7].length>0 ?one_lesson[index7].trim().split("{")[1].trim() : "";
                                    text = text.length > 0 ? text.substr(0,text.length-1):"";
                                    my_list2.push(text);
                                    my_list3+=text+",";
                                }
                            }
                        }
                    }
                    my_list3 =my_list3.substring(0,(my_list3.length-1));
                    var possible_lesson = posible_lessons[classes[index2].classname];
                    for (let index5=0; index5 < possible_lesson.length; index5++) { 
                        var single_lesson = possible_lesson[index5].trim();
                        var is_present = checkPresntContain(my_list2,single_lesson);
                        if (!is_present) {
                            lessons_allowed_holder.push(single_lesson);
                        }
                    }
                    my_list = [];
                    my_list2 = [];
                    my_list3 = "";
                    each_class.lessons.push(lessons_allowed_holder);
                }
                day_lessons.classes.push(each_class);
            }
            new_block_tt.blocktimetable.push(day_lessons);
        }
        // console.log(timetable_blocks);
        new_block_tt = new_block_tt.blocktimetable;
        // console.log(new_block_tt);
        // loop through the timetable block and look for places to replace the new subjects combinations
        for (let index = 0; index < timetable_blocks.length; index++) {
            var classes = timetable_blocks[index].classes;
            var classes_block = new_block_tt[index].classes;
            for (let index2 = 0; index2 < classes.length; index2++) {
                const element = classes[index2];
                var lessons = element.lessons;
                var lesson_new_tt = classes_block[index2].lessons;
                var new_lesson = [];
                var classname = element.classname;
                var combination_json = cObj("combination_json").innerText;
                if (combination_json.length > 0) {
                    combination_json = JSON.parse(combination_json);
                    var combinations = combination_json.combinations;
                    for (let index3 = 0; index3 < combinations.length; index3++) {
                        const element2 = combinations[index3];
                        var combo = element2.combo;
                        var my_classes = element2.class;
                        if (my_classes.trim() == classname.trim()) {
                            for (let index4 = 0; index4 < combo.length; index4++) {
                                const element3 = combo[index4].split("|");
                                var positions = [];
                                var subjects = [];
                                var repeat = 0;// the repeat of a subject replace should not be greater then two
                                for (let index5 = 0; index5 < lessons.length; index5++) {
                                    const element4 = lessons[index5].trim();
                                    var posible_subjects = lesson_new_tt[index5];
                                    for (let ind = 0; ind < element3.length; ind++) {
                                        if (element4.includes(element3[ind].trim())) {
                                            positions.push(index5);
                                            subjects.push(posible_subjects);
                                            break;
                                        }
                                    }
                                    // if (element4.includes(element3[0]) || element4.includes(element3[1])) {
                                    //     // if the lesson either includes lesson combo 1 or two mark the position
                                    //     positions.push(index5);
                                    //     subjects.push(posible_subjects);
                                    // }
                                }
                                // get the possible lessons at positions that are there then know what to replace
                                // loop through the positions to get to know what we are changing
                                for (let index6 = 0; index6 < positions.length; index6++) {
                                    var prese = 0;
                                    var subjets_names = "";
                                    for (let ind = 0; ind < element3.length; ind++) {
                                        // check if the subjects combined are allowed at that position they are in
                                        // if all subjects are allowed that lesson is assigned the new combination or the subjects are left blank
                                        if (checkPresentIncludes(subjects[index6],element3[ind])) {
                                            prese++;
                                            var subject_lists = posible_lessons[my_classes];
                                            for (let i = 0; i < subject_lists.length; i++) {
                                                if(subject_lists[i].includes(element3[ind].trim())){
                                                    subjets_names+=subject_lists[i].trim()+"|";
                                                }
                                                
                                            }
                                        }
                                    }
                                    if (prese == element3.length) {
                                        subjets_names = subjets_names.substring(0,(subjets_names.length-1));
                                        lessons[positions[index6]] = subjets_names;
                                        // to know if all are present they should count to the size of the subjects
                                    }else{
                                        lessons[positions[index6]] = "";
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        cObj("timetable_blocks").innerText = JSON.stringify(timetable_blocks);
        // console.log(timetable_blocks);
        displayTable();
    }
}
cObj("return_timetable_lists").onclick = function () {
    cObj("customize_tt").classList.add("hide");
    cObj("mytimetable").classList.remove("hide");
    viewMyTimetable2(cObj("timetable_ids_holders").value);
    cObj("subjects_listing").innerHTML = "";
    cObj("advanced_window").classList.add("hide");
}
cObj("return_timetable_lists2").onclick = function () {
    cObj("return_timetable_lists").click();
}
function checkPresntContain(array, string) {
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        // console.log(string+" include this = "+element+" => "+element.includes(string));
        if (element.length > 0) {
            if (string.includes(element)) {
                return true;
            }
        }
    }
    return false;
}
function checkPresentIncludes(array, string) {
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        // console.log(string+" include this = "+element+" => "+element.includes(string));
        if (element.length > 0) {
            if (element.includes(string)) {
                return true;
            }
        }
    }
    return false;
}
function checkPresent(array, string) {
    for (let index = 0; index < array.length; index++) {
        const element = array[index];
        if (element == string) {
            return true;
        }
    }
    return false;
}
cObj("save_custom_tt").onclick = function () {
    // show confirmation for saving the data
    cObj("timetable_title").innerText = cObj("customize_my_tables_tt").innerText;
    cObj("dialogholder2").classList.remove("hide");
    cObj("subjects_listing").innerHTML = "";
}

cObj("save_changestt_no").onclick = function () {
    cObj("dialogholder2").classList.add("hide");
}

cObj("save_custom_tt2").onclick = function () {
    cObj("save_custom_tt").click();
}

cObj("save_changestt_yees").onclick = function () {
    cObj("dialogholder2").classList.add("hide");
    var datapass = "new_tt_data="+cObj("timetable_blocks").innerText+"";
    sendDataPost("POST","ajax/academic/academic.php",datapass,cObj("error_handler_customize_tt"),cObj("loadings"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                stopInterval(ids);
               
            }
        }, 100);
    }, 200);
}

cObj("return_timetable_list").onclick = function () {
    cObj("table_lists").classList.remove("hide");
    cObj("mytimetable").classList.add("hide");
}
cObj("return_timetable_list2").onclick = function () {
    cObj("return_timetable_list").click();
}
cObj("promptnottt").onclick = function () {
    cObj("prompt_del_timetable").classList.add("hide");
}
cObj("set_grades_btn").onclick = function () {
    var errors = checkBlank("maximum_marks");
    errors+=checkBlank("minimum_marks");
    errors+=checkBlank("grade_score");
    if (errors == 0) {
        if (valObj("minimum_marks")*1 >= 0) {
            cObj("error_handler_graders").innerHTML = "";
            var err = ((valObj("minimum_marks")*1) >=  (valObj("maximum_marks") * 1)) ? 1 : 0;
            // no errors present
            if (err == 1) {
                // there is an error
                cObj("error_handler_graders").innerHTML = "<p class='text-danger'>The minimum value cannot be greater than or equal to the maximum value in this range</p>";
            }else{
                cObj("error_handler_graders").innerHTML = "";
                var data = cObj("my_grades_lists").innerText;
                if (data.length > 0) {
                    var ids = 0;
                    var min = 0;
                    var informa = JSON.parse(data);
                    for (let index = 0; index < informa.length; index++) {
                        const element = informa[index];
                        if (informa.length == index+1) {
                            ids+=1;
                            min = element.min;
                        }
                    }
                    data = data.substr(0,(data.length-1));
                    data += ',{"grade_name": "'+valObj("grade_score")+'","max":"'+valObj("maximum_marks")+'","min":"'+valObj("minimum_marks")+'","grade_id":"'+ids+'"}]';
                    cObj("my_grades_lists").innerText = data;
                    cObj("maximum_marks").value = (valObj("minimum_marks")*1) > 0 ? ((valObj("minimum_marks")*1)-1) : 0;
                    cObj("minimum_marks").value = "";
                    cObj("grade_score").value = "";
                    create_grade_list(data)
                }else{
                    data = '[{"grade_name": "'+valObj("grade_score")+'","max":"'+valObj("maximum_marks")+'","min":"'+valObj("minimum_marks")+'","grade_id":"1"}]';
                    cObj("my_grades_lists").innerText = data;
                    cObj("maximum_marks").value = (valObj("minimum_marks")*1) > 0 ? ((valObj("minimum_marks")*1)-1) : 0;
                    cObj("minimum_marks").value = "";
                    cObj("grade_score").value = "";
                    create_grade_list(data)
                }
            }
        }else{
            cObj("error_handler_graders").innerHTML = "<p class='text-danger'>Minimum value is zero '0'</p>";
        }
    }else{
        cObj("error_handler_graders").innerHTML = "<p class='text-danger'>Please fill all the fields covered with red boarder</p>";
    }
}
cObj("add_grades_in_cancels").onclick = function () {
    cObj("add_grades_win").classList.add("hide");
    cObj("grades_listers").innerHTML = "<p>Kindly add the grades for the respective subject <br> Grades will appear here.</p>";
}
cObj("set_grades_display_btn").onclick = function () {
    // get the subject name
    var errors = checkBlank("subname");
    errors+=checkBlank("sundids");
    errors+=checkBlank("submarks");
    // console.log(errors);
    if (errors == 0) {
        // no errors present
        cObj("errregsub").innerHTML = "";
        cObj("add_grades_win").classList.remove("hide");
        cObj("subject_grades_names").innerText = cObj("subname").value;
        cObj("maximum_marks").value = cObj("submarks").value;
    }else{
        cObj("errregsub").innerHTML = "<p class='text-danger'>Please fill all the fields covered with red boarder</p>";
    }
}
cObj("close_add_grades_window").onclick = function () {
    cObj("add_grades_win").classList.add("hide");
}

function create_grade_list(data,table_id="grades_listers") {
    if (data.trim().length > 0) {
        var decoded = JSON.parse(data);
        var data_to_display = "<h6 class='text-center'>Grade Table</h6><table class='table'><tr><th>Grade</th><th>Min Marks</th><th>Max Marks</th></tr>";
        for (let indexes = 0; indexes < decoded.length; indexes++) {
            const element = decoded[indexes];
            data_to_display+="<tr><td>"+element.grade_name+"</td><td>"+element.max+"</td><td>"+element.min+"</td></tr>";
        }
        data_to_display+="</table>";
        cObj(table_id).innerHTML = data_to_display;
    }
}

cObj("add_grades_in").onclick = function () {
    cObj("set_my_grades_list").innerText = cObj("my_grades_lists").innerText;
    cObj("add_grades_win").classList.add("hide");
    cObj("minimum_marks").value = "";
    cObj("grade_score").value = "";
    cObj("maximum_marks").value = 0;
    // display the grade table below
    create_grade_list(cObj("set_my_grades_list").innerText,"display_tables_list");
}

cObj("edit_grades_btn").onclick = function () {
    var errors = checkBlank("edit_maximum_marks");
    errors+=checkBlank("edit_minimum_marks");
    errors+=checkBlank("edit_grade_score");
    if (errors == 0) {
        if (valObj("edit_minimum_marks")*1 >= 0) {
            cObj("edit_error_handler_graders").innerHTML = "";
            var err = ((valObj("edit_minimum_marks")*1) >=  (valObj("edit_maximum_marks") * 1)) ? 1 : 0;
            // no errors present
            if (err == 1) {
                // there is an error
                cObj("edit_error_handler_graders").innerHTML = "<p class='text-danger'>The minimum value cannot be greater than or equal to the maximum value in this range</p>";
            }else{
                cObj("edit_error_handler_graders").innerHTML = "";
                var data = cObj("my_grades_edits").innerText;
                if (data.length > 0) {
                    var ids = 0;
                    var min = 0;
                    var informa = JSON.parse(data);
                    for (let index = 0; index < informa.length; index++) {
                        const element = informa[index];
                        if (informa.length == index+1) {
                            ids+=1;
                            min = element.min;
                        }
                    }
                    data = data.substr(0,(data.length-1));
                    data += ',{"grade_name": "'+valObj("edit_grade_score")+'","max":"'+valObj("edit_maximum_marks")+'","min":"'+valObj("edit_minimum_marks")+'","grade_id":"'+ids+'"}]';
                    cObj("my_grades_edits").innerText = data;
                    cObj("edit_maximum_marks").value = (valObj("edit_minimum_marks")*1) > 0 ? ((valObj("edit_minimum_marks")*1)-1) : 0;
                    cObj("edit_minimum_marks").value = "";
                    cObj("edit_grade_score").value = "";
                    create_grade_list(data,"edit_grades_lists");
                }else{
                    data = '[{"grade_name": "'+valObj("edit_grade_score")+'","max":"'+valObj("edit_maximum_marks")+'","min":"'+valObj("edit_minimum_marks")+'","grade_id":"1"}]';
                    cObj("my_grades_edits").innerText = data;
                    cObj("edit_maximum_marks").value = (valObj("edit_minimum_marks")*1) > 0 ? ((valObj("edit_minimum_marks")*1)-1) : 0;
                    cObj("edit_minimum_marks").value = "";
                    cObj("edit_grade_score").value = "";
                    create_grade_list(data,"edit_grades_lists");
                }
            }
        }else{
            cObj("edit_error_handler_graders").innerHTML = "<p class='text-danger'>Minimum value is zero '0'</p>";
        }
    }else{
        cObj("edit_error_handler_graders").innerHTML = "<p class='text-danger'>Please fill all the fields covered with red boarder</p>";
    }
}

cObj("edit_add_grades_in").onclick = function () {
    cObj("subjects_grades_hidden").innerText = cObj("my_grades_edits").innerText;
    cObj("edit_grades_win").classList.add("hide");
    cObj("edit_maximum_marks").value = "";
    cObj("edit_grade_score").value = "";
    cObj("edit_minimum_marks").value = 0;
    cObj("my_grades_edits").innerText = "";
    cObj("edit_grades_lists").innerText = "";
    // display the grade table below
    create_grade_list(cObj("subjects_grades_hidden").innerText,"my_grade_lists_subject");
}
cObj("canc_exam_print").onclick = function () {
    cObj("printer_window").classList.add("hide");
}
cObj("close_exams_printing").onclick = function () {
    cObj("printer_window").classList.add("hide");
}

// current slide
let currentSlideexams = 0;

// get all the slides
let all_slides = document.getElementsByClassName("slides");

// function to move to the next slide
function goNextExams() {
    // hide all slides
    for (let index = 0; index < all_slides.length; index++) {
        const element = all_slides[index];
        element.classList.add("hide");
    }
    // calculate the next slide
    currentSlideexams = (currentSlideexams + 1 + all_slides.length) % all_slides.length;
    // display the next slide
    all_slides[currentSlideexams].classList.remove("hide");
}

// function to move to the previous slide
function goPrevExams() {
    // hide all slides
    for (let index = 0; index < all_slides.length; index++) {
        const element = all_slides[index];
        element.classList.add("hide");
    }
    // calculate the next slide
    currentSlideexams = (currentSlideexams - 1 + all_slides.length) % all_slides.length;
    // display the next slide
    all_slides[currentSlideexams].classList.remove("hide");
}

function setPreviewComments() {
    var its_value = this.value;
    var its_id = this.id.substr(11);
    if (its_value.length > 0) {
        var fullnames = cObj("student_names_full_"+its_id).innerText.trim();
        var student_gender = valObj("student_gender_"+its_id);
        its_value = its_value.replace("{fullname}",fullnames.split(" ")[1]+" "+fullnames.split(" ")[2]+" "+fullnames.split(" ")[3]);
        its_value = its_value.replace("{firstname}",fullnames.split(" ")[2]);
        its_value = its_value.replace("{noun1}",(student_gender == "Male" ? "son":"daughter"));
        its_value = its_value.replace("{noun2}",(student_gender == "Male" ? "boy":"girl"));
        cObj("preview_comments_exams_"+its_id).innerText = its_value;
    }else{
        cObj("preview_comments_exams_"+its_id).innerText = "Previews Appear here..";
    }
}

// set the buttons to handle the events
cObj("next_exams_btn").onclick = function () {
    // get the actions for slide 6
    if (currentSlideexams == 6) {
        var err = checkBlank("grades_options");
        err+=checkBlank("garding_options_grade_7");
        if (err == 0) {
            goNextExams();
            cObj("next_exams_btn").classList.add("hide");
        }
    }
    // get action for slide 5
    if (currentSlideexams == 5) {
        goNextExams();
    }
    // get the actions for slide 4
    if (currentSlideexams == 4) {
        // get all students in that particular class
        var check_boxes_exams_name = document.getElementsByClassName("check_boxes_exams_name");
        var selected_exams = [];
        for (let index = 0; index < check_boxes_exams_name.length; index++) {
            const element = check_boxes_exams_name[index];
            if (element.checked == true) {
                selected_exams.push(element.value);
            }
        }
        var datapass = "display_students_in_exams="+valObj("students_class_reports")+"&exams_done="+JSON.stringify(selected_exams);
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("students_commentators"),cObj("err_handler_step_5"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("err_handler_step_5").classList.contains("hide")) {
                    stopInterval(ids);
                    var student_exam_commentator = document.getElementsByClassName("student_exam_commentator");
                    for (let index = 0; index < student_exam_commentator.length; index++) {
                        const element = student_exam_commentator[index];
                        element.addEventListener("keyup",setPreviewComments)
                    }
                   
                }
            }, 100);
        }, 200);
        goNextExams();
    }
    // get the actions for the third slide
    if (currentSlideexams == 3) {
        // ensure that atleast one exam is selected
        var check_boxes_exams_name = document.getElementsByClassName("check_boxes_exams_name");
        var selected_exams = 0;
        for (let index = 0; index < check_boxes_exams_name.length; index++) {
            const element = check_boxes_exams_name[index];
            if (element.checked == true) {
                selected_exams++;
            }
        }

        if (selected_exams > 0) {
            cObj("err_handler_step_3").innerHTML = "";
            goNextExams();
        }else{
            cObj("err_handler_step_3").innerHTML = "<p class='text-danger border border-danger p-2 my-2'>Select atleast one exams before proceeding!</p>";
        }
    }
    // get the actions for the second slide
    if (currentSlideexams == 2) {
        var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
        var checked = 0;
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (element.checked == true) {
                checked++;
            }
        }
        if (checked > 0) {
            cObj("err_handler_step_2").innerHTML = "";
            // proceed to the next slide and populate the exams to be done there
            // get the exams done in the terms
            // parameters are the class selected and the terms selected
            var class_selected = valObj("students_class_reports");
            var terms_selected = [];
            var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
            for (let index = 0; index < check_boxes_ex_report.length; index++) {
                const element = check_boxes_ex_report[index];
                if (element.checked == true) {
                    terms_selected.push(element.value);
                }
            }

            var datapass = "?get_exams_done=true&terms_selected="+JSON.stringify(terms_selected)+"&class_selected="+class_selected;
            sendData2("GET","administration/admissions.php",datapass,cObj("display_exams_attempted_in_those_terms"),cObj("exams_report_exams_done_loader"));
            
            goNextExams();
        }else{
            cObj("err_handler_step_2").innerHTML = "<p class='text-danger p-1 my-2 border border-danger'>Kindly select the terms you want to include in the students report!</p>";
        }
    }

    if (currentSlideexams == 1) {
        cObj("back_exams_btn").classList.remove("hide");
        // check if the class is selected or already displayed
        var err = 0;
        if (cObj("students_class_reports") != undefined && cObj("students_class_reports") != null) {
            err = checkBlank("students_class_reports");
            if (err == 0) {
                cObj("err_handler_step_1").innerHTML = "";
                // get the terms and the term dates
                var datapass = "?get_terms_date=true";
                sendData2("GET","administration/admissions.php",datapass,cObj("display_terms_present"),cObj("exams_report_terms_loader"));
                setTimeout(() => {
                    var timeout = 0;
                    var idss = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout==1200) {
                            stopInterval(idss);                        
                        }
                        if (cObj("exams_report_exams_done_loader").classList.contains("hide")) {
                            //setting listener to the delete button of the subject list
                            var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
                            for (let ind = 0; ind < check_boxes_ex_report.length; ind++) {
                                const element = check_boxes_ex_report[ind];
                                element.addEventListener("change", checkedBoxes);
                            }
                            stopInterval(idss);
                        }
                    }, 100);
                }, 200);
                goNextExams();
            }else{
                cObj("err_handler_step_1").innerHTML = "<p class='text-danger border border-danger p-2'>Fill all fields covered with red border!</p>";
            }
        }else{
            cObj("err_handler_step_1").innerHTML = "<p class='text-danger border border-danger p-2'>You cannot proceed beyond this point if classes are not defined!</p>";
        }
    }
    // get what page we are and link all activities with the action

    if (currentSlideexams == 0) {
        var err = checkBlank("exams_report_type");
        if (err == 0) {
            cObj("back_exams_btn").classList.remove("hide");
            // get the classes
            var datapass = "?get_class_exams_report=true&its_id=students_class_reports";
            sendData2("GET","administration/admissions.php",datapass,cObj("exams_report_classes_display"),cObj("exams_report_class_reports"));
            goNextExams();
        }
    }
}
cObj("back_exams_btn").onclick = function () {
    if (currentSlideexams == 1) {
        cObj("back_exams_btn").classList.add("hide");
    }
    if (currentSlideexams > 0) {
        goPrevExams();
        if (currentSlideexams == 6) {
            cObj("next_exams_btn").classList.remove("hide");
            cObj("generate_report_btns").classList.add("hide");
        }
    }
}

cObj("finish_generating_reports").onclick = function () {
    goNextExams();
    cObj("first_exmas_options").selected = true;
    tinymce.get("email_contents_exam_reports").setContent("");
    cObj("generate_exams_reports_window").classList.add("hide");
    cObj("back_exams_btn").classList.add("hide");
    cObj("next_exams_btn").classList.remove("hide");
    // currentSlideexams = 0;
    cObj("generate_report_btns").classList.add("hide");
}

function editSamplesData(event) {
    var sample_message = editSample_message(event.getContent());
    if (sample_message.length > 0) {
        cObj("email_contents_exam_reports_preview").innerHTML = sample_message;
    }else{
        cObj("email_contents_exam_reports_preview").innerHTML = "Sample Appear here ..";
    }
}

function editSample_message(messages) {
    messages = messages.replace("{firstname}","Esmond");
    messages = messages.replace("{fullname}","Esmond Bwire Adala");
    messages = messages.replace("{noun1}","son");
    messages = messages.replace("{noun2}","boy");
    messages = messages.replace("{noun3}","his");
    messages = messages.replace("{noun4}","he");
    messages = messages.replace("{class}","Grade 8");
    messages = messages.replace("{adm_no}","101");
    return messages;
}

function checkedBoxes() {
    // check if the boxes before are checked or not
    if (this.checked == true) {
        var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
        var checked_index = 0;
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (element.checked == true) {
                if (this.id == element.id) {
                    checked_index = index;
                }
            }
        }
    
        // checked index
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (index <= checked_index) {
                element.checked = true;
            }
        }
    }else{
        var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
        var checked_index = 0;
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (this.id == element.id) {
                checked_index = index;
            }
        }
    
        // checked index
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (index >= checked_index) {
                element.checked = false;
            }
        }
    }
}

// cObj("next_exams_btn").addEventListener("click",goNextExams);
// cObj("back_exams_btn").addEventListener("click",goPrevExams);

cObj("generate_exams_reports").onclick = function () {
    console.log(currentSlideexams);
    cObj("viewexam").classList.add("hide");
    if (currentSlideexams == 0) {
        cObj("generate_exams_reports_window").classList.toggle("hide");
    }else{
        cObj("finish_generating_reports").click();
        cObj("generate_exams_reports_window").classList.toggle("hide");
        // hide all slides
        for (let index = 0; index < all_slides.length; index++) {
            const element = all_slides[index];
            element.classList.add("hide");
        }
        // display the next slide
        all_slides[0].classList.remove("hide");
        cObj("generate_exams_reports_window").classList.toggle("hide");
        currentSlideexams = 0;
    }
}

cObj("execute_exams_report_cards").onclick = function () {
    // get all the data collected and print or email
    // check if its printing or mailing

    var err = checkBlank("select_exams_actions");
    if (err == 0) {
        // proceed and do the neccessary
        cObj("err_handler_step_7").innerHTML = "";
        var class_selected = valObj("students_class_reports");
        
        var terms_selected = [];
        var check_boxes_ex_report = document.getElementsByClassName("check_boxes_ex_report");
        for (let index = 0; index < check_boxes_ex_report.length; index++) {
            const element = check_boxes_ex_report[index];
            if (element.checked == true) {
                terms_selected.push(element.value);
            }
        }

        // exams selected
        // get all students in that particular class
        var check_boxes_exams_name = document.getElementsByClassName("check_boxes_exams_name");
        var selected_exams = [];
        for (let index = 0; index < check_boxes_exams_name.length; index++) {
            const element = check_boxes_exams_name[index];
            if (element.checked == true) {
                selected_exams.push(element.value);
            }
        }

        // get the directors comments
        var directors_comments = document.getElementsByClassName("student_exam_commentator");
        var student_comments = [];
        for (let index = 0; index < directors_comments.length; index++) {
            const element = directors_comments[index];
            var directors_comment = element.value;
            var student_adm_no = element.id.substring(11);
            let comments_data = {student_adm :student_adm_no,directors_commented : directors_comment };
            student_comments.push(comments_data);
        }
        

        student_comments = JSON.stringify(student_comments);
        cObj("class_select").value = class_selected;
        cObj("terms_selected").value = JSON.stringify(terms_selected);
        cObj("exams_selected").value = JSON.stringify(selected_exams);
        cObj("academic_year").value = valObj("academic_year_reports");
        cObj("directors_comments").value = student_comments;
        cObj("next_yr_opening").value = valObj("next_open_date");
        cObj("actions").value = valObj("select_exams_actions");
        cObj("generate_report_btns").classList.remove("hide");
        cObj("grades_options_holder").value = valObj("grades_options");
        cObj("garding_options_grade_8").value = valObj("garding_options_grade_7");
        cObj("include_your_tutors").value = (cObj("include_tutors").checked == true) ? "Yes":"No";
        cObj("include_trend_analysis").value = (cObj("display_trend_analysis").checked == true) ? "Yes":"No";
        cObj("report_term_selected_submit").value = valObj("report_term_selected");

        if (valObj("select_exams_actions") == "print_exams") {
            cObj("generate_report_btns").value = "Print Termly Reports";
        }else if (valObj("select_exams_actions") == "email_parents"){
            cObj("generate_report_btns").value = "Email Termly Reports";
        }

        // var datapass = "generate_students_exams_report=true&class_select="+class_selected+"&terms_selected="+JSON.stringify(terms_selected)+"&exams_selected="+JSON.stringify(selected_exams)+"&academic_year="+valObj("academic_year_reports")+"&directors_comments="+student_comments+"&next_yr_opening="+valObj("next_open_date")+"&actions="+valObj("select_exams_actions");
        // sendDataPost("POST","reports/reports.php",datapass,cObj("err_handler_step_7"),cObj("exam_report_generator"));
    }else{
        cObj("err_handler_step_7").innerHTML = "<p class='text-danger border border-danger p-2 my-2'>Select an option before proceeding!</p>";
    }
}

cObj("select_exams_actions").onchange = function () {
    var selected_option = valObj("select_exams_actions");
    // selected option
    if (selected_option == "email_parents") {
        cObj("email_data_holder").classList.remove("hide");
    }else{
        cObj("email_data_holder").classList.add("hide");
    }
    cObj("generate_report_btns").classList.add("hide");
}