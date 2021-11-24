<?php

namespace App\Controller\API\v1;

use App\Entity\AboutUser;
use App\Entity\User;
use App\Service\FileUploader;
use Couchbase\Document;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader): JsonResponse
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

        return new JsonResponse([
            'error' => false,
            'msg' => 'Registration successful!'
        ]);
    }
}
