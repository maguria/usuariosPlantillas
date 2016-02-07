<?php

class ManageObra {
    private $bd = null;
    private $tabla = "obra";
    
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    
    function get($id_obra){
        $parametros = array();
        $parametros['id_obra']=$id_obra;
        $this->bd->select($this->tabla, "*", "id_obra = :id_obra",$parametros );
        $row = $this->bd->getRow();
        $obra = new Obra();
        $obra->set($row);
        return $obra;
    }
    
    function delete($id_obra){
        $parametros=array();
        $parametros["id_obra"]=$id_obra;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    function deleteObra($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
   
    function erase(Obra $obra){
        return $this->delete($obra->getId_obra());
    }
    
    function set(Obra $obra){
        $parametrosWhere=array();
        $parametrosWhere["id_obra"]=$obra->getId_obra();
        return $this->bd->update($this->tabla, $obra->getGenerico(), $parametrosWhere);
    }
   
    function insert(Obra $obra){
        //inserta un objeto y devuelve el ID
        return $this->bd->insert($this->tabla, $obra->getGenerico());
    }
   
    
    function count($condicion="1=1", $parametros=array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
    
    function getListObras($pagina=1,$orden="",$nrpp=Contants::NRPP){
        
        $ordenPredeterminado="$orden, email";
        if($orden==="" || $orden===null){
             $ordenPredeterminado="email";
        }
      
        $registroInicial=($pagina-1)*$nrpp;
        $this->bd->select($this->tabla, "*", "1=1", array(), $ordenPredeterminado,"$registroInicial,$nrpp");
        $r=array();
        
        while($row = $this->bd->getRow()){
            $obra = new Obra();
            $obra->set($row);
            $r[]=$obra;
        }
        return $r;
    }
     function getListObrasEmail($email){
    
        $parametros["email"]=$email;
        $this->bd->select($this->tabla, "*", "email=:email", $parametros,"email");
        $r=array();
        
        while($row = $this->bd->getRow()){
            $obra = new Obra();
            $obra->set($row);
            $r[]=$obra;
        }
        return $r;
    }
    
    function getListInnerAutor(){
        
        $parametros=array();
        $sql="select usu.*, ob.* from usuario usu inner join obra ob on usu.email=ob.email"; 
        $this->bd->send($sql, $parametros);
        $r=array();
        $contador=0;
        
        while($row = $this->bd->getRow()){
            $obra = new Obra();
            $obra->set($row);
            $usuario=new Usuario();
            $usuario->set($row,4);
            
            $r[$contador]["obra"]=$obra;
            $r[$contador]["usuario"]=$usuario;
            $contador++;
        }
        return $r;
        
    }
    
        
    
    function getValueSelect(){
        //$table, $proyeccion="*", $parametros=array(), $orden="1", $limite=""
        $this->bd->query($this->tabla, "email", array(), "email");
        $array =array();
        while ($row=  $this->bd->getRow()){
            $array[$row[0]]=$row[1];
        }
        return $array;
    }

}

?>