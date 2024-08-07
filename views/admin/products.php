<?php
    session_start();
    require_once '../../auth/auth.php';
    requireAuth();
    isAdmin();
    require_once '../../controllers/UserController.php';
    require_once '../../controllers/ProductController.php';
    $productController = new ProductController();
    $_SESSION['location']='products';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <?php 
        $products = $productController->getAllProducts();
        $products_exists = false;
        include_once './layout/navbar.php';
    ?>
    <div class="container mt-4">
        <button class="general-button px-4 mb-3" id="addProductButton">
            <i class="bi bi-plus-circle"></i> Agregar producto
        </button>
        <h2>Lista de productos</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <?php foreach ($products as $product): ?>
                        <?php if ($product['type'] === 'product'): ?>
                        <?php $products_exists = true ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <?php if (!empty($product['img'])): ?>
                                        <img src="<?php echo $product['img'] ?>" alt="Product Image" style="width: 100px; height: auto;">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td >
                                    <div class="d-flex flex-wrap gap-2 h-100 w-100">
                                        <button class="btn btn-sm btn-warning p-2 px-3" onclick="editProduct('<?php echo $product['_id']; ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteProduct('<?php echo $product['_id']; ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button> 
                                    </div>
                                    
                                </td>
                            </tr> 
                        <?php endif; ?>                       
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$products_exists): ?>
                <div class="w-100 text-center">
                    Aun no hay algun producto registrado
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container mt-4">
        <h2>Lista de premios</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <?php foreach ($products as $product): ?>
                        <?php if ($product['type'] === 'award'): ?>
                        <?php $products_exists = true ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['_id']); ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <?php if (!empty($product['img'])): ?>
                                        <img src="<?php echo $product['img'] ?>" alt="Product Image" style="width: 100px; height: auto;">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td >
                                    <div class="d-flex flex-wrap gap-2 h-100 w-100">
                                        <button class="btn btn-sm btn-warning p-2 px-3" onclick="editProduct('<?php echo $product['_id']; ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteProduct('<?php echo $product['_id']; ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button> 
                                    </div>
                                    
                                </td>
                            </tr> 
                        <?php endif; ?>                       
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$products_exists): ?>
                <div class="w-100 text-center">
                    Aun no hay algun producto registrado
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('addProductButton').addEventListener('click', function() {
            Swal.fire({
                customClass: {
                    container: 'add-modalr',
                    popup: 'add-modal-popup',
                    confirmButton: 'general-button px-4',
                    cancelButton: 'cancel-button px-4'
                },
                html: `
                    <form id="addProductForm">
                        <p class="fs-3 text-black fw-bold">Agregar producto</p>
                        <div class="d-flex gap-3 ">
                            <div class="mb-3 w-50">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="price" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="d-flex ">
                            <div class="mb-3 w-100">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="description" name="description" required></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="0" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="seleccionar" disabled selected>Seleccionar</option>
                                    <option value="product">Producto</option>
                                    <option value="award">Premio</option>
                                </select>
                            </div>
                        </div>
                         <div class="d-flex">
                            <div class="mb-3 w-100">
                                <label for="img" class="form-label">Imagen</label>
                                <input type="text" class="form-control" id="img" name="img" required>
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const form = document.getElementById('addProductForm');
                    const formData = new FormData(form);
                    const productData = Object.fromEntries(formData.entries());

                    return fetch('./services/products/addProduct.php', {
                        method: 'POST',
                        body: JSON.stringify(productData)
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
                        Swal.fire('Éxito', 'Producto agregado exitosamente', 'success');
                        updateProductsList();
                    }
                });
            });
        function deleteProduct(_id) {
            fetch(`./services/products/getOneProduct.php?imgId=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: `¿Deseas eliminar el producto ${data.product.name}?`,
                            icon: "warning",
                            text: "¡No podrás revertir esto!",
                            customClass: {
                                container: 'add-modal',
                                popup: 'add-modal-popup',
                                confirmButton: 'delete-button px-4',
                                cancelButton: 'cancel-button px-4'
                            },
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Eliminar',
                            preConfirm: () => {
                            
                                return fetch(`./services/products/deleteProduct.php?_id=${_id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    }
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
                                Swal.fire('Éxito', 'Producto eliminado exitosamente', 'success');
                                updateProductsList();
                            }
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo obtener los datos del producto', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error al obtener los datos del producto', 'error');
                });
        
        }
        function editProduct(_id) {
            fetch(`./services/products/getOneProduct.php?imgId=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                             customClass: {
                                container: 'add-modal',
                                popup: 'add-modal-popup',
                                confirmButton: 'general-button',
                                cancelButton: 'cancel-modal-button'
                            },
                            html: `
                            <form id="editProductForm">
                                <p class="fs-3 text-black fw-bold">Agregar producto</p>
                                <div class="d-flex gap-3 ">
                                    <div class="mb-3 w-50">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name" value="${data.product.name}" required>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label for="price" class="form-label">Precio</label>
                                        <input type="number" class="form-control" id="price" name="price" value="${data.product.price}" required>
                                    </div>
                                </div>
                                <div class="d-flex ">
                                    <div class="mb-3 w-100">
                                        <label for="description" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="description" name="description" required>${data.product.description}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <div class="mb-3 w-50">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control" id="stock" name="stock" value="${data.product.stock}" required>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label for="type" class="form-label">Tipo</label>
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="seleccionar" disabled>Seleccionar</option>
                                            <option value="product" ${data.product.type === 'product' ? 'selected' : ''}>Producto</option>
                                            <option value="award" ${data.product.type === 'award' ? 'selected' : ''}>Premio</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="mb-3 w-100">
                                        <label for="img" class="form-label">Imagen</label>
                                        <input type="text" class="form-control" id="img" name="img" value="${data.product.img}" required>
                                    </div>
                                </div>
                            </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const form = document.getElementById('editProductForm');
                                const formData = new FormData(form);
                                const productData = Object.fromEntries(formData.entries());
                            
                                return fetch(`./services/products/updateProduct.php?_id=${_id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(productData)
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
                                updateProductsList();
                                Swal.fire('Éxito', 'Producto actualizado exitosamente', 'success');
                            }
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo obtener los datos del producto', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error al obtener los datos del producto', 'error');
                });
        }
        function updateProductsList() {
            fetch('./services/products/getProducts.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const products = data.products;
                    const tableBody = document.getElementById('productsTableBody');
                
                    tableBody.innerHTML = '';
                
                    products.forEach(product => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${product._id}</td>
                            <td>${product.name}</td>
                            <td>${product.price}</td>
                            <td>${product.stock}</td>
                            <td>${product.description}</td>
                            <td><img src="${product.img}" alt="Product Image" style="width: 100px; height: auto;"></td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 h-100 w-100">
                                     <button class="btn btn-sm btn-warning p-2 px-3" onclick="editProduct('${product._id}')">
                                         <i class="bi bi-pencil"></i>
                                     </button>
                                     <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteProduct('${product._id}')">
                                         <i class="bi bi-trash"></i>
                                     </button> 
                                 </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Error al obtener la lista de productos:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        function loadProductImage(imgId) {
            const imageUrl = `./services/products/getImg.php?imgId=${imgId}`;
            document.getElementById('productImage').src = imageUrl;
        }
    </script>
</body>
</html>