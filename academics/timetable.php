<div class="contents animate hide" id="timetable_window">
    <div class="titled">
        <h2>Timetable</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Timetable</p>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- Create your timetable.</p>
                <p>- View your timetable.</p>
                <p>- Follow the instruction below to accomplish your task.</p>
            </div>
            <div class="conts">
                    <p class="block_btn" id="view_tt_in"><i class=" fa fa-eye"></i> View My Timetable</p>
                    <p class="block_btn" id="create_tt_in"><i class=" fa fa-pen"></i> Create Timetable</p>
            </div>
            <!-- <div class="conts">
                <input type="text" name="message" id="message-me" placeholder = 'Write message here'>
                <button type="button" id="click_messages">Send the message</button>
            </div> -->
            <div class="conts hide hind" id="create_timetabled">
                <div class="conts">
                    <h6 style='text-align:center;'>Create Timetable</h6>
                </div>
                <div class="body3 animate hide" id="create_tt_inside">
                    <h6 style='text-align:center;'>Step 1: Select Class</h6>
                    <div class="left">
                        <div class="conts">
                            <p>Start by selecting the classes.</p>
                            <label for="sel_class">Select Class: <br></label>
                            <p id="class_datas_12"></p>
                            <!-- <div class="classlist">
                                <div class="checkboxholder" style="margin:10px 0;padding:0px 0px;"><label style="margin-right:5px;cursor:pointer;font-size:12px;" for="1">Class 1</label><input class="ttt_class" type="checkbox" name="1" id="1"></div>
                                <div class="checkboxholder" style="margin:10px 0;padding:0px 0px;"><label style="margin-right:5px;cursor:pointer;font-size:12px;" for="2">Class 2</label><input class="ttt_class" type="checkbox" name="1" id="1"></div>
                                <div class="checkboxholder" style="margin:10px 0;padding:0px 0px;"><label style="margin-right:5px;cursor:pointer;font-size:12px;" for="3">Class 3</label><input class="ttt_class" type="checkbox" name="1" id="1"></div>
                            </div> -->
                        </div>
                        <div class="">
                            <p class='link' style='text-align:right;' id='next_infor'>Next >></p>
                        </div>
                    </div>
                </div>
                <div class="body3 hide animate hind"  id="create_tt_inside2">
                    <h6 style='text-align:center;'>Step 2 : Select subject</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- Only subjects done by these classes will be displayed here.</i></p>
                            <p>Select subjects below.</p>
                            <label for="sel_class">Select subjects: <br></label>
                            <p id="class_datas_13"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor1'><< Previous</p>
                            <p class='link' style='text-align:right;' id='next_infor2'>Next >></p>
                        </div>
                    </div>
                </div>
                <div class="body3 hide animate hind" id="create_tt_inside3">
                    <h6 style='text-align:center;'>Step 3: First Preview</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- Preview the data from your database.</i></p>
                            <p id="class_datas_14"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                            
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor2'><< Previous</p>
                            <p class='link' style='text-align:right;' id='next_infor3'>Next >></p>
                        </div>
                    </div>
                </div>
                <div class="body3 hide animate hind" id="create_tt_inside4">
                    <h6 style='text-align:center;'>Step 4: Number of lessons a day</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- The maximum number of lessons a day is twice the number of subjects choosen.</i></p>
                            <p><i>- For this case the maximum number of lessons a day is <span id="max_lessons_in"></span></i></p>
                            <p id="class_datas_15"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                            <label for="number_of_lessons">Enter number of lessons a day: <br></label>
                            <input type="number" name="number_of_lessons" id="number_of_lessons" min = "0" placeholder = "Number of lessons">
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor3'><< Previous</p>
                            <p class='link' style='text-align:right;' id='next_infor4'>Next >></p>
                        </div>
                    </div>
                </div>

                <div class="body3 hide animate hind" id="create_tt_inside5">
                    <h6 style='text-align:center;'>Step 5: Morning hours subjects</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- Select the lessons to appear in the morning.</i></p>
                            <p id="class_datas_15"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                            <label for="">Select morning hour lessons: <br></label>
                            <p id="morning_less"></p>
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor4'><< Previous</p>
                            <p class='link' style='text-align:right;' id='next_infor5'>Next >></p>
                        </div>
                    </div>
                </div>

                <div class="body3 hide animate hind" id="create_tt_inside6">
                    <h6 style='text-align:center;'>Step 6: Select Days of the week</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- Select the days of the week.</i></p>
                            <p id="class_datas_15"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                            <label for="">Select days of the week: <br></label>
                            <div class="classlist">
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='monday1'>Monday</label><input class='ttt_class4' type='checkbox' name = 'Monday' value = 'Monday' id='monday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='tuesday1'>Tuesday</label><input class='ttt_class4' type='checkbox' name = 'Tuesday' value = 'Tuesday' id='tuesday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='wednesday1'>Wednesday</label><input class='ttt_class4' type='checkbox' name = 'Wednesday' value = 'Wednesday' id='wednesday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='thursday1'>Thursday</label><input class='ttt_class4' type='checkbox' name = 'Thursday' value = 'Thursday' id='thursday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='friday1'>Friday</label><input class='ttt_class4' type='checkbox' name = 'Friday' value = 'Friday' id='friday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='saturday1'>Saturday</label><input class='ttt_class4' type='checkbox' name = 'saturday' value = 'Saturday' id='saturday1'></div>
                            <div class='checkboxholder' style='margin:10px 0;padding:0px 0px;'><label style='margin-right:5px;cursor:pointer;font-size:12px;' for='sunday1'>Sunday</label><input class='ttt_class4' type='checkbox' name = 'Sunday' value = 'Sunday' id='sunday1'></div>
                            </div>
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor5'><< Previous</p>
                            <p class='link' style='text-align:right;' id='next_infor6'>Finish >></p>
                        </div>
                    </div>
                </div>

                <div class="body3 hide animate hind" id="create_tt_inside7">
                    <h6 style='text-align:center;'>Step 7: FInal preview and Generate timetable</h6>
                    <div class="left">
                        <div class="conts">
                            <p><strong>Notice:</strong></p>
                            <p><i>- Final Preview.</i></p>
                            <p id="class_datas_16"></p>
                            <!-- Display the subjects that is shared amoung the classes -->
                            <label>Give timetable name: <br></label>
                            <input type="text" name="tt_named" id="tt_named" placeholder = "Timetable name">
                            <div class="flexed">
                                <p class="block_btn" id="create_tt_complete"><i class=" fa fa-generator"></i> Genarate Timetable</p>
                            </div>
                            <p id="class_in_87"></p>
                        </div>
                        <div class="flexed">
                            <p class='link' style='text-align:left;' id='prev_infor6'><< return</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="conts hide" id="view_tt_inxide">
                <div class="conts">
                    <h5 style='text-align:center;'>View My Timetable </h5>
                    <p><b>Note</b><br><span class='text-danger'>(All your TT requests will be generated after every 10 maximum)</span></p>
                </div>
                <div class="conts animate" id="table_lists">
                    <p id="timetable_lists"></p>
                    <!-- <div class="table_holders">
                        <table>
                            <tr>
                                <th>No.</th>
                                <th>Timetable Name</th>
                                <th>Date Generated</th>
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>Jane</td>
                                <td>21-Oct-2021</td>
                                <td>Attended</td>
                                <td><span class="link"><i class="fa fa-trash"></i> Delete</span> || <span class="link"><i class="fa fa-eye"></i> View</span></td>
                            </tr>
                        </table>
                    </div> -->
                    <p id="name_tags"></p>
                </div>
                <div class="conts hide " id="mytimetable">
                    <div class="conts">
                        <h6 style='text-align:center;'>Timetable Name: <b><span id="timetable_title_name"></span></b></h6>
                    </div>
                    <button id="return_timetable_list2"> <i class="fa fa-undo-alt"></i> Back</button><br>
                    <p class="block_btn" id="print_tt"><i class="fas fa-print"></i> Print Timetable</p>
                    <!-- print table -->
                    <div class="row">
                        <form id="print_tt_windows" class="hide container col-md-6 border border-secondary mx-0 p-2" action="/sims/reports/reports.php" target="_blank" method="post">
                            <h6>Print</h6>
                            <p class="hide" id="lesson_length_holder"></p>
                            <!-- print time tables -->
                            <input type="hidden" name="timetable_generation" value="set">
                            <label for="first_lesson" class="form-control-label"><b>First Lesson time</b></label>
                            <input type="time" required name="first_lesson" id="first_lesson" class="form-control w-50">
                            <label for="lesson_time" class="form-control-label"><b>Lesson duration in minutes</b></label>
                            <input required type="number" name="lesson_time" id="lesson_time" class="form-control" placeholder="Lesson in Minutes">
                            <hr>
                            <div class="container" id="break_names">
                                <label class="form-control-label" for=""><b>Break Name</b></label>
                                <input type="text" name="" id="brake_name" class="form-control" value="BREAK" placeholder="Break or Lunch">
                                <label class="form-control-label" for="break_1"><b>Break after what lesson</b></label>
                                <span id="break_1_period_select"></span>
                                <label for="period_in_minutes" class="form-control-label"><b>Period in minutes</b></label>
                                <input type="number" class="form-control" id="break_1_period_in_minutes" placeholder="Period in minutes">
                                <p class="block_btn" id="add_breaks"><i class="fas fa-plus"></i> Add Breaks</p>
                                <input type="hidden" name="breaks_lists" id="breaks_lists"><br>
                                <span class="text-success" id="table_breaktime">When you add breaks they will appear here in table form.</span>
                            </div>
                            <label for="what_tt" class="form-control-label">Type of Timetable</label>
                            <select required name="what_tt" id="what_tt" class="form-control">
                                <option value="" hidden>Select What timetable</option>
                                <option value="class_timetable">Class Timetable</option>
                                <option value="block_timetable">Block Timetable</option>
                                <option value="specific_tr_timetable">Teacher Timetable</option>
                            </select>
                            <div class="container hide" id="teacher_lists_select">
                                <label for="specific_tr_tt" class="form-control-label">What Timetable is generated</label>
                                <span class="" id="specific_tr_tt_lists_select"></span>
                            </div>
                            <button type="submit" class="btn btn-secondary">Print Timetable</button>
                        </form>
                    </div>
                        <hr>
                    <p class="hide" id="view_my_tt_ids"></p>
                    <p id="read_timetable"></p>
                    <!-- <div style='margin-top:20px;' class="conts">
                        <h4 style='text-align:left;'>Timetables</h4>
                        <div class="conts">
                            <p><strong>Class</strong>: Grade 7</p>
                            <div class="table_holders"></div>
                        </div>
                    </div> -->
                    <!-- Block timetable -->
                    <!-- Class timetable -->
                    <!-- Personal timetable -->
                    <button id="return_timetable_list"> <i class="fa fa-undo-alt"></i> Back</button>
                </div>
                <div class="container hide" id="customize_tt">
                    <div class="row">
                        <div class="col-md-10">
                            <button id="return_timetable_lists2"> <i class="fa fa-undo-alt"></i> Back</button>
                        </div>
                        <div class="col-md-2">
                            <button id="save_custom_tt2"> <i class="fa fa-save float-right"></i> Save</button>
                        </div>
                    </div>
                    <div class="conts">
                        <input type="hidden" name="" id="timetable_ids_holders">
                        <span id="error_handler_customize_tt"></span><br>
                        <b>Note:</b><br>
                        <span>- When changing lessons in the table the system will pick lesson that will best fit that period without causing any conflict. </span><br>
                        <span>- You can also pick blank lesson to indicate free lesson or physical education </span><br>
                        <span>- Ensure you save the changes before leaving this page</span>
                        <hr>
                        <h6 style='text-align:center;'>Customize Timetable: <b><span id="customize_my_tables_tt"></span></b></h6>
                    </div>
                    <div class="container">
                        <p class="block_btn" id="advanced_options"><i class="fas fa-user-astronaut"></i> Advance Options</p>
                        <div class="w-50 hide" id="advanced_window">
                            <div class="container border border-secondary p-2" id="step_1_tt">
                                <b class="text-success">Note:</b>
                                <span>- In this advanced section you can be able to do either of the two tasks</span>
                                <span>
                                    <ul>
                                        <li>Combine Subjects - here is where we combine two or more subjects that will appear in the same instance or period. <b>Save when done</b></li>
                                        <li>Setting up rooms - there are rooms apart from classes that you may want lessons to be carried out, that can be configured. <b>Save when done</b></li>
                                    </ul>
                                </span>
                                <h6><u>Step 1: Subject Combination</u></h6>
                                <label for="my_class_list" class="form-control-label"><b>Select Class</b></label>
                                <span id="class_lists_holder"></span>
                                <label for="classes_lists" class="form-control-label">Check Subjects to combine</label>
                                <span id="subjects_listing"></span>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="block_btn" id="set_combination"><i class="fas fa-pen-fancy"></i> Set Combination</p>
                                    </div>
                                    <div class="col-md-6">
                                    <p class="block_btn bg-transparent" id="skip_combinations">Skip <i class="fas fa-forward"></i></p>
                                    </div>
                                </div>
                                <span id="display_notice"></span>
                                <div class="table_holders" id="table_combinations"></div>
                            </div>
                            <div class="comtainer hide border border-secondary p-2 my-2" id="step_2_tt">
                                <h6>Step 2: <u>Set Up Rooms</u></h6>
                                <p class="block_btn" id="delete_all_rooms"><i class="fas fa-trash-alt"></i> Delete All Rooms</p><br>
                                <label for="room_names" class="form-control-label">Room Name</label>
                                <input type="text" name="room_names" id="room_names" class="form-control" placeholder="Room Name eg: Room 1">
                                <label for="shorts_names" class="form-control-label">Short Name</label>
                                <input type="text" name="shorts_names" id="shorts_names" class="form-control" placeholder="Room Short eg: Rm1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="block_btn bg-transparent" id="back_step2_tt"><i class="fas fa-backward"></i> back</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="block_btn" id="set_rooms"><i class="fas fa-pen-fancy"></i> Set Room</p>
                                    </div>
                                </div>
                                <span class="hide" id="rooms_set"></span>
                                <p id="err_handles_rooms"></p>
                                <p id="set_tables"></p>
                            </div>
                            <span class="hide" id="combination_json"></span>
                            <!-- <table class="table">
                                <tr><th>Class Name</th><th>Combination</th></tr>
                                <tr><td>Grade 5</td><td>BIOLOGY | SCIENCE <span class="link ml-3"><i class="fas fa-trash-alt"></i></span><hr class="my-0"><span>HISTORY | ENGLISH <span class="link ml-3"><i class="fas fa-trash"></i></span></span></td></tr>
                            </table> -->
                        </div>
                        <hr>
                        <!-- get the timetable information -->
                        <span id="custom_window_tt"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <button id="return_timetable_lists"> <i class="fa fa-undo-alt"></i> Back</button>
                        </div>
                        <div class="col-md-2">
                            <button id="save_custom_tt"> <i class="fa fa-save float-right"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>