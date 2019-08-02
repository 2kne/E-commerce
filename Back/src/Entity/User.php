<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $roles = array();
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apiToken;
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
     /**
    * Returns the roles or permissions granted to the user for security.
    */
   public function getRoles()
   {
       $roles = $this->roles;
       // guarantees that a user always has at least one role for security
       if (empty($roles)) {
           $roles[] = 'ROLE_USER';
       }
       return array_unique($roles);
   }
   public function setRoles($roles)
   {
       $this->roles = $roles;
   }
   /**
    * Returns the salt that was originally used to encode the password.
    */
   public function getSalt()
   {
       return;
   }
   /**
    * Removes sensitive data from the user.
    */
   public function eraseCredentials()
   {
       // if you had a plainPassword property, you'd nullify it here
       // $this->plainPassword = null;
   }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    public function getPlainPassword()
    {
    }


    // private $role;

    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    // public function getEmail(): ?string
    // {
    //     return $this->email;
    // }

    // public function setEmail(string $email): self
    // {
    //     $this->email = $email;

    //     return $this;
    // }

    // public function getUsername(): ?string
    // {
    //     return $this->username;
    // }

    // public function setUsername(string $username): self
    // {
    //     $this->username = $username;

    //     return $this;
    // }

    // public function getPassword(): ?string
    // {
    //     return $this->password;
    // }

    // public function setPassword(string $password): self
    // {
    //     $this->password = $password;

    //     return $this;
    // }

    // public function getRole(): ?bool
    // {
    //     return $this->role;
    // }

    // public function setRole(bool $role): self
    // {
    //     $this->role = $role;

    //     return $this;
    // }
}
