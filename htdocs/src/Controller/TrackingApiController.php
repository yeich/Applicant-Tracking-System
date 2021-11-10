<?php

namespace App\Controller;

use App\Entity\Tracking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tracking")
 */
class TrackingApiController extends AbstractController
{
    /**
     * @Route("/{job_id}/{source}/{type}", name="api_tracking")
     */
    public function track($job_id, $source, $type = 'job_clicked'): JsonResponse
    {

        $em = $this->getDoctrine()->getManager();

        $tracking = new Tracking();

        $tracking->setJob('JOB_NAME'); //TODO: Get Job by job_id -> change type inside Tracking Entity to JOB (ENTITY)
        $tracking->setSource($source);
        $tracking->setType($type);

        $em->persist($tracking);
        $em->flush();

        return new JsonResponse([
            'error' => false,
            'msg' => 'Tracking Event created.'
        ]);
    }
}
