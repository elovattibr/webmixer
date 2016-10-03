<?php
namespace Runtime;

class Module {
    
    protected $db, 
              $app,
              $output;
    
    public $modules,
           $request;

    public function __construct($get=array(), $request=array()) {
        
        $this->app = App::attach();
        
        $this->request = $request;
        
        $this->modules = &$this->app->container;
        
        if($this->app->datasource){
            $this->db = &$this->app->datasource;
        }
        
    }
    
}

