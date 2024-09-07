<?php
    class Conexion {
        public static function Conectar() {
            if (!defined('servidor')) {
                define('servidor', 'localhost');
            }
            if (!defined('nombre_bd')) {
                define('nombre_bd', 'u864743456_sedi_cbtf');
            }
            if (!defined('usuario')) {
                define('usuario', 'u864743456_root');
            }
            if (!defined('password')) {
                define('password', 'Cbtf1974');
            }

            $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

            try {
                $conexion = new PDO("mysql:host=" . servidor . ";dbname=" . nombre_bd, usuario, password, $opciones);
                return $conexion;
            } catch (Exception $e) {
                die("El error de Conexión es :" . $e->getMessage());
            }
        }
    }
?>