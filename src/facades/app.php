<?php
namespace facades;

use core\kernel;

class app 
{
    public static function get($routeBrute, $callable)
    {
        $kernel = new kernel;
        $kernel->get($routeBrute, $callable);
    }
    
    public static function run()
    {
        $kernel = new kernel;
        $kernel->run();
    }
    
    public static function getContainer()
    {
        $kernel = new kernel;
        $kernel->loadContainer();
    }
    
    public static function setContainer($container)
    {
        $kernel = new kernel;
        $kernel->setContainer($container);
    }
}