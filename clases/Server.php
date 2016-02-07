<?php

class Server {
    static function getServerName(){
        return $_SERVER["SERVER_NANE"];
    }
    
    static function getRootPath(){
        return $_SERVER["CONTEXT_DOCUMENT_ROOT"];
    }
    static function getPort(){
        return $_SERVER["SERVER_PORT"];
    }
    static function gerUserAgent(){
        return $_SERVER["HTTP_USER_AGENT"];
    }
    static function getQueryString(){
        return $_SERVER["QUERY_STRING"];
    }
    static function getFile(){
        return $_SERVER["SCRIPT_FILENAME"];
    }
    static function getMethod(){
        return $_SERVER["REQUEST_METHOD"];
    }
    
    static function  isGet()
    {
        return self::getMethod()=="GET";
    }
    
    static function  isPost()
    {
        return self::getMethod()=="POST";
    }

    static function getClientAddres(){
        return $_SERVER["REMOTE_ADDR"];
    }
    static function getRequestDate($campo=null){
        switch($campo){
            case "Y":
                return intval(date("Y",$_SERVER["REQUEST_TIME"]));
            case "M":
                return intval(date("m",$_SERVER["REQUEST_TIME"]));
            case "D":
                return intval(date("d",$_SERVER["REQUEST_TIME"]));
            case "h":
                return intval(date("H",$_SERVER["REQUEST_TIME"]));
            case "m":
                return intval(date("i",$_SERVER["REQUEST_TIME"]));
            case "n":
                return intval(date("s",$_SERVER["REQUEST_TIME"]));
        }
       return $_SERVER("REQUEST_TIME");
    }
    
    static function getClientLanguage(){
        return $_SERVER("HTTP_ACCEPT_LANGUAGE");
    }
    
    public function getTodosLosValores(){
        foreach ($this as $indice => $valor) {
            echo"<br>$indice: $valor<br/>";
        }
    }
    
    public function getJson(){
            echo "{";
       foreach ($this as $indice => $valor) {
            echo '"'.$indice.'" : "'.$valor.'"<br>';
        }
        echo "}";
    }
}

?>
