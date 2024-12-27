<?php

namespace Src\Controllers;

class PageController extends Controller
{
    public function index()
    {
        return $this->view("index");
    }

    public function terms()
    {
        return $this->view("legal/terms-of-use");
    }

    public function privacy()
    {
        return $this->view("legal/privacy-policy");
    }
}
