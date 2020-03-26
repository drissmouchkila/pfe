<?php

namespace appbox\voyageBundle\Controller;

use appbox\adminBundle\Entity\contact;
use appbox\voyageBundle\Entity\newsletter;
use appbox\voyageBundle\Entity\review;
use appbox\voyageBundle\Entity\rechercheV;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class indexController extends Controller
{
    /**
     * @Route("/",name="indexpath")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->indexvoyage();
        $voyagepromo = $em->getRepository("appboxvoyageBundle:voyage")->indexpromovoyage();
        $blog = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->blogforindex();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $voyagedestination = $em->getRepository("appboxvoyageBundle:voyage")->indexdestination();
        //pour formulaire
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;

        if ($request->isMethod('POST')) {
            if ($request->request->get($form2->getName())) {
                $form2->handleRequest($request);
                if ($form2->isValid()) {
                    return $this->redirectToRoute('recherchepath',
                        array('nom' => $rechercheVo2->getNom(), 'destination' => $rechercheVo2->getDestination(), 'type' => $rechercheVo2->getType()));
                }
            }

        }
        return $this->render('appboxvoyageBundle:siteprincipale:index.html.twig',
            array(
                'footerblog'=>$blogfooter,
                'voyage' =>$voyage,
                'vpromo' =>$voyagepromo,
                'vdestination' =>$voyagedestination,
                'blogindex' =>$blog,
                'form2' =>$form2->createView(),
            ));
    }
    /**
     * @Route("/ajouternewsletter",name="ajouternewletter")
     */
    public function ajouternewletterAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $newsletter = new newsletter();
        $newsletter->setEmail($request->get('email'));
        $em->persist($newsletter);
        $em->flush();
        return $this->redirectToRoute('indexpath');

    }
    /**
     * @Route("/circuits/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="circuitspath")
     */
    public function circuitAction($page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->pagination($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $voyagedestination = $em->getRepository("appboxvoyageBundle:voyage")->indexdestination();
        $pagination = array(
            'page' => $page,
            'path'=> 'circuitspath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        //pour formulaire
        $rechercheVo = new rechercheV();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo)

            ->add('nom',     TextType::class, array('required' => false))
            ->add('save',      SubmitType::class)
            ->getForm()
        ;
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $form2->handleRequest($request);
            if ($form->isValid()||$form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo->getNom(),'destination'=>$request->get('destination'),'type'=>$request->get('type')));
            }
        }

        //fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:circuits.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,
                'form'=>$form->createView(),'vdestination' =>$voyagedestination, 'form2'=>$form2->createView() ));
    }
    /**
     * @Route("/promo/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="promopath")
     */
    public function promoAction($page,Request$request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->paginationpromo($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'promopath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }
        return $this->render('appboxvoyageBundle:siteprincipale:promo.html.twig',array('form2'=>$form2->createView(),'footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination));
    }
    /**
     * @Route("/contact",requirements={"page": "\d+"},name="contactpath")
     */
    public function contactAction(Request $request){
        $blogfooter =$this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }
        if($request->isMethod('POST')){
           $contact = new contact();
            $contact->setEmail($request->get('email'));
            $contact->setNom($request->get('nom'));
            $contact->setObjet($request->get('objet'));
            $contact->setText(($request->get('text')));
            $this->getDoctrine()->getManager()->persist($contact);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('indexpath');
        }
        return $this->render('appboxvoyageBundle:siteprincipale:contact.html.twig',array('footerblog'=>$blogfooter,'form2'=>$form2->createView()));
    }
    /**
     * @Route("/derniermunite/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="derniermunipath")
     */
    public function dermuniAction($page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->paginationlastmunite($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'derniermunipath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }
        return $this->render('appboxvoyageBundle:siteprincipale:derniermunite.html.twig',array('form2'=>$form2->createView(),'footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination' =>$pagination));
    }
    /**
     * @Route("/Weekend/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="weekendpath")
     */
    public function weekendAction($page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->paginationweekend($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'weekendpath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }
        return $this->render('appboxvoyageBundle:siteprincipale:weekend.html.twig',array('form2'=>$form2->createView(),'footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination' =>$pagination));
    }
    /**
     * @Route("/circuit/{id}",requirements={"id": "\d+"},name="circuitpath")
     */
    public function oneAction($id,Request $req){


        $voyage = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:voyage')->find($id);
        if($voyage == null){
            return $this->render('Exception/error.html.twig');
        }
        $route = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:route')->routeforonevoyage($id);
        $galerie = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:galerie')->galerieforonevoyage($id);
        $treevoyage = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        $review = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->reviewforvoyage($id);
        $option = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:opti')->findBy(array('voyage'=>$id));
        $image = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:image')->findBy(array('voyage'=>$id));
        $voyage->setNbrvue($voyage->getNbrvue()+1);
        $reservation = null;
        $commentaires =null;
        if($this->getUser() != null) {
            $reservation = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findOneBy(
                array('voyage' => $voyage->getId(), 'user' => $this->getUser()->getid()));
            $commentaires = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findOneBy(
                array('voyage' => $voyage->getId(), 'nom' => $this->getUser()->getusername(), 'valider' => false)
            );
        }
        $this->getDoctrine()->getManager()->persist($voyage);
        $this->getDoctrine()->getManager()->flush();
        $user = $this->getUser();
        //pour formulaire

        $rev = new review();
        $rev->setVoyage($id);

        $form = $this->get('form.factory')->createBuilder(FormType::class, $rev)
            ->add('review',   TextareaType::class)
            ->add('save',      SubmitType::class)
            ->getForm()
        ;
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if($req->isMethod('POST')){
            if($req->request->get($form->getName())){
                $rev->setNom($user->getusername());
                $rev->setEmail($user->getemail());
                $form->handleRequest($req);
                if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                $em->persist($voyage);
                $em->persist($rev);
                $em->flush();
                return $this->redirectToRoute('circuitpath',array('id' =>$id));
            }}
            if($req->request->get($form2->getName())){
                $form2->handleRequest($req);
                if ($form2->isValid()) {
                    return $this->redirectToRoute('recherchepath',
                        array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
                }
            }
        }


        //--fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:circuit.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'route' =>$route,'galerie' =>$galerie,'voyagewest' =>$treevoyage,'reviews'=>$review,
                'image'=>$image,'option'=>$option,'form'=>$form->createView(),'form2'=>$form2->createView(),'reser'=>$reservation,'comm'=>$commentaires ));
    }
    /**
     * @Route("/blog",name="blogpath")
     */
    public function blogAction(Request $request){
        $treevoyage = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blog = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:blog')->findAll();
        $blogfooter =$this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }

        return $this->render('appboxvoyageBundle:siteprincipale:blog.html.twig',array('form2'=>$form2->createView(),'footerblog'=>$blogfooter,'article' =>$blog,'voyagewest'=>$treevoyage));
    }
    /**
     * @Route("/type/{type}/{page}",requirements={"page": "\d+","type":"Air rides|Croisieres|Randonnee|Sport|En marchant|faune"},defaults={"page":"1"},name="types")
     */
    public function typecircuitsAction($type,$page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->typepagination($page,4,$type);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'type' => $type,
            'path'=> 'types',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }
        return $this->render('appboxvoyageBundle:siteprincipale:typecircuits.html.twig',array('form2'=>$form2->createView(),'footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,'type'=>$type ));
    }
    /**
     * @Route("/recherche/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="recherchepath")
     */
    public function rechercheAction($page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->recherchepagination($page,4,$request->query->get('nom'),$request->query->get('destination'),$request->query->get('type'));
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $voyagedestination = $em->getRepository("appboxvoyageBundle:voyage")->indexdestination();
        $pagination = array(
            'page' => $page,
            'path'=> 'circuitspath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        $post = array(
          'nom' => $request->query->get('nom'),
            'destination' => $request->query->get('destination'),
            'type' => $request->query->get('type'),
        );
        //pour formulaire
        $rechercheVo = new rechercheV();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo)

            ->add('nom',     TextType::class, array('required' => false))
            ->add('save',      SubmitType::class)
            ->getForm()
        ;
        $rechercheVo2 = new rechercheV();
        $form2 = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo2)
            ->add('nom',     TextType::class)
            ->add('save',    SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $form2->handleRequest($request);
            if ($form->isValid()||$form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo->getNom(),'destination'=>$request->get('destination'),'type'=>$request->get('type')));
            }
        }

        //fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:recherche.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'vdestination' =>$voyagedestination,'voyagewest' => $treevoyage,'pagination'=>$pagination,'form'=>$form->createView(),'form2'=>$form2->createView(),'post'=>$post ));
    }

}
