cObj("back_to_vans").onclick = function () {
    // get back to the van list
    cObj("save_van_window").classList.add("hide");
    cObj("viewRegisteredCars").classList.remove("hide");
    getTransport();
}
cObj("add_school_vans").onclick = function () {
    // get back to the van list
    cObj("save_van_window").classList.remove("hide");
    cObj("viewRegisteredCars").classList.add("hide");

    // get the school driver list
    var datapass = "?getdrivers=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("driver_lists"), cObj("vans_driver_load"));
    var datapass = "?getRoutes=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("routes_lists"), cObj("vans_routes"));
}

// switch through the vans and the routes
// cObj("routes_n_prices").onclick = function () {
//     cObj("route_information").classList.remove("hide");
//     cObj("vans_information").classList.add("hide");
// }
cObj("back_to_routes").onclick = function () {
    cObj("route_list").classList.remove("hide");
    cObj("register_route").classList.add("hide");
}
cObj("back_to_routes2").onclick = function () {
    cObj("route_list").classList.remove("hide");
    cObj("view_route_infor").classList.add("hide");
    cObj("routes_n_trans").click();
}
cObj("register_route_btn").onclick = function () {
    cObj("route_list").classList.add("hide");
    cObj("register_route").classList.remove("hide");
}
cObj("save_new_route").onclick = function () {
    // get the blank spaces on the routes
    var err = checkBlank("route_name");
    err += checkBlank("route_price");
    err += checkBlank("route_area_coverage");

    if (err <= 0) {
        // proceed to add the route in the system
        var datapass = "add_route=true&route_name=" + valObj("route_name") + "&route_price=" + valObj("route_price") + "&route_area_coverage=" + valObj("route_area_coverage");
        sendDataPost("POST", "/sims/ajax/administration/admissions.php", datapass, cObj("route_err_handler"), cObj("route_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("route_loader").classList.contains("hide")) {
                    if (cObj("route_err_handler").innerText == "Route added successfully!") {
                        cObj("back_to_routes").click();
                        cObj("route_name").value = "";
                        cObj("route_price").value = "";
                        cObj("route_area_coverage").value = "";
                        cObj("routes_n_trans").click();
                        cObj("back_to_vans").click();
                    }
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    } else {
        //
    }
}

cObj("delete_van").onclick = function () {
    // eelete the van from this field
    var datapass = "delete_van="+cObj("van_id_in").value;
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("delete_error_hand"),cObj("van_delete_it"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("van_delete_it").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("delete_error_hand").innerText = "";
                }, 4000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

var rowsColStudents2 = [];
var pagecountTransaction2 = 0; //this are the number of pages for transaction
var pagecounttrans2 = 1; //the current page the user is
var startpage2 = 1; // this is where we start counting the page number





// after the transport button is clicked get the route lists
function getRouteList() {
    rowsColStudents2 = [];
    pagecountTransaction2 = 0; //this are the number of pages for transaction
    pagecounttrans2 = 1; //the current page the user is
    startpage2 = 1; // this is where we start counting the page number
    // get the route list
    var datapass = "get_routes=true";
    sendDataPost("POST", "/sims/ajax/administration/admissions.php", datapass, cObj("myrouteinformation"), cObj("routes_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("routes_loader").classList.contains("hide")) {
                if (cObj("myrouteinformation").innerText.length > 0) {
                    var myrouteinformation = cObj("myrouteinformation").innerText.split("|");
                    for (let index = 0; index < myrouteinformation.length; index++) {
                        var element = myrouteinformation[index];
                        rowsColStudents2.push(element.split("^"));
                    }

                    cObj("tot_records2").innerText = myrouteinformation.length;
                    //create the display table
                    //get the number of pages
                    cObj("transDataReciever2").innerHTML = displayRecord2(1, 10, rowsColStudents2);

                    //show the number of pages for each record
                    var counted = myrouteinformation.length / 10;
                    pagecountTransaction2 = Math.ceil(counted);
                    set_routes();
                } else {
                    cObj("transDataReciever2").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Route data not found!!</p>";
                    cObj("tablefooter2").classList.add("invisible");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}// after the transport button is clicked get the route lists
function getTransport() {
    rowsColStudents1 = [];
    pagecountTransaction1 = 0; //this are the number of pages for transaction
    pagecounttrans1 = 1; //the current page the user is
    startpage1 = 1; // this is where we start counting the page number
    // get the route list
    var datapass = "get_vans=true";
    sendDataPost("POST", "/sims/ajax/administration/admissions.php", datapass, cObj("vans_informations"), cObj("van_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("van_loader").classList.contains("hide")) {
                if (cObj("vans_informations").innerText.length > 0) {
                    var vans_informations = cObj("vans_informations").innerText.split("|");
                    for (let index = 0; index < vans_informations.length; index++) {
                        var element = vans_informations[index];
                        rowsColStudents1.push(element.split("^"));
                    }

                    cObj("tot_records1").innerText = vans_informations.length;
                    //create the display table
                    //get the number of pages
                    cObj("transDataReciever1").innerHTML = displayRecord1(1, 10, rowsColStudents1);

                    //show the number of pages for each record
                    var counted = vans_informations.length / 10;
                    pagecountTransaction1 = Math.ceil(counted);
                    set_vansaction();
                } else {
                    cObj("transDataReciever1").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>School Vans data is not found!!</p>";
                    cObj("tablefooter1").classList.add("invisible");
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

function displayRecord2(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><tr><th>No.</th><th>Route Name</th><th>Route Price.</th><th>Route Arears</th><th>Actions</th></tr>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + ". </td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][2] +"</td><td>"+arrays[index][3] +"</td><td class='link viewroute' id='viewroute"+arrays[index][0]+"' style='font-size:12px;'><p><i class='fa fa-pen'></i> Edit</p></td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + ". </td><td>"+arrays[index][1]+"</td><td>Kes "+arrays[index][2] +"</td><td>"+arrays[index][3] +"</td><td class='link viewroute' id='viewroute"+arrays[index][0]+"' style='font-size:12px;'><p><i class='fa fa-pen'></i> Edit</p></td></tr>";
        }
        fins = total;
    }
    tableData += "</table>";
    //set the start and the end value
    cObj("startNo2").innerText = (start + 1);
    cObj("finishNo2").innerText = fins;
    //set the page number
    cObj("pagenumNav2").innerText = pagecounttrans2;

    // set the event listener of the edit button

    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 10
cObj("tonextNav2").onclick = function () {
    console.log(pagecounttrans2);
    if (pagecounttrans2 < pagecountTransaction2) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage2 += 10;
        pagecounttrans2++;
        var endpage = startpage2 + 11;
        cObj("transDataReciever2").innerHTML = displayRecord2(startpage2, endpage, rowsColStudents2);
        set_routes();
    } else {
        pagecounttrans2 = pagecountTransaction2;
    }
    setAssignLis();
}
// end of next records
cObj("toprevNac2").onclick = function () {
    if (pagecounttrans2 > 1) {
        pagecounttrans2--;
        startpage2 -= 10;
        var endpage = (startpage2 + 10) - 1;
        cObj("transDataReciever2").innerHTML = displayRecord2(startpage2, endpage, rowsColStudents2);
        set_routes();
    }
    setAssignLis();
}
cObj("tofirstNav2").onclick = function () {
    if (pagecountTransaction2 > 0) {
        pagecounttrans2 = 1;
        startpage2 = 0;
        var endpage = startpage2 + 10;
        cObj("transDataReciever2").innerHTML = displayRecord2(startpage2, endpage, rowsColStudents2);
        set_routes();
    }
    setAssignLis();
}
cObj("tolastNav2").onclick = function () {
    if (pagecountTransaction2 > 0) {
        pagecounttrans2 = pagecountTransaction2;
        startpage2 = ((pagecounttrans2 * 10) - 10) + 1;
        var endpage = startpage2 + 10;
        cObj("transDataReciever2").innerHTML = displayRecord2(startpage2, endpage, rowsColStudents2);
        set_routes();
    }
    setAssignLis();
}

// seacrh keyword at the table
cObj("searchkey2").onkeyup = function () {
    checkName2(this.value);
    // set the listener for the assign button
    var assign_payment = document.getElementsByClassName("assign_payment");
    for (let index = 0; index < assign_payment.length; index++) {
        const element = assign_payment[index];
        element.addEventListener("click", find_Payment);
    }
}
//create a function to check if the array has the keyword being searched for
function checkName2(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter2").classList.add("invisible");
        // set the 
        var rowsNcol2 = [];
        var keylower = keyword.toLowerCase();
        var keyUpper = keyword.toUpperCase();
        //row break
        for (let index = 0; index < rowsColStudents2.length; index++) {
            const element = rowsColStudents2[index];
            //column break
            var present = 0;
            if (element[1].toLowerCase().includes(keylower) || element[1].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[2].toLowerCase().includes(keylower) || element[2].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[3].toLowerCase().includes(keylower) || element[3].toLowerCase().includes(keyUpper)) {
                present++;
            }
            //here you can add any other columns to be searched for
            if (present > 0) {
                rowsNcol2.push(element);
            }
        }
        if (rowsNcol2.length > 0) {
            cObj("transDataReciever2").innerHTML = displayRecord2(1, 10, rowsNcol2);
            set_routes();
        } else {
            cObj("transDataReciever2").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
            cObj("tablefooter2").classList.add("invisible");
        }
    } else {
        cObj("tablefooter2").classList.remove("invisible");
        cObj("transDataReciever2").innerHTML = displayRecord2(1, 10, rowsColStudents2);
        set_routes();
    }
}

function set_routes() {
    var viewroute = document.getElementsByClassName("viewroute");
    for (let index = 0; index < viewroute.length; index++) {
        const element = viewroute[index];
        element.addEventListener("click",view_routes);
    }
}

function set_vansaction() {
    var viewvans = document.getElementsByClassName("viewvans");
    for (let index = 0; index < viewvans.length; index++) {
        const element = viewvans[index];
        element.addEventListener("click",viewVan);
    }
}
function viewVan() {
    var van_id = this.id.substr(8);
    cObj("viewRegisteredCars").classList.add("hide");
    cObj("update_van_window").classList.remove("hide");
    // get the school drivers and the routes available
    // get the school driver list
    var datapass = "?getdrivers_update=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("driver_lists1"), cObj("vans_driver_load1"));
    var datapass = "?getRoutes_update=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("routes_lists1"), cObj("vans_routes1"));
    // get the school van data
    var datapass = "van_infor="+van_id;
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("update_data"),cObj("van_loader1"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("van_loader1").classList.contains("hide")) {
                if (cObj("update_data").innerText.length > 0) {
                    // split the string to array
                    var data = cObj("update_data").innerText.split("|");
                    cObj("vans_names").innerText = data[1];
                    cObj("van_id_in").value = data[0];
                    cObj("bus_name1").value = data[1];
                    cObj("van_regno1").value = data[2];
                    cObj("van_model1").value = data[3];
                    cObj("van_seater_size1").value = data[4];
                    cObj("insurance_date1").value = data[6];
                    cObj("service_date1").value = data[7];
                    cObj("vans_regnos").innerText = data[2];
                    cObj("vans_models").innerText = data[3];
                    cObj("vans_seater_sizes").innerText = data[4];
                    cObj("vans_exp_dates").innerText = data[6];
                    cObj("vans_next_exp_dates").innerText = data[7];
                    cObj("vans_drivers").innerText = data[8];
                    cObj("vans_routes12").innerText = data[5];
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);

}

// update the vans data 
cObj("update_new_van").onclick = function () {
    var err = 0;
    err+=checkBlank("bus_name1");
    err+=checkBlank("van_regno1");
    err+=checkBlank("van_model1");
    err+=checkBlank("van_seater_size1");
    err+=checkBlank("insurance_date1");
    err+=checkBlank("service_date1");

    if (err == 0) {
        // proceed and save the data
        var datapass = "update_van=true";
        datapass+="&van_name="+valObj("bus_name1");
        datapass+="&van_regno="+valObj("van_regno1");
        datapass+="&van_model="+valObj("van_model1");
        datapass+="&van_seater_size="+valObj("van_seater_size1");
        datapass+="&insurance_date="+valObj("insurance_date1");
        datapass+="&service_date="+valObj("service_date1");
        datapass+="&van_id="+valObj("van_id_in");
        datapass+="&van_driver="+((valObj("van_driver_up").length < 1) ? "Null" : valObj("van_driver_up"));
        datapass+="&van_route="+((valObj("routed_lists_inside").length < 1) ? "Null" : valObj("routed_lists_inside"));
        sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("update_van_err"),cObj("update_bus_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("update_bus_loader").classList.contains("hide")) {
                    setTimeout(() => {
                        cObj("update_van_err").innerHTML = "";
                    }, 4000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        // show error 
        cObj("update_van_err").innerHTML = "<p class='text-danger'>Fill all the fields marked with red border!</p>";
    }
}
cObj("back_to_vans1").onclick = function () {
    cObj("viewRegisteredCars").classList.remove("hide");
    cObj("update_van_window").classList.add("hide");
    getTransport();
}
function view_routes() {
    var route_id = this.id.substr(9);
    cObj("route_list").classList.add("hide");
    cObj("view_route_infor").classList.remove("hide");
    // get the route list
    var datapass = "getroute_infor="+route_id;
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("routes_in4"),cObj("view_route_loader"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("view_route_loader").classList.contains("hide")) {
                if (cObj("routes_in4").innerText.length > 0) {
                    // split the string to array
                    var data = cObj("routes_in4").innerText.split("^");
                    cObj("routes_names").value = data[1];
                    cObj("routes_names2").innerText = data[1];
                    cObj("routes_prices").value = data[2];
                    cObj("routes_prices2").innerText = data[2];
                    cObj("routes_areas").value = data[3];
                    cObj("route_id").value = data[0];
                }
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

cObj("update_route").onclick = function () {
    // get the data first
    var err = checkBlank("routes_names");
    err+=checkBlank("routes_prices");
    if (err == 0) {
        // upload the data in the database
        var datapass = "update_routes=true&routes_names="+valObj("routes_names")+"&routes_price="+valObj("routes_prices")+"&routes_areas="+valObj("routes_areas")+"&route_ids="+valObj("route_id")+"&route_prev_price="+cObj("routes_prices2").innerText;
        sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("update_route_err_handler"),cObj("updates_routes_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("updates_routes_loader").classList.contains("hide")) {
                    cObj("viewroute"+valObj("route_id")).click();
                    cObj("back_to_vans").click();
                    setTimeout(() => {
                        cObj("update_route_err_handler").innerHTML = "";
                    }, 2000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{
        cObj("update_route_err_handler").innerHTML = "<p class='text-danger'>Fill all the fields marked with red border!</p>";
    }
}

cObj("delete_route").onclick = function () {
    // delete the route and get route id
    var route_id = valObj("route_id");
    var datapass = "delete_route="+route_id;
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("delete_err_route"),cObj("route_err_routed"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("route_err_routed").classList.contains("hide")) {
                setTimeout(() => {
                    cObj("back_to_routes2").click();
                    cObj("delete_err_route").innerHTML = "";
                }, 1000);
                stopInterval(ids);
            }
        }, 100);
    }, 200);
}

// register a new van
cObj("save_new_van").onclick = function () {
    rowsColStudents1 = [];
    pagecountTransaction1 = 0; //this are the number of pages for transaction
    pagecounttrans1 = 1; //the current page the user is
    startpage1 = 1; // this is where we start counting the page number
    // get errors
    var err = checkBlank("bus_name");
    err+=checkBlank("van_regno");
    err+=checkBlank("van_model");
    err+=checkBlank("van_seater_size");
    if (err == 0) {
        // proceed and save the data in the database
        var datapass = "save_van=true&bus_name="+valObj("bus_name")+"&van_regno="+valObj("van_regno")+"&van_model="+valObj("van_model")+"&van_seater_size="+valObj("van_seater_size")+"&insurance_date="+valObj("insurance_date")+"&service_date="+valObj("service_date")+"&routed_lists="+valObj("routed_lists")+"&van_driver="+valObj("van_driver")
        sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("save_van_err"),cObj("save_bus_loader"));
        setTimeout(() => {
            var timeout = 0;
            var ids = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(ids);
                }
                if (cObj("save_bus_loader").classList.contains("hide")) {
                    cObj("bus_name").value = "";
                    cObj("van_regno").value = "";
                    cObj("van_model").value = "";
                    cObj("van_seater_size").value = "";
                    cObj("insurance_date").value = "";
                    cObj("service_date").value = "";
                    cObj("routed_lists").value = "";
                    cObj("van_driver").value = "";
                    setTimeout(() => {
                        cObj("save_van_err").innerHTML = "";
                        cObj("back_to_vans").click();
                        getTransport();
                        getRouteList();
                    }, 2000);
                    stopInterval(ids);
                }
            }, 100);
        }, 200);
    }else{

    }
}



var rowsColStudents1 = [];
var pagecountTransaction1 = 0; //this are the number of pages for transaction
var pagecounttrans1 = 1; //the current page the user is
var startpage1 = 1; // this is where we start counting the page number

function displayRecord1(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><tr><th>No.</th><th>Van Name.</th><th>Manufacturer</th><th>Driver</th><th>Licence Expiration</th><th>Actions</th></tr>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + ". </td><td>"+arrays[index][1]+" ("+arrays[index][2]+")</td><td>"+arrays[index][3] +"</td><td>"+arrays[index][8] +"</td><td>"+arrays[index][6] +"</td><td class='link viewvans' id='viewvans"+arrays[index][0]+"' style='font-size:12px;'><p><i class='fa fa-pen'></i> Edit</p></td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            //create table of 10 elements
            tableData += "<tr><td>" + (index + 1) + ". </td><td>"+arrays[index][1]+" ("+arrays[index][2]+")</td><td>"+arrays[index][3] +"</td><td>"+arrays[index][8] +"</td><td>"+arrays[index][6] +"</td><td class='link viewvans' id='viewvans"+arrays[index][0]+"' style='font-size:12px;'><p><i class='fa fa-pen'></i> Edit</p></td></tr>";
        }
        fins = total;
    }
    tableData += "</table>";
    //set the start and the end value
    cObj("startNo1").innerText = (start + 1);
    cObj("finishNo1").innerText = fins;
    //set the page number
    cObj("pagenumNav1").innerText = pagecounttrans1;

    // set the event listener of the edit button

    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 10
cObj("tonextNav1").onclick = function () {
    if (pagecounttrans1 < pagecountTransaction1) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage1 += 10;
        pagecounttrans1++;
        var endpage = startpage1 + 11;
        cObj("transDataReciever2").innerHTML = displayRecord1(startpage1, endpage, rowsColStudents1);
        set_routes();
    } else {
        pagecounttrans1 = pagecountTransaction1;
    }
    set_vansaction();
}
// end of next records
cObj("toprevNac1").onclick = function () {
    if (pagecounttrans1 > 1) {
        pagecounttrans1--;
        startpage1 -= 10;
        var endpage = (startpage1 + 10) - 1;
        cObj("transDataReciever2").innerHTML = displayRecord1(startpage1, endpage, rowsColStudents1);
        set_routes();
    }
    set_vansaction();
}
cObj("tofirstNav1").onclick = function () {
    if (pagecountTransaction1 > 0) {
        pagecounttrans1 = 1;
        startpage1 = 0;
        var endpage = startpage1 + 10;
        cObj("transDataReciever1").innerHTML = displayRecord1(startpage1, endpage, rowsColStudents1);
        set_routes();
    }
    set_vansaction();
}
cObj("tolastNav1").onclick = function () {
    if (pagecountTransaction1 > 0) {
        pagecounttrans1 = pagecountTransaction1;
        startpage1 = ((pagecounttrans1 * 10) - 10) + 1;
        var endpage = startpage1 + 10;
        cObj("transDataReciever1").innerHTML = displayRecord1(startpage1, endpage, rowsColStudents1);
        set_routes();
    }
    set_vansaction();
}

// seacrh keyword at the table
cObj("searchkey1").onkeyup = function () {
    checkName1(this.value);
    // set the listener for the assign button
    var assign_payment = document.getElementsByClassName("assign_payment");
    for (let index = 0; index < assign_payment.length; index++) {
        const element = assign_payment[index];
        element.addEventListener("click", find_Payment);
    }
}
//create a function to check if the array has the keyword being searched for
function checkName1(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter1").classList.add("invisible");
        // set the 
        var rowsNcol2 = [];
        var keylower = keyword.toLowerCase();
        var keyUpper = keyword.toUpperCase();
        //row break
        for (let index = 0; index < rowsColStudents1.length; index++) {
            const element = rowsColStudents1[index];
            //column break
            var present = 0;
            if (element[1].toLowerCase().includes(keylower) || element[1].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[2].toLowerCase().includes(keylower) || element[2].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[3].toLowerCase().includes(keylower) || element[3].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[8].toLowerCase().includes(keylower) || element[8].toLowerCase().includes(keyUpper)) {
                present++;
            }
            //here you can add any other columns to be searched for
            if (present > 0) {
                rowsNcol2.push(element);
            }
        }
        if (rowsNcol2.length > 0) {
            cObj("transDataReciever1").innerHTML = displayRecord1(1, 10, rowsNcol2);
            set_vansaction();
        } else {
            cObj("transDataReciever1").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
            cObj("tablefooter1").classList.add("invisible");
        }
    } else {
        cObj("tablefooter1").classList.remove("invisible");
        cObj("transDataReciever1").innerHTML = displayRecord1(1, 10, rowsColStudents1);
        set_vansaction();
    }
}

cObj("enroll_student_tr").onclick = function () {
    // get the window that enrolls students
    cObj("enroll_stud_transport").classList.remove("hide");
    cObj("students_trans_enrolled").classList.add("hide");
    getStudents();
    getROuters();
}
cObj("back_to_std_trans_list").onclick = function () {
    // get the window that enrolls students
    cObj("enroll_stud_transport").classList.add("hide");
    cObj("students_trans_enrolled").classList.remove("hide");
    getStudentsTransport();
}

function getROuters() {
    var datapass = "?getRoutes_enroll_trans=true";
    sendData2("GET", "../ajax/finance/financial.php", datapass, cObj("student_routes_loader12"), cObj("student_routes_loaders"));
}

stud_fname = [];
sec_name = [];
sur_name = [];
stud_clases = [];
adm_nos = [];
// get the students name, admission number and class
function getStudents() {
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
                autocomplete(document.getElementById("student_named"), stud_fname,sec_name,sur_name,adm_nos,stud_clases);
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}

cObj("search_by_admission_no1").onclick = function () {
    // get the id 
    var datapass = "get_std_enroll_trans="+valObj("student_named");
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("output_2333"),cObj("admission_nos_223"));
    setTimeout(() => {
        var timeout = 0;
        var idfs = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idfs);
            }
            if (cObj("admission_nos_223").classList.contains("hide")) {
                var data = cObj("output_2333").innerText;
                if (data.length > 0) {
                    if(data == "-1"){
                        cObj("the_save_button").classList.add("hide");
                        cObj("err_handler_transport_sys").innerHTML = "<p class='text-danger'>The student is already enrolled for the transport!</p>";
                    }else{
                        cObj("the_save_button").classList.remove("hide");
                        cObj("err_handler_transport_sys").innerHTML = "<p class='text-success'>Student found! By clicking enroll you are confirming enrollment of the student found</p>";
                        // split the data to array
                        var stud_data = data.split("|");
                        cObj("_std_fullname").value = stud_data[0];
                        cObj("_std_stopage").value = stud_data[1];
                        cObj("_std_class").value = stud_data[2];
                        cObj("std_ids_in").value = valObj("student_named");
                    }
                }else{
                    cObj("the_save_button").classList.add("hide");
                    cObj("err_handler_transport_sys").innerHTML = "<p class='text-danger'>The admission number entered is invalid! or The student is an alumni!</p>";
                }

                setTimeout(() => {
                    cObj("err_handler_transport_sys").innerHTML = "";
                }, 20000);
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}
cObj("save_trans_stud").onclick = function () {
    // save student 
    if (cObj("enroll_studs_routes") == null) {
        // show that the object is null
        cObj("err_handler_transport_sys").innerHTML = "<p class='text-danger'>There are no routes registered, A student should be registered in a route.</p>";
    }else{
        cObj("err_handler_transport_sys").innerHTML = "";
        var err = 0;
        err+=checkBlank("_std_fullname");
        err+=checkBlank("_std_class");
        err+=checkBlank("_std_dor");
        err+=checkBlank("_std_stopage");
        err+=checkBlank("enroll_studs_routes");
        if (err == 0) {
            var datapass = "enroll_students=true";
            datapass+="&student_id="+valObj("std_ids_in");
            datapass+="&route_id="+valObj("enroll_studs_routes");
            datapass+="&stoppage="+valObj("_std_stopage");
            datapass+="&date_selected="+valObj("_std_dor");
            sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("err_handler_transport_sys"),cObj("admission_nos_223"));
            setTimeout(() => {
                var timeout = 0;
                var idfs = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(idfs);
                    }
                    if (cObj("admission_nos_223").classList.contains("hide")) {
                        cObj("_std_fullname").value = "";
                        cObj("_std_class").value = "";
                        // cObj("_std_dor").value = "";
                        cObj("_std_stopage").value = "";
                        setTimeout(() => {
                            cObj("back_to_std_trans_list").click();
                        }, 2000);
                        setTimeout(() => {
                            cObj("err_handler_transport_sys").innerHTML = "";
                        }, 5000);
                        stopInterval(idfs);
                    }
                }, 100);
            }, 100);
        }else{
            cObj("err_handler_transport_sys").innerHTML = "<p class='text-danger'>Please fill all the fields marked with red border!</p>";
        }
    }
}

