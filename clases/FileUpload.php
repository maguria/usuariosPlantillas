<?php

class FileUpload {
    const CONSERVAR = 1, REEMPLAZAR = 2, RENOMBRAR = 3;
    private $destino="./", $nombre="", $tamaño=10000000000000000000,$parametro,$extension,$error=false, $politica = self::REEMPLAZAR,$errorCode;
//tipo, archivos
    private $arrayDeTipos = array(
        "jpg"=>1,
        "gif"=>1,
        "png"=>1,
        "jpeg"=>1,
        "mp3"=>1,
        "txt"=>1,
        "avi"=>1
    );
    private $errores=array(
         0=>"Subida satisfactoria",
        101=>"Tamaño no permitido",
        201=>"Extensión no permitida",
        301=>"Destino incorrecto",
        401=>"Politica incorrecta"
        
    );
    
    function __construct($parametro) {
        
        if(isset($parametro) && $parametro["name"]!==""){
        $this->parametro=$parametro;
        //$this->extension= PATHINFO_EXTENSION($parametro["name"]);
        $this->extension=  pathinfo($parametro["name"])["extension"];
        $this->nombre = pathinfo($parametro["name"])["filename"];
        }
        else{$this->error=true;}
    }

    public function getDestino() {
        return $this->destino;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTamaño() {
        return $this->tamaño;
    }
    public function getExtension(){
        return $this->extension;
        
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTamaño($tamaño) {
        $this->tamaño = $tamaño;
    }
    public function getPolitica(){
        return $this->politica;
    }
    public function setPolitica($politica){
        $this->politica = $politica;
    }

        public function addTipo($tipo){
        if(!$this->isTipo($tipo)){
            $this->arrayDeTipos[$tipo]=1;
            return true;
        }
        return false;
    }
    public function removeTipo($tipo){
       if($this->isTipo($tipo)){
            unset($this->arrayDeTipos[$this]);
            return true;
        }
        return false;
    }
    public function isTipo($tipo){
        return isset($this->arrayDeTipos[$tipo]);
        
        
    }

    public function upload(){
      if($this->parametro["error"]!=UPLOAD_ERR_OK){
          $this->errorCode=$this->parametro["error"];
      }  
      if($this->parametro["size"]>$this->tamaño){
          $this->errorCode=101;
          return false;
          
      }
      if(!$this->isTipo($this->extension)){
          $this->errorCode=201;
          return false;
      }
      if(!is_dir($this->destino) && substr($this->destino, -1) === "/"){
          $this->errorCode=301;
          return false;
      }
      if($this->politica===self::CONSERVAR && file_exists($this->destino.$this->nombre.".".$this->extension)){
          $this->errorCode=401;
          return false;
      }
      
      $nombre = $this->nombre;
      if($this->politica===self::RENOMBRAR && file_exists($this->destino.$this->nombre.".".$this->extension)){
          $nombre= $this->renombrar($nombre);
          
      }
      if($this->politica===self::REEMPLAZAR && file_exists($this->destino.$this->nombre.".".$this->extension)){
          
      }
      $this->errorCode=0;
      return move_uploaded_file($this->parametro["tmp_name"],$this->destino.$nombre.".".$this->extension);
      
      
      }
    
      
        static function cambiarArray($array){
          $files=array();
          $filas=count($array["name"]);
          
          for($i=0;$i<$filas;$i++){
              $files[$i]['name']=$array['name'][$i];
              $files[$i]['type']=$array['type'][$i];
              $files[$i]['tmp_name']=$array['tmp_name'][$i];
              $files[$i]['error']=$array['error'][$i];
              $files[$i]['size']=$array['size'][$i];
          }
          return $files;   
      }
      
      static function uploadMulti($param, $destino,$nombre){
          
          $result = array();
          if(isset($param['name'][0])){
          $cambioArray=self::cambiarArray($param);
          for($i=0;$i<count($cambioArray);$i++){
              $sube=new FileUpload($cambioArray[$i]);
              $sube->setDestino($destino);
              $sube->setNombre($nombre);
              $sube->setPolitica(FileUpload::REEMPLAZAR);
              $sube->upload();
              $error_subida = array($sube->getErrorCode(), $sube->getErrorMessage());
              array_push($result, $error_subida);
          }
          return $result;
      }
      else{
          $sube=new FileUpload($param);
          $sube->setDestino($destino);
          $sube->setNombre($nombre);
          $sube->setPolitica(FileUpload::REEMPLAZAR);
          $sube->upload();
          $error_subida = array($sube->getErrorCode(), $sube->getErrorMessage());
          array_push($result, $error_subida);
      }
      return result;
      }
    
      private function getErrorCode(){
          return $this->errorCode;
      }
      private function getErrorMessage(){
          return $this->errores[$this->errorCode];
      }
      
    private function renombrar($nombre){
        $i=1;
        while (file_exists($this->destino.$this->nombre."(".$i.")".".".$this->extension)){
            $i++;
        }
        return $nombre."(".$i.")";
    }
    
    private function reemplazar($nombre){
        
    }
    
    
    
}
