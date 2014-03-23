<?php

/*
 * This file is part of the MyQuote bundle.
 *
 * (c) Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace My\Bundle\QuoteBundle\Controller;

use My\Bundle\QuoteBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Search customers
 *
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
class CustomerController extends Controller
{

    /**
     * Search customers
     *
     * @author Vincent Chalamon <vincentchalamon@gmail.com>
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $repository = $this->get('my.repository.customer');
        $results    = $repository->search($request->get('query'));
        $customers  = array();
        foreach ($results as $result) {
            /** @var Customer $result */
            $customers[] = array(
                'id'      => $result->getId(),
                'value'   => $result->__toString(),
                'address' => $result->getAddress(),
                'zipcode' => $result->getZipcode(),
                'city'    => $result->getCity(),
                'email'   => $result->getEmail()
            );
        }

        return new JsonResponse($customers);
    }
}
