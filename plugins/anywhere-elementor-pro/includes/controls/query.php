<?php

namespace Aepro\Controls;

use Elementor\Control_Select2;

class Query extends Control_Select2
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_type()
    {
        return 'aep-query';
    }
}
