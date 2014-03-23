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
 * Quote
 */
class Quote
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
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var \DateTime
     */
    private $deadline;

    /**
     * @var Collection
     */
    private $deliveries;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var Bill
     */
    private $bill;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
    }

    /**
     * Render as string
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function __toString()
    {
        return sprintf('Devis %s', $this->getId() ? $this->getNumero() : '-');
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return sprintf('%s-%sD', $this->getCreatedAt()->format('y'), str_pad($this->id, 5, 0, STR_PAD_LEFT));
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        $amount = 0;
        foreach ($this->getDeliveries() as $delivery) {
            /** @var Delivery $delivery */
            $amount += $delivery->getPrice();
        }

        return $amount;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Quote
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Quote
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Quote
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Add deliveries
     *
     * @param Delivery $delivery
     * @return Quote
     */
    public function addDelivery(Delivery $delivery)
    {
        $this->deliveries[] = $delivery;

        return $this;
    }

    /**
     * Remove delivery
     *
     * @param Delivery $delivery
     */
    public function removeDelivery(Delivery $delivery)
    {
        $this->deliveries->removeElement($delivery);
    }

    /**
     * Get deliveries
     *
     * @return Collection
     */
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     * @return Quote
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set bill
     *
     * @param Bill $bill
     * @return Quote
     */
    public function setBill(Bill $bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return Bill
     */
    public function getBill()
    {
        return $this->bill;
    }
}
