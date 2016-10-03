<?php 
/**
 * Preferences module
 * 
 * @author Eduardo Lovatti
 */

class preferences extends \Runtime\Module {
    
    public $location = null,
            $contents = false,
            $preferences = Array(
                'devices'=>Array(
                    "default" => Array(
                        "label" => "Default soundcard",
                        "columns" => "4",
                        "mode" => "mixer",
                        "orientation" => "horizontal",
                        "presets" => true,
                        "schedule" => true,
                        "mixers" => Array()
                    ), 
                    "equal" => Array(
                        "label" => "Equalizer",
                        "columns" => "1",
                        "mode" => "equalizer",
                        "orientation" => "vertical",
                        "presets" => true,
                        "schedule" => true,
                        "mixers" => Array()
                    ), 
                )
            );
    
    public function __construct($get, $request){
        
        parent::__construct($get, $request);
        
        $this->load();
        
    }
    
    //When we call a inexisting method, 
    //it will assume that you want to load a view.
    
    /* TODO: Implement a view check in parent class */
    public function __call($name, $arguments) {
        
        include "views/preferences/{$name}.php";
        
    }    
    
    /*
     * PUBLIC
     */
    
    //Return preferences in JSON [REST API like]
    public function get(){
        
        exit(json_encode($this->preferences, true));
        
    }
    
    //Set preferences to JSON [REST API like]
    public function set(){
        
        /* NOT IMPLEMENTED */
        
    }
    
    /*
     * PRIVATE
     */
    
    //Get the preference object and save it in json format.
    private function save (){
        /*
         * too primitive. 
         * todo: implement permission check pattern.
         */
        $converted = json_encode($this->preferences, true);
        
        file_put_contents($this->location, $converted);
        
        return true;
        
    }
    
    //Load the preferences file to an object
    private function load (){
        /*
         * too primitive. 
         * todo: implement permission check pattern.
         */
        
        $this->location = $this->app->settings->preferences_file;
        
        if(file_exists($this->location)){
            
            $this->contents = file_get_contents($this->location);

            $this->preferences = json_decode($this->contents);
            
        } else {
            
            $this->save();
            
        }
        
        return true;
        
    }
    
}
