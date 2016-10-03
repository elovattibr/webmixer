<?php

namespace Runtime;

use ArrayObject;
use Http;
use Cli;

class App {
    
    /*Singleton instance*/
    static $instance; 
    
    public $settings,   //Settings
           $container;  //Dependency injection container
    
    /*Private constructor. This class must be instantiated with Static init func*/
    private function __construct($settings) {
        
        /*Dependency injection container*/
        $this->container  = new Container();
        
        /*Built in settings file*/
        $this->settings   = new ArrayObject($settings, ArrayObject::ARRAY_AS_PROPS);
        
        /*Map to your database / abstraction*/
        $this->datasource = null;
        
    }
    
    /*Class initializer*/
    public static function init($settings=Array()) {
        
        if (!is_null(self::$instance) && is_a(self::$instance, __CLASS__))
            return self::$instance;
        
        return (self::$instance = new self($settings));
        
    }   
    
    /*Late binding for newly instantiated classes*/
    public static function attach() {
        
        if (is_null(self::$instance))
            throw new \Exception("Can't attach a uninitialized application.");
        
        return self::$instance;
        
    }   
    
    /*
     * Gateway for Cli, Standalone and 3rd party web server
     * 
     *  This is the entry point to anything. 
     * 
     *  On a webserver it rely on the URI ex:
     *  http://server/{module}/{action}/{other}/{other1}/... [pattern]
     *  http://server/mixer/get [real example]
     * 
     *  On the cli it will rely on the given command ex:
     *  php index.php module action other1 other2 ... [pattern]
     *  php index.php mixer show [real example]
     *  
     *  Both Web and Cli have same capabilities like 
     *  $this->db, $this->modules, $this->settings...
     *  so you only worry about coding everything else
     *  this micro framework handles for you.
     * 
     *      */
    public function run() {
        
        try {
            
            switch(\tools::checkPhpCli()){
                
                //Am I on Cli?
                case true: 
                    
                    /*Cli environment setup*/
                    require_once 'system/cli.php';
                    
                    new Cli\Response( //Return the response
                        new Cli\Route() //For the route requested [no sanitization needed]
                    );
                    break;
                
                //Default is HTTP
                default:
                    new Http\Response( //Response
                        new Http\Route(    //For the route requested
                           new Http\Request()   //We've got a new request -> sanitization and security concerns
                        )
                    );
                
            }
            
        } catch (Exception $ex) {
            
            /* OH SNAP ! 
             * Do you know this means dont you? 
             * This not handled exception will be throwned 
             * to the CLI or WEB server. */
            
            echo $ex->getMessage();
            
        }
        
    }   
    
}

