
cObj("register_exams").onclick = function () {
    //get the teacher lists that are not present at the dorm list
    var datapass = "?get_dorm_captain=true";
    sendData2("GET","boarding/boarding.php",datapass,cObj("tr_list"),cObj("tr_lists"));
    cObj("dorm_registrations").classList.remove("hide");
}
cObj("close_dorm_reg_btn").onclick = function () {
    cObj("dorm_registrations").classList.add("hide");
}
cObj("close_dorm_reg").onclick = function () {
    cObj("dorm_registrations").classList.add("hide");
}
cObj("close_dorm_edit_btn").onclick = function () {
    cObj("dorm_edits").classList.add("hide");
}
cObj("close_dorm_edit").onclick = function () {
    cObj("dorm_edits").classList.add("hide");
}
cObj("add_dormitory").onclick = function () {
    var err = 0;
    err = checkBlank("dorm_name");
    err = checkBlank("dorm_capacity");
    if (err == 0) {
        cObj("add_dorm_err_handler").innerHTML = "";
        //send data to the database
        var datapass = "?add_dormitory=true&dorm_name="+cObj("dorm_name").value+"&dorm_capacity="+cObj("dorm_capacity").value+"&dorm_captain="+cObj("dorm_captain").value;
        sendData1("GET","boarding/boarding.php",datapass,cObj("add_dorm_err_handler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //reset form
                    cObj("reg_dorm_form").reset();
                    //refesh
                    cObj("refresh_dorm_list").click();
                    //close window
                    cObj("dorm_registrations").classList.add("hide");
                    cObj("add_dorm_err_handler").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("add_dorm_err_handler").innerHTML = "<p style='color:red;font-size:13px;font-weight:600;'>Fill the fields with a red border!</p>";
    }
}
cObj("refresh_dorm_list").onclick = function () {
    //get the dormitory list
    changeTables();
    var datapass = "?get_dormitory_list=true";
    sendData1("GET","boarding/boarding.php",datapass,cObj("dormitory_list"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                var dorm_edit = document.getElementsByClassName("dorm_edit");
                for (let index = 0; index < dorm_edit.length; index++) {
                    const element = dorm_edit[index];
                    element.addEventListener("click",dormEditListener);
                }
                var linked_occupancy = document.getElementsByClassName("linked_occupancy");
                for (let index = 0; index < linked_occupancy.length; index++) {
                    const element = linked_occupancy[index];
                    element.addEventListener("click",view_Occupancy)
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function dormEditListener() {
    //view the examination information
    //set the values to the exam setting window
    var exam_id = this.id.substr(4);
    cObj("dormitory_id").innerText = exam_id;
    cObj("dorm_name_edit").value = cObj("dn"+exam_id).innerText;
    cObj("cap_name").innerText = cObj("dc"+exam_id).innerText;
    cObj("dorm_capacity_edit").value = cObj("cap"+exam_id).innerText;
    var prefic_id = "dorm_captain_edit";
    //get existing dorm list
    var datapass = "?get_dorm_captain=true&class_name="+prefic_id;
    sendData2("GET","boarding/boarding.php",datapass,cObj("teacher_list"),cObj("teacher_lists"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show the window
                cObj("dorm_edits").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function view_Occupancy() {
    //get the dorm id
    var dorm_id = this.id.substr(8);
    var datapass = "?get_occupancy=true&dormitory_id="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("dorm_occupancy_details"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //hide the window
                cObj("dormitory_list").classList.add("hide");
                cObj("dorm_occupancy_details").classList.remove("hide");
                if(typeof(cObj("back_to_dormlist")) != 'undefined' && cObj("back_to_dormlist") != null){
                    cObj("back_to_dormlist").addEventListener("click",changeTables);
                }
                var change_dormitory = document.getElementsByClassName("change_dormitory");
                for (let index = 0; index < change_dormitory.length; index++) {
                    const element = change_dormitory[index];
                    element.addEventListener("click",changeDormitory);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function changeDormitory() {
    var identity = this.id;
    var dorm_id = identity.split("|")[0];
    var student_id = identity.split("|")[1];
    cObj("my_student_id").innerText = student_id;
    cObj("my_dorm_id").innerText = dorm_id;
    cObj("my_student_name").innerText = cObj("mystud"+student_id).innerText;
    var datapass = "?get_dorm_list=true&current_dorm="+dorm_id+"&student_ids="+student_id;
    sendData2("GET","boarding/boarding.php",datapass,cObj("dorms_lists"),cObj("dorm_list_monitor"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show window
                cObj("change_student_dorm").classList.remove("hide");
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function changeTables() {
    //hide the window
    cObj("dormitory_list").classList.remove("hide");
    cObj("dorm_occupancy_details").classList.add("hide");    
}
cObj("update_dormitory").onclick = function () {
    //check for errors 
    var err = 0;
    err+=checkBlank("dorm_name_edit");
    err+=checkBlank("dorm_capacity_edit");
    if (err == 0) {
        cObj("edit_dorm_err_handler").innerHTML = "";
        var datapass = "?change_dorm_data=true&dorm_name="+cObj("dorm_name_edit").value+"&dorm_capacity="+cObj("dorm_capacity_edit").value+"&dorm_id="+cObj("dormitory_id").innerText;
        if(checkBlank("dorm_captain_edit") == 0){
            datapass = "?change_dorm_data=true&dorm_name="+cObj("dorm_name_edit").value+"&dorm_capacity="+cObj("dorm_capacity_edit").value+"&dorm_captain="+cObj("dorm_captain_edit").value+"&dorm_id="+cObj("dormitory_id").innerText;
        }
        sendData1("GET","boarding/boarding.php",datapass,cObj("edit_dorm_err_handler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //show the window
                    cObj("dorm_edits").classList.add("hide");
                    cObj("edit_dorm_form").reset();
                    cObj("refresh_dorm_list").click();
                    cObj("edit_dorm_err_handler").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("edit_dorm_err_handler").innerHTML = "<p style='color:red;font-size:13px;font-weight:600;'>Fill the fields with a red border!</p>";
    }
}
cObj("un_assign_captain_btn").onclick = function () {
    var datapass = "?un_assign_dorm="+cObj("dormitory_id").innerText;
    sendData1("GET","boarding/boarding.php",datapass,cObj("edit_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show the window
                cObj("dorm_edits").classList.add("hide");
                cObj("edit_dorm_form").reset();
                cObj("refresh_dorm_list").click();
                cObj("edit_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("display_all_present").onclick = function () {
    var datapass = "?get_enrolled_boarders=true";
    sendData1("GET","boarding/boarding.php",datapass,cObj("unenrolled_student_list"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                //show the window
                //set listeners to the buttons
                var elements = document.getElementsByClassName("save_boarder");
                for (let index = 0; index < elements.length; index++) {
                    const element = elements[index];
                    element.addEventListener("click",saveBoarders)
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("display_unenrolled").onclick = function () {
    var err = checkBlank("admission_number");
    if (err == 0) {
        cObj("err_handler_enroll").innerHTML = "";
        var datapass = "?get_enrolled_boarders=true&use_adm="+cObj("admission_number").value;
        sendData1("GET","boarding/boarding.php",datapass,cObj("unenrolled_student_list"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout==1200) {
                    stopInterval(ids);                        
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //show the window
                    //set listeners to the buttons
                    var elements = document.getElementsByClassName("save_boarder");
                    for (let index = 0; index < elements.length; index++) {
                        const element = elements[index];
                        element.addEventListener("click",saveBoarders)
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("err_handler_enroll").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Fill all fields with red border</p>";
    }
}
function saveBoarders() {
    //check for errors on the unselected options
    if(typeof(cObj("select"+this.id.substr(2))) != 'undefined' && cObj("select"+this.id.substr(2)) != null){
        var err = checkBlank("select"+this.id.substr(2));
        if (err == 0) {
            //get the dorm id and the students id and save the information in the boardings table
            var stud_id = this.id.substr(2);
            var dorm_id = cObj("select"+stud_id).value;
            var datapass = "?save_boarder_infor=true&boarder_id="+stud_id+"&house_id="+dorm_id;
            sendData1("GET","boarding/boarding.php",datapass,cObj("outer"+stud_id));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("display_all_present").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    }
}
cObj("close_dorm_change_btn").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
}
cObj("change_student_close").onclick = function () {
    cObj("change_student_dorm").classList.add("hide");
}
cObj("change_dormitory_btn").onclick = function () {
    if(typeof(cObj("dorm_list_change")) != 'undefined' && cObj("dorm_list_change") != null) {
        var err = checkBlank("dorm_list_change");
        if (err == 0) {
            cObj("chage_dorms_err_handlers").innerHTML = "";
            //send data to the database
            var datapass = "?change_student_dorm=true&student_id="+cObj("my_student_id").innerText+"&new_dorm_id="+cObj("dorm_list_change").value+"&current_dorm_id="+cObj("my_dorm_id").innerText;
            sendData1("GET","boarding/boarding.php",datapass,cObj("chage_dorms_err_handlers"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout==1200) {
                        stopInterval(ids);                        
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("chage_dorms_err_handlers").innerHTML = "";
                        cObj("change_student_dorm").classList.add("hide");
                        cObj("back_to_dormlist").click();
                        cObj("refresh_dorm_list").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("chage_dorms_err_handlers").innerHTML = "<p class ='errors' style='color:red;'>No house is selected for the students!</p>";
        }
    }else{
        cObj("chage_dorms_err_handlers").innerHTML = "<p class ='errors' style='color:red;'>No house is selected for the students!</p>";
    }
}
cObj("un_assign_boarder_btn").onclick = function () {
    //get the student admission number and the dormitory number
    var studentid = cObj("my_student_id").innerText;
    var dorm_id = cObj("my_dorm_id").innerText;
    var datapass = "?delete_student_information=true&student_id="+studentid+"&dormitory_id="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("change_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                changeTables();
                cObj("refresh_dorm_list").click();
                cObj("change_student_dorm").classList.add("hide");
                cObj("change_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("un_assign_dorm_btn").onclick = function () {
    //delete the student from the dorm list and update the student data to enroll
    var studentid = cObj("my_student_id").innerText;
    var dorm_id = cObj("my_dorm_id").innerText;
    var datapass = "?un_assign_dormitory=true&student_id="+studentid+"&dormids="+dorm_id;
    sendData1("GET","boarding/boarding.php",datapass,cObj("change_dorm_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout==1200) {
                stopInterval(ids);                        
            }
            if (cObj("loadings").classList.contains("hide")) {
                changeTables();
                cObj("refresh_dorm_list").click();
                cObj("change_student_dorm").classList.add("hide");
                cObj("change_dorm_err_handler").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}