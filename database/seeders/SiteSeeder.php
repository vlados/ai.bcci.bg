<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Position;
use App\Models\TeamMember;
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

    protected function seedTeam(): void
    {
        $roles = [
            $this->t('Председател', 'Chair'),
            $this->t('Заместник-председател', 'Deputy Chair'),
            $this->t('Член — технологичен сектор', 'Member — technology sector'),
            $this->t('Член — право', 'Member — law'),
            $this->t('Член — наука', 'Member — science'),
            $this->t('Член — образование', 'Member — education'),
        ];

        foreach ($roles as $i => $role) {
            TeamMember::updateOrCreate(
                ['name' => 'Име Фамилия', 'sort_order' => $i + 1],
                ['role' => $role, 'sort_order' => $i + 1],
            );
        }
    }

    protected function seedPartners(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Partner::updateOrCreate(
                ['name' => 'Партньор '.$i],
                ['sort_order' => $i],
            );
        }
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

        // Cover images: these articles are data journalism, not photo stories.
        // Left null deliberately rather than dressing them with stock imagery.
        $images = [];

        foreach ($news as $n) {
            NewsArticle::updateOrCreate(
                ['slug' => $n['slug']],
                [
                    'title' => $n['title'],
                    'excerpt' => $n['excerpt'],
                    'body' => $n['body'],
                    'meta_description' => $n['meta_description'] ?? null,
                    'image_url' => $images[$n['slug']] ?? null,
                    'published_at' => $n['date'],
                    'is_published' => true,
                ],
            );
        }
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
