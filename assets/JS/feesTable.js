
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
// get the data from the database
// var student_data = data;
// get an object by id 
function cObj(id) {
    return document.getElementById(id);
}

function stopInterval(id) {
    clearInterval(id);
}

var rowsColStudents_fees = [];
var rowsNCols_original_fees = [];
var pagecountTransaction = 0; //this are the number of pages for transaction
var pagecounttrans = 1; //the current page the user is
var startpage_fees = 0; // this is where we start counting the page number

// load the user data
function getFeesNDisplay(student_data) {
    rowsColStudents_fees = [];
    rowsNCols_original_fees = [];
    pagecountTransaction = 0; //this are the number of pages for transaction
    pagecounttrans = 1; //the current page the user is
    startpage_fees = 0; // this is where we start counting the page number
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
            col.push(element['stud_admin']);
            col.push(element['amount']);
            col.push(element['date_of_transaction']);
            col.push(element['student_name']);
            col.push(element['mode_of_pay']);
            col.push(element['payment_for']);
            col.push(element['amount_sort']);
            col.push(element['trans_date_sort']);
            col.push((index+1));
            col.push(element['balance']);
            col.push(element['transaction_code']);
            col.push(element['time_of_transaction_1']);
            col.push(element['date_of_transaction_1']);
            col.push(element['transaction_id']);
            col.push(element['support_document']);
            // var col = element.split(":");
            rowsColStudents_fees.push(col);
        }
        rowsNCols_original_fees = rowsColStudents_fees;
        cObj("tot_records_fees").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);

        //show the number of pages for each record
        var counted = rows.length / 50;
        pagecountTransaction = Math.ceil(counted);
        setReprintListener();

    } else {
        cObj("transDataReciever_fees").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_fees").classList.add("invisible");
    }
}

function reprintClick() {
    var id_index = this.id.substr(8);

    var student_name = cObj("students_name_fin"+id_index).innerText;
    var student_admission_no = cObj("adms_fin"+id_index).innerText;
    var amount_paid_by_student = cObj("fees_paid"+id_index).innerText;
    var new_student_balance = cObj("new_balance"+id_index).value;
    var mode_of_payments = cObj("mod"+id_index).innerText;
    var payments_for = cObj("purpose"+id_index).innerText;
    var transaction_code = cObj("transaction_code"+id_index).value;

    cObj("check-parents-sms").disabled = true;
    cObj("send_sms_dsiclaimer").innerText = "Disabled";
    cObj("switch_confirmation").innerText = "Re-print Receipts";
    cObj("title_confirmation").classList.add("hide");
    

    // set the values for the reciept printing
    cObj("student_admission_no").value = student_admission_no;
    cObj("amount_paid_by_student").value = amount_paid_by_student;
    cObj("new_student_balance").value = new_student_balance;
    cObj("mode_of_payments").value = mode_of_payments;
    cObj("payments_for").value = payments_for;
    cObj("students_names").value = student_name;
    cObj("reprint").value = "true";
    cObj("transaction_codes").value = transaction_code;
    cObj("masiku").value = valObj("masiku"+id_index);
    cObj("masaa").value = valObj("masaa"+id_index);
    cObj("last_receipt_id_take").value = valObj("trans_id"+id_index);

    // display data
    cObj("confirmpayments").classList.remove("hide");
}

function setReprintListener() {
    var re_print = document.getElementsByClassName("re-print");
    for (let index = 0; index < re_print.length; index++) {
        const element = re_print[index];
        element.addEventListener("click",reprintClick);
    }

    var delete_trans = document.getElementsByClassName("delete_trans");
    for (let index = 0; index < delete_trans.length; index++) {
        const element = delete_trans[index];
        element.addEventListener("click",deleteTransactions);
    }

    var view_dets = document.getElementsByClassName("view_dets");
    for (let index = 0; index < view_dets.length; index++) {
        const element = view_dets[index];
        element.addEventListener("click",viewTransactions);
    }
}

