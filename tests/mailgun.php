<?php
require "../../vendor/autoload.php";


use Mailgun\Mailgun;
//$mailgun = new Mailgun('key-1563898f3db05812311b76f9658279b9', new \Http\Adapter\Guzzle6\Client());

# Instantiate the client.
$mailgun = new Mailgun('key-1563898f3db05812311b76f9658279b9');
$domain = "visualy.com";

# Make the call to the client.
$result = $mailgun->sendMessage($domain, array(
    'from'    => 'Excited User <mailgun@visualy.com.br>',
    'to'      => 'Baz <fabio.gonzagabr@gmail.com>',
    'subject' => 'Hello',
    'text'    => 'Testing some Mailgun awesomness!'
));

var_dump( $result );