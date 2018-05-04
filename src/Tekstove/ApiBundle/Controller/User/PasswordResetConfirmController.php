<?php

namespace Tekstove\ApiBundle\Controller\User;

use Tekstove\ApiBundle\Controller\TekstoveAbstractController as Controller;

use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\User\Exception\UserHumanReadableException;

/**
 * Description of PasswordResetConfirmController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class PasswordResetConfirmController extends Controller
{
    public function postAction(Request $request)
    {
        $requestData = \json_decode($request->getContent(), true);
        $givenKey = $requestData['key'];

        $matches = [];
        preg_match('/^([0-9]+)f(.+)$/', $givenKey, $matches);
        $userId = $matches[1];
        $hash = $matches[2];

        $query = new \Tekstove\ApiBundle\Model\UserQuery();
        $user = $query->findOneById($userId);

        if (!$user) {
            $error = new UserHumanReadableException("user not found");
            $error->addError('user', 'user not found');
            $view = $this->handleData($request, $error->getErrors());
            $view->setStatusCode(400);
            return $view;
        }

        $expectedKey = $user->getId() . 'f' . sha1($user->getPassword() . $user->getMail() . $user->getApiKey());

        if ($expectedKey !== $givenKey) {
            $error = new UserHumanReadableException("Invalid key");
            $error->addError('key', 'Invalid key');
            $view = $this->handleData($request, $error->getErrors());
            $view->setStatusCode(400);
            return $view;
        }

        $randomPassword = sha1($user->getPassword() . uniqid());
        $passwordShort = substr($randomPassword, 0, 8);

        $user->setPassword(md5($passwordShort));

        // we do not want to validate user on password reset.
        // If there are any issues, they will be displayed to user after login
        // $user->save();
        $query->update(
            [
                'Password' => $user->getPassword(),
            ]
        );

        $mailMessage = \Swift_Message::newInstance();
        $mailMessage->setFrom('tekstoveinfo@gmail.com');
        $mailMessage->setSubject('Нова парола');
        $mailMessage->setTo($user->getMail());
        $mailMessage->setBody(
            $this->renderView(
                '@tekstoveApiBundle/user/mailPasswordConfirm.html.twig',
                [
                    'user' => $user,
                    'password' => $passwordShort,
                ]
            ),
            'text/html'
        );
        $this->get('mailer')->send($mailMessage);

        $this->getContext()->setGroups(['Details']);
        return $this->handleData(
            $request,
            $user
        );
    }
}
