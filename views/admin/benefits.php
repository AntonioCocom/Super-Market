<?php
    session_start();
    require_once '../../auth/auth.php';
    requireAuth();
    isAdmin();
    require_once '../../controllers/UserController.php';
    require_once '../../controllers/BenefitController.php'; // Cambiar a BenefitController
    $_SESSION['location']='benefits';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficios</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>views/admin/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <?php 
        $benefitController = new BenefitController();
        $benefits = $benefitController->getAllBenefits();
        $benefits_exists = false;
        include_once './layout/navbar.php';
    ?>
    <div class="container mt-4">
        <h2>Lista de beneficios</h2>
        <button class="general-button px-4" id="addBenefitButton">
            <i class="bi bi-plus-circle"></i> Agregar beneficio
        </button>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Beneficio</th>
                        <th>Descripción</th>
                        <th>Validez</th>
                        <th>Restricción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="benefitsTableBody">
                    <?php foreach ($benefits as $benefit): ?>
                        <?php $benefits_exists = true; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($benefit['company']); ?></td>
                            <td><?php echo htmlspecialchars($benefit['benefit']); ?></td>
                            <td><?php echo htmlspecialchars($benefit['description']); ?></td>
                            <td>
                                <?php
                                $validityDate = new DateTime($benefit['validity']);
                                echo htmlspecialchars($validityDate->format('d/m/Y')); // o 'j \d\e F \d\e Y' para formato largo
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($benefit['restrictions']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($benefit['img']); ?>" alt="Imagen del beneficio" style="width: 100px;"></td>
                            <td>
                                <button class="btn btn-sm btn-warning p-2 px-3" onclick="editBenefit('<?php echo $benefit['_id']; ?>')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteBenefit('<?php echo $benefit['_id']; ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (!$benefits_exists): ?>
                <div class="w-100 text-center">
                    Aún no hay ningún beneficio registrado
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('addBenefitButton').addEventListener('click', function() {
            Swal.fire({
                customClass: {
                    container: 'add-modalr',
                    popup: 'add-modal-popup',
                    confirmButton: 'general-button px-4',
                    cancelButton: 'cancel-button px-4'
                },
                html: `
                    <form id="addBenefitForm">
                        <p class="fs-3 text-black fw-bold">Agregar beneficio</p>
                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="company" class="form-label">Compañía</label>
                                <input type="text" class="form-control" id="company" name="company" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="benefit" class="form-label">Beneficio</label>
                                <input type="text" class="form-control" id="benefit" name="benefit" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="mb-3 w-50">
                                <label for="validity" class="form-label">Validez</label>
                                <input type="date" class="form-control" id="validity" name="validity" required>
                            </div>
                            <div class="mb-3 w-50">
                                <label for="restriction" class="form-label">Restricción</label>
                                <input type="text" class="form-control" id="restriction" name="restriction" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="img" class="form-label">Imagen (URL)</label>
                            <input type="text" class="form-control" id="img" name="img" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const form = document.getElementById('addBenefitForm');
                    const formData = new FormData(form);
                    const benefitData = Object.fromEntries(formData.entries());

                    return fetch('./services/benefits/addBenefit.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(benefitData)
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
                    Swal.fire('Éxito', 'Beneficio agregado exitosamente', 'success');
                    updateBenefitsList(); // Actualizar la lista de beneficios
                }
            });
        });

        function deleteBenefit(_id) {
            fetch(`./services/benefits/getOneBenefit.php?_id=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: `¿Deseas eliminar el beneficio de ${data.benefit.benefit}?`,
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
                                return fetch(`./services/benefits/deleteBenefit.php?_id=${_id}`, {
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
                                Swal.fire('Éxito', 'Beneficio eliminado exitosamente', 'success');
                                updateBenefitsList();
                            }
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo obtener los datos del beneficio', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error al obtener los datos del beneficio', 'error');
                });
        }

        function editBenefit(_id) {
            fetch(`./services/benefits/getOneBenefit.php?_id=${_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        // Función para convertir la fecha a YYYY-MM-DD
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
                                confirmButton: 'general-button px-4',
                                cancelButton: 'cancel-button px-4'
                            },
                            html: `
                            <form id="editBenefitForm">
                                <p class="fs-3 text-black fw-bold">Agregar beneficio</p>
                                <div class="d-flex gap-3">
                                    <div class="mb-3 w-50">
                                        <label for="company" class="form-label">Compañía</label>
                                        <input type="text" class="form-control" id="company" name="company" value="${data.benefit.company}" required>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label for="benefit" class="form-label">Beneficio</label>
                                        <input type="text" class="form-control" id="benefit" name="benefit" value="${data.benefit.benefit}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="description" name="description" required>${data.benefit.description}</textarea>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="mb-3 w-50">
                                        <label for="validity" class="form-label">Validez</label>
                                        <input type="date" class="form-control" id="validity" name="validity" value="${formattedDate}" required>
                                    </div>
                                    <div class="mb-3 w-50">
                                        <label for="restriction" class="form-label">Restricción</label>
                                        <input type="text" class="form-control" id="restriction" name="restriction" value="${data.benefit.restrictions}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="img" class="form-label">Imagen (URL)</label>
                                    <input type="text" class="form-control" id="img" name="img" value="${data.benefit.img}" required>
                                </div>
                            </form>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Guardar',
                            cancelButtonText: 'Cancelar',
                            preConfirm: () => {
                                const form = document.getElementById('editBenefitForm');
                                const formData = new FormData(form);
                                const benefitData = Object.fromEntries(formData.entries());

                                return fetch('./services/benefits/editBenefit.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(benefitData)
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
                                Swal.fire('Éxito', 'Beneficio actualizado exitosamente', 'success');
                                updateBenefitsList();
                            }
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo obtener los datos del beneficio', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error al obtener los datos del beneficio', 'error');
                });
        }

        function updateBenefitsList() {
            fetch('./services/benefits/getBenefits.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('benefitsTableBody');
                        tableBody.innerHTML = data.benefits.map(benefit => `
                            <tr>
                                <td>${benefit.company}</td>
                                <td>${benefit.benefit}</td>
                                <td>${benefit.description}</td>
                                <td>${benefit.validity}</td>
                                <td>${benefit.restriction}</td>
                                <td><img src="${benefit.img}" alt="Imagen del beneficio" style="width: 100px;"></td>
                                <td>
                                    <button class="btn btn-sm btn-warning p-2 px-3" onclick="editBenefit('${benefit._id}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger p-2 px-3" onclick="deleteBenefit('${benefit._id}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar la lista de beneficios', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error al actualizar la lista de beneficios', 'error');
                });
        }
    </script>
</body>
</html>
