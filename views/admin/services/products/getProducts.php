<?php
require_once '../../../../controllers/ProductController.php';

$productController = new ProductController();

$products = $productController->getAllProducts();


foreach ($products as &$product) {
    if ($product['_id'] instanceof MongoDB\BSON\ObjectId) {
        $productId = (string) $product['_id'];
    } elseif (isset($product['_id']['$oid'])) {
        $productId = $product['_id']['$oid'];
    } else {
        $productId = (string) $product['_id'];
    }
    
    $product['_id'] = $productId;
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'products' => $products]);
?>