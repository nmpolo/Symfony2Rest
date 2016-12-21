<?php

namespace Nmpolo\RestBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Nmpolo\RestBundle\Entity\Organisation;
use Nmpolo\RestBundle\Entity\User;
use Nmpolo\RestBundle\Entity\UserRepository;

class UserController implements ClassResourceInterface
{
    private $manager;

    private $repo;

    private $formFactory;

    private $router;

    /**
     * Controller constructor
     * @var ObjectManager $manager
     * @var UserRepository $repo
     * @var FormFactoryInterface $formFactory
     * @var RouterInterface $router
     */
    public function __construct(ObjectManager $manager, UserRepository $repo, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * Retrieve all users for an organisation
     * @var Organisation $organisation
     * @return Collection
     *
     * @Rest\View()
     */
    public function cgetAction(Organisation $organisation)
    {
        $users = $this->repo->findByOrganisation($organisation);
        return $users;
    }

    /**
     * Retrieve a user
     * @var Organisation $organisation
     * @var User $user
     * @return User
     *
     * @Rest\View()
     * @ParamConverter("user", options={"mapping": {"organisation": "organisation", "user": "id"}})
     */
    public function getAction(Organisation $organisation, User $user)
    {
        return $user;
    }

    /**
     * Create a user for an organisation
     * @var Organisation $organisation
     * @var Request $request
     * @return View|FormInterface
     */
    public function cpostAction(Organisation $organisation, Request $request)
    {
        $user = new User($organisation);
        $form = $this->formFactory->createNamed('', 'Nmpolo\RestBundle\Form\UserType', $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush($user);

            $url = $this->router->generate(
                'get_organisation_user',
                array('organisation' => $organisation->getId(), 'user' => $user->getId())
            );
            return View::createRedirect($url, Codes::HTTP_CREATED);
        }

        return $form;
    }

    /**
     * Update a user
     * @var Organisation $organisation
     * @var User $user
     * @var Request $request
     * @return View|FormInterface
     *
     * @ParamConverter("user", options={"mapping": {"organisation": "organisation", "user": "id"}})
     */
    public function putAction(Organisation $organisation, User $user, Request $request)
    {
        $form = $this->formFactory->createNamed('', 'Nmpolo\RestBundle\Form\UserType', $user, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->manager->flush($user);

            return View::create(null, Codes::HTTP_NO_CONTENT);
        }

        return $form;
    }

    /**
     * Delete a user
     * @var Organisation $organisation
     * @var User $user
     * @return View
     * 
     * @ParamConverter("user", options={"mapping": {"organisation": "organisation", "user": "id"}})
     */
    public function deleteAction(Organisation $organisation, User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush($user);

        return View::create(null, Codes::HTTP_NO_CONTENT);
    }
}
