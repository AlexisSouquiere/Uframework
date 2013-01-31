<?php

namespace Http;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class JsonResponse extends Response
{
    public function __construct($content, $statusCode = 200)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $content = $serializer->serialize($content, 'json');

        parent::__construct($content, $statusCode, ['Content-Type' => 'application/json']);
    }
}
