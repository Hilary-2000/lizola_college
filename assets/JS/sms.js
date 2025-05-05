cObj("select_recipients1").onchange = function () {
    if (cObj("sms_information").classList.contains("hide")) {
        cObj("sms_information").classList.remove("hide");
    }
    if (this.value == "phone_no") {
        cObj("enter_phone").classList.remove("hide");
        cObj("select_tr").classList.add("hide");
    } else if (this.value == "my_staff") {
        cObj("enter_phone").classList.add("hide");
        cObj("select_tr").classList.remove("hide");
        //get the staff lists
        if (cObj("select_staff_sms") == null || cObj("select_staff_sms") == "undefined") {
            var datapass = "?getMyStaff=true";
            sendData1("GET", "sms/sms.php", datapass, cObj("my_staff_info"));
        }
    }
}

cObj("email_recipient").onchange = function () {
    var value = this.value;
    if (value == "email") {
        cObj("select_tr_email").classList.add("hide");
        cObj("enter_client_email_addr").classList.remove("hide");
    } else if (value == "my_staff") {
        cObj("select_tr_email").classList.remove("hide");
        cObj("enter_client_email_addr").classList.add("hide");

        // get staff data
        var datapass = "?getStaffMailData=true";
        sendData1("GET", "sms/sms.php", datapass, cObj("my_staff_emails"));
    }
}

cObj("send_email_button").onclick = function () {
    var err = 0;
    var myContent = tinymce.get("email_messages").getContent();
    console.log(myContent);
    err += checkBlank("email_recipient");
    if (err == 0) {
        cObj("email_send_errors").innerHTML = "";
        var option = valObj("email_recipient");
        if (option == "email") {
            err = 0;
            err += checkBlank("staff_email_addressess");
            err += myContent.length > 0 ? 0 : 1;
            err += checkBlank("email_header");
            if (err == 0) {
                cObj("email_send_errors").innerHTML = "";
                var datapass = "send_mail_to=" + valObj("staff_email_addressess") + "&cc=" + valObj("carbon_copy1") + "&bcc=" + valObj("blind_carbon_copy1") + "&message=" + escape(myContent) + "&email_header=" + valObj("email_header");
                sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("email_send_errors"), cObj("load_email_sending"));
                setTimeout(() => {
                    var timeout = 0;
                    var id23w = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(id23w);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            tinymce.get("email_messages").setContent("");
                            cObj("staff_email_addressess").value = "";
                            cObj("carbon_copy1").value = "";
                            cObj("blind_carbon_copy1").value = "";
                            cObj("email_header").value = "";
                            setTimeout(() => {
                                cObj("email_send_errors").innerHTML = "";
                            }, 4000);
                            stopInterval(id23w);

                        }
                    }, 100);
                }, 200);
            } else {
                cObj("email_send_errors").innerHTML = "<p class='text-danger'>Please ensure that all the neccessary fields are filled!</p>";
            }
        } else if (option == "my_staff") {
            err = 0;
            err += checkBlank("select_staff_emails");
            err += myContent.length > 0 ? 0 : 1;
            err += checkBlank("email_header");
            if (err == 0) {
                cObj("email_send_errors").innerHTML = "";
                var datapass = "send_mail_to=" + valObj("select_staff_emails") + "&cc=" + valObj("carbon_copy1") + "&bcc=" + valObj("blind_carbon_copy1") + "&message=" + escape(myContent) + "&email_header=" + valObj("email_header");
                sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("email_send_errors"), cObj("load_email_sending"));
                setTimeout(() => {
                    var timeout = 0;
                    var id23w = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(id23w);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            tinymce.get("email_messages").setContent("");
                            // cObj("select_staff_emails").value = "";
                            cObj("carbon_copy1").value = "";
                            cObj("blind_carbon_copy1").value = "";
                            cObj("email_header").value = "";
                            setTimeout(() => {
                                cObj("email_send_errors").innerHTML = "";
                            }, 4000);
                            stopInterval(id23w);

                        }
                    }, 100);
                }, 200);
            } else {
                cObj("email_send_errors").innerHTML = "<p class='text-danger'>Please ensure that all the neccessary fields are filled!</p>";
            }
        }
    } else {
        cObj("email_send_errors").innerHTML = "<p class='text-danger'>Select an option before proceeding</p>";
    }
}

