<?php
require_once '../../../../controllers/ProductController.php';

// Obtener los datos de la solicitud (por ejemplo, desde JSON en el cuerpo de la solicitud)
$data = json_decode(file_get_contents('php://input'), true);
$_id = $_GET['_id'] ?? '';
// Verificar que se recibieron los datos necesarios
if (
    !isset($data['name']) || 
    !isset($data['price']) || 
    !isset($data['description']) || 
    !isset($data['stock']) || 
    !isset($data['img']) ||
    !isset($data['type'])
) {
    http_response_code(400); // Código de estado HTTP 400 Bad Request
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
    exit;
}

try {
    $productController = new ProductController();
    $result = $productController->updateProduct($_id, $data);

    // Verifica el resultado y devuelve la respuesta adecuada
    if ($result['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Producto actualizado exitosamente'
    ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => $result['message'] ?? 'Error al actualizar producto'
        ]);
    }
} catch (Exception $e) {
    // Manejo de excepciones
    http_response_code(500); // Código de estado HTTP 500 Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
