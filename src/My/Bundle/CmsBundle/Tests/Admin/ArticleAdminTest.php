<?php

/*
 * This file is part of the MyCms bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Test Article admin
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ArticleAdminTest extends WebTestCase
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
        $client->request('GET', '/admin/articles/list');
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('sonata_user_admin_security_login', array(), true)));

        // Successful
        $this->fetchContent('/admin/articles/list', 'GET', array(
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
        $crawler = $this->fetchCrawler('/admin/articles/list', 'GET', array(
                'username' => 'admin',
                'password' => '4dm1n'
            )
        );

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $row = $crawler->filter('table.table.table-bordered.table-striped tbody tr')->first();
        $this->assertEquals('Accueil', trim($row->filter('td')->eq(1)->text()));
        $this->assertRegExp('/^\/admin\/articles\/\d+\/edit$/', $row->filter('td')->eq(1)->filter('a')->attr('href'));
        $this->assertEquals('/', trim($row->filter('td')->eq(2)->text()));
        $this->assertEquals('Publié', trim($row->filter('td')->eq(3)->text()));
        $this->assertEmpty(trim($row->filter('td')->eq(4)->text()));
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
        $crawler = $client->request('GET', '/admin/articles/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/articles/batch"]')->form();
        $form['idx'][1]->tick();
        $crawler = $client->submit($form, array('action' => 'delete'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/articles/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/articles/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/articles\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
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
        $crawler = $client->request('GET', '/admin/articles/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(3)->text()));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(1)->filter('td')->eq(3)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/articles/batch"]')->form();
        $form['idx'][0]->tick();
        $form['idx'][1]->tick();
        $crawler = $client->submit($form, array('action' => 'publish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/articles/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/articles/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/articles\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(3)->text()));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(1)->filter('td')->eq(3)->text()));
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
        $crawler = $client->request('GET', '/admin/articles/list');

        // Test displayed data in list
        $this->assertCount(1, $crawler->filter('table.table.table-bordered.table-striped'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'));
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(3)->text()));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(1)->filter('td')->eq(3)->text()));

        // Submit form
        $form = $crawler->filter('form[action^="/admin/articles/batch"]')->form();
        $form['idx'][0]->tick();
        $form['idx'][1]->tick();
        $crawler = $client->submit($form, array('action' => 'unpublish'));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form[action^="/admin/articles/batch"]'));
        $client->submit($crawler->filter('form[action^="/admin/articles/batch"]')->form());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check updated list
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/articles\/list/', $client->getRequest()->getUri());
        $this->assertCount(1, $crawler->filter('.alert-success'));
        $this->assertCount(2, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
        $this->assertEquals('Publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->first()->filter('td')->eq(3)->text()));
        $this->assertEquals('Jamais publié', trim($crawler->filter('table.table.table-bordered.table-striped tbody tr')->eq(1)->filter('td')->eq(3)->text()));
    }

    /**
     * Test homepage form
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testHomepageForm()
    {
        $this->loadFixtures(array('My\Bundle\CmsBundle\Tests\Fixtures\TestData'));

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            )
        );
        $crawler = $client->request('GET', '/admin/articles/list');
        $crawler = $client->click($crawler->filter('table.table.table-bordered.table-striped tbody tr td')->eq(1)->filter('a')->link());
        $this->assertTrue($client->getResponse()->isOk());

        // Test displayed fields
        $this->assertCount(3, $crawler->filter('fieldset'));
        $this->assertCount(0, $crawler->filter('fieldset')->first()->filter('input[id$=_url]'));
        $this->assertEquals('Accueil', $crawler->filter('fieldset')->first()->filter('input')->first()->attr('value'));
        $this->assertCount(2, $crawler->filter('select[id$=_template] option'));
        $this->assertRegExp('/\d+/', $crawler->filter('select[id$=_template] option:selected')->attr('value'));
    }

    /**
     * Test form
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     */
    public function testForm()
    {
        $this->loadFixtures(array(
                'Vince\Bundle\CmsBundle\DataFixtures\ORM\CmsData',
                'My\Bundle\CmsBundle\Tests\Fixtures\TestData'
            )
        );

        // Successful
        $client = $this->makeClient(array(
                'username' => 'admin',
                'password' => '4dm1n'
            ), array('HTTP_HOST' => 'blog.local')
        );
        $crawler = $client->request('GET', '/admin/articles/create');
        $this->isSuccessful($client->getResponse());

        // Test displayed fields
        $this->assertCount(4, $crawler->filter('fieldset'));
        $this->assertCount(1, $crawler->filter('fieldset')->first()->filter('input[id$=_url]'));
        $this->assertEmpty($crawler->filter('fieldset')->first()->filter('input')->first()->attr('value'));
        $this->assertCount(1, $crawler->filter('input[id$=_startedAt]'));
        $this->assertCount(1, $crawler->filter('input[id$=_endedAt]'));
        $this->assertCount(2, $crawler->filter('select[id$=_template] option'));
        $this->assertCount(0, $crawler->filter('select[id$=_template] option:selected'));

        // Test filled SEO
        $seo = $crawler->filter('fieldset')->eq(2);
        $this->assertEquals('fr', $seo->filter('input[id$=_language]')->attr('value'));
        $this->assertEquals('article', $seo->filter('input[id$=type]')->attr('value'));
        $this->assertEquals('summary', $seo->filter('input[id$=card]')->attr('value'));
        $this->assertEquals('Vincent CHALAMON', $seo->filter('input[id$=_author]')->attr('value'));
        $this->assertEmpty($seo->filter('input[id$=creator]')->attr('value'));

        // Test displayed error messages
        $crawler = $client->submit($crawler->filter('form[action^="/admin/articles/create"]')->form());
        $this->isSuccessful($client->getResponse());
        $this->assertCount(2, $crawler->filter('.control-group.error'));

        // Test submit
        $formName = preg_replace('/^([^\[]+).*$/', '$1', $crawler->filter('form[action^="/admin/articles/create"] input')->first()->attr('name'));
        $client->submit($crawler->filter('form[action^="/admin/articles/create"]')->form(array(
                    $formName => array(
                        'title' => 'Test',
                        'summary' => 'Hello World!'
                    )
                )
            )
        );
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertCount(0, $crawler->filter('.control-group.error'));
        $this->assertRegExp('/^http\:\/\/blog\.local\/admin\/articles\/\d+\/edit$/', $client->getRequest()->getUri());

        // Test updated list
        $crawler = $client->click($crawler->filter('a[href="/admin/articles/list"]')->link());
        $this->isSuccessful($client->getResponse());
        $this->assertCount(3, $crawler->filter('table.table.table-bordered.table-striped tbody tr'), $client->getResponse()->getContent());
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFixtures(array $classNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $return = parent::loadFixtures($classNames, $omName, $registryName, $purgeMode);

        // Need to clear router cache
        $files = Finder::create()->files()->name('/app[A-z]+Url(?:Generator|Matcher)\.php/')->in($this->getContainer()->getParameter('kernel.cache_dir'));
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            unlink($file->__toString());
        }

        return $return;
    }
}