<div class="contents animate hide" id="human_resource_windows">
    <div class="titled">
        <h2>Human Resource</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Call register</p>
                </div>
                <div class="col-md-3">
                    <!-- <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span> -->
                </div>
            </div>
            <!-- <input type="text"  id="myname" hidden value = <?php if(isset($_SESSION['username'])){ echo $_SESSION['username']; } ?> > -->
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- Manage your Employees Leave Information.</p>
                <p>- Manage your Employees employment information.</p>
            </div>
            <div class="container">
                <label for="hr_options" class="form-control-label"><b>Select an Option</b></label>
                <select name="hr_options" id="hr_options" class="form-control">
                    <option value="" hidden>Select an option</option>
                    <option value="manage_leaves">Manage Leaves</option>
                    <!-- <option value="manage employees">Manage Employees</option> -->
                </select>
            </div>
            <hr class="my-1">
            <div class="container border border-secondary p-2 my-2 hide" id="leave_management_window">
                <h4 class="text-center ">Manage Leaves</h4>
                <!-- options for leaves are below -->
                <p><b>Note:</b> <br> Kindly select an action before proceeding</p>
                <label for="leaves_options">Select an action</label>
                <select name="leaves_options" id="leaves_options" class="form-control">
                    <option value="" hidden>Select an action</option>
                    <option value="view leave application">View Leave Applications</option>
                    <!-- <option value="view leave balances">View Leave Balances</option> -->
                    <option value="view leave categories">Manage Leave Categories</option>
                </select>
                <hr class="my-1">
                <!-- manage leave categories -->
                <div class="leave_displays container my-2 animate border border-secondary p-2 hide" id="leave_diplay_windows">
                    <h5 class="text-center" >Leave Categories<img class="hide" src="images/ajax_clock_small.gif" id="load_leaves_table"></h5>
                    <div class="row">
                        <div class="col-md-8">
                            <!-- <input type="text" class="form-control w-50" placeholder="Search Here"> -->
                        </div>
                        <div class="col-md-4">
                            <p class="block_btn" id="add_leave_category"> <i class="fas fa-plus"></i> Add Leave Category</p>
                        </div>
                    </div>
                    <span id="leave_tables_display"></span>
                    <!-- <table class="table">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Accrued</th>
                            <th>Genders</th>
                            <th>Max Days</th>
                            <th>Actions</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Annual Leave</td>
                            <td>Monthly</td>
                            <td>Male</td>
                            <td>21</td>
                            <td><small><span class="link"><i class="fas fa-pen-fancy"></i> Edit</span>  <span class="link"><i class="fas fa-trash-alt"></i> Delete</span></small></td>
                        </tr>
                    </table>
                    <p>Showing 1 to 6 of 6 records</p> -->
                </div>
                <div class="leave_displays container my-2 animate border border-secondary p-2 hide" id="add_leave_cat_window">
                    <h5 class="text-center">Add Leave Categories</h5>
                    <p class="block_btn" id="go_back_leave_list"><i class="fas fa-arrow-left"></i> Back</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_title_name" class="form-control-label">Leave Title/Name</label>
                                <input type="text" id="leave_title_name" class="form-control w-100" placeholder="Leave Name">
                            </div>
                            <div class="form-group">
                                <label for="gender_eligible" class="form-control-label">Gender Eligible</label>
                                <select name="" id="gender_eligible" class="form-control w-100">
                                    <option value="" hidden>Select Gender</option>
                                    <option value="All">All</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_per_yr" class="form-control-label">Maximum Days per Year</label>
                                <input type="number" name="" id="max_days_per_yr" class="form-control w-100" placeholder="Max Days">
                            </div>
                            <div class="form-group">
                                <label for="leave_status" class="form-control-label">Leave Status</label>
                                <select name="" id="leave_status" class="form-control w-100">
                                    <option value="" id="p_8" hidden>Select Period</option>
                                    <option value="1">Active</option>
                                    <option value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_year_start" class="form-control-label">Leaves Year Starts</label>
                                <select name="" id="leave_year_start" class="form-control w-100">
                                    <option value="" id="p_7" hidden>Select Period</option>
                                    <option value="Start Of Academic Yr">Start Of Academic Yr</option>
                                    <option value="Start of january">Start Of January</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="days_accrued" class="form-control-label">Days are accrued?</label>
                                <select name="" id="days_accrued" class="form-control w-100">
                                    <option id="p_6" value="" hidden>Select Period</option>
                                    <option value="Yearly">Yearly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Weekly">Weekly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="period_to_accrued" class="form-control-label">Period accrued?</label>
                                <select name="" id="period_to_accrued" class="form-control w-100">
                                    <option id="p_0" value="" hidden>Select Period</option>
                                    <option id="p_1" value="Start Of Year">Start Of Year</option>
                                    <option id="p_2" value="Start Of Month">Start Of Month</option>
                                    <option id="p_3" value="End Of Month">End Of Month</option>
                                    <option id="p_4" value="Start Of Week">Start Of Week</option>
                                    <option id="p_5" value="End Of Week">End Of Week</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_carry_forward" class="form-control-label">Max Days Carry Forward ?</label>
                                <input type="number" name="" id="max_days_carry_forward" class="form-control w-100" placeholder="Max Days Carry Forward">
                            </div>
                        </div>
                    </div>
                    <p id="save_leave_cat"></p>
                    <p class="block_btn my-2" id="save_leave_category"><i class="fas fa-save"></i> Save <img class="hide" src="images/ajax_clock_small.gif" id="save_leave_cat_loader"></p>
                </div>
                <div class="leave_displays container my-2 animate border border-secondary p-2 hide" id="edit_leave_cat_window">
                    <h5 class="text-center">Edit Leave Categories</h5>
                    <p class="block_btn" id="go_back_leave_list2"><i class="fas fa-arrow-left"></i> Back</p>
                    <p><b>Note:</b> <br>- Make changes where neccessary</p><hr class="my-1">
                    <p class="hide" id="leave_data_holder"></p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="" id="leaves_id">
                                <label for="leave_title_name2" class="form-control-label">Leave Title/Name</label>
                                <input type="text" id="leave_title_name2" class="form-control w-100" placeholder="Leave Name">
                            </div>
                            <div class="form-group">
                                <label for="gender_eligible2" class="form-control-label">Gender Eligible</label>
                                <select name="" id="gender_eligible2" class="form-control w-100">
                                    <option id="p_19_19" value="" hidden>Select Gender</option>
                                    <option id="p_16_16" value="All">All</option>
                                    <option id="p_17_17" value="Male">Male</option>
                                    <option id="p_18_18" value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_per_yr2" class="form-control-label">Maximum Days per Year</label>
                                <input type="number" name="" id="max_days_per_yr2" class="form-control w-100" placeholder="Max Days">
                            </div>
                            <div class="form-group">
                                <label for="leave_status2" class="form-control-label">Leave Status</label>
                                <select name="" id="leave_status2" class="form-control w-100">
                                    <option value="" id="p_13_13" hidden>Select Period</option>
                                    <option id="p_14_14" value="1">Active</option>
                                    <option id="p_15_15" value="0">In-Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_year_start2" class="form-control-label">Leaves Year Starts</label>
                                <select name="" id="leave_year_start2" class="form-control w-100">
                                    <option value="" id="p_7_7" hidden>Select Period</option>
                                    <option id="p_8_8" value="Start Of Academic Yr">Start Of Academic Yr</option>
                                    <option id="p_9_9" value="Start of january">Start Of January</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="days_accrued2" class="form-control-label">Days are accrued?</label>
                                <select name="" id="days_accrued2" class="form-control w-100">
                                    <option id="p_6_6" value="" hidden>Select Period</option>
                                    <option id="p_10_10" value="Yearly">Yearly</option>
                                    <option id="p_11_11" value="Monthly">Monthly</option>
                                    <option id="p_12_12" value="Weekly">Weekly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="period_to_accrued2" class="form-control-label">Period accrued?</label>
                                <select name="" id="period_to_accrued2" class="form-control w-100">
                                    <option id="p_0_0" value="" hidden>Select Period</option>
                                    <option id="p_1_1" value="Start Of Year">Start Of Year</option>
                                    <option id="p_2_2" value="Start Of Month">Start Of Month</option>
                                    <option id="p_3_3" value="End Of Month">End Of Month</option>
                                    <option id="p_4_4" value="Start Of Week">Start Of Week</option>
                                    <option id="p_5_5" value="End Of Week">End Of Week</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="max_days_carry_forward2" class="form-control-label">Max Days Carry Forward ?</label>
                                <input type="number" name="" id="max_days_carry_forward2" class="form-control w-100" placeholder="Max Days Carry Forward">
                            </div>
                        </div>
                    </div>
                    <p id="save_leave_cat2"></p>
                    <p class="block_btn my-2" id="update_leave_category"><i class="fas fa-save"></i> Update <img class="hide" src="images/ajax_clock_small.gif" id="save_leave_cat_loader"></p>
                </div>
                <div class="leave_displays container my-2 animate border border-secondary p-2 hide" id="all_leaves_application">
                    <h5 class="text-center">Leave Applications<img class="hide" src="images/ajax_clock_small.gif" id="leaves_application_loaders"></h5>
                    <p class="hide" id="my_leaves_application"></p>
                    <div class="row" id="search_option_sms">
                        <div class="col-md-6 form-group">
                            <input type="text" name="search" id="searchkey_leaves" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                        </div>
                        <div class="col-md-6 form-group">
                            <select name="choose_status" id="choose_leave_status" class="form-control">
                                <option value="">Select All</option>
                                <option value="0">Pending</option>
                                <option value="1">Accepted</option>
                                <option value="2">Declined</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive" id="transDataReciever_leave_apply">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th title="Sort all descending"># <span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th  title="Sort by Reg No descending">Applicant <span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th  title="Sort by Amount descending">Leave Type <span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th  title="Sort by date Applied">Application Date <span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th  title="Sort by date Dates">Dates<span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th  title="Sort by Duration">Duration <span id=""><i class="fas fa-caret-down"></i></span></th>
                                    <th >Actions <span id=""><i class="fas fa-caret-down"></i></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1 <span class="text-success" title="Charged"><i class="fas fa-info"></i></span></td>
                                    <td>Hillary Ngige <span class="badge badge-success"> </span></td>
                                    <td>Annual Leave</td>
                                    <td>14th June 2021</td>
                                    <td>14th June 2021 to 14th June 2021</td>
                                    <td>16 Days</td>
                                    <td><small class="link" id="edit_table"><i class="fas fa-check"></i> Accept</small> <small class="link" id="edit_table"><i class="fas fa-times"></i> Reject</small> <small class="link" id="edit_table"><i class="fas fa-eye"></i> View</small></td>
                                </tr>
                                <tr>
                                    <td>1 <span class="text-success" title="Charged"><i class="fas fa-info"></i></span></td>
                                    <td>Hillary Ngige <span class="badge badge-success"> </span></td>
                                    <td>Annual Leave</td>
                                    <td>14th June 2021</td>
                                    <td>14th June 2021 to 14th June 2021</td>
                                    <td>16 Days</td>
                                    <td><span id="edit_table"><i class="fas fa-pen-fancy"></i> Edit</span></td>
                                </tr>
                                <tr>
                                    <td>1 <span class="text-success" title="Charged"><i class="fas fa-info"></i></span></td>
                                    <td>Hillary Ngige <span class="badge badge-success"> </span></td>
                                    <td>Annual Leave</td>
                                    <td>14th June 2021</td>
                                    <td>14th June 2021 to 14th June 2021</td>
                                    <td>16 Days</td>
                                    <td><span id="edit_table"><i class="fas fa-pen-fancy"></i> Edit</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- <div class='displaydata'><img class='' src='images/error.png'></div>
                        <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p> -->
                    </div>
                    <div class="row mt-5" id="tablefooter_leave_apply">
                        <div class="col-sm-12 col-md-5">
                            <div class="container-fluid">
                                <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_leave_apply">1 </span> to <span class="text-primary" id="finishNo_leave_apply">10</span> of <span id="tot_records_leave_apply"></span> Records.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item first" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_leave_apply">First</a></li>
                                    <li class="paginate_button page-item previous mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_leave_apply">Prev</a></li>
                                    <li class="paginate_button page-item previous active mx-3" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_leave_apply">1</a></li>
                                    <li class="paginate_button page-item next mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_leave_apply">Next</a></li>
                                    <li class="paginate_button page-item last mx-1" id=""><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_leave_apply">Last</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="leave_displays container my-2 animate border border-secondary p-2 hide" id="view_applied_leaves_windows">
                    <h5 class="text-center">View Leave Details<img class="hide" src="images/ajax_clock_small.gif" id="view_leaves"></h5>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="block_btn" id="back_to_leave_list"><i class="fas fa-arrow-left"></i> Back to list</p>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" id="unussual_id">
                                <p class="btn btn-danger btn-sm" id="delete_this_application"><i class="fas fa-trash"></i></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_applicant_names_view" class="form-control-label">Applicant Names</label>
                                    <input type="text" name="" placeholder="Applicant Name" readonly id="leave_applicant_names_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_title_view" class="form-control-label">Leave Title</label>
                                    <input type="text" name="" placeholder="Leave Title" readonly id="leave_title_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leaf_duration_view" class="form-control-label">Leave Duration</label>
                                    <input type="text" name="" placeholder="Leave Duration" readonly id="leaf_duration_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_from_date_view" class="form-control-label">Leave Starting Date</label>
                                    <input type="text" date="" placeholder="Leave Started On" readonly id="leave_from_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_to_date_view" class="form-control-label">Leave Ending Date</label>
                                    <input type="text" date="" placeholder="Leaves Ends On" readonly id="leave_to_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_application_date_view" class="form-control-label">Leave Application Date</label>
                                    <input type="text" name="" placeholder="Leave Application Date" readonly id="leave_application_date_view" class="form-control w-100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" id="leaves_view_id">
                                    <label for="leaves_status" class="form-control-label">Leave Status</label>
                                    <select name="choose_status" id="choose_leave_status2" class="form-control">
                                        <option id="p_101" value="">Select Leave status</option>
                                        <option id="p_102" value="0">Pending</option>
                                        <option id="p_103" value="1">Accepted</option>
                                        <option id="p_104" value="2">Declined</option>
                                    </select>
                                    <span id="leaves_status_displayer"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_description_view" class="form-control-label">Leave Description</label>
                                    <textarea name="" id="leave_description_view" placeholder="Leave Description" cols="30" rows="10" class="form-control" placeholder="Description for leave application will appear here.."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="leaves_options_views">
                                    <p id="accept_leave_applications" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Accept</p>
                                    <p id="decline_leave_applications" class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Reject</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container border border-secondary p-2 my-2 hide" id="employees_management_window">
                <h4 class="text-center">Manage Employees</h4>
            </div>
            <div class="container border border-secondary p-2 my-2 hide" id="error_hr_selection">
                <h4 class="text-center text-danger">Select a valid option</h4>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>