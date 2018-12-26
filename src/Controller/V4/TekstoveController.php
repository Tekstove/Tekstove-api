<?php

namespace App\Controller\V4;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class TekstoveController extends AbstractController
{
    private $serializer;
    private $groups;

    public function __construct(SerializerInterface $serializer, RequestStack $r)
    {
        $this->serializer = $serializer;
        $groups = $r->getCurrentRequest()->query->get('groups');
        $this->setGroups($groups);
    }

    protected function setGroups(array $groups)
    {
        // @FIXME remove snsitive data!
        array_walk($groups, function (&$item) { $item = strtolower($item); });
        $this->groups = $groups;
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function handleData($data): Response
    {
        if (is_object($data)) {
            $viewData = $this->serializer->serialize(
                [
                    'item' => $data
                ],
                'json',
                [
                    'groups' => $this->groups,
                ]
            );
        } else {
            throw new \RuntimeException("Not implemented");
        }

        return new Response($viewData);
    }
}
