<div class="contents animate hide" id="finance_statement">
    <div class="titled">
        <h2>Financial Report</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Financial statements</p>
        </div>
        <div class="middle1">
            <div class="notice1">
                <div class="notify">
                    <p><strong>Important:</strong></p>
                </div>
                <p>- At this window you will be able to view the different account statements basing on the financial information collected over time.</p>
                <p>- Select different option to view your information differently.</p>
                <p>- The main financial statements are:</p>
                <li class="margin-3px">Statement of Financial Performance</li>
                <!-- <li class = "margin-3px">Balance sheet <span class="banner">Coming soon</span></li> -->
                <li class="margin-3px">Cash flow statement</li>
            </div>
            <div class="rounded my-2 p-2 bg-gray notice1" >
                <div class="notify"><u><b>Select Document to display!</b></u></div>
                <label for="select_financial_document" class="form-control-label"><b>Select Document</b></label>
                <select name="select_financial_document" id="select_financial_document" class="form-control w-75">
                    <option value="" hidden>Select Option</option>
                    <option value="cashflow_statement">Cashflow Statement</option>
                    <option value="financial_performance_statement">Statement of Financial Performance</option>
                </select>
            </div>
            <div class="container hide" id="income_statement_window">
                <h4 class="text-center"><u>Cash Flow Statement</u></h4>
                <div class="cont row border-top border-bottom border-dark py-3 my-2">
                    <div class="col-md-4">
                        <label for="cash_flow_statement" class="form-control-label">Cash Flow Statement</label>
                        <select name="cash_flow_statement" id="cash_flow_statement" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="income_statement">Income Statement Termly Report</option>
                            <option value="income_statement_quarterly">Income Statement Quaterly Report</option>
                            <option value="annual_report">Annual Cashflow Report</option>
                            <option value="quarterly_report_sep">Quaterly Cashflow Report September</option>
                            <option value="quarterly_report_dec">Quaterly Cashflow Report December</option>
                            <option value="quarterly_report_mar">Quaterly Cashflow Report March</option>
                            <option value="quarterly_report_jun">Quaterly Cashflow Report June</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year_of_statement" class="form-control-label">Year of Statement</label>
                        <select name="year_of_statement" id="year_of_statement" class="form-control w-100">
                            <option value="" hidden>Select an option</option>
                            <?php
                            for ($index = ((date("Y") * 1) + 1); $index >= 2023; $index--) {
                                echo "<option " . ((date("Y") * 1) == $index ? "selected" : "") . " value='" . $index . "'>" . $index . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="mx-auto" id="generate_finance_reports">Generate Reports</button>
                    </div>
                </div>
                <div class="table_holders">
                    <div class="my-2" id="finance_statements"></div>
                    <div class="my-2" id="balanced_sheet"></div>
                </div>
            </div>
            <div class="container hide" id="financial_performance">
                <h4 class="text-center"><u>Statement of Financial perfomance</u></h4>
                <form method="post" action="reports/reports.php" target="_blank" class="cont row border-top border-bottom border-dark py-3 my-2">
                    <div class="col-md-4">
                        <label for="financial_performace" class="form-control-label">Financial perfomance</label>
                        <select required name="financial_performace" id="financial_performace" class="form-control w-100">
                            <option value="" hidden>Select an Option</option>
                            <option value="annual_report">Annual Financial Perfomance Report</option>
                            <!-- <option value="quarterly_report_sep">Quaterly Financial Perfomance Report September</option>
                            <option value="quarterly_report_dec">Quaterly Financial Perfomance Report December</option>
                            <option value="quarterly_report_mar">Quaterly Financial Perfomance Report March</option>
                            <option value="quarterly_report_jun">Quaterly Financial Perfomance Report June</option> -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year_of_perfomance" class="form-control-label">Year of Statement</label>
                        <select name="year_of_perfomance" id="year_of_perfomance" class="form-control w-100">
                            <option value="" hidden>Select an option</option>
                            <?php
                                for ($index = ((date("Y") * 1) + 1); $index >= 2023; $index--) {
                                    echo "<option " . ((date("Y") * 1) == $index ? "selected" : "") . " value='" . $index . "'> ".($index-1)." / " . $index . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="mx-auto" id="generate_finance_perfomance_reports">Generate Reports</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>