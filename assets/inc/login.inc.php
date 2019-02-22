<?php
//Clases
$funcionesNav = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();

if (isset($_POST["login"])) {
    $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
    $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');

    $usuario->set("email", $email);
    $usuario->set("password", $password);

    if (!$usuario->login()) {
        ?>
        <script>
            $(document).ready(function () {
                $("#errorLogin").html('<br/><div class="alert alert-warning" role="alert">Email o contraseña incorrecta.</div>');
                $('#login').modal("show");
            });
        </script>
        <?php
    } else {
        $funcionesNav->headerMove(CANONICAL);
    }
}
?>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myLogin" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-link">Iniciar Sesión</a>
            </div>
            <div class="modal-body">
                <p id="errorLogin"></p>
                <form id="login" method="post">
                    <div class="input-group">
                        <input class="form-control h40" type="email" placeholder="Correo electrónico" name="email"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-envelope"></i></span>
                    </div>
                    <br/>
                    <div class="input-group">
                        <input class="form-control h40" type="password" placeholder="Contraseña" name="password"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                    </div>
                    <br/>
                    <button type="submit" name="login" class="btn btn-default">Ingresar</button>
                    <br/><br/>
                    <div class="text-left">
                        <a href="#">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- End modal -->
<!-- REGISTRAR -->
<?php
if (isset($_POST["registrar"])) {
    if ($_POST["password"] == $_POST["password2"]) {
        $nombre = $funcionesNav->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
        $apellido = $funcionesNav->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
        $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
        $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
        $cod = substr(md5(uniqid(rand())), 0, 10);
        $fecha = getdate();
        $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

        $usuario->set("cod", $cod);
        $usuario->set("nombre", $nombre);
        $usuario->set("apellido", $apellido);
        $usuario->set("email", $email);
        $usuario->set("password", $password);
        $usuario->set("fecha", $fecha);
        $usuario->set("invitado", 0);
        $usuario->set("descuento", 0);
        $add = $usuario->add();
        if ($add == false) {
            ?>
            <script>
                $(document).ready(function () {
                    $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">El email ya está registrado.</div>');
                    $('#registrar').modal("show");
                });
            </script>
            <?php
        } else {
            $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
            $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
            $usuario->login();
            $funcionesNav->headerMove(URL."/sesion");
        }
    } else {
        ?>
        <script>
            $(document).ready(function () {
                $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">Las contraseñas no coinciden.</div>');
                $('#registrar').modal("show");
            });
        </script>
        <?php
    }
}
?>
<div class="modal fade" id="registrar" tabindex="-1" role="dialog" aria-labelledby="registrar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close-link">Registro</a>
            </div>
            <div class="modal-body">
                <p id="errorRegistro"></p>
                <form id="registro" method="post">
                    <div class="input-group">
                        <input class="form-control h40" type="text" placeholder="Nombre" name="nombre"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-user"></i></span>
                    </div>
                    <br/>
                    <div class="input-group">
                        <input class="form-control h40" type="text" placeholder="Apellido" name="apellido"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-user"></i></span>
                    </div>
                    <br/>
                    <div class="input-group">
                        <input class="form-control h40" type="email" placeholder="Email" name="email"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-envelope"></i></span>
                    </div>
                    <br/>
                    <div class="input-group">
                        <input class="form-control h40" type="password" placeholder="Contraseña" name="password"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                    </div>
                    <br/>
                    <div class="input-group">
                        <input class="form-control h40" type="password" placeholder="Confirmar Contraseña"
                               name="password2"
                               required/>
                        <span class="input-group-addon"> <i class="login_icon glyphicon glyphicon-lock"></i></span>
                    </div>
                    <br/>
                    <button type="submit" name="registrar" class="btn btn-default">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div><!-- End Register modal -->
