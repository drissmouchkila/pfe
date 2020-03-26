<?php

namespace appbox\adminBundle\Controller;

use appbox\voyageBundle\Entity\image;
use appbox\voyageBundle\Entity\opti;
use appbox\voyageBundle\Entity\voyage;
use appbox\voyageBundle\Entity\galerie;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class gestionvoyageController extends Controller
{
    /**
     * @Route("/admin/tousvoyage",name="tousvoyagepath")
     */
    public function tousvoyagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $tousvoyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'en cours'));
        }
        else{
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $tousvoyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('employee'=>$employe->getId(),'etat'=>'en cours'));
        }
        return $this->render('appboxadminBundle:Gestionvoyages:tousvoyages.html.twig',array('voyage'=>$tousvoyage,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }

    /**
     * @Route("/admin/archivevoyage",name="archivevoyagepath")
     */
    public function archivevoyagesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $tousvoyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'termine'));
        }
        else{
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $tousvoyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'termine','employee'=>$employe->getId()));
        }
        return $this->render('appboxadminBundle:Gestionvoyages:tousvoyages.html.twig',array('voyage'=>$tousvoyage,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }
    /**
     * @Route("/admin/suppvoyage",name="suppvoyagepath")
     */
    public function suppvoyage(Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($request->query->get('voyage'));
        $route = $em->getRepository('appboxvoyageBundle:route')->routeforonevoyage($voyage->getId());
        $galerie = $em->getRepository('appboxvoyageBundle:galerie')->galerieforonevoyage($voyage->getId());
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId'=>$this->getUser()->getid()));
        if($voyage->getEmployee() == $employe->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
        if($voyage->getNbrplacereser() == 0) {
                foreach ($galerie as $galeri) {
                    $em->remove($galeri);
                }
            foreach ($route as $rout) {
                $em->remove($rout);
            }
            $employe->setNbrvoyage($employe->getNbrvoyage()-1);
            $em->persist($employe);
            $em->remove($voyage);
            $em->flush();
        }
        }
        return $this->redirectToRoute('tousvoyagepath');
    }

    /**
     * @Route("/admin/seulvoyage/{id}",name="seulvoyagepath")
     */
    public function seulvoyagesAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($id);
        $reservation = $em->getRepository('appboxvoyageBundle:reservation')->findByvoyage($voyage->getId());
        $route = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:route')->routeforonevoyage($voyage->getId());
        $galerie =  $em->getRepository('appboxvoyageBundle:galerie')->findByvoyage($voyage->getId());
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId'=>$this->getUser()->getid()));
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $employe->getId()!= $voyage->getEmployee())
        {
            $this->redirectToRoute('tousvoyagepath');
        }
        if($request->isMethod('POST')){
            $message = \Swift_Message::newInstance();

            $message->setSubject($request->get('objet'))
                ->setFrom('ayoublaw123@gmail.com');
            foreach($reservation as $liste) {
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

            return $this->redirectToRoute('seulvoyagepath',array('id'=>$voyage->getId()));
        }
        return $this->render('appboxadminBundle:Gestionvoyages:seulvoyages.html.twig',array(
            'route'=>$route,
            'voyage'=>$voyage,
            'reservation'=>$reservation,
            'galerie'=>$galerie,
            'newcomm'=>$newcomm,
            'newreser'=>$newreser,'message'=>$newmessage));
    }
    /**
     * @Route("/admin/mettrevoyageenpromo",name="mettrevoyageenpromovoyagepath")
     */
    public function mettrevoyageenpromoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findByetat('en cours');
        }
        else{
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'en cours','employee'=>$employe->getId()));
        }
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        if ($request->isMethod('POST')) {
              $voya = $em->getRepository('appboxvoyageBundle:voyage')->find($request->get('id'));
              $voya->setPromo(true);
              $voya->setNewprix($voya->getprix());
              $voya->setPrix($request->get('prix'));
              $voya->setTypepromo($request->get('type'));
              $em->persist($voya);
              $em->flush();
              $this->redirectToRoute('tousvoyagepath');
        }

            return $this->render('appboxadminBundle:Gestionvoyages:mettrevoyageenpromo.html.twig',
           array(
               'newcomm'=>$newcomm,
               'voyage'=>$voyage,
               'newreser'=>$newreser,
               'message'=>$newmessage
           ));
    }
    /**
     * @Route("/admin/AjouterGalerie",name="ajoutergaleriepath")
     */
    public function ajoutergalerieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'termine'));
        }
        else{
            $employee = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId'=>$this->getUser()->getid()));
            $voyage = $em->getRepository('appboxvoyageBundle:voyage')->findBy(array('etat'=>'termine','employee'=>$employee->getId()));
        }
            return $this->render('appboxadminBundle:Gestionvoyages:ajouterGalerie.html.twig',array(
                'voyage'=>$voyage,'newcomm'=>$newcomm,'newreser'=>$newreser,'message'=>$newmessage));
    }
    /**
     * @route("/admin/dropzone",name="dropezone")
     */
    public function dropezoneAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $image = new image();
        $file = $request->files->get('file');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move(
            $this->getParameter('brochures_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $image->setUrl($fileName);
        $image->setVoyage($request->get('circuit'));
        $em->persist($image);
        $em->flush();
        return new JsonResponse(array('success' => true));
    }
    /**
     * @route("/admin/dropzone2",name="dropezone2")
     */
    public function dropezonevAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $image = new galerie();
        $file = $request->files->get('file');
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Move the file to the directory where brochures are stored
        $file->move(
            $this->getParameter('brochures_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $image->setVoyage(0);
        $image->setUrl($fileName);
        $em->persist($image);
        $em->flush();
        return new JsonResponse(array('success' => true));
    }

    /**
     * @route("/admin/Ajouterweekend",name="ajouterweekendpath")
     */
    public function dropezoneAvtion(Request $request){
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $voyage = new voyage();
        if ($request->isMethod('POST')){
            $voyage = new voyage();
            $voyage->setTitre($request->get('nomcircuit'));
            $voyage->setDescription($request->get('description'));
            $voyage->setDestination($request->get('destination'));
            $voyage->setNbrplacetotal($request->get('nbrplace'));
            $voyage->setPrix($request->get('prix'));
            $voyage->setNbrRoute(2);
            $voyage->setLieu($request->get('lieu'));
            $voyage->setMinage($request->get('minage'));
            $voyage->setDatedebut(new \DateTime($request->get('datedebut')));
            $voyage->setDatefin(new \DateTime($request->get('datefin')));
            $voyage->setType($request->get('categorie'));


            $voyage->setImage('null');
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $voyage->setEmployee($employe->getId());
            $employe->setNbrvoyage($employe->getNbrvoyage()+1);
            $voyage->setTyped('Week-end');
            $em->persist($employe);
            $em->persist($voyage);
            $em->flush();
            for ($i=1;$i<=6;$i++) {
                if ($request->get('c'.$i)) {
                    $option = new opti();
                    $option->setVoyage($voyage->getId());
                    $option->setEtat('inclut');
                    $option->setNom($request->get('c'.$i));
                    $em->persist($option);
                }
            }

            $em->flush();
            $image = $em->getRepository('appboxvoyageBundle:galerie')->findByvoyage(0);
            foreach($image as $i){
                $i->setVoyage($voyage->getId());
                $voyage->setImage($i->getUrl());
                $em->persist($i);
                $em->persist($voyage);
                $em->flush();
            }

            $route =new \appbox\voyageBundle\Entity\route();
            $route->setNumjour(1);
            $route->setVoyage($voyage->getId());
            $route->setTitre('Samedi');
            $route->setDescription($request->get('samedi'));
            $em->persist($route);
            $em->flush();
            $route =new \appbox\voyageBundle\Entity\route();
            $route->setNumjour(2);
            $route->setVoyage($voyage->getId());
            $route->setTitre('Dimanche');
            $route->setDescription($request->get('dimanche'));
            $em->persist($route);
            $em->flush();
            return $this->redirectToRoute('tousvoyagepath');
        }
        return $this->render('appboxadminBundle:Gestionvoyages:Ajouterweekend.html.twig',array(
            'newcomm'=>$newcomm,
            'newreser'=>$newreser,
            'message'=>$newmessage,
        ));

    }
    /**
     * @Route("/admin/ajoutervoyage",name="ajoutervoyagepath")
     */
    public function Ajoutervoyage(Request $request){
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        if ($request->isMethod('POST')){
            $voyage = new voyage();
            $voyage->setTitre($request->get('nomcircuit'));
            $voyage->setDescription($request->get('description'));
            $voyage->setDestination($request->get('destination'));
            $voyage->setNbrplacetotal($request->get('nbrplace'));
            $voyage->setPrix($request->get('prix'));
            $voyage->setNbrRoute($request->get('nbrdejour'));
            $voyage->setDatedebut(new \DateTime($request->get('datedebut')));
            $voyage->setDatefin(new \DateTime($request->get('datefin')));
            $voyage->setLieu($request->get('lieu'));
            $voyage->setMinage($request->get('minage'));
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $employe->setNbrvoyage($employe->getNbrvoyage()+1);
            $em->persist($employe);
            $voyage->setEmployee($employe->getId());
            $voyage->setType($request->get('categorie'));
            $voyage->setImage('null');
            $em->persist($voyage);
            $em->flush();
            for ($i=1;$i<=6;$i++) {
                if ($request->get('c'.$i)) {
                    $option = new opti();
                    $option->setVoyage($voyage->getId());
                    $option->setEtat('inclut');
                    $option->setNom($request->get('c'.$i));
                    $em->persist($option);
                }
            }

            $em->flush();
            //persist Route
            $r = 1;
            while($r < $voyage->getNbrRoute()) {
                $route =new \appbox\voyageBundle\Entity\route();
                $route->setNumjour($r);
                $route->setVoyage($voyage->getId());
                $route->setDescription($request->get('route'.$r));
                $em->persist($route);
                $em->flush();
                $r=$r+1;
            }
            $route = new \appbox\voyageBundle\Entity\route();
            $route->setNumjour($voyage->getNbrRoute());
            $route->setVoyage($voyage->getId());
            $route->setTitre('Retourne');
            $route->setDescription('');
            $em->persist($route);
            $em->flush();

            //persist Image
            $image = $em->getRepository('appboxvoyageBundle:galerie')->findByvoyage(0);
            foreach($image as $i){
                $i->setVoyage($voyage->getId());
                $voyage->setImage($i->getUrl());
                $em->persist($i);
                $em->persist($voyage);
                $em->flush();
            }

            return $this->redirectToRoute('tousvoyagepath');
        }
        return $this->render('appboxadminBundle:Gestionvoyages:ajoutervoyage.html.twig',array('newcomm'=>$newcomm,'message'=>$newmessage,'newreser'=>$newreser));
    }
    /**
     * @Route("/admin/modcircuit",name="modcircuitpath")
     */
    public function modifcircuit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->find($request->get('id'));
        $galerie = $em->getRepository('appboxvoyageBundle:galerie')->findBy(array('voyage'=>$voyage->getId()));
        $route = $em->getRepository('appboxvoyageBundle:route')->findBy(array('voyage'=>$voyage->getId()),array('numjour'=>'ASC'));
        if($request->isMethod('POST'))
        {
            $voyage->setTitre($request->get('nomcircuit'));
            $voyage->setDescription($request->get('description'));
            $voyage->setNbrplacetotal($request->get('nbrplace'));
            $voyage->setDatedebut(new \DateTime($request->get('datedebut')));
            $voyage->setDatefin(new \DateTime($request->get('datefin')));
            $voyage->setLieu($request->get('lieu'));
            $voyage->setMinage($request->get('minage'));
            $voyage->setType($request->get('categorie'));
            $em->persist($voyage);
            if($request->get('c2')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Billet d\'avion'));
                $option->setEtat('inclut');
                $em->persist($option);
            }
            if($request->get('c3')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Transport local'));

                $option->setEtat('inclut');
                $em->persist($option);
            }
            if($request->get('c4')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Hebergement'));
                $option->setEtat('inclut');
                $em->persist($option);
            }
            if($request->get('c5')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Frais d\'entree'));

                $option->setEtat('inclut');
                $em->persist($option);
            }
            if($request->get('c6')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'guide gratuit'));

                $option->setEtat('inclut');
                $em->persist($option);
            }
            if($request->get('cc2')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Billet d\'avion'));
                $option->setEtat('noninclut');
                $em->persist($option);
            }
            if($request->get('cc3')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Transport local'));

                $option->setEtat('noninclut');
                $em->persist($option);
            }
            if($request->get('cc4')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Hebergement'));
                $option->setEtat('noninclut');
                $em->persist($option);
            }
            if($request->get('cc5')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'Frais d\'entree'));

                $option->setEtat('noninclut');
                $em->persist($option);
            }
            if($request->get('cc6')){
                $option = $em->getRepository('appboxvoyageBundle:opti')->findOneBy(array('voyage'=>$voyage->getId(),'nom'=>'guide gratuit'));

                $option->setEtat('noninclut');
                $em->persist($option);
            }
            foreach($galerie as $g){
                $file = $request->files->get('recu'.$g->getId());
                if($file != null) {
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('brochures_directory'),
                        $fileName
                    );
                    $g->setUrl($fileName);
                    $g->setVoyage($voyage->getId());
                    $em->persist($g);
                }
            }
            foreach($route as $r){
                    $r->setDescription($request->get('route'.$r->getId()));
                    $em->persist($r);

            }

            $em->flush();
            return $this->redirectToRoute('tousvoyagepath');
        }

        return $this->render('appboxadminBundle:Gestionvoyages:mod.html.twig',array(
            'message'=>$newmessage,
            'newcomm'=>$newcomm,
            'newreser'=>$newreser,
            'voyage'=>$voyage,
            'route'=>$route,
            'galerie'=>$galerie
        ));
    }
}