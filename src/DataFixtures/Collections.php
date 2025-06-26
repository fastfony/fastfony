<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Collection\Field;
use App\Entity\Collection\Record;
use App\Entity\Collection\RecordCollection;
use App\Entity\Collection\RecordFieldValue;
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

        $this->addReference('title-field', $titleField);
        $this->addReference('description-field', $descriptionField);
        $this->addReference('icon-field', $iconField);

        $featureCollection
            ->addField($titleField)
            ->addField($descriptionField)
            ->addField($iconField);

        $manager->persist($featureCollection);
        $this->addReference(
            'features',
            $featureCollection
        );

        $records = [
            'content_management' => [
                'title' => 'Content Management',
                'description' => "• Simple SEO-Friendly Pages: Create HTML or Twig pages with built-in SEO\n• Collections & Records: Structure your data with flexible collections\n• Taxonomies & Tags: Organize your content for easy filtering\n• Edit in place: Modify content directly from the front-end",
                'icon' => 'fas fa-file-code',
            ],
            'user_authentication' => [
                'title' => 'Authentication & User Management',
                'description' => "• Magic Link Login: Login via secure links sent by email\n• Full User Management: Manage users, profiles and roles\n• Multiple Auth Methods: Email, password or OAuth with Google and GitHub\n• Profile with Avatar: Add profile pictures with local or S3 storage\n• Permissions Matrix: Control access with an intuitive permissions system\n• Password Reset Flow: Allow users to reset their password",
                'icon' => 'fas fa-users-cog',
            ],
            'e_commerce' => [
                'title' => 'E-Commerce & Payments',
                'description' => "• Product Management: Manage products with front & back views, connected to Stripe\n• Stripe Payments: Sell one-time or recurring products with Stripe integration\n• Contact Form Ready: Easily collect user requests",
                'icon' => 'fas fa-shopping-cart',
            ],
            'admin_tools' => [
                'title' => 'Administration Tools',
                'description' => "• Scheduler & Logs: Monitor recurring tasks and view execution logs\n• Reusable CRUD Setup: Quickly manage settings, contacts, etc\n• Settings Panel: Easily manage application-level configurations",
                'icon' => 'fas fa-cogs',
            ],
            'ui_ux' => [
                'title' => 'User Interface & UX',
                'description' => "• Tailwind with DaisyUI & Theme Switcher: Change themes on the fly\n• Toast Notifications: Display clean alerts with Symfony flashes and Vue Toastification\n• i18n Multi-language: Ready for international applications",
                'icon' => 'fas fa-palette',
            ],
            'api_integration' => [
                'title' => 'API & Integrations',
                'description' => "• OAuth2 Server Ready: Authenticate users and applications with standard OAuth2 flows\n• Flexible API Access: Expose public, internal or private APIs\n• API Platform: Create powerful and documented REST and GraphQL APIs",
                'icon' => 'fas fa-plug',
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

            $record = (new Record())
                ->setCollection($featureCollection)
                ->setPublished(true)
                ->addField($titleValue)
                ->addField($descriptionValue)
                ->addField($iconValue);

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

        $this->setReference('title-field', $titleField);
        $this->setReference('description-field', $descriptionField);
        $this->setReference('icon-field', $iconField);

        $useCasesCollection
            ->addField($titleField)
            ->addField($descriptionField)
            ->addField($iconField)
        ;

        $manager->persist($useCasesCollection);
        $this->addReference(
            'use-cases',
            $useCasesCollection
        );

        $useCases = [
            'content_management' => [
                'title' => 'Content Management Solutions',
                'description' => "• Simple CMS Solution: Build a custom content site with SEO pages, collections, and contact form\n• Landing Page Builder: Quickly create and manage SEO-optimized landing pages for campaigns\n• Multilingual App: Create multilingual apps ready for global users with built-in i18n tools",
                'icon' => 'fas fa-edit',
            ],
            'api_services' => [
                'title' => 'API & Integration Services',
                'description' => "• Headless CMS: Use Fastfony as a backend-only CMS with a full-featured OAuth2 API\n• API-first Backbone: Expose your app's logic through a secure and flexible Symfony and API Platform-based API\n• OAuth2 Auth System: Provide secure authentication for users and third-party apps via OAuth2",
                'icon' => 'fas fa-network-wired',
            ],
            'ecommerce' => [
                'title' => 'E-Commerce Solutions',
                'description' => "• E-Commerce Platform: Create a modern storefront with Stripe integration and product management\n• Contact Request System: Collect and manage customer messages through smart contact forms",
                'icon' => 'fas fa-shopping-cart',
            ],
            'user_management' => [
                'title' => 'User Access & Management',
                'description' => "• User Portal: Build a complete user space with profiles, permissions and login options\n• OAuth2 Auth System: Provide secure authentication for users and third-party apps via OAuth2",
                'icon' => 'fas fa-user-lock',
            ],
            'administration' => [
                'title' => 'Administrative Tools',
                'description' => "• Admin Dashboard: Quickly generate admin views to manage content, users and settings\n• Job Monitoring Tool: Manage and track scheduled jobs with logs from Symfony Messenger",
                'icon' => 'fas fa-tools',
            ],
            'custom_applications' => [
                'title' => 'Custom Application Development',
                'description' => "• Your SaaS Project: Fastly develop your SaaS project, leveraging Fastfony powerful features and flexibility\n• Multi-purpose Platform: Create any web application utilizing the full range of Fastfony components and tools",
                'icon' => 'fas fa-building',
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

            $record = (new Record())
                ->setCollection($useCasesCollection)
                ->setPublished(true)
                ->addField($titleValue)
                ->addField($descriptionValue)
                ->addField($iconValue)
            ;

            $manager->persist($record);
        }

        $manager->flush();
    }
}
