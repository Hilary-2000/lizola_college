

function hideWindow() {
    let windows = document.getElementsByClassName("contents");
    for (var t = 0; t < windows.length; t++) {
        windows[t].classList.add("hide");
    }
}

function unselectbtns() {
    let sidebtn = document.getElementsByClassName("sidebtns");
    for (let index = 0; index < sidebtn.length; index++) {
        const element = sidebtn[index];
        //element.style.backgroundColor = "rgb(189, 189, 189)";
        //element.style.fontWeight = "400";
        element.classList.remove("selectedbtn");
    }
}
window.onclick = function (event) {
    if (event.target == cObj("paneled")) {
        cObj("paneled").style.display = 'none';
        cObj("sideme").classList.remove("animate3");
        cObj("sideme").classList.add("animate4");
        setTimeout(() => {
            cObj("sideme").style.display = 'none';
        }, 400);
    }
}

function stopInterval(id) {
    clearInterval(id);
}
function removesidebar() {
    if (cObj("sideme").classList.contains("unhide")) {
        cObj("paneled").style.display = 'none';
        cObj("sideme").classList.remove("animate3");
        cObj("sideme").classList.add("animate4");
        setTimeout(() => {
            cObj("sideme").style.display = 'none';
        }, 400);
    }
}
function addselected(id) {
    //cObj(id).style.backgroundColor = "gray";
    //cObj(id).style.fontWeight = "600";
    cObj(id).classList.add("selectedbtn");
}

function valObj1(id) {
    if (cObj(id).value.length > 0) {
        if (cObj(id).value == "N/A") {
            return '';
        } else {
            return cObj(id).value;
        }
    } else {
        return '';
    }
}

function compareTwo(string1, string2) {
    let alike = 0;
    if (string1.length > 0 && string2.length > 0) {
        if (string1 == string2) {
            alike++;
        }
    }
    return alike;
}

function ucwords(string) {
    var cases = string.toLowerCase().split(" ");
    // split the string to get the number of words present
    var final_word = "";
    for (let index = 0; index < cases.length; index++) {
        const element = cases[index];
        final_word += element.substr(0, 1).toUpperCase() + element.substr(1) + " ";
    }
    return final_word.trim();
}
function ucword(string) {
    if (string != null) {
        var cases = string.toLowerCase();
        // split the string to get the number of words present
        var final_word = cases.substr(0, 1).toUpperCase() + cases.substr(1);
        return final_word.trim();
    }
    return "";
}

function isJSON(value) {
    try {
        JSON.parse(value);
        return true;
    } catch (error) {
        return false;
    }
}
function checkBlank(id) {
    let err = 0;
    if (cObj(id).value.trim().length > 0) {
        if (cObj(id).value.trim() == 'N/A') {
            redBorder(cObj(id));
            err++;
        } else {
            grayBorder(cObj(id));
        }
    } else {
        redBorder(cObj(id));
        err++;
    }
    return err;
}
function checkPhone(id, dispErr) {
    let err = 0;
    var phone = valObj(id);
    if (cObj(id).value.length == 10 || cObj(id).value.length == 12) {
        grayBorder(cObj(id));
        cObj(dispErr).innerHTML = "<p style='display:none;'></p>";
    } else {
        redBorder(cObj(id));
        cObj(dispErr).innerHTML = "<p style='font-size:12px;color:red;'>Phone number should be either (ten) 10 (0712345678) or (twelve) 12 (254712345678) characters</p>";
        err++;
    }
    return err;
}
function checkEmails(idobj, errorhandler) {
    let err = 0;
    var email = valObj(idobj);
    if (email.length > 1) {
        if (email.includes("@", 1) && (email.includes(".com", 2) || email.includes(".co", 2) || email.includes(".go.ke", 2) || email.includes(".ac.ke", 2))) {
            cObj(errorhandler).innerHTML = "<p style=''></p>";
            grayBorder(cObj(idobj));
        } else {
            err++;
            redBorder(cObj(idobj));
            cObj(errorhandler).innerHTML = "<p style='color:red;'>An email should contain '@', '.com', '.co.ke', '.ac.ke' </p>";
        }
    }
    return err;

}

