<div class="contents animate hide" id="assign_credit_note_window">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Assign Credit Note</p>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="row">
                <div class="notice1 col-md-12">
                    <div class="notify">
                        <p><strong>Important:</strong></p>
                    </div>
                    <ul>
                        <li> At this window you will be able to view, edit and update credit notes for students that have existing staffs as parents in school.</li>
                        <li> First search for students by their name or admission no, then assign them the amount that has been credited.</li>
                        <li> Ensure that the student assigned the amount is the correct one.</li>
                    </ul>
                    <button type='button' id='back_to_fees_payment' ><i class="fa fa-arrow-left"></i> Back to Fees Payment</button>
                </div>
            </div>
            <div class="container">
                <div id="credit_note_window">
                    <h6 class="text-center my-2"><b>Credit Notes</b><span class="hide" id="credit_notes_loader"><img src="images/ajax_clock_small.gif"></span></h6>
                    <span class="hide" id="store_credit_notes"></span>
                    <p id="success_message_cr"></p>
                    <div class="row" id="search_option_credit_note">
                        <div class="col-md-6 form-group">
                            <input type="text" name="search" id="searchkey_credit_note" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                        </div>
                    </div>
                    <div class="container table-responsive" id="transDataReciever_credit_note">
                        <!-- <table class="table">
                            <thead>
                                <tr>
                                <th title="Sort all descending"># <span id="sortall"><i class="fas fa-caret-down"></i></span></th>
                                <th  title="Sort by Reg No descending">Adm no {Student Name} <span id="sortadmno"><i class="fas fa-caret-down"></i></span></th>
                                <th  title="Sort by Amount descending">Paid Amount <span id="sortfeeamount"><i class="fas fa-caret-down"></i></span></th>
                                <th  title="Sort by date descending">D.O.P <span id="sortdate"><i class="fas fa-caret-down"></i></span></th>
                                <th>M.O.P</th>
                                <th>Purpose</th></tr>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>LBDB {<small>MAKR OTTO</small> } <span class="badge badge-success"> </span></td>
                                    <td>Kes 1,000</td>
                                    <td>14th June 2021</td>
                                    <td>Bank</td>
                                    <td>Admission Fees</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>LBDB {<small>MAKR OTTO</small> } <span class="badge badge-success"> </span></td>
                                    <td>Kes 1,000</td>
                                    <td>14th June 2021</td>
                                    <td>Bank</td>
                                    <td>Admission Fees</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>LBDB {<small>MAKR OTTO</small> } <span class="badge badge-success"> </span></td>
                                    <td>Kes 1,000</td>
                                    <td>14th June 2021</td>
                                    <td>Bank</td>
                                    <td>Admission Fees</td>
                                </tr>
                            </tbody>
                        </table> -->
                        <div class='displaydata'><img class='' src='images/error.png'></div>
                        <p class='sm-text text-danger text-bold text-center'><br>No records to display, Start by displaying your data with the options above</p>
                    </div>
                    <div class="row mt-5 invisible" id="tablefooter_credit_note">
                        <div class="col-sm-12 col-md-5">
                            <div class="container-fluid">
                                <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_credit_note">1 </span> to <span class="text-primary" id="finishNo_credit_note">10</span> of <span id="tot_records_credit_note"></span> Records.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate_credit_note">
                                <ul class="pagination">
                                    <li class="paginate_button page-item first" id="datatable_first_credit_note"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_credit_note">First</a></li>
                                    <li class="paginate_button page-item previous mx-1" id="datatable_previous_credit_note"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_credit_note">Prev</a></li>
                                    <li class="paginate_button page-item previous active mx-3" id="datatable_previous_credit_note"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_credit_note">1</a></li>
                                    <li class="paginate_button page-item next mx-1" id="datatable_next_credit_note"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_credit_note">Next</a></li>
                                    <li class="paginate_button page-item last mx-1" id="datatable_last_credit_note"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_credit_note">Last</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container my-2 hide" id="credit_note_window2">
                    <span class="btn btn-primary btn-sm" id="back_to_credit_win"><i class="fas fa-arrow-left"></i> Back to List</span>
                    <h6 class="text-center my-2"><b>Credit Note</b><span class="hide" id="credit_notes_loader_win2"><img src="images/ajax_clock_small.gif"></span></h6>
                    <div class="container row border border-secondary rounded w-75 p-1 mx-auto">
                        <div class="col-md-12">
                            <input type="hidden" name="" id="credit_note_id">
                            <h6 class="text-center"><u>Credit Note Details</u></h6>
                        </div>
                        <div class="col-md-4">
                            <p><b>Staff Credited : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="staff_credited_credit">Null</span>
                        </div>
                        <div class="col-md-4">
                            <p><b>Month Assigned : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="month_assigned_credit">Null</span>
                        </div>
                        <div class="col-md-4">
                            <p><b>Date Registered : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="date_credit_note_registered">Null</span>
                        </div>
                        <div class="col-md-4">
                            <p><b>Student Assigned : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="student_assigned_credit">Null</span>
                        </div>
                        <div class="col-md-4">
                            <p><b>Amount : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="amount_credited">Null</span>
                        </div>
                        <div class="col-md-4">
                            <p><b>Status : </b></p>
                        </div>
                        <div class="col-md-8">
                            <span id="credit_note_status">Null</span>
                        </div>
                        <div class="hide col-md-12" id="un_assign_credi_note_window">
                            <hr>
                            <p><b>Click to un-assign credit : </b></p>
                            <span class="btn btn-danger btn-sm my-2" id="un_assign_credit_note">Un-Assign Credit Note</span>
                            <p id="unassign_credit_note_message_holder"></p>
                        </div>
                    </div>
                    <div class="container border border-secondary rounded my-2 p-2" id="assign_credit_note_window_2">
                        <div class="title">
                            <h4 style="text-align:center;">Assign Student Credit Note <span class="hide" id="credit_notes_loader_win3"><img src="images/ajax_clock_small.gif"></span></h4>
                        </div>
                        <div class="conts search_students_finance">
                            <p class="hide" id="display_error_credit_note"></p>
                            <label for="studids" class="form-control-label" >Enter student Name or Reg No: <br> </label>
                            <div class="autocomplete">
                                <input class="form-control" type="text" style="max-width:300px; "  id="student_adm_credit_note" placeholder = "Reg No or Name">
                            </div>
                            <button type='button'  id='search_student_credit_note'>Search</button>
                        </div>
                        <div class="contsfd" id="fees_list_result">
                            
                        </div>
                        <!-- get the fees payment purpose -->
                        <div class="container-fluid my-2">
                            <form id="credit_record_from">
                                <label for="payment_for_option" class="form-control-label">Select what the payment is for:</label>
                                <p id="payment_option_credit_note"></p>

                                <label for="select_time_set_opt_cr" class="form-label" id="select_time_cash">Set Fees Payment Time</label>
                                <select name="select_time_set_opt_cr" id="select_time_set_opt_cr" class="form-control">
                                    <option id="" value="auto" selected >Automatically Capture</option>
                                    <option id="" value="set" >Set Date/Time</option>
                                </select>

                                <div class="container hide border border-secondary rounded my-2" id="set_time_cr_nt">
                                    <h6><u>Select Time</u></h6>
                                    <p><b>Note:</b> <br>Change time and date only if the transaction was made before.</p>
                                    <hr>
                                    <label for="date_of_payments_fees_cr_nt" class="form-label">Date of payments</label>
                                    <input type="date"  class="form-control" id="date_of_payments_fees_cr_nt" value="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d");?>">
                                    <div class="col-md-6">
                                        <label for="time_of_payment_fees_cr_nt" class="form-label">Date Of Payments</label>
                                        <input type="time" class="form-control" id="time_of_payment_fees_cr_nt" value="<?php echo date("H:i")?>"  max="<?php echo date("H:i");?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="button">
                            <div class="bg-white p-1">
                                <p class="text-bolder">By clicking Assign Payment you are confirming transfer of Credit of Ksh <strong id="amount_to_credit_cr_nt">1000</strong> to <strong id="stud_name_credit_note">Student name</strong>.</p>
                            </div>
                            <button class="btn btn-primary " id="assign_payment_credit_note">Assign Payment</button>
                            <p id="error_handled_credit_note"></p>
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