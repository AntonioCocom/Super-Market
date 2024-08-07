<?php

require_once __DIR__ . '/../models/DigitalCard.php';

class DigitalCardController {
    private $digitalCardModel;

    public function __construct() {
        $this->digitalCardModel = new DigitalCard();
    }

    public function createCard($digitalCardData) {
        return $this->digitalCardModel->create($digitalCardData);
    }

    public function getCard($id) {
        return $this->digitalCardModel->getOne($id);
    }

    public function updateCard($id, $cardData) {
        return $this->digitalCardModel->update($id, $cardData);
    }

    public function deleteCard($id) {
        return $this->digitalCardModel->delete($id);
    }

    public function getAllCards() {
        $cursor = $this->digitalCardModel->getAll();
        $cards = iterator_to_array($cursor);
        return $cards;
    }
}
?>