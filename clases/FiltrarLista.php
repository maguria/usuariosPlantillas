<?php


class FiltrarLista {
    
    
    function __construct() {
      
    }
    function getUsuario($cadena) { 
        $pos= strpos($cadena,"_");
        $cad=  substr($cadena, 0,$pos);
        return $cad;
    }
    function getCategoria($cadena) {
        
        $max=  strlen($cadena);
        $pos1=strpos($cadena,"_");
        $pos2=  strrpos($cadena, "_");
        $pos3=$max-$pos2;
        $cad=  substr($cadena,$pos1+1, -$pos3);
        return $cad;
    }
    function getNombre($cadena) { 
        $pos1=  strrpos($cadena, "_");
        $cad=substr($cadena,$pos1+1,  strlen($cadena)-4);
        return $cad;   
    }
    
     function getLista($lista){
       $files=array();
        if (is_dir($lista)) {
            if ($dh = opendir($lista)) {
              while (($file = readdir($dh)) !== false) {
                $files[]=$file;
        }
        closedir($dh);
     }
    }
    return $files;
    }
   
}
