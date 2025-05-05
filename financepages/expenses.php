<div class="contents animate hide" id="expenses_win">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Record expenses</p>
                </div>
                <div class="col-md-3">
                    <span id="record_expenses_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- At this window you are allowed to record expenses and liabilities from both operating and non-operating activities.</p>
                <p>- Please capture the information you enter correctly.</p>
            </div>
            <div class="expense_record_panel">
                <h6><i class="fa fa-file" style="font-size:18px;margin-bottom:10px;"></i> Expenses:</h6>
                <div class="expeses_options" id="exp_options">
                    <button id="add_exp"><i class="fas fa-plus"></i> Add Expenses</button>
                    <button class="hide" id="approve_payments"><i class="fas fa-check"></i> Approve Payments</button>
                    <button class="hide" id="find_exp_da">Find Expenses</button>
                </div>
                <div class="find_exp hide" id="find_exp_date">
                    <div class="conts">
                        <h6>Find Expense</h6>
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="view-options-date">Select options: <br></label>
                        <select class="form-control" name="view-options-date" id="view-options-date">
                            <option value="" hidden>Select option..</option>
                            <option value="by-date">By date</option>
                            <option value="by-month">By Month</option>
                        </select>
                        <div class="bydate-view hide" id="bydates_viewings">
                            <label class="form-control-label" for="date_for_exp">Select date: <br></label>
                            <input class="form-control" type="date" name="date_for_exp" id="date_for_exp" max=<?php echo date("Y-m-d");?>>
                        </div>
                        <div class="bymonth-view hide" id="by_months_viewing">
                            <label class="form-control-label" for="sele-years">Select year: <br></label>
                            <select class="form-control" name="sele-years" id="sele-years">
                                <option value="" hidden>Select option..</option>
                                <?php
                                    $year = date("Y");
                                    for ($count=$year; $count > 2018; $count--) { 
                                        echo "<option value='$count'>$count</option>";
                                    }
                                ?>
                            </select>
                            <label class="form-control-label" for="month_for_exp"><br>Select Month: <br></label>
                            <select class="form-control" name="month_for_exp" id="month_for_exp">
                                <option value="" hidden>Select option..</option>
                                <option value="Jan">January</option>
                                <option value="Feb">February</option>
                                <option value="Mar">March</option>
                                <option value="Apr">April</option>
                                <option value="May">May</option>
                                <option value="Jun">June</option>
                                <option value="Jul">July</option>
                                <option value="Aug">August</option>
                                <option value="Sep">September</option>
                                <option value="Oct">October</option>
                                <option value="Nov">November</option>
                                <option value="Dec">December</option>
                            </select>
                        </div>
                        <p id="date_err"></p>
                    </div>
                    <div class="conts">
                        <button id="disp_btns">Display</button>
                        <button id='done_display_exp'>Close</button>
                    </div>
                </div>
                <div class="conts hide" id="recordexp">
                    <div class="conts">
                        <h6 class="text-center"><u>Add Expense</u></h6>
                    </div>
                    <div class="message_contents mt-3">
                        <p>- All expenses must be approved by the pricipal</p>
                        <p>- It starts by you making a request and from the pricipal side they`ll approve all expense requests!</p>
                    </div>
                    <hr>
                    <div class="row border border-secondary m-1 p-1" >
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="exp_named"><b>Expense Name:</b> <br></label>
                            <input class="form-control w-100" type="text" name="exp_named" id="exp_named" placeholder = "Expense Name">
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="exp_cat"><b>Expense category</b> <br><span class="hide" id="load_expense_categs"><img src="images/ajax_clock_small.gif" id=""></span></label>
                            <div id="expense_categories_holders"></div>
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="exp_cat"><b>Expense Sub-Category</b> <br><span class="hide" id="load_expense_sub_categs"><img src="images/ajax_clock_small.gif" id=""></span></label>
                            <div id="expense_subcategory_display"><p class="text-danger">Select expense category to display expense sub-category!</p></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="expense_cash_activity" class="form-control-label"><b>Expense Activity</b></label>
                            <select name="expense_cash_activity" id="expense_cash_activity" class="form-control w-100">
                                <option value="" hidden>Select Option</option>
                                <option value="1">Operating Activities</option>
                                <option value="2">Investing Activities</option>
                                <option value="3">Financing Activities</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="expense_record_date" class="form-control-label"><b>Expense Record Date</b></label>
                            <input type="date" class="form-control w-100" id="expense_record_date" value="<?php echo date("Y-m-d");?>">
                        </div>
                        <div class="conts col-md-4 hide">
                            <label class="form-control-label" for="exp_quant"><b>Expense quantity:</b> <br></label>
                            <input class="form-control w-100" value="1" type="number" name="exp_quant" id="exp_quant"  min = "0" placeholder = "Quantity">
                        </div>
                        <div class="conts col-md-4 hide">
                            <label class="form-control-label" for="exp_amnt"><b>Expense unit cost:</b> <br></label>
                            <input class="form-control w-100" type="number" name="exp_amnt" id="exp_amnt" value = '0' min = "0" placeholder = "Amount">
                        </div>
                        <div class="conts col-md-4 hide">
                            <label class="form-control-label" for="unit_name"><b>Unit Name:</b> <small>(eg. kgs, litres)</small> <br></label>
                            <input class="form-control w-100" type="text" name="unit_name" id="unit_name" placeholder = "Unit Name">
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="exp_total_amt"><b>Expense Amount</b> <br></label>
                            <input class="form-control w-100" type="number" name="exp_total_amt" id="exp_total_amt" min = "0" placeholder = "Amount">
                        </div>
                        <div class="conts col-md-4">
                            <label class="form-control-label" for="document_number"><b>Document Number</b> <br></label>
                            <input class="form-control w-100" type="text" name="document_number" id="document_number"  min = "0" placeholder = "Optional - Receipt, Invoice, Cheque">
                        </div>
                        <div class="col-md-12">
                            <label for="new_expense_description" class="form-control-label"><b>Expense Description</b></label>
                            <textarea name="new_expense_description" id="new_expense_description" cols="30" rows="3" class="form-control" placeholder="Write expense description here.."></textarea>
                        </div>
                        <div class="conts col-md-4">
                            <p id='err_hndler_expenses'></p>
                        </div>
                    </div>
                    <div class="conts col-md-6 row">
                        <div class="col-md-6">
                            <div id="error_message_expenses"><p class="text-danger text-center"><b>Select expense category before you proceed!</b></p></div>
                            <button id='add_expenseed' class="hide"><i class="fas fa-paper-plane"></i> Make Payment Request</button>
                        </div>
                        <div class="col-md-6">
                            <button id='done_adding_exp'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="display_daily_expense">
                <div class="tables">
                    <p id ="my_table"></p>
                    <div id="window_expenses">
                        <hr>
                        <h6 class="text-center my-2"><b>Expenses Table</b></h6>
                        <div class="row d-none" id="search_option_expenses">
                            <div class="col-md-6 form-group row">
                                <input type="text" name="search" id="searchkey_expenses" class="w-100 form-control rounded-lg p-1" placeholder="Search here ..">
                            </div>
                        </div>
                        <div class="table-responsive" id="transDataReciever_expenses">
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
                        <div class="row mt-5 invisible" id="tablefooter_expenses">
                            <div class="col-sm-12 col-md-5">
                                <div class="container-fluid">
                                    <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_expenses">1 </span> to <span class="text-primary" id="finishNo_expenses">10</span> of <span id="tot_records_expenses"></span> Records.</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item first" id="datatable_first"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_expenses">First</a></li>
                                        <li class="paginate_button page-item previous mx-1" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_expenses">Prev</a></li>
                                        <li class="paginate_button page-item previous active mx-3" id="datatable_previous"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_expenses">1</a></li>
                                        <li class="paginate_button page-item next mx-1" id="datatable_next"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_expenses">Next</a></li>
                                        <li class="paginate_button page-item last mx-1" id="datatable_last"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_expenses">Last</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="conts">
                    <div class="table_holders">
                        <!-- <table>
                            <tr>
                                <th>Expense Name</th>
                                <th>Expense Category</th>
                                <th>Units</th>
                                <th>Price per Unit</th>
                                <th>Total Amount</th>
                            </tr>
                            <tr>
                                <td>Sugar</td>
                                <td>Utility</td>
                                <td>3</td>
                                <td>Ksh 102</td>
                                <td><b>Ksh 306</b></td>
                            </tr>
                        </table> -->
                        <!-- <table>
                            <tr>
                                <th>No.</th>
                                <th>Expense Category</th>
                                <th>Total</th>
                                <th>Record(s)</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td>Daily-expense</td>
                                <td>Kes 1,000</td>
                                <td>2</td>
                            </tr>
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