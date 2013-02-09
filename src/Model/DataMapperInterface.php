<?php 

namespace Model;

interface DataMapperInterface
{
    public function persist($object);

    public function remove($object);
}
