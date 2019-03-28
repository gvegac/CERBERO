<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$OBS_COD = $OBS_FEC_HOR = $OBS_EMP = $OBS_VIV = $OBS_TIP = $OBS_DES = "";
$OBS_COD_err = $OBS_FEC_HOR_err = $OBS_EMP_err= $OBS_VIV_err = $OBS_TIP_err = $OBS_DES_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["OBS_COD"]) && !empty($_POST["OBS_COD"])){
    // Get hidden input value
    $OBS_COD = trim($_POST["OBS_COD"]);
   	$OBS_FEC_HOR = trim($_POST["OBS_FEC_HOR"]);
	$OBS_EMP = trim($_POST["OBS_EMP"]);
	
	//VALIDATE OBS_VIV
	$input_OBS_VIV = trim($_POST["OBS_VIV"]);
    if(empty($input_OBS_VIV)){
        $OBS_VIV_err = "Porfavor ingrese la vivienda";
    } elseif(!filter_var($input_OBS_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $OBS_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $OBS_VIV = $input_OBS_VIV;
    }
	// Validate OBS_TIP
    $OBS_TIP = trim($_POST["OBS_TIP"]);
    	
    // Validate OBS_DES
    $input_OBS_DES = trim($_POST["OBS_DES"]);
    if(empty($input_OBS_DES)){
        $OBS_DES_err = "Ingrese la observación.";     
    }
    else{
        $OBS_DES = $input_OBS_DES;
    }
	    
   if(empty($OBS_VIV_err) && empty($OBS_DES_err)){
        // Prepare an insert statement
        $sql = "UPDATE observacion SET OBS_DES=?, OBS_TIP=?, OBS_VIV=?  WHERE OBS_COD=? AND OBS_FEC_HOR=? AND OBS_EMP=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sisiss", $param_OBS_DES, $param_OBS_TIP, $param_OBS_VIV, $param_OBS_COD, $param_OBS_FEC_HOR, $param_OBS_EMP);
            
            // Set parameters
			$param_OBS_DES = $OBS_DES;
			$param_OBS_TIP = $OBS_TIP;
            $param_OBS_VIV = $OBS_VIV;
			$param_OBS_COD = $OBS_COD;
			$param_OBS_FEC_HOR = $OBS_FEC_HOR;
            $param_OBS_EMP = $OBS_EMP;
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
else{
    // Check existence of id parameter before processing further
    if(isset($_GET["OBS_COD"]) && !empty(trim($_GET["OBS_COD"]))){
        // Get URL parameter
        $OBS_COD =  trim($_GET["OBS_COD"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM observacion WHERE OBS_COD = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_OBS_COD);
            
            // Set parameters
            $param_OBS_COD = $OBS_COD;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $OBS_COD = $row["OBS_COD"];
					$OBS_FEC_HOR = $row["OBS_FEC_HOR"];
					$OBS_EMP = $row["OBS_EMP"];
					$OBS_VIV = $row["OBS_VIV"];
					$OBS_TIP = $row["OBS_TIP"];
					$OBS_DES = $row["OBS_DES"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Observacion</title>
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
                        <h2>Actualizar observacion</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar una observacion en la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($OBS_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Código de observacion</label>
                            <input type="text" name="OBS_COD" class="form-control" value="<?php echo $OBS_COD; ?>" readonly="readonly"/>
                            <span class="help-block"><?php echo $OBS_COD_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($OBS_FEC_HOR_err)) ? 'has-error' : ''; ?>">
                            <label>Fecha y hora del suceso</label>
                            <input type="text" name="OBS_FEC_HOR" class="form-control" value="<?php echo $OBS_FEC_HOR; ?>" readonly="readonly"/>
                            <span class="help-block"><?php echo $OBS_FEC_HOR_err;?></span>
                        </div>
                         <div class="form-group <?php echo (!empty($OBS_EMP_err)) ? 'has-error' : ''; ?>">
                            <label>Conserje a cargo</label>
                            <input type="text" name="OBS_EMP" class="form-control" value="<?php echo $OBS_EMP; ?>" readonly="readonly"/>
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
                            <label>Agregar información a la observacion (No eliminar la observación original)</label>
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