<?php

namespace App\Controller\V4\Artist;

use App\Controller\V4\TekstoveController;
use App\Entity\Artist\Artist;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistController extends TekstoveController
{
    public function indexAction(Request $request): Response
    {
        $repo = $this->getDoctrine()->getRepository(Artist::class);

        return $this->handleRepository($repo);
    }

    public function getAction(string $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Artist::class);
        $entity = $repo->findOneBy(['id' => $id]);

        if ($entity === null) {
            throw $this->createNotFoundException('Artist not found');
        }

        return $this->handleEntity($entity);
    }
}
