<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Bill
 */
class Bill
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $quote;

    /**
     * @param Quote $quote
     */
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * Render as string
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function __toString()
    {
        return 'Facture '.$this->getNumero();
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return sprintf('%s-%s', $this->getCreatedAt()->format('y'), str_pad($this->id, 5, 0, STR_PAD_LEFT));
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->getQuote()->getCustomer();
    }

    /**
     * Get deliveries
     *
     * @return ArrayCollection
     */
    public function getDeliveries()
    {
        return $this->getQuote()->getDeliveries();
    }

    /**
     * Get Quote amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getQuote()->getAmount();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Quote
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set quote
     *
     * @param Quote $quote
     * @return Bill
     */
    public function setQuote(Quote $quote)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote
     *
     * @return Quote
     */
    public function getQuote()
    {
        return $this->quote;
    }
}