function viewTransactions() {
    var this_id = this.id.substr(10);
    cObj("payment_details_window").classList.remove("hide");
    // get the data of the transaction
    var data = valObj("support_docs_"+this_id);
    // console.log(data);
    if (hasJsonStructure(data)) {
        data = JSON.parse(data);
        // console.log(data);
        cObj("payment_description_2").innerText = data[3];
        cObj("payment_description_3").innerText = data[12]+" : "+data[11];
        cObj("payment_description_4").innerText = data[4];
        cObj("payment_description_5").innerText = "Kes "+data[1];
        cObj("payment_description_6").innerText = data[9];
        cObj("payment_description_7").innerText = data[5];

        // has json structure
        if (hasJsonStructure(data[14])) {
            var supporting_docs_values = (data[14]);
            displaySupportingDocs(supporting_docs_values);
        }else{
            cObj("supporting_documents_list_holder").innerHTML = "<p class='text-secondary p-1 border border-secondary rounded p-1'>No Supporting Documents Present</p>";
        }
    }else{
        cObj("supporting_documents_list_holder").innerHTML = "<p class='text-secondary p-1 border border-secondary rounded p-1'>No Supporting Documents Present</p>";
    }
}

cObj("confirm_delete_trans_no").onclick = function () {
    cObj("confirm_transaction_delete").classList.add("hide");
}

cObj("confirm_delete_trans_yes").onclick = function () {
    var err = checkBlank("transaction_pay_id");
    if (err == 0) {
        var datapass = "?delete_transaction=true&transactions_id="+valObj("transaction_pay_id");
        sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("delete_pay_err_handlers"), cObj("load_delete_payments"));
        setTimeout(() => {
            var timeout = 0;
            var idms = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idms);
                }
                if (cObj("load_delete_payments").classList.contains("hide")) {
                    // get the arrays
                    cObj("confirm_delete_trans_no").click();
                    cObj("searchtransaction").click();
                    cObj("delete_pay_err_handlers").innerHTML = "";
                    stopInterval(idms);
                }
            }, 100);
        }, 100);
    }else{
        cObj("delete_pay_err_handlers").innerHTML = "<p class='text-danger'>An error has occured try again.</p>";
        setTimeout(() => {
            cObj("delete_pay_err_handlers").innerHTML = "";
        }, 4000);
    }
}
function deleteTransactions() {
    var this_id = this.id.substr(12);
    cObj("transaction_owner").innerText = cObj("students_name_fin"+this_id).innerText;
    cObj("date_of_payments").innerHTML = valObj("masiku"+this_id)+" @ "+valObj("masaa"+this_id);
    cObj("transaction_pay_id").value = valObj("trans_id"+this_id);
    cObj("amounts_paid_trans").innerHTML = cObj("fees_paid"+this_id).innerText;
    cObj("payments_for_info").innerHTML = cObj("purpose"+this_id).innerText;
    cObj("confirm_transaction_delete").classList.remove("hide");
}

