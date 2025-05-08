let presentBCNO = false;
let studentinformation;
let presentid;
let staffdata = [];
/***active windows */
cObj("admitbtn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("admitsStudents").classList.remove("hide");
    removesidebar();
    //get the classes from the database for the admissions window
    getClasses("class_admission", "errolment", "");
    getLastAdm();
}

cObj("supplier_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);

    // unhide the main page
    cObj("supplier_data").classList.remove("hide");
    display_supplier();
    removesidebar();
}

// asset_accounts
cObj("asset_account_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);

    // unhide the main page
    cObj("asset_accounts").classList.remove("hide");
    display_assets();
    removesidebar();
}
function getDepartmentsList() {
    // get the departments
    var datapass = "?get_departments=true";
    sendData2("GET","administration/admissions.php",datapass,cObj("admit_department"),cObj("department_all_loader"));
}

function getCourseList(course_level) {
    // get the course lists
    var datapass = "?get_course_list=true&course_level="+course_level;
    sendData2("GET","administration/admissions.php",datapass,cObj("course_list_holder"),cObj("course_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("course_list_loader").classList.contains("hide")) {
                if (cObj("course_chosen")!=undefined && cObj("course_chosen")!=null) {
                    cObj("course_chosen").addEventListener("change", getCourseModuleTerms);
                }
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
    
}

function getCourseModuleTerms() {
    var datapass = "?get_course_module_terms="+this.value;
    sendData2("GET","administration/admissions.php", datapass, cObj("course_term_holder"), cObj("course_term_loader"));
}
function getCourseListEdit(course_level) {
    // get the course lists
    var datapass = "?get_course_list_edit=true&course_level="+course_level;
    sendData2("GET","administration/admissions.php",datapass,cObj("course_list_edit"),cObj("course_list_edit_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("course_list_edit_loader").classList.contains("hide")) {
                cObj("course_chosen_edit").addEventListener("change",courseChange);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function courseChange() {
    // warn the user of switching to a different level if they have not complete this level and also the same should be done for the courses
    var select_options = document.getElementsByClassName("select_options");
    var level_count = 0;
    for (let index = 0; index < select_options.length; index++) {
        const element = select_options[index];
        console.log(element.value);
        if (element.value != 2 && element.value != 1) {
            level_count++;
        }
    }

    // if the level count is greater than zero it means that there is a term that has not been completed by the student
    if(level_count > 0 && this.value != valObj("course_chosen_level_hidden")){
        cObj("course_chosen_error_window").innerHTML = "<p class='text-danger'>You have changed the student`s course without them completing. Make sure they have completed every module term before moving them to any level.</p>";
    }else{
        cObj("course_chosen_error_window").innerHTML = "";
    }
}

cObj("pleasewaiting").onclick = function () {
    //removePleasewait();
}
function getClasses(object_id, select_class_id, value_prefix, obj = null) {
    var datapass = "?getclass=true&select_class_id=" + select_class_id + "&value_prefix=" + value_prefix;
    obj == null ? sendData1("GET", "administration/admissions.php", datapass, cObj(object_id)) : sendData2("GET", "administration/admissions.php", datapass, cObj(object_id), cObj(obj));
    if (select_class_id == "errolment") {
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                var loader = obj == null ? cObj("loadings") : obj;
                if (loader.classList.contains("hide")) {
                    if (cObj("errolment")!=undefined) {
                        cObj("errolment").addEventListener("change", select_courses);
                    }
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function select_courses_edit() {
    // get courses list
    // get another search by course list
    var datapass = "?get_course_list_search=true&course_levels="+this.value;
    sendData2("GET","administration/admissions.php",datapass,cObj("get_student_class_list"),cObj("course_list_find_loader"));
}
// select courses
function select_courses() {
    // get the course list
    // console.log(this.value);
    getCourseList(this.value);
}

function getLastAdm() {
    var datapass = "?last_admno_used=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("last_admno_holder"), cObj("load_admno"));
}
function showPleasewait() {
    cObj("pleasewaiting").classList.add("animate");
    cObj("pleasewaiting").classList.remove("hide");
}
function removePleasewait() {
    cObj("pleasewaiting").classList.remove("animate");
    cObj("pleasewaiting").classList.add("animate10");
    setTimeout(() => {
        cObj("pleasewaiting").classList.add("hide");
        cObj("pleasewaiting").classList.remove("animate10");
    }, 900);
}
cObj("dash").onclick = function () {
    hideWindow();
    unselectbtns();
    var auth = cObj("authoriti").value;
    if (auth == '0') {
        cObj("adminsdash").classList.remove("hide");
    } else if (auth == '1') {
        cObj("htdash").classList.remove("hide");
    } else if (auth == '5') {
        cObj("ctdash").classList.remove("hide");
    } else if (auth == '2') {
        cObj("tr_dash").classList.remove("hide");
    } else if (auth == '3') {
        cObj("dp_dash").classList.remove("hide");
    } else {
        cObj("tr_dash").classList.remove("hide");
    }
}
cObj("skip").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("admitsStudents").classList.remove("hide");
}
var auth = cObj("authoriti").value;
if (auth == 1 || auth == 3) {
    cObj("check_logs").onclick = function () {
        hideWindow();
        cObj("loggers_page").classList.remove("hide");
    }
}
cObj("findstudsbtn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("findstudents").classList.remove("hide");
    removesidebar();

    //get the classes from the database for the admissions window
    getClasses("stud_class_find", "selclass", "","course_list_edit_loader");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("course_list_edit_loader").classList.contains("hide")) {
                cObj("selclass").addEventListener("change",select_courses_edit);
                stopInterval(ids);
            }
        }, 100);
    }, 100);

    getClasses("class_holders", "classed", "cl", "class_loaders_id_in");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("class_loaders_id_in").classList.contains("hide")) {
                if (cObj("classed") != undefined && cObj("classed") != null) {
                    cObj("classed").addEventListener("change", showClassChange);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 1000);
    getClubSportsList();
    cObj("resultsbody").classList.remove("hide");
    cObj("viewinformation").classList.add("hide");
}
function showClassChange() {
    if (this.value == "-2") {
        cObj("reason_for_leaving_window").classList.remove("hide");
    }else {
        cObj("reason_for_leaving_window").classList.add("hide");
    }

    // get the course that the student can be enrolled to
    var course_level = cObj("classed") != undefined ? valObj("classed") : "-1";
    getCourseListEdit(course_level);

    // warn the user of switching to a different level if they have not complete this level and also the same should be done for the courses
    var select_options = document.getElementsByClassName("select_options");
    var level_count = 0;
    for (let index = 0; index < select_options.length; index++) {
        const element = select_options[index];
        console.log(element.value);
        if (element.value != 2 && element.value != 1) {
            level_count++;
        }
    }

    // if the level count is greater than zero it means that there is a term that has not been completed by the student
    if(level_count > 0 && this.value != valObj("course_level_hidden")){
        cObj("course_level_error_window").innerHTML = "<p class='text-danger'>You have changed the student`s course level without them completing. Make sure they have completed every term before moving them to any level.</p>";
    }else{
        cObj("course_level_error_window").innerHTML = "";
    }
}
cObj("update_school_profile").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("update_school_profile_page").classList.remove("hide");
    removesidebar();
    getSchoolInformation();
}
cObj("apply_leave_staff").onclick = function () {
    hideWindow();
    unselectbtns();
    // addselected(this.id);
    cObj("leave_mgmt_staff").classList.remove("hide");
    removesidebar();
    // get leaves applied
    getLeavesApplied();
}
cObj("update_personal_profile").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("personal_profile_page").classList.remove("hide");
    removesidebar();
    //get my personal information
    getPersonalInformation();
}
cObj("subject_selections").onclick = function () {
    hideWindow();
    unselectbtns();

    // addselected(this.id);
    cObj("subject_selection_window").classList.remove("hide");

    // remove sidebar
    removesidebar();

    // display class
    getClasses("subject_selection_class_list","selection_selected_class","","exams_data_loaders");
}
function getSchoolInformation() {
    var datapass = "?getSchoolInformation=true";
    sendData1("GET", "login/login.php", datapass, cObj("store_sch_information"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var schoolInformation = cObj("store_sch_information").innerText;
                if (hasJsonStructure(schoolInformation)) {
                    schoolInformation = JSON.parse(schoolInformation);

                    cObj("school_name_s").value = schoolInformation[1];
                    cObj("school_motto_s").value = schoolInformation[2];
                    cObj("school_vission").value = schoolInformation[5];
                    // console.log(schoolInformation);
                    // console.log(cObj("store_sch_information").innerText);
                    cObj("school_codes").value = schoolInformation[0];
                    cObj("school_message_name").value = schoolInformation[8];
                    cObj("administrator_name").value = schoolInformation[9];
                    cObj("administrator_contacts").value = schoolInformation[6];
                    cObj("administrator_email").value = schoolInformation[7];
                    cObj("school_box_no").value = schoolInformation[10];
                    cObj("box_Code").value = schoolInformation[11];
                    cObj(schoolInformation[13]).selected = true;
                    cObj(schoolInformation[12]).selected = true;

                    cObj("sch_physical_address").value = schoolInformation[14];
                    cObj("school_websites").value = schoolInformation[15];

                    // commented for null values
                    // cObj(schoolInformation[12]).selected = true;
                    // cObj(schoolInformation[13]).selected = true;
                } else {
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("cancel_payment_options").onclick = function () {
    cObj("payment_description_texts").value = "";
    cObj("add_payment_options_window").classList.add("hide");
}

cObj("close_window_payment_options").onclick = function () {
    cObj("cancel_payment_options").click();
}

cObj("setup_payment_options").onclick = function () {
    cObj("add_payment_options_window").classList.remove("hide");
}

cObj("save_payment_option").onclick = function () {
    var err = checkBlank("payment_description_texts");
    if (err == 0) {
        var data = valObj("payment_description");
        if (data.length > 0) {
            var newdata = "{\"description\":\"" + valObj("payment_description_texts") + "\",\"show\":\"true\"}";
            data = data.substr(0, data.length - 1) + "," + newdata + "]";
            cObj("payment_description").value = data;
            displayPaymentOption(data);
        } else {
            var newdata = "[{\"description\":\"" + valObj("payment_description_texts") + "\",\"show\":\"true\"}]";
            cObj("payment_description").value = newdata;
            displayPaymentOption(newdata);
        }
        cObj("cancel_payment_options").click();
    }
}

function displayPaymentOption(data) {
    if (data.length > 0) {
        if (hasJsonStructure(data)) {
            var json_data = JSON.parse(data);
            var data_to_display = "<table class='table'><tr><th>No.</th><th>Payments Description.</th><th>Arrange</th><th title='Display in reciepts and invoices' >Show</th><th>Actions.</th></tr>";

            for (let index = 0; index < json_data.length; index++) {
                // get the select option
                var first_option = index != 0 ? "<option value='[-1," + index + "]'>At the beginning</option>" : "";
                var select = "<select id='arrange_pd_" + index + "' class='form-control arrange_pd'><option hidden value=''>Select option</option>" + first_option;
                for (let index_1 = 0; index_1 < json_data.length; index_1++) {
                    if (index_1 != index) {
                        select += "<option value='[\"" + index_1 + "\",\"" + index + "\"]'>After Description " + (index_1 + 1) + "</option>";
                    }
                }
                select += "</select>";
                const element = json_data[index];
                var checked = element.show == "true" ? "checked" : "";
                data_to_display += "<tr><td>" + (index + 1) + ". </td><td id='descriptied" + (index + 1) + "'>" + element.description + "</td>";
                data_to_display += "<td>" + select + "</td>";
                data_to_display += "<td><input type='checkbox' " + checked + " class='pd_show_' id='pd_show_" + (index + 1) + "'></td><td><p><span class='mx-1 link edit_pd' id='edit_pd_" + (index + 1) + "'> <i class='fas fa-pen-fancy'></i></span> <span class='mx-1 link delete_pd' id='delete_pd_" + (index + 1) + "'><i class='fas fa-trash'></i></span></p></td></tr>";
            }
            data_to_display += "</table>";
            cObj("pd_table_holder").innerHTML = data_to_display;

            var arrange_pd = document.getElementsByClassName("arrange_pd");
            for (let index = 0; index < arrange_pd.length; index++) {
                const element = arrange_pd[index];
                element.addEventListener("change", arrangePD);
            }
            var edit_pd = document.getElementsByClassName("edit_pd");
            for (let index = 0; index < edit_pd.length; index++) {
                const element = edit_pd[index];
                element.addEventListener("click", editChangesDesc);
            }
            var pd_show_ = document.getElementsByClassName("pd_show_");
            for (let index = 0; index < pd_show_.length; index++) {
                const element = pd_show_[index];
                element.addEventListener("change", showPaymentDescription);
            }
            var delete_pd = document.getElementsByClassName("delete_pd");
            for (let index = 0; index < delete_pd.length; index++) {
                const element = delete_pd[index];
                element.addEventListener("click", deleteDescription);
            }
        } else {
            cObj("pd_table_holder").innerHTML = "<p class='text-danger border border-danger my-2 p-2'>An error has occured.</p>";
        }
    } else {
        cObj("pd_table_holder").innerHTML = "<p class='text-danger border border-danger my-2 p-2'>Set up the payment options then proceed!.</p>";
    }
}

function deleteDescription() {
    var data_index = (this.id.substr(10) * 1) - 1;
    cObj("delete_pd_desc_win").classList.remove("hide");

    cObj("get_pd_index").value = data_index + 1;
    cObj("description_index").innerHTML = "<b>Description " + (data_index + 1) + "</b>"
}

cObj("no_delete_pd").onclick = function () {
    cObj("delete_pd_desc_win").classList.add("hide");
}
cObj("yes_delete_pd").onclick = function () {
    var json_data = valObj("payment_description");

    if (hasJsonStructure(json_data)) {
        var data_index = valObj("get_pd_index");
        var data_json = JSON.parse(json_data);
        var new_json_data = [];
        for (let index = 0; index < data_json.length; index++) {
            const element = data_json[index];
            if (data_index != index) {
                new_json_data.push(element);
            }
        }
        cObj("payment_description").value = JSON.stringify(new_json_data);
        cObj("save_changes_payment_opt").click();
        cObj("no_delete_pd").click();
    }
}

function showPaymentDescription() {
    var data_index = (this.id.substr(8) * 1) - 1;
    var data_json = valObj("payment_description");
    if (hasJsonStructure(data_json)) {
        var json_data = JSON.parse(data_json);

        for (let index = 0; index < json_data.length; index++) {
            const element = json_data[index];
            if (data_index == index) {
                if (this.checked == true) {
                    element.show = "true";
                } else {
                    element.show = "false";
                }
            }
        }

        cObj("payment_description").value = JSON.stringify(json_data);
        cObj("save_changes_payment_opt").click();
    }
}

function editChangesDesc() {
    var ids = this.id.substr(8);
    var this_value = cObj("descriptied" + ids).innerText;
    cObj("label_desc").value = this_value;
    cObj("change_description_name").classList.remove("hide");
    cObj("change_description_id").value = ids;
}

cObj("cancel_save_changes_new_pd").onclick = function () {
    cObj("label_desc").value = "";
    cObj("change_description_name").classList.add("hide");
}
cObj("save_changes_new_pd").onclick = function () {
    var err = checkBlank("label_desc");
    if (err == 0) {
        var selected_index = (valObj("change_description_id") * 1 - 1);
        var this_value = valObj("label_desc");

        var json_data = valObj("payment_description");
        if (hasJsonStructure(json_data)) {
            json_data = JSON.parse(json_data);
            for (let index = 0; index < json_data.length; index++) {
                const element = json_data[index];
                if (selected_index == index) {
                    element.description = this_value;
                }
            }
            cObj("payment_description").value = JSON.stringify(json_data);
            cObj("save_changes_payment_opt").click();
            cObj("cancel_save_changes_new_pd").click();
        }
    }
}

function arrangePD() {
    console.log(this.value);
    if (this.value.length > 0) {
        var data = valObj("payment_description");
        if (hasJsonStructure(data)) {
            var json_data = JSON.parse(data);
            var selected_index = JSON.parse(this.value);

            var new_arraus = [];

            var selected_element = json_data[selected_index[1]];

            for (let index = 0; index < json_data.length; index++) {
                const element = json_data[index];
                if (selected_index[0] == "-1" && index == 0) {
                    new_arraus.push(selected_element);
                }
                if (selected_index[1] != index) {
                    new_arraus.push(element);
                }
                if (selected_index[0] == index) {
                    new_arraus.push(selected_element);
                }
            }

            var data_string = JSON.stringify(new_arraus);
            cObj("payment_description").value = data_string;
            displayPaymentOption(data_string);
        }
    }
}

cObj("save_changes_payment_opt").onclick = function () {
    var datapass = "save_payment_options=true&payment_data=" + encodeURIComponent(valObj("payment_description"));
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("display_data_po"), cObj("payment_options_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
            }
            if (cObj("payment_options_loaders").classList.contains("hide")) {
                getPaymentOptions();
                setTimeout(() => {
                    cObj("display_data_po").innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function getPaymentOptions() {
    var datapass = "getPaymentOptions=true";
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("payment_details_blocks"), cObj("payment_options_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
            }
            if (cObj("payment_options_loaders").classList.contains("hide")) {
                displayPaymentOption(cObj("payment_details_blocks").innerText);
                cObj("payment_description").value = cObj("payment_details_blocks").innerText;
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function getPersonalInformation() {
    //get the personal informaton
    var datapass = "?get_my_information=true;";
    sendData1("GET", "login/login.php", datapass, cObj("my_information_inner"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (cObj("my_information").innerText.length > 0) {
                    var personalInformation = cObj("my_information").innerText.split("|");
                    if (personalInformation.length > 0) {
                        cObj("my_full_name").value = personalInformation[0];
                        cObj("my_dob").value = personalInformation[1];
                        cObj(personalInformation[4] + "12").selected = true;
                        cObj("my_phone_no").value = personalInformation[3];
                        cObj("my_nat_id").value = personalInformation[6];
                        cObj("my_tsc_code").value = personalInformation[7];
                        cObj("my_mail").value = personalInformation[10];
                        cObj("my_address").value = personalInformation[5];
                        cObj("sys_username").value = personalInformation[8];
                    }
                    stopInterval(ids);
                }
            }
        }, 100);
    }, 200);
}

cObj("manage_departments").onclick = function () {
    // hide all windows
    hideWindow();

    // unselect all buttons so that we may not know where in the menu we are
    unselectbtns();

    // select this button so that we can know where we are in the menu
    addselected("managestaf");

    // display the window related to the menu on the left
    cObj("department_manager").classList.remove("hide");

    // remove side bar if display is on phone
    removesidebar();

    // display departments
    displayDepartments();
}

cObj("callregister").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("classregister").classList.remove("hide");
    removesidebar();
    getStudentNameAdmno();
}

// mpesa tables
cObj("mpesaTrans").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("mpesa_trans").classList.remove("hide");
    removesidebar();
    getMpesaPayments();
}

cObj("humanresource").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("human_resource_windows").classList.remove("hide");
    removesidebar();
}

cObj("my_reports").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("my_reports_page").classList.remove("hide");
    removesidebar();
    getMyReportclasses();
    getStudentNameAdmno();
}
cObj("send_feedback").onclick = function () {
    cObj("feed_back_btns").click();
}
cObj("feed_back_btns").onclick = function () {
    hideWindow();
    unselectbtns();
    cObj("send_feed_page").classList.remove("hide");
    removesidebar();
}
cObj("set_btns").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("settings_page").classList.remove("hide");
    removesidebar();
    //comeback
    getMyClassList();
    getActiveHours();
    activeTerms();
    getAdmissionEssentials();
    allowCTadmit();
    getRoleData();
    getClubHouses();
    email_settings();
    staff_roles_changes();
    working_days();
    getPaymentOptions();
    displayExpCategories();
    displayRevenueCategories();
    get_courses();
    get_admission_prefix();
}

if (typeof (cObj("callrollcall")) != 'undefined' && cObj("callrollcall") != null) {
    cObj("callrollcall").onclick = function () {
        hideWindow();
        unselectbtns();
        addselected("callregister");
        cObj("classregister").classList.remove("hide");
    }
}
/***
cObj("dashbutn").onclick = function () {
    hideWindow();
    unselectbtns();
    var auth = cObj("authoriti").value;
    if (auth=='0') {
        cObj("adminsdash").classList.remove("hide");        
    }else if (auth == '1') {
        cObj("htdash").classList.remove("hide");        
    }else if (auth == '5') {
        cObj("ctdash").classList.remove("hide");
    }else{
        cObj("ctdash").classList.remove("hide");
    }
    removesidebar();
} */
cObj("regstaffs").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("regstaff").classList.remove("hide");
    removesidebar();
    getStaff_roles();
}

cObj("managestaf").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("managestaff").classList.remove("hide");
    removesidebar();
    getStaff_roles_maanage();

    // display my staff
    viewstaffavailablebtn();
}

cObj("promoteStd").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("promoteStdd").classList.remove("hide");
    removesidebar();
    displayWholeSchool();
}
cObj("payfeess").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("payfeesd").classList.remove("hide");
    removesidebar();
    getStudentNameAdmno();
}
// record school income
cObj("record_school_income").onclick = function () {
    hideWindow();
    unselectbtns();
    cObj("record_other_schools_income").classList.remove("hide");
    removesidebar();
    getRevenue();
}
cObj("assign_fees_credit_notes").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(cObj("payfeess").id);
    cObj("assign_credit_note_window").classList.remove("hide");
    removesidebar();
    getCreditNote();
    cObj("back_to_credit_win").click();
}

cObj("findtrans").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("findtransaction").classList.remove("hide");
    removesidebar();
    getClasses("manage_trans", "classedd", "");
}

cObj("feestruct").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("feestructure").classList.remove("hide");
    removesidebar();
    getClasses("fees_struct_class", "daros", "");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }

            // loadings
            if (cObj("loadings").classList.contains("hide")) {
                cObj("daros").addEventListener("change", get_course_list_fees_struct);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function get_course_list_fees_struct() {
    // get the course list
    var datapass = "?get_course_list_fees_struct=true&course_level="+this.value;
    sendData2("GET","administration/admissions.php",datapass,cObj("search_fees_window_course"),cObj("show_course_list_loader"));
}

cObj("expenses_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("expenses_win").classList.remove("hide");
    removesidebar();
    //get daily expenses
    displayTodaysExpense();
    //getClasses("fees_struct_class","daros","");
}

cObj("approve_payments").onclick = function () {
    hideWindow();
    unselectbtns();
    // addselected(this.id);
    cObj("payment_approval_window").classList.remove("hide");
    removesidebar();
    display_payment_requests();
}

cObj("regsub").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("regsubjects").classList.remove("hide");
    removesidebar();
}
cObj("managesub").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("managesubjects").classList.remove("hide");
    removesidebar();
    displayAllSubjects();
}

cObj("managetrnsub").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("managesubanteach").classList.remove("hide");
    removesidebar();
}
cObj("maanage_dorm").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("dorm_registration").classList.remove("hide");
    removesidebar();
    cObj("refresh_dorm_list").click();
}
cObj("exam_fill_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("exam_fillings").classList.remove("hide");
    removesidebar();
}
cObj("sms_broadcast").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("send_sms").classList.remove("hide");
    removesidebar();
    //function to display teachers
    displayTeacherNotice();
    //get recent sent messages
    getRecentMessage();
}
cObj("finance_report_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("finance_statement").classList.remove("hide");
    removesidebar();
    incomeStatement();
}
cObj("payroll_sys").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("payrolled_win").classList.remove("hide");
    removesidebar();
}
cObj("routes_n_trans").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("transport_n_route").classList.remove("hide");
    removesidebar();
    getRouteList();
    getTransport();
}
cObj("enroll_students").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("enroll_students_transportsystem").classList.remove("hide");
    removesidebar();
    getStudentsTransport();
}
cObj("generate_tt_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("timetable_window").classList.remove("hide");
    removesidebar();
    cObj("view_tt_in").click();
}
cObj("enroll_boarding_btn").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("enroll_boarding").classList.remove("hide");
    removesidebar();
    cObj("display_all_present").click();
}
function selectListeners() {
    cObj("btn_panel").classList.remove("btns");
    cObj("btn_panel").classList.add("hide");
    cObj("classes_list").classList.add("hide");
    cObj("grading_methods").classList.add("hide");
    cObj("subject_list").classList.remove("hide");
    var exam_id = this.value;
    var datapass = "?get_exam_class=" + exam_id;
    sendData1("GET", "academic/academic.php", datapass, cObj("subject_list"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (typeof (cObj("sub_jectlists")) != 'undefined' && cObj("sub_jectlists") != null) {
                    cObj("sub_jectlists").addEventListener("change", selectSubject);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function selectSubject() {
    cObj("classes_list").classList.remove("hide");
    cObj("grading_methods").classList.remove("hide");
    cObj("btn_panel").classList.add("btns");
    cObj("btn_panel").classList.remove("hide");
    //show classes available 
    var subject_id = this.value;
    var exam_id = cObj("exam_list").value;
    var datapass = "?subjects_id_ds=" + subject_id + "&exams_id_ids=" + exam_id;
    sendData1("GET", "academic/academic.php", datapass, cObj("classes_list"));
}
cObj("examanagement").onclick = function () {
    hideWindow();
    unselectbtns();
    addselected(this.id);
    cObj("exammanagement").classList.remove("hide");
    removesidebar();
    var datapass = "?getExamination=onetermexams";
    sendData1("GET", "academic/academic.php", datapass, cObj("holdExaminfor"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
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
                    element.addEventListener("click", printExamsFunc);
                }
                var view_exam_result = document.getElementsByClassName("view_exam_result");
                for (let index = 0; index < view_exam_result.length; index++) {
                    const element = view_exam_result[index];
                    element.addEventListener("click", getExamsInfor);
                }
                var delete_exams_ = document.getElementsByClassName("delete_exams_");
                for (let index = 0; index < delete_exams_.length; index++) {
                    const element = delete_exams_[index];
                    element.addEventListener("click", delete_exams);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
/******end of active window****** */

function delete_exams() {
    var exams_name = cObj("exams_names_edit" + this.id.substr(13)).innerText;
    cObj("exams_ids_delete").value = this.id.substr(13);
    cObj("name_of_students_exams").innerText = exams_name;
    cObj("confirm_delete_exams_win").classList.remove("hide");
}

cObj("confirm_del_exams_no").onclick = function () {
    cObj("confirm_delete_exams_win").classList.add("hide");
}

cObj("confirm_del_exams_yes").onclick = function () {
    var err = checkBlank("exams_ids_delete");
    if (err == 0) {
        var exams_id = valObj("exams_ids_delete");
        var datapass = "?delete_exams=true&exams_id=" + exams_id;
        sendData2("GET", "academic/academic.php", datapass, cObj("exams_data_windows"), cObj("delete_exams_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    if (!cObj("viewexam").classList.contains("hide")) {
                        cObj("displaysubjects").click();
                    } else {
                        cObj("examanagement").click();
                    }
                    setTimeout(() => {
                        cObj("exams_data_windows").innerHTML = "";
                    }, 4000);
                    cObj("confirm_del_exams_no").click();
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

function getExamsInfor() {
    // switch the windows first
    cObj("exams_table_list").classList.add("hide");
    cObj("exams_details_window").classList.remove("hide");
    var datapass = "?get_exams_results=" + this.id.substr(16);
    cObj("exams_id_result").innerText = this.id.substr(16);
    sendData2("GET", "academic/academic.php", datapass, cObj("exams_details_holder"), cObj("exams_details_loader"));
    // cObj("exams_window_display").innerHTML = "";

}

cObj("back_exams_list").onclick = function () {
    cObj("exams_table_list").classList.remove("hide");
    cObj("exams_details_window").classList.add("hide");
    cObj("exams_window_display").innerHTML = "<p class='class-success text-center'>Your exams results will appear here!<br>Select class to proceed!</p>"
}

/********change password controls***********/
cObj("cancelchngebtn1").onclick = function () {
    cObj("passwindows").classList.remove("animate5");
    cObj("changepasswin").classList.add("hide");
    cObj("passwindows").style.left = '0px';
    cObj("changepass").style.height = '220px';
    valObj("enterpass").value = '';
    valObj("reenterpass").value = '';
}
cObj("cancelchngebtn2").onclick = function () {
    cObj("passwindows").classList.remove("animate5");
    cObj("changepasswin").classList.add("hide");
    cObj("passwindows").style.left = '0px';
    cObj("changepass").style.height = '220px';
    valObj("enterpass").value = '';
    valObj("reenterpass").value = '';
}

cObj("menubtn").onclick = function () {
    cObj("sideme").classList.remove("animate4");
    cObj("sideme").classList.add("animate3");
    cObj("sideme").classList.add("unhide");
    cObj("sideme").style.display = 'block';
    cObj("paneled").style.display = 'block';
}
cObj("closesidebar").onclick = function () {
    cObj("paneled").style.display = 'none';
    cObj("sideme").classList.remove("animate3");
    cObj("sideme").classList.add("animate4");
    setTimeout(() => {
        cObj("sideme").style.display = 'none';
    }, 400);
}
cObj("proceed").onclick = function () {
    cObj("passwindows").classList.add("animate5");
    setTimeout(() => {
        cObj("passwindows").style.left = '-400px';
    }, 499);
    cObj("changepass").style.height = '300px';
}
cObj("back_one").onclick = function () {
    cObj("backtostaff").click();
}

cObj("changepwd").onclick = function () {
    cObj("changepasswin").classList.remove("hide");
}

cObj("changebtns").onclick = function () {
    let err = 0;
    var full = cObj("fullnamed").value;
    var split = full.split(" ");
    cObj("namesdd").innerText = split[0] + "`s";
    err += checkBlank("enterpass");
    err += checkBlank("reenterpass");
    if (err > 0) {
        cObj("passworderrors").innerHTML = "<p style='color:red;'>Please fill both the password fields!</p>";
    } else {
        if (valObj("enterpass") == valObj("reenterpass")) {
            cObj("passworderrors").innerHTML = "<p style='color:green;'>Password do match</p>";
            cObj("changepasswin").classList.add("hide");
            let datapassings = "?updatingpassword=" + valObj("reenterpass") + "&usersids=" + cObj("staffid").innerText;
            sendData1("GET", "administration/admissions.php", datapassings, cObj("passworderrors2"));
            cObj("cancelchngebtn1").click();
        } else {
            cObj("passworderrors").innerHTML = "<p style='color:red;'>Passwords don`t match!</p>";
        }
    }
}
/********end of change password***********/





function staff_roles_changes() {
    // go make changes to the new staff roles
    var datapass = "?staff_role_changes=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("show_changes_roles"), cObj("load_changes_roles"));
}
function deleteCookie(cookieName) {
    document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  }
/*********Admission essentials**********/
window.onload = function () {
    // get the latest updates
    
    // Delete the cookie before
    deleteCookie("latest_update_1_"+user_id+"");

    // Example usage
    var user_id = cObj("useriddds").value;
    var isSet = isCookieSet("latest_update_3_"+user_id+"");
    if (isSet) {
        cObj("latest_updates_window").classList.add("hide");
    }else{
        setCookie("latest_update_3_"+user_id+"", "Latest : 8th Jul 2023", 30);
        cObj("latest_updates_window").classList.remove("hide");
    }


    //get essentials
    var datapass = "?getessentials=true";
    sendData("GET", "administration/admissions.php", datapass, cObj("admissionessentials"));
    var userid = cObj("authoriti").value;
    createTimetabe(userid);

    // if (userid == 5) {
    //     var admin = document.getElementsByClassName("htbtn");
    //     for (let index = 0; index < admin.length; index++) {
    //         const element = admin[index];
    //         element.style.display = 'none';
    //     }
    //     cObj("class_tr_only").classList.add("hide");
    //     cObj("class_tr_onl").classList.remove("hide");
    //     cObj("class_assigned_tr").value = cObj("classselected").value;
    //     cObj("class_tr_search").classList.remove("hide");
    //     cObj("updatestudinfor").classList.add("hide");
    //     //in name
    //     var ct_cg_val = cObj("ct_cg_gc").value;
    //     if (ct_cg_val == "Yes") {
    //         cObj("admitbtn").style.display = "flex";
    //         cObj("updatestudinfor").classList.remove("hide");
    //     }
    // } else if (userid == 2) {
    //     var admin = document.getElementsByClassName("htbtn");
    //     for (let index = 0; index < admin.length; index++) {
    //         const element = admin[index];
    //         element.style.display = 'none';
    //     }
    //     var admin = document.getElementsByClassName("tr_hides");
    //     for (let index = 0; index < admin.length; index++) {
    //         const element = admin[index];
    //         element.style.display = 'none';
    //     }
    //     cObj("class_tr_only").classList.add("hide");
    //     cObj("class_tr_onl").classList.remove("hide");
    //     cObj("class_assigned_tr").value = cObj("classselected").value;
    //     cObj("class_tr_search").classList.remove("hide");
    //     cObj("updatestudinfor").classList.add("hide");
    // } else {
    //     // console.log(userid);
    //     showNyMenu(userid);

    // }

    /***********start of class displays************/
    if (typeof (cObj("showmystuds")) != 'undefined' && cObj("showmystuds") != null) {
        cObj("showmystuds").onclick = function () {
            alert("My name is hillary");
        }
    }

    //get the payment details here
    var datapass2 = "?payfordetails=true";
    sendData("GET", "finance/financial.php", datapass2, cObj("payments"));

    var datapass3 = "?showsubjects=true";
    sendData("GET", "academic/academic.php", datapass3, cObj("subjectlist"));
    var datapass3 = "?showsubjected=true";
    sendData("GET", "academic/academic.php", datapass3, cObj("classeslist"));
    datapass3 = "?showsubject=true";
    sendData("GET", "academic/academic.php", datapass3, cObj("subjClass"));
    //show school logo
    changeSchoolDpLocale();
    //show dp
    changeDpLocale();
    /***********end of class displays************/
    // start of checkbox selection
    var administration1 = document.getElementsByClassName("administration1");
    for (let index = 0; index < administration1.length; index++) {
        const element = administration1[index];
        element.addEventListener("change", administration_check);
    }
    var finance1 = document.getElementsByClassName("finance1");
    for (let index = 0; index < finance1.length; index++) {
        const element = finance1[index];
        element.addEventListener("change", finance_check);
    }
    var routesnvans1 = document.getElementsByClassName("routesnvans1");
    for (let index = 0; index < routesnvans1.length; index++) {
        const element = routesnvans1[index];
        element.addEventListener("change", route_check);
    }
    var academic_sect = document.getElementsByClassName("academic_sect");
    for (let index = 0; index < academic_sect.length; index++) {
        const element = academic_sect[index];
        element.addEventListener("change", academic_check);
    }
    var boarding_sect = document.getElementsByClassName("boarding_sect");
    for (let index = 0; index < boarding_sect.length; index++) {
        const element = boarding_sect[index];
        element.addEventListener("change", boarding_check);
    }
    var sms_broadcasted = document.getElementsByClassName("sms_broadcasted");
    for (let index = 0; index < sms_broadcasted.length; index++) {
        const element = sms_broadcasted[index];
        element.addEventListener("change", all_sms_check);
    }
    var accounts_section = document.getElementsByClassName("accounts_section");
    for (let index = 0; index < accounts_section.length; index++) {
        const element = accounts_section[index];
        element.addEventListener("change", all_account_settings);
    }
    /******************DONT CONFUSE*****************/
    // start of edit checks 2
    var administration12 = document.getElementsByClassName("administration12");
    for (let index = 0; index < administration12.length; index++) {
        const element = administration12[index];
        element.addEventListener("change", administration_check2);
    }
    var finance12 = document.getElementsByClassName("finance12");
    for (let index = 0; index < finance12.length; index++) {
        const element = finance12[index];
        element.addEventListener("change", finance_check2);
    }
    var routesnvans12 = document.getElementsByClassName("routesnvans12");
    for (let index = 0; index < routesnvans12.length; index++) {
        const element = routesnvans12[index];
        element.addEventListener("change", route_check2);
    }
    var academic_sect2 = document.getElementsByClassName("academic_sect2");
    for (let index = 0; index < academic_sect2.length; index++) {
        const element = academic_sect2[index];
        element.addEventListener("change", academic_check2);
    }
    var boarding_sect2 = document.getElementsByClassName("boarding_sect2");
    for (let index = 0; index < boarding_sect2.length; index++) {
        const element = boarding_sect2[index];
        element.addEventListener("change", boarding_check2);
    }
    var sms_broadcasted2 = document.getElementsByClassName("sms_broadcasted2");
    for (let index = 0; index < sms_broadcasted2.length; index++) {
        const element = sms_broadcasted2[index];
        element.addEventListener("change", all_sms_check2);
    }
    var accounts_section2 = document.getElementsByClassName("accounts_section2");
    for (let index = 0; index < accounts_section2.length; index++) {
        const element = accounts_section2[index];
        element.addEventListener("change", all_account_settings2);
    }

    // get if the reports button is set
    var datapass = "set_report_button=true";
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("set_reports"), cObj("set_reports2"));
    // get the student list

    // allow editing of school logo
    cObj("sch_logos").onclick = function () {
        cObj("update_school_profile").click();
    }

    if (auth == '1') {
        //get number of students
        var datapass = "?getStudentCount=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studentscount"));

        //get number of students registerd today
        var datapass = "?studentscounttoday=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studentscounttoday"));


        //get number of students present in school today
        var datapass = "?studentspresenttoday=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studpresenttoday"));

        //get number off students absent

        setInterval(() => {
            if (!cObj("htdash").classList.contains("hide")) {
                var total = cObj("studentscount").innerText.split(" ");
                var present = cObj("studpresenttoday").innerText.split(" ");
                var total1 = total[0];
                var present1 = present[0];
                if (present1 != 0) {
                    cObj("absentstuds").innerText = (total1 - present1) + " Student(s)";
                } else {
                    cObj("absentstuds").innerText = "Roll call not taken.";
                }
            }
        }, 900000);

        //number of active users
        var datapass = "?checkactive=true&userid=" + cObj("useriddds").value;
        sendData("GET", "administration/admissions.php", datapass, cObj("activeusers"));


        //number of school fees recieved
        var datapass = "?schoolfeesrecieved=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("schoolfeesrecieved"));


        //number of transfered students
        var datapass = "?transfered_students=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("transfered_studs"));

        //number of alumnis students
        var datapass = "?alumnis_number=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("alumnis_number"));


        //get the logs
        var datapass = "?get_loggers=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("loggers_table"));

        //get the active exams
        var datapass = "?active_exams_lts=true";
        sendData("GET", "academic/academic.php", datapass, cObj("active_examination"));

        // subject list
        var datapass = "?subs_lists=true";
        sendData("GET", "academic/academic.php", datapass, cObj("my_subjects"));

        // unhide the payment approval button if logged in as the headteacher
        cObj("approve_payments").classList.remove("hide");
    }

    //deputy prncipal
    if (auth == 3) {

        //get number of students
        var datapass = "?getStudentCount=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studentscount"));

        //get number of students registerd today
        var datapass = "?studentscounttoday=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studentscounttoday"));

        //get number of students present in school today
        var datapass = "?studentspresenttoday=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studpresenttoday"));

        //number of active users
        var datapass = "?checkactive=true&userid=" + cObj("useriddds").value;
        sendData("GET", "administration/admissions.php", datapass, cObj("activeusers"));


        //get the logs
        var datapass = "?get_loggers=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("loggers_table"));

        //get the active exams
        var datapass = "?active_exams_lts=true";
        sendData("GET", "academic/academic.php", datapass, cObj("active_examination"));

        //my subjects
        var datapass = "?subs_lists=true";
        sendData("GET", "academic/academic.php", datapass, cObj("my_subjects"));

        //end of the deputy principal
    }

    //administrator dashboard = 0
    if (auth == 0) {
        //get number of students
        var datapass = "?getStudentCount=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("students"));

        //get number of users present in school
        var datapass = "?totaluserspresent=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studpresenttoday"));

        //number of active users
        var datapass = "?checkactive=true&userid=" + cObj("useriddds").value;
        sendData("GET", "administration/admissions.php", datapass, cObj("activeusers"));

        //get number of students present in school today
        var datapass = "?studentspresenttoday=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("rollcalnumber"));

        //get the logs
        var datapass = "?get_loggers=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("loggers_table"));

        //number of transfered students
        var datapass = "?transfered_students=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("transfered_stud2"));

        //number of alumnis students
        var datapass = "?alumnis_number=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("alumnis_number2"));
    }
    //classteacher dashboard = 5
    if (auth == 5) {
        //get total number of students in my class
        var datapass = "?number_of_me_studnets=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("studclass"));

        //get total number of students regestered today in my class
        var datapass = "?reg_today_my_class=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("reg_tod_mine"));

        //get total number of students present in school today in my class
        var datapass = "?today_attendance=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("my_att_clas"));

        //get total number of students present in school today in my class 
        var datapass = "?absent_students=true";
        sendData("GET", "administration/admissions.php", datapass, cObj("my_absent_list"));

        //my subjects
        var datapass = "?subs_lists=true";
        sendData("GET", "academic/academic.php", datapass, cObj("my_subjects"));

    }
    //the teachers` dashboard
    if (auth == 2) {
        //get the active exams
        var datapass = "?active_exams_lts=true";
        sendData("GET", "academic/academic.php", datapass, cObj("active_examination"));

        //my subjects
        var datapass = "?subs_lists=true";
        sendData("GET", "academic/academic.php", datapass, cObj("my_subjects"));

    }
}

