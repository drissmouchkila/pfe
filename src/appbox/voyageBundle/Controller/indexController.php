<?php

namespace appbox\voyageBundle\Controller;

use appbox\voyageBundle\Entity\review;
use appbox\voyageBundle\Entity\rechercheV;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class indexController extends Controller
{
    /**
     * @Route("/index",name="indexpath")
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
            $form2->handleRequest($request);
            if ($form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo2->getNom(),'destination'=>$rechercheVo2->getDestination(),'type'=>$rechercheVo2->getType()));
            }
        }

        return $this->render('appboxvoyageBundle:siteprincipale:index.html.twig',
            array('footerblog'=>$blogfooter,'voyage' =>$voyage,'vpromo' =>$voyagepromo,'vdestination' =>$voyagedestination,"blogindex" =>$blog,'form2' =>$form2->createView()));
    }
    /**
     * @Route("/circuits/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="circuitspath")
     */
    public function circuitAction($page,Request $request){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->pagination($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'circuitspath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        //pour formulaire
        $rechercheVo = new rechercheV();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $rechercheVo)

            ->add('nom',     TextType::class, array('required' => false))
            ->add('destination',     TextType::class, array('required' => false))
            ->add('type',     TextType::class, array('required' => false))
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
            if ($form->isValid() || $form2->isValid()) {
                return $this->redirectToRoute('recherchepath',
                    array('nom' => $rechercheVo->getNom(), 'destination' => $rechercheVo->getDestination(), 'type' => $rechercheVo->getType()));
            }
        }

        //fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:circuits.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,'form'=>$form->createView(),'form2'=>$form2->createView() ));
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
     * @Route("/circuit/{id}",requirements={"id": "\d+"},name="circuitpath")
     */
    public function oneAction($id,Request $req){


        $voyage = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:voyage')->find($id);
        $route = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:route')->routeforonevoyage($id);
        $galerie = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:galerie')->galerieforonevoyage($id);
        $treevoyage = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        $review = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->reviewforvoyage($id);
        //pour formulaire

        $rev = new review();
        $rev->setVoyage($id);

        $form = $this->get('form.factory')->createBuilder(FormType::class, $rev)
            ->add('review',   TextareaType::class)
            ->add('nom',     TextType::class)
            ->add('email',    EmailType::class)
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
            $form->handleRequest($req);
            $form2->handleRequest($request);
            if($form->isValid()||$form2->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($rev);
                $em->flush();
                return $this->redirectToRoute('circuitpath',array('id' =>$id));
            }
        }


        //--fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:circuit.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'route' =>$route,'galerie' =>$galerie,'voyagewest' =>$treevoyage,'reviews'=>$review,'form'=>$form->createView() ));
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
     * @Route("/type/{type}/{page}",requirements={"page": "\d+","type":"Air Rides|Hiking|Cruises|Sport|Walking|Wildlife"},defaults={"page":"1"},name="types")
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
            ->add('destination',     TextType::class, array('required' => false))
            ->add('type',     TextType::class, array('required' => false))
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
                    array('nom' => $rechercheVo->getNom(),'destination'=>$rechercheVo->getDestination(),'type'=>$rechercheVo->getType()));
            }
        }

        //fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:recherche.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,'form'=>$form->createView(),'form2'=>$form2->createView(),'post'=>$post ));
    }
    /**
     * @Route("/test",requirements={"page": "\d+"},name="testpath")
     */
    public function testAction(){
        $blogfooter =$this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        return $this->render('appboxvoyageBundle:siteprincipale:test.html.twig',array('footerblog'=>$blogfooter));
    }
}