function displayRecord_fees(start, finish, arrays) {
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th title='Sort all' id='sortall_th'># <span id='sortall'><i class='fas fa-caret-down'></i></span></th><th id='sortadmno_th' title='Sort by Reg No'>Student Name {Adm no}<span id='sortadmno'><i class='fas fa-caret-down'></i></span></th><th  id='sortfeeamount_th' title='Sort by Amount'>Paid Amount <span id='sortfeeamount'><i class='fas fa-caret-down'></i></span></th><th  title='Sort by date' id='sortdate_th'>D.O.P <span id='sortdate'><i class='fas fa-caret-down'></i></span></th><th>M.O.P</th><th>Purpose</th><th>Option</th></tr></thead><tbody>";
    if(finish < total) {
        fins = finish;
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < finish; index++) {
            tableData += "<tr><input type='hidden' id='support_docs_"+index+"' value='"+(JSON.stringify(arrays[index]))+"'><input type='hidden' id='trans_id"+index+"' value='"+arrays[index][13]+"'><input type='hidden' id='masiku"+index+"' value='"+arrays[index][12]+"'><input type='hidden' id='masaa"+index+"' value='"+arrays[index][11]+"'><input type='hidden' id='new_balance"+index+"' value='"+arrays[index][9]+"'><input type='hidden' id='transaction_code"+index+"' value='"+arrays[index][10]+"'><td>"+arrays[index][8]+"</td><td><small class='text-sm' id='students_name_fin"+index+"'>"+arrays[index][3]+"</small> {<span id='adms_fin"+index+"'>"+arrays[index][0]+"</span>}</td><td id='fees_paid"+index+"'>Kes "+comma3(arrays[index][1])+"</td><td id='d_o_p"+index+"'>"+arrays[index][2]+"</td><td id='mod"+index+"'>"+arrays[index][4]+"</td><td id='purpose"+index+"'>"+arrays[index][5]+"</td><td><span class='link re-print' id='re-print"+index+"'><i class='fas fa-print'></i> Print</span><span class='link delete_trans mx-1' id='delete_trans"+index+"'><i class='fas fa-trash'></i> Delete</span><span class='link view_dets mx-1' id='view_dets_"+index+"'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
    }else{
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < total; index++) {
            tableData += "<tr><input type='hidden' id='support_docs_"+index+"' value='"+(JSON.stringify(arrays[index]))+"'><input type='hidden' id='trans_id"+index+"' value='"+arrays[index][13]+"'><input type='hidden' id='masiku"+index+"' value='"+arrays[index][12]+"'><input type='hidden' id='masaa"+index+"' value='"+arrays[index][11]+"'><input type='hidden' id='new_balance"+index+"' value='"+arrays[index][9]+"'><input type='hidden' id='transaction_code"+index+"' value='"+arrays[index][10]+"'><td>"+arrays[index][8]+"</td><td><small class='text-sm' id='students_name_fin"+index+"'>"+arrays[index][3]+"</small> {<span id='adms_fin"+index+"'>"+arrays[index][0]+"</span>}</td><td id='fees_paid"+index+"'>Kes "+comma3(arrays[index][1])+"</td><td id='d_o_p"+index+"'>"+arrays[index][2]+"</td><td id='mod"+index+"'>"+arrays[index][4]+"</td><td id='purpose"+index+"'>"+arrays[index][5]+"</td><td><span class='link re-print' id='re-print"+index+"'><i class='fas fa-print'></i> Print</span><span class='link delete_trans mx-1' id='delete_trans"+index+"'><i class='fas fa-trash'></i> Delete</span><span class='link view_dets mx-1' id='view_dets_"+index+"'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
        fins = total;
    }

    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_fees").innerText = start + 1;
    cObj("finishNo_fees").innerText = fins;
    //set the page number
    cObj("pagenumNav_fees").innerText = pagecounttrans;
    // set tool tip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    setTimeout(() => {
        sortTable();
    }, 500);
    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_fees").onclick = function() {
    console.log(pagecounttrans+" "+pagecountTransaction);
        if (pagecounttrans < pagecountTransaction) { // if the current page is less than the total number of pages add a page to go to the next page
            startpage_fees += 50;
            pagecounttrans++;
            var endpage = startpage_fees + 50;
            cObj("transDataReciever_fees").innerHTML = displayRecord_fees(startpage_fees, endpage, rowsColStudents_fees);
            setReprintListener();
        } else {
            pagecounttrans = pagecountTransaction;
        }
    }
    // end of next records