cObj("send_sms_btns").onclick = function () {
    //check if an option is selected
    var err = checkBlank("select_recipients1");
    if (err == 0) {
        //check whats selected
        var select = cObj("select_recipients1").value;
        if (select == "phone_no") {
            err += checkBlank("staff_phones");
            err += checkBlank("text_message");
            if (err == 0) {
                cObj("out_put22").innerHTML = "";
                //send data to the database
                var datapass = "?send_sms=true&phone_no=" + valObj("staff_phones") + "&message=" + valObj("text_message");
                //var datapass = "?message="+valObj("text_message")+"&mobile="+valObj("staff_phones")+"&shortcode=JuaMobile&partnerID=3468&apikey=9dbd3d8b9ae3d183db6598e815d66f12";
                //var link = "https://quicksms.advantasms.com/api/services/sendsms/";
                //sendData4("POST",link,datapass,cObj("out_put22"));
                sendData1("GET", "sms/sms.php", datapass, cObj("out_put22"));
                setTimeout(() => {
                    var timeout = 0;
                    var id23w = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(id23w);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            //get the message description
                            if (cObj("out_put22").innerText.length > 0) {
                                var sms_data = cObj("out_put22").innerText;
                                stopInterval(id23w);
                                var data = JSON.parse(sms_data);
                                if (Array.isArray(data.responses)) {
                                    cObj("out_put22").innerHTML = "<p class='green_notice'>" + data.responses[0]['response-description'] + "fully sent.</p>";
                                    //record the sms sent
                                    var datapass = "?sms_val=true&message_type=Multicast&message_count=1&recipient_no=" + valObj("staff_phones") + "&text_message=" + valObj("text_message");
                                    sendData1("GET", "sms/sms.php", datapass, cObj("out_put223"));
                                    //clear text
                                    cObj("text_message").value = "";
                                    cObj("staff_phones").value = "";
                                    setTimeout(() => {
                                        cObj("out_put22").innerText = "";
                                    }, 10000);
                                } else {
                                    if (data['response-description'] != undefined) {
                                        cObj("out_put223").innerHTML = "<p class='red_notice'>" + data['response-description'] + "</p>";
                                    } else if (data['message'] != undefined) {
                                        cObj("out_put223").innerHTML = "<p class='red_notice'>" + data['message'] + "</p>";
                                    } else {
                                        cObj("out_put223").innerHTML = "<p class='red_notice'>Inssufficient balance</p>";
                                    }
                                }
                            } else {
                                cObj("out_put223").innerHTML = "<p class='red_notice'>Can`t connect to the sms server!</p>";
                            }
                            stopInterval(id23w);
                        }
                    }, 100);
                }, 200);
            } else {
                cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
            }
        } else if (select == "my_staff") {
            err += checkBlank("select_staff_sms");
            err += checkBlank("text_message");
            if (err == 0) {
                cObj("out_put22").innerHTML = "";
                //send data to the database
                var datapass = "?send_sms=true&phone_no=" + valObj("select_staff_sms") + "&message=" + valObj("text_message");
                //var datapass = "?message="+valObj("select_staff_sms")+"&mobile="+valObj("text_message")+"&shortcode=JuaMobile&partnerID=3468&apikey=9dbd3d8b9ae3d183db6598e815d66f12";
                //var link = "https://quicksms.advantasms.com/api/services/sendsms/";
                //sendData4("POST",link,datapass,cObj("out_put22"));
                sendData1("GET", "sms/sms.php", datapass, cObj("out_put22"));
                setTimeout(() => {
                    var timeout = 0;
                    var id23w = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(id23w);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            //get the message description
                            if (cObj("out_put22").innerText.length > 0) {
                                var sms_data = cObj("out_put22").innerText;
                                stopInterval(id23w);
                                var data = JSON.parse(sms_data);
                                if (Array.isArray(data.responses)) {
                                    cObj("out_put22").innerHTML = "<p class='green_notice'>" + data.responses[0]['response-description'] + "fully sent.</p>";
                                    //record the sms sent
                                    var datapass = "?sms_val=true&message_type=Multicast&message_count=1&recipient_no=" + valObj("select_staff_sms") + "&text_message=" + valObj("text_message");
                                    sendData1("GET", "sms/sms.php", datapass, cObj("out_put223"));
                                    //clear text
                                    cObj("text_message").value = "";
                                    setTimeout(() => {
                                        cObj("out_put22").innerText = "";
                                    }, 10000);
                                } else {
                                    cObj("out_put223").innerHTML = "<p class='red_notice'>" + data['response-description'] + "</p>";
                                }
                            } else {
                                cObj("out_put223").innerHTML = "<p class='red_notice'>Can`t connect to the sms server!</p>";
                            }
                            stopInterval(id23w);
                        }
                    }, 100);
                }, 200);
            } else {
                cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
            }
        }
    } else {
        cObj("out_put22").innerHTML = "<p class='red_notice'>Please fill all field covered with a red border</p>";
    }
}
cObj("text_message").onkeyup = function () {
    cObj("char_count").innerText = this.value.length;
}
cObj("select_recipients2").onchange = function () {
    if (this.value == "my_staff") {
        cObj("students_parents").classList.add("hide");
        cObj("staffs_list_ms").classList.remove("hide");
        //get my staff information
        getStaffLists1();
        cObj("parent_selections").classList.add("hide");
        cObj("message_tags_window").classList.add("d-none");
    } else if (this.value == "parents") {
        cObj("students_parents").classList.remove("hide");
        cObj("staffs_list_ms").classList.add("hide");
        cObj("parent_selections").classList.remove("hide");
        cObj("message_tags_window").classList.remove("d-none");
        getStudentsParent();
    }
}
function getStaffLists1() {
    //get staff lists
    var datapass = "?mystaff_list=true";
    sendData1("GET", "sms/sms.php", datapass, cObj("staff_my_lists"));
}
function getStudentsParent() {
    //get classes lists first
    var datapass = "?parents_lists=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("cl_list_msg"),cObj("loading_my_sms_here"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("loading_my_sms_here").classList.contains("hide")) {
                //add a listener
                if (cObj("my-class") != null) {
                    cObj("my-class").addEventListener("change", getParentsList);
                }

                stopInterval(id23w);
            }
        }, 100);
    }, 200);
    datapass = "?all_parents=true";
    sendData1("GET", "sms/sms.php", datapass, cObj("all_parents"));
}
function getStudentsSms() {
    var keyword = this.value.toLowerCase();
    var students_sms_names = document.getElementsByClassName("students_sms_names");
    for (let index = 0; index < students_sms_names.length; index++) {
        const element = students_sms_names[index].innerText.toLowerCase();
        var our_id = students_sms_names[index].id.substr(3);
        if (element.includes(keyword)) {
            cObj("hide_students" + our_id).classList.remove("d-none");
        } else {
            cObj("hide_students" + our_id).classList.add("d-none");
        }
    }
}
function getParentsList() {
    if (this.value.length > 0) {
        var datapass = "?get_parents_list=" + this.value;
        sendData2("GET", "sms/sms.php", datapass, cObj("parents_lists_nm"),cObj("loading_my_sms_here"));
        setTimeout(() => {
            var timeout = 0;
            var id23w = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(id23w);
                }
                if (cObj("loading_my_sms_here").classList.contains("hide")) {
                    //add a listener
                    checkSelected();
                    if (cObj("staff123s") != "undefined" && cObj("staff123s") != null) {
                        cObj("staff123s").addEventListener("change", selectAll);
                    }
                    var studentslist = document.getElementsByClassName("student-class-par");
                    for (let index = 0; index < studentslist.length; index++) {
                        const element = studentslist[index];
                        element.addEventListener("change", getStudentId);
                    }
                    if (cObj("search_student_sms") != null) {
                        cObj("search_student_sms").addEventListener("keyup", getStudentsSms);
                    }

                    // set listener for active students
                    if(cObj("active_students_check") != undefined){
                        cObj("active_students_check").addEventListener("change",checkActiveStudents);
                    }
                    if (cObj("in_active_students_check") != undefined) {
                        cObj("in_active_students_check").addEventListener("change",checkInActiveStudents);
                    }
                    stopInterval(id23w);
                }
            }, 100);
        }, 200);
    }
}

