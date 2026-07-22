# SEO + GEO audit: `aicouncil.hosting.vladko.dev`

**Audit date:** 22 July 2026  
**Scope:** Bulgarian and English rendered pages, current official BCCI references, the Council’s existing campaign domains, visible content architecture, search positioning, and current Google/OpenAI guidance.

## Executive verdict

The website has a sensible information architecture, a clear institutional mission, a workable Bulgarian/English URL structure, and an unusually strong potential authority source: its affiliation with the Bulgarian Chamber of Commerce and Industry.

It is nevertheless **not ready for a production launch or migration in its current state**.

The central problem is not design, metadata, or even page speed. It is **lack of verifiable substance**. The public pages currently contain:

- Sample chart data.
- Placeholder team members and photographs.
- Placeholder partner names.
- Articles explicitly labelled as sample text.
- Unlinked position titles without supporting documents.
- Educational initiatives that are still described only as future plans.

These defects are especially damaging for an institutional website. Search engines and generative systems need to determine who the organization is, who is responsible for its claims, what primary evidence supports those claims, and which version of the organization’s content is canonical. At present, the site answers those questions poorly.

The highest-priority recommendation is:

> **Use `ai.bcci.bg` as the canonical production property, consolidate the duplicate campaign domains, remove every placeholder, and reorganize the site around real experts, real policy documents, and original Bulgarian AI adoption data.**

The website’s strongest future SEO and GEO assets are not routine blog posts. They are:

1. The Council’s official institutional mandate.
2. Its named experts.
3. Its policy positions and legislative participation.
4. The “AI in Bulgarian Business 2026” research and resulting dataset.
5. Practical, Bulgaria-specific AI Act and AI adoption guidance.

---

## Qualitative scorecard

These are strategic audit scores, not Lighthouse or Search Console measurements.

| Area | Current assessment |
|---|---:|
| Institutional authority potential | **8/10** |
| Navigation and information architecture | **6/10** |
| On-page SEO | **5/10** |
| Content completeness and depth | **2/10** |
| Trust and institutional evidence | **2/10** |
| Bulgarian–English implementation | **4/10** |
| GEO and citation readiness | **2/10** |
| Technical SEO | **Unverified** |
| Production launch readiness | **3/10** |

“Technical SEO” is deliberately not scored. I could not independently verify raw response headers, canonical tags, `hreflang`, `robots.txt`, XML sitemaps, JSON-LD, status-code behaviour, Search Console data, analytics, server logs, or field Core Web Vitals. Treating an unverified item as missing would be unjustified; treating it as complete would be equally unjustified.

---

# 1. What is already working

Several foundations are sound:

- The top-level navigation is shallow and comprehensible.
- The Bulgarian site is at the root and English pages use `/en/`, which is a reasonable multilingual architecture.
- The website has a skip-to-content link.
- The content is organized around three intelligible pillars: business competitiveness, education and human capacity, and technology policy.
- The contact address and BCCI connection are consistently visible.
- The homepage has clear calls to action for the survey and Council information.
- The rendered content is retrievable, reducing—but not eliminating—the risk of serious JavaScript rendering problems.

The external authority foundation is stronger than the website communicates. BCCI pages describe the Council as a consultative and expert body, identify named participants, and document its involvement in AI policy discussions. The official LinkedIn profile identifies `ai.bcci.bg` as the Council’s website.

That means the project does not need to manufacture authority. It needs to **consolidate, document, and expose authority that already exists**.

---

# 2. P0 launch blockers

## 2.1 Canonical domain ownership is fragmented

The strongest technical and strategic risk is the current three-host situation:

- The redesign is publicly accessible at `aicouncil.hosting.vladko.dev`.
- The Council’s official LinkedIn profile identifies `ai.bcci.bg` as the official website.
- The survey campaign exists at both `ai.bcci.bg/ai-business-2026` and `prouchvane.bg/ai-business-2026`, including corresponding survey paths.
- The redesign’s survey page links users to `prouchvane.bg`.

This creates four risks:

1. Search signals and backlinks can be divided between domains.
2. Search engines may choose a canonical version you did not intend.
3. LLMs may cite the vanity domain, development host, or old official page inconsistently.
4. Analytics and conversion attribution become fragmented.

### Required architecture

Treat `ai.bcci.bg` as the canonical production property, assuming the redesign is intended to replace the current official presence.

Recommended mapping:

```text
aicouncil.hosting.vladko.dev/*  →  https://ai.bcci.bg/*
prouchvane.bg/ai-business-2026  →  https://ai.bcci.bg/ai-business-2026
prouchvane.bg/ai-business-2026/survey
                               →  https://ai.bcci.bg/ai-business-2026/survey
```

Use permanent, server-side, one-to-one HTTP `301` or `308` redirects. Preserve the existing campaign paths instead of renaming them unnecessarily.

Google treats permanent redirects and `rel="canonical"` as strong canonical signals, while sitemap inclusion is weaker. It also recommends self-referential canonicals and consistent internal linking to the canonical URLs.

### Development environment

