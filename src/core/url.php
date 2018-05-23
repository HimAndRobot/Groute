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



class url
{   
    
    /**
    * $rotas é o array responsavel por armazenar as classes de rotas, criadas pelo usuario. 
    * os dados estao no formato $rota[tipo da requisição][indice da classe]
    */  
    private $rotas = array();
    
    /**
    * $active_control é o array responsavel por armazenar o controller e a action da url atual. 
    * os dados estao no formato $active_control['controller','action']
    */     
    private $active_function = array();
    
    private $active_middle = array();
    
    /**
    * $param é o array responsavel por armazenar os passados pela url 
    * Os dados estao no formato $param[nome definido no arquivo de rotas] = (valor passado pelo usuario)
    * Esse array é passado para o control
    */  
    private $params = array();
    
    
    private $group_active = '';
    private $groups= array();
    private $names_routes= array();
    private $rota_active = '';
    private $last_rota = '';
    
    function create_group($name)
    {
 
        $this->groups[$name]=['name'=>$name,'middle'=>''];
    }
    function define_active_group($name)
    {
        $this->group_active=$name;
    }
    
    function define_group_route($name)
    {
        $this->rota_active = $name;
    }  
    function define_middle($control)
    {
        $number = $this->last_rota;
        $obj = $this->rotas[$number[1]][$number[0]];
        $obj_group = $obj->getGroup();
    
        if($obj_group == ''){
            $obj->setMiddleware($control);
        }else{
            $date = explode('@',$control);
            $this->groups[$obj_group]['middle'] = [$date[0],$date[1]];
        }
    }
    

    
    /**
    * Esssa funçao adiciona no array de rotas uma nova classe com os parametros passados no arquivo de rotas
    * 1 - $rota = rota definida pelo usuario
    * 2 - $params = controller e action definidos pelo usuario no formato de 'controller@action'
    * 3 - $type = para qual tipo de requisição(GET,POST,DELETE) essa rota se aplica
    */  
    function addRoute($rota,$params,$type)
    {
        
        $this->rotas[$type][] = new route($rota,$params,$type,$this->group_active,$this->rota_active);
        $this->last_rota =[count($this->rotas[$type]) - 1,$type];

    }
/****************************** Inicia o sistema de parametros *****************************/  
    function paramCheck($params,$paramReal,$paramPropety)
    {
        foreach ($paramReal as $keys => $value) {
                    if(isset($params[$keys])){
                       $this->paramPropetyCheck($paramPropety[$keys],$params[$keys]);
                    }else{
                        if (!isset($paramPropety[$keys]['?'])) {
                            return false;
                        }else {
                            $params[$keys]='';

                        } 
                    }
                }    
                return $params;    
    }
   function paramPropetyCheck($paramPropety,$paramValue) 
    {
        $valorVarComp = '';
        foreach ( $paramPropety as $key => $value) {
            switch ($value) {
                case 'str':
                    $arrayPrag = '/[^a-z,A-z]/i' ;
                    $valorVarComp .= preg_replace($arrayPrag, '', $paramValue);
                    break;
                case 'int':
                    $arrayPrag = '/[^0-9]/i' ;
                    $valorVarComp .= preg_replace($arrayPrag, '', $paramValue);
                    break;
                case '?':
                    break;    
                default:
                    break;
            }
        }
        $paramValue = str_replace(' ', '', $paramValue);
        if ((strlen($paramValue) != strlen($valorVarComp)) && ($valorVarComp != '')){
            return false;
        }
    }    
/****************************** Fim do sistema de parametros *****************************/  
 /****************************** Inicia o sistema de rotas *****************************/  
    function execute($route_user,$type,$container)
    {
        $rota = $this->searchRoute($route_user,$type);
        $control = $this->preExecute($rota[0],$rota[1]);
        $midle = $this->active_middle;
        $this->runControl($control,$container);
               
    }
    function searchRoute($url_decode,$url_type){
        $url_enconde = $this->urlEncode($url_decode);
        foreach ($this->rotas[$url_type] as $key => $rote) {
            $route_params = $rote->getParams();
            $route_paramsPropety = $rote->getParamsPropety();
            $route_real = array_diff_key($url_enconde, $route_params);
            $route_fake = $rote->getRoute();
            $params = array_diff_key($url_enconde, $route_real);
            if(($route_real == $route_fake)){
                $retorno = $this->paramCheck($params,$route_params,$route_paramsPropety);
            if($retorno){
                return array($rote,$retorno); 
            };    
               
            }
        }
         echo "Pagina inexistente. Por favor contate o web master";
         exit;
    }   
    function urlEncode($url_decode)
    {
        $url_enconde = array();
        if($url_decode == ''){
            $url_enconde = explode('/', '/');
        } else{

            substr($url_decode, -1) == '/' ? $url_decode = substr($url_decode, 0,-1):false;
            $url_enconde = explode('/', $url_decode);
        }
        return $url_enconde;
    }

    function preExecute($route,$route_params)
    {
        $this->params = array_combine($route->getParams(), $route_params); 
        $route_middle = [$route->getGroup(),$route->getMiddleware()];
        $this->middlewareExecute($route_middle);
        return $route->getFunction();
    }
    function middlewareExecute($middle_info)
    {
        if($middle_info[0] == '' && $middle_info[1] != ''){
            $control = 'app\http\middleware\\'. $middle_info[1][0];
            $action = $middle_info[1][1];
            $control = new $control;
            $control->$action();
        }
        else if(isset($this->middle_info[$middle_info[0]]['middle'][0])){
            $midle2 = $this->middle_info[$middle_info[0]]['middle'];
            $control = 'app\http\middleware\\'. $midle2[0];
            $action = $middle_info[1][1];
            $control = new $control;
            $control->$action();

        }
    }    
 /****************************** Final do sistema de rotas *****************************/  


   
    
    public function defineNome($name)
    {
        $lat = $this->last_rota;
        $lat = $this->rotas[$lat[1]][$lat[0]];
        
        $this->names_routes[$name] = [$lat->getRouteReal(),$lat->getParamsRedirect()];
    }
    
    /**
    * Essa função executa o control($active_control) e passa nele os parametros($param)
    */    
    private function runControl($control_name,$container)
    {
        $request = new http\request;
        $function = $control_name;
        $container->path->setRoute($this->names_routes);
     
            $a= 'login';
            $control = 'app\http\controller\\'. $control_name[1][0]."Controller";
            $method = $control_name[1][1];
                if( $control = new $control($container) ){
                    $control->$method($request,$this->params);
                }
          
    }
    
    /**
    * Função principal executa todas as outras.
    * 1 - procura uma rota aproximada com find_Routes()
    * 2 - analisa se rota encontrada é valida com a check_Route()
    * 3 - executa o controller com o runControl()
    */ 
    
    
        
 

}