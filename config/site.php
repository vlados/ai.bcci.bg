<?php

/*
|--------------------------------------------------------------------------
| Site configuration — AI Council (BCCI)
|--------------------------------------------------------------------------
|
| Single source of truth for the public site: the locales it ships in, the
| top navigation, and the editable-field schema for every content page.
|
| The same `page_fields` schema drives two things:
|   - the Filament page editor (which fields to render, per page)
|   - the database seeder (which keys to fill with the design's copy)
|
| Field definition keys:
|   type         => text | textarea | richtext | url | list
|   label        => admin label (Bulgarian)
|   translatable => store as {bg, en}; otherwise a single shared value
|   fields       => (list only) sub-field schema for each repeater row
|
*/

return [

    // Locales the site is published in. First one is the default (no URL prefix).
    'locales' => [
        'bg' => 'БГ',
        'en' => 'EN',
    ],

    'default_locale' => 'bg',

    // Organization identity — powers JSON-LD (schema.org), Open Graph and the
    // llms.txt file. This is the machine-readable definition of who we are,
    // which is what GEO / answer-engines actually consume.
    'org' => [
        // Names are taken verbatim from Art. 2 of the Правилник (rules of
        // procedure), approved by the BCCI Executive Council in Protocol
        // No 13/7-2025 of 27 May 2025. Do not paraphrase them: the Council is
        // "Съвет ЗА изкуствен интелект", not "по".
        'name' => [
            'bg' => 'Съвет за изкуствен интелект към Българската търговско-промишлена палата',
            'en' => 'Artificial Intelligence Council at the Bulgarian Chamber of Commerce and Industry',
        ],
        'short' => ['bg' => 'Съвет ИИ към БТПП', 'en' => 'AI Council at BCCI'],
        // BTPP/BCCI is a self-governing chamber under private law, not a state
        // body, so its advisory council must NOT claim GovernmentOrganization.
        'legal_type' => 'Organization',
        'parent' => [
            'name' => ['bg' => 'Българска търговско-промишлена палата', 'en' => 'Bulgarian Chamber of Commerce and Industry'],
            'url' => 'https://www.bcci.bg',
        ],
        'same_as' => [
            'https://www.bcci.bg',
        ],
        // Established by decision of the BCCI Executive Council, Protocol
        // No 13/7-2025, 13th session, 27 May 2025.
        'founding_date' => '2025-05-27',
        // Used by the PostalAddress node; the street line comes from the
        // editable contact block so markup and visible address stay in step.
        'locality' => 'София',
        'postal_code' => '1058',
        'area_served' => ['bg' => 'България', 'en' => 'Bulgaria'],
        'knows_about' => [
            ['bg' => 'Изкуствен интелект', 'en' => 'Artificial intelligence'],
            ['bg' => 'Технологична политика', 'en' => 'Technology policy'],
            ['bg' => 'Дигитална трансформация', 'en' => 'Digital transformation'],
            ['bg' => 'Акт за изкуствения интелект (AI Act)', 'en' => 'EU AI Act'],
            ['bg' => 'Образование в областта на AI', 'en' => 'AI education'],
        ],
    ],

    // SEO defaults.
    'seo' => [
        'og_image' => 'assets/logo.png', // relative to public/
        'twitter' => null,               // @handle, if one exists later

        // The one hostname allowed to be indexed. Any other host serving this
        // app (dev box, preview, bare IP, www variant) is forced to noindex, so
        // a staging copy can never compete with production for its own content.
        'production_host' => env('SEO_PRODUCTION_HOST', 'ai.bcci.bg'),
    ],

    // Top navigation — page key => translated label. Order matters.
    'nav' => [
        'home' => ['bg' => 'Начало',      'en' => 'Home'],
        'about' => ['bg' => 'За нас',       'en' => 'About us'],
        'education' => ['bg' => 'Образование',  'en' => 'Education'],
        'positions' => ['bg' => 'Становища',    'en' => 'Positions'],
        'survey' => ['bg' => 'Проучване',    'en' => 'Survey'],
        'partners' => ['bg' => 'Партньори',    'en' => 'Partners'],
        'news' => ['bg' => 'Новини',        'en' => 'News'],
        'contacts' => ['bg' => 'Контакти',     'en' => 'Contact'],
    ],

    // Editable content fields, grouped by page key. `global` is the shared
    // header/footer/contact block used across every page.
    'page_fields' => [

        'global' => [
            'seo_description' => ['type' => 'textarea', 'label' => 'SEO — описание на сайта (fallback)', 'translatable' => true],
            'topbar_tagline' => ['type' => 'text',     'label' => 'Лента отгоре — текст', 'translatable' => true],
            'contact_email' => ['type' => 'text',     'label' => 'Имейл'],
            'contact_phone' => ['type' => 'text',     'label' => 'Телефон'],
            'contact_address' => ['type' => 'textarea', 'label' => 'Адрес', 'translatable' => true],
            'contact_address_note' => ['type' => 'text',     'label' => 'Адрес — бележка', 'translatable' => true],
            'newsletter_title' => ['type' => 'text',     'label' => 'Бюлетин — заглавие', 'translatable' => true],
            'newsletter_text' => ['type' => 'textarea', 'label' => 'Бюлетин — текст', 'translatable' => true],
            'newsletter_url' => ['type' => 'url',      'label' => 'Бюлетин — линк за абониране (БТПП)'],
            'footer_address' => ['type' => 'textarea', 'label' => 'Футър — адрес', 'translatable' => true],
            'copyright' => ['type' => 'text',     'label' => 'Авторско право', 'translatable' => true],
        ],

        'home' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'hero_image' => ['type' => 'url',      'label' => 'Хиро — изображение (URL)'],
            'cta_primary' => ['type' => 'text',     'label' => 'Бутон 1', 'translatable' => true],
            'cta_secondary' => ['type' => 'text',     'label' => 'Бутон 2', 'translatable' => true],
            'pillars_title' => ['type' => 'text',     'label' => 'Направления — заглавие на панела', 'translatable' => true],
            'pillars' => ['type' => 'list',     'label' => 'Три направления', 'fields' => [
                'num' => ['type' => 'text',     'label' => '№'],
                'title' => ['type' => 'text',     'label' => 'Заглавие', 'translatable' => true],
                'text' => ['type' => 'textarea', 'label' => 'Текст', 'translatable' => true],
            ]],
            'process_title' => ['type' => 'text',     'label' => 'Как работи Съветът — заглавие', 'translatable' => true],
            'process' => ['type' => 'list',     'label' => 'Как работи Съветът (стъпки)', 'fields' => [
                'num' => ['type' => 'text',     'label' => '№'],
                'title' => ['type' => 'text',     'label' => 'Заглавие', 'translatable' => true],
                'text' => ['type' => 'textarea', 'label' => 'Текст', 'translatable' => true],
            ]],
            'intro_title' => ['type' => 'text',     'label' => 'Секция „заедно“ — заглавие', 'translatable' => true],
            'intro_body' => ['type' => 'richtext', 'label' => 'Секция „заедно“ — текст', 'translatable' => true],
            'news_title' => ['type' => 'text',     'label' => 'Новини — заглавие', 'translatable' => true],
            'quote_eyebrow' => ['type' => 'text',     'label' => 'Цитат — надзаглавие', 'translatable' => true],
            'quote_text' => ['type' => 'textarea', 'label' => 'Цитат — текст', 'translatable' => true],
        ],

        'about' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'goal_title' => ['type' => 'text',     'label' => 'Цел — заглавие', 'translatable' => true],
            'goal_body' => ['type' => 'richtext', 'label' => 'Цел — текст', 'translatable' => true],
            'pillars_title' => ['type' => 'text',     'label' => 'Направления — заглавие', 'translatable' => true],
            'pillars' => ['type' => 'list',     'label' => 'Три основни направления', 'fields' => [
                'num' => ['type' => 'text',     'label' => '№'],
                'title' => ['type' => 'text',     'label' => 'Заглавие', 'translatable' => true],
                'text' => ['type' => 'textarea', 'label' => 'Текст', 'translatable' => true],
            ]],
            'team_title' => ['type' => 'text',     'label' => 'Екип — заглавие', 'translatable' => true],
            'team_subtitle' => ['type' => 'text',     'label' => 'Екип — подзаглавие', 'translatable' => true],
            'hub_title' => ['type' => 'text',     'label' => 'Хъб — заглавие', 'translatable' => true],
            'hub_body' => ['type' => 'textarea', 'label' => 'Хъб — текст', 'translatable' => true],
        ],

        'education' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'body_title' => ['type' => 'text',     'label' => 'Текст — заглавие', 'translatable' => true],
            'body' => ['type' => 'richtext', 'label' => 'Основен текст', 'translatable' => true],
            'plans_title' => ['type' => 'text',     'label' => 'Планове — заглавие', 'translatable' => true],
            'plans' => ['type' => 'list',     'label' => 'Средносрочни планове', 'fields' => [
                'num' => ['type' => 'text',     'label' => '№'],
                'title' => ['type' => 'text',     'label' => 'Заглавие', 'translatable' => true],
                'text' => ['type' => 'textarea', 'label' => 'Текст', 'translatable' => true],
            ]],
            'cta_text' => ['type' => 'text', 'label' => 'CTA — текст', 'translatable' => true],
            'cta_button' => ['type' => 'text', 'label' => 'CTA — бутон', 'translatable' => true],
        ],

        'positions' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'body' => ['type' => 'richtext', 'label' => 'Основен текст', 'translatable' => true],
            'list_title' => ['type' => 'text',     'label' => 'Списък — заглавие', 'translatable' => true],
        ],

        'survey' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'box_eyebrow' => ['type' => 'text',     'label' => 'Панел — надзаглавие', 'translatable' => true],
            'box_title' => ['type' => 'text',     'label' => 'Панел — заглавие', 'translatable' => true],
            'box_text' => ['type' => 'textarea', 'label' => 'Панел — текст', 'translatable' => true],
            'box_button' => ['type' => 'text',     'label' => 'Панел — бутон', 'translatable' => true],
            'box_url' => ['type' => 'url',      'label' => 'Панел — линк към проучването'],
            'results_title' => ['type' => 'text',     'label' => 'Резултати — заглавие', 'translatable' => true],
            'results' => ['type' => 'list',     'label' => 'Резултати — за кого', 'fields' => [
                'label' => ['type' => 'text',     'label' => 'Аудитория', 'translatable' => true],
                'text' => ['type' => 'text',     'label' => 'Описание', 'translatable' => true],
            ]],
            'footer_note' => ['type' => 'text', 'label' => 'Долна лента — текст', 'translatable' => true],
        ],

        'partners' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Въведение', 'translatable' => true],
            'join_title' => ['type' => 'text',     'label' => 'Присъединяване — заглавие', 'translatable' => true],
            'join_text' => ['type' => 'text',     'label' => 'Присъединяване — текст', 'translatable' => true],
            'join_button' => ['type' => 'text',     'label' => 'Присъединяване — бутон', 'translatable' => true],
        ],

        'news' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
        ],

        'contacts' => [
            'hero_eyebrow' => ['type' => 'text',     'label' => 'Хиро — надзаглавие', 'translatable' => true],
            'hero_title' => ['type' => 'textarea', 'label' => 'Хиро — заглавие', 'translatable' => true],
            'hero_intro' => ['type' => 'textarea', 'label' => 'Хиро — текст', 'translatable' => true],
            'form_title' => ['type' => 'text',     'label' => 'Форма — заглавие', 'translatable' => true],
            'card_title' => ['type' => 'text',     'label' => 'Данни за контакт — заглавие', 'translatable' => true],
        ],
    ],
];
