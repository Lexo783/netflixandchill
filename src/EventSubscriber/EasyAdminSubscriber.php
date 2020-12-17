<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Services\StripeApi;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $stripeApi;

    function __construct(StripeApi $stripeApi)
    {
        $this->stripeApi = $stripeApi;
    }

    /**
     * @return array|\string[][]
     * on appelle no fonction selon l'event actuelle
     */
    public static function getSubscribedEvents()
    {
        return[
            BeforeEntityPersistedEvent::class => ['setEntityBeforePersist'],
            BeforeEntityUpdatedEvent::class => ['updateEntity']
        ];
    }

    public function setEntityBeforePersist(BeforeEntityPersistedEvent $event)
    {
        if ($event->getEntityInstance() instanceof Product)
        {
            $entity = $event->getEntityInstance();
            $productStripe = $this->stripeApi->createProduct($entity->getName());
            $priceStripe = $this->stripeApi->createPrice($productStripe->id,$entity->getPrice());
            $entity->setProductStripe($productStripe->id);
            $entity->setPriceStripeId($priceStripe->id);
        }
    }


    public function updateEntity(BeforeEntityUpdatedEvent $event)
    {
        if ($event->getEntityInstance() instanceof Product)
        {
            $entity = $event->getEntityInstance();
        }
    }
}
