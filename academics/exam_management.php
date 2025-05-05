<div class="contents animate hide" id="exammanagement">
    <div class="titled">
        <h2>Academics</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Exams Management</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p><strong>Information:</strong></p>
                <p>- At this window you are previledged to register exams and view their information.</p>
            </div>
            <div class="body4">
                <p>Start by doing either of the following:</p>
                <p class="block_btn" id="registerexamsbtn">Register New Exam</p>
                <p class="block_btn" id="viewexams">View Exams</p>
                <p class="block_btn" id="generate_exams_reports">Generate Reports</p>
                <div class="conts bordered_bottom">
                    <div class="options hide border border-dark p-2 col-md-6 my-1" id="viewexam">
                        <h5>Search Exams</h5>
                        <label for="options1" class="form-control-label">Select an option below: <br></label>
                        <select name="option1" class="form-control w-100" id="option1">
                            <option value="" hidden>Select option...</option>
                            <option value="allactive" id="all_active">All active</option>
                            <option value="byname">By Name</option>
                            <option value="byperiod">By period</option>
                            <!--<option value="byclass">By class</option>-->
                            <option value="bystatus">By status</option>
                        </select>
                        <p id="err123d"></p>
                        <div class="conts hide" id="statuses">
                            <label for="status1" class="form-control-label">Select status below: <br></label>
                            <select name="status1" class="form-control w-100" id="status1">
                                <option value="" hidden>Select status</option>
                                <option value="completed">Completed</option>
                                <option value="incompleted">In-Complete</option>
                            </select>
                        </div>
                        <div class="conts hide" id="usingname">
                            <label for="usenames2" class="form-control-label">Enter Exam Name: <br></label>
                            <input type="text" class="form-control w-50" name="usenames2" id="usenames2" placeholder="Enter exam name">
                        </div>
                        <div class="conts hide row w-100" id="btnperiods">
                            <div class="col-md-6">
                                <label for="btnperiod" class="form-control-label">From: <br></label>
                                <input type="date" class="form-control w-100" style="width: 100%;" name="btnperiod" id="btnperiod" max=<?php echo date("Y-m-d", strtotime("3 hour")); ?>>
                            </div>
                            <div class="col-md-6">
                                <label for="endsperiod" class="form-control-label">To: <br></label>
                                <input type="date" class="form-control w-100" style="width: 100%;" name="endsperiod" id="endsperiod">
                            </div>
                        </div>
                        <div class="conts" id="classed1234">

                        </div>
                        <button type="button" title="Click to display the exams available" id="displaysubjects">Display</button>
                    </div>
                </div>
                <div class="container w-75 animate bordered_bottom  border border-secondary rounded hide" id="generate_exams_reports_window">
                    <h5 class='text-center'><b>Generate exams Reports</b></h5>
                    <div class="carousels" id="carousels">
                        <div class="slides p-2 ">
                            <h6 class="text-center">Select Report Type</h6>
                            <div class="form-group">
                                <label for="students_class_reports" class="form-control-label">Select report type </label>
                                <select name="exams_report_type" id="exams_report_type" class="form-control">
                                    <option value="" id="first_exmas_options" hidden>Select an option</option>
                                    <option value="termly_exams_report">Termly Exam Reports</option>
                                </select>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 1: Select Class</h6>
                            <div class="form-group">
                                <label for="students_class_reports" class="form-control-label">Select Class </label><img src="images/ajax_clock_small.gif" class="hide" id="exams_report_class_reports">
                                <span id="exams_report_classes_display"></span>
                                <p id="err_handler_step_1"></p>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 2: Select Terms</h6>
                            <div class="form-group">
                                <p class="border border-secondary p-2 text-secondary my-1">
                                    <b>Note:</b> <br> The current term and the terms that have already been covered are the ones that are to be displayed only!
                                </p>
                                <label for="exams_report_class" class="form-control-label">Select Terms to be included in the report cards</label><img src="images/ajax_clock_small.gif" class="hide" id="exams_report_terms_loader">
                                <div class="bg-white rounded d-flex flex-column w-50" id="display_terms_present"></div>
                                <p id="err_handler_step_2"></p>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 3: Select Exams</h6>
                            <div class="form-group">
                                <p class="border border-secondary p-2 text-secondary">
                                    <b>Note:</b> <br> Select exams you want the system to include in the reports. <br> All exams will be displayed including those that the student did not attempt.
                                </p>
                                <label for="exams_report_class" class="form-control-label">Select Exams</label><img src="images/ajax_clock_small.gif" class="hide" id="exams_report_exams_done_loader">
                                <div class="bg-white rounded d-flex flex-column w-50" id="display_exams_attempted_in_those_terms"></div>
                                <span id="err_handler_step_3"></span>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 4: Define Academic Year</h6>
                            <div class="form-group">
                                <p class="border border-secondary p-2 text-secondary">
                                    <b>Note:</b> <br> Leave blank if you do not want to define the academic year
                                </p>
                                <label for="academic_year_reports" class="form-control-label">Define Academic Year</label>
                                <input type="text" class="form-control" id="academic_year_reports" placeholder="Eg: 2021/2022">
                                <span id="err_handler_step_4"></span>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 5: Directors comment</h6>
                            <div class="form-group">
                                <div class="border border-secondary p-2 text-secondary">
                                    <b>Note:</b> <br>Directors comment will appear on every students report card. <br> You can include tags to make the comments more specific to the students <br>Leave Blank to exclude the directors comment<br>
                                    <b><u>Tags Include</u></b>
                                    <ul>
                                        <li>{fullname} : Display the students fullname.</li>
                                        <li>{firstname} : Display the students first name.</li>
                                        <li>{noun1} : Display the students noun. <small>If they are <b>male</b> they will be reffered to as <b>son</b></small></li>
                                        <li>{noun2} : Display the students noun. <small>If they are <b>male</b> they will be reffered to as <b>boy</b></small></li>
                                    </ul>
                                    <b class="text-danger"><u>Include the curly braces("{}") when including tags and check on the preview how the comments are going to appear.</u></b>
                                </div>
                                <!-- decide how you may want the comments to be written -->
                                <!-- it can be written in bulk or individually -->
                                <!-- <label for="comment_type" class="form-control-label">Comment Type</label>
                                <select name="comment_type" id="comment_type" class="form-control">
                                    <option value="" hidden>Select Comment Type</option>
                                    <option value="all_students">All Students</option>
                                    <option value="individual_students">Individual Student</option>
                                </select> -->
                                <div class="container-fluid my_element_max_300 overflow-auto bg-white mt-2" id="students_commentators">
                                    <div class="container bg-transparent p-2 my-2 bordered_bottom">
                                        <p class="my-1"><b>1. Student Name</b> <a class="link" href="#" target="_blank"><i class="fas fa-eye"></i> View Result</a></p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="student_id_" class="form-control-label">Comment here:</label>
                                                <textarea name="student_id_" id="student_id_" cols="30" rows="3" class="form-control" placeholder="Comments go here"></textarea>
                                            </div>
                                            <div class="col-md-4 container-fluid border border-secondary rounded">
                                                <p class="text-primary" id="preview_comments_exams_">Previews Appear here..</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container bg-transparent p-2 my-2 bordered_bottom">
                                        <p class="my-1"><b>2. Student Name</b></p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="student_id_" class="form-control-label">Comment here:</label>
                                                <textarea name="student_id_" id="student_id_" cols="30" rows="3" class="form-control" placeholder="Comments go here"></textarea>
                                            </div>
                                            <div class="col-md-4 container-fluid border border-secondary rounded">
                                                <p class="text-primary">Previews Appear here..</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container bg-transparent p-2 my-2 bordered_bottom">
                                        <p class="my-1"><b>3. Student Name</b></p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="student_id_" class="form-control-label">Comment here:</label>
                                                <textarea name="student_id_" id="student_id_" cols="30" rows="3" class="form-control" placeholder="Comments go here"></textarea>
                                            </div>
                                            <div class="col-md-4 container-fluid border border-secondary rounded">
                                                <p class="text-primary">Previews Appear here..</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container bg-transparent p-2 my-2 bordered_bottom">
                                        <p class="my-1"><b>4. Student Name</b></p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="student_id_" class="form-control-label">Comment here:</label>
                                                <textarea name="student_id_" id="student_id_" cols="30" rows="3" class="form-control" placeholder="Comments go here"></textarea>
                                            </div>
                                            <div class="col-md-4 container-fluid border border-secondary rounded">
                                                <p class="text-primary">Previews Appear here..</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container bg-transparent p-2 my-2 bordered_bottom">
                                        <p class="my-1"><b>5. Student Name</b></p>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="student_id_" class="form-control-label">Comment here:</label>
                                                <textarea name="student_id_" id="student_id_" cols="30" rows="3" class="form-control" placeholder="Comments go here"></textarea>
                                            </div>
                                            <div class="col-md-4 container-fluid border border-secondary rounded">
                                                <p class="text-primary">Previews Appear here..</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span id="err_handler_step_5"></span>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 6: Additional Data</h6>
                            <div class="form-group">
                                <div class="border border-secondary p-2 text-secondary">
                                    <b>Note:</b> <br> To exclude the next opening date leave the field black.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="next_open_date" class="form-control-label"><b>Next Open Date</b></label>
                                        <input type="date" class="form-control" id="next_open_date" value="<?php echo date("Y-m-d");?>" placeholder="Eg: 2021/2022">
                                        <label for="grades_options" class="form-control-label"><b>Select grades options</b></label>
                                        <select name="grades_options" id="grades_options" class="form-control">
                                            <option value="">Select option</option>
                                            <option value="grades only">Show Grades only</option>
                                            <option value="marks only">Show Marks only</option>
                                            <option value="grades and marks">Show Grades and Marks</option>
                                        </select>
                                        <label class="form-control-label" for="garding_options_grade_7"><b>Select grading method: </b><br></label>
                                        <select class="form-control" name="garding_options_grade_7" id="garding_options_grade_7">
                                            <option value="" hidden>Select an option</option>
                                            <option value="cbc">C.B.C</option>
                                            <option value="844">8-4-4</option>
                                            <option value="IGCSE">IGCSE</option>
                                            <option value="iPrimary">iPrimary</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <div class="d-flex flex-row justify-content-between p-2 my-2 w-100 bg-primary">
                                            <label for="display_trend_analysis" class="form-control-label">Include Trend Analysis</label>
                                            <input type="checkbox" class="mr-1" name="display_trend_analysis" id="display_trend_analysis" checked><br>
                                        </div>
                                        <div class="d-flex flex-row justify-content-between p-2 my-2 w-100 bg-secondary">
                                            <label for="include_tutors" class="form-control-label">Include Tutors</label>
                                            <input type="checkbox" class="mr-1" name="include_tutors" id="include_tutors" checked>
                                        </div> -->
                                        <div class="row my-2">
                                            <div class="col-md-9">
                                                <label for="display_trend_analysis" class="form-control-label"><b>Include Trend Analysis</b></label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" class="mr-1" name="display_trend_analysis" id="display_trend_analysis" checked><br>
                                            </div>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-md-9">
                                                <label for="include_tutors" class="form-control-label"><b>Include Tutors</b></label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="checkbox" class="mr-1" name="include_tutors" id="include_tutors" checked>
                                            </div>
                                        </div>
                                        <div class="my-2">
                                            <label for="report_term_selected" class="form-control-label"><b>Report For:</b></label>
                                            <input type="text" id="report_term_selected" class="form-control" placeholder="e.g, Term 1 or Annual Report">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="slides p-2 hide">
                            <h6 class="text-center">Step 7: Confirm & Generate Report</h6>
                            <img src="images/ajax_clock_small.gif" class="hide" id="exam_report_generator">
                            <input hidden name="all_data_text" id="all_data_text">
                            <p class="border border-secondary text-secondary p-1 my-2">- Select an option below then load the data you have input in the system then generate the reports. <br> 
                            - You can always go back and alter the information fed in the system to view the different outputs. <br>
                            - Kindly note that sending of mails do take time be patient as the process is going on.
                        </p>
                            <span id="err_handler_step_7"></span>
                            <form action="reports/reports.php" target="_blank" method="post">
                                <input type="hidden" name="generate_students_exams_report" value="true">
                                <select name="select_exams_actions" id="select_exams_actions" class="form-control">
                                    <option value="" hidden>Select option</option>
                                    <option value="print_exams">Print Exams report cards</option>
                                    <option value="email_parents">Email Parents</option>
                                </select>
                                <input type="hidden" name="class_select" id="class_select">
                                <input type="hidden" name="terms_selected" id="terms_selected">
                                <input type="hidden" name="exams_selected" id="exams_selected">
                                <input type="hidden" name="academic_year" id="academic_year">
                                <input type="hidden" name="directors_comments" id="directors_comments">
                                <input type="hidden" name="next_yr_opening" id="next_yr_opening">
                                <input type="hidden" name="actions" id="actions">
                                <input type="hidden" name="grades_options" id="grades_options_holder">
                                <input type="hidden" name="garding_options_grade_8" id="garding_options_grade_8">
                                <input type="hidden" name="include_trend_analysis" id="include_trend_analysis">
                                <input type="hidden" name="include_your_tutors" id="include_your_tutors">
                                <input type="hidden" name="report_term_selected" id="report_term_selected_submit">
                                <div class="container p-2 my-2 hide" id="email_data_holder">
                                    <label for="send_to_email_reports" class="form-control-label">Send to:</label>
                                    <select name="send_to_email_reports" id="send_to_email_reports" class="form-control">
                                        <option value="" hidden>Select an option</option>
                                        <option value="send_to_primary_parent">Send to primary parent</option>
                                        <option value="send_to_secondary_parent">Send to secondary parent</option>
                                        <option selected value="send_to_both_parents">Send to both parent</option>
                                    </select>

                                    <label for="email_cc_subject_reports" class="form-control-label">CC</label>
                                    <input type="text" name="email_cc_subject_reports" class="form-control" id="email_cc_subject_reports" placeholder="CC">

                                    <label for="email_subject_exams_report" class="form-control-label">Email Subject</label>
                                    <input type="text" class="form-control" placeholder="E-mail Subject" value="<?php echo 'Student Exam Report Card'?>" name="email_subject_exams_report" id="email_subject_exams_report">

                                    
                                    <div class="border border-secondary p-2 text-secondary my-2">
                                        <b>Note:</b> <br>Compose email message below that will accompany the report card attachment. <br> Include tags to make the message more personalised to the parents <br>Leave Blank to exclude the message<br>
                                        <b><u>Tags Include</u></b>
                                        <ul>
                                            <li>{fullname} : Display the students fullname.</li>
                                            <li>{firstname} : Display the students first name.</li>
                                            <li>{noun1} : Display the students noun. <small>If they are <b>male</b> they will be reffered to as <b>son</b></small></li>
                                            <li>{noun2} : Display the students noun. <small>If they are <b>male</b> they will be reffered to as <b>boy</b></small></li>
                                            <li>{noun3} : Display the students noun. <small>If they are <b>male</b> they will be reffered to as <b>his</b></small></li>
                                            <li>{class} : Display the students class.</li>
                                            <li>{adm_no} : Display the students admission no.</li>
                                        </ul>
                                        <b class="text-danger"><u>Include the curly braces("{}") when including tags and check on the preview how the messages are going to appear.</u></b>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <label for="email_contents_exam_reports" class="form-control-label">Email Contents: </label>
                                            <textarea name="email_contents_exam_reports" id="email_contents_exam_reports" cols="30" rows="5" class="form-control" placeholder="Write your message here"></textarea>
                                        </div>
                                        <div class="col-md-3 bg-white">
                                            <p class="text-primary py-2 my-2" id="email_contents_exam_reports_preview">Previews Appear here..</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-row justify-content-between p-2">
                                    <span id="execute_exams_report_cards" type='submit' class="btn btn-primary btn-sm m-2"><i class="fas fa-save"></i> Load Data</span>
                                    <span id="finish_generating_reports" type='submit' class="btn btn-secondary btn-sm m-2">Close <i class="fas fa-times"></i></span>
                                </div>
                                <input type="submit" id="generate_report_btns" value="Generate Termly Reports" class="btn btn-sm btn-success hide my-2">
                            </form>
                        </div>
                    </div>
                    <!-- navigation buttons -->
                    <div class="row my-2">
                        <div class="col-md-6">
                            <span id="back_exams_btn" class="btn btn-secondary btn-sm hide"><i class="fas fa-arrow-left"></i> Back</span>
                        </div>
                        <div class="col-md-6">
                            <span id="next_exams_btn" class="btn btn-success btn-sm"><i class="fas fa-arrow-right"></i> Next</span>
                        </div>
                    </div>
                </div>
                <div class="conts" id="exams_table_list">
                    <!--<div class="contsload" id="loads12345d">
                        <img src="images/load2.gif" alt="loading..">
                    </div>-->
                    <span class="text-center" id="exams_data_windows"></span>
                    <div class="conts" id="holdExaminfor">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Option</th>
                            </tr>
                            <tr>
                                <td>TARGETER SERIES 1</td>
                                <td>Active</td>
                                <td>Tue 12th Aug</td>
                                <td>Fri 15th Aug</td>
                                <td><button type="button">View</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="container hide" id="exams_details_window">
                    <!-- get the exams details class-->
                    <h5 class="text-center my-2">Exams Details <img src="images/ajax_clock_small.gif" class="hide" id="exams_details_loader"></h5>
                    <span class="link" id="back_exams_list"><i class="fas fa-arrow-left"></i> Back Exam List</span>
                    <div class="container col-md-6 border border-secondary">
                        <p class="text-center"><b>Display Results</b></p>
                        <p class="hide" id="exams_id_result"></p>
                        <p class="text-success"><small>Classes that did the exams are the only ones that appear
                                below</small></p>
                        <label for="class_label_exams_result" class="form-control-label">Select a class to display the
                            result</label><br>
                        <span id="exams_details_holder">Please wait...</span><br>
                        <button id="display_exams_for_classes">Display</button>
                        <span id="results_output"></span>
                    </div>
                    <!-- the display button -->
                    <div class="container  border border-secondary p-1 my-2" id="exams_window_display">
                        <p class="class-success text-center">Your exams results will appear here!<br>Select class to
                            proceed!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>