function checkActiveStudents() {
    // check all active students and add their details in the list
    var  active_banner = document.getElementsByClassName("active_banner");
    for (let index = 0; index < active_banner.length; index++) {
        const element = active_banner[index];
        var elem_id = "adm"+element.id.substring(14);
        cObj(elem_id).checked = this.checked;
        triggerEvent(cObj(elem_id),"change");
    }
}

function checkInActiveStudents() {
    // check all active students and add their details in the list
    var  student_class_par = document.getElementsByClassName("student-class-par");
    for (let index = 0; index < student_class_par.length; index++) {
        const element = student_class_par[index];
        var elem_id = element.id;
        element.checked = !element.classList.contains("activated_banner") ? this.checked : false;
        triggerEvent(cObj(elem_id),"change");
    }
}
function triggerEvent(element, eventName) {
    // Create a new event
    const event = new Event(eventName);
  
    // Dispatch the event on the specified element
    element.dispatchEvent(event);
}

function selectAll() {
    if (this.checked == true) {
        var selects = document.getElementsByClassName("student-class-par");
        for (let index = 0; index < selects.length; index++) {
            var element = selects[index];
            element.checked = true;
            addAdmNo(element.id.substr(3));
        }
    } else {
        var selects = document.getElementsByClassName("student-class-par");
        for (let index = 0; index < selects.length; index++) {
            var element = selects[index];
            element.checked = false;
            removeAdmNo(element.id.substr(3));
        }
    }
    if (cObj("seleceted_class").innerText.length > 0) {
        var counts = cObj("seleceted_class").innerText.split(",").length;
        cObj("excempt_list").innerText = counts;
    } else {
        cObj("excempt_list").innerText = 0;
    }
}
function checkSelected() {
    var selected_class = cObj("seleceted_class").innerText.split(",");
    var selects = document.getElementsByClassName("student-class-par");
    var counts = selects.length;
    var counter1 = 0;
    for (let index = 0; index < selects.length; index++) {
        var element = selects[index];
        var present = checkPresents(element.id.substr(3), selected_class);
        if (present == 1) {
            element.checked = true;
            counter1++;
        }
    }
    if (counts == counter1) {
        // cObj("staff123s").checked = true;
    }
}
function getStudentId() {
    if (this.checked == true) {
        addAdmNo(this.id.substr(3));
    } else {
        removeAdmNo(this.id.substr(3));
    }
    if (cObj("seleceted_class").innerText.length > 0) {
        var counts = cObj("seleceted_class").innerText.split(",").length;
        cObj("excempt_list").innerText = counts;
    } else {
        cObj("excempt_list").innerText = 0;
    }
    //check if all the checkboxes are selected
    var selects = document.getElementsByClassName("student-class-par");
    var units = 0;
    for (let index = 0; index < selects.length; index++) {
        const element = selects[index];
        if (element.checked == true) {
            units++;
        }
    }
    if (units == selects.length) {
        cObj("staff123s").indeterminate = false;
        cObj("staff123s").checked = true;
    } else {
        if (units > 0) {
            cObj("staff123s").checked = false;
            cObj("staff123s").indeterminate = true;
        }else{
            cObj("staff123s").checked = false;
            cObj("staff123s").indeterminate = false;
        }
    }
}
function addAdmNo(adm_no) {
    var selected_class = cObj("seleceted_class").innerText;
    if (selected_class.length > 0) {
        var split = selected_class.split(",");
        if (split.length > 0) {
            var present = checkPresents(adm_no, split);
            if (present == 0) {
                selected_class += "," + adm_no;
                cObj("seleceted_class").innerText = selected_class;
            }
        } else {
            cObj("seleceted_class").innerText = adm_no;
        }
    } else {
        cObj("seleceted_class").innerText = adm_no;
    }
}
function removeAdmNo(adm_no) {
    var seleceted_class = cObj("seleceted_class").innerText;
    if (seleceted_class.length > 0) {
        //split the string and check if the admno is already added
        var splits = seleceted_class.split(",");
        if (splits.length > 0) {
            var data = "";
            for (let index = 0; index < splits.length; index++) {
                var elements = splits[index];
                if (elements == adm_no) {
                    continue;
                } else {
                    data += elements + ",";
                    var excempt_list = cObj("excempt_list").innerText;
                    if (excempt_list != 0) {
                        excempt_list = (excempt_list * 1) - 1;
                        cObj("excempt_list").innerText = excempt_list;
                    }
                }
            }
            cObj("seleceted_class").innerText = data.substr(0, data.length - 1);
        }
    }
}
function checkPresents(value1, array1) {
    if (array1.length > 0) {
        for (let index = 0; index < array1.length; index++) {
            var element = array1[index];
            if (element == value1) {
                return 1
            }
        }
    }
    return 0;
}
cObj("text_message2").onkeyup = function () {
    cObj("chr_counts_in").innerText = this.value.length;
    messageData();
}
cObj("send_msg_btns").onclick = function () {
    // check if its sms or email
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        //check error
        var err = checkBlank("text_message2");
        if (err == 0) {
            cObj("err_hands_error").innerHTML = "";
            //check if its parent or staff
            var selection = valObj("select_recipients2");
            if (selection == "my_staff") {
                //get selected staff
                var data = "";
                //get the selected staff
                var selected_staff = document.getElementsByClassName("snamesd112e");
                var checker = 0;
                for (let index = 0; index < selected_staff.length; index++) {
                    var element = selected_staff[index];
                    if (element.checked == true) {
                        var elem = element.id.substr(1, element.id.length);
                        data += elem + ",";
                        checker++;
                    }
                }
                if (checker > 0) {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'></p>";
                    data = data.substr(0, data.length - 1);
                    var datapass = "?tr_ids_excempt=" + data + "&messages=" + valObj("text_message2");
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"));
                    setTimeout(() => {
                        var timeout = 0;
                        var id23w = setInterval(() => {
                            timeout++;
                            //after two minutes of slow connection the next process wont be executed
                            if (timeout == 1200) {
                                stopInterval(id23w);
                            }
                            if (cObj("loadings").classList.contains("hide")) {
                                setTimeout(() => {
                                    cObj("err_hands_error").innerText = "";
                                    //cObj("parents_lists_nm").classList.add("hide");
                                    //cObj("cl_list_msg").classList.add("hide");
                                    cObj("message_samples").innerHTML = "";
                                    cObj("text_message2").value = "";
                                }, 10000);
                                stopInterval(id23w);
                            }
                        }, 100);
                    }, 200);
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select atleast one staff to send a message!</p>";
                }
            } else if (selection == "parents") {
                var err = checkBlank("send_to_whom");
                if (err == 0) {
                    cObj("err_hands_error").innerHTML = "";
                    var data = cObj("seleceted_class").innerText;
                    var datapass = "?parents_ids_excempt=" + data + "&messages=" + valObj("text_message2") + "&to_whom=" + valObj("send_to_whom");
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"));
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select which parents you will want to send SMS.</p>";
                }
            }
        } else {
            cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Fill all the fields colored with a red border</p>";
        }
    } else if (send_options == "send_emails") {
        //check error
        var err = checkBlank("email_bulk_subject");
        err += tinymce.get("email_editored").getContent().length > 0 ? 0 : 1;
        if (err == 0) {
            cObj("err_hands_error").innerHTML = "";
            //check if its parent or staff
            var selection = valObj("select_recipients2");
            cObj("err_hands_error").innerHTML = "";
            if (selection == "my_staff") {
                //get selected staff
                var data = "";
                //get the selected staff
                var selected_staff = document.getElementsByClassName("snamesd112e");
                var checker = 0;
                for (let index = 0; index < selected_staff.length; index++) {
                    var element = selected_staff[index];
                    if (element.checked == true) {
                        var elem = element.id.substr(1, element.id.length);
                        data += elem + ",";
                        checker++;
                    }
                }
                if (checker > 0) {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'></p>";
                    data = data.substr(0, data.length - 1);
                    var datapass = "?teacher_sms_id_group=" + data + "&messages=" + escape(tinymce.get("email_editored").getContent()) + "&email_subject=" + valObj("email_bulk_subject") + "&email_cc=" + valObj("cc_email_bulk") + "&email_bcc=" + valObj("bcc_email_bulk");
                    // console.log(datapass);
                    sendData1("GET", "sms/sms.php", datapass, cObj("err_hands_error"));
                    setTimeout(() => {
                        var timeout = 0;
                        var id23w = setInterval(() => {
                            timeout++;
                            //after two minutes of slow connection the next process wont be executed
                            if (timeout == 1200) {
                                stopInterval(id23w);
                            }
                            if (cObj("loadings").classList.contains("hide")) {
                                tinymce.get("email_editored").setContent("");
                                setTimeout(() => {
                                    cObj("err_hands_error").innerText = "";
                                    //cObj("parents_lists_nm").classList.add("hide");
                                    //cObj("cl_list_msg").classList.add("hide");
                                    cObj("message_samples").innerHTML = "";
                                }, 10000);
                                stopInterval(id23w);
                            }
                        }, 100);
                    }, 200);
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select atleast one staff so that you can send the email!</p>";
                }
            } else if (selection == "parents") {
                var err = checkBlank("send_to_whom");
                var emeil_message = tinymce.get("email_editored").getContent();
                err += emeil_message.length > 0 ? 0 : 1;
                if (err == 0) {
                    cObj("err_hands_error").innerHTML = "";
                    var data = cObj("seleceted_class").innerText;
                    var datapass = "?parents_ids_excempt_email=" + data + "&messages=" + escape(emeil_message) + "&to_whom=" + valObj("send_to_whom") + "&cc=" + valObj("cc_email_bulk") + "&bcc=" + valObj("bcc_email_bulk") + "&subject=" + valObj("email_bulk_subject");
                    sendData2("GET", "sms/sms.php", datapass, cObj("err_hands_error"), cObj("load_bulk_emails_sending"));
                    cObj("err_hands_error").innerHTML = "<p class='text-success'>Sending bulk E-Mails can take some time. <br>Kindly be patient as the process is done by the system</p>";
                } else {
                    cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select which parents you will want to send SMS.</p>";
                }
            } else {
                cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Select who to send email.</p>";
            }
        } else {
            cObj("err_hands_error").innerHTML = "<p class= 'red_notice'>Fill all the fields that are left blank</p>";
        }
    }
}
cObj("type_notice_here").onkeyup = function () {
    cObj("chr_counts_in1").innerText = this.value.length;
}
function displayTeacherNotice() {
    var datapass = "?get_my_trs=true";
    sendData1("GET", "sms/sms.php", datapass, cObj("staffs_l_s"));
}
cObj("send_post").onclick = function () {
    if (cObj("select_staff_infors") != null) {
        var err = checkBlank("select_staff_infors");
        err += checkBlank("type_notice_here");
        if (err == 0) {
            cObj("notice_errors").innerHTML = "";
            var datapass = "?send_message_notice=true&recpt_id=" + cObj("select_staff_infors").value + "&message=" + cObj("type_notice_here").value;
            sendData1("GET", "sms/sms.php", datapass, cObj("notice_errors"));
            setTimeout(() => {
                var timeout = 0;
                var id23w = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(id23w);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("type_notice_here").value = "";
                        setTimeout(() => {
                            cObj("notice_errors").innerText = "";
                        }, 15000);
                        stopInterval(id23w);
                    }
                }, 100);
            }, 200);
        } else {
            cObj("notice_errors").innerHTML = "<p class='red_notice'>Type your message in the box above!</p>";
        }
    } else {
        cObj("notice_errors").innerHTML = "<p class='red_notice'>No staff present!!</p>";
    }
}
cObj("view_sms_history").onclick = function () {
    //check if the dates are blank
    var err = checkBlank("from_msg_sent");
    err += checkBlank("to_msg_sent");
    if (err == 0) {
        cObj("sms_checker_evt_handlers").innerHTML = "";
        var datapass = "?sms_history=true&from=" + valObj("from_msg_sent") + "&to=" + valObj("to_msg_sent");
        sendData1("GET", "sms/sms.php", datapass, cObj("histotysms"));
        setTimeout(() => {
            var timeout = 0;
            var id23w = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(id23w);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    if (cObj("sms_data_results") != undefined && cObj("sms_data_results") != null) {
                        var sms_data = cObj("sms_data_results").innerText;
                        sms_data = JSON.parse(sms_data);
                        // console.log(sms_data);
                        create_smsdata_table(sms_data);
                    }
                    stopInterval(id23w);
                }
            }, 100);
        }, 200);
    } else {
        cObj("sms_checker_evt_handlers").innerHTML = "<p class='red_notice'>Fill all the dates to proceed!</p>";
    }
}
//function toget the recent messages sent
function getRecentMessage() {
    var datapass = "?sms_history=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("histotysms"), cObj("sms_loaders_window"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("sms_loaders_window").classList.contains("hide")) {
                stopInterval(id23w);
                if (cObj("sms_data_results") != null && cObj("sms_data_results") != undefined) {
                    var sms_data = cObj("sms_data_results").innerText;
                    sms_data = JSON.parse(sms_data);
                    // console.log(sms_data);
                    create_smsdata_table(sms_data);
                }else {
                    cObj("transDataReciever_sms").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
                    cObj("tablefooter_sms").classList.add("invisible");
                }
            }
        }, 100);
    }, 200);
}

