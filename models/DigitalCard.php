<?php
require_once __DIR__ . '/../config/bd.php';

use MongoDB\BSON\ObjectId;

class DigitalCard{
    private $collection;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->collection = $db->DigitalCard;
    }

    public function create($digitalCardData) {
        $document = [
            'customer' => $digitalCardData['customer'],
            'points' => $digitalCardData['points'],
            'expiration' => $digitalCardData['expiration'],
            'card_number' => $digitalCardData['card_number']
        ];

        try {
            $this->collection->insertOne($document);            
        } catch (Exception $e) {
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
        return $this->collection->findOne(['customer' => new ObjectId($id)]);
    }

    public function update($id, $digitalCardData) {
        $existingCard= $this->collection->findOne(['_id' => new ObjectId($id)]);

        if (!$existingCard) {
            return [
                'success' => false,
                'message' => 'Tarjeta no encontrada'
            ];
        }
        $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $digitalCardData]
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