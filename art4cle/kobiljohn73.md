Держи итоговый документ. Совет учителя: сохрани его прямо в проект как `README.md` (или `PROJECT_GUIDE.md`) в корне репозитория и закоммить — на защите это выглядит очень взросло, а тебе самому будет шпаргалкой на все оставшиеся лабы и на перенос.

---

# 📘 ART4CLE — ПОЛНАЯ ДОКУМЕНТАЦИЯ ПРОЕКТА
### Новостной сайт на Laravel + PostgreSQL. Тематика: «Мор (Утопия)». Лабы 1–4.

---

## ЧАСТЬ I. АРХИТЕКТУРА: КАК УСТРОЕН ПРОЕКТ

### 1. Большая схема: путь одного запроса

Laravel работает по паттерну **MVC** (Model — View — Controller). Любой клик пользователя проходит один и тот же путь:

```
Браузер (GET /admin)
   ↓
public/index.php          ← единственная входная дверь всего сайта
   ↓
routes/web.php            ← диспетчер: какому контроллеру отдать адрес
   ↓
Middleware (CheckRole)    ← охрана: пускает только нужные роли
   ↓
Controller (метод admin)  ← мозг: решает, ЧТО достать из базы
   ↓
Model (Text, User, Role)  ← руки: идут в PostgreSQL через Eloquent
   ↓
View (admin.blade.php)    ← лицо: Blade-шаблон превращает данные в HTML
   ↓
Браузер видит страницу
```

Запомни эту цепочку — на защите тебя будут спрашивать именно про неё.

### 2. База данных (Лаба 3): четыре таблицы

| Таблица | Назначение | Ключевые колонки | Правила целостности |
| :--- | :--- | :--- | :--- |
| `roles` | Справочник ролей | `id`, `name` | `name` уникален |
| `categories` | Справочник категорий | `id`, `name` | `name` уникален |
| `users` | Учётные записи | `id`, `login`, `password`, `role_id` | `login` уникален; `role_id` → `roles.id`, удаление занятой роли **запрещено** (`restrict`) |
| `texts` | Статьи (рабочая таблица) | `id`, `heading`, `text`, `category_id`, `user_id`, `is_blocked`, `created_at`, `updated_at` | `category_id` → `categories.id` (`restrict`: категорию с текстами не удалить); `user_id` → `users.id` (`cascade`: удалил автора — погибли его статьи) |

Связи: роль → много пользователей; категория → много текстов; автор → много текстов. Все внешние ключи обязательны к заполнению и проиндексированы (Laravel делает индексы на FK автоматически).

### 3. Файлы и кто за что отвечает

#### 📁 `routes/web.php` — таблица маршрутов
Единственное место, где адрес сайта связывается с кодом. Полный список:

| Маршрут | Метод | Кто пускается | Что делает |
| :--- | :--- | :--- | :--- |
| `/` | GET | все | Главная лента |
| `/category` | GET | все | Категория «Хронология» |
| `/register`, `/login` | GET+POST | все | Формы и обработка входа/регистрации |
| `/logout` | POST | вошедшие | Выход |
| `/journalist` | GET+POST | Journalist, Admin | Форма и сохранение статьи |
| `/admin` | GET | Admin | Панель администратора |
| `/admin/edit/{text}` | GET | Admin | Форма правки статьи |
| `/admin/update/{text}`, `/admin/block/{text}`, `/admin/delete/{text}`, `/admin/role` | POST | Admin | Правка, блокировка, удаление, смена роли |

Всё, что меняет данные (создание, удаление, блокировка, выход), — **только POST**: это защита от случайных кликов и ботов.

#### 📁 `app/Http/Controllers/` — контроллеры

**`PageController.php`** — вся логика страниц:
*   `home()` — лента: `Text::with(['category','author'])->where('is_blocked', false)->latest()->get()`. Перевод: «возьми тексты, сразу подтяни категории и авторов, только не заблокированные, от новых к старым».
*   `category()` — тексты одной категории через связь `$category->texts()`.
*   `journalist()` — отдаёт форму + список категорий для select.
*   `store()` — валидация → `$request->user()->texts()->create($data)` (автор подставляется сам).
*   `admin()` — собирает три коллекции: тексты, пользователи, роли.
*   `edit() / update()` — форма правки и сохранение.
*   `block()` — переключатель `is_blocked` (true↔false).
*   `destroy()` — удаление записи.
*   `changeRole()` — смена `role_id` у пользователя.