var rowsColStudents4 = [];
var pagecountTransaction4 = 0; //this are the number of pages for transaction
var pagecounttrans4 = 1; //the current page the user is
var startpage4 = 1; // this is where we start counting the page number
function getStudentsTransport() {
    // get statistics
    getStatistics();
    rowsColStudents4 = [];
    pagecountTransaction4 = 0; //this are the number of pages for transaction
    pagecounttrans4 = 1; //the current page the user is
    startpage4 = 1; // this is where we start counting the page number
    var datapass = "getStudents_enrolled=true";
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("std_inform_trans"),cObj("student_trans_loader"));
    setTimeout(() => {
        var timeout = 0;
        var idfs = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idfs);
            }
            if (cObj("student_trans_loader").classList.contains("hide")) {
                if (cObj("std_inform_trans").innerText.length > 0) {
                    cObj("tablefooter4").classList.remove("invisible");
                    // var std_inform_trans = cObj("std_inform_trans").innerText.split("|");
                    var std_inform_trans = cObj("std_inform_trans").innerText;

                    if (hasJsonStructure(std_inform_trans)) {
                        std_inform_trans = JSON.parse(std_inform_trans);
                        for (let index = 0; index < std_inform_trans.length; index++) {
                            var element = std_inform_trans[index];
                            rowsColStudents4.push(element);
                        }
            
                        cObj("tot_records4").innerText = std_inform_trans.length;
                        //create the display table
                        //get the number of pages
                        cObj("transDataReciever4").innerHTML = displayRecord4(1, 10, rowsColStudents4);
                
                        //show the number of pages for each record
                        var counted = std_inform_trans.length / 10;
                        pagecountTransaction4 = Math.ceil(counted);
                        setView_Trans();
                    }else {
                        cObj("transDataReciever4").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Student data is not found!!</p>";
                        cObj("tablefooter4").classList.add("invisible");
                    }
                    // for (let index = 0; index < std_inform_trans.length; index++) {
                    //     var element = std_inform_trans[index];
                    //     rowsColStudents4.push(element.split("^"));
                    // }
            
                    // cObj("tot_records4").innerText = std_inform_trans.length;
                    // //create the display table
                    // //get the number of pages
                    // cObj("transDataReciever4").innerHTML = displayRecord4(1, 10, rowsColStudents4);
            
                    // //show the number of pages for each record
                    // var counted = std_inform_trans.length / 10;
                    // pagecountTransaction4 = Math.ceil(counted);
                    // setView_Trans();
                } else {
                    cObj("transDataReciever4").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Student data is not found!!</p>";
                    cObj("tablefooter4").classList.add("invisible");
                }
                
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}

