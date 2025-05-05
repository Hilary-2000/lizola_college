<?php
include_once("../connections/conn1.php");
include_once("../connections/conn2.php");
include_once("../ajax/finance/financial.php");
$financial_performace = $_POST['financial_performace'];
$year_of_perfomance = $_POST['year_of_perfomance'];

// get the periods
$periods = [$year_of_perfomance, (($year_of_perfomance * 1) - 1), (($year_of_perfomance * 1) - 2)];
$tittle = "Financial Statement.";
if ($financial_performace == "annual_report") {
    $periods = [[$periods[0] . "0630", $periods[1] . "0701"], [$periods[1] . "0630", $periods[2] . "0701"]];
} elseif ($financial_performace == "quarterly_report_sep") {
    $periods = [[$periods[1] . "0930", $periods[1] . "0701"], [$periods[2] . "0930", $periods[2] . "0701"]];
} elseif ($financial_performace == "quarterly_report_dec") {
    $periods = [[$periods[1] . "1231", $periods[1] . "0701"], [$periods[2] . "1231", $periods[2] . "0701"]];
} elseif ($financial_performace == "quarterly_report_mar") {
    $periods = [[$periods[0] . "0331", $periods[1] . "0701"], [$periods[1] . "0331", $periods[2] . "0701"]];
} elseif ($financial_performace == "quarterly_report_jun") {
    $periods = [[$periods[0] . "0630", $periods[1] . "0701"], [$periods[1] . "0630", $periods[2] . "0701"]];
} else {
    $periods = [[$periods[0] . "0630", $periods[1] . "0701"], [$periods[1] . "0630", $periods[2] . "0701"]];
}
$pdf = new PDF('P', 'mm', 'A4');
$pdf->setHeaderPos(200);
$pdf->set_document_title($tittle);
$pdf->setSchoolLogo("../../" . schoolLogo($conn));
$pdf->set_school_name($_SESSION['schname']);
$pdf->set_school_po($_SESSION['po_boxs']);
$pdf->set_school_box_code($_SESSION['box_codes']);
$pdf->set_school_contact($_SESSION['school_contact']);
$pdf->AddPage();
$pdf->SetFont('Times', 'BU', 10);
$tittle = "Statement of Financial Performance for The Year Ended Jun 30th " . $year_of_perfomance;
$pdf->Cell(190, 7, $tittle, 0, 1, "C");
$pdf->SetFont('Times', '', 10);
$pdf->Cell(275, 8, "Date Generated : " . date("l dS M Y : h:i:sA"), 0, 0, 'L', false);
$pdf->ln();

// SET FILL COLLOR
$pdf->SetFillColor(0, 112, 192);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(85, 6, "Description", "TL", 0, "C", TRUE);
$pdf->Cell(15, 6, "Notes", 1, 0, "L", TRUE);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[0][1])) . " / " . date("dS Y", strtotime($periods[0][0])), 1, 0, "C", TRUE);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[1][1])) . " / " . date("dS Y", strtotime($periods[1][0])), 1, 1, "C", TRUE);


$pdf->Cell(85, 6, "", "BL", 0, "L", TRUE);
$pdf->Cell(15, 6, "", 1, 0, "L", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 1, "C", TRUE);

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 6, "Revenue  from Non-Exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "L", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "C", FALSE);

// 
$pdf->SetFont('Times', '', 10);

// total of revenue
$total_revenue_1 = 0;
$total_revenue_2 = 0;