Until migration, protect the development host with authentication or an effective `noindex` directive. Do not rely only on `robots.txt`: a robots exclusion can prevent crawling while still allowing a URL to appear in search results based on external links.

If the development URLs have already received backlinks or indexation, redirect every URL to its corresponding production URL after launch. Do not simply shut down the host or redirect every page to the homepage.

---

## 2.2 Remove all sample and placeholder content

The homepage visibly labels its chart as sample data. The About page has six “Име Фамилия” team entries. The Partners page shows “Партньор 1” through “Партньор 8.” The news articles contain only a few paragraphs and explicitly state “примерен текст” or “sample text.”

This is more serious than ordinary unfinished copy:

- It signals that the institution itself may not be fully real or operational.
- It prevents validation of expertise and authorship.
- It creates thin pages with virtually no independent search value.
- It makes the content unsuitable for reliable citation by Google AI features, ChatGPT, Gemini, Copilot, or Perplexity.
- It may generate misleading snippets if indexed before completion.

### Launch rule

Do not publish any page with:

- Placeholder names.
- Placeholder partner logos.
- Sample or invented statistics.
- Sample news.
- Unverified “leading,” “first,” “national,” or similar superiority claims.
- Position titles that do not correspond to real, accessible documents.

Where final content is unavailable, remove the section or keep the entire page out of the production index. A clean site with six substantive pages is better than a 20-page institutional shell.

---

## 2.3 Establish one formal entity name

The current ecosystem uses several variants:

- “Съвет по изкуствен интелект към БТПП”
- “Съветът за изкуствен интелект към БТПП”
- “Съвет за AI”
- “AI Council”
- “AI Council at BCCI”

BCCI’s own publications and the LinkedIn profile do not use the wording completely consistently.

This weakens entity resolution. It also creates ambiguity with the European Commission’s “Съвет по ИИ,” meaning the EU AI Board.

### Required decision

Confirm the legally or formally approved name from the Council’s founding resolution or terms of reference.

Then use it consistently in:

- Homepage H1.
- Page titles.
- About-page opening sentence.
- Footer.
- Structured data.
- BCCI pages.
- LinkedIn.
- News bylines.
- PDF covers.
- Media releases.
- Partner websites.

A likely canonical form is:

**Bulgarian:**  
`Съвет по изкуствен интелект към Българската търговско-промишлена палата`

**English:**  
`AI Council at the Bulgarian Chamber of Commerce and Industry`

Use the shorter variants as `alternateName` values in structured data.

The About page should explicitly state that the Council is an advisory and expert body at BCCI, not a government regulator and not the EU AI Board.

---

## 2.4 Publish real governance and expert information

The website claims that the Council includes representatives of technology, education, science, law, and entrepreneurship, but the actual roster is not present.

For an expert-policy body, generic role labels are insufficient. Every member should have a dedicated profile containing:

- Full name.
- Official Council role.
- Current professional position.
- Relevant AI, legal, academic, policy, or business expertise.
- Short biography.
- Selected publications or projects.
- Disclosure of relevant commercial interests where appropriate.
- Links to verified professional profiles.
- Portrait with permission and credit.
- Articles, positions, and events authored or reviewed by that person.

The governance section should include:

- Founding date.
- Founding or appointment instrument.
- Terms of reference.
- Organizational relationship with BCCI.
- Appointment and removal process.
- Decision-making or approval process for official positions.
- Funding and sponsorship model.
- Conflict-of-interest policy.
- Editorial and corrections policy.
- Contact person or secretariat.

This is not merely “E-E-A-T decoration.” It gives search systems a factual graph connecting the organization, parent organization, members, publications, events, and policy documents.

---

## 2.5 Fix English localization before indexation

The English About page still uses the Bulgarian placeholder “Име Фамилия,” and the English Partners page still shows “Партньор 1” through “Партньор 8.” The English news pages reproduce the same sample-content problem as Bulgarian.

This is not only a translation defect. It signals an incomplete or automated localization process.

Before launch:

- Perform a page-by-page bilingual content parity audit.
- Make the language switcher lead to the corresponding translated page, not merely the other-language homepage.
- Use reciprocal `hreflang="bg"` and `hreflang="en"` references.
- Consider `x-default` only for a genuine language-selection or default entry page.
- Give each language version a canonical URL in its own language.
- Set the correct `<html lang>` attribute.
- Do not attach English `hreflang` to untranslated or partially translated pages.

Google requires localized alternatives to reference one another consistently, and canonicalization should remain within the same language where possible.

---

# 3. Page-by-page audit

## Homepage

### Current strengths

- Clear mission and three strategic pillars.
- Useful primary calls to action.
- BCCI association appears immediately.
- A skip link is present.
- Current news and the survey are discoverable from the homepage.

### Problems

#### The H1 targets a slogan rather than the entity

The current H1 is:

> “Изкуственият интелект отдавна не е бъдещето. Той е настоящето.”

The entity name appears above it as a smaller preheading.

This reverses the ideal hierarchy for both brand/entity search and machine understanding.

**Recommended H1:**

