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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class indexController extends Controller
{
    /**
     * @Route("/index",name="indexpath")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository("appboxvoyageBundle:voyage")->indexvoyage();
        $voyagepromo = $em->getRepository("appboxvoyageBundle:voyage")->indexpromovoyage();
        $blog = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->blogforindex();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        return $this->render('appboxvoyageBundle:siteprincipale:index.html.twig',array('footerblog'=>$blogfooter,'voyage' =>$voyage,'vpromo' =>$voyagepromo,"blogindex" =>$blog));
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

            ->add('nom',     TextType::class)
            ->add('destination',     TextType::class, array('required' => false))
            ->add('type',     TextType::class)
            ->add('save',      SubmitType::class)
            ->getForm()
        ;
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
            return new Response($rechercheVo->getType());
            }
        }


        //fin formulaire
        return $this->render('appboxvoyageBundle:siteprincipale:circuits.html.twig',
            array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,'form'=>$form->createView() ));
    }
    /**
     * @Route("/promo/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="promopath")
     */
    public function promoAction($page){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->paginationpromo($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter =$em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'promopath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        return $this->render('appboxvoyageBundle:siteprincipale:promo.html.twig',array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination));
    }
    /**
     * @Route("/contact",requirements={"page": "\d+"},name="contactpath")
     */
    public function contactAction(){
        $blogfooter =$this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        return $this->render('appboxvoyageBundle:siteprincipale:contact.html.twig',array('footerblog'=>$blogfooter));
    }
    /**
     * @Route("/derniermunite/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="derniermunipath")
     */
    public function dermuniAction($page){
        $em = $this->getDoctrine()->getManager();
        $voyage = $em->getRepository('appboxvoyageBundle:voyage')->paginationlastmunite($page,4);
        $treevoyage = $em->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blogfooter = $em->getRepository("appboxvoyageBundle:blog")->footerblog();
        $pagination = array(
            'page' => $page,
            'path'=> 'derniermunipath',
            'nbrpages' => ceil(count($voyage) / 4),
        );
        return $this->render('appboxvoyageBundle:siteprincipale:derniermunite.html.twig',array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination' =>$pagination));
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

        if($req->isMethod('POST')){
            $form->handleRequest($req);
            if($form->isValid()){
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
    public function blogAction(){
        $treevoyage = $this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:voyage")->treevoyage();
        $blog = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:blog')->findAll();
        $blogfooter =$this->getDoctrine()->getManager()->getRepository("appboxvoyageBundle:blog")->footerblog();
        return $this->render('appboxvoyageBundle:siteprincipale:blog.html.twig',array('footerblog'=>$blogfooter,'article' =>$blog,'voyagewest'=>$treevoyage));
    }
    /**
     * @Route("/{type}/{page}",requirements={"page": "\d+"},defaults={"page":"1"},name="types")
     */
    public function typecircuitsAction($type,$page){
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
        return $this->render('appboxvoyageBundle:siteprincipale:typecircuits.html.twig',array('footerblog'=>$blogfooter,'voyage' => $voyage,'voyagewest' => $treevoyage,'pagination'=>$pagination,'type'=>$type ));
    }
}
