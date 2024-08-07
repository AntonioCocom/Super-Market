<?php
require_once '../../../../controllers/BenefitController.php';

$_id = $_GET['_id'] ?? '';

try {
    $benefitController = new Benefit();
    $result = $benefitController->delete($_id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Beneficio eliminado exitosamente']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el beneficio']);
    }
} catch (Exception $e) {
    // Manejo de excepciones
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
