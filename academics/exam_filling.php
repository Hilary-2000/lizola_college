<div class="contents animate hide" id="exam_fillings">
    <div class="titled">
        <h2>Academic</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <p>Student Marks Entry and viewing</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p> <strong>Note:</strong><br>- Only active exams will appear when you record student marks and the subjects that you teach</p>
                <p>- Changes of exams can only be done if the exam is still active.</p>
                <p>- Start by selecting either of the three options</p>
                <p>- The results displayed by the system will only be accurate if the all the student marks are filled and have the same grading system</p>
            </div>
            <div class="body4" style='border-bottom:1px dashed rgb(25, 113, 137);' id="finded">
                <div class="conts">
                    <label class="form-control-label" for="option_exams">Select an option: <br></label>
                    <select name="option_exams" class="form-control" id="option_exams">
                        <option value="" hidden>Select..</option>
                        <option value="view_exams">View Marks per subject</option>
                        <!-- <option value="view_per_class">View per class</option> -->
                        <option value="fill_in_exams">Record student marks</option>
                    </select>
                </div>
                <div class="exam_fill" id="exam_fill">
                    <!--Exam list-->
                    <div class="conts hide" style="margin-top:10px;" id="exam_select">
                        <select name="exam_list" id="">
                            <option value="" hidden>Select an option..</option>
                        </select>
                    </div>
                    <!--Subject list-->
                    <div class="conts hide" id="subject_list">
                        <select name="exam_list" id="">
                            <option value="" hidden>Select an option..</option>
                        </select>
                    </div>
                    <!--Class list-->
                    <div class="conts hide" id="classes_list">
                        <select name="exam_list" id="">
                            <option value="" hidden>Select an option..</option>
                        </select>
                    </div>
                    <div class="conts hide" id="grading_methods">
                        <label class="form-control-label" for="grade_mode">Select grading method: <br></label>
                        <select class="form-control" name="grade_mode" id="grade_mode">
                            <option value="" hidden>Select an option</option>
                            <option value="cbc">C.B.C</option>
                            <option value="844">8-4-4</option>
                            <option value="IGCSE">IGCSE</option>
                            <option value="iPrimary">iPrimary</option>
                        </select>
                    </div>
                    <div class="conts">
                        <p id="exma_record_err"></p>
                    </div>
                    <div class="hide" id="btn_panel">
                        <button type="button" id = "populate_btn">Populate</button>
                    </div>
                </div>
                <div class="view_exams" id="view_exams_record">
                    <div class="term_select" id="select_term">
                    </div>
                    <div class="exam_attempt" id="exam_attempt">
                    </div>
                    <div class="subjects_done" id="subjects_done">
                    </div>
                    <div class="class_sitters" id="class_sitters">
                    </div>
                    <button class="hide" type="button" id="display_results">Display</button>
                    <p id="error_handlers"></p>
                </div>
                <div class="view_exams" id="view_exams_class_record">
                    <div class="term_select" id="select_one_term">
                    </div>
                    <div class="term_select" id="select_one_exams">
                    </div>
                    <div class="term_select" id="select_one_class_siting">
                    </div>
                    <div class="term_select hide" id="display_btns">
                        <button type='button' id = "display_results_per_class">Display</button>
                    </div>
                    <div class="term_select">
                        <p id="view_subjects_err"></p>
                    </div>
                </div>
            </div>
            <div class="conts hide"  id="resulters">
                <div class="conts" id="record_exams_id">
                    <!--<div class="table_fill">
                        <div class="table_header">
                            <div class="td">
                                <p>No</p>
                                <p>Student Name</p>
                                <p>Subject Marks</p>
                                <p>Subject Grade</p>
                                <p>Option</p>
                            </div>
                        </div>
                        <div class="table_body">
                            <div class="table_row">
                                <div class="td">
                                    <p>1. </p>
                                    <p>Esmond Bwire</p>
                                    <span>
                                        <select name="jj" id="jj" id="jj">
                                            <option value="" hidden>---</option>
                                            <option value="4">4</option>
                                            <option value="3">3</option>
                                            <option value="2">2</option>
                                            <option value="1">1</option>
                                            <option value="A">A</option>
                                        </select>
                                        <div class="imagers hide" id="imager1">
                                            <img src="images/load2.gif" alt="loading">
                                        </div>
                                        <div class="imagers hide" id="imager2">
                                            <img src="images/check.gif" alt="loading">
                                        </div>
                                        <div class="imagers hide" id="imager3">
                                            <img src="images/check2.jpg" alt="loading">
                                        </div>
                                    </span>
                                    <p>A</p>
                                    <button type="button" id="savers">Save</button>
                                </div>
                            </div>
                            <div class="table_row">
                                <div class="td">
                                    <p>2. </p>
                                    <p>Ann Akotch</p>
                                    <p>97</p>
                                    <p>A</p>
                                    <button type="button">Change</button>
                                </div>
                            </div>
                            <div class="table_row">
                                <div class="td">
                                    <p>3. </p>
                                    <p>Owen Malingu</p>
                                    <input type="number" name="" id="" placeholder ="Enter Marks">
                                    <p>A</p>
                                    <button type="button" >Save</button>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
                <div class="conts" id="display_result">
                </div>
                <div class="conts" id="display_class_result">
                    <div class="tableHolder">
                        <table>
                            <tr>
                                <th>Subject 1</th>
                                <th>Subject 2</th>
                                <th>Subject 3</th>
                                <th>Subject 4</th>
                                <th>Subject 5</th>
                                <th>Subject 6</th>
                                <th>Subject 7</th>
                                <th>Subject 8</th>
                                <th>Subject 9</th>
                            </tr>
                            <tr>
                                <td>maths</td>
                                <td>Eng</td>
                                <td>Kisw lu</td>
                                <td>Kisw Ish</td>
                                <td>Science</td>
                                <td>Social studies</td>
                                <td>Cre</td>
                                <td>SST/CRE</td>
                                <td>Biology</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="btns">
                    <button class="" id="go_back" >Back</button>
                    <button type="button" id="print_results"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>