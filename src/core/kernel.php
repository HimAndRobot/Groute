<?php
namespace core;

class kernel 
{
    
    /** 
    * Comentário de cabeçalho de arquivos 
    * Esta classe é a principal, reponsavel por cria uma aplicação groute.
    * 
    * @author HIM&ROBOT <him&robot@gmail.com> 
    * @version 0.1 
    * @access public 
    * @package groot/main 
    * @copyright GPL © 2018, HIM&ROBOT ltda. 
    */ 


    static private $routes = array();
    static private $container;
    
    public function run()
    {
        $browserUrl = $this->getBrowserUrl();  
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $route = $this->findRouteByUrl($requestMethod, $browserUrl);
        $params = $this->combinedParams($route, $browserUrl);
        $this->runClass($route ,$params);
    }
    
    private function runClass(route $route, array $params)
    {
        $callable = $route->getCallable();
        $container = self::$container;
        if($callable['type'] == 'function') {
            call_user_func($container[$callable['class']], $params);
        } else {
            call_user_func_array([$container[$callable['class']], $callable['method']], [$params]);
        }
    }
    
    public function loadContainer()
    {
        return new container;
    }
    
    public function setContainer($container)
    {
        self::$container = $container;
    }
    
    private function combinedParams(route $route, array $browserUrl)
    {
        $routeParams = $route->getParams();
        $browserUrlParams = array_intersect_key($browserUrl, $routeParams);
        $userParams = array_combine($routeParams, $browserUrlParams);
        return $userParams;
    }
    
    private function findRouteByUrl(string $routeMethod, array $browserUrl)
    {
        $routes = self::$routes[$routeMethod];
        foreach($routes as $key => $route) {
            if($route->compareRoutes($browserUrl)){
                return $route;
            } else {
                trigger_error('ERROR 404: O url digitado não existe. Por favor contante o web master.',E_USER_ERROR);
            }
        }
    }
    
    private function getBrowserUrl()
    {
        if(isset($_GET['url'])) {
            $urlBrute = '/' . $_GET['url'];
        } else {
            $urlBrute = '/';
        }
        $browserUrl = $this->tratamentBruteBrowserUrl($urlBrute);
        return $browserUrl;
    }
    private function tratamentBruteBrowserUrl(string $bruteBrowserUrl)
    {
        if((strlen($bruteBrowserUrl) > 1) and (substr($bruteBrowserUrl,-1) == '/')){
            $bruteBrowserUrl = substr($bruteBrowserUrl,0,-1);
        }
        $browserUrl = explode('/',$bruteBrowserUrl);
        return $browserUrl;
    }
        
    public function __call($name, $arguments)
    {
            $arguments[] = $name;
            call_user_func_array(array($this,'addRoute'),$arguments);
    }
    
    private function addRoute(string $bruteRoute, string $routeCallable, string $type)
    {
        $tratamentRoute = $this->tratamentBruteRoute($bruteRoute);
        $tratamentCallable = $this->tratamentCallable($routeCallable);
        $route = new route($tratamentRoute['route'], $tratamentRoute['params'], $tratamentRoute['paramsPropeties'], $tratamentCallable);
        self::$routes[$type][] = $route;
    }
    
    private function tratamentCallable(string $bruteCallable)
    {
        if(strPos($bruteCallable, '.') === FALSE) {
            return ['function', $bruteCallable, ''];
        } else {
            $callable = explode('.', $bruteCallable);
            array_unshift($callable, 'controller');
            return $callable;          
        }
    }
    
    private function tratamentBruteRoute(string $bruteRoute)
    {
        $explodeRoute = explode('/', $bruteRoute);
        $route = array();
        $params = array();
        foreach($explodeRoute as $key => $value) {
            if(strpos($value, '{') === FALSE ) {
                $route[$key] = $value; 
            } else {
                $params[$key] = $value;
            }
        }
        $params = $this->tratamentBruteParams($params);
        return array('route' => $route, 'params' => $params['params'], 'paramsPropeties' => $params['paramsPropeties']);
    }
    
    private function tratamentBruteParams(array $bruteParams)
    {
        $params = array();
        $paramsPropeties = array();
        foreach($bruteParams as $key => $value) {
            $paramBrute = substr($value,1,-1);
            if(strpos($paramBrute, '[') === FALSE) {
                $params[$key] = $paramBrute;              
            } else {
                $strInicio = strpos($paramBrute, '[') + 1;
                $strFim = strpos($paramBrute, ']') - $strInicio;
                $brutePropeties = substr($paramBrute,$strInicio,$strFim);
                $paramPropeties = $this->tratamentBruteParamPropeties($brutePropeties);
                $strFim = strpos($paramBrute, '[');
                $params[$key] = substr($paramBrute,0,$strFim);
                $paramsPropeties[$key] = $paramPropeties;
            }
        }
        return array('params' => $params, 'paramsPropeties' => $paramsPropeties);
    }
    
    private function tratamentBruteParamPropeties(string $brutePropeties) {
        $paramPropeties = explode('-',$brutePropeties);
        $validValues = array('str','?','int');
        foreach($paramPropeties as $key => $value) {
            if(in_array($value, $validValues)) {
                $return[$value] = TRUE;
            } else {
                trigger_error('O filtro utilizado no parametro da rota não existe.',E_USER_ERROR);
                exit;
            }
        }
        return $return;
    }
}