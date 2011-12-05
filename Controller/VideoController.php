<?php

namespace ImiBorbas\VideoGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Video controller.
 */
class VideoController extends Controller
{
    /**
     * Finds and displays a Video entity.
     */
    public function showAction($id)
    {
        $entityManager = $this->getDoctrine()->getEntityManager();

        $video = $entityManager->getRepository('ImiBorbasVideoGalleryBundle:Video')->find($id);

        if (!$video) {
            return new Response('Not found.', 404);
        }

        return $this->render('ImiBorbasVideoGalleryBundle:Video:show.html.twig', array(
            'video' => $video
        ));
    }
}