function setDatalen(id, values) {
    values = values + "";
    if (values.length > 0) {
        cObj(id).value = values;
    } else {
        cObj(id).value = 'N/A';
    }
}
function redirect(link) {
    window.location = link;
}

function grayBorder(object) {
    object.style.borderColor = 'gray';
}
function redBorder(object) {
    object.style.borderColor = 'red';
}
//this allows typing of numbers only
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
/***
cObj("back_btns").onmousedown = function () {
    this.style.cursor = "grabbing";
}
cObj("back_btns").onmouseup = function () {
    this.style.cursor = "grab";
}
cObj("back_btns").onmouseleave = function () {
    this.style.cursor = "grab";
}
 */
function sendData1(method, file, datapassing, object) {
    // console.log("ajax/" + file + datapassing);
    // datapassing = escape(datapassing);
    //make the loading window show
    cObj("loadings").classList.remove("hide");
    let xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            object.innerHTML = this.responseText;
            cObj("loadings").classList.add("hide");
        } else if (this.status == 500) {
            cObj("loadings").classList.add("hide");
            object.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
        }
    };
    xml.open(method, "ajax/" + file + datapassing, true);
    xml.send();
}

function UrlExists(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status != 404;
}

function sendData(method, file, datapassing, object) {
    // datapassing = escape(datapassing);
    let xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            object.innerHTML = this.responseText;
        } else if (this.status == 500) {
            cObj("loadings").classList.add("hide");
            object.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
        }
    };
    xml.open(method, "ajax/" + file + datapassing, true);
    xml.send();
}

function isPresent(array, value) {
    if (array.length > 0) {
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            if (element == value) {
                return true;
            }
        }
    }
    return false;
}

function sendData2(method, file, datapassing, object1, object2) {
    // datapassing = escape(datapassing);
    //make the loading window show
    object2.classList.remove("hide");
    let xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            object1.innerHTML = this.responseText;
            object2.classList.add("hide");
        } else if (this.status == 500) {
            object2.classList.add("hide");
            cObj("loadings").classList.add("hide");
            object1.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
        }
    };
    xml.open(method, "ajax/" + file + datapassing, true);
    xml.send();
}

function classNameAdms(data) {
    if (data == "-1") {
        return "Alumni";
    }
    if (data == "-2") {
        return "Transfered";
    }
    var datas = "Grade " + data;
    if (data.length > 1) {
        datas = data;
    }
    return datas;
}

function sendData4(method, link, datapassing, object) {
    // datapassing = escape(datapassing);
    //make the loading window show
    cObj("loadings").classList.remove("hide");
    let xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            object.innerHTML = this.responseText;
            cObj("loadings").classList.add("hide");
        } else if (this.status == 500) {
            cObj("loadings").classList.add("hide");
            object.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
        }
    };
    xml.open(method, link + datapassing);
    xml.setRequestHeader("Access-Control-Allow-Origin", "*");
    xml.setRequestHeader("Access-Control-Allow-Credentials", "true");
    xml.send();
}
function formatNum(n) {
    var splits = n.toString().split(".");
    const numSplit = splits[0];
    const decimalSplit = splits[1];
    const thousands = /\B(?=(\d{3})+(?!\d))/g;
    return numSplit.replace(thousands, ",") + (decimalSplit ? "." + decimalSplit : "");
}
function hasJsonStructure(str) {
    if (typeof str !== 'string') return false;
    try {
        const result = JSON.parse(str);
        const type = Object.prototype.toString.call(result);
        return type === '[object Object]'
            || type === '[object Array]';
    } catch (err) {
        return false;
    }
}
function sendDataPost(method, file, datapassing, object1, object2) {
    //make the loading window show
    // datapassing = escape(datapassing);
    cObj(object2.id).classList.remove("hide");
    let xml = new XMLHttpRequest();
    xml.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            object1.innerHTML = this.responseText;
            cObj(object2.id).classList.add("hide");
        } else if (this.status == 500) {
            cObj(object2.id).classList.add("hide");
            object1.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
        }
    };
    xml.open(method, "" + file, true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xml.send(datapassing);
}
function isCookieSet(cookieName) {
    return document.cookie.indexOf(cookieName + '=') !== -1;
}
  function getCookieValue(cookieName) {
    var cookieString = document.cookie;
    var cookies = cookieString.split(";");
  
    for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i].trim();
      if (cookie.indexOf(cookieName + "=") === 0) {
        return decodeURIComponent(cookie.substring(cookieName.length + 1));
      }
    }
  
    return null; // Cookie not found
  }
