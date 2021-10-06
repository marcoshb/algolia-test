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

class CourseModel extends \Database\Database {

    static protected $table_name = 'course';
    public $id;
    public $title;
    public $image;
    public $description;
    public $institute;
    public $link;
    public $created_date;

    public function _save() {
        if (!$this->id) {
            $Date = new \DateTime();
            $this->created_date = $Date->format("Y-d-m H:i:s");
        }
        parent::_save();
    }

    

}
