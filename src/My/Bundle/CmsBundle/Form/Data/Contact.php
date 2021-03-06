<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as Recaptcha;

/**
 * Object for ContactType
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class Contact
{
    /**
     * Name
     *
     * @var string
     *
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * Email
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email(checkHost=true, checkMX=true)
     */
    protected $email;

    /**
     * Message
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max="100")
     */
    protected $message;

    /**
     * Captcha
     *
     * @var string
     *
     * @Recaptcha
     */
    protected $captcha;

    /**
     * Set Email
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get Email
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Message
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get Message
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Name
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $name
     *
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Name
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get captcha
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @return string
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Set captcha
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param string $captcha
     *
     * @return Contact
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }
}
