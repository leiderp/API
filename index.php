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
	  
	  ?>
<!doctype html>
<head>
	
</head>

<body>
	<link href="c.css" rel="stylesheet" type="text/css">
	<script src="j.json"></script>
	<div id="registration-form">
	<div class='fieldset'>
    <legend>Registro</legend>
		<form action="#" method="post" data-validate="parsley">
			<div class='row'>
				<label for="email">E-mail</label>
				<input type="text" placeholder="E-mail"  name='email' data-required="true" data-type="email" data-error-message="Your E-mail is required"  id="em">
			</div>
			<div class='row'>
				<label for='firstname'>Name</label>
				<input type="text" placeholder="First" name='firstname' id='firstname' data-required="true" data-error-message="Your First Name is required"  id="nm">
			</div>
			<div class='row'>
				<label for="cemail">Last name</label>
				<input type="text" placeholder="Last name" name='lastname' data-required="true" data-error-message="Your E-mail must correspond" id="lnm">
			</div>
			<div class='row'>
				<label for="cemail">Address</label>
				<input type="text" placeholder="Address" name='address' data-required="true" data-error-message="Your E-mail must correspond"  id="ad">
			</div>
			<div class='row'>
				<label for="cemail">Passaword</label>
				<input type="text" placeholder="Confirm your E-mail" name='cemail' data-required="true" data-error-message="Your E-mail must correspond" id="ps">
			</div>
			<input type="submit" value="Register" onClick="prueba();" >
		</form>
	</div>
</div>

</body>
      
</html>

<script>
	
	



</script>
<?php
	  
	
	  
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
	  
	  
	  
	  
	  
  });


  $app->run();

?>