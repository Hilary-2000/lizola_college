
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

var rowsColStudents_expenses = [];
var rowsNCols_original_expenses = [];
var pagecountTransaction = 0; //this are the number of pages for transaction
var pagecounttrans = 1; //the current page the user is
var startpage_expenses = 0; // this is where we start counting the page number

// load the user data
function getExpensesNDisplay(student_data) {
    rowsColStudents_expenses = [];
    rowsNCols_original_expenses = [];
    pagecountTransaction = 0; //this are the number of pages for transaction
    pagecounttrans = 1; //the current page the user is
    startpage_expenses = 0; // this is where we start counting the page number
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
            col.push(element['exp_amount']);
            col.push(element['expense_name']);
            col.push(element['exp_name']);
            col.push(element['exp_quantity']);
            col.push(element['exp_time']);
            col.push(element['exp_unit_cost']);
            col.push(element['expense_date']);
            col.push(element['unit_name']);
            col.push((index+1));
            col.push(element['expid']);
            col.push(element['expense_categories']);
            col.push(element['date']);
            col.push(element['exp_category']);
            col.push(element['document_number']);
            col.push(element['expense_description']);
            col.push(element['exp_sub_category']);
<<<<<<< HEAD
            col.push(element['approval_status']);
            col.push(element['approval_comment']);
=======
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
            // var col = element.split(":");
            rowsColStudents_expenses.push(col);
        }
        rowsNCols_original_expenses = rowsColStudents_expenses;
        cObj("tot_records_expenses").innerText = rows.length;
        //create the display table
        //get the number of pages
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);

        //show the number of pages for each record
        var counted = rows.length / 50;
        pagecountTransaction = Math.ceil(counted);
        setEvents();

    } else {
        cObj("transDataReciever_expenses").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! No results found!</p>";
        cObj("tablefooter_expenses").classList.add("invisible");
    }
}

function setEvents() {
    var edit_expense = document.getElementsByClassName("edit_expense");
    for (let index = 0; index < edit_expense.length; index++) {
        const element = edit_expense[index];
        element.addEventListener("click",editExpense);
    }
}

function activate_amount() {
    var disable = true;
    if (cObj("expense_categories_value") != undefined && cObj("expense_categories_value") != null) {
<<<<<<< HEAD
        let expense_categories_value = valObj("expense_categories_value");
        console.log(expense_categories_value);
=======
        var expense_categories_value = valObj("expense_categories_value");
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
        if(hasJsonStructure(expense_categories_value)){
            expense_categories_value = JSON.parse(expense_categories_value);
            for (let index = 0; index < expense_categories_value.length; index++) {
                const element = expense_categories_value[index];
                if (element.expense_id == valObj("exp_cat")) {
                    if (element.running_balance >= this.value) {
                        disable = false;
                    }
                    break;
                }
            }
        }
    }
    if(disable){
        cObj("add_expenseed").classList.add("hide");
        cObj("error_message_expenses").innerHTML = "<p class='text-danger text-center'>You have exceeded your current running balance!</p>";
    }else{
        cObj("error_message_expenses").innerHTML = "";
        cObj("add_expenseed").classList.remove("hide");
    }
}
function editExpense() {
    var this_id = this.id.substr(12);
    var data = valObj("data_expenses"+this_id);
    if (hasJsonStructure(data)) {
        data = JSON.parse(data);
        cObj("edit_expense_name").value = data[2];
        cObj("total_unit_cost").value = data[0];
        cObj("expense_ids_in").value = data[9];
        cObj("edit_expense_record_date").value = data[11];
        cObj("edit_expense_description").value = data[14];
        cObj("edit_document_number").value = data[13];
<<<<<<< HEAD
        cObj("reason_for_payment_decline").innerText = data[17] != null ? (data[17].length > 0 ? data[17] : "Reason not stated!") : "Reason not stated!";
=======
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
        console.log(data);
        
        // set the expense activity
        var edit_expense_cash_activity = cObj("edit_expense_cash_activity").children;
        edit_expense_cash_activity[0].selected = true;
        for (let index = 0; index < edit_expense_cash_activity.length; index++) {
            const element = edit_expense_cash_activity[index];
            if (element.value == data[10]) {
                // console.log(element.value);
                edit_expense_cash_activity[index].selected = true;
            }
        }
        
        cObj("edit_expense_windows").classList.remove("hide");
<<<<<<< HEAD
        cObj("expense_status_view").innerHTML = data[16] == 1 ? "<span class='badge bg-success'>Payment Approved</span>" : (data[16] == 0 ? "<span class='badge bg-warning'>Payment Not Approved Yet!</span>" : "<span class='badge bg-danger'>Payment Declined!</span>");
        
        // disable the update button of payment is already approved
        if(data[16] == 1){
            cObj("delete_promt_expenses").classList.add("hide");
            cObj("total_unit_cost").disabled = true;
            cObj("reason_for_req_decline_window").classList.add("hide");
        }else{
            cObj("delete_promt_expenses").classList.remove("hide");
            cObj("total_unit_cost").disabled = false;
            if (data[16] == 2) {
                cObj("reason_for_req_decline_window").classList.remove("hide");
            }else{
                cObj("reason_for_req_decline_window").classList.add("hide");
            }
        }

        // send data to database
=======
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
        var datapass = "getExpenseCategories=true";
        sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("show_expense_category"),cObj("expense_cat_egories"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
<<<<<<< HEAD

                // expense category
=======
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
                if (cObj("expense_cat_egories").classList.contains("hide")) {
                    var exp_cats_exp = document.getElementsByClassName("exp_cats_exp");
                    for (let index = 0; index < exp_cats_exp.length; index++) {
                        const element = exp_cats_exp[index];
                        if (element.value == data[12]) {
                            element.selected = true;
                            break;
                        }
                    }

                    // DISPLAY THE EXPENSE SUBCATEGORIES
                    // display_expense_sub_categories(data[15].split(":")[1]);
                    display_expense_sub_categories(data[12], (data[15] != null ? data[15].split(":")[1] : "-"));

                    // set the listener
                    cObj("edit_expense_category").addEventListener("change", function () {
                        display_expense_sub_categories(this.value,"-");
                    });
                    stopInterval(ids);
                }
            }, 10);
        }, 10);
    }
}

