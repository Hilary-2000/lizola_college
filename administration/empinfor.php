<div class="staffinformed hide" id='informationwindow'>
        <button type="button" id='back_one'><i class="fas fa-arrow-left"></i> Back</button>
    <div class="titlesd">
        <p><strong>Employee Information</strong></p>
    </div>
    <div class="conts">
        <p>Employeed id: <span id = 'staffid'>14</span></p>
        <p class="link" style="width: fit-content;" id="delete_staff_permanently"><i class="fas fa-trash"></i> Delete Staff permanently</p>
    </div>
    <div class="notification">
        <div class="titles">
            <p><strong>Notice:</strong></p>
        </div>
        <div class="conts">
            <p><i>Please be sure with the information you are updating.</i></p>
            <p><i>If possible let the staff update their own information from their portal.</i></p>
        </div>
    </div>
    <div class="trnames">
        <div class="titles">
            <p><strong>Personal information:</strong></p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-control-label" for="fullnamed"><b>Full names: </b><br></label>
                <input class="form-control w-100" type="text" name="fullnamed" id="fullnamed" autocomplete="off"  placeholder = 'Fullname'>
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="gende"><b>Gender: </b><br></label>
                <select class="form-control w-100" name="gende" id="gende">
                    <option value="" hidden>Select..</option>
                    <option class="genders" id='M' value="M">Male</option>
                    <option class="genders" id ='F' value="F">Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="dobd"><b>Date of birth: </b><br></label>
                <input class="form-control w-100" type="date" name="dobd" id="dobd" placeholder = 'Fullname' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="natids"><b>National id: </b><br></label>
                <p id = 'nationalids'></p>
                <input class="form-control w-100" type="text" name="natids" id="natids" placeholder = 'National id/ Passport' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="phonenumberd"><b>Phone number : </b><br></label>
                <p id ='phoneerrord'></p>
                <input class="form-control w-100" type="text" name="phonenumberd" id="phonenumberd" placeholder = 'Phone numbers' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="addresdd"><b>Address: </b><br></label>
                <input class="form-control w-100" type="text" name="addresdd" id="addresdd" placeholder = 'Town or City' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="staffmail"><b>Email: </b><br></label>
                <p id='emailstaff'></p>
                <input class="form-control w-100" type="text" name="staffmail" id="staffmail" placeholder = 'Email' autocomplete="off" >
            </div>
        </div>
    </div>
    <div class="credentials">
        <div class="titles">
            <p><strong>Human Resource Details</strong></p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-control-label" for="d_o_e_input"><b>Date of employment: </b><br></label>
                <input class="form-control w-100" type="date" name="d_o_e_input" id="d_o_e_input" max = "<?php echo date("Y-m-d"); ?>" placeholder = 'Job Number' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <p id="error_job_number"></p>
                <label class="form-control-label" for="employees_job_number"><b>Personal File Number: <img class="hide" src="images/ajax_clock_small.gif" id="job_number_loader"></span></b><br></label>
                <input class="form-control w-100" type="text" name="employees_job_number" id="employees_job_number" placeholder = 'Personal File Number' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="employees_job_title"><b>Job Title: </b><br></label>
                <input class="form-control w-100" type="text" name="employees_job_title" id="employees_job_title" placeholder = 'Job Title' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="tscnosd"><b>Employment Number: </b><br></label>
                <input class="form-control w-100" type="text" name="tscnosd" id="tscnosd" placeholder = 'Employment Number' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="nssf_numbers"><b>NSSF No: </b><br></label>
                <input class="form-control w-100" type="text" name="nssf_numbers" id="nssf_numbers" placeholder = 'NSSF No' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="nhif_numbers"><b>NHIF No: </b><br></label>
                <input class="form-control w-100" type="text" name="nhif_numbers" id="nhif_numbers" placeholder = 'NHIF No' autocomplete="off" >
            </div>
            <div class="col-md-4">
                <label class="form-control-label" for="employees_type"><b>Employees Type: </b><br></label>
                <select name="employees_type" id="employees_type" class="form-control">
                    <option class="emp_infor_opt" value="" hidden>Select Employees Type</option>
                    <option class="emp_infor_opt" value="Intern"> Intern</option>
                    <option class="emp_infor_opt" value="Probationary"> Probationary</option>
                    <option class="emp_infor_opt" value="Regular (fixed-term)"> Regular (fixed-term)</option>
                    <option class="emp_infor_opt" value="Regular (open-ended)"> Regular (open-ended)</option>
                    <option class="emp_infor_opt" value="Casual"> Casual</option>
                    <option class="emp_infor_opt" value="Consultant"> Consultant</option>
                    <option class="emp_infor_opt" value="Non-teaching Staff"> Non-teaching Staff</option>
                    <option class="emp_infor_opt" value="BOG Staff"> BOG Staff</option>
                    <option class="emp_infor_opt" value="Teacher In Practice"> Teacher In Practice (T.P)</option>
                    <option class="emp_infor_opt" value="T.S.C Teacher"> T.S.C Teacher</option>
                </select>
            </div>
        </div>
    </div>
    <div class="credentials">
        <div class="titles">
            <p><strong>Credentials</strong></p>
        </div>
        <div class="conts">
            <label class="form-control-label" for="usenames"><b>Usename: <small>(not case sensitive)</small></b></label>
            <p id='usererrors'></p>
            <input class="form-control" type="text" name="usenames" id="usenames" autocomplete="off" >
        </div>
        <div class="conts">
            <label class="form-control-label" >Change password?</label>
            <p id='passworderrors2'></p>
            <button type='button' id ='changepwd'>Change password</button>
        </div>
    </div>
    <div class="credentials">
        <div class="titles">
            <p><strong>Next Of Kin</strong></p>
        </div>
        <div class="conts">
            <label class="form-control-label" for="kin_fullnames"><b>Kin Fullname: </b></label>
            <input class="form-control" placeholder="Kin Fullname" type="text" name="kin_fullnames" id="kin_fullnames" >
        </div>
        <div class="conts">
            <label class="form-control-label" for="kin_relationship_edit"><b>Kin Relationship: </b></label>
            <input class="form-control" placeholder="Kin Relationship" type="text" name="kin_relationship_edit" id="kin_relationship_edit" >
        </div>
        <div class="conts">
            <label class="form-control-label" for="kin_contacts_edit"><b>Kin Contacts: </b></label>
            <input class="form-control" placeholder="Kin Contacts" type="text" name="kin_contacts_edit" id="kin_contacts_edit" >
        </div>
        <div class="conts">
            <label class="form-control-label" for="kin_location_edit"><b>Kin Location: </b></label>
            <input class="form-control" placeholder="Kin Location" type="text" name="kin_location_edit" id="kin_location_edit" >
        </div>
    </div>
    <div class="statuses">
        <div class="titles">
            <p><strong>Status</strong></p>
        </div>
        <div class="conts">
            <p class="hide" id="staff_detail_out"></p>
            <label class="form-control-label" for="auths"><b>Authority: <span id="myauthorities"></span> </b><br> <span class="hide" id="load_roles43"><img src="images/ajax_clock_small.gif" id=""></span></label>
            <span id="data_in_display"></span>
            <!-- <select name="auths" id="auths">
                <option value="" hidden>Select authority..</option>
                <option id="auths0" value="0">Administrator</option>
                <option id="auths1" value="1">Headteacher/Principal</option>
                <option id="auths3" value="3">Deputy principal</option>
                <option id="auths2" value="2">Teacher</option>
                <option id="auths5" value="5">Class teacher</option>
                <option id="auths6" value="6">School Driver</option>
                
            </select> -->
        </div>
        <div class="conts">
            <label class="form-control-label" for="deleted"><b>Deleted: </b><br></label>
            <p><i>The user won`t appear in any list as staff when searched and also cannot login.</i></p>
            <select name="deleted" id="deleted">
                <option value="" hidden>Select..</option>
                <option id='del1' value="1">Yes</option>
                <option id='del0' value="0">No</option>
            </select>
        </div>
        <div class="conts">
            <label class="form-control-label" for="activated"><b>Activated: </b><br></label>
            <p><i >The user will appear as a staff but they will be in-active as users to the system.</i></p>
            <select name="activated" id="activated">
                <option value="" hidden>Select..</option>
                <option id='act1' value="1">Yes</option>
                <option id='act0' value="0">No</option>
            </select>
        </div>
        <div class="conts hide" id="reason_for_staff_inactive">
            <label class="form-control-label" for="activated"><b>Reason: </b><br></label>
            <p><i >Type reasons below why the staffis de-activated.</i></p>
            <textarea name="reason_inactive" id="reason_inactive" cols="30" rows="5" class="w-100 form-control" placeholder="Reason for being De-activated!"></textarea>
        </div>
    </div>
    <div class="conts">
        <p id='updateerror'></p>
    </div>
    <div class="action">
        <button type="button" id='updatestaff' ><i class="fas fa-upload"></i> Update</button>
        <button type="button" id='backtostaff'><i class="fas fa-arrow-left"></i> Back</button>
    </div>
</div>