function stopInterval(id) {
    clearInterval(id);
}

// from here we set the table for sms
var rowsColStudents_sms = [];
var rowsNCols_original_sms = [];
var pagecountTransaction = 0; //this are the number of pages for transaction
var pagecounttrans = 1; //the current page the user is
var startpage_sms = 0; // this is where we start counting the page number

// load the user data
function create_smsdata_table(student_data) {
    rowsColStudents_sms = [];
    rowsNCols_original_sms = [];
    pagecountTransaction = 0; //this are the number of pages for transaction
    pagecounttrans = 1; //the current page the user is
    startpage_sms = 0; // this is where we start counting the page number
    // console.log(student_data.length);
    // get the arrays
    if (student_data.length > 0) {
        var rows = student_data;
        //create a column now
        for (let index = 0; index < rows.length; index++) {
            const element = rows[index];
            // create the collumn array that will take the row value
            var col = [];
            // console.log(element);
            col.push(element['charged']);
            col.push(element['date_sent']);
            col.push(element['message']);
            col.push(element['message_count']);
            col.push(element['message_description']);
            col.push(element['message_sent_succesfully']);
            col.push(element['message_type']);
            col.push(element['message_undelivered']);
            col.push(element['send_id']);
            col.push(element['sender_no']);
            col.push(index + 1);
            col.push(element['date_sent2']);
            col.push(element['number_collection']);
            col.push(element['recipients']);
            // var col = element.split(":");
            rowsColStudents_sms.push(col);
        }
        rowsNCols_original_sms = rowsColStudents_sms;
        cObj("tot_records_sms").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
        view_and_edit_listeners();

        //show the number of pages for each record
        var counted = rows.length / 50;
        pagecountTransaction = Math.ceil(counted);

    } else {
        cObj("transDataReciever_sms").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_sms").classList.add("invisible");
    }
}

