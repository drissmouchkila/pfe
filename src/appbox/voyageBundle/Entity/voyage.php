<?php

namespace appbox\voyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}

