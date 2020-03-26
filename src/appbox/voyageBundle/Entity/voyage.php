<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
    private $nbrplacereser = 0;

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
    private $promo = false;

    /**
     * @var \String
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\Column(name="typed", type="string")
     */
    private $typed ="Normal";

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
     * @ORM\Column(name="newprix",type="float",nullable=true)
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
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $image;
    /**
     * @var string
     *
     * @ORM\Column(name="typepromo",type="string",nullable=true)
     */
    private $typepromo;
    /**
     * @ORM\Column(name="totalargentvoyage",type="integer")
     */
    private $totalargentvoyage;
    /**
     * @ORM\Column(name="lieu",type="string")
     */
    private $lieu;
    /**
     * @ORM\Column(name="minage",type="integer")
     */
    private $minage;
    /**
     * @ORM\Column(name="employee", type="integer")
     */
    private $employee;

    /**
     * @ORM\Column(name="nbevue", type="integer")
     */
    private $nbrvue;

    /**
     * @ORM\Column(name="nbrcommentaires", type="integer")
     */
    private $nbrcommentaires;

    /**
     * @ORM\Column(name="etat", type="string")
     */
    private $etat ;



    public function __construct(){
        $this->totalargentvoyage = 0;
        $this->nbrvue = 0;
        $this->nbrcommentaires = 0;
        $this->etat = "en cours";
    }
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
    public function increservation($qte){
        $this->nbrplacereser+=$qte;
    }
    public function decreservation($qte){
        $this->nbrplacereser-=$qte;
    }


    /**
     * Set totalargentvoyage
     *
     * @param integer $totalargentvoyage
     *
     * @return voyage
     */
    public function setTotalargentvoyage($totalargentvoyage)
    {
        $this->totalargentvoyage = $totalargentvoyage;

        return $this;
    }

    /**
     * Get totalargentvoyage
     *
     * @return integer
     */
    public function getTotalargentvoyage()
    {
        return $this->totalargentvoyage;
    }

    /**
     * Set employee
     *
     * @param integer $employee
     *
     * @return voyage
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return integer
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set nbrvue
     *
     * @param integer $nbrvue
     *
     * @return voyage
     */
    public function setNbrvue($nbrvue)
    {
        $this->nbrvue = $nbrvue;

        return $this;
    }

    /**
     * Get nbrvue
     *
     * @return integer
     */
    public function getNbrvue()
    {
        return $this->nbrvue;
    }

    /**
     * Set nbrcommentaires
     *
     * @param integer $nbrcommentaires
     *
     * @return voyage
     */
    public function setNbrcommentaires($nbrcommentaires)
    {
        $this->nbrcommentaires = $nbrcommentaires;

        return $this;
    }

    /**
     * Get nbrcommentaires
     *
     * @return integer
     */
    public function getNbrcommentaires()
    {
        return $this->nbrcommentaires;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return voyage
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }


    /**
     * Set typed
     *
     * @param string $typed
     *
     * @return voyage
     */
    public function setTyped($typed)
    {
        $this->typed = $typed;

        return $this;
    }

    /**
     * Get typed
     *
     * @return string
     */
    public function getTyped()
    {
        return $this->typed;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return voyage
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set minage
     *
     * @param integer $minage
     *
     * @return voyage
     */
    public function setMinage($minage)
    {
        $this->minage = $minage;

        return $this;
    }

    /**
     * Get minage
     *
     * @return integer
     */
    public function getMinage()
    {
        return $this->minage;
    }
}
