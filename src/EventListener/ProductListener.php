<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class ProductListener
{
    private HubInterface $hub;

    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public function preUpdate(Product $product, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('sale')) {
            if ($args->getOldValue('sale') < $args->getNewValue('sale')) {
                $update = new Update(
                    "http://localhost:8000/product/" . $product->getId(),
                    json_encode([
                        'id' => $product->getId(),
                        'sale' => $product->getSale(),
                        'updated_at' => new \DateTime()
                    ])
                );
                $this->hub->publish($update);
            }
        }
    }
}
