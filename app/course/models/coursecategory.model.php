<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of schools
 *
 * @author marcos
 */

namespace Course;

class CourseCategoryModel extends \Database\Database {

    static protected $table_name = 'course_category_relation';
    public $id;
    public $course_id;
    public $category_id;

    public function get_all_category($course_id) {
        $db = self::$connection;

        if ($course_id) {

            $query = "SELECT * FROM 
                     " . static::$table_name . " ccr
                         INNER JOIN
                          category c 
                          ON find_in_set(c.id, ccr.category_id)
                WHERE 
                    course_id = :course_id";
            $resutl = $db->prepare($query);

            $resutl->bindValue(":course_id", $course_id, $db::PARAM_INT);

            $resutl->execute();
            $data = $resutl->fetchALL($db::FETCH_ASSOC);
        } else {
            throw new \Exception('Error: No ID Loaded');
        }
        return $data;
    }

    public function _delete_by_course($course_id) {
        $db = self::$connection;

        if ($course_id) {

            $query = "DELETE FROM 
                     " . static::$table_name . "
                WHERE 
                    course_id = {$course_id}";
            $resutl = $db->prepare($query);
            $resutl->execute();
        } else {
            throw new \Exception('Error in course deliting: No ID Loaded');
        }
    }

}
