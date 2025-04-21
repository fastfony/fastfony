<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Pro\Entity\Collection\Field;
use App\Pro\Entity\Collection\Record;
use App\Pro\Entity\Collection\RecordCollection;
use App\Pro\Entity\Collection\RecordFieldValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Collections extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->createFeatures($manager);
        $this->createUseCases($manager);
    }

    /**
     * @return array<string>
     */
    public static function getGroups(): array
    {
        return [
            AppFixtures::GROUP_INSTALL,
            AppFixtures::GROUP_TEST,
        ];
    }

    private function createFeatures(ObjectManager $manager): void
    {
        $featureCollection = (new RecordCollection())
            ->setName('features');

        $titleField = (new Field())
            ->setName('title')
            ->setType('plainText');

        $descriptionField = (new Field())
            ->setName('description')
            ->setType('plainText');

        $iconField = (new Field())
            ->setName('icon')
            ->setType('plainText');

        $proField = (new Field())
            ->setName('pro')
            ->setType('boolean');

        $this->addReference('title-field', $titleField);
        $this->addReference('description-field', $descriptionField);
        $this->addReference('icon-field', $iconField);
        $this->addReference('pro-field', $proField);

        $featureCollection
            ->addField($titleField)
            ->addField($descriptionField)
            ->addField($iconField)
            ->addField($proField);

        $manager->persist($featureCollection);
        $this->addReference(
            'features',
            $featureCollection
        );

        $records = [
            'simple_pages' => [
                'title' => 'Simple SEO-Friendly Pages',
                'description' => 'Create clean HTML or Twig pages with built-in SEO, in both front and backoffice.',
                'icon' => 'fas fa-file-code',
                'pro' => false,
            ],
            'collections_records' => [
                'title' => 'Collections & Records',
                'description' => 'Build structured data with flexible collections and custom records.',
                'icon' => 'fas fa-database',
                'pro' => true,
            ],
            'email_login_link' => [
                'title' => 'Magic Link Login',
                'description' => 'Let users register and log in via secure login links sent by email.',
                'icon' => 'fas fa-envelope-open-text',
                'pro' => false,
            ],
            'contact_form' => [
                'title' => 'Contact Form Ready',
                'description' => 'Easily collect user inquiries with a contact form.',
                'icon' => 'fas fa-paper-plane',
                'pro' => false,
            ],
            'user_management' => [
                'title' => 'Full User Management',
                'description' => 'Manage users, profiles, roles, groups and security out of the box.',
                'icon' => 'fas fa-users-cog',
                'pro' => false,
            ],
            'auth_methods' => [
                'title' => 'Multiple Auth Methods',
                'description' => 'Email, password or OAuth login with Google & GitHub pre-integrated.',
                'icon' => 'fas fa-unlock-alt',
                'pro' => true,
            ],
            'profile_upload' => [
                'title' => 'Profile with Avatar',
                'description' => 'Add user profile pictures with local or S3 storage via VichUploader.',
                'icon' => 'fas fa-user-circle',
                'pro' => false,
            ],
            'permissions_matrix' => [
                'title' => 'Permissions Matrix',
                'description' => 'Control user access with a detailed and intuitive permissions system.',
                'icon' => 'fas fa-lock',
                'pro' => true,
            ],
            'reset_password' => [
                'title' => 'Password Reset Flow',
                'description' => 'Allow users to securely reset their password via email.',
                'icon' => 'fas fa-key',
                'pro' => true,
            ],
            'product_management' => [
                'title' => 'Product Management',
                'description' => 'Manage products with front & back views, connected to your Stripe account.',
                'icon' => 'fas fa-box-open',
                'pro' => true,
            ],
            'stripe_integration' => [
                'title' => 'Stripe Payments',
                'description' => 'Sell one-time or recurring products with seamless Stripe integration.',
                'icon' => 'fas fa-credit-card',
                'pro' => true,
            ],
            'scheduler' => [
                'title' => 'Scheduler & Logs',
                'description' => 'Monitor recurring tasks and view execution logs in a simple dashboard.',
                'icon' => 'fas fa-clock',
                'pro' => true,
            ],
            'crud_controllers' => [
                'title' => 'Reusable CRUD Setup',
                'description' => 'Quickly manage parameters, contacts, and more with ready-to-go CRUDs.',
                'icon' => 'fas fa-th-list',
                'pro' => false,
            ],
            'settings_panel' => [
                'title' => 'Settings Panel',
                'description' => 'Easily manage app-level configurations through a clean UI.',
                'icon' => 'fas fa-cogs',
                'pro' => false,
            ],
            'theme_chooser' => [
                'title' => 'Tailwind with DaisyUI & Theme Switcher',
                'description' => 'Switch themes on the fly with integrated DaisyUI theme support.',
                'icon' => 'fas fa-palette',
                'pro' => false,
            ],
            'toasts' => [
                'title' => 'Toast Notifications',
                'description' => 'Display clean alerts using Symfony flashes and Vue Toastification.',
                'icon' => 'fas fa-bell',
                'pro' => false,
            ],
            'oauth2_server' => [
                'title' => 'OAuth2 Server Ready',
                'description' => 'Authenticate users and apps using standard OAuth2 flows.',
                'icon' => 'fas fa-server',
                'pro' => true,
            ],
            'api_modes' => [
                'title' => 'Flexible API Access',
                'description' => 'Expose public, internal or private APIs — perfect for headless setups.',
                'icon' => 'fas fa-plug',
                'pro' => false,
            ],
            'i18n' => [
                'title' => 'i18n Multi-language',
                'description' => 'Ready for international apps with Vue I18n & Symfony translations.',
                'icon' => 'fas fa-globe',
                'pro' => false,
            ],
        ];

        foreach ($records as $recordData) {
            $titleValue = (new RecordFieldValue())
                ->setField($this->getReference('title-field', Field::class))
                ->setValue($recordData['title']);

            $manager->persist($titleValue);

            $descriptionValue = (new RecordFieldValue())
                ->setField($this->getReference('description-field', Field::class))
                ->setValue($recordData['description']);

            $manager->persist($descriptionValue);

            $iconValue = (new RecordFieldValue())
                ->setField($this->getReference('icon-field', Field::class))
                ->setValue($recordData['icon']);

            $manager->persist($iconValue);

            $proValue = (new RecordFieldValue())
                ->setField($this->getReference('pro-field', Field::class))
                ->setValue((string) $recordData['pro']);

            $manager->persist($proValue);

            $record = (new Record())
                ->setCollection($featureCollection)
                ->setPublished(true)
                ->addField($titleValue)
                ->addField($descriptionValue)
                ->addField($iconValue)
                ->addField($proValue);

            $manager->persist($record);
        }

        $manager->flush();
    }

    private function createUseCases(ObjectManager $manager): void
    {
        $useCasesCollection = (new RecordCollection())
            ->setName('use-cases')
        ;

        $titleField = (new Field())
            ->setName('title')
            ->setType('plainText');

        $descriptionField = (new Field())
            ->setName('description')
            ->setType('plainText')
        ;

        $iconField = (new Field())
            ->setName('icon')
            ->setType('plainText')
        ;

        $proField = (new Field())
            ->setName('pro')
            ->setType('boolean');

        $this->setReference('title-field', $titleField);
        $this->setReference('description-field', $descriptionField);
        $this->setReference('icon-field', $iconField);
        $this->setReference('pro-field', $proField);

        $useCasesCollection
            ->addField($titleField)
            ->addField($descriptionField)
            ->addField($iconField)
            ->addField($proField)
        ;

        $manager->persist($useCasesCollection);
        $this->addReference(
            'use-cases',
            $useCasesCollection
        );

        $useCases = [
            'simple_cms' => [
                'title' => 'Simple CMS Solution',
                'description' => 'Build a custom content site with SEO pages, collections, and contact form.',
                'icon' => 'fas fa-edit',
                'pro' => false,
            ],
            'headless_cms' => [
                'title' => 'Headless CMS',
                'description' => 'Use Fastfony as a backend-only CMS with a full-featured OAuth2 API.',
                'icon' => 'fas fa-network-wired',
                'pro' => false,
            ],
            'auth_platform' => [
                'title' => 'OAuth2 Auth System',
                'description' => 'Provide secure authentication for users and third-party apps via OAuth2.',
                'icon' => 'fas fa-user-shield',
                'pro' => true,
            ],
            'ecommerce_platform' => [
                'title' => 'E-Commerce Platform',
                'description' => 'Create a modern storefront with Stripe integration and product management.',
                'icon' => 'fas fa-shopping-cart',
                'pro' => true,
            ],
            'user_portal' => [
                'title' => 'User Portal',
                'description' => 'Build a complete user space with profiles, permissions and login options.',
                'icon' => 'fas fa-user-lock',
                'pro' => false,
            ],
            'contact_platform' => [
                'title' => 'Contact Request System',
                'description' => 'Collect and manage customer messages through smart contact forms.',
                'icon' => 'fas fa-comments',
                'pro' => false,
            ],
            'admin_panel' => [
                'title' => 'Admin Dashboard',
                'description' => 'Quickly generate admin views to manage content, users and settings.',
                'icon' => 'fas fa-tools',
                'pro' => false,
            ],
            'multi_lang_app' => [
                'title' => 'Multilingual App',
                'description' => 'Create multilingual apps ready for global users with built-in i18n tools.',
                'icon' => 'fas fa-language',
                'pro' => false,
            ],
            'api_backbone' => [
                'title' => 'API-first Backbone',
                'description' => 'Expose your app’s logic through a secure and flexible Symfony and API Platform-based API.',
                'icon' => 'fas fa-code-branch',
                'pro' => false,
            ],
            'job_monitoring' => [
                'title' => 'Job Monitoring Tool',
                'description' => 'Manage and track scheduled jobs with logs from Symfony Messenger.',
                'icon' => 'fas fa-tasks',
                'pro' => true,
            ],
            'landing_builder' => [
                'title' => 'Landing Page Builder',
                'description' => 'Quickly create and manage SEO-optimized landing pages for campaigns.',
                'icon' => 'fas fa-rocket',
                'pro' => false,
            ],
            'your_saas_project' => [
                'title' => 'Your SaaS Project',
                'description' => 'Fastly develop your SaaS project, leveraging Fastfony powerful features and flexibility.',
                'icon' => 'fas fa-building',
                'pro' => true,
            ],
        ];

        foreach ($useCases as $useCaseData) {
            $titleValue = (new RecordFieldValue())
                ->setField($this->getReference('title-field', Field::class))
                ->setValue($useCaseData['title']);

            $manager->persist($titleValue);

            $descriptionValue = (new RecordFieldValue())
                ->setField($this->getReference('description-field', Field::class))
                ->setValue($useCaseData['description']);

            $manager->persist($descriptionValue);

            $iconValue = (new RecordFieldValue())
                ->setField($this->getReference('icon-field', Field::class))
                ->setValue($useCaseData['icon']);

            $manager->persist($iconValue);

            $proValue = (new RecordFieldValue())
                ->setField($this->getReference('pro-field', Field::class))
                ->setValue((string) $useCaseData['pro']);

            $manager->persist($proValue);

            $record = (new Record())
                ->setCollection($useCasesCollection)
                ->setPublished(true)
                ->addField($titleValue)
                ->addField($descriptionValue)
                ->addField($iconValue)
                ->addField($proValue)
            ;

            $manager->persist($record);
        }

        $manager->flush();
    }
}