function display_expense_sub_categories(expense_category,expense_sub_category) {
    var datapass = "get_expense_sub_category="+expense_category+"&expense_sub_category="+expense_sub_category;
    sendDataPost("POST","ajax/administration/admissions.php",datapass,cObj("show_expense_sub_category"),cObj("expense_sub_cat_egories"));
}

function displayRecord_expenses(start, finish, arrays) {
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><thead><tr><th title='Sort all' id='sortall_exp'># <span id='sortallexp'><i class='fas fa-caret-down'></i></span></th><th id='sortexp_name' title='Sort by Expense Name'>Expense Name<span id='sortByexp_name'><i class='fas fa-caret-down'></i></span></th><th  id='sortExp_cat' title='Sort by Expense Category'>Expense Category<span id='sortByExp_cat'><i class='fas fa-caret-down'></i></span></th><th title='Sort by Total Unit Cost' id='sortTotUnits'>Total Unit Cost <span id='sortByTotUnit_price'><i class='fas fa-caret-down'></i></span></th><th>Document Number</th><th>Date</th><th>Action</th></tr></thead><tbody>";
    if(finish < total) {
        fins = finish;
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < finish; index++) {
<<<<<<< HEAD
            var approval_status = arrays[index][16] == 1 ? '<small class="badge bg-success" title="Approved">A</small>' : (arrays[index][16] == 0 ? '<small class="badge bg-warning" title="Not-Approved">-A</small>' : '<small class="badge bg-danger" title="Payment Declined">-A</small>');
            tableData += "<tr><input type='hidden' id='data_expenses"+index+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][8]+" "+approval_status+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][0]+"</td><td class='text-center'>"+ (arrays[index][13] != null ? arrays[index][13] : "-") +"</td><td>"+arrays[index][6]+" @ "+arrays[index][4]+"</td><td><span class='link edit_expense' id='edit_expense"+index+"'><i class='fas fa-pen-fancy'></i> Edit</span></td></tr>";
=======
            tableData += "<tr><input type='hidden' id='data_expenses"+index+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][8]+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][0]+"</td><td class='text-center'>"+ (arrays[index][13] != null ? arrays[index][13] : "-") +"</td><td>"+arrays[index][6]+" @ "+arrays[index][4]+"</td><td><span class='link edit_expense' id='edit_expense"+index+"'><i class='fas fa-pen-fancy'></i> Edit</span></td></tr>";
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
            counter++;
        }
    }else{
        //create a table of the 50 records
        var counter = start+1;
        for (let index = start; index < total; index++) {
<<<<<<< HEAD
            var approval_status = arrays[index][16] == 1 ? '<small class="badge bg-success" title="Approved">A</small>' : (arrays[index][16] == 0 ? '<small class="badge bg-warning" title="Not-Approved">-A</small>' : '<small class="badge bg-danger" title="Payment Declined">-A</small>');
            tableData += "<tr><input type='hidden' id='data_expenses"+index+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][8]+" "+approval_status+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][0]+"</td><td class='text-center'>"+ (arrays[index][13] != null ? arrays[index][13] : "-") +"</td><td>"+arrays[index][6]+" @ "+arrays[index][4]+"</td><td><span class='link edit_expense' id='edit_expense"+index+"'><i class='fas fa-pen-fancy'></i> Edit</span></td></tr>";
