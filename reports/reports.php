<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../ajax/administration/phpmailer/src/Exception.php';
require '../ajax/administration/phpmailer/src/PHPMailer.php';
require '../ajax/administration/phpmailer/src/SMTP.php';

// Include PhpSpreadsheet library
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

require('fpdf.php');
require('sector.php');

session_start();
date_default_timezone_set('Africa/Nairobi');

class PDF extends FPDF
{
    protected $B = 0;
    public $school_logo = "../../" . "assets/img/ladybird.png";
    protected $school_name = "LADYBIRD PRIMARY SCHOOL";
    protected $school_po = "552";
    protected $school_BOX_CODE = "50400";
    public $school_contact = "0743551250";
    public $school_document_title = "Students List";
    protected $school_header_position = 300;
    protected $conn = null;
    public $arm_of_gov = "../../assets/img/arm_of_gov.png";
    // set school_logo
    function setSchoolLogo($logo)
    {
        $this->school_logo = $logo;
    }
    // set school_name
    function set_school_name($sch_name)
    {
        $this->school_name = $sch_name;
    }
    // set school_po
    function set_school_po($sch_po)
    {
        $this->school_po = $sch_po;
    }
    // set school_box_code
    function set_school_box_code($sch_box_code)
    {
        $this->school_BOX_CODE = $sch_box_code;
    }
    // set school_box_code
    function set_school_contact($sch_contacts)
    {
        $this->school_contact = $sch_contacts;
    }
    // set school_box_code
    function set_document_title($title)
    {
        $this->school_document_title = $title;
    }
    // Load data
    function LoadData($file)
    {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line)
            $data[] = explode(';', trim($line));
        return $data;
    }

    // Page header
    function Header()
    {
        // Logo
        $this->Image(dirname(__FILE__) . $this->school_logo, 6, 6, 20);
        $this->Image(dirname(__FILE__) . $this->arm_of_gov, ($this->school_header_position == 300 ? ($this->school_header_position - 30) : ($this->school_header_position - 15)), 6, 18);
        // Arial  15
        $this->SetFont('Arial', 'B', 13);
        // Title
        $this->Cell($this->school_header_position, 5, strtoupper($this->school_name), 0, 0, 'C');
        $this->Ln();
        // Arial  15
        $this->SetFont('Arial', '', 8);
        $this->Cell($this->school_header_position, 5, "P.O Box : " . $this->school_po . "-" . $this->school_BOX_CODE, 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->school_header_position, 5, "Contact Us: " . $this->school_contact, 0, 0, 'C');
        // Line break
        $this->Ln();
        if (isset($_SESSION['school_mail'])) {
            $this->Cell($this->school_header_position, 5, "Mail Us: " . $_SESSION['school_mail'], 0, 1, 'C');
        }
        $this->SetFont('Arial', 'BU', 10);
        $this->Ln();
        $this->Cell($this->school_header_position, 5,
        /** "Report Title: " . **/
        $this->school_document_title . "", 0, 0, 'C');
        $this->SetTitle($this->school_document_title);
        $this->SetFont('', '');
        $this->SetAuthor($_SESSION['username']);
        // Line break
        if ($this->school_header_position == 200) {
            // potrait
            $this->Ln(10);
            $this->Cell(190, 0, "", 1);
        }
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . '', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Arial', 'I', 8);
        $this->Cell($this->school_header_position, 7, "If found please return to " . ucwords(strtolower(trim($this->school_name))) . " or contact " . $this->school_contact . "",0,0,"C");
    }

    function setHeaderPos($pos)
    {
        $this->school_header_position = $pos;
    }

    // Colored table
    function FancyTable($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            $this->Cell($w[3], 6, ($row[3] == "Male" ? "M" : "F"), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            $this->Cell($w[6], 6, ($row[6]), 1, 0, 'C', $fill);
            $this->Cell($w[7], 6, ($row[7]), 1, 0, 'L', $fill);
            $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $this->Cell($w[9], 6, ($row[9]), 1, 0, 'L', $fill);
            if(count($w) > 10){
                $this->Cell($w[10], 6, ($row[10]), 1, 0, 'R', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function timeTable_create($header, $data, $width)
    {
        $heighest_header = 0;
        for ($index = 0; $index < count($header); $index++) {
            $data_in = round($this->GetStringWidth($header[$index])) + 5;
            if ($data_in > $heighest_header) {
                $heighest_header = $data_in;
            }
        }
        // get the highest string length in the data
        $highest_len = 0;
        for ($index1 = 0; $index1 < count($data); $index1++) {
            for ($index2 = 0; $index2 < count($data[$index1]); $index2++) {
                $data_in = round($this->GetStringWidth($data[$index1][$index2])) + 5;
                if ($data_in > $highest_len) {
                    // echo $data[$index1][$index2]." = $highest_len < ".$data_in."<br>";
                    $highest_len = $data_in;
                }
            }
        }

        // echo $highest_len;
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        $mu_len = ceil($heighest_header / $w[1]) * 6; // get the number of lines the data is going to be then multiply by the standard height
        // echo $mu_len." ".$heighest_header." $w[1]";
        $this->Cell($w[0], $mu_len, $header[0], 1, 0, 'C', true);
        $w_count = 0;
        $x = $this->GetX();
        $y = $this->GetY();
        for ($i = 1; $i < count($header); $i++) {
            $data_text = $header[$i];
            while (round($this->GetStringWidth($data_text)) < $heighest_header) {
                $data_text .= " ";
            }
            $this->SetXY($x + $w_count, $y);
            $this->MultiCell($w[$i], 6, $data_text, "LTR", "C", true);
            // $this->MultiCell(8,5,"My name is HIllary I come from Kirintage","LTR","L",false);
            $w_count += $w[$i];
        }
        // $this->Ln();
        // Color and font restoration
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 7);
        // Data
        $fill = false;
        for ($ind = 0; $ind < count($data); $ind++) {
            $row = $data[$ind];
            $mu_len2 = ceil($highest_len / $w[1]) * 5;
            $this->Cell($w[0], $mu_len2, $row[0], 1, 0, 'LTR', $fill);
            // create a row of the timetable
            $w_count1 = 0;
            $x1 = $this->GetX();
            $y1 = $this->GetY();
            for ($index = 1; $index < count($row); $index++) {
                $data_text = str_replace("{", " (", $row[$index]);
                $data_text = str_replace("}", ") ", $data_text);
                $data_text = str_replace("|", " & ", $data_text);
                $data_text = str_replace("=", " @ ", $data_text);
                $data_text = count(explode("=", $data_text)) > 1 ? explode("=", $data_text)[0] . " " . explode("=", $data_text)[1] : $data_text;
                while (round($this->GetStringWidth($data_text)) < $highest_len) {
                    $data_text .= " ";
                }

                $this->SetXY($x1 + $w_count1, $y1);
                $this->MultiCell($w[$index], 5, $data_text, 1, "C", $fill);
                // $this->MultiCell(8,5,"My name is HIllary I come from Kirintage","LTR","L",false);
                $w_count1 += $w[$index];
            }
            // $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            // $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            // $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            // $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            // $this->Cell($w[4], 6, $row[4], 1, 0, 'L', $fill);
            // $this->Cell($w[5], 6, $row[5], 1, 0, 'L', $fill);
            // $this->Cell($w[6], 6, ($row[6]), 1, 0, 'C', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            // $this->Cell($w[9], 6, ($row[9]), 1, 0, 'R', $fill);
            // $this->Cell($w[10], 6, ($row[10]), 1, 0, 'R', $fill);
            // $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }

    function feesStructure($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        $this->SetFont('Arial', 'B', 9);
        // Header
        $w = $width;
        $this->Cell(5, 8, "", 0, 0, 'C', 0);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 9);
        // Data
        $fill = false;
        $term1 = 0;
        $term2 = 0;
        $term3 = 0;
        foreach ($data as $row) {
            $this->Cell(5, 6, "", 0, 0, 'C', 0);
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, ucwords(strtolower($row[1])), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, "Kes " . number_format($row[2]), 1, 0, 'R', $fill);
            $this->Cell($w[3], 6, "Kes " . number_format($row[3]), 1, 0, 'R', $fill);
            $this->Cell($w[4], 6, "Kes " . number_format($row[4]), 1, 0, 'R', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            $this->Ln();
            // $fill = !$fill;
            $term1 += $row[2];
            $term2 += $row[3];
            $term3 += $row[4];
        }
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell(5, 6, "", 0, 0, 'C', 0);
        $this->Cell($w[0], 6, "", 1, 0, 'L', false);
        $this->Cell($w[1], 6, "Total", 1, 0, 'L', false);
        $this->Cell($w[2], 6, "Kes " . number_format($term1), 1, 0, 'R', false);
        $this->Cell($w[3], 6, "Kes " . number_format($term2), 1, 0, 'R', false);
        $this->Cell($w[4], 6, "Kes " . number_format($term3), 1, 0, 'R', false);
        $this->Cell($w[5], 6, "", 1, 0, 'C', false);
        $this->Ln();
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function financeTable($header, $data, $width, $skip = true)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        if ($skip == true) {
            $w[3]+=20;
        }
        for ($i = 0; $i < count($header); $i++) {
            if ($skip == true) {
                if ($i != 4) {
                    $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
                }
            } else {
                $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
            }
        }

        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        $recieved = 0;
        $balance = 0;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, "Kes " . number_format($row[1]), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, "Kes " . number_format($row[2]), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            if ($skip == false) {
                $this->Cell($w[4], 6, $row[4], 1, 0, 'L', $fill);
            }
            $this->Cell($w[5], 6, $row[5], 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, ucwords(strtolower($row[6])), 1, 0, 'L', $fill);
            $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $this->Cell($w[9], 6, ($row[9]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
            $balance += $row[2];
            $recieved += $row[1];
        }
        $this->SetFont('Helvetica', 'BI', 8);
        $this->Cell($w[0], 6, "Tot", 1, 0, 'L', $fill);
        $this->Cell($w[1], 6, "Kes " . number_format($recieved), 1, 0, 'L', $fill);
        // $this->Cell($w[2], 6, "Kes " . number_format($balance), 1, 0, 'L', $fill);
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function expenseTable($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        }

        $this->Ln();
        // Color and font restoration
        // $header = array('No', 'Expense', 'Category','Units', 'Unit Price', 'Total',  'Date');
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        $index = 1;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $index, 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, ucwords(strtolower($row[0])), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ucwords(strtolower($row[1])), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, ucwords(strtolower($row[2])), 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, "Kes ".number_format($row[3]), 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, "Kes ".number_format($row[4]), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, date("dS M Y : H:i:s",strtotime($row[5])), 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
            $index++;
        }
        // $this->SetFont('Helvetica', 'BI', 8);
        // $this->Cell($w[0], 6, "Tot", 1, 0, 'L', $fill);
        // $this->Cell($w[1], 6, "Kes " . number_format($recieved), 1, 0, 'L', $fill);
        // $this->Cell($w[2], 6, "Kes " . number_format($balance), 1, 0, 'L', $fill);
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function exams_results($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            for ($index = 0; $index < count($row); $index++) {
                if ($index <= 2) {
                    $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
                } else {
                    $this->Cell($w[$index], 6, $row[$index], 1, 0, 'C', $fill);
                }
            }
            // $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            // $this->Cell($w[1], 6, $row[1], 1, 0, 'R', $fill);
            // $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            // $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            // $this->Cell($w[4], 6, $row[4], 1, 0, 'L', $fill);
            // $this->Cell($w[5], 6, $row[5], 1, 0, 'L', $fill);
            // $this->Cell($w[6], 6, ($row[6]), 1, 0, 'R', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell($w[0], 6, "", "LB", 0, 'L', false);
        $this->Cell($w[1], 6, "", "B", 0, 'L', false);
        $this->SetFont('Helvetica', 'B', 8);
        $this->Cell($w[2], 6, "Mean : ", "B", 0, 'L', false);
        // get the array to store the data
        $data_holder = [];
        for ($index = 3; $index < (count($data[0])); $index++) {
            $dtat = [];
            array_push($data_holder, $dtat);
        }
        // echo count($data_holder)."<br>";
        // GET EACH DATA AS COLUMN
        foreach ($data as $rows) {
            $indexed = 0;
            for ($index = 3; $index < (count($rows)); $index++) {
                array_push($data_holder[$indexed], $rows[$index]);
                $indexed++;
            }
        }
        // echo var_dump($data_holder[0])."<br>";
        for ($i = 0; $i < count($data_holder); $i++) {
            $marks = round(array_sum($data_holder[$i]) / count($data_holder[$i]), 1);
            $grade = $marks > 0 ? $this->getSubjectGrade($header[$i + 3], $marks) : "";
            $this->Cell($w[$i + 3], 6, array_sum($data_holder[$i]) > 0 ? round(array_sum($data_holder[$i]) / count($data_holder[$i]), 1) . " " . $grade . "" : " " . $grade . "", 'LR', 0, 'C', false);
        }
        // Closing line
        $this->Ln();
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function getSubjectGrade($subject_id, $subject_marks)
    {
        if ($subject_id == "Total") {
            return "";
        }
        if ($this->conn != null) {
            $select = "SELECT * FROM `table_subject` WHERE `timetable_id` = '" . $subject_id . "'";
            $stmt = $this->conn->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $grading = $row['grading'];
                    if (strlen(trim($grading)) > 0) {
                        $decode = json_decode($grading);
                        $my_grade = "N/A";
                        for ($index = 0; $index < count($decode); $index++) {
                            $max = $decode[$index]->max;
                            $min = $decode[$index]->min;
                            $grade_name = $decode[$index]->grade_name;
                            if ($subject_marks >= $min && $subject_marks <= $max) {
                                return $grade_name;
                            }
                        }
                        return $my_grade;
                    } else {
                        return "N/A";
                    }
                }
            }
        }
        return "N/A";
    }
    function setConn($conn)
    {
        $this->conn = $conn;
    }
    // balance table
    function balancesTable($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            if ($i != 3) {
                $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
            }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 7);
        // Data
        $fill = false;
        $total_1 = 0;
        $total_2 = 0;
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            // $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, (is_integer($row[5]) ? "Kes " . number_format($row[5]) : $row[5]), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, (is_integer($row[6]) ? "Kes " . number_format($row[6]) : $row[6]), 1, 0, 'L', $fill);
            $this->Cell($w[7], 6, (is_integer($row[7]) ? "Kes " . number_format($row[7]) : $row[7]), 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
            $total_1 += is_integer($row[5]) ? $row[5] : 0;
            $total_2 += is_integer($row[6]) ? $row[6] : 0;
            $total_3 += is_integer($row[7]) ? $row[7] : 0;
        }
        $this->SetFont('Helvetica', 'BI', 7);
        $this->Cell(($w[0] + $w[1] + $w[2]), 6, "", 0, 0, "R");
        $this->Cell(($w[4]), 6, "Tot", 1, 0, "R");
        $this->Cell(($w[5]), 6, "Kes " . number_format($total_1), 1, 0, "L");
        $this->Cell(($w[6]), 6, "Kes " . number_format($total_2), 1, 0, "L");
        $this->Cell(($w[7]), 6, "Kes " . number_format($total_3), 1, 0, "L");
        // CALCULATE TOTAL
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Attendance fancy table
    function AttendanceTable($header, $data, $present_status)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = array(10, 50, 20, 20, 20, 34);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            $this->Cell($w[3], 6, ($row[3] == "Male" ? "M" : "F"), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, $present_status, 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function StaffData($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            $this->Cell($w[6], 6, ($row[6]), 1, 0, 'C', $fill);
            $this->Cell($w[7], 6, ($row[7]), 1, 0, 'L', $fill);
            $this->Cell($w[8], 6, ($row[8]), 1, 0, 'L', $fill);
            $this->Cell($w[9], 6, ($row[9]), 1, 0, 'L', $fill);
            $this->Cell($w[10], 6, ($row[10]), 1, 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function logTables($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            $this->Cell($w[6], 6, ($row[6]), 1, 0, 'R', $fill);
            $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            // $this->Cell($w[9], 6, ($row[9]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function classTrData($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 9);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'R', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'R', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            // $this->Cell($w[6], 6, ($row[6]), 1, 0, 'R', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            // $this->Cell($w[9], 6, ($row[9]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function salaryTables($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 9);
        // Data
        $fill = false;
        $total_earnings = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, "Kes " . number_format($row[2]), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'R', $fill);
            $this->Cell($w[4], 6, "Kes " . number_format($row[4]), 1, 0, 'L', $fill);
            $total_earnings += $row[4];
            // $this->Cell($w[5], 6, $row[5], 1, 0, 'C', $fill);
            // $this->Cell($w[6], 6, ($row[6]), 1, 0, 'R', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            // $this->Cell($w[9], 6, ($row[9]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $fill = false;
        $this->Cell($w[0], 6, '', 0, 0, 'L', $fill);
        $this->Cell($w[1], 6, '', 0, 0, 'L', $fill);
        $this->Cell($w[2], 6, '', 0, 0, 'L', $fill);
        $this->Cell($w[3], 6, 'Total', 1, 0, 'R', $fill);
        $this->Cell($w[4], 6, "Kes " . number_format($total_earnings), 1, 1, 'L', $fill);
        $this->Ln();
        // Closing line
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
    // Colored table
    function receipt_table($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'R', $fill);
            $this->Cell($w[2], 6, ($row[2]), 1, 0, 'R', $fill);
            $this->Cell($w[3], 6, ($row[3]), 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, $row[5], 1, 0, 'L', $fill);
            // $this->Cell($w[6], 6, ($row[6]), 1, 0, 'R', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function NSSF_TABLE($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        $this->SetFont('Helvetica', 'B', 8);
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        $counter = 1;
        $total_1 = 0;
        $total_2 = 0;
        $total_3 = 0;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $counter, 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row[1], 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, ($row[2]), 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, ($row[3]), 1, 0, 'C', $fill);
            $this->Cell($w[5], 6, "Kes " . comma($row[4]), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, "Kes " . comma($row[5]), 1, 0, 'L', $fill);
            $this->Cell($w[7], 6, "Kes " . comma($row[6]), 1, 0, 'L', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $total_1 += $row[4];
            $total_2 += $row[5];
            $total_3 += $row[6];
            $this->Ln();
            $fill = !$fill;
            $counter++;
        }
        // ADD SOME CELLS TO GET THE TOTAL AMOUNTS
        // indent cell
        $this->SetFont('Helvetica', 'BI', 8);
        $this->Cell(($w[0] + $w[1] + $w[2] + $w[3]));
        $this->Cell($w[4], 6, "Total", 1, 0, "R", true);
        $this->Cell($w[5], 6, "Kes " . comma($total_1), 1, 0, "L", true);
        $this->Cell($w[6], 6, "Kes " . comma($total_2), 1, 0, "L", true);
        $this->Cell($w[7], 6, "Kes " . comma($total_3), 1, 1, "L", true);
        // totals
        // Closing line
        $this->Cell(array_sum($w), 0, '', 0);
    }
    function NHIF_TABLE($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        // Header
        $w = $width;
        $this->SetFont('Helvetica', 'B', 8);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        $counter = 1;
        $total_1 = 0;
        $total_2 = 0;
        $total_3 = 0;
        $total_4 = 0;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $counter, 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, (strlen(trim($row[2])) > 0 ? trim($row[2]) : "-"), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, "Kes " . comma($row[1]), 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, "Kes " . comma($row[3]), 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, "Kes " . comma($row[4]), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, "Kes " . comma($row[3] - $row[4]), 1, 0, 'L', $fill);
            $this->Cell($w[7], 6, ($row[5]), 1, 0, 'R', $fill);
            $this->Cell($w[8], 6, ($row[6]), 1, 0, 'R', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $total_1 += $row[1];
            $total_2 += $row[3];
            $total_3 += $row[4];
            $total_4 += ($row[3] - $row[4]);
            $this->Ln();
            $fill = !$fill;
            $counter++;
        }
        // ADD SOME CELLS TO GET THE TOTAL AMOUNTS
        // indent cell
        // $this->SetFont('Helvetica', 'i', 9);
        $this->SetFont('Helvetica', 'BI', 8);
        $this->Cell(($w[0] + $w[1]));
        $this->Cell($w[2], 6, "Total", 1, 0, "L", true);
        $this->Cell($w[3], 6, "Kes " . comma($total_1), 1, 0, "L", true);
        $this->Cell($w[4], 6, "Kes " . comma($total_2), 1, 0, "L", true);
        $this->Cell($w[5], 6, "Kes " . comma($total_3), 1, 0, "L", true);
        $this->Cell($w[6], 6, "Kes " . comma($total_4), 1, 1, "L", true);
        // totals
        // Closing line
        $this->Cell(array_sum($w), 0, '', 0);
    }
    function KRA_TABLE($header, $data, $width)
    {
        // Colors, line width and bold font
        $this->SetFillColor(157, 183, 184);
        // $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        // $this->SetFont('','B');
        $this->SetFont('Helvetica', 'B', 8);
        // Header
        $w = $width;
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(205, 211, 218);
        $this->SetTextColor(0);
        $this->SetFont('Helvetica', '', 8);
        // Data
        $fill = false;
        $counter = 1;
        $total_1 = 0;
        $total_2 = 0;
        $total_3 = 0;
        $total_4 = 0;
        $total_5 = 0;
        $total_6 = 0;
        foreach ($data as $row) {
            // for ($$index=0; $$index < count($row); $$index++) { 
            //     $this->Cell($w[$index], 6, $row[$index], 1, 0, 'L', $fill);
            // }
            $this->Cell($w[0], 6, $counter, 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, "Kes " . comma($row[1]), 1, 0, 'L', $fill);
            $this->Cell($w[3], 6, "Kes " . comma($row[2]), 1, 0, 'L', $fill);
            $this->Cell($w[4], 6, "Kes " . comma($row[3]), 1, 0, 'L', $fill);
            $this->Cell($w[5], 6, "Kes " . comma($row[6]), 1, 0, 'L', $fill);
            $this->Cell($w[6], 6, "Kes " . comma($row[7]), 1, 0, 'L', $fill);
            $this->Cell($w[7], 6, "Kes " . comma($row[8]), 1, 0, 'L', $fill);
            // $this->Cell($w[7], 6, ($row[7]), 1, 0, 'R', $fill);
            // $this->Cell($w[8], 6, ($row[8]), 1, 0, 'R', $fill);
            $total_1 += $row[6];
            $total_2 += $row[7];
            $total_3 += $row[8];
            $total_4 += $row[1];
            $total_5 += $row[2];
            $total_6 += $row[3];
            $this->Ln();
            $fill = !$fill;
            $counter++;
        }
        // ADD SOME CELLS TO GET THE TOTAL AMOUNTS
        // indent cell
        $this->SetFont('Helvetica', 'BI', 8);
        $this->Cell(($w[0]));
        $this->Cell($w[1], 6, "Total", 1, 0, "R", true);
        $this->Cell($w[2], 6, "Kes " . comma($total_4), 1, 0, "L", true);
        $this->Cell($w[3], 6, "Kes " . comma($total_5), 1, 0, "L", true);
        $this->Cell($w[4], 6, "Kes " . comma($total_6), 1, 0, "L", true);
        $this->Cell($w[5], 6, "Kes " . comma($total_1), 1, 0, "L", true);
        $this->Cell($w[6], 6, "Kes " . comma($total_2), 1, 0, "L", true);
        $this->Cell($w[7], 6, "Kes " . comma($total_3), 1, 1, "L", true);
        // totals
        // Closing line
        $this->Cell(array_sum($w), 0, '', 0);
    }
}
class PDF_Diag extends PDF_Sector
{
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;
    public $school_logo = "../../.." . "/sims/assets/img/ladybird.png";
    function setSchoolLogo($logo)
    {
        $this->school_logo = $logo;
    }

    function PieChart($w, $h, $data, $format, $colors = null)
    {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data, $format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;
        if ($colors == null) {
            for ($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray, $gray, $gray);
            }
        }

        //Sectors
        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach ($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        //Legends
        $this->SetFont('Courier', '', 10);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal * ($hLegend + $margin)) / 2;
        for ($i = 0; $i < $this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
            $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2, $y1);
            $this->Cell(0, $hLegend, $this->legends[$i]);
            $y1 += $hLegend + $margin;
        }
    }

    function BarDiagram($w, $h, $data, $format, $color = null, $maxVal = 0, $nbDiv = 4)
    {
        $this->SetFont('Times', '', 9);
        $this->SetLegends($data, $format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);
        if ($color == null)
            $color = array(155, 155, 155);
        if ($maxVal == 0) {
            $maxVal = max($data);
        }
        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Times', '', 9);
        $this->SetFillColor($color[0], $color[1], $color[2]);
        $i = 0;
        foreach ($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int)($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->Rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->SetXY(0, $yval);
            $this->Cell($xval - $margin, $hval, $this->legends[$i], 0, 0, 'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->Text($xpos, $ypos + 5, $val);
        }
    }

    function ColumnChart($w, $h, $data, $format, $color = null, $maxVal = 0, $nbDiv = 4)
    {

        // RGB for color 0
        $colors[0][0] = 155;
        $colors[0][1] = 75;
        $colors[0][2] = 155;

        // RGB for color 1
        $colors[1][0] = 0;
        $colors[1][1] = 155;
        $colors[1][2] = 0;

        // RGB for color 2
        $colors[2][0] = 75;
        $colors[2][1] = 155;
        $colors[2][2] = 255;

        // RGB for color 3
        $colors[3][0] = 75;
        $colors[3][1] = 0;
        $colors[3][2] = 155;

        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data, $format);

        // Starting corner (current page position where the chart has been inserted)
        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;

        // Y position of the chart
        $YDiag = $YPage + $margin;

        // chart HEIGHT
        $hDiag = floor($h - $margin * 2);

        // X position of the chart
        $XDiag = $XPage + $margin;

        // chart LENGHT
        $lDiag = floor($w - $margin * 3 - $this->wLegend);

        if ($color == null)
            $color = array(155, 155, 155);
        if ($maxVal == 0) {
            foreach ($data as $val) {
                if (max($val) > $maxVal) {
                    $maxVal = max($val);
                }
            }
        }

        // define the distance between the visual reference lines (the lines which cross the chart's internal area and serve as visual reference for the column's heights)
        $valIndRepere = ceil($maxVal / $nbDiv);

        // adjust the maximum value to be plotted (recalculate through the newly calculated distance between the visual reference lines)
        $maxVal = $valIndRepere * $nbDiv;

        // define the distance between the visual reference lines (in milimeters)
        $hRepere = floor($hDiag / $nbDiv);

        // adjust the chart HEIGHT
        $hDiag = $hRepere * $nbDiv;

        // determine the height unit (milimiters/data unit)
        $unit = $hDiag / $maxVal;

        // determine the bar's thickness
        $lBar = floor($lDiag / ($this->NbVal + 1));
        $lDiag = $lBar * ($this->NbVal + 1);
        $eColumn = floor($lBar * 80 / 100);

        // draw the chart border
        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0], $color[1], $color[2]);
        $i = 0;
        foreach ($data as $val) {
            //Column
            $yval = $YDiag + $hDiag;
            $xval = $XDiag + ($i + 1) * $lBar - $eColumn / 2;
            $lval = floor($eColumn / (count($val)));
            $j = 0;
            foreach ($val as $v) {
                $hval = (int)($v * $unit);
                $this->SetFillColor($colors[$j][0], $colors[$j][1], $colors[$j][2]);
                $this->Rect($xval + ($lval * $j), $yval, $lval, -$hval, 'DF');
                $j++;
            }

            //Legend
            $this->SetXY($xval, $yval + $margin);
            $this->Cell($lval, 5, $this->legends[$i], 0, 0, 'C');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $ypos = $YDiag + $hRepere * $i;
            $this->Line($XDiag, $ypos, $XDiag + $lDiag, $ypos);
            $val = ($nbDiv - $i) * $valIndRepere;
            $ypos = $YDiag + $hRepere * $i;
            $xpos = $XDiag - $margin - $this->GetStringWidth($val);
            $this->Text($xpos, $ypos, $val);
        }
    }
    function SetLegends($data, $format)
    {
        $this->legends = array();
        $this->wLegend = 0;
        $this->sum = array_sum($data);
        $this->NbVal = count($data);
        // echo $this->sum;
        // echo json_encode($data);
        foreach ($data as $l => $val) {
            // echo json_encode($val);
            $p =  $this->sum > 0 ? sprintf('%.2f', $val / $this->sum * 100) . '%' : 0;
            $legend = str_replace(array('%l', '%v', '%p'), array($l, $val, $p), $format);
            $this->legends[] = $legend;
            $this->wLegend = max($this->GetStringWidth($legend), $this->wLegend);
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['schname'])) {
    // get the fields
    if (isset($_POST['select_report_class']) && isset($_POST['pdf']) && isset($_POST['select_entity'])) {
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        $select_entity = $_POST['select_entity'];
        $select_student_option = $_POST['select_student_option'];
        $select_report_class = $_POST['select_report_class'];
        $select_date = $_POST['select_date'];
        $from_date_report = $_POST['from_date_report'];
        $to_date_report = $_POST['to_date_report'];
        $staff_options = $_POST['staff_options'];
        $select_date_staff = $_POST['select_date_staff'];
        $intake_months_reports = $_POST['intake_months_reports'];
        $intake_year_reports = $_POST['intake_year_reports'];
        $student_status = $_POST['student_status'];
        
        if ($select_entity == "student") {
            $course_names = $_POST['course_name'];
            // get the student data per class
            if ($select_student_option == "all_students") {
                if((strlen($intake_year_reports) > 0 && strlen($intake_months_reports) == 0) || strlen($intake_year_reports) == 0 && strlen($intake_months_reports) > 0){
                    echo "<p style='color:red;'>Ensure you`ve selected the intake month and year!</p>";
                    return 0;
                }
                // get the class the student is selected
                if (strlen($select_report_class) > 0) {
                    $select = "SELECT * FROM `student_data`";
                    $add_course = strlen(trim($course_names)) > 0 ? " AND `course_done` = '".$course_names."' " : "";
                    $condition = $select_report_class != "all" ? " WHERE `stud_class` = '$select_report_class' ".$add_course."" : " WHERE `stud_class` != '-1' AND `stud_class` != '-2'";
                    
                    $select_gender_option = $_POST['select_gender_option'];
                    $gender_option = $select_gender_option == "all" ? "" : "AND `gender` = '".$select_gender_option."'";
                    $condition .= $gender_option;

                    // add the intake condition
                    $intake_condition = (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " AND `intake_year` = '".$intake_year_reports."' AND `intake_month` = '".$intake_months_reports."' " : "";
                    $condition.=$intake_condition;
                    // echo $condition;
                    if ($select_report_class != "all") {
                        $select = $select . $condition;
                        // echo $select;
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        // get the courses list and the department list

                        // get the course
                        $all_courses = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }

                        // get the department
                        $all_department = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }
                        // financial.php
                        include_once("../ajax/finance/financial.php");
                        // echo json_encode($all_department);
                        while ($row = $result->fetch_assoc()) {
                            $student_name = ucwords(strtolower($row['first_name'] . " " . $row['second_name']));
                            $adm_no = $row['adm_no'];
                            $gender = $row['gender'];
                            $level_name = classNameReport($row['stud_class']);
                            $dob = $row['D_O_B'];
                            $date1 = date_create($dob);
                            $date2 = date_create(date("Y-m-d"));
                            $diff = date_diff($date1, $date2);
                            $diffs = $diff->format("%y Yrs");
                            $dob = $row['D_O_B'] . " | " . $diffs . "";
                            $doa = $row['D_O_A'];
                            $parentName = ucwords(strtolower($row['parentName']));
                            $parentContacts = $row['parentContacts'];
                            $parent_name2 = ucwords(strtolower($row['parent_name2']));
                            $parent_contact2 = $row['parent_contact2'];
                            $address = $row['address'];
                            $intake = $row['intake_month'].":".$row['intake_year'];

                            // show departments
                            $course_id = $row['course_done'];
                            $course_name = "N/A";
                            $department_id = null;
                            for($index =0; $index < count($all_courses); $index++){
                                if($all_courses[$index]->id == $course_id){
                                    $course_name = $all_courses[$index]->course_name;
                                    $courses_name = $course_name;
                                    $department_id = $all_courses[$index]->department;
                                    break;
                                }
                            }

                            // get the department names
                            $department_name = "N/A";
                            for($index = 0; $index < count($all_department); $index++){
                                if($all_department[$index]->code == $department_id){
                                    $department_name = $all_department[$index]->name;
                                    break;
                                }
                            }
                            // echo count(json_decode($row['my_course_list']))."-<br>";

                            // get their course progress details
                            $course_progress = (isset($row['my_course_list']) && isJson_report($row['my_course_list'])) ? json_decode($row['my_course_list']) : [];
                            $status = "In-Active";

                            // get the course status
                            for($in = 0; $in < count($course_progress); $in++){
                                $course_status = $course_progress[$in]->course_status;
                                if($course_status == 1){
                                    $module_terms = $course_progress[$in]->module_terms;
                                    for($ind = 0; $ind < count($module_terms); $ind++){
                                        if($module_terms[$ind]->status == 1){
                                            // end date
                                            $status = "Active";
                                        }
                                    }
                                }
                            }

                            // student data
                            // array_push($student_data[$index],$status);

                            $push = false;

                            if($status == "Active" && $student_status == 1){
                                $push = true;
                            }
                            if($status == "In-Active" && $student_status == 0){
                                $push = true;
                            }
                            if($student_status == 2){
                                $push = true;
                            }
                            // echo json_encode($student_data);
                            if($push){
                                if ($gender == "Male") {
                                    $boys++;
                                } else {
                                    $girls++;
                                }
                                // course level
                                $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $intake, $department_name, $dob, $doa, $address,$status);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                        }
                        $pdf = new PDF('L', 'mm', 'A4');
                        // Column headings
                        $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Intake', 'Department', 'D.O.B', 'D.O.A', 'Address',"Status");
                        // Data loading
                        // $data = $pdf->LoadData('countries.txt');

                        // course titles
                        $courses_name = "N/A";
                        for($index =0; $index < count($all_courses); $index++){
                            if($all_courses[$index]->id == $course_names){
                                $course_name = $all_courses[$index]->course_name;
                                $courses_name = $course_name;
                                break;
                            }
                        }
                        $course_title = strlen(trim($course_names)) > 0 ? " in ".$courses_name." " : "";
                        $tittle = $select_report_class != "all" ? "List for " . classNameReport($select_report_class) . " $course_title" : "Student List for Whole School";

                        // end of the title editing
                        $intake_title = (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " : Intake ".$intake_months_reports." ".$intake_year_reports."" : "";
                        $tittle.=$intake_title;
                        $STATUS_TITLE = $student_status == 1 ? " : Active" : ($student_status == 2 ? " :In-Actve" : "");
                        $tittle.=$STATUS_TITLE;

                        $data = $student_data;
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 9);
                        if($select_gender_option == "all" || $select_gender_option == "male"){
                            $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                            $pdf->Cell(20, 5, $boys . " Student(s)", 0, 0, 'L', false);
                            $pdf->Ln();
                        }
                        if($select_gender_option == "all" || $select_gender_option == "female"){
                            $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                            $pdf->Cell(20, 5, $girls . " Student(s)", 0, 0, 'L', false);
                            $pdf->Ln();
                        }
                        $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                        $pdf->Cell(20, 5, ($girls + $boys) . " Student(s)", 'T', 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', 'B', 8);
                        $width = array(7, 30, 17, 10, 45, 25, 45, 25, 20, 45,15);
                        $pdf->FancyTable($header, $data, $width);
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    } else {
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            // get the course
                            $all_courses = [];
                            $sel = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                            $statements = $conn2->prepare($sel);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }
    
                            // get the department
                            $all_department = [];
                            $sel = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                            $statements = $conn2->prepare($sel);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }

                            $pdf = new PDF('L', 'mm', 'A4');
                            $tittle = $select_report_class != "all" ? "List for " . classNameReport($select_report_class) . "" : "Student List for Whole School";
                            $intake_title = (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " : Intake ".$intake_months_reports." ".$intake_year_reports."" : "";
                            $tittle.=$intake_title;
                            $STATUS_TITLE = $student_status == 1 ? " : Active" : ($student_status == 2 ? "" : " : In-Active");
                            $tittle.=$STATUS_TITLE;
                            $pdf->set_document_title($tittle);
                            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                            $pdf->set_school_name($_SESSION['schname']);
                            $pdf->set_school_po($_SESSION['po_boxs']);
                            $pdf->set_school_box_code($_SESSION['box_codes']);
                            $pdf->set_school_contact($_SESSION['school_contact']);

                            // select statement
                            $select = $select . " WHERE `stud_class` = ?";

                            // add gender option
                            $select_gender_option = $_POST['select_gender_option'];
                            $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                            $select .= $gender_option;


                            $select .=  (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " AND `intake_year` = '".$intake_year_reports."' AND `intake_month` = '".$intake_months_reports."' " : "";
                            for ($index = 0; $index < count($school_classes); $index++) {
                                $stmt = $conn2->prepare($select);
                                $stmt->bind_param("s", $school_classes[$index]);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $student_data = [];
                                $number = 1;
                                $boys = 0;
                                $girls = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                    $adm_no = $row['adm_no'];
                                    $gender = $row['gender'];
                                    if ($gender == "Male") {
                                        $boys++;
                                    } else {
                                        $girls++;
                                    }
                                    $level_name = classNameReport($row['stud_class']);
                                    $dob = $row['D_O_B'];
                                    $date1 = date_create($dob);
                                    $date2 = date_create(date("Y-m-d"));
                                    $diff = date_diff($date1, $date2);
                                    $diffs = $diff->format("%y Yrs");
                                    $dob = $row['D_O_B'] . " | " . $diffs . "";
                                    $doa = $row['D_O_A'];
                                    $parentName = ucwords(strtolower($row['parentName']));
                                    $parentContacts = $row['parentContacts'];
                                    $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                    $parent_contact2 = $row['parent_contact2'];
                                    $address = $row['address'];
                                    $intake = $row['intake_month'].":".$row['intake_year'];

                                    // show departments
                                    $course_id = $row['course_done'];
                                    $course_name = "N/A";
                                    $department_id = null;
                                    for($ind =0; $ind < count($all_courses); $ind++){
                                        if($all_courses[$ind]->id == $course_id){
                                            $course_name = $all_courses[$ind]->course_name;
                                            $department_id = $all_courses[$ind]->department;
                                            break;
                                        }
                                    }

                                    // get the department names
                                    $department_name = "N/A";
                                    for($ind = 0; $ind < count($all_department); $ind++){
                                        if($all_department[$ind]->code == $department_id){
                                            $department_name = $all_department[$ind]->name;
                                            break;
                                        }
                                    }

                                    // get their course progress details
                                    $course_progress = (isset($row['my_course_list']) && isJson_report($row['my_course_list'])) ? json_decode($row['my_course_list']) : [];
                                    $status = "In-Active";
        
                                    // get the course status
                                    for($in = 0; $in < count($course_progress); $in++){
                                        $course_status = $course_progress[$in]->course_status;
                                        if($course_status == 1){
                                            $module_terms = $course_progress[$in]->module_terms;
                                            for($ind = 0; $ind < count($module_terms); $ind++){
                                                if($module_terms[$ind]->status == 1){
                                                    // end date
                                                    $status = "Active";
                                                }
                                            }
                                        }
                                    }
        
                                    // student data
                                    // array_push($student_data[$index],$status);
        
                                    $push = false;
        
                                    if($status == "Active" && $student_status == 1){
                                        $push = true;
                                    }
                                    if($status == "In-Active" && $student_status == 0){
                                        $push = true;
                                    }
                                    if($student_status == 2){
                                        $push = true;
                                    }
                                    // echo json_encode($student_data);
        
                                    if($push){
                                        if ($gender == "Male") {
                                            // $boys++;
                                            // $gender = "M";
                                        } else {
                                            // $girls++;
                                            // $gender = "F";
                                        }
                                        // course level
                                        $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $intake, $department_name, $dob, $doa, $address,$status);
                                        array_push($student_data, $each_stud);
                                        $number++;
                                    }
                                }

                                // Column headings
                                $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Intake', 'Department', 'D.O.B', 'D.O.A', 'Address','Status');
                                // Data loading
                                // $data = $pdf->LoadData('countries.txt');
                                $data = $student_data;
                                $pdf->AddPage();
                                $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Times', 'I', 9);
                                if($select_gender_option == "all" || $select_gender_option == "male"){
                                    $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                                    $pdf->Cell(20, 5, $boys . " Student(s)", 0, 0, 'L', false);
                                    $pdf->Ln();
                                }
                                if($select_gender_option == "all" || $select_gender_option == "female"){
                                    $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                                    $pdf->Cell(20, 5, $girls . " Student(s)", 0, 0, 'L', false);
                                    $pdf->Ln();
                                }
                                $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                                $pdf->Cell(20, 5, ($girls + $boys) . " Student(s)", 'T', 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', 'BU', 9);
                                $pdf->Cell(50, 10, classNameReport($school_classes[$index]), 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', 'B', 8);
                                $width = array(7, 30, 17, 10, 45, 25, 45, 25, 20, 45,15);
                                $pdf->FancyTable($header, $data, $width);
                            }
                            $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                        }else{
                            echo "<p style='color:red;'>Classes not found!</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select the student course level to display the students information";
                }
            } elseif ($select_student_option == "students_admitted") {
                if (strlen($select_report_class) > 0 && strlen($select_date) > 0) {
                    $select = "SELECT * FROM `student_data` WHERE `D_O_A` = ? AND `stud_class` = ?";

                    // add gender option
                    $select_gender_option = $_POST['select_gender_option'];
                    $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                    $select .= $gender_option;

                    if ($select_report_class != "all") {
                        // display the student data per class
                        $tittle = classNameReport($select_report_class) . " admitted on " . date("dS M Y", strtotime($select_date));
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("ss", $select_date, $select_report_class);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        if ($result) {

                            // get the course
                            $all_courses = [];
                            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                            $statements = $conn2->prepare($select);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }
    
                            // get the department
                            $all_department = [];
                            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                            $statements = $conn2->prepare($select);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }

                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                if ($gender == "Male") {
                                    $boys++;
                                } else {
                                    $girls++;
                                }
                                $level_name = classNameReport($row['stud_class']);
                                $dob = $row['D_O_B'];
                                $date1 = date_create($dob);
                                $date2 = date_create(date("Y-m-d"));
                                $diff = date_diff($date1, $date2);
                                $diffs = $diff->format("%y Yrs");
                                $dob = $row['D_O_B'] . " | " . $diffs . "";
                                $doa = $row['D_O_A'];
                                $parentName = ucwords(strtolower($row['parentName']));
                                $parentContacts = $row['parentContacts'];
                                $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                $parent_contact2 = $row['parent_contact2'];
                                $address = $row['address'];

                                // show departments
                                $course_id = $row['course_done'];
                                $course_name = "N/A";
                                $department_id = null;
                                for($index =0; $index < count($all_courses); $index++){
                                    if($all_courses[$index]->id == $course_id){
                                        $course_name = $all_courses[$index]->course_name;
                                        $courses_name = $course_name;
                                        $department_id = $all_courses[$index]->department;
                                        break;
                                    }
                                }

                                // get the department names
                                $department_name = "N/A";
                                for($index = 0; $index < count($all_department); $index++){
                                    if($all_department[$index]->code == $department_id){
                                        $department_name = $all_department[$index]->name;
                                        break;
                                    }
                                }

                                // course level
                                $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $level_name, $department_name, $dob, $doa, $address);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                            $pdf = new PDF('L', 'mm', 'A4');
                            // Column headings
                            $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', 'D.O.B', 'D.O.A', 'Address');
                            // Data loading
                            // $data = $pdf->LoadData('countries.txt');
                            $data = $student_data;
                            // echo json_encode($data);
                            $pdf->set_document_title($tittle);
                            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                            $pdf->set_school_name($_SESSION['schname']);
                            $pdf->set_school_po($_SESSION['po_boxs']);
                            $pdf->set_school_box_code($_SESSION['box_codes']);
                            $pdf->set_school_contact($_SESSION['school_contact']);
                            $pdf->AddPage();
                            $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                            $pdf->Ln();
                            $pdf->SetFont('Times', 'I', 9);
                            if($select_gender_option == "all" || $select_gender_option == "male"){
                                $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, $boys . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                            }
                            if($select_gender_option == "all" || $select_gender_option == "female"){
                                $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, $girls . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                            }
                            $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                            $pdf->Cell(20, 5, ($girls + $boys) . " Student(s)", 'T', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Ln();
                            $pdf->SetFont('Helvetica', 'B', 8);
                            $width = array(7, 45, 17, 10, 45, 25, 45, 25, 20, 45);
                            $pdf->FancyTable($header, $data, $width);
                            $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                        } else {
                            echo "Please set the student course details and the date of admission to view the students information";
                        }
                    } else {
                        $select = "SELECT * FROM `student_data` WHERE `D_O_A` = '" . $select_date . "' AND `stud_class` != '-1'";

                        // add gender option
                        $select_gender_option = $_POST['select_gender_option'];
                        $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                        $select .= $gender_option;

                        // echo $select_report_class;
                        $tittle = "Students registered on " . date("dS M Y", strtotime($select_date));
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        if ($result) {
                            // get the course
                            $all_courses = [];
                            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                            $statements = $conn2->prepare($select);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }
    
                            // get the department
                            $all_department = [];
                            $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                            $statements = $conn2->prepare($select);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }

                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                if ($gender == "Male") {
                                    $boys++;
                                } else {
                                    $girls++;
                                }
                                $level_name = classNameReport($row['stud_class']);
                                $dob = $row['D_O_B'];
                                $date1 = date_create($dob);
                                $date2 = date_create(date("Y-m-d"));
                                $diff = date_diff($date1, $date2);
                                $diffs = $diff->format("%y Yrs");
                                $dob = $row['D_O_B'] . " | " . $diffs . "";
                                $doa = $row['D_O_A'];
                                $parentName = ucwords(strtolower($row['parentName']));
                                $parentContacts = $row['parentContacts'];
                                $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                $parent_contact2 = $row['parent_contact2'];
                                $address = $row['address'];

                                // show departments
                                $course_id = $row['course_done'];
                                $course_name = "N/A";
                                $department_id = null;
                                for($index =0; $index < count($all_courses); $index++){
                                    if($all_courses[$index]->id == $course_id){
                                        $course_name = $all_courses[$index]->course_name;
                                        $courses_name = $course_name;
                                        $department_id = $all_courses[$index]->department;
                                        break;
                                    }
                                }

                                // get the department names
                                $department_name = "N/A";
                                for($index = 0; $index < count($all_department); $index++){
                                    if($all_department[$index]->code == $department_id){
                                        $department_name = $all_department[$index]->name;
                                        break;
                                    }
                                }

                                // course level
                                $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $level_name, $department_name, $dob, $doa, $address);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                            $pdf = new PDF('L', 'mm', 'A4');
                            // Column headings
                            $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', 'D.O.B', 'D.O.A', 'Address');
                            // Data loading
                            // $data = $pdf->LoadData('countries.txt');
                            $data = $student_data;
                            $pdf->set_document_title($tittle);
                            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                            $pdf->set_school_name($_SESSION['schname']);
                            $pdf->set_school_po($_SESSION['po_boxs']);
                            $pdf->set_school_box_code($_SESSION['box_codes']);
                            $pdf->set_school_contact($_SESSION['school_contact']);
                            $pdf->AddPage();
                            $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                            $pdf->Ln();
                            $pdf->SetFont('Times', 'I', 9);
                            if($select_gender_option == "all" || $select_gender_option == "male"){
                                $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, $boys . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                            }
                            if($select_gender_option == "all" || $select_gender_option == "female"){
                                $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, $girls . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                            }
                            $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                            $pdf->Cell(20, 5, ($girls + $boys) . " Student(s)", 'T', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Ln();
                            $pdf->SetFont('Helvetica', 'B', 8);
                            $width = array(7, 45, 17, 10, 45, 25, 45, 25, 20, 45);
                            $pdf->FancyTable($header, $data, $width);
                            $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                        } else {
                            echo "<p style='color:red;'><b>Note:</b><br>Please set the student course details and the date of admission to view the students information";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please set the student course details and the date of admission to view the students information";
                }
            } elseif ($select_student_option == "school_in_attendance") {
                // check if the class and the dates are set so that we can display the students present and the ones absent
                if (strlen($select_date) > 0 && strlen($select_report_class) > 0) {
                    if ($select_report_class != "all") {
                        // get all the students present
                        $select = "SELECT * FROM `attendancetable` WHERE `class` = '" . $select_report_class . "' AND `date` = '" . $select_date . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $students_present = [];
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                array_push($students_present, $row['admission_no']);
                            }
                        }
                        // get all students and seperate the present from the absent
                        // we are going to use a potrait A4 page 
                        $select  = "SELECT * FROM `student_data` WHERE `stud_class` = '" . $select_report_class . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 0;
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                $stud_class = classNameReport($row['stud_class']);
                                $each_stud = array($number, $student_name, $adm_no, $gender, $stud_class);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                        }
                        // get the data for the students that are present and those that are absent
                        $present_data = [];
                        $absent_data = [];
                        $numbP = 1;
                        $numbA = 1;
                        for ($indexes = 0; $indexes < count($student_data); $indexes++) {
                            if (checkPresentReport($students_present, $student_data[$indexes][2])) {
                                $student_data[$indexes][0] = $numbP;
                                array_push($present_data, $student_data[$indexes]);
                                $numbP++;
                            } else {
                                $student_data[$indexes][0] = $numbA;
                                array_push($absent_data, $student_data[$indexes]);
                                $numbA++;
                            }
                        }
                        // echo  count($absent_data);
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        // Column headings
                        $header = array('No', 'Student Name', 'Reg no', 'Gender', 'Class', 'Status');
                        // Data loading
                        // $data = $pdf->LoadData('countries.txt');
                        $data = $student_data;
                        $tittle = classNameReport($select_report_class) . " attendance on " . date("dS M Y", strtotime($select_date));
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        // statistic table
                        $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 10);
                        $pdf->Cell(20, 5, "Present :", 0, 0, 'L', false);
                        $pdf->Cell(20, 5, count($present_data) . " Student(s)", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(20, 5, "Absent :", 0, 0, 'L', false);
                        $pdf->Cell(20, 5, count($absent_data) . " Student(s)", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Ln();
                        // display present students
                        $pdf->SetFont('Helvetica', 'BU', 10);
                        $pdf->Cell(50, 10, "Present List", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', '', 10);
                        // the a present attendance table
                        $pdf->AttendanceTable($header, $present_data, "Present");
                        $pdf->Ln();
                        $pdf->Ln();
                        // display present students
                        $pdf->SetFont('Helvetica', 'BU', 10);
                        $pdf->Cell(50, 10, "Absent List", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', '', 10);
                        // the a present attendance table
                        $pdf->AttendanceTable($header, $absent_data, "Absent");
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    } else {
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            $pdf = new PDF('P', 'mm', 'A4');
                            $pdf->setHeaderPos(200);
                            $tittle = "School attendance on " . date("dS M Y", strtotime($select_date));
                            $pdf->set_document_title($tittle);
                            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                            $pdf->set_school_name($_SESSION['schname']);
                            $pdf->set_school_po($_SESSION['po_boxs']);
                            $pdf->set_school_box_code($_SESSION['box_codes']);
                            $pdf->set_school_contact($_SESSION['school_contact']);
                            for ($ind = 0; $ind < count($school_classes); $ind++) {
                                // get all the students present
                                $select = "SELECT * FROM `attendancetable` WHERE `class` = '" . $school_classes[$ind] . "' AND `date` = '" . $select_date . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $students_present = [];
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        array_push($students_present, $row['admission_no']);
                                    }
                                }
                                // get all students and seperate the present from the absent
                                // we are going to use a potrait A4 page 
                                $select  = "SELECT * FROM `student_data` WHERE `stud_class` = '" . $school_classes[$ind] . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $student_data = [];
                                $number = 0;
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                        $adm_no = $row['adm_no'];
                                        $gender = $row['gender'];
                                        $stud_class = classNameReport($row['stud_class']);
                                        $each_stud = array($number, $student_name, $adm_no, $gender, $stud_class);
                                        array_push($student_data, $each_stud);
                                        $number++;
                                    }
                                }
                                // get the data for the students that are present and those that are absent
                                $present_data = [];
                                $absent_data = [];
                                $numbP = 1;
                                $numbA = 1;
                                for ($indexes = 0; $indexes < count($student_data); $indexes++) {
                                    if (checkPresentReport($students_present, $student_data[$indexes][2])) {
                                        $student_data[$indexes][0] = $numbP;
                                        array_push($present_data, $student_data[$indexes]);
                                        $numbP++;
                                    } else {
                                        $student_data[$indexes][0] = $numbA;
                                        array_push($absent_data, $student_data[$indexes]);
                                        $numbA++;
                                    }
                                }
                                // Column headings
                                $header = array('No', 'Student Name', 'Reg no', 'Gender', 'Class', 'Status');
                                $pdf->AddPage();
                                // statistic table
                                $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Times', 'I', 9);
                                $pdf->Cell(20, 5, "Present :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, count($present_data) . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Cell(20, 5, "Absent :", 0, 0, 'L', false);
                                $pdf->Cell(20, 5, count($absent_data) . " Student(s)", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', 'BU', 11);
                                $pdf->Cell(50, 10, classNameReport($school_classes[$ind]), 0, 0, 'L', false);
                                $pdf->Ln();
                                // display present students
                                $pdf->SetFont('Helvetica', 'BU', 10);
                                $pdf->Cell(50, 10, "Present List", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', 'B', 9);
                                // the a present attendance table
                                $pdf->AttendanceTable($header, $present_data, "Present");
                                $pdf->Ln();
                                $pdf->Ln();
                                $pdf->Ln();
                                $pdf->Ln();
                                // display present students
                                $pdf->SetFont('Helvetica', 'BU', 10);
                                $pdf->Cell(50, 10, "Absent List", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', '', 10);
                                $pdf->SetFont('Helvetica', 'B', 9);
                                // the a present attendance table
                                $pdf->AttendanceTable($header, $absent_data, "Absent");
                            }
                            $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                        } else {
                            echo "<p style='color:red;'><b>Note:</b><br>No classes have been found</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select the student course level to display the students information</p>";
                }
            } elseif ($select_student_option == "show_alumni") {
                $select = "SELECT * FROM `student_data` WHERE `stud_class` = '-1'";

                // add gender option
                $select_gender_option = $_POST['select_gender_option'];
                $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                $select .= $gender_option;

                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $student_data = [];
                $number = 1;
                $boys = 0;
                $girls = 0;

                // get the course
                $all_courses = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }

                // get the department
                $all_department = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }

                while ($row = $result->fetch_assoc()) {
                    $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                    $adm_no = $row['adm_no'];
                    $gender = $row['gender'];
                    if ($gender == "Male") {
                        $boys++;
                    } else {
                        $girls++;
                    }
                    $level_name = classNameReport($row['stud_class']);
                    $dob = $row['D_O_B'];
                    $date1 = date_create($dob);
                    $date2 = date_create(date("Y-m-d"));
                    $diff = date_diff($date1, $date2);
                    $diffs = $diff->format("%y Yrs");
                    $dob = $row['D_O_B'] . " | " . $diffs . "";
                    $doa = $row['D_O_A'];
                    $parentName = ucwords(strtolower($row['parentName']));
                    $parentContacts = $row['parentContacts'];
                    $parent_name2 = ucwords(strtolower($row['parent_name2']));
                    $parent_contact2 = $row['parent_contact2'];
                    $address = $row['address'];

                    // show departments
                    $course_id = $row['course_done'];
                    $course_name = "N/A";
                    $department_id = null;
                    for($index =0; $index < count($all_courses); $index++){
                        if($all_courses[$index]->id == $course_id){
                            $course_name = $all_courses[$index]->course_name;
                            $courses_name = $course_name;
                            $department_id = $all_courses[$index]->department;
                            break;
                        }
                    }

                    // get the department names
                    $department_name = "N/A";
                    for($index = 0; $index < count($all_department); $index++){
                        if($all_department[$index]->code == $department_id){
                            $department_name = $all_department[$index]->name;
                            break;
                        }
                    }

                    // course level
                    $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $level_name, $department_name, $dob, $doa, $address);
                    array_push($student_data, $each_stud);
                    $number++;
                }
                $pdf = new PDF('L', 'mm', 'A4');
                // Column headings
                $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', 'D.O.B', 'D.O.A', 'Address');

                // Data loading
                // $data = $pdf->LoadData('countries.txt');
                $tittle = "Alumni List";
                $data = $student_data;
                $pdf->set_document_title($tittle);
                $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                $pdf->set_school_name($_SESSION['schname']);
                $pdf->set_school_po($_SESSION['po_boxs']);
                $pdf->set_school_box_code($_SESSION['box_codes']);
                $pdf->set_school_contact($_SESSION['school_contact']);
                $pdf->AddPage();
                $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                $pdf->Ln();
                $pdf->SetFont('Times', 'I', 11);
                $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                $pdf->Cell(20, 5, $boys . " Student(s)", 0, 0, 'L', false);
                $pdf->Ln();
                $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                $pdf->Cell(20, 5, $girls . " Student(s)", 0, 0, 'L', false);
                $pdf->Ln();
                $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                $pdf->Cell(20, 5, ($girls + $boys) . " Student(s)", 'T', 0, 'L', false);
                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Helvetica', 'B', 8);
                $width = array(7, 45, 17, 10, 45, 25, 45, 25, 20, 45);
                $pdf->FancyTable($header, $data, $width);
                $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
            }
        } elseif ($select_entity == "staff") {
            if ($staff_options == "staff_details") {
                // get all the staff details
                $select = "SELECT * FROM `user_tbl` WHERE `school_code` = '" . $_SESSION['schcode'] . "'";
                $stmt = $conn->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $staff_data = [];
                $boys = 0;
                $girls = 0;
                $number = 1;
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $fullname = ucwords(strtolower($row['fullname']));
                        $dob = $row['dob'];
                        $phone_number = $row['phone_number'];
                        $id_number = $row['nat_id'];
                        $payroll = $row['payroll'] == "enabled" ? "Enrolled" : "Not-enrolled";
                        $auth = ucwords(strtolower(authority($row['auth'])));
                        $gender = $row['gender'] == "M" ? "M" : "F";
                        if ($row['gender'] == "M") {
                            $boys++;
                        } else {
                            $girls++;
                        }
                        $tsc_no = $row['tsc_no'];
                        $status = $row['activated'] == 1 ? "Active" : "In-active";
                        $username = $row['username'];
                        $date1 = date_create($dob);
                        $date2 = date_create(date("Y-m-d"));
                        $diff = date_diff($date1, $date2);
                        $diffs = $diff->format("%y Yrs");
                        $staffs = array($number, $fullname, $diffs, $phone_number, $gender, $id_number, $payroll, $auth, $status, $username, $tsc_no);
                        array_push($staff_data, $staffs);
                        $number++;
                    }

                    // pdf
                    $pdf = new PDF('L', 'mm', 'A4');
                    // Column headings
                    $header = array('No', 'Full Name', 'Age', 'Contact', 'Sex', 'I`d no', 'Payroll', 'Role', 'Status', 'Username', 'Emp No.');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    // echo count($staff_data);
                    $data = $staff_data;
                    $tittle = "Staff Details";
                    $pdf->set_document_title($tittle);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->AddPage();
                    $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'I', 11);
                    $pdf->Cell(20, 5, "Male :", 0, 0, 'L', false);
                    $pdf->Cell(20, 5, $boys . " Staff(s)", 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(20, 5, "Female :", 0, 0, 'L', false);
                    $pdf->Cell(20, 5, $girls . " Staff(s)", 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(20, 5, "Total :", 'T', 0, 'L', false);
                    $pdf->Cell(20, 5, ($girls + $boys) . " Staff(s)", 'T', 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'B', 9);
                    $width = array(7, 50, 15, 25, 10, 20, 20, 30, 17, 40, 40);
                    $pdf->StaffData($header, $data, $width);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                }
            } elseif ($staff_options == "logs") {
                // check if date is set
                if (strlen($select_date_staff) > 0) {
                    $select = "SELECT * FROM `logs` WHERE `date` = '" . $select_date_staff . "'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $mystaff = getStaffData($conn);
                    $staff_log = [];
                    if ($result) {
                        $number = 1;
                        $all_active = 0;
                        while ($row = $result->fetch_assoc()) {
                            $login_time = $row['login_time'];
                            $active_time = $row['active_time'];
                            $user_id = $row['user_id'];
                            $date = $row['date'];
                            $contacts = getStaffDets($mystaff, $user_id)[0];
                            $staff_address = getStaffDets($mystaff, $user_id)[1];
                            $role = authority(getStaffDets($mystaff, $user_id)[2]);
                            $fullname = ucwords(strtolower(getStaffNamedReport($mystaff, $user_id)));
                            $my_data = array($number, $fullname, $role, $login_time, $active_time, $date, $staff_address, $contacts);
                            array_push($staff_log, $my_data);
                            $all_active++;
                            $number++;
                        }
                        $pdf = new PDF('P', 'mm', 'A4');
                        // Column headings
                        $header = array('No', 'Full Name', 'Role', 'Login Time', 'Last Active', 'Date', 'Address', 'Contact');
                        // Data loading
                        // $data = $pdf->LoadData('countries.txt');
                        // echo count($staff_data);
                        $data = $staff_log;
                        $tittle = "Staffs Active on " . date("dS M Y", strtotime($select_date_staff));
                        $pdf->set_document_title($tittle);
                        $pdf->setHeaderPos(200);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        $pdf->Cell(40, 10, "Population", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 10);
                        $pdf->Cell(20, 5, "Total Active : ", 'T', 0, 'L', false);
                        $pdf->Cell(20, 5, $all_active . " Staffs", 'T', 0, 'R', false);
                        $pdf->Ln();
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', '', 9);
                        $width = array(10, 35, 35, 20, 20, 20, 25, 25);
                        $pdf->logTables($header, $data, $width);
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select date option to procced!</p>";
                }
            } elseif ($staff_options == "class_teachers") {
                $select = "SELECT * FROM `class_teacher_tbl`";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $assigned_class_teacher = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($assigned_class_teacher, $row);
                    }
                }
                // get the data of the class teacher that are available 
                $mystaff = getStaffData($conn);
                // go through the class teacher list and get those that ara assigned classes and those that are not assigned classes
                $teacher_data = [];
                $number = 1;
                for ($index = 0; $index < count($mystaff); $index++) {
                    if ($mystaff[$index]['auth'] == "5") {
                        // get if the class teacher is assigned or not
                        $taecher_name = ucwords(strtolower($mystaff[$index]['fullname']));
                        $gender = $mystaff[$index]['gender'];
                        $class_assigned = "Not-Assigned";
                        $student_count = "N/A";
                        $contacts = $mystaff[$index]['phone_number'];
                        for ($ind = 0; $ind < count($assigned_class_teacher); $ind++) {
                            if ($assigned_class_teacher[$ind]['class_teacher_id'] == $mystaff[$index]['user_id']) {
                                // get the class 
                                $class_assigned = classNameReport($assigned_class_teacher[$ind]['class_assigned']);
                                $student_count = count(getStudents($assigned_class_teacher[$ind]['class_assigned'], $conn2)) . " Student(s)";
                            }
                        }
                        $class_tr = array($number, $taecher_name, $gender, $class_assigned, $student_count, $contacts);
                        $number++;
                        array_push($teacher_data, $class_tr);
                    }
                }
                if (count($teacher_data) > 0) {
                    // create the pdf
                    $pdf = new PDF('P', 'mm', 'A4');
                    // Column headings
                    $header = array('No', 'Fullname', 'Sex', 'Class Assigned', 'Total Students', 'Contacts');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    // echo count($staff_data);
                    $data = $teacher_data;
                    $tittle = "Class Teacher List ";
                    $pdf->set_document_title($tittle);
                    $pdf->setHeaderPos(200);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->AddPage();
                    $pdf->SetFont('Times', 'UI', 13);
                    $pdf->Cell(150, 8, "Class Teacher Table", 0, 0, 'C', false);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', '', 10);
                    $width = array(10, 40, 10, 35, 35, 35);
                    $pdf->classTrData($header, $data, $width);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>No classteachers present!</p>";
                }
            } else {
                echo "<p style='color:red;'><b>Note:</b><br>Please select an option to procced!</p>";
            }
        }
    }elseif (isset($_POST['select_report_class']) && isset($_POST['xslx']) && isset($_POST['select_entity'])) {
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $table_style = [
            'font' => [
                'bold' => true,
                'name' => 'Calibri Light',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF9DB7B8',
                ],
            ],
        ];
        $table_style_2 = [
            'font' => [
                'bold' => false,
                'name' => 'Calibri Light',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        $select_entity = $_POST['select_entity'];
        $select_student_option = $_POST['select_student_option'];
        $select_report_class = $_POST['select_report_class'];
        $select_date = $_POST['select_date'];
        $from_date_report = $_POST['from_date_report'];
        $to_date_report = $_POST['to_date_report'];
        $staff_options = $_POST['staff_options'];
        $select_date_staff = $_POST['select_date_staff'];
        $course_names = $_POST['course_name'];
        $intake_months_reports = $_POST['intake_months_reports'];
        $intake_year_reports = $_POST['intake_year_reports'];

        if ($select_entity == "student") {
            // get the student data per class
            if ($select_student_option == "all_students") {
                if((strlen($intake_year_reports) > 0 && strlen($intake_months_reports) == 0) || strlen($intake_year_reports) == 0 && strlen($intake_months_reports) > 0){
                    echo "<p style='color:red;'>Ensure you`ve selected the intake month and year!</p>";
                    return 0;
                }

                // get the course
                $all_courses = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }

                // get the department
                $all_department = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }
                // get the class the student is selected
                if (strlen($select_report_class) > 0) {
                    $select = "SELECT * FROM `student_data` ";
                    $add_course = strlen(trim($course_names)) > 0 ? " AND `course_done` = '".$course_names."' " : "";
                    $condition = $select_report_class != "all" ? " WHERE `stud_class` = '$select_report_class' ".$add_course."" : " WHERE `stud_class` != '-1' AND `stud_class` != '-2'";
                    // add the intake condition
                    $intake_condition = (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " AND `intake_year` = '".$intake_year_reports."' AND `intake_month` = '".$intake_months_reports."' " : "";
                    $condition.=$intake_condition;

                    // gender select option
                    $select_gender_option = $_POST['select_gender_option'];
                    $gender_option = $select_gender_option == "all" ? "" : "AND `gender` = '".$select_gender_option."'";
                    $condition .= $gender_option;

                    // get for specific class
                    if ($select_report_class != "all") {
                        $select = $select . $condition;
                        // 
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        
                        // course titles
                        $courses_name = "N/A";
                        for($index =0; $index < count($all_courses); $index++){
                            if($all_courses[$index]->id == $course_names){
                                $course_name = $all_courses[$index]->course_name;
                                $courses_name = $course_name;
                                break;
                            }
                        }

                        // course title
                        $course_title = strlen(trim($course_names)) > 0 ? " in ".$courses_name." " : "";
                        $tittle = $select_report_class != "all" ? "List for " . classNameReport($select_report_class) . " $course_title" : "Student List for Whole School";

                        while ($row = $result->fetch_assoc()) {
                            $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                            $adm_no = $row['adm_no'];
                            $gender = $row['gender'];
                            if ($gender == "Male") {
                                $boys++;
                            } else {
                                $girls++;
                            }
                            $level_name = classNameReport($row['stud_class']);
                            $dob = $row['D_O_B'];
                            $date1 = date_create($dob);
                            $date2 = date_create(date("Y-m-d"));
                            $diff = date_diff($date1, $date2);
                            $diffs = $diff->format("%y Yrs");
                            $dob = $row['D_O_B'] . " | " . $diffs . "";
                            $doa = $row['D_O_A'];
                            $parentName = ucwords(strtolower($row['parentName']));
                            $parentContacts = $row['parentContacts'];
                            $parent_name2 = ucwords(strtolower($row['parent_name2']));
                            $parent_contact2 = $row['parent_contact2'];

                            // show departments
                            $course_id = $row['course_done'];
                            $course_name = "N/A";
                            $department_id = null;
                            for($index =0; $index < count($all_courses); $index++){
                                if($all_courses[$index]->id == $course_id){
                                    $course_name = $all_courses[$index]->course_name;
                                    $courses_name = $course_name;
                                    $department_id = $all_courses[$index]->department;
                                    break;
                                }
                            }

                            // get the department names
                            $department_name = "N/A";
                            for($index = 0; $index < count($all_department); $index++){
                                if($all_department[$index]->code == $department_id){
                                    $department_name = $all_department[$index]->name;
                                    break;
                                }
                            }
                            $each_stud = array($number, $student_name, $adm_no, $gender,  $course_name, $level_name, $department_name, $row['intake_month'],$row['intake_year'], $dob, $doa, $parentName, $parentContacts, $parent_name2, $parent_contact2);
                            array_push($student_data, $each_stud);
                            $number++;
                        }
                        $data = $student_data;

                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();

                        // Set document properties
                        $tittle = strlen($tittle) > 31 ? substr($tittle,0,31) : $tittle;
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
                            $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department',"Intake Month", "Intake Year", 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                        
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle($tittle);
                        // set the statistics
                        $worksheet->setCellValue("A1", "Population");
                        $worksheet->setCellValue("A2", "Male");
                        $worksheet->setCellValue("A3", "Female");
                        $worksheet->setCellValue("B2", $boys . " Student(s)");
                        $worksheet->setCellValue("B3", $girls . " Student(s)");
                        $worksheet->setCellValue("A4", "Total");
                        $worksheet->setCellValue("B4", ($boys+$girls)." Student(s)");

                        // set the header
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);

                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+8), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."".(count($data)+7))->applyFromArray($table_style_2);

                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        // set auto width

                        // spreadsheet
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        
                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');

                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                    } else {
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            // document tittle
                            $tittle = $select_report_class != "all" ? "List for " . classNameReport($select_report_class) . "" : "Student List for Whole School";
                            $intake_title = (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " : Intake ".$intake_months_reports." ".$intake_year_reports."" : "";
                            $tittle.=$intake_title;

                            // select statment
                            $select = $select . " WHERE `stud_class` = ?";

                            // gender select option
                            $select_gender_option = $_POST['select_gender_option'];
                            $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                            $condition .= $gender_option;

                            $select .=  (strlen($intake_year_reports) > 0 && strlen($intake_months_reports) > 0) ? " AND `intake_year` = '".$intake_year_reports."' AND `intake_month` = '".$intake_months_reports."' " : "";

                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();

                            // Set document properties
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);

                                // loop to generate excel sheets
                            for ($index = 0; $index < count($school_classes); $index++) {
                                $stmt = $conn2->prepare($select);
                                $stmt->bind_param("s", $school_classes[$index]);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $student_data = [];
                                $number = 1;
                                $boys = 0;
                                $girls = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                    $adm_no = $row['adm_no'];
                                    $gender = $row['gender'];
                                    if ($gender == "Male") {
                                        $boys++;
                                    } else {
                                        $girls++;
                                    }
                                    $level_name = classNameReport($row['stud_class']);
                                    $dob = $row['D_O_B'];
                                    $date1 = date_create($dob);
                                    $date2 = date_create(date("Y-m-d"));
                                    $diff = date_diff($date1, $date2);
                                    $diffs = $diff->format("%y Yrs");
                                    $dob = $row['D_O_B'] . " | " . $diffs . "";
                                    $doa = $row['D_O_A'];
                                    $parentName = ucwords(strtolower($row['parentName']));
                                    $parentContacts = $row['parentContacts'];
                                    $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                    $parent_contact2 = $row['parent_contact2'];
        
                                    // show departments
                                    $course_id = $row['course_done'];
                                    $course_name = "N/A";
                                    $department_id = null;
                                    for($in =0; $in < count($all_courses); $in++){
                                        if($all_courses[$in]->id == $course_id){
                                            $course_name = $all_courses[$in]->course_name;
                                            $courses_name = $course_name;
                                            $department_id = $all_courses[$in]->department;
                                            break;
                                        }
                                    }
        
                                    // get the department names
                                    $department_name = "N/A";
                                    for($in = 0; $in < count($all_department); $in++){
                                        if($all_department[$in]->code == $department_id){
                                            $department_name = $all_department[$in]->name;
                                            break;
                                        }
                                    }
                                    $each_stud = array($number, $student_name, $adm_no, $gender,  $course_name, $level_name, $department_name, $row['intake_month'],$row['intake_year'],$dob, $doa, $parentName, $parentContacts, $parent_name2, $parent_contact2);
                                    array_push($student_data, $each_stud);
                                    $number++;
                                }
                                
                                $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', "Intake Month", "Intake Year", 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                                
                                // if its the first sheet dont add the data
                                if ($index == 0) {
                                    // Add data
                                    $worksheet = $spreadsheet->getActiveSheet();
                                    $worksheet->setTitle(classNameReport($school_classes[$index]));
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Population");
                                    $worksheet->setCellValue("A2", "Male");
                                    $worksheet->setCellValue("A3", "Female");
                                    $worksheet->setCellValue("B2", $boys . " Student(s)");
                                    $worksheet->setCellValue("B3", $girls . " Student(s)");
                                    $worksheet->setCellValue("A4", "Total");
                                    $worksheet->setCellValue("B4", ($boys+$girls)." Student(s)");

                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                                    }
                                    $spreadsheet->getActiveSheet()->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);
            
                                    // set the values for the data
                                    for ($index1=0; $index1 < count($student_data); $index1++) { 
                                        for($index2=0; $index2 < count($student_data[$index1]); $index2++){
                                            $worksheet->setCellValue("".$letters[$index2]."".($index1+8), $student_data[$index1][$index2]);
                                        }
                                    }
                                    $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."".(count($student_data)+7))->applyFromArray($table_style_2);
                                }else{
                                    // Add data
                                    $worksheet = $spreadsheet->createSheet();
                                    $worksheet->setTitle(substr(classNameReport($school_classes[$index]),0,31));
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Population");
                                    $worksheet->setCellValue("A2", "Male");
                                    $worksheet->setCellValue("A3", "Female");
                                    $worksheet->setCellValue("B2", $boys . " Student(s)");
                                    $worksheet->setCellValue("B3", $girls . " Student(s)");
                                    $worksheet->setCellValue("A4", "Total");
                                    $worksheet->setCellValue("B4", ($boys+$girls)." Student(s)");

                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                                    }
                                    $worksheet->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);
            
                                    // set the values for the data
                                    for ($index1=0; $index1 < count($student_data); $index1++) { 
                                        for($index2=0; $index2 < count($student_data[$index1]); $index2++){
                                            $worksheet->setCellValue("".$letters[$index2]."".($index1+8), $student_data[$index1][$index2]);
                                        }
                                    }
                                    $worksheet->getStyle("A8:".$letters[count($header)-1]."".(count($student_data)+7))->applyFromArray($table_style_2);
                                }
                            }
                            // Set active sheet index to the first sheet
                            $spreadsheet->setActiveSheetIndex(0);
                            // set auto width
                            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                                // set auto width
                                for ($indexing=0; $indexing < count($header); $indexing++) {
                                    $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                                }
                            }
                            // Redirect output to a client’s web browser (Xls)
                            header('Content-Type: application/vnd.ms-excel');;
                            header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                            header('Cache-Control: max-age=0');

                            $writer = new Xls($spreadsheet);
                            $writer->save('php://output');
                            exit;
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select the student course level to display the students information";
                }
            } elseif ($select_student_option == "students_admitted") {

                // get the course
                $all_courses = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }

                // get the department
                $all_department = [];
                $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                $statements = $conn2->prepare($select);
                $statements->execute();
                $res = $statements->get_result();
                if($res){
                    if($rows = $res->fetch_assoc()){
                        $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                    }
                }
                if (strlen($select_report_class) > 0 && strlen($select_date) > 0) {
                    $select = "SELECT * FROM `student_data` WHERE `D_O_A` = ? AND `stud_class` = ?";

                    // gender select option
                    $select_gender_option = $_POST['select_gender_option'];
                    $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                    $select .= $gender_option;

                    if ($select_report_class != "all") {
                        // display the student data per class
                        $tittle = classNameReport($select_report_class) . " admitted on " . date("dS M Y", strtotime($select_date));
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("ss", $select_date, $select_report_class);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                if ($gender == "Male") {
                                    $boys++;
                                } else {
                                    $girls++;
                                }
                                $level_name = classNameReport($row['stud_class']);
                                $dob = $row['D_O_B'];
                                $date1 = date_create($dob);
                                $date2 = date_create(date("Y-m-d"));
                                $diff = date_diff($date1, $date2);
                                $diffs = $diff->format("%y Yrs");
                                $dob = $row['D_O_B'] . " | " . $diffs . "";
                                $doa = $row['D_O_A'];
                                $parentName = ucwords(strtolower($row['parentName']));
                                $parentContacts = $row['parentContacts'];
                                $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                $parent_contact2 = $row['parent_contact2'];

                                // show departments
                                $course_id = $row['course_done'];
                                $course_name = "N/A";
                                $department_id = null;
                                for($index =0; $index < count($all_courses); $index++){
                                    if($all_courses[$index]->id == $course_id){
                                        $course_name = $all_courses[$index]->course_name;
                                        $courses_name = $course_name;
                                        $department_id = $all_courses[$index]->department;
                                        break;
                                    }
                                }

                                // get the department names
                                $department_name = "N/A";
                                for($index = 0; $index < count($all_department); $index++){
                                    if($all_department[$index]->code == $department_id){
                                        $department_name = $all_department[$index]->name;
                                        break;
                                    }
                                }
                                $each_stud = array($number, $student_name, $adm_no, $gender,  $course_name, $level_name, $department_name,$row['intake_month'],$row['intake_year'],$dob, $doa, $parentName, $parentContacts, $parent_name2, $parent_contact2);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();
    
                            // Set document properties
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);
                            // Column headings
                            $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', "Intake Month", "Intake Year", 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                            
                            // Data loading
                            $data = $student_data;

                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();
    
                            // Set document properties
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);
                            // $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Class', 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                            
                            // Add data
                            $worksheet = $spreadsheet->getActiveSheet();
                            $worksheet->setTitle("Admitted on ".date("dS M Y", strtotime($select_date)));
                            // set the statistics
                            $worksheet->setCellValue("A1", "Population");
                            $worksheet->setCellValue("A2", "Male");
                            $worksheet->setCellValue("A3", "Female");
                            $worksheet->setCellValue("B2", $boys . " Student(s)");
                            $worksheet->setCellValue("B3", $girls . " Student(s)");
                            $worksheet->setCellValue("A4", "Total");
                            $worksheet->setCellValue("B4", ($boys+$girls)." Student(s)");
    
                            // set the header
                            for ($i = 0; $i < count($header); $i++) {
                                $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                            }
                            $spreadsheet->getActiveSheet()->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);
    
                            // set the values for the data
                            for ($index=0; $index < count($data); $index++) { 
                                for($index1=0; $index1 < count($data[$index]); $index1++){
                                    $worksheet->setCellValue("".$letters[$index1]."".($index+8), $data[$index][$index1]);
                                }
                            }
                            $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."".(count($data)+7))->applyFromArray($table_style_2);
    
                            // Set active sheet index to the first sheet
                            $spreadsheet->setActiveSheetIndex(0);
                            // set auto width
                            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                                // set auto width
                                for ($indexing=0; $indexing < count($header); $indexing++) {
                                    $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                                }
                            }
    
                            
                            // Redirect output to a client’s web browser (Xls)
                            header('Content-Type: application/vnd.ms-excel');;
                            header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                            header('Cache-Control: max-age=0');
    
                            $writer = new Xls($spreadsheet);
                            $writer->save('php://output');
                            exit;
                        } else {
                            echo "Please set the student course details and the date of admission to view the students information";
                        }
                    } else {
                        $select = "SELECT * FROM `student_data` WHERE `D_O_A` = '" . $select_date . "' AND `stud_class` != '-1'";
                    
                        // gender select option
                        $select_gender_option = $_POST['select_gender_option'];
                        $gender_option = $select_gender_option == "all" ? "" : " AND `gender` = '".$select_gender_option."'";
                        $condition .= $gender_option;
                        
                        // echo $select_report_class;
                        $tittle = "Students registered on " . date("dS M Y", strtotime($select_date));
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 1;
                        $boys = 0;
                        $girls = 0;
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                if ($gender == "Male") {
                                    $boys++;
                                } else {
                                    $girls++;
                                }
                                $level_name = classNameReport($row['stud_class']);
                                $dob = $row['D_O_B'];
                                $date1 = date_create($dob);
                                $date2 = date_create(date("Y-m-d"));
                                $diff = date_diff($date1, $date2);
                                $diffs = $diff->format("%y Yrs");
                                $dob = $row['D_O_B'] . " | " . $diffs . "";
                                $doa = $row['D_O_A'];
                                $parentName = ucwords(strtolower($row['parentName']));
                                $parentContacts = $row['parentContacts'];
                                $parent_name2 = ucwords(strtolower($row['parent_name2']));
                                $parent_contact2 = $row['parent_contact2'];

                                // show departments
                                $course_id = $row['course_done'];
                                $course_name = "N/A";
                                $department_id = null;
                                for($index =0; $index < count($all_courses); $index++){
                                    if($all_courses[$index]->id == $course_id){
                                        $course_name = $all_courses[$index]->course_name;
                                        $courses_name = $course_name;
                                        $department_id = $all_courses[$index]->department;
                                        break;
                                    }
                                }

                                // get the department names
                                $department_name = "N/A";
                                for($index = 0; $index < count($all_department); $index++){
                                    if($all_department[$index]->code == $department_id){
                                        $department_name = $all_department[$index]->name;
                                        break;
                                    }
                                }
                                $each_stud = array($number, $student_name, $adm_no, $gender, $course_name, $level_name, $department_name,$row['intake_month'],$row['intake_year'], $dob, $doa, $parentName, $parentContacts, $parent_name2, $parent_contact2);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                            // Column headings
                            $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Course', 'Level', 'Department', "Intake Month", "Intake Year", 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                            // Data loading
                            // $data = $pdf->LoadData('countries.txt');
                            $data = $student_data;
                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();
    
                            // Set document properties
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);
                            // $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Class', 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');
                            
                            // Add data
                            $worksheet = $spreadsheet->getActiveSheet();
                            $worksheet->setTitle("Admitted on ".date("dS M Y", strtotime($select_date)));
                            // set the statistics
                            $worksheet->setCellValue("A1", "Population");
                            $worksheet->setCellValue("A2", "Male");
                            $worksheet->setCellValue("A3", "Female");
                            $worksheet->setCellValue("B2", $boys . " Student(s)");
                            $worksheet->setCellValue("B3", $girls . " Student(s)");
                            $worksheet->setCellValue("A4", "Total");
                            $worksheet->setCellValue("B4", ($boys+$girls)." Student(s)");
    
                            // set the header
                            for ($i = 0; $i < count($header); $i++) {
                                $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                            }
                            $spreadsheet->getActiveSheet()->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);
    
                            // set the values for the data
                            for ($index=0; $index < count($data); $index++) { 
                                for($index1=0; $index1 < count($data[$index]); $index1++){
                                    $worksheet->setCellValue("".$letters[$index1]."".($index+8), $data[$index][$index1]);
                                }
                            }
                            $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."".(count($data)+7))->applyFromArray($table_style_2);
    
                            // Set active sheet index to the first sheet
                            $spreadsheet->setActiveSheetIndex(0);

                            // set auto width
                            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                                // set auto width
                                for ($indexing=0; $indexing < count($header); $indexing++) {
                                    $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                                }
                            }
    
                            
                            // Redirect output to a client’s web browser (Xls)
                            header('Content-Type: application/vnd.ms-excel');;
                            header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                            header('Cache-Control: max-age=0');
    
                            $writer = new Xls($spreadsheet);
                            $writer->save('php://output');
                            exit;
                        } else {
                            echo "<p style='color:red;'><b>Note:</b><br>Please set the student course details and the date of admission to view the students information";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please set the student course details and the date of admission to view the students information";
                }
            } elseif ($select_student_option == "school_in_attendance") {
                // check if the class and the dates are set so that we can display the students present and the ones absent
                if (strlen($select_date) > 0 && strlen($select_report_class) > 0) {
                    if ($select_report_class != "all") {
                        // get all the students present
                        $select = "SELECT * FROM `attendancetable` WHERE `class` = '" . $select_report_class . "' AND `date` = '" . $select_date . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $students_present = [];
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                array_push($students_present, $row['admission_no']);
                            }
                        }
                        // get all students and seperate the present from the absent
                        // we are going to use a potrait A4 page 
                        $select  = "SELECT * FROM `student_data` WHERE `stud_class` = '" . $select_report_class . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $student_data = [];
                        $number = 0;
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                $adm_no = $row['adm_no'];
                                $gender = $row['gender'];
                                $stud_class = classNameReport($row['stud_class']);
                                $each_stud = array($number, $student_name, $adm_no, $gender, $stud_class);
                                array_push($student_data, $each_stud);
                                $number++;
                            }
                        }
                        // get the data for the students that are present and those that are absent
                        $present_data = [];
                        $absent_data = [];
                        $numbP = 1;
                        $numbA = 1;
                        for ($indexes = 0; $indexes < count($student_data); $indexes++) {
                            if (checkPresentReport($students_present, $student_data[$indexes][2])) {
                                $student_data[$indexes][0] = $numbP;
                                array_push($present_data, $student_data[$indexes]);
                                $numbP++;
                            } else {
                                $student_data[$indexes][0] = $numbA;
                                array_push($absent_data, $student_data[$indexes]);
                                $numbA++;
                            }
                        }

                        // Column headings
                        $header = array('No', 'Student Name', 'Reg no', 'Gender', 'Class', 'Status');
                        $tittle = classNameReport($select_report_class) . " attendance on " . date("dS M Y", strtotime($select_date));
                       
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();

                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);

                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr("Attendance for ".classNameReport($select_report_class)." on ".date("dS M Y", strtotime($select_date)),0,31));

                        // set the statistics
                        $worksheet->setCellValue("A1", "Population");
                        $worksheet->setCellValue("A2", "Present");
                        $worksheet->setCellValue("A3", "Absent");
                        $worksheet->setCellValue("B2", count($present_data) . " Student(s)");
                        $worksheet->setCellValue("B3", count($absent_data) . " Student(s)");
                        $worksheet->setCellValue("A4", "Total");
                        $worksheet->setCellValue("B4", (count($present_data)+count($absent_data))." Student(s)");
    
                        // set the header
                        $worksheet->setCellValue("A7", "Absent Student");
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                        }
                        $data = $absent_data;
                        $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);

                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A9:".$letters[count($header)-1]."".(count($data)+9))->applyFromArray($table_style_2);

                        // where the present ones will appear row id will continue from
                        $proceed_from = count($data)+11;
                        $worksheet->setCellValue("A".$proceed_from, "Present Student");
                        $proceed_from+=1;
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."".($proceed_from), $header[$i]);
                        }
                        $data = $present_data;
                        $spreadsheet->getActiveSheet()->getStyle("A$proceed_from:".$letters[count($header)-1]."$proceed_from")->applyFromArray($table_style);

                        // set the values for the data
                        $proceed_from+=1;
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+$proceed_from), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A".($proceed_from).":".$letters[count($header)-1]."".(count($data)+$proceed_from-1))->applyFromArray($table_style_2);

                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);

                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        
                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');

                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            $tittle = "School attendance on " . date("dS M Y", strtotime($select_date));
                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();

                            // Set document properties
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);

                            for ($ind = 0; $ind < count($school_classes); $ind++) {
                                // get all the students present
                                $select = "SELECT * FROM `attendancetable` WHERE `class` = '" . $school_classes[$ind] . "' AND `date` = '" . $select_date . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $students_present = [];
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        array_push($students_present, $row['admission_no']);
                                    }
                                }
                                // get all students and seperate the present from the absent
                                // we are going to use a potrait A4 page 
                                $select  = "SELECT * FROM `student_data` WHERE `stud_class` = '" . $school_classes[$ind] . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $student_data = [];
                                $number = 0;
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                                        $adm_no = $row['adm_no'];
                                        $gender = $row['gender'];
                                        $stud_class = classNameReport($row['stud_class']);
                                        $each_stud = array($number, $student_name, $adm_no, $gender, $stud_class);
                                        array_push($student_data, $each_stud);
                                        $number++;
                                    }
                                }
                                // get the data for the students that are present and those that are absent
                                $present_data = [];
                                $absent_data = [];
                                $numbP = 1;
                                $numbA = 1;
                                for ($indexes = 0; $indexes < count($student_data); $indexes++) {
                                    if (checkPresentReport($students_present, $student_data[$indexes][2])) {
                                        $student_data[$indexes][0] = $numbP;
                                        array_push($present_data, $student_data[$indexes]);
                                        $numbP++;
                                    } else {
                                        $student_data[$indexes][0] = $numbA;
                                        array_push($absent_data, $student_data[$indexes]);
                                        $numbA++;
                                    }
                                }

                                // Column headings
                                $header = array('No', 'Student Name', 'Reg no', 'Gender', 'Class', 'Status');
                                
                                // 
                                if ($ind  == 0) {
                                    $worksheet = $spreadsheet->getActiveSheet();
                                    $worksheet->setTitle(substr(classNameReport($school_classes[$ind])." on ".date("dS M Y", strtotime($select_date)),0,31));
    
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Population");
                                    $worksheet->setCellValue("A2", "Present");
                                    $worksheet->setCellValue("A3", "Absent");
                                    $worksheet->setCellValue("B2", count($present_data) . " Student(s)");
                                    $worksheet->setCellValue("B3", count($absent_data) . " Student(s)");
                                    $worksheet->setCellValue("A4", "Total");
                                    $worksheet->setCellValue("B4", (count($present_data)+count($absent_data))." Student(s)");
                
                                    // set the header
                                    $worksheet->setCellValue("A7", "Absent Student");
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                                    }
                                    $data = $absent_data;
                                    $worksheet->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);
    
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);
    
                                    // where the present ones will appear row id will continue from
                                    $proceed_from = count($data)+11;
                                    $worksheet->setCellValue("A".$proceed_from, "Present Student");
                                    $proceed_from+=1;
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."".($proceed_from), $header[$i]);
                                    }
                                    $worksheet->getStyle("A$proceed_from:".$letters[count($header)-1]."$proceed_from")->applyFromArray($table_style);
                                    $data = $present_data;
    
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+$proceed_from), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A".($proceed_from).":".$letters[count($header)-1]."".(count($data)+$proceed_from-1))->applyFromArray($table_style_2);
                                }else{
                                    $worksheet = $spreadsheet->createSheet();
                                    $worksheet->setTitle(substr(classNameReport($school_classes[$ind])." on ".date("dS M Y", strtotime($select_date)),0,31));
    
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Population");
                                    $worksheet->setCellValue("A2", "Present");
                                    $worksheet->setCellValue("A3", "Absent");
                                    $worksheet->setCellValue("B2", count($present_data) . " Student(s)");
                                    $worksheet->setCellValue("B3", count($absent_data) . " Student(s)");
                                    $worksheet->setCellValue("A4", "Total");
                                    $worksheet->setCellValue("B4", (count($present_data)+count($absent_data))." Student(s)");
                
                                    // set the header
                                    $worksheet->setCellValue("A7", "Absent Student");
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                                    }
                                    $data = $absent_data;
                                    $worksheet->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);
    
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);
    
                                    // where the present ones will appear row id will continue from
                                    $proceed_from = count($data)+11;
                                    $worksheet->setCellValue("A".$proceed_from, "Present Student");
                                    $proceed_from+=1;
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."".($proceed_from), $header[$i]);
                                    }
                                    $worksheet->getStyle("A$proceed_from:".$letters[count($header)-1]."$proceed_from")->applyFromArray($table_style);
                                    $data = $present_data;
    
                                    // set the values for the data
                                    $proceed_from+=1;
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+$proceed_from), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A".($proceed_from).":".$letters[count($header)-1]."".(count($data)+$proceed_from-1))->applyFromArray($table_style_2);
                                }
                            }
                            // Set active sheet index to the first sheet
                            $spreadsheet->setActiveSheetIndex(0);

                            // set auto width
                            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                                // set auto width
                                for ($indexing=0; $indexing < count($header); $indexing++) {
                                    $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                                }
                            }
                            
                            // Redirect output to a client’s web browser (Xls)
                            header('Content-Type: application/vnd.ms-excel');;
                            header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                            header('Cache-Control: max-age=0');
    
                            $writer = new Xls($spreadsheet);
                            $writer->save('php://output');
                            exit;
                        } else {
                            echo "<p style='color:red;'><b>Note:</b><br>No classes have been found</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select the student course level to display the students information</p>";
                }
            } elseif ($select_student_option == "show_alumni") {
                $select = "SELECT * FROM `student_data` WHERE `stud_class` = '-1';";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $student_data = [];
                $number = 1;
                $boys = 0;
                $girls = 0;
                while ($row = $result->fetch_assoc()) {
                    $student_name = ucwords(strtolower($row['surname'] . " " . $row['first_name'] . " " . $row['second_name']));
                    $adm_no = $row['adm_no'];
                    $gender = $row['gender'];
                    if ($gender == "Male") {
                        $boys++;
                    } else {
                        $girls++;
                    }
                    $stud_class = classNameReport($row['stud_class']);
                    $dob = $row['D_O_B'];
                    $date1 = date_create($dob);
                    $date2 = date_create(date("Y-m-d"));
                    $diff = date_diff($date1, $date2);
                    $diffs = $diff->format("%y Yrs");
                    $dob = $row['D_O_B'] . " | " . $diffs . "";
                    $doa = $row['D_O_A'];
                    $parentName = ucwords(strtolower($row['parentName']));
                    $parentContacts = $row['parentContacts'];
                    $relation = $row['parent_relation'];
                    $parent_name2 = ucwords(strtolower($row['parent_name2']));
                    $parent_contact2 = $row['parent_contact2'];
                    $year_left = explode("|", $row['year_of_study']);
                    $l_ind = $year_left[count($year_left) - 1];
                    $year = explode(":", $l_ind)[0];
                    $the_class = explode(":", $l_ind)[1] == "-1" ? "Completed" : classNameReport(explode(":", $l_ind)[1]);
                    $each_stud = array($number, $student_name, $adm_no, $gender, $stud_class, $row['intake_month'],$row['intake_year'],$dob, $doa, $parentName, $parentContacts, $parent_name2, $parent_contact2);
                    array_push($student_data, $each_stud);
                    $number++;
                }
                // Column headings
                $header = array('No', 'Student Name', 'Reg no', 'Sex', 'Class', "Intake Month","Intake Year", 'D.O.B', 'Date Of Adm', '1st Parent Name', 'Contacts', '2nd Parent Name', 'Contacts');

                // Data loading
                $tittle = "Alumni List";
                $data = $student_data;

                // Create new Spreadsheet object
                $spreadsheet = new Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                    ->setLastModifiedBy($_SESSION['username'])
                    ->setTitle($tittle)
                    ->setSubject($tittle)
                    ->setDescription($_SESSION['username']." ".$tittle);

                // Add data
                $worksheet = $spreadsheet->getActiveSheet();
                $worksheet->setTitle(substr($tittle,0,31));

                // set the statistics
                $worksheet->setCellValue("A1", "Population");
                $worksheet->setCellValue("A2", "Male");
                $worksheet->setCellValue("A3", "Female");
                $worksheet->setCellValue("B2", ($boys) . " Student(s)");
                $worksheet->setCellValue("B3", ($girls) . " Student(s)");
                $worksheet->setCellValue("A4", "Total");
                $worksheet->setCellValue("B4", ($boys)+($girls)." Student(s)");
                
                // set the header
                for ($i = 0; $i < count($header); $i++) {
                    $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                }
                $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);

                // set the values for the data
                for ($index=0; $index < count($data); $index++) { 
                    for($index1=0; $index1 < count($data[$index]); $index1++){
                        $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                    }
                }
                $spreadsheet->getActiveSheet()->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);

                // Set active sheet index to the first sheet
                $spreadsheet->setActiveSheetIndex(0);

                // set auto width
                foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                    // set auto width
                    for ($indexing=0; $indexing < count($header); $indexing++) {
                        $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                    }
                }
                
                // Redirect output to a client’s web browser (Xls)
                header('Content-Type: application/vnd.ms-excel');;
                header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                header('Cache-Control: max-age=0');

                $writer = new Xls($spreadsheet);
                $writer->save('php://output');
                exit;
            }
        } elseif ($select_entity == "staff") {
            if ($staff_options == "staff_details") {
                // get all the staff details
                $select = "SELECT * FROM `user_tbl` WHERE `school_code` = '" . $_SESSION['schcode'] . "'";
                $stmt = $conn->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $staff_data = [];
                $boys = 0;
                $girls = 0;
                $number = 1;
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $fullname = ucwords(strtolower($row['fullname']));
                        $dob = $row['dob'];
                        $phone_number = $row['phone_number'];
                        $id_number = $row['nat_id'];
                        $payroll = $row['payroll'] == "enabled" ? "Enrolled" : "Not-enrolled";
                        $auth = ucwords(strtolower(authority($row['auth'])));
                        $gender = $row['gender'] == "M" ? "M" : "F";
                        if ($row['gender'] == "M") {
                            $boys++;
                        } else {
                            $girls++;
                        }
                        $tsc_no = $row['tsc_no'];
                        $status = $row['activated'] == 1 ? "Active" : "In-active";
                        $username = $row['username'];
                        $date1 = date_create($dob);
                        $date2 = date_create(date("Y-m-d"));
                        $diff = date_diff($date1, $date2);
                        $diffs = $diff->format("%y Yrs");
                        $staffs = array($number, $fullname, $diffs, $phone_number, $gender, $id_number, $payroll, $auth, $status, $username, $tsc_no);
                        array_push($staff_data, $staffs);
                        $number++;
                    }
                    // Column headings
                    $header = array('No', 'Full Name', 'Age', 'Contact', 'Sex', 'I`d no', 'Payroll', 'Role', 'Status', 'Username', 'TSC No.');
                    
                    // Data loading;
                    $data = $staff_data;
                    $tittle = "Staff Details";

                    // Create new Spreadsheet object
                    $spreadsheet = new Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                        ->setLastModifiedBy($_SESSION['username'])
                        ->setTitle($tittle)
                        ->setSubject($tittle)
                        ->setDescription($_SESSION['username']." ".$tittle);

                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr($tittle,0,31));

                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr($tittle,0,31));

                    // set the statistics
                    $worksheet->setCellValue("A1", "Population");
                    $worksheet->setCellValue("A2", "Male");
                    $worksheet->setCellValue("A3", "Female");
                    $worksheet->setCellValue("B2", ($boys) . " Staffs(s)");
                    $worksheet->setCellValue("B3", ($girls) . " Staffs(s)");
                    $worksheet->setCellValue("A4", "Total");
                    $worksheet->setCellValue("B4", ($boys)+($girls)." Staffs(s)");
                    
                    // set the header
                    for ($i = 0; $i < count($header); $i++) {
                        $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);

                    // set the values for the data
                    for ($index=0; $index < count($data); $index++) { 
                        for($index1=0; $index1 < count($data[$index]); $index1++){
                            $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                        }
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);

                    // Set active sheet index to the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    // set auto width
                    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                        // set auto width
                        for ($indexing=0; $indexing < count($header); $indexing++) {
                            $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                        }
                    }
                    // Redirect output to a client’s web browser (Xls)
                    header('Content-Type: application/vnd.ms-excel');;
                    header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                    header('Cache-Control: max-age=0');

                    $writer = new Xls($spreadsheet);
                    $writer->save('php://output');
                    exit;
                }
            } elseif ($staff_options == "logs") {
                // check if date is set
                if (strlen($select_date_staff) > 0) {
                    $select = "SELECT * FROM `logs` WHERE `date` = '" . $select_date_staff . "'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $mystaff = getStaffData($conn);
                    $staff_log = [];
                    if ($result) {
                        $number = 1;
                        $all_active = 0;
                        while ($row = $result->fetch_assoc()) {
                            $login_time = $row['login_time'];
                            $active_time = $row['active_time'];
                            $user_id = $row['user_id'];
                            $date = $row['date'];
                            $contacts = getStaffDets($mystaff, $user_id)[0];
                            $staff_address = getStaffDets($mystaff, $user_id)[1];
                            $role = authority(getStaffDets($mystaff, $user_id)[2]);
                            $fullname = ucwords(strtolower(getStaffNamedReport($mystaff, $user_id)));
                            $my_data = array($number, $fullname, $role, $login_time, $active_time, $date, $staff_address, $contacts);
                            array_push($staff_log, $my_data);
                            $all_active++;
                            $number++;
                        }
                        $header = array('No', 'Full Name', 'Role', 'Login Time', 'Last Active', 'Date', 'Address', 'Contact');
                        
                        // Data loading
                        $data = $staff_log;
                        $tittle = "Staffs Active on " . date("dS M Y", strtotime($select_date_staff));
                        
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();

                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
    
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr($tittle,0,31));
    
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr($tittle,0,31));
    
                        // set the statistics
                        $worksheet->setCellValue("A1", "Population");
                        $worksheet->setCellValue("A2", "Total Active");
                        $worksheet->setCellValue("B2", ($all_active) . " Staffs(s)");
                        
                        // set the header
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."5", $header[$i]);
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A5:".$letters[count($header)-1]."5")->applyFromArray($table_style);
    
                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+6), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A6:".$letters[count($header)-1]."".(count($data)+5))->applyFromArray($table_style_2);
    
                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }
                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>Please select date option to procced!</p>";
                }
            } elseif ($staff_options == "class_teachers") {
                $select = "SELECT * FROM `class_teacher_tbl`";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $assigned_class_teacher = [];
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($assigned_class_teacher, $row);
                    }
                }
                // get the data of the class teacher that are available 
                $mystaff = getStaffData($conn);
                // go through the class teacher list and get those that ara assigned classes and those that are not assigned classes
                $teacher_data = [];
                $number = 1;
                for ($index = 0; $index < count($mystaff); $index++) {
                    if ($mystaff[$index]['auth'] == "5") {
                        // get if the class teacher is assigned or not
                        $taecher_name = ucwords(strtolower($mystaff[$index]['fullname']));
                        $gender = $mystaff[$index]['gender'];
                        $class_assigned = "Not-Assigned";
                        $student_count = "N/A";
                        $contacts = $mystaff[$index]['phone_number'];
                        for ($ind = 0; $ind < count($assigned_class_teacher); $ind++) {
                            if ($assigned_class_teacher[$ind]['class_teacher_id'] == $mystaff[$index]['user_id']) {
                                // get the class 
                                $class_assigned = classNameReport($assigned_class_teacher[$ind]['class_assigned']);
                                $student_count = count(getStudents($assigned_class_teacher[$ind]['class_assigned'], $conn2)) . " Student(s)";
                            }
                        }
                        $class_tr = array($number, $taecher_name, $gender, $class_assigned, $student_count, $contacts);
                        $number++;
                        array_push($teacher_data, $class_tr);
                    }
                }
                if (count($teacher_data) > 0) {
                    // Column headings
                    $header = array('No', 'Fullname', 'Sex', 'Class Assigned', 'Total Students', 'Contacts');
                    
                    // class teacher
                    $tittle = "Class Teacher List";
                    
                    // Data loading
                    $data = $teacher_data;
                        
                    // Create new Spreadsheet object
                    $spreadsheet = new Spreadsheet();
                    
                    // Set document properties
                    $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                        ->setLastModifiedBy($_SESSION['username'])
                        ->setTitle($tittle)
                        ->setSubject($tittle)
                        ->setDescription($_SESSION['username']." ".$tittle);

                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr($tittle,0,31));

                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr($tittle,0,31));

                    // set the statistics
                    $worksheet->setCellValue("C1", "Class Teacher List");
                    
                    // set the header
                    for ($i = 0; $i < count($header); $i++) {
                        $worksheet->setCellValue("".$letters[$i]."3", $header[$i]);
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A3:".$letters[count($header)-1]."3")->applyFromArray($table_style);

                    // set the values for the data
                    for ($index=0; $index < count($data); $index++) { 
                        for($index1=0; $index1 < count($data[$index]); $index1++){
                            $worksheet->setCellValue("".$letters[$index1]."".($index+4), $data[$index][$index1]);
                        }
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A4:".$letters[count($header)-1]."".(count($data)+3))->applyFromArray($table_style_2);

                    // Set active sheet index to the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    // set auto width
                    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                        // set auto width
                        for ($indexing=0; $indexing < count($header); $indexing++) {
                            $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                        }
                    }
                    // Redirect output to a client’s web browser (Xls)
                    header('Content-Type: application/vnd.ms-excel');;
                    header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                    header('Cache-Control: max-age=0');

                    $writer = new Xls($spreadsheet);
                    $writer->save('php://output');
                    exit;
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br>No classteachers present!</p>";
                }
            } else {
                echo "<p style='color:red;'><b>Note:</b><br>Please select an option to procced!</p>";
            }
        }
    }elseif (isset($_POST['finance_entity']) && isset($_POST['pdf'])) {
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        $finance_entity = $_POST['finance_entity'];
        $period_selection = $_POST['period_selection'];
        // echo $period_selection;
        $from_date_finance = $_POST['from_date_finance'] ? $_POST['from_date_finance'] : date("Y-m-d");
        $to_date_finance = $_POST['to_date_finance'] ? $_POST['to_date_finance'] : date("Y-m-d");
        $specific_date_finance = $_POST['specific_date_finance'] ? $_POST['specific_date_finance'] : date("Y-m-d");
        $student_options = $_POST['student_options'];
        $student_admno_in = $_POST['student_admno_in'];
        $student_class_fin = $_POST['student_class_fin'];
        $reminder_message = $_POST['reminder_message'];
        $course_name = isset($_POST['course_name']) ? $_POST['course_name'] : null;
        $expense_category = isset($_POST['expense_category']) ? $_POST['expense_category'] : "All";

        if ($finance_entity == "fees_collection") {
            if (strlen($student_class_fin) > 0 && strlen($period_selection) > 0 && $student_options == "byClass") {
                // fees collection of specific date and specific class
                if ($student_class_fin != "all") {
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } elseif ($period_selection == "period") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction`  BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '" . date("Y-m-d") . "' ORDER BY `transaction_id` DESC";
                    }
                    // echo $select;
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $course_id = strlen($course_name) > 0 ? $course_name : null;
                    $student_data = getStudents($student_class_fin, $conn2, $course_id);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $reversed = 0;
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }
                            // $amount_paid = ($amount_paid);
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $row['stud_admin'], $mode_of_pay, $pay_for, $date, $payBy);
                            if ($amount_paid != 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    // create the pdf file
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->setHeaderPos(200);
                    // Column headings
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Reg-No.', 'Mode', 'Votehead', 'Date', 'Served By');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');

                    // get the course
                    $all_courses = [];
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                    $statements = $conn2->prepare($select);
                    $statements->execute();
                    $res = $statements->get_result();
                    if($res){
                        if($rows = $res->fetch_assoc()){
                            $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                        }
                    }

                    // get the department
                    $all_department = [];
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                    $statements = $conn2->prepare($select);
                    $statements->execute();
                    $res = $statements->get_result();
                    if($res){
                        if($rows = $res->fetch_assoc()){
                            $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                        }
                    }

                    // title
                    $tittle = "No records to display";
                    $courses_name = "N/A";
                    for($index =0; $index < count($all_courses); $index++){
                        if($all_courses[$index]->id == $course_name){
                            $cs = $all_courses[$index]->course_name;
                            $courses_name = $cs;
                            break;
                        }
                    }
                    $course_names = strlen($course_name) > 0 ? " in ".$courses_name : null;


                    if ($period_selection == "specific_date") {
                        $tittle = classNameReport($student_class_fin) . " ".$course_names." - Fees list on " . date("dS M Y", strtotime($specific_date_finance));
                    } elseif ($period_selection == "period") {
                        $tittle = classNameReport($student_class_fin) . " ".$course_names." - Fees list from " . date("dS M Y", strtotime($from_date_finance)) . " to " . date("dS M Y", strtotime($to_date_finance));
                    } else {
                        $tittle = "No records to display";
                    }
                    $data = $finance_list;
                    $pdf->set_document_title($tittle);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->SetMargins(5, 5);
                    $pdf->AddPage();
                    $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'I', 9);
                    $pdf->Cell(30, 5, "Cash :", 0, 0, 'L', false);
                    $pdf->Cell(30, 5, "Kes " . number_format($cash), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(30, 5, "M-Pesa :", 0, 0, 'L', false);
                    $pdf->Cell(30, 5, "Kes " . number_format($mpesa), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(30, 5, "Bank :", 0, 0, 'L', false);
                    $pdf->Cell(30, 5, "Kes " . number_format($bank), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(30, 5, "Reversed :", 0, 0, 'L', false);
                    $pdf->Cell(30, 5, "Kes " . number_format($reversed), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(30, 5, "Total Recieved:", 'T', 0, 'L', false);
                    $pdf->Cell(30, 5, "Kes " . number_format($cash + $mpesa + $bank + $reversed), 'T', 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'IU', 13);
                    $pdf->Ln();
                    $pdf->Cell(200, 8, "Fees Collection Table", 0, 0, 'C', false);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'B', 8);
                    $width = array(5, 20, 17, 22, 28, 13, 10, 35, 33, 18);
                    $skip = false;
                    $pdf->financeTable($header, $data, $width, $skip);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                } else {
                    // this brings all the transactions for the whole institution
                    $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } elseif ($period_selection == "period") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction`  BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '" . date("Y-m-d") . "' ORDER BY `transaction_id` DESC";
                    }
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $student_data = getStudents("-1", $conn2);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $reversed = 0;
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                                // echo $cash."<br>";
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }
                            // $amount_paid = ($amount_paid);
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $row['stud_admin'], $mode_of_pay, $pay_for, $date, $payBy);
                            // array_push($finance_list,$stud_data);
                            if ($amount_paid != 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    // create the pdf file
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->setHeaderPos(200);
                    // Column headings
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Reg-No.' ,'Mode', 'Votehead', 'Date', 'Served By');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    $tittle = "Fees recieved on " . date("dS M Y", strtotime($specific_date_finance));
                    $tittle = "No records to display";
                    if ($period_selection == "specific_date") {
                        $tittle = "Fees recieved on " . date("dS M Y", strtotime($specific_date_finance));
                    } elseif ($period_selection == "period") {
                        $tittle = "Fees recieved from (" . date("D dS M Y", strtotime($from_date_finance)) . ") to (" . date("D dS M Y", strtotime($to_date_finance)) . ")";
                    } else {
                        $tittle = "No records to display";
                    }
                    $data = $finance_list;
                    $pdf->set_document_title($tittle);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->SetMargins(5, 5);
                    $pdf->AddPage();
                    $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'I', 9);
                    $pdf->Cell(40, 5, "Cash :", 0, 0, 'L', false);
                    $pdf->Cell(40, 5, "Kes " . number_format($cash), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(40, 5, "M-Pesa :", 0, 0, 'L', false);
                    $pdf->Cell(40, 5, "Kes " . number_format($mpesa), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(40, 5, "Bank :", 0, 0, 'L', false);
                    $pdf->Cell(40, 5, "Kes " . number_format($bank), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(40, 5, "Reversed :", 0, 0, 'L', false);
                    $pdf->Cell(40, 5, "Kes " . number_format($reversed), 0, 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell(40, 5, "Total Recieved:", 'T', 0, 'L', false);
                    $pdf->Cell(40, 5, "Kes " . number_format($cash + $mpesa + $bank + $reversed), 'T', 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'IU', 13);
                    $pdf->Ln();
                    $pdf->Cell(200, 8, "Fees Collection Table", 0, 0, 'C', false);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'B', 8);
                    $width = array(5, 20, 17, 22, 28, 13, 10, 35, 33, 18);
                    $skip = false;
                    $pdf->financeTable($header, $data, $width, $skip);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                }
            } elseif ($student_options == "bySpecific" && strlen($period_selection) > 0) {
                // if the student id is set proceed
                if (strlen($student_admno_in) > 0) {
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `stud_admin` = '$student_admno_in' AND `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `stud_admin` = '$student_admno_in' AND `date_of_transaction` BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    }
                    // echo $select;
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $student_data = getStudents("-1", $conn2);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $reversed = 0;
                    $student_name = "Null";
                    $classNameReport = "Null";
                    $capture_balance = "Kes 0";
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        if ($number == 1) {
                            $capture_balance = "Kes " . number_format($row['balance']);
                        }
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $student_name = $fullname;
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }
                            // $amount_paid = ($amount_paid);
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $mode_of_pay, $pay_for, $date, $payBy);
                            // array_push($finance_list,$stud_data);
                            if ($amount_paid != 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    // create the pdf file
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->setHeaderPos(200);
                    // Column headings
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    $title = "No data to display!";
                    if ($period_selection == "specific_date") {
                        $tittle = $student_name . " Fees list on " . date("dS M Y", strtotime($specific_date_finance));
                    } else {
                        $tittle = $student_name . " Fees list from " . date("dS M Y", strtotime($from_date_finance)) . " to " . date("dS M Y", strtotime($to_date_finance));
                    }
                    $data = $finance_list;
                    if (count($data) > 0) {
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        // $pdf->SetMargins(5,5);
                        $pdf->AddPage();
                        $pdf->Cell(40, 10, "Balance", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 8);
                        $pdf->Cell(40, 5, "Last Balance :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, $capture_balance, 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', 'U', 8);
                        $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 8);
                        $pdf->Cell(40, 5, "Cash :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($cash), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "M-Pesa :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($mpesa), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "Bank :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($bank), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "Reversed :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($reversed), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "Total Recieved:", 'T', 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($cash + $mpesa + $bank + $reversed), 'T', 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'IU', 13);
                        $pdf->Ln();
                        $pdf->Cell(200, 8, "Fees Collection Table", 0, 0, 'C', false);
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', 'B', 8);
                        $width = array(8, 22, 22, 20, 28, 13, 35, 33, 18);
                        $pdf->financeTable($header, $data, $width);
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    } else {
                        echo "<p style='color:red;'>No records to display</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Please provide the student admission number to proceed!</p>";
                }
            } else {
                echo "<p style='color:red;'>Select Class or Date option to proceed!</p>";
            }
        } elseif ($finance_entity == "class_balances") {
            if (strlen($student_class_fin) > 0 && $student_options == "byClass") {
                include_once("../ajax/finance/financial.php");
                // display the class balance
                if ($student_class_fin != "all") {
                    // get per class
                    $course_id = strlen($course_name) > 0 ? $course_name : null;
                    $student_data = getStudents($student_class_fin, $conn2, $course_id);
                    $number = 1;
                    $stud_data = [];
                    $total_fees = 0;
                    $fees_repo_paid = 0;
                    $total_balances = 0;
                    $term = getTermV2($conn2);
                    // echo $term;
                    for ($index = 0; $index < count($student_data); $index++) {
                        // get the student data data
                        $Fullname = ucwords(strtolower($student_data[$index]['first_name'] . " " . $student_data[$index]['second_name']));
                        $gender = $student_data[$index]['gender'] == "Male" ? "M" : "F";
                        $classes = classNameReport($student_class_fin);
                        
                        // get fees to pay by the student
                        $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                        $fees_paid = $feespaidbystud;
                        $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                        
                        // echo $balanced."<br>";
                        $balance = ($balanced * 1);
                        $total_fees += $balanced + $feespaidbystud;
                        $fees_repo_paid += $feespaidbystud;
                        $total_balances += $balanced;

                        // LAST ACADEMIC YEAR BALANCE
                        $last_acad_yr = lastACADyrBal($student_data[$index]['adm_no'], $conn2);
                        $acad_balance = ($last_acad_yr);
                        $border = isBoarding($student_data[$index]['adm_no'], $conn2) ? (getBoardingFees($conn2, $student_class_fin, "null", $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                        $transport = isTransport($conn2, $student_data[$index]['adm_no']) ? (transportBalanceSinceAdmission($conn2, $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                        $data = array($number, $Fullname, $student_data[$index]['adm_no'], $classes, $gender, $fees_paid, $balance, $acad_balance);
                        array_push($stud_data, $data);
                        $number++;
                    }
                    if (count($stud_data) > 0) {
                        // get the course
                        $all_courses = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }

                        // get the department
                        $all_department = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }

                        // title
                        $courses_name = "N/A";
                        for($index =0; $index < count($all_courses); $index++){
                            if($all_courses[$index]->id == $course_name){
                                $cs = $all_courses[$index]->course_name;
                                $courses_name = $cs;
                                break;
                            }
                        }
                        $course_names = strlen($course_name) > 0 ? " in ".$courses_name : null;

                        // display the data 
                        // create the pdf file
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        // Column headings
                        $header = array('No', 'Fullname', 'Reg No.', 'Class', 'Sex', 'Fees paid', 'Balance', 'Last Yrs Bal');
                        // Data loading
                        // $data = $pdf->LoadData('countries.txt');
                        $tittle = "Fees list for - " . classNameReport($student_class_fin)." ".$course_names;
                        $data = $stud_data;
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        $pdf->SetFont('Times', 'BU', 10);
                        $pdf->Cell(40, 5, "Note :", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 10);
                        $pdf->Cell(200, 5, "- The balances are " . "as of " . ucwords(strtolower($term)) . " inclusive of the previous academic years balance.", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(200, 5, "- When you sum the \"Balance\" and the \"Fees paid\" you get the total amount the student is supposed to pay.", 0, 0, 'L', false);
                        $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'I', 10);
                        $pdf->Cell(40, 5, "Fees Paid :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($fees_repo_paid), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "Balance :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($total_balances), 0, 0, 'L', false);
                        $pdf->Ln();
                        $pdf->Cell(40, 5, "Fees To be paid :", 0, 0, 'L', false);
                        $pdf->Cell(40, 5, "Kes " . number_format($total_fees), "T", 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Times', 'IU', 12);
                        $pdf->Ln();
                        $pdf->Cell(200, 8, "Fees Balances for - " . classNameReport($student_class_fin) . "".$course_names. " - as of " . ucwords(strtolower($term)), 0, 0, 'C', false);
                        $pdf->Ln();
                        $pdf->SetFont('Helvetica', 'B', 8);
                        $width = array(8, 33, 18, 15, 8, 38, 38, 38);
                        $pdf->balancesTable($header, $data, $width);
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    } else {
                        echo "<p style='color:red;'><b>Note:</b><br>No students present to display.</p>";
                    }
                } elseif ($student_class_fin == "all") {
                    $school_classes = getSchoolCLass($conn2);
                    if (count($school_classes) > 0) {
                        $term = getTermV2($conn2);
                        // display the data 
                        // create the pdf file
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        $tittle = "Fees list for whole school";
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);

                        // get the course
                        $all_courses = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }
                        // get the department
                        $all_department = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'departments'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_department = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }
                        // title
                        $courses_name = "N/A";
                        for($ind =0; $ind < count($all_courses); $ind++){
                            if($all_courses[$ind]->id == $course_name){
                                $cs = $all_courses[$ind]->course_name;
                                $courses_name = $cs;
                                break;
                            }
                        }
                        $course_names = strlen($course_name) > 0 ? " in ".$courses_name : null;

                        // Column headings
                        $header = array('No', 'Fullname', 'Reg No.', 'Class', 'Sex', 'Fees paid', 'Balance', 'Last Yrs Bal');
                        for ($ind = 0; $ind < count($school_classes); $ind++) {
                            // get per class
                            $student_class_fin = $school_classes[$ind];
                            $student_data = getStudents($student_class_fin, $conn2);
                            $number = 1;
                            $stud_data = [];
                            $total_fees = 0;
                            $fees_repo_paid = 0;
                            $total_balances = 0;
                            for ($index = 0; $index < count($student_data); $index++) {
                                // echo $number;
                                // get the student data data
                                $Fullname = ucwords(strtolower($student_data[$index]['first_name'] . " " . $student_data[$index]['second_name']));
                                $gender = $student_data[$index]['gender'] == "Male" ? "M" : "F";
                                // echo $gender;
                                $classes = classNameReport($student_class_fin);
                                // get fees to pay by the student
                                $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                                $fees_paid = ($feespaidbystud);
                                $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                                $balance = ($balanced * 1);
                                $total_fees += $balanced + $feespaidbystud;
                                $fees_repo_paid += $feespaidbystud;
                                $total_balances += $balanced;
                                // LAST ACADEMIC YEAR BALANCE
                                $last_acad_yr = lastACADyrBal($student_data[$index]['adm_no'], $conn2);
                                $acad_balance = ($last_acad_yr);
                                $border = isBoarding($student_data[$index]['adm_no'], $conn2) ? (getBoardingFees($conn2, $student_class_fin) * 1) : "Not-enrolled";
                                $transport = isTransport($conn2, $student_data[$index]['adm_no']) ? (transportBalanceSinceAdmission($conn2, $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                                $data = array($number, $Fullname, $student_data[$index]['adm_no'], $classes, $gender, $fees_paid, $balance, $acad_balance);
                                array_push($stud_data, $data);
                                $number++;
                            }
                            if (count($stud_data) > 0) {

                                // $data = $pdf->LoadData('countries.txt');
                                $data = $stud_data;
                                $pdf->AddPage();
                                $pdf->SetFont('Times', 'BU', 10);
                                $pdf->Cell(40, 5, "Note :", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Times', 'I', 10);
                                $pdf->Cell(200, 5, "- The balances are " . "as of " . ucwords(strtolower($term)) . " inclusive of the previous academic years balance.", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Cell(200, 5, "- When you sum the \"Balance\" and the \"Fees paid\" you get the total amount the student is supposed to pay.", 0, 0, 'L', false);
                                $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Times', 'I', 10);
                                $pdf->Cell(40, 5, "Fees Paid :", 0, 0, 'L', false);
                                $pdf->Cell(40, 5, "Kes " . number_format($fees_repo_paid), 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Cell(40, 5, "Balance :", 0, 0, 'L', false);
                                $pdf->Cell(40, 5, "Kes " . number_format($total_balances), 0, 0, 'L', false);
                                $pdf->Ln();
                                $pdf->Cell(40, 5, "Fees To be paid :", 0, 0, 'L', false);
                                $pdf->Cell(40, 5, "Kes " . number_format($total_fees), "T", 0, 'L', false);
                                $pdf->Ln();
                                $pdf->SetFont('Times', 'IU', 12);
                                $pdf->Ln();
                                $pdf->Cell(200, 8, "Fees Balances for " . classNameReport($student_class_fin) . "".$course_names."" . " as of " . ucwords(strtolower($term)), 0, 0, 'C', false);
                                $pdf->Ln();
                                $pdf->SetFont('Helvetica', 'B', 8);
                                $width = array(8, 33, 18, 15, 8, 38, 38, 38);
                                $pdf->balancesTable($header, $data, $width);
                            }
                        }
                        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                    }
                }
            } else {
                echo "<p><b>Note:</b><br>Producing fee balance for a specific student is not available at the moment.</p>";
            }
        } elseif ($finance_entity == "fees_reminders") {
            // echo "My name is hillary!";
            include("fees_reminder.php");
            include_once("../ajax/finance/financial.php");
            if ($student_options == "byClass") {
                if ($student_class_fin != "all") {
                    if (strlen($reminder_message) > 0) {
                        $course_id = strlen($course_name) > 0 ? $course_name : null;
                        $students = getStudents($student_class_fin, $conn2, $course_id);
                        if (count($students) > 0) {
                            $counter = 1;
                            $pdf2 = new PDF2('P', 'mm', 'A4');
                            // Column headings
                            // Data loading
                            // $data = $pdf2->LoadData('countries.txt');
                            $tittle = "Fees Reminder";
                            // $data = $student_data;
                            $pdf2->set_document_title($tittle);
                            $pdf2->setHeaderPos(200);
                            $pdf2->setSchoolLogo("../" . schoolLogo($conn));
                            $pdf2->set_school_name($_SESSION['schname']);
                            $pdf2->set_school_po($_SESSION['po_boxs']);
                            $pdf2->set_school_box_code($_SESSION['box_codes']);
                            $pdf2->set_school_contact($_SESSION['school_contact']);
                            $pdf2->AddPage();
                            $old_x_pos = 6;
                            for ($indexes = 0; $indexes < count($students); $indexes++) {
                                $reminder_msg = reminderMsg($students[$indexes], $reminder_message, $conn2);
                                // EDIT THE INFORMATION TO DISPLAY FOR THE REMINDER
                                // $pdf2->Image(dirname(__FILE__) . $pdf2->school_logo, 6, 6, 20);
                                // Arial  15
                                // $pdf2->SetFont('Arial', '', 15);
                                // Title
                                $pdf2->Cell($pdf2->school_header_position, 5, "", 0, 0, 'C');
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 15);
                                $pdf2->Cell($pdf2->school_header_position, 5, strtoupper(trim($pdf2->school_name)), 0, 0, 'C');
                                // Arial  15
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell($pdf2->school_header_position, 5, "P.O Box : " . $pdf2->school_po . "-" . $pdf2->school_BOX_CODE, 0, 0, 'C');
                                $pdf2->Ln();
                                $pdf2->Cell($pdf2->school_header_position, 5, "Contact us: " . $pdf2->school_contact, 0, 0, 'C');
                                // Line break
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', 'U', 9);
                                $pdf2->Cell($pdf2->school_header_position, 5, $pdf2->school_document_title . "", 0, 0, 'C');
                                $pdf2->SetTitle($pdf2->school_document_title);
                                $pdf2->SetFont('', '');
                                $pdf2->SetAuthor($_SESSION['username']);
                                // Line break
                                $pdf2->Ln(5);
                                $pdf2->SetFont('Arial', '', 10);
                                $pdf2->Cell(30, 5, "Student Name : ", 0, 0, 'L', 0);
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, ucwords(strtolower($students[$indexes]['first_name'] . " " . $students[$indexes]['second_name'])), 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 10);
                                $pdf2->Cell(30, 5, "Student Reg No : ", 0, 0, 'L', 0);
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, $students[$indexes]['adm_no'], 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 10);
                                $pdf2->Cell(30, 5, "Student Level : ", 0, 0, 'L', 0);
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, classNameReport($students[$indexes]['stud_class']), 0, 0, 'L', 0);
                                $pdf2->Ln(10);
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, "Dear Parent/Guardian,", 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->Write(5, $reminder_msg);
                                // ouput the pdf generated
                                $pdf2->Ln(10);
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, "Yours Faithfull", 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, "Headteacher,", 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->SetFont('Arial', '', 9);
                                $pdf2->Cell(30, 5, trim(ucwords(strtolower($pdf2->school_name))), 0, 0, 'L', 0);
                                $pdf2->Ln();
                                $pdf2->SetDrawColor(194, 200, 200);
                                $pdf2->SetLineWidth(.2);
                                $pdf2->Cell(180, 1, "", "B", 0, 0);
                                $pdf2->Ln();
                                $get_height = round($pdf2->GetY());
                                // echo $get_height."<br>";
                                $old_x_pos+=5;
                                $pdf2->Image($pdf2->school_logo,6,$old_x_pos,15);
                                $pdf2->Image($pdf2->arm_of_gov,190,$old_x_pos,15);
                                $old_x_pos = $get_height;
                                $pdf2->ln();
                                if ($counter == 3) {
                                    $counter = 0;
                                    $pdf2->AddPage();
                                    // echo $get_height."Add page<br>";
                                    $old_x_pos = 6;
                                }
                                $counter++;
                            }
                            $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                        } else {
                            echo "<p style='color:red;'><b>Nore:</b><br> - No students in " . classNameReport($student_class_fin) . "!</p>";
                        }
                    } else {
                        echo "<p style='color:red;'><b>Nore:</b><br> - Message is not set!</p>";
                    }
                    // classes
                } else {
                    // get the whole school students 
                    $school_classes = getSchoolCLass($conn2);
                    if (count($school_classes) > 0) {
                        $pdf2 = new PDF2('P', 'mm', 'A4');
                        // Column headings
                        // Data loading
                        // $data = $pdf2->LoadData('countries.txt');
                        $tittle = "Fees Reminder";
                        // $data = $student_data;
                        $pdf2->set_document_title($tittle);
                        $pdf2->setHeaderPos(200);
                        $pdf2->setSchoolLogo("../" . schoolLogo($conn));
                        $pdf2->set_school_name($_SESSION['schname']);
                        $pdf2->set_school_po($_SESSION['po_boxs']);
                        $pdf2->set_school_box_code($_SESSION['box_codes']);
                        $pdf2->set_school_contact($_SESSION['school_contact']);
                        $pdf2->AddPage();
                        $counter = 1;
                        for ($indexed = 0; $indexed < count($school_classes); $indexed++) {
                            $students = getStudents($school_classes[$indexed], $conn2);
                            if (count($students) > 0) {
                                $old_x_pos = 6;
                                for ($indexes = 0; $indexes < count($students); $indexes++) {
                                    $reminder_msg = reminderMsg($students[$indexes], $reminder_message, $conn2);
                                    // EDIT THE INFORMATION TO DISPLAY FOR THE REMINDER
                                    // $pdf2->Image(dirname(__FILE__) . $pdf2->school_logo, 6, 6, 20);
                                    // Arial  15
                                    // $pdf2->SetFont('Arial', '', 15);
                                    // Title
                                    $pdf2->Cell($pdf2->school_header_position, 5, "", 0, 0, 'C');
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 15);
                                    $pdf2->Cell($pdf2->school_header_position, 5, strtoupper(trim($pdf2->school_name)), 0, 0, 'C');
                                    // Arial  15
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell($pdf2->school_header_position, 5, "P.O Box : " . $pdf2->school_po . "-" . $pdf2->school_BOX_CODE, 0, 0, 'C');
                                    $pdf2->Ln();
                                    $pdf2->Cell($pdf2->school_header_position, 5, "Contact us: " . $pdf2->school_contact, 0, 0, 'C');
                                    // Line break
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', 'U', 9);
                                    $pdf2->Cell($pdf2->school_header_position, 5, $pdf2->school_document_title . "", 0, 0, 'C');
                                    $pdf2->SetTitle($pdf2->school_document_title);
                                    $pdf2->SetFont('', '');
                                    $pdf2->SetAuthor($_SESSION['username']);
                                    // Line break
                                    $pdf2->Ln(5);
                                    $pdf2->SetFont('Arial', '', 10);
                                    $pdf2->Cell(30, 5, "Student Name : ", 0, 0, 'L', 0);
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, ucwords(strtolower($students[$indexes]['first_name'] . " " . $students[$indexes]['second_name'])), 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 10);
                                    $pdf2->Cell(30, 5, "Student Reg No : ", 0, 0, 'L', 0);
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, $students[$indexes]['adm_no'], 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 10);
                                    $pdf2->Cell(30, 5, "Student Level : ", 0, 0, 'L', 0);
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, classNameReport($students[$indexes]['stud_class']), 0, 0, 'L', 0);
                                    $pdf2->Ln(10);
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, "Dear Parent/Guardian,", 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->Write(5, $reminder_msg);
                                    // ouput the pdf generated
                                    $pdf2->Ln(10);
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, "Yours Faithfull", 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, "Headteacher,", 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 9);
                                    $pdf2->Cell(30, 5, trim(strtoupper($pdf2->school_name)), 0, 0, 'L', 0);
                                    $pdf2->Ln();
                                    $pdf2->SetDrawColor(194, 200, 200);
                                    $pdf2->SetLineWidth(.2);
                                    $pdf2->Cell(180, 1, "", "B", 0, 0);
                                    $get_height = round($pdf2->GetY());
                                    $old_x_pos+=5;
                                    $pdf2->Image($pdf2->school_logo,6,$old_x_pos,15);
                                    $pdf2->Image($pdf2->arm_of_gov,190,$old_x_pos,15);
                                    $old_x_pos = $get_height;
                                    $pdf2->Ln();
                                    if ($counter == 3) {
                                        $counter = 0;
                                        $pdf2->AddPage();
                                        $old_x_pos = 6;
                                    }
                                    $counter++;
                                }
                            }
                        }
                        $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                    } else {
                        echo "<p style='color:red;'><b>Nore:</b><br> - Student Classes not available!</p>";
                    }
                }
            } elseif ($student_options == "bySpecific") {
                // get the fees detail of specific student
                if (strlen($student_admno_in) > 0) {
                    $student_data = getStudDetail($conn2, $student_admno_in);
                    if (count($student_data) > 0) {
                        // display the student details
                        $pdf2 = new PDF2('P', 'mm', 'A4');
                        // Column headings
                        // Data loading
                        // $data = $pdf2->LoadData('countries.txt');
                        $tittle = "Fees Reminder";
                        // $data = $student_data;
                        $pdf2->set_document_title($tittle);
                        $pdf2->setHeaderPos(200);
                        $pdf2->setSchoolLogo("../" . schoolLogo($conn));
                        $pdf2->set_school_name($_SESSION['schname']);
                        $pdf2->set_school_po($_SESSION['po_boxs']);
                        $pdf2->set_school_box_code($_SESSION['box_codes']);
                        $pdf2->set_school_contact($_SESSION['school_contact']);
                        $pdf2->AddPage();
                        $reminder_msg = reminderMsg($student_data, $reminder_message, $conn2);
                        // EDIT THE INFORMATION TO DISPLAY FOR THE REMINDER
                        // $pdf2->Image(dirname(__FILE__) . $pdf2->school_logo, 6, 6, 20);
                        // Arial  15
                        // $pdf2->SetFont('Arial', '', 15);
                        // Title
                        $pdf2->Cell($pdf2->school_header_position, 5, "", 0, 0, 'C');
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 15);
                        $pdf2->Cell($pdf2->school_header_position, 5, strtoupper(trim($pdf2->school_name)), 0, 0, 'C');
                        // Arial  15
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell($pdf2->school_header_position, 5, "P.O Box : " . $pdf2->school_po . "-" . $pdf2->school_BOX_CODE, 0, 0, 'C');
                        $pdf2->Ln();
                        $pdf2->Cell($pdf2->school_header_position, 5, "Contact us: " . $pdf2->school_contact, 0, 0, 'C');
                        // Line break
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', 'U', 9);
                        $pdf2->Cell($pdf2->school_header_position, 5, $pdf2->school_document_title . "", 0, 0, 'C');
                        $pdf2->SetTitle($pdf2->school_document_title);
                        $pdf2->SetFont('', '');
                        $pdf2->SetAuthor($_SESSION['username']);
                        // Line break
                        $pdf2->Ln(5);
                        $pdf2->SetFont('Arial', '', 10);
                        $pdf2->Cell(30, 5, "Student Name : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, ucwords(strtolower($student_data['first_name'] . " " . $student_data['second_name'])), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 10);
                        $pdf2->Cell(30, 5, "Student Reg No : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, $student_data['adm_no'], 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 10);
                        $pdf2->Cell(30, 5, "Student Level : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, classNameReport($student_data['stud_class']), 0, 0, 'L', 0);
                        $pdf2->Ln(10);
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, "Dear Parent/Guardian,", 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->Write(5, $reminder_msg);
                        // ouput the pdf generated
                        $pdf2->Ln(10);
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, "Yours Faithfull", 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, "Headteacher,", 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Arial', '', 9);
                        $pdf2->Cell(30, 5, trim(strtoupper($pdf2->school_name)), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetDrawColor(194, 200, 200);
                        $pdf2->SetLineWidth(.2);
                        $pdf2->Cell(180, 1, "", "B", 0, 0);
                        $get_height = round($pdf2->GetY());
                        $old_x_pos=6;
                        $pdf2->Image($pdf2->school_logo,6,$old_x_pos,15);
                        $pdf2->Image($pdf2->arm_of_gov,190,$old_x_pos,15);
                        $old_x_pos = $get_height;
                        $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                    } else {
                        echo "<p style='color:red;'><b>Nore:</b><br> - Invalid Admission number!</p>";
                    }
                } else {
                    echo "<p style='color:red;'><b>Nore:</b><br> - Please provide the student admission number to proceed!</p>";
                }
            }
        } elseif ($finance_entity == "fees_structure") {
            include("fees_reminder.php");
            // continue and get if the class has been selected for the fees structure
            if ($student_options == "byClass") {
                // get the class and pull the fees structure
                if(strlen($course_name) == 0){
                    // echo "<p style='color:red'>Course name is not selected!</p>";
                    // return 0;
                }
                if (strlen($student_class_fin) > 0) {
                    // get what class it is
                    if ($student_class_fin != "all") {
                        $select = strlen($course_name) != 0 ? "SELECT * FROM fees_structure WHERE `classes` = ? AND `course` = '$course_name'" : "SELECT * FROM fees_structure WHERE `classes` = ?";
                        $daros = "" . $student_class_fin . "";
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("s", $daros);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $fees_data = [];
                        if ($result) {
                            $number = 1;
                            while ($row = $result->fetch_assoc()) {
                                $expenses = $row['expenses'];
                                $roles = $row['roles'];
                                $TERM_1 = $row['TERM_1'];
                                $TERM_2 = $row['TERM_2'];
                                $TERM_3 = $row['TERM_3'];
                                $classes = $row['classes'];
                                $activated = $row['activated'];
                                array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                                $number++;
                            }
                        }
                        // go ahead and display the fee structure but with its own header first
                        // display the student details
                        $pdf2 = new PDF2('P', 'mm', 'A4');
                        // Column headings
                        // Data loading
                        // $data = $pdf2->LoadData('countries.txt');

                        // get the course
                        $all_courses = [];
                        $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                        $statements = $conn2->prepare($select);
                        $statements->execute();
                        $res = $statements->get_result();
                        if($res){
                            if($rows = $res->fetch_assoc()){
                                $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                            }
                        }
                        // title
                        $courses_name = "N/A";
                        for($ind =0; $ind < count($all_courses); $ind++){
                            if($all_courses[$ind]->id == $course_name){
                                $cs = $all_courses[$ind]->course_name;
                                $courses_name = $cs;
                                break;
                            }
                        }
                        $course_names = strlen($course_name) > 0 ? " in ".$courses_name : null;

                        // set title
                        $tittle = "Fees Structure for - " . classNameReport($student_class_fin).$course_names;
                        $data = $fees_data;
                        $pdf2->set_document_title($tittle);
                        $pdf2->SetTopMargin(1);
                        $pdf2->setHeaderPos(190);
                        $pdf2->setSchoolLogo("../" . schoolLogo($conn));
                        $pdf2->set_school_name($_SESSION['schname']);
                        $pdf2->set_school_po($_SESSION['po_boxs']);
                        $pdf2->set_school_box_code($_SESSION['box_codes']);
                        $pdf2->set_school_contact($_SESSION['school_contact']);
                        $pdf2->AddPage();
                        // get the number of cuts to be present in the page
                        $cuts = 3;
                        if (count($data) > 0 && count($data) < 4) {
                            $cuts = 4;
                        } elseif (count($data) >= 4 && count($data) < 9) {
                            $cuts = 3;
                        } else {
                            $cuts = 2;
                        }
                        $old_x_pos=6;
                        for ($ind = 0; $ind < $cuts; $ind++) {
                            $pdf2->Cell($pdf2->school_header_position, 5, "", 0, 0, 'C');
                            $pdf2->Ln();
                            $pdf2->SetFont('Arial', '', 12);
                            $pdf2->Cell($pdf2->school_header_position, 5, strtoupper(trim($pdf2->school_name)), 0, 0, 'C');
                            // Arial  15
                            $pdf2->Ln();
                            $pdf2->SetFont('Arial', '', 8);
                            $pdf2->Cell($pdf2->school_header_position, 4, "P.O Box : " . $pdf2->school_po . "-" . $pdf2->school_BOX_CODE, 0, 0, 'C');
                            $pdf2->Ln();
                            $pdf2->Cell($pdf2->school_header_position, 4, "Contact us: " . $pdf2->school_contact, 0, 0, 'C');
                            // Line break
                            $pdf2->Ln(10);
                            $pdf2->SetFont('Arial', 'U', 10);
                            $pdf2->Cell($pdf2->school_header_position, 5, $pdf2->school_document_title . "", 0, 0, 'C');
                            $pdf2->SetTitle($pdf2->school_document_title);
                            $pdf2->SetFont('', '');
                            $pdf2->SetAuthor($_SESSION['username']);
                            $pdf2->Ln(8);
                            $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                            $width = array(8, 35, 35, 35, 35, 35);
                            $pdf2->feesStructure($header, $data, $width);
                            $pdf2->Ln();
                            $get_height = round($pdf2->GetY());
                            $pdf2->Image($pdf2->school_logo,6,$old_x_pos,15);
                            $pdf2->Image($pdf2->arm_of_gov,190,$old_x_pos,15);
                            $old_x_pos = $get_height+5;
                            if (($ind + 1) % $cuts != 0) {
                                $pdf2->SetLineWidth(0.1);
                                $pdf2->SetDash(1, 1);
                                $pdf2->Cell(195, 0, "", 'B');
                                $pdf2->SetDash();
                            }
                        }
                        $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                    } else {
                        // display for all classes
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            // display the student details
                            $pdf2 = new PDF2('P', 'mm', 'A4');
                            // Column headings
                            // Data loading
                            // $data = $pdf2->LoadData('countries.txt');
                            $pdf2->setHeaderPos(190);
                            $pdf2->setSchoolLogo("../" . schoolLogo($conn));
                            $pdf2->set_school_name($_SESSION['schname']);
                            $pdf2->set_school_po($_SESSION['po_boxs']);
                            $pdf2->set_school_box_code($_SESSION['box_codes']);
                            $pdf2->set_school_contact($_SESSION['school_contact']);
                            $pdf2->AddPage();
                            // get the course
                            $all_courses = [];
                            $select = "SELECT * FROM `settings` WHERE `sett` = 'courses'";
                            $statements = $conn2->prepare($select);
                            $statements->execute();
                            $res = $statements->get_result();
                            if($res){
                                if($rows = $res->fetch_assoc()){
                                    $all_courses = isJson_report($rows['valued']) ? json_decode($rows['valued']) : [];
                                }
                            }
                            $old_x_pos = 6;
                            for ($in = 0; $in < count($school_classes); $in++) {
                                // title
                                $courses_name = "N/A";
                                for($ind =0; $ind < count($all_courses); $ind++){
                                    if($all_courses[$ind]->id == $course_name){
                                        $cs = $all_courses[$ind]->course_name;
                                        $courses_name = $cs;
                                        break;
                                    }
                                }
                                $course_names = strlen($course_name) > 0 ? " in ".$courses_name : null;

                                $my_class = $school_classes[$in];
                                $tittle = "Fees Structure " . classNameReport($my_class).$course_names;
                                $pdf2->set_document_title($tittle);
                                $select = "SELECT `expenses`,`roles` ,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`activated`,`ids` FROM fees_structure WHERE `classes` = ?";
                                $daros = "" . $my_class . "";
                                $stmt = $conn2->prepare($select);
                                $stmt->bind_param("s", $daros);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $fees_data = [];
                                if ($result) {
                                    $number = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $expenses = $row['expenses'];
                                        $roles = $row['roles'];
                                        $TERM_1 = $row['TERM_1'];
                                        $TERM_2 = $row['TERM_2'];
                                        $TERM_3 = $row['TERM_3'];
                                        $classes = $row['classes'];
                                        $activated = $row['activated'];
                                        array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                                        $number++;
                                    }
                                }
                                $data = $fees_data;
                                // get the number of cuts to be present in the page
                                $cuts = 3;
                                if (count($data) > 0 && count($data) < 4) {
                                    $cuts = 4;
                                } elseif (count($data) >= 4 && count($data) < 9) {
                                    $cuts = 3;
                                } else {
                                    $cuts = 2;
                                }
                                for ($ind = 0; $ind < $cuts; $ind++) {
                                    $pdf2->Cell($pdf2->school_header_position, 4, "", 0, 0, 'C');
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 13);
                                    $pdf2->Cell($pdf2->school_header_position, 4, strtoupper(trim($pdf2->school_name)), 0, 0, 'C');
                                    // Arial  15
                                    $pdf2->Ln();
                                    $pdf2->SetFont('Arial', '', 8);
                                    $pdf2->Cell($pdf2->school_header_position, 4, "P.O Box : " . $pdf2->school_po . "-" . $pdf2->school_BOX_CODE, 0, 0, 'C');
                                    $pdf2->Ln();
                                    $pdf2->Cell($pdf2->school_header_position, 4, "Contact us: " . $pdf2->school_contact, 0, 0, 'C');
                                    // Line break
                                    $pdf2->Ln(10);
                                    $pdf2->SetFont('Arial', 'U', 10);
                                    $pdf2->Cell($pdf2->school_header_position, 5, $pdf2->school_document_title . "", 0, 0, 'C');
                                    $pdf2->SetTitle($pdf2->school_document_title);
                                    $pdf2->SetFont('', '');
                                    $pdf2->SetAuthor($_SESSION['username']);
                                    $pdf2->Ln(8);
                                    $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                                    $width = array(8, 35, 35, 35, 35, 35);
                                    $pdf2->feesStructure($header, $data, $width);
                                    $pdf2->Ln();

                                    // insert logos
                                    $get_height = round($pdf2->GetY());
                                    $pdf2->Image($pdf2->school_logo,6,$old_x_pos,15);
                                    $pdf2->Image($pdf2->arm_of_gov,190,$old_x_pos,15);
                                    $old_x_pos = $get_height+5;
                                    if (($ind + 1) % $cuts != 0) {
                                        $pdf2->SetLineWidth(0.1);
                                        $pdf2->SetDash(1, 1);
                                        $pdf2->Cell(195, 0, "", 'B');
                                        $pdf2->SetDash();
                                    }
                                }
                                if ($in != count($school_classes) - 1) {
                                    $pdf2->AddPage();
                                    $old_x_pos = 6;
                                }
                            }
                            $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                        } else {
                            echo "<p style='color:red;'><b>Nore:</b><br> -No classes present!</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Nore:</b><br> -No class has been selected to display the fees structure!</p>";
                }
            } elseif ($student_options == "bySpecific") {
                include_once("../ajax/finance/financial.php");
                // get the student admission number
                if (strlen($student_admno_in) > 0) {
                    $student_infor = getStudDetail($conn2, $student_admno_in);
                    $stud_class = $student_infor['stud_class'];
                    // check if the student is a border
                    $isBoarder = isBoarding($student_infor['adm_no'], $conn2);
                    $dorm_name = $isBoarder ? getDormitory($student_infor['adm_no'], $conn2) : "Null";
                    $isTransport = isTransport($conn2, $student_infor['adm_no']);
                    $trans_infor = getRouteEnrolled($student_infor['adm_no'], $conn2);
                    $sub_trans_infor = $trans_infor;
                    

                    $term = getTermV2($conn2);
                    $balance = getBalanceReports($student_infor['adm_no'], $term, $conn2);
                    $feespaidbystud = getFeespaidByStudent($student_infor['adm_no'], $conn2);
                    

                    // check if the student is enrolled for transport
                    $pdf = new PDF('P', 'mm', 'A4');
                    // Column headings
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    $tittle = "Fees Structure for " . ucwords(strtolower($student_infor['first_name'] . " " . $student_infor['second_name']));
                    // $data = $fees_data;
                    $pdf->set_document_title($tittle);
                    $pdf->setHeaderPos(190);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->AddPage();
                    // set the title for the student
                    $pdf->Ln();
                    $pdf->SetFillColor(245, 245, 245);
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Name : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, ucwords(strtolower($student_infor['first_name'] . " " . $student_infor['second_name'] . " " . $student_infor['surname'])), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Reg No : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, $student_infor['adm_no'], 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Level : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, classNameReport($student_infor['stud_class']), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Boarding : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, $isBoarder ? "Enrolled {" . $dorm_name . "}" : "Not - Enrolled", 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Transport : ", 'LR', 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    // $pdf->Cell(153, 6, $isTransport ? "Enrolled {" . $tran_route_infor . "}" : "Not - Enrolled", 1, 0, 'L', 0);
                    if($isTransport){
                        if (count($trans_infor)) {
                            for($index = 0; $index < count($trans_infor); $index++){
                                if($index == 0){
                                    $pdf->Cell(33, 6, "Term : ".$trans_infor[$index]->term, 1, 0, 'R', true);
                                    $pdf->Cell(120, 6, "Enrolled {" . "Route: " . $trans_infor[$index]->route_name . " @ Kes " . number_format($trans_infor[$index]->route_price) . "}", 1, 0, 'L', 0);
                                }else{
                                    if($index+1 == count($trans_infor)){
                                        $pdf->Cell(37, 6, "", 'LRB', 0, 'R', true);
                                    }else{
                                        $pdf->Cell(37, 6, "", 'LR', 0, 'R', true);
                                    }
                                    $pdf->Cell(33, 6, "Term : ".$trans_infor[$index]->term, 1, 0, 'R', true);
                                    $pdf->Cell(120, 6, "Enrolled {" . "Route: " . $trans_infor[$index]->route_name . " @ Kes " . number_format($trans_infor[$index]->route_price) . "}", 1, 0, 'L', 0);
                                }
                                $pdf->Ln();
                                
                                if($term == $trans_infor[$index]->term){
                                    break;
                                }
                            }
                        }else{
                            $pdf->Cell(153, 6, "Not - Enrolled", 1, 0, 'L', 0);
                        }
                    }else{
                        $pdf->Cell(153, 6, "Not - Enrolled", 1, 0, 'L', 0);
                    }
                    // $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Amount Paid : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($feespaidbystud), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Balance as of " . $term . ": ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($balance), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $last_acad_yr = lastACADyrBal($student_infor['adm_no'], $conn2);
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Last Academic Yr Bal: ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($last_acad_yr), 1, 0, 'L', 0);
                    $pdf->Ln(10);
                    $pdf->SetFont('Arial', '', 8);
                    // get the feestructure depending on the student class and boarding section
                    $select = "SELECT `expenses`,`roles` ,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`activated`,`ids` FROM fees_structure WHERE `classes` LIKE ?";
                    $daros = "%|" . $stud_class . "|%";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s", $daros);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fees_data = [];
                    $number = 1;
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $expenses = $row['expenses'];
                            $roles = $row['roles'];
                            $TERM_1 = $row['TERM_1'];
                            $TERM_2 = $row['TERM_2'];
                            $TERM_3 = $row['TERM_3'];
                            $classes = $row['classes'];
                            $activated = $row['activated'];
                            array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                            $number++;
                        }
                    }
                    // ALREADY COLLECTED THE STUDENTS FEES STRUCTURE
                    // ADD THE TRANSPORT STRUCTURE IS ENROLLED IN THE TRANSPORT SYSTEM
                    if ($isTransport) {
                        $term_1 = 0;
                        $term_2 = 0;
                        $term_3 = 0;
                        for($ind = 0; $ind < count($sub_trans_infor);$ind++){
                            if($sub_trans_infor[$ind]->term == "TERM_1"){
                                $term_1 = $sub_trans_infor[$ind]->route_price;
                            }
                            if($sub_trans_infor[$ind]->term == "TERM_2"){
                                $term_2 = $sub_trans_infor[$ind]->route_price;
                            }
                            if($sub_trans_infor[$ind]->term == "TERM_3"){
                                $term_3 = $sub_trans_infor[$ind]->route_price;
                            }
                        }
                        array_push($fees_data, array($number, "Transport", $term_1, $term_2, $term_3, "Transport"));
                        $pdf->SetAuthor($_SESSION['username']);
                        $pdf->Ln(8);
                    }
                    $data = $fees_data;
                    $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                    $width = array(8, 35, 35, 35, 35, 35);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'BU', 10);
                    $pdf->Cell(190, 5, "Fees Structure", 0, 0, 'C', 0);
                    $pdf->Ln();
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'BU', 10);
                    $pdf->feesStructure($header, $data, $width);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                } else {
                    echo "<p style='color:red;'><b>Nore:</b><br> -Please provide Reg No. for the specific student!</p>";
                }
            }
        } elseif ($finance_entity == "payroll_information") {
            $mystaff_lists_select = $_POST['mystaff_lists_select'];
            if ($mystaff_lists_select != "-1") {
                $mystaff_data = getStaffData($conn);
                $selected_staff = [];
                for ($i = 0; $i < count($mystaff_data); $i++) {
                    if ($mystaff_data[$i]['user_id'] == $mystaff_lists_select) {
                        $selected_staff = $mystaff_data[$i];
                    }
                }
                if (count($selected_staff) > 0) {
                    // get the staff payroll information
                    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $selected_staff['user_id'] . "'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->execute();
                    $stmt->store_result();
                    $rnums = $stmt->num_rows;
                    $current_balance = 0;
                    $current_balance_monNyear = 0;
                    $salary_amount = 0;
                    if ($rnums > 0) {
                        if ($row = $result->fetch_assoc()) {
                            $current_balance = $row['current_balance'];
                            $current_balance_monNyear = $row['current_balance_monNyear'];
                            $salary_amount = explode(",", $row['salary_amount']);
                            $salary_amount = $salary_amount[count($salary_amount) - 1];
                        }
                        // create pdf
                        // get basic information
                        $pdf2 = new PDF('P', 'mm', 'A4');
                        // Column headings
                        // Data loading
                        // $data = $pdf2->LoadData('countries.txt');
                        $tittle = "Payslip for " . ucwords(strtolower($selected_staff['fullname']));
                        // $data = $student_data;
                        $pdf2->set_document_title($tittle);
                        $pdf2->setHeaderPos(200);
                        $pdf2->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf2->set_school_name($_SESSION['schname']);
                        $pdf2->set_school_po($_SESSION['po_boxs']);
                        $pdf2->set_school_box_code($_SESSION['box_codes']);
                        $pdf2->set_school_contact($_SESSION['school_contact']);
                        $pdf2->AddPage();
                        // Line break
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Staff Name : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, ucwords(strtolower($selected_staff['fullname'])), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Age : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $date1 = date_create($selected_staff['dob']);
                        $date2 = date_create(date("Y-m-d"));
                        $diff = date_diff($date1, $date2);
                        $diffs = $diff->format("%y Yr(s)");
                        $pdf2->Cell(30, 5, $diffs, 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Staff Role : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, authority($selected_staff['auth']), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "I`d No : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, $selected_staff['nat_id'], 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Staff Netpay : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $number = 1;
                        $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number);
                        $salary_amount -= $_SESSION['total_advances'];
                        unset($_SESSION['total_advances']);
                        $pdf2->Cell(30, 5, "Kes " . number_format($salary_amount), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Last Month Paid : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, $current_balance_monNyear, 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', '', 10);
                        $pdf2->Cell(30, 5, "Salary Balance : ", 0, 0, 'L', 0);
                        $pdf2->SetFont('Helvetica', '', 9);
                        $pdf2->Cell(30, 5, "Kes " . number_format($current_balance), 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->Cell(190, 5, "", 'B', 0, 'L', 0);
                        $pdf2->Ln(10);
                        // earnings
                        // get the staff earnings and allowances
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', 'BU', 10);
                        $pdf2->Cell(30, 5, "Earnings & Reliefs", 0, 0, 'L', 0);
                        $pdf2->Ln();
                        $pdf2->SetFont('Helvetica', 'B', 10);
                        $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number);
                        $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                        $w = array(15, 70, 30, 30, 30);
                        $pdf2->salaryTables($header, $earnings, $w);
                        // get the staff deductions
                        $pdf2->Ln(10);
                        $pdf2->SetFont('Helvetica', 'BU', 10);
                        $pdf2->Cell(30, 5, "Deductions", 0, 0, 'L', 0);
                        $number = 1;
                        $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                        $w = array(15, 70, 30, 30, 30);
                        $pdf2->Ln();
                        $pdf2->salaryTables($header, $deductions, $w);
                        $pdf2->Ln(10);
                        $pdf2->SetFillColor(157, 183, 184);
                        $pdf2->Cell(85, 1, '', 0, 0, 0, 0);
                        $pdf2->Cell(45, 6, 'Net Pay', 1, 0, 'L', true);
                        $pdf2->Cell(45, 6, "Kes " . number_format($salary_amount), 1, 0, 'L', true);
                        $pdf2->Ln();
                        $pdf2->Ln();
                        $pdf2->Cell(30, 1, '', 0, 0, 0, 0);
                        $pdf2->Write(6, "If you have questions about this payslip please contact : " . $_SESSION['school_contact'] . "");
                        $pdf2->Ln();
                        $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
                    } else {
                        echo "<p style='color:red;'><b>Note:</b><br> - Staff not enrolled in the Payroll System!</p>";
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br> - Staff not present!</p>";
                }
            } else {
                // display the payroll data for all the staffs
                $mystaff_data = getStaffData($conn);
                $selected_staff = [];
                // get basic information
                $pdf2 = new PDF('P', 'mm', 'A4');
                $pdf2->setHeaderPos(200);
                $pdf2->setSchoolLogo("../../" . schoolLogo($conn));
                $pdf2->set_school_name($_SESSION['schname']);
                $pdf2->set_school_po($_SESSION['po_boxs']);
                $pdf2->set_school_box_code($_SESSION['box_codes']);
                $pdf2->set_school_contact($_SESSION['school_contact']);
                $tittle = "Payslip for All Staffs";
                // $data = $student_data;
                $pdf2->set_document_title($tittle);
                for ($i = 0; $i < count($mystaff_data); $i++) {
                    $selected_staff = $mystaff_data[$i];
                    if (count($selected_staff) > 0) {
                        // get the staff payroll information
                        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $selected_staff['user_id'] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->execute();
                        $stmt->store_result();
                        $rnums = $stmt->num_rows;
                        $current_balance = 0;
                        $current_balance_monNyear = 0;
                        $salary_amount = 0;
                        if ($rnums > 0) {
                            if ($row = $result->fetch_assoc()) {
                                $current_balance = $row['current_balance'];
                                $current_balance_monNyear = $row['current_balance_monNyear'];
                                $salary_amount = explode(",", $row['salary_amount']);
                                $salary_amount = $salary_amount[count($salary_amount) - 1];
                            }
                            $pdf2->AddPage();
                            // Line break
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Staff Name : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, ucwords(strtolower($selected_staff['fullname'])), 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Age : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $date1 = date_create($selected_staff['dob']);
                            $date2 = date_create(date("Y-m-d"));
                            $diff = date_diff($date1, $date2);
                            $diffs = $diff->format("%y Yr(s)");
                            $pdf2->Cell(30, 5, $diffs, 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Staff Role : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, authority($selected_staff['auth']), 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "I`d No : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, $selected_staff['nat_id'], 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Staff Netpay : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $number = 1;
                            $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number);
                            $salary_amount -= $_SESSION['total_advances'];
                            unset($_SESSION['total_advances']);
                            $pdf2->Cell(30, 5, "Kes " . number_format($salary_amount), 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Last Month Paid : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, $current_balance_monNyear, 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', '', 10);
                            $pdf2->Cell(30, 5, "Salary Balance : ", 0, 0, 'L', 0);
                            $pdf2->SetFont('Helvetica', '', 9);
                            $pdf2->Cell(30, 5, "Kes " . number_format($current_balance), 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->Cell(190, 5, "", 'B', 0, 'L', 0);
                            $pdf2->Ln(10);
                            // earnings
                            // get the staff earnings and allowances
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', 'BU', 10);
                            $pdf2->Cell(30, 5, "Earnings & Reliefs", 0, 0, 'L', 0);
                            $pdf2->Ln();
                            $pdf2->SetFont('Helvetica', 'B', 10);
                            $number = 1;
                            $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number);
                            $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                            $w = array(15, 70, 30, 30, 30);
                            $pdf2->salaryTables($header, $earnings, $w);
                            // get the staff deductions
                            $pdf2->Ln(10);
                            $pdf2->SetFont('Helvetica', 'BU', 10);
                            $pdf2->Cell(30, 5, "Deductions", 0, 0, 'L', 0);
                            $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                            $w = array(15, 70, 30, 30, 30);
                            $pdf2->Ln();
                            $pdf2->salaryTables($header, $deductions, $w);
                            $pdf2->Ln(10);
                            $pdf2->SetFillColor(157, 183, 184);
                            $pdf2->Cell(85, 1, '', 0, 0, 0, 0);
                            $pdf2->Cell(45, 6, 'Net Pay', 1, 0, 'L', true);
                            $pdf2->Cell(45, 6, "Kes " . number_format($salary_amount), 1, 0, 'L', true);
                            $pdf2->Ln();
                            $pdf2->Ln();
                            $pdf2->Cell(30, 1, '', 0, 0, 0, 0);
                            $pdf2->Write(6, "If you have questions about this payslip please contact : " . $_SESSION['school_contact'] . "");
                            $pdf2->Ln();
                        }
                    }
                }
                $tittle = "Payslip for All Staff";
                // $data = $student_data;
                // $pdf2->school_document_title = $tittle;
                $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
            }
        } elseif ($finance_entity == "expenses") {
            require_once "../ajax/finance/financial.php";
            $expense_data = [];
            $total_expense = 0;
            if ($expense_category != "All") {
                $select = "SELECT * FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? AND `exp_category` = ? ORDER BY `expid` DESC";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("sss",$from_date_finance,$to_date_finance,$expense_category);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while($row = $result->fetch_assoc()){
                        // expense name
                        $expense_name = get_expense($row['exp_category'],$conn2);
                        $expense_category = $expense_name != null ? $expense_name['expense_name'] : $row['exp_category'];

                        // create the expense data
                        $row_data = array($row['exp_name'],$expense_category,$row['document_number'],$row['exp_amount'],$row['expense_date']." ".$row['exp_time']);
                        array_push($expense_data,$row_data);
                        $total_expense+=$row['exp_amount'];
                    }
                }
            }else{
                $select = "SELECT * FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? ORDER BY `expid` DESC";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$from_date_finance,$to_date_finance);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while($row = $result->fetch_assoc()){
                        // expense name
                        $expense_name = get_expense($row['exp_category'],$conn2);
                        $expense_category = $expense_name != null ? $expense_name['expense_name'] : $row['exp_category'];

                        // create the expense data
                        $row_data = array($row['exp_name'],$expense_category,$row['document_number'],$row['exp_amount'],$row['expense_date']." ".$row['exp_time']);
                        array_push($expense_data,$row_data);
                        $total_expense+=$row['exp_amount'];
                    }
                }
            }
            if(count($expense_data) > 0){
                // create the PDF file

                // create the pdf file
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->setHeaderPos(200);
                // Column headings
                $header = array('No', 'Expense', 'Category','Units', 'Unit Price', 'Total',  'Date');
                // Data loading
                // $data = $pdf->LoadData('countries.txt');
                $tittle = "No records to display";
                if ($expense_category == "All") {
                    $tittle = "Expense Table, Period: from ".date("dS M Y",strtotime($from_date_finance))." to ".date("dS M Y",strtotime($to_date_finance));
                }else {
                    $tittle = "Expense Table, Category: ".$expense_category.", Period: from (".date("dS M Y",strtotime($from_date_finance)).") to (".date("dS M Y",strtotime($to_date_finance)).")";
                }
                $pdf->set_document_title($tittle);
                $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                $pdf->set_school_name($_SESSION['schname']);
                $pdf->set_school_po($_SESSION['po_boxs']);
                $pdf->set_school_box_code($_SESSION['box_codes']);
                $pdf->set_school_contact($_SESSION['school_contact']);
                $pdf->SetMargins(5, 5);
                $pdf->AddPage();
                $pdf->Cell(40, 10, "Statistics", 0, 0, 'L', false);
                $pdf->Ln();
                $pdf->SetFont('Times', 'I', 9);
                $pdf->Cell(30, 5, "Total Expenses :", 0, 0, 'L', false);
                $pdf->Cell(30, 5, "Kes " . number_format($total_expense), 0, 0, 'L', false);
                $pdf->Ln();

                // display the data in tables
                // echo json_encode($expense_data);
                $width = array(8, 40, 40, 20, 25, 25, 35);
                $pdf->Ln();
                $pdf->SetFont('Helvetica', 'B', 8);
                $pdf->expenseTable($header, $expense_data, $width);
                $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");

                $pdf->Output();
            }else{
                echo "<p style='color:red;'>No expenses recorded between the periods defined!</p>";
            }
        }
    }elseif (isset($_POST['finance_entity']) && isset($_POST['xslx'])) {
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $table_style = [
            'font' => [
                'bold' => true,
                'name' => 'Calibri Light',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF9DB7B8',
                ],
            ],
        ];
        $table_style_2 = [
            'font' => [
                'bold' => false,
                'name' => 'Calibri Light',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $finance_entity = $_POST['finance_entity'];
        $period_selection = $_POST['period_selection'];
        // echo $period_selection;
        $from_date_finance = $_POST['from_date_finance'] ? $_POST['from_date_finance'] : date("Y-m-d");
        $to_date_finance = $_POST['to_date_finance'] ? $_POST['to_date_finance'] : date("Y-m-d");
        $specific_date_finance = $_POST['specific_date_finance'] ? $_POST['specific_date_finance'] : date("Y-m-d");
        $student_options = $_POST['student_options'];
        $student_admno_in = $_POST['student_admno_in'];
        $student_class_fin = $_POST['student_class_fin'];
        $reminder_message = $_POST['reminder_message'];
        $expense_category = isset($_POST['expense_category']) ? $_POST['expense_category'] : "All";

        if ($finance_entity == "fees_collection") {
            if (strlen($student_class_fin) > 0 && strlen($period_selection) > 0 && $student_options == "byClass") {
                // fees collection of specific date and specific class
                if ($student_class_fin != "all") {
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } elseif ($period_selection == "period") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction`  BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '" . date("Y-m-d") . "' ORDER BY `transaction_id` DESC";
                    }
                    // echo $select;
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $student_data = getStudents($student_class_fin, $conn2);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $credit_note = 0;
                    $reversed = 0;
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }elseif($mode_of_pay == "Credit Note"){
                                $credit_note+=$amount_paid;
                            }
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $mode_of_pay, $pay_for, $date, $payBy);
                            if ($amount_paid != 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    $tittle = "No records to display";
                    if ($period_selection == "specific_date") {
                        $tittle = classNameReport($student_class_fin) . " Fees list on " . date("dS M Y", strtotime($specific_date_finance));
                    } elseif ($period_selection == "period") {
                        $tittle = classNameReport($student_class_fin) . " Fees list from " . date("dS M Y", strtotime($from_date_finance)) . " to " . date("dS M Y", strtotime($to_date_finance));
                    } else {
                        $tittle = "No records to display";
                    }
                    $data = $finance_list;
                    // Create new Spreadsheet object
                    $spreadsheet = new Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                        ->setLastModifiedBy($_SESSION['username'])
                        ->setTitle($tittle)
                        ->setSubject($tittle)
                        ->setDescription($_SESSION['username']." ".$tittle);
                    // HEADER DATA
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr("Sheet 1",0,31));
                    // set the statistics
                    $worksheet->setCellValue("A1", "Statistics");
                    $worksheet->setCellValue("A2", "Cash");
                    $worksheet->setCellValue("A3", "M-Pesa");
                    $worksheet->setCellValue("A4", "Bank");
                    $worksheet->setCellValue("A5", "Reversed");
                    $worksheet->setCellValue("B2", $cash);
                    $worksheet->setCellValue("B3", $mpesa);
                    $worksheet->setCellValue("B4", $bank);
                    $worksheet->setCellValue("B5", $reversed);
                    $worksheet->setCellValue("B6", $credit_note);
                    $worksheet->setCellValue("A7", "Total Recieved");
                    $worksheet->setCellValue("B7", ($cash+$mpesa+$bank+$reversed+$credit_note));
                    $worksheet->setCellValue("D9","Fees Collection Table");

                    // set the header
                    for ($i = 0; $i < count($header); $i++) {
                        $worksheet->setCellValue("".$letters[$i]."10", $header[$i]);
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A10:".$letters[count($header)-1]."10")->applyFromArray($table_style);

                    // set the values for the data
                    for ($index=0; $index < count($data); $index++) { 
                        for($index1=0; $index1 < count($data[$index]); $index1++){
                            $worksheet->setCellValue("".$letters[$index1]."".($index+11), $data[$index][$index1]);
                        }
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A11:".$letters[count($header)-1]."".(count($data)+10))->applyFromArray($table_style_2);

                    // Set active sheet index to the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    // set auto width
                    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                        // set auto width
                        for ($indexing=0; $indexing < count($header); $indexing++) {
                            $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                        }
                    }
                    // Redirect output to a client’s web browser (Xls)
                    header('Content-Type: application/vnd.ms-excel');;
                    header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                    header('Cache-Control: max-age=0');

                    $writer = new Xls($spreadsheet);
                    $writer->save('php://output');
                    exit;
                } else {
                    // this brings all the transactions for the whole institution
                    $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } elseif ($period_selection == "period") {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction`  BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `date_of_transaction` = '" . date("Y-m-d") . "' ORDER BY `transaction_id` DESC";
                    }
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $student_data = getStudents("-1", $conn2);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $credit_note = 0;
                    $reversed = 0;
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                                // echo $cash."<br>";
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }elseif($mode_of_pay == "Credit Note"){
                                $credit_note+=$amount_paid;
                            }
                            // $amount_paid = ($amount_paid);
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $mode_of_pay, $pay_for, $date, $payBy);
                            // array_push($finance_list,$stud_data);
                            if ($amount_paid > 0 || $amount_paid < 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    $tittle = "Fees recieved on " . date("dS M Y", strtotime($specific_date_finance));
                    $tittle = "No records to display";
                    if ($period_selection == "specific_date") {
                        $tittle = "Fees recieved on " . date("dS M Y", strtotime($specific_date_finance));
                    } elseif ($period_selection == "period") {
                        $tittle = "Fees recieved from (" . date("D dS M Y", strtotime($from_date_finance)) . ") to (" . date("D dS M Y", strtotime($to_date_finance)) . ")";
                    } else {
                        $tittle = "No records to display";
                    }
                    $data = $finance_list;

                    // Column headings
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                    
                    // Create new Spreadsheet object
                    $spreadsheet = new Spreadsheet();

                    // Set document properties
                    $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                        ->setLastModifiedBy($_SESSION['username'])
                        ->setTitle($tittle)
                        ->setSubject($tittle)
                        ->setDescription($_SESSION['username']." ".$tittle);
                    // HEADER DATA
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                    // Add data
                    $worksheet = $spreadsheet->getActiveSheet();
                    $worksheet->setTitle(substr("Sheet 1",0,31));
                    // set the statistics
                    $worksheet->setCellValue("A1", "Statistics");
                    $worksheet->setCellValue("A2", "Cash");
                    $worksheet->setCellValue("A3", "M-Pesa");
                    $worksheet->setCellValue("A4", "Bank");
                    $worksheet->setCellValue("A5", "Reversed");
                    $worksheet->setCellValue("A6", "Credit Note");
                    $worksheet->setCellValue("B2", $cash);
                    $worksheet->setCellValue("B3", $mpesa);
                    $worksheet->setCellValue("B4", $bank);
                    $worksheet->setCellValue("B5", $reversed);
                    $worksheet->setCellValue("B6", $credit_note);
                    $worksheet->setCellValue("A7", "Total Recieved");
                    $worksheet->setCellValue("B7", ($cash+$mpesa+$bank+$reversed+$credit_note));
                    $worksheet->setCellValue("D9","Fees Collection Table");

                    // set the header
                    for ($i = 0; $i < count($header); $i++) {
                        $worksheet->setCellValue("".$letters[$i]."10", $header[$i]);
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A10:".$letters[count($header)-1]."10")->applyFromArray($table_style);

                    // set the values for the data
                    for ($index=0; $index < count($data); $index++) { 
                        for($index1=0; $index1 < count($data[$index]); $index1++){
                            $worksheet->setCellValue("".$letters[$index1]."".($index+11), $data[$index][$index1]);
                        }
                    }
                    $spreadsheet->getActiveSheet()->getStyle("A11:".$letters[count($header)-1]."".(count($data)+10))->applyFromArray($table_style_2);

                    // Set active sheet index to the first sheet
                    $spreadsheet->setActiveSheetIndex(0);
                    // set auto width
                    foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                        // set auto width
                        for ($indexing=0; $indexing < count($header); $indexing++) {
                            $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                        }
                    }
                    // Redirect output to a client’s web browser (Xls)
                    header('Content-Type: application/vnd.ms-excel');;
                    header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                    header('Cache-Control: max-age=0');

                    $writer = new Xls($spreadsheet);
                    $writer->save('php://output');
                    exit;
                }
            } elseif ($student_options == "bySpecific" && strlen($period_selection) > 0) {
                // if the student id is set proceed
                if (strlen($student_admno_in) > 0) {
                    if ($period_selection == "specific_date") {
                        $select = "SELECT * FROM `finance` WHERE `stud_admin` = '$student_admno_in' AND `date_of_transaction` = '$specific_date_finance' ORDER BY `transaction_id` DESC";
                    } else {
                        $select = "SELECT * FROM `finance` WHERE `stud_admin` = '$student_admno_in' AND `date_of_transaction` BETWEEN '$from_date_finance' AND '$to_date_finance' ORDER BY `transaction_id` DESC";
                    }
                    // echo $select;
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $student_data = getStudents("-1", $conn2);
                    $staff_data = getStaffData($conn);
                    $finance_list = [];
                    $number = 1;
                    $cash = 0;
                    $mpesa = 0;
                    $bank = 0;
                    $reversed = 0;
                    $credit_note = 0;
                    $student_name = "Null";
                    $classNameReport = "Null";
                    $capture_balance = "Kes 0";
                    while ($row = $result->fetch_assoc()) {
                        // check if the student is in that class if present take the student name
                        $students_present = checkPresentStud($student_data, $row['stud_admin']);
                        if ($number == 1) {
                            $capture_balance = "Kes " . number_format($row['balance']);
                        }
                        // echo $students_present." ".$row['stud_admin']."<br>";
                        if ($students_present >= 0) {
                            // add the student in the student list
                            $fullname = ucwords(strtolower($student_data[$students_present]['first_name'] . " " . $student_data[$students_present]['second_name']));
                            $amount_paid = $row['amount'];
                            $student_name = $fullname;
                            $balance = ($row['balance']);
                            $mode_of_pay = $row['mode_of_pay'];
                            if ($mode_of_pay == "cash") {
                                $cash = $cash + $amount_paid;
                            } elseif ($mode_of_pay == "bank") {
                                $bank += $amount_paid;
                            } elseif ($mode_of_pay == "mpesa") {
                                $mpesa += $amount_paid;
                            } elseif ($mode_of_pay == "reverse") {
                                $reversed += $amount_paid;
                            }elseif($mode_of_pay == "Credit Note"){
                                $credit_note += $amount_paid;
                            }
                            // $amount_paid = ($amount_paid);
                            $payBy = explode(" ", ucwords(strtolower(getStaffNamedReport($staff_data, $row['payBy']))))[0];
                            $pay_for = $row['payment_for'];
                            $date = date("dS M Y H:ia", strtotime($row['date_of_transaction'] . " " . $row['time_of_transaction']));
                            $transaction_code = $row['transaction_code'];
                            $stud_data = array($number, $amount_paid, $balance, $transaction_code, $fullname, $mode_of_pay, $pay_for, $date, $payBy);
                            // array_push($finance_list,$stud_data);
                            if ($amount_paid != 0) {
                                array_push($finance_list, $stud_data);
                            }
                            $number++;
                        }
                    }
                    // create the pdf file
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->setHeaderPos(200);
                    // Column headings
                    $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    $title = "No data to display!";
                    if ($period_selection == "specific_date") {
                        $tittle = $student_name . " Fees list on " . date("dS M Y", strtotime($specific_date_finance));
                    } else {
                        $tittle = $student_name . " Fees list from " . date("dS M Y", strtotime($from_date_finance)) . " to " . date("dS M Y", strtotime($to_date_finance));
                    }
                    $data = $finance_list;
                    if (count($data) > 0) {
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();
    
                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
                        // HEADER DATA
                        $header = array('No', 'Fees Paid', 'Balance', 'Code', 'Student Name', 'Mode', 'Votehead', 'Date', 'Served By');
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr("Sheet 1",0,31));
                        // set the statistics
                        $worksheet->setCellValue("A1", "Statistics");
                        $worksheet->setCellValue("A2", "Cash");
                        $worksheet->setCellValue("A3", "M-Pesa");
                        $worksheet->setCellValue("A4", "Bank");
                        $worksheet->setCellValue("A5", "Reversed");
                        $worksheet->setCellValue("A6", "Credit Note");
                        $worksheet->setCellValue("B2", $cash);
                        $worksheet->setCellValue("B3", $mpesa);
                        $worksheet->setCellValue("B4", $bank);
                        $worksheet->setCellValue("B5", $reversed);
                        $worksheet->setCellValue("B6", $credit_note);
                        $worksheet->setCellValue("A7", "Total Recieved");
                        $worksheet->setCellValue("B7", ($cash+$mpesa+$bank+$reversed+$credit_note));
                        $worksheet->setCellValue("A9", "Last Year Balance");
                        $worksheet->setCellValue("B9", ($capture_balance));
                        $worksheet->setCellValue("D11","Fees Collection Table");
    
                        // set the header
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."12", $header[$i]);
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A12:".$letters[count($header)-1]."12")->applyFromArray($table_style);
    
                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+13), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A13:".$letters[count($header)-1]."".(count($data)+12))->applyFromArray($table_style_2);
    
                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        
                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        echo "<p style='color:red;'>No records to display</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Please provide the student admission number to proceed!</p>";
                }
            } else {
                echo "<p style='color:red;'>Select Class or Date option to proceed!</p>";
            }
        } elseif ($finance_entity == "class_balances") {
            if (strlen($student_class_fin) > 0 && $student_options == "byClass") {
                include_once("../ajax/finance/financial.php");
                // display the class balance
                if ($student_class_fin != "all") {
                    // get per class
                    $student_data = getStudents($student_class_fin, $conn2);
                    $number = 1;
                    $stud_data = [];
                    $total_fees = 0;
                    $fees_repo_paid = 0;
                    $total_balances = 0;
                    $term = getTermV2($conn2);
                    for ($index = 0; $index < count($student_data); $index++) {
                        // get the student data data
                        $Fullname = ucwords(strtolower($student_data[$index]['first_name'] . " " . $student_data[$index]['second_name']));
                        $gender = $student_data[$index]['gender'] == "Male" ? "M" : "F";
                        $classes = classNameReport($student_class_fin);
                        // get fees to pay by the student
                        $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                        // echo $term;
                        $fees_paid = ($feespaidbystud);
                        $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                        $balance = ($balanced * 1);
                        $total_fees += $balanced + $feespaidbystud;
                        $fees_repo_paid += $feespaidbystud;
                        $total_balances += $balanced;
                        // LAST ACADEMIC YEAR BALANCE
                        $last_acad_yr = lastACADyrBal($student_data[$index]['adm_no'], $conn2);
                        $acad_balance = ($last_acad_yr);
                        $border = isBoarding($student_data[$index]['adm_no'], $conn2) ? (getBoardingFees($conn2, $student_class_fin, "null", $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                        $transport = isTransport($conn2, $student_data[$index]['adm_no']) ? (transportBalanceSinceAdmission($conn2, $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                        $data = array($number, $Fullname, $student_data[$index]['adm_no'], $classes, $gender, $fees_paid, $balance, $border, $transport, $acad_balance);
                        array_push($stud_data, $data);
                        $number++;
                    }
                    if (count($stud_data) > 0) {
                        // Column headings
                        $header = array('No', 'Fullname', 'Reg No.', 'Class', 'Sex', 'Fees paid', 'Balance', 'Bording', 'Transport', 'Last Yrs Bal');
                        // Data loading
                        // $data = $pdf->LoadData('countries.txt');
                        $tittle = "Fees list for " . classNameReport($student_class_fin);
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();

                        // my data
                        $data = $stud_data;
    
                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
                        // HEADER DATA
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr("Sheet 1",0,31));
                        // set the statistics
                        $worksheet->setCellValue("A1", "Statistics");
                        $worksheet->setCellValue("A2", "Fees Paid");
                        $worksheet->setCellValue("A3", "Balance");
                        $worksheet->setCellValue("A4", "Fees to be paid");
                        $worksheet->setCellValue("B2", $fees_repo_paid);
                        $worksheet->setCellValue("B3", $total_balances);
                        $worksheet->setCellValue("B4", $total_fees);
                        $worksheet->setCellValue("D7","Fees Balance Table");
    
                        // set the header
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);
    
                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                            }
                        }
                        $spreadsheet->getActiveSheet()->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);
    
                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        
                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        echo "<p style='color:red;'><b>Note:</b><br>No students present to display.</p>";
                    }
                } elseif ($student_class_fin == "all") {
                    $school_classes = getSchoolCLass($conn2);
                    if (count($school_classes) > 0) {
                        $term = getTermV2($conn2);
                        // display the data
                        $tittle = "Fees list for whole school";
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();

                        // my data
                        // $data = $stud_data;
    
                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);

                        // Column headings
                        // sheet counter
                        $sheet_counter = 0;
                        $header = array('No', 'Fullname', 'Reg No.', 'Class', 'Sex', 'Fees paid', 'Balance', 'Bording', 'Transport', 'Last Yrs Bal');
                        for ($ind = 0; $ind < count($school_classes); $ind++) {
                            // get per class
                            $student_class_fin = $school_classes[$ind];
                            $student_data = getStudents($student_class_fin, $conn2);
                            $number = 1;
                            $stud_data = [];
                            $total_fees = 0;
                            $fees_repo_paid = 0;
                            $total_balances = 0;
                            for ($index = 0; $index < count($student_data); $index++) {
                                // echo $number;
                                // get the student data data
                                $Fullname = ucwords(strtolower($student_data[$index]['first_name'] . " " . $student_data[$index]['second_name']));
                                $gender = $student_data[$index]['gender'] == "Male" ? "M" : "F";
                                // echo $gender;
                                $classes = classNameReport($student_class_fin);
                                // get fees to pay by the student
                                $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                                $fees_paid = ($feespaidbystud);
                                $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                                $balance = ($balanced * 1);
                                $total_fees += $balanced + $feespaidbystud;
                                $fees_repo_paid += $feespaidbystud;
                                $total_balances += $balanced;
                                // LAST ACADEMIC YEAR BALANCE
                                $last_acad_yr = lastACADyrBal($student_data[$index]['adm_no'], $conn2);
                                $acad_balance = ($last_acad_yr);
                                $border = isBoarding($student_data[$index]['adm_no'], $conn2) ? (getBoardingFees($conn2, $student_class_fin) * 1) : "Not-enrolled";
                                $transport = isTransport($conn2, $student_data[$index]['adm_no']) ? (transportBalanceSinceAdmission($conn2, $student_data[$index]['adm_no']) * 1) : "Not-enrolled";
                                $data = array($number, $Fullname, $student_data[$index]['adm_no'], $classes, $gender, $fees_paid, $balance, $border, $transport, $acad_balance);
                                array_push($stud_data, $data);
                                $number++;
                            }
                            if (count($stud_data) > 0) {
                                if ($sheet_counter == 0) {
                                    $data = $stud_data;
                                    // Add data
                                    $worksheet = $spreadsheet->getActiveSheet();
                                    $worksheet->setTitle(substr(classNameReport($student_class_fin),0,31));
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Statistics");
                                    $worksheet->setCellValue("A2", "Fees Paid");
                                    $worksheet->setCellValue("A3", "Balance");
                                    $worksheet->setCellValue("A4", "Fees to be paid");
                                    $worksheet->setCellValue("B2", $fees_repo_paid);
                                    $worksheet->setCellValue("B3", $total_balances);
                                    $worksheet->setCellValue("B4", $total_fees);
                                    $worksheet->setCellValue("D7","Fees Balance Table");
                
                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                                    }
                                    $worksheet->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);
                
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);
                                }else{
                                    $data = $stud_data;
                                    // Add data
                                    $worksheet = $spreadsheet->createSheet();
                                    $worksheet->setTitle(substr(classNameReport($student_class_fin),0,31));
                                    // set the statistics
                                    $worksheet->setCellValue("A1", "Statistics");
                                    $worksheet->setCellValue("A2", "Fees Paid");
                                    $worksheet->setCellValue("A3", "Balance");
                                    $worksheet->setCellValue("A4", "Fees to be paid");
                                    $worksheet->setCellValue("B2", $fees_repo_paid);
                                    $worksheet->setCellValue("B3", $total_balances);
                                    $worksheet->setCellValue("B4", $total_fees);
                                    $worksheet->setCellValue("D7","Fees Balance Table");
                
                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."8", $header[$i]);
                                    }
                                    $worksheet->getStyle("A8:".$letters[count($header)-1]."8")->applyFromArray($table_style);
                
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+9), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A9:".$letters[count($header)-1]."".(count($data)+8))->applyFromArray($table_style_2);
                        
                                }
                                $sheet_counter++;
                            }
                        }

                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');

                        // spreadsheet
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    }
                }
            } else {
                echo "<p><b>Note:</b><br>Producing fee balance for a specific student is not available at the moment.</p>";
            }
        } elseif ($finance_entity == "fees_reminders") {
            echo "<p style='color:red;'>Only PDF Format is available for this section</p>";
        } elseif ($finance_entity == "fees_structure") {
            include("fees_reminder.php");
            // continue and get if the class has been selected for the fees structure
            if ($student_options == "byClass") {
                // get the class and pull the fees structure
                if (strlen($student_class_fin) > 0) {
                    // get what class it is
                    if ($student_class_fin != "all") {
                        $select = "SELECT `expenses`,`roles` ,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`activated`,`ids` FROM fees_structure WHERE `classes` LIKE ?";
                        $daros = "%|" . $student_class_fin . "|%";
                        $stmt = $conn2->prepare($select);
                        $stmt->bind_param("s", $daros);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $fees_data = [];
                        if ($result) {
                            $number = 1;
                            while ($row = $result->fetch_assoc()) {
                                $expenses = $row['expenses'];
                                $roles = $row['roles'];
                                $TERM_1 = $row['TERM_1'];
                                $TERM_2 = $row['TERM_2'];
                                $TERM_3 = $row['TERM_3'];
                                $classes = $row['classes'];
                                $activated = $row['activated'];
                                array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                                $number++;
                            }
                        }

                        // my tittle
                        $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                        $tittle = "Fees Structure " . classNameReport($student_class_fin);
                        $data = $fees_data;
                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();
    
                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
                        // HEADER DATA
                        // Add data
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr($tittle,0,31));
                        $worksheet->setCellValue("D2","Fees Structure");
    
                        // set the header
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."3", $header[$i]);
                        }
                        $worksheet->getStyle("A3:".$letters[count($header)-1]."3")->applyFromArray($table_style);
    
                        // set the values for the data
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+4), $data[$index][$index1]);
                            }
                        }
                        $worksheet->getStyle("A4:".$letters[count($header)-1]."".(count($data)+3))->applyFromArray($table_style_2);
    
                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        
                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        // display for all classes
                        $school_classes = getSchoolCLass($conn2);
                        if (count($school_classes) > 0) {
                            
                            // Create new Spreadsheet object
                            $spreadsheet = new Spreadsheet();
        
                            // Set document properties
                            $tittle = "Whole School Fees Structure";
                            $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                            $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                                ->setLastModifiedBy($_SESSION['username'])
                                ->setTitle($tittle)
                                ->setSubject($tittle)
                                ->setDescription($_SESSION['username']." ".$tittle);

                                // loop through data
                            for ($in = 0; $in < count($school_classes); $in++) {
                                $my_class = $school_classes[$in];
                                $tittle = "Fees Structure " . classNameReport($my_class);
                                $select = "SELECT `expenses`,`roles` ,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`activated`,`ids` FROM fees_structure WHERE `classes` LIKE ?";
                                $daros = "%|" . $my_class . "|%";
                                $stmt = $conn2->prepare($select);
                                $stmt->bind_param("s", $daros);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $fees_data = [];
                                if ($result) {
                                    $number = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $expenses = $row['expenses'];
                                        $roles = $row['roles'];
                                        $TERM_1 = $row['TERM_1'];
                                        $TERM_2 = $row['TERM_2'];
                                        $TERM_3 = $row['TERM_3'];
                                        $classes = $row['classes'];
                                        $activated = $row['activated'];
                                        array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                                        $number++;
                                    }
                                }
                                // get data
                                $data = $fees_data;

                                if ($in == 0) {
                                    // HEADER DATA
                                    // Add data
                                    $worksheet = $spreadsheet->getActiveSheet();
                                    $worksheet->setTitle(substr($tittle,0,31));
                                    $worksheet->setCellValue("D2","Fees Structure");
                
                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."3", $header[$i]);
                                    }
                                    $worksheet->getStyle("A3:".$letters[count($header)-1]."3")->applyFromArray($table_style);
                
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+4), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A4:".$letters[count($header)-1]."".(count($data)+3))->applyFromArray($table_style_2);
                                }else{
                                    // HEADER DATA
                                    // Add data
                                    $worksheet = $spreadsheet->createSheet();
                                    $worksheet->setTitle(substr($tittle,0,31));
                                    $worksheet->setCellValue("D2","Fees Structure");
                
                                    // set the header
                                    for ($i = 0; $i < count($header); $i++) {
                                        $worksheet->setCellValue("".$letters[$i]."3", $header[$i]);
                                    }
                                    $worksheet->getStyle("A3:".$letters[count($header)-1]."3")->applyFromArray($table_style);
                
                                    // set the values for the data
                                    for ($index=0; $index < count($data); $index++) { 
                                        for($index1=0; $index1 < count($data[$index]); $index1++){
                                            $worksheet->setCellValue("".$letters[$index1]."".($index+4), $data[$index][$index1]);
                                        }
                                    }
                                    $worksheet->getStyle("A4:".$letters[count($header)-1]."".(count($data)+3))->applyFromArray($table_style_2);
                                }
                            }
                            // Set active sheet index to the first sheet
                            $spreadsheet->setActiveSheetIndex(0);
                            
                            // set auto width
                            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                                // set auto width
                                for ($indexing=0; $indexing < count($header); $indexing++) {
                                    $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                                }
                            }

                            // Redirect output to a client’s web browser (Xls)
                            $tittle = "Whole School Fees Structure";
                            header('Content-Type: application/vnd.ms-excel');;
                            header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                            header('Cache-Control: max-age=0');
        
                            $writer = new Xls($spreadsheet);
                            $writer->save('php://output');
                            exit;
                        }else{
                            echo "<p style='color:red;'><b>Nore:</b><br> -No classes present!</p>";
                        }
                    }
                } else {
                    echo "<p style='color:red;'><b>Nore:</b><br> -No class has been selected to display the fees structure!</p>";
                }
            } elseif ($student_options == "bySpecific") {
                include_once("../ajax/finance/financial.php");
                // get the student admission number
                if (strlen($student_admno_in) > 0) {
                    $student_infor = getStudDetail($conn2, $student_admno_in);
                    $stud_class = $student_infor['stud_class'];
                    // check if the student is a border
                    $isBoarder = isBoarding($student_infor['adm_no'], $conn2);
                    $dorm_name = $isBoarder ? getDormitory($student_infor['adm_no'], $conn2) : "Null";
                    $isTransport = isTransport($conn2, $student_infor['adm_no']);
                    $trans_infor = getRouteEnrolled($student_infor['adm_no'], $conn2);
                    $sub_trans_infor = $trans_infor;
                    

                    $term = getTermV2($conn2);
                    $balance = getBalanceReports($student_infor['adm_no'], $term, $conn2);
                    $feespaidbystud = getFeespaidByStudent($student_infor['adm_no'], $conn2);
                    

                    // check if the student is enrolled for transport
                    $pdf = new PDF('P', 'mm', 'A4');
                    // Column headings
                    // Data loading
                    // $data = $pdf->LoadData('countries.txt');
                    $tittle = "Fees Structure for " . ucwords(strtolower($student_infor['first_name'] . " " . $student_infor['second_name']));
                    // $data = $fees_data;
                    $pdf->set_document_title($tittle);
                    $pdf->setHeaderPos(190);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);
                    $pdf->AddPage();
                    // set the title for the student
                    $pdf->Ln();
                    $pdf->SetFillColor(245, 245, 245);
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Name : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, ucwords(strtolower($student_infor['first_name'] . " " . $student_infor['second_name'] . " " . $student_infor['surname'])), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Reg No : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, $student_infor['adm_no'], 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Student Level : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, classNameReport($student_infor['stud_class']), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Boarding : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, $isBoarder ? "Enrolled {" . $dorm_name . "}" : "Not - Enrolled", 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Transport : ", 'LR', 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    // $pdf->Cell(153, 6, $isTransport ? "Enrolled {" . $tran_route_infor . "}" : "Not - Enrolled", 1, 0, 'L', 0);
                    if($isTransport){
                        if (count($trans_infor)) {
                            for($index = 0; $index < count($trans_infor); $index++){
                                if($index == 0){
                                    $pdf->Cell(33, 6, "Term : ".$trans_infor[$index]->term, 1, 0, 'R', true);
                                    $pdf->Cell(120, 6, "Enrolled {" . "Route: " . $trans_infor[$index]->route_name . " @ Kes " . number_format($trans_infor[$index]->route_price) . "}", 1, 0, 'L', 0);
                                }else{
                                    if($index+1 == count($trans_infor)){
                                        $pdf->Cell(37, 6, "", 'LRB', 0, 'R', true);
                                    }else{
                                        $pdf->Cell(37, 6, "", 'LR', 0, 'R', true);
                                    }
                                    $pdf->Cell(33, 6, "Term : ".$trans_infor[$index]->term, 1, 0, 'R', true);
                                    $pdf->Cell(120, 6, "Enrolled {" . "Route: " . $trans_infor[$index]->route_name . " @ Kes " . number_format($trans_infor[$index]->route_price) . "}", 1, 0, 'L', 0);
                                }
                                $pdf->Ln();
                                
                                if($term == $trans_infor[$index]->term){
                                    break;
                                }
                            }
                        }else{
                            $pdf->Cell(153, 6, "Not - Enrolled", 1, 0, 'L', 0);
                        }
                    }else{
                        $pdf->Cell(153, 6, "Not - Enrolled", 1, 0, 'L', 0);
                    }
                    // $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Amount Paid : ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($feespaidbystud), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Balance as of " . $term . ": ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($balance), 1, 0, 'L', 0);
                    $pdf->Ln();
                    $last_acad_yr = lastACADyrBal($student_infor['adm_no'], $conn2);
                    $pdf->SetFont('Helvetica', 'BI', 8);
                    $pdf->Cell(37, 6, "Last Academic Yr Bal: ", 1, 0, 'R', true);
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(153, 6, "Kes " . number_format($last_acad_yr), 1, 0, 'L', 0);
                    $pdf->Ln(10);
                    $pdf->SetFont('Arial', '', 8);
                    // get the feestructure depending on the student class and boarding section
                    $select = "SELECT `expenses`,`roles` ,`TERM_1`,`TERM_2`,`TERM_3`,`classes`,`activated`,`ids` FROM fees_structure WHERE `classes` LIKE ?";
                    $daros = "%|" . $stud_class . "|%";
                    $stmt = $conn2->prepare($select);
                    $stmt->bind_param("s", $daros);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $fees_data = [];
                    $number = 1;
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $expenses = $row['expenses'];
                            $roles = $row['roles'];
                            $TERM_1 = $row['TERM_1'];
                            $TERM_2 = $row['TERM_2'];
                            $TERM_3 = $row['TERM_3'];
                            $classes = $row['classes'];
                            $activated = $row['activated'];
                            array_push($fees_data, array($number, $expenses, $TERM_1, $TERM_2, $TERM_3, $roles));
                            $number++;
                        }
                    }
                    // ALREADY COLLECTED THE STUDENTS FEES STRUCTURE
                    // ADD THE TRANSPORT STRUCTURE IS ENROLLED IN THE TRANSPORT SYSTEM
                    if ($isTransport) {
                        $term_1 = 0;
                        $term_2 = 0;
                        $term_3 = 0;
                        for($ind = 0; $ind < count($sub_trans_infor);$ind++){
                            if($sub_trans_infor[$ind]->term == "TERM_1"){
                                $term_1 = $sub_trans_infor[$ind]->route_price;
                            }
                            if($sub_trans_infor[$ind]->term == "TERM_2"){
                                $term_2 = $sub_trans_infor[$ind]->route_price;
                            }
                            if($sub_trans_infor[$ind]->term == "TERM_3"){
                                $term_3 = $sub_trans_infor[$ind]->route_price;
                            }
                        }
                        array_push($fees_data, array($number, "Transport", $term_1, $term_2, $term_3, "Transport"));
                        $pdf->SetAuthor($_SESSION['username']);
                        $pdf->Ln(8);
                    }
                    $data = $fees_data;
                    $header = array('No', 'Votehead', 'TERM 1', 'TERM 2', 'TERM 3', 'Role');
                    $width = array(8, 35, 35, 35, 35, 35);
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'BU', 10);
                    $pdf->Cell(190, 5, "Fees Structure", 0, 0, 'C', 0);
                    $pdf->Ln();
                    $pdf->Ln();
                    $pdf->SetFont('Times', 'BU', 10);
                    $pdf->feesStructure($header, $data, $width);
                    $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
                } else {
                    echo "<p style='color:red;'><b>Nore:</b><br> -Please provide Reg No. for the specific student!</p>";
                }
            }
        } elseif ($finance_entity == "payroll_information") {
            $mystaff_lists_select = $_POST['mystaff_lists_select'];
            if ($mystaff_lists_select != "-1") {
                $mystaff_data = getStaffData($conn);
                $selected_staff = [];
                for ($i = 0; $i < count($mystaff_data); $i++) {
                    if ($mystaff_data[$i]['user_id'] == $mystaff_lists_select) {
                        $selected_staff = $mystaff_data[$i];
                    }
                }
                if (count($selected_staff) > 0) {
                    // get the staff payroll information
                    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $selected_staff['user_id'] . "'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->execute();
                    $stmt->store_result();
                    $rnums = $stmt->num_rows;
                    $current_balance = 0;
                    $current_balance_monNyear = 0;
                    $salary_amount = 0;
                    if ($rnums > 0) {
                        if ($row = $result->fetch_assoc()) {
                            $current_balance = $row['current_balance'];
                            $current_balance_monNyear = $row['current_balance_monNyear'];
                            $salary_amount = explode(",", $row['salary_amount']);
                            $salary_amount = $salary_amount[count($salary_amount) - 1];
                        }
                        // Column headings
                        $tittle = "Payslip for " . ucwords(strtolower($selected_staff['fullname']));

                        // Create new Spreadsheet object
                        $spreadsheet = new Spreadsheet();
    
                        // Set document properties
                        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                            ->setLastModifiedBy($_SESSION['username'])
                            ->setTitle($tittle)
                            ->setSubject($tittle)
                            ->setDescription($_SESSION['username']." ".$tittle);
                        // HEADER DATA
                        
                        // GET STAFF AGE
                        $date1 = date_create($selected_staff['dob']);
                        $date2 = date_create(date("Y-m-d"));
                        $diff = date_diff($date1, $date2);
                        $diffs = $diff->format("%y Yr(s)");
                        
                        // GET SALARY DETAILS
                        $worksheet = $spreadsheet->getActiveSheet();
                        $worksheet->setTitle(substr("Payslip",0,31));
                        $worksheet->setCellValue("A2",$tittle);
                        $worksheet->setCellValue("A4","Staff Name:");
                        $worksheet->setCellValue("B4",ucwords(strtolower($selected_staff['fullname'])));
                        $worksheet->setCellValue("A5","Age");
                        $worksheet->setCellValue("B5",$diffs);
                        $worksheet->setCellValue("A6","Staff Role");
                        $worksheet->setCellValue("B6",authority($selected_staff['auth']));
                        $worksheet->setCellValue("A7","I`d No ");
                        $worksheet->setCellValue("B7",$selected_staff['nat_id']);
                        $worksheet->setCellValue("A8","Staff Netpay");
                        $worksheet->setCellValue("B8",$salary_amount);
                        $worksheet->setCellValue("A9","Last Month Paid");
                        $worksheet->setCellValue("B9",$current_balance_monNyear);
                        $worksheet->setCellValue("A10","Salary Balance");
                        $worksheet->setCellValue("B10",$current_balance);

                        $number = 1;
                        // SALARY EARNINGS 
                        $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number);
                        $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                        
                        // set the header
                        $worksheet->setCellValue("B12","Earnings");
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."13", $header[$i]);
                        }
                        $worksheet->getStyle("A13:".$letters[count($header)-1]."13")->applyFromArray($table_style);
    
                        // earning data
                        $data = $earnings;

                        // set the values for the data
                        $row_counter = 13;
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+14), $data[$index][$index1]);
                            }
                            $row_counter++;
                        }
                        $worksheet->getStyle("A14:".$letters[count($header)-1]."".(count($data)+13))->applyFromArray($table_style_2);
                        $row_counter+=1;
                        $worksheet->setCellValue("D$row_counter","Total");

                        $number = 1;
                        $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                        $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number);
                        $salary_amount -= $_SESSION['total_advances'];
                        unset($_SESSION['total_advances']);


                        // set the header
                        $data = $deductions;
                        $row_counter+=2;
                        // set the header
                        $worksheet->setCellValue("B".$row_counter,"Deductions");
                        $row_counter+=1;
                        for ($i = 0; $i < count($header); $i++) {
                            $worksheet->setCellValue("".$letters[$i]."$row_counter", $header[$i]);
                        }
                        
                        // set the values for the data
                        $row_counter+=1;
                        for ($index=0; $index < count($data); $index++) { 
                            for($index1=0; $index1 < count($data[$index]); $index1++){
                                $worksheet->setCellValue("".$letters[$index1]."".($index+$row_counter), $data[$index][$index1]);
                            }
                        }
                        $row_counter+=count($data);
                        $worksheet->setCellValue("D$row_counter","Total");
                        
                        // Set active sheet index to the first sheet
                        $spreadsheet->setActiveSheetIndex(0);
                        
                        // set auto width
                        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                            // set auto width
                            for ($indexing=0; $indexing < count($header); $indexing++) {
                                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                            }
                        }

                        // Redirect output to a client’s web browser (Xls)
                        header('Content-Type: application/vnd.ms-excel');;
                        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                        header('Cache-Control: max-age=0');
    
                        $writer = new Xls($spreadsheet);
                        $writer->save('php://output');
                        exit;
                    } else {
                        echo "<p style='color:red;'><b>Note:</b><br> - Staff not enrolled in the Payroll System!</p>";
                    }
                } else {
                    echo "<p style='color:red;'><b>Note:</b><br> - Staff not present!</p>";
                }
            } else {
                // display the payroll data for all the staffs
                $mystaff_data = getStaffData($conn);
                $selected_staff = [];
                // get basic information
                $tittle = "Payslip for All Staffs";

                // Create new Spreadsheet object
                $spreadsheet = new Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                    ->setLastModifiedBy($_SESSION['username'])
                    ->setTitle($tittle)
                    ->setSubject($tittle)
                    ->setDescription($_SESSION['username']." ".$tittle);

                    $counter = 0;
                for ($i = 0; $i < count($mystaff_data); $i++) {
                    $selected_staff = $mystaff_data[$i];
                    if (count($selected_staff) > 0) {
                        // get the staff payroll information
                        $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $selected_staff['user_id'] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->execute();
                        $stmt->store_result();
                        $rnums = $stmt->num_rows;
                        $current_balance = 0;
                        $current_balance_monNyear = 0;
                        $salary_amount = 0;
                        if ($rnums > 0) {
                            if ($row = $result->fetch_assoc()) {
                                $current_balance = $row['current_balance'];
                                $current_balance_monNyear = $row['current_balance_monNyear'];
                                $salary_amount = explode(",", $row['salary_amount']);
                                $salary_amount = $salary_amount[count($salary_amount) - 1];
                            }
                            
                            // deduct dates
                            $date1 = date_create($selected_staff['dob']);
                            $date2 = date_create(date("Y-m-d"));
                            $diff = date_diff($date1, $date2);
                            $diffs = $diff->format("%y Yr(s)");
                            
                            // index 1
                            if ($counter == 0) {
                                // GET SALARY DETAILS
                                $worksheet = $spreadsheet->getActiveSheet();
                                $worksheet->setTitle(substr(ucwords(strtolower($selected_staff['fullname']))." Payslip",0,31));
                                $worksheet->setCellValue("A2",ucwords(strtolower($selected_staff['fullname']))." Payslip");
                                $worksheet->setCellValue("A4","Staff Name:");
                                $worksheet->setCellValue("B4",ucwords(strtolower($selected_staff['fullname'])));
                                $worksheet->setCellValue("A5","Age");
                                $worksheet->setCellValue("B5",$diffs);
                                $worksheet->setCellValue("A6","Staff Role");
                                $worksheet->setCellValue("B6",authority($selected_staff['auth']));
                                $worksheet->setCellValue("A7","I`d No ");
                                $worksheet->setCellValue("B7",$selected_staff['nat_id']);
                                $worksheet->setCellValue("A8","Staff Netpay");
                                $worksheet->setCellValue("B8",$salary_amount);
                                $worksheet->setCellValue("A9","Last Month Paid");
                                $worksheet->setCellValue("B9",$current_balance_monNyear);
                                $worksheet->setCellValue("A10","Salary Balance");
                                $worksheet->setCellValue("B10",$current_balance);

                                $number = 1;
                                // SALARY EARNINGS 
                                $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number);
                                $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                                
                                // set the header
                                $worksheet->setCellValue("B12","Earnings");
                                for ($inside_index = 0; $inside_index < count($header); $inside_index++) {
                                    $worksheet->setCellValue("".$letters[$inside_index]."13", $header[$inside_index]);
                                }
                                $worksheet->getStyle("A13:".$letters[count($header)-1]."13")->applyFromArray($table_style);
            
                                // earning data
                                $data = $earnings;

                                // set the values for the data
                                $row_counter = 13;
                                for ($index=0; $index < count($data); $index++) { 
                                    for($index1=0; $index1 < count($data[$index]); $index1++){
                                        $worksheet->setCellValue("".$letters[$index1]."".($index+14), $data[$index][$index1]);
                                    }
                                    $row_counter++;
                                }
                                $worksheet->getStyle("A14:".$letters[count($header)-1]."".(count($data)+13))->applyFromArray($table_style_2);
                                $row_counter+=1;
                                $worksheet->setCellValue("D$row_counter","Total");

                                $number = 1;
                                $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                                $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number);
                                $salary_amount -= $_SESSION['total_advances'];
                                unset($_SESSION['total_advances']);


                                // set the header
                                $data = $deductions;
                                $row_counter+=2;
                                // set the header
                                $worksheet->setCellValue("B".$row_counter,"Deductions");
                                $row_counter+=1;
                                for ($inside_index = 0; $inside_index < count($header); $inside_index++) {
                                    $worksheet->setCellValue("".$letters[$inside_index]."$row_counter", $header[$inside_index]);
                                }
                                $worksheet->getStyle("A$row_counter:".$letters[count($header)-1]."$row_counter")->applyFromArray($table_style);
                                
                                // set the values for the data
                                $row_counter+=1;
                                for ($index=0; $index < count($data); $index++) { 
                                    for($index1=0; $index1 < count($data[$index]); $index1++){
                                        $worksheet->setCellValue("".$letters[$index1]."".($index+$row_counter), $data[$index][$index1]);
                                    }
                                }
                                $worksheet->getStyle("A".($row_counter).":".$letters[count($header)-1]."".(count($data)+($row_counter-1)))->applyFromArray($table_style_2);
                                $row_counter+=count($data);
                                $worksheet->setCellValue("D$row_counter","Total");
                            }else{
                                // GET SALARY DETAILS
                                $worksheet = $spreadsheet->createSheet();
                                $worksheet->setTitle(substr(ucwords(strtolower($selected_staff['fullname']))." Payslip",0,31));
                                $worksheet->setCellValue("A2",ucwords(strtolower($selected_staff['fullname']))." Payslip");
                                $worksheet->setCellValue("A4","Staff Name:");
                                $worksheet->setCellValue("B4",ucwords(strtolower($selected_staff['fullname'])));
                                $worksheet->setCellValue("A5","Age");
                                $worksheet->setCellValue("B5",$diffs);
                                $worksheet->setCellValue("A6","Staff Role");
                                $worksheet->setCellValue("B6",authority($selected_staff['auth']));
                                $worksheet->setCellValue("A7","I`d No ");
                                $worksheet->setCellValue("B7",$selected_staff['nat_id']);
                                $worksheet->setCellValue("A8","Staff Netpay");
                                $worksheet->setCellValue("B8",$salary_amount);
                                $worksheet->setCellValue("A9","Last Month Paid");
                                $worksheet->setCellValue("B9",$current_balance_monNyear);
                                $worksheet->setCellValue("A10","Salary Balance");
                                $worksheet->setCellValue("B10",$current_balance);

                                $number = 1;
                                // SALARY EARNINGS 
                                $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number);
                                $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                                
                                // set the header
                                $worksheet->setCellValue("B12","Earnings");
                                for ($inside_index = 0; $inside_index < count($header); $inside_index++) {
                                    $worksheet->setCellValue("".$letters[$inside_index]."13", $header[$inside_index]);
                                }
                                $worksheet->getStyle("A13:".$letters[count($header)-1]."13")->applyFromArray($table_style);
            
                                // earning data
                                $data = $earnings;

                                // set the values for the data
                                $row_counter = 13;
                                for ($index=0; $index < count($data); $index++) { 
                                    for($index1=0; $index1 < count($data[$index]); $index1++){
                                        $worksheet->setCellValue("".$letters[$index1]."".($index+14), $data[$index][$index1]);
                                    }
                                    $row_counter++;
                                }
                                $worksheet->getStyle("A14:".$letters[count($header)-1]."".(count($data)+13))->applyFromArray($table_style_2);
                                $row_counter+=1;
                                $worksheet->setCellValue("D$row_counter","Total");

                                $number = 1;
                                $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                                $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number);
                                $salary_amount -= $_SESSION['total_advances'];
                                unset($_SESSION['total_advances']);


                                // set the header
                                $data = $deductions;
                                $row_counter+=2;
                                // set the header
                                $worksheet->setCellValue("B".$row_counter,"Deductions");
                                $row_counter+=1;
                                for ($inside_index = 0; $inside_index < count($header); $inside_index++) {
                                    $worksheet->setCellValue("".$letters[$inside_index]."$row_counter", $header[$inside_index]);
                                }
                                $worksheet->getStyle("A$row_counter:".$letters[count($header)-1]."$row_counter")->applyFromArray($table_style);
                                
                                // set the values for the data
                                $row_counter+=1;
                                for ($index=0; $index < count($data); $index++) { 
                                    for($index1=0; $index1 < count($data[$index]); $index1++){
                                        $worksheet->setCellValue("".$letters[$index1]."".($index+$row_counter), $data[$index][$index1]);
                                    }
                                }
                                $worksheet->getStyle("A".($row_counter).":".$letters[count($header)-1]."".(count($data)+($row_counter-1)))->applyFromArray($table_style_2);
                                $row_counter+=count($data);
                                $worksheet->setCellValue("D$row_counter","Total");
                            }
                            $counter++;
                        }
                    }
                }

                // payslip title
                $tittle = "Payslip for All Staff";
                // Set active sheet index to the first sheet
                $spreadsheet->setActiveSheetIndex(0);
                        
                // set auto width
                foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                    // set auto width
                    for ($indexing=0; $indexing < count($header); $indexing++) {
                        $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                    }
                }

                // Redirect output to a client’s web browser (Xls)
                header('Content-Type: application/vnd.ms-excel');;
                header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                header('Cache-Control: max-age=0');

                $writer = new Xls($spreadsheet);
                $writer->save('php://output');
                exit;
            }
        } elseif ($finance_entity == "expenses") {
            $expense_data = [];
            $total_expense = 0;
            $counter = 1;
            if ($expense_category != "All") {
                $select = "SELECT * FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? AND `exp_category` = ? ORDER BY `expid` DESC";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("sss",$from_date_finance,$to_date_finance,$expense_category);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while($row = $result->fetch_assoc()){
                        // create the expense data
                        $row_data = array($counter,$row['exp_name'],$row['exp_category'],$row['exp_quantity']." ".$row['unit_name'],$row['exp_unit_cost'],$row['exp_amount'],$row['expense_date']." ".$row['exp_time']);
                        array_push($expense_data,$row_data);
                        $total_expense+=$row['exp_amount'];
                        $counter++;
                    }
                }
            }else{
                $select = "SELECT * FROM `expenses` WHERE `expense_date` BETWEEN ? AND ? ORDER BY `expid` DESC";
                $stmt = $conn2->prepare($select);
                $stmt->bind_param("ss",$from_date_finance,$to_date_finance);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while($row = $result->fetch_assoc()){
                        // create the expense data
                        $row_data = array($counter,$row['exp_name'],$row['exp_category'],$row['exp_quantity']." ".$row['unit_name'],$row['exp_unit_cost'],$row['exp_amount'],$row['expense_date']." ".$row['exp_time']);
                        array_push($expense_data,$row_data);
                        $total_expense+=$row['exp_amount'];
                        $counter++;
                    }
                }
            }
            if(count($expense_data) > 0){
                // create the PDF file

                // create the pdf file
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->setHeaderPos(200);
                // Column headings
                $header = array('No', 'Expense', 'Category','Units', 'Unit Price', 'Total',  'Date');
                // Data loading
                // $data = $pdf->LoadData('countries.txt');
                $tittle = "No records to display";
                if ($expense_category == "All") {
                    $tittle = "Expense Table, Period: from ".date("dS M Y",strtotime($from_date_finance))." to ".date("dS M Y",strtotime($to_date_finance));
                }else {
                    $tittle = "Expense Table, Category: ".$expense_category.", Period: from (".date("dS M Y",strtotime($from_date_finance)).") to (".date("dS M Y",strtotime($to_date_finance)).")";
                }

                // Create new Spreadsheet object
                $spreadsheet = new Spreadsheet();

                // Set document properties
                $spreadsheet->getProperties()->setCreator($_SESSION['username'])
                    ->setLastModifiedBy($_SESSION['username'])
                    ->setTitle($tittle)
                    ->setSubject($tittle)
                    ->setDescription($_SESSION['username']." ".$tittle);

                // worksheet
                $worksheet = $spreadsheet->getActiveSheet();
                $worksheet->setTitle(substr("Expenses",0,31));
                $worksheet->setCellValue("A2","Statistics");
                $worksheet->setCellValue("A3","Total Expense");
                $worksheet->setCellValue("B3",$total_expense);

                // set the header
                $worksheet->setCellValue("B6","Earnings");
                for ($i = 0; $i < count($header); $i++) {
                    $worksheet->setCellValue("".$letters[$i]."7", $header[$i]);
                }
                $worksheet->getStyle("A7:".$letters[count($header)-1]."7")->applyFromArray($table_style);

                // earning data
                $data = $expense_data;

                // set the values for the data
                for ($index=0; $index < count($data); $index++) { 
                    for($index1=0; $index1 < count($data[$index]); $index1++){
                        $worksheet->setCellValue("".$letters[$index1]."".($index+8), $data[$index][$index1]);
                    }
                }
                $worksheet->getStyle("A8:".$letters[count($header)-1]."".(count($data)+7))->applyFromArray($table_style_2);
                
                // Set active sheet index to the first sheet
                $spreadsheet->setActiveSheetIndex(0);
                
                // set auto width
                foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                    // set auto width
                    for ($indexing=0; $indexing < count($header); $indexing++) {
                        $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
                    }
                }

                // Redirect output to a client’s web browser (Xls)
                header('Content-Type: application/vnd.ms-excel');;
                header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
                header('Cache-Control: max-age=0');

                $writer = new Xls($spreadsheet);
                $writer->save('php://output');
                exit;
            }else{
                echo "<p style='color:red;'>No expenses recorded between the periods defined!</p>";
            }
        }
    }elseif (isset($_POST['fees_payment_receipt'])) {
        include("../connections/conn1.php");
        include("../connections/conn2.php");

        $students_names = ucwords(strtolower($_POST['students_names']));
        $student_admission_no = $_POST['student_admission_no'];
        $amount_paid_by_student = $_POST['amount_paid_by_student'];
        $new_student_balance = $_POST['new_student_balance'];
        $mode_of_payments = $_POST['mode_of_payments'];
        $transaction_codes = $_POST['transaction_codes'];
        $payments_for = ucwords(strtolower($_POST['payments_for']));
        $fees_payment_receipt = $_POST['fees_payment_receipt'];
        $reciept_size = $_POST['reciept_size'];
        $fees_payment_opt_holder = $_POST['fees_payment_opt_holder'];
        $last_receipt_id_take = isset($_POST['last_receipt_id_take']) ? receiptNo($_POST['last_receipt_id_take']) : "001";
        $date_of_payments_fees = $fees_payment_opt_holder == "set" ? date("D dS-M-Y",strtotime($_POST['date_of_payments_fees'])) : date("D dS-M-Y");
        $time_of_payment_fees = $fees_payment_opt_holder == "set" ? date("H:i:s",strtotime($_POST['time_of_payment_fees'].":00")) : date("H:i:s");
        // echo $fees_payment_opt_holder;

        // var_dump($_POST);

        if ($reciept_size == "A4") {
            // create the pdf file
            $pdf = new PDF('P', 'mm', 'A4');
            $pdf->setHeaderPos(200);
            $tittle = $students_names . " Fees Receipt.";
            $pdf->set_document_title($tittle);
            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
            $pdf->set_school_name($_SESSION['schname']);
            $pdf->set_school_po($_SESSION['po_boxs']);
            $pdf->set_school_box_code($_SESSION['box_codes']);
            $pdf->set_school_contact($_SESSION['school_contact']);
            $pdf->AddPage();
            $pdf->Cell(40, 10, "Student Data", 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Times', 'I', 11);
            $pdf->Cell(40, 5, "Student Name :", 0, 0, 'L', false);
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(40, 5, $students_names, 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Times', 'I', 11);
            $pdf->Cell(40, 5, "Student Reg No. :", 0, 0, 'L', false);
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(40, 5, $student_admission_no, 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Times', 'I', 11);
            $pdf->Cell(40, 5, "Date Of Transaction. :", 0, 0, 'L', false);
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(40, 5, $_POST['reprint'] == "false" ? $date_of_payments_fees : $_POST['masiku'], 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->SetFont('Times', 'I', 11);
            $pdf->Cell(40, 5, "Time of Transaction. :", 0, 0, 'L', false);
            $pdf->SetFont('Times', '', 11);
            $pdf->Cell(40, 5, $_POST['reprint'] == "false" ? $time_of_payment_fees : $_POST['masaa'], 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->Ln(10);
            $width = array(8, 60, 30, 45, 40, 40);
            $pdf->SetFont('Helvetica', 'B', 9);
            include_once("../ajax/finance/financial.php");
            $term = getTermV2($conn2);
            $pdf->SetFillColor(219, 219, 219);
            $pdf->Cell(45, 6, "Title :", 1, 0, 'C', true);
            $pdf->Cell(150, 6, "Description", 1, 1, 'C', false);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(45, 6, "Payment For :", 1, 0, 'L', true);
            $pdf->Cell(150, 6, $payments_for, 1, 1, 'L', false);
            $pdf->Cell(45, 6, "Amount Paid :", 1, 0, 'L', true);
            $pdf->Cell(150, 6, $amount_paid_by_student, 1, 1, 'L', false);
            $pdf->Cell(45, 6, "Balance (as of " . $term . "):", 1, 0, 'L', true);
            $pdf->Cell(150, 6, $new_student_balance, 1, 1, 'L', false);
            $pdf->Cell(45, 6, "Transaction Code :", 1, 0, 'L', true);
            $pdf->Cell(150, 6, $transaction_codes, 1, 1, 'L', false);
            $pdf->Cell(45, 6, "Mode of Payment :", 1, 0, 'L', true);
            $pdf->Cell(150, 6, $mode_of_payments, 1, 1, 'L', false);
            // $header = array("No.", "Payment For", "Amount Paid", "Balance (as of " . $term . ")", "Transaction Code", "Mode of Payment");
            // $data = [array("1", $payments_for, $amount_paid_by_student, $new_student_balance, $transaction_codes, $mode_of_payments)];
            // $pdf->receipt_table($header, $data, $width);
            $pdf->Ln(10);
            $pdf->SetFont('Helvetica', '', 11);
            $pdf->Cell(40, 5, "Description: ", 0, 0, 'L', false);
            $pdf->Ln(10);
            $pdf->Cell(190, 0, "", 1, 0, 'L', false);
            $pdf->Ln(5);
            $pdf->Cell(190, 0, "", 1, 0, 'L', false);
            $pdf->Ln(5);
            $pdf->Cell(190, 0, "", 1, 0, 'L', false);
            $pdf->Ln(5);
            $pdf->Cell(120, 0, "", 1, 0, 'L', false);
            $pdf->Ln(5);
            $pdf->SetFont('Helvetica', 'BU', 11);
            $pdf->Cell(190, 10, "OFFICIAL USE ONLY!", 0, 0, 'C', false);
            $pdf->Ln();
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(70, 5, "School principal / Accountant Signature", 0, 0, 'L', false);
            $pdf->Ln(15);
            $pdf->Cell(120, 0, "", 1, 0, 'L', false);
            $pdf->Ln(10);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(120, 5, "School Stamp", 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->Cell(70, 50, "", 1, 0, 'L', false);
            $pdf->Ln(60);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(120, 5, "Note:", 0, 0, 'L', false);
            $pdf->Ln();
            $pdf->Cell(120, 5, "- This is a computer generated document, it is not valid without a school rubber stamp.", 0, 0, 'L', false);
            $pdf->Output();
        } elseif ($reciept_size == "A51") {
            include("fees_reminder.php");
            // create the pdf file
            $pdf = new PDF2('P', 'mm', 'A4');
            $pdf->setHeaderPos(200);
            $tittle = $students_names . " Fees Receipt.";
            $pdf->set_document_title($tittle);
            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
            $pdf->set_school_name($_SESSION['schname']);
            $pdf->set_school_po($_SESSION['po_boxs']);
            $pdf->set_school_box_code($_SESSION['box_codes']);
            $pdf->set_school_contact($_SESSION['school_contact']);
            $pdf->AddPage();
            $pdf->SetMargins(5, 5);

            // get the school information
            $school_info = getSchoolInfo($conn);
            $school_name = ucwords(strtoupper($school_info['school_name']));
            $school_motto = ucwords(strtolower($school_info['school_motto']));
            $school_admin_name = $school_info['school_admin_name'];
            $school_mail = $school_info['school_mail'];
            $county = $school_info['county'];
            $physicall_address = $school_info['physicall_address'];
            $country = $school_info['country'];
            $school_profile_image = "../" . $school_info['school_profile_image'];
            $po_box = $school_info['po_box'];
            $box_code = $school_info['box_code'];
            $school_contact = $school_info['school_contact'];
            $website_name = $school_info['website_name'];

            $pdf->Image($school_profile_image, 5, 10, 20, 20);
            $pdf->Image($pdf->arm_of_gov, 100, 15, 12, 12);
            $pdf->SetFont('Helvetica', 'B', 14);
            $pdf->SetFillColor(100, 100, 100);
            $pdf->SetTitle("Receipt for ".$students_names." Reg No. ".$student_admission_no.".");
            $pdf->Cell(15, 10, "", 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);

            $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 10, "", 0, 0, "L", false);
            $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);
            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);
            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
            // divider strip
            $pdf->SetFillColor(220, 220, 220);
            $pdf->Ln(5);
            $pdf->Cell(200, 2, "", 0, 1, 0, true);
            $pdf->SetFillColor(240, 240, 240);

            // start the receipt details
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->Cell(65, 10, "FEES PAYMENT RECEIPT", 0, 0, "C", false);
            $pdf->Cell(65, 10, "** SCHOOL COPY **", 0, 0, "C", false);
            $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

            // RECEIPT DETAILS
            // row 1
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30, 6, $_POST['reprint'] == "false" ? $date_of_payments_fees : date("D dS-M-Y", strtotime($_POST['masiku'] . " " . $_POST['masaa'])), 1, 0, "L",false);
            
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(40, 6, $_POST['reprint'] == "false" ? $time_of_payment_fees : date("H:i:s", strtotime($_POST['masaa'])), "RTB", 1, "L",false);
            // $pdf->Cell(53, 6, "", "RBT", 1, "L");

            // row 2
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30, 6, "Student Name. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(70, 6, $students_names, 1, 0, "L");
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

            // THIRD ROW
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 6, $amount_paid_by_student, 1, 0, "L");
            $new_numbers = new NUmbers();
            $new_number = returnNumbers($amount_paid_by_student)*1;
            $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
            $prefix = $new_number < 0? "Negative ":"";
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(158, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

            // voteheads paid for
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

            // another row
            $pdf->Cell(155,7,$payments_for,1,0,"L",false);
            $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

            // another row
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(20,7,"Served By : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
            $pdf->Cell(45,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(15,7,"Total:",1,0,"L",false);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

            // ANOTHER ROW
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Acc No. : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,$student_admission_no,"B",0,"L",false);
            $pdf->Cell(30,7,"","BR",0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(15,7,"Balance:",1,0,"L",false);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,"".$new_student_balance."",1,1,"L",false);

            // DISCLAIMER
            $pdf->SetFont('Helvetica', 'I', 9);
            $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
            // $pdf->Ln(5);
            // get the school payment option
            $select = "SELECT * FROM `settings` WHERE `sett` = 'payment details';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $json_data = $row['valued'];
                    if(isJson_reports($json_data)){
                        $json_data = json_decode($json_data);
                        $counter = 1;
                        if(count($json_data) > 0){
                            $pdf->SetFont('Helvetica', 'U', 10);
                            $pdf->Cell(200,6,"Acceptable payment options","",1,"L",false);
                            $pdf->SetFont('Helvetica', '', 9);
                        }
                        for ($index=0; $index < count($json_data); $index++) { 
                            if($json_data[$index]->show == "true"){
                                $pdf->Cell(10,4.5,($counter).". ","",0,"R",false); 
                                $pdf->Cell(190,4.5,$json_data[$index]->description,"",1,"L",false);
                                $counter++;
                            }
                        }
                    }
                }
            }
            
            $remaining_lines = 11-$counter;

            for($i = 0; $i < $remaining_lines; $i++){
                $pdf->Ln();
            }
            $pdf->Cell(200,0,"",1,1);
            $pdf->Ln(5);
            
            // space between
            $y = $pdf->GetY();
            $pdf->Image($school_profile_image, 5, $y, 20, 20);
            $pdf->Image($pdf->arm_of_gov, 100, $y+5, 12, 12);
            $pdf->SetFont('Helvetica', 'B', 14);
            $pdf->SetFillColor(100, 100, 100);
            $pdf->Cell(20, 10, "", 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);

            $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 10, "", 0, 0, "L", false);
            $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);

            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->SetFont('Helvetica', '', 8);
            $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);
            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y + 5);

            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 5, "", 0, 0, "L", false);
            $pdf->Cell(100, 5, "", 0, 0, "L", false);
            $pdf->SetFont('Helvetica', '', 8);
            $X = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->SetXY($X, $y - 5);
            $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
            // divider strip
            $pdf->SetFillColor(220, 220, 220);
            $pdf->Ln(5);
            $pdf->Cell(200, 2, "", 0, 1, 0, true);
            $pdf->SetFillColor(240, 240, 240);

            // start the receipt details
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->Cell(65, 10, "FEES PAYMENT RECEIPT", 0, 0, "C", false);
            $pdf->Cell(65, 10, "** STUDENT COPY **", 0, 0, "C", false);
            $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

            // RECEIPT DETAILS
            // row 1
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30, 6, $_POST['reprint'] == "false" ? $date_of_payments_fees : date("D dS-M-Y", strtotime($_POST['masiku'] . " " . $_POST['masaa'])), 1, 0, "L",false);

            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(40, 6, $_POST['reprint'] == "false" ? $time_of_payment_fees : date("H:i:s", strtotime($_POST['masaa'])), "RTB", 1, "L",false);
            // $pdf->Cell(53, 6, "", "RBT", 1, "L");

            // row 2
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30, 6, "Student Name. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(70, 6, $students_names, 1, 0, "L");
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

            // THIRD ROW
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(20, 6, $amount_paid_by_student, 1, 0, "L");
            $new_numbers = new NUmbers();
            $new_number = returnNumbers($amount_paid_by_student)*1;
            $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
            // $my_number = $new_numbers->convert_number($new_number);
            $prefix = $new_number < 0? "Negative ":"";
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(158, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

            // voteheads paid for
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

            // another row
            $pdf->Cell(155,7,$payments_for,1,0,"L",false);
            $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

            // another row
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(20,7,"Served By : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
            $pdf->Cell(45,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(15,7,"Total:",1,0,"L",false);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

            // ANOTHER ROW
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(30,7,"Acc No. : ",1,0,"L",true);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,$student_admission_no,"B",0,"L",false);
            // $pdf->Cell(75,7,"","BR",1,"L",false);
            $pdf->Cell(30,7,"","BR",0,"L",false);
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(15,7,"Balance:",1,0,"L",false);
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->Cell(30,7,"".$new_student_balance."",1,1,"L",false);

            // DISCLAIMER
            $pdf->SetFont('Helvetica', 'I', 9);
            $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
            // $pdf->Ln(5);
            // get the school payment option
            $select = "SELECT * FROM `settings` WHERE `sett` = 'payment details';";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result){
                if($row = $result->fetch_assoc()){
                    $json_data = $row['valued'];
                    if(isJson_reports($json_data)){
                        $json_data = json_decode($json_data);
                        $counter = 1;
                        if(count($json_data) > 0){
                            $pdf->SetFont('Helvetica', 'U', 10);
                            $pdf->Cell(200,6,"Acceptable payment options","",1,"L",false);
                            $pdf->SetFont('Helvetica', '', 9);
                        }
                        for ($index=0; $index < count($json_data); $index++) { 
                            if($json_data[$index]->show == "true"){
                                $pdf->Cell(10,5,($counter).". ","",0,"R",false); 
                                $pdf->Cell(190,5,$json_data[$index]->description,"",1,"L",false);
                                $counter++;
                            }
                        }
                    }
                }
            }

            $pdf->Output();
        }
    } elseif (isset($_POST['timetable_generation'])) {
        // process lesson
        // set times
        $first_lesson = $_POST['first_lesson'];
        $lesson_time = $_POST['lesson_time'];
        $breaks_lists = $_POST['breaks_lists'];
        $what_tt = $_POST['what_tt'];
        // echo $breaks_lists."<br>";
        // first open the file and ensure the file is readable
        include("../connections/conn1.php");
        $get_custom_table = $_SESSION['timetable_id'];
        $select = "SELECT * FROM `timetable_req` WHERE `ids` = '" . $get_custom_table . "'";
        $stmt = $conn->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $file_location = "";
        if ($result) {
            if ($row = $result->fetch_assoc()) {
                $file_location = $row['return_json'];
            }
        }
        if (strlen($file_location) > 0) {
            if (!is_dir($file_location)) {
                if (file_exists($file_location)) {
                    // chmod($file_location,0755);
                }
                if ($myfile = fopen($file_location, "r")) {
                    $read = fread($myfile, filesize($file_location));
                    if ($what_tt == "class_timetable") {
                        // get the number of lessons 
                        $block_tt = json_decode($read);
                        $metadata = json_decode($read)->metadata;
                        $lesson_count = count($block_tt->timetables[0]->blocktimetable[0]->classes[0]->lessons);
                        $first_lesson = date("H:i", strtotime($first_lesson));
                        // echo $first_lesson;

                        // set the timetable titles
                        $timetable_titles = ["Day"];
                        $breaks = (strlen($breaks_lists) > 0) ? json_decode($breaks_lists)->breaks : [];
                        for ($i = 0; $i < $lesson_count; $i++) {
                            $start_time = $first_lesson;
                            // loop inside the breaks and add them in the process
                            $date = date_create($first_lesson);
                            date_add($date, date_interval_create_from_date_string($lesson_time . " minutes"));
                            $first_lesson = date_format($date, "H:i");
                            $end_time = $first_lesson;
                            array_push($timetable_titles, $start_time . " - " . $end_time);
                            for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                $location = $breaks[$i2]->after;
                                $duration = $breaks[$i2]->period;

                                if ($i + 1 == $location) {
                                    $start_time = $first_lesson;
                                    // loop inside the breaks and add them in the process
                                    $date = date_create($first_lesson);
                                    date_add($date, date_interval_create_from_date_string($duration . " minutes"));
                                    $first_lesson = date_format($date, "H:i");
                                    $end_time = $first_lesson;

                                    array_push($timetable_titles, $start_time . " - " . $end_time);
                                }
                            }
                        }

                        $classtimetable = $block_tt->timetables[1]->classtimetable;
                        // end of timetable titles
                        // var_dump($timetable_titles);

                        // $lessons = [];
                        // display metadata $pdf = new PDF('P', 'mm', 'A4');// create the pdf file
                        $pdf = new PDF('L', 'mm', 'A4');
                        $tittle = "CLASS TIMETABLE.";
                        $pdf->SetMargins(3,3);
                        $pdf->SetFont('Times', '', 11);
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        // $pdf->AddPage();
                        // $pdf->setHeaderPos(200);


                        // set class timetable
                        for ($index = 0; $index < count($classtimetable); $index++) {
                            $classname = $classtimetable[$index]->classname;
                            // echo $classname."<br>";
                            $daysoftheweek = $classtimetable[$index]->daysoftheweek;
                            $week_lesson = [];

                            // headers
                            $pdf->AddPage();
                            // echo $highest_len;
                            // Colors, line width and bold font
                            $pdf->SetFillColor(157, 183, 184);
                            // $pdf->SetTextColor(255);
                            $pdf->SetDrawColor(0, 0, 0);
                            $pdf->SetLineWidth(.1);
                            // $pdf->SetFont('','B');
                            // set metadata
                            $subjects = $metadata[0]->subjects;
                            $subjects2 = [];
                            $subs = [];
                            for ($i = 0; $i < count($subjects); $i++) {
                                if (chckPrsnt($subs, $subjects[$i]->subject_id) == 0) {
                                    array_push($subs, $subjects[$i]->subject_id);
                                    array_push($subjects2, $subjects[$i]);
                                }
                            }
                            $pdf->SetFont('Times', 'U', 10);
                            $pdf->Cell(40, 6, $classname, 0, 1, 'C', false);
                            $pdf->SetFont('Times', '', 9);
                            $subjects = $subjects2;
                            $teachers = $metadata[2]->teachers;
                            $pdf->Cell(10, 5, "#", 1, 0, "C", true);
                            $pdf->Cell(50, 5, "Lesson Name", 1, 0, "C", true);
                            $pdf->Cell(20, 5, "Short", 1, 0, "C", true);
                            $pdf->Cell(70, 0, "");
                            $pdf->Cell(10, 5, "#", 1, 0, "C", true);
                            $pdf->Cell(50, 5, "Teacher Name", 1, 0, "C", true);
                            $pdf->Cell(20, 5, "Short", 1, 1, "C", true);

                            // array_pop($subjects);
                            // create not for techers
                            $longest = count($subjects);
                            if (count($teachers) > $longest) {
                                $longest = count($teachers);
                            }
                            // echo $longest;
                            for ($index6 = 0; $index6 < $longest; $index6++) {
                                if (count($subjects) > $index6) {
                                    $pdf->Cell(10, 5, ($index6 + 1), 1, 0, "C", false);
                                    $pdf->Cell(50, 5, ucwords(strtolower($subjects[$index6]->subjectname)), 1, 0, "C", false);
                                    $pdf->Cell(20, 5, $subjects[$index6]->subject_id, 1, 0, "C", false);
                                } else {
                                    $pdf->Cell(10, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(50, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(20, 5, "", 0, 0, "C", false);
                                }
                                $pdf->Cell(70, 0, "");
                                if (count($teachers) > $index6) {
                                    $pdf->Cell(10, 5, ($index6 + 1), 1, 0, "C", false);
                                    $pdf->Cell(50, 5, ucwords(strtolower($teachers[$index6]->teachername)), 1, 0, "C", false);
                                    $pdf->Cell(20, 5, $teachers[$index6]->teacherid, 1, 1, "C", false);
                                } else {
                                    $pdf->Cell(10, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(50, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(20, 5, "", 0, 1, "C", false);
                                }
                            }

                            // create the tables
                            // $x = $pdf->GetX();
                            // $y = $pdf->GetY();
                            // $pdf->MultiCell(25,5,"My name is HIllary I come from Kirintage My name is HIllary".$pdf->GetStringWidth(""),"LTRB","L",false);
                            // $pdf->SetXY($x + 25, $y);
                            // $pdf->MultiCell(25,5,"My name is HIllary I come from Kirintage                    ","LTRB","L",false);
                            $pdf->Ln();
                            // $pdf->SetFillColor(205, 211, 218);
                            $pdf->SetFont('Times', 'U', 10);
                            $pdf->Cell(40, 10, "Class Timetable", 0, 1, 'C', false);
                            // $pdf->SetFont('Times', 'BU', 11);
                            $widths_cut = [];
                            for ($index2 = 0; $index2 < count($daysoftheweek); $index2++) {
                                $lesson_data = [date("D", strtotime($daysoftheweek[$index2]->Day))];
                                $lessons_in = $daysoftheweek[$index2]->lessons;
                                for ($index3 = 0; $index3 < count($lessons_in); $index3++) {
                                    array_push($lesson_data, $lessons_in[$index3]);
                                    for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                        $location = $breaks[$i2]->after;
                                        $duration = $breaks[$i2]->period;
                                        $brake_name = $breaks[$i2]->brake_name;
                                        if ($index3 + 1 == $location) {
                                            array_push($lesson_data, $brake_name);
                                        }
                                    }
                                }
                                $width = 326;
                                $widths_cut = [$width - 310];
                                for ($ind = 0; $ind < (count($lesson_data)); $ind++) {
                                    $counters = round(310 / (count($lesson_data))) - 1;
                                    array_push($widths_cut, $counters);
                                    // var_dump($widths_cut);
                                    // echo $counters;
                                    // break;
                                }
                                // echo "<br>";
                                array_push($week_lesson, $lesson_data);
                                // break;
                            }
                            // var_dump($week_lesson[0][0]);
                            $pdf->SetFont('Helvetica', '', 6);
                            $pdf->timeTable_create($timetable_titles, $week_lesson, $widths_cut);
                            // break;
                        }
                        // teacher timetable
                        $pdf->Output();
                    } elseif ($what_tt == "block_timetable") {
                        // get the number of lessons 
                        $block_tt = json_decode($read);
                        $metadata = json_decode($read)->metadata;
                        $lesson_count = count($block_tt->timetables[0]->blocktimetable[0]->classes[0]->lessons);
                        $first_lesson = date("H:i", strtotime($first_lesson));
                        // echo $first_lesson;

                        // set the timetable titles
                        $timetable_titles = ["Day"];
                        $breaks = (strlen($breaks_lists) > 0) ? json_decode($breaks_lists)->breaks : [];
                        for ($i = 0; $i < $lesson_count; $i++) {
                            $start_time = $first_lesson;
                            // loop inside the breaks and add them in the process
                            $date = date_create($first_lesson);
                            date_add($date, date_interval_create_from_date_string($lesson_time . " minutes"));
                            $first_lesson = date_format($date, "H:i");
                            $end_time = $first_lesson;
                            array_push($timetable_titles, $start_time . " - " . $end_time);
                            for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                $location = $breaks[$i2]->after;
                                $duration = $breaks[$i2]->period;

                                if ($i + 1 == $location) {
                                    $start_time = $first_lesson;
                                    // loop inside the breaks and add them in the process
                                    $date = date_create($first_lesson);
                                    date_add($date, date_interval_create_from_date_string($duration . " minutes"));
                                    $first_lesson = date_format($date, "H:i");
                                    $end_time = $first_lesson;

                                    array_push($timetable_titles, $start_time . " - " . $end_time);
                                }
                            }
                        }

                        $blocktimetable = $block_tt->timetables[0]->blocktimetable;
                        // end of timetable titles
                        // var_dump($timetable_titles);

                        // $lessons = [];
                        // display metadata $pdf = new PDF('P', 'mm', 'A4');// create the pdf file
                        $pdf = new PDF('L', 'mm', 'A4');
                        $tittle = "BLOCK TIMETABLE.";
                        $pdf->SetFont('Times', '', 11);
                        $pdf->SetMargins(3,3);
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        // $pdf->AddPage();

                        for ($index = 0; $index < count($blocktimetable); $index++) {
                            $Day = $blocktimetable[$index]->Day;
                            // echo $Day."<br>";
                            $classes = $blocktimetable[$index]->classes;
                            $week_lesson = [];

                            // headers
                            $pdf->AddPage();
                            // echo $highest_len;
                            // Colors, line width and bold font
                            $pdf->SetFillColor(157, 183, 184);
                            // $pdf->SetTextColor(255);
                            $pdf->SetDrawColor(0, 0, 0);
                            $pdf->SetLineWidth(.1);
                            // $pdf->SetFont('','B');
                            // set metadata
                            $subjects = $metadata[0]->subjects;
                            $subjects2 = [];
                            $subs = [];
                            for ($i = 0; $i < count($subjects); $i++) {
                                if (chckPrsnt($subs, $subjects[$i]->subject_id) == 0) {
                                    array_push($subs, $subjects[$i]->subject_id);
                                    array_push($subjects2, $subjects[$i]);
                                }
                            }
                            $pdf->SetFont('Times', 'U', 10);
                            $pdf->Cell(40, 10, $Day, 0, 1, 'C', false);
                            $pdf->SetFont('Times', '', 9);
                            $subjects = $subjects2;
                            $teachers = $metadata[2]->teachers;
                            $pdf->Cell(10, 5, "#", 1, 0, "C", true);
                            $pdf->Cell(50, 5, "Lesson Name", 1, 0, "C", true);
                            $pdf->Cell(20, 5, "Short", 1, 0, "C", true);
                            $pdf->Cell(70, 0, "");
                            $pdf->Cell(10, 5, "#", 1, 0, "C", true);
                            $pdf->Cell(50, 5, "Teacher Name", 1, 0, "C", true);
                            $pdf->Cell(20, 5, "Short", 1, 1, "C", true);

                            // array_pop($subjects);
                            // create not for techers
                            $longest = count($subjects);
                            if (count($teachers) > $longest) {
                                $longest = count($teachers);
                            }
                            // echo $longest;
                            for ($index6 = 0; $index6 < $longest; $index6++) {
                                if (count($subjects) > $index6) {
                                    $pdf->Cell(10, 5, ($index6 + 1), 1, 0, "C", false);
                                    $pdf->Cell(50, 5, ucwords(strtolower($subjects[$index6]->subjectname)), 1, 0, "C", false);
                                    $pdf->Cell(20, 5, $subjects[$index6]->subject_id, 1, 0, "C", false);
                                } else {
                                    $pdf->Cell(10, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(50, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(20, 5, "", 0, 0, "C", false);
                                }
                                $pdf->Cell(70, 0, "");
                                if (count($teachers) > $index6) {
                                    $pdf->Cell(10, 5, ($index6 + 1), 1, 0, "C", false);
                                    $pdf->Cell(50, 5, ucwords(strtolower($teachers[$index6]->teachername)), 1, 0, "C", false);
                                    $pdf->Cell(20, 5, $teachers[$index6]->teacherid, 1, 1, "C", false);
                                } else {
                                    $pdf->Cell(10, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(50, 5, "", 0, 0, "C", false);
                                    $pdf->Cell(20, 5, "", 0, 1, "C", false);
                                }
                            }

                            // create the tables
                            // $x = $pdf->GetX();
                            // $y = $pdf->GetY();
                            // $pdf->MultiCell(25,5,"My name is HIllary I come from Kirintage My name is HIllary".$pdf->GetStringWidth(""),"LTRB","L",false);
                            // $pdf->SetXY($x + 25, $y);
                            // $pdf->MultiCell(25,5,"My name is HIllary I come from Kirintage                    ","LTRB","L",false);
                            // $pdf->Ln();
                            // $pdf->SetFillColor(205, 211, 218);
                            $pdf->SetFont('Times', 'U', 10);
                            $pdf->Cell(40, 10, "Block Timetable", 0, 1, 'C', false);
                            // $pdf->SetFont('Times', 'BU', 11);
                            $widths_cut = [];
                            for ($index2 = 0; $index2 < count($classes); $index2++) {
                                $lesson_data = [$classes[$index2]->classname];
                                $lessons_in = $classes[$index2]->lessons;
                                for ($index3 = 0; $index3 < count($lessons_in); $index3++) {
                                    array_push($lesson_data, $lessons_in[$index3]);
                                    for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                        $location = $breaks[$i2]->after;
                                        $duration = $breaks[$i2]->period;
                                        $brake_name = $breaks[$i2]->brake_name;
                                        if ($index3 + 1 == $location) {
                                            array_push($lesson_data, $brake_name);
                                        }
                                    }
                                }
                                $width = 326;
                                $widths_cut = [$width - 310];
                                for ($ind = 0; $ind < (count($lesson_data)); $ind++) {
                                    $counters = round(310 / (count($lesson_data))) - 1;
                                    array_push($widths_cut, $counters);
                                    // var_dump($widths_cut);
                                    // echo $counters;
                                    // break;
                                }
                                // echo "<br>";
                                array_push($week_lesson, $lesson_data);
                                // break;
                            }
                            // var_dump($week_lesson[0][0]);
                            $pdf->SetFont('Helvetica', '', 6);
                            $pdf->timeTable_create($timetable_titles, $week_lesson, $widths_cut);
                            // break;
                        }
                        $pdf->Output();
                    } elseif ($what_tt == "specific_tr_timetable") {
                        if (isset($_POST['specific_tr_tt'])) {
                            $specific_tr_tt = $_POST['specific_tr_tt'];
                            // get teacher name
                            // loop through the days of the week and set the data for the teacher
                            // get the number of lessons 
                            $block_tt = json_decode($read);
                            $metadata = json_decode($read)->metadata;
                            $lesson_count = count($block_tt->timetables[0]->blocktimetable[0]->classes[0]->lessons);
                            $first_lesson = date("H:i", strtotime($first_lesson));
                            // echo $first_lesson;

                            // set the timetable titles
                            $timetable_titles = ["Day"];
                            $breaks = (strlen($breaks_lists) > 0) ? json_decode($breaks_lists)->breaks : [];
                            for ($i = 0; $i < $lesson_count; $i++) {
                                $start_time = $first_lesson;
                                // loop inside the breaks and add them in the process
                                $date = date_create($first_lesson);
                                date_add($date, date_interval_create_from_date_string($lesson_time . " minutes"));
                                $first_lesson = date_format($date, "H:i");
                                $end_time = $first_lesson;
                                array_push($timetable_titles, $start_time . " - " . $end_time);
                                for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                    $location = $breaks[$i2]->after;
                                    $duration = $breaks[$i2]->period;

                                    if ($i + 1 == $location) {
                                        $start_time = $first_lesson;
                                        // loop inside the breaks and add them in the process
                                        $date = date_create($first_lesson);
                                        date_add($date, date_interval_create_from_date_string($duration . " minutes"));
                                        $first_lesson = date_format($date, "H:i");
                                        $end_time = $first_lesson;

                                        array_push($timetable_titles, $start_time . " - " . $end_time);
                                    }
                                }
                            }

                            // var_dump($timetable_titles);
                            $blocktimetable = $block_tt->timetables[0]->blocktimetable;
                            $me_subjects = $block_tt->metadata[0]->subjects;
                            // var_dump($metadata);
                            // end of timetable titles
                            // var_dump($timetable_titles);

                            // $lessons = [];
                            // display metadata $pdf = new PDF('P', 'mm', 'A4');// create the pdf file
                            // $pdf->SetFont('Times', '', 11);
                            $teachers = $metadata[2]->teachers;
                            $tr_full_name = "Null";
                            for ($inde = 0; $inde < count($teachers); $inde++) {
                                $teachername = $teachers[$inde]->teachername;
                                $teacherid = $teachers[$inde]->teacherid;
                                if (trim($teacherid) == trim($specific_tr_tt)) {
                                    $tr_full_name = $teachername;
                                }
                            }
                            $pdf = new PDF('L', 'mm', 'A4');
                            $tittle = "BLOCK TIMETABLE.";
                            $pdf->SetFont('Times', '', 11);
                            $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                            $pdf->set_document_title("Week Timetable for " . ucwords(strtolower($tr_full_name)));
                            $pdf->set_school_name($_SESSION['schname']);
                            $pdf->set_school_po($_SESSION['po_boxs']);
                            $pdf->set_school_box_code($_SESSION['box_codes']);
                            $pdf->set_school_contact($_SESSION['school_contact']);
                            $pdf->AddPage();
                            $pdf->SetFont('Times', 'U', 9);
                            $pdf->Cell(40, 10, "Week Timetable", 0, 1, 'C', false);

                            // get the days of the week
                            $days_of_week = [];
                            for ($index = 0; $index < count($blocktimetable); $index++) {
                                array_push($days_of_week, $blocktimetable[$index]->Day);
                            }
                            $teacher_tt_data = [];
                            $number_of_lessons = [];
                            for ($index1 = 0; $index1 < count($days_of_week); $index1++) {
                                $day_of_week = $days_of_week[$index1];
                                $day_data = [date("D", strtotime($day_of_week))];
                                // echo $day_of_week;
                                for ($index2 = 0; $index2 < count($blocktimetable); $index2++) {
                                    $Day = $blocktimetable[$index2]->Day;
                                    if ($Day == $day_of_week) {
                                        $classes = $blocktimetable[$index2]->classes;
                                        for ($index3 = 0; $index3 < $lesson_count; $index3++) {
                                            $class_contained = "";
                                            for ($index4 = 0; $index4 < count($classes); $index4++) {
                                                $class_name = $classes[$index4]->classname;
                                                $lessons = $classes[$index4]->lessons[$index3];
                                                if (str_contains($lessons, $specific_tr_tt)) {
                                                    array_push($day_data, $lessons . " " . $class_name);
                                                    $class_contained = $lessons;
                                                    array_push($number_of_lessons,explode(" ",$lessons)[0]);
                                                    // echo round($pdf->GetStringWidth($lessons." ".$class_name))." = ".$lessons." ".$class_name."<br>";
                                                    break;
                                                }
                                            }
                                            if ($class_contained == "") {
                                                array_push($day_data, " - ");
                                            }
                                            for ($i2 = 0; $i2 < count($breaks); $i2++) {
                                                $location = $breaks[$i2]->after;
                                                $duration = $breaks[$i2]->period;
                                                $brake_name = $breaks[$i2]->brake_name;
                                                if ($index3 + 1 == $location) {
                                                    array_push($day_data, $brake_name);
                                                }
                                            }
                                        }
                                    }
                                }
                                // echo "<br>";
                                array_push($teacher_tt_data, $day_data);
                            }
                            // echo json_encode($teacher_tt_data);
                            $width = 326;
                            $widths_cut = [$width - 310];
                            for ($ind = 0; $ind < (count($teacher_tt_data[0])); $ind++) {
                                $counters = round(310 / (count($teacher_tt_data[0]))) - 1;
                                array_push($widths_cut, $counters);
                                // var_dump($widths_cut);
                                // echo $counters;
                                // break;
                            }
                            for ($ind2 = 0; $ind2 < count($widths_cut); $ind2++) {
                                // echo $widths_cut[$ind2]." ";
                            }

                            // get the total number of lessons plus all the lessons the teacher teaches
                            $all_lessons = [];
                            for ($index=0; $index < count($number_of_lessons); $index++) { 
                                $all_lessons[$number_of_lessons[$index]] = 0;
                                // echo $number_of_lessons[$index]."<br>";
                            }
                            for ($index2=0; $index2 < count($number_of_lessons); $index2++) { 
                                $all_lessons[$number_of_lessons[$index2]]++;
                            }
                            // var_dump($week_lesson[0][0]);
                            $pdf->SetFont('Helvetica', '', 6);
                            $pdf->timeTable_create($timetable_titles, $teacher_tt_data, $widths_cut);

                            $pdf->Ln(10);
                            $pdf->SetFillColor(157, 183, 184);
                            $pdf->SetFont('Helvetica', 'B', 8);
                            $pdf->Cell(10,6,"No.",1,0,"L",true);
                            $pdf->Cell(40,6,"Lesson Name.",1,0,"L",true);
                            $pdf->Cell(20,6,"Lessons.",1,1,"L",true);
                            $index = 1;
                            $pdf->SetFillColor(157, 183, 184);
                            $pdf->SetFont('Helvetica', '', 8);
                            $indexes = 0;
                            foreach ($all_lessons as $key => $value) {
                                $pdf->Cell(10,6,$index,1,0,"L",false);
                                $subject_names = $key;
                                for ($index2=0; $index2 < count($me_subjects); $index2++) { 
                                    $subjectname = $me_subjects[$index2]->subjectname;
                                    $subject_id = $me_subjects[$index2]->subject_id;
                                    if ($subject_id == $key) {
                                        $subject_names = $subjectname;
                                        break;
                                    }
                                }
                                $pdf->Cell(40,6,$subject_names,1,0,"L",false);
                                $pdf->Cell(20,6,$value." Lesson(s)",1,1,"L",false);
                                $indexes+=$value;
                                $index++;
                            }
                            $pdf->SetFont('Helvetica', 'B', 8);
                            $pdf->Cell(10,6,"",0,0,"L",false);
                            $pdf->Cell(40,6,"Total.",1,0,"L",false);
                            $pdf->Cell(20,6,$indexes." Lessons",1,1,"L",false);
                            $pdf->Output();
                        } else {
                            echo "<p class='text-danger'>Select a teacher you want to print their timetable before proceeding</p>";
                        }
                    }
                }
            }
        }
    } elseif (isset($_POST['classes_for_exams']) && isset($_POST['classes_for_exams'])) {
        $classes_for_exams = $_POST['classes_for_exams'];
        $what_to_print = $_POST['what_to_print'];
        $exam_ids_printing = $_POST['exam_ids_printing'];
        if ($what_to_print == "exams_filling_slip") {
            include("../connections/conn2.php");
            include("../connections/conn1.php");
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '" . $exam_ids_printing . "'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $exams_names = $row['exams_name'];
                    $students_sitting = strlen($row['students_sitting']) > 0 ? json_decode($row['students_sitting']) : [];
                    $subjects_examined = strlen($row['subject_done']) > 0 ? explode(",", substr($row['subject_done'], 1, (strlen($row['subject_done']) - 2))) : [];
                    // get the subjects taught in the selected class.
                    $subjects_present = [];
                    for ($ind = 0; $ind < count($subjects_examined); $ind++) {
                        $present = isSubjectTaught($conn2, $subjects_examined[$ind], $classes_for_exams);
                        if ($present == 1) {
                            array_push($subjects_present, $subjects_examined[$ind]);
                        }
                    }
                    // get the class that is needed
                    $class_lists = [];
                    for ($index = 0; $index < count($students_sitting); $index++) {
                        $curr_class = $students_sitting[$index]->classname;
                        if ($curr_class == $classes_for_exams) {
                            $class_lists = $students_sitting[$index]->classlist;
                            break;
                        }
                    }
                    $student_data = [];
                    $admno = [];
                    // go through all students and get their information
                    for ($index = 0; $index < count($class_lists); $index++) {
                        $select = "SELECT * FROM `student_data` WHERE `adm_no` = '" . $class_lists[$index] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $student_names = $row['first_name'] . " " . $row['second_name'];
                                $adm = $row['adm_no'];
                                array_push($student_data, $student_names);
                                array_push($admno, $adm);
                            }
                        }
                    }
                    // take all students in that class
                    // $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$classes_for_exams."'";
                    // $stmt = $conn2->prepare($select);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                    // if ($result) {
                    //     while($row = $result->fetch_assoc()){
                    //         $student_names = $row['first_name']." ".$row['second_name'] ;
                    //         $adm = $row['adm_no'];
                    //         array_push($student_data,$student_names);
                    //         array_push($admno,$adm);
                    //     }
                    // }

                    if (count($admno) > 0) {
                        // take the data and get the
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        $tittle = "" . ucwords(strtolower($exams_names)) . " " . className_exam($classes_for_exams) . " Score Sheet.";
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        $original = 170;
                        $no = 8;
                        $names = 35;
                        $admnos = 20;
                        $original = $original - ($names + $no);
                        $widths = round($original / (count($subjects_present) + 1));
                        $width = [$no, $names, $admnos];
                        $subjects = [];
                        for ($index = 0; $index < count($subjects_present); $index++) {
                            array_push($width, $widths);
                            $sub_name = subjectName($conn2, $subjects_present[$index]);
                            array_push($subjects, $sub_name);
                        }
                        array_push($width, $widths);
                        $header = ["No", "Student Name", "Adm No"];
                        $header = array_merge($header, $subjects, ["Total"]);
                        // get the subjects data
                        $all_data = [];
                        for ($index = 0; $index < count($student_data); $index++) {
                            $in_data = [$index + 1, ucwords(strtolower($student_data[$index])), $admno[$index]];
                            for ($index2 = 0; $index2 < count($subjects); $index2++) {
                                array_push($in_data, "");
                            }
                            array_push($in_data, "");
                            array_push($all_data, $in_data);
                        }
                        $pdf->SetFont("Helvetica", "", 7);
                        $pdf->setConn($conn2);
                        $pdf->exams_results($header, $all_data, $width);

                        $pdf->Output();
                    } else {
                        echo "<p style='color:red;'><b>Note: </b><br>No student were available in that class during this exams occurence!</p>";
                    }
                }
            }
        } elseif ($what_to_print == "exams_marks") {
            include("../connections/conn2.php");
            include("../connections/conn1.php");
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '" . $exam_ids_printing . "'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $exams_names = $row['exams_name'];
                    $students_sitting = strlen($row['students_sitting']) > 0 ? json_decode($row['students_sitting']) : [];
                    $subjects_examined = strlen($row['subject_done']) > 0 ? explode(",", substr($row['subject_done'], 1, (strlen($row['subject_done']) - 2))) : [];
                    // get the subjects taught in the selected class.
                    $subjects_present = [];
                    for ($ind = 0; $ind < count($subjects_examined); $ind++) {
                        $present = isSubjectTaught($conn2, $subjects_examined[$ind], $classes_for_exams);
                        if ($present == 1) {
                            array_push($subjects_present, $subjects_examined[$ind]);
                        }
                    }
                    // get the class that is needed
                    $class_lists = [];
                    for ($index = 0; $index < count($students_sitting); $index++) {
                        $curr_class = $students_sitting[$index]->classname;
                        if ($curr_class == $classes_for_exams) {
                            $class_lists = $students_sitting[$index]->classlist;
                            break;
                        }
                    }
                    // take all students in that class
                    // $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$classes_for_exams."'";
                    // $stmt = $conn2->prepare($select);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                    // $student_data = [];
                    // $admno = [];
                    // if ($result) {
                    //     while($row = $result->fetch_assoc()){
                    //         $student_names = $row['first_name']." ".$row['second_name'] ;
                    //         $adm = $row['adm_no'];
                    //         array_push($student_data,$student_names);
                    //         array_push($admno,$adm);
                    //     }
                    // }
                    $student_data = [];
                    $admno = [];
                    // go through all students and get their information
                    for ($index = 0; $index < count($class_lists); $index++) {
                        $select = "SELECT * FROM `student_data` WHERE `adm_no` = '" . $class_lists[$index] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $student_names = $row['first_name'] . " " . $row['second_name'];
                                $adm = $row['adm_no'];
                                array_push($student_data, $student_names);
                                array_push($admno, $adm);
                            }
                        }
                    }
                    if (count($admno) > 0) {
                        // take the data and get the
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        $tittle = "" . ucwords(strtolower($exams_names)) . " " . className_exam($classes_for_exams) . " Results.";
                        $pdf->set_document_title($tittle);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $pdf->AddPage();
                        $original = 170;
                        $no = 8;
                        $names = 35;
                        $admnos = 20;
                        $original = $original - ($names + $no);
                        $widths = round($original / (count($subjects_present) + 1));
                        $width = [$no, $names, $admnos];
                        $subjects = [];
                        for ($index = 0; $index < count($subjects_present); $index++) {
                            array_push($width, $widths);
                            $sub_name = subjectName($conn2, $subjects_present[$index]);
                            array_push($subjects, $sub_name);
                        }
                        array_push($width, $widths);
                        $header = ["Pos", "Student Name", "Adm No"];
                        $header = array_merge($header, $subjects, ["Total"]);
                        // get the subjects data
                        $all_data = [];
                        $totals = [];
                        for ($index = 0; $index < count($student_data); $index++) {
                            $in_data = [ucwords(strtolower($student_data[$index])), $admno[$index]];
                            $total = 0;
                            for ($index2 = 0; $index2 < count($subjects); $index2++) {
                                $marks = marksNGrade($exam_ids_printing, $subjects_present[$index2], $admno[$index], $conn2);
                                // $marks = count($marks) >  ? $marks:["",""];
                                array_push($in_data, (strlen($marks[0]) > 0 ? $marks[0] : "-") . " " . $marks[1]);
                                $total += strlen($marks[0]) > 0 ? $marks[0] : 0;
                            }
                            array_push($totals, $total);
                            array_push($in_data, $total);
                            array_push($all_data, $in_data);
                        }
                        // sort the total arrays 
                        rsort($totals);
                        // assign each total to the correct array
                        $arrayed = [];
                        $position = 1;
                        $prev = 0;
                        $counter = 1;
                        for ($index = 0; $index < count($totals); $index++) {
                            for ($index2 = 0; $index2 < count($all_data); $index2++) {
                                // echo $all_data[$index2][(count($all_data[$index2])-1)]." == ".$totals[$index]."<br>";
                                if ($all_data[$index2][(count($all_data[$index2]) - 1)] == $totals[$index]) {
                                    // if ($all_data[$index2][(count($all_data[$index2])-1)] == 0) {
                                    //     break;
                                    // }
                                    if ($prev == $totals[$index]) {
                                        $position = $position;
                                    } else {
                                        $position = $counter;
                                    }
                                    $my_data = [$position];

                                    $present = chckPrsnt($arrayed, array_merge($my_data, $all_data[$index2]));
                                    if ($present == 1) {
                                        continue;
                                    }
                                    array_push($arrayed, array_merge($my_data, $all_data[$index2]));
                                    $prev = $totals[$index];
                                    break;
                                }
                            }
                            $counter++;
                        }
                        $pdf->SetFont("Helvetica", "", 7);
                        $pdf->setConn($conn2);
                        $pdf->exams_results($header, $arrayed, $width);
                        $pdf->Ln(10);
                        $pdf->SetFont('Helvetica', 'U', 10);
                        $pdf->Cell(30, 5, "Class Teacher Remarks", 0, 1, 'L', false);
                        $pdf->Cell(190, 5, "", 'B', 1, 'C', false);
                        $pdf->Cell(190, 5, "", 'B', 1, 'C', false);
                        $pdf->Cell(100, 5, "", 'B', 1, 'C', false);
                        // CLASS TEACHER SIGNATURE
                        $pdf->Ln(10);
                        $pdf->Cell(30, 5, "Class Teacher Signature", 0, 1, 'L', false);
                        $pdf->Cell(60, 10, "", 'B', 1, 'C', false);
                        $pdf->Ln(10);
                        $pdf->Cell(50, 5, "Head Teacher / Principal Signature", 0, 1, 'L', false);
                        $pdf->Cell(60, 10, "", 'B', 1, 'C', false);
                        if (count($arrayed) > 0) {
                            $pdf->Output();
                        } else {
                            echo "<p style='color:red;'>No student has their marks recorded yet!</p>";
                        }
                    } else {
                        echo "<p style='color:red;'><b>Note: </b><br>No student were available in that class during this exams occurence!</p>";
                    }
                }
            }
        } elseif ($what_to_print == "student_report_card") {
            include("../connections/conn2.php");
            include("../connections/conn1.php");
            $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '" . $exam_ids_printing . "'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $exams_names = $row['exams_name'];
                    $students_sitting = strlen($row['students_sitting']) > 0 ? json_decode($row['students_sitting']) : [];
                    $subjects_examined = strlen($row['subject_done']) > 0 ? explode(",", substr($row['subject_done'], 1, (strlen($row['subject_done']) - 2))) : [];
                    // get the subjects taught in the selected class.
                    $subjects_present = [];
                    for ($ind = 0; $ind < count($subjects_examined); $ind++) {
                        $present = isSubjectTaught($conn2, $subjects_examined[$ind], $classes_for_exams);
                        if ($present == 1) {
                            array_push($subjects_present, $subjects_examined[$ind]);
                        }
                    }
                    // get the class that is needed
                    $class_lists = [];
                    for ($index = 0; $index < count($students_sitting); $index++) {
                        $curr_class = $students_sitting[$index]->classname;
                        if ($curr_class == $classes_for_exams) {
                            $class_lists = $students_sitting[$index]->classlist;
                            break;
                        }
                    }
                    $student_data = [];
                    $admno = [];
                    $our_students = [];
                    // go through all students and get their information
                    for ($index = 0; $index < count($class_lists); $index++) {
                        $select = "SELECT * FROM `student_data` WHERE `adm_no` = '" . $class_lists[$index] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $student_names = $row['first_name'] . " " . $row['second_name'];
                                $adm = $row['adm_no'];
                                array_push($student_data, $student_names);
                                array_push($admno, $adm);
                                array_push($our_students, $row);
                            }
                        }
                    }
                    // take all students in that class
                    // $select = "SELECT * FROM `student_data` WHERE `stud_class` = '".$classes_for_exams."'";
                    // $stmt = $conn2->prepare($select);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                    // $student_data = [];
                    // $admno = [];
                    // $our_students = [];
                    // if ($result) {
                    //     while($row = $result->fetch_assoc()){
                    //         $student_names = $row['first_name']." ".$row['second_name'] ;
                    //         $adm = $row['adm_no'];
                    //         array_push($student_data,$student_names);
                    //         array_push($admno,$adm);
                    //         array_push($our_students,$row);
                    //     }
                    // }
                    // take the data and get the
                    if (count($our_students) > 0) {
                        $pdf = new PDF('P', 'mm', 'A4');
                        $pdf->setHeaderPos(200);
                        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                        $pdf->set_school_name($_SESSION['schname']);
                        $pdf->set_school_po($_SESSION['po_boxs']);
                        $pdf->set_school_box_code($_SESSION['box_codes']);
                        $pdf->set_school_contact($_SESSION['school_contact']);
                        $our_staff = getStaffData($conn);
                        // $pdf->AddPage();
                        // check all students
                        for ($index = 0; $index < count($our_students); $index++) {
                            $tittle = "Result Slip";
                            $pdf->set_document_title($tittle);
                            $pdf->AddPage();
                            // getting student report cards
                            $admission = $our_students[$index]['adm_no'];
                            $full_name = $our_students[$index]['surname'] . " " . $our_students[$index]['first_name'] . " " . $our_students[$index]['second_name'];
                            $gender = $our_students[$index]['gender'];
                            $student_class = className_exam($our_students[$index]['stud_class']);
                            $student_gender = $our_students[$index]['gender'];
                            $student_position = $our_students[$index]['gender'];
                            $student_classteacher = classteacher($conn, $conn2, $our_students[$index]['stud_class']);
                            try {
                                if ($gender == "Male") {
                                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/assets/img/male.jpg", 120, 50, 20);
                                } else {
                                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/assets/img/female.png", 120, 50, 20);
                                }
                                // try setting that image if not set the image
                            } catch (Exception $e) {
                                // echo $e->getMessage();
                                if ($gender == "Male") {
                                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/assets/img/male.jpg", 120, 50, 20);
                                } else {
                                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/assets/img/female.png", 120, 50, 20);
                                }
                            }
                            $pdf->SetFont('Helvetica', '', 9);
                            $pdf->Cell(30, 5, "Name: ", 0, 0, 'L', false);
                            $pdf->Cell(60, 5, ucwords(strtolower($full_name)), 'R', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Cell(30, 5, "Reg No.: ", 0, 0, 'L', false);
                            $pdf->Cell(60, 5, $admission, 'R', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Cell(30, 5, "Class: ", 0, 0, 'L', false);
                            $pdf->Cell(60, 5, $student_class, 'R', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Cell(30, 5, "Exam Name: ", 0, 0, 'L', false);
                            $pdf->Cell(60, 5, ucwords(strtolower($exams_names)), 'R', 0, 'L', false);
                            $pdf->Ln();
                            // $pdf->Cell(30, 5, "Position: ", 0, 0, 'L', false);
                            // $pdf->Cell(60, 5, "7", 'R', 0, 'L', false);
                            // $pdf->Ln();
                            $pdf->Cell(30, 5, "Class Teacher: ", 0, 0, 'L', false);
                            $pdf->Cell(60, 5, ucwords(strtolower($student_classteacher)), 'R', 0, 'L', false);
                            $pdf->Ln();
                            $pdf->Ln(5);
                            $pdf->SetFont('Helvetica', 'U', 12);
                            $pdf->Cell(190, 5, "Subject Scores", 0, 0, 'C', false);
                            $pdf->Ln(10);
                            $pdf->SetFont('Times', 'B', 10);
                            // Colors, line width and bold font
                            $pdf->SetFillColor(157, 183, 184);
                            // $pdf->SetTextColor(255);
                            $pdf->SetDrawColor(0, 0, 0);
                            $pdf->SetLineWidth(.1);
                            $pdf->Cell(10, 7, "No.", 1, 0, 'C', true);
                            $pdf->Cell(45, 7, "Subject Name", 1, 0, 'C', true);
                            $pdf->Cell(45, 7, "Subject Scores", 1, 0, 'C', true);
                            $pdf->Cell(45, 7, "Subject Grade", 1, 0, 'C', true);
                            $pdf->Cell(45, 7, "Teacher Teaching", 1, 0, 'C', true);
                            $pdf->Ln();
                            // get the subject marks for the student
                            $fill = false;
                            $pdf->SetFillColor(205, 211, 218);
                            $pdf->SetTextColor(0);
                            $pdf->SetFont('Helvetica', '', 8);
                            for ($index2 = 0; $index2 < count($subjects_present); $index2++) {
                                $sub_dets = subjectsDetails($conn2, $subjects_present[$index2], $our_staff);
                                $scores = exam_grade($conn2, $exam_ids_printing, $subjects_present[$index2]);
                                $pdf->Cell(10, 7, ($index2 + 1), 1, 0, 'C', $fill);
                                $pdf->Cell(45, 7, $sub_dets[1], 1, 0, 'L', $fill);
                                $pdf->Cell(45, 7, $scores[0], 1, 0, 'L', $fill);
                                $pdf->Cell(45, 7, $scores[1], 1, 0, 'L', $fill);
                                $pdf->Cell(45, 7, $sub_dets[0], 1, 0, 'L', $fill);
                                $pdf->Ln();
                                $fill = !$fill;
                            }
                            // 
                            $pdf->Ln(10);
                            $pdf->SetFont('Helvetica', 'U', 10);
                            $pdf->Cell(30, 5, "Class Teacher Remarks", 0, 1, 'L', false);
                            $pdf->Cell(190, 5, "", 'B', 1, 'C', false);
                            $pdf->Cell(190, 5, "", 'B', 1, 'C', false);
                            $pdf->Cell(100, 5, "", 'B', 1, 'C', false);
                            // CLASS TEACHER SIGNATURE
                            $pdf->Ln(5);
                            $pdf->Cell(30, 5, "Class Teacher Signature", 0, 1, 'L', false);
                            $pdf->Cell(60, 10, "", 'B', 1, 'C', false);
                            $pdf->Ln(5);
                            $pdf->Cell(50, 5, "Head Teacher / Principal Signature", 0, 1, 'L', false);
                            $pdf->Cell(60, 10, "", 'B', 1, 'C', false);
                            $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/reports2.png", 1, 1, 209);
                            // break;
                        }
                        $pdf->Output();
                    } else {
                        echo "<p style='color:red;'><b>Note: </b><br>No student were available in that class during this exams occurence!</p>";
                    }
                }
            }
        }
    } elseif (isset($_POST['print_or_send_invoice_btn'])) {
        if (strlen(trim($_POST['students_ids'])) > 0) {
            // start by first the print email options
            if ($_POST['email_selections'] == "print_invoices") {
                // print invoices
                // only need the student list
                $students_ids = $_POST['students_ids'];
                // BREAK THE STUDENT IDS INTO ARRAYS
                $stud_ids = strlen(trim($students_ids)) > 0 ? explode(",", $students_ids) : [];

                // include the connection for the main DB
                include("../connections/conn1.php");
                include("../connections/conn2.php");
                include_once("../ajax/finance/financial.php");
                include("fees_reminder.php");

                $pdf = new PDF2('P', 'mm', 'A4');

                $tittle = "Invoice";
                $pdf->SetFont('Times', '', 11);
                $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                $pdf->SetTitle($tittle);
                $pdf->set_school_name($_SESSION['schname']);
                $pdf->set_school_po($_SESSION['po_boxs']);
                $pdf->set_school_box_code($_SESSION['box_codes']);
                $pdf->set_school_contact($_SESSION['school_contact']);


                // loop through the student ids and get their details while you create the pdf
                $term = getTermV2($conn2);
                for ($index = 0; $index < count($stud_ids); $index++) {
                    $student_data = students_details($stud_ids[$index],$conn2);

                    // get the date of registration is in what term
                    $term_admitted = "TERM_1";

                    // get the students current term enrolled
                    $current_term_enrolled = null;
                    $course_progress = isJson_report($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
                    for($i = 0; $i < count($course_progress); $i++){
                        if($course_progress[$i]->course_status == 1){
                            $module_terms = $course_progress[$i]->module_terms;
                            for($in = 0; $in < count($module_terms); $in++){
                                if($module_terms[$in]->status == 1){
                                    $current_term_enrolled = $module_terms[$in]->term_name;
                                    break;
                                }
                            }
                        }
                    }

                    $invoice_number = date("YmdHis") . "#" . $stud_ids[$index];
                    $student_name = $student_data['surname'] . " " . $student_data['first_name'] . " " . $student_data['second_name'];
                    $admission_number = $stud_ids[$index];
                    $student_class = className_exam($student_data['stud_class']);

                    // get all fees to be paid in that particular class for that particular term
                    $select = "SELECT * FROM `fees_structure` WHERE `classes` = '" . $student_data['stud_class'] . "' AND `roles` = 'regular'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // balance report
                    $bal_repo = getBalanceReports($admission_number, "TERM_1", $conn2);
                    // echo $bal_repo;

                    // get the term fees
                    $arrays = [];
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $skip = 0;
                            $total = 0;

                            if($current_term_enrolled == "TERM_1"){
                                $total += $row['TERM_1'];
                            }
                            if($current_term_enrolled == "TERM_2"){
                                $total += $row['TERM_2'];
                            }
                            if($current_term_enrolled == "TERM_3"){
                                $total += $row['TERM_3'];
                            }

                            $price_n_name = array($row['expenses'] => $total);
                            if($skip == 0){
                                array_push($arrays, $price_n_name);
                            }
                        }
                    }
                    $feespaidbystud = getFeespaidByStudent($admission_number,$conn2);
                    
                    // get the paid fees paid untill that time
                    // get the balance
                    // get the student balance
                    // check if they have last year academic balance this means that the new fees structure will be added to their fees invoice description
                    $last_acad_yr = lastACADyrBal($admission_number, $conn2);
                    if ($last_acad_yr > 0) {
                        // add the last year academic balance to the array list
                        $acad_balance = array("Last Active Term Balance" => $last_acad_yr);
                        array_push($arrays, $acad_balance);
                        // first get the balance and know what was paid for
                        $balanced = getBalanceReports($admission_number, $term, $conn2);
                        $student_balance = $balanced;

                        // echo "New balance : <br>";
                        // echo json_encode($arrays)."<br><br>";

                        // ouput your pdf file here 
                        $pdf->AddPage();
                        $pdf->setHeaderPos(190);
                        $pdf->Image(dirname(__FILE__) . "../../" . schoolLogo($conn), 170, 3, 30);
                        $pdf->SetFont("Arial", "", 40);
                        $pdf->Cell(100, 30, "INVOICE", 0, 1, "L");
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "INVOICE NUMBER");
                        $pdf->Cell(50, 5, "DATE OF ISSUE ", 0, 1);
                        $pdf->SetFont("Arial", "", 8);
                        $pdf->Cell(50, 5, date("YmdHi") . "#" . $admission_number);
                        $pdf->Cell(50, 5, date("D dS M Y"), 0, 1);

                        // BILL FOR
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "BILLED TO:", 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, trim(ucwords(strtolower($pdf->school_name))), 0, 1);

                        // student details
                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, $student_name, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "P.0 Box " . $pdf->school_po . " - " . $pdf->school_BOX_CODE, 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Reg No. : " . $admission_number, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Contact us : " . $pdf->school_contact . "", 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Course Level : " . $student_class, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Email us : " . $_SESSION['school_mail'] . "", 0, 1);

                        // here we have the description of all the VOTEHEADS the student is supposed to pay.
                        $pdf->Ln(20);
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->SetTextColor(120, 120, 120);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Cell(10, 5, "#", "B", 0);
                        $pdf->Cell(120, 5, "Description", "B", 0);
                        $pdf->Cell(30, 5, "Fee", "B", 0);
                        $pdf->Cell(30, 5, "Amount", "B", 1);

                        // change the text color
                        $pdf->SetFont("Helvetica", "", 9);
                        $pdf->SetTextColor(10, 10, 10);
                        $pdf->SetLineWidth(0.1);
                        // here we set the payments to be paid
                        $total_fees_to_pay = 0;
                        $counters = 1;
                        foreach ($arrays as $key => $value) {
                            foreach ($value as $key2 => $value2) {
                                // row 1
                                $pdf->Cell(10, 8, "" . $counters . ". ", "B", 0);
                                $pdf->Cell(120, 8, ucwords(strtolower($key2)), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 1);
                                $total_fees_to_pay += ($value2 * 1);
                            }
                            $counters++;
                        }

                        // total
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Total Balance ", "B", 0);
                        // $pdf->Cell(30, 8, "Kes " . number_format($total_fees_to_pay), "B", 1);
                        $pdf->Cell(30, 8, "Kes " . number_format($balanced), "B", 1);

                        // payment information
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 7, "Payment Information: ", 0, 0);
                        $pdf->SetFont("Arial", "BI", 9);
                        $pdf->MultiCell(130, 7, $_POST['invoice_email_message'], 0, "J");


                        // footer
                        // Position at 1.5 cm from bottom
                        $pdf->SetY(260);
                        // Arial italic 8
                        $pdf->SetFont('Arial', 'I', 8);
                        $pdf->Cell(190, 0, "", "T", 1);
                        $pdf->Cell(190, 7, "----- Fees Once Paid are not refundable OR transferable -------", 0, 1, 'C');
                        $pdf->Cell(190, 7, "Page No " . $pdf->PageNo(), 0, 1, 'C');
                    } else {
                        // echo json_encode($arrays);
                        // echo json_encode($arrays);
                        $balanced = getBalanceReports($admission_number, $term, $conn2);
                        $student_balance = $balanced;

                        // echo "New balance : <br>";
                        // echo json_encode($arrays)."<br><br>";

                        // ouput your pdf file here 
                        $pdf->AddPage();
                        $pdf->setHeaderPos(190);
                        $pdf->Image(dirname(__FILE__) . "../../" . schoolLogo($conn), 170, 3, 30);
                        $pdf->SetFont("Arial", "", 40);
                        $pdf->Cell(100, 30, "INVOICE", 0, 1, "L");
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "INVOICE NUMBER");
                        $pdf->Cell(50, 5, "DATE OF ISSUE", 0, 1);
                        $pdf->SetFont("Arial", "", 8);
                        $pdf->Cell(50, 5, date("YmdHi") . "#" . $admission_number);
                        $pdf->Cell(50, 5, date("D dS M Y"), 0, 1);

                        // BILL FOR
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "BILLED TO:", 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, trim(ucwords(strtolower($pdf->school_name))), 0, 1);

                        // student details
                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, $student_name, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "P.0 Box " . $pdf->school_po . " - " . $pdf->school_BOX_CODE, 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Reg No. : " . $admission_number, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Contact us : " . $pdf->school_contact . "", 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Course Level : " . $student_class, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Email us : " . $_SESSION['school_mail'] . "", 0, 1);

                        // here we have the description of all the VOTEHEADS the student is supposed to pay.
                        $pdf->Ln(20);
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->SetTextColor(120, 120, 120);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Cell(10, 5, "#", "B", 0);
                        $pdf->Cell(120, 5, "Description", "B", 0);
                        $pdf->Cell(30, 5, "Fee", "B", 0);
                        $pdf->Cell(30, 5, "Amount", "B", 1);
                        
                        // change the text color
                        $pdf->SetFont("Helvetica", "", 9);
                        $pdf->SetTextColor(10, 10, 10);
                        $pdf->SetLineWidth(0.1);
                        // here we set the payments to be paid
                        $total_fees_to_pay = 0;
                        $counted = 1;
                        foreach ($arrays as $key => $value) {
                            foreach ($value as $key2 => $value2) {
                                // row 1
                                $pdf->Cell(10, 8, "" . $counted . ". ", "B", 0);
                                $pdf->Cell(120, 8, ucwords(strtolower($key2)), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 1);
                                $total_fees_to_pay += ($value2 * 1);
                            }
                            $counted++;
                        }

                        // add another row of amount paid
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Fees Paid ", "B", 0);
                        // $pdf->Cell(30, 8, "Kes " . number_format($total_fees_to_pay), "B", 1);
                        $pdf->Cell(30, 8, "Kes " . number_format($feespaidbystud), "B", 1);

                        // total
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Total Balance ", "B", 0);
                        // $pdf->Cell(30, 8, "Kes " . number_format($total_fees_to_pay), "B", 1);
                        $pdf->Cell(30, 8, "Kes " . number_format($balanced), "B", 1);

                        // payment information
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 7, "Payment Information: ", 0, 0);
                        $pdf->SetFont("Arial", "BI", 9);
                        $pdf->MultiCell(130, 7, $_POST['invoice_email_message'], 0, "J");


                        // footer
                        // Position at 1.5 cm from bottom
                        $pdf->SetY(250);
                        // Arial italic 8
                        $pdf->SetFont('Arial', 'I', 10);
                        $pdf->Cell(190, 0, "", "T", 1);
                        $pdf->Cell(190, 7, "----- Fees Once Paid are not refundable OR transferable -------", 0, 1, 'C');
                        $pdf->SetFont('Arial', 'I', 5);
                        $pdf->Cell(190, 7, "----- Fees balance on each votehead is calculated by the percentage of the total balance-------", 0, 1, 'C');
                        $pdf->SetFont('Arial', 'I', 10);
                        $pdf->Cell(190, 7, "Page No " . $pdf->PageNo(), 0, 1, 'C');
                    }
                    // break;
                }
                $pdf->set_document_title("Invoices");
                $pdf->Output("I", "invoices.pdf");
            } elseif ($_POST['email_selections'] == "send_email_invoices") {
                // first create the directory for the school and show invoices
                // check if the directory for the institution is created
                if (!folder_exist("../invoices/" . $_SESSION['dbname'] . "")) {
                    // create the folder with all permissions
                    // echo "Created!";
                    mkdir("../invoices/" . $_SESSION['dbname'] . "");
                    chmod("../invoices/" . $_SESSION['dbname'] . "", 0777);
                }
                // create the directory time and date
                $dated = date("YmdHi");
                if (!folder_exist("../invoices/" . $_SESSION['dbname'] . "/" . $dated . "")) {
                    // create the folder with all permissions
                    // echo "Created!";
                    mkdir("../invoices/" . $_SESSION['dbname'] . "/" . $dated . "");
                    chmod("../invoices/" . $_SESSION['dbname'] . "/" . $dated . "", 0777);
                }
                // there the files will be added

                include("../connections/conn1.php");
                include("../connections/conn2.php");
                include_once("../ajax/finance/financial.php");
                include("fees_reminder.php");

                // print invoices
                // only need the student list
                $students_ids = $_POST['students_ids'];
                // BREAK THE STUDENT IDS INTO ARRAYS
                $stud_ids = strlen(trim($students_ids)) > 0 ? explode(",", $students_ids) : [];

                $term = getTermV2($conn2);

                for ($index = 0; $index < count($stud_ids); $index++) {
                    // create the invoices to be attached in the emails// print invoices
                    $pdf = new PDF2('P', 'mm', 'A4');

                    $tittle = "Invoice";
                    $pdf->SetFont('Times', '', 11);
                    $pdf->SetTitle($tittle);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->set_school_name($_SESSION['schname']);
                    $pdf->set_school_po($_SESSION['po_boxs']);
                    $pdf->set_school_box_code($_SESSION['box_codes']);
                    $pdf->set_school_contact($_SESSION['school_contact']);



                    // loop through the student ids and get their details while you create the pdf
                    $term = getTermV2($conn2);
                    $student_data = students_details($stud_ids[$index],$conn2);

                    // get the date of registration is in what term
                    $term_admitted = "TERM_1";

                    // get the students current term enrolled
                    $current_term_enrolled = null;
                    $course_progress = isJson_report($student_data['my_course_list']) ? json_decode($student_data['my_course_list']) : [];
                    for($i = 0; $i < count($course_progress); $i++){
                        if($course_progress[$i]->course_status == 1){
                            $module_terms = $course_progress[$i]->module_terms;
                            for($in = 0; $in < count($module_terms); $in++){
                                if($module_terms[$in]->status == 1){
                                    $current_term_enrolled = $module_terms[$in]->term_name;
                                    break;
                                }
                            }
                        }
                    }

                    $invoice_number = date("YmdHis") . "#" . $stud_ids[$index];
                    $student_name = $student_data['surname'] . " " . $student_data['first_name'] . " " . $student_data['second_name'];
                    $admission_number = $stud_ids[$index];
                    $student_class = className_exam($student_data['stud_class']);

                    // get all fees to be paid in that particular class for that particular term
                    $select = "SELECT * FROM `fees_structure` WHERE `classes` = '" . $student_data['stud_class'] . "' AND `roles` = 'regular'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // balance report
                    $bal_repo = getBalanceReports($admission_number, "TERM_1", $conn2);
                    // echo $bal_repo;

                    // get the term fees
                    $arrays = [];
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $skip = 0;
                            $total = 0;

                            if($current_term_enrolled == "TERM_1"){
                                $total += $row['TERM_1'];
                            }
                            if($current_term_enrolled == "TERM_2"){
                                $total += $row['TERM_2'];
                            }
                            if($current_term_enrolled == "TERM_3"){
                                $total += $row['TERM_3'];
                            }

                            $price_n_name = array($row['expenses'] => $total);
                            if($skip == 0){
                                array_push($arrays, $price_n_name);
                            }
                        }
                    }
                    $feespaidbystud = getFeespaidByStudent($admission_number,$conn2);

                    // get the student balance
                    // check if they have last year academic balance this means that the new fees structure will be added to their fees invoice description
                    $last_acad_yr = lastACADyrBal($admission_number, $conn2);
                    if ($last_acad_yr > 0) {
                        // add the last year academic balance to the array list
                        $acad_balance = array("Last Active Term Balance" => $last_acad_yr);
                        array_push($arrays, $acad_balance);
                        // first get the balance and know what was paid for
                        $balanced = getBalanceReports($admission_number, $term, $conn2);
                        $student_balance = $balanced;

                        // echo "New balance : <br>";
                        // echo json_encode($arrays)."<br><br>";

                        // ouput your pdf file here 
                        $pdf->AddPage();
                        $pdf->setHeaderPos(190);
                        $pdf->Image(dirname(__FILE__) . "../../" . schoolLogo($conn), 170, 3, 30);
                        $pdf->SetFont("Arial", "", 40);
                        $pdf->Cell(100, 30, "INVOICE", 0, 1, "L");
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "INVOICE NUMBER");
                        $pdf->Cell(50, 5, "DATE OF ISSUE ", 0, 1);
                        $pdf->SetFont("Arial", "", 8);
                        $pdf->Cell(50, 5, date("YmdHi") . "#" . $admission_number);
                        $pdf->Cell(50, 5, date("D dS M Y"), 0, 1);

                        // BILL FOR
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "BILLED TO:", 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, trim(ucwords(strtolower($pdf->school_name))), 0, 1);

                        // student details
                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, $student_name, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "P.0 Box " . $pdf->school_po . " - " . $pdf->school_BOX_CODE, 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Reg No. : " . $admission_number, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Contact us : " . $pdf->school_contact . "", 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Course Level : " . $student_class, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Email us : " . $_SESSION['school_mail'] . "", 0, 1);

                        // here we have the description of all the VOTEHEADS the student is supposed to pay.
                        $pdf->Ln(20);
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->SetTextColor(120, 120, 120);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Cell(10, 5, "#", "B", 0);
                        $pdf->Cell(120, 5, "Description", "B", 0);
                        $pdf->Cell(30, 5, "Fee", "B", 0);
                        $pdf->Cell(30, 5, "Amount", "B", 1);

                        // change the text color
                        $pdf->SetFont("Helvetica", "", 9);
                        $pdf->SetTextColor(10, 10, 10);
                        $pdf->SetLineWidth(0.1);
                        // here we set the payments to be paid
                        $counters = 1;
                        $total_fees_to_pay = 0;
                        foreach ($arrays as $key => $value) {
                            foreach ($value as $key2 => $value2) {
                                // row 1
                                $pdf->Cell(10, 8, "" . $counters . ". ", "B", 0);
                                $pdf->Cell(120, 8, ucwords(strtolower($key2)), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 1);
                                $total_fees_to_pay += ($value2 * 1);
                            }
                            $counters++;
                        }

                        // total
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Total Balance", "B", 0);
                        $pdf->Cell(30, 8, "Kes " . number_format($balanced), "B", 1);

                        // payment information
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 7, "Payment Information: ", 0, 0);
                        $pdf->SetFont("Arial", "BI", 9);
                        $pdf->MultiCell(130, 7, $_POST['invoice_email_message'], 0, "J");


                        // footer
                        // Position at 1.5 cm from bottom
                        $pdf->SetY(260);
                        // Arial italic 8
                        $pdf->SetFont('Arial', 'I', 10);
                        $pdf->Cell(190, 0, "", "T", 1);
                        $pdf->Cell(190, 7, "----- Fees Once Paid are not refundable OR transferable -------", 0, 1, 'C');
                        $pdf->Cell(190, 7, "Page No " . $pdf->PageNo(), 0, 1, 'C');
                    } else {
                        // break down the current prices and add
                        // first get the balance and know what was paid for
                        $balanced = getBalanceReports($admission_number, $term, $conn2);
                        $student_balance = $balanced;

                        // echo "New balance : <br>";
                        // echo json_encode($arrays)."<br><br>";

                        // ouput your pdf file here 
                        $pdf->AddPage();
                        $pdf->setHeaderPos(190);
                        $pdf->Image(dirname(__FILE__) . "../../" . schoolLogo($conn), 170, 3, 30);
                        $pdf->SetFont("Arial", "", 40);
                        $pdf->Cell(100, 30, "INVOICE", 0, 1, "L");
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "INVOICE NUMBER");
                        $pdf->Cell(50, 5, "DATE OF ISSUE", 0, 1);
                        $pdf->SetFont("Arial", "", 8);
                        $pdf->Cell(50, 5, date("YmdHi") . "#" . $admission_number);
                        $pdf->Cell(50, 5, date("D dS M Y"), 0, 1);

                        // BILL FOR
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "B", 9);
                        $pdf->Cell(50, 5, "BILLED TO:", 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, trim(ucwords(strtolower($pdf->school_name))), 0, 1);

                        // student details
                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, $student_name, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "P.0 Box " . $pdf->school_po . " - " . $pdf->school_BOX_CODE, 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Reg No. : " . $admission_number, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Contact us : " . $pdf->school_contact . "", 0, 1);


                        $pdf->SetFont("Arial", "", 9);
                        $pdf->Cell(50, 5, "Course Level : " . $student_class, 0, 0);
                        $pdf->Cell(50, 5, "", 0, 0);
                        $pdf->Cell(50, 5, "Email us : " . $_SESSION['school_mail'] . "", 0, 1);

                        // here we have the description of all the VOTEHEADS the student is supposed to pay.
                        $pdf->Ln(20);
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->SetTextColor(120, 120, 120);
                        $pdf->SetLineWidth(0.5);
                        $pdf->Cell(10, 5, "#", "B", 0);
                        $pdf->Cell(120, 5, "Description", "B", 0);
                        $pdf->Cell(30, 5, "Fee", "B", 0);
                        $pdf->Cell(30, 5, "Amount", "B", 1);

                        // change the text color
                        $pdf->SetFont("Helvetica", "", 9);
                        $pdf->SetTextColor(10, 10, 10);
                        $pdf->SetLineWidth(0.1);
                        // here we set the payments to be paid
                        $total_fees_to_pay = 0;
                        $counted = 1;
                        foreach ($arrays as $key => $value) {
                            foreach ($value as $key2 => $value2) {
                                // row 1
                                $pdf->Cell(10, 8, "" . $counted . ". ", "B", 0);
                                $pdf->Cell(120, 8, ucwords(strtolower($key2)), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 0);
                                $pdf->Cell(30, 8, "Kes " . number_format($value2), "B", 1);
                                $total_fees_to_pay += ($value2 * 1);
                            }
                            $counted++;
                        }

                        // add another row of amount paid
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Fees Paid ", "B", 0);
                        // $pdf->Cell(30, 8, "Kes " . number_format($total_fees_to_pay), "B", 1);
                        $pdf->Cell(30, 8, "Kes " . number_format($feespaidbystud), "B", 1);

                        // total
                        $pdf->SetFont("Arial", "B", 10);
                        $pdf->Cell(10, 8, "", "", 0);
                        $pdf->Cell(120, 8, "", "", 0);
                        $pdf->Cell(30, 8, "Total Balance ", "B", 0);
                        $pdf->Cell(30, 8, "Kes " . number_format($balanced), "B", 1);

                        // payment information
                        $pdf->Ln(10);
                        $pdf->SetFont("Arial", "", 10);
                        $pdf->Cell(40, 7, "Payment Information: ", 0, 0);
                        $pdf->SetFont("Arial", "BI", 9);
                        $pdf->MultiCell(130, 7, $_POST['invoice_email_message'], 0, "J");


                        // footer
                        // Position at 1.5 cm from bottom
                        $pdf->SetY(260);
                        // Arial italic 8
                        $pdf->SetFont('Arial', 'I', 10);
                        $pdf->Cell(190, 0, "", "T", 1);
                        $pdf->Cell(190, 7, "----- Fees Once Paid are not refundable OR transferable -------", 0, 1, 'C');
                        $pdf->Cell(190, 7, "Page No " . $pdf->PageNo(), 0, 1, 'C');
                    }
                    // break;

                    $pdf->set_document_title("Invoices");
                    $file_names = date("YmdHi");
                    $pdf->Output("F", "../invoices/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $admission_number . ".pdf");
                    chmod("../invoices/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $admission_number . ".pdf", 0777);
                    $path_of_document = "../invoices/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $admission_number . ".pdf";
                    // take the emails and send
                    $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $stmt->store_result();
                    $rnums = $stmt->num_rows;
                    if ($rnums > 0) {
                        // contimue to send email
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $email_sets = $row['valued'];
                                $lengths = strlen($email_sets);

                                if ($lengths > 0) {
                                    // send email
                                    $json_mail = json_decode($email_sets);
                                    $sender_name = $json_mail->sender_name;
                                    $email_host_addr = $json_mail->email_host_addr;
                                    $email_username = $json_mail->email_username;
                                    $email_password = $json_mail->email_password;
                                    $tester_mail = $json_mail->tester_mail;

                                    // send email
                                    try {
                                        $mail = new PHPMailer(true);

                                        $mail->isSMTP();
                                        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                        // $mail->Host = 'smtp.gmail.com';
                                        $mail->Host = $email_host_addr;
                                        $mail->SMTPAuth = true;
                                        // $mail->Username = "hilaryme45@gmail.com";
                                        // $mail->Password = "cmksnyxqmcgtncxw";
                                        $mail->Username = $email_username;
                                        $mail->Password = $email_password;
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                        $mail->Port = 587;


                                        $mail->setFrom($email_username, $sender_name);
                                        strlen(trim($_POST['bcc_email'])) > 1 ?  $mail->addBCC($_POST['bcc_email'], $sender_name) : "";
                                        strlen(trim($_POST['cc_email'])) > 1 ?  $mail->addCC($_POST['cc_email'], $sender_name) : "";

                                        // PRIMARY EMAIL ADDRESS
                                        // SECONDARY EMAIL ADDRESS
                                        $primary_email = $student_data['parent_email'];
                                        $secondary_email = $student_data['parent_email2'];

                                        $send_to_whom = $_POST['send_to_whom'];
                                        $send_mail_to = "";
                                        if ($send_to_whom == "primary_parents") {
                                            strlen(trim($primary_email)) > 0 ? $mail->addAddress($primary_email) : "";
                                            $send_mail_to = $primary_email;
                                        } elseif ($send_to_whom == "secondary_parent") {
                                            strlen(trim($secondary_email)) > 0 ? $mail->addAddress($secondary_email) : "";
                                            $send_mail_to = $secondary_email;
                                        } elseif ($send_to_whom == "both_parent") {
                                            strlen(trim($primary_email)) > 0 ? $mail->addAddress($primary_email) : "";
                                            strlen(trim($secondary_email)) > 0 ? $mail->addAddress($secondary_email) : "";
                                            $send_mail_to = $primary_email . "," . $secondary_email;
                                        }
                                        // check who you are sending to
                                        // $mail->addAddress($send_mail_to);
                                        $mail->isHTML(true);
                                        $mail->Subject = $_POST['message_subjects'];
                                        $mail->Body = $_POST['invoice_message'];

                                        // attach the file
                                        $mail->AddAttachment($path_of_document, '', $encoding = 'base64', $type = 'application/pdf');

                                        $mail->send();

                                        // save the email address sent
                                        $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`,`attachments`) VALUES (?,?,?,?,?,?,?,?)";
                                        $stmt = $conn2->prepare($insert);
                                        $dates = date("YmdHis");
                                        $stmt->bind_param("ssssssss", $email_username, $send_mail_to, $_POST['bcc_email'], $dates, $_POST['message_subjects'], $_POST['invoice_message'], $_POST['bcc_email'], $path_of_document);
                                        $stmt->execute();
                                        // end of saving


                                    } catch (Exception $th) {
                                        echo "<p class='text-danger p-1 border border-danger'>Error : " . $mail->ErrorInfo . "</p>";
                                    }
                                } else {
                                    echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
                                }
                            }
                        }
                    } else {
                        echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
                    }
                }

                echo
                "
                <p class='text-success border border-success p-1'><b>Note</b>: <br> " . count($stud_ids) . " Emails has been created and sent successfully.</p>
                ";
                // successfully created the email
                echo "Emails successfully created!";
            }
        } else {
            echo "<p style='color:red;'>Please select a student before you proceed!</p>";
        }
    } elseif (isset($_POST['generate_students_exams_report'])) {
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        // include_once("../ajax/finance/financial.php");
        $class_select = $_POST['class_select'];
        $terms_selected = $_POST['terms_selected'];
        $exams_selected = $_POST['exams_selected'];
        $academic_year = $_POST['academic_year'];
        $directors_comments = $_POST['directors_comments'];
        $next_yr_opening = $_POST['next_yr_opening'];
        $actions = $_POST['actions'];
        $grades_options = $_POST['grades_options'];
        $include_your_tutors = $_POST['include_your_tutors'];
        $include_trend_analysis = $_POST['include_trend_analysis'];

        $email_cc_subject_reports = $_POST['email_cc_subject_reports'];
        $email_subject_exams_report = $_POST['email_subject_exams_report'];
        $email_contents_exam_reports = $_POST['email_contents_exam_reports'];
        $send_to_email_reports = $_POST['send_to_email_reports'];

        // echo $email_contents_exam_reports."<br>";

        if ($actions == "print_exams") {
            // go through the terms that have been selected and get all the exams done in those periods
            $term_n_exams = [];
            $terms_selected = json_decode($terms_selected);

            // echo $directors_comments;
            $students_lists = [];
            $students_data = json_decode($directors_comments);
            for ($indexes = 0; $indexes < count($students_data); $indexes++) {
                array_push($students_lists, $students_data[$indexes]->student_adm);
            }

            // echo json_encode($students_lists);
            $exams_selected = json_decode($exams_selected);
            for ($index = 0; $index < count($terms_selected); $index++) {
                // get the term start date and end date
                $select = "SELECT * FROM `academic_calendar` WHERE `term` = '" . $terms_selected[$index] . "'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $start_date = "";
                $end_date = "";
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $start_date = $row['start_time'];
                        $end_date = $row['end_time'];
                    }
                }
                // get all exams done between the dates
                $exams_lists = [];
                $select = "SELECT * FROM `exams_tbl` WHERE `start_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        if (chckPrsnt($exams_selected, $row['exams_id'])) {
                            array_push($exams_lists, $row['exams_id']);
                        }
                    }
                }
                $array_data = array($terms_selected[$index] => $exams_lists);
                array_push($term_n_exams, $array_data);
            }
            // echo json_encode($exams_selected)."<hr>";

            // get the subjects done by the students in those terms
            $subject_list_in_exams = [];
            for ($indexing = 0; $indexing < count($term_n_exams); $indexing++) {
                foreach ($term_n_exams[$indexing] as $key => $value) {
                    for ($index = 0; $index < count($value); $index++) {
                        $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '" . $value[$index] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $subjects_selected = $row['subject_done'];
                                $subjects_selected = strlen($subjects_selected) > 2 ? substr($subjects_selected, 1, (strlen($subjects_selected) - 2)) : "";
                                // echo $subjects_selected . "<br>";
                                $subject_listing = explode(",", $subjects_selected);
                                for ($i = 0; $i < count($subject_listing); $i++) {
                                    if (chckPrsnt($subject_list_in_exams, $subject_listing[$i]) == 0) {
                                        array_push($subject_list_in_exams, $subject_listing[$i]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // get all subjects in the subject list
            $new_subject_list = [];
            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (chckPrsnt($subject_list_in_exams, $row['subject_id']) == 1) {
                        array_push($new_subject_list, $row['subject_id']);
                    }
                }
            }
            // echo json_encode($subject_list_in_exams)."<hr>";
            // echo json_encode($new_subject_list);

            // get the subjects marks
            $exams_data_marks = [];
            for ($index = 0; $index < count($term_n_exams); $index++) {
                foreach ($term_n_exams[$index] as $key => $value) {
                    // get the term
                    $this_term = $key;
                    $exams_done = $value;
                    $exams_dones = [];
                    for ($ind = 0; $ind < count($exams_done); $ind++) {
                        $exams_id = $exams_done[$ind];
                        $exams_marks = [];
                        for ($inde = 0; $inde < count($students_lists); $inde++) {
                            // loop through the subjects and their marks
                            $student_subject_marks = [];
                            for ($indexes = 0; $indexes < count($new_subject_list); $indexes++) {
                                $subject_id = $new_subject_list[$indexes];
                                $select = "SELECT * FROM `exam_record_tbl` WHERE `exam_id` = '" . $exams_id . "' AND `subject_id` = '" . $subject_id . "' AND `student_id` = '" . $students_lists[$inde] . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $exam_marks = $row['exam_marks'];
                                        $exam_grade = $row['exam_grade'];
                                        $subject_ids = $row['subject_id'];
                                        $exams_data = array("subject_marks" => $exam_marks, "subject_ids" => $subject_ids, "exams_grades" => $exam_grade);
                                        array_push($student_subject_marks, $exams_data);
                                    } else {
                                        $exams_data = array("subject_marks" => 0, "subject_ids" => $subject_id, "exams_grades" => "-");
                                        array_push($student_subject_marks, $exams_data);
                                    }
                                } else {
                                    $exams_data = array("subject_marks" => 0, "subject_ids" => $subject_id, "exams_grades" => "-");
                                    array_push($student_subject_marks, $exams_data);
                                }
                            }
                            $student_data = array("student_id" => $students_lists[$inde], "subjects_n_marks" => $student_subject_marks);
                            array_push($exams_marks, $student_data);
                        }
                        $exams_mark = array("exam_id" => $exams_id, "student_marks" => $exams_marks);
                        array_push($exams_dones, $exams_mark);
                    }
                    $term_exams = array("term" => $this_term, "exams_done" => $exams_dones);
                    array_push($exams_data_marks, $term_exams);
                }
            }
            // echo json_encode($exams_data_marks);

            // get the average marks of each student depending on the term they are in
            $student_average_scores = [];

            // create the student list and subject marks holder
            $student_mark_list = [];
            for ($index = 0; $index < count($students_lists); $index++) {
                $student_marks_holder = [];
                for ($index2 = 0; $index2 < count($new_subject_list); $index2++) {
                    $subject_mark_list = array("subject_id" => $new_subject_list[$index2], "subject_marks" => 0);
                    array_push($student_marks_holder, $subject_mark_list);
                }
                $students_full_list = array("student_id" => $students_lists[$index], "subject_marks" => $student_marks_holder);
                array_push($student_mark_list, $students_full_list);
            }
            // echo json_encode($student_mark_list);

            $total_marks_holder = [];
            // store the student marks in the mark list created above
            for ($index = 0; $index < count($exams_data_marks); $index++) {
                // echo json_encode($exams_data_marks[$index]['term']);
                // break;
                // term selected
                $term_selected = $exams_data_marks[$index]['term'];

                // create a copy of the marklist
                $per_term_mark_list = $student_mark_list;

                // get all the exams done this term
                $exams_done_this_term = $exams_data_marks[$index]['exams_done'];

                // go through the student marks list and add all the marks you get for the subjects
                for ($index1 = 0; $index1 < count($per_term_mark_list); $index1++) {

                    // subject lists loop
                    $subject_marks_termly = $per_term_mark_list[$index1]['subject_marks'];
                    for ($index4 = 0; $index4 < count($subject_marks_termly); $index4++) {
                        // display subject_id 
                        $per_term_sub_id = $subject_marks_termly[$index4]['subject_id'];
                        $perterm_marks = $subject_marks_termly[$index4]['subject_marks'];
                        // go through the exams done
                        for ($index2 = 0; $index2 < count($exams_done_this_term); $index2++) {
                            $student_marks = $exams_done_this_term[$index2]['student_marks'];

                            for ($index6 = 0; $index6 < count($student_marks); $index6++) {
                                // go through the individual student marks and add where neccessary
                                $student_id = $student_marks[$index6]['student_id'];
                                $subjects_n_marks = $student_marks[$index6]['subjects_n_marks'];
                                for ($index3 = 0; $index3 < count($subjects_n_marks); $index3++) {
                                    $subject_marks = $subjects_n_marks[$index3]['subject_marks'];
                                    $subject_ids = $subjects_n_marks[$index3]['subject_ids'];
                                    $exams_grades = $subjects_n_marks[$index3]['exams_grades'];

                                    // assign the marks to the students and the subject
                                    if ($per_term_mark_list[$index1]['student_id'] == $student_id && $per_term_sub_id == $subject_ids) {
                                        // echo $per_term_mark_list[$index1]['subject_marks'][$index4]['subject_marks'];
                                        $per_term_mark_list[$index1]['subject_marks'][$index4]['subject_marks'] += $subject_marks;
                                        $perterm_marks += $subject_marks;
                                    }
                                }
                            }
                        }
                    }
                }
                // echo json_encode($per_term_mark_list);

                // take all the student marks and devide by the total number of exams done to get the average
                $total_exams_done = count($exams_done_this_term);

                // start getting average
                for ($index7 = 0; $index7 < count($per_term_mark_list); $index7++) {
                    for ($index8 = 0; $index8 < count($per_term_mark_list[$index7]['subject_marks']); $index8++) {
                        $total_exams_done = $total_exams_done > 0 ? $total_exams_done : 0;
                        $per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] = $per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] > 0 ? round($per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] / $total_exams_done) : 0;
                    }
                }

                // add the perfomance to the array holder
                $termly_marks_average = array("term" => $term_selected, "students_perfomaces" => $per_term_mark_list);
                array_push($total_marks_holder, $termly_marks_average);

                // echo json_encode($per_term_mark_list);
                // break;
            }
            // echo json_encode($total_marks_holder);
            // loop through the exams and get the data for the subjects and the students in term selected

            // loop through the students and get their perfomances term wise
            $student_termly_perfomance = [];
            for ($index = 0; $index < count($students_lists); $index++) {
                $termly_perfomace = [];
                for ($index2 = 0; $index2 < count($total_marks_holder); $index2++) {
                    $term = $total_marks_holder[$index2]['term'];
                    $students_perfomaces = $total_marks_holder[$index2]['students_perfomaces'];

                    for ($index3 = 0; $index3 < count($students_perfomaces); $index3++) {
                        $student_id = $students_perfomaces[$index3]['student_id'];
                        $subject_marks = $students_perfomaces[$index3]['subject_marks'];

                        if ($student_id == $students_lists[$index]) {
                            $term_data = array("TERM" => $term, "subject_perfomaces" => $subject_marks);
                            array_push($termly_perfomace, $term_data);
                        }
                    }
                }
                $student_perfomance_data = array("student_id" => $students_lists[$index], "termly_perfomace" => $termly_perfomace);
                array_push($student_termly_perfomance, $student_perfomance_data);
            }
            // echo json_encode($termly_perfomace);

            $pdf = new PDF_Diag('L', 'mm', 'A4');
            $term = getTermV2_exams($conn2);
            $anwani = $term;
            for ($index = 0; $index < count($student_termly_perfomance); $index++) {
                $infor_student = getStudDetail($conn2,$student_termly_perfomance[$index]['student_id']);
                $student_id = $student_termly_perfomance[$index]['student_id'];
                $termly_perfomace = $student_termly_perfomance[$index]['termly_perfomace'];

                if ($include_trend_analysis == "Yes") {
                    $subject_chosen = isJson_reports($infor_student['subjects_attempting']) ? json_decode($infor_student['subjects_attempting']) : [];
                    
                    
                    $student_details = getStudDetail($conn2, $student_id);
                    $pdf->AddPage();
                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);
                    $pdf->SetFillColor(245, 245, 245);
                    // $pdf->Ln();
                    $pdf->SetFont("Times", "B", 13);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->Image(dirname(__FILE__) . $pdf->school_logo, 8, 8, 30);
                    $pdf->Cell(297, 7, $_SESSION['schname'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(297, 7, "LEARNING PROGRESS REPORT", 0, 1, 'C', false);
                    $term = trim($_POST['report_term_selected']) == "" ? (($anwani != "TERM_3" && $anwani != "TERM_2") ? "FIRST TERM" : (($anwani == "TERM_2") ? "SECOND TERM" : "THIRD TERM")) : $_POST['report_term_selected'];
                    $pdf->Cell(297, 7, date("F Y"), 0, 1, 'C', false);
                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(297, 5, "Contact Us: " . $_SESSION['school_contact'], 0, 1, 'C', false);
                    $pdf->Cell(297, 5, "Email Us: " . $_SESSION['school_mail'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 13);

                    $pdf->SetFont("Times", "BU", 12);
                    $pdf->Cell(297, 5, className_exam($student_details['stud_class']) . " Academic Assessment", 0, 1, 'C', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Ln();
                    $pdf->Cell(15, 7, "Name:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(70, 7, ucwords(strtolower($student_details['surname'] . " " . $student_details['first_name'] . " " . $student_details['second_name'])), 1, 0, 'L', false);


                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "Adm No:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(20, 7, $student_details['adm_no'], 1, 0, 'L', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "TERM: ", 1, 0, 'C', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(30, 7, $term, 1, 0, 'C', false);


                    if (isset($_POST['academic_year'])) {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(17, 7, "Class:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 0, 'L', false);

                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(35, 7, "Academic Year:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(30, 7, $_POST['academic_year'], 1, 1, 'L', false);
                    } else {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(15, 7, "Class:", 1, 0, 'R', false);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 1, 'L', false);
                    }
                    $pdf->SetFont("Times", "BU", 12);

                    // create data
                    $terms_present = [];
                    for ($indexx = 0; $indexx < count($termly_perfomace); $indexx++) {
                        $display_data = [];
                        $TERM = $termly_perfomace[$indexx]['TERM'];
                        array_push($terms_present, $TERM);
                        $subject_perfomaces = $termly_perfomace[$indexx]['subject_perfomaces'];
                        // echo json_encode($subject_perfomaces);
                        for ($index10 = 0; $index10 < count($subject_perfomaces); $index10++) {
                            // skip that class if the subjects is not amoung the list of the subject the student chose
                            if (count($subject_chosen) > 0) {
                                if (!chckPrsnt($subject_chosen,$subject_perfomaces[$index10]['subject_id'])) {
                                    continue;
                                }
                            }
                            // get the subject name
                            $subject_details = subjectsDetails($conn2, $subject_perfomaces[$index10]['subject_id']);
                            $new_data = array($subject_details[0] => $subject_perfomaces[$index10]['subject_marks']);
                            $display_data[$subject_details[1]] = $subject_perfomaces[$index10]['subject_marks'];
                            // echo $subject_details[0];
                        }
                        $bar_height = 120;
                        $gap_size = 15;
                        if (count($termly_perfomace) > 0) {
                            if (count($termly_perfomace) == 1) {
                                $bar_height = 120;
                            } elseif (count($termly_perfomace) == 2) {
                                $bar_height = 55;
                            } elseif (count($termly_perfomace) == 3) {
                                $bar_height = 35;
                                $gap_size = 10;
                            }
                        }

                        $format = $grades_options == "grades only" ? '%l' : (($grades_options == "marks only") ? "%l : (%v%)" : "%l : (%v%)");

                        // display graphs
                        $x_before = $pdf->GetX();
                        $pdf->SetFont("Times", "BU", 10);
                        $pdf->Cell(295, 6, $TERM . " Trend Analysis", 0, 1, "C");
                        $pdf->BarDiagram(250, $bar_height, ($display_data), $format, array(22, 164, 250), 100, 20);
                        $pdf->SetXY($x_before, $pdf->GetY() + $gap_size);
                        // break;
                    }
                }

                // SECTION 2
                $pdf->AddPage();
                $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);
                if ($include_trend_analysis != "Yes") {
                    $student_details = getStudDetail($conn2, $student_id);
                    $pdf->SetFillColor(245, 245, 245);
                    // $pdf->Ln();
                    $pdf->SetFont("Times", "B", 13);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->Image(dirname(__FILE__) . $pdf->school_logo, 8, 8, 30);
                    $pdf->Cell(297, 7, $_SESSION['schname'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(297, 7, "LEARNING PROGRESS REPORT", 0, 1, 'C', false);
                    $term = trim($_POST['report_term_selected']) == "" ? (($anwani != "TERM_3" && $anwani != "TERM_2") ? "FIRST TERM" : (($anwani == "TERM_2") ? "SECOND TERM" : "THIRD TERM")) : $_POST['report_term_selected'];
                    $pdf->Cell(297, 7, date("F Y"), 0, 1, 'C', false);
                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(297, 7, "Contact Us: " . $_SESSION['school_contact'], 0, 1, 'C', false);
                    $pdf->Cell(297, 7, "Email Us: " . $_SESSION['school_mail'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 13);

                    $pdf->SetFont("Times", "BU", 12);
                    $pdf->Cell(297, 5, className_exam($student_details['stud_class']) . " Academic Assessment", 0, 1, 'C', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Ln();
                    $pdf->Cell(15, 7, "Name:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(70, 7, ucwords(strtolower($student_details['first_name'] . " " . $student_details['second_name']." ".$student_details['surname'])), 1, 0, 'L', false);


                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "Adm No:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(20, 7, $student_details['adm_no'], 1, 0, 'L', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "TERM: ", 1, 0, 'C', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(30, 7, $term, 1, 0, 'C', false);


                    if (isset($_POST['academic_year'])) {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(17, 7, "Class:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 0, 'L', false);

                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(35, 7, "Academic Year:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(30, 7, $_POST['academic_year'], 1, 1, 'L', false);
                    } else {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(15, 7, "Class:", 1, 0, 'R', false);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 1, 'L', false);
                    }
                }
                $pdf->SetFont("Times", "B", 10);
                $pdf->Cell(295, 6, "TERMLY ACADEMIC RESULTS", 0, 1, 'C', false);
                // echo true ? 9 : 10;

                $terms_present = [];
                for ($indexx = 0; $indexx < count($termly_perfomace); $indexx++) {
                    $TERM = $termly_perfomace[$indexx]['TERM'];
                    array_push($terms_present, $TERM);
                    // break;
                }
                // echo json_encode($termly_perfomace);
                $cell_width = 75;
                if ($include_your_tutors == "Yes") {
                    $cell_width = 55;
                }

                // create the termly academic table
                $pdf->SetFont("Times", "B", 10);
                $pdf->SetLineWidth(.1);
                $pdf->SetFillColor(245, 245, 245);
                $pdf->Ln(5);
                $pdf->Cell(50, 20, "SUBJECTS", 1, 0, 'C', true);
                $terms_default_counter = 3;
                $terms_in_present = count($terms_present);
                $table_titles_head = $_POST['garding_options_grade_8'] == "IGCSE" ? "Points" : ($_POST['garding_options_grade_8'] == "iPrimary" ? "Grades" : "Grades/Points");
                for ($indexing = 0; $indexing < $terms_default_counter; $indexing++) {
                    if ($indexing >= $terms_in_present) {
                        if ($grades_options == "grades only") {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell($cell_width, 10, $table_titles_head, 1, 0, 'C', true);
                        } elseif ($grades_options == "marks only") {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell($cell_width, 10, "AGGREGATE", 1, 0, 'C', true);
                        } else {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, "AGGREGATE", 1, 0, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, $table_titles_head, 1, 0, 'C', true);
                        }
                        $X = $pdf->GetX();
                        $Y = $pdf->GetY() - 10;
                        $pdf->SetXY($X, $Y);
                    } else {
                        if ($grades_options == "grades only") {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width), 10, $table_titles_head, 1, 0, 'C', true);
                        } elseif ($grades_options == "marks only") {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width), 10, "AGGREGATE", 1, 0, 'C', true);
                        } else {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, "AGGREGATE", 1, 0, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, $table_titles_head, 1, 0, 'C', true);
                        }
                        $X = $pdf->GetX();
                        $Y = $pdf->GetY() - 10;
                        $pdf->SetXY($X, $Y);
                    }
                }

                if ($include_your_tutors == "Yes") {
                    $pdf->Cell(55, 20, "TUTOR", 1, 1, 'C', true);
                } else {
                    $pdf->Ln();
                    $pdf->Ln();
                }

                // FILL THE SUBJECTS AND THEIR MARKS
                $subject_count = count($termly_perfomace[0]['subject_perfomaces']);
                $term_count = count($termly_perfomace);
                $term_default_count = 3;
                $pdf->SetFont("Times", "", 11);
                // echo json_encode($termly_perfomace)."<br>";
                // subjects passed
                $subjects_passed = [];
                $subjects_passed['TERM_1'] = 0;
                $subjects_passed['TERM_2'] = 0;
                $subjects_passed['TERM_3'] = 0;

                // subjects failed
                $subjects_failed = [];
                $subjects_failed['TERM_1'] = 0;
                $subjects_failed['TERM_2'] = 0;
                $subjects_failed['TERM_3'] = 0;

                // points
                $points_scored = [];
                $points_scored['TERM_1'] = 0;
                $points_scored['TERM_2'] = 0;
                $points_scored['TERM_3'] = 0;

                $subject_chosen = isJson_reports($infor_student['subjects_attempting']) ? json_decode($infor_student['subjects_attempting']) : [];
                // echo json_decode($infor_student['subjects_attempting'])[0];
                for ($index13 = 0; $index13 < count($new_subject_list); $index13++) {
                    $subject_id = $new_subject_list[$index13];

                    // skip that class if the subjects is not amoung the list of the subject the student chose
                    if (count($subject_chosen) > 0) {
                        if (!chckPrsnt($subject_chosen,$subject_id)) {
                            continue;
                        }
                    }
                    $our_staff = getStaffData($conn);
                    $subject_details = subjectsDetails($conn2, $subject_id, $our_staff);
                    $pdf->Cell(50, 8, $subject_details[1], 1, 0, "L", true);
                    // loop through the subjects and their marks in their terms
                    $subject_counters = 0;
                    for ($index11 = 0; $index11 < $subject_count; $index11++) {
                        for ($index12 = 0; $index12 < $term_default_count; $index12++) {
                            if ($index12 >= $term_count) {
                                if ($subject_counters == $index13) {
                                    $inside_sub_id == $subject_id;
                                }
                            }else {
                                $inside_sub_id = $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'];
                            }
                            $subject_counters++;
                            if ($inside_sub_id == $subject_id) {
                                if ($grades_options == "grades only") {
                                    // $pdf->Cell(28, 10, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks']." %",1,0,"C",false);
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell($cell_width, 8, "-", 1, 0, "C", false);
                                    } else {
                                        // echo $index11." -- ".$index12."<br>";
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell($cell_width, 8, ($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $grades : "-"), 1, 0, "C", false);
                                    }
                                } elseif ($grades_options == "marks only") {
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell($cell_width, 8, "-", 1, 0, "C", false);
                                    } else {
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += ($grades * 1);
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell($cell_width, 8, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] . " %" : "-", 1, 0, "C", false);
                                    }
                                    // $pdf->Cell(27, 8, getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'],$_POST['garding_options_grade_8']),1,0,"C",false);
                                } else {
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell(($cell_width / 2), 8, "-", 1, 0, "C", false);
                                        $pdf->Cell(($cell_width / 2), 8, "-", 1, 0, "C", false);
                                    } else {
                                        $pdf->Cell(($cell_width / 2), 8, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] . " %", 1, 0, "C", false);
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        // echo $grades . " ".$termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks']."<br>";
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell(($cell_width / 2), 8, ($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $grades : "-"), 1, 0, "C", false);
                                    }
                                }
                            }
                        }
                    }
                    if ($include_your_tutors == "Yes") {
                        $pdf->Cell(55, 8, ucwords(strtolower($subject_details[0])), 1, 1, "L", true);
                    } else {
                        $pdf->Ln();
                        // $pdf->Ln();
                    }
                }



                // SECTION 3
                $pdf->AddPage();
                $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);

                // synopsis
                if ($_POST['garding_options_grade_8'] == "IGCSE" || $_POST['garding_options_grade_8'] == "iPrimary") {
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(210, 7, "Synopsis of perfomance", 1, 1, "C", true);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "TERMS", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 1", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 2", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 3", 1, 1, "C", true);

                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "No. of Subjects Passed", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $subjects_passed['TERM_1'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $subjects_passed['TERM_2'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $subjects_passed['TERM_3'] . " Subject(s)" : "-", 1, 1, "C", false);

                    // row 2
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "No. of Subjects Failed", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $subjects_failed['TERM_1'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $subjects_failed['TERM_2'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $subjects_failed['TERM_3'] . " Subject(s)" : "-", 1, 1, "C", false);

                    if ($_POST['garding_options_grade_8'] == "IGCSE") {
                        $pdf->Cell(30, 7, "", 0, 0, 'C');
                        $pdf->Cell(60, 7, "Total Point(s)", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $points_scored['TERM_1'] . " Point(s)" : "-", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $points_scored['TERM_2'] . " Point(s)" : "-", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $points_scored['TERM_3'] . " Point(s)" : "-", 1, 1, "C", false);
                    }
                }

                $pdf->Ln();
                if ($_POST['garding_options_grade_8'] == "iPrimary" || $_POST['garding_options_grade_8'] == "IGCSE" || $_POST['garding_options_grade_8'] == "cbc") {
                    if ($_POST['garding_options_grade_8'] == "iPrimary") {
                        // ROW

                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW

                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "100% - 91%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "A*", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "90% - 81%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "A", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "80% - 71%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "B", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "70% - 61%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "C", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "60% - 51%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "D", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "50% - 41%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "E", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "40% - 31%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "F", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "30% - 0%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "U", 1, 1, 'C');
                    } elseif ($_POST['garding_options_grade_8'] == "IGCSE") {
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "100% - 91%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "9", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "90% - 81%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "8", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "80% - 74%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "7", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "73% - 68%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "6", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "67% - 60%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "5", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "59% - 54%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "4", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "53% - 47%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "3", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "46% - 40%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "2", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "39% - 35%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "1", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "34% - 0%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "U", 1, 1, 'C');
                    } elseif ($_POST['garding_options_grade_8'] == "cbc") {
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "4", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Exceeding Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "3", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Approaching Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "2", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Meeting Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "1", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Approaching Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "A", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Absent", 1, 1, 'C');
                    }
                }



                $pdf->Ln(5);
                $pdf->Cell(90, 0, "");
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(90, 6, "Director`s Comment", 0, 1, 'C');
                // $pdf->Cell(60, 16, "Directors Comments:", 1, 0, 'L', true);
                $pdf->Cell(10, 8, "", 0, 0, 'C');
                $pdf->Cell(50, 16, "Name: " . ucwords(strtolower($_SESSION['admin_name'])) . "", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);

                // $X = $pdf->GetX();
                // $Y = $pdf->GetY()-8;
                // $pdf->SetXY($X, $Y);
                // catch the student comments
                $student_comments = "";
                // echo $directors_comments;
                $all_comments = $directors_comments;
                $all_comments = json_decode($all_comments);
                for ($index_comment = 0; $index_comment < count($all_comments); $index_comment++) {
                    $student_adm = $all_comments[$index_comment]->student_adm;
                    $directors_commented = $all_comments[$index_comment]->directors_commented;
                    if ($student_termly_perfomance[$index]['student_id'] == $student_adm) {
                        $student_comments = $directors_commented;
                        break;
                    }
                }
                $student_info = getStudDetail($conn2, $student_termly_perfomance[$index]['student_id']);
                // $pdf->MultiCell(200, 8, "Maya has a good understanding of her subjects however she has not settled yet in school. She was not able to complete her ETE paper this term", 1, 1, 'L');
                $string_width = $pdf->GetStringWidth($student_comments);
                if (strlen($student_comments) > 0) {
                    while ($string_width < 210) {
                        $student_comments .= " ";
                        $string_width = $pdf->GetStringWidth($student_comments);
                    }
                } else {
                    while ($string_width < 414) {
                        $student_comments .= "_";
                        $string_width = $pdf->GetStringWidth($student_comments);
                    }
                }
                $pdf->MultiCell(210, 8, editComments($student_comments, $student_info), 1, 1, 'L', false);

                $pdf->Ln(5);
                $pdf->Cell(15, 8, "", 0, 0, 'C');
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(20, 7, "Date: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(60, 7, date("M Y"), 1, 0, 'L');

                // attendance stats
                $attendances = presentStats_report($conn2, $student_termly_perfomance[$index]['student_id'], $student_info['stud_class']);
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(30, 7, "Attendance: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(50, 7, $attendances[2] . " %", 1, 0, 'L');

                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(40, 7, "Next Term Opens: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(50, 7, date("D, dS F Y", strtotime($next_yr_opening)), 1, 1, 'L');
                // break;
            }
            // echo $directors_comments;
            $pdf->SetTitle(className_exam($class_select) . " Termly Reports");
            $pdf->Output();
        } elseif ($actions == "email_parents") {
            // create folders for storing students report cards
            // check if the directory for the institution is created
            if (!folder_exist("../report_cards/" . $_SESSION['dbname'] . "")) {
                // create the folder with all permissions
                // echo "Created!";
                mkdir("../report_cards/" . $_SESSION['dbname'] . "");
                chmod("../report_cards/" . $_SESSION['dbname'] . "", 0777);
            }
            // create the directory time and date
            $dated = date("YmdHi");
            if (!folder_exist("../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "")) {
                // create the folder with all permissions
                // echo "Created!";
                mkdir("../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "");
                chmod("../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "", 0777);
            }
            // sending as emails
            // go through the terms that have been selected and get all the exams done in those periods
            $term_n_exams = [];
            $terms_selected = json_decode($terms_selected);

            // echo $directors_comments;
            $students_lists = [];
            $students_data = json_decode($directors_comments);
            for ($indexes = 0; $indexes < count($students_data); $indexes++) {
                array_push($students_lists, $students_data[$indexes]->student_adm);
            }

            // echo json_encode($students_lists);
            $exams_selected = json_decode($exams_selected);
            for ($index = 0; $index < count($terms_selected); $index++) {
                // get the term start date and end date
                $select = "SELECT * FROM `academic_calendar` WHERE `term` = '" . $terms_selected[$index] . "'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $start_date = "";
                $end_date = "";
                if ($result) {
                    if ($row = $result->fetch_assoc()) {
                        $start_date = $row['start_time'];
                        $end_date = $row['end_time'];
                    }
                }
                // get all exams done between the dates
                $exams_lists = [];
                $select = "SELECT * FROM `exams_tbl` WHERE `start_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        if (chckPrsnt($exams_selected, $row['exams_id'])) {
                            array_push($exams_lists, $row['exams_id']);
                        }
                    }
                }
                $array_data = array($terms_selected[$index] => $exams_lists);
                array_push($term_n_exams, $array_data);
            }
            // echo json_encode($term_n_exams);

            // get the subjects done by the students in those terms
            $subject_list_in_exams = [];
            for ($indexing = 0; $indexing < count($term_n_exams); $indexing++) {
                foreach ($term_n_exams[$indexing] as $key => $value) {
                    for ($index = 0; $index < count($value); $index++) {
                        $select = "SELECT * FROM `exams_tbl` WHERE `exams_id` = '" . $value[$index] . "'";
                        $stmt = $conn2->prepare($select);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result) {
                            if ($row = $result->fetch_assoc()) {
                                $subjects_selected = $row['subject_done'];
                                $subjects_selected = strlen($subjects_selected) > 2 ? substr($subjects_selected, 1, (strlen($subjects_selected) - 2)) : "";
                                // echo $subjects_selected . "<br>";
                                $subject_listing = explode(",", $subjects_selected);
                                for ($i = 0; $i < count($subject_listing); $i++) {
                                    if (chckPrsnt($subject_list_in_exams, $subject_listing[$i]) == 0) {
                                        array_push($subject_list_in_exams, $subject_listing[$i]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // get all subjects in the subject list
            $new_subject_list = [];
            $select = "SELECT * FROM `table_subject`";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (chckPrsnt($subject_list_in_exams, $row['subject_id']) == 1) {
                        array_push($new_subject_list, $row['subject_id']);
                    }
                }
            }
            // echo json_encode($subject_list_in_exams);
            // echo json_encode($new_subject_list);

            // get the subjects marks
            $exams_data_marks = [];
            for ($index = 0; $index < count($term_n_exams); $index++) {
                foreach ($term_n_exams[$index] as $key => $value) {
                    // get the term
                    $this_term = $key;
                    $exams_done = $value;
                    $exams_dones = [];
                    for ($ind = 0; $ind < count($exams_done); $ind++) {
                        $exams_id = $exams_done[$ind];
                        $exams_marks = [];
                        for ($inde = 0; $inde < count($students_lists); $inde++) {
                            // loop through the subjects and their marks
                            $student_subject_marks = [];
                            for ($indexes = 0; $indexes < count($new_subject_list); $indexes++) {
                                $subject_id = $new_subject_list[$indexes];
                                $select = "SELECT * FROM `exam_record_tbl` WHERE `exam_id` = '" . $exams_id . "' AND `subject_id` = '" . $subject_id . "' AND `student_id` = '" . $students_lists[$inde] . "'";
                                $stmt = $conn2->prepare($select);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result) {
                                    if ($row = $result->fetch_assoc()) {
                                        $exam_marks = $row['exam_marks'];
                                        $exam_grade = $row['exam_grade'];
                                        $subject_ids = $row['subject_id'];
                                        $exams_data = array("subject_marks" => $exam_marks, "subject_ids" => $subject_ids, "exams_grades" => $exam_grade);
                                        array_push($student_subject_marks, $exams_data);
                                    } else {
                                        $exams_data = array("subject_marks" => 0, "subject_ids" => $subject_id, "exams_grades" => "-");
                                        array_push($student_subject_marks, $exams_data);
                                    }
                                } else {
                                    $exams_data = array("subject_marks" => 0, "subject_ids" => $subject_id, "exams_grades" => "-");
                                    array_push($student_subject_marks, $exams_data);
                                }
                            }
                            $student_data = array("student_id" => $students_lists[$inde], "subjects_n_marks" => $student_subject_marks);
                            array_push($exams_marks, $student_data);
                        }
                        $exams_mark = array("exam_id" => $exams_id, "student_marks" => $exams_marks);
                        array_push($exams_dones, $exams_mark);
                    }
                    $term_exams = array("term" => $this_term, "exams_done" => $exams_dones);
                    array_push($exams_data_marks, $term_exams);
                }
            }
            // echo json_encode($exams_data_marks);

            // get the average marks of each student depending on the term they are in
            $student_average_scores = [];

            // create the student list and subject marks holder
            $student_mark_list = [];
            for ($index = 0; $index < count($students_lists); $index++) {
                $student_marks_holder = [];
                for ($index2 = 0; $index2 < count($new_subject_list); $index2++) {
                    $subject_mark_list = array("subject_id" => $new_subject_list[$index2], "subject_marks" => 0);
                    array_push($student_marks_holder, $subject_mark_list);
                }
                $students_full_list = array("student_id" => $students_lists[$index], "subject_marks" => $student_marks_holder);
                array_push($student_mark_list, $students_full_list);
            }
            // echo json_encode($student_mark_list);

            $total_marks_holder = [];
            // store the student marks in the mark list created above
            for ($index = 0; $index < count($exams_data_marks); $index++) {
                // echo json_encode($exams_data_marks[$index]['term']);
                // break;
                // term selected
                $term_selected = $exams_data_marks[$index]['term'];

                // create a copy of the marklist
                $per_term_mark_list = $student_mark_list;

                // get all the exams done this term
                $exams_done_this_term = $exams_data_marks[$index]['exams_done'];

                // go through the student marks list and add all the marks you get for the subjects
                for ($index1 = 0; $index1 < count($per_term_mark_list); $index1++) {

                    // subject lists loop
                    $subject_marks_termly = $per_term_mark_list[$index1]['subject_marks'];
                    for ($index4 = 0; $index4 < count($subject_marks_termly); $index4++) {
                        // display subject_id 
                        $per_term_sub_id = $subject_marks_termly[$index4]['subject_id'];
                        $perterm_marks = $subject_marks_termly[$index4]['subject_marks'];
                        // go through the exams done
                        for ($index2 = 0; $index2 < count($exams_done_this_term); $index2++) {
                            $student_marks = $exams_done_this_term[$index2]['student_marks'];

                            for ($index6 = 0; $index6 < count($student_marks); $index6++) {
                                // go through the individual student marks and add where neccessary
                                $student_id = $student_marks[$index6]['student_id'];
                                $subjects_n_marks = $student_marks[$index6]['subjects_n_marks'];
                                for ($index3 = 0; $index3 < count($subjects_n_marks); $index3++) {
                                    $subject_marks = $subjects_n_marks[$index3]['subject_marks'];
                                    $subject_ids = $subjects_n_marks[$index3]['subject_ids'];
                                    $exams_grades = $subjects_n_marks[$index3]['exams_grades'];

                                    // assign the marks to the students and the subject
                                    if ($per_term_mark_list[$index1]['student_id'] == $student_id && $per_term_sub_id == $subject_ids) {
                                        // echo $per_term_mark_list[$index1]['subject_marks'][$index4]['subject_marks'];
                                        $per_term_mark_list[$index1]['subject_marks'][$index4]['subject_marks'] += $subject_marks;
                                        $perterm_marks += $subject_marks;
                                    }
                                }
                            }
                        }
                    }
                }
                // echo json_encode($per_term_mark_list);

                // take all the student marks and devide by the total number of exams done to get the average
                $total_exams_done = count($exams_done_this_term);

                // start getting average
                for ($index7 = 0; $index7 < count($per_term_mark_list); $index7++) {
                    for ($index8 = 0; $index8 < count($per_term_mark_list[$index7]['subject_marks']); $index8++) {
                        $total_exams_done = $total_exams_done > 0 ? $total_exams_done : 0;
                        $per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] = $per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] > 0 ? round($per_term_mark_list[$index7]['subject_marks'][$index8]['subject_marks'] / $total_exams_done) : 0;
                    }
                }

                // add the perfomance to the array holder
                $termly_marks_average = array("term" => $term_selected, "students_perfomaces" => $per_term_mark_list);
                array_push($total_marks_holder, $termly_marks_average);

                // echo json_encode($per_term_mark_list);
                // break;
            }
            // echo json_encode($total_marks_holder);
            // loop through the exams and get the data for the subjects and the students in term selected

            // loop through the students and get their perfomances term wise
            $student_termly_perfomance = [];
            for ($index = 0; $index < count($students_lists); $index++) {
                $termly_perfomace = [];
                for ($index2 = 0; $index2 < count($total_marks_holder); $index2++) {
                    $term = $total_marks_holder[$index2]['term'];
                    $students_perfomaces = $total_marks_holder[$index2]['students_perfomaces'];

                    for ($index3 = 0; $index3 < count($students_perfomaces); $index3++) {
                        $student_id = $students_perfomaces[$index3]['student_id'];
                        $subject_marks = $students_perfomaces[$index3]['subject_marks'];

                        if ($student_id == $students_lists[$index]) {
                            $term_data = array("TERM" => $term, "subject_perfomaces" => $subject_marks);
                            array_push($termly_perfomace, $term_data);
                        }
                    }
                }
                $student_perfomance_data = array("student_id" => $students_lists[$index], "termly_perfomace" => $termly_perfomace);
                array_push($student_termly_perfomance, $student_perfomance_data);
            }
            // echo json_encode($student_termly_perfomance);

            $email_sent_counter = 0;
            for ($index = 0; $index < count($student_termly_perfomance); $index++) {
                $infor_student = getStudDetail($conn2,$student_termly_perfomance[$index]['student_id']);

                $pdf = new PDF_Diag('L', 'mm', 'A4');
                $term = getTermV2_exams($conn2);

                $student_id = $student_termly_perfomance[$index]['student_id'];
                $termly_perfomace = $student_termly_perfomance[$index]['termly_perfomace'];

                if ($include_trend_analysis == "Yes") {
                    $subject_chosen = isJson_reports($infor_student['subjects_attempting']) ? json_decode($infor_student['subjects_attempting']) : [];

                    $student_details = getStudDetail($conn2, $student_id);
                    $pdf->AddPage();
                    $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);
                    $pdf->SetFillColor(245, 245, 245);
                    // $pdf->Ln();
                    $pdf->SetFont("Times", "B", 13);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->Image(dirname(__FILE__) . $pdf->school_logo, 8, 8, 30);
                    $pdf->Cell(297, 7, $_SESSION['schname'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(297, 7, "LEARNING PROGRESS REPORT", 0, 1, 'C', false);
                    $term = trim($_POST['report_term_selected']) == "" ? (($anwani != "TERM_3" && $anwani != "TERM_2") ? "FIRST TERM" : (($anwani == "TERM_2") ? "SECOND TERM" : "THIRD TERM")) : $_POST['report_term_selected'];
                    $pdf->Cell(297, 7, date("F Y"), 0, 1, 'C', false);
                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(297, 5, "Contact Us: " . $_SESSION['school_contact'], 0, 1, 'C', false);
                    $pdf->Cell(297, 5, "Email Us: " . $_SESSION['school_mail'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 13);

                    $pdf->SetFont("Times", "BU", 12);
                    $pdf->Cell(297, 5, className_exam($student_details['stud_class']) . " Academic Assessment", 0, 1, 'C', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Ln();
                    $pdf->Cell(15, 7, "Name:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(70, 7, ucwords(strtolower($student_details['surname'] . " " . $student_details['first_name'] . " " . $student_details['second_name'])), 1, 0, 'L', false);


                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "Adm No:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(20, 7, $student_details['adm_no'], 1, 0, 'L', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "TERM: ", 1, 0, 'C', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(30, 7, $term, 1, 0, 'C', false);


                    if (isset($_POST['academic_year'])) {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(17, 7, "Class:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 0, 'L', false);

                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(35, 7, "Academic Year:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(30, 7, $_POST['academic_year'], 1, 1, 'L', false);
                    } else {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(15, 7, "Class:", 1, 0, 'R', false);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 1, 'L', false);
                    }
                    $pdf->SetFont("Times", "BU", 12);

                    // create data
                    $terms_present = [];
                    for ($indexx = 0; $indexx < count($termly_perfomace); $indexx++) {
                        $display_data = [];
                        $TERM = $termly_perfomace[$indexx]['TERM'];
                        array_push($terms_present, $TERM);
                        $subject_perfomaces = $termly_perfomace[$indexx]['subject_perfomaces'];
                        // echo json_encode($subject_perfomaces);
                        for ($index10 = 0; $index10 < count($subject_perfomaces); $index10++) {
                            // skip that class if the subjects is not amoung the list of the subject the student chose
                            if (count($subject_chosen) > 0) {
                                if (!chckPrsnt($subject_chosen,$subject_perfomaces[$index10]['subject_id'])) {
                                    continue;
                                }
                            }
                            
                            // get the subject name
                            $subject_details = subjectsDetails($conn2, $subject_perfomaces[$index10]['subject_id']);
                            $new_data = array($subject_details[0] => $subject_perfomaces[$index10]['subject_marks']);
                            $display_data[$subject_details[1]] = $subject_perfomaces[$index10]['subject_marks'];
                            // echo $subject_details[0];
                        }
                        $bar_height = 120;
                        $gap_size = 15;
                        if (count($termly_perfomace) > 0) {
                            if (count($termly_perfomace) == 1) {
                                $bar_height = 120;
                            } elseif (count($termly_perfomace) == 2) {
                                $bar_height = 55;
                            } elseif (count($termly_perfomace) == 3) {
                                $bar_height = 35;
                                $gap_size = 10;
                            }
                        }

                        $format = $grades_options == "grades only" ? '%l' : (($grades_options == "marks only") ? "%l : (%v%)" : "%l : (%v%)");

                        // display graphs
                        $x_before = $pdf->GetX();
                        $pdf->SetFont("Times", "BU", 10);
                        $pdf->Cell(295, 6, $TERM . " Trend Analysis", 0, 1, "C");
                        $pdf->BarDiagram(250, $bar_height, ($display_data), $format, array(22, 164, 250), 100, 20);
                        $pdf->SetXY($x_before, $pdf->GetY() + $gap_size);
                        // break;
                    }
                }

                // SECTION 2
                $pdf->AddPage();
                $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);
                if ($include_trend_analysis != "Yes") {
                    $student_details = getStudDetail($conn2, $student_id);
                    $pdf->SetFillColor(245, 245, 245);
                    // $pdf->Ln();
                    $pdf->SetFont("Times", "B", 13);
                    $pdf->setSchoolLogo("../../" . schoolLogo($conn));
                    $pdf->Image(dirname(__FILE__) . $pdf->school_logo, 8, 8, 30);
                    $pdf->Cell(297, 7, $_SESSION['schname'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(297, 7, "LEARNING PROGRESS REPORT", 0, 1, 'C', false);
                    $term = trim($_POST['report_term_selected']) == "" ? (($anwani != "TERM_3" && $anwani != "TERM_2") ? "FIRST TERM" : (($anwani == "TERM_2") ? "SECOND TERM" : "THIRD TERM")) : $_POST['report_term_selected'];
                    $pdf->Cell(297, 7, date("F Y"), 0, 1, 'C', false);
                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(297, 7, "Contact Us: " . $_SESSION['school_contact'], 0, 1, 'C', false);
                    $pdf->Cell(297, 7, "Email Us: " . $_SESSION['school_mail'], 0, 1, 'C', false);
                    $pdf->SetFont("Times", "B", 13);

                    $pdf->SetFont("Times", "BU", 12);
                    $pdf->Cell(297, 5, className_exam($student_details['stud_class']) . " Academic Assessment", 0, 1, 'C', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Ln();
                    $pdf->Cell(15, 7, "Name:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(70, 7, ucwords(strtolower($student_details['first_name'] . " " . $student_details['second_name']." ".$student_details['surname'])), 1, 0, 'L', false);


                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "Adm No:", 1, 0, 'R', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(20, 7, $student_details['adm_no'], 1, 0, 'L', false);

                    $pdf->SetFont("Times", "B", 12);
                    $pdf->Cell(20, 7, "TERM: ", 1, 0, 'C', true);
                    $pdf->SetFont("Times", "", 12);
                    $pdf->Cell(30, 7, $term, 1, 0, 'C', false);


                    if (isset($_POST['academic_year'])) {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(17, 7, "Class:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 0, 'L', false);

                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(35, 7, "Academic Year:", 1, 0, 'R', true);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(30, 7, $_POST['academic_year'], 1, 1, 'L', false);
                    } else {
                        $pdf->SetFont("Times", "B", 12);
                        $pdf->Cell(15, 7, "Class:", 1, 0, 'R', false);
                        $pdf->SetFont("Times", "", 12);
                        $pdf->Cell(20, 7, className_exam($student_details['stud_class']), 1, 1, 'L', false);
                    }
                }
                $pdf->SetFont("Times", "B", 10);
                $pdf->Cell(295, 6, "TERMLY ACADEMIC RESULTS", 0, 1, 'C', false);
                // echo true ? 9 : 10;

                $terms_present = [];
                for ($indexx = 0; $indexx < count($termly_perfomace); $indexx++) {
                    $TERM = $termly_perfomace[$indexx]['TERM'];
                    array_push($terms_present, $TERM);
                    // break;
                }
                $cell_width = 75;
                if ($include_your_tutors == "Yes") {
                    $cell_width = 55;
                }

                // create the termly academic table
                $pdf->SetFont("Times", "B", 10);
                $pdf->SetLineWidth(.1);
                $pdf->SetFillColor(245, 245, 245);
                $pdf->Ln(5);
                $pdf->Cell(50, 20, "SUBJECTS", 1, 0, 'C', true);
                $terms_default_counter = 3;
                $terms_in_present = count($terms_present);
                $table_titles_head = $_POST['garding_options_grade_8'] == "IGCSE" ? "Points" : ($_POST['garding_options_grade_8'] == "iPrimary" ? "Grades" : "Grades/Points");
                for ($indexing = 0; $indexing < $terms_default_counter; $indexing++) {
                    if ($indexing >= $terms_in_present) {
                        if ($grades_options == "grades only") {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell($cell_width, 10, $table_titles_head, 1, 0, 'C', true);
                        } elseif ($grades_options == "marks only") {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell($cell_width, 10, "AGGREGATE", 1, 0, 'C', true);
                        } else {
                            $pdf->Cell($cell_width, 10, "TERM_" . ($indexing + 1), 1, 2, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, "AGGREGATE", 1, 0, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, $table_titles_head, 1, 0, 'C', true);
                        }
                        $X = $pdf->GetX();
                        $Y = $pdf->GetY() - 10;
                        $pdf->SetXY($X, $Y);
                    } else {
                        if ($grades_options == "grades only") {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width), 10, $table_titles_head, 1, 0, 'C', true);
                        } elseif ($grades_options == "marks only") {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width), 10, "AGGREGATE", 1, 0, 'C', true);
                        } else {
                            $pdf->Cell(($cell_width), 10, $terms_present[$indexing], 1, 2, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, "AGGREGATE", 1, 0, 'C', true);
                            $pdf->Cell(($cell_width / 2), 10, $table_titles_head, 1, 0, 'C', true);
                        }
                        $X = $pdf->GetX();
                        $Y = $pdf->GetY() - 10;
                        $pdf->SetXY($X, $Y);
                    }
                }

                if ($include_your_tutors == "Yes") {
                    $pdf->Cell(55, 20, "TUTOR", 1, 1, 'C', true);
                } else {
                    $pdf->Ln();
                    $pdf->Ln();
                }
                
                // FILL THE SUBJECTS AND THEIR MARKS
                $subject_count = count($termly_perfomace[0]['subject_perfomaces']);
                $term_count = count($termly_perfomace);
                $term_default_count = 3;
                $pdf->SetFont("Times", "", 11);
                // echo $subject_count;
                // subjects passed
                $subjects_passed = [];
                $subjects_passed['TERM_1'] = 0;
                $subjects_passed['TERM_2'] = 0;
                $subjects_passed['TERM_3'] = 0;

                // subjects failed
                $subjects_failed = [];
                $subjects_failed['TERM_1'] = 0;
                $subjects_failed['TERM_2'] = 0;
                $subjects_failed['TERM_3'] = 0;

                // points
                $points_scored = [];
                $points_scored['TERM_1'] = 0;
                $points_scored['TERM_2'] = 0;
                $points_scored['TERM_3'] = 0;
                
                $subject_chosen = isJson_reports($infor_student['subjects_attempting']) ? json_decode($infor_student['subjects_attempting']) : [];

                for ($index13 = 0; $index13 < count($new_subject_list); $index13++) {
                    $subject_id = $new_subject_list[$index13];

                    // skip that class if the subjects is not amoung the list of the subject the student chose
                    if (count($subject_chosen) > 0) {
                        if (!chckPrsnt($subject_chosen,$subject_id)) {
                            continue;
                        }
                    }

                    $our_staff = getStaffData($conn);
                    $subject_details = subjectsDetails($conn2, $subject_id, $our_staff);
                    $pdf->Cell(50, 8, $subject_details[1], 1, 0, "L", true);
                    // loop through the subjects and their marks in their terms
                    $subject_counters = 0;
                    for ($index11 = 0; $index11 < $subject_count; $index11++) {
                        for ($index12 = 0; $index12 < $term_default_count; $index12++) {
                            if ($index12 >= $term_count) {
                                if ($subject_counters == $index13) {
                                    $inside_sub_id == $subject_id;
                                }
                            } else {
                                $inside_sub_id = $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'];
                            }
                            $subject_counters++;
                            if ($inside_sub_id == $subject_id) {
                                if ($grades_options == "grades only") {
                                    // $pdf->Cell(28, 10, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks']." %",1,0,"C",false);
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell($cell_width, 8, "-", 1, 0, "C", false);
                                    } else {
                                        // echo $index11." -- ".$index12."<br>";
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell($cell_width, 8, ($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $grades : "-"), 1, 0, "C", false);
                                    }
                                } elseif ($grades_options == "marks only") {
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell($cell_width, 8, "-", 1, 0, "C", false);
                                    } else {
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += ($grades * 1);
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell($cell_width, 8, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] . " %" : "-", 1, 0, "C", false);
                                    }
                                    // $pdf->Cell(27, 8, getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'],$_POST['garding_options_grade_8']),1,0,"C",false);
                                } else {
                                    if ($index12 >= $term_count) {
                                        $pdf->Cell(($cell_width / 2), 8, "-", 1, 0, "C", false);
                                        $pdf->Cell(($cell_width / 2), 8, "-", 1, 0, "C", false);
                                    } else {
                                        $pdf->Cell(($cell_width / 2), 8, $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] . " %", 1, 0, "C", false);
                                        $grades = getGrade($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'], $_POST['garding_options_grade_8'], $termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_id'], $conn2);
                                        // echo $grades . " ".$termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks']."<br>";
                                        if ($grades == "U") {
                                            if($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0){
                                                $subjects_failed[$termly_perfomace[$index12]['TERM']]++;
                                            }
                                        } else {
                                            if ($_POST['garding_options_grade_8'] == "IGCSE") {
                                                $points_scored[$termly_perfomace[$index12]['TERM']] += is_numeric($grades) ? ($grades * 1) : 0;
                                            }
                                            $subjects_passed[$termly_perfomace[$index12]['TERM']]++;
                                        }
                                        $pdf->Cell(($cell_width / 2), 8, ($termly_perfomace[$index12]['subject_perfomaces'][$index11]['subject_marks'] > 0 ? $grades : "-"), 1, 0, "C", false);
                                    }
                                }
                            }
                        }
                    }
                    if ($include_your_tutors == "Yes") {
                        $pdf->Cell(55, 8, ucwords(strtolower($subject_details[0])), 1, 1, "L", true);
                    } else {
                        $pdf->Ln();
                        // $pdf->Ln();
                    }
                }



                // SECTION 3
                $pdf->AddPage();
                $pdf->Image(dirname(__FILE__) . "../../.." . "/sims/images/ux_design.png", 1, 1, 295);

                // synopsis
                if ($_POST['garding_options_grade_8'] == "IGCSE" || $_POST['garding_options_grade_8'] == "iPrimary") {
                    $pdf->SetFont("Times", "B", 11);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(210, 7, "Synopsis of perfomance", 1, 1, "C", true);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "TERMS", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 1", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 2", 1, 0, "C", true);
                    $pdf->Cell(50, 7, "TERM 3", 1, 1, "C", true);

                    $pdf->SetFont("Times", "", 11);
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "No. of Subjects Passed", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $subjects_passed['TERM_1'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $subjects_passed['TERM_2'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $subjects_passed['TERM_3'] . " Subject(s)" : "-", 1, 1, "C", false);

                    // row 2
                    $pdf->Cell(30, 7, "", 0, 0, 'C');
                    $pdf->Cell(60, 7, "No. of Subjects Failed", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $subjects_failed['TERM_1'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $subjects_failed['TERM_2'] . " Subject(s)" : "-", 1, 0, "C", false);
                    $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $subjects_failed['TERM_3'] . " Subject(s)" : "-", 1, 1, "C", false);

                    if ($_POST['garding_options_grade_8'] == "IGCSE") {
                        $pdf->Cell(30, 7, "", 0, 0, 'C');
                        $pdf->Cell(60, 7, "Total Point(s)", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_1'] > 0 || $subjects_failed['TERM_1'] > 0) ? $points_scored['TERM_1'] . " Point(s)" : "-", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_2'] > 0 || $subjects_failed['TERM_2'] > 0) ? $points_scored['TERM_2'] . " Point(s)" : "-", 1, 0, "C", false);
                        $pdf->Cell(50, 7, "" . ($subjects_passed['TERM_3'] > 0 || $subjects_failed['TERM_3'] > 0) ? $points_scored['TERM_3'] . " Point(s)" : "-", 1, 1, "C", false);
                    }
                }

                $pdf->Ln();
                if ($_POST['garding_options_grade_8'] == "iPrimary" || $_POST['garding_options_grade_8'] == "IGCSE" || $_POST['garding_options_grade_8'] == "cbc") {
                    if ($_POST['garding_options_grade_8'] == "iPrimary") {
                        // ROW

                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW

                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "100% - 91%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "A*", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "90% - 81%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "A", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "80% - 71%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "B", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "70% - 61%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "C", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "60% - 51%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "D", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "50% - 41%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "E", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "40% - 31%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "F", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "30% - 0%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "U", 1, 1, 'C');
                    } elseif ($_POST['garding_options_grade_8'] == "IGCSE") {
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "100% - 91%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "9", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "90% - 81%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "8", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "80% - 74%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "7", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "73% - 68%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "6", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "67% - 60%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "5", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "59% - 54%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "4", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "53% - 47%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "3", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "46% - 40%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "2", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "39% - 35%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "1", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "34% - 0%", 1, 0, 'C');
                        $pdf->Cell(50, 8, "U", 1, 1, 'C');
                    } elseif ($_POST['garding_options_grade_8'] == "cbc") {
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->SetFont("Times", "B", 11);
                        $pdf->Cell(110, 8, "ACADEMIC ASSESSMENT KEY", 1, 1, 'C', true);
                        $pdf->SetFont("Times", "", 11);
                        // ROW
                        $pdf->Cell(90, 8, "", 0, 0, 'L', false);
                        $pdf->Cell(60, 8, "Subject Score", 1, 0, 'C', true);
                        $pdf->Cell(50, 8, $table_titles_head, 1, 1, 'C', true);

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "4", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Exceeding Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "3", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Approaching Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "2", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Meeting Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "1", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Approaching Expectation", 1, 1, 'C');

                        // ROW
                        $pdf->SetFont("Times", "", 11);
                        $pdf->Cell(90, 8, "", 0, 0, 'C');
                        $pdf->Cell(60, 8, "A", 1, 0, 'C');
                        $pdf->Cell(50, 8, "Absent", 1, 1, 'C');
                    }
                }
                
                $pdf->Ln(5);
                $pdf->Cell(90, 0, "");
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(90, 6, "Director`s Comment", 0, 1, 'C');
                // $pdf->Cell(60, 16, "Directors Comments:", 1, 0, 'L', true);
                $pdf->Cell(10, 8, "", 0, 0, 'C');
                $pdf->Cell(50, 16, "Name: " . ucwords(strtolower($_SESSION['admin_name'])) . "", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);

                // $X = $pdf->GetX();
                // $Y = $pdf->GetY()-8;
                // $pdf->SetXY($X, $Y);
                // catch the student comments
                $student_comments = "";
                // echo $directors_comments;
                $all_comments = $directors_comments;
                $all_comments = json_decode($all_comments);
                for ($index_comment = 0; $index_comment < count($all_comments); $index_comment++) {
                    $student_adm = $all_comments[$index_comment]->student_adm;
                    $directors_commented = $all_comments[$index_comment]->directors_commented;
                    if ($student_termly_perfomance[$index]['student_id'] == $student_adm) {
                        $student_comments = $directors_commented;
                        break;
                    }
                }
                $student_info = getStudDetail($conn2, $student_termly_perfomance[$index]['student_id']);
                // $pdf->MultiCell(200, 8, "Maya has a good understanding of her subjects however she has not settled yet in school. She was not able to complete her ETE paper this term", 1, 1, 'L');
                $string_width = $pdf->GetStringWidth($student_comments);
                if (strlen($student_comments) > 0) {
                    while ($string_width < 210) {
                        $student_comments .= " ";
                        $string_width = $pdf->GetStringWidth($student_comments);
                    }
                } else {
                    while ($string_width < 414) {
                        $student_comments .= "_";
                        $string_width = $pdf->GetStringWidth($student_comments);
                    }
                }
                $pdf->MultiCell(210, 8, editComments($student_comments, $student_info), 1, 1, 'L', false);

                $pdf->Ln(5);
                $pdf->Cell(15, 8, "", 0, 0, 'C');
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(20, 7, "Date: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(60, 7, date("M Y"), 1, 0, 'L');

                // attendance stats
                $attendances = presentStats_report($conn2, $student_termly_perfomance[$index]['student_id'], $student_info['stud_class']);
                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(30, 7, "Attendance: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(50, 7, $attendances[2] . " %", 1, 0, 'L');

                $pdf->SetFont("Times", "B", 11);
                $pdf->Cell(40, 7, "Next Term Opens: ", 1, 0, 'L', true);
                $pdf->SetFont("Times", "", 11);
                $pdf->Cell(50, 7, date("D, dS F Y", strtotime($next_yr_opening)), 1, 1, 'L');
                // break;

                // SEND EMAIL FROM HERE
                // echo $directors_comments;
                $pdf->SetTitle(className_exam($class_select) . " Termly Reports");
                // $pdf->Output();


                $file_names = date("YmdHi");
                $pdf->Output("F", "../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $student_info['adm_no'] . ".pdf");
                chmod("../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $student_info['adm_no'] . ".pdf", 0777);
                $path_of_document = "../report_cards/" . $_SESSION['dbname'] . "/" . $dated . "/" . $file_names . "_" . $student_info['adm_no'] . ".pdf";
                // take the emails and send
                $select = "SELECT * FROM `settings` WHERE `sett` = 'email_setup'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $stmt->store_result();
                $rnums = $stmt->num_rows;
                if ($rnums > 0) {
                    // contimue to send email
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $email_sets = $row['valued'];
                            $lengths = strlen($email_sets);

                            if ($lengths > 0 && isJson_report($email_sets)) {
                                // send email
                                $json_mail = json_decode($email_sets);
                                $sender_name = $json_mail->sender_name;
                                $email_host_addr = $json_mail->email_host_addr;
                                $email_username = $json_mail->email_username;
                                $email_password = $json_mail->email_password;
                                $tester_mail = $json_mail->tester_mail;

                                // send email
                                try {
                                    $mail = new PHPMailer(true);

                                    $mail->isSMTP();
                                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                                    // $mail->Host = 'smtp.gmail.com';
                                    $mail->Host = $email_host_addr;
                                    $mail->SMTPAuth = true;
                                    // $mail->Username = "hilaryme45@gmail.com";
                                    // $mail->Password = "cmksnyxqmcgtncxw";
                                    $mail->Username = $email_username;
                                    $mail->Password = $email_password;
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                    $mail->Port = 587;


                                    $mail->setFrom($email_username, $sender_name);
                                    strlen(trim($email_cc_subject_reports)) > 1 ?  $mail->addCC($email_cc_subject_reports, $sender_name) : "";

                                    // PRIMARY EMAIL ADDRESS
                                    // SECONDARY EMAIL ADDRESS
                                    $primary_email = $student_info['parent_email'];
                                    $secondary_email = $student_info['parent_email2'];

                                    // send to
                                    $send_mail_to = "";
                                    $mails_add = 0;
                                    if ($send_to_email_reports == "send_to_primary_parent") {
                                        strlen(trim($primary_email)) > 0 ? $mail->addAddress($primary_email) : "";

                                        strlen(trim($primary_email)) > 0 ? $mails_add += 1 : "";
                                        $send_mail_to = $primary_email;
                                        $email_sent_counter++;
                                    } elseif ($send_to_email_reports == "send_to_secondary_parent") {
                                        strlen(trim($secondary_email)) > 0 ? $mail->addAddress($secondary_email) : "";

                                        strlen(trim($secondary_email)) > 0 ? $mails_add += 1 : "";
                                        $send_mail_to = $secondary_email;
                                        $email_sent_counter++;
                                    } elseif ($send_to_email_reports == "send_to_both_parents") {
                                        strlen(trim($primary_email)) > 0 ? $mail->addAddress($primary_email) : "";
                                        strlen(trim($secondary_email)) > 0 ? $mail->addAddress($secondary_email) : "";

                                        strlen(trim($primary_email)) > 0 ? $mails_add += 1 : "";
                                        strlen(trim($secondary_email)) > 0 ? $mails_add += 1 : "";

                                        $send_mail_to = $primary_email . "," . $secondary_email;
                                        $email_sent_counter++;
                                        $email_sent_counter++;
                                    }
                                    // check who you are sending to
                                    // $mail->addAddress($send_mail_to);
                                    $mail->isHTML(true);
                                    $mail->Subject = $email_subject_exams_report;
                                    $email_contents_exam_reports = editComments($email_contents_exam_reports, $student_info);
                                    $mail->Body = $email_contents_exam_reports;

                                    // attach the file
                                    $mail->AddAttachment($path_of_document, '', $encoding = 'base64', $type = 'application/pdf');

                                    if ($mails_add > 0) {
                                        $mail->send();
                                    }

                                    $bcc = "null";
                                    // save the email address sent
                                    $insert = "INSERT INTO `email_address` (`sender_from`,`recipient_to`,`bcc`,`date_time`,`message_subject`,`message`,`cc`,`attachments`) VALUES (?,?,?,?,?,?,?,?)";
                                    $stmt = $conn2->prepare($insert);
                                    $dates = date("YmdHis");
                                    $stmt->bind_param("ssssssss", $email_username, $send_mail_to, $bcc, $dates, $email_subject_exams_report, $email_contents_exam_reports, $email_cc_subject_reports, $path_of_document);
                                    $stmt->execute();
                                } catch (Exception $th) {
                                    echo "<p class='text-danger p-1 border border-danger'>Error : " . $mail->ErrorInfo . "</p>";
                                }
                            } else {
                                echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
                            }
                        }
                    }
                } else {
                    echo "<p class='text-danger'>Your email has not been setup, Kindly setup your email and try again!</p>";
                }
            }
            echo "<p style='color:green;font-size:12px;'>$email_sent_counter enails have been sent successfully!, You can check the emails you have sent on the <b>Email & SMS section on the email tables section.</b></p>";
        }
    }elseif(isset($_POST['financial_performace'])){
        include_once('finance_report.php');
    }elseif(isset($_POST['generate_income_statement'])){
        include_once("../connections/conn1.php");
        include_once("../connections/conn2.php");
        require_once "../ajax/finance/financial.php";
        $year = $_POST['year'];
        // echo json_encode($student_data);
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "Income Statement of ".$year;
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(275, 8, "Date Generated : ".date("l dS M Y : h:i:sA"), 0, 0, 'L', false);
        $pdf->ln();
        // $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 10, "", 0, 0, 'C', false);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->SetFillColor(0, 112, 192);
        $pdf->Cell(50, 6, "TERM 1", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 2", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 3", 1, 0, 'C', TRUE);

        // SET THE PRIMARY INCOME
        $pdf->ln();
        $pdf->SetFont('Times', 'BU', 10);
        $pdf->Cell(40, 6, "Primary Income", 0, 0, 'L', false);
        $pdf->Cell(150, 6, "", 1, 0, 'C', false);
        $pdf->ln();

        // get the operating revenue
        // get the term incomes
        $revenue = getOtherRevenue_report($conn2,$year);
        //get the time periods between terms
        $term_arrays = getTermPeriods_report($conn2,$year);
        //get the income based on the period above
        $term_income = getTermIncome_report($term_arrays,$conn2);
        //get the expenses per term
        $term_expense = getExpenses_report($term_arrays,$conn2);
        //get all the expenses names
        $all_expenses = getAllExpenseNames_report($term_expense);
        //get taxes
        $all_taxes = getTaxes_report($term_arrays,$conn2);
        //term periods 
        $term_per = getTermPeriod_report($conn2);
        //get the current term period
        $years = date("Y");

        // operating revenue
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(40, 6, "Operating Revenue", 0, 0, 'L', false);
        for ($indes=0; $indes < count($term_income); $indes++) {
            $pdf->Cell(50, 6, "Kes ".number_format($term_income[$indes]), 1, 0, 'C', false);
        }
        $pdf->ln();
        $pdf->Cell(40, 6, "Other Income", 0, 0, 'L', false);
        $pdf->Cell(50, 6, "Kes ".number_format($revenue[0]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($revenue[1]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($revenue[2]), 1, 0, 'C', false);
        $pdf->ln();
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40, 6, "Total Income", 0, 0, 'L', false);
        for ($indes=0; $indes < count($term_income); $indes++) {
            $term_income[$indes] += $revenue[$indes];
            $pdf->Cell(50, 6, "Kes ".number_format($term_income[$indes]), 1, 0, 'C', false);
        }

        //create an array with all the expense array list
        $expenses_val = [];
        for ($index=0; $index <= count($all_expenses); $index++) { 
            if ($index == count($all_expenses)) {
                $expenses_val["Salaries"] = [];
                break;
            }else {
                $expenses_val[$all_expenses[$index]] = [];
            }
        }
        
        //get values per the period given
        $totalExpenses = [];
        for ($index=0; $index < count($term_expense); $index++) {
            //echo "term ".($index+1)." Size is ".count($term_expense[$index])."<br>";
            $total = 0;
            for ($index1=0; $index1 < count($all_expenses); $index1++) {
                if (checkPresent_report($term_expense[$index],$all_expenses[$index1])) {
                    $my_val = getValues_report($term_expense[$index],$all_expenses[$index1]);
                    //echo "- ".$all_expenses[$index1]." = ".$my_val."<br>";
                    array_push($expenses_val[$all_expenses[$index1]],$my_val);
                    $total+=($my_val*1);
                }else {
                    //echo "- ".$all_expenses[$index1]." = 0<br>";
                    array_push($expenses_val[$all_expenses[$index1]],0);
                }
            }
            array_push($totalExpenses,$total);
        }
        
        //add a category called salaries and this includes all the salaries the institution distributes
        $salaries = getSalaryExp_report($conn2,$term_arrays);
        //ADD THE SALARIES ARRAY TO THE GROUP
        array_push($all_expenses,"Salaries");
        array_push($expenses_val["Salaries"],$salaries[0],$salaries[1],$salaries[2]);
        //add the salaries value to the total value
        for ($intex=0; $intex < count($totalExpenses); $intex++) { 
            $totalExpenses[$intex]+=$salaries[$intex];
        }

        $pdf->ln();
        $pdf->ln();
        $pdf->SetFont('Times', 'BU', 11);
        $pdf->Cell(40, 6, "Expenses", 0, 0, 'L', false);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(50, 6, "TERM 1", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 2", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 3", 1, 0, 'C', TRUE);
        // $pdf->Cell(225, 6, "", 1, 0, 'L', false);
        $pdf->SetFont('Times', '', 10);
        $pdf->ln();

        for ($indexes=0; $indexes < count($all_expenses); $indexes++) {
            // expense name
            $expense_name = get_expense($all_expenses[$indexes],$conn2);

            // expense name
            $pdf->Cell(40, 6, "". ($expense_name != null ? $expense_name['expense_name'] : $all_expenses[$indexes]) ."", 0, 0, 'L', false);
            $pdf->Cell(50, 6, "Kes ".number_format($expenses_val[$all_expenses[$indexes]][0]), 1, 0, 'C', false);
            $pdf->Cell(50, 6, "Kes ".number_format($expenses_val[$all_expenses[$indexes]][1]), 1, 0, 'C', false);
            $pdf->Cell(50, 6, "Kes ".number_format($expenses_val[$all_expenses[$indexes]][2]), 1, 0, 'C', false);
            $pdf->ln();
        }

        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40, 6, "Total Expense", 0, 0, 'L', false);
        $pdf->Cell(50, 6, "Kes ".number_format($totalExpenses[0]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($totalExpenses[1]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($totalExpenses[2]), 1, 0, 'C', false);
        $pdf->ln();
        
        //deduct term expenses from term income
        $before_taxes = [];
        for ($index=0; $index < count($term_income); $index++) {
            // add before tx
            $befo_taxes = $term_income[$index] - $totalExpenses[$index];
            array_push($before_taxes,$befo_taxes);
        }
        $pdf->ln();

        // HEADER
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "", 0, 0, 'L', false);
        $pdf->Cell(50, 6, "TERM 1", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 2", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 3", 1, 1, 'C', TRUE);

        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40, 6, "Earning Before Tax", 0, 0, 'L', false);
        $pdf->Cell(50, 6, "Kes ".number_format($before_taxes[0]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($before_taxes[1]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($before_taxes[2]), 1, 0, 'C', false);
        $pdf->Ln();
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln();
        $pdf->SetFont('Times', 'BU', 11);

        $pdf->Cell(40, 6, "Taxes", 0, 0, 'L', false);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(50, 6, "TERM 1", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 2", 1, 0, 'C', TRUE);
        $pdf->Cell(50, 6, "TERM 3", 1, 0, 'C', TRUE);
        $pdf->ln();
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(40, 6, "Taxes", 0, 0, 'L', false);
        $pdf->Cell(50, 6, "Kes ".number_format($all_taxes[0]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($all_taxes[1]), 1, 0, 'C', false);
        $pdf->Cell(50, 6, "Kes ".number_format($all_taxes[2]), 1, 0, 'C', false);
        $pdf->Ln();

        //net income = income before tax - taxes
        $net_income = [];
        for ($index=0; $index < count($all_taxes); $index++) { 
            $netincome = $before_taxes[$index] - $all_taxes[$index];
            // add other revenues
            array_push($net_income,$netincome);
        }
        $pdf->SetFont('Times', 'BUI', 11);
        $pdf->Cell(40, 7, "Net Income", 0, 0, 'L', false);
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(50, 7, "Kes ".number_format($net_income[0]), 1, 0, 'C', false);
        $pdf->Cell(50, 7, "Kes ".number_format($net_income[1]), 1, 0, 'C', false);
        $pdf->Cell(50, 7, "Kes ".number_format($net_income[2]), 1, 0, 'C', false);
        $pdf->Ln();
        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
    }elseif(isset($_POST['generate_income_statement_quaterly'])){
        include_once("../connections/conn1.php");
        include_once("../connections/conn2.php");
        include_once("../ajax/finance/financial.php");
        $year = $_POST['year'];
        $prev_year = ($year*1) - 1;
        
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "Income Statement Quaterly of ".$prev_year."/".$year;
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(275, 8, "Date Generated : ".date("l dS M Y : h:i:sA"), 0, 0, 'L', false);
        $pdf->ln();
        // annual quater array
        $year_1 = ($year*1)-1;
        $annual_quaters = [];
        $q1a = date("Y-m-d",strtotime($year_1."0701"));
        $q1b = date("Y-m-d",strtotime($year_1."0930"));
        array_push($annual_quaters,[$q1a,$q1b]);
        $q2a = date("Y-m-d",strtotime($year_1."1001"));
        $q2b = date("Y-m-d",strtotime($year_1."1231"));
        array_push($annual_quaters,[$q2a,$q2b]);
        $q3a = date("Y-m-d",strtotime($year."0101"));
        $q3b = date("Y-m-d",strtotime($year."0331"));
        array_push($annual_quaters,[$q3a,$q3b]);
        $q4a = date("Y-m-d",strtotime($year."0401"));
        $q4b = date("Y-m-d",strtotime($year."0630"));
        array_push($annual_quaters,[$q4a,$q4b]);

        // get the term incomes
        $revenue = getOtherRevenueQuaterly($conn2,$year,$annual_quaters);
        
        // get the term income
        $term_income = getTermIncomeQuaterly($annual_quaters,$conn2);
        // echo json_encode($revenue);
        // return 0;
        
        // get the expenses per term
        $term_expense = getExpensesQuaterly($annual_quaters,$conn2);
        
        //get all the expenses names
        $all_expenses = getAllExpenseNames($term_expense);
        
        //get taxes
        $all_taxes = getTaxesQuaterly($annual_quaters,$conn2);
        $data_to_display = "";

        $pdf->Ln();
        $pdf->Cell(40,6,"",0,0,'C',false);
        $pdf->SetFillColor(0, 112, 192);
        $pdf->SetFont('Times', 'B', 9);
        $pdf->Cell(38,6,date("M-d-Y",strtotime($annual_quaters[0][0]))." - ".date("M-d-Y",strtotime($annual_quaters[0][1])),1,0,'C',true);
        $pdf->Cell(38,6,date("M-d-Y",strtotime($annual_quaters[1][0]))." - ".date("M-d-Y",strtotime($annual_quaters[1][1])),1,0,'C',true);
        $pdf->Cell(38,6,date("M-d-Y",strtotime($annual_quaters[2][0]))." - ".date("M-d-Y",strtotime($annual_quaters[2][1])),1,0,'C',true);
        $pdf->Cell(38,6,date("M-d-Y",strtotime($annual_quaters[3][0]))." - ".date("M-d-Y",strtotime($annual_quaters[3][1])),1,1,'C',true);
        
        $pdf->SetFont('Times', 'BU', 11);
        $pdf->Cell(40,6,"Income",0,0,"L");
        $pdf->Cell(152,6,"",1,1,"L");
        $pdf->SetFont('Times', '', 10);

        // primary income
        $pdf->Cell(40,7,"Primary Income",0,0);
        for ($indes=0; $indes < count($term_income); $indes++) {
            $pdf->Cell(38,7,"Ksh ".number_format($term_income[$indes])."",1,0,"L");
        }
        $pdf->Ln();
        $pdf->Cell(40,7,"Other Income",0,0);
        $pdf->Cell(38,7,"Ksh ".number_format($revenue[0])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($revenue[1])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($revenue[2])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($revenue[3])."",1,0);
        $pdf->Ln();

        //total the income
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40,7,"Total Income",0,0);
        for ($indes=0; $indes < count($term_income); $indes++) {
            $term_income[$indes] += $revenue[$indes];
            $pdf->Cell(38,7,"Ksh ".number_format($term_income[$indes])."",1,0);
        }
        $pdf->Ln();

        $pdf->SetFont('Times', 'BU', 11);
        $pdf->Cell(40,6,"Expenses",0,0,"L");
        $pdf->Cell(152,6,"",1,1,"L");
        $pdf->SetFont('Times', '', 10);

        //create an array with all the expense array list
        $expenses_val = [];
        for ($index=0; $index <= count($all_expenses); $index++) { 
            if ($index == count($all_expenses)) {
                $expenses_val["Salaries"] = [];
                break;
            }else {
                $expenses_val[$all_expenses[$index]] = [];
            }
        }

        //get values per the period given
        $totalExpenses = [];
        for ($index=0; $index < count($term_expense); $index++) {
            //echo "term ".($index+1)." Size is ".count($term_expense[$index])."<br>";
            $total = 0;
            for ($index1=0; $index1 < count($all_expenses); $index1++) {
                if (checkPresent($term_expense[$index],$all_expenses[$index1])) {
                    $my_val = getValues($term_expense[$index],$all_expenses[$index1]);
                    //echo "- ".$all_expenses[$index1]." = ".$my_val."<br>";
                    array_push($expenses_val[$all_expenses[$index1]],$my_val);
                    $total+=($my_val*1);
                }else {
                    //echo "- ".$all_expenses[$index1]." = 0<br>";
                    array_push($expenses_val[$all_expenses[$index1]],0);
                }
            }
            array_push($totalExpenses,$total);
        }
        

        //add a category called salaries and this includes all the salaries the institution distributes
        $salaries = getSalaryExpQuaterly($conn2,$annual_quaters);
        //ADD THE SALARIES ARRAY TO THE GROUP
        array_push($all_expenses,"Salaries");
        array_push($expenses_val["Salaries"],$salaries[0],$salaries[1],$salaries[2],$salaries[3]);
        //add the salaries value to the total value
        for ($intex=0; $intex < count($totalExpenses); $intex++) { 
            $totalExpenses[$intex]+=$salaries[$intex];
        }

        for ($indexes=0; $indexes < count($all_expenses); $indexes++) {
            // expense name
            $expense_name = get_expense($all_expenses[$indexes],$conn2);

            $pdf->Cell(40,7,($expense_name != null ? $expense_name['expense_name'] : $all_expenses[$indexes]),0,0);
            $pdf->Cell(38,7,"Ksh ".number_format($expenses_val[$all_expenses[$indexes]][0])."",1,0);
            $pdf->Cell(38,7,"Ksh ".number_format($expenses_val[$all_expenses[$indexes]][1])."",1,0);
            $pdf->Cell(38,7,"Ksh ".number_format($expenses_val[$all_expenses[$indexes]][2])."",1,0);
            $pdf->Cell(38,7,"Ksh ".number_format($expenses_val[$all_expenses[$indexes]][3])."",1,1);
        }
        
        //TOTAL ALL THE EXPENSES
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40,7,"Total Expenses",0,0);
        $pdf->Cell(38,7,"Ksh ".number_format($totalExpenses[0])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($totalExpenses[1])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($totalExpenses[2])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($totalExpenses[3])."",1,1);

        //CALCULATE EARNINGS BEFORE TAXES
        //deduct term expenses from term income
        $before_taxes = [];
        for ($index=0; $index < count($term_income); $index++) {
            // add other revenue

            // add before tx
            $befo_taxes = $term_income[$index] - $totalExpenses[$index];
            array_push($before_taxes,$befo_taxes);
        }
        $pdf->Cell(40,6,"",0,0,"L");
        $pdf->Cell(152,6,"",1,1,"L");
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40,7,"Earning before Tax",0,0);
        $pdf->Cell(38,7,"Ksh ".number_format($before_taxes[0])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($before_taxes[1])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($before_taxes[2])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($before_taxes[3])."",1,1);

        // earnings before tax
        $pdf->Cell(40,6,"Tax",0,0,"L");
        $pdf->Cell(152,6,"",1,1,"L");
        $pdf->SetFont('Times', '', 10);

        $pdf->Cell(40,7,"Taxes",0,0);
        $pdf->Cell(38,7,"Ksh ".number_format($all_taxes[0])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($all_taxes[1])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($all_taxes[2])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($all_taxes[3])."",1,1);
        
        //GET THE NET INCOME
        //net income = income before tax - taxes
        $net_income = [];
        for ($index=0; $index < count($all_taxes); $index++) { 
            $netincome = $before_taxes[$index] - $all_taxes[$index];
            // add other revenues
            array_push($net_income,$netincome);
        }
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(40,6,"Net Income",0,0,"L");
        $pdf->Cell(38,7,"Ksh ".number_format($net_income[0])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($net_income[1])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($net_income[2])."",1,0);
        $pdf->Cell(38,7,"Ksh ".number_format($net_income[3])."",1,1);
        $pdf->Output();
    }elseif(isset($_POST['generate_annual'])){
        include_once("../connections/conn1.php");
        include_once("../connections/conn2.php");
        include_once("../ajax/finance/financial.php");

        // echo json_encode($_POST);
        // return 0;
        // report type
        $report_type = $_POST['report_type'];

        // get the current and the previous financial year
        $year = date("Y", strtotime($_POST['year']."0101")) * 1;
        if($report_type == "annual_report"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
            
            $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
            $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_sep"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-09-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-09-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-09-30"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_dec"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-12-31"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-12-31"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-12-31"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_mar"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime((($current_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($current_financial_year[1]*1))."-03-31"))];
            $prev_year = [date("Ymd",strtotime((($previous_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($previous_financial_year[1]*1))."-03-31"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-03-31"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }else{
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
            
            $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
            $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
        }
        
        // start getting the revenue catgories present
        $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $revenue_category = [];
        if ($result) {
            if($row = $result->fetch_assoc()){
                $revenue_category = json_decode($row['valued']);
            }
        }

        // start with operating activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '1' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year operating activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_operating_activities = [];
        $operating_revenue_categories = [];
        $fees_id = 2000;
        $fees_category_added = false;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($curr_operating_activities,$row);
            }
        }

        // get the fees for this year
        $student_fees = "SELECT COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($curr_operating_activities,$fees_category);

                $revenue = new stdClass();
                $revenue->category_id = $fees_id;
                $revenue->category_name = "Rendering of services- fees from students";
                array_push($operating_revenue_categories,$revenue);
                $fees_category_added = true;
            }
        }

        // operating revenue previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_operating_activities = [];
        // $max_id = 0;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($prev_operating_activities,$row);
            }
        }

        // get the previous year student fees
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($prev_operating_activities,$fees_category);

                if(!$fees_category_added){
                    $revenue = new stdClass();
                    $revenue->category_id = $fees_id;
                    $revenue->category_name = "Rendering of services- fees from students";
                    array_push($operating_revenue_categories,$revenue);
                }
            }
        }

        // operating revenue previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_operating_activities_1 = [];
        // $max_id = 0;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($prev_operating_activities_1,$row);
            }
        }

        // get the previous year student fees
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($prev_operating_activities_1,$fees_category);

                if(!$fees_category_added){
                    $revenue = new stdClass();
                    $revenue->category_id = $fees_id;
                    $revenue->category_name = "Rendering of services- fees from students";
                    array_push($operating_revenue_categories,$revenue);
                }
            }
        }
        // echo $fees_id;

        // start with investing activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '2' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year investing activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_investing_activities = [];
        $investing_revenue_categories = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($curr_investing_activities,$row);
            }
        }

        // operating investing previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_investing_activities = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($prev_investing_activities,$row);
            }
        }

        // operating investing previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_investing_activities_1 = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($prev_investing_activities_1,$row);
            }
        }

        // start with financing activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '3' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year financing activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_financing_activities = [];
        $financing_revenue_categories = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($curr_financing_activities,$row);
            }
        }

        // financing activity previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_finance_activities = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($prev_finance_activities,$row);
            }
        }

        // financing activity previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_finance_activities_1 = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($prev_finance_activities_1,$row);
            }
        }

        // get the operating expenses of the previous years and this year
        $curr_year_operating_expenses = [];
        $operating_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '1' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_operating_expenses,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_operating_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_operating_expenses,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the second previous years
        $prev_year_operating_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_operating_expenses_1,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }


        // get the operating expenses of the previous years and this year
        $curr_year_investing_expenses = [];
        $investing_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '2' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_investing_expenses,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_investing_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_investing_expenses,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_investing_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_investing_expenses_1,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the operating expenses of the previous years and this year
        $curr_year_financing_expenses = [];
        $financing_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '3' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_financing_expenses,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_financing_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_financing_expenses,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_financing_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_financing_expenses_1,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // display the data now since its ready
        $current_acad_year = [date("Y-m-d",strtotime($curr_year[0]."-07-01")),date("Y-m-d",strtotime($curr_year[1]."-06-30"))];
        $previous_acad_year = [date("Y-m-d",strtotime($prev_year[0]."-07-01")),date("Y-m-d",strtotime($prev_year[1]."-06-30"))];
        // echo json_encode($previous_acad_year[0]);
        // echo json_encode($student_data);
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "Statement of Cashflow From ".date("D dS M Y",strtotime($curr_year[0]."-06-30"))." to year end of ".date("dS M",strtotime($curr_year[1]))." ".date("Y",strtotime($current_acad_year[1])).".";
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 10);
        // $pdf->Cell(275, 10, "Date Generated : ".date("l dS M Y"), 0, 0, 'L', false);
        $pdf->ln();
        $pdf->Cell(190, 8, "Date Generated : ".date("l dS M Y : h:i:sA"), 0, 1, 'L',false);

        // create the table header
        $pdf->SetFillColor(0, 112, 192);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(100, 8, "Description", 1, 0, 'C',true);
        $pdf->Cell(45, 8, $current_display_year, 1, 0, 'C',true);
        $pdf->Cell(45, 8, $previous_display_year, 1, 1, 'C',true);
        $pdf->Cell(100, 8, "", 1, 0, 'C',true);
        $pdf->Cell(45, 8, "Kes", 1, 0, 'C',true);
        $pdf->Cell(45, 8, "Kes", 1, 1, 'C',true);

        // display Cashflow from Operating Activities
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow from Operating Activities", 1, 1, 'L',false);
        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;

        // Net increase/(decrease) in cash and cash equivalents
        $net_increase_curr_year = 0;
        $net_increase_prev_year = 0;
        $net_increase_prev_year_1 = 0;
        $pdf->SetFillColor(216, 217, 218);
        $fill = true;
        $pdf->SetFont('Times', '', 10);
        if(count($operating_revenue_categories) > 0 ){
            foreach($operating_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                // get the current year
                foreach ($curr_operating_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_operating_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_operating_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }

                $pdf->Cell(100, 6, $index.". ".$value->category_name, 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
                // $fill = !$fill;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No cash flow from operating activities record!", 1, 1, 'L',false);
        }
        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous), 1, 1, 'L',false);

        // cash flow used in operating activity
                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow Used in Operating Activity", 1, 1, 'L',false);
        $pdf->SetFont('Times', '', 10);

        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;
        if(count($operating_expense_categories) > 0 ){
            foreach($operating_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_operating_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_operating_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }
                // get the previous year
                foreach ($prev_year_operating_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                
                // expense name
                $expense_name = get_expense($value,$conn2);

                $pdf->Cell(100, 6, $index.". ".($expense_name != null ? $expense_name['expense_name'] : $value), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No Operating Activity Expenses!", 1, 1, 'L',false);
        }
        $pdf->SetFont('Times', 'BI', 10);

        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous_expense), 1, 1, 'L',false);

        $pdf->Cell(100, 6, "Net Cashflow From Operating Revenue", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current - $total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous  - $total_previous_expense), 1, 1, 'L',false);

        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow From Investing Activities", 1, 1, 'L',false);

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);

        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;
        $pdf->SetFont('Times', '', 10);
        if(count($investing_revenue_categories) > 0 ){
            foreach($investing_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_investing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_investing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_investing_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }

                $pdf->Cell(100, 6, $index.". ".ucwords(strtolower($value->category_name)), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No Cashflow from Investing Activities!", 1, 1, 'L',false);
        }

        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous), 1, 1, 'L',false);

        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow Used in Investing Activity", 1, 1, 'L',false);
                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;
        
        $pdf->SetFont('Times', '', 10);
        if(count($investing_expense_categories) > 0 ){
            foreach($investing_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_investing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_investing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_investing_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                                
                // expense name
                $expense_name = get_expense($value,$conn2);
                
                $pdf->Cell(100, 6, $index.". ".($expense_name != null ? $expense_name['expense_name'] : $value), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No Investing Activity Expenses!", 1, 1, 'L',false);
        }
        $pdf->SetFont('Times', 'BI', 10);

        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous_expense), 1, 1, 'L',false);

        $pdf->Cell(100, 6, "Net Cashflow From Investing Revenue", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current - $total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous  - $total_previous_expense), 1, 1, 'L',false);

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);

        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow from Financing Activities", 1, 1, 'L',false);
                    
        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;
        $pdf->SetFont('Times', '', 10);
        if(count($financing_revenue_categories) > 0 ){
            foreach($financing_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_financing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_finance_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_finance_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }
                
                $pdf->Cell(100, 6, $index.". ".ucwords(strtolower($value->category_name)), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No Financing Activity records!", 1, 1, 'L',false);
        }

        $pdf->SetFont('Times', 'BI', 10);
        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous), 1, 1, 'L',false);

        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(190, 6, "Cashflow Used in Financing Activity", 1, 1, 'L',false);
                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;

        // echo json_encode($operating_expense_categories);
        // return 0;
        $pdf->SetFont('Times', '', 10);
        if(count($financing_expense_categories) > 0 ){
            foreach($financing_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_financing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_financing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_financing_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                                
                // expense name
                $expense_name = get_expense($value,$conn2);
                
                $pdf->Cell(100, 6, $index.". ".($expense_name != null ? $expense_name['expense_name'] : $value), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($current_year), 1, 0, 'L',$fill);
                $pdf->Cell(45, 6, "Ksh ".number_format($previous_year), 1, 1, 'L',$fill);
                $index++;
            }
        }else{
            $pdf->SetFont('Times', '', 10);
            $pdf->Cell(190, 6, "No Financing Activity Expenses!", 1, 1, 'L',false);
        }
        $pdf->SetFont('Times', 'BI', 10);

        $pdf->Cell(100, 6, "Total", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous_expense), 1, 1, 'L',false);

        $pdf->Cell(100, 6, "Net Cashflow From Financing Revenue", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_current - $total_current_expense), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($total_previous  - $total_previous_expense), 1, 1, 'L',false);

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);

        $pdf->Cell(100, 6, "Net increase/(decrease) in cash and cash equivalents", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_curr_year), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_prev_year), 1, 1, 'L',false);

        $pdf->SetFont('Times', 'I', 10);
        $pdf->Cell(190, 6, "Cash and Cash Equivalents at the Beginning and End of the Period", 1, 1, 'L',false);

        $pdf->Cell(100, 6, "Cash and Cash Equivalents at the Beginning of the Period", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_prev_year+$net_increase_prev_year_1), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_prev_year_1), 1, 1, 'L',false);

        $pdf->Cell(100, 6, "Cash and Cash Equivalents at the end of the Period", 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_prev_year_1+$net_increase_prev_year+$net_increase_curr_year), 1, 0, 'L',false);
        $pdf->Cell(45, 6, "Ksh ".number_format($net_increase_prev_year_1+$net_increase_prev_year), 1, 1, 'L',false);
        $pdf->Output();
    
    }elseif(isset($_POST['generate_annual_excel'])){
        include_once("../connections/conn1.php");
        include_once("../connections/conn2.php");
        include_once("../ajax/finance/financial.php");
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','prev_year','S','T','U','V','W','X','Y','Z'];
        $table_style = [
            'font' => [
                'bold' => true,
                'name' => 'Calibri Light',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF9DB7B8',
                ],
            ],
        ];
        $table_style_2 = [
            'font' => [
                'bold' => true,
                'name' => 'Times New Roman',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $table_style_3 = [
            'font' => [
                'bold' => true,
                'italic' => false,
                'underline' => false,
                'name' => 'Times New Roman',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF0070C0',
                ],
            ],
        ];
        $just_border = [
            'font' => [
                'name' => 'Times New Roman',
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFD8D9DA',
                ],
            ],
        ];
        $error_border = [
            'font' => [
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'name' => 'Times New Roman',
                'color' => ['argb' => 'FFFF0000'],
            ],
            // 'alignment' => [
            //     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            //     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            // ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $total_expenses = [
            'font' => [
                'bold' => true,
                'italic' => false,
                'underline' => false,
                'name' => 'Times New Roman',
                // 'color' => ['argb' => 'FFFF0000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            // 'fill' => [
            //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            //     'startColor' => [
            //         'argb' => 'FF0070C0',
            //     ],
            // ],
        ];
        $total_expenses_i = [
            'font' => [
                'bold' => true,
                'italic' => true,
                'underline' => false,
                'name' => 'Times New Roman',
                // 'color' => ['argb' => 'FFFF0000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        // echo json_encode($_POST);
        // return 0;
        // report type
        $report_type = $_POST['report_type'];

        // get the current and the previous financial year
        $year = date("Y", strtotime($_POST['year']."0101")) * 1;
        if($report_type == "annual_report"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
            
            $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
            $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_sep"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-09-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-09-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-09-30"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_dec"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[1]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-12-31"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[1]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-12-31"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[1]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-12-31"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }elseif($report_type == "quarterly_report_mar"){
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime((($current_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($current_financial_year[1]*1))."-03-31"))];
            $prev_year = [date("Ymd",strtotime((($previous_financial_year[0]*1))."-07-01")),date("Ymd",strtotime((($previous_financial_year[1]*1))."-03-31"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-03-31"))];
            // echo json_encode($curr_year);
            // return 0;
            $current_display_year = date("M dS Y",strtotime($curr_year[1]));
            $previous_display_year = date("M dS Y",strtotime($prev_year[1]));
        }else{
            $year_1 = ($year*1 - 1);
            $year_2 = ($year_1*1 - 1);
            $year_3 = ($year_2*1 - 1);
            $previous_financial_year_1 = [$year_3,$year_2];
            $previous_financial_year = [$year_2,$year_1];
            $current_financial_year = [$year_1,$year];
            $curr_year = [date("Ymd",strtotime($current_financial_year[0]."-07-01")),date("Ymd",strtotime($current_financial_year[1]."-06-30"))];
            $prev_year = [date("Ymd",strtotime($previous_financial_year[0]."-07-01")),date("Ymd",strtotime($previous_financial_year[1]."-06-30"))];
            $prev_year_1 = [date("Ymd",strtotime($previous_financial_year_1[0]."-07-01")),date("Ymd",strtotime($previous_financial_year_1[1]."-06-30"))];
            
            $current_display_year = date("Y",strtotime($curr_year[0]))."/".date("Y",strtotime($curr_year[1]));
            $previous_display_year = date("Y",strtotime($prev_year[0]))."/".date("Y",strtotime($prev_year[1]));
        }
        
        // start getting the revenue catgories present
        $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories';";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $revenue_category = [];
        if ($result) {
            if($row = $result->fetch_assoc()){
                $revenue_category = json_decode($row['valued']);
            }
        }

        // start with operating activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '1' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year operating activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_operating_activities = [];
        $operating_revenue_categories = [];
        $fees_id = 2000;
        $fees_category_added = false;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($curr_operating_activities,$row);
            }
        }

        // get the fees for this year
        $student_fees = "SELECT COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($curr_operating_activities,$fees_category);

                $revenue = new stdClass();
                $revenue->category_id = $fees_id;
                $revenue->category_name = "Rendering of services- fees from students";
                array_push($operating_revenue_categories,$revenue);
                $fees_category_added = true;
            }
        }

        // operating revenue previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_operating_activities = [];
        // $max_id = 0;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($prev_operating_activities,$row);
            }
        }

        // get the previous year student fees
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($prev_operating_activities,$fees_category);

                if(!$fees_category_added){
                    $revenue = new stdClass();
                    $revenue->category_id = $fees_id;
                    $revenue->category_name = "Rendering of services- fees from students";
                    array_push($operating_revenue_categories,$revenue);
                }
            }
        }

        // operating revenue previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_operating_activities_1 = [];
        // $max_id = 0;
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($operating_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($operating_revenue_categories,$revenue);
                }
                array_push($prev_operating_activities_1,$row);
            }
        }

        // get the previous year student fees
        $stmt = $conn2->prepare($student_fees);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            if($row = $result->fetch_assoc()){
                $fees_category = [];
                $fees_category['revenue_category'] = $fees_id;
                $fees_category['Records'] = $row['Records'];
                $fees_category['Total'] = $row['Total'] == null ? 0 : $row['Total']*1;
                $fees_category['revenue_category_name'] = "Rendering of services- fees from students";
                array_push($prev_operating_activities_1,$fees_category);

                if(!$fees_category_added){
                    $revenue = new stdClass();
                    $revenue->category_id = $fees_id;
                    $revenue->category_name = "Rendering of services- fees from students";
                    array_push($operating_revenue_categories,$revenue);
                }
            }
        }
        // echo $fees_id;

        // start with investing activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '2' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year investing activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_investing_activities = [];
        $investing_revenue_categories = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($curr_investing_activities,$row);
            }
        }

        // operating investing previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_investing_activities = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($prev_investing_activities,$row);
            }
        }

        // operating investing previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_investing_activities_1 = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($investing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($investing_revenue_categories,$revenue);
                }
                array_push($prev_investing_activities_1,$row);
            }
        }

        // start with financing activities
        $select = "SELECT `revenue_category` ,COUNT(*) AS 'Records', SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `reportable_status` = '1' AND `cash_flow_activities` = '3' AND `date_recorded` BETWEEN ? AND ? GROUP BY `revenue_category`;";
        
        // current year financing activities
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $curr_financing_activities = [];
        $financing_revenue_categories = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($curr_financing_activities,$row);
            }
        }

        // financing activity previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_finance_activities = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($prev_finance_activities,$row);
            }
        }

        // financing activity previous year
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        $prev_finance_activities_1 = [];
        if ($result) {
            while($row = $result->fetch_assoc()){
                $row['revenue_category_name'] = "N/A";
                foreach ($revenue_category as $key => $value) {
                    if($value->category_id == $row['revenue_category']){
                        $row['revenue_category_name'] = $value->category_name;
                    }
                }
                if(!check_revenue_category($financing_revenue_categories,$row['revenue_category'])){
                    $revenue = new stdClass();
                    $revenue->category_id = $row['revenue_category'];
                    $revenue->category_name = $row['revenue_category_name'];
                    array_push($financing_revenue_categories,$revenue);
                }
                array_push($prev_finance_activities_1,$row);
            }
        }

        // get the operating expenses of the previous years and this year
        $curr_year_operating_expenses = [];
        $operating_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '1' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_operating_expenses,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_operating_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_operating_expenses,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the second previous years
        $prev_year_operating_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_operating_expenses_1,$row);
                if(!in_array($row['exp_category'],$operating_expense_categories)){
                    array_push($operating_expense_categories,$row['exp_category']);
                }
            }
        }


        // get the operating expenses of the previous years and this year
        $curr_year_investing_expenses = [];
        $investing_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '2' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_investing_expenses,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_investing_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_investing_expenses,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_investing_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_investing_expenses_1,$row);
                if(!in_array($row['exp_category'],$investing_expense_categories)){
                    array_push($investing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the operating expenses of the previous years and this year
        $curr_year_financing_expenses = [];
        $financing_expense_categories = [];
        $select = "SELECT `exp_category`, COUNT(*) AS 'count_expense_category', SUM(`exp_amount`) AS 'expense_amount' FROM `expenses`  WHERE `expense_categories` = '3' AND `expense_date` BETWEEN ? AND ? GROUP BY `exp_category`";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$curr_year[0],$curr_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($curr_year_financing_expenses,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_financing_expenses = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year[0],$prev_year[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_financing_expenses,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // get the previoud years
        $prev_year_financing_expenses_1 = [];
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$prev_year_1[0],$prev_year_1[1]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()){
                array_push($prev_year_financing_expenses_1,$row);
                if(!in_array($row['exp_category'],$financing_expense_categories)){
                    array_push($financing_expense_categories,$row['exp_category']);
                }
            }
        }

        // display the data now since its ready
        $current_acad_year = [date("Y-m-d",strtotime($curr_year[0]."-07-01")),date("Y-m-d",strtotime($curr_year[1]."-06-30"))];
        $previous_acad_year = [date("Y-m-d",strtotime($prev_year[0]."-07-01")),date("Y-m-d",strtotime($prev_year[1]."-06-30"))];
        
        
        $tittle = "Statement of Cashflow From ".date("D dS M Y",strtotime($curr_year[0]."-06-30"))." to year end of ".date("dS M",strtotime($curr_year[1]))." ".date("Y",strtotime($current_acad_year[1])).".";
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Set document properties
        $small_title = "Test Title";
        $spreadsheet->getProperties()->setCreator($_SESSION['username'])
            ->setLastModifiedBy($_SESSION['username'])
            ->setTitle($small_title)
            ->setSubject($small_title)
            ->setDescription($_SESSION['username']." ".$tittle);

        // worksheet
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle(substr(date("dS M",strtotime($curr_year[1]))." ".date("Y",strtotime($current_acad_year[1])),0,31));
        $worksheet->setCellValue("A1","Description");
        $worksheet->setCellValue("B1",$current_display_year);
        $worksheet->setCellValue("C1",$previous_display_year);
        $worksheet->setCellValue("B2","Kes");
        $worksheet->setCellValue("C2","Kes");

        $cellRange = 'A1:C1'; // Adjust the cell range as needed
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_3);

        $cellRange = 'B2:C2'; // Adjust the cell range as needed
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_3);

        // MERGE CELL AI:A2
        $worksheet->mergeCells("A1:A2");
        
        // CASHFLOW OPERATING ACTIVITIES
        $worksheet->setCellValue("A4", "Cashflow from Operating Activities");
        
        // MERGE CELLS
        $worksheet->mergeCells("A4:C4");
        $cellRange = 'A4:C4';

        // Adjust the cell range as needed
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;

        // Net increase/(decrease) in cash and cash equivalents
        $net_increase_curr_year = 0;
        $net_increase_prev_year = 0;
        $net_increase_prev_year_1 = 0;
        $cell_index = 5;
        if(count($operating_revenue_categories) > 0 ){
            foreach($operating_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_operating_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_operating_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_operating_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }
                
                $worksheet->setCellValue("A".$cell_index,$value->category_name);
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $index++;
                $cell_index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No cash flow from operating activities record!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }

        // worksheet
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current));
        $worksheet->setCellValue("C".$cell_index,($total_previous));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;
        $worksheet->setCellValue("A".$cell_index,"Cashflow Used in Operating Activity");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;

        if(count($operating_expense_categories) > 0 ){
            foreach($operating_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_operating_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_operating_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }
                // get the previous year
                foreach ($prev_year_operating_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                                
                // expense name
                $expense_name = get_expense($value,$conn2);
                
                $worksheet->setCellValue("A".$cell_index,$index.". ". ($expense_name != null ? $expense_name['expense_name'] : $value));
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $cell_index++;
                $index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No Operating Activity Expenses!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }
                
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
                
        $worksheet->setCellValue("A".$cell_index,"Net Cashflow From Operating Revenue");
        $worksheet->setCellValue("B".$cell_index,($total_current - $total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous  - $total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

        $worksheet->setCellValue("A".$cell_index,"Cashflow from Investing Activities");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);

        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;
        

        if(count($investing_revenue_categories) > 0 ){
            foreach($investing_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_investing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_investing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_investing_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }
                
                $worksheet->setCellValue("A".$cell_index,$index.". ".ucwords(strtolower($value->category_name)));
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $cell_index++;
                $index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No Cashflow from Investing Activities!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }
                
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current));
        $worksheet->setCellValue("C".$cell_index,($total_previous));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

        $worksheet->setCellValue("A".$cell_index,"Cashflow Used in Investing Activity");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;
                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;

        if(count($investing_expense_categories) > 0 ){
            foreach($investing_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_investing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_investing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_investing_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                                
                // expense name
                $expense_name = get_expense($value,$conn2);
                
                $worksheet->setCellValue("A".$cell_index,$index.". ".($expense_name != null ? $expense_name['expense_name'] : $value));
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $cell_index++;
                $index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No Investing Activity Expenses!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }
                
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
                
        $worksheet->setCellValue("A".$cell_index,"Net Cashflow from Investing Activity");
        $worksheet->setCellValue("B".$cell_index,($total_current - $total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous  - $total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);

        $worksheet->setCellValue("A".$cell_index,"Cashflow from Financing Activities");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;
                    
        $index = 1;
        $total_current = 0;
        $total_previous = 0;
        $total_previous_1 = 0;
        if(count($financing_revenue_categories) > 0 ){
            foreach($financing_revenue_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_financing_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value->category_id) {
                        $current_year = $key_value['Total'];
                        $total_current += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_finance_activities as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year = $key_value['Total'];
                        $total_previous+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_finance_activities_1 as $key_activity => $key_value) {
                    if ($key_value['revenue_category'] == $value) {
                        $previous_year_1 = $key_value['Total'];
                        $total_previous_1+=$previous_year_1;
                    }
                }
                
                $worksheet->setCellValue("A".$cell_index,$index.". ".ucwords(strtolower($value->category_name)));
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $cell_index++;
                $index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No Financing Activity records!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }
                
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current));
        $worksheet->setCellValue("C".$cell_index,($total_previous));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;
        
        $worksheet->setCellValue("A".$cell_index,"Cashflow Used in Financing Activity");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;
                    
        $index = 1;
        $total_current_expense = 0;
        $total_previous_expense = 0;
        $total_previous_expense_1 = 0;
        
        if(count($financing_expense_categories) > 0 ){
            foreach($financing_expense_categories as $key => $value){
                $current_year = 0;
                $previous_year = 0;
                $previous_year_1 = 0;
                // get the current year
                foreach ($curr_year_financing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $current_year = $key_value['expense_amount'];
                        $total_current_expense += $current_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_financing_expenses as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year = $key_value['expense_amount'];
                        $total_previous_expense+=$previous_year;
                    }
                }

                // get the previous year
                foreach ($prev_year_financing_expenses_1 as $key_activity => $key_value) {
                    if ($key_value['exp_category'] == $value) {
                        $previous_year_1 = $key_value['expense_amount'];
                        $total_previous_expense_1+=$previous_year_1;
                    }
                }
                
                $worksheet->setCellValue("A".$cell_index,$index.". ".$value);
                $worksheet->setCellValue("B".$cell_index,($current_year));
                $worksheet->setCellValue("C".$cell_index,($previous_year));
                $cellRange = "A".$cell_index.":C".$cell_index;
                $worksheet->getStyle($cellRange)->applyFromArray($just_border);
                $cell_index++;
                $index++;
            }
        }else{
            $worksheet->setCellValue("A".$cell_index,"No Financing Activity Expenses!");
            $cellRange = "A".$cell_index.":C".$cell_index;
            $worksheet->mergeCells($cellRange);
            $worksheet->getStyle($cellRange)->applyFromArray($error_border);
            $cell_index++;
        }
                
        $worksheet->setCellValue("A".$cell_index,"Total");
        $worksheet->setCellValue("B".$cell_index,($total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;
                
        $worksheet->setCellValue("A".$cell_index,"Net Cashflow From Financing Revenue");
        $worksheet->setCellValue("B".$cell_index,($total_current - $total_current_expense));
        $worksheet->setCellValue("C".$cell_index,($total_previous  - $total_previous_expense));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

        $net_increase_curr_year+=($total_current - $total_current_expense);
        $net_increase_prev_year+=($total_previous  - $total_previous_expense);
        $net_increase_prev_year_1+=($total_previous_1  - $total_previous_expense_1);
                
        $worksheet->setCellValue("A".$cell_index,"Net increase/(decrease) in cash and cash equivalents");
        $worksheet->setCellValue("B".$cell_index,($net_increase_curr_year));
        $worksheet->setCellValue("C".$cell_index,($net_increase_prev_year));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses);
        $cell_index++;
        $cell_index++;

        $worksheet->setCellValue("A".$cell_index,"Cash and Cash Equivalents at the Beginning and End of the Period");
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->mergeCells($cellRange);
        $worksheet->getStyle($cellRange)->applyFromArray($table_style_2);
        $cell_index++;
                
        $worksheet->setCellValue("A".$cell_index,"Cash and Cash Equivalents at the Beginning of the Period");
        $worksheet->setCellValue("B".$cell_index,($net_increase_prev_year+$net_increase_prev_year_1));
        $worksheet->setCellValue("C".$cell_index,($net_increase_prev_year_1));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses_i);
        $cell_index++;
        
        $worksheet->setCellValue("A".$cell_index,"Cash and Cash Equivalents at the End of the Period");
        $worksheet->setCellValue("B".$cell_index,($net_increase_prev_year_1+$net_increase_prev_year+$net_increase_curr_year));
        $worksheet->setCellValue("C".$cell_index,($net_increase_prev_year_1+$net_increase_prev_year));
        $cellRange = "A".$cell_index.":C".$cell_index;
        $worksheet->getStyle($cellRange)->applyFromArray($total_expenses_i);
        $cell_index++;
        // set auto width
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            // set auto width
            for ($indexing=0; $indexing < 3; $indexing++) {
                $worksheet->getColumnDimension($letters[$indexing])->setAutoSize(true);
            }
        }
        

        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');;
        header('Content-Disposition: attachment;filename="'.$tittle.' '.date("YmdHis").'.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
        exit;
        $pdf->Output();
    }elseif(isset($_POST['print_statement_of_account'])){
        // echo json_encode($_POST);
        include("../connections/conn1.php");
        include("../connections/conn2.php");
        include_once("../ajax/finance/financial.php");
        $asset_id = $_POST['asset_id'];
        
        // get the statement of accounts
        $select = "SELECT * FROM `asset_table` WHERE `asset_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$asset_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $present = false;
        $asset_table = [];
        if($result){
            if($row = $result->fetch_assoc()){
                $present = true;
                $asset_table = $row;
            }
        }

        // check if the asset table is present
        if(!$present){
            echo "<p style='color:red;'>The asset cannot be found!</p>";
            return 0;
        }

        // proceed and print the asset account
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $title = "Asset \"(".ucwords(strtolower($asset_table['asset_name'])).")\" Account Statement";
        $pdf->set_document_title($title);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();

        // add the account details
        $asset_data = get_current_value($asset_table);

        // echo json_encode($asset_data);
        // row 1
        $pdf->SetFont('Times', 'BU', 10);
        $pdf->Cell(45, 6, "Asset Details: ", 0, 'B', 'L',false);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Asset Name: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, ucwords(strtolower($asset_table['asset_name'])), 0,1);
        
        // get the asset category
        $asset_category = "N.A";
        if($asset_table['asset_category'] == "1"){
            $asset_category = "Land";
        }elseif($asset_table['asset_category'] == "2"){
            $asset_category = "Buildings";
        }elseif($asset_table['asset_category'] == "3"){
            $asset_category = "Motor Vehicle";
        }elseif($asset_table['asset_category'] == "4"){
            $asset_category = "Furniture & Fittings";
        }elseif($asset_table['asset_category'] == "5"){
            $asset_category = "Computer & ICT Equipments";
        }elseif($asset_table['asset_category'] == "6"){
            $asset_category = "Plant & Equipments";
        }elseif($asset_table['asset_category'] == "7"){
            $asset_category = "Capital Work in Progress";
        }

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Asset Category: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, ucwords(strtolower($asset_category)), 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Asset Acquiry Date: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, date("D dS M Y",strtotime($asset_table['date_of_acquiry'])), 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Asset Original Value: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, "Kes ".number_format($asset_table['orginal_value']), 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Asset Acquisition Rate: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, $asset_table['acquisition_rate']."%", 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Current Value: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, "Kes ".number_format(round(($asset_data['new_value']*1),2))." (".(date("Y",strtotime($asset_table['date_of_acquiry'])) * 1) + ($asset_data['years']*1).")", 0,1);
        // echo json_encode($asset_data);

        // Disposed Status
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Disposed Status: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, ($asset_table['disposed_status'] == 1 ? "Disposed" : "Not-Disposed"), 0,1);

        // Disposed value
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Disposed Value: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, $asset_table['disposed_status'] == 1 ? "Kes ". number_format($asset_table['disposed_value'])." - ( Date : ".date("D dS M Y",strtotime($asset_table['disposed_on']))." )" : "0", 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Acquisition Method:", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, $asset_data['value_acquisition_method'], 0,1);

        // make a line
        $pdf->Ln();
        $pdf->Cell(190,1,"",1,1);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times', 'B', 10);
        $pdf->SetFillColor(216, 217, 218);
        $pdf->Cell(190,6,$pdf->school_document_title,1,1,"C",TRUE);
        $pdf->Cell(10,6,"QTY",1,0,"L",TRUE);
        $pdf->Cell(55,6,"ITEM",1,0,"L",TRUE);
        $pdf->Cell(35,6,"YEAR",1,0,"C",TRUE);
        $pdf->Cell(30,6,"DEBIT",1,0,"L",TRUE);
        $pdf->Cell(30,6,"CREDIT",1,0,"L",TRUE);
        $pdf->Cell(30,6,"BALANCE",1,1,"L",TRUE);
        $pdf->SetFont('Times', '', 10);

        // GET THE ROW VALUE
        $reductions = $asset_data['account'];
        for($index = 0; $index < count($reductions); $index++){
            $pdf->Cell(10,6,($index+1),1,0,"L",TRUE);
            $pdf->Cell(55,6,$reductions[$index]['name'],1,0);
            $pdf->Cell(35,6,$reductions[$index]['year'],1,0);
            $pdf->Cell(30,6,$reductions[$index]['account'] == "debit" ? $reductions[$index]['amount'] : "-",1,0);
            $pdf->Cell(30,6,$reductions[$index]['account'] == "credit" ? $reductions[$index]['amount'] : "-",1,0);
            $pdf->Cell(30,6,$reductions[$index]['balance'],1,1);
        }
        $pdf->SetFont('Times', 'B', 10,"L");
        $pdf->Cell(100,6,"Total:",0,0,"R",FALSE);
        $pdf->Cell(30,6, substr($asset_data['value_acquisition_method'],-5) == "(+ve)" ? "Kes ". number_format(round($asset_data['reduction_amount'],2)) : "-",1,0,"L",TRUE);
        $pdf->Cell(30,6, substr($asset_data['value_acquisition_method'],-5) == "(-ve)" ? "Kes ". number_format(round($asset_data['reduction_amount'],2)) : "-",1,0,"L",TRUE);
        $pdf->Cell(30,6, "Kes ". number_format(round($asset_data['new_value'],2)),1,1,"L",TRUE);
        // echo json_encode($asset_data);
        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['schname'])) {
    include("../connections/conn1.php");
    include("../connections/conn2.php");
    // var_dump($_GET);
    if (isset($_GET['effect_month']) && isset($_GET['get_nssf_reports'])) {
        // get staff 
        include("../comma.php");
        $selected_month = $_GET['effect_month'];
        // echo $selected_month;
        $select = "SELECT * FROM `payroll_information`";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // check if the staff was paid by the month the user has chosen
                $effect_month = explode(",", $row['effect_month'])[0];
                $current_balance = $row['current_balance'];
                $current_balance_monNyear = $row['current_balance_monNyear'];

                // get the joined_date 
                $joined_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $effect_month)));
                $last_paid_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $current_balance_monNyear)));
                // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";

                // selected month
                $selected_month = date("Y-m-d", strtotime($selected_month . "-01"));

                // if the selected month is between the two date
                if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                    // with the staff data create a table showing
                    $row_data = [];
                    $staff_information = getStaffInformations_report($conn, $row['staff_id']);
                    // echo json_encode($staff_information);
                    $staff_name = count($staff_information) > 0 ? ucwords(strtolower($staff_information['fullname'])) : "Null";
                    $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                    $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                    $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                    // get if the staff gets the nssf deduction
                    $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                    $nssf_amounts = 0;
                    $nssf_type = "none";
                    $salary_details = getMySalaryBreakdown_report($row['staff_id'], $conn2, $selected_month);
                    if ($salary_details != null) {
                        // decode the salary details to get the nssf amount
                        $decode_salary = ($salary_details);
                        if ($decode_salary->nssf_rates == "teir_1") {
                            $nssf_amounts = 360;
                            $nssf_type = "Teir 1";
                        } elseif ($decode_salary->nssf_rates == "teir_1_2") {
                            $nssf_amounts = 1080;
                            $nssf_type = "Teir 1 & 2";
                        } elseif ($decode_salary->nssf_rates == "teir_old") {
                            $nssf_amounts = 200;
                            $nssf_type = "Old Rates";
                        } else {
                            $nssf_amounts = 0;
                            $nssf_type = "none";
                        }
                    }
                    // employees amounts
                    $employers_amount = $nssf_amounts;
                    $total_to_pay = $employers_amount + $nssf_amounts;
                    array_push($row_data, $staff_name, $id_no, $nssf_no, $nssf_type, ($employers_amount), ($employers_amount), ($total_to_pay));
                    array_push($data, $row_data);
                    // break;
                }
            }
        }
        // create the pdf
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "N.S.S.F Contributions for " . date("M Y", strtotime($selected_month . "-01")) . "";
        // Column headings
        $header = array('#', 'Employees Name', 'I`d no', 'NSSF No', 'NSSF Category', 'Employer Contribution', 'Employees Contribution', 'Total');

        $data = $data;
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 8);
        $width = array(7, 35, 17, 22, 25, 33, 37, 15);
        $pdf->NSSF_TABLE($header, $data, $width);
        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
    } elseif (isset($_GET['get_kra_reports']) && isset($_GET['effect_month'])) {
        // include("../ajax/finance/financial.php");
        // get staff 
        $selected_month = $_GET['effect_month'];
        // echo $selected_month;
        $select = "SELECT * FROM `payroll_information`";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // check if the staff was paid by the month the user has chosen
                $effect_month = explode(",", $row['effect_month'])[0];
                $current_balance = $row['current_balance'];
                $current_balance_monNyear = $row['current_balance_monNyear'];

                // get the joined_date 
                $joined_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $effect_month)));
                $last_paid_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $current_balance_monNyear)));
                // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";

                // selected month
                $selected_month = date("Y-m-d", strtotime($selected_month . "-01"));
                /** TEST WITH THIS**/
                // $staff_information = getStaffInformations_report($conn,$row['staff_id']);
                // $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                // echo $staff_name." ||(".$selected_month.">". $joined_date ."&&". $selected_month ."<". $last_paid_date.") || (".$joined_date." == ". $selected_month." && ".$last_paid_date." > ".$joined_date.") || (".$last_paid_date." == ".$selected_month." && ".$current_balance." == 0).||<br>";
                /** ENDS HERE**/

                // if the selected month is between the two date
                if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                    // with the staff data create a table showing
                    $row_data = [];
                    $staff_information = getStaffInformations_report($conn, $row['staff_id']);
                    // echo json_encode($staff_information);
                    $staff_name = count($staff_information) > 0 ? ucwords(strtolower($staff_information['fullname'])) : "Null";
                    $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                    $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                    $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                    // get if the staff gets the nssf deduction
                    $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                    $gross_salary = getSalary_Report($selected_month, $conn2, $row['staff_id']);
                    $nssf_amounts = 0;
                    $nssf_type = "none";
                    $contributions = 0;
                    $nhif_amounts = 0;
                    $taxable_income = 0;
                    $deductions = 0;
                    $reliefs = 0;
                    $salary_details = getMySalaryBreakdown_report($row['staff_id'], $conn2, $selected_month);
                    if ($salary_details != null) {
                        // decode the salary details to get the nssf amount
                        $decode_salary = ($salary_details);
                        if ($decode_salary->nssf_rates == "teir_1") {
                            $nssf_amounts = 360;
                            $nssf_type = "Teir 1";
                        } elseif ($decode_salary->nssf_rates == "teir_1_2") {
                            $nssf_amounts = 1080;
                            $nssf_type = "Teir 1 & 2";
                        } elseif ($decode_salary->nssf_rates == "teir_old") {
                            $nssf_amounts = 200;
                            $nssf_type = "Old Rates";
                        } else {
                            $nssf_amounts = 0;
                            $nssf_type = "none";
                        }
                        // year 
                        $year = $decode_salary->year;
                        // get allowances
                        $total_allowances = 0;
                        $allowances = $decode_salary->allowances;
                        if (is_array($allowances)) {
                            for ($in = 0; $in < count($allowances); $in++) {
                                $total_allowances += $allowances[$in]->value;
                            }
                        }

                        // get gross salary
                        $gross_salary = $decode_salary->gross_salary;

                        // get the nhif contribution
                        $nhif_status = $decode_salary->deduct_nhif;
                        $nhif_amounts = ($nhif_status == "yes") ? getNHIFContribution_reports($gross_salary) : 0;

                        // nssf & nhif
                        $contributions = $nssf_amounts + $nhif_amounts;

                        // get taxable income 
                        $taxable_income = ($gross_salary + $total_allowances) - $nssf_amounts;

                        // calculate P.A.Y.E
                        $paye = ($decode_salary->deduct_paye == "yes") ? getPaye_Report($taxable_income, $year) : 0;

                        // get reliefs
                        $paye_relief = ($decode_salary->deduct_paye == "yes" && $decode_salary->personal_relief == "yes") ? 2400 : 0;
                        $nhif_relief = ($decode_salary->deduct_nhif == "yes" && $decode_salary->nhif_relief == "yes") ? (($nhif_amounts * 0.15) > 255 ? 255 : ($nhif_amounts * 0.15)) : 0;

                        // total reliefs
                        $reliefs = $paye_relief;
                        // echo $reliefs."<br>";
                        // get deductions
                        $deductions = $nhif_amounts + $paye;
                    }
                    $final_paye = $paye - $paye_relief;
                    // employees amounts
                    $employers_amount = $nssf_amounts;
                    $total_to_pay = $employers_amount + $nssf_amounts;
                    array_push($row_data, $staff_name, round($gross_salary), $total_allowances, round($taxable_income), round($contributions), round($deductions), round($paye), round($reliefs), round($final_paye), $row['staff_id']);
                    array_push($data, $row_data);
                    // break;
                }
            }
        }
        include("../comma.php");
        // create the pdf
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "K.R.A Contributions for " . date("M Y", strtotime($selected_month . "-01")) . "";
        // Column headings
        $header = array('#', 'Employees Name', 'Gross Salary', 'Allowances', 'Taxable Income', 'P.A.Y.E', 'Relief', 'Final P.A.Y.E');

        $data = $data;
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 8);
        $width = array(7, 40, 25, 20, 25, 25, 25, 30);
        $pdf->KRA_TABLE($header, $data, $width);
        $pdf->Output("I", str_replace(" ", "_", $pdf->school_document_title) . ".pdf");
    } elseif (isset($_GET['generate_slip']) && isset($_GET['staff_slip']) && isset($_GET['selected_month'])) {
        // GET THE STAFF DETAILS TO PRINT THEM A PAYSLIP
        $staff_id = $_GET['staff_slip'];
        $mystaff_data = getStaffData($conn);
        $selected_staff = [];
        for ($i = 0; $i < count($mystaff_data); $i++) {
            if ($mystaff_data[$i]['user_id'] == $staff_id) {
                $selected_staff = $mystaff_data[$i];
            }
        }
        if (count($selected_staff) > 0) {
            // get the staff payroll information
            $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $selected_staff['user_id'] . "'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->execute();
            $stmt->store_result();
            $rnums = $stmt->num_rows;
            $current_balance = 0;
            $current_balance_monNyear = 0;
            $salary_amount = 0;
            if ($rnums > 0) {
                if ($row = $result->fetch_assoc()) {
                    $current_balance = $row['current_balance'];
                    $current_balance_monNyear = $row['current_balance_monNyear'];
                    $salary_amount = explode(",", $row['salary_amount']);
                    $salary_amount = $salary_amount[count($salary_amount) - 1];
                }
                // create pdf
                // get basic information
                $pdf2 = new PDF('P', 'mm', 'A4');
                // Column headings
                // Data loading
                // $data = $pdf2->LoadData('countries.txt');
                $tittle = "Payslip for " . ucwords(strtolower($selected_staff['fullname'])) . " on " . date("M Y", strtotime($_GET['selected_month']));
                // $data = $student_data;
                $pdf2->set_document_title($tittle);
                $pdf2->setHeaderPos(200);
                $pdf2->setSchoolLogo("../../" . schoolLogo($conn));
                $pdf2->set_school_name($_SESSION['schname']);
                $pdf2->set_school_po($_SESSION['po_boxs']);
                $pdf2->set_school_box_code($_SESSION['box_codes']);
                $pdf2->set_school_contact($_SESSION['school_contact']);
                $pdf2->AddPage();
                // Line break
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Staff Name : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, ucwords(strtolower($selected_staff['fullname'])), 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Age : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $date1 = date_create($selected_staff['dob']);
                $date2 = date_create(date("Y-m-d"));
                $diff = date_diff($date1, $date2);
                $diffs = $diff->format("%y Yr(s)");
                $pdf2->Cell(30, 5, $diffs, 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Staff Role : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, authority($selected_staff['auth']), 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "I`d No : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, $selected_staff['nat_id'], 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Staff Netpay : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $number = 1;
                $date_selected = $_GET['selected_month'];
                $deductions = getSalaryDeductionDetails($conn2, $selected_staff['user_id'], $number, $date_selected);
                $salary_amount -= $_SESSION['total_advances'];
                unset($_SESSION['total_advances']);
                $pdf2->Cell(30, 5, "Kes " . number_format($salary_amount), 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Last Month Paid : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, $current_balance_monNyear, 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', '', 10);
                $pdf2->Cell(30, 5, "Salary Balance : ", 0, 0, 'L', 0);
                $pdf2->SetFont('Helvetica', '', 9);
                $pdf2->Cell(30, 5, "Kes " . number_format($current_balance), 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->Cell(190, 5, "", 'B', 0, 'L', 0);
                $pdf2->Ln(10);
                // earnings
                // get the staff earnings and allowances
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', 'BU', 10);
                $pdf2->Cell(30, 5, "Earnings & Reliefs", 0, 0, 'L', 0);
                $pdf2->Ln();
                $pdf2->SetFont('Helvetica', 'B', 10);
                $number = 1;
                $earnings = getSalaryEarningsDetails($conn2, $selected_staff['user_id'], $number, $date_selected);
                $header = array("No.", "Earnings & Reliefs", "Amount", "Working Days", "Total");
                $w = array(15, 70, 30, 30, 30);
                $pdf2->salaryTables($header, $earnings, $w);
                // get the staff deductions
                $pdf2->Ln(10);
                $pdf2->SetFont('Helvetica', 'BU', 10);
                $pdf2->Cell(30, 5, "Deductions", 0, 0, 'L', 0);
                $header = array("No.", "Deductions", "Amount", "Working Days", "Total");
                $w = array(15, 70, 30, 30, 30);
                $pdf2->Ln();
                $pdf2->salaryTables($header, $deductions, $w);
                $pdf2->Ln(10);
                $pdf2->SetFillColor(157, 183, 184);
                $pdf2->Cell(85, 1, '', 0, 0, 0, 0);
                $pdf2->Cell(45, 6, 'Net Pay', 1, 0, 'L', true);
                $pdf2->Cell(45, 6, "Kes " . number_format($salary_amount), 1, 0, 'L', true);
                $pdf2->Ln();
                $pdf2->Ln();
                $pdf2->Cell(30, 1, '', 0, 0, 0, 0);
                $pdf2->Write(6, "If you have questions about this payslip please contact : " . $_SESSION['school_contact'] . "");
                $pdf2->Ln();
                $pdf2->Output("I", str_replace(" ", "_", $pdf2->school_document_title) . ".pdf");
            } else {
                echo "<p style='color:red;'><b>Note:</b><br> - Staff not enrolled in the Payroll System!</p>";
            }
        } else {
            echo "<p style='color:red;'><b>Note:</b><br> - Staff not present!</p>";
        }
    } elseif (isset($_GET['get_nhif_reports'])) {
        include("../comma.php");
        // get staff 
        $selected_month = $_GET['effect_month'];
        // echo $selected_month;
        $select = "SELECT * FROM `payroll_information`";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // check if the staff was paid by the month the user has chosen
                $effect_month = explode(",", $row['effect_month'])[0];
                $current_balance = $row['current_balance'];
                $current_balance_monNyear = $row['current_balance_monNyear'];

                // get the joined_date 
                $joined_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $effect_month)));
                $last_paid_date = date("Y-m-d", strtotime("01-" . str_replace(":", "-", $current_balance_monNyear)));
                // echo "<br>".$effect_month." effect_month ".$current_balance." current_balance ".$current_balance_monNyear." current_balance_monNyear <br>";

                // selected month
                $selected_month = date("Y-m-d", strtotime($selected_month . "-01"));
                /** TEST WITH THIS**/
                // $staff_information = getStaffInformations($conn,$row['staff_id']);
                // $staff_name = count($staff_information)>0 ? ucwords(strtolower($staff_information['fullname'])):"Null";
                // echo $staff_name." ||(".$selected_month.">". $joined_date ."&&". $selected_month ."<". $last_paid_date.") || (".$joined_date." == ". $selected_month." && ".$last_paid_date." > ".$joined_date.") || (".$last_paid_date." == ".$selected_month." && ".$current_balance." == 0).||<br>";
                /** ENDS HERE**/

                // if the selected month is between the two date
                if (($selected_month > $joined_date && $selected_month < $last_paid_date) || ($joined_date == $selected_month && $last_paid_date > $joined_date) || ($last_paid_date == $selected_month && $current_balance == 0)) {
                    // with the staff data create a table showing
                    $row_data = [];
                    $staff_information = getStaffInformations_report($conn, $row['staff_id']);
                    // echo json_encode($staff_information);
                    $staff_name = count($staff_information) > 0 ? ucwords(strtolower($staff_information['fullname'])) : "Null";
                    $id_no = count($staff_information) > 0 ? $staff_information['nat_id'] : "Null";
                    $nssf_no = count($staff_information) > 0 ? $staff_information['nssf_number'] : "Null";
                    $nhif_no = count($staff_information) > 0 ? $staff_information['nhif_number'] : "Null";
                    // get if the staff gets the nssf deduction
                    $salary_details = count($staff_information) > 0 ? $row['salary_breakdown'] : "Null";
                    $nhif_amounts = 0;
                    $nhif_reliefs = 0;
                    $salary_details = getMySalaryBreakdown_report($row['staff_id'], $conn2, $selected_month);
                    $gross_salary = 0;

                    if ($salary_details != null) {
                        // decode the salary details to get the nssf amount
                        $decode_salary = ($salary_details);
                        $deduct_nhif = $salary_details->deduct_nhif;
                        $nhif_relief = $salary_details->nhif_relief;
                        $gross_salary = $salary_details->gross_salary;

                        if ($deduct_nhif == "yes") {
                            $nhif_amounts = getNHIFContribution_reports($gross_salary);
                            $nhif_reliefs = $nhif_relief == "yes" ? (($nhif_amounts * 0.15) > 255 ? 255 : ($nhif_amounts * 0.15)) : 0;
                        }
                    }
                    // add data to row
                    array_push($row_data, $staff_name, $gross_salary, $nhif_no, $nhif_amounts, $nhif_reliefs, $deduct_nhif, $nhif_relief, $row['staff_id']);
                    array_push($data, $row_data);
                    // break;
                }
            }
        }

        // create the pdf
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = "N.H.I.F Contributions for " . date("M Y", strtotime($selected_month . "-01")) . "";
        // Column headings
        $header = array('#', 'Employees Name', 'I`d no', 'N.S.S.F No', 'N.S.S.F Category', 'Employer Contribution', 'Employees Contribution', 'Total');

        $data = $data;
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $width = array(7, 35, 17, 20, 27, 20, 33, 20, 15);
        $header = array("#", "Staff Name", "NHIF No", "Gross Salary", "NHIF Contribution", "NHIF Relief", "Final NHIF Contribution", "Deduct NHIF", "Relief");
        $pdf->NHIF_TABLE($header, $data, $width);
        $pdf->Output();
    }elseif (isset($_GET['revenue_receipt'])){
        include("../connections/conn1.php");
        include("../connections/conn2.php");

        if(!isset($_GET['revenue_details'])){
            echo "<p style='color:red;'>Invalid revenue details!</p>";
            return 0;
        }

        // check if the revenue details are valid
        $revenue_details = $_GET['revenue_details'];
        if(!isJson_report($revenue_details)){
            echo "<p style='color:red;'>Invalid revenue details. Contact your administrator!</p>";
            return 0;
        }

        // revenue details
        $revenue_details = json_decode($revenue_details, true);

        $students_names = $revenue_details['customer_name'];
        $student_admission_no = "Nan";
        $amount_paid_by_student = "Kes ". number_format($revenue_details['amount']);
        $new_student_balance = 0;
        $mode_of_payments = $revenue_details['mode_of_payment'] == 1 ? "M-Pesa" : ($revenue_details['mode_of_payment'] == 2 ? "Cash" : ($revenue_details['mode_of_payment'] == 3 ? "Bank" : "Not-Defined"));
        $transaction_codes = $revenue_details['payment_code'];
        $payments_for = $revenue_details['name'];
        $fees_payment_receipt = "Nan";
        $reciept_size = "Nan";
        $fees_payment_opt_holder = "Nan";
        $last_receipt_id_take = $revenue_details['id'];
        $date_of_payments_fees = date("D dS-M-Y", strtotime($revenue_details['date_recorded']));
        $time_of_payment_fees = date("H:i:s");
        
        // echo $fees_payment_opt_holder;
        include("fees_reminder.php");
        // create the pdf file
        $pdf = new PDF2('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = $students_names . " Fees Receipt.";
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetMargins(5, 5);

        // get the school information
        $school_info = getSchoolInfo($conn);
        $school_name = ucwords(strtoupper($school_info['school_name']));
        $school_motto = ucwords(strtolower($school_info['school_motto']));
        $school_admin_name = $school_info['school_admin_name'];
        $school_mail = $school_info['school_mail'];
        $county = $school_info['county'];
        $physicall_address = $school_info['physicall_address'];
        $country = $school_info['country'];
        $school_profile_image = "../" . $school_info['school_profile_image'];
        $po_box = $school_info['po_box'];
        $box_code = $school_info['box_code'];
        $school_contact = $school_info['school_contact'];
        $website_name = $school_info['website_name'];

        $pdf->Image($school_profile_image, 5, 10, 20, 20);
        $pdf->Image($pdf->arm_of_gov, 100, 15, 12, 12);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetFillColor(100, 100, 100);
        $pdf->SetTitle("Receipt for ".$students_names." Reg No. ".$student_admission_no.".");
        $pdf->Cell(15, 10, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);

        $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);
        $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
        // divider strip
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Ln(5);
        $pdf->Cell(200, 2, "", 0, 1, 0, true);
        $pdf->SetFillColor(240, 240, 240);

        // start the receipt details
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(65, 10, "CLIENT PAYMENT RECEIPT", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** SCHOOL COPY **", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

        // RECEIPT DETAILS
        // row 1
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $date_of_payments_fees , 1, 0, "L",false);
        
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, $time_of_payment_fees , "RTB", 1, "L",false);
        // $pdf->Cell(53, 6, "", "RBT", 1, "L");

        // row 2
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30, 6, "Client Name. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(170, 6, $students_names, 1, 1, "L");
        // $pdf->SetFont('Helvetica', 'B', 9);
        // $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
        // $pdf->SetFont('Helvetica', '', 9);
        // $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

        // THIRD ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $amount_paid_by_student, 1, 0, "L");
        $new_numbers = new NUmbers();
        $new_number = returnNumbers($amount_paid_by_student);
        $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
        $prefix = $new_number < 0? "Negative ":"";

        $text_width = $pdf->GetStringWidth("** ".$prefix." ".$my_number." Kenya Shillings Only **");
        $font_size = round((148*100*9) / ($text_width * 100),3)-1;
        $font_size = $font_size > 9 ? 9 : $font_size;
        $pdf->SetFont('Helvetica', 'B', $font_size);
        $pdf->Cell(148, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

        // voteheads paid for
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

        // another row
        $pdf->Cell(155,7,$payments_for,1,0,"L",false);
        $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

        // another row
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(15,7,"Total:",1,0,"L",false);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

        // ANOTHER ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Served By : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
        $pdf->Cell(170,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,1,"L",false);

        // DISCLAIMER
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
        // $pdf->Ln(5);
        
        $remaining_lines = 6;

        for($i = 0; $i < $remaining_lines; $i++){
            $pdf->Ln();
        }
        $pdf->Cell(200,0,"",1,1);
        $pdf->Ln(5);
        
        // space between
        $y = $pdf->GetY();
        $pdf->Image($school_profile_image, 5, $y, 20, 20);
        $pdf->Image($pdf->arm_of_gov, 100, $y+5, 12, 12);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetFillColor(100, 100, 100);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);

        $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);
        $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
        // divider strip
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Ln(5);
        $pdf->Cell(200, 2, "", 0, 1, 0, true);
        $pdf->SetFillColor(240, 240, 240);

        // start the receipt details
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(65, 10, "CLIENT PAYMENT RECEIPT", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** CLIENT COPY **", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

        // RECEIPT DETAILS
        // row 1
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $date_of_payments_fees , 1, 0, "L",false);
        
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, $time_of_payment_fees , "RTB", 1, "L",false);
        // $pdf->Cell(53, 6, "", "RBT", 1, "L");

        // row 2
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30, 6, "Client Name. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(170, 6, $students_names, 1, 1, "L");
        // $pdf->SetFont('Helvetica', 'B', 9);
        // $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
        // $pdf->SetFont('Helvetica', '', 9);
        // $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

        // THIRD ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $amount_paid_by_student, 1, 0, "L");
        $new_numbers = new NUmbers();
        $new_number = returnNumbers($amount_paid_by_student);
        $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
        $prefix = $new_number < 0? "Negative ":"";

        $text_width = $pdf->GetStringWidth("** ".$prefix." ".$my_number." Kenya Shillings Only **");
        $font_size = round((148*100*9) / ($text_width * 100),3)-1;
        $font_size = $font_size > 9 ? 9 : $font_size;
        $pdf->SetFont('Helvetica', 'B', $font_size);
        $pdf->Cell(148, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

        // voteheads paid for
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

        // another row
        $pdf->Cell(155,7,$payments_for,1,0,"L",false);
        $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

        // another row
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(15,7,"Total:",1,0,"L",false);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

        // ANOTHER ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Served By : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
        $pdf->Cell(170,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,1,"L",false);

        // DISCLAIMER
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
        // $pdf->Ln(5);

        $pdf->Output();
    
    }elseif (isset($_GET['supplier_payment_id'])){
        include("../connections/conn1.php");
        include("../connections/conn2.php");

        if(!isset($_GET['supplier_payment_id'])){
            echo "<p style='color:red;'>Invalid revenue details!</p>";
            return 0;
        }
        $supplier_payment_id = $_GET['supplier_payment_id'];
        // check if the payment is valid
        $select = "SELECT * FROM `supplier_bill_payments` WHERE `payment_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$supplier_payment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $present = false;
        $bill_details = [];
        if($result){
            if($row = $result->fetch_assoc()){
                $present = true;
                $bill_details = $row;
            }
        }
        
        // check if not present
        if(!$present){
            echo "<p style='color:red;'>The payment is invalid, select another payment to print receipt!</p>";
            return 0;
        }

        // get the supplier details
        $select = "SELECT * FROM `suppliers` AS S WHERE `supplier_id` = (SELECT `supplier_id` FROM `supplier_bills` AS SB WHERE `bill_id` = '".$bill_details['payment_for']."')";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $supplier_details = [];
        $supplier_present = false;
        if($result){
            if($row = $result->fetch_assoc()){
                $supplier_details = $row;
                $supplier_present = true;
            }
        }
        
        if(!$supplier_present){
            echo "<p style='color:red;'>Supplier is invalid, We can`t provide receipt for an invalid supplier!</p>";
            return 0;
        }

        // get the bill details
        $select = "SELECT * FROM `supplier_bills` AS SB WHERE `bill_id` = '".$bill_details['payment_for']."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $supplier_bill_validity = false;
        $supplier_bill = [];
        if($result){
            if($row = $result->fetch_assoc()){
                $supplier_bill_validity = true;
                $supplier_bill = $row;
            }
        }
        
        if(!$supplier_bill_validity){
            echo "<p style='color:red;'>Bill is invalid, We can`t provide receipt for an invalid bill!</p>";
            return 0;
        }

        $students_names = $supplier_details['supplier_name']." - (".ucwords(strtolower($supplier_details['company_name'])).")";
        $student_admission_no = "Nan";
        $amount_paid_by_student = "Kes ". number_format($bill_details['amount']);
        $new_student_balance = 0;
        $mode_of_payments = $bill_details['payment_method'] == 1 ? "Bank Transfer" : ($bill_details['payment_method'] == 2 ? "Cheque" : ($bill_details['payment_method'] == 3 ? "Cash" : ($bill_details['payment_method'] == 4 ? "M-Pesa (Paybill)" : ($bill_details['payment_method'] == 5 ? "M-Pesa (Buy Goods)" : ($bill_details['payment_method'] == 6 ? "M-Pesa (Pochi)" : ($bill_details['payment_method'] == 7 ? "M-Pesa (Send Money)" : "Not-Defined"))))));
        $transaction_codes = $bill_details['document_number'];
        $payments_for = $supplier_bill['bill_name'];
        $fees_payment_receipt = "Nan";
        $reciept_size = "Nan";
        $fees_payment_opt_holder = "Nan";
        $last_receipt_id_take = $bill_details['payment_id'];
        $date_of_payments_fees = date("D dS-M-Y", strtotime($bill_details['date_paid']));
        $time_of_payment_fees = date("H:i:s");
        
        // echo $fees_payment_opt_holder;
        include("fees_reminder.php");
        // create the pdf file
        $pdf = new PDF2('P', 'mm', 'A4');
        $pdf->setHeaderPos(200);
        $tittle = $students_names . " Fees Receipt.";
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        $pdf->SetMargins(5, 5);

        // get the school information
        $school_info = getSchoolInfo($conn);
        $school_name = ucwords(strtoupper($school_info['school_name']));
        $school_motto = ucwords(strtolower($school_info['school_motto']));
        $school_admin_name = $school_info['school_admin_name'];
        $school_mail = $school_info['school_mail'];
        $county = $school_info['county'];
        $physicall_address = $school_info['physicall_address'];
        $country = $school_info['country'];
        $school_profile_image = "../" . $school_info['school_profile_image'];
        $po_box = $school_info['po_box'];
        $box_code = $school_info['box_code'];
        $school_contact = $school_info['school_contact'];
        $website_name = $school_info['website_name'];

        $pdf->Image($school_profile_image, 5, 10, 20, 20);
        $pdf->Image($pdf->arm_of_gov, 100, 15, 12, 12);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetFillColor(100, 100, 100);
        $pdf->SetTitle("Payment Voucher for ".$students_names." Reg No. ".$student_admission_no.".");
        $pdf->Cell(15, 10, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);

        $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);
        $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
        // divider strip
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Ln(5);
        $pdf->Cell(200, 2, "", 0, 1, 0, true);
        $pdf->SetFillColor(240, 240, 240);

        // start the receipt details
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(65, 10, "SUPPLIER PAYMENT VOUCHER", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** SCHOOL COPY **", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

        // RECEIPT DETAILS
        // row 1
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $date_of_payments_fees , 1, 0, "L",false);
        
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, $time_of_payment_fees , "RTB", 1, "L",false);
        // $pdf->Cell(53, 6, "", "RBT", 1, "L");

        // row 2
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30, 6, "Client Name. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(170, 6, $students_names, 1, 1, "L");
        // $pdf->SetFont('Helvetica', 'B', 9);
        // $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
        // $pdf->SetFont('Helvetica', '', 9);
        // $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

        // THIRD ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $amount_paid_by_student, 1, 0, "L");
        $new_numbers = new NUmbers();
        $new_number = returnNumbers($amount_paid_by_student);
        $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
        $prefix = $new_number < 0? "Negative ":"";

        $text_width = $pdf->GetStringWidth("** ".$prefix." ".$my_number." Kenya Shillings Only **");
        $font_size = round((148*100*9) / ($text_width * 100),3)-1;
        $font_size = $font_size > 9 ? 9 : $font_size;
        $pdf->SetFont('Helvetica', 'B', $font_size);
        $pdf->Cell(148, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

        // voteheads paid for
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

        // another row
        $pdf->Cell(155,7,$payments_for,1,0,"L",false);
        $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

        // another row
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(15,7,"Total:",1,0,"L",false);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

        // ANOTHER ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Served By : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
        $pdf->Cell(170,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,1,"L",false);

        // DISCLAIMER
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
        // $pdf->Ln(5);
        
        $remaining_lines = 6;

        for($i = 0; $i < $remaining_lines; $i++){
            $pdf->Ln();
        }
        $pdf->Cell(200,0,"",1,1);
        $pdf->Ln(5);
        
        // space between
        $y = $pdf->GetY();
        $pdf->Image($school_profile_image, 5, $y, 20, 20);
        $pdf->Image($pdf->arm_of_gov, 100, $y+5, 12, 12);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetFillColor(100, 100, 100);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->Cell(100, 6, $school_name, 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);

        $pdf->Cell(80, 4, "P.O Box " . $po_box . " - " . $box_code . " " . $county . " " . $country, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 10, "", 0, 0, "L", false);
        $pdf->Cell(100, 10, $school_motto, 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, $physicall_address, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);

        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 4, "Tel: " . $school_contact, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Email : " . $school_mail, 0, 1, "R", false);


        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y + 5);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(20, 5, "", 0, 0, "L", false);
        $pdf->Cell(100, 5, "", 0, 0, "L", false);
        $pdf->SetFont('Helvetica', '', 8);
        $X = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($X, $y - 5);
        $pdf->Cell(80, 4, "Website : " . $website_name, 0, 1, "R", false);
        // divider strip
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Ln(5);
        $pdf->Cell(200, 2, "", 0, 1, 0, true);
        $pdf->SetFillColor(240, 240, 240);

        // start the receipt details
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(65, 10, "SUPPLIER PAYMENT VOUCHER", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** SUPPLIER COPY **", 0, 0, "C", false);
        $pdf->Cell(65, 10, "** ORIGINAL **", 0, 1, "C", false);

        // RECEIPT DETAILS
        // row 1
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Receipt No. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60, 6, $last_receipt_id_take, 1, 0, "L");
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(20, 6, "Date : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $date_of_payments_fees , 1, 0, "L",false);
        
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(25, 6, "Time : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(40, 6, $time_of_payment_fees , "RTB", 1, "L",false);
        // $pdf->Cell(53, 6, "", "RBT", 1, "L");

        // row 2
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30, 6, "Client Name. : ", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(170, 6, $students_names, 1, 1, "L");
        // $pdf->SetFont('Helvetica', 'B', 9);
        // $pdf->Cell(25, 6, "Adm No. : ", 1, 0, "L",true);
        // $pdf->SetFont('Helvetica', '', 9);
        // $pdf->Cell(75, 6, $student_admission_no, 1, 1, "L");

        // THIRD ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(22, 6, "Amount", 1, 0, "L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30, 6, $amount_paid_by_student, 1, 0, "L");
        $new_numbers = new NUmbers();
        $new_number = returnNumbers($amount_paid_by_student);
        $my_number = $new_number< 0 ? $new_numbers->convert_number($new_number*-1):$new_numbers->convert_number($new_number);
        $prefix = $new_number < 0? "Negative ":"";

        $text_width = $pdf->GetStringWidth("** ".$prefix." ".$my_number." Kenya Shillings Only **");
        $font_size = round((148*100*9) / ($text_width * 100),3)-1;
        $font_size = $font_size > 9 ? 9 : $font_size;
        $pdf->SetFont('Helvetica', 'B', $font_size);
        $pdf->Cell(148, 6, "** ".$prefix." ".$my_number." Kenya Shillings Only **", "BR", 1, "L");

        // voteheads paid for
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(200,7,"VOTEHEAD","TBLR",1,"C",true);

        // another row
        $pdf->Cell(155,7,$payments_for,1,0,"L",false);
        $pdf->Cell(45,7,$amount_paid_by_student,"BR",1,"L",false);

        // another row
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Payment Mode : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(35,7,$mode_of_payments,1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Transaction Code:",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(60,7,"".$transaction_codes."",1,0,"L",false);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(15,7,"Total:",1,0,"L",false);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(30,7,"".$amount_paid_by_student."",1,1,"L",false);

        // ANOTHER ROW
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(30,7,"Served By : ",1,0,"L",true);
        $pdf->SetFont('Helvetica', '', 9);
        $staff_infor = getStaffInformations_report($conn,$_SESSION['userids']);
        $pdf->Cell(170,7,explode(" ",ucwords(strtolower($staff_infor['fullname'])))[0],1,1,"L",false);

        // DISCLAIMER
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->Cell(200,7,"** Receipts are not valid unless signed OR Stamped with the Official School Stamp  **","",1,"C",false);
        // $pdf->Ln(5);

        $pdf->Output();
    
    }elseif(isset($_GET['supplier_account_id'])){
        $supplier_account_id = $_GET['supplier_account_id'];

        // check if its a valid supplier
        $my_supplier = "SELECT * FROM `suppliers` WHERE `supplier_id` = '".$supplier_account_id."'";
        $stmt = $conn2->prepare($my_supplier);
        $stmt->execute();
        $result = $stmt->get_result();
        $present = false;
        $supplier_data = [];
        if($result){
            if($row = $result->fetch_assoc()){
                $present = true;
                $supplier_data = $row;
            }
        }

        if(!$present){
            echo "<p style='color:red;'>In-valid supplier!</p>";
            return 0;
        }
        
        // GET ALL THE SUPPLIER BILLS
        $select = "SELECT * FROM `supplier_bills` WHERE `supplier_id` = ?";
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("s",$supplier_account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_data = [];
        if($result){
            while($row = $result->fetch_assoc()){
                // data
                $data = array("account" => "debit","amount" => $row['bill_amount'],"date" => $row['date_assigned'], "usage" => $row['bill_name']);
                array_push($row_data, $data);

                $select = "SELECT * FROM `supplier_bill_payments` WHERE `payment_for` = '".$row['bill_id']."' AND approval_status = '1'";
                $statement = $conn2->prepare($select);
                $statement -> execute();
                $res = $statement->get_result();
                if($res){
                    while($rowed = $res->fetch_assoc()){
                        $data = array("account" => "credit","amount" => $rowed['amount'],"date" => $rowed['date_paid'], "usage" => "Paid - (". $row['bill_name'].")");
                        array_push($row_data, $data);
                    }
                }
            }
        }

        $key = "date";
        $row_data = sortByKey($row_data,$key,true);
        // echo json_encode($row_data);
        // return 0;

        // display the records

        // display the data pn the pdf
        $pdf = new PDF("P","mm","A4");
        $pdf->setHeaderPos(200);
        $tittle = "Supplier Accounts Statements \"".$supplier_data['supplier_name']."\"";
        
        $pdf->set_document_title($tittle);
        $pdf->setSchoolLogo("../../" . schoolLogo($conn));
        $pdf->set_school_name($_SESSION['schname']);
        $pdf->set_school_po($_SESSION['po_boxs']);
        $pdf->set_school_box_code($_SESSION['box_codes']);
        $pdf->set_school_contact($_SESSION['school_contact']);
        $pdf->AddPage();
        // row 1
        $pdf->SetFont('Times', 'BU', 10);
        $pdf->Cell(45, 6, "Supplier Details: ", 0, 'B', 'L',false);
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Supplier Name: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, ucwords(strtolower($supplier_data['supplier_name'])), 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Supplier Company: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, ucwords(strtolower($supplier_data['company_name'])), 0,1);
        
        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Supplier Address: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, $supplier_data['supplier_address']."", 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Registration Date: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, date("D dS M Y",strtotime($supplier_data['date_registered'])), 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Supplier Contact: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, $supplier_data['supplier_phone'], 0,1);

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Date Generated: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, date("D dS M Y @ H:i:sA"), 0,1);

        // supplier balance
        // $select = "SELECT SUM(SB.bill_amount) AS 'Due', SUM((SELECT SUM(SBP.amount) AS 'Paid' FROM `supplier_bill_payments` AS SBP WHERE SBP.payment_for = SB.bill_id )) AS 'Paid' FROM `supplier_bills` AS SB WHERE SB.supplier_id = '".$supplier_account_id."'";
        $select = "SELECT SUM(SB.bill_amount) AS 'Due', CONCAT('0') AS 'Paid' FROM `supplier_bills` AS SB WHERE `supplier_id` = '".$supplier_account_id."' UNION ALL (SELECT CONCAT('0') AS 'Due', SUM(SBP.amount) AS 'Paid' FROM `supplier_bill_payments` AS SBP LEFT JOIN supplier_bills AS SBILL ON SBILL.bill_id = SBP.payment_for WHERE SBP.approval_status = 1 AND SBILL.supplier_id = '".$supplier_account_id."');";
        // echo $select;
        $statement = $conn2->prepare($select);
        $statement->execute();
        $res = $statement->get_result();
        $billing_amount = 0;
        $paid_amount = 0;
        if ($res) {
            while($rows = $res->fetch_assoc()){
                $billing_amount += $rows['Due']*1;
                $paid_amount += $rows['Paid']*1;
            }
        }

        $amount_owed = $billing_amount-$paid_amount;

        // row 2
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(40, 6, "Supplier Balance: ", 0);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(45, 6, "Kes ".number_format($amount_owed), 0,1);

        // make a line
        $pdf->Ln();
        $pdf->Cell(190,1,"",1,1);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times', 'B', 10);
        $pdf->SetFillColor(216, 217, 218);
        $pdf->Cell(190,6,$pdf->school_document_title,1,1,"C",TRUE);
        $pdf->Cell(10,6,"QTY",1,0,"L",TRUE);
        $pdf->Cell(60,6,"ITEM",1,0,"L",TRUE);
        $pdf->Cell(30,6,"DATE",1,0,"L",TRUE);
        $pdf->Cell(30,6,"DEBIT",1,0,"L",TRUE);
        $pdf->Cell(30,6,"CREDIT",1,0,"L",TRUE);
        $pdf->Cell(30,6,"BALANCE",1,1,"L",TRUE);
        $pdf->SetFont('Times', '', 10);

        // GET THE ROW VALUE
        // $data = array("account" => "credit","amount" => $rowed['amount'],"date" => $rowed['date_paid'], "usage" => $row['bill_name']);
        $debit = 0;
        $credit = 0;
        $balance = 0;
        for($index = 0; $index < count($row_data); $index++){
            $pdf->Cell(10,6,($index+1),1,0,"L",TRUE);
            $pdf->Cell(60,6,$row_data[$index]['usage'],1,0);
            $pdf->Cell(30,6,date("D dS M Y",strtotime($row_data[$index]['date'])),1,0);
            $pdf->Cell(30,6,$row_data[$index]['account'] == "debit" ? "Kes ".number_format($row_data[$index]['amount']) : "-",1,0);
            $pdf->Cell(30,6,$row_data[$index]['account'] == "credit" ? "Kes ".number_format($row_data[$index]['amount']) : "-",1,0);
            if($row_data[$index]['account'] == "debit"){
                $debit += $row_data[$index]['amount'];
            }else{
                $credit += $row_data[$index]['amount'];
            }
            $balance = $row_data[$index]['account'] == "credit" ? ($balance - $row_data[$index]['amount']*1) : ($row_data[$index]['account'] == "debit" ? ($balance + $row_data[$index]['amount']*1) : $balance);
            $pdf->Cell(30,6,"Kes ".number_format($balance),1,1);
        }
        $pdf->SetFont('Times', 'B', 10,"L");
        $pdf->Cell(100,6,"Total:",0,0,"R",FALSE);
        $pdf->Cell(30,6, "Kes ". number_format($debit) ,1,0,"L",TRUE);
        $pdf->Cell(30,6, "Kes ". number_format($credit) ,1,0,"L",TRUE);
        $pdf->Cell(30,6, "Kes ". number_format($balance),1,1,"L",TRUE);
        $pdf->Output();
        
    }
}

// get the asset history
function asset_history($conn2, $asset_category, $periods){
    // get the asset categories
    $select = "SELECT * FROM `asset_table` WHERE `asset_category` = '".$asset_category."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $asset_acquisitions = [];
    $asset_category_name = $asset_category == "1" ? "Land" : ($asset_category == "2" ? "Buildings" : ($asset_category == "3" ? "Motor Vehicle" : ($asset_category == "4" ? "Furniture & Fittings" : ($asset_category == "5" ? "Computer & ICT Equipments" : ($asset_category == "6" ? "Plant & Equipments" : ($asset_category == "7" ? "Capital Work in Progress" : "N/A"))))));
    if($result){
        while($row = $result->fetch_assoc()){
            $value_acquisition = get_current_value($row);
            array_push($asset_acquisitions,$value_acquisition);
        }
    }
    
    $earliest_year = date("Y");
    $highest_years = 1;
    for($index = 0; $index < count($asset_acquisitions); $index++){
        $earliest_year = count($asset_acquisitions[$index]['account']) > 0 ? ($asset_acquisitions[$index]['account'][0]['year'] < $earliest_year ? $asset_acquisitions[$index]['account'][0]['year'] : $earliest_year) : $earliest_year;
        $highest_years = $asset_acquisitions[$index]['years'] > $highest_years ? $asset_acquisitions[$index]['years'] : $highest_years;
    }

    // json encode
    $highest_years*1;
    $highest_years+= $earliest_year;
    // echo json_encode($asset_acquisitions);

    // get the total years they have been active
    
    $asset_accounted = [];
    for($index = $earliest_year; $index <= ($highest_years); $index ++){
        $data = array("asset_name" => ucwords(strtolower($asset_category_name)),"asset_category" => $asset_category, "debit" => 0, "credit" => 0, "balance" => 0, "year" => $index);
        for($ind = 0; $ind < count($asset_acquisitions); $ind++){
            $asset = $asset_acquisitions[$ind];
            // echo json_encode($asset['account'])."<br>";
            for($i = 0; $i < count($asset['account']); $i++){
                // get the year
                $asset_account = $asset['account'][$i];
                if($asset_account['year'] == $index){
                    $data['debit'] += $asset_account['account'] == "debit" ? substr(str_replace(",","",$asset_account['amount']),4)*1 : 0;
                    $data['credit'] += $asset_account['account'] == "credit" ? substr(str_replace(",","",$asset_account['amount']),4)*1 : 0;
                    $data['balance'] += substr(str_replace(",","",$asset_account['balance']),4)*1;
                }
            }
        }
        array_push($asset_accounted, $data);
    }

    // start time and end time
    $start_time = date("Y",strtotime($periods[1][1]));
    $end_time = date("Y",strtotime($periods[0][0]));

    // get the asset during that period
    $asset_account_return = [];
    for($index = $start_time; $index <= $end_time; $index++){
        $present = false;
        for($ind = 0; $ind < count($asset_accounted); $ind++){
            if($asset_accounted[$ind]['year'] == $index.""){
                array_push($asset_account_return,$asset_accounted[$ind]);
                $present = true;
            }
        }

        // not present
        if(!$present){
            $data = array("asset_name" => ucwords(strtolower($asset_category_name)),"asset_category" => $asset_category, "debit" => 0, "credit" => 0, "balance" => 0, "year" => $index);
            array_push($asset_account_return,$data);
        }
    }

    // return value
    return $asset_account_return;
}

function display_notes($note, $pdf, $periods, $note_title){
        
    // NOTE 6
    $pdf->AddPage();
    $pdf->Cell(190,7,$note_title,0,1,"L");
    
    // SET FILL COLLOR
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetFillColor(0, 112, 192);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(85,6,"Description","TL",0,"C",TRUE);
    $pdf->Cell(45,6,date("dS Y",strtotime($periods[0][1]))." / ".date("dS Y",strtotime($periods[0][0])),1,0,"C",TRUE);
    $pdf->Cell(45,6,date("dS Y",strtotime($periods[1][1]))." / ".date("dS Y",strtotime($periods[1][0])),1,1,"C",TRUE);

    $pdf->Cell(85,6,"","BL",0,"L",TRUE);
    $pdf->Cell(45,6,"Ksh",1,0,"C",TRUE);
    $pdf->Cell(45,6,"Ksh",1,1,"C",TRUE);

    $pdf->SetTextColor(0, 0, 0);

    // max index
    $longest = count($note['current_year_value']) > count($note['last_year_value']) ? $note['current_year_value'] : $note['last_year_value'];
    $values = [];
    
    // longest
    for($index = 0; $index < count($longest); $index++){
        $present = false; 
        for($ind = 0; $ind < count($values); $ind++){
            if($values[$ind] == $longest[$index]['item']){
                $present = true;
                break;
            }
        }
        if(!$present){
            array_push($values, $longest[$index]['item']);
        }
    }

    $pdf->SetFont('Times', '', 10);
    for($index = 0; $index < count($values); $index++){
        $pdf->Cell(85,6,$values[$index],"BL",0,"L",false);

        // current value
        $current_value = 0;
        for($ind = 0; $ind < count($note['current_year_value']); $ind++){
            if($note['current_year_value'][$ind]['item'] == $values[$index]){
                $current_value = $note['current_year_value'][$ind]['Total'];
            }
        }

        // last year value
        $last_year_value = 0;
        for($ind = 0; $ind < count($note['last_year_value']); $ind++){
            if($note['last_year_value'][$ind]['item'] == $values[$index]){
                $last_year_value = $note['last_year_value'][$ind]['Total'];
            }
        }
        $pdf->Cell(45,6,"Ksh ".number_format($current_value),1,0,"C",false);
        $pdf->Cell(45,6,"Ksh ".number_format($last_year_value),1,1,"C",false);
    }
    $pdf->SetFont('Times', 'B', 10);

    $pdf->Cell(85,6,"Total","BL",0,"L",false);
    $pdf->Cell(45,6,"Ksh ".number_format($note['current_year_total']),1,0,"C",false);
    $pdf->Cell(45,6,"Ksh ".number_format($note['last_year_total']),1,1,"C",false);

}

function get_school_balances($conn2){
    $school_classes = getSchoolCLass($conn2);
    if (count($school_classes) > 0) {
        $term = getTermV2($conn2);
        $student_count  = 0;
        $total_balance = 0;
        $total_fees_to_pay = 0;
        $per_course_balance = [];
        
        // go through every class
        for ($ind = 0; $ind < count($school_classes); $ind++) {
            // get per class
            $student_class_fin = $school_classes[$ind];
            $student_data = getStudents($student_class_fin, $conn2);
            $number = 1;
            $total_fees = 0;
            $fees_repo_paid = 0;
            $total_balances = 0;
            for ($index = 0; $index < count($student_data); $index++) {
                // get fees to pay by the student
                $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                $total_fees += $balanced + $feespaidbystud;
                $fees_repo_paid += $feespaidbystud;
                $total_balances += $balanced;
                
                // LAST ACADEMIC YEAR BALANCE
                $last_acad_yr = lastACADyrBal($student_data[$index]['adm_no'], $conn2);
                $number++;
            }
            // student count
            $student_count += count($student_data);
            $total_balance += $total_balances;
            $total_fees_to_pay += $total_fees;
            if (count($student_data) > 0) {
                $data = array("student_count" => count($student_data), "total_balance" => $total_balances, "total_fees" => $total_fees,"course_name" => ucwords(strtolower($student_class_fin)));
                array_push($per_course_balance, $data);
            }
        }
        return array("student_count" => $student_count, "total_balance" => $total_balance, "total_fees" => $total_fees_to_pay, "per_course_balances" => $per_course_balance);
    }
    return array("student_count" => 0, "total_balance" => 0, "total_fees" => 0, "per_course_balances" => []);
}
function get_note($periods, $conn2, $note){
    $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $present = false;
    $revenue_category_id = null;
    $revenue_sub_category = [];
    if($result){
        if($row = $result->fetch_assoc()){
            $revenue_categories = $row['valued'];
            if(isJson_report($revenue_categories)){
                $revenue_categories = json_decode($revenue_categories);
                for($index = 0; $index < count($revenue_categories); $index++){
                    if($note == $revenue_categories[$index]->revenue_notes){
                        $revenue_category_id = $revenue_categories[$index]->category_id;
                        $revenue_sub_category = $revenue_categories[$index]->sub_categories;
                        $present = true;
                    }
                }
            }
        }
    }

    if(!$present){
        return array("current_year_total" => 0,"last_year_total" => 0,"current_year_value" => [],"last_year_value" => []);
    }

    // GET THE TOTAL REVENUE FOR NOTE 6 CURRENT YEAR
    $select_1 = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."'";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_year = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year = $row['Total'];
        }
    }

    // GET THE TOTAL REVENUE FOR NOTE 6 LAST YEAR
    $select_2 = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."'";
    $stmt = $conn2->prepare($select_2);
    $stmt->execute();
    $result = $stmt->get_result();
    $previous_year = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $previous_year = $row['Total'];
        }
    }

    // GET THE NOTE DATA FOR CURRENT YEAR
    $select_1 = "SELECT SUM(`amount`) AS 'Total', `revenue_sub_category` AS 'item' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."' GROUP BY `revenue_sub_category`";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_revenue_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $rev_sub_cat = "~Deleted~";
            for($ind = 0; $ind < count($revenue_sub_category); $ind++){
                if($revenue_sub_category[$ind]->id == $row['item']){
                    $rev_sub_cat = $revenue_sub_category[$ind]->name;
                }
            }

            // item name
            $row['item'] = $rev_sub_cat;
            array_push($current_revenue_notes,$row);
        }
    }

    // GET THE NOTE DATA FOR CURRENT YEAR
    $select_2 = "SELECT SUM(`amount`) AS 'Total', `revenue_sub_category` AS 'item' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."' GROUP BY `revenue_sub_category`";
    $stmt = $conn2->prepare($select_2);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_revenue_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $rev_sub_cat = "~Deleted~";
            for($ind = 0; $ind < count($revenue_sub_category); $ind++){
                if($revenue_sub_category[$ind]->id == $row['item']){
                    $rev_sub_cat = $revenue_sub_category[$ind]->name;
                }
            }

            // item name
            $row['item'] = $rev_sub_cat;
            array_push($last_revenue_notes,$row);
        }
    }
    return array("current_year_total" => $current_year,"last_year_total" => $previous_year,"current_year_value" => $current_revenue_notes,"last_year_value" => $last_revenue_notes);
}

function get_note_10_a($periods, $conn2){
    $select = "SELECT * FROM `settings` WHERE `sett` = 'revenue_categories'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $present = false;
    $revenue_category_id = null;
    $revenue_sub_category = [];
    if($result){
        if($row = $result->fetch_assoc()){
            $revenue_categories = $row['valued'];
            if(isJson_report($revenue_categories)){
                $revenue_categories = json_decode($revenue_categories);
                for($index = 0; $index < count($revenue_categories); $index++){
                    if("10" == $revenue_categories[$index]->revenue_notes){
                        $revenue_category_id = $revenue_categories[$index]->category_id;
                        $revenue_sub_category = $revenue_categories[$index]->sub_categories;
                        $present = true;
                    }
                }
            }
        }
    }

    if(!$present){
        return array("current_year_total" => 0,"last_year_total" => 0,"current_year_value" => [],"last_year_value" => []);
    }

    // GET THE TOTAL REVENUE FOR NOTE 6 CURRENT YEAR
    $select_1 = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."'";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_year = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year = $row['Total'];
        }
    }

    // get the student fees
    $select_fees_1 = "SELECT SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN '".date("Y-m-d",strtotime($periods[0][1]))."' AND '".date("Y-m-d",strtotime($periods[0][0]))."'";
    $stmt = $conn2->prepare($select_fees_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $tuition = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year += $row['Total'];
            $tuition = $row['Total'];
        }
    }

    // GET THE TOTAL REVENUE FOR NOTE 6 LAST YEAR
    $select_2 = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."'";
    $stmt = $conn2->prepare($select_2);
    $stmt->execute();
    $result = $stmt->get_result();
    $previous_year = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $previous_year = $row['Total'];
        }
    }

    // get the student fees
    $select_fees_1 = "SELECT SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN '".date("Y-m-d",strtotime($periods[1][1]))."' AND '".date("Y-m-d",strtotime($periods[1][0]))."'";
    $stmt = $conn2->prepare($select_fees_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $prev_tuition = $row['Total'];
    if($result){
        if($row = $result->fetch_assoc()){
            $previous_year += $row['Total'];
            $prev_tuition = $row['Total'];
        }
    }

    // GET THE NOTE DATA FOR CURRENT YEAR
    $select_1 = "SELECT SUM(`amount`) AS 'Total', `revenue_sub_category` AS 'item' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."' GROUP BY `revenue_sub_category`";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_revenue_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $rev_sub_cat = "~Deleted~";
            for($ind = 0; $ind < count($revenue_sub_category); $ind++){
                if($revenue_sub_category[$ind]->id == $row['item']){
                    $rev_sub_cat = $revenue_sub_category[$ind]->name;
                }
            }

            // item name
            $row['item'] = $rev_sub_cat;
            array_push($current_revenue_notes,$row);
        }
    }
    array_push($current_revenue_notes, array("Total" => $tuition, "item" => "Student Tuition Fees Invoiced"));

    // GET THE NOTE DATA FOR CURRENT YEAR
    $select_2 = "SELECT SUM(`amount`) AS 'Total', `revenue_sub_category` AS 'item' FROM `school_revenue` WHERE `revenue_category` = '".$revenue_category_id."' AND `date_recorded` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."' GROUP BY `revenue_sub_category`";
    $stmt = $conn2->prepare($select_2);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_revenue_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $rev_sub_cat = "~Deleted~";
            for($ind = 0; $ind < count($revenue_sub_category); $ind++){
                if($revenue_sub_category[$ind]->id == $row['item']){
                    $rev_sub_cat = $revenue_sub_category[$ind]->name;
                }
            }

            // item name
            $row['item'] = $rev_sub_cat;
            array_push($last_revenue_notes,$row);
        }
    }
    array_push($last_revenue_notes, array("Total" => $prev_tuition, "item" => "Student Tuition Fees Invoiced"));
    return array("current_year_total" => $current_year,"last_year_total" => $previous_year,"current_year_value" => $current_revenue_notes,"last_year_value" => $last_revenue_notes);
}


function get_note_10($conn2)
{
    // echo json_encode($note);
    $school_classes = getSchoolCLass($conn2);
    $fees_to_pay = 0;
    if (count($school_classes) > 0) {
        $term = getTermV2($conn2);
        for ($ind = 0; $ind < count($school_classes); $ind++) {
            // get per class
            $student_class_fin = $school_classes[$ind];
            $student_data = getStudents($student_class_fin, $conn2);
            for ($index = 0; $index < count($student_data); $index++) {
                // get fees to pay by the student
                $feespaidbystud = getFeespaidByStudent($student_data[$index]['adm_no'], $conn2);
                $fees_paid = ($feespaidbystud);
                $balanced = getBalanceReports($student_data[$index]['adm_no'], $term, $conn2);
                $balance = ($balanced * 1);

                // fees_to_pay 
                $fees_to_pay +=  ($fees_paid + $balance);
            }
        }
    }

    // return fees_to_pay
    $current_revenue_notes = [];
    array_push($current_revenue_notes, array("Total" => $fees_to_pay, "item" => "Student Tuition Fees Invoiced"));
    return array("current_year_total" => $fees_to_pay,"last_year_total" => 0,"current_year_value" => $current_revenue_notes,"last_year_value" => []);
}
function create_note_table($pdf, $periods, $row, $note_title){
    // NOTE 6
    $pdf->AddPage();
    $pdf->Cell(190,7,$note_title,0,1,"L");

    // SET FILL COLLOR
    $pdf->SetFont('Times', 'B', 10);
    $pdf->SetFillColor(0, 112, 192);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(85,6,"Description","TL",0,"C",TRUE);
    $pdf->Cell(45,6,date("dS Y",strtotime($periods[0][1]))." / ".date("dS Y",strtotime($periods[0][0])),1,0,"C",TRUE);
    $pdf->Cell(45,6,date("dS Y",strtotime($periods[1][1]))." / ".date("dS Y",strtotime($periods[1][0])),1,1,"C",TRUE);

    $pdf->Cell(85,6,"","BL",0,"L",TRUE);
    $pdf->Cell(45,6,"Ksh",1,0,"C",TRUE);
    $pdf->Cell(45,6,"Ksh",1,1,"C",TRUE);

    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Times', '', 10);
    for($index = 0; $index < $row; $index ++){
        $pdf->Cell(85,6,"",1,0,"L",false);
        $pdf->Cell(45,6,"",1,0,"C",false);
        $pdf->Cell(45,6,"",1,1,"C",false);
    }
    
    // times
    $pdf->SetFont('Times', 'B', 10);

    $pdf->Cell(85,6,"Total","BL",0,"L",false);
    $pdf->Cell(45,6," ",1,0,"C",false);
    $pdf->Cell(45,6," ",1,1,"C",false);
}

function get_expense_note($periods, $conn2, $note){
    $select = "SELECT * FROM `expense_category` WHERE `expense_note` = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s",$note);
    $stmt->execute();
    $result = $stmt->get_result();
    $present = false;
    $expense_category = [];
    if($result){
        if($row = $result->fetch_assoc()){
            $present = true;
            $expense_category = $row;
        }
    }

    if(!$present){
        return array("current_year_total" => 0,"last_year_total" => 0,"current_year_value" => [],"last_year_value" => []);
    }

    // get the expense category total
    $select_1 = "SELECT SUM(`exp_amount`) AS 'Total' FROM `expenses` WHERE `exp_category` = '".$expense_category['expense_id']."' AND `expense_date` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."'";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_year_total = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year_total = $row['Total'];
        }
    }

    // get the expense category total
    $select_1 = "SELECT SUM(`exp_amount`) AS 'Total' FROM `expenses` WHERE `exp_category` = '".$expense_category['expense_id']."' AND `expense_date` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."'";
    $stmt = $conn2->prepare($select_1);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_year_total = 0;
    if($result){
        if($row = $result->fetch_assoc()){
            $last_year_total = $row['Total'];
        }
    }

    $select = "SELECT SUM(`exp_amount`) AS 'Total', `exp_sub_category` AS 'item' FROM `expenses` WHERE `exp_category` = '".$expense_category['expense_id']."' AND `expense_date` BETWEEN '".$periods[0][1]."' AND '".$periods[0][0]."' GROUP BY `exp_sub_category`";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_expense_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $expense_sub_category = $expense_category['expense_sub_categories'];
            $exp_sub_cat = "N.A";
            if(isJson_report($expense_sub_category)){
                $expense_sub_category = json_decode($expense_sub_category);
                for($index = 0; $index < count($expense_sub_category); $index++){
                    if($expense_category['expense_id'].":".$expense_sub_category[$index]->id == $row['item']){
                        $exp_sub_cat = $expense_sub_category[$index]->name;
                    }
                }
            }
            $row['item'] = $exp_sub_cat;
            array_push($current_expense_notes,$row);
        }
    }
    // echo $select;
    // echo json_encode($current_expense_notes);

    $select = "SELECT SUM(`exp_amount`) AS 'Total', `exp_sub_category` AS 'item' FROM `expenses` WHERE `exp_category` = '".$expense_category['expense_id']."' AND `expense_date` BETWEEN '".$periods[1][1]."' AND '".$periods[1][0]."' GROUP BY `exp_sub_category`";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_expense_notes = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $expense_sub_category = $expense_category['expense_sub_categories'];
            $exp_sub_cat = "N.A";
            if(isJson_report($expense_sub_category)){
                $expense_sub_category = json_decode($expense_sub_category);
                for($index = 0; $index < count($expense_sub_category); $index++){
                    if($expense_category['expense_id'].":".$expense_sub_category[$index]->id == $row['item']){
                        $exp_sub_cat = $expense_sub_category[$index]->name;
                    }
                }
            }
            $row['item'] = $exp_sub_cat;
            array_push($last_expense_notes,$row);
        }
    }

    // row
    // echo json_encode($current_expense_notes)."<br>";

    // SUPPLIER BILLS
    $select = "SELECT SB.*,EC.expense_name FROM `supplier_bills` AS SB
                LEFT JOIN `expense_category` AS EC
                ON SB.expense_category = EC.expense_id 
                WHERE EC.expense_note = '".$note."' 
                AND SB.date_assigned BETWEEN '".$periods[1][1]."000000' AND '".$periods[1][0]."235959';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        while($row = $result->fetch_assoc()){
            $last_year_total += ($row['bill_amount'] * 1);
            array_push($last_expense_notes, array("Total" => ($row['bill_amount'] * 1), "item" => ucwords(strtolower($row['bill_name']))));
        }
    }

    // SUPPLIER BILLS
    $select = "SELECT SB.*,EC.expense_name FROM `supplier_bills` AS SB
                LEFT JOIN `expense_category` AS EC
                ON SB.expense_category = EC.expense_id 
                WHERE EC.expense_note = '".$note."'
                AND SB.date_assigned BETWEEN '".$periods[0][1]."000000' AND '".$periods[0][0]."235959';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        while($row = $result->fetch_assoc()){
            $current_year_total += ($row['bill_amount'] * 1);
            array_push($current_expense_notes, array("Total" => ($row['bill_amount'] * 1), "item" => ucwords(strtolower($row['bill_name']))));
        }
    }
    // return array
    return array("current_year_total" => $current_year_total,"last_year_total" => $last_year_total,"current_year_value" => $current_expense_notes,"last_year_value" => $last_expense_notes);
}

function note_26($conn2, $periods){
    // INCOME FROM FEES CURRENT
    $current_year_income = 0;
    $select = "SELECT SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN '".date("Y-m-d", strtotime($periods[0][1]))."' AND '".date("Y-m-d", strtotime($periods[0][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year_income += $row['Total']*1;
        }
    }

    // GET THE OTHER REVENUES
    $select = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `date_recorded` BETWEEN '".date("Y-m-d", strtotime($periods[0][1]))."' AND '".date("Y-m-d", strtotime($periods[0][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year_income += $row['Total']*1;
        }
    }

    // INCOME FROM FEES LAST YEAR
    $last_year_income = 0;
    $select = "SELECT SUM(`amount`) AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN '".date("Y-m-d", strtotime($periods[1][1]))."' AND '".date("Y-m-d", strtotime($periods[1][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $last_year_income += $row['Total']*1;
        }
    }

    // GET THE OTHER REVENUES
    $select = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE `date_recorded` BETWEEN '".date("Y-m-d", strtotime($periods[1][1]))."' AND '".date("Y-m-d", strtotime($periods[1][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $last_year_income += $row['Total']*1;
        }
    }


    //--------------------EXPENSES------------------------//
    // Current year expenses
    $current_year_exp = 0;
    $select = "SELECT SUM(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN '".date("Y-m-d", strtotime($periods[0][1]))."' AND '".date("Y-m-d", strtotime($periods[0][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year_exp += $row['Total']*1;
        }
    }

    $select = "SELECT SUM(`amount`) AS 'Total' FROM `supplier_bill_payments` WHERE `date_paid` BETWEEN '".date("Y-m-d", strtotime($periods[0][1]))."' AND '".date("Y-m-d", strtotime($periods[0][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $current_year_exp += $row['Total']*1;
        }
    }

    // Last year expenses
    $last_year_expense = 0;
    $select = "SELECT SUM(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN '".date("Y-m-d", strtotime($periods[1][1]))."' AND '".date("Y-m-d", strtotime($periods[1][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $last_year_expense += $row['Total']*1;
        }
    }

    $select = "SELECT SUM(`amount`) AS 'Total' FROM `supplier_bill_payments` WHERE `date_paid` BETWEEN '".date("Y-m-d", strtotime($periods[1][1]))."' AND '".date("Y-m-d", strtotime($periods[1][0]))."'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result){
        if($row = $result->fetch_assoc()){
            $last_year_expense += $row['Total']*1;
        }
    }

    $last_year_cash = $last_year_income - $last_year_expense;
    $current_year_cash = $current_year_income - $current_year_exp;

    return array("current_year_cash" => $current_year_cash, "last_year_cash" => $last_year_cash, "current_year_income" => $current_year_income, "last_year_income" => $last_year_income, "current_year_expense" => $current_year_exp, "last_year_expense" => $last_year_expense);
}

/**
 * Checks if a folder exist and return canonicalized absolute pathname (long version)
 * @param string $folder the path being checked.
 * @return mixed returns the canonicalized absolute pathname on success otherwise FALSE is returned
 */
function sortByKey($array, $key, $ascending = true) {
    // Create a copy of the input array
    $sortedArray = $array;

    // Sort the copy of the array
    usort($sortedArray, function($a, $b) use ($key, $ascending) {
        $comparison = strcmp($a[$key], $b[$key]);
        return $ascending ? $comparison : -$comparison;
    });

    // Return the sorted array
    return $sortedArray;
}

function editComments($comments, $student_details)
{
    $comments = str_replace("{fullname}", ucwords(strtolower($student_details['surname'] . " " . $student_details['first_name'] . " " . $student_details['second_name'])), $comments);
    $comments = str_replace("{firstname}", $student_details['first_name'], $comments);
    $comments = str_replace("{noun1}", ($student_details['gender'] == "Male" ? "son" : "daughter"), $comments);
    $comments = str_replace("{noun2}", ($student_details['gender'] == "Male" ? "boy" : "girl"), $comments);
    $comments = str_replace("{noun3}", ($student_details['gender'] == "Male" ? "boy" : "girl"), $comments);
    $comments = str_replace("{class}", $student_details['stud_class'], $comments);
    $comments = str_replace("{adm_no}", $student_details['stud_class'], $comments);
    return $comments;
}
function folder_exist($folder)
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    if ($path !== false and is_dir($path)) {
        // Return canonicalized absolute pathname
        return $path;
    }

    // Path/folder does not exist
    return false;
}

function isJson_report($string)
{
    return ((is_string($string) &&
        (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}

function exam_grade($conn2, $exam_id, $subject_id)
{
    $select = "SELECT * FROM `exam_record_tbl` WHERE `exam_id` = '" . $exam_id . "' AND `subject_id` = '" . $subject_id . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $exam_marks = "N/A";
    $exam_grade = "N/A";
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $exam_marks = $row['exam_marks'];
            $exam_grade = $row['exam_grade'];
        }
    }
    return [$exam_marks, $exam_grade];
}

function subjectsDetails($conn2, $subject_id, $our_staff = [])
{
    $select = "SELECT * FROM `table_subject` WHERE `subject_id` = '" . $subject_id . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result  = $stmt->get_result();
    $subject_name = "N/A";
    $teacher_name = "N/A";
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            // check if the display name is present
            $display_name = $row['display_name'];
            $subject_name = strlen(trim($display_name)) > 0 ? $display_name : ucwords(strtolower($row['subject_name']));
            // proceed to get who teaches what subject
            $teachers_id = $row['teachers_id'];
            $split_tr = explode("|", $teachers_id);
            // loop through and find the teacher and class
            $tr_id = "";
            for ($index = 0; $index < count($split_tr); $index++) {
                $explode = strlen(trim(substr($split_tr[$index], 1, strlen($split_tr[$index]) - 2)));
                if ($explode > 1) {
                    $str_sub = explode(":", substr($split_tr[$index], 1, strlen($split_tr[$index]) - 2));
                    // echo substr($split_tr[$index],1,strlen($split_tr[$index])-2)."-<br>";
                    // frst teacher second subject id
                    if ($str_sub[1] == $subject_id) {
                        // get the teacher data
                        $tr_id = $str_sub[1];
                        break;
                    }
                }
            }
            // get the teacher teaching the subject
            for ($index2 = 0; $index2 < count($our_staff); $index2++) {
                if ($our_staff[$index2]['user_id'] == $tr_id) {
                    $gender = $our_staff[$index2]['gender'] == "M" ? "Mr." : "Ms.";
                    $teacher_name = $gender . " " . ucwords(strtolower($our_staff[$index2]['fullname']));
                }
            }
        }
    }
    return [$teacher_name, $subject_name];
}

function getGrade($marks, $grading_method, $subject_id = null, $conn2 = null)
{
    if ($grading_method == "IGCSE") {
        $values = $marks;
        if ($values >= 0) {
            $values *= 1;
            $scored_grade = "-";
            if ($values <= 100 && $values > 90) {
                $scored_grade = "9";
            } else if ($values <= 90 && $values > 80) {
                $scored_grade = "8";
            } else if ($values <= 80 && $values > 73) {
                $scored_grade = "7";
            } else if ($values <= 73 && $values > 67) {
                $scored_grade = "6";
            } else if ($values <= 67 && $values > 59) {
                $scored_grade = "5";
            } else if ($values <= 59 && $values > 53) {
                $scored_grade = "4";
            } else if ($values <= 53 && $values > 46) {
                $scored_grade = "3";
            } else if ($values <= 46 && $values > 39) {
                $scored_grade = "2";
            } else if ($values <= 39 && $values > 34) {
                $scored_grade = "1";
            } else if ($values <= 34 && $values >= 0) {
                $scored_grade = "U";
            } else {
                $scored_grade = "-";
            }
            return $scored_grade;
        } else {
            return "-";
        }
    } else if ($grading_method == "iPrimary") {
        $values = $marks;
        if ($values >= 0) {
            $values *= 1;
            $scored_grade = "-";
            if ($values <= 100 && $values > 90) {
                $scored_grade = "A*";
            } else if ($values <= 90 && $values > 80) {
                $scored_grade = "A";
            } else if ($values <= 80 && $values > 70) {
                $scored_grade = "B";
            } else if ($values <= 70 && $values > 60) {
                $scored_grade = "C";
            } else if ($values <= 60 && $values > 50) {
                $scored_grade = "D";
            } else if ($values <= 50 && $values > 40) {
                $scored_grade = "E";
            } else if ($values <= 40 && $values > 30) {
                $scored_grade = "F";
            } else if ($values <= 30 && $values >= 0) {
                $scored_grade = "U";
            } else {
                $scored_grade = "-";
            }
            return $scored_grade;
        } else {
            return '-';
        }
    } elseif ($grading_method == "844") {
        if ($subject_id != null && $conn2 != null) {
            // get the subject grading method
            $select = "SELECT * FROM `table_subject` WHERE `subject_id` = '" . $subject_id . "'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            $grading = "";
            if ($result) {
                if ($row = $result->fetch_assoc()) {
                    $grading = $row['grading'];
                }
            }
            // get the grading options
            if (isJson_reports($grading)) {
                $gradings = json_decode($grading);
                for ($index = 0; $index < count($gradings); $index++) {
                    $grade_name = $gradings[$index]->grade_name;
                    $max = $gradings[$index]->max * 1;
                    $min = $gradings[$index]->min * 1;
                    if (is_numeric($marks)) {
                        $values = $marks * 1;
                        if ($values <= $max && $values >= $min) {
                            return $grade_name;
                        } else {
                            return "-";
                        }
                    } else {
                        return "-";
                    }
                }
            } else {
                return "N/A";
            }
        } else {
            return "-";
        }
    } elseif ($grading_method == "cbc") {
        $values = $marks;
        if ($values == 4) {
            return "E.E";
        } elseif ($values == 3) {
            return "A.E";
        } elseif ($values == 2) {
            return "M.E";
        } elseif ($values == 1) {
            return "B.E";
        } elseif ($values == 'A') {
            return "A";
        } else {
            return 'N/A';
        }
    } else {
        return "-";
    }
}

function classteacher($conn, $conn2, $class_tr)
{
    $select = "SELECT * FROM `class_teacher_tbl` WHERE `class_assigned` = '" . $class_tr . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $class_teacher_id = $row['class_teacher_id'];
            if (strlen($class_teacher_id) > 0) {
                $select = "SELECT * FROM `user_tbl` WHERE `user_id` = '" . $class_teacher_id . "'";
                $stmt = $conn->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result) {
                    if ($rows = $result->fetch_assoc()) {
                        $gender = $rows['gender'] == "M" ? "Mr." : "Ms.";
                        return $gender . " " . $rows['fullname'];
                    }
                }
            }
        }
    }
    return "Not Available";
}
function marksNGrade($exam_id, $subject_id, $student_id, $conn2)
{
    $select = "SELECT * FROM `exam_record_tbl` WHERE `student_id` = '" . $student_id . "' AND `exam_id` = '" . $exam_id . "' AND `subject_id` = '" . $subject_id . "'";
    // echo $select."<br>";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return [$row['exam_marks'], $row['exam_grade']];
        }
    }
    return ["", ""];
}
function subjectName($conn2, $subject_id)
{
    $select = "SELECT * FROM `table_subject` WHERE `subject_id` = '" . $subject_id . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return $row['timetable_id'];
        }
    }
    return "NaN";
}
function className_exam($data)
{
    $datas = "Grade  " . $data;
    if (strlen($data) > 1) {
        $datas = $data;
    }
    return $datas;
}
function isSubjectTaught($conn2, $subject_id, $class)
{
    $select = "SELECT * FROM `table_subject` WHERE `subject_id` = '" . $subject_id . "' AND `classes_taught` LIKE '%" . $class . "%';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $stmt->store_result();
    $rnums = $stmt->num_rows;
    // echo $rnums;
    if ($rnums > 0) {
        return 1;
    }
    return 0;
}
function chckPrsnt($array, $string)
{
    $present = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] == $string) {
            $present = 1;
        }
    }
    return $present;
}
function getSalaryEarningsDetails($conn2, $staff_id, $number, $selected_date = null)
{
    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '$staff_id'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $earnings = [];
    if ($row = $result->fetch_assoc()) {
        $salary_infor = getMySalaryBreakdown_report($staff_id, $conn2, $selected_date);
        if ($salary_infor != null) {
            // $salary_infor = json_decode($row['salary_breakdown']);
            // echo $row['salary_breakdown'];
            $allowances = is_array($salary_infor->allowances) ? $salary_infor->allowances : [];
            $gross_salary = $salary_infor->gross_salary;
            // add the salary as the first entry then the allowances follow
            array_push($earnings, array($number, "Gross Salary", $gross_salary, "30 Day(s)", $gross_salary));
            $number++;
            for ($index = 0; $index < count($allowances); $index++) {
                // get the allowances
                $name = ucwords(strtolower($allowances[$index]->name));
                $value = $allowances[$index]->value;
                array_push($earnings, array($number, $name, $value, "30 Day(s)", $value));
                $number++;
            }
            $personal_relief = $salary_infor->personal_relief;
            if ($personal_relief == "yes" && $salary_infor->deduct_paye == "yes") {
                $personal_relief = $gross_salary > 24000 ? 2400 : 0;
                array_push($earnings, array($number, "Personal Relief", $personal_relief, "30 Day(s)", $personal_relief));
                $number++;
            }
            $nhif_relief = $salary_infor->nhif_relief;
            if ($nhif_relief == "yes" && $salary_infor->deduct_nhif == "yes") {
                $nhif_contribution = getNHIFContribution_reports($gross_salary);
                $nhif_relief = ($nhif_contribution * 0.15) > 255 ? 255 : $nhif_contribution * 0.15;
                array_push($earnings, array($number, "NHIF Relief", $nhif_relief, "30 Day(s)", $nhif_relief));
                $number++;
            }
        } else {
            $salary_amount = explode(",", $row['salary_amount']);
            $salary_amount = $salary_amount[count($salary_amount) - 1];
            $gross_salary = $salary_amount;
            array_push($earnings, array($number, "Gross Salary", $gross_salary, "30 Day(s)", $gross_salary));
        }
    }
    return $earnings;
}
function getTermIncome_report($arrayPeriod,$conn2){
    $term_pay = [];
    $select = "SELECT sum(`amount`)  AS 'Total' FROM `finance` WHERE `date_of_transaction` BETWEEN ? AND ?";
    $stmt = $conn2->prepare($select);
    $stmt ->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
    $stmt->execute();
    $result = $stmt->get_result();
    $err = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $total = $row['Total'];
            if ($total >= 0 || $total != null) {
                array_push($term_pay,$row['Total']);
            }else {
                $err++;
                array_push($term_pay,0);
            }
        }else {
            array_push($term_pay,"0");
        }
    }else {
        array_push($term_pay,"0");
    }
    $stmt ->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $total = $row['Total'];
            if ($total >= 0 || $total != null) {
                array_push($term_pay,$row['Total']);
            }else {
                array_push($term_pay,0);
                $err++;
            }
        }else {
            array_push($term_pay,"0");
        }
    }else {
        array_push($term_pay,"0");
    }
    $stmt ->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $total = $row['Total'];
            if ($total >= 0 || $total != null) {
                array_push($term_pay,$row['Total']);
            }else {
                $err++;
                array_push($term_pay,0);
            }
        }else {
            array_push($term_pay,"0");
        }
    }else {
        array_push($term_pay,"0");
    }
    if ($err == 3) {
        echo "<p class='red_notice'>Edit your school academic calender first before generating your financial statement</p>";
    }
    return $term_pay;
}
function checkPresent_report($array,$string){
    if (count($array) > 0) {
        for ($index=0; $index < count($array); $index++) { 
            $my_str = $array[$index];
            if (strlen($my_str) > 0) {
                $my_str_split = explode(":",$my_str);
                if ($my_str_split[0] == $string) {
                    return true;
                }
            }
        }
    }
    return false;
}
function getValues_report($array,$string){
    if (count($array) > 0) {
        for ($index=0; $index < count($array); $index++) { 
            $my_str = $array[$index];
            if (strlen($my_str) > 0) {
                $my_str_split = explode(":",$my_str);
                if ($my_str_split[0] == $string) {
                    return $my_str_split[1];
                }
            }
        }
    }
    return "0";
}
function getSalaryExp_report($conn2,$term_period){
    $select = "SELECT SUM(`amount_paid`) AS 'Total' FROM `salary_payment` WHERE `date_paid` BETWEEN ? AND ?;";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("ss",$term_period[0],$term_period[1]);
    $stmt->execute();
    $result = $stmt->get_result();
    $salaries = [];
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            if (isset($row['Total'])) {
                array_push($salaries,$row['Total']);
            }else {
                array_push($salaries,"0");
            }
        }
    }
    $stmt->bind_param("ss",$term_period[2],$term_period[3]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            if (isset($row['Total'])) {
                array_push($salaries,$row['Total']);
            }else {
                array_push($salaries,"0");
            }
        }
    }
    $stmt->bind_param("ss",$term_period[4],$term_period[5]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            if (isset($row['Total'])) {
                array_push($salaries,$row['Total']);
            }else {
                array_push($salaries,"0");
            }
        }
    }
    return $salaries;
}
function getTermPeriod_report($conn2){
    // $select = "SELECT `start_time`,`end_time` FROM `academic_calendar` WHERE 
    //             (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
    //             OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
    //             OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
    $select = "SELECT `start_time`,`end_time` FROM `academic_calendar` WHERE 
                (`term` = 'TERM_1') 
                OR (`term` = 'TERM_2') 
                OR (`term` = 'TERM_3');";
    $stmt = $conn2->prepare($select);
    $date = date("Y");
    // $stmt->bind_param("sss",$date,$date,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $dates = [];
    if ($result) {
        while($row = $result->fetch_assoc()){
            array_push($dates,$row['start_time'],$row['end_time']);
        }
    }
    //echo count($dates);
    return $dates;
}
function getTaxes_report($arrayPeriod,$conn2){
    $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` = 'taxes'  GROUP BY `Expense`";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
    $stmt->execute();
    $termExp = [];
    $result = $stmt->get_result();
    $taxes = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $taxes = $row['Total'];
        }
    }
    array_push($termExp,$taxes);
    //second term
    $taxes = 0;
    $stmt->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $taxes = $row['Total'];
        }
    }
    array_push($termExp,$taxes);
    //third term
    $taxes = 0;
    $stmt->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $taxes = $row['Total'];
        }
    }
    array_push($termExp,$taxes);
    //echo $arrayPeriod[4]." - ".$arrayPeriod[5];

    return $termExp;
}
function isPresent_report($array,$string){
    if (count($array) > 0 ) {
        for ($indexes=0; $indexes <count($array) ; $indexes++) { 
            if ($string == $array[$indexes]) {
                return true;
                break;
            }
        }
    }
    return false;
}
function getAllExpenseNames_report($term_expense){
    //its a multilevel array
    $allitems = [];
    for ($index1=0; $index1 < count($term_expense); $index1++) { 
        for ($index2=0; $index2 < count($term_expense[$index1]); $index2++) { 
            $object = $term_expense[$index1][$index2];
            //array_push($allitems,$object);
            //split the text
            if (strlen($object) > 0) {
                $stringExp = explode(":",$object);
                if (!isPresent_report($allitems,$stringExp[0])) {
                    array_push($allitems,$stringExp[0]);
                }
            }
        }
    }
    return $allitems;
}
function getExpenses_report($arrayPeriod,$conn2){
    $select = "SELECT `exp_category` as 'Expense', sum(`exp_amount`) AS 'Total' FROM `expenses` WHERE `expense_date` BETWEEN ? and ?   AND `exp_category` != 'taxes'  GROUP BY `Expense`";
    $termExp = [];
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("ss",$arrayPeriod[0],$arrayPeriod[1]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $termPexp1 = [];
        while ($row = $result->fetch_assoc()) {
            array_push($termPexp1,$row['Expense'].":".$row['Total']);
        }
        array_push($termExp,$termPexp1);
    }
    //second term
    $stmt->bind_param("ss",$arrayPeriod[2],$arrayPeriod[3]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $termPexp1 = [];
        while ($row = $result->fetch_assoc()) {
            array_push($termPexp1,$row['Expense'].":".$row['Total']);
        }
        array_push($termExp,$termPexp1);
    }
    //third term
    $stmt->bind_param("ss",$arrayPeriod[4],$arrayPeriod[5]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $termPexp1 = [];
        while ($row = $result->fetch_assoc()) {
            array_push($termPexp1,$row['Expense'].":".$row['Total']);
        }
        array_push($termExp,$termPexp1);
    }
    return $termExp;
}
function getTermPeriods_report($conn2, $year = null){
    $date = $year == null ? date("Y")."0101" : $year."0101";
    // $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE 
    // (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
    // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
    // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
    $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE 
    (`term` = 'TERM_1') 
    OR (`term` = 'TERM_2') 
    OR (`term` = 'TERM_3');";
    $stmt = $conn2->prepare($select);
    // $stmt->bind_param("sss",$date,$date,$date);
    $stmt->execute();
    $period = [];
    $result = $stmt->get_result();
    if ($result) {
        while($row = $result->fetch_assoc()){
            array_push($period,date("Y",strtotime($date)).substr($row['start_time'],4),date("Y",strtotime($date)).substr($row['end_time'],4));
        }
    }
    return $period;
}

function getTermPeriods_report_sms($conn2,$term = "TERM_1"){
    $date = date("Y");
    // $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE 
    // (YEAR(`end_time`) >= ? AND `term` = 'TERM_1') 
    // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_2') 
    // OR (YEAR(`end_time`) >= ? AND `term` = 'TERM_3');";
    $select = "SELECT  `term`,`start_time`,`end_time`,`closing_date` FROM `academic_calendar` WHERE (`term` = '$term')";
    $stmt = $conn2->prepare($select);
    // $stmt->bind_param("sss",$date,$date,$date);
    $stmt->execute();
    $period = [];
    $result = $stmt->get_result();
    if ($result) {
        while($row = $result->fetch_assoc()){
            array_push($period,$row['start_time'],$row['end_time']);
        }
    }
    return $period;
}
function getOtherRevenue_report($conn2, $year = null){
    $year = $year == null ? date("Y") : $year;
    $get_term_period = getTermPeriods_report($conn2, $year);
    $select = "SELECT SUM(`amount`) AS 'Total' FROM `school_revenue` WHERE  `reportable_status` = '1' AND `date_recorded` BETWEEN ? AND ?";
    $school_revenue = [];
    for ($index=0; $index < count($get_term_period)/2; $index++) {
        $time_period = $index == 0 ? [$get_term_period[0],$get_term_period[1]] : ($index == 1 ? [$get_term_period[2],$get_term_period[3]] : [$get_term_period[4],$get_term_period[5]]);
        $term_start = date("Ymd",strtotime($time_period[0]));
        $term_end = date("Ymd",strtotime($time_period[1]));

        // prepare select
        $revenue = 0;
        $stmt = $conn2->prepare($select);
        $stmt->bind_param("ss",$term_start,$term_end);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result){
            if($row = $result->fetch_assoc()){
                $revenue = $row['Total']*1;
            }
        }

        // array push
        array_push($school_revenue,$revenue);
    }
    // $stmt->bind_param("ss",)
    return $school_revenue;
}
function getNHIFContribution_reports($gross_salary)
{
    if ($gross_salary > 0 && $gross_salary <= 5999) {
        return 150;
    } else if ($gross_salary > 5999 && $gross_salary <= 7999) {
        return 300;
    } else if ($gross_salary > 7999 && $gross_salary <= 11999) {
        return 400;
    } else if ($gross_salary > 11999 && $gross_salary <= 14999) {
        return 500;
    } else if ($gross_salary > 14999 && $gross_salary <= 19999) {
        return 600;
    } else if ($gross_salary > 19999 && $gross_salary <= 24999) {
        return 750;
    } else if ($gross_salary > 24999 && $gross_salary <= 29999) {
        return 850;
    } else if ($gross_salary > 29999 && $gross_salary <= 34999) {
        return 900;
    } else if ($gross_salary > 34999 && $gross_salary <= 39999) {
        return 950;
    } else if ($gross_salary > 39999 && $gross_salary <= 44999) {
        return 1000;
    } else if ($gross_salary > 44999 && $gross_salary <= 49999) {
        return 1100;
    } else if ($gross_salary > 49999 && $gross_salary <= 59999) {
        return 1200;
    } else if ($gross_salary > 59999 && $gross_salary <= 69999) {
        return 1300;
    } else if ($gross_salary > 69999 && $gross_salary <= 79999) {
        return 1400;
    } else if ($gross_salary > 79999 && $gross_salary <= 89999) {
        return 1500;
    } else if ($gross_salary > 89999 && $gross_salary <= 99999) {
        return 1600;
    } else if ($gross_salary > 99999) {
        return 1700;
    } else {
        return 0;
    }
}
function getMySalaryBreakdown_report($staff_id, $conn2, $date)
{
    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $staff_id . "';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $salary_breakdown_index = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $effect_month = $row['effect_month'];
            $salary_amount = $row['salary_amount'];

            // all salaries
            $all_salaries = explode(",", $effect_month);
            $salary_amount = explode(",", $salary_amount);
            // first recorded date

            // loop until today and when we reach the month he was paid 
            $salo_amount = 0;
            for ($index = 0; $index < count($all_salaries); $index++) {
                $salary_month = explode(":", $all_salaries[$index]);
                $salo_date = date("Y-m-d", strtotime("01-" . $salary_month[0] . "-" . $salary_month[1]));
                // echo $date ." salo date-> ". $salo_date."<br>";
                if ($date >= $salo_date) {
                    $salo_amount = $salary_amount[$index];
                    $salary_breakdown_index = $index;
                }
            }
            // get the salary breakdown
            $salary_breakdown = $row['salary_breakdown'];

            // check if it has json structure
            if (isJson_report($salary_breakdown)) {
                $new_salo_breakdown = json_decode($salary_breakdown);
                if (is_array($new_salo_breakdown)) {
                    // check if the salary size is the same as the salary breaks size
                    if (count($all_salaries) == count($new_salo_breakdown)) {
                        return $new_salo_breakdown[$salary_breakdown_index];
                    } else {
                        // give the last salary index
                        return $new_salo_breakdown[count($new_salo_breakdown) - 1];
                    }
                } else {
                    return $new_salo_breakdown;
                }
            }
        }
    }
    return null;
}
function getMySalary_reports($staff_id, $conn2, $date)
{
    $first_salary = getFirstPaymentAmount($conn2, $staff_id);
    // get last time he was paid
    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $staff_id . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $first_time_paid = date("Y-m-d", strtotime("01-" . (explode(":", explode(",", $row['effect_month'])[0])[0]) . "-" . explode(":", explode(",", $row['effect_month'])[0])[1]));
        }
    }
    if ($first_time_paid == $date) {
        return $first_salary;
    }
    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '" . $staff_id . "';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $effect_month = $row['effect_month'];
            $salary_amount = $row['salary_amount'];
            $effect_month = $row['effect_month'];

            // all salaries
            $all_salaries = explode(",", $effect_month);
            $salary_amount = explode(",", $salary_amount);
            // first recorded date

            // loop until today and when we reach the month he was paid 
            $salo_amount = 0;
            for ($index = 0; $index < count($all_salaries); $index++) {
                $salary_month = explode(":", $all_salaries[$index]);
                $salo_date = date("Y-m-d", strtotime("01-" . $salary_month[0] . "-" . $salary_month[1]));
                // echo $date ." salo date-> ". $salo_date."<br>";
                if ($date >= $salo_date) {
                    $salo_amount = $salary_amount[$index];
                }
            }
            return $salo_amount;
        }
    }
    return 0;
}
function getSalaryDeductionDetails($conn2, $staff_id, $number, $selected_date = null)
{
    $select = "SELECT * FROM `payroll_information` WHERE `staff_id` = '$staff_id'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $deductions = [];
    $selected_date = $selected_date == null ? date("Y-m-d") : $selected_date;
    $_SESSION['total_advances'] = 0;
    if ($row = $result->fetch_assoc()) {
        $salary_infor = getMySalaryBreakdown_report($staff_id, $conn2, $selected_date);
        if ($salary_infor != null) {
            // $salary_infor = json_decode($row['salary_breakdown']);
            // echo $row['salary_breakdown'];
            $allowances = is_array($salary_infor->allowances) ? $salary_infor->allowances : [];
            $gross_salary = $salary_infor->gross_salary;
            $deduct_paye = $salary_infor->deduct_paye;
            $deducts = isset($salary_infor->deductions) ? $salary_infor->deductions : [];
            // NSSF
            $nssf_rates = $salary_infor->nssf_rates;
            if (strlen($nssf_rates) > 0) {
                $nssf_contribution = getNSSFContribution($gross_salary, $nssf_rates);
                array_push($deductions, array($number, "NSSF Contribution", $nssf_contribution, "30 Day(s)", $nssf_contribution));
                $number++;
            }
            // get P.A.Y.E
            if ($deduct_paye == "yes") {
                $total_allowances = 0;
                for ($ind = 0; $ind < count($allowances); $ind++) {
                    $total_allowances += $allowances[$ind]->value;
                }
                $year = $salary_infor->year;
                $taxable_income = ($total_allowances + $gross_salary) - $nssf_contribution;
                // echo $taxable_income;
                $income_tax = getIncomeTax($taxable_income, $year);
                // echo $income_tax;
                array_push($deductions, array($number, "P.A.Y.E (" . $year . ")", $income_tax, "30 Day(s)", $income_tax));
                $number++;
            }
            // nhif
            $deduct_nhif = $salary_infor->deduct_nhif;
            if ($deduct_nhif == "yes") {
                $nhif_contribution = getNHIFContribution_reports($gross_salary);
                array_push($deductions, array($number, "NHIF Contribution", $nhif_contribution, "30 Day(s)", $nhif_contribution));
                $number++;
            }
            // get defined deductions
            // if (is_array($deducts) > 0) {
            //     // var_dump($deducts);
            //     for ($count_deduct=0; $count_deduct < count($deducts); $count_deduct++) {
            //         array_push($deductions,array($number,$deducts[$count_deduct]->name,$deducts[$count_deduct]->value,"30 Day(s)",$deducts[$count_deduct]->value));
            //     }
            // }
            // get other deductions
            $deduct = isset($salary_infor->deductions) ? $salary_infor->deductions : "";

            if (is_array($deduct)) {
                for ($indexing = 0; $indexing < count($deduct); $indexing++) {
                    array_push($deductions, array($number, $deduct[$indexing]->name, $deduct[$indexing]->value, "30 Day(s)", $deduct[$indexing]->value));
                    $number++;
                }
            }
            // get the advances if there are any only if the current month is seen
            if (isset($_GET['selected_month'])) {
                $selected_month = date("M:Y", strtotime($_GET['selected_month']));
                $select = "SELECT * FROM `advance_pay` WHERE `employees_id` = '" . $staff_id . "'";
                $stmt = $conn2->prepare($select);
                $stmt->execute();
                $result = $stmt->get_result();
                $advances_this_month = 0;
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $payment_breakdown = $row['payment_breakdown'];
                        if (isJson_report($payment_breakdown)) {
                            $pay_breakdown = json_decode($payment_breakdown);
                            for ($index = 0; $index < count($pay_breakdown); $index++) {
                                if ($pay_breakdown[$index]->payment_for == $selected_month) {
                                    $advances_this_month += $pay_breakdown[$index]->amount_paid;
                                    $_SESSION['total_advances'] += $pay_breakdown[$index]->amount_paid;
                                }
                            }
                        }
                    }
                }
                if ($advances_this_month > 0) {
                    array_push($deductions, array($number, $selected_month . " advance payment", $advances_this_month, "30 Day(s)", $advances_this_month));
                    $number++;
                }
            }
        } else {
            array_push($deductions, array($number, "Deductions", 0, "30 Day(s)", 0));
        }
    }
    return $deductions;
}
function getIncomeTax($taxable_income, $year)
{
    if ($year == "2022" || $year == "2023") {
        if ($taxable_income > 24000) {
            $tax = 0;
            // calculate the income $tax
            if ($taxable_income >= 12298) {
                $first_ten = 12298 * 0.1; //10%
                $tax += $first_ten;
                if ($taxable_income >= 23885) {
                    $second = (23885 - 12298) * 0.15; //15%
                    $tax += $second;
                    if ($taxable_income >= 35472) {
                        $third = (35472 - 23885) * 0.2; //20%
                        $tax += $third;
                        if ($taxable_income >= 47059) {
                            $fourth = (47059 - 35472) * 0.25; //25%
                            $tax += $fourth;
                            if ($taxable_income > 47059) {
                                $fifth = ($taxable_income - 47059) * 0.3;
                                $tax += $fifth;
                            }
                        } else {
                            $fourth = ($taxable_income - 35472) * 0.20; //20%
                            $tax += $fourth;
                        }
                    } else {
                        $third = ($taxable_income - 23885) * 0.20; //20%
                        $tax += $third;
                    }
                } else {
                    $second = ($taxable_income - 12299) * 0.15; //15%
                    $tax += $second;
                }
            } else {
                $tax += $taxable_income * 0.1;
            }
            return $tax;
        } else {
            return 0;
        }
    } else if ($year == "2021") {
        $tax = 0;
        if ($taxable_income >= 24000) {
            $tax += (24000 * 0.1);
            if ($taxable_income >= 32333) {
                $tax += (8333 * 0.25);
                if ($taxable_income > 32333) {
                    $tax += ($taxable_income - 32333) * 0.3;
                }
            } else {
                $tax += ($taxable_income - 24000) * 0.25;
            }
        } else {
            $tax += $taxable_income * 0.1;
        }
        return $tax;
    }
}
function getNSSFContribution($gross_salary, $teir)
{
    $teir1 = 0;
    if ($teir == "teir_1") {
        if ($gross_salary >= 6000) {
            $teir1 = 360;
        } else {
            $teir1 = 0.06 * $gross_salary;
        }
        return $teir1;
    } else if ($teir == "teir_1_2") {
        $teir1n2 = 0;
        if ($gross_salary >= 6000) {
            // get the $teir 1
            $teir1n2 += 360;
            if ($gross_salary >= 18000) {
                $teir1n2 += 720;
            } else {
                $teir1n2 += (0.06 * ($gross_salary - 6000));
            }
        } else {
            $teir1n2 = 0.06 * $gross_salary;
        }
        return $teir1n2;
    } else if ($teir == "teir_old") {
        if ($gross_salary >= 200) {
            return 200;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
function reminderMsg($stud_data, $message, $conn2)
{
    $data = $message;
    $studen_admno = $stud_data['adm_no'];
    // get fees to pay by the student
    $feespaidbystud = getFeespaidByStudent($studen_admno, $conn2);
    $fees_paid = "Kes " . number_format($feespaidbystud);
    $term = getTermV2($conn2);
    $balanced = getBalanceReports($studen_admno, $term, $conn2);
    $balance = "Kes " . number_format($balanced);
    $fullname = ucwords(strtolower($stud_data['first_name'] . " " . $stud_data['second_name']));
    $data = str_replace("[student_name]", $fullname, $data);
    $data = str_replace("[student_arrears]", $balance, $data);
    $data = str_replace("[student_fees_paid]", $fees_paid, $data);
    return $data;
}
function getTermV2_exams($conn2)
{
    $date = date("Y-m-d", strtotime("3 hour"));
    $select = "SELECT `term` FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
    // include("../../connections/conn2.php");
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("ss", $date, $date);
    $stmt->execute();
    $results = $stmt->get_result();
    if ($results) {
        if ($rowed = $results->fetch_assoc()) {
            $term = $rowed['term'];
            return $term;
        } else {
            return "TERM_1";
        }
    } else {
        return "TERM_1";
    }

    $stmt->close();
    $conn2->close();
}
function authority($auth)
{
    $data = "";
    if ($auth == 0) {
        $data .= "System Administrator";
    }else if ($auth == "1"){
        $data .= "Principal";
    }else if ($auth == "2"){
        $data .= "Deputy Principal Academics";
    }else if ($auth == "3"){
        $data .= "Deputy Principal Administration";
    }else if ($auth == "4"){
        $data .= "Dean of Students";
    }else if ($auth == "5"){
        $data .= "Finance Office";
    }else if ($auth == "6"){
        $data .= "Human Resource Officer";
    }else if ($auth == "7"){
        $data .= "Head of Department";
    }else if ($auth == "8"){
        $data .= "Trainer/Lecturer";
    } else if ($auth == "9") {
        $data .= "Admissions";
    } else {
        $data .= ucwords(strtolower($auth));
    }
    
    // return data
    return $data;
}
// get specific student details provide conn and student id
function getStudDetail($conn2, $stud_id)
{
    $select = "SELECT * FROM `student_data` WHERE `adm_no` = '$stud_id'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return [];
}
// get students data from a specific class
function getStudents($stud_class, $conn2, $course_id = null)
{
    $student_data = [];
    if ($stud_class != "-1") {
        $select = $course_id == null ? "SELECT * FROM `student_data` WHERE `stud_class` = '" . $stud_class . "'" : "SELECT * FROM `student_data` WHERE `stud_class` = '" . $stud_class . "' AND `course_done` = '".$course_id."'";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($student_data, $row);
            }
        }
    } else {
        $select = "SELECT * FROM `student_data`";
        $stmt = $conn2->prepare($select);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($student_data, $row);
            }
        }
    }
    return $student_data;
}
// check if the student is in class 
function checkPresentStud($classlist, $stud_id)
{
    if (count($classlist) > 0) {
        for ($index = 0; $index < count($classlist); $index++) {
            if ($classlist[$index]['adm_no'] == $stud_id) {
                return $index;
            }
        }
    }
    return -1;
}
function classNameReport($data)
{
    if ($data == "-1") {
        return "Alumni";
    }
    if (strlen($data) > 1) {
        return $data;
    } else {
        return "Grade " . $data;
    }
    return $data;
}
// get the my users
function getStaffData($conn)
{
    $select = "SELECT * FROM `user_tbl` WHERE `school_code` = '" . $_SESSION['schcode'] . "'";
    $stmt = $conn->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // echo $row['user_id'];
            // $my_data = array("user_id"=>$row['user_id'], "fullname" => $row['fullname']);
            array_push($user_data, $row);
        }
    }
    return $user_data;
}
function checkPresentReport($array, $data)
{
    if (count($array) > 0) {
        for ($index = 0; $index < count($array); $index++) {
            if ($array[$index] == $data) {
                return true;
            }
        }
    }
    return false;
}

function getStaffNamedReport($array_data, $staff_id)
{
    if (count($array_data) > 0) {
        for ($ind = 0; $ind < count($array_data); $ind++) {
            if ($staff_id == $array_data[$ind]['user_id']) {
                return $array_data[$ind]['fullname'];
            }
        }
    }
    return "Null";
}

function getStaffDets($array_data, $staff_id)
{
    if (count($array_data) > 0) {
        for ($ind = 0; $ind < count($array_data); $ind++) {
            if ($staff_id == $array_data[$ind]['user_id']) {
                return array($array_data[$ind]['phone_number'], $array_data[$ind]['address'], $array_data[$ind]['auth']);
            }
        }
    }
    return array("Null", "Null");
}
function schoolLogo($conn)
{
    $select = "SELECT * FROM `school_information` WHERE `school_code` = '" . $_SESSION['schcode'] . "'";
    $stmt = $conn->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return $row['school_profile_image'];
        }
    }
    return "";
}
function getSchoolCLass($conn2)
{
    $select = "SELECT * FROM `settings` WHERE `sett` = 'class'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $classes = isJson_report($row['valued']) ? json_decode($row['valued']) : [];
            $class_list = [];
            for($index = 0; $index < count($classes); $index++){
                array_push($class_list,$classes[$index]->classes);
            }
            return $class_list;
        }
    }
    return [];
}
function getDormitory($stud_id, $conn2)
{
    $select = "SELECT * FROM `boarding_list` WHERE `student_id` = '$stud_id'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $dorm_id = $row['dorm_id'];
            $select = "SELECT * FROM `dorm_list` WHERE `dorm_id` = '$dorm_id'";
            $stmt = $conn2->prepare($select);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row['dorm_name'];
            }
        }
    }
    return "Null";
}

function getRouteEnrolled($stud_id, $conn2)
{
    // return $stud_id;
    $select = "SELECT * FROM `transport_enrolled_students` WHERE `student_id` = '" . $stud_id . "'";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $deregistered = $row['deregistered'];
            if (isJson_report_fin($deregistered)) {
                $deregistered = json_decode($deregistered);
                $route_details = [];
                
                for($index = 0; $index < count($deregistered); $index++){
                    $route_data = new stdClass();
                    $route_data->route_id = $deregistered[$index]->route;
                    $route_data->term = $deregistered[$index]->term;

                    //create a json object to hold the route data
                    $select = "SELECT * FROM `van_routes` WHERE route_id = '".$deregistered[$index]->route."'";
                    $stmt = $conn2->prepare($select);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result) {
                        if ($row = $result->fetch_assoc()) {
                            $route_name = $row['route_name'];
                            $route_price = $row['route_price'];
                            $route_areas = $row['route_areas'];
                            // return array($route_price, $stoppage, $route_name, $route_areas);

                            $route_data->route_name = $route_name;
                            $route_data->route_price = $route_price;
                            $route_data->route_areas = $route_areas;
                        }
                    }

                    // add arrays
                    array_push($route_details,$route_data);
                }
                return $route_details;
            }
        }
    }
    return [];
}
function getStaffInformations_report($conn, $id)
{
    $select = "SELECT * FROM `user_tbl` WHERE `user_id` = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return [];
}
function getFirstPayDate_Report($conn2, $staff_id)
{
    $select = "SELECT `effect_month` FROM `payroll_information` WHERE `staff_id` = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $first_month = "";
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $first_month = $row['effect_month'];
        }
    }
    if (strlen($first_month) > 0) {
        $divide_mon = explode(",", $first_month);
        $first_month = $divide_mon[0];
    }
    return $first_month;
}
function getSalary_Report($dates, $conn2, $staff_id, $first_salary = -1)
{
    $first_pay = getFirstPayDate_Report($conn2, $staff_id);
    $select = "SELECT `effect_month`, `salary_amount` FROM `payroll_information` WHERE `staff_id` = ?";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("s", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $times = "";
    $salary = "";
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $times = $row['effect_month'];
            $salary = $row['salary_amount'];
        }
    }
    $f_date = explode(":", $first_pay);
    $f_d_date = date("Y-m-d", strtotime("01-" . $f_date[0] . "-" . $f_date[1]));
    if ($f_d_date == $dates && $first_salary != -1) {
        // echo $first_salary." ".$f_d_date;
        return $first_salary;
    }
    if (isset($times) && strlen($times) > 0) {
        $time_divide = explode(",", $times);
        if (count($time_divide) == 1) {
            return $salary;
        } elseif (count($time_divide) > 1) {
            $exploded_salo = explode(",", $salary);
            for ($index = 0; $index < count($time_divide); $index++) {
                $epl_time = explode(":", $time_divide[$index]);
                if ($index + 1 < count($time_divide)) {
                    $nextMonth = explode(":", $time_divide[$index + 1]);
                } else {
                    $count = count($exploded_salo);
                    return $exploded_salo[$count - 1];
                    break;
                }
                $date_now = date("Y-m-d", strtotime("01-" . $epl_time[0] . "-" . $epl_time[1]));
                $next_mon = date("Y-m-d", strtotime("01-" . $nextMonth[0] . "-" . $nextMonth[1]));
                if ($dates >= $date_now && $dates < $next_mon) {
                    return $exploded_salo[$index];
                    break;
                }
            }
        }
    } else {
        return 0;
    }
}
// get payes
function getPaye_Report($taxable_income, $year)
{
    // console.log(taxable_income);
    if ($year == "2022" || $year == "2023") {
        if ($taxable_income > 24000) {
            $tax = 0;
            // calculate the income $tax
            if ($taxable_income >= 12298) {
                $first_ten = 12298 * 0.1; //10%
                $tax += $first_ten;
                if ($taxable_income >= 23885) {
                    $second = (23885 - 12298) * 0.15; //15%
                    $tax += $second;
                    if ($taxable_income >= 35472) {
                        $third = (35472 - 23885) * 0.2; //20%
                        $tax += $third;
                        if ($taxable_income >= 47059) {
                            $fourth = (47059 - 35472) * 0.25; //25%
                            $tax += $fourth;
                            if ($taxable_income > 47059) {
                                $fifth = ($taxable_income - 47059) * 0.3;
                                $tax += $fifth;
                            }
                        } else {
                            $fourth = ($taxable_income - 35472) * 0.20; //20%
                            $tax += $fourth;
                        }
                    } else {
                        $third = ($taxable_income - 23885) * 0.20; //20%
                        $tax += $third;
                    }
                } else {
                    $second = ($taxable_income - 12299) * 0.15; //15%
                    $tax += $second;
                }
            } else {
                $tax += $taxable_income * 0.1;
            }
            return $tax;
        } else {
            return 0;
        }
    } else if ($year == "2021") {
        $tax = 0;
        if ($taxable_income >= 24000) {
            $tax += (24000 * 0.1);
            if ($taxable_income >= 32333) {
                $tax += (8333 * 0.25);
                if ($taxable_income > 32333) {
                    $tax += ($taxable_income - 32333) * 0.3;
                }
            } else {
                $tax += ($taxable_income - 24000) * 0.25;
            }
        }
        return $tax;
    }
}
function getAcademicStartV1_reports($conn2, $term = "TERM_1")
{
    $select = "SELECT * FROM `academic_calendar` WHERE `term` = '" . $term . "';";
    $stmt = $conn2->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return [$row['start_time'], $row['end_time']];
        }
    }
    return [date('Y') . "-01-01", date('Y') . "-01-30"];
}
function presentStats_report($conn2, $admno, $class_student)
{
    // get the current term its starting period and ending period
    $term = "TERM_1";
    // get when the term is starting and ending
    $calender = getAcademicStartV1_reports($conn2, $term);
    // return $calender[0]." - ".$calender[1];
    // get the total number of days this term we have called register
    $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `class` = '" . $class_student . "'";
    $stmt = $conn2->prepare($select);
    $today = date("Y-m-d");
    $stmt->bind_param("ss", $calender[0], $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_attendance = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $total_attendance = $row['Totals'];
        }
    }
    // get the students attendance report
    $select = "SELECT COUNT(DISTINCT `date`) AS 'Totals' FROM `attendancetable` WHERE `date` >= ? AND `date` <= ? AND `admission_no` = '" . $admno . "' AND `class` = '" . $class_student . "'";
    $stmt = $conn2->prepare($select);
    $stmt->bind_param("ss", $calender[0], $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $student_attendance = 0;
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            $student_attendance = $row['Totals'];
        }
    }
    $percentages = ($total_attendance > 0 ? round(($student_attendance / $total_attendance) * 100, 1) : 0);
    // return "".$student_attendance." out of ". $total_attendance.".  <span class='text-primary'>(".$percentages."%)</small>";
    return [$student_attendance, $total_attendance, $percentages];
}
function isJson_reports($string)
{
    return ((is_string($string) &&
        (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
}

function getSchoolInfo($conn)
{
    $select = "SELECT * FROM `school_information` WHERE `school_code` = '" . $_SESSION['schcode'] . "'";
    $stmt = $conn->prepare($select);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return [];
}

function returnNumbers($number){
    $split_no = explode(" ",$number);
    $text = $split_no[1];

    $new_string = "";
    for ($index=0; $index < strlen($text); $index++) { 
        if($text[$index] != ","){
            $new_string.=$text[$index];
        }
    }
    return $new_string;
}

class NUmbers{
    function convert_number($number)
    {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = "";
        if ($giga) {
            $result .= $this->convert_number($giga) .  " Million";
        }
        if ($kilo) {
            $result .= (empty($result) ? "" : " ") . $this->convert_number($kilo) . " Thousand";
        }
        if ($hecto) {
            $result .= (empty($result) ? "" : " ") . $this->convert_number($hecto) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($deca || $n) {
            if (!empty($result)) {
                $result .= " and ";
            }
            if ($deca < 2) {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n) {
                    $result .= "-" . $ones[$n];
                }
            }
        }
        if (empty($result)) {
            $result = "zero";
        }
        return $result;
    }
}
function getTermAdmin(){
    $date = date("Y-m-d");
    $select = "SELECT `term` FROM `academic_calendar` WHERE `end_time` >= ? AND `start_time` <= ?";
    include("../../connections/conn2.php");
    $stmt= $conn2->prepare($select);
    $stmt->bind_param("ss",$date,$date);
    $stmt->execute();
    $results = $stmt->get_result();
    if($results){
        if ($rowed = $results->fetch_assoc()) {
          $term = $rowed['term'];
          return $term;
        }else {
          return "TERM_1";
        }
    }else {
        return "TERM_1";
      }
    
    $stmt->close();
    $conn2->close();
}

function receiptNo($no){
    if (strlen($no) < 3) {
        if(strlen($no) == 2){
            return "0".$no;
        }else{
            return "00".$no;
        }
    }
    return $no;
}
