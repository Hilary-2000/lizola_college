<div class="contents animate hide" id="payfeesd">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Pay fees</p>
                </div>
                <div class="col-md-3">
                    <span id="payfees_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="row mt-2">
                <div class="notice1 col-md-8">
                    <div class="notify">
                        <p><strong>Important:</strong></p>
                    </div>
                    <p>- At this window you are allowed to pay fees</p>
                    <p>- Please capture the information you enter correctly.</p>
                    <p>- if you are not sure of the information you are entering do not confirm any transaction and seek advice from the administrator.</p>
                    <P>- You also have 30 minutes to reverse the transaction after which the status changes to confirmed.</P>
                    <div class="notify">
                        <p><strong>Procedure:</strong></p>
                        <div class="conts" id='btnshow1'>
                            <button type='button' id="showprocess1">Show</button>
                        </div>
                    </div>
                    <div class="procedure hide" id="procedure">
                        <p>1. Search for the student with their admission number</p>
                        <p>2. Select purpose of payment at the 'Pay here' window below</p>
                        <p>3. Select mode of pay.</p>
                        <p>4. Enter the information requested by the system <strong>correctly</strong>.</p>
                        <p>5. Confirm payment to save the information in the database.</p>
                        <div class="conts" id='btnshide1'>
                            <button type='button' id="hideprocess1">Hide</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type='button' class="d-none" id='assign_fees_credit_notes' >Assign Fees Credit Notes</button>
                    <button type='button' id='record_school_income' >Manage Revenue</button>
                </div>
            </div>
            <div class="paywindow">
                <div class="title">
                    <h4 style="text-align:center;">Student summary information</h4>
                </div>
                <div class="conts search_students_finance">
                    <p class="hide" id="err_handler"></p>
                    <label for="studids" class="form-control-label" >Enter student Name or Reg No: <br> </label>
                    <div class="autocomplete">
                        <input class="form-control" type="text" style="max-width:300px; "  id="studids" placeholder = "Reg No or Name">
                    </div>
                    <button type='button'  id='searchfin1'>Search</button>
                </div>
                <div class="contsfd">
                    <p id='paymentsresults'></p>
                </div>
            </div>
            <form class="paywindow" id='payforms'>
                <div class="title">
                    <h3>Pay here</h3>
                </div>
                <div class="conts">
                    <label for="payfor"><b>Select what to pay for and enter the amount:</b> <br></label>
                    <p id='payments'></p>
                </div>
                <div class="conts">
                    <label class="form-control-label" for="modeofpay"><b>Select mode of pay</b> <br></label>
                    <select class="form-control" name="modeofpay" id="modeofpay">
                        <option value="" hidden>Select option..</option>
                        <option value="mpesa">M-PESA</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
                <div class="payments hide" id='mpesad'>
                    <h6><u>M-Pesa Payment</u></h6>
                    <div class="conts">
                        <label class="form-control-label" for="mpesacode"><b>Enter Mpesa code:</b> <br></label>
                        <input type="text" class="form-control" name="mpesacode" id="mpesacode" placeholder='Enter mpesa code'>
                        <p id="mpesa_code_err"></p>
                        <!--<button type="button">Confirm</button>-->
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="amount1"><b>Amount:</b> <br></label>
                        <input class="form-control" type="number" name="amount1" id="amount1" placeholder='Enter Amount'>
                    </div>
                    <div class="conts">
                        <label for="select_time_set_option3" class="form-label" id="select_time_cash"><b>Set Fees Payment Time</b></label>
                        <select name="select_time_set_option3" id="select_time_set_option3" class="form-control">
                            <option id="option1_3" value="auto" selected >Automatically Capture</option>
                            <option id="option2_3" value="set" >Set Date</option>
                        </select>
                    </div>
                </div>
                <div class="payments hide" id='banksd'>
                    <h6><u>Bank Payment</u></h6>
                    <div class="conts">
                        <label class="form-control-label" for="bankcode"><b>Enter bank code: </b><br></label>
                        <input class="form-control" type="text" name="bankcode" id="bankcode" placeholder='Enter bank code'>
                        <p id="bank_code_errs"></p>
                    </div>
                    <div class="conts">
                        <label class="form-control-label" for="amount2"><b>Amount:</b> <br></label>
                        <input class="form-control" type="number" name="amount2" id="amount2" placeholder='Enter Amount'>
                    </div>
                    <div class="conts">
                        <label for="select_time_set_option2" class="form-label" id="select_time_cash"><b>Set Fees Payment Time</b></label>
                        <select name="select_time_set_option2" id="select_time_set_option2" class="form-control">
                            <option id="option1_2" value="auto" selected >Automatically Capture</option>
                            <option id="option2_2" value="set" >Set Date</option>
                        </select>
                    </div>
                </div>
                <div class="payments hide" id='cash'>
                    <h6><u>Cash Payment</u></h6>
                    <div class="conts">
                        <label class="form-control-label" for="amount3"><b>Amount: </b><br></label>
                        <input class="form-control" type="number" name="amount3" id="amount3" placeholder='Enter Amount'>
                    </div>
                    <label for="select_time_set_option1" class="form-label" id="select_time_cash"><b>Set Fees Payment Time</b></label>
                    <select name="select_time_set_option1" id="select_time_set_option1" class="form-control">
                        <option id="option1_1" value="auto" selected >Automatically Capture</option>
                        <option id="option2_1" value="set" >Set Date/Time</option>
                    </select>
                </div>
                <div class="payments p-2 hide" id="show_date_time">
                    <h6><u>Select Time</u></h6>
                    <p><b>Note:</b> <br>Change time and date only if the transaction was made before.</p>
                    <hr>
                    <label for="date_of_payments_fees" class="form-label"><b>Date of payments</b></label>
                    <input type="date"  class="form-control" id="date_of_payments_fees" value="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d");?>">
                    <div class="col-md-6">
                        <label for="time_of_payment_fees" class="form-label"><b>Date Of Payments</b></label>
                        <input type="time" class="form-control" id="time_of_payment_fees" value="<?php echo date("H:i")?>"  max="<?php echo date("H:i");?>">
                    </div>
                </div>
                <div class="hide" id="edit_supporting_documents">
                    <label for="reciept_size" class="form-control-label mt-2"><b>Supporting Documents</b></label>
                    <p id="supporting_document_err"></p>
                    <div class="container border border-secondary rounded p-2">
                        <label for="file_names" class="form-label">Supporting Document Name</label>
                        <input type="text" class="form-control" id="file_names" placeholder="File Name">
                        <label for="supporting_documents" class="form-control-label">Select Multiple Supporting Documents <i title="Upload Supporting documents like cheques & bank deposit slips." class="fas fa-info-circle"></i> <span class="hide" id="load_documents"><img src="images/ajax_clock_small.gif" id=""></span></label>
                        <input type="file"  accept=".png, .jpeg, .jpg, .pdf, .docx" class="form-control text-sm my-2" name="supporting_documents" id="supporting_documents">
                        <progress class="form-control my-1 hide" id="upload-progress" value="0" max="100"></progress>
                        <button type="button" id="upload_supporting_documents" class="">Upload</button>
                        <div class="container my-1 p-1 border border-secondary rounded" id="list_supporting_documents">
                            <p class="text-secondary">No Supporting Documents Added</p>
                        </div>
                    </div>
                </div>
                <div class="conts">
                    <div class="conts">
                        <p id ='geterrorpay'></p>
                    </div>
                </div>
                <div class="contsbtn hide" id="btns">
                    <button type='button' id='makepayments' >Confirm</button>
                </div>
            </form>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>