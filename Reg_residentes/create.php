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
$RES_ID = $RES_NOM = $RES_APE1 = $RES_APE2 = $RES_VIV = $RES_TEL = $RES_EMAIL = "";
$RES_ID_err = $RES_NOM_err = $RES_APE1_err = $RES_APE2_err = $RES_VIV_err = $RES_TEL_err = $RES_EMAIL_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar rut del residente
	$input_RES_ID = trim($_POST["RES_ID"]);
	if(validaRut($input_RES_ID)==true){
		$RES_ID = $input_RES_ID;
	}		
    else if(empty($input_RES_ID)){
        $RES_ID_err = "Porfavor ingrese el rut de la visita.";
    }
	else{
		$RES_ID_err = "Porfavor ingresar un rut válido.";
    }
    
    // Validate RES_NOM
    $input_RES_NOM = trim($_POST["RES_NOM"]);
    if(empty($input_RES_NOM)){
        $RES_NOM_err = "Porfavor ingrese el nombre del residente.";
    } elseif(!filter_var($input_RES_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $RES_NOM_err = "Porfavor ingresar un nombre válido.";
    } else{
        $RES_NOM = $input_RES_NOM;
    }
    
	// Validate RES_APE1
    $input_RES_APE1 = trim($_POST["RES_APE1"]);
    if(empty($input_RES_APE1)){
        $RES_APE1_err = "Porfavor ingrese el APE1bre del residente.";
    } elseif(!filter_var($input_RES_APE1, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $RES_APE1_err = "Porfavor ingresar un APE1bre válido.";
    } else{
        $RES_APE1 = $input_RES_APE1;
    }
	
	// Validate RES_APE2
    $input_RES_APE2 = trim($_POST["RES_APE2"]);
    if(empty($input_RES_APE2)){
        $RES_APE2_err = "Porfavor ingrese el APE2bre del residente.";
    } elseif(!filter_var($input_RES_APE2, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $RES_APE2_err = "Porfavor ingresar un APE2bre válido.";
    } else{
        $RES_APE2 = $input_RES_APE2;
    }
	// Validate RES_VIV
	$input_RES_VIV = trim($_POST["RES_VIV"]);
    if(empty($input_RES_VIV)){
        $RES_VIV_err = "Porfavor ingrese la vivienda de su REScota.";
    } elseif(!filter_var($input_RES_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $RES_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $RES_VIV = $input_RES_VIV;
    }
	// Validate RES_TEL
    $input_RES_TEL = trim($_POST["RES_TEL"]);
     if(empty($input_RES_TEL)){
        $RES_TEL_err = "Porfavor ingrese el teléfono del residente.";
    }
	else{
        $RES_TEL = $input_RES_TEL;
    }
    // Validate RES_EMAIL
    $RES_EMAIL = trim($_POST["RES_EMAIL"]);
    
    
    // Check input errors before inserting in database
    if(empty($RES_ID_err) && empty($RES_NOM_err) && empty($RES_APE1_err) && empty($RES_APE2_err) && empty($RES_VIV_err) && empty($RES_TEL_err) && empty($RES_EMAIL_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO residente (RES_ID, RES_NOM, RES_APE1, RES_APE2, RES_VIV, RES_TEL, RES_EMAIL) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssis", $param_RES_ID, $param_RES_NOM, $param_RES_APE1, $param_RES_APE2, $param_RES_VIV, $param_RES_TEL, $param_RES_EMAIL);
            
            // Set parameters
            $param_RES_ID = $RES_ID;
            $param_RES_NOM = $RES_NOM;
            $param_RES_APE1 = $RES_APE1;
			$param_RES_APE2 = $RES_APE2;
			$param_RES_VIV = $RES_VIV;
			$param_RES_TEL = $RES_TEL;
			$param_RES_EMAIL = $RES_EMAIL;
            
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
    <title>Ingresar Rsidente</title>
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
                        <h2>Ingresar Residente</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar un Residente a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($RES_ID_err)) ? 'has-error' : ''; ?>">
                            <label>Rut</label>
                            <input type="text" name="RES_ID" class="form-control" value="<?php echo $RES_ID; ?>">
                            <span class="help-block"><?php echo $RES_ID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($RES_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="RES_NOM" class="form-control" value="<?php echo $RES_NOM; ?>">
                            <span class="help-block"><?php echo $RES_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($RES_APE1_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Paterno</label>
                            <input type="text" name="RES_APE1" class="form-control" value="<?php echo $RES_APE1; ?>">
                            <span class="help-block"><?php echo $RES_APE1_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($RES_APE2_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Materno</label>
                            <input type="text" name="RES_APE2" class="form-control" value="<?php echo $RES_APE2; ?>">
                            <span class="help-block"><?php echo $RES_APE2_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($RES_VIV_err)) ? 'has-error' : ''; ?>">
                            <label>Vivienda</label>
                            <input type="text" name="RES_VIV" class="form-control" value="<?php echo $RES_VIV; ?>">
                            <span class="help-block"><?php echo $RES_VIV_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($RES_TEL_err)) ? 'has-error' : ''; ?>">
                            <label>Teléfono</label>
                            <input type="text" name="RES_TEL" class="form-control" value="<?php echo $RES_TEL; ?>">
                            <span class="help-block"><?php echo $RES_TEL_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($RES_EMAIL_err)) ? 'has-error' : ''; ?>">
                            <label>E-MAIL</label>
                            <input type="text" name="RES_EMAIL" class="form-control" value="<?php echo $RES_EMAIL; ?>">
                            <span class="help-block"><?php echo $RES_EMAIL_err;?></span>
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