<?php

namespace App\Controller\V4\Lyric;

use App\Entity\Lyric\Lyric;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LyricController extends AbstractController
{
    public function view(Request $request, string $id)
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $entity = $repo->findOneBy(['id' => $id]);
        /* @var $entity Lyric */

        $returnData = [
            'item' => [
                'sendDate' => $entity->getSendDate()->getTimestamp(),
            ],
        ];

        $response = new Response(json_encode($returnData));

        return $response;
    }
}
