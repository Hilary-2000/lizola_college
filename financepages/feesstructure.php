<div class="contents animate hide" id="feestructure">
    <div class="titled">
        <h2>Finance</h2>
    </div>
    <div class="admWindow">
        <div class="top1">
            <div class="row">
                <div class="col-md-9">
                    <p>Fees structure</p>
                </div>
                <div class="col-md-3">
                    <span id="fees_structure_tutorial" class="link"><i class="fas fa-play"></i> Tutorial</span>
                </div>
            </div>
        </div>
        <div class="middle1">
            <div class="instructions">
                <p><strong>Enlightenment:</strong></p>
                <p>- At this window you get to view the fees structure that the system uses to pay fees.</p>
                <p>- Changes to the fees structure once a year is highly recomemnded so that the dignity of the system is upheld.</p>
                <p><br> <strong> <?php if (isset($_SESSION['schoolname'] )) { echo $_SESSION['schoolname']; }else {echo "School Name"; }?> </strong>current fees structure. <br>Click button bellow to display </p>
                <div class="conts" style="width:180px">
                    <label for="daros"><b>Select class</b></label>
                    <div id='fees_struct_class'></div>
                    <label for="search_fees_course_list"><b>Select course</b> <img class="hide" src="images/ajax_clock_small.gif" id="show_course_list_loader"></label>
                    <div id="search_fees_window_course"><small class="text-secondary">If course level is selected, course list will appear here!</small></div>
                    <button id='showfeesstructure'>Show fees structure</button>
                    <div class="conted">
                        <button id='add_expense'><i class='fa fa-plus'></i> Add Fees</button>
                        <button id="print_structure" class="hide" type='button'><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
                <p class="hide" id="removeer_fees"></p>
            </div>
            <div class="conts">
                <p id='displayfin'></p>
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>