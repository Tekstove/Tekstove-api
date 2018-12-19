<?php

namespace App\Controller\V4\Lyric;

use App\Entity\Lyric\Lyric;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class LyricController extends AbstractController
{
    public function view(SerializerInterface $serializer, string $id)
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $entity = $repo->findOneBy(['id' => $id]);
        /* @var $entity Lyric */

        $returnData = [
            'item' => [
                'sendDate' => $entity->getSendDate()->getTimestamp(),
            ],
        ];


        $r = $serializer->serialize(
            $entity,
            'json',
            [
                'groups' => [
                    'g2',
                    'g1',
                ],
            ]
        );



        dump($serializer);
        dump($entity);
        dump($r); die;


        $response = new Response(json_encode($returnData));

        return $response;
    }
}
