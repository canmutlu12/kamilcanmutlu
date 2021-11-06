<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'BriefAPI';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['product']="BriefAPI/getProduct";
$route['product/detail/(.*)']="BriefAPI/getProduct/$1";