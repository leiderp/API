<?php
  require "vendor/autoload.php";
  require "Modelo/Conectar.php";

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  $app = new \Slim\App;

  $app->get('/', function () {

  });

  $app->get('/CONSULTAS/{c}/{n}', function (Request $request, Response $response) {
	  $n = $request->getAttribute('n');
	  $c = $request->getAttribute('c');
	  $db = Conectar::conexion();
	  if($c == 'SIZE'){
		  if($n == 'SMALL'){
			  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE Rooms >= 10 and Rooms <= 50 ");
		  }else{
			  if($n == 'MEDIUM'){
				  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE Rooms >= 51 and Rooms <= 100 ");
			  }else{
				  if($n == 'LARGE'){
					  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE Rooms > 100 ");
				  }
			  }
		  }
	  }else{
		  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE $c = '$n'");
	  }
	  $consulta->execute();
	  $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
      echo (json_encode($resultado));
  });

  $app->post('/ticket/new', function (Request $request, Response $response) {

    	  echo("dnjwndjen");
  });


  $app->post('/usuario/add', function (Request $request, Response $response) {
      $name = $request->getParam('name');
      $last_name = $request->getParam('last_name');
      $address = $request->getParam('address');
      $email = $request->getParam('email');
      $password = $request->getParam('password');


      $sql = "INSERT INTO usuarios (name, last_name, address, email, password) VALUES
      (:name, :last_name, :address, :email, :password)";
  	  $db = Conectar::conexion();

      $stmt = $db->prepare($sql);

      $stmt-> bindParam(':name', $name);
      $stmt-> bindParam(':last_name', $last_name);
      $stmt-> bindParam(':address', $address);
      $stmt-> bindParam(':email', $email);
      $stmt-> bindParam(':password', $password);

  	  $stmt->execute();

      echo '{"response" : "User created"}';
      return $response;
    });


  /*$resultado = $consulta->fetchAll(PDO::FETCH_OBJ);*/


  $app->run();

?>
