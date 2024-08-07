<?php
require_once __DIR__ . '/../config/bd.php';

use MongoDB\BSON\ObjectId;

class Benefit {
    private $collection;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->collection = $db->Benfits;
    }

    public function create($benefitData) {
        $document = [
            'company' => $benefitData['company'],
            'benefit' => $benefitData['benefit'],
            'description' => $benefitData['description'],
            'validity' => $benefitData['validity'],
            'restrictions' => $benefitData['restrictions'],
            'img' => $benefitData['img'],
        ];

        try {
            $result = $this->collection->insertOne($document);
            
            // Verificar si la operación de inserción fue exitosa
            if ($result->getInsertedCount() === 1) {
                // Retorna éxito y el ID del documento insertado
                return [
                    'success' => true,
                    'message' => 'Benficio agregado exitosamente!'
                ];
            } else {
                // En caso de inserción fallida
                return ['success' => false, 'message' => 'No se pudo agregar el benficio'];
            }
        } catch (Exception $e) {
            // Manejo de errores
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

    public function update($id, $benefitData) {
        $existingBenefit = $this->collection->findOne(['_id' => new ObjectId($id)]);

        if (!$existingBenefit) {
            return [
                'success' => false,
                'message' => 'Beneficio no encontrado'
            ];
        }
        if (isset($benefitData['benefit']) && password_verify($benefitData['benefit'], $existingBenefit['benefit'])) {
            return [
                'success' => false,
                'message' => 'Ya existe un benficio con el mismo nombre'
            ];
        }
        $result = $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $benefitData]
        );
        return $result->getModifiedCount();
    }

    public function delete($id) {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount();
    }
}
?>