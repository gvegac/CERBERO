<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$EMP_ID = $EMP_NOM = $EMP_APE1 = $EMP_APE2 = $EMP_TEL = $EMP_PASS =  "";
$EMP_ID_err = $EMP_NOM_err = $EMP_APE1_err = $EMP_APE2_err =  $EMP_TEL_err = $EMP_PASS_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["EMP_ID"]) && !empty($_POST["EMP_ID"])){
    // Validate EMP_ID
    
    $EMP_ID = trim($_POST["EMP_ID"]);   
     // Validate EMP_NOM
    $input_EMP_NOM = trim($_POST["EMP_NOM"]);
    if(empty($input_EMP_NOM)){
        $EMP_NOM_err = "Porfavor ingrese el nombre del Empleado.";
    } elseif(!filter_var($input_EMP_NOM, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $EMP_NOM_err = "Porfavor ingresar un nombre valido.";
    } else{
        $EMP_NOM = $input_EMP_NOM;
    }
    
    // Validate EMP_APE1
    $input_EMP_APE1 = trim($_POST["EMP_APE1"]);
    if(empty($input_EMP_APE1)){
        $EMP_APE1_err = "Ingrese el Apellido Paterno de Empleado.";     
    } elseif(!filter_var($input_EMP_APE1, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $EMP_APE1_err = "Ingrese un Apellido Paterno Valido.";
    } else{
        $EMP_APE1 = $input_EMP_APE1;
    }
	// Validate EMP_APE2
    $input_EMP_APE2 = trim($_POST["EMP_APE2"]);
     if(empty($input_EMP_APE2)){
        $EMP_APE2_err = "Ingrese el Apellido Materno de Empleado.";     
    } elseif(!filter_var($input_EMP_APE2, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $EMP_APE2_err = "Ingrese un Apellido Materno Valido.";
    } else{
        $EMP_APE2 = $input_EMP_APE2;
    }
    // Validate EMP_TEL
    $input_EMP_TEL = trim($_POST["EMP_TEL"]);
     if(empty($input_EMP_TEL)){
        $EMP_TEL_err = "Ingrese un Telefono.";     
    }else{
        $EMP_TEL = $input_EMP_TEL;
    }
	
	//Validate Emp_tip
	$EMP_TIP = trim($_POST["EMP_TIP"]);
	
    // Validate EMP_PASS
    $input_EMP_PASS = trim($_POST["EMP_PASS"]);
     if(empty($input_EMP_PASS)){
        $EMP_PASS_err = "Ingrese una contraseña.";     
    } elseif(!filter_var($input_EMP_PASS, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $EMP_PASS_err = "Ingrese una contraseña Valida.";
    } else{
        $EMP_PASS = $input_EMP_PASS;
    }
	
    // Check input errors before inserting in database
    if(empty($EMP_ID_err) && empty($EMP_NOM_err) && empty($EMP_APE1_err) && empty($EMP_APE2_err) && empty($EMP_TEL_err) && empty($EMP_PASS_err) ){
        // Prepare an insert statement
        $sql = "UPDATE empleado SET EMP_NOM=?,EMP_APE1=?,EMP_APE2=?,EMP_TEL=?,EMP_PASS=?, EMP_TIP=? WHERE EMP_ID=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssisss", $param_EMP_NOM, $param_EMP_APE1, $param_EMP_APE2, $param_EMP_TEL, $param_EMP_PASS, $param_EMP_TIP, $param_EMP_ID);
            
            // Set parameters
            $param_EMP_NOM = $EMP_NOM;
            $param_EMP_APE1 = $EMP_APE1;
            $param_EMP_APE2 = $EMP_APE2;
            $param_EMP_TEL = $EMP_TEL;
            $param_EMP_PASS = $EMP_PASS;
			$param_EMP_TIP = $EMP_TIP;
            $param_EMP_ID = $EMP_ID;
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

else{
    // Check existence of id parameter before processing further
    if(isset($_GET["EMP_ID"]) && !empty(trim($_GET["EMP_ID"]))){
        // Get URL parameter
        $EMP_ID =  trim($_GET["EMP_ID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM empleado WHERE EMP_ID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_EMP_ID);
            
            // Set parameters
            $param_EMP_ID = $EMP_ID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $EMP_ID = $row["EMP_ID"];
					$EMP_NOM = $row["EMP_NOM"];
					$EMP_APE1 = $row["EMP_APE1"];
					$EMP_APE2 = $row["EMP_APE2"];
					$EMP_TEL = $row["EMP_TEL"];
                    $EMP_PASS = $row["EMP_PASS"];
					$EMP_TIP = $row["EMP_TIP"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Error";
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
    <title>Actualizar Empleado</title>
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
                        <h2>Actualizar Empleado</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para agregar un Empleado a la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($EMP_ID_err)) ? 'has-error' : ''; ?>">
                            <label>Rut de Empleado</label>
                            <input type="text" name="EMP_ID" class="form-control" value="<?php echo $EMP_ID; ?>">
                            <span class="help-block"><?php echo $EMP_ID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($EMP_NOM_err)) ? 'has-error' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" name="EMP_NOM" class="form-control" value="<?php echo $EMP_NOM; ?>">
                            <span class="help-block"><?php echo $EMP_NOM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($EMP_APE1_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Paterno</label>
                            <input type="text" name="EMP_APE1" class="form-control" value="<?php echo $EMP_APE1; ?>">
                            <span class="help-block"><?php echo $EMP_APE1_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($EMP_APE2_err)) ? 'has-error' : ''; ?>">
                            <label>Apellido Materno</label>
                            <input type="text" name="EMP_APE2" class="form-control" value="<?php echo $EMP_APE2; ?>">
                            <span class="help-block"><?php echo $EMP_APE2_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($EMP_TEL_err)) ? 'has-error' : ''; ?>">
                            <label>Teléfono del Empleado (Sólo numeros sin +56)</label>
                            <input type="text" name="EMP_TEL" class="form-control" value="<?php echo $EMP_TEL; ?>">
                            <span class="help-block"><?php echo $EMP_TEL_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($EMP_PASS_err)) ? 'has-error' : ''; ?>">
                            <label>Modificar Contraseña al Empleado</label>
                            <input type="text" name="EMP_PASS" class="form-control" value="<?php echo $EMP_PASS; ?>">
                            <span class="help-block"><?php echo $EMP_PASS_err;?></span>
                        </div>
						<div class="form-group">
							<label for="EMP_TIP">Permisos de usuario:</label>
							<select class="form-control" name="EMP_TIP">
								<option value="C">Conserje</option>
								<option value="A">Administrador</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="MNT_EMP.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>