<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Employe
 *
 * @ORM\Table(name="employe")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\EmployeRepository")
 */
class Employe
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255)
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrvoyage", type="string", length=255)
     */
    private $nbrvoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrbloc", type="string", length=255)
     */
    private $nbrbloc;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $image;
    /**
     * @ORM\Column(name="datederecr", type="date")
     */
    private $datederrecur;
    /**
     * @ORM\Column(name="facebookUrl", type="string", length=255)
     */
    private $facebbookUrl;
    /**
     * @ORM\Column(name="twitterUrl", type="string", length=255)
     */
    private $twitterURL;
    /**
     * @ORM\Column(name="googleUrl", type="string", length=255)
     */
    private $googleURL;
    /**
     * @ORM\Column(name="LinkdenUrl", type="string", length=255)
     */
    private $LinkdenURL;
    /**
     * @ORM\Column(name="UserId", type="integer")
     */
    private $UserId;
    /**
     * @ORM\Column(name="totalargent", type="integer")
     */
    private $totalargent;
    /**
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat = 'en travail';
    /**
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;



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
     * Set name
     *
     * @param string $name
     *
     * @return Employe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Employe
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Employe
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set nbrvoyage
     *
     * @param string $nbrvoyage
     *
     * @return Employe
     */
    public function setNbrvoyage($nbrvoyage)
    {
        $this->nbrvoyage = $nbrvoyage;

        return $this;
    }

    /**
     * Get nbrvoyage
     *
     * @return string
     */
    public function getNbrvoyage()
    {
        return $this->nbrvoyage;
    }

    /**
     * Set nbrbloc
     *
     * @param string $nbrbloc
     *
     * @return Employe
     */
    public function setNbrbloc($nbrbloc)
    {
        $this->nbrbloc = $nbrbloc;

        return $this;
    }

    /**
     * Get nbrbloc
     *
     * @return string
     */
    public function getNbrbloc()
    {
        return $this->nbrbloc;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Employe
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
     * Set datederrecur
     *
     * @param \DateTime $datederrecur
     *
     * @return Employe
     */
    public function setDatederrecur($datederrecur)
    {
        $this->datederrecur = $datederrecur;

        return $this;
    }

    /**
     * Get datederrecur
     *
     * @return \DateTime
     */
    public function getDatederrecur()
    {
        return $this->datederrecur;
    }

    /**
     * Set facebbookUrl
     *
     * @param string $facebbookUrl
     *
     * @return Employe
     */
    public function setFacebbookUrl($facebbookUrl)
    {
        $this->facebbookUrl = $facebbookUrl;

        return $this;
    }

    /**
     * Get facebbookUrl
     *
     * @return string
     */
    public function getFacebbookUrl()
    {
        return $this->facebbookUrl;
    }

    /**
     * Set twitterURL
     *
     * @param string $twitterURL
     *
     * @return Employe
     */
    public function setTwitterURL($twitterURL)
    {
        $this->twitterURL = $twitterURL;

        return $this;
    }

    /**
     * Get twitterURL
     *
     * @return string
     */
    public function getTwitterURL()
    {
        return $this->twitterURL;
    }

    /**
     * Set googleURL
     *
     * @param string $googleURL
     *
     * @return Employe
     */
    public function setGoogleURL($googleURL)
    {
        $this->googleURL = $googleURL;

        return $this;
    }

    /**
     * Get googleURL
     *
     * @return string
     */
    public function getGoogleURL()
    {
        return $this->googleURL;
    }

    /**
     * Set linkdenURL
     *
     * @param string $linkdenURL
     *
     * @return Employe
     */
    public function setLinkdenURL($linkdenURL)
    {
        $this->LinkdenURL = $linkdenURL;

        return $this;
    }

    /**
     * Get linkdenURL
     *
     * @return string
     */
    public function getLinkdenURL()
    {
        return $this->LinkdenURL;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Employe
     */
    public function setUserId($userId)
    {
        $this->UserId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->UserId;
    }

    /**
     * Set totalargent
     *
     * @param integer $totalargent
     *
     * @return Employe
     */
    public function setTotalargent($totalargent)
    {
        $this->totalargent = $totalargent;

        return $this;
    }

    /**
     * Get totalargent
     *
     * @return integer
     */
    public function getTotalargent()
    {
        return $this->totalargent;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Employe
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
     * Set email
     *
     * @param string $email
     *
     * @return Employe
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
