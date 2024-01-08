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
    
    public function __construct(array $route, array $params, array $paramsProperties, array $callable)
    {
        $this->route = $route;
        $this->params = $params;
        $this->paramsProperties = $paramsProperties;
        $this->callable['type'] = $callable[0];
        $this->callable['class'] = $callable[1];
        $this->callable['method'] = $callable[2];
    }
    
    public function compareRoutes(array $browserUrl)
    {
        $routeOriginal = $this->route;
        $routeParamsOriginal = $this->params;
        $browserUrlWithoutParams = array_diff_key($browserUrl, $routeParamsOriginal);
        $browserUrlWithoutRoute = array_diff_key($browserUrl, $routeOriginal);
        if($browserUrlWithoutParams == $routeOriginal){
            if($this->validateVariable($browserUrlWithoutRoute)) {
                return TRUE;
            }
        }
    }
    
    public function getCallable()
    {
        return $this->callable;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    private function validateVariable(array $browserUrlParams)
    {
        $routeParamsOriginal = $this->params;
        $routeParamsPropertiesOriginal = $this->paramsProperties;
        if(array_keys($browserUrlParams) == array_keys($routeParamsOriginal))
        {
            return $this->checkFilterVariable($browserUrlParams);
        } else {
            $paramsWithout = array_diff_key($routeParamsOriginal, $browserUrlParams);
            foreach($paramsWithout as $key => $param) {
                if(isset($routeParamsPropertiesOriginal[$key]['?'])) {
                    unset($browserUrlParams[$key]);
                } else {
                    return FALSE;
                }
            }
            return $this->checkFilterVariable($browserUrlParams);
        }
    }
    
    private function checkFilterVariable(array $params)
    {
        $routeParamsPropertiesOriginal = $this->paramsProperties;
        foreach($params as $key => $param) {
            if(isset($routeParamsPropertiesOriginal[$key])){
                $patterns = $this->createFilterPatterns($key);
                if(!preg_match($patterns, $param)) {
                return FALSE;
                }
            }
        }
        return TRUE;
    }
    
    private function createFilterPatterns(int $param)
    {
        $patterns = '/^[" ",';
        $routeParamsPropertiesOriginal = $this->paramsProperties;   
        foreach($routeParamsPropertiesOriginal[$param] as $propetie => $value) {
            switch ($propetie) {
                case 'str':
                    $patterns_filter[]='a-z,A-z';
                    break;
                case 'int':
                    $patterns_filter[]='0-9';
                    break;
            }
        }
        $patterns .= implode(',', $patterns_filter).']+$/';
        return $patterns;
    }
        
    
}