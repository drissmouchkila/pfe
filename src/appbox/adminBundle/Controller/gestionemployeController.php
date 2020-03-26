<?php

namespace appbox\adminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use appbox\voyageBundle\Entity\Employe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class gestionemployeController extends Controller
{
    /**
     * @Route("/gc/tousemployee",name="tousemployeepath")
     */
    public function tousemployeeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $employee = $em->getRepository('appboxvoyageBundle:Employe')->findBy(array('etat'=>'En travail'));
        return $this->render('appboxadminBundle:GestionEmplyee:tousEmployee.html.twig',array('employee'=>$employee,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }

    /**
     * @Route("/gc/exmployee",name="exemployepath")
     */
    public function exemployeeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $employee = $em->getRepository('appboxvoyageBundle:Employe')->findBy(array('etat'=>'EX'));
        return $this->render('appboxadminBundle:GestionEmplyee:tousEmployee.html.twig',array('employee'=>$employee,'message'=>$newmessage,'newcomm'=>$newcomm,'newreser'=>$newreser));
    }
    /**
     * @Route("/gc/ajouteremployee",name="ajouteremployee")
     */
    public function ajouteremployeeAction(Request $request){
        $employee = new Employe();
        $newcomm = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $this->getDoctrine()->getManager()->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $this->getDoctrine()->getManager()->getRepository('appboxadminBundle:contact')->message();
        $user = $this->getDoctrine()->getManager()->getRepository('appboxUserBundle:User')->findAll();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $employee)
            ->add('name',      TextType::class)
            ->add('datederrecur',     DateType::class)
            ->add('phone',   TextType::class)
            ->add('image', FileType::class, array('label' => 'Brochure (PDF file)'))
            ->add('adress',   TextType::class)
            ->add('facebbookUrl',   TextType::class)
            ->add('twitterUrl',   TextType::class)
            ->add('googleUrl',   TextType::class)
            ->add('LinkdenUrl',   TextType::class)
            ->add('save',      SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

                // On enregistre notre objet $advert dans la base de données, par exemple
                $em = $this->getDoctrine()->getManager();
                $user=$em->getRepository('appboxUserBundle:User')->findOneByusername($request->get('username'));
                $user->setRoles(array('ROLE_ADMIN'));
                $employee->setUserId($user->getId());
                $employee->setEmail($user->getemail());
                $file = $employee->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('brochures_directory'),
                    $fileName
                );

                // Update the 'brochure' property to store the PDF file name
                // instead of its contents
                $employee->setImage($fileName);
                $employee->setNbrbloc(0);
                $employee->setNbrvoyage(0);
                $employee->setTotalargent(0);

                $em->persist($employee);
                $em->flush();
                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('tousemployeepath');

        }
        return $this->render('appboxadminBundle:GestionEmplyee:ajouteremployee.html.twig',array('newreser'=>$newreser,'message'=>$newmessage,'form'=>$form->CreateView(),'user'=>$user,'newcomm'=>$newcomm));
    }
    /**
     * @Route("/gc/employeeprofil/{id}",requirements={"id": "\d+"},name="employeepath")
     */
    public function employeeAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newcomm = $em->getRepository('appboxvoyageBundle:review')->findByvalider(false);
        $newreser = $em->getRepository('appboxvoyageBundle:reservation')->findBypaiement('En cours');
        $newmessage = $em->getRepository('appboxadminBundle:contact')->message();
        $employee = $em->getRepository('appboxvoyageBundle:Employe')->find($id);
        $user = $em->getRepository('appboxUserBundle:User')->find($employee->getUserId());
        $voyages = $em->getRepository('appboxvoyageBundle:voyage')->findByemployee($employee->getId());
        $form = $this->get('form.factory')->createBuilder(FormType::class, $employee)
            ->add('name',      TextType::class)
            ->add('datederrecur',     DateType::class)
            ->add('phone',   TextType::class)
            ->add('image', FileType::class, array('label' => 'Brochure (PDF file)','data_class' => null))
            ->add('adress',   TextType::class)
            ->add('save',      SubmitType::class)
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)

            // On enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $file = $employee->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $employee->setImage($fileName);
            $em->persist($employee);
            $em->flush();
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('employeepath',array('id'=>$id));

        }
        $nbperso = 0;
        $nbrreser = 0;
        $nbrvue = 0;
        foreach($voyages as $key => $voyage){
            $nbperso += $voyage->getNbrplacetotal();
             $nbrreser  += $voyage->getNbrplacereser();
            $nbrvue += $voyage->getNbrvue();
        }
        $nbrvide = $nbperso-$nbrreser;
        return $this->render('appboxadminBundle:GestionEmplyee:EmployeeProfil.html.twig',
            array(
                'employee'=>$employee,
                'user'=>$user,
                'voyages'=>$voyages,
                'nbrreser'=>$nbrreser,
                'nbrperso'=>$nbperso,
                'nbrvue'=>$nbrvue,
                'nbrvide'=>$nbrvide,
                'newcomm'=>$newcomm,
            'newreser'=>$newreser,'message'=>$newmessage,
                'form'=>$form->CreateView(),
            ));
    }
    /**
     * @Route("/gc/suppemployee/{id}",name="suppemployeepath")
     */
    public function suppemployeeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $employee = $em->getRepository('appboxvoyageBundle:Employe')->find($id);
        $employee->setEtat('EX');
        $user = $em->getRepository('appboxUserBundle:User')->find($employee->getUserId());
        $user->setRoles(array('ROLE_USER'));
        $em->persist($user);
        $em->persist($employee);
        $em->flush();
        return $this->redirectToRoute('tousemployeepath');
    }
}
