<?php

/**
 * App
 *
 * @author      Gean pedro da silva
 * @copyright   2017 Gean Pedro
 * @link        geanpn@gmail.com
 * @day         05/01/2018
 *
 * MIT LICENSE
 */
namespace vendor\web;

class route
{
    private $type = array();
    private $rota = array();
    private $rota_not_param = array();
    private $params = array();
    private $teste = array();
    private $array_route = '';
    private $paramsPropety = array();
    private $params_real_op = array();
    private $route_real = '';
    private $control = array();
    private $group = '';
    private $middleware = '';
    
    
    function __construct($rota,$params,$type,$group,$group_route)
    {
        $this->rota = $group_route.$rota;
        $this->teste = $params;
        $this->type = $type;
        $this->urlPrepare($rota);
        $this->getRealControl();
        $this->group = $group;
        return $this;
                
    }
    function urlPrepare($rota){
        $urlGross = $this->urlExplode($rota);
        $rota = $this->urlSeparate($urlGross);
        $this->rota = $rota[0];
        $this->params = $rota[1];
        $this->paramsPropety = $rota[2];
    }
    function urlSeparate($urlGross){
        $rota = array();
        $paramsGross = array();
        foreach ($urlGross as $key => $value) {
            if (strpos($value,'{') === false) {
                $rota[$key] = $value;
            }else {
                $param = $this->paramPrepare($value);
                $paramsGross[$key] = $param[0];
                $paramsPropety[$key] = $param[1];
            }
        }
        return array($rota,$paramsGross,$paramsPropety);
    }
    function paramPrepare($param)
    {
        $param = substr($param, 1,-1);
        $startPos = strpos($param, '[') + 1;
        $endPos = strpos($param, ']') - $startPos;
        $paramName = strpos($param, '[') === false ? $paramName = $param : substr($param, 0 , $startPos - 1);
        $paramPropetyGross = substr($param, $startPos,$endPos);
        $paramPropety = explode( '-',$paramPropetyGross);
        $paramPropety = array_combine($paramPropety, $paramPropety);
        return array($paramName,$paramPropety);
    }
    
  
    function setMiddleware($name)
    {
        $date= explode('@',$name);
        $this->middleware = [$date[0],$date[1]];
    }
    function getMiddleware()
    {
       return $this->middleware; 
    }      
    function getRoute()
    {
        return $this->rota;
    } 
    function getRouteReal()
    {
        return  $this->rota_not_param ;
    } 
    function getGroup()
    {
        return $this->group;
    }     
    function getRealControl()
    {
        $control = $this->teste;
        if(is_callable($control)){
            $return = ['1',$control];
            
        } else {
            $return = ['0',explode('@',$control)];
        }
        
        $this->control = $return;
    }
    function getParams(){
        return $this->params;
    }
    function getParamsPropety(){
        return $this->paramsPropety;
    }
   
    
    function urlExplode($url)
    {
        $array_explode = explode('/',$url);
        $url == '/' ? false : array_shift($array_explode);;
        return $array_explode;
    }
    
    function getFunction()
    {
        return $this->control ;
    }
    

    
    function getParamsRedirect()
    {
         return $this->params_real;
    }
    
  

}