> Съвет по изкуствен интелект към БТПП

Keep the existing slogan as the supporting headline or subtitle.

#### The sample chart must be removed

A chart presented as “Примерни данни — илюстрация” contributes no evidence and invites confusion. Replace it with one of the following:

- Verified data from the national survey.
- A live counter with documented methodology.
- No chart until verified data exist.

Every number should include:

- Source.
- Measurement date.
- Population/sample.
- Definition.
- Link to methodology.

#### The homepage lacks visible proof

Add a compact proof section near the top:

- “Established by BCCI on [verified date].”
- Number of verified Council experts.
- Number of published positions.
- Number of partner organizations.
- Link to the Council’s mandate.
- Link to the latest official position.
- Link to the survey methodology.

Do not publish numerical proof until each figure is auditable.

#### The quote is not connected to a source

The “Из становище на Съвета” block should identify and link to the exact position, publication date, and document version.

---

## About

The About page explains the mission and subject areas well, but the team section is entirely placeholder content.

### Add these sections

1. **What the Council is**
2. **What it is not**
3. **Legal or organizational basis**
4. **Relationship with BCCI**
5. **Leadership and members**
6. **How positions are approved**
7. **Annual priorities**
8. **Transparency and conflicts**
9. **Secretariat and contact**
10. **Chronology of major activities**

BCCI already has public material documenting the Council’s creation, purpose, named representatives, and policy participation. The new website should consolidate that material rather than forcing search engines and visitors to reconstruct the entity from third-party pages.

---

## Education

The page presents four medium-term plans—joint courses, practical training, mentoring, and certification—but finishes with “очаквайте скоро.” It has no course dates, instructors, learning outcomes, delivery format, registration, or named partners.

That makes it a roadmap page, not a useful education landing page.

### Two defensible options

**Option A: No real programs yet**

Keep a concise “Education strategy” page but do not optimize it as though courses exist. Add:

- Current status.
- Responsible working group.
- Target launch period.
- Contact or expression-of-interest form.
- Named partners only after confirmation.
- Publicly available resources in the meantime.

**Option B: Programs are confirmed**

Create individual pages for every course or event containing:

- Audience.
- Prerequisites.
- Learning outcomes.
- Curriculum.
- Instructor biographies.
- Dates and duration.
- In-person/online format.
- Location.
- Price or free-status.
- Registration deadline.
- Certificate terms.
- Organizer and partner responsibilities.

Use `Course` and `Event` structured data only when those details are visible and accurate.

---

## Positions

The Positions page currently lists four titles and dates, but the rendered page does not expose detail links, summaries, authors, supporting documents, or downloadable PDFs.

This is one of the largest missed opportunities. An actual BCCI position on the proposed Bulgarian AI law is already hosted by the National Assembly. The document contains detailed analysis and concrete recommendations, including regulatory and implementation proposals.

### Required publication model

Each position should have its own permanent HTML page:

```text
/positions/[descriptive-slug]/
```

The page should include:

1. A 40–80 word executive answer.
2. Document status: draft, approved, submitted, or final.
3. Publication and submission dates.
4. Recipient institution or consultation.
5. Council authors and expert reviewers.
6. Main recommendations.
7. Expected effect on Bulgarian companies.
8. Legal and policy background.
9. Primary sources.
10. Version history.
11. Signed or final PDF.
12. Link to the official parliamentary or regulatory record.
13. Related news, events, and analysis.

The HTML page should be the primary searchable and citeable version. The PDF should remain available as the formal document. If the PDF substantially duplicates the HTML, use an HTTP `Link` canonical header according to the chosen indexing strategy. Google supports canonical headers for non-HTML files such as PDFs.

---

## Survey

The survey is potentially the most valuable long-term SEO and GEO asset. The site promises a broad national picture and open results, but the current landing page does not expose methodology, questionnaire, field dates, sample design, closing date, limitations, privacy details, or current response counts. The participation CTA leads to `prouchvane.bg`.

### Pre-fieldwork page requirements

- Exact research objective.
- Target populations: companies, students, or both.
- Sampling and distribution approach.
- Eligibility criteria.
- Questionnaire length.
- Fieldwork start and end dates.
- Research partners.
- Data controller.
- Privacy and retention policy.
- Whether responses are anonymous.
- Planned publication date.
- Contact for methodology questions.

The claim that this will provide a complete picture “for the first time” should either be supported by a documented review of earlier Bulgarian studies or softened.

### Results publication requirements

Build a research hub with:

- Executive summary.
- Methodology report.
- Sample profile.
- Weighted and unweighted figures where relevant.
- Limitations and non-response caveats.
- Accessible charts.
- HTML data tables.
- Downloadable CSV, JSON, XLSX, and PDF.
- Questionnaire.
- Data dictionary.
- Recommended citation.
- Named researchers and reviewers.
- `Dataset` structured data.
- Permanent versioned URLs.

This is what creates a citation moat. Hundreds of generic AI articles will not distinguish the Council; an authoritative Bulgarian dataset will.

---

## Partners

