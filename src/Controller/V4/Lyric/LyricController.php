<?php

namespace App\Controller\V4\Lyric;

use App\Controller\V4\TekstoveController;
use App\Entity\AuthorizationInterface;
use App\Entity\Lyric\Lyric;
use App\Entity\Lyric\Redirect;
use App\EventDispatcher\Lyric\LyricEvent;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LyricController extends TekstoveController
{
    public function indexAction(Request $request): Response
    {
        $repo = $this->getDoctrine()->getRepository(Lyric::class);
        $qb = $repo->createQueryBuilder('d');
        $filters = $request->query->get('filters', []);
        $filterKeysToDelete = [];
        foreach ($filters as $filterKey => &$filter) {
            if ($filter['field'] == 'ArtistId' || $filter['field'] == 'artist') {
                $filter['field'] = 'd.artistLyrics.artist';
            } elseif ($filter['field'] == 'publisher') {
                $filter['field'] = 'd.publishers.id';
            } elseif ($filter['field'] == 'onlyAuthorized') {
                /* @var $qb QueryBuilder */
                $qb->leftJoin(
                    'd.artistLyrics',
                    'permissions_al'
                );

                $qb->leftJoin(
                    'permissions_al.artist',
                    'permissions_artist',
                    Query\Expr\Join::WITH,
                    'permissions_artist.authorization = :authorizationStatus'
                );

                $qb->setParameter('authorizationStatus', AuthorizationInterface::AUTHORIZATION_ALLOWED);

                $qb->leftJoin(
                    'd.publishers',
                    'permissions_publisher',
                    Query\Expr\Join::WITH,
                    'permissions_publisher.authorization = :authorizationStatus'
                );

                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->isNotNull('permissions_publisher.id'),
                        $qb->expr()->isNotNull('permissions_artist.id')
                    )
                );

                $filterKeysToDelete[] = $filterKey;
            }
        }

        foreach ($filterKeysToDelete as $key) {
            unset($filters[$key]);
        }
        $request->query->set('filters', $filters);

        return $this->handleQueryBuilder($qb);
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
