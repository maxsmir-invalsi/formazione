<?php
ini_set('memory_limit', '1024M');
session_start();	//Inizia la sessione


//registro le informazioni di accesso
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$data = array();
$data["SESSION_USER"] = @$_SESSION["user"];
$data["GET"] = $_GET;
$data["POST"] = $_POST;

function get_client_ip(){
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}


function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}


//=========================================
//===============REGOLE DIXIT==============
//=========================================

function take_get_post_params(){
	$param = ( isset($_GET["page"]) ? $_GET : $_POST );
	$get_p = $param["page"];
	foreach ($param as $key => $value) {
		$get_p .= "&".$key."=".$value;
	}

	return $get_p;
}


function post_par(){
	echo "page: '".$_GET["page"]."',";
}

function success_ajax(){
	echo "var r = JSON.parse(res);";
}

function getRootAddress(){
	return 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'], "/"));
}

//Funzione per generare una query eseguibile a partire da $q e $p
function montaQuery($q, $p){
	$parts = explode("?", $q);

	$resQ = "";
	for ($i=0; $i < count($parts); $i++) {
		if($i < count($p)){
			$resQ .= $parts[$i]."'".$p[$i]."'";
		}else{
			$resQ .= $parts[$i];
		}
	}

	return $resQ;
}