function view_and_edit_listeners() {
    var view_sms_details = document.getElementsByClassName("view_sms_details");
    for (let index = 0; index < view_sms_details.length; index++) {
        const element = view_sms_details[index];
        element.addEventListener("click",viewPhoneNumbers);
    }
}

function viewPhoneNumbers() {
    var this_id = "numbers_value_"+this.id.substr(17);
    var phone_numbers = hasJsonStructure(valObj(this_id)) ? JSON.parse(valObj(this_id)) : [];
    // fill in the message details
    cObj("message_recipients").innerText = phone_numbers.length > 0 ?  displayRecipients(hasJsonStructure(phone_numbers[12]) ? JSON.parse(phone_numbers[12]) : []) : "Not-Set";
    cObj("message_contents_view").innerText = phone_numbers.length > 0 ?  phone_numbers[2] : "Not-Set";
    cObj("date_sent_view").innerText = phone_numbers.length > 0 ?  phone_numbers[11] : "Not-Set";

    // display the window
    cObj("message_details_window").classList.remove("hide");
}

function displayRecipients(arrays) {
    if (arrays.length == 0) {
        return "No Recipient";
    }

    let data_to_display = "";
    for (let index = 0; index < arrays.length; index++) {
        const element = arrays[index];
        data_to_display+=element+", ";
    }
    return data_to_display.substring(0,data_to_display.length-2);
}

cObj("close_message_details").onclick = function () {
    cObj("message_details_window").classList.add("hide");
}

function displayRecord_sms(start, finish, arrays) {
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th title='Sort all descending' id='sortall_sms_th'># <span id='sortall_sms'><i class='fas fa-caret-down'></i></span></th><th id='sort_message_type_th' >Recipients <span id='sort_message_type'><i class='fas fa-caret-down'></i></span></th><th id = 'sort_content_th'>Message Content <span id='sort_content'><i class='fas fa-caret-down'></i></span></th><th  title='Sort by date descending'>Date Sent <span id='sortdate_sms'><i class='fas fa-caret-down'></i></span></th><th>Action</th></tr></thead><tbody>";
    if (finish < total) {
        fins = finish;
        //create a table of the 50 records
        var counter = start + 1;
        for (let index = start; index < finish; index++) {
            var charged = "<span class='text-success' title='Charged'><i class='fas fa-coins'></i></span>";
            if (arrays[index][0] == 0) {
                charged = "<span class='text-secondary' title='Charged'><i class='fas fa-coins'></i></span>";
            }
            tableData += "<tr title =''><td><input hidden value='"+JSON.stringify(arrays[index])+"' id='numbers_value_"+arrays[index][8]+"'>" + (index + 1) + "  " + charged + "</td><td>" + arrays[index][13]+ "</td><td>" + arrays[index][2] + "</td><td>" + arrays[index][11] + "</td><td><span style='font-size:12px;' class='link view_sms_details' id='view_sms_details_"+arrays[index][8]+"'><i class='fa fa-eye'></i> View </span></td></tr>";
            counter++;
        }
    } else {
        //create a table of the 50 records
        var counter = start + 1;
        for (let index = start; index < total; index++) {
            var charged = "<span class='text-success' title='Charged'><i class='fas fa-coins'></i></span>";
            if (arrays[index][0] == 0) {
                charged = "<span class='text-secondary' title='Charged'><i class='fas fa-coins'></i></span>";
            }
            tableData += "<tr title =''><td><input hidden value='"+JSON.stringify(arrays[index])+"' id='numbers_value_"+arrays[index][8]+"'>" + (index + 1) + "  " + charged + "</td><td>" + arrays[index][13]+ "</td><td>" + arrays[index][2] + "</td><td>" + arrays[index][11] + "</td><td><span style='font-size:12px;' class='link view_sms_details' id='view_sms_details_"+arrays[index][8]+"'><i class='fa fa-eye'></i> View </span></td></tr>";
            counter++;
        }
        fins = total;
    }

    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_sms").innerText = start + 1;
    cObj("finishNo_sms").innerText = fins;
    //set the page number
    cObj("pagenumNav_sms").innerText = pagecounttrans;
    // set tool tip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    if (cObj("sort_content_th") != undefined) {
        sortTable_sms();
    } else {
        setTimeout(() => {
            sortTable_sms();
        }, 500);
    }
    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_sms").onclick = function () {
    console.log(pagecounttrans + " " + pagecountTransaction);
    if (pagecounttrans < pagecountTransaction) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_sms += 50;
        pagecounttrans++;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    } else {
        pagecounttrans = pagecountTransaction;
    }
}
// end of next records
cObj("toprevNac_sms").onclick = function () {
    if (pagecounttrans > 1) {
        pagecounttrans--;
        startpage_sms -= 50;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}
cObj("tofirstNav_sms").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = 1;
        startpage_sms = 0;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}
