<?php
require_once '../../../../controllers/BenefitController.php';

$benefitController = new BenefitController();

$benefits = $benefitController->getAllBenefits();


foreach ($benefits as &$benefit) {
    if ($benefit['_id'] instanceof MongoDB\BSON\ObjectId) {
        $benefitId = (string) $benefit['_id'];
    } elseif (isset($benefit['_id']['$oid'])) {
        $benefitId = $benefit['_id']['$oid'];
    } else {
        $benefitId = (string) $benefit['_id'];
    }
    
    $benefit['_id'] = $benefitId;
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'benefits' => $benefits]);
?>