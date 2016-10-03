<?php

namespace Cli;

class Route {
    
    var $injection, $args = null;
    
    function __construct() {
        $this->app     = \Runtime\App::attach();
        $this->parse();
    }
    
    public function prepare(){
          
        /*No module means start server*/
        if (strlen($this->module) <= 0){
            $this->module = 'Http\Server';
        };
        
        /*If a invalid module was requested, then die.*/
        if(!autoload($class = $this->module)){
            die("Could not load module {$this->module}. Aborting.");
        };
        
        /*All set. Then construct the class.*/
        $this->target = 
                $this->app->container->{$class}
                        (Array(), $this->args);
                        
        return $this;
        
    }
    
    public function submit(){
        return $this->target->{$this->action}($this->args);
    }
  
    public function parse() {
        $this->args = \tools::getArguments();
        $this->action = $this->args['action'];
        $this->module = $this->args['module'];
    }    
    
}
