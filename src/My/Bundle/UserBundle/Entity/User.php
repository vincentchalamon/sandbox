<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\UserBundle\Entity\BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="My\Bundle\UserBundle\Entity\Group", cascade={"persist"})
     * @ORM\JoinTable(name="fos_user_group")
     */
    protected $groups;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * todo-vince Assert ?
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5)
     *
     * todo-vince Assert ?
     */
    protected $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * todo-vince Assert ?
     */
    protected $city;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        $this->setUsernameCanonical($email);
        $this->setEmailCanonical($email);

        return parent::setEmail($email);
    }

    /**
     * Set Address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set City
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get City
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set Zipcode
     *
     * @param string $zipcode
     *
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get Zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
}
