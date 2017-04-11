<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * galerie
 *
 * @ORM\Table(name="galerie")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\galerieRepository")
 */
class galerie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

