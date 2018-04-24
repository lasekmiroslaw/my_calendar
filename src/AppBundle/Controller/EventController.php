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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @View(statusCode=201)
     */
    public function postAction(Request $request)
    {
        $event = new Event();
        $event->setUser($this->getUser());
        $form = $this->createForm(EventType::class, $event);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $form;
        }

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return  $event;
    }

    /**
     * @View()
     */
    public function putAction($id, Request $request)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->getEvent($id);
        $form = $this->createForm(EventType::class, $event);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $form;
        }

        $this->entityManager->flush();

        return  $event;
    }

    /**
     * @View()
     */
    public function getAction($id)
    {
        return $this->getDoctrine()->getRepository(Event::class)->getEvent($id);
    }

    /**
     * @View()
     */
    public function cgetAction()
    {
        return ['events' => $this->getDoctrine()->getRepository(Event::class)->findAll()];
    }
}
