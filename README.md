# Fastfony : boilerplate, starter kit for develop your project or launch your SaaS idea with Symfony 7

## Getting Started

1. If not already, [install Symfony CLI](https://symfony.com/download), [install Node](https://nodejs.org/en/download) and [install Docker Compose](https://docs.docker.com/compose/install/)
2. First time, run `composer install && npm ci`
3. Run `docker compose up -d && symfony serve -d && npm run dev-server`
4. Open `https://127.0.0.1:8000` in your favorite web browser

## Start & stop

* Start : run `docker compose up -d && symfony serve -d && npm run dev-server`
* Stop : quit the start prompt and run `symfony server:stop && docker compose down`

## Features

* Register and received a login link by email
* Login form with email that send a login link by email
* Settings panel
* DaisyUI themes chooser on admin panel
* API Platform 4
* EasyAdmin (with Bootstrap 5) and CRUD controllers for : Users, Parameters, Parameter Categories
* Webpack Encore, Vue.js, Tailwind CSS & DaisyUI

## Docs

1. [Coding Style](docs/coding_style.md)
2. [Entities](docs/entities.md)
3. [Repositories](docs/repositories.md)
4. [CSS Theme](docs/css_theme.md)

# Updating Your Project

To import the changes made to the *Fastfony* template into your project, we recommend using
[*template-sync*](https://github.com/coopTilleuls/template-sync):

1. Run the script to synchronize your project with the latest version of Fastfony:

```console
curl -sSL https://raw.githubusercontent.com/coopTilleuls/template-sync/main/template-sync.sh | sh -s -- https://github.com/fastfony/fastfony
```

2. Resolve conflicts, if any
3. Run `git cherry-pick --continue`

For more advanced options, refer to [the documentation of *template sync*](https://github.com/coopTilleuls/template-sync#template-sync).

## License

Fastfony is available under the MIT License.

## Credits

* Fastfony is created by [Mathieu Dumoutier](https://mathieu.dumoutier.fr) and sponsored by [Minuit 11](https://minuit11.fr).
