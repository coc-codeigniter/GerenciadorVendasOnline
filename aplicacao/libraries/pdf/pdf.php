<?php

require_once 'dompdf/dompdf_config.inc.php';


Class PDF extends DOMPDF{
    
    function __construct() {
        parent::__construct();
    }
    
}