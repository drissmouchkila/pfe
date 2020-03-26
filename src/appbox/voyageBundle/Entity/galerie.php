<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Tests\StringableObject;

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
     * @var String
     *
     * @ORM\Column(name="url", type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $url;

    /**
     * @var int
     *
     * @ORM\Column(name="voyage", type="integer")
     */
    private $voyage;

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
     * @return galerie
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
     * Set voyage
     *
     * @param integer $voyage
     *
     * @return galerie
     */
    public function setVoyage($voyage)
    {
        $this->voyage = $voyage;

        return $this;
    }

    /**
     * Get voyage
     *
     * @return integer
     */
    public function getVoyage()
    {
        return $this->voyage;
    }

    /**
     * Set principal
     *
     * @param boolean $principal
     *
     * @return galerie
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return boolean
     */
    public function getPrincipal()
    {
        return $this->principal;
    }
}
