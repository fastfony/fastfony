<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Attribute\FeatureFlag as FeatureFlagAttribute;
use App\Handler\FeatureFlag as FeatureFlagHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsEventListener(event: ControllerEvent::class, method: 'onKernelController')]
final class FeatureFlag
{
    public function __construct(
        private readonly FeatureFlagHandler $featureFlag,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if ($controller instanceof AbstractController) {
            $reflection = new \ReflectionClass($controller);
            $attributes = $reflection->getAttributes(FeatureFlagAttribute::class);

            foreach ($attributes as $attribute) {
                /** @var FeatureFlagAttribute $instance */
                $instance = $attribute->newInstance();
                if (false === $this->featureFlag->isEnabled($instance->flag)) {
                    throw new NotFoundHttpException();
                }
            }
        }
    }
}
