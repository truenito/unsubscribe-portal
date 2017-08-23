<?php
  /*
   *  Configuraración y envío del correo para cancelar suscripción.
   */
  // Cuenta de correo que estará en el "From" del e-mail enviado.
  $from = 'Darse de baja <unsubscribe@dgcp.gob.do>';
  // Cuenta de correo que recibe el e-mail con los datos del formulario.
  $sendTo = 'Cancelar suscripción <cancelarsuscripcion@dgcp.gob.do>';
  // Sujeto del correo.
  $subject = 'Petición para darse de baja';
  // Campos del formulario y sus nombres.
  // Nombre del campo => Como aparece en el correo.
  $fields = array('name' => 'Nombre', 'email' => 'Email', 'message' => 'Mensaje', 'document-type' => 'Tipo de documento', 'document-id' => 'Numero de documento');
  // Mensaje a presentar si se envía el correo correctamente.
  $okMessage = "Se ha enviado correctamente!\n\n Puede que aún continue recibiendo correos mientras el sistema procesa su petición,\n\n Gracias.";
  // Mensaje a presentar si NO se envía el correo correctamente.
  $errorMessage = 'Hubo un error enviando la petición para darse de baja. Intente mas tarde!';

  try
  {
      if(count($_POST) == 0) throw new \Exception('El formulario está vacío');
      $emailText = "Nuevo mensaje para dar de baja una cuenta del Portal Transaccional Dominicano.\n\n=========================================\n\n";

      foreach ($_POST as $key => $value) {
          // Si el campo en el arreglo existe en el contenido enviado vía formulario, incluirlo en el correo.
          if (isset($fields[$key])) {
              $emailText .= "$fields[$key]: $value\n";
          }
      }

      $emailText .= "\n\n\n No responder a este mensaje,\nGracias.";

      // Los headers del correo, no necesario cuando se use SMTP.
      $headers = array('Content-Type: text/plain; charset="UTF-8";',
          'From: ' . $from,
          'Reply-To: ' . $from,
          'Return-Path: ' . $from,
      );

      // Enviando el correo.
      // @TODO: @truenito, cambiar metodo a envío a protocolo SMTP.
      mail($sendTo, $subject, $emailText, implode("\n", $headers));

      $responseArray = array('type' => 'success', 'message' => $okMessage);
  }

  catch (\Exception $e) {
      $responseArray = array('type' => 'danger', 'message' => $errorMessage);
  }

  // Si se pide vía AJAX, retornar JSON.
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      $encoded = json_encode($responseArray);

      header('Content-Type: application/json');
      echo $encoded;
  }
  // De lo contrario mostrar el mensaje.
  else {
      echo $responseArray['message'];
  }
?>
