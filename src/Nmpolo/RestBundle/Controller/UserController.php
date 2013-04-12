<?php

namespace Nmpolo\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\Rest\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Nmpolo\RestBundle\Entity\Organisation;
use Nmpolo\RestBundle\Entity\User;
use Nmpolo\RestBundle\Form\UserType;

class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Collection get action
     * @var Request $request
     * @var integer $organisationId Id of the entity's organisation
     * @return array
     *
     * @Rest\View()
     */
    public function cgetAction(Request $request, $organisationId)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NmpoloRestBundle:User')->findBy(
            array(
                'organisation' => $organisationId,
            )
        );

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Get action
     * @var integer $organisationId Id of the entity's organisation
     * @var integer $id Id of the entity
     * @return array
     *
     * @Rest\View()
     */
    public function getAction($organisationId, $id)
    {
        $entity = $this->getEntity($organisationId, $id);

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Collection post action
     * @var Request $request
     * @var integer $organisationId Id of the entity's organisation
     * @return View|array
     */
    public function cpostAction(Request $request, $organisationId)
    {
        $organisation = $this->getOrganisation($organisationId);
        $entity = new User();
        $entity->setOrganisation($organisation);
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectView(
                $this->generateUrl(
                    'get_organisation_user',
                    array(
                        'organisationId' => $entity->getOrganisation()->getId(),
                        'id' => $entity->getId()
                    )
                ),
                Codes::HTTP_CREATED
            );
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Put action
     * @var Request $request
     * @var integer $organisationId Id of the entity's organisation
     * @var integer $id Id of the entity
     * @return View|array
     */
    public function putAction(Request $request, $organisationId, $id)
    {
        $entity = $this->getEntity($organisationId, $id);
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->view(null, Codes::HTTP_NO_CONTENT);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete action
     * @var integer $organisationId Id of the entity's organisation
     * @var integer $id Id of the entity
     * @return View
     */
    public function deleteAction($organisationId, $id)
    {
        $entity = $this->getEntity($organisationId, $id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        return $this->view(null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * Get entity instance
     * @var integer $organisationId Id of the entity's organisation
     * @var integer $id Id of the entity
     * @return User
     */
    protected function getEntity($organisationId, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NmpoloRestBundle:User')->findOneBy(
            array(
                'id' => $id,
                'organisation' => $organisationId,
            )
        );

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find user entity');
        }

        return $entity;
    }

    /**
     * Get organisation instance
     * @var integer $id Id of the organisation
     * @return Organisation
     */
    protected function getOrganisation($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NmpoloRestBundle:Organisation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find organisation entity');
        }

        return $entity;
    }
}
