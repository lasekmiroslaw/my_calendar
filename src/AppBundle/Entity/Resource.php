<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resource
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResourceRepository")
 */
class Resource
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
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * One Resource has Many Resources.
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="parent")
     */
    private $children;

    /**
     * Many Resources have One Resource.
     * @ORM\ManyToOne(targetEntity="Resource", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set event
     *
     * @param integer $event
     *
     * @return Resource
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return int
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Resource
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set children
     *
     * @param string $children
     *
     * @return Collection
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get parent
     *
     * @return Resource
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the value of userId
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
