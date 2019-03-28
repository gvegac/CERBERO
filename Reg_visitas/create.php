<?php
// Inc   mysql_set_charset("utf8");lude config file
require_once "config.php";
//funcion validadora de rut
function validaRut($rut){
if(strlen($rut)<=10){ 
    if(strpos($rut,"-")==false){
        $RUT[0] = substr($rut, 0, -1);
        $RUT[1] = substr($rut, -1);
    }else{
        $RUT = explode("-", trim($rut));
    }
	$suma = 0;
    $elRut = str_replace(".", "", trim($RUT[0]));
    $factor = 2;
    for($i = strlen($elRut)-1; $i >= 0; $i--):
        $factor = $factor > 7 ? 2 : $factor;
        $suma += $elRut{$i}*$factor++;
    endfor;
    $resto = $suma % 11;
    $dv = 11 - $resto;
    if($dv == 11){
        $dv=0;
    }else if($dv == 10){
        $dv="k";
    }else{
        $dv=$dv;
    }
   if($dv == trim(strtolower($RUT[1]))){
       return true;
   }else{
       return false;
   }
}
else{
	return false;
}
}
// Define variables and initialize with empty values
$VIS_RUT = $VIS_NOM = $VIS_APE1 = $VIS_APE2 = $VIS_FEC_HOR = $VIS_VIV = "";
$VIS_RUT_err = $VIS_NOM_err = $VIS_APE1_err = $VIS_APE2_err = $VIS_FEC_HOR_err = $VIS_VIV_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$VIS_FEC_HOR = date('Y-m-d H:i:s');
    // Validar rut de la visita
	$input_VIS_RUT = trim($_POST["VIS_RUT"]);
	if(validaRut($input_VIS_RUT)==true){
		$VIS_RUT = $input_VIS_RUT;
	}		
    else if(empty($input_VIS_RUT)){
        $VIS_RUT_err = "Porfavor ingrese el rut de la visita.";
    }
	else{
		$VIS_RUT_err = "Porfavor ingresar un rut válido.";
    }
	// Validar el nombre
	$input_VIS_NOM = trim($_POST["VIS_NOM"]);
    if(empty($input_VIS_NOM)){
        $VIS_NOM_err = "Porfavor ingrese el nombre del residente.";
    } elseif(!filter_var($input_VIS_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $VIS_NOM_err = "Porfavor ingresar un nombre válido.";
    } else{
        $VIS_NOM = $input_VIS_NOM;
    }
	// Validar el apellido
    $input_VIS_APE1 = trim($_POST["VIS_APE1"]);
    if(empty($input_VIS_APE1)){
        $VIS_APE1_err = "Porfavor ingrese el apellido paterno de la visita.";
    } elseif(!filter_var($input_VIS_APE1, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $VIS_APE1_err = "Porfavor ingresar un apellido válido.";
    } else{
        $VIS_APE1 = $input_VIS_APE1;
    }
	
	// Validate VIS_APE2
    $input_VIS_APE2 = trim($_POST["VIS_APE2"]);
    if(empty($input_VIS_APE2)){
        $VIS_APE2_err = "Porfavor ingrese el apellido materno de la visita.";
    } elseif(!filter_var($input_VIS_APE2, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $VIS_APE2_err = "Porfavor ingresar un apellido válido.";
    } else{
        $VIS_APE2 = $input_VIS_APE2;
    }
	//VALIDATE VIS_VIV
	$input_VIS_VIV = trim($_POST["VIS_VIV"]);
    if(empty($input_VIS_VIV)){
        $VIS_VIV_err = "Porfavor ingrese la vivienda";
    } elseif(!filter_var($input_VIS_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $VIS_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $VIS_VIV = $input_VIS_VIV;
    }
    
    // Check input errors before inserting in database
    if(empty($VIS_RUT_err) && empty($VIS_NOM_err) && empty($VIS_APE1_err) && empty($VIS_APE2_err) && empty($VIS_VIV_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO visita (VIS_RUT, VIS_NOM, VIS_APE1, VIS_APE2, VIS_FEC_HOR, VIS_VIV) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ssssss", $param_VIS_RUT, $param_VIS_NOM, $param_VIS_APE1, $param_VIS_APE2, $param_VIS_FEC_HOR, $param_VIS_VIV);
            
            // Set parameters
            $param_VIS_RUT = $VIS_RUT;
            $param_VIS_NOM = $VIS_NOM;
            $param_VIS_APE1 = $VIS_APE1;
			$param_VIS_APE2 = $VIS_APE2;
			$param_VIS_FEC_HOR = $VIS_FEC_HOR;
            $param_VIS_VIV = $VIS_VIV;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingresar visita</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Ingresar Visita</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar una visita a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VIS_RUT_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de la visita sin puntos y con guión ( Ej:18.315.554-7 > 18315554-7)</label>
                            <input type="text" name="VIS_RUT" class="form-control" value="<?php echo $VIS_RUT; ?>">
                            <span class="help-block"><?php echo $VIS_RUT_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIS_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre de la visita</label>
                            <input type="text" name="VIS_NOM" class="form-control" value="<?php echo $VIS_NOM; ?>">
                            <span class="help-block"><?php echo $VIS_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIS_APE1_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido paterno de la visita</label>
                            <input type="text" name="VIS_APE1" class="form-control" value="<?php echo $VIS_APE1; ?>">
                            <span class="help-block"><?php echo $VIS_APE1_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIS_APE2_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido materno de la visita</label>
                            <input type="text" name="VIS_APE2" class="form-control" value="<?php echo $VIS_APE2; ?>">
                            <span class="help-block"><?php echo $VIS_APE2_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIS_VIV_err)) ? 'has-error' : ''; ?>">
                            <label>Vivienda a la que visita</label>
                            <input type="text" name="VIS_VIV" class="form-control" value="<?php echo $VIS_VIV; ?>">
                            <span class="help-block"><?php echo $VIS_VIV_err;?></span>
                        </div>
						<input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="index.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>