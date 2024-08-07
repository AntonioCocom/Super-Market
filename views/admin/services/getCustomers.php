<?php
require_once '../../../controllers/UserController.php';
require_once '../../../controllers/DigitalCardController.php';

$userController = new UserController();
$cardController = new DigitalCardController();
$role = 'customer'; 

$customers = $userController->getByRole($role);

$cards = [];

foreach ($customers as &$customer) {
    if ($customer['_id'] instanceof MongoDB\BSON\ObjectId) {
        $customerId = (string) $customer['_id'];
    } elseif (isset($customer['_id']['$oid'])) {
        $customerId = $customer['_id']['$oid'];
    } else {
        $customerId = (string) $customer['_id'];
    }
    
    $customer['_id'] = $customerId;
    $cards[$customerId] = $cardController->getCard($customerId);
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'customers' => $customers, 'cards' => $cards]);
?>