<?php

declare(strict_types=1);

namespace App\Pro\Controller\Admin\OAuth2Server;

use App\Pro\Entity\OAuth2Server\Client;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClientCrud extends AbstractCrudController
{
    public function __construct(
        private ParameterBagInterface $params,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    /**
     * @return iterable<FieldInterface>
     */
    public function configureFields(string $pageName): iterable
    {
        $scopes = $this->params->get('league.oauth2_server.scopes.default');

        return [
            TextField::new('name'),
            TextField::new('identifier'),
            TextField::new('secret')
                ->hideOnIndex()
                ->hideOnDetail()
                ->hideWhenCreating(),
            ChoiceField::new('scopeStrings')
                ->setChoices(array_combine($scopes, $scopes))
                ->allowMultipleChoices(),
            ChoiceField::new('grantStrings')
                ->setChoices([
                    OAuth2Grants::AUTHORIZATION_CODE => OAuth2Grants::AUTHORIZATION_CODE,
                    OAuth2Grants::CLIENT_CREDENTIALS => OAuth2Grants::CLIENT_CREDENTIALS,
                ])
                ->allowMultipleChoices(),
            ArrayField::new('redirectUriStrings')
                ->hideOnIndex(),
            BooleanField::new('active'),
        ];
    }

    public function createEntity(string $entityFqcn): Client
    {
        return new $entityFqcn(
            '',
            $this->generateRandomClientIdentifier(),
            $this->generateRandomClientSecret(),
        );
    }

    /**
     * @throws \Exception
     */
    private function generateRandomClientIdentifier(): string
    {
        return hash('md5', random_bytes(16));
    }

    /**
     * @throws \Exception
     */
    private function generateRandomClientSecret(): string
    {
        return hash('sha512', random_bytes(32));
    }
}
