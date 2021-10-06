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

namespace Search;

Use Algolia;

abstract class AlgoliaController {

    protected $client;
    protected $index;
    protected $container;

    function __construct() {
        $this->connect();
        $this->get_data();
        $this->set_index();
        $this->execute();
    }

    function connect() {

        $this->client = Algolia\AlgoliaSearch\SearchClient::create(
                        'Z74QGMKVYK',
                        'ba648815fce0b4dfe2bfab2757c17d55'
        );
    }

    function set_index() {
        $this->container = $this->client->initIndex($this->index);
    }

    abstract function get_data();

    abstract function execute();
}
