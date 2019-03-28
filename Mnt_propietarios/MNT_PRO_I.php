<?php
// Include config file
require_once "config.php";
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
}
// Define variables and initialize with empty values
$PRO_RUT = $PRO_NOM = $PRO_APE1 = $PRO_APE2 = $PRO_TEL = $PRO_EMAIL = $PRO_ESTADO =  "";
$PRO_RUT_err = $PRO_NOM_err = $PRO_APE1_err = $PRO_APE2_err = $PRO_TEL_err = $PRO_EMAIL_err = $PRO_ESTADO_err =  "";
 
// Processing form data when form is submitted
if(isset($_POST["PRO_RUT"]) && !empty($_POST["PRO_RUT"])){
    // Validate PRO_RUT
    // Get hidden input value
    $input_PRO_RUT = trim($_POST["PRO_RUT"]);
	if(validaRut($input_PRO_RUT)==true){
		$PRO_RUT = $input_PRO_RUT;
	}else if(empty($input_PRO_RUT)){
        $PRO_RUT_err = "Porfavor ingrese el rut del propietario.";
    }else{
		$PRO_RUT_err = "Porfavor ingresar un rut válido.";
    }
    // Validate PRO_NOM
    $input_PRO_NOM = trim($_POST["PRO_NOM"]);
    if(empty($input_PRO_NOM)){
        $PRO_NOM_err = "Porfavor ingrese el nombre del Propietario.";
    } elseif(!filter_var($input_PRO_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $PRO_NOM_err = "Porfavor ingresar un nombre valido.";
    } else{
        $PRO_NOM = $input_PRO_NOM;
    }
    
    // Validate PRO_APE1
    $input_PRO_APE1 = trim($_POST["PRO_APE1"]);
    if(empty($input_PRO_APE1)){
        $PRO_APE1_err = "Ingrese el Apellido Paterno.";     
    } elseif(!filter_var($input_PRO_APE1, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/")))){
        $PRO_APE1_err = "Ingrese un Apellido Paterno Valdo.";
    } else{
        $PRO_APE1 = $input_PRO_APE1;
    }
	// Validate PRO_APE2
	
    $input_PRO_APE2 = trim($_POST["PRO_APE2"]);
     if(empty($input_PRO_APE2)){
        $PRO_APE2_err = "Ingrese un Apellido Materno.";     
    } elseif(!filter_var($input_PRO_APE2, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/")))){
        $PRO_APE2_err = "Ingrese un Apellido Materno Valido.";
    } else{
        $PRO_APE2 = $input_PRO_APE2;
    }
    // Validate PRO_TEL
    $input_PRO_TEL = trim($_POST["PRO_TEL"]);
     if(empty($input_PRO_TEL)){
        $PRO_TEL_err = "Ingrese el Telefono de propietario.";     
    } else{
        $PRO_TEL = $input_PRO_TEL;
    }

    // Validate PRO_EMAIL
    $input_PRO_EMAIL = trim($_POST["PRO_EMAIL"]);
     if(empty($input_PRO_EMAIL)){
        $PRO_EMAIL_err = "Ingrese el Email de propietario.";     
    } elseif(!filter_var($input_PRO_EMAIL, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9.a-zA-Z0-9@a-zA-Z.a-zA-Z]+$/")))){
        $PRO_EMAIL_err = "Ingrese un Email valido.";
    } else{
        $PRO_EMAIL = $input_PRO_EMAIL;
    }
	//ESTADO
    $PRO_ESTADO = trim($_POST["PRO_ESTADO"]);
	
    // Check input errors before inserting in database
    if(empty($PRO_RUT_err) && empty($PRO_NOM_err) && empty($PRO_APE1_err) && empty($PRO_APE2_err) && empty($PRO_TEL_err) && empty($PRO_EMAIL_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO propietario (PRO_RUT,PRO_NOM,PRO_APE1,PRO_APE2,PRO_TEL,PRO_EMAIL,PRO_ESTADO) VALUES (?,?,?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssisi", $param_PRO_RUT, $param_PRO_NOM, $param_PRO_APE1, $param_PRO_APE2,$param_PRO_TEL,$param_PRO_EMAIL,$param_PRO_ESTADO);
            
            // Set parameters
            $param_PRO_RUT = $PRO_RUT;
            $param_PRO_NOM = $PRO_NOM;
            $param_PRO_APE1 = $PRO_APE1;
            $param_PRO_APE2 = $PRO_APE2;
            $param_PRO_TEL = $PRO_TEL;
            $param_PRO_EMAIL = $PRO_EMAIL;
            $param_PRO_ESTADO = $PRO_ESTADO;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: return.php");
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
    <title>Ingresar Propietario</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Ingresar un Propietario</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para ingresar un propietario en la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($PRO_RUT_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de Propietario</label>
                            <input type="text" name="PRO_RUT" class="form-control" value="<?php echo $PRO_RUT; ?>">
                            <span class="help-block"><?php echo $PRO_RUT_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($PRO_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre de Propietario</label>
                            <input type="text" name="PRO_NOM" class="form-control" value="<?php echo $PRO_NOM; ?>">
                            <span class="help-block"><?php echo $PRO_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($PRO_APE1_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Paterno de Propietario</label>
                            <input type="text" name="PRO_APE1" class="form-control" value="<?php echo $PRO_APE1; ?>">
                            <span class="help-block"><?php echo $PRO_APE1_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($PRO_APE2_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Materno de Propietario</label>
                            <input type="text" name="PRO_APE2" class="form-control" value="<?php echo $PRO_APE2; ?>">
                            <span class="help-block"><?php echo $PRO_APE2_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($PRO_TEL_err)) ? 'has-error' : ''; ?>">
                            <label>Telefono de Propietario</label>
                            <input type="text" name="PRO_TEL" class="form-control" value="<?php echo $PRO_TEL; ?>">
                            <span class="help-block"><?php echo $PRO_TEL_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($PRO_EMAIL_err)) ? 'has-error' : ''; ?>">
                            <label>Email de Propietario</label>
                            <input type="text" name="PRO_EMAIL" class="form-control" value="<?php echo $PRO_EMAIL; ?>">
                            <span class="help-block"><?php echo $PRO_EMAIL_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="PRO_ESTADO">Estado</label>
							<select class="form-control" name="PRO_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_PRO.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>