<?php
/**
 * Mixer module
 * 
 * This class goal is to parse
 * amixer output and turn into 
 * a rest api like interface.
 * 
 * @author Eduardo Lovatti
 */

class devices extends \Runtime\Module {
    
    public $preferences,
           $devices;

    public function __construct($get, $request){
        
        parent::__construct($get, $request);
        
        $this->load();
        
    }
    
    public function __call($name, $arguments) {
        
        $this->device = $name;
        
        include "views/devices/device.php";
        
    }
    
    private function load (){
        
        $preferences = $this->modules->preferences();
        
        $this->preferences = $preferences->preferences;
        
        $this->devices = $this->preferences->devices;
        
    }

}
