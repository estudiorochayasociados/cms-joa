<?php namespace Clases;


class PublicFunction
{

    public function antihack_mysqli($str)
    {
        $con = new Conexion();
        $conexion = $con->con();
        $str = mysqli_real_escape_string($conexion, $str);
        return $str;
    }

    public function antihack($str)
    {
        $str = stripslashes($str);
        $str = strip_tags($str);
        $str = htmlentities($str);
        return $str;
    }

    public function headerMove($location)
    {
        echo "<script> document.location.href='" . $location . "';</script>";
    }

    public function send_email($asunto, $email, $mensaje)
    {

        $mail = new PHPMailer; //moved here
        var_dump($mail);

    }

    public function normalizar_link($string)
    {
        $string = str_replace("á", "a", $string);
        $string = str_replace("Á", "A", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("É", "E", $string);
        $string = str_replace("í", "i", $string);
        $string = str_replace("Í", "I", $string);
        $string = str_replace("ó", "o", $string);
        $string = str_replace("Ó", "O", $string);
        $string = str_replace("ú", "u", $string);
        $string = str_replace("Ú", "U", $string);
        $string = str_replace(" ", "-", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("ñ", "n", $string);
        $string = str_replace("Ñ", "N", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("?", "", $string);
        $string = str_replace("¿", "", $string);
        $string = str_replace("¡", "", $string);
        $string = str_replace("/", "", $string);
        $string = str_replace(",", "", $string);
        $string = str_replace(".", "", $string);
        $string = strtolower($string);
        //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
        //$string = str_replace("caracter - que - queremos - cambiar","caracter - por - el - cual - lo - vamos - a - cambiar",$string);
        return $string;

    }

    public function localidades()
    {
        $con = new Conexion();
        $palabra = ($_GET["elegido"]);
        $sql = "SELECT  distinct `_provincias`.`nombre`,`_localidades`.`nombre` FROM  `_localidades` , `_provincias` WHERE  `_localidades`.`provincia_id` =  `_provincias`.`id` AND `_provincias`.`nombre`  LIKE '%$palabra%' AND `_localidades`.`nombre` != '' ORDER BY `_localidades`.`nombre`";
        $notas = $con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($notas)) {
            echo strtoupper($row["nombre"]) . ";";
        }
    }

    public function provincias()
    {
        $con = new Conexion();
        $sql = "SELECT `nombre` FROM  `_provincias` ORDER BY nombre";
        $provincias = $con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($provincias)) {
            echo '<option value="' . $row['nombre'] . '">' . mb_strtoupper($row['nombre']) . '</option>';
        }
    }

    public function fileExists($url)
    {
        return (@fopen($url, "r") == true);
    }

    public function eliminar_get($url, $varname)
    {
        $parsedUrl = parse_url($url);
        $query = array();

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            unset($query[$varname]);
        }

        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $query = !empty($query) ? '?'. http_build_query($query) : '';

        return $parsedUrl['scheme']. '://'. $parsedUrl['host']. $path. $query;
    }
}
