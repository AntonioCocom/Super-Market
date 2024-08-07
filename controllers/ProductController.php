<?php

require_once __DIR__ . '/../models/Products.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function createProduct($productData) {
        return $this->productModel->create($productData);
    }

    public function getProduct($id) {
        return $this->productModel->getOne($id);
    }
    
    public function getImage($imgId) {
        $productModel = new Product();
        $response = $productModel->getImage($imgId);

        if ($response['success']) {
            header('Content-Type: image/jpeg'); // Cambia esto según el tipo de imagen
            echo $response['image'];
        } else {
            header('HTTP/1.0 404 Not Found');
            echo 'Imagen no encontrada';
        }
    }


    public function updateProduct($id, $productData) {
        return $this->productModel->update($id, $productData);
    }

    public function deleteProduct($id) {
        return $this->productModel->delete($id);
    }

    public function getAllProducts() {
        $cursor = $this->productModel->getAll();
        $products = iterator_to_array($cursor);
        return $products;
    }
}
?>