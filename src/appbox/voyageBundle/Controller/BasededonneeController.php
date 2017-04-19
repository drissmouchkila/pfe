<?php

namespace appbox\voyageBundle\Controller;

use appbox\voyageBundle\Entity\personne;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;


class BasededonneeController extends Controller
{
    /**
     * @Route("/ajouter")
     */
    public function indexAction(Request $request)
    {
        $personne = new personne();
        $form = $this->createFormBuilder($personne)
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('Email',TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getform();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

             $em = $this->getDoctrine()->getManager();
             $em->persist($personne);
             $em->flush();

            return $this->redirect('appboxvoyageBundle:basetest:Ajouter2.html.twig',array('pers' => $personne));
        }

        return $this->render('appboxvoyageBundle:basetest:Ajouter.html.twig',array('form' => $form->createView() ));
    }

    /**
     * @Route("/utilisateur/{id}",name="affichervoyage", requirements={"page": "\d+"})
     */
    public function afficherutiliAction($id){
              $em = $this->getDoctrine()->getManager();
              $voyages = $em->getRepository("appboxvoyageBundle:personne_voyage")->findBy(array('personne'=>$id ));
              if($voyages){
               foreach($voyages as $voyage){
                   $list[] = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:voyage")
                            ->find($voyage->getVoyage());
               }

                return $this->render('appboxvoyageBundle:basetest:Afficher.html.twig',array('list'=>$list));
              }
             return new Response("n'est pas Erreur");

    }
}
