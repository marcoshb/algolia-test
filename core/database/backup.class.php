<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of backup
 *
 * @author marcos
 */
class Backup
{

    //put your code here
    private static $db;
    private $date;
    private $database;
    private $tables = FALSE;
    private $dir;

    public function __construct(
        Database\Database $db,
        $dir = NULL,
        $tables = NULL
    )
    {
        self::$db = $db->get_connection();

        // Set database name
        $this->set_db_name();

        //Set Dir
        $this->dir = $dir;

        // Set tables to be exported
        $this->set_tables($tables);
    }

    function set_db_name()
    {
        $db = self::$db;

        $query = "SELECT DATABASE() `database`;";
        $resutl = $db->prepare($query);
        $resutl->execute();
        $resutl->setFetchMode($db::FETCH_ASSOC);
        $data = $resutl->fetch();

        if ($data["database"])
        {
            $this->database = $data["database"];
        }
        else
        {
            die("ERROR: Database name could not be loaded");
        }
    }

    private function set_tables($tables)
    {
        $db = self::$db;

        if (!$tables)
        {
            $query = "SHOW TABLES FROM {$this->database};";
            $resutl = $db->prepare($query);
            $resutl->execute();
            $data = $resutl->fetchALL(PDO::FETCH_NUM);
            foreach ($data as $fila)
            {
                $this->tables[] = $fila[0];
            }
        }
        else if ($tables && is_array($tables))
        {
            $this->tables = $tables();
        }
        else
        {
            die("ERROR: tables must be an array value");
        }
    }

    private function compose_data()
    {
        $db = self::$db;

        $mysql_server = $db->getAttribute(PDO::ATTR_SERVER_VERSION);
        $tables = implode(" ", $this->tables);
        $dump = <<<EOT
# +===================================================================
# | {$_SERVER['HTTP_HOST']} ! 2.0
# |
# | Date: {$this->date}
# | Server: {$_SERVER['HTTP_HOST']}
# | MySQL Version: {$mysql_server}
# | Data base: {$this->database}
# | Tables: { $tables}
# |
# +-------------------------------------------------------------------

EOT;
        foreach ($this->tables as $table)
        {

            $drop_table_query = "";
            $create_table_query = "";
            $insert_into_query = "";

            /* Se halla el query que será capaz vaciar la tabla. */
            if ($drop)
            {
                $drop_table_query = "DROP TABLE IF EXISTS `$table`;";
            }
            else
            {
                $drop_table_query = "# No especificado.";
            }

            /* Se halla el query que será capaz de recrear la estructura de la tabla. */
            $create_table_query = "";
            $query = "SHOW CREATE TABLE {$table}";
            $resutl = $db->prepare($query);
            $resutl->execute();
            $data = $resutl->fetchALL(PDO::FETCH_NUM);
            foreach ($data as $fila)
            {
                $create_table_query = $fila[1] . ";";
            }

            /* Se halla el query que será capaz de insertar los datos. */
            $insert_into_query = "";
            $query = "SELECT * FROM {$table};";
            $resutl = $db->prepare($query);
            $resutl->execute();
            $data = $resutl->fetchALL(PDO::FETCH_NUM);
            foreach ($data as $fila)
            {
                $columnas = array_keys($fila);
                foreach ($columnas as $columna)
                {
                    if (gettype($fila[$columna]) == "NULL")
                    {
                        $values[] = "NULL";
                    }
                    else
                    {


                        $values[] = "'" . filter_var($fila[$columna], FILTER_SANITIZE_STRING) . "'";
                    }
                }
                $insert_into_query .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                unset($values);
            }
            $dump .= <<<EOT
# | Vaciado de tabla '$table'
# +------------------------------------->
$drop_table_query
# | Estructura de la tabla '$table'
# +------------------------------------->
$create_table_query
# | Carga de datos de la tabla '$table'
# +------------------------------------->
$insert_into_query
EOT;
        }

        return $dump;
    }

    private function set_file_dir()
    {
        if ($this->dir)
        {
            $dir = $this->dir;
        }
        else
        {
            $dir = getcwd();
        }
        $dir .= "/" . date("Y") . "/" . strtolower(date("M")) . "";
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, TRUE);
        }
        return $dir . "/";
    }

    private function save_file($dump)
    {
        $compresion = "gz";

        $FileName = $this->database . "_";
        $FileName .= date("Y-m-d_H.i.s");
        $Dir = $this->set_file_dir();
        if (!headers_sent())
        {
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Content-Transfer-Encoding: binary");
            switch ($compresion)
            {
                case "gz":
                    /* header("Content-Disposition: attachment; filename=$nombre.gz");
                      header("Content-type: application/x-gzip"); */
                    $FileName .= ".gz";
                    $gzdata = gzencode($dump, 9);
                    $fp = fopen($Dir . $FileName, "w");
                    fwrite($fp, $gzdata);
                    fclose($fp);
                    break;
                case "bz2":
                    header("Content-Disposition: attachment; filename=$nombre.bz2");
                    header("Content-type: application/x-bzip2");
                    echo bzcompress($dump, 9);
                    break;
                default:
                    header("Content-Disposition: attachment; filename=$nombre");
                    header("Content-type: application/force-download");
                    echo $dump;
            }
            return $Dir . $FileName;
        }
        else
        {
            echo "<b>ATENCION: Probablemente ha ocurrido un error</b><br />\n<pre>\n$dump\n</pre>";
        }
    }

    public function run()
    {
        $date = new DateTime("now");
        $this->date = $date->format("Y-m-d H:i:s");
        $data = $this->compose_data();
        $file_path = $this->save_file($data);
        return $file_path;
    }

}
