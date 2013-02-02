<?php 

namespace Model;

interface DataMapperInterface
{
    public function persist(Location $location);

    public function remove(Location $location);
}
