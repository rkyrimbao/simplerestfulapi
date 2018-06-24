<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FootballTeam
 *
 * @ORM\Table(name="football_team", indexes={@ORM\Index(name="fk_football_team_football_league_idx", columns={"football_league_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FootballTeamRepository")
 */
class FootballTeam
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="strip", type="string", length=45, nullable=true)
     */
    private $strip;

    /**
     * @var \FootballLeague
     *
     * @ORM\ManyToOne(targetEntity="FootballLeague")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="football_league_id", referencedColumnName="id")
     * })
     */
    private $footballLeague;



    /**
     * Get id
     *
     * @return integer
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
     * @return FootballTeam
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
     * Set strip
     *
     * @param string $strip
     *
     * @return FootballTeam
     */
    public function setStrip($strip)
    {
        $this->strip = $strip;

        return $this;
    }

    /**
     * Get strip
     *
     * @return string
     */
    public function getStrip()
    {
        return $this->strip;
    }

    /**
     * Set footballLeague
     *
     * @param \AppBundle\Entity\FootballLeague $footballLeague
     *
     * @return FootballTeam
     */
    public function setFootballLeague(\AppBundle\Entity\FootballLeague $footballLeague = null)
    {
        $this->footballLeague = $footballLeague;

        return $this;
    }

    /**
     * Get footballLeague
     *
     * @return \AppBundle\Entity\FootballLeague
     */
    public function getFootballLeague()
    {
        return $this->footballLeague;
    }
}