function displayRecord4(start, finish, arrays) {
    start--;
    if (start < 0) {
        start = 0;
    }
    var total = arrays.length;
    //the finish value
    var fins = 0;
    //this is the table header to the start of the tbody
    var tableData = "<table class='table'><tr><th>No.</th><th>Student Name.</th><th>Stoppage</th><th>Route</th><th>Date Joined</th><th>Actions</th></tr>";
    if (finish < total) {
        fins = finish;
        //create a table of the 10 records
        for (let index = start; index < finish; index++) {
            //create table of 10 elements
            tableData += "<tr><td>"+(index + 1)+". </td><td>"+arrays[index][1]+"</td><td>"+arrays[index][3]+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][4]+"</td><td> <p class='link studtrans1' id='stud_trans"+arrays[index][0]+"' style='font-size:12px;' ><i class='fa fa-eye'></i> View</p></td></tr>";
        }
    } else {
        //create a table of the 10 records
        for (let index = start; index < total; index++) {
            //create table of 10 elements
            tableData += "<tr><td>"+(index + 1)+". </td><td>"+arrays[index][1]+"</td><td>"+arrays[index][3]+"</td><td>"+arrays[index][2]+"</td><td>"+arrays[index][4]+"</td><td> <p class='link studtrans1' id='stud_trans"+arrays[index][0]+"' style='font-size:12px;' ><i class='fa fa-eye'></i> View</p></td></tr>";
        }
        fins = total;
    }
    tableData += "</table>";
    //set the start and the end value
    cObj("startNo4").innerText = (start + 1);
    cObj("finishNo4").innerText = fins;
    //set the page number
    cObj("pagenumNav4").innerText = pagecounttrans4;

    // set the event listener of the edit button

    return tableData;
}
//next record 
//add the page by one and the number os rows to dispay by 10
cObj("tonextNav4").onclick = function () {
    if (pagecounttrans4 < pagecountTransaction4) { // if the current page is less than the total number of pages add a page to go to the next page
        startpage4 += 10;
        pagecounttrans4++;
        var endpage = startpage4 + 11;
        cObj("transDataReciever4").innerHTML = displayRecord4(startpage4, endpage, rowsColStudents4);
        set_routes();
    } else {
        pagecounttrans4 = pagecountTransaction4;
    }
    setView_Trans();
}
// end of next records
cObj("toprevNac4").onclick = function () {
    if (pagecounttrans4 > 1) {
        pagecounttrans4--;
        startpage4 -= 10;
        var endpage = (startpage4 + 10) - 1;
        cObj("transDataReciever4").innerHTML = displayRecord4(startpage4, endpage, rowsColStudents4);
        set_routes();
    }
    setView_Trans();
}
cObj("tofirstNav4").onclick = function () {
    if (pagecountTransaction4 > 0) {
        pagecounttrans4 = 1;
        startpage4 = 0;
        var endpage = startpage4 + 10;
        cObj("transDataReciever4").innerHTML = displayRecord4(startpage4, endpage, rowsColStudents4);
        set_routes();
    }
    setView_Trans();
}
cObj("tolastNav4").onclick = function () {
    if (pagecountTransaction4 > 0) {
        pagecounttrans4 = pagecountTransaction4;
        startpage4 = ((pagecounttrans4 * 10) - 10) + 1;
        var endpage =startpage4 + 10;
        cObj("transDataReciever4").innerHTML = displayRecord4(startpage4, endpage, rowsColStudents4);
        set_routes();
    }
    setView_Trans();
}

