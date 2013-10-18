<?php
/**
 * Sosa Silex Persistence Entities: User
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

namespace Sosa\Persistence\Entities;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation As ANN;
use Faker\Factory;

/**
 * @ORM\Entity
 * @ORM\Table(name="user",uniqueConstraints={@ORM\UniqueConstraint(name="search_email", columns={"email"})})
 */
Class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ANN\Type("integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ANN\Type("string")
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @ANN\Type("string")
     */
    protected $last_name;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     * @ANN\Type("string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @ANN\Type("string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @ANN\Type("string")
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=255)
     * @ANN\Type("string")
     */
    protected $roles;

    /**
     * @ORM\Column(type="datetime")
     * @ANN\Type("datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @ANN\Type("datetime")
     */
    protected $updated_at;

    /**
     * __construct
     *
     * @return User $this
     */
    public function __construct() {
        return $this;
    }

    /**
     * displayName
     *
     * @return string a displayable name
     */
    public function displayName()
    {
        return sprintf("%s %s", $this->getFirstName(), $this->getLastName());
    }

    /**
     * Returns the email address, which serves as the username used to authenticate the user.
     *
     * This method is required by the UserInterface.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is a no-op, since we never store the plain text credentials in this object.
     * It's required by UserInterface.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * getFake: get fake values
     *
     * @return User;
     */
    public function getFake($container)
    {
        $faker = Factory::create();
        $this->setFirstName($faker->firstName);
        $this->setLastName($faker->lastName);
        $this->setEmail($faker->companyEmail);
        $this->setRoles('ROLE_USER');
        $this->setSalt($faker->sha256);
        $this->setEncodedPassword($container, $this->getEmail()); // Password is open data
        $this->setPassword($this->getEmail()); // Password is open data
        $this->setCreatedAt($faker->dateTime);
        $this->setUpdatedAt($this->getCreatedAt());

        return $this;
    }

    /**
     * getTest: get test user
     * @param App $container
     *
     * @return User $this
     */
    public function getTest($container)
    {
        $faker = Factory::create();
        $this->setFirstName($container['tests.user']['first_name']);
        $this->setLastName($container['tests.user']['last_name']);
        $this->setEmail($container['tests.user']['email']);
        $this->setRoles($container['tests.user']['roles']);
        $this->setSalt($faker->sha256);
        $this->setEncodedPassword($container, $container['tests.user']['password']);
        $this->setCreatedAt($container['tests.user']['created_at']);
        $this->setUpdatedAt($this->getCreatedAt());

        return $this;
    }

    public function setEncodedPassword($container, $password)
    {
        $this->setPassword($container['security.encoder.digest']->encodePassword($password, $this->getSalt()));
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
     * Set first_name
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        return array($this->roles);
    }
}
