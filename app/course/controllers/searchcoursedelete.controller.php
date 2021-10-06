<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * SearchCourseAddController
 *
 * @author marcos
 */

namespace Course;

use Search\AlgoliaController;

class SearchCourseDeleteController extends AlgoliaController {

    protected $index = 'courses';
    private $id;

    public function __construct($id) {

        $this->id = $id;
        parent::__construct();
    }

    function get_data() {
        $this->data = $this->id;
    }

    public function execute() {
      
        $this->container->deleteObjects(
                $this->data
        );
    }

}
