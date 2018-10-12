<?php
  require "vendor/autoload.php";
  require "Modelo/Conectar.php";

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  $app = new \Slim\App;

  $app->get('/', function () {

  });

  $app->get('/consultas/{atributo}/{nombre}', function (Request $request, Response $response) {
	  $n = $request->getAttribute('nombre');
	  $c = $request->getAttribute('atributo');
	  $n = str_replace("_"," ","$n");
	  echo($n);
	  $db = Conectar::conexion();
	  if($c == 'size'){
		  if($n == 'small'){
			  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE Rooms >= 10 and Rooms <= 50 ");
		  }else{
			  if($n == 'medium'){
				  $consulta = $db->prepare("SELECT * FROM `informacion` WHERE Rooms >= 51 and Rooms <= 100 ");
			  }else{
				  if($n == 'large'){
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

  $app->post('/usuario/add', function (Request $request, Response $response) {
      $name = $request->getParam('name');
      $last_name = $request->getParam('last_name');
      $address = $request->getParam('address');
      $email = $request->getParam('email');
      $password = $request->getParam('password');

  	  $db = Conectar::conexion();

      /*Id actual*/
      $sql = "SELECT `AUTO_INCREMENT` AS 'ID' FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'hoteles_db' AND TABLE_NAME = 'usuarios'";
      $stmt = $db->prepare($sql);
      $stmt->execute();

      $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
      /*Creacion del usuario*/
      $sql = "INSERT INTO usuarios (name, last_name, address, email, password) VALUES
      (:name, :last_name, :address, :email, :password)";
      $stmt = $db->prepare($sql);

      $stmt-> bindParam(':name', $name);
      $stmt-> bindParam(':last_name', $last_name);
      $stmt-> bindParam(':address', $address);
      $stmt-> bindParam(':email', $email);
      $stmt-> bindParam(':password', $password);

  	  $stmt->execute();

      return json_encode($resultado);

    });

$app->put('/usuario/update/{id}', function (Request $request, Response $response) {
	  $id = $request->getAttribute('id');
	  $name = $request->getParam('name');
      $last_name = $request->getParam('last_name');
      $address = $request->getParam('address');
      $email = $request->getParam('email');
      $password = $request->getParam('password');

  	  $db = Conectar::conexion();
	$sql = "UPDATE usuarios SET
				name = :name,
				last_name = :last_name,
				address = :address,
				email = :email,
				password = :password
			WHERE id = $id";
	try{
		
		$stmt = $db->prepare($sql);
		$stmt-> bindParam(':name', $name);
		$stmt-> bindParam(':last_name', $last_name);
		$stmt-> bindParam(':address', $address);
	    $stmt-> bindParam(':email', $email);
		$stmt-> bindParam(':password', $password);
  	    $stmt->execute();
		echo("1");
		
	}catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
  });

  $app->run();

?>