=======
            tableData += "<tr><input type='hidden' id='data_expenses"+index+"' value='"+JSON.stringify(arrays[index])+"'><td>"+arrays[index][8]+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][0]+"</td><td class='text-center'>"+ (arrays[index][13] != null ? arrays[index][13] : "-") +"</td><td>"+arrays[index][6]+" @ "+arrays[index][4]+"</td><td><span class='link edit_expense' id='edit_expense"+index+"'><i class='fas fa-pen-fancy'></i> Edit</span></td></tr>";
>>>>>>> 81e1b958f51128c22ca1a0a78f0b19cacfa0380c
            counter++;
        }
        fins = total;
    }

    tableData += "</tbody></table>";
    //set the start and the end value
    cObj("startNo_expenses").innerText = start + 1;
    cObj("finishNo_expenses").innerText = fins;
    //set the page number
    cObj("pagenumNav_expenses").innerText = pagecounttrans;
    // set tool tip
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    setTimeout(() => {
        sortTableExpenses();
    }, 500);
    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 50
cObj("tonextNav_expenses").onclick = function() {
    console.log(pagecounttrans+" "+pagecountTransaction);
        if (pagecounttrans < pagecountTransaction) { // if the current page is less than the total number of pages add a page to go to the next page
            startpage_expenses += 50;
            pagecounttrans++;
            var endpage = startpage_expenses + 50;
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(startpage_expenses, endpage, rowsColStudents_expenses);
            setEvents();
        } else {
            pagecounttrans = pagecountTransaction;
        }
    }
    // end of next records
cObj("toprevNac_expenses").onclick = function() {
    if (pagecounttrans > 1) {
        pagecounttrans--;
        startpage_expenses -= 50;
        var endpage = startpage_expenses + 50;
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(startpage_expenses, endpage, rowsColStudents_expenses);
        setEvents();
    }
}
cObj("tofirstNav_expenses").onclick = function() {
    if (pagecountTransaction > 0) {
        pagecounttrans = 1;
        startpage_expenses = 0;
        var endpage = startpage_expenses + 50;
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(startpage_expenses, endpage, rowsColStudents_expenses);
        setEvents();
    }
}
cObj("tolastNav_expenses").onclick = function() {
    if (pagecountTransaction > 0) {
        pagecounttrans = pagecountTransaction;
        startpage_expenses = (pagecounttrans * 50) - 50;
        var endpage = startpage_expenses + 50;
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(startpage_expenses, endpage, rowsColStudents_expenses);
        setEvents();
    }
}

// seacrh keyword at the table
cObj("searchkey_expenses").onkeyup = function() {
        checkName3(this.value);
    }
    //create a function to check if the array has the keyword being searched for
function checkName3(keyword) {
    rowsColStudents_expenses = rowsNCols_original_expenses;
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
    for (let index = 0; index < rowsColStudents_expenses.length; index++) {
        const element = rowsColStudents_expenses[index];
        //column break
        var present = 0;
        if (element[0].toString().includes(keylower)) {
            present++;
        }
        if (element[1].toLowerCase().includes(keylower) || element[1].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[2].toLowerCase().includes(keylower) || element[2].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[3].toString().includes(keylower)) {
            present++;
        }
        if (element[4].toLowerCase().includes(keylower) || element[4].toUpperCase().includes(keyUpper)) {
            present++;
        }
        if (element[5].toString().includes(keylower)) {
            present++;
        }
        if (element[6].toLowerCase().includes(keyword) || element[6].toLowerCase().includes(keyUpper)) {
            present++;
        }
        if (element[13] != null) {
            if (element[13].toLowerCase().toString().includes(keylower)) {
                present++;
            }
        }
        //here you can add any other columns to be searched for
        // console.log(element[6]==keyword);
        if (present > 0) {
            rowsNcol2.push(element);
        }
    }
    if (rowsNcol2.length > 0) {
        rowsColStudents_expenses = rowsNcol2;
        var counted = rowsNcol2.length / 50;
        pagecountTransaction = Math.ceil(counted);
        cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsNcol2);
        cObj("tot_records_expenses").innerText = rowsNcol2.length;
        setEvents()
    } else {
        cObj("transDataReciever_expenses").innerHTML = "<div class='displaydata'><img class='' src='images/error.png'></div><p class='sm-text text-danger text-bold text-center'><br>Ooops! your search for \"" + keyword + "\" was not found</p>";
        // cObj("tablefooter").classList.add("invisible");
        cObj("startNo_expenses").innerText = 0;
        cObj("finishNo_expenses").innerText = 0;
        cObj("tot_records_expenses").innerText = 0;
        pagecountTransaction = 1;
    }
}

