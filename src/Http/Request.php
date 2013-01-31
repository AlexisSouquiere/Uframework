<?php

namespace Http;

class Request
{
    const GET    = 'GET';

    const POST   = 'POST';

    const PUT    = 'PUT';

    const DELETE = 'DELETE';

    private $parameters;

    function __construct(array $query = array(), array $request = array())
    {
        $this->parameters = array_merge($query, $request);
    }

    public static function createFromGlobals()
    {
        if(isset($_SERVER['CONTENT_TYPE'])  && 
           $_SERVER['CONTENT_TYPE'] === 'application/json') {
                $data    = file_get_contents('php://input');
                $request = @json_decode($data, true);
                $request = array_merge($request, array("_method" => $_SERVER['REQUEST_METHOD']));
        }
        else 
            $request = $_POST;
               
        return new self($_GET, $request);
    }
    
    public function guessBestFormat()
    {
	$negotiator   = new \Negotiation\FormatNegotiator();

	$acceptHeader = $_SERVER['HTTP_ACCEPT'];
	$priorities   = array('html', 'json', '*/*');

	return $negotiator->getBest($acceptHeader, $priorities);
    }

    public function getParameter($name, $default = null)
    {
            return $this->parameters[$name];
    }

    public function getMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
        if (self::POST === $method) {
            return $this->getParameter('_method', $method);
        }
        return $method;
    }

    public function getUri()
    {
        $uri =  isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }
}
