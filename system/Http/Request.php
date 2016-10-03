<?php

namespace Http;

class Request {
    
    var $extensions = array(
            "jpg", "jpeg", "gif", "css", "js"
        ),
        $session, 
        $request, 
        $server, 
        $cookie, 
        $files, 
        $post, 
        $get;
    
    public function __construct() {
        
        $this->server  = $_SERVER;
        $this->request = $_REQUEST;
        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->agent   = $_SERVER['HTTP_USER_AGENT'];
        $this->remote  = $_SERVER['REMOTE_ADDR'];
        
        foreach($GLOBALS AS $name => $values) {
            switch($name) {
                case '_GET':
                case '_POST':
                case '_FILES':
                case '_SERVER':
                case '_COOKIE':
                case '_REQUEST':
                case '_SESSION':
                    $var = strtolower(ltrim($name, "_"));
                    $this->{$var} = $values;
                    unset($GLOBALS[$name]);
                    break;
            }
        }
        
        if($this->serve()){
            
            unset($GLOBALS);
            unset($_SERVER);
            
            $this->parse();
        };

    }
    
    public function serve() {
        
        $path = parse_url($this->server["REQUEST_URI"], PHP_URL_PATH);
        
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        if (in_array($ext, $this->extensions)) {
            // let the server handle the request as-is
            return false;  
        }
        
        return true;
        
    }
    
    public function parse() {
        
        $parts = explode('/',$this->server["REQUEST_URI"]);
        
        $this->action = array_pop($parts);
        
        $this->module = trim(join('/', $parts), '/');
        
        /*No module, then go index -> main.*/
        if (strlen($this->module) <= 0 && strlen($this->action) <= 0){
            $this->module = 'home';
            $this->action = 'main';
        };
        
    }
    
}
