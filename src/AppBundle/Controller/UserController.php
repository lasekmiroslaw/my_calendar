<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @View()
     */
    public function getAction($id)
    {
        return $this->getDoctrine()->getRepository(User::class)->findUser($id);
    }

    /**
     * @View(statusCode=405)
     */
    public function cgetAction()
    {
      throw new MethodNotAllowedHttpException([], "Method not allowed");
        // return [
        //   'users' => $this->getDoctrine()->getRepository(User::class)->findUsers()
        // ];
    }
}
