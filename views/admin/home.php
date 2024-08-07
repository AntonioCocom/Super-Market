<?php
    session_start();
    require_once '../../auth/auth.php';
    requireAuth();
    isAdmin();
    require_once '../../controllers/UserController.php';
    require_once '../../controllers/DigitalCardController.php';
    $userController = new UserController();
    $cardController = new DigitalCardController();
    $_SESSION['location']='home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <?php 
        $customers = $userController->getAllUsers();
        $customers_exists = false;
        include_once './layout/navbar.php';
       
    ?>
    <div class="container mt-4">
        <h2>Lista de clientes</h2>
        <button class="general-button px-4" id="addClientButton">
            <i class="bi bi-plus-circle"></i> Agregar cliente
        </button>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Móvil</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Ciudad</th>
                        <th>Puntos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="customersTableBody">
                    <?php foreach ($customers as $customer): ?>
                        <?php if ($customer['role'] === 'customer'): ?>
                            <?php $customers_exists = true; ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($customer['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['address']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo htmlspecialchars($customer['state']); ?></td>
                                <td><?php echo htmlspecialchars($customer['town']); ?></td>
                                <td class="card-points" data-id="<?php echo $customer['_id']; ?>">
                                    <?php $card = $cardController->getCard($customer['_id']); echo htmlspecialchars($card['points']); ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning p-2 px-3" onclick="editClient('<?php echo $customer['_id']; ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteClient('<?php echo $customer['_id']; ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$customers_exists): ?>
                <div class="w-100 text-center">
                    Aun no hay algun cliente registrado
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('addClientButton').addEventListener('click', function() {
            Swal.fire({
                customClass: {
                    container: 'add-modalr',
                    popup: 'add-modal-popup',
                    confirmButton: 'general-button px-4',
                    cancelButton: 'cancel-button px-4'
                },
                html: `
                    <form id="addUserForm">
                        <p class="fs-3 text-black fw-bold">Agregar cliente</p>
                        <div class="d-flex gap-3 ">
                            <div class="mb-3 w-50">
                                <label for="first_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="last_name" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="mobile" class="form-label">Móvil</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="town" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="town" name="town" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="points" class="form-label">Puntos</label>
                                <input type="number" class="form-control" id="points" name="points" value="0" required>
                            </div>
                        </div>                        
                        <div class="mb-3 w-100">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const form = document.getElementById('addUserForm');
                    const formData = new FormData(form);
                    const userData = Object.fromEntries(formData.entries());

                    return fetch('./services/addcustomer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(userData)
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
                    Swal.fire('Éxito', 'Cliente agregado exitosamente', 'success');
                    updateCustomersList(); // Actualizar la lista de customeres
                }
            });
        });
        function deleteClient(_id) {
            fetch(`./services/getOneCustomer.php?_id=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar el modal de edición con los datos del customere
                        Swal.fire({
                            title: `¿Deseas eliminar a ${data.customer.first_name} ${data.customer.last_name}?`,
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
                            
                                return fetch(`./services/deleteCustomer.php?_id=${_id}`, {
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
                                Swal.fire('Éxito', 'Cliente eliminado exitosamente', 'success');
                                updateCustomersList();
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
        function editClient(_id) {
            // Hacer una solicitud para obtener los datos del customere
            fetch(`./services/getOneCustomer.php?_id=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar el modal de edición con los datos del customere
                        Swal.fire({
                             customClass: {
                                container: 'add-modal',
                                popup: 'add-modal-popup',
                                confirmButton: 'general-button',
                                cancelButton: 'cancel-modal-button'
                            },
                            html: `
                                <form id="editUserForm">
                                    <p class="fs-3 text-black fw-bold">Agregar cliente</p>
                                    <div class="d-flex gap-3">
                                        <div class="mb-3 w-50">
                                            <label for="edit_mobile" class="form-label">Móvil</label>
                                            <input type="text" class="form-control" id="edit_mobile" name="mobile" value="${data.customer.mobile}" required>
                                        </div>
                                        <div class="mb-3 w-50">
                                            <label for="edit_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="edit_email" name="email" value="${data.customer.email}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_first_name" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="edit_first_name" name="first_name" value="${data.customer.first_name}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_last_name" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" id="edit_last_name" name="last_name" value="${data.customer.last_name}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_address" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="edit_address" name="address" value="${data.customer.address}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_state" class="form-label">Estado</label>
                                            <input type="text" class="form-control" id="edit_state" name="state" value="${data.customer.state}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="edit_town" class="form-label">Ciudad</label>
                                            <input type="text" class="form-control" id="edit_town" name="town" value="${data.customer.town}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_points" class="form-label">Puntos</label>
                                        <input type="number" class="form-control" id="edit_points" name="points" value="${data.card.points}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>
                                </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const form = document.getElementById('editUserForm');
                                const formData = new FormData(form);
                                const userData = Object.fromEntries(formData.entries());
                            
                                return fetch(`./services/updateCustomer.php?_id=${_id}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(userData)
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
                                updateCustomersList();
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
        function updateCustomersList() {
            fetch('./services/getCustomers.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const customers = data.customers; // Suponiendo que la respuesta contiene una lista de customeres
                    const tableBody = document.getElementById('customersTableBody');
                    const cards = data.cards;
                
                    // Limpiar el contenido actual de la tabla
                    tableBody.innerHTML = '';
                
                    // Rellenar la tabla con la lista actualizada de customeres
                    customers.forEach(customer => {
                        const customerId = customer._id;
                        const card = cards[customerId] || {};
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${customer.mobile}</td>
                            <td>${customer.first_name}</td>
                            <td>${customer.last_name}</td>
                            <td>${customer.address}</td>
                            <td>${customer.email}</td>
                            <td>${customer.state}</td>
                            <td>${customer.town}</td>
                            <td>${card.points || 'N/A'}</td>
                            <td>
                                <button class="btn btn-sm btn-warning p-2 px-3" onclick="editClient('${customer['_id']}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteClient('${customer['_id']}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Error al obtener la lista de customeres:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>