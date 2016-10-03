<?php
namespace Runtime;

class Injector {
    
    public $class, $args;

    public function __construct($class, $args) {
        
        $this->class = $class;
        $this->args = $args;

        
    }
    
    
}