// seacrh keyword at the table
cObj("searchkey4").onkeyup = function () {
    checkName4(this.value);
    // set the listener for the assign button
    var assign_payment = document.getElementsByClassName("assign_payment");
    for (let index = 0; index < assign_payment.length; index++) {
        const element = assign_payment[index];
        element.addEventListener("click", find_Payment);
    }
}
//create a function to check if the array has the keyword being searched for
function checkName4(keyword) {
    if (keyword.length > 0) {
        cObj("tablefooter4").classList.add("invisible");
        // set the 
        var rowsNcol2 = [];
        var keylower = keyword.toLowerCase();
        var keyUpper = keyword.toUpperCase();
        //row break
        for (let index = 0; index < rowsColStudents4.length; index++) {
            const element = rowsColStudents4[index];
            //column break
            var present = 0;
            if (element[1].toLowerCase().includes(keylower) || element[1].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[2].toLowerCase().includes(keylower) || element[2].toLowerCase().includes(keyUpper)) {
                present++;
            }
            if (element[3].toLowerCase().includes(keylower) || element[3].toLowerCase().includes(keyUpper)) {
                present++;
            }
            //here you can add any other columns to be searched for
            if (present > 0) {
                rowsNcol2.push(element);
            }
        }
        if (rowsNcol2.length > 0) {
            cObj("transDataReciever4").innerHTML = displayRecord4(1, 10, rowsNcol2);
            setView_Trans();
        } else {
            cObj("transDataReciever4").innerHTML = "<p class='sm-text text-danger text-bold text-center'><span style='font-size:40px;'><i class='fas fa-exclamation-triangle'></i></span> <br>Ooops! your search for \"" + keyword + "\" was not found</p>";
            cObj("tablefooter4").classList.add("invisible");
        }
    } else {
        cObj("tablefooter4").classList.remove("invisible");
        cObj("transDataReciever4").innerHTML = displayRecord4(1, 10, rowsColStudents4);
        setView_Trans();
    }
}
function getStatistics() {
    // get the data
    var datapass = "get_statistics=true";
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("statistics_trans"),cObj("statistics_loader"));
    setTimeout(() => {
        var timeout = 0;
        var idfs = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idfs);
            }
            if (cObj("statistics_loader").classList.contains("hide")) {
                var statistics_trans = cObj("statistics_trans").innerText;
                if (statistics_trans.length > 0) {
                    var data = statistics_trans.split("|");
                    cObj("students_enrolled").innerText = data[0]+" Student(s)";
                    cObj("routes_counted").innerText = data[2]+" Route(s)";
                    cObj("vans_counted").innerText = data[1]+" Van(s)";
                }else{
                    cObj("students_enrolled").innerText = "0 Student(s)";
                    cObj("routes_counted").innerText = "0 Route(s)";
                    cObj("vans_counted").innerText = "0 Van(s)";
                }
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}

