cObj("showfeesstructure").onclick = function () {
    if (cObj("daros") != "undefined" && cObj("daros") != null) {
        var err = checkBlank("daros");
        err += cObj("search_fees_course_list") == undefined ? 1 : 0;

        // continue if there is not error
        if (err == 0) {
            if (checkBlank("search_fees_course_list") == 1) {
                cObj("displayfin").innerHTML = "<p style='color:red;'>Select fees course list to proceed!</p>";
                return 0;
            }

            // get the datapass
            var datapass = "?feesstructurefind=true&class=" + valObj("daros")+"&course_id="+valObj("search_fees_course_list");
            sendData1("GET", "finance/financial.php", datapass, cObj("displayfin"));
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("loadings").classList.contains("hide")) {
                        var removef_ee = document.getElementsByClassName("removef_ee");
                        //delete the fee
                        for (let index = 0; index < removef_ee.length; index++) {
                            const element = removef_ee[index];
                            element.addEventListener("click", removeFees);
                        }
                        var edit_feeser = document.getElementsByClassName("edit_feeser");
                        for (let index = 0; index < edit_feeser.length; index++) {
                            const element = edit_feeser[index];
                            element.addEventListener("click", editFees);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 1000);
        } else {
            cObj("displayfin").innerHTML = "<p style='color:red;'>Select class to proceed!</p>";
        }
    } else {
        alert("Its null");
    }
}

cObj("back_to_fees_payment").onclick = function () {
    cObj("payfeess").click();
}
cObj("back_to_fees_payment_2").onclick = function () {
    cObj("payfeess").click();
}

cObj("return_to_revenue_list").onclick = function () {
    cObj("show_revenue_list").classList.remove("hide");
    cObj("add_revenues").classList.add("hide");
    getRevenue();

}

