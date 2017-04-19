<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * personne_voyage
 *
 * @ORM\Table(name="personne_voyage")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\personne_voyageRepository")
 */
class personne_voyage
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
     * @ORM\Column(name="personne", type="integer")
     */
    private $personne;

    /**
     * @var bool
     *
     * @ORM\Column(name="paiement", type="boolean")
     */
    private $paiement;


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
     * @return personne_voyage
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
     * Set personne
     *
     * @param string $personne
     *
     * @return personne_voyage
     */
    public function setPersonne($personne)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return string
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set paiement
     *
     * @param boolean $paiement
     *
     * @return personne_voyage
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return bool
     */
    public function getPaiement()
    {
        return $this->paiement;
    }
}

