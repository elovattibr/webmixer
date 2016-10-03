<?php
/**
 * Home module
 * 
 * This class act like a router to 
 * static index html file
 * 
 * @author Eduardo Lovatti
 */

class home extends \Runtime\Module {
    
    public $preferences;

    public function __construct($get, $request){
        
        parent::__construct($get, $request);
        
        $this->load();
        
    }
        
    /*WEB VIEWS*/
    public function __call($name, $arguments) {
        
        include "views/home/{$name}.php";
        
    }   
    
    private function load (){
        
        $preferences = $this->modules->preferences();
        
        $this->preferences = $preferences->preferences;
        
        $this->devices = $this->preferences->devices;
        
    }
        
}
