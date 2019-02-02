<?php 
session_start();
include('PHP/funciones.php');
//Conectar a la base de datos
conectar('localhost:3306', 'sakbecom_freelance', 'sakbecom_ulisses', 'Chopin2019!');
//Crear la tabla si no existe y si existe bajar la informacion
$exist = mysql_query('select 1 from documents LIMIT 1');

if($exist !== FALSE)
{
   $files=queryDBM('documents', 'id!=0 ORDER BY id DESC');
   $numFiles=count($files);
   $_SESSION['id']=$files[0][id];
}
else
{
    @mysql_query("CREATE TABLE documents (id INT AUTO_INCREMENT PRIMARY KEY, name TEXT NOT NULL, info TEXT NOT NULL, date TEXT NOT NULL) COLLATE utf8_general_ci ");
}
//fecha date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Markdown editor</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.7.1/showdown.min.js"></script>
    <script src="js/bcsocket.js"></script>
    <script src="js/share.uncompressed.js"></script>
    <script src="js/textarea.js"></script>
    <script src="js/script.js"></script>
    
    <!--sweet alert-->
  	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</head>

<body class="container-fluid">
	<section class="row">
       <div class="col-md-2 full-height" id="panel">
       		<div id="title" align="center"><h2>Markdown editor</h2><br><p style="margin-top: -25px">Ulisses Mercado</p></div>
       		<div id="tools" align="center">
       			<img src="images/new.png" id="new" onClick="newDocument()">
       			<img src="images/trash.png" id="trash" onClick="deleteDocument()">
       			<!--<img src="images/download.png" id="download" onClick="alert('Descargar documento')">-->
       			<img src="images/save.png" id="save" onClick="saveDocument()">
       			<img src="images/html.png" id="html" onClick="convertToHTML()">
       		</div>
       		<div id="scroll" align="center">
       		<?php
				for($i=0; $i<$numFiles; $i++){
			?>
				<div class="<?php if($i==0){echo('documentChange');}else{echo('document');}?>" id="<?php echo($files[$i]['id']); ?>" onClick="changeDocument(this)">
       				<table style="width:100%">
					  <tr rowspan="2">
						<td width="30%"><img src="images/document.png" id="documentImg" ></td>
						<td>
							<table style="width:100%">
						  <tr>
							<td style="font-size: 20px;"><?php echo($files[$i]['name']); ?></td>
						  </tr>
						  <tr>
							<td style="font-size: 11px;"><?php echo($files[$i]['date']); ?></td>
						  </tr>
						</table>
						</td>
					  </tr>
					</table>
       			</div>
			<?php
				}
			?>
       		</div>
       </div>
        <textarea class="col-md-5 full-height" id="pad"><?php echo($files[0]['info']);?>
        </textarea>
        <div class="col-md-5 full-height" id="markdown"></div>
        <div class="col-md-5 full-height" id="intoHTML" style="display: none;"></div>
    </section>
</body>
</html>

<!--jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
//initial identifier
id =<?php echo($_SESSION['id']); ?>;
//Change document
function changeDocument(me){
	$(".documentChange").attr('class', 'document');
	$(me).attr('class', 'documentChange');
	id = $(me).attr("id");
	$.ajax({
		  type:'POST', 
		  data:{'id':id ,
				'type':"change"},  
		  url:'ajax/actions.php',
		  success:function(data){
			  pad.value=data;
			  $("#intoHTML").html('<xmp>'+html+'</xmp>');
		  }
	  });  
}
//save document
function saveDocument(){
	$.ajax({
		  type:'POST', 
		  data:{'id':id ,
				'type':"save",
			   	'info':$('#pad').val()},  
		  url:'ajax/actions.php',
		  success:function(){
			  Swal.fire({
				  position: 'top-end',
				  type: 'success',
				  title: 'Your document has been saved',
				  showConfirmButton: false,
				  timer: 2000
				})
		  }
	  });  
}
//Delete document
function deleteDocument(){
	$.ajax({
		  type:'POST', 
		  data:{'id':id ,
				'type':"delete"},  
		  url:'ajax/actions.php',
		  success:function(){
			  Swal.fire({
				  position: 'top-end',
				  type: 'success',
				  title: 'Your document has been delete',
				  showConfirmButton: false,
				  timer: 2000
				})
			  location.reload();
		  }
	  }); 
}
//Nuevo documento
function newDocument(){
Swal.fire({
  title: 'Insert a good name',
  input: 'text',
  inputAttributes: {
    autocapitalize: 'off'
  },
  showCancelButton: true,
  confirmButtonText: 'Accept',
  showLoaderOnConfirm: true,
  preConfirm: (name) => {
	  $.ajax({
		  type:'POST', 
		  data:{'type':"new",
			    'name':name},  
		  url:'ajax/actions.php',
		  success:function(){
			  Swal.fire({
				  position: 'top-end',
				  type: 'success',
				  title: 'Your document has been saved',
				  showConfirmButton: false,
				  timer: 2000
				})
			  location.reload();
		  }
	  }); 
  }
  })
}
function convertToHTML(){
	$("#html").attr('id', 'normal');
	$("#markdown").css('display', 'none');
	$("#intoHTML").html('<xmp>'+html+'</xmp>');
	$("#intoHTML").css('display', 'block');
	$("#normal").attr('onclick', 'returnView()');
}
function returnView(){
	$("#normal").attr('id', 'html');
	$("#markdown").css('display', 'block');
	$("#intoHTML").css('display', 'none');
	$("#html").attr('onclick', 'convertToHTML()');
}
</script>