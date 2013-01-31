<?php

namespace Model;

interface PersistenceInterface
{
    /**
     * @param string $name Name of the new location
     *
     * @return int Id of the new location
     */
    public function create($name);

    public function update($id, $name);

    public function delete($id);
}
