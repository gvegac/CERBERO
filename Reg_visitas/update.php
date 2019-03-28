<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$VIS_COD = $VIS_RUT = $VIS_NOM = $VIS_APE1 = $VIS_APE2 = $VIS_FEC_HOR = $VIS_VIV = "";
$VIS_COD_err = $VIS_RUT_err = $VIS_NOM_err = $VIS_APE1_err = $VIS_APE2_err = $VIS_FEC_HOR_err = $VIS_VIV_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["VIS_COD"]) && !empty($_POST["VIS_COD"])){
    // Get hidden input value
    $VIS_COD = $_POST["VIS_COD"];
   	$VIS_FEC_HOR = $_POST["VIS_FEC_HOR"];
	$VIS_RUT = $_POST["VIS_RUT"];
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
	    
   if(empty($VIS_NOM_err) && empty($VIS_APE1_err) && empty($VIS_APE2_err) && empty($VIS_VIV_err)){
        // Prepare an insert statement
        $sql = "UPDATE visita SET VIS_NOM=?, VIS_APE1=?, VIS_APE2=?, VIS_VIV=? WHERE VIS_COD=? AND VIS_FEC_HOR=? AND VIS_RUT=?";
         
		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ssssiss", $param_VIS_NOM, $param_VIS_APE1, $param_VIS_APE2, $param_VIS_VIV, $param_VIS_COD, $param_VIS_FEC_HOR, $param_VIS_RUT);
            
            // Set parameters
            $param_VIS_NOM = $VIS_NOM;
            $param_VIS_APE1 = $VIS_APE1;
			$param_VIS_APE2 = $VIS_APE2;
            $param_VIS_VIV = $VIS_VIV;
			$param_VIS_COD = $VIS_COD;
			$param_VIS_FEC_HOR = $VIS_FEC_HOR;
			$param_VIS_RUT = $VIS_RUT;
            
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
    if(isset($_GET["VIS_COD"]) && !empty(trim($_GET["VIS_COD"]))){
        // Get URL parameter
        $VIS_COD =  trim($_GET["VIS_COD"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM visita WHERE VIS_COD = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_VIS_COD);
            
            // Set parameters
            $param_VIS_COD = $VIS_COD;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $VIS_COD = $row["VIS_COD"];
					$VIS_FEC_HOR = $row["VIS_FEC_HOR"];
					$VIS_RUT = $row["VIS_RUT"];
					$VIS_NOM = $row["VIS_NOM"];
					$VIS_APE1 = $row["VIS_APE1"];
					$VIS_APE2 = $row["VIS_APE2"];
					$VIS_VIV = $row["VIS_VIV"];
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
    <title>Actualizar Visita</title>
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
                        <h2>Actualizar Visita</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar una visita en la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VIS_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Código de visita</label>
                            <input type="text" name="VIS_COD" class="form-control" value="<?php echo $VIS_COD; ?>" readonly="readonly"/>
                            <span class="help-block"><?php echo $VIS_COD_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIS_FEC_HOR_err)) ? 'has-error' : ''; ?>">
                            <label>Fecha y hora del suceso)</label>
                            <input type="text" name="VIS_FEC_HOR" class="form-control" value="<?php echo $VIS_FEC_HOR; ?>" readonly="readonly"/>
                            <span class="help-block"><?php echo $VIS_FEC_HOR_err;?></span>
                        </div>
                         <div class="form-group <?php echo (!empty($VIS_RUT_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de visita</label>
                            <input type="text" name="VIS_RUT" class="form-control" value="<?php echo $VIS_RUT; ?>" readonly="readonly"/>
                            <span class="help-block"><?php echo $VIS_RUT_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIS_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="VIS_NOM" class="form-control" value="<?php echo $VIS_NOM; ?>">
                            <span class="help-block"><?php echo $VIS_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIS_AP1_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido paterno</label>
                            <input type="text" name="VIS_APE1" class="form-control" value="<?php echo $VIS_APE1; ?>">
                            <span class="help-block"><?php echo $VIS_APE1_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIS_APE2_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido materno</label>
                            <input type="text" name="VIS_APE2" class="form-control" value="<?php echo $VIS_APE2; ?>">
                            <span class="help-block"><?php echo $VIS_APE2_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIS_VIV_err)) ? 'has-error' : ''; ?>">
                            <label>Vivienda a visitar</label>
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