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
                    'Съветът по изкуствен интелект към БТПП работи за повишаване на конкурентоспособността на българския бизнес и за позиционирането на България сред лидерите в областта на изкуствения интелект.',
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
                'copyright' => $t('© 2026 Съвет по изкуствен интелект към БТПП', '© 2026 AI Council at BCCI'),
            ],

            'home' => [
                'hero_eyebrow' => $t('СЪВЕТ ПО ИЗКУСТВЕН ИНТЕЛЕКТ КЪМ БТПП', 'AI COUNCIL AT BCCI'),
                'hero_title' => $t('Изкуственият интелект отдавна не е бъдещето. Той е настоящето.', 'Artificial intelligence is no longer the future. It is the present.'),
                'hero_intro' => $t('Работим за повишаване на конкурентоспособността на българския бизнес и за позиционирането на България сред лидерите в една от най-перспективните области на иновациите.', 'We work to strengthen the competitiveness of Bulgarian business and to position Bulgaria among the leaders in one of today’s most promising fields of innovation.'),
                'cta_primary' => $t('Участвайте в проучването', 'Take part in the survey'),
                'cta_secondary' => $t('За Съвета', 'About the Council'),
                'pillars_title' => $t('ТРИ НАПРАВЛЕНИЯ', 'THREE PILLARS'),
                'pillars' => [
                    ['num' => '01', 'title' => $t('Конкурентоспособност на бизнеса', 'Business competitiveness'), 'text' => $t('Консултации, експертиза и подкрепа за успешното внедряване на AI.', 'Consulting, expertise and support for successful AI adoption.')],
                    ['num' => '02', 'title' => $t('Образование и човешки капацитет', 'Education and human capacity'), 'text' => $t('Партньорства с университети, обучения и менторски програми.', 'University partnerships, trainings and mentorship programmes.')],
                    ['num' => '03', 'title' => $t('Технологична политика', 'Technology policy'), 'text' => $t('Становища по регулаторни инициативи на национално и европейско ниво.', 'Positions on regulatory initiatives at national and European level.')],
                ],
                'intro_title' => $t('Хора и технологии, заедно', 'People and technology, together'),
                'intro_body' => $t(
                    '<p>Не става въпрос за замяна на хората от машини, а за повишаване ефективността на всеки член на екипа — от мениджърите до всяко ниво в организацията. Ще бъдем в тясна комуникация с българските компании, ще отговаряме на въпросите им и ще търсим решения, повишаващи тяхната ефективност.</p><p>Желанието ни е да обединим гласовете на бизнеса, образованието, науката и държавните институции, за да позиционираме България като един от лидерите в света на бъдещето.</p>',
                    '<p>This is not about machines replacing people — it is about making every member of the team more effective, from managers to every level of the organisation. We stay in close contact with Bulgarian companies, answer their questions and look for solutions that raise their efficiency.</p><p>Our ambition is to unite the voices of business, education, science and public institutions, positioning Bulgaria among the leaders of the world of tomorrow.</p>'
                ),
                'news_title' => $t('Последни новини', 'Latest news'),
                'quote_eyebrow' => $t('ИЗ СТАНОВИЩЕ НА СЪВЕТА', 'FROM A COUNCIL POSITION'),
                'quote_text' => $t('Нашата позиция е базирана на балансиран подход — правила, които защитават обществения интерес, без да спират иновациите.', 'Our position is based on a balanced approach — rules that protect the public interest without stifling innovation.'),
                'meta_title' => $t('Съвет по изкуствен интелект — БТПП', 'AI Council — Bulgarian Chamber of Commerce and Industry'),
            ],

            'about' => [
                'hero_eyebrow' => $t('ЗА НАС', 'ABOUT US'),
                'hero_title' => $t('Консултативен орган към Българската търговско-промишлена палата', 'An advisory body to the Bulgarian Chamber of Commerce and Industry'),
                'hero_intro' => $t('Съветът по изкуствен интелект е създаден от експерти с убеждението, че AI технологиите ще играят ключова роля в дигиталната трансформация на бизнеса.', 'The AI Council was created by experts convinced that AI technologies will play a key role in the digital transformation of business.'),
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
                'hero_intro' => $t('А какви са нагласите на студентите? Съветът по изкуствен интелект към БТПП създаде мащабно национално проучване, което за първи път ще даде цялостна картина на нагласите, очакванията и предизвикателствата пред българските компании в контекста на навлизането на AI технологиите.', 'And what about students? The AI Council at BCCI has launched a large-scale national survey that will, for the first time, give a complete picture of the attitudes, expectations and challenges facing Bulgarian companies as AI technologies take hold.'),
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
                'hero_intro' => $t('Съветът по изкуствен интелект е част от Българската търговско-промишлена палата и работи с организации, които споделят нашата визия за технологично грамотна и конкурентоспособна България.', 'The AI Council is part of the Bulgarian Chamber of Commerce and Industry and works with organisations that share our vision of a technologically literate and competitive Bulgaria.'),
                'intro' => $t('Нашите партньори включват водещи бизнес организации, университети, технологични компании и международни участници в AI екосистемата.', 'Our partners include leading business organisations, universities, technology companies and international players in the AI ecosystem.'),
                'join_title' => $t('Искате да се присъедините към мрежата ни?', 'Want to join our network?'),
                'join_text' => $t('Ако вашата организация споделя нашата визия — свържете се с нас.', 'If your organisation shares our vision — get in touch.'),
                'join_button' => $t('Свържете се с нас', 'Get in touch'),
            ],

            'news' => [
                'hero_eyebrow' => $t('НОВИНИ', 'NEWS'),
                'hero_title' => $t('Последно от Съвета', 'Latest from the Council'),
                'hero_intro' => $t('Новини, събития и инициативи на Съвета по изкуствен интелект.', 'News, events and initiatives of the AI Council.'),
            ],

            'contacts' => [
                'hero_eyebrow' => $t('КОНТАКТИ', 'CONTACT'),
                'hero_title' => $t('Свържете се с нас', 'Get in touch'),
                'hero_intro' => $t('Имате въпрос, идея или искате да се включите в работата на Съвета по изкуствен интелект?', 'Have a question or an idea, or want to get involved in the work of the AI Council?'),
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
                'slug' => 'stanovishte-ai-act',
                'date' => '2026-07-15',
                'title' => $this->t('Съветът представи становище по прилагането на европейския Акт за изкуствения интелект', 'The Council presented a position on the implementation of the European AI Act'),
                'excerpt' => $this->t('Становището подчертава нуждата от предвидими правила и ясни насоки за българските компании при прилагането на новата европейска регулация.', 'The position highlights the need for predictable rules and clear guidance for Bulgarian companies in applying the new European regulation.'),
                'body' => $this->t(
                    '<p>Становището подчертава нуждата от предвидими правила и ясни насоки за българските компании при прилагането на новата европейска регулация. Съветът настоява за подход, който защитава обществения интерес, без да спира иновациите.</p><p>Документът е адресиран до регулатори, законодатели и институции и се основава на реалния опит на бизнеса. <em>(примерен текст)</em></p>',
                    '<p>The position highlights the need for predictable rules and clear guidance for Bulgarian companies applying the new European regulation. The Council calls for an approach that protects the public interest without stifling innovation.</p><p>The document is addressed to regulators, legislators and institutions and is grounded in the real experience of business. <em>(sample text)</em></p>'
                ),
            ],
            [
                'slug' => 'natsionalno-prouchvane-ai',
                'date' => '2026-07-02',
                'title' => $this->t('Стартира националното проучване за нагласите на бизнеса към AI технологиите', 'The national survey on business attitudes towards AI technologies is launched'),
                'excerpt' => $this->t('Проучването ще обхване компании от всички сектори и региони, както и студенти от водещи университети.', 'The survey will cover companies from all sectors and regions, as well as students from leading universities.'),
                'body' => $this->t(
                    '<p>Проучването ще обхване компании от всички сектори и региони, както и студенти от водещи университети. За първи път то ще даде цялостна картина на нагласите, очакванията и предизвикателствата пред българските компании.</p><p>Резултатите ще бъдат отворени и достъпни за бизнеса, изследователите, политиците и обществото. <em>(примерен текст)</em></p>',
                    '<p>The survey will cover companies from all sectors and regions, as well as students from leading universities. For the first time it will give a complete picture of the attitudes, expectations and challenges facing Bulgarian companies.</p><p>The results will be open and available to business, researchers, policymakers and society. <em>(sample text)</em></p>'
                ),
            ],
            [
                'slug' => 'partnyorstvo-universiteti',
                'date' => '2026-06-18',
                'title' => $this->t('Ново партньорство с водещи университети за съвместни AI обучения', 'New partnership with leading universities for joint AI training'),
                'excerpt' => $this->t('Партньорството предвижда съвместни курсове, практически обучения и менторски програми през следващата академична година.', 'The partnership envisions joint courses, hands-on training and mentorship programmes in the coming academic year.'),
                'body' => $this->t(
                    '<p>Партньорството предвижда съвместни курсове, практически обучения и менторски програми през следващата академична година. Целта е по-добра подготовка на българските студенти и по-конкурентни кадри за бизнеса.</p><p>Инициативата е част от усилията на Съвета за по-широко навлизане на обучението по изкуствен интелект. <em>(примерен текст)</em></p>',
                    '<p>The partnership envisions joint courses, hands-on training and mentorship programmes in the coming academic year. The goal is better preparation for Bulgarian students and more competitive talent for business.</p><p>The initiative is part of the Council’s efforts to broaden AI education. <em>(sample text)</em></p>'
                ),
            ],
        ];

        foreach ($news as $n) {
            NewsArticle::updateOrCreate(
                ['slug' => $n['slug']],
                [
                    'title' => $n['title'],
                    'excerpt' => $n['excerpt'],
                    'body' => $n['body'],
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
