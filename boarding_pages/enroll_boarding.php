<div class="contents animate hide" id="enroll_boarding">
    <div class="titled">
        <h2>Boarding</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Enroll Boarding</p>
        </div>
        <div class="middle1">
            <div class="conts" style="border-bottom:1px dashed black;">
                <div class="conts">
                    <p><strong>Information:</strong></p>
                    <p>- At this window you are previledged to assign boarders a dormitory.</p>
                    <p>- Start by searching for a students who are enrolled for boarding.</p>
                </div>
                <div style="border-top:1px dashed black;padding:10px 0;margin-top:10px;font-size:14px; display:flex; flex-direction:column;align-items:center;" class="conts">
                    <label style="font-weight:600;" for="admission_number">Enter Student`s admission number: <br></label>
                    <input type="text" style="margin:0;"  name="admission_number" id="admission_number" placeholder = "Search Adm number">
                    <button id ="display_unenrolled"  type="button">Search</button>
                    <p style="text-align:center;" id="err_handler_enroll"></p>
                </div>
                <div class="conts">
                    <p class="link" id="display_all_present">Display students available for enrollment</p>
                </div>
            </div>
            <div class="conts">
                <p id="unenrolled_student_list">
                    <!--<table>
                        <tr>
                            <th>Adm no</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Select dormitory</th>
                            <th>Save</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Esmond Bwire</td>
                            <td>F</td>
                            <td ><select name='dormitory' id='dormitory'>
                                <option value='' hidden>Select</option>
                                <option value='' >Mt sinai</option>
                                <option value='' >Mt Kilimanjaro</option>
                                <option value='' >Mt Longonot</option>
                            </select> <p style='color:green;'>Enrolled ✔</p> </td>
                            <td><button style='margin:0;'>Save</button></td>
                        </tr>
                    </table>-->
                </p>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>