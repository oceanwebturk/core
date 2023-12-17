<?php

namespace OceanWT;

class Controller
{
    public $import;
    public function __construct()
    {
        $this->import = new Import();
    }
}
