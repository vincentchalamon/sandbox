<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use My\Bundle\CmsBundle\Entity\Category;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Test Category validation.
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CategoryTest extends WebTestCase
{

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * Test Category validation
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testContactValidation()
    {
        /** @var Validator $validator */
        $category  = new Category();
        $validator = static::$kernel->getContainer()->get('validator');

        // Test invalid object
        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($category);
        $this->assertCount(1, $errors);
        $this->assertEquals('name', $errors->get(0)->getPropertyPath());

        $category->setName('Test');
        $this->assertCount(0, $validator->validate($category));
    }
}