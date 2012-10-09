<?php

namespace Nmpolo\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\Rest\Util\Codes;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function cgetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NmpoloRestBundle:Organisation')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @Rest\View()
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NmpoloRestBundle:Organisation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find organisation entity');
        }

        return array(
            'entity' => $entity,
        );
    }
}

