<div class="contents animate hide" id="admitsStudents">
<img class="hide" src="images/ajax_clock_small.gif" id="allow_ct_reg_clock_elect">
    <p class="hide" id="menu_data"></p>
    <div class="titled">
        <h2>Admission</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Register a student</p>
                </div>
                <div class="col-md-3">
                    <span id="admit_student_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1 ">
            <div class="row">
                <div class="col-md-9">
                    <div class="top">
                        <p style="text-align: center; font-size:16px;"><strong>Instructions</strong></p>
                        <p>1. Fill all the required fields to complete the student registration</p>
                        <p>2. The Important field fields are marked with <span style = 'color:red;'>*</span>, the rest are optional</p>
                        <p>3. After filling all the fields click the submit button to save the information to the database</p>
                        <p>4. The reset button on the left clears the whole form letting you fill it afresh</p>
                        <p>5. Examples are given on every field on how to fill it.</p>
                        <p><br><strong>NOTE:</strong> After submitting the information the students admission number will be automatically generated<br>Its highly recomended that the students remember their admission number.</p>
                        <p> <strong>Do not admit the student twice!</strong> </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- upload documents -->
                    <p style="text-align: center; font-size:16px;"><strong>Upload Students</strong></p>
                    <input style="font-size: 12px;" type="file"  accept=".csv, .xlsx, .xls" class="form-control text-sm my-2" name="new_student_uploads" id="new_student_uploads">
                    <progress class="form-control my-1 hide" id="upload_new_students" value="0" max="100"></progress>
                    <p id="error_message_holder_new_student"></p>
                    <button type="button" id="upload_new_students_button" class="">Upload</button>
                </div>
            </div>
            <form class="body row" id="admitform">
                <div class="col-md-6">
                    <p class="titled_sections"><strong> Student information </strong></p>
                    <div class="conts">
                        <label for="surname">Enter surname:<span style = 'color:blue;'>*</span> <span class="examples">eg: Onyango or Kamau </span> <br> </label>
                        <input type="text" class="effect-8" name="surname" id="surname" placeholder="Enter Surname">
                    </div>
                    <div class="conts">
                        <label for="fname">Enter Firstname:<span style = 'color:red;'>*</span> <span class="examples"> eg Ezekiel or Esmond</span><br> </label>
                        <input type="text" name="fname" id="fname" placeholder="Enter Firstname">
                    </div>
                    <div class="conts">
                        <label for="sname">Enter middle name:<span style = 'color:red;'>*</span> <span class="examples"> eg Odongo or Bwire</span><br></label>
                        <input type="text" name="sname" id="sname" placeholder="Enter Middle names">
                    </div>
                    <div class="conts">
                        <label for="dob">Select D.O.B:<span style = 'color:red;'>*</span> <span class="examples"> it accepts dates 2yrs from today</span> <br></label>
                        <input type="date" name="dob" id="dob" max = <?php echo date("Y-m-d",strtotime("-1 years"))?> value = <?php echo date("Y-m-d",strtotime("-2 years"))?>>
                    </div>
                    <div class="conts">
                        <label for="gender">Select gender:<span style = 'color:red;'>*</span><br></label>
                        <select name="gender" id="gender">
                            <option value="" hidden >Select..</option>
                            <option value="Male">Male<i class='fas fa-mars' style='font-size:24px'></i></option>
                            <option value="Female">Female<i class='fas fa-venus' style='font-size:24px'></i></option>
                        </select>
                    </div>
                    <div class="conts">
                        <span id="load_admno"></span>
                        <label for="automated_amd">Admission number: <span style = 'color:red;'>*</span> {<span id="last_admno_holder"></span>} <br></label>
                        <select name="automated_amd" id="automated_amd">
                            <option value="" hidden>Select option:</option>
                            <option value="automate_adm">Auto Generate</option>
                            <option value="insertmanually">Add Manually</option>
                        </select>
                        <div class="conts hide" id="auto_generate">
                            <label for="autogen">Auto generated: <img src="images/ajax_clock_small.gif" id="autogenamds"><br></label>
                            <p class="hide" id="admnogenerated"></p>
                            <input type="text" name="autogen" id="autogen" placeholder= "not generated" readonly>
                        </div>
                        <div class="conts hide" id="man_generate">
                            <label for="mangen">Add admission number manually: <img class="hide" src="images/ajax_clock_small.gif" id="manualassign"><br></label>
                            <input type="text" name="mangen" id="mangen" placeholder= "Enter admission number">
                            <p class="red_notice" id="admgenman"></p>
                        </div>
                    </div>
                    <div class="conts row">
                        <div class="col-md-12">
                            <p class="text-center"><b>Intake</b></p>
                        </div>
                        <div class="col-md-6">
                            <label for="intake_month" class="form-control-label">Intake Month</label>
                            <select name="intake_month" id="intake_month" class="form-control">
                                <option value="" hidden>Select an Option</option>
                                <option value="JAN">JAN</option>
                                <option value="FEB">FEB</option>
                                <option value="MAR">MAR</option>
                                <option value="APR">APR</option>
                                <option value="MAY">MAY</option>
                                <option value="JUN">JUN</option>
                                <option value="JUL">JUL</option>
                                <option value="AUG">AUG</option>
                                <option value="SEP">SEP</option>
                                <option value="OCT">OCT</option>
                                <option value="NOV">NOV</option>
                                <option value="DEC">DEC</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="intake_year" class="form-control-label">Intake Year</label>
                            <select name="intake_year" id="intake_year" class="form-control">
                                <option value="" hidden>Select an Option</option>
                                <?php for($index = date("Y"); $index > 2017; $index--):?>
                                    <option value="<?=$index?>"><?=$index?></option>
                                <?php endfor;?>
                            </select>
                        </div>
                    </div>
                    <div class="conts">
                        <label for="errolment">Course Level:<span style='color:red;'>*</span><br></label>
                        <div id="class_admission"></div>
                    </div>
                    <div class="conts">
                        <label for="course_chosen">Course Enrolled:<span style='color:red;'>*</span><img src="images/ajax_clock_small.gif" class="hide" id="course_list_loader"><br></label>
                        <div id="course_list_holder"><p class="text-secondary">Select Course Level to display available courses!</p></div>
                    </div>
                    <div class="conts">
                        <label for="course_module_terms">Course Module Terms:<img src="images/ajax_clock_small.gif" class="hide" id="course_term_loader"></label>
                        <div id="course_term_holder"><p class="text-secondary">Select Course to display the course terms!</p></div>
                        <input class="d-none" type="text" name="upis" id="upis" placeholder="Unique Personal Identifier">
                    </div>
                    <div class="conts">
                        <label for="bcno">National ID number:</label>
                        <p id="bcnerr"></p>
                        <input type="text" name="bcno" id="bcno" placeholder="Enter BC No">
                    </div>
                    <div class="conts">
                        <label for="address">Student residence: <span class="examples"> eg Kisumu,Kenya or Busia,Kenya</span></label><br>
                        <input type="text" name="address" id="address" placeholder="Area of residence">
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="text-danger"><strong>Parent / Guardian information</strong></p>
                    <!-- The first parent -->
                    <p style="color:maroon;font-size:12px;"><u>Primary Parent / Guardian</u></p>
                    <div class="conts">
                        <label for="parname">Primary Parent name:<span style = 'color:blue;'>*</span> <span class="examples"> eg Esmond Bwire</span></label><br>
                        <input type="text" name="parname" id="parname" placeholder="Parent name">
                    </div>
                    <div class="conts">
                        <label for="parconts">Primary Parent contacts:<span style = 'color:blue;'>*</span> <span class="examples"> eg 0712345678 (Kenyan only) </span></label><br>
                        <p id="parerr"></p>
                        <input type="number" name="parconts" id="parconts" placeholder="Kenyan contacts only">
                    </div>
                    <div class="conts">
                        <label for="parrelation">Primary Parent relationship:<span style = 'color:blue;'>*</span> <br></label>
                        <select name="parrelation" id="parrelation">
                            <option value="" hidden >Select..</option>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Guardian">Guardian</option>
                        </select>
                    </div>
                    <div class="conts">
                        <label for="pemail">Primary Parent email: <span class="examples"> eg esmond@gmail.com</span></label>
                        <p id="emailerr"></p>
                        <input type="email" name="pemail" id="pemail" placeholder = "Enter email">
                    </div>
                    <div class="conts">
                        <label for="parent_accupation1">Primary Parent`s Occupation</label>
                        <input type="text" name="parent_accupation1" id="parent_accupation1" placeholder="e.g Teacher, Doctor" class="form-control">
                    </div>
                    <hr class="p-0">
                    <p style="color:maroon;font-size:12px;"><u>Secondary Parent / Guardian</u></p>
                    <!-- The second parent -->
                    <div class="conts">
                        <label for="parname2">Secondary Parent name: <span class="examples"> eg Esmond Bwire</span></label><br>
                        <input type="text" name="parname2" id="parname2" placeholder="Parent name">
                    </div>
                    <div class="conts">
                        <label for="parconts2">Secondary Parent contacts: <span class="examples"> eg 0712345678 (Kenyan only) </span></label><br>
                        <p id="parerr"></p>
                        <input type="number" name="parconts2" id="parconts2" placeholder="Kenyan contacts only">
                    </div>
                    <div class="conts">
                        <label for="parrelation2">Secondary Parent relationship: <br></label>
                        <select name="parrelation2" id="parrelation2">
                            <option value="" hidden >Select..</option>
                            <option value="Mother">Mother</option>
                            <option value="Father">Father</option>
                            <option value="Guardian">Guardian</option>
                        </select>
                    </div>
                    <div class="conts">
                        <label for="pemail2">Secondary Parent email: <span class="examples"> eg esmond@gmail.com</span></label>
                        <p id="emailerr"></p>
                        <input type="email" name="pemail2" id="pemail2" placeholder = "Enter email">
                    </div>
                    <div class="conts">
                        <label for="parent_accupation2">Secondary Parent`s Occupation</label>
                        <input type="text" name="parent_accupation2" id="parent_accupation2" placeholder="e.g Teacher, Doctor" class="form-control">
                    </div>
                    <div class="conts">
                        <p id="erroradm"></p>
                    </div>
                </div>
            </form>
            <div class="bottom">
                <button type="button" id="submitbtn">Submit</button>
                <button type="button" id="resetadmitform">Reset</button>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>

</div>