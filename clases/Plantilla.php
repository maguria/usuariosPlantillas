<?php

class Plantilla {
    
    function __construct() {
      
    }
    function get($plantilla){
        return file_get_contents('plantilla/'.$plantilla.'.html');
    }
    
    function replace($param,$pagina){
         foreach ($param as $key=>$value){
           $pagina=str_replace("{".$key."}",$value,$pagina);
       }
       return $pagina;
    }
}