<?php
session_start();
date_default_timezone_set('Africa/Nairobi');

// create a function to allow the buttons to be visible
function allowed($id){
    $auth = $_SESSION['auth'];
    if ($auth == 0) {
        $allowed = ['admitbtn',"findstudsbtn",'callregister','regstaffs','managestaf','promoteStd','payfeess','findtrans','mpesaTrans','feestruct','expenses_btn','finance_report_btn','routes_n_trans','enroll_students','payroll_sys','humanresource','regsub','managesub','managetrnsub','generate_tt_btn','examanagement','exam_fill_btn','enroll_boarding_btn','maanage_dorm','dashbutn','send_feedback','sms_broadcast','update_school_profile','update_personal_profile','set_btns','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "1") {
        $allowed = ['admitbtn',"findstudsbtn",'callregister','regstaffs','managestaf','promoteStd','payfeess','findtrans','mpesaTrans','feestruct','expenses_btn','finance_report_btn','routes_n_trans','enroll_students','payroll_sys','humanresource','regsub','managesub','managetrnsub','generate_tt_btn','examanagement','exam_fill_btn','enroll_boarding_btn','maanage_dorm','dashbutn','send_feedback','sms_broadcast','update_school_profile','update_personal_profile','set_btns','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "2") {
        $allowed = ['',"",'','','','','','','','','','','','','','','regsub','managesub','managetrnsub','generate_tt_btn','examanagement','exam_fill_btn','','','','','sms_broadcast','','update_personal_profile','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "3") {
        $allowed = ['admitbtn',"findstudsbtn",'','regstaffs','managestaf','','','','','','','','','','','','','','','','','','','','','','','update_school_profile','update_personal_profile','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "4") {
        $allowed = ['admitbtn',"findstudsbtn",'','regstaffs','managestaf','','','','','','','','','','','','','','','','','','','','','','','','update_personal_profile','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "5") {
        $allowed = ['admitbtn',"findstudsbtn",'','','','','payfeess','findtrans','mpesaTrans','feestruct','expenses_btn','finance_report_btn','','','payroll_sys','','','','','','','','','','','','','','','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "6") {
        $allowed = ['',"",'','regstaffs','managestaf','','','','','','','','','','','','','','','','','','','','','send_feedback','sms_broadcast','','','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "7") {
        $allowed = ['',"",'','','','','','','','','','','','','','','regsub','managesub','managetrnsub','generate_tt_btn','examanagement','exam_fill_btn','','','','','','','','','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "8") {
        $allowed = ['',"",'','','','','','','','','','','','','','','','','','generate_tt_btn','','exam_fill_btn','','','','','','','update_personal_profile','',''];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else if ($auth == "9") {
        $allowed = ['admitbtn',"findstudsbtn",'','','','','','','','','','','','','','','','','','','','','','','','','','','','',''];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    } else {
        // get the allowed fields
        // $data .= "<td>". ucwords(strtolower($auth))."</td>";

        // get the allowed for that particular user
        include("connections/conn2.php");
        $select = "SELECT * FROM `settings` WHERE `sett` = 'user_roles';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $valued = [];
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $valued = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
            }
        }
        $allowed = [];
        for ($index=0; $index < count($valued); $index++) { 
            if ($valued[$index]->name == $auth) {
                // get the roles arrays and take the one that has a Status yes
                $roles = $valued[$index]->roles;
                for($in = 0; $in < count($roles); $in++){
                    if ($roles[$in]->Status == "yes") {
                        array_push($allowed,$roles[$in]->name);
                    }
                }
            }
        }
        // $allowed = ['admitbtn',"findstudsbtn",'callregister','regstaffs','managestaf','promoteStd','payfeess','findtrans','mpesaTrans','feestruct','expenses_btn','finance_report_btn','routes_n_trans','enroll_students','payroll_sys','humanresource','regsub','managesub','managetrnsub','generate_tt_btn','examanagement','exam_fill_btn','enroll_boarding_btn','maanage_dorm','dashbutn','send_feedback','sms_broadcast','update_school_profile','update_personal_profile','set_btns','my_reports'];
        return checkPresnt($allowed,$id) ? "" : "d-none";
    }
    return "d-none";
}

function isJson_report($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}

function checkPresnt($array, $string){
    if (count($array)>0) {
        for ($i=0; $i < count($array); $i++) { 
            if ($string == $array[$i]) {
                return true;
            }
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta charset="utf-8">

    <!-- this clears cache so that changes when done to this webpage loads new changes -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- ends here -->


    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="description" content="Ladybird SMIS is a user friendly Management Information System that helps schools manage their school information.">
    <meta name="keywords" content="Ladybird, dashboard, database, SMIS" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=yes">
    <title>Dashboard || <?php
                        if (isset($_SESSION['schoolname'])) {
                            if ($_SESSION['schoolname'] == '0') {
                                echo "School name";
                                header('HTTP/1.1 301 Moved Permanently');
                                header('Location: login.php');
                            } else {
                                echo $_SESSION['schoolname'];
                            }
                        } else {
                            echo "School name";
                            header('HTTP/1.1 301 Moved Permanently');
                            header('Location: login.php');
                        } ?> </title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/ladybird.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/CSS/mainpage.css">
    <link rel="stylesheet" href="assets/CSS/homepage2.css">
    <link rel="stylesheet" href="assets/CSS/font-awesome/css/all.css">
    <!-- GOOGLE FONTS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    
    <!-- the customizable text editor documents -->
        <script src="https://cdn.tiny.cloud/1/if2hs0ax6hmgx2842yuozz7qt8lde0hvc8upqv9gmokdk2id/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- ends here -->

    
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-243578000-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-243578000-1');
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-K5H4YCK02K"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-K5H4YCK02K');
    </script>
    
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="assets/css/homepage2.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <style>
        .iframe_thumbnail {
        width: 100%;
        height: 100px;
        }
        /*the container must be positioned relative:*/
        .autocomplete {
            position: relative;
            display: inline-block;
            width: 100%
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
        .custom_tt{
            all: unset !important;
            padding: 2px !important; 
            border-style: solid !important;
            border-radius: 5px !important;
            border-color: gray !important;
            border-width: 0.5px;
            cursor: pointer !important;
            padding: 2px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-top: 2px;
        }
        .vertical-writing
        {
        writing-mode: vertical-rl;
        text-orientation: upright;
        }
        .image-real-size {
            width: auto;
            height: auto;
            max-width: 20px;
            max-height: 20px;
        }
    </style>

</head>
<?php
    // include the connection
    include("connections/conn2.php");
    // connect to the database and change the classes make them json
    $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $valued = $row['valued'];
            if (!isJson_report($valued)) {
                // if its not json turn it to json
                $valued = explode(",",$valued);

                // create the json
                $classes = [];
                for ($index=0; $index < count($valued); $index++) { 
                    $class_json = new stdClass();
                    $class_json->id = $index+1;
                    $class_json->classes = $valued[$index];

                    // add the class json
                    array_push($classes,$class_json);
                }

                // update the database
                $class_string = json_encode($classes);
                $update = "UPDATE `settings` SET `valued` = ? WHERE `sett` = 'class'";
                $stmt = $conn2->prepare($update);
                $stmt->bind_param("s",$class_string);
                $stmt->execute();
            }
        }
    }
?>
<body>
    <div class="mainpages" id="images_bgs">
        <div class="load_clear hide" id="log_notification">
        </div>
        <div class="dashboard " id="dashme">
            <div class="left_dash">
                <div class="conts menu">
                    <span><img class="menuicon" id="menubtn" src="images/menu.png" alt="menu"></span>
                </div>
                <ul class="d-flex flex-column justify-content-between bg-gray p-2" id="" title="Home">
                    <button class="btn btn-primary btn-sm" id="dash" type="button"><i class="fas fa-home"></i></button>
                </ul>
                <div class="conts fa-xs">
                    <input class="form-control" type="text" id="authoriti" value="<?php echo $_SESSION['auth'] ?>" hidden>
                    <input class="form-control" type="text" id="useriddds" value="<?php echo $_SESSION['userids'] ?>" hidden>
                    <input class="form-control" type="text" id="ct_cg_gc" value="<?php echo $_SESSION['ct_cg'] ?>" hidden>
                    <p style='display:none;' id='nulled'></p>
                    <ul class="dash_menu">
                        <!--<li class="hide_six">Dashboard</li>-->
                        <li class="hide_six" id="feed_back_btns">Send us a feedback</li>
                        <!-- <li class="hide_six hide" >About us</li>
                        <li class="hide_six hide" >Help</li> -->
                    </ul>
                </div>
                <div class="icons">
                    <p class="notifies" id="open_notify"><span><img src="images/notify.png" class='notify' alt="notify"></span><span class="num_notify"><span id="note_2">0</span></span></p>
                    <div class="notification_parent_window animate hide" id="notification_win">
                        <span class="anchors"></span>
                        <div class="ti_tles">
                            <h6>Notifications</h6>
                        </div>
                        <div class="notification_conts" id="notice_list">
                            <div class="notice" title="Click to open message">
                                <p>1. Payments for Hillary Ngige in Class 6</p>
                            </div>
                        </div>
                        <div class="show_all_notices">
                            <p class="link" id="show_all_notices">Show all Notices</p>
                        </div>
                    </div>
                </div>
                <div class="icons">
                    <p class="hide" id="dps_images"></p>
                    <p class="hide" id="sch_dp_images"></p>
                    <img src="images/dp.png" class="dip" alt="dp" id="open_more">
                    <div class="notification_parent_window animate hide" id="per_profile">
                        <span class="anchors"></span>
                        <div class="ti_tles">
                            <h6>
                                <?php if (isset($_SESSION['fullnames'])) {
                                    $salute = "";
                                    if ($_SESSION['gen'] == 'M') {
                                        $salute = 'Mr. ';
                                    } elseif ($_SESSION['gen'] == 'F') {
                                        $salute = 'Mrs. ';
                                    } else {
                                        $salute = "";
                                    }
                                    $named = explode(" ", $_SESSION['fullnames']);
                                    echo "Hello, " . $salute . $named[0];
                                } else {
                                    echo "Username ";
                                } ?></h6>
                        </div>
                        <div class="button_holder" id="">
                            <div class="ss">
                                <button class="good_btn" id="update_my_prof">My profile</button>
                            </div>
                            <div class="ss">
                                <button class="bad_btn" id="logout_1">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="administrationwindow">
                <div class="conts">
                    <button type='button'>Register student <span><img src="images/registerstud.png" alt="admit"></span></button>
                </div>
                <div class="conts">
                    <button type='button'>Manage staff<span><img src="images/manage1.png" alt="admit"></span></button>
                </div>
                <div class="conts">
                    <button type='button'>Manage student<span><img src="images/manage2.png" alt="admit"></span></button>
                </div>
            </div>
            <div class="administrationwindow academics">
                <div class="conts">
                    <button type='button'>Register student <span><img src="images/registerstud.png" alt="admit"></span></button>
                </div>
                <div class="conts">
                    <button type='button'>Manage staff<span><img src="images/manage1.png" alt="admit"></span></button>
                </div>
                <div class="conts">
                    <button type='button'>Manage student<span><img src="images/manage2.png" alt="admit"></span></button>
                </div>
            </div>
        </div>
        <div class="sidebars animate3" id="sideme">
            <div class="conts flexed_column centernpadd">
                <div class="img"><img src="images/board.jpg" id="sch_logos" class="schicon" alt="school"></div>
                <h2 style="font-weight: 800;" class="text-bolder text-uppercase"><?php if (isset($_SESSION['schoolname'])) {
                                                                                        echo $_SESSION['schoolname'];
                                                                                    } else {
                                                                                        echo "School name";
                                                                                    } ?></h2>
            </div>
            <div class="titles">
                <h2>Navigate</h2>
                <span id='closesidebar'>&times</span>
            </div>
            <!--<div class="contsds">
                <button class="navButs">Shortcut <span class="arrow rotate_right"></span> </button>
                <div class="contsd">
                    <div class="contsc hide">
                        <button type="button" id='dashbutn' ><span><img class="icons" src="images/dash.png"></span> Dashboard</button>
                        <button type="button" ><span><img class="icons" src="images/feedback.png"></span>Send feedback</button>
                        <button type='button' ><span><img class="icons" src="images/about.png"></span>About us</button>
                    </div>
                </div>
            </div>-->
            <div class="conts">
                <button class="navButs tr_hides">Administration <span class="arrow rotate_right"></span> </button>
                <div class="contsd">
                    <div class="contsc hide">
                        <button type="button" class="sidebtns <?php echo allowed("admitbtn"); ?> htbtn" id="admitbtn"><span><img class="icons" src="images/register.png"></span> Admit students</button>
                        <button type="button" class="sidebtns <?php echo allowed("findstudsbtn"); ?> tr_hides" id="findstudsbtn"><span><img class="icons" src="images/findstud.png"></span>Manage students</button>
                        <button type='button' class="sidebtns <?php echo allowed("callregister"); ?> tr_hides d-none" id='callregister'><span><img class="icons" src="images/registercall.png"></span>Student Attendance</button>
                        <button type='button' class="sidebtns <?php echo allowed("regstaffs"); ?> htbtn" id='regstaffs'><span><img class="icons" src="images/registerstaff.png"></span>Register staff</button>
                        <button type='button' class="sidebtns <?php echo allowed("managestaf"); ?> htbtn" id='managestaf'><span><img class="icons" src="images/managestaff.png"></span>Manage staff</button>
                        <button type='button' class="sidebtns <?php echo allowed("promoteStd"); ?> htbtn d-none" id='promoteStd'><span><img class="icons" src="images/managestaff.png"></span>Promote Students</button>
                    </div>
                </div>
            </div>
            <div class="conts">
                <button class="navButs htbtn">Finance<span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc hide">
                        <button type='button' class="sidebtns <?php echo allowed("payfeess"); ?> htbtn" id='payfeess'><span><img class="icons" src="images/pay.png"></span>Collect Fees & Revenue</button>
                        <button type='button' class="sidebtns <?php echo allowed("findtrans"); ?> htbtn" id='findtrans'><span><img class="icons" src="images/manage3.png"></span>Manage transaction</button>
                        <button type='button' class="sidebtns <?php echo allowed("mpesaTrans"); ?> htbtn" id='mpesaTrans'><span><img class="icons" src="images/manage3.png"></span>MPESA transactions</button>
                        <button type='button' class="sidebtns <?php echo allowed("feestruct"); ?> htbtn" id='feestruct'><span><img class="icons" src="images/feestructure.png"></span>Fees structure</button>
                        <button type='button' class="sidebtns <?php echo allowed("expenses_btn"); ?> htbtn" id='expenses_btn'><span><img class="icons" src="images/feestructure.png"></span>Expenses & Approvals</button>
                        <button type='button' class="sidebtns <?php echo allowed("expenses_btn"); ?> htbtn" id='supplier_btn'><span><img class="icons" src="images/findstud.png"></span> Supplier Accounts</button>
                        <button type='button' class="sidebtns <?php echo allowed("expenses_btn"); ?> htbtn" id='asset_account_btn'><span><img class="icons" src="images/pay.png"></span> Asset Accounts</button>
                        <button type='button' class="sidebtns <?php echo allowed("finance_report_btn"); ?> htbtn" id='finance_report_btn'><span><img class="icons" src="images/report.png"></span>Financial report</button>
                    </div>
                </div>
            </div>
            <div class="conts d-none">
                <button class="navButs htbtn">Route & Transport<span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc hide">
                        <button type='button' class="sidebtns <?php echo allowed("routes_n_trans"); ?> htbtn" id='routes_n_trans'><span><i class="fas fa-light fa-route text-dark"></i></span>Routes & Vans</button>
                        <button type='button' class="sidebtns <?php echo allowed("enroll_students"); ?> htbtn" id='enroll_students'><span><img class="icons" src="images/manage3.png"></span>Enroll Students</button>
                    </div>
                </div>
            </div>
            <div class="conts d-none">
                <button class="navButs htbtn">Human Resource<span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc hide">
                        <button type='button' class="sidebtns <?php echo allowed("payroll_sys"); ?> htbtn" id='payroll_sys'><span><img class="icons" src="images/report.png"></span>Payroll</button>
                        <button type='button' class="sidebtns <?php echo allowed("humanresource"); ?> htbtn" id='humanresource'><span><img class="icons" src="images/managestaff.png"></span>Human Resource</button>
                    </div>
                </div>
            </div>
            <div class="conts d-none">
                <button class="navButs">Academic<span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc">
                        <button type='button' class="sidebtns <?php echo allowed("regsub"); ?> htbtn" id='regsub'><span><img class="icons" src="images/addsub.png"></span>Register subject</button>
                        <button type='button' class="sidebtns <?php echo allowed("managesub"); ?> htbtn" id='managesub'><span><img class="icons" src="images/managesubs.png"></span>Manage subjects</button>
                        <button type='button' class="sidebtns <?php echo allowed("managetrnsub"); ?> htbtn" id='managetrnsub'><span><img class="icons" src="images/manageteach.png"></span>Manage teacher</button>
                        <button type='button' class="sidebtns <?php echo allowed("generate_tt_btn"); ?> " id='generate_tt_btn'><span><img class="icons" src="images/timetable.png"></span>Timetable</button>
                        <button type='button' class="sidebtns <?php echo allowed("examanagement"); ?> htbtn" id='examanagement'><span><img class="icons" src="images/addmarks.png"></span>Exam Management</button>
                        <button type='button' class="sidebtns <?php echo allowed("exam_fill_btn"); ?> " id='exam_fill_btn'><span><img class="icons" src="images/managemarks.png"></span>Students Marks Entry</button>
                    </div>
                </div>
            </div>
            <div class="conts d-none">
                <button class="navButs htbtn">Boarding<span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc">
                        <button type='button' class="sidebtns <?php echo allowed("enroll_boarding_btn"); ?> htbtn" id='enroll_boarding_btn'><span><img class="icons" src="images/enrollboarding.png"></span>Enroll boarding</button>
                        <button type='button' class="sidebtns <?php echo allowed("maanage_dorm"); ?> htbtn" id='maanage_dorm'><span><img class="icons" src="images/dormitory.png"></span>Manage dormitory</button>
                        <!--<button><span><img class="icons" src="images/information.png"></span>Student information</button>
                        <button><span><img class="icons" src="images/manageinfor.png"></span>Manage information</button>
                        <button><span><img class="icons" src="images/boardingpay.png"></span>Boarding payment</button>-->
                    </div>
                </div>
            </div>
            <div class="contsds">
                <button class="navButs">Feedback <span class="arrow rotate_right"></span> </button>
                <div class="contsd">
                    <div class="contsc hide">
                        <!--<button type="button" id='dashbutn' ><span><img class="icons" src="images/dash.png"></span> Dashboard</button>-->
                        <button type="button" class="sidebtns <?php echo allowed("send_feedback"); ?>" id='send_feedback'><span><img class="icons" src="images/feedback.png"></span>Send feedback</button>
                        <button type='button' class="sidebtns hide d-none htbtn"><span><img class="icons" src="images/about.png"></span>About us</button>
                    </div>
                </div>
            </div>
            <div id="regs" class="conts">
                <!-- class=""  -->
                <button class="navButs htbtn">Email & SMS <span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc">
                        <button type='button' class="sidebtns <?php echo allowed("sms_broadcast"); ?> htbtn" id='sms_broadcast'><span><img class="icons" src="images/broadcast.png"></span>Broadcast Message</button>
                    </div>
                </div>
            </div>
            <div id="regs" class="conts">
                <button class="navButs">Account <span class="arrow rotate_right"></button>
                <div class="contsd">
                    <div class="contsc">
                        <button type='button' class="sidebtns <?php echo allowed("update_school_profile"); ?> htbtn" id='update_school_profile'><span><img class="icons" src="images/updateprofile.png"></span>Update school profile</button>
                        <button type='button' class="sidebtns <?php echo allowed("update_personal_profile"); ?> " id='update_personal_profile'><span><img class="icons" src="images/updateprofile.png"></span>Update personal profile</button>
                        <button type='button' class="sidebtns <?php echo allowed("set_btns"); ?> htbtn" id='set_btns'><span><img class="icons" src="images/settings.png"></span>Settings</button>
                        <button type='button' class="sidebtns <?php echo allowed("my_reports"); ?> htbtn" id='my_reports'><span><i class="fas fa-book text-dark"></i></span>Reports</button>
                        <button id="logout" class="sidebtns" style='color:red'><span><img class="icons" src="images/logout.png"></span>Logout</button>
                        <p class="copyright1">Ladybird SMIS Copyright © 2020 - <?php echo date("Y"); ?> | All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="paneled" id="paneled">

        </div>
        <div class="maincontents animate" id='maincontents'>

            <?php
            if ($_SESSION['auth'] == '0') {
                include("dashboard/admindash.php");
            } elseif ($_SESSION['auth'] == '1') {
                include("dashboard/htdashboard.php");
            } elseif ($_SESSION['auth'] == '2') {
                include("dashboard/teacher.php");
            } elseif ($_SESSION['auth'] == '3') {
                include("dashboard/deputy_dash.php");
            } elseif ($_SESSION['auth'] == '4') {
                include("dashboard/teacher.php");
            } elseif ($_SESSION['auth'] == '5') {
                include("dashboard/classteacherdash.php");
            }else {
                include("dashboard/teacher.php");
            }

            // include administration
            include("administration/administration.php");
            include("administration/departments_manager.php");
            include("administration/completeadm.php");
            include("administration/findstudents.php");
            include("administration/classregister.php");
            include("administration/registerstaff.php");
            include("administration/managestaff.php");
            include("administration/promote.php");
            include("administration/human_resource.php");
            include("administration/leaves.php");
            include("financepages/payfees.php");
            include("financepages/credit_note.php");
            include("financepages/record_school_income.php");
            include("financepages/mpesa_transaction.php");
            include("financepages/findpayment.php");
            include("financepages/feesstructure.php");
            include("financepages/expenses.php");
            include("financepages/payment_approval.php");
            include("financepages/suppliers.php");
            include("financepages/assets.php");
            include("financepages/financial_statements.php");
            include("financepages/payroll.php");
            include("transport/transport_route.php");
            include("transport/enroll_student.php");
            include("academics/regsubjects.php");
            include("academics/subject_selection.php");
            include("academics/managesubs.php");
            include("academics/manageteachsubj.php");
            include("academics/exam_management.php");
            include("academics/exam_filling.php");
            include("academics/timetable.php");
            include("system_error_pages/inconvinience.php");
            include("boarding_pages/register_dorm.php");
            include("boarding_pages/enroll_boarding.php");
            include("main_pages/system_profile.php");
            include("main_pages/personal_profile.php");
            include("main_pages/notification_page.php");
            include("main_pages/settings.php");
            include("main_pages/logsview.php");
            include("main_pages/send_feedback.php");
            include("main_pages/my_report.php");
            include("feesprint.php");
            include("results_print.php");
            include("fees_reminder.php");
            include("fees_struct.php");
            include("sms_apis/sendsms.php");
            include("charttest/chartstest.php");
            ?>

        </div>
        <div class="loadwindow hide" id="loadings">
            <div class="loadingcontents">
                <img src="images/load2.gif" alt="loading">
            </div>
        </div>
        <div class="dialogholder hide" id="dialogholder1">
            <div class="dialogwindow animate2">
                <h6>Confirm</h6>
                <div class="message" id="message">
                    <p>Are you sure ?</p>
                </div>
                <div class="buttons">
                    <button type='button' id='clasregyes'>Yes</button>
                    <button type='button' id='clasregno'>No</button>
                </div>
            </div>
        </div>
        <div class="dialogholder hide" id="dialogholder2">
            <div class="dialogwindow animate2">
                <h6>Confirm Delete</h6>
                <div class="message" id="">
                    <p>Are you sure you want to save changes made in <b id="timetable_title"></b> ?</p>
                </div>
                <div class="buttons">
                    <button type='button' id='save_changestt_yees'>Yes</button>
                    <button type='button' id='save_changestt_no'>No</button>
                </div>
            </div>
        </div>
        <div class="dialogholder hide" id="change_description_name">
            <div class="dialogwindow animate2">
                <h6 class="text-center">Change Description</h6>
                <div class="container">
                    <input type="hidden" id="change_description_id">
                    <label for="label_desc" class="form-control-label">New Payment Description</label>
                    <textarea id="label_desc" cols="30" rows="3" maxlength="300" class="form-control" placeholder="Direct deposit to KCB account 1230090019"></textarea>
                </div>
                <div class="buttons">
                    <button type='button' id='save_changes_new_pd'>Save</button>
                    <button type='button' id='cancel_save_changes_new_pd'>Cancel</button>
                </div>
            </div>
        </div>

        <div class="dialogholder hide" id="delete_pd_desc_win">
            <div class="dialogwindow animate2">
                <h6>Confirm Delete</h6>
                <div class="message" id="">
                    <input type="hidden" name="" id="get_pd_index">
                    <p>Are you sure you want to delete <b id="description_index"></b> ?</p>
                </div>
                <div class="buttons">
                    <button type='button' id='yes_delete_pd'>Yes</button>
                    <button type='button' id='no_delete_pd'>No</button>
                </div>
            </div>
        </div>

        <div class="changepasswindow hide" id='changepasswin'>
            <div class="changepass animate" id='changepass'>
                <div class="conts">
                    <h6>Change password</h6>
                </div>
                <div class="passwindows row" id='passwindows'>
                    <div class="contse col-md-6">
                        <p>You are previledged to change user`s passwords without the system asking for their current password</p>
                        <p>If you proceed you will change <span id='namesdd'>users</span> password</p>
                        <div class="contsbt">
                            <button type='button' id='proceed'>Proceed!</button>
                            <button type='button' id='cancelchngebtn1'>Cancel</button>
                        </div>
                    </div>
                    <div class="changepass1 col-md-6">
                        <p id='passworderrors'></p>
                        <div class="conts">
                            <label class="form-control-label" for="enterpass">Enter password: <br></label>
                            <input class="form-control w-75 px-2 text-xxs" type="password" name="enterpass" id="enterpass" placeholder='Enter Password'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="reenterpass">Re-enter password: <br></label>
                            <input class="form-control w-75 px-2 text-xxs" type="password" name="reenterpass" id="reenterpass" placeholder='Re-enter password'>
                        </div>
                        <div class="contsbt">
                            <button type='button' id='changebtns'>Change password</button>
                            <button type='button' id='cancelchngebtn2'>Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='confirmpayments'>
            <div class="confirmpayment animate">
                <h6 id="switch_confirmation" class='text-center'>Confirm Payment</h6>
                <p id="title_confirmation">Are you sure you want to make payment for <b><span id="nameofstudents"></span></b>?</p>
                <p><label class="form-control-label" for="check-parents-sms"><b>Send the parents SMS ?</b> <span class="text-danger" id="send_sms_dsiclaimer"></span></label>
                    <!-- <input type="checkbox" id="check-parents-sms">  -->
                    <select class="form-control p-1" name="check-parents-sms" id="check-parents-sms">
                        <option value="" hidden>Select who to send SMS</option>
                        <option value="first_parent">Primary Parent</option>
                        <option value="second_parent">Second Parent</option>
                        <option value="both_parent">Both Parent</option>
                        <option value="none">None</option>
                    </select>
                </p>
                <form method="POST" action="reports/reports.php" target="_blank">
                    <p>
                        <label for="reciept_size" class="form-control-label"><b>Select receipt size</b></label>
                        <select name="reciept_size" id="reciept_size" class="form-control">
                            <option value="" hidden>Select reciept Size</option>
                            <option value="A4">A4 Size Full</option>
                            <option value="A51">A5 Size Sample 1</option>
                            <option value="thermal1">Thermal paper size 80mm Sample 1</option>
                        </select>
                    </p>
                    <input type="hidden" name="student_admission_no" id="student_admission_no">
                    <input type="hidden" name="amount_paid_by_student" id="amount_paid_by_student">
                    <input type="hidden" name="new_student_balance" id="new_student_balance">
                    <input type="hidden" name="mode_of_payments" id="mode_of_payments">
                    <input type="hidden" name="transaction_codes" id="transaction_codes">
                    <input type="hidden" name="payments_for" id="payments_for">
                    <input type="hidden" name="students_names" id="students_names">
                    <input type="hidden" id="reprint" name="reprint">
                    <input type="hidden" id="masiku" name="masiku">
                    <input type="hidden" id="masaa" name="masaa">
                    <input type="hidden" name="last_receipt_id_take" id="last_receipt_id_take">
                    <input type="hidden" value="fees_payment_receipt"  name="fees_payment_receipt" id="fees_payment_receipt">
                    <input type="hidden" value="<?php echo date("Y-m-d");?>" name="date_of_payments_fees" id="date_of_payments_fees_holder">
                    <input type="hidden" value="<?php echo date("H:i");?>" name="time_of_payment_fees" id="time_of_payment_fees_holder">
                    <input type="hidden" value="auto" name="fees_payment_opt_holder" id="fees_payment_opt_holder">
                    <input type="hidden" name="supporting_documents_list" value="[]" id="supporting_documents_list">
                    <button hidden id="submit_receipt_printing" type="submit">Submit</button>
                </form>
                <div class="btns">
                    <button type='button' id='confirmyes'>Yes</button>
                    <button type='button' id='confirmno'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='payment_details_window'>
            <div class="confirmpayment w-50 animate" style="overflow: auto;">
                <h6 id="switch_confirmation" class='text-center'><u>Payment Details</u></h6>
                <table class="table">
                    <tr><th><b>Description:</b></th><th><span>Value</span></th></tr>
                    <tr><td><b>Paid By:</b></td><td><span id="payment_description_2">Value</span></td></tr>
                    <tr><td><b>Payment Date:</b></td><td><span id="payment_description_3">Value</span></td></tr>
                    <tr><td><b>Mode:</b></td><td><span id="payment_description_4">Value</span></td></tr>
                    <tr><td><b>Amount:</b></td><td><span id="payment_description_5">Value</span></td></tr>
                    <tr><td><b>Balance:</b></td><td><span id="payment_description_6">Value</span></td></tr>
                    <tr><td><b>Purpose:</b></td><td><span id="payment_description_7">Value</span></td></tr>
                </table>
                <hr>
                <h6 class="text-center"><u>Supporting Documents</u></h6>
                <ul class="list-group" id="supporting_documents_list_holder" style="overflow: auto; max-height: 200px;">
                    <li class="list-group-item"><a href="#" target="_blank" class="link">Link 1</a></li>
                    <li class="list-group-item">Item 2</li>
                    <li class="list-group-item">Item 3</li>
                </ul>
                <div class="btns">
                    <button type='button' id='payment_information_no'>Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='message_details_window'>
            <div class="confirmpayment w-50 animate" style="overflow: auto;">
                <h6 id="switch_confirmation" class='text-center'><u>Message Details</u></h6>
                <table class="table">
                    <tr><th><b>Description:</b></th><th><span>Value</span></th></tr>
                    <tr><td><b>Recipients:</b></td><td><span id="message_recipients">Value</span></td></tr>
                    <tr><td><b>Message Content:</b></td><td><span id="message_contents_view">Value</span></td></tr>
                    <tr><td><b>Date Sent:</b></td><td><span id="date_sent_view">Value</span></td></tr>
                </table>
                <hr>
                <div class="btns">
                    <button type='button' id='close_message_details'>Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='confirm_transaction_delete'>
            <div class="confirmpayment animate">
                <h6 class='text-center'>Delete Payment <img class="hide" src="images/ajax_clock_small.gif" id="load_delete_payments"></h6>
                <p>Are you sure you want to delete <b><span id="transaction_owner"></span></b>`s payment for: <b id="payments_for_info"></b> made on <b id="date_of_payments"></b>, Amount: <b id="amounts_paid_trans"></b> ?</p>
                <input type="hidden" id="transaction_pay_id">
                <p id="delete_pay_err_handlers"></p>
                <div class="btns">
                    <button type='button' id='confirm_delete_trans_yes'>Yes</button>
                    <button type='button' id='confirm_delete_trans_no'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='confirm_revenue_delete'>
            <div class="confirmpayment animate">
                <h6 class='text-center'>Delete Revenue <img class="hide" src="images/ajax_clock_small.gif" id="load_delete_revenue"></h6>
                <p>Are you sure you want to delete <b><span id="revenue_name_holder"></span></b>`s record of <b id="revenue_date_of_recording"></b>, Amount: <b id="revenue_amount_recorded"></b> ?</p>
                <input type="hidden" id="revenue_id_delete">
                <p id="delete_pay_err_handlers"></p>
                <div class="btns">
                    <button type='button' id='confirm_delete_revenue'>Yes</button>
                    <button type='button' id='confirm_Delete_revenue_no'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="latest_updates_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_latest_updates_window">&times</p>
                    <h6 class="text-center"><b>Latest Updates</b></h6>
                </div>
                <div class="conts" id="">
                    <div class="add_expense" id="">
                        <div class="conts">
                        <h6><u>💫 Changes</u></h6>
                            <ul>
                                <li><b>🌟 Generate Excel Reports</b> -: Along-side PDF reports that you can generate from the system, you can now generate Excel reports!</li>
                            </ul>
                            <h6><u>😎 Why do you need it?</u></h6>
                            <ul>
                                <li>👉🏻 Spreadsheet Functionality: Excel provides a familiar and user-friendly spreadsheet interface that allows you to organize, manipulate, and analyze data effectively. It offers a wide range of features such as sorting, filtering, conditional formatting, formulas, charts, and pivot tables, enabling you to perform complex data management tasks.</li>
                                <li>👉🏻 Data Analysis: Excel provides powerful tools for data analysis. You can use built-in functions and formulas to perform calculations, manipulate data, and derive meaningful insights. Excel's data analysis capabilities, such as sorting, filtering, and conditional formatting, allow you to identify patterns, trends, and outliers in your data.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="btns">
                        <!-- <button type="button" id="save_add_expense">Save</button> -->
                        <button type="button" id="close_latest_updates_window_2">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='confirm_del_leav_applic'>
            <div class="confirmpayment animate">
                <h6 class='text-center'>Delete Leave Application <img class="hide" src="images/ajax_clock_small.gif" id="load_delete_leave_app"></h6>
                <h6>Are you sure you want to delete this Leave Application?</h6>
                <b class="text-danger">Note:</b>
                <p class="">"Its important to note that when this leave Application is deleted your staff is going to regain back the leave days applied for if it was accepted!"</p>
                <p id="delete_leave_err_handlers"></p>
                <div class="btns">
                    <button type='button' id='confirm_delete_leave_apply_yes'>Yes</button>
                    <button type='button' id='confirm_delete_leave_apply_no'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='remove_roles_windows'>
            <div class="confirmpayment animate">
                <p class="hide" id="index_to_delete"></p>
                <h6>Confirm Role Removal</h6>
                <p>Are you sure you want to remove this role?</p>
                <div class="btns">
                    <button type='button' id='confirmyes_roled'>Yes</button>
                    <button type='button' id='confirmno_roled'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='regenerate_timetables'>
            <div class="confirmpayment animate">
                <h6 class="text-center">Regenerate Timetable <img class="hide" src="images/ajax_clock_small.gif" id="load_regen_tt"></img></h6>
                <input type="hidden" id="regen_id">
                <p class="text-danger p-1 border border-secondary my-1"><b>Note:</b> <br> All customizations that has been made will be lost</p>
                <p>Are you sure you want to regenerate <b id="regen_tts"></b>?</p>
                <div class="btns">
                    <button type='button' id='confirm_regen'>Confirm</button>
                    <button type='button' id='cancel_regen'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='previous_schools_windows'>
            <div class="changesubwindow animate">
                <h6 class="text-center"><b>Add previous Schools</b></h6>
                <div class="container p-0">
                    <label for="prev_school_name" class="form-control-label">Previous School Name:</label>
                    <input type="text" class="form-control" name="prev_school_name" id="prev_school_name" placeholder="Previous school name" >
                    
                    <label for="date_left" class="form-control-label">Date left:</label>
                    <input type="date" class="form-control" name="date_left" id="date_left" placeholder="Previous school name" >
                    
                    <label for="marks_scored" class="form-control-label">Marks / Grade Scored:</label>
                    <input type="text" class="form-control" name="marks_scored" id="marks_scored" placeholder="Marks / Grades Scored" >
                    <hr class="py-0 my-1">
                    <label for="leaving_certifcate" class="form-control-label mt-2">Leaving Certificate</label>
                    <input type="checkbox" class="mb-3" name="leaving_certifcate" id="leaving_certifcate" placeholder="Previous school name" >
                    <br>
                    <hr class="py-0 my-1">
                    <label for="description" class="form-control-label">Reason For Leaving:</label>
                    <textarea name="description" id="description" cols="15" rows="5" class="form-control mx-auto border border-dark" placeholder="Reason for leaving"></textarea>
                </div>
                <div class="container">
                    <p id="add_prevsch_error"></p>
                </div>
                <div class="btns">
                    <button type='button' id='add_prev_sch_btn'>Add</button>
                    <button type='button' id='canc_add_prev_sch_btn'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='previous_schools_windows_edit'>
            <div class="changesubwindow animate">
                <h6 class="text-center"><b>Add previous Schools</b></h6>
                <div class="container p-0">
                    <label for="prev_school_name_edits" class="form-control-label">Previous School Name:</label>
                    <input type="text" class="form-control" name="prev_school_name_edits" id="prev_school_name_edits" placeholder="Previous school name" >
                    
                    <label for="date_left_edit" class="form-control-label">Date left:</label>
                    <input type="date" class="form-control" name="date_left_edit" id="date_left_edit" placeholder="Previous school name" >
                    
                    <label for="marks_scored_edit" class="form-control-label">Marks / Grade Scored:</label>
                    <input type="text" class="form-control" name="marks_scored_edit" id="marks_scored_edit" placeholder="Marks / Grades Scored" >
                    <hr class="py-0 my-1">
                    <label for="leaving_certifcate_edit" class="form-control-label mt-2">Leaving Certificate</label>
                    <input type="checkbox" class="mb-3" name="leaving_certifcate_edit" id="leaving_certifcate_edit" placeholder="Previous school name" >
                    <br>
                    <hr class="py-0 my-1">
                    <label for="description_edit" class="form-control-label">Reason For Leaving:</label>
                    <textarea name="description_edit" id="description_edit" cols="15" rows="5" class="form-control mx-auto border border-dark" placeholder="Reason for leaving"></textarea>
                </div>
                <div class="container">
                    <p id="add_prevsch_error_edit"></p>
                </div>
                <div class="btns">
                    <button type='button' id='add_prev_sch_btn_edit'>Add</button>
                    <button type='button' id='canc_add_prev_sch_btn_edit'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='allowance_window'>
            <div class="confirmpayment animate">
                <h6 class="text-center">Add Allowance</h6>
                <div class="form-group">
                    <label for="allowance_name" class="form-control-label">Allowance Name</label>
                    <input type="text" class="form-control" placeholder="Allowance Name" id="allowance_name">
                </div>
                <div class="form-group my-2">
                    <label for="allowance_amounts" class="form-control-label">Allowance Amounts</label>
                    <input type="number" class="form-control" placeholder="Allowance Amounts" id="allowance_amounts">
                </div>
                <p id="allowance_err1_handler"></p>
                <div class="btns">
                    <button type='button' id='add_allowances'>Add</button>
                    <button type='button' id='cancel_allowances'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='allowance_window2'>
            <div class="confirmpayment animate">
                <h6 class="text-center">Add Allowance</h6>
                <div class="form-group">
                    <label for="allowance_name2" class="form-control-label">Allowance Name</label>
                    <input type="text" class="form-control" placeholder="Allowance Name" id="allowance_name2">
                </div>
                <div class="form-group my-2">
                    <label for="allowance_amounts2" class="form-control-label">Allowance Amounts</label>
                    <input type="number" class="form-control" placeholder="Allowance Amounts" id="allowance_amounts2">
                </div>
                <p id="allowance_err2_handler"></p>
                <div class="btns">
                    <button type='button' id='add_allowances2'>Add</button>
                    <button type='button' id='cancel_allowances2'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='deductions_window'>
            <div class="confirmpayment animate">
                <h6 class="text-center">Add Deductions</h6>
                <div class="form-group">
                    <label for="deduction_name" class="form-control-label">Select Deduction</label>
                    <select name="deduction_type" id="deduction_type" class="form-control">
                        <option id="select_an_option_deduction" value="" hidden>Select an option</option>
                        <option value="N.I.T.A">N.I.T.A</option>
                        <option value="Absent Days">Absent Days</option>
                        <option value="Absent Hours">Absent Hours</option>
                        <option value="Losses & Damages">Losses & Damages</option>
                        <option value="Mortgage">Mortgage</option>
                        <option value="Fees Credit Note">Fees Credit Note</option>
                        <option value="define_new_entry">Define New deduction</option>
                    </select>
                    <input type="text" class="form-control w-75 hide my-2" placeholder="Deduction Name" id="deduction_name">
                </div>
                <div class="form-group my-2">
                    <label for="deduction_amount" class="form-control-label">Deduction Amounts</label>
                    <input type="number" class="form-control w-75" placeholder="Deduction Amounts" id="deduction_amount">
                </div>
                <p id="deduction_error"></p>
                <div class="btns">
                    <button type='button' id='add_deductions_in'>Add</button>
                    <button type='button' id='cancel_deductions'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='deductions_window_1'>
            <div class="confirmpayment animate">
                <h6 class="text-center">Add Deductions</h6>
                <div class="form-group">
                    <label for="deduction_name_1" class="form-control-label">Select Deduction</label>
                    <select name="deduction_type_1" id="deduction_type_1" class="form-control">
                        <option id="select_an_option_deduction_1" value="" hidden>Select an option</option>
                        <option value="N.I.T.A">N.I.T.A</option>
                        <option value="Absent Days">Absent Days</option>
                        <option value="Absent Hours">Absent Hours</option>
                        <option value="Losses & Damages">Losses & Damages</option>
                        <option value="Mortgage">Mortgage</option>
                        <option value="Fees Credit Note">Fees Credit Note</option>
                        <option value="define_new_entry">Define New deduction</option>
                    </select>
                    <input type="text" class="form-control w-75 hide my-2" placeholder="Deduction Name" id="deduction_name_1">
                </div>
                <div class="form-group my-2">
                    <label for="deduction_amount_1" class="form-control-label">Deduction Amounts</label>
                    <input type="number" class="form-control w-75" placeholder="Deduction Amounts" id="deduction_amount_1">
                </div>
                <p id="deduction_error_1"></p>
                <div class="btns">
                    <button type='button' id='add_deductions_in_1'>Add</button>
                    <button type='button' id='cancel_deductions_1'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='add_user_role_window'>
            <div class="changesubwindow animate">
                <h6 class="text-center">Add User Roles</h6>
                <div class="form-group">
                    <label for="role_name" class="form-control-label">Role Name</label>
                    <input type="text" class="form-control" placeholder="Role Name" id="role_name">
                </div>
                <label for="" class="form-control-label">Select Tasks the user will do.</label>
                <div class="form-group my-2">
                    <div class='classlist2 form-control' style='height:200px;overflow:auto;' name='' id=''>
                        <!-- administration section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_administration'><b>Administration Section</b></label>
                            <input class='' type='checkbox' name='all_administration' id='all_administration'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='admit_student_sect'>1. Admit Students</label>
                            <input class='administration1' type='checkbox' name='admit_student_sect' id='admit_student_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_stud_sect'>2. Manage Students</label>
                            <input class='administration1' type='checkbox' name='manage_stud_sect' id='manage_stud_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='class_attendance_sect'>3. Class Attendance</label>
                            <input class='administration1' type='checkbox' name='class_attendance_sect' id='class_attendance_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='register_staff_sect'>4. Register Staff</label>
                            <input class='administration1' type='checkbox' name='register_staff_sect' id='register_staff_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_staff_sect'>5. Manage Staff</label>
                            <input class='administration1' type='checkbox' name='manage_staff_sect' id='manage_staff_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='promote_students_sect'>6. Promote Student</label>
                            <input class='administration1' type='checkbox' name='promote_students_sect' id='promote_students_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='human_resource_sect'>7. Human Resource</label>
                            <input class='administration1' type='checkbox' name='human_resource_sect' id='human_resource_sect'>
                        </div>
                        <hr>
                        <!-- staft of finance section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_finance_sect'><b>Finance Section</b></label>
                            <input class='' type='checkbox' name='all_finance_sect' id='all_finance_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='pay_fees-sector'>1. Pay Fees</label>
                            <input class='finance1' type='checkbox' name='pay_fees-sector' id='pay_fees-sector'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_transaction_sect'>2. Manage Transaction</label>
                            <input class='finance1' type='checkbox' name='manage_transaction_sect' id='manage_transaction_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='mpesa_transaction_sect'>3. M-Pesa Transactions</label>
                            <input class='finance1' type='checkbox' name='mpesa_transaction_sect' id='mpesa_transaction_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='fees_structures_sect'>4. Fees Structure</label>
                            <input class='finance1' type='checkbox' name='fees_structures_sect' id='fees_structures_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='expense_section'>5. Expense</label>
                            <input class='finance1' type='checkbox' name='expense_section' id='expense_section'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='financial_report_section'>6. Financial Reports</label>
                            <input class='finance1' type='checkbox' name='financial_report_section' id='financial_report_section'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='payroll_section'>7. Payroll</label>
                            <input class='finance1' type='checkbox' name='payroll_section' id='payroll_section'>
                        </div>
                        <hr>
                        <!-- staft of finance section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='route_transport_section'><b>Route & Transport</b></label>
                            <input class='' type='checkbox' name='route_transport_section' id='route_transport_section'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='route_n_van_sect'>1. Route & Vans</label>
                            <input class='routesnvans1' type='checkbox' name='route_n_van_sect' id='route_n_van_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='enroll_students_sect'>2. Enroll Students</label>
                            <input class='routesnvans1' type='checkbox' name='enroll_students_sect' id='enroll_students_sect'>
                        </div>
                        <hr>
                        <!-- start of academic section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='academic_section'><b>Academic Section</b></label>
                            <input class='' type='checkbox' name='academic_section' id='academic_section'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='register_subject_sect'>1. Register Subject</label>
                            <input class='academic_sect' type='checkbox' name='register_subject_sect' id='register_subject_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_subject_sect'>2. Manage Subject</label>
                            <input class='academic_sect' type='checkbox' name='manage_subject_sect' id='manage_subject_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_teacher_sect'>3. Manage Teachers</label>
                            <input class='academic_sect' type='checkbox' name='manage_teacher_sect' id='manage_teacher_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='timetables_sect'>4. Timetable</label>
                            <input class='academic_sect' type='checkbox' name='timetables_sect' id='timetables_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='exam_management_sect'>5. Exam Management</label>
                            <input class='academic_sect' type='checkbox' name='exam_management_sect' id='exam_management_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='student_marks_entry'>6. Student Marks Entry</label>
                            <input class='academic_sect' type='checkbox' name='student_marks_entry' id='student_marks_entry'>
                        </div>
                        <hr>
                        <!-- staft of Boarding section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_boarding_section'><b>Boarding Section</b></label>
                            <input class='' type='checkbox' name='all_boarding_section' id='all_boarding_section'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='enroll_boarding_sect'>1. Enroll Boarding</label>
                            <input class='boarding_sect' type='checkbox' name='enroll_boarding_sect' id='enroll_boarding_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_dormitory_sect'>2. Manage Dormitory</label>
                            <input class='boarding_sect' type='checkbox' name='manage_dormitory_sect' id='manage_dormitory_sect'>
                        </div>
                        <hr>
                        <!-- staft of sms section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_sms_check'><b>Email & SMS Section</b></label>
                            <input class='' type='checkbox' name='all_sms_check' id='all_sms_check'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='sms_and_broadcast'>1. SMS & Broadcast</label>
                            <input class='sms_broadcasted' type='checkbox' name='sms_and_broadcast' id='sms_and_broadcast'>
                        </div>
                        <hr>
                        <!-- staft of sms section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='accounts_sector'><b>Accounts Section</b></label>
                            <input class='' type='checkbox' name='accounts_sector' id='accounts_sector'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='update_school_profile_sect'>1. Update School Profile</label>
                            <input class='accounts_section' type='checkbox' name='update_school_profile_sect' id='update_school_profile_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='update_personal_profile_sect'>2. Update Personal Profile</label>
                            <input class='accounts_section' type='checkbox' name='update_personal_profile_sect' id='update_personal_profile_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='settings_sect'>3. Settings</label>
                            <input class='accounts_section' type='checkbox' name='settings_sect' id='settings_sect'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='my_school_reports'>4. Reports</label>
                            <input class='accounts_section' type='checkbox' name='my_school_reports' id='my_school_reports'>
                        </div>
                    </div>
                </div>
                <p id="allowance_err3_handler"></p>
                <p class="hide" id="add_user_roles_in"><img src="images/ajax_clock_small.gif" id=""> Loading... </p>
                <div class="btns">
                    <button type='button' id='add_role_btns'>Add</button>
                    <button type='button' id='cancel_role_btn'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='add_user_role_window2'>
            <div class="changesubwindow animate">
                <h6 class="text-center">Edit User Roles</h6>
                <div class="form-group">
                    <label for="role_name2" class="form-control-label">Role Name {<span id="old_role_name"></span> }</label>
                    <input type="text" class="form-control" placeholder="Role Name" id="role_name2">
                </div>
                <label for="" class="form-control-label">Select Tasks the user will do.</label>
                <p class="hide" id="role_ids_in"></p>
                <div class="form-group my-2">
                    <div class='classlist2 form-control' style='height:200px;overflow:auto;' name='' id=''>
                        <!-- administration section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_administration2'><b>Administration Section</b></label>
                            <input class='' type='checkbox' name='all_administration2' id='all_administration2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='admit_student_sect2'>1. Admit Students</label>
                            <input class='fill_data administration12' type='checkbox' name='admit_student_sect2' id='admit_student_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_stud_sect2'>2. Manage Students</label>
                            <input class='fill_data administration12' type='checkbox' name='manage_stud_sect2' id='manage_stud_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='class_attendance_sect2'>3. Class Attendance</label>
                            <input class='fill_data administration12' type='checkbox' name='class_attendance_sect2' id='class_attendance_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='register_staff_sect2'>4. Register Staff</label>
                            <input class='fill_data administration12' type='checkbox' name='register_staff_sect2' id='register_staff_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_staff_sect2'>5. Manage Staff</label>
                            <input class='fill_data administration12' type='checkbox' name='manage_staff_sect2' id='manage_staff_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='promote_students_sect2'>6. Promote Student</label>
                            <input class='fill_data administration12' type='checkbox' name='promote_students_sect2' id='promote_students_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='human_resource_sect2'>7. Human Resource</label>
                            <input class='fill_data administration12' type='checkbox' name='human_resource_sect2' id='human_resource_sect2'>
                        </div>
                        <hr>
                        <!-- staft of finance section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_finance_sect2'><b>Finance Section</b></label>
                            <input class='' type='checkbox' name='all_finance_sect2' id='all_finance_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='pay_fees-sector2'>1. Pay Fees</label>
                            <input class='fill_data finance12' type='checkbox' name='pay_fees-sector2' id='pay_fees-sector2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_transaction_sect2'>2. Manage Transaction</label>
                            <input class='fill_data finance12' type='checkbox' name='manage_transaction_sect2' id='manage_transaction_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='mpesa_transaction_sect2'>3. M-Pesa Transactions</label>
                            <input class='fill_data finance12' type='checkbox' name='mpesa_transaction_sect2' id='mpesa_transaction_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='fees_structures_sect2'>4. Fees Structure</label>
                            <input class='fill_data finance12' type='checkbox' name='fees_structures_sect2' id='fees_structures_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='expense_section2'>5. Expense</label>
                            <input class='fill_data finance12' type='checkbox' name='expense_section2' id='expense_section2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='financial_report_section2'>6. Financial Reports</label>
                            <input class='fill_data finance12' type='checkbox' name='financial_report_section2' id='financial_report_section2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='payroll_section2'>7. Payroll</label>
                            <input class='fill_data finance12' type='checkbox' name='payroll_section2' id='payroll_section2'>
                        </div>
                        <hr>
                        <!-- staft of finance section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='route_transport_section2'><b>Route & Transport</b></label>
                            <input class='' type='checkbox' name='route_transport_section2' id='route_transport_section2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='route_n_van_sect2'>1. Route & Vans</label>
                            <input class='fill_data routesnvans12' type='checkbox' name='route_n_van_sect2' id='route_n_van_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='enroll_students_sect2'>2. Enroll Students</label>
                            <input class='fill_data routesnvans12' type='checkbox' name='enroll_students_sect2' id='enroll_students_sect2'>
                        </div>
                        <hr>
                        <!-- start of academic section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='academic_section2'><b>Academic Section</b></label>
                            <input class='' type='checkbox' name='academic_section2' id='academic_section2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='register_subject_sect2'>1. Register Subject</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='register_subject_sect2' id='register_subject_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_subject_sect2'>2. Manage Subject</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='manage_subject_sect2' id='manage_subject_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_teacher_sect2'>3. Manage Teachers</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='manage_teacher_sect2' id='manage_teacher_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='timetables_sect2'>4. Timetable</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='timetables_sect2' id='timetables_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='exam_management_sect2'>5. Exam Management</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='exam_management_sect2' id='exam_management_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='student_marks_entry2'>6. Student Marks Entry</label>
                            <input class='fill_data academic_sect2' type='checkbox' name='student_marks_entry2' id='student_marks_entry2'>
                        </div>
                        <hr>
                        <!-- staft of Boarding section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_boarding_section2'><b>Boarding Section</b></label>
                            <input class='' type='checkbox' name='all_boarding_section2' id='all_boarding_section2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='enroll_boarding_sect2'>1. Enroll Boarding</label>
                            <input class='fill_data boarding_sect2' type='checkbox' name='enroll_boarding_sect2' id='enroll_boarding_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='manage_dormitory_sect2'>2. Manage Dormitory</label>
                            <input class='fill_data boarding_sect2' type='checkbox' name='manage_dormitory_sect2' id='manage_dormitory_sect2'>
                        </div>
                        <hr>
                        <!-- staft of sms section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='all_sms_check2'><b>Email & SMS Section</b></label>
                            <input class='' type='checkbox' name='all_sms_check2' id='all_sms_check2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='sms_and_broadcast2'>1. SMS & Broadcast</label>
                            <input class='fill_data sms_broadcasted2' type='checkbox' name='sms_and_broadcast2' id='sms_and_broadcast2'>
                        </div>
                        <hr>
                        <!-- staft of sms section -->
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:15px;' for='accounts_sector2'><b>Accounts Section</b></label>
                            <input class='' type='checkbox' name='accounts_sector2' id='accounts_sector2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='update_school_profile_sect2'>1. Update School Profile</label>
                            <input class='fill_data accounts_section2' type='checkbox' name='update_school_profile_sect2' id='update_school_profile_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='update_personal_profile_sect2'>2. Update Personal Profile</label>
                            <input class='fill_data accounts_section2' type='checkbox' name='update_personal_profile_sect2' id='update_personal_profile_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='settings_sect2'>3. Settings</label>
                            <input class='fill_data accounts_section2' type='checkbox' name='settings_sect2' id='settings_sect2'>
                        </div>
                        <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                            <label style='margin-right:5px;cursor:pointer;font-size:13px;' for='my_school_reports2'>4. Reports</label>
                            <input class='fill_data accounts_section' type='checkbox' name='my_school_reports2' id='my_school_reports2'>
                        </div>
                    </div>
                </div>
                <p id="allowance_err4_handler"></p>
                <p class="hide" id="add_user_roles_in2"><img src="images/ajax_clock_small.gif" id=""> Loading... </p>
                <div class="btns">
                    <button type='button' id='add_role_btns2'>Update</button>
                    <button type='button' id='cancel_role_btn2'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="changesubjclass">
            <div class="changesubwindow animate">
                <div class="conts">
                    <p class="funga" id="closed">&times</p>
                    <h5 class="text-center"><b>Edit classes</b></h5>
                </div>
                <div class="conts" style='text-align:center;'>
                    <label class="form-control-label" for="namesub">Name of the subject: <br> </label>
                    <input class="form-control" type="text" style='text-align:center;' value="Kiswahili" name="namesub" id="namesub" readonly placeholder="Name of subject">
                </div>
                <p class='hide' id='useridentity'></p>
                <p class='hide' id='subidentity'></p>
                <div class="conts" id="clicker">
                    <p><span class="butns" style='font-size:13px;' id="knows">- Here is what I need to know</span></p>
                </div>
                <div class="informed hide" id="informations">
                    <p>- Below is class lists that the teacher teaches and the available classes that are not assigned a teacher.</p>
                    <p>- Select the classes you want the teacher to teach, if no class is selected the teacher will be cleared from the list of available teachers that teach that subject</p>
                </div>
                <div class="conts">
                    <label class="form-control-label">Classes:</label>
                    <p id='claslistd'></p>
                </div>
                <div class="conts">
                    <p id='geterrors'></p>
                </div>
                <div class="btns">
                    <button type="button" id='changeclasslist'>Change</button>
                    <button type="button" id="cancelclasschange">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="add_memebers_dept_window">
            <div class="changesubwindow animate">
                <div class="conts">
                    <p class="funga" id="close_window_add_member_dept">&times</p>
                    <h5 class="text-center"><b>Add Members</b></h5>
                </div>
                <div class="container">
                    <p>
                        <b>Note:</b> <br> 
                        - Add members from the list below. <br>
                        - The list below will show the members that have not been assigned a department.
                    </p>
                    <hr class="w-50 mx-auto">
                    <label for="members_list_selected" class="form-label"><b>Members List</b><img class="hide" src="images/ajax_clock_small.gif" id="add_members_dept_loader"></label><br>
                    <label id="select_all_members_dept">Select All <input class="" type="checkbox" id="select_all_dept"></label>
                    <div id="member_list_window">
                        <div class="classlist">
                            <div class="checkboxholder" style="margin:10px 0;padding:0px 0px;">
                                <label style="margin-right:5px;cursor:pointer;font-size:12px;" for="">Grade 8</label>
                                <input class="update_expense_check" type="checkbox" id="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="[]" id="members_lists">
                    <span class="hide" id="show_dept_lists"></span>
                    <p id="display_dept_message"></p>
                </div>
                <div class="btns">
                    <button type="button" id='add_names_inside'>Add</button>
                    <button type="button" id="close_adding_members">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="add_subjects_dept_window">
            <div class="changesubwindow animate">
                <div class="conts">
                    <p class="funga" id="close_window_add_subject_dept">&times</p>
                    <h5 class="text-center"><b>Add Subjects</b></h5>
                </div>
                <div class="container">
                    <p>
                        <b>Note:</b> <br> 
                        - Add subjects from the list below. <br>
                        - The list below will show the subjects that have not been assigned a department.
                    </p>
                    <hr class="w-50 mx-auto">
                    <label for="subjects_list_selected" class="form-label"><b>Subjects List</b><img class="hide" src="images/ajax_clock_small.gif" id="add_subjects_dept_loader"></label><br>
                    <label for="select_all_subjects_dept">Select All <input class="" type="checkbox" id="select_all_subjects_dept"></label>
                    <div id="subject_list_window">
                        <div class="classlist">
                            <div class="checkboxholder" style="margin:10px 0;padding:0px 0px;">
                                <label style="margin-right:5px;cursor:pointer;font-size:12px;" for="">Grade 8</label>
                                <input class="update_expense_check" type="checkbox" id="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="[]" id="subjects_lists">
                    <span class="hide" id="show_subjects_lists"></span>
                    <p id="display_subject_message"></p>
                </div>
                <div class="btns">
                    <button type="button" id='add_subjects_list_dept'>Add</button>
                    <button type="button" id="close_adding_subjects">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="addteachsubject">
            <div class="changesubwindow add_subjects animate">
                <div class="conts">
                    <p class="funga" id="funga1">&times</p>
                    <h6>Add subject</h6>
                </div>
                <div class="conts" id='selectsub1'>
                    <div class="conts">
                        <label class="form-control-label">Teacher id: <span id="trid12">1</span> <br></label>
                        <label class="form-control-label">Select a subject below:</label>
                    </div>
                    <div class="conts">
                        <p>Subject list:</p>
                        <p id='subslist'></p>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Mathematics</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>English</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Christian Religeous Education</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                        </div>-->
                    </div>
                </div>
                <div class="conts hide" id='selectclass1'>
                    <div class="conts borded">
                        <label class="form-control-label">Subject Name: <span id="subjectname2" style="color: brown;">Kiswahili</span> <br></label>
                        <label class="form-control-label">Subject id: <span id="subjectid2" style="color: brown;">0</span> <br></label>
                        <label class="form-control-label">Select classes the teacher will teach : <br></label>
                    </div>
                    <div class="conts">
                        <p>Class list:</p>
                        <p id='classlist_1'>
                        <div class="contsload" id="loadings23">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        </p>
                    </div>
                    <div class="contbut ">
                        <button type='button' id='return1'>Go back</button>
                        <label class="form-control-label"><br> Note:</label>
                        <p style='color:green;font-size:14px;'>Remember to save. <br> If you go back no data will be saved</p>
                    </div>
                </div>
                <div class="conts">
                    <p id='geterrors12'></p>
                </div>
                <div class="btns">
                    <button type="button" class="hide" id='saves1'>Save</button>
                    <button type="button" id="close2">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="regexams">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="fungash">&times</p>
                    <h5 class="text-center"><b>Register exams</b></h5>
                </div>
                <form class="formsexams" id='formsexams1'>
                    <div class="exam_form" id="examform1">
                        <div class="conts">
                            <label class="form-control-label" for="examjina">Exam name: <br></label>
                            <input class="form-control" type="text" name="examjina" id="examjina" placeholder="Exam name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="">Select subjects to be done: <br></label>
                            <!--Test subject list
                            <div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                                <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                    <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abc'>Mathematics</label>
                                    <input class="form-control" class='subjectcls' type='checkbox' name='abc' id='abc'>
                                </div>
                            </div>-->
                            <p id="subjectslists">
                            <div class="contsload" id="loadings213">
                                <img src="images/load2.gif" alt="loading..">
                            </div>
                            </p>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="examstartdate">Start date: <br></label>
                            <input class="form-control" type="date" name="examstartdate" id="examstartdate">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="examenddate">End Date: <br></label>
                            <input class="form-control" type="date" name="examenddate" id="examenddate">
                        </div>
                        <div class="curricullum">
                            <label class="form-control-label" for="curriculum">Select curricullum: <br></label>
                            <select class="form-control" title="Choosing the type of curriculum will help the system know the grading method" name="curriculum" id="curriculum">
                                <option value="" hidden>Select curricullum..</option>
                                <option value="cbc">C.B.C</option>
                                <option value="844">8-4-4</option>
                                <option value="IGCSE">IGCSE</option>
                                <option value="iPrimary">iPrimary</option>
                            </select>
                        </div>
                    </div>
                    <div class="exam_form hide" id="examform2">
                        <div class="conts" style="text-align:center;">
                            <h6>Complete Registration</h6>
                        </div>
                        <div class="conts">
                            <label class="form-control-label">Available classes: <br></label>
                            <!--Test subject list
                            <div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                                <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                    <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abcd'>Class 8</label>
                                    <input class="form-control" class='subjectcls' type='checkbox' name='abcd' id='abcd'>
                                </div>
                            </div>-->
                            <p id="classeslisted">
                            <div class="contsload" id="loadings214">
                                <img src="images/load2.gif" alt="loading..">
                            </div>
                            </p>
                        </div>
                        <div class="conts hide" style='margin-top:10px;' id="844m">
                            <label class="form-control-label" for="targetms">Target Meanscore: <br></label>
                            <input class="form-control" type="number" name="targetms" id="targetms" min='0' max='100' placeholder="Mean Score">
                        </div>
                        <div class="conts hide" style='margin-top:10px;' id="cbcm">
                            <label class="form-control-label" for="targetmscbc">Target Meanscore: <br></label>
                            <select name="targetmscbc" id="targetmscbc">
                                <option value="" hidden>Select option</option>
                                <option value="4">Exceeding Expectation</option>
                                <option value="3">Meeting Expectation</option>
                                <option value="2">Approaching Expectation</option>
                                <option value="1">Below Expectation</option>
                            </select>
                        </div>
                    </div>
                </form>
                <p id='errhandlers1203'></p>
                <div class="nextprevious">
                    <p id="previousexams"> Previous </p>
                    <p id="nextexams"> Next </p>
                </div>
                <div class="conts hide" id="savebuttons">
                    <div class="btns">
                        <button type="button" class="" id='saveexams'>Save</button>
                        <button type="button" id="cancelexams">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="editexams">
            <div class="changesubwindow editregexams animate">
                <div class="conts">
                    <p class="funga" id="fungash1">&times</p>
                    <h5 class="text-center"><b>Edit Exam Information</b></h5>
                    <p class="hide" id="exams_infor"></p>
                    <p><b>Note:</b></p>
                    <p>- You can only remove the subjects and the classes if the exam is active. <br>
                        - An active exam is which its end date is today or future date.
                    </p>
                </div>
                <form class="formsexams1" id='formsexams11'>
                    <div class="exam_form" id="examform11">
                        <div class="conts">
                            <label class="form-control-label">Exam id : <span id="examidsd">0</span> <br></label>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="examjina1">Exam name: <br></label>
                            <input class="form-control" type="text" name="examjina1" id="examjina1" placeholder="Exam name">
                        </div>
                        <div class="subjectandclass" style='margin-top:10px;'>
                            <div class="conts1">
                                <label class="form-control-label" for="">Subjects to be done: <br><small>Click the x button to remove the subject</small> <br></label>
                                <!--Test subject list
                                <div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                                    <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                        <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abc'>Mathematics</label>
                                        <p title="Click to remove this subject" class="fungasubjects" id="">&times</p>
                                    </div>
                                    <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                        <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abc'>English</label>
                                        <p class="fungasubjects" id="">&times</p>
                                    </div>
                                    <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                        <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abc'>Science</label>
                                        <p class="fungasubjects" id="">&times</p>
                                    </div>
                                    <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                        <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abc'>SST</label>
                                        <p class="fungasubjects" id="">&times</p>
                                    </div>
                                </div>-->
                                <p id="subjectslists1">
                                <div class="contsload" id="loadings2131">
                                    <img src="images/load2.gif" alt="loading..">
                                </div>
                                </p>
                                <p style='margin-top:10px;' id='errhandlers12031'></p>
                                <div class="conts">
                                    <button type="button" id="addsubjbtn">Add subject</button>
                                </div>
                            </div>
                            <div class="conts1">
                                <label class="form-control-label">Classes sitting:<br><small>Click the x button to remove the class</small> <br></label>
                                <!--Test subject list
                                <div class ='classlist' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                                    <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                        <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='abcd'>Class 8</label>
                                        <input class="form-control" class='subjectcls' type='checkbox' name='abcd' id='abcd'>
                                    </div>
                                </div>-->
                                <p id="classeslisted1">
                                <div class="contsload" id="loadings2141">
                                    <img src="images/load2.gif" alt="loading..">
                                </div>
                                </p>
                                <p style='margin-top:10px;' id="err102_op"></p>
                                <div class="conts">
                                    <button type="button" id="addclassbtn">Add classes</button>
                                </div>
                            </div>
                        </div>
                        <div class="curricullum">
                            <div class="conts">
                                <label class="form-control-label" for="examenddate1">End Date: <br></label>
                                <input class="form-control" type="date" name="examenddate1" id="examenddate1">
                            </div>
                            <label class="form-control-label" for="curriculum1">Select curricullum: <br></label>
                            <select class="form-control" title="Choosing the type of curriculum will help the system know the grading method" name="curriculum1" id="curriculum1">
                                <option value="" hidden>Select curricullum..</option>
                                <option id="cbc12" value="cbc">C.B.C</option>
                                <option id="84412" value="844">8-4-4</option>
                                <option id="IGCSE12" value="IGCSE">IGCSE</option>
                                <option id="iPrimary12" value="iPrimary">iPrimary</option>
                            </select>
                        </div>
                    </div>
                    <div class="exam_form" id="examform21">
                        <div class="conts hide" style='margin-top:10px;' id="844m1">
                            <label class="form-control-label" for="targetms1">Target Meanscore: <br></label>
                            <input class="form-control" type="number" name="targetms1" id="targetms1" min='0' max='100' placeholder="Mean Score">
                        </div>
                        <div class="conts hide" style='margin-top:10px;' id="cbcm1">
                            <label class="form-control-label" for="targetmscbc1">Target Meanscore: <br></label>
                            <select name="targetmscbc1" id="targetmscbc1">
                                <option value="" hidden>Select option</option>
                                <option class="my_option" value="4">Exceeding Expectation</option>
                                <option class="my_option" value="3">Meeting Expectation</option>
                                <option class="my_option" value="2">Approaching Expectation</option>
                                <option class="my_option" value="1">Below Expectation</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="conts">
                    <p id="error_1201"></p>
                </div>
                <div class="conts " id="savebuttons1">
                    <div class="btns">
                        <button type="button" class="" id='saveexams1'>Save</button>
                        <button type="button" id="cancelexams1">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="addsubjects">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="fungash2">&times</p>
                    <h6>Add subject</h6>
                </div>
                <div class="formation3">
                    <div class="conts" id="part1">
                        <label class="form-control-label">Select a subject:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek' id="check">Mathematics</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>English</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Christian Religeous Education</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                        </div>-->
                        <p id="subjectslists2">
                        <div class="contsload" id="loadings2132">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        </p>
                        <p><br><br><strong>Hint:</strong> Click on a subject to select it.</p>
                    </div>
                    <div class="conts hide" id="part2" style="margin-top:10px;">
                        <label class="form-control-label">Subject Id: <span id="subject_id" style="color:brown;">0</span><br></label>
                        <label class="form-control-label">Subject name: <span id="subject_name" style="color:brown;">Mathematics</span><br></label>
                        <label class="form-control-label">Select a class:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 8</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 7</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 6</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                        </div>-->
                        <p id="classlist45332"></p>
                        <div class="contsload" id="loadings2132ss">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        <p id="returnback">Back</p>
                        <p id="errorhandler1203"></p>
                    </div>
                </div>
                <div class="conts hide" id="savebuttons2">
                    <div class="btns">
                        <button type="button" class="" id='saveexams2'>Add</button>
                        <button type="button" id="cancelexams2">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="addclasswin">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="fungash232">&times</p>
                    <h6>Add Classes</h6>
                </div>
                <div class="formation3">
                    <div class="conts " id="part2" style="margin-top:10px;">
                        <label class="form-control-label">Exam Id: <span id="exam_sid" style="color:brown;">0</span></label><br>
                        <label class="form-control-label">Avilable classes:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 8</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 7</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 6</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                        </div>-->
                        <p id="classlist45221"></p>
                        <div class="contsload" id="loadingskh87">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        <p id="errorhandler78h7"></p>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" class="" id='saveexams232'>Add</button>
                    <button type="button" id="cancelexams232">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="assign_teacher">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="cancel_win1">&times</p>
                    <h6>Assign Class Teacher</h6>
                </div>
                <div class="formation3">
                    <div class="conts" id="select_teacher">
                        <label class="form-control-label">Select a teacher:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek' id="check">Mathematics</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>English</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Christian Religeous Education</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='".$arr[$i]."' name='chek".$arr[$i]."' id='chek".$arr[$i]."'>
                            </div>
                        </div>-->
                        <p id="assign_data">
                        <div class="contsload" id="loader_win">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        </p>
                        <p><br><br><strong>Hint:</strong> Click on a teacher name to select it.</p>
                    </div>
                    <div class="conts hide" id="partition" style="margin-top:10px;">
                        <label class="form-control-label">Teacher Id: <span id="tr_ids" style="color:brown;">0</span><br></label>
                        <label class="form-control-label">Teacher name: <span id="tr_name" style="color:brown;">Mathematics</span><br></label>
                        <label class="form-control-label">Select a class:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek'>Class 8</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                        </div>-->
                        <p id="class_list12">
                        <div class="contsload" id="loading_12">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        </p>
                        <p id="returnback2">Back</p>
                        <p id="errorhandler12031"></p>
                    </div>
                </div>
                <div class="conts" id="savebuttons2">
                    <div class="btns">
                        <button type="button" class="hide" id="add_subject">Add</button>
                        <button type="button" id="cancel_addsub">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="class_information">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_ci">&times</p>
                    <h6>Change Class information</h6>
                </div>
                <div class="formation3">
                    <div class="conts" id="find_opts">
                        <div class="conts">
                            <label class="form-control-label">Teacher id: <span style="color:brown;" id="tr_id_s">0</span><br></label>
                            <label class="form-control-label">Teacher Name: <span style="color:brown;" id="tr_na_me">Hillary Ngige</span><br></label>
                            <label class="form-control-label">Class assigned: <span style="color:brown;" id="class_assigned">Class 1</span></label>
                        </div>
                        <div class="btns" id="option_s">
                            <button type="button" id="un_assign_btn">Un-assign Teacher</button>
                            <button type="button" id="change_assigned_tr">Change Teacher</button>
                        </div>
                        <div class="conts bordered hide" id="confirm_delete_btns">
                            <p>Are you sure you want to un-assign this class a teacher ?</p>
                            <div class="btns">
                                <button type="button" id="no_unassign">No</button>
                                <button type="button" id="yes_unassign">Yes</button>
                            </div>
                        </div>
                    </div>
                    <div class="conts hide" id="options_ones">
                        <label class="form-control-label">Select a teacher:</label>
                        <!--<div class ='classlist2' style='height:100px;overflow:auto;' name='selectsubs' id='selectsubs'>
                            <div class = 'checkboxholder' style='margin:10px 0;padding:0px 0px;'>
                                <label class="form-control-label" style='margin-right:5px;cursor:pointer;font-size:14px;' for='chek' id="check">Mathematics</label>
                                <input class="form-control" class='checksubjects' type='checkbox' value='' name='' id=''>
                            </div>
                        </div>-->
                        <p id="populate_data">
                        <div class="contsload" id="load_teacher">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        </p>
                        <p><br><br><strong>Hint:</strong> Click on a teacher name to select.</p>
                        <p id="returnback3">Back</p>
                    </div>
                </div>
                <div class="conts">
                    <p id="set_class_err"></p>
                </div>
                <div class="conts" id="">
                    <div class="btns">
                        <button type="button" class="hide" id="save_inform">Save</button>
                        <button type="button" id="close_ci_1">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="pleasewait animate2 hide" id="pleasewaiting" title="Click to dismis">
            <label class="form-control-label">Please wait ...</label>
        </div>
        <div class="back_button animate hide" id="back_btns" title="Click to dismis">
            <button class="my_back_button"><img src="images/back.png" alt="back"></button>
        </div>
        <div class="anonymus hide" id="anonymus" title="Click to dismis">
        </div>
        <!--Changing student`s exam marks-->
        <div class="confirmpaymentwindow hide" id="change_record_marks">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_change_marks">&times</p>
                    <h6 class="text-center"><b>Change Student Marks</b></h6>
                </div>
                <div class="formation3">
                    <div class="conts" id="">
                        <div class="conts">
                            <label class="form-control-label">Marks Id: <span style="color:brown;" id="record_id">0</span></label><br>
                            <label class="form-control-label">Student Name: <span style="color:brown;" id="student_names">Hillary Ngige</span><br></label>
                            <label class="form-control-label">Exam Name: <span style="color:brown;" id="examsName">Targeter </span><br></label>
                            <label class="form-control-label">Subject Name: <span style="color:brown;" id="subjectsNames">Social Studies</span></label><br>
                            <p class="form-control-label">Grading Method: <span style="color:brown;" id="gradeMethod">8-4-4</span><br></p>
                            <hr>
                            <p>Delete the subject marks and record again if you want to change the grading system</p>
                            <button type="button" id="delete_marks">Delete Record</button>
                            <hr>
                        </div>
                        <div class="cbcmode" style="margin:10px 0" id="cbcmode1">
                            <h6>Change Score</h6>
                            <label for="change_cbc_marks" class="form-control-label">Change Marks</label>
                            <input type="text" class="form-control" id="change_cbc_marks">
                            <label class="form-control-label" for="cbc_mode1">Change score: <br></label>
                            <select class='selected_grades' name="cbc_mode1" id="cbc_mode1">
                                <option value='' hidden>Select..</option>
                                <option id='idd4' value='4'>4</option>
                                <option id='idd3' value='3'>3</option>
                                <option id='idd2' value='2'>2</option>
                                <option id='idd1' value='1'>1</option>
                                <option id='iddA' value='A'>A</option>
                            </select>
                        </div>
                        <div class="cbcmode" id="cbcmode2">
                            <label class="form-control-label" for="844_mode1">Change score: <br></label>
                            <input class="form-control" type="number" name="844_mode1" id="844_mode1" placeholder="Enter Marks" min='0' max='100' value='1'>
                        </div>
                        <div class="grades">
                            <label class="form-control-label" for="graders">Grade : (<span style="color:brown;" id="grade_scored">-</span>)</label>
                        </div>
                    </div>
                </div>
                <div class="conts">
                    <p id="set_class_err2"></p>
                </div>
                <div class="conts" id="">
                    <div class="btns">
                        <button type="button" class="" id="save_marks_change">Save</button>
                        <button type="button" id="close_change_marks2">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--confirm delete-->
        <div class="confirmpaymentwindow hide" id='confirm_delete'>
            <div class="confirmpayment animate">
                <h6>Confirm</h6>
                <p>Are you sure you want to delete marks for <b><span id="name_of_students"></span></b>?</p>
                <div class="btns">
                    <button type='button' id='confirm_yes'>Yes</button>
                    <button type='button' id='confirm_no'>No</button>
                </div>
            </div>
        </div>
        <!--confirm delete exams-->
        <div class="confirmpaymentwindow hide" id='confirm_delete_exams_win'>
            <div class="confirmpayment animate">
                <input type="hidden" id="exams_ids_delete">
                <h6>Confirm</h6>
                <p>Are you sure you want to delete this exam "<b><span id="name_of_students_exams"></span></b>" ? <br> <span class="text-danger">This action is irreversible.</span></p>
                <div class="btns">
                    <button type='button' id='confirm_del_exams_yes'>Yes</button>
                    <button type='button' id='confirm_del_exams_no'>No</button>
                </div>
            </div>
        </div>
        <!--confirm delete exams-->
        <div class="confirmpaymentwindow hide" id='change_class_name_window'>
            <div class="confirmpayment animate">
                <input type="hidden" id="class_id">
                <input type="hidden" name="" id="old_class_name_edit">
                <h5 class="text-center">Change " <b id="old_clas_name"></b> " Name</h5>
                <div class="form-group my-2">
                    <label for="new_class_name" class="form-control-label">New Class Name</label>
                    <input type="text" class="form-control" id="new_class_name" placeholder="New Class Name">
                </div>
                <div class="container p-1 my-1" id="err_change_class_name">

                </div>
                <div class="btns">
                    <button type='button' id='accept_class_change'>Save</button>
                    <button type='button' id='cancel_class_change'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='change_last_academic_windows'>
            <div class="confirmpayment animate">
                <h5 class="text-center">Change Last Academic Year Balance <img src="images/ajax_clock_small.gif" class="hide" id="change_balance_loaders"></h5>
                <input type="hidden" name="students_admission_number" id="students_admission_number">
                <label for="new_last_acad_bal" class="form-control-label text-bold">New balance</label>
                <input type="number" class="form-control" id="new_last_acad_bal" placeholder="New last academic year balance">
                <p id="men_in_out"></p>
                <div class="btns">
                    <button type='button' id='accept_last_yr_acad_bal'>Save</button>
                    <button type='button' id='cancel_last_yr_acad_bal'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='edit_discounts_window'>
            <div class="confirmpayment animate">
                <h5 class="text-center">Edit Discounts <img src="images/ajax_clock_small.gif" class="hide" id="edit_discounts_loader"></h5>
                <input type="hidden" name="stud_admin_discounts" id="stud_admin_discounts">
                <label for="discount_option" class="form-control-label">Discount Option</label>
                <select name="discount_option" id="discount_option" class="form-control">
                    <option value="" hidden>Select an option</option>
                    <option value="value">Discount Amount</option>
                    <option selected value="percentage">Discount Percentage</option>
                </select>
                <div class="container p-0 hide" id="discount_value_window">
                    <label for="new_discount_value" class="form-control-label text-bold">New Discount Value <small>e.g 10000</small></label>
                    <input type="number" class="form-control" value="0" id="new_discount_value" min="0" placeholder="Discount Value">
                </div>
                <div class="container p-0" id="discount_percentage_window">
                    <label for="new_discount_percentage" class="form-control-label text-bold">New Discount Percentage <small>e.g 35% <span class="text-danger">Dont include <b>%</b></span></small></label>
                    <input type="number" class="form-control" value="0" id="new_discount_percentage" min="0" max="100" placeholder="New Discount Value">
                </div>
                <p id="new_discount_error"></p>
                <div class="btns">
                    <button type='button' id='accept_new_discount_val'>Save</button>
                    <button type='button' id='cancel_new_discount_val'>Cancel</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="dorm_registrations">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_dorm_reg">&times</p>
                    <h6>Register Dormitory</h6>
                </div>
                <form class="formation" id="reg_dorm_form">
                    <div class="conts">
                        <label class="form-control-label" for="dorm_name">Dormitory Name: <br></label>
                        <input class="form-control" type="text" name="dorm_name" id="dorm_name" placeholder="Enter dormitory name">
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_capacity">Dormitory Capacity: <br></label>
                        <input class="form-control" type="number" name="dorm_capacity" id="dorm_capacity" placeholder="Enter dormitory Capacity">
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_captain">Dormitory Captain: <br></label>
                        <p id="tr_list"></p>
                        <div class="contsload" id="tr_lists">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                    </div>
                </form>
                <div class="conts">
                    <p id='add_dorm_err_handler'></p>
                </div>
                <div class="btns">
                    <button type="button" class="" id='add_dormitory'>Add</button>
                    <button type="button" id="close_dorm_reg_btn">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="dorm_edits">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_dorm_edit">&times</p>
                    <h6>Edit Dormitory</h6>
                </div>
                <form class="formation" id="edit_dorm_form">
                    <div class="conts">
                        <label class="form-control-label">House id: <span style="color:brown;" id="dormitory_id">0</span></label><br>
                        <label class="form-control-label">House Captain <span style="color:brown;" id="cap_name">Hillary Ngige</span></label>
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_name_edit">Dormitory Name: <br></label>
                        <input class="form-control" style="color:brown;" type="text" name="dorm_name_edit" id="dorm_name_edit" placeholder="Enter dormitory name">
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_capacity_edit">Dormitory Capacity: <br></label>
                        <input class="form-control" style="color:brown;" type="number" name="dorm_capacity_edit" id="dorm_capacity_edit" placeholder="Enter dormitory Capacity">
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_captain_edit">Change Captain: <br></label>
                        <p id="teacher_list"></p>
                        <div class="contsload" id="teacher_lists">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        <button type='button' id="un_assign_captain_btn">Un-assign captain</button>
                    </div>
                </form>
                <div class="conts">
                    <p id='edit_dorm_err_handler'></p>
                </div>
                <div class="btns">
                    <button type="button" class="" id='update_dormitory'>Save Changes</button>
                    <button type="button" id="close_dorm_edit_btn">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="change_student_dorm">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="change_student_close">&times</p>
                    <h6>Edit Dormitory</h6>
                </div>
                <form class="formation" id="">
                    <div class="conts">
                        <label class="form-control-label">Student id: <span style="color:brown;" id="my_student_id">0</span></label>
                        <label class="form-control-label" class='hide'>Dormitory id: <span style="color:brown;" id="my_dorm_id">0</span></label><br>
                        <label class="form-control-label">Student Name: <span style="color:brown;" id="my_student_name">Hillary Ngige</span></label>
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="dorm_list_change">Change dorm: <br></label>
                        <p id="dorms_lists"></p>
                        <div class="contsload" id="dorm_list_monitor">
                            <img src="images/load2.gif" alt="loading..">
                        </div>
                        <div class="btns">
                            <button type="button" class="" id='change_dormitory_btn'>Save Changes</button>
                        </div>
                        <p id="chage_dorms_err_handlers"></p>
                        <p>Click the <b>Un-assign</b> button below to un-assign the boarder a dormitory</p>
                        <p>Click the <b>De-register</b> button below to de-register student as a boarder.</p>
                        <div class="btns" style='border-bottom:1px dashed black;'>
                            <button type='button' style='margin:0;' id="un_assign_dorm_btn">Un-assign</button>
                            <button type='button' style='margin:0;' id="un_assign_boarder_btn">De-register</button>
                        </div>
                    </div>
                </form>
                <div class="conts">
                    <p id='change_dorm_err_handler'></p>
                </div>
                <div class="btns">
                    <button type="button" id="close_dorm_change_btn">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="read_notice">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_read_notice">&times</p>
                    <h6 style='text-align:center;'>Read Notice</h6>
                </div>
                <div class="conts" id="msg_body">
                    <div class="message_header">
                        <label class="form-control-label" class="hide">Message type : <span>Broadcast</span><br></label>
                        <label class="form-control-label" class="">Message id : <span>12</span><br></label>
                        <label class="form-control-label">Message Title : <span>Confirmed Payment For Hilary Ngige</span><br></label>
                        <label class="form-control-label">Message From : <span>Broadcast</span><br></label>
                    </div>
                    <div class="message_contents">
                        <label class="form-control-label"><u>Message content</u></label><br>
                        <p>Confirmed 20000 has been recieved from Hilary Ngige Adm No 23 for Lunch,on Jul-20th-2021</p>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="delete_message">Delete</button>
                    <button type="button" id="close_read_notice1">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_supplier_biil">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_add_supplier_bill">&times</p>
                    <h6 class="text-center">Add Supplier Bill</h6>
                </div>
                <div class="conts">
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Fill all fields as required!</p>
                    </div>
                    <form class="add_expense" id="add_supplier_bill">
                        <h6 class="text-center" id="supplier_name_title"><u>Supplier Bill</u></h6>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_name">Bill name: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_bill_name" id="supplier_bill_name" placeholder="Bill Name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_amount">Bill Amount: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_bill_amount" id="supplier_bill_amount" placeholder="Bill Amount">
                        </div>
                        <div class="conts">
                            <label for="expense_type" class="form-control-label" id="">Expense Type</label>
                            <select name="expense_type" id="expense_type" class="form-control w-75">
                                <option hidden value="">Select an option</option>
                                <option value="capital">Capital Expense</option>
                                <option value="operation">Operation Expense Expense</option>
                            </select>
                        </div>
                        <div class="conts hide capital_expenses">
                            <label class="form-control-label" for="asset_expense_category">Asset Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_asset_category_supplier"><br></label>
                            <select name="asset_expense_category" id="asset_expense_category" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="1">Land</option>
                                <option value="2">Buildings</option>
                                <option value="3">Motor Vehicle</option>
                                <option value="4">Furniture & Fittings</option>
                                <option value="5">Computer & ICT Equipments</option>
                                <option value="6">Plant & Equipments</option>
                                <option value="7">Capital Work in Progress</option>
                            </select>
                        </div>
                        <div class="conts hide operational_expense">
                            <label class="form-control-label" for="supplier_expense_category">Bill Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_supplier_expense_category"><br></label>
                            <div id="supplier_expense_cat"></div>
                        </div>
                        <div class="conts hide operational_expense">
                            <label class="form-control-label" for="supplier_expense_sub_category">Bill Sub-Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_supplier_expense_sub_category"><br></label>
                            <div id="supplier_expense_sub_cat"><p class="text-danger">Please select the bill category to display the subcategories</p></div>
                        </div>
                        <div class="conts hide capital_expenses">
                            <label for="asset_acquisition_method" class="form-label"><b>Value Acquisition Option</b></label>
                            <select name="asset_acquisition_method" id="asset_acquisition_method" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="1">Straightline Method</option>
                                <option value="2">Reducing Balance Method</option>
                            </select>
                        </div>
                        <div class="conts hide capital_expenses">
                            <label for="asset_acquisition_rates" class="form-label"><b>Value Depreciation rate <small class="text-success">(Not more than 100%)</small></b></label>
                            <input type="number" class="form-control w-75" name="asset_acquisition_rates" id="asset_acquisition_rates" value="0">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="date_assigned">Assigned Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d")?>" type="date" name="date_assigned" id="date_assigned" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_due_date">Due Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d",strtotime("30 days"))?>" type="date" name="supplier_bill_due_date" id="supplier_bill_due_date" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_document_number">Document Number: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_document_number" id="supplier_document_number" placeholder="Document Number">
                        </div>
                    </form>
                    <div class="conts">
                        <p id="supplier_bill_error"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="save_new_supplier_bill">Save Bill <img class="hide" src="images/ajax_clock_small.gif" id="save_bill_loader"></button>
                        <button type="button" id="close_new_supplier_bill_window">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="edit_supplier_biil">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_edit_supplier_bill">&times</p>
                    <h6 class="text-center">Edit Supplier Bill</h6>
                </div>
                <div class="conts">
                    <div class="cont my-2">
                        <div class="container ml-3">
                            <span class="btn btn-sm btn-secondary" id="delete_supplier_bill"><i class="fas fa-trash"></i></span>
                        </div>
                        <div class="message_contents hide" id="delete_bill_confirmation_window">
                            <label class="form-control-label"><u>Confirm:</u></label>
                            <p>Are you sure you want to delete this bill?</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <button id="confirm_bill_deletion"><i class="fas fa-trash"></i> Delete <img class="hide" src="images/ajax_clock_small.gif" id="delete_bill_loader"></button>
                                </div>
                                <div class="col-md-6">
                                    <button id="cancel_bill_deletion"><i class="fas fa-x"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Fill all fields as required!</p>
                        <p>- Edit the supplier bill.</p>
                    </div>
                    <form class="add_expense" id="edit_supplier_bill">
                        <h6 class="text-center" ><u>Supplier Bill</u></h6>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_name_edit">Bill name: <br></label>
                            <input type="hidden" name="" id="supplier_bill_id_edit">
                            <input class="form-control w-75" type="text" name="supplier_bill_name_edit" id="supplier_bill_name_edit" placeholder="Bill Name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_amount_edit">Bill Amount: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_bill_amount_edit" id="supplier_bill_amount_edit" placeholder="Bill Amount">
                        </div>
                        <div class="conts">
                            <label for="expense_type_edit" class="form-control-label" id="">Expense Type</label>
                            <select name="expense_type_edit" id="expense_type_edit" class="form-control w-75">
                                <option hidden value="">Select an option</option>
                                <option value="capital">Capital Expense</option>
                                <option value="operation">Operation Expense Expense</option>
                            </select>
                        </div>
                        <div class="conts hide capital_expenses">
                            <label class="form-control-label" for="asset_expense_category_edit">Asset Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_asset_category_supplier"><br></label>
                            <select name="asset_expense_category_edit" id="asset_expense_category_edit" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="1">Land</option>
                                <option value="2">Buildings</option>
                                <option value="3">Motor Vehicle</option>
                                <option value="4">Furniture & Fittings</option>
                                <option value="5">Computer & ICT Equipments</option>
                                <option value="6">Plant & Equipments</option>
                                <option value="7">Capital Work in Progress</option>
                            </select>
                        </div>
                        <div class="conts hide operational_expense">
                            <label class="form-control-label" for="supplier_expense_category_edit">Bill Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_supplier_expense_category_edit"><br></label>
                            <div id="supplier_expense_cat_edit"></div>
                        </div>
                        <div class="conts hide operational_expense">
                            <label class="form-control-label" for="supplier_expense_sub_category_edit">Bill Sub-Category: <img class="hide" src="images/ajax_clock_small.gif" id="display_supplier_expense_sub_category_edit"><br></label>
                            <div id="supplier_expense_sub_cat_edit"><p class="text-danger">Please select the bill category to display the subcategories</p></div>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="date_assigned_edit">Assigned Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d")?>" type="date" name="date_assigned_edit" id="date_assigned_edit" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_bill_due_date_edit">Due Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d",strtotime("30 days"))?>" type="date" name="supplier_bill_due_date_edit" id="supplier_bill_due_date_edit" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_document_number_edit">Document Number: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_document_number_edit" id="supplier_document_number_edit" placeholder="Document Number">
                        </div>
                    </form>
                    <div class="conts">
                        <p id="supplier_bill_error_edit"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="save_new_supplier_bill_edit">Update Bill <img class="hide" src="images/ajax_clock_small.gif" id="save_bill_loader_edit"></button>
                        <button type="button" id="close_new_supplier_bill_window_edit">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="view_payment_request">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_view_payment_request">&times</p>
                    <h6 class="text-center">View Payment Request</h6>
                </div>
                <div class="conts">
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Fill all fields as required!</p>
                        <p>- Edit the supplier bill.</p>
                    </div>
                    <form class="add_expense" >
                        <h6 class="text-center" ><u>Supplier Bill</u></h6>
                        <div class="conts">
                            <label class="form-control-label" for="payment_request_name">Expense Name: <br></label>
                            <input type="hidden" name="" id="payment_req_id">
                            <input type="hidden" name="" id="payment_req_type">
                            <input class="form-control w-90" type="text" name="payment_request_name" id="payment_request_name" placeholder="Bill Name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="PR_payment_for">Expense Category: <br></label>
                            <input class="form-control w-90" type="text" name="PR_payment_for" id="PR_payment_for" placeholder="Payment For">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="PR_expense_categories">Expense Amount: <br></label>
                            <input class="form-control w-90" type="text" name="PR_expense_categories" id="PR_expense_categories" placeholder="Expense Category">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="PR_expense_amount">Date Paid: <br></label>
                            <input class="form-control w-90" type="text" name="PR_expense_amount" id="PR_expense_amount" placeholder="Expense Amount">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="PR_date_paid">Document Number: <br></label>
                            <input class="form-control w-90" type="text" name="PR_date_paid" id="PR_date_paid" placeholder="Expense Name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="PR_expense_description">Expense Description: <br></label>
                            <textarea name="PR_expense_description" id="PR_expense_description" class="form-control" cols="30" rows="2"></textarea>
                        </div>
                    </form>
                    <div class="conts">
                        <!-- <p id="payment_requests_error"></p> -->
                    </div>
                    <div class="message_contents mt-3 hide" id="confirm_payment_request_window">
                        <label class="form-control-label"><u>Confirm:</u></label>
                        <p>- Confirm accepting the payment request?</p>
                        <p>- This action is irreversible.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <button id="confirm_accept_payment_request"><i class="fas fa-trash"></i> Yes <img class="hide" src="images/ajax_clock_small.gif" id="delete_supplier_loader"></button>
                            </div>
                            <div class="col-md-6">
                                <button id="cancel_accept_payment_request"><i class="fas fa-x"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="btns">
                        <p class="float-right btn btn-sm btn-success" id="accept_payment_approvall"><i class="fas fa-check"></i> Accept</p>
                        <p class="float-center btn btn-sm btn-warning" id="close_payment_approvale">Close</p>
                        <p class="float-left btn btn-sm btn-danger" id="reject_payment_approvale">X reject</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="show_payment_req_confirmation">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_confirm_payment_request">&times</p>
                    <h6 class="text-center">Accept Payment Request</h6>
                </div>
                <div class="conts">
                    <div class="message_contents mt-3">
                        <input type="hidden" id="payment_request_id">
                        <input type="hidden" id="payment_request_type">
                        <label class="form-control-label"><u>Confirm:</u></label>
                        <p>- Confirm accepting the payment request?</p>
                        <p>- This action is irreversible.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <button id="confirm_payment_request_in" >Yes <img id="loader_clocks" class="hide" src="images/ajax_clock_small.gif" ></button>
                            </div>
                            <div class="col-md-6">
                                <button id="cancel_payment_request_in" >Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="show_payment_decline_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_payment_decline_window">&times</p>
                    <h6 class="text-center">Decline Payment Request</h6>
                </div>
                <div class="conts">
                    <div class="message_contents mt-3">
                        <input type="hidden" id="payment_request_id_decline">
                        <input type="hidden" id="payment_request_type_decline">
                        <label class="form-control-label"><u>Confirm:</u></label>
                        <p>- Confirm decline the payment request?</p>
                        <p>- This action is irreversible.</p>
                        <label for="payment_decline_description" class="form-control-label">Reason of decline</label>
                        <textarea name="payment_decline_description" id="payment_decline_description" class="form-control" rows="5" placeholder="Reason for declining the payment request!"></textarea>
                        <div class="row">
                            <div class="col-md-6">
                                <button id="confirm_payment_request_decline" >Yes <img id="all_loader_clocks" class="hide" src="images/ajax_clock_small.gif" ></button>
                            </div>
                            <div class="col-md-6">
                                <button id="cancel_payment_request_decline" >Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="make_payments_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_supplier_payments">&times</p>
                    <h6 class="text-center">Make Payments</h6>
                </div>
                <div class="conts">
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Fill all fields as required!</p>
                    </div>
                    <form class="add_expense" >
                        <h6 class="text-center" ><u>Make Payment</u></h6>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_for">Payment For: <img class="hide" src="images/ajax_clock_small.gif" id="supplier_payment_for_loader"><br></label>
                            <div id="payment_for_details"></div>
                        </div>
                        <div class="cont">
                            <label for="payment-method" class="form-control-label">Payment Method</label>
                            <select name="payment-method" id="payment-method" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="1">Bank Transfer</option>
                                <option value="2">Cheque</option>
                                <option value="3">Cash</option>
                                <option value="4">M-Pesa (Paybill)</option>
                                <option value="5">M-Pesa (Buy Goods)</option>
                                <option value="6">M-Pesa (Pochi)</option>
                                <option value="7">M-Pesa (Send Money)</option>
                            </select>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_amount">Payment Amount: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_payment_amount" id="supplier_payment_amount" placeholder="Bill Amount">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_date">Assigned Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d")?>" type="date" name="supplier_payment_date" id="supplier_payment_date" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_document_no">Document Number: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_payment_document_no" id="supplier_payment_document_no" placeholder="Document Number">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_description">Payment Description: <br></label>
                            <textarea name="" id="supplier_payment_description" cols="30" rows="4" class="form-control" placeholder="Give a narrative about this payment"></textarea>
                        </div>
                    </form>
                    <div class="conts">
                        <p id="supplier_payment_description_error"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="make_supplier_payment">Confirm Payment Request<img class="hide" src="images/ajax_clock_small.gif" id="make_payment_loader"></button>
                        <button type="button" id="close_supplier_payment">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="edit_supplier_payments_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_edit_supplier_payments">&times</p>
                    <h6 class="text-center">View Supplier Payments</h6>
                </div>
                <div class="conts">
                    <div class="cont">
                        <div class="container ml-3 row">
                            <div class="col-md-6">
                                <span class="btn btn-sm btn-secondary" id="delete_payments"><i class="fas fa-trash"></i></span>
                            </div>
                            <div class="col-md-6" id="show_supplier_payment_status">

                            </div>
                        </div>

                        <div class="message_contents hide my-2" id="delete_payment_window">
                            <label class="form-control-label text-danger"><u>Confirm Delete:</u></label>
                            <p>Are you sure you want to delete this payment?</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <button id="confirm_delete_payments"><i class="fas fa-trash"></i> Delete <img class="hide" src="images/ajax_clock_small.gif" id="delete_payment_loader"></button>
                                </div>
                                <div class="col-md-6">
                                    <button id="cancel_delete_payments"><i class="fas fa-x"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="message_contents hide my-2" id="show_reason_payment_declined">
                            <label class="form-control-label"><u>Reason for payment decline:</u></label>
                            <p id="show_reason_supplier_payment_decline">No reason stated!</p>
                        </div>
                    </div>
                    <form class="add_expense" >
                        <h6 class="text-center" ><u>Edit Payment</u></h6>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_for_edit">Payment For: <img class="hide" src="images/ajax_clock_small.gif" id="supplier_payment_for_loader_edit"><br></label>
                            <div id="payment_for_details_edit"></div>
                            <input type="hidden" name="" id="supplier_payment_id">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_amount_edit">Payment Amount: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_payment_amount_edit" id="supplier_payment_amount_edit" placeholder="Bill Amount">
                        </div>
                        <div class="cont">
                            <label for="payment-method-edit" class="form-control-label">Payment Method</label>
                            <select name="payment-method-edit" id="payment-method-edit" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="1">Bank Transfer</option>
                                <option value="2">Cheque</option>
                                <option value="3">Cash</option>
                                <option value="4">M-Pesa (Paybill)</option>
                                <option value="5">M-Pesa (Buy Goods)</option>
                                <option value="6">M-Pesa (Pochi)</option>
                                <option value="7">M-Pesa (Send Money)</option>
                            </select>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_date_edit">Assigned Date: <br></label>
                            <input class="form-control w-75" value="<?=date("Y-m-d")?>" type="date" name="supplier_payment_date_edit" id="supplier_payment_date_edit" placeholder="Bill Date">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_document_no_edit">Document Number: <br></label>
                            <input class="form-control w-75" type="text" name="supplier_payment_document_no_edit" id="supplier_payment_document_no_edit" placeholder="Document Number">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="supplier_payment_description_edit">Payment Description: <br></label>
                            <textarea name="" id="supplier_payment_description_edit" cols="30" rows="4" class="form-control" placeholder="Give a narrative about this payment"></textarea>
                        </div>
                    </form>
                    <div class="conts">
                        <p id="supplier_payment_description_error_edit"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="make_supplier_payment_edit">Update Payment <img class="hide" src="images/ajax_clock_small.gif" id="make_payment_loader_edit"></button>
                        <button type="button" id="close_supplier_payment_edit">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_expense_par">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_add_expense">&times</p>
                    <h6 class="text-center">Add Votehead</h6>
                </div>
                <div class="conts" id="">
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Adding of a votehead for regular or boarder will result to an immediate change in student`s balances and an increase or decrease of what is charged.</p>
                    </div>
                    <form class="add_expense" id="exp_names">
                        <div class="conts">
                            <label class="form-control-label" for="exp_name">Votehead name: <br></label>
                            <input class="form-control" type="text" name="exp_name" id="exp_name" placeholder="Votehead Name">
                            <p id="expe_err"></p>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_one">Term One Amount: <br></label>
                            <input class="form-control" type="number" name="term_one" id="term_one" placeholder="Term One">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_two">Term Two Amount: <br></label>
                            <input class="form-control" type="number" name="term_two" id="term_two" placeholder="Term Two">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_three">Term Three Amount: <br></label>
                            <input class="form-control" type="number" name="term_three" id="term_three" placeholder="Term Three">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="boarders_regular">Fees type: <br></label>
                            <select class="form-control" name="boarders_regular" id="boarders_regular">
                                <option value="" hidden>Select..</option>
                                <option value="regular">Regular</option>
                                <option value="boarding">Boarder</option>
                                <option value="provisional">Provisional</option>
                            </select>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="select_classes">Select Course Level: <img class="hide" src="images/ajax_clock_small.gif" id="loadings213111"><br></label>
                            <div id="class_list_fees">
                            </div>
                        </div>
                        <div class="conts">
                            <label for="course_lists_fees_structure" class="form-control-label">Course <img class="hide" src="images/ajax_clock_small.gif" id="loading_course_level_fees_struct"></label>
                            <div id="course_fees_structure"><p class="text-secondary">Courses will appear here if the course level is selected!</p></div>
                        </div>
                    </form>
                    <div class="conts">
                        <p id="err_handler_10"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="save_add_expense">Save</button>
                        <button type="button" id="close_add_expense2">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_payment_options_window">
            <div class="changesubwindow editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_window_payment_options">&times</p>
                    <h6 class="text-center"><b>Add Payment Options</b></h6>
                </div>
                <div class="container w-100" id="">
                    <label for="" class="form-control-label">Write your payment description below</label>
                    <textarea class="form-control" maxlength="250" name="" id="payment_description_texts" cols="30" rows="3" placeholder="Ex : Direct deposit to KCB Bank, Account No. 1257951734, Account Name 'Ladybird School Mis'"></textarea>
                    <div class="btns">
                        <button type="button" id="save_payment_option">Save</button>
                        <button type="button" id="cancel_payment_options">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_expense_category_window">
            <div class="changesubwindow2 editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_window_expense_category">&times</p>
                    <h5 class="text-center"><b>Add Expense Category</b></h5>
                </div>
                <hr class="my-2 py-1">
                <div class="mx-auto row w-100">
                    <div class="container col-md-6" id="">
                        <h6 class="text-center">Expense Details</h6>
                        <div class="form-group">
                            <label for="expense_category_name" class="form-control-label">Expense Category Name.</label>
                            <input type="text" name="" id="expense_category_name" class="form-control w-75" placeholder="E.x., Daily Expenses">
                        </div>
                        <div class="form-group my-2">
                            <label for="expense_category_budget" class="form-control-label">Category Maximum Budget.</label>
                            <input type="number" name="" id="expense_category_budget" class="form-control w-75" placeholder="E.x. 1000000">
                        </div>
                        <div class="form-group my-2">
                            <label for="budget_start_time" class="form-control-label">Budget Start Time.</label>
                            <input type="date" name="" id="budget_start_time" class="form-control w-75" value="<?= date("Y")."-01-01" ?>">
                        </div>
                        <div class="form-group my-2">
                            <label for="budget_end_date" class="form-control-label">Budget End Time.</label>
                            <input type="date" name="" id="budget_end_date" class="form-control w-75" value="<?= date("Y")."-12-31" ?>">
                        </div>
                        <div class="form-group my-2">
                            <label for="expense_notes" class="form-control-label">Expense Notes <sup>IPSAS</sup></label>
                            <select name="expense_notes" id="expense_notes" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="15">Note 15</option>
                                <option value="16">Note 16</option>
                                <option value="17">Note 17</option>
                                <option value="18">Note 18</option>
                                <option value="19">Note 19</option>
                                <option value="20">Note 20</option>
                                <option value="21">Note 21</option>
                                <option value="22">Note 22</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center">Expense Categories</h6>
                        <div class="container border border-rounded border-secondary row">
                            <div class="col-lg-6 my-2">
                                <label for="expense_sub_categories" class="form-control-label">Expense Categories</label>
                                <input type="text" placeholder="Exp. Category" class="form-control w-100" id="expense_sub_categories">
                                <input type="hidden" value="[]" id="expense_sub_categories_holder">
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-primary" id="add_expense_sub_category"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        <div class="container border border-rounded border-secondary row my-1 py-2">
                            <div class="col-md-12">
                                <h6 class="text-center">Expense Sub-Category Table</h6>
                            </div>
                            <div class="col-md-12 p-0" id="expense_subcategory_table">
                                <!-- <table class="table col-md-12">
                                    <tr>
                                        <th>No.</th>
                                        <th>Expense Sub-Categories</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Expense Sub-Category 1</td>
                                        <td><span class="link" id="exit_expense_sub_cat"><i class="fas fa-trash"></i> Delete</span></td>
                                    </tr>
                                </table> -->
                                <p class='text-danger'>No expense categories to display!<br>Add expense category list will appear here!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="save_expense_category">Save</button>
                    <button type="button" id="cancel_expense_category">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_revenue_category_window">
            <div class="changesubwindow2 editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_window_revenue_category">&times</p>
                    <h6 class="text-center"><b>Add Revenue Category</b></h6>
                </div>
                <div class="row container">
                    <div class="col-md-6 p-2" id="">
                        <label for="revenue_category_name" class="form-control-label">Revenue Category Name.</label>
                        <input type="text" name="" id="revenue_category_name" class="form-control" placeholder="E.x., Transfers from National Government entities">
                        <div class="form-group">
                            <label for="revenue_notes" class="form-control-label">Revenue Note <sup>IPSAS</sup></label>
                            <select name="revenue_notes" id="revenue_notes" class="form-control w-75">
                                <option value="" hidden>Select an option</option>
                                <option value="6">Note 6</option>
                                <option value="7">Note 7</option>
                                <option value="8">Note 8</option>
                                <option value="9">Note 9</option>
                                <option value="10">Note 10</option>
                                <option value="11">Note 11</option>
                                <option value="12">Note 12</option>
                                <option value="13">Note 13</option>
                                <option value="14">Note 14</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="container border border-rounded border-secondary row">
                            <div class="col-lg-6 my-2">
                                <label for="add_revenue_sub_categories" class="form-control-label">Revenue Categories</label>
                                <input type="text" placeholder="Revenue. Category" class="form-control w-100" id="add_revenue_sub_categories_1">
                                <input type="hidden" value="[]" id="add_revenue_sub_categories_holder_1">
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-primary" id="add_revenue_sub_category_1"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        <div class="container border border-rounded border-secondary row my-1 py-2">
                            <div class="col-md-12">
                                <h6 class="text-center">Revenue Sub-Category Table</h6>
                            </div>
                            <div class="col-md-12 p-0" id="add_revenue_subcategory_table_1">
                                <!-- <table class="table col-md-12">
                                    <tr>
                                        <th>No.</th>
                                        <th>Expense Sub-Categories</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Expense Sub-Category 1</td>
                                        <td><span class="link" id="exit_expense_sub_cat"><i class="fas fa-trash"></i> Delete</span></td>
                                    </tr>
                                </table> -->
                                <p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="save_revenue_category">Save</button>
                    <button type="button" id="cancel_revenue_category">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="delete_expense_category_window">
            <div class="changesubwindow editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_window_delete_expense_category">&times</p>
                    <h6 class="text-center"><b>Delete Expense Category</b></h6>
                </div>
                <div class="container w-100" id="">
                    <p>Are you sure you want to delete <b id="expense_category_delete_name"></b>?</p>
                    <input type="hidden" id="exp_indexes">
                    <div class="btns">
                        <button type="button" id="save_delete_expense_category">Yes</button>
                        <button type="button" id="cancel_delete_expense_category">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="edit_expense_windows">
            <div class="changesubwindow editexams animate">
                <div class="container w-100">
                    <p class="funga" id="edit_expense_windows_2">&times</p>
                    <h6 class="text-center"><b>Edit Expense</b> <label for=""><img src="images/ajax_clock_small.gif" id="expense_editor_loader" class="hide"></label></h6>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type='button' id="delete_promt_expenses">Delete</button>
                    </div>
                    <div class="col-md-6" id="expense_status_view">
                        <!-- show the payment approve status -->
                    </div>
                </div>
                <p class="message_contents border border-primary rounded p-2 hide" id="delete_exp_window">
                    Are you sure you want to delete this expense entry! <br>
                    <span id="delete_expense_entry" class="text-danger link"><i class="fas fa-trash"></i>Delete</span>
                </p>
                <div class="message_contents hide" id="reason_for_req_decline_window">
                    <label class="form-control-label"><u>Reason for request decline:</u></label>
                    <p id="reason_for_payment_decline">No Reason stated!</p>
                </div>
                <div class="add_expense" id="">
                    <label for="edit_expense_name" class="form-label">Expense Name</label>
                    <input type="text" class="form-control w-75" id="edit_expense_name" placeholder="Expense Name">
                    
                    <label for="edit_expense_record_date" class="form-label">Expense Record Date</label>
                    <input type="date" class="form-control w-75" id="edit_expense_record_date" value="<?=date("Y-m-d")?>">

                    <label for="edit_expense_category" class="form-label">Expense Category <img src="images/ajax_clock_small.gif" id="expense_cat_egories" class="hide"></label>
                    <p id="show_expense_category"></p>

                    <label for="edit_expense_sub_cat" class="form-label">Expense Sub-Category <img src="images/ajax_clock_small.gif" id="expense_sub_cat_egories" class="hide"></label>
                    <p id="show_expense_sub_category"></p>

                    <label for="edit_expense_cash_activity" class="form-control-label"><b>Expense Activity</b></label>
                    <select name="edit_expense_cash_activity" id="edit_expense_cash_activity" class="form-control w-75">
                        <option value="" hidden>Select Option</option>
                        <option value="1">Operating Activities</option>
                        <option value="2">Investing Activities</option>
                        <option value="3">Financing Activities</option>
                    </select>
                    
                    <label for="total_unit_cost" class="form-control-label">Total Unit Cost</label>
                    <input type="number" id="total_unit_cost" class="form-control w-75">

                    <label class="form-control-label" for="edit_document_number"><b>Document Number</b> <br></label>
                    <input class="form-control w-75" type="text" name="edit_document_number" id="edit_document_number"  min = "0" placeholder = "Optional - Receipt, Invoice, Cheque">

                    <label class="form-control-label" for="edit_expense_description"><b>Expense Description</b> <br></label>
                    <textarea name="edit_expense_description" id="edit_expense_description" cols="30" rows="3" class="form-control" placeholder="Expense description"></textarea>

                    <input type="hidden" id="expense_ids_in">
                    <div class="btns">
                        <button type="button" id="save_expense_details">Update Expense</button>
                        <button type="button" id="close_edit_expense_window">Close</button>
                    </div>
                    <div class="container" id="error_handlers_expenses">
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="change_revenue_category_window">
            <div class="changesubwindow2 editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_change_revenue_category_window">&times</p>
                    <h6 class="text-center"><b>Change Revenue Category</b></h6>
                </div>
                <div class="row container">
                    <div class="container col-md-6 border border-secondary rounded" id="">
                        <p>Are you sure you want to change <b id="revenue_category_change_name"></b>? <br> <b class="text-danger"> Note</b> <br> All entities with this name will be changed in the system.</p>
                        <label for="change_revenue_category_input_window" class="form-control-label">New Revenue Category Name</label>
                        <input type="text" class="form-control w-75" placeholder="Revenue Category" id="change_revenue_category_input_window">
                        <input type="hidden" id="revenue_indexes_update">
                        <div class="form-group">
                            <label for="revenue_notes_edit" class="form-control-label">Revenue Note <sup>IPSAS</sup></label>
                            <select name="revenue_notes_edit" id="revenue_notes_edit" class="form-control w-75">
                                <option value="" hidden>Select an option</option>
                                <option value="6">Note 6</option>
                                <option value="7">Note 7</option>
                                <option value="8">Note 8</option>
                                <option value="9">Note 9</option>
                                <option value="10">Note 10</option>
                                <option value="11">Note 11</option>
                                <option value="12">Note 12</option>
                                <option value="13">Note 13</option>
                                <option value="14">Note 14</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="container border border-rounded border-secondary row">
                            <div class="col-lg-6 my-2">
                                <label for="add_revenue_sub_categories" class="form-control-label">Revenue Categories</label>
                                <input type="text" placeholder="Revenue. Category" class="form-control w-100" id="add_revenue_sub_categories">
                                <input type="hidden" value="[]" id="add_revenue_sub_categories_holder">
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-primary" id="add_revenue_sub_category"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        <div class="container border border-rounded border-secondary row my-1 py-2">
                            <div class="col-md-12">
                                <h6 class="text-center">Revenue Sub-Category Table</h6>
                            </div>
                            <div class="col-md-12 p-0" id="add_revenue_subcategory_table">
                                <!-- <table class="table col-md-12">
                                    <tr>
                                        <th>No.</th>
                                        <th>Expense Sub-Categories</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Expense Sub-Category 1</td>
                                        <td><span class="link" id="exit_expense_sub_cat"><i class="fas fa-trash"></i> Delete</span></td>
                                    </tr>
                                </table> -->
                                <p class='text-danger'>No revenue sub-categories to display!<br>Revenue sub-category list will appear here!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="save_change_revenue_category"><i class="fas fa-save"></i> Save</button>
                    <button type="button" id="cancel_change_revenue_category"><div class="fas fa-close"></div> Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="change_expense_category_window">
            <div class="changesubwindow2 editexams animate">
                <div class="container w-100">
                    <p class="funga" id="close_change_expense_category_window">&times</p>
                    <h6 class="text-center"><b>Change Expense Category</b></h6>
                        <p>Are you sure you want to change <b id="expense_category_change_name"></b>? <br> <b class="text-danger"> Note</b> <br> All entities with this name will be changed in the system. <br>This action will be recorded in the system.</p>
                </div>
                <hr class="py-1 my-2">
                <div class="row container">
                    <div class="container col-md-6" id="">
                        <h6 class="text-center">Expense Details</h6>
                        <div class="form-group">
                            <label for="change_expense_category_input_window" class="form-control-label">New Expense Category Name</label>
                            <input type="text" class="form-control" placeholder="Expense Category" id="change_expense_category_input_window">
                        </div>
                        <div class="form-group my-2">
                            <label for="expense_category_budget_edit" class="form-control-label">Category Maximum Budget.</label>
                            <input type="number" name="" id="expense_category_budget_edit" class="form-control" placeholder="E.x. 1000000">
                        </div>
                        <div class="form-group my-2">
                            <label for="budget_start_time_edit" class="form-control-label">Budget Start Time.</label>
                            <input type="date" name="" id="budget_start_time_edit" class="form-control" value="<?= date("Y")."-01-01" ?>">
                        </div>
                        <div class="form-group my-2">
                            <label for="budget_end_date_edit" class="form-control-label">Budget End Time.</label>
                            <input type="date" name="" id="budget_end_date_edit" class="form-control" value="<?= date("Y")."-12-31" ?>">
                        </div>
                        <div class="form-group my-2">
                            <label for="edit_expense_notes" class="form-control-label">Expense Notes <sup>IPSAS</sup></label>
                            <select name="edit_expense_notes" id="edit_expense_notes" class="form-control w-75">
                                <option value="" hidden>Select an Option</option>
                                <option value="15">Note 15</option>
                                <option value="16">Note 16</option>
                                <option value="17">Note 17</option>
                                <option value="18">Note 18</option>
                                <option value="19">Note 19</option>
                                <option value="20">Note 20</option>
                                <option value="21">Note 21</option>
                                <option value="22">Note 22</option>
                            </select>
                        </div>
                        <input type="hidden" id="exp_indexes_update">
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center">Expense Sub-Categories</h6>
                        <div class="container border border-rounded border-secondary row">
                            <div class="col-lg-6 my-2">
                                <label for="edit_expense_sub_categories" class="form-control-label">Expense Categories</label>
                                <input type="text" placeholder="Exp. Category" class="form-control w-100" id="edit_expense_sub_categories">
                                <input type="hidden" value="[]" id="edit_expense_sub_categories_holder">
                            </div>
                            <div class="col-lg-6">
                                <button class="btn btn-primary" id="edit_expense_sub_category"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        <div class="container border border-rounded border-secondary row my-1 py-2">
                            <div class="col-md-12">
                                <h6 class="text-center">Expense Sub-Category Table</h6>
                            </div>
                            <div class="col-md-12 p-0" id="edit_expense_subcategory_table">
                                <!-- <table class="table col-md-12">
                                    <tr>
                                        <th>No.</th>
                                        <th>Expense Sub-Categories</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Expense Sub-Category 1</td>
                                        <td><span class="link" id="exit_expense_sub_cat"><i class="fas fa-trash"></i> Delete</span></td>
                                    </tr>
                                </table> -->
                                <p class='text-danger'>No expense categories to display!<br>Add expense category list will appear here!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="save_change_expense_category">Update</button>
                    <button type="button" id="cancel_change_expense_category">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="email_data_read_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_email_data_read_window">&times</p>
                    <h6 class="text-center">Read Email<span class="hide" id="read_email_loader"><img src="images/ajax_clock_small.gif"></span></h6>
                </div>
                <div class="conts" id="">
                    <span id="email_contents" class=""></span>
                    <!-- <div class="message_contents">
                        <p><b>Sender :</b> mail@ladybirdsmis.com</p>
                        <p><b>BCC :</b> mail@ladybirdsmis.com</p>
                        <p><b>CC :</b> mail@ladybirdsmis.com</p>
                        <p><b>Recepient :</b> mail@ladybirdsmis.com</p>
                    </div>
                    <div class="add_expense" id="exp_names">
                        <div class="conts">
                            <label class="form-control-label" for="exp_name">Message Contents: <br></label>
                            <span id="email_message_contents"></span>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="exp_name">Message Attachment: <br></label>
                            <a href="http://ladybirdsmis.com" target="_blank">http://ladybirdsmis.com</a>
                        </div>
                    </div> -->
                    <div class="btns">
                        <button type="button" id="close_email_data_windows">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="email_setup_window">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_email_windows">&times</p>
                    <h6 class="text-center" >Set Up Email <span class="hide" id="load_email_setup2"><img src="images/ajax_clock_small.gif" id=""></span></h6>
                </div>
                <div class="conts" id="">
                    <div class="message_contents">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Provide the correct data for the emails to work.</p>
                    </div>
                    <form class="add_expense" id="non12">
                        <div class="conts">
                            <label class="form-control-label" for="sender_name">Sender`s Name: <br></label>
                            <input class="form-control" type="text" name="sender_name" id="sender_name" placeholder="The name seen by recipient">
                            <p id=""></p>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="email_host_addr">Email Host: <br></label>
                            <input class="form-control" type="text" name="email_host_addr" id="email_host_addr" placeholder="eg: Gmail = smtp.gmail.com">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="email_username">Email Username: <br></label>
                            <input class="form-control" type="text" name="email_username" id="email_username" placeholder="Your Email Address">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="email_password">Email Password: <br></label>
                            <input class="form-control" type="password" name="email_password" id="email_password" placeholder="Your mail password">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="tester_mail">Test Email: <br></label>
                            <input class="form-control" type="text" name="tester_mail" id="tester_mail" placeholder="Email for testing">
                        </div>
                    </form>
                    <div class="conts">
                        <p id="error_email_setups"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="save_email_setup">Save</button>
                        <button type="button" id="close_email_setup">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='delete_fee_win'>
            <div class="confirmpayment animate">
                <p class="hide" id="record_ids"></p>
                <h6>Remove Fee</h6>
                <p><b>This action is irreversible!</b> <br>Are you sure you want remove <b id='expensenamed'></b> fee.?</p>
                <div class="btns">
                    <button type='button' id='confirm_yes_fees'>Yes</button>
                    <button type='button' id='confirm_no_fees'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id='delete_department_window'>
            <div class="confirmpayment animate">
                <input type="hidden" id="department_code_delete">
                <h6>Delete Department <img class="hide" src="images/ajax_clock_small.gif" id="delete_department_all"></h6>
                <p><b class="text-danger">This action is irreversible!</b> <br>Are you sure you want delete "<b id='department_name_delete'></b>" department?</p>
                <p id="error_handler_delete_dept"></p>
                <div class="btns">
                    <button type='button' id='confirm_delete_department'>Yes</button>
                    <button type='button' id='close_delete_department_window'>No</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_expense_update">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_add_expense1">&times</p>
                    <h6>Update Votehead Information</h6>
                </div>
                <div class="conts" id="">
                    <div class="conts">
                        <label class="form-control-label"><u>Note:</u></label>
                        <p>- Changing of votehead amount will result to an immediate change in student`s balances or and an increase or decrease of what is charged.</p>
                    </div>
                    <form class="add_expense" id="exp_names1">
                        <div class="conts">
                            <p class="hide" id="original_exp_name"></p>
                        </div>
                        <div class="conts"><label class="form-control-label">Fees id: <span id="fee_id_s">0</span></label></div>
                        <div class="conts">
                            <label class="form-control-label" for="exp_name1">Votehead name: <br></label>
                            <input class="form-control" type="text" name="exp_name1" id="exp_name1" placeholder="Votehead Name">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_one1">Term One Amount: <br></label>
                            <input class="form-control" type="number" name="term_one1" id="term_one1" placeholder="Term One">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_two1">Term Two Amount: <br></label>
                            <input class="form-control" type="number" name="term_two1" id="term_two1" placeholder="Term Two">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="term_three1">Term Three Amount: <br></label>
                            <input class="form-control" type="number" name="term_three1" id="term_three1" placeholder="Term Three">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="boarders1_regular1">Fees type: <br></label>
                            <select class="form-control" name="boarders1_regular1" id="boarders1_regular1">
                                <option value="" hidden>Select..</option>
                                <option value="regular" id="regular12">Regular</option>
                                <option value="boarding" id="boarding12">Boarder</option>
                                <option value="provisional" id="provisional12">Provisional</option>
                            </select>
                        </div>
                        <input type="hidden" name="" id="course_id_edit">
                        <div class="conts">
                            <label class="form-control-label" for="fees_structure_edit_level">Select Course Levels: <img class="hide" src="images/ajax_clock_small.gif" id="load_course_levels_edit"><br></label>
                            <div id="class_list_fees_update"></div>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="course_chosen_fees_structure">Select Course: <img class="hide" src="images/ajax_clock_small.gif" id="course_list_edits_loader"><br></label>
                            <div id="course_list_details"></div>
                        </div>
                    </form>
                    <div class="conts">
                        <p id="err_handler_101"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="save_add_expense1">Save</button>
                        <button type="button" id="close_add_expense21">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="add_grades_win">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_add_grades_window">&times</p>
                    <h5 class="text-center"><b>Set Grades</b></h5>
                </div>
                <div class="container" id="">
                    <div class="container p-0 mx-0 my-2">
                        <h6>Set Grades for <b id="subject_grades_names">Subject Name</b></h6>
                        <h6><b>Note</b></h6>
                        <p>1 Add the grade list first before saving</p>
                        <p class="hide" id="my_grades_lists"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-2">
                            <label for="maximum_marks" class="form-control-label"><b>Starting from :</b></label>
                            <input readonly type="number" class="form-control w-100" id="maximum_marks" placeholder="starting from">
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="minimum_marks" class="form-control-label"><b>To :</b></label>
                            <input type="number" class="form-control w-100" id="minimum_marks" placeholder="To">
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="grade_score" class="form-control-label"><b>Grade Name: </b></label>
                            <input type="text" class="form-control w-100" id="grade_score" placeholder="Grade Name eg 'A'">
                        </div>
                        <div class="col-md-6 my-2">
                            <p id="set_grades_btn" class="block_btn">Add Grade</p>
                        </div>
                    </div>
                    <p id="error_handler_graders"></p>
                    <hr>
                    <div class="container" id="grades_listers">
                        <!-- <h6 class="text-center">Grade List</h6>
                        <table class="table">
                            <tr>
                                <th>Grade</th>
                                <th>Min Marks</th>
                                <th>Max Marks</th>
                            </tr>
                            <tr>
                                <td>A (Plain)</td>
                                <td>50</td>
                                <td>40</td>
                            </tr>
                        </table> -->
                        <p>Kindly add the grades for the respective subject <br> Grades will appear here.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button id="add_grades_in">Save Grades</button>
                        </div>
                        <div class="col-md-6">
                        <button id="add_grades_in_cancels">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" style="overflow: auto;" id="edit_grades_win">
            <div class="changesubwindow editexams animate">
                <div class="conts">
                    <p class="funga" id="close_edit_grade_win">&times</p>
                    <h5 class="text-center"><b>Edit Grades</b></h5>
                </div>
                <div class="container" id="">
                    <div class="container p-0 mx-0 my-2">
                        <h6>Set Grades for <b id="edit_grades_subject_name">Subject Name</b></h6>
                        <h6><b>Note</b></h6>
                        <p>1 Add the grade list first before saving</p>
                        <p class="hide" id="my_grades_edits"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 my-2">
                            <label for="edit_maximum_marks" class="form-control-label"><b>Starting from :</b></label>
                            <input readonly type="number" class="form-control w-100" id="edit_maximum_marks" placeholder="starting from">
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="edit_minimum_marks" class="form-control-label"><b>To :</b></label>
                            <input type="number" class="form-control w-100" id="edit_minimum_marks" placeholder="To">
                        </div>
                        <div class="col-md-6 my-2">
                            <label for="edit_grade_score" class="form-control-label"><b>Grade Name: </b></label>
                            <input type="text" class="form-control w-100" id="edit_grade_score" placeholder="Grade Name eg 'A'">
                        </div>
                        <div class="col-md-6 my-2">
                            <p id="edit_grades_btn" class="block_btn">Add Grade</p>
                        </div>
                    </div>
                    <p id="edit_error_handler_graders"></p>
                    <hr>
                    <div class="container" id="edit_grades_lists">
                        <!-- <h6 class="text-center">Grade List</h6>
                        <table class="table">
                            <tr>
                                <th>Grade</th>
                                <th>Min Marks</th>
                                <th>Max Marks</th>
                            </tr>
                            <tr>
                                <td>A (Plain)</td>
                                <td>50</td>
                                <td>40</td>
                            </tr>
                        </table> -->
                        <p>Kindly add the grades for the respective subject <br> Grades will appear here.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button id="edit_add_grades_in">Save Grades</button>
                        </div>
                        <div class="col-md-6">
                        <button id="edit_add_grades_in_cancels">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="add_classes_win">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_add_class_win">&times</p>
                    <h6>Add Course Level</h6>
                </div>
                <div class="conts" id="">
                    <div class="add_expenses">
                        <label class="form-control-label" for="input_text">Enter Course Level Name: <br></label>
                        <input class="form-control" type="text" name="input_text" id="input_text" placeholder="Course name">
                        <img src="images/ajax_clock_small.gif" class="hide" id="add_class_clock">
                        <p id="add_class_outputtxt"></p>
                    </div>
                    <div class="btns">
                        <button type="button" id="add_class_btn">Add Course Level</button>
                        <button type="button" id="close_add_cl_win">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="del_classes_win">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_del_class_win">&times</p>
                    <h6 class="text-center">Delete Class</h6>
                </div>
                <div class="conts" id="">
                    <div class="add_expenses">
                        <input type="hidden" name="" id="delete_classes_id">
                        <p class="text-center">Are you sure you want to delete "<b id="delete_class_id"></b>". <br> This action is permanent!</p>
                    </div>
                    <div class="btns">
                        <button type="button" id="del_class_btn">Yes, Delete</button>
                        <button type="button" id="close_del_cl_win">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="add_course_window">
            <div class="changesubwindow2 addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_add_course_win">&times</p>
                    <h6 class="text-center">Add Course</h6>
                </div>
                <div class="container border border-dark rounded p-2 w-75">
                    <div class="form-group">
                        <label class="form-control-label" for="course_input_text">Enter Course Name: <br></label>
                        <input class="form-control" type="text" name="course_input_text" id="course_input_text" placeholder="eg: Information Technology">
                    </div>
                    <div class="form-group">
                        <label for="level_lists" class="form-control-label">Level Available</label> (<small class="text-secondary">Select the level the course is to be offered!</small>)
                        <img src="images/ajax_clock_small.gif" class="" id="add_course_clock">
                        <div id="level_available_course_name" class="w-75">
                            <p class="text-danger">Set the course levels first before setting up courses!</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_of_terms" class="form-control-label">No of Terms</label>
                        <input type="number" name="no_of_terms" id="no_of_terms" class="form-control" placeholder="No of Terms(5 terms)">
                    </div>
                    <div class="form-group">
                        <label for="termly_fees" class="form-control-label">Termly Fees</label>
                        <input type="number" name="termly_fees" id="termly_fees" class="form-control" placeholder="Termly fees(Kes 20,000)">
                    </div>
                    <div class="form-group my-2">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="duration_in_figures" class="form-control-label">Term Durations</label>
                                <input type="number" name="term_duration" id="term_duration" class="form-control" placeholder="Duration e.g, 10 months">
                            </div>
                            <div class="col-md-6">
                                <label for="duration_intervals" class="form-control-label">Duration Intervals</label>
                                <select name="duration_intervals" id="duration_intervals" class="form-control">
                                    <option value="year">Years</option>
                                    <option value="months" selected>Months</option>
                                    <option value="week">Weeks</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="department_list" class="form-control-label">Course Department</label>
                        <img src="images/ajax_clock_small.gif" class="" id="display_my_departments">
                        <div id="department_list_window" class="w-75">
                            <p class="text-danger">Set the course departments!</p>
                        </div>
                        <p id="add_course_outputtxt"></p>
                    </div>
                </div>
                <div class="btns">
                    <button type="button" id="add_course_btn">Add Course</button>
                    <button type="button" id="close_add_course_window">Close</button>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="edit_course_window">
            <div class="changesubwindow2 addsubject animate p-2">
                <div class="conts">
                    <p class="funga" id="close_edit_course_win">&times</p>
                    <h6 class="text-center">Edit Course</h6>
                </div>
                <div class="conts" id="">
                    <div class="add_expense">
                        <div class="form-control">
                            <label class="form-control-label" for="course_edit_input_text">Enter Course Name: <br></label>
                            <input type="hidden" id="course_id_holder">
                            <input class="form-control w-100 mx-0" type="text" name="course_edit_input_text" id="course_edit_input_text" placeholder="eg: Information Technology">
                        </div>
                        <div class="form-control">
                            <label for="level_lists" class="form-control-label">Level Available</label> (<small class="text-secondary">Select the level the course is to be offered!</small>)
                            <img src="images/ajax_clock_small.gif" class="" id="edit_course_clock">
                            <div id="level_available_course_name_edit">
                                <p class="text-danger">Set the course levels first before setting up courses!</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_no_of_terms" class="form-control-label">No of Terms</label>
                            <input type="number" name="edit_no_of_terms" id="edit_no_of_terms" class="form-control" placeholder="No of Terms(5 terms)">
                        </div>
                        <div class="form-group">
                            <label for="edit_termly_fees" class="form-control-label">Termly Fees</label>
                            <input type="number" name="edit_termly_fees" id="edit_termly_fees" class="form-control" placeholder="Termly fees(Kes 20,000)">
                        </div>
                        <div class="form-group my-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_term_duration" class="form-control-label">Term Durations</label>
                                    <input type="number" name="edit_term_duration" id="edit_term_duration" class="form-control" placeholder="Duration e.g, 10 months">
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_duration_intervals" class="form-control-label">Duration Intervals</label>
                                    <select name="edit_duration_intervals" id="edit_duration_intervals" class="form-control">
                                        <option value="year">Years</option>
                                        <option value="months" selected>Months</option>
                                        <option value="week">Weeks</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-control">
                            <label for="department_list" class="form-control-label">Course Department</label>
                            <img src="images/ajax_clock_small.gif" class="" id="display_my_departments_edit">
                            <div id="department_list_window_edit">
                                <p class="text-danger">Set the course levels first before setting up courses!</p>
                            </div>
                            <p id="edit_course_outputtxt"></p>
                        </div>
                    </div>
                    <div class="btns">
                        <button type="button" id="Edit_course_btn">Edit Course</button>
                        <button type="button" id="close_Edit_course_window">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="active_hours_window">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_active_hours">&times</p>
                    <h6>Change active hours</h6>
                </div>
                <div class="conts" id="">
                    <p>Change the hours your users will be allowed to use the system</p>
                    <div class="add_expenses">
                        <label class="form-control-label" for="from_timer">From: <br></label>
                        <input class="form-control" type="time" name="from_timer" id="from_timer">
                        <label class="form-control-label" for="to_timer"><br> To: <br></label>
                        <input class="form-control" type="time" name="to_timer" id="to_timer">
                        <img src="images/ajax_clock_small.gif" class="hide" id="active_hour_clocker">
                    </div>
                    <div class="btns">
                        <button type="button" id="change_active_btn">Change time</button>
                        <button type="button" id="close_active_hours1">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="acad_timetable_win">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_time_tables">&times</p>
                    <h6>Change academic calender</h6>
                </div>
                <p>Change the school`s calender for the whole year.</p>
                <div class="dates_holder" id="">
                    <div class="add_expenses on_win fine" id="term_ones">
                        <h6>Term One</h6>
                        <label class="form-control-label" for="term_one_start">Term one start date: <br></label>
                        <input class="form-control" type="date" name="term_one_start" id="term_one_start">
                        <label class="form-control-label" for="term_one_closing"><br>Term one Closing date: <br></label>
                        <input class="form-control" type="date" name="term_one_closing" id="term_one_closing">
                        <label class="form-control-label" for="term_one_end"><br>Term one ending date: <br></label>
                        <input class="form-control" type="date" name="term_one_end" id="term_one_end">
                    </div>
                    <div class="add_expenses on_win hide" id="term_twos">
                        <h6>Term Two</h6>
                        <label class="form-control-label" for="term_two_start">Term two start date: <br></label>
                        <input class="form-control" type="date" name="term_two_start" id="term_two_start">
                        <label class="form-control-label" for="term_two_closing"><br>Term two Closing date: <br></label>
                        <input class="form-control" type="date" name="term_two_closing" id="term_two_closing">
                        <label class="form-control-label" for="term_two_end"><br>Term two ending date: <br></label>
                        <input class="form-control" type="date" name="term_two_end" id="term_two_end">
                    </div>
                    <div class="add_expenses on_win fine hide" id="term_threes">
                        <h6>Term Three</h6>
                        <label class="form-control-label" for="term_three_start">Term three start date: <br></label>
                        <input class="form-control" type="date" name="term_three_start" id="term_three_start">
                        <label class="form-control-label" for="term_three_closing"><br>Term three Closing date: <br></label>
                        <input class="form-control" type="date" name="term_three_closing" id="term_three_closing">
                        <label class="form-control-label" for="term_three_end"><br>Term three ending date: <br></label>
                        <input class="form-control" type="date" name="term_three_end" id="term_three_end">
                    </div>
                </div>
                <div class="conts">
                    <p id="err_win_handlers"></p>
                </div>
                <div class="btns">
                    <p class="link" id="next_page">Next >></p>
                    <p class="link" id="prev_page">
                        << Previous</p>
                </div>
                <div class="conts hide" id="save_opts">
                    <div class="btns">
                        <button type="button" id="Change_acad_cal">Save</button>
                        <button type="button" id="close_acad_cal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirmpaymentwindow hide" id="admission_ess">
            <div class="changesubwindow addsubject animate">
                <div class="conts">
                    <p class="funga" id="close_win_admissions">&times</p>
                    <h6>Add admission essentials</h6>
                </div>
                <p>Add admission essentials that are to be accompanied with by the student during admissions.</p>
                <div class="conts">
                    <label class="form-control-label" for="adm_ess"><br>Component name: <br></label>
                    <input class="form-control" type="text" name="adm_ess" id="adm_ess" placeholder="Admission essential">
                </div>
                <div class="conts">
                    <p id="admission_essentials_err_handler"></p>
                </div>
                <div class="btns">
                    <button type="button" id="save_comp">Add</button>
                    <button type="button" id="close_win">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="confirmpaymentwindow hide" id='change_dp_win'>
        <div class="confirmpayment animate">
            <h6>Change Profile Image</h6>
            <p>Select an image:<br></p>
            <input class="form-control" type="file" name="dp_image" id="dp_image">
            <p id="imagenotifier"></p>
            <img class="hide" src="images/ajax_clock_small.gif" alt="loading btb" id="insert_images">
            <div class="btns">
                <button type='button' id='change_my_dp_img'>Yes</button>
                <button type='button' id='close_change_dp'>No</button>
            </div>
        </div>
    </div>
    <div class="confirmpaymentwindow hide" id='change_sch_dp_win'>
        <div class="confirmpayment animate">
            <h6>Change School profile Image</h6>
            <label class="form-control-label">Select an image:<br></label>
            <input class="form-control" type="file" name="school_dp" id="school_dp">
            <p id="imagenotifiered"></p>
            <div class="btns">
                <button type='button' id='change_sch_dp_img'>Yes</button>
                <button type='button' id='close_sch_change_dp'>No</button>
            </div>
        </div>
    </div>
    <!-- change student dp -->
    <div class="confirmpaymentwindow hide" id='change_studes_dp_win'>
        <div class="confirmpayment animate">
            <h6>Change Student  Image</h6>
            <label class="form-control-label">Select an image:<br></label>
            <input class="form-control" type="file" name="students_image" id="students_image">
            <p id="imagenotifiered_studes"></p>
            <div class="btns">
                <button type='button' id='change_studes_dp_img'>Yes</button>
                <button type='button' id='close_studes_change_dp'>No</button>
            </div>
        </div>
    </div>
    <div class="animate hide" id="imagers">
        <p class="closers" id="close_img_viewer">&times</p>
        <img src="images/dp.png" alt="" id="image_viewer" srcset="">
    </div>
    <div class="dialogholder hide" id="unenroll_confirm">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message" id="message">
                <p class="text-danger">All your staff payroll information will be erased, including all salary payments done to them!</p>
                <p>Are you sure you want to un-enroll <b><span id="name_sake">your staff</span></b> from the payroll system?</p>
            </div>
            <div class="buttons">
                <button type='button' id='yes_unenroll'>Yes</button>
                <button type='button' id='no_unenroll'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="pay_salo_winds">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message" id="message">
                <p>Are you sure you want to make payments for <b><span id="name_sake_2">unknown</span></b> of Kes <b><span id="amount_salo">0.00</span></b> ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='yes_salo_pay'>Yes</button>
                <button type='button' id='no_salo_pay'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="delete_staff_perm">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message" id="message">
                <p>Are you sure you want to delete <b><span id="staff_name_del">unknown</span></b> permanently ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='yes_delete_permanently'>Yes</button>
                <button type='button' id='no_delete_permanently'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="delete_revenue_category">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message">
                <input type="hidden" id="revenue_index" value="-1">
                <p>Are you sure you want to delete <b><span id="revenue_category_name_holder">unknown</span></b> the revenue permanently ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='yes_delete_revenue_category'>Yes</button>
                <button type='button' id='no_delete_revenue_category'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="delete_course_parmenently">
        <div class="dialogwindow animate2">
            <h6>Confirm Action <img class="hide" src="images/ajax_clock_small.gif" id="delete_course_pamernently"></h6>
            <div class="message" id="">
                <p>Are you sure you want to delete <b><span id="course_name_placeholder">unknown</span></b> course permanently ?</p>
                <input type="hidden" id="course_id_holder_delete">
                <small class="text-secondary text-left"><b>Note:</b> <br> (This action is not reversible)</small>
            </div>
            <p id="error_handler_course_del"></p>
            <div class="buttons">
                <button type='button' id='yes_delete_permanently_course'>Yes</button>
                <button type='button' id='no_delete_permanently_course'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="confirm_accepting_leaf">
        <div class="dialogwindow animate2">
            <h6>Confirm <img class="hide" src="images/ajax_clock_small.gif" id="leaves_acceptance_loaders"></h6>
            <div class="message" id="event_message">
                <input type="hidden" name="" id="accept_leave_ids">
                <p>Are you sure you want to accept <b><span id="employees_names_leaves">unknown</span></b></p>
            </div>
            <p id="leaves_accept_err_handlers"></p>
            <div class="buttons">
                <button type='button' id='yes_accept_leaves'>Yes</button>
                <button type='button' id='no_accept_leaves'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="confirm_declining_leaf">
        <div class="dialogwindow animate2">
            <h6>Confirm <img class="hide" src="images/ajax_clock_small.gif" id="leaves_declining_loaders"></h6>
            <div class="message" id="event_message">
                <input type="hidden" name="" id="reject_leave_ids">
                <p>Are you sure you want to reject <b><span id="employees_names_leaves_reject">unknown</span></b></p>
            </div>
            <p id="leaves_reject_err_handlers"></p>
            <div class="buttons">
                <button type='button' id='yes_reject_leaves'>Yes</button>
                <button type='button' id='no_reject_leaves'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="delete_studs_perm">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message" id="message">
                <p>Are you sure you want to delete <b><span id="stud_name_del">unknown</span></b> permanently ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='delete_student'>Yes</button>
                <button type='button' id='no_delete_students'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="add_clubs_win">
        <div class="dialogwindow animate">
            <h6>Add Sports House </h6>
            <div class="container my-2">
                <label for="club_name" class="form-control-label"><b>Sports House Name / Club:</b></label>
                <p id="clubs_errors_in"></p>
                <input type="text" class="form-control w-100 mx-0" id="club_name" placeholder="Sports House Name / Club">
            </div>
            <div class="buttons align-center">
                <button type='button' id='add_clubs_btn'>Add</button>
                <button type='button' id='cancel_add_sports_btn'>Cancel</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="set_admission_number_prefix">
        <div class="dialogwindow animate">
            <h6>Set Admission Number Prefix <img src="images/ajax_clock_small.gif" class="hide" id="set_admission_number_prefix_loader"></h6>
            <p class="hide" id="admission_numbers_prefix_value"></p>
            <div class="container my-2">
                <label for="admission_number_prefix" class="form-control-label"><b>Admission Number Prefix:</b></label>
                <input type="text" class="form-control w-100 mx-0" id="admission_number_prefix" placeholder="Admission Number Prefix">
            </div>
            <div class="buttons align-center">
                <button type='button' id='confirm_set_admission_number'>Edit</button>
                <button type='button' id='cancel_set_admission_number'>Cancel</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="edit_clubs_win">
        <div class="dialogwindow animate">
            <h6>Edit Sports House </h6>
            <div class="container my-2">
                <label for="club_edit_name" class="form-control-label"><b>Sports House Name / Club:</b></label>
                <p id="clubs_edit_errors_in"></p>
                <p class="hide" id="clubs_ids"></p>
                <input type="text" class="form-control w-100 mx-0" id="club_edit_name" placeholder="Sports House Name / Club">
            </div>
            <div class="buttons align-center">
                <button type='button' id='edit_clubs_btn'>Edit</button>
                <button type='button' id='cancel_edit_sports_btn'>Cancel</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="close_window_tutorial">
        <div class="container h-100 w-100 my-5 mx-auto">
            <p class="bg-white" style="width: fit-content;">Click outside the video to close.</p>
            <iframe allowfullscreen id="tutorial_windows" src="https://www.youtube.com/embed/bA7yaVvS81Q" class="w-100" height="80%" >
            </iframe>
        </div>
    </div>
    <div class="dialogholder hide" id="delsubconfirmwin">
        <div class="dialogwindow animate2">
            <h6>Confirm</h6>
            <div class="message" id="mssg_name">
                <p>Are you sure ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='delsubyes'>Yes</button>
                <button type='button' id='delsubno'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="printer_window">
        <div class="dialogwindow animate2">
            <div class="w-100 row">
                <div class="col-md-10">
                    <h6 class="text-center">Print Exams : <span id="exsms_name"></span> </h6>
                </div>
                <div class="col-md-1">
                    <p class="funga text-lg" style="cursor: pointer;" id="close_exams_printing">&times</p>
                </div>
            </div>
            <div class="message" id="mssg_name">
                <p>Below you will be able to print this exam in different formats.</p>
            </div>
            <form method="POST" action="reports/reports.php" target="_blank" class="w-100">
                <input type="hidden" name="exam_ids_printing" id="exam_ids_printing">
                <label for="what_to_print" class="form-control-label"><b>Select what to print:</b></label>
                <select required name="what_to_print" id="what_to_print" class="form-control">
                    <option value="" hidden>Select option</option>
                    <option value="exams_filling_slip">Exams Recording Slip</option>
                    <option value="exams_marks">Students Exams Marks</option>
                    <option value="student_report_card">Students Results Slip</option>
                </select>
                
                <!-- class doing to  -->
                <label for="classes_for_exams" class="form-control-label"><b>Select classes: </b></label>
                <span id="all_classes_here"></span>
                <button class="my-2" type="submit">Print <i class="fas fa-print"></i></button>
                <button class="my-2 mx-1" id="canc_exam_print" type="button">Cancel</button>
            </form>
        </div>
    </div>
    <div class="dialogholder hide" id="delete_clubs_window">
        <div class="dialogwindow animate2">
            <h6>Confirm Delete</h6>
            <div class="message" id="">
                <p class="hide" id="clubs_ids_delete"></p>
                <p>Are you sure you want to delete <b id="sports_house_name">Null</b> ?</p>
            </div>
            <div class="buttons">
                <button type='button' id='delete_clubs_yes'>Yes</button>
                <button type='button' id='cancel_delete_clubs'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="prompt_timetable">
        <div class="dialogwindow animate">
            <h6>Confirm</h6>
            <div class="message" id="">
                <p>Are you want to restart the process ? <br><strong>Note:</strong> all the information collected will be lost.</p>
            </div>
            <div class="buttons">
                <button type='button' id='promptyestt'>Yes</button>
                <button type='button' id='promptnott'>No</button>
            </div>
        </div>
    </div>
    <div class="dialogholder hide" id="prompt_del_timetable">
        <div class="dialogwindow animate">
            <h6>Confirm</h6>
            <div class="message" id="">
                <p>Are you want to delete the selected timetable ? <br><strong>Note:</strong> This action is irreversible.</p>
            </div>
            <p class="hide" id="ttimetableid"></p>
            <div class="buttons">
                <button type='button' id='promptyesttt'>Yes</button>
                <button type='button' id='promptnottt'>No</button>
            </div>
        </div>
    </div>
    </div>
    <div class="copyright">
        <p> Last System Update: 26th January 2024 @ 10:01AM || Ladybird SMIS Copyright © 2020 - <?php echo date("Y", strtotime("3 hour")); ?> | All rights reserved</p>
    </div>
    <script src="assets/JS/functions.js"></script>
    <script src="assets/JS/print.min.js"></script>
    <script>
        cObj("logout").onclick = function() {
            redirect("login.php");
            //change values to be shared
            let datapass = "?changeval=true";
            sendData("GET", "login/changevalues.php", datapass, cObj("logout"));
        }
    </script>
    <script src="assets/JS/admissions.js"></script>
    <script src="assets/JS/dashboardajax.js"></script>
    <script src="assets/JS/finance.js"></script>
    <script src="assets/JS/feesTable.js"></script>
    <script src="assets/JS/expense.js"></script>
    <script src="assets/JS/transport.js"></script>
    <script src="assets/JS/academics.js"></script>
    <script src="assets/JS/boarding.js"></script>
    <script src="assets/JS/sms.js"></script>
    <script src="assets/JS/my_reports.js"></script>
    <script src="assets/JS/chart.min.js"></script>
    <script src="assets/JS/chartconfig.js"></script>

    <script>
        tinymce.init({
        selector: '#email_messages',
        plugins: 'anchor autolink charmap codesample emoticons link lists searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        menubar: true,
        toolbar: true
        });
        
        tinymce.init({
            selector: '#email_editored',
            plugins: ["link","code","media","image","emoticons"],
            // plugins: 'anchor autolink charmap codesample emoticons link lists searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            menubar: true,
            toolbar: true,
            setup : function(ed) {
                ed.on('keyup', function(e) {
                    working_onit(ed);
                });
            }
        });
        
        tinymce.init({
            selector: '#email_contents_exam_reports',
            plugins: ["link","code","media","image","emoticons"],
            // plugins: 'anchor autolink charmap codesample emoticons link lists searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            menubar: true,
            toolbar: true,
            setup : function(ed) {
                ed.on('keyup', function(e) {
                    editSamplesData(ed);
                });
            }
        });
    </script>
</body>

</html>