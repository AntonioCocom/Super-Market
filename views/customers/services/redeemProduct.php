<?php
require_once '../../../controllers/DigitalCardController.php';
require_once '../../../controllers/ProductController.php';

$data = json_decode(file_get_contents('php://input'), true);
$_id = $data['_id'] ?? '';
$userId = $_GET['user_id']??'';
function calculatePoints($amount) {
    $pointsPer100 = 5;

    $points = ($amount / 100) * $pointsPer100;

    return $points;
}
try {
    $cardController = new DigitalCardController();
    $productController = new ProductController();
    $total = $data['quantity'] * $data['price'];
    $product = $productController->getProduct($_id);
    if ($product['stock'] < $data['quantity']) {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Stock insuficiente'
        ]);
        exit();
    }
    $card = $cardController->getCard($userId);
    if($card['points'] < $total) {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Puntos insuficientes'
        ]);
        exit();
    }
    $cardData = [
        "points" => $card['points'] - $total
    ];
    $productData = [
        'stock' => $product['stock'] - $data['quantity']
    ];
    $cardResult = $cardController->updateCard($card['_id'], $cardData);
    $productResult = $productController->updateProduct($_id, $productData);

    // Verifica el resultado y devuelve la respuesta adecuada
    if ($cardResult['success'] && $productResult['success']) {
    echo json_encode([
        'success' => true, 
        'message' => 'Compra realizada exitosamente'
    ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => $result['message'] ?? 'Error al procesar la compra'
        ]);
    }
} catch (Exception $e) {
    // Manejo de excepciones
    http_response_code(500); // Código de estado HTTP 500 Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
