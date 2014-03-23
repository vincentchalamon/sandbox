<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Admin\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use My\Bundle\QuoteBundle\Entity\Delivery;
use My\Bundle\QuoteBundle\Entity\Quote;
use My\Bundle\QuoteBundle\Form\Transformer\DeliveriesTransformer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Quote admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class QuoteAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected $baseRoutePattern = 'devis';

    /**
     * Delivery repository
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Set Delivery repository
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param EntityRepository $repository
     */
    public function setDeliveryRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Set object manager
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param ObjectManager $em
     */
    public function setObjectManager(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormTheme()
    {
        return array_merge(parent::getFormTheme(), array('MyQuoteBundle:Form:form_theme.html.twig'));
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'MyQuoteBundle:Form:quote.html.twig';
            case 'list':
                return 'MyQuoteBundle:List:list.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('numero', null, array('label' => 'Numéro'))
            ->add('createdAt', 'localizeddate', array('label' => 'Créé le'))
            ->add('amount', 'string', array('label' => 'Montant', 'template' => 'MyQuoteBundle:List:amount.html.twig'))
            ->add('customer.name', null, array('label' => 'Client'))
            ->add('download', 'string', array('label' => 'Devis', 'template' => 'MyQuoteBundle:List:quote.html.twig'))
            ->add('bill', 'string', array('label' => 'Facture', 'template' => 'MyQuoteBundle:List:bill.html.twig'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('customer', 'customer')
            ->add('notes', null, array('attr' => array('placeholder' => 'Notes')))
            ->add('deadline', 'datepicker', array('attr' => array('placeholder' => 'Date d\'échéance')))
            ->add('deliveries', 'collection', array(
                    'type' => 'delivery',
                    'allow_add' => true,
                    'allow_delete' => true
                )
            )
        ;
    }

    /**
     * Force relation because of doctrine2 bug on cascade persist for OneToMany association.
     *
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        /** @var Quote $object */
        foreach ($object->getDeliveries() as $delivery) {
            /** @var Delivery $delivery */
            $delivery->setQuote($object);
        }
        $deliveries = $this->repository->findBy(array('quote' => $object->getId()));
        foreach ($deliveries as $delivery) {
            if (!in_array($delivery->getId(), $object->getDeliveries()->map(function (Delivery $del) {
                        return $del->getId();
                    })->toArray())) {
                $this->em->remove($delivery);
            }
        }
        // todo-vince Sync Customer with Google contact
    }

    /**
     * Force relation because of doctrine2 bug on cascade persist for OneToMany association.
     *
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        /** @var Quote $object */
        foreach ($object->getDeliveries() as $delivery) {
            /** @var Delivery $delivery */
            $delivery->setQuote($object);
        }
        $deliveries = $this->repository->findBy(array('quote' => $object->getId()));
        foreach ($deliveries as $delivery) {
            if (!in_array($delivery->getId(), $object->getDeliveries()->map(function (Delivery $del) {
                        return $del->getId();
                    })->toArray())) {
                $this->em->remove($delivery);
            }
        }
        // todo-vince Sync Customer with Google contact
    }
}