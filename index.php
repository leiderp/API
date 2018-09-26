<?php
  require "vendor/autoload.php";
	class Conectar{
			public static function conexion(){
				$usr="root";
				$psswrd="";
				try{
					$conexion = new PDO('mysql:host=localhost;dbname=hoteles_db',$usr,$psswrd);
				}catch(Exception $e){
					die("Error" .$e->getMessage());
					echo "Linea del error" . $e->getLine();
				}
				return $conexion;
			}
		}
  $app = new \Slim\App;

  $app->get('/', function () {
	  $db = Conectar::conexion();
	  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE state = 'KERALA' and PHONE = '0477-2230767'");
	  $consulta->execute();
	  $resultado = $consulta->fetchAll();
	  $items = $consulta->rowCount();
	  for ($i=0; $i <= ($items-1); $i++) { 
		  echo $resultado[$i]['HOTEL NAME'];
		  echo nl2br("\n");
		  echo $resultado[$i]['ADDRESS'];
		  echo nl2br("\n");
		  echo $resultado[$i]['STATE']; 
		  echo nl2br("\n");
		  echo $resultado[$i]['PHONE'];
		  echo nl2br("\n");
		  echo $resultado[$i]['FAX'];
		  echo nl2br("\n");
		  echo $resultado[$i]['EMAIL ID'];
		  echo nl2br("\n");
		  echo $resultado[$i]['WEBSITE'];
		  echo nl2br("\n");
		  echo $resultado[$i]['TYPE'];
		  echo nl2br("\n");
		  echo $resultado[$i]['Rooms'];
	  }
  });

  $app->get('/cincoestrellas', function () {

    echo "hoteles";

  });

  $app->get('/awita', function () {

    echo "papi";

  });

  $app->run();

?>
