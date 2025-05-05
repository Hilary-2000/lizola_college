<div class="contents animate hide" id="record_other_schools_income">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Record Other School Income</p>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="row">
                <div class="notice1 col-md-12">
                    <button type='button' id='back_to_fees_payment_2'><i class="fa fa-arrow-left"></i> Back to Fees Payment</button>
                    <div class="notify">
                        <p><strong>Important:</strong></p>
                    </div>
                    <ul>
                        <li> VIEW, UPDATE AND DELETE THE SCHOOL OTHER SOURCES OF INCOME FROM THIS SECTION.</li>
                    </ul>
                </div>
            </div>
            <div class="container" id="show_revenue_list">
                <button class="nav-bar-btns" id="add-revenue-btn"><i class="fa fa-plus"></i> Add Revenue <img class="hide" src="images/ajax_clock_small.gif" id="show_revenue_loader"></button>
                <div id="error_handler_general_revenue"></div>
                <div class="hide" id="show_revenue_values"></div>
                <div class="table_container">
                    <input type="hidden" name="" value="1" id="page_value_income">
                    <input type="hidden" name="" value="1" id="maximum_page_income">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <input type="text" class="text form-control w-100" id="search_school_revenue" placeholder="Search Keyword Here">
                        </div>
                    </div>
                    <div id="revenue_data">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Date Recorded.</th>
                                    <th>Customer Name</th>
                                    <th>Customer Contact</th>
                                    <th>Contact Person</th>
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
                                    <td>0743551250</td>
                                    <td>James</td>
                                    <td><span style="font-size:12px;" class="link view_students" id="view1"><i class="fa fa-pen-fancy"></i> Edit </span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row w-50">
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="previous_income_data"><i class="fa fa-arrow-left"></i><i class="fa fa-arrow-left"></i></span>
                        </div>
                        <div class="col-sm-4">
                            <span id="page_number" class="text-center">Page 1 of 2</span>
                        </div>
                        <div class="col-sm-4">
                            <span class="btn btn-secondary btn-sm disabled" id="next_income_data"><i class="fa fa-arrow-right"></i><i class="fa fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container hide" id="add_revenues">
                <button id="return_to_revenue_list"><i class="fa fa-arrow-left"></i> Back to revenue </button>
                <div class="row my-2">
                    <div class="col-md-12 my-2"><h4 class="text-center"><u>Add Revenue</u></h4></div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_name" class="form-label"><b>Name</b></label>
                        <input type="text" name="revenue_name" id="revenue_name" class="form-control w-100" placeholder="Revenue Name e.g, Swimming Pool Hire">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_amount" class="form-label"><b>Amount</b></label>
                        <input type="number" name="revenue_amount" id="revenue_amount" class="form-control w-100" placeholder="e.g, 1000">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="mode_of_revenue_payment" class="form-control-label"><b>Mode of Payment</b></label>
                        <select name="mode_of_revenue_payment" id="mode_of_revenue_payment" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option value="1">M-Pesa</option>
                            <option value="2">Cash</option>
                            <option value="3">Bank</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="payment_code" class="form-control-label"><b>Payment Code</b></label>
                        <input type="text" class="form-control w-100" id="payment_code" placeholder="eg. KJKJHKJ (Optional)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_cash_activity" class="form-control-label"><b>Revenue Cashflow Activity</b></label>
                        <select name="revenue_cash_activity" id="revenue_cash_activity" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option value="1">Operating Activities</option>
                            <option value="2">Investing Activities</option>
                            <option value="3">Financing Activities</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_category" class="form-control-label"><b>Revenue Categories</b> <img class="hide" src="images/ajax_clock_small.gif" id="revenue_categories_loader"></label>
                        <div id="revenue_categories_list">
                            <p class="text-danger">Revenue categories will appear here!</p>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="add_revenue_sub_category" class="form-control-label"><b>Revenue Sub-Categories</b> <img class="hide" src="images/ajax_clock_small.gif" id="revenue_sub_categories_loaders"></label>
                        <div id="revenue_sub_categories_list"><p class="text-danger">Select revenue to display revenue sub-categories!</p></div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_date" class="form-label"><b>Date</b></label>
                        <input type="date" name="revenue_date" id="revenue_date" class="form-control w-100" value="<?= date("Y-m-d");?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="customer_name" class="form-label"><b>Customer Name</b></label>
                        <input type="text" name="customer_name"  id="customer_name" class="form-control w-100" placeholder="Customer Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="customer_contacts_revenue" class="form-label"><b>Customer Contacts</b></label>
                        <input type="text" name="customer_contacts_revenue" id="customer_contacts_revenue" class="form-control w-100" placeholder="Customer Contacts - (Optional)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="contact_person" class="form-label"><b>Contact Person</b></label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control w-100" placeholder="Contact Person - (Optional)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="reportable_status" class="form-control-label"><b>Reportable Status</b></label>
                        <select name="reportable_status" id="reportable_status" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option selected value="1">Reportable Revenue</option>
                            <option value="0">Non-Reportable Revenue</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="revenue_description" class="form-label"><b>Revenue Description</b></label>
                        <textarea name="revenue_description" id="revenue_description" cols="30" rows="5" class="form-control w-100" placeholder="Write a brief description of the revenue here!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="error_handler_revenue_collection"></div>
                        <button id="save_revenue"><i class="fa fa-save"></i> Save Revenue <img class="hide" src="images/ajax_clock_small.gif" id="save_revenue_loader"></button>
                    </div>
                </div>
            </div>
            <div class="container hide" id="edit_revenues">
                <button id="return_to_revenue_list_edit"><i class="fa fa-arrow-left"></i> Back to revenue</button>
                <div class="row my-2">
                    <div class="col-md-12 my-2"><h4 class="text-center"><u>Edit Revenue</u></h4></div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_name_edit" class="form-label"><b>Name</b></label>
                        <input type="hidden" name="" id="revenue_ids">
                        <input type="text" name="revenue_name_edit" id="revenue_name_edit" class="form-control w-100" placeholder="Revenue Name e.g, Swimming Pool Hire">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_amount_edit" class="form-label"><b>Amount</b></label>
                        <input type="number" name="revenue_amount_edit" id="revenue_amount_edit" class="form-control w-100" placeholder="e.g, 1000">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="mode_of_revenue_payment_edit" class="form-control-label"><b>Mode of Payment</b></label>
                        <select name="mode_of_revenue_payment_edit" id="mode_of_revenue_payment_edit" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option value="1">M-Pesa</option>
                            <option value="2">Cash</option>
                            <option value="3">Bank</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="payment_code_edit" class="form-control-label"><b>Payment Code</b></label>
                        <input type="text" class="form-control W-100" id="payment_code_edit" placeholder="eg, JGHGFHG">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="edit_revenue_cash_activity" class="form-control-label"><b>Revenue Cashflow Activity</b></label>
                        <select name="edit_revenue_cash_activity" id="edit_revenue_cash_activity" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option value="1">Operating Activities</option>
                            <option value="2">Investing Activities</option>
                            <option value="3">Financing Activities</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="edit_revenue_category" class="form-control-label"><b>Revenue Categories</b> <img class="hide" src="images/ajax_clock_small.gif" id="edit_revenue_categories_loader"></label>
                        <div id="edit_revenue_category_holder"></div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="edit_revenue_sub_category" class="form-control-label"><b>Revenue sub-categories</b> <img class="hide" src="images/ajax_clock_small.gif" id="edit_revenue_sub_categories_loader"></label>
                        <div id="edit_revenue_sub_categories"></div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="revenue_date_edit" class="form-label"><b>Date</b></label>
                        <input type="date" name="revenue_date_edit" id="revenue_date_edit" class="form-control w-100" value="<?= date("Y-m-d");?>">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="customer_name_edit" class="form-label"><b>Customer Name</b></label>
                        <input type="text" name="customer_name_edit" id="customer_name_edit" class="form-control w-100" placeholder="Customer Name">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="customer_contacts_revenue_edit" class="form-label"><b>Customer Contacts</b></label>
                        <input type="text" name="customer_contacts_revenue_edit" id="customer_contacts_revenue_edit" class="form-control w-100" placeholder="Customer Contacts - (Optional)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="contact_person_edit" class="form-label"><b>Contact Person</b></label>
                        <input type="text" name="contact_person_edit" id="contact_person_edit" class="form-control w-100" placeholder="Contact Person - (Optional)">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="reportable_status_edit" class="form-control-label"><b>Reportable Status</b></label>
                        <select name="reportable_status_edit" id="reportable_status_edit" class="form-control w-100">
                            <option value="" hidden>Select Option</option>
                            <option selected value="1">Reportable Revenue</option>
                            <option value="0">Non-Reportable Revenue</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="revenue_description_edit" class="form-label"><b>Revenue Description</b></label>
                        <textarea name="revenue_description_edit" id="revenue_description_edit" cols="30" rows="5" class="form-control w-100" placeholder="Write a brief description of the revenue here!"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div id="error_handler_revenue_collection_edit"></div>
                        <button id="save_revenue_edit"><i class="fa fa-save"></i> Update Revenue <img class="hide" src="images/ajax_clock_small.gif" id="update_revenue_loader"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>