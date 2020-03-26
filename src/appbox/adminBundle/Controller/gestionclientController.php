<?php

namespace appbox\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class gestionclientController extends Controller
{
    /**
     * @Route("/gc/tousclient",name="tousclientpath")
     */
    public function gestionclientAction()
    {

        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $client = $em->getRepository('appboxUserBundle:User')->findAll();
        return $this->render('appboxadminBundle:GestionClient:tousclient.html.twig',array('clients'=>$client,'message'=>$newmessage,'newreser'=>$newreser,'newcomm'=>$newcomm));
    }
    /**
     * @Route("/gc/suppclient",name="suppclient")
     */
    public function suppclientAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('appboxUserBundle:User')->find($request->get('id'));
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute('tousclientpath');
    }


}
