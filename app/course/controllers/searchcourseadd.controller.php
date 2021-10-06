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

use Search\AlgoliaController;

class SearchCourseAddController extends AlgoliaController {

    protected $index = 'courses';
    private $id;

    public function __construct($id) {

        $this->id = $id;
        parent::__construct();
    }

    function get_data() {

        foreach ($this->id as $course_id) {
            # Course Details
            $Course = new CourseModel();
            $Course->find_by_id($course_id);
            $course_details = (array) $Course;

            # Rename ID
            $course_details['objectID'] = $course_details['id'];

            # Convert date to unix
            $created_date = date_create($course_details['created_date']);
            $course_details['created_date'] = date_timestamp_get($created_date);

            # Exclude element no wanted
            //unset($course_details['description']);
            unset($course_details['id']);

            # Conver category to list
            $CourseCategory = new CourseCategoryModel();
            $row_category_list = $CourseCategory->get_all_category($Course->id);
            $category_list['category'] = $this->format_category($row_category_list);

            # Save data 
            $this->data[] = array_merge($course_details, $category_list);
        }
    }

    public function execute() {
        $this->container->saveObjects(
                $this->data
        );
    }

    function format_category(array $category_list) {
        foreach ($category_list as $category) {
            $list_category_search[] = $category["name"];
        }
        return $list_category_search;
    }

}
