<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use My\Bundle\QuoteBundle\Entity\Quote;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Download bills & quotes
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class DownloadController extends Controller
{

    /**
     * Generate & download bill from quote
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function billAction($id)
    {
        /** @var Quote $quote */
        if (!$quote = $this->get('my.repository.quote')->find((int)$id)) {
            throw $this->createNotFoundException();
        }
        $html = $this->renderView('MyQuoteBundle:Quote:bill.html.twig', array(
                'quote' => $quote
            )
        );

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="facture-%d.pdf"', $quote->getNumero())
            )
        );
    }

    /**
     * Generate & download quote
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function quoteAction($id)
    {
        /** @var Quote $quote */
        if (!$quote = $this->get('my.repository.quote')->find((int)$id)) {
            throw $this->createNotFoundException();
        }
        $html = $this->renderView('MyQuoteBundle:Quote:quote.html.twig', array(
                'quote' => $quote
            )
        );

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="devis-%d.pdf"', $quote->getNumero())
            )
        );
    }
}
