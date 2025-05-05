<div class="contents animate hide" id="managesubjects">
    <div class="titled">
        <h2>Academics</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Manage subjects</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p><strong>Information</strong></p>
                <p>- Update and delete subject information at this window</p>
                <p>- Changes done at the subject will cause major effects to how the system works including teachers access, exams and system timetable</p>
                <p>- When changes are done a new timetable needs to be generated!</p>
            </div>
            <div class="conts hide">
                <p id='subinform'></p>
            </div>
            <div class="body4">
                <label class="form-control-label" for="subjects_option">Start by either <br></label>
                <select class="form-control" name="subjects_option" id="subjects_option">
                    <option value="" hidden>Select an option</option>
                    <option value="search_subjects">Searching the subject</option>
                    <option value="display_subjects">Display all subject</option>
                </select>
                <div class="boddy form-group">
                    <div class="conts hide" id='seachsub'>
                        <div class="conts">
                            <label  class="form-control-label"  for="serchby">Search by: <br></label>
                            <select  class="form-control" name="serchby" id="serchby">
                                <option value="" hidden>Select..</option>
                                <option value="byname">By name:</option>
                                <option value="byclass">By class taught:</option>
                            </select>
                        </div>
                        
                        <div class="conts hide" id="byname">
                            <label  class="form-control-label" for="subnamed">Enter subject name: <br></label>
                            <input  class="form-control" type="text" name="subnamed" id="subnamed" placeholder="Enter subject name">
                        </div>
                        <div class="conts hide" id="classtaught">
                            <label  class="form-control-label" for="classtaughts">Select class:<br></label>
                            <p id="subjClass"><img src="images/load2.gif" alt="loading"></p>
                        </div>
                        <div class="btns">
                            <button type='button' id='finder' >Find</button>
                        </div>
                        <div class="conts" id="seachsubd">
                            <p id='errorhand'></p>
                        </div>
                    </div>
                </div>
                <div class="boddy1">
                    <p id="resulthold"></p>
                    <form class="boddy3 hide" id ='subjectdets'>
                        <div class="conts">
                            <h3 style='text-align:center;' >Subject Details</h3>
                        </div>
                        <div class="delete-sub">
                            <p  class="funga" id="delete-subject" ><i class="fa fa-trash-alt"></i></p>
                        </div>
                        <div class="conts">
                            <label for=""><b>Subject id</b>: <span id='subids'></span> <br></label>
                        </div>
                        <div class="conts">
                            <label  class="form-control-label"  for="subnam"><b>Subject name: </b><br></label>
                            <input class="form-control"  type="text" name="subnam" id="subnam" placeholder = 'Subject name'>
                        </div>
                        <div class="conts">
                            <label  class="form-control-label"  for="sub_display_name"><b>Subject Display Name: </b><br></label>
                            <input class="form-control"  type="text" name="sub_display_name" id="sub_display_name" placeholder = 'Subject name'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="subidss"><b>Enter Subject timetable refferee: </b><small>Eg. 'KSW' for 'Kiswahili' this name will appear on the timetable</small> <br></label>
                            <input  class="form-control" type="text" name="subidss" id="subidss" placeholder = 'Subject IDs'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="submarksd"><b>Subject Maximum marks: </b><br></label>
                            <input class="form-control" type="number" name="submarksd" id="submarksd" placeholder = 'Subject maximum marks'>
                        </div>
                        <div class="conts">
                            <label class="form-control-label" for="selcub"><b>Select Classes taught: </b><br></label>
                            <p id='classeslist'><img src="images/load2.gif" alt="loading"></p>
                        </div>
                        <hr>
                        <div class="conts">
                            <p class="hide" id="subjects_grades_hidden"></p>
                            <label for="grading_lists" class="form-control-label"><b>Grading Lists</b><span id="edit_grading_subject" class="block_btn mx-2" style="padding:2px;border-radius: 3px;"><small>Edit Grades</small></span></label>
                            <p id="my_grade_lists_subject"></p>
                        </div>
                        <div class="btns" >
                            <button type='button' id='updatesubs'>Update</button>
                            <!-- <button type='button' id='deletesubs' >Delete</button> -->
                            <button type='button' id='cancelsubs'><i class="fa fa-undo-alt"></i> Back</button>
                        </div>
                        <div class="conts">
                            <p id="errhandlers"></p>
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