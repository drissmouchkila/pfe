<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Tests\StringableObject;

/**
 * voyage
 *
 * @ORM\Table(name="voyage")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\voyageRepository")
 */
class voyage
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
     * @ORM\Column(name="nbrplacetotal", type="integer")
     */
    private $nbrplacetotal;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrplacereser", type="integer")
     */
    private $nbrplacereser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="datetime")
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="datetime")
     */
    private $datefin;

    /**
     * @var \String
     *
     * @ORM\Column(name="promo", type="boolean")
     */
    private $promo;

    /**
     * @var \String
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \int
     *
     * @ORM\Column(name="nbrRoute",type="integer")
     */
    private $nbrRoute;

    /**
     * @var \String
     *
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var \String
     *
     * @ORM\Column(name="destination",type="string")
     */
    private $destination;

    /**
     * @var float
     *
     * @ORM\Column(name="prix",type="float")
     */
    private $prix;

    /**
     * @var float
     *
     * @ORM\Column(name="newprix",type="float")
     */
     private $newprix;

    /**
     * @var String
     *
     * @ORM\Column(name="titre",type="string")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\column(name="image",type="string")
     */
    private $image;
    /**
     * @var string
     *
     * @ORM\Column(name="typepromo",type="string",nullable=false)
     */
    private $typepromo;
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
     * Set nbrplacetotal
     *
     * @param integer $nbrplacetotal
     *
     * @return voyage
     */
    public function setNbrplacetotal($nbrplacetotal)
    {
        $this->nbrplacetotal = $nbrplacetotal;

        return $this;
    }

    /**
     * Get nbrplacetotal
     *
     * @return int
     */
    public function getNbrplacetotal()
    {
        return $this->nbrplacetotal;
    }

    /**
     * Set nbrplacereser
     *
     * @param integer $nbrplacereser
     *
     * @return voyage
     */
    public function setNbrplacereser($nbrplacereser)
    {
        $this->nbrplacereser = $nbrplacereser;

        return $this;
    }

    /**
     * Get nbrplacereser
     *
     * @return int
     */
    public function getNbrplacereser()
    {
        return $this->nbrplacereser;
    }

    /**
     * Set datedebut
     *
     * @param \DateTime $datedebut
     *
     * @return voyage
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    /**
     * Get datedebut
     *
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * Set datefin
     *
     * @param \DateTime $datefin
     *
     * @return voyage
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * Get datefin
     *
     * @return \DateTime
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * Set promo
     *
     * @param boolean $promo
     *
     * @return voyage
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return boolean
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return voyage
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
     * Set nbrRoute
     *
     * @param integer $nbrRoute
     *
     * @return voyage
     */
    public function setNbrRoute($nbrRoute)
    {
        $this->nbrRoute = $nbrRoute;

        return $this;
    }

    /**
     * Get nbrRoute
     *
     * @return integer
     */
    public function getNbrRoute()
    {
        return $this->nbrRoute;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return voyage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return voyage
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return voyage
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set newprix
     *
     * @param float $newprix
     *
     * @return voyage
     */
    public function setNewprix($newprix)
    {
        $this->newprix = $newprix;

        return $this;
    }

    /**
     * Get newprix
     *
     * @return float
     */
    public function getNewprix()
    {
        return $this->newprix;
    }

    /**
     * Set titre
     *
     * @param float $titre
     *
     * @return voyage
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return float
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return voyage
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set typepromo
     *
     * @param string $typepromo
     *
     * @return voyage
     */
    public function setTypepromo($typepromo)
    {
        $this->typepromo = $typepromo;

        return $this;
    }

    /**
     * Get typepromo
     *
     * @return string
     */
    public function getTypepromo()
    {
        return $this->typepromo;
    }
}