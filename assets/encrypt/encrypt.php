<?php


function encryptCode($dataToEncrypt){
    //first get char code for each name
    $revdata = strrev($dataToEncrypt);
    $data = str_split($revdata);
    $encrpted = "";
    for($y=0;$y<count($data);$y++){
        $encrpted.=getCode($data[$y]);
    }
    $encrypted = strrev($encrpted); 
    return $encrypted;   
}
function decryptcode($datatodecrypt){
    $arrayeddata = str_split(strrev($datatodecrypt),3);
    $data="";
    for ($i=0; $i < count($arrayeddata); $i++) { 
        $data.=$arrayeddata[$i];
    }
    return strrev($data);
}

function getCode($code){

    if($code=='A'){
        return '$rSv';
    }elseif ($code=='B') {
        return 'Grp2';
    }elseif ($code=='C') {
        return 'SnMp';
    }elseif ($code=='D') {
        return 'Tr#4';
    }elseif ($code=='E') {
        return '69!4';
    }elseif ($code=='F') {
        return 'PpQr';
    }elseif ($code=='G') {
        return 'TpSO';
    }elseif ($code=='H') {
        return 'IvSr';
    }elseif ($code=='I') {
        return 'LpTs';
    }elseif ($code=='J') {
        return 'L496';
    }elseif ($code=='K') {
        return '674S';
    }elseif ($code=='L') {
        return 'IqRs';
    }elseif ($code=='M') {
        return 'Rama';
    }elseif ($code=='N') {
        return 'Kilo';
    }elseif ($code=='O') {
        return 'PorT';
    }elseif ($code=='P') {
        return 'Stea';
    }elseif ($code=='Q') {
        return 'aTeM';
    }elseif ($code=='R') {
        return '#4@p';
    }elseif ($code=='S') {
        return '*9$N';
    }elseif ($code=='T') {
        return 'NiPs';
    }elseif ($code=='U') {
        return 'IobT';
    }elseif ($code=='V') {
        return 'PpRT';
    }elseif ($code=='W') {
        return 'wTvs';
    }elseif ($code=='X') {
        return 'SunT';
    }elseif ($code=='Y') {
        return 'umRT';
    }elseif ($code=='Z') {
        return 'PrS!';
    }elseif ($code=='a') {
        return 'ooEV';
    }elseif ($code=='b') {
        return 'EmpT';
    }elseif ($code=='c') {
        return 'Rt@P';
    }elseif ($code=='d') {
        return '#41B';
    }elseif ($code=='e') {
        return 'Yeyo';
    }elseif ($code=='f') {
        return 'ZxMU';
    }elseif ($code=='g') {
        return 'LuMk';
    }elseif ($code=='h') {
        return 'SaWa';
    }elseif ($code=='i') {
        return 'Eaws';
    }elseif ($code=='j') {
        return 'GliM';
    }elseif ($code=='k') {
        return 'NoNS';
    }elseif ($code=='l') {
        return 'SiIB';
    }elseif ($code=='m') {
        return 'prEA';
    }elseif ($code=='n') {
        return 'ApEM';
    }elseif ($code=='o') {
        return 'MoeN';
    }elseif ($code=='p') {
        return 'NoST';
    }elseif ($code=='q') {
        return 'SeTs';
    }elseif ($code=='r') {
        return 'RasP';
    }elseif ($code=='s') {
        return 'PaRT';
    }elseif ($code=='t') {
        return 'TrUs';
    }elseif ($code=='u') {
        return 'LuTr';
    }elseif ($code=='v') {
        return 'rGgT';
    }elseif ($code=='w') {
        return 'S@sY';
    }elseif ($code=='x') {
        return 'YeTr';
    }elseif ($code=='y') {
        return 'GeTr';
    }elseif ($code=='z') {
        return 'TrSe';
    }elseif ($code=='0') {
        return 'OE#@';
    }elseif ($code=='1') {
        return 'PsT@';
    }elseif ($code=='2') {
        return 'TrO$';
    }elseif ($code=='3') {
        return '$sTp';
    }elseif ($code=='4') {
        return 'qoRp';
    }elseif ($code=='5') {
        return '?GrP';
    }elseif ($code=='6') {
        return 'OeMr';
    }elseif ($code=='7') {
        return 'StmR';
    }elseif ($code=='8') {
        return 'EpR!';
    }elseif ($code=='9') {
        return 'StpS';
    }elseif ($code==' ') {
        return 'tP#3';
    }else{
        return "";
    }
}

