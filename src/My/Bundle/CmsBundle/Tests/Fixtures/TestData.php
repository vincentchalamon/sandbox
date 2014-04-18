<?php

/*
 * This file is part of the Sandbox package.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vince\Bundle\CmsBundle\Component\YamlFixturesLoader as Loader;
use My\Bundle\UserBundle\Entity\User;

/**
 * Load test fixtures from yml
 * 
 * @author Vincent CHALAMON <vincentchalamon@gmail.com>
 */
class TestData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load fixtures files
     * 
     * @author Vincent CHALAMON <vincentchalamon@gmail.com>
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $loader = new Loader();
        $loader->addFile(__DIR__.'/fixtures.yml');
        $loader->load($manager, function ($name, $object) {
                if ($object instanceof User) {
                    $object->setUsername($name);
                    $object->setUsernameCanonical($name);
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}