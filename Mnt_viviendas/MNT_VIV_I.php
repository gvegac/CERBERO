<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$VIV_COD = $VIV_COM = $VIV_EST = $VIV_BOD = $VIV_ESTADO = "";
$VIV_COD_err = $VIV_COM_err = $VIV_EST_err = $VIV_BOD_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate VIV_COD
    $input_VIV_COD = trim($_POST["VIV_COD"]);
    if(empty($input_VIV_COD)){
        $VIV_COD_err = "Porfavor ingrese el Codigo de la Vivienda.";
    }else{
        $VIV_COD = $input_VIV_COD;
    }
    
    // Validate VIV_COM
    $input_VIV_COM = trim($_POST["VIV_COM"]);
    if(empty($input_VIV_COM)){
        $VIV_COM_err = "Porfavor ingrese el Codigo del Complejo Habitacional de la Vivienda.";
    } elseif(!filter_var($input_VIV_COM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9-0-9a-zA-Z\s]+$/")))){
        $VIV_COM_err = "Porfavor ingresar un Codigo Valido.";
    } else{
        $VIV_COM = $input_VIV_COM;
    }
    
    // Validate VIV_EST
    $input_VIV_EST = trim($_POST["VIV_EST"]);
    if(empty($input_VIV_EST)){
        $VIV_EST_err = "Ingrese el Codigo de estacionamiento de la Vivienda.";     
    } elseif(!ctype_digit($input_VIV_EST)){
        $VIV_EST_err = "Ingrese un Codigo Valido.";
    } else{
        $VIV_EST = $input_VIV_EST;
    }
	// Validate VIV_BOD
    $input_VIV_BOD = trim($_POST["VIV_BOD"]);
     if(empty($input_VIV_BOD)){
        $VIV_BOD_err = "Ingrese el Codigo de Bodega de la Vivienda.";     
    } elseif(!ctype_digit($input_VIV_BOD)){
        $VIV_BOD_err = "Ingrese un Codigo Valida.";
    } else{
        $VIV_BOD = $input_VIV_BOD;
    }
	// Validate VIV_ESTADO
    $VIV_ESTADO = trim($_POST["VIV_ESTADO"]);
    
    
    // Check input errors before inserting in database
    if(empty($VIV_COD_err) && empty($VIV_COM_err) && empty($VIV_EST_err) && empty($VIV_BOD_err) && empty($VIV_ESTADO_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO vivienda (VIV_COD, VIV_COM, VIV_EST, VIV_BOD, VIV_ESTADO) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiii", $param_VIV_COD, $param_VIV_COM, $param_VIV_EST, $param_VIV_BOD, $param_VIV_ESTADO);
            
            // Set parameters
            $param_VIV_COD = $VIV_COD;
            $param_VIV_COM = $VIV_COM;
            $param_VIV_EST = $VIV_EST;
			$param_VIV_BOD = $VIV_BOD;
			$param_VIV_ESTADO = $VIV_ESTADO;            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: return.php");
                exit();
            } else{
                echo "Incongruencia en los datos";
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
    <title>Ingresar Vivienda</title>
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
                        <h2>Ingrese Vivienda</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar un Vivienda a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VIV_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Codigo de Vivienda </label>
                            <input type="text" name="VIV_COD" class="form-control" value="<?php echo $VIV_COD; ?>">
                            <span class="help-block"><?php echo $VIV_COD_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIV_COM_err)) ? 'has-error' : ''; ?>">
                            <label>Codigo de Complejo Habitacional de la Vivienda</label>
                            <input type="text" name="VIV_COM" class="form-control" value="<?php echo $VIV_COM; ?>">
                            <span class="help-block"><?php echo $VIV_COM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIV_EST_err)) ? 'has-error' : ''; ?>">
                            <label>Codigo de estacionamiento de la Vivienda</label>
                            <input type="text" name="VIV_EST" class="form-control" value="<?php echo $VIV_EST; ?>">
                            <span class="help-block"><?php echo $VIV_EST_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIV_BOD_err)) ? 'has-error' : ''; ?>">
                            <label>Codigo de Bodega de la Vivienda</label>
                            <input type="text" name="VIV_BOD" class="form-control" value="<?php echo $VIV_BOD; ?>">
                            <span class="help-block"><?php echo $VIV_BOD_err;?></span>
                        </div>
						<div class="form-group">
							<label for="VIV_ESTADO">Estado</label>
							<select class="form-control" name="VIV_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_VIV.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>