<?php
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DigitalCard.php';

use MongoDB\BSON\ObjectId;

class User {
    private $collection;
    private $cardCollection;
    private $digitalCardModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->collection = $db->Users;
        $this->cardCollection = $db->DigitalCard;
        $this->digitalCardModel = new DigitalCard();
    }

    public function create($userData) {
        $existingUser = $this->collection->findOne([
        '$or' => [
            ['email' => $userData['email']],
            ['mobile' => $userData['mobile']]
        ]
        ]);

        if ($existingUser) {
            return [
                'success' => false,
                'message' => 'El email o el número de móvil ya están en uso.'
            ];
        }
        $document = [
            'mobile' => $userData['mobile'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'address' => $userData['address'],
            'email' => $userData['email'],
            'state' => $userData['state'],
            'town' => $userData['town'],
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'role' => $userData['role'] ?? 'customer'
        ];

        try {
            $result = $this->collection->insertOne($document);
            $clientId = $result->getInsertedId();
            $cardNumber = $this->generateCardNumber();
            $expirationDate = date('Y-m-d', strtotime('+1 year'));

            $digitalCardDocument = [
                'customer' => $clientId, // Asocia la tarjeta digital con el cliente
                'points' => $userData['points'],
                'expiration' => $expirationDate,
                'card_number' => $cardNumber
            ];

            $this->digitalCardModel->create($digitalCardDocument);
            
            // Verificar si la operación de inserción fue exitosa
            if ($result->getInsertedCount() === 1){
                // Retorna éxito y el ID del documento insertado
                return [
                    'success' => true,
                    'message' => 'Cliente agregado exitosamente!'
                ];
            } else {
                // En caso de inserción fallida
                return ['success' => false, 'message' => 'No se pudo agregar el cliente'];
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

    public function getByPhone($telefono_movil) {
        return $this->collection->findOne(['mobile' => $telefono_movil]);
    }

    public function getByRole($role) {
        return $this->collection->find(['role' => $role]);
    }

    public function isAdmin($id) {
        $user = $this->getOne($id);
        return $user && $user['role'] === 'administrator';
    }

    public function update($id, $userData) {
        $existingUser = $this->collection->findOne(['_id' => new ObjectId($id)]);

        if (!$existingUser) {
            return [
                'success' => false,
                'message' => 'Usuario no encontrado'
            ];
        }
        if (isset($userData['password']) && password_verify($userData['password'], $existingUser['password'])) {
            return [
                'success' => false,
                'message' => 'La contraseña debe ser diferente a la actual'
            ];
        }
        $document = [];
        if($userData['password']==='') {
            $document = [
                'mobile' => $userData['mobile'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'address' => $userData['address'],
                'email' => $userData['email'],
                'state' => $userData['state'],
                'town' => $userData['town']
            ];
        } else {
            $document = [
                'mobile' => $userData['mobile'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'address' => $userData['address'],
                'email' => $userData['email'],
                'state' => $userData['state'],
                'town' => $userData['town'],
                'password' => $userData['password']
            ];
        }
        $updateData = [];
        foreach ($document as $key => $value) {
            if ($key !== 'password') {
                $updateData[$key] = $value;
            } else {
                $updateData[$key] = password_hash($value, PASSWORD_DEFAULT);
            }
        }
        $card = $this->digitalCardModel->getOne($existingUser['_id']);
        if (!$card) {
            error_log("No se encontró la tarjeta digital para el usuario con ID: " . $existingUser['_id']);
        }
        $digitalCardDocument = [
           'points' => $userData['points']
        ];

        $cardResult = $this->cardCollection->updateOne(
            ['_id' => new ObjectId($card['_id'])],
            ['$set' => $digitalCardDocument]
        );

        $userResult = $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $updateData]
        );

        

        return [
            'success' => true,
            'message' => 'Actualización completada',
            'userUpdated' => $userResult->getModifiedCount() > 0,
            'cardUpdated' => $cardResult->getModifiedCount() > 0
        ];
    }

    public function delete($id) {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount();
    }

    private function generateCardNumber() {
        return sprintf('%016d', mt_rand(0, 9999999999999999));
    }
}
?>