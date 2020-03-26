<?php
// src/OC/UserBundle/Entity/User.php

namespace appbox\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="oc_user")
 * @ORM\Entity(repositoryClass="appbox\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(name="facebookid", type="string", nullable=true)
     */
    protected $facebookid;
    /**
     * @ORM\Column(name="googleid", type="string", nullable=true)
     */
    protected $googleid;
    /**
     * @ORM\Column(name="twitterid", type="string", nullable=true)
     */
    protected $twitterid;

    /**
     * @ORM\Column(name="nbrreser",type="integer", nullable=true)
     */
    protected $nbrreser = 0;
    /**
     * @ORM\Column(name="nbrcommtotal",type="integer", nullable=true)
     */
    protected $nbrcommtotal = 0;
    /**
     * @ORM\Column(name="nbrcommbloq",type="integer", nullable=true)
     */
    protected $nbrcommbloqS = 0;
    /**
     * @ORM\Column(name="nom", type="string",nullable=true)
     */
    protected $nom;
    /**
     * @ORM\Column(name="prenom", type="string",nullable=true)
     */
    protected $prenom;
    /**
     * @ORM\Column(name="adress", type="string",nullable=true)
     */
    protected $adress;
    /**
     * @ORM\Column(name="telephone", type="integer",nullable=true)
     */
    protected $telephone;


    public function _construct(){
        $this->nbrcommtotal=0;
        $this->nbrreser=0;
        $this->nbrcommbloqS=0;
    }

    /**
     * Set facebookid
     *
     * @param string $facebookid
     *
     * @return User
     */
    public function setFacebookid($facebookid)
    {
        $this->facebookid = $facebookid;

        return $this;
    }

    /**
     * Get facebookid
     *
     * @return string
     */
    public function getFacebookid()
    {
        return $this->facebookid;
    }

    /**
     * Set googleid
     *
     * @param string $googleid
     *
     * @return User
     */
    public function setGoogleid($googleid)
    {
        $this->googleid = $googleid;

        return $this;
    }

    /**
     * Get googleid
     *
     * @return string
     */
    public function getGoogleid()
    {
        return $this->googleid;
    }

    /**
     * Set twitterid
     *
     * @param string $twitterid
     *
     * @return User
     */
    public function setTwitterid($twitterid)
    {
        $this->twitterid = $twitterid;

        return $this;
    }

    /**
     * Get twitterid
     *
     * @return string
     */
    public function getTwitterid()
    {
        return $this->twitterid;
    }

    /**
     * Set nbrreser
     *
     * @param integer $nbrreser
     *
     * @return User
     */
    public function setNbrreser($nbrreser)
    {
        $this->nbrreser = $nbrreser;

        return $this;
    }

    /**
     * Get nbrreser
     *
     * @return integer
     */
    public function getNbrreser()
    {
        return $this->nbrreser;
    }

    /**
     * Set nbrcommtotal
     *
     * @param integer $nbrcommtotal
     *
     * @return User
     */
    public function setNbrcommtotal($nbrcommtotal)
    {
        $this->nbrcommtotal = $nbrcommtotal;

        return $this;
    }

    /**
     * Get nbrcommtotal
     *
     * @return integer
     */
    public function getNbrcommtotal()
    {
        return $this->nbrcommtotal;
    }

    /**
     * Set nbrcommbloqS
     *
     * @param integer $nbrcommbloqS
     *
     * @return User
     */
    public function setNbrcommbloqS($nbrcommbloqS)
    {
        $this->nbrcommbloqS = $nbrcommbloqS;

        return $this;
    }

    /**
     * Get nbrcommbloqS
     *
     * @return integer
     */
    public function getNbrcommbloqS()
    {
        return $this->nbrcommbloqS;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
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
     * @return User
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
     * Set adress
     *
     * @param string $adress
     *
     * @return User
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
     * Set telephone
     *
     * @param integer $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return integer
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
}