cObj("toprevNac_fees").onclick = function() {
    if (pagecounttrans > 1) {
        pagecounttrans--;
        startpage_fees -= 50;
        var endpage = startpage_fees + 50;
        cObj("transDataReciever_fees").innerHTML = displayRecord_fees(startpage_fees, endpage, rowsColStudents_fees);
        setReprintListener();
    }
}
cObj("tofirstNav_fees").onclick = function() {
    if (pagecountTransaction > 0) {
        pagecounttrans = 1;
        startpage_fees = 0;
        var endpage = startpage_fees + 50;
        cObj("transDataReciever_fees").innerHTML = displayRecord_fees(startpage_fees, endpage, rowsColStudents_fees);
        setReprintListener();
    }
}
cObj("tolastNav_fees").onclick = function() {
    if (pagecountTransaction > 0) {
        pagecounttrans = pagecountTransaction;
        startpage_fees = (pagecounttrans * 50) - 50;
        var endpage = startpage_fees + 50;
        cObj("transDataReciever_fees").innerHTML = displayRecord_fees(startpage_fees, endpage, rowsColStudents_fees);
        setReprintListener();
    }
}

// seacrh keyword at the table
cObj("searchkey_fees").onkeyup = function() {
        check_name_fees(this.value);
        console.log(this.value);
    }
    //create a function to check if the array has the keyword being searched for
function check_name_fees(keyword) {
    rowsColStudents_fees = rowsNCols_original_fees;
    pagecounttrans = 1;
    if (keyword.length > 0) {
        cObj("tablefooter").classList.add("invisible");
    } else {
        cObj("tablefooter").classList.remove("invisible");
    }
    // console.log(keyword.toLowerCase());
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_fees.length; index++) {
        const element = rowsColStudents_fees[index];
        //column break
        var present = 0;
        if (element[0].toString().toLowerCase().includes(keylower) || element[0].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[1].toString().toLowerCase().includes(keylower) || element[1].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
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
        if (element[6].toString().includes(keyword)) {
            present++;
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        rowsColStudents_fees = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction = Math.ceil(counted);
        cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsNcol2);
        setReprintListener();
        cObj("tot_records_fees").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_fees").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_fees").innerText = 0;
        cObj("finishNo_fees").innerText = 0;
        cObj("tot_records_fees").innerText = 0;
        pagecountTransaction = 1;
    }
}