**`AuthController.php`** — авторизация (Лаба 2):
*   `register()` — валидация (`unique:users` гарантирует уникальный логин), `Hash::make()` хеширует пароль, `Auth::login()` сразу входит.
*   `login()` — `Auth::attempt()` сам находит юзера по логину и сверяет хеш; при успехе `session()->regenerate()`.
*   `logout()` — убивает сессию и токен.

#### 📁 `app/Http/Middleware/CheckRole.php` — охрана (Лаба 4)
Три решения по порядку: не вошёл → `redirect('/login')`; роль не из списка → `abort(403)`; иначе пропустить. Вешается на маршруты как `->middleware('role:Journalist,Admin')`. Зарегистрирован алиасом `'role'` в **`bootstrap/app.php`** (в современном Laravel файла Kernel.php нет, middleware регистрируются там).

#### 📁 `app/Models/` — модели (Eloquent)
Каждая модель = представитель таблицы в PHP.

*   **`Role`**: `hasMany(User)` — у роли много пользователей.
*   **`User`**: `belongsTo(Role)`, `hasMany(Text)`. Атрибуты: `#[Fillable(['login','password','role_id'])]` (белый список для массового создания), `#[Hidden]` (пароль не светится в JSON), cast `'password' => 'hashed'`.
*   **`Category`**: `hasMany(Text)`.
*   **`Text`**: `belongsTo(Category)`, `belongsTo(User, 'user_id')` под именем `author`. Fillable включает `is_blocked`.

`Fillable` — защита от mass assignment: создать можно только разрешённые поля. Связи (`hasMany`/`belongsTo`) — то, что позволяет писать `$text->author->login` и `$category->texts()`.

#### 📁 `database/migrations/` — чертежи таблиц (порядок = дата в имени)
1.  `..._create_users_table` — users (login, password) + sessions
2.  `..._create_cache_table`, `..._create_jobs_table` — служебные, не трогаем
3.  `..._create_categories_table` — name unique
4.  `..._create_roles_table` — name unique
5.  `..._add_role_id_to_users_table` — FK на roles, `restrict`
6.  `..._create_texts_table` — heading, text, два FK, timestamps

Правило: применённую миграцию **не правят**; изменения делаются новой миграцией. Исключение в разработке — `migrate:fresh`, который сносит всё и строит заново.

#### 📁 `database/seeders/` — демо-данные
`ArticleSeeder` (вызывается из `DatabaseSeeder`): создаёт 3 роли, 4 категории, админа (`admin` / `123456`, роль Admin) и 4 статьи с автором. Использует `firstOrCreate`, поэтому переживает повторный запуск. Благодаря сидеру **данные ездят вместе с проектом через git** — на любой машине `migrate:fresh --seed` восстанавливает демо-набор.

#### 📁 `resources/views/` — Blade-шаблоны
*   `layouts/app.blade.php` — скелет: `<head>` с `@vite`, шапка, `@yield('content')`, подвал.
*   `components/header.blade.php` — меню с ролевой логикой (`@auth`, `@if` по роли) и блок `@guest/@else` (вход/регистрация против «Привет, login» + кнопка выхода).
*   `components/footer.blade.php` — подвал.
*   `home.blade.php`, `category.blade.php` — ленты карточек (`$text->heading`, `$text->category->name`, `$text->author?->login`).
*   `journalist.blade.php` — форма создания (POST, `@csrf`, select категорий из базы).
*   `admin.blade.php` — живая таблица статей + форма смены ролей.
*   `admin/edit.blade.php` — форма правки (`old('heading', $text->heading)`, `@selected`).
*   `auth/login.blade.php`, `auth/register.blade.php` — формы авторизации с `@error`.
*   `errors/403.blade.php` — кастомная страница отказа доступа с картинкой из `public/images/`.

Blade-минимум для защиты: `@extends/@section/@yield` — наследование шаблонов; `{{ }}` — вывод с экранированием; `@foreach`, `@if`, `@error`, `@csrf`, `@guest/@auth`, `old()` — память формы после ошибки валидации.

#### 📁 Прочее важное
*   **`.env`** — личные настройки машины (доступы к БД, `APP_DEBUG`). В git **не попадает** (gitignore), на каждой машине создаётся заново из `.env.example`.
*   **`resources/css/app.css`** — все стили; собираются Vite в `public/build`.
*   **`public/`** — единственная папка, доступная браузеру напрямую: `index.php`, собранные стили, `images/`.
*   **`vendor/`, `node_modules/`** — чужой код зависимостей; в git не попадают, восстанавливаются командами `composer install` / `npm install`.
*   **`composer.json` / `package.json`** — списки зависимостей (PHP и JS соответственно). Именно они делают проект переносимым.

