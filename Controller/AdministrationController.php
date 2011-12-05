<?php

namespace ImiBorbas\VideoGalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ImiBorbas\VideoGalleryBundle\Entity\VideoGallery;
use ImiBorbas\VideoGalleryBundle\Form\VideoGalleryType;
use ImiBorbas\VideoGalleryBundle\Importing\YoutubeImporter;

/**
 * Administration controller.
 */
class AdministrationController extends Controller
{
    /**
     * Lists all VideoGallery entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->findAll();

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Displays a form to create a new VideoGallery entity.
     */
    public function newAction()
    {
        $entity = new VideoGallery();
        $form   = $this->createForm(new VideoGalleryType(), $entity);

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new VideoGallery entity.
     */
    public function createAction()
    {
        $entity  = new VideoGallery();
        $request = $this->getRequest();
        $form    = $this->createForm(new VideoGalleryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('videogallery_admin'));
            
        }

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing VideoGallery entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoGallery entity.');
        }

        $editForm = $this->createForm(new VideoGalleryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing VideoGallery entity.
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VideoGallery entity.');
        }

        $editForm   = $this->createForm(new VideoGalleryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('videogallery_admin_edit', array('id' => $id)));
        }

        return $this->render('ImiBorbasVideoGalleryBundle:VideoGallery:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a VideoGallery entity.
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VideoGallery entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('videogallery_admin'));
    }

    /**
     * Synchronizes all the videos in the playlist from a remote web service.
     */
    public function synchronizeAction($id)
    {
        $entityManager = $this->getDoctrine()->getEntityManager();

        $videoGallery = $entityManager->getRepository('ImiBorbasVideoGalleryBundle:VideoGallery')->findOneBy(array(
            'id' => $id,
        ));

        if (!$videoGallery) {
            return new Response('Not found.', 404);
        }

        $updater = $this->get('imi_borbas_video_gallery.video_gallery_updater');
        $updater->updateVideoGallery(
            $videoGallery,
            $this->container->getParameter('imi_borbas_video_gallery.thumbnail_directory')
        );

        return $this->redirect($this->generateUrl('videogallery_list', array(
            'slug' => $videoGallery->getSlug()
        )));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
