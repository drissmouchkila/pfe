<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * personne_voiture
 *
 * @ORM\Table(name="personne_voiture")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\personne_voitureRepository")
 */
class personne_voiture
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
     * @ORM\Column(name="personne", type="integer")
     */
    private $personne;

    /**
     * @var int
     *
     * @ORM\Column(name="voiture", type="integer")
     */
    private $voiture;

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
     * Set personne
     *
     * @param integer $personne
     *
     * @return personne_voiture
     */
    public function setPersonne($personne)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return int
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set voiture
     *
     * @param integer $voiture
     *
     * @return personne_voiture
     */
    public function setVoiture($voiture)
    {
        $this->voiture = $voiture;

        return $this;
    }

    /**
     * Get voiture
     *
     * @return int
     */
    public function getVoiture()
    {
        return $this->voiture;
    }

    /**
     * Set paiement
     *
     * @param boolean $paiement
     *
     * @return personne_voiture
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

