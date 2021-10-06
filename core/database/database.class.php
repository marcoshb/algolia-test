<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Database;

use Pagination\pagination;
use PDO;

class Database
{

    static protected $connection;
    static protected $table_name;

    function __construct()
    {
        try
        {
            if (!self::$connection)
            {
                $this->connect();
            }
        }
        catch (PDOException $e)
        {
            $e->getMessage();
            echo $e . "<br><br>";
        }
    }

    protected function connect()
    {
        require CONFIG . '/database_credentials.php';
        $db = new PDO("mysql:host={$dbHost}; dbname={$dbName};charset=utf8", $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->exec("set names utf8");

        self::$connection = $db;
    }

    public function get_connection()
    {
        return self::$connection;
    }

    protected function set_database($db_name_new)
    {
        if ($db_name_new)
        {
            require CONFIG . '/database_credentials.php';

            $db = new PDO("mysql:host={$dbHost}; dbname={$db_name_new};charset=utf8", $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->exec("set names utf8");

            self::$connection = $db;
        }
    }

    public function set($args)
    {
        $attr = self::get_attr_array();
        foreach ($attr as $key => $value)
        {
            if (isset($args[$key]))
            {
                $this->$key = $args[$key];
            }
        }
    }

    private function get_attr_array()
    {
        $allAttr = get_object_vars($this);
        foreach ($allAttr as $key => $value)
        {
            if ($key != 'db')
            {
                $attr[$key] = $value;
            }
        }
        return $attr;
    }

    /* operations */

    public function _save()
    {
        if ($this->id)
        {
            $this->_update();
        }
        else
        {
            $this->_create();
        }
    }

    private function _create()
    {
        $db = self::$connection;

        $attr = $this->get_attr_array();
        unset($attr["id"]);

        $keys = array_keys($attr);
        $values = array_values($attr);

        for ($i = 0; $i < count($keys); $i++)
        {
            $bind_data[] = "?";
        }

        $query = "INSERT INTO 
                    " . static::$table_name . " (
                    " . join(',', array_keys($attr)) . "
                    )
                VALUES(
                    " . join(",", array_values($bind_data)) . "
                    )";

        $resutl = $db->prepare($query);
        $resutl->execute($values);
        if ($resutl)
        {
            $this->id = $db->lastInsertId();
        }
        return $resutl;
    }

    private function _update()
    {
        $db = self::$connection;

        $attr = $this->get_attr_array();
        unset($attr["id"]);

        $keys = array_keys($attr);
        $values = array_values($attr);

        $keys_number = count($keys);

        foreach ($keys as $value)
        {
            $attr_bind[] = "{$value} = ?";
        }
        $query = "UPDATE
                    " . static::$table_name . " 
                SET 
                    " . join(',', $attr_bind) . "
                WHERE
                    id = {$this->id}
                ";
        $resutl = $db->prepare($query);
        $resutl->execute($values);
    }

    public function _delete()
    {
        $db = self::$connection;

        $query = "DELETE FROM
                    " . static::$table_name . " 
                WHERE
                    id = {$this->id}
                ";
        $resutl = $db->prepare($query);
        $resutl->execute();
    }

    /* Displaying */

    public function find_by_id($id)
    {
        $db = self::$connection;

        $query = "SELECT 
                    * 
                FROM
                    " . static::$table_name . "
                WHERE 
                    id = :id ";
        $resutl = $db->prepare($query);
        $resutl->bindValue(':id', $id);
        $resutl->execute();
        $resutl->setFetchMode($db::FETCH_ASSOC);
        $object = $resutl->fetch();
        $this->set($object);
    }

    public function find_by_property($property_name)
    {
        //$this->set_connection();
        $db = self::$connection;

        $query = "SELECT 
                    * 
                FROM
                    " . static::$table_name . " " .
                "WHERE 
                    {$property_name} = :property ";
        $resutl = $db->prepare($query);
        $resutl->bindValue(':property', $this->$property_name);
        $resutl->execute();
        $resutl->setFetchMode($db::FETCH_ASSOC);
        $object = $resutl->fetch();
        $this->set($object);
    }

    public function find_all(pagination $pagination = null)
    {
        $db = self::$connection;

        $query = "SELECT 
                    * 
                FROM
                    " . static::$table_name . " ";

        /* Pagination */
        unset($_SESSION["pagination_nav"]);
        if ($pagination)
        {
            $resutl = $db->prepare($query);
            $resutl->execute();

            $pagination->set_total($resutl->rowCount());
            $_SESSION["pagination_nav"] = $pagination->navigation;
            $query .= " LIMIT {$pagination->pointer},{$pagination->item_per_page} ";
        }
        /* END: Pagination */

        $resutl = $db->prepare($query);
        $resutl->execute();

        $resutl->setFetchMode($db::FETCH_ASSOC);
        $list = $resutl->fetchALL();
        foreach ($list as $entry)
        {
            $object = new static;
            $object->set($entry);
            $array[] = $object;
        }
        return $array;
    }

    public function find_all_by_property(
            $property_name,
            $order = null,
            $limit = null)
    {
        $db = self::$connection;

        $query = "SELECT 
                    * 
                FROM
                    " . static::$table_name . " " .
                "WHERE 
                     {$property_name} = :property ";
        if ($order)
        {
            $query .= "ORDER BY
                {$order}";
        }

        $resutl = $db->prepare($query);
        $resutl->bindValue(':property', $this->$property_name);
        $resutl->execute();
        //$resutl->debugDumpParams();
        $resutl->setFetchMode($db::FETCH_ASSOC);
        $list = $resutl->fetchALL();
        foreach ($list as $entry)
        {
            $object = new static;
            $object->set($entry);
            $array[] = $object;
        }
        return $array;
    }

    public function count_row(
            $property_name = null,
            $order = null,
            $limit = null)
    {
        $db = self::$connection;

        $query = "SELECT 
                    * 
                FROM
                    " . static::$table_name . " ";
        if ($property_name)
        {
            $query .= "WHERE 
                     {$property_name} = :property ";
        }

        if ($order)
        {
            $query .= "ORDER BY
                {$order}";
        }

        $resutl = $db->prepare($query);
        if ($property_name)
        {
            $resutl->bindValue(':property', $this->$property_name);
        }
        $resutl->execute();
        //$resutl->debugDumpParams();

        return $resutl->rowCount();
    }

}

?>
