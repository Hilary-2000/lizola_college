<div class="contents animate hide" id="findtransaction">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Manage Transactions</p>
                </div>
                <div class="col-md-3">
                    <span id="manage_transactions_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="ontop">
                <div class="top">
                    <strong>Note:</strong>
                    <p>- At this window you can trace for any transaction done by the system</p>
                    <p>- Start by filtering it to suite your search</p>
                    <p>- Select <strong>By class</strong> and select a class to print fee reminders.</p>
                    <p id='look'></p>
                </div>
                <div class="tops">
                    <div class="conts">
                        <label class="form-control-label" for="timeopt">Select time period:</label><br>
                        <select class="form-control" name="timeopt" id="timeopt">
                            <option value="" hidden>Select option...</option>
                            <option value="today" id='todayfees'>Today</option>
                            <option value="last2days">Last 2 Days</option>
                            <option value="last5days">Last 5 Days</option>
                            <option value="lastoneweek">Last one week</option>
                            <option value="btndates" id="btnd">Between dates</option>
                            <option value="clased">By class</option>
                            <option value="transactioncodes">By Transaction code</option>
                        </select>
                    </div>
                    <div class="conts" id='otheropts'>
                        <div class="otheropt">
                            <div class="conts">
                                <label class="form-control-label" for="student_s">Select option..</label><br>
                                <select class="form-control" name="student_s" id="student_s">
                                    <option value="" hidden>Select option..</option>
                                    <option value="admno" id="spcificstd">Specific Student</option>
                                    <option value="allstudents" id='allstudents'>All Students</option>
                                </select>
                            </div>
                            <div class="conts hide" id='enteradmno'>
                                <label class="form-control-label" for="admnno">Enter admission no:</label><br>
                                <input class="form-control" type="text" name="admnno" id="admnno" placeholder="Enter admission number">
                            </div>
                        </div>
                    </div>
                    <div class="classlists hide" id="classlists">
                        <label class="form-control-label" for="classedd">Select class:</label>
                        <p id='manage_trans'></p>
                    </div>
                    <div class="conts hide" id="trans_code">
                        <label class="form-control-label" for="transact_code">Enter transaction code: <br></label>
                        <input class="form-control" type="text" name="transact_code" id="transact_code" placeholder="Enter transaction code">
                    </div>
                    <div class="conts">
                        <span class="btn btn-primary mx-2" type='button' id="searchtransaction">Search</span>
                    </div>
                </div>
                <div class="conts hide" id='btndates'>
                    <div class="btndates ">
                        <div class="conts ">
                            <label class="form-control-label" for="startdate">From: <br></label>
                            <input class="form-control" type="date" name="startdate" id="startdate" placeholder="start date" max=<?php echo date("Y-m-d", strtotime("3 hour")); ?>>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="enddate">To: <br></label>
                            <input class="form-control" type="date" name="enddate" id="enddate" placeholder="end date" max=<?php echo date("Y-m-d", strtotime("3 hour")); ?>>
                        </div>
                    </div>
                </div>
            </div>
            <div class="body1">
                <p id='errhandler'></p>
                <div id="window_2">
                    <hr>
                    <h6 class="text-center my-2"><b>Transaction Details</b></h6>
                    <div class="row d-none" id="search_option_fee">
                        <div class="col-md-6 form-group row">
                            <input type="text" name="search" id="searchkey_fees" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                        </div>
                    </div>
                    <div class="table-responsive" id="transDataReciever_fees">
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
                    <div class="row mt-5 invisible" id="tablefooter_fees">
                        <div class="col-sm-12 col-md-5">
                            <div class="container-fluid">
                                <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_fees">1 </span> to <span class="text-primary" id="finishNo_fees">10</span> of <span id="tot_records_fees"></span> Records.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item first" id="datatable_first"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_fees">First</a></li>
                                    <li class="paginate_button page-item previous mx-1" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_fees">Prev</a></li>
                                    <li class="paginate_button page-item previous active mx-3" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_fees">1</a></li>
                                    <li class="paginate_button page-item next mx-1" id="datatable_next"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_fees">Next</a></li>
                                    <li class="paginate_button page-item last mx-1" id="datatable_last"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_fees">Last</a></li>
                                </ul>
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