// sort in ascending or descending order
var sortallstatus = 1;
var sortadmnostatus = 1;
var sortfeeamountstatus = 1;
var sortUnits = 1;
var sortTotUnit = 1;
var sortUnited = 1;
function sortTableExpenses() {
    cObj("sortall_exp").addEventListener("click",function () {
        // sort all in ascending order
        if (sortallstatus == 0) {
            // asc up to down
            sortallstatus = 1;
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortDesc(rowsColStudents_expenses,8);
            var counted = rowsColStudents_expenses.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_expenses);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortallexp").innerHTML = "- <i class='fas fa-caret-down'></i>";
            setEvents();
        }else{
            // desc down to up
            sortallstatus = 0;
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortAsc(rowsColStudents_expenses,8);
            var counted = rowsColStudents_expenses.length / 50;
            // console.log(rowsColStudents_expenses);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortallexp").innerHTML = "- <i class='fas fa-caret-up'></i>";
            setEvents();
        }
    });
    cObj("sortexp_name").addEventListener("click",function () {
        // sort all in ascending order
        if (sortadmnostatus == 0) {
            // asc up to down
            sortadmnostatus = 1;
            // console.log(cObj("sortByexp_name").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortDesc(rowsColStudents_expenses,2);
            var counted = rowsColStudents_expenses.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_expenses);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByexp_name").innerHTML = "- <i class='fas fa-caret-down'></i>";
            setEvents();
        }else{
            // desc down to up
            sortadmnostatus = 0;
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortAsc(rowsColStudents_expenses,2);
            var counted = rowsColStudents_expenses.length / 50;
            // console.log(rowsColStudents_expenses);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByexp_name").innerHTML = "- <i class='fas fa-caret-up'></i>";
            setEvents();
        }
    });
    cObj("sortExp_cat").addEventListener("click",function () {
        // sort all in ascending order
        if (sortfeeamountstatus == 0) {
            // asc up to down
            sortfeeamountstatus = 1;
            // console.log(cObj("sortByExp_cat").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortDesc(rowsColStudents_expenses,3);
            var counted = rowsColStudents_expenses.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_expenses);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByExp_cat").innerHTML = "- <i class='fas fa-caret-down'></i>";
            setEvents();
        }else{
            // desc down to up
            sortfeeamountstatus = 0;
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortAsc(rowsColStudents_expenses,1);
            var counted = rowsColStudents_expenses.length / 50;
            // console.log(rowsColStudents_expenses);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByExp_cat").innerHTML = "- <i class='fas fa-caret-up'></i>";
            setEvents();
        }
    });
    cObj("sortTotUnits").addEventListener("click",function () {
        // sort all in ascending order
        if (sortTotUnit == 0) {
            // asc up to down
            sortTotUnit = 1;
            // console.log(cObj("sortByTotUnit_price").innerHTML);
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortDesc(rowsColStudents_expenses,0);
            var counted = rowsColStudents_expenses.length / 50;
            pagecountTransaction = Math.ceil(counted);
            // console.log(rowsColStudents_expenses);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByTotUnit_price").innerHTML = "- <i class='fas fa-caret-down'></i>";
            setEvents();
        }else{
            // desc down to up
            sortTotUnit = 0;
            //WITH FIRST COLUMN
            rowsColStudents_expenses = rowsNCols_original_expenses;
            rowsColStudents_expenses = sortAsc(rowsColStudents_expenses,0);
            var counted = rowsColStudents_expenses.length / 50;
            // console.log(rowsColStudents_expenses);
            pagecountTransaction = Math.ceil(counted);
            cObj("transDataReciever_expenses").innerHTML = displayRecord_expenses(0, 50, rowsColStudents_expenses);
            cObj("tot_records_expenses").innerText = rowsColStudents_expenses.length;
            cObj("sortByTotUnit_price").innerHTML = "- <i class='fas fa-caret-up'></i>";
            setEvents();
        }
    });
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