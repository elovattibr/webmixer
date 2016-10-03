<?php

namespace Http;

class Response {
    
    private $response = null;
    
    function __construct(Route $route) {
        
        try {
            
            $route->prepare();
                    
        } catch (\Runtime\Errors\Error404 $ex) {

            echo $ex->getMessage();
                
        }
        
        $this->output($route->submit($route->source));
        
    }
  
    public function output($response){
        
        switch(gettype($response)){
            
            case 'array':
                ob_end_clean();
                header_remove();
                header ('Content-Type: text/json; charset=utf-8');
                exit(json_encode($response));
                
            default:
                exit($response);
            
        }
        
    }
    
}
