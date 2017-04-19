<?php
namespace appbox\voyageBundle\Entity;

class rechercheV{
    private $nom;
    private $destination;
    private $type;

    public function getNom(){
        return $this->nom;
    }
    public function setNom($nom){
        $this->nom=$nom;
    }
    public function getDestination(){
        return $this->destination;
    }
    public function setDestination($des){
        $this->destination=$des;
    }
    public function getType(){
        return $this->type;
    }
    public function setType($type){
        $this->type=$type;
    }
}