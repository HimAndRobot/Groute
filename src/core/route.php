<?php
namespace core;

class route 
{
    private $route;
    private $params;
    private $paramsProperties;
    private $callable;
    private $group;
    private $middleare;
    
    public function __construct(array $route, array $params, array $paramsPropeties)
    {
        $this->route = $route;
        $this->params = $params;
        $this->paramsProperties = $paramsPropeties;
    }
    
    
}