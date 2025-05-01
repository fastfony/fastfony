<p align="center"><a href="https://fastfony.com" target="_blank">
    <img src="assets/images/Fastfony-black.svg" alt="Fastfony" style="width: 250px">
</a></p>

# [Fastfony](https://fastfony.com) : pragmatic boilerplate and starter kit for fastly develop a project or launch a SaaS idea with Symfony

You really go fast, you really earn precious time.

<a href="https://github.com/fastfony/fastfony/actions/workflows/test.yaml"><img src="https://github.com/fastfony/fastfony/actions/workflows/test.yaml/badge.svg" alt="Unit & Functional Tests"></a>
[![Test Coverage](https://github.com/fastfony/fastfony/blob/badges/coverage.svg?raw=true)](https://github.com/fastfony/fastfony/actions/workflows/test.yaml)
[![fastfony](https://wakapi.dev/api/badge/neothone/interval:any/label:fastfony)](https://github.com/fastfony/fastfony)

## Getting Started

1. If not already:
    1. [install PHP](https://www.php.net/manual/en/install.php),
    2. [install Composer](https://getcomposer.org),
    3. [install Symfony CLI](https://symfony.com/download),
    4. [install Node](https://nodejs.org/en/download),
    5. [install Docker Compose](https://docs.docker.com/compose/install/) and
    6. [install Taskfile](https://taskfile.dev/installation/)
2. Run `task start`
3. If you want use OAuth2 server run `task oauth2-server-init`
4. Enjoy!

Be careful to the minimum versions requirements for:

- PHP: 8.2
- Symfony CLI: 5.11
- Node: 22 with npm and _npx_

Specific things from Fastfony:

- Translation files are not in `./translations` directory but in `./assets/locales` directory in order to use [vue-i18n](https://vue-i18n.intlify.dev/) and symfony translation component. We try to apply [php-translation best practices](https://php-translation.readthedocs.io/en/latest/best-practice/index.html).
- In directories `pro` or `Pro` you can find the pro features of Fastfony. You can use them if you have a license. If you don't have a license, you can use only in development mode.

## Documentation

Documentation is available on [docs.fastfony.com](https://docs.fastfony.com/).

## Features

- Simple pages with HTML or custom twig template and SEO friendly (front and backoffice)
- Collections & records builder
- Taxonomy management (categories, tags, etc.)
- Register and received a login link by email
- Contact requests form
- Complete user management with Profile (and Groups, Roles) and security features :
    - Login form with email that send a login link by email
    - Login form with password
    - Login with OAuth clients (Google and Github are already installed)
    - Profile with photo (and upload on AWS S3 if you want, with VichUploader and Flysystem)
    - Permissions matrix for the management of the user's rights
    - Reset password by email
- Complete product management linked to your [Stripe](https://stripe.com) account (if you want) with front and backoffice :
    - Product page
    - Products list
    - Buy with Stripe and save orders (subscription or one-time payment)
- Scheduler dashboard and logs : list configured recurring messages and display logs
- CRUD controllers for : Parameters, Parameter categories, Contact requests, etc.
- Settings panel
- DaisyUI themes chooser
- Toasts notifications (flash messages from Symfony and others with [vue-toastification](https://vue-toastification.maronato.dev/))
- OAuth2 Server for authenticated registered users and by applications/clients via OAuth2 grant types (password, client credentials, authorization code, refresh token)
- Public, internal and private with OAuth2 API (you can use it as a headless CMS!)
- I18n ready with Vue I18n and Symfony translation component
- Edit translations in place

## Technical stack

- Symfony 7.2
- API Platform 4.1
- EasyAdmin 4 (with Bootstrap 5)
- Vue.js 3 & Bootstrap 5 for admin (with EasyAdmin)
- Vue.js 3 & Tailwind CSS 4 & DaisyUI 5 for front (and you can use also React or another frontend framework!)
- Vueuse, AG Grid, Vue I18n, Pinia, FormKit, PrimeVue and others for front
- Webpack Encore
- Taskfile for easy install & start commands (just `task start` and develop)
- Flysystem for file storage management (local and AWS S3 ready)
- GrumPHP for manage pre-hook commit
- PHPUnit and its unit and functional tests
- PHPStan (level 5, [symfony simplify rules](https://github.com/symplify/phpstan-rules) and [symfony extension](https://github.com/phpstan/phpstan-symfony))
- PHP Insights

## Start & stop

- Start : just run `task start`
- Stop : just run `task stop`

Thanks to Taskfile!

## Execute tests

- Run `task tests` or `task coverage`

## Execute analytics

- Run `task phpstan` for PHPStan
- Run `task phpinsights` for PHPInsights

# Updating Your Project

To import the changes made to the _Fastfony_ template into your project, we recommend using
[_template-sync_](https://github.com/coopTilleuls/template-sync):

1. Run the script on your branch `main` to synchronize your project with the latest version of Fastfony:

```console
curl -sSL https://raw.githubusercontent.com/coopTilleuls/template-sync/main/template-sync.sh | sh -s -- https://github.com/fastfony/fastfony
```

2. Resolve conflicts, if any
3. Run `git cherry-pick --continue`

For more advanced options, refer to [the documentation of _template sync_](https://github.com/coopTilleuls/template-sync#template-sync).

## Contributing

You can contribute to Fastfony by creating a pull request on the GitHub repository.

This repository use the git plugin [git-flow](https://github.com/nvie/gitflow), so please create your feature branch from the `develop` branch and install [git-flow](https://git-flow.readthedocs.io/fr/latest/index.html).

The Conventional Commits specification is a lightweight convention on top of commit messages. Fastfony uses it. You can find more information on the [Conventional Commits website](https://www.conventionalcommits.org/en/v1.0.0/).

## License

Fastfony is an open source project licensed under the [MIT license](https://opensource.org/licenses/MIT), expect all content that resides under any "pro/" or "Pro/" directory of this repository, if such directories exists, are licensed under the license defined in "pro/LICENSE" or "Pro/LICENSE".

## Special thanks

Without the following projects, Fastfony would not exist:

- [Symfony](https://symfony.com)
- [API Platform](https://api-platform.com)
- [EasyAdmin](https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html)
- [PHP Translation](https://php-translation.readthedocs.io)
- [Webpack Encore](https://symfony.com/doc/current/frontend.html)
- [Vue.js](https://vuejs.org)
- [Tailwind CSS](https://tailwindcss.com)
- [DaisyUI](https://daisyui.com)
- [Taskfile](https://taskfile.dev)
- [Quill](https://quilljs.com)
- and many others...
