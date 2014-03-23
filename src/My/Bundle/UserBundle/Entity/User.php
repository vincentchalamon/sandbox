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

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var ArrayCollection
     */
    protected $groups;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $zipcode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $siret;

    /**
     * @var string
     */
    protected $ape;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
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

    /**
     * Set Ape
     *
     * @param string $ape
     *
     * @return User
     */
    public function setApe($ape)
    {
        $this->ape = $ape;

        return $this;
    }

    /**
     * Get Ape
     *
     * @return string
     */
    public function getApe()
    {
        return $this->ape;
    }

    /**
     * Set Siret
     *
     * @param string $siret
     *
     * @return User
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get Siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Get formatted siret
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @return string
     */
    public function getFormattedSiret()
    {
        return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{5})$/i', '$1 $2 $3 $4', $this->siret);
    }
}