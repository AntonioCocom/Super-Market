<?php
require_once '../../../../controllers/ProductController.php';

// Obtener datos de la solicitud (por ejemplo, desde JSON en el cuerpo de la solicitud)
$data = json_decode(file_get_contents('php://input'), true);
// Verificar que se recibieron los datos necesarios
if (
    !isset($data['name']) || $data['name'] ==='' ||
    !isset($data['price']) || $data['price'] ==='' ||
    !isset($data['description']) || $data['description'] ==='' ||
    !isset($data['stock']) || $data['stock'] ==='' ||
    !isset($data['img']) || $data['img'] ==='' ||
    !isset($data['type']) || $data['type'] ===''
) {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
    exit;
}

$productController = new ProductController();
$result = $productController->createProduct($data);

echo json_encode($result);
exit;
?>
