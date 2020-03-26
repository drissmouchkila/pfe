<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * route
 *
 * @ORM\Table(name="route")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\routeRepository")
 */
class route
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
     * @var int
     *
     * @ORM\Column(name="voyage", type="integer")
     */
    private $voyage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @var int
     *
     * @ORM\Column(name="numjour", type="integer")
     */
    private $numjour;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set voyage
     *
     * @param integer $voyage
     *
     * @return route
     */
    public function setVoyage($voyage)
    {
        $this->voyage = $voyage;

        return $this;
    }

    /**
     * Get voyage
     *
     * @return int
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return route
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return route
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set numjour
     *
     * @param integer $numjour
     *
     * @return route
     */
    public function setNumjour($numjour)
    {
        $this->numjour = $numjour;

        return $this;
    }

    /**
     * Get numjour
     *
     * @return int
     */
    public function getNumjour()
    {
        return $this->numjour;
    }
}
