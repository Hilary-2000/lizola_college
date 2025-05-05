<div class="contents animate hide" id="managesubanteach">
    <div class="titled">
        <h2>Academics</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Manage teacher and subjects</p>
        </div>
        <div class="middle1">
            <div class="conts">
                <p><strong>Information</strong></p>
                <p>- At this window you are previledged to alter the subject each teacher teaches.</p>
                <p>- When altering the subject taught by the respective teacher, previledges the teacher has over the subject can be removed or assigned to them. </p>
            </div>
            <div class="body4">
                <div class="row">
                    <div class="col-md-9">
                        <label for="option_ed form-control-label">Start of by either of the following option : <br></label>
                        <select name="option_ed" class="form-control" id="option_ed">
                            <option value="" hidden>Select an option</option>
                            <option value="finding_a_tr">Finding a teacher</option>
                            <option value="displaying_all_trs">Displaying all the teachers</option>
                        </select>
                        <form class="boddy hide" id='searchteach'>
                            <label for="opt12" class="form-control-label">Select option: <br></label>
                            <select name="opt12" class="form-control" id="opt12">
                                <option value="" hidden >Select...</option>
                                <option value="byname">By name</option>
                                <option value="byidno">By I`d Number</option>
                            </select>
                            <div class="conts" id="">
                                <div class="conts hide" id="trnames">
                                    <label for="nameds" class="form-control-label">Enter Teacher`s name: <br></label>
                                    <input type="text" name="nameds" class="form-control" id="nameds" placeholder = "Enter teacher name">
                                </div>
                                <div class="conts hide" id="tridnum">
                                    <label for="idnumbers" class="form-control-label">Enter I`d number: <br></label>
                                    <input type="number" class="form-control" name="idnumbers" id="idnumbers" placeholder = "Enter I`d number">
                                </div>
                                <div class="btns">
                                    <button type='button' id='findersd' >Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <p class="block_btn" id="subject_selections"><i class=" fa fa-pen"></i> Subject Selection</p>
                    </div>
                </div>
                <hr>
                <div class="conts" id = "viewsubinformations">
                    <p id ='managesubsteacherr'></p>
                    <p id='managesubstr'></p>
                </div>
                <div class="boddy1">
                    <div class="boddy3 hide" id='editsubinfor'>
                        <div class="conts">
                            <h6 style='text-align:center;'>Edit Teacher subjects</h6>
                        </div>
                        <p class='hide' id='tids'></p>
                        <div class="conts">
                            <label for="teachname" class="form-control-label" >Teacher name: <br></label>
                            <input type="text"  class="form-control"  name="teachname" id="teachname" readonly placeholder = "Teacher name">
                        </div>
                        <div class="conts">
                            <label  class="form-control-label" for="">Subjects Taught: <br></label>
                            <div class="conts" id = 'outputsubs'>
                                <p style='font-size:20px;margin-left:10px;color:rgb(165, 42, 42);'>No subjects loaded yet</p>
                            </div>
                        </div>
                        <div class="conts">
                            <button type='button' id ='backtosubs' >Back</button>
                            <button type='button' id='addsubsbutn' >Add subject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>