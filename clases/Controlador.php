<?php

class Controlador {
    protected $db, $gestorUsu, $gestorObra, $plantilla,$sesion;
  
  function __construct(){
        $this->db= new Database();
        $this->gestorUsu = new ManageUsuario($this->db);
        $this->gestorObra = new ManageObra($this->db);
        $this->plantilla = new Plantilla();
        $this->sesion=new Session();
  }
  
    function handle(){
        $op=Request::req("op");
        $metodo=$op;
    
        //Aquí le decimos si existe el metodo en la clase (podemos llamar a otra clase en lugar de this)
        if(method_exists($this, $metodo)){ 
                $this->$metodo();
        }else{
            $this->principal();
        }
   }
   
   function principal(){
        $pagina=$this->plantilla->get('_index');
         $datos=array(
           "registro"=>"",
           "login"=>"",
           "mensaje"=>"",
           "mensajelogin"=>""
           );
           $p=$this->plantilla->replace($datos,$pagina);
           echo $p;
    }
    function inicio(){
        $this->principal();
    }
    function registro(){
          $pagina=$this->plantilla->get('_index');
          $contenido=$this->plantilla->get('_formregistro');
            $datos=array(
           "registro"=>$contenido,
           "mensaje"=>"",
           "login"=>"",
           "mensajelogin"=>""
           );
        $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
    }
    function formlogin(){
          $pagina=$this->plantilla->get('_index');
          $contenido=$this->plantilla->get('_formlogin');
        $datos=array(
           "registro"=>"",
           "login"=>$contenido,
           "mensaje"=>"",
           "mensajelogin"=>""
           );
       $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
        
    }
    
    function insertar(){
        $em=Request::post("email");
        $c1=Request::post("clave");
        $c2=Request::post("clave2");
        $pagina=$this->plantilla->get('_index');
        $formuregistro=$this->plantilla->get('_formregistro');
        $usu=$this->gestorUsu->get($em);
            if(Request::post("email")){
                if(Filter::isEmail($em)){
                    if($c1==$c2){
                        if($usu->getEmail()!=null){
                             $contenido="Email repetido";
                             $datos=array(
                             "registro"=>$formuregistro,
                            "login"=>"",
                            "mensaje"=>$contenido,
                            "mensajelogin"=>""
                             );
                             $p=$this->plantilla->replace($datos,$pagina);
                             echo $p;
                         }
                        else{
                        
                            $fechaalta=date('Y-m-d');
                            $alias=$em;
                            $plant=Request::post('plantilla');
                            $usuario=new Usuario($em,sha1($c1),$alias,$fechaalta,$plant);
                            $rUsuario = $this->gestorUsu->insert($usuario);
                            $this->db->close();
                             $contenido="Registro completado. Identifíquese";
                             $datos=array(
                             "registro"=>"",
                             "login"=>"",
                             "mensaje"=>$contenido,
                             "mensajelogin"=>""
                             );
                            $p=$this->plantilla->replace($datos,$pagina);
                            echo $p;
                            }
                        }
    
                    else{
                         $contenido="Las claves no coinciden";
                         $datos=array(
                         "registro"=>$formuregistro,
                         "login"=>"",
                         "mensaje"=>$contenido,
                         "mensajelogin"=>""
                         );
                        $p=$this->plantilla->replace($datos,$pagina);
                        echo $p;
        
                        } 
                    }
                else{
                    $contenido="Email incorrecto";
                    $datos=array(
                   "registro"=>$formuregistro,
                   "login"=>"",
                   "mensaje"=>$contenido,
                   "mensajelogin"=>""
                    );
                   $p=$this->plantilla->replace($datos,$pagina);
                  echo $p;
                   }
               }                         
        else{
            $this->principal();
            }

     }
     
