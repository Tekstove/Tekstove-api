<?php

namespace App\Controller\User;

use App\Controller\TekstoveAbstractController as Controller;

use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Tekstove\ApiBundle\Model\User\Exception\UserHumanReadableException;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class PasswordResetController extends Controller
{
    public function postAction(Request $request)
    {
        $requestData = \json_decode($request->getContent(), true);
        $mail = $requestData['user']['mail'];
        $linkTemplate = $requestData['link'];
        $query = new \Tekstove\ApiBundle\Model\UserQuery();
        $query->filterByMail($mail, Criteria::EQUAL);
        $user = $query->findOne();

        if (!$user) {
            $error = new UserHumanReadableException("user not found");
            $error->addError('mail', 'user not found');
            $view = $this->handleData($request, $error->getErrors());
            $view->setStatusCode(400);
            return $view;
        }

        $key = $user->getId() . 'f' . sha1($user->getPassword() . $user->getMail() . $user->getApiKey());
        $linkParsed = str_replace('::key::', $key, $linkTemplate);

        $mailMessage = new \Swift_Message();
        $mailMessage->setFrom('tekstoveinfo@gmail.com');
        $mailMessage->setSubject('Заявка за нова парола');
        $mailMessage->setTo($mail);
        $mailMessage->setBody(
            $this->renderView(
                'user/mailPasswordReset.html.twig',
                [
                    'user' => $user,
                    'link' => $linkParsed,
                ]
            ),
            'text/html'
        );
        $this->get('mailer')->send($mailMessage);

        $this->getContext()->setGroups(['Details']);
        return $this->handleData(
            $request,
            [
                'status' => 'ok',
            ]
        );
    }
}
