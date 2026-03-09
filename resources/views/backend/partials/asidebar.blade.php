{{-- @php
$systemSetting = App\Models\SystemSetting::first();
@endphp --}}
<!-- Start Sidebar Area -->
<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="{{ route('admin.dashboard') }}" class="d-block text-decoration-none position-relative">
            <img src="{{ asset($systemSetting->logo ?? 'backend/admin/assets/logo.png') }}" alt="logo-icon"
                style="height: 100px">
        </a>
        <button
            class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
            id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar
        style="height: calc(100vh - 100px)">
        <ul class="menu-inner">
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">MAIN</span>
            </li>
            <!-- Dashboard Menu Item -->
            <li class="menu-item open">
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">dashboard</span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">APPS</span>
            </li>


            <li class="menu-item open">
                <a href="{{ route('admin_contact_us.index') }}"
                    class="menu-link {{ request()->routeIs('admin_contact_us.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">content_paste</span>
                    <span class="title">Contact Message</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route('faqs.index') }}"
                    class="menu-link {{ request()->routeIs('faqs.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">help_outline</span>
                    <span class="title">FAQ's</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route('blogs.index') }}"
                    class="menu-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">book</span>
                    <span class="title">Blogs</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route('social_link.index') }}"
                    class="menu-link {{ request()->routeIs('social_link.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">link</span>
                    <span class="title">Social Link</span>
                </a>
            </li>
            <!-- menu title -->
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">OTHERS</span>
            </li>

            <!-- Settings Menu Item -->
            <li class="menu-item {{ request()->routeIs('profile_settings.*', 'system_settings.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">settings</span>
                    <span class="title">Settings</span>
                </a>
                <ul class="menu-sub">
                    <!-- Account Settings Submenu -->
                    <li class="menu-item ">
                        <a href="{{ route('profile_settings.index') }}"
                            class="menu-link {{ request()->routeIs('profile_settings.index') ? 'active' : '' }} ">
                            Profile Settings
                        </a>
                    </li>
                    <!-- Change Password Submenu -->
                    <li class="menu-item">
                        <a href="{{ route('profile_settings.password_change') }}"
                            class="menu-link {{ request()->routeIs('profile_settings.password_change') ? 'active' : '' }}">
                            Change Password
                        </a>
                    </li>
                    <!-- System Settings Configaration Submenu -->
                    <li class="menu-item">
                        <a href="{{ route('system_settings.index') }}"
                            class="menu-link {{ request()->routeIs('system_settings.*') ? 'active' : '' }}">
                            System Configaration
                        </a>
                    </li>

                </ul>
            </li>
            <!-- dynamic page Menu Item -->
            <li class="menu-item {{ request()->routeIs('dynamic_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">clarify</span>
                    <span class="title">Dynamic page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('dynamic_page.index') }}"
                            class="menu-link {{ request()->routeIs('dynamic_page.index') ? 'active' : '' }}">
                            Pages
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dynamic_page.create') }}"
                            class="menu-link {{ request()->routeIs('dynamic_page.create') ? 'active' : '' }}">
                            Add New
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">CMS</span>
            </li>

            <!-- CMS Home Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.home_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">home</span>
                    <span class="title">Home Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.counter_section.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.counter_section.*') ? 'active' : '' }}">
                            Counter Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.meet_our_clergy.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.meet_our_clergy.*') ? 'active' : '' }}">
                            Meet Our Clergy
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.how_it_works_section.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.how_it_works_section.*') ? 'active' : '' }}">
                            How It Works
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.review_section.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.review_section.*') ? 'active' : '' }}">
                            Review Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.spiritual_journey.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.spiritual_journey.*') ? 'active' : '' }}">
                            Spiritual Journey
                        </a>
                    </li>
                </ul>
            </li>


            <!-- CMS How It Work Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.how_it_work_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">psychology</span>
                    <span class="title">How It Work Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.how_it_work_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.how_it_work_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.how_it_work_page.work_progress_section.index') }}"
                            class="menu-link {{ request()->routeIs('cms.how_it_work_page.work_progress_section.*') ? 'active' : '' }}">
                            Work Progress Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.how_it_work_page.our_values_section.index') }}"
                            class="menu-link {{ request()->routeIs('cms.how_it_work_page.our_values_section.*') ? 'active' : '' }}">
                            Our Values Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.how_it_work_page.spiritual_journey.create') }}"
                            class="menu-link {{ request()->routeIs('cms.how_it_work_page.spiritual_journey.*') ? 'active' : '' }}">
                            Spiritual Journey
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Find Clergy Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.find_clergy_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">person_search</span>
                    <span class="title">Find Clergy Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.find_clergy_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.find_clergy_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Onboarding Screen Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.onboarding_screen.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">slideshow</span>
                    <span class="title">Onboarding Screen</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.onboarding_screen.screens.index') }}"
                            class="menu-link {{ request()->routeIs('cms.onboarding_screen.screens.*') ? 'active' : '' }}">
                            Screens
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Question Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.question_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">quiz</span>
                    <span class="title">Question Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.question_page.clergy.index') }}"
                            class="menu-link {{ request()->routeIs('cms.question_page.clergy.*') ? 'active' : '' }}">
                            Clergy Questions
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.question_page.seeker.index') }}"
                            class="menu-link {{ request()->routeIs('cms.question_page.seeker.*') ? 'active' : '' }}">
                            Seeker Questions
                        </a>
                    </li>
                </ul>
            </li>


            {{-- <!-- CMS Contact Us Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.contact_us_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">contacts</span>
                    <span class="title">Contact Us</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.contact_us_page.index') }}"
                            class="menu-link {{ request()->routeIs('cms.contact_us_page.index') ? 'active' : '' }}">
                            Contact Information
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.contact_us_page.form.create') }}"
                            class="menu-link {{ request()->routeIs('cms.contact_us_page.form.*') ? 'active' : '' }}">
                            Contact Form
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Auth Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.auth_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">fingerprint</span>
                    <span class="title">Auth</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.auth_page.login.create') }}"
                            class="menu-link {{ request()->routeIs('cms.auth_page.login.*') ? 'active' : '' }}">
                            Login
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.auth_page.register.create') }}"
                            class="menu-link {{ request()->routeIs('cms.auth_page.register.*') ? 'active' : '' }}">
                            Register
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.auth_page.forgot_password.create') }}"
                            class="menu-link {{ request()->routeIs('cms.auth_page.forgot_password.*') ? 'active' : '' }}">
                            Forgot Password
                        </a>
                    </li>
                </ul>
            </li> --}}

            <!-- Logout Menu Item -->
            <li class="menu-item">
                <a class="menu-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form-asidebar').submit();">
                    <span class="material-symbols-outlined menu-icon">logout</span>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
        <form id="logout-form-asidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </aside>
</div>
<!-- End Sidebar Area -->
