<?php

/*
 * This file is part of the VinceCmsSonataAdmin bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vince\Bundle\CmsSonataAdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Vince\Bundle\CmsBundle\Component\YamlFixturesLoader as Loader;

/**
 * Load fixtures from yml
 * 
 * @author Vincent CHALAMON <vincentchalamon@gmail.com>
 */
class UserData extends AbstractFixture implements OrderedFixtureInterface
{
    
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $loader = new Loader();
        $loader->addDirectory(__DIR__.'/../../Resources/config/data');
        $loader->load($manager, null, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}