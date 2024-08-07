<?php
require_once '../../../../controllers/BenefitController.php';

$data = json_decode(file_get_contents('php://input'), true);
$_id = $_GET['_id'] ?? '';
if (
    !isset($data['benefit']) || 
    !isset($data['company']) || 
    !isset($data['description']) || 
    !isset($data['validity']) || 
    !isset($data['img']) ||
    !isset($data['restriction'])
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
    exit;
}

try {
    $benefitController = new BenefitController();
    $result = $benefitController->updateBenefit($_id, $data);

    if ($result['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Beneficio actualizado exitosamente'
    ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => $result['message'] ?? 'Error al actualizar el beneficio'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
