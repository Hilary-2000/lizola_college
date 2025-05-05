<div class="contents animate hide" id="managestaff">
    <div class="titled">
        <h2>Staff Information Management</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Staff management</p>
            <!--<div class="admin_special rotate_down">
            </div>-->
        </div>
        <div class="middle1">
            <div class="row">
                <div class="conts col-md-9">
                    <p>At this window you are able to manage your staff information<br>This include <br>1. Updating and deleting their information <br>2. Activate or deactivate them as users <br>3. Assign class teacher a class <br>4. Click either of the options below to start<br><br></p>
                    <p>You can start off by selectiing the following options: <br></p>
                    <select class="d-none" name="manage_tr_option" id="manage_tr_option">
                        <option value="" hidden>Select...</option>
                        <option value="viewstaffavailable" id="view_my_stf">View My staff</option>
                        <option value="assignclasses">Assign teacher a class</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <span id="manage_departments" class="block_btn"><i class="fas fa-cogs"></i> Manage Departments</span>
                </div>
            </div>
            <hr>
            <div class="contstable hide" id="constable">
                <p id = 'stafferrors' ></p>
            </div>
            <?php include("empinfor.php")?>
            <?php include("assign_subjects.php")?>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>