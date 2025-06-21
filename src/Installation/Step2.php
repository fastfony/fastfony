<?php

declare(strict_types=1);

namespace App\Installation;

use App\Handler\FeatureFlag;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Step2
{
    public function __construct(
        private readonly FeatureFlag $featureFlag,
    ) {
    }

    /** @phpstan-ignore missingType.generics */
    public function do(
        FormInterface $form,
        Request $request,
    ): bool {
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->featureFlag->save($form->getData()['features']);

                return true;
            }
        }

        return false;
    }
}
