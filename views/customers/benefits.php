<?php
    session_start();
    require_once '../../auth/auth.php';
    requireAuth();
    isCustomer();
    require_once '../../controllers/UserController.php';
    require_once '../../controllers/DigitalCardController.php';
    require_once '../../controllers/BenefitController.php';
    $userController = new UserController();
    $cardController = new DigitalCardController();
    $benefitController = new BenefitController();
    $_SESSION['location']='benefits';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficios</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/customers/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <?php 
        $customer = $userController->getUser($_SESSION['user_id']);
        $card = $cardController->getCard($_SESSION['user_id']);
        $benefits = $benefitController->getAllBenefits();
        $benefit_exists = false;
        include_once './layout/navbar.php';
       
    ?>
    <div class="mt-4 home-products py-5">
        <h2 class="text-center">Beneficios</h2>
        <div class="d-flex flex-wrap justify-content-center gap-4">
        <?php foreach ($benefits as $benefit): ?>
            <?php $benefits_exists = true; ?>
            <div class="product-card">
                <div class="product-image" onclick="viewBenefit('<?php echo $benefit['_id']; ?>')">
                    <img class='img-fluid' src="<?php echo htmlspecialchars($benefit['img'])?>" alt="imgAlt"  style="max-height:250px"/>
                </div>               
            </div>
        <?php endforeach; ?>
        </div>

        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewBenefit(_id) {
    fetch(`../admin/services/benefits/getOneBenefit.php?_id=${_id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                function formatDateToInput(dateString) {
                    // Crear un objeto Date con la fecha en formato ISO con zona horaria
                    const date = new Date(dateString);

                    // Obtener el año, mes y día en UTC
                    const year = date.getUTCFullYear();
                    const month = ('0' + (date.getUTCMonth() + 1)).slice(-2); // Mes en formato de dos dígitos
                    const day = ('0' + date.getUTCDate()).slice(-2); // Día en formato de dos dígitos

                    return `${year}-${month}-${day}`;
                }


                        // Usar la función para obtener el formato adecuado
                const formattedDate = formatDateToInput(data.benefit.validity);
                Swal.fire({
                    customClass: {
                        container: 'add-modal',
                        popup: 'add-modal-popup',
                        confirmButton: 'general-button px-4'
                    },
                    html: `
                        <div>
                            <p class="fs-3 text-black fw-bold">Detalle del beneficio</p>
                            <div>
                                <img src="${data.benefit.img}" class="product-detail-img col-5"/>
                                <p class="text-left">${data.benefit.benefit}</p>
                                <p>${data.benefit.description}</p>
                                <p>Vigencia al: ${formattedDate}</p>
                            </div>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar',
                    showCancelButton: false
                });
            } else {
                Swal.fire('Error', 'No se pudo obtener los datos del cliente', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Error al obtener los datos del cliente', 'error');
        });
}

    </script>
</body>
<footer class="footer">
  <p>Copyright © 1999-2024 SuperMarcket</p>
  <p><span >supermarcket@gmail.com</span></p>
</footer>
</html>