cObj("tolastNav_sms").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = pagecountTransaction;
        startpage_sms = (pagecounttrans * 50) - 50;
        var endpage = startpage_sms + 50;
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(startpage_sms, endpage, rowsColStudents_sms);
        view_and_edit_listeners();
    }
}

// seacrh keyword at the table
cObj("searchkey_sms").onkeyup = function () {
    searchMySms(this.value);
}
//create a function to check if the array has the keyword being searched for
function searchMySms(keyword) {
    rowsColStudents_sms = rowsNCols_original_sms;
    pagecounttrans = 1;
    if (keyword.length > 0) {
        // cObj("tablefooter").classList.add("invisible");
    } else {
        // cObj("tablefooter").classList.remove("invisible");
    }
    // console.log(keyword.toLowerCase());
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_sms.length; index++) {
        const element = rowsColStudents_sms[index];
        //column break
        var present = 0;
        if (element[0].toString().toLowerCase().includes(keylower) || element[0].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3].toString().toLowerCase().includes(keylower) || element[3].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[5].toString().toLowerCase().includes(keylower) || element[5].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[6].toString().toLowerCase().includes(keylower) || element[6].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[7].toString().toLowerCase().includes(keylower) || element[7].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[8].toString().toLowerCase().includes(keylower) || element[8].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[9].toString().toLowerCase().includes(keylower) || element[9].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[10].toString().toLowerCase().includes(keylower) || element[10].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[11].toString().toLowerCase().includes(keylower) || element[11].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[13].toString().toLowerCase().includes(keylower) || element[13].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    // console.log(rowsNcol2.length);
    if (rowsNcol2.length > 0) {
        rowsColStudents_sms = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction = Math.ceil(counted);
        cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsNcol2);
        view_and_edit_listeners();
        cObj("tot_records_sms").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_sms").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_sms").innerText = 0;
        cObj("finishNo_sms").innerText = 0;
        cObj("tot_records_sms").innerText = 0;
        pagecountTransaction = 1;
    }
}

// sort in ascending or descending order
var sortall_sms_status = 1;
var sort_message_type_status = 1;
var sort_content_status = 1;
function sortTable_sms() {
    cObj("sortall_sms_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sortall_sms_status == 0) {
            // asc up to down
            sortall_sms_status = 1;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 10);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sortall_sms").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sortall_sms_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 10);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sortall_sms").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_message_type_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_message_type_status == 0) {
            // asc up to down
            sort_message_type_status = 1;
            // console.log(cObj("sortadmno").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 6);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_message_type").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_message_type_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 6);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_message_type").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_content_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_content_status == 0) {
            // asc up to down
            sort_content_status = 1;
            // console.log(cObj("sortfeeamount").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortDesc(rowsColStudents_sms, 2);
            var counted = rowsColStudents_sms.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_sms);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_content").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_content_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_sms = rowsNCols_original_sms;
            rowsColStudents_sms = sortAsc(rowsColStudents_sms, 2);
            var counted = rowsColStudents_sms.length / 50;
            // console.log(rowsColStudents_sms);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_sms").innerHTML = displayRecord_sms(0, 50, rowsColStudents_sms);
            view_and_edit_listeners();
            cObj("tot_records_sms").innerText = rowsColStudents_sms.length;
            cObj("sort_content").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sortdate_sms").addEventListener("click", function () {
        cObj("sortall_sms_th").click();
    });
}

cObj("insert_tag1").onclick = function () {
    // check if its email or sms first
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fullname}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_fullname}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag2").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_first_name}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_first_name}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag3").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_class}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_class}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag4").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_age}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_age}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag5").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_balance}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_fees_balance}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag6").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_to_pay}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_fees_to_pay}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag7").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_fees_paid}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_fees_paid}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag8").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {par_fullname}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {par_fullname}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag9").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {today}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {today}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag10").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {par_first_name}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {par_first_name}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag11").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {title_1}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {title_1}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag12").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {title_2}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {title_2}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
cObj("insert_tag13").onclick = function () {
    var send_options = cObj("send_options").value;
    if (send_options == "send_sms") {
        var valued = cObj("text_message2").value.trim();
        cObj("text_message2").value = valued + " {stud_noun}";
        messageData();
    } else {
        var valued = tinymce.get("email_editored").getContent();
        tinymce.get("email_editored").setContent(valued + " {stud_noun}");
        html_messageData(tinymce.get("email_editored").getContent());
    }
}
function process_messages(data) {
    var message = data;
    message = message.replace(/{stud_fullname}/g, "<b class='text-primary'>Esmond Adala</b>");
    message = message.replace(/{stud_first_name}/g, "<b class='text-primary'>Esmond</b>");
    message = message.replace(/{stud_class}/g, "<b class='text-primary'>Grade 7</b>");
    message = message.replace(/{stud_age}/g, "<b class='text-primary'>12 yrs</b>");
    message = message.replace(/{stud_fees_balance}/g, "<b class='text-primary'>1,000</b>");
    message = message.replace(/{stud_fees_to_pay}/g, "<b class='text-primary'>36,578</b>");
    message = message.replace(/{stud_fees_paid}/g, "<b class='text-primary'>22,121</b>");
    message = message.replace(/{par_fullname}/g, "<b class='text-primary'>Mathias Adala</b>");
    message = message.replace(/{par_first_name}/g, "<b class='text-primary'>Mathias</b>");
    message = message.replace(/{title_1}/g, "<b class='text-primary'>Mr</b>");
    message = message.replace(/{title_2}/g, "<b class='text-primary'>Sir</b>");
    message = message.replace(/{today}/g, "<b class='text-primary'>30th Jun 2022</b>");
    message = message.replace(/{stud_noun}/g, "<b class='text-primary'>Son</b>");
    return message;
}

