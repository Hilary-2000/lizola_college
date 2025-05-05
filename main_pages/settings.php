<div class="contents animate hide" id="settings_page">
    <div class="titled">
        <h2>Settings</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Settings</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <div class="school_logo">
                    <img src="images/settings.png" id="" alt="">
                </div>
                <div class="conts" style="text-align:center;border-bottom:1px dashed black;">
                    <h3><?php echo $_SESSION['fullnames']; ?></h3>
                    <p> <b><u>My Settings</u></b></p>
                </div>
            </div>
            <div class="setting_s">
                <p>Add or remove course levels that the system recognizes!</p>
                <label for="class_list">Course Levels: <br></label>
                <img src="images/ajax_clock_small.gif" id="class_list_clock">
                <p id="class_holder">
                    <!--<table style='margin:0;'>
                        <tr>
                            <th>No.</th>
                            <th>Class</th>
                            <th>Options</th>
                        </tr>
                        <tr>
                            <td>1. </td>
                            <td>Class 1</td>
                            <td><p class='link'>Remove</p></td>
                        </tr>
                    </table>-->
                </p>
                <p id="add_class_err_handler"></p>
                <button type="button" id="add_class"><i class="fas fa-plus"></i> Add Course Level</button>
            </div>
            <div class="setting_s">
                <p>Add or remove courses that the system recognizes!</p>
                <button type="button" id="add_course"><i class="fas fa-plus"></i> Add Course</button><br>
                <label for="class_list">Course List: <br></label>
                <img src="images/ajax_clock_small.gif" class="hide" id="course_list_clock">
                <div id="courses_holder">
                    <p class="text-center text-secondary">All your courses will appear here!</p>
                    <!-- <div class="w-100 table_holder p-0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Course</th>
                                    <th>Levels Offered</th>
                                    <th>Department</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1. </td>
                                    <td>Information Technology</td>
                                    <td>Diploma,Artisan Certificate,Entry</td>
                                    <td>Department 1</td>
                                    <td><span class="link remove_class mx-2" id="clmDiploma" style="font-size:12px; color:brown;"><i class="fa fa-trash"></i></span><span class="link change_classes"=="" id="change_classesDiploma" style="font-size:12px; color:brown;"><i class="fa fa-pen-fancy"></i></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div> -->
                </div>
                <p id="add_course_err_handler"></p>
            </div>
            <div class="setting_s">
                <label for="class_list">Admission Number Prefix: <br></label>
                <img src="images/ajax_clock_small.gif" class="hide" id="admission_number_prefix_loader">
                <p class="hide" id="prefix_holder_adm"></p>
                <div class="border border-secondary rounded w-50 p-2" id="admission_number_prefix_window">
                    <p class="text-primary">TB/SG <span class="text-secondary">Example: TB/2020/001</span></p>
                </div>
                <p id="admission_number_prefix_error"></p>
                <button type="button" id="change_admissions_prefix"><i class="fas fa-plus"></i> Change Prefix</button>
            </div>
            <div class="setting_s">
                <p>Change the time your users are allowed to use the system,<br> Its only the headteacher and the administrator allowed to use the system beyond the time set</p>
                <p class="hide" id="active_hours"></p>
                <img src="images/ajax_clock_small.gif" id="active_list_clock">
                <label for="from_time">Change login hours: <br>From: <br></label>
                <input type="time" name="from_time" id="from_time" readonly>
                <label for="to_time"><br>To: <br></label>
                <input type="time" name="to_time" id="to_time" readonly><br>
                <button type='button' id="change_active_hrs_btn">Change</button>
                <p id="outputbtn_activehours"></p>
            </div>
            <div class="setting_s">
                <label for="" class="form-control-label">Working days <img src="images/ajax_clock_small.gif" id="working_days_loader"></label>
                <p>- You can change the working days in the system, this will be used when your staff apply for leave</p>
                <p>- When there is holiday in the non-working days the system will carry forward a day to the working day</p>
                <div class="container d-flex p-2 border border-primary tableme" id="display_working_days">

                </div>
                <!-- <div class="container d-flex p-2 border border-primary">
                    <span id="wd_1" class="wd_btn btn btn-sm btn-success mx-2">Mon</span>
                    <span id="wd_2" class="wd_btn btn btn-sm btn-secondary mx-2">Tue</span>
                    <span id="wd_3" class="wd_btn btn btn-sm btn-secondary mx-2">Wed</span>
                    <span id="wd_4" class="wd_btn btn btn-sm btn-secondary mx-2">Thur</span>
                    <span id="wd_5" class="wd_btn btn btn-sm btn-secondary mx-2">Fri</span>
                    <span id="wd_6" class="wd_btn btn btn-sm btn-secondary mx-2">Sat</span>
                    <span id="wd_7" class="wd_btn btn btn-sm btn-secondary mx-2">Sun</span>
                </div> -->
            </div>
            <div class="setting_s">
                <img src="images/ajax_clock_small.gif" id="acad_table_clock">
                <label for="acad_calender">Academic Calender: <br></label>
                <p id="acad_table"></p>
                <!--<div class="table_holders">
                    <table>
                        <tr>
                            <th>No. </th>
                            <th>Term</th>
                            <th>Opening day</th>
                            <th>Closing date:</th>
                            <th>Starting date</th>
                            <th>Ending date</th>
                        </tr>
                        <tr>
                            <td>1.</td>
                            <td>TERM 1</td>
                            <td>Jan-2-2021</td>
                            <td>Apr-10-2021</td>
                            <td>Jan-1-2021</td>
                            <td>Apr-30-2021</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>TERM 2</td>
                            <td>May-2-2021</td>
                            <td>Aug-10-2021</td>
                            <td>May-1-2021</td>
                            <td>Aug-30-2021</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>TERM 3</td>
                            <td>Sep-2-2021</td>
                            <td>Dec-10-2021</td>
                            <td>Sep-1-2021</td>
                            <td>Dec-30-2021</td>
                        </tr>
                    </table>
                </div>-->
                <p id="acad_cal_errhandler"></p>
                <button type="button" id="change_acad_win">Change</button>
            </div>
            <div class="setting_s">
                <p>These are elements that are to be brought on the day of admission</p>
                <img src="images/ajax_clock_small.gif" id="adm_essential_clock">
                <label for="setters">Admission essentials: <br></label>
                <p id="adm_essential"></p>
                <!--<table>
                    <tr>
                        <th>No. </th>
                        <th>Admission item</th>
                        <th>Option</th>
                    </tr>
                    <tr>
                        <td>1. </td>
                        <td>Plastic chair(500)</td>
                        <td><p class="link">Remove</p></td>
                    </tr>
                    <tr>
                        <td>2. </td>
                        <td>Hockey stick</td>
                        <td><p class="link">Remove</p></td>
                    </tr>
                    <tr>
                        <td>3. </td>
                        <td>Football</td>
                        <td><p class="link">Remove</p></td>
                    </tr>
                </table>-->
                <p id="add_admission_err_handler"></p>
                <button type="button" id="add_adm_ess">Add item</button>
            </div>
            <div class="setting_s">
                <p>Allow Class teachers to register student data</p>
                <img src="images/ajax_clock_small.gif" id="allow_ct_reg_clock">
                <label for="optioms_todo">Allow registration by classteachers: <br></label>
                <select name="optioms_todo" id="optioms_todo">
                    <option value="" hidden>Select option</option>
                    <option id="yes_opt_in1" value="Yes">Yes</option>
                    <option id="no_opt_in1" value="No">No</option>
                </select>
                <p class="" id="allow_ct_err_handler"></p>
                <button type="button" id="change_btns_inside"><i class="fa fa-save"></i> Save</button>
            </div>
            <div class="setting_s">
                <span class="hide" id="load_changes_roles">loading ...</span>
                <span class="hide" id="show_changes_roles">loading ...</span>
                <h6>User Roles</h6>
                <p class="my-2"><strong>Note</strong>: <span class="text-danger">Please note do not use one Role name twice</span></p>
                <p class="hide" id="show_roles"></p>
                <p id="roles_errors"></p>
                <p style="width: fit-content;" class="link" id="add_user_type"><i class="fa fa-plus"></i> Add User Roles</p>
                <span class="hide" id="load_roles"><img src="images/ajax_clock_small.gif" id=""></span>
                <div class="container w-60 tableme" id="roles_holder">

                </div>
                <!-- <table>
                    <tr>
                        <th>No.</th>
                        <th>Role</th>
                        <th>Options</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>School Driver</td>
                        <td><span class="link" ><i class="fa fa-pen"></i> Edit</span> <span class="link"><i class="fa fa-trash"></i> Delete</span></td>
                    </tr>
                </table> -->
            </div>
            <div class="setting_s">
                <h6>Sports House / Clubs</h6>
                <p class="" id="clubs_sport_houses"></p>
                <p id="clubs_errors"></p>
                <p><b>Note:</b> Add Sport houses or clubs that will be assigned to students during admission</p>
                <p style="width: fit-content;" class="link" id="add_sports_clubs"><i class="fa fa-plus"></i> Add Sports Houses / Clubs</p>
                <div class="container" id="clubs_house_tables">

                </div>
                <!-- <table class="table">
                    <tr>
                        <th>No.</th>
                        <th>Sports House / Clubs</th>
                        <th>Options</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Drama CLubs</td>
                        <td><span class="link" ><i class="fa fa-pen"></i> Edit</span> <span class="link"><i class="fa fa-trash"></i> Delete</span></td>
                    </tr>
                </table> -->
            </div>
            <div class="setting_s">
                <h6>Email Set-Up <span class="hide" id="load_email_setup"><img src="images/ajax_clock_small.gif" id=""></span></h6>
                <p>- Keep proffessionalism by taking advantage of the bulk email service by configuring your email address this allows the system to use your personal or business email address to communicate with your parents!</p>
                <br>
                <p class="hide" id="email_errors"></p>
                <p id="email_main_errors"></p>
                <p><b>Note:</b> <span class="text-danger"><br> Your email password is encrypted it will not be visible to anyone.</span></p>
                <p>Kindly test the configurations you set before proceeding, this will allow you to know if the mail configuration is okay.</p>
                <div class="container my-2 hide" id="email_not_setup">
                    <p style="width: fit-content;" class="link" id="setup_email_windows"><i class="fa fa-plus"></i> Set-up Email</p>
                    <div class="p-1 border border-danger text-danger fx-10 my-2 w-50 text-left align-left">
                        <b>Note:</b> <br> - Your Email has not been set-up yet.
                    </div>
                </div>
                <div class="container my-2 mx-0 hide w-75 p-2 border border-dark" id="email_already_setup">
                    <div class="row">
                        <div class="col-md-6">
                            <b>Name</b>
                        </div>
                        <div class="col-md-6">
                            <p><b>Value</b></p>
                        </div>
                        <div class="col-md-6">
                            <b>1 Sender`s Name</b>
                        </div>
                        <div class="col-md-6">
                            <p id="sender_name_set">Ladybird Softech</p>
                        </div>
                        <div class="col-md-6">
                            <b>2 Test Email Address</b>
                        </div>
                        <div class="col-md-6">
                            <p id="test_mail_set">mail@Ladybirdsmis.com</p>
                        </div>
                        <div class="col-md-6">
                            <b>3 Email Host</b>
                        </div>
                        <div class="col-md-6">
                            <p id="host_set_mail">mail.privateemail.com</p>
                        </div>
                        <div class="col-md-6">
                            <b>4 Username</b>
                        </div>
                        <div class="col-md-6">
                            <p id="username_mail_set">mail@ladybirdsmis.com</p>
                        </div>
                        <div class="col-md-6">
                            <b>5 Password</b>
                        </div>
                        <div class="col-md-6">
                            <p>*******</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <span id="edit_usernames" class="my-1 btn btn-primary btn-sm text-sm"><i class="fas fa-pen-fancy"></i> Edit</span>
                        </div>
                        <div class="col-md-4">
                            <span id="test_emails" class="my-1 btn btn-success btn-sm text-sm"><i class="fas fa-sync"></i> Test</span>
                        </div>
                        <div class="col-md-4">
                            <span id="remove_email_settings" class="my-1 btn btn-danger btn-sm text-sm"><i class="fas fa-trash-alt"></i> Remove</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting_s">
                <h6>Payment Options<span class="hide" id="payment_options_loaders"><img src="images/ajax_clock_small.gif" id=""></span></h6>
                <p>- Describe your available payment options in under 50 words. This will be included on your invoices and student`s reciept</p>
                <p class="text-danger"><b>Note:</b></p>
                <p>- when the check box is selected the entry will appear in your invoices and reciepts</p>
                <input type="hidden" name="" id="payment_description">
                <p class="hide" id="payment_details_blocks"></p>
                <div class="container my-1">
                    <p style="width: fit-content;" class="link" id="setup_payment_options"><i class="fa fa-plus"></i> Set-up Payment Options</p>
                    <div class="container my-2 tableme" id="pd_table_holder">
                        <!-- <table class="table">
                            <tr>
                                <th>No.</th>
                                <th>Payments Description.</th>
                                <th>Arrange</th>
                                <th title="Display in reciepts and invoices" >Show</th>
                                <th>Actions.</th>
                            </tr>
                            <tr>
                                <td>1. </td>
                                <td>Direct deposit to KCB Account No. 1257951734 Account Ladybird School Mis</td>
                                <td><select name="" id="" class="form-control">
                                    <option value="">Select option</option>
                                    <option value="">At the beginning</option>
                                    <option value="">After Option 2</option>
                                </select></td>
                                <td><input type="checkbox" name="" id=""></td>
                                <td><p><span class="mx-1 link"><i class="fas fa-pen-fancy"></i></span> <span class="mx-1 link"><i class="fas fa-trash"></i></span></p></td>
                            </tr>
                        </table> -->
                        <p class='text-danger border border-danger my-2 p-2'>Set up the payment options then proceed!.</p>
                    </div>
                    <span class="btn btn-success" id="save_changes_payment_opt"><i class="fas fa-save"></i> Save</span>
                    <p id="display_data_po"></p>
                </div>
            </div>
            <div class="setting_s">
                <h6>Expense Categories<span class="hide" id="expense_categories_loaders"><img src="images/ajax_clock_small.gif"></span></h6>
                <p>- Add an expense category to use when recording expenses</p>
                <input type="hidden" name="" id="expense_category_storage">
                <div class="container my-1">
                    <p style="width: fit-content;" class="link" id="setup_expense_category"><i class="fa fa-plus"></i> Add Expense Category</p>
                    <div class="container my-2 tableme" style="overflow-y: auto; max-height: 300px;" id="expense_category_table_holder">
                        <!-- <table class="table">
                            <tr>
                                <th>No.</th>
                                <th>Expense Category.</th>
                                <th>Actions.</th>
                            </tr>
                            <tr>
                                <td>1. </td>
                                <td>School Bus</td>
                                <td><p><span class="mx-1 link"><i class="fas fa-pen-fancy"></i></span> <span class="mx-1 link"><i class="fas fa-trash"></i></span></p></td>
                            </tr>
                        </table>
                        <p class='text-danger border border-success my-2 p-2'>Add expense categories, they will appear here.</p> -->
                    </div>
                    <p id="display_data_exp_category"></p>
                </div>
            </div>
            <div class="setting_s">
                <h6>Revenue Categories<span class="hide" id="revenue_categories_loaders"><img src="images/ajax_clock_small.gif"></span></h6>
                <p>- Add an revenue category to use when recording revenue</p>
                <input type="hidden" name="" id="revenue_category_storage">
                <div class="container my-1">
                    <p style="width: fit-content;" class="link" id="setup_revenue_category"><i class="fa fa-plus"></i> Add Revenue Category</p>
                    <div class="container my-2 tableme" style="overflow-y: auto; max-height: 300px;" id="revenue_category_table_holder">
                        <table class="table">
                            <tr>
                                <th>No.</th>
                                <th>Revenue Category.</th>
                                <th>Actions.</th>
                            </tr>
                            <tr>
                                <td>1. </td>
                                <td>Transfers from National Government entities</td>
                                <td><p><span class="mx-1 link"><i class="fas fa-pen-fancy"></i></span> <span class="mx-1 link"><i class="fas fa-trash"></i></span></p></td>
                            </tr>
                        </table>
                        <!-- <p class='text-danger border border-success my-2 p-2'>Add revenue categories, they will appear here.</p> -->
                    </div>
                    <p id="display_data_revenue_category"></p>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>