<div class="contents animate hide" id="payrolled_win">
    <div class="titled">
        <h2>Payroll System</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Payroll System</p>
                </div>
                <div class="col-md-3">
                    <span id="payroll_sys_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- Pay your staff using the payroll system.</p>
                <p>- Select different option to accomplish your task.</p>
            </div>
            <div class="conts">
                <div class="conts border border-primary p-1 my-1">
                    <p class="block_btn" id="enroll_staff_btn"><i class=" fa fa-plus"></i> Enroll staff</p>
                    <p class="block_btn" id="see_enrolled"><i class=" fa fa-eye"></i> View enrolled staff</p>
                    <p class="block_btn" id="advance_pay_view"><i class=" fa fa-cog"></i> Manage Advances</p>
                    <p class="block_btn" id="kra_reports"><i class=" fa fa-flag"></i> KRA reports</p>
                    <p class="block_btn" id="nssf_reports"><i class=" fa fa-flag"></i> NSSF reports</p>
                    <p class="block_btn" id="nhif_reports"><i class=" fa fa-flag"></i> NHIF reports</p>
                    <p class="block_btn" id="nita_reports"><i class=" fa fa-flag"></i> NITA reports</p>
                </div>
                <div class="staff_information">
                    <!-- enroll staff -->
                    <form class="enroll_staf hide row" id="payroll_enroll">
                        <h6 class="text-center"><strong><u>Enroll staff</u></strong></h6>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="information_window">
                                    <h6 id="head_infor">Who am I</h6>
                                    <p id="para_infor">Click <span class="more_infor">Info</span> to see more information about the field.</p>
                                </div>
                                <div class="conts">
                                    <h6 class="text-center"><strong>Staff Details</strong></h6>
                                </div>
                                <div class="conts">
                                    <label class="form-control-label" for="staff_l">Select staff to enroll <span class="more_infor" title="Click to show more information about the field" id="staff_en">Info</span><br></label>
                                    <p id="staff_li"></p>
                                    <!-- <select name="staff_l" id="staff_l">
                                        <option value="" hidden>Select option</option>
                                    </select> -->
                                </div>
                                <div class="conts">
                                    <label class="form-control-label" for="amount_to_pay">Enter salary amount: <span class="text-danger">(Readonly)</span> <span class="more_infor" title="Click to show more information about the field" id="staff_salo">Info</span><br></label>
                                    <input class="form-control bg-secondary text-white" type="number" readonly name="amount_to_pay" id="amount_to_pay" placeholder="Salary amount" value="1" min="1">
                                </div>
                                <div class="conts">
                                    <label class="form-control-label" for="effect_from">Current month: <span class="more_infor" title="Click to show more information about the field" id="staff_currMon">Info</span><br></label>
                                    <select class="form-control" name="effect_from" id="effect_from">
                                        <option value="" hidden>Select Month</option>
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
                                    <p id="tell_pay"></p>
                                </div>
                                <div class="conts">
                                    <label class="form-control-label" for="effect_year">Current Year <span class="more_infor" title="Click to show more information about the field" id="staff_currYear">Info</span><br></label>
                                    <select class="form-control" name="effect_year" id="effect_year">
                                        <option value="" hidden>Select Year</option>
                                        <?php
                                        $year = date("Y");
                                        for ($count = $year; $count > 2020; $count--) {
                                            echo "<option value='$count'>$count</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="conts">
                                    <label class="form-control-label" for="balances">Balance: <span class="more_infor" title="Click to show more information about the field" id="staff_accruedbal">Info</span><br></label>
                                    <input class="form-control" type="number" name="balances" id="balances" placeholder="Balances" value="1" min="1">
                                </div>
                            </div>
                            <div class=" col-lg-8 my-2" style="border-left: 1px solid black;border-right: 1px solid black;">
                                <h6 class="text-center"><u>Net Pay Calculator (latest effective Jan 1st 2022)</u></h6>
                                <!-- get the payments and deductions for the client -->
                                <div class="row ">
                                    <div class="col-md-6 h-100" style="border-right: 1px solid gray;">
                                        <h6 class="text-center"><strong><u>Net Salary Calculator</u></strong></h6>
                                        <label for="paye_effect_year" class="form-control-label"><strong>Year of P.A.Y.E Rates effect</strong></label>
                                        <select class="form-control" name="paye_effect_year" id="paye_effect_year">
                                            <option value="" hidden>Select Year</option>
                                            <?php
                                            $year = date("Y");
                                            for ($count = $year; $count > 2020; $count--) {
                                                $selected = "";
                                                if ($count == $year) {
                                                    $selected = "selected";
                                                }
                                                echo "<option " . $selected . " value='$count'>$count</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="gross_salary" class="form-control-label"><strong>Gross Salary</strong></label>
                                        <input type="number" name="gross_salary" id="gross_salary" value="100" class="form-control" placeholder="Gross salary">
                                        <hr>
                                        <p class="hide" id="allowance_holder"></p>
                                        <p><strong>Allowance and Bonuses</strong> <span class="link" id="add_allowances_in"><i class="fa fa-plus"></i> Click to add allowances</span></p>
                                        <div id="allowances_and_bonuses">
                                            <p class='text-success border border-success p-1 my-1'>No allowances to display at the moment.</p>
                                        </div>
                                        <hr>
                                        <p class="hide" id="deductions_holder_1"></p>
                                        <p><strong>Deductions</strong> <span class="link" id="add_deductions_1"><i class="fa fa-plus"></i> Click to add deductions</span></p>
                                        <div id="deductions_windoww_1">
                                            <p class='text-success border border-success p-1 my-1'>No deductions to display at the moment.</p>
                                        </div>
                                        <hr>
                                        <p> <strong>Reliefs</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="personal_relief"><i>- Personal Relief:</i></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="checkbox" name="personal_relief" id="personal_relief">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="NHIF_relief"><i>- NHIF Relief:</i></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="checkbox" name="NHIF_relief" id="NHIF_relief">
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>NSSF Rates</strong></p>
                                        <label class="form-control-label" for="nssf_rates"><i>- NSSF Rates:</i></label>
                                        <select name="nssf_rates" id="nssf_rates" class="form-control">
                                            <option value="" hidden>Select Rates</option>
                                            <option value="teir_1">Teir 1 (Ksh 360)</option>
                                            <option selected value="teir_1_2">Teir 1 & 2 (Ksh 1080)</option>
                                            <option value="teir_old">Old Rates (Ksh 200)</option>
                                            <option value="none">None</option>
                                        </select>
                                        <hr>
                                        <p class="mt-1"><strong>P.A.Y.E</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="deduct_paye"><i>- Deduct P.A.Y.E</i></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="checkbox" name="deduct_paye" id="deduct_paye">
                                            </div>
                                        </div>
                                        <hr>
                                        <p class="mt-1"><strong>NHIF</strong></p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="deduct_NHIF"><i>- Deduct NHIF</i></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="checkbox" name="deduct_NHIF" id="deduct_NHIF">
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-center"><strong><u>Calculation breakdown</u></strong></h6>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p><strong><i>- Gross Salary</i></strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong id="gros_salo_rec">Ksh 0</strong></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Contributions</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p><i>- NSSF Contribution</i></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><i id="nssf_contributes">Ksh 0</i></p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p><i>- NHIF Contribution</i></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><i id="nhif_contributions_records">Ksh 0</i></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Income after Contribution</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- Income after NSSF Contribution</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="income_after_nssf_contribute">Ksh 0</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Allowances</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- Total Allowances</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="all_allowances">Ksh 0</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Taxable Income</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- Taxable Income</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="taxable_income_records">Ksh 0</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Tax / P.A.Y.E</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p><i>- Income Tax</i></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><i id="incomeTaxRecord">Ksh 0</i></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Reliefs</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- Personal Relief</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="personal_relief_records">Ksh 0</p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- NHIF Relief</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="nhif_relief_record">Ksh 0</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Tax after reliefs</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p><i>- Final Income Tax</i></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><i id="final_income_taxe">Ksh 0</i></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Deductions</strong></p>
                                        <div class="row ">
                                            <div class="col-md-8">
                                                <p>- Total Deductions</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p id="deductions_calculate">Ksh 0</p>
                                            </div>
                                        </div>
                                        <div class="row border-top border-bottom border-dark p-2 mt-4">
                                            <div class="col-md-8">
                                                <p><strong><i>- Net Salary</i></strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong id="net_salary_record">Ksh 0</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="conts">
                            <p id="enroll_err_handler"></p>
                            <button type='button' id="enrol_staf_btn"><i class="fa fa-save"></i> Enroll</button>
                        </div>
                    </form>
                    <!-- view staff enrolled -->
                    <div class="enroll_staf hide" id="viewEnrolledPay">
                        <div class="conts">
                            <h6 style="text-align:center;">Enrolled Staff</h6>
                        </div>
                        <div class="conts">
                            <div class="table_holders">
                                <p id="my_enrolled_staff"></p>
                            </div>
                            <!-- <table>
                                <tr>
                                    <th>No.</th>
                                    <th>Staff Name</th>
                                    <th>Balance</th>
                                    <th>Last Paid</th>
                                    <th>Salary Amount</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>1. </td>
                                    <td>James Corden</td>
                                    <td>Kes 5,000</td>
                                    <td>Jun 2021</td>
                                    <td>Kes 25,000</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                </tr>
                                <tr>
                                    <td>1. </td>
                                    <td>James Corden</td>
                                    <td>Kes 5,000</td>
                                    <td>Jun 2021</td>
                                    <td>Kes 25,000</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                </tr>
                                <tr>
                                    <td>1. </td>
                                    <td>James Corden</td>
                                    <td>Kes 5,000</td>
                                    <td>Jun 2021</td>
                                    <td>Kes 25,000</td>
                                    <td class="link" style="font-size:12px;"><p><i class="fa fa-pen"></i> Edit</p></td>
                                </tr>
                            </table> -->
                        </div>
                    </div>
                    <div class="body3 hide" id="pay_salary_staff">
                        <div class="left">
                            <div class="conts">
                                <h6 style='text-align:center;margin-bottom:10px;'>Pay Staff</h6>
                                <button type="button" id="refresh_paydets">Refresh</button>
                            </div>
                            <div class="conts border border-primary p-2 rounded my-2">
                                <p>Last paid : <span class="color_brown" id="last_paid_time">unknown</span><br> Latest Month: <span class="color_brown"><?php echo date("M-Y", strtotime("-1 month")); ?></span><br> Balance: <span class="color_brown" id="salary_balances">unknown</span><br></p>
                                <p>Monthly salary: <span class="color_brown" id="monthly_salo">unknown</span><br>Total Balance: <span class="color_brown" id="tot_bal">unknown</span> <br></p>
                                <p for="staff_name">Staff id: <span class="color_brown" id="stf_ids_pay">unknown</span><br></p>
                            </div>
                            <div class="conts">
                                <label for="staff_name" class="form-control-label">Staff name:</label>
                                <input type="text" class="form-control " name="staff_name" id="staff_name" placeholder="Staff name" readonly>
                                <label class='form-control-label' for="pay_mode">Payment Mode: <br></label>
                                <select class='form-control ' name="pay_mode" id="pay_mode">
                                    <option value="" id="def_opt" hidden>Select an option</option>
                                    <option value="m-pesa">M-pesa</option>
                                    <option value="bank">Bank</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>
                            <div class="conts hide" id="mpesa_salary">
                                <label class='form-control-label' for="mpesa_code">M-pesa code: <br></label>
                                <input type="text" class='form-control' name="mpesa_code" id="mpesa_code" placeholder="Mpesa code">
                            </div>
                            <div class="conts hide" id="banks_sal">
                                <label class='form-control-label' for="bank_code">Bank code: <br></label>
                                <input type="text" class='form-control' name="bank_code" id="bank_code" placeholder="Bank code">
                            </div>
                            <div class="conts hide" id="amount_sal">
                                <label class='form-control-label' for="amount_salary">Salary Amount <br></label>
                                <input type="number" class='form-control' name="amount_salary" id="amount_salary" placeholder="Salary Amount">
                            </div>
                            <!-- <div class="conts">
                                <label for="salary_type" class="form-control-label">Payment Type</label>
                                <select name="salary_type" id="salary_type" class="form-control">
                                    <option value="" hidden>Select Salary Type</option>
                                    <option value="salary" >Salary Pay</option>
                                    <option value="advance" >Advance Pay</option>
                                </select>
                            </div> -->
                            <div class="conts hide" id="sal_pay_btns">
                                <button type="button" id="salary_pays_btns">Process Payment</button>
                                <p id="err_handler_in"></p>
                            </div>
                            <div class="conts my-2">
                                <p class="link" style="font-size:12px;text-align:left;" id="back_to_payroll">
                                    << Back</p>
                            </div>
                        </div>
                    </div>
                    <div class="body5 p-0 hide" id="salary_infor">
                        <div class="conts my-2">
                            <h6 style='text-align:center;'>Edit Information</h6>
                            <p class="hide" id="salary_infor_br"></p>
                        </div>
                        <div class="conts">
                            <p class="link" style="font-size:12px;text-align:left;" id="back_to_payrolls12">
                                << Back</p>
                        </div>
                        <div class="left row m-0">
                            <div class="col-md-4 m-0">
                                <div class="conts">
                                    <label class='form-control-label'>Staff id: <span id="stf_id_sal">10</span><br> Staff Name: <br></label>
                                    <input type="text" class='form-control' id="staff_name_ids_sal" placeholder="Staff name" readonly>
                                </div>
                                <div class="conts">
                                    <label class='form-control-label' for="change_salary">Raise or reduce salary <br> {<span id="old_salary"></span>} </label>
                                    <input type="number" readonly class='form-control' name="change_salary" id="change_salary" placeholder="Salary" min=0>
                                </div>
                                <p class="hide" id="old_salo"></p>
                                <div class="conts">
                                    <p id="err_handler_F"></p>
                                    <button type="button" id="changes_salary_btn">Update</button>
                                    <button type="button" id="unenroll_staff_salary">Un-enroll</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                        <h6 class="text-center"><b>Net Pay Calculator</b></h6>
                                        <p id="error_calaculator"></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="gross_salary_edit" class="form-control-label">Gross salary { <span id="gross_sa"></span>}</label>
                                        <input type="number" value="100" class="form-control" id="gross_salary_edit" placeholder="Gross salary">
                                        <label for="year_of_effect_paye" class="form-control-label">Year of P.A.Y.E Rates</label>
                                        <select name="year_of_effect_paye" id="year_of_effect_paye" class="form-control">
                                            <option value="" hidden>Select year of effect</option>
                                            <?php
                                            $year = date("Y");
                                            for ($count = $year; $count > 2020; $count--) {
                                                $selected = "";
                                                if ($count == $year) {
                                                    $selected = "selected";
                                                }
                                                echo "<option id='yr_".$count."' " . $selected . " value='$count'>$count</option>";
                                            }
                                            ?>
                                        </select>
                                        <!-- nssf rates -->
                                        <label class="form-control-label" for="nssf_rates_edit"><i>- NSSF Rates:</i></label>
                                        <select name="nssf_rates_edit" id="nssf_rates_edit" class="form-control">
                                            <option value="" hidden>Select Rates</option>
                                            <option id="teir_1" value="teir_1">Teir 1 (Ksh 360)</option>
                                            <option id="teir_1_2" value="teir_1_2">Teir 1 & 2 (Ksh 1080)</option>
                                            <option id="teir_old" value="teir_old">Old Rates (Ksh 200)</option>
                                            <option id="none" value="none">None</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Deductions.</strong></p>
                                        <!-- nhif deduct -->
                                        <label for="dedcut_paye_edit" class="form-control-label">- Deduct P.A.Y.E</label>
                                        <input type="checkbox" name="dedcut_paye_edit" id="dedcut_paye_edit"><br>
                                        <!-- nhif deduct -->
                                        <label for="dedcut_nhif_edit" class="form-control-label">- Deduct NHIF</label>
                                        <input type="checkbox" name="dedcut_nhif_edit" id="dedcut_nhif_edit"><br>
                                        <hr>
                                        <p><strong>Reliefs.</strong></p>
                                        <!-- nhif relief -->
                                        <label for="nhif_relief_accept" class="form-control-label">- NHIF Relief</label>
                                        <input type="checkbox" name="nhif_relief_accept" id="nhif_relief_accept"><br>
                                        <!-- personal relief -->
                                        <label for="personal_relief_accept" class="form-control-label">- Personal Relief</label>
                                        <input type="checkbox" name="personal_relief_accept" id="personal_relief_accept">
                                        <hr>
                                        <p class="hide" id="allowance_holder_edit"></p>
                                        <p><strong>Allowances</strong> <span class="link" id="edit_allowances"><i class="fa fa-plus"></i> Add Allowances</span> </p>
                                        <div id="allowance_html">
                                            <p class='text-success'>No allowances to display at the moment.</p>
                                            <!-- <div class='row'>
                                                <div class='col-md-6'><label for=''>1. House Allowance</label></div>
                                                <div class='col-md-3'>
                                                    <p>Kes 1,000<span id='value_holder' class='hide value_holder'>1000</span></p>
                                                </div>
                                                <div class='col-md-3'>
                                                    <input type='checkbox' checked class='accept_allowance' id='accept_allowance'>
                                                    <span class='funga removed_allowance mx-1' style='font-size: 15px;cursor: pointer;' id='removed_allowance'>&times</span>
                                                </div>
                                            </div> -->
                                        </div>
                                        <hr>
                                        <p class="hide" id="deductions_holder"></p>
                                        <p><strong>Deductions</strong> <span class="link" id="add_deductions"><i class="fa fa-plus"></i> Add Deductions</span> </p>
                                        <div id="deduction_windows">
                                            <p class='text-success'>No deductions to display at the moment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- display the table that breaks down the payment -->
                        <div class="container my-2">
                            <h6 class="text-center"><strong>Calculation breakdown</strong></h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong><i>- Gross Salary</i></strong></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong id="gros_salo_rec_edit">Ksh 0</strong></p>
                                </div>
                            </div>
                            <hr>
                            <p><strong>Contributions</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p><i>- NSSF Contribution</i></p>
                                </div>
                                <div class="col-md-4">
                                    <p><i id="nssf_contributes_edit">Ksh 0</i></p>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p><i>- NHIF Contribution</i></p>
                                </div>
                                <div class="col-md-4">
                                    <p><i id="nhif_contributions_records_edit">Ksh 0</i></p>
                                </div>
                            </div>
                            <p><strong>Income after Contribution</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- Income after NSSF Contribution</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="income_after_nssf_contribute_edit">Ksh 0</p>
                                </div>
                            </div>
                            <p><strong>Allowances</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- Total Allowances</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="all_allowances_edit">Ksh 0</p>
                                </div>
                            </div>
                            <p><strong>Taxable Income</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- Taxable Income</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="taxable_income_records_edit">Ksh 0</p>
                                </div>
                            </div>
                            <p><strong>Tax / P.A.Y.E</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p><i>- Income Tax</i></p>
                                </div>
                                <div class="col-md-4">
                                    <p><i id="incomeTaxRecord_edit">Ksh 0</i></p>
                                </div>
                            </div>
                            <p><strong>Reliefs</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- Personal Relief</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="personal_relief_records_edit">Ksh 0</p>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- NHIF Relief</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="nhif_relief_record_edit">Ksh 0</p>
                                </div>
                            </div>
                            <p><strong>Tax after relief</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p><i>- Final Income Tax</i></p>
                                </div>
                                <div class="col-md-4">
                                    <p><i id="final_income_taxe_edit">Ksh 0</i></p>
                                </div>
                            </div>
                            <p><strong>Deductions</strong></p>
                            <div class="row ">
                                <div class="col-md-8">
                                    <p>- Total Deductions</p>
                                </div>
                                <div class="col-md-4">
                                    <p id="all_deductions_edit">Ksh 0</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <p><strong><i>- Net Salary</i></strong></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong id="net_salary_record_edit">Ksh 0</strong></p>
                                </div>
                            </div>
                        </div>
                        <!-- end of the breakdown of the payment -->
                    </div>
                    <div class="body5 hide" id="view_payment_history">
                        <div class="left">
                            <div class="conts">
                                <h6>Payment History
                                    <select class='form-control' id="sel_yrs">
                                        <option value="" hidden>Select year</option>
                                        <?php
                                        $year = date("Y",strtotime("1 year"));
                                        for ($count = $year; $count > 2018; $count--) {
                                            echo "<option value='$count'>$count</option>";
                                        }
                                        ?>
                                    </select>
                                </h6>
                                <input type="hidden" id="userPayId">
                            </div>
                            <div class="conts">
                                <p class="link" style="font-size:12px;text-align:left;" id="back2_to_payroll123">
                                    << Back</p>
                            </div>
                            <div class="conts" style="margin:10px 0;" id="getmysalohistory">
                                <div class="conts">
                                    <p class="embold">Staff name: <span class="color_brown">Mr James St Patrick</span></p>
                                    <p class="embold">Year : <span class="color_brown">2020</span></p>
                                    <p class="embold">Total salary : <span class="color_brown">Kes 32,000</span></p>
                                </div>
                                <div class="my_salo-flexbox">

                                </div>
                                <!-- <div class="my_salo-flexbox">
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000<span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                    <div class="year_card">
                                        <div class="margin-bottom-5px width_100per bordered_bottom">
                                            <p class="embold">Month: <span class="color_brown">January</span></p>
                                        </div>
                                        <div class="salary-amount">
                                            <p class="embold">Salary : <span class="color_brown"> Kes 20,000</span></p>
                                        </div>
                                        <div class="payments-details">
                                            <p>- 5,000 <span class="green_notice">-c</span> (01-Jan-2021) (13:09:01)</p>
                                            <p>- 2,000 <span class="green_notice">-m</span> (13-Jan-2021) (13:09:01)</p>
                                            <p>- 7,000 <span class="green_notice">-b</span> (15-Jan-2021) (13:09:01)</p>
                                        </div>
                                        <div class="total_payments">
                                            <p class="embold">Total paid: <span class="color_brown"> Kes 14,000</span></p>
                                            <p class="embold">Balance : <span class="color_brown">Kes 6,000</span></p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="conts">
                                <p class="embold">Hint:</p>
                                <p>Cash payment : <span class="green_notice">-c</span></p>
                                <p>Bank payment : <span class="green_notice">-b</span></p>
                                <p>Mpesa payment: <span class="green_notice">-m</span></p>
                            </div>
                            <div class="conts">
                                <p class="link" style="font-size:12px;text-align:left;" id="back_to_payroll123">
                                    << Back</p>
                            </div>
                        </div>
                    </div>
                    <div class="container border border-secondary rounded p-2 hide" id="advance_management">
                        <div class="container advances" id="view_all_advances_window">
                            <h6 class="text-center"><u>Advances Registered</u> <img class="hide" src="images/ajax_clock_small.gif" id="advance_registers_loaders"></h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="search_advances" id="search_advances" class="form-control border border-gray rounded p-2 text-xs font-weight-bold w-100" style="width:fit-content;" placeholder="Enter keyword to search table...">
                                </div>
                                <div class="col-md-6">
                                    <p class="block_btn" id="define_advance_pay"><i class="fas fa-plus"></i> Add Advance</p>
                                </div>
                            </div>
                            <p class="hide" id="data_advances_holder"></p>
                            <div class="table-responsive p-0" id="transDataReciever_advances">
                                <table class="table">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">#</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Employees Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Date Issued</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ps-2">Installments</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2">Status<br></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center ps-2">Action<br></th>
                                        <!-- <th></th>
                                        <th></th> -->
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="d-flex px-2 align-content-center">
                                                <div class="my-auto">
                                                    <span class="mb-0"> <strong class="text-center">Owen Malingu</strong></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="">1,000</p>
                                        </td>
                                        <td>
                                            <span class="">24th January 2022</span>
                                        </td>
                                        <td>
                                            <span class="">3 time(s)</span>
                                        </td>
                                        <td class="">
                                            <span class="">Cleared</span>
                                        </td>
                                        <td class="">
                                            <span class="link "><i class="fas fa-eye"></i> View</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="d-flex px-2 align-content-center">
                                                <div class="my-auto">
                                                    <span class="mb-0"> <strong class="text-uppercase text-dark font-weight-bolder text-center">Esmond Bwire</strong></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">1,000</p>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">24th January 2022</span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">3 time(s)</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badges badges-success">In-progress</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="link "><i class="fas fa-eye"></i> View</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row mt-5" id="tablefooter_advances">
                                <div class="col-sm-12 col-md-5">
                                    <div class="container-fluid">
                                        <p class="text-xxs font-weight-bolder opacity-9 text-uppercase">Showing <span class="text-primary" id="startNo_advances">1 </span> to <span class="text-primary" id="finishNo_advances">10</span> of <span id="tot_records_advances"></span> Records.</p>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_full_numbers" id="datatable_paginate_advances">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item first" id="datatable_first_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="0" tabindex="0" class="page-link" id="tofirstNav_advances">First</a></li>
                                            <li class="paginate_button page-item previous mx-1" id="datatable_previous_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="toprevNac_advances">Prev</a></li>
                                            <li class="paginate_button page-item previous active mx-3" id="datatable_previous_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="1" tabindex="0" class="page-link" id="pagenumNav_advances">1</a></li>
                                            <li class="paginate_button page-item next mx-1" id="datatable_next_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="7" tabindex="0" class="page-link" id="tonextNav_advances">Next</a></li>
                                            <li class="paginate_button page-item last mx-1" id="datatable_last_advances"><a href="javascript:;" aria-controls="datatable" data-dt-idx="8" tabindex="0" class="page-link" id="tolastNav_advances">Last</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="container advances hide" id="define_advance_window">
                            <h6 class="text-center"><u>Define New Advance</u></h6>
                            <p class="link" style="width: fit-content;" id="back_to_advance_list"><i class="fas fa-arrow-left"></i> Back to list</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="employees_id_advances" class="form-control-label">Employee`s Name <img class="hide" src="images/ajax_clock_small.gif" id="employees_data_loaders"></label>
                                    <p id="employees_data"></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="advance_amount" class="form-control-label">Advance Amount</label>
                                    <input type="number" class="w-100 form-control border border-gray rounded p-2 text-xs font-weight-bold" id="advance_amount" placeholder="Advance Amounts">
                                </div>
                                <div class="col-md-6">
                                    <label for="month_effects" class="form-control-label">Effect Month</label>
                                    <input min="<?php echo date("Y-m");?>" class="w-100 form-control border border-gray rounded p-2 text-xs font-weight-bold" type="month" name="month_effects" id="month_effects" placeholder="Effect Month">
                                </div>
                                <div class="col-md-6">
                                    <label for="advance_installments" class="form-control-label">Advance Installments</label>
                                    <input type="number" class="w-100 form-control border border-gray rounded p-2 text-xs font-weight-bold" id="advance_installments" value="1" min="1" placeholder="Advance Installments">
                                    <p id="advance_installments_price"></p>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <p class="block_btn" id="define_advances"><i class="fas fa-plus"></i> Add Advance <img class="hide" src="images/ajax_clock_small.gif" id="add_advance_loadings"></p>
                                    <p id="add_leave_error_handler"></p>
                                </div>
                            </div>
                        </div>
                        <div class="container advances hide" id="view_advance_window">
                            <h6 class="text-center"><u>View Advance Details</u> <small class="text-danger">(Readonly)</small></h6>
                            <p class="link" style="width: fit-content;" id="back_to_view_advance_list"><i class="fas fa-arrow-left"></i> Back to list</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="form-control-label">Employee`s Name</label>
                                    <input type="text" readonly readonly class="form-control w-100" id="employees_name_view" placeholder="Advance Amounts">
                                </div>
                                <div class="col-md-6">
                                    <label for="advance_amount_view" class="form-control-label">Advance Amount</label>
                                    <input type="text" readonly class="form-control w-100" id="advance_amount_view" placeholder="Advance Amounts">
                                </div>
                                <div class="col-md-6">
                                    <label for="month_effects_view" class="form-control-label">Effect Month</label>
                                    <input readonly class="form-control w-100 text-primary bg-white" type="month" name="month_effects_view" id="month_effects_view" placeholder="Effect Month">
                                </div>
                                <div class="col-md-6">
                                    <label for="advance_installments_view" class="form-control-label">Advance Installments</label>
                                    <input type="text" readonly class="form-control w-100" id="advance_installments_view" value="1" min="1" placeholder="Advance Installments">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-control-label">Date Taken</label>
                                    <input type="text" readonly class="form-control w-100" id="advance_date_taken" placeholder="Advance Amounts">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-control-label">Advance Balance</label>
                                    <input type="text" readonly class="form-control w-100" id="advance_balance" placeholder="Advance Amounts">
                                </div>
                                <div class="col-md-12 my-2">
                                    <h6 class="text-center"><u>Payment Installments</u></h6>
                                    <p id="payment_installments_advanced"></p>
                                    <!-- <table class="table">
                                        <tr>
                                            <th>#</th>
                                            <th>Pay Amount</th>
                                            <th>Pay Date</th>
                                            <th>Pay For</th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Kes 1000</td>
                                            <td>202201010101</td>
                                            <td>Jan:2022</td>
                                        </tr>
                                    </table> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container border border-secondary rounded p-2 hide" id="payroll_reports_window">
                        <div class="my_reports hide" id="nssf_reports_window">
                            <h5 class="text-center"><u>N.S.S.F reports</u></h5>
                            <div class="container row">
                                <div class="col-md-6">
                                    <label for="select_nssf_months" class="form-control-label">Select Month</label>
                                    <input type="month" name="select_nssf_months" id="select_nssf_months" class="form-control" value="<?php echo date('Y-m');?>">
                                </div>
                                <div class="col-md-6">
                                    <p class="block_btn" id="display_nssf_reports"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_nssf_reports_loader"></p>
                                </div>
                            </div>
                            <div class="container table_holders" id="display_nssf_reports_windows">
                                <table class="table">
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Id No.</th>
                                        <th>NSSF No.</th>
                                        <th>NSSF Category</th>
                                        <th>NSSF Payments</th>
                                        <th>Employer Amount</th>
                                        <th>Employees Amount</th>
                                        <th>Total</th>
                                    </tr>
                                    <tr>
                                        <td>1. </td>
                                        <td>Hillary Ngige</td>
                                        <td>37367344</td>
                                        <td>37367344</td>
                                        <td>Teri 1 & 2</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 1080</td>
                                        <td>Kes 1080</td>
                                        <td>Kes 2160</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="my_reports hide" id="nhif_reports_window">
                            <h5 class="text-center"><u>N.H.I.F reports</u></h5>
                            <div class="container row">
                                <div class="col-md-6">
                                    <label for="select_nhif_months" class="form-control-label">Select Month</label>
                                    <input type="month" name="select_nhif_months" id="select_nhif_months" class="form-control" value="<?php echo date('Y-m');?>">
                                </div>
                                <div class="col-md-6">
                                    <p class="block_btn" id="display_nhif_reports"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_nhif_reports_loader"></p>
                                </div>
                            </div>
                            <div class="container table_holders" id="display_nhif_reports_windows">
                                <table class="table">
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Id No.</th>
                                        <th>NHIF No.</th>
                                        <th>NHIF Payments</th>
                                        <th>Employees Amount</th>
                                        <th>Total</th>
                                    </tr>
                                    <tr>
                                        <td>1. </td>
                                        <td>Hillary Ngige</td>
                                        <td>37367344</td>
                                        <td>37367344</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 1080</td>
                                        <td>Kes 2160</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="my_reports hide" id="kra_reports_window">
                            <h5 class="text-center"><u>K.R.A reports</u></h5>
                            <div class="container row">
                                <div class="col-md-6">
                                    <label for="select_kra_months" class="form-control-label">Select Month</label>
                                    <input type="month" name="select_kra_months" id="select_kra_months" class="form-control" value="<?php echo date('Y-m');?>">
                                </div>
                                <div class="col-md-6">
                                    <p class="block_btn" id="display_kra_reports"><i class="fas fa-eye"></i> Display <img class="hide" src="images/ajax_clock_small.gif" id="display_kra_reports_loader"></p>
                                </div>
                            </div>
                            <div class="container table_holders" style="font-size: 12px;" id="display_kra_reports_windows">
                                <table class="table">
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Id No.</th>
                                        <th>Gross Salary.</th>
                                        <th>Contribution(s)</th>
                                        <th>Deduction(s)</th>
                                        <th>Taxable Income</th>
                                        <th>P.A.Y.E</th>
                                        <th>Relief</th>
                                        <th>Final PAYE</th>
                                    </tr>
                                    <tr>
                                        <td>1. </td>
                                        <td>Hillary Ngige</td>
                                        <td>37367344</td>
                                        <td>Kes 12000</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 1080</td>
                                        <td>Kes 2160</td>
                                        <td>Kes 2160</td>
                                    </tr>
                                </table>
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