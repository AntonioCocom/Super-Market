<?php
    require_once __DIR__ . '/../config.php';

    function isTokenVerified() {
        return isset($_SESSION['token_verified']) && $_SESSION['token_verified'] === true;
    }

    function requireTokenVerification() {
        if (!isTokenVerified()) {
            header('Location: '.BASE_URL.'auth/token.php');
            exit();
        }
    }

    function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    function isAdmin(){
        if($_SESSION['role'] !== 'administrator'){
            header('Location: '.BASE_URL.'views/notAuthorized.php');
            exit();
        };
    }

    function isCustomer(){
        if($_SESSION['role'] !== 'customer'){
            header('Location: '.BASE_URL.'views/notAuthorized.php');
            exit();
        };
    }

    function requireAuth() {
        if (!isAuthenticated()) {
            header('Location: '.BASE_URL.'auth/login.php');
            exit();
        }
    }

?>