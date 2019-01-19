<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$MAS_COD = $MAS_NOM = $MAS_ESP = $MAS_RAZA = $MAS_RES = $MAS_VIV = "";
$MAS_COD_err = $MAS_NOM_err = $MAS_ESP_err = $MAS_RAZA_err = $MAS_RES_err = $MAS_VIV_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate MAS_COD
    $input_MAS_COD = trim($_POST["MAS_COD"]);
    if(empty($input_MAS_COD)){
        $MAS_COD_err = "Porfavor ingrese el codigo de su mascota.";
    } elseif(!filter_var($input_MAS_COD, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $MAS_COD_err = "Porfavor ingresar un codigo valido.";
    } else{
        $MAS_COD = $input_MAS_COD;
    }
    
    // Validate MAS_NOM
    $input_MAS_NOM = trim($_POST["MAS_NOM"]);
    if(empty($input_MAS_NOM)){
        $MAS_NOM_err = "Porfavor ingrese el nombre de su mascota.";
    } elseif(!filter_var($input_MAS_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $MAS_NOM_err = "Porfavor ingresar un nombre valido.";
    } else{
        $MAS_NOM = $input_MAS_NOM;
    }
    
    // Validate MAS_ESP
    $input_MAS_ESP = trim($_POST["MAS_ESP"]);
    if(empty($input_MAS_ESP)){
        $MAS_ESP_err = "Ingrese una especie.";     
    } elseif(!ctype_digit($input_MAS_ESP)){
        $MAS_ESP_err = "Ingrese una especie valida.";
    } else{
        $MAS_ESP = $input_MAS_ESP;
    }
	// Validate MAS_RAZA
    $input_MAS_RAZA = trim($_POST["MAS_RAZA"]);
     if(empty($input_MAS_RAZA)){
        $MAS_RAZA_err = "Ingrese una raza.";     
    } elseif(!ctype_digit($input_MAS_RAZA)){
        $MAS_RAZA_err = "Ingrese una raza valida.";
    } else{
        $MAS_RAZA = $input_MAS_RAZA;
    }
	// Validate MAS_RES
    $input_MAS_RES = trim($_POST["MAS_RES"]);
     if(empty($input_MAS_RES)){
        $MAS_RES_err = "Porfavor ingrese el rut responsable de su mascota.";
    } else{
        $MAS_RES = $input_MAS_RES;
    }
	// Validate MAS_VIV
	$input_MAS_VIV = trim($_POST["MAS_VIV"]);
    if(empty($input_MAS_COD)){
        $MAS_VIV_err = "Porfavor ingrese la vivienda de su mascota.";
    } elseif(!filter_var($input_MAS_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $MAS_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $MAS_VIV = $input_MAS_VIV;
    }
    
    // Check input errors before inserting in database
    if(empty($MAS_COD_err) && empty($MAS_NOM_err) && empty($MAS_ESP_err) && empty($MAS_RAZA_err) && empty($MAS_RES_err) && empty($MAS_VIV_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO mascota (MAS_COD, MAS_NOM, MAS_ESP, MAS_RAZA, MAS_RES, MAS_VIV) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiiss", $param_MAS_COD, $param_MAS_NOM, $param_MAS_ESP, $param_MAS_RAZA, $param_MAS_RES, $param_MAS_VIV);
            
            // Set parameters
            $param_MAS_COD = $MAS_COD;
            $param_MAS_NOM = $MAS_NOM;
            $param_MAS_ESP = $MAS_ESP;
			$param_MAS_RAZA = $MAS_RAZA;
			$param_MAS_RES = $MAS_RES;
			$param_MAS_VIV = $MAS_VIV;
            
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
    <title>Ingresar mascota</title>
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
                        <h2>Ingresar mascota</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar una mascota a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($MAS_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Código</label>
                            <input type="text" name="MAS_COD" class="form-control" value="<?php echo $MAS_COD; ?>">
                            <span class="help-block"><?php echo $MAS_COD_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($MAS_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="MAS_NOM" class="form-control" value="<?php echo $MAS_NOM; ?>">
                            <span class="help-block"><?php echo $MAS_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($MAS_ESP_err)) ? 'has-error' : ''; ?>">
                            <label>Especie</label>
                            <input type="text" name="MAS_ESP" class="form-control" value="<?php echo $MAS_ESP; ?>">
                            <span class="help-block"><?php echo $MAS_ESP_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($MAS_RAZA_err)) ? 'has-error' : ''; ?>">
                            <label>Raza</label>
                            <input type="text" name="MAS_RAZA" class="form-control" value="<?php echo $MAS_RAZA; ?>">
                            <span class="help-block"><?php echo $MAS_RAZA_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($MAS_RES_err)) ? 'has-error' : ''; ?>">
                            <label>Rut Responsable</label>
                            <input type="text" name="MAS_RES" class="form-control" value="<?php echo $MAS_RES; ?>">
                            <span class="help-block"><?php echo $MAS_RES_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($MAS_VIV_err)) ? 'has-error' : ''; ?>">
                            <label>Vivienda de la mascota</label>
                            <input type="text" name="MAS_VIV" class="form-control" value="<?php echo $MAS_VIV; ?>">
                            <span class="help-block"><?php echo $MAS_VIV_err;?></span>
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