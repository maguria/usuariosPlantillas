<?php

class Obra{
    private $id_obra,$email,$nombre,$tecnica;
    
    
   function __construct($id_obra=null, $email=null, $nombre=null, $tecnica=null) {
        $this->id_obra = $id_obra;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->tecnica = $tecnica;
    }
    function getId_obra() {
        return $this->id_obra;
    }

    function getEmail() {
        return $this->email;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getTecnica() {
        return $this->tecnica;
    }

    function setId_obra($id_obra) {
        $this->id_obra = $id_obra;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setTecnica($tecnica) {
        $this->tecnica = $tecnica;
    }

        
    function set($valores, $inicio=0){
        $i=0;
        foreach ($this as $indice => $valor) {
            $this->$indice=$valores[$i+$inicio];
            $i++;
        }
    }
    
    function getGenerico(){
        $array = array();
        foreach ($this as $indice => $valor) {
            $array[$indice]=$valor;
        }
        return $array;
    }
    
    public function __toString() {
        $r ="";
        foreach ($this as $key => $valor) {
            $r .= "$valor ";
        }
        return $r;
    }
    
    //Con este método, del tirón, leo el objeto entero. Lee todos los parámetros, y ya los tiene preparados
    function read(){
        foreach ($this as $key=> $valor) {
            $this->$key= Request::req($key);
        }
    }
}


