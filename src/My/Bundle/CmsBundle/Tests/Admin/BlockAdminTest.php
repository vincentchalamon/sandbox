<?php

/*
 * This file is part of the Sandbox package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Vince\Bundle\CmsBundle\Entity\Block;

/**
 * Test Block admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class BlockAdminTest extends WebTestCase
{

    /**
     * Test access
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testAccess()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Must be authenticated
        $client = $this->makeClient(false, array('HTTP_HOST' => 'blog.local'));
        $client->request('GET', '/admin/blocs/list');
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('sonata_user_admin_security_login', array(), true)));

        // Successful
        $this->fetchContent('/admin/blocs/list', 'GET', array(
                'username' => 'admin',
                'password' => '4dm1n'
            )
        );
    }

    /**
     * Test list
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testList()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $crawler = $this->fetchCrawler('/admin/blocs/list', 'GET', array(
                'username' => 'admin',
                'password' => '4dm1n'
            )
        );

        // Test displayed data in list
        $this->assertCount(0, $crawler->filter('a[href="/admin/blocs/create"]'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->first();
        $this->assertEquals('Test', trim($row->filter('td')->eq(1)->text()));
        $this->assertEquals('Jamais publié', trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals('Publier', $crawler->filter('table.table.table-bordered.table-striped select option')->first()->text());
        $this->assertEquals('Dépublier', $crawler->filter('table.table.table-bordered.table-striped select option')->eq(1)->text());
        $this->assertCount(0, $crawler->filter('table.table.table-bordered.table-striped select option[value=delete]'));
    }

    /**
     * Test publish
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testPublish()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );
        $crawler = $client->request('GET', '/admin/blocs/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(2)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/blocs/batch"]')->form();
        $form['idx'][0]->tick();
        $crawler = $client->submit($form, array('action' => 'publish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/blocs/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/blocs/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/blocs\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(2)->text()));
    }

    /**
     * Test unpublish
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testUnpublish()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );

        // Update Block for test
        /** @var Block $block */
        $block = $client->getContainer()->get('vince.repository.block')->findOneBy(array('name' => 'test'));
        $block->publish();
        $client->getContainer()->get('doctrine.orm.default_entity_manager')->persist($block);
        $client->getContainer()->get('doctrine.orm.default_entity_manager')->flush();

        // Test displayed data in list
        $crawler = $client->request('GET', '/admin/blocs/list');
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(2)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/blocs/batch"]')->form();
        $form['idx'][0]->tick();
        $crawler = $client->submit($form, array('action' => 'unpublish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/blocs/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/blocs/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/blocs\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(2)->text()));
    }

    /**
     * Test form
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testForm()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );
        $crawler = $client->request('GET', '/admin/blocs/list');
        $this->isSuccessful($client->getResponse());
        $crawler = $client->click($crawler->filter('table.table.table-bordered.table-striped tbody tr a')->first()->link());

        // Test displayed fields
        $this->assertCount(2, $crawler->filter('fieldset'));
        $this->assertEquals('Test', $crawler->filter('fieldset input[id$=_title]')->attr('value'));
        $this->assertCount(1, $crawler->filter('input[id$=_title]'));
        $this->assertCount(1, $crawler->filter('input[id$=_startedAt]'));
        $this->assertCount(1, $crawler->filter('input[id$=_endedAt]'));

        // Test displayed error messages
        $formName = preg_replace('/^([^\[]+).*$/', '$1', $crawler->filter('form[action^="/admin/blocs/"] input')->first()->attr('name'));
        $crawler = $client->submit($crawler->filter('form[action^="/admin/blocs/"]')->form(array(
                    $formName => array(
                        'title' => null,
                        'contents' => null
                    )
                )
            )
        );
        $this->isSuccessful($client->getResponse());
        $this->assertCount(2, $crawler->filter('.control-group.error'));

        // Test submit
        $client->submit($crawler->filter('form[action^="/admin/blocs/"]')->form(array(
                    $formName => array(
                        'title' => 'Lorem ipsum',
                        'contents' => 'Hello World!'
                    )
                )
            )
        );
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertCount(0, $crawler->filter('.control-group.error'));
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/blocs\/\d+\/edit$/', $client->getRequest()->getUri());

        // Test updated list
        $crawler = $client->click($crawler->filter('a[href="/admin/blocs/list"]')->link());
        $this->isSuccessful($client->getResponse());
        $this->assertEquals('Lorem ipsum', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(1)->text()));
    }
}