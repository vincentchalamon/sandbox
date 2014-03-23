<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Form\Transformer;

use Doctrine\ORM\EntityRepository;
use My\Bundle\QuoteBundle\Entity\Customer;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Customer transformer
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CustomerTransformer implements DataTransformerInterface
{

    /**
     * Customer repository
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        /** @var Customer $value */
        // Fix for Symfony 2.4
        if (is_null($value)) {
            return null;
        }

        return array(
            'id' => $value->getId(),
            'name' => $value->getName(),
            'address' => $value->getAddress(),
            'zipcode' => $value->getZipcode(),
            'city' => $value->getCity(),
            'email' => $value->getEmail()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!isset($value['id']) || !$value['id'] || !$customer = $this->repository->find($value['id'])) {
            $customer = new Customer();
        }
        $customer->setName(trim($value['name']));
        $customer->setAddress(trim($value['address']));
        $customer->setZipcode(trim($value['zipcode']));
        $customer->setCity(trim($value['city']));
        $customer->setEmail(trim($value['email']));

        return $customer;
    }
}