The page claims relationships with leading business organizations, universities, technology companies, and international AI participants, but shows eight placeholders.

Do not launch this page until every listed relationship is real and approved.

For each partner, show:

- Official name and logo.
- Type of organization.
- Nature of the partnership.
- Specific joint project or contribution.
- Start date if public.
- Link to an independent announcement on the partner’s website.

A logo wall alone has limited SEO value. A verifiable relationship description contributes to entity trust.

---

## News

The existing articles are too thin to function as useful news or analysis. The AI Act article repeats nearly the same sentence and then labels the remainder as sample text. The English survey and university partnership articles have the same problem.

### Minimum article standard

Every real article should include:

- Descriptive title.
- Publication date and updated date.
- Named author.
- Expert reviewer where appropriate.
- 40–60 word summary.
- Substantive body content.
- Links to original documents.
- Sources and external references.
- Original photography or credited media.
- Related position, event, expert, or research page.
- `NewsArticle` structured data.

Do not use news pages merely to announce that another page exists. The article should add context: what happened, why it matters, who is affected, and what happens next.

---

## Contact and footer

The visible site provides BCCI’s address, newsletter connection, email/telephone details on the contact area, and a clear institutional association. However, the parsed footer does not visibly expose links to privacy, cookies, terms, accessibility, editorial standards, or corrections.

Add:

- Privacy notice.
- Cookie settings and notice where required.
- Terms of use.
- Accessibility statement.
- Editorial and corrections policy.
- Data-controller information.
- Media contact.
- Survey-specific privacy information.

Manually validate that the contact form has programmatic labels, clear validation messages, keyboard operation, error recovery, consent wording, and spam protection that does not block accessibility.

---

# 4. On-page SEO recommendations

## Titles, H1s, and intent alignment

The current copy often uses inspirational headings where a precise entity or query-focused heading would be stronger.

Recommended examples:

| Page | Suggested title | Suggested H1 |
|---|---|---|
| Bulgarian homepage | **Съвет по изкуствен интелект към БТПП \| AI за бизнеса** | **Съвет по изкуствен интелект към БТПП** |
| English homepage | **AI Council at BCCI \| AI Adoption, Policy and Skills** | **AI Council at the Bulgarian Chamber of Commerce and Industry** |
| About | **За Съвета по изкуствен интелект към БТПП** | **За Съвета по изкуствен интелект към БТПП** |
| Education | **AI обучения за бизнеса и образованието \| БТПП** | **Обучения и умения за работа с изкуствен интелект** |
| Positions | **Становища за AI Act и технологична политика \| БТПП** | **Становища по изкуствен интелект и технологична политика** |
| Survey | **AI в българския бизнес 2026 — национално проучване** | **Изкуственият интелект в българския бизнес 2026** |
| News | **Новини и анализи за изкуствен интелект \| БТПП** | **Новини и анализи** |
| Contact | **Контакти \| Съвет по изкуствен интелект към БТПП** | **Свържете се със Съвета** |

### Homepage meta description example

> Експертна платформа към БТПП за внедряване на изкуствен интелект, AI Act, обучения, становища и данни за българския бизнес.

### English homepage meta description

> BCCI’s expert platform for responsible AI adoption, AI Act guidance, skills, policy positions and original data on Bulgarian business.

Meta descriptions should be written for click qualification, not keyword repetition.

---

## Internal linking

Create clear relationships among content types:

- Every news article about a position should link to the complete position.
- Every position should link to related experts, legislation, and news.
- Every expert profile should list authored or reviewed publications.
- Survey articles should link to methodology and results.
- AI Act guidance should link to relevant Council positions.
- Course pages should link to instructor profiles and related resources.
- Partner pages should link to joint initiatives, not only partner homepages.

Use descriptive anchors such as:

- “Становище по проекта на Закон за изкуствения интелект”
- “Методология на проучването AI в българския бизнес 2026”
- “Практическо ръководство за AI Act за МСП”

Avoid repeated generic anchors such as “Научете повече.”

---

## Breadcrumbs

Add visible breadcrumbs and `BreadcrumbList` markup to all deeper pages:

```text
Начало → Становища → Закон за използването и развитието на ИИ
```

This improves orientation, internal linking, and entity relationships.

---

# 5. Search positioning and content strategy

## The positioning problem

The phrase “AI Council” or “Съвет по ИИ” is generic and overlaps with the European Union’s official AI governance body. Meanwhile, AI Act search results already include detailed legal guidance, practical compliance content, specialist training, and broader research about AI adoption.

The Council will not win through broad slogans such as “AI is the present.” It can win through a narrower, defensible proposition:

> **The primary BCCI-backed source for the adoption, governance, skills and economic impact of artificial intelligence in Bulgarian business.**

## Recommended topic clusters

### 1. Brand and institutional entity

Target concepts:

- Съвет по изкуствен интелект към БТПП
- Съвет за изкуствен интелект БТПП
- AI Council BCCI
- AI Council Bulgaria
- експерти по изкуствен интелект БТПП

Primary assets:

