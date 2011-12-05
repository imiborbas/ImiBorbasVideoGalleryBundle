<?php

namespace ImiBorbas\VideoGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * VideoGallery controller.
 */
class VideoGalleryController extends Controller
{
    /**
     * Lists all videos contained within a VideoGallery.
     */
    public function listAction($slug)
    {
        $entityManager = $this->getDoctrine()->getEntityManager();

        $videoGallery = $entityManager->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->findOneBy(array(
            'slug' => $slug,
        ));

        if (!$videoGallery) {
            return new Response('Not found.', 404);
        }

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:list.html.twig', array(
            'videoGallery' => $videoGallery
        ));
    }
}
