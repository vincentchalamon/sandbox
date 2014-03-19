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

/**
 * Delivery
 */
class Delivery
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $contents;

    /**
     * @var float
     */
    private $price;

    /**
     * @var Quote
     */
    private $quote;

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
     * Render as string
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function __toString()
    {
        return $this->getContents() ?: '-';
    }

    /**
     * Set contents
     *
     * @param string $contents
     * @return Delivery
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return string 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Delivery
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quote
     *
     * @param Quote $quote
     * @return Delivery
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
