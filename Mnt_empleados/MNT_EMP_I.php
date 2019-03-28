<?php
// Include config file
require_once "config.php";
 function validaRut($rut){
if(strlen($rut)<=10){ 
    if(strpos($rut,"-")==false){
        $RUT[0] = substr($rut, 0, -1);
        $RUT[1] = substr($rut, -1);
    }else{
        $RUT = explode("-", trim($rut));
    }
	$suma = 0;
    $elRut = str_replace(".", "", trim($RUT[0]));
    $factor = 2;
    for($i = strlen($elRut)-1; $i >= 0; $i--):
        $factor = $factor > 7 ? 2 : $factor;
        $suma += $elRut{$i}*$factor++;
    endfor;
    $resto = $suma % 11;
    $dv = 11 - $resto;
    if($dv == 11){
        $dv=0;
    }else if($dv == 10){
        $dv="k";
    }else{
        $dv=$dv;
    }
   if($dv == trim(strtolower($RUT[1]))){
       return true;
   }else{
       return false;
   }
 }
}
// Define variables and initialize with empty values
$EMP_ID = $EMP_NOM = $EMP_APE1 = $EMP_APE2 = $EMP_TEL = $EMP_PASS = $EMP_ESTADO = $EMP_TIP = "";
$EMP_ID_err = $EMP_NOM_err = $EMP_APE1_err = $EMP_APE2_err =  $EMP_TEL_err = $EMP_PASS_err = $EMP_ESTADO_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate EMP_ID
    $input_EMP_ID = trim($_POST["EMP_ID"]);
	if(validaRut($input_EMP_ID)==true){
		$EMP_ID = $input_EMP_ID;
	}		
    else if(empty($input_EMP_ID)){
        $EMP_ID_err = "Porfavor ingrese el rut de la visita.";
    }
	else{
		$EMP_ID_err = "Porfavor ingresar un rut válido.";
    }
    
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
        $EMP_TEL_err = "Ingrese una Telefono.";     
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
    // Validate EMP_ESTADO
    $EMP_ESTADO = trim($_POST["EMP_ESTADO"]);
	
    // Check input errors before inserting in database
    if(empty($EMP_ID_err) && empty($EMP_NOM_err) && empty($EMP_APE1_err) && empty($EMP_APE2_err) && empty($EMP_TEL_err) && empty($EMP_PASS_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO empleado (EMP_ID,EMP_NOM,EMP_APE1,EMP_APE2,EMP_TEL,EMP_PASS,EMP_TIP,EMP_ESTADO) VALUES (?,?,?,?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssissi",$param_EMP_ID,$param_EMP_NOM, $param_EMP_APE1, $param_EMP_APE2, $param_EMP_TEL, $param_EMP_PASS, $param_EMP_TIP, $param_EMP_ESTADO);
            
            // Set parameters
            $param_EMP_ID = $EMP_ID;
            $param_EMP_NOM = $EMP_NOM;
            $param_EMP_APE1 = $EMP_APE1;
            $param_EMP_APE2 = $EMP_APE2;
            $param_EMP_TEL = $EMP_TEL;
            $param_EMP_PASS = $EMP_PASS;
			$param_EMP_TIP = $EMP_TIP;
            $param_EMP_ESTADO = $EMP_ESTADO;
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Empleado</title>
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
                        <h2>Ingrese Empleado</h2>
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
                            <label>Asignar Contraseña al Empleado</label>
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
						<div class="form-group">
							<label for="EMP_ESTADO">Estado</label>
							<select class="form-control" name="EMP_ESTADO">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
                        <input type="submit" class="btn btn-primary" value="Ingresar">
                        <a href="MNT_EMP.php" class="btn btn-default">Regresar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>