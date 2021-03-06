<?php

namespace PixellHub\NewsletterBundle\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use PixellHub\NewsletterBundle\Entity\NewsletterEntity;
use PixellHub\NewsletterBundle\Listener\VoxmailWrapper;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\HttpKernel;

use xmlrpcmsg;

class NewsletterListener
{
    protected $container;
    protected $config;
    protected $request;

    public function __construct(ContainerInterface $container, array $config)
    {
        $this->config = $config;
        $this->container = $container;
    }

    /*
     * Listen to the 'kernel.request' event to get the main request, this has several reasons:
     *  - The request can not be injected directly into a Twig extension, this causes a ScopeWideningInjectionException
     *  - Retrieving the request inside of the 'localize_route' method might yield us an internal request
     *  - Requesting the request from the container in the constructor breaks the CLI environment (e.g. cache warming)
     */
    public function onKernelRequest(GetResponseEvent $event) {
	if ($event->getRequestType() === HttpKernel::MASTER_REQUEST) {
		$this->request = $event->getRequest();
	}
    }
    
    public function onFlush(OnFlushEventArgs $args)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $args->getEntityManager();
        /* @var $uow \Doctrine\ORM\UnitOfWork */
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof NewsletterEntity) {
                $this->createUserService($entity);
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof NewsletterEntity) {
                $this->deleteUserService($entity);
            }
        }
    }
    
    private function createUserService($entity)
    {
        $wrapper= new VoxmailWrapper();
        
        $wrapper->voxmail_init(
                $this->config['host'],
                $this->config['api_key'],
                $this->config['secret']
            );
        
        $wrapper->voxmail_user_create(array(
                'mail' => $entity->getEmail(),
                'profile_name' => $entity->getName(),
                'profile_surname' => $entity->getSurname(),
                'profile_lingua' => $this->request->getLocale()
            ));
    }
    
    private function deleteUserService($entity)
    {
        $wrapper= new VoxmailWrapper();
        
        $wrapper->voxmail_init(
                $this->config['host'],
                $this->config['api_key'],
                $this->config['secret']
            );
        
        $wrapper->voxmail_user_erase($entity->getEmail());
    }
    
}
