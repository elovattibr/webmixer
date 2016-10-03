<?php

namespace Http;

class Server extends \Runtime\Module {
    
    public function __construct() {

        parent::__construct();
        
        if(\tools::checkPhpCli()){
            
            $this->startServer(ROOT_FOLDER);
            
        }

    }
    
    private function startServer($root) {
        
        echo "Starting server in '{$root}'\n";
        
        passthru("/usr/bin/php -S 0.0.0.0:8080 -t {$root}");
        
    }
    
}
