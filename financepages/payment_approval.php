<div class="contents animate hide" id="payment_approval_window">
    <div class="titled">
        <h2>Finance - Payment Approval</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Payment Approval</p>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="row">
                <div class="notice1 col-md-12">
                    <!-- <button type='button' id='back_to_fees_payment_2'><i class="fa fa-arrow-left"></i> Back to Fees Payment</button> -->
                    <div class="notify">
                        <p><strong>Important:</strong></p>
                    </div>
                    <ul>
                        <li> Accept or decline payment approvals from this window.</li>
                        <li> The table below shows payment requests made by the finance Officer.</li>
                    </ul>
                </div>
            </div>
            <div class="container" id="payment_approvals">
                <button id="back_to_expenses_view"><i class="fas fa-arrow-left"></i> Back</button>
                <div class="table_container">
                    <h6 class="text-center my-2"><u>Payment Requests Table</u> <img src="images/ajax_clock_small.gif" id="payment_request_table_loader"></span></h6>
                    <input type="hidden" name="" value="1" id="payment_request_page">
                    <input type="hidden" name="" value="1" id="maximum_payment_request">
                    <div class="hide" id="payment_request_holder"></div>
                    <div id="payment_request_success"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <input type="text" class="text form-control w-100" id="search_payment_approvals" placeholder="Enter Keyword Here">
                        </div>
                    </div>
                    <div id="payment_request_tables">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Payment for</th>
                                    <th>Expense Categories</th>
                                    <th>Expense Amount.</th>
                                    <th>Date Paid</th>
                                    <th>Document Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Expense 1</td>
                                    <td>Expense Category</td>
                                    <td>Kes 10,000</td>
                                    <td>21st Jun 2023 @ 13:04PM.</td>
                                    <td>DSFSDFE</td>
                                    <td>
                                        <span style="font-size:12px;" class="link view_pay_request" id="view3"><i class="fa fa-eye"></i> View </span> <br> 
                                        <span style="font-size:12px;" class="link accept_pay_request" id="view1"><i class="fa fa-check"></i> Accept </span> <br> 
                                        <span style="font-size:12px; color:red;" class="link decline_pay_request" id="view2"><b>X</b> Decline </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row w-50">
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="previous_payment_requests"><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-left"></i></span>
                        </div>
                        <div class="col-sm-4">
                            <span id="payment_requests_index" class="text-center">Page 1 of 2</span>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="next_payment_requests"><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-right"></i></span>
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