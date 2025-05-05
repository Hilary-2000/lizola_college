<div class="contents animate hide" id="enroll_students_transportsystem">
    <div class="titled">
        <h2>Transport System</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Students In Transport System</p>
                </div>
                <div class="col-md-3">
                    <span id="transport_system_student_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- ADD, UPDATE AND REMOVE students IN the Transport system.</p>
            </div>
            <div class="conts">
                <div class="staff_information" >
                    <p class="hide" id="statistics_trans"></p>
                    <h6>Statistics <img src="images/ajax_clock_small.gif" class="hide" id="statistics_loader"></h6>
                    <p><strong>Students enrolled : </strong> <span id="students_enrolled">25 Student(s)</span></p>
                    <p><strong>Routes Available : </strong> <span id="routes_counted">4 Route(s)</span></p>
                    <p><strong>School Vans : </strong> <span id="vans_counted">5 Van(s)</span></p>
                </div>
                <hr>
                <div class="staff_information " id="students_trans_enrolled">
                    <h6 class="text-center"><strong>Students List</strong> <span class="hide" id="student_trans_loader"><i class="fas fa-spinner fa-spin"></i></span></h6>
                    <p class="block_btn" id="enroll_student_tr"><i class="fas fa-plus"></i> Enroll Student</p>
                    <div class="conts">
                        <div class="table_holders">
                            <p class="hide" id="std_inform_trans"></p>
                        </div>
                        <div class="row m-0">
                            <div class="col-sm-7">
                            </div>
                            <div class="col sm-5">
                                <div class="input-group my-3">
                                    <input type="text" name="searchkey" id="searchkey4" class="form-control border border-dark rounded p-2 text-xs font-weight-bold" style="width:fit-content;" placeholder="Enter keyword to search table...">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" id="transDataReciever4">
                            <table class="table">
                                <tr>
                                    <th>No.</th>
                                    <th>Student Name.</th>
                                    <th>Stoppage</th>
                                    <th>Route</th>
                                    <th>Date Joined</th>
                                    <th>Actions</th>
                                </tr>
                                <tr>
                                    <td>1. </td>
                                    <td>OWEN MALINGU</td>
                                    <td>Kitisuru</td>
                                    <td>Route 2</td>
                                    <td>30th March 2022</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> View</p></td>
                                </tr>
                                <tr>
                                    <td>2. </td>
                                    <td>OWEN MALINGU</td>
                                    <td>Kitisuru</td>
                                    <td>Route 2</td>
                                    <td>30th March 2022</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> View</p></td>
                                </tr>
                                <tr>
                                    <td>3. </td>
                                    <td>OWEN MALINGU</td>
                                    <td>Kitisuru</td>
                                    <td>Route 2</td>
                                    <td>30th March 2022</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> View</p></td>
                                </tr>
                            </table>
                        </div>
                        <div class="row mt-5" id="tablefooter4">
                            <div class="col-sm-12 col-md-5">
                                <div class="container-fluid">
                                    <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo4">1 </span> to <span class="text-primary" id="finishNo4">10</span> of <span id="tot_records4"></span> Records.</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate4">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item first" id="datatable_first4"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav4">First</a></li>
                                        <li class="paginate_button page-item previous mx-1" id="datatable_previous4"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac4">Prev</a></li>
                                        <li class="paginate_button page-item previous active mx-3" id="datatable_previous4"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav4">1</a></li>
                                        <li class="paginate_button page-item next mx-1" id="datatable_next4"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav4">Next</a></li>
                                        <li class="paginate_button page-item last mx-1" id="datatable_last4"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav4">Last</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="staff_information hide" id="enroll_stud_transport">
                    <h6 class="text-center" ><strong>Register Student</strong></h6>
                    <p class="block_btn" id="back_to_std_trans_list"><i class="fas fa-arrow-left"></i> Back to Student list</p>
                    <div class="cont">
                        <p><strong>Important</strong></p>
                        <p>- Start by finding the students by either their name or admission number</p>
                        <p>- Select from the drop down (will appear when you type on the search box) the student you want to enroll.</p>
                        <p>- Their admission number will be auto filled in the search box. Click the search button the students data will be populated in the fields below</p>
                        <p>- Only the students stoppage address can be edited</p>
                    </div>
                    <hr>
                    <h6>Search Student</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="student_named" class="form-control-label">Search by Fullname or admission no <img src="images/ajax_clock_small.gif" class="hide" id="admission_nos_223"></label>
                            <div class="autocomplete">
                                <input type="text" name="student_named" style="width: 100% !important;" id="student_named" class="form-control" placeholder="Search here by Student Name or Admission no">
                            </div>
                            <p class="block_btn" id="search_by_admission_no1"><i class="fas fa-search"></i> Auto Fill</p>
                            <p id="err_handler_transport_sys"></p>
                        </div>
                        <div class="col-md-6">
                            <p class="hide" id="output_2333"></p>
                        </div>
                    </div>
                    <hr>
                    <h6>Student data</h6>
                    <p class="hide" id="std_data"></p>
                    <div class="row container">
                        <div class="col-md-6">
                            <input type="hidden" name="" id="std_ids_in">
                            <label for="_std_fullname" class="form-control-label">Student`s Fullname</label>
                            <input type="text" readonly style="width: 100%;" class="form-control" id="_std_fullname" placeholder="Student Fullname">
                        </div>
                        <div class="col-md-6">
                            <label for="_std_class" class="form-control-label">Student`s Class</label>
                            <input type="text" readonly style="width: 100%;" class="form-control" id="_std_class" placeholder="Student Class">
                        </div>
                        <div class="col-md-6">
                            <label for="_std_dor" class="form-control-label">Student`s Date of Registration</label>
                            <input type="date" readonly style="width: 100%;" value="<?php echo date('Y-m-d');?>" class="form-control" id="_std_dor" placeholder="Student Fullname">
                        </div>
                        <div class="col-md-6">
                            <label for="_std_stopage" class="form-control-label">Student`s Stoppage address <span class="text-success">Editable</span></label>
                            <input type="text" style="width: 100%;" class="form-control" id="_std_stopage" placeholder="Fill the stoppage address">
                        </div>
                        <div class="col-md-6">
                            <label for="_std_dor" class="form-control-label">Student`s Route <span class="text-success">Editable</span></label>
                            <img src="images/ajax_clock_small.gif" id="student_routes_loaders">
                            <span id="student_routes_loader12">
                            </span>
                        </div>
                    </div>
                    <div class="row container my-2">
                        <div class="col-md-4 hide" id="the_save_button">
                            <p class="block_btn" id="save_trans_stud"><i class="fas fa-save"></i> Enroll Student <span class="hide" id="enroll_loader_trans"><i class="fas fa-spinner fa-spin" id=""></i></span></p>
                        </div>
                    </div>
                </div>
                <!-- the window to view student information -->
                <div class="staff_information hide" id="view_student_infor_trans">
                    <h6 class="text-center"><strong>View / Update Student`s Information</strong></h6>
                    <p class="block_btn" id="back_to_std_trans_list2"><i class="fas fa-arrow-left"></i> Back to Student list</p>
                    <div class="cont">
                        <p><strong>Important</strong></p>
                        <p>- Update students information from this window</p>
                        <hr>
                        <?php
                            include_once("connections/conn2.php");
                            function getTermDetails($conn2){
                                $selected_term = date("Y-m-d");
                                $select = "SELECT * FROM `academic_calendar` WHERE `end_time` >= '$selected_term'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $results = $stmt->get_result();
                                $terms = [];
                                if ($results) {
                                    while ($row = $results->fetch_assoc()) {
                                        array_push($terms,$row['term']);
                                    }
                                }
                                return $terms;
                            }

                            function showOption($term,$conn2){
                                // show terms
                                $my_terms = getTermDetails($conn2);

                                // loop to show if present
                                return in_array($term,$my_terms);
                            }
                        ?>
                            <div class="container hide">
                        <h6><strong>Actions</strong></h6>
                                <label for="select_term_deregister" class="form-control-label"><b>De-register From : </b></label>
                                <select name="select_term_deregister" id="select_term_deregister" class="form-control">
                                    <option value="" hidden > Select Option</option>
                                    <option <?php $my_tm = "TERM_1"; echo showOption($my_tm,$conn2) ? '': 'hidden';?> value="TERM_1">From Term 1</option>
                                    <option <?php $my_tm = "TERM_2"; echo showOption($my_tm,$conn2) ? '':'hidden';?> value="TERM_2">From Term 2</option>
                                    <option <?php $my_tm = "TERM_3"; echo showOption($my_tm,$conn2) ? '':'hidden';?> value="TERM_3">From Term 3</option>
                                </select>
                                <p id="de_register_stud_transport"class="link" style="width: fit-content;"><i class="fas fa-user-minus"></i> De-register Student</p>
                            </div>
                            <hr>
                            <h6><b>Route Termly Details</b></h6>
                            <div class="container row">
                                <div class="col-md-4 border border-secondary rounded my-1 p-1">
                                    <h6 class="text-center">Term One Route <span class="hide" id="term_one_tr_loader" ><img src="images/ajax_clock_small.gif"></span></h6>
                                    <label for="route_for_term_1">Select Route</label>
                                    <div class="" id="route_term_1">
                                        <p class="text-danger">Routes are not set yet!</p>
                                    </div>
                                    <span id="save_term_one_route" class="btn btn-sm btn-success w-100 my-2"><i class="fas fa-save"></i> Save Term 1 Route</span>
                                    <span id="error_message_t1"></span>
                                </div>
                                <div class="col-md-4 border border-secondary rounded my-1 p-1">
                                    <h6 class="text-center">Term Two Route <span id="term_two_tr_loader" class="hide"><img src="images/ajax_clock_small.gif"></span></h6>
                                    <label for="route_for_term_2">Select Route</label>
                                    <div class="" id="route_term_2">
                                        <p class="text-danger">Routes are not set yet!</p>
                                    </div>
                                    <span id="save_term_two_route" class="btn btn-sm btn-success w-100 my-2"><i class="fas fa-save"></i> Save Term 2 Route</span>
                                    <span id="error_message_t2"></span>
                                </div>
                                <div class="col-md-4 border border-secondary rounded my-1 p-1">
                                    <h6 class="text-center">Term Three Route <span id="term_three_tr_loader" class="hide"><img src="images/ajax_clock_small.gif"></span></h6>
                                    <label for="route_for_term_3">Select Route</label>
                                    <div class="" id="route_term_3">
                                        <p class="text-danger">Routes are not set yet!</p>
                                    </div>
                                    <span id="save_term_three_route" class="btn btn-sm btn-success w-100 my-2"><i class="fas fa-save"></i> Save Term 3 Route</span>
                                    <span id="error_message_t3"></span>
                                </div>
                            </div>
                        <hr>
                    </div>
                    <div class="row my-2">
                        <h6><strong>Student details <span id="stud_data_loader" class="hide"><i class="fas fa-spinner fa-spin"></i></span></strong></h6>
                        <p class="hide" id="stud_data_2"></p>
                        <p><strong>Adm No</strong>: <span id="admn_no_trans"></span></p>
                        <div class="col-md-6">
                            <input type="hidden" name="" id="stud_detail_trans_id">
                            <label for="full_name" class="form-control-label">Student`s Fullname</label>
                            <input type="text" style="width: 100%;" name="full_name" id="full_name" readonly class="form-control" placeholder="Student`s fullname">
                        </div>
                        <div class="col-md-6">
                            <label for="studs_class_trans" class="form-control-label">Student`s Class</label>
                            <input type="text" style="width: 100%;" name="studs_class_trans" id="studs_class_trans" readonly class="form-control" placeholder="Student`s Class">
                        </div>
                        <div class="col-md-6">
                            <label for="studs_dor_trans2" class="form-control-label">Student`s Date of registration</label>
                            <input type="date" style="width: 100%;" name="studs_dor_trans2" id="studs_dor_trans2"  class="form-control" placeholder="Date or registration">
                        </div>
                        <div class="col-md-6">
                            <label for="stud_stoppage_trans" class="form-control-label">Student`s Stoppage {<span id="stoppage_val"></span>}</label>
                            <input type="text" style="width: 100%;" name="stud_stoppage_trans" id="stud_stoppage_trans"  class="form-control" placeholder="Student`s Stoppage">
                        </div>
                        <div class="col-md-6 hide">
                            <label for="" class="form-control-label">Student`s Route {<span id="route_values"></span>}</label>
                            <img src="images/ajax_clock_small.gif" id="student_routes_loaders2">
                            <span id="student_routes_loader13">
                            </span>
                        </div>
                    </div>
                    <div class="cont">
                        <p id="err_handler_transport2"></p>
                    </div>
                    <div class="row hide">
                        <div class="col-md-6">
                            <p class="block_btn" id="Update_stud_trans"><i class="fas fa-upload"></i> Update student data <span class="hide" id="update_std_spinner"><i class="fas fa-spinner fa-spin"></i></span> </p>
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