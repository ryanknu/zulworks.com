<?php

namespace Application\Model;

class AppleScript
{
    private function quote($str)
    {
        return str_replace("\"", "\\\"", $str);
    }
    
    public function exec($script, $filter=true)
    {
        $ar = [];
        $execStr = 'osascript -s s -e "' . $this->quote( $script ) . '"';
        //echo $execStr;
        exec( $execStr, $ar );
        if ( $filter )
        {
			foreach($ar as $key => $val)
			{
				$ar[$key] = json_decode( str_replace(['{', '}'], ['[', ']'], $val) );
			}
        }
        return $ar;
    }
    
    public function tell($application, $something)
    {
        $tell = "tell application \"$application\"\n $something \nend tell";
        return $this->exec( $tell );
    }
    
    public function file($fname)
    {
    	return $this->exec( file_get_contents($fname) );
    }
}