// sort in ascending or descending order
var sortallstatus = 1;
var sortadmnostatus = 1;
var sortfeeamountstatus = 1;
function sortTable() {
    if (cObj("sortall_th") != null) {
        cObj("sortall_th").addEventListener("click",function () {
            // sort all in ascending order
            if (sortallstatus == 0) {
                // asc up to down
                sortallstatus = 1;
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortDesc(rowsColStudents_fees,8);
                var counted = rowsColStudents_fees.length / 50;
                pagecountTransaction = Math.ceil(counted);
                // console.log(rowsColStudents_fees);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortall").innerHTML = "- <i class='fas fa-caret-down'></i>";
            }else{
                // desc down to up
                sortallstatus = 0;
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortAsc(rowsColStudents_fees,8);
                var counted = rowsColStudents_fees.length / 50;
                // console.log(rowsColStudents_fees);
                pagecountTransaction = Math.ceil(counted);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortall").innerHTML = "- <i class='fas fa-caret-up'></i>";
            }
            setReprintListener();
        });
    }
    if (cObj("sortadmno_th") != null) {
        cObj("sortadmno_th").addEventListener("click",function () {
            // sort all in ascending order
            if (sortadmnostatus == 0) {
                // asc up to down
                sortadmnostatus = 1;
                // console.log(cObj("sortadmno").innerHTML);
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortDesc(rowsColStudents_fees,0);
                var counted = rowsColStudents_fees.length / 50;
                pagecountTransaction = Math.ceil(counted);
                // console.log(rowsColStudents_fees);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortadmno").innerHTML = "- <i class='fas fa-caret-down'></i>";
            }else{
                // desc down to up
                sortadmnostatus = 0;
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortAsc(rowsColStudents_fees,0);
                var counted = rowsColStudents_fees.length / 50;
                // console.log(rowsColStudents_fees);
                pagecountTransaction = Math.ceil(counted);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortadmno").innerHTML = "- <i class='fas fa-caret-up'></i>";
            }
            setReprintListener();
        });
    }
    if (cObj("sortfeeamount_th") != null) {
        cObj("sortfeeamount_th").addEventListener("click",function () {
            // sort all in ascending order
            if (sortfeeamountstatus == 0) {
                // asc up to down
                sortfeeamountstatus = 1;
                // console.log(cObj("sortfeeamount").innerHTML);
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortDesc(rowsColStudents_fees,6);
                var counted = rowsColStudents_fees.length / 50;
                pagecountTransaction = Math.ceil(counted);
                // console.log(rowsColStudents_fees);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortfeeamount").innerHTML = "- <i class='fas fa-caret-down'></i>";
            }else{
                // desc down to up
                sortfeeamountstatus = 0;
                //WITH FIRST COLUMN
                rowsColStudents_fees = rowsNCols_original_fees;
                rowsColStudents_fees = sortAsc(rowsColStudents_fees,6);
                var counted = rowsColStudents_fees.length / 50;
                // console.log(rowsColStudents_fees);
                pagecountTransaction = Math.ceil(counted);
                cObj("transDataReciever_fees").innerHTML = displayRecord_fees(0, 50, rowsColStudents_fees);
                cObj("tot_records_fees").innerText = rowsColStudents_fees.length;
                cObj("sortfeeamount").innerHTML = "- <i class='fas fa-caret-up'></i>";
            }
            setReprintListener();
        });
    }
    if (cObj("sortdate_th") != null) {
        cObj("sortdate_th").addEventListener("click",function () {
            cObj("sortall_th").click();
        });
    }
}
function sortDesc(arrays,index){
    arrays = arrays.sort(sortFunction);
    function sortFunction(a, b) {
        if (a[index] === b[index]) {
            return 0;
        }
        else {
            return (a[index] > b[index]) ? -1 : 1;
        }
    }
    return arrays;
}
function sortAsc(arrays,index){
    arrays = arrays.sort(sortFunction);
    function sortFunction(a, b) {
        if (a[index] === b[index]) {
            return 0;
        }
        else {
            return (a[index] < b[index]) ? -1 : 1;
        }
    }
    return arrays;
}
/**\
 * Start of the credit note table display
 * anything else below or above this section
 */
var rowsColStudents_credit_note = [];
var rowsNCols_original_credit_note = [];
var pagecountTransaction_credit_note = 0; //this are the number of pages for transaction
var pagecounttrans_credit_note = 1; //the current page the user is
var startpage_credit_note = 0; // this is where we start counting the page number

// load the user data
function getCreditNoteDisplay(student_data) {
    rowsColStudents_credit_note = [];
    rowsNCols_original_credit_note = [];
    pagecountTransaction_credit_note = 0; //this are the number of pages for transaction
    pagecounttrans_credit_note = 1; //the current page the user is
    startpage_credit_note = 0; // this is where we start counting the page number
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
            col.push(index+1);
            col.push(element['id']);
            col.push(element['amount']);
            col.push(element['month']);
            col.push(element['assigned']);
            col.push(element['date_registered']);
            col.push(element['staff_id']);
            col.push(element['status']);
            // var col = element.split(":");
            rowsColStudents_credit_note.push(col);
        }
        rowsNCols_original_credit_note = rowsColStudents_credit_note;
        cObj("tablefooter_credit_note").classList.remove("invisible");
        // console.log(rowsNCols_original_credit_note);
        cObj("tot_records_credit_note").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(0, 50, rowsColStudents_credit_note);
        view_credit_note();

        //show the number of pages for each record
        var counted = rows.length / 50;
        pagecountTransaction_credit_note = Math.ceil(counted);

    } else {
        cObj("transDataReciever_credit_note").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_credit_note").classList.add("invisible");
    }
}

