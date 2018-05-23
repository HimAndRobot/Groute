<?php
namespace app\http\controller;
class geanController extends controller  {
    
    
    function showproducts($request,$args){
    	echo $args['id'];
   }
    
     function addproduct($request,$args){
     	var_dump($request);
       
    }
     function put(){
        echo 'put';
       
    }    
    
}