function setView_Trans() {
    var viewstd_trans = document.getElementsByClassName("studtrans1");
    for (let index = 0; index < viewstd_trans.length; index++) {
        var element = viewstd_trans[index];
        element.addEventListener("click",viewStudent_trans);
    }
}

function viewStudent_trans() {
    var id = this.id.substr(10);
    cObj("view_student_infor_trans").classList.remove("hide");
    cObj("students_trans_enrolled").classList.add("hide");
    var datapass = "?getroute_view_information=true";
    sendData2("GET","../ajax/finance/financial.php",datapass,cObj("student_routes_loader13"),cObj("student_routes_loaders2"));
    setTimeout(() => {
        var timeout = 0;
        var ids = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(ids);
            }
            if (cObj("student_routes_loaders2").classList.contains("hide")) {
                if (cObj("route_details_lists") != undefined) {
                    var route_details_lists = cObj("route_details_lists").value;
                    if (hasJsonStructure(route_details_lists)) {
                        route_details_lists = JSON.parse(route_details_lists);

                        // we create the terms route lists
                        var data_to_display = "<select name='route_for_term_1' id='route_for_term_1' class='form-control'><option value='' hidden >Select Route</option><option class='routes_data1' value='inactive' >In-Active</option>";
                        var data_to_display2 = "<select name='route_for_term_2' id='route_for_term_2' class='form-control'><option value='' hidden >Select Route</option><option class='routes_data2' value='inactive' >In-Active</option>";
                        var data_to_display3 = "<select name='route_for_term_3' id='route_for_term_3' class='form-control'><option value='' hidden >Select Route</option><option class='routes_data3' value='inactive' >In-Active</option>";
                        for (let index = 0; index < route_details_lists.length; index++) {
                            const element = route_details_lists[index];
                            data_to_display+="<option class='routes_data1' value='"+element.route_id+"' >"+element.route_name+" (Kes "+element.route_price+") </option>";
                            data_to_display2+="<option class='routes_data2' value='"+element.route_id+"' >"+element.route_name+" (Kes "+element.route_price+") </option>";
                            data_to_display3+="<option class='routes_data3' value='"+element.route_id+"' >"+element.route_name+" (Kes "+element.route_price+") </option>";
                        }
                        data_to_display+="</select>";
                        data_to_display2+="</select>";
                        data_to_display3+="</select>";
                        cObj("route_term_1").innerHTML = data_to_display;
                        cObj("route_term_2").innerHTML = data_to_display2;
                        cObj("route_term_3").innerHTML = data_to_display3;
                    }
                }
                stopInterval(ids);
            }
        }, 100);
    }, 100);
    var datapass = "student_data="+id;
    sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("stud_data_2"),cObj("stud_data_loader"));
    setTimeout(() => {
        var timeout = 0;
        var idfs = setInterval(() => {
            timeout++;
            //after two minutes of slow connection the next process wont be executed
            if (timeout == 1200) {
                stopInterval(idfs);
            }
            if (cObj("stud_data_loader").classList.contains("hide")) {
                var std_data = cObj("stud_data_2").innerText;
                if (hasJsonStructure(std_data)) {
                    std_data = JSON.parse(std_data);
                    if (std_data.length > 0) {
                        var data = std_data;
                        cObj("full_name").value = data[0];
                        cObj("studs_class_trans").value = data[5];
                        cObj("studs_dor_trans2").value = data[3];
                        cObj("stud_stoppage_trans").value = data[2];
                        cObj("stoppage_val").innerText = data[2];
                        cObj("route_values").innerText = data[1];
                        cObj("stud_detail_trans_id").value = data[4];
                        cObj("admn_no_trans").innerText = data[6];
                        // console.log(data[7]);

                        // if hasjson structure
                        data[7] = replaceSingleQuotes(data[7]);
                        if (hasJsonStructure(data[7])) {
                            var term_data = JSON.parse(data[7]);
                            var term_1 = [];
                            var term_2 = [];
                            var term_3 = [];

                            // get the datalist
                            for (let index = 0; index < term_data.length; index++) {
                                const element = term_data[index];
                                if (element.term == "TERM_1") {
                                    term_1 = element;
                                }
                                if (element.term == "TERM_2") {
                                    term_2 = element;
                                }
                                if (element.term == "TERM_3") {
                                    term_3 = element;
                                }
                            }
                            
                            // set the value for the three terms
                            var routes_data1 = document.getElementsByClassName("routes_data1");
                            for (let index = 0; index < routes_data1.length; index++) {
                                const element = routes_data1[index];
                                if (element.value == term_1.route) {
                                    element.selected = true;
                                }
                            }
                            var routes_data2 = document.getElementsByClassName("routes_data2");
                            for (let index = 0; index < routes_data2.length; index++) {
                                const element = routes_data2[index];
                                if (element.value == term_2.route) {
                                    element.selected = true;
                                }
                            }
                            var routes_data3 = document.getElementsByClassName("routes_data3");
                            for (let index = 0; index < routes_data3.length; index++) {
                                const element = routes_data3[index];
                                if (element.value == term_3.route) {
                                    element.selected = true;
                                }
                            }
                        }
                    }
                }
                stopInterval(idfs);
            }
        }, 100);
    }, 100);
}
function replaceSingleQuotes(string) {
    var result = string.replace(/'/g, '"');
    return result;
}  
cObj("back_to_std_trans_list2").onclick = function () {
    getStudentsTransport();
    cObj("view_student_infor_trans").classList.add("hide");
    cObj("students_trans_enrolled").classList.remove("hide");
}

cObj("Update_stud_trans").onclick = function () {
    // get the data
    var err_data = 0;
    if (cObj("update_studs_routes") == null) {
        
    }else{
        err_data+=checkBlank("stud_detail_trans_id");
        err_data+=checkBlank("studs_class_trans");
        err_data+=checkBlank("studs_dor_trans2");
        err_data+=checkBlank("stud_stoppage_trans");
        err_data+=checkBlank("update_studs_routes");
        
        var datapass = "update_student_trans=true";
        datapass+="&data_id="+valObj("stud_detail_trans_id");
        datapass+="&route_id="+valObj("update_studs_routes");
        datapass+="&stud_stoppage_trans="+valObj("stud_stoppage_trans");

        sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("err_handler_transport2"),cObj("update_std_spinner"));
        setTimeout(() => {
            var timeout = 0;
            var idfs = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idfs);
                }
                if (cObj("update_std_spinner").classList.contains("hide")) {
                    cObj("stud_trans"+valObj("stud_detail_trans_id")).click();
                    setTimeout(() => {
                        cObj("err_handler_transport2").innerText = "";
                    }, 3000);
                    stopInterval(idfs);
                }
            }, 100);
        }, 100);
    }
}
cObj("de_register_stud_transport").onclick = function () {
    var err = checkBlank("select_term_deregister");
    if (err == 0) {
        // get the data to delete
        var datapass = "deregister_stud="+valObj("stud_detail_trans_id")+"&select_term_deregister="+valObj("select_term_deregister");
        sendDataPost("POST","/sims/ajax/administration/admissions.php",datapass,cObj("err_handler_transport2"),cObj("update_std_spinner"));
        setTimeout(() => {
            var timeout = 0;
            var idfs = setInterval(() => {
                timeout++;
                //after two minutes of slow connection the next process wont be executed
                if (timeout == 1200) {
                    stopInterval(idfs);
                }
                if (cObj("update_std_spinner").classList.contains("hide")) {
                    cObj("back_to_std_trans_list2").click();
                    stopInterval(idfs);
                    setTimeout(() => {
                        cObj("err_handler_transport2").innerText = "";
                    }, 3000);
                }
            }, 100);
        }, 100);
    }
}

