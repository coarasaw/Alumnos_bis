<?php
require"clases/Alumnos.php";
$path = "gestion.php";

?>
<!doctype html>
<html lang="en-US">
<head>

  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <title> Archivos </title>

  <link rel="stylesheet" type="text/css" href="css/estilo.css">
  <link rel="stylesheet" type="text/css" href="css/animacion.css">
  <link rel="stylesheet" type="text/css" media="all" href="css/style.css">
  
  <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
  <script type="text/javascript" src="js/funcionAutoCompletar.js"></script>
  <!--script type="text/javascript" src="js/currency-autocomplete.js"></script-->
</head>
	<body>
    <div class="CajaUno animated bounceInDown">

      <form action="<?php echo $path; ?>" method="post" enctype="multipart/form-data">
        <input type="number" name="legajo" placeholder="Legajo" id="autocomplete" required />
        <input type="text" name="apellido" placeholder="Apellido" id="autocomplete" required/>
        <input type="text" name="nombre" placeholder="Nombre" id="autocomplete" required />
        <br>
        <input type="file" name="archivo" class="MiBotonUTN" />
        <input type="submit" id="botonIngreso" class="MiBotonUTN" value="ingreso" name="DatoAlumno" />
        <input type="submit" id="botonEgreso" class="MiBotonUTN" value="egreso" name="DatoAlumno" />
        <input type="submit" id="botonModificacion" class="MiBotonUTN" value="modifica" name="DatoAlumno" />
      </form>
      <div id="outputbox">
        <p id="outputcontent">
          <?php 
              if(isset($_SESSION['mensaje'])){
                echo $_SESSION['mensaje'];
              }
          ?>
        </p>
      </div>
    </div>
    
    <div class="CajaEnunciado animated bounceInLeft">
        <h2>Alumnos Ingresados</h2>
        <?php include("archivos/tablaIngresados.php"); ?>
    </div>

    <div class="CajaEnunciadoDerecha animated bounceInRight">
        <h2>Alumnos Egresados</h2>
        <?php include("archivos/tablaEgresados.php"); ?>
    </div>
	</body>
</html>