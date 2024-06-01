<?php require_once "config/conexion.php";
require_once "config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Carrito de Compras</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/estilos.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="./">MercaOnline</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </div>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Car</h1>
                <p class="lead fw-normal text-white-50 mb-0">Your added products..</p>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5">
            <div class="row">
            <div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Cuantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="tblCarrito">
                <!-- Filas del carrito -->
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-5 ms-auto">
    <h4>Total: <span id="total_pagar">0.00</span></h4>
    <div class="d-grid gap-2">
        <div id="paypal-button-container"></div>
        <button class="btn btn-warning" type="button" id="btnVaciar">Clear</button>
        <button class="btn btn-success" type="button" id="btnComprar">Buy</button>
    </div>
</div>
</div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; ?</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&locale=<?php echo LOCALE; ?>"></script>
    <script src="assets/js/scripts.js"></script>
    <!-- Parte modificada del script en carrito.php -->
<script>
    mostrarCarrito();

    function mostrarCarrito() {
        if (localStorage.getItem("productos") != null) {
            let array = JSON.parse(localStorage.getItem('productos'));
            if (array.length > 0) {
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    async: true,
                    data: {
                        action: 'buscar',
                        data: array
                    },
                    success: function(response) {
                        console.log(response);
                        const res = JSON.parse(response);
                        let html = '';
                        res.datos.forEach((element, index) => {
                            html += `
                        <tr>
                            <td>${element.id}</td>
                            <td>${element.nombre}</td>
                            <td>${element.precio}</td>
                            <td>
                                <input type="number" min="1" value="1" class="form-control cantidad" data-index="${index}">
                            </td>
                            <td>${element.precio}</td>
                        </tr>
                        `;
                        });
                        $('#tblCarrito').html(html);
                        $('#total_pagar').text(res.total);

                        // Actualizar total al cambiar la cantidad
                        $('.cantidad').change(function() {
                            const index = $(this).data('index');
                            const cantidad = $(this).val();
                            const precio = res.datos[index].precio;
                            const subtotal = precio * cantidad;
                            $(this).closest('tr').find('td:last').text(subtotal);
                            actualizarTotal();
                        });

                        // Renderizar botones de PayPal
                        paypal.Buttons({
                            style: {
                                color: 'blue',
                                shape: 'pill',
                                label: 'pay'
                            },
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: $('#total_pagar').text()
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    alert('Transaction completed by ' + details.payer.name.given_name);
                                    localStorage.removeItem('productos'); // Limpiar el carrito
                                    window.location.href = 'gracias.php';
                                });
                            }
                        }).render('#paypal-button-container');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }
    }

    function actualizarTotal() {
        let total = 0;
        $('#tblCarrito tr').each(function() {
            total += parseFloat($(this).find('td:last').text());
        });
        $('#total_pagar').text(total.toFixed(2));
    }

    $('#btnComprar').click(function() {
        localStorage.removeItem('productos'); // Limpiar el carrito
        window.location.href = 'gracias.php';
    });

    $('#btnVaciar').click(function() {
        localStorage.removeItem('productos'); // Limpiar el carrito
        $('#tblCarrito').html('');
        $('#total_pagar').text('0.00');
    });
</script>
</body>

</html>