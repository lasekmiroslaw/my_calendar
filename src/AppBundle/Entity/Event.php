<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @ORM\Column(name="title", type="string", length=255)
     * @Groups({"default"})
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     * @Assert\NotBlank()
     * @ORM\Column(name="start", type="datetime")
     * @Groups({"default"})
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     * @ORM\Column(name="end", type="datetime", nullable=true)
     * @Groups({"default"})
     */
    private $end;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

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
     * Set title
     *
     * @param string $title
     *
     * @return Event
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
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