function displayRecord_credit_note(start, finish, arrays) {
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th>#</th><th>Amount</th><th>Date Registered</th><th>Month Assigned</th><th>Staff Credited</th><th>Student Assigned</th><th>Options</th></tr></thead><tbody>";
    if(finish < total) {
        fins = finish;
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < finish; index++) {
            tableData += "<tr><input type='hidden' id='credit_notes_data"+arrays[index][1]+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][0]+"</td><td>Kes "+comma3(arrays[index][2])+"</td><td>"+arrays[index][5]+"</td><td>"+arrays[index][3]+"</td><td>"+arrays[index][6]+"</td><td>"+arrays[index][4]+"</td><td><span class='link view_credit_note' id='view_credit_note"+arrays[index][1]+"'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
    }else{
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < total; index++) {
            tableData += "<tr><input type='hidden' id='credit_notes_data"+arrays[index][1]+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][0]+"</td><td>Kes "+comma3(arrays[index][2])+"</td><td>"+arrays[index][5]+"</td><td>"+arrays[index][3]+"</td><td>"+arrays[index][6]+"</td><td>"+arrays[index][4]+"</td><td><span class='link view_credit_note' id='view_credit_note"+arrays[index][1]+"'><i class='fas fa-eye'></i> View</span></td></tr>";
            counter++;
        }
        fins = total;
    }

    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_credit_note").innerText = start + 1;
    cObj("finishNo_credit_note").innerText = fins;
    //set the page number
    cObj("pagenumNav_credit_note").innerText = pagecounttrans_credit_note;
    return tableData;
}

//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_credit_note").onclick = function() {
    console.log(pagecounttrans_credit_note+" "+pagecountTransaction_credit_note);
        if (pagecounttrans_credit_note < pagecountTransaction_credit_note) { // if the current page is less than the total number of pages add a page to go to the next page
            startpage_credit_note_fees += 50;
            pagecounttrans_credit_note++;
            var endpage = startpage_credit_note_fees + 50;
            cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(startpage_credit_note_fees, endpage, rowsColStudents_credit_note);
            view_credit_note();
        } else {
            pagecounttrans_credit_note = pagecountTransaction_credit_note;
        }
    }
    // end of next records
cObj("toprevNac_credit_note").onclick = function() {
    if (pagecounttrans_credit_note > 1) {
        pagecounttrans_credit_note--;
        startpage_credit_note_fees -= 50;
        var endpage = startpage_credit_note_fees + 50;
        cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(startpage_credit_note_fees, endpage, rowsColStudents_credit_note);
        view_credit_note();
    }
}
cObj("tofirstNav_credit_note").onclick = function() {
    if (pagecountTransaction_credit_note > 0) {
        pagecounttrans_credit_note = 1;
        startpage_credit_note_fees = 0;
        var endpage = startpage_credit_note_fees + 50;
        cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(startpage_credit_note_fees, endpage, rowsColStudents_credit_note);
        view_credit_note();
    }
}
cObj("tolastNav_credit_note").onclick = function() {
    if (pagecountTransaction_credit_note > 0) {
        pagecounttrans_credit_note = pagecountTransaction_credit_note;
        startpage_credit_note_fees = (pagecounttrans_credit_note * 50) - 50;
        var endpage = startpage_credit_note_fees + 50;
        cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(startpage_credit_note_fees, endpage, rowsColStudents_credit_note);
        view_credit_note();
    }
}

