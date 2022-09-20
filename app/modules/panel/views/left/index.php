<div class="shadow-bottom"></div>
<ul class="list-unstyled menu-categories" id="accordionExample">
    <!-- Dashboard -->
    <li class="menu">
        <a <?= activeIF(['panel'], 'index', 'data-active="true" aria-expanded="false"') ?>
                href="{URL:panel}" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Dashboard</span>
            </div>
        </a>
    </li>

    <!-- Vacancy Management -->
    <li class="menu">
        <a <?= $show = activeIF(['sectors', 'tech_stack', 'locations', 'vacancies', 'offices'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_vacancy" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                <span>Vacancy Management</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_vacancy" data-parent="#accordionExample">
            <li class="<?= activeIF('vacancies', false, 'active') ?>">
                <a href="{URL:panel/vacancies}">
                    Vacancy Manager
                </a>
            </li>
            <li class="<?= activeIF('sectors', false, 'active') ?>">
                <a href="{URL:panel/vacancies/sectors}">
                    Sectors / Industries
                </a>
            </li>
            <li class="<?= activeIF('locations', false, 'active') ?>">
                <a href="{URL:panel/vacancies/locations}">
                    Locations
                </a>
            </li>
            <li class="<?= activeIF('tech_stack', false, 'active') ?>">
                <a href="{URL:panel/vacancies/tech_stack}">
                    Tech Stack
                </a>
            </li>
            <li class="<?= activeIF('offices', false, 'active') ?>">
                <a href="{URL:panel/offices}">
                    Offices
                </a>
            </li>
        </ul>
    </li>

    <!-- Talent Pool -->
    <li class="menu">
        <a <?= $show = activeIF(['candidates', 'vacancy_applications'], ['index','edit','submited_cv', 'view', 'applications', 'archive'], 'data-active="true" aria-expanded="false"') ?>
                href="#tab_talent_pool" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <span>Talent Pool</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_talent_pool" data-parent="#accordionExample">
            <li class="<?= activeIF('candidates', false, 'active') ?>">
                <a href="{URL:panel/candidates}">
                    Candidate Registrations
                </a>
            </li>
            <li class="<?= activeIF('vacancy_applications', ['index', 'edit', 'view', 'archive'], 'active') ?>">
                <a href="{URL:panel/vacancy_applications}">
                    Vacancy Applications
                </a>
            </li>
            <li class="<?= activeIF('vacancy_applications', 'submited_cv', 'active') ?>">
                <a href="{URL:panel/vacancy_applications/submited_cv}">
                    Uploaded CVs
                </a>
            </li>
        </ul>
    </li>

    <!-- Blog / News -->
    <li class="menu">
        <a <?= $show = activeIF(['categories', 'blog', 'blogmod', 'blogtest', 'blog_ckeditor', 'blog_codex'], false, 'data-active="true" aria-expanded="false"',
            (CONTROLLER !== 'panel/learning_development/categories' && CONTROLLER !== 'panel/event_card/categories')) ?>
                href="#tab_news" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                <span>Blog / News</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_news" data-parent="#accordionExample">
            <li class="<?= activeIF('blog', false, 'active') ?>">
                <a href="{URL:panel/blog}">
                    Blog Posts
                </a>
            </li>
            <li class="<?= activeIF('blogmod', false, 'active') ?>">
                <a href="{URL:panel/blogmod}">
                    Blog Mode
                </a>
            </li>
            <li class="<?= activeIF('blogtest', false, 'active') ?>">
                <a href="{URL:panel/blogtest}">
                    Blog Quill
                </a>
            </li>
            <li class="<?= activeIF('blog_ckeditor', false, 'active') ?>">
                <a href="{URL:panel/blog_ckeditor}">
                    Blog CKEditor
                </a>
            </li>
            <li class="<?= activeIF('blog_codex', false, 'active') ?>">
                <a href="{URL:panel/blog_codex}">
                    Blog Codex
                </a>
            </li>
            <li class="<?= activeIF('categories', false, 'active', (CONTROLLER !== 'panel/learning_development/categories' && CONTROLLER !== 'panel/event_card/categories')) ?>">
                <a href="{URL:panel/blog/categories}">
                    Categories
                </a>
            </li>
        </ul>
    </li>

    <!-- Resource -->
    <li class="menu">
        <a <?= $show = activeIF(['resources'], ['downloads', 'index', 'edit', 'add', 'archive'], 'data-active="true" aria-expanded="false"') ?>
                href="#tab_resources" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                <span>Resources</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_resources" data-parent="#accordionExample">
            <li class="<?= activeIF('resources', ['index', 'edit', 'add', 'archive'], 'active') ?>">
                <a href="{URL:panel/resources}">
                    Resources
                </a>
            </li>
            <li class="<?= activeIF('resources', 'downloads', 'active') ?>">
                <a href="{URL:panel/resources/downloads}">
                    Resource Downloads
                </a>
            </li>
        </ul>
    </li>

    <!-- Event -->
    <li class="menu">
        <a <?= $show = activeIF(['events', 'event_card', 'categories'], false, 'data-active="true" aria-expanded="false"', (CONTROLLER !== 'panel/learning_development/categories' && CONTROLLER !== 'panel/blog/categories')) ?>
                href="#tab_events" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                <span>Event Manager</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_events" data-parent="#accordionExample">

            <li class="<?= activeIF('event_card', false, 'active') ?>">
                <a href="{URL:panel/event_card}">
                    Events
                </a>
            </li>
            <li class="<?= activeIF('categories', false, 'active', (CONTROLLER !== 'panel/learning_development/categories' && CONTROLLER !== 'panel/blog/categories')) ?>">
                <a href="{URL:panel/event_card/categories}">
                    Events' Categories
                </a>
            </li>
        </ul>
    </li>

    <!-- Content Management -->
    <li class="menu">
        <a <?= $show = activeIF(['content_pages', 'landings', 'testimonials', 'videos'], false, 'data-active="true" aria-expanded="true"') ?>
                href="#tab_content_manager" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                <span>Content Management</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_content_manager" data-parent="#accordionExample">
            <li class="<?= activeIF('content_pages', false, 'active') ?>">
                <a href="{URL:panel/content_pages}">
                    Content Pages
                </a>
            </li>
            <li class="<?= activeIF('landings', false, 'active') ?>">
                <a href="{URL:panel/landings}">
                    Page Builder
                </a>
            </li>
            <li class="<?= activeIF('testimonials', false, 'active') ?>">
                <a href="{URL:panel/testimonials}">
                    Testimonials
                </a>
            </li>
            <li class="<?= activeIF('videos', false, 'active') ?>">
                <a href="{URL:panel/videos}">
                    Videos
                </a>
            </li>

        </ul>
    </li>

    <!-- Client Microsites -->
    <li class="menu">
        <a <?= $show = activeIF(['microsites'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_microsites" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Client Microsites</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_microsites" data-parent="#accordionExample">
            <li class="<?= activeIF('microsites', false, 'active') ?>">
                <a href="{URL:panel/microsites}">
                    Manage Microsites
                </a>
            </li>
        </ul>
    </li>

    <!-- Form Builder -->
    <li class="menu">
        <a <?= $show = activeIF(['forms', 'sections', 'fields'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_forms" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                <span>Form Builder</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_forms" data-parent="#accordionExample">
            <li class="<?= activeIF('forms', false, 'active') ?>">
                <a href="{URL:panel/forms}">
                    Forms
                </a>
            </li>
            <li class="<?= activeIF('sections', false, 'active') ?>">
                <a href="{URL:panel/forms/sections}">
                    Sections
                </a>
            </li>
            <li class="<?= activeIF('fields', false, 'active') ?>">
                <a href="{URL:panel/forms/fields}">
                    Fields
                </a>
            </li>
        </ul>
    </li>

    <!-- Shop -->
    <li class="menu">
        <a <?= $show = activeIF(['shops', 'brands', 'types'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_shop" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                <span>Shop</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_shop" data-parent="#accordionExample">
            <li class="<?= activeIF('shops', false, 'active') ?>">
                <a href="{URL:panel/shops}">
                    Products
                </a>
            </li>
            <li class="<?= activeIF('brands', false, 'active') ?>">
                <a href="{URL:panel/shops/brands}">
                    Brands
                </a>
            </li>
            <li class="<?= activeIF('types', false, 'active') ?>">
                <a href="{URL:panel/shops/types}">
                    Types
                </a>
            </li>
        </ul>
    </li>

    <!-- Talent Vault -->
    <li class="menu">
        <a <?= $show = activeIF(['talents','skills','languages','your_tc','open_profiles','anonymous_profiles','hotlists','shortlists','candidate_alerts','password_protection'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_talents" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                <span>Talent Vault</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_talents" data-parent="#accordionExample">
            <?php /*
            <li class="<?= activeIF('skills', false, 'active') ?>">
                <a href="{URL:panel/talents/skills}">
                    Skills
                </a>
            </li>
            <li class="<?= activeIF('languages', false, 'active') ?>">
                <a href="{URL:panel/talents/languages}">
                    Languages
                </a>
            </li>
            <li class="<?= activeIF('your_tc', false, 'active') ?>">
                <a href="{URL:panel/talents/your_tc}">
                    Your Terms & Conditions
                </a>
            </li>
            */ ?>
            <li class="<?= activeIF('open_profiles', false, 'active') ?>">
                <a href="{URL:panel/talents/open_profiles}">
                    Open Profiles
                </a>
            </li>

            <li class="<?= activeIF('anonymous_profiles', false, 'active') ?>">
                <a href="{URL:panel/talents/anonymous_profiles}">
                    Anonymous Profiles
                </a>
            </li>
            <li class="<?= activeIF('hotlists', false, 'active') ?>">
                <a href="{URL:panel/talents/hotlists}">
                    Hot Lists
                </a>
            </li>

            <li class="<?= activeIF('shortlists', false, 'active') ?>">
                <a href="{URL:panel/talents/shortlists}">
                    Short Lists
                </a>
            </li>

            <li class="<?= activeIF('candidate_alerts', false, 'active') ?>">
                <a href="{URL:panel/talents/candidate_alerts}">
                    Candidate Alerts
                </a>
            </li>

            <li class="<?= activeIF('talents', 'password_protection', 'active') ?>">
                <a href="{URL:panel/talents/password_protection}">
                    Password Protection
                </a>
            </li>
        </ul>
    </li>
<?php /*
    <!-- Learning & Development -->
    <li class="menu">
        <a <?= $show = activeIF(['learning_development', 'categories'], false, 'data-active="true" aria-expanded="false"', (CONTROLLER !== 'panel/blog/categories')) ?>
                href="#tab_lnd" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
                <span>Learning & Development</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_lnd" data-parent="#accordionExample">
            <li class="<?= activeIF('learning_development', false, 'active') ?>">
                <a href="{URL:panel/learning_development}">
                    Resources
                </a>
            </li>
            <li class="<?= activeIF('categories', false, 'active', (CONTROLLER !== 'panel/blog/categories')) ?>">
                <a href="{URL:panel/learning_development/categories}">
                    Categories
                </a>
            </li>
        </ul>
    </li>

    <!-- Gated Content -->
    <li class="menu">
        <a <?= $show = activeIF(['club_blog', 'club_categories'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_club_blog" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>Gated Content</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_club_blog" data-parent="#accordionExample">
            <li class="<?= activeIF('club_blog', false, 'active') ?>">
                <a href="{URL:panel/club_blog}">
                    Articles
                </a>
            </li>
            <li class="<?= activeIF('club_categories', false, 'active') ?>">
                <a href="{URL:panel/club_blog/club_categories}">
                    Categories
                </a>
            </li>
        </ul>
    </li>

    <!-- Salary Survey -->
    <li class="menu">
        <a <?= $show = activeIF(['salary_survey'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_salary_survey" data-toggle="collapse" class="dropdown-toggle">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span>Salary Survey</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_salary_survey" data-parent="#accordionExample">
            <li class="<?= activeIF('salary_survey', false, 'active') ?>">
                <a href="{URL:panel/salary_survey}">
                    Salary Survey
                </a>
            </li>
        </ul>
    </li>
*/ ?>
    <!-- Client Portal -->
    <li class="menu">
        <a <?= $show = activeIF(['candidates_portal', 'customers'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_client_portal" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Client Portal</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_client_portal" data-parent="#accordionExample">
            <li class="<?= activeIF('customers', ['index','add', 'edit'], 'active') ?>">
                <a href="{URL:panel/customers}">
                    Manage Clients
                </a>
            </li>
            <li class="<?= activeIF('candidates_portal', ['index','add', 'edit'], 'active') ?>">
                <a href="{URL:panel/candidates_portal}">
                    Manage Candidates
                </a>
            </li>
        </ul>
    </li>

    <!-- Team Management -->
    <li class="menu">
        <a <?= $show = activeIF(['team'], false, 'data-active="true" aria-expanded="false"') ?>
                href="#tab_team" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Team Management</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_team" data-parent="#accordionExample">
            <li class="<?= activeIF('team', false, 'active') ?>">
                <a href="{URL:panel/team}">
                    Team Manager
                </a>
            </li>
        </ul>
    </li>

    <!-- Analytics & Reporting -->
    <li class="menu">
        <a <?= $show = activeIF(['analytics', 'subscribers', 'panel'], false, 'data-active="true" aria-expanded="false"',
            (CONTROLLER == 'panel' && ACTION == 'logs') || (CONTROLLER == 'panel' && ACTION == 'email_logs') || (CONTROLLER == 'panel' && ACTION == 'user_logs')
            || !(CONTROLLER == 'panel')) ?>
                href="#tab_analytics" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                <span>Analytics & Reporting</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_analytics" data-parent="#accordionExample">
            <?php /*
            <li class="<?= activeIF('analytics', ['index', 'config'], 'active') ?>">
                <a href="{URL:panel/analytics}">
                    Google Analytics Settings
                </a>
            </li>
            */ ?>
            <li class="<?= activeIF('analytics', 'include_code', 'active') ?>">
                <a href="{URL:panel/analytics/include_code}">
                    Include Code
                </a>
            </li>
            <?php /*
            <li class="<?= activeIF('subscribers', false, 'active') ?>">
                <a href="{URL:panel/analytics/subscribers}">
                    Email Subscribers
                </a>
            </li>
            <li class="<?= activeIF('analytics', 'refers', 'active') ?>">
                <a href="{URL:panel/analytics/refers}">
                    Refer a Friend
                </a>
            </li>
            */ ?>
            <li class="<?= activeIF('panel', 'logs', 'active') ?>">
                <a href="{URL:panel/logs}">
                    Logs
                </a>
            </li>
        </ul>
    </li>

    <!-- Settings -->
    <li class="menu">
        <a <?= $show = activeIF(['panel', 'data_generator', 'dashboard_settings', 'sitemap', 'settings', 'dashboard', 'parser'], false, 'data-active="true" aria-expanded="false"',
            !(CONTROLLER == 'panel' && ACTION == 'index') && !(CONTROLLER == 'panel' && ACTION == 'logs') && !(CONTROLLER == 'panel' && ACTION == 'email_logs')  && !(CONTROLLER == 'panel' && ACTION == 'user_logs')) ?>
                href="#tab_settings" data-toggle="collapse" class="dropdown-toggle">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Settings</span>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </a>
        <ul class="collapse submenu list-unstyled <?= $show ? 'show' : '' ?>" id="tab_settings" data-parent="#accordionExample">
            <li class="<?= activeIF('settings', 'index', 'active') ?>">
                <a href="{URL:panel/settings}">
                    General
                </a>
            </li>
            <li class="<?= activeIF('dashboard', 'index', 'active', !(CONTROLLER_SHORT == 'dashboard_settings' && ACTION == 'google')) ?>">
                <a href="{URL:panel/settings/dashboard}">
                    Dashboard
                </a>
            </li>
            <li class="<?= activeIF('settings', 'social_networks', 'active') ?>">
                <a href="{URL:panel/settings/social_networks}">
                    Social Links
                </a>
            </li>
            <li class="<?= activeIF('settings', 'google', 'active') ?>">
                <a href="{URL:panel/settings/google}">
                    Google Settings
                </a>
            </li>
            <li class="<?= activeIF('settings', 'cookie', 'active') ?>">
                <a href="{URL:panel/settings/cookie}">
                    Cookies Popup
                </a>
            </li>
            <li class="<?= activeIF('sitemap', false, 'active') ?>">
                <a href="{URL:panel/settings/sitemap}">
                    Sitemap
                </a>
            </li>
            <li class="<?= activeIF('settings', 'robots', 'active') ?>">
                <a href="{URL:panel/settings/robots}">
                    Robots.txt
                </a>
            </li>

            <li class="<?= activeIF('panel', 'modules', 'active') ?>">
                <a href="{URL:panel/modules}">
                    Modules
                </a>
            </li>
            <li class="<?= activeIF('parser',  false,'active') ?>">
                <a href="{URL:panel/parser}">
                    Excel Parser
                </a>
            </li>
            <?php /*
            <li class="<?= activeIF('data_generator', false, 'active') ?>">
                <a href="{URL:panel/data_generator}">
                    Data generator
                </a>
            </li>
            */ ?>
        </ul>
    </li>


    <!-- Report Issue -->
    <?php if (Request::getParam('tracker') == 'yes') { ?>
        <li class="menu">
            <a class="dropdown-toggle report"
                    onclick="load('issue_manager/create_task', 'project=<?= Request::getParam('tracker_api') ?>', 'url=' + window.location.href);">
                <div>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bug" class="svg-inline--fa fa-bug fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M511.988 288.9c-.478 17.43-15.217 31.1-32.653 31.1H424v16c0 21.864-4.882 42.584-13.6 61.145l60.228 60.228c12.496 12.497 12.496 32.758 0 45.255-12.498 12.497-32.759 12.496-45.256 0l-54.736-54.736C345.886 467.965 314.351 480 280 480V236c0-6.627-5.373-12-12-12h-24c-6.627 0-12 5.373-12 12v244c-34.351 0-65.886-12.035-90.636-32.108l-54.736 54.736c-12.498 12.497-32.759 12.496-45.256 0-12.496-12.497-12.496-32.758 0-45.255l60.228-60.228C92.882 378.584 88 357.864 88 336v-16H32.666C15.23 320 .491 306.33.013 288.9-.484 270.816 14.028 256 32 256h56v-58.745l-46.628-46.628c-12.496-12.497-12.496-32.758 0-45.255 12.498-12.497 32.758-12.497 45.256 0L141.255 160h229.489l54.627-54.627c12.498-12.497 32.758-12.497 45.256 0 12.496 12.497 12.496 32.758 0 45.255L424 197.255V256h56c17.972 0 32.484 14.816 31.988 32.9zM257 0c-61.856 0-112 50.144-112 112h224C369 50.144 318.856 0 257 0z"></path></svg>
                    <span>Report Issue</span>
                </div>
            </a>
        </li>
    <?php } ?>

    <li style="margin-bottom: 100px;"></li>
</ul>
<!-- <div class="shadow-bottom"></div> -->
