<?php  
// Datos de conexión a la base de datos  
$servidor = "localhost";  
$usuario = "usuario";  
$password = "contraseña";   
$base_datos = "explora_tu_mundo";  

// Crear conexión  
$connection = mysqli_connect($servidor, $usuario, $password, $base_datos);  

// Comprobar conexión  
if (!$connection) {  
    die("Conexión fallida: " . mysqli_connect_error());  
}  

// Procesar el formulario al enviar  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Sanitizar y recibir datos del formulario  
    $usuario_input = htmlspecialchars(trim($_POST['usuario']));  
    $password_input = htmlspecialchars(trim($_POST['password']));  

    // Verificar usuario y contraseña  
    $stmt = $connection->prepare("SELECT password FROM usuario WHERE email = ?");  
    $stmt->bind_param("s", $usuario_input);  
    $stmt->execute();  
    $stmt->bind_result($password_hashed);  
    
    if ($stmt->fetch()) {  
        // Verificar la contraseña  
        if (password_verify($password_input, $password_hashed)) {  
            echo "<h2>Login Exitoso!</h2>";   
            header("Location: index.html");  
            exit();  
        } else {  
            echo "<h2>Contraseña incorrecta!</h2>";  
        }  
    } else {  
        echo "<h2>Usuario no encontrado!</h2>";  
    }  

    // Cerrar declaración  
    $stmt->close();  
}  

// Cerrar conexión  
$connection->close();  
