<?php

declare(strict_types=1);

namespace App\Tests\Functional\Admin\Feature;

use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;

final class IndexTest extends WebTestCase
{
    public function testToggleFeature(): void
    {
        $user = static::getContainer()->get(UserRepository::class)
            ->findOneBy(['enabled' => true]);
        self::ensureKernelShutdown();

        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($user);

        $client->request('GET', '/admin?routeName=admin_features');
        $this->assertSelectorExists('ul li input.toggle.form-check-input');

        $form = $client->getCrawler()->selectButton('Save')->form();

        // Search and untick the OAuth2 Server checkbox
        $checkboxes = $form->get('features_form[features]');
        /** @var ChoiceFormField$checkbox */
        foreach ($checkboxes as $checkbox) {
            if ('oauth2_server' === $checkbox->getValue()) {
                $checkbox->untick();
                break;
            }
        }
        $client->submit($form);

        $this->assertSelectorExists('#flash-messages .alert.alert-success');
    }
}
