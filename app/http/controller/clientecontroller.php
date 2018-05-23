<?php
use vendor\facades\db;
use vendor\facades\pag;
namespace app\http\controllers;
class clienteController {
    
    function index($request,$args,$reponse){
        print_r ($request);
    }
    
    function post($request,$args,$response){
       print_r($this->routes);
    
    }
    

}