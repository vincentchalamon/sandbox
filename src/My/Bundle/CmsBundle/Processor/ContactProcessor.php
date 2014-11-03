<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Processor;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Templating\EngineInterface;
use Vince\Bundle\CmsBundle\Component\Processor\Processor;
use My\Bundle\CmsBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Process Contact form
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ContactProcessor extends Processor
{

    /**
     * Mailer
     *
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * No-reply email address
     *
     * @var string
     */
    protected $noreply;

    /**
     * Sitename
     *
     * @var string
     */
    protected $sitename;

    /**
     * Contact email address
     *
     * @var string
     */
    protected $contact;

    /**
     * Templating
     *
     * @var EngineInterface
     */
    protected $templating;

    /**
     * Session
     *
     * @var Session
     */
    protected $session;

    /**
     * Web dir
     *
     * @var string
     */
    protected $webDir;

    /**
     * {@inheritdoc}
     */
    public function process(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de contact')
                        ->setFrom(array($this->noreply => $this->sitename))
                        ->setReplyTo($form->get('email')->getData(), $form->get('name')->getData())
                        ->setTo(array($this->contact))
                        ->setContentType('text/html')
            ;
            $body = $this->templating->render('MyCmsBundle::mail.html.twig', array(
                    'message' => $form->get('message')->getData(),
                    'name' => $form->get('name')->getData(),
                    'title' => 'Demande de contact'
                )
            );
            if (preg_match_all('/<img([^>]+)src="(\/[^"]+)"([^>]+)?>/i', $body, $matches)) {
                foreach ($matches[0] as $key => $match) {
                    $body = str_ireplace($match, sprintf('<img%ssrc="%s"%s>', $matches[1][$key], $message->embed(\Swift_Image::fromPath($this->webDir.$matches[2][$key])), $matches[3][$key]), $body);
                }
            }
            $message->setBody($body);
            $this->mailer->send($message);

            return new JsonResponse(array(
                    'code' => 'success',
                    'message' => 'Votre message a bien été envoyé.'
                )
            );
        }

        return new JsonResponse(array(
                'code' => 'error',
                'form' => $this->templating->render('MyCmsBundle:Component:contact.html.twig', array(
                            'form' => $form->createView()
                        )
                    )
            )
        );
    }

    /**
     * Set Session
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Set Mailer
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param \Swift_Mailer $mailer
     * @param string        $noreply
     * @param string        $sitename
     * @param string        $contact
     */
    public function setMailer(\Swift_Mailer $mailer, $noreply, $sitename, $contact)
    {
        $this->mailer   = $mailer;
        $this->noreply  = $noreply;
        $this->sitename = $sitename;
        $this->contact  = $contact;
    }

    /**
     * Set Templating
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * Set web dir
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;
    }
}
