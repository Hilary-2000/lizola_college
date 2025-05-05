<div class="contents animate " id="htdash">
    <div class="welcome">
        <div class="name_n_icons">        
            <h2>Welcome back  <?php if(isset($_SESSION['fullnames'])){
                $salute = "";
                if($_SESSION['gen']=='M'){
                    $salute = 'Mr. ';
                }elseif ($_SESSION['gen'] == 'F') {
                    $salute = 'Mrs. ';
                }else{
                    $salute = "";
                }
                $named = explode(" ",$_SESSION['fullnames']);
                echo $salute.$named[0];
            }else {
                            echo "Username ";
                        }?> </h2>
            <span><img class="images" src="images/dp.png" id="ht_dp_img" alt="userimg"></span>
        </div>

        <div class="contedd">
            <p>You are logged in as 
                <?php 
                    if(isset($_SESSION['auth'])){
                        $auth = $_SESSION['auth'];
                        $data = "";
                        if ($auth == 0) {
                            $data .= "<b>". "System Administrator"."</b>";
                        }else if ($auth == "1"){
                            $data .= "<b>". "Principal"."</b>";
                        }else if ($auth == "2"){
                            $data .= "<b>". "Deputy Principal Academics"."</b>";
                        }else if ($auth == "3"){
                            $data .= "<b>". "Deputy Principal Administration"."</b>";
                        }else if ($auth == "4"){
                            $data .= "<b>". "Dean of Students"."</b>";
                        }else if ($auth == "5"){
                            $data .= "<b>". "Finance Office"."</b>";
                        }else if ($auth == "6"){
                            $data .= "<b>". "Human Resource Officer"."</b>";
                        }else if ($auth == "7"){
                            $data .= "<b>". "Head of Department"."</b>";
                        }else if ($auth == "8"){
                            $data .= "<b>". "Trainer/Lecturer"."</b>";
                        }else if ($auth == "9"){
                            $data .= "<b>". "Admissions"."</b>";
                        }else {
                            $data .= "<b>". ucwords(strtolower($auth))."</b>";
                        }
                        echo $data;
                    }else{
                            echo "Login to proceed";
                    }
                ?>
            </p>
            <p>Welcome to your dashboard <br>Use the navigation bar on your left to select a task you want to carry out!</p>
            <p>Below I have summarized infomation of what you might need to know</p>
        </div>
    </div>
    <div class="cardholder">
        <div class="cards">
            <div class="conted">
                <p><strong>Total number of students :</strong></p>
            </div>
            <div class="conted">
                <p id="studentscount">0 student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="#" id='totalstuds'>More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Number of students registered today:</strong></p>
            </div>
            <div class="conted">
                <p id = "studentscounttoday">0 Student(s) </p>
            </div>    
            <div class="conted">
                <p><a href="#" id='regtoday'>More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong> Number of students present in school today(Roll call):</strong></p>
            </div>
            <div class="conted">
                <p id ="studpresenttoday">0 student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="#" id='prestoday' >More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Students absent</strong></p>
            </div>
            <div class="conted">
                <p id ='absentstuds'>0 Student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="#regs" id='studentabs'>More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Transfered Students:</strong></p>
            </div>
            <div class="conted">
                <p id='transfered_studs'>0 Student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="">More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Alumni:</strong></p>
            </div>
            <div class="conted">
                <p id='alumnis_number'>0 Student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="">More..</a></p>
            </div>    
        </div>
        <!--<div class="cards">
            <div class="conted">
                <p><strong> Todays expense recorded:</strong></p>
            </div>
            <div class="conted">
                <div class="top">
                    <div class="block" id="shwexpense"></div>
                    <p>Ksh: 1200</p>
                </div>
                <div class="bottom">
                    <input type="button" id="showexpenses" value="Show">
                </div>
            </div>    
            <div class="conted">
                <p><a href="#">More..</a></p>
            </div>    
        </div>-->
        <div class="cards">
            <div class="conted">
                <p><strong>School fees recieved today:</strong></p>
            </div>
            <div class="conted">
                <div class="top">
                <div class="block" id="hidefees"></div>
                    <p id="schoolfeesrecieved">Ksh: 12000</p>
                </div>
                <div class="bottom">
                <button type="button" id="showfees" ><span id='se_e'><i class="fa fa-eye"></i></span> <span class='hide' id='unse_e'><i class="fa fa-eye-slash"></i></span></button>
                </div>
            </div>    
            <div class="conted">
                <p><button href="#" id='schoolfee'>More..</button></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Active users now: </strong></p>
            </div>
            <div class="conted">
                <p id="activeusers">0 User(s)</p>
            </div>
            <div class="conted"> 
                <p id="check_logs"><a href="#" >Check logs</a></p>
            </div>
        </div>
        
        <div class="cards">
            <div class="conted">
                <p><strong>Active exams:</strong></p>
            </div>
            <div class="conted">
                <p><span id = "active_examination">0</span> : exam(s)</p>
            </div>    
            <div class="conted">
                <p id="view_active_exams"><a href="#">More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Subjects I teach :</strong></p>
            </div>
            <div class="conted">
                <p><span id = "my_subjects">1</span> : Subject(s)</p>
            </div>    
            <div class="conted">
                <p id="view_my_subs"><a href="#my_information_inner">More..</a></p>
            </div>    
        </div>
    </div>

</div>