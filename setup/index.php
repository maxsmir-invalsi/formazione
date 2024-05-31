<?php
if(!isset($_GET["page"])){
	$_GET["page"] = 'setup';
	//echo "Necessario specificare la pagina da richiedere";
	//exit;
}


include "classes/DB_Communicator.php";
global $db;
$db = new DB_Communicator();

include "support/commons.php";


$folder_scripts = "moduli/".$_GET["page"]."/";
$div_script = "div_".$_GET["page"].".php";
$js_script = "js_".$_GET["page"].".php";


//REDIRECT per DIXIT [usato su ALTERVISTA].
/*if(strpos($_GET["page"], "dixit_")===0){
	$param = "";
	$primo = true;
	foreach ($_GET as $key => $value) {
		if($primo){
			$primo = false;
		}else{
			$param .= "&";
		}

		$param .= $key."=".$value;
	}
	header("location: ".DOMAIN."/".MAIN_FOLDER."/?".$param);
	exit();
}*/

//se sono in modalità solo testo allora non carico il JS
$response_type = (!array_key_exists('response_type', $_GET) ? 'html' : $_GET['response_type']);

if($response_type == 'html') {
?>

	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<style>
				<?php include "support/style.css"; ?>
			</style>
			<script src="support/jquery-3.1.1.min_1_.js"></script>
		</head>
		
		<body>
			<?php if(file_exists($folder_scripts.$div_script)){ include $folder_scripts.$div_script; } ?>
			<?php if(file_exists($folder_scripts.$js_script)){ include $folder_scripts.$js_script; } ?>
		</body>
	</html>

<?php
} elseif($response_type == 'text') {
	if(file_exists($folder_scripts.$div_script)){ include $folder_scripts.$div_script; } 
}
?>