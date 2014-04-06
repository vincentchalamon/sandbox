<?php

/*
 * This file is part of the MyCms bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\CmsBundle\Controller;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Download bills & quotes
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class ScreenshotController extends Controller
{

    /**
     * Generate screenshot from url
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function generateAction(Request $request)
    {
        if (!$request->get('url')) {
            throw $this->createNotFoundException();
        }
        $url      = rtrim(preg_replace('/\-{2,}/i', '-', preg_replace('/[^A-z\d\-_]+/i', '-', $request->get('url'))), '-');
        $filename = sprintf('%s/screenshots/%s.jpg', $this->container->getParameter('kernel.upload_dir'), $url);
        if (!is_file($filename)) {
            $this->get('knp_snappy.image')->generate($request->get('url'), $filename);
        }

        return new Response(file_get_contents($filename), 200, array(
                'Content-Type'        => 'image/jpg',
                'Content-Disposition' => 'inline'
            )
        );
    }
}