// PROCESS NOTE 6
$note_6 = get_note($periods, $conn2, "6");
$pdf->Cell(85, 6, "Transfers from other National Government entities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "6", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_6['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_6['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue
$total_revenue_1 += $note_6['current_year_total'];
$total_revenue_2 += $note_6['last_year_total'];

// PROCESS NOTE 7
$note_7 = get_note($periods, $conn2, "7");
$pdf->Cell(85, 6, "Grants from donors and development partners", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "7", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_7['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_7['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue
$total_revenue_1 += $note_7['current_year_total'];
$total_revenue_2 += $note_7['last_year_total'];

// PROCESS NOTE 8
$note_8 = get_note($periods, $conn2, "8");
$pdf->Cell(85, 6, "Transfers from other levels of government", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "8", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_8['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_8['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue
$total_revenue_1 += $note_8['current_year_total'];
$total_revenue_2 += $note_8['last_year_total'];

// PROCESS NOTE 9
$note_9 = get_note($periods, $conn2, "9");
$pdf->Cell(85, 6, "Public contributions and donations", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "9", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_9['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_9['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue
$total_revenue_1 += $note_9['current_year_total'];
$total_revenue_2 += $note_9['last_year_total'];

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue_1), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue_2), 1, 1, "R", FALSE);

$pdf->Cell(85, 6, "Revenue from Exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "C", FALSE);
$pdf->SetFont('Times', '', 10);

// addittion to total revenue 2
$total_revenue1 = 0;
$total_revenue2 = 0;

// PROCESS NOTE 10
$note_10 = get_note_10($conn2);
$pdf->Cell(85, 6, "Rendering of services- fees from students", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "10", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_10['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_10['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue 2
$total_revenue1 += $note_10['current_year_total'];
$total_revenue2 += $note_10['last_year_total'];

// PROCESS NOTE 11
$note_11 = get_note($periods, $conn2, "11");
$pdf->Cell(85, 6, "Cafeteria sales", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "11", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_11['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_11['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue 2
$total_revenue1 += $note_11['current_year_total'];
$total_revenue2 += $note_11['last_year_total'];

// PROCESS NOTE 12
$note_12 = get_note($periods, $conn2, "12");
$pdf->Cell(85, 6, "Rental revenue from facilities and equipment", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "12", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_12['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_12['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue 2
$total_revenue1 += $note_12['current_year_total'];
$total_revenue2 += $note_12['last_year_total'];

// PROCESS NOTE 13
$note_13 = get_note($periods, $conn2, "13");
$pdf->Cell(85, 6, "Finance Income", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "13", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_13['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_13['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue 2
$total_revenue1 += $note_13['current_year_total'];
$total_revenue2 += $note_13['last_year_total'];

// PROCESS NOTE 14
$note_14 = get_note($periods, $conn2, "14");
$pdf->Cell(85, 6, "Miscellaneous income", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "14", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_14['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_14['last_year_total']), 1, 1, "R", FALSE);

// addittion to total revenue 2
$total_revenue1 += $note_14['current_year_total'];
$total_revenue2 += $note_14['last_year_total'];

// 
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue1), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue2), 1, 1, "R", FALSE);

// 
$pdf->Cell(85, 6, "Total Revenue", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue1 + $total_revenue_1), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_revenue2 + $total_revenue_2), 1, 1, "R", FALSE);


$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

// 
$pdf->Cell(85, 6, "Expenses", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

$pdf->SetFont('Times', '', 10);
// tally expense
$total_expense_1 = 0;
$total_expense_2 = 0;
// 
$note_15 = get_expense_note($periods, $conn2, "15");
$pdf->Cell(85, 6, "Use of goods and services", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "15", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_15['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_15['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_15['current_year_total'];
$total_expense_2 += $note_15['last_year_total'];

// 
$note_16 = get_expense_note($periods, $conn2, "16");
$pdf->Cell(85, 6, "Employee costs", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "16", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_16['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_16['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_16['current_year_total'];
$total_expense_2 += $note_16['last_year_total'];

// 
$note_17 = get_expense_note($periods, $conn2, "17");
$pdf->Cell(85, 6, "Board /Council Expenses", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "17", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_17['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_17['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_17['current_year_total'];
$total_expense_2 += $note_17['last_year_total'];

// 
$note_18 = get_expense_note($periods, $conn2, "18");
$pdf->Cell(85, 6, "Depreciation and  amortization expense", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "18", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_18['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_18['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_18['current_year_total'];
$total_expense_2 += $note_18['last_year_total'];

// 
$note_19 = get_expense_note($periods, $conn2, "19");
$pdf->Cell(85, 6, "Repairs and maintenance", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "19", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_19['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_19['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_19['current_year_total'];
$total_expense_2 += $note_19['last_year_total'];

// 
$note_20 = get_expense_note($periods, $conn2, "20");
$pdf->Cell(85, 6, "Contracted services", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "20", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_20['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_20['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_20['current_year_total'];
$total_expense_2 += $note_20['last_year_total'];

// 
$note_21 = get_expense_note($periods, $conn2, "21");
$pdf->Cell(85, 6, "Grants and subsidies", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "21", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_21['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_21['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_21['current_year_total'];
$total_expense_2 += $note_21['last_year_total'];

// 
$note_22 = get_expense_note($periods, $conn2, "22");
$pdf->Cell(85, 6, "Finance costs", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "22", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_22['current_year_total']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($note_22['last_year_total']), 1, 1, "R", FALSE);

// add expense
$total_expense_1 += $note_22['current_year_total'];
$total_expense_2 += $note_22['last_year_total'];

// 

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Expenses", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_expense_1), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($total_expense_2), 1, 1, "R", FALSE);


$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

// other gains
$pdf->Cell(85, 6, "Other Gains/(Losses)", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

$pdf->SetFont('Times', '', 10);
// other gains
$pdf->Cell(85, 6, "Gain on sale of assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "23", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "000", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "000", 1, 1, "R", FALSE);

// other gains
$pdf->Cell(85, 6, "Gain/ Loss on fair value of investments", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "24", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "000", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "000", 1, 1, "R", FALSE);

// other gains
$pdf->Cell(85, 6, "Impairment loss", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "25", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "000", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "000", 1, 1, "R", FALSE);

// other gains
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Other Gains/(Losses)", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "000", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "000", 1, 1, "R", FALSE);

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Net surplus/(deficit)  for the year", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$income_1 = ($total_revenue1 + $total_revenue_1) - $total_expense_1;
$income_2 = ($total_revenue2 + $total_revenue_2) - $total_expense_2;
$pdf->Cell(45, 6, "Kes " . number_format($income_1), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($income_2), 1, 1, "R", FALSE);

// assets
$pdf->AddPage();
$pdf->SetFont('Times', 'BU', 10);
$tittle = "Statement of Financial Position as at 30th June " . date("Y", strtotime($year_of_perfomance));
$pdf->Cell(190, 6, $tittle, 0, 1, 'C');

// SET FILL COLLOR
$pdf->SetFont('Times', 'B', 10);
$pdf->SetFillColor(0, 112, 192);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(85, 6, "Description", "TL", 0, "C", TRUE);
$pdf->Cell(15, 6, "Notes", 1, 0, "L", TRUE);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[0][1])) . " / " . date("dS Y", strtotime($periods[0][0])), 1, 0, "C", TRUE);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[1][1])) . " / " . date("dS Y", strtotime($periods[1][0])), 1, 1, "C", TRUE);


$pdf->Cell(85, 6, "", "BL", 0, "L", TRUE);
$pdf->Cell(15, 6, "", 1, 0, "L", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 1, "C", TRUE);

// set text color back to black
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 6, "Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

// set text color back to black
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 6, "Current Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

$cash_and_cash_equivalents = note_26($conn2, $periods);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Cash and cash equivalents", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "26", 1, 0, "C", FALSE);
$income_1 = ($total_revenue1 + $total_revenue_1) - $total_expense_1;
$income_2 = ($total_revenue2 + $total_revenue_2) - $total_expense_2;
$pdf->Cell(45, 6, "Kes " . number_format($cash_and_cash_equivalents['current_year_cash']), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "Kes " . number_format($cash_and_cash_equivalents['last_year_cash']), 1, 1, "R", FALSE);

$student_balances = get_school_balances($conn2);
$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Current portion of receivables from exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "27(a)", 1, 0, "C", FALSE);
$true_balance = (95 * $student_balances['total_balance']) / 100;
$pdf->Cell(45, 6, "Kes " . number_format($true_balance), 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Receivables from non-exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "28", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Inventories", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "29", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Investments in financial assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "30", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Current Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Non-Current Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Long term receivables from exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "27(b)", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Investments", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "30", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Property ,plant, and  equipment", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "31", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Intangible assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "32", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Investment property", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "33", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Biological Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "34", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Non-Current Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Current Liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Trade and other payables from exchange transactions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "35", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Refundable deposits from customers", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "36", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Current provisions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "37", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Finance lease obligation", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "38", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Deferred income", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "39", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Employee benefit obligation", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "40", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Payments received in advance", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "41", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Current portion of borrowings", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "43", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Social Benefits", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "45", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Current Liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Non-Current Liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

// add page
$pdf->AddPage();

// SET FILL COLLOR
$pdf->SetFont('Times', 'B', 10);
$pdf->SetFillColor(0, 112, 192);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(85, 6, "Description", "TL", 0, "C", TRUE);
$pdf->Cell(15, 6, "Notes", 1, 0, "L", TRUE);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[0][1])) . " / " . date("dS Y", strtotime($periods[0][0])), 1, 0, "C", TRUE);
$pdf->Cell(45, 6, date("dS Y", strtotime($periods[1][1])) . " / " . date("dS Y", strtotime($periods[1][0])), 1, 1, "C", TRUE);


$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 6, "", "BL", 0, "L", TRUE);
$pdf->Cell(15, 6, "", 1, 0, "L", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(45, 6, "Ksh", 1, 1, "C", TRUE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Deferred income", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "39", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Non-Current Employee Benefit Obligation", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "40", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Non-Current Provisions", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "42", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Non- Current Borrowings", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "43", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Service Concession Liability", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "44", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Social benefits", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "45", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total non- current liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->Cell(85, 6, "", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Net Assets", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Revaluation Reserves", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Accumulated Surplus", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', '', 10);
$pdf->Cell(85, 6, "Capital Fund", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);


$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(85, 6, "Total Net Assets and Liabilities", "BL", 0, "L", FALSE);
$pdf->Cell(15, 6, "", 1, 0, "C", FALSE);
$pdf->Cell(45, 6, "", 1, 0, "R", FALSE);
$pdf->Cell(45, 6, "", 1, 1, "R", FALSE);

// Display notes
// echo json_encode($note_7);

// display note 6
$note_title = "6.	Transfers from other National Government entities";
display_notes($note_6, $pdf, $periods, $note_title);

// display note 7
$note_title = "7.	Grants from Donors and Development Partners";
display_notes($note_7, $pdf, $periods, $note_title);

// display note 8
$note_title = "8.	Transfers from Other Levels of Government";
display_notes($note_8, $pdf, $periods, $note_title);

// display note 9
$note_title = "9.	Public Contributions and Donations";
display_notes($note_9, $pdf, $periods, $note_title);

// display note 10
$note_title = "10.	Rendering of Services";
display_notes($note_10, $pdf, $periods, $note_title);

// display note 11
$note_title = "11.	Sale of Goods";
display_notes($note_11, $pdf, $periods, $note_title);

// display note 11
$note_title = "12.	Rental revenue from facilities and equipment";
display_notes($note_12, $pdf, $periods, $note_title);

// display note 11
$note_title = "13.	Finance Income ";
display_notes($note_13, $pdf, $periods, $note_title);

// display note 11
$note_title = "14.	Miscellaneous Income";
display_notes($note_14, $pdf, $periods, $note_title);

// display note 11
$note_title = "15.	Use of Goods and Services";
display_notes($note_15, $pdf, $periods, $note_title);

// display note 11
$note_title = "16.	Employee Costs";
display_notes($note_16, $pdf, $periods, $note_title);

// display note 11
$note_title = "17.	Board/Council Expenses";
display_notes($note_17, $pdf, $periods, $note_title);

// display note 11
$note_title = "18.	Depreciation and Amortization expense";
display_notes($note_18, $pdf, $periods, $note_title);

// display note 11
$note_title = "19.	Repairs and Maintenance";
display_notes($note_19, $pdf, $periods, $note_title);

// display note 11
$note_title = "20.	Contracted Services";
display_notes($note_20, $pdf, $periods, $note_title);

// display note 11
$note_title = "21.	Grants and Subsidies";
display_notes($note_21, $pdf, $periods, $note_title);

// display note 11
$note_title = "22.	Finance Costs";
display_notes($note_22, $pdf, $periods, $note_title);

// // display note 11
$note_title = "23.	Gain On Sale of Assets";
$row = 7;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "24.	Gain/(loss) on Fair Value Investments";
$row = 6;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "25.	Impairment Loss";
$row = 6;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "26 (a). Detailed Analysis of Cash and Cash equivalents";
$row = 6;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "27(a) Current Receivables from Exchange transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "27(b) Long- term Receivables from Exchange transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "27 (c) Ageing Analysis of Receivables from Exchange transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "27 (d) Reconciliation for impairment Allowance on Receivables from Exchange Transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "28.	Receivables from Non-Exchange transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "28 (a) Ageing Analysis on Receivables from Non-Exchange Transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "28 (b) Reconciliation for Impairment Allowance on Receivables from Non-Exchange Transactions";
$row = 5;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "29.	Inventories";
$row = 10;
create_note_table($pdf, $periods, $row, $note_title);

// // display note 11
$note_title = "30.	Investments in financial assets";
$row = 10;
create_note_table($pdf, $periods, $row, $note_title);

// $pdf->Close();
$pdf->setHeaderPos(290);
$pdf->AddPage("L", "A4");

// SET FILL COLLOR
$pdf->SetFont('Times', 'B', 10);
$pdf->SetFillColor(0, 112, 192);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(45, 6, "Cost", "TL", 0, "C", TRUE);
$pdf->SetFont('Times', 'B', 6);
$pdf->Cell(30, 6, "Land", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Building", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Motor Vehicle", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Furniture & Fitting", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Computer & ICT Equipments", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Plant and equipment", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Capital Work in progress", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Total", 1, 1, "C", TRUE);

$pdf->Cell(45, 6, "", "BL", 0, "L", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 0, "C", TRUE);
$pdf->Cell(30, 6, "Ksh", 1, 1, "C", TRUE);

// get asset history
$pdf->SetTextColor(0, 0, 0);
$assets = [];
for ($index = 1; $index <= 7; $index++) {
    $asset_category = $index . "";
    $asset_history = asset_history($conn2, $asset_category, $periods);
    array_push($assets, $asset_history);
}

// assets
// echo json_encode($assets);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(45, 6, "At 1st July " . date("Y", strtotime($periods[1][1])), "BL", 0, "L", FALSE);
$total = 0;
for ($index = 0; $index < count($assets); $index++) {
    $index_asset = $assets[$index];
    for ($ind = 0; $ind < count($index_asset); $ind++) {
        if ($index_asset[$ind]['year'] == date("Y", strtotime($periods[1][1]))) {
            $pdf->Cell(30, 6, "" . number_format($index_asset[$ind]['balance']), 1, 0, "L", FALSE);
            $total += $index_asset[$ind]['balance'];
        }
    }
}
$pdf->Cell(30, 6, "Ksh " . number_format($total), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->SetFont('Times', '', 10);
$pdf->Cell(45, 6, "Additions", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->Cell(45, 6, "Disposals", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();


$pdf->Cell(45, 6, "Transfers/Adjustments", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

// next year
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(45, 6, "At 30th June " . date("Y", strtotime($periods[0][1])), "BL", 0, "L", FALSE);
$total = 0;
for ($index = 0; $index < count($assets); $index++) {
    $index_asset = $assets[$index];
    for ($ind = 0; $ind < count($index_asset); $ind++) {
        if ($index_asset[$ind]['year'] == date("Y", strtotime($periods[0][1]))) {
            $pdf->Cell(30, 6, "" . number_format($index_asset[$ind]['balance']), 1, 0, "L", FALSE);
            $total += $index_asset[$ind]['balance'];
        }
    }
}
$pdf->Cell(30, 6, "Ksh " . number_format($total), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->SetFont('Times', '', 10);
$pdf->Cell(45, 6, "Additions", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->Cell(45, 6, "Disposals", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();


$pdf->Cell(45, 6, "Transfers/Adjustments", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

// next year
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(45, 6, "At 30th June " . date("Y", strtotime($periods[0][0])), "BL", 0, "L", FALSE);
$total = 0;
for ($index = 0; $index < count($assets); $index++) {
    $index_asset = $assets[$index];
    for ($ind = 0; $ind < count($index_asset); $ind++) {
        if ($index_asset[$ind]['year'] == date("Y", strtotime($periods[0][0]))) {
            $pdf->Cell(30, 6, "" . number_format($index_asset[$ind]['balance']), 1, 0, "L", FALSE);
            $total += $index_asset[$ind]['balance'];
        }
    }
}
$pdf->Cell(30, 6, "Ksh " . number_format($total), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->Cell(45, 6, "", 1, 0, "L", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 1, "C", false);

$pdf->Cell(45, 6, "Depreciation And Impairment", 1, 0, "L", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 0, "C", false);
$pdf->Cell(30, 6, "", 1, 1, "C", false);

// next year
// echo json_encode($assets[0]);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(45, 6, "At 1st July " . date("Y", strtotime($periods[1][1])), "BL", 0, "L", FALSE);
$total = 0;
for ($index = 0; $index < count($assets); $index++) {
    $index_asset = $assets[$index];
    for ($ind = 0; $ind < count($index_asset); $ind++) {
        if ($index_asset[$ind]['year'] == date("Y", strtotime($periods[1][1]))) {
            $pdf->Cell(30, 6, "" . number_format($index_asset[$ind]['credit']), 1, 0, "L", FALSE);
            $total += $index_asset[$ind]['credit'];
        }
    }
}
$pdf->Cell(30, 6, "Ksh " . number_format($total), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->SetFont('Times', '', 10);
$pdf->Cell(45, 6, "Additions", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

$pdf->Cell(45, 6, "Disposals", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();


$pdf->Cell(45, 6, "Transfers/Adjustments", "BL", 0, "L", FALSE);
for ($index = 0; $index < count($assets); $index++) {
    $pdf->Cell(30, 6, "0", 1, 0, "L", FALSE);
}
$pdf->Cell(30, 6, "Ksh " . number_format(0), 1, 0, "L", FALSE);
$pdf->Ln();

// output
$pdf->Output();
