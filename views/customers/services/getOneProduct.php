<?php
require_once '../../../controllers/ProductController.php';

$imgId = $_GET['imgId'] ?? '';

if (!$imgId) {
    echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
    exit;
}

$productController = new ProductController();
$product = $productController->getProduct($imgId);
if ($product['_id'] instanceof MongoDB\BSON\ObjectId) {
        $productId = (string) $product['_id'];
    } elseif (isset($product['_id']['$oid'])) {
        $productId = $product['_id']['$oid'];
    } else {
        $productId = (string) $product['_id'];
    }
    
    $product['_id'] = $productId;


if ($product) {
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
}
?>