<div class="contents printer_page hide" id='printer_page' >
    <?php include("comma.php");?>
    <div class="print_main_page" id="fees_reciept">
        <div class="print_page" >
            <div class="schoollogo">
                <img src="images/board.jpg" alt="Logos" id="sch_logods">
                <h2><?php echo $_SESSION['schoolname']; ?></h2>
                <h3>School motto: <?php echo $_SESSION['schoolmotto']; ?></h3>
                <p>P.O BOX <?php echo $_SESSION['po_boxs']." - ". $_SESSION['box_codes'];?></p>
                <h3 style="color:brown;font-size:12px;">Fees Payment reciept</h3>
            </div>
            <div class="student_details">
                <div class="labels">
                    <p><span>Student id</span></p>
                    <p><span>Student Name</span> </p>
                    <p><span>Date of transaction </span></p>
                    <p><span>Time of transaction </span></p>
                </div>
                <div class="data_labels">
                    <p id="student_adm_no">: 12</p>
                    <p id="students_jina">: Hillary Ngige Adala</p>
                    <p>: <?php echo date("Y-m-d");?></p>
                    <p>: <?php echo date("H:i:s");?></p>
                </div>
            </div>
            <div class="payment_details">
                <!-- <table>
                    <tr>
                        <th>Transaction Code:</th>
                        <td>HKLJKJLKK</td>
                    </tr>
                    <tr>
                        <th>Mode of payment</th>
                        <td>Cash</td>
                    </tr>
                    <tr>
                        <th>Recieved Amount</th>
                        <td>12,000</td>
                    </tr>
                    <tr>
                        <th>Closing Balance</th>
                        <td>16,000</td>
                    </tr>
                    <tr>
                        <th>Payment For</th>
                        <td>Schhol Trip</td>
                    </tr>
                </table> -->
                <p><span>Transaction code:</span> <strong style='color:rgb(0, 100, 255);' id="transaction_codeds">KJHJ298JH</strong></p>
                <p><span>Mode of payment:</span> <strong style='color:rgb(0, 100, 255);'  id="mode_of_payment">Cash</strong></p>
                <p><span>Recieved amount: </span> <strong style='color:rgb(0, 100, 255);'  id="cash_recieved">Kes 12,000</strong> </p>
                <p><span>Closing Balance: </span> <strong  style='color:rgb(0, 100, 255);' id="closing_balance">Kes 16,000</strong> </p>
                <p><span>Payment for: </span> <strong style='color:rgb(0, 100, 255);'  id="purpose_in_p">Science Trip</strong> </p>
            </div>
            <div class="signature">
                <p>School Headteacher/Finance officer</p>
                <p><br></p>
                <p>___________________</p>
                <p><br><br></p>
                <p>School stamp <span>____________</span> Date generated: <span><u><?php echo date("D - M / dS / Y");?></u></span>  </p>
            </div>
            <div class="foot_er">
                <p><i> This is a computer generated document. <br> It`s not a valid document without a school stamp </i></p>
                <p><br> <i>For inquiries kindly contact us : <br> <strong>Email: </strong>  <?php echo $_SESSION['school_mails']; ?> <br><strong>Phone: </strong><?php echo $_SESSION['school_contacts'] ?> </i></p>
            </div>
        </div>
        <div class="school_name rotate_right">
        </div>
    </div>
    <div class="btns">
        <button onclick="printFeesReciept()"> <img src="images/print.png" alt="print"> Print</button>
        <button type = "button" onclick = "payWindowclick()">Cancel</button>
    </div>
</div>