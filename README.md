# Fastfony : pragmatic boilerplate for fastly develop a project with Symfony 7

You go fast, you earn precious time.

<a href="https://github.com/fastfony/fastfony/actions/workflows/test.yaml"><img src="https://github.com/fastfony/fastfony/actions/workflows/test.yaml/badge.svg" alt="Unit & Functional Tests"></a>
[![Test Coverage](https://raw.githubusercontent.com/fastfony/fastfony/refs/heads/badges/coverage.svg)](https://github.com/fastfony/fastfony)
[![Wakapi.dev](https://wakapi.dev/api/badge/neothone/interval:any/project:fastfony)](https://github.com/fastfony/fastfony)

## Getting Started

1. If not already:
    1. [install PHP](https://www.php.net/manual/en/install.php),
    2. [install Composer](https://getcomposer.org),
    3. [install Symfony CLI](https://symfony.com/download),
    4. [install Node](https://nodejs.org/en/download),
    5. [install Docker Compose](https://docs.docker.com/compose/install/) and
    6. [install Taskfile](https://taskfile.dev/installation/)
2. Run `task start`
3. Enjoy!

Be careful to the minimum versions requirements for:
- PHP: 8.2
- Symfony CLI: 5.11
- Node: 22 with npm and _npx_

## Start & stop

- Start : just run `task start`
- Stop : just run `task stop` 

Thanks to Taskfile!

## Features

- Simple pages SEO friendly (front and backoffice)
- Simple products with prices SEO ready (backoffice only)
- Register and received a login link by email
- Login form with email that send a login link by email
- Users management with Profile (and Groups, Roles)
- Settings panel
- DaisyUI themes chooser
- Toasts notifications (flash messages from Symfony and others with [vue-toastification](https://vue-toastification.maronato.dev/))
- CRUD controllers for : Parameters, Parameter categories etc...
- And more features in **[Pro version](https://fastfony.com/pro)!**

## Technical stack

- Symfony 7.2
- API Platform 4.1
- EasyAdmin 4 (with Bootstrap 5)
- Vue.js 3 & Bootstrap 5 for admin (with EasyAdmin)
- Vue.js 3 & Tailwind CSS 4 & DaisyUI 5 for front (and you can use also React or another frontend framework!)
- Webpack Encore
- Taskfile for easy install & start commands (just `task start` and develop)
- GrumPHP for manage pre-hook commit
- PHPUnit and its unit and functional tests
- PHPStan (level 5, [symfony simplify rules](https://github.com/symplify/phpstan-rules) and [symfony extension](https://github.com/phpstan/phpstan-symfony))
- PHP Insights

## Execute tests

- Run `task tests` or `task coverage`

## Execute analytics

- Run `task phpstan` for PHPStan
- Run `task phpinsights` for PHPInsights

## Docs

Documentation is available on [docs.fastfony.com](https://docs.fastfony.com/).

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

## Special thanks

Without the following projects, Fastfony would not exist:

- [Symfony](https://symfony.com)
- [API Platform](https://api-platform.com)
- [EasyAdmin](https://symfony.com/doc/current/bundles/EasyAdminBundle/index.html)
- [Webpack Encore](https://symfony.com/doc/current/frontend.html)
- [Vue.js](https://vuejs.org)
- [Tailwind CSS](https://tailwindcss.com)
- [DaisyUI](https://daisyui.com)
- [Taskfile](https://taskfile.dev)
- [Editorjs](https://editorjs.io)
- and many others...

## Credits

Fastfony is created by [Minuit 11](https://minuit11.fr) a french development company.
