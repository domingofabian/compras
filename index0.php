<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>

<body>
    <div>
        <video autoplay loop controls>
            <source src="img/video.mp4" type="video/mp4">
        </video>
        <div class="capa"></div>
    </div>

    <div class="caja">
        <section id="intro">
            <h1>
                ¡<span>Bienvenido al torneo</span>!
            </h1>
            <h2>
                Lugar donde se hace
            </h2>
            <p>aca te damos la bienvenida a este fenomenal torneo de taekondo ba bababababbababbababababbababbabababab
                bababa abbabba bababba ababb. </p>
            <a href="tv/" class="btn"><button>IR AL TORNEO</button></a>
        </section>
        <div id="sec2">
            <div class="faq-container">
                <section class="faq-section">
                    <div class="faq-inner">

                        <h1><?php
                        require_once 'php/conexion.php';

                        // Set default timezone if needed, usually best to match server/DB
                        date_default_timezone_set('America/Argentina/Buenos_Aires'); // Adjust based on user location if known, else default
                        
                        $sql = "SELECT nombre, fecha FROM torneo WHERE estado = 'pendiente' ORDER BY fecha ASC LIMIT 1";
                        $result = mysqli_query($conexion, $sql);

                        $targetDate = null;
                        $torneoNombre = "Próximo Torneo";

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $targetDate = $row['fecha'];
                            $torneoNombre = $row['nombre'];
                        } else {
                            echo "No hay torneos pendientes.";
                        }
                        ?>

                            <?php if ($targetDate): ?>
                                <div id="countdown-display">Cargando...</div>
                                <script>
                                    (function () {
                                        // Parse DB date (assuming YYYY-MM-DD HH:MM:SS format)
                                        // Note: 'fecha' in DB is varchar, ensure it mimics proper datetime string
                                        var targetStr = "<?php echo $targetDate; ?>";
                                        // If targetStr is just YYYY-MM-DD, append time if needed or handle parsing

                                        // Safe parsing for "YYYY-MM-DD HH:MM:SS" or "YYYY-MM-DD"
                                        var targetDate = new Date(targetStr.replace(/-/g, "/")); // Replace - with / for better cross-browser compatibility

                                        function updateTimer() {
                                            var now = new Date();
                                            var diff = targetDate - now;

                                            if (diff <= 0) {
                                                document.getElementById("countdown-display").innerHTML = "¡El torneo ha comenzado!";
                                                return;
                                            }

                                            var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                            var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                            var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                                            document.getElementById("countdown-display").innerHTML =
                                                "Comienza en " + days + " días . " + hours + " horas . " + minutes + " minutos . " + seconds + " segundos";
                                        }

                                        setInterval(updateTimer, 1000);
                                        updateTimer();
                                    })();
                                </script>
                            <?php endif; ?>
                        </h1>

                    </div>
                </section>
            </div>
        </div>

    </div>


    <div id="sec1">
    </div>

    </div>


    <div id="loader">
        <i class="spinner"></i>
    </div>
    <script src="js/recarga.js"></script>
</body>

</html>