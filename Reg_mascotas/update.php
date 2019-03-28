<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$MAS_COD = $MAS_NOM = $MAS_ESP = $MAS_RAZA = $MAS_RES = $MAS_VIV = "";
$MAS_COD_err = $MAS_NOM_err = $MAS_ESP_err = $MAS_RAZA_err = $MAS_RES_err = $MAS_VIV_err = $MAS_DES_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["MAS_COD"]) && !empty($_POST["MAS_COD"])){
    // Get hidden input value
    $MAS_COD = $_POST["MAS_COD"];
    
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
    if(empty($input_MAS_VIV)){
        $MAS_VIV_err = "Porfavor ingrese la vivienda de su mascota.";
    } elseif(!filter_var($input_MAS_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9a-zA-Z\s]+$/")))){
        $MAS_VIV_err = "Porfavor ingresar una vivienda valida.";
    } else{
        $MAS_VIV = $input_MAS_VIV;
    }
	 // Validate MAS_DES
    $input_MAS_DES = trim($_POST["MAS_DES"]);
    if(empty($input_MAS_DES)){
        $MAS_DES_err = "Ingrese la descripción.";     
    }
    else{
        $MAS_DES = $input_MAS_DES;
    }
    
    
    // Check input errors before inserting in database
    if(empty($MAS_NOM_err) && empty($MAS_ESP_err) && empty($MAS_RAZA_err) && empty($MAS_RES_err) && empty($MAS_VIV_err) && empty($MAS_COD_err) && empty($MAS_DES_err)){
        // Prepare an update statement
        $sql = "UPDATE MASCOTA SET MAS_NOM=?, MAS_ESP=?, MAS_RAZA=?, MAS_RES=?, MAS_VIV=?, MAS_DES=? WHERE MAS_COD=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siisssi", $param_MAS_NOM, $param_MAS_ESP, $param_MAS_RAZA, $param_MAS_RES, $param_MAS_VIV, $param_MAS_DES, $param_MAS_COD);
            
            // Set parameters
            $param_MAS_NOM = $MAS_NOM;
            $param_MAS_ESP = $MAS_ESP;
			$param_MAS_RAZA = $MAS_RAZA;
			$param_MAS_RES = $MAS_RES;
			$param_MAS_VIV = $MAS_VIV;
			$param_MAS_DES = $MAS_DES;
            $param_MAS_COD = $MAS_COD;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Existe una incongruencia con los datos";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["MAS_COD"]) && !empty(trim($_GET["MAS_COD"]))){
        // Get URL parameter
        $MAS_COD =  trim($_GET["MAS_COD"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM mascota WHERE MAS_COD = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_MAS_COD);
            
            // Set parameters
            $param_MAS_COD = $MAS_COD;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $MAS_COD = $row["MAS_COD"];
					$MAS_NOM = $row["MAS_NOM"];
					$MAS_ESP = $row["MAS_ESP"];
					$MAS_RAZA = $row["MAS_RAZA"];
					$MAS_RES = $row["MAS_RES"];
					$MAS_VIV = $row["MAS_VIV"];
					$MAS_DES = $row["MAS_DES"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Existe una incongruencia con los datos.";
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
    <title>Actualizar registro</title>
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
                        <h2>Actualizar registro</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar una mascota en la base de datos.</p>
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
                        <div class="form-group">
							<label for="MAS_ESP">Especie</label>
							<select class="form-control" name="MAS_ESP">
								<option value="1">Perro</option>
								<option value="2">Gato</option>
								<option value="3">Ave</option>
								<option value="4">Otro</option>
							</select>
						</div>
						<div class="form-group">
							<label for="MAS_RAZA">Raza</label>
							<select class="form-control" name="MAS_RAZA">
								<option value="1">Pug</option>
								<option value="2">Golden</option>
								<option value="3">Labrador</option>
								<option value="4">Bulldog</option>
								<option value="5">Chiguagua</option>
								<option value="6">Pastor Alemán</option>
								<option value="7">Persa</option>
								<option value="8">Siamés</option>
								<option value="9">Siberiano</option>
								<option value="10">Canario</option>
								<option value="11">Otro</option>
							</select>
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
						<div class="form-group <?php echo (!empty($MAS_DES_err)) ? 'has-error' : ''; ?>">
                            <label>Descripción breve de la mascota</label>
                            <textarea name="MAS_DES" class="form-control"><?php echo $MAS_DES; ?></textarea>
                            <span class="help-block"><?php echo $MAS_DES_err;?></span>
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