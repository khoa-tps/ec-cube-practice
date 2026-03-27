# EC-CUBE 4.2 & Symfony Coding Rules

You are an expert PHP and Symfony developer specializing in the EC-CUBE e-commerce platform. When working on this project, ALWAYS adhere to the following rules:

## 1. Tech Stack & Environment
- **PHP Version**: PHP 8.1.34
- **Framework**: Symfony 6.4.23
- **Platform**: EC-CUBE 4.3.0
- **Database**: PostgreSQL

## 2. Core Coding Rules
- **Constructor Property Promotion**: ALWAYS use PHP 8+ Constructor Property Promotion for dependency injection. Do NOT write boilerplate class properties and manual assignments in `__construct()` unless extending a parent class requires otherwise.
  - *Correct*: `public function __construct(private CustomRepository $repo) {}`
- **Parent Constructor Call**: Whenever you extend a core Controller or Service, if you override its `__construct()`, you MUST call `parent::__construct(...)` and pass all required dependencies so that parent class variables are properly initialized.
- **Strict Typing**: Enforce strict return types, parameter types, and use null-safe operators (`?->`). Remove outdated/redundant PHPDocs if native Type Hinting already covers it.
- **PHP Attributes Instead of Annotations**: ALWAYS use PHP 8 Attributes (`#[Route(...)]`, `#[Template(...)]`) instead of legacy Doctrine Annotations (`/** @Route(...) */`) for routing and controller configuration.
- **Naming Conventions**: Classes should follow `StudlyCaps`, methods and variables follow `camelCase`. Avoid abbreviations.

## 3. EC-CUBE Architecture & Core Modification (CRITICAL)
**CRITICAL RULE: NEVER MODIFY FILES INSIDE `src/` (Core Files).**
All customizations must be placed inside `app/Customize/` (for site-specific code) or `app/Plugin/` (for reusable plugins). 

When you need to override or extend functionality, use these exact paths:
- **Controllers**: `app/Customize/Controller/` 
  *(Extend core controllers or `Eccube\Controller\AbstractController`)*
- **Entities**: `app/Customize/Entity/` 
  *(Use Doctrine Traits and `Customize\Entity` namespace to extend core tables like `dtb_product`. Read EC-CUBE Schema Extension guide)*
- **Repositories**: `app/Customize/Repository/` 
  *(Create custom repositories by extending core repositories and alias them in `services.yaml` or use DI)*
- **Services/Logic**: `app/Customize/Service/`
- **Form Extensions**: `app/Customize/Form/Extension/` 
  *(Do not recreate forms; use `AbstractTypeExtension` to add logic/fields into existing core forms)*
- **Event Dispatchers**: `app/Customize/Event/` 
  *(Subscribe to core events like `eccube.entity.*`, `front.*`, or `admin.*` to hook logic instead of modifying core flows)*
- **Twig Templates**: `app/template/` 
  *(Copy the respective core `.twig` file from `src/Resource/template/...` to `app/template/default/...` or `app/template/admin/...` to override UI. Never edit `src/Resource/...` block directly)*

## 4. Database & ORM (PostgreSQL specifics)
- **Doctrine QueryBuilder**: Always use Doctrine QueryBuilder for database interactions. **NO RAW SQL**.
- **Postgres Compatibility**: PostgreSQL is strictly case-sensitive in queries unlike MySQL. Be cautious when doing text searches.
- **Pagination**: Use KNP Paginator (`PaginatorInterface`) standard across the platform.

## 5. Security & Quality
- Validate all incoming `$request` inputs in Forms properly instead of fetching them directly from `$request->request->get()`.
- Translations: Use `$this->translator->trans()` with translation keys instead of hardcoding text strings in PHP files.

Please review and rigorously follow this document before making any changes.
