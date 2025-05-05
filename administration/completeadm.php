<div class="contents animate hide" id="completeadmission">
    <div class="titled">
        <h2>Complete admission</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Complete admission</p>
        </div>
        <div class="middle2">
            <form class="form-group" id = "completeadm">
                <div class="tops">
                    <h3>Student Name: <span id="studname">Student Name</span></h3>
                    <h3>Student admission is: <span id="admissionno">38</span></h3>
                    <p style="margin-top:10px;font-size:12px;"><strong>Note: </strong>The admission number should be shared to the student. Its their unique id that the system needs to uniquely identity each student</p>
                </div>
                <div class="conts fa-xs">
                    <p id="errorcomadmit" ></p>
                    <label class="form-control-label" for="disabled"><b>Disabled</b></label><br>
                    <select  class=" border border-dark text-xxs form-control  w-50" name="disabled" id="disabled">
                        <option hidden value="">Select..</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <div class="conts hide" id="disable">
                        <label class="form-control-label" >If yes briefly describe below.</label><br>
                        <textarea class="form-control" placeholder="Briefly explain the student`s disability" name="disability" id="disability" cols="30" rows="5"></textarea>
                    </div>
                </div>
                <hr>
                <div class="conts">
                    <label for=""><b>Admission essentials:</b> <small><strong>Hint:</strong> These are items to be brought on the day of admission</small> </label>
                    <div class="w-50" id ="admissionessentials">

                    </div>
                </div>
                <hr>
                <div class="conts w-50" >
                    <label class="form-control-label" for="payfees"><b>Pay admission fees</b></label><br>
                    <select  class=" border border-dark text-xxs form-control" name="payfees" id="payfees">
                        <option hidden value="">Select..</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="conts hide w-50" id="paysmode">
                    <label class="form-control-label" for="paymode">Paymode: </label><br>
                    <select  class=" border border-dark text-xxs form-control" name="paymode" id="paymode">
                        <option hidden value="">Select mode..</option>
                        <option value="mpesa">M-PESA</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                    </select>
                    
                    <div class="conts">
                        <p id="errpayments"></p>
                    </div>
                    <div class="hide border border-secondary rounded w-100 p-1" id=cashed>
                        <div class="conts">
                            <label class="form-control-label" for="amnt">Amount:<br></label>
                            <input  class="form-control" type="text" name="amnt" id="amnt" placeholder="Amount paid (ksh)">
                        </div>
                    </div>
                    <div class="hide border border-secondary rounded w-100 p-1" id="mpesas">
                        <div class="conts">
                            <label for="mpesa">M-Pesa code:<br></label>
                            <input class="form-control" type="text" name="mpesa" id="mpesa" placeholder="M-Pesa code">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="amounts">Amount:<br></label>
                            <input class="form-control" type="text" name="amounts" id="amounts" placeholder="Amount paid (ksh)">
                        </div>
                    </div>
                    <div class="hide border border-secondary rounded w-100 p-1" id="banks">
                        <div class="conts">
                            <label class="form-control-label" for="bank">Bank Transaction code:<br></label>
                            <input class="form-control" type="text" name="bank" id="bank" placeholder="Bank transaction code">
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="amount">Amount:<br></label>
                            <input class="form-control" type="text" name="amount" id="amount" placeholder="Amount paid (ksh)">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="conts">
                    <p class="hide" id="previous_schools"></p>
                    <label for="previous_schools_attended" class="p-0 bg-transparent"><b>Previous Schools Attended</b></label>
                    <br>
                    <p class="block_btn" id="prev_school"><i class="fas fa-plus"></i> Add</p>
                    <div class="container" id="previous_school_list">
                        <!-- <table class="table">
                            <tr>
                                <th>No</th>
                                <th>School Name</th>
                                <th>Date Left</th>
                                <th>Marks Scored</th>
                                <th>Reason for Leaving</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Testimony Grammar School</td>
                                <td>Jun 12th 2022</td>
                                <td>352</td>
                                <td>Relocated to a new town.</td>
                            </tr>
                        </table> -->
                        <p class='text-secondary'>No school previously attended by the student listed!</p>
                    </div>
                </div>
                <hr>
                <div class="conts">
                    <label for="medical_history" class="p-0 bg-transparent"><b>Medical History:</b></label>
                    <textarea name="medical_histoty" id="medical_history" cols="30" rows="5" class="form-control" placeholder="Briefly describe the medical hisory of the student"></textarea>
                </div>
                <hr>
                <div class="conts">
                    <label for="clubs_and_sports" class="form-control-label" ><b>Sports Houses / Clubs</b></label>
                    <div class="conts" id="clubs_n_sports"></div>
                </div>
                <hr>
                <div class="conts">
                    <label for="source_of_funding" class="p-0"><b>Source Of Funding School Fees:</b></label>
                    <select name="source_of_funding" class="border border-dark text-xxs form-control bg-light w-50" id="source_of_funding" class="form-control">
                        <option value="" hidden>Select an option</option>
                        <option value="Sponsorship">Sponsorship</option>
                        <option value="Reimbursment">Reimbursment by Company</option>
                        <option value="Self">Self</option>
                        <option value="Others">Others</option>
                    </select>
                    <input class="form-control w-75 my-2 mx-0 bg-light hide" type="text" placeholder="Specify ways they fund their Fees Payment,," name="source_of_funding_data" id="source_of_funding_data" placeholder="Source of funding">
                </div>
                <div class="form-group">
                    <label for="last_year_academic_balance" class="form-control-label">Last year academic balance</label>
                    <input type="number" value="0" class="form-control" id="last_year_academic_balance" placeholder="Last academic year balance">
                </div>
                <div class="cont">
                </div>
                <hr>
                <div class="conts">
                    <label class="form-control-label" for="board"><b>Boarding</b></label>
                    <select class=" border border-dark text-xxs form-control w-25"  name=" board" id="board">
                        <option value="">Select..</option>
                        <option value="enroll">Yes ,(enroll)</option>
                        <option value="none">No</option>
                    </select>
                    <div class="conts hide" id="boardings">
                        <p>Proceed and complete admission then there after go to the <strong>"Eroll boarding"</strong>  button on the navigation pane to enroll the student.</p>
                        <p>Alternatively ask the dormitory admin to enroll the student</p>
                    </div>
                </div>
                <div class="bot">
                    <p id="skip"><u>Skip</u> and complete later</p>
                    <button id="completeadmbtn" type ="button">Complete admission</button>
                </div>
            </form>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>