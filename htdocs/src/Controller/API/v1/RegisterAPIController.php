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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/v1/register")
 */
class RegisterAPIController extends AbstractController
{
    /**
     * @Route("/", name="api_v1_register", methods={"POST"}, options={"expose"=true})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader, MailerInterface $mailer): JsonResponse
    {

        $json = $request->request->all();

        if(sizeof($json) !== 12) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'Internal Server Error. Please try again later.'
            ]);
        }

        $cv = $request->files->get('cv');

        if(!$cv) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'Internal Server Error. Please try again later.'
            ]);
        }

        $em = $this->getDoctrine()->getManager();

        $user_check = $em->getRepository('App:User')->findOneBy(['email' => $json['email']]);

        if($user_check) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'E-Mail already registered.'
            ]);
        }

        $user = new User();

        $user->setFirstname($json['firstname']);
        $user->setLastname($json['lastname']);
        $user->setAddress($json['address']);
        $user->setPronouns($json['pronouns'] ?: null);

        $hash = bin2hex(random_bytes(32));

        while($em->getRepository('App:User')->findOneBy(['email_hash' => $hash])) {
            $hash = bin2hex(random_bytes(32));
        }

        $user->setEmailHash($hash);

        $user->setEmail($json['email']);
//        $user->setEmailPublic($json['emailPublic']);

        $user->setPhone($json['phone'] ?: null);
//        $user->setPhonePublic($json['phonePublic']);

        $user->setPassword($passwordEncoder->encodePassword($user, $json['password']));

        $cvFileName = $fileUploader->upload($cv);
        $user->setCVFileName($cvFileName);

        $em->persist($user);

        $userAbout = new AboutUser();
        $userAbout->setUser($user);
        $userAbout->setCurrentJobTitle($json['currentJob'] ?: null);
        $userAbout->setCurrentEmployer($json['currentEmployer'] ?: null);
        $userAbout->setMotivation($json['motivation'] ?: null);
        $userAbout->setLookingFor($json['lookingFor'] ?: null);

        $em->persist($userAbout);
        $em->flush();


        //TODO: Send Mail
        $user->setIsActivated(true);
        $user->setEmailHash(null);

        $em->persist($user);
        $em->flush();
//        $email = (new TemplatedEmail())
//            ->from('no-reply@ats.profq.eu')
//            ->to($user->getEmail())
//            ->subject('Confirmation instructions')
//            ->htmlTemplate('mail_templates/registration.html.twig')
//            ->context([
//                'firstname' => $user->getFirstname(),
//                'lastname' => $user->getLastname(),
//                'hash' => $hash
//            ]);
//
//        $mailer->send($email);

        return new JsonResponse([
            'error' => false,
            'msg' => 'Registration successful!'
        ]);
    }
}
