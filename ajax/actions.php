<?php
session_start();
include('../PHP/funciones.php');
conectar('localhost:3306', 'sakbecom_freelance', 'sakbecom_ulisses', 'Chopin2019!');

//cambiar documento
if($_POST['type']=="change"){
	$files=queryDBM('documents', 'id="'.$_POST['id'].'"');
	$_SESSION['id']=$_POST['id'];
	echo($files[0]['info']);
}

//Guardar documento
if($_POST['type']=="save"){
	$insert=array(
	'info'=>mysql_real_escape_string ($_POST['info']),
	'date'=>date("Y-m-d H:i:s"));
	$set=setDBM('documents', $insert, 'id="'.$_POST['id'].'"');
}

//Borrar documento
if($_POST['type']=="delete"){
	@mysql_query('DELETE FROM documents WHERE id="'.$_POST['id'].'"');
}

//Nuevo documento
if($_POST['type']=="new"){
	$insert=array(
	'name'=>$_POST['name'],
	'date'=>date("Y-m-d H:i:s"));
	insertDBM('documents', $insert);
}
?>