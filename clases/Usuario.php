<?php

class Usuario{
    private $email, $clave, $alias, $fechaalta,$plantilla,$activo, $administrador, $personal;
    
    
    function __construct($email=null, $clave=null, $alias=null, $fechaalta=null, $plantilla=null,$activo=1, $administrador=0, $personal=0) {
        $this->email = $email;
        $this->clave = $clave;
        $this->alias = $alias;
        $this->fechaalta = $fechaalta;
        $this->activo = $activo;
        $this->administrador = $administrador;
        $this->personal = $personal;
        $this->plantilla=$plantilla;
    }

    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getAlias() {
        return $this->alias;
    }

    function getFechaalta() {
        $time=time();
        return date("Y-m-d ", $time);
        
    }

    function getActivo() {
        return $this->activo;
    }

    function getAdministrador() {
        return $this->administrador;
    }

    function getPersonal() {
        return $this->personal;
    }
    function getPlantilla(){
        return $this->plantilla;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setAlias($alias) {
        $this->alias = $alias;
    }

    function setFechaalta($fechaalta) {
        $this->fechaalta = $fechaalta;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function setAdministrador($administrador) {
        $this->administrador = $administrador;
    }

    function setPersonal($personal) {
        $this->personal = $personal;
    }
    function setPlantilla($plantilla){
        $this->plantilla=$plantilla;
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
    function getTrozo($email){
        $pos= strpos($email,"@");
        $cad=  substr($email, 0,$pos);
        return $cad;
    }
}


