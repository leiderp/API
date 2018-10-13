<?php
function llenar (){
	  $j = 1;
	  $db = Conectar::conexion();
	  while($j <= 301){
		  $sql = "SELECT * FROM `informacion` WHERE ID_HOTEL = '$j'";
		  $stmt = $db->prepare($sql);
		  $stmt->execute();
		  $resultado = $stmt->fetchAll();
	      $n = $resultado[0]['Rooms'];
		  $sw = 0;
		  if($sw == 0){
			  $i = 1;
			  while($i <= $n){
				  $sql =  "INSERT INTO habitaciones (id_hotel) VALUES ($j)";
				  $stmt = $db->prepare($sql);
				  $stmt->execute();
				  $i = $i + 1;
			  }
			  $sw = 1;
	     }
		  $j = $j + 1;
	  }
  }
?>