cObj("save_term_one_route").onclick = function () {
    if (cObj("route_for_term_1") != undefined) {
        var err = checkBlank("route_for_term_1");
        if (err == 0) {
            // get the data
            var datapass = "?update_student_route=true&student_id="+cObj("admn_no_trans").innerText+"&term=TERM_1&router_id="+valObj("route_for_term_1");
            sendData2("GET","../ajax/finance/financial.php",datapass,cObj("error_message_t1"),cObj("term_one_tr_loader"));
            setTimeout(() => {
                var timeout = 0;
                var idfs = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(idfs);
                    }
                    if (cObj("term_one_tr_loader").classList.contains("hide")) {
                        setTimeout(() => {
                            cObj("error_message_t1").innerText = "";
                        }, 3000);
                        stopInterval(idfs);
                    }
                }, 100);
            }, 100);
        }else{
            cObj("error_message_t1").innerHTML = "<p class='text-danger'>Select route before proceeding!</p>";
        }
    }else{
        cObj("error_message_t1").innerHTML = "<p class='text-danger'>Routes are not set!</p>";
    }
}
// term two
cObj("save_term_two_route").onclick = function () {
    if (cObj("route_for_term_2") != undefined) {
        var err = checkBlank("route_for_term_2");
        if (err == 0) {
            // get the data
            var datapass = "?update_student_route=true&student_id="+cObj("admn_no_trans").innerText+"&term=TERM_2&router_id="+valObj("route_for_term_2");
            sendData2("GET","../ajax/finance/financial.php",datapass,cObj("error_message_t2"),cObj("term_two_tr_loader"));
            setTimeout(() => {
                var timeout = 0;
                var idfs = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(idfs);
                    }
                    if (cObj("term_two_tr_loader").classList.contains("hide")) {
                        setTimeout(() => {
                            cObj("error_message_t2").innerText = "";
                        }, 3000);
                        stopInterval(idfs);
                    }
                }, 100);
            }, 100);
        }else{
            cObj("error_message_t2").innerHTML = "<p class='text-danger'>Select route before proceeding!</p>";
        }
    }else{
        cObj("error_message_t2").innerHTML = "<p class='text-danger'>Routes are not set!</p>";
    }
}
// term three
cObj("save_term_three_route").onclick = function () {
    if (cObj("route_for_term_3") != undefined) {
        var err = checkBlank("route_for_term_3");
        if (err == 0) {
            // get the data
            var datapass = "?update_student_route=true&student_id="+cObj("admn_no_trans").innerText+"&term=TERM_3&router_id="+valObj("route_for_term_3");
            sendData2("GET","../ajax/finance/financial.php",datapass,cObj("error_message_t3"),cObj("term_three_tr_loader"));
            setTimeout(() => {
                var timeout = 0;
                var idfs = setInterval(() => {
                    timeout++;
                    //after two minutes of slow connection the next process wont be executed
                    if (timeout == 1200) {
                        stopInterval(idfs);
                    }
                    if (cObj("term_three_tr_loader").classList.contains("hide")) {
                        setTimeout(() => {
                            cObj("error_message_t3").innerText = "";
                        }, 3000);
                        stopInterval(idfs);
                    }
                }, 100);
            }, 100);
        }else{
            cObj("error_message_t3").innerHTML = "<p class='text-danger'>Select route before proceeding!</p>";
        }
    }else{
        cObj("error_message_t3").innerHTML = "<p class='text-danger'>Routes are not set!</p>";
    }
}