<?php

namespace OceanWT;

class Controller
{
     use Support\Traits\Macro;
    public $import;
    public function __construct()
    {
        $this->import = new Import();
    }
}
