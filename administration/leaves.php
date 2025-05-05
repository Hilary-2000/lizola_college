<div class="contents animate hide" id="leave_mgmt_staff">
    <div class="titled">
        <h2>Employees Leave Management</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Employees Leave Management</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
        </div>
        <div class="middle1">
            <p><b>Note:</b></p>
            <p>Welcome <?php echo ucwords(strtolower($_SESSION['fullnames']));?> to the Employees Leave management window, At this window you will be able to apply for leaves and also view your leave application history.</p>
            <hr class="my-1">
            <div class="container border border-secondary p-2 hide my_leaves_view" id="apply_leaves_windows">
                <h4 class="text-centre">Apply for Leave</h4>
                <p class="block_btn" id="back_to_list_emp_leave_list"><i class="fas fa-arrow-left"></i> Back to list</p>
                <div class="row my-4">
                    <div class="col-md-6">
                        <div class="form-group my-1">
                            <label for="leave_type" class="form-control-label text-bold"><b>Select leave:</b><img class="hide" src="images/ajax_clock_small.gif" id="leave_loader_select"></label>
                            <span id="leave_category_select"></span>
                        </div>
                        <div class="form-group my-1">
                            <label for="" class="form-control-label"><b>Leave Balance:</b><img class="hide" src="images/ajax_clock_small.gif" id="leave_balance_loader"></label>
                            <!-- <input type="number" name="leave_balance" id="leave_balance" readonly placeholder="Select Leave Category" value="" class="form-control w-100"> -->
                            <span class="text-primary" id="leave_balance_apply" class="">Select leave category</span>
                        </div>
                        <div class="form-group my-1">
                            <!-- <label for="leave_type" class="form-control-label"><b>Date Range:</b></label> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="from_leave_date" class="form-control-label"><b>From (Leave starting date):</b></label>
                                    <input class="form-control w-100" type="date" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="from_leave_date" id="from_leave_date">
                                </div>
                                <div class="col-md-12">
                                    <label for="to_leaves_date" class="form-control-label"><b>To (Returning date):</b></label>
                                    <input class="form-control w-100" type="date" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="to_leaves_date" id="to_leaves_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-1">
                            <label for="leave_duration" class="form-control-label"><b>Leave duration in Days<img class="hide" src="images/ajax_clock_small.gif" id="leave_duration_loader"></b></label>
                            <input type="number" readonly name="leave_duration" value="0" id="leave_duration" placeholder="Calculated Automatically" class="form-control w-100">
                            <p class="" id="leave_days_holder"></p>
                            <p id="duration_day_errors"></p>
                        </div>
                        <div class="form-group my-1">
                            <label for="leave_comments" class="form-control-label"><b>Leave Description</b></label>
                            <textarea name="leave_comments" id="leave_comments" cols="30" rows="5" class="form-control w-100" placeholder="Describe why you are applying for the leave"></textarea>
                        </div>
                        <div class="form-group my-1">
                            <p class="block_btn" id="apply_leave"><i class="fas fa-save"></i> Apply Now <img class="hide" src="images/ajax_clock_small.gif" id="apply_leave_loader"></p>
                            <p id="application_error"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container border border-secondary my_leaves_view" id="display_my_applied_leaves">
                <h5 class="text-center">Leave Application Table<img class="hide" src="images/ajax_clock_small.gif" id="my_leave_list_loader"></h5>
                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6 d-flex">
                        <p class="block_btn" id="self_apply_for_leave"><i class="fas fa-plus"></i> Apply Leave</p>
                    </div>
                </div>
                <div class="container" id="my_application_table_data">
                    <table class='table'>
                        <tr>
                            <th>#</th>
                            <th>Leave Title</th>
                            <th>Date Applied</th>
                            <th>Days Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Annual Leaf</td>
                            <td>Mon 6th Aug 2022</td>
                            <td>60 days</td>
                            <td>Pending</td>
                            <td><p class='link view_emp_leaves' id='view_emp_leaves'><i class="fas fa-eye"></i> View</p></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="container border border-secondary p-2 hide my_leaves_view" id="view_leave_details_emp">
                <h4 class="text-centre">Leave Details <img class="hide" src="images/ajax_clock_small.gif" id="load_leave_details"></h4>
                <p class="hide" id="leave_details_result"></p>
                <p class="block_btn" id="back_to_list_emp_leave_list_2"><i class="fas fa-arrow-left"></i> Back to list</p>
                <div class="row my-4">
                    <div class="col-md-12">
                        <span id="my_leave_status"></span>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-1">
                            <label for="leave_name_applied" class="form-control-label text-bold"><b>Leave Title:</b></label>
                            <input type="text" name="" readonly placeholder="Leave Title" id="leave_name_applied" class="form-control w-100">
                        </div>
                        <div class="form-group my-1">
                            <label for="leave_apply_date_views" class="form-control-label"><b>Date Applied:</b><img class="hide" src="images/ajax_clock_small.gif" id="leave_balance_loader"></label>
                            <input type="text" name="" readonly placeholder="Leave Apply Date" id="leave_apply_date_views" class="form-control w-100">
                        </div>
                        <div class="form-group my-1">
                            <!-- <label for="leave_type" class="form-control-label"><b>Date Range:</b></label> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="from_leave_date_my_view" class="form-control-label"><b>From (Leave starting date):</b></label>
                                    <input class="form-control w-100" readonly type="text" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="from_leave_date_my_view" id="from_leave_date_my_view">
                                </div>
                                <div class="col-md-12">
                                    <label for="to_leaves_date_my_view" class="form-control-label"><b>To (Returning date):</b></label>
                                    <input class="form-control w-100" type="text" min="<?php echo date("Y-m-d",strtotime("7 days"));?>" name="to_leaves_date_my_view" id="to_leaves_date_my_view">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-1">
                            <label for="leave_duration_days_view" class="form-control-label"><b>Leave duration in Days<img class="hide" src="images/ajax_clock_small.gif" id="leave_duration_loader"></b></label>
                            <input type="number" readonly name="leave_duration_days_view" value="0" id="leave_duration_days_view" placeholder="Calculated Automatically" class="form-control w-100">
                            <p class="" id="leave_days_holder"></p>
                            <p id="duration_day_errors"></p>
                        </div>
                        <div class="form-group my-1">
                            <label for="leave_comments_my_view" class="form-control-label"><b>Leave Description</b></label>
                            <textarea name="leave_comments_my_view" id="leave_comments_my_view" cols="30" rows="5" class="form-control w-100" placeholder="Describe why you are applying for the leave"></textarea>
                        </div>
                        <div class="form-group my-1">
                            <!-- <p class="block_btn" id="apply_leave"><i class="fas fa-save"></i> Apply Now <img class="hide" src="images/ajax_clock_small.gif" id="apply_leave_loader"></p> -->
                            <p id="application_error_view"></p>
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