<?php

namespace appbox\adminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class InboxController extends Controller
{
    /**
     * @Route("/gc/inbox",name="inboxpath")
     */
    public function inboxAction(Request $request)
    {
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('appboxadminBundle:contact')->inbox();
        return $this->render('appboxadminBundle:Inbox:inbox.html.twig',array('contact' =>$contact,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }
    /**
     * @Route("/gc/inbox/supp",name="suppcontact")
     */
    public function suppcontactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('appboxadminBundle:contact')->find($request->get('id'));
        $em->remove($contact);
        $em->flush();
        return $this->redirectToRoute('inboxpath');
    }
    /**
     * @Route("/gc/detailinbox/{id}",name="detailinboxpath")
     */
    public function detailinboxAction($id,Request $request){

        $em = $this->getDoctrine()->getManager();
        $mail= $em->getRepository('appboxadminBundle:contact')->find($id);
        $mail->setVue(true);
        $em->persist($mail);
        $em->flush();
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        if($request->isMethod('POST')){
            $message = \Swift_Message::newInstance();

            $message->setSubject('Repose a votre message')
                ->setFrom('ayoublaw123@gmail.com')
                ->setTo($mail->getEmail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/Emailcontact.html.twig',
                        array('contact' => $mail,'text'=>$request->get('email'),'image'=> $message->embed(\Swift_Image::fromPath('http://localhost:81/pfe/web/bundles/appboxvoyageBundle/image/logoblack.png')) )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('inboxpath');

        }
        return $this->render('appboxadminBundle:Inbox:detailinbox.html.twig',array(
            'mail' =>$mail,
            'message'=>$newmessage,
            'newcomm'=>$newcomm,
            'newreser'=>$newreser));
    }

    /**
     * @Route("/gc/envoyeremail",name="envoyeremailpath")
     */
    public function EnvoyeremailAction(Request $request)
    {
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        $em = $this->getDoctrine()->getManager();
        $client = null;
        if($request->get('id')!= null){
        $client = $em->getRepository('appboxUserBundle:User')->find($request->get('id'));}
        if($request->isMethod('POST')){
            $client = $em->getRepository('appboxUserBundle:User')->findOneByemail($request->get('email'));
            $message = \Swift_Message::newInstance();

            $message->setSubject($request->get('objet'))
                ->setFrom('ayoublaw123@gmail.com')
                ->setTo($request->get('email'))
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/EmailSimple.html.twig',
                        array('client' => $client,'text'=>$request->get('text'),'image'=> $message->embed(\Swift_Image::fromPath('http://localhost:81/pfe/web/bundles/appboxvoyageBundle/image/logoblack.png')) )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('tousclientpath');
        }
        return $this->render('appboxadminBundle:Inbox:envoyerEmail.html.twig',array('client' =>$client,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));

    }
    /**
     * @Route("/gc/envoyeremailnonvalider",name="envoyeremailnonvaliderpath")
     */
    public function EnvoyeremailnonAction(Request $request)
    {
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository('appboxvoyageBundle:reservation')->find($request->get('id'));
        if($request->isMethod('POST')){
            $message = \Swift_Message::newInstance();

            $message->setSubject($request->get('objet'))
                ->setFrom('ayoublaw123@gmail.com')
                ->setTo($request->get('email'))
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/Emailnonvalider.html.twig',
                        array('client' => $client,'text'=>$request->get('text'),'image'=> $message->embed(\Swift_Image::fromPath('http://localhost:81/pfe/web/bundles/appboxvoyageBundle/image/logoblack.png')) )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('nouveaureservationpath');
        }
        return $this->render('appboxadminBundle:Inbox:envoyerEmail.html.twig',array('client' =>$client,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));

    }
}
