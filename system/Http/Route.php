<?php

namespace Http;

class Route {
    
    var $injection, $request = null;
    
    function __construct(Request $source) {
        $this->app     = \Runtime\App::attach();
        $this->source  = $source;
    }
    
    public function prepare(){
        
        $source = &$this->source;

        /*If a invalid module was requested, then die.*/
        if (!autoload($source->module)){
            die("Erro");
            return false;
        };        
        
        //Then let's instantiate the module that y've asked for
        $this->target = $this->app->container->{$source->module}(
            $this->source->get, $this->source->request
        );
        
        return $this;
        
    }
    
    public function submit($source){
        return $this->target->{$this->source->action}($source);
    }
  
}
