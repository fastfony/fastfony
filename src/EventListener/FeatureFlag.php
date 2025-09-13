<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Attribute\FeatureFlag as FeatureFlagAttribute;
use App\Handler\FeatureFlag as FeatureFlagHandler;
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
        $attributes = [];

        if (is_array($controller) && 2 === count($controller)) {
            $reflectionClass = new \ReflectionClass($controller[0]);
            $attributes = array_merge(
                $reflectionClass->getAttributes(FeatureFlagAttribute::class),
                (new \ReflectionMethod($controller[0], $controller[1]))->getAttributes(FeatureFlagAttribute::class)
            );
        } elseif (is_object($controller)) {
            $reflectionClass = new \ReflectionClass($controller);
            $attributes = $reflectionClass->getAttributes(FeatureFlagAttribute::class);
            if (true === $reflectionClass->hasMethod('__invoke')) {
                $attributes = array_merge(
                    $attributes,
                    $reflectionClass->getMethod('__invoke')->getAttributes(FeatureFlagAttribute::class)
                );
            }
        }

        foreach ($attributes as $attribute) {
            /** @var FeatureFlagAttribute $instance */
            $instance = $attribute->newInstance();
            if (false === $this->featureFlag->isEnabled($instance->flag)) {
                throw new NotFoundHttpException();
            }
        }
    }
}
