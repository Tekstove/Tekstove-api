<?php

namespace App\Controller\V4\Chat;

use App\Controller\V4\TekstoveController;
use App\Entity\Chat\Message;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends TekstoveController
{
    public function indexAction(Request $request): Response
    {
        $messageRepo = $this->getDoctrine()->getRepository(Message::class);
        /* @var $messageRepo EntityRepository */
        $queryBuilder = $messageRepo->createQueryBuilder('d');

        if (empty($request->get('filter'))) {
            $maxIdQueryBuilder = $messageRepo->createQueryBuilder('d');
            $maxIdQueryBuilder->andWhere(
                $maxIdQueryBuilder->expr()->isNull('d.idOverride')
            );
            $maxIdQueryBuilder->addOrderBy('d.id', 'DESC');
            $maxIdQueryBuilder->setFirstResult(21);
            $maxIdQueryBuilder->setMaxResults(1);
            $messageToStart = $maxIdQueryBuilder->getQuery()->getOneOrNullResult();

            $startId = 0;
            if ($messageToStart) {
                $startId = $messageToStart->getId();
            }

            $queryBuilder->andWhere(
                $queryBuilder->expr()->gt('d.id', ':startId')
            );

            $queryBuilder->setParameter('startId', $startId, Type::INTEGER);
        }

        $queryBuilder->addOrderBy('d.id');

        return $this->handleQueryBuilder($queryBuilder);
    }
}
