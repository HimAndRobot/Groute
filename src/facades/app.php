<?php
namespace facades;

use core\kernel;

class app 
{
    public static function get($routeBrute, $callable)
    {
        $kernel = new kernel;
        call_user_func_array([$kernel ,'get'],[$routeBrute,$callable]);
    }
    public static function run()
    {
        $kernel = new kernel;
        call_user_func_array([$kernel ,'run'],[]);
    }
}