function messageData() {
    var my_message = cObj("text_message2").value;
    my_message = process_messages(my_message);
    cObj("message_samples").innerHTML = my_message;
}

function html_messageData(content) {
    var my_message = content;
    my_message = process_messages(my_message);
    cObj("message_samples").innerHTML = my_message;
}

// email messages table

//function toget the recent messages sent
function getRecentEmail() {
    var datapass = "?email_history=true";
    sendData2("GET", "sms/sms.php", datapass, cObj("histotyemail"), cObj("email_loaders_window"));
    setTimeout(() => {
        var timeout = 0;
        var id23w = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(id23w);
            }
            if (cObj("email_loaders_window").classList.contains("hide")) {
                stopInterval(id23w);
                if (cObj("email_data_results") != undefined && cObj("email_data_results") != null) {
                    var emails_data = cObj("email_data_results").innerText;
                    emails_data = decodeURIComponent(emails_data);
                    emails_data = JSON.parse(emails_data);
                    // console.log(emails_data);
                    create_email_data_table(emails_data);
                }
            }
        }, 100);
    }, 200);
}


// from here we set the table for sms
var rowsColStudents_email = [];
var rowsNCols_original_email = [];
var pagecountTransaction_emails = 0; //this are the number of pages for transaction
var pagecounttrans_emails = 1; //the current page the user is
var startpage_email = 0; // this is where we start counting the page number

// load the user data
function create_email_data_table(email_data) {
    rowsColStudents_email = [];
    rowsNCols_original_email = [];
    pagecountTransaction_emails = 0; //this are the number of pages for transaction
    pagecounttrans_emails = 1; //the current page the user is
    startpage_email = 0; // this is where we start counting the page number
    // console.log(email_data.length);
    // get the arrays
    if (email_data.length > 0) {
        var rows = email_data;
        //create a column now
        for (let index = 0; index < rows.length; index++) {
            const element = rows[index];
            // create the collumn array that will take the row value
            var col = [];
            // console.log(element);
            col.push(element['charged']);
            col.push(element['date_time']);
            col.push(element['sender_from']);
            col.push(element['recipient_to']);
            col.push(element['message_subject']);
            col.push(element['bcc']);
            col.push(element['message']);
            col.push(element['attachments']);
            col.push(element['cc']);
            col.push(index + 1);
            col.push(element['id']);
            // var col = element.split(":");
            rowsColStudents_email.push(col);
        }
        rowsNCols_original_email = rowsColStudents_email;
        cObj("tot_records_email").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
        viewEmailAddress();

        //show the number of pages for each record
        var counted = rows.length / 50;
        pagecountTransaction_emails = Math.ceil(counted);

    } else {
        cObj("transDataReciever_email").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_email").classList.add("invisible");
    }
}

function setDateEmail(email_date) {
    var our_date = email_date;
    var year = our_date.substr(0, 4);
    var month = our_date.substr(4, 2);
    var day = our_date.substr(6, 2);
    var hour = our_date.substr(8, 2);
    var minute = our_date.substr(10, 2);

    var months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return day + "-" + months[month] + "-" + year + " @ " + hour + ":" + minute;
}

function displayRecord_email(start, finish, arrays) {
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th title='Sort all descending' id='sortall_email_th'># <span id='sortall_email'><i class='fas fa-caret-down'></i></span></th><th id='sort_email_type_th' >Email Sender <span id='sort_email_type'><i class='fas fa-caret-down'></i></span></th><th  title='Sort by date descending'>Email Content <span id='sortdate_email'><i class='fas fa-caret-down'></i></span></th><th>View</th></tr></thead><tbody>";
    if (finish < total) {
        fins = finish;
        //create a table of the 50 records
        var counter = start + 1;
        for (let index = start; index < finish; index++) {

            tableData += "<tr title =''><td>" + (index + 1) + "</td><td>From: " + arrays[index][2] + "<br>To : " + arrays[index][3] + "<br>" + setDateEmail(arrays[index][1]) + "</td><td>" + arrays[index][6] + "</td><td><span id='email_data" + arrays[index][10] + "' class='email_data link'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
    } else {
        //create a table of the 50 records
        var counter = start + 1;
        for (let index = start; index < total; index++) {

            tableData += "<tr title =''><td>" + (index + 1) + "</td><td>From: " + arrays[index][2] + "<br>To : " + arrays[index][3] + "<br>" + setDateEmail(arrays[index][1]) + "</td><td>" + arrays[index][6] + "</td><td><span id='email_data" + arrays[index][10] + "' class='email_data link'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
        fins = total;
    }

    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_email").innerText = start + 1;
    cObj("finishNo_email").innerText = fins;
    //set the page number
    cObj("pagenumNav_email").innerText = pagecounttrans_emails;
    // set tool tip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    if (cObj("sort_content_th_email") != undefined) {
        sortTable_email();
    } else {
        setTimeout(() => {
            sortTable_email();
        }, 500);
    }
    return tableData;
}
// sort in ascending or descending order
var sortall_email_status = 1;
var sort_email_type_status = 1;
var sort_mails_status = 1;
function sortTable_email() {
    cObj("sortall_email_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sortall_email_status == 0) {
            // asc up to down
            sortall_email_status = 1;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortDesc(rowsColStudents_email, 10);
            var counted = rowsColStudents_email.length / 50;
            pagecountTransaction_emails = Math.ceil(counted);
            // console.log(rowsColStudents_email);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sortall_email").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sortall_email_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortAsc(rowsColStudents_email, 10);
            var counted = rowsColStudents_email.length / 50;
            // console.log(rowsColStudents_email);
            pagecountTransaction_emails = Math.ceil(counted);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sortall_email").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sort_email_type_th").addEventListener("click", function () {
        // sort all in ascending order
        if (sort_email_type_status == 0) {
            // asc up to down
            sort_email_type_status = 1;
            // console.log(cObj("sortadmno").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortDesc(rowsColStudents_email, 6);
            var counted = rowsColStudents_email.length / 50;
            pagecountTransaction_emails = Math.ceil(counted);
            // console.log(rowsColStudents_email);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sort_email_type").innerHTML = "- <i class='fas fa-caret-down'></i>";
        } else {
            // desc down to up
            sort_email_type_status = 0;
            //WITH FIRST COLUMN
            rowsColStudents_email = rowsNCols_original_sms;
            rowsColStudents_email = sortAsc(rowsColStudents_email, 6);
            var counted = rowsColStudents_email.length / 50;
            // console.log(rowsColStudents_email);
            pagecountTransaction_emails = Math.ceil(counted);
            cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsColStudents_email);
            viewEmailAddress();
            cObj("tot_records_email").innerText = rowsColStudents_email.length;
            cObj("sort_email_type").innerHTML = "- <i class='fas fa-caret-up'></i>";
        }
    });
    cObj("sortdate_sms").addEventListener("click", function () {
        cObj("sortall_email_th").click();
    });
}


