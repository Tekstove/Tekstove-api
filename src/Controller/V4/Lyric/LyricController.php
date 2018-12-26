<?php

namespace App\Controller\V4\Lyric;

use App\Controller\V4\TekstoveController;
use App\Entity\Lyric\Lyric;
use Symfony\Component\HttpFoundation\Response;

class LyricController extends TekstoveController
{
    public function view(string $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $entity = $repo->findOneBy(['id' => $id]);

        return $this->handleData($entity);
    }
}
