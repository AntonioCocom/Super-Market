<?php
    require_once './auth.php';
    if (isTokenVerified()) {        
        switch ($_SESSION['role']) {
            case 'administrator':
                header('../views/admin/home.php');
                break;
            case 'customer':
                header('../views/customers/home.php');
                break;
        }
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de token</title>
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
        <h2 class="mt-3">Verificar token</h2>
        <div class="w-100">
            <form id="tokenForm">
                <label for="token">Token</label>
                <input type="text" id="token" name="token" required>
                <div class="d-flex justify-content-center">
                    <button class="col-8" type="submit" >Verificar token</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('tokenForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(document.getElementById('tokenForm'));
        
        fetch('./validateToken.php', {
        method: 'POST',
        body: formData
        })
        .then(response => {
            // Verifica si la respuesta es JSON
            const contentType = response.headers.get('Content-Type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Respuesta no JSON');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                switch (data.role) {
                    case 'administrator':
                        window.location.href = '../views/admin/home.php';
                        break;
                    case 'customer':
                        window.location.href = '../views/customers/home.php';
                        break;
                    default:
                    window.location.href = '../views/guest/home.php';
                    break;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
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