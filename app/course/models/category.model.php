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

class CategoryModel extends \Database\Database
{

    static protected $table_name = 'category';
    public $id;
    public $name;
    public $urlseo;
    

    public function _delete_by_school($school_id)
    {
        $db = self::$connection;

        if ($school_id)
        {
            $this->school_id = $school_id;
            // Deleting Courses Info and Price

            $Courses = $this->find_all_by_property("school_id");

            foreach ($Courses as $Course)
            {
                $CourseInfo = new \CourseInfoClass\CourseInfo();
                $CourseInfo->_delete_by_course($Course->id);

                $CoursePrice = new \CoursePriceClass\CoursePrice();
                $CoursePrice->_delete_by_course($Course->id);
            }
            // Deleting records from DB
            $query = "DELETE FROM 
                     " . static::$table_name . "
                WHERE 
                    school_id = {$school_id}";
            $resutl = $db->prepare($query);
            $resutl->execute();

            $info["status"] = "succed";
            $info["message"] = "course deleted";
        }
        else
        {
            throw new \Exception('Error in School Deliting: No ID Loaded');
        }
        return $info;
    }

}
