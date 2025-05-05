<div class="contents animate hide" id="findstudents">
    <div class="titled">
        <h2>Manage Students</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Manage student information</p>
            <p class="hide" id="studentinformation" ></p>
        </div>
        <div class="middle1">
            <div class="topsearch">
                <div class="topsearch1">
                    <p><Strong>What you need to know:</Strong></p>
                    <p>- Student information can be retrieved, changed and deleted at this window. <br>- Start by finding the student by the available options</p>
                    <p>- Its recomended that the student is searched by their admission number <small>(the results are accurate)</small></p>
                </div>
                <div class="row my-2">
                    <div class="col-lg-3">
                        <p id="errorSearch"></p>
                        <label for="sach" >Search here by: </label><br>
                        <select class="form-control-select w-100" name="sach" id="sach">
                            <option value="" hidden>Select option..</option>
                            <option value="name">Name</option>
                            <option value="AdmNo">Admission No</option>
                            <option value="class">Course Level</option>
                            <option value="bcno">BC number</option>
                            <option value="allstuds" id ='alstuds'>All students</option>
                            <option value="regtoday" id='regtodays'>Registered today</option>
                        </select>
                    </div>
                    <div class="col-lg-7 row">
                        <div class="col-md-6 mx-auto hide" id="named">
                            <label class="form-control-label" for="name"><b>Enter name:</b> <br></label>
                            <input type="text" name="name" id="name" placeholder="Type name here">
                            <img src="images/ajax_clock_small.gif" class="hide" id="names_loaders_find">
                        </div>
                        <div class="col-md-6 mx-auto hide" id="admnosd">
                            <label class="form-control-label" for="admno"><b>Enter admission no: </b><br></label>
                            <input type="text" name="admno" id="admno" placeholder="Type admission no.">
                            <img src="images/ajax_clock_small.gif" class="hide" id="admnos_loaders_find">
                        </div>
                        <div class="col-md-6 mx-auto hide bg-red" id="classenroll">
                            <label class="form-control-label" for="selclass"><b>Select Course Level:</b> <br></label>
                            <p id="stud_class_find"></p>
                        </div> 
                        <div class="col-md-6 mx-auto hide" id="course_lists_search_bar">
                            <label class="form-control-label" for="course_chosen_search"><b>Select Course:</b> <img class="hide" src="images/ajax_clock_small.gif" id="course_list_find_loader"><br></label>
                            <p id="get_student_class_list" class="text-danger"> Select course level first!</p>
                        </div>
                        <div class="col-md-6 mx-auto hide" id="bcnos">
                            <label class="form-control-label" for="bcnosd"><b>Enter birth certifcate no.</b> <br></label>
                            <input type="text" name="bcnosd" id="bcnosd" placeholder="Enter BC NO.">
                        </div> 
                    </div>
                    <div class="col-lg-2">
                        <span id="findingstudents" class="btn btn-primary btn-sm mt-4 rounded" type="button"><i class="fas fa-search"></i> Search</span>
                    </div>
                </div>
                <hr>
                <p id="swindow"></p>
                <p id="topsearch2"></p>
                
                <!-- <div class="topsearch2" id ="clas_tr_na">
                    <div class="conts">
                        <p id="errorSearch"></p>
                        <label for="sach" >Search here by: </label><br>
                        <select class="form-control-select w-100" name="sach" id="sach">
                            <option value="" hidden>Select option..</option>
                            <option value="name">Name</option>
                            <option value="AdmNo">Admission No</option>
                            <option value="class">Class</option>
                            <option value="bcno">BC number</option>
                            <option value="allstuds" id ='alstuds'>All students</option>
                            <option value="regtoday" id='regtodays'>Registered today</option>
                        </select>
                    </div>
                    <div class="searchwindows hide" id="swindow">
                        <div class="conts hide" id="named">
                            <label for="name">Enter name: <br></label>
                            <input type="text" name="name" id="name" placeholder="Type name here">
                            <img src="images/ajax_clock_small.gif" class="hide" id="names_loaders_find">
                        </div>
                        <div class="conts hide" id="admnosd">
                            <label for="admno">Enter admission no: <br></label>
                            <input type="text" name="admno" id="admno" placeholder="Type admission no.">
                            <img src="images/ajax_clock_small.gif" class="hide" id="admnos_loaders_find">
                        </div>
                        <div class="classenroll hide bg-red" id="classenroll">
                            <label for="selclass">Select Course Level: <br></label>
                            <p id="stud_class_find"></p>
                        </div> 
                        <div class="conts hide" id="course_lists_search_bar">
                            <label for="course_chosen_search">Select Course: <img class="hide" src="images/ajax_clock_small.gif" id="course_list_find_loader"><br></label>
                            <p id="get_student_class_list"> Course list will appear here!</p>
                        </div>
                        <div class="conts hide" id="bcnos">
                            <label for="bcnosd">Enter birth certifcate no. <br></label>
                            <input type="text" name="bcnosd" id="bcnosd" placeholder="Enter BC NO.">
                        </div> 
                    </div>
                    <div class="conts">
                        <span id="findingstudents" class="btn btn-primary rounded" type="button"><i class="fas fa-search"></i> Search</span>
                    </div>
                </div> -->
                <div class="body1 hide" id="class_tr_search">
                        <div class="conts" id="">
                            <label for="class_assigned_tr">Class assigned: <br></label>
                            <input type="text" style="max-width:200px" name="class_assigned_tr" id="class_assigned_tr" placeholder="Class assigned" Readonly>
                            <button type="button" id="display_my_students">Display my students</button>
                        </div>
                </div>
            </div>
            <div class="">
                <div class="otherbtn hide" id="resultsbody">
                </div>
                    <div class="back_button animate hide" id="back_btns" title="Click to dismis">
                        <button class = "my_back_button"  type="button" id="go_back_1" ><img src="images/back.png" alt="back"></button>
                    </div>
                <div class="staffinformed form-group rounded hide" id="viewinformation" >
                    <div class="conts">
                        <!--<h3 class='infortitle' viewinformation><strong>Student information</strong></h3>-->
                        <p id ="updateerrors"></p>
                        <!-- <div class="notification">
                            <div class="ttt">
                                <p><strong>Notice:</strong></p>
                            </div>
                            <div class="conts">
                                <p><i>The student information can only be updated if you are administrator.</i></p>
                            </div>
                        </div> -->
                        <div class="studentdetails">
                            <!-- <div class="dp_images">
                                <img src="images/dp.png" alt="images">
                                <button>Change</button>
                            </div>
                            <div class="stats">
                                <label><strong>Attendance:</strong><br></label>
                                <label class = "spanned"><span>Term 1: 85% <br>Term 2: 77% <br>Term 3: 87%</span></label>
                                <label><br><strong>Admission essentials:</strong><br></label>
                                <label class = "spanned"><span>Tissue, Bread</span>.</label>
                            </div> -->
                            <div class="cont">
                                <div class="row my-1">
                                    <div class="col-md-6">
                                        <p id="boarding_status_changer"></p>
                                        <p style="width: fit-content;" class="link my-2" id="prompt_delete_student"><i class="fas fa-trash"></i> Permanently Delete Student<img class="hide" src="images/ajax_clock_small.gif" id="delete_student_load"></p>
                                        <hr>
                                        <div class="hide">
                                            <p class="my-2">Boarding status: <span id="boarding_status"> <span style="background-color: green; color:white;" class="rounded p-1 ">Enrolled</span> || <span id="enroll_stud_boarding" class="link">Un - Enroll ?</span></span>
                                            <img class="hide" src="images/ajax_clock_small.gif" id="boarding_status_load"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 border-left-0" style="border-left: 1px solid gray;">
                                        <div class="cont">
                                            <p id="dp_local_stud" class="hide"></p>
                                            <p class="text-center"> <img class="hide" src="images/ajax_clock_small.gif" id="student_dp_loader"> <span id="change_student_profile_image" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i> Change</span> <span class="btn btn-danger btn-sm btn-alt" id="delete_dp"><i class="fas fa-trash-alt"></i></span></p>
                                            <p id="dp_locale"></p>
                                        </div>
                                        <div class="container m-auto w-50 d-flex justify-content-center" style="width: 120px;">
                                            <div class="student_images">
                                                <img id="student_image" style="cursor: pointer;" src="images/board.jpg" alt="studen`s photo" class="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="titlesd">
                                <p>Student Personal Data</p>
                            </div>
                            <div class="mx-1 row border border-secondary py-2 bg-light">
                                <div class="col-md-8 bg-infor">
                                    <div class="container">
                                        <h6 class="text-center">Finance Summary</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Total Fees <small>as of <span id="current_term"></span></small>:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="total_amount_to_pay">Kes 10,000</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Last Academic Year Balance:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><span id="lastyr_fees_balance">Kes 10,000</span><span class="link mx-2 <?php if ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0"){}else{echo "hide";}?>" id="edit_last_yr_academic_balance"><i class="fas fa-pen-fancy"></i></span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Fees Discount: <i class="fas fa-info-circle" title="This discount only affects the Regular & Boarding Fees only!. Transport fees won`t be affected by this discount"></i> </b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><span id="fees_discount">10</span><span class="link mx-2 <?php if ($_SESSION['authority'] == "1" || $_SESSION['authority'] == "0"){}else{echo "hide";}?>" id="edit_discounts"><i class="fas fa-pen-fancy"></i></span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Fees Paid as of <span id="current_term2"></span>:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="fees_paid_this_term">Kes 10,000</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Fees Balance:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="fees_balances">Kes 10,000</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Tot Paid Since Joining:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="total_paid_fees">Kes 56,000</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Transport Enrolled:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="transport_enrolled_std_infor">Yes</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>Boarding Enrolled:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="board_enrolled_std_infor">No</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-center">Attendance Statistics</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>This Term:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="attendance_this_term">8/10 (80%)</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><b>This Academic Year:</b></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p id="attendance_this_year">80/100 (80%)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-control d-flex flex-wrap my-2 rounded form-group row">
                                <div class="titles">
                                    <p>Basic information </p>
                                </div>
                                <div class="col-md-4">
                                    <label for="snamed_in" class="form-control-label"><b>Surname: </b><br></label>
                                    <input type="text" class="form-control w-100" autocomplete="off" id="snamed_in"  placeholder ="Surname">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="fnamed_in"><b>First name:</b> <br></label>
                                    <input  class="form-control w-100" type="text" autocomplete="off" id="fnamed_in"  placeholder ="Firstname">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="lnamed_in"><b>Last name: </b><br></label>
                                    <input  class="form-control w-100" type="text" autocomplete="off" id="lnamed_in"  placeholder ="Last Name">
                                </div>
                                <div class="col-md-4">
                                    <img src="images/ajax_clock_small.gif" class="hide" id="class_loaders_id_in">
                                    <label class="form-control-label" for="classed"><b>Course Level: </b><br></label>
                                    <div id="class_holders"></div>
                                    <div id="course_level_error_window"></div>
                                    <input type="hidden" name="" id="course_level_hidden">
                                </div>
                                <div class="col-md-4">
                                    <img src="images/ajax_clock_small.gif" class="hide" id="course_list_edit_loader">
                                    <label class="form-control-label" for="course_chosen_edit"><b>Course: </b><br></label>
                                    <div id="course_list_edit"></div>
                                    <div id="course_chosen_error_window"></div>
                                    <input type="hidden" name="" id="course_chosen_level_hidden">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="dobs"><b>Date of birth </b><br></label>
                                    <input  class="form-control w-100" type="date" autocomplete="off" id="dobs"  max=<?php date("Y-m-d",strtotime("-2 years")) ?>>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="doas"><b>Date of admission </b><br></label>
                                    <input  class="form-control w-100" type="date" autocomplete="off" id="doas"  max=<?php date("Y-m-d",strtotime("-2 years")) ?>>
                                </div>
                                <div class="container col-md-12 my-1 p-2 hide" id="reason_for_leaving_window">
                                    <label for="reason_for_leaving_desc" class="form-control-label"><strong>Reason for leaving</strong></label>
                                    <textarea name="reason_for_leaving_desc" id="reason_for_leaving_desc" cols="30" rows="5" class="form-control" placeholder="Brief description why they transfered">We are here!</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="genders"><b>Gender: </b><br></label>
                                    <select class="form-control w-100" id="genders">
                                        <option value="" hidden>Select gender..</option>
                                        <option id='Male' value="Male">Male</option>
                                        <option id='Female' value="Female">Female</option>
                                    </select>
                                </div>
                                <div class = "col-md-4">
                                    <label class="form-control-label" for="addressed"><b>Address</b></label><br>
                                    <input class="form-control w-100" type="text" autocomplete="off" id="addressed" placeholder="Area of residence">
                                </div>
                                <div class="col-md-4">
                                    <label for="intake_month_edit" class="form-control-label"><b>Intake Month</b></label>
                                    <select name="intake_month_edit" id="intake_month_edit" class="form-control">
                                        <option value="" hidden>Select an Option</option>
                                        <option value="JAN">JAN</option>
                                        <option value="MAY">MAY</option>
                                        <option value="SEP">SEP</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="intake_year_edit" class="form-control-label"><b>Intake Year</b></label>
                                    <select name="intake_year_edit" id="intake_year_edit" class="form-control">
                                        <option value="" hidden>Select an Option</option>
                                        <?php for($index = date("Y"); $index > 2017; $index--):?>
                                            <option value="<?=$index?>"><?=$index?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-control d-flex flex-wrap my-2 rounded form-group row">
                                <div id="course_details_display">
                                    <h4 class="text-center">Course Levels</h4>
                                    <table class="table">
                                        <tr>
                                            <th>Course Level</th>
                                            <th>Course Name</th>
                                            <th>Module Terms</th>
                                            <th>Status</th>
                                            <th>Completed</th>
                                            <th>Period</th>
                                        </tr>
                                        <tr>
                                            <td rowspan="3" style="vertical-align: middle;"><b>Diploma Module 1</b></td>
                                            <td rowspan="3" style="vertical-align: middle;"><b>Engineering</b></td>
                                            <td>Term 1</td>
                                            <td>In-Active</td>
                                            <td>Completed</td>
                                            <td>Jan 12th June 2023 <br> Jan 30th June 2024</td>
                                        </tr>
                                        <tr>
                                            <td>Term 2</td>
                                            <td>In-Active</td>
                                            <td>In-Complete</td>
                                            <td>Jan 12th June 2023 <br> Jan 30th June 2024</td>
                                        </tr>
                                        <tr>
                                            <td>Term 3</td>
                                            <td>Active</td>
                                            <td>In-Complete</td>
                                            <td>Jan 12th June 2023 <br> Jan 30th June 2024</td>
                                        </tr>
                                    </table>
                                </div>
                                <span class="btn btn-secondary btn-sm w-50 mx-auto my-2" id="save_course_progress"><i class="fa fa-save"></i> Save Course Progress <img src="images/ajax_clock_small.gif" class="hide" id="save_course_progress_loader"></span>
                                <div id="error_handler_course_progress"></div>
                            </div>
                            <div class="form-control d-flex flex-wrap my-2 rounded row">
                                <div class="titles">
                                    <p>Numbers</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="adminnos"><b>Admission No.</b> <span style='color:rgb(99, 36, 36);font-size:13px;'>(Readonly)</span> <br></label>
                                    <input  class="w-100 bg-secondary form-control" type="text" readonly  id="adminnos" placeholder = "Adm No.">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="indexnos"><b>Index number </b><br></label>
                                    <input  class="w-100 form-control" type="text"  id="indexnos" placeholder = "Index number">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="bcnno"><b>Birth certifiacte number </b><br></label>
                                    <input  class="w-100 form-control" type="text"  id="bcnno" placeholder = "BC number">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="adm_ess"><b>Admission Essentials </b><br></label>
                                    <p id="admissionessentials_lists"></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="clubs_in_sporters" class="form-control-label"><b>Sports Houses / Clubs</b></label>
                                    <p id="clubs_for_sports_in">
                                    <select name='' class='border border-dark text-xxs form-control bg-light w-50' id='select_clubs_sports' class='form-control'><option value='' id='select_clubs_sports_def' hidden>Select an option</option></select>
                                    </p>
                                </div>
                            </div>
                            <div class="form-control d-flex flex-wrap my-2 rounded row">
                                <div class = "titles">
                                    <p>More information</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="disableds"><b>Disabled: </b></label><br>
                                    <select id="disableds" class="w-100">
                                        <option value="" hidden>Select option..</option>
                                        <option id="Yes" value="Yes">Yes</option>
                                        <option id="No" value="No">No</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-control-label" for="descriptionsd"><b>Disabled description: </b><br></label>
                                    <textarea style='padding:4px 2px;' class="form-control w-100" placeholder="Disabled Description" autocomplete="off" id="descriptionsd" cols="30" rows="5"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label for="medical_histry" class="form-control-label"><b>Medical History</b></label>
                                    <textarea class="form-control" name="medical_histry" id="medical_histry" cols="30" rows="5" placeholder="Medical History Appears here"></textarea>
                                </div>
                                <div class="col-md-12 my-2">
                                    <p class="hide" id="previous_school_json"></p>
                                    <h6 class="text-center">Previous Schools Attended.</h6>
                                    <div class="tableme"><p id="prev_sch_list"></p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="parentsinformation">
                        <!--<h3 class='infortitle'><strong>Parents information</strong></h3>-->
                        <div class="form-control d-flex flex-wrap my-2 rounded">
                            <div class="titles">
                                <p>Student information</p>
                            </div>
                            <p style="color:maroon;font-size:12px;"><u>First Parent</u></p>
                            <div class="form-control d-flex flex-wrap my-2 rounded row">
                                <div class="col-md-4">
                                    <label for="pnamed"><b>Parents name: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pnamed" placeholder='Parents name'>
                                </div>
                                <div class="col-md-4">
                                    <label for="pcontacted"><b>Parents Contacts : </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pcontacted" placeholder='Parents Contacts'>
                                </div>
                                <div class="col-md-4">
                                    <label for="paddressed"><b>Parents residence: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="paddressed" placeholder='Parents Residence'>
                                </div>
                                <div class="col-md-4">
                                    <label for="pemails"><b>Parents Email: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pemails" placeholder='Parents Emails'>
                                </div>
                                <div class="col-md-4">
                                    <label for="parrelationship"><b>Parents Relation: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="parrelationship" placeholder='Parents Emails'>
                                </div>
                                <div class="col-md-4">
                                    <label for="paroccupation1"><b>Parents Occupation: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="paroccupation1" placeholder='Parent`s Occupation'>
                                </div>
                                <div class="col-md-4 hide">
                                    <div class="call_option">
                                        <p class="btn btn-sm btn-success my-2" id="call_phone">Click to call parent</p>
                                    </div>
                                    <div class="call_option">
                                        <p class="btn btn-sm btn-success my-2" id="mail_to">Click to send the parent an email.</p>
                                    </div>
                                </div>
                            </div>
                            <p style="color:maroon;font-size:12px;"><u>Second Parent</u></p>
                            <div class="form-control d-flex flex-wrap my-2 rounded row">
                                <div class="col-md-4">
                                    <label for="pnamed2"><b>Parents name: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pnamed2" placeholder='Parents name'>
                                </div>
                                <div class="col-md-4">
                                    <label for="pcontacted2"><b>Parents Contacts : </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pcontacted2" placeholder='Parents Contacts'>
                                </div>
                                <div class="col-md-4">
                                    <label for="pemails2"><b>Parents Email: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="pemails2" placeholder='Parents Emails'>
                                </div>
                                <div class="col-md-4">
                                    <label for="parrelationship2"><b>Parents Relation: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="parrelationship2" placeholder='Parents Relation'>
                                </div>
                                <div class="col-md-4">
                                    <label for="paroccupation2"><b>Parents Occupation: </b><br></label>
                                    <input class='form-control w-100' type="text" autocomplete="off" id="paroccupation2" placeholder='Parent`s Occupation'>
                                </div>
                                <div class="col-md-4 hide">
                                    <div class="call_option">
                                        <p class="btn btn-sm btn-success my-2" id="call_phone2">Click to call parent</p>
                                    </div>
                                    <div class="call_option">
                                        <p class="btn btn-sm btn-success my-2" id="mail_to2">Click to send the parent an email.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btns">
                        <!--<button type="button">Delete Student</button>-->
                        <button type="button" id='updatestudinfor'>Update student information</button>
                        <p class = "link" id="returnfind">&laquo; Go back to student list</p>
                    </div>
                    <p id="coppy_cat_err"></p>
                </div>
                <!-- <div class="conts" id="result_body_2">
                    <div class="view_informations row p-2">
                        <div class="dp_holder col-md-4 ">
                            <div class="dp_s">
                                <img src="images/dp.png" alt="dp">
                                <button>Change</button>
                            </div>
                        </div>
                        <div class=" row col-md-8 ">
                            <div class="col-md-5 shadow-lg border border-secondary p-1 rounded-sm m-2">
                                <div class="container ">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Basic Information</h6>
                                    <p><strong>✔ Student name: </strong> <span id="s_name">James st patrick</span></p>
                                    <p><strong>✔ Gender: </strong> <span id="gender_studs">male</span></p>
                                    <p><strong>✔ Class enrolled: </strong> <span id="students_classes">Class 3</span></p>
                                    <p><strong>✔ D.O.B: </strong> <span id="students_dobs">Jun 10<sup>th</sup> 2000</span></p>
                                    <p><strong>✔ Admission Date: </strong> <span id="student_doas">Jun 10<sup>th</sup> 2009</span></p>
                                    <p><strong>✔ Home Address: </strong> <span id="students_adds">Kisumu</span></p>
                                </div>
                                <div class="container">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Numbers</h6>
                                    <p><strong>✔ Admission No.</strong> <span id="students_adm_nos">202</span></p>
                                    <p><strong>✔ Student UPI: </strong> <span id="students_upisd">FM-1000</span></p>
                                    <p><strong>✔ Student BC No: </strong> <span id="students_bcs_no">09898978</span></p>
                                    <p><strong>✔ Student Index No: </strong> <span id="students_indexes_nos">1</span></p>
                                </div>
                                <div class="container">
                                    <h6 class="text-bold" style="color: rgb(88, 104, 104);">Other Information</h6>
                                    <p><strong>✔ Disabled.</strong> <span id="students_disableds">Yes</span></p>
                                    <p><strong>✔ Disabled description:</strong> <span id="students_brocken_ankle" > Brocken ankle and stomach alergies.</span></p>
                                </div>
                            </div>
                            <div class="col-md-6 shadow-lg border border-secondary p-1 rounded-sm m-2 container">
                                <h6 class="text-bold" style="color: rgb(88, 104, 104);">Parents information</h6>
                                <div class="dp_s ">
                                    <img src="images/dp.png" alt="DP">
                                </div>
                                <div class="parent_data">
                                    <p><strong>✔ Parent name: </strong> <span id="pare_name">Cecilia Adongo</span></p>
                                    <p><strong>✔ Parent Relation</strong> <span id="pare_relations">Mother</span></p>
                                    <p><strong>✔ Parent Residence</strong> <span id="pare_residences">Kijabe, Kenya</span></p>
                                    <p><strong>✔ Parent Phone</strong>: <span id="pare_phoneids">0714151617</span> </p>
                                    <p><strong>✔ Parent Email</strong>: <span id="pare_maileds">parent@gmail.com</span></p>
                                </div>
                                <div class="call">
                                    <div class="call_option" id = "callers">
                                        <label><a href="tel:+254714152415" class = "link" >Call Cecilia Adongo  </a></label> <br>
                                        <img src="images/calls.png" alt="call" srcset="">
                                    </div>
                                    <div class="call_option" id="Mailersa">
                                        <label><a href="mailto:hilaryme452gmail.com" class = "link" >Mail Mrs Cecilia  </a></label>
                                        <img src="images/send_mail.png" alt="mail">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>