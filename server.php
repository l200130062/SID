<?php
require_once "lib/nusoap.php";
function getInfo($name){
  //provide information details
	$data['alvian']['nama'] = 'Alvian';
	$data['alvian']['nim'] = 'L200130062';
	if (isset($data[$name])){
		return json_encode($data[$name]);
	}else{
		return "{$name}? what kind of food is that??";
	}
}

$server = new nusoap_server();
$server->configureWSDL("server","urn:server");
$server->register(
	"getInfo",
	array("name" => "xsd:string"),
	array("return" => "xsd:string"),
	"urn:one",
	"urn:one#getInfo",
	"rpc",
	"encoded",
	"get info sajalah"
	);
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
