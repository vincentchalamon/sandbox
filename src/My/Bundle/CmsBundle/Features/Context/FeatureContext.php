<?php

namespace My\Bundle\CmsBundle\Features\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\ElementTextException;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;
use PHPUnit_Framework_ExpectationFailedException as AssertException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements KernelAwareContext
{
    use KernelDictionary;

    /**
     * Prevent redirections
     *
     * @When /^(?P<step>.*) without redirection$/
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $step Step
     *
     * @return mixed
     *
     * @throws UnsupportedDriverActionException
     */
    public function interceptRedirections($step)
    {
        $this->getClient()->followRedirects(false);
        $this->followStep($step);
    }

    /**
     * Follow redirection
     *
     * @When /^(?:|I )follow the redirection$/
     * @Then /^(?:|I )should be redirected$/
     */
    public function iFollowTheRedirection()
    {
        $this->getClient()->followRedirects(true);
        $this->getClient()->followRedirect();
    }

    /**
     * Trace email from Symfony profile
     *
     * @Given /^(?:|I )should receive an email from "(?P<from>[^"]+)" to "(?P<to>[^"]+)" with:$/
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string       $from Sender email address
     * @param string       $to   Recipient email address
     * @param PyStringNode $text Email message
     *
     * @return bool
     *
     * @throws ExpectationException
     */
    public function iShouldReceiveAnEmail($from, $to, PyStringNode $text)
    {
        /** @var MessageDataCollector $collector */
        $collector = $this->getSymfonyProfile()->getCollector('swiftmailer');

        if (!count($collector->getMessages())) {
            throw new ExpectationException('No message sent.', $this->getSession());
        }

        $error = sprintf('%d message(s) sent, but no one from "%s" to "%s"', $collector->getMessages(), $from, $to);

        foreach ($collector->getMessages() as $message) {
            /** @var \Swift_Message $message */
            if ((!array_key_exists($from, $message->getFrom()) && (
                    !$message->getHeaders()->has('X-Swift-From') ||
                    !array_key_exists($from, $message->getHeaders()->get('X-Swift-From')->getFieldBodyModel())
                )) || (!array_key_exists($to, $message->getTo()) && (
                        !$message->getHeaders()->has('X-Swift-To') ||
                        !array_key_exists($to, $message->getHeaders()->get('X-Swift-To')->getFieldBodyModel())
                    )
                )) {
                continue;
            }

            try {
                return $text->getRaw() === $message->getBody();
            } catch (AssertException $e) {
                $error = sprintf('An email has been sent from "%s" to "%s", but not with the provided text.', $from, $to);
            }
        }

        throw new ExpectationException($error, $this->getSession());
    }

    /**
     * Click on the element with the provided xpath query
     *
     * @When /^(?:|I )should see "(?P<text>(?:[^"]|\\")*)" in the element with xpath "(?P<xpath>[^"]+)"$/
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $text  Element text
     * @param string $xpath Xpath query
     *
     * @throws ElementTextException
     */
    public function iShouldSeeInTheElementWithXpath($text, $xpath)
    {
        // Get the mink session
        $session = $this->getSession();

        // Runs the actual query and returns the element
        $element = $session->getPage()->find('xpath', $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath));

        // Errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }

        // Compare text
        $text    = $this->fixStepArgument($text);
        $actual  = $element->getText();
        $regex   = '/'.preg_quote($text, '/').'/ui';
        $message = sprintf('The text "%s" was not found in the text of the element with xpath "%s".', $text, $xpath);
        if (!(bool) preg_match($regex, $actual)) {
            throw new ElementTextException($message, $session, $element);
        }
    }

    /**
     * Execute provided step (example in `interceptRedirections` method)
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @param  string $step Step
     * @return mixed
     */
    protected function followStep($step)
    {
        $reflectionClass = new \ReflectionClass($this);
        $regex = '/\@(given|when|then)\s+(.+)/i';
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            /** @var \ReflectionMethod $reflectionMethod */
            if (preg_match_all($regex, $reflectionMethod->getDocComment(), $annotations)) {
                foreach ($annotations[2] as $pattern) {
                    if (preg_match($pattern, $step, $args)) {
                        unset($args[0], $args[1]);

                        return call_user_func_array(array($this, $reflectionMethod->getName()), $args);
                    }
                }
            }
        }
    }

    /**
     * Get Symfony profile
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @return Profile
     * @throws UnsupportedDriverActionException
     */
    protected function getSymfonyProfile()
    {
        $profile = $this->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'The profiler is disabled. Activate it by setting '.
                'framework.profiler.only_exceptions to false and '.
                'framework.profiler.collect to true in your config'
            );
        }

        return $profile;
    }

    /**
     * Get client
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @return Client
     * @throws UnsupportedDriverActionException
     */
    protected function getClient()
    {
        if (!$this->getDriver() instanceof BrowserKitDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with '.
                '"@mink:symfony2". Using the client '.
                'is not supported by %s', $this->getDriver()
            );
        }

        return $this->getDriver()->getClient();
    }

    /**
     * Get driver
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     * @return BrowserKitDriver
     * @throws UnsupportedDriverActionException
     */
    protected function getDriver()
    {
        return $this->getSession()->getDriver();
    }
}
