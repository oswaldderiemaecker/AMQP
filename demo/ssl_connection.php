<?php
/**
 *
 */
require_once(__DIR__ . '/config.php');

use AMQP\Connection;


$options = array(
    'ssl_options' => array(
        'cafile' => CA_PATH ,
        'local_cert' => CERT_PATH,
        'verify_peer' => true
    )
);

$conn = new Connection(AMQP_SSL_RESOURCE, $options);

register_shutdown_function(
    function() use ($conn)
    {
        $conn->close();
    }
);

while (true) {
}
