<?php

namespace App\Controller\User;

use App\Controller\TekstoveAbstractController;
use Symfony\Component\HttpFoundation\Request;

use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\User\Exception\UserHumanReadableException;

/**
 * Description of RegisterController
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class RegisterController extends TekstoveAbstractController
{
    public function indexAction(Request $request)
    {
        $recaptchaKey = $this->container->getParameter('app.recaptcha.key');

        $request->request->set('groups', ['unused']);
        $returnData = [
            'item' => [
                'recaptcha' => [
                    'key' => $recaptchaKey,
                ]
            ],
        ];
        return $this->handleData($request, $returnData);
    }

    public function postAction(Request $request)
    {
        $repo = $this->get('tekstove.user.repository');
        /* @var $repo \Tekstove\ApiBundle\Model\User\UserRepository */
        $recaptchaSecret = $this->container->getParameter('app.recaptcha.secret');

        $requestData = json_decode($request->getContent(), true);
        $userData = $requestData['user'];
        $recaptchaData = $requestData['recaptcha'];

        $user = new User();

        try {
            $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret);
            $recaptchaResponse = $recaptcha->verify($recaptchaData['g-recaptcha-response']);
            if (!$recaptchaResponse->isSuccess()) {
                $recaptchaException = new UserHumanReadableException("Recaptcha validation failed");
                $recaptchaException->addError("recaptcha", "Validation failed");
                throw $recaptchaException;
            }

            if (empty($userData['termsAccepted'])) {
                $exception = new UserHumanReadableException("Validation failed");
                $exception->addError('termsAccepted', 'Not accepted');
                throw $exception;
            }

            $user->settermsAccepted(new \DateTime());
            $user->setUsername($userData['username']);
            $user->setMail($userData['mail']);
            $user->setPassword(
                $this->hashPassword(
                    $userData['password']
                )
            );

            $user->setapiKey(sha1(str_shuffle(uniqid())));
            $repo->save($user);
            $this->getContext()->setGroups(['Details']);

            return $this->handleData($request, $user);
        } catch (UserHumanReadableException $e) {
            $view = $this->handleData($request, $e->getErrors());
            $view->setStatusCode(400);

            return $view;
        }
    }

    /**
     * password hashing should be changed!
     * md5 is insecure!
     *
     * @param string $password
     * @return string
     */
    protected function hashPassword($password)
    {
        return md5($password);
    }
}
