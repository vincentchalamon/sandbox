<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app['swiftmailer.options'] = array(
    'host' => 'localhost',
    'port' => '1025'
);

$app->match('/', function (Request $request) use ($app) {
    /** @var Form $form */
    $form = $app['form.factory']->createBuilder('form')
        ->add('email', 'email', array(
            'label' => 'Votre adresse e-mail',
            'attr'  => array(
                'class' => 'form-control',
                'placeholder' => 'Votre adresse e-mail',
                'data-validation-required-message' => 'Veuillez saisir votre adresse e-mail'
            ),
            'constraints' => array(
                new Assert\Email(array('message' => 'Veuillez saisir une adresse e-mail valide', 'checkMX' => true, 'checkHost' => true)),
                new Assert\NotBlank(array('message' => 'Veuillez saisir votre adresse e-mail'))
            )
        ))
        ->add('message', 'textarea', array(
            'label' => 'Votre message',
            'attr'  => array(
                'class' => 'form-control',
                'placeholder' => 'Votre message',
                'data-validation-required-message' => 'Veuillez saisir votre message'
            ),
            'constraints' => array(
                new Assert\NotBlank(array('message' => 'Veuillez saisir votre message'))
            )
        ))
        ->getForm();

    // Form has been sent
    if ($request->isMethod('post')) {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('[Blog] Contact')
                ->setFrom(array($form->get('email')->getData()))
                ->setTo(array('vincentchalamon@gmail.com'))
                ->setBody($app['twig']->render('mail.html.twig', array(
                    'message' => $form->get('message')->getData()
                )))->setContentType('text/html');
            $app['mailer']->send($message);

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'code' => 'success',
                    'html' => $app['twig']->render('form.html.twig', array(
                        'form' => $form->createView()
                    ))
                ));
            }

            return $app->redirect('/');
        } elseif ($request->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'code' => 'error',
                'html' => $app['twig']->render('form.html.twig', array(
                    'form' => $form->createView()
                ))
            ));
        }
    }

    return $app['twig']->render('index.html.twig', array(
        'form' => $form->createView()
    ));
});

$app->run();