<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pagination;

/**
 * Description of pagination
 *
 * @author marco
 */
class pagination
{

    public $item_per_page = 25;
    public $pointer = 0;
    public $navigation = null;
    private $url;
    private $actual_page = 1;
    private $row_num;
    private $total_pages;

    function __construct(
            $actual_page,
            $url = null,
            $item_num = null)
    {
        # Set item_per_page
        $item_num = intval($item_num);
        if ($item_num)
        {
            $this->item_per_page = $item_num;
        }

        #Set actual_page
        $actual_page = intval($actual_page);
        if ($actual_page)
        {
            $this->actual_page = $actual_page;
            if ($this->actual_page > 1)
            {
                $this->set_start();
            }
        }

        # Set URL
        $this->set_url($url);
    }

    function set_total($row_num)
    {
        $row_num = intval($row_num);
        $this->row_num = $row_num;
        $this->total_pages = ceil($this->row_num / $this->item_per_page);
        $this->set_navigation();
    }

    function set_start()
    {
        $this->pointer = ($this->actual_page - 1) * $this->item_per_page;
    }

    function set_url($url)
    {
        if (!$url)
        {
            $url = $_SERVER['HTTP_REFERER'];
            $url_components = parse_url($url);
            parse_str($url_components['query'], $params);
            unset($params["actual_page"]);
            $this->url = $url_components['scheme'] . "://" . $url_components['host'] . "?";
            $this->url .= http_build_query($params);
        }
        else
        {
            $this->url = $url;
        }
        $this->url .= "&actual_page=";
    }

    function get_previou_page()
    {
        return $this->actual_page - 1;
    }

    function get_next_page()
    {
        return $this->actual_page + 1;
    }

    function set_navigation()
    {
        $maxpags = 10;
        $minimo = $maxpags ? max(1, $this->actual_page - ceil($maxpags / 2)) : 1;
        $maximo = $maxpags ? min($this->total_pages, $this->actual_page + floor($maxpags / 2)) : $this->total_pages;

        $texto = '<div class="col"><nav aria-label="Page navigation example"><ul class="pagination">';
        if ($this->actual_page > 1)
        {
            $texto .= "<li class='page-item'>";
            $texto .= "<a href='#' onclick='load_list({$this->get_previou_page()}); return false;' class='page-link' >&laquo;</a> ";
            $texto .= "</li>";
        }
        else
        {
            $texto .= "<li class='page-item disabled'>";
            $texto .= '<a href="#" tabindex="-1" class="page-link">&laquo;</a>';
            $texto .= "</li>";
        }

        if ($minimo != 1)
        {
            $texto .= '<li class="page-item disabled">
                        <a href="#" tabindex="-1" class="page-link">...</a>
                    </li>';
        }

        for ($i = $minimo; $i < $this->actual_page; $i++)
        {
            $texto .= "<li class='page-item'>";
            $texto .= "<a  href='#' onclick='load_list({$i}); return false;' class='page-link'>$i</a> ";
            $texto .= "</li>";
        }

        $texto .= "<li class='page-item disabled'>";
        $texto .= "<a class='page-link' href='#' tabindex='-1'>$this->actual_page</a> ";
        $texto .= "</li>";

        for ($i = $this->actual_page + 1; $i <= $maximo; $i++)
        {
            $texto .= "<li class='page-item'>";
            $texto .= "<a href='#' onclick='load_list({$i}); return false;'  class='page-link'>$i</a> ";
            $texto .= "<li class='page-item'>";
        }

        if ($maximo != $this->total_pages)
        {
            $texto .= '<li class="page-item disabled">
                        <a href="#" tabindex="-1" class="page-link">...</a>
                    </li>';
        }
        if ($this->actual_page < $this->total_pages)
        {
            $texto .= "<li class='page-item'>";
            $texto .= "<a href='#' onclick='load_list({$this->get_next_page()}); return false;' class='page-link'>&raquo;</a>";
            $texto .= "</li>";
        }
        else
        {
            $texto .= '<li class="page-item disabled">';
            $texto .= '<a href="#" tabindex="-1" class="page-link">&raquo;</a>';
            $texto .= '</li>';
        }
        $texto .= '</div></ul></nav>';

        $this->navigation = $texto;
        $_SESSION["pagination_nav"] = $this->navigation;
    }

}
