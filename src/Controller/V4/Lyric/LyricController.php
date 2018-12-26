<?php

namespace App\Controller\V4\Lyric;

use App\Controller\V4\TekstoveController;
use App\Entity\Lyric\Lyric;
use Symfony\Component\Serializer\SerializerInterface;

class LyricController extends TekstoveController
{
    public function view(SerializerInterface $serializer, string $id)
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $entity = $repo->findOneBy(['id' => $id]);

        return $this->handleData($entity);
    }
}
