<?php

namespace App\Controller\V4\Lyric;

use App\Controller\V4\TekstoveController;
use App\Entity\Lyric\Lyric;
use App\Entity\Lyric\Redirect;
use App\EventDispatcher\Lyric\LyricEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class LyricController extends TekstoveController
{
    public function indexAction(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        return $this->handleRepository($repo);
    }

    public function getAction(EventDispatcherInterface $eventDispatcher, string $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $entity = $repo->findOneBy(['id' => $id]);

        if ($entity === null) {
            $redirectRepo = $this->getDoctrine()->getRepository(Redirect::class);
            $redirect = $redirectRepo->findOneBy(['deletedId' => $id]);
            if ($redirect) {
                $view = $this->handleArray(
                    [
                        'redirect' => [
                            'id' => $redirect->getRedirectId(),
                        ],
                    ]
                );

                $view->setStatusCode(404);

                return $view;
            }
            throw $this->createNotFoundException('Lyric not found');
        }

        $viewEvent = new LyricEvent($entity);
        $eventDispatcher->dispatch('tekstove.lyric.view', $viewEvent);

        return $this->handleEntity($entity);
    }
}
