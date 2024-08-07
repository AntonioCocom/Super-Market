<?php
require_once '../../../controllers/UserController.php';
require_once '../../../controllers/DigitalCardController.php';

$_id = $_GET['_id'] ?? '';

if (!$_id) {
    echo json_encode(['success' => false, 'message' => 'ID del cliente no proporcionado']);
    exit;
}

$userController = new UserController();
$cardController = new DigitalCardController();
$card = $cardController->getCard($_id);
$customer = $userController->getUser($_id);

if ($customer && $card) {
    echo json_encode(['success' => true, 'customer' => $customer, 'card' => $card]);
} else {
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
}
?>
