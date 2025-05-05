<div class="contents animate hide" id="personal_profile_page">
    <div class="titled">
        <h2>School Profile</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Update School Profile</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <div class="school_logo">
                    <img src="images/dp.png" id="dpimage-sett" alt="">
                    <button type="button" id="change_dp_btns">Change</button>
                    <p id="dp_err_handler"></p>
                </div>
                <div class="conts" style="text-align:center;border-bottom:1px dashed black;">
                    <h5><?php echo $_SESSION['fullnames'];?></h5>
                    <p> <b><u> Update Personal Information</u></b></p>
                </div>
                    <p id="my_information_inner"></p>
                    <p class="block_btn" id="apply_leave_staff">Apply Leave <i class="fas fa-send"></i></p>
                <div class="basic_details">
                    <div class="titles">
                        <p>Basic Information</p>
                    </div>
                    <div class="row">
                        <div class="conts col-md-6">
                            <label for="my_full_name">Full name: <span style="color:red;">*</span> <br></label>
                            <input class="form-control" type="text" name="my_full_name" id="my_full_name" placeholder ="Fullname">
                        </div>
                        <div class="conts col-md-6">
                            <label for="my_dob">D.O.B: <span style="color:red;">*</span>  <br></label>
                            <input class="form-control" type="date" name="my_dob" id="my_dob" >
                        </div>
                        <div class="conts col-md-6">
                            <label for="sys_username">Username: <span style="color:red;">*</span>  <br></label>
                            <input class="form-control" type="text" name="sys_username" id="sys_username" placeholder = "Username">
                            <img src="images/ajax_clock_small.gif" class="hide" id="ch_uname_clock">
                            <p id="check_me_username"></p>
                        </div>
                        <div class="conts col-md-6 my-1">
                            <label for="my_gender">Gender: <span style="color:red;">*</span> <br></label>
                            <select class="form-control w-100" name="my_gender" id="my_gender">
                                <option value="" hidden>Select...</option>
                                <option id="M12" value="M">Male</option>
                                <option id="F12" value="F">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="administrator_contact">
                    <div class="titles">
                        <p>Contact Information</p>
                    </div>
                    <div class="conts">
                        <label for="my_phone_no">Phone no: <span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="number" name="my_phone_no" id="my_phone_no" placeholder ="Phone number">
                    </div>
                    <div class="conts">
                        <label for="my_nat_id">National I`d/ Passport No: <span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="number" name="my_nat_id" id="my_nat_id" placeholder ="National I`d">
                    </div>
                    <div class="conts">
                        <label for="my_tsc_code">My TSC Code:<br></label>
                        <input class="form-control" type="text" name="my_tsc_code" id="my_tsc_code" placeholder ="TSC Code">
                    </div>
                    <div class="conts">
                        <label for="my_mail">Email:<span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="email" name="my_mail" id="my_mail" placeholder ="My email">
                    </div>
                    <div class="conts">
                        <label for="my_address">Address:<span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="text" name="my_address" id="my_address" placeholder ="Address">
                    </div>
                </div>
                    <div class="conts">
                        <p id="update_my_infor"></p>
                    </div>
                    <div class="btns">
                        <button type='button' id="change_my_information">Change Information</button>
                    </div>
                <div class="administrator_contact">
                    <div class="titles">
                        <p>Change Credentials</p>
                    </div>
                    <div class="conts">
                        <label for="old_pass">Old password: <span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="password" name="old_pass" id="old_pass" placeholder ="Old Password">
                    </div>
                    <div class="conts">
                        <label for="new_pass">New password: <span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="password" name="new_pass" id="new_pass" placeholder ="New Password">
                    </div>
                    <div class="conts">
                        <label for="repeat_pass">Repeat password: <span style="color:red;">*</span>  <br></label>
                        <input class="form-control" type="password" name="repeat_pass" id="repeat_pass" placeholder ="Repeat Password">
                    </div>
                </div>
                <div class="conts">
                    <p id="update_credential_infor"></p>
                </div>
                <div class="btns">
                    <button type='button' id="change_my_pass">Change Credentials</button>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>