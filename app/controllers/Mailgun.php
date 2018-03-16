<?php
namespace App\Controllers;

use \Interop\Container\ContainerInterface as ContainerInterface;
use \Http\Client\Exception\HttpException;

class Mailgun
{
	protected $container;

	// constructor receives container instance
	public function __construct(ContainerInterface $container) {
	   $this->container = $container;
	}

	public function home($request, $response, $args) {
	    // your code
	    //$this->container->logger->info("Mailgun Controller is working");
	    $args['name'] = ' - Mailgun is working';
	    return $this->container->renderer->render($response, 'mailgun-form.phtml', $args);
	}

	/**
	* Sending a simple message
	*/
	public function send($request, $response, $args) {
		try {
	    	if ($request->getMethod() == 'POST')
	    	{
	    		$httpClient = new \GuzzleHttp\Client([
				    'verify' => false,
				]);
				$httpAdapter = new \Http\Adapter\Guzzle6\Client($httpClient);

			    $mailgun = new \Mailgun\Mailgun('YOUR_API_KEY', $httpAdapter);
				$domain = $request->getParam('domain');

				$args = array(
				    'from'    => $request->getParam('from'),
				    'to'      => $request->getParam('to'),
				    'subject' => $request->getParam('subject'),
				    'html'    => $request->getParam('message') // text or html type
				);
				if ($request->getParam('cc'))
				{
					$args['cc'] = $request->getParam('cc');
				}
				if ($request->getParam('bcc'))
				{
					$args['bcc'] = $request->getParam('bcc');
				}

				# Make the call to the client.
				$result = $mailgun->sendMessage($domain, $args);
	    		
				// $args['id'] = $result->http_response_body->id;
				// $args['message'] = $result->http_response_body->message;
				// $args['http_response_code'] = $result->http_response_body->http_response_code;

		    	return $response->withJson($result);
	    	}
	    	else
	    	{
	    		return $response->withStatus(401)->withJson('Method not allowed.');
	    	}
		} catch (HttpException $e) {
		    return $response->withStatus(400)->withJson($e->getMessage());
		} catch (Exception $e) {
			return $response->withStatus(500)->withJson($e->getMessage());
		} 
	}
}