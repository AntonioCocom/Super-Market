<?php
require_once __DIR__ . '/../controllers/ProductController.php';

if (isset($_GET['imgId'])) {
    $imgId = $_GET['imgId'];
    $productController = new ProductController();
    $response = $productController->getImage($imgId);

    if ($response['success']) {
        // Establecer el tipo de contenido según el tipo de archivo
        header('Content-Type: image/jpeg'); // Cambia esto según el tipo de imagen
        echo $response['image'];
    } else {
        header('HTTP/1.0 404 Not Found');
        echo 'Imagen no encontrada';
    }
} else {
    header('HTTP/1.0 400 Bad Request');
    echo 'ID de imagen no proporcionado';
}
?>