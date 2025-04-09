<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Kernel;
use Fastfony\LicenseBundle\Security\LicenseChecker;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Fastfony extends AbstractDataCollector
{
    public function __construct(
        private readonly ?string $licenseKey,
        #[Autowire(service: 'fastfony_license.security.license_checker')]
        private readonly LicenseChecker $licenseChecker,
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
            'licenseKey' => $this->licenseKey,
            'licenseKeyValidity' => $this->checkLicenseKeyValidity($this->licenseKey),
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

    public function getFastfonyExtraVersion(): ?string
    {
        return Kernel::FF_EXTRA_VERSION;
    }

    public function getLicenseKey(): ?string
    {
        return $this->data['licenseKey'];
    }

    public function getLicenseKeyValidity(): bool
    {
        return !empty($this->data['licenseKey']) && $this->data['licenseKeyValidity'];
    }

    public function getName(): string
    {
        return 'fastfony';
    }

    private function checkLicenseKeyValidity(?string $licenseKey): bool
    {
        if (empty($licenseKey)) {
            return false;
        }

        return $this->licenseChecker->isValid($licenseKey);
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
        } elseif ('' !== $this->getFastfonyExtraVersion()) {
            $versionState = 'dev';
        } else {
            $versionState = 'stable';
        }

        return $versionState;
    }
}
