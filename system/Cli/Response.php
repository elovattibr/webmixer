<?php

namespace Cli;

class Response {
    
    private $response = null;
    
    function __construct(Route $route) {
        
        try {
            
            $route->prepare();
                    
        } catch (\Runtime\Errors\Error404 $ex) {

            echo $ex->getMessage();
                
        }
        
        $this->output($route->submit());
        
    }
  
    public function output($response){
        
        switch(gettype($response)){
            
            case 'array':
                exit(json_encode($response));
                
            default:
                exit(ob_get_clean());
            
        }
        
    }
    
}
