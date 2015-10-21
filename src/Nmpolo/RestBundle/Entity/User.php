<?php

namespace Nmpolo\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Nmpolo\RestBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Nmpolo\RestBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("all")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Constraints\NotNull
     * @Constraints\NotBlank
     * @Expose
     */
    private $name;

    /**
     * @var Organisation $organisation
     *
     * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="users")
     */
    private $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

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
     * @return User
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
     * Set organisation
     *
     * @param Nmpolo\RestBundle\Entity\Organisation $organisation
     * @return User
     */
    public function setOrganisation(\Nmpolo\RestBundle\Entity\Organisation $organisation = null)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return Nmpolo\RestBundle\Entity\Organisation 
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
}
