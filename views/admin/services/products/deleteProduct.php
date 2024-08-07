<?php
require_once '../../../../controllers/ProductController.php';

$_id = $_GET['_id'] ?? '';

try {
    $productController = new Product();
    $result = $productController->delete($_id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
    } else {
        http_response_code(500); // Código de estado HTTP 500 Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
    }
} catch (Exception $e) {
    // Manejo de excepciones
    http_response_code(500); // Código de estado HTTP 500 Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