//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_email").onclick = function () {
    // console.log(pagecounttrans_emails+" "+pagecountTransaction_emails);
    if (pagecounttrans_emails < pagecountTransaction_emails) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_email += 50;
        pagecounttrans_emails++;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    } else {
        pagecounttrans_emails = pagecountTransaction_emails;
    }
}
// end of next records
cObj("toprevNac_email").onclick = function () {
    if (pagecounttrans_emails > 1) {
        pagecounttrans_emails--;
        startpage_email -= 50;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}
cObj("tofirstNav_email").onclick = function () {
    if (pagecountTransaction_emails > 0) {
        pagecounttrans_emails = 1;
        startpage_email = 0;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}
cObj("tolastNav_email").onclick = function () {
    if (pagecountTransaction_emails > 0) {
        pagecounttrans_emails = pagecountTransaction_emails;
        startpage_email = (pagecounttrans_emails * 50) - 50;
        var endpage = startpage_email + 50;
        cObj("transDataReciever_email").innerHTML = displayRecord_email(startpage_email, endpage, rowsColStudents_email);
        viewEmailAddress();
    }
}

// seacrh keyword at the table
cObj("searchkey_email").onkeyup = function () {
    searchMyEmail(this.value);
}
//create a function to check if the array has the keyword being searched for
function searchMyEmail(keyword) {
    rowsColStudents_email = rowsNCols_original_email;
    pagecounttrans_emails = 1;
    if (keyword.length > 0) {
        // cObj("tablefooter").classList.add("invisible");
    } else {
        // cObj("tablefooter").classList.remove("invisible");
    }
    // console.log(keyword.toLowerCase());
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_email.length; index++) {
        const element = rowsColStudents_email[index];
        //column break
        var present = 0;
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3] != null) {
            if (element[3].toString().toLowerCase().includes(keylower) || element[3].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[4] != null) {
            if (element[4].toString().toLowerCase().includes(keylower) || element[4].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[5] != null) {
            if (element[5].toString().toLowerCase().includes(keylower) || element[5].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[6] != null) {
            if (element[6].toString().toLowerCase().includes(keylower) || element[6].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        if (element[8] != null) {
            if (element[8].toString().toLowerCase().includes(keylower) || element[8].toString().toUpperCase().includes(keyUpper)) {
                present++;
            }
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    // console.log(rowsNcol2.length);
    if (rowsNcol2.length > 0) {
        rowsColStudents_email = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction_emails = Math.ceil(counted);
        cObj("transDataReciever_email").innerHTML = displayRecord_email(0, 50, rowsNcol2);
        viewEmailAddress();
        cObj("tot_records_email").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_email").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_sms").innerText = 0;
        cObj("finishNo_sms").innerText = 0;
        cObj("tot_records_email").innerText = 0;
        pagecountTransaction_emails = 1;
    }
}

function viewEmailAddress() {
    var email_data = document.getElementsByClassName("email_data");
    for (let index = 0; index < email_data.length; index++) {
        const element = email_data[index];
        element.onclick = function () {
            var email_id = element.id.substring(10);
            // get the email data from the 
            var datapass = "?email_data=" + email_id;
            cObj("email_data_read_window").classList.remove("hide");
            sendData2("GET", "sms/sms.php", datapass, cObj("email_contents"), cObj("read_email_loader"));
            setTimeout(() => {
                var timeout = 0;
                var id23w = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(id23w);
                    }
                    if (cObj("read_email_loader").classList.contains("hide")) {
                        stopInterval(id23w);
                        if (cObj("delete_email") != undefined || cObj("delete_email") != null) {
                            cObj("delete_email").onclick = function () {
                                // delete the email
                                var datapass = "?delete_mail=" + cObj("emai_id_delete").innerText;
                                sendData2("GET", "sms/sms.php", datapass, cObj("email_text_holder"), cObj("delete_email_loader"));
                                setTimeout(() => {
                                    var timeout = 0;
                                    var id23w1 = setInterval(() => {
                                        timeout++;
                                        //after two minutes of slow connection the next process wont be executed
                                        if (timeout == 1200) {
                                            stopInterval(id23w1);
                                        }
                                        if (cObj("read_email_loader").classList.contains("hide")) {
                                            stopInterval(id23w1);
                                            cObj("email_data_read_window").classList.add("hide");
                                            getRecentEmail();
                                        }
                                    }, 100);
                                }, 200);
                            }
                        }
                    }
                }, 100);
            }, 200);
        }
    }
}

cObj("close_email_data_read_window").onclick = function () {
    cObj("email_data_read_window").classList.add("hide");
}
cObj("close_email_data_windows").onclick = function () {
    cObj("email_data_read_window").classList.add("hide");
}

cObj("rather_view_email_history").onclick = function () {
    cObj("show_sms_windows").classList.add("hide");
    cObj("show_email_windows").classList.remove("hide");
    getRecentEmail();
}
cObj("rather_view_sms_history").onclick = function () {
    cObj("show_sms_windows").classList.remove("hide");
    cObj("show_email_windows").classList.add("hide");

    // getRecentMessage()
    getRecentMessage();
}