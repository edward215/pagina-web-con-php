<?php  
// Datos de conexión  
$servidor = "localhost";   
$usuario = "usuario";  
$password = "password";   
$base_datos = "explora_tu_mundo";  

// Crear conexión  
$conn = mysqli_connect($servidor, $usuario, $password, $base_datos);  

// Comprobar conexión  
if (!$conn) {  
    die("Conexión fallida: " . mysqli_connect_error());  
}  

// Obtener datos del formulario de forma segura  
$username = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';    
$email = isset($_POST['email']) ? trim($_POST['email']) : '';  
$password = isset($_POST['password']) ? trim($_POST['password']) : '';  

if (!empty($username) && !empty($email) && !empty($password)) {  
    // Validar si el correo ya existe en la base de datos  
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");  
    $stmt->bind_param("s", $email);  
    $stmt->execute();  
    $result = $stmt->get_result();  

    if ($result->num_rows > 0) {  
echo "El correo ya está en uso. <a href='SignUp.html'>Intenta nuevamente</a>";  
    } 
    else {  
        // Hashear la contraseña antes de guardarla  
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);  
    
        // Insertar nuevo usuario  
        $stmt = $conn->prepare("INSERT INTO login_y_registro (usuaio, email, password) VALUES (?, ?, ?)");  
        $stmt->bind_param("ssss", $username, $email, $password_hashed);  

        if ($stmt->execute()) {   
            header("Location: index.html");  
            exit();   
        } else {  
            echo "Error:" . $stmt->error;  
        }  
    }  

    // Cerrar declaración y conexión  
    $stmt->close();  
} else {  
    echo "Todos los campos son obligatorios. <a href='SignUp.html'>Intenta nuevamente</a>.";  
}  
$conn->close();  

