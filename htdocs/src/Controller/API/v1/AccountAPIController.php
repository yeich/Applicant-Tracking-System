<?php

namespace App\Controller\API\v1;

use App\Entity\AboutUser;
use App\Entity\User;
use App\Service\FileUploader;
use Couchbase\Document;
use PHPUnit\Util\Json;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/v1/account")
 */
class AccountAPIController extends AbstractController
{
    /**
     * @Route("/confirm/{hash}", name="api_v1_account_confirm", methods={"GET"})
     */
    public function confirm($hash): RedirectResponse
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('App:User')->findOneBy(['email_hash' => $hash]);

        if(!$user) {
            $this->addFlash('error', 'No Account found.');
        } else {

            $user->setIsActivated(true);
            $user->setEmailHash(null);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your Account has been activated.');
        }

        return new RedirectResponse($this->generateUrl('app_home'));
    }
}