- Homepage.
- About.
- Governance.
- Expert profiles.
- Press and activity chronology.

### 2. AI Act for Bulgarian companies

Target concepts:

- AI Act България
- Акт за изкуствения интелект за бизнеса
- AI Act за МСП
- задължения по AI Act
- AI literacy обучение
- високорискови AI системи
- доставчик и внедрител на AI система
- AI policy за компания

Primary assets:

- Comprehensive business guide.
- Implementation timeline.
- Role/risk-classification tool.
- AI literacy guide.
- Internal AI-use policy template.
- Procurement checklist.
- Council positions.

### 3. AI adoption by SMEs and enterprises

Target concepts:

- изкуствен интелект за бизнеса
- AI за малък бизнес
- внедряване на AI в компания
- AI стратегия за фирма
- оценка на готовността за AI
- автоматизация с AI
- избор на AI доставчик

Primary assets:

- AI readiness assessment.
- Sector-specific case studies.
- Business implementation framework.
- Vendor due-diligence checklist.
- ROI and risk worksheet.

### 4. Education and AI literacy

Target concepts:

- AI обучение за бизнеса
- курс по AI за мениджъри
- AI literacy обучение
- обучение по AI Act
- изкуствен интелект за служители
- отговорно използване на генеративен AI

Primary assets:

- Real course pages.
- Event calendar.
- Trainer profiles.
- Downloadable learning resources.
- Recorded webinars and transcripts.

### 5. Bulgarian AI research and statistics

Target concepts:

- AI в българския бизнес проучване
- използване на AI в България статистика
- AI adoption Bulgaria
- нагласи на бизнеса към AI
- изкуствен интелект български компании

Primary assets:

- `AI Business 2026` methodology.
- Interactive results.
- Open dataset.
- Sector and regional breakdowns.
- Annual comparisons.

### 6. Technology policy

Target concepts:

- политика за изкуствен интелект България
- закон за изкуствения интелект България
- национална стратегия за AI
- регулаторен пясъчник AI
- финансиране на AI иновации

Primary assets:

- Position papers.
- Consultation responses.
- Legislative explainers.
- Policy trackers.
- Public-event summaries.

These are topic opportunities, not search-volume estimates. Query demand should be validated through Search Console, Google Trends, paid keyword datasets, stakeholder interviews, and the survey itself.

---

# 6. Recommended information architecture

Preserve any existing indexed URL that already has equity, particularly the campaign paths.

```text
/
├── about/
│   ├── governance/
│   ├── transparency/
│   └── history/
├── experts/
│   └── [expert-name]/
├── ai-adoption/
│   ├── readiness-assessment/
│   ├── sector-guides/
│   ├── case-studies/
│   └── tools-and-templates/
├── ai-act/
│   ├── business-guide/
│   ├── implementation-timeline/
│   ├── ai-literacy/
│   ├── risk-classification/
│   └── frequently-asked-questions/
├── education/
│   ├── courses/
│   ├── events/
│   └── resources/
├── positions/
│   └── [position-slug]/
├── ai-business-2026/
│   ├── survey/
│   ├── methodology/
│   ├── results/
│   └── data/
├── news/
├── partners/
├── press/
└── contact/
```

Mirror only genuine English equivalents under `/en/`. Partial or unmaintained English sections are worse than a smaller, fully maintained English site.

---

# 7. GEO and AI-search analysis

## GEO is not a separate shortcut

Google’s current guidance is explicit that generative search features remain grounded in the ordinary search index and core ranking systems. Google recommends foundational SEO and unique expert-led content rather than special “AEO/GEO hacks.” It also says that `llms.txt` is ignored by Google and that content does not need to be artificially divided into tiny “AI-friendly” chunks.

Therefore, the correct GEO strategy is:

1. Make the entity unambiguous.
2. Make the pages crawlable and canonical.
3. Publish original, non-commodity evidence.
4. Attach claims to named experts and primary sources.
5. Structure pages so answers can be extracted without losing context.
6. Maintain dates, versions, and stable URLs.

## Current GEO weaknesses

The current site lacks most of the attributes that make a page safe to cite:

- Named authors.
- Named reviewers.
- Real expert profiles.
- Supporting sources.
- Methodologies.
- Unique numerical evidence.
- Version history.
- Linked primary documents.
- Substantive explanations.
- Complete institutional governance.
- Consistent entity naming.

A model can summarize the mission statement. It has very little reason to cite the website when answering a substantive question such as:

- “What does the Bulgarian business community recommend about AI regulation?”
- “How many Bulgarian companies use generative AI?”
- “What does the AI Act require from a Bulgarian SME?”
- “Who are the AI Council’s experts?”
- “What AI training does BCCI provide?”

## Answer-first content structure

Every important guide, research report, or position should begin with a concise, independently understandable summary:

```text
Direct answer
Key findings or recommendations
Who is affected
Effective/as-of date
Source and methodology
Detailed analysis
Limitations
Author and reviewer
Primary references
Version history
```

This is not artificial “chunking.” It is sound editorial structure.

## Build a citation graph

