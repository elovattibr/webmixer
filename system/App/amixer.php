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

namespace App;

class amixer {
    
    public $device = 'default',
           $preferences = Array();
    
    private $prefixes = Array(
        "numid" => "id",
        "  ; t" => "type",
        "  ; I" => "item",
        "  : v" => "values",
        "  | d" => "dB",
        "  | c" => "container",
        "    |" => "sub_container",
    );
    
    /*WEB VIEWS*/
    public function __call($name, $arguments) {
        
        $this->device = $name;
        
        include "views/mixer/mixer.php";
        
    }

    /*CLI*/
    public function show() {
        
        $mixers = $this->get();
        
        foreach($mixers AS $idx => $mixer) {
            echo "\n {$mixer['name']}";
        }
        
    }

    /* REST API (sort of) */
    public function get(){
        
        $this->device = $this->request['device'];
        
        $controls    = Array();
        $contents    = $this->contents();
        $channels    = $this->channels();
        $preferences = $this->preferences();
        
        foreach($contents AS $idx => &$mixer){
            
            $id         = $mixer['id'];
            $full_name  = $mixer['name'];
            $short_name = str_replace(Array("Source","Switch","Volume","Playback"), "", $full_name);
            $short_name = str_replace("  ", " ", $short_name);
            
            /*Format workarounds*/
            if(isset($mixer['step'])){
                $mixer['step'] = floatval(str_replace("dB", "", $mixer['step']));
            }

            switch($mixer['type']){
                
                case 'ENUMERATED':

                    if(is_array($mixer['items']) && is_array($mixer['values'])){
                        if(isset($mixer['values'][0])){
                            $selection = intval($mixer['values'][0]);
                            $mixer['items'][$selection]['checked'] = true;
                        }
                    }
                    
                    $controls[trim($short_name)]['source'] = $mixer;
                    break;
                
                case 'BOOLEAN':
                    if(is_array($mixer['values']) && is_array($mixer['values'])){
                        $mixer['values'] = array_map(function($state){
                            switch(true){
                                case ( trim($state) === 'on' ): return true;
                                case ( trim($state) === 'off' ): return false;
                            }
                            return $state;
                        }, $mixer['values']);
                    }                   
                    
                    $controls[trim($short_name)]['switch'] = $mixer;
                    break;
                
                case 'INTEGER':
                    
                    $mixer['channels'] = Array();
                    
                    if($mixer['step'] > 0){
                        
                        foreach($channels[trim($short_name)] AS $idx => $name){
                            
                            $mixer['channels'][] = Array(
                                "name" => $name,
                                "id" => $mixer['id'],
                                "channel" => $idx,
                                "min" => $mixer['min'],
                                "max" => $mixer['max'],
                                "step" => $mixer['step'],
                                "current" => isset($mixer['values'][$idx])?$mixer['values'][$idx]:false
                            );
                        }

                        $controls[trim($short_name)]['volume'] = $mixer;
                        
                    }
                    break;
                
                default:
                    $controls[trim($short_name)]['unknown'] = $mixer;
                    break;
                
            }
            
            $controls[trim($short_name)]['id'] = trim($id);
            $controls[trim($short_name)]['name'] = trim($short_name);
            $controls[trim($short_name)]['preferences'] = $preferences;
            
        }
        
        return Array(
            "preferences" => $preferences,
            "controls" => array_values($controls)
        );
        
    }
    
    public function set(){
        
        exit(json_encode(Array(
            "status"=>true,
            "output"=>$this->amixer(
                "cset numid={$this->request['id']} {$this->request['value']} {$this->request['channel']} "
            )
        ),true));
        
    }
    
    /*AMIXER*/
    private function amixer($params, $grep=false){
        
        $cmdl = "amixer -D {$this->device} " .
                "{$params}" .
                (($grep!==false)?" |grep {$grep}":"");
        
        exec($cmdl, $contents);
        
        return $contents;
        
    }
    
    private function contents(){
         
        //Our mixes will be populated here
        $mixers  = Array();
        
        //Pointer for mixer finding
        $pointer = null;
        
        //Ask amixer for its content
        foreach($this->amixer("contents") AS $idx => $row){
           
            /* The first 6 chars are the prefixes 
             * for each kind of possible row */
            $prfxid = substr($row,0,5);
            
            /*But we strip only 4 to parse 
             * all information correctly*/
            $endfx  = substr($row, 3);
            
            /*Association so we can see the pattern*/
            $prefix = isset($this->prefixes[$prfxid])
                        ?$this->prefixes[$prfxid]:false;

            switch($prefix){
                
                case "id":
                case "type":
                case "dB":
                    
                    /* This prefix means a new mixer */
                    if($prefix === "id"){
                        $pointer = &$mixers[];
                        $pointer = Array();
                    }
                    
                    foreach(split(',', $endfx) AS $aidx => $arg) {
                        list($key, $val) = split('=', $arg);
                        $pointer[trim($key)] = trim(trim($val),"'");
                    };
                    break;
                    
                case "values":
                    $item = str_replace("values=","",$endfx);
                    $values = split(',', $item);
                    /*Check if count is same as values found*/
                    if(count($values) == $pointer['values']){
                        $pointer['values'] = $values;
                    } else {
                        unset($pointer['values']);
                    }
                    break;
                    
                case "item":
                    if(!is_array($pointer['items'])){
                        $pointer['items'] = Array();
                    };
                    $item = str_replace(Array("Item #","'"),"",$endfx);
                    $num = trim(substr($item, 0,2));
                    $desc = trim(substr($item, 2));
                    $pointer['items'][] = Array(
                        'index'=>$num, 
                        'source'=>$pointer['id'], 
                        'description'=>$desc, 
                    );
                    break;
                
                /*Can we use it ? Not figure out how. Yet.*/
                case "container":
                    $pointer['container'] = Array();
                    break;
                
                case "sub_container":
                    $pointer['container'][] = str_replace(" |","",$endfx);
                    break;
                
            }
            
        }
        
        return $mixers;
        
    }
    
    private function channels(){
         
        //Our channels will be populated here
        $channels  = Array();
        
        //Pointer for mixer finding
        $pointer = null;
        
        //Ask amixer for its content
        foreach($this->amixer("", "-e control -e channels") AS $idx => $row){
           
            $prfxid = trim(substr($row,0,2));
            
            if($prfxid != ""){
                
                preg_match_all("/\'(.*?)\'/", $row, $matches);
                $description = $matches[1][0];
                $pointer = &$channels[$description];
                $pointer = Array();
                
            } else {
                
                if(isset($pointer)){
                    
                    $name = split(": ",$row)[1];
                    
                    foreach(split("-", $name) AS $idx => $val){
                        $pointer[$idx] = trim($val);
                    }
                }
                
            }

        }
        
        return $channels;
        
    }
    
    /*CUSTOM*/
    public function preferences(){
        
        $api = $this->modules->preferences();
        
        $set = $api->preferences->devices->{$this->device};
        
        $this->preferences = $set;
        
        return $set;
        
    }
    
}
