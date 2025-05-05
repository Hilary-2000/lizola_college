<div class="contents animate " id='adminsdash'>
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
            <span><img class="images" src="images/dp.png" id="admin_admin_dp" alt="userimg"></span>
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
            <p>Below I have summarized some infomation of what you might need to know.</p>
        </div>
    </div>
    <div class="cardholder">
        <div class="cards">
            <div class="conted">
                <p><strong>Total number of students:</strong></p>
            </div>
            <div class="conted">
                <p id='students'>0 student(s)</p>
            </div>    
            <div class="conted">
                <p><a href="#" id="admin_students">More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Total number of registered users</strong></p>
            </div>
            <div class="conted">
                <p id="studpresenttoday" >0 User(s)</p>
            </div>
            <div class="conted">
                <p><a href="#" id="my_employees">More..</a></p>
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
            <button type='button' id="view_logs">View logs</button>
            </div>
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Transfered Students:</strong></p>
            </div>
            <div class="conted">
                <p id='transfered_stud2'>0 Student(s)</p>
            </div>    
            <div class="conted">
                <!-- <p><a href="">More..</a></p> -->
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Alumni:</strong></p>
            </div>
            <div class="conted">
                <p id='alumnis_number2'>0 ALumni(s)</p>
            </div>    
            <div class="conted">
                <!-- <p><a href="">More..</a></p> -->
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong> Number of students present in school today(Roll call):</strong></p>
            </div>
            <div class="conted">
                <p id="rollcalnumber">0 student(s)</p>
            </div>    
            <div class="conted">
                <!--<p><a href="">More..</a></p>-->
            </div>    
        </div>
        <!--<div class="cards">
            <div class="conted">
                <p><strong>In-active users:</strong></p>
            </div>
            <div class="conted">
                <p id='inactive'>10 User(s)</p>
            </div>    
            <div class="conted">
                <p><a href="">More..</a></p>
            </div>    
        </div>
        <div class="cards">
            <div class="conted">
                <p><strong>Logs:</strong></p>
            </div>
            <div class="conted">
                <button type='button'>View..</button>
            </div> 
        </div>-->

    </div>

</div>