Connect the entities explicitly:

```text
BCCI
  └── AI Council
       ├── Council member
       │    └── authored/reviewed position
       ├── research project
       │    ├── methodology
       │    ├── dataset
       │    └── results
       ├── partner organization
       │    └── joint course/event
       └── policy position
            ├── legislation
            ├── recipient institution
            └── official submitted PDF
```

Use consistent names, IDs, URLs, and structured data throughout.

## Original data is the strongest GEO opportunity

The survey should become the source that other organizations, journalists, researchers, and LLMs cite when discussing Bulgarian AI adoption.

To achieve this, publish:

- Stable annual editions.
- Clear methodology.
- Machine-readable data.
- Permalinks to individual charts and findings.
- HTML tables alongside visual charts.
- Explicit measurement dates.
- Definitions for every metric.
- Sector and size breakdowns.
- Limitations.
- Citation text.
- Named researchers.
- Archived previous versions.

A claim such as “34% of surveyed Bulgarian SMEs use generative AI as of Q3 2026” is citeable only when the site explains what “use,” “SME,” “surveyed,” and the sample itself mean.

## ChatGPT discoverability

OpenAI’s current publisher guidance says that sites should allow `OAI-SearchBot` if they want their content included in ChatGPT search summaries and snippets. `GPTBot` is a separate control related to potential training, so search discoverability and training policy can be decided independently. OpenAI also states that ChatGPT referral URLs include `utm_source=chatgpt.com`, allowing attribution in analytics.

A possible production policy, subject to BCCI’s content-governance decision, is:

```text
User-agent: OAI-SearchBot
Allow: /

User-agent: GPTBot
Disallow: /
```

This example permits ChatGPT search retrieval while opting out of GPTBot training access. It should be implemented only after the organization makes an explicit policy decision.

## Accessibility is also GEO infrastructure

OpenAI recommends accessible semantic structure and ARIA where appropriate because it helps agents understand and operate websites.

Validate:

- Semantic headings.
- Meaningful link text.
- Programmatic form labels.
- Keyboard navigation.
- Visible focus states.
- Correct button states.
- Alternative text.
- Table headers.
- Chart text equivalents.
- Mobile navigation exposure to assistive technology.
- Language changes.
- Error and success messages.

The parsed homepage exposes two copies of the navigation, probably desktop and mobile variants. Confirm that only the active version is exposed to assistive technologies and that hidden navigation is genuinely hidden, not merely visually positioned off-screen.

## `llms.txt`

It is acceptable to create `llms.txt` experimentally for systems that may use it, but it should be a **P3 task**, not part of the launch-critical path. Google explicitly says it ignores the file.

Do not let `llms.txt`, AI summaries, or special Markdown copies become substitutes for:

- Canonical HTML.
- XML sitemaps.
- Real authors.
- Source links.
- Structured data.
- Primary evidence.
- Good accessibility.

---

# 8. Structured data blueprint

Structured data should reflect visible, verified content. It should never be used to turn placeholder material into apparently legitimate entities. Google describes structured data as a mechanism for helping its systems understand page content, but the markup must correspond to the actual page.

| Content type | Recommended schema |
|---|---|
| Homepage/About | `Organization`, `WebSite` |
| Council member | `ProfilePage` + `Person` |
| News article | `NewsArticle` |
| Analytical guide | `Article` |
| Policy position | `Report`, `DigitalDocument`, or `Article` as contextually appropriate |
| Research results | `Dataset` |
| Training | `Course` |
| Seminar/conference | `Event` |
| Interior pages | `BreadcrumbList` |

## Organization entity fields

The organization markup should include:

- Persistent `@id`, for example `https://ai.bcci.bg/#organization`.
- Verified formal name.
- `alternateName` values.
- Canonical `url`.
- Logo.
- Description.
- Founding date, if formally established and public.
- Email and telephone.
- Postal address.
- `parentOrganization` referencing BCCI.
- `sameAs` for LinkedIn and relevant official profiles.
- `member` or related expert entities only where accurate.

Do not create fake `aggregateRating`, FAQ, course, partner, or founder properties.

---

# 9. Technical SEO acceptance checklist

Because the following could not be inspected directly, all should be treated as launch acceptance tests.

## Crawl and indexation

- Every canonical page returns `200`.
- Retired URLs return a one-hop `301` to their closest replacement.
- Nonexistent URLs return a genuine `404` or `410`, not a soft-404 homepage.
- Development and preview environments are password-protected or `noindex`.
- Production pages do not accidentally inherit `noindex`.
- Googlebot, Bingbot, and desired AI search crawlers are not inadvertently blocked.
- Search Console URL Inspection renders the complete primary content.

## Canonicalization

- Every indexable HTML page has an absolute self-referential canonical.
- Canonicals point to `ai.bcci.bg`, not the development or vanity domain.
- Internal links use canonical URLs.
- XML sitemaps contain only canonical URLs.
- Open Graph URLs and structured-data URLs match canonicals.
- Tracking parameters do not generate indexable duplicates.
- HTTP/HTTPS and host variants redirect consistently.
- One trailing-slash policy is used consistently.

