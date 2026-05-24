<!DOCTYPE html>

<html lang="en">

<head>
    <title>Compras/Pagos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" type="image/jpeg" href="2.jpeg" sizes="16x16">
</head>

<style type="text/css">
    html,
    body {
        height: 100%;
        margin: 0;
    }
    
    #pag {
        min-height: 100%;
    }
    
    img {
        max‐width: 100%;
        height: auto;
    }
</style>


<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">


            </div>

            <div class="panel-body">
    
                <div class="container-fluid" id="pag">


                    <div class="row">
                        <div class="col-xs-6">
                            <img src="logos/pesos.png" class="img-rounded efectivo">
                        </div>
                        <div class="col-xs-6">
                            <img src="logos/credencial_servicio.png" class="img-rounded adelanto"> 
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-6">
                            <img src="logos/visa.png" class="img-rounded visaDebitoProvincia"> Debito Provincia
                        </div>
                        <div class="col-xs-6">
                            <img src="logos/cabaldebito.png" class="img-rounded cabalDebitoCredicoop"> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <img src="logos/visa.png" class="img-rounded cabalCredicoCredicoop"> Credito Credicoop
                        </div>
                        <div class="col-xs-6">
                            <img src="logos/cabal.png" class="img-rounded visaCreditoCredicoop">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <img src="logos/dni.png" class="img-rounded cuentadni"> 
                        </div>
                        <div class="col-xs-6">
                            <img src="logos/pago.png" class="img-rounded mercadopago">
                        </div>
                    </div>


                </div>


                <br>
                <!-- Modal -->
                <div class="modal fade" id="modal_fabian" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal contenido-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Compras o Pagos</h4>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

        </div>
        <!--




      
Panel cierra-->



    </div>

    <script>
        $('.efectivo').on('click', function() {
            $('.modal-body').load('modal.php?pago=1&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.adelanto').on('click', function() {
            $('.modal-body').load('modal.php?pago=6&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.visaDebitoProvincia').on('click', function() {
            $('.modal-body').load('modal.php?pago=3&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.cabalDebitoCredicoop').on('click', function() {
            $('.modal-body').load('modal.php?pago=2&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.cabalCredicoCredicoop').on('click', function() {
            $('.modal-body').load('modal.php?pago=4&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.visaCreditoCredicoop').on('click', function() {
            $('.modal-body').load('modal.php?pago=5&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.cuentadni').on('click', function() {
            $('.modal-body').load('modal.php?pago=7&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });

        $('.mercadopago').on('click', function() {
            $('.modal-body').load('modal.php?pago=8&user=Pabla', function() {
                $('#modal_fabian').modal({
                    show: true
                });
            });
        });
    </script>
</body>

</html>