/*******end of it********/


function showNyMenu(authoriti) {
    var datapass = "?staff_roles=true";
    sendData2("GET", "academic/academic.php", datapass, cObj("menu_data"), cObj("allow_ct_reg_clock_elect"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("allow_ct_reg_clock_elect").classList.contains("hide")) {
                var menu_data = cObj("menu_data").innerText;
                if (menu_data.length > 0) {
                    var object = JSON.parse(menu_data);
                    for (let index = 0; index < object.length; index++) {
                        const element = object[index];
                        // console.log(element.roles);
                        if (element.name == authoriti) {
                            var roles = element.roles;
                            for (let index = 0; index < roles.length; index++) {
                                const ele = roles[index];
                                cObj(ele.name).style.display = "none"
                                if (ele.Status == "yes") {
                                    cObj(ele.name).style.display = "";
                                }
                            }
                        }
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("backtostaff").onclick = function () {
    //setwindow open
    cObj("constable").classList.remove("hide");
    cObj("informationwindow").classList.add("hide");
    viewstaffavailablebtn();
}
cObj("display_my_students").onclick = function () {
    //show the students by class
    var datapassing = "?find=true" + "&classes=" + valObj("class_assigned_tr");
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datapassing, cObj("resultsbody"));
    setTimeout(() => {
        cObj("resultsbody").classList.remove("hide");
        cObj("viewinformation").classList.add("hide");
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var btns = document.getElementsByClassName("view_students");
                for (let index = 0; index < btns.length; index++) {
                    const element = btns[index];
                    setListenerBtnTab(element.id);
                }
                if (valObj("sach") == "allstuds") {
                    var obj = document.getElementsByClassName("viewclass");
                    setListenerViewbtn1(obj);
                } else {
                }
                if (cObj("search_student_tables") != undefined && cObj("search_student_tables") != null) {
                    cObj("search_student_tables").addEventListener("keyup", showStudentData);
                }
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 200);

}

function createTimetabe(id) {
    if (id == 5 || id == 6 || id == 7 || id == 8) {
        cObj("create_tt_in").classList.add("hide");
    }
}
cObj("optd").onchange = function () {
    cObj("tableinformation").innerHTML = "";
    var err = checkBlank("optd");
    if (err == 0) {
        if (valObj("optd") == "callreg") {
            cObj("moreopt").classList.remove("hide");
            cObj("moreopt2").classList.add("hide");
            cObj("register_btns").classList.remove("hide");
            cObj("moreopt3").classList.add("hide");
            //get classes
            getClasses("class_register_class", "selectclass", "");
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        if (typeof (cObj("selectclass")) != 'undefined' && cObj("selectclass") != null) {
                            cObj("selectclass").addEventListener("change", selectClass);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
            cObj("attendance_register_one_student").classList.add("hide");
        } else if (valObj("optd") == "view_attendance") {
            cObj("moreopt").classList.add("hide");
            cObj("moreopt2").classList.remove("hide");
            cObj("register_btns").classList.add("hide");
            cObj("moreopt3").classList.add("hide");
            cObj("attendance_register_one_student").classList.add("hide");
        } else if (valObj("optd") == "specific_student") {
            cObj("moreopt").classList.add("hide");
            cObj("moreopt2").classList.add("hide");
            cObj("register_btns").classList.add("hide");
            cObj("moreopt3").classList.remove("hide");
            cObj("attendance_register_one_student").classList.remove("hide");
        }
    }
}


cObj("natids").onblur = function () {
    let nationalid = this.value;
    if (nationalid.length > 0) {
        let staffid = cObj("staffid").innerText;
        let datapass = '?findnationalid=' + nationalid + '&userids=' + staffid;
        sendData("GET", "administration/admissions.php", datapass, cObj("nationalids"));
    }
}

cObj("phonenumberd").onblur = function () {
    let phonenumber = this.value;
    if (phonenumber.length > 0) {
        let staffid = cObj("staffid").innerText;
        let datapass = '?findphonenumberd=' + phonenumber + '&userids=' + staffid;
        sendData("GET", "administration/admissions.php", datapass, cObj("phoneerrord"));
    }
}

cObj("staffmail").onblur = function () {
    let emails = this.value;
    if (emails.length > 0) {
        let staffid = cObj("staffid").innerText;
        let datapass = '?findstafsemails=' + emails + '&userids=' + staffid;
        sendData("GET", "administration/admissions.php", datapass, cObj("emailstaff"));
    }
}

cObj("usererrors").onblur = function () {
    let username = this.value;
    if (username.length > 0) {
        let staffid = cObj("staffid").innerText;
        let datapass = '?findusername=' + username + '&userids=' + staffid;
        sendData("GET", "administration/admissions.php", datapass, cObj("emailstaff"));
    }
}


cObj('updatestaff').onclick = function () {
    let errors = 0;
    //check if changes are made
    let alikes = 0;
    if (staffdata.length > 0) {
        alikes += compareTwo(valObj1('dobd'), staffdata[1]);
        alikes += compareTwo(valObj1('fullnamed'), staffdata[0]);
        alikes += compareTwo(valObj1('natids'), staffdata[6]);
        alikes += compareTwo(valObj1('phonenumberd'), staffdata[3]);
        alikes += compareTwo(valObj1('addresdd'), staffdata[5]);
        // alikes+=compareTwo(valObj1('staffmail'),staffdata[12]);
        alikes += compareTwo(valObj1('tscnosd'), staffdata[7]);
        alikes += compareTwo(valObj1('usenames'), staffdata[8]);
        alikes += compareTwo(valObj1("gende"), staffdata[4]);
        alikes += compareTwo(valObj1("deleted"), staffdata[9]);
        alikes += compareTwo(valObj1("activated"), staffdata[10]);
        alikes += compareTwo(valObj1("auths"), staffdata[11]);
        if (alikes < 12) {
            errors += checkBlank("fullnamed");
            errors += checkBlank("gende");
            errors += checkBlank("dobd");
            errors += checkBlank("natids");
            errors += checkBlank("phonenumberd");
            errors += checkBlank("auths");
            errors += checkBlank("d_o_e_input");
            errors += (cObj("err_job_number_back") != null && cObj("err_job_number_back") != undefined) ? 1 : 0;
            // errors+=checkEmails("staffmail","emailstaff");
            errors += checkBlank("addresdd");
            // the new addition error check

            if (errors > 0) {
                cObj("updateerror").innerHTML = "<p style='color:red;'>Kindly check all fields with errors and rectify accordingly</p>";
            } else {
                cObj("updateerror").innerHTML = "<p style='color:red;'></p>";
                let staffid = cObj("staffid").innerText;
                let datapassing = '?updatestaff=true&fullnames=' + valObj1('fullnamed') + '&dob=' + valObj1('dobd') + '&natids=' + valObj1('natids') + '&phonenumber=' + valObj1('phonenumberd') + '&address=' + valObj1('addresdd');
                datapassing += '&emails=' + valObj1('staffmail') + '&tscno=' + valObj1('tscnosd') + '&username=' + valObj1('usenames') + '&genders=' + valObj1('gende') + '&activated=' + valObj1('activated') + '&authorities=' + valObj1('auths') + '&staffid=' + staffid + '&deleted=' + valObj1("deleted");
                datapassing += "&nssf_numbers=" + valObj1("nssf_numbers") + "&nhif_numbers=" + valObj1("nhif_numbers") + "&d_o_e_input=" + valObj("d_o_e_input");
                datapassing += "&job_number=" + valObj("employees_job_number") + "&job_title=" + valObj("employees_job_title") + "&employees_type=" + valObj("employees_type") + "";
                datapassing += "&kin_fullnames=" + valObj("kin_fullnames") + "&kin_relationship_edit=" + valObj("kin_relationship_edit") + "&kin_contacts_edit=" + valObj("kin_contacts_edit") + "&kin_location_edit=" + valObj("kin_location_edit")+"&reason_inactive="+valObj("reason_inactive");
                sendData1('GET', "administration/admissions.php", datapassing, cObj('updateerror'));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            setTimeout(() => {
                                cObj("updateerror").innerText = "";
                            }, 3000);
                            //removePleasewait();
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            }
        }
    }
}

cObj("delete_staff_permanently").onclick = function () {
    cObj("staff_name_del").innerText = cObj("fullnamed").value;
    cObj("delete_staff_perm").classList.remove("hide");
}
cObj("no_delete_permanently").onclick = function () {
    cObj("delete_staff_perm").classList.add("hide");
}
cObj("yes_delete_permanently").onclick = function () {
    var datapass = "?delete_staff=true&staff_ids=" + cObj("staffid").innerText;
    sendData1('GET', "administration/admissions.php", datapass, cObj('updateerror'));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("delete_staff_perm").classList.add("hide");
                cObj("back_one").click();
                setTimeout(() => {
                    cObj("updateerror").innerText = "";
                }, 4000);
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("change_admissions_prefix").onclick = function () {
    // get the admission number prefix
    var datapass = "?get_admission_prefix=true";
    sendData2("GET","administration/admissions.php",datapass,cObj("admission_numbers_prefix_value"),cObj("set_admission_number_prefix_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("set_admission_number_prefix_loader").classList.contains("hide")) {
                var admission_value = cObj("admission_numbers_prefix_value").innerText;
                cObj("admission_number_prefix").value = admission_value.length > 0 ? admission_value : "Not-Set";
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    cObj("set_admission_number_prefix").classList.remove("hide");
}

cObj("confirm_set_admission_number").onclick = function () {
    // var err = checkBlank("admission_number_prefix");
    var err = 0;
    if (err == 0) {
        err = valObj("admission_number_prefix") == "Not-Set" ? 1 : 0;
        if (err == 0) {
            // gray border
            grayBorder(cObj("admission_number_prefix"));

            // save the admission number
            var datapass = "?save_admission_prefix=true&admission_prefix="+valObj("admission_number_prefix");
            sendData2("GET","administration/admissions.php",datapass,cObj("admission_number_prefix_error"),cObj("set_admission_number_prefix_loader"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("set_admission_number_prefix_loader").classList.contains("hide")) {
                        cObj("cancel_set_admission_number").click();
                        get_admission_prefix();
                        setTimeout(() => {
                            cObj("admission_number_prefix_error").innerHTML = "";
                        }, 3000);
                        //removePleasewait();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            redBorder(cObj("admission_number_prefix"));
        }
    }
}

cObj("cancel_set_admission_number").onclick = function () {
    cObj("set_admission_number_prefix").classList.add("hide");
}

function get_admission_prefix() {
    var datapass = "?get_admission_prefix_details=true";
    sendData2("GET","administration/admissions.php",datapass,cObj("admission_number_prefix_window"),cObj("admission_number_prefix_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("admission_number_prefix_loader").classList.contains("hide")) {
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function viewstaffavailablebtn() {
    cObj("informationwindow").classList.remove("hide");
    cObj("subject_and_teacher").classList.add("hide");
    //setwindow open
    cObj("constable").classList.remove("hide");
    cObj("informationwindow").classList.add("hide");
    var datastring = "?getavalablestaff=true";
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datastring, cObj("stafferrors"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var collectbtn = document.getElementsByClassName('viewtr');
                for (let index = 0; index < collectbtn.length; index++) {
                    const element = collectbtn[index];
                    setListenertblstaff(element.id);
                }
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function setListenertblstaff(id) {
    cObj(id).addEventListener('click', clicks);
}
//check if phone number , email,tscnumber and national id entered are present in the database
//cObj("").onblur = function () {
//    var value = this.value;
//    var idval = cObj("").innerText;
//}
function clicks() {
    var datapass = "?staffdata=" + this.id;
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datapass, cObj("errorsviewing"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var data = cObj("errorsviewing").innerText;
                //split the data
                if (hasJsonStructure(data)) {
                    data = JSON.parse(data);
                    var splitdata = data;
                    staffdata = splitdata;
                    cObj('dobd').value = splitdata[1];
                    // cObj(splitdata[4]).selected = true;
                    // set gender
                    var genders = document.getElementsByClassName("genders");
                    for (let index = 0; index < genders.length; index++) {
                        const element = genders[index];
                        if (element.value == splitdata[4]) {
                            element.selected = true;
                        }
                    }
                    setDatalen('fullnamed', splitdata[0]);
                    setDatalen('natids', splitdata[6]);
                    setDatalen('phonenumberd', splitdata[3]);
                    setDatalen('addresdd', splitdata[5]);
                    setDatalen('staffmail', splitdata[12]);
                    setDatalen('tscnosd', splitdata[7]);
                    setDatalen('usenames', splitdata[8]);
                    setDatalen('nssf_numbers', splitdata[14]);
                    setDatalen('nhif_numbers', splitdata[15]);
                    cObj('d_o_e_input').value = splitdata[16];
                    cObj('employees_job_number').value = splitdata[17];
                    cObj('employees_job_title').value = splitdata[18];
                    cObj("reason_inactive").value = splitdata[24];

                    var employees_type = splitdata[19];
                    var emp_infor_opt = document.getElementsByClassName("emp_infor_opt");
                    for (let index = 0; index < emp_infor_opt.length; index++) {
                        const element = emp_infor_opt[index];
                        // console.log(element.value+" == "+employees_type);
                        if (element.value == employees_type) {
                            element.selected = true;
                            break;
                        }
                    }

                    // staff role assignment
                    var auth = splitdata[11];
                    var data = "";
                    if (auth == 0) {
                        data += "System Administrator";
                    } else if (auth == "1") {
                        data += "Principal";
                    } else if (auth == "2") {
                        data += "Deputy Principal Academics";
                    } else if (auth == "3") {
                        data += "Deputy Principal Administration";
                    } else if (auth == "4") {
                        data += "Dean of Students";
                    } else if (auth == "5") {
                        data += "Finance Office";
                    } else if (auth == "6") {
                        data += "Human Resource Officer";
                    } else if (auth == "7") {
                        data += "Head of Department";
                    } else if (auth == "8") {
                        data += "Trainer/Lecturer";
                    } else if (auth == "9") {
                        data += "Admissions";
                    } else {
                        data += auth;
                    }
                    cObj("myauthorities").innerHTML = "<span style='color:blue;'>{" + data + "}</span>";

                    // set the value of the role
                    if(cObj("auths") != undefined){
                        var authority_children = cObj("auths").children;
                        for (let index = 0; index < authority_children.length; index++) {
                            const element = authority_children[index];
                            // console.log(element.value +" "+ auth);
                            if (element.value == auth) {
                                element.selected = true;
                                // console.log(element.value +" "+ auth+" find");
                                break;
                            }
                        }
                    }
                    // end of staff role assignment


                    cObj("del" + splitdata[9] + "").selected = true;
                    cObj("act" + splitdata[10] + "").selected = true;
                    if (splitdata[10] == 0) {
                        cObj("reason_for_staff_inactive").classList.remove("hide");
                    }else{
                        cObj("reason_for_staff_inactive").classList.add("hide");
                    }
                    cObj("staffid").innerText = splitdata[13];
                    // cObj("auths"+splitdata[11]).selected = true;

                    cObj("kin_fullnames").value = splitdata[20];
                    cObj("kin_relationship_edit").value = splitdata[22];
                    cObj("kin_contacts_edit").value = splitdata[21];
                    cObj("kin_location_edit").value = splitdata[23];

                    //setwindow open
                    cObj("constable").classList.add("hide");
                    cObj("informationwindow").classList.remove("hide");
                    //removePleasewait();
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);

}

cObj("employees_job_number").onkeyup = function () {
    // console.log();
    if (this.value.length > 0) {
        var datapass = "?job_number_checker=" + this.value + "&employee_id=" + cObj("staffid").innerText;
        sendData2("GET", "administration/admissions.php", datapass, cObj("error_job_number"), cObj("job_number_loader"));
    }
}

cObj("registerstaff").onclick = function () {
    var errs = checkerrorsstaf();
    if (cObj("err_hand_check_uname").innerText.length > 0) {
        errs++;
        alert("Please use another username, the one given is already used!");
    }
    if (errs == 0) {
        var data = "";
        cObj("errors").innerHTML = "<p style='color:red;font-size:14px;'></p>";
        if (valObj("pword") == valObj("pword2")) {
            var datapassing = "?insertstaff=true&fullnames=" + valObj("fullnames") + "&dobos=" + valObj("dobo") + "&genders=" + valObj("gen") + "&phonenumbers=" + valObj("phonenumber") + "&address=" + valObj("adress");
            datapassing += "&idnumber=" + valObj("poridnumber") + "&authority=" + valObj("authority") + "&username=" + valObj("username") + "&password=" + valObj("pword") + "&tscnumber=" + valObj("tscno") + "&emails=" + valObj("staffemail");
            datapassing += "&nhif_number=" + valObj("nhif_number") + "&nssf_number=" + valObj("nssf_number");
            datapassing += "&kin_fullname=" + valObj("kin_fullname") + "&kin_relation=" + valObj("kin_relation") + "&kin_contacts=" + valObj("kin_contacts") + "&kin_location=" + valObj("kin_location");
            sendData1("GET", "administration/admissions.php", datapassing, cObj("errors"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        let answer = cObj("errors").innerText.substr(0, 12);
                        if (answer == "Registration") {
                            cObj("staffdatas").reset();
                        }

                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        } else {
            cObj("errors").innerHTML = "<p style='text-align:center;color:red;font-size:14px;'>Passwords don`t match!</p>";
        }

    } else {
        cObj("errors").innerHTML = "<p style='text-align:center;color:red;font-size:14px;'>Please fill all the field marked with red border</p>";
    }
}

cObj("resetstaffdatas").onclick = function () {
    cObj("staffdatas").reset();
}

cObj("username").onblur = function () {
    if (this.value.length > 0) {
        var datapass = "?usernames_value=" + this.value;
        sendData2("GET", "administration/admissions.php", datapass, cObj("err_hand_check_uname"), cObj("check_usernames_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("check_usernames_clock").classList.contains("hide")) {
                    if (cObj("err_hand_check_uname").innerText.length > 0) {
                        redBorder(this);
                    } else {
                        grayBorder(this);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

function checkerrorsstaf() {
    let errors = 0;
    errors += checkBlank("fullnames");
    errors += checkBlank("dobo");
    errors += checkBlank("gen");
    errors += checkPhone("phonenumber", "phonehandler");
    errors += checkBlank("adress");
    errors += checkBlank("poridnumber");
    errors += checkBlank("authority");
    errors += checkBlank("username");
    errors += checkBlank("pword");
    errors += checkBlank("pword2");
    // errors += checkBlank("kin_fullname");
    // errors += checkBlank("kin_relation");
    // errors += checkBlank("kin_contacts");
    // errors += checkBlank("kin_location");
    errors += checkEmails("staffemail", "emailhandler");
    return errors;

}
cObj("phonenumber").onblur = function () {
    let phone = this.value;
    if (this.value.length > 0) {
        let datapas = "?findphone=" + phone;
        sendData("GET", "administration/admissions.php", datapas, cObj("phonehandler"));
    } else {
        cObj("phonehandler").innerHTML = "<p></p>";
    }
}
cObj("poridnumber").onblur = function () {
    let id = this.value;
    if (this.value.length > 0) {
        let datapas = "?findidpass=" + id;
        sendData("GET", "administration/admissions.php", datapas, cObj("idpasshandler"));
    } else {
        cObj("idpasshandler").innerHTML = "<p></p>";
    }
}
cObj("tscno").onblur = function () {
    let tscnod = this.value;
    if (this.value.length > 0) {
        let datapas = "?findtscno=" + tscnod;
        sendData("GET", "administration/admissions.php", datapas, cObj("tschandler"));
    } else {
        cObj("tschandler").innerHTML = "<p></p>";
    }
}

cObj("staffemail").onblur = function () {
    let emails = this.value;
    if (this.value.length > 0) {
        let datapas = "?findemail=" + emails;
        sendData("GET", "administration/admissions.php", datapas, cObj("emailhandler"));
    } else {
        cObj("emailhandler").innerHTML = "<p></p>";
    }
}


cObj("sach").onchange = function () {
    if (this.value == "name") {
        cObj("swindow").classList.remove("hide");
        cObj("named").classList.remove("hide");
        cObj("admnosd").classList.add("hide");
        cObj("classenroll").classList.add("hide");
        cObj("bcnos").classList.add("hide");
        cObj("course_lists_search_bar").classList.add("hide");
    } else if (this.value == "AdmNo") {
        cObj("swindow").classList.remove("hide");
        cObj("named").classList.add("hide");
        cObj("admnosd").classList.remove("hide");
        cObj("classenroll").classList.add("hide");
        cObj("course_lists_search_bar").classList.add("hide");
        cObj("bcnos").classList.add("hide");
    } else if (this.value == "class") {
        cObj("swindow").classList.remove("hide");
        cObj("named").classList.add("hide");
        cObj("admnosd").classList.add("hide");
        cObj("classenroll").classList.remove("hide");
        cObj("course_lists_search_bar").classList.remove("hide");
        cObj("bcnos").classList.add("hide");
    } else if (this.value == "bcno") {
        cObj("swindow").classList.remove("hide");
        cObj("named").classList.add("hide");
        cObj("admnosd").classList.add("hide");
        cObj("classenroll").classList.add("hide");
        cObj("course_lists_search_bar").classList.add("hide");
        cObj("bcnos").classList.remove("hide");
    } else if (this.value == "allstuds") {
        cObj("course_lists_search_bar").classList.add("hide");
        cObj("swindow").classList.add("hide");
        cObj("bcnos").classList.add("hide");
    } else if (this.value == "regtoday") {
        cObj("course_lists_search_bar").classList.add("hide");
        cObj("swindow").classList.add("hide");
        cObj("bcnos").classList.add("hide");
    }
}

cObj("findingstudents").onclick = function () {
    cObj("resultsbody").classList.remove("hide");
    cObj("viewinformation").classList.add("hide");
    if (valObj("sach").length > 0) {
        grayBorder(cObj("sach"));
        cObj("errorSearch").innerHTML = "<p style='color:red;font-size:14px'></p>";
        var datapassing = "?find=true";

        var erroro = 0;
        if (valObj("sach") == "name") {
            if (valObj("name").length > 0) {
                grayBorder(cObj("name"));
                datapassing += "&comname=" + valObj("name");
            } else {
                redBorder(cObj("name"));
                erroro++;
            }
        } else if (valObj("sach") == "AdmNo") {
            if (valObj("admno").length > 0) {
                grayBorder(cObj("admno"));
                datapassing += "&comadm=" + valObj("admno");
            } else {
                redBorder(cObj("admno"));
                erroro++;
            }
        } else if (valObj("sach") == "class") {
            if (cObj("selclass") != null) {
                if (valObj("selclass").length > 0) {
                    grayBorder(cObj("selclass"));
                    datapassing += "&classes=" + valObj("selclass");
                } else {
                    redBorder(cObj("selclass"));
                    erroro++;
                }
            } else {
                erroro++;
            }
            
            // get course_list
            if (cObj("course_chosen_search") != null) {
                grayBorder(cObj("course_chosen_search"));
                datapassing += "&course_chosen=" + valObj("course_chosen_search");
            } else {
                erroro++;
            }
        } else if (valObj("sach") == "bcno") {
            if (valObj("bcnosd") > 0) {
                grayBorder(cObj("bcnosd"));
                datapassing += "&combcno=" + valObj("bcnosd");
            } else {
                redBorder(cObj("bcnosd"));
                erroro++;
            }
        } else if (valObj("sach") == "allstuds") {
            datapassing += "&allstudents=true";
        }
        else if (valObj("sach") == "regtoday") {
            datapassing += "&todayreg=true";
        }
        if (erroro == 0) {
            //showPleasewait();
            // console.log("No error!");
            sendData1("GET", "administration/admissions.php", datapassing, cObj("resultsbody"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        var btns = document.getElementsByClassName("view_students");
                        for (let index = 0; index < btns.length; index++) {
                            const element = btns[index];
                            setListenerBtnTab(element.id);
                        }
                        if (valObj("sach") == "allstuds") {
                            var obj = document.getElementsByClassName("viewclass");
                            setListenerViewbtn1(obj);
                        } else {
                        }
                        if (cObj("search_student_tables") != undefined && cObj("search_student_tables") != null) {
                            cObj("search_student_tables").addEventListener("keyup", showStudentData);
                        }
                        //removePleasewait();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    } else {
        redBorder(cObj("sach"));
        cObj("errorSearch").innerHTML = "<p style='color:red;font-size:14px'>Select an option to proceed!</p>";
    }
}

function showStudentData() {
    var this_value = this.value.toLowerCase();
    if (this_value.length > 0) {
        var search_this_main = document.getElementsByClassName("search_this_main");
        for (let index = 0; index < search_this_main.length; index++) {
            const element = search_this_main[index];
            element.classList.add("hide");
        }

        var search_this = document.getElementsByClassName("search_this");
        var indexed = [];
        for (let index = 0; index < search_this.length; index++) {
            const element = search_this[index];
            // console.log(element.innerText.toLowerCase().includes(this_value)+" "+element.innerText.toLowerCase() + " "+this_value);
            if (element.innerText.toLowerCase().includes(this_value)) {
                var this_index = element.id.substring(3);
                if (isPresent(indexed, this_index) == false) {
                    indexed.push(this_index);
                }
            }
        }
        // console.log(indexed);

        // show the rows that are present
        for (let index = 0; index < indexed.length; index++) {
            const element = indexed[index];
            cObj("search_this_main" + element).classList.remove("hide");
        }
    } else {
        var search_this_main = document.getElementsByClassName("search_this_main");
        for (let index = 0; index < search_this_main.length; index++) {
            const element = search_this_main[index];
            element.classList.remove("hide");
        }
    }
}

function setListenerViewbtn1(ids) {
    for (let index = 0; index < ids.length; index++) {
        const element = ids[index];
        element.addEventListener('click', viewlisteners);
    }
}

cObj("student_image").onclick = function () {
    cObj("image_viewer").src = cObj("student_image").src;
    cObj("imagers").classList.add("image_view");
    cObj("imagers").classList.remove("hide");
}

cObj("change_student_profile_image").onclick = function () {
    cObj("change_studes_dp_win").classList.remove("hide");
}
cObj("close_studes_change_dp").onclick = function () {
    cObj("change_studes_dp_win").classList.add("hide");
}

function viewlisteners() {
    var ids = this.id;
    var datapassing = "?find=true";
    datapassing += "&classes=" + ids;
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datapassing, cObj("resultsbody")); setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var btns = document.getElementsByClassName("view_students");
                for (let index = 0; index < btns.length; index++) {
                    const element = btns[index];
                    setListenerBtnTab(element.id);
                }
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("name").onkeyup = function () {
    var name = this.value;
    cObj("resultsbody").classList.remove("hide");
    cObj("viewinformation").classList.add("hide");
    if (name.length > 0) {
        //query the server
        var datapass = "?find=true&bynametype=" + name;
        sendData2("GET", "administration/admissions.php", datapass, cObj("resultsbody"), cObj("names_loaders_find"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("names_loaders_find").classList.contains("hide")) {
                    var btns = document.getElementsByClassName("view_students");
                    for (let index = 0; index < btns.length; index++) {
                        const element = btns[index];
                        setListenerBtnTab(element.id);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("viewpresent").onclick = function () {
    if (typeof (cObj("selectclass")) != 'undefined' && cObj("selectclass") != null) {
        var err = checkBlank("selectclass");
        if (err == 0) {
            //if(valObj("selectclass").length>0)
            var daro = valObj("selectclass");
            var datapass = "?class=" + daro + "&dates=today";
            //showPleasewait();
            sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //removePleasewait();
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
            cObj("view_attendances").classList.remove("hide");
            cObj("mains").classList.add("hide");
        }
    } else if (valObj("classselected") != "0") {
        var daro = valObj("classselected");
        var datapass = "?class=" + daro + "&dates=today";
        //showPleasewait();
        sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //removePleasewait();
                    stopInterval(ids);
                }
            }, 100);
        }, 500);
        cObj("view_attendances").classList.remove("hide");
        cObj("mains").classList.add("hide");
    } else {
        alert("Select a class to proceed!")
    }
}
cObj("show_class_att").onclick = function () {
    if (valObj("classselected") != "0") {
        cObj("register_btns").classList.remove("hide");
        var classed = valObj("classselected");
        var date_used = valObj("class_register_dates_cltr");
        if (valObj("classselected").length > 0) {
            var datapass = "?getclassinformation=true&daro=" + classed + "&date_used=" + date_used;
            //showPleasewait();
            sendData1("GET", "administration/admissions.php", datapass, cObj("tableinformation"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //removePleasewait();
                        if (cObj("present_all") != undefined) {
                            cObj("present_all").addEventListener("change", selectAllPresent);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
        }
    }
}

function selectAllPresent() {
    var present = document.getElementsByClassName("present");
    if (this.checked == true) {
        for (let index = 0; index < present.length; index++) {
            const element = present[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < present.length; index++) {
            const element = present[index];
            element.checked = false;
        }
    }
}
cObj("manage_tr_option").onchange = function () {
    if (this.value == "viewstaffavailable") {
        viewstaffavailablebtn();
    } else if (this.value == "assignclasses") {
        assignsubjectsbtn();
    }
}

cObj("display_attendance").onclick = function () {
    var err = checkBlank("date_selected");
    if (err == 0) {
        var date = cObj("date_selected").value;
        //
        if (typeof (cObj("classselected")) != 'undefined' && cObj("classselected") != null) {
            if (valObj("classselected").length > 0) {
                var daro = valObj("classselected");
                var datapass = "?class=" + daro + "&dates=" + date;
                //showPleasewait();
                sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            //removePleasewait();
                            stopInterval(ids);
                        }
                    }, 100);
                }, 500);
                cObj("view_attendances").classList.remove("hide");
                cObj("mains").classList.add("hide");
            }
            console.log(1);
        } else if (valObj("classselected") != "0") {
            var daro = valObj("classselected");
            var daro = valObj("hidden_class_selected");
            var datapass = "?class=" + daro + "&dates=" + date;
            //showPleasewait();
            sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //removePleasewait();
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
            cObj("view_attendances").classList.remove("hide");
            cObj("mains").classList.add("hide");
            console.log(2);
        } else {
            var daro = valObj("hidden_class_selected");
            var datapass = "?class=" + daro + "&dates=" + date;
            //showPleasewait();
            sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //removePleasewait();
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
            cObj("view_attendances").classList.remove("hide");
            cObj("mains").classList.add("hide");
            console.log(3);
        }
    }
}

cObj("backtosearch").onclick = function () {
    cObj("view_attendances").classList.add("hide");
    cObj("mains").classList.remove("hide");
}
cObj("display_student_attendances").onclick = function () {
    selectClass();
}
function selectClass() {
    var classed = cObj("selectclass").value;
    var date_used = cObj("class_register_dates").value;
    if (cObj("selectclass").value.length > 0 && date_used.length > 0) {
        cObj("register_btns").classList.remove("hide");
        var datapass = "?getclassinformation=true&daro=" + classed + "&date_used=" + date_used;
        //showPleasewait();
        sendData1("GET", "administration/admissions.php", datapass, cObj("tableinformation"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    //removePleasewait();
                    cObj("present_all").addEventListener("change", selectAllPresent);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

var classreg = "";
cObj("submitclasspresent").onclick = function () {
    var studpresnt = document.getElementsByClassName("present");
    var ids = "";
    let count = 0;
    for (let index = 0; index < studpresnt.length; index++) {
        const element = studpresnt[index];
        if (element.checked) {
            ids += element.id + ",";
            count++;
        }
    }
    if (count > 0) {
        var auth = cObj("authoriti").value;
        if (auth == 5) {
            var idcollection = ids.substr(0, ids.length - 1).split(",");
            var name = cObj("myname").value;
            var daros = valObj("classselected");
            name += "," + daros + "," + idcollection;
            var datapas = "?insertattendance=" + name + "&calldate=" + cObj("class_register_dates_cltr").value;
            cObj('message').innerHTML = "<p style='font-size:12px;'>Are you sure you want to submit attendance for " + classNameAdms(daros) + " on <b class='text-success'>" + cObj("class_register_dates_cltr").value + "</b>?</p>";
            classreg = datapas;
            cObj("dialogholder1").classList.remove("hide");
        } else {
            var idcollection = ids.substr(0, ids.length - 1).split(",");
            var name = cObj("myname").value;
            var daros = valObj("selectclass");
            name += "," + daros + "," + idcollection;
            var datapas = "?insertattendance=" + name + "&calldate=" + cObj("class_register_dates").value;
            cObj('message').innerHTML = "<p style='font-size:12px;'>Are you sure you want to submit attendance for " + classNameAdms(cObj("selectclass").value) + " on <b class='text-success'>" + cObj("class_register_dates").value + "</b>?</p>";
            classreg = datapas;
            cObj("dialogholder1").classList.remove("hide");
        }

    }
}
cObj("clasregyes").onclick = function () {
    sendData1("GET", "administration/admissions.php", classreg, cObj("tablein"));
    cObj("dialogholder1").classList.add("hide");
}
cObj("clasregno").onclick = function () {
    cObj("dialogholder1").classList.add("hide");
    //sendData1("GET","administration/admissions.php",datapas,cObj("tablein"));
}

cObj("bcnosd").onkeyup = function () {
    var bcn = this.value;
    if (bcn.length > 0) {
        var datapass = "?find=true&bybcntype=" + bcn;
        sendData("GET", "administration/admissions.php", datapass, cObj("resultsbody"));
        setTimeout(() => {
            var btns = document.getElementsByClassName("view_students");
            for (let index = 0; index < btns.length; index++) {
                const element = btns[index];
                setListenerBtnTab(element.id);
            }
        }, 2000);
    }
}

function setListenerBtnTab(id) {
    cObj(id).addEventListener("click", tablebtnlistener);
}
function tablebtnlistener() {
    cObj("course_chosen_error_window").innerHTML = "";
    cObj("course_level_error_window").innerHTML = "";
    // remove the course level window
    cObj("course_level_error_window").innerHTML = "";
    cObj("select_clubs_sports_def").selected = true;
    var admno = this.id.substr(4);
    //send the id to the database.
    let datapass = "?find=true&usingadmno=" + admno;
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datapass, cObj("studentinformation"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var infor = cObj("studentinformation").innerText;
                var json_data = hasJsonStructure(infor) ? JSON.parse(infor) : [];
                var splitdata = json_data;
                studentinformation = splitdata;
                //check if he is the administrator
                var auth = cObj("authoriti").value;
                // if(auth == 1){
                if (splitdata.length > 10) {
                    cObj("intake_year_edit").selectedIndex = 0;
                    cObj("intake_month_edit").selectedIndex = 0;
                    cObj("loadings").classList.remove("hide");
                    cObj("snamed_in").value = splitdata[0]
                    cObj("fnamed_in").value = splitdata[1]
                    cObj("lnamed_in").value = splitdata[2]
                    if (splitdata[6] == "-2") {
                        cObj("reason_for_leaving_window").classList.remove("hide");
                    } else {
                        cObj("reason_for_leaving_window").classList.add("hide");
                    }
                    
                    // get the children for the select and select the children that has that value
                    var classed = cObj("classed") != undefined ? cObj("classed").children : [];
                    for (let index = 0; index < classed.length; index++) {
                        const element = classed[index];
                        if(element.value == splitdata[6]){
                            element.selected = true;
                        }
                    }

                    // course level and course
                    cObj("course_level_hidden").value = splitdata[6];
                    cObj("course_chosen_level_hidden").value = splitdata[41];

                    // cObj("cl" + splitdata[6]) != undefined ? cObj("cl" + splitdata[6]).selected = true : "";
                    cObj("adminnos").value = splitdata[7]
                    if (splitdata[3] == 0) {
                        cObj("indexnos").value = "N/A";
                    } else {
                        cObj("indexnos").value = splitdata[3];
                    }

                    if (splitdata[14] == 0) {
                        cObj("bcnno").value = 'N/A';
                    } else {
                        cObj("bcnno").value = splitdata[14]
                    }

                    cObj("dobs").value = splitdata[4]
                    cObj("doas").value = splitdata[8]
                    if (splitdata[5].length > 0) {
                        cObj(splitdata[5]).selected = true
                    }
                    if (splitdata[15].length > 0) {
                        cObj(splitdata[15]).selected = true
                    }
                    cObj("descriptionsd").value = splitdata[20];
                    cObj("addressed").value = splitdata[13];
                    cObj("pnamed").value = splitdata[9];
                    cObj("pcontacted").value = splitdata[10];
                    cObj("paddressed").value = splitdata[13];
                    cObj("pemails").value = splitdata[12];
                    cObj("parrelationship").value = splitdata[11];
                    cObj("resultsbody").classList.add("hide");
                    cObj("viewinformation").classList.remove("hide");
                    cObj("loadings").classList.add("hide");
                    cObj("updateerrors").innerHTML = "";
                    // console.log(splitdata);
                    cObj("paroccupation1").value = splitdata[26];
                    cObj("paroccupation2").value = splitdata[27];
                    cObj("medical_histry").value = splitdata[23];
                    
                    cObj("pnamed2").value = splitdata[16];
                    cObj("pcontacted2").value = splitdata[17];
                    cObj("pemails2").value = splitdata[19];
                    cObj("parrelationship2").value = splitdata[18];

                    var datainside = splitdata[22].trim().split(",");
                    var admission_essentialed = "<ol>";
                    var counting = 0;
                    for (let ind = 0; ind < datainside.length; ind++) {
                        const element = datainside[ind];
                        admission_essentialed += "<li>" + element + "</li>";
                        counting++;
                    }
                    admission_essentialed += "</ol>";
                    // console.log(datainside);
                    if (splitdata[22].trim().length > 1) {
                        cObj("admissionessentials_lists").innerHTML = admission_essentialed;
                    } else {
                        cObj("admissionessentials_lists").innerHTML = "No admission essentials";
                    }

                    // previous schools attended
                    var prev_schools = hasJsonStructure(splitdata[24]) ? JSON.parse(splitdata[24]) : [];
                    // console.log(prev_schools);
                    cObj("previous_school_json").innerText = splitdata[24];
                    var counters = 0;
                    var previous_schools = "<table class='table'><tr><th>No</th><th>School Name</th><th>Date Left</th><th>Marks Scored</th><th>Reason For Leaving</th><th>Leaving Certificate</th><th>Actions</th></tr>";
                    for (let indexes = 0; indexes < prev_schools.length; indexes++) {
                        counters++;
                        const element = prev_schools[indexes];
                        previous_schools += "<tr><td>" + (indexes + 1) + "</td><td>" + element.school_name + "</td><td>" + element.date_left + "</td><td>" + element.marks_scored + "</td><td>" + element.reason_for_leaving + "</td><td>" + (element.leaving_cert == "true" ? "Submitted" : "Not Submitted") + "</td><td><span class='link rm_prev_sch' id='rm_prev_sch" + indexes + "'><i class='fas fa-trash'></i> Remove</span></td></tr>";
                    }
                    previous_schools += "</table>";
                    if (counters > 0) {
                        cObj("prev_sch_list").innerHTML = previous_schools + "<br><p class='block_btn' id='edit_prev_school_btn'>Add Previous Schools</p>";
                        cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
                        // remove 
                        var rm_prev_sch = document.getElementsByClassName("rm_prev_sch");
                        for (let ind = 0; ind < rm_prev_sch.length; ind++) {
                            const element = rm_prev_sch[ind];
                            element.addEventListener("click", remove_school);
                        }
                    } else {
                        cObj("prev_sch_list").innerHTML = "<p class='block_btn'  id='edit_prev_school_btn'>Add Previous Schools</p><br><p class='text-danger'>No previous schools attended by the student has been recorded</p>";
                        cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
                    }

                    // fees summary
                    cObj("current_term").innerHTML = splitdata[33];
                    cObj("current_term2").innerHTML = splitdata[33];
                    cObj("total_amount_to_pay").innerHTML = splitdata[30];
                    cObj("lastyr_fees_balance").innerHTML = splitdata[29];
                    cObj("fees_paid_this_term").innerHTML = splitdata[28];
                    cObj("fees_balances").innerHTML = splitdata[31];
                    cObj("total_paid_fees").innerHTML = splitdata[32];
                    cObj("transport_enrolled_std_infor").innerHTML = splitdata[34];
                    cObj("board_enrolled_std_infor").innerHTML = splitdata[35];

                    var clubs_in_sporter = document.getElementsByClassName("clubs_in_sporter");
                    // console.log(splitdata);
                    for (let index = 0; index < clubs_in_sporter.length; index++) {
                        const element = clubs_in_sporter[index];
                        if (element.value == splitdata[36]) {
                            element.selected = true;
                        }
                    }
                    // set the boarding data 
                    if (splitdata[21] != "enrolled" && splitdata[21] != "enroll") {
                        // the user has not been enrolled in any dormitory
                        cObj("boarding_status").innerHTML = "<span style='background-color: orange; color:white;' class='rounded p-1 '>Not-enrolled</span> || <span id='enroll_stud_boarding' class='link'>Click me to Enroll</span>";
                        // set the listener
                        cObj("enroll_stud_boarding").addEventListener("click", clickEnroll);
                    } else {
                        cObj("boarding_status").innerHTML = "<span style='background-color: green; color:white;' class='rounded p-1 '>Enrolled</span> || <span id='unenroll_stud_boarding' class='link' >CLick me to Un-Enroll ?</span>";
                        cObj("unenroll_stud_boarding").addEventListener("click", clickUnEnroll);
                    }
                    cObj("attendance_this_term").innerHTML = splitdata[37];
                    cObj("attendance_this_year").innerHTML = splitdata[38];
                    cObj("reason_for_leaving_desc").value = splitdata[39];
                    cObj("fees_discount").innerHTML = splitdata[40];
                    getDP();

                    // get the course being done by the students
                    // store the course id
                    let course_id = splitdata[41];

                    // get the courses to be done based on the level of education of the student
                    var datapass = "?getCoursesEdit=true&course_level="+splitdata[6]+"&course_id="+course_id;
                    sendData2("GET","administration/admissions.php",datapass,cObj("course_list_edit"),cObj("course_list_edit_loader"));
                    setTimeout(() => {
                        var timeout = 0;
                        var ids = setInterval(() => {
                            timeout++;
                            //after two minutes of slow connection the next process wont be executed
                            if (timeout == 1200) {
                                stopInterval(ids);
                            }
                            if (cObj("course_list_edit_loader").classList.contains("hide")) {
                                cObj("course_chosen_edit").addEventListener("change",courseChange);
                                // stop
                                stopInterval(ids);
                            }
                        }, 100);
                    }, 100);

                    // proceed and make the table that will show the course history of the kid.
                    let course_history = splitdata[42];

                    // course history is null
                    if(course_history != null){
                        // display that in the table.
                        var data_to_display = "<h4 class='text-center'>Course Progress <input id='course_level_value' hidden value='"+JSON.stringify(splitdata[43])+"'></h4><table class='table'><tr><th>Course Level</th><th>Course Name</th><th>Module Terms</th><th>Status</th><th>period</th></tr>";
                        data_to_display+="<tr><td rowspan='"+course_history.module_terms.length+"' style='vertical-align: middle;'><b>"+course_history.course_level_name+"</b></td><td rowspan='"+course_history.module_terms.length+"' style='vertical-align: middle;'><b>"+course_history.course_name+"</b></td><td>"+course_history.module_terms[0].term_name+"</td><td><label class='form-control-label' for='checked1' >Status : </label> <select class='form-control select_options' id='select_option_0'><option value='' hidden>Select Option</option><option value='0' "+(course_history.module_terms[0].status == 0 ? "selected" : "")+">In-Active</option><option "+(course_history.module_terms[0].status == 1 ? "selected" : "")+" value='1'>Active</option><option "+(course_history.module_terms[0].status == 2 ? "selected" : "")+" value='2'>Completed</option></select></td><td>"+(course_history.module_terms[0].start_date.length > 0 ? "Start Date : "+"<b>"+formatDate_1(course_history.module_terms[0].start_date)+"</b>"+"<br>End Date : "+"<b>"+formatDate_1(course_history.module_terms[0].end_date)+"</b>" : "In-Active") +"</td></tr>";

                        // loop through the data
                        for (let index = 1; index < course_history.module_terms.length; index++) {
                            const element = course_history.module_terms[index];
                            let delete_button = (index == (course_history.module_terms.length-1)) ? "<span class='btn btn-sm btn-outline-danger' id='delete_course_term_module'><i class='fa fa-trash'></i> Del</span>" : "";
                            data_to_display+="<tr><td>"+course_history.module_terms[index].term_name+"<br>"+delete_button+"</td><td><label class='form-control-label' for='select_option_1' >Status : </label> <select class='form-control select_options' id='select_option_"+index+"'><option value='' hidden>Select Option</option><option value='0' "+(course_history.module_terms[index].status == 0 ? "selected" : "")+">In-Active</option><option "+(course_history.module_terms[index].status == 1 ? "selected" : "")+" value='1'>Active</option><option "+(course_history.module_terms[index].status == 2 ? "selected" : "")+" value='2'>Completed</option></select></td><td>"+(course_history.module_terms[index].start_date.length > 0 ? "Start Date : "+"<b>"+formatDate_1(course_history.module_terms[index].start_date)+"</b>"+"<br>End Date : "+"<b>"+formatDate_1(course_history.module_terms[index].end_date)+"</b>" : "In-Active") +"</td></tr>";
                        }
                        data_to_display += "</table>";

                        // diplay the data of the course
                        cObj("course_details_display").innerHTML = data_to_display;
                    }else{
                        cObj("course_details_display").innerHTML = "<h4 class='text-center'>Course Progress</h4><p class='text-danger text-center'>Your course progress will appear here!</p>";
                    }

                    // intake month
                    var intake_year_edit = cObj("intake_year_edit").children;
                    for (let index = 0; index < intake_year_edit.length; index++) {
                        const element = intake_year_edit[index];
                        if(element.value == splitdata[44]){
                            element.selected = true;
                        }
                    }
                    
                    // intake year
                    var intake_month_edit = cObj("intake_month_edit").children;
                    for (let index = 0; index < intake_month_edit.length; index++) {
                        const element = intake_month_edit[index];
                        if(element.value == splitdata[45]){
                            element.selected = true;
                        }
                    }
                    
                    if(cObj("delete_course_term_module") != undefined){
                        cObj("delete_course_term_module").addEventListener("click", function () {
                            var datapass = "delete_course_module=true&admission_number="+valObj("adminnos");
                            sendDataPost("POST","ajax/administration/admissions.php", datapass, cObj("error_handler_course_progress"), cObj("save_course_progress_loader"));
                            setTimeout(() => {
                                var timeout = 0;
                                var ids = setInterval(() => {
                                    timeout++;
                                    //after two minutes of slow connection the next process wont be executed
                                    if (timeout == 1200) {
                                        stopInterval(ids);
                                    }
                                    if (cObj("save_course_progress_loader").classList.contains("hide")) {
                                        cObj("view"+valObj("adminnos")).click();
                                        setTimeout(() => {
                                            cObj("error_handler_course_progress").innerHTML = "";
                                        }, 3000);
                                        stopInterval(ids);
                                    }
                                }, 100);
                            }, 200);
                        });
                    }
                    
                    var course_progress_children = cObj("course_progress").children;
                    for (let index = 0; index < course_progress_children.length; index++) {
                        const element = course_progress_children[index];
                        if (element.value == splitdata[46]) {
                            element.selected = true;
                        }
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("save_course_progress").onclick = function () {
    if (cObj("course_level_value") != undefined) {
        var course_level_value = hasJsonStructure(valObj("course_level_value")) ? JSON.parse(valObj("course_level_value")) : [];
        console.log(course_level_value);

        // get the value for the course level
        var select_options = document.getElementsByClassName("select_options");
        var statuses = [];
        for (let index = 0; index < select_options.length; index++) {
            const element = select_options[index];
            statuses.push(element.value);
        }

        // statuses
        console.log(statuses);

        // modify the course levels
        let module_terms = course_level_value.module_terms;
        for (let index = 0; index < module_terms.length; index++) {
            const element = module_terms[index];
            element.status = statuses[index];
        }

        // show course levels
        console.log(course_level_value);

        // update the database
        var datapass = "update_course_progress=true&course_updated="+JSON.stringify(course_level_value)+"&student_id="+valObj("adminnos");
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("error_handler_course_progress"),cObj("save_course_progress_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_course_progress_loader").classList.contains("hide")) {
                    cObj("view"+valObj("adminnos")).click();
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

function edit_prev_school() {
    var previous_school_js = cObj("previous_school_json").innerText;
    // console.log(previous_school_js);
    cObj("previous_schools_windows_edit").classList.remove("hide");
}
cObj("canc_add_prev_sch_btn_edit").onclick = function () {
    cObj("previous_schools_windows_edit").classList.add("hide");
}
cObj("add_prev_sch_btn_edit").onclick = function () {
    var err = checkBlank("prev_school_name_edits");
    err += checkBlank("date_left_edit");
    err += checkBlank("marks_scored_edit");
    err += checkBlank("leaving_certifcate_edit");
    err += checkBlank("description_edit");
    if (err == 0) {
        // remove error
        cObj("add_prevsch_error_edit").innerHTML = "";
        // collect data
        var prev_school_name = cObj("prev_school_name_edits").value;
        var date_left = cObj("date_left_edit").value;
        var marks_scored = cObj("marks_scored_edit").value;
        var leaving_certifcate = cObj("leaving_certifcate_edit").checked;
        var description = cObj("description_edit").value;


        // proceed and add the information to the list
        var text = '[{"school_name":"' + prev_school_name + '","date_left":"' + date_left + '","marks_scored":"' + marks_scored + '","leaving_cert":"' + leaving_certifcate + '","reason_for_leaving":"' + description + '"}]';
        var available_txt = cObj("previous_school_json").innerText;
        if (available_txt.length > 0) {
            text = '{"school_name":"' + prev_school_name + '","date_left":"' + date_left + '","marks_scored":"' + marks_scored + '","leaving_cert":"' + leaving_certifcate + '","reason_for_leaving":"' + description + '"}';
            available_txt = available_txt.substring(0, available_txt.length - 1) + "," + text + "]";
            cObj("previous_school_json").innerText = available_txt;
            var prev_schools = JSON.parse(available_txt);
            var counters = 0;
            var previous_schools = "<p class='text-danger'><small>Please save before leaving this window</small></p><table class='table'><tr><th>No</th><th>School Name</th><th>Date Left</th><th>Marks Scored</th><th>Reason For Leaving</th><th>Leaving Certificate</th><th>Actions</th></tr>";
            for (let indexes = 0; indexes < prev_schools.length; indexes++) {
                counters++;
                const element = prev_schools[indexes];
                previous_schools += "<tr><td>" + (indexes + 1) + "</td><td>" + element.school_name + "</td><td>" + element.date_left + "</td><td>" + element.marks_scored + "</td><td>" + element.reason_for_leaving + "</td><td>" + (element.leaving_cert == "true" ? "Submitted" : "Not Submitted") + "</td><td><span class='link rm_prev_sch' id='rm_prev_sch" + indexes + "'><i class='fas fa-trash'></i> Remove</span></td></tr>";
            }
            previous_schools += "</table>";
            if (counters > 0) {
                cObj("prev_sch_list").innerHTML = previous_schools + "<br><p class='block_btn' id='edit_prev_school_btn'>Add Previous Schools</p>";
                cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
                // remove 
                var rm_prev_sch = document.getElementsByClassName("rm_prev_sch");
                for (let ind = 0; ind < rm_prev_sch.length; ind++) {
                    const element = rm_prev_sch[ind];
                    element.addEventListener("click", remove_school);
                }
            } else {
                cObj("prev_sch_list").innerHTML = "<p class='block_btn'  id='edit_prev_school_btn'>Add Previous Schools</p><br><p class='text-danger'>No previous schools attended by the student has been recorded</p>";
                cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
            }
        } else {
            cObj("previous_school_json").innerText = text;
            cObj("prev_sch_list").innerHTML = "<p class='block_btn'  id='edit_prev_school_btn'>Add Previous Schools</p><br><p class='text-danger'>No previous schools attended by the student has been recorded</p>";
            cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);

            var prev_schools = JSON.parse(text);
            var counters = 0;
            var previous_schools = "<p class='text-danger'><small>Please save before leaving this window</small></p><table class='table'><tr><th>No</th><th>School Name</th><th>Date Left</th><th>Marks Scored</th><th>Reason For Leaving</th><th>Leaving Certificate</th><th>Actions</th></tr>";
            for (let indexes = 0; indexes < prev_schools.length; indexes++) {
                counters++;
                const element = prev_schools[indexes];
                previous_schools += "<tr><td>" + (indexes + 1) + "</td><td>" + element.school_name + "</td><td>" + element.date_left + "</td><td>" + element.marks_scored + "</td><td>" + element.reason_for_leaving + "</td><td>" + (element.leaving_cert == "true" ? "Submitted" : "Not Submitted") + "</td><td><span class='link rm_prev_sch' id='rm_prev_sch" + indexes + "'><i class='fas fa-trash'></i> Remove</span></td></tr>";
            }
            previous_schools += "</table>";
            if (counters > 0) {
                cObj("prev_sch_list").innerHTML = previous_schools + "<br><p class='block_btn' id='edit_prev_school_btn'>Add Previous Schools</p>";
                cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
                // remove 
                var rm_prev_sch = document.getElementsByClassName("rm_prev_sch");
                for (let ind = 0; ind < rm_prev_sch.length; ind++) {
                    const element = rm_prev_sch[ind];
                    element.addEventListener("click", remove_school);
                }
            } else {
                cObj("prev_sch_list").innerHTML = "<p class='block_btn'  id='edit_prev_school_btn'>Add Previous Schools</p><br><p class='text-danger'>No previous schools attended by the student has been recorded</p>";
                cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
            }
        }
        cObj("previous_schools_windows_edit").classList.add("hide");
        cObj("prev_school_name_edits").value = "";
        cObj("date_left_edit").value = "";
        cObj("marks_scored_edit").value = "";
        cObj("leaving_certifcate_edit").checked = false;
        cObj("description_edit").value = "";
    } else {
        cObj("add_prevsch_error_edit").innerHTML = "<p class='text-danger'>Please fill all the fields with red borders</p>";
    }
}
function remove_school() {
    var ids = this.id.substr(11);
    var previous_school_js = cObj("previous_school_json").innerText.length > 0 ? JSON.parse(cObj("previous_school_json").innerText) : [];
    var datapass = '[';
    var counter = 0;
    for (let index = 0; index < previous_school_js.length; index++) {
        const element = previous_school_js[index];
        if (index != ids) {
            datapass += JSON.stringify(element) + ",";
            counter++;
        }
    }
    if (counter > 0) {
        datapass = datapass.substring(0, (datapass.length - 1)) + "]";
        var prev_schools = JSON.parse(datapass);
        var counters = 0;
        var previous_schools = "<p class='text-danger'><small>Please save before leaving this window</small></p><table class='table'><tr><th>No</th><th>School Name</th><th>Date Left</th><th>Marks Scored</th><th>Reason For Leaving</th><th>Leaving Certificate</th><th>Actions</th></tr>";
        for (let indexes = 0; indexes < prev_schools.length; indexes++) {
            counters++;
            const element = prev_schools[indexes];
            previous_schools += "<tr><td>" + (indexes + 1) + "</td><td>" + element.school_name + "</td><td>" + element.date_left + "</td><td>" + element.marks_scored + "</td><td>" + element.reason_for_leaving + "</td><td>" + (element.leaving_cert == "true" ? "Submitted" : "Not Submitted") + "</td><td><span class='link rm_prev_sch' id='rm_prev_sch" + indexes + "'><i class='fas fa-trash'></i> Remove</span></td></tr>";
        }
        previous_schools += "</table>";

        cObj("prev_sch_list").innerHTML = previous_schools + "<br><p class='block_btn' id='edit_prev_school_btn'>Add Previous Schools</p>";
        cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
        // remove 
        var rm_prev_sch = document.getElementsByClassName("rm_prev_sch");
        for (let ind = 0; ind < rm_prev_sch.length; ind++) {
            const element = rm_prev_sch[ind];
            element.addEventListener("click", remove_school);
        }
        cObj("previous_school_json").innerText = datapass;
    } else {
        cObj("prev_sch_list").innerHTML = "<p class='text-danger'><small>Please save before leaving this window</small></p><p class='block_btn'  id='edit_prev_school_btn' >Add Previous Schools</p><br><p class='text-danger'>No previous schools attended by the student has been recorded</p>";
        cObj("edit_prev_school_btn").addEventListener("click", edit_prev_school);
        cObj("previous_school_json").innerText = "";
    }
}

cObj("delete_student").onclick = function () {
    var admno = cObj("adminnos").value;
    // get the student id to delete
    var datapass = "delete_student=" + admno;
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("boarding_status_changer"), cObj("delete_student_load"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("delete_student_load").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("boarding_status_changer").innerHTML = "";
                    cObj("returnfind").click();
                    cObj("delete_studs_perm").classList.add("hide");
                }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("prompt_delete_student").onclick = function () {
    cObj("stud_name_del").innerText = cObj("snamed_in").value + " " + cObj("fnamed_in").value + " " + cObj("lnamed_in").value;
    cObj("delete_studs_perm").classList.remove("hide");
}
cObj("no_delete_students").onclick = function () {
    cObj("delete_studs_perm").classList.add("hide");
}

function clickEnroll() {
    // click enroll
    // get the admission number of the student to enroll
    var admno = cObj("adminnos").value;
    // send the admission number to be enrolled
    var datapass = "?enroll_boarding_this=" + admno;
    sendData2("GET", "administration/admissions.php", datapass, cObj("boarding_status_changer"), cObj("boarding_status_load"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("boarding_status_load").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("boarding_status_changer").innerHTML = "";
                }, 10000);
                stopInterval(ids);
                cObj("boarding_status").innerHTML = "<span style='background-color: green; color:white;' class='rounded p-1 '>Enrolled</span> || <span id='unenroll_stud_boarding' class='link' >CLick me to Un-Enroll ?</span>";
                cObj("unenroll_stud_boarding").addEventListener("click", clickUnEnroll);
            }
        }, 100);
    }, 200);
}
function clickUnEnroll() {
    // click enroll
    // get the admission number of the student to enroll
    var admno = cObj("adminnos").value;
    // send the admission number to be enrolled
    var datapass = "?unenroll_boarding_this=" + admno;
    sendData2("GET", "administration/admissions.php", datapass, cObj("boarding_status_changer"), cObj("boarding_status_load"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("boarding_status_load").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("boarding_status_changer").innerHTML = "";
                }, 10000);
                cObj("boarding_status").innerHTML = "<span style='background-color: orange; color:white;' class='rounded p-1 '>Not-enrolled</span> || <span id='enroll_stud_boarding' class='link'>Click me to Enroll</span>";
                // set the listener
                cObj("enroll_stud_boarding").addEventListener("click", clickEnroll);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("updatestudinfor").onclick = function () {
    var changes = checkforchanged(studentinformation);
    changes = 1;
    var err = checkBlank("classed");
    err += checkBlank("doas");

    // clear all errors in the error holder!
    cObj("updateerrors").innerHTML = "";
    cObj("coppy_cat_err").innerHTML = "";
    if (err == 0) {
        if (cObj("course_chosen_edit") != undefined) {
            if (checkBlank("course_chosen_edit") == 1) {
                cObj("updateerrors").innerHTML = "<p class='text-danger'>Select course before proceeding!</p>";
                cObj("coppy_cat_err").innerHTML = "<p class='text-danger'>Select course before proceeding!</p>";
                return 0;
            }
            if (changes != 0) {
                var classed = valObj("classed");
                var index = valObj("indexnos");
                var bcnos = valObj("bcnno");
                var dobs = valObj("dobs");
                var gender = valObj("genders");
                var disabled = valObj("disableds");
                var describe = valObj("descriptionsd");
                var addressed = valObj("addressed");
                var pnamed = valObj("pnamed");
                var pcontacted = valObj("pcontacted");
                var paddressed = valObj("paddressed");
                var pemails = valObj("pemails");
                var parrelationship = valObj("parrelationship");
                var admnos = valObj("adminnos");
                var snamed = valObj("snamed_in");
                var fnamed = valObj("fnamed_in");
                var lnamed = valObj("lnamed_in");
                var occupation1 = valObj("paroccupation1");
                var occupation2 = valObj("paroccupation2");
                var medical_history = valObj("medical_histry");
                var clubs_in_sporters = valObj("clubs_in_sporters");
                var previous_schools = cObj("previous_school_json").innerText;
                var doas = valObj("doas");
                var reason_for_leaving = valObj("reason_for_leaving_desc");
                var course_chosen = valObj("course_chosen_edit");

                // var previous course
                var course_level_hidden = valObj("course_level_hidden");
                var course_chosen_level_hidden = valObj("course_chosen_level_hidden");

                // intake
                var intake_month_edit = valObj("intake_month_edit");
                var intake_year_edit = valObj("intake_year_edit");
    
                var parname2 = valObj('pnamed2');
                var parconts2 = valObj('pcontacted2');
                var parrelation2 = valObj('parrelationship2');
                var pemail2 = valObj('pemails2');
                var course_progress = valObj("course_progress");
                var existing_course = cObj("course_level_value") != undefined ? valObj("course_level_value") : null;
                //collect the data and send to the database
                var datapass = "?updatestudinfor=true&class=" + classed + "&index=" + index + "&bcnos=" + bcnos + "&dob=" + dobs + "&genders=" + gender + "&disabled=" + disabled + "&describe=" + describe;
                datapass += "&address=" + addressed + "&pnamed=" + pnamed + "&pcontacts=" + pcontacted + "&paddress=" + paddressed + "&pemail=" + pemails + "&prelation=" + parrelationship + "&adminnumber=" + admnos;
                datapass += "&parentname2=" + parname2 + "&parentcontact=" + parconts2 + "&parentrelation=" + parrelation2 + "&pemails=" + pemail2 + "&snamed=" + snamed + "&fnamed=" + fnamed + "&lnamed=" + lnamed;
                datapass += "&occupation1=" + occupation1 + "&occupation2=" + occupation2 + "&medical_history=" + medical_history + "&clubs_in_sporters=" + clubs_in_sporters + "&previous_schools=" + previous_schools + "&doas=" + doas;
                datapass += "&reason_for_leaving=" + reason_for_leaving+"&course_chosen="+course_chosen+"&course_level_hidden="+course_level_hidden+"&course_chosen_level_hidden="+course_chosen_level_hidden+"&existing_course_details="+existing_course;
                datapass += "&intake_year_edit="+intake_year_edit+"&intake_month_edit="+intake_month_edit+"&course_progress="+course_progress;
                cObj("updateerrors").innerHTML = "";
                sendData1("GET", "administration/admissions.php", datapass, cObj("updateerrors"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            cObj("coppy_cat_err").innerHTML = cObj("updateerrors").innerHTML;
                            cObj("view"+valObj("adminnos")).click();
                            setTimeout(() => {
                                cObj("updateerrors").innerHTML = "";
                                cObj("coppy_cat_err").innerHTML = cObj("updateerrors").innerHTML;
                            }, 4000);
                            stopInterval(ids);
                        }
                    }, 100);
                }, 100);
            } else {
                cObj("updateerrors").innerHTML = "<p class='text-danger'>Check all errors before you proceed!</p>";
                cObj("coppy_cat_err").innerHTML = "<p class='text-danger'>Check all errors before you proceed!</p>";
            }
        }else{
            cObj("updateerrors").innerHTML = "<p class='text-danger'>Courses have not been set up yet!</p>";
            cObj("coppy_cat_err").innerHTML = "<p class='text-danger'>Courses have not been set up yet!</p>";
        }
    } else {
        console.log(err);
        cObj("updateerrors").innerHTML = "<p class='text-danger'>Select class before you proceed!</p>";
        cObj("coppy_cat_err").innerHTML = "<p class='text-danger'>Select class before you proceed!</p>";
    }
}
function checkforchanged(olddata) {
    let changed = 0;
    if (valObj("classed") != olddata[6]) {
        changed++;
    }

    var indexno = 0;
    if (valObj("indexnos") == "N/A") {
        indexno = 0;
    } else {
        indexno = valObj("indexnos");
    }

    if (indexno != olddata[3]) {
        changed++;
    }

    var bcnos = 0;
    if (valObj("bcnno") == "N/A") {
        bcnos = 0;
    } else {
        bcnos = valObj("bcnno");
    }

    if (bcnos != olddata[14]) {
        changed++;
    }

    if (valObj("dobs") != olddata[4]) {
        changed++;
    }

    if (valObj("genders") != olddata[5]) {
        changed++;
    }

    if (valObj("disableds") != olddata[15]) {
        changed++;
    }
    if (valObj("descriptionsd") != olddata[16]) {
        changed++;
    }

    if (valObj("addressed") != olddata[13]) {
        changed++;
    }
    if (valObj("pnamed") != olddata[9]) {
        changed++;
    }
    if (valObj("pcontacted") != olddata[10]) {
        changed++;
    }
    if (valObj("paddressed") != olddata[13]) {
        changed++;
    }
    if (valObj("pemails") != olddata[12]) {
        changed++;
    }

    if (valObj("parrelationship") != olddata[11]) {
        changed++;
    }
    return changed;
}
cObj("delete_dp").onclick = function () {
    var datapass = "?delete_dps_student=" + cObj("adminnos").value;
    sendData2("GET", "administration/admissions.php", datapass, cObj("dp_locale"), cObj("student_dp_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("student_dp_loader").classList.contains("hide")) {
                getDP();
                setTimeout(() => {
                    cObj("dp_locale").innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
cObj("admno").onkeyup = function () {
    var admissionno = this.value;
    cObj("resultsbody").classList.remove("hide");
    cObj("viewinformation").classList.add("hide");
    if (admissionno.length > 0) {
        var datapass = "?find=true&admnoincomplete=" + admissionno;
        sendData2("GET", "administration/admissions.php", datapass, cObj("resultsbody"), cObj("admnos_loaders_find"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("admnos_loaders_find").classList.contains("hide")) {
                    var btns = document.getElementsByClassName("view_students");
                    for (let index = 0; index < btns.length; index++) {
                        const element = btns[index];
                        setListenerBtnTab(element.id);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}
cObj("returnfind").onclick = function () {
    goBack();
}
cObj("go_back_1").onclick = function () {
    goBack();
}
function goBack() {
    cObj("resultsbody").classList.remove("hide");
    cObj("viewinformation").classList.add("hide");
    cObj("findingstudents").click();
}
if (typeof (cObj("showexpenses")) != 'undefined' && cObj("showexpenses") != null) {
    cObj("showexpenses").onclick = function () {
        if (this.value == "Show") {
            this.value = "Hide";
            cObj("shwexpense").classList.add("hide");
        } else if (this.value == "Hide") {
            this.value = "Show";
            cObj("shwexpense").classList.remove("hide");
        }
    }
}

cObj("accept_last_yr_acad_bal").onclick = function () {
    var err = checkBlank("new_last_acad_bal");
    if (err == 0) {
        var datapass = "?update_academic_balance=true&student_balance=" + valObj("new_last_acad_bal") + "&student_admission=" + valObj("students_admission_number");
        sendData2("GET", "finance/financial.php", datapass, cObj("men_in_out"), cObj("change_balance_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("change_balance_loaders").classList.contains("hide")) {
                    cObj("view" + valObj("students_admission_number")).click();
                    cObj("cancel_last_yr_acad_bal").click();
                    cObj("men_in_out").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("edit_last_yr_academic_balance").onclick = function () {
    cObj("change_last_academic_windows").classList.remove("hide");
    cObj("students_admission_number").value = valObj("adminnos");
}

cObj("cancel_last_yr_acad_bal").onclick = function () {
    cObj("change_last_academic_windows").classList.add("hide");
    cObj("students_admission_number").value = "";
    cObj("new_last_acad_bal").value = 0;
}

cObj("completeadmbtn").onclick = function () {
    //check if disabled is selected
    let err = 0;
    //data needed
    let disabled = "";
    let describe = "none";
    let paymode = "none";
    let payamount = "";
    let paycode = "cash";
    let boarded = "";
    //check disabled

    disabled = valObj("disabled");
    if (disabled == "Yes") {
        grayBorder(cObj("disabled"));
        describe = valObj("disability");
        if (describe.length > 5) {
            grayBorder(cObj("disability"));
        } else {
            redBorder(cObj("disability"));
            err++;
        }
    } else if (disabled == "No") {
        grayBorder(cObj("disabled"));
    } else {
        redBorder(cObj("disabled"));
        err++;
    }

    //check mode of payments
    var payedadmfee = valObj("payfees");
    if (payedadmfee == "Yes") {
        grayBorder(cObj("payfees"));
        paymode = valObj("paymode");
        if (paymode == "mpesa") {
            grayBorder(cObj("paymode"));
            paycode = valObj("mpesa");
            payamount = valObj("amounts");
            err += checkBlank("mpesa");
            err += checkBlank("amounts");
        } else if (paymode == "cash") {
            grayBorder(cObj("paymode"));
            payamount = valObj("amnt");
            err += checkBlank("amnt");
        } else if (paymode == "bank") {
            grayBorder(cObj("paymode"));
            paycode = valObj("bank");
            payamount = valObj("amount");
            err += checkBlank("bank");
            err += checkBlank("amount");
        } else {
            err++;
            redBorder(cObj("paymode"));
        }
    } else if (payedadmfee == "No") {
        grayBorder(cObj("payfees"));
    } else {
        err++;
        redBorder(cObj("payfees"));
    }
    boarded = valObj("board");
    if (boarded == "enroll") {
        grayBorder(cObj("board"));
    } else if (boarded == "none") {
        grayBorder(cObj("board"));
    } else {
        redBorder(cObj("board"));
    }
    //check the selected checkbox
    var admissionessentials = document.getElementsByClassName("elementsadm");
    var len = admissionessentials.length;
    var admissionessentialscollected = "";
    if (len > 0) {
        for (let index = 0; index < admissionessentials.length; index++) {
            const element = admissionessentials[index];
            if (element.checked == true) {
                admissionessentialscollected += element.value + ",";
            }
        }
        admissionessentialscollected = admissionessentialscollected.substr(0, (admissionessentialscollected.length - 1));
    }
    //update information to the new students
    if (err == 0) {
        var admissno = cObj("admissionno").innerText;
        // console.log(admissno);
        var medical_history = cObj("medical_history").value;
        var source_of_funding_data = cObj("source_of_funding_data").value;
        var previous_schools = cObj("previous_schools").innerText;
        var clubs_n_sports = cObj("select_clubs_sports").value;
        if (admissno.length > 0) {
            var datapass = "?completeadmit=true&disabled=" + disabled + "&description=" + describe + "&paymode=" + paymode + "&payamount=" + payamount + "&paycode=" + paycode + "&boarded=" + boarded + "&admno=" + admissno + "&admissionessentials=" + admissionessentialscollected + "&fees_paid=" + payedadmfee;
            datapass += "&medical_history=" + medical_history + "&source_of_funding_data=" + source_of_funding_data + "&previous_schools=" + previous_schools + "";
            datapass += "&clubs_n_sports=" + clubs_n_sports + "&last_year_academic_balance=" + valObj("last_year_academic_balance");
            sendData1("GET", "administration/admissions.php", datapass, cObj("errorcomadmit"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        var errormessage = cObj("errorcomadmit").innerText.substr(0, 12);
                        if (errormessage == "Registration") {
                            cObj("completeadm").reset();
                            cObj("errorcomadmit").innerHTML = "<p></p>";
                            cObj("paysmode").classList.add("hide");
                            cObj("boardings").classList.add("hide");
                            cObj("cashed").classList.add("hide");
                            cObj("mpesas").classList.add("hide");
                            cObj("banks").classList.add("hide");
                            hideWindow();
                            cObj("admitsStudents").classList.remove("hide");
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
        } else {
            cObj("errorcomadmit").innerHTML = "<p style='color:red;'>Check your admission number and try again</p>";
        }
    } else {
        cObj("errorcomadmit").innerHTML = "<p style='color:red;'>Check for errors and try again!</p>";
    }
}

cObj("board").onchange = function () {
    if (this.value == "enroll") {
        cObj("boardings").classList.remove("hide");
    } else if (this.value == "none") {
        cObj("boardings").classList.add("hide");
    } else {
        cObj("boardings").classList.add("hide");
    }
}

cObj("disabled").onchange = function () {
    if (this.value == "Yes") {
        cObj("disable").classList.remove("hide");
    } else if (this.value == "No") {
        cObj("disable").classList.add("hide");
    }
}
cObj("payfees").onchange = function () {
    if (this.value == "Yes") {
        cObj("paysmode").classList.remove("hide");
    } else if (this.value == "No") {
        cObj("paysmode").classList.add("hide");
    }
}
cObj("paymode").onchange = function () {
    if (this.value == "mpesa") {
        cObj("cashed").classList.add("hide");
        cObj("mpesas").classList.remove("hide");
        cObj("banks").classList.add("hide");
    } else if (this.value == "cash") {
        cObj("cashed").classList.remove("hide");
        cObj("mpesas").classList.add("hide");
        cObj("banks").classList.add("hide");
    } else if (this.value == "bank") {
        cObj("cashed").classList.add("hide");
        cObj("mpesas").classList.add("hide");
        cObj("banks").classList.remove("hide");
    }
}


cObj("resetadmitform").onclick = function () {
    cObj("admitform").reset();
}

// submit the button
cObj("submitbtn").onclick = function () {
    //check for any blank field
    let errors = checkAdmission();
    if (errors == 0 && presentBCNO == false) {
        if (cObj("course_chosen") != undefined) {
            if (typeof (cObj("errolment")) != undefined && cObj("errolment") != null) {
                //proceed and upload the data
                cObj("erroradm").innerHTML = "<p style='color:green;font-size:14px;'>Good to go!</p>";
                //GET VALUES
                var surname = valObj('surname');
                var fname = valObj('fname');
                var sname = valObj('sname');
                var dob = valObj('dob');
                var gender = valObj('gender');
                var errolment = valObj('errolment');
                var parname = valObj('parname');
                var parconts = valObj('parconts');
                var parrelation = valObj('parrelation');
                var pemail = valObj('pemail');
                var course_chosen = valObj("course_chosen");
    
                var parname2 = valObj('parname2');
                var parconts2 = valObj('parconts2');
                var parrelation2 = valObj('parrelation2');
                var pemail2 = valObj('pemail2');
    
                var bcno = valObj('bcno');
                var address = valObj('address');
                var upis = valObj("upis");
                var last_year_academic_balance = valObj("last_year_academic_balance");
                var admno = "";
                if (valObj("automated_amd") == "insertmanually") {
                    admno = cObj("mangen").value;
                }
                if (valObj("automated_amd") == "automate_adm") {
                    admno = cObj("autogen").value;
                }
    
                var parent_accupation1 = valObj("parent_accupation1").trim().length > 0 ? valObj("parent_accupation1").trim() : "none";
                var parent_accupation2 = valObj("parent_accupation2").trim().length > 0 ? valObj("parent_accupation2").trim() : "none";
    
                var datapass = "admit=true&surname=" + surname + "&fname=" + fname + "&sname=" + sname + "&dob=" + dob + "&gender=" + gender + "&enrolment=" + errolment + "&parentname=" + parname + "&parentconts=" + parconts + "&upis=" + upis;
                datapass += "&parentrela=" + parrelation + "&pemail=" + pemail + "&bcno=" + bcno + "&address=" + address + "&admnos=" + admno;
                datapass += "&parentrela2=" + parrelation2 + "&pemail2=" + pemail2 + "&parentname2=" + parname2 + "&parentconts2=" + parconts2;
                datapass += "&parent_accupation1=" + parent_accupation1 + "&parent_accupation2=" + parent_accupation2 + "&last_year_academic_balance=" + last_year_academic_balance;
                datapass += "&course_chosen="+course_chosen+"&adm_option="+valObj("automated_amd");
                datapass += "&intake_year="+valObj("intake_year")+"&intake_month="+valObj("intake_month")+"&course_module_terms="+valObj("course_module_terms");
                sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("erroradm"),cObj("loadings"));
                setTimeout(() => {
                    var ids = setInterval(() => {
                        if (cObj("loadings").classList.contains("hide")) {
                            if (cObj("admnohold") != null) {
                                var admnos = valObj("admnohold");
                                var names = valObj("namehold");
                                cObj("admissionno").innerText = admno;
                                cObj("studname").innerText = names;
                                cObj("admitform").reset();
                                
                                //bring the complete admission window
                                hideWindow();
                                cObj("completeadmission").classList.remove("hide");
                                getClubsNSports();
                            }
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            } else {
                cObj("erroradm").innerHTML = "<p style='color:red;font-size:14px;'><strong>Errors</strong><br>No class selected!</p>";
            }
        }else{
            cObj("erroradm").innerHTML = "<p style='color:red;font-size:14px;'><strong>Errors</strong><br>Courses offered is not configured, Kindly set them up to proceed!</p>";
        }
    } else {
        cObj("erroradm").innerHTML = "<p style='color:red;font-size:14px;'><strong>Errors</strong><br>Please fill all the fields with the red border and read their instructions correctly</p>";
        if (typeof (cObj("errolment")) == 'undefined' && cObj("errolment") == null) {
            cObj("erroradm").innerHTML = "<p style='color:red;font-size:14px;'><strong>Errors</strong><br>No class selected!</p>";
        }
    }
}
cObj("bcno").onblur = function () {
    if (this.value.length > 0) {
        //check if the BCNO is present
        var datapassing = "?checkbcno=" + this.value;
        sendData("GET", "administration/admissions.php", datapassing, cObj("bcnerr"));
        setTimeout(() => {
            if (cObj("bcnerr").innerText.substr(0, 3) == "The") {
                redBorder(this);
                presentBCNO = true;
            } else {
                grayBorder(this);
                presentBCNO = false;
            }
        }, 200);

    }
}
function checkAdmission() {
    let err = 0;
    // err += checkBlank("surname");//username
    err += checkBlank("fname");
    err += checkBlank("sname");
    err += checkBlank("dob");
    err += checkBlank("gender");
    err += checkBlank("intake_year");
    err += checkBlank("intake_month");
    if (typeof (cObj("errolment")) != 'undefined' && cObj("errolment") != null) {
        err += checkBlank("errolment");
    } else {
        err++;
    }
    // err += checkBlank("parname");
    // err += checkPhone("parconts", "parerr");
    // err += checkBlank("parrelation");
    err += checkBlank("automated_amd");
    if (valObj("automated_amd") == "automate_adm") {
        err += checkBlank("autogen");
    }
    if (valObj("automated_amd") == "insertmanually") {
        err += checkBlank("mangen");
    }
    if (cObj("admgenman").innerText.length > 0) {
        err++;
    }

    if (cObj("course_module_terms") != undefined) {
        err += checkBlank("course_module_terms");
    }else{
        err ++;
    }

    if (cObj("course_chosen") != undefined) {
        err += checkBlank("course_chosen");
    }else{
        err ++;
    }

    // continue with the course enrollment
    return err;
}
function assignsubjectsbtn() {
    cObj("constable").classList.add("hide");
    cObj("informationwindow").classList.add("hide");
    cObj("subject_and_teacher").classList.remove("hide");
    //get the class teachers with no class assigned to them
    var datapass = "?get_CLassteacher=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("getteacherdata"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                var change_classteacher = document.getElementsByClassName("change_classteacher");
                for (let fled = 0; fled < change_classteacher.length; fled++) {
                    const element = change_classteacher[fled];
                    setChangeTeacherListener(element.id);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function setChangeTeacherListener(ids) {
    cObj(ids).addEventListener("click", changeTeacherListener);
}
function changeTeacherListener() {
    cObj("tr_na_me").innerText = cObj("ccN" + this.id.substr(2)).innerText;
    cObj("class_assigned").innerText = cObj("ccD" + this.id.substr(2)).innerText;
    cObj("tr_id_s").innerText = this.id.substr(2);
    cObj("class_information").classList.remove("hide");
}
cObj("show_subjects").onclick = function () {
    cObj("assign_teacher").classList.remove("hide");
    //send data to the database to show those teachers with no classes
    var datapass = "?get_available_teacher=true;";
    sendData2("GET", "administration/admissions.php", datapass, cObj("assign_data"), cObj("loader_win"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loader_win").classList.contains("hide")) {
                var check_subjects = document.getElementsByClassName("check_subjects");
                for (let fled = 0; fled < check_subjects.length; fled++) {
                    const element = check_subjects[fled];
                    setTeacherListener(element.id);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);

}
function setTeacherListener(ids) {
    cObj(ids).addEventListener("change", teacherListener);
}
function teacherListener() {
    if (this.checked == true) {
        cObj("partition").classList.remove("hide");
        cObj("select_teacher").classList.add("hide");
        cObj("add_subject").classList.remove("hide");
        //set name and id
        cObj("tr_ids").innerText = this.id.substr(4);
        cObj("tr_name").innerText = this.value;
        //get classes with no class teachers
        var datapass = "?get_Class_available=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("class_list12"), cObj("loading_12"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loading_12").classList.contains("hide")) {
                    var check_class = document.getElementsByClassName("check_class");
                    for (let fled = 0; fled < check_class.length; fled++) {
                        const element = check_class[fled];
                        setClassListener(element.id);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}
function setClassListener(ids) {
    cObj(ids).addEventListener("click", listenClas);
}


function listenClas() {
    if (this.checked == true) {
        //add the teacher to the database
        var check_class = document.getElementsByClassName("check_class");
        for (let fled = 0; fled < check_class.length; fled++) {
            const element = check_class[fled];
            element.checked = false;
        }
        this.checked = true;
    }
}
cObj("add_subject").onclick = function () {
    var check_class = document.getElementsByClassName("check_class");
    var class_selected = "";
    for (let fled = 0; fled < check_class.length; fled++) {
        const element = check_class[fled];
        if (element.checked == true) {
            class_selected = element.value;
            break;
        }
    }
    if (class_selected.length > 0) {
        cObj("errorhandler12031").innerHTML = "";
        //send data to the database
        var datapass = "?add_classteacher=true&clas_s=" + class_selected + "&teacher_ids=" + cObj("tr_ids").innerText;
        sendData1("GET", "administration/admissions.php", datapass, cObj("errorhandler12031"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loading_12").classList.contains("hide")) {
                    cObj("partition").classList.add("hide");
                    cObj("select_teacher").classList.remove("hide");
                    assignsubjectsbtn();
                    //close the window
                    cObj("assign_teacher").classList.add("hide");
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("errorhandler12031").innerHTML = "<p style='color:red;font-size:14px;'>Select a class to proceed!</p>";
    }
}
cObj("returnback2").onclick = function () {
    cObj("errorhandler12031").innerHTML = "";
    cObj("partition").classList.add("hide");
    cObj("select_teacher").classList.remove("hide");
    cObj("add_subject").classList.add("hide");
    //uncheck all checked fields
    var check_subjects = document.getElementsByClassName("check_subjects");
    for (let fled = 0; fled < check_subjects.length; fled++) {
        const element = check_subjects[fled];
        element.checked = false;
    }

}
cObj("cancel_addsub").onclick = function () {
    cObj("errorhandler12031").innerHTML = "";
    cObj("add_subject").classList.add("hide");
    cObj("partition").classList.add("hide");
    cObj("select_teacher").classList.remove("hide");
    cObj("assign_teacher").classList.add("hide");
}
cObj("cancel_win1").onclick = function () {
    cObj("errorhandler12031").innerHTML = "";
    cObj("add_subject").classList.add("hide");
    cObj("partition").classList.add("hide");
    cObj("select_teacher").classList.remove("hide");
    cObj("assign_teacher").classList.add("hide");
}
cObj("close_ci").onclick = function () {
    cObj("no_unassign").click();
    cObj("returnback3").click();
    cObj("class_information").classList.add("hide");
}
cObj("close_ci_1").onclick = function () {
    cObj("no_unassign").click();
    cObj("returnback3").click();
    cObj("class_information").classList.add("hide");
}
cObj("un_assign_btn").onclick = function () {
    cObj("confirm_delete_btns").classList.remove("hide");
    cObj("option_s").classList.add("hide");
}
cObj("no_unassign").onclick = function () {
    cObj("confirm_delete_btns").classList.add("hide");
    cObj("option_s").classList.remove("hide");
}
cObj("yes_unassign").onclick = function () {
    var datapass = "?teacher_unassign_id=" + cObj("tr_id_s").innerText;
    sendData1("GET", "administration/admissions.php", datapass, cObj("set_class_err"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                assignsubjectsbtn();
                cObj("set_class_err").innerText = "";
                cObj("close_ci").click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("change_assigned_tr").onclick = function () {
    cObj("find_opts").classList.add("hide");
    cObj("options_ones").classList.remove("hide");
    cObj("save_inform").classList.remove("hide");
    var datapass = "?get_teacher_for_subject=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("populate_data"), cObj("load_teacher"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                var savedata = document.getElementsByClassName("check_teachers_subjects");
                for (let infor = 0; infor < savedata.length; infor++) {
                    const element = savedata[infor];
                    setListenerTrSub(element.id);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function setListenerTrSub(ids) {
    cObj(ids).addEventListener("change", listenerTrSub);
}
function listenerTrSub() {
    if (this.checked == true) {
        var savedata = document.getElementsByClassName("check_teachers_subjects");
        for (let index = 0; index < savedata.length; index++) {
            const element = savedata[index];
            element.checked = false;
        }
        cObj(this.id).checked = true;
    }
}
cObj("returnback3").onclick = function () {
    cObj("find_opts").classList.remove("hide");
    cObj("options_ones").classList.add("hide");
    cObj("save_inform").classList.add("hide");
    cObj("set_class_err").innerHTML = "";
}
cObj("save_inform").onclick = function () {
    var savedata = document.getElementsByClassName("check_teachers_subjects");
    var present = 0;
    var id = "";
    for (let index = 0; index < savedata.length; index++) {
        const element = savedata[index];
        if (element.checked == true) {
            present = 1;
            id = element.id.substr(7);
        }
    }
    if (present == 1) {
        cObj("set_class_err").innerHTML = "";
        var datapass = "?replace_tr_id=" + id + "&existing_id=" + cObj("tr_id_s").innerText;
        sendData1("GET", "administration/admissions.php", datapass, cObj("set_class_err"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("set_class_err").innerHTML = "";
                    cObj("close_ci_1").click();
                    assignsubjectsbtn();
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("set_class_err").innerHTML = "<p style='color:red;font-size:14px;'>Select a teacher to proceed!</p>";
    }
}
/**
cObj("savers").onclick = function () {
    cObj("jj").classList.add("hide");
    cObj("imager1").classList.remove("hide");
    cObj("imager3").classList.add("hide");
    setTimeout(() => {
        cObj("imager2").classList.remove("hide");
        cObj("imager1").classList.add("hide");
        setTimeout(() => {
            cObj("imager3").classList.remove("hide");
            cObj("imager2").classList.add("hide");
        }, 1000);
    }, 1200);
}
 */
cObj("update_school_in4").onclick = function () {
    //check for error!
    var err = checkBlank("school_name_s");
    err += checkBlank("school_motto_s");
    err += checkBlank("school_message_name");
    err += checkBlank("school_vission");
    err += checkBlank("school_codes");
    err += checkBlank("administrator_name");
    err += checkBlank("administrator_contacts");
    err += checkBlank("school_box_no");
    err += checkBlank("box_Code");
    err + checkBlank("sch_country");
    err += checkBlank("sch_county");
    if (err == 0) {
        cObj("school_information_err_handler").innerHTML = "";
        var datapass = "?update_school_information=true&school_name=" + cObj("school_name_s").value + "&school_motto=" + cObj("school_motto_s").value + "&school_message_name=" + cObj("school_message_name").value + "&school_vission=" + cObj("school_vission").value;
        datapass += "&school_codes=" + cObj("school_codes").value + "&administrator_name=" + cObj("administrator_name").value + "&administrator_contacts=" + cObj("administrator_contacts").value + "&administrator_email=" + cObj("administrator_email").value;
        datapass += "&postalcode=" + cObj("box_Code").value + "&sch_box_no=" + cObj("school_box_no").value;
        datapass += "&sch_country=" + cObj("sch_country").value + "&sch_county=" + cObj("sch_county").value;
        datapass += "&physicall_address=" + valObj("sch_physical_address") + "&school_website=" + valObj("school_websites");
        //alert(datapass);
        sendData1("GET", "login/login.php", datapass, cObj("school_information_err_handler"));
    } else {
        cObj("school_information_err_handler").innerHTML = "<p style='font-size:13px;font-weight:600;color:red;'>Check all blank fields that are marked with a red border!</p>";
    }
}
cObj("change_my_information").onclick = function () {
    //check for blank spaces
    var err = checkBlank("my_full_name");
    err += checkBlank("my_dob");
    err += checkBlank("sys_username");
    err += checkBlank("my_gender");
    err += checkBlank("my_phone_no");
    err += checkBlank("my_nat_id");
    err += checkBlank("my_tsc_code");
    err += checkBlank("my_address");
    err += checkBlank("my_mail");
    if (cObj("check_me_username").innerText.length > 0) {
        err++;
    }
    if (err == 0) {
        cObj("update_my_infor").innerHTML = "";
        if (valObj("sys_username").trim().length > 4) {
            var datapass = "?change_my_information=true&my_name=" + cObj("my_full_name").value + "&my_dob=" + cObj("my_dob").value + "&my_username=" + cObj("sys_username").value.trim() + "&my_gender=" + cObj("my_gender").value + "&my_phone=" + cObj("my_phone_no").value + "&my_nat_id=" + cObj("my_nat_id").value + "&my_tsc_code=" + cObj("my_tsc_code").value + "&my_address=" + cObj("my_address").value + "&my_mail=" + cObj("my_mail").value;
            sendData1("GET", "login/login.php", datapass, cObj("update_my_infor"));
            grayBorder(cObj("sys_username"));
        } else {
            redBorder(cObj("sys_username"));
            cObj("update_my_infor").innerHTML = "<p style='font-size:13px;font-weight:600;color:red;'>Minimum of five characters to be used for the username!</p>";
        }
    } else {
        cObj("update_my_infor").innerHTML = "<p style='font-size:13px;font-weight:600;color:red;'>Check all blank fields that are marked with a red border!</p>";
    }
}
//change password
cObj("change_my_pass").onclick = function () {
    //check blank
    var err = checkBlank("old_pass");
    err += checkBlank("new_pass");
    err += checkBlank("repeat_pass");
    if (err == 0) {
        if (valObj("new_pass").trim() == valObj("repeat_pass").trim()) {
            cObj("update_credential_infor").innerHTML = "";
            var datapass = "?update_password=true&old_pass=" + valObj("old_pass").trim() + "&newpass=" + valObj("new_pass").trim();
            sendData1("GET", "login/login.php", datapass, cObj("update_credential_infor"));
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("loadings").classList.contains("hide")) {
                        if (cObj("update_credential_infor").innerText == "Your old password is in-correct") {
                            redBorder(cObj("old_pass"));
                        } else {
                            grayBorder(cObj("old_pass"));
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        } else {
            redBorder(cObj("new_pass"));
            redBorder(cObj("repeat_pass"));
            cObj("update_credential_infor").innerHTML = "<p style='color:red;font-weight:600;font-size:13px;'>Passwords don`t match!</p>";
        }
    } else {
        cObj("update_credential_infor").innerHTML = "<p style='color:red;font-weight:600;font-size:13px;'>Check all blank fields that are marked with a red border!</p>";
    }
}
cObj("open_notify").onclick = function () {
    cObj("notification_win").classList.toggle("hide");
    cObj("log_notification").classList.toggle("hide");
    if (!cObj("notification_win").classList.contains("hide")) {
        //get the notification list
        var datapass = "?getNoticeTitles=true";
        sendData1("GET", "notices/notices.php", datapass, cObj("notice_list"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loadings").classList.contains("hide")) {
                    //set listeners for the view_students message button
                    var read_message = document.getElementsByClassName("set_notify");
                    for (let index = 0; index < read_message.length; index++) {
                        const element = read_message[index];
                        element.addEventListener("click", viewMessage);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}
cObj("open_more").onclick = function () {
    cObj("per_profile").classList.toggle("hide");
    cObj("log_notification").classList.toggle("hide");
}
cObj("log_notification").onclick = function () {
    if (!cObj("notification_win").classList.contains("hide")) {
        cObj("notification_win").classList.add("hide");
    }
    if (!cObj("per_profile").classList.contains("hide")) {
        cObj("per_profile").classList.add("hide");
    }
    this.classList.add("hide");
}
cObj("update_my_prof").onclick = function () {
    cObj("log_notification").classList.add("hide");
    cObj("per_profile").classList.add("hide");
    cObj("update_personal_profile").click();
}
cObj("logout_1").onclick = function () {
    cObj("log_notification").classList.add("hide");
    cObj("per_profile").classList.add("hide");
    cObj("logout").click();
}
cObj("close_read_notice1").onclick = function () {
    cObj("read_notice").classList.add("hide");
    refreshNotice();
}
cObj("close_read_notice").onclick = function () {
    cObj("read_notice").classList.add("hide");
    refreshNotice();
}
cObj("show_all_notices").onclick = function () {
    //show the notice window
    hideWindow();
    cObj("notices_window").classList.remove("hide");
    //get the notices from the database
    var datapass = "?getAllMessages=true";
    sendData1("GET", "notices/notices.php", datapass, cObj("notifies_holders"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                //set listeners for the view_students message button
                var read_message = document.getElementsByClassName("read_message");
                for (let index = 0; index < read_message.length; index++) {
                    const element = read_message[index];
                    element.addEventListener("click", viewMessage);
                }
                var delete_notice = document.getElementsByClassName("delete_notice");
                for (let index = 0; index < delete_notice.length; index++) {
                    const element = delete_notice[index];
                    element.addEventListener("click", deleteNofification);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    //hide the notice pane
    cObj("notification_win").classList.add("hide");
    cObj("log_notification").classList.add("hide");

}
function viewMessage() {
    var notice_id = this.id.substr(3);
    viewMsg(notice_id);
}
function refreshNotice() {
    if (!cObj("notices_window").classList.contains("hide")) {
        cObj("show_all_notices").click();
    }
}
function deleteNofification() {
    //get the message id
    var datapas = "?delete_notice=" + this.id.substr(4);
    sendData1("GET", "notices/notices.php", datapas, cObj("delete_not"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                //refresh the notification
                cObj("show_all_notices").click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function viewMsg(msg_id) {
    //get the data from the database
    var datapass = "?getMyNoticeid=" + msg_id;
    sendData1("GET", "notices/notices.php", datapass, cObj("msg_body"));
    //display the view_students message window
    cObj("read_notice").classList.remove("hide");
    //if the notice window is open close
    if (!cObj("notification_win").classList.contains("hide")) {
        //hide the notice pane
        cObj("notification_win").classList.add("hide");
        cObj("log_notification").classList.add("hide");
    }
}
cObj("delete_message").onclick = function () {
    //get id
    var datapas = "?delete_notice=" + cObj("notify_id").innerText;
    sendData1("GET", "notices/notices.php", datapas, cObj("delete_not"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                //hide the window
                cObj("read_notice").classList.add("hide");
                if (!cObj("notices_window").classList.contains("hide")) {
                    //refresh the notification
                    cObj("show_all_notices").click();
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("close_add_expense").onclick = function () {
    cObj("add_expense_par").classList.add("hide");
}
cObj("close_add_expense2").onclick = function () {
    cObj("add_expense_par").classList.add("hide");
}
cObj("add_expense").onclick = function () {
    cObj("add_expense_par").classList.remove("hide");
    getClasses("class_list_fees","course_level_fees_structure","","loadings213111");
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings213111").classList.contains("hide")) {
                // add an event listener
                cObj("course_level_fees_structure").addEventListener("change",getTheCourseList);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function getTheCourseList() {
    var datapass = "?get_course_fees_structure=true&course_level="+this.value;
    sendData2("GET","administration/admissions.php",datapass,cObj("course_fees_structure"),cObj("loading_course_level_fees_struct"));
}

cObj("save_add_expense").onclick = function () {
    //check for errors
    cObj("err_handler_10").innerHTML = "";
    var err = checkBlank("exp_name");
    err += checkBlank("term_one");
    err += checkBlank("term_two");
    err += checkBlank("term_three");
    err += checkBlank("boarders_regular");
    if (cObj("expe_err").innerText.length > 0) {
        err++;
    }
    if (err == 0) {
        // check if the courses are present
        if(cObj("course_level_fees_structure") == undefined){
            cObj("err_handler_10").innerHTML = "<p class='red_notice'>Courses levels are not set!</p>";
            return 0;
        }
        // check if the course list is present
        if(cObj("course_lists_fees_structure")  == undefined){
            cObj("err_handler_10").innerHTML = "<p class='red_notice'>Courses are not set!</p>";
            return 0;
        }
        err = 0;
        err += checkBlank("course_lists_fees_structure");
        err += checkBlank("course_level_fees_structure");
        if (err == 0) {
            //check if classes is selected
            //get class list and get the datas
            var expense_name = valObj("exp_name");
            var term_one = valObj("term_one");
            var term_two = valObj("term_two");
            var term_three = valObj("term_three");
            var roles = valObj("boarders_regular");
            var course = valObj("course_lists_fees_structure");
            var course_level = valObj("course_level_fees_structure");

            // send to the server for processing!
            var datapass = "?add_expense=true&expense_name=" + expense_name + "&term_one=" + term_one + "&term_two=" + term_two + "&term_three=" + term_three + "&course_level=" + course_level + "&roles=" + roles+"&course="+course;
            sendData1("GET", "finance/financial.php", datapass, cObj("err_handler_10"));
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("loadings").classList.contains("hide")) {
                        cObj("err_handler_10").innerHTML = "";
                        cObj("exp_names").reset();
                        // cObj("add_expense_par").classList.add("hide");
                        cObj("close_add_expense2").click();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("err_handler_10").innerHTML = "<p class='red_notice'>Check all fields covered with red border!</p>";
        }
    } else {
        cObj("err_handler_10").innerHTML = "<p class='red_notice'>Check for errors for the fields covered with red border!</p>";
    }
}
function getMyClassList() {
    //get the class list
    var datapas = "?getmyClassList=true";
    sendData2("GET", "administration/admissions.php", datapas, cObj("class_holder"), cObj("class_list_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("class_list_clock").classList.contains("hide")) {
                var remove_class = document.getElementsByClassName("remove_class");
                for (let index = 0; index < remove_class.length; index++) {
                    const element = remove_class[index];
                    element.addEventListener("click", promptUserDeleteClass);
                }
                var arrange_class = document.getElementsByClassName("arrange_class");
                for (let index1 = 0; index1 < arrange_class.length; index1++) {
                    const element = arrange_class[index1];
                    element.addEventListener("change", arrangeClasses);
                }
                var change_classes = document.getElementsByClassName("change_classes");
                for (let index = 0; index < change_classes.length; index++) {
                    const element = change_classes[index];
                    element.addEventListener("click", changeClassName);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
function changeClassName() {
    var our_id = this.id.substr(14);
    
    if (cObj("class_value_"+our_id) != undefined) {
        var class_value = hasJsonStructure(valObj("class_value_"+our_id)) ? JSON.parse(valObj("class_value_"+our_id)) : [];
        cObj("old_clas_name").innerText = classNameAdms(class_value.classes);
        cObj("old_class_name_edit").value = class_value.classes;
        cObj("new_class_name").value = class_value.classes;
        cObj("class_id").value = class_value.id;
        cObj("change_class_name_window").classList.remove("hide");
        
    }
}

cObj("cancel_class_change").onclick = function () {
    cObj("change_class_name_window").classList.add("hide");
}

cObj("accept_class_change").onclick = function () {
    var err = checkBlank("new_class_name");
    err += checkBlank("class_id");
    err += checkBlank("old_class_name_edit");
    if (err == 0) {
        var datapass = "?change_class_name=true&new_class_name=" + encodeURIComponent(valObj("new_class_name")) + "&class_id=" + valObj("class_id")+"&old_class_name="+encodeURIComponent(cObj("old_class_name_edit"));
        sendData2("GET", "administration/admissions.php", datapass, cObj("add_class_err_handler"), cObj("class_list_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("class_list_clock").classList.contains("hide")) {
                    cObj("new_class_name").value = "";
                    getMyClassList();
                    cObj("cancel_class_change").click();
                    setTimeout(() => {
                        cObj("add_class_err_handler").innerHTML = "";
                    }, 3000);
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function arrangeClasses() {
    var datapass = "?arrange_class=true&class_index=" + this.value;
    // console.log(datapass);
    sendData2("GET", "administration/admissions.php", datapass, cObj("add_class_err_handler"), cObj("class_list_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("class_list_clock").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("add_class_err_handler").innerHTML = "";
                }, 10000);
                getMyClassList();
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
function promptUserDeleteClass() {
    // get the row id
    var this_data = this.id.substr(3);

    // get the class name and set to the prompt message
    var class_data = hasJsonStructure(valObj("class_value_"+this_data)) ? JSON.parse(valObj("class_value_"+this_data)) : [];
    cObj("delete_class_id").innerText = class_data.classes;
    cObj("delete_classes_id").value = class_data.id;

    // get the prompt window
    cObj("del_classes_win").classList.remove("hide");
}
// delete class
cObj("close_del_cl_win").onclick = function () {
    cObj("del_classes_win").classList.add("hide");
}

cObj("del_class_btn").onclick = function () {
    removeClassSys(valObj("delete_classes_id"));
}
function removeClassSys(class_id) {
    var class_val = class_id;
    var datapass = "?remove_class=" + class_val;
    sendData1("GET", "administration/admissions.php", datapass, cObj("add_class_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                getMyClassList();
                setTimeout(() => {
                    cObj("add_class_err_handler").innerHTML = "";
                }, 10000);
                cObj("close_del_cl_win").click();
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
function getActiveHours() {
    var datapas = "?loginHours=true";
    sendData2("GET", "administration/admissions.php", datapas, cObj("active_hours"), cObj("active_list_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("active_list_clock").classList.contains("hide")) {
                //get the list of hours
                var active_hours = cObj("active_hours").innerText;
                if (active_hours.length > 0) {
                    var split_hours = active_hours.split("|");
                    cObj("from_time").value = split_hours[0];
                    cObj("to_time").value = split_hours[1];
                }
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
function activeTerms() {
    var datapass = "?academicCalender=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("acad_table"), cObj("acad_table_clock"));
}
function getAdmissionEssentials() {
    var datapass = "?get_adm_essential=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("adm_essential"), cObj("adm_essential_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("adm_essential_clock").classList.contains("hide")) {
                var adms_essent = document.getElementsByClassName("adms_essent");
                for (let index = 0; index < adms_essent.length; index++) {
                    const element = adms_essent[index];
                    element.addEventListener("click", removeAdmComp);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
function removeAdmComp() {
    var componentval = this.id.substr(4);
    var datapass = "?remove_components=true&component_rem=" + componentval;
    sendData1("GET", "administration/admissions.php", datapass, cObj("add_admission_err_handler"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                getAdmissionEssentials();
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
cObj("add_class").onclick = function () {
    cObj("add_classes_win").classList.remove("hide");
}
cObj("close_add_class_win").onclick = function () {
    cObj("add_classes_win").classList.add("hide");
}
cObj("close_add_cl_win").onclick = function () {
    cObj("add_classes_win").classList.add("hide");
}
cObj("add_class_btn").onclick = function () {
    var datapass = "?add_class=" + encodeURIComponent(cObj("input_text").value);
    sendData2("GET", "administration/admissions.php", datapass, cObj("add_class_outputtxt"), cObj("add_class_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("add_class_clock").classList.contains("hide")) {
                getMyClassList();
                cObj("add_classes_win").classList.add("hide");
                cObj("add_class_outputtxt").innerText = "";
                cObj("input_text").value = "";
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
cObj("close_active_hours1").onclick = function () {
    cObj("active_hours_window").classList.add("hide");
}
cObj("close_active_hours").onclick = function () {
    cObj("active_hours_window").classList.add("hide");
}
cObj("change_active_hrs_btn").onclick = function () {
    cObj("active_hours_window").classList.remove("hide");
}
cObj("change_active_btn").onclick = function () {
    var from = cObj("from_timer").value;
    var to = cObj("to_timer").value;
    if (from > to) {
        alert("Starting time should be earlier than ending time.");
    } else if (to > from) {
        var datapass = "?change_active_hours=true&from=" + from + "&to=" + to;
        sendData2("GET", "administration/admissions.php", datapass, cObj("outputbtn_activehours"), cObj("active_hour_clocker"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("add_class_clock").classList.contains("hide")) {
                    getActiveHours();
                    cObj("active_hours_window").classList.add("hide");
                    setTimeout(() => {
                        cObj("outputbtn_activehours").innerText = "";
                    }, 10000);
                    stopInterval(ids);
                }
            }, 100);
        }, 500);
    } else {
        alert("Starting time and ending time should not be the same.");
    }
}
cObj("next_page").onclick = function () {
    if (!cObj("term_ones").classList.contains("hide")) {
        var errs = errTerm1();
        if (errs == 0) {
            cObj("err_win_handlers").innerHTML = "";
            cObj("term_ones").classList.add("animate20");
            cObj("term_twos").classList.remove("hide");
            cObj("term_twos").classList.add("animate20");
            setTimeout(() => {
                cObj("term_ones").classList.add("hide");
                cObj("term_ones").classList.remove("animate20");
                cObj("term_twos").classList.remove("animate20");
            }, 900);
        } else {
            cObj("err_win_handlers").innerHTML = "<p class='red_notice'>Fill all fields with red border.</p>";
        }
    } else if (!cObj("term_twos").classList.contains("hide")) {
        var err = errTerm2();
        if (err == 0) {
            cObj("err_win_handlers").innerHTML = "";
            cObj("term_twos").classList.add("animate20");
            cObj("term_threes").classList.remove("hide");
            cObj("term_threes").classList.add("animate20");
            setTimeout(() => {
                cObj("term_twos").classList.add("hide");
                cObj("term_twos").classList.remove("animate20");
                cObj("term_threes").classList.remove("animate20");
                cObj("save_opts").classList.remove("hide");
            }, 900);
        } else {
            cObj("err_win_handlers").innerHTML = "<p class='red_notice'>Fill all fields with red border.</p>";
        }
    } else {
        var err = errTerm3();
        if (err == 0) {
            cObj("err_win_handlers").innerHTML = "<p class='green_notice'>Click to save your changes.</p>";
        } else {
            cObj("err_win_handlers").innerHTML = "<p class='red_notice'>Fill all fields with red border.</p>";
        }
    }
}
cObj("prev_page").onclick = function () {
    if (!cObj("term_threes").classList.contains("hide")) {
        cObj("term_threes").classList.add("animate21");
        cObj("term_twos").classList.remove("hide");
        cObj("term_twos").classList.add("animate21");
        setTimeout(() => {
            cObj("term_threes").classList.add("hide");
            cObj("term_threes").classList.remove("animate21");
            cObj("term_twos").classList.remove("animate21");
            cObj("save_opts").classList.add("hide");
        }, 900);

    } else if (!cObj("term_twos").classList.contains("hide")) {
        cObj("term_twos").classList.add("animate21");
        cObj("term_ones").classList.remove("hide");
        cObj("term_ones").classList.add("animate21");
        setTimeout(() => {
            cObj("term_twos").classList.add("hide");
            cObj("term_twos").classList.remove("animate21");
            cObj("term_ones").classList.remove("animate21");
        }, 900);
    }
}
//check term one errors
function errTerm1() {
    var err = 0;
    err += checkBlank("term_one_start");
    err += checkBlank("term_one_closing");
    err += checkBlank("term_one_end");
    return err;
}
//check term two errors
function errTerm2() {
    var err = 0;
    err += checkBlank("term_two_start");
    err += checkBlank("term_two_closing");
    err += checkBlank("term_two_end");
    return err;
}
function errTerm3() {
    var err = 0;
    err += checkBlank("term_three_start");
    err += checkBlank("term_three_closing");
    err += checkBlank("term_three_end");
    return err;
}
function resetWindow() {
    var on_win = document.getElementsByClassName("on_win");
    for (let index = 0; index < on_win.length; index++) {
        const element = on_win[index];
        element.classList.add("hide");
        element.classList.remove("animate20");
        element.classList.remove("animate21");
    }
    cObj("term_ones").classList.remove("hide");
}
cObj("close_time_tables").onclick = function () {
    cObj("acad_timetable_win").classList.add("hide");
    resetWindow();
}
cObj("close_acad_cal").onclick = function () {
    cObj("acad_timetable_win").classList.add("hide");
    resetWindow();
}
cObj("change_acad_win").onclick = function () {
    cObj("acad_timetable_win").classList.remove("hide");
}
cObj("Change_acad_cal").onclick = function () {
    var err = errTerm3();
    if (err == 0) {
        var datapass = "?update_sch_cal=true&term_one_start=" + valObj("term_one_start") + "&term_one_close=" + valObj("term_one_closing") + "&term_one_end=" + valObj("term_one_end") + "&term_two_start=" + valObj("term_two_start") + "&term_two_close=" + valObj("term_two_closing") + "&term_two_end=" + valObj("term_two_end") + "&term_three_start=" + valObj("term_three_start") + "&term_three_close=" + valObj("term_three_closing") + "&term_three_end=" + valObj("term_three_end") + "";
        sendData1("GET", "administration/admissions.php", datapass, cObj("acad_cal_errhandler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    resetWindow();
                    cObj("acad_timetable_win").classList.add("hide");
                    stopInterval(ids);
                }
            }, 100);
        }, 500);
    }
}
cObj("close_win").onclick = function () {
    cObj("admission_ess").classList.add("hide");
}
cObj("close_win_admissions").onclick = function () {
    cObj("admission_ess").classList.add("hide");
}
cObj("add_adm_ess").onclick = function () {
    cObj("admission_ess").classList.remove("hide");
}
cObj("save_comp").onclick = function () {
    //check errors
    var err = checkBlank("adm_ess");
    if (err == 0) {
        cObj("admission_essentials_err_handler").innerHTML = "";
        var datapass = "?add_admission_ess=true&component=" + valObj("adm_ess");
        sendData1("GET", "administration/admissions.php", datapass, cObj("admission_essentials_err_handler"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("admission_ess").classList.add("hide");
                    getAdmissionEssentials();
                    cObj("adm_ess").value = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 500);
    } else {
        cObj("admission_essentials_err_handler").innerHTML = "<p class = 'red_notice'>Fill all the fields covered with red border!</p>";
    }
}
cObj("display_loggers").onclick = function () {
    var err = checkBlank("date_logs");
    if (err == 0) {
        var datapass = "?date_logs=" + valObj("date_logs");
        sendData2("GET", "administration/admissions.php", datapass, cObj("loggers_table_before"), cObj("logger_clock"));
    } else {
        cObj("loggers_table_before").innerHTML = "<p class='red_notice'>Select a date to display logs!</p>";
    }
}
cObj("sys_username").onblur = function () {
    //get if username was used
    if (this.value.length > 0) {
        var datapass = "?usernames_value=" + this.value;
        sendData2("GET", "administration/admissions.php", datapass, cObj("check_me_username"), cObj("ch_uname_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("ch_uname_clock").classList.contains("hide")) {
                    if (cObj("check_me_username").innerText.length > 0) {
                        redBorder(this);
                    } else {
                        grayBorder(this);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}
cObj("close_change_dp").onclick = function () {
    cObj("change_dp_win").classList.add("hide");
}
cObj("change_dp_btns").onclick = function () {
    cObj("change_dp_win").classList.remove("hide");
}
cObj("change_my_dp_img").onclick = function () {
    var err = checkBlank("dp_image");
    if (err == 0) {
        var filepath = cObj("dp_image").value.split(".")[1];
        if (filepath == "jpeg" || filepath == "png" || filepath == "jpg" || filepath == "gif") {
            //create an xml request to upload the image into the server
            var done = 0;
            const xhr = new XMLHttpRequest();
            const formdata = new FormData();
            cObj("insert_images").classList.remove("hide");
            for (const fills of cObj("dp_image").files) {
                formdata.append("myFiles[]", fills);
            }
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    cObj("imagenotifier").innerHTML = "<p style='color:green; font-size:12px;'>Image Uploaded successfully!</p>";
                    //cObj("imagenotifier").innerHTML = this.responseText;
                    done = 1;
                    cObj("insert_images").classList.add("hide");
                }
            }

            xhr.open("POST", "ajax/image_upload/change_dp.php");
            xhr.send(formdata);

            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (done == 1) {
                        //change the location of the dp in the database
                        var datapass = "?change_dp_local=true";
                        sendData1("GET", "administration/admissions.php", datapass, cObj("dp_err_handler"));
                        setTimeout(() => {
                            var timeout = 0;
                            var id23w = setInterval(() => {
                                timeout++;
                                //after two minutes of slow connection the next process wont be executed
                                if (timeout == 1200) {
                                    stopInterval(id23w);
                                }
                                if (cObj("loadings").classList.contains("hide")) {
                                    changeDpLocale();
                                    cObj("change_dp_win").classList.add("hide");
                                    cObj("dp_image").value = "";
                                    stopInterval(id23w);
                                }
                            }, 100);
                        }, 200);
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
        } else {
            alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            cObj("dp_image").value = "";
        }
    } else {
        alert("select an image to proceed!");
    }
}
function changeDpLocale() {
    var datapass = "?getImages_dp=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("dps_images"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var image_local = cObj("dps_images").innerText;
                if (image_local.length > 0) {
                    cObj("open_more").src = image_local;
                    cObj("dpimage-sett").src = image_local;
                    cObj("user_imged").src = image_local;
                    var auth = cObj("authoriti").value;
                    if (auth == 1) {
                        cObj("ht_dp_img").src = image_local;
                    } else if (auth == 5) {
                        cObj("ct_admin_dp").src = image_local;
                    } else if (auth == 3) {
                        cObj("dp_dash_dp").src = image_local;
                    } else if (auth == 2) {
                        cObj("tr_dash_dp").src = image_local;
                    } else if (auth == 0) {
                        cObj("admin_admin_dp").src = image_local;
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("close_sch_change_dp").onclick = function () {
    cObj("change_sch_dp_win").classList.add("hide");
}
cObj("change_sch_dp").onclick = function () {
    cObj("change_sch_dp_win").classList.remove("hide");
}
cObj("change_studes_dp_img").onclick = function () {
    var err = checkBlank("students_image");
    if (err == 0) {
        var filepath = cObj("students_image").value.split(".")[1].toLowerCase();
        if (filepath == "jpeg" || filepath == "png" || filepath == "jpg" || filepath == "jpeg" || filepath == "gif") {
            cObj("student_dp_loader").classList.remove("hide");
            cObj("close_studes_change_dp").click();
            //create an xml request to upload the image into the server
            var done = 0;
            const xhr = new XMLHttpRequest();
            const formdata = new FormData();
            for (const fills of cObj("students_image").files) {
                formdata.append("myFiles[]", fills);
            }
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    cObj("dp_locale").innerHTML = "<p style='color:green; font-size:12px;'>Image Uploaded successfully!</p>";
                    // cObj("imagenotifiered_studes").innerHTML = this.responseText;
                    done = 1;
                }
            }
            formdata.append("admission_no", cObj("adminnos").value);
            xhr.open("POST", "ajax/image_upload/change_stud_dp.php");
            xhr.send(formdata);

            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (done == 1) {
                        cObj("student_dp_loader").classList.add("hide");
                        getDP();
                        cObj("students_image").value = "";
                        setTimeout(() => {
                            cObj("dp_locale").innerHTML = "";
                        }, 3000);
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
        } else {
            alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            cObj("students_image").value = "";
        }
    }
}

function getDP() {
    var datapass = "?get_profile_image=true&admissions_no=" + cObj("adminnos").value;
    sendData2("GET", "administration/admissions.php", datapass, cObj("dp_local_stud"), cObj("student_dp_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("student_dp_loader").classList.contains("hide")) {
                if (cObj("dp_local_stud").innerText != "Null") {
                    var exists = UrlExists(cObj("dp_local_stud").innerText);
                    if (exists) {
                        cObj("student_image").src = cObj("dp_local_stud").innerText;
                    } else {
                        cObj("student_image").src = "images/dp.png";
                    }
                } else {
                    cObj("student_image").src = "images/dp.png";
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
    // console.log("Image changed "+cObj("dp_local_stud").innerText);
}

cObj("change_sch_dp_img").onclick = function () {
    var err = checkBlank("school_dp");
    if (err == 0) {
        var filepath = cObj("school_dp").value.split(".")[1];
        if (filepath == "jpeg" || filepath == "png" || filepath == "jpg" || filepath == "gif") {
            //create an xml request to upload the image into the server
            var done = 0;
            const xhr = new XMLHttpRequest();
            const formdata = new FormData();
            for (const fills of cObj("school_dp").files) {
                formdata.append("myFiles[]", fills);
            }
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    cObj("imagenotifiered").innerHTML = "<p style='color:green; font-size:12px;'>Image Uploaded successfully!</p>";
                    //cObj("imagenotifiered").innerHTML = this.responseText;
                    done = 1;
                }
            }

            xhr.open("POST", "ajax/image_upload/change_sch_dp.php");
            xhr.send(formdata);

            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (done == 1) {
                        //change the location of the dp in the database
                        var datapass = "?change_dp_school=true";
                        sendData1("GET", "administration/admissions.php", datapass, cObj("imagenotifiered"));
                        setTimeout(() => {
                            var timeout = 0;
                            var iddd = setInterval(() => {
                                timeout++;
                                //after two minutes of slow connection the next process wont be executed
                                if (timeout == 1200) {
                                    stopInterval(iddd);
                                }
                                if (cObj("loadings").classList.contains("hide")) {
                                    cObj("change_sch_dp_win").classList.add("hide");
                                    changeSchoolDpLocale();
                                    stopInterval(iddd);
                                }
                            }, 100);
                        }, 200);
                        stopInterval(ids);
                    }
                }, 100);
            }, 500);
        } else {
            alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            cObj("school_dp").value = "";
        }
    }
}
function changeSchoolDpLocale() {
    var datapass = "?bring_me_sch_dp=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("sch_dp_images"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var image_local = cObj("sch_dp_images").innerText;
                if (image_local.length > 0) {
                    cObj("sch_logos").src = image_local;
                    cObj("sch_logos2").src = image_local;
                    document.getElementById("images_bgs").style.backgroundImage = "url(" + image_local + ")";
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("close_img_viewer").onclick = function () {
    cObj("imagers").classList.remove("image_view");
    cObj("imagers").classList.add("hide");
}
cObj("dpimage-sett").onclick = function () {
    cObj("image_viewer").src = cObj("dpimage-sett").src;
    cObj("imagers").classList.add("image_view");
    cObj("imagers").classList.remove("hide");
}
cObj("sch_logos2").onclick = function () {
    cObj("image_viewer").src = cObj("sch_logos2").src;
    cObj("imagers").classList.add("image_view");
    cObj("imagers").classList.remove("hide");
}
cObj("suggestion_box").onkeyup = function () {
    var len = this.value.length;
    cObj("count_char").innerText = len;
}
cObj("send-feedback_btns").onclick = function () {
    var err = checkBlank("suggestion_box");
    if (err == 0) {
        cObj("err_handlered").innerHTML = "";
        var datapass = "?feedback_message=" + valObj("suggestion_box");
        sendData2("GET", "administration/admissions.php", datapass, cObj("err_handlered"), cObj("feedback-clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("feedback-clock").classList.contains("hide")) {
                    cObj("suggestion_box").value = "";
                    setTimeout(() => {
                        cObj("err_handlered").innerHTML = "";
                    }, 10000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("err_handlered").innerHTML = "<p class = 'red_notice'>Write what you think in the box above!</p>";
    }
}
cObj("display_attendance_class").onclick = function () {
    var err = checkBlank("sel_att_date");
    if (err == 0) {
        cObj("err_date_handled").innerHTML = "";
        var datapass = "?get_attendance_school=true&dated=" + valObj("sel_att_date");
        sendData1("GET", "administration/admissions.php", datapass, cObj("tableinformation"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    var view_stud_attendance = document.getElementsByClassName("view_stud_attendance");
                    for (let index = 0; index < view_stud_attendance.length; index++) {
                        const element = view_stud_attendance[index];
                        element.addEventListener("click", viewClassAttend);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("err_date_handled").innerHTML = "<p class='red_notice'>Select a date!</p>";
    }
}
function viewClassAttend() {
    var daro = this.id;
    var date = valObj("sel_att_date");
    var datapass = "?class=" + daro + "&dates=" + date;
    cObj("hidden_class_selected").value = daro;
    //showPleasewait();
    sendData1("GET", "administration/admissions.php", datapass, cObj("atendanceinfor"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                //removePleasewait();
                stopInterval(ids);
            }
        }, 100);
    }, 500);
    cObj("view_attendances").classList.remove("hide");
    cObj("mains").classList.add("hide");
}

//allow the ct to admit students only in their class
function allowCTadmit() {
    //get the value from the database
    var datapass = "?allowct=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("allow_ct_err_handler"), cObj("allow_ct_reg_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("allow_ct_reg_clock").classList.contains("hide")) {
                //removePleasewait();
                if (cObj("allow_ct_err_handler").innerText == "Yes") {
                    cObj("yes_opt_in1").selected = true;
                } else if (cObj("allow_ct_err_handler").innerText == "No") {
                    cObj("no_opt_in1").selected = true;
                }
                cObj("allow_ct_err_handler").innerText = "";
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
cObj("change_btns_inside").onclick = function () {
    var datapass = "?update_ct=true&ct_cg_value=" + cObj("optioms_todo").value;
    sendData2("GET", "administration/admissions.php", datapass, cObj("allow_ct_err_handler"), cObj("allow_ct_reg_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("allow_ct_reg_clock").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("allow_ct_err_handler").innerText = "";
                }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 500);
}
cObj("automated_amd").onchange = function () {
    var selval = this.value;
    if (selval == "automate_adm") {
        cObj("auto_generate").classList.remove("hide");
        cObj("man_generate").classList.add("hide");
        //generate an admission number thats not used
        var datapass = "?generate_adm_auto=true";
        sendData2("GET", "administration/admissions.php", datapass, cObj("admnogenerated"), cObj("autogenamds"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("autogenamds").classList.contains("hide")) {
                    cObj("autogen").value = cObj("admnogenerated").innerText;
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    } else if (selval == "insertmanually") {
        cObj("man_generate").classList.remove("hide");
        cObj("auto_generate").classList.add("hide");
    }
}

cObj("mangen").onkeyup = function () {
    //get if the admission number is used already
    var datapass = "?genmanuall=true&admno=" + this.value;
    // alert(datapass);
    sendData2("GET", "administration/admissions.php", datapass, cObj("admgenman"), cObj("manualassign"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("manualassign").classList.contains("hide")) {
                if (cObj("admgenman").innerText.length > 0) {
                    redBorder(this);
                } else {
                    grayBorder(this);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function displayWholeSchool() {
    // here we get the students to display in the whole school
    var datapass = "?getWholeSchool=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("wholeSchool"), cObj("loader55"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loader55").classList.contains("hide")) {
                // assign the class a promote button an assignment
                var promoteclass = document.getElementsByClassName("promoteclass");
                for (let index = 0; index < promoteclass.length; index++) {
                    const element = promoteclass[index];
                    element.addEventListener("click", promoteClass);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
function promoteClass() {
    var datapass = "?getclassData=true&classname=" + this.id.substr(2);
    sendData2("GET", "administration/admissions.php", datapass, cObj("wholeSchool"), cObj("loader55"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loader55").classList.contains("hide")) {
                // assign the class a promote button an assignment
                cObj("goBack3").onclick = function () {
                    cObj("promoteStd").click();
                }

                // select all
                if (cObj("promoSelect") != null) {
                    cObj("promoSelect").onclick = function () {
                        if (this.checked == true) {
                            var promotionCheck = document.getElementsByClassName("promotionCheck");
                            for (let index = 0; index < promotionCheck.length; index++) {
                                const element = promotionCheck[index];
                                element.checked = true;
                            }
                        } else {
                            var promotionCheck = document.getElementsByClassName("promotionCheck");
                            for (let index = 0; index < promotionCheck.length; index++) {
                                const element = promotionCheck[index];
                                element.checked = false;
                            }
                        }
                    }
                }
                if (cObj("promoteStudents") != null) {
                    cObj("promoteStudents").onclick = function () {
                        // get the students id to promote and the class they are in
                        var studentsSelected = "";
                        var unselected = "";
                        var promotionCheck = document.getElementsByClassName("promotionCheck");
                        for (let index = 0; index < promotionCheck.length; index++) {
                            const element = promotionCheck[index];
                            if (element.checked == true) {
                                studentsSelected += element.id.substring(5) + ",";
                            } else {
                                unselected += element.id.substring(5) + ",";
                            }
                        }
                        studentsSelected = studentsSelected.substring(0, studentsSelected.length - 1);
                        unselected = unselected.substring(0, unselected.length - 1);
                        if (studentsSelected.length > 0) {
                            cObj("errHandler44").innerHTML = "";
                            var datapass = "?promote=true&selectedStd=" + studentsSelected + "&classselected=" + cObj("theClass").value + "&unselected=" + unselected;
                            sendData2("GET", "administration/admissions.php", datapass, cObj("errHandler44"), cObj("loader55"));
                            setTimeout(() => {
                                var timeout = 0;
                                var ids = setInterval(() => {
                                    timeout++;
                                    //after two minutes of slow connection the next process wont be executed
                                    if (timeout == 1200) {
                                        stopInterval(ids);
                                    }
                                    if (cObj("loader55").classList.contains("hide")) {
                                        // assign the class a promote button an assignment
                                        setTimeout(() => {
                                            cObj("goBack3").click();
                                        }, 2000);
                                        stopInterval(ids);
                                    }
                                }, 100);
                            }, 100);
                            // console.log(datapass);
                        } else {
                            cObj("errHandler44").innerHTML = "<span class='text-danger'>Select atleast one student to promote!</span>";
                            setTimeout(() => {
                                cObj("errHandler44").innerHTML = "";
                            }, 5000);
                        }
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
// user roles
cObj("add_user_type").onclick = function () {
    cObj("add_user_role_window").classList.remove("hide");
}
cObj("cancel_role_btn").onclick = function () {
    cObj("add_user_role_window").classList.add("hide");
}
function administration_check() {
    var classin = document.getElementsByClassName("administration1");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_administration").checked = true;
    } else {
        cObj("all_administration").checked = false;
    }
}

function finance_check() {
    var classin = document.getElementsByClassName("finance1");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_finance_sect").checked = true;
    } else {
        cObj("all_finance_sect").checked = false;
    }
}
function route_check() {
    var classin = document.getElementsByClassName("routesnvans1");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("route_transport_section").checked = true;
    } else {
        cObj("route_transport_section").checked = false;
    }
}
function academic_check() {
    var classin = document.getElementsByClassName("academic_sect");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("academic_section").checked = true;
    } else {
        cObj("academic_section").checked = false;
    }
}
function boarding_check() {
    var classin = document.getElementsByClassName("boarding_sect");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_boarding_section").checked = true;
    } else {
        cObj("all_boarding_section").checked = false;
    }
}
function all_sms_check() {
    var classin = document.getElementsByClassName("sms_broadcasted");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_sms_check").checked = true;
    } else {
        cObj("all_sms_check").checked = false;
    }
}
function all_account_settings() {
    var classin = document.getElementsByClassName("accounts_section");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("accounts_sector").checked = true;
    } else {
        cObj("accounts_sector").checked = false;
    }
}
/***HEY DONT CONFUSE UP AND DOWN */
function administration_check2() {
    var classin = document.getElementsByClassName("administration12");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_administration2").checked = true;
    } else {
        cObj("all_administration2").checked = false;
    }
}

function finance_check2() {
    var classin = document.getElementsByClassName("finance12");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_finance_sect2").checked = true;
    } else {
        cObj("all_finance_sect2").checked = false;
    }
}
function route_check2() {
    var classin = document.getElementsByClassName("routesnvans12");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("route_transport_section2").checked = true;
    } else {
        cObj("route_transport_section2").checked = false;
    }
}
function academic_check2() {
    var classin = document.getElementsByClassName("academic_sect2");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("academic_section2").checked = true;
    } else {
        cObj("academic_section2").checked = false;
    }
}
function boarding_check2() {
    var classin = document.getElementsByClassName("boarding_sect2");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_boarding_section2").checked = true;
    } else {
        cObj("all_boarding_section2").checked = false;
    }
}
function all_sms_check2() {
    var classin = document.getElementsByClassName("sms_broadcasted2");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("all_sms_check2").checked = true;
    } else {
        cObj("all_sms_check2").checked = false;
    }
}
function all_account_settings2() {
    var classin = document.getElementsByClassName("accounts_section2");
    var count = 0;
    for (let index = 0; index < classin.length; index++) {
        const element = classin[index];
        if (element.checked == true) {
            count++;
        }
    }
    if (count == classin.length) {
        cObj("accounts_sector2").checked = true;
    } else {
        cObj("accounts_sector2").checked = false;
    }
}
/****ENDS HERE */

cObj("all_administration").onchange = function () {
    var mychecks = document.getElementsByClassName("administration1");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}

cObj("all_finance_sect").onchange = function () {
    var mychecks = document.getElementsByClassName("finance1");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("route_transport_section").onchange = function () {
    var mychecks = document.getElementsByClassName("routesnvans1");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("academic_section").onchange = function () {
    var mychecks = document.getElementsByClassName("academic_sect");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("all_boarding_section").onchange = function () {
    var mychecks = document.getElementsByClassName("boarding_sect");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("all_sms_check").onchange = function () {
    var mychecks = document.getElementsByClassName("sms_broadcasted");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("accounts_sector").onchange = function () {
    var mychecks = document.getElementsByClassName("accounts_section");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
/**HEY HEYE HEYE HEY */
/**HEY DONT GET CONFUSED HERE THE UPPER CODE IS DUPLICATE AS DOWN HERE */
cObj("all_administration2").onchange = function () {
    var mychecks = document.getElementsByClassName("administration12");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}

cObj("all_finance_sect2").onchange = function () {
    var mychecks = document.getElementsByClassName("finance12");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("route_transport_section2").onchange = function () {
    var mychecks = document.getElementsByClassName("routesnvans12");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("academic_section2").onchange = function () {
    var mychecks = document.getElementsByClassName("academic_sect2");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("all_boarding_section2").onchange = function () {
    var mychecks = document.getElementsByClassName("boarding_sect2");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("all_sms_check2").onchange = function () {
    var mychecks = document.getElementsByClassName("sms_broadcasted2");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
cObj("accounts_sector2").onchange = function () {
    var mychecks = document.getElementsByClassName("accounts_section2");
    if (this.checked == true) {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = true;
        }
    } else {
        for (let index = 0; index < mychecks.length; index++) {
            const element = mychecks[index];
            element.checked = false;
        }
    }
}
/**ENDS HERE BRUH */

function getStaffRole() {
    var role = "[";
    var status = cObj("admit_student_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"admitbtn\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_stud_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"findstudsbtn\",\"Status\":\"" + status + "\"},"
    status = cObj("class_attendance_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"callregister\",\"Status\":\"" + status + "\"},"
    status = cObj("register_staff_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"regstaffs\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_staff_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"managestaf\",\"Status\":\"" + status + "\"},"
    status = cObj("promote_students_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"promoteStd\",\"Status\":\"" + status + "\"},"
    // human resource changed 21st nov 2022
    status = cObj("human_resource_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"humanresource\",\"Status\":\"" + status + "\"},"
    // ends here
    status = cObj("pay_fees-sector").checked == true ? "yes" : "no";
    role += "{\"name\":\"payfeess\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_transaction_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"findtrans\",\"Status\":\"" + status + "\"},"
    status = cObj("mpesa_transaction_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"mpesaTrans\",\"Status\":\"" + status + "\"},"
    status = cObj("fees_structures_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"feestruct\",\"Status\":\"" + status + "\"},"
    status = cObj("expense_section").checked == true ? "yes" : "no";
    role += "{\"name\":\"expenses_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("financial_report_section").checked == true ? "yes" : "no";
    role += "{\"name\":\"finance_report_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("payroll_section").checked == true ? "yes" : "no";
    role += "{\"name\":\"payroll_sys\",\"Status\":\"" + status + "\"},"
    status = cObj("route_n_van_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"routes_n_trans\",\"Status\":\"" + status + "\"},"
    status = cObj("enroll_students_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"enroll_students\",\"Status\":\"" + status + "\"},"
    status = cObj("register_subject_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"regsub\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_subject_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"managesub\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_teacher_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"managetrnsub\",\"Status\":\"" + status + "\"},"
    status = cObj("timetables_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"generate_tt_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("exam_management_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"examanagement\",\"Status\":\"" + status + "\"},"
    status = cObj("student_marks_entry").checked == true ? "yes" : "no";
    role += "{\"name\":\"exam_fill_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("enroll_boarding_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"enroll_boarding_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_dormitory_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"maanage_dorm\",\"Status\":\"" + status + "\"},"
    status = cObj("sms_and_broadcast").checked == true ? "yes" : "no";
    role += "{\"name\":\"sms_broadcast\",\"Status\":\"" + status + "\"},"
    status = cObj("update_school_profile_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"update_school_profile\",\"Status\":\"" + status + "\"},"
    status = cObj("update_personal_profile_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"update_personal_profile\",\"Status\":\"" + status + "\"},"
    status = cObj("settings_sect").checked == true ? "yes" : "no";
    role += "{\"name\":\"set_btns\",\"Status\":\"" + status + "\"},";
    status = cObj("my_school_reports").checked == true ? "yes" : "no";
    role += "{\"name\":\"my_reports\",\"Status\":\"" + status + "\"}]";
    return role;
}

/**Hey dont confuse this function to the one above */

function getStaffRole2(role_index, role_name) {
    var role = "[";
    var status = cObj("admit_student_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"admitbtn\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_stud_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"findstudsbtn\",\"Status\":\"" + status + "\"},"
    status = cObj("class_attendance_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"callregister\",\"Status\":\"" + status + "\"},"
    status = cObj("register_staff_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"regstaffs\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_staff_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"managestaf\",\"Status\":\"" + status + "\"},"
    status = cObj("promote_students_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"promoteStd\",\"Status\":\"" + status + "\"},"
    // human resource changed 21st nov 2022
    status = cObj("human_resource_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"humanresource\",\"Status\":\"" + status + "\"},"
    // ends here
    status = cObj("pay_fees-sector2").checked == true ? "yes" : "no";
    role += "{\"name\":\"payfeess\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_transaction_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"findtrans\",\"Status\":\"" + status + "\"},"
    status = cObj("mpesa_transaction_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"mpesaTrans\",\"Status\":\"" + status + "\"},"
    status = cObj("fees_structures_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"feestruct\",\"Status\":\"" + status + "\"},"
    status = cObj("expense_section2").checked == true ? "yes" : "no";
    role += "{\"name\":\"expenses_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("financial_report_section2").checked == true ? "yes" : "no";
    role += "{\"name\":\"finance_report_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("payroll_section2").checked == true ? "yes" : "no";
    role += "{\"name\":\"payroll_sys\",\"Status\":\"" + status + "\"},"
    status = cObj("route_n_van_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"routes_n_trans\",\"Status\":\"" + status + "\"},"
    status = cObj("enroll_students_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"enroll_students\",\"Status\":\"" + status + "\"},"
    status = cObj("register_subject_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"regsub\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_subject_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"managesub\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_teacher_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"managetrnsub\",\"Status\":\"" + status + "\"},"
    status = cObj("timetables_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"generate_tt_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("exam_management_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"examanagement\",\"Status\":\"" + status + "\"},"
    status = cObj("student_marks_entry2").checked == true ? "yes" : "no";
    role += "{\"name\":\"exam_fill_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("enroll_boarding_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"enroll_boarding_btn\",\"Status\":\"" + status + "\"},"
    status = cObj("manage_dormitory_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"maanage_dorm\",\"Status\":\"" + status + "\"},"
    status = cObj("sms_and_broadcast2").checked == true ? "yes" : "no";
    role += "{\"name\":\"sms_broadcast\",\"Status\":\"" + status + "\"},"
    status = cObj("update_school_profile_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"update_school_profile\",\"Status\":\"" + status + "\"},"
    status = cObj("update_personal_profile_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"update_personal_profile\",\"Status\":\"" + status + "\"},"
    status = cObj("settings_sect2").checked == true ? "yes" : "no";
    role += "{\"name\":\"set_btns\",\"Status\":\"" + status + "\"},";
    status = cObj("my_school_reports2").checked == true ? "yes" : "no";
    role += "{\"name\":\"my_reports\",\"Status\":\"" + status + "\"}]";

    var data_in = cObj("show_roles").innerText;
    var roles_upload = "[";
    if (data_in.length > 0) {
        var object = JSON.parse(data_in);
        for (let index = 0; index < object.length; index++) {
            const element = object[index];
            if (index == role_index) {
                roles_upload += "{\"name\":\"" + role_name + "\",\"roles\":" + role + "},";
            } else {
                roles_upload += JSON.stringify(element) + ",";
            }
        }
        roles_upload = roles_upload.substring(0, (roles_upload.length - 1)) + "]";
    }
    return roles_upload;
}
/**IT END HERE */
cObj("add_role_btns").onclick = function () {
    var role = getStaffRole();
    var err = checkBlank("role_name");
    if (err < 1) {
        cObj("allowance_err3_handler").innerHTML = "";
        var datapass = "?add_another_user=true&role_name=" + encodeURIComponent(valObj("role_name")) + "&role_doing=" + encodeURIComponent(role);
        sendData2("GET", "academic/academic.php", datapass, cObj("allowance_err3_handler"), cObj("add_user_roles_in"));
        setTimeout(() => {
            cObj("cancel_role_btn").click();
            cObj("allowance_err3_handler").innerHTML = "";
            cObj("role_name").value = "";
            cObj("set_btns").click();
        }, 1000);

    } else {
        cObj("allowance_err3_handler").innerHTML = "<p class='text-danger'>Fill all fields covered with red borders</p>";
    }
}
cObj("add_role_btns2").onclick = function () {
    var role_index = cObj("role_ids_in").innerText;
    var err = checkBlank("role_name2");
    if (err < 1) {
        cObj("allowance_err4_handler").innerHTML = "";
        var role = getStaffRole2(role_index, valObj("role_name2"));
        var datapass = "edit_another_user=true&role_name=" + encodeURIComponent(valObj("role_name2")) + "&old_role_name=" + encodeURIComponent(cObj("old_role_name").innerText) + "&role_values=" + encodeURIComponent(role);
        sendDataPost("POST", "ajax/academic/academic.php", datapass, cObj("allowance_err4_handler"), cObj("add_user_roles_in2"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("add_user_roles_in2").classList.contains("hide")) {
                    cObj("cancel_role_btn2").click();
                    cObj("allowance_err4_handler").innerHTML = "";
                    cObj("role_name2").value = "";
                    cObj("set_btns").click();
                    stopInterval(ids);
                }
            }, 100);
        }, 100);

    } else {
        cObj("allowance_err4_handler").innerHTML = "<p class='text-danger'>Fill all fields covered with red borders</p>";
    }
}
function getRoleData() {
    var datapass = "?get_user_roles=true";
    sendData2("GET", "academic/academic.php", datapass, cObj("show_roles"), cObj("load_roles"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_roles").classList.contains("hide")) {
                // get the data in the object and decipher it
                var data = cObj("show_roles").innerText;
                if (data.length > 0) {
                    var obje = JSON.parse(data);
                    var data_to_display = "<table class='table'><tr><th>No.</th><th>Role Name</th><th>Options</th></tr>";
                    for (let index = 0; index < obje.length; index++) {
                        const element = obje[index];
                        // get the element data and display as a table
                        data_to_display += "<tr><td>" + (index + 1) + "</td><td>" + element.name + "</td><td><span class='link edit_role_' id='edit_role_" + index + "' ><i class='fa fa-pen'></i> Edit</span> <span class='link ml-2 delete_roles' id='delete_roles" + index + "'><i class='fa fa-trash'></i> Delete</span></td></tr>";

                    }
                    data_to_display += "</table>";
                    cObj("roles_holder").innerHTML = data_to_display;
                } else {
                    cObj("roles_holder").innerHTML = "<p class='text-success'>There are no roles added to the system currently!</p>";
                }
                var edit_role_ = document.getElementsByClassName("edit_role_");
                for (let index = 0; index < edit_role_.length; index++) {
                    const element = edit_role_[index];
                    element.addEventListener("click", editRoleListener);
                }
                var delete_roles = document.getElementsByClassName("delete_roles");
                for (let index = 0; index < delete_roles.length; index++) {
                    const element = delete_roles[index];
                    element.addEventListener("click", deleteRoles_Present);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function editRoleListener() {
    var ids = this.id.substring(("edit_role_".length));
    var data = cObj("show_roles").innerText;
    var object = JSON.parse(data);
    var obj = object[ids];
    // create a window to display the data
    cObj("add_user_role_window2").classList.remove("hide");
    var fill_data = document.getElementsByClassName("fill_data");
    var data2 = obj.roles;
    for (let index = 0; index < data2.length; index++) {
        const element = data2[index];
        if (element.Status == "yes") {
            fill_data[index].checked = true;
        } else {
            fill_data[index].checked = false;
        }
    }
    cObj("role_ids_in").innerText = ids;
    cObj("role_name2").value = obj.name;
    cObj("old_role_name").innerText = obj.name;
    administration_check2();
    finance_check2();
    route_check2();
    academic_check2();
    boarding_check2();
    all_sms_check2();
    all_account_settings2();
}
cObj("cancel_role_btn2").onclick = function () {
    cObj("add_user_role_window2").classList.add("hide");
}
function deleteRoles_Present() {
    // show the delet confirmation window
    var ids = this.id.substring("delete_roles".length);
    cObj("remove_roles_windows").classList.remove("hide");
    cObj("index_to_delete").innerText = ids;
}
cObj("confirmno_roled").onclick = function () {
    cObj("remove_roles_windows").classList.add("hide");
}
cObj("confirmyes_roled").onclick = function () {
    var role_index = cObj("index_to_delete").innerText;
    var roles_n_user = cObj("show_roles").innerText;
    if (roles_n_user.length > 0) {
        cObj("cancel_role_btn2").click()
        var object = JSON.parse(roles_n_user);
        var data_to_upload = "[";
        var role_name = "";
        var counted = 0;
        for (let index = 0; index < object.length; index++) {
            const element = object[index];
            if (index != role_index) {
                data_to_upload += JSON.stringify(element) + ",";
                counted++;
            } else {
                role_name = element.name;
            }
        }
        data_to_upload = data_to_upload.substring(0, (data_to_upload.length - 1)) + "]";
        if (counted > 0) {
            // send data to be uploaded
            var datapass = "delete_roles=" + role_name + "&raw_data=" + data_to_upload;
            sendDataPost("POST", "ajax/academic/academic.php", datapass, cObj("roles_errors"), cObj("load_roles"));
            cObj("confirmno_roled").click();
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("load_roles").classList.contains("hide")) {
                        cObj("set_btns").click()
                        setTimeout(() => {
                            cObj("roles_errors").innerText = "";
                        }, 4000);
                        stopInterval(ids);
                    }
                }, 100);
            }, 100);
        } else {
            data_to_upload = "";
            // send data to be uploaded
            var datapass = "?delete_roles=" + role_name + "&raw_data=" + data_to_upload;
            sendData2("GET", "academic/academic.php", datapass, cObj("roles_errors"), cObj("load_roles"));
            cObj("confirmno_roled").click();
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("load_roles").classList.contains("hide")) {
                        cObj("set_btns").click()
                        setTimeout(() => {
                            cObj("roles_errors").innerText = "";
                        }, 4000);
                        stopInterval(ids);
                    }
                }, 100);
            }, 100);
        }
    } else {
        cObj("confirmno_roled").click();
    }
}

function getStaff_roles() {
    var datapass = "?staff_roles=true";
    sendData2("GET", "academic/academic.php", datapass, cObj("role_data_2322"), cObj("load_roles2"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_roles2").classList.contains("hide")) {
                var data_to_display = "<select  class='form-control' style='width: 90%;' name='authority' id='authority'><option value='' hidden>Select Role</option><option value='0'>System Administrator</option><option value='1'>Principal</option><option value='2'>Deputy Principal Academics</option><option value='3'>Deputy Principal Administration</option><option value='4'>Dean of students</option><option value='5'>Finance officer</option><option value='6'>Human resource officer</option><option value='7'>Head of department</option><option value='8'>Trainer/Lecturer</option><option value='9'>Admissions</option>";
                // console.log(cObj("role_data_23").innerText);
                var data_in = cObj("role_data_2322").innerText.length;
                if (data_in > 0) {
                    var data = cObj("role_data_2322").innerText;
                    var object = JSON.parse(data);
                    for (let index = 0; index < object.length; index++) {
                        const element = object[index];
                        var majina = element.name;
                        data_to_display += "<option style='color:blue;' value='" + majina + "'>" + ucwords(majina) + "</option>";
                    }
                }
                data_to_display += "</select>";
                cObj("other_roles_inside").innerHTML = data_to_display;

                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function getStaff_roles_maanage() {
    var datapass = "?staff_roles=true";
    sendData2("GET", "academic/academic.php", datapass, cObj("staff_detail_out"), cObj("load_roles43"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_roles43").classList.contains("hide")) {
                // var data_to_display = "<select  class='form-control' style='width: 90%;' name='auths' id='auths'><option class='staff_role_class' value='' hidden>Select..</option><option class='staff_role_class' value='0'>Administrator</option><option class='staff_role_class' value='1'>Headteacher/Principal</option><option class='staff_role_class' value='3'>Deputy principal</option><option class='staff_role_class' value='2'>Teacher</option><option class='staff_role_class' value='5'>Class teacher</option><option class='staff_role_class' value='6'>School Driver</option>";
                var data_to_display = "<select  class='form-control' style='width: 90%;' name='auths' id='auths'><option value='' hidden>Select Role</option><option value='0'>System Administrator</option><option value='1'>Principal</option><option value='2'>Deputy Principal Academics</option><option value='3'>Deputy Principal Administration</option><option value='4'>Dean of students</option><option value='5'>Finance officer</option><option value='6'>Human resource officer</option><option value='7'>Head of department</option><option value='8'>Trainer/Lecturer</option><option value='9'>Admissions</option>";
                // console.log(cObj("role_data_23").innerText);
                var data_in = cObj("staff_detail_out").innerText.length;
                if (data_in > 0) {
                    var data = cObj("staff_detail_out").innerText;
                    var object = JSON.parse(data);
                    for (let index = 0; index < object.length; index++) {
                        const element = object[index];
                        var majina = element.name;
                        data_to_display += "<option style='color:blue;' class='staff_role_class' value='" + majina + "'>" + ucwords(majina) + "</option>";
                    }
                }
                data_to_display += "</select>";
                cObj("data_in_display").innerHTML = data_to_display;

                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
cObj("canc_add_prev_sch_btn").onclick = function () {
    cObj("previous_schools_windows").classList.add("hide");
}
cObj("prev_school").onclick = function () {
    cObj("previous_schools_windows").classList.remove("hide");
    cObj("prev_school_name").value = "";
    cObj("date_left").value = "";
    cObj("marks_scored").value = "";
    cObj("leaving_certifcate").checked = false;
    cObj("description").value = "";
    cObj("add_prevsch_error").innerHTML = "";

    grayBorder(cObj("prev_school_name"));
    grayBorder(cObj("date_left"));
    grayBorder(cObj("marks_scored"));

}

cObj("add_prev_sch_btn").onclick = function () {
    // record the schools absent
    var prev_school_name = cObj("prev_school_name").value;
    var date_left = cObj("date_left").value;
    var marks_scored = cObj("marks_scored").value;
    var leaving_certifcate = cObj("leaving_certifcate").checked;
    var description = cObj("description").value;

    var err = checkBlank("prev_school_name");
    err += checkBlank("date_left");
    err += checkBlank("marks_scored");
    if (err > 0) {
        cObj("add_prevsch_error").innerHTML = "<p class='text-danger'>Please fill all the fields with a red border.</p>";
    } else {
        cObj("add_prevsch_error").innerHTML = "";
        // proceed and add the information to the list
        var text = '[{"school_name":"' + prev_school_name + '","date_left":"' + date_left + '","marks_scored":"' + marks_scored + '","leaving_cert":"' + leaving_certifcate + '","reason_for_leaving":"' + description + '"}]';
        var available_txt = cObj("previous_schools").innerText;
        if (available_txt.length > 0) {
            text = '{"school_name":"' + prev_school_name + '","date_left":"' + date_left + '","marks_scored":"' + marks_scored + '","leaving_cert":"' + leaving_certifcate + '","reason_for_leaving":"' + description + '"}';
            available_txt = available_txt.substring(0, available_txt.length - 1) + "," + text + "]";
            cObj("previous_schools").innerText = available_txt;
        } else {
            cObj("previous_schools").innerText = text;
        }
        cObj("previous_schools_windows").classList.add("hide");
        create_tbl_prev_sch();
    }
}

function create_tbl_prev_sch() {
    var data = cObj("previous_schools").innerText;
    // create tables to display the data
    if (data.length > 0) {
        var previous_schools = JSON.parse(data);
        var count = 0;
        var data_to_display = "<table class='table'><tr><th>No</th><th>School Name</th><th>Date Left</th><th>Marks Scored</th><th>Reason for Leaving</th><th>Actions</th></tr>";
        for (let index = 0; index < previous_schools.length; index++) {
            count++;
            const element = previous_schools[index];
            data_to_display += "<tr><td>" + (index + 1) + "</td><td>" + element.school_name + "</td><td>" + element.date_left + "</td><td>" + element.marks_scored + "</td><td>" + element.reason_for_leaving + "</td><td><span class='edit_prev_sch link' id='edit_prev_sch_" + index + "'><i class='fas fa-trash'></i> Remove</span></td></tr>";
        }
        data_to_display += "</table>";
        if (count > 0) {
            cObj("previous_school_list").innerHTML = data_to_display;
        } else {
            cObj("previous_school_list").innerHTML = "<p class='text-secondary'>No school previously attended by the student listed!</p>";
        }

        // Add Previous Schools
        var edit_prev_sch = document.getElementsByClassName("edit_prev_sch");
        for (let indexes = 0; indexes < edit_prev_sch.length; indexes++) {
            const element = edit_prev_sch[indexes];
            element.addEventListener("click", delete_prev_schs);
        }
    } else {
        cObj("previous_school_list").innerHTML = "<p class='text-secondary'>No school previously attended by the student listed!</p>";
    }
}
cObj("source_of_funding").onchange = function () {
    var my_val = cObj("source_of_funding").value;
    cObj("source_of_funding_data").value = my_val;
    if (my_val == "Others") {
        cObj("source_of_funding_data").value = "";
        cObj("source_of_funding_data").classList.remove("hide");
    } else {
        cObj("source_of_funding_data").classList.add("hide");
    }
}

function delete_prev_schs() {
    var ids = this.id.substring(14);
    var school_data = JSON.parse(cObj("previous_schools").innerText);
    var data = "[";
    var count = 0;
    for (let index = 0; index < school_data.length; index++) {
        const element = school_data[index];
        if (index != ids) {
            count++;
            data += '{"school_name":"' + element.school_name + '","date_left":"' + element.date_left + '","marks_scored":"' + element.marks_scored + '","leaving_cert":"' + element.leaving_cert + '","reason_for_leaving":"' + element.reason_for_leaving + '"},';
        }
    }
    if (count > 0) {
        data = data.substring(0, data.length - 1) + "]";
    } else {
        data = "";
    }
    cObj("previous_schools").innerText = data;
    create_tbl_prev_sch();
}

// display the add clubs window
cObj("cancel_add_sports_btn").onclick = function () {
    cObj("add_clubs_win").classList.add("hide");
}
cObj("add_sports_clubs").onclick = function () {
    cObj("add_clubs_win").classList.remove("hide");
}
cObj("add_clubs_btn").onclick = function () {
    // submit the data in the back end
    var err = checkBlank("club_name");
    if (err == 0) {
        // no errors present
        cObj("clubs_errors_in").innerHTML = "<p class='text-danger' ></p>";
        var club_name = valObj("club_name");
        var datapass = "?add_club=true&club_name=" + club_name;
        sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_sport_houses"));
        cObj("club_name").value = "";
        cObj("add_clubs_win").classList.add("hide");
        setTimeout(() => {
            var timeout = 0;
            var idd = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idd);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    // add the function that displays the data of the clubs
                    getClubHouses();
                    stopInterval(idd);
                }
            }, 100);
        }, 200);
    } else {
        cObj("clubs_errors_in").innerHTML = "<p class='text-danger' >Please fill the Sports House or Club name and proceed to add!</p>";
    }
}
function getClubHouses() {
    var datapass = "?getClubHouses=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_house_tables"));
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("loadings").classList.contains("hide")) {
                // add the function that displays the data of the clubs
                var edit_clubs = document.getElementsByClassName("edit_clubs");
                for (let index = 0; index < edit_clubs.length; index++) {
                    const element = edit_clubs[index];
                    element.addEventListener("click", editClubData);
                }
                // delete the clusbs
                var delete_clubs = document.getElementsByClassName("delete_clubs");
                for (let index = 0; index < delete_clubs.length; index++) {
                    const elem = delete_clubs[index];
                    elem.addEventListener("click", delete_club);
                }
                stopInterval(idd);
            }
        }, 100);
    }, 200);
}
function delete_club() {
    // delete the clubs
    var its_id = this.id;
    var suffix = its_id.substr(12);
    // pull the confirmation window
    cObj("delete_clubs_window").classList.remove("hide");
    cObj("clubs_ids_delete").innerText = suffix;
    var ids = "club_named" + suffix;
    cObj("sports_house_name").innerText = cObj(ids).innerText;
}
cObj("cancel_delete_clubs").onclick = function () {
    cObj("delete_clubs_window").classList.add("hide");
}
cObj("delete_clubs_yes").onclick = function () {
    var ids = cObj("clubs_ids_delete").innerText;
    var datapass = "?delete_clubs=true&ided=" + ids;
    sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_sport_houses"));
    cObj("delete_clubs_window").classList.add("hide");
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("loadings").classList.contains("hide")) {
                getClubHouses();
                stopInterval(idd);
            }
        }, 100);
    }, 200);
}

cObj("send_options").onchange = function () {
    var my_value = cObj("send_options").value;
    if (my_value == "send_emails") {
        cObj("email_sender").classList.remove("hide");
        cObj("text_message2").classList.add("hide");
        cObj("hide_text_areas").classList.remove("hide");
    } else {
        cObj("email_sender").classList.add("hide");
        cObj("text_message2").classList.remove("hide");
        cObj("hide_text_areas").classList.add("hide");
    }
}

function working_onit(event) {
    html_messageData(event.getContent());
    // console.log('Editor contents was modified. Contents: ' + event.getContent());
}

function editClubData() {
    var its_id = this.id;
    console.log(this.id);
    var suffix = its_id.substr(10);
    cObj("edit_clubs_win").classList.remove("hide");
    var ids = "club_named" + suffix;
    cObj("clubs_ids").innerText = suffix;
    cObj("club_edit_name").value = cObj(ids).innerText;
}
cObj("cancel_edit_sports_btn").onclick = function () {
    cObj("edit_clubs_win").classList.add("hide");
}
cObj("edit_clubs_btn").onclick = function () {
    var err = checkBlank("club_edit_name");
    if (err == 0) {
        // proceed and upload the data
        cObj("clubs_edit_errors_in").innerHTML = "";
        var datapass = "?edit_clubs=true&club_name=" + valObj("club_edit_name") + "&club_id=" + cObj("clubs_ids").innerText;
        sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_sport_houses"));
        cObj("edit_clubs_win").classList.add("hide");
        setTimeout(() => {
            var timeout = 0;
            var idd = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idd);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    getClubHouses();
                    stopInterval(idd);
                }
            }, 100);
        }, 200);
    } else {
        cObj("clubs_edit_errors_in").innerHTML = "<p class='text-danger' >Please fill the Sports House or Club name and proceed to Edit!</p>";
    }
}

function getClubsNSports() {
    var datapass = "?getmyclubs=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_n_sports"));
}
function getClubSportsList() {
    var datapass = "?getmyclubs2=true";
    sendData1("GET", "administration/admissions.php", datapass, cObj("clubs_for_sports_in"));
}

cObj("close_window_tutorial").onclick = function () {
    cObj("tutorial_windows").src = "";
    this.classList.add("hide");
}

// interact with the email window
cObj("close_email_setup").onclick = function () {
    cObj("email_setup_window").classList.add("hide");
    cObj("non12").reset();
}
cObj("close_email_windows").onclick = function () {
    cObj("close_email_setup").click();
}
// saving the email data
cObj("save_email_setup").onclick = function () {
    var err = 0;
    err += checkBlank("sender_name");
    err += checkBlank("email_host_addr");
    err += checkBlank("email_username");
    err += checkBlank("email_password");
    err += checkBlank("tester_mail");
    if (err == 0) {
        // save the data in the database
        cObj("error_email_setups").innerHTML = "";
        var datapass = "?sender_name=" + valObj("sender_name") + "&email_host_addr=" + valObj("email_host_addr") + "&email_username=" + valObj("email_username") + "&email_password=" + valObj("email_password") + "&tester_mail=" + valObj("tester_mail");
        sendData2("GET", "administration/admissions.php", datapass, cObj("error_email_setups"), cObj("load_email_setup2"));
        setTimeout(() => {
            var timeout = 0;
            var idd = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idd);
                }
                if (cObj("load_email_setup2").classList.contains("hide")) {
                    // function to get emails setup
                    email_settings();
                    cObj("close_email_setup").click();
                    setTimeout(() => {
                        cObj("error_email_setups").innerHTML = "";
                    }, 3000);
                    cObj("non12").reset();
                    stopInterval(idd);
                }
            }, 100);
        }, 200);
    } else {
        // show error of black spaces available
        cObj("error_email_setups").innerHTML = "<p class='text-danger'>Please fill all fields with black spaces</p>";
    }
}

// test the email
cObj("test_emails").onclick = function () {
    var datapass = "?test_email=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("email_main_errors"), cObj("load_email_setup"));
    setTimeout(() => {
        cObj("test_emails").disabled = true;
    }, 100);
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("load_email_setup").classList.contains("hide")) {
                // function to get emails setup
                cObj("test_emails").disabled = false;
                setTimeout(() => {
                    cObj("email_main_errors").innerHTML = "";
                }, 5000);
                stopInterval(idd);
            }
        }, 100);
    }, 200);
}

function email_settings() {
    var datapass = "?get_email_setups=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("email_errors"), cObj("load_email_setup"));
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("load_email_setup").classList.contains("hide")) {
                // function to get emails setup
                var data = cObj("email_errors").innerText;
                if (data.length > 0) {
                    // the email is set
                    cObj("email_already_setup").classList.remove("hide")
                    cObj("email_not_setup").classList.add("hide")

                    // set the data here
                    var newdata = JSON.parse(data);
                    cObj("sender_name_set").innerText = newdata.sender_name;
                    cObj("test_mail_set").innerText = newdata.tester_mail;
                    cObj("host_set_mail").innerText = newdata.email_host_addr;
                    cObj("username_mail_set").innerText = newdata.email_username;

                } else {
                    // email is not set
                    cObj("email_already_setup").classList.add("hide")
                    cObj("email_not_setup").classList.remove("hide");
                }
                stopInterval(idd);
            }
        }, 100);
    }, 200);
}


cObj("rather_send_email_btn").onclick = function () {
    cObj("send_email_button").classList.add("disabled");
    cObj("send_sms_window").classList.add("hide");
    cObj("send_email_window").classList.remove("hide");
    // check if the email configuration has been set
    var datapass = "?check_email_setup=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("email_not_setup_notify"), cObj("load_email_sending"));
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("load_email_sending").classList.contains("hide")) {
                var data = cObj("email_not_setup_notify").innerText;
                if (data == 1) {
                    cObj("send_email_button").classList.remove("disabled");
                    cObj("email_send_errors").innerHTML = "";
                } else {
                    cObj("email_send_errors").innerHTML = "<p class='text-danger'>Kindly setup your email first so that you can messages!</p>";
                }
                stopInterval(idd);
            }
        }, 100);
    }, 200);
}

cObj("rather_send_sms_btn").onclick = function () {
    cObj("send_sms_window").classList.remove("hide");
    cObj("send_email_window").classList.add("hide");
}

cObj("remove_email_settings").onclick = function () {
    var datapass = "?remove_email=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("email_main_errors"), cObj("load_email_setup2"));
    setTimeout(() => {
        var timeout = 0;
        var idd = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idd);
            }
            if (cObj("load_email_setup").classList.contains("hide")) {
                email_settings();
                stopInterval(idd);
                setTimeout(() => {
                    cObj("email_main_errors").innerText = "";
                }, 3000);
            }
        }, 100);
    }, 200);
}

cObj("edit_usernames").onclick = function () {
    cObj("sender_name").value = cObj("sender_name_set").innerText;
    cObj("email_host_addr").value = cObj("host_set_mail").innerText;
    cObj("email_username").value = cObj("username_mail_set").innerText;
    cObj("email_password").value = "";
    cObj("tester_mail").value = cObj("test_mail_set").innerText;
    cObj("email_setup_window").classList.remove("hide");
}

cObj("setup_email_windows").onclick = function () {
    cObj("email_setup_window").classList.remove("hide");
}

// tutorial videos

cObj("admit_student_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/bA7yaVvS81Q")
};
cObj("student_attendance_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/PKPcHFAGTyY");
}
cObj("register_staff_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/za6dCeuGtDI");
}
cObj("payfees_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/XW06SZn5ZHo");
}
cObj("manage_transactions_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/LrXzyzzeEdU");
}
cObj("mpesa_trans_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/GeH02-Bbn2U");
}
cObj("fees_structure_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/xg8tBhn0OXk");
}
cObj("record_expenses_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/5iAF_yGEINk");
}
cObj("payroll_sys_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/z5mcUSZkbGw");
}
cObj("transport_system_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/CHsK_glWYJ4");
}
cObj("transport_system_student_tutorial").onclick = function () {
    showTutorial("https://www.youtube.com/embed/CHsK_glWYJ4");
}
function showTutorial(link) {
    // console.log(cObj("tutorial_windows").src);
    cObj("tutorial_windows").src = link;
    cObj("close_window_tutorial").classList.remove("hide");
}

// HR OPTIONS
cObj("hr_options").onchange = function () {
    var hr_options_value = this.value;
    if (hr_options_value == "manage employees") {
        cObj("leave_management_window").classList.add("hide");
        cObj("employees_management_window").classList.remove("hide");
        cObj("error_hr_selection").classList.add("hide");
    } else if (hr_options_value == "manage_leaves") {
        cObj("leave_management_window").classList.remove("hide");
        cObj("employees_management_window").classList.add("hide");
        cObj("error_hr_selection").classList.add("hide");
    } else {
        cObj("leave_management_window").classList.add("hide");
        cObj("employees_management_window").classList.add("hide");
        cObj("error_hr_selection").classList.remove("hide");
    }
}

cObj("days_accrued").onchange = function () {
    var days_accrued = this.value;
    if (days_accrued == "Yearly") {
        cObj("p_1").hidden = false;
        cObj("p_2").hidden = true;
        cObj("p_3").hidden = true;
        cObj("p_4").hidden = true;
        cObj("p_5").hidden = true;
        cObj("p_0").selected = true;
    } else if (days_accrued == "Monthly") {
        cObj("p_1").hidden = true;
        cObj("p_2").hidden = false;
        cObj("p_3").hidden = false;
        cObj("p_4").hidden = true;
        cObj("p_5").hidden = true;
        cObj("p_0").selected = true;
    } else if (days_accrued == "Weekly") {
        cObj("p_1").hidden = true;
        cObj("p_2").hidden = true;
        cObj("p_3").hidden = true;
        cObj("p_4").hidden = false;
        cObj("p_5").hidden = false;
        cObj("p_0").selected = true;
    }
}
cObj("days_accrued2").onchange = function () {
    var days_accrued = this.value;
    if (days_accrued == "Yearly") {
        cObj("p_1_1").hidden = false;
        cObj("p_2_2").hidden = true;
        cObj("p_3_3").hidden = true;
        cObj("p_4_4").hidden = true;
        cObj("p_5_5").hidden = true;
        cObj("p_0_0").selected = true;
    } else if (days_accrued == "Monthly") {
        cObj("p_1_1").hidden = true;
        cObj("p_2_2").hidden = false;
        cObj("p_3_3").hidden = false;
        cObj("p_4_4").hidden = true;
        cObj("p_5_5").hidden = true;
        cObj("p_0_0").selected = true;
    } else if (days_accrued == "Weekly") {
        cObj("p_1_1").hidden = true;
        cObj("p_2_2").hidden = true;
        cObj("p_3_3").hidden = true;
        cObj("p_4_4").hidden = false;
        cObj("p_5_5").hidden = false;
        cObj("p_0_0").selected = true;
    }
}

// save the leave category
cObj("save_leave_category").onclick = function () {
    var err = 0;
    err += checkBlank("gender_eligible");
    err += checkBlank("max_days_per_yr");
    err += checkBlank("leave_status");
    err += checkBlank("leave_year_start");
    err += checkBlank("days_accrued");
    err += checkBlank("period_to_accrued");
    err += checkBlank("max_days_carry_forward");
    err += checkBlank("leave_title_name");

    if (err == 0) {
        cObj("save_leave_cat").innerHTML = "<p class='text-success'>Saving changes please wait...</p>";
        var datapass = "?save_leave_cat=true&leave_title=" + valObj("leave_title_name") + "&gender_eligible=" + valObj("gender_eligible") + "&max_days=" + valObj("max_days_per_yr") + "&leave_status=" + valObj("leave_status") + "&leave_yr=" + valObj("leave_year_start") + "&days_accrued=" + valObj("days_accrued") + "&period_accrued=" + valObj("period_to_accrued") + "&carry_forward=" + valObj("max_days_carry_forward");
        sendData2("GET", "administration/admissions.php", datapass, cObj("save_leave_cat"), cObj("save_leave_cat_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("allow_ct_reg_clock_elect").classList.contains("hide")) {
                    // clear the table
                    cObj("gender_eligible").value = "";
                    cObj("max_days_per_yr").value = "";
                    cObj("p_7").selected = true;
                    cObj("p_8").selected = true;
                    cObj("p_6").selected = true;
                    cObj("p_0").selected = true;
                    cObj("max_days_carry_forward").value = "";
                    cObj("leave_title_name").value = "";
                    setTimeout(() => {
                        cObj("save_leave_cat").innerHTML = "";
                    }, 10000);
                    stopInterval(ids);
                }
            }, 100);
        }, 100);

    } else {
        cObj("save_leave_cat").innerHTML = "<p class='text-danger'>Fill all fields covered with red border!</p>-";
    }
}

cObj("add_leave_category").onclick = function () {
    cObj("leave_diplay_windows").classList.add("hide");
    cObj("add_leave_cat_window").classList.remove("hide");
}

cObj("go_back_leave_list").onclick = function () {
    cObj("leave_diplay_windows").classList.remove("hide");
    cObj("add_leave_cat_window").classList.add("hide");
    display_leaves();
}
// go back from editing leave data
cObj("go_back_leave_list2").onclick = function () {
    cObj("leave_diplay_windows").classList.remove("hide");
    cObj("edit_leave_cat_window").classList.add("hide");
    display_leaves();
}


// leave categories options
cObj("leaves_options").onchange = function () {
    var leaves_options = this.value;
    if (leaves_options == "view leave categories") {
        display_leaves();
        leave_displays("leave_diplay_windows");
    } else if (leaves_options == "view leave application") {
        leave_displays("all_leaves_application");
        getLeave_Applications();
    }
}

function leave_displays(obj) {
    var leave_displays = document.getElementsByClassName("leave_displays");
    for (let index = 0; index < leave_displays.length; index++) {
        const element = leave_displays[index];
        element.classList.add("hide");
    }
    // unhide the chosen object
    cObj(obj).classList.remove("hide");
}

function display_leaves() {
    var datapass = "?get_leave_categories=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("leave_tables_display"), cObj("load_leaves_table"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_leaves_table").classList.contains("hide")) {
                // get the data 
                var edit_leaves = document.getElementsByClassName("edit_leaves");
                for (let index = 0; index < edit_leaves.length; index++) {
                    const element = edit_leaves[index];
                    element.addEventListener("click", editLeaves);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function editLeaves() {
    var leave_id = this.id.substr(11);
    var datapass = "?get_leave_data=" + leave_id;
    sendData1("GET", "administration/admissions.php", datapass, cObj("leave_data_holder"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("leave_diplay_windows").classList.add("hide");
                cObj("edit_leave_cat_window").classList.remove("hide");
                // get the data and change it to json format
                var leave_data = cObj("leave_data_holder").innerText;
                if (hasJsonStructure(leave_data)) {
                    leave_data = JSON.parse(leave_data);
                    cObj("leave_title_name2").value = leave_data.leave_title;
                    cObj("max_days_per_yr2").value = leave_data.max_days;
                    cObj("max_days_carry_forward2").value = leave_data.max_days_carry_forward;
                    // gender
                    var gender_eligible = leave_data.gender;
                    if (gender_eligible == "All") {
                        cObj("p_16_16").selected = true;
                    } else if (gender_eligible == "Male") {
                        cObj("p_17_17").selected = true;
                    } else if (gender_eligible == "Female") {
                        cObj("p_18_18").selected = true;
                    } else {
                        cObj("p_19_19").selected = true;
                    }

                    // leave status
                    var leave_status = leave_data.active;
                    if (leave_status == 0) {
                        cObj("p_15_15").selected = true;
                    } else if (leave_status == 1) {
                        cObj("p_14_14").selected = true;
                    } else {
                        cObj("p_13_13").selected = true;
                    }
                    // leave year start
                    var leave_year_starts = leave_data.leave_year_starts;
                    if (leave_year_starts == "Start Of Academic Yr") {
                        cObj("p_8_8").selected = true;
                    } else if (leave_year_starts == "Start of january") {
                        cObj("p_9_9").selected = true;
                    } else {
                        cObj("p_7_7").selected = true;
                    }
                    // days accrued
                    var days_are_accrued = leave_data.days_are_accrued;
                    if (days_are_accrued == "Yearly") {
                        cObj("p_10_10").selected = true;
                    } else if (days_are_accrued == "Monthly") {
                        cObj("p_11_11").selected = true;
                    } else if (days_are_accrued == "Weekly") {
                        cObj("p_12_12").selected = true;
                    } else {
                        cObj("p_6_6").selected = true;
                    }
                    // when accrued
                    var period_accrued = leave_data.period_accrued;
                    if (period_accrued == "Start Of Year") {
                        cObj("p_1_1").selected = true;
                    } else if (period_accrued == "Start Of Month") {
                        cObj("p_2_2").selected = true;
                    } else if (period_accrued == "End Of Month") {
                        cObj("p_3_3").selected = true;
                    } else if (period_accrued == "Start Of Week") {
                        cObj("p_4_4").selected = true;
                    } else if (period_accrued == "End Of Week") {
                        cObj("p_5_5").selected = true;
                    } else {
                        cObj("p_0_0").selected = true;
                    }
                    cObj("leaves_id").value = leave_data.id;
                } else {
                    console.log("An error has occured try again later");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

// update the category
cObj("update_leave_category").onclick = function () {
    var err = 0;
    err += checkBlank("leave_title_name2");
    err += checkBlank("gender_eligible2");
    err += checkBlank("max_days_per_yr2");
    err += checkBlank("leave_status2");
    err += checkBlank("leave_year_start2");
    err += checkBlank("days_accrued2");
    err += checkBlank("period_to_accrued2");
    err += checkBlank("max_days_carry_forward2");


    if (err == 0) {
        var datapass = "?update_leaves=" + valObj("leaves_id") + "&leave_title=" + valObj("leave_title_name2") + "&gender_eligible=" + valObj("gender_eligible2") + "&max_days=" + valObj("max_days_per_yr2") + "&leave_status=" + valObj("leave_status2") + "&leave_year_starts=" + valObj("leave_year_start2") + "&days_accrued=" + valObj("days_accrued2") + "&period_accrued=" + valObj("period_to_accrued2") + "&days_carry_forward=" + valObj("max_days_carry_forward2");
        sendData1("GET", "administration/admissions.php", datapass, cObj("save_leave_cat2"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("load_leaves_table").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("save_leave_cat2").innerHTML = "<p class='text-danger'></p>";
                    }, 10000);
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("setup_expense_category").onclick = function () {
    cObj("add_expense_category_window").classList.remove("hide");
}
cObj("close_window_expense_category").onclick = function () {
    cObj("add_expense_category_window").classList.add("hide");
}
cObj("cancel_expense_category").onclick = function () {
    cObj("add_expense_category_window").classList.add("hide");
}

cObj("setup_revenue_category").onclick = function () {
    cObj("add_revenue_sub_categories_holder_1").value = "[]";
    display_revenue_subs_1();
    cObj("add_revenue_category_window").classList.remove("hide");
}
cObj("close_window_revenue_category").onclick = function () {
    cObj("add_revenue_category_window").classList.add("hide");
}
cObj("cancel_revenue_category").onclick = function () {
    cObj("add_revenue_category_window").classList.add("hide");
}

cObj("add_expense_sub_category").onclick = function () {
    var expense_subcategories = valObj("expense_sub_categories_holder");
    var store_subcategories = [];
    if (hasJsonStructure(expense_subcategories)) {
        store_subcategories = JSON.parse(expense_subcategories);
    }

    // get the subcategories
    var err = checkBlank("expense_sub_categories");
    if (err == 0) {
        var id = 0;
        for (let index = 0; index < store_subcategories.length; index++) {
            const element = store_subcategories[index];
            if (element.id > id) {
                id = element.id;
            }
        }

        // get the id
        id+=1;
        var expense_sub_cat = {"id" : id,"name" : valObj("expense_sub_categories")};
        store_subcategories.push(expense_sub_cat);
    }
    console.log(store_subcategories);

    cObj("expense_sub_categories_holder").value = JSON.stringify(store_subcategories);

    // display subcategories
    display_subcategories();

    // reset the input text field
    cObj("expense_sub_categories").value = "";
}

// display tables
function display_subcategories() {
    var expense_subcategories = valObj("expense_sub_categories_holder");
    var store_subcategories = [];
    if (hasJsonStructure(expense_subcategories)) {
        store_subcategories = JSON.parse(expense_subcategories);
    }

    //display the stored subcategories
    var array = store_subcategories;
    var data_to_display = "";
    if (array.length > 0) {
        data_to_display = "<div class='container my-2 tableme'><table class='table col-md-12'><tr><th>No.</th><th>Expense Sub-Categories</th><th>Action</th></tr>";
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            data_to_display += "<tr><td>"+(index+1)+".</td><td>"+element.name+"</td><td><span class='link exit_expense_sub_cat' id='exit_expense_sub_cat_"+element.id+"'><i class='fas fa-trash'></i> Delete</span></td></tr>";
        }
        data_to_display+="</table></div>";
    }else{
        data_to_display = "<p class='text-danger'>No expense categories to display!<br>Add expense category list will appear here!</p>";
    }
    
    // display
    cObj("expense_subcategory_table").innerHTML = data_to_display;

    // add event lsiteners
    var exit_expense_sub_cat = document.getElementsByClassName("exit_expense_sub_cat");
    for (let index = 0; index < exit_expense_sub_cat.length; index++) {
        const element = exit_expense_sub_cat[index];
        element.addEventListener("click",edit_delete_expense);
    }
}

function edit_delete_expense() {
    var expense_subcategories = valObj("expense_sub_categories_holder");
    var store_subcategories = [];
    if (hasJsonStructure(expense_subcategories)) {
        store_subcategories = JSON.parse(expense_subcategories);
    }

    var expense_category_2 = [];
    for (let index = 0; index < store_subcategories.length; index++) {
        const element = store_subcategories[index];
        if (element.id == this.id.substr(21)) {
            continue;
        }
        expense_category_2.push(element);
    }
    
    // display subcategories
    cObj("expense_sub_categories_holder").value = JSON.stringify(expense_category_2);
    display_subcategories();
}


cObj("save_expense_category").onclick = function () {
    var err = checkBlank("expense_category_name");
    err+= checkBlank("expense_category_budget");
    err += checkBlank("budget_start_time");
    err += checkBlank("budget_end_date");
    if (err == 0) {
        var datapass = "save_expense_category=true&category_name=" + encodeURIComponent(valObj("expense_category_name"))+"&expense_category_budget="+valObj("expense_category_budget")+"&expense_categories="+encodeURIComponent(valObj("expense_sub_categories_holder"));
        datapass += "&budget_start_time="+valObj("budget_start_time")+"&budget_end_date="+valObj("budget_end_date")+"&expense_notes="+valObj("expense_notes");
        sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("display_data_exp_category"), cObj("expense_categories_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("expense_categories_loaders").classList.contains("hide")) {
                    if(cObj("expense_error_supplier") == undefined){
                        setTimeout(() => {
                            displayExpCategories();
                            cObj("display_data_exp_category").innerHTML = "";
                            cObj("expense_category_name").value = "";
                            cObj("expense_category_budget").value = "";
                            cObj("budget_start_time").value = "";
                            cObj("budget_end_date").value = "";
                            cObj("expense_sub_categories_holder").value = "[]"
                            display_subcategories();
                        }, 1000);
                        cObj("cancel_expense_category").click();
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}


cObj("save_revenue_category").onclick = function () {
    var err = checkBlank("revenue_category_name");
    if (err == 0) {
        var datapass = "?save_revenue_category=true&category_name=" + encodeURIComponent(valObj("revenue_category_name"))+"&revenue_sub_category="+valObj("add_revenue_sub_categories_holder_1");
        datapass+= "&revenue_notes="+valObj("revenue_notes");
        sendData2("GET", "administration/admissions.php", datapass, cObj("display_data_revenue_category"), cObj("revenue_categories_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("revenue_categories_loaders").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("display_data_revenue_category").innerHTML = "";
                    }, 3000);
                    displayRevenueCategories();
                    cObj("revenue_category_name").value = "";
                    cObj("cancel_revenue_category").click();
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function delete_revenue_categories() {
    console.log(this.id);
    cObj("delete_revenue_category").classList.remove("hide");
    cObj("revenue_category_name_holder").innerText = cObj("revenue_name_"+this.id.substr(19)).innerText;
    cObj("revenue_index").value = this.id.substr(19);
}

cObj("activated").onchange = function () {
    if (cObj("activated").value == "0") {
        cObj("reason_for_staff_inactive").classList.remove("hide");
    }else{
        cObj("reason_for_staff_inactive").classList.add("hide");
    }
}

cObj("yes_delete_revenue_category").onclick = function () {
    var datapass = "delete_revenue_category="+cObj("revenue_index").value;
    sendDataPost("POST","ajax/administration/admissions.php", datapass, cObj("display_data_revenue_category"), cObj("revenue_categories_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("revenue_categories_loaders").classList.contains("hide")) {
                cObj("delete_revenue_category").classList.add("hide");
                displayRevenueCategories();
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("no_delete_revenue_category").onclick = function () {
    cObj("delete_revenue_category").classList.add("hide");
}

function displayRevenueCategories() {
    var datapass = "?show_revenue_category=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("revenue_category_table_holder"), cObj("revenue_categories_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("revenue_categories_loaders").classList.contains("hide")) {
                var delete_revenue_cat = document.getElementsByClassName("delete_revenue_cat");
                for (let index = 0; index < delete_revenue_cat.length; index++) {
                    const element = delete_revenue_cat[index];
                    element.addEventListener("click", delete_revenue_categories);
                }

                var edit_revenue_cat = document.getElementsByClassName("edit_revenue_cat");
                for (let index = 0; index < edit_revenue_cat.length; index++) {
                    const element = edit_revenue_cat[index];
                    element.addEventListener("click", edit_revenue_category);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function displayExpCategories() {
    var datapass = "?show_expense_cat=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("expense_category_table_holder"), cObj("expense_categories_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("expense_categories_loaders").classList.contains("hide")) {
                var delete_exp_cat = document.getElementsByClassName("delete_exp_cat");
                for (let index = 0; index < delete_exp_cat.length; index++) {
                    const element = delete_exp_cat[index];
                    element.addEventListener("click", delete_exp_categories);
                }

                var edit_exp_cat = document.getElementsByClassName("edit_exp_cat");
                for (let index = 0; index < edit_exp_cat.length; index++) {
                    const element = edit_exp_cat[index];
                    element.addEventListener("click", edit_expense_category);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("cancel_change_expense_category").onclick = function () {
    cObj("change_expense_category_window").classList.add("hide");
}
cObj("close_change_expense_category_window").onclick = function () {
    cObj("change_expense_category_window").classList.add("hide");
}

cObj("cancel_change_revenue_category").onclick = function () {
    cObj("change_revenue_category_window").classList.add("hide");
}
cObj("close_change_revenue_category_window").onclick = function () {
    cObj("change_revenue_category_window").classList.add("hide");
}

cObj("save_change_revenue_category").onclick = function name() {
    var err = checkBlank("change_revenue_category_input_window");
    if (err == 0) {
        var datapass = "?change_revenue_categories=true&new_revenue_name=" + encodeURIComponent(valObj("change_revenue_category_input_window")) + "&revenue_indexes=" + encodeURIComponent(valObj("revenue_indexes_update"))+"&revenue_sub_categories="+valObj("add_revenue_sub_categories_holder");
        datapass += "&revenue_notes="+valObj("revenue_notes_edit");
        sendData2("GET", "administration/admissions.php", datapass, cObj("display_data_revenue_category"), cObj("revenue_categories_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("revenue_categories_loaders").classList.contains("hide")) {
                    if(cObj("income_note_error") == undefined){
                        displayRevenueCategories();
                        setTimeout(() => {
                            cObj("display_data_revenue_category").innerHTML = "";
                        }, 3000);
                    }
                    cObj("cancel_change_revenue_category").click();
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("save_change_expense_category").onclick = function name() {
    var err = checkBlank("change_expense_category_input_window");
    err += checkBlank("budget_start_time_edit");
    err += checkBlank("budget_end_date_edit");
    err += checkBlank("expense_category_budget_edit");
    if (err == 0) {
        var datapass = "change_expense_categories=true&new_exp_name=" + encodeURIComponent(valObj("change_expense_category_input_window")) + "&exp_indexes=" + encodeURIComponent(valObj("exp_indexes_update"))+"&expense_categories="+encodeURIComponent(valObj("edit_expense_sub_categories_holder"));
        datapass += "&budget_start_time_edit="+valObj("budget_start_time_edit")+"&budget_end_date_edit="+valObj("budget_end_date_edit")+"&expense_category_budget_edit="+valObj("expense_category_budget_edit")+"&edit_expense_notes="+valObj("edit_expense_notes");
        sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("display_data_exp_category"), cObj("expense_categories_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("expense_categories_loaders").classList.contains("hide")) {
                    if(cObj("expense_note_selected_edit") == undefined){
                        displayExpCategories();
                        setTimeout(() => {
                            cObj("display_data_exp_category").innerHTML = "";
                        }, 5000);
                    }
                    cObj("cancel_change_expense_category").click();
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function edit_expense_category() {
    var this_id = this.id.substr(13);
    var expense_category = valObj("exp_name_"+this_id);
    console.log(expense_category);
    if (hasJsonStructure(expense_category)) {
        cObj("change_expense_category_window").classList.remove("hide");
        
        // decode json
        expense_category = JSON.parse(expense_category);

        // get the expense value
        cObj("exp_indexes_update").value = this_id;
        cObj("change_expense_category_input_window").value = expense_category.expense_name;
        cObj("expense_category_change_name").value = expense_category.expense_name;
        cObj("expense_category_budget_edit").value = expense_category.expense_budget;
        cObj("budget_start_time_edit").value = expense_category.start_date;
        cObj("budget_end_date_edit").value = expense_category.end_date;
        cObj("edit_expense_sub_categories_holder").value = JSON.stringify(expense_category.expense_sub_categories);
        
        var children = cObj("edit_expense_notes").children;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if (element.value == expense_category.expense_note) {
                element.selected = true;
                break;
            }
        }

        // display expense category
        display_data_exp_category_edit();
    }
}


function display_data_exp_category_edit() {
    // expense_category
    var expense_category = cObj("edit_expense_sub_categories_holder").value;
    var expense_sub_categories = [];
    if (hasJsonStructure(expense_category)) {
        expense_sub_categories = JSON.parse(expense_category);
    }

    //display the stored subcategories
    var array = expense_sub_categories;
    var data_to_display = "";
    if (array.length > 0) {
        data_to_display = "<table class='table col-md-12'><tr><th>No.</th><th>Expense Sub-Categories</th><th>Action</th></tr>";
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            data_to_display += "<tr><td>"+(index+1)+".</td><td>"+element.name+"</td><td><span class='link delete_expense_sub_cat' id='delete_expense_sub_cat_"+element.id+"'><i class='fas fa-trash'></i> Delete</span></td></tr>";
        }
        data_to_display+="</table>";
    }else{
        data_to_display = "<p class='text-danger'>No expense categories to display!<br>Add expense category list will appear here!</p>";
    }
    
    // display
    cObj("edit_expense_subcategory_table").innerHTML = data_to_display;

    // add event lsiteners
    var delete_expense_sub_cat = document.getElementsByClassName("delete_expense_sub_cat");
    for (let index = 0; index < delete_expense_sub_cat.length; index++) {
        const element = delete_expense_sub_cat[index];
        element.addEventListener("click",edit_delete_expense_category);
    }
}

function edit_delete_expense_category() {
    var expense_subcategories = valObj("edit_expense_sub_categories_holder");
    var store_subcategories = [];
    if (hasJsonStructure(expense_subcategories)) {
        store_subcategories = JSON.parse(expense_subcategories);
    }

    var expense_category_2 = [];
    for (let index = 0; index < store_subcategories.length; index++) {
        const element = store_subcategories[index];
        if (element.id == this.id.substr(23)) {
            continue;
        }
        expense_category_2.push(element);
    }
    
    // display subcategories
    cObj("edit_expense_sub_categories_holder").value = JSON.stringify(expense_category_2);
    display_data_exp_category_edit();
}

cObj("edit_expense_sub_category").onclick = function () {
    var expense_subcategories = valObj("edit_expense_sub_categories_holder");
    var store_subcategories = [];
    if (hasJsonStructure(expense_subcategories)) {
        store_subcategories = JSON.parse(expense_subcategories);
    }

    // get the subcategories
    var err = checkBlank("edit_expense_sub_categories");
    if (err == 0) {
        var id = 0;
        for (let index = 0; index < store_subcategories.length; index++) {
            const element = store_subcategories[index];
            if (element.id > id) {
                id = element.id;
            }
        }

        // get the id
        id+=1;
        var expense_sub_cat = {"id" : id,"name" : valObj("edit_expense_sub_categories")};
        store_subcategories.push(expense_sub_cat);
    }

    cObj("edit_expense_sub_categories_holder").value = JSON.stringify(store_subcategories);

    // display subcategories
    display_data_exp_category_edit();

    // reset the input text field
    cObj("edit_expense_sub_categories").value = "";
}

function edit_revenue_category() {
    var this_id = this.id.substr(17);
    cObj("change_revenue_category_input_window").value = cObj("revenue_name_" + this_id).innerText;
    cObj("revenue_category_change_name").innerHTML = cObj("revenue_name_" + this_id).innerText;
    cObj("change_revenue_category_window").classList.remove("hide");
    cObj("revenue_indexes_update").value = this_id;
    cObj("add_revenue_sub_categories_holder").value = cObj("expense_sub_category_"+this_id).value;

    var revenue_note = cObj("revenue_note_"+this_id).value;
    console.log(revenue_note);
    var children = cObj("revenue_notes_edit").children;
    children[0].selected = true;
    for (let index = 0; index < children.length; index++) {
        const element = children[index];
        if(element.value == revenue_note){
            element.selected = true;
        }
    }
    display_revenue_subs();
}

function delete_exp_categories() {
    var ids = this.id.substr(15);
    var expense_category = cObj("exp_name_" + ids).value;
    console.log(expense_category);
    if (hasJsonStructure(expense_category)) {
        expense_category = JSON.parse(expense_category);
        cObj("expense_category_delete_name").innerText = expense_category.expense_name;
        cObj("exp_indexes").value = ids;
        cObj("delete_expense_category_window").classList.remove("hide");
    }
}

cObj("close_window_delete_expense_category").onclick = function () {
    cObj("delete_expense_category_window").classList.add("hide");
}
cObj("cancel_delete_expense_category").onclick = function () {
    cObj("delete_expense_category_window").classList.add("hide");
}

cObj("save_delete_expense_category").onclick = function () {
    var datapass = "?delete_expense_category=true&index_id=" + valObj("exp_indexes");
    sendData2("GET", "administration/admissions.php", datapass, cObj("display_data_exp_category"), cObj("expense_categories_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("expense_categories_loaders").classList.contains("hide")) {
                displayExpCategories();
                cObj("cancel_delete_expense_category").click();
                cObj("display_data_exp_category").innerHTML = "";
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}



function working_days() {
    var datapass = "?get_working_days=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("display_working_days"), cObj("working_days_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("working_days_loader").classList.contains("hide")) {
                var wd_btn = document.getElementsByClassName("wd_btn");
                for (let index = 0; index < wd_btn.length; index++) {
                    const element = wd_btn[index];
                    element.addEventListener("click", activateWorkingDay);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function activateWorkingDay() {
    var days = this.innerText;
    var datapass = "?working_day_change=" + days;
    sendData2("GET", "administration/admissions.php", datapass, cObj("display_working_days"), cObj("working_days_loader"));
    cObj("display_working_days").innerHTML = "<p class='text-success'>Please wait...</p>";
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("working_days_loader").classList.contains("hide")) {
                working_days();
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
function getLeaveCategory(select_id, leave_holder, loader) {
    // get the leave detail
    var datapass = "?get_leaves_cat=true&select_ids=" + select_id;
    sendData2("GET", "administration/admissions.php", datapass, cObj(leave_holder), cObj(loader));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj(loader).classList.contains("hide")) {
                // set the select button with an onchange listener
                cObj(select_id).addEventListener("change", selectLeave);
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
function getLeavesApplied() {
    var datapass = "?my_leaves_application=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("my_application_table_data"), cObj("my_leave_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("my_leave_list_loader").classList.contains("hide")) {
                var view_emp_leaves = document.getElementsByClassName("view_emp_leaves");
                for (let index = 0; index < view_emp_leaves.length; index++) {
                    const element = view_emp_leaves[index];
                    element.addEventListener("click", view_my_leaves_data)
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
cObj("back_to_list_emp_leave_list_2").onclick = function () {
    my_leaves_view("display_my_applied_leaves");
    getLeavesApplied();
}
cObj("back_to_list_emp_leave_list").onclick = function () {
    my_leaves_view("display_my_applied_leaves");
    cObj("leave_balance_apply").innerText = "Select leave category";
    getLeavesApplied();
}
function view_my_leaves_data() {
    my_leaves_view("view_leave_details_emp");
    var leave_id = this.id.substr(15);
    var datapass = "?get_my_leave_data=" + leave_id;
    sendData2("GET", "administration/admissions.php", datapass, cObj("leave_details_result"), cObj("load_leave_details"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_leave_details").classList.contains("hide")) {
                var leave_details_result = cObj("leave_details_result").innerText;
                if (hasJsonStructure(leave_details_result)) {
                    var jsoned_data = JSON.parse(leave_details_result);
                    cObj("leave_name_applied").value = jsoned_data.leave_category;
                    cObj("leave_apply_date_views").value = jsoned_data.date_applied;
                    cObj("from_leave_date_my_view").value = jsoned_data.from;
                    cObj("to_leaves_date_my_view").value = jsoned_data.to;
                    cObj("leave_duration_days_view").value = jsoned_data.days_duration;
                    cObj("leave_comments_my_view").value = jsoned_data.leave_description;
                    cObj("application_error_view").innerHTML = "";
                    var status = jsoned_data.status;
                    if (status == "0") {
                        cObj("my_leave_status").innerHTML = "<span class='btn btn-secondary btn-block'> Pending</span>";
                    } else if (status == "1") {
                        cObj("my_leave_status").innerHTML = "<span class='btn btn-success btn-block'><i class='fas fa-check'></i> Approved</span>";
                    } else if (status == "2") {
                        cObj("my_leave_status").innerHTML = "<span class='btn btn-danger btn-block'><i class='fas fa-times'></i> Declined</span>";
                    }
                } else {
                    cObj("leave_name_applied").value = "";
                    cObj("leave_apply_date_views").value = "";
                    cObj("from_leave_date_my_view").value = "";
                    cObj("to_leaves_date_my_view").value = "";
                    cObj("leave_duration_days_view").value = "";
                    cObj("leave_comments_my_view").value = "";
                    cObj("application_error_view").innerHTML = "<p class='text-danger'>An error occured while trying to display try again later!</p>";
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}
function my_leaves_view(object_id) {
    var my_leaves_view = document.getElementsByClassName("my_leaves_view");
    for (let index = 0; index < my_leaves_view.length; index++) {
        const element = my_leaves_view[index];
        element.classList.add("hide");
    }
    cObj(object_id).classList.remove("hide");
}
function selectLeave() {
    var leave_cat = this.value;
    var datapass = "?get_leave_balance=" + leave_cat;
    // console.log(datapass);
    sendData2("GET", "administration/admissions.php", datapass, cObj("leave_balance_apply"), cObj("leave_balance_loader"));
}

cObj("from_leave_date").onchange = function () {
    var err = checkBlank("from_leave_date");
    err += checkBlank("to_leaves_date");
    if (err == 0) {
        cObj("to_leaves_date").min = valObj("from_leave_date");
        var datapass = "?count_days=true&from_date=" + valObj("from_leave_date") + "&to_date=" + valObj("to_leaves_date");
        sendData2("GET", "administration/admissions.php", datapass, cObj("leave_days_holder"), cObj("leave_duration_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("leave_duration_loader").classList.contains("hide")) {
                    // console.log("Noticed in");
                    if (cObj("date_differences_leave_holder") != null && cObj("date_differences_leave_holder") != undefined) {
                        var difference = cObj("date_differences_leave_holder").innerText;
                        cObj("leave_duration").value = difference;
                    } else {
                        cObj("leave_duration").value = 0;
                    }
                    if (cObj("days_entittled") != null || cObj("days_entittled") != undefined) {
                        var days_chosen = (valObj("leave_duration") * 1);
                        var max_days = (cObj("days_entittled").innerText * 1);
                        if (days_chosen > max_days) {
                            cObj("duration_day_errors").innerHTML = "<p class='text-danger' id='days_exceed_error'>You cannot apply for more days than the leave days balance!</p>";
                        } else {
                            cObj("duration_day_errors").innerHTML = "<p class='text-success'>These days exclude non-working days and holidays!</p>";
                        }
                    } else {
                        cObj("duration_day_errors").innerHTML = "<p class='text-danger'>Select the leave category your are applying for!</p>";
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}
cObj("to_leaves_date").onchange = function () {
    var err = checkBlank("from_leave_date");
    err += checkBlank("to_leaves_date");
    if (err == 0) {
        cObj("to_leaves_date").min = valObj("from_leave_date");
        // cObj("to_leaves_date").min = valObj("from_leave_date");
        var datapass = "?count_days=true&from_date=" + valObj("from_leave_date") + "&to_date=" + valObj("to_leaves_date");
        sendData2("GET", "administration/admissions.php", datapass, cObj("leave_days_holder"), cObj("leave_duration_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("leave_duration_loader").classList.contains("hide")) {
                    // console.log("Noticed in");
                    if (cObj("date_differences_leave_holder") != null && cObj("date_differences_leave_holder") != undefined) {
                        var difference = cObj("date_differences_leave_holder").innerText;
                        cObj("leave_duration").value = difference;
                    } else {
                        cObj("leave_duration").value = 0;
                    }
                    if (cObj("days_entittled") != null || cObj("days_entittled") != undefined) {
                        var days_chosen = (valObj("leave_duration") * 1);
                        var max_days = (cObj("days_entittled").innerText * 1);
                        if (days_chosen > max_days) {
                            cObj("duration_day_errors").innerHTML = "<p class='text-danger' id='days_exceed_error'>You cannot apply for more days than the leave days balance!</p>";
                        } else {
                            cObj("duration_day_errors").innerHTML = "<p class='text-success'>These days exclude non-working days and holidays!</p>";
                        }
                    } else {
                        cObj("duration_day_errors").innerHTML = "<p class='text-danger'>Select the leave category your are applying for!</p>";
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("apply_leave").onclick = function () {
    var err = checkBlank("leave_type");
    err += checkBlank("from_leave_date");
    err += checkBlank("to_leaves_date");
    err += checkBlank("leave_duration");
    err += (cObj("days_exceed_error") != null && cObj("days_exceed_error") != undefined) ? 1 : 0;
    // console.log(cObj("days_exceed_error") != null && cObj("days_exceed_error") != undefined);
    if (err == 0) {
        cObj("application_error").innerHTML = "";
        var datapass = "?apply_leaves=true&leave_category_applied=" + valObj("leave_type") + "&from_date=" + valObj("from_leave_date") + "&to_date=" + valObj("to_leaves_date") + "&leave_duration=" + valObj("leave_duration") + "&leave_description=" + valObj("leave_comments");
        sendData2("GET", "administration/admissions.php", datapass, cObj("application_error"), cObj("apply_leave_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("apply_leave_loader").classList.contains("hide")) {
                    cObj("from_leave_date").value = "";
                    cObj("to_leaves_date").value = "";
                    cObj("leave_duration").value = "";
                    cObj("leave_comments").value = "";
                    cObj("back_to_list_emp_leave_list").click();

                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    } else {
        cObj("application_error").innerHTML = "<p class='text-danger'>Kindly check for all errors before proceeding!!</p>";
    }
}

// get the data from the database

function getLeave_Applications() {
    leave_data_display = [];
    leave_data_display_2 = [];
    var datapass = "?get_all_leaves=true";
    sendData2("GET", "administration/admissions.php", datapass, cObj("my_leaves_application"), cObj("leaves_application_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("leaves_application_loaders").classList.contains("hide")) {
                var data_recieved = cObj("my_leaves_application").innerText;
                // check if its json format
                if (hasJsonStructure(data_recieved)) {
                    var jsoned_data = JSON.parse(data_recieved);
                    for (let index = 0; index < jsoned_data.length; index++) {
                        const element = jsoned_data[index];
                        var element_array = [];
                        Object.keys(element).forEach(function (key) {
                            // console.log('Key : ' + key + ', Value : ' + element[key]);
                            element_array.push(element[key]);
                        });
                        leave_data_display.push(element_array);
                    }
                    leave_data_display_2 = leave_data_display;


                    var counted = leave_data_display.length / 20;
                    pagecountLeaves = Math.ceil(counted);
                    // console.log(leave_data_display);
                    cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, leave_data_display);
                    setLeavesListeners();
                } else {
                    cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! An error has occured while displaying table.<br> Try reloading your page!</p>";
                    cObj("tablefooter_leave_apply").classList.add("invisible");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

// DISPLAYING OF LEAVES STARTS HERE

var leave_data_display = [];
var leave_data_display_2 = [];
var pagecountLeaves = 0; //this are the number of pages for transaction
var pagecount_leaved = 1; //the current page the user is
var startpage_leaves = 1; // this is where we start counting the page number

// display record function
function displayRecord_leave_application(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th title='Sort all descending'># <span id=''><i class='fas fa-caret-down'></i></span></th><th  title='Sort by Reg No descending'>Applicant <span id=''><i class='fas fa-caret-down'></i></span></th><th  title='Sort by Amount descending'>Leave Type <span id=''><i class='fas fa-caret-down'></i></span></th><th  title='Sort by date Applied'>Application Date <span id=''><i class='fas fa-caret-down'></i></span></th><th  title='Sort by date Dates'>Dates<span id=''><i class='fas fa-caret-down'></i></span></th><th  title='Sort by Duration'>Duration <span id=''><i class='fas fa-caret-down'></i></span></th><th >Actions <span id=''><i class='fas fa-caret-down'></i></span></th></tr></thead><tbody>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            var accept_leave = "";
            var decline_leave = "";
            var view_leaves = "";
            var leave_status = "";
            if (arrays[index][7] == "0") {
                accept_leave = "";
                decline_leave = "";
                view_leaves = "";
                leave_status = "primary";
            } else if (arrays[index][7] == "1") {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "success";
            } else if (arrays[index][7] == "2") {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "danger";
            } else {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "danger";
            }
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + " <span class='text-" + leave_status + "' title='Charged'><i class='fas fa-info'></i></span></td><td><small>" + arrays[index][1] + "</small> <span class='badge badge-success'> </span></td><td><small>" + arrays[index][0] + "</small></td><td><small>" + arrays[index][5] + "</small></td><td><small>" + arrays[index][3] + " TO </small><small>" + arrays[index][4] + "</small></td><td><small>" + arrays[index][2] + " Working Day(s)</small></td><td><small class='link " + accept_leave + " accept_leaves' id='accept_leaves" + index + "'><i class='fas fa-check'></i> Accept</small> <small class='link " + decline_leave + " decline_leave' id='decline_leave" + index + "'><i class='fas fa-times'></i> Reject</small> <small class='link " + view_leaves + " view_leaves_btn' id='view_leaves_btn" + index + "'><i class='fas fa-eye'></i> View</small></td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            var accept_leave = "";
            var decline_leave = "";
            var view_leaves = "";
            var leave_status = "";
            if (arrays[index][7] == "0") {
                accept_leave = "";
                decline_leave = "";
                view_leaves = "";
                leave_status = "primary";
            } else if (arrays[index][7] == "1") {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "success";
            } else if (arrays[index][7] == "2") {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "danger";
            } else {
                accept_leave = "hide";
                decline_leave = "hide";
                view_leaves = "";
                leave_status = "danger";
            }
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + " <span class='text-" + leave_status + "' title='Charged'><i class='fas fa-info'></i></span></td><td><small>" + arrays[index][1] + "</small> <span class='badge badge-success'> </span></td><td><small>" + arrays[index][0] + "</small></td><td><small>" + arrays[index][5] + "</small></td><td><small>" + arrays[index][3] + " TO </small><small>" + arrays[index][4] + "</small></td><td><small>" + arrays[index][2] + " Working Day(s)</small></td><td><small class='link " + accept_leave + " accept_leaves' id='accept_leaves" + index + "'><i class='fas fa-check'></i> Accept</small> <small class='link " + decline_leave + " decline_leave' id='decline_leave" + index + "'><i class='fas fa-times'></i> Reject</small> <small class='link " + view_leaves + " view_leaves_btn' id='view_leaves_btn" + index + "'><i class='fas fa-eye'></i> View</small></td></tr>";
        }
        fins = total;
    }
    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_leave_apply").innerText = (start + 1);
    cObj("finishNo_leave_apply").innerText = fins;
    //set the page number
    cObj("pagenumNav_leave_apply").innerText = pagecount_leaved;
    return tableData;
}

cObj("tonextNav_leave_apply").onclick = function () {
    if (pagecount_leaved < pagecountLeaves) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_leaves += 20
        pagecount_leaved++;
        var endpage = startpage_leaves + 11;
        cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(startpage_leaves, endpage, leave_data_display);
        setLeavesListeners();
    } else {
        pagecount_leaved = pagecountLeaves;
    }
}
// end of next records
cObj("toprevNac_leave_apply").onclick = function () {
    if (pagecount_leaved > 1) {
        pagecount_leaved--;
        startpage_leaves -= 20
        var endpage = (startpage_leaves + 10) - 1;
        cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(startpage_leaves, endpage, leave_data_display);
        setLeavesListeners();
    }
}

cObj("tofirstNav_leave_apply").onclick = function () {
    if (pagecountLeaves > 0) {
        pagecount_leaved = 1;
        startpage_leaves = 0;
        var endpage = startpage_leaves + 20
        cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(startpage_leaves, endpage, leave_data_display);
        setLeavesListeners();
    }
    setAssignLis();
}

cObj("tolastNav_leave_apply").onclick = function () {
    if (pagecountLeaves > 0) {
        pagecount_leaved = pagecountLeaves;
        startpage_leaves = ((pagecount_leaved * 20) - 20) + 1;
        var endpage = startpage_leaves + 20
        cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(startpage_leaves, endpage, leave_data_display);
        setLeavesListeners();
    }
    setAssignLis();
}

// seacrh keyword at the table
cObj("searchkey_leaves").onkeyup = function () {
    check_leave_application(this.value);
}

//create a function to check if the array has the keyword being searched for
function check_leave_application(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter").classList.add("invisible");
        // set the 
    } else {
        cObj("tablefooter").classList.remove("invisible");
    }
    var rowsNcol2 = [];
    var keylower = keyword.toString().toLowerCase();
    var keyUpper = keyword.toString().toUpperCase();
    //row break
    for (let index = 0; index < leave_data_display.length; index++) {
        const element = leave_data_display[index];
        //column break
        var present = 0;
        if (element[1].toLowerCase().toString().includes(keylower) || element[1].toUpperCase().includes(keyUpper)) {
            present++;
            // console.log(element[1].toLowerCase().toString());
        }
        if (element[2].toLowerCase().toString().includes(keylower) || element[2].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[0].toLowerCase().toString().includes(keylower) || element[0].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3].toLowerCase().toString().includes(keylower) || element[6].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[4].toLowerCase().toString().includes(keylower) || element[4].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[5].toLowerCase().toString().includes(keylower) || element[5].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[6].toLowerCase().toString().includes(keylower) || element[6].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[7].toLowerCase().toString().includes(keylower) || element[7].toUpperCase().includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, rowsNcol2);
        setLeavesListeners();
    } else {
        cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        cObj("tablefooter").classList.add("invisible");
    }
}

function setLeavesListeners() {
    var accept_leaves = document.getElementsByClassName("accept_leaves");
    for (let index = 0; index < accept_leaves.length; index++) {
        const element = accept_leaves[index];
        element.addEventListener("click", accept_leaves_func);
    }
    // decline leaves
    var decline_leave = document.getElementsByClassName("decline_leave");
    for (let index2 = 0; index2 < decline_leave.length; index2++) {
        const element = decline_leave[index2];
        element.addEventListener("click", decline_leaves_func);
    }
    // view leaves
    var view_leaves_btn = document.getElementsByClassName("view_leaves_btn");
    for (let index = 0; index < view_leaves_btn.length; index++) {
        const element = view_leaves_btn[index];
        element.addEventListener("click", viewLeaveApplied);
    }
}
function accept_leaves_func() {
    // console.log(leave_data_display[this.id.substr(13)]);
    cObj("confirm_accepting_leaf").classList.remove("hide");
    cObj("accept_leave_ids").value = leave_data_display[this.id.substr(13)][8];
    // console.log(leave_data_display[this.id.substr(13)][8]);
    cObj("employees_names_leaves").innerHTML = leave_data_display[this.id.substr(13)][1] + "`s " + leave_data_display[this.id.substr(13)][0] + " application ?";
}

cObj("no_accept_leaves").onclick = function () {
    cObj("confirm_accepting_leaf").classList.add("hide");
}

cObj("yes_accept_leaves").onclick = function () {
    var err = checkBlank("accept_leave_ids");
    if (err == 0) {
        var datapass = "?accept_leaves=true&leaves_id=" + valObj("accept_leave_ids");
        sendData2("GET", "administration/admissions.php", datapass, cObj("leaves_accept_err_handlers"), cObj("leaves_acceptance_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("leaves_acceptance_loaders").classList.contains("hide")) {
                    if (cObj("accept_leaf_badge") != null && cObj("accept_leaf_badge") != undefined) {
                        cObj("confirm_accepting_leaf").classList.add("hide");
                        leave_data_display = [];
                        leave_data_display_2 = [];
                        // getLeave_Applications();
                        cObj("back_to_leave_list").click();
                    }
                    setTimeout(() => {
                        cObj("leaves_accept_err_handlers").innerHTML = "";
                    }, 10000);

                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function decline_leaves_func() {
    cObj("confirm_declining_leaf").classList.remove("hide");
    cObj("reject_leave_ids").value = leave_data_display[this.id.substr(13)][8];
    cObj("employees_names_leaves_reject").innerHTML = leave_data_display[this.id.substr(13)][1] + "`s " + leave_data_display[this.id.substr(13)][0] + " application ?";
}

cObj("decline_leave_applications").onclick = function () {
    cObj("confirm_declining_leaf").classList.remove("hide");
    cObj("reject_leave_ids").value = valObj("leaves_view_id");
    cObj("employees_names_leaves_reject").innerHTML = valObj("leave_applicant_names_view") + "`s " + valObj("leave_title_view") + " application ?";
}

cObj("accept_leave_applications").onclick = function () {
    // console.log(leave_data_display[this.id.substr(13)]);
    cObj("confirm_accepting_leaf").classList.remove("hide");
    cObj("accept_leave_ids").value = valObj("leaves_view_id");
    cObj("employees_names_leaves").innerHTML = valObj("leave_applicant_names_view") + "`s " + valObj("leave_title_view") + " application ?";

}

cObj("self_apply_for_leave").onclick = function () {
    my_leaves_view("apply_leaves_windows");
    // get the leaves category
    var select_id = "leave_type";
    var leave_holder = "leave_category_select";
    var loader = "leave_loader_select";
    getLeaveCategory(select_id, leave_holder, loader);
}

cObj("no_reject_leaves").onclick = function () {
    cObj("confirm_declining_leaf").classList.add("hide");
}
cObj("yes_reject_leaves").onclick = function () {
    var err = checkBlank("reject_leave_ids");
    if (err == 0) {
        var datapass = "?decline_leaves=true&leaves_id=" + valObj("reject_leave_ids");
        sendData2("GET", "administration/admissions.php", datapass, cObj("leaves_reject_err_handlers"), cObj("leaves_declining_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("leaves_declining_loaders").classList.contains("hide")) {
                    if (cObj("reject_leaf_badge") != null && cObj("reject_leaf_badge") != undefined) {
                        cObj("confirm_declining_leaf").classList.add("hide");
                        // getLeave_Applications();
                        cObj("back_to_leave_list").click();
                    }
                    setTimeout(() => {
                        cObj("leaves_reject_err_handlers").innerHTML = "";
                    }, 10000);

                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("delete_this_application").onclick = function () {
    cObj("confirm_del_leav_applic").classList.remove("hide");
}

cObj("confirm_delete_leave_apply_no").onclick = function () {
    cObj("confirm_del_leav_applic").classList.add("hide")
}

cObj("confirm_delete_leave_apply_yes").onclick = function () {
    var err = checkBlank("unussual_id");
    if (err == 0) {
        cObj("delete_leave_err_handlers").innerHTML = "";
        var datapass = "?delete_leave_apply=true&application_id=" + valObj("unussual_id");
        sendData2("GET", "administration/admissions.php", datapass, cObj("delete_leave_err_handlers"), cObj("load_delete_leave_app"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("load_delete_leave_app").classList.contains("hide")) {

                    setTimeout(() => {
                        cObj("delete_leave_err_handlers").innerHTML = "";
                        cObj("confirm_delete_leave_apply_no").click();
                        cObj("back_to_leave_list").click();
                    }, 2000);
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    } else {
        cObj("delete_leave_err_handlers").innerHTML = "<p class='text-danger'>An error has occured kindly try again</p>";
    }
}

function viewLeaveApplied() {
    leave_displays("view_applied_leaves_windows");
    // start filling the fields
    if (leave_data_display[this.id.substr(15)].length > 0) {
        cObj("leave_applicant_names_view").value = leave_data_display[this.id.substr(15)][1];
        cObj("leave_title_view").value = leave_data_display[this.id.substr(15)][0];
        cObj("leaf_duration_view").value = leave_data_display[this.id.substr(15)][2] + " Days";
        cObj("leave_from_date_view").value = leave_data_display[this.id.substr(15)][3];
        cObj("leave_to_date_view").value = leave_data_display[this.id.substr(15)][4];
        cObj("leave_application_date_view").value = leave_data_display[this.id.substr(15)][5];
        cObj("leave_description_view").value = leave_data_display[this.id.substr(15)][6];
        cObj("unussual_id").value = leave_data_display[this.id.substr(15)][8];
        if (leave_data_display[this.id.substr(15)][7] == "0") {
            cObj("p_102").selected = true;
            cObj("leaves_options_views").classList.remove("hide");
            cObj("leaves_status_displayer").innerHTML = "<span class='my-2 btn btn-secondary'>Pending</span>";
        } else if (leave_data_display[this.id.substr(15)][7] == "1") {
            cObj("p_103").selected = true;
            cObj("leaves_options_views").classList.add("hide");
            cObj("leaves_status_displayer").innerHTML = "<span class='my-2 btn btn-success'><i class='fas fa-check'></i> Accepted</span>";
        } else if (leave_data_display[this.id.substr(15)][7] == "2") {
            cObj("p_104").selected = true;
            cObj("leaves_options_views").classList.add("hide");
            cObj("leaves_status_displayer").innerHTML = "<span class='my-2 btn btn-danger'><i class='fas fa-times'></i> Declined</span>";
        } else {
            cObj("p_101").selected = true;
            cObj("leaves_options_views").classList.add("hide");
            cObj("leaves_status_displayer").innerHTML = "<span class='my-2 btn btn-secondary'> Pending</span>";
        }

        cObj("leaves_view_id").value = leave_data_display[this.id.substr(15)][8];
        // console.log(leave_data_display[this.id.substr(15)][8]);
    } else {
        cObj("leave_applicant_names_view").value = "";
        cObj("leave_title_view").value = "";
        cObj("leaf_duration_view").value = "";
        cObj("leave_from_date_view").value = "";
        cObj("leave_to_date_view").value = "";
        cObj("leave_application_date_view").value = "";
        cObj("leave_description_view").value = "";
    }
}

cObj("back_to_leave_list").onclick = function () {
    leave_displays("all_leaves_application");
    getLeave_Applications();
}

cObj("choose_leave_status").onchange = function () {
    leave_data_display = leave_data_display_2;
    var new_array = [];
    // console.log(leave_data_display);
    if (this.value == "0") {
        for (let index = 0; index < leave_data_display.length; index++) {
            const element = leave_data_display[index];
            if (element[7] == "0") {
                new_array.push(element);
            }
        }
        leave_data_display = new_array;
        if (leave_data_display.length > 0) {
            cObj("tablefooter_leave_apply").classList.remove("invisible");
            cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, leave_data_display);
            setLeavesListeners();
        } else {
            cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! An error has occured while displaying table.<br> Try reloading your page!</p>";
            cObj("tablefooter_leave_apply").classList.add("invisible");
        }
    } else if (this.value == "1") {
        for (let index = 0; index < leave_data_display.length; index++) {
            const element = leave_data_display[index];
            if (element[7] == "1") {
                new_array.push(element);
            }
        }
        leave_data_display = new_array;
        if (leave_data_display.length > 0) {
            cObj("tablefooter_leave_apply").classList.remove("invisible");
            cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, leave_data_display);
            setLeavesListeners();
        } else {
            cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! There are no declined leave applications present.</p>";
            cObj("tablefooter_leave_apply").classList.add("invisible");
        }
    } else if (this.value == "2") {
        for (let index = 0; index < leave_data_display.length; index++) {
            const element = leave_data_display[index];
            if (element[7] == "2") {
                new_array.push(element);
            }
        }
        leave_data_display = new_array;
        if (leave_data_display.length > 0) {
            cObj("tablefooter_leave_apply").classList.remove("invisible");
            cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, leave_data_display);
            setLeavesListeners();
        } else {
            cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! There are no declined leave applications present.!</p>";
            cObj("tablefooter_leave_apply").classList.add("invisible");
        }
    } else {
        leave_data_display = leave_data_display_2;
        if (leave_data_display.length > 0) {
            cObj("tablefooter_leave_apply").classList.remove("invisible");
            cObj("transDataReciever_leave_apply").innerHTML = displayRecord_leave_application(1, 20, leave_data_display);
            setLeavesListeners();
        } else {
            cObj("transDataReciever_leave_apply").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops!No data to display!</p>";
            cObj("tablefooter_leave_apply").classList.add("invisible");
        }
    }
}
// DISPLAYING OF LEAVES ENDS HERE

cObj("display_attendance_class_specific").onclick = function () {
    var err = 0;
    err += checkBlank("students_admnos_in");
    err += checkBlank("select_months_attendance");

    if (err == 0) {
        // get the students data
        var datapass = "?get_student_attendance=true&student_admno=" + valObj("students_admnos_in") + "&selected_month=" + valObj("select_months_attendance");
        sendData2("GET", "administration/admissions.php", datapass, cObj("display_student_attendance"), cObj("select_student_clock"));
    }
}
cObj("edit_discounts").onclick = function () {
    cObj("edit_discounts_window").classList.remove("hide");
    cObj("stud_admin_discounts").value = valObj("adminnos");
}
cObj("cancel_new_discount_val").onclick = function () {
    cObj("edit_discounts_window").classList.add("hide");
}

cObj("discount_option").onchange = function () {
    var discount_option = this.value;
    if (discount_option == "value") {
        cObj("discount_value_window").classList.remove("hide");
        cObj("discount_percentage_window").classList.add("hide");
    } else {
        cObj("discount_value_window").classList.add("hide");
        cObj("discount_percentage_window").classList.remove("hide");
    }
}

cObj("accept_new_discount_val").onclick = function () {
    var err = checkBlank("new_discount_value");
    err += checkBlank("discount_option");
    if (err == 0) {
        if (valObj("discount_option") == "percentage") {
            if (valObj("new_discount_percentage") * 1 >= 0) {
                if (valObj("new_discount_percentage") * 1 <= 100) {
                    var datapass = "?update_discounts=" + valObj("stud_admin_discounts") + "&discount_value=" + valObj("new_discount_percentage") + "&discount_option=" + valObj("discount_option");
                    sendData2("GET", "administration/admissions.php", datapass, cObj("new_discount_error"), cObj("edit_discounts_loader"));
                    setTimeout(() => {
                        var timeout = 0;
                        var ids = setInterval(() => {
                            timeout++;
                            //after two minutes of slow connection the next process wont be executed
                            if (timeout == 1200) {
                                stopInterval(ids);
                            }
                            if (cObj("change_balance_loaders").classList.contains("hide")) {
                                cObj("view" + valObj("stud_admin_discounts")).click();
                                cObj("cancel_new_discount_val").click();
                                cObj("new_discount_error").innerHTML = "";
                                cObj("new_discount_value").value = 0;
                                cObj("new_discount_percentage").value = 0;
                                stopInterval(ids);
                            }
                        }, 100);
                    }, 100);
                } else {
                    cObj("new_discount_error").innerHTML = "<p class='text-danger'>Percentage cannot be more than 100.</p>";
                }
            } else {
                cObj("new_discount_error").innerHTML = "<p class='text-danger'>The discount value should be greater than zero.</p>";
            }
        } else {
            var datapass = "?update_discounts=" + valObj("stud_admin_discounts") + "&discount_value=" + valObj("new_discount_value") + "&discount_option=" + valObj("discount_option");
            sendData2("GET", "administration/admissions.php", datapass, cObj("new_discount_error"), cObj("edit_discounts_loader"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("change_balance_loaders").classList.contains("hide")) {
                        cObj("view" + valObj("stud_admin_discounts")).click();
                        cObj("cancel_new_discount_val").click();
                        cObj("new_discount_error").innerHTML = "";
                        cObj("new_discount_value").value = 0;
                        cObj("new_discount_percentage").value = 0;
                        stopInterval(ids);
                    }
                }, 100);
            }, 100);
        }
    }
}

// MANAGE DEPARTMENTS
cObj("back_to_manage_staff").onclick = function () {
    cObj("managestaf").click();
}

cObj("save_departments").onclick = function () {
    var err = checkBlank("department_name");
    if (err == 0) {
        // proceed and get the department details
        var datapass = "save_department=true&department_name=" + valObj("department_name") + "&department_code=" + valObj("department_code") + "&department_description=" + valObj("department_description");
        sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("loader_infor_teller"), cObj("department_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("department_loader").classList.contains("hide")) {
                    cObj("department_name").value = "";
                    cObj("department_code").value = "";
                    cObj("department_description").value = "";

                    // display departments
                    displayDepartments();
                    cObj("add_a_departments").click();

                    setTimeout(() => {
                        cObj("loader_infor_teller").innerText = "";
                    }, 4000);
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("add_a_departments").onclick = function () {
    cObj("add_department_window").classList.toggle("hide");
}

function displayDepartments() {
    var datapass = "getData=true";
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("department_data"), cObj("department_loader_tables"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("department_loader_tables").classList.contains("hide")) {
                var department_data = cObj("department_data").innerText;
                if (hasJsonStructure(department_data)) {
                    // display department data
                    department_data = JSON.parse(department_data);

                    // display this data on a table
                    var data_to_display = "<table class='table'><thead><tr><th>#</th><th>Department Name</th><th>Department Code</th><th>Member Population</th><th>Date Created</th><th>Action</th></tr></thead><tbody>";

                    var counted = 1;
                    for (let index = (department_data.length - 1); index >= 0; index--) {
                        const element = department_data[index];
                        data_to_display += "<tr>"
                            + "<td>" + (counted) + "<input type='hidden' id='dept_data_" + element.id + "' value='" + JSON.stringify(element) + "'></td>" +
                            "<td>" + element.name + "</td>" +
                            "<td>" + (element.code == undefined ? "No Code" : element.code) + "</td>" +
                            "<td>" + element.members.length + " Member(s)</td>" +
                            "<td>" + formatDate(element.date_created) + "</td>" +
                            "<td><span class='btn btn-sm btn-primary my-0 view_departments' id='view_departments_" + element.id + "'><i class='fas fa-eye'></i> View</span></td>" +
                            "</tr>";
                        counted++;
                    }
                    data_to_display += "</tbody></table>";
                    cObj("data_table_department_table").innerHTML = data_to_display;

                    // set the listener
                    var view_departments = document.getElementsByClassName("view_departments");
                    for (let index = 0; index < view_departments.length; index++) {
                        const element = view_departments[index];
                        element.addEventListener("click", showDepartments);
                    }

                    // departmentd dta
                    if (department_data.length == 0) {
                        cObj("data_table_department_table").innerHTML = "<p class='text-danger text-center'><span><i class='fas fa-exclamation-triangle'></i></span><br> No records to display, Start by displaying your data with the options above</p>";
                    }
                    // click
                } else {
                    cObj("data_table_department_table").innerHTML = "<p class='text-danger text-center'><span><i class='fas fa-exclamation-triangle'></i></span><br> No records to display, Start by displaying your data with the options above</p>";
                }

                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("delete_department_details").onclick = function () {
    var dept_codes = valObj("dept_codes");
    cObj("department_code_delete").value = dept_codes;
    cObj("department_name_delete").innerText = valObj("department_name_view");
    cObj("delete_department_window").classList.remove("hide");
}

cObj("close_delete_department_window").onclick = function () {
    cObj("delete_department_window").classList.add("hide");
}

cObj("confirm_delete_department").onclick = function () {
    var datapass = "delete_department=" + valObj("department_code_delete");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("error_handler_delete_dept"), cObj("delete_department_all"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("department_loader_view").classList.contains("hide")) {
                // back to department list
                cObj("back_to_department_list").click();
                cObj("confirm_delete_department").disabled = true;
                setTimeout(() => {
                    cObj("error_handler_delete_dept").innerText = "";
                    cObj("close_delete_department_window").click();
                    cObj("confirm_delete_department").disabled = false;
                }, 2000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function showDepartments() {
    cObj("action_dept").classList.add("hide");
    // my id
    var my_id = this.id.substr(17);

    // department data
    var department_data = valObj("dept_data_" + my_id);
    if (hasJsonStructure(department_data)) {
        department_data = JSON.parse(department_data);
        // console.log(department_data);
        cObj("dept_codes").value = department_data.id;
        cObj("department_name_view").value = department_data.name;
        cObj("department_code_view").value = department_data.code;
        cObj("department_description_view").value = department_data.description;
        cObj("subjects_present").innerText = department_data.subjects.length + " Subject(s)";
        cObj("members_present").innerText = department_data.members.length + " Member(s)";
        cObj("date_created").innerText = formatDate(department_data.date_created);

        cObj("view_department_window").classList.remove("hide");
        cObj("dept_table_display").classList.add("hide");

        // get the staff data and the subject information
        var datapass = "getStaffAndSubjectDataDept=true";
        sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("department_details"), cObj("department_loader_view"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("department_loader_view").classList.contains("hide")) {
                    if (cObj("subject_n_students") != undefined) {
                        var subject_n_students = valObj("subject_n_students");
                        if (hasJsonStructure(subject_n_students)) {
                            subject_n_students = JSON.parse(subject_n_students);

                            // get the staff data and details
                            var teachers = subject_n_students.teacher_data;
                            var subjects = subject_n_students.subjects;

                            // loop trough the teachers to get the teacher data

                            var data_for_teacher = "<select name='head_of_department' id='hod_view' class='form-control w-75'><option value=''>Select Staff</option>";
                            var present = 0;
                            for (let index = 0; index < teachers.length; index++) {
                                const element = teachers[index];
                                var selected = teachers[index].user_id == department_data.hod ? "selected" : "";
                                if (teachers[index].user_id == department_data.hod) {
                                    cObj("head_of_dept").innerText = getTeacherName(teachers, department_data.hod);
                                    present = 1;
                                }
                                data_for_teacher += "<option " + selected + " value='" + element.user_id + "'>" + (element.fullname) + "</option>";
                            }
                            data_for_teacher += "</select>";
                            cObj("hod_window_holder").innerHTML = data_for_teacher;

                            if (present == 0) {
                                cObj("head_of_dept").innerText = "Not Set";
                            }

                            var display_teachers = "<table class='table'>" +
                                "<thead>" +
                                "<tr>" +
                                "<th>No<input type='checkbox' id='dept_data'></th>" +
                                "<th>Name</th>" +
                                "<th>Date Joined</th>" +
                                "<th>Action</th>" +
                                "</tr>" +
                                "</thead>";
                            for (let index = 0; index < department_data.members.length; index++) {
                                const element = department_data.members[index];
                                display_teachers += "<tr>" +
                                    "<td>" + (index + 1) + ".  <input type='checkbox' class='dept_data' value='" + element.name + "' id='dept_data_" + element.name + "'></td>" +
                                    "<td>" + getTeacherName(teachers, element.name) + "</td>" +
                                    "<td>" + formatDate(element.date_joined) + "</td>" +
                                    "<td><span class='link delete_member' id='delete_member_" + element.name + "'><i class='fas fa-trash'></i> Remove</span></td>" +
                                    "</tr>";
                            }

                            display_teachers += "</table>";
                            if (department_data.members.length > 0) {
                                cObj("members_list_table").innerHTML = display_teachers;

                                // set listeners for the remove button
                                var delete_member = document.getElementsByClassName("delete_member");
                                for (let index = 0; index < delete_member.length; index++) {
                                    const element = delete_member[index];
                                    element.addEventListener("click", deleteElement);
                                }

                                // select option
                                var dept_data = document.getElementsByClassName("dept_data");
                                for (let index = 0; index < dept_data.length; index++) {
                                    const element = dept_data[index];
                                    element.addEventListener("click", selectMembers);
                                }

                                // select listener
                                if (cObj("dept_data") != undefined) {
                                    cObj("dept_data").addEventListener("click", checkDept);
                                }
                            } else {
                                cObj("members_list_table").innerHTML = "<p class='text-danger text-center'><span><i class='fas fa-exclamation-triangle'></i></span><br> This department has no Members</p>";
                            }

                            // get the display for subjects
                            var display_subjects = "<table class='table'>" +
                                "<thead>" +
                                "<tr>" +
                                "<th>No <input type='checkbox' id='our_subject'></th>" +
                                "<th>Subject Name</th>" +
                                "<th>Display Name</th>" +
                                "<th>Action</th>" +
                                "</tr>" +
                                "</thead>";
                            for (let index = 0; index < department_data.subjects.length; index++) {
                                const element = department_data.subjects[index];
                                var subject_data = getSubjectDetails(subjects, element.name);
                                display_subjects += "<tr>" +
                                    "<td>" + (index + 1) + ". <input type='checkbox' class='our_subject' value='" + element.name + "' id='our_subject_" + element.name + "'></td>" +
                                    "<td>" + subject_data[0] + "</td>" +
                                    "<td>" + subject_data[1] + "</td>" +
                                    "<td><span class='link delete_dept_subject' id='delete_dept_subject_" + element.name + "'><i class='fas fa-trash'></i> Remove</span></td>" +
                                    "</tr>";
                            }
                            display_subjects += "</table>";

                            if (department_data.subjects.length > 0) {
                                cObj("subject_list_table_dept").innerHTML = display_subjects;

                                // display subject list
                                var delete_dept_subject = document.getElementsByClassName("delete_dept_subject");
                                for (let index = 0; index < delete_dept_subject.length; index++) {
                                    const element = delete_dept_subject[index];
                                    element.addEventListener("click", removeSubject);
                                }

                                // checkboxes
                                var our_subject = document.getElementsByClassName("our_subject");
                                for (let index = 0; index < our_subject.length; index++) {
                                    const element = our_subject[index];
                                    element.addEventListener("change", checkTableSubjects);
                                }

                                // all checkbox
                                cObj("our_subject").onchange = function () {
                                    var our_subject = document.getElementsByClassName("our_subject");
                                    var my_values = [];
                                    for (let index = 0; index < our_subject.length; index++) {
                                        const element = our_subject[index];
                                        element.checked = cObj("our_subject").checked;
                                        my_values.push(our_subject[index].value);
                                    }
                                    if (cObj("our_subject").checked) {
                                        cObj("save_selected_subjects").value = JSON.stringify(my_values);
                                        cObj("action_subject_details").classList.remove("hide");
                                    } else {
                                        cObj("save_selected_subjects").value = JSON.stringify([]);
                                        cObj("action_subject_details").classList.add("hide");
                                    }
                                }
                            } else {
                                cObj("subject_list_table_dept").innerHTML = "<p class='text-danger text-center'><span><i class='fas fa-exclamation-triangle'></i></span><br> This department has no Subjects</p>";
                            }
                        }
                    }
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);

    }
}

function checkTableSubjects() {
    var save_selected_subjects = valObj("save_selected_subjects");
    if (hasJsonStructure(save_selected_subjects)) {
        save_selected_subjects = JSON.parse(save_selected_subjects);
        if (this.checked == true) {
            save_selected_subjects.push(this.value);
        } else {
            var new_list = [];
            for (let index = 0; index < save_selected_subjects.length; index++) {
                const element = save_selected_subjects[index];
                if (element != this.value) {
                    new_list.push(element);
                }
            }
            save_selected_subjects = new_list;
        }
        cObj("save_selected_subjects").value = JSON.stringify(save_selected_subjects);
    } else {
        cObj("save_selected_subjects").value = JSON.stringify([this.value]);
    }

    var our_subject = document.getElementsByClassName("our_subject");
    var selected = 0;
    for (let index = 0; index < our_subject.length; index++) {
        const element = our_subject[index];
        if (element.checked) {
            selected++;
        }
    }

    if (selected == 0) {
        cObj("our_subject").indeterminate = false;
        cObj("our_subject").checked = false;
        cObj("action_subject_details").classList.add("hide");
    } else {
        cObj("action_subject_details").classList.remove("hide");
        if (our_subject.length == selected) {
            cObj("our_subject").indeterminate = false;
            cObj("our_subject").checked = true;
        } else {
            cObj("our_subject").indeterminate = true;
            cObj("our_subject").checked = false;
        }
    }
}

cObj("remove_subject_depf").onclick = function () {
    var datapass = "removeSubjects=true&department_id=" + valObj("dept_codes") + "&subject_list=" + valObj("save_selected_subjects");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("subject_error_handlers"), cObj("subject_dept_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("subject_dept_list_loader").classList.contains("hide")) {
                cObj("action_subject_details").classList.add("hide");
                setTimeout(() => {
                    cObj("back_to_department_list").click();
                    cObj("subject_error_handlers").innerHTML = "";
                }, 2000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function removeSubject() {
    var datapass = "removeSubject=true&subject_id=" + this.id.substr(20) + "&department=" + valObj("dept_codes");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("subject_error_handlers"), cObj("subject_dept_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("subject_dept_list_loader").classList.contains("hide")) {

                setTimeout(() => {
                    cObj("back_to_department_list").click();
                    cObj("subject_error_handlers").innerHTML = "";
                }, 2000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function checkDept() {
    var dept_data = document.getElementsByClassName("dept_data");
    var elements = [];
    for (let index = 0; index < dept_data.length; index++) {
        const element = dept_data[index];
        element.checked = this.checked;
        elements.push(element.value);
    }

    // save elements
    if (this.checked) {
        cObj("save_members").value = JSON.stringify(elements);
        cObj("action_dept").classList.remove("hide");
    } else {
        cObj("save_members").value = "[]";
        cObj("action_dept").classList.add("hide");
    }
}

function selectMembers() {
    // add its value
    var this_value = this.value;

    // display the value

    if (this.checked) {
        var save_members = valObj("save_members");
        if (hasJsonStructure(save_members)) {
            save_members = JSON.parse(save_members);

            // loop
            if (!isPresent_dept(save_members, this_value)) {
                save_members.push(this.value);
            }
            cObj("save_members").value = JSON.stringify(save_members);
        } else {
            cObj("save_members").value = JSON.stringify([this_value]);
        }
    } else {
        var save_members = valObj("save_members");
        if (hasJsonStructure(save_members)) {
            save_members = JSON.parse(save_members);

            // loop
            var new_members = [];
            for (let index = 0; index < save_members.length; index++) {
                const element = save_members[index];
                if (element != this_value) {
                    new_members.push(element);
                }
            }

            cObj("save_members").value = JSON.stringify(new_members);
        } else {
            cObj("save_members").value = "[]";
        }
    }

    // check the select all
    var dept_data = document.getElementsByClassName("dept_data");
    var all_checked = 0;
    for (let index = 0; index < dept_data.length; index++) {
        const element = dept_data[index];
        if (element.checked) {
            all_checked++;
        }
    }

    // all checked
    if (all_checked == 0) {
        cObj("dept_data").indeterminate = false;
        cObj("dept_data").checked = false;
        cObj("action_dept").classList.add("hide");
    } else {
        cObj("action_dept").classList.remove("hide");
        if (all_checked == dept_data.length) {
            cObj("dept_data").indeterminate = false;
            cObj("dept_data").checked = true;
        } else {
            cObj("dept_data").checked = false;
            cObj("dept_data").indeterminate = true;
        }
    }
}

cObj("remove_staff_depf").onclick = function () {
    // delete the selected data
    var datapass = "remove_staff=true&staff_lists=" + valObj("save_members");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("member_error_handlers"), cObj("members_dept_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("members_dept_list_loader").classList.contains("hide")) {
                cObj("back_to_department_list").click();
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function deleteElement() {
    var datapass = "delete_member=true&delete_member=" + this.id.substr(14) + "&department_id=" + cObj("dept_codes").value;
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("member_error_handlers"), cObj("members_dept_list_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("members_dept_list_loader").classList.contains("hide")) {
                cObj("back_to_department_list").click();
                setTimeout(() => {
                    cObj("member_error_handlers").innerHTML = "";
                }, 2000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function getSubjectDetails(subject_data, subject_id) {
    for (let index = 0; index < subject_data.length; index++) {
        const element = subject_data[index];
        if (element.subject_id == subject_id) {
            return [element.subject_name, element.display_name];
        }
    }
    return ["Not Set", "Not Set"];
}

cObj("back_to_department_list").onclick = function () {
    cObj("view_department_window").classList.add("hide");
    cObj("dept_table_display").classList.remove("hide");
    displayDepartments();
}

function getTeacherName(teacher_list, teacher_id) {

    for (let index = 0; index < teacher_list.length; index++) {
        const element = teacher_list[index];
        if (element.user_id == teacher_id) {
            return element.fullname;
        }
    }

    return "Null";
}

// update departments
cObj("update_departments").onclick = function () {
    var err = checkBlank("department_name_view");
    err += checkBlank("department_code_view");
    err += checkBlank("department_description_view");

    // my errors
    if (err == 0) {
        var val_hod = cObj("hod_view") != undefined ? valObj("hod_view") : "";
        var datapass = "update_departments=true&department_id=" + valObj("dept_codes") + "&department_name=" + valObj("department_name_view") + "&department_code=" + valObj("department_code_view") + "&description=" + valObj("department_description_view") + "&head_of_dept=" + val_hod;
        sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("loader_infor_teller_view"), cObj("department_loader_view"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("department_loader_view").classList.contains("hide")) {
                    displayDepartments();


                    // loader
                    setTimeout(() => {
                        cObj("loader_infor_teller_view").innerHTML = "";
                    }, 4000);
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function formatDate_1(dateString) {
    // Parse the input date string
    const year = dateString.substring(0, 4);
    const month = parseInt(dateString.substring(4, 6)) - 1; // Months are zero-based
    const day = parseInt(dateString.substring(6, 8));
    const hour = parseInt(dateString.substring(8, 10));
    const minute = parseInt(dateString.substring(10, 12));
    const second = parseInt(dateString.substring(12, 14));

    // Create a JavaScript Date object
    const date = new Date(year, month, day, hour, minute, second);

    // Define the weekday names and suffixes for ordinal numbers
    const weekdayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    const ordinalSuffixes = ["th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th"];

    // Get the weekday, day, month, hour, and minute in the desired format
    const weekday = weekdayNames[date.getDay()];
    const dayWithSuffix = day + ordinalSuffixes[day % 10];
    const monthName = new Intl.DateTimeFormat("en-US", { month: "long" }).format(date);
    const hour12 = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
    const period = date.getHours() >= 12 ? "PM" : "AM";

    // Format the date string
    const formattedDate = `${weekday} ${dayWithSuffix} ${monthName} ${year}`;

    return formattedDate;
}
function formatDate(dateString) {
    // Parse the input date string
    const year = dateString.substring(0, 4);
    const month = parseInt(dateString.substring(4, 6)) - 1; // Months are zero-based
    const day = parseInt(dateString.substring(6, 8));
    const hour = parseInt(dateString.substring(8, 10));
    const minute = parseInt(dateString.substring(10, 12));
    const second = parseInt(dateString.substring(12, 14));

    // Create a JavaScript Date object
    const date = new Date(year, month, day, hour, minute, second);

    // Define the weekday names and suffixes for ordinal numbers
    const weekdayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    const ordinalSuffixes = ["th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th"];

    // Get the weekday, day, month, hour, and minute in the desired format
    const weekday = weekdayNames[date.getDay()];
    const dayWithSuffix = day + ordinalSuffixes[day % 10];
    const monthName = new Intl.DateTimeFormat("en-US", { month: "long" }).format(date);
    const hour12 = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
    const period = date.getHours() >= 12 ? "PM" : "AM";

    // Format the date string
    const formattedDate = `${weekday} ${dayWithSuffix} ${monthName} ${year} @ ${hour12}:${minute.toString().padStart(2, "0")} ${period}`;

    return formattedDate;
}

cObj("close_adding_members").onclick = function () {
    cObj("add_memebers_dept_window").classList.add("hide");
}
cObj("close_window_add_member_dept").onclick = function () {
    cObj("add_memebers_dept_window").classList.add("hide");
}
cObj("add_members_dept").onclick = function () {
    cObj("add_memebers_dept_window").classList.remove("hide");
    cObj("display_dept_message").innerHTML = "";
    cObj("members_lists").value = "[]";
    var datapass = "getDepartments=true";
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("show_dept_lists"), cObj("add_members_dept_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("add_members_dept_loader").classList.contains("hide")) {
                // get tfrom the list who not include
                if (cObj("departments_value") != undefined) {
                    var departments_value = valObj("departments_value");
                    if (hasJsonStructure(departments_value)) {
                        var present_members = [];
                        // department value
                        departments_value = JSON.parse(departments_value);
                        var departments = departments_value.departments;
                        var teachers = departments_value.teachers;

                        // loop value
                        for (let index = 0; index < departments.length; index++) {
                            const element = departments[index];
                            for (let ind = 0; ind < element.members.length; ind++) {
                                const elem = element.members[ind];
                                present_members.push(elem.name);
                            }
                        }
                        console.log(present_members);

                        // loop to display the data for the teachers
                        var data_to_display = "<div class='classlist'>";
                        for (let index = 0; index < teachers.length; index++) {
                            if (!isPresent_dept(present_members, teachers[index].user_id)) {
                                const element = teachers[index];
                                data_to_display += "<div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>" +
                                    "<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='teachers_dept_" + teachers[index].user_id + "'>" + getTeacherName(teachers, teachers[index].user_id) + "</label>" +
                                    "<input class='update_expense_check teachers_dept' value='" + teachers[index].user_id + "' type='checkbox' id='teachers_dept_" + teachers[index].user_id + "'>" +
                                    "</div>";
                            }
                        }
                        data_to_display += "</div>";

                        cObj("member_list_window").innerHTML = data_to_display;

                        // add department value
                        var teachers_dept = document.getElementsByClassName("teachers_dept");
                        for (let index = 0; index < teachers_dept.length; index++) {
                            const element = teachers_dept[index];
                            element.addEventListener("click", selectDept);
                        }
                    }
                }
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function isPresent_dept(arrays, member) {
    for (let index = 0; index < arrays.length; index++) {
        const element = arrays[index];
        if (element == member) {
            return true;
        }
    }
    return false;
}

function selectDept() {
    var members_lists = valObj("members_lists");
    if (hasJsonStructure(members_lists)) {
        members_lists = JSON.parse(members_lists);
        if (this.checked) {
            members_lists.push(this.value);
        } else {
            var new_list = [];
            for (let index = 0; index < members_lists.length; index++) {
                const element = members_lists[index];
                if (element != this.value) {
                    new_list.push(element);
                }
            }
            members_lists = new_list;
        }
        cObj("members_lists").value = JSON.stringify(members_lists);
    } else {
        cObj("members_lists").value = JSON.stringify([this.value]);
    }

    var teachers_dept = document.getElementsByClassName("teachers_dept");
    var members = 0;
    for (let index = 0; index < teachers_dept.length; index++) {
        const element = teachers_dept[index];
        if (element.checked) {
            members++;
        }
    }
    // console.log([members,teachers_dept.length]);

    if (members == teachers_dept.length) {
        cObj("select_all_dept").indeterminate = false;
        cObj("select_all_dept").checked = true;
    }
    if (members != teachers_dept.length) {
        cObj("select_all_dept").indeterminate = true;
    }
}

cObj("add_names_inside").onclick = function () {
    var datapass = "save_new_members_data=true&department_code=" + valObj("dept_codes") + "&member_list=" + valObj("members_lists");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("display_dept_message"), cObj("add_members_dept_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("add_members_dept_loader").classList.contains("hide")) {
                cObj("back_to_department_list").click();
                setTimeout(() => {
                    cObj("close_window_add_member_dept").click();
                }, 1000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("select_all_dept").onchange = function () {
    var teachers_dept = document.getElementsByClassName("teachers_dept");
    var members_lists = [];
    for (let index = 0; index < teachers_dept.length; index++) {
        const element = teachers_dept[index];
        element.checked = this.checked;
        if (this.checked) {
            members_lists.push(element.value);
        }
    }

    // members list
    cObj("members_lists").value = JSON.stringify(members_lists);
}

cObj("upload_new_students_button").onclick = function () {
    var err = checkBlank("new_student_uploads");
    if (err == 0) {
        // show the progress bar
        cObj("upload_new_students").classList.remove("hide");

        // pick the file.
        var file = cObj("new_student_uploads").files[0];
        
        var formData = new FormData();
        formData.append("file", file);
        formData.append("upload_new_students", "new_student");
      
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/college_sims/ajax/administration/admissions.php", true);
        
        xhr.upload.onprogress = function (e) {
          if (e.lengthComputable) {
            var progress = (e.loaded / e.total) * 100;
            cObj("upload_new_students").value = progress;
          }
        };
      
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            if (hasJsonStructure(xhr.responseText)) {
                // hide progress bar
                cObj("upload_new_students").classList.add("hide");

                // set the values
                cObj("file_names").value = "";

                // get the response
                var response = JSON.parse(xhr.responseText);
                
                // check the message if its a success message
                if (response.success) {
                    cObj("error_message_holder_new_student").innerHTML = "<p class='text-success'>"+response.message+"</p>";
                }else{
                    cObj("error_message_holder_new_student").innerHTML = "<p class='text-danger'>"+response.message+"</p>";
                }
            }
            cObj("new_student_uploads").value = "";
          }
        };
        xhr.send(formData);
    }
}
cObj("add_subject_dept").onclick = function () {
    cObj("add_subjects_dept_window").classList.remove("hide");
    cObj("subjects_lists").value = "[]";
    cObj("select_all_subjects_dept").checked = false;
    var datapass = "getOurSubjectsList=true";
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("show_subjects_lists"), cObj("add_subjects_dept_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("add_subjects_dept_loader").classList.contains("hide")) {
                if (cObj("department_data_subjects") != undefined) {
                    var my_department = valObj("department_data_subjects");
                    if (hasJsonStructure(my_department)) {
                        var my_department = JSON.parse(my_department);

                        // loop through the departments to see all the subjects available
                        var subjects = my_department.subjects;
                        var departments = my_department.departments;


                        // present subjects
                        var subjects_present = [];
                        for (let index = 0; index < departments.length; index++) {
                            const element = departments[index];
                            var our_subs = element.subjects;
                            for (let ind = 0; ind < our_subs.length; ind++) {
                                const elem = our_subs[ind];
                                subjects_present.push(elem.name);
                            }
                        }

                        var data_to_display = "<div class='classlist bg-gray'>";
                        for (let index = 0; index < subjects.length; index++) {
                            const element = subjects[index];
                            if (!isPresent(subjects_present, element.subject_id)) {
                                data_to_display += "<div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>" +
                                    "<label style='margin-right:5px;cursor:pointer;font-size:12px;' for='dept_subject" + element.subject_id + "'>" + element.subject_name + " (" + element.display_name + ")</label>" +
                                    "<input class='update_expense_check dept_subject' type='checkbox' value='" + element.subject_id + "' id='dept_subject" + element.subject_id + "'>" +
                                    "</div>";
                            }
                        }
                        data_to_display += "</div>";
                        cObj("subject_list_window").innerHTML = data_to_display;

                        // set checkbox listener
                        var dept_subject = document.getElementsByClassName("dept_subject");
                        for (let index = 0; index < dept_subject.length; index++) {
                            const element = dept_subject[index];
                            element.addEventListener("change", checkSubject);
                        }
                    }
                }
                setTimeout(() => {
                    cObj("close_window_add_member_dept").click();
                }, 1000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function checkSubject() {
    var subjects_lists = valObj("subjects_lists");
    if (hasJsonStructure(subjects_lists)) {
        // subject list
        subjects_lists = JSON.parse(subjects_lists);

        if (this.checked) {
            subjects_lists.push(this.value);
        } else {
            var new_list = [];
            for (let index = 0; index < subjects_lists.length; index++) {
                const element = subjects_lists[index];
                if (element != this.value) {
                    new_list.push(element);
                }
            }
            subjects_lists = new_list;
        }
        cObj("subjects_lists").value = JSON.stringify(subjects_lists);
    } else {
        if (this.checked) {
            cObj("subjects_lists").value = JSON.stringify([this.value]);
        }
    }

    // check for all selected
    var selected = 0;
    var dept_subject = document.getElementsByClassName("dept_subject");
    for (let index = 0; index < dept_subject.length; index++) {
        const element = dept_subject[index];
        if (element.checked) {
            selected++;
        }
    }

    if (selected == 0) {
        cObj("select_all_subjects_dept").checked = false;
        cObj("select_all_subjects_dept").indeterminate = false;
    } else {
        if (selected == dept_subject.length) {
            cObj("select_all_subjects_dept").indeterminate = false;
            cObj("select_all_subjects_dept").checked = true;
        } else {
            cObj("select_all_subjects_dept").indeterminate = true;
            cObj("select_all_subjects_dept").checked = false;
        }
    }
}

cObj("select_all_subjects_dept").onchange = function () {
    var dept_subject = document.getElementsByClassName("dept_subject");
    var list = [];
    for (let index = 0; index < dept_subject.length; index++) {
        const element = dept_subject[index];
        element.checked = this.checked;
        if (this.checked) {
            list.push(element.value);
        }
    }

    cObj("subjects_lists").value = JSON.stringify(list);
}

cObj("close_adding_subjects").onclick = function () {
    cObj("add_subjects_dept_window").classList.add("hide");
}
cObj("close_window_add_subject_dept").onclick = function () {
    cObj("add_subjects_dept_window").classList.add("hide");
}

cObj("add_subjects_list_dept").onclick = function () {
    var datapass = "addSubjectInDept=true&subject_list=" + valObj("dept_codes") + "&subjects_lists=" + valObj("subjects_lists");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("display_subject_message"), cObj("add_subjects_dept_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("add_subjects_dept_loader").classList.contains("hide")) {
                cObj("back_to_department_list").click();
                setTimeout(() => {
                    cObj("close_window_add_subject_dept").click();
                    cObj("display_subject_message").innerHTML = "";
                }, 1000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("close_latest_updates_window_2").onclick = function () {
    cObj("latest_updates_window").classList.add("hide");
}

cObj("close_latest_updates_window").onclick = function () {
    cObj("latest_updates_window").classList.add("hide");
}

// display data
cObj("display_class_selection").onclick = function () {
    // check if the object is undefined
    if (cObj("selection_selected_class") != undefined) {
        // display the table with all the student data subject_details_in
        var datapass = "?get_student_search=true&class_selected="+valObj("selection_selected_class");
        sendData2("GET", "administration/admissions.php", datapass, cObj("subject_details_in"), cObj("exams_data_loaders"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("exams_data_loaders").classList.contains("hide")) {
                    var subject_selection_buttons = document.getElementsByClassName("subject_selection_buttons");
                    for (let index = 0; index < subject_selection_buttons.length; index++) {
                        const element = subject_selection_buttons[index];
                        element.addEventListener("click",selectStudentSubjects);
                    }
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

function selectStudentSubjects() {
    var id = this.id.substr(21);
    
    // get the class for each row representing all the checkboxes for the subjects the student is taught in class
    var all_checkboxes = document.getElementsByClassName("ch_"+id);
    var subjects_selected = [];
    for (let index = 0; index < all_checkboxes.length; index++) {
        const element = all_checkboxes[index];
        if (element.checked == true) {
            subjects_selected.push(element.value);
        }
    }

    // send data to the database
    var datapass = "subjects_for_student=true&student_admission="+id+"&student_subjects_chosen="+JSON.stringify(subjects_selected);
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("error_class_selection"), cObj("exams_data_loaders_"+id));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("exams_data_loaders_"+id).classList.contains("hide")) {
                setTimeout(() => {
                    cObj("error_class_selection").innerHTML = '';
                }, 3000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("back_to_teacher_data").onclick = function () {
    cObj("managetrnsub").click();
}

// add the courses
cObj("add_course").onclick = function () {
    cObj("add_course_window").classList.remove("hide");
    // get the courses levels
    var datapass = "?get_courses=true";
    cObj("level_available_course_name").innerHTML = "";
    cObj("level_available_course_name").innerText = "";
    sendData2("GET","administration/admissions.php",datapass,cObj("level_available_course_name"),cObj("add_course_clock"));
    
    // department id
    var datapass = "?get_departments_course_reg=true&dept_id=department_id";
    sendData2("GET","administration/admissions.php",datapass,cObj("department_list_window"),cObj("display_my_departments"));
}
cObj("close_add_course_window").onclick = function () {
    cObj("add_course_window").classList.add("hide");
}
cObj("close_add_course_win").onclick = function () {
    cObj("add_course_window").classList.add("hide");
}


// add the course material
cObj("add_course_btn").onclick = function () {
    // errors
    var err = checkBlank("course_input_text");
    err += cObj("course_list_setup") != undefined ? checkBlank("course_list_setup") : 1;
    err += checkBlank("no_of_terms");
    err += checkBlank("termly_fees");
    err += checkBlank("term_duration");
    err += checkBlank("duration_intervals");

    cObj("add_course_outputtxt").innerHTML = "";
    if (err == 0) {
        // proceed and save the course levels
        var dept_name = valObj("department_id");
        var datapass = "?add_course=true&course_name="+valObj("course_input_text")+"&course_level="+valObj("course_list_setup")+"&department_name="+dept_name;
        datapass += "&no_of_terms="+valObj("no_of_terms")+"&termly_fees="+valObj("termly_fees")+"&term_duration="+valObj("term_duration")+"&duration_intervals="+valObj("duration_intervals");
        sendData2("GET","administration/admissions.php",datapass, cObj("add_course_outputtxt"), cObj("add_course_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("add_course_clock").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("close_add_course_window").click();
                        cObj('course_input_text').value = "";
                        var course_level = document.getElementsByClassName("course_level");
                        for (let index = 0; index < course_level.length; index++) {
                            const element = course_level[index];
                            element.checked = false;
                        }
                        cObj("add_course_outputtxt").innerHTML = "";

                        // show the courses
                        get_courses();
                    }, 1000);
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }else{
        cObj("add_course_outputtxt").innerHTML = "<p class='text-danger'>Provide the course name to proceed!</p>";
    }
}

cObj("Edit_course_btn").onclick = function () {
    var err = checkBlank("course_edit_input_text");
    err += checkBlank("course_list_setup_edit");
    err += checkBlank("edit_no_of_terms");
    err += checkBlank("edit_termly_fees");
    err += checkBlank("edit_term_duration");
    err += checkBlank("edit_duration_intervals");
    cObj("edit_course_outputtxt").innerHTML = "";
    if (err == 0) {
        // proceed and save the course levels
        var dept_name = valObj("department_id_edit");
        var datapass = "?edit_course=true&course_name="+valObj("course_edit_input_text")+"&course_levels="+JSON.stringify([])+"&department_name="+dept_name+"&course_id="+valObj("course_id_holder");
        datapass += "&course_level="+valObj("course_list_setup_edit")+"&no_of_terms="+valObj("edit_no_of_terms")+"&termly_fees="+valObj("edit_termly_fees")+"&term_duration="+valObj("edit_term_duration");
        datapass += "&duration_intervals="+valObj("edit_duration_intervals");
        sendData2("GET","administration/admissions.php",datapass, cObj("edit_course_outputtxt"), cObj("edit_course_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("edit_course_clock").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("close_Edit_course_window").click();
                        cObj('course_edit_input_text').value = "";
                        var course_level = document.getElementsByClassName("course_level_edit");
                        for (let index = 0; index < course_level.length; index++) {
                            const element = course_level[index];
                            element.checked = false;
                        }
                        cObj("edit_course_outputtxt").innerHTML = "";

                        // show the courses
                        get_courses();
                    }, 1000);
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }else{
        cObj("edit_course_outputtxt").innerHTML = "<p class='text-danger'>Provide the course name to proceed!</p>";
    }
}

function get_courses() {
    var datapass = "?get_courses_list=true";
    sendData2("GET","administration/admissions.php",datapass,cObj("courses_holder"),cObj("course_list_clock"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("course_list_clock").classList.contains("hide")) {
                var edit_courses = document.getElementsByClassName("edit_courses");
                for (let index = 0; index < edit_courses.length; index++) {
                    const element = edit_courses[index];
                    element.addEventListener("click",editCourses);
                }

                // delete the course
                var remove_course = document.getElementsByClassName("remove_course");
                for (let index = 0; index < remove_course.length; index++) {
                    const element = remove_course[index];
                    element.addEventListener("click",deleteCourse);
                }
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function deleteCourse() {
    var this_id = this.id.substr(14);
    var course_data = valObj("hidden_value_courses_"+this_id);

    // change the course data
    if (hasJsonStructure(course_data)){
        course_data = JSON.parse(course_data);

        // display the confirmation window.
        cObj("delete_course_parmenently").classList.remove("hide");
        cObj("course_name_placeholder").innerText = course_data.course_name;

        // store the course id
        cObj("course_id_holder_delete").value = course_data.id;
    }
}

cObj("no_delete_permanently_course").onclick = function () {
    cObj("delete_course_parmenently").classList.add("hide");
}

// 
cObj("yes_delete_permanently_course").onclick = function () {
    var datapass = "?delete_course=true&course_id="+valObj("course_id_holder_delete");
    sendData2("GET","administration/admissions.php",datapass,cObj("error_handler_course_del"), cObj("delete_course_pamernently"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("delete_course_pamernently").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("no_delete_permanently_course").click();
                    cObj("error_handler_course_del").innerHTML = "";

                    // show the courses
                    get_courses();
                }, 1000);
                // stop
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}


function editCourses() {
    var this_id = this.id.substr(12);
    var course_data = valObj("hidden_value_courses_"+this_id);

    // change the course data
    if (hasJsonStructure(course_data)) {
        course_data = JSON.parse(course_data);
        cObj("edit_course_window").classList.remove("hide");

        // set course name
        cObj("course_edit_input_text").value = course_data.course_name;
        cObj("course_id_holder").value = course_data.id;
        cObj("edit_no_of_terms").value = course_data.no_of_terms;
        cObj("edit_term_duration").value = course_data.term_duration;
        cObj("edit_termly_fees").value = course_data.termly_fees;
        var children = cObj("edit_duration_intervals").children;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if (element.value == course_data.duration_intervals) {
                element.selected = true;
            }
        }


        // get the levels
        // get the courses levels
        var datapass = "?get_courses_edit=true&course_level="+(course_data.course_level != undefined ? course_data.course_level : "");
        cObj("level_available_course_name_edit").innerHTML = "";
        sendData2("GET","administration/admissions.php",datapass,cObj("level_available_course_name_edit"),cObj("edit_course_clock"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("edit_course_clock").classList.contains("hide")) {
                    // loop through the checkboxes
                    var leave_data = document.getElementsByClassName("course_level_edit");
                    for (let index = 0; index < leave_data.length; index++) {
                        const element = leave_data[index];
                        var course_levels = hasJsonStructure(course_data.course_levels) ? JSON.parse(course_data.course_levels) : [];
                        
                        // loop through to get the data
                        for (let ind = 0; ind < course_levels.length; ind++) {
                            const elem = course_levels[ind];
                            if (element.value == elem) {
                                element.checked = true;
                                break;
                            }
                        }
                    }
                    // stop
                    stopInterval(ids);
                }
            }, 100);
        }, 100);

        // get the department details
        // department id
        var datapass = "?get_departments_course_reg=true&dept_id=department_id_edit";
        sendData2("GET","administration/admissions.php",datapass,cObj("department_list_window_edit"),cObj("display_my_departments_edit"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("display_my_departments_edit").classList.contains("hide")) {
                    // stop
                    var children = cObj("department_id_edit").children;
                    for (let index = 0; index < children.length; index++) {
                        const element = children[index];
                        if (element.value == course_data.department) {
                            element.selected = true;
                            break;
                        }
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);

    }else{

    }
}


cObj("close_Edit_course_window").onclick = function () {
    cObj("edit_course_window").classList.add("hide");
}
cObj("close_edit_course_win").onclick = function () {
    cObj("edit_course_window").classList.add("hide");
}

cObj("add_modules").onclick = function () {
    var datapass = "add_modules=true&student_id="+valObj("adminnos");
    sendDataPost("POST", "ajax/administration/admissions.php", datapass, cObj("error_handler_course_progress"), cObj("add_course_modules_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("save_course_progress_loader").classList.contains("hide")) {
                cObj("view"+valObj("adminnos")).click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}