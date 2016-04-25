<?php
	require"clases/Alumnos.php";
 
	$legajo=$_POST['legajo'];
	$apellido=$_POST['apellido'];
	$nombre=$_POST['nombre'];
	$foto=$_FILES['archivo']['name'];
	$accion=$_POST['DatoAlumno'];

	$unAlumno = new Alumnos($legajo,$apellido,$nombre,$foto);
 	
	switch($accion){
		case "ingreso":{
			$unAlumno->Guardar();
		 	$unAlumno->CrearTablaIngresados();
			break;
		}
		case "egreso":{
			$unAlumno->Sacar($legajo);
		 	$unAlumno->CrearTablaEgresados();
		 	$unAlumno->CrearTablaIngresados();
			break;
		}	
		case "modifica":{
			$unAlumno->Modificar($legajo,$apellido,$nombre,$foto);
		 	$unAlumno->CrearTablaIngresados();
		 	break;
		}
	}

	header("location:index.php");
?>
