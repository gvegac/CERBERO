<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$RES_ID = $RES_NOM = $RES_APE1 = $RES_APE2 = $RES_VIV = $RES_TEL = $RES_EMAIL = "";
$RES_ID_err = $RES_NOM_err = $RES_APE1_err = $RES_APE2_err = $RES_VIV_err = $RES_TEL_err = $RES_EMAIL_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["RES_ID"]) && !empty($_POST["RES_ID"])){
    // Get hidden input value
    $RES_ID = $_POST["RES_ID"];
    
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
        $RES_VIV_err = "Porfavor ingrese la vivienda del residente.";
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
	//elseif(!filter_var($input_RES_VIV, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([0-9]{3})(-)([0-9]{4})(-)([0-9]{4})$/")))){
    //    $RES_TEL_err = "Porfavor ingresar un teléfono válido.";
	//}
	else{
        $RES_TEL = $input_RES_TEL;
    }
    // Validate RES_EMAIL
    $input_RES_EMAIL = trim($_POST["RES_EMAIL"]);
    if(empty($input_RES_EMAIL)){
        $RES_EMAIL_err = "Ingrese un E-MAIL del residente.";     
    } elseif(!filter_var($input_RES_EMAIL, FILTER_VALIDATE_EMAIL)){
        $RES_EMAIL_err = "Ingrese una especie valida.";
    } else{
        $RES_EMAIL = $input_RES_EMAIL;
    }
	
    
    
   // Check input errors before inserting in database
    if(empty($RES_NOM_err) && empty($RES_APE1_err) && empty($RES_APE2_err) && empty($RES_VIV_err) && empty($RES_TEL_err) && empty($RES_EMAIL_err) && empty($RES_ID_err)){
        // Prepare an insert statement
        $sql = "UPDATE residente SET RES_NOM=?, RES_APE1=?, RES_APE2=?, RES_VIV=?, RES_TEL=?, RES_EMAIL=? WHERE RES_ID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_RES_NOM, $param_RES_APE1, $param_RES_APE2, $param_RES_VIV, $param_RES_TEL, $param_RES_EMAIL, $param_RES_ID);
            
            // Set parameters
            $param_RES_NOM = $RES_NOM;
            $param_RES_APE1 = $RES_APE1;
			$param_RES_APE2 = $RES_APE2;
			$param_RES_VIV = $RES_VIV;
			$param_RES_TEL = $RES_TEL;
			$param_RES_EMAIL = $RES_EMAIL;
            $param_RES_ID = $RES_ID;
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
    if(isset($_GET["RES_ID"]) && !empty(trim($_GET["RES_ID"]))){
        // Get URL parameter
        $RES_ID =  trim($_GET["RES_ID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM residente WHERE RES_ID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_RES_ID);
            
            // Set parameters
            $param_RES_ID = $RES_ID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $RES_ID = $row["RES_ID"];
					$RES_NOM = $row["RES_NOM"];
					$RES_APE1 = $row["RES_APE1"];
					$RES_APE2 = $row["RES_APE2"];
					$RES_VIV = $row["RES_VIV"];
					$RES_TEL = $row["RES_TEL"];
					$RES_EMAIL = $row["RES_EMAIL"];
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
                    <p>Porfavor llenar el formulario y aceptar para actualizar un residente en la base de datos.</p>
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