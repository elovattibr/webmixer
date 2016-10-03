<?php

class tools {
    
    static function uuid(){
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0010
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }  
    
    static function getAddress(){
        $host = trim($_SERVER['HTTP_HOST'], '/');
        $path = trim(dirname('/' . trim($_SERVER['PHP_SELF'], '/') . '/'), '/');
        return "http://$host/$path/";
    }  
    
    static function log($what){
        
        switch(true) {
            
            case (is_array($what)):
            case (is_object($what)):
               error_log(var_export($what,true)); 
            break;
        
            default:
               error_log($what); 
        }
        
    }
    
    //### CONSOLE ###
    static function console($array) {
        
        $message = "\n[".date('Y-m-d H:i:s',time())."]";
        
        foreach($array as $name => $value) {
            
            if($name === 0 || $name === false || $name === null) {
                $message .= " [{$value}]";
            } else {
                $message .= " [{$name}: '{$value}']";
            }
            
            
        }
        
        echo $message;
        
    }       
    
    static function getArguments(){
        
        global $argv;
        
        $arguments = Array();
        
        foreach ($argv as $idx => $arg) {
            
            switch (true) {
                
                case ($idx == 1):
                    $arguments['module'] = $arg;
                    break;
                
                case ($idx == 2):
                    $arguments['action'] = $arg;
                    break;
                
                case ($idx > 2):
                    $arguments["option-{$idx}"] = $arg;
                    break;
                
            }
            
        }
        
        return $arguments;
        
    }
    
//    
//    static function getArguments(){
//        
//        global $argv;
//        
//        $_ARG = array();
//        
//        foreach ($argv as $arg) {
//            
//          if (ereg('--([^=]+)=(.*)',$arg,$reg)) {
//              
//            $_ARG[$reg[1]] = $reg[2]; 
//                
//            } elseif (ereg('-([a-zA-Z0-9])',$arg,$reg)) {
//                $_ARG[$reg[1]] = 'true';
//            }
//
//        }
//        
//        return $_ARG;
//        
//    }
//    
    static function getOsName(){
        return strstr(php_uname(), " ",true);        
    }

    static function checkPid($PID){
        
        switch (self::getOsName()){
            
            case 'Windows':
              exec("Tasklist /v /fi \"PID eq $PID\" /fo csv", $ProcessState);
              return(count($ProcessState) >= 2);
              
            case 'Linux':
                return (posix_getpgid($PID)!=false);
              
            
        }
        
    }
    
    static function realPath($path){
        return str_replace(Array("\\","/"), DIRECTORY_SEPARATOR,realpath($path));
    }
    
    static function usleep($msec) {
       $usec = $msec * 1000;
       @socket_select($read = NULL, $write = NULL, $sock = array(@socket_create (AF_INET, SOCK_RAW, 0)), 0, $usec);    
    }
    
    static function checkPhpCli() {
       return (php_sapi_name()=='cli')?true:false;
    }
    
}