<?php

namespace App\Enums;

enum Page: string
{
    case ContactUsPage = 'contact_us_page';
    case HomePage = 'home_page';
    case AboutPage = 'about_page';
    case ResourcesPage = 'resources_page';
    // case ContactUsPage = 'contact_us_page';
    case AuthPage = 'auth_page';

    case StateLicensingPage = 'state_licensing_page';

    case OnboardingPage = 'onboarding_page';

    case HowItWorkPage = 'how_it_work_page';

    case FindClergyPage = 'find_clergy_page';

    case FaqPage = 'faq_page';

}



