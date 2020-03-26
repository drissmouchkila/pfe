<?php

namespace appbox\voyageBundle\Controller;

use appbox\voyageBundle\Entity\reservation;
use appbox\voyageBundle\Entity\review;
use appbox\voyageBundle\Entity\rechercheV;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Payum\Core\Request\GetHumanStatus;

class reservationController extends Controller
{
    /**
     * @Route("/paiement",name="paiementpath")
     */
    public function PaiementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($request->query->get('circuit'));
        $reser= $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findOneBy(
            array('voyage'=>$voyage->getId(),'user'=>$this->getUser()->getid()));
        $qte = $request->get('quantity');
        $session = $request->getSession();

        $reservation = new reservation();
        $reservation->setVoyage($request->query->get('circuit'));
        $reservation->setUser($this->getUser()->getid());
        $form = $this->get('form.factory')->createBuilder(FormType::class,$reservation)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('address', TextType::class)
            ->add('telephone', NumberType::class)
            ->add('save',    SubmitType::class)
            ->getForm()

        ;
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;

        if ($request->isMethod('POST')) {
            //paiement par cheque
           if($request->request->get($form->getName())){
               $reservation->setNbrpersonne($session->get('qte'));
               $reservation->setTypepaiement('Par cheque');
               $reservation->setDatedebutvoyage($voyage->getDatedebut());
               $this->getUser()->setNbrreser($this->getUser()->getNbrreser()+1);
               $voyage->increservation($reservation->getNbrpersonne());
               $this->getDoctrine()->getManager()->persist($voyage);
               $this->getDoctrine()->getManager()->persist($this->getUser());
              $form->handleRequest($request);
               $em->persist($reservation);
              $em->flush();
              if($request->get('payment_method') == 'cheque') {
                  return $this->redirectToRoute('fos_user_profile_show');
              }
            //paiement en ligne
              if($request->get('payment_method') == 'paypal') {

                $gatewayName = 'paiement_paypal';
                $storage = $this->get('payum')->getStorage('appbox\voyageBundle\Entity\Payment');
                $payment = $storage->create();

                $payment->setReservation($reservation->getId());
                $payment->setCurrencyCode('USD');
                $payment->setTotalAmount(($reservation->getNbrpersonne()* $voyage->getprix()).'00');
                $payment->setClientEmail($this->getUser()->getemail());
                $payment->setClientId($this->getUser()->getid());
                $payment->setNumber(uniqid());
                $payment->setDescription('c\'est un test');
                $storage->update($payment);
                $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
                    $gatewayName,
                    $payment,
                    'paymentDone'
                );
                return $this->redirect($captureToken->getTargetUrl());
            }
           }
        }

        return $this->render('appboxvoyageBundle:siteprincipale:paiement.html.twig',
            array(
            'footerblog'=>$blogfooter,
            'voyagewest' => $treevoyage,
            'voyage'=> $voyage,
            'qte'=>$qte,
            'reser'=>$reser,
            'form'=>$form->createView(),
            'form2'=>$form2->createView(),
            ));
    }
    /**
     * @Route("/paiement/payment/reser", name="paymentreser")
     */
    public function paiementreserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('appboxvoyageBundle:reservation')->find($request->get('reservation'));
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($reservation->getVoyage());
        $gatewayName = 'paiement_paypal';
        $storage = $this->get('payum')->getStorage('appbox\voyageBundle\Entity\Payment');
        $payment = $storage->create();

        $payment->setReservation($reservation->getId());
        $payment->setCurrencyCode('USD');
        $payment->setTotalAmount(($reservation->getNbrpersonne()* $voyage->getprix()).'00');
        $payment->setClientEmail($this->getUser()->getemail());
        $payment->setClientId($this->getUser()->getid());
        $payment->setNumber(uniqid());
        $payment->setDescription('paiement pour réservation de circuit '.$voyage->getTitre());
        $storage->update($payment);
        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'paymentDone'
        );
        return $this->redirect($captureToken->getTargetUrl());

    }

    /**
     * @Route("/paiement/payment/done", name="paymentDone")
     */
    public function doneAction(Request $request)     {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $this->get('payum')->getHttpRequestVerifier()->invalidate($token);
        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();
        $status = strtolower($status->getValue());
        if ($status=='captured') {
            $reservation = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->find($payment->getReservation());
            $reservation->setPaiement('En cours');
            $reservation->setTypepaiement('Par PayPal');
            $this->getDoctrine()->getManager()->persist($reservation);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('fos_user_profile_show');
        }
        else {
// Le paiement a foiré, on affiche une erreur
//$this->addFlash('error', $this->trans('PaymentFailed', array(), 'activation'));
//return $this->redirectToRoute('paymentPrepare', array('_locale'=>$request->getLocale()));
            echo \nl2br(\json_encode(array(
                'status' => $status,
                'payment' => array(
                    'total_amount' => $payment->getTotalAmount(),
                    'currency_code' => $payment->getCurrencyCode(),
                    'details' => $payment->getDetails(),
                ),
            )));
            exit();
        }
    }





    /**
     * @Route("/paiement/facture",name="facturepath")
     */
    public function factureAction(Request $request)
    {
        $request->getSession()->remove('qte');
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($session->get('voyage'));
        $reservation = $em->getRepository("appboxvoyageBundle:reservation")->find($request->getSession()->get('reservation'));
        $session->remove('reservation');
        $session->remove('voyage');
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            return $this->redirectToRoute('recherchepath',
                array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));

        }
        return $this->render('appboxvoyageBundle:siteprincipale:facture.html.twig',
            array(
                'footerblog'=>$blogfooter,
                'voyagewest' => $treevoyage,
                'voyage' => $voyage,
                'reservation' => $reservation,
                'form2'=>$form2->createView()
            ));
    }
    /**
     * @Route("/paiement/facture/imprimer",name="imprimerfacturepath")
     */
    public function imprimerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($request->query->get('voyage'));
        $reservation = $em->getRepository("appboxvoyageBundle:reservation")->find($request->query->get('reservation'));

        $url =   $this->renderView('appboxvoyageBundle:siteprincipale:factureimprime.html.twig',
            array(
                'voyage' => $voyage,
                'reservation' => $reservation,
            ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($url),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="file.pdf"'
            )
        );

    }
    /**
     * @Route("/paiement/suppreser",name="suppreservation")
     */
    public function supprimerAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository("appboxvoyageBundle:reservation")->find($request->query->get('reservation'));
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($reservation->getVoyage());
        $voyage->decreservation($reservation->getNbrpersonne());
        if($this->getUser()->getid() == $reservation->getUser() && !$reservation->getPaiement()) {
            $em->remove($reservation);
            $em->flush();
        }
        return $this->redirectToRoute('fos_user_profile_show');
    }
    /**
 * @Route("paiement/supcomentaire",name="supcommentaire")
 */
    public function supprimercomentaireAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $review = $em->getRepository("appboxvoyageBundle:review")->find($request->query->get('review'));
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->find($review->getVoyage());
        if($review->getNom() == $this->getUser()->getusername()){
            $voyage->setNbrcommentaires($voyage->getNbrcommentaires()-1);
            $user = $this->getUser();
            $user->setNbrcommtotal($user->getNbrcommtotal()-1);
            $em->persist($voyage);
            $em->persist($user);
            $em->remove($review);
        $em->flush();}
        return $this->redirectToRoute('circuitpath',array('id' =>$id));
    }
    /**
     * @Route("paiement/ajouterrecu",name="ajouter_recu")
     */
    public function ajouterrecuAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $file = $request->files->get('recu');
        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move(
            $this->getParameter('recu_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $reser = $em->getRepository('appboxvoyageBundle:reservation')->find($request->get('id'));
        $reser->setPaiement('En cours');
        $reser->setBrochure($fileName);
        $em->persist($reser);
        $em->flush();
        return $this->redirectToRoute('fos_user_profile_show');
    }
}