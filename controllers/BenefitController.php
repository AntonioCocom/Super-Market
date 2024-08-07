<?php

require_once __DIR__ . '/../models/Benefits.php';

class BenefitController {
    private $benefitModel;

    public function __construct() {
        $this->benefitModel = new Benefit();
    }

    public function createBenefit($benefitData) {
        return $this->benefitModel->create($benefitData);
    }

    public function getBenefit($id) {
        return $this->benefitModel->getOne($id);
    }

    public function updateBenefit($id, $benefitData) {
        return $this->benefitModel->update($id, $benefitData);
    }

    public function deleteBenefit($id) {
        return $this->benefitModel->delete($id);
    }

    public function getAllBenefits() {
        $cursor = $this->benefitModel->getAll();
        $benefits = iterator_to_array($cursor);
        return $benefits;
    }
}
?>