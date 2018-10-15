<?php
  require "vendor/autoload.php";
  require "Modelo/Conectar.php";
  require "Utils/random.php";

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  $app = new \Slim\App;

  $app->get('/', function () {

  });

$app->get('/disponibilidad/{dates}', function (Request $request, Response $response) {
	$str = $request->getAttribute('dates');
	$dates = explode("_", $str);
	$db = Conectar::conexion();

	$sql = "SELECT ID_HOTEL FROM informacion WHERE ID_HOTEL IN (SELECT DISTINCT(id_hotel) FROM habitaciones WHERE id_habitacion NOT IN(SELECT ID_ROOM FROM reservas WHERE(START_DATE >= '$dates[0]' AND START_DATE <= '$dates[1]') OR (FINISH_DATE >= '$dates[0]' AND FINISH_DATE <= '$dates[1]'))) AND STATE = '$dates[2]'";

	$stmt = $db->prepare($sql);
	$stmt->execute();
	$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
	echo(json_encode($resultado));
  });

  $app->get('/consultas/{atributo}/{nombre}', function (Request $request, Response $response) {
	  $n = $request->getAttribute('nombre');
	  $c = $request->getAttribute('atributo');
	  $n = str_replace("_"," ","$n");
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

$app->get('/reservacion/{infores}', function (Request $request, Response $response) {

	$str = $request->getAttribute('infores');
	$infores = explode("_", $str);
  	$db = Conectar::conexion();

	$sql = "SELECT h.* FROM (SELECT DISTINCT(id_habitacion), id_hotel FROM habitaciones WHERE id_habitacion NOT IN (SELECT ID_ROOM FROM reservas WHERE(START_DATE >= '$infores[2]' AND START_DATE <= '$infores[3]') OR (FINISH_DATE >= '$infores[2]' AND FINISH_DATE <= '$infores[3]'))) h WHERE '$infores[0]' = h.id_hotel";

	$stmt = $db->prepare($sql);
	$stmt->execute();
	$resultado = $stmt->fetchAll();
	$items = $stmt->rowCount();

	if($infores[4] <= $items){
		$i = 0;
		while($i < $infores[4]){
			$temp = $resultado[$i]['id_habitacion'];
			$sql = "INSERT INTO reservas (ID_USUARIO, ID_ROOM, START_DATE, FINISH_DATE) VALUES
            ('$infores[1]', '$temp', '$infores[2]', '$infores[3]')";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$sql = "SELECT ID_RESERVA FROM reservas WHERE ID_ROOM = '$temp' AND START_DATE = '$infores[2]' AND FINISH_DATE = '$infores[3]'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$resultado2 = $stmt->fetchAll();
			echo('El ID DE LA RESERVA '.($i + 1).' es: '.$resultado2[0]['ID_RESERVA']. "<br/>");
			$i = $i + 1;
		}
	}else{
		echo("no hay sufuciente disponibilidad en ese hotel");
	}
  });

<<<<<<< HEAD

=======
>>>>>>> origin/master
$app->delete('/hotel/delete/{info}', function (Request $request, Response $response) {
	$str = $request->getAttribute('info');
	$key = $request->getParam('key');

	$db = Conectar::conexion();

  $sql = "SELECT id_key from api_keys where api_key ='$key'";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $items = $stmt->rowCount();
  if ($items == 0) {
    return "{'message':'No API key found in request'}";
  }else if($items == 1){
  /*###############################################*/
        $sql = "SELECT HOTEL_NAME FROM informacion WHERE ID_HOTEL = '$str'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $items = $stmt->rowCount();
        if ($items == 0) {
          return "{'message':'Hotel no existe'}";
        }else{
          	$sql = "DELETE FROM informacion WHERE ID_HOTEL = '$str'";
          	$stmt = $db->prepare($sql);
            $stmt->execute();

          	$sql = "SELECT h.id_hotel, r.ID_ROOM FROM reservas r, habitaciones h WHERE h.id_habitacion = r.ID_ROOM AND h.id_hotel = '$str'";
          	$stmt = $db->prepare($sql);
              $stmt->execute();
          	$resultado = $stmt->fetchAll();
          	$items = $stmt->rowCount();
          	$i = 0;
          	while($i < $items){
          		$temp = $resultado[$i]['ID_ROOM'];
          		$sql = "DELETE FROM reservas WHERE reservas.ID_ROOM = '$temp'";
          		$stmt = $db->prepare($sql);
                  $stmt->execute();
          		$i = $i + 1;
          	}

          	$sql = "DELETE FROM habitaciones WHERE id_hotel = '$str'";
          	$stmt = $db->prepare($sql);
            $stmt->execute();
            return "{'message':'Hotel borrado'}";
          }
      }
      return "{'message':'que pasa brother'}";
	 });

<<<<<<< HEAD

=======
>>>>>>> origin/master
  $app->post('/createkey', function (Request $request, Response $response) {
      $contact_name = $request->getParam('contact_name');
      $company = $request->getParam('company');
      $email = $request->getParam('email');
      $key = generateRandomString(26);

  	  $db = Conectar::conexion();

      /*Creacion de la key*/
      $sql = "INSERT INTO api_keys (contact_name, company, email, api_key) VALUES
      (:contact_name,:company,:email,:api_key)";
      $stmt = $db->prepare($sql);

      $stmt-> bindParam(':contact_name', $contact_name);
      $stmt-> bindParam(':company', $company);
      $stmt-> bindParam(':email', $email);
      $stmt-> bindParam(':api_key', $key);

  	  $stmt->execute();

      $response = $stmt->fetchAll(PDO::FETCH_OBJ);

      return json_encode($response);

    });
<<<<<<< HEAD
=======

>>>>>>> origin/master
  $app->run();

?>
