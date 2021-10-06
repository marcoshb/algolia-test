<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of router
 *
 * @author marcos
 */

namespace Core;

use Core\Security;

class Router {

    private $section;
    private $action;
    private $controller;

    public function __construct() {

        $Security = new Security();

        $this->section = $Security->filterInput("get", "section", "string");
        if (!$this->section) {
            $this->section = $Security->filterInput("post", "section", "string");
        }

        $this->action = $Security->filterInput("get", "action", "string");
        if (!$this->action) {
            $this->action = $Security->filterInput("post", "action", "string");
        }

        $this->load();
        $this->go();
    }

    function load() {

        if ($this->section == "course") {
            $this->controller = new \Course\CourseController();
        }
        
        
        if ($this->section == "main") {
            $this->controller = new \App\Main\MainController();
        }
    }

    function go() {
        
        if (method_exists($this->controller, $this->action)) {
            $action = (string)$this->action;
            $this->controller->$action();
        } else {

            unset($this->controller);

            $this->controller = new \Course\CourseController();
            $this->controller->listing();
        }
    }

}
