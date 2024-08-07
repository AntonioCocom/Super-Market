<?php
require_once __DIR__ . '/../config/bd.php';

use MongoDB\BSON\ObjectId;
use MongoDB\Client;

class Product {
    private $collection;
    private $bucket;

    public function __construct() {
        // Establece la conexión a MongoDB
        $client = new Client("mongodb://localhost:27017");
        $database = $client->selectDatabase('pyc_fidelizacion');
        
        $this->collection = $database->selectCollection('Products');
        $this->bucket = $database->selectGridFSBucket();
    }

    public function create($productData) {
        

        $document = [
            'name' => $productData['name'],
            'price' => $productData['price'],
            'description' => $productData['description'],
            'stock' => $productData['stock'],
            'img' => $productData['img'],
            'type' => $productData['type']
        ];

        try {
            $result = $this->collection->insertOne($document);

            if ($result->getInsertedCount() === 1) {
                return [
                    'success' => true,
                    'message' => 'Producto agregado exitosamente!'
                ];
            } else {
                return ['success' => false, 'message' => 'No se pudo agregar el producto'];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getImage($imgId) {
        try {
            // Convertir el ID a ObjectId
            $imgId = new \MongoDB\BSON\ObjectId($imgId);

            // Recuperar el archivo desde GridFS
            $downloadStream = $this->bucket->openDownloadStream($imgId);
            $imgContent = stream_get_contents($downloadStream);

            if ($imgContent === false) {
                return [
                    'success' => false,
                    'message' => 'No se pudo recuperar la imagen'
                ];
            }

            // Devuelve el contenido de la imagen
            return [
                'success' => true,
                'image' => $imgContent
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function getAll() {
        return $this->collection->find();
    }

    public function getOne($id) {
        return $this->collection->findOne(['_id' => new ObjectId($id)]);
    }

    public function update($id, $productData) {
        $existingProduct = $this->collection->findOne(['_id' => new ObjectId($id)]);

        if (!$existingProduct) {
            return [
                'success' => false,
                'message' => 'Producto no encontrado'
            ];
        }
        $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $productData]
        );
        return [
            'success' => true,
            'message' => 'Actualización completada'
        ];
    }

    public function delete($id) {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount();
    }
}
?>