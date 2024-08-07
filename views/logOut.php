<?php
require_once '../controllers/UserController.php';
require_once '../auth/auth.php';


$userController = new UserController();
$customer = $userController->logOut();
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>
