<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of School
 *
 * @author marcos
 */

namespace Course;

use Algolia\AlgoliaSearch\SearchClient;

class SearchCourseController {

    function add($id = null) {
        if (!is_array($id)) {
            $id = explode(",", $id);
        }
        if (is_array($id)) {
            new SearchCourseAddController($id);
        } else {
            throw new \Exception('Error in Algolia Search course add: ID it is not an array');
        }
    }

    function delete($id = null) {
        echo $id;
        if (!is_array($id)) {
            $id = explode(",", $id);
        }
        if (is_array($id)) {
            echo $id;
            new SearchCourseDeleteController($id);
        } else {
            throw new \Exception('Error in Algolia Search course delete: ID it is not an array');
        }
    }

}
