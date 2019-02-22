<?php

namespace Clases;

class DBBackup
{
    private $link = '';

    function __construct()
    {
        $conexion = new \Clases\Conexion();
        $datos = $conexion->backup();
        $this->hostname = $datos["host"];
        $this->username = $datos["user"];
        $this->password = $datos["pass"];
        $this->database = $datos["db"];
        $this->initalizeDB();

    }

    /* Function used to Initialize the MySQL DB */
    private function initalizeDB()
    {
        $conexion = new \Clases\Conexion();
        $this->link = $conexion->con();
    }

    /* Function is used to Backup you Database */
    public function backupDatabase($tables = '*', $backupDirectory = '')
    {
        if ($tables == '*') {
            $tables = array();
            $result = mysqli_query($this->link, "SHOW TABLES");
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        $sql = 'SET FOREIGN_KEY_CHECKS = 0;' . "\n" . 'CREATE DATABASE IF NOT EXISTS `' . $this->database . "`;\n";
        $sql .= 'USE `' . $this->database . '`;';
        foreach ($tables as $table) {
            $tableDetails = mysqli_query($this->link, "SELECT * FROM " . $table);
            $totalCols = mysqli_num_fields($tableDetails);
            $sql .= "\n\nDROP TABLE IF EXISTS `" . $table . "`;\n";
            $result1 = mysqli_fetch_row(mysqli_query($this->link, 'SHOW CREATE TABLE ' . $table));
            $sql .= $result1[1] . ";\n\n";

            while ($row = mysqli_fetch_row($tableDetails)) {
                $sql .= 'INSERT INTO `' . $table . '` VALUES(';
                for ($j = 0; $j < $totalCols; $j++) {
                    $row[$j] = preg_replace("/\n/", "\\n", addslashes($row[$j]));
                    if (isset($row[$j])) {
                        $sql .= '"' . $row[$j] . '"';
                    } else {
                        $sql .= '""';
                    }

                    if ($j < ($totalCols - 1)) {
                        $sql .= ', ';
                    }
                }
                $sql .= "); \n";
            }
        }
        $sql .= 'SET FOREIGN_KEY_CHECKS = 1;';
        $backupDirectory = ($backupDirectory == '') ? $this->backupDirectory : $backupDirectory;
        if ($this->logDatabase($sql, $backupDirectory)) {
            echo '<h4>Exported Database</h4>';
            exit;
        } else {
            echo '<h2>Error in Exporting Database<h2>';
            exit;
        }

    }

    private function logDatabase($sql, $backupDirectory = '')
    {
        if (!$sql) {
            return false;
        }

        if (!file_exists($backupDirectory)) {
            if (mkdir($backupDirectory)) {
                $filename = 'sql-' . date('d-m-Y');
                $fileHandler = fopen($backupDirectory . '/' . $filename . '.sql', 'w+');
                fwrite($fileHandler, $sql);
                fclose($fileHandler);
                return true;
            }
        } else {
            $filename = 'sql-' . date('d-m-Y');
            $fileHandler = fopen($backupDirectory . '/' . $filename . '.sql', 'w+');
            fwrite($fileHandler, $sql);
            fclose($fileHandler);
            return true;
        }
        return false;
    }
}