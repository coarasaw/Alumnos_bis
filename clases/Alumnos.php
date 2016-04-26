<?php
session_start();
$_SESSION['mensaje']='';
require"archivo.php";

class Alumnos
{
	public $legajo;
	public $apellido;
    public $nombre;
    public $foto;

    function __construct($vLegajo,$vApellido,$vNomnbre,$vFoto)
    {
    	$this->legajo = $vLegajo;
    	$this->apellido = $vApellido;
    	$this->nombre = $vNomnbre;
    	$this->foto = $vFoto;
    } 	

	public function Guardar()
	{
		if($_FILES['archivo']['size'] > 1480576 || $_FILES['archivo']['size'] == 0){
			$tamaño = $this->foto['archivo']['size'];
			$_SESSION['mensaje'] = "La foto supera el tamaño permitido ($tamaño/1024)";
			return;
		}
		if((strtoupper(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)) != 'JPG') && (strtoupper(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)) != ' ')){
			$_SESSION['mensaje'] = "Formato no valido ($extension) ";
			return;
		}
		$extension = $this->apellido."_".$this->nombre."_".$this->legajo.".".pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
	    move_uploaded_file($_FILES['archivo']['tmp_name'] , "Fotitos/$extension");
		$archivo=fopen("archivos/ingresados.txt", "a");
		//$ahora=date("Y-m-d H:i:s"); 
		
		$renglon=$this->legajo . "=>" . $this->apellido . "=>" . $this->nombre . "=>" . $extension . "\n";
		fwrite($archivo, $renglon); 		 
		fclose($archivo);

		$_SESSION['mensaje'] = "Alumno guardado";
	}

	
	public function CrearTablaIngresados()
	{
		if(file_exists("archivos/ingresados.txt"))
		{
			$cadena=" <table><th>Legajo</th><th>Apellido</th><th>Nombre</th><th>Foto</th>";
			$archivo=fopen("archivos/ingresados.txt", "r");

			while(!feof($archivo)){
				$archAux=fgets($archivo);
				$auto=explode("=>", $archAux);
				$auto[0]=trim($auto[0]);
				if($auto[0]!="")
					$cadena =$cadena."<tr> <td> ".$auto[0]."</td> <td> ".$auto[1] ."</td> <td> ".$auto[2] ."</td><td><img src=Fotitos/".$auto[3]." height=100px with=100px/></td></tr>" ; 
			}
				$cadena =$cadena." </table>";
				fclose($archivo);

				$archivo=fopen("archivos/tablaIngresados.php", "w");
				fwrite($archivo, $cadena);
		}
		else{
			$cadena= "No hay alumno ingresado";

			$archivo=fopen("archivos/tablaIngresado.php", "w");
			fwrite($archivo, $cadena);
		}

	}

	public static function Sacar($leg)
	{
		$listaAlumnosArchivo = Alumnos::Leer();
		$listaAlumnosGuardados = array();
		$esta = false;
		$legajoSacar;
		$ApeSacar;
		$NomSacar;
		$fotoSacar;		
		
		foreach ($listaAlumnosArchivo as $alumnito) {
			if(trim($alumnito[0]) != trim($leg)){
				$listaAlumnosGuardados[] = $alumnito;
			}
			else
			{
				$esta = true;
				//$ahora = date("Y-m-d H:i:s");
				$legajoSacar = $alumnito[0];
				$ApeSacar = $alumnito[1];
				$NomSacar = $alumnito[2];
				$fotoSacar = $alumnito[3];				
			}
		}		
		if($esta)
		{
			Alumnos::GuardarAlumno($legajoSacar,$ApeSacar,$NomSacar,$fotoSacar);
			$_SESSION['mensaje'] = "Alumno Eliminado";
		}
		Alumnos::GuardarListado($listaAlumnosGuardados);
	}

	public static function Modificar($leg,$Ap,$Nom,$Fot)
	{
		$listaAlumnosArchivo = Alumnos::Leer();
		$listaAlumnosGuardados = array();
		$esta = false;
		$lModi=$leg;
		$aModi=$Ap;
		$nModi=$Nom;
		$fModi=$Fot;	

		foreach ($listaAlumnosArchivo as $alumnito) {
			if(trim($alumnito[0]) != trim($leg)){
				$listaAlumnosGuardados[] = $alumnito;
			}
			else
			{								
				if($_FILES['archivo']['size'] > 1480576 || $_FILES['archivo']['size'] == 0){
					$tamaño = $_FILES['archivo']['size'];
					$_SESSION['mensaje'] = "La foto supera el tamaño permitido ($tamaño/1024)";
					return;
				}
				if((strtoupper(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)) != 'JPG') && (strtoupper(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)) != ' ')){
					$_SESSION['mensaje'] = "Formato no valido ($extension) ";
					return;
				}
				$extension = $aModi."_".$nModi."_".$lModi.".".pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
			    move_uploaded_file($_FILES['archivo']['tmp_name'] , "Fotitos/$extension");		
			    $esta = true;
				$alumnito[0]=$lModi;
				$alumnito[1]=$aModi;
				$alumnito[2]=$nModi;
				$alumnito[3]=$extension. "\n";
				$listaAlumnosGuardados[] = $alumnito;	
			}
		}		
		if($esta)
		{			
			Alumnos::GuardarListado($listaAlumnosGuardados);
			$_SESSION['mensaje'] = "Alumno Modificado";
		}	
	}

	public static function Leer()
	{
		$ListaDeAlumnosLeida = array();
		$archivo=fopen("archivos/ingresados.txt", "r");

		while(!feof($archivo))
		{
			$renglon=fgets($archivo);
			$auto=explode("=>", $renglon);
			$auto[0]=trim($auto[0]);
			if($auto[0]!="")
			$ListaDeAlumnosLeida[]=$auto;
		}

		fclose($archivo);
		return $ListaDeAlumnosLeida;
	}

	public static function GuardarAlumno($leg,$ape,$nom,$foto)
	{
		$archivo=fopen("archivos/egresados.txt", "a");
		$renglon=$leg."=>".$ape."=>".$nom."=>".$foto."\n";
		fwrite($archivo, $renglon); 		 
		fclose($archivo);
	}

	public static function CrearTablaEgresados()
	{
		if(file_exists("archivos/egresados.txt"))
		{
			$cadena=" <table><th>Legajo</th><th>Apellido</th><th>Nombre</th><th>Foto</th>";

			$archivo=fopen("archivos/egresados.txt", "r");

			while(!feof($archivo))
			{
				$archAux=fgets($archivo);				
				$auto=explode("=>", $archAux);				
				$auto[0]=trim($auto[0]);
				if($auto[0]!="")
					$cadena =$cadena."<tr> <td> ".$auto[0]."</td> <td> ".$auto[1] ."</td> <td> ".$auto[2] ."</td><td><img src=Fotitos/".$auto[3]." height=100px with=100px/></td></tr>" ; 
				}

				$cadena =$cadena." </table>";
				fclose($archivo);

				$archivo=fopen("archivos/TablaEgresados.php", "w");
				fwrite($archivo, $cadena);
			}
		else{
			$cadena= "no hay Egresados";

			$archivo=fopen("archivos/TablaEgresados.php", "w");
			fwrite($archivo, $cadena);
		}
	}

	public static function GuardarListado($listado)
	{
		$archivo=fopen("archivos/ingresados.txt", "w");
		foreach ($listado as $alumno) {
			fwrite($archivo, $alumno[0] . "=>" . $alumno[1] . "=>" . $alumno[2] . "=>" . $alumno[3]);
			//echo $archivo, $alumno[0] . "=>" . $alumno[1] . "=>" . $alumno[2] . "=>" . $alumno[3];	
			//echo "<br>";		 
		}	
		//die();		
		fclose($archivo);
	}

}
/*

public static function CrearJSAutocompletar()
	{		
		$cadena="";

		$archivo=fopen("archivos/ingrsados.txt", "r");

		while(!feof($archivo))
		{
			$archAux=fgets($archivo);
			$auto=explode("=>", $archAux);
			$auto[0]=trim($auto[0]);

			if($auto[0]!="")
			{
				$auto[1]=trim($auto[1]);
				$cadena=$cadena." {value: \"".$auto[0]."\" , data: \" ".$auto[1]." \" }, \n"; 
			}
		}
		fclose($archivo);

		$archivoJS="$(function(){
		var patentes = [ \n\r
		". $cadena."

		];

		// setup autocomplete function pulling from patentes[] array
		$('#autocomplete').autocomplete({
		lookup: patentes,
		onSelect: function (suggestion) {
		var thehtml = '<strong>patente: </strong> ' + suggestion.value + ' <br> <strong>ingreso: </strong> ' + suggestion.data;
		$('#outputcontent').html(thehtml);
		$('#botonIngreso').css('display','none');
			console.log('aca llego');
		}
		});


		});";

		$archivo=fopen("js/funcionAutoCompletar.js", "w");
		fwrite($archivo, $archivoJS);
	}
*/
?>