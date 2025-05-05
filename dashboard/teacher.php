<div class="contents animate " id="tr_dash">
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
            <span><img class="images" src="images/dp.png" id="tr_dash_dp" alt="userimg"></span>
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
                <p><strong>Active exams:</strong></p>
            </div>
            <div class="conted">
                <p><span id = "active_examination">0</span> : exam(s)</p>
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
                <p><a href="#">More..</a></p>
            </div>    
        </div>-->
    </div>

</div>