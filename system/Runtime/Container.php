<?php

namespace Runtime;

use ArrayObject;

class Container extends ArrayObject {
    
    static $instance; 
    
    public function __construct() {
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
    }

    function __get($name){
        echo "<p>GET {$name}</p>";
    }
    
    public function __call($name, $args){
        
        if(!autoload($name))
            throw new \Runtime\Errors\Error404("Class not found");

        if(count($args)){
            
            $ref = new \ReflectionClass($name);
            return $ref->newInstanceArgs($args);
            
        } else {
            $map = &$this->{$name};
            $map = new $name(null, null);
            return $map;
        }
        
    }    
    
    
}


