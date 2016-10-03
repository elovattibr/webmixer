<?php 

session_start() or die('PHP Sessions must be enabled.');
//error_reporting(false);

function autoload($className)
{
    
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    $fileName .=  str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    
    switch(true) {
        
        case autocheck('system', $fileName): break;
        case autocheck('modules', $fileName): break;
        case autocheck('libs', $fileName): break;
        
        default: return false;
    }
    
    return true;
}

function autocheck($dir, $file, $parent=true)
{
    
    $root = ($parent)?realpath(".".DIRECTORY_SEPARATOR):".".DIRECTORY_SEPARATOR;
    $base = $root . DIRECTORY_SEPARATOR . $dir;
    $path = $base . DIRECTORY_SEPARATOR . $file;
    
    if(file_exists($path)) {
        require_once $path;
        return true;
    }
    
    return false;
    
}

spl_autoload_register('autoload');