<?php

namespace App\Controller\V4\Publisher;

use App\Controller\V4\TekstoveController;
use App\Entity\Publisher\Publisher;
use Symfony\Component\HttpFoundation\Response;

class PublisherController extends TekstoveController
{
    public function getAction(string $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Publisher::class);
        $entity = $repo->findOneBy(['id' => $id]);

        if ($entity === null) {
            throw $this->createNotFoundException('Publisher not found');
        }

        return $this->handleEntity($entity);
    }
}
