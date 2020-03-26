<?php

namespace appbox\adminBundle\Controller;

use appbox\voyageBundle\Entity\reservation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class GestionReservationController extends Controller
{
    /**
     * @Route("/admin/nouveaureservation",name="nouveaureservationpath")
     */
    public function nouveaureservationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $reservation = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findAll();
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId'=>$this->getUser()->getid()));
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $voyagepersonnel = $em->getRepository('appboxvoyageBundle:voyage')->findAll();
        }
        else{
            $voyagepersonnel = $em->getRepository('appboxvoyageBundle:voyage')->findByemployee($employe->getId());

        }
        return $this->render('appboxadminBundle:GestionReservation:nouveaureservation.html.twig',array('v'=>$voyagepersonnel,'voyage'=>$voyage,'message'=>$newmessage,'newreser'=>$newreser,'reservation'=>$reservation,'newcomm'=>$newcomm));
    }
    /**
     * @Route("/admin/ajouterreservation",name="ajouterreservationpath")
     */
    public function ajouterreservationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $reservation = new reservation();
        $vo = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'En cours'));
        $form = $this->get('form.factory')->createBuilder(FormType::class, $reservation)
            ->add('nom',      TextType::class)
            ->add('prenom',   TextType::class)
            ->add('email', TextType::class)
            ->add('address',TextType::class)
            ->add('telephone',TextType::class)
            ->add('save',      SubmitType::class)
            ->getForm();
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            $reservation->setVoyage($request->get('voyage'));
            $reservation->setNbrpersonne(1);
            $reservation->setTypepaiement('Sur agence');
            $reservation->setUser(0);
            $reservation->setPaiement('Valider');
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($reservation->getVoyage());
            $reservation->setDatedebutvoyage($voyage->getDatedebut());
            $voyage->setNbrplacereser($voyage->getNbrplacereser()+1);
            $voyage->setTotalargentvoyage($voyage->getTotalargentvoyage()+$voyage->getPrix());
            $em->persist($voyage);
            $em->persist($reservation);
            $em->flush();
           return $this->redirectToRoute('imprimerfacturepath',array('voyage'=>$voyage->getId(),'reservation'=>$reservation->getId()) );
        }
        return $this->render('appboxadminBundle:GestionReservation:Ajouterreservation.html.twig',array(
            'vo'=>$vo,'message'=>$newmessage,'newreser'=>$newreser,'newcomm'=>$newcomm,'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/validerpaiement",name="validerpaiementpath")
     */
    public function ValiderpaiementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('appboxvoyageBundle:reservation')->find($request->get('id'));
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($reservation->getVoyage());
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->find($voyage->getEmployee());
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') || $employe->getUserId() == $this->getUser()->getid())
        {
            $reservation->setPaiement('Valider');
            $reservation->setBrochure(null);
            $voyage->setTotalargentvoyage($voyage->getTotalargentvoyage() + ($voyage->getPrix() * $reservation->getNbrpersonne()));
            $employe->setTotalargent($employe->getTotalargent() + ($voyage->getPrix() * $reservation->getNbrpersonne()));
            $em->persist($reservation);
            $em->persist($employe);
            $em->persist($voyage);
            $em->flush();

            $message = \Swift_Message::newInstance();

            $message->setSubject('Validation de paiement')
                ->setFrom('ayoublaw123@gmail.com')
                ->setTo($reservation->getEmail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'Emails/Validation.html.twig',
                        array('reser' => $reservation, 'voyage' => $voyage, 'image' => $message->embed(\Swift_Image::fromPath('http://localhost:81/pfe/web/bundles/appboxvoyageBundle/image/logoblack.png')))
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }
        return $this->redirectToRoute('nouveaureservationpath');

    }
    /**
     * @Route("/admin/nonvaliderpaiement",name="nonvaliderpaiementpath")
     */
    public function nonValiderpaiementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('appboxvoyageBundle:reservation')->find($request->get('id'));
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($reservation->getVoyage());
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->find($voyage->getEmployee());
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') || $employe->getUserId() == $this->getUser()->getid())
        {
        $reservation->setPaiement('Non');
            $reservation->setDatemettreres(new \DateTime());
        $reservation->setBrochure(null);
        $em->persist($reservation);
            $em->persist($voyage);
        $em->flush();
        }
        return $this->redirectToRoute('envoyeremailnonvaliderpath',array('id'=>$reservation->getId()));
    }
}