### 4. Матрица доступа (Лаба 4) — как есть в коде

| Раздел | Гость | Reader | Journalist | Admin |
| :--- | :--- | :--- | :--- | :--- |
| `/`, `/category` | ✅ | ✅ | ✅ | ✅ |
| `/journalist` | → на `/login` | 403 | ✅ | ✅ |
| `/admin` | → на `/login` | 403 | 403 | ✅ |

### 5. Мини-шпаргалка «вопрос на защите → ответ»

| Вопрос | Ответ одной фразой |
| :--- | :--- |
| Зачем `Hash::make`? | В базе хранится необратимый хеш пароля, а не сам пароль (требование лабы 2). |
| Что такое CSRF и `@csrf`? | Секретный токен формы; без него Laravel отвечает 419 — защита от подделки запроса чужим сайтом. |
| Зачем middleware? | Проверяет роль до входа в контроллер; централизованная охрана маршрутов (лаба 4). |
| `cascade` против `restrict`? | cascade — удалить родителя и всё дочернее; restrict — запретить удаление родителя, пока есть дети. |
| Что делает `with(['category','author'])`? | Eager loading: два запроса вместо N+1, сайт не тормозит на ленте. |
| Что такое `Text $text` в аргументах метода? | Route model binding: Laravel сам находит запись по id из URL и подставляет моделью. |
| Зачем `latest()`? | Сортировка по `created_at` по убыванию — требование «от новых к старым». |
| Почему `unique:users` в валидации? | Требование «логин уникальный»; проверяется на уровне БД и валидации. |

---

## ЧАСТЬ II. ГАЙД ПЕРЕНОСА ПРОЕКТА НА WINDOWS

### 0. Что едет, а что нет

| Едет через git | Создаётся на месте заново |
| :--- | :--- |
| Весь код, шаблоны, миграции, сидеры, стили, `public/images` | `vendor/` (composer install), `node_modules/` (npm install), `public/build` (npm run build), `.env` (вручную), **данные базы** (migrate:fresh --seed) |

База данных на Fedora **не переносится файлом**: на Windows она создаётся с нуля из миграций и сидера. Это и есть взрослый способ переноса.

### 1. Установка софта (один раз)

1.  **Git for Windows** — git-scm.com, ставить со значениями по умолчанию.
2.  **VS Code** — code.visualstudio.com.
3.  **Node.js LTS** — nodejs.org (даст `node` и `npm`).
4.  **PostgreSQL** — официальный установщик postgresql.org (версия 16 или 17). При установке **запиши пароль суперюзера `postgres`** — спросит один раз, нужен будет постоянно. Соглашайся на установку pgAdmin — пригодится.
5.  **PHP + Composer** — два пути:
    *   **Путь А (рекомендую): Laravel Herd для Windows** — herd.laravel.com/windows. Ставит PHP и Composer одним пакетом, расширение `pdo_pgsql` включено из коробки.
    *   **Путь Б (ручной):** PHP с windows.php.net (Thread Safe, распаковать в `C:\php`), Composer-Setup.exe. Вручную придётся править `php.ini`: раскомментировать строки `extension=pdo_pgsql` и `extension=pgsql` (убрать `;` в начале) — иначе Laravel не увидит Postgres.

### 2. Скачать проект

Открой **Git Bash** или PowerShell:

```bash
git clone https://github.com/ТВОЙ_ЛОГИН/art4cle.git
cd art4cle
```

### 3. Поставить зависимости проекта

```bash
composer install
npm install
npm run build
```

Третья команда собирает CSS в `public/build`. Без неё сайт откроется «голым» или с ошибкой «Vite manifest not found».

### 4. Создать `.env` и ключ

```bash
copy .env.example .env
php artisan key:generate
```
*(В PowerShell вместо `copy` пиши `Copy-Item .env.example .env`.)*

Затем открой `.env` в VS Code и пропиши блок базы (пароль — тот, что задашь на шаге 5):

```env
APP_DEBUG=true
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=art4cle
DB_USERNAME=eva
DB_PASSWORD=твой_пароль
```

### 5. Поднять PostgreSQL на Windows

Открой **SQL Shell (psql)** из меню Пуск (войди как `postgres` со своим паролем) и выполни построчно — это ровно то, что ты делал на Fedora, включая грабли со схемой `public`:

