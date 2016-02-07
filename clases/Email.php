<?php


class Email {
    private $cliente, $origen, $destino, $alias, $asunto, $mensaje;
    
    function __construct($destino=null,  $asunto=null, $mensaje=null, $origen=null, $alias=null) {
        $this->cliente = new Google_Client();
        $this->cliente->setApplicationName('correo');
        $this->cliente->setClientId('750166320318-drg7l14mcm8qtv254jhb84sbqik6jm29.apps.googleusercontent.com');
        $this->cliente->setClientSecret('KH9Qp0R4jfqco_qKNo0Fx64t');
        $this->cliente->setRedirectUri('C:\xampp\htdocs\usuariosCorreo\oauth\guardar.php');
        $this->cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
        $this->cliente->setAccessToken(file_get_contents('C:\xampp\htdocs\usuariosCorreo\oauth\token.conf'));
        $this->origen= $origen;
        $this->destino = $destino;
        $this->alias = $alias;
        $this->asunto = $asunto;
        $this->mensaje= $mensaje;
    }
    
    function getOrigen() {
        return $this->origen;
    }

    function getDestino() {
        return $this->destino;
    }

    function getAsunto() {
        return $this->asunto;
    }

    function getMensaje() {
        return $this->mensaje;
    }


    function setOrigen($origen) {
        $this->origen=$origen;
    }

    function setDestino($destino) {
        $this->destino = $destino;
    }

    function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }

    function enviar(){
        if ($this->cliente->getAccessToken()) {
            $service = new Google_Service_Gmail($this->cliente);
            try {
                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->From = Contants::FROM;
                $mail->FromName = Contants::ALIAS;
                $mail->AddAddress($this->destino);
                $mail->AddReplyTo(Contants::FROM, Contants::ALIAS);
                $mail->Subject = $this->asunto;
                $mail->Body = $this->mensaje;
                $mail->preSend();
                $mime = $mail->getSentMIMEMessage();
                $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
                $mensaje = new Google_Service_Gmail_Message();
                $mensaje->setRaw($mime);
                $service->users_messages->send('me', $mensaje);
                $r = 1;
            } catch (Exception $e) {
                print($e->getMessage());
                $r = 0;
            }
        }else{
             $r = -1;
        }
        return $r;
    }

}

?>
