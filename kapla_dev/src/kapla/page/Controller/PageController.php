<?php

namespace kapla\page\Controller;

use kapla\page\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use kapla\page\Entity\Page;
use kapla\page\Form\PageType;
use kapla\page\Entity\Bloc;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Psr\Log\LoggerInterface;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $home = $em->getRepository('PageBundle:Category')->getMinNumOrder();
        if (!empty($home))
        {
            return $this->redirectToRoute('PageBundle_show', array(
                'slug' => $home[0]->getMainPage()->getSlug()
            ));
        }
        return $this->redirectToRoute('PageBundle_empty');
    }

    /**
     * @Route("/page/{slug}", name="PageBundle_show")
     */
    public function showAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('PageBundle:Bloc');
        $listBlocs = null;

        $blocs = $rep->findBy(
            array('published' => true, 'page' => $page->getId()),
            array('num_order' => 'ASC')
        );


        foreach($blocs as $bloc )
        {
            $type = $bloc->getContent()->getType();

            $typeBloc = $bloc->getTypeBloc();
           // var_dump($typeBloc);
            $blocDetails = $em->getRepository($type.'Bundle:'.$type)->findOneByContent($bloc->getContent());
             //var_dump($blocDetails);

            if ($blocDetails != null)
            {
                $listBlocs[] = array(
                    "id" => $bloc->getId(),
                    "contentId" => $blocDetails->getId(),
                    "type" => $type, 
                    "typeBloc" => $typeBloc);
            }
        }
        //var_dump($listBlocs);



        return $this->render('PageBundle:Default:show.html.twig', array(
            'page' => $page,
            'listBlocs' => $listBlocs
        ));
    }

    /**
     * @Route("/send_mail", name="PageBundle_sendmail")
     */
    public function SendMailConfirm($data)
    {
        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_host'),
            $this->container->getParameter('mailer_port'),  $this->container->getParameter('mailer_encryption'))
            ->setUsername($this->container->getParameter('mailer_user'))
            ->setPassword($this->container->getParameter('mailer_password'))
            ->setStreamOptions($https);

        $message = \Swift_Message::newInstance();
        $message->setContentType('text/html')
            ->setSubject(' ALC - Votre demande d\'information a bien été envoyée')
            ->setFrom($this->container->getParameter('mail_transmitter'))
            ->setTo($data['email']);
        $logo = $message->embed(\Swift_Image::fromPath('bundles/page/img/general/alc_logo.png'));
        $message->setBody($this->renderView("PageBundle:Mail:contact.html.twig", array(
                "data" => $data,
                "logo" => $logo
            )));

        $mailer = \Swift_Mailer::newInstance($transport);

        return $mailer->send($message);
    }

    public function SendMail($data)
    {
        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_host'),
            $this->container->getParameter('mailer_port'),  $this->container->getParameter('mailer_encryption'))
            ->setUsername($this->container->getParameter('mailer_user'))
            ->setPassword($this->container->getParameter('mailer_password'))
            ->setStreamOptions($https);

        $message = \Swift_Message::newInstance();
        $message->setContentType('text/html')
            ->setSubject($data['sujet'])
            ->setFrom($this->container->getParameter('mail_transmitter'))
            ->setTo($this->container->getParameter('alcmail'));
        $logo = $message->embed(\Swift_Image::fromPath('bundles/page/img/general/alc_logo.png'));
        $message->setBody($this->renderView("PageBundle:Mail:contact_alc.html.twig", array(
            "data" => $data,
            "logo" => $logo
        )));

        $mailer = \Swift_Mailer::newInstance($transport);

        return $mailer->send($message);
    }

    /**
     * @Route("/empty", name="PageBundle_empty")
     */
    public function emptyPageAction()
    {
        return $this->render('PageBundle:EmptyPage:emptyPage.html.twig');
    }

    /**
     * @Route("/contact", name="PageBundle_contact")
     */
    public function MailAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mail = $this->SendMailConfirm($data);
            $this->SendMail($data);

            if ($mail){
                $this->addFlash('success', 'Votre Email à été envoyé');
            } else {
                $this->addFlash('warning', 'Votre Email n\'a pas pu être envoyé');
            }

            // Detruit le form afin de le regenerer et de vider les inputs
            unset($form);
            $form = $this->createForm(ContactType::class);

            return $this->render('PageBundle:Default:contact.html.twig', [
                'slug' => 'contact',
                'form' => $form->createView()
            ]);
        }

        return $this->render('PageBundle:Default:contact.html.twig', [
            'slug' => 'contact',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mentionslegales", name="PageBundle_notice")
     */
    public function noticeAction()
    {
        return $this->render('PageBundle:Default:mentionslegales.html.twig');
    }

    /**
     * @Route("/pedagogy", name="pedagogy")
     */
    public function pedagogy()
    {
        return $this->render('PageBundle:Default:pedagogy.html.twig');
    }

}