// seacrh keyword at the table
cObj("searchkey_credit_note").onkeyup = function() {
        check_name_credit_note(this.value);
}
//create a function to check if the array has the keyword being searched for
function check_name_credit_note(keyword) {
    rowsColStudents_credit_note = rowsNCols_original_credit_note;
    pagecounttrans_credit_note = 1;
    if (keyword.length > 0) {
        cObj("tablefooter_credit_note").classList.add("invisible");
    } else {
        cObj("tablefooter_credit_note").classList.remove("invisible");
    }
    // console.log(rowsColStudents_credit_note);
    var rowsNcol2 = [];
    var keylower = keyword.toLowerCase();
    var keyUpper = keyword.toUpperCase();
    //row break
    for (let index = 0; index < rowsColStudents_credit_note.length; index++) {
        const element = rowsColStudents_credit_note[index];
        //column break
        var present = 0;
        if (element[1].toString().toLowerCase().includes(keylower) || element[1].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[2].toString().toLowerCase().includes(keylower) || element[2].toString().toUpperCase().includes(keyUpper)) {
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
        if (element[6].toString().toLowerCase().includes(keylower) || element[6].toString().toUpperCase().includes(keyUpper)) {
            present++;
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        rowsColStudents_credit_note = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction_credit_note = Math.ceil(counted);
        cObj("transDataReciever_credit_note").innerHTML = displayRecord_credit_note(0, 50, rowsNcol2);
        view_credit_note();
        cObj("tot_records_credit_note").innerText = rowsNcol2.length;
    } else {
        cObj("transDataReciever_credit_note").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        cObj("tablefooter_credit_note").classList.add("invisible");
        cObj("startNo_credit_note").innerText = 0;
        cObj("finishNo_credit_note").innerText = 0;
        cObj("tot_records_credit_note").innerText = 0;
        pagecountTransaction_credit_note = 1;
    }
}

// get
function getCreditNote() {
    var datapass = "?get_credit_notes=true";
    sendData2("GET","../ajax/finance/financial.php",datapass,cObj("store_credit_notes"),cObj("credit_notes_loader"));
    setTimeout(() => {
        var timeout = 0;
        var idms = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idms);
            }
            if (cObj("credit_notes_loader").classList.contains("hide")) {
                var credit_note_data = cObj("store_credit_notes").innerText;
                if (hasJsonStructure(credit_note_data)) {
                    credit_note_data = JSON.parse(credit_note_data);
                    getCreditNoteDisplay(credit_note_data);
                } else {
                    cObj("transDataReciever_credit_note").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! No data was not found</p>";
                    cObj("tablefooter_credit_note").classList.add("invisible");
                    cObj("startNo_credit_note").innerText = 0;
                    cObj("finishNo_credit_note").innerText = 0;
                    cObj("tot_records_credit_note").innerText = 0;
                    pagecountTransaction_credit_note = 1;
                    stopInterval(idms);
                }
                stopInterval(idms);
            }
        }, 100);
    }, 100);
}

function view_credit_note() {
    var view_credit_note = document.getElementsByClassName("view_credit_note");
    for (let index = 0; index < view_credit_note.length; index++) {
        const element = view_credit_note[index];
        element.addEventListener("click",viewCreditNote);
    }
}

function viewCreditNote() {
    cObj("credit_note_window").classList.add("hide");
    cObj("credit_note_window2").classList.remove("hide");
    getStudentNameAdmno();
    var credit_notes_data = valObj("credit_notes_data"+this.id.substr(16));
    if (hasJsonStructure(credit_notes_data)) {
        credit_notes_data = JSON.parse(credit_notes_data);
        cObj("staff_credited_credit").innerText = credit_notes_data[6];
        cObj("month_assigned_credit").innerText = credit_notes_data[3];
        cObj("date_credit_note_registered").innerText = credit_notes_data[5];
        cObj("student_assigned_credit").innerText = credit_notes_data[4];
        cObj("amount_credited").innerText = "Kes "+comma3(credit_notes_data[2]);
        cObj("amount_to_credit_cr_nt").innerText = credit_notes_data[2];
        cObj("credit_note_id").value = credit_notes_data[1];
        cObj("credit_note_status").innerHTML = credit_notes_data[7] == 1 ? "<span class='btn btn-success btn-sm'>Assigned!</span>" : "<span class='btn btn-warning btn-sm'>Un-Assigned!</span>";
        if (credit_notes_data[7] == 1) {
            cObj("assign_credit_note_window_2").classList.add("hide");
            cObj("un_assign_credi_note_window").classList.remove("hide");
        }else{
            cObj("assign_credit_note_window_2").classList.remove("hide");
            cObj("un_assign_credi_note_window").classList.add("hide");
        }
    }
}

