<?php
require_once '../../../../controllers/BenefitController.php';

// Obtener datos de la solicitud (por ejemplo, desde JSON en el cuerpo de la solicitud)
$data = json_decode(file_get_contents('php://input'), true);
// Verificar que se recibieron los datos necesarios
if (
    !isset($data['benefit']) || $data['benefit'] ==='' ||
    !isset($data['company']) || $data['company'] ==='' ||
    !isset($data['description']) || $data['description'] ==='' ||
    !isset($data['validity']) || $data['validity'] ==='' ||
    !isset($data['img']) || $data['img'] ===''
) {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes']);
    exit;
}
$validityDate = new DateTime($data['validity']); // Convierte a DateTime si es necesario
$data['validity'] = $validityDate->format(DateTime::ATOM); // O almacena como cadena YYYY-MM-DD

// Crear el documento de beneficio
$benefit = [
    'company' => $data['company'],
    'benefit' => $data['benefit'],
    'description' => $data['description'],
    'validity' => $data['validity'],
    'restrictions' => $data['restrictions'] ?? 'No tiene restricción',
    'img' => $data['img'],
];
$benefitController = new BenefitController();
$result = $benefitController->createBenefit($benefit);

echo json_encode($result);
exit;
?>