// Function to set a cookie with expiration date
function setCookie(name, value, days) {
    var expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + days);

    var cookieValue = encodeURIComponent(value) + ";";
    cookieValue += "expires=" + expirationDate.toUTCString() + ";";

    document.cookie = name + "=" + cookieValue;
}

// function sendDataPost(method,file,datapassing) {
//     //make the loading window show
//     //object2.classList.remove("hide");
//     let xml = new XMLHttpRequest();
//     xml.onreadystatechange = function () {
//         if(this.readyState ==4 && this.status==200){
//             // object1.innerHTML = this.responseText;
//             // object2.classList.add("hide");
//         }else if (this.status == 500) {
//             // object2.classList.add("hide");
//             // object1.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
//         }
//     };
//     xml.open(method,""+file,true);
//     xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xml.send(datapassing);
// }
// function sendDataPost(method,file,datapassing,object1) {
//     //make the loading window show
//     //object2.classList.remove("hide");
//     let xml = new XMLHttpRequest();
//     xml.onreadystatechange = function () {
//         if(this.readyState ==4 && this.status==200){
//             object1.innerHTML = this.responseText;
//             // object2.classList.add("hide");
//         }else if (this.status == 500) {
//             // object2.classList.add("hide");
//             object1.innerHTML = "<p class='red_notice'>Cannot establish connection to server.<br>Try reloading your page</p>";
//         }
//     };
//     xml.open(method,""+file,true);
//     xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xml.send(datapassing);
// }
function comma3(number) {
    if (number + "".length < 1) {
        return number;
    }
    var negatives = "";
    if (number + "".substring(0, 1) == "-") {
        number = number + "".substring(1);
        negatives = "-";
    }
    var data = [];
    number += "";
    var wholenumber = number;
    var results = number.includes(".");
    if (results == true) {
        data = number.split(".");
        wholenumber = data[0];
    }
    wholenumber += "";
    var reverse = strreverse(wholenumber);
    var tt = "";
    for (let v = 0; v < reverse.length; v++) {
        if ((v + 1) % 3 == 0 && v != 0) {
            if ((v + 2) > reverse.length) {
                tt += reverse.charAt(v);
            } else {
                tt += reverse.charAt(v) + ",";
            }
        } else {
            tt += reverse.charAt(v);
        }
    }
    if (data.length > 1) {
        var data1 = data[1].substring(0, 2);
        // console.log(data1);
        return negatives + strreverse(tt) + "." + data1;
    } else {
        return negatives + strreverse(tt);
    }
}

function strreverse(str) {
    var len = str.length;
    var data = "";
    for (let i = len; i >= 0; i--) {
        data += str.charAt(i);
    }
    return data;
}
function cObj(objectid) {
    return document.getElementById(objectid);
}
function valObj(objectid) {
    if (document.getElementById(objectid) == null) {
        return "";
    }
    return document.getElementById(objectid).value;
}
