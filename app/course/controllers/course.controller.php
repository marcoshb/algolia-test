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

class CourseController {

    function save($args = null) {
        $Security = new \Core\Security();
        $args["id"] = $Security->filterInput("post", "course_id", 'int');
        $args["title"] = $Security->filterInput("post", "title", 'string');
        $args["description"] = $Security->filterInput("post", "description", 'html');
        $args["institute"] = $Security->filterInput("post", "institute", 'string');
        $args["link"] = $Security->filterInput("post", "link", 'string');
        $args["image"] = $Security->filterInput("post", "image", 'string');

        $Course = new CourseModel();
        if ($args["id"]) {
            $Course->find_by_id($args["id"]);
        }
        $Course->set($args);
        $Course->_save();

        if ($Course->id) {
            $categories = $Security->filterInputArray("post", "categories", 'string');

            $CourseCategory = new CourseCategoryModel();
            $CourseCategory->_delete_by_course($Course->id);

            foreach ($categories as $category) {
                $CourseCategory = new CourseCategoryModel();
                $CourseCategory->course_id = $Course->id;
                $CourseCategory->category_id = $category;
                $CourseCategory->_save();
            }
        }

        if ($Course->id) {
            $Search = new SearchCourseController();
            $Search->add($Course->id);
        }


        echo $Course->id;
    }

    function delete($args = null) {
        $Security = new \Core\Security();
        $id = $Security->filterInput("get", "course_id", 'int');
        if ($id) {
            $Course = new CourseModel();
            $Course->find_by_id($id);

            if ($Course->id) {
                $Search = new SearchCourseController();
                $Search->delete($Course->id);

                $CourseCategory = new CourseCategoryModel();
                $CourseCategory->_delete_by_course($Course->id);

                $Course->id = $id;
                $Course->_delete();
            }
        }
        echo "<script>window.location.replace('index.php?section=course&action=listing');</script>";
    }

    function form() {
        $Security = new \Core\Security();
        $course_id = $Security->filterInput("get", "course_id", "int");

        if ($course_id) {
            $Course = new CourseModel();
            $Course->find_by_id($course_id);
        }

        include_once __DIR__ . '/../views/course-form.php';
    }

    function listing() {
        $Security = new \Core\Security();
        include_once __DIR__ . '/../views/course-list.php';
    }

    function listing_data() {

        $Security = new \Core\Security();
        $page = $Security->filterInput("get", "page", "int");

        $Pagination = new \Pagination\pagination($page, null, 10);

        $Course = new \Course\CourseModel();
        $list = $Course->find_all($Pagination);

        include_once __DIR__ . '/../views/course-list-data.php';
    }

    function updateAlgolia() {
        /* $Serach = new SearchController();
          $Serach->action(New Delete($data))
          $Serach->delete("courses"); */
    }

}
