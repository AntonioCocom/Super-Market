<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/generateToken.php';
require_once __DIR__ . '/../utils/sendEmail.php';
require_once  __DIR__ . '/../auth/auth.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function createUser($userData) {
        if (isset($userData['role']) && $userData['role'] === 'administrator') {
            if (!$this->isCurrentUserAdmin()) {
                throw new Exception("No tienes permisos para crear un administrador");
            }
        }
        return $this->userModel->create($userData);
    }

    public function getUser($id) {
        return $this->userModel->getOne($id);
    }

    public function getByRole($role) {
       $cursor =  $this->userModel->getByRole($role);
        $users = iterator_to_array($cursor);
        return $users;
    }

    public function updateUser($id, $userData) {
        if (isset($userData['role']) && $userData['role'] === 'administrator') {
            if (!$this->isCurrentUserAdmin()) {
                throw new Exception("No tienes permisos para cambiar un usuario a administrador");
            }
        }
        return $this->userModel->update($id, $userData);
    }

    public function deleteUser($id) {
        return $this->userModel->delete($id);
    }

    public function getAllUsers() {
        $cursor = $this->userModel->getAll();
        // Convertir el cursor a un array
        $users = iterator_to_array($cursor);
        return $users;
    }

    public function isCurrentUserAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $user = $this->getUser($_SESSION['user_id']);
        return isset($user['role']) && $user['role'] === 'administrator';
    }

    public function authenticate($mobile, $password) {
        $user = $this->userModel->getByPhone($mobile);
        if (!$user) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }

        if (password_verify($password, $user['password'])) {
            session_start();
            $token = generateToken();
            $_SESSION['verification_token'] = $token;
            $_SESSION['token_expiration'] = time() + 300;

            sendVerificationEmail($user['email'], $token);
            $_SESSION['user_id'] = (string)$user['_id'];
            $_SESSION['mobile'] = (string)$user['mobile'];
            $_SESSION['role'] = $user['role'];
            return ['success' => true, 'message' => 'Autenticado correctamente'];
        } else {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }
    }

    public function verifyToken($inputToken) {
        session_start();
        if (!isset($_SESSION['verification_token']) || !isset($_SESSION['token_expiration'])) {
            return ['success' => false, 'message' => 'No se ha generado un token de verificación'];
        }

        if (time() > $_SESSION['token_expiration']) {
            return ['success' => false, 'message' => 'El token ha expirado'];
        }
        $actualInputToken = trim($inputToken);
        $sessionToken = trim($_SESSION['verification_token']);
        if ($actualInputToken === $sessionToken) {
            unset($_SESSION['verification_token']);
            unset($_SESSION['token_expiration']);
            $_SESSION['token_verified'] = true;
            return [
                'success' => true, 
                'message' => 'Verificación exitosa',
                'role' => $_SESSION['role'] ?? 'guest'
            ];
        } else {
            return ['success' => false, 'message' => 'Token incorrecto'];
        }

        
    }

    public function logOut() {
        session_start();
        session_destroy();
    }
}
?>