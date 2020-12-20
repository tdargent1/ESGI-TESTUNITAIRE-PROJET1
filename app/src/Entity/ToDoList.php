<?php

namespace App\Entity;

use Exception;
use App\Entity\Item;
use App\Service\MailService;
use App\Service\ToDoListService;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\ItemService;

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
     * @ORM\Column(type="datetime")
     */
    private $lastAddedTime;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="toDoList")
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

    public function addItem(ItemService $itemService, ToDoListService $todoService, MailService $mailService, Item $item): self
    {
        $todoService->updateToDoList($this->self, $item, $itemService, $mailService);
        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getToDoList() === $this) {
                $item->setToDoList(null);
            }
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function isValid() 
    {
        if (empty($this->name))
            throw new Exception("Nom vide.");

        if (empty($this->description))
            throw new Exception("Description vide.");
    
        return true;
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