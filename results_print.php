<div class="contents printer_page hide" id = "resul_printer_page">
    <div class="fee_reminder" id = "print_result_out">
        <div class="page_titles">
            <h2><?php echo $_SESSION['schoolname']; ?></h2>
            <p>P.O BOX <?php echo $_SESSION['po_boxs']." - ". $_SESSION['box_codes'];?></p>
            <h4><?php echo "Motto: ".$_SESSION['schoolmotto']; ?></h4>
            </div>
        <div class="print_page" id="print_results_page12">

        </div>
    </div>
    <div class="btns">
        <button type="button" onclick = "printResultsWindow()">Print</button>
        <button type="button" onclick = "closeWinC()">Cancel</button>
    </div>
</div>