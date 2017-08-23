$(function () {

    // Inicializar el validador por default.
    $('#contact-form').validator();

    // Cuando el formulario se envíe.
    $('#contact-form').on('submit', function (e) {

        // Si el validador no evita el envío.
        // @TODO: @truenito usar un validador de RNC y cedulas real.
        if (!e.isDefaultPrevented()) {
            var url = "cancelar.php";

            // POST los valores al script para enviar el correo.
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    // Recibimos el tipo de mensaje y preparamos la clase/texto para mostrar la respuesta.
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    // Creamos el mensaje de alerta según los datos generados.
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    // Si los datos fueron generados correctamente, mostramos la respuesta.
                    if (messageAlert && messageText) {
                        // Adicionamos el alertbox al div de mensajes.
                        $('#contact-form').find('.messages').html(alertBox);
                        // Reseteamos el formulario.
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});
