<?php
require_once '../controllers/UserController.php';

$userController = new UserController();

$telefono_movil = $_POST['mobile'] ?? '';
$contrasena = $_POST['password'] ?? '';

$result = $userController->authenticate($telefono_movil, $contrasena);

echo json_encode($result);
exit;
?>