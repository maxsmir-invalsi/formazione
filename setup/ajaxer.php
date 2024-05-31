<?php
//CONTROLLI PER LA SICUREZZA
if(!isset($_POST["page"])){
	echo "Necessario specificare la pagina da richiedere";
	exit;
}


include "classes/DB_Communicator.php";
global $db;
$db = new DB_Communicator();

include "support/commons.php";

$folder_scripts = "moduli/".$_POST["page"]."/";
$php_script = "php_".$_POST["page"].".php";

if(file_exists($folder_scripts.$php_script)){ include $folder_scripts.$php_script; }
echo json_encode($out);
