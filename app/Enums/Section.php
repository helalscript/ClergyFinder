<?php

namespace App\Enums;

enum Section: string
{
    // ===== Home Page Sections =====
    case HeroSection = 'hero_section';
    case CounterSection = 'counter_section';
    case CounterSectionContent = 'counter_section_content';
    case MeetOurClergySection = 'meet_our_clergy_section';
    case HomeHowItWorksSection = 'home_how_it_works_section';
    case HomeHowItWorksSectionContent = 'home_how_it_works_section_content';
    case ReviewSection = 'review_section';
    case ReviewSectionContent = 'review_section_content';
    case SpiritualJourneySection = 'spiritual_journey_section';
    case SocialLinkContainer = 'social_link_container';

    // ===== About Page Sections =====
    case OurStory = 'our_story';
    case OurVision = 'our_vision';
    case OurMission = 'our_mission';
    case HowItWorks = 'how_it_works';
    case OurServices = 'our_services';
    case LeadershipTeamSection = 'leadership_team_section';
    case LeadershipTeamSectionContent = 'leadership_team_section_content';
    case AboutFaqs = 'about_faqs'; // to differentiate from home page faq
    case AboutTestimonials = 'about_testimonials';
    case HowitWorksSection = 'howit_works_section';
    case HowitWorksSectionContent = 'howit_works_section_content';

    // ===== Resources Page Sections =====
    case LoanCalculatorSection = 'loan_calculator_section';
    case LoanCalculatorSectionContent = 'loan_calculator_section_content';
    case RipeCreditTrackerGuide = 'ripe_credit_tracker_guide';
    case RepaymentOptions = 'repayment_options';
    case FeesPolicies = 'fees_policies';
    case SecurityPrivacy = 'security_privacy';
    case Faqs = 'faqs';
    case Blogs = 'blogs';

    // ===== Contact Us Page Sections =====
    case Main = 'main';
    case Form = 'form';

    // ===== Auth Page Sections =====
    case Login = 'login';
    case Register = 'register';
    case ForgotPassword = 'forgot_password';

    case PageContentSection = 'page_content_section';
    case PageContentSectionContent = 'page_content_section_content';

    // ===== Onboarding Page Sections =====
    case OnboardingScreenSection = 'onboarding_screen_section';
    case OnboardingScreenSectionContent = 'onboarding_screen_section_content';

    // ===== How It Work Page Sections =====
    case HowItWorkHeroSection = 'how_it_work_hero_section';
    case HowItWorkWorkProgressSection = 'how_it_work_work_progress_section';
    case HowItWorkWorkProgressSectionContent = 'how_it_work_work_progress_section_content';
    case HowItWorkOurValuesSection = 'how_it_work_our_values_section';
    case HowItWorkOurValuesSectionContent = 'how_it_work_our_values_section_content';
    case HowItWorkSpiritualJourneySection = 'how_it_work_spiritual_journey_section';

    // ===== Find Clergy Page Sections =====
    case FindClergyHeroSection = 'find_clergy_hero_section';

    // ===== FAQ Page Sections =====
    case FaqPageHeroSection = 'faq_page_hero_section';

}