cObj("back_to_credit_win").onclick = function () {
    cObj("credit_note_window").classList.remove("hide");
    cObj("credit_note_window2").classList.add("hide");
    getCreditNote();
}

cObj("search_student_credit_note").onclick = function () {
    var err = checkBlank("student_adm_credit_note");
    if (err == 0) {
        var datapass = "?findadmno=" + valObj("student_adm_credit_note")+"&class_id=credit_note_class_id&fees_bal_id=credit_note_fees_balance&student_name_cr=student_name_credit";
        sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("fees_list_result"), cObj("credit_notes_loader_win3"));
        setTimeout(() => {
            var timeout = 0;
            var idms = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idms);
                }
                if (cObj("credit_notes_loader_win3").classList.contains("hide")) {
                    // get the students payment details
                    cObj("stud_name_credit_note").innerText = cObj("student_name_credit").innerText;
                    var datapass = "?payfordetails=true&class_use="+valObj("credit_note_class_id")+"&student_admission="+valObj("student_adm_credit_note")+"&object_id=payment_for_credit_nt";
                    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("payment_option_credit_note"), cObj("credit_notes_loader_win3"));
                    stopInterval(idms);
                }
            }, 100);
        }, 100);
    }
}

cObj("select_time_set_opt_cr").onchange = function () {
    var this_data = this.value;
    if (this_data == "set") {
        cObj("set_time_cr_nt").classList.remove("hide");
    }else{
        cObj("set_time_cr_nt").classList.add("hide");
    }
}

cObj("assign_payment_credit_note").onclick = function () {
    var datapass = "?insertpayments=true&stuadmin="+valObj("student_adm_credit_note")+"&transcode=Credit Note&amount="+cObj("amount_to_credit_cr_nt").innerText+"&payfor="+valObj("payment_for_credit_nt")+"&modeofpay=Credit Note&balances="+valObj("credit_note_fees_balance")+"&send_sms=none&date_of_payments_fees="+valObj("date_of_payments_fees_cr_nt")+"&time_of_payment_fees="+valObj("time_of_payment_fees")+"&fees_payment_opt_holder="+valObj("select_time_set_opt_cr")+"&credit_id="+valObj("credit_note_id");
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("error_handled_credit_note"), cObj("credit_notes_loader_win3"));
    setTimeout(() => {
        var timeout = 0;
        var idms = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idms);
            }
            if (cObj("credit_notes_loader_win3").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("back_to_credit_win").click();
                }, 1000);
                // restore the window
                cObj("credit_record_from").reset();
                cObj("fees_list_result").innerHTML = "";
                // cObj("back_to_credit_win").click();
                getCreditNote();
                // setTimeout(() => {
                //     cObj("view_credit_note"+valObj("credit_note_id")).click();
                // }, 3000);
                
                cObj("success_message_cr").innerHTML = "<p class='border border-primary rounded p-1 text-success'> Credit is successfully assigned to "+cObj("student_assigned_credit").innerText+"</p>";
                setTimeout(() => {
                    cObj("success_message_cr").innerHTML = "";
                }, 5000);
                stopInterval(idms);
            }
        }, 100);
    }, 100);
}

// credit note table end
// credit note table end

cObj("un_assign_credit_note").onclick = function () {
    var get_credit_note_id = valObj("credit_note_id");
    if (checkBlank("credit_note_id") == 0) {
        // get the credit note and send
        var datapass = "?un_assign_data=true&un_assign_id="+get_credit_note_id;
        sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("unassign_credit_note_message_holder"), cObj("credit_notes_loader_win2"));
        setTimeout(() => {
            var timeout = 0;
            var idms = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idms);
                }
                if (cObj("credit_notes_loader_win2").classList.contains("hide")) {
                    // get the fees credit note.
                    setTimeout(() => {
                        cObj("unassign_credit_note_message_holder").innerHTML = "";
                    }, 2000);

                    cObj("back_to_credit_win").click();
                    stopInterval(idms);
                }
            }, 100);
        }, 100);
    }
}
