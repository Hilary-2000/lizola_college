<div class="contents animate hide" id="loggers_page">
    <div class="titled">
        <h2>Logs</h2>
    </div>
    <div class="admWindow ">
        <div class="top1">
            <p>Logs</p>
        </div>
        <div class="middle1">
            <div class="logs_information">
                <p>At this window you are previledged to view user logs: They include</p>
                <p>- User log in time <br>- User logout time.<br>- User active hours</p>
            </div>
            <div class="logs_search">
                <label for="date_logs">Search for logs <br></label>
                <input type="date" name="date_logs" id="date_logs" value = <?php echo date("Y-m-d",strtotime("3 hour"));?> max = <?php echo date("Y-m-d",strtotime("3 hour"));?>>
                <button type='button' id="display_loggers">Display logs</button>
            </div>
            <div class="logs_contents">
                <label>Active users: <br></label>
                <p>The list displayed below shows the users who have logged in the system on the respective date:</p>
                <img src="images/ajax_clock_small.gif" class="hide" id="logger_clock">
                <p id='loggers_table_before'></p>
                <p id="loggers_table"></p>
                <!--<table style="margin-left:10px;">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>last time login</th>
                        <th>Active</th>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td>Hillary Ngige</td>
                        <td>19:00:43</td>
                        <td class="bg_green">Active</td>
                    </tr>
                </table>-->
            </div>
        </div>
        <div class="bottom1">
            <p>Managed by Ladybird</p>
        </div>
    </div>
</div>