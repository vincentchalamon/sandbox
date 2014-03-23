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

use My\Bundle\QuoteBundle\Entity\Quote;
use My\Bundle\QuoteBundle\Entity\Bill;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * Download quote as pdf
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $numero
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function quoteAction($numero)
    {
        if (!$quote = $this->get('my.repository.quote')->find((int)substr($numero, 3, -1))) {
            throw $this->createNotFoundException();
        }
        $html = $this->renderView('MyQuoteBundle:Quote:quote.html.twig', array(
                'object' => $quote
            )
        );

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $quote->getNumero())
            )
        );
    }

    /**
     * Generate bill from quote & download as pdf
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $numero
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function billAction($numero)
    {
        if (!$quote = $this->get('my.repository.quote')->find((int)substr($numero, 3))) {
            throw $this->createNotFoundException();
        }
        // Generate Bill from Quote
        if (!$quote->getBill()) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist(new Bill($quote));
            $em->flush();
            $em->refresh($quote);
        }
        $html = $this->renderView('MyQuoteBundle:Quote:bill.html.twig', array(
                'object' => $quote->getBill()
            )
        );

        return new Response($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $quote->getBill()->getNumero())
            )
        );
    }
}
