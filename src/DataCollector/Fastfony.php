<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Kernel;
use Fastfony\LicenceBundle\Security\LicenceChecker;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Fastfony extends AbstractDataCollector
{
    public function __construct(
        private readonly ?string $licenceKey,
        #[Autowire(service: 'fastfony_licence.security.licence_checker')]
        private readonly LicenceChecker $licenceChecker,
    ) {
    }

    public function collect(
        Request $request,
        Response $response,
        ?\Throwable $exception = null,
    ): void {
        $this->data = [
            'fastfonyVersion' => Kernel::FF_VERSION,
            'fastfonyState' => $this->determineFastfonyState(),
            'licenceKey' => $this->licenceKey,
            'licenceKeyValidity' => $this->checkLicenceKeyValidity($this->licenceKey),
        ];
    }

    public static function getTemplate(): ?string
    {
        return 'data_collector/fastfony.html.twig';
    }

    public function getFastfonyState(): string
    {
        return $this->data['fastfonyState'];
    }

    public function getFastfonyVersion(): string
    {
        return $this->data['fastfonyVersion'];
    }

    public function getLicenceKey(): ?string
    {
        return $this->data['licenceKey'];
    }

    public function getLicenceKeyValidity(): bool
    {
        return !empty($this->data['licenceKey']) && $this->data['licenceKeyValidity'];
    }

    public function getName(): string
    {
        return 'fastfony';
    }

    private function checkLicenceKeyValidity(?string $licenceKey): bool
    {
        if (empty($licenceKey)) {
            return false;
        }

        return $this->licenceChecker->isValid($licenceKey);
    }

    private function determineFastfonyState(): string
    {
        $now = new \DateTimeImmutable();
        $eom = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.Kernel::FF_END_OF_MAINTENANCE)
            ->modify('last day of this month');
        $eol = \DateTimeImmutable::createFromFormat('d/m/Y', '01/'.Kernel::FF_END_OF_LIFE)
            ->modify('last day of this month');

        if ($now > $eol) {
            $versionState = 'eol';
        } elseif ($now > $eom) {
            $versionState = 'eom';
        /* @phpstan-ignore notIdentical.alwaysTrue */
        } elseif ('' !== Kernel::FF_EXTRA_VERSION) {
            $versionState = 'dev';
        } else {
            $versionState = 'stable';
        }

        return $versionState;
    }
}