```sql
CREATE DATABASE art4cle;
CREATE USER eva WITH PASSWORD 'твой_пароль';
GRANT ALL PRIVILEGES ON DATABASE art4cle TO eva;
\c art4cle
GRANT ALL ON SCHEMA public TO eva;
\q
```

Последний `GRANT` — обязательный: без него получишь знакомую ошибку `permission denied for schema public`.

### 6. Построить базу и заселить данные

В папке проекта:

```bash
php artisan migrate:fresh --seed
```

Миграции создадут `roles`, `categories`, `users`, `texts`; сидер зальёт роли, категории, админа и 4 статьи. Проверка:

```bash
php artisan db:show
```

### 7. Запуск

```bash
php artisan serve
```

Открой `http://127.0.0.1:8000`. Войди как **admin / 123456**. Если хочешь править CSS на ходу — во второй вкладке терминала `npm run dev`.

### 8. Чек-лист «всё ожило»

- [ ] Главная: 4 статьи, автор admin, даты
- [ ] `/category`: статья «Хроника»
- [ ] `/register` → новый юзер получает роль Reader и 403 на `/journalist`
- [ ] `/admin` под админом: таблица, блокировка, правка, удаление, смена роли
- [ ] `/admin` под читателем: кастомный 403 с твоей картинкой

### 9. Типовые грабли Windows и лечение

| Симптом | Лечение |
| :--- | :--- |
| `could not find driver` / `pdo_pgsql` | Ручной PHP: раскомментировать `extension=pdo_pgsql` в `php.ini`. В Herd уже включено |
| `psql не является командой` | Пуск → «SQL Shell (psql)», либо полный путь `"C:\Program Files\PostgreSQL\17\bin\psql.exe"` |
| PowerShell не даёт запустить npm | `Set-ExecutionPolicy -Scope CurrentUser RemoteSigned` |
| «Vite manifest not found» / сайт без стилей | `npm run build` |
| Порт 8000 занят | `php artisan serve --port=8001` |
| `permission denied for schema public` | Шаг 5, строка `GRANT ALL ON SCHEMA public TO eva;` |
| Белые/красные страницы без объяснений | `APP_DEBUG=true` в `.env` |
| Забыл пароль от Postgres | Переставить Postgres или сбросить через pgAdmin — проще всего на учебной машине |

### 10. Дальнейшая работа на двух машинах

Развиваешь проект на Windows → фиксируй и отправляй:

```bash
git add .
git commit -m "что сделал"
git push
```

Садишься за Fedora → забирай изменения и обновляй базу, если менялись миграции/сидеры:

```bash
git pull
php artisan migrate:fresh --seed
```

Правило гигиены: **база данных не синхронизируется git-ом никогда**. Синхронизируется только код; база на каждой машине строится миграциями и сидерами. Статьи, написанные руками на одной машине, на другой не появятся — и это нормально, для демо и существует сидер.

---

## ПРИЛОЖЕНИЕ. ШПАРГАЛКА КОМАНД ПРОЕКТА

| Команда | Зачем |
| :--- | :--- |
| `php artisan serve` | Запустить сайт |
| `php artisan migrate` | Применить новые чертежи таблиц |
| `php artisan migrate:fresh --seed` | Перестроить базу с нуля + демо-данные |
| `php artisan db:seed --class=ArticleSeeder` | Залить демо-данные без перестройки |
| `php artisan make:migration / make:model / make:controller / make:middleware / make:seeder` | Создать заготовку файла |
| `php artisan optimize:clear` | Сбросить кэши, когда «код правильный, а ведёт себя по-старому» |
| `php artisan db:show` | Проверить подключение к БД |
| `psql -h 127.0.0.1 -U eva -d art4cle` → `\d texts`, `\dt`, `\q` | Посмотреть таблицы глазами SQL |
| `npm install` / `npm run build` / `npm run dev` | Зависимости фронтенда / сборка стилей / горячая пересборка |
| `git clone / add / commit / push / pull` | Перенос и история кода |

---

Всё. Это полный портрет твоего проекта: от пути запроса до пароля суперюзера Postgres на Windows. Сохрани как `README.md`, закоммить — и считай, что у тебя есть не только четыре закрытые лабы, но и документация, которой позавидует половина потока. 🏆

Если перед защитой захочешь прогнать «тренировочное собеседование» по разделу 5 — пиши, устрою тебе допрос с пристрастием, как настоящий препод. 🔥
