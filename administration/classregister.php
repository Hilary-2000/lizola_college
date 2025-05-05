<div class="contents animate hide" id="classregister">
    <div class="titled">
        <h2>Student Attendance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Call register</p>
                </div>
                <div class="col-md-3">
                    <span id="student_attendance_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
            <input type="text"  id="myname" hidden value = <?php if(isset($_SESSION['username'])){ echo $_SESSION['username']; } ?> >
        </div>
        <div class="middle1">
            <div class="register" id='mains'>
                <div class="registerbodytop rounded-lg p-2">
                    <div class="conts">
                        <p><strong>Note:</strong></p>
                        <p><i>Always confirm that youve checked the students present before submitting.</i></p>
                        <p><i>After submitting the attendance you can view the attendance list.</i></p>
                        <input type="hidden" name="" id="hidden_class_selected">
                    </div>
                    <div class="conts ">
                        <p style=''>You are logged in as <?php 
                        if(isset($_SESSION['auth'])){
                            $authority = $_SESSION['auth'];
                        $data ="";
                        $class = '0';
                        $my_class = "0";
                        $classasigned = '0';
                        if($authority==0){
                            $data.="<b>admin</b></p>";
                        }elseif ($authority==1) {
                            $data.="<b> Headteacher</b>";
                            $data.="<br>You can select any class to call register";
                        }elseif ($authority ==2) {
                            $data.="<b> Teacher</b>";
                            $data.="Until a class teacher assigns you a class is when you may call register";
                        }elseif ($authority == 3) {
                            $data.="<b> Deputy principal</b>";
                            $data.="<br>You can select any class to call register";
                        }elseif ($authority == 4) {
                            $data.="<b> Staff</b>";
                        }elseif ($authority == 5) {
                            
                            $data.="<b> Class teacher</b>";
                            $data.="<br>Your class assigned is ";
                            if(isset($_SESSION['class_taughts'])){
                                $datas = "Class ".$_SESSION['class_taughts'];
                                $my_class = $_SESSION['class_taughts'];
                                if (strlen($_SESSION['class_taughts'])>1) {
                                    $datas = $_SESSION['class_taughts'];
                                }
                                $data.="<b>".$datas."</b>";
                                $class = $_SESSION['class_taughts'];
                            }else {
                                $data.="Nan";
                            }
                            $data.="";
                            $data.="<br>Select the date you want to call register and click the <b>display</b> button to display your students";
                        }elseif ($authority == 6) {
                            $data.="<b> Student</b>";
                        }
                        echo $data;}else{
                            echo "Login to proceed";
                        }?></p>
                        <div class="option1 hide" id="class_tr_onl">
                            <label for="class_register_dates_cltr" class="form-control-label"><b>Select Date</b></label>
                            <input type="date" class="form-control" id="class_register_dates_cltr" value="<?php echo date("Y-m-d")?>" max="<?php echo date("Y-m-d")?>">
                            <label class="form-control-label" for="classselected"><b>Click to display: </b></label>
                            <input type="hidden" class="hide" id="classselected"  value = "<?php echo $my_class;?>" placeholder="Class assigned" readonly>
                            <button type='button' id="show_class_att">Display</button>
                        </div>
                    </div>
                    <div style="border-bottom:1px dashed black;padding-bottom:10px;" class="classoptions" id="class_tr_only">
                        <div class="options2">
                            <label class="form-control-label" for="optd"><b>Select an option: </b><br></label>
                            <select class="form-control w-25" style="max-width:150px;" name="optd" id="optd">
                                <option value="" hidden>Select...</option>
                                <option value="callreg">Call registers</option>
                                <option value="view_attendance" id="view_atts">View attendance</option>
                                <option value="specific_student" id="view_specific">View attendance specific student</option>
                            </select>
                        </div>
                        <div class="option2 hide" id="moreopt">
                            <label for="class_register_dates" class="form-control-label"><b>Select Date</b></label>
                            <input type="date" class="form-control" id="class_register_dates" value="<?php echo date("Y-m-d")?>" max="<?php echo date("Y-m-d")?>">
                            <label class="form-control-label" for="selectclass"><b>Select class: </b><br></label>
                            <p id="class_register_class"></p>
                            <button class="btn btn-secondary btn-sm" id="display_student_attendances" type="button">Display</button>
                        </div>
                        <div class="option2 hide" id="moreopt2">
                            <label class="form-control-label" for="sel_att_date"><b>Select date: </b><br></label>
                            <input class="form-control" type="date" id="sel_att_date" value = <?php echo date("Y-m-d", strtotime("3 hour"));?> max = <?php echo date("Y-m-d", strtotime("3 hour"));?> ><br>
                            <p id="err_date_handled"></p>
                            <button type="button" id="display_attendance_class"> Display</button>
                        </div>
                        <div class="option2 hide" id="moreopt3">
                            <label class="form-control-label" for="students_admnos_in"><strong>Student Reg No: </strong></label>
                            <div class="autocomplete">
                                <input class="form-control my-1" type="text" id="students_admnos_in" placeholder="Student Ids">
                            </div>
                            <label class="form-control-label my-0" for="select_months_attendance"><strong>Select Month: </strong></label>
                            <input class="form-control w-25 my-1" value="<?php echo date("Y-m");?>" type="month" id="select_months_attendance">
                            <img src="images/ajax_clock_small.gif" class="hide" id="select_student_clock">
                            <button type="button" id="display_attendance_class_specific"> Display</button>
                        </div>
                    </div>
                </div>
                <div class="registerbody" id='tableinformation'>
                    
                </div>
                <div class="container hide"  id ="register_btns">
                    <div class="btns">
                        <button type='button' id='submitclasspresent'>Submit</button>
                        <button type='button' id='viewpresent'>View attendance</button>
                    </div>
                </div>
            </div>
            <div class="container hide" id="attendance_register_one_student">
                <div class="container" id="display_student_attendance">
                </div>
            </div>
            <div class="view hide" id="view_attendances">
                <div class="conts">
                    <p style=''>You are logged in as <?php 
                    if(isset($_SESSION['auth'])){
                        $authority = $_SESSION['auth'];
                    $data ="<p>";
                    $class = '0';
                    $classasigned = '0';
                    if($authority==0){
                        $data.="<b>admin</b></p>";
                    }elseif ($authority==1) {
                        $data.="<b> Headteacher</b>";
                        $data.="<br>You can select any class to call register</p>";
                    }elseif ($authority ==2) {
                        $data.="<b> Teacher</b>";
                        $data.="You are not the class teacher.<br>Until a class teacher assigns you a class is when you may call register</p>";
                    }elseif ($authority == 3) {
                        $data.="<b> Deputy principal</b>";
                        $data.="<br>You can select any class to call register</p>";
                    }elseif ($authority == 4) {
                        $data.="<b> Staff</b></p>";
                    }elseif ($authority == 5) {
                        $data.="<b> Class teacher</b>";
                        $data.="Your class has been selected for you</p>";
                    }elseif ($authority == 6) {
                        $data.="<b> Student</b></p>";
                    }
                    echo $data;}else{
                        echo "Login to proceed";
                    }?></p>
                    <div class="container">
                        <p style='padding-left:20px;'>At this window there are two tables. <br>1. The first table shows the student present <br>2. The second table shows student absent </p>
                        <label style="font-size:14px;color:black; font-weight:600;">Select a date to show attendance: <br></label>
                        <input type="date" style="max-width:150px;" name="date_selected" id="date_selected" value = <?php echo date("Y-m-d", strtotime("3 hour"));?>  max =<?php echo date("Y-m-d",strtotime("3 hour"));?> >
                        <br>
                        <div class="conts">
                            <button type="button" id="display_attendance">Display</button>
                        </div>
                    </div>
                </div>
                <div class="informationtoview" id="atendanceinfor">
                </div>
                <div class="buttons">
                    <button id ='backtosearch'><i class="fas fa-arrow-left"></i> Back</button>
                </div>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>