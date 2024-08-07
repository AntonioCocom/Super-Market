<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="login-container d-flex flex-column align-items-center">
        <div class="logo-container">
            <img src="../assets/img/logo.png" alt="" class="logo">
        </div>
        <h2 class="mt-3">Iniciar Sesión</h2>
        <div class="w-100">
            <form id="loginForm">
                <label for="telefono_movil">Número de Teléfono:</label>
                <input type="tel" id="mobile" name="mobile" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <div class="d-flex justify-content-center">
                    <button class="col-8" type="submit" >Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        fetch('./authenticate.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                    window.location.href = './token.php';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud'
            });
        });
    });
    </script>
</body>
</html>