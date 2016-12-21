<?php

namespace Nmpolo\RestBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Nmpolo\RestBundle\Entity\Organisation;
use Nmpolo\RestBundle\Entity\OrganisationRepository;

class OrganisationController implements ClassResourceInterface
{
    private $manager;

    private $repo;

    private $formFactory;

    private $router;

    /**
     * Controller constructor
     * @var ObjectManager $manager
     * @var OrganisationRepository $repo
     * @var FormFactoryInterface $formFactory
     * @var RouterInterface $router
     */
    public function __construct(ObjectManager $manager, OrganisationRepository $repo, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * Retrieve all organisations
     * @return Collection
     *
     * @Rest\View()
     */
    public function cgetAction()
    {
        $organisations = $this->repo->findAll();
        return $organisations;
    }

    /**
     * Retrieve an organisation
     * @var Organisation $organisation
     * @return Organisation
     *
     * @Rest\View()
     */
    public function getAction(Organisation $organisation)
    {
        return $organisation;
    }

    /**
     * Create an organisation
     * @var Request $request
     * @return View|FormInterface
     */
    public function cpostAction(Request $request)
    {
        $organisation = new Organisation();
        $form = $this->formFactory->createNamed('', 'Nmpolo\RestBundle\Form\OrganisationType', $organisation);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->persist($organisation);
            $this->manager->flush($organisation);

            $url = $this->router->generate(
                'get_organisation',
                array('organisation' => $organisation->getId())
            );
            return View::createRedirect($url, Codes::HTTP_CREATED);
        }

        return $form;
    }

    /**
     * Update an organisation
     * @var Organisation $organisation
     * @var Request $request
     * @return View|FormInterface
     */
    public function putAction(Organisation $organisation, Request $request)
    {
        $form = $this->formFactory->createNamed('', 'Nmpolo\RestBundle\Form\OrganisationType', $organisation, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush($organisation);

            return View::create(null, Codes::HTTP_NO_CONTENT);
        }

        return $form;
    }

    /**
     * Delete an organisation
     * @var Organisation $organisation
     * @return View
     */
    public function deleteAction(Organisation $organisation)
    {
        $this->manager->remove($organisation);
        $this->manager->flush($organisation);

        return View::create(null, Codes::HTTP_NO_CONTENT);
    }
}
