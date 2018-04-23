<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Doctrine\ORM\EntityManagerInterface;


class EventController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function postAction(Request $request)
    {
        $form = $this->createForm(EventType::class, new Event());

        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }

        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();

        return $this->handleView(
            $this->view(
                [
                    'status' => 'ok',
                ],
                Response::HTTP_CREATED
            )
        );
    }

    /**
     * @View()
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getAction($id)
    {
        return $this->findEventById($id);
    }

    /**
     * @View()
     */
    public function cgetAction()
    {
        return ['events' => $this->getDoctrine()->getRepository(Event::class)->findAll()];
    }

    /**
     * @param string $id
     *
     * @return Album
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function findEventById(string $id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if (null === $event) {
          throw $this->createNotFoundException(
              'No event found for id '.$id
          );
        }

        return $event;
    }
}