     function login(){
        $em= Request::post('email');
        $pas=Request::post('clave');
        $p=sha1($pas);
        $usuario=$this->gestorUsu->get($em);
        $e=$this->gestorObra->getListObrasEmail($this->sesion->get("usu"));
         $formsesion=$this->plantilla->get('_formsesion');
        $rid=$this->gestorUsu->getUsuarioTrue($em,$p);
        $ra=$this->gestorUsu->esAdmin($em, $p);
        if($rid==1){
            if(!$this->sesion->get("usu")){
                $this->sesion->set("usu",$em);
                }
            if($ra==0 && $rp==0){
                $paginaUno=$this->plantilla->get('_formmod');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pUno=$this->plantilla->replace($d,$paginaUno);
                
    
                $pagina=$this->plantilla->get('_usuario');
                $formusuario=$this->plantilla->get('_formusuario');
                $datos=array(
                "nombresesion"=>$this->sesion->get("usu"),
                "formmod"=>$pUno,
                "formsesion"=>$formsesion,
                "datos"=>$formusuario,
                "mensajemod"=>"",
                "mensajelogin"=>"",
                "mensajesubida"=>"",
                "contenidoobras"=>"",
                "mensajemodobra"=>"",
                "modificaobra"=>""
            );
                $p=$this->plantilla->replace($datos,$pagina);
                echo $p;
            }
            else if($ra==1){
                 $pagina=$this->plantilla->get('_admin');
                 $d=array(
                    "nombresesion"=>$this->sesion->get("usu"),
                    "formsesion"=>$formsesion,
                    "lista"=>"",
                    "activado"=>""
                    );
                $p=$this->plantilla->replace($d,$pagina);
                echo $p;
            }
        }
        else{
                $pagina=$this->plantilla->get('_index');
        
                 $datos=array(
                  "registro"=>"",
                  "login"=>"",
                  "mensaje"=>"",
                  "mensajelogin"=>"Datos incorrectos"
           );
           $p=$this->plantilla->replace($datos,$pagina);
           echo $p;
     }

}
    function cierrasesion(){
        $this->sesion->destroy();
        $this->principal();
    }
    function modificausuario(){
        $em=Request::post('email');
        $cla=Request::post('clave');
        $alias=Request::post('alias');
        $plantilla=Request::post('plantilla');
        $email=$this->sesion->get("usu");
        $usuario=$this->gestorUsu->get($email);
       if($em!=$usuario->getEmail()){
            $usuario->setEmail($em);
        }
        if(sha1($cla)!=$usuario->getClave()){
             $usuario->setClave(sha1($cla));
        }
        $usuario->setClave(sha1($cla));
        $usuario->setAlias($alias);
        $usuario->setPlantilla($plantilla);
        
        $this->gestorUsu->setEm($usuario, $email);
        $this->sesion->destroy();
        $pagina=$this->plantilla->get('_index');
        
         $datos=array(
           "registro"=>"",
           "login"=>"",
           "mensaje"=>"",
           "mensajelogin"=>"Datos modificados correctamente. Identifíquese"
           );
           $p=$this->plantilla->replace($datos,$pagina);
           echo $p;
        
    }
    function sube(){
        $s=$this->sesion->get("usu");
        $n=Request::post("nombre");
        $t=Request::post("tecnica");
        $obra=new Obra(null,$s,$n,$t);
        $e=$this->gestorObra->getListObrasEmail($this->sesion->get("usu"));
        $usuario=$this->gestorUsu->get($this->sesion->get("usu"));
        $i=$this->gestorObra->insert($obra);
        $fileUpload = new FileUpload($_FILES["imagen"]);
        $fileUpload->setNombre($n);
        $fileUpload->setDestino("imagenes/obras/");
        if($fileUpload->upload() && $i==1){
            $msn="Archivo subido con éxito";
        }
                $paginaUno=$this->plantilla->get('_formmod');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pUno=$this->plantilla->replace($d,$paginaUno);
                
    
                $pagina=$this->plantilla->get('_usuario');
                $formusuario=$this->plantilla->get('_formusuario');
                $formsesion=$this->plantilla->get('_formsesion');
                $datos=array(
                "nombresesion"=>$this->sesion->get("usu"),
                "formmod"=>$pUno,
                "formsesion"=>$formsesion,
                "datos"=>$formusuario,
                "mensajemod"=>"",
                "mensajelogin"=>"",
                "mensajesubida"=>"",
                "contenidoobras"=>"",
                "mensajemodobra"=>"",
                "modificaobra"=>""
            );
                $p=$this->plantilla->replace($datos,$pagina);
                echo $p;
        
    }
    function listaArtistas(){
        $lista = $this->gestorUsu->getListUsuArtistas();
        $paginaUno = $this->plantilla->get('_listaArtistas');
        $artistas="";
        foreach($lista as $key=>$value){
            $arti=str_replace('{alias}',$value->getAlias(), $paginaUno);
            $arti=str_replace('{email}',$value->getEmail(),$arti);
            $artistas.=$arti;
        }
        $pagina=$this->plantilla->get('_artistas');
    
        $datos=array(
         "contenidoartistas"=>$artistas
        );
    
        $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
    }
    function ver(){
        $em=Request::get("email");
        $e=$this->gestorObra->getListObrasEmail($em);
        $usuario=$this->gestorUsu->get($em);
        $paginaUno=$this->plantilla->get('_obras');
        $obras="";
        foreach($e as $key=>$value){
            
            $obrai=str_replace('{nombre}',$value->getNombre(), $paginaUno);
            $obrai=str_replace('{tecnica}',$value->getTecnica(),$obrai);
            $obras.=$obrai;
        }
         $pagina=$this->plantilla->get($usuario->getPlantilla());
    
        $datos=array(
         "autor"=>$usuario->getAlias(),
         "obras"=>$obras
        );
    
        $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
     
    }
    
