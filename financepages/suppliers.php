<div class="contents animate hide" id="supplier_data">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Suppliers</p>
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
                        <li> VIEW, UPDATE AND DELETE THE SCHOOL SUPPLIERS.</li>
                    </ul>
                </div>
            </div>
            <div class="container" id="supplier_list">
                <button class="nav-bar-btns" id="add-supplier-btn"><i class="fa fa-plus"></i> Register New Supplier <img class="hide" src="images/ajax_clock_small.gif" id="show_supplier_loader"></button>
                <div id="supplier_runtime_error"></div>
                <div class="hide" id="show_supplier_list"></div>
                <div class="table_container">
                    <h6 class="text-center my-2"><u>Supplier List</u></h6>
                    <input type="hidden" name="" value="1" id="supplier_page">
                    <input type="hidden" name="" value="1" id="maximum_supplier_page">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <input type="text" class="text form-control w-100" id="search_school_suppliers" placeholder="Enter Keyword Here">
                        </div>
                    </div>
                    <p id="supplier_notices"></p>
                    <div id="supplier_table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Supplier Name</th>
                                    <th>Amount Owed</th>
                                    <th>Date Registered.</th>
                                    <th>Contact Person</th>
                                    <th>Supplier Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>James Kiboro</td>
                                    <td>Kes 10,000</td>
                                    <td>21st Jun 2023 @ 13:04PM.</td>
                                    <td>Kibwezi West</td>
                                    <td>James</td>
                                    <td><span style="font-size:12px;" class="link view_students" id="view1"><i class="fa fa-pen-fancy"></i> Edit </span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row w-50">
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="previous_supplier_page"><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-left"></i></span>
                        </div>
                        <div class="col-sm-4">
                            <span id="supplier_page_index" class="text-center">Page 1 of 2</span>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="next_supplier_page"><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container hide" id="register_suppliers">
                <button id="return_to_supplier_list"><i class="fa fa-arrow-left"></i> Back to suppliers </button>
                <div class="row my-2">
                    <div class="col-md-12 my-2">
                        <h4 class="text-center"><u>Register Supplier</u></h4>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="company_name" class="form-label"><b>Company Name</b></label>
                        <input type="text" name="company_name" id="company_name" class="form-control w-100" placeholder="Company Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_prefix" class="form-label"><b>Supplier Prefix</b></label>
                        <select name="supplier_prefix" id="supplier_prefix" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Sir">Sir</option>
                            <option value="Madam">Madam</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_name" class="form-label"><b>Supplier Name</b></label>
                        <input type="text" name="supplier_name" id="supplier_name" class="form-control w-100" placeholder="Supplier Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_phone" class="form-label"><b>Supplier Phone</b></label>
                        <input type="text" name="supplier_phone" id="supplier_phone" class="form-control w-100" placeholder="Phone Number">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_email" class="form-label"><b>Supplier Email</b></label>
                        <input type="text" name="supplier_email" id="supplier_email" class="form-control w-100" placeholder="Email Address">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_address" class="form-label"><b>Supplier Address</b></label>
                        <input type="text" name="supplier_address" id="supplier_address" class="form-control w-100" placeholder="Supplier Address">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_openning_balance" class="form-label"><b>Opening Balance as of </b><small>(<?= date("D dS M Y") ?>)</small></label>
                        <input type="number" name="supplier_openning_balance" id="supplier_openning_balance" value="0" class="form-control w-100" placeholder="Opening Balance">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_bank_name" class="form-label"><b>Bank Name</b></label>
                        <input type="text" name="supplier_bank_name" id="supplier_bank_name" class="form-control w-100" placeholder="Bank Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="account_no" class="form-label"><b>Bank Account No</b></label>
                        <input type="text" name="account_no" id="account_no" class="form-control w-100" placeholder="Bank Account No.">
                    </div>
                    <div class="col-md-12">
                        <label for="supplier_note" class="form-label"><b>Note</b></label>
                        <textarea name="supplier_note" id="supplier_note" cols="30" rows="5" class="form-control w-100" placeholder="Write a short narative about the supplier!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="supplier_errors"></div>
                        <button id="save_suppliers"><i class="fa fa-save"></i> Save Supplier <img class="hide" src="images/ajax_clock_small.gif" id="save_suppliers_loader"></button>
                    </div>
                </div>
            </div>
            <div class="container p-1 mx-auto hide" id="edit_suppliers">
                <button id="return_to_supplier_list_2"><i class="fa fa-arrow-left"></i> Back to suppliers </button>
                <div class="row my-2">
                    <div class="col-md-12 my-2">
                        <h4 class="text-center"><u>View Supplier</u> <img class="hide" src="images/ajax_clock_small.gif" id="supplier_data_loaders"></h4>
                    </div>
                    <div class="cont my-2">
                        <div class="container ml-3">
                            <span class="btn btn-sm btn-secondary" id="delete_supplier"><i class="fas fa-trash"></i> Delete</span>
                            <a href="reports/reports.php?supplier_account_id=" id="supplier_href_link" target="_blank" class="btn btn-sm btn-primary text-white"><i class="fas fa-print"></i> Print Supplier Accounts</a>
                        </div>
                        <div class="message_contents mt-3 hide" id="delete_supplier_window">
                            <label class="form-control-label"><u>Confirm:</u></label>
                            <p>- Are you sure you want to delete this supplier?</p>
                            <p>- All your supplier data will be deleted and actions will not be reversible.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <button id="confirm_delete_supplier"><i class="fas fa-trash"></i> Delete <img class="hide" src="images/ajax_clock_small.gif" id="delete_supplier_loader"></button>
                                </div>
                                <div class="col-md-6">
                                    <button id="cancel_delete_supplier"><i class="fas fa-x"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="company_name" class="form-label"><b>Company Name</b></label>
                        <input type="hidden" name="" id="supplier_id">
                        <input type="text" name="company_name" id="company_name_2" class="form-control w-100" placeholder="Company Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_prefix" class="form-label"><b>Supplier Prefix</b></label>
                        <select name="supplier_prefix" id="supplier_prefix_2" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Sir">Sir</option>
                            <option value="Madam">Madam</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_name_2" class="form-label"><b>Supplier Name</b></label>
                        <input type="text" name="supplier_name_2" id="supplier_name_2" class="form-control w-100" placeholder="Supplier Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_phone_2" class="form-label"><b>Supplier Phone</b></label>
                        <input type="text" name="supplier_phone_2" id="supplier_phone_2" class="form-control w-100" placeholder="Phone Number">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_email_2" class="form-label"><b>Supplier Email</b></label>
                        <input type="text" name="supplier_email_2" id="supplier_email_2" class="form-control w-100" placeholder="Email Address">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_address_2" class="form-label"><b>Supplier Address</b></label>
                        <input type="text" name="supplier_address_2" id="supplier_address_2" class="form-control w-100" placeholder="Supplier Address">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="supplier_bank_name" class="form-label"><b>Bank Name</b></label>
                        <input type="text" name="supplier_bank_name" id="supplier_bank_name_2" class="form-control w-100" placeholder="Bank Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="account_no" class="form-label"><b>Bank Account No</b></label>
                        <input type="text" name="account_no" id="account_no_2" class="form-control w-100" placeholder="Bank Account No.">
                    </div>
                    <div class="col-md-12">
                        <label for="supplier_note" class="form-label"><b>Note</b></label>
                        <textarea name="supplier_note" id="supplier_note_2" cols="30" rows="5" class="form-control w-100" placeholder="Write a short narative about the supplier!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="supplier_errors_2"></div>
                        <button id="save_suppliers_2"><i class="fa fa-save"></i> Update Supplier <img class="hide" src="images/ajax_clock_small.gif" id="save_suppliers_loader_2"></button>
                    </div>
                </div>
                <hr class="my-1 p-1">
                <div class="hide" id="supplier_payment_bills"></div>
                <h6 class="my-2 text-center"><u>Bills & Payments</u></h6>
                <div class="container p-1 row">
                    <div class="col-md-6">
                        <h6 class="text-center">Bills</h6>
                        <button id="add_bills"><i class="fa fa-plus"></i> Add Bills</button>
                        <hr class="my-2">
                        <div class="" id="supplier_bill_tables">
                            <table class="table">
                                <tr>
                                    <th>No.</th>
                                    <th>Bill Name</th>
                                    <th>Amount</th>
                                    <th>Date Registered</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Fire Extinguisher Supplies</td>
                                    <td>Kes 10,000</td>
                                    <td>Mon 5th Jun 2024</td>
                                    <td><span class="link"><i class="fas fa-eye"></i> View</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center">Payments</h6>
                        <button id="add_payments"><i class="fa fa-plus"></i> Make Payment Request</button>
                        <hr class="my-2">
                        <div class="" id="supplier_payment_table">
                            <table class="table">
                                <tr>
                                    <th>No.</th>
                                    <th>Paid Amount</th>
                                    <th>Date Paid</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Kes 10,000</td>
                                    <td>Mon 5th Jun 2024</td>
                                    <td><span class="link"><i class="fas fa-eye"></i> View</span></td>
                                </tr>
                            </table>
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