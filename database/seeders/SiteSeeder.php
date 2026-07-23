<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiteSeeder extends Seeder
{
    /** Helper: a translatable value. */
    protected function t(string $bg, string $en): array
    {
        return ['bg' => $bg, 'en' => $en];
    }

    public function run(): void
    {
        $this->seedPages();
        $this->seedTeam();
        $this->seedPartners();
        $this->seedPositions();
        $this->seedNews();
        $this->seedAdmin();
    }

    protected function seedPages(): void
    {
        $t = fn (string $bg, string $en) => $this->t($bg, $en);

        $pages = [
            'global' => [
                'seo_description' => $t(
                    'Съветът за изкуствен интелект към БТПП работи за повишаване на конкурентоспособността на българския бизнес и за позиционирането на България сред лидерите в областта на изкуствения интелект.',
                    'The AI Council at the Bulgarian Chamber of Commerce and Industry works to strengthen the competitiveness of Bulgarian business and to position Bulgaria among the leaders in artificial intelligence.'
                ),
                'topbar_tagline' => $t('Консултативен орган към Българската търговско-промишлена палата', 'An advisory body to the Bulgarian Chamber of Commerce and Industry'),
                'contact_email' => 'ai@bcci.bg',
                'contact_phone' => '+359 2 8117 400',
                'contact_address' => $t('ул. „Искър“ 9, 1058 София', '9 Iskar St, 1058 Sofia'),
                'contact_address_note' => $t('или чрез секретариата на Българската търговско-промишлена палата', 'or via the secretariat of the Bulgarian Chamber of Commerce and Industry'),
                'newsletter_title' => $t('Бюлетин ИНФОБИЗНЕС', 'INFOBUSINESS newsletter'),
                'newsletter_text' => $t('Абонирайте се за ежедневния бюлетин на БТПП — последни новини, становища и събития.', 'Subscribe to BCCI’s daily bulletin — latest news, positions and events.'),
                'newsletter_url' => 'https://www.bcci.bg',
                'footer_address' => $t("ул. „Искър“ 9, 1058 София\nчрез секретариата на БТПП", "9 Iskar St, 1058 Sofia\nvia the BCCI secretariat"),
                'copyright' => $t('© 2026 Съвет за изкуствен интелект към БТПП', '© 2026 AI Council at BCCI'),
            ],

            'home' => [
                'hero_eyebrow' => $t('СЪВЕТ ПО ИЗКУСТВЕН ИНТЕЛЕКТ КЪМ БТПП', 'AI COUNCIL AT BCCI'),
                'hero_title' => $t('Изкуственият интелект отдавна не е бъдещето. Той е настоящето.', 'Artificial intelligence is no longer the future. It is the present.'),
                'hero_intro' => $t('Работим за повишаване на конкурентоспособността на българския бизнес и за позиционирането на България сред лидерите в една от най-перспективните области на иновациите.', 'We work to strengthen the competitiveness of Bulgarian business and to position Bulgaria among the leaders in one of today’s most promising fields of innovation.'),
                'hero_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1400&q=70',
                'cta_primary' => $t('Участвайте в проучването', 'Take part in the survey'),
                'cta_secondary' => $t('За Съвета', 'About the Council'),
                'pillars_title' => $t('ТРИ НАПРАВЛЕНИЯ', 'THREE PILLARS'),
                'pillars' => [
                    ['num' => '01', 'title' => $t('Конкурентоспособност на бизнеса', 'Business competitiveness'), 'text' => $t('Консултации, експертиза и подкрепа за успешното внедряване на AI.', 'Consulting, expertise and support for successful AI adoption.')],
                    ['num' => '02', 'title' => $t('Образование и човешки капацитет', 'Education and human capacity'), 'text' => $t('Партньорства с университети, обучения и менторски програми.', 'University partnerships, trainings and mentorship programmes.')],
                    ['num' => '03', 'title' => $t('Технологична политика', 'Technology policy'), 'text' => $t('Становища по регулаторни инициативи на национално и европейско ниво.', 'Positions on regulatory initiatives at national and European level.')],
                ],
                'process_title' => $t('Как работи Съветът', 'How the Council works'),
                'process' => [
                    ['num' => '01 →', 'title' => $t('Питате', 'You ask'), 'text' => $t('Изпращате ни въпрос или конкретен казус от вашия бизнес.', 'You send us a question or a specific case from your business.')],
                    ['num' => '02 →', 'title' => $t('Свързваме', 'We connect'), 'text' => $t('Насочваме ви към подходящия експерт от мрежата на Съвета.', 'We point you to the right expert from the Council’s network.')],
                    ['num' => '03 →', 'title' => $t('Решаваме', 'We solve'), 'text' => $t('Заедно търсим решение, което повишава ефективността ви.', 'Together we find a solution that raises your efficiency.')],
                    ['num' => '04', 'title' => $t('Споделяме', 'We share'), 'text' => $t('Натрупаното знание става достъпно за всички — чрез новини и становища.', 'The accumulated knowledge becomes available to everyone — through news and positions.')],
                ],
                'intro_title' => $t('Хора и технологии, заедно', 'People and technology, together'),
                'intro_body' => $t(
                    '<p>Не става въпрос за замяна на хората от машини, а за повишаване ефективността на всеки член на екипа — от мениджърите до всяко ниво в организацията. Ще бъдем в тясна комуникация с българските компании, ще отговаряме на въпросите им и ще търсим решения, повишаващи тяхната ефективност.</p><p>Желанието ни е да обединим гласовете на бизнеса, образованието, науката и държавните институции, за да позиционираме България като един от лидерите в света на бъдещето.</p>',
                    '<p>This is not about machines replacing people — it is about making every member of the team more effective, from managers to every level of the organisation. We stay in close contact with Bulgarian companies, answer their questions and look for solutions that raise their efficiency.</p><p>Our ambition is to unite the voices of business, education, science and public institutions, positioning Bulgaria among the leaders of the world of tomorrow.</p>'
                ),
                'news_title' => $t('Последни новини', 'Latest news'),
                'quote_eyebrow' => $t('ИЗ СТАНОВИЩЕ НА СЪВЕТА', 'FROM A COUNCIL POSITION'),
                'quote_text' => $t('Нашата позиция е базирана на балансиран подход — правила, които защитават обществения интерес, без да спират иновациите.', 'Our position is based on a balanced approach — rules that protect the public interest without stifling innovation.'),
                'meta_title' => $t('Съвет за изкуствен интелект — БТПП', 'AI Council — Bulgarian Chamber of Commerce and Industry'),
            ],

            'about' => [
                'hero_eyebrow' => $t('ЗА НАС', 'ABOUT US'),
                'hero_title' => $t('Консултативен орган към Българската търговско-промишлена палата', 'An advisory body to the Bulgarian Chamber of Commerce and Industry'),
                'hero_intro' => $t('Съветът за изкуствен интелект е създаден от експерти с убеждението, че AI технологиите ще играят ключова роля в дигиталната трансформация на бизнеса.', 'The AI Council was created by experts convinced that AI technologies will play a key role in the digital transformation of business.'),
                'goal_title' => $t('Нашата цел', 'Our goal'),
                'goal_body' => $t(
                    '<p>Екипът на Съвета включва представители на технологичния сектор, образователните среди, науката, успешни юристи и предприемачи.</p><p>Целта ни е да изградим среда, в която знанието за изкуствения интелект е достъпно за всеки български гражданин и се използва по най-добрия начин от всяко българско предприятие — независимо от неговия размер и икономически сектор. Помагаме на бизнеса да разбере и внедри изкуствения интелект в своята дейност и инвестираме в хората, които ще изграждат бъдещето.</p>',
                    '<p>The Council’s team includes representatives of the technology sector, education, science, accomplished lawyers and entrepreneurs.</p><p>Our goal is to build an environment where knowledge about artificial intelligence is accessible to every Bulgarian citizen and used to the fullest by every Bulgarian enterprise — regardless of its size or economic sector. We help business understand and adopt AI, and we invest in the people who will build the future.</p>'
                ),
                'pillars_title' => $t('Три основни направления', 'Three core pillars'),
                'pillars' => [
                    ['num' => '01', 'title' => $t('Конкурентоспособност на бизнеса', 'Business competitiveness'), 'text' => $t('Консултации, свързване с подходящи експерти и подкрепа за успешното внедряване на изкуствения интелект. Автоматизацията, анализът на данни и интелигентните системи не са привилегия на корпорациите — те са инструмент за всеки, който иска да расте.', 'Consulting, connecting with the right experts and support for successful AI adoption. Automation, data analysis and intelligent systems are not a privilege of large corporations — they are a tool for anyone who wants to grow.')],
                    ['num' => '02', 'title' => $t('Образование и човешки капацитет', 'Education and human capacity'), 'text' => $t('Подкрепяме инициативи, които подготвят следващото поколение специалисти и помагат на настоящите да се адаптират към бързо променящия се технологичен пейзаж.', 'We support initiatives that prepare the next generation of specialists and help current ones adapt to the rapidly changing technological landscape.')],
                    ['num' => '03', 'title' => $t('Технологична политика, основана на реалността', 'Reality-based technology policy'), 'text' => $t('Създаваме становища по законодателни и регулаторни инициативи на национално и европейско ниво, за да гарантираме, че гласът на бизнеса е чут там, където се вземат решенията.', 'We produce positions on legislative and regulatory initiatives at national and European level to ensure the voice of business is heard where decisions are made.')],
                ],
                'team_title' => $t('Екипът на Съвета', 'The Council’s team'),
                'team_subtitle' => $t('Технологичен сектор, образование, наука, право и предприемачество.', 'Technology, education, science, law and entrepreneurship.'),
                'hub_title' => $t('Информационен хъб за AI знание', 'An information hub for AI knowledge'),
                'hub_body' => $t('Днес дори големи технологични компании са застрашени от загуба на позиции — нови бизнес модели, базирани на AI, могат да променят техния сегмент за броени месеци. В тези несигурни времена скоростта, с която достъпваме информация за новостите, определя дали ще спечелим, или ще изгубим. Затова Съветът се позиционира като информационен хъб, който концентрира знанието в тази перспективна технологична област и го прави максимално достъпно за всички български граждани и бизнес организации.', 'Today even large technology companies risk losing ground — new AI-based business models can reshape their segment within months. In these uncertain times, the speed at which we access information about what is new determines whether we win or lose. That is why the Council positions itself as an information hub that concentrates knowledge in this promising field and makes it as accessible as possible to all Bulgarian citizens and businesses.'),
            ],

            'education' => [
                'hero_eyebrow' => $t('ОБРАЗОВАНИЕ', 'EDUCATION'),
                'hero_title' => $t('Подготовка за новата реалност', 'Preparing for the new reality'),
                'hero_intro' => $t('Технологиите се развиват по-бързо от учебните програми — много от професиите, за които се обучават днешните младежи, може и да не съществуват до завършването им.', 'Technology evolves faster than curricula — many of the professions today’s young people are training for may not even exist by the time they graduate.'),
                'body_title' => $t('Знанието е ценност сама по себе си', 'Knowledge is a value in itself'),
                'body' => $t(
                    '<p>За да подпомогне по-широкото навлизане на обучението по AI в образованието, Съветът изгражда партньорства с водещи български университети, академии и други образователни организации. Целта ни е по-добра подготовка на българските ученици и студенти, нови възможности за учене през целия живот и по-конкурентни кадри за българския бизнес.</p><p>Във време, когато цялото знание на света изглежда достъпно на един клик разстояние, съществува риск от погрешното внушение, че процесът на учене вече не е необходим. Ние сме убедени, че знанието има критично значение за развитието на личността и на обществото. Ученето дори на привидно ненужни неща създава нови невронни връзки, развива асоциативното мислене и често води до открития в съвсем различни области.</p><p>Традиционните образователни методи остават значими, но към тях трябва да прибавим по-бързи и гъвкави формати, чрез които и младите, и по-възрастните хора да усвояват нови знания и умения, оставайки конкурентни в непрекъснато променящата се технологична среда.</p>',
                    '<p>To support the wider adoption of AI training in education, the Council is building partnerships with leading Bulgarian universities, academies and other educational organisations. Our aim is better preparation for Bulgarian pupils and students, new lifelong-learning opportunities and more competitive talent for Bulgarian business.</p><p>At a time when all the world’s knowledge seems a click away, there is a risk of the mistaken belief that learning is no longer necessary. We are convinced that knowledge is critical to the development of the individual and of society. Learning even seemingly unnecessary things creates new neural connections, develops associative thinking and often leads to discoveries in entirely different fields.</p><p>Traditional educational methods remain significant, but we must add faster, more flexible formats through which both younger and older people can acquire new knowledge and skills, staying competitive in a constantly changing technological environment.</p>'
                ),
                'plans_title' => $t('Средносрочни планове', 'Medium-term plans'),
                'plans' => [
                    ['num' => '01', 'title' => $t('Съвместни курсове', 'Joint courses'), 'text' => $t('Разработени заедно с университети и академии.', 'Developed together with universities and academies.')],
                    ['num' => '02', 'title' => $t('Практически обучения', 'Hands-on training'), 'text' => $t('Реални казуси и работа с актуални AI инструменти.', 'Real cases and work with current AI tools.')],
                    ['num' => '03', 'title' => $t('Менторски програми', 'Mentorship programmes'), 'text' => $t('Опитни специалисти подкрепят следващото поколение.', 'Experienced specialists support the next generation.')],
                    ['num' => '04', 'title' => $t('Сертификационна програма', 'Certification programme'), 'text' => $t('За български студенти.', 'For Bulgarian students.')],
                ],
                'cta_text' => $t('Повече информация за нашите образователни инициативи — очаквайте скоро.', 'More information about our educational initiatives — coming soon.'),
                'cta_button' => $t('Пишете ни', 'Write to us'),
            ],

            'positions' => [
                'hero_eyebrow' => $t('СТАНОВИЩА', 'POSITIONS'),
                'hero_title' => $t('Гласът на бизнеса в технологичната политика', 'The voice of business in technology policy'),
                'hero_intro' => $t('Регулирането на технологиите — и на изкуствения интелект в частност — е сред най-сложните политически предизвикателства на нашето време. Законодателите все по-често изостават от реалното развитие на технологиите и пазара.', 'Regulating technology — and artificial intelligence in particular — is among the most complex policy challenges of our time. Legislators increasingly lag behind the real pace of technology and the market.'),
                'body' => $t(
                    '<p>Съветът концентрира експертен потенциал в областта на правото, технологиите и бизнеса за изготвяне на становища по ключови въпроси — от прилагането на европейския Акт за изкуствения интелект и регулациите за защита на данните, до националните стратегии за дигитализация и информационна сигурност.</p><p>Нашите позиции са изградени на базата на реалния опит на бизнеса, а не на сухата теория, и са насочени към регулатори, законодатели и институции. Във време, когато Европа видимо изостава от САЩ и Китай, ние отстояваме балансиран подход — правила, които защитават обществения интерес, без да спират иновациите.</p>',
                    '<p>The Council concentrates expert potential in law, technology and business to prepare positions on key questions — from the implementation of the European AI Act and data-protection rules to national strategies for digitalisation and information security.</p><p>Our positions are built on the real experience of business, not dry theory, and are addressed to regulators, legislators and institutions. At a time when Europe visibly lags behind the US and China, we stand for a balanced approach — rules that protect the public interest without stifling innovation.</p>'
                ),
                'list_title' => $t('Публикувани становища', 'Published positions'),
            ],

            'survey' => [
                'hero_eyebrow' => $t('НАЦИОНАЛНО ПРОУЧВАНЕ', 'NATIONAL SURVEY'),
                'hero_title' => $t('Какво мисли българският бизнес за изкуствения интелект?', 'What does Bulgarian business think about artificial intelligence?'),
                'hero_intro' => $t('А какви са нагласите на студентите? Съветът за изкуствен интелект към БТПП създаде мащабно национално проучване, което за първи път ще даде цялостна картина на нагласите, очакванията и предизвикателствата пред българските компании в контекста на навлизането на AI технологиите.', 'And what about students? The AI Council at BCCI has launched a large-scale national survey that will, for the first time, give a complete picture of the attitudes, expectations and challenges facing Bulgarian companies as AI technologies take hold.'),
                'box_eyebrow' => $t('ВКЛЮЧЕТЕ СЕ', 'GET INVOLVED'),
                'box_title' => $t('Вашият опит е част от картината', 'Your experience is part of the picture'),
                'box_text' => $t('Проучването е отворено за компании от всички сектори и региони, както и за студенти.', 'The survey is open to companies from all sectors and regions, as well as to students.'),
                'box_button' => $t('Участвайте в проучването', 'Take part in the survey'),
                'box_url' => 'https://prouchvane.bg/ai-business-2026',
                'results_title' => $t('РЕЗУЛТАТИТЕ — ОТВОРЕНИ ЗА ВСИЧКИ', 'THE RESULTS — OPEN TO ALL'),
                'results' => [
                    ['label' => $t('За бизнеса', 'For business'), 'text' => $t('ориентир къде се намира секторът', 'a benchmark of where the sector stands')],
                    ['label' => $t('За изследователите', 'For researchers'), 'text' => $t('отворени данни за анализ', 'open data for analysis')],
                    ['label' => $t('За политиците', 'For policymakers'), 'text' => $t('основа за информирани решения', 'a basis for informed decisions')],
                    ['label' => $t('За обществото', 'For society'), 'text' => $t('цялостна картина на нагласите', 'a complete picture of attitudes')],
                ],
                'footer_note' => $t('Следете ни за повече информация — резултатите ще бъдат публикувани тук.', 'Follow us for updates — the results will be published here.'),
            ],

            'partners' => [
                'hero_eyebrow' => $t('ПАРТНЬОРИ', 'PARTNERS'),
                'hero_title' => $t('Мрежа от съмишленици', 'A network of like-minded partners'),
                'hero_intro' => $t('Съветът за изкуствен интелект е част от Българската търговско-промишлена палата и работи с организации, които споделят нашата визия за технологично грамотна и конкурентоспособна България.', 'The AI Council is part of the Bulgarian Chamber of Commerce and Industry and works with organisations that share our vision of a technologically literate and competitive Bulgaria.'),
                'intro' => $t('Нашите партньори включват водещи бизнес организации, университети, технологични компании и международни участници в AI екосистемата.', 'Our partners include leading business organisations, universities, technology companies and international players in the AI ecosystem.'),
                'join_title' => $t('Искате да се присъедините към мрежата ни?', 'Want to join our network?'),
                'join_text' => $t('Ако вашата организация споделя нашата визия — свържете се с нас.', 'If your organisation shares our vision — get in touch.'),
                'join_button' => $t('Свържете се с нас', 'Get in touch'),
            ],

            'news' => [
                'hero_eyebrow' => $t('НОВИНИ', 'NEWS'),
                'hero_title' => $t('Последно от Съвета', 'Latest from the Council'),
                'hero_intro' => $t('Новини, събития и инициативи на Съвета за изкуствен интелект.', 'News, events and initiatives of the AI Council.'),
            ],

            'contacts' => [
                'hero_eyebrow' => $t('КОНТАКТИ', 'CONTACT'),
                'hero_title' => $t('Свържете се с нас', 'Get in touch'),
                'hero_intro' => $t('Имате въпрос, идея или искате да се включите в работата на Съвета за изкуствен интелект?', 'Have a question or an idea, or want to get involved in the work of the AI Council?'),
                'form_title' => $t('Пишете ни', 'Write to us'),
                'card_title' => $t('ДАННИ ЗА КОНТАКТ', 'CONTACT DETAILS'),
            ],
        ];

        foreach ($pages as $key => $content) {
            Page::updateOrCreate(['key' => $key], ['content' => $content]);
        }
    }

    /**
     * Team and partners are deliberately NOT seeded.
     *
     * They used to be filled with "Име Фамилия" x6 and "Партньор 1..8", which
     * is exactly what the SEO audit flags as a launch blocker: placeholder
     * people and partner relationships make an institution look unreal and
     * cannot be verified by anyone. The About and Partners pages already hide
     * their sections when these tables are empty, so an empty site is honest
     * where a populated fake one is not.
     *
     * Real members and partners are entered through the admin panel.
     */
    protected function seedTeam(): void
    {
        // Intentionally empty — see the note above.
    }

    protected function seedPartners(): void
    {
        // Intentionally empty — see the note above.
    }

    protected function seedPositions(): void
    {
        $positions = [
            ['date' => '2026-07-15', 'scope' => 'eu', 'title' => $this->t('Становище относно прилагането на Акта за изкуствения интелект (AI Act) в България', 'Position on the implementation of the AI Act in Bulgaria')],
            ['date' => '2026-05-28', 'scope' => 'national', 'title' => $this->t('Позиция по проекта на Национална стратегия за изкуствен интелект 2030', 'Position on the draft National AI Strategy 2030')],
            ['date' => '2026-04-09', 'scope' => 'eu', 'title' => $this->t('Коментари по насоките на Европейската комисия за AI модели с общо предназначение', 'Comments on the European Commission guidelines for general-purpose AI models')],
            ['date' => '2026-02-20', 'scope' => 'national', 'title' => $this->t('Становище по изменения в Закона за електронното управление', 'Position on amendments to the E-Government Act')],
        ];

        foreach ($positions as $p) {
            Position::updateOrCreate(
                ['title->bg' => $p['title']['bg']],
                ['title' => $p['title'], 'scope' => $p['scope'], 'document_date' => $p['date'], 'is_published' => true],
            );
        }
    }

    protected function seedNews(): void
    {
        $news = [
            [
                'slug' => 'ai-and-digitalisation-in-bulgaria-2026',
                'date' => '2026-07-23',
                'title' => $this->t(
                    'Внедряване на изкуствения интелект и цифровизацията в България: анализ 2026',
                    'Adoption of artificial intelligence and digitalisation in Bulgaria: a 2026 analysis'
                ),
                'excerpt' => $this->t(
                    'Сравнителен анализ спрямо ЕС и препоръки за бизнеса, държавата и университетите. По ключовите показатели България остава значително под средното за Съюза, а основният проблем не е липсата на технологични активи, а ограниченото им разпространение.',
                    'A comparative analysis against the EU and recommendations for business, the state and universities. On the key indicators Bulgaria remains well below the EU average - and the core problem is not an absence of technological assets but their limited diffusion.'
                ),
                'meta_description' => $this->t(
                    'Къде стои България по цифрови умения, ИИ в предприятията и електронни услуги спрямо ЕС, Дания и Финландия - и осем приоритетни мерки за 2026-2030 г.',
                    'Where Bulgaria stands on digital skills, enterprise AI and e-government against the EU, Denmark and Finland - and eight priority measures for 2026-2030.'
                ),
                'body' => $this->t(
                    '<p><strong>България не изостава във всяко отношение.</strong> Страната разполага с добра свързаност, работещи национални цифрови системи, силно научно ядро чрез INSAIT, предстоящата фабрика за изкуствен интелект BRAIN++ и продуктови компании с международен пазар. Основният проблем не е липсата на технологични активи, а ограниченото им разпространение сред масовия бизнес, публичната администрация извън отделни водещи системи, повечето университети и регионите. (Източници: ЕК: Digital Decade 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC: BRAIN++</a>; Световна банка)</p>

<p>По ключови показатели страната остава значително под средното за ЕС. През 2025 г. 38% от хората на възраст 16–74 години имат поне базови цифрови умения при 60% средно за ЕС. Изкуствен интелект се използва от 8,55% от предприятията при 19,95% за ЕС. Онлайн публични услуги са използвали 36% от гражданите при 72% за ЕС, а електронна идентификация — 12% при 52%. Разходите за научноизследователска и развойна дейност през 2024 г. са 0,77% от БВП при 2,24% за ЕС. (Източници: Eurostat: цифровизация 2026; <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">Eurostat: ИИ в предприятията</a>; Eurostat: електронно управление; Eurostat: НИРД)</p>

<p>Тези разлики показват системен дефицит на мащабиране. България създава отделни „острови на високи постижения“, но все още не превръща достатъчно бързо успехите им в по-висока производителност, по-добри публични услуги и по-силен технологичен трансфер. Стартъп активността и капиталът са концентрирани в София, а връзките между университети, публични данни и индустрия остават неравномерни. (Източници: Световна банка; ЕК: Digital Decade 2026)</p>

<p>За периода 2026–2030 г. приоритетът трябва да се измести от единични проекти към широко внедряване. Това означава подкрепа за цифровизация и изкуствен интелект в малките и средните предприятия, достъп до качествени данни и тестови среди, по-прагматичен трансфер от университетите към бизнеса и ясна рамка за доверие и съответствие с европейските правила.</p>

<p>Съветът за изкуствен интелект към БТПП може да бъде практическа координационна платформа между бизнеса, университетите и публичния сектор. Най-голяма добавена стойност ще има работа по конкретни секторни пилоти, общи индикатори за резултат, обучение на управленски екипи и формулиране на предложения за по-предвидима регулаторна и институционална среда.</p>

<h2>Къде стои България днес</h2>

<p>Европейската комисия не публикува DESI като самостоятелен общ индекс след 2022 г. От 2023 г. наблюдението е включено в рамката „Цифрово десетилетие“, затова актуалното сравнение трябва да се прави по отделни показатели, а не чрез един общ резултат. В последното общо класиране DESI за 2022 г. България е 26-а от 27 държави членки. Докладът за България за 2026 г. продължава да отчита слабости в цифровите умения, внедряването на технологии в МСП, иновациите и киберсигурността, наред със силната свързаност. (Източници: ЕК: DESI; DESI 2022 — България; ЕК: Digital Decade 2026)</p>

<h3>Сравнителен профил по ключови показатели</h3>

<p>Дания и Финландия са използвани като ориентир, защото са малки отворени икономики с висока цифрова зрялост, силни резултати при изкуствения интелект, широко използване на електронни услуги и високи нива на умения. Данните по-долу са последните налични към юли 2026 г.; референтната година е посочена за всеки показател.</p>

<div class="table-wrap">
<table>
<caption>Сравнителен профил по ключови показатели, България спрямо ЕС, Дания и Финландия</caption>
<thead>
<tr>
<th scope="col">Показател</th>
<th scope="col">България</th>
<th scope="col">ЕС</th>
<th scope="col">Дания</th>
<th scope="col">Финландия</th>
</tr>
</thead>
<tbody>
<tr><th scope="row">DESI 2022 — общо класиране</th><td>26/27</td><td>н.п.</td><td>2/27</td><td>1/27</td></tr>
<tr><th scope="row">Поне базови цифрови умения, 2025</th><td>38%</td><td>60%</td><td>81%</td><td>81%</td></tr>
<tr><th scope="row">Предприятия, използващи ИИ, 2025</th><td>8,55%</td><td>19,95%</td><td>42,03%</td><td>37,82%</td></tr>
<tr><th scope="row">МСП с поне базова цифрова интензивност, 2025</th><td>38%</td><td>71%</td><td>92%</td><td>94%</td></tr>
<tr><th scope="row">Използване на онлайн публични услуги, 2025</th><td>36%</td><td>72%</td><td>98%</td><td>96%</td></tr>
<tr><th scope="row">Използване на електронна идентификация, 2025</th><td>12%</td><td>52%</td><td>99%</td><td>96%</td></tr>
<tr><th scope="row">Разходи за НИРД, 2024</th><td>0,77% от БВП</td><td>2,24% от БВП</td><td>3,01% от БВП</td><td>3,22% от БВП</td></tr>
<tr><th scope="row">Government AI Readiness, 2024</th><td>60,64</td><td>н.п.</td><td>74,71</td><td>76,48</td></tr>
</tbody>
</table>
</div>

<p><em>Бележка: „н.п.“ означава „неприложимо“. DESI е общ индекс до 2022 г. За Government AI Readiness няма официална средна стойност за ЕС, а методологията на индекса е различна от тази на DESI. (Източници: ЕК: DESI; Eurostat: цифровизация 2026; <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table?lang=en">Eurostat: ИИ</a>; Eurostat: електронно управление; Eurostat: НИРД; Oxford Insights 2024)</em></p>

<p>Съществен е не отделният показател, а общият модел. Разликата с Дания и Финландия е системна: при използването на изкуствен интелект от предприятията тя е около четири до пет пъти, при електронните публични услуги — близо три пъти, а при електронната идентификация — около осем пъти. Това отразява разлики не само в технологиите, а и в дизайна на услугите, управленския капацитет, уменията и доверието.</p>

<h3>По-детайлен прочит на показателите</h3>

<p>Базовите цифрови умения се подобряват, но твърде бавно. България се повишава от около 31% през 2021 г. до 38% през 2025 г., докато ЕС се движи от 54% до 60%. Разликата не се затваря достатъчно бързо. При широко навлизане на генеративния изкуствен интелект това е пряка пречка пред внедряването в МСП и администрацията, защото липсата на базови умения увеличава риска от грешки, ниска възвръщаемост и зависимост от външни доставчици. (Източници: DESI 2022 — България; Eurostat: цифровизация 2026)</p>

<p>При МСП дефицитът е още по-практичен. През 2025 г. само 38% от българските МСП достигат поне базово ниво на <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_e_dii/default/table">цифрова интензивност</a>, в сравнение със 71% в ЕС, 92% в Дания и 94% във Финландия. Фирма без надеждни основни системи — управление на ресурси, клиенти, документи, данни и киберсигурност — трудно може да превърне изкуствения интелект в продуктивен инструмент. В такъв контекст той често остава демонстрация или отделен абонамент, а не промяна на процеса. (Източник: Eurostat: цифровизация 2026)</p>

<p>Разликата при научните изследвания и иновациите е структурна. През 2024 г. България отделя 0,77% от БВП за НИРД при 2,24% за ЕС, 3,01% за Дания и 3,22% за Финландия. Допълнителен сигнал е размерът на държавните бюджетни средства за НИРД: 38,3 евро на човек в България при 284,7 евро средно за ЕС през 2024 г. Това ограничава модерната инфраструктура, привличането и задържането на таланти и броя на приложните проекти с индустрията. (Източници: Eurostat: НИРД; Eurostat: бюджет за НИРД на човек)</p>

<p>При специалистите по информационни и комуникационни технологии картината е смесена. Делът им в ЕС достига 5,0% от заетостта през 2025 г., а във Финландия е около 7,8%. България има конкурентно предимство в по-високия дял на жените сред ИКТ специалистите — 25% през 2025 г. Това не решава недостига на кадри, но дава по-добра база за разширяване на таланта чрез обучение, преквалификация и политики за задържане. (Източник: Eurostat: ИКТ специалисти)</p>

<p>Стартъп екосистемата е по-развита, отколкото показват общите иновационни показатели, но е силно концентрирана. Последната оценка на Световната банка посочва около 348 активни стартъпа в София — приблизително 87% от националния общ брой — и около 500 млн. евро активи, управлявани от местни фондове за рисков капитал към края на 2024 г. В същото време рисковите инвестиции през 2024 г. спадат до около 50–55 млн. евро, а под 10% достигат до кръгове B и следващи етапи. България може да създава силни компании, но все още не осигурява надежден капиталов мост към международно разрастване. (Източник: Световна банка)</p>

<p>В публичния сектор трябва да се различават наличието на цифрови системи и реалното им използване. България е електронизирала ключови процеси, включително обществените поръчки и значителна част от здравната информация, но през 2025 г. само 36% от гражданите използват сайт или приложение на публичен орган, а 12% използват електронна идентификация за достъп до услуги. Следователно следващият етап не е просто изграждане на още системи, а по-добра интеграция, по-малко административни стъпки и по-ясна полза за потребителя. (Източници: Eurostat: електронно управление; ЕК: Digital Decade 2026)</p>

<p>Внедряването на изкуствен интелект в бизнеса расте, но изходната база остава ниска. Делът на предприятията, които използват поне една технология на изкуствения интелект, се повишава от около 6,5% през 2024 г. до 8,55% през 2025 г. За същия период ЕС достига 19,95%, Дания — 42,03%, а Финландия — 37,82%. България не изостава само с една година; разликата е в мащаба и в способността на фирмите да превръщат данните и автоматизацията в ежедневни процеси. (Източник: <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">Eurostat: ИИ в предприятията</a>)</p>

<h2>Политики и водещи проекти</h2>

<p>България разполага с широка стратегическа рамка. Концепцията за развитие на изкуствения интелект до 2030 г. определя основната визия; актуализираният документ „Цифрова трансформация на България 2024–2030“ свързва националните цели с европейското „Цифрово десетилетие“; Иновационната стратегия за интелигентна специализация 2021–2027 насочва приоритетите за научни изследвания и иновации; Националната стратегия за МСП е удължена до 2030 г. Проблемът е не липсата на документи, а слабата координация между тях, ограничената приоритизация и неравномерното изпълнение. (Източници: Концепция за ИИ до 2030 г.; Цифрова трансформация 2024–2030; ИСИС 2021–2027; Стратегия за МСП 2021–2030)</p>

<p>Националната пътна карта по „Цифрово десетилетие“ съдържа 60 мерки с общ бюджет 2,19 млрд. евро. Европейската комисия отбелязва, че 48% от мерките изтичат до края на 2026 г.; това представлява 597 млн. евро публично финансиране, или 27% от публичния бюджет на пътната карта. Рискът е да възникне пропуск между приключващите мерки и следващия програмен цикъл. Необходими са междинен преглед, публичен статус на ключовите мерки и предварително пренасочване на ресурс към дейности с доказан ефект. (Източник: ЕК: Digital Decade 2026)</p>

<p>Регулаторната рамка също навлиза в практическа фаза. Забранените практики и изискванията за грамотност по изкуствен интелект се прилагат от февруари 2025 г., правилата за управление и моделите с общо предназначение — от август 2025 г., а основната част от <a href="https://eur-lex.europa.eu/legal-content/BG/TXT/HTML/?uri=OJ:L_202401689">Акта за изкуствения интелект</a> се прилага от 2 август 2026 г. За част от високорисковите системи има по-късни срокове. Българските организации се нуждаят от ясни национални указания, образци за договори и обществени поръчки, секторни тестови среди и достъпна помощ за съответствие, без ненужно спиране на иновациите. (Източник: ЕК: Акт за изкуствения интелект)</p>

<h3>Флагмански проекти, които променят картината</h3>

<p><a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">BRAIN++</a> е най-значимият нов инфраструктурен актив. Българската фабрика за изкуствен интелект ще обедини оптимизирания за ИИ суперкомпютър Discoverer++ и център за услуги към държавни институции, образователни организации, изследователи и компании. Проектът е на стойност около 90 млн. евро. При добро управление BRAIN++ може да се превърне в национален ускорител за прототипи, български езикови модели, роботика, здравеопазване, земеделие и инструменти за надежден изкуствен интелект. Икономическият ефект ще зависи от прозрачни правила за достъп, измерими секторни резултати и активно участие на МСП. (Източници: EuroHPC: BRAIN++; МИР: проект за 90 млн. евро)</p>

<p>INSAIT вече е източник на международно видими научни резултати, талант и потенциал за нови компании. През 2026 г. институтът отчита 17 приети статии в основната програма на CVPR, първо място в Източна Европа и място сред четирите водещи институции в ЕС по този показател. Националната задача е този научен капацитет да бъде свързан системно с индустриални докторантури, съвместни лаборатории, доказване на концепции и създаване на отделени университетски компании. (Източници: INSAIT: CVPR 2026; Световна банка)</p>

<p>Discoverer+ е третият ключов актив. Надграждането му със системи за изкуствен интелект разширява възможностите за специализирани услуги към бизнеса, науката и публичния сектор. Суперкомпютърът и BRAIN++ ще имат значим икономически ефект само ако бъдат свързани с университети, европейски цифрови иновационни хъбове, браншови клъстери и конкретни производствени и обществени приложения. (Източник: МИР: Discoverer+)</p>

<h2>Казуси от бизнеса, публичния сектор и университетите</h2>

<h3>Бизнес</h3>

<p>Shelly Group показва как цифровизацията може да се превърне в собствен продукт, а не само във вътрешен процес. Компанията развива решения за интернет на нещата, интелигентни сгради, автоматизация и енергиен мениджмънт, свързани чрез мобилни и облачни приложения. Според оценката на Световната банка през 2025 г. пазарната стойност на компанията преминава 1 млрд. щатски долара. Урокът е, че България може да създава глобално конкурентни компании, когато инженерната база се комбинира със собствен продукт и ясно решен клиентски проблем. (Източници: Shelly: инвеститорски доклад 2025; Световна банка)</p>

<p>Payhawk представя друга траектория — финтех платформа, която вгражда изкуствен интелект във финансовите процеси. През 2025 г. компанията развива „AI Office of the CFO“ и ИИ агенти за покупки, пътувания, разходи и плащания. Световната банка посочва, че Payhawk достига статут на еднорог през 2022 г. Значението на случая е в наличието на български корпоративен ИИ продукт с международен пазар, а не само в оценката на компанията. (Източници: Payhawk: Fall 2025; Световна банка)</p>

<p>Postbank е пример за поетапно внедряване в традиционен сектор. Банката използва EVA като дигитален асистент на български и английски език и прилага ИИ инструменти в подбора на служители. Подходът е показателен: организациите често постигат най-бърз резултат, когато започнат от обслужване на клиенти, човешки ресурси и работни процеси със знания, преди да преминат към по-рискови основни системи. (Източник: Postbank: EVA)</p>

<h3>Публичен сектор</h3>

<p>ЦАИС ЕОП е зрял пример за процесна цифровизация в национален мащаб. През 2025 г. в системата са публикувани 111 415 обявления и са подадени 111 355 оферти. Същевременно делът на процедурите с една оферта е 39% при цел 23% по Националния план за възстановяване и устойчивост. Това показва важна граница: цифровизирането на процеса повишава проследимостта и ефективността, но не решава автоматично проблемите с конкуренцията и качеството на пазара. (Източник: АОП: годишен доклад 2025)</p>

<p>Националната здравноинформационна система е вторият силен публичен казус. Към ноември 2025 г. през нея са обработени над 615 млн. електронни здравни записа, системата обслужва близо 3 млн. заявки дневно, над 21 000 лекари използват активно приложението eRx, а активните граждански потребители на eZdrave са около 240 000. Това е реална държавна платформа с мащаб, която може да подкрепи следваща вълна от надежден изкуствен интелект — при ясни правила за качество на данните, достъп, сигурност и човешки контрол. (Източник: Информационно обслужване: НЗИС)</p>

<p>Електронното правосъдие и Единната информационна система на съдилищата са третият пример. По данни от ноември 2025 г. за пет години са заведени и разгледани над 2,4 млн. електронни дела, издадени са над 8,7 млн. електронни акта, системата се използва от над 12 300 потребители и е свързана с повече от 15 външни системи. Най-подходящите първи приложения на изкуствения интелект са преобразуване на реч в текст, подпомагане на документооборота, търсене и аналитична помощ — не непрозрачно автоматизиране на съдебни решения. (Източник: Информационно обслужване: електронно правосъдие)</p>

<h3>Университети и научен трансфер</h3>

<p>В България най-силният академичен капацитет по изкуствен интелект е концентриран в ограничен брой структури, докато водещите европейски системи разполагат с по-широки мрежи от университети, индустриални партньорства и устойчиво финансиране за трансфер. Ниската национална интензивност на НИРД и ограничените механизми за комерсиализация означават, че научните успехи трудно се разпространяват извън отделни центрове. (Източници: Световна банка; Eurostat: НИРД)</p>

<p>Софийският университет концентрира най-видимото академично ядро чрез INSAIT и GATE. GATE е създаден като автономна структура за големи данни и приложения за интелигентно общество, а INSAIT носи международно видими публикации и ранни резултати по комерсиализация. Този модел показва как университетът може да бъде едновременно образователна институция, изследователска инфраструктура и основа за създаване на нови компании. (Източници: GATE; INSAIT: CVPR 2026; Световна банка)</p>

<p>Техническият университет — София изгражда инженерната и приложната база. Международната конференция ICARAI 2025, интензивните програми по изкуствен интелект и проектите в роботика, комуникации и обработка на сигнали са важни за кадрите, необходими на производството, вградените системи и индустриалната автоматизация. Този капацитет трябва да се свърже с повече съвместни лаборатории и проекти, възложени от предприятия. (Източник: ТУ — София: ICARAI 2025)</p>

<p>Медицинският университет — Плевен показва възможностите за изкуствен интелект в медицинското образование и клиничната практика. През 2025 г. университетът въвежда ИИ приложение в библиотеката и развива дейности, свързани с роботизирана хирургия и телемедицина. Здравеопазването е една от най-перспективните области за надежден изкуствен интелект в България, но изисква съчетаване на клинична експертиза, качествени данни, валидиране и етични правила. (Източник: МУ — Плевен)</p>

<h2>Бариери и възможности</h2>

<h3>Система от взаимно свързани бариери</h3>

<p>Първата бариера е институционалната и регулаторната предвидимост. Основният проблем не е формалната липса на правила, а неравномерното им прилагане, бавните търговски спорове, трудната несъстоятелност, слабата защита на интелектуалната собственост и неясните правила за собственост върху резултати от публично финансирани изследвания. При изкуствения интелект правната неяснота увеличава разходите за сделка и обезкуражава инвеститори, университети и разрастващи се компании. (Източник: Световна банка)</p>

<p>Втората бариера са уменията. Недостигът не се ограничава до програмисти. Необходими са управленци, инженери, специалисти по данни, приложни изследователи, преподаватели и държавни служители, които могат да превърнат технологията в работещ процес. Демографският спад, изтичането на кадри и слабото съответствие между образование и търсене превръщат политиката за изкуствен интелект в политика за общ капацитет на икономиката. (Източници: Световна банка; Eurostat: цифровизация 2026)</p>

<p>Третата бариера е финансирането след ранния етап. България разполага с фондове и програми за начало на компании, но под 10% от рисковите инвестиции достигат до кръгове B и следващи етапи. Необходим е капиталов и пазарен мост между доказан продукт и международно разрастване. В противен случай най-силните компании преместват ключови функции навън именно когато започват да създават най-голяма стойност. (Източник: Световна банка)</p>

<p>Четвъртата бариера е достъпът до данни и слабото им оползотворяване. България има значими системи като НЗИС, RegiX и ЦАИС ЕОП. RegiX свързва десетки регистри и осигурява основа за служебен обмен, но това не означава автоматично, че данните са добре документирани, стандартизирани и подходящи за анализ и машинно обучение. Необходими са общи правила за управление на данни, програмни интерфейси, анонимизация, синтетични данни и защитени тестови среди. (Източници: Държавна агенция „Електронно управление“: RegiX; Информационно обслужване: НЗИС; АОП: годишен доклад 2025)</p>

<p>Петата бариера е регионалната концентрация. Растежът, стартъп активността и голяма част от специализирания талант са съсредоточени в София и в по-малка степен в Пловдив. Извън тези центрове инфраструктурата, управленският капацитет и връзките между университети и фирми са неравномерни. България има силни цифрови центрове, но все още няма достатъчно плътна национална мрежа за разпространение на технологии. (Източник: Световна банка)</p>

<p>Шестата бариера е доверието. Ниското използване на електронна идентификация и публични електронни услуги показва, че технологичното предлагане не се превръща автоматично в гражданска и бизнес употреба. При изкуствения интелект доверието изисква ясна отговорност, човешки контрол, проследимост, обяснимост и възможност за оспорване. Без тези елементи публичното внедряване ще създава съпротива вместо стойност. (Източници: Eurostat: електронно управление; ЕК: Акт за изкуствения интелект)</p>

<h3>Възможностите са реални</h3>

<p>България разполага с рядка комбинация за държава от своя размер: висококласно изследователско ядро, европейска инфраструктура чрез BRAIN++, работещи национални цифрови системи с голям обем данни, продуктови компании с международен пазар и сравнително висок дял на жените в ИКТ професиите. Това прави следващата фаза на развитие постижима, но само при координирано използване на тези активи. (Източници: INSAIT: CVPR 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC: BRAIN++</a>; Eurostat: ИКТ специалисти; Световна банка)</p>

<p>Подходящият ориентир не е механично копиране на големи икономики, а моделът на малки, координирани европейски държави. От Дания и Финландия могат да се извлекат три практически урока: силно цифрово ядро на публичния сектор, постоянна връзка между наука и предприятия и последователен фокус върху масовото внедряване в МСП. Разликата се създава от изпълнението в мащаб, не от броя на стратегическите документи. (Източници: Eurostat: цифровизация 2026; Eurostat: електронно управление)</p>

<h2>Какво да се направи сега</h2>

<p>Препоръките са подредени по стратегическа важност. За целите на планирането е използван ориентировъчен мащаб на необходимия публичен и смесен ресурс: нисък — до около 10 млн. лв.; среден — около 10–50 млн. лв.; висок — над 50 млн. лв. Същественото е инвестициите да изграждат механизми за разпространение на капацитет, а не поредица от несвързани пилотни проекти.</p>

<h3>Приоритетни препоръки и времева рамка</h3>

<p><strong>P1. Национална програма за внедряване на изкуствен интелект в МСП</strong> — чрез ваучери, секторни консултации и европейски цифрови иновационни хъбове. Всеки проект да включва данни, процес и киберсигурност, а не само лиценз за инструмент.<br>
<em>Срок:</em> старт 6–12 месеца, мащаб 36 месеца. <em>Водещи участници:</em> МИР, МЕУ, БТПП, EDIH, браншови организации, общини. <em>Ресурс:</em> среден-висок.</p>

<p><strong>P2. Национално координационно звено за управление и обществени поръчки на изкуствен интелект</strong> — образци за поръчки и договори, класификация на риска, карти на моделите, човешки контрол, одитни следи и готовност за Акта за изкуствения интелект.<br>
<em>Срок:</em> 6–12 месеца. <em>Водещи участници:</em> МЕУ, МС, КЗЛД, АОП, секторни регулатори, БТПП. <em>Ресурс:</em> нисък-среден.</p>

<p><strong>P3. Секторни пространства за данни и тестови среди</strong> за здравеопазване, правосъдие, енергетика и индустрия, включително стандарти за анонимизация, синтетични данни и контрол на достъпа.<br>
<em>Срок:</em> 12–36 месеца. <em>Водещи участници:</em> МЕУ, МЗ, МП, МЕ, НСИ, университети, София Тех Парк. <em>Ресурс:</em> висок.</p>

<p><strong>P4. Единни правила за университетска интелектуална собственост</strong> и национален фонд за доказване на концепции и трансфер от университети и БАН към предприятия.<br>
<em>Срок:</em> 12–24 месеца. <em>Водещи участници:</em> МОН, МИР, Фонд на фондовете, университети, БАН. <em>Ресурс:</em> среден.</p>

<p><strong>P5. Инструмент за финансиране на разрастването в кръгове A/B</strong> и използване на публичния сектор като първи клиент на български компании за дълбоки технологии и изкуствен интелект.<br>
<em>Срок:</em> 12–36 месеца. <em>Водещи участници:</em> МИР, Фонд на фондовете, ББР, EIF/EIB, АОП. <em>Ресурс:</em> висок.</p>

<p><strong>P6. Мащабна програма за грамотност по изкуствен интелект</strong> и практическо надграждане на уменията на управители, служители в МСП, държавни служители, учители и университетски преподаватели.<br>
<em>Срок:</em> старт до 6 месеца, постоянна. <em>Водещи участници:</em> МОН, МТСП, ИПА, БТПП, университети, работодателски организации. <em>Ресурс:</em> среден-висок.</p>

<p><strong>P7. Регулаторна тестова среда и консултативен център за съответствие</strong> за високорисков изкуствен интелект, електронно управление, здравни и финансови технологии.<br>
<em>Срок:</em> 6–18 месеца. <em>Водещи участници:</em> МЕУ, секторни регулатори, КЗЛД, БНБ, КФН, БТПП. <em>Ресурс:</em> нисък-среден.</p>

<p><strong>P8. Пет национални мисии с измерим резултат:</strong> изкуствен интелект в здравеопазването, правосъдието, общинските услуги, енергийната ефективност и индустриалната автоматизация.<br>
<em>Срок:</em> 12–48 месеца. <em>Водещи участници:</em> МС, ресорни министерства, общини, университети, бизнес. <em>Ресурс:</em> висок.</p>

<p>Първият пакет за незабавно действие следва да обхване P1, P2 и P4. P1 адресира основния дефицит — слабото разпространение на технологии към масовия бизнес. P2 създава предвидима рамка, без която публичният сектор ще се колебае между прекомерна предпазливост и несвързани пилоти. P4 превръща научните резултати в продукти, лицензи и нови компании. Трите мерки трябва да се изпълняват паралелно; иначе BRAIN++, INSAIT и отделните фирмени успехи няма да доведат до широка икономическа промяна. (Източници: ЕК: Digital Decade 2026; Световна банка; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC: BRAIN++</a>)</p>

<h3>Роли на ключовите участници</h3>

<p>Нито един участник не може самостоятелно да осъществи трансформацията. Държавата определя правилата, управлява публичните данни и формира значителна част от търсенето. Университетите и научните организации осигуряват талант, изследвания и инфраструктура. Бизнесът носи отговорността за внедряването, производителността и международния пазар. БТПП и секторните организации могат да свържат тези три системи и да превърнат общите цели в секторни програми.</p>

<p>За Съвета за изкуствен интелект към БТПП най-полезната роля е да координира разпространението на изкуствения интелект по сектори, умения и правила за внедряване. Това изисква постоянни работни групи, регулярни срещи между фирми, университети и администрация и общ набор от ключови показатели за изпълнение:</p>

<ul>
<li>Дял на участващите МСП, които внедряват решение и го използват устойчиво след 12 месеца.</li>
<li>Измерена промяна в производителност, време за обработка, качество или разход на процеса.</li>
<li>Брой достъпни набори от данни и тестови среди с ясни правила за използване.</li>
<li>Брой университетски доказателства на концепция, лицензи и нови компании.</li>
<li>Брой обучени управители и служители и дял на организациите с вътрешни правила за отговорно използване на изкуствен интелект.</li>
</ul>

<h3>Незабавни следващи стъпки за Съвета</h3>

<ol>
<li>В рамките на три месеца да бъдат създадени работни групи за МСП, умения, университетски трансфер и регулаторни въпроси, с конкретни ръководители и график.</li>
<li>Да се проведат структурирани интервюта с представители на водещи МСП, университети, общини и публични институции, за да се подберат проблеми с ясна икономическа или обществена стойност.</li>
<li>До шестия месец да започнат поне два секторни пилота с предварително определени показатели, бюджет, собственик на процеса и план за мащабиране.</li>
<li>До края на 2026 г. да бъде публикуван кратък табло-доклад за напредъка, резултатите, научените уроци и необходимите промени в регулации, финансиране и обществени поръчки.</li>
</ol>

<h2>Заключение</h2>

<p>България има реална възможност да се превърне в регионален център за приложен изкуствен интелект в Югоизточна Европа. Налични са силни активи: INSAIT, BRAIN++, разширената суперкомпютърна инфраструктура, национални системи в здравеопазването, обществените поръчки и правосъдието, както и продуктови компании с международен успех. Слабостта е, че тези активи все още не се превръщат достатъчно бързо в масово внедряване в МСП, регионите и публичните услуги. (Източници: INSAIT: CVPR 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC: BRAIN++</a>; ЕК: Digital Decade 2026; Световна банка)</p>

<p>Затова целта не трябва да бъде България да разполага с няколко впечатляващи проекта. Целта е изкуственият интелект и цифровизацията да повишават производителността на хиляди предприятия, качеството на десетки публични услуги и икономическата стойност на университетските изследвания. Именно в свързването на бизнеса, държавата и университетите Съветът за изкуствен интелект към БТПП може да постигне най-голямо и измеримо въздействие.</p>',
                    '<p><strong>Bulgaria is not behind on everything.</strong> The country has good connectivity, national digital systems that work, a strong research core in INSAIT, the forthcoming BRAIN++ AI factory and product companies selling on international markets. The core problem is not an absence of technological assets but their limited diffusion — into mainstream business, into public administration beyond a few flagship systems, into most universities and into the regions. (Sources: EC, Digital Decade 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC, BRAIN++</a>; World Bank)</p>

<p>On the key indicators the country remains well below the EU average. In 2025, 38% of people aged 16–74 had at least basic digital skills, against 60% across the EU. Artificial intelligence was used by 8.55% of enterprises, against 19.95% in the EU. Online public services were used by 36% of citizens against 72% in the EU, and electronic identification by 12% against 52%. Research and development spending in 2024 stood at 0.77% of GDP against 2.24% for the EU. (Sources: Eurostat, digitalisation 2026; <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">Eurostat, AI in enterprises</a>; Eurostat, e-government; Eurostat, R&amp;D)</p>

<p>These gaps point to a systemic deficit in scaling. Bulgaria produces isolated "islands of excellence" but does not yet convert them quickly enough into higher productivity, better public services and stronger technology transfer. Start-up activity and capital are concentrated in Sofia, and the links between universities, public data and industry remain uneven. (Sources: World Bank; EC, Digital Decade 2026)</p>

<p>For 2026–2030 the priority has to shift from individual projects to broad adoption. That means support for digitalisation and AI in small and medium-sized enterprises, access to quality data and testing environments, more pragmatic transfer from universities to business, and a clear framework for trust and compliance with European rules.</p>

<p>The AI Council at the Bulgarian Chamber of Commerce and Industry can serve as a practical coordination platform between business, universities and the public sector. The greatest added value lies in concrete sectoral pilots, shared outcome indicators, training for management teams, and proposals for a more predictable regulatory and institutional environment.</p>

<h2>Where Bulgaria stands today</h2>

<p>The European Commission has not published DESI as a standalone composite index since 2022. From 2023 the monitoring has been folded into the Digital Decade framework, so any current comparison has to be made indicator by indicator rather than through a single overall score. In the last full DESI ranking, for 2022, Bulgaria placed 26th of 27 member states. The 2026 country report continues to record weaknesses in digital skills, technology adoption in SMEs, innovation and cybersecurity, alongside strong connectivity. (Sources: EC, DESI; DESI 2022 — Bulgaria; EC, Digital Decade 2026)</p>

<h3>A comparative profile on the key indicators</h3>

<p>Denmark and Finland are used as reference points because they are small open economies with high digital maturity, strong AI results, widespread use of electronic services and high skill levels. The figures below are the latest available as of July 2026; the reference year is given for each indicator.</p>

<div class="table-wrap">
<table>
<caption>A comparative profile on the key indicators: Bulgaria against the EU, Denmark and Finland</caption>
<thead>
<tr>
<th scope="col">Indicator</th>
<th scope="col">Bulgaria</th>
<th scope="col">EU</th>
<th scope="col">Denmark</th>
<th scope="col">Finland</th>
</tr>
</thead>
<tbody>
<tr><th scope="row">DESI 2022 — overall ranking</th><td>26/27</td><td>n/a</td><td>2/27</td><td>1/27</td></tr>
<tr><th scope="row">At least basic digital skills, 2025</th><td>38%</td><td>60%</td><td>81%</td><td>81%</td></tr>
<tr><th scope="row">Enterprises using AI, 2025</th><td>8.55%</td><td>19.95%</td><td>42.03%</td><td>37.82%</td></tr>
<tr><th scope="row">SMEs with at least basic digital intensity, 2025</th><td>38%</td><td>71%</td><td>92%</td><td>94%</td></tr>
<tr><th scope="row">Use of online public services, 2025</th><td>36%</td><td>72%</td><td>98%</td><td>96%</td></tr>
<tr><th scope="row">Use of electronic identification, 2025</th><td>12%</td><td>52%</td><td>99%</td><td>96%</td></tr>
<tr><th scope="row">R&amp;D expenditure, 2024</th><td>0.77% of GDP</td><td>2.24% of GDP</td><td>3.01% of GDP</td><td>3.22% of GDP</td></tr>
<tr><th scope="row">Government AI Readiness, 2024</th><td>60.64</td><td>n/a</td><td>74.71</td><td>76.48</td></tr>
</tbody>
</table>
</div>

<p><em>Note: "n/a" means not applicable. DESI is a composite index only up to 2022. For Government AI Readiness there is no official EU average, and the index methodology differs from that of DESI. (Sources: EC, DESI; Eurostat, digitalisation 2026; <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table?lang=en">Eurostat, AI</a>; Eurostat, e-government; Eurostat, R&amp;D; Oxford Insights 2024)</em></p>

<p>What matters is not any single indicator but the overall pattern. The distance to Denmark and Finland is systemic: roughly four to five times on enterprise use of AI, close to three times on online public services and about eight times on electronic identification. That reflects differences not only in technology but in service design, management capacity, skills and trust.</p>

<h3>A closer reading of the indicators</h3>

<p>Basic digital skills are improving, but too slowly. Bulgaria rises from about 31% in 2021 to 38% in 2025, while the EU moves from 54% to 60%. The gap is not closing fast enough. With generative AI spreading widely, this is a direct obstacle to adoption in SMEs and in the administration, because missing basic skills raise the risk of errors, low returns and dependence on outside suppliers. (Sources: DESI 2022 — Bulgaria; Eurostat, digitalisation 2026)</p>

<p>In SMEs the deficit is more practical still. In 2025 only 38% of Bulgarian SMEs reached at least a basic level of <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_e_dii/default/table">digital intensity</a>, against 71% in the EU, 92% in Denmark and 94% in Finland. A firm without dependable core systems — for resources, customers, documents, data and cybersecurity — will struggle to turn AI into a productive tool. In that context AI often stays a demonstration or a standalone subscription rather than a change to the process. (Source: Eurostat, digitalisation 2026)</p>

<p>The gap in research and innovation is structural. In 2024 Bulgaria spent 0.77% of GDP on R&amp;D against 2.24% for the EU, 3.01% for Denmark and 3.22% for Finland. A further signal is the size of government budget allocations for R&amp;D: €38.3 per person in Bulgaria against an EU average of €284.7 in 2024. That constrains modern infrastructure, the ability to attract and retain talent, and the number of applied projects with industry. (Sources: Eurostat, R&amp;D; Eurostat, government budget allocations for R&amp;D per capita)</p>

<p>On information and communication technology specialists the picture is mixed. Their share of employment in the EU reached 5.0% in 2025, and in Finland it is around 7.8%. Bulgaria has a competitive advantage in the higher share of women among ICT specialists — 25% in 2025. That does not solve the shortage of staff, but it offers a better base for widening the talent pool through training, reskilling and retention policies. (Source: Eurostat, ICT specialists)</p>

<p>The start-up ecosystem is more developed than the general innovation indicators suggest, but it is heavily concentrated. The latest World Bank assessment records about 348 active start-ups in Sofia — roughly 87% of the national total — and about €500 million in assets managed by local venture capital funds at the end of 2024. At the same time venture investment in 2024 fell to about €50–55 million, and under 10% reaches Series B and later stages. Bulgaria can create strong companies, but it does not yet provide a reliable capital bridge to international scale-up. (Source: World Bank)</p>

<p>In the public sector, the existence of digital systems and their actual use need to be kept apart. Bulgaria has digitised key processes, including public procurement and a substantial part of health information, yet in 2025 only 36% of citizens used a public authority\'s website or application and 12% used electronic identification to access services. The next stage is therefore not simply building more systems but better integration, fewer administrative steps and a clearer benefit to the user. (Sources: Eurostat, e-government; EC, Digital Decade 2026)</p>

<p>AI adoption in business is growing, but from a low base. The share of enterprises using at least one AI technology rose from about 6.5% in 2024 to 8.55% in 2025. Over the same period the EU reached 19.95%, Denmark 42.03% and Finland 37.82%. Bulgaria is not one year behind; the difference is one of scale, and of how far firms can turn data and automation into everyday processes. (Source: <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">Eurostat, AI in enterprises</a>)</p>

<h2>Policies and flagship projects</h2>

<p>Bulgaria has a broad strategic framework. The Concept for the Development of Artificial Intelligence to 2030 sets the overall vision; the updated "Digital Transformation of Bulgaria 2024–2030" links national goals to the European Digital Decade; the Innovation Strategy for Smart Specialisation 2021–2027 directs research and innovation priorities; the National SME Strategy has been extended to 2030. The problem is not a shortage of documents but weak coordination between them, limited prioritisation and uneven implementation. (Sources: AI Concept to 2030; Digital Transformation 2024–2030; ISSS 2021–2027; SME Strategy 2021–2030)</p>

<p>The national Digital Decade roadmap contains 60 measures with a total budget of €2.19 billion. The European Commission notes that 48% of the measures expire by the end of 2026; that amounts to €597 million of public funding, or 27% of the roadmap\'s public budget. The risk is a gap between the measures now ending and the next programming cycle. What is needed is a mid-term review, a public status for the key measures, and reallocation of resources in advance towards activities with a proven effect. (Source: EC, Digital Decade 2026)</p>

<p>The regulatory framework is also entering a practical phase. The prohibited practices and the AI literacy requirements have applied since February 2025, the governance rules and those for general-purpose models since August 2025, and the main body of the <a href="https://eur-lex.europa.eu/legal-content/EN/TXT/HTML/?uri=OJ:L_202401689">AI Act</a> applies from 2 August 2026. Later deadlines apply to some high-risk systems. Bulgarian organisations need clear national guidance, model contracts and procurement templates, sectoral testing environments and accessible compliance support — without unnecessarily stalling innovation. (Source: EC, AI Act)</p>

<h3>The flagship projects that change the picture</h3>

<p><a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">BRAIN++</a> is the most significant new infrastructure asset. Bulgaria\'s AI factory will bring together the AI-optimised Discoverer++ supercomputer and a service centre for state institutions, educational organisations, researchers and companies. The project is worth around €90 million. Managed well, BRAIN++ can become a national accelerator for prototypes, Bulgarian language models, robotics, healthcare, agriculture and tools for trustworthy AI. Its economic effect will depend on transparent access rules, measurable sectoral results and active SME participation. (Sources: EuroHPC, BRAIN++; Ministry of Innovation and Growth, €90 million project)</p>

<p>INSAIT is already a source of internationally visible research results, talent and potential new companies. In 2026 the institute reports 17 papers accepted to the main programme of CVPR, first place in Eastern Europe and a place among the top four institutions in the EU on that measure. The national task is to connect that research capacity systematically to industrial doctorates, joint laboratories, proof of concept and university spin-offs. (Sources: INSAIT, CVPR 2026; World Bank)</p>

<p>Discoverer+ is the third key asset. Upgrading it with AI systems widens the scope for specialised services to business, science and the public sector. The supercomputer and BRAIN++ will have a meaningful economic effect only if they are connected to universities, European digital innovation hubs, industry clusters and concrete manufacturing and public applications. (Source: Ministry of Innovation and Growth, Discoverer+)</p>

<h2>Cases from business, the public sector and universities</h2>

<h3>Business</h3>

<p>Shelly Group shows how digitalisation can become a product in its own right rather than only an internal process. The company develops solutions for the internet of things, smart buildings, automation and energy management, connected through mobile and cloud applications. According to the World Bank\'s assessment, in 2025 the company\'s market value passed one billion US dollars. The lesson is that Bulgaria can build globally competitive companies when an engineering base is combined with an own product and a clearly solved customer problem. (Sources: Shelly, investor report 2025; World Bank)</p>

<p>Payhawk represents a different trajectory — a fintech platform that embeds AI in financial processes. In 2025 the company developed its "AI Office of the CFO" and AI agents for procurement, travel, expenses and payments. The World Bank notes that Payhawk reached unicorn status in 2022. What matters about the case is the existence of a Bulgarian enterprise AI product with an international market, not the company\'s valuation alone. (Sources: Payhawk, Fall 2025; World Bank)</p>

<p>Postbank is an example of staged adoption in a traditional sector. The bank uses EVA as a digital assistant in Bulgarian and English and applies AI tools in recruitment. The approach is instructive: organisations often see the fastest results when they start with customer service, human resources and knowledge work before moving to riskier core systems. (Source: Postbank, EVA)</p>

<h3>Public sector</h3>

<p>CAIS EPP, the centralised electronic public procurement system, is a mature example of process digitalisation at national scale. In 2025 the system carried 111,415 notices and received 111,355 tenders. At the same time the share of procedures with a single bid was 39%, against a target of 23% under the National Recovery and Resilience Plan. This marks an important boundary: digitising a process improves traceability and efficiency, but it does not automatically fix problems of competition and market quality. (Source: Public Procurement Agency, annual report 2025)</p>

<p>The National Health Information System is the second strong public case. As of November 2025 it had processed over 615 million electronic health records, serves close to 3 million requests a day, more than 21,000 doctors actively use the eRx application, and active citizen users of eZdrave number around 240,000. This is a genuine state platform at scale, and it can support a next wave of trustworthy AI — given clear rules on data quality, access, security and human oversight. (Source: Information Services, NHIS)</p>

<p>Electronic justice and the Unified Information System of the courts are the third example. On November 2025 figures, over 2.4 million electronic cases have been filed and heard over five years, more than 8.7 million electronic acts issued, the system is used by over 12,300 users and connected to more than 15 external systems. The most suitable first applications of AI here are speech-to-text, document workflow support, search and analytical assistance — not opaque automation of judicial decisions. (Source: Information Services, electronic justice)</p>

<h3>Universities and research transfer</h3>

<p>In Bulgaria the strongest academic capacity in AI is concentrated in a small number of structures, whereas the leading European systems have broader networks of universities, industrial partnerships and sustained funding for transfer. Low national R&amp;D intensity and limited commercialisation mechanisms mean that research successes spread only with difficulty beyond individual centres. (Sources: World Bank; Eurostat, R&amp;D)</p>

<p>Sofia University concentrates the most visible academic core through INSAIT and GATE. GATE was established as an autonomous structure for big data and smart society applications, while INSAIT produces internationally visible publications and early commercialisation results. This model shows how a university can be at once an educational institution, a research infrastructure and a basis for creating new companies. (Sources: GATE; INSAIT, CVPR 2026; World Bank)</p>

<p>The Technical University of Sofia builds the engineering and applied base. The ICARAI 2025 international conference, intensive AI programmes and projects in robotics, communications and signal processing matter for the workforce that manufacturing, embedded systems and industrial automation need. That capacity should be linked to more joint laboratories and to projects commissioned by enterprises. (Source: Technical University of Sofia, ICARAI 2025)</p>

<p>The Medical University of Pleven illustrates the scope for AI in medical education and clinical practice. In 2025 the university introduced an AI application in its library and developed activities in robotic surgery and telemedicine. Healthcare is one of the most promising fields for trustworthy AI in Bulgaria, but it requires clinical expertise, quality data, validation and ethical rules together. (Source: Medical University of Pleven)</p>

<h2>Barriers and opportunities</h2>

<h3>A system of interlocking barriers</h3>

<p>The first barrier is institutional and regulatory predictability. The core problem is not a formal absence of rules but their uneven application, slow commercial disputes, difficult insolvency, weak protection of intellectual property and unclear rules on ownership of results from publicly funded research. In AI, legal uncertainty raises transaction costs and discourages investors, universities and scaling companies. (Source: World Bank)</p>

<p>The second barrier is skills. The shortage is not confined to programmers. What is needed are managers, engineers, data specialists, applied researchers, teachers and civil servants who can turn the technology into a working process. Demographic decline, outward migration of qualified people and a weak match between education and demand turn AI policy into policy for the general capacity of the economy. (Sources: World Bank; Eurostat, digitalisation 2026)</p>

<p>The third barrier is funding beyond the early stage. Bulgaria has funds and programmes to start companies, but under 10% of venture investment reaches Series B and later stages. A capital and market bridge is needed between a proven product and international scale-up. Without it, the strongest companies move key functions abroad precisely when they begin to create the most value. (Source: World Bank)</p>

<p>The fourth barrier is access to data and how little of it is put to use. Bulgaria has significant systems such as the NHIS, RegiX and CAIS EPP. RegiX connects dozens of registers and provides a basis for exchange between authorities, but that does not automatically mean the data are well documented, standardised and fit for analysis and machine learning. Common rules on data governance, application programming interfaces, anonymisation, synthetic data and secure testing environments are required. (Sources: State e-Government Agency, RegiX; Information Services, NHIS; Public Procurement Agency, annual report 2025)</p>

<p>The fifth barrier is regional concentration. Growth, start-up activity and much of the specialised talent sit in Sofia and, to a lesser degree, Plovdiv. Outside those centres, infrastructure, management capacity and the links between universities and firms are uneven. Bulgaria has strong digital centres but not yet a national network dense enough to diffuse technology. (Source: World Bank)</p>

<p>The sixth barrier is trust. Low use of electronic identification and of public electronic services shows that a technological supply does not automatically become use by citizens and business. In AI, trust requires clear accountability, human oversight, traceability, explainability and the possibility of challenge. Without those elements, public deployment will generate resistance rather than value. (Sources: Eurostat, e-government; EC, AI Act)</p>

<h3>The opportunities are real</h3>

<p>Bulgaria has a rare combination for a country of its size: a high-calibre research core, European infrastructure through BRAIN++, working national digital systems holding large volumes of data, product companies with international markets and a comparatively high share of women in ICT professions. That makes the next phase of development attainable — but only if these assets are used in a coordinated way. (Sources: INSAIT, CVPR 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC, BRAIN++</a>; Eurostat, ICT specialists; World Bank)</p>

<p>The right reference point is not a mechanical copy of large economies but the model of small, coordinated European states. Three practical lessons can be drawn from Denmark and Finland: a strong digital core in the public sector, a standing connection between science and enterprises, and a consistent focus on mass adoption in SMEs. The difference is made by execution at scale, not by the number of strategic documents. (Sources: Eurostat, digitalisation 2026; Eurostat, e-government)</p>

<h2>What to do now</h2>

<p>The recommendations are ordered by strategic importance. For planning purposes an indicative scale of the public and blended resource required is used: low — up to about 10 million leva; medium — about 10–50 million leva; high — over 50 million leva. What matters is that the investment builds mechanisms for diffusing capacity rather than a series of disconnected pilot projects.</p>

<h3>Priority recommendations and timeline</h3>

<p><strong>P1. A national programme for AI adoption in SMEs</strong> — through vouchers, sectoral consultancy and the European digital innovation hubs. Every project should cover data, process and cybersecurity, not merely a licence for a tool.<br>
<em>Timing:</em> start in 6–12 months, scale over 36 months. <em>Lead actors:</em> Ministry of Innovation and Growth, Ministry of e-Government, BCCI, EDIHs, branch organisations, municipalities. <em>Resource:</em> medium to high.</p>

<p><strong>P2. A national coordination unit for AI governance and procurement</strong> — procurement and contract templates, risk classification, model cards, human oversight, audit trails and readiness for the AI Act.<br>
<em>Timing:</em> 6–12 months. <em>Lead actors:</em> Ministry of e-Government, Council of Ministers, Commission for Personal Data Protection, Public Procurement Agency, sectoral regulators, BCCI. <em>Resource:</em> low to medium.</p>

<p><strong>P3. Sectoral data spaces and testing environments</strong> for healthcare, justice, energy and industry, including standards for anonymisation, synthetic data and access control.<br>
<em>Timing:</em> 12–36 months. <em>Lead actors:</em> Ministry of e-Government, Ministry of Health, Ministry of Justice, Ministry of Energy, National Statistical Institute, universities, Sofia Tech Park. <em>Resource:</em> high.</p>

<p><strong>P4. Unified rules for university intellectual property</strong> and a national fund for proof of concept and transfer from universities and the Bulgarian Academy of Sciences to enterprises.<br>
<em>Timing:</em> 12–24 months. <em>Lead actors:</em> Ministry of Education and Science, Ministry of Innovation and Growth, Fund of Funds, universities, Bulgarian Academy of Sciences. <em>Resource:</em> medium.</p>

<p><strong>P5. A scale-up financing instrument for Series A and B rounds</strong>, and use of the public sector as a first customer for Bulgarian deep-tech and AI companies.<br>
<em>Timing:</em> 12–36 months. <em>Lead actors:</em> Ministry of Innovation and Growth, Fund of Funds, Bulgarian Development Bank, EIF/EIB, Public Procurement Agency. <em>Resource:</em> high.</p>

<p><strong>P6. A large-scale AI literacy programme</strong> and practical upskilling for managers, SME employees, civil servants, teachers and university lecturers.<br>
<em>Timing:</em> start within 6 months, then continuous. <em>Lead actors:</em> Ministry of Education and Science, Ministry of Labour and Social Policy, Institute of Public Administration, BCCI, universities, employers\' organisations. <em>Resource:</em> medium to high.</p>

<p><strong>P7. A regulatory sandbox and a compliance advisory centre</strong> for high-risk AI, e-government, health technology and financial technology.<br>
<em>Timing:</em> 6–18 months. <em>Lead actors:</em> Ministry of e-Government, sectoral regulators, Commission for Personal Data Protection, Bulgarian National Bank, Financial Supervision Commission, BCCI. <em>Resource:</em> low to medium.</p>

<p><strong>P8. Five national missions with a measurable outcome:</strong> AI in healthcare, justice, municipal services, energy efficiency and industrial automation.<br>
<em>Timing:</em> 12–48 months. <em>Lead actors:</em> Council of Ministers, line ministries, municipalities, universities, business. <em>Resource:</em> high.</p>

<p>The first package for immediate action should cover P1, P2 and P4. P1 addresses the central deficit — the weak diffusion of technology into mainstream business. P2 creates a predictable framework, without which the public sector will oscillate between excessive caution and disconnected pilots. P4 turns research results into products, licences and new companies. The three measures need to run in parallel; otherwise BRAIN++, INSAIT and individual company successes will not add up to broad economic change. (Sources: EC, Digital Decade 2026; World Bank; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC, BRAIN++</a>)</p>

<h3>The roles of the key actors</h3>

<p>No single actor can deliver the transformation alone. The state sets the rules, manages public data and shapes a significant part of demand. Universities and research organisations supply talent, research and infrastructure. Business carries the responsibility for adoption, productivity and international markets. BCCI and the branch organisations can connect these three systems and turn shared goals into sectoral programmes.</p>

<p>For the AI Council at BCCI the most useful role is to coordinate the diffusion of AI across sectors, skills and adoption rules. That requires standing working groups, regular meetings between firms, universities and the administration, and a common set of key performance indicators:</p>

<ul>
<li>The share of participating SMEs that adopt a solution and are still using it after 12 months.</li>
<li>The measured change in productivity, processing time, quality or the cost of the process.</li>
<li>The number of accessible datasets and testing environments with clear rules of use.</li>
<li>The number of university proofs of concept, licences and new companies.</li>
<li>The number of managers and employees trained, and the share of organisations with internal rules for responsible use of AI.</li>
</ul>

<h3>Immediate next steps for the Council</h3>

<ol>
<li>Within three months, set up working groups on SMEs, skills, university transfer and regulatory questions, each with a named lead and a timetable.</li>
<li>Conduct structured interviews with representatives of leading SMEs, universities, municipalities and public institutions, in order to select problems with clear economic or social value.</li>
<li>By month six, start at least two sectoral pilots with predefined indicators, a budget, a process owner and a scaling plan.</li>
<li>By the end of 2026, publish a short scoreboard report on progress, results, lessons learned and the changes needed in regulation, funding and public procurement.</li>
</ol>

<h2>Conclusion</h2>

<p>Bulgaria has a real opportunity to become a regional centre for applied AI in South-East Europe. The assets are there: INSAIT, BRAIN++, the expanded supercomputing infrastructure, national systems in healthcare, public procurement and justice, and product companies with international success. The weakness is that these assets are not yet converting quickly enough into mass adoption in SMEs, in the regions and in public services. (Sources: INSAIT, CVPR 2026; <a href="https://eurohpc-ju.europa.eu/ai-factories/bulgaria_en">EuroHPC, BRAIN++</a>; EC, Digital Decade 2026; World Bank)</p>

<p>The goal should therefore not be for Bulgaria to have a handful of impressive projects. The goal is for AI and digitalisation to raise the productivity of thousands of enterprises, the quality of dozens of public services and the economic value of university research. It is precisely in connecting business, the state and the universities that the AI Council at BCCI can have the largest and most measurable impact.</p>'
                ),
            ],
            [
                'slug' => 'ai-adoption-gap-bulgaria-eu-eurostat-2025',
                'date' => '2026-07-22',
                'title' => $this->t(
                    'Къде стои българският бизнес с изкуствения интелект: 8,55% срещу 19,95% за ЕС',
                    'Where Bulgarian business really stands on AI: 8.55% against the EU\'s 19.95%'
                ),
                'excerpt' => $this->t(
                    'През 2025 г. 8,55% от българските предприятия с 10 и повече заети използват изкуствен интелект, при 19,95% за ЕС-27. Разликата със средното за Съюза расте — от 4,36 на 11,40 процентни пункта от 2021 г. насам.',
                    'In 2025, 8.55% of Bulgarian enterprises with 10 or more persons employed used AI, against 19.95% across the EU-27. The gap to the EU average has widened from 4.36 to 11.40 percentage points since 2021.'
                ),
                'meta_description' => $this->t(
                    'Евростат, 2025 г.: 8,55% от българските предприятия с 10+ заети използват ИИ срещу 19,95% в ЕС-27. Колко е голяма разликата и какво я движи.',
                    'Eurostat 2025: 8.55% of Bulgarian enterprises with 10+ persons employed use AI, versus 19.95% in the EU-27 - the size of the gap and what drives it.'
                ),
                'body' => $this->t(
                    '<p>По <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">данни на Евростат</a> за 2025 г. <strong>8,55% от българските предприятия с 10 и повече заети лица използват поне една технология на изкуствен интелект, при средно 19,95% за ЕС-27</strong> — по-малко от половината от средното за Съюза и трето най-ниско ниво сред 27-те държави членки, след Румъния (5,21%) и Полша (8,36%). По-съществено от самото изоставане е посоката му: през 2021 г. разликата със средното за ЕС беше 4,36 процентни пункта, през 2025 г. е 11,40 пункта. Тя не се стопява, а се разширява.</p>

<h2>Какво точно измерва това число</h2>
<p>8,55% не означава „всички български фирми“. Показателят на Евростат (набор от данни <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_eb_ai?format=JSON&amp;lang=EN&amp;geo=BG&amp;indic_is=E_AI_TANY&amp;size_emp=GE10&amp;unit=PC_ENT">isoc_eb_ai</a>) обхваща предприятия с 10 или повече заети лица — наети и самонаети — в агрегата по NACE Rev. 2 C10-S951_X_K, тоест „всички дейности с изключение на селското, горското и рибното стопанство и на добивната промишленост“, при това без финансовия сектор. Микропредприятията с под 10 заети изобщо не участват в тази статистика — за България за тях няма публикувани данни.</p>
<p>„Използване на ИИ“ пък означава използване на поне една от осем технологии: извличане на информация от текст; разпознаване на реч; генериране на писмен и говорим език и на програмен код; генериране на изображения, видео и звук; разпознаване на изображения; машинно обучение (включително дълбоко обучение) за анализ на данни; софтуерна роботизирана автоматизация на процеси; и технологии, позволяващи на машините да се придвижват физически въз основа на автономни решения. Данните са събрани от националната статистика в първите месеци на 2025 г.</p>

<h2>Тенденцията — с необходимата уговорка</h2>
<p>Серията за България е 3,29% (2021), 3,62% (2023), 6,47% (2024) и 8,55% (2025). За ЕС-27 съответно: 7,65%, 8,06%, 13,48% и 19,95%. Наблюдение за 2022 г. не съществува и не бива да се интерполира. Важна методологична уговорка: до 2024 г. показателят обединяваше седем технологии, а от 2025 г. — осем, след добавянето на генерирането на изображения, видео и звук. Част от скока между 2024 и 2025 г. отразява разширената дефиниция, а не само реално навлизане. Съотношението обаче остава показателно: за една година ЕС добавя 6,47 пункта, България — 2,08. Спрямо средното за ЕС относителната позиция на България почти не се променя — 43,0% от него през 2021 г. и 42,9% през 2025 г., с връх 48,0% през 2024 г. — докато абсолютното разстояние, което трябва да навакса, се е увеличило повече от два пъти.</p>

<h2>Не е само въпрос на размер на фирмите</h2>
<p>Удобното обяснение е, че българската икономика се състои предимно от малки предприятия. Данните го опровергават. В ЕС делът расте от 17,0% при фирмите с 10–49 заети през 30,36% при тези с 50–249 до 55,03% при тези с 250 и повече. В България същите групи са 7,17%, 13,3% и 26,18%. Тоест дори най-големите български предприятия остават под равнището на <em>средните</em> европейски фирми и под половината от това на големите. Разликата се разширява с размера на компанията — което насочва към капацитет, умения и управленски избор, а не само към структурата на икономиката.</p>
<p>Словения показва, че регионът не е съдба: 21,61% — над средното за ЕС. Следват Словакия с 18,00%, Чехия с 17,60%, Хърватия с 15,19%, Унгария с 10,37% и Гърция с 8,93%. Начело са Дания с 42,03%, Финландия с 37,82% и Швеция с 35,04%.</p>

<h2>Какво спира предприятията</h2>
<p>Тук знаменателят е решаващ. През 2025 г. едва 11,1% от предприятията в ЕС с 10 и повече заети и 4,89% от българските изобщо някога са обмисляли използването на ИИ технология. Сред <em>обмислялите</em> причините да не използват ИИ в ЕС са: липса на подходяща експертиза — 70,31%; неяснота за правните последици — 53,61%; опасения за нарушаване на защитата на личните данни — 52,72%; ИИ не е полезен за предприятието — 17,79%. За България: 72,69%, 50,29%, 47,68% и 16,26%. Тези проценти са дял от обмислялите ИИ предприятия, а не от всички; без тази уговорка те заблуждават.</p>
<p>Сравнението с 2024 г. на равнище ЕС е поучително: сред обмислялите ИИ предприятия „не е полезен“ спада от 20,68% на 17,79%, а опасенията за нарушаване на защитата на личните данни нарастват от 48,83% на 52,72%. В тази група все по-малко се съмняват в ползата и все повече се колебаят заради правните последици и защитата на личните данни.</p>

<h2>Служителите изпреварват работодателите</h2>
<p>Друго изследване на Евростат — <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_by_individuals">за използването на изкуствен интелект от лицата</a> — отчита, че 22,50% от лицата на възраст 16–74 г. в България са използвали генеративни ИИ инструменти през последните три месеца преди анкетата, при 32,66% за ЕС-27. По същия набор от данни (isoc_ai_iaiu, разбивка по възрастови групи — вж. бележката за източниците) сред българите на 16–24 г. делът е 49,98%, при 26,78% за 25–54 г. и 8,09% за 55–74 г. Двете изследвания имат различни съвкупности и стойностите им не се изваждат една от друга. Но посоката е недвусмислена: хората навлизат в тези инструменти по-бързо, отколкото организациите ги въвеждат официално.</p>
<p>Това има пряко правно измерение. Член 4 от <a href="https://eur-lex.europa.eu/legal-content/BG/TXT/HTML/?uri=OJ:L_202401689">Регламент (ЕС) 2024/1689</a> (Акт за изкуствения интелект) задължава доставчиците и внедрителите на системи с ИИ да „предприемат всички възможни мерки, за да гарантират достатъчно ниво на грамотност в областта на ИИ на техния персонал и другите лица, занимаващи се с функционирането и използването на системите с ИИ от тяхно име“. Задължението се прилага от 2 февруари 2025 г. В <a href="https://digital-strategy.ec.europa.eu/en/faqs/ai-literacy-questions-answers">необвързващите си разяснения</a> Европейската комисия приема, че фирма, чиито служители използват ChatGPT например за рекламни текстове или преводи, попада в обхвата: служителите следва да бъдат информирани за конкретните рискове, например за халюцинациите (в оригинал на английски: „Yes, they should be informed about the specific risks, for example hallucination.“). Надзорът и прилагането са в правомощията на националните органи за надзор на пазара и започват от 2 август 2026 г.</p>

<h2>Бележка за източниците</h2>
<p>Всички числа произхождат от базите isoc_eb_ai и isoc_ai_iaiu на Евростат (обновени на 15 и 5 юни 2026 г.), извлечени на 22 юли 2026 г., и от текста на Регламент (ЕС) 2024/1689. Разликите в процентни пунктове и съотношенията спрямо средното за ЕС са изчислени пряко от тези публикувани стойности. Възрастовите разбивки за България и ЕС-27 са от isoc_ai_iaiu, показател I_IUAI, категории Y16_24, Y25_54 и Y55_74 — точният адрес на извличането е в списъка с източници. Цитатът от член 4 е по официалния български текст на регламента в EUR-Lex; отговорът на Европейската комисия е публикуван само на английски. Данните за предприятията не обхващат микрофирмите и финансовия сектор; данните за лицата се отнасят за възрастта 16–74 г. Текстът не е правен съвет.</p>',
                    '<p>According to <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">Eurostat</a>, in 2025 <strong>8.55% of Bulgarian enterprises with 10 or more persons employed used at least one artificial-intelligence technology, against an EU-27 average of 19.95%</strong> — less than half the Union average, and the third-lowest share among the 27 Member States, with only Romania (5.21%) and Poland (8.36%) ranking lower. More telling than the gap itself is its direction: in 2021 it stood at 4.36 percentage points; in 2025 it is 11.40. It is not closing — it is widening.</p>

<h2>What the figure actually measures</h2>
<p>8.55% does not mean "all Bulgarian companies". The Eurostat indicator (dataset <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_eb_ai?format=JSON&amp;lang=EN&amp;geo=BG&amp;indic_is=E_AI_TANY&amp;size_emp=GE10&amp;unit=PC_ENT">isoc_eb_ai</a>) covers enterprises with 10 or more persons employed — employees and self-employed — in NACE Rev. 2 aggregate C10-S951_X_K, that is "all activities except agriculture, forestry and fishing, and mining and quarrying", and excluding the financial sector. Micro-enterprises with fewer than 10 persons employed are outside the statistic altogether; no Bulgarian data are published for them.</p>
<p>"Using AI" means using at least one of eight technologies: text mining; speech recognition; natural language generation and speech synthesis, including programming code; generation of pictures, video or sound/audio; image recognition; machine learning, including deep learning, for data analysis; AI-based software robotic process automation; and technologies enabling machines to move physically by taking autonomous decisions. The data were collected by national statistical authorities in the first months of 2025.</p>

<h2>The trend, with the necessary caveat</h2>
<p>Bulgaria\'s series runs 3.29% (2021), 3.62% (2023), 6.47% (2024) and 8.55% (2025). The EU-27 equivalents are 7.65%, 8.06%, 13.48% and 19.95%. There is no 2022 observation, and none should be interpolated. One methodological caveat matters: until 2024 the indicator aggregated seven technologies; from 2025 it aggregates eight, after image, video and audio generation was added. Part of the 2024-to-2025 jump reflects the widened definition rather than adoption alone. The ratio is still instructive: over one year the EU added 6.47 points, Bulgaria 2.08. Measured against the EU average, Bulgaria\'s relative position has barely moved — 43.0% of the EU level in 2021 and 42.9% in 2025, with a peak of 48.0% in 2024 — while in absolute terms the distance it has to close has more than doubled.</p>

<h2>Not merely a question of company size</h2>
<p>The convenient explanation is that the Bulgarian economy is made of small firms. The data refute it. Across the EU, adoption rises from 17.0% among enterprises with 10-49 persons employed, through 30.36% for 50-249, to 55.03% for 250 and more. In Bulgaria the same bands are 7.17%, 13.3% and 26.18%. Even Bulgaria\'s largest enterprises therefore sit below the European rate for <em>medium-sized</em> firms, and at under half the European large-firm rate. The gap widens with company size — which points to capability, skills and management choice, not only to economic structure.</p>
<p>Slovenia shows the region is not destiny, at 21.61%, above the EU average. Slovakia is at 18.00%, Czechia 17.60%, Croatia 15.19%, Hungary 10.37% and Greece 8.93%. The leaders are Denmark at 42.03%, Finland 37.82% and Sweden 35.04%.</p>

<h2>What holds enterprises back</h2>
<p>Here the denominator decides everything. In 2025 only 11.1% of EU enterprises with 10 or more persons employed, and 4.89% of Bulgarian ones, had ever even considered using an AI technology. Among <em>those that considered it</em>, the EU reasons for not using AI were: lack of relevant expertise, 70.31%; lack of clarity about the legal consequences, 53.61%; concerns about breaching data protection and privacy, 52.72%; AI not useful for the enterprise, 17.79%. Bulgaria\'s figures are 72.69%, 50.29%, 47.68% and 16.26%. These shares are of enterprises that ever considered AI, not of all enterprises — quoted without that qualifier they mislead.</p>
<p>The 2024 comparison at EU level is revealing: among enterprises that had ever considered AI, "not useful" falls from 20.68% to 17.79%, while concerns about breaching data protection and privacy rise from 48.83% to 52.72%. Within that group, doubts about usefulness are receding while hesitation over the legal consequences and over personal-data protection is growing.</p>

<h2>Employees are ahead of employers</h2>
<p>A separate Eurostat survey — on <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_by_individuals">the use of artificial intelligence by individuals</a> — records that 22.50% of individuals aged 16-74 in Bulgaria had used generative AI tools in the three months before the survey, against 32.66% across the EU-27. In the same dataset (isoc_ai_iaiu, broken down by age group — see the note on sources), the share among Bulgarians aged 16-24 is 49.98%, against 26.78% for 25-54 and 8.09% for 55-74. The two surveys have different populations and their values cannot be subtracted from one another. The direction, however, is unambiguous: people are taking up these tools faster than organisations are formally adopting them.</p>
<p>That has a direct legal dimension. Article 4 of <a href="https://eur-lex.europa.eu/legal-content/EN/TXT/HTML/?uri=OJ:L_202401689">Regulation (EU) 2024/1689</a> (the AI Act) requires providers and deployers of AI systems to "take measures to ensure, to their best extent, a sufficient level of AI literacy of their staff and other persons dealing with the operation and use of AI systems on their behalf". The obligation has applied since 2 February 2025. In its <a href="https://digital-strategy.ec.europa.eu/en/faqs/ai-literacy-questions-answers">non-binding guidance</a>, the European Commission takes the view that a company whose employees use ChatGPT for, say, advertising copy or translation falls within scope: "Yes, they should be informed about the specific risks, for example hallucination." Supervision and enforcement rest with national market surveillance authorities and begin on 2 August 2026.</p>

<h2>A note on sources</h2>
<p>Every figure comes from Eurostat datasets isoc_eb_ai and isoc_ai_iaiu (updated 15 and 5 June 2026), extracted on 22 July 2026, and from the text of Regulation (EU) 2024/1689. The percentage-point differences and the ratios to the EU average are calculated directly from those published values. The age breakdowns for Bulgaria and the EU-27 come from isoc_ai_iaiu, indicator I_IUAI, categories Y16_24, Y25_54 and Y55_74; the exact extraction address is in the source list. Article 4 is quoted from the official English text of the Regulation on EUR-Lex, and the Commission\'s answer is published in English only. The enterprise data exclude micro-firms and the financial sector; the individual data cover ages 16-74. This text is not legal advice.</p>'
                ),
            ],
            [
                'slug' => 'why-companies-that-considered-ai-decided-against-it',
                'date' => '2026-07-22',
                'title' => $this->t(
                    'Защо компаниите, които вече са обмисляли изкуствен интелект, се отказват',
                    'Why companies that considered AI decided against it'
                ),
                'excerpt' => $this->t(
                    'Не защото ИИ им е безполезен. По данни на Евростат за 2025 г. сред предприятията в ЕС с 10 и повече заети лица, обмисляли ИИ, 70,31% сочат липса на експертиза, 53,61% правна неяснота, а че не е полезен — едва 17,79%.',
                    'Not because they found AI useless. Eurostat data for 2025 show that among EU enterprises with 10 or more persons employed that considered AI, 70.31% cite missing expertise, 53.61% legal uncertainty and only 17.79% say it is not useful.'
                ),
                'meta_description' => $this->t(
                    'Евростат 2025: сред фирмите в ЕС с 10+ заети, обмисляли ИИ, 70,31% сочат липса на експертиза, а само 17,79% – че не е полезен. Какво значи това за България.',
                    'Eurostat 2025: among EU firms with 10+ staff that considered AI, 70.31% cite missing expertise and only 17.79% say it is not useful. What it means for Bulgaria.'
                ),
                'body' => $this->t(
                    '<p>Не защото са решили, че изкуственият интелект не им е полезен. По данни от <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table?lang=en">базата данни isoc_eb_ai на Евростат</a> за 2025 г., сред предприятията в ЕС с 10 и повече заети лица, които някога са обмисляли използването на технология на изкуствения интелект (ИИ), 70,31% посочват като причина да не я използват липсата на подходяща експертиза, 53,61% — липсата на яснота относно правните последици, и 52,72% — опасенията за нарушаване на защитата на данните и на неприкосновеността на личния живот. Отговорът, че технологиите на ИИ не са полезни за предприятието, е избран от 17,79% — близо четири пъти по-рядко от липсата на експертиза.</p>

<p>Сред тези предприятия мнозинството не отхвърля ИИ по същество: най-често посочваните пречки са, че няма кой да го внедри и че не е ясно какво е позволено. Показателят се измерва сред предприятията, които не използват ИИ. За всички останали — както тези, които вече използват ИИ, така и тези, които изобщо не са стигали до обмисляне — това изследване не казва нищо по въпроса защо.</p>

<h2>Какво точно измерват тези проценти</h2>

<p>Числата по-горе не се отнасят до всички европейски предприятия. Евростат публикува същите показатели с няколко различни знаменателя, а използваната тук мерна единица (код PC_ENT_AI_EC) е процентът от предприятията, които някога са обмисляли използването на някоя от технологиите на ИИ. Тази група е малка: през 2025 г. тя е 11,1% от обхванатите предприятия в ЕС и 4,89% в България. Спрямо всички обхванати предприятия същата пречка — липсата на подходяща експертиза — дава 7,76% за ЕС и 3,56% за България. И двете стойности са официални; разликата е в знаменателя.</p>

<p>Обхватът също е ограничен: предприятия с 10 и повече заети лица в агрегата по NACE Rev. 2 C10–S951_X_K — всички дейности без селското, горското и рибното стопанство, без добивната промишленост и без финансовия сектор. Микропредприятията с под 10 заети лица не влизат в статистиката.</p>

<p>Едно предупреждение за читателя, който ще провери източника: обзорната статия на Евростат в Statistics Explained (данни, извлечени през декември 2025 г.) посочва за същите четири причини други стойности — 70,89%, 52,52%, 48,83% и 20,68%. Това са стойностите за референтна 2024 година в същата база данни. Числата тук са взети директно от базата данни isoc_eb_ai за референтна 2025 година, в редакцията ѝ от 15 юни 2026 г.</p>

<h2>Какво се променя между 2024 и 2025 г. в ЕС</h2>

<p>Сравнението между двете години по същия показател за ЕС-27 изглежда така:</p>

<ul>
<li>липса на подходяща експертиза: 70,89% през 2024 г. и 70,31% през 2025 г.;</li>
<li>липса на яснота относно правните последици: 52,52% и 53,61%;</li>
<li>опасения за нарушаване на защитата на данните и на неприкосновеността на личния живот: 48,83% и 52,72%;</li>
<li>технологиите на ИИ не са полезни за предприятието: 20,68% и 17,79%.</li>
</ul>

<p>Две наблюдения не правят тенденция, а редицата няма данни за 2022 г. С това уточнение: аргументът, че технологията не е полезна, отстъпва с 2,89 процентни пункта, опасенията за данните нарастват с 3,89 пункта, а липсата на експертиза остава практически непроменена (–0,58 пункта). Посоката не е една и съща навсякъде: в България правната неяснота се движи в обратна посока — надолу.</p>

<h2>Българската картина</h2>

<p>За България същите четири показателя са: липса на експертиза 61,60% през 2024 г. и 72,69% през 2025 г.; правна неяснота 53,51% и 50,29%; защита на данните 38,39% и 47,68%; отговорът, че технологиите на ИИ не са полезни за предприятието — 13,25% и 16,26%. За една година пречката липса на експертиза нараства с над 11 процентни пункта.</p>

<p>През 2025 г. 8,55% от българските предприятия с 10 и повече заети лица в същия отраслов обхват са използвали <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">поне една технология на ИИ</a>, при 19,95% средно за ЕС-27 — трета най-ниска стойност в Съюза, по-висока само от Румъния (5,21%) и Полша (8,36%). Разликата с Гърция (8,93%) обаче е едва 0,38 процентни пункта, така че самото класиране е крехко. По големина: 7,17% при 10–49 заети, 13,3% при 50–249 и 26,18% при 250 и повече, срещу 17,0%, 30,36% и 55,03% в ЕС. Дори най-големите български компании остават под половината от европейското ниво за своята категория по големина.</p>

<h2>Правната неяснота вече има конкретни дати</h2>

<p><a href="https://eur-lex.europa.eu/legal-content/BG/TXT/HTML/?uri=OJ:L_202401689">Регламент (ЕС) 2024/1689</a> (Акт за изкуствения интелект) е публикуван в Официален вестник на 12 юли 2024 г. и влиза в сила на 1 август 2024 г. Съгласно член 113 от него: „Прилага се от 2 август 2026 г.“, но „глави I и II се прилагат, считано от 2 февруари 2025 г.“ Това означава, че задължението за грамотност в областта на ИИ по член 4 и забраните по член 5 обвързват предприятията вече над година.</p>

<p>В разясненията си <a href="https://digital-strategy.ec.europa.eu/en/faqs/ai-literacy-questions-answers">„AI literacy – questions and answers“</a> (последна актуализация 19 ноември 2025 г.) Европейската комисия отговаря на въпроса дали компания, чиито служители използват ChatGPT например за рекламни текстове или за преводи, трябва да спазва член 4, така: „Yes, they should be informed about the specific risks, for example hallucination.“ — тоест служителите следва да бъдат информирани за конкретните рискове, например за халюцинации. Надзорът и правоприлагането се осъществяват от националните органи за надзор на пазара; на едно място страницата на Комисията сочи 3 август 2026 г., а датата на прилагане по член 113 от регламента е 2 август 2026 г.</p>

<p>Полезно е да се знае какво не предвижда регламентът. Член 4 не е сред разпоредбите, изброени в член 99, параграф 4 — тези, чието нарушение се наказва с административни глоби и имуществени санкции „в размер до 15 000 000 EUR или, ако нарушителят е предприятие, до 3 % от общия му годишен световен оборот за предходната финансова година“. А съгласно член 99, параграф 6 за МСП, включително новосъздадените предприятия, всяка глоба или имуществена санкция „е в размер до процентите или сумата, посочени в параграфи 3, 5 и 4, като се прилага по-ниският размер“ — тоест по-ниската от двете стойности, а не по-високата.</p>

<p>Изменящият регламент „Digital Omnibus on AI“ (процедура 2025/0359(COD)), който отлага задълженията за високорисковите системи, е приет от Европейския парламент на 16 юни и от Съвета на 29 юни 2026 г. и е подписан от председателите на двете институции на 8 юли 2026 г., но към 22 юли 2026 г. не е публикуван в Официален вестник и съответно не е в сила.</p>

<h2>Какво следва от тези данни</h2>

<p>Ако водещата пречка беше липсата на полза, задачата щеше да е убеждаване. Данните сочат друго: три от четирите най-често посочвани причини са въпроси на капацитет — умения, правна ориентация и защита на данните. Те изискват обучение, вътрешни правила и разбираема правна информация, а не още демонстрации.</p>

<p>По данни на Евростат 22,50% от лицата на 16–74 г. в България са използвали инструменти на генеративния ИИ през трите месеца преди изследването през 2025 г., при 32,66% в ЕС; сред 16–24-годишните в България делът е 49,98%, а сред 55–74-годишните в България — 8,09%. Това е друго изследване, с друга съвкупност, и не е пряко съпоставимо с корпоративната статистика. Съпоставянето на двата реда числа обаче повдига въпрос, на който всяка компания може да си отговори сама: 22,50% лична употреба при 8,55% фирмена — има ли предприятието вътрешни правила и обучение за инструменти, които част от служителите му вече ползват?</p>

<p><strong>За източниците.</strong> Данните за пречките и за използването на ИИ от предприятия са от базата <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_eb_ai?format=JSON&amp;lang=EN&amp;geo=EU27_2020&amp;geo=BG&amp;indic_is=E_AI_BLE&amp;indic_is=E_AI_BLEG&amp;indic_is=E_AI_BCDP&amp;indic_is=E_AI_BNU&amp;size_emp=GE10&amp;unit=PC_ENT_AI_EC">isoc_eb_ai</a> на Евростат (редакция от 15 юни 2026 г.), а данните за лицата — от базата <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_ai_iaiu?format=JSON&amp;lang=EN&amp;geo=EU27_2020&amp;geo=BG&amp;indic_is=I_IUAI&amp;unit=PC_IND&amp;ind_type=IND_TOTAL&amp;ind_type=Y16_24&amp;ind_type=Y55_74">isoc_ai_iaiu</a>; посочените адреси са заявки към разпространителския интерфейс на Евростат и връщат точно цитираните стойности. Правните разпоредби са цитирани по българското издание на Регламент (ЕС) 2024/1689 в EUR-Lex. Уговорки: редицата isoc_eb_ai няма наблюдение за 2022 г.; от 2025 г. агрегатът за използване на поне една технология на ИИ (E_AI_TANY) обхваща осем технологии вместо седем, така че част от ръста спрямо 2024 г. се дължи на разширената дефиниция. Тази втора уговорка се отнася до данните за използването — 8,55%, 19,95% и разбивките по големина — а не до показателите за пречките.</p>',
                    '<p>Not because they concluded that AI would be of no use to them. According to <a href="https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table?lang=en">Eurostat\'s isoc_eb_ai database</a> for 2025, among EU enterprises with 10 or more persons employed that had ever considered using an artificial intelligence (AI) technology, 70.31% cited a lack of relevant expertise as a reason for not using it, 53.61% cited a lack of clarity about the legal consequences, and 52.72% cited concerns about breaching data protection and privacy. The answer that AI technologies are not useful for the enterprise was chosen by 17.79% — nearly four times less often than missing expertise.</p>

<p>Among those enterprises, the majority is not rejecting AI on the merits: the most frequently cited barriers are that there is nobody to implement it and that it is unclear what is permitted. The indicator is measured among enterprises that do not use AI. About everyone else — both those already using AI and those that never got as far as considering it — this survey says nothing on the question of why.</p>

<h2>What these percentages actually measure</h2>

<p>The figures above do not refer to all European enterprises. Eurostat publishes these indicators against several denominators; the unit used here (code PC_ENT_AI_EC) is the percentage of the enterprises which ever considered to use any of the AI technologies. That group is small: in 2025 it was 11.1% of the enterprises in scope in the EU and 4.89% in Bulgaria. Measured against all enterprises in scope, the same barrier — a lack of relevant expertise — gives 7.76% for the EU and 3.56% for Bulgaria. Both values are official; only the denominator differs.</p>

<p>The coverage is limited too: enterprises with 10 or more persons employed in NACE Rev. 2 aggregate C10–S951_X_K — all activities except agriculture, forestry and fishing and mining and quarrying, and without the financial sector. Micro-enterprises with fewer than 10 persons employed are outside the statistic.</p>

<p>One warning for the reader who checks the source: Eurostat\'s Statistics Explained overview article (data extracted in December 2025) shows different values for these same four reasons — 70.89%, 52.52%, 48.83% and 20.68%. Those are the reference-year 2024 values in the same database. The figures used here are taken directly from the isoc_eb_ai database for reference year 2025, in its version of 15 June 2026.</p>

<h2>What changed between 2024 and 2025 in the EU</h2>

<p>The comparison between the two years on the same indicator for the EU-27 reads as follows:</p>

<ul>
<li>lack of relevant expertise: 70.89% in 2024 and 70.31% in 2025;</li>
<li>lack of clarity about the legal consequences: 52.52% and 53.61%;</li>
<li>concerns regarding violation of data protection and privacy: 48.83% and 52.72%;</li>
<li>AI technologies not useful for the enterprise: 20.68% and 17.79%.</li>
</ul>

<p>Two observations do not make a trend, and the series has no data for 2022. With that caveat: the argument that the technology is not useful recedes by 2.89 percentage points, data-protection concerns rise by 3.89 points, and the expertise barrier is essentially unchanged (–0.58 points). The direction is not the same everywhere: in Bulgaria legal uncertainty moves the other way — downwards.</p>

<h2>The Bulgarian picture</h2>

<p>For Bulgaria the same four indicators read: lack of expertise 61.60% in 2024 and 72.69% in 2025; legal uncertainty 53.51% and 50.29%; data protection 38.39% and 47.68%; the answer that AI technologies are not useful for the enterprise 13.25% and 16.26%. In a single year the expertise barrier rose by more than 11 percentage points.</p>

<p>In 2025, 8.55% of Bulgarian enterprises with 10 or more persons employed in the same scope <a href="https://ec.europa.eu/eurostat/statistics-explained/index.php?title=Use_of_artificial_intelligence_in_enterprises">used at least one AI technology</a>, against an EU-27 average of 19.95% — the third-lowest share in the Union, above only Romania (5.21%) and Poland (8.36%). The gap to Greece (8.93%) is only 0.38 percentage points, so the ranking itself is fragile. By size: 7.17% for 10–49 persons employed, 13.3% for 50–249 and 26.18% for 250 or more, against 17.0%, 30.36% and 55.03% in the EU. Even Bulgaria\'s largest companies stay below half the EU level for their size band.</p>

<h2>The legal uncertainty now has firm dates</h2>

<p><a href="https://eur-lex.europa.eu/legal-content/EN/TXT/HTML/?uri=OJ:L_202401689">Regulation (EU) 2024/1689</a> (the AI Act) was published in the Official Journal on 12 July 2024 and entered into force on 1 August 2024. Under its Article 113, "It shall apply from 2 August 2026", but "Chapters I and II shall apply from 2 February 2025". That means the AI literacy duty in Article 4 and the prohibitions in Article 5 have bound enterprises for over a year.</p>

<p>In its <a href="https://digital-strategy.ec.europa.eu/en/faqs/ai-literacy-questions-answers">"AI literacy – questions and answers"</a> guidance (last updated 19 November 2025), the European Commission answers the question whether a company whose employees use ChatGPT for, for example, writing advertisement text or translating text has to comply with Article 4: "Yes, they should be informed about the specific risks, for example hallucination." Supervision and enforcement sit with the national market surveillance authorities; in one place the Commission\'s page gives 3 August 2026, while the application date in Article 113 of the Regulation is 2 August 2026.</p>

<p>It is worth knowing what the Regulation does not provide. Article 4 is not among the provisions listed in Article 99(4) — those whose infringement is subject to administrative fines "of up to EUR 15 000 000 or, if the offender is an undertaking, up to 3 % of its total worldwide annual turnover for the preceding financial year". And under Article 99(6), for SMEs, including start-ups, "each fine referred to in this Article shall be up to the percentages or amount referred to in paragraphs 3, 4 and 5, whichever thereof is lower" — that is, the lower of the two figures, not the higher.</p>

<p>The amending "Digital Omnibus on AI" regulation (procedure 2025/0359(COD)), which defers the obligations for high-risk systems, was adopted by the European Parliament on 16 June and by the Council on 29 June 2026 and signed by the Presidents of both institutions on 8 July 2026, but as of 22 July 2026 it has not been published in the Official Journal and is therefore not in force.</p>

<h2>What follows from the data</h2>

<p>If the leading barrier were a lack of usefulness, the task would be persuasion. The data say otherwise: three of the four most frequently cited reasons are questions of capacity — skills, legal orientation and data protection. Those call for training, internal rules and accessible legal guidance rather than more demonstrations.</p>

<p>Eurostat also reports that 22.50% of individuals aged 16–74 in Bulgaria used generative AI tools in the three months before the 2025 survey, against 32.66% in the EU; among 16–24-year-olds in Bulgaria the share is 49.98%, and among those aged 55–74 in Bulgaria it is 8.09%. This is a different survey, with a different population, and is not directly comparable with the enterprise statistics. Setting the two sets of numbers side by side does raise a question each company can answer for itself: 22.50% personal use against 8.55% enterprise use — does the firm have internal rules and training for tools that some of its staff already use?</p>

<p><strong>On sources.</strong> The data on barriers and on enterprise AI use come from Eurostat\'s <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_eb_ai?format=JSON&amp;lang=EN&amp;geo=EU27_2020&amp;geo=BG&amp;indic_is=E_AI_BLE&amp;indic_is=E_AI_BLEG&amp;indic_is=E_AI_BCDP&amp;indic_is=E_AI_BNU&amp;size_emp=GE10&amp;unit=PC_ENT_AI_EC">isoc_eb_ai</a> database (version of 15 June 2026), and the data on individuals from <a href="https://ec.europa.eu/eurostat/api/dissemination/statistics/1.0/data/isoc_ai_iaiu?format=JSON&amp;lang=EN&amp;geo=EU27_2020&amp;geo=BG&amp;indic_is=I_IUAI&amp;unit=PC_IND&amp;ind_type=IND_TOTAL&amp;ind_type=Y16_24&amp;ind_type=Y55_74">isoc_ai_iaiu</a>; those addresses are queries to Eurostat\'s dissemination interface and return exactly the values quoted. The legal provisions are quoted from Regulation (EU) 2024/1689 on EUR-Lex. Caveats: the isoc_eb_ai series has no observation for 2022; and from 2025 the aggregate for use of at least one AI technology (E_AI_TANY) covers eight technologies instead of seven, so part of the rise on 2024 reflects the widened definition. That second caveat concerns the adoption figures — 8.55%, 19.95% and the size breakdowns — not the barrier indicators.</p>'
                ),
            ],
        ];

        foreach ($news as $n) {
            $article = NewsArticle::updateOrCreate(
                ['slug' => $n['slug']],
                [
                    'title' => $n['title'],
                    'excerpt' => $n['excerpt'],
                    'body' => $n['body'],
                    'meta_description' => $n['meta_description'] ?? null,
                    'published_at' => $n['date'],
                    'is_published' => true,
                ],
            );

            $this->attachCover($article);
        }
    }

    /**
     * Cover images: these articles are data journalism, not photo stories, so
     * the covers are the figures themselves rather than stock imagery. They are
     * rendered by `php artisan news:covers` and committed under public/assets;
     * the design lives in resources/news/cover.html.
     *
     * `preservingOriginal` keeps that committed source file in place — the
     * media library copies it rather than moving it — and the collection is
     * `singleFile`, so re-seeding replaces the cover instead of stacking a
     * second one behind it.
     *
     * The `seeded` custom property is what makes a cover safe to overwrite.
     * Seeding is routine on a development box, and an editor who uploads a
     * cover through the admin should not lose it the next time someone runs
     * `db:seed` — so only covers this method placed are ever replaced.
     */
    protected function attachCover(NewsArticle $article): void
    {
        $source = public_path("assets/news/{$article->slug}.png");

        if (! is_file($source)) {
            return;
        }

        $existing = $article->getFirstMedia(NewsArticle::COVER);

        if ($existing) {
            // Uploaded by a human through the admin — leave it alone.
            if (! $existing->getCustomProperty('seeded')) {
                return;
            }

            // Already carrying this exact file.
            if ($existing->getCustomProperty('sha1') === sha1_file($source)) {
                return;
            }
        }

        $article->addMedia($source)
            ->preservingOriginal()
            ->withCustomProperties(['seeded' => true, 'sha1' => sha1_file($source)])
            ->toMediaCollection(NewsArticle::COVER);
    }

    protected function seedAdmin(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bcci.bg'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );
    }
}
