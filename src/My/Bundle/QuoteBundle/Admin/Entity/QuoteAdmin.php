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

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;

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
     * {@inheritdoc}
     */
    public function getFormTheme()
    {
        return array_merge(parent::getFormTheme(), array('MyQuoteBundle:Form:form_theme.html.twig'));
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
            ->add('download', 'string', array('label' => 'Télécharger', 'template' => 'MyQuoteBundle:List:quote.html.twig'))
            ->add('bill', 'string', array('label' => 'Facture', 'template' => 'MyQuoteBundle:List:bill.html.twig'))
        ;
    }
}