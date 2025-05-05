<?php
$my_file = "infor.text";
$data = date("D dS M Y H:i:s");
file_put_contents($my_file,$data);

?>