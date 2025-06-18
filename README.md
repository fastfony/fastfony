<p align="center"><a href="https://fastfony.com" target="_blank">
    <img src="assets/images/Fastfony-black.svg" alt="Fastfony" style="width: 250px">
</a></p>

# [Fastfony](https://fastfony.com) : pragmatic boilerplate and starter kit for fastly develop a project or launch a SaaS idea with Symfony

You really go fast, you really earn precious time.

<a href="https://github.com/fastfony/fastfony/actions/workflows/test.yaml"><img src="https://github.com/fastfony/fastfony/actions/workflows/test.yaml/badge.svg" alt="Unit & Functional Tests"></a>
[![Test Coverage](https://github.com/fastfony/fastfony/blob/badges/coverage.svg?raw=true)](https://github.com/fastfony/fastfony/actions/workflows/test.yaml)
[![fastfony](https://wakapi.dev/api/badge/neothone/interval:any/label:fastfony)](https://github.com/fastfony/fastfony)

## Getting Started & Documentation

For starting with Fastfony, you can follow the online [installation guide](https://docs.fastfony.com/for-developers/install-start).

Documentation is available on [docs.fastfony.com](https://docs.fastfony.com/).

- Technology stack : https://docs.fastfony.com/technology-stack
- List of features : https://docs.fastfony.com/features
- [For developers](https://docs.fastfony.com/for-developers)
- [For admin users](https://docs.fastfony.com/for-admin-users)

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

If you have considerably modified the template, we don't recommend using _template-sync_.

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
