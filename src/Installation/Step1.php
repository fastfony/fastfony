<?php

declare(strict_types=1);

namespace App\Installation;

use App\Repository\Parameter\ParameterRepository;

class Step1
{
    public function __construct(
        private readonly ParameterRepository $parameterRepository,
    ) {
    }

    public function do(): bool
    {
        return $this->checkIfParamExist();
    }

    public function checkIfParamExist(): bool
    {
        if (!$this->parameterRepository->findOneBy(['key' => 'MAILER_SENDER'])) {
            return false;
        }

        return true;
    }
}
