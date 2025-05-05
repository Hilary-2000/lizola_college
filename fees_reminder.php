<div class="contents printer_page hide" id = "fees_reminders">
    <div class="fee_reminder" id = "print_reminded">
        <div class="printable_page">
            <div class="page_titles">
                <h2><?php echo $_SESSION['schoolname']; ?></h2>
                <p>P.O BOX 853 - 50400 (Nambale)</p>
                <h4><?php echo "Motto: ".$_SESSION['schoolmotto']; ?></h4>
            </div>
            <div class="student_data">
                <p><strong>Student Name:</strong> Hilary Ngige Adala</p>
                <p><strong>Student Id:</strong> 14</p>
                <p><strong>Student Class:</strong> Class 6</p>
            </div>
            <div class="message_remider">
                <p>Dear Parent, <br>You are kindly reminded to clear your fee arrears of Kes <strong>85,000</strong> by date given. <br><br></p>
                <p class="mumify"> <strong> Yours Failthfully <br>Headteacher, <br> <?php echo $_SESSION['schoolname']; ?></strong></p>
            </div>
        </div>
    </div>
    <div class="btns">
        <button type="button" onclick = "printFeesReminded()">Print</button>
        <button type="button" onclick = "closeWinB()">Cancel</button>
    </div>
</div>