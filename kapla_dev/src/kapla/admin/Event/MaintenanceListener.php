<?php

namespace kapla\admin\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;



class MaintenanceListener
{

	private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

	public function onKernelRequest(GetResponseEvent $event)
	{
		if (file_exists(__DIR__.'/../Resources/config/maintenance.lock') == false)
			file_put_contents(__DIR__.'/../Resources/config/maintenance.lock', "false");
		$maintenance = file_get_contents(__DIR__.'/../Resources/config/maintenance.lock');
		if ($maintenance == 'false')
			return;
		if($event->getRequest()->getRequestFormat() == 'css' || $event->getRequest()->getRequestFormat() == 'js')
        	return;
		if ((null !== $this->container->get('security.token_storage')->getToken() 
			&& $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN') == true) 
			|| $event->getRequest()->get('_route') == 'fos_user_security_login')
			return;
		$engine = $this->container->get('templating');
		$content = $engine->render('AdminBundle:Default:maintenance.html.twig');
           $event->setResponse(new Response($content, 503));
		$event->stopPropagation();
	}
}