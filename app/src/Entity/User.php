<?php

namespace App\Entity;

use Exception;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="user_account", schema="iw")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    private $firstname;
    private $lastname;
    private $birthday;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    
    }
    
    public function getFirstname()
    {
        return $this->firstname;
    }
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }
 
    public function getLastname()
    {
        return $this->lastname;
    }
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function isValid() 
    {
        if (empty($this->lastname))
            throw new Exception("Nom vide.");

        if (empty($this->firstname))
            throw new Exception("Prénom vide.");

        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL))
            throw new Exception("Email vide.");

        if ($this->birthday->addYears(13)->isBefore(Carbon::now()))
            throw new Exception("L'utilisateur doit avoir au moins 13 ans.");
 
        if (strlen($this->password) < 8 && strlen($this->password) > 40)
            throw new Exception("Le mot de passe doit comprendre entre 8 et 40 catactères.");

        return true;
    }
}