function getChar($code){
    if($code=='$rSv'){
        return 'A';
    }elseif ($code=='Grp2') {
        return 'B';
    }elseif ($code=='SnMp') {
        return 'C';
    }elseif ($code=='Tr#4') {
        return 'D';
    }elseif ($code=='69!4') {
        return 'E';
    }elseif ($code=='PpQr') {
        return 'F';
    }elseif ($code=='TpSO') {
        return 'G';
    }elseif ($code=='IvSr') {
        return 'H';
    }elseif ($code=='LpTs') {
        return 'I';
    }elseif ($code=='L496') {
        return 'J';
    }elseif ($code=='674S') {
        return 'K';
    }elseif ($code=='IqRs') {
        return 'L';
    }elseif ($code=='Rama') {
        return 'M';
    }elseif ($code=='Kilo') {
        return 'N';
    }elseif ($code=='PorT') {
        return 'O';
    }elseif ($code=='Stea') {
        return 'P';
    }elseif ($code=='aTeM') {
        return 'Q';
    }elseif ($code=='#4@p') {
        return 'R';
    }elseif ($code=='*9$N') {
        return 'S';
    }elseif ($code=='NiPs') {
        return 'T';
    }elseif ($code=='IobT') {
        return 'U';
    }elseif ($code=='PpRT') {
        return 'V';
    }elseif ($code=='wTvs') {
        return 'W';
    }elseif ($code=='SunT') {
        return 'X';
    }elseif ($code=='umRT') {
        return 'Y';
    }elseif ($code=='PrS!') {
        return 'Z';
    }elseif ($code=='ooEV') {
        return 'a';
    }elseif ($code=='EmpT') {
        return 'b';
    }elseif ($code=='Rt@P') {
        return 'c';
    }elseif ($code=='#41B') {
        return 'd';
    }elseif ($code=='Yeyo') {
        return 'e';
    }elseif ($code=='ZxMU') {
        return 'f';
    }elseif ($code=='LuMk') {
        return 'g';
    }elseif ($code=='SaWa') {
        return 'h';
    }elseif ($code=='Eaws') {
        return 'i';
    }elseif ($code=='GliM') {
        return 'j';
    }elseif ($code=='NoNS') {
        return 'k';
    }elseif ($code=='SiIB') {
        return 'l';
    }elseif ($code=='prEA') {
        return 'm';
    }elseif ($code=='ApEM') {
        return 'n';
    }elseif ($code=='MoeN') {
        return 'o';
    }elseif ($code=='NoST') {
        return 'p';
    }elseif ($code=='SeTs') {
        return 'q';
    }elseif ($code=='RasP') {
        return 'r';
    }elseif ($code=='PaRT') {
        return 's';
    }elseif ($code=='TrUs') {
        return 't';
    }elseif ($code=='LuTr') {
        return 'u';
    }elseif ($code=='rGgT') {
        return 'v';
    }elseif ($code=='S@sY') {
        return 'w';
    }elseif ($code=='YeTr') {
        return 'x';
    }elseif ($code=='GeTr') {
        return 'y';
    }elseif ($code=='TrSe') {
        return 'z';
    }elseif ($code=='OE#@') {
        return '0';
    }elseif ($code=='PsT@') {
        return '1';
    }elseif ($code=='TrO$') {
        return '2';
    }elseif ($code=='$sTp') {
        return '3';
    }elseif ($code=='qoRp') {
        return '4';
    }elseif ($code=='?GrP') {
        return '5';
    }elseif ($code=='OeMr') {
        return '6';
    }elseif ($code=='StmR') {
        return '7';
    }elseif ($code=='EpR!') {
        return '8';
    }elseif ($code=='StpS') {
        return '9';
    }elseif ($code=='tP#3') {
        return ' ';
    }else{
        return "";
    }
}
?>