<?php


class Request {
    
    //Si le pasamos un false a filtrar es para que no me filtre
    
    static function get($nombre,$filtrar=true, $indice=null) {
        if(isset($_GET[$nombre])){
           return self::read($_GET[$nombre],$filtrar,$indice);     
        }
        
        return null;   //Porque es el único valor que no se puede pasar
    }
    
    
    
    static function post($nombre,$filtrar=null,$indice=null){
        if(isset($_POST[$nombre]))
        {
            return self::read($_POST[$nombre],$filtrar,$indice);  
        }
        
        return null;   //Porque es el único valor que no se puede pasar
    }
    
    
    //Ahora haremos un metodo que nos lea tanto si es post como get
    
    static function req($nombre,$indice=null){
        $valor=self::post($nombre,$indice);
        
        if($valor!==null)
        {
            return $valor;
    
        }    
       return self::get($nombre,$indice);
     }
     
     //Para filtrar y limpiar el valor que llegue. Por ejemplo, si llega un <script>...</script>
     
     private static function clean($valor,$filtrar){
         if($filtrar===true){
         return htmlspecialchars($valor);
         }
         //trim quita espacios iniciales y finales 
         return trim($valor);
     }
     
     
     //Aqui llegara un $_GET[nombre] o $_POST[nombre]. Vamos a unificar el metodo
     
     private static function read($parametro,$filtrar=true,$indice=null){
             if(is_array($parametro)){
            
              if($indice===null) {
             
               $array=array();
               
               foreach($parametro as $value)
               {
                   $array[]=self::clean($value,$filtrar);
               }
               return $array;
               
            }
            else{
                if(isset($parametro[$indice])) {
                    return self::clean($parametro[$indice],$filtrar);
                }  
            }
            }
            else{
               return self::clean($parametro,$filtrar);
        }

    }
}
