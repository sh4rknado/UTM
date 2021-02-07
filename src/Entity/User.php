<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class User implements UserInterface {


    /* //////////////////////// < ATTRIBUTES > ////////////////////////////////// */

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
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password", message="les mots de passe ne correspondent pas")
     */
    private $password;

    /**
     * @var string Password Confirm
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="password", message="les mots de passe ne correspondent pas")
     */
    public $confirm_password;

    /**
     * @var string username
     * @ORM\Column(type="string", length=255)
     *
     */
    private $username;

    /**
     * @var string The Token
     * @orm\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @var string image_path
     * @orm\Column(type="string", nullable=true)
     */
    private $avatar;


    /**
     * @var boolean use api key
     * @orm\Column(type="boolean", nullable=false)
     */
    private $use_api;

    /* //////////////////////// < DEFAULT BUILDER > ////////////////////////////////// */


    /**
     * Default Buidler init Roles : ROLE_USER
     */
    public function __contruct__() {
        $this->setRoles(['ROLE_USER']);
        $this->setUseApi(false);
    }


    /* //////////////////////// < GETTER / SETTER > ////////////////////////////////// */


    /**
     * @return bool
     */
    public function isUseApi(): bool
    {
        return $this->use_api;
    }

    /**
     * @param bool $use_api
     * @return User
     */
    public function setUseApi(bool $use_api): User
    {
        $this->use_api = $use_api;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        if($this->avatar == null) return "";
        else return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        if($this->apiToken == null) return "";
        else return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return User
     */
    public function setApiToken(string $apiToken): User
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }


    /* //////////////////////// < HELPERS FUNCTION > ////////////////////////////////// */


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @param string $new_roles
     * @return bool
     */
    public function addRole($new_roles) {

        if(in_array($new_roles, $this->getRoles())) {
            return false;
        }
        else {
            $roles = $this->getRoles();
            array_push($roles, $new_roles);
            $this->setRoles($roles);
            return true;
        }
    }

    /**
     * @param string $old_roles
     * @return bool
     */
    public function removeRole($old_roles) {

        if(in_array($old_roles, $this->getRoles())) {
            $new_roles = [];
            $roles = $this->getRoles();
            foreach ($roles as $r) if($r != $old_roles) array_push($new_roles, $r);
            return true;
        }
        else  return false;
    }

    /**
     * @return string
     */
    public function rolesToString() {
        $convert_roles = "";

        foreach ($this->getRoles() as $r) {
            $convert_roles .= $r." - ";
        }
        return $convert_roles;

    }

    /**
     * Generate Token
     */
    public function generateToken() {
        $this->setUseApi(true);
        $this->setApiToken(bin2hex(openssl_random_pseudo_bytes(16)));
    }

    /**
     * Set Token to null
     */
    public function revokeToken() {
        $this->setUseApi(false);
        $this->apiToken = null;
    }

}
