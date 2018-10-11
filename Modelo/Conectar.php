<?php
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
?>