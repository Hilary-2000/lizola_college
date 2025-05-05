<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/developer.css">
    <link rel="stylesheet" href="/sims/assets/CSS/font-awesome/css/all.css">
    <link rel="shortcut icon" href="../../images/ladybird.png" type="image/x-icon">
    <title>Developer Dashboard</title>
</head>
<body>
    <?php
        session_start();
    ?>
    <div class="mainpage">
        <div class="top_bar"  id="navbar">
            <div class="logo">
                <img src="../../images/ladybird.png" alt="Logo">
                <h3>Ladybird Developer</h3>
            </div>
            <div class="menu fa-xs">
                <menu><i class="fa fa-home"></i> Home</menu>
                <menu>Register School</menu>
                <menu>Register user</menu>
                <menu id="profile_user"><i class="fa fa-user" aria-hidden="true"></i> 
                <?php if(isset($_SESSION['fullname'])){
                            if ($_SESSION['fullname'] != "0") {
                                echo $_SESSION['fullname'];
                            }else{
                                header('HTTP/1.1 301 Moved Permanently');
                                header('Location: /sims/developer/');
                            }
                        }else{
                            header('HTTP/1.1 301 Moved Permanently');
                            header('Location: /sims/developer/');
                        }
                        ?></menu>
                <div class="user_menu hide" id="more-opt-win">
                    <div class="anchor fa-rotate-45">

                    </div>
                    <div class="sect">
                        <p><i class="fa fa-sync"></i> Update Profile</p>
                    </div>
                    <div class="sect ma-top">
                        <p id="logout-in"><i class="fa fa-sign-out-alt"></i> Logout</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="sec-mainpage content">
            <div class="sidemenu" id="menu-btn-show">
                <p class="menu-1 fa-xs"><i class="fa fa-bars"></i> Menu</p>
            </div>
            <div class="sidemenu hide fa-sm" id="menu-window">
                <h3>Menu</h3>
                <!-- <menu><i class="fa fa-home"></i> - Home</menu> -->
                <menu id="enrol-sch-btn"><i class="fa fa-school"></i> - Schools</menu>
                <menu><i class="fa fa-money-check-alt"></i> - Payment</menu>
                <menu><i class="fa fa-users"></i> - Users</menu>
                <menu><i class="fa fa-users-cog"></i> - My profile</menu>
                <p class="close-menu" id="close-menu-win"><i class="fa fa-times"></i></p>
            </div>
            <div class="developer-pad">
                <div class="welcome-dash" id="welcome-dash">
                    <div class="welcome-pad">
                        <h3 class="fa-fw">Developer Dashboard</h3>
                    </div>
                    <div class="shorts fa-xs">
                        <p><i class="fa fa-check"></i> Schools available: <b id="school_present">0 schools</b>.</p>
                        <p><i class="fa fa-check"></i> Users available: <b id="user_present">300 users</b>.</p>
                        <p><i class="fa fa-check"></i> Active Users: <b id="active_user">11 users</b>.</p>
                        <p><i class="fa fa-check"></i> Developer Active: <b>2 Dev</b>.</p>
                    </div>
                    <!-- <div class="reminders">
                        <h4 class="fa-fw">Check out your reminders</h4>
                        <div class="reminder-in4">
                            <p>1. Add reminder</p>
                        </div>
                    </div> -->
                    <div class="options">
                        <div class="lady-btns fa-xs" id="reg-dev-btn">
                            <p><i></i> Register Dev</p>
                        </div>
                        <div class="lady-btns fa-xs">
                            <p><i></i> Register School</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="developer-pad animate win-pin hide" id="reg-dev">
                <div class="register-dev ">
                    <div class="title fa-fw">
                        <h3>Register Developer</h3>
                        <p class ="fa-xs">Register new developer by filling all of the field <span class="red_notice">*</span></p>
                    </div>
                    <form class ="fa-xs" id="add-user-frm">
                        <div class="sect">
                            <label for="FullName">Enter Fullname: <br></label>
                            <input type="text" id="FullName" placeholder="Enter Fullname">
                        </div>
                        <div class="sect">
                            <label for="role">Role: <br></label>
                            <select name="main-admin" id="main-admin">
                                <option value="" hidden>Select role..</option>
                                <option value="0">Administrator</option>
                                <option value="1">Sales Agent</option>
                            </select>
                        </div>
                        <div class="sect">
                            <label for="username">Username: <br></label>
                            <input type="text" id="username" placeholder="Username">
                        </div>
                        <div class="sect">
                            <label for="passcode">Password: <br></label>
                            <input type="password" id="passcode" placeholder="Enter Password">
                        </div>
                        <div class="sect">
                            <label for="re-passcode">Re - enter Password: <br></label>
                            <input type="password" id="re-passcode" placeholder="Re - enter Password">
                        </div>
                        <p id="err-handler"></p>
                    </form>
                    <div class="options">
                        <div class="lady-btns fa-xs" id="enter-dev-infor">
                            <p><i></i> Register  <i class="fa fa-code"></i></p>
                        </div>
                        <p><img class="hide" id="clock_ajax1" src="../../images/ajax_clock_small.gif" alt="ajax"></p>
                    </div>
                </div>
            </div>
            <div class="developer-pad win-pin animate " id="sch-wind">
                <div class="register-dev">
                    <div class="title fa-fw">
                        <h3><i class="fa fa-school"></i> School Information</h3>
                        <p class="fa-xs">Below will be able to view the school list and browse more for their information</p>
                    </div>
                </div>
                <div class="">
                    <!-- Display schools registered in the database -->
                    <div class="tables" id='sch-tbl-info'>
                        <div class="conts hide" id="school_loads">
                            <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
                        </div>
                        <div class="table-holder fa-xs" id="sch-inform">

                        </div>
                        <!-- <table>
                            <tr>
                                <th>Id</th>
                                <th>School Name</th>
                                <th>User(s)</th>
                                <th>Administrator</th>
                                <th>Contact</th>
                                <th>School Mail</th>
                                <th>DB Name</th>
                            </tr>
                            <tr class="row-hover">
                                <td>1.</td>
                                <td class="view_opt">Testimony Academy</td>
                                <td>22</td>
                                <td>Mrs Maria Wakio</td>
                                <td>0720002156</td>
                                <td>hilaryme45@gmail.com</td>
                                <td>testimonytbl1</td>
                            </tr>
                            <tr class="row-hover">
                                <td>1.</td>
                                <td>Testimony Academy</td>
                                <td>22</td>
                                <td>Mrs Maria Wakio</td>
                                <td>0720002156</td>
                                <td>hilaryme45@gmail.com</td>
                                <td>testimonytbl1</td>
                            </tr>
                            <tr class="row-hover">
                                <td>1.</td>
                                <td>Testimony Academy</td>
                                <td>22</td>
                                <td>Mrs Maria Wakio</td>
                                <td>0720002156</td>
                                <td>hilaryme45@gmail.com</td>
                                <td>testimonytbl1</td>
                            </tr>
                        </table> -->
                    </div>
                    <!-- end -->

                    <!-- View single table information in detail -->
                    <div class="sch-details hide" id="sch-details">
                        <div class="conts hide" id="sch-data-retrieve">
                            <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
                        </div>
                        <div class="title fa-fw fa-xs">
                            <div class="logos">
                                <img id="sch_dp_inform" src="../../images/ladybird.png" alt="Logo">
                            </div>
                            <h3 id="my-sch-name">Testimony Academy</h3>
                            <p>Motto: <span id="my-motto">Education is a resource.</span></p>
                            <div class="conts hide">
                                <p id = "sch-data-infor">popo</p>
                                <p id="sch-id-inside"></p>
                            </div>
                        </div>
                        <div class="sch-details-holder">
                            <div class="detail-sch-infor">
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>Contact Information</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-envelope"></i> P.O BOX: <span class="change-able ch" id="box-no"> 853 </span> - (<span class="change-able ch" id="box-code">50400</span>)</p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-paper-plane"></i> Email: <span class="change-able ch" id="sch-mail"> hilaryme45@gmail.com</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-phone-alt"></i> Phone: <span class="change-able ch" id="sch-contact"> 0743551250</span></p>
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>School Information</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport"></i> KNEC code: <span class="change-able " id="sch-code"> 35601110.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-monument"></i> Name: <span class="change-able ch" id="sch-name"> Testimony Grammar School </span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-user-shield"></i> Administrator Name : <span class="change-able ch" id="sch-admin"> Maria Wakio Ngige</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-map-marker-alt"></i> Location: <span> County: <span class="change-able ch" id="sch-county" > Busia</span>, County : <span class="change-able ch" id="sch-country">Kenya</span></span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport"></i> School Motto: <span class="change-able ch" id="sch-motto"> Education is a resource.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport"></i> School Vision: <span class="change-able ch" id="sch-vission"> Education is a resource.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport"></i> School Mission: <span class="change-able ch" id="sch-mission"> Education is a resource.</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="detail-sch-infor">
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>Account Information</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-database"></i> Database Name: <span class="change-able " id="sch-dbname">testimonytbl1</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport"></i> Activated : <span class="change-able " id="activated">Activated</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-money-bill-alt"></i> Last time Paid: <span class="change-able">TERM_1 2021</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="backs">
                            <div class="lady-btns fa-xs" id="go-to-sch-table">
                                <p><i class="fa fa-arrow-left"></i> Back to list</p>
                            </div>
                        </div>
                    </div>
                    <!-- end -->

                    <!-- The user information is diplayed here -->
                    <div class="sch-details hide" id="user_information">
                        <div class="conts hide" id="schl_oad">
                            <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
                        </div>
                        <div class="table-holder fa-xs" id="users-inform">
                            <table>
                                <tr>
                                    <th>No</th>
                                    <th>Fullname</th>
                                    <th>Gender</th>
                                    <th>Deleted</th>
                                    <th>Activated</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Hilary Ngige</td>
                                    <td>Male</td>
                                    <td>No</td>
                                    <td>Yes</td>
                                </tr>
                            </table>
                        </div>
                        <div class="backs">
                            <div class="lady-btns fa-xs" id="go-to-sch">
                                <p><i class="fa fa-arrow-left"></i> Back to list</p>
                            </div>
                        </div>
                    </div>
                    <!-- end -->

                    <!-- View single user detailed information -->
                    <div class="sch-details hide" id="user-detail">
                        <div class="conts hide" id="user_loadings">
                            <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
                        </div>
                        <div class="title fa-fw fa-xs">
                            <div class="logos">
                                <img id="user_dp_inform" src="../../images/ladybird.png" alt="Logo">
                            </div>
                            <h3 id="mynames-user">ADALA HILLARY NGIGE</h3>
                            <div class="conts hide">
                                <p id = "sch-data-infor1">popo</p>
                                <p id="sch-id-inside1"></p>
                            </div>
                        </div>
                        <div class="sch-details-holder">
                            <div class="detail-sch-infor">
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>Personal Information</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-passport fa-fx"></i> FullName: <span class="change-able tx" id="user-name"> ESMOND BWIRE.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-male fa-fx"></i> Gender: <span class="change-able gen1" id="user-gender"> Male.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-birthday-cake fa-fx"></i> Date of birth: <span class="change-able dt" id="user-dob"> 2021-09-22.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-id-card fa-fx"></i> Id number: <span class="change-able num" id="user-id-no"> 35601110.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-phone-alt fa-fx"></i> Phone number: <span class="change-able num" id="user-phone"> 0743551250.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-map-marker-alt fa-fx"></i> Address: <span class="change-able tx" id="user-address"> Kitale Ke.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-envelope-square fa-fx"></i> Email: <span class="change-able tx" id="user-gmail"> hilaryme45@gmail.com.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-id-card-alt fa-fx"></i> TSC No: <span class="change-able tx" id="user-tsc"> 35601110.</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="detail-sch-infor">
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>Status:</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-check fa-fx"></i> Authority: <span class="change-able " id="user-authority"> Head Teacher.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-check fa-fx"></i> Deleted: <span class="change-able yesno" id="user-del"> Deleted.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-check fa-fx"></i> Activated: <span class="change-able yesno" id="user-active"> Active.</span></p>
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="cont-title">
                                        <h4>Credentials:</h4>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-check fa-fx"></i> Username: <span class="change-able tx" id="user-u-name"> Head Teacher.</span></p>
                                    </div>
                                    <div class="data">
                                        <p><i class="fa fa-check fa-fx"></i> Password: <span class="change-able tx" id="user-password"> <i class="fa fa-times"></i><i class="fa fa-times"></i><i class="fa fa-times"></i><i class="fa fa-times"></i><i class="fa fa-times"></i></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="backs">
                            <div class="lady-btns fa-xs" id="go-to-user-list">
                                <p><i class="fa fa-arrow-left"></i> Back to list</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start of the field change window to recieve type input -->
    <div class="modal_win hide" id="change-field-win">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="field_col"></p>
                <p id="sch_code"></p>
            </div>
            <p class="close" id="close-win-field"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="field_name">Change <span id="field_names"></span>: <br></label>
                <input type="text" name="field_name" id="field_name" placeholder="Field Value">
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form"></p>
            <div class="conts hide" id="loads_in">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- close window -->
    <!-- Start of the select option of the window -->
    <div class="modal_win hide" id="activated-win">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="field_col1"></p>
                <p id="sch_code1"></p>
            </div>
            <p class="close" id="close-win-field1"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="sctive">Activated: <br></label>
                <select name="sctive" id="sctive">
                    <option value="" hidden>Select option..</option>
                    <option id="yes-active" value="1">Yes</option>
                    <option id="no-active" value="0">No</option>
                </select>
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor2">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form2"></p>
            <div class="conts hide" id="loads_in2">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- end of the select window -->
    <!-- Start personal user input type text -->

    <!-- Start texts -->
    <div class="modal_win hide" id="remove-inside">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="column_names1"></p>
                <p id=""></p>
            </div>
            <p class="close" id="close_window3"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="input_text_user1">Change <span id="fld_val1"></span>: <br></label>
                <input type="text" name="input_text_user1" id="input_text_user1" placeholder="Field value">
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor3">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form3"></p>
            <div class="conts hide" id="loads_in2">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- end -->

    <!-- start number -->
    <div class="modal_win hide" id="remove-inside1">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="column_names2"></p>
                <p id=""></p>
            </div>
            <p class="close" id="close_window4"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="input_text_user2">Change <span id="fld_val2"></span>: <br></label>
                <input type="number" name="input_text_user2" id="input_text_user2" placeholder="Field value">
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor5">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form4"></p>
            <div class="conts hide" id="loads_in3">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- start date -->
    <div class="modal_win hide" id="remove-inside2">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="column_names3"></p>
                <p id=""></p>
            </div>
            <p class="close" id="close_window5"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="input_text_user3">Change <span id="fld_val3"></span>: <br></label>
                <input type="date" name="input_text_user3" id="input_text_user3" placeholder="Field value">
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor4">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form5"></p>
            <div class="conts hide" id="loads_in4">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Staff gender -->
    <div class="modal_win hide" id="remove-inside3">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="column_names4"></p>
                <p id=""></p>
            </div>
            <p class="close" id="close_window6"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="input_sel_gen">Change <span id="fld_val4"></span>: <br></label>
                <select name="input_sel_gen" id="input_sel_gen">
                    <option value="" hidden>Select gender</option>
                    <option id="female_user" value="F"><i class="fa fa-female"></i> Female</option>
                    <option id="male_user" value="M"><i class="fa fa-male"></i> Male</option>
                </select>
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor6">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form6"></p>
            <div class="conts hide" id="loads_in5">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Start select 0s and 1s -->
    <div class="modal_win hide" id="remove-inside4">
        <div class="change-window animate">
            <div class="title fa-fw">
                <h3>Change Field</h3>
            </div>
            <div class="hide">
                <p id="column_names5"></p>
                <p id=""></p>
            </div>
            <p class="close" id="close_window7"><i class="fa fa-times"></i></p>
            <div class="field-name fa-xs">
                <label for="change_on_off">Change <span id="fld_val5"></span>: <br></label>
                <select name="change_on_off" id="change_on_off">
                    <option value="" hidden>Select option..</option>
                    <option id="yes_opt" value="1">Yes</option>
                    <option id="no_opt" value="0">No</option>
                </select>
            </div>
            <div class="lady-btns fa-xs" id="save-sub-infor7">
                <p><i class="fa fa-save"></i> Save</p>
            </div>
            <p id="output-form7"></p>
            <div class="conts hide" id="loads_in6">
                <h4 class="options fa-xs"  style='justify-content:center;color:brown;' >Loading ... <img src="../../images/ajax_clock_small.gif" alt="load"></h4>
            </div>
        </div>
    </div>
    <script src="../../assets/JS/functions.js"></script>
    <script src="../assets/js/developer.js"></script>
</body>
</html>