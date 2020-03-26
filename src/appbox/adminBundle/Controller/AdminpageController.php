<?php

namespace appbox\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminpageController extends Controller
{
    /**
     * @Route("/admin/dashboard",name="adminpath")
     */
    public function adminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        //CONFIGURATION
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'En cours'));
        $reservation =  $em->getRepository('appboxvoyageBundle:reservation')->findBy(array('paiement'=>'Non'));
        foreach ($voyage as $v) {
            if($v->getDatedebut() <= new \DateTime('now') ){
                $v->setEtat('termine');
                $v->setTyped('Normal');
                $em->persist($v);
                $em->flush();
            }
            $db = $v->getDatedebut()->modify('-7 Day') ;
            if($db <= new \DateTime('now') && $v->getEtat() != 'termine'){
              $v->setTyped('Dernier munite');
                $em->persist($v);
                $em->flush();
            }



        }
        foreach ($reservation as $r) {
            $date = $r->getDatemettreres()->modify('+2 Day');
            if ($date <= new \DateTime('now')) {
                $em->remove($r);
                $em->flush();
            }
        }

            //ENDCONFIGURATION
        return $this->render('appboxadminBundle:adminpage:adminpage.html.twig',array('newcomm'=>$newcomm,'newreser'=>$newreser,'message'=>$newmessage));
    }
}
