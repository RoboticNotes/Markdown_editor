<?php
/* No usar los simbolos ^ para enviar valores a la db 
Ejemplo de ingreso : conectar('localhost:3306', 'kanekcom_prueba', 'kanekcom_admin', 'Chopin2019!');*/
function conectar($servidor, $db, $user, $pass ){
	$con = @mysql_connect($servidor, $user, $pass);
	@mysql_select_db($db, $con);	
}

function insertDBM($table, $array){
	$keys = implode(', ', array_keys($array));
	$values = convertVals($array);
	$result = @mysql_query('INSERT INTO '.$table.' ('.$keys.') VALUES ('.$values.')');
	return($result? true: false);
}


function setDBM($table, $array, $whereCond){
	$modify=convertOfKeysAndValues($array);
	$result=@mysql_query('UPDATE '.$table.' SET '.$modify.' WHERE '.$whereCond.'');
	return($result? true: false);
}

function existDBM($table, $whereCond){
	$modify=convertOfKeysAndValues($array);
	$query=@mysql_query('SELECT * FROM '.$table.' WHERE '.$whereCond.'');
	$result = @mysql_fetch_object($query);
	return($result? true: false);
}

function queryDBM($table, $whereCond){
	$query=@mysql_query('SELECT * FROM '.$table.' WHERE '.$whereCond.'');
	$return=array();
	while($result= mysql_fetch_array($query)){
		array_push($return, $result);
	}
	return($return);
}

function advanceSearchDBM($table, $whereCond, $sentenceToSearch){
	$filter = explode(" ", $sentenceToSearch);
	$search= convertOfKeysAndValuesAdnvanceSearch($whereCond, $sentenceToSearch, 0);
	for($i = 1; $i < count($filter); $i++) {
        if(!empty($filter[$i])) {
            $search.= " AND ". convertOfKeysAndValuesAdnvanceSearch($whereCond, $sentenceToSearch, 0);
        }
	}
	$query=@mysql_query('SELECT * FROM '.$table.' WHERE '.$search.'');
	$return=array();
	while($result= mysql_fetch_array($query)){
		array_push($return, $result);
	}
	return($return);
}

function convertVals($array){ // convierte los valores para que puedan ser usados e insertados en la db
	$store=array();
	foreach ($array as $key => $value) {
    array_push($store, '"'.$value.'"');
	}
	$convertion= implode(', ', array_values($store));
	return($convertion);
}

function convertOfKeysAndValues($array){ // convierte los valores y claves para que puedan ser usados e insertados en la db
	$store=array();
	foreach ($array as $key => $value) {
    array_push($store, $key.'="'.$value.'"');
	}
	$convertion= implode(', ', array_values($store));
	return($convertion);
}

function convertOfKeysAndValuesAdnvanceSearch($array, $sentenceToSearch, $i){ // convierte los valores y claves para que puedan ser usados e insertados en la db
	$filter = explode(" ", $sentenceToSearch);
	$store=array();
	foreach ($array as $key => $value) {
    array_push($store, "$value LIKE '%$filter[$i]%'");
	}
	$convertion= implode(' OR ', array_values($store));
	$convertion='('.$convertion.')';
	return($convertion);
}


?>
