<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$VIV_COM = $VIV_EST = $VIV_BOD =  "";
$VIV_COD_err = $VIV_COM_err = $VIV_EST_err = $VIV_BOD_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate VIV_COD
    $VIV_COD = trim($_POST["VIV_COD"]);
    
    // Validate VIV_COM
    $input_VIV_COM = trim($_POST["VIV_COM"]);
    if(empty($input_VIV_COM)){
        $VIV_COM_err = "Porfavor ingrese el cod del Complejo Habitacional de la Vivienda.";
    }else{
        $VIV_COM = $input_VIV_COM;
    }
    
    // Validate VIV_EST
    $input_VIV_EST = trim($_POST["VIV_EST"]);
    if(empty($input_VIV_EST)){
        $VIV_EST_err = "Ingrese el Codigo de estacionamiento de la Vivienda.";     
    } elseif(!ctype_digit($input_VIV_EST)){
        $VIV_EST_err = "Ingrese un Codigo de estacionamiento de la Vivienda.";
    } else{
        $VIV_EST = $input_VIV_EST;
    }
	// Validate VIV_BOD
    $input_VIV_BOD = trim($_POST["VIV_BOD"]);
     if(empty($input_VIV_BOD)){
        $VIV_BOD_err = "Ingrese el Codigo de Bodega de la Vivienda.";     
    } elseif(!ctype_digit($input_VIV_BOD)){
        $VIV_BOD_err = "Ingrese un Codigo Valido.";
    } else{
        $VIV_BOD = $input_VIV_BOD;
    }
    
    // Check input errors before inserting in database
    if(empty($VIV_COM_err) && empty($VIV_EST_err) && empty($VIV_BOD_err)){
        // Prepare an insert statement
        $sql = "UPDATE vivienda SET VIV_COM=?,VIV_EST=?,VIV_BOD=? WHERE VIV_COD=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siis", $param_VIV_COM, $param_VIV_EST, $param_VIV_BOD, $param_VIV_COD);
            
            // Set parameters
            $param_VIV_COM = $VIV_COM;
            $param_VIV_EST = $VIV_EST;
            $param_VIV_BOD = $VIV_BOD;
            $param_VIV_COD = $VIV_COD;
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
    if(isset($_GET["VIV_COD"]) && !empty(trim($_GET["VIV_COD"]))){
        // Get URL parameter
        $VIV_COD =  trim($_GET["VIV_COD"]);
        
        // Prepare a select statement
        $sql = "SELECT VIV_COD,VIV_COM,VIV_EST,VIV_BOD FROM vivienda WHERE VIV_COD = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_VIV_COD);
            
            // Set parameters
            $param_VIV_COD = $VIV_COD;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $VIV_COD = $row["VIV_COD"];
					$VIV_COM = $row["VIV_COM"];
					$VIV_EST = $row["VIV_EST"];
					$VIV_BOD = $row["VIV_BOD"];
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
    <title>Actualizar Complejo Habitacional</title>
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
                        <h2>Actualizar Vivienda</h2>
                    </div>
                    <p>Porfavor llenar el formulario y aceptar para actualizar una vivienda en la base de datos.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($VIV_COD_err)) ? 'has-error' : ''; ?>">
                            <label>Código de vivienda</label>
                            <input type="text" name="VIV_COD" class="form-control" value="<?php echo $VIV_COD; ?>">
                            <span class="help-block"><?php echo $VIV_COD_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIV_COM_err)) ? 'has-error' : ''; ?>">
                            <label>Codigo de Complejo Habitacional de la vivienda</label>
                            <input type="text" name="VIV_COM" class="form-control" value="<?php echo $VIV_COM; ?>">
                            <span class="help-block"><?php echo $VIV_COM_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($VIV_EST_err)) ? 'has-error' : ''; ?>">
                            <label>Estacionamiento</label>
                            <input type="text" name="VIV_EST" class="form-control" value="<?php echo $VIV_EST; ?>">
                            <span class="help-block"><?php echo $VIV_EST_err;?></span>
                        </div>
						<div class="form-group <?php echo (!empty($VIV_BOD_err)) ? 'has-error' : ''; ?>">
                            <label>Bodega</label>
                            <input type="text" name="VIV_BOD" class="form-control" value="<?php echo $VIV_BOD; ?>">
                            <span class="help-block"><?php echo $VIV_BOD_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Actualizar">
                        <a href="MNT_VIV.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>