<?php

namespace App\Entity;

use Exception;
use App\Entity\Item;
use App\Service\MailService;
use App\Service\ToDoListService;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ToDoListRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 */
class ToDoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastAddedTime;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="toDoList", cascade={"persist"})
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct(User $owner)
    {
        $this->items = new ArrayCollection();
        $this->owner = $owner;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLastAddedTime(): ?\DateTimeInterface
    {
        return $this->lastAddedTime;
    }

    public function setLastAddedTime(\DateTimeInterface $lastAddedTime): self
    {
        $this->lastAddedTime = $lastAddedTime;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}