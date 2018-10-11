<?php
if(!empty($_POST)){
	if(isset($_POST["username"]) &&isset($_POST["fullname"]) &&isset($_POST["email"]) &&isset($_POST["address"]) &&isset($_POST["password"]) &&isset($_POST["confirm_password"])){
		echo("aki");
		if($_POST["username"]!=""&& $_POST["fullname"]!=""&&$_POST["email"]!=""&&$_POST["password"]!=""&&$_POST["password"]==$_POST["confirm_password"]){
			include "../Modelo/Conectar.php";
			$con = Conectar::conexion();
			$e = rand( 100 ,  999 );
			$found=false;
			$sql1= "select * from user where username=\"$_POST[username]\" or email=\"$_POST[email]\"";
			$query = $con->prepare($sql1);
			$query->execute();
			while ($r=$query->fetchAll()) {
				$found=true;
				break;
			}
			if($found){
				print "<script>alert(\"Nombre de usuario o email ya estan registrados.\");window.location='../registro.php';</script>";
			}
			$sql = "insert into usuarios(userid,username,fullname,address,email,password,created_at) value (\"$e\",\"$_POST[username]\",\"$_POST[fullname]\",\"$_POST[address]\",\"$_POST[fullname]\",\"$_POST[email]\",\"$_POST[password]\")";
			$query = $con->prepare($sql);
			$query->execute();
			if($query!=null){
				print "<script>alert(\"Registro exitoso. Proceda a logearse\");window.location='../login.php';</script>";
			}
		}
	}
}
	


?>