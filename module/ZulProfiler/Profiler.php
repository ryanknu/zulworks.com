<?php

/**
 * ZulProfiler
 * Profiling tool for Zend Framework 2
 */

namespace ZulProfiler;

class Profiler
{
    private static $_instance = null;
    
    private function __construct() {}
    
    public static function profiler()
    {
        if ( self::$_instance === null )
        {
            self::$_instance = new Profiler;
        }
        return self::$_instance;
    }
    
    public function startDispatch()
    {
        
    }
    
    public function startRender()
    {
        
    }
    
    public function stopTimer()
    {
        
    }
}
