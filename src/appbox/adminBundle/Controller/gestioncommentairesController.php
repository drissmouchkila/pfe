<?php

namespace appbox\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class gestioncommentairesController extends Controller
{
    /**
     * @Route("/admin/touscommentaires",name="touscommentaires")
     */
    public function touscommentairesAction()
    {
       $em = $this->getDoctrine()->getManager();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findAll();
        }
        else{
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('employee'=>$employe->getId()));
        }
        $review = $em->getRepository('appboxvoyageBundle:review')->tousreview();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        return $this->render('appboxadminBundle:Gestioncommentaires:touscommentaires.html.twig',array(
            'newreser'=>$newreser,'voyages'=>$voyage,'review'=>$review,'message'=>$newmessage,'newcomm'=>$newcomm));
    }
    /**
     * @Route("/admin/supcomentaire",name="supcommentaireadmin")
     */
    public function supprimercomentaireAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository("appboxvoyageBundle:review")->find($request->get('id'));
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($review->getVoyage());
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
        if($voyage->getEmployee() == $employe->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $voyage->setNbrcommentaires($voyage->getNbrcommentaires() - 1);
        $user = $em->getRepository('appboxUserBundle:User')->findOneBy(array('username'=>$review->getNom()));
        $user->setNbrcommtotal($user->getNbrcommtotal() - 1);
        $user->setNbrcommbloqS($user->getNbrcommbloqS() + 1);
        $em->persist($user);
        $em->persist($voyage);
        $em->remove($review);
        $em->flush();
        }
        return $this->redirectToRoute('touscommentaires');
    }
    /**
     * @Route("/admin/nouveaucommentaire",name="nouveaucommentaire")
     */
    public function nouveaucommentaire(){
        $em = $this->getDoctrine()->getManager();
        $nouveaucommentaire = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId'=>$this->getUser()->getid()));
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $voyagepersonnel = $em->getRepository('appboxvoyageBundle:voyage')->findAll();
        }
        else{
            $voyagepersonnel = $em->getRepository('appboxvoyageBundle:voyage')->findByemployee($employe->getId());

        }
        return $this->render('appboxadminBundle:Gestioncommentaires:nouveaucommentaires.html.twig',array('v'=>$voyagepersonnel,'newreser'=>$newreser,'message'=>$newmessage,'review'=>$nouveaucommentaire,'newcomm'=>$newcomm));
    }
    /**
     * @Route("/admin/validercommentaire",name="validercommentaire")
     */
    public function validercommentaire(Request $request){
        $em = $this->getDoctrine()->getManager();
        $nouveaucommentaire = $em->getRepository('appboxvoyageBundle:review')->find($request->get('id'));
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($nouveaucommentaire->getVoyage());
        $user = $em->getRepository('appboxUserBundle:User')->findOneByusername($nouveaucommentaire->getNom());
        $voyage->setNbrcommentaires($voyage->getNbrcommentaires()+1);
        $user->setNbrcommtotal($user->getNbrcommtotal()+1);
        $nouveaucommentaire->setvalider(true);
        $em->persist($user);
        $em->persist($voyage);
        $em->persist($nouveaucommentaire);
        $em->flush();
        return $this->redirectToRoute('nouveaucommentaire');
    }
}