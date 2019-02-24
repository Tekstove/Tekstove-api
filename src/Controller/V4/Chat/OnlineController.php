<?php

namespace App\Controller\V4\Chat;

use App\Controller\V4\TekstoveController;
use App\HttpFoundation\RequestIdentificator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\Request;

class OnlineController extends TekstoveController
{
    public function indexAction()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('user_id', 'id');
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('username', 'username');

        $query = $this->getDoctrine()->getManager()->createNativeQuery("
            SELECT
                user_id,
                username
            FROM
              chat_online
            WHERE
              `date` >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 2 MINUTE)
        ", $rsm);

        $result = $query->getArrayResult();

        return $this->handleArray(['items' => $result]);
    }

    public function postAction(Request $request)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('user_id', 'userId');

            $query = $this->getDoctrine()->getManager()->createNativeQuery(
                "
                    SELECT
                        user_id
                    FROM
                        chat_online
                    WHERE
                        user_id = :userId
                    LIMIT 1
                ",
                $rsm
            );

            $query->setParameter('userId', $user->getId());

            $lastOnline = $query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
            if ($lastOnline) {
                $this->getDoctrine()->getConnection()->executeUpdate(
                    "
                        UPDATE
                            chat_online
                        SET
                            `date` = CURRENT_TIMESTAMP
                        WHERE
                          user_id = :userId
                        LIMIT 1
                    ",
                    [
                        'userId' => $user->getId(),
                    ]
                );
            } else {
                $this->getDoctrine()->getConnection()->executeUpdate(
                    "
                        INSERT INTO
                            chat_online (`date`, username, user_id)
                        VALUES(CURRENT_TIMESTAMP, :username, :userId)
                    ",
                    [
                        'userId' => $user->getId(),
                        'username' => $user->getUsername(),
                    ]
                );
            }
        } else {
            // anonymous user!
            $username = (new RequestIdentificator())->identify($request);

            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('username', 'username');

            $query = $this->getDoctrine()->getManager()->createNativeQuery(
                "
                    SELECT
                        username
                    FROM
                        chat_online
                    WHERE
                        username = :username
                        AND user_id IS NULL
                    LIMIT 1
                ",
                $rsm
            );

            $query->setParameter('username', $username, 'string');

            $lastOnline = $query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
            if ($lastOnline) {
                $this->getDoctrine()->getConnection()->executeUpdate(
                    "
                        UPDATE
                            chat_online
                        SET
                            `date` = CURRENT_TIMESTAMP
                        WHERE
                             username = :username
                        LIMIT 1
                    ",
                    [
                        'username' => $username,
                    ],
                    [
                        'username' => 'string',
                    ]
                );
            } else {
                $this->getDoctrine()->getConnection()->executeUpdate(
                    "
                        INSERT INTO
                            chat_online (`date`, username, user_id)
                        VALUES (CURRENT_TIMESTAMP, :username, :userId)
                    ",
                    [
                        'userId' => null,
                        'username' => $username,
                    ]
                );
            }
        }

        return $this->handleArray([]);
    }
}
