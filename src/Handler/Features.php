<?php

declare(strict_types=1);

namespace App\Handler;

enum Features: string
{
    case USERS_MANAGEMENT = 'users_management';
    case REGISTRATION = 'registration';
    case PAGES = 'pages';
    case PRODUCTS = 'products';
    case THEME_CHOOSER = 'theme_chooser';
    case OAUTH2_SERVER = 'oauth2_server';
}
