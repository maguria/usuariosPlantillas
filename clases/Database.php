<?php

/*
 Metodos:
 Constructor;
 SentenciaSql/query/insert/update/delete;
 Cerrar;
 getCount;
 getID;
 getRow;
 */
 
class Database {
    private $conexion, $consulta;
    
    function __construct() {
        try {
    $this->conexion = new PDO(
            'mysql:host=' . Contants::SERVER . ';' .
            'dbname=' . Contants::DATABASE, Contants::DBUSER,  Contants::DBPASS, array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
    } catch (PDOException $e) {
    //Esta parte hay que trabajarla
    var_dump($e);
    echo 'no se ha podido conectar';
    exit();
    }
    }
    
    function close(){
        $this->conexion = null;
    }
    
    function getCount(){
        return $this->consulta->rowCount();
    }
    
    function getID(){
        return $this->conexion->lastInsertId();
    }
    
    function getErrorConection(){
        return $this->conexion->errorInfo();
    }
    
    function getErrorSql(){
        return $this->consulta->errorInfo();
    }
    
    function send($sql, $parametros=array()){
        $this->consulta = $this->conexion->prepare($sql);
        foreach ($parametros as $nombreParametro => $valorParametro) {
            $this->consulta->bindValue($nombreParametro, $valorParametro);
        }
        return $this->consulta->execute();
    }
    
    function getRow(){
        $r = $this->consulta->fetch();
        if($r===null){
            $this->consulta->closeCursor();
        }
        return $r;
    }
    //Para saber el número de registros que tengo en mi tabla
    function count($tabla, $condicion="1=1", $parametros=array()){
        $sql="select count(*) from $tabla where $condicion";
        $this->send($sql, $parametros);
        $fila=$this->getRow();
        return $fila[0];
    }
    
    function erase($table, $condicion, $parametros = array()){
        //delete from TABLA where CONDICION
        $sql = "delete from $table where $condicion";
        if($this->send($sql, $parametros)){
            return $this->getCount();
        }
        return false;
    }
    
    function delete($table, $parametros = array() ){
        $camposWhere = "";
        foreach ($parametros as $nombreParametro => $valorParametros) {
            $camposWhere .= $nombreParametro. " = :". $nombreParametro. " and ";
        }
        $camposWhere = substr($camposWhere,0, -4);
        //$camposWhere .= "1 = 1";
        $sql = "delete from $table where $camposWhere";
        if($this->send($sql, $parametros)){
            return $this->getCount();
        }
    }
    
    
    function insert($table, $parametros=array(), $auto=true){
        //insert into TABLA values (VALORES)
        //insert into TABLA(CAMPOS) values (VALORES)
       
        $campos = "";
        $valores ="";
        foreach ($parametros as $nombreParametro => $valorParametros) {
            $campos .= $nombreParametro. ",";
            $valores .= ":". $nombreParametro. ",";
        }
        $campos = substr($campos, 0, -1);
        $valores = substr($valores, 0, -1);
         $sql = "insert into $table ($campos) values ($valores)";
         if($this->send($sql, $parametros)){
             if($auto){
                 return $this->getID();
             }
             return $this->getCount();
         }
         return "-1";
    }
    
    function update($table, $parametrosSet=array(), $parametrosWhere = array()){
        //update TABLA set VALORES where CONDICION
        //update TABLA set c1=:c1, c2=:c2 where c1=:wc1 and c3=:wc3
        $camposSet = "";
        $camposWhere = "";
        $parametros = array();
        foreach ($parametrosSet as $nombreParametro => $valorParametros) {
            $camposSet .= $nombreParametro. " = :". $nombreParametro. ",";
            $parametros[$nombreParametro]= $valorParametros;
        }
        $camposSet = substr($camposSet, 0, -1);
        foreach ($parametrosWhere as $nombreParametro => $valorParametros) {
            $camposWhere .= $nombreParametro. " = :_". $nombreParametro. " and ";
            $parametros["_".$nombreParametro]= $valorParametros;
        }
        $camposWhere = substr($camposWhere,0, -4);
        //$camposWhere .= "1 = 1";
        $sql = "update $table set $camposSet where $camposWhere";
        if($this->send($sql, $parametros)){
            return $this->getCount();
        }
        return "-1";
    }
    
    function query($table, $proyeccion="*", $parametros=array(), $orden="1", $limite=""){
        //select CAMPOS from TABLA where CONDICION order by ORDEN LIMIT
        //select c1,c2 form  TABLA where c3=:c3 and c4=:c4 order by c2 desc,c1 limit 8,15
        $campos = "";
        foreach ($parametros as $nombreParametro => $valorParametros) {
            $campos .= $nombreParametro. " = :". $nombreParametro. " and ";
        }
        $campos .= "1 = 1";
        //$campos = substr($campos,0, -4);
        $limit = "";
        if($limite!==""){
            $limit = "limit $limite";
        }
        $sql = "select $proyeccion from $table where $campos order by $orden $limit";
        
        return $this->send($sql, $parametros);    
        
        }
        
        function select($table, $proyeccion="*", $condicion= "1=1", $parametros=array(), $orden="1", $limite=""){
            $limit = "";
            if($limite!==""){
                $limit = "limit $limite";
            }
            $sql = "select $proyeccion from $table where $condicion order by $orden $limit";
            return $this->send($sql, $parametros);    
        }
        
        
}


