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
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
  $app = new \Slim\App;

  $app->get('/', function () {
	  
	
	  
  });

  $app->get('/consulta/{p}', function (Request $request, Response $response) {
	  
	  $t = $request->getAttribute('p');
	  $db = Conectar::conexion();
	  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE HOTEL_NAME = '$t'");
	  $consulta->execute();
	  $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
	  echo (json_encode($resultado));
	  
  });

  $app->get('/awita', function () {

    echo "papi";

  });

  $app->get('/cat', function () {

    echo "c'est une prueve";

  });


  $app->run();

?>