## Multilingual implementation

- Reciprocal `bg` and `en` `hreflang`.
- Correct `<html lang>`.
- Same-language canonical.
- Language switcher maps equivalent pages.
- No `hreflang` pointing to a redirect, `404`, `noindex`, or incomplete translation.

## XML sitemap

- Includes only `200`, canonical, indexable URLs.
- Correct and meaningful `lastmod`.
- Submitted in Google Search Console and Bing Webmaster Tools.
- Referenced in `robots.txt`.
- Separate news sitemap only when real news is published regularly.

Google recommends including canonical URLs in sitemaps and submitting them through Search Console; sitemap inclusion assists discovery but does not guarantee indexing.

## Metadata and social sharing

- Unique title on every indexable page.
- Useful meta description.
- Correct Open Graph title, description, image, URL, and locale.
- Twitter/X card metadata.
- High-quality social image for every position, research report, event, and article.
- No sample titles or descriptions inherited from templates.

## Structured data validation

- Valid JSON-LD.
- No template placeholders.
- URLs resolve.
- Dates include correct time zones where required.
- Person and Organization IDs are stable.
- Rich Results Test and Schema.org validator pass.
- Markup remains consistent with visible content.

## Other technical items

- Custom 404 page.
- No broken internal links.
- No redirect chains.
- No canonical-to-redirect combinations.
- No mixed content.
- Correct favicon and web manifest where used.
- Appropriate security headers.
- Crawlable pagination as the news archive grows.
- RSS/Atom feed for substantive publication updates.
- IndexNow as a secondary discovery mechanism for Bing-supported systems—not as a replacement for crawling, sitemaps, or internal links.

---

# 10. Performance and Core Web Vitals

No reliable field or lab performance score was available for this audit. Assigning a number would be fabrication.

Google’s current “good” thresholds are:

- **LCP:** 2.5 seconds or less.
- **INP:** 200 milliseconds or less.
- **CLS:** 0.1 or less.

These should be met at the 75th percentile for both mobile and desktop users.

Test at minimum:

1. Homepage.
2. Long article/position.
3. Survey page.
4. Research-results page with charts.
5. Course or event page.
6. Mobile navigation and contact form.

Likely optimization priorities for this type of design:

- Serve images as appropriately compressed AVIF or WebP.
- Use responsive `srcset` and `sizes`.
- Give all images explicit dimensions.
- Do not lazy-load the above-the-fold LCP image.
- Lazy-load below-the-fold images.
- Minimize font files, weights, and blocking font requests.
- Preload only critical resources.
- Remove unused JavaScript and CSS.
- Avoid client-side rendering for primary text.
- Prevent animated components and charts from causing layout shifts.
- Use static HTML data tables as accessible equivalents to charts.
- Measure real-user performance after launch, not only Lighthouse lab tests.

---

# 11. Authority and backlink strategy

The Council already has valuable external references on BCCI’s official domain, a LinkedIn organization page, and formal participation in public policy processes.

Consolidate those signals:

1. Update relevant BCCI articles to link to the precise canonical Council page.
2. Link BCCI’s Council directory or institutional page to `ai.bcci.bg`.
3. Ask confirmed university and business partners to publish their own descriptions of the collaboration.
4. Link every policy position to the official consultation or parliamentary record.
5. Ensure press releases link to the underlying report, not just the homepage.
6. Publish expert commentaries that journalists can attribute to named people.
7. Provide a press page with biographies, headshots, institutional descriptions, and media contact.
8. Preserve stable URLs so citations do not break.

Do not pursue manufactured mentions, bulk guest posts, paid link networks, or self-created pseudo-independent profiles. For GEO, a small number of strong, independent and consistent references is more useful than hundreds of low-quality mentions.

Do not force a Wikipedia article before independent notability exists. Build genuinely independent reporting and references first.

---

# 12. Measurement framework

## Search measurement

Configure:

- Google Search Console domain property.
- Bing Webmaster Tools.
- XML sitemap submission.
- Analytics with compliant consent.
- Server-log analysis.
- Rank tracking for a small, strategic Bulgarian and English query set.

Track:

- Valid indexed canonical URLs.
- Excluded and duplicate URLs.
- Branded versus non-branded impressions.
- Query-to-page alignment.
- Click-through rate.
- Referring domains.
- Position/PDF downloads.
- Survey starts and completions.
- Course registrations.
- Contact submissions.
- Newsletter referrals.
- Core Web Vitals.

A `site:` search is only a diagnostic spot-check. Search Console should be treated as the source of truth for Google indexation.

## GEO measurement

There is no single trustworthy universal “AI visibility score.” Create a controlled monitoring system.

Maintain a set of 20–50 Bulgarian and English prompts, for example:

- Кой е Съветът по изкуствен интелект към БТПП?
- Какво препоръчва българският бизнес за прилагането на AI Act?
- Какви са нивата на използване на AI от българските компании?
- Кои организации предлагат AI literacy обучение в България?
- What is the BCCI AI Council?
- What are Bulgarian companies’ main barriers to AI adoption?
- What AI Act guidance is available for Bulgarian SMEs?

