<?php

namespace Clases;

class Usuarios
{

//Atributos
    public $id;
    public $cod;
    public $nombre;
    public $apellido;
    public $doc;
    public $email;
    public $password;
    public $direccion;
    public $postal;
    public $localidad;
    public $provincia;
    public $pais;
    public $telefono;
    public $celular;
    public $invitado;
    public $descuento;
    public $fecha;
    private $con;

//Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $validar = $this->validate();
        if (!is_array($validar)) {
            $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `password`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `invitado`, `descuento`, `fecha`) VALUES ('{$this->cod}', '{$this->nombre}', '{$this->apellido}', '{$this->doc}', '{$this->email}', '{$this->password}', '{$this->direccion}', '{$this->postal}', '{$this->localidad}', '{$this->provincia}', '{$this->pais}', '{$this->telefono}', '{$this->celular}', '{$this->invitado}', '{$this->descuento}', '{$this->fecha}')";
            $this->con->sql($sql);
            $r = 1;
        } else {
            $r = 0;
        }
        return $r;
    }

    public function edit()
    {
        $validar = $this->validate();
        $usuario = $this->view();
        $sql = "UPDATE `usuarios` SET `nombre` = '{$this->nombre}', `apellido` = '{$this->apellido}', `doc` = '{$this->doc}', `email` = '{$this->email}', `password` = '{$this->password}', `direccion` = '{$this->direccion}', `postal` = '{$this->postal}', `localidad` = '{$this->localidad}', `provincia` = '{$this->provincia}', `pais` = '{$this->pais}', `telefono` = '{$this->telefono}', `celular` = '{$this->celular}', `invitado` = '{$this->invitado}', `descuento` = '{$this->descuento}', `fecha` = '{$this->fecha}'WHERE `cod`='{$this->cod}'";

        if (is_array($validar)) {
            if ($validar["email"] == $usuario["email"]) {
                $query = $this->con->sql($sql);
                return $query;
            } else {
                echo "<div class='col-md-12'><div class='alert alert-danger'>Este correo ya existe como usuario.</div></div>";
            }
        } else {
            $query = $this->con->sql($sql);
            return $query;
        }
    }

    public function invitado_sesion()
    {
        $_SESSION["usuarios"] = array(
            'cod' => $this->cod,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'doc' => $this->doc,
            'email' => $this->email,
            'password' => $this->password,
            'direccion' => $this->direccion,
            'postal' => $this->postal,
            'localidad' => $this->localidad,
            'provincia' => $this->provincia,
            'pais' => $this->pais,
            'telefono' => $this->telefono,
            'celular' => $this->celular,
            'invitado' => $this->invitado,
            'descuento' => $this->descuento,
            'fecha' => $this->fecha
        );

        $sql = "INSERT INTO `usuarios` (`cod`, `nombre`, `apellido`, `doc`, `email`, `password`, `direccion`, `postal`, `localidad`, `provincia`, `pais`, `telefono`, `celular`, `invitado`, `descuento`, `fecha`) VALUES ('{$this->cod}', '{$this->nombre}', '{$this->apellido}', '{$this->doc}', '{$this->email}', '{$this->password}', '{$this->direccion}', '{$this->postal}', '{$this->localidad}', '{$this->provincia}', '{$this->pais}', '{$this->telefono}', '{$this->celular}',1, '{$this->descuento}', '{$this->fecha}')";
        $this->con->sql($sql);
    }


    public function delete()
    {
        $sql = "DELETE FROM `usuarios`WHERE `cod`= '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function login()
    {
        $sql = "SELECT * FROM `usuarios` WHERE `email` = '{$this->email}' AND `password`= '{$this->password}'";
        $usuarios = $this->con->sqlReturn($sql);
        $contar = mysqli_num_rows($usuarios);
        $row = mysqli_fetch_assoc($usuarios);
        if ($contar == 1) {
            $_SESSION["usuarios"] = $row;
        }
        return $contar;
    }

    public function logout()
    {
        $funciones = new PublicFunction();
        unset($_SESSION["usuarios"]);
        $funciones->headerMove(URL);
    }

    public function view()
    {
        $sql = "SELECT * FROM `usuarios`WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $usuario = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($usuario);
        return $row;
    }

    public function view_sesion()
    {
        if (!isset($_SESSION["usuarios"])) {
            $_SESSION["usuarios"] = array();
            return $_SESSION["usuarios"];
        } else {
            return $_SESSION["usuarios"];
        }
    }

    public function validate()
    {
        $sql = "SELECT * FROM `usuarios`WHERE email = '{$this->email}'";
        $usuario = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($usuario);
        return $row;
    }

    public function list($filter)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql = "SELECT * FROM `usuarios` $filterSql  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }

}
