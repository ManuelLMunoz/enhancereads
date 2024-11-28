<?php

namespace Src\Controllers;

class LegalController extends Controller
{
    public function terms()
    {
        return $this->view("legal/terms-of-use");
    }

    public function privacy()
    {
        return $this->view("legal/privacy-policy");
    }
}