    function misObras(){
        $lista = $this->gestorObra->getListObrasEmail($this->sesion->get("usu"));
         $usuario=$this->gestorUsu->get($this->sesion->get("usu"));
        $paginaUno = $this->plantilla->get('_listaObras');
        $obras="";
        foreach($lista as $key=>$value){
            $obri=str_replace('{nombre}',$value->getNombre(), $paginaUno);
            $obri=str_replace('{id_obra}',$value->getId_obra(),$obri);
            $obras.=$obri;
        }
        $paginaDos=$this->plantilla->get('_formmod');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pDos=$this->plantilla->replace($d,$paginaDos);
        
        
        $pagina=$this->plantilla->get('_usuario');
        $formusuario=$this->plantilla->get('_formusuario');
        $formsesion=$this->plantilla->get('_formsesion');
        $datos=array(
         "contenidoobras"=>$obras,
          "nombresesion"=>$this->sesion->get("usu"),
         "formmod"=>$pDos,
         "formsesion"=>$formsesion,
        "datos"=>$formusuario,
        "mensajemod"=>"",
         "mensajelogin"=>"",
         "mensajesubida"=>"",
        "modificaobra"=>"",
        "mensajemodobra"=>""
        );
        $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
    }
    function verModificarObra(){
        $o=Request::get("id_obra");
        $e=$this->gestorObra->get($o);
        $usuario=$this->gestorUsu->get($this->sesion->get("usu"));
        $paginaUno=$this->plantilla->get('_formmodObra');
                
                $campos="";
                $d=array(
                    "nombre"=>$e->getNombre(),
                    "tecnica"=>$e->getTecnica(),
                    "id_obra"=>$o
                    );
        $pUno=$this->plantilla->replace($d,$paginaUno);
        $paginaDos=$this->plantilla->get('_formmod');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pDos=$this->plantilla->replace($d,$paginaDos);
                
                $pagina=$this->plantilla->get('_usuario');
                $formusuario=$this->plantilla->get('_formusuario');
                $formsesion=$this->plantilla->get('_formsesion');
                $datos=array(
                "nombresesion"=>$this->sesion->get("usu"),
                "modificaobra"=>$pUno,
                "formmod"=>$pDos,
                "formsesion"=>$formsesion,
                "datos"=>$formusuario,
                "mensajemod"=>"",
                "mensajelogin"=>"",
                "mensajesubida"=>"",
                "contenidoobras"=>"",
                "mensajemodobra"=>""
            );
                $p=$this->plantilla->replace($datos,$pagina);
                echo $p;
        
        }
        function modificarObra(){
    
            $n=Request::post('nombre');
            $t=Request::post('tecnica');
            $i=Request::post('id_obra');
            $obra=$this->gestorObra->get($i);
            $lista = $this->gestorObra->getListObrasEmail($this->sesion->get("usu"));
            $formusuario=$this->plantilla->get('_formusuario');
            $formsesion=$this->plantilla->get('_formsesion');
            $obra->setNombre($n);
            $obra->setTecnica($t);
            $this->gestorObra->set($obra);
            $this->sesion->destroy();
            $fileUpload = new FileUpload($_FILES["imagen"]);
            $fileUpload->setNombre($n);
            $fileUpload->setDestino("imagenes/obras/");
            $fileUpload->upload();
           
             $usuario=$this->gestorUsu->get($this->sesion->get("usu"));
            $paginaDos=$this->plantilla->get('_formmod');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pDos=$this->plantilla->replace($d,$paginaDos);
            
            $pagina=$this->plantilla->get('_usuario');
            $datos=array(
             "registro"=>"",
             "nombresesion"=>$this->sesion->get("usu"),
             "formsesion"=>$formsesion,
             "datos"=>$formusuario,
             "formmod"=>$pDos,
             "login"=>"",
             "mensaje"=>"",
             "mensajesubida"=>"",
             "mensajemod"=>"",
             "contenidoobras"=>"",
             "modificaobra"=>"",
             "mensajemodobra"=>"Operación realizada correctamente."
           );
           $p=$this->plantilla->replace($datos,$pagina);
           echo $p;
            
        }
        function borrarObra(){
            $i=Request::get('id_obra');
            $obra=$this->gestorObra->delete($i);
            $usuario=$this->gestorUsu->get($this->sesion->get("usu"));
            $paginaDos=$this->plantilla->get('_formmod');
            $formusuario=$this->plantilla->get('_formusuario');
            $formsesion=$this->plantilla->get('_formsesion');
                
                $campos="";
                $d=array(
                    "email"=>$usuario->getEmail(),
                    "clave"=>$usuario->getClave(),
                    "alias"=>$usuario->getAlias(),
                    );
                 $pDos=$this->plantilla->replace($d,$paginaDos);
            
            $pagina=$this->plantilla->get('_usuario');
            $datos=array(
             "registro"=>"",
             "nombresesion"=>$this->sesion->get("usu"),
             "formsesion"=>$formsesion,
             "datos"=>$formusuario,
             "formmod"=>$pDos,
             "login"=>"",
             "mensaje"=>"",
             "mensajesubida"=>"",
             "mensajemod"=>"",
             "contenidoobras"=>"",
             "modificaobra"=>"",
             "mensajemodobra"=>"Operación realizada correctamente."
           );
           $p=$this->plantilla->replace($datos,$pagina);
           echo $p;
            
        }
        function listaadminusu(){
             $lista = $this->gestorUsu->getListUsuarios();
             $formsesion=$this->plantilla->get('_formsesion');
             $paginaUno=$this->plantilla->get('_listausuadmin');
             $usus="";
             foreach($lista as $key=>$value){
                $usui=str_replace('{email}',$value->getEmail(), $paginaUno);
                $usus.=$usui;
             }
             $pagina=$this->plantilla->get('_admin');
    
            $datos=array(
                "lista"=>$usus,
                "nombresesion"=>$this->sesion->get("usu"),
                "formsesion"=>$formsesion,
                "activado"=>""
            );
    
        $p=$this->plantilla->replace($datos,$pagina);
        echo $p;
     
        }
        function desactivar(){
            $email=Request::get('email');
            $usuario=$this->gestorUsu->get($email);
            $formsesion=$this->plantilla->get('_formsesion');
            $usuario->setActivo(0);
            $this->gestorUsu->set($usuario);
            $pagina=$this->plantilla->get('_admin');
    
            $datos=array(
                "lista"=>$usus,
                "nombresesion"=>$this->sesion->get("usu"),
                "formsesion"=>$formsesion,
                "activado"=>"Usuario desactivado"
            );
    
            $p=$this->plantilla->replace($datos,$pagina);
            echo $p;
     
        }
         function activar(){
            $email=Request::get('email');
            $usuario=$this->gestorUsu->get($email);
            $formsesion=$this->plantilla->get('_formsesion');
            $usuario->setActivo(1);
            $this->gestorUsu->set($usuario);
            $pagina=$this->plantilla->get('_admin');
    
            $datos=array(
                "lista"=>$usus,
                "nombresesion"=>$this->sesion->get("usu"),
                "formsesion"=>$formsesion,
                "activado"=>"Usuario activado"
            );
    
            $p=$this->plantilla->replace($datos,$pagina);
            echo $p;
     
        }
        function listaadminobras(){
             $lista = $this->gestorObra->getListObras();
             $formsesion=$this->plantilla->get('_formsesion');
             $paginaUno=$this->plantilla->get('_listausuobras');
             $obras="";
             foreach($lista as $key=>$value){
                $obrai=str_replace('{nombre}',$value->getNombre(), $paginaUno);
                $obrai=str_replace('{id_obra}',$value->getId_obra(), $obrai);
                $obras.=$obrai;
             }
             $pagina=$this->plantilla->get('_admin');
    
            $datos=array(
                "lista"=>$obras,
                "nombresesion"=>$this->sesion->get("usu"),
                "formsesion"=>$formsesion,
                "activado"=>""
            );
    
            $p=$this->plantilla->replace($datos,$pagina);
            echo $p;
     
        }
        function borraAdminObra(){
             $i=Request::get('id_obra');
             $o=$this->gestorObra->get($i);
             $obra=$this->gestorObra->delete($i);
             $formsesion=$this->plantilla->get('_formsesion');
              $pagina=$this->plantilla->get('_admin');
    
            $datos=array(
                "lista"=>"",
                "nombresesion"=>$this->sesion->get("usu"),
                "formsesion"=>$formsesion,
                "activado"=>"La obra ".$o->getNombre()." ha sido borrada correctamente"
            );
    
            $p=$this->plantilla->replace($datos,$pagina);
            echo $p;
        }
            
        }
        
    

