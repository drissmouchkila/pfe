<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="appbox\voyageBundle\Repository\blogRepository")
 */
class blog
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
     * @ORM\Column(name="url", type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    /**
     * @ORM\Column(name="datedepublication", type="date")
     */
    private $datederecrutement;

    /**
     * @ORM\Column(name="employe",type="integer")
     */
    private $employe;

    public function __construct(){
        $this->datederecrutement= new \DateTime();
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
     * Set url
     *
     * @param string $url
     *
     * @return blog
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return blog
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
     * Set description
     *
     * @param string $description
     *
     * @return blog
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
     * Set datederecrutement
     *
     * @param \DateTime $datederecrutement
     *
     * @return blog
     */
    public function setDatederecrutement($datederecrutement)
    {
        $this->datederecrutement = $datederecrutement;

        return $this;
    }

    /**
     * Get datederecrutement
     *
     * @return \DateTime
     */
    public function getDatederecrutement()
    {
        return $this->datederecrutement;
    }

    /**
     * Set employe
     *
     * @param integer $employe
     *
     * @return blog
     */
    public function setEmploye($employe)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return integer
     */
    public function getEmploye()
    {
        return $this->employe;
    }
}
