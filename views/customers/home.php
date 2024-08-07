<?php
    session_start();
    require_once '../../auth/auth.php';
    requireAuth();
    isCustomer();
    require_once '../../controllers/UserController.php';
    require_once '../../controllers/DigitalCardController.php';
    require_once '../../controllers/ProductController.php';
    $userController = new UserController();
    $cardController = new DigitalCardController();
    $productController = new ProductController();
    $_SESSION['location']='home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos</title>
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
        $products = $productController->getAllProducts();
        $products_exists = false;
        include_once './layout/navbar.php';
       
    ?>
    <div class="container mt-4">
        <h2 class="text-center">Puntos acumulados</h2>
        <div id="cardContainer" class="card-container">
            <div class='d-flex flex-wrap justify-content-center gap-5'>
              <div class='customer-card' style="background-image: url('<?php echo BASE_URL; ?>assets/img/card.png')">
                <p class='fs-5'><?php echo htmlspecialchars($card['card_number']); ?></p>
              </div>
              <div style="min-width: 250px">
                <div class='customer-points h-100'>
                  <div class='text-center h-100 pt-3 col-5'>
                    <p class='fw-bold fs-4 text-white'>Puntos</p>
                    <p class='mt-4 fw-bold fs-4'><?php echo htmlspecialchars($card['points']); ?></p>  
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="mt-4 home-products py-5">
        <h2 class="text-center">Productos</h2>
        <div class="d-flex flex-wrap justify-content-center gap-4">
        <?php foreach ($products as $product): ?>
            <?php if ($product['type'] === 'product'): ?>
                <?php $products_exists = true; ?>
                <div class="product-card">
                    <div class="product-image">
                        <img class='img-fluid' src="<?php echo htmlspecialchars($product['img'])?>" alt="imgAlt"  style="max-height:250px"/>
                    </div>
                    
                    <div class="product-details">
                        <p class="product-name"><?php echo htmlspecialchars($product['name']); ?></p>
                        <p class="product-price"><?php echo htmlspecialchars($product['price']); ?></p>
                    <?php if($product['stock']>=1): ?> <p> Disponible: <?php echo htmlspecialchars($product['stock']); ?> unidades </p><?php else:?><p style="color:#D61515; background:#d6151540" className='ps-2 rounded-1 col-7'>No disponible</p><?php endif; ?>
                        <button class='general-button' onclick="buyProduct('<?php echo $product['_id']; ?>')">Comprar</button>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>

        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function buyProduct(_id) {
            fetch(`./services/getOneProduct.php?imgId=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const userId = `<?php echo $_SESSION['user_id']?>`
                        Swal.fire({
                             customClass: {
                                container: 'add-modal',
                                popup: 'add-modal-popup',
                                confirmButton: 'general-button px-4',
                                cancelButton: 'cancel-modal-button'
                            },
                            html: `
                                <form id="buyProductForm">
                                    <p class="fs-3 text-black fw-bold">Comprar producto</p>
                                    <div>
                                        <input type="hidden" name="_id" value="${data.product._id}"/>
                                        <img src="${data.product.img}" class="product-detail-img col-5"/>
                                        <p class="text-left">${data.product.name}</p>
                                        <p>${data.product.description}</p>
                                        <p>Disponible: ${data.product.stock} unidades</p>
                                        <p  class="form-control w-25 border-0" disabled>Precio: $${data.product.price}</p>
                                        <input type="hidden" name="price" value="${data.product.price}"/>
                                        <input name="quantity" type="number" class="form-control w-25 border-0" value="0"/>
                                    </div>
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Comprar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const form = document.getElementById('buyProductForm');
                                const formData = new FormData(form);
                                const data = Object.fromEntries(formData.entries());
                            
                                return fetch(`./services/buyProduct.php?user_id=${userId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.success) {
                                        Swal.showValidationMessage(`Error: ${data.message}`);
                                    }
                                    return data;
                                })
                                .catch(error => {
                                    Swal.showValidationMessage(`Error: ${error}`);
                                });
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                Swal.fire('Éxito', 'Cliente actualizado exitosamente', 'success');
                            }
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