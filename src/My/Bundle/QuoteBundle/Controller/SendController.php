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
use My\Bundle\QuoteBundle\Form\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Send pdf by email
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class SendController extends Controller
{

    /**
     * Send Quote pdf by email
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     * @throws NotFoundHttpException
     */
    public function quoteAction(Request $request)
    {
        /** @var Quote $quote */
        if (!$quote = $this->get('my.repository.quote')->find((int)substr($request->get('numero'), 3))) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(new EmailType(), array(
                'contents' => $this->get('translator')->trans('email.quote.contents', array(
                            '%account%' => number_format($quote->getAmount()/2, 2, ',', ' '),
                            '%date%' => \IntlDateFormatter::create($request->getLocale(), \IntlDateFormatter::FULL,
                                        \IntlDateFormatter::NONE, $quote->getDeadline()->getTimezone()->getName(),
                                        \IntlDateFormatter::GREGORIAN)->format($quote->getDeadline()->getTimestamp())
                        ), 'SonataAdminBundle')
            )
        );
        if ($request->isMethod('post')) {
            // Bind form
            $form->handleRequest($request);
            if ($form->isValid()) {
                // Prepare message
                $message = \Swift_Message::newInstance()
                    ->setSubject($quote->__toString())
                    ->setFrom(array($this->getUser()->getEmail() => $this->getUser()->getFirstname().' '.$this->getUser()->getLastname()))
                    ->setTo(array($quote->getCustomer()->getEmail() => $quote->getCustomer()->getName()))
                    ->setCc(array($this->getUser()->getEmail() => $this->getUser()->getFirstname().' '.$this->getUser()->getLastname()))
                    ->setContentType('text/html');

                // Prepare message body
                $body = $this->get('templating')->render('MyQuoteBundle:Email:mail.html.twig', array(
                        'title'    => $message->getHeaders()->get('Subject'),
                        'contents' => $form->get('contents')->getData()
                    )
                );

                // Embed body images
                if (preg_match_all('/<img[^>]+src="((?:http|https):\/\/[^\/]+(\/[^"]+))"/i', $body, $matches)) {
                    foreach ($matches[0] as $key => $match) {
                        $body = str_ireplace($match, str_ireplace($matches[1][$key], $message->embed(\Swift_Image::fromPath($this->container->getParameter('kernel.web_dir').$matches[2][$key])), $match), $body);
                    }
                }
                $message->setBody($body);

                // Add pdf file to message attachment
                $html = $this->renderView('MyQuoteBundle:Quote:quote.html.twig', array(
                        'object' => $quote
                    )
                );
                $message->attach(\Swift_Attachment::newInstance()
                    ->setFilename($quote->getNumero().'.pdf')
                    ->setContentType('application/pdf')
                    ->setBody($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                            'Content-Type'        => 'application/pdf',
                            'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $quote->getNumero())
                        )
                    )
                );

                // Send email
                $this->get('mailer')->send($message);

                return new JsonResponse(array(
                        'code'    => 'success',
                        'message' => $this->get('translator')->trans('email.quote.confirmation', array(
                                    '%number%' => $quote->getNumero(),
                                    '%customer.name%' => $quote->getCustomer()->getName(),
                                    '%customer.email%' => $quote->getCustomer()->getEmail()
                                ), 'SonataAdminBundle'
                            )
                    )
                );
            }

            return new JsonResponse(array(
                    'html' => $this->render('MyQuoteBundle:Email:form.html.twig', array(
                                'form'   => $form->createView(),
                                'action' => $this->get('router')->generate('send_quote', array('numero' => $quote->getNumero()))
                            )
                        )
                )
            );
        }

        return $this->render('MyQuoteBundle:Email:form.html.twig', array(
                'form'   => $form->createView(),
                'action' => $this->get('router')->generate('send_quote', array('numero' => $quote->getNumero()))
            )
        );
    }

    /**
     * Send Bill pdf by email
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     * @throws NotFoundHttpException
     */
    public function billAction(Request $request)
    {
        /** @var Quote $quote */
        if (!$quote = $this->get('my.repository.quote')->find((int)substr($request->get('numero'), 3))) {
            throw $this->createNotFoundException();
        }
        // Generate Bill from Quote
        if (!$quote->getBill()) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist(new Bill($quote));
            $em->flush();
            $em->refresh($quote);
        }
        $form = $this->createForm(new EmailType(), array(
                'contents' => $this->get('translator')->trans('email.bill.contents', array(
                            '%bill.number%' => $quote->getBill()->getNumero(),
                            '%quote.number%' => $quote->getNumero(),
                            '%account%' => number_format($quote->getAmount()/2, 2, ',', ' '),
                            '%date%' => \IntlDateFormatter::create($request->getLocale(), \IntlDateFormatter::FULL,
                                        \IntlDateFormatter::NONE, $quote->getDeadline()->getTimezone()->getName(),
                                        \IntlDateFormatter::GREGORIAN)->format($quote->getBill()->getCreatedAt()->add(\DateInterval::createFromDateString('+30 days')))
                        ), 'SonataAdminBundle')
            )
        );
        if ($request->isMethod('post')) {
            // Bind form
            $form->handleRequest($request);
            if ($form->isValid()) {
                // Prepare message
                $message = \Swift_Message::newInstance()
                    ->setSubject($quote->getBill()->__toString())
                    ->setFrom(array($this->getUser()->getEmail() => $this->getUser()->getFirstname().' '.$this->getUser()->getLastname()))
                    ->setTo(array($quote->getCustomer()->getEmail() => $quote->getCustomer()->getName()))
                    ->setCc(array($this->getUser()->getEmail() => $this->getUser()->getFirstname().' '.$this->getUser()->getLastname()))
                    ->setContentType('text/html');

                // Prepare message body
                $body = $this->get('templating')->render('MyQuoteBundle:Email:mail.html.twig', array(
                        'title'    => $message->getHeaders()->get('Subject'),
                        'contents' => $form->get('contents')->getData()
                    )
                );

                // Embed body images
                if (preg_match_all('/<img[^>]+src="((?:http|https):\/\/[^\/]+(\/[^"]+))"/i', $body, $matches)) {
                    foreach ($matches[0] as $key => $match) {
                        $body = str_ireplace($match, str_ireplace($matches[1][$key], $message->embed(\Swift_Image::fromPath($this->container->getParameter('kernel.web_dir').$matches[2][$key])), $match), $body);
                    }
                }
                $message->setBody($body);

                // Add pdf file to message attachment
                $html = $this->renderView('MyQuoteBundle:Quote:bill.html.twig', array(
                        'object' => $quote
                    )
                );
                $message->attach(\Swift_Attachment::newInstance()
                    ->setFilename($quote->getBill()->getNumero().'.pdf')
                    ->setContentType('application/pdf')
                    ->setBody($this->get('knp_snappy.pdf')->getOutputFromHtml($html), 200, array(
                            'Content-Type'        => 'application/pdf',
                            'Content-Disposition' => sprintf('attachment; filename="%s.pdf"', $quote->getBill()->getNumero())
                        )
                    )
                );

                // Send email
                $this->get('mailer')->send($message);

                return new JsonResponse(array(
                        'code'    => 'success',
                        'message' => $this->get('translator')->trans('email.bill.confirmation', array(
                                    '%number%' => $quote->getBill()->getNumero(),
                                    '%customer.name%' => $quote->getCustomer()->getName(),
                                    '%customer.email%' => $quote->getCustomer()->getEmail()
                                ), 'SonataAdminBundle'
                            )
                    )
                );
            }

            return new JsonResponse(array(
                    'html' => $this->render('MyQuoteBundle:Email:form.html.twig', array(
                                'form'   => $form->createView(),
                                'action' => $this->get('router')->generate('send_bill', array('numero' => $quote->getBill()->getNumero()))
                            )
                        )
                )
            );
        }

        return $this->render('MyQuoteBundle:Email:form.html.twig', array(
                'form'   => $form->createView(),
                'action' => $this->get('router')->generate('send_bill', array('numero' => $quote->getBill()->getNumero()))
            )
        );
    }
}
