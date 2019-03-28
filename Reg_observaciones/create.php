<?php
// Inc   mysql_set_charset("utf8");lude config file
require_once "config.php";
 
// Define variables and initialize with empty values
$OBS_FEC_HOR = $OBS_EMP = $OBS_VIV = $OBS_TIP = $OBS_DES = "";
$OBS_EMP_err = $OBS_VIV_err = $OBS_TIP_err = $OBS_DES_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$OBS_FEC_HOR = date('Y-m-d H:i:s');
    // Validate OBS_EMP
    $input_OBS_EMP = trim($_POST["OBS_EMP"]);
    if(empty($input_OBS_EMP)){
        $OBS_EMP_err = "Porfavor ingrese el rut del empleado.";
    } 
	else{
        $OBS_EMP = $input_OBS_EMP;
    }
		
    // Validate OBS_DES
    $input_OBS_DES = trim($_POST["OBS_DES"]);
    if(empty($input_OBS_DES)){
        $OBS_DES_err = "Ingrese la observación.";     
    }
    else{
        $OBS_DES = $input_OBS_DES;
    }
	// Validate OBS_TIP
    $OBS_TIP = trim($_POST["OBS_TIP"]);
	//VALIDATE OBS_VIV
	$input_OBS_VIV = trim($_POST["OBS_VIV"]);
    if(empty($input_OBS_VIV)){
        $OBS_VIV_err = "Porfavor ingrese la vivienda";
    } elseif(!filter_var($input_OBS_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $OBS_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $OBS_VIV = $input_OBS_VIV;
    }
    // Check input errors before inserting in database
    if(empty($OBS_EMP_err) && empty($OBS_VIV_err) && empty($OBS_TIP_err) && empty($OBS_DES_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO observacion (OBS_FEC_HOR, OBS_EMP, OBS_DES, OBS_TIP, OBS_VIV) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sssis",$param_OBS_FEC_HOR, $param_OBS_EMP, $param_OBS_DES, $param_OBS_TIP, $param_OBS_VIV);
            
            // Set parameters
            $param_OBS_FEC_HOR = $OBS_FEC_HOR;
            $param_OBS_EMP = $OBS_EMP;
			$param_OBS_DES = $OBS_DES;
			$param_OBS_TIP = $OBS_TIP;
            $param_OBS_VIV = $OBS_VIV;
            
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
    <title>Ingresar observación</title>
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
                        <h2>Ingresar Observación</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar una observación a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($OBS_EMP_err)) ? 'has-error' : ''; ?>">
                            <label>Conserje a cargo</label>
                            <input type="text" name="OBS_EMP" class="form-control" value="<?php echo $OBS_EMP; ?>">
                            <span class="help-block"><?php echo $OBS_EMP_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($OBS_VIV_err)) ? 'has-error' : ''; ?>">
                            <label>Vivienda involucrada</label>
                            <input type="text" name="OBS_VIV" class="form-control" value="<?php echo $OBS_VIV; ?>">
                            <span class="help-block"><?php echo $OBS_VIV_err;?></span>
                        </div>
                        <div class="form-group">
							<label for="OBS_TIP">Tipo de observación</label>
							<select class="form-control" name="OBS_TIP">
								<option value="0">Neutra</option>
								<option value="1">Mala</option>
							</select>
						</div>
						<div class="form-group <?php echo (!empty($OBS_DES_err)) ? 'has-error' : ''; ?>">
                            <label>¿Que ocurrió?</label>
                            <textarea name="OBS_DES" class="form-control"><?php echo $OBS_DES; ?></textarea>
                            <span class="help-block"><?php echo $OBS_DES_err;?></span>
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