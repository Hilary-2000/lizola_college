<div class="contents animate hide" id = "send_sms">
    <div class="titled">
        <h6>Broadcast Messages</h6>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Broadcast Messages</p>
        </div>
        <div class="middle1">
            <p class="hide" id="loading_my_sms_here"></p>
            <div class="setting_s">
                <div id="sms_loaders_window"></div>
                <div id="email_loaders_window"></div>
                <div class="conts">
                    <p><strong>What you should know:</strong></p>
                    <p>- At this window you are able to view your balances</p>
                    <p>- View the statistics of the messages you have sent over time and their delivery reports</p>
                </div>
            </div>
            <div class="setting_s">
                <div class="conts " id = "send_sms_window">
                    <h6 class="text-primary text-center"><u>Send a message to a recipient</u> </h6>

                    <p style="width:fit-content;" class="p-1 border border-primary rounded" >Would you rather <span id="rather_send_email_btn" class="btn btn-primary btn-sm my-2">Send E-Mail</span> ?</p>
                    <hr>

                    <p>Start by selecting your recipient, you can either enter their phone number or select a staff.</p>
                    <label class="form-control-label" for="select_recipients1"> <br> Select recipients: </label><br>
                    <select class="form-control" name="select_recipients1" id="select_recipients1">
                        <option value="" hidden>Select recipient..</option>
                        <option value="phone_no">Enter Phone No.</option>
                        <option value="my_staff">My staff</option>
                    </select>

                    <div class="conts hide" id="sms_information">
                        <div class="hide" id="enter_phone">
                            <label class="form-control-label" for="staff_phones">Enter phone number: </label><br>
                            <input class="form-control" type="number" name="staff_phones" id="staff_phones" placeholder = "Phone number"> <br>
                        </div>

                        <div class="hide" id="select_tr">
                            <label class="form-control-label" for="select_staff_sms">Select staff below: <br></label>
                            <p id="my_staff_info"></p>
                        </div>

                        <label for="text_message">Message: <br></label>
                        <p style="color:gray;font-size:12px;" ><span id="char_count">0</span>/160 characters (One unit for 160 characters)</p>

                        <textarea class="form-control fx-12" name="text_message" id="text_message" cols="30" rows="5" maxlength="160"  placeholder = "Type your text message here"></textarea><br>

                        <p class="" id="out_put22"></p>
                        <p id="out_put223"></p>

                        <span class="btn btn-primary" type='button' id="send_sms_btns"><i class="fas fa-paper-plane"></i> Send</span>
                    </div>

                </div>
                <div class="conts hide" id = "send_email_window">
                    <h6 class="text-primary text-center"><u>Send an Email to a recipient</u> </h6>
                    <p style="width:fit-content;"  class="text-left p-1 border border-primary rounded">Would you rather <span id="rather_send_sms_btn" class="btn btn-primary btn-sm my-2">Send SMS</span> ?</p>
                    <hr>

                    <p>Start by selecting your recipient, you can either enter their email address or select a staff.</p>
                    <label class="form-control-label" for="email_recipient"> <br> Select recipients: </label><br>
                    <select class="form-control" name="email_recipient" id="email_recipient">
                        <option value="" hidden>Select recipient..</option>
                        <option value="email">Enter Email Address.</option>
                        <option value="my_staff">My staff</option>
                    </select>

                    <div class="conts " id="email_information">
                        <div class="hide my-0" id="enter_client_email_addr">
                            <label class="form-control-label" for="staff_email_addressess">Enter E-Mail Address: </label><br>
                            <input class="form-control my-0" type="text" name="staff_email_addressess" id="staff_email_addressess" placeholder = "Email Address"> <br>
                        </div>

                        <div class="hide" id="select_tr_email">
                            <label class="form-control-label" for="select_staff_emails">Select staff below: <br></label>
                            <p id="my_staff_emails"></p>
                        </div>
                        
                        <label for="carbon_copy1" class="form-control-label">CC.</label>
                        <input type="text" name="carbon_copy1" id="carbon_copy1" class="form-control" placeholder="Carbon Copy(optional)">
                        
                        <label for="blind_carbon_copy1" class="form-control-label">BCC.</label>
                        <input type="text" name="blind_carbon_copy1" id="blind_carbon_copy1" class="form-control" placeholder="Blind CC(optional)">

                        <label for="email_header" class="form-control-label">Message Subject.</label>
                        <input type="text" name="email_header" id="email_header" class="form-control" placeholder="E-Mail Subject">

                        <label for="email_messages">Message: <br></label>
                        <textarea class="form-control fx-12" name="email_messages" id="email_messages" cols="30" rows="5" maxlength="320"  placeholder = "Type your message here"></textarea><br>
                        <p class="hide" id="email_not_setup_notify"></p>
                        <span class="btn btn-primary disabled" type='button' id="send_email_button"><i class="fas fa-paper-plane"></i> Send <span class="hide" id="load_email_sending"><img src="images/ajax_clock_small.gif"></span></span>
                        <br><span id="email_send_errors"></span>
                    </div>
                </div>
            </div>
            <div class="setting_s">
                <div class="conts" id = "print_reminded">
                    <h6 class="text-primary text-center"><u>Send broadcast message.</u> </h6>
                    <div class="p-1 my-2 text-primary border border-primary w-100"><b>Note:</b> <br> - Broadcast messages will only allow you to send messages to either your <b>staff</b> or <b>Parent</b> </div> <br>
                    <label for="select_recipients2">Select recipients: <br></label><br>
                    <select class="form-control" name="select_recipients2" id="select_recipients2">
                        <option value="" hidden>Select recipient..</option>
                        <option value="my_staff">My staff</option>
                        <option value="parents">Parents</option>
                    </select>
                    <div class="conts hide" id="staffs_list_ms">
                        <div class="p-1 my-2 text-success border border-success w-50"><b>Note:</b> <br>- Select staff you  want to send message to:</div>
                        <label for="">To My staff Except: <br></label>
                        <p id="staff_my_lists"></p>
                        <!--<div class="staff_list">
                            <div class="staff_dets">
                                <label>1.</label>
                                <label for="staff1">James st Patrick</label>
                                <input type="checkbox" name="staff1" id="staff1">
                            </div>
                            <div class="staff_dets">
                                <label>2.</label>
                                <label for="staff2">James st Patrick</label>
                                <input type="checkbox" name="staff2" id="staff2">
                            </div>
                            <div class="staff_dets">
                                <label>3.</label>
                                <label for="staff3">James st Patrick</label>
                                <input type="checkbox" name="staff3" id="staff3">
                            </div>
                            <div class="staff_dets">
                                <label>4.</label>
                                <label for="staff1">James st Patrick</label>
                                <input type="checkbox" name="staff1" id="staff1">
                            </div>
                            <div class="staff_dets">
                                <label>5.</label>
                                <label for="staff2">James st Patrick</label>
                                <input type="checkbox" name="staff2" id="staff2">
                            </div>
                        </div>-->
                    </div>
                    <div class="conts hide" id="students_parents">
                        <div class="p-1 border border-success text-success fx-10 my-2 w-50 text-left align-left"><b>Note:</b> <br> - Select the student to whom you want to send message to their parent.</div>
                        <label>To Student`s parent selected: <br></label>
                        <p class="text-secondary">(Total Number of Students: <span id="all_parents" >0</span>) <br></p>
                        <p class="text-secondary">(Selected Students: <span class="red_notice" id="excempt_list">0</span>) <br></p>
                        <label for="my-class">Select class: <br></label>
                        <p id="seleceted_class" class = "hide"></p>
                        <p id="cl_list_msg"></p><!--Select class-->
                        <p id="parents_lists_nm"><!--Student list-->
                        <!--<div class="staff_list">
                                <div class="staff_dets">
                                    <label for="staff1" style="color:cadetblue;">Select all</label>
                                    <input type="checkbox" name="staff1" id="staff1">
                                </div>
                                <div class="staff_dets">
                                    <label>1.</label>
                                    <label for="staff1">Owen Malingu`s Parent (1)</label>
                                    <input type="checkbox" name="staff1" id="staff1">
                                </div>
                                <div class="staff_dets">
                                    <label>2.</label>
                                    <label for="staff2">Owen Malingu`s Parent (1)</label>
                                    <input type="checkbox" name="staff2" id="staff2">
                                </div>
                                <div class="staff_dets">
                                    <label>3.</label>
                                    <label for="staff3">Owen Malingu`s Parent (1)</label>
                                    <input type="checkbox" name="staff3" id="staff3">
                                </div>
                                <div class="staff_dets">
                                    <label>4.</label>
                                    <label for="staff1">Owen Malingu`s Parent (1)</label>
                                    <input type="checkbox" name="staff1" id="staff1">
                                </div>
                                <div class="staff_dets">
                                    <label>5.</label>
                                    <label for="staff2">Owen Malingu`s Parent (1)</label>
                                    <input type="checkbox" name="staff2" id="staff2">
                                </div>
                            </div>-->
                        </p>
                    </div>
                    <div class="conts hide" id="parent_selections">
                        <label for="send_to_whom" class="form-control-label">Send to whom?</label>
                        <select name="send_to_whom" id="send_to_whom" class="form-control">
                            <option value="" hidden >Select an option</option>
                            <option value="all_three">All Three</option>
                            <option value="both">Both Parents</option>
                            <option value="student_contact">Student Only</option>
                            <option value="primary">Primary Parent</option>
                            <option value="secondary">Secondary Parent</option>
                        </select>
                    </div>
                    <label for="send_options" class="form-control-label">Would You rather ?</label>
                    <select name="send_options" id="send_options" class="form-control">
                        <option value="" hidden >Select an option</option>
                        <option value="send_emails">Send Email</option>
                        <option selected value="send_sms">Send SMS</option>
                    </select>
                    <div class="container w-50 border border-primary p-1 mx-0 my-2 hide" id="email_sender">
                        <h6 class="text-center">Send Email</h6>
                        <div class="container w-100">
                            <label for="cc_email_bulk" class="form-control-label">CC</label>
                            <input type="text" class="form-control w-100" id="cc_email_bulk" placeholder="CC">
                        </div>
                        <div class="container w-100">
                            <label for="bcc_email_bulk" class="form-control-label">BCC</label>
                            <input type="text" class="form-control w-100" id="bcc_email_bulk" placeholder="BCC">
                        </div>
                        <div class="container w-100">
                            <label for="email_bulk_subject" class="form-control-label">Subject</label>
                            <input type="text" class="form-control w-100" id="email_bulk_subject" value="Email from <?php echo ucwords(strtolower($_SESSION['schname']))?>" placeholder="Subject">
                        </div>
                    </div>
                    <div class="cont border border-secondary p-1 my-2 mx-2 d-none" id="message_tags_window">
                        <h6 class="text-center"><b>Message Tags</b></h6>
                        <span class="text-primary"><b>Note:</b> <br>
                        <small>- Below are tags that hold dynamic data that can be inserted in a message to make it more informative and more specific to the recipient. <br>
                            - Always include the curly braces when inserting tags in the message and always check the preview before sending the message.
                        </small></span>
                        <div class="row w-90 mx-2">
                            <div class="conts p-2 bg-white my-2 col-md-6">
                                <span><b>1. Student Fullname: </b> <span class="mx-2">{stud_fullname}</span> <span id="insert_tag1" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>2. Student First Name: </b> <span class="mx-2">{stud_first_name}</span> <span id="insert_tag2" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>3. Student Class: </b> <span class="mx-2">{stud_class}</span> <span id="insert_tag3" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>4. Student Age: </b> <span class="mx-2">{stud_age}</span> <span id="insert_tag4" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>5. Student Fees Balance: </b> <span class="mx-2">{stud_fees_balance}</span> <span id="insert_tag5" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>6. Student Fees To Pay: </b> <span class="mx-2">{stud_fees_to_pay}</span> <span id="insert_tag6" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>7. Student Fees Paid: </b> <span class="mx-2">{stud_fees_paid}</span> <span id="insert_tag7" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><br>
                                <span><b>8. Student Noun: </b> <span class="mx-2">{stud_noun}</span> <span id="insert_tag13" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span> <small><b>Son/Daughter</b></small></span>
                            </div>
                            <div class="conts p-2 bg-white my-2 col-md-6">
                                <span><b>1. Parent Fullname: </b> <span class="mx-2">{par_fullname}</span> <span id="insert_tag8" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>2. Parent First Name: </b> <span class="mx-2">{par_first_name}</span> <span id="insert_tag10" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                                <span><b>3. Parent title 1: </b> <span class="mx-2">{title_1}</span> <span id="insert_tag11" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span> <small><b>Mr & Mrs</b></small></span><hr class="my-1">
                                <span><b>4. Parent title 2: </b> <span class="mx-2">{title_2}</span> <span id="insert_tag12" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span> <small><b>Madam / Sir</b></small></span><hr class="my-1">
                                <span><b>6. Today: </b> <span class="mx-2">{today}</span> <span id="insert_tag9" class="text-left link" title="click to insert in message"><i class="fas fa-angle-double-down"></i></span></span><hr class="my-1">
                            </div>
                        </div>
                    </div>
                    <label  for="text_message2"><br> Write Message: <br></label><br>
                    <p style="color:gray;font-size:12px;" ><span id="chr_counts_in">0</span>/160 characters (One unit for 160 characters)</p>
                    <div class="w-90 mx-2 p-1 row bg-light">
                        <div class="col-md-7 p-0">
                            <div class="container hide p-0" id="hide_text_areas">
                                <textarea class="form-control" name="email_editors" id="email_editored" cols="30" rows="10" maxlength="160"   placeholder = "Type your message here"></textarea><br>
                            </div>
                            <textarea class="form-control" name="text_message2" id="text_message2" cols="30" rows="10" maxlength="160"   placeholder = "Type your message here"></textarea><br>
                        </div>
                        <div class="col-md-5 border border-primary p-2">
                            <h6><b>Message Sample</b></h6>
                            <small id="message_samples">Message Sample will appear here</small>
                        </div>
                    </div>
                    <p id="err_hands_error"></p>
                    <button type='button' id="send_msg_btns">Send message <span class="hide" id="load_bulk_emails_sending"><img src="images/ajax_clock_small.gif"></span></button>
                    <p id="out_put"></p>
                </div>
            </div>
            <div class="setting_s">
                <h6 style='text-align:center;color:cadetblue;' ><u>Send staff notice/Message.</u> </h6>
                <div class="conts">
                    <p><strong>Note:</strong></p>
                    <p style = 'color:brown;'>The message you send will appear as a notice in the staff portal.</p> <br>
                </div>
                <div class="conts">
                    <label class="form-control-label" for="select_staff_infors">Select staff: </label><br>
                    <p id="staffs_l_s"></p>
                </div>
                <div class="conts">
                    <label class="form-control-label" for="type_notice_here">Type message <br></label>
                    <p style="color:gray;font-size:12px;" ><span id="chr_counts_in1">0</span>/160 characters</p>
                    <textarea name="type_notice_here" id="type_notice_here" cols="30" rows="10" maxlength="160" placeholder = "Type your message here" ></textarea><br>
                    <p id='notice_errors'></p>
                    <button type='button' id="send_post" >Send Notice</button>
                </div>
            </div>
            <div class="setting_s">
                <div class="messages_hold" id="show_sms_windows">
                    <p style="width:fit-content;"  class="text-left p-1 border border-primary rounded">Would you rather <span id="rather_view_email_history" class="btn btn-primary btn-sm my-2">View E-mail</span> ?</p>
                    <div class="">
                        <h6 class="form-control-label" style='text-align:center;color:cadetblue;'>View your SMS history. <br></h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-control-label" for="from_msg_sent">From: <br></label>
                                <input type="date" class="form-control" name="from_msg_sent" id="from_msg_sent" value = <?php echo date("Y-m-d", strtotime("-167 hour"))?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="to_msg_sent">To:</label>
                                <input class="form-control" type="date" name="to_msg_sent" id="to_msg_sent" value = <?php echo date("Y-m-d", strtotime("3 hour"))?>><br>
                            </div>
                            <div class="col-md-4">
                                <button role="button" id="view_sms_history">View SMS history</button>
                            </div>
                        </div>
                        <p id="sms_checker_evt_handlers"></p>
                        <div class="conts" id="histotysms"></div>
                        <div class="conts" id="">
                            <h6 class="text-center">Messages Table</h6>
                            <div class="row" id="search_option_sms">
                                <div class="col-md-6 form-group row">
                                    <input type="text" name="search" id="searchkey_sms" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                                </div>
                            </div>
                            <div class="table-responsive" id="transDataReciever_sms">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th title="Sort all descending"># <span id="sortall_sms"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by Reg No descending">Message Type <span id="sort_message_type"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by Amount descending">Content <span id="sort_content"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by date descending">Date Sent <span id="sortdate_sms"><i class="fas fa-caret-down"></i></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1 <span class="text-success" title="Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                        <tr>
                                            <td>2 <span class="text-secondary"  title="Not Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                        <tr>
                                            <td>3 <span class="text-success"  title="Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>Multicast Delivered (1/1) <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <div class='displaydata'><img class='' src='images/error.png'></div>
                                <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p> -->
                            </div>
                            <div class="row mt-5" id="tablefooter_sms">
                                <div class="col-sm-12 col-md-5">
                                    <div class="container-fluid">
                                        <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_sms">1 </span> to <span class="text-primary" id="finishNo_sms">10</span> of <span id="tot_records_sms"></span> Records.</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_sms">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_sms">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_sms">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_sms">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_sms">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="messages_hold hide" id="show_email_windows">
                    <p style="width:fit-content;"  class="text-left p-1 border border-primary rounded">Would you rather <span id="rather_view_sms_history" class="btn btn-primary btn-sm my-2">View SMS</span> ?</p>
                    <div class="">
                        <h6 class="form-control-label" style='text-align:center;color:cadetblue;'>View your E-Mail history. <br></h6>
                        <p id="email_checker_evt_handlers"></p>
                        <div class="conts" id="histotyemail"></div>
                        <div class="conts" id="">
                            <h6 class="text-center">Email Table</h6>
                            <div class="row" id="search_option_email">
                                <div class="col-md-6 form-group row">
                                    <input type="text" name="search" id="searchkey_email" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                                </div>
                            </div>
                            <div class="table-responsive" id="transDataReciever_email">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th title="Sort all descending"># <span id="sortall_email"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by Reg No descending">Sent To <span id="sort_message_type"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by Amount descending">Content <span id="sort_content_email"><i class="fas fa-caret-down"></i></span></th>
                                            <th  title="Sort by date descending">Date Sent <span id="sortdate_email"><i class="fas fa-caret-down"></i></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1 <span class="text-success" title="Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>mail@ladybirdsmis.com <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                        <tr>
                                            <td>2 <span class="text-secondary"  title="Not Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>mail@ladybirdsmis. <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                        <tr>
                                            <td>3 <span class="text-success"  title="Charged"><i class="fas fa-coins"></i></span></td>
                                            <td>mail@ladybirdsmis. <span class="badge badge-success"> </span></td>
                                            <td>Hello Hillary see me at my office at 5 after classes</td>
                                            <td>14th June 2021</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <div class='displaydata'><img class='' src='images/error.png'></div>
                                <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p> -->
                            </div>
                            <div class="row mt-5" id="tablefooter_email">
                                <div class="col-sm-12 col-md-5">
                                    <div class="container-fluid">
                                        <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_email">1 </span> to <span class="text-primary" id="finishNo_email">10</span> of <span id="tot_records_email"></span> Records.</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate_email">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_email">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_email">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_email">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_email">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last_email"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_email">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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