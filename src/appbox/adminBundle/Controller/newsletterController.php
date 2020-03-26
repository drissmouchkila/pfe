<?php

namespace appbox\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
class newsletterController extends Controller
{
    /**
     * @Route("/gc/listnewsletter",name="listnewsletterpath")
     */
    public function listnewsletterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        $list = $em->getRepository('appboxvoyageBundle:newsletter')->findAll();
        return $this->render('appboxadminBundle:newsletter:listnewsletter.html.twig', array('list' => $list,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }
    /**
     * @Route("/gc/Suppnewsletter",name="suppnewsletterpath")
     */
    public function suppnewsletterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('appboxvoyageBundle:newsletter')->find($request->get('id'));
        $em->remove($list);
        $em->flush();
        return $this->redirectToRoute('listnewsletterpath');
    }
    /**
     * @Route("/gc/Envoyernewsletter",name="Envoyernewsletter")
     */
    public function envoyernewsletter(Request $request){
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $list = $em->getRepository('appboxvoyageBundle:newsletter')->findAll();
            $message = \Swift_Message::newInstance();

            $message->setSubject($request->get('objet'))
                ->setFrom('ayoublaw123@gmail.com');
                foreach($list as $liste) {
                    $message->addBcc($liste->getEmail());
                        }
                $message->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/Emailnewsletter.html.twig',
                        array('text'=>$request->get('text'),'image'=> $message->embed(\Swift_Image::fromPath('http://localhost:81/pfe/web/bundles/appboxvoyageBundle/image/logoblack.png')) )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('listnewsletterpath');
        }
        return $this->render('appboxadminBundle:Inbox:envoyerEmailnewsletter.html.twig',array('newcomm'=>$newcomm,'message'=>$newmessage,'newreser'=>$newreser));
    }
}