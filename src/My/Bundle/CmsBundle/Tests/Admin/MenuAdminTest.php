<?php

/*
 * This file is part of the Sandbox package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Test Menu admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class MenuAdminTest extends WebTestCase
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
        $client->request('GET', '/admin/menus/list');
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('sonata_user_admin_security_login', array(), true)));

        // Successful
        $this->fetchContent('/admin/menus/list', 'GET', array(
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
        $crawler = $this->fetchCrawler('/admin/menus/list', 'GET', array(
                'username' => 'admin',
                'password' => '4dm1n'
            )
        );

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Supprimer', $crawler->filter('table.table.table-bordered.table-striped select option')->first()->text());
        $this->assertEquals('Publier', $crawler->filter('table.table.table-bordered.table-striped select option')->eq(1)->text());
        $this->assertEquals('Dépublier', $crawler->filter('table.table.table-bordered.table-striped select option')->eq(2)->text());

        // Test rows order
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->first();
        $this->assertEmpty(trim($row->filter('td')->eq(1)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals('Root', trim($row->filter('td')->eq(3)->text()));
        $this->assertCount(0, $row->filter('td')->eq(4)->filter('a'));
        $this->assertEquals('Publié', trim($row->filter('td')->eq(5)->text()));
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(1);
        $this->assertEmpty(trim($row->filter('td')->eq(1)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals("    Test", trim($row->filter('td')->eq(3)->text()));
        $this->assertEquals('/test', $row->filter('td')->eq(4)->filter('a')->text());
        $this->assertEquals('Publié', trim($row->filter('td')->eq(5)->text()));
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(2);
        $this->assertEmpty(trim($row->filter('td')->eq(1)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals('Root 2', trim($row->filter('td')->eq(3)->text()));
        $this->assertCount(0, $row->filter('td')->eq(4)->filter('a'));
        $this->assertEquals('Publié', trim($row->filter('td')->eq(5)->text()));
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(3);
        $this->assertEmpty(trim($row->filter('td')->eq(1)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals("    Test 2", trim($row->filter('td')->eq(3)->text()));
        $this->assertEquals('/test-2', $row->filter('td')->eq(4)->filter('a')->text());
        $this->assertEquals('Jamais publié', trim($row->filter('td')->eq(5)->text()));
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
        $crawler = $client->request('GET', '/admin/menus/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->last()->filter('td')->eq(5)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/menus/batch"]')->form();
        $form['idx'][3]->tick();
        $crawler = $client->submit($form, array('action' => 'publish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/menus/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/menus/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/menus\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->last()->filter('td')->eq(5)->text()));
    }

    /**
     * Test unpublish
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testUnpublish()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );
        $crawler = $client->request('GET', '/admin/menus/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(2)->filter('td')->eq(5)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/menus/batch"]')->form();
        $form['idx'][2]->tick();
        $crawler = $client->submit($form, array('action' => 'unpublish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/menus/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/menus/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/menus\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(2)->filter('td')->eq(5)->text()));
    }

    /**
     * Test delete
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testDelete()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );
        $crawler = $client->request('GET', '/admin/menus/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(4, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/menus/batch"]')->form();
        $form['idx'][2]->tick();
        $crawler = $client->submit($form, array('action' => 'delete'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/menus/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/menus/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/menus\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
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
        $crawler = $client->request('GET', '/admin/menus/create');
        $this->isSuccessful($client->getResponse());

        // Test displayed fields
        $this->assertCount(3, $crawler->filter('fieldset'));
        $this->assertCount(1, $crawler->filter('input[id$=_startedAt]'));
        $this->assertCount(1, $crawler->filter('input[id$=_endedAt]'));
        $this->assertCount(4, $crawler->filter('select[id$=_parent] option'));
        $this->assertCount(0, $crawler->filter('select[id$=_parent] option:selected'));

        // Test displayed error messages
        $crawler = $client->submit($crawler->filter('form[action^="/admin/menus/create"]')->form());
        $this->isSuccessful($client->getResponse());
        $this->assertCount(2, $crawler->filter('.control-group.error'));

        // Test submit
        $formName = preg_replace('/^([^\[]+).*$/', '$1', $crawler->filter('form[action^="/admin/menus/create"] input')->first()->attr('name'));
        $form     = $crawler->filter('form[action^="/admin/menus/create"]')->form(array(
                $formName => array(
                    'title' => 'Test 3',
                    'url' => '/test-3',
                    'image' => true
                )
            )
        );
        $form[$formName]['file']->upload(__DIR__.'/../Fixtures/test.jpg');
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getResponse()->getContent());
        $crawler = $client->followRedirect();
        $this->assertCount(0, $crawler->filter('.control-group.error'));
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/menus\/\d+\/edit$/', $client->getRequest()->getUri());

        // Test updated list
        $crawler = $client->click($crawler->filter('a[href="/admin/menus/list"]')->link());
        $this->isSuccessful($client->getResponse());
        $this->assertCount(5, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(2);
        $this->assertEmpty(trim($row->filter('td')->eq(1)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals("    Test 3", trim($row->filter('td')->eq(3)->text()));
        $this->assertEquals('/test-3', $row->filter('td')->eq(4)->filter('a')->text());
        $this->assertEquals('Jamais publié', trim($row->filter('td')->eq(5)->text()));
    }
}