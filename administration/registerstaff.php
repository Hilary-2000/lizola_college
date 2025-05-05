<div class="contents animate hide" id="regstaff">
    <div class="titled">
        <h2>Register staff</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Register staff</p>
                </div>
                <div class="col-md-3">
                    <span id="register_staff_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="top">
                <p style='text-align:center;font-size:17px;'><strong>Instructions</strong></p>
                <p>1. Please fill all the fields required: They are marked with <span style = 'color:red;'>*</span> </p>
                <p>2. This user will have an account and will be limited to some functions with the role you assign to them. </p>
                <p>3. Depending on the role you assign a user, the user will be able to change some information but will be monitored.</p>
                <p>4. The date of birth maximum date is set 18 years from today because an employee cant be less than 18 years</p>
                <!--<p><a href="#"> Click here to read more.. </a></p>-->
            </div>
            <div class="bodywindow">
                <p id='errors'></p>
                <form class="staffdatas" id="staffdatas">
                    <div class="col-md-6 form-group">
                        <p class="sections">Employee`s Personal Data:</p>
                        <div class="conts my-2">
                            <label class="form-control-label" for="fullnames"><b>Full Name:</b><span style = 'color:red;'>*</span></label>
                            <input class="form-control" style="width: 90%;" type="text" name="fullnames" id="fullnames" placeholder = "Enter fullname">
                        </div>
                        <div class="conts my-2">
                            <label for="dobo"><b>DOB: </b><span style = 'color:red;'>*</span> <small>The maximum date is 18 yrs from today</small><br> </label>
                            <input class="form-control" style="width: 90%;" type="date" name="dobo" id="dobo" max=<?php echo date("Y-m-d",strtotime("-18 years"));?>>
                        </div>
                        <div class="conts my-2">
                            <label for="gen"><b>Gender:</b><span style = 'color:red;'>*</span> <br> </label>
                            <select class="form-control" style="width: 90%;" name="gen"id="gen">
                                <option value="" hidden>Select..</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div class="conts my-2">
                            <label for="phonenumber"><b>Phone number:</b><span style = 'color:red;'>*</span> <br></label>
                            <p id="phonehandler"></p>
                            <input class="form-control" style="width: 90%;" type="number" name="phonenumber" id="phonenumber" placeholder = "Enter phonenumber">
                        </div>
                        <div class="conts my-2">
                            <label for="adress"><b>Address:</b><span style = 'color:red;'>*</span> <br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="adress" id="adress" placeholder = "Enter town or city">
                        </div>
                        <div class="conts my-2">
                            <label for="poridnumber"><b>Id number/Passport number:</b><span style = 'color:red;'>*</span> <br> </label>
                            <p id="idpasshandler"></p>
                            <input class="form-control" style="width: 90%;" type="text" name="poridnumber" id="poridnumber" placeholder = "Enter id or passport number">
                        </div>
                        <div class="conts my-2">
                            <p class="hide" id="role_data_2322"></p>
                            <label for="authority"><b>Role:</b><span style = 'color:red;'>*</span> <br> <span class="hide" id="load_roles2"><img src="images/ajax_clock_small.gif" ></span></label>
                            <span id="other_roles_inside"></span>
                            <!-- <select  class="form-control" style="width: 90%;" name="authority" id="authority">
                                <option value="" hidden>Select..</option>
                                <option value="0">Administrator</option>
                                <option value="1">Headteacher/Principal</option>
                                <option value="3">Deputy principal</option>
                                <option value="2">Teacher</option>
                                <option value="5">Class teacher</option>
                                <option value="6">School Driver</option>
                                
                            </select> -->
                        </div>
                        <div class="conts my-2">
                            <label for="tscno"><b>Employment Number: </b><br> </label>
                            <p id="tschandler"></p>
                            <input class="form-control" style="width: 90%;" type="text" name="tscno" id="tscno" placeholder = "Employment Number.">
                        </div>
                        <div class="conts my-2">
                            <label for="nssf_number"><b>NSSF number: </b><br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="nssf_number" id="nssf_number" placeholder = "Enter NSSF No.">
                        </div>
                        <div class="conts my-2">
                            <label for="nhif_number"><b>NHIF number: </b><br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="nhif_number" id="nhif_number" placeholder = "Enter NHIF No.">
                        </div>
                        <div class="conts my-2">
                            <label for="staffemail"><b>Enter email: </b><br> </label>
                            <p id="emailhandler"></p>                            
                            <input class="form-control" style="width: 90%;" type="email" name="staffemail" id="staffemail" placeholder = "Enter email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="sections"><b>User`s Credentials:</b></p>
                        <div class="conts my-2">
                            <label for="username"><b>Username:</b><span style = 'color:red;'>*</span> <br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="username" id="username" placeholder = "Enter Username - Phone Number Recommended!">
                            <img src="images/ajax_clock_small.gif" class="hide" id="check_usernames_clock">
                            <p id="err_hand_check_uname"></p>
                        </div>
                        <div class="conts my-2">
                            <label for="pword"><b>Password:</b><span style = 'color:red;'>*</span> <br> </label>
                            <input class="form-control" style="width: 90%;" type="password" name="pword" id="pword" placeholder = "Enter Password">
                        </div>
                        <div class="conts my-2">
                            <label for="pword2"><b>Re-enter password:</b><span style = 'color:red;'>*</span> <br> </label>
                            <input class="form-control" style="width: 90%;" type="password" name="pword2" id="pword2" placeholder = "Re-enter Password">
                        </div>
                        <p class="sections"><b>Next Of Kin:</b></p>
                        <div class="conts my-2">
                            <label for="kin_fullname"><b>FullName:</b><br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="kin_fullname" id="kin_fullname" placeholder = "Kin Fullname">
                        </div>
                        <div class="conts my-2">
                            <label for="kin_relation"><b>Kin Relation:</b><br> </label>
                            <select name="kin_relation" id="kin_relation" style="width: 90%;" class="form-control">
                                <option value="" hidden>Select Kin</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Guardian">Guardian</option>
                            </select>
                        </div>
                        <div class="conts my-2">
                            <label for="kin_contacts"><b>Kin Contacts:</b><br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="kin_contacts" id="kin_contacts" placeholder = "Kin Contacts">
                        </div>
                        <div class="conts my-2">
                            <label for="kin_location"><b>Kin Location:</b><br> </label>
                            <input class="form-control" style="width: 90%;" type="text" name="kin_location" id="kin_location" placeholder = "Kin Location">
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm text-xxs" type="button" id="resetstaffdatas">Reset form</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-sm text-xxs" id="registerstaff"><i class="fas fa-pen-fancy"></i> Register Staff</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>