Record monthly:

- Whether the Council is mentioned.
- Whether a Council URL is cited.
- Which exact URL is cited.
- Whether the entity name is correct.
- Whether facts and numbers are reproduced accurately.
- Whether Bulgarian and English sources are both discovered.
- Whether competitors are cited instead.
- Whether obsolete or vanity-domain URLs appear.

Monitor crawler logs for:

- Googlebot.
- Bingbot.
- OAI-SearchBot.
- GPTBot according to policy.
- Other relevant retrieval agents.

OpenAI says ChatGPT search referral links include `utm_source=chatgpt.com`, so these sessions can be segmented in analytics.

---

# 13. Prioritized 90-day roadmap

## Phase 0 — Before production launch

**Non-negotiable**

1. Approve `ai.bcci.bg` as canonical production host.
2. Create a complete URL migration and redirect map.
3. Protect or `noindex` the development host.
4. Redirect duplicate `prouchvane.bg` pages one-to-one.
5. Remove every placeholder and sample element.
6. Confirm the formal Council name.
7. Publish the real team and governance information.
8. Replace sample articles with real material or remove them.
9. Publish at least one complete position page with a downloadable official document.
10. Complete Bulgarian–English QA.
11. Validate canonicals, `hreflang`, robots rules, sitemap, status codes and structured data.
12. Add privacy, accessibility, editorial, corrections and survey data notices.
13. Test mobile usability, forms, keyboard access and Core Web Vitals.

## Phase 1 — First 30 days after launch

1. Publish expert profile pages.
2. Convert existing positions into substantive HTML pages.
3. Publish the survey methodology and questionnaire.
4. Rewrite homepage proof and entity sections.
5. Implement Organization, Person, Article, Dataset and breadcrumb markup.
6. Strengthen internal linking.
7. Update BCCI and LinkedIn references to the canonical domain.
8. Configure Search Console, Bing, analytics and log monitoring.
9. Create an AI Act content hub.
10. Publish a practical guide for Bulgarian SMEs.

## Phase 2 — Days 31–60

1. Publish initial survey findings or a transparent fieldwork update.
2. Release downloadable templates:
    - Internal AI-use policy.
    - AI vendor checklist.
    - AI readiness assessment.
    - AI Act role-classification worksheet.
3. Publish real course and event pages.
4. Add original Bulgarian business case studies.
5. Secure links from confirmed partners and institutions.
6. Publish webinar recordings and transcripts.
7. Introduce a press and media resource section.

## Phase 3 — Days 61–90

1. Publish the full survey dataset and methodology report.
2. Produce sector-specific result pages.
3. Launch an annual research/update cycle.
4. Expand AI Act guidance based on actual stakeholder questions.
5. Start monthly SEO and GEO prompt monitoring.
6. Refresh pages with new regulatory dates and evidence.
7. Add RSS, news sitemap, and IndexNow only if the publishing cadence justifies them.
8. Reassess content gaps using Search Console queries and survey feedback.

---

# 14. Launch acceptance criteria

The site should not be treated as production-ready until all of these statements are true:

- [ ] `ai.bcci.bg` is the unambiguous canonical property.
- [ ] The development domain is not indexable.
- [ ] The duplicate campaign domain is redirected or otherwise consolidated.
- [ ] No sample or placeholder content remains.
- [ ] The formal Council name is consistent.
- [ ] Every publicly listed team member is real and verified.
- [ ] Every displayed partner relationship is real and described.
- [ ] Every news item contains substantive final copy.
- [ ] Every listed position has a detail page and source document.
- [ ] The survey has methodology and privacy information.
- [ ] Bulgarian and English pages have complete editorial parity.
- [ ] Canonicals and reciprocal `hreflang` pass validation.
- [ ] Sitemaps contain only canonical, indexable `200` URLs.
- [ ] The site has appropriate structured data with no invented properties.
- [ ] Forms are accessible and legally compliant.
- [ ] Representative templates pass Core Web Vitals in real-user data.
- [ ] Search Console and Bing Webmaster Tools are configured.
- [ ] BCCI’s existing references link to the canonical site.
- [ ] OAI-SearchBot and GPTBot policies have been decided explicitly.

---

# Bottom line

The redesign’s visual and navigational framework is adequate. The bottleneck is that a potentially high-authority institution is currently represented by low-evidence pages.

The correct order of work is:

1. **Resolve canonical domain ownership.**
2. **Remove all sample material.**
3. **Prove the entity through governance and named experts.**
4. **Turn real positions into citeable HTML publications.**
5. **Make the national survey a transparent, open-data research asset.**
6. **Build practical AI Act and business-adoption guidance around that evidence.**
7. **Only then optimize metadata, schema, performance and AI crawler access.**

The Council does not need more generic AI content. It needs fewer, stronger, attributable and evidence-rich pages. That will create substantially more SEO and GEO value than mass blogging, superficial answer formatting, or an `llms.txt` file.