cObj("save_revenue").onclick = function () {
    var err = 0;
    err += checkBlank("revenue_name");
    err += checkBlank("revenue_amount");
    err += checkBlank("revenue_date");
    err += checkBlank("customer_name");
    err += checkBlank("revenue_cash_activity");
    // err += (cObj("revenue_categories") != undefined) ? 0 : 1;
    // console.log(cObj("revenue_category"));
    
    if(cObj("revenue_category") == undefined){
        cObj("error_handler_revenue_collection").innerHTML = "<p class='text-danger'>Set up the revenue categories before you proceed!</p>";
        return 0;
    }else{
        cObj("error_handler_revenue_collection").innerHTML = "";
    }

    if (err == 0) {
        cObj("error_handler_revenue_collection").innerHTML = "<p class='text-danger'></p>";
        let datapass = "add_revenue=true&revenue_name="+valObj("revenue_name")+"&revenue_amount="+valObj("revenue_amount")+"&revenue_date="+valObj("revenue_date")+"&customer_name="+valObj("customer_name")+"&customer_contacts_revenue="+valObj("customer_contacts_revenue")+"&contact_person="+valObj("contact_person")+"&revenue_description="+valObj("revenue_description")+"&revenue_categories="+valObj("revenue_category")+"&revenue_cash_activity="+valObj("revenue_cash_activity")+"&reportable_status="+valObj("reportable_status");
        datapass += "&mode_of_revenue_payment="+valObj("mode_of_revenue_payment")+"&payment_code="+valObj("payment_code")+"&revenue_sub_category="+valObj("add_revenue_sub_category");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("error_handler_revenue_collection"),cObj("save_revenue_loader"));
        setTimeout(() => {
            var ids = setInterval(() => {
                // remove the values from the input fields
                if (cObj("save_revenue_loader").classList.contains("hide")) {
                    cObj("revenue_name").value = "";
                    cObj("revenue_amount").value = "";
                    cObj("customer_name").value = "";
                    cObj("customer_contacts_revenue").value = "";
                    cObj("contact_person").value = "";
                    cObj("revenue_description").value = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }else{
        // tell users to check for the errors
        cObj("error_handler_revenue_collection").innerHTML = "<p class='text-danger'>Check for errors from fields having red borders!</p>";
    }
}

function getRevenue(page = 1) {
    var datapass = "get_revenue=true&page_req="+page;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("show_revenue_values"),cObj("show_revenue_loader"));
    setTimeout(() => {
        var ids = setInterval(() => {
            // remove the values from the input fields
            if (cObj("show_revenue_loader").classList.contains("hide")) {
                // convert the string to jsosn
                var show_revenue_values = cObj("show_revenue_values").innerText;
                if (hasJsonStructure(show_revenue_values)) {
                    // conver to json
                    show_revenue_values = JSON.parse(show_revenue_values);
                    
                    // set the navigator
                    cObj("page_number").innerText = "Page "+show_revenue_values.current_page+" of "+show_revenue_values.total_pages;
                    cObj("page_value_income").value = show_revenue_values.current_page;
                    cObj("maximum_page_income").value = show_revenue_values.total_pages;
                    
                    // if the total number of pages is more than one activate the nex button
                    if (show_revenue_values.total_pages > 1 && show_revenue_values.current_page < show_revenue_values.total_pages) {
                        cObj("next_income_data").classList.remove("disabled");
                    }
                    // display the table
                    display_revenue(show_revenue_values.data,show_revenue_values.start_from);
                }else{
                    // no data to display
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

function display_revenue(data,start_from) {
    var data_to_display = "<br><h4 class='text-center'><u>Revenue List</u></h4><table class='table'><thead><tr><th>No.</th><th>Name</th><th>Amount</th><th>Date Recorded.</th><th>Revenue Name.</th><th>Contact Person</th><th>Customer Contact</th><th>Action</th></tr></thead><tbody id='revenue_lists_all_display'>";
    for (let index = 0; index < data.length; index++) {
        const element = data[index];
        data_to_display+="<tr><td><input hidden value='"+JSON.stringify(element)+"' id='revenue_values_"+element.id+"'>"+(start_from+index+1)+"</td><td>"+element.name+"</td><td>Kes "+comma3(element.amount)+"</td><td>"+formatDate_1(element.date_recorded+"000000")+"</td><td>"+element.revenue_category_name+"</td><td>"+element.contact_person+"</td><td>"+element.customer_contact+"</td><td><span style='font-size:12px;' class='link edit_revenue_window' id='edit_revenue_window_"+element.id+"'><i class='fa fa-pen-fancy'></i> Edit </span> <br> <span style='font-size:12px;' class='link delete_revenue_window' id='delete_revenue_window_"+element.id+"'><i class='fa fa-trash'></i> Del</span> <br> <a target='_blank' href='/college_sims/reports/reports.php?revenue_receipt=true&revenue_details="+JSON.stringify(element)+"'><span style='font-size:12px;'><i class='fa fa-print'></i> Print</span></a></td></tr>";
    }
    data_to_display+="</tbody></table>";

    // inner html
    cObj("revenue_data").innerHTML = data_to_display;

    // set the listeners
    var edit_revenue_window = document.getElementsByClassName("edit_revenue_window");
    for (let index = 0; index < edit_revenue_window.length; index++) {
        const element = edit_revenue_window[index];
        element.addEventListener("click",edit_revenues);
    }

    var delete_revenue_window = document.getElementsByClassName("delete_revenue_window");
    for (let index = 0; index < delete_revenue_window.length; index++) {
        const element = delete_revenue_window[index];
        element.addEventListener("click",delete_revenue);
    }
}

cObj("search_school_revenue").onkeyup = function () {
    if (this.value.length > 0) {
        var revenue_lists_all_display = cObj("revenue_lists_all_display").children;
        for (let index = 0; index < revenue_lists_all_display.length; index++) {
            const element = revenue_lists_all_display[index];
            // check if the children except the last one if they have the value of the keyword
            let present = 0;
            for (let ind = 0; ind < (element.children.length-1); ind++) {
                const elem = element.children[ind];
                if (elem.innerText.toLowerCase().includes(this.value.toLowerCase())) {
                    present++;
                }
            }

            // check if its present
            if (present > 0) {
                element.classList.remove("hide");
            }else{
                element.classList.add("hide");
            }
        }
    }else{
        var revenue_lists_all_display = cObj("revenue_lists_all_display").children;
        for (let index = 0; index < revenue_lists_all_display.length; index++) {
            const element = revenue_lists_all_display[index];
            // check if the children except the last one if they have the value of the keyword
            element.classList.remove("hide");
        }
    }
}

function delete_revenue() {
    // get the row valued
    var row_id = this.id.substr(22);
    var row_value = valObj("revenue_values_"+row_id);
    
    // get the row value
    row_value = JSON.parse(row_value);

    cObj("revenue_name_holder").innerText = row_value.name;
    cObj("revenue_date_of_recording").innerText = formatDate_1(row_value.date_recorded+"000000");
    cObj("revenue_amount_recorded").innerText = "Kes "+comma3(row_value.amount);
    cObj("revenue_id_delete").value = row_value.id;

    // display the delete confirmation window
    cObj("confirm_revenue_delete").classList.remove("hide");
}

cObj("confirm_delete_revenue").onclick = function () {
    var revenue_id_delete = valObj("revenue_id_delete");
    let datapass = "delete_revenue=true&revenue_id="+revenue_id_delete;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("error_handler_general_revenue"),cObj("load_delete_revenue"));
    setTimeout(() => {
        var ids = setInterval(() => {
            // remove the values from the input fields
            if (cObj("load_delete_revenue").classList.contains("hide")) {
                cObj("confirm_Delete_revenue_no").click();
                getRevenue();
                setTimeout(() => {
                    cObj("error_handler_general_revenue").innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }
        }, 100);
    }, 100);
}

cObj("next_income_data").onclick = function () {
    let current_page = valObj("page_value_income");
    current_page*=1;
    current_page+=1;
    let total_page = valObj("maximum_page_income");
    total_page*=1;

    // disanle
    if (current_page === total_page) {
        console.log(total_page===current_page);
        cObj("next_income_data").classList.add("disabled");
        cObj("next_income_data").classList.add("disabled");
    }

    // get the revenue
    getRevenue(current_page);

    // activate the previous button
    cObj("previous_income_data").classList.remove("disabled");
}

cObj("previous_income_data").onclick = function () {
    let current_page = valObj("page_value_income");
    current_page*=1;
    let total_page = valObj("maximum_page_income");
    total_page*=1;
    if (current_page == total_page) {
        cObj("next_income_data").classList.remove("disabled");
    }
    current_page-=1;

    // get the revenue
    getRevenue(current_page);

    // activate the previous button
    if (current_page == 1) {
        cObj("previous_income_data").classList.add("disabled");
    }
}

function edit_revenues() {
    // get the row valued
    var row_id = this.id.substr(20);
    var row_value = valObj("revenue_values_"+row_id);
    // console.log(row_value);

    // get the row value
    row_value = JSON.parse(row_value);
    
    // check if the revenue category holder is set
    var datapass = "get_revenue_categories=true&revenue_id=edit_revenue_category";
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("edit_revenue_category_holder"),cObj("edit_revenue_categories_loader"));
    setTimeout(() => {
        var ids = setInterval(() => {
            // remove the values from the input fields
            if (cObj("edit_revenue_categories_loader").classList.contains("hide")) {
                if (cObj("edit_revenue_category") != undefined) {
                    // proceed and select the selected category
                    var children = cObj("edit_revenue_category").children;
                    for (let index = 0; index < children.length; index++) {
                        const element = children[index];
                        if (element.value == row_value.revenue_category) {
                            element.selected = true;
                        }
                    }

                    // get the revenue sub category
                    var revenue_sub_categories = (row_value.revenue_sub_category != null ? row_value.revenue_sub_category : "");
                    revenue_sub_categories_2(row_value.revenue_category,revenue_sub_categories,"edit_revenue_sub_category");

                    // get the revenue category
                    cObj("edit_revenue_category").addEventListener("change",function () {
                        revenue_sub_categories_2(this.value,"","edit_revenue_sub_category");
                    });
                }
                stopInterval(ids);
            }
        }, 10);
    }, 10);
    
    // set the value for the revenue cash flow activity
    var select_children = cObj("edit_revenue_cash_activity").children;
    select_children[0].selected = true;
    for (let index = 0; index < select_children.length; index++) {
        const element = select_children[index];
        if (element.value == row_value.cash_flow_activities) {
            element.selected = true;
        }
    }

    // set the reportable status
    var reportable_status_edit = cObj("reportable_status_edit").children;
    reportable_status_edit[0].selected = true;
    for (let index = 0; index < reportable_status_edit.length; index++) {
        const element = reportable_status_edit[index];
        if (element.value == row_value.reportable_status) {
            element.selected = true;
        }
    }

    // set the mode of payment
    var mode_of_revenue_payment_edit = cObj("mode_of_revenue_payment_edit").children;
    mode_of_revenue_payment_edit[0].selected = true;
    for (let index = 0; index < mode_of_revenue_payment_edit.length; index++) {
        const element = mode_of_revenue_payment_edit[index];
        if (element.value == row_value.mode_of_payment) {
            element.selected = true;
        }
    }

    cObj("payment_code_edit").value = row_value.payment_code;

    // fill all fields with the row data
    cObj("revenue_name_edit").value = row_value.name;
    cObj("revenue_amount_edit").value = row_value.amount;
    cObj("revenue_date_edit").value = row_value.date_recorded;
    cObj("customer_name_edit").value = row_value.customer_name;
    cObj("customer_contacts_revenue_edit").value = row_value.customer_contact;
    cObj("contact_person_edit").value = row_value.contact_person;
    cObj("revenue_description_edit").value = row_value.revenue_description;
    cObj("revenue_ids").value = row_value.id;

    // set the values to the listeners and open the editor window
    cObj("edit_revenues").classList.remove("hide");
    cObj("show_revenue_list").classList.add("hide");
}

// update the revenue details
cObj("save_revenue_edit").onclick = function () {
    var err = 0;
    err += checkBlank("revenue_name_edit");
    err += checkBlank("revenue_amount_edit");
    err += checkBlank("revenue_date_edit");
    err += checkBlank("customer_name_edit");
    err += checkBlank("customer_contacts_revenue_edit");
    err += checkBlank("contact_person_edit");
    err += checkBlank("reportable_status");
    err += checkBlank("edit_revenue_cash_activity");
    // err += checkBlank("revenue_description_edit");

    if (cObj("edit_revenue_category") == undefined) {
        cObj("error_handler_revenue_collection_edit").innerHTML = "<p class='text-danger'>Set up your revenue categories before updating a record!</p>";
    }

    if (err == 0) {
        cObj("error_handler_revenue_collection_edit").innerHTML = "<p class='text-danger'></p>";
        let datapass = "update_revenue=true&revenue_name="+valObj("revenue_name_edit")+"&revenue_amount="+valObj("revenue_amount_edit")+"&revenue_date="+valObj("revenue_date_edit")+"&customer_name="+valObj("customer_name_edit")+"&customer_contacts_revenue="+valObj("customer_contacts_revenue_edit")+"&contact_person="+valObj("contact_person_edit")+"&revenue_description="+valObj("revenue_description_edit")+"&revenue_id="+valObj("revenue_ids")+"&revenue_category="+valObj("edit_revenue_category")+"&edit_revenue_cash_activity="+valObj("edit_revenue_cash_activity")+"&reportable_status_edit="+valObj("reportable_status_edit");
        datapass += "&mode_of_revenue_payment_edit="+valObj("mode_of_revenue_payment_edit")+"&payment_code_edit="+valObj("payment_code_edit")+"&revenue_sub_category="+valObj("edit_revenue_sub_category");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("error_handler_revenue_collection_edit"),cObj("update_revenue_loader"));
        setTimeout(() => {
            var ids = setInterval(() => {
                // remove the values from the input fields
                if (cObj("update_revenue_loader").classList.contains("hide")) {
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }else{
        // tell users to check for the errors
        cObj("error_handler_revenue_collection_edit").innerHTML = "<p class='text-danger'>Check for errors from fields having red borders!</p>";
    }
}

cObj("return_to_revenue_list_edit").onclick = function () {
    cObj("edit_revenues").classList.add("hide");
    cObj("show_revenue_list").classList.remove("hide");
    getRevenue();
    cObj("error_handler_revenue_collection_edit").innerHTML = "";
}

cObj("confirm_Delete_revenue_no").onclick = function () {
    // remove the revenue DELETE CONFIRMATION WINDOW
    cObj("confirm_revenue_delete").classList.add("hide");
}

cObj("add-revenue-btn").onclick = function () {
    cObj("show_revenue_list").classList.add("hide");
    cObj("add_revenues").classList.remove("hide");
    cObj("revenue_sub_categories_list").innerHTML = "<p class='text-danger'>Select revenue to display revenue sub-categories!</p>";
    cObj("revenue_categories_list").innerHTML = "<p class='text-danger'>Revenue categories will appear here!</p>";
    display_revenue_category();
}

function display_revenue_category() {
    var datapass = "get_revenue_categories=true&revenue_id=revenue_category";
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("revenue_categories_list"),cObj("show_revenue_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("show_revenue_loader").classList.contains("hide")) {
                if(cObj("revenue_category") != undefined){
                    cObj("revenue_category").addEventListener("change",function () {
                        revenue_sub_categories(this.value,"","add_revenue_sub_category");
                    });
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}


function revenue_sub_categories(value,id,element_id) {
    // get the revenue subcategories.
    var datapass = "?get_revenue_sub_category=true&revenue_value="+value+"&sub_revenue_id="+id+"&element_id="+element_id;
    sendData2("GET","administration/admissions.php",datapass,cObj("revenue_sub_categories_list"),cObj("revenue_sub_categories_loaders"));
}

function revenue_sub_categories_2(value,id,element_id) {
    // get the revenue subcategories.
    var datapass = "?get_revenue_sub_category=true&revenue_value="+value+"&sub_revenue_id="+id+"&element_id="+element_id;
    sendData2("GET","administration/admissions.php",datapass,cObj("edit_revenue_sub_categories"),cObj("edit_revenue_sub_categories_loader"));
}

function editFees() {
    //get the values from the table
    var fees_id = this.id.substr(3);
    var fees_structure = hasJsonStructure(valObj("fees_structure_value_"+fees_id)) ? JSON.parse(valObj("fees_structure_value_"+fees_id)) : [];

    if (typeof fees_structure == "object") {
        // console.log(fees_structure);
        cObj("exp_name1").value = fees_structure['expenses'];
        cObj("term_one1").value = fees_structure['TERM_1'];
        cObj("term_two1").value = fees_structure['TERM_2'];
        cObj("term_three1").value = fees_structure['TERM_3'];
        cObj("original_exp_name").innerText = fees_structure['expenses'];
        var proles = fees_structure['roles'];
        cObj(proles + "12").selected = true;
        cObj("fee_id_s").innerText = fees_id;
        cObj("course_id_edit").value = fees_structure['course'];

        // get the course list and select the course

        //show class list
        getClasses("class_list_fees_update","fees_structure_edit_level","","load_course_levels_edit");
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("load_course_levels_edit").classList.contains("hide")) {
                    // set the selected course level
                    var children = cObj("fees_structure_edit_level").children;
                    for (let index = 0; index < children.length; index++) {
                        const element = children[index];
                        if (element.value == fees_structure['classes']) {
                            element.selected = true;
                            break;
                        }
                    }

                    // add an event listener
                    cObj("fees_structure_edit_level").addEventListener("change",F1);

                    // get the course level
                    var datapass = "?get_fees_struct_courses=true&course_level="+fees_structure['classes']+"&course_id="+fees_structure['course'];
                    sendData2("GET","finance/financial.php",datapass,cObj("course_list_details"),cObj("course_list_edits_loader"));

                    // get the selected 
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
    //show the window
    cObj("add_expense_update").classList.remove("hide");
}

function F1() {
    var datapass = "?get_fees_struct_courses=true&course_level="+this.value+"&course_id=0";
    sendData2("GET","finance/financial.php",datapass,cObj("course_list_details"),cObj("course_list_edits_loader"));
}

function checkPresent(array, strings) {
    if (array.length > 0) {
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            if (element.trim() == strings.trim()) {
                return 1;
            }
        }
    }
    return 0;
}
function removeFees() {
    var fee_id = this.id.substr(7);
    var expensename = cObj("expense_name" + fee_id).innerText;
    cObj("expensenamed").innerText = expensename;
    cObj("record_ids").innerText = fee_id;
    cObj("delete_fee_win").classList.remove("hide");
}
cObj("confirm_yes_fees").onclick = function () {
    //get the record id
    var fee_id = cObj("record_ids").innerText;
    var datapass = "?delete_fee=" + fee_id;
    sendData1("GET", "finance/financial.php", datapass, cObj("removeer_fees"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                cObj("delete_fee_win").classList.add("hide");
                cObj("showfeesstructure").click();
                stopInterval(ids);
            }
        }, 100);
    }, 1000);
}
cObj("confirm_no_fees").onclick = function () {
    cObj("delete_fee_win").classList.add("hide");
}
cObj("modeofpay").onchange = function () {
    var thisvalue = this.value;
    if (thisvalue == "mpesa") {
        cObj("mpesad").classList.remove("hide");
        cObj("banksd").classList.add("hide");
        cObj("cash").classList.add("hide");
        cObj("btns").classList.remove("hide");
        cObj("edit_supporting_documents").classList.remove("hide");
    } else if (thisvalue == "cash") {
        cObj("mpesad").classList.add("hide");
        cObj("banksd").classList.add("hide");
        cObj("cash").classList.remove("hide");
        cObj("btns").classList.remove("hide");
        cObj("edit_supporting_documents").classList.add("hide");
    } else if (thisvalue == "bank") {
        cObj("mpesad").classList.add("hide");
        cObj("banksd").classList.remove("hide");
        cObj("cash").classList.add("hide");
        cObj("btns").classList.remove("hide");
        cObj("edit_supporting_documents").classList.remove("hide");
    }
}
cObj("modeofpay").onclick = function () {
    cObj("makepayments").classList.remove("hide");
}
cObj("searchfin1").onclick = function () {
    var err = 0;
    err += checkBlank("studids");
    if (err == 0) {
        var datapass = "?findadmno=" + valObj("studids");
        sendData1("GET", "finance/financial.php", datapass, cObj("paymentsresults"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loadings").classList.contains("hide")) {
                    var className = document.getElementsByClassName("reverse");
                    setReverselistener(className);
                    stopInterval(ids);
                    //get the vote head only for that specific class
                    var classes = document.getElementsByClassName("class_studs_in");
                    getVoteHead(classes,valObj("studids"));
                    if (cObj("closed_balance") != null) {
                        cObj("closed_balance").addEventListener("click", showBalanceInput);
                        cObj("accBalance").addEventListener("click", acceptBalance);
                        cObj("rejectBalances").addEventListener("click", closeAcceptBalance);
                    }
                }
            }, 100);
        }, 1000);
    } else {
        cObj("paymentsresults").innerHTML = "<p style='color:red;'>Enter an admission number to proceed!</p>";
    }
}
function getVoteHead(classname,student_id) {
    if (classname.length > 0) {
        for (let index = 0; index < classname.length; index++) {
            if (index > 0) {
                break;
            }
            const element = classname[index];
            var myclass = element.innerText;
            voterHeads(myclass,student_id);
        }
    }
}
function voterHeads(classin,student_id) {
    //get the payment details here
    var course_value = cObj("course_value_finance") != undefined ? valObj("course_value_finance") : "0";
    var datapass2 = "?payfordetails=true&class_use=" + classin+"&student_admission="+student_id+"&course_value="+course_value;
    sendData("GET", "finance/financial.php", datapass2, cObj("payments"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                var votehead_checks = document.getElementsByClassName("votehead_checks");
                for (let index = 0; index < votehead_checks.length; index++) {
                    const element = votehead_checks[index];
                    element.addEventListener("change", function () {
                        if (this.checked) {
                            cObj("votehead_amounts_"+this.id.substring(16, this.id.length)).classList.remove("disabled");
                            cObj("votehead_amounts_"+this.id.substring(16, this.id.length)).disabled = false;
                        }else{
                            cObj("votehead_amounts_"+this.id.substring(16, this.id.length)).classList.add("disabled");
                            cObj("votehead_amounts_"+this.id.substring(16, this.id.length)).disabled = true;
                        }
                    });
                }

                var votehead_amounts  = document.getElementsByClassName("votehead_amounts ");
                for (let index = 0; index < votehead_amounts .length; index++) {
                    const element = votehead_amounts [index];
                    element.addEventListener("keyup", function () {
                        var va = document.getElementsByClassName("votehead_amounts ");
                        var total_fees = 0;
                        for (let index = 0; index < va.length; index++) {
                            const elem = va[index];
                            if (!elem.classList.contains("disabled")) {
                                total_fees += elem.value*1;
                            }
                        }
                        console.log(total_fees);
                        cObj("amount2").value = total_fees;
                        cObj("amount1").value = total_fees;
                    });
                }
                stopInterval(ids);
            }
        }, 100);
    }, 1000);
}
function setReverselistener(className) {
    if (className.length > 0) {
        for (let index = 0; index < className.length; index++) {
            const element = className[index];
            element.addEventListener("click", reverseListener);
        }
    }
}
function reverseListener() {
    var datapass = "?transactionid=" + this.id + "&amount_reverse=" + cObj("reverse_amount" + this.id).innerText + "&students_id_ddds=" + cObj("students_id_ddds").innerText;
    sendData1("GET", "finance/financial.php", datapass, cObj("reversehandler"));
    setTimeout(() => {
        var ids = setInterval(() => {
            if (cObj("loadings").classList.contains("hide")) {
                cObj("searchfin1").click();
                stopInterval(ids);
            }
        }, 100);
    }, 1000);
}
cObj("makepayments").onclick = function () {
    //first check if the student information has been populated

    if (typeof (cObj("presented")) != 'undefined' && cObj("presented") != null) {
        grayBorder(cObj("studids"));
        cObj("geterrorpay").innerHTML = "<p style='color:red;font-size:12px;'></p>";
        if (typeof (cObj("payfor")) != 'undefined' && cObj("payfor") != null) {
            var errs = checkErrors();
            if (cObj("modeofpay").value == "mpesa") {
                if (cObj("mpesa_code_err").innerText.length > 1) {
                    errs++;
                    redBorder(cObj("mpesacode"));
                } else {
                    grayBorder(cObj("mpesacode"));
                }
            } else if (cObj("modeofpay").value == "bank") {
                if (cObj("bank_code_errs").innerText.length > 1) {
                    errs++;
                    redBorder(cObj("bankcode"));
                } else {
                    grayBorder(cObj("bankcode"));
                }
            } else if (cObj("modeofpay").value == "cash"){
                if (cObj("amount3").innerText.length > 0) {
                    errs++;
                    redBorder(cObj("amount3"));
                }else{
                    grayBorder(cObj("amount3"));
                }
            }
            if (errs == 0) {
                // get the payment for details
                var total_fees_paid = 0;
                var votehead_paid_for = [];
                var votehead_amounts = document.getElementsByClassName("votehead_amounts");
                for (let index = 0; index < votehead_amounts.length; index++) {
                    const element = votehead_amounts[index];
                    if (!element.classList.contains("disabled")) {
                        var votehead_details = cObj("course_details_"+element.id.substring(17, element.id.length)).value;
                        var json_votehead_details = hasJsonStructure(votehead_details) ? JSON.parse(votehead_details) : [];
                        var payment_details = {
                            id: json_votehead_details.length == 0 ? "_"+index : json_votehead_details.fees_id,
                            name: json_votehead_details.length == 0 ? "Undefined Fees" : json_votehead_details.fees_name,
                            amount_paid: element.value,
                            roles: json_votehead_details.length == 0 ? "compulsory" : json_votehead_details.fees_role
                        };
                        votehead_paid_for.push(payment_details);
                        total_fees_paid += element.value*1;
                    }
                }

                if(votehead_paid_for.length == 0){
                    cObj("geterrorpay").innerHTML = "<p class='text-danger'>No voteheads to pay for selected!</p>";
                    return 0;
                }

                var collected_fees = valObj("modeofpay") == "mpesa" ? valObj("amount1") : valObj("amount2");
                console.log(collected_fees +" "+ total_fees_paid);
                if (total_fees_paid != collected_fees) {
                    redBorder(cObj("amount1"));
                    redBorder(cObj("amount2"));
                    cObj("geterrorpay").innerHTML = "<p class='text-danger'>The total votehead amount does not match the total fees to be paid!</p>";
                    return 0;
                }else{
                    grayBorder(cObj("amount1"));
                    grayBorder(cObj("amount2"));
                }
                
                // payment for
                cObj("payment_for_details").value = JSON.stringify(votehead_paid_for);
                

                // student names
                cObj("nameofstudents").innerText = cObj("std_names").innerText;
                cObj("reprint").value = "false";
                // display the confirmation window
                cObj("confirmpayments").classList.remove("hide");
                cObj("date_of_payments_fees_holder").value = valObj("date_of_payments_fees");
                cObj("time_of_payment_fees_holder").value = valObj("time_of_payment_fees");
                cObj("fees_payment_opt_holder").value = valObj("select_time_set_option1");
            } else {
                cObj("geterrorpay").innerHTML = "<p style='color:red;font-size:12px;'>Check for errors and fill all the fields having red borders</p>";
            }
        } else {
            cObj("geterrorpay").innerHTML = "<p style='color:red;'>Populate the student information before proceeding or contact your administrator there might be an issue with the system configuration.</p>"
        }
    } else {
        redBorder(cObj("studids"));
        cObj("geterrorpay").innerHTML = "<p style='color:red;font-size:12px;'>First check if the student admission number is valid by searching, if its found to be valid proceed and make the payment.</p>"
    }

}

cObj("bankcode").onblur = function () {
    var code = this.value;
    if (code.trim().length > 0) {
        var datapass = "?bank_codes=" + code;
        sendData2("GET", "finance/financial.php", datapass, cObj("bank_code_errs"), cObj("anonymus"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("anonymus").classList.contains("hide")) {
                    if (cObj("bank_code_errs").innerText.trim().length > 1) {
                        redBorder(this);
                    } else {
                        grayBorder(this);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    }
}

cObj("select_time_set_option1").addEventListener("click",showdatefunct);
cObj("select_time_set_option2").addEventListener("click",showdatefunct);
cObj("select_time_set_option3").addEventListener("click",showdatefunct);
function showdatefunct() {
    var this_values = this.value;
    if (this_values == "set") {
        cObj("show_date_time").classList.remove("hide");
        cObj("option2_1").selected = true;
        cObj("option2_2").selected = true;
        cObj("option2_3").selected = true;
    }else{
        cObj("show_date_time").classList.add("hide");
        cObj("option1_1").selected = true;
        cObj("option1_2").selected = true;
        cObj("option1_3").selected = true;
    }
}

cObj("generate_finance_reports").onclick = function () {
    var this_value = cObj("cash_flow_statement").value;
    if (this_value == "income_statement") {
        incomeStatement(valObj("year_of_statement"));
    }else if(this_value == "income_statement_quarterly"){
        income_statement_quarterly(valObj("year_of_statement"));
    }else if (this_value == "annual_report") {
        annual_cashflow_report(valObj("year_of_statement"));
    }else if (this_value == "quarterly_report_sep") {
        annual_cashflow_report(valObj("year_of_statement"));
    }else if (this_value == "quarterly_report_mar") {
        annual_cashflow_report(valObj("year_of_statement"));
    }else if (this_value == "quarterly_report_dec") {
        annual_cashflow_report(valObj("year_of_statement"));
    }else if (this_value == "quarterly_report_jun") {
        annual_cashflow_report(valObj("year_of_statement"));
    }
    console.log(this_value);
}

function annual_cashflow_report(year) {
    var datapass = "?cashflow_statement=true&year="+year+"&report_type="+cObj("cash_flow_statement").value;
    sendData1("GET", "finance/financial.php", datapass, cObj("finance_statements"));
}
function end_of_sep_cashflow_report(year) {
    var datapass = "?cashflow_statement=true&year="+year+"&report_type="+cObj("cash_flow_statement").value;
    sendData1("GET", "finance/financial.php", datapass, cObj("finance_statements"));
}

function income_statement_quarterly(year) {
    var datapass = "?income_statement_quarterly=true&year="+year;
    sendData1("GET", "finance/financial.php", datapass, cObj("finance_statements"));
}


cObj("confirmyes").onclick = function () {
    // add sms functionality to the payment system
    var trancode = '';
    var amount = 0;
    if (valObj("modeofpay") == 'mpesa') {
        trancode = valObj("mpesacode");
        amount = valObj("amount1")
    } else if (valObj("modeofpay") == 'cash') {
        trancode = "cash";
        amount = valObj("amount3")
    } else if (valObj("modeofpay") == 'bank') {
        trancode = valObj("bankcode");
        amount = valObj("amount2")
    }
    var send_sms = cObj("check-parents-sms").value;
    // send sms to the students parent
    if (valObj("reprint") == "false") {
        var date_of_payments_fees = valObj("date_of_payments_fees");
        var time_of_payment_fees = valObj("time_of_payment_fees");
        var fees_payment_opt_holder = valObj("fees_payment_opt_holder");
        var supporting_documents_list = valObj("supporting_documents_list");
        var datapass = "?insertpayments=true&stuadmin=" + valObj("presented") + "&transcode=" + trancode + "&amount=" + amount + "&payfor=" + valObj("payment_for_details") + "&paidby=" + valObj("useriddds") + "&modeofpay=" + valObj("modeofpay") + "&balances=" + cObj("closed_balance").innerText + "&send_sms=" + send_sms+"&date_of_payments_fees="+date_of_payments_fees;
        datapass+="&time_of_payment_fees="+time_of_payment_fees+"&fees_payment_opt_holder="+fees_payment_opt_holder+"&supporting_documents_list="+supporting_documents_list;
        sendData1("GET", "finance/financial.php", datapass, cObj("geterrorpay"));
        var purpose_p = valObj("payment_for_details");
        cObj("confirmpayments").classList.add("hide");
        setTimeout(() => {
            var ids = setInterval(() => {
                setTimeout(() => {
                    var timeout = 0;
                    var idsf = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(idsf);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            var text = cObj("geterrorpay").innerText.substr(0, 11);
                            if (text == "Transaction"){
                                cObj("payforms").reset();
                                cObj("mpesad").classList.add("hide");
                                cObj("banksd").classList.add("hide");
                                cObj("cash").classList.add("hide");
                                cObj("show_date_time").classList.add("hide");
                                cObj("makepayments").classList.add("hide");
                                cObj("studids").value = cObj("students_id_ddds").innerText;
                                cObj("searchfin1").click();
                                
                                // reset the supporting document
                                cObj("supporting_documents_list").value = "[]";
                                displaySupportingDocuments();
                                cObj("edit_supporting_documents").classList.add("hide");

                                setTimeout(() => {
                                    var inner_id = setInterval(() => {
                                        if (cObj("loadings").classList.contains("hide")) {
                                            if (amount > 0 && valObj("reprint") == "false") {
                                                if (cObj("last_receipt_id")!= null) {
                                                    var last_receipt_id = valObj("last_receipt_id");
                                                    cObj("last_receipt_id_take").value = last_receipt_id;
                                                }
                                                //set the values of the payment reciept
                                                cObj("student_adm_no").innerText = ": " + cObj("students_id_ddds").innerText;
                                                cObj("students_jina").innerText = ": " + cObj("std_names").innerText;
                                                cObj("transaction_codeds").innerText = cObj("transaction_code") != null ? cObj("transaction_code").innerText : "no-code";
                                                cObj("mode_of_payment").innerText = cObj("mode_use_pay") != null ? cObj("mode_use_pay").innerText : "no-code";
                                                cObj("cash_recieved").innerText = "Kes " + comma3((cObj("amount_recieved") != null ? cObj("amount_recieved").innerText : "0"));
                                                cObj("closing_balance").innerText = "Kes " + comma3(cObj("closed_balance").innerText);
                                                cObj("purpose_in_p").innerText = purpose_p;
                                                cObj("sch_logods").src = cObj("sch_logos").src;
                                                // values to submit for reciept printing
                                                cObj("students_names").value = cObj("std_names").innerText;
                                                cObj("student_admission_no").value = cObj("students_id_ddds").innerText;
                                                cObj("amount_paid_by_student").value = "Kes " + comma3(cObj("amount_recieved").innerText);
                                                cObj("new_student_balance").value = "Kes " + comma3(cObj("closed_balance").innerText);
                                                cObj("mode_of_payments").value = cObj("mode_use_pay") != null ? cObj("mode_use_pay").innerText : "no-code";
                                                cObj("transaction_codes").value = cObj("transaction_code") != null ? cObj("transaction_code").innerText : "no-code";
                                                cObj("payments_for").value = purpose_p;
                
                                                cObj("submit_receipt_printing").click();
                                            }
                                            stopInterval(inner_id);
                                        }
                                    }, 100);
                                }, 100);
                                setTimeout(() => {
                                    cObj("geterrorpay").innerText = "";
                                }, 1000);
                            }
                            stopInterval(idsf);
                        }
                    }, 100);
                }, 200);
                stopInterval(ids);
            }, 100);
        }, 200);
    }else{
        cObj("submit_receipt_printing").click();
        cObj("confirmno").click();
    }

}

cObj("mpesacode").onblur = function () {
    var mpesacode = this.value;
    if (mpesacode.trim().length > 0) {
        //send data to the database to check the code if its used
        var datapass = "?m_pesa_code=" + this.value.trim();
        sendData2("GET", "finance/financial.php", datapass, cObj("mpesa_code_err"), cObj("anonymus"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("anonymus").classList.contains("hide")) {
                    if (cObj("mpesa_code_err").innerText.trim().length > 1) {
                        redBorder(this);
                    } else {
                        grayBorder(this);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 100);
    } else {
        cObj("mpesa_code_err").innerHTML = "";
    }
}

cObj("confirmno").onclick = function () {
    cObj("confirmpayments").classList.add("hide");
    cObj("check-parents-sms").disabled = false;
    cObj("send_sms_dsiclaimer").innerText = "";
    cObj("switch_confirmation").innerText = "Confirm payment";
    cObj("title_confirmation").classList.remove("hide");
    // cObj("")
}

cObj("showprocess1").onclick = function () {
    cObj("procedure").classList.remove("hide");
    cObj("btnshow1").classList.add("hide");
}
cObj("hideprocess1").onclick = function () {
    cObj("procedure").classList.add("hide");
    cObj("btnshow1").classList.remove("hide");
}


function checkErrors() {
    let errors = 0;
    // errors += checkBlank("payfor");
    //check if cash, mpesa or bank is selected
    errors += checkBlank("modeofpay");
    if (checkBlank("modeofpay") == 0) {
        if (valObj("modeofpay") == 'mpesa') {
            errors += checkBlank("mpesacode");
            errors += checkBlank("amount1");
        } else if (valObj("modeofpay") == 'cash') {
            errors += checkBlank("amount3");
        } else if (valObj("modeofpay") == 'bank') {
            errors += checkBlank("bankcode");
            errors += checkBlank("amount2");
        }
    }

    return errors;
}


cObj("timeopt").onchange = function () {
    if (this.value == "btndates") {
        cObj("btndates").classList.remove("hide");
        cObj("otheropts").classList.remove("hide");
        cObj("classlists").classList.add("hide");
        cObj("trans_code").classList.add("hide");
    } else {
        if (this.value == "clased") {
            cObj("otheropts").classList.add("hide");
            cObj("btndates").classList.add("hide");
            cObj("classlists").classList.remove("hide");
            cObj("trans_code").classList.add("hide");
        } else if (this.value == "transactioncodes") {
            cObj("otheropts").classList.add("hide");
            cObj("btndates").classList.add("hide");
            cObj("classlists").classList.add("hide");
            cObj("trans_code").classList.remove("hide");
        } else {
            cObj("btndates").classList.add("hide");
            cObj("otheropts").classList.remove("hide");
            cObj("classlists").classList.add("hide");
            cObj("trans_code").classList.add("hide");
        }
    }
}

cObj("student_s").onchange = function () {
    if (this.value == "admno") {
        cObj("enteradmno").classList.remove("hide");
    } else {
        cObj("enteradmno").classList.add("hide");
    }
}

cObj("searchtransaction").onclick = function () {
    let errs = checkerrorstrans();
    if (errs == 0) {
        if (cObj("classedd") != null && cObj("classedd") != "undefined") {
            getTransactionId();
        } else {
            getClasses("manage_trans", "classedd", "");
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        getTransactionId();
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    } else {
        cObj("errhandler").innerHTML = "<p style='color:red;'>Select and fill all the options with the red border</p>";
    }
}

var mybutclicked = 0;

function getTransactionId() {
    cObj("errhandler").innerHTML = "<p style='color:red;'></p>";
    var firstselect = cObj("timeopt").value;
    var secondselect = cObj("student_s").value;
    if (cObj("classedd") != "undefined" && cObj("classedd") != null) {
        var thirdselect = cObj("classedd").value;
        var datapass = "?";
        if (firstselect != "btndates" && secondselect != "admno" && firstselect != "clased" && firstselect != "transactioncodes") {
            datapass = "?findtransactions=true&period=" + firstselect + "&studentstype=" + secondselect;
            sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
            assignEventsDone();
            displayFeesData();
        } else {
            if (firstselect == "btndates" && secondselect != "admno" && firstselect != "clased" && firstselect != "transactioncodes") {
                datapass = "?findtransbtndates=true&startfrom=" + valObj("startdate") + "&endperiod=" + valObj("enddate");
                sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
                assignEventsDone();
                displayFeesData();
            } else if (firstselect == "btndates" && secondselect == "admno" && firstselect != "clased" && firstselect != "transactioncodes") {
                datapass = "?findtransbtndatesandadmno=true&startfrom=" + valObj("startdate") + "&endperiod=" + valObj("enddate") + "&admnos=" + valObj("admnno");
                sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
                assignEventsDone();
                displayFeesData();
            } else if (firstselect != "btndates" && secondselect == "admno" && firstselect != "clased" && firstselect != "transactioncodes") {
                datapass = "?findtransbtncontsdatesandadmno=true&admnos=" + valObj("admnno") + "&period=" + firstselect;
                sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
                assignEventsDone();
            } else if (thirdselect.length > 0 && firstselect == "clased" && firstselect != "transactioncodes") {
                if (mybutclicked > 0) {
                    tinymce.triggerSave();
                    tinymce.remove();
                }
                datapass = "?findtransindates=true&class=" + thirdselect;
                sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            var obj = document.getElementsByClassName("finbtns");
                            setListener(obj);
                            if (cObj("pleasewait23") != "undefined" && cObj("pleasewait23") != null) {
                                cObj("pleasewait23").style.display = 'none';
                            }
                            //set listener for the print remiders button
                            if (cObj("print_reminders") != "undefined" && cObj("print_reminders") != null) {
                                cObj("print_reminders").addEventListener("click", printFeesReminder);
                            }
                            // set listener for the select all button
                            if (cObj("select_all_reminders") != "undefined" && cObj("select_all_reminders") != null) {
                                cObj("select_all_reminders").addEventListener("change", selectAllChange);

                                var allelements = document.getElementsByClassName("sutid");
                                for (let ind = 0; ind < allelements.length; ind++) {
                                    const element = allelements[ind];
                                    element.onchange = function () {
                                        if (element.checked == true) {
                                            var checked = 0;
                                            var unchecked = 0;
                                            for (let ind2 = 0; ind2 < allelements.length; ind2++) {
                                                const elems = allelements[ind2];
                                                if (elems.checked == true) {
                                                    checked++;
                                                } else {
                                                    unchecked++;
                                                }
                                            }

                                            if (unchecked == 0) {
                                                cObj("select_all_reminders").checked = true;
                                            } else {
                                                cObj("select_all_reminders").checked = false;
                                            }
                                        } else {
                                            var checked = 0;
                                            var unchecked = 0;
                                            for (let ind2 = 0; ind2 < allelements.length; ind2++) {
                                                const elems = allelements[ind2];
                                                if (elems.checked == true) {
                                                    checked++;
                                                } else {
                                                    unchecked++;
                                                }
                                            }

                                            if (unchecked == 0) {
                                                cObj("select_all_reminders").checked = true;
                                            } else {
                                                cObj("select_all_reminders").checked = false;
                                            }
                                        }
                                        check_selected();
                                    }
                                }
                            }

                            if (cObj("email_selections") != "undefined" && cObj("email_selections") != null) {
                                cObj("email_selections").onchange = function () {
                                    if (cObj("email_selections").value == "print_invoices") {
                                        var container_ones = document.getElementsByClassName("container_ones");
                                        for (let indx = 0; indx < container_ones.length; indx++) {
                                            const elems = container_ones[indx];
                                            elems.classList.add("hide");
                                        }
                                        cObj("print_or_send_invoice_btn").innerHTML = "<i class='fas fa-print'></i> Print";
                                        cObj("image_omens").innerText = "Print Invoices";
                                    } else if (cObj("email_selections").value == "send_email_invoices") {
                                        var container_ones = document.getElementsByClassName("container_ones");
                                        for (let indx = 0; indx < container_ones.length; indx++) {
                                            const elems = container_ones[indx];
                                            elems.classList.remove("hide");
                                        }
                                        cObj("image_omens").innerText = "Send Invoices";
                                        cObj("print_or_send_invoice_btn").innerHTML = "<i class='fas fa-paper-plane'></i> Send";
                                    }
                                }
                            }
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
                cObj("window_2").classList.add("hide");
            } else if (firstselect == "transactioncodes") {
                datapass = "?find_transaction_with_code=" + cObj("transact_code").value;
                sendData1("GET", "finance/financial.php", datapass, cObj("errhandler"));
                assignEventsDone();
                displayFeesData();
            }
        }
    }
}

function check_selected() {
    var sutid = document.getElementsByClassName("sutid");
    var student_id = "";

    for (let ind = 0; ind < sutid.length; ind++) {
        const element = sutid[ind];
        if (element.checked == true) {
            student_id += element.id.substring(5) + ",";
        }
    }
    student_id = student_id.substring(0, student_id.length - 1);
    cObj("students_ids").value = student_id;
}

function selectAllChange() {
    var selected = cObj("select_all_reminders").checked;
    if (selected == true) {
        var sutid = document.getElementsByClassName("sutid");
        for (let index = 0; index < sutid.length; index++) {
            const element = sutid[index];
            element.checked = true;
        }
    } else {
        var sutid = document.getElementsByClassName("sutid");
        for (let index = 0; index < sutid.length; index++) {
            const element = sutid[index];
            element.checked = false;
        }
    }
    check_selected();
}

function assignEventsDone() {
    if (cObj("fin_tables") != 'undefined') {
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("tabular").addEventListener("click", selectTable);
                    cObj("chartlike").addEventListener("click", selectChart);
                    cObj("hide_chart_table").addEventListener("click",hideData);
                    hideData();
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}
// display data


function displayFeesData() {
    cObj("window_2").classList.remove("hide");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var fees_data = cObj("fees_data").innerText;
                if (hasJsonStructure(fees_data)) {
                    var json_data = JSON.parse(fees_data);
                    // create the table
                    getFeesNDisplay(json_data);
                    cObj("search_option_fee").classList.remove("d-none");
                    cObj("tablefooter_fees").classList.remove("invisible");
                } else {
                    cObj("search_option_fee").classList.add("d-none");
                    cObj("transDataReciever_fees").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
                    cObj("tablefooter_fees").classList.add("invisible");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function printFeesReminder() {
    var student_to_send_reminder = document.getElementsByClassName("sutid");
    var student_id = "";
    for (let index = 0; index < student_to_send_reminder.length; index++) {
        const element = student_to_send_reminder[index];
        if (element.checked == true) {
            student_id += element.id.substr(5) + ",";
        }
    }
    student_id = student_id.substr(0, student_id.length - 1);
    var err = checkBlank("date_picker");
    //send the data to the database
    if (err == 0) {
        cObj("reminder_err").innerHTML = "";
        if (student_id.length > 1) {
            var datapass = "?get_fee_reminders=" + student_id + "&deadline=" + cObj("date_picker").value;
            sendData1("GET", "finance/financial.php", datapass, cObj("print_reminded"));
            setTimeout(() => {
                var ids = setInterval(() => {
                    if (cObj("loadings").classList.contains("hide")) {
                        hideWindow();
                        cObj("fees_reminders").classList.remove("hide");
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        } else {
            cObj("reminder_err").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Select a student to print reminder!</p>";
        }
    } else {
        cObj("reminder_err").innerHTML = "<p style='color:red;font-size:12px;font-weight:600;'>Give a date from today as the deadline for fees payment!</p>";
    }
}
function stopInterval(id) {
    clearInterval(id);
}
function setListener(obj) {
    for (let index = 0; index < obj.length; index++) {
        const element = obj[index];
        element.addEventListener('click', viewlistener);
    }
};

function viewlistener() {
    var admno = this.id;
    admno = admno.substr(6, admno.length);
    //first change period
    cObj("btnd").selected = true;
    cObj("timeopt").click();
    cObj("spcificstd").selected = true;
    cObj("student_s").click();
    cObj("admnno").value = "" + admno + "";
    //get date today and year
    var year = new Date();
    var mon = year.getMonth() + 1;
    var date = year.getDate();
    month = '' + mon;
    dates = "" + date;
    if (mon < 10) {
        month = "0" + mon;
    }
    if (date < 10) {
        dates = "0" + date;
    }
    cObj("startdate").value = year.getFullYear() + "-01-01";
    cObj("enddate").value = year.getFullYear() + "-" + month + "-" + dates;
    cObj("searchtransaction").click();
}
function checkerrorstrans() {
    let err = 0;
    err += checkBlank("timeopt");

    if (cObj("timeopt").value != "clased" && cObj("timeopt").value != "transactioncodes") {
        err += checkBlank("student_s");
    }

    if (cObj("timeopt").value == "btndates") {
        err += checkBlank("startdate");
        err += checkBlank("enddate");
    }
    if (cObj("student_s").value == "admno") {
        err += checkBlank("admnno");
    }
    if (cObj("timeopt").value == "clased") {
        if (cObj("classedd") != "undefined" && cObj("classedd") != null) {
            err += checkBlank("classedd");
        } else {
            err++;
        }
    }
    if (cObj("timeopt").value == "transactioncodes") {
        err += checkBlank("transact_code");
    }
    return err;
}
var a;
function printFeesReciept() {
    a = window.open('', '', 'height=500px, width=500px');
    a.document.write("<html><head><link rel='stylesheet' href='../../assets/CSS/homepage2.css'></head><body>");
    a.document.write(cObj("fees_reciept").innerHTML);
    a.document.write("</body></html>");
    a.document.close();
    setTimeout(() => {
        a.print();
    }, 2000);
    // cObj("fees_reciept").print();
}
function payWindowclick() {
    cObj("payfeess").click();
    closeWin();
}
function closeWin() {
    if (a != "undefined" && a != null) {
        a.close();
    }
}

var b;
function printFeesReminded() {
    a = window.open('', '', 'height=480px, width=700px');
    a.document.write("<html><head><link rel='stylesheet' href='../../assets/CSS/homepage2.css'></head><body>");
    a.document.write(cObj("print_reminded").innerHTML);
    a.document.write("</body></html>");
    a.document.close();
    setTimeout(() => {
        a.print();
    }, 2000);
}
function closeWinB() {
    if (a != "undefined" && a != null) {
        a.close();
    }
    cObj("findtrans").click();
}
//the third window
var c;
function printFeesStructure() {
    c = window.open('', '', 'height=480px, width=700px');
    c.document.write("<html><head><link rel='stylesheet' href='../../assets/CSS/homepage2.css'></head><body>");
    c.document.write(cObj("fees_struct-in").innerHTML);
    c.document.write("</body></html>");
    c.document.close();
    setTimeout(() => {
        c.print();
    }, 2000);
}
//close window

function closeWindowPay() {
    cObj("feestruct").click();
    closeWin2();
}
function closeWin2() {
    if (c != "undefined" && c != null) {
        c.close();
    }
}
function sendMessage() {
    var datapass = "?send_message=true&to=" + cObj("phone_nos").value + "&message=" + cObj("text_message").value
    sendData1("GET", "finance/financial.php", datapass, cObj("out_put"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var response = cObj("out_put").innerText
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("close_add_expense21").onclick = function () {
    cObj("add_expense_update").classList.add("hide");
}
cObj("close_add_expense1").onclick = function () {
    cObj("add_expense_update").classList.add("hide");
}
cObj("save_add_expense1").onclick = function () {
    //check for errors
    var err = checkBlank("exp_name1");
    err += checkBlank("term_one1");
    err += checkBlank("term_two1");
    err += checkBlank("term_three1");
    err += checkBlank("boarders1_regular1");
    if (err == 0) {
        cObj("err_handler_101").innerHTML = "";
        
        // check for error before proceeding
        if(cObj("fees_structure_edit_level") == undefined){
            cObj("err_handler_101").innerHTML = "<p class='red_notice'>Course levels has not been set up, try again later!</p>";
            return 0;
        }

        // fees structure
        if(cObj("course_chosen_fees_structure") == undefined){
            cObj("err_handler_101").innerHTML = "<p class='red_notice'>Courses have not been set up, try again later!</p>";
            return 0;
        }
        // fees
        if (checkBlank("course_chosen_fees_structure") == 1 || checkBlank("fees_structure_edit_level") == 1) {
            cObj("err_handler_101").innerHTML = "<p class='red_notice'>Check all fields covered with red border!</p>";
            return 0;
        }
        
        var fee_name = cObj("exp_name1").value
        var term_one1 = cObj("term_one1").value
        var term_two1 = cObj("term_two1").value
        var term_three1 = cObj("term_three1").value
        var fees_id = cObj("fee_id_s").innerText
        var roles = cObj("boarders1_regular1").value;
        var courses = valObj("course_chosen_fees_structure");
        var course_level = valObj("fees_structure_edit_level");
        var datapass = "?update_fees_information=true&fees_name=" + fee_name + "&t_one=" + term_one1 + "&t_two=" + term_two1 + "&t_three=" + term_three1 + "&fee_ids=" + fees_id + "&course=" + courses+"&course_level="+ course_level + "&old_names=" + cObj("original_exp_name").innerText + "&roles=" + roles;
        sendData1("GET", "finance/financial.php", datapass, cObj("err_handler_101"));
        setTimeout(() => {
            var ids = setInterval(() => {
                if (cObj("loadings").classList.contains("hide")) {
                    cObj("add_expense_update").classList.add("hide");
                    cObj("showfeesstructure").click();
                    cObj("err_handler_101").innerHTML = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("err_handler_101").innerHTML = "<p class='red_notice'>Fill all the fields covered with a red border!</p>";
    }
}
cObj("exp_name").onblur = function () {
    //gwt its value
    // var expense_name = this.value;
    // if (expense_name.length > 0) {
    //     //check if the name is used
    //     var datapass = "?check_expense_name=" + expense_name;
    //     sendData2("GET", "finance/financial.php", datapass, cObj("expe_err"), cObj("anonymus"));
    //     setTimeout(() => {
    //         var timeout = 0;
    //         var ids = setInterval(() => {
    //             timeout++;
    //             //after two minutes of slow connection the next process wont be executed
    //             if (timeout == 1200) {
    //                 stopInterval(ids);
    //             }
    //             if (cObj("anonymus").classList.contains("hide")) {
    //                 //get the if the expe_err has some text in it
    //                 // if (cObj("expe_err").innerText.length > 0) {
    //                 //     redBorder(this);
    //                 // } else {
    //                 //     grayBorder(this);
    //                 // }
    //                 stopInterval(ids);
    //             }
    //         }, 100);
    //     }, 200);
    // }
}
function showBalanceInput() {
    cObj("fee_balance_new").classList.remove("hide");
    cObj("fee_balance_new").classList.add("new_balances");
    cObj("read_note").classList.remove("hide");
}
function acceptBalance() {
    var err = checkBlank("new_bala_ces");
    if (err == 0) {
        var balance = valObj("new_bala_ces");
        cObj("closed_balance").innerText = balance;
        closeAcceptBalance();
        cObj("new_bala_ces").value = "";
    }
}
function closeAcceptBalance() {
    cObj("fee_balance_new").classList.add("hide");
    cObj("fee_balance_new").classList.remove("new_balances");
    cObj("new_bala_ces").value = "";
    cObj("read_note").classList.add("hide");
}

//record an expense
cObj("add_expenseed").addEventListener("click", addExpense);

function addExpense() {
    if (cObj("exp_cat")!=undefined && cObj("exp_cat") != null) {
        //check for errors
        var err = checkBlank("exp_named");
        err += checkBlank("exp_cat");
        err += checkBlank("exp_total_amt");
        err += checkBlank("expense_cash_activity");
        if (err == 0) {
            cObj("err_hndler_expenses").innerHTML = "<p class='green_notice'></p>";
            err = 0;
            if (cObj("exp_total_amt").value == "0" || cObj("exp_quant").value == "0") {
                redBorder(cObj("exp_total_amt"));
                cObj("err_hndler_expenses").innerHTML = "<p class='red_notice'>Amount can`t be zero</p>";
                err++;
            } else {
                grayBorder(cObj("exp_total_amt"));
                cObj("err_hndler_expenses").innerHTML = "<p class='green_notice'></p>";
            }
            if (err == 0) {
                var expense_sub_category = cObj("expense_sub_category") != undefined && cObj("expense_sub_category") != null ? valObj("expense_sub_category") : "";
                var datapass = "?addExpenses=true&exp_name=" + cObj("exp_named").value + "&expensecat=" + cObj("exp_cat").value + "&quantity=" + cObj("exp_quant").value + "&unitcost=" + cObj("exp_amnt").value + "&total=" + cObj("exp_total_amt").value + "&unit_name=" + cObj("unit_name").value+"&expense_cash_activity="+valObj("expense_cash_activity")+"&expense_record_date="+valObj("expense_record_date")+"&document_number="+valObj("document_number")+"&new_expense_description="+valObj("new_expense_description")+"&expense_sub_category="+expense_sub_category;
                sendData1("GET", "finance/financial.php", datapass, cObj("err_hndler_expenses"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            //get the if the expe_err has some text in it
                            if (cObj("uploaded") != null) {
                                cObj("exp_named").value = "";
                                cObj("exp_quant").value = "0";
                                cObj("exp_amnt").value = "0";
                                cObj("exp_total_amt").value = "0";
                                cObj("main_sele").selected = true;
                                cObj("unit_name").value = "";
                                cObj("new_expense_description").value = "";
                                cObj("document_number").value = "";

                                // check if the element is defines
                                if(cObj("expense_sub_category") != undefined && cObj("expense_sub_category") != null){
                                    var main_children = cObj("expense_sub_category").children;
                                    main_children[0].selected = true;
                                }
                                displayTodaysExpense();
                            }
                            setTimeout(() => {
                                cObj("err_hndler_expenses").innerHTML = "";
                            }, 3000);
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            }
        } else {
            cObj("err_hndler_expenses").innerHTML = "<p class='red_notice'>Please fill all the blank fields</p>";
        }
    }else {
        cObj("err_hndler_expenses").innerHTML = "<p class='red_notice'>Set up expense categories</p>";
    }
}

//display todays expenses
function displayTodaysExpense() {
    var datapass = "?todays_expense=true";
    sendData1("GET", "finance/financial.php", datapass, cObj("my_table"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                //create a chart and get the data - decode the JSON data
                if (cObj("table_values2") != undefined) {
                    var datavalue = cObj("table_values2").innerText;
                    var dataval2 = JSON.parse(datavalue);
                    //get the value labels
                    var arrLabels = [];
                    for (let val in dataval2) {
                        arrLabels.push(val);
                    }

                    //get the values and the color value
                    var arrData = [];
                    var arrColor = [];
                    for (let index = 0; index < arrLabels.length; index++) {
                        const element = arrLabels[index];
                        arrData.push(dataval2[element]);
                        arrColor.push(getRandomColor());
                    }

                    var title = cObj("title-charts2").innerText;
                    createChart2(cObj("expense-charted-in"), title, arrLabels, arrData, arrColor);
                }

                // get the data of the expense table
                if (cObj("expenses_data_json") != undefined) {
                    var expenses_data_json = cObj("expenses_data_json").innerText;
                    if (expenses_data_json.length > 0) {
                        if (expenses_data_json.length > 5) {
                            var expense_data = expenses_data_json.length > 0 ? JSON.parse(expenses_data_json) : [];
                            // console.log(expense_data);
                            getExpensesNDisplay(expense_data);
                            // create the table
                            cObj("search_option_expenses").classList.remove("d-none");
                            cObj("tablefooter_expenses").classList.remove("invisible");
                        } else {
                            cObj("search_option_expenses").classList.add("d-none");
                            cObj("transDataReciever_expensess").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
                            cObj("tablefooter_expenses").classList.add("invisible");
                        }
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function getExpense_Cats() {
    var datapass = "?get_expense_cats=true";
    sendData2("GET","administration/admissions.php",datapass,cObj("expense_categories_holders"),cObj("load_expense_categs"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("load_expense_categs").classList.contains("hide")) {
                cObj("exp_total_amt").addEventListener("keyup",activate_amount);
                cObj("exp_cat").addEventListener("change",change_expense_category);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function change_expense_category() {
    if (this.value.length > 0) {
        cObj("error_message_expenses").innerHTML = "";
        cObj("add_expenseed").classList.remove("hide");

        // get the subcategory
        var datapass = "?get_expense_subcategory="+this.value;
        sendData2("GET","administration/admissions.php",datapass,cObj("expense_subcategory_display"),cObj("load_expense_sub_categs"));
    }
}

cObj("done_adding_exp").onclick = function () {
    cObj("recordexp").classList.add("hide");
    cObj("exp_options").classList.remove("hide");
}
cObj("add_exp").onclick = function () {
    cObj("recordexp").classList.remove("hide");
    cObj("exp_options").classList.add("hide");
    cObj("expense_subcategory_display").innerHTML = "<p class='text-danger'>Select expense category to display expense sub-category!</p>";
    getExpense_Cats();
}
cObj("done_display_exp").onclick = function () {
    cObj("find_exp_date").classList.add("hide");
    cObj("exp_options").classList.remove("hide");
}
cObj("find_exp_da").onclick = function () {
    cObj("find_exp_date").classList.remove("hide");
    cObj("exp_options").classList.add("hide");
}
cObj("disp_btns").onclick = function () {
    var options = cObj("view-options-date").value;
    if (options == "by-date") {
        var err = checkBlank("date_for_exp");
        if (err == 0) {
            cObj("date_err").innerHTML = "<p class='green_notice'></p>";
            var datapass = "?date_display=" + cObj("date_for_exp").value;
            sendData1("GET", "finance/financial.php", datapass, cObj("my_table"));
        } else {
            cObj("date_err").innerHTML = "<p class='red_notice'>Select date!</p>";
        }
    } else if (options == "by-month") {
        var err = checkBlank("sele-years");
        err += checkBlank("month_for_exp");
        if (err == 0) {
            cObj("date_err").innerHTML = "<p class='green_notice'></p>";
            var datapass = "?get_expenses=true&years=" + cObj("sele-years").value + "&months=" + cObj("month_for_exp").value;
            sendData1("GET", "finance/financial.php", datapass, cObj("my_table"));
            //create table
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("loadings").classList.contains("hide")) {
                        //create a chart and get the data - decode the JSON data
                        if (cObj("table_values") != undefined) {
                            var datavalue = cObj("table_values").innerText;
                            var dataval2 = JSON.parse(datavalue);
                            //get the value labels
                            var arrLabels = [];
                            for (let val in dataval2) {
                                arrLabels.push(val);
                            }

                            //get the values and the color value
                            var arrData = [];
                            var arrColor = [];
                            for (let index = 0; index < arrLabels.length; index++) {
                                const element = arrLabels[index];
                                arrData.push(dataval2[element]);
                                arrColor.push(getRandomColor());
                            }
                            var title = cObj("title-charts").innerText;
                            createChart2(cObj("expense-charts-in"), title, arrLabels, arrData, arrColor);
                        }
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }
    }
}

//get the income finance statement
function incomeStatement(year = null) {
    var datapass = "?incomestatement=true&year="+year;
    sendData1("GET", "finance/financial.php", datapass, cObj("finance_statements"));
}
//create the select teachers to get the teachers in their pay roll
cObj("enroll_staff_btn").onclick = function () {
    cObj("payroll_enroll").classList.remove("hide");
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.add("hide");
    //get the staff information
    getStaff_id();
    hideAllReports();
}
function getStaff_id() {
    var datapass = "?mystaff=true";
    sendData1("GET", "finance/financial.php", datapass, cObj("staff_li"));
}
//save the staff information
cObj("enrol_staf_btn").onclick = function () {
    //check first for the staff list
    var err = 0;
    if (cObj("staff_l") != null) {
        err += checkBlank("staff_l");
        err += checkBlank("amount_to_pay");
        err += checkBlank("effect_year");
        err += checkBlank("balances");
        err += checkBlank("effect_from");
        if (err == 0) {
            var salary_breakdown = get_salary_breakdown();
            cObj("enroll_err_handler").innerHTML = "";
            var datapass = "?enroll_payroll=true&staff_id=" + cObj("staff_l").value + "&salary_amount=" + cObj("amount_to_pay").value + "&effect_year=" + cObj("effect_year").value + "&balance=" + cObj("balances").value + "&effect_month=" + cObj("effect_from").value + "&salary_breakdown=" + salary_breakdown;
            sendData1("GET", "finance/financial.php", datapass, cObj("enroll_err_handler"));
            cObj("payroll_enroll").reset();
        } else {
            cObj("enroll_err_handler").innerHTML = "<p class='red_notice'>Please fill all the fields covered with red border.</p>";
        }
    } else {
        cObj("enroll_err_handler").innerHTML = "<p class='red_notice'>No staff available for enrollment</p>";
    }
}

//display information about the system
cObj("staff_en").onclick = function () {
    cObj("head_infor").innerText = "Who to select?";
    cObj("para_infor").innerText = "Select the staff you want to enroll to the school payroll system.";
}
cObj("staff_salo").onclick = function () {
    cObj("head_infor").innerText = "What is salary amount?";
    cObj("para_infor").innerText = "This is the amount of money to pay the staff monthly.";
}
cObj("staff_currMon").onclick = function () {
    cObj("head_infor").innerText = "What is the current month?";
    cObj("para_infor").innerText = "This is the month that the staff was last paid.  Its also the month at which the salary was effective";
}
cObj("staff_currYear").onclick = function () {
    cObj("head_infor").innerText = "What is the current year?";
    cObj("para_infor").innerText = "This is the year that the staff was last paid. Its also the year at which the salary was effective";
}
cObj("staff_accruedbal").onclick = function () {
    cObj("head_infor").innerText = "Balance?";
    cObj("para_infor").innerHTML = "At this field you are expected to fill the balance of the last month the staff was paid. <br>Example <i>If the staff was last paid in june this year with a balance was 12,000 and we are at August you record the 12,000 as the balance</i><br>The system will able to know the payments made monthly and their balances.<br>Leave it at zero if there is no balance";
}

cObj("advance_pay_view").onclick = function () {
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("payroll_enroll").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.remove("hide");
    getAllAdvances();
    hideAllReports();
}

cObj("nssf_reports").onclick = function () {
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("payroll_enroll").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.add("hide");
    
    hideAllReports();
    cObj("payroll_reports_window").classList.remove("hide");
    cObj("nssf_reports_window").classList.remove("hide");
    cObj("display_nssf_reports").click();
}

cObj("nhif_reports").onclick = function () {
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("payroll_enroll").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.add("hide");
    
    hideAllReports();
    cObj("payroll_reports_window").classList.remove("hide");
    cObj("nhif_reports_window").classList.remove("hide");

    // display the nhif reports
    cObj("display_nhif_reports").click();
}


cObj("kra_reports").onclick = function () {
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("payroll_enroll").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.add("hide");
    
    hideAllReports();
    cObj("payroll_reports_window").classList.remove("hide");
    cObj("kra_reports_window").classList.remove("hide");
    cObj("display_kra_reports").click();
}

cObj("display_kra_reports").onclick = function () {
    var err = checkBlank("select_kra_months");
    if (err == 0) {
        var datapass = "?get_kra_reports=true&selected_months="+valObj("select_kra_months");
        sendData2("GET","finance/financial.php",datapass,cObj("display_kra_reports_windows"),cObj("display_kra_reports_loader"));
    }
}
cObj("display_nhif_reports").onclick = function () {
    var err = checkBlank("select_nhif_months");
    if (err == 0) {
        var datapass = "?get_nhif_reports=true&selected_months="+valObj("select_nhif_months");
        sendData2("GET","finance/financial.php",datapass,cObj("display_nhif_reports_windows"),cObj("display_nhif_reports_loader"));
    }
}

function hideAllReports() {
    cObj("payroll_reports_window").classList.add("hide");
    var my_reports = document.getElementsByClassName("my_reports");
    for (let index = 0; index < my_reports.length; index++) {
        const element = my_reports[index];
        element.classList.add("hide");
    }
}

//view those enrolled for the payroll
cObj("see_enrolled").onclick = function () {
    cObj("viewEnrolledPay").classList.remove("hide");
    cObj("payroll_enroll").classList.add("hide");
    cObj("pay_salary_staff").classList.add("hide");
    cObj("view_payment_history").classList.add("hide");
    cObj("salary_infor").classList.add("hide");
    cObj("advance_management").classList.add("hide");
    seeEnrolled();
    hideAllReports();
}
//get enrolled class
function seeEnrolled() {
    var datapass = "?getEnrolled=true";
    sendData1("GET", "finance/financial.php", datapass, cObj("my_enrolled_staff"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var edit_salary = document.getElementsByClassName("edit_salary");
                for (let index = 0; index < edit_salary.length; index++) {
                    const element = edit_salary[index];
                    element.addEventListener("click", editSalaries);
                }
                var pay_staff_salo = document.getElementsByClassName("pay_staff_salo");
                for (let index = 0; index < pay_staff_salo.length; index++) {
                    const element = pay_staff_salo[index];
                    element.addEventListener("click", showPaymentwin);
                }
                var view_salos_pay = document.getElementsByClassName("view_salos_pay");
                for (let index = 0; index < view_salos_pay.length; index++) {
                    const element = view_salos_pay[index];
                    element.addEventListener("click", viewSalaryPay);
                }
                var enroll_pays = document.getElementsByClassName("enroll_pays");
                for (let index = 0; index < enroll_pays.length; index++) {
                    const element = enroll_pays[index];
                    element.addEventListener("click", enrollPay);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
cObj("pay_mode").onchange = function () {
    //hide the bank and cash window when neccessary
    var sel_val = this.value;
    if (sel_val == "m-pesa") {
        cObj("mpesa_salary").classList.remove("hide");
        cObj("banks_sal").classList.add("hide");
    } else if (sel_val == "bank") {
        cObj("banks_sal").classList.remove("hide");
        cObj("mpesa_salary").classList.add("hide");
    } else if (sel_val == "cash") {
        cObj("mpesa_salary").classList.add("hide");
        cObj("banks_sal").classList.add("hide");
    }
    cObj("amount_sal").classList.remove("hide");
    cObj("sal_pay_btns").classList.remove("hide");
}

function editSalaries() {
    var stfid = this.id.substr(3);
    cObj("stf_id_sal").innerText = stfid;
    cObj("pay_salary_staff").classList.add("hide");
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("staff_name_ids_sal").value = cObj("namd" + stfid).innerText;
    cObj("change_salary").value = cObj("salo" + stfid).innerText;
    cObj("old_salo").innerText = cObj("salo" + stfid).innerText;
    cObj("old_salary").innerText = cObj("salo" + stfid).innerText;
    cObj("salary_infor").classList.remove("hide");
    cObj("gross_salary_edit").value = 0;
    cObj("personal_relief_accept").checked = false;
    cObj("nhif_relief_accept").checked = false;
    cObj("dedcut_nhif_edit").checked = false;
    cObj("dedcut_paye_edit").checked = false;
    cObj("allowance_holder_edit").innerText = "";
    cObj("gross_sa").innerText = 0;
    cObj("allowance_html").innerHTML = "<p class='text-success'>No allowances to display at the moment.</p>";
    // get the salary details
    var datapass = "?salary_details=" + stfid;
    sendData1("GET", "finance/financial.php", datapass, cObj("salary_infor_br"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var data = cObj("salary_infor_br").innerText;
                if (hasJsonStructure(data)) {
                    data = JSON.parse(data);
                    var date_now = data.date_today;
                    var salary_breakdown = data.salary_breakdown;
                    salary_breakdown = salary_breakdown.replace(/'/g,'"');
                    if (hasJsonStructure(salary_breakdown)) {
                        salary_breakdown = JSON.parse(salary_breakdown);

                        // if its an array pick the latest data is the one we need if not the data is the effective one
                        var obj = Array.isArray(salary_breakdown) ? salary_breakdown[salary_breakdown.length-1]:salary_breakdown;
                        // change the data to JSON format and get the salary and allowances
                        // var obj = JSON.parse(data);
                        // get the allowances
                        var allowances = JSON.stringify(obj.allowances);
                        cObj("allowance_holder_edit").innerText = (allowances == '""') ? "" : allowances;
                        // set the nssf rates
                        var nssf_id = obj.nssf_rates;
                        cObj(nssf_id).selected = true;
                        // set the reliefs
                        var nhif_relief = obj.nhif_relief;
                        if (nhif_relief == "yes") {
                            cObj("nhif_relief_accept").checked = true;
                        } else {
                            cObj("nhif_relief_accept").checked = false;
                        }
                        // personal relief
                        var personal_relief = obj.personal_relief;
                        if (personal_relief == "yes") {
                            cObj("personal_relief_accept").checked = true;
                        } else {
                            cObj("personal_relief_accept").checked = false;
                        }
    
                        // deduct NHIF
                        var deduct_NHIF = obj.deduct_nhif;
                        if (deduct_NHIF == "yes") {
                            cObj("dedcut_nhif_edit").checked = true;
                        } else {
                            cObj("dedcut_nhif_edit").checked = false;
                        }
                        // deduct PAYE
                        var dedcut_paye_edit = obj.deduct_paye;
                        if (dedcut_paye_edit == "yes") {
                            cObj("dedcut_paye_edit").checked = true;
                        } else {
                            cObj("dedcut_paye_edit").checked = false;
                        }
                        var gross_salary = obj.gross_salary;
                        cObj("gross_salary_edit").value = gross_salary;
                        cObj("gross_sa").innerText = gross_salary;
                        //year 
                        var year = "yr_" + obj.year;
                        cObj(year).selected = true;
                        if (obj.allowances.length > 0) {
                            // console.log(obj.allowances);
                            addAllowances2(obj.allowances);
                        }
    
                        // deductions 
                        if (obj.deductions != null && obj.deductions != undefined) {
                            // display the data
                            displayDeductions(obj.deductions);
                        }else{
                            cObj("deduction_windows").innerHTML = "<p class='text-success'>No deductions to display at the moment.</p>";
                        }
                        cObj("error_calaculator").innerHTML = "";
                        breakdownPayments2();
                    }
                } else {
                    cObj("error_calaculator").innerHTML = "<p class='text-success'>The staff salary tax and deductions is not calculated.You can calculate or leave as is.</p>";
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}
function showPaymentwin() {
    let id = this.id.substr(4);
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("pay_salary_staff").classList.remove("hide");
    cObj("last_paid_time").innerText = cObj("lastpay" + id).innerText;
    cObj("salary_balances").innerText = cObj("salo_balance" + id).innerText;
    cObj("stf_ids_pay").innerText = id;
    cObj("staff_name").value = cObj("namd" + id).innerText;
    cObj("monthly_salo").innerText = cObj("montly_sal" + id).innerText;
    checkBalance(id);
}
function viewSalaryPay() {
    let id = this.id.substr(3);
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("view_payment_history").classList.remove("hide");
    var date = new Date();
    cObj("userPayId").value = id;
    var datapass = "?view_salo_history=true&staff_id=" + id + "&curr_year=" + date.getFullYear();
    sendData1("GET", "finance/financial.php", datapass, cObj("getmysalohistory"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var show_salo_break_down = document.getElementsByClassName("show_salo_break_down");
                for (let index = 0; index < show_salo_break_down.length; index++) {
                    const element = show_salo_break_down[index];
                    element.addEventListener("click", showSalaryDed);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

var show_hides = 0;
function showSalaryDed() {
    if (show_hides == 0) {
        show_hides = 1;
        cObj(this.id + "_1").classList.remove("hide");
        cObj(this.id).innerHTML = "<i class='fas fa-eye-slash'></i> Hide";
    } else {
        show_hides = 0;
        cObj(this.id + "_1").classList.add("hide");
        cObj(this.id).innerHTML = "<i class='fas fa-eye'></i> See More";
    }
}

cObj("sel_yrs").onchange = function () {
    let id = cObj("userPayId").value;
    cObj("viewEnrolledPay").classList.add("hide");
    cObj("view_payment_history").classList.remove("hide");
    var date = new Date();
    var datapass = "?view_salo_history=true&staff_id=" + id + "&curr_year=" + this.value;
    sendData1("GET", "finance/financial.php", datapass, cObj("getmysalohistory"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                var show_salo_break_down = document.getElementsByClassName("show_salo_break_down");
                for (let index = 0; index < show_salo_break_down.length; index++) {
                    const element = show_salo_break_down[index];
                    element.addEventListener("click", showSalaryDed);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function enrollPay() {
    cObj("enroll_staff_btn").click();
}
function checkBalance(id) {
    var datapass = "?checkBalance=true&ids=" + id;
    sendData1("GET", "finance/financial.php", datapass, cObj("tot_bal"));
}
cObj("back_to_payroll123").onclick = function () {
    cObj("view_payment_history").classList.add("hide");
    cObj("viewEnrolledPay").classList.remove("hide");
}
cObj("back2_to_payroll123").onclick = function () {
    cObj("back_to_payroll123").click();
}
cObj("changes_salary_btn").onclick = function () {
    //save changes of the new salary
    var err = 0;
    err += checkBlank("change_salary");
    if (err == 0) {
        if (cObj("change_salary").value > 0) {
            grayBorder(cObj("change_salary"));
            cObj("err_handler_F").innerHTML = "";
            var new_salo = cObj("change_salary").value;
            var old_salo = cObj("old_salo").innerText;
            if (old_salo != new_salo) {
                var salobreakdown = get_salary_breakdown2();
                var datapass = "?change_salo=true&id=" + cObj("stf_id_sal").innerText + "&new_amnt=" + new_salo + "&salo_breakdown=" + salobreakdown;
                sendData1("GET", "finance/financial.php", datapass, cObj("err_handler_F"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            cObj("see_enrolled").click();
                            setTimeout(() => {
                                cObj("err_handler_F").innerHTML = "";
                            }, 4000);
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
            } else {
                cObj("err_handler_F").innerHTML = "<p class='green_notice'>Change the salary to a new value!</p>";
                setTimeout(() => {
                    cObj("err_handler_F").innerHTML = "";
                }, 3000);
            }
        } else {
            redBorder(cObj("change_salary"));
            cObj("err_handler_F").innerHTML = "<p class='red_notice'>Salary should be greater than zero!</p>";
        }
    } else {
        cObj("err_handler_F").innerHTML = "<p class='red_notice'>Check for errors where necessary!</p>";
    }
}
cObj("unenroll_staff_salary").onclick = function () {
    cObj("unenroll_confirm").classList.remove("hide");
    cObj("name_sake").innerText = cObj("staff_name_ids_sal").value;
}
cObj("no_unenroll").onclick = function () {
    cObj("unenroll_confirm").classList.add("hide");
}
cObj("yes_unenroll").addEventListener("click", unenrollUser);
function unenrollUser() {
    let id = cObj("stf_id_sal").innerText;
    var datapass = "?unenroll_user=true&userids=" + id;
    sendData1("GET", "finance/financial.php", datapass, cObj("err_handler_F"));
    cObj("unenroll_confirm").classList.add("hide");
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("see_enrolled").click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("salary_pays_btns").onclick = function () {
    //pay staff salary
    //FIRST CHECK FOR ERRORS
    var err = checkBlank("pay_mode");
    err += checkBlank("amount_salary");
    var selection = cObj("pay_mode").value;
    if (selection == "m-pesa") {
        err += checkBlank("mpesa_code");
    } else if (selection == "bank") {
        err += checkBlank("bank_code");
    }
    if (err == 0) {
        cObj("err_handler_in").innerHTML = "";
        cObj("name_sake_2").innerText = cObj("staff_name").value
        cObj("amount_salo").innerText = cObj("amount_salary").value
        cObj("pay_salo_winds").classList.remove("hide");
    } else {
        cObj("err_handler_in").innerHTML = "<p class='red_notice'>Check all the fields colored with a redborder!</p>";
    }
}
cObj("no_salo_pay").onclick = function () {
    cObj("pay_salo_winds").classList.add("hide");
}

let divine = 0;
cObj("yes_salo_pay").onclick = function () {
    if (divine == 0) {
        makeSaloPay();
        divine++;
        setTimeout(() => {
            divine = 0;
        }, 10000);
    }
}
function makeSaloPay() {
    var selection = cObj("pay_mode").value;
    var mode_of_pay = selection;
    var transaction_code = "cash"
    if (selection == "m-pesa") {
        transaction_code = cObj("mpesa_code").value;
    } else if (selection == "bank") {
        transaction_code = cObj("bank_code").value;
    }
    var datapass = "?pay_staff=true&staff_id=" + cObj("stf_ids_pay").innerText + "&mode_of_pay=" + mode_of_pay + "&transactioncode=" + transaction_code + "&amount=" + cObj("amount_salary").value;
    sendData1("GET", "finance/financial.php", datapass, cObj("err_handler_in"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("loadings").classList.contains("hide")) {
                seeEnrolled();
                var id = cObj("stf_ids_pay").innerText;
                cObj("pay_salo_winds").classList.add("hide");
                //delete values from the inputs
                cObj("amount_salary").value = 0;
                cObj("mpesa_code").value = 0;
                cObj("bank_code").value = 0;
                setTimeout(() => {
                    cObj("lipa" + id).click();
                }, 2000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("define_advance_pay").onclick = function () {
    var advances = document.getElementsByClassName("advances");
    for (let index = 0; index < advances.length; index++) {
        const element = advances[index];
        element.classList.add("hide");
    }
    cObj("define_advance_window").classList.remove("hide");
    var datapass = "?get_my_users=true";
    sendData2("GET", "finance/financial.php", datapass, cObj("employees_data"), cObj("employees_data_loaders"));
}

cObj("back_to_advance_list").onclick = function () {
    var advances = document.getElementsByClassName("advances");
    for (let index = 0; index < advances.length; index++) {
        const element = advances[index];
        element.classList.add("hide");
    }
    getAllAdvances();
    cObj("view_all_advances_window").classList.remove("hide");
}

cObj("define_advances").onclick = function () {
    var err = 0;
    err += checkBlank("employees_id_advances");
    err += checkBlank("advance_amount");
    err += checkBlank("month_effects");
    err += checkBlank("advance_installments");
    if (err == 0) {
        var datapass = "?define_advance=true&employees_name=" + valObj("employees_id_advances") + "&advance_amount=" + valObj("advance_amount") + "&effect_month=" + valObj("month_effects") + "&advance_installments=" + valObj("advance_installments");
        sendData2("GET", "finance/financial.php", datapass, cObj("add_leave_error_handler"), cObj("add_advance_loadings"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("add_advance_loadings").classList.contains("hide")) {
                    if (cObj("advance_payments_in") != null) {
                        cObj("advance_amount").value = "";
                        cObj("month_effects").value = "";
                        cObj("advance_installments").value = "";
                        setTimeout(() => {
                            cObj("back_to_advance_list").click();
                            cObj("add_leave_error_handler").innerHTML = "";
                        }, 2000);
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        cObj("add_leave_error_handler").innerHTML = "<p class='text-danger border border-danger my-2'>Fill all fields that are marked with red border</p>";
    }
}

cObj("advance_amount").onkeyup = function () {
    var err = checkBlank("advance_amount");
    err += checkBlank("advance_installments");
    if (err == 0) {
        var advance_amount = valObj("advance_amount");
        var advance_installments = valObj("advance_installments");
        var remainder = (advance_amount / advance_installments).toFixed(2);
        cObj("advance_installments_price").innerHTML = "<p class='text-success border border-success p-2 my-1'>Kes " + formatNum(remainder) + " per installment for " + advance_installments + " Month(s)</p>";
    } else {
        var advance_installments = valObj("advance_installments");
        cObj("advance_installments_price").innerHTML = "<p class='text-success border border-success p-2 my-1'>Kes 0 per installments for " + advance_installments + " Month(s)</p>";
    }
}
cObj("advance_installments").onkeyup = function () {
    var err = checkBlank("advance_amount");
    err += checkBlank("advance_installments");
    if (err == 0) {
        var advance_amount = valObj("advance_amount");
        var advance_installments = valObj("advance_installments");
        var remainder = (advance_amount / advance_installments).toFixed(2);
        cObj("advance_installments_price").innerHTML = "<p class='text-success border border-success p-2 my-1'>Kes " + formatNum(remainder) + " per installment for " + advance_installments + " Month(s)</p>";
    } else {
        var advance_installments = valObj("advance_installments");
        cObj("advance_installments_price").innerHTML = "<p class='text-success border border-success p-2 my-1'>Kes 0 per installments for " + advance_installments + " Month(s)</p>";
    }
}

cObj("refresh_paydets").onclick = function () {
    var id = cObj("stf_ids_pay").innerText;
    cObj("lipa" + id).click();
}
cObj("back_to_payroll").onclick = function () {
    cObj("pay_salary_staff").classList.add("hide");
    cObj("viewEnrolledPay").classList.remove("hide");
    cObj("err_handler_in").innerHTML = "";
    //reset everything
    cObj("mpesa_salary").classList.add("hide");
    cObj("banks_sal").classList.add("hide");
    cObj("amount_sal").classList.add("hide");
    cObj("sal_pay_btns").classList.add("hide");
    cObj("def_opt").selected = true;
}
cObj("back_to_payrolls12").onclick = function () {
    cObj("salary_infor").classList.add("hide");
    cObj("viewEnrolledPay").classList.remove("hide");
}

//print the fees structure
cObj("print_structure").addEventListener("click", feesStructed);
function feesStructed() {
    //get the value of the fields
    var t_ones = document.getElementsByClassName("t-one");
    var t_two = document.getElementsByClassName("t-two");
    var t_three = document.getElementsByClassName("t-three");
    var vote_head = document.getElementsByClassName("vote_heads");
    var roles = document.getElementsByClassName("roles_in");
    //alert three
    //create the table and add the data to the field
    var table_data = "<table><tr><th>Votehead</th><th>Term One</th><th>Term Two</th><th>Term Three</th><th>Roles</th><th>Total</th></tr>";
    if (t_ones.length > 0) {
        var grand_total = 0;
        var termone = 0;
        var termtwo = 0;
        var termthree = 0;
        for (let index = 0; index < t_two.length; index++) {
            var total = (t_ones[index].innerText * 1) + (t_two[index].innerText * 1) + (t_three[index].innerText * 1);
            grand_total += total;
            termone += t_ones[index].innerText * 1;
            termtwo += t_two[index].innerText * 1;
            termthree += t_three[index].innerText * 1;
            table_data += "<tr><td>" + vote_head[index].innerText + "</td><td>Kes " + t_ones[index].innerText + "</td><td>Kes " + t_two[index].innerText + "</td><td>Kes " + t_three[index].innerText + "</td><td>" + roles[index].innerText + "</td><td><b>Kes " + total + "</b></td></tr>";
        }
        table_data += "<tr><td><b>Total</b></td><td><b>Kes " + termone + "</b></td><td><b>Kes " + termtwo + "</b></td><td><b>Kes " + termthree + "</b></td></tr>";
        table_data += "<tr><td><b>Grand Total</b></td><td><b>Kes " + grand_total + "</b></td></tr>";
        //fill all the fields in that class with the data 
        var dataholder = document.getElementsByClassName("terms_fees");
        for (let index = 0; index < dataholder.length; index++) {
            const element = dataholder[index];
            element.innerHTML = table_data;
        }
        //add the class value in the fees struct
        var inside = document.getElementsByClassName("class_struct_in");
        for (let index = 0; index < inside.length; index++) {
            const element = inside[index];
            element.innerText = cObj("class_display_fees").innerText;
        }

        //show the window
        hideWindow();
        cObj("print_fees_struct").classList.remove("hide");
    }

}

cObj("view-options-date").onchange = function () {
    if (this.value == "by-date") {
        cObj("bydates_viewings").classList.remove("hide");
        cObj("by_months_viewing").classList.add("hide");
    } else if (this.value == "by-month") {
        cObj("bydates_viewings").classList.add("hide");
        cObj("by_months_viewing").classList.remove("hide");
    }
    cObj("date_err").innerText = "";
}


var rowsColStudents = [];
var pagecountTransaction = 0; //this are the number of pages for transaction
var pagecounttrans = 1; //the current page the user is
var startpage = 1; // this is where we start counting the page number



function getMpesaPayments() {
    // get the exams that are already done
    rowsColStudents = [];
    var datapass = "?mpesaTransaction=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("output"), cObj("completedTransHolder"));
    setTimeout(() => {
        var timeout = 0;
        var idms = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idms);
            }
            if (cObj("completedTransHolder").classList.contains("hide")) {
                // get the arrays
                var results = cObj("output").innerText;
                if (results != "NULL" && results.length > 0) {
                    var rows = results.split("|");
                    //create a column now
                    for (let index = 0; index < rows.length; index++) {
                        const element = rows[index];
                        var col = element.split(":");
                        rowsColStudents.push(col);
                    }

                    cObj("tot_records").innerText = rows.length;
                    //create the display table
                    //get the number of pages
                    cObj("transDataReciever").innerHTML = displayRecord(1, 10, rowsColStudents);

                    //show the number of pages for each record
                    var counted = rows.length / 10;
                    pagecountTransaction = Math.ceil(counted);

                } else {
                    cObj("transDataReciever").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No M-Pesa transactions has been captured!</p>";
                    cObj("tablefooter").classList.add("invisible");
                }

                // set the listener for the assign button
                var assign_payment = document.getElementsByClassName("assign_payment");
                for (let index = 0; index < assign_payment.length; index++) {
                    const element = assign_payment[index];
                    element.addEventListener("click", find_Payment)
                }
                stopInterval(idms);
            }
        }, 100);
    }, 100);
}
// create a function to assign payment to value
function setAssignLis() {
    // set the listener for the assign button
    var assign_payment = document.getElementsByClassName("assign_payment");
    for (let index = 0; index < assign_payment.length; index++) {
        const element = assign_payment[index];
        element.addEventListener("click", find_Payment)
    }
}

// display records

function displayRecord(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder'>#</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder'>Transaction No</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder ps-2'>Amount</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder ps-2'>Adm No</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2'>Time Of Transaction<br></th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2'>ACTION<br></th><!-- <th></th><th></th> --></tr>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            //create table of 10 elements
            //the rows now with their respective data
            //check if the user has a null payment or not
            var status = arrays[index][7];
            var action = "";
            if (status == 0) {
                status = "<span style='color:red;'>Not-Assigned</span>";
                action = "<span class='link assign_payment' id='" + arrays[index][8] + "'><i class='fas fa-eye'></i> Assign</span>";
            } else {
                status = "<span style='color:green;'>Assigned</span>";
                action = "<span>Assigned</span>";
            }
            tableData += "<tr><td>" + (index + 1) + "</td><td><div class='d-flex px-2 align-content-center'><div class='my-auto'><p class='mb-0 text-sm'><span class='text-uppercase text-secondary text-sm font-weight-bolder text-center'>" + arrays[index][0] + "</span></p></div></div></td><td><p class='text-sm font-weight-bold mb-0'>Kes " + arrays[index][1] + " </p></td><td><span class='text-xs font-weight-bold'>" + arrays[index][2] + "</span></td><td class='align-middle text-center'><p class='text-xs font-weight-bold'>" + arrays[index][3] + "</p></td><td class='align-middle'>" + action + "</td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            //create table of 10 elements
            //the rows now with their respective data
            var status = arrays[index][7];
            var action = "";
            if (status == 0) {
                status = "<span style='color:red;'>Not-Assigned</span>";
                action = "<span class='link assign_payment' id='" + arrays[index][8] + "'><i class='fas fa-eye'></i> Assign</span>";
            } else {
                status = "<span style='color:green;'>Assigned</span>";
                action = "<span>Assigned</span>";
            }
            tableData += "<tr><td>" + (index + 1) + "</td><td><div class='d-flex px-2 align-content-center'><div class='my-auto'><p class='mb-0 text-sm'><span class='text-uppercase text-secondary text-sm font-weight-bolder text-center'>" + arrays[index][0] + "</span></p></div></div></td><td><p class='text-sm font-weight-bold mb-0'>Kes " + arrays[index][1] + " </p></td><td><span class='text-xs font-weight-bold'>" + arrays[index][2] + "</span></td><td class='align-middle text-center'><p class='text-xs font-weight-bold'>" + arrays[index][3] + "</p></td><td class='align-middle'>" + action + "</td></tr>";
        }
        fins = total;
    }
    tableData += "</table>";
    //set the start and the end value
    cObj("startNo").innerText = (start + 1);
    cObj("finishNo").innerText = fins;
    //set the page number
    cObj("pagenumNav").innerText = pagecounttrans;
    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 10
cObj("tonextNav").onclick = function () {
    if (pagecounttrans < pagecountTransaction) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage += 10;
        pagecounttrans++;
        var endpage = startpage + 11;
        cObj("transDataReciever").innerHTML = displayRecord(startpage, endpage, rowsColStudents);
    } else {
        pagecounttrans = pagecountTransaction;
    }
    setAssignLis();
}
// end of next records
cObj("toprevNac").onclick = function () {
    if (pagecounttrans > 1) {
        pagecounttrans--;
        startpage -= 10;
        var endpage = (startpage + 10) - 1;
        cObj("transDataReciever").innerHTML = displayRecord(startpage, endpage, rowsColStudents);
    }
    setAssignLis();
}
cObj("tofirstNav").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = 1;
        startpage = 0;
        var endpage = startpage + 10;
        cObj("transDataReciever").innerHTML = displayRecord(startpage, endpage, rowsColStudents);
    }
    setAssignLis();
}
cObj("tolastNav").onclick = function () {
    if (pagecountTransaction > 0) {
        pagecounttrans = pagecountTransaction;
        startpage = ((pagecounttrans * 10) - 10) + 1;
        var endpage = startpage + 10;
        cObj("transDataReciever").innerHTML = displayRecord(startpage, endpage, rowsColStudents);
    }
    setAssignLis();
}

// seacrh keyword at the table
cObj("searchkey").onkeyup = function () {
    checkName_mpesas(this.value);
    // set the listener for the assign button
    var assign_payment = document.getElementsByClassName("assign_payment");
    for (let index = 0; index < assign_payment.length; index++) {
        const element = assign_payment[index];
        element.addEventListener("click", find_Payment);
    }
}
//create a function to check if the array has the keyword being searched for
function checkName_mpesas(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter").classList.add("invisible");
        // set the 
    } else {
        cObj("tablefooter").classList.remove("invisible");
    }
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents.length; index++) {
        const element = rowsColStudents[index];
        //column break
        var present = 0;
        if (element[1].includes(keylower) || element[1].includes(keyUpper)) {
            present++;
        }
        if (element[2].includes(keylower) || element[2].includes(keyUpper)) {
            present++;
        }
        if (element[0].includes(keylower) || element[0].includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        cObj("transDataReciever").innerHTML = displayRecord(1, 10, rowsNcol2);
    } else {
        cObj("transDataReciever").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        cObj("tablefooter").classList.add("invisible");
    }
}

// here we find payments 
function find_Payment() {
    // get the transaction information
    var datapass = "?mpesa_transaction_id=" + this.id;
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("output_mpesa_transactions"), cObj("loadings"));
    setTimeout(() => {
        var timeout = 0;
        var idms = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idms);
            }
            if (cObj("loadings").classList.contains("hide")) {
                // get the arrays
                var results = cObj("output_mpesa_transactions").innerText;
                var mpesa_data = results.split(":");
                cObj("mpesa_id").innerText = mpesa_data[1];
                cObj("amount_paid").innerText = mpesa_data[2];
                cObj("wrong_adm").innerText = mpesa_data[3];
                cObj("trans_time").innerText = mpesa_data[4];
                cObj("payer_name").innerText = mpesa_data[7];
                cObj("msisdn").innerText = mpesa_data[6];
                cObj("payment_id").innerText = mpesa_data[0];
                cObj("mpesa_idds").innerText = mpesa_data[1];
                cObj("amount_to_transfer").innerText = mpesa_data[2];
                stopInterval(idms);
            }
        }, 100);
    }, 100);


    // switdh through the window
    cObj("mpesa_payment_tbl").classList.add("hide");
    cObj("payment_information").classList.remove("hide");
}
cObj("goback_link").onclick = function () {
    cObj("mpesa_payment_tbl").classList.remove("hide");
    cObj("payment_information").classList.add("hide");

    // clear some areas
    cObj("result_holder").classList.remove("hide");
    cObj("student_results").innerHTML = "";
    cObj("error_handled").innerHTML = "";
    cObj("payments_options").innerHTML = "";
    cObj("mpesaTrans").click();
}

// find the students results to assign the unassigned payment to
cObj("find_student_assign").onclick = function () {
    // get the student admission number
    var datapass = "?findadmno=" + valObj("stud_admission_no");
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("student_results"), cObj("loadings"));
    setTimeout(() => {
        var timeout = 0;
        var idms = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idms);
            }
            if (cObj("loadings").classList.contains("hide")) {
                cObj("result_holder").classList.remove("hide");
                if (cObj("student_results").innerText != "Admission number entered is invalid!") {
                    // this gets the payment options for that particular student
                    var class_studs_in = document.getElementsByClassName("class_studs_in");
                    var student_names = document.getElementsByClassName("student_names");
                    var queried = document.getElementsByClassName("queried");
                    // if the length is two then its the second one if its one then its the first one
                    var cl_length = class_studs_in.length;
                    var datapass = "?payfordetails=true&class_use=";
                    if (cl_length == 2) {
                        // get the length of the class
                        datapass += class_studs_in[1].innerText;
                        cObj("stud_name").innerText = student_names[1].innerText;
                        queried[1].id = "std_closing_bal";
                    } else {
                        datapass += class_studs_in[0].innerText;
                        cObj("stud_name").innerText = student_names[0].innerText;
                        queried[0].id = "std_closing_bal";
                    }
                    datapass+="&student_admission="+valObj("stud_admission_no");
                    // send the data to get the select
                    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("payments_options"), cObj("loadings"));
                    setTimeout(() => {
                        var timeout = 0;
                        var idfs = setInterval(() => {
                            timeout++;
                            //after two minutes of slow connection the next process wont be executed
                            if (timeout == 1200) {
                                stopInterval(idfs);
                            }
                            if (cObj("loadings").classList.contains("hide")) {

                                var payments_options = document.getElementsByClassName("payments_options");
                                // if the length is two then its the second one if its one then its the first one
                                var cl_length = payments_options.length;
                                if (cl_length == 2) {
                                    payments_options[1].id = "payment_for_option";
                                } else {
                                    payments_options[0].id = "payment_for_option";
                                }
                                stopInterval(idfs);
                            }
                        }, 100);
                    }, 100);
                }
                stopInterval(idms);
            }
        }, 100);
    }, 100);
}
var click = 0;
cObj("assigne_payment_btn").onclick = function () {
    // check if the object is selected
    if (cObj("student_results").innerText != "Admission number entered is invalid!") {
        checkBlank("payment_for_option");
        var payfor = cObj("payment_for_option").value;
        if (payfor.length > 0) {
            // var queried = document.getElementsByClassName("queried");
            if (click == 0) {
                cObj("error_handled").innerHTML = "";
                var prevbal = (cObj("std_closing_bal").innerText * 1);
                var amountpaid = (cObj("amount_paid").innerText * 1);
                var balance = prevbal;
                if (cObj("last_receipt_id")!= null) {
                    var last_receipt_id = valObj("last_receipt_id");
                    cObj("last_receipt_id_take").value = last_receipt_id;
                }
                var supporting_documents_list = valObj("supporting_documents_list");
                var datapass = "?insertpayments=true&stuadmin=" + valObj("stud_admission_no") + "&transcode=" + cObj("mpesa_id").innerText + "&amount=" + amountpaid + "&payfor=" + payfor + "&paidby=mpesa&modeofpay=mpesa&balances=" + balance + "&send_sms=true&mpesa_id=" + cObj("payment_id").innerText+"&supporting_documents_list="+supporting_documents_list;
                sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("error_handled"), cObj("loadings"));
                setTimeout(() => {
                    var timeout = 0;
                    var idfs = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(idfs);
                        }
                        if (cObj("loadings").classList.contains("hide")) {
                            if (cObj("error_handled").innerText == "Transaction completed successfully!") {
                                setTimeout(() => {
                                    cObj("goback_link").click();
                                }, 2000);
                            }
                            stopInterval(idfs);
                        }
                    }, 100);
                }, 100);
                click = 1;
            }
            setTimeout(() => {
                click = 0;
            }, 2000);

        } else {
            cObj("error_handled").innerHTML = "<p class='text-danger'>Select what the fund is allocated for.</p>";
        }
    } else {
        // show error message
        cObj("error_handled").innerHTML = "<p class='text-danger'>You have not selected a student to associate the payment to.</p>";
    }
}
var stud_fname = [];
var sec_name = [];
var sur_name = [];
var stud_clases = [];
var adm_nos = [];
// get the students name, admission number and class
function getStudentNameAdmno() {
    stud_fname = [];
    sec_name = [];
    sur_name = [];
    stud_clases = [];
    adm_nos = [];
    datapass = "?getstudentdetails=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("err_handler"), cObj("loadings"));
    setTimeout(() => {
        var timeout = 0;
        var idfs = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idfs);
            }
            if (cObj("loadings").classList.contains("hide")) {
                if (cObj("err_handler").innerText.length > 0) {
                    // change the data recieved to arrays
                    var data = cObj("err_handler").innerText;
                    var student_data = data.split("|");
                    for (let index = 0; index < student_data.length; index++) {
                        var element = student_data[index];
                        var single_stud = element.split(":");
                        // add the array to the student data array
                        stud_fname.push(single_stud[0]);
                        sec_name.push(single_stud[1]);
                        sur_name.push(single_stud[2]);
                        stud_clases.push(single_stud[3]);
                        adm_nos.push(single_stud[4]);
                    }
                }
                autocomplete(document.getElementById("studids"), stud_fname, sec_name, sur_name, adm_nos, stud_clases);
                autocomplete(document.getElementById("student_admno_in"), stud_fname, sec_name, sur_name, adm_nos, stud_clases);
                autocomplete(document.getElementById("students_admnos_in"), stud_fname, sec_name, sur_name, adm_nos, stud_clases);
                autocomplete(document.getElementById("student_adm_credit_note"), stud_fname, sec_name, sur_name, adm_nos, stud_clases);
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}

function autocomplete(inp, arr, arr2, arr3, arr4, arr5) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        var counter = 0;
        for (i = 0; i < arr.length; i++) {
            if (counter > 10) {
                break;
            }
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].toUpperCase().includes(val.toUpperCase())
                || arr2[i].toUpperCase().includes(val.toUpperCase())
                || arr3[i].toUpperCase().includes(val.toUpperCase())
                || arr5[i].toUpperCase().includes(val.toUpperCase())
            ) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = /**"<strong>" +*/arr[i] + " " + arr2[i] + " " + arr3[i] + "(" + arr4[i] + ") - (" + arr5[i] + ")"/**.substr(0, val.length)*/ /**+ "</strong>"*/;
                // b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr5[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/

                    /**Click the search button when the option is selected */
                    if (inp.id == "studids") {
                        cObj("searchfin1").click();
                    }
                    closeAllLists();
                });
                a.appendChild(b);
                counter++;
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();

                /**Click the search button when the option is selected */
                if (inp.id == "studids") {
                    cObj("searchfin1").click();
                }
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

// add deductions
cObj("add_deductions_1").onclick = function () {
    cObj("deductions_window_1").classList.remove("hide");
}

// add deductions
cObj("cancel_deductions_1").onclick = function () {
    cObj("deductions_window_1").classList.add("hide");

    cObj("select_an_option_deduction_1").selected = true;
    cObj("deduction_name_1").value = "";
    cObj("deduction_amount_1").value = "";
    cObj("deduction_name_1").classList.add("hide");
}

cObj("deduction_type_1").onchange = function () {
    var this_value = this.value;
    if (this_value == "define_new_entry") {
        cObj("deduction_name_1").classList.remove("hide");
    }else{
        cObj("deduction_name_1").classList.add("hide");
    }
}

// payroll calculations corner
cObj("add_allowances_in").onclick = function () {
    // display the window that will add allowances
    cObj("allowance_window").classList.remove("hide");
}
cObj("cancel_allowances").onclick = function () {
    // hide the window that will add allowances
    cObj("allowance_window").classList.add("hide");
}
//add allowances that are to be stored in the allowances holder
cObj("add_allowances").onclick = function () {
    var err = checkBlank("allowance_name");
    err += checkBlank("allowance_amounts");
    if (err == 0) {
        // continue and make the data to JSON
        cObj("allowance_err1_handler").innerHTML = "";
        var name = valObj("allowance_name");
        var values = valObj("allowance_amounts");
        var json_data = cObj("allowance_holder").innerText;
        if (json_data.length > 0) {
            json_data = json_data.substr(0, (json_data.length - 1)) + ",{\"name\":\"" + name + "\",\"value\":\"" + values + "\"}]";
        } else {
            json_data += "[{\"name\":\"" + name + "\",\"value\":\"" + values + "\"}]";
        }
        cObj("allowance_holder").innerText = json_data;
        // enpty the fields
        cObj("allowance_err1_handler").innerHTML = "<p class='text-success'>Data added successfully!</p>";
        setTimeout(() => {
            cObj("allowance_name").value = "";
            cObj("allowance_amounts").value = "";
            cObj("allowance_err1_handler").innerHTML = "";
            cObj("allowance_window").classList.add("hide");
        }, 1000);
        // console.log(json_data+" in data");
        // take the string above and change it to json data
        const obj = JSON.parse(json_data);
        addAllowances(obj);
    } else {
        cObj("allowance_err1_handler").innerHTML = "<p class='text-danger'>Fill all fields marked with red-border to proceed!</p>";
    }
}

// function to add the allowances to be seen by the administrator
function addAllowances(data) {
    var data_to_display = "";
    var index = 1;
    data.forEach(element => {
        data_to_display += "<div class='row'><div class='col-md-6' ><label for='select_allowance" + index + "'><i>" + index + ". " + element.name + ":</i></label></div>"
        data_to_display += "<div class='col-md-3'><p>Kes " + element.value + " <span id='hold_val" + index + "' class='hide'>" + element.value + "</span></p></div><div class='col-md-3'><input type='checkbox' checked class='select_allowances' id='select_allowance" + index + "'>"
        data_to_display += " <span class='funga remove_allowance mx-1' style='font-size: 15px;cursor: pointer;' id='remove_allowance" + index + "'>&times</span></div></div>";
        index++;
    });
    cObj("allowances_and_bonuses").innerHTML = data_to_display;
    // set listeners for the allowance removers
    var remove_allowance = document.getElementsByClassName("remove_allowance");
    for (let index = 0; index < remove_allowance.length; index++) {
        const element = remove_allowance[index];
        // set listeners 
        element.addEventListener("click", deleteAllowances);
    }
    var select_allowances = document.getElementsByClassName("select_allowances");
    for (let index = 0; index < select_allowances.length; index++) {
        const element = select_allowances[index];
        element.addEventListener("change", breakdownPayments);
    }
    breakdownPayments();
}
// function to add the allowances to be seen by the administrator
function addAllowances2(data) {
    var data_to_display = "";
    var index = 1;
    data.forEach(element => {
        data_to_display += "<div class='row'><div class='col-md-6'><label for='accept_allowance" + index + "'>" + index + ". " + element.name + "</label></div><div class='col-md-3'>";
        data_to_display += "<p>Kes " + comma3(element.value) + "<span id='value_holder" + index + "' class='hide value_holder'>" + element.value + "</span></p></div><div class='col-md-3'>"
        data_to_display += "<input type='checkbox' checked class='accept_allowance' id='accept_allowance" + index + "'>";
        data_to_display += "<span class='funga removed_allowance mx-1' style='font-size: 15px;cursor: pointer;' id='removed_allowance" + index + "'>&times</span></div></div>";
        index++;
    });
    cObj("allowance_html").innerHTML = data_to_display;
    // set listeners for the allowance removers
    var removed_allowance = document.getElementsByClassName("removed_allowance");
    for (let index = 0; index < removed_allowance.length; index++) {
        const element = removed_allowance[index];
        // set listeners 
        element.addEventListener("click", deleteAllowances2);
    }
    var accept_allowance = document.getElementsByClassName("accept_allowance");
    for (let index = 0; index < accept_allowance.length; index++) {
        const element = accept_allowance[index];
        element.addEventListener("change",breakdownPayments2);
    }
    breakdownPayments2();
}

function displayDeductions_1(deductions) {
    if (Array.isArray(deductions)) {
        var data_to_display = "";
        var index = 1;
        deductions.forEach(element => {
            data_to_display += "<div class='row'><div class='col-md-6'><label for='accept_deductions_1" + index + "'>" + index + ". " + element.name + "</label></div><div class='col-md-3'>";
            data_to_display += "<p>Kes " + comma3(element.value) + "<span id='value_holder_1" + index + "' class='hide value_holder_1'>" + element.value + "</span></p></div><div class='col-md-3'>"
            data_to_display += "<input type='checkbox' checked class='accept_deductions_1' value='"+element.value+"' id='accept_deductions_1" + index + "'>";
            data_to_display += "<span class='funga remove_deductions_1 mx-1' style='font-size: 15px;cursor: pointer;' id='remove_deductions_1" + index + "'>&times</span></div></div>";
            index++;
        });
        cObj("deductions_windoww_1").innerHTML = data_to_display;
    
        // remove the allowances
        var remove_deductions_1 = document.getElementsByClassName("remove_deductions_1");
        for (let index = 0; index < remove_deductions_1.length; index++) {
            const element = remove_deductions_1[index];
            element.addEventListener("click",delete_deduction_1);
        }
    
        // check and uncheck the allowances
        var accept_deductions_1 = document.getElementsByClassName("accept_deductions_1");
        for (let index = 0; index < accept_deductions_1.length; index++) {
            const element = accept_deductions_1[index];
            element.addEventListener("change",breakdownPayments);
        }
        breakdownPayments();
    }
}

function displayDeductions(deductions) {
    if (Array.isArray(deductions)) {
        var data_to_display = "";
        var index = 1;
        deductions.forEach(element => {
            data_to_display += "<div class='row'><div class='col-md-6'><label for='accept_deductions" + index + "'>" + index + ". " + element.name + "</label></div><div class='col-md-3'>";
            data_to_display += "<p>Kes " + comma3(element.value) + "<span id='value_holder" + index + "' class='hide value_holder'>" + element.value + "</span></p></div><div class='col-md-3'>"
            data_to_display += "<input type='checkbox' checked class='accept_deductions' value='"+element.value+"' id='accept_deductions" + index + "'>";
            data_to_display += "<span class='funga remove_deductions mx-1' style='font-size: 15px;cursor: pointer;' id='remove_deductions" + index + "'>&times</span></div></div>";
            index++;
        });
        cObj("deduction_windows").innerHTML = data_to_display;
    
        // remove the allowances
        var remove_deductions = document.getElementsByClassName("remove_deductions");
        for (let index = 0; index < remove_deductions.length; index++) {
            const element = remove_deductions[index];
            element.addEventListener("click",delete_deduction);
        }
    
        // check and uncheck the allowances
        var accept_deductions = document.getElementsByClassName("accept_deductions");
        for (let index = 0; index < accept_deductions.length; index++) {
            const element = accept_deductions[index];
            element.addEventListener("change",breakdownPayments2);
        }
        breakdownPayments2();
    }
}

function delete_deduction_1() {
    var deductions = cObj("deductions_holder_1").innerText;
    
    // change them to JSON
    deductions = JSON.parse(deductions);

    // get id
    var id = this.id.substr(19)-1;

    // counter to tell how many deductions will be still left
    var counter = 0;

    // add json data
    var json_data = [];
    for (let index = 0; index < deductions.length; index++) {
        const element = deductions[index];
        if (index != id) {
            json_data.push(element);
            counter++;
        }
    }

    // if it one or more element display the data
    if (counter > 0) {
        displayDeductions_1(json_data);
        cObj("deductions_holder_1").innerText = JSON.stringify(json_data);
    }else{
        cObj("deductions_holder_1").innerText = "";
        cObj("deductions_windoww_1").innerHTML = "<p class='text-success'>No deductions to display at the moment.</p>";
    }
}

function delete_deduction() {
    var deductions = cObj("deductions_holder").innerText;
    
    // change them to JSON
    deductions = JSON.parse(deductions);

    // get id
    var id = this.id.substr(17)-1;

    // counter to tell how many deductions will be still left
    var counter = 0;

    // add json data
    var json_data = [];
    for (let index = 0; index < deductions.length; index++) {
        const element = deductions[index];
        if (index != id) {
            json_data.push(element);
            counter++;
        }
    }

    // if it one or more element display the data
    if (counter > 0) {
        displayDeductions(json_data);
        cObj("deductions_holder").innerText = JSON.stringify(json_data);
    }else{
        cObj("deductions_holder").innerText = "";
        cObj("deduction_windows").innerHTML = "<p class='text-success'>No deductions to display at the moment.</p>";
    }
}

cObj("add_deductions").onclick = function () {
    cObj("deductions_window").classList.remove("hide");
}

cObj("deduction_type").onchange = function () {
    if (valObj("deduction_type") == "define_new_entry") {
        cObj("deduction_name").classList.remove("hide");
    }else{
        cObj("deduction_name").classList.add("hide");
    }
}

cObj("add_deductions_in_1").onclick = function () {
    var err = 0;
    err+=checkBlank("deduction_type_1");
    err+=checkBlank("deduction_amount_1");
    if (valObj("deduction_type_1") == "define_new_entry") {
        err+=checkBlank("deduction_name_1");
    }

    if (err == 0) {
        cObj("deduction_error_1").innerHTML = "";
        var name = valObj("deduction_type_1") == "define_new_entry" ? valObj("deduction_name_1") : valObj("deduction_type_1");
        var existing_deductions = cObj("deductions_holder_1").innerText;

        if (hasJsonStructure(existing_deductions)) {
            // continue and add to the existing data
            var json_data = '{"name":"'+name+'","value":"'+valObj("deduction_amount_1")+'"}';
            existing_deductions = JSON.parse(existing_deductions);
            var present = 0;
            for (let index = 0; index < existing_deductions.length; index++) {
                const element = existing_deductions[index];
                if (element.name == name) {
                    present = 1;
                    break;
                }
            }
            if (present == 0) {
                json_data = JSON.parse(json_data);
                existing_deductions.push(json_data);
                cObj("cancel_deductions_1").click();
            }else{
                cObj("deduction_error_1").innerHTML = "<p class='text-danger border border-danger my-2 p-1'>"+name+" already exists.<br>Kindly use another name or modify the existing one!</p>";
            }

            existing_deductions = JSON.stringify(existing_deductions);
            cObj("deductions_holder_1").innerText = existing_deductions;
        }else{
            // create a new json data
            var json_data = '[{"name":"'+name+'","value":"'+valObj("deduction_amount_1")+'"}]';
            cObj("deductions_holder_1").innerText = json_data;
            cObj("cancel_deductions_1").click();
        }
    }else{
        cObj("deduction_error_1").innerHTML = "<p class='text-danger border border-danger p-1 my-1'>Kindly fill all field covered with red-borders</p>";
    }
    var existing_deductions = cObj("deductions_holder_1").innerText;
    if (hasJsonStructure(existing_deductions)) {
        existing_deductions = JSON.parse(existing_deductions);
        displayDeductions_1(existing_deductions);
    }
}

cObj("add_deductions_in").onclick = function () {
    var err = 0;
    err+=checkBlank("deduction_type");
    err+=checkBlank("deduction_amount");
    if (valObj("deduction_type") == "define_new_entry") {
        err+=checkBlank("deduction_name");
    }

    if (err == 0) {
        cObj("deduction_error").innerHTML = "";
        var name = valObj("deduction_type") == "define_new_entry" ? valObj("deduction_name") : valObj("deduction_type");
        var existing_deductions = cObj("deductions_holder").innerText;
        if (existing_deductions.length > 0 && hasJsonStructure(existing_deductions)){
            // continue and add to the existing data
            var json_data = '{"name":"'+name+'","value":"'+valObj("deduction_amount")+'"}';
            existing_deductions = JSON.parse(existing_deductions);
            var present = 0;
            for (let index = 0; index < existing_deductions.length; index++) {
                const element = existing_deductions[index];
                if (element.name == name) {
                    present = 1;
                    break;
                }
            }
            if (present == 0) {
                json_data = JSON.parse(json_data);
                existing_deductions.push(json_data);
                cObj("cancel_deductions").click();
            }else{
                cObj("deduction_error").innerHTML = "<p class='text-danger border border-danger my-2 p-1'>"+name+" already exists.<br>Kindly use another name or modify the existing one!</p>";
            }

            existing_deductions = JSON.stringify(existing_deductions);
            cObj("deductions_holder").innerText = existing_deductions;
        }else{
            // create a new json data
            var json_data = '[{"name":"'+name+'","value":"'+valObj("deduction_amount")+'"}]';
            cObj("deductions_holder").innerText = json_data;
            cObj("cancel_deductions").click();
        }
    }else{
        cObj("deduction_error").innerHTML = "<p class='text-danger border border-danger my-2 p-1'>Kindly fill all fields with red border!</p>";
    }
    var existing_deductions = cObj("deductions_holder").innerText;
    if (hasJsonStructure(existing_deductions)) {
        existing_deductions = JSON.parse(existing_deductions);
        displayDeductions(existing_deductions);
    }
}

cObj("cancel_deductions").onclick = function () {
    cObj("deductions_window").classList.add("hide");
    cObj("deduction_name").value = "";
    cObj("deduction_amount").value = "";
    cObj("select_an_option_deduction").selected = true;
    cObj("deduction_name").classList.add("hide");
}

function deleteAllowances() {
    // get the id
    // remove index 1
    var index2 = this.id.substr(this.id.length - 1);
    // get the data as string
    var data = cObj("allowance_holder").innerText;
    if (data.length > 0) {
        var obj = JSON.parse(data);
        var data2 = "[";
        for (let index = 0; index < obj.length; index++) {
            const element = obj[index];
            // skip the element data
            if (index + 1 == index2) {
                continue;
            }
            data2 += JSON.stringify(element) + ",";
        }
        data2 = data2.substring(0, data2.length - 1) + "]";
        cObj("allowance_holder").innerText = data2;
        if (data2.length > 1) {
            var obj = JSON.parse(data2);
            addAllowances(obj);
        } else {
            cObj("allowance_holder").innerText = "";
            cObj("allowances_and_bonuses").innerHTML = "<p class='text-success'>No allowances to display at the moment.</p>";
        }
    } else {
        cObj("allowance_holder").innerText = "";
        cObj("allowances_and_bonuses").innerHTML = "<p class='text-success'>No allowances to display at the moment.</p>";
    }
}

function deleteAllowances2() {
    // get the id
    // remove index 1
    var index2 = this.id.substr(this.id.length - 1);
    // get the data as string
    var data = cObj("allowance_holder_edit").innerText;
    if (data.length > 0) {
        var obj = JSON.parse(data);
        var data2 = "[";
        for (let index = 0; index < obj.length; index++) {
            const element = obj[index];
            // skip the element data
            if (index + 1 == index2) {
                continue;
            }
            data2 += JSON.stringify(element) + ",";
        }
        data2 = data2.substring(0, data2.length - 1) + "]";
        cObj("allowance_holder_edit").innerText = data2;
        if (data2.length > 1) {
            if (hasJsonStructure(data2)) {
                var obj = JSON.parse(data2);
                addAllowances2(obj);
            }
        } else {
            cObj("allowance_holder_edit").innerText = "";
            cObj("allowance_html").innerHTML = "<p class='text-success'>No allowances to display at the moment.</p>";
        }
    } else {
        cObj("allowance_holder_edit").innerText = "";
        cObj("allowance_html").innerHTML = "<p class='text-success'>No allowances to display at the moment.</p>";
    }
}
function breakdownPayments() {
    // get the additions
    var gross_salary = valObj("gross_salary");
    var teir = valObj("nssf_rates") ? valObj("nssf_rates") : "none";
    var nssf_contribution = getNSSFContribution(gross_salary, teir);
    var income_after_nssf = gross_salary - nssf_contribution;
    var allowances = getAllowances();
    var taxable_income = income_after_nssf + allowances;
    var year = valObj("paye_effect_year");
    var income_tax = getIncomeTax(taxable_income, year);
    var personal_relief = 0;
    var final_income_tax = income_tax;
    // console.log(cObj("personal_relief").checked );
    if (cObj("personal_relief").checked == true) {
        if (gross_salary > 24000) {
            personal_relief = 2400;
            if (income_tax > 2400) {
                final_income_tax = income_tax - personal_relief;
            } else {
                final_income_tax = 0;
            }
        }
    }
    var nhif_contribution = getNHIFContribution(gross_salary);
    var prov_relief = (nhif_contribution * 15) / 100;
    var nhif_relief = nhif_contribution > 200 ? (prov_relief > 255 ? 255 : prov_relief) : 0;
    var netSalary = (taxable_income - (final_income_tax + (nhif_contribution)));
    if (cObj("NHIF_relief").checked == true && cObj("deduct_NHIF").checked == true) {
        netSalary = (taxable_income - (final_income_tax + (nhif_contribution - nhif_relief)));
    }
    if (cObj("deduct_paye").checked == false) {
        netSalary += final_income_tax;
    }
    if (cObj("deduct_NHIF").checked == false) {
        netSalary += nhif_contribution;
    }
    cObj("gros_salo_rec").innerText = "Ksh " + comma3(gross_salary);
    cObj("nssf_contributes").innerText = "Ksh " + comma3(nssf_contribution);
    cObj("income_after_nssf_contribute").innerText = "Ksh " + comma3(income_after_nssf);
    cObj("all_allowances").innerText = "Ksh " + comma3(allowances);
    cObj("taxable_income_records").innerText = "Ksh " + comma3(taxable_income);
    cObj("incomeTaxRecord").innerText = "Ksh " + comma3(income_tax);
    cObj("personal_relief_records").innerText = "Ksh " + comma3(personal_relief);
    cObj("final_income_taxe").innerText = "Ksh " + comma3(final_income_tax);
    cObj("nhif_contributions_records").innerText = "Ksh " + comma3(nhif_contribution);
    cObj("nhif_relief_record").innerText = "Ksh " + comma3(nhif_relief);
    var deductions = getDeductions_1();
    netSalary -= deductions;
    cObj("deductions_calculate").innerText = "Ksh "+ comma3(deductions);
    cObj("net_salary_record").innerText = "Ksh " + comma3(netSalary);
    cObj("amount_to_pay").value = netSalary.toFixed(0);
}
function breakdownPayments2() {
    // get the additions
    var gross_salary = valObj("gross_salary_edit");
    var teir = valObj("nssf_rates") ? valObj("nssf_rates_edit") : "none";
    var nssf_contribution = getNSSFContribution(gross_salary, teir);
    var income_after_nssf = gross_salary - nssf_contribution;
    var allowances = getAllowances2();
    var taxable_income = income_after_nssf + allowances;
    var year = valObj("year_of_effect_paye");
    var income_tax = getIncomeTax(taxable_income, year);
    var personal_relief = 0;
    var final_income_tax = income_tax;
    // console.log(cObj("personal_relief").checked );
    if (cObj("personal_relief_accept").checked == true) {
        if (gross_salary > 24000) {
            personal_relief = 2400;
            if (income_tax > 2400) {
                final_income_tax = income_tax - personal_relief;
            } else {
                final_income_tax = 0;
            }
        }
    }
    var nhif_contribution = getNHIFContribution(gross_salary);
    var prov_relief = (nhif_contribution * 15) / 100;
    var nhif_relief = (nhif_contribution > 200 && cObj("nhif_relief_accept").checked == true) ? ((prov_relief > 255) ? 255 : prov_relief) : 0;
    var netSalary = (taxable_income - (final_income_tax + (nhif_contribution)));
    if (cObj("nhif_relief_accept").checked == true && cObj("dedcut_nhif_edit").checked == true) {
        netSalary = (taxable_income - (final_income_tax + (nhif_contribution - nhif_relief)));
    }
    if (cObj("dedcut_paye_edit").checked == false) {
        netSalary += final_income_tax;
    }
    if (cObj("dedcut_nhif_edit").checked == false) {
        netSalary += nhif_contribution;
    }
    cObj("gros_salo_rec_edit").innerText = "Ksh " + comma3(gross_salary);
    cObj("nssf_contributes_edit").innerText = "Ksh " + comma3(nssf_contribution);
    cObj("income_after_nssf_contribute_edit").innerText = "Ksh " + comma3(income_after_nssf);
    cObj("all_allowances_edit").innerText = "Ksh " + comma3(allowances);
    cObj("taxable_income_records_edit").innerText = "Ksh " + comma3(taxable_income);
    cObj("incomeTaxRecord_edit").innerText = "Ksh " + comma3(income_tax);
    cObj("personal_relief_records_edit").innerText = "Ksh " + comma3(personal_relief);
    cObj("final_income_taxe_edit").innerText = "Ksh " + comma3(final_income_tax);
    cObj("nhif_contributions_records_edit").innerText = "Ksh " + comma3(nhif_contribution);
    cObj("nhif_relief_record_edit").innerText = "Ksh " + comma3(nhif_relief);
    var deductions = getDeductions();
    netSalary -= deductions;
    cObj("all_deductions_edit").innerText = "Kes "+comma3(deductions);
    cObj("net_salary_record_edit").innerText = "Ksh " + comma3(netSalary);
    cObj("change_salary").value = netSalary.toFixed(0);
}

function getNHIFContribution(gross_salary) {
    if (gross_salary > 0 && gross_salary <= 5999) {
        return 150;
    } else if (gross_salary > 5999 && gross_salary <= 7999) {
        return 300;
    } else if (gross_salary > 7999 && gross_salary <= 11999) {
        return 400;
    } else if (gross_salary > 11999 && gross_salary <= 14999) {
        return 500;
    } else if (gross_salary > 14999 && gross_salary <= 19999) {
        return 600;
    } else if (gross_salary > 19999 && gross_salary <= 24999) {
        return 750;
    } else if (gross_salary > 24999 && gross_salary <= 29999) {
        return 850;
    } else if (gross_salary > 29999 && gross_salary <= 34999) {
        return 900;
    } else if (gross_salary > 34999 && gross_salary <= 39999) {
        return 950;
    } else if (gross_salary > 39999 && gross_salary <= 44999) {
        return 1000;
    } else if (gross_salary > 44999 && gross_salary <= 49999) {
        return 1100;
    } else if (gross_salary > 49999 && gross_salary <= 59999) {
        return 1200;
    } else if (gross_salary > 59999 && gross_salary <= 69999) {
        return 1300;
    } else if (gross_salary > 69999 && gross_salary <= 79999) {
        return 1400;
    } else if (gross_salary > 79999 && gross_salary <= 89999) {
        return 1500;
    } else if (gross_salary > 89999 && gross_salary <= 99999) {
        return 1600;
    } else if (gross_salary > 99999) {
        return 1700;
    } else {
        return 0;
    }
}

function getAllowances() {
    var select_allowances = document.getElementsByClassName("select_allowances");
    var allowance = 0;
    for (let index = 0; index < select_allowances.length; index++) {
        const element = select_allowances[index];
        // get the id of the element
        if (element.checked == true) {
            var id = "hold_val" + (element.id.substring(element.id.length - 1) * 1);
            allowance += cObj(id).innerText * 1;
        }
    }
    return allowance;
}
function getAllowances2() {
    var accept_allowance = document.getElementsByClassName("accept_allowance");
    var allowance = 0;
    for (let index = 0; index < accept_allowance.length; index++) {
        const element = accept_allowance[index];
        // get the id of the element
        if (element.checked == true) {
            var id = "value_holder" + (element.id.substring(element.id.length - 1) * 1);
            allowance += cObj(id).innerText * 1;
        }
    }
    return allowance;
}

function getDeductions_1() {
    var accept_deductions = document.getElementsByClassName("accept_deductions_1");
    
    // deductions
    var deductions_in = 0;

    // accept deductions
    for (let index = 0; index < accept_deductions.length; index++) {
        const element = accept_deductions[index];
        if (element.checked == true) {
            deductions_in += (element.value * 1);
        }
    }

    return deductions_in;
}

function getDeductions() {
    var accept_deductions = document.getElementsByClassName("accept_deductions");
    
    // deductions
    var deductions_in = 0;

    // accept deductions
    for (let index = 0; index < accept_deductions.length; index++) {
        const element = accept_deductions[index];
        if (element.checked == true) {
            deductions_in += (element.value * 1);
        }
    }

    return deductions_in;
}

function getIncomeTax(taxable_income, year) {
    // console.log(taxable_income);
    if (year == "2022" || year == "2023") {
        if (taxable_income > 24000) {
            var tax = 0;
            // calculate the income tax
            if (taxable_income >= 12298) {
                var first_ten = 12298 * 0.1; //10%
                tax += first_ten;
                if (taxable_income >= 23885) {
                    var second = (23885 - 12298) * 0.15//15%
                    tax += second;
                    if (taxable_income >= 35472) {
                        var third = (35472 - 23885) * 0.2//20%
                        tax += third;
                        if (taxable_income >= 47059) {
                            var fourth = (47059 - 35472) * 0.25;//25%
                            tax += fourth;
                            if (taxable_income > 47059) {
                                var fifth = (taxable_income - 47059) * 0.3
                                tax += fifth;
                            }
                        } else {
                            var fourth = (taxable_income - 35472) * 0.20//20%
                            tax += fourth;
                        }
                    } else {
                        var third = (taxable_income - 23885) * 0.20//20%
                        tax += third;
                    }
                } else {
                    var second = (taxable_income - 12299) * 0.15//15%
                    tax += second;
                }
            } else {
                tax += taxable_income * 0.1;
            }
            return tax;
        } else { return 0; }
    } else if (year == "2021") {
        var tax = 0;
        if (taxable_income >= 24000) {
            tax += (24000 * 0.1);
            if (taxable_income >= 32333) {
                tax += (8333 * 0.25);
                if (taxable_income > 32333) {
                    tax += (taxable_income - 32333) * 0.3;
                }
            } else {
                tax += (taxable_income - 24000) * 0.25;
            }
        }
        return tax;
    }
}

function getNSSFContribution(gross_salary, teir) {
    var teir1 = 0;
    if (teir == "teir_1") {
        if (gross_salary >= 6000) {
            teir1 = 360;
        } else {
            teir1 = 0.06 * gross_salary;
        }
        return teir1;
    } else if (teir == "teir_1_2") {
        var teir1n2 = 0;
        if (gross_salary >= 6000) {
            // get the teir 1
            teir1n2 += 360;
            if (gross_salary >= 18000) {
                teir1n2 += 720;
            } else {
                teir1n2 += (0.06 * (gross_salary - 6000));
            }
        } else {
            teir1n2 = 0.06 * gross_salary;
        }
        return teir1n2;
    } else if (teir == "teir_old") {
        if (gross_salary >= 200) {
            return 200;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

cObj("gross_salary").onkeyup = function () {
    breakdownPayments();
}
cObj("personal_relief").onchange = function () {
    breakdownPayments();
}
cObj("NHIF_relief").onchange = function () {
    breakdownPayments();
}
cObj("nssf_rates").onchange = function () {
    breakdownPayments();
}
cObj("deduct_paye").onchange = function () {
    breakdownPayments();
}
cObj("deduct_NHIF").onchange = function () {
    breakdownPayments();
}
cObj("paye_effect_year").onchange = function () {
    breakdownPayments();
}
function get_salary_breakdown() {
    var salary_breakdown = "[{\"gross_salary\":\"" + valObj("gross_salary") + "\",";
    var personal_relief = cObj("personal_relief").checked ? "yes" : "no";
    var nhif_relief = cObj("NHIF_relief").checked ? "yes" : "no";
    var deduct_paye = cObj("deduct_paye").checked ? "yes" : "no";
    var deduct_nhif = cObj("deduct_NHIF").checked ? "yes" : "no";
    var nssf_rates = valObj("nssf_rates");
    salary_breakdown += "\"personal_relief\":\"" + personal_relief + "\",\"nhif_relief\":\"" + nhif_relief + "\"";
    salary_breakdown += ",\"deduct_paye\":\"" + deduct_paye + "\",\"deduct_nhif\":\"" + deduct_nhif + "\",\"nssf_rates\":\"" + nssf_rates + "\""
    var allowances = cObj("allowance_holder").innerText.length > 0 ? cObj("allowance_holder").innerText : "\"\"";
    salary_breakdown += ",\"allowances\":" + allowances + "";
    var deductions = cObj("deductions_holder_1").innerText.length > 0 ? cObj("deductions_holder_1").innerText : "\"\"";
    salary_breakdown += ",\"deductions\":"+deductions+""
    salary_breakdown += ",\"year\":\"" + valObj("paye_effect_year") + "\"}]";
    return salary_breakdown;
}
function get_salary_breakdown2() {
    var salary_breakdown = "{\"gross_salary\":\"" + valObj("gross_salary_edit") + "\",";
    var personal_relief = cObj("personal_relief_accept").checked ? "yes" : "no";
    var nhif_relief = cObj("nhif_relief_accept").checked ? "yes" : "no";
    var deduct_paye = cObj("dedcut_paye_edit").checked ? "yes" : "no";
    var deduct_nhif = cObj("dedcut_nhif_edit").checked ? "yes" : "no";
    var nssf_rates = valObj("nssf_rates_edit");
    salary_breakdown += "\"personal_relief\":\"" + personal_relief + "\",\"nhif_relief\":\"" + nhif_relief + "\"";
    salary_breakdown += ",\"deduct_paye\":\"" + deduct_paye + "\",\"deduct_nhif\":\"" + deduct_nhif + "\",\"nssf_rates\":\"" + nssf_rates + "\""
    var allowances = cObj("allowance_holder_edit").innerText.length > 0 ? cObj("allowance_holder_edit").innerText : "\"\"";
    salary_breakdown += ",\"allowances\":" + allowances + "";
    var deductions = cObj("deductions_holder").innerText.length > 0 ? cObj("deductions_holder").innerText : "\"\"";
    salary_breakdown += ",\"deductions\":"+deductions+""
    salary_breakdown += ",\"year\":\"" + valObj("year_of_effect_paye") + "\"}";
    return salary_breakdown;
}

cObj("edit_allowances").onclick = function () {
    cObj("allowance_window2").classList.remove("hide");
}
cObj("cancel_allowances2").onclick = function () {
    cObj("allowance_window2").classList.add("hide");
}

//add allowances that are to be stored in the allowances holder
cObj("add_allowances2").onclick = function () {
    var err = checkBlank("allowance_name2");
    err += checkBlank("allowance_amounts2");
    if (err < 1) {
        // continue and make the data to JSON
        cObj("allowance_err2_handler").innerHTML = "";
        var name = valObj("allowance_name2");
        var values = valObj("allowance_amounts2");
        var json_data = cObj("allowance_holder_edit").innerText;
        if (json_data.trim().length > 0) {
            console.log(json_data.length);
            json_data = json_data.substr(0, (json_data.length - 1)) + ",{\"name\":\"" + name + "\",\"value\":\"" + values + "\"}]";
        } else {
            // console.log(json_data);
            json_data += "[{\"name\":\"" + name + "\",\"value\":\"" + values + "\"}]";
        }
        cObj("allowance_holder_edit").innerText = json_data;
        // enpty the fields
        cObj("allowance_err2_handler").innerHTML = "<p class='text-success'>Data added successfully!</p>";
        setTimeout(() => {
            cObj("allowance_name2").value = "";
            cObj("allowance_amounts2").value = "";
            cObj("allowance_err2_handler").innerHTML = "";
            cObj("allowance_window2").classList.add("hide");
        }, 1000);
        // take the string above and change it to json data
        if (hasJsonStructure(json_data)) {
            const obj = JSON.parse(json_data);
            addAllowances2(obj);
        }
    } else {
        cObj("allowance_err2_handler").innerHTML = "<p class='text-danger'>Fill all fields marked with red-border to proceed!</p>";
    }
}
cObj("gross_salary_edit").onkeyup = function () {
    breakdownPayments2();
}
cObj("personal_relief_accept").onchange = function () {
    breakdownPayments2();
}
cObj("nhif_relief_accept").onchange = function () {
    breakdownPayments2();
}
cObj("nssf_rates_edit").onchange = function () {
    breakdownPayments2();
}
cObj("dedcut_paye_edit").onchange = function () {
    breakdownPayments2();
}
cObj("dedcut_nhif_edit").onchange = function () {
    breakdownPayments2();
}
cObj("year_of_effect_paye").onchange = function () {
    breakdownPayments2();
}

// var advance_data_2_display = [];
// var leave_data_display_2 = [];
// var pagecount_advances = 0; //this are the number of pages for transaction
// var pagecount_advanced = 1; //the current page the user is
// var startpage_advances = 1; // this is where we start counting the page number

// display data in tables
var advance_data_2_display = [];
var advance_data_2_display_2 = [];
var pagecount_advances = 0; //this are the number of pages for transaction
var pagecount_advanced = 1; //the current page the user is
var startpage_advances = 1; // this is where we start counting the page number
function getAllAdvances() {
    var datapass = "?get_advances=true";
    sendData2("GET", "finance/financial.php", datapass, cObj("data_advances_holder"), cObj("advance_registers_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("advance_registers_loaders").classList.contains("hide")) {
                // add the array to the data holder
                var advance_data = cObj("data_advances_holder").innerText;

                if (hasJsonStructure(advance_data)) {
                    advance_data = JSON.parse(advance_data);
                    advance_data_2_display = [];
                    advance_data_2_display_2 = [];
                    for (let index = 0; index < advance_data.length; index++) {
                        const element = advance_data[index];
                        var row_holder = [];
                        row_holder.push(element.month_effect);
                        row_holder.push(element.amount);
                        row_holder.push(element.installments);
                        row_holder.push(element.date_taken);
                        row_holder.push(element.employees_id);
                        row_holder.push(element.balance_left);
                        row_holder.push(element.payment_breakdown);
                        row_holder.push(element.advance_id);
                        var statuses = element.balance_left == 0 ? "<span class='text-success'>Complete</span>" : "<span class='text-danger'>In-complete</span>";
                        row_holder.push(statuses);
                        advance_data_2_display.push(row_holder);
                    }
                    cObj("tot_records_advances").innerText = advance_data_2_display.length;
                    advance_data_2_display_2 = advance_data_2_display;

                    var counted = advance_data_2_display.length / 20;
                    pagecount_advances = Math.ceil(counted);

                    cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(1, 20, advance_data_2_display);
                    setAdvanceListeners();
                } else {
                    cObj("transDataReciever_advances").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! An error has occured while displaying table.<br> Try reloading your page!</p>";
                    cObj("tablefooter_leave_apply").classList.add("invisible");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

// display record function
function displayRecord_advance_application(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><tr><th class='text-uppercase text-secondary text-xxs font-weight-bolder'>#</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder'>Employees Name</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder ps-2'>Amount</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder ps-2'>Date Issued</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder ps-2'>Installments</th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2'>Balances<br></th><th class='text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2'>Action<br></th></tr>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            //create table of 10 elements
            var balances = arrays[index][5] > 0 ? "Kes "+formatNum(arrays[index][5]) : "<p class='text-success'>Cleared</p>";
            tableData += "<tr><td>" + ((index + 1) * 1) + "</td><td><div class='d-flex px-2 align-content-center'><div class='my-auto'><span class='mb-0'> <strong class='text-center'>" + arrays[index][4] + "</strong></span></div></div></td><td><p> Kes " + formatNum(arrays[index][1]) + "</p></td><td><span>" + arrays[index][3] + "</span></td><td><span>" + arrays[index][2] + " installment(s)</span></td><td><span>" + balances + "</span></td><td><span class='link view_advance_datas' id='view_advance" + arrays[index][7] + "'><i class='fas fa-eye'></i> View</span></td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            //create table of 10 elements
            var balances = arrays[index][5] > 0 ? "Kes "+formatNum(arrays[index][5]) : "<p class='text-success'>Cleared</p>";
            tableData += "<tr><td>" + ((index + 1) * 1) + "</td><td><div class='d-flex px-2 align-content-center'><div class='my-auto'><span class='mb-0'> <strong class='text-center'>" + arrays[index][4] + "</strong></span></div></div></td><td><p> Kes " + formatNum(arrays[index][1]) + "</p></td><td><span>" + arrays[index][3] + "</span></td><td><span>" + arrays[index][2] + " installment(s)</span></td><td><span>" + balances + "</span></td><td><span class='link view_advance_datas' id='view_advance" + arrays[index][7] + "'><i class='fas fa-eye'></i> View</span></td></tr>";
        }
        fins = total;
    }
    tableData += "</table>";
    //set the start and the end value
    cObj("startNo_advances").innerText = (start + 1);
    cObj("finishNo_advances").innerText = fins;
    //set the page number
    cObj("pagenumNav_advances").innerText = pagecount_advanced;
    return tableData;
}


cObj("tonextNav_advances").onclick = function () {
    // console.log(pagecount_advanced+" advances "+pagecount_advances);
    if (pagecount_advanced < pagecount_advances) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage_advances += 20
        pagecount_advanced++;
        var endpage = startpage_advances + 11;
        cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(startpage_advances, endpage, advance_data_2_display);
        setAdvanceListeners();
    } else {
        pagecount_advanced = pagecount_advances;
    }
}
// end of next records
cObj("toprevNac_advances").onclick = function () {
    if (pagecount_advanced > 1) {
        pagecount_advanced--;
        startpage_advances -= 20
        var endpage = (startpage_advances + 20) - 1;
        cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(startpage_advances, endpage, advance_data_2_display);
        setAdvanceListeners();
    }
}

cObj("tofirstNav_advances").onclick = function () {
    if (pagecount_advances > 0) {
        pagecount_advanced = 1;
        startpage_advances = 0;
        var endpage = startpage_advances + 20
        cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(startpage_advances, endpage, advance_data_2_display);
        setAdvanceListeners();
    }
    setAssignLis();
}

cObj("tolastNav_advances").onclick = function () {
    if (pagecount_advances > 0) {
        pagecount_advanced = pagecount_advances;
        startpage_advances = ((pagecount_advanced * 20) - 20) + 1;
        var endpage = startpage_advances + 20
        cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(startpage_advances, endpage, advance_data_2_display);
        setAdvanceListeners();
    }
    setAssignLis();
}

// seacrh keyword at the table
cObj("search_advances").onkeyup = function () {
    searchAdvances(this.value);
}

//create a function to check if the array has the keyword being searched for
function searchAdvances(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter_advances").classList.add("invisible");
        // set the 
    } else {
        cObj("tablefooter_advances").classList.remove("invisible");
    }
    var rowsNcol2 = [];
    var keylower = keyword.toString().toLowerCase();
    var keyUpper = keyword.toString().toUpperCase();
    //row break
    for (let index = 0; index < advance_data_2_display.length; index++) {
        const element = advance_data_2_display[index];
        //column break
        var present = 0;
        if (element[1].toString().toLowerCase().includes(keylower) || element[1].toString().toUpperCase().includes(keyUpper)) {
            present++;
            // console.log(element[1].toString().toLowerCase());
        }
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[0].toString().toLowerCase().includes(keylower) || element[0].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3].toString().toLowerCase().includes(keylower) || element[3].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[4].toString().toLowerCase().includes(keylower) || element[4].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[5].toString().toLowerCase().includes(keylower) || element[5].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[7].toString().toLowerCase().includes(keylower) || element[7].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        cObj("transDataReciever_advances").innerHTML = displayRecord_advance_application(1, 20, rowsNcol2);
        setAdvanceListeners();
    } else {
        cObj("transDataReciever_advances").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        cObj("tablefooter_advances").classList.add("invisible");
    }
}

// advances listsneres
function setAdvanceListeners() {
    var view_advance_datas = document.getElementsByClassName("view_advance_datas");
    for (let index = 0; index < view_advance_datas.length; index++) {
        const element = view_advance_datas[index];
        element.addEventListener("click", showAdvanceDets);
    }
}

function showAdvanceDets() {
    // loop through the array and get the data selected
    var ids = this.id.substr(12);
    // console.log(ids);
    // loop
    for (let index = 0; index < advance_data_2_display.length; index++) {
        const element = advance_data_2_display[index];
        if (element[7] == ids) {
            // display the data in the view leave history
            displayAdvanceData(element);
        }
    }
}

function displayAdvanceData(data) {
    var advances = document.getElementsByClassName("advances");
    for (let index = 0; index < advances.length; index++) {
        const element = advances[index];
        element.classList.add("hide");
    }
    cObj("view_advance_window").classList.remove("hide");
    // assigne the data to the data holders
    cObj("employees_name_view").value = data[4];
    cObj("advance_amount_view").value = "Kes "+formatNum(data[1]);
    cObj("month_effects_view").value = data[0];
    cObj("advance_date_taken").value = data[3];
    cObj("advance_balance").value = "Kes "+formatNum(data[5]);
    cObj("advance_installments_view").value = data[2]+" installment(s)";

    cObj("payment_installments_advanced").innerText = data[6];
    if (hasJsonStructure(data[6])) {
        var json_data = JSON.parse(data[6]);
        var data_to_display = "<table class='table'><tr><th>#</th><th>Pay Amount</th><th>Pay Date (dd-mm-yy | HH:mm)</th><th>Effect Month</th></tr>";
        for (let index = 0; index < json_data.length; index++) {
            const element = json_data[index];
            var year = element['paydate'].substr(0,4);
            var month = element['paydate'].substr(4,2);
            var day = element['paydate'].substr(6,2);
            var hour = element['paydate'].substr(8,2);
            var minute = element['paydate'].substr(10,2);
            var second = element['paydate'].substr(12,2);

            data_to_display+="<tr><td>"+(index+1)+"</td><td>Kes "+formatNum(element['amount_paid'])+"</td><td>"+day+"-"+month+"-"+year+" | "+hour+":"+minute+"</td><td>"+element['payment_for']+"</td></tr>";
        }
        data_to_display+="</table>";
        if (json_data.length > 0) {
            cObj("payment_installments_advanced").innerHTML = data_to_display;
        }else{
            cObj("payment_installments_advanced").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! NO payments have been made for the advances!</p>";
        }
    }else{
        cObj("payment_installments_advanced").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! NO payments have been made for the advances!</p>";
    }
}

cObj("back_to_view_advance_list").onclick = function () {
    var advances = document.getElementsByClassName("advances");
    for (let index = 0; index < advances.length; index++) {
        const element = advances[index];
        element.classList.add("hide");
    }
    cObj("view_all_advances_window").classList.remove("hide");
}

cObj("display_nssf_reports").onclick = function () {
    var err = 0;
    err+=checkBlank("select_nssf_months");
    if (err == 0) {
        var datapass = "?get_nssf_reports=true&selected_month="+valObj("select_nssf_months");
        sendData1("GET","finance/financial.php",datapass,cObj("display_nssf_reports_windows"),cObj("display_nssf_reports_loader"));
    }
}

cObj("close_edit_expense_window").onclick = function () {
    cObj("edit_expense_windows").classList.add("hide");
}

cObj("edit_expense_windows_2").onclick = function () {
    cObj("edit_expense_windows").classList.add("hide");
}


cObj("save_expense_details").onclick = function () {
    var err = checkBlank("edit_expense_name");
    if (cObj("edit_expense_category") != undefined && cObj("edit_expense_category") != null) {
        err+=checkBlank("edit_expense_category");
        // err+=checkBlank("edit_document_number");
        err+=checkBlank("total_unit_cost");
        err+=checkBlank("edit_expense_cash_activity");
        err+=checkBlank("edit_expense_record_date");
        // err+=checkBlank("edit_expense_description");
        
        if (err == 0) {
            cObj("error_handlers_expenses").innerHTML = "";
            var edit_expense_name = valObj("edit_expense_name");
            var edit_expense_category = valObj("edit_expense_category");
            var edit_document_number = valObj("edit_document_number");
            var edit_expense_description = valObj("edit_expense_description");
            var total_unit_cost = valObj("total_unit_cost");
            var expense_ids_in = valObj("expense_ids_in");
            var edit_expense_cash_activity = valObj("edit_expense_cash_activity");
            var edit_expense_record_date = valObj("edit_expense_record_date");
            var expense_sub_cate = cObj("expense_sub_category") != undefined ? cObj("expense_sub_category").value : "";
    
    
            var datapass = "update_expense=true&expense_name="+edit_expense_name+"&expense_category="+edit_expense_category+"&document_number="+edit_document_number+"&expense_description="+edit_expense_description+"&total_unit_cost="+total_unit_cost+"&expense_ids_in="+expense_ids_in+"&expense_cash_activity="+edit_expense_cash_activity+"&edit_expense_record_date="+edit_expense_record_date+"&expense_sub_category="+expense_sub_cate;
            sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("error_handlers_expenses"),cObj("expense_editor_loader"));
            setTimeout(() => {
                var timeout = 0;
                var ids = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(ids);
                    }
                    if (cObj("expense_editor_loader").classList.contains("hide")) {
                        setTimeout(() => {
                            cObj("close_edit_expense_window").click();
                            cObj("error_handlers_expenses").innerHTML = "";
                            displayTodaysExpense();
                        }, 500);
                        stopInterval(ids);
                    }
                }, 100);
            }, 200);
        }else{
            cObj("error_handlers_expenses").innerHTML = "<p class='text-danger border border-danger rounded p-2'>Check all fields with red border</p>";
        }
    }else{
        cObj("error_handlers_expenses").innerHTML = "<p class='text-danger border border-danger rounded p-2'>Set your expense categories before proceeding!</p>";
    }
}

cObj("delete_promt_expenses").onclick = function () {
    cObj("delete_exp_window").classList.toggle("hide");
}
cObj("delete_expense_entry").onclick = function () {
    var expense_ids_in = valObj("expense_ids_in");
    var datapass = "delete_expense=true&exp_ids="+expense_ids_in;
    sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("error_handlers_expenses"),cObj("expense_editor_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("expense_editor_loader").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("delete_exp_window").classList.add("hide");
                    cObj("close_edit_expense_window").click();
                    cObj("error_handlers_expenses").innerHTML = "";
                    displayTodaysExpense();
                }, 500);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("upload_supporting_documents").onclick = function () {
    var err = checkBlank("supporting_documents");
    err+=checkBlank("studids");
    if (err == 0) {
        document.getElementById("upload-progress").classList.remove("hide");
        var fileInput = document.getElementById("supporting_documents");
        var file = fileInput.files[0];
      
        var customFileName = document.getElementById("studids").value;
        var file_names = valObj("file_names");
        
        var formData = new FormData();
        formData.append("file", file);
        formData.append("file_name",file_names);
        formData.append("student_admission", customFileName);
      
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/college_sims/ajax/finance/financial.php", true);
        
        xhr.upload.onprogress = function (e) {
          if (e.lengthComputable) {
            var progress = (e.loaded / e.total) * 100;
            document.getElementById("upload-progress").value = progress;
          }
        };
      
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            if (hasJsonStructure(xhr.responseText)) {
                // hide progress bar
                document.getElementById("upload-progress").classList.add("hide");

                // set the values
                cObj("supporting_documents").value = "";
                cObj("file_names").value = "";

                // get the response
                var response = JSON.parse(xhr.responseText);
                // get where this data is saved
                var supporting_documents_list = valObj("supporting_documents_list");
                if (hasJsonStructure(supporting_documents_list)) {
                    supporting_documents_list = JSON.parse(supporting_documents_list);

                    // get the record id
                    var id = 0;
                    for (let index = 0; index < supporting_documents_list.length; index++) {
                        const element = supporting_documents_list[index];
                        if (element.id >= id) {
                            id = element.id;
                        }
                    }

                    id+=1;
                    response.id = id;

                    // add the reponse to the string
                    supporting_documents_list.push(response);

                    // set the string
                    cObj("supporting_documents_list").value = JSON.stringify(supporting_documents_list);
                }else{
                    cObj("supporting_documents_list").value = "["+JSON.stringify(response)+"]";
                }

                displaySupportingDocuments();
            }
            // document.getElementById("upload-status").innerHTML = "File uploaded successfully.<br>File name: " + response.fileName + "<br>Location: " + response.fileLocation;
          }
        };
        
        xhr.send(formData);
    }else{
        cObj("supporting_document_err").innerHTML = "<p class='text-danger'>Populate the student data first before proceeding!</p>";
        setTimeout(() => {
            cObj("supporting_document_err").innerHTML = "";
        }, 5000);
    }
}

function displaySupportingDocuments() {
    var supporting_documents_list = valObj("supporting_documents_list");

    var data_to_display = "<p class='text-secondary'>No Supporting Documents Added</p>";
    if (hasJsonStructure(supporting_documents_list)){
        supporting_documents_list = JSON.parse(supporting_documents_list);
        if (supporting_documents_list.length > 0) {
            data_to_display = "<li class='list-group'>";
            for (let index = 0; index < supporting_documents_list.length; index++) {
                const element = supporting_documents_list[index];
                data_to_display+="<ul class='list-group-item'><a href='"+element.fileLocation+"' class='link' target='_blank'>"+(index+1)+". "+element.fileName+" </a> <span style='cursor:pointer;' class='text-danger delete_trash' id='delete_trash_"+element.id+"'><i class='fas fa-trash'></i> Del</span></ul>";
            }
            data_to_display+="</li>";
        }
    }
    
    cObj("list_supporting_documents").innerHTML = data_to_display;

    // get the file locale
    var delete_trash = document.getElementsByClassName("delete_trash");
    for (let index = 0; index < delete_trash.length; index++) {
        const element = delete_trash[index];
        element.addEventListener("click",deleteTrash);
    }
}
function displaySupportingDocs(data) {
    var supporting_documents_list = data;

    var data_to_display = "<p class='text-secondary'>No Supporting Documents Added</p>";
    if (hasJsonStructure(supporting_documents_list)){
        supporting_documents_list = JSON.parse(supporting_documents_list);
        if (supporting_documents_list.length > 0) {
            data_to_display = "<li class='list-group'>";
            for (let index = 0; index < supporting_documents_list.length; index++) {
                const element = supporting_documents_list[index];
                // data_to_display+="<iframe class='iframe_thumbnail' src='"+element.fileLocation+"' frameborder=''></iframe>";
                data_to_display+="<ul class='list-group-item'><a href='"+element.fileLocation+"' class='link' target='_blank'>"+(index+1)+". "+element.fileName+" <i class='fas fa-external-link-alt'></i></a> <br><iframe class='iframe_thumbnail' src='"+element.fileLocation+"' frameborder=''></iframe></ul>";
            }
            data_to_display+="</li>";
        }
    }
    
    cObj("supporting_documents_list_holder").innerHTML = data_to_display;

    // get the file locale
    // var delete_trash = document.getElementsByClassName("delete_trash");
    // for (let index = 0; index < delete_trash.length; index++) {
    //     const element = delete_trash[index];
    //     element.addEventListener("click",deleteTrash);
    // }
}

// delete trash
function deleteTrash() {
    // get the file location
    var this_id  = this.id.substr(13);

    // get the data
    var supporting_documents_list = valObj("supporting_documents_list");
    if (hasJsonStructure(supporting_documents_list)) {
        supporting_documents_list = JSON.parse(supporting_documents_list);

        // file data 
        for (let index = 0; index < supporting_documents_list.length; index++) {
            const element = supporting_documents_list[index];
            if (element.id == this_id) {
                // get the data and delete the file
                var file_details = JSON.stringify(element);
                var datapass = "?delete_file=true&file_details="+file_details;

                // send the data
                sendData2("GET","finance/financial.php", datapass, cObj("supporting_document_err"), cObj("load_documents"));
                setTimeout(() => {
                    var timeout = 0;
                    var ids = setInterval(() => {
                        timeout++;
                        //after two minutes of slow connection the next process wont be executed
                        if (timeout == 1200) {
                            stopInterval(ids);
                        }
                        if (cObj("load_documents").classList.contains("hide")) {
                            var new_file_list = [];
                            for (let ind = 0; ind < supporting_documents_list.length; ind++) {
                                const elems = supporting_documents_list[ind];
                                if (elems.id != this_id) {
                                    new_file_list.push(elems);
                                }
                            }

                            cObj("supporting_documents_list").value = JSON.stringify(new_file_list);
                            displaySupportingDocuments();
                            stopInterval(ids);
                        }
                    }, 100);
                }, 200);
                break;
            }
        }
    }
}

cObj("payment_information_no").onclick = function () {
    cObj("payment_details_window").classList.add("hide");
}

cObj("add_revenue_sub_category").onclick = function () {
    var err = checkBlank("add_revenue_sub_categories");
    if (err == 0) {
        var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder");
        var revenue_holder = [];
        if (hasJsonStructure(add_revenue_sub_categories_holder)) {
            revenue_holder = JSON.parse(add_revenue_sub_categories_holder);
        }
    
        // get the id
        var id = 0;
        for (let index = 0; index < revenue_holder.length; index++) {
            const element = revenue_holder[index];
            if(element.id >= id){
                id = element.id;
            }
        }
    
        // add id plus 1
        id+=1;
        
        // add a new id
        var new_revenue = {id:id, name: valObj("add_revenue_sub_categories")};
        revenue_holder.push(new_revenue);

        // return the value to the revenue holder
        cObj("add_revenue_sub_categories_holder").value = JSON.stringify(revenue_holder);

        // reset the field
        cObj("add_revenue_sub_categories").value = "";

        // display subcategories
        display_revenue_subs();
    }
}
function display_revenue_subs() {
    var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder");
    var data_to_display = "";
    if (hasJsonStructure(add_revenue_sub_categories_holder)) {
        add_revenue_sub_categories_holder = JSON.parse(add_revenue_sub_categories_holder);
        if (add_revenue_sub_categories_holder.length > 0) {
            // display table
            data_to_display = "<div class='container my-2 tableme'><table class='table col-md-12'><tr><th>No.</th><th>Revenue Sub-Categories</th><th>Action</th></tr>";
            for (let index = 0; index < add_revenue_sub_categories_holder.length; index++) {
                const element = add_revenue_sub_categories_holder[index];
                data_to_display+="<tr><td>"+ (index+1) +".</td><td>"+element.name+"</td><td><span class='link edit_revenue' id='edit_revenue_"+element.id+"'><i class='fas fa-trash'></i> Delete</span></td></tr>";
            }
            data_to_display+="</table></div>";
        }else{
            data_to_display += "<p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>";
        }
    }else{
        data_to_display += "<p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>";
    }

    cObj("add_revenue_subcategory_table").innerHTML = data_to_display;

    // add event listener
    var edit_revenue = document.getElementsByClassName("edit_revenue");
    for (let index = 0; index < edit_revenue.length; index++) {
        const element = edit_revenue[index];
        element.addEventListener("click", revenue_event_listener);
    }
}

function revenue_event_listener() {
    var id = this.id.substr(13);
    var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder");
    if (hasJsonStructure(add_revenue_sub_categories_holder)) {
        add_revenue_sub_categories_holder = JSON.parse(add_revenue_sub_categories_holder);

        // loop through the 
        var new_revenue_list = [];
        for (let index = 0; index < add_revenue_sub_categories_holder.length; index++) {
            const element = add_revenue_sub_categories_holder[index];
            if (element.id == id) {
                continue;
            }
            new_revenue_list.push(element);
        }

        // return value
        cObj("add_revenue_sub_categories_holder").value = JSON.stringify(new_revenue_list);
    }

    // display value
    display_revenue_subs();
}

cObj("add_revenue_sub_category_1").onclick = function () {
    var err = checkBlank("add_revenue_sub_categories_1");
    if (err == 0) {
        var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder_1");
        var revenue_holder = [];
        if (hasJsonStructure(add_revenue_sub_categories_holder)) {
            revenue_holder = JSON.parse(add_revenue_sub_categories_holder);
        }
    
        // get the id
        var id = 0;
        for (let index = 0; index < revenue_holder.length; index++) {
            const element = revenue_holder[index];
            if(element.id >= id){
                id = element.id;
            }
        }
    
        // add id plus 1
        id+=1;
        
        // add a new id
        var new_revenue = {id:id, name: valObj("add_revenue_sub_categories_1")};
        revenue_holder.push(new_revenue);

        // return the value to the revenue holder
        cObj("add_revenue_sub_categories_holder_1").value = JSON.stringify(revenue_holder);

        // reset the field
        cObj("add_revenue_sub_categories_1").value = "";

        // display subcategories
        display_revenue_subs_1();
    }
}
function display_revenue_subs_1() {
    var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder_1");
    var data_to_display = "";
    if (hasJsonStructure(add_revenue_sub_categories_holder)) {
        add_revenue_sub_categories_holder = JSON.parse(add_revenue_sub_categories_holder);
        if (add_revenue_sub_categories_holder.length > 0) {
            // display table
            data_to_display = "<table class='table col-md-12'><tr><th>No.</th><th>Revenue Sub-Categories</th><th>Action</th></tr>";
            for (let index = 0; index < add_revenue_sub_categories_holder.length; index++) {
                const element = add_revenue_sub_categories_holder[index];
                data_to_display+="<tr><td>"+ (index+1) +".</td><td>"+element.name+"</td><td><span class='link edit_revenue_1' id='edit_revenue_1_"+element.id+"'><i class='fas fa-trash'></i> Delete</span></td></tr>";
            }
            data_to_display+="</table>";
        }else{
            data_to_display += "<p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>";
        }
    }else{
        data_to_display += "<p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>";
    }

    cObj("add_revenue_subcategory_table_1").innerHTML = data_to_display;

    // add event listener
    var edit_revenue = document.getElementsByClassName("edit_revenue_1");
    for (let index = 0; index < edit_revenue.length; index++) {
        const element = edit_revenue[index];
        element.addEventListener("click", revenue_event_listener_1);
    }
}

function revenue_event_listener_1() {
    var id = this.id.substr(15);
    var add_revenue_sub_categories_holder = valObj("add_revenue_sub_categories_holder_1");
    if (hasJsonStructure(add_revenue_sub_categories_holder)) {
        add_revenue_sub_categories_holder = JSON.parse(add_revenue_sub_categories_holder);

        // loop through the 
        var new_revenue_list = [];
        for (let index = 0; index < add_revenue_sub_categories_holder.length; index++) {
            const element = add_revenue_sub_categories_holder[index];
            if (element.id == id) {
                continue;
            }
            new_revenue_list.push(element);
        }

        // return value
        cObj("add_revenue_sub_categories_holder_1").value = JSON.stringify(new_revenue_list);
    }

    // display value
    display_revenue_subs_1();
}

cObj("add-supplier-btn").onclick = function () {
    cObj("supplier_list").classList.add("hide");
    cObj("register_suppliers").classList.remove("hide");
    cObj("edit_suppliers").classList.add("hide");
}

cObj("return_to_supplier_list").onclick = function () {
    cObj("supplier_list").classList.remove("hide");
    cObj("register_suppliers").classList.add("hide");
    cObj("edit_suppliers").classList.add("hide");

    // children selected
    cObj("supplier_prefix").children[0].selected = true;
    display_supplier();
};

// save suppliers
cObj("save_suppliers").onclick = function () {
    var err = checkBlank("supplier_name");
    err += checkBlank("company_name");
    err += checkBlank("supplier_phone");
    err += checkBlank("supplier_email");
    
    if (err == 0) {
        cObj("supplier_errors").innerHTML = "";
        var datapass = "save_supplier=true&supplier_name="+valObj("supplier_name")+"&company_name="+valObj("company_name")+"&supplier_phone="+valObj("supplier_phone")+"&supplier_email="+valObj("supplier_email")+"&supplier_address="+valObj("supplier_address")+"&supplier_prefix="+valObj("supplier_prefix");
        datapass+="&supplier_openning_balance="+valObj("supplier_openning_balance")+"&supplier_bank_name="+valObj("supplier_bank_name")+"&account_no="+valObj("account_no");
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("supplier_errors"),cObj("save_suppliers_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_suppliers_loader").classList.contains("hide")) {
                    cObj("supplier_name").value = "";
                    cObj("company_name").value = "";
                    cObj("supplier_phone").value = "";
                    cObj("supplier_email").value = "";
                    cObj("supplier_address").value = "";
                    cObj("supplier_prefix").children[0].selected = true;
                    cObj("supplier_openning_balance").value = "";
                    cObj("supplier_bank_name").value = "";
                    cObj("account_no").value = "";
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("supplier_errors").innerHTML = "<p class='text-danger'>Kindly fill all fields with the red borders!</p>";
    }
}

function display_supplier(page = 1){
    var datapass = "get_suppliers="+page;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("show_supplier_list"),cObj("show_supplier_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("show_supplier_loader").classList.contains("hide")) {
                var data_to_display = hasJsonStructure(cObj("show_supplier_list").innerText) ? JSON.parse(cObj("show_supplier_list").innerText) : {"suppliers" : [],"total_pages" : 0};
                display_all_supplier(data_to_display.suppliers);
                cObj("supplier_page_index").innerText = "Page "+page+" of "+data_to_display.total_pages;
                cObj("supplier_page").value = page;
                cObj("maximum_supplier_page").value = data_to_display.total_pages;
                stopInterval(ids);
            }

            // navigation buttons
            cObj("next_supplier_page").addEventListener("click",navigate_right);
            cObj("previous_supplier_page").addEventListener("click",navigate_left);
        }, 100);
    }, 200);
}

function search_school_suppliers() {
    var keyword = this.value.toLowerCase();
    var row = cObj("supplier_table_list").children;

    // new row
    var new_row = row;
    for (let index = 0; index < 4; index++) {
        new_row = new_row.length > 0 ? (new_row[0].tagName == "TR" ? new_row : new_row[0].children) : [];
        if (new_row.length > 0) {
            if (new_row[0].tagName == "TR") {
                break;
            }else{
                new_row = new_row[0].children;
            }
        }else{
            new_row = [];
        }
    }

    // row length
    row = new_row;
    for (let index = 1; index < row.length; index++) {
        const element = row[index];
        var columns = element.children;

        // check if in this row the column has what the keyword shows
        var present = 0;
        for (let index = 1; index < (columns.length-1); index++) {
            const elem = columns[index];
            if (elem.innerText.toLowerCase().includes(keyword)) {
                present++;
            }
        }

        // if the keyword is present show the row
        if (present > 0) {
            element.classList.remove("hide");
        }else{
            element.classList.add("hide");
        }
    }
}

function navigate_left() {
    if (valObj("maximum_supplier_page") == valObj("supplier_page")) {
        // add disabled
        this.classList.add("disabled");
    }else{
        // remove disabled
        this.classList.remove("disabled");
        var supplier_page = valObj("supplier_page");

        cObj("supplier_page").value = supplier_page-1;
        display_supplier((supplier_page-1));
    }
}
function navigate_right() {
    if (valObj("maximum_supplier_page") == valObj("supplier_page")) {
        // add disabled
        this.classList.add("disabled");
    }else{
        // remove disabled
        this.classList.remove("disabled");
        var supplier_page = valObj("supplier_page");

        cObj("supplier_page").value = supplier_page+1;
        display_supplier((supplier_page+1));
    }
}

function display_all_supplier(data_to_display) {
    var data_in_display = "";
    if (data_to_display.length > 0) {
        data_in_display = "<table id='supplier_table_list' class='table'><tr><th>No.</th><th>Supplier Name</th><th>Amount Owed</th><th>Date Registered.</th><th>Company Name</th><th>Contact</th><th>Action</th></tr>";
        for (let index = 0; index < data_to_display.length; index++) {
            const element = data_to_display[index];
            data_in_display+="<tr><td><input type='hidden' id='supplier_details_"+element.supplier_id+"' value='"+JSON.stringify(element)+"'>"+(index+1)+"</td><td>"+element.supplier_name+"</td><td>Kes "+element.amount_owed+"</td><td>"+element.date_registered+"</td><td>"+element.company_name+"</td><td>"+element.supplier_phone+"</td><td><span style='font-size:12px;' class='link view_suppliers' id='view_supplier_"+element.supplier_id+"'><i class='fa fa-eye'></i> View </span></td></tr>";        
        }
        data_in_display+="</table>";
    }else{
        data_in_display="<p class='text-danger'>No supplier data found!</p>";
    }

    // data to display
    cObj("supplier_table").innerHTML = data_in_display;

    // add an event listener
    var view_suppliers = document.getElementsByClassName("view_suppliers");
    for (let index = 0; index < view_suppliers.length; index++) {
        const element = view_suppliers[index];
        element.addEventListener("click",view_supplier);
    }

    // search keyword
    cObj("search_school_suppliers").addEventListener("keyup",search_school_suppliers);
}

// view suppliers
function view_supplier() {
    cObj("edit_suppliers").classList.remove("hide");
    cObj("register_suppliers").classList.add("hide");
    cObj("supplier_list").classList.add("hide");

    // get the supplier details
    var id = "supplier_details_"+this.id.substr(14);
    var supplier_details = valObj(id);
    if (hasJsonStructure(supplier_details)) {
        // supplier details
        supplier_details = JSON.parse(supplier_details);
        cObj("supplier_href_link").href = "reports/reports.php?supplier_account_id="+supplier_details.supplier_id;

        // set the values
        cObj("supplier_phone_2").value = supplier_details.supplier_phone;
        cObj("supplier_email_2").value = supplier_details.supplier_email;
        cObj("supplier_address_2").value = supplier_details.supplier_address;
        cObj("supplier_bank_name_2").value = supplier_details.supplier_bank_name;
        cObj("account_no_2").value = supplier_details.account_no;
        cObj("supplier_note_2").value = supplier_details.supplier_note;
        cObj("company_name_2").value = supplier_details.company_name;
        cObj("supplier_id").value = supplier_details.supplier_id;

        // split the company name
        var prefix = supplier_details.supplier_name.split(".")[0];
        if (prefix == "Mr" || prefix == "Mrs" || prefix == "Sir" || prefix == "Madam") {
            var children = cObj("supplier_prefix_2").children;
            for (let index = 0; index < children.length; index++) {
                const element = children[index];
                if (element.value == prefix) {
                    element.selected = true;
                    break;
                }
            }

            var pref_length = prefix.length;
            cObj("supplier_name_2").value = pref_length > 0 ? supplier_details.supplier_name.substr((pref_length+2),supplier_details.supplier_name.length) : supplier_details.supplier_name;
        }else{
            var pref_length = prefix.length;
            cObj("supplier_name_2").value = supplier_details.supplier_name;
        }

    }

    // display bill and supply
    display_bills_n_payments(supplier_details.supplier_id);
}

function display_bills_n_payments(supplier_id) {
    // get the supplier bill and supplier payments
    var datapass = "?get_supplier_data="+supplier_id;
    sendData2("GET","/administration/admissions.php",datapass,cObj("supplier_payment_bills"),cObj("supplier_data_loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("supplier_data_loaders").classList.contains("hide")) {
                // split the bills and payments
                var supplier_payments_and_bills = cObj("supplier_payment_bills").innerText;
                if (hasJsonStructure(supplier_payments_and_bills)) {
                    // supplier payments and bills
                    supplier_payments_and_bills = JSON.parse(supplier_payments_and_bills);

                    // bills
                    var bills = supplier_payments_and_bills.supplier_bill;
                    var payments = supplier_payments_and_bills.supplier_payment;

                    // display the data
                    display_suppliers_bill(bills);
                    display_suppliers_payments(payments);
                    
                }else{
                    display_suppliers_bill([]);
                    display_suppliers_payments([]);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function display_suppliers_bill(bills) {
    var data_to_display = "<table class='table'><tr><th>No.</th><th>Bill Name</th><th>Amount</th><th>Date Registered</th><th>Action</th></tr>";
    if (bills.length > 0) {
        for (let index = 0; index < bills.length; index++) {
            const element = bills[index];
            data_to_display+="<tr>";
            data_to_display+="<td> <input type='hidden' id='each_bill_values_"+element.bill_id+"' value='"+JSON.stringify(element)+"'>"+(index+1)+"</td>";
            data_to_display+="<td>"+element.bill_name+"</td>";
            data_to_display+="<td>Kes "+element.bill_amount+"</td>";
            data_to_display+="<td>"+element.date+"</td>";
            data_to_display+="<td><span class='link supplier_bills' id='supplier_bills_"+element.bill_id+"'><i class='fas fa-eye'></i> View</span></td>";
            data_to_display+="</tr>";
        }
        data_to_display+="</table>";
    }else{
        data_to_display = "<p class='text-danger text-center'>Supplier bills not present!</p>";
    }

    // inner html
    cObj("supplier_bill_tables").innerHTML = data_to_display;

    // get the supplier bills
    var supplier_bills = document.getElementsByClassName("supplier_bills");
    for (let index = 0; index < supplier_bills.length; index++) {
        const element = supplier_bills[index];
        element.addEventListener("click",edit_supplier_bills);
    }
}

function edit_supplier_bills() {
    var this_id = this.id.substr(15);
    var edit_supplier_bill = valObj("each_bill_values_"+this_id);

    if (hasJsonStructure(edit_supplier_bill)) {
        // edit_supplier_bill
        edit_supplier_bill = JSON.parse(edit_supplier_bill);
        cObj("supplier_bill_name_edit").value = edit_supplier_bill.bill_name;
        cObj("supplier_bill_amount_edit").value = edit_supplier_bill.real_amount;
        cObj("date_assigned_edit").value = edit_supplier_bill.real_date.substr(0,4)+"-"+edit_supplier_bill.real_date.substr(4,2)+"-"+edit_supplier_bill.real_date.substr(6,2);
        cObj("supplier_bill_due_date_edit").value = edit_supplier_bill.due_date.substr(0,4)+"-"+edit_supplier_bill.due_date.substr(4,2)+"-"+edit_supplier_bill.due_date.substr(6,2);
        cObj("supplier_document_number_edit").value = edit_supplier_bill.document_number;
        cObj("supplier_bill_id_edit").value = edit_supplier_bill.bill_id;

        // set the capital expenses
        var children = cObj("expense_type_edit").children;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if (element.value == edit_supplier_bill.expense_type) {
                element.selected = true;
            }
        }
        
        // show id
        var show = edit_supplier_bill.expense_type == "capital" ? "capital_expenses" : "operational_expense";
        var hide = edit_supplier_bill.expense_type == "capital" ? "operational_expense" : "capital_expenses";
        
        var show_element = document.getElementsByClassName(show);
        var hide_element = document.getElementsByClassName(hide);

        // document
        var children = cObj("asset_expense_category_edit").children;
        children[0].selected = true;

        // get the other document
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if (element.value == edit_supplier_bill.expense_category) {
                // selected element
                element.selected = true;
            }
        }

        // show element
        for (let index = 0; index < show_element.length; index++) {
            const element = show_element[index];
            element.classList.remove("hide");
        }

        // hide element
        for (let index = 0; index < hide_element.length; index++) {
            const element = hide_element[index];
            element.classList.add("hide");
        }

        // show the edit window
        cObj("edit_supplier_biil").classList.remove("hide");

        // get the expense category

        // get the bill category
        var datapass = "get_expense_categories=true&expense_category_id=supplier_expense_category_edit&selected_value="+ edit_supplier_bill.expense_category;
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("supplier_expense_cat_edit"),cObj("display_supplier_expense_category_edit"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("display_supplier_expense_category_edit").classList.contains("hide")) {
                    if(cObj("supplier_expense_category_edit") != undefined){
                        cObj("supplier_expense_category_edit").addEventListener("change", function () {
                            display_expense_sub_category_edit("");
                        });
                    }

                    // display subcategory
                    display_expense_sub_category_edit(edit_supplier_bill.expense_sub_category);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

// update the bill
cObj("save_new_supplier_bill_edit").onclick = function () {
    var err = checkBlank("supplier_bill_name_edit");
    err+=checkBlank("supplier_bill_amount_edit");
    err+=checkBlank("date_assigned_edit");
    err+=checkBlank("supplier_bill_due_date_edit");
    err += checkBlank("expense_type_edit");
    err += valObj("expense_type_edit") == "operation" ? (cObj("supplier_expense_category_edit") != undefined ? checkBlank("supplier_expense_category_edit") : 0) : 0;
    err += valObj("expense_type_edit") == "operation" ? (cObj("supplier_expense_sub_category_edit") != undefined ? checkBlank("supplier_expense_sub_category_edit") : 0) : 0;
    err += valObj("expense_type_edit") == "capital" ? checkBlank("asset_expense_category_edit") : 0;

    // get error
    if (err == 0) {
        var supplier_bill_name_edit = valObj("supplier_bill_name_edit");
        var supplier_bill_amount_edit = valObj("supplier_bill_amount_edit");
        var date_assigned_edit = valObj("date_assigned_edit");
        var supplier_bill_due_date_edit = valObj("supplier_bill_due_date_edit");

        var datapass = "update_bill=true&bill_name="+supplier_bill_name_edit+"&bill_amount="+supplier_bill_amount_edit+"&date_assigned="+date_assigned_edit+"&due_date="+supplier_bill_due_date_edit+"&supplier_bill_id_edit="+valObj("supplier_bill_id_edit")+"&supplier_document_number="+valObj("supplier_document_number_edit");
        datapass += "&supplier_expense_category_edit="+valObj("supplier_expense_category_edit")+"&supplier_expense_sub_category_edit="+valObj("supplier_expense_sub_category_edit");
        datapass += "&asset_expense_category="+valObj("asset_expense_category_edit")+"&expense_type="+valObj("expense_type_edit");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_bill_error_edit"),cObj("save_bill_loader_edit"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_bill_loader_edit").classList.contains("hide")) {
                    display_bills_n_payments(valObj("supplier_id"));
                    setTimeout(() => {
                        cObj("supplier_bill_error_edit").innerHTML = "";
                        cObj("close_new_supplier_bill_window_edit").click();
                    }, 1000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

cObj("close_new_supplier_bill_window_edit").onclick = function () {
    cObj("edit_supplier_biil").classList.add("hide");
    cObj("supplier_expense_sub_cat_edit").innerHTML = "<p class='text-danger'>Please select the bill category to display the subcategories</p>";
}
// edit suppliers
cObj("close_edit_supplier_bill").onclick = function () {
    cObj("edit_supplier_biil").classList.add("hide");
}

function display_suppliers_payments(payments) {
    var data_to_display = "<table class='table'><tr><th>No.</th><th>Paid Amount</th><th>Date Paid</th><th>Action</th></tr>";
    if (payments.length > 0) {
        for (let index = 0; index < payments.length; index++) {
            const element = payments[index];
            var status = element.approval_status == 1 ? "<span class='badge bg-success' title='Payment Approved'>A</span>" : (element.approval_status == 0 ? "<span class='badge bg-warning' title='Payment Not Approved'>-A</span>" : "<span class='badge bg-danger' title='Payment Declined'>-A</span>");
            var print_enable = element.approval_status == 1 ? "" : "hide";
            var encodedValue = encodeURIComponent(JSON.stringify(element)).replace(/'/g, '%27');
            data_to_display+="<tr>";
            data_to_display+="<td><input type='hidden' id='supplier_payment_dets_"+element.payment_id+"' value='"+encodedValue+"'> "+(index+1)+" "+status+"</td>";
            data_to_display+="<td>Kes "+element.amount+"</td>";
            data_to_display+="<td>"+element.date+"</td>";
            data_to_display+="<td><span class='link supplier_payment' id='supplier_payment_"+element.payment_id+"'><i class='fas fa-eye'></i> View</span> <br><a class='"+print_enable+" link text-sm' target='_blank' href='reports/reports.php?supplier_payment_id="+element.payment_id+"'><i class='fas fa-print'></i> Print</a></td>";
            data_to_display+="</tr>";
        }
        data_to_display+="</table>";
    }else{
        data_to_display = "<p class='text-danger text-center'>Supplier payments not present!</p>";
    }

    // inner html
    cObj("supplier_payment_table").innerHTML = data_to_display;

    var supplier_payment = document.getElementsByClassName("supplier_payment");//edit_supplier_payments_window
    for (let index = 0; index < supplier_payment.length; index++) {
        const element = supplier_payment[index];
        element.addEventListener("click", edit_supplier_payments);
    }
}

function edit_supplier_payments() {

    // display the payment for details
    var supplier_payment_dets = decodeURIComponent(valObj("supplier_payment_dets_"+this.id.substr(17)));
    if (hasJsonStructure(supplier_payment_dets)) {
        cObj("edit_supplier_payments_window").classList.remove("hide");
        supplier_payment_dets = JSON.parse(supplier_payment_dets);
        cObj("supplier_payment_amount_edit").value = supplier_payment_dets.real_amount
        cObj("supplier_payment_date_edit").value = supplier_payment_dets.date_paid.substr(0,4)+"-"+supplier_payment_dets.date_paid.substr(4,2)+"-"+supplier_payment_dets.date_paid.substr(6,2);
        cObj("supplier_payment_document_no_edit").value = supplier_payment_dets.document_number
        cObj("supplier_payment_description_edit").value = supplier_payment_dets.payment_description
        cObj("supplier_payment_id").value = supplier_payment_dets.payment_id
        cObj("show_supplier_payment_status").innerHTML = supplier_payment_dets.approval_status == 1 ? "<span class='badge bg-success'>Payment Approved</span>" : (supplier_payment_dets.approval_status == 0 ? "<span class='badge bg-warning'>Payment Not Approved Yet!</span>" : "<span class='badge bg-danger'>Payment Declined!</span>");
        cObj("show_reason_supplier_payment_decline").innerHTML = supplier_payment_dets.approval_comment != null ? (supplier_payment_dets.approval_comment.length > 0 ? supplier_payment_dets.approval_comment : "No reason stated!") : "No reason stated!";

        // disable some elements when the payment status is approved
        if (supplier_payment_dets.approval_status == 1) {
            cObj("supplier_payment_amount_edit").disabled = true;
            cObj("payment-method-edit").disabled = true;
            cObj("delete_payments").classList.add("hide");
            cObj("show_reason_payment_declined").classList.add("hide");
        }else{
            cObj("supplier_payment_amount_edit").disabled = false;
            cObj("payment-method-edit").disabled = false;
            cObj("delete_payments").classList.remove("hide");
            if(supplier_payment_dets.approval_status == 2){
                cObj("show_reason_payment_declined").classList.remove("hide");
            }else{
                cObj("show_reason_payment_declined").classList.add("hide");
            }
        }

        var children = cObj("payment-method-edit").children;
        children[0].selected = true;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if(element.value == supplier_payment_dets.payment_method){
                element.selected = true;
            }
        }
        
        // get the payment option
        var disabled = supplier_payment_dets.approval_status == 1;
        get_payment_option("supplier_payment_for_edit",supplier_payment_dets.payment_for,"payment_for_details_edit","supplier_payment_for_loader_edit",disabled);
    }else{
        // display an error has occured!
    }
}

cObj("close_edit_supplier_payments").onclick = function () {
    cObj("edit_supplier_payments_window").classList.add("hide");
}

cObj("close_supplier_payment_edit").onclick = function () {
    cObj("edit_supplier_payments_window").classList.add("hide");
}
cObj("return_to_supplier_list_2").onclick = function () {
    cObj("edit_suppliers").classList.add("hide");
    cObj("register_suppliers").classList.add("hide");
    cObj("supplier_list").classList.remove("hide");
    display_supplier();
}

cObj("save_suppliers_2").onclick = function () {
    var datapass = "update_suppliers=true&company_name="+valObj("company_name_2")+"&supplier_prefix="+valObj("supplier_prefix_2")+"&supplier_name="+valObj("supplier_name_2")+"&supplier_phone="+valObj("supplier_phone_2")+"&supplier_email="+valObj("supplier_email_2")+"&supplier_address="+valObj("supplier_address_2")+"&supplier_bank_name="+valObj("supplier_bank_name_2");
    datapass+="&account_no="+valObj("account_no_2")+"&supplier_note="+valObj("supplier_note_2")+"&supplier_id="+valObj("supplier_id");
    sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("supplier_errors_2"),cObj("save_suppliers_loader_2"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("save_suppliers_loader_2").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("supplier_errors_2").innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("close_new_supplier_bill_window").onclick = function () {
    cObj("add_supplier_biil").classList.add("hide");
}

cObj("close_add_supplier_bill").onclick = function () {
    cObj("add_supplier_biil").classList.add("hide");
}

cObj("add_bills").onclick = function () {
    cObj("add_supplier_biil").classList.remove("hide");

    // get the bill category
    var datapass = "get_expense_categories=true&expense_category_id=supplier_expense_category&selected_value=";
    sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("supplier_expense_cat"),cObj("display_supplier_expense_category"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("display_supplier_expense_category").classList.contains("hide")) {
                if(cObj("supplier_expense_category") != undefined){
                    cObj("supplier_expense_category").addEventListener("change", display_expense_sub_category);
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);

    // get the asset categories
    var datapass = "asset_categories=true";
}

function display_expense_sub_category() {
    // datapass
    var datapass = "get_expense_category=true&expense_category_id="+this.value+"&id=supplier_expense_sub_category&value=";
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_expense_sub_cat"),cObj("display_supplier_expense_sub_category"));
}

function display_expense_sub_category_edit(expense_subcategories = "") {
    // datapass
    var datapass = "get_expense_category=true&expense_category_id="+valObj("supplier_expense_category_edit")+"&id=supplier_expense_sub_category_edit&value="+expense_subcategories;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_expense_sub_cat_edit"),cObj("display_supplier_expense_sub_category_edit"));
}

cObj("save_new_supplier_bill").onclick = function () {
    var err = checkBlank("supplier_bill_name");
    err+= checkBlank("supplier_bill_amount");
    err+= checkBlank("date_assigned");
    err += valObj("expense_type") == "capital" ?  checkBlank("asset_expense_category") : 0;
    err += valObj("expense_type") == "operation" ? (cObj("supplier_expense_category") != undefined ? checkBlank("supplier_expense_category") : 1) : 0;
    err += valObj("expense_type") == "operation" ? (cObj("supplier_expense_sub_category") != undefined ? checkBlank("supplier_expense_sub_category") : 1) : 0;

    if (valObj("expense_type") == "capital") {
        err += valObj("asset_acquisition_rates") > 100 ? 1 : 0;
        cObj("supplier_bill_error").innerHTML = valObj("asset_acquisition_rates") > 100 ? "<p class='text-danger'>The asset depreciation rate can`t be more than 100</p>" : "";
    }else{
        cObj("supplier_bill_error").innerHTML = "";
    }

    if (err == 0) {
        var datapass = "save_supplier_bill=true&supplier_id="+valObj("supplier_id")+"&supplier_bill_name="+valObj("supplier_bill_name")+"&supplier_bill_amount="+valObj("supplier_bill_amount")+"&date_assigned="+valObj("date_assigned")+"&supplier_document_number="+valObj("supplier_document_number")+"&supplier_bill_due_date="+valObj("supplier_bill_due_date");
        datapass += "&supplier_expense_category="+valObj("supplier_expense_category")+"&supplier_expense_sub_category="+valObj("supplier_expense_sub_category");
        datapass += "&asset_expense_category="+valObj("asset_expense_category")+"&asset_acquisition_method="+valObj("asset_acquisition_method")+"&asset_acquisition_rates="+valObj("asset_acquisition_rates")+"&supplier_expense_type="+valObj("expense_type");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_bill_error"),cObj("save_bill_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_suppliers_loader_2").classList.contains("hide")) {
                    // clear the form
                    cObj("supplier_bill_name").value = "";
                    cObj("supplier_bill_amount").value = "";
                    cObj("supplier_document_number").value = "";

                    // 
                    var capital_expenses = document.getElementsByClassName("capital_expenses");
                    var operational_expense = document.getElementsByClassName("operational_expense");
                    
                    // hide the capital expenses and operational expenses
                    for (let index = 0; index < capital_expenses.length; index++) {
                        const element = capital_expenses[index];
                        element.classList.add("hide");
                    }
                    
                    for (let index = 0; index < operational_expense.length; index++) {
                        const element = operational_expense[index];
                        element.classList.add("hide");
                    }
                    
                    // reset the expense type
                    cObj("expense_type").children[0].selected = true;
                    cObj("asset_expense_category").children[0].selected = true;
                    cObj("asset_acquisition_rates").value = 0;

                    // hide the window
                    setTimeout(() => {
                        cObj("close_add_supplier_bill").click();
                        cObj("supplier_bill_error").innerHTML = "";
                    }, 1000);

                    // redisplay the data
                    display_bills_n_payments(valObj("supplier_id"));
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("supplier_bill_error").innerHTML = "<p class='text-danger'>Fill all fields covered with the red border!</p>";
    }
}

cObj("close_supplier_payment").onclick = function () {
    cObj("make_payments_window").classList.add("hide");
}

cObj("close_supplier_payments").onclick = function () {
    cObj("make_payments_window").classList.add("hide");
}

cObj("add_payments").onclick = function () {
    cObj("make_payments_window").classList.remove("hide");
    // get the payment options
    get_payment_option("supplier_payment_for","");
}

function get_payment_option(option_id,option_value, display_area = "payment_for_details", loader = "supplier_payment_for_loader", disabled = false) {
    var datapass = "get_payment_for=true&option_id="+option_id+"&option_value="+option_value+"&supplier_id="+valObj("supplier_id");
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj(display_area),cObj(loader));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj(loader).classList.contains("hide")) {
                cObj(option_id).disabled = disabled;
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("make_supplier_payment_edit").onclick = function () {
    var err = checkBlank("supplier_payment_for_edit");
    err+=checkBlank("supplier_payment_amount_edit");
    err+=checkBlank("supplier_payment_date_edit");
    err+=checkBlank("payment-method-edit");

    // check for errors
    if(err == 0){
        var datapass = "save_supplier_data=true&payment_amount="+valObj("supplier_payment_amount_edit")+"&payment_date="+valObj("supplier_payment_date_edit")+"&document_number="+valObj("supplier_payment_document_no_edit")+"&payment_description="+valObj("supplier_payment_description_edit")+"&supplier_payment_id="+valObj("supplier_payment_id")+"&supplier_payment_for="+valObj("supplier_payment_for_edit");
        datapass+="&payment_method="+valObj("payment-method-edit");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_payment_description_error_edit"),cObj("make_payment_loader_edit"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("make_payment_loader_edit").classList.contains("hide")) {
                    if(cObj("supplier_payment_error_edit") != undefined){
                        // show the window still
                    }else{
                        // clear the form
                        // cObj("supplier_payment_amount_edit").value = "";
                        // cObj("supplier_payment_date_edit").value = "";
                        // cObj("supplier_payment_document_no_edit").value = "";
                        // cObj("supplier_payment_description_edit").value = "";
    
                        // hide the window
                        setTimeout(() => {
                            cObj("close_supplier_payment_edit").click();
                            cObj("supplier_payment_description_error_edit").innerHTML = "";
                        }, 1000);
                    }

                    // redisplay the data
                    display_bills_n_payments(valObj("supplier_id"));
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{

    }
}

cObj("make_supplier_payment").onclick = function () {
    var err = checkBlank("supplier_payment_for");
    err += checkBlank("supplier_payment_amount");
    err += checkBlank("supplier_payment_date");
    err += checkBlank("payment-method");

    if (err == 0) {
        var datapass = "save_payment=true&supplier_payment_for="+valObj("supplier_payment_for")+"&supplier_payment_amount="+valObj("supplier_payment_amount")+"&supplier_payment_date="+valObj("supplier_payment_date")+"&supplier_payment_description="+valObj("supplier_payment_description")+"&supplier_payment_document_no="+valObj("supplier_payment_document_no");
        datapass += "&payment_method="+valObj("payment-method");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_payment_description_error"),cObj("make_payment_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_suppliers_loader_2").classList.contains("hide")) {
                    if(cObj("supplier_payment_error") != undefined){
                        // show the window still
                    }else{
                        // clear the form
                        cObj("supplier_payment_amount").value = "";
                        cObj("supplier_payment_date").value = "";
                        cObj("supplier_payment_document_no").value = "";
                        cObj("supplier_payment_description").value = "";
                        cObj("supplier_payment_for").children[0].selected = true;
    
                        // hide the window
                        setTimeout(() => {
                            cObj("close_supplier_payment").click();
                            cObj("supplier_payment_description_error").innerHTML = "";
                        }, 1000);
                    }

                    // redisplay the data
                    display_bills_n_payments(valObj("supplier_id"));
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

cObj("delete_payments").onclick = function () {
    cObj("delete_payment_window").classList.toggle("hide");
}

cObj("cancel_delete_payments").onclick = function () {
    cObj("delete_payment_window").classList.toggle("hide");
}

cObj("confirm_delete_payments").onclick = function () {
    // delete the payment
    var datapass = "delete_supplier_payment="+valObj("supplier_payment_id");
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_payment_description_error_edit"),cObj("delete_payment_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("delete_payment_loader").classList.contains("hide")) {
                // hide the window
                setTimeout(() => {
                    cObj("close_supplier_payment_edit").click();
                    cObj("supplier_payment_description_error_edit").innerHTML = "";
                    cObj("cancel_delete_payments").click();
                }, 100);

                // redisplay the data
                display_bills_n_payments(valObj("supplier_id"));
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("delete_supplier_bill").onclick = function () {
    cObj("delete_bill_confirmation_window").classList.toggle("hide");
}

cObj("cancel_bill_deletion").onclick = function () {
    cObj("delete_bill_confirmation_window").classList.toggle("hide");
}

cObj("confirm_bill_deletion").onclick = function () {
    var datapass = "delete_supplier_bill="+valObj("supplier_bill_id_edit");
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_bill_error_edit"),cObj("delete_bill_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("delete_bill_loader").classList.contains("hide")) {
                // hide the window
                setTimeout(() => {
                    cObj("close_new_supplier_bill_window_edit").click();
                    cObj("supplier_bill_error_edit").innerHTML = "";
                    cObj("cancel_bill_deletion").click();
                }, 2000);

                // redisplay the data
                display_bills_n_payments(valObj("supplier_id"));
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("delete_supplier").onclick = function () {
    console.log("Hide me");
    cObj("delete_supplier_window").classList.toggle("hide");
}

cObj("cancel_delete_supplier").onclick = function () {
    cObj("delete_supplier_window").classList.toggle("hide");
}

cObj("confirm_delete_supplier").onclick = function () {
    var datapass = "delete_supplier="+valObj("supplier_id");
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("supplier_notices"),cObj("show_supplier_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("delete_bill_loader").classList.contains("hide")) {
                // hide the window
                cObj("return_to_supplier_list_2").click();
                cObj("cancel_delete_supplier").click();
                setTimeout(() => {
                    cObj("supplier_notices").innerHTML = "";
                }, 3000);

                // redisplay the data
                display_bills_n_payments(valObj("supplier_id"));
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("save-assets-btn").onclick = function () {
    var err = checkBlank("asset-acquiry-date");
    err += checkBlank("asset-original-value");
    err += checkBlank("value-acquisition-option");
    err += checkBlank("value-acquisition-percentage");
    
    if (err == 0) {
        var datapass = "save_assets=true&acquiry_date="+valObj("asset-acquiry-date")+"&asset_original_value="+valObj("asset-original-value")+"&value_acquisition="+valObj("value-acquisition-option")+"&value_acquisition_percentage="+valObj("value-acquisition-percentage");
        datapass += "&asset_description="+valObj("asset-description")+"&asset_name="+valObj("asset-name")+"&asset_category="+valObj("asset-category")+"&asset_acquiry_date="+valObj("asset-acquiry-date");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("asset-error"),cObj("save-assets-loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save-assets-loader").classList.contains("hide")) {
                    // go bck to list
                    cObj("back-to-assets").click();
                    display_assets();
                    
                    // hide the window
                    setTimeout(() => {
                        cObj("asset-error").innerHTML = "";
                    }, 3000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }
}

cObj("register-new-asset").onclick = function () {
    cObj("register-asset").classList.remove("hide");
    cObj("asset-list").classList.add("hide");
    cObj("edit-assets").classList.add("hide");
}

cObj("back-to-assets").onclick = function () {
    cObj("register-asset").classList.add("hide");
    cObj("asset-list").classList.remove("hide");
    cObj("edit-assets").classList.add("hide");
}
function display_assets(page = 1){
    var datapass = "get_assets="+page;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("asset-data"),cObj("new-asset-loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("new-asset-loader").classList.contains("hide")) {
                var data_to_display = hasJsonStructure(cObj("asset-data").innerText) ? JSON.parse(cObj("asset-data").innerText) : {"assets" : [],"total_pages" : 0};
                display_all_assets(data_to_display.assets);
                
                cObj("asset-current-page").innerText = "Page "+page+" of "+data_to_display.total_pages;
                cObj("asset-page").value = page;
                cObj("maximum-page-asset").value = data_to_display.total_pages;
                stopInterval(ids);
            }

            // navigation buttons
            // cObj("next_supplier_page").addEventListener("click",navigate_right);
            // cObj("previous_supplier_page").addEventListener("click",navigate_left);
        }, 100);
    }, 200);
}

function display_all_assets(data_to_display) {
    var data_in_display = "";
    if (data_to_display.length > 0) {
        data_in_display = "<table id='assets-table-list' class='table'><thead><tr><th>No.</th><th>Assets Name</th><th>Asset Category</th><th>Date Acquired.</th><th>Original Value</th><th>Current Value</th><th>rate</th><th>Action</th></tr></thead><tbody>";
        for (let index = 0; index < data_to_display.length; index++) {
            const element = data_to_display[index];
            var show = element.value_acquisition == "decrease" ? "<i class='fas fa-arrow-down text-danger'></i>" : "<i class='fas fa-arrow-up text-success'></i>";
            var status = element.disposed_status == "1" ? "<small class='badge bg-danger'>-D</small>" : "";
            data_in_display+="<tr><td><input type='hidden' id='assets-values-"+element.asset_id+"' value='"+JSON.stringify(element)+"'>"+(index+1)+" "+status+" </td><td>"+element.asset_name+"</td><td>"+element.asset_category+"</td><td>"+element.date_of_acquiry+"</td><td>Kes "+element.orginal_value+"</td><td>Kes "+element.new_value+" <small>("+element.years+" years later)</small></td><td>"+element.acquisition_rate+"% "+show+"</td><td><span style='font-size:12px;' class='link view-assets' id='view-assets-"+element.asset_id+"'><i class='fa fa-eye'></i> View </span></td></tr>";        
        }
        data_in_display+="</table>";
    }else{
        data_in_display="<p class='text-danger'>No Asset data found!</p>";
    }

    // data to display
    cObj("asset-data-table").innerHTML = data_in_display;

    // add an event listener
    var view_asset = document.getElementsByClassName("view-assets");
    for (let index = 0; index < view_asset.length; index++) {
        const element = view_asset[index];
        element.addEventListener("click",view_assets);
    }

    // // search keyword
    cObj("search-assets").addEventListener("keyup",search_school_assets);
}

function view_assets() {
    // view assets
    var asset_data = cObj("assets-values-"+this.id.substr(12)).value;
    if(hasJsonStructure(asset_data)){
        // display the edit page
        cObj("edit-assets").classList.remove("hide");
        cObj("register-asset").classList.add("hide");
        cObj("asset-list").classList.add("hide");

        // show the error
        cObj("asset-notices-2").innerHTML = "";

        asset_data = JSON.parse(asset_data);
        // fill all the fields.

        cObj("asset-name-edit").value = asset_data.asset_name;
        cObj("asset-acquiry-date-edit").value = asset_data.real_acquisition_date.substr(0,4)+"-"+asset_data.real_acquisition_date.substr(4,2)+"-"+asset_data.real_acquisition_date.substr(6,2);
        cObj("asset-original-value-edit").value = asset_data.real_orginal_value;
        cObj("value-acquisition-percentage-edit").value = asset_data.acquisition_rate;
        cObj("asset-description-edit").value = asset_data.description;
        cObj("asset-id").value = asset_data.asset_id;

        var children = cObj("asset-category-edit").children;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if(element.value == asset_data.real_asset_category){
                element.selected = true;
            }
        }

        var children = cObj("value-acquisition-option-edit").children;
        for (let index = 0; index < children.length; index++) {
            const element = children[index];
            if(element.value == asset_data.acquisition_option){
                element.selected = true;
            }
        }

        // IF ITS A DISPOSED ASSET SHOW WHEN IT WAS DISPOSED AND HIDE THE DISPOSE BUTTON.
        if(asset_data.disposed_status == 1){
            cObj("dispose_assets_btn").classList.add("hide");
            cObj("recycles").classList.remove("hide");
            cObj("date_asset_disposed").innerHTML = asset_data.disposed_on;
            cObj("asset_dispose_value").innerHTML = asset_data.disposed_value;
        }else{
            cObj("recycles").classList.add("hide");
            cObj("dispose_assets_btn").classList.remove("hide");
            cObj("date_asset_disposed").innerHTML = "N/A";
        }

        // display asset account
        view_asset_account(asset_data.asset_id)
    }else{
        cObj("asset-notices-2").innerHTML = "<p class='text-danger'>An error has occured!<br> Reload your page and try again!</p>";
    }
}

function view_asset_account(asset_id) {
    // get the asset depreciation calculation
    var datapass = "get_asset_account="+asset_id;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("asset-accounts-holder"),cObj("asset-account-loaders"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("asset-account-loaders").classList.contains("hide")) {
                var data_to_display = hasJsonStructure(cObj("asset-accounts-holder").innerText) ? JSON.parse(cObj("asset-accounts-holder").innerText) : [];
                // console.log(data_to_display);
                display_asset_account(data_to_display.account);
                stopInterval(ids);
            }

            // navigation buttons
            // cObj("next_supplier_page").addEventListener("click",navigate_right);
            // cObj("previous_supplier_page").addEventListener("click",navigate_left);
        }, 100);
    }, 200);
}

function display_asset_account(datapass) {
    if (datapass.length > 0) {
        var data_to_display = "<table class='table'><tr><th>No</th><th>Name</th><th>Year</th><th>Debit (Dr)</th><th>Credit (Cr)</th><th>Balance</th></tr>";
        for (let index = 0; index < datapass.length; index++) {
            const element = datapass[index];
            data_to_display+="<tr><td>"+(1+index)+".</td><td>"+element.name+"</td><td>"+element.year+"</td><td>"+(element.account == "debit" ? element.amount : "-")+"</td><td>"+(element.account == "credit" ? element.amount : "-")+"</td><td>"+element.balance+"</td></tr>";
        }
        data_to_display+="</table>";

        cObj("asset_transaction_table").innerHTML = data_to_display;
    }else{
        cObj("asset_transaction_table").innerHTML = "<p class='text-success'>Account information is missing!</p>";
    }
}

cObj("back-to-assets-edit").onclick = function () {
    cObj("edit-assets").classList.add("hide");
    cObj("register-asset").classList.add("hide");
    cObj("asset-list").classList.remove("hide");

    // display assets
    display_assets();
}

function search_school_assets() {
    var keyword = this.value.toLowerCase();
    var row = cObj("assets-table-list").children;

    // new row
    var new_row = row;
    for (let index = 0; index < 4; index++) {
        new_row = new_row.length > 0 ? (new_row[0].tagName == "TR" ? new_row : new_row[0].children) : [];
        if (new_row.length > 0) {
            if (new_row[0].tagName == "TR") {
                break;
            }else{
                new_row = new_row[0].children;
            }
        }else{
            new_row = [];
        }
    }

    // row length
    row = new_row;
    for (let index = 1; index < row.length; index++) {
        const element = row[index];
        var columns = element.children;

        // check if in this row the column has what the keyword shows
        var present = 0;
        for (let index = 1; index < (columns.length-1); index++) {
            const elem = columns[index];
            if (elem.innerText.toLowerCase().includes(keyword)) {
                present++;
            }
        }

        // if the keyword is present show the row
        if (present > 0) {
            element.classList.remove("hide");
        }else{
            element.classList.add("hide");
        }
    }
}

cObj("update-assets-btn").onclick = function () {
    var err = checkBlank("asset-name-edit");
    err += checkBlank("asset-category-edit");
    err += checkBlank("asset-acquiry-date-edit");
    err += checkBlank("asset-original-value-edit");
    err += checkBlank("value-acquisition-option-edit");
    err += checkBlank("value-acquisition-percentage-edit");
    
    if (err == 0) {
        cObj("asset-error-edit").innerHTML = "";

        var datapass = "update_asset=true&asset_id="+valObj("asset-id")+"&asset_name="+valObj("asset-name-edit")+"&asset_category="+valObj("asset-category-edit")+"&acquiry_date="+valObj("asset-acquiry-date-edit")+"&original_value="+valObj("asset-original-value-edit")+"&acquisition_option="+valObj("value-acquisition-option-edit")+"&rate="+valObj("value-acquisition-percentage-edit")+"&description="+valObj("asset-description-edit");
        sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("asset-error-edit"),cObj("save-assets-loader-edit"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save-assets-loader-edit").classList.contains("hide")) {
                    // display asset account
                    view_asset_account(valObj("asset-id"));
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("asset-error-edit").innerHTML = "<p class='text-danger'>Fill all fields covered with a red border!</p>";
    }
}

cObj("select_financial_document").onchange = function () {
    // income_statement_window
    if (this.value == "cashflow_statement") {
        cObj("income_statement_window").classList.remove("hide");
        cObj("financial_performance").classList.add("hide");
    }else{
        cObj("income_statement_window").classList.add("hide");
        cObj("financial_performance").classList.remove("hide");
    }
}

cObj("dispose_assets_btn").onclick = function () {
    cObj("dispose_asset_window").classList.toggle("hide");
}

cObj("cancel_asset_disposal").onclick = function () {
    cObj("dispose_asset_window").classList.add("hide");
}

cObj("dispose_asset").onclick = function () {
    var datapass = "?dispose_asset=true&asset_id="+valObj("asset-id")+"&set_disposed_date="+valObj("set_disposed_date")+"&dispose_value="+valObj("dispose_value");
    sendData2("GET","finance/financial.php",datapass, cObj("asset-dispose-error"),cObj("dispose_asset_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("dispose_asset_loader").classList.contains("hide")) {
                // display asset account
                if(cObj("asset-dispose-success") != undefined){
                    get_asset_data(valObj("asset-id"));
                    cObj("cancel_asset_disposal").click();
                    cObj("dispose_value").value = 0;
                }

                // hide error
                setTimeout(() => {
                    cObj("asset-dispose-error").innerHTML = "";
                }, 3000);
                view_asset_account(valObj("asset-id"));
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("recover_assets_btn").onclick = function () {
    cObj("recover_asset_confirm").classList.toggle("hide");
}

cObj("cancel_asset_recovery").onclick = function () {
    cObj("recover_asset_confirm").classList.add("hide");
}

// dispose asset
cObj("recover_asset_confirm_btn").onclick = function () {
    var datapass = "?recover_asset=true&asset_id="+valObj("asset-id");
    sendData2("GET","finance/financial.php",datapass,cObj("asset-recovery-error"),cObj("recover_asset_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("recover_asset_loader").classList.contains("hide")) {
                // display asset account
                if (cObj("asset-recover-success") != undefined) {
                    get_asset_data(valObj("asset-id"));
                    cObj("cancel_asset_recovery").click();
                }
                setTimeout(() => {
                    cObj("asset-recovery-error").innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

// get the asset data function
function get_asset_data(asset_id) {
    var datapass = "?asset_data=true&asset_id="+asset_id;
    sendData2("GET","finance/financial.php",datapass,cObj("asset_data_holder"),cObj("asset_data_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("asset_data_loader").classList.contains("hide")) {
                // display asset account
                cObj("assets-values-"+asset_id).value = cObj("asset_data_holder").innerText;
                cObj("view-assets-"+valObj("asset-id")).click();
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function display_payment_requests(page = 1){
    var datapass = "get_payment_requests="+page;
    sendDataPost("POST","ajax/finance/financial.php",datapass,cObj("payment_request_holder"),cObj("payment_request_table_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("payment_request_table_loader").classList.contains("hide")) {
                var data_to_display = hasJsonStructure(cObj("payment_request_holder").innerText) ? JSON.parse(cObj("payment_request_holder").innerText) : {"payment_requests" : [],"total_pages" : 0};
                display_all_payment_requests(data_to_display.pay_requests);
                cObj("payment_requests_index").innerText = "Page "+page+" of "+data_to_display.total_pages;
                cObj("payment_request_page").value = page;
                cObj("maximum_payment_request").value = data_to_display.total_pages;
                stopInterval(ids);
            }

            // navigation buttons
            // cObj("next_supplier_page").addEventListener("click",navigate_right);
            // cObj("previous_supplier_page").addEventListener("click",navigate_left);
        }, 100);
    }, 200);
}

function display_all_payment_requests(data_to_display) {
    var data_in_display = "";
    if (data_to_display.length > 0) {
        data_in_display = "<table id='payment_application_table' class='table'><tr><th>No.</th><th>Payment for</th><th>Expense Categories</th><th>Expense Amount.</th><th>Date Paid</th><th>Document Number</th><th>Action</th></tr>";
        for (let index = 0; index < data_to_display.length; index++) {
            const element = data_to_display[index];
            var flag = element.table_name == "running_expense" ? " <small class='badge bg-success' title='Running Expenses'>E</small>" : " <small class='badge bg-warning' title='Suppliers'>S</small>";
            data_in_display+="<tr><td><input type='hidden' id='payment_request_"+element.payment_id+"' value='"+JSON.stringify(element)+"'>"+(index+1)+" "+flag+"</td><td>"+element.exp_name+"</td><td>"+element.exp_category+"</td><td>"+element.amount+"</td><td>"+element.date_paid+"</td><td>"+element.document_number+"</td><td><span style='font-size:12px;' class='link view_payment_requests' id='view_pay_req_"+element.payment_id+"'><i class='fa fa-eye'></i> View </span> <br> <span style='font-size:12px;' class='link accept_pay_request' id='accept_"+element.payment_id+"'><i class='fa fa-check'></i> Accept </span> <br> <span style='font-size:12px; color:red;' class='link decline_pay_request' id='decline_"+element.payment_id+"'><b>X</b> Decline </span></td></tr>";
        }
        data_in_display+="</table>";
    }else{
        data_in_display="<p class='text-danger'>No Payment requests data found!</p>";
    }

    // data to display
    cObj("payment_request_tables").innerHTML = data_in_display;

    // add an event listener
    var view_payment_requests = document.getElementsByClassName("view_payment_requests");
    for (let index = 0; index < view_payment_requests.length; index++) {
        const element = view_payment_requests[index];
        element.addEventListener("click",view_payment_request);
    }

    var accept_pay_request = document.getElementsByClassName("accept_pay_request");
    for (let index = 0; index < accept_pay_request.length; index++) {
        const element = accept_pay_request[index];
        element.addEventListener("click", accept_pay_requests);
    }

    var decline_pay_request = document.getElementsByClassName("decline_pay_request");
    for (let index = 0; index < decline_pay_request.length; index++) {
        const element = decline_pay_request[index];
        element.addEventListener("click", decline_pay_requests);
    }

    // search keyword
    cObj("search_payment_approvals").addEventListener("keyup",search_payment_approvals);
}

function view_payment_request() {
    var id = this.id.substr("view_pay_req_".length,this.id.length);
    cObj("view_payment_request").classList.remove("hide");

    // fill data
    if (hasJsonStructure(valObj("payment_request_"+id))) {
        var payment_request = JSON.parse(valObj("payment_request_"+id));

        // get the data 
        cObj("payment_request_name").value = payment_request.exp_name;
        cObj("PR_payment_for").value = payment_request.exp_category;
        cObj("PR_expense_categories").value = payment_request.amount;
        cObj("PR_expense_amount").value = payment_request.date_paid;
        cObj("PR_date_paid").value = payment_request.document_number;
        cObj("PR_expense_description").value = payment_request.payment_description;
        cObj("payment_req_id").value = payment_request.payment_id;
        cObj("payment_req_type").value = payment_request.table_name;
    }
}

cObj("cancel_accept_payment_request").onclick = function () {
    cObj("confirm_payment_request_window").classList.add("hide");
}

function search_payment_approvals() {
    var keyword = this.value.toLowerCase();
    var row = cObj("payment_application_table").children;

    // new row
    var new_row = row;
    for (let index = 0; index < 4; index++) {
        new_row = new_row.length > 0 ? (new_row[0].tagName == "TR" ? new_row : new_row[0].children) : [];
        if (new_row.length > 0) {
            if (new_row[0].tagName == "TR") {
                break;
            }else{
                new_row = new_row[0].children;
            }
        }else{
            new_row = [];
        }
    }

    // row length
    row = new_row;
    for (let index = 1; index < row.length; index++) {
        const element = row[index];
        var columns = element.children;

        // check if in this row the column has what the keyword shows
        var present = 0;
        for (let index = 1; index < (columns.length-1); index++) {
            const elem = columns[index];
            if (elem.innerText.toLowerCase().includes(keyword)) {
                present++;
            }
        }

        // if the keyword is present show the row
        if (present > 0) {
            element.classList.remove("hide");
        }else{
            element.classList.add("hide");
        }
    }
}

cObj("back_to_expenses_view").onclick = function () {
    cObj("expenses_btn").click();
}

cObj("close_view_payment_request").onclick = function () {
    cObj("view_payment_request").classList.add("hide");
    cObj("confirm_payment_request_window").classList.add("hide");
}

cObj("close_payment_approvale").onclick = function () {
    cObj("view_payment_request").classList.add("hide");
    cObj("confirm_payment_request_window").classList.add("hide");
}

cObj("accept_payment_approvall").onclick = function () {
    cObj("payment_request_id").value = cObj("payment_req_id").value;
    cObj("payment_request_type").value = cObj("payment_req_type").value;
    cObj("show_payment_req_confirmation").classList.remove("hide");
}

cObj("confirm_accept_payment_request").onclick = function () {
    send_payment_request(valObj("payment_req_id"), valObj("payment_req_type"), "payment_request_success", "payment_request_table_loader")
}

function send_payment_request(id, payment_type, message, loader, comment = "", request_status = 1) {
    var datapass = "send_payment_request=true&payment_id="+id+"&payment_type="+payment_type+"&comment="+comment+"&request_status="+request_status;
    sendDataPost("POST","ajax/finance/financial.php",datapass, cObj(message),cObj(loader));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj(loader).classList.contains("hide")) {
                // display all payment requests
                display_payment_requests();

                // close payment approval
                cObj("close_payment_approvale").click();

                // close the decline window
                cObj("close_payment_decline_window").click();

                // hide the confirmation window
                cObj("show_payment_req_confirmation").classList.add("hide");
                setTimeout(() => {
                    cObj(message).innerHTML = "";
                }, 3000);
                stopInterval(ids);
            }

            // navigation buttons
            // cObj("next_supplier_page").addEventListener("click",navigate_right);
            // cObj("previous_supplier_page").addEventListener("click",navigate_left);
        }, 100);
    }, 200);
}

function accept_pay_requests() {
    var id = this.id.substr("accept_".length,this.id.length);

    // fill data
    if (hasJsonStructure(valObj("payment_request_"+id))) {
        cObj("show_payment_req_confirmation").classList.remove("hide");
        var payment_request = JSON.parse(valObj("payment_request_"+id));

        // get the data
        cObj("payment_request_id").value = payment_request.payment_id;
        cObj("payment_request_type").value = payment_request.table_name;
    }
}

function decline_pay_requests() {
    var id = this.id.substr("decline_".length,this.id.length);

    // fill data
    if (hasJsonStructure(valObj("payment_request_"+id))) {
        cObj("show_payment_decline_window").classList.remove("hide");
        var payment_request = JSON.parse(valObj("payment_request_"+id));

        // get the data 
        cObj("payment_request_id_decline").value = payment_request.payment_id;
        cObj("payment_request_type_decline").value = payment_request.table_name;
    }
}

cObj("close_confirm_payment_request").onclick = function () {
    cObj("show_payment_req_confirmation").classList.add("hide");
}

cObj("cancel_payment_request_in").onclick = function () {
    cObj("show_payment_req_confirmation").classList.add("hide");
}
cObj("confirm_payment_request_in").onclick = function () {
    send_payment_request(valObj("payment_request_id"), valObj("payment_request_type"), "payment_request_success", "payment_request_table_loader")
}

cObj("cancel_payment_request_decline").onclick = function () {
    cObj("show_payment_decline_window").classList.add("hide");
    cObj("payment_decline_description").value = "";
}

cObj("close_payment_decline_window").onclick = function () {
    cObj("show_payment_decline_window").classList.add("hide");
    cObj("payment_decline_description").value = "";
}

cObj("confirm_payment_request_decline").onclick = function () {
    var payment_description = valObj("payment_decline_description");
    send_payment_request(valObj("payment_request_id_decline"), valObj("payment_request_type_decline"), "payment_request_success", "payment_request_table_loader", payment_description, "2");
}

cObj("reject_payment_approvale").onclick = function () {
    cObj("payment_request_id_decline").value = cObj("payment_req_id").value;
    cObj("payment_request_type_decline").value = cObj("payment_req_type").value;
    cObj("show_payment_decline_window").classList.remove("hide");
}

cObj("expense_type").onchange = function () {
    var capital_expenses = document.getElementsByClassName("capital_expenses");
    var operational_expense = document.getElementsByClassName("operational_expense");
    if (this.value == "capital") {
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.remove("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.add("hide");
        }
    }else if (this.value == "operation") {
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.add("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.remove("hide");
        }
    }else{
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.add("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.add("hide");
        }
    }
}

cObj("expense_type_edit").onchange = function () {
    var capital_expenses = document.getElementsByClassName("capital_expenses");
    var operational_expense = document.getElementsByClassName("operational_expense");
    if (this.value == "capital") {
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.remove("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.add("hide");
        }
    }else if (this.value == "operation") {
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.add("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.remove("hide");
        }
    }else{
        for (let index = 0; index < capital_expenses.length; index++) {
            const element = capital_expenses[index];
            element.classList.add("hide");
        }
        
        for (let index = 0; index < operational_expense.length; index++) {
            const element = operational_expense[index];
            element.classList.add("hide");
        }
    }
}