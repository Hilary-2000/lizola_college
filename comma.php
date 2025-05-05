<?php
    function comma($number){
        // echo $number." ".is_numeric($number)."<br>";
        $data = explode(".",$number);
        $wholenumber = $data[0];
        $negative = substr($wholenumber,0,1);
        $negate = "";
        if ($negative == "-") {
            $negate = "-";
            $wholenumber = substr($wholenumber,1,strlen($wholenumber));
        }
        elseif ($negative == "+") {
            $negate = "+";
            $wholenumber = substr($wholenumber,1,strlen($wholenumber));
        }
        $reverse = strrev($wholenumber);
        $tt = "";
        for ($index=0; $index < strlen($reverse); $index++) { 
            if ( ($index+1) % 3 == 0 && $index!=0) {
                if (($index+2)>strlen($reverse)) {
                    $tt.=$reverse[$index];
                }else{
                    $tt.=$reverse[$index].",";
                }
            }else{
                $tt.=$reverse[$index];
            }
        }
        if (count($data)>1) {
            return $negate.strrev($tt).".".$data[1];
        }else{
            return $negate.strrev($tt);
        }
    }
?>