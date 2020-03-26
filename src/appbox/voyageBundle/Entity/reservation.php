<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\reservationRepository")
 */
class reservation
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
     * @var int
     *
     * @ORM\Column(name="user", type="integer")
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="paiement", type="string")
     */
    private $paiement;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(name="datedebutvoyage", type="date")
     */
    private $datedebutvoyage;
    


    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text")
     */
    private $address;
    /**
     * @var string
     *
     * @ORM\Column(name="typepaiement", type="string", length=255)
     */
    private $typepaiement;
    /**
     * @ORM\Column(name="nbrpersonne", type="integer")
     */
    private $nbrpersonne;
    /**
     * @ORM\Column(name="email", type="string")
     */
    private $email;
    /**
     * @var float
     *
     * @ORM\Column(name="telephone", type="float")
     */
    private $telephone;

    /**
     * @ORM\Column(name="datemettrereservation", type="datetime", nullable=true)
     */
    private $datemettreres;
    /**
     * @ORM\Column(type="string",nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $brochure;
    public function __construct(){
        $this->paiement="Non";
        $this->datemettreres= new \DateTime();
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
     * Set voyage
     *
     * @param integer $voyage
     *
     * @return reservation
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
     * Set user
     *
     * @param integer $user
     *
     * @return reservation
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set paiement
     *
     * @param boolean $paiement
     *
     * @return reservation
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

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return reservation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return reservation
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set pys
     *
     * @param string $pys
     *
     * @return reservation
     */
    public function setPys($pys)
    {
        $this->pys = $pys;

        return $this;
    }

    /**
     * Get pys
     *
     * @return string
     */
    public function getPys()
    {
        return $this->pys;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return reservation
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return reservation
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return reservation
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set telephone
     *
     * @param float $telephone
     *
     * @return reservation
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return float
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set datemettreres
     *
     * @param \DateTime $datemettreres
     *
     * @return reservation
     */
    public function setDatemettreres($datemettreres)
    {
        $this->datemettreres = $datemettreres;

        return $this;
    }

    /**
     * Get datemettreres
     *
     * @return \DateTime
     */
    public function getDatemettreres()
    {
        return $this->datemettreres;
    }

    /**
     * Set nbrpersonne
     *
     * @param integer $nbrpersonne
     *
     * @return reservation
     */
    public function setNbrpersonne($nbrpersonne)
    {
        $this->nbrpersonne = $nbrpersonne;

        return $this;
    }

    /**
     * Get nbrpersonne
     *
     * @return integer
     */
    public function getNbrpersonne()
    {
        return $this->nbrpersonne;
    }
    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }

    /**
     * Set typepaiement
     *
     * @param string $typepaiement
     *
     * @return reservation
     */
    public function setTypepaiement($typepaiement)
    {
        $this->typepaiement = $typepaiement;

        return $this;
    }

    /**
     * Get typepaiement
     *
     * @return string
     */
    public function getTypepaiement()
    {
        return $this->typepaiement;
    }

    /**
     * Set datedebutvoyage
     *
     * @param \DateTime $datedebutvoyage
     *
     * @return reservation
     */
    public function setDatedebutvoyage($datedebutvoyage)
    {
        $this->datedebutvoyage = $datedebutvoyage;

        return $this;
    }

    /**
     * Get datedebutvoyage
     *
     * @return \DateTime
     */
    public function getDatedebutvoyage()
    {
        return $this->datedebutvoyage;
    }

    /**
     * Set brochure
     *
     * @param string $brochure
     *
     * @return reservation
     */
    public function setBrochure($brochure)
    {
        $this->brochure = $brochure;

        return $this;
    }

    /**
     * Get brochure
     *
     * @return string
     */
    public function getBrochure()
    {
        return $this->brochure;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
