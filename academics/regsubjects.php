<div class="contents animate hide" id="regsubjects">
    <div class="titled">
        <h2>Academics</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Register a subject</p>
        </div>
        <div class="middle1">
            <div class="tops" style='padding: 10px 0;'>
                <div class="conts">
                    <p><strong>Note:</strong></p>
                    <p>- At this window you will be able to register a subject.</p>
                    <p>- Fill all the fields required correctly.</p>
                    <p>- A subject name can be used twice but the sibject id cant be used twice.</p>
                    <p>- When registering a subject its recomended that the classes the subject is taught should be checked under the same subject name unlike inserting the subject name more than once and assigning a class on each registration.</p>
                </div>
            </div>
            <div class="body1">
                <div class="conts" style='padding:10px 0;'>
                    <h5 style="text-align:center;">Register Subjects</h5>
                </div>
                <div class="body3">
                    <form class="left" id="formpay">
                        <div class="conts">
                            <label class="form-control-label" for="subname"><b>Enter Subject Name: </b><small>Eg. 'Kiswahili Class 4 & 5'</small> <span class="text-danger">(Unique only)</span> <br></label>
                            <p id='subnameerr'></p>
                            <input class="form-control" type="text" name="subname" id="subname" placeholder = 'Subject Name'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="subject_display_name"><b>Enter Subject Display Name: </b> eg 'Kiswahili'<br></label>
                            <input  class="form-control" type="text" name="subject display name" id="subject_display_name" placeholder = 'Subject Display Name'>
                        </div>
                        <div class="conts">
                            <label  class="form-control-label" for="sundids"><b>Enter Subject timetable refferee: </b><small>Eg. 'KSW' for 'Kiswahili'</small> <br></label>
                            <input class="form-control"  type="text" name="sundids" id="sundids" placeholder = 'Subject IDs'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="submarks"><b>Enter Subject Maximum Marks: </b><br></label>
                            <input  class="form-control" type="number" max=100 min=0 name="submarks" id="submarks" placeholder = 'Subject Marks'>
                        </div>
                        <div class="conts" style='margin:10px 0 0 0'>
                            <label class="form-control-label" for="selectsubs"><b>Select classes: </b><br></label>
                            <p id='subjectlist'><img src="images/load2.gif" alt="loading"></p>                            
                        </div>
                        <div class="cont my-2">
                            <label for="set_grades" class="form-control-label"><b>Set Grades</b></label><br>
                            <p class="block_btn" id="set_grades_display_btn">Set Grades</p>
                            <p class="my-2 hide" id="set_my_grades_list"></p>
                            <p class="my-2" id="display_tables_list"></p>
                        </div>
                        <div class="conts" style='margin:20px 0 0 0;display:flex;flex-direction:row-reverse;'>
                            <button type='button' id='registersub'>Register</button>
                        </div>
                        <div class="conts">
                            <p id='errregsub'></p>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>