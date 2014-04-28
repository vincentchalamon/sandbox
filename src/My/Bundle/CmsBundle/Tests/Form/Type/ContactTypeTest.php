<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Form\Type;

use My\Bundle\CmsBundle\Form\Data\Contact;
use My\Bundle\CmsBundle\Form\Type\ContactType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Test ContactType submission
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ContactTypeTest extends TypeTestCase
{

    /**
     * Test ContactType submission
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testSubmitContact()
    {
        $contact = new Contact();
        $contact->setName('John DOE');
        $contact->setEmail('test@gmail.com');
        $contact->setMessage('Hello World!');

        $form = $this->factory->create(new ContactType());
        $this->assertEquals(null, $form->createView()->vars['value']);
        $form->submit(array(
                'name' => 'John DOE',
                'email' => 'test@gmail.com',
                'message' => 'Hello World!'
            )
        );
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($contact, $form->getData());

        $form = $this->factory->create(new ContactType(), $contact);
        $this->assertEquals($contact, $form->createView()->vars['value']);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($contact, $form->getData());
    }
}