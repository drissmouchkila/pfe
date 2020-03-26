<?php

namespace appbox\adminBundle\Controller;

use appbox\voyageBundle\Entity\blog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GestionBlogController extends Controller
{
    /**
     * @Route("/admin/tousblog",name="tousblogpath")
     */
    public function tousBlogAction()
    {
        $em = $this->getDoctrine()->getManager();
        if($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') ) {
        $blog = $em->getRepository('appboxvoyageBundle:blog')->findall();}
        else{
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
            $blog = $em->getRepository('appboxvoyageBundle:blog')->findBy(array('employe' => $employe->getId()), null, null, null);
        }
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        return $this->render('appboxadminBundle:GestionBlog:Blog.html.twig',array('blog'=>$blog,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }
    /**
     * @Route("/admin/ajouterblog",name="ajouterblogpath")
     */
    public function ajouterBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
        $blog = new blog();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $blog)
            ->add('titre',      TextType::class)
            ->add('description',   TextType::class)
            ->add('url', FileType::class, array('label' => 'Brochure (PDF file)'))
            ->add('save',      SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            // On enregistre notre objet $advert dans la base de données, par exemple
            $file = $blog->getUrl();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $blog->setUrl($fileName);
                $employe->setNbrbloc($employe->getNbrbloc() + 1);
                $blog->setEmploye($employe->getId());
                $em->persist($employe);
            $em->persist($blog);
            $em->flush();
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('tousblogpath');
        }
        return $this->render('appboxadminBundle:GestionBlog:ajouterBlog.html.twig',array('newreser'=>$newreser,'message'=>$newmessage,'newcomm'=>$newcomm,'form'=>$form->CreateView()));
    }
    /**
     * @Route("/admin/Suppblog",name="suppblogpath")
     */
    public function SuppBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('appboxvoyageBundle:blog')->find($request->get('id'));
            $employe = $em->getRepository('appboxvoyageBundle:Employe')->find($blog->getEmploye());
        if( $employe->getUserId() == $this->getUser()->getid() || $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')){
            $employe->setNbrbloc($employe->getNbrbloc() - 1);
            $em->persist($employe);
        $em->remove($blog);
        $em->flush();}
        return $this->redirectToRoute('tousblogpath');
    }
    /**
     * @Route("/admin/Modifierblog",name="modifierblogpath")
     */
    public function modifierBlogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $blog = $em->getRepository('appboxvoyageBundle:blog')->find($request->get('id'));
        $employe = $em->getRepository('appboxvoyageBundle:Employe')->findOneBy(array('UserId' => $this->getUser()->getid()), null, null, null);
        $form = $this->get('form.factory')->createBuilder(FormType::class, $blog)
            ->add('titre',      TextType::class)
            ->add('description',   TextareaType::class)
            ->add('url', FileType::class, array('label' => 'Brochure (PDF file)','data_class' => null))
            ->add('save',      SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            // On enregistre notre objet $advert dans la base de données, par exemple
            $file = $blog->getUrl();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $blog->setUrl($fileName);

            $em->persist($blog);
            $em->flush();
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('tousblogpath');

        }
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN') && $employe->getId()!= $blog->getEmploye())
        {
            $this->redirectToRoute('tousvoyagepath');
        }
        return $this->render('appboxadminBundle:GestionBlog:ModifierBlog.html.twig',array('galerie'=>$blog,'newcomm'=>$newcomm,'message'=>$newmessage,'form'=>$form->CreateView(),'newreser'=>$newreser));
    }
}
