<?php
function note_10A($conn2)
{
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
    return 0;
}
