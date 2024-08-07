<?php
require_once '../../../../controllers/BenefitController.php';

$_id = $_GET['_id'] ?? '';

if (!$_id) {
    echo json_encode(['success' => false, 'message' => 'ID del beneficio no proporcionado']);
    exit;
}

$benefitController = new BenefitController();
$benefit = $benefitController->getBenefit($_id);

if ($benefit) {
    echo json_encode(['success' => true, 'benefit' => $benefit]);
} else {
    echo json_encode(['success' => false, 'message' => 'Beneficio no encontrado']);
}
?>