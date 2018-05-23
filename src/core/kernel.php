<?php
namespace core;

class kernel 
{
    static private $routes = array();
    
    public function __call($name, $arguments)
    {
        if($name == "get" or $name == "post" or $name == "put" or $name == "delete") {
            $arguments[] = $name;
            call_user_func_array(array($this,'addRoute'),$arguments);
        } else {
            trigger_error('O metodo informado ainda não é aceito pelo GROUTE. Por favor acesse nossa documentação para saber os metodos aceitos.',E_USER_WARNING);
        }
    }
    
    private function addRoute($bruteRoute, $routeCallable, $type)
    {
        $tratamentRoute = $this->tratamentBruteRoute($bruteRoute);
        $route = new route($tratamentRoute['route'], $tratamentRoute['params'], $tratamentRoute['paramsPropeties']);
        self::$routes[$type][] = $route;
        echo '</br>.........................</br>';
        print_r(self::$routes);
        
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
                $params[$key] = $value;              
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