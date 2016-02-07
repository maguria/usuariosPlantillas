<?php


class Filter {
    
    static function isEmail($valor){
         return filter_var($valor, FILTER_VALIDATE_EMAIL);

    }
    
     static function isNumber($valor){
         return filter_var($valor, FILTER_VALIDATE_INT);

    }
    
     static function isFloat($valor){
         return filter_var($valor, FILTER_VALIDATE_FLOAT);

    }
    
    static function isIp($valor){
         return filter_var($valor, FILTER_VALIDATE_IP);

    }
    
    static function isUrl($valor){
         return filter_var($valor, FILTER_VALIDATE_URL);

    }
    
    static function isMinLength($valor,$longitud){
        
         return strlen($valor) <= $longitud;

    }
    
    static function isLogin($valor){
        return preg_match('/^[A-Za-z][A-Za-z0-9]{5,9}$/', $valor);
     
    }
    
    static function isSexo($valor){
        if($valor==="m" || $valor=="f" || $valor=""){
            return true;
        }
        else{
            return false;
        }
    }
    
   static function isDate($valor){
       
   }
    
}