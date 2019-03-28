<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$PRO_RUT = $PRO_NOM = $PRO_APE1 = $PRO_APE2 = $PRO_TEL = $PRO_EMAIL =  "";
$PRO_RUT_err = $PRO_NOM_err = $PRO_APE1_err = $PRO_APE2_err = $PRO_TEL_err = $PRO_EMAIL_err =  "";
 
// Processing form data when form is submitted
if(isset($_POST["PRO_RUT"]) && !empty($_POST["PRO_RUT"])){
    // Validate PRO_RUT
    // Get hidden input value
    $PRO_RUT = $_POST["PRO_RUT"];
    
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
    } elseif(!filter_var($input_PRO_TEL, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9 0-9-0-9]+$/")))){
        $PRO_TEL_err = "Ingrese un Telefono valido.";
    } else{
        $PRO_TEL = $input_PRO_TEL;
    }

    // Validate PRO_EMAIL
    $input_PRO_EMAIL = trim($_POST["PRO_EMAIL"]);
     if(empty($input_PRO_EMAIL)){
        $PRO_EMAIL_err = "Ingrese el Email de propietario.";     
    } elseif(!filter_var($input_PRO_EMAIL, FILTER_VALIDATE_EMAIL)){
        $PRO_EMAIL_err = "Ingrese un Email valido.";
    } else{
        $PRO_EMAIL = $input_PRO_EMAIL;
    }
    // Check input errors before inserting in database
    if(empty($PRO_RUT_err) && empty($PRO_NOM_err) && empty($PRO_APE1_err) && empty($PRO_APE2_err) && empty($PRO_TEL_err) && empty($PRO_EMAIL_err) ){
        // Prepare an insert statement
        $sql = "UPDATE propietario SET PRO_NOM=?,PRO_APE1=?,PRO_APE2=?,PRO_TEL=?,PRO_EMAIL=? WHERE PRO_RUT=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssiss",$param_PRO_NOM, $param_PRO_APE1, $param_PRO_APE2,$param_PRO_TEL,$param_PRO_EMAIL,$param_PRO_RUT);
            
            // Set parameters
            $param_PRO_NOM = $PRO_NOM;
            $param_PRO_APE1 = $PRO_APE1;
            $param_PRO_APE2 = $PRO_APE2;
            $param_PRO_TEL = $PRO_TEL;
            $param_PRO_EMAIL = $PRO_EMAIL;
            $param_PRO_RUT=$PRO_RUT;
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
}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["PRO_RUT"]) && !empty(trim($_GET["PRO_RUT"]))){
        // Get URL parameter
        $PRO_RUT =  trim($_GET["PRO_RUT"]);
        
        // Prepare a select statement
        $sql = "SELECT PRO_RUT,PRO_NOM,PRO_APE1,PRO_APE2,PRO_TEL,PRO_EMAIL FROM propietario WHERE PRO_RUT = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_PRO_RUT);
            
            // Set parameters
            $param_PRO_RUT = $PRO_RUT;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $PRO_RUT = $row["PRO_RUT"];
					$PRO_NOM = $row["PRO_NOM"];
					$PRO_APE1 = $row["PRO_APE1"];
					$PRO_APE2 = $row["PRO_APE2"];
                    $PRO_TEL = $row["PRO_TEL"];
                    $PRO_EMAIL = $row["PRO_EMAIL"];
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
    <title>Actualizar Propietario</title>
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
                        <h2>Actualizar Propietario</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar un propietario en la base de datos.</p>
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
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="MNT_PRO.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>