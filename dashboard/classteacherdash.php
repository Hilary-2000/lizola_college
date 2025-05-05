<div class="contents animate " id="ctdash">
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
            <span><img class="images" src="images/dp.png" id="ct_admin_dp" alt="userimg"></span>
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
            <p>Below I have summarized infomation of what you might need to know.</p>
        </div>
    </div>
    <div class="cardholder">
        <div class="cards">
            <div class="conted">
                <p><strong>Total number of students in My class:</strong></p>
            </div>
            <div class="conted">
                <p id='studclass'>20 student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="#" id="my_students_populate">More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Number of students registered today in my class:</strong></p>
            </div>
            <div class="conted">
                <p id="reg_tod_mine">2 Student(s) </p>
            </div>
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong> Number of students present in class today(Roll call):</strong></p>
            </div>
            <div class="conted">
                <p id="my_att_clas">18 student(s)</p>
            </div>    
            <div class="conted">
                <!--<p><a href="">More..</a></p>-->
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Register roll call:</strong></p>
            </div> 
            <div class="conted">
                <button type="button" id='callrollcall'>Register..</button>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Students absent in my class:</strong></p>
            </div>
            <div class="conted">
                <p id="my_absent_list">1 Student(s)</p>
            </div>    
            <div class="conted">
                <!--<p><a href="">More..</a></p>-->
            </div>    
        </div>
        <!--<div class="cards">
            <div class="conted">
                <p><strong>Students absent without permission</strong></p>
            </div>
            <div class="conted">
                <p>2 Student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="">More..</a></p>
            </div>    
        </div>-->
        <div class="cards">
            <div class="conted">
                <p><strong>My Timetable:</strong></p>
            </div> 
            <div class="conted">
                <button type="button" id="view_my_tt">View..</button>
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
                <p id="view_my_subs"><a href="#">More..</a></p>
            </div>    
        </div>
        <!--<div class="cards">
            <div class="conted">
                <p><strong>Todays activities:</strong></p>
            </div>
            <div class="conted">
                <p><ul>
                    <p>Scout camping</p>
                    <p>Drama training</p>
                    <p>Music training</p>
                </ul></p>
            </div>    
            <div class="conted">
                <p><a href="">More..</a></p>
            </div>    
        </div>-->
    </div>

</div>