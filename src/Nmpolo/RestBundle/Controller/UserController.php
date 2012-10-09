<?php

namespace Nmpolo\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\Rest\Util\Codes;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{
    /**
     * @Rest\View()
     */
    public function cgetAction(Request $request, $organisationId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NmpoloRestBundle:User')->findBy(array(
            'organisation' => $organisationId,
        ));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @Rest\View()
     */
    public function getAction($organisationId, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NmpoloRestBundle:User')->findOneBy(array(
            'id'=>$id,
            'organisation' => $organisationId,
        ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find user entity');
        }

        return array(
            'entity' => $entity,
        );
    }
}

