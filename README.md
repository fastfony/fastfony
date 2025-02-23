# Fastfony : boilerplate, starter kit for develop your project or launch your SaaS idea with Symfony 7

## Getting Started

1. If not already, [install PHP](https://www.php.net/manual/en/install.php), [install Composer](https://getcomposer.org) [install Symfony CLI](https://symfony.com/download), [install Node](https://nodejs.org/en/download), [install Docker Compose](https://docs.docker.com/compose/install/) and [install Taskfile](https://taskfile.dev/installation/)
2. First time, run `task fixtures`
3. Run `task start`
4. Open `https://fastfony.wip` (if you have setting up the [Symfony local proxy](https://symfony.com/doc/current/setup/symfony_server.html#setting-up-the-local-proxy)) or `https://127.0.0.1:9876` in your favorite web browser

If you want to change the domain name, you can edit the `.symfony.local.yaml` file and change the `proxy.domains` variable.

## Start & stop

* Start : just run `task start`
* Stop : just run `task stop` (Thanks to Taskfile!)

## Execute tests

* Run `task tests`

## Features

* Register and received a login link by email
* Login form with email that send a login link by email
* Settings panel
* DaisyUI themes chooser on admin panel
* API Platform 4
* EasyAdmin (with Bootstrap 5) and CRUD controllers for : Users, Parameters, Parameter Categories
* Webpack Encore, Vue.js, Tailwind CSS & DaisyUI
* Taskfile for easy commands

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

## Contribute

You can contribute to Fastfony by creating a pull request on the GitHub repository.

This repository use the git plugin [git-flow](https://github.com/nvie/gitflow), so please create your feature branch from the `develop` branch and install [git-flow](https://git-flow.readthedocs.io/fr/latest/index.html).

The Conventional Commits specification is a lightweight convention on top of commit messages. Fastfony uses it. You can find more information on the [Conventional Commits website](https://www.conventionalcommits.org/en/v1.0.0/).

## License

Fastfony is available under the MIT License.

## Credits

* Fastfony is created by [Mathieu Dumoutier](https://mathieu.dumoutier.fr) and sponsored by [Minuit 11](https://minuit11.fr).
