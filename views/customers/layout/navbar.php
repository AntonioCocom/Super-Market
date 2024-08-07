<?php
    $userController = new UserController();
    $actualUser = $userController->getUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/layout/navbar.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img class="navbar-logo" src="<?php echo BASE_URL; ?>assets/img/logo2.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="navbar-nav me-auto mb-2 ms-5 gap-5 mb-lg-0 d-flex align-items-center" style="min-height: 70px">
                    <a href="<?php echo BASE_URL; ?>views/customers/home.php" class="nav-item nav-link navbar-section col-4 text-center" style="<?php if($_SESSION['location']==='home'){?>border-bottom: 2px solid #FFF <?php } ?>">Principal</a>
                    <a href="<?php echo BASE_URL; ?>views/customers/awards.php" class="nav-item nav-link navbar-section col-4 text-center" style="<?php if($_SESSION['location']==='awards'){?>border-bottom: 2px solid #FFF <?php } ?>">Premios</a>
                    <a href="<?php echo BASE_URL; ?>views/customers/benefits.php" class="nav-item nav-link navbar-section col-4 text-center" style="<?php if($_SESSION['location']==='benefits'){?>border-bottom: 2px solid #FFF <?php } ?>">Beneficios</a>
                </div>
                <div class="d-flex flex-column flex-lg-row align-items-center gap-5">
                    <div class="d-flex align-items-center gap-2 user-info">
                        <img class="user-nav-icon" src="https://img.icons8.com/?size=100&id=fJ7hcfUGpKG7&format=png&color=FFFFFF" alt="User Icon">
                        <p class="mb-0 text-white"><?php if($_SESSION['role']==='customer') {echo $actualUser['first_name'];} ?></p>
                    </div>
                    <button class="logout-btn" onclick="logOut()">Cerrar sesión</button>
                </div>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        function logOut() {
            const logOutUrl = '<?php echo BASE_URL; ?>views/logOut.php';
            const index = '<?php echo BASE_URL; ?>index.php';
             Swal.fire({
                title: `¿Deseas cerrar sesión?`,
                icon: "warning",
                text: "Te redirigirá al login",
                customClass: {
                    container: 'add-modal',
                    popup: 'add-modal-popup',
                    confirmButton: 'delete-button px-4',
                    cancelButton: 'cancel-button px-4'
                },
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar',
                preConfirm: () => {
                    fetch(logOutUrl, {
                        method: 'POST', // o 'GET' según tu necesidad
                        headers: {
                            'Content-Type': 'application/json',
                            // Si necesitas enviar datos adicionales en el cuerpo de la solicitud, agrégalo aquí
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: 'Sesión cerrada exitosamente. Redirigiendo...',
                                icon: 'success',
                                timer: 2000, // Tiempo en milisegundos para que el modal se cierre automáticamente
                                showConfirmButton: false // Opcional: Ocultar el botón de confirmación
                            }).then(() => {
                                // Redirigir a la página de inicio de sesión después de que el modal se cierre
                                window.location.href = index; // Ajusta la URL según la ubicación de tu página de inicio de sesión
                            });
                        } else {
                            // Maneja el caso en que el cierre de sesión no fue exitoso
                            console.error('Error al cerrar sesión');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
        })
    }
    </script>
</body>
</html>