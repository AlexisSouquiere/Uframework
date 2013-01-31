<?php

namespace Model;

use Dal\Connection;

class Locations implements FinderInterface, PersistenceInterface
{
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }
 
    private function getCities()
    {      
        $json = file_get_contents(__DIR__.'/../../data/locations.json');
        $cities = json_decode($json, true);
        return $cities;
    }

    private function saveCities($cities)
    {
        $file = __DIR__.'/../../data/locations.json';
        file_put_contents($file, json_encode(array_values($cities)));
    }

    /*function createLocation($row)
    {
         $date = null;
         if(false === empty($row['CREATED_AT'])) {
              $date = new DateTime($row['CREATED_AT']);
         }
         return new Location($row['ID'], $row['NAME'], $date);
    }*/

    public function findAll()
    {
        $query = 'SELECT * FROM LOCATIONS';
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $result = array_map(function($row)
      {
           $date = null;
           if(isset($row['CREATED_AT'])) {
                $date = new \DateTime($row['CREATED_AT']);
           }    
           return new Location($row['ID'], $row['NAME'], $date);
      }, $result);
        return $result;
   } 

    public function findOneById($id)
    {
        $query = 'SELECT * FROM LOCATIONS WHERE ID = :id';
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $date = null;
        if(false === empty($row[0]['CREATED_AT'])) {
            $date = new \DateTime($row['CREATED_AT']);
        }

        if(false === empty($result)) {
           return new Location($result[0]['ID'], $result[0]['NAME'], $date);
        } 
        return null;
    }

    public function create($name)
    {
        $cities = $this->getCities();
        $max = 0;
        foreach($cities as $city_id => $city)
        {
           if($max < $city["id"]) 
              $max = $city["id"];            
        }
        $id = $max + 1;
        array_push($cities, array("id" => "$id", "name" => $name));
        $this->saveCities($cities);
        
        return $id;
    }

    public function update($id, $name)
    {
        $cities = $this->getCities();
        foreach ($cities as $city_id => $city)
        {
            if($id == $city["id"]) {
                 $cities[$city_id]["name"] = $name;
                 break;
            }
        }
        $this->saveCities($cities);
    }

    public function delete($id)
    {
        $cities = $this->getCities();
        foreach ($cities as $index => $city)
        {
             if($id === $city["id"]) {
                unset($cities[$index]);
                break;
             }
        }
        $this->saveCities($cities);
    }
}
