<div class="contents animate hide" id="department_manager">
    <div class="titled">
        <h2>Department Manager</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Department Manager</p>
            <!--<div class="admin_special rotate_down">
            </div>-->
        </div>
        <div class="middle1">
            <span  id="back_to_manage_staff" class="btn btn-sm btn-secondary p-1"><i class="fas fa-arrow-left"></i> Back</span>
            <hr>
            <div class="conts">
                <p>- At this window you are able to manage the departments in the institution.</p>
                <p>- Add different departments and manage the staff in the different departments.</p>
            </div>
            <hr>
            <span class="btn btn-primary btn-sm p-1" id="add_a_departments"><i class="fas fa-plus"></i> Add Department</span>
            <div class="container rounded border border-secondary my-2 p-1 hide" id="add_department_window">
                <h6 class="text-primary text-center"><u>Add Department</u> <img class="hide" src="images/ajax_clock_small.gif" id="department_loader"></h6>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-75" id="department_name" placeholder="Department Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_code" class="form-label">Department Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-75" id="department_code" placeholder="e.g DP-001">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_description" class="form-label">Department Description </label>
                        <textarea name="department_description" id="department_description" cols="30" rows="5" class="form-control" placeholder="Department Description (Optional)"></textarea>
                    </div>
                    <div class="col-md-12">
                        <p class="my-1" id="loader_infor_teller"></p>
                        <span class="btn btn-primary btn-sm btn-block w-100 my-1" id="save_departments"><i class="fas fa-save"></i> Save</span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="container rounded border border-secondary my-2 p-1" id="dept_table_display">
                <p class="hide" id="department_data"></p>
                <h6 class="text-center text-primary">Department Table <img class="hide" src="images/ajax_clock_small.gif" id="department_loader_tables"></h6>
                <div class="table-responsive" id="data_table_department_table">
                    <!-- <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Name</th>
                                <th>Department Code</th>
                                <th>Member Population</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mathematics Department</td>
                                <td>MT 1010</td>
                                <td>20 Student(s)</td>
                                <td>Mon 30th Jun 2023</td>
                                <td><span class="btn btn-sm btn-primary my-0" id=""><i class="fas fa-eye"></i> View</span></td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
            </div>
            <div class="container rounded border border-secondary my-2 p-1 hide animate2" id="view_department_window">
                <h6 class="text-primary text-center"><u>View Department Details</u> <img class="hide" src="images/ajax_clock_small.gif" id="department_loader_view"></h6>
                <div class="row">
                    <div class="col-md-9 my-2">
                        <span class="hide" id="department_details"></span>
                        <span id="back_to_department_list" class="btn btn-sm my-2 btn-secondary p-1"><i class="fas fa-arrow-left"></i> Back</span>
                        <p><b>Head of Department:</b> <span id="head_of_dept">Not Set</span></p>
                        <p><b>Subjects Present:</b> <span id="subjects_present">Not Set</span></p>
                        <p><b>Members Present:</b> <span id="members_present">Not Set</span></p>
                        <p><b>Date Created:</b> <span id="date_created">Not Set</span></p>
                    </div>
                    <div class="col-md-3 my-2">
                        <span class="btn btn-outline-danger btn-sm p-1" id="delete_department_details"><i class="fas fa-trash"></i> Delete</span>
                    </div>
                    <hr class="w-75 mx-auto my-2">
                    <div class="form-group col-md-6">
                        <input type="hidden" id="department_id_holder">
                        <label for="department_name_view" class="form-label"><b>Department Name</b> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-75" id="department_name_view" placeholder="Department Name">
                        <input type="hidden" value="0" id="dept_codes">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_code_view" class="form-label"><b>Department Code</b> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-75" id="department_code_view" placeholder="e.g DP-001">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_code_view" class="form-label"><b>Head Of Department</b> <span class="text-danger">*</span></label>
                        <span id="hod_window_holder"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_description_view" class="form-label"><b>Department Description</b> </label>
                        <textarea name="department_description_view" id="department_description_view" cols="30" rows="5" class="form-control" placeholder="Department Description (Optional)"></textarea>
                    </div>
                    <div class="col-md-12">
                        <p class="my-1" id="loader_infor_teller_view"></p>
                        <span class="btn btn-primary w-100 btn-sm mx-auto my-1" id="update_departments"><i class="fas fa-save"></i> Update</span>
                    </div>
                    <!-- <hr class="w-75 mx-auto my-2"> -->
                    <div class="col-md-9 mx-auto hide">
                        <h6 class="text-center text-primary">Member List<img class="hide" src="images/ajax_clock_small.gif" id="members_dept_list_loader"></h6>
                        <span class="btn btn-secondary btn-sm" id="add_members_dept"><i class="fas fa-user-plus"></i> Add Members</span>
                        <p id="member_error_handlers"></p>
                        <hr>
                        <input type="hidden" value="[]" id="save_members">
                        <div class="container p-1 col-md-5 ml-auto mr-1 border rounded border-primary hide" id="action_dept">
                            <h6 class="text-secondary text-center">Action</h6>
                            <p class="link" id="remove_staff_depf"><i class="fas fa-trash"></i> Remove</p>
                        </div>
                        <div id="members_list_table">

                        </div>
                        <!-- <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Date Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>James Ouma</td>
                                    <td>Mon 13th Jun 2023</td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                    <!-- <hr class="w-75 mx-auto my-2"> -->
                    <div class="col-md-9 mx-auto hide">
                        <h6 class="text-center text-primary">Subject List<img class="hide" src="images/ajax_clock_small.gif" id="subject_dept_list_loader"></h6>
                        <span class="btn btn-secondary btn-sm" id="add_subject_dept"><i class="fas fa-plus"></i> Add Subject</span>
                        <p id="subject_error_handlers"></p>
                        <hr>
                        <div class="container p-1 col-md-5 ml-auto mr-1 border rounded border-primary hide" id="action_subject_details">
                            <h6 class="text-secondary text-center">Action</h6>
                            <p class="link" id="remove_subject_depf"><i class="fas fa-trash"></i> Remove</p>
                        </div>
                        <input type="hidden" value="[]" id="save_selected_subjects">
                        <div id="subject_list_table_dept">
                            
                        </div>
                        <!-- <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Subject Name</th>
                                    <th>Display Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>James Ouma</td>
                                    <td>Mon 13th Jun 2023</td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>