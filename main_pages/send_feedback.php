<div class="contents animate hide" id="send_feed_page">
    <div class="titled">
        <h2>Send feedback</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Send us a feedback</p>
        </div>
        <div class="middle1">
            <div class="conts" >
                <div class="school_logo">
                    <img src="images/feedback.png" id="" alt="">
                </div>
                <div class="conts" style="text-align:center;border-bottom:1px dashed black;">
                </div>
            </div>
            <div class="setting_s">      
                <p><?php
                    $date = date("H");
                    if($date<=10){
                        echo "Good morning, ";
                    }elseif ($date>10 && $date<=13) {
                        echo "Hello, ";
                    }elseif ($date > 13 && $date <= 20) {
                        echo "Good evening, ";
                    }elseif ($date>20) {
                        # code...
                    }
                    if(isset($_SESSION['fullnames'])){
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
                        }?> <br>- We will really appreciate your feedback, suggestions and opinions.</p>
                        <p>- Sending the feedback is completely anonymus, either positive or negative we will gladly appreciate and use it to improve your experience.</p>
                <label for="class_list">Enter your feedback below: <img src="images/ajax_clock_small.gif" class = "hide" id="feedback-clock"><br></label>
                <p style="color:gray;font-size:12px;"><span id="count_char">0</span>/500 Characters</p>
                <textarea name="suggestion_box" id="suggestion_box" cols="35" rows="10" placeholder = "Type here 500 characters maximum"></textarea>
                <p id="err_handlered"></p>
                <button type="button" id="send-feedback_btns">Send feedback</button>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>