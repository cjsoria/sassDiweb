<?php
    session_cache_limiter( 'nocache' );
    header( 'Expires: ' . gmdate( 'r', 0 ) );
    header( 'Content-type: application/json' );

    $to         = 'contacto@cjsoria.tk';

    $email_template = 'simple.html';

    $subject    = strip_tags($_POST['subject']);
    $email      = strip_tags($_POST['email']);
    $phone      = strip_tags($_POST['phone']);
    $name       = strip_tags($_POST['name']);
    $message    = nl2br( htmlspecialchars($_POST['message'], ENT_QUOTES) );
    $result     = array();

    if(empty($name)){

        $result = array( 'response' => 'error', 'empty'=>'name', 'message'=>'<strong>Error!</strong>&nbsp; El nombre está vacío.' );
        echo json_encode($result );
        die;
    } 

    if(empty($email)){

        $result = array( 'response' => 'error', 'empty'=>'email', 'message'=>'<strong>Error!</strong>&nbsp; El correo está vacío.' );
        echo json_encode($result );
        die;
    } 

    if(empty($message)){

         $result = array( 'response' => 'error', 'empty'=>'message', 'message'=>'<strong>Error!</strong>&nbsp; El cuerpo del mensaje está vacío.' );
         echo json_encode($result );
         die;
    }
    
    $headers  = "From: " . $name . ' <' . $email . '>' . "\r\n";
    $headers .= "Reply-To: ". $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $templateTags =  array(
        '{{subject}}' => $subject,
        '{{email}}'=>$email,
        '{{message}}'=>$message,
        '{{name}}'=>$name,
        '{{phone}}'=>$phone
        );

    $templateContents = file_get_contents( dirname(__FILE__) . '/email-templates/'.$email_template);

    $contents =  strtr($templateContents, $templateTags);

    if ( mail( $to, $subject, $contents) ) {
        $result = array( 'response' => 'success', 'message'=>'<strong>Gracias!</strong>&nbsp; Tu correo ha sido enviado.' );
    } else {
        $result = array( 'response' => 'error', 'message'=>'<strong>Error!</strong>&nbsp; Error al enviar el correo.'  );
    }

    echo json_encode( $result );

    die;