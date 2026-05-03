# PROJECT_MANIFEST.md

## 1. Panoramica del Progetto

| Campo            | Valore                                                                                                      |
| ---------------- | ----------------------------------------------------------------------------------------------------------- |
| **Nome**         | Laravel Base Application                                                                                    |
| **Tipo**         | Web Application (Laravel 12)                                                                                |
| **Descrizione**  | Skeleton application per Laravel framework con sistema di gestione media (immagini) e autenticazione utente |
| **Licenza**      | MIT                                                                                                         |
| **Versione PHP** | ^8.2 (minimo 8.2, compatibile 8.3, 8.4+)                                                                    |

---

## 2. Stack Tecnologico

### Backend

- **Framework**: Laravel 12.0
- **PHP**: ^8.2 (PHP 8.2+)
- **Database**: SQLite (default), supporta MySQL/PostgreSQL
- **Image Processing**: intervention/image-laravel ^1.5

### Frontend

- **Build Tool**: Vite 7.0.7
- **CSS Framework**: Tailwind CSS 3.1.0
- **JavaScript**: Alpine.js 3.4.2, Axios 1.11.0
- **Forms**: @tailwindcss/forms 0.5.2

### Development Tools

- **Testing**: Pest PHP 4.3 + pest-plugin-laravel 4.0
- **Code Style**: Laravel Pint 1.24
- **Scaffolding**: Laravel Breeze 2.3 (authentication)
- **Dev Server**: Laravel Sail 1.41

---

## 3. Struttura del Database

### Tabella `users`

| Colonna           | Tipo      | Note                                   |
| ----------------- | --------- | -------------------------------------- |
| id                | bigint    | Primary key                            |
| name              | string    | Nome utente                            |
| email             | string    | Email univoca                          |
| email_verified_at | timestamp | Data verifica email                    |
| password          | string    | Password hashed                        |
| remember_token    | string    | Token remember                         |
| is_active         | boolean   | Stato attivo/disattivo (default: true) |
| created_at        | timestamp |                                        |
| updated_at        | timestamp |                                        |

> **Nota**: Il campo `role` è stato rimosso. La gestione ruoli è ora gestita da Spatie Permission.

### Tabella `media`
| Colonna    | Tipo            | Note                                  |
| ---------- | --------------- | ------------------------------------- |
| id         | bigint          | Primary key                           |
| model_id   | bigint          | ID del modello associato (morph)      |
| model_type | string          | Tipo modello (es. App\Models\User)    |
| collection | string          | Collezione (avatar, gallery, default) |
| disk       | string          | Disk storage (default: 'public')      |
| path       | string          | Percorso file                         |
| filename   | string          | Nome file                             |
| mime_type  | string          | Tipo MIME                             |
| size       | unsignedInteger | Dimensione file                       |
| width      | unsignedInteger | Larghezza immagine                    |
| height     | unsignedInteger | Altezza immagine                      |
| order      | unsignedInteger | Ordinamento                           |
| is_primary | boolean         | Immagine primaria                     |
| created_at | timestamp       |                                       |
| updated_at | timestamp       |                                       |

### Tabella `brands`
| Colonna    | Tipo      | Note                     |
| ---------- | --------- | ------------------------- |
| id         | bigint    | Primary key               |
| name       | string    | Nome marca (es. BMW, Fiat) |
| created_at | timestamp |                          |
| updated_at | timestamp |                          |

### Tabella `shops`
| Colonna    | Tipo      | Note                                |
| ---------- | --------- | ---------------------------------- |
| id         | bigint    | Primary key                        |
| user_id    | bigint    | FK a users (proprietario)           |
| name       | string    | Nome negozio                       |
| description| text      | Descrizione negozio                |
| is_active  | boolean   | Stato attivo/disattivo             |
| phone      | string    | Telefono                           |
| email      | string    | Email                              |
| created_at | timestamp |                                    |
| updated_at | timestamp |                                    |

### Tabella `locations`
| Colonna    | Tipo      | Note                     |
| ---------- | --------- | ------------------------ |
| id         | bigint    | Primary key              |
| shop_id    | bigint    | FK a shops               |
| address    | string    | Indirizzo                |
| city       | string    | Città                    |
| province   | string(2) | Provincia (es. RM)       |
| zip_code   | string    | CAP                      |
| created_at | timestamp |                          |
| updated_at | timestamp |                          |

### Tabella `cars`
| Colonna    | Tipo      | Note                                |
| ---------- | --------- | ----------------------------------- |
| id         | bigint    | Primary key                        |
| user_id    | bigint    | FK a users (venditore)             |
| shop_id    | bigint    | FK a shops (nullable)               |
| location_id| bigint    | FK a locations (nullable)            |
| brand_id   | bigint    | FK a brands                        |
| model      | string    | Modello auto (es. Panda)           |
| year       | integer   | Anno immatricolazione               |
| price      | decimal   | Prezzo (10,2)                      |
| mileage    | integer   | Chilometri (nullable)               |
| fuel_type  | string    | Carburante (Benzina, Diesel, etc.) |
| transmission| string   | Cambio (Manuale, Automatico)       |
| is_new     | boolean   | Nuova/Usata                        |
| description| text      | Descrizione annuncio                |
| is_active  | boolean   | Stato attivo/disattivo             |
| created_at | timestamp |                                    |
| updated_at | timestamp |                                    |

---

## 4. Modelli (Eloquent)

### User Model

**Path**: `app/Models/User.php`

- Estende `Authenticatable`
- Usa trait: `HasApiTokens`, `HasMedia`, `HasRoles`
- Campi fillable: `name`, `email`, `password`, `is_active`
- Metodo `isAdmin()`: verifica se ha ruolo admin
- Evento `deleting`: cancella avatar e gallery alla rimozione utente

### Brand Model

**Path**: `app/Models/Brand.php`

- Rappresenta marche auto (Fiat, BMW, etc.)
- Relazione `hasMany(Car::class)`
- Campo fillable: `name` (univoco)

### Shop Model

**Path**: `app/Models/Shop.php`

- Usa trait: `HasMedia` (per logo e copertina)
- Relazioni: `belongsTo(User)`, `hasMany(Location)`, `hasMany(Car)`
- Campi fillable: `user_id`, `name`, `description`, `is_active`, `phone`, `email`
- Metodi: `logo()` (primaryMedia 'logo'), `cover()` (primaryMedia 'cover')

### Location Model

**Path**: `app/Models/Location.php`

- Rappresenta punti vendita di uno shop
- Relazioni: `belongsTo(Shop)`, `hasMany(Car)`
- Campi fillable: `shop_id`, `address`, `city`, `province`, `zip_code`

### Car Model

**Path**: `app/Models/Car.php`

- Usa trait: `HasMedia` (per foto auto)
- Relazioni: `belongsTo(User)`, `belongsTo(Shop)`, `belongsTo(Location)`, `belongsTo(Brand)`
- Campi fillable: `user_id`, `shop_id`, `location_id`, `brand_id`, `model`, `year`, `price`, `mileage`, `fuel_type`, `transmission`, `is_new`, `description`, `is_active`
- Metodi: `primaryImage()` (primaryMedia 'gallery'), `gallery()` (getMediaByCollection 'gallery')

### Media Model

**Path**: `app/Models/Media.php`

- Rappresenta file media (immagini)
- Relazione morphTo `model()` per associazione polimorfica
- Accessor `url`: URL completo del media
- Accessor `fullPath`: percorso fisico del file

---

## 5. Trait

### HasMedia Trait

**Path**: `app/Traits/HasMedia.php`

Metodi disponibili:

- `media()`: relazione morphMany
- `primaryMedia($collection)`: ottiene media primario
- `getMediaByCollection($collection)`: ottiene tutti i media di una collezione
- `addMedia($file, $collection, $isPrimary)`: aggiunge nuovo media con validazione

Validazione file:

- MIME consentiti: `image/jpeg`, `image/png`, `image/gif`, `image/webp`
- Estensioni: `jpg`, `jpeg`, `png`, `gif`, `webp`

---

## 6. Controller

### ProfileController

**Path**: `app/Http/Controllers/ProfileController.php`

- `edit(Request)`: mostra form modifica profilo
- `update(ProfileUpdateRequest)`: aggiorna dati profilo
- `destroy(Request)`: elimina account utente

### AvatarController

**Path**: `app/Http/Controllers/Profile/AvatarController.php`

- `edit()`: mostra form modifica avatar
- `update(Request)`: carica nuovo avatar
- `destroy()`: elimina avatar

### SearchController

**Path**: `app/Http/Controllers/SearchController.php`

- `index()`: homepage con filtri ricerca (brands, cities, fuel types)
- `search(Request)`: risultati ricerca con filtri (brand, model, prezzo, anno, carburante, città)

### ShopController

**Path**: `app/Http/Controllers/ShopController.php`

- `show($id)`: visualizza pagina pubblica negozio con auto e locations
- `create()`: form creazione negozio
- `store(Request)`: salva nuovo negozio
- `edit($id)`: form modifica negozio
- `update(Request, $id)`: aggiorna negozio

### CarController

**Path**: `app/Http/Controllers/CarController.php`

- `create()`: form inserimento auto (con brands, shops, locations)
- `store(Request)`: salva nuova auto
- `show($id)`: visualizza dettaglio auto con gallery
- `edit($id)`: form modifica auto
- `update(Request, $id)`: aggiorna auto

### LocationController

**Path**: `app/Http/Controllers/LocationController.php`

- `store(Request, $shopId)`: aggiunge punto vendita a uno shop
- `destroy($shopId, $locationId)`: rimuove punto vendita

---

## 7. Request Validation

### ProfileUpdateRequest

**Path**: `app/Http/Requests/ProfileUpdateRequest.php`

- `name`: required, string, max:255
- `email`: required, string, lowercase, email, max:255, unique (ignore current user)

---

## 8. Route

### web.php

**Path**: `routes/web.php`

| Metodo | URI             | Controller                | Middleware     | Nome rotta             |
| ------ | --------------- | ------------------------- | -------------- | ---------------------- |
| GET    | /               | closure (welcome + recent)| -              | home                   |
| GET    | /dashboard      | closure                   | auth, verified | dashboard              |
| GET    | /cerca          | SearchController@index    | -              | search.index           |
| GET    | /search         | SearchController@search   | -              | search.results         |
| GET    | /shops/{shop}   | ShopController@show       | -              | shops.show             |
| GET    | /cars/{car}     | CarController@show        | -              | cars.show              |
| GET    | /shops/create   | ShopController@create     | auth           | shops.create           |
| POST   | /shops          | ShopController@store      | auth           | shops.store            |
| GET    | /shops/{shop}/edit | ShopController@edit    | auth           | shops.edit             |
| PUT    | /shops/{shop}   | ShopController@update     | auth           | shops.update           |
| GET    | /cars/create    | CarController@create      | auth           | cars.create            |
| POST   | /cars           | CarController@store       | auth           | cars.store             |
| GET    | /cars/{car}/edit| CarController@edit        | auth           | cars.edit              |
| PUT    | /cars/{car}     | CarController@update      | auth           | cars.update            |
| POST   | /shops/{shop}/locations | LocationController@store | auth    | locations.store        |
| DELETE | /shops/{shop}/locations/{location} | LocationController@destroy | auth | locations.destroy |
| GET    | /profile        | ProfileController@edit    | auth           | profile.edit           |
| PATCH  | /profile        | ProfileController@update  | auth           | profile.update         |
| DELETE | /profile        | ProfileController@destroy | auth           | profile.destroy        |
| GET    | /profile/avatar | AvatarController@edit     | auth           | profile.avatar         |
| PUT    | /profile/avatar | AvatarController@update   | auth           | profile.avatar.update  |
| DELETE | /profile/avatar | AvatarController@destroy  | auth           | profile.avatar.destroy |

Rotte auth.php (Breeze):

- Login, Register, Logout, Password Reset, Email Verification

---

## 9. API REST

### API Authentication

**Framework**: Laravel Sanctum (token-based authentication)

### API Routes

**Path**: `routes/api.php`

| Metodo | URI                   | Controller              | Middleware   | Descrizione                  |
| ------ | --------------------- | ----------------------- | ------------ | ---------------------------- |
| POST   | /api/login            | AuthController@login    | -            | Login e generazione token    |
| POST   | /api/register         | AuthController@register | -            | Registrazione utente         |
| GET    | /api/user             | closure                 | auth:sanctum | Info utente corrente         |
| GET    | /api/me               | AuthController@me       | auth:sanctum | Info dettagliate utente    |
| POST   | /api/logout           | AuthController@logout   | auth:sanctum | Logout e revoca token        |
| GET    | /api/shops            | ShopApiController@index | auth:sanctum | Lista negozi                 |
| GET    | /api/shops/{shop}     | ShopApiController@show  | auth:sanctum | Dettaglio negozio            |
| POST   | /api/shop/request      | ShopApiController@requestDealer | auth:sanctum | Richiesta dealer         |
| GET    | /api/cars             | CarApiController@index | auth:sanctum | Lista auto (con filtri)      |
| GET    | /api/cars/{car}       | CarApiController@show  | auth:sanctum | Dettaglio auto               |
| POST   | /api/cars             | CarApiController@store | auth:sanctum | Crea annuncio auto          |
| PUT    | /api/cars/{car}       | CarApiController@update | auth:sanctum | Modifica annuncio auto      |

### API Resources

#### UserResource

**Path**: `app/Http/Resources/UserResource.php`

Trasforma il modello User in JSON strutturato per le API:

```json
{
    "id": 1,
    "name": "Nome Utente",
    "email": "user@example.com",
    "email_verified_at": "2026-05-01T10:00:00.000000Z",
    "is_active": true,
    "roles": ["admin"],
    "permissions": ["create-users", "edit-posts"],
    "avatar": {
        "id": 1,
        "name": "avatar.jpg",
        "url": "http://localhost/storage/1/avatar.jpg",
        "thumb_url": "http://localhost/storage/1/conversions/avatar-thumb.jpg"
    },
    "created_at": "2026-05-01T10:00:00.000000Z",
    "updated_at": "2026-05-01T10:00:00.000000Z"
}
```

**Campi condizionali**:

- `roles` e `permissions`: caricati solo se richiesta relazione
- `avatar`: incluso solo se caricati i media

#### ShopResource

**Path**: `app/Http/Resources/ShopResource.php`

```json
{
    "id": 1,
    "name": "AutoUsato Roma",
    "description": "Il miglior concessionario di Roma",
    "phone": "06 1234567",
    "email": "info@autousato.it",
    "logo": {
        "url": "http://localhost/storage/shops/1/logo.jpg",
        "thumb_url": "http://localhost/storage/shops/1/conversions/logo-thumb.jpg"
    },
    "locations": [
        {
            "id": 1,
            "address": "Via Tuscolana 123",
            "city": "Roma",
            "province": "RM"
        }
    ],
    "cars_count": 15,
    "recent_cars": [...],
    "created_at": "2026-05-01T10:00:00.000000Z"
}
```

#### CarResource

**Path**: `app/Http/Resources/CarResource.php`

```json
{
    "id": 1,
    "brand": {
        "id": 1,
        "name": "BMW"
    },
    "model": "Serie 3",
    "year": 2022,
    "price": 35000.00,
    "mileage": 15000,
    "fuel_type": "Diesel",
    "transmission": "Automatico",
    "is_new": false,
    "description": "Ottima condizioni, unico proprietario",
    "primary_image": {
        "url": "http://localhost/storage/cars/1/main.jpg",
        "thumb_url": "http://localhost/storage/cars/1/conversions/main-thumb.jpg"
    },
    "gallery": [...],
    "shop": {
        "id": 1,
        "name": "AutoUsato Roma"
    },
    "location": {
        "id": 1,
        "city": "Roma",
        "address": "Via Tuscolana 123"
    },
    "created_at": "2026-05-01T10:00:00.000000Z"
}
```

### API Controller

#### AuthController (API)

**Path**: `app/Http/Controllers/Api/AuthController.php`

Metodi:

- `login(Request)`: autentica utente, verifica `is_active`, genera token Sanctum
- `register(Request)`: registrazione nuovo utente (da implementare)
- `me(Request)`: informazioni dettagliate utente autenticato
- `logout(Request)`: revoca token corrente

**Validazione login**:

- Controlla credenziali (email/password)
- Verifica campo `is_active = true`
- Restituisce errore se account disattivato

#### ShopApiController

**Path**: `app/Http/Controllers/Api/ShopApiController.php`

Metodi:

- `index()`: lista negozi attivi con paginazione
- `show($id)`: dettaglio negozio con locations e auto recenti
- `requestDealer(Request)`: richiesta per diventare dealer (crea shop con `is_active=false`)

#### CarApiController

**Path**: `app/Http/Controllers/Api/CarApiController.php`

Metodi:

- `index(Request)`: lista auto con filtri (brand, model, prezzo, anno, carburante, città)
- `show($id)`: dettaglio auto con brand, shop, location, gallery
- `store(Request)`: crea nuovo annuncio auto (validazione completa)
- `update(Request, $id)`: modifica annuncio auto esistente

Trasforma il modello User in JSON strutturato per le API:

```json
{
    "id": 1,
    "name": "Nome Utente",
    "email": "user@example.com",
    "email_verified_at": "2026-05-01T10:00:00.000000Z",
    "is_active": true,
    "roles": ["admin"],
    "permissions": ["create-users", "edit-posts"],
    "avatar": {
        "id": 1,
        "name": "avatar.jpg",
        "url": "http://localhost/storage/1/avatar.jpg",
        "thumb_url": "http://localhost/storage/1/conversions/avatar-thumb.jpg"
    },
    "created_at": "2026-05-01T10:00:00.000000Z",
    "updated_at": "2026-05-01T10:00:00.000000Z"
}
```

**Campi condizionali**:

- `roles` e `permissions`: caricati solo se richiesta relazione
- `avatar`: incluso solo se caricati i media

### API Controller

#### AuthController (API)

**Path**: `app/Http/Controllers/Api/AuthController.php`

Metodi:

- `login(Request)`: autentica utente, verifica `is_active`, genera token Sanctum
- `register(Request)`: registrazione nuovo utente (da implementare)
- `me(Request)`: informazioni dettagliate utente autenticato
- `logout(Request)`: revoca token corrente

**Validazione login**:

- Controlla credenziali (email/password)
- Verifica campo `is_active = true`
- Restituisce errore se account disattivato

---

## 10. View

### Blade Templates

**Path**: `resources/views/`

| File                                                       | Descrizione               |
| ---------------------------------------------------------- | ------------------------- |
| welcome.blade.php                                          | Landing page con search box + recent cars + CTA |
| dashboard.blade.php                                        | Dashboard utente loggato  |
| search/index.blade.php                                     | Pagina ricerca avanzata (/cerca) |
| search/results.blade.php                                   | Risultati ricerca (/search) |
| shops/show.blade.php                                       | Pagina pubblica negozio con auto e locations |
| shops/create.blade.php                                     | Form creazione negozio (auth) |
| shops/edit.blade.php                                       | Form modifica negozio (auth) |
| cars/show.blade.php                                        | Dettaglio auto con gallery |
| cars/create.blade.php                                      | Form inserimento auto (auth) |
| cars/edit.blade.php                                        | Form modifica auto (auth) |
| profile/edit.blade.php                                     | Pagina modifica profilo   |
| profile/avatar.blade.php                                   | Pagina modifica avatar    |
| profile/partials/update-profile-information-form.blade.php | Form info profilo         |
| profile/partials/update-password-form.blade.php            | Form cambio password      |
| profile/partials/delete-user-form.blade.php                | Form eliminazione account |
| layouts/navigation.blade.php                               | Navigation bar (logo → dashboard/home, solo Dashboard link per auth) |

Layout: usa componenti Blade Laravel (x-app-layout, x-slot)

### Flusso Home Page (Landing Page)
- **Visitor** → `/` (welcome.blade.php) → vede search box + 6 recent cars + CTA register
- **Search** → box nella home → `/search?brand=...&q=...` (risultati)
- **Cerca avanzata** → link "Ricerca avanzata" → `/cerca` (pagina completa)
- **Auto details** → click su auto → `/cars/{car}` (dettaglio)
- **Shop page** → da auto → `/shops/{shop}` (tutte auto del concessionario)
- **Register** → click "Registrati ora" → `/register` → entra in area privata
- **Area privata** → dashboard → crea shop → aggiungi auto → gestisci locations

---

## 11. Seeder

### AdminUserSeeder

**Path**: `database/seeders/AdminUserSeeder.php`

Crea utente amministratore:

- **Email**: admin@example.com
- **Password**: password123
- **Nome**: Amministratore
- **Ruolo**: admin (assegnato via Spatie)
- **is_active**: true

> **Nota**: Il ruolo viene assegnato da `RolePermissionSeeder`, non direttamente da questo seeder.

---

## 12. Configurazione

### config/app.php

- Nome: Laravel (default)
- Environment: production (default)
- Debug: false (default)
- URL: http://localhost

### config/database.php

- Default: SQLite
- Supporta: mysql, pgsql, sqlsrv

### config/auth.php

- Guard: web (session)
- Provider: users (eloquent)
- Password broker: users

---

## 13. Dipendenze Composer

### require

```json
{
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "intervention/image-laravel": "^1.5",
    "spatie/laravel-permission": "^7.0",
    "laravel/sanctum": "^4.0"
}
```

> **Nota**: `^8.2` significa PHP 8.2 o superiore (8.3, 8.4, ecc.)

### require-dev

```json
{
    "fakerphp/faker": "^1.23",
    "laravel/breeze": "^2.3",
    "laravel/pail": "^1.2.2",
    "laravel/pint": "^1.24",
    "laravel/sail": "^1.41",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "pestphp/pest": "^4.3",
    "pestphp/pest-plugin-laravel": "^4.0"
}
```

---

## 14. Dipendenze npm

### devDependencies

```json
{
    "@tailwindcss/forms": "^0.5.2",
    "@tailwindcss/vite": "^4.0.0",
    "alpinejs": "^3.4.2",
    "autoprefixer": "^10.4.2",
    "axios": "^1.11.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^2.0.0",
    "postcss": "^8.4.31",
    "tailwindcss": "^3.1.0",
    "vite": "^7.0.7"
}
```

---

## 15. Script npm

| Script | Comando    | Descrizione        |
| ------ | ---------- | ------------------ |
| build  | vite build | Build produzione   |
| dev    | vite       | Development server |

---

## 16. Script Composer

| Script                    | Comando        | Descrizione             |
| ------------------------- | -------------- | ----------------------- |
| setup                     | composer setup | Setup completo progetto |
| dev                       | (various)      | Development commands    |
| post-root-package-install | (scripts)      | Post install            |
| post-create-project-cmd   | (scripts)      | Post create project     |

---

## 17. Test

### Feature Tests

- `tests/Feature/ExampleTest.php`
- `tests/Feature/ProfileTest.php`
- `tests/Feature/Auth/`

### Unit Tests

- `tests/Unit/ExampleTest.php`

Framework: Pest PHP

---

## 18. Struttura File Principali

```
autosalon/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CarApiController.php
│   │   │   │   └── ShopApiController.php
│   │   │   ├── Profile/
│   │   │   │   └── AvatarController.php
│   │   │   ├── CarController.php
│   │   │   ├── LocationController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── SearchController.php
│   │   │   └── ShopController.php
│   │   ├── Requests/
│   │   │   └── ProfileUpdateRequest.php
│   │   └── Resources/
│   │       ├── CarResource.php
│   │       ├── ShopResource.php
│   │       └── UserResource.php
│   ├── Models/
│   │   ├── Brand.php
│   │   ├── Car.php
│   │   ├── Location.php
│   │   ├── Media.php
│   │   ├── Shop.php
│   │   └── User.php
│   ├── Traits/
│   │   └── HasMedia.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   ├── app.php
│   └── providers.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── (altri config)
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_02_16_105304_create_media_table.php
│   │   ├── 2026_04_30_000000_create_permission_tables.php
│   │   ├── 2026_05_01_071708_create_personal_access_tokens_table.php
│   │   ├── 2026_05_03_081219_create_brands_table.php
│   │   ├── 2026_05_03_081220_create_shops_table.php
│   │   ├── 2026_05_03_081221_create_locations_table.php
│   │   └── 2026_05_03_081222_create_cars_table.php
│   └── seeders/
│       ├── AdminUserSeeder.php
│       ├── BrandSeeder.php
│       ├── DatabaseSeeder.php
│       └── RolePermissionSeeder.php
├── public/
│   ├── index.php
│   └── (altri file pubblici)
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── layouts/
│       │   └── navigation.blade.php
│       ├── search/
│       │   ├── index.blade.php
│       │   └── results.blade.php
│       ├── shops/
│       │   ├── show.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── cars/
│       │   ├── show.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       ├── profile/
│       │   ├── edit.blade.php
│       │   ├── avatar.blade.php
│       │   └── partials/
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── web.php
│   ├── api.php
│   ├── auth.php
│   └── console.php
├── tests/
│   ├── Feature/
│   │   ├── ExampleTest.php
│   │   ├── ProfileTest.php
│   │   └── Auth/
│   └── Unit/
│       └── ExampleTest.php
├── .vscode/
│   └── settings.json
├── AGENTS.md
├── PROJECT_MANIFEST.md
├── composer.json
├── package.json
├── vite.config.js
└── phpunit.xml
```

---

## 19. Note di Implementazione

### Sistema Media

- Il sistema media è implementato con pattern **Morph** (polimorfico)
- Un singolo modello `Media` può essere associato a qualsiasi entità (User, Product, Post, ecc.)
- La tabella `media` usa `model_id` + `model_type` per le relazioni polimorfiche
- Il trait `HasMedia` fornisce metodi utility per gestire upload, cancellazione e recupero media

### Gestione Avatar

- Gli avatar sono memorizzati nella collection 'avatar'
- Un utente può avere un solo avatar primario (`is_primary = true`)
- Alla cancellazione dell'utente, avatar e gallery vengono eliminati automaticamente

### Autenticazione

- Usa **Laravel Breeze** per autenticazione completa
- Include: login, registrazione, logout, reset password, verifica email
- Middleware `auth` e `verified` per protezione route

### API REST

- Autenticazione basata su **Laravel Sanctum** (token bearer)
- **UserResource** per strutturare risposte JSON consistenti
- Campo `is_active` verificato nel login API
- Route protette con middleware `auth:sanctum`
- Supporto lazy loading per relazioni (roles, permissions, media)

### Ruoli Utente

- Gestiti da **Spatie Permission** (tabelle separate)
- Ruoli disponibili: 'admin', 'editor', 'user'
- Campo `is_active`: boolean per attivare/disattivare utenti
- Metodo `isAdmin()` per verifica rapida

---

## 20. Comandi Utili

```bash
# Setup progetto
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev

# Seed database
php artisan db:seed --class=AdminUserSeeder

# Test
./vendor/bin/pest

# Code style
./vendor/bin/pint
```

---

## 20. Credenziali Default

| Campo          | Valore            |
| -------------- | ----------------- |
| Email Admin    | admin@example.com |
| Password Admin | password123       |

---

## 21. Sistema Ruoli e Permessi (Spatie)

### Tabelle Spatie Permission

| Tabella                 | Descrizione                  |
| ----------------------- | ---------------------------- |
| `permissions`           | Permessi singoli             |
| `roles`                 | Ruoli utente                 |
| `model_has_permissions` | Permessi assegnati a modelli |
| `model_has_roles`       | Ruoli assegnati a modelli    |
| `role_has_permissions`  | Relazione ruoli-permessi     |

### Ruoli Creati

| Ruolo    | Permessi                                             |
| -------- | ---------------------------------------------------- |
| `admin`  | Tutti i permessi                                     |
| `editor` | profile.view, profile.edit, media.view, media.upload |
| `user`   | profile.view, profile.edit                           |

### Permessi Creati

| Permesso       | Descrizione        |
| -------------- | ------------------ |
| `user.view`    | Visualizza utenti  |
| `user.create`  | Crea utenti        |
| `user.edit`    | Modifica utenti    |
| `user.delete`  | Elimina utenti     |
| `profile.view` | Visualizza profilo |
| `profile.edit` | Modifica profilo   |
| `media.view`   | Visualizza media   |
| `media.upload` | Carica media       |
| `media.delete` | Elimina media      |

### Seeder

- `database/seeders/RolePermissionSeeder.php` - Crea ruoli e permessi base

### Metodi Helper (HasRoles Trait)

- `$user->hasRole('admin')` - Verifica ruolo
- `$user->assignRole('admin')` - Assegna ruolo
- `$user->removeRole('admin')` - Rimuove ruolo
- `$user->givePermissionTo('user.create')` - Assegna permesso
- `$user->can('user.edit')` - Verifica permesso

---

## 22. API REST (Laravel Sanctum)

### Endpoint API

| Metodo | URI           | Controller              | Autenticazione | Descrizione                |
| ------ | ------------- | ----------------------- | -------------- | -------------------------- |
| POST   | /api/login    | AuthController@login    | No             | Login e ottenimento token  |
| POST   | /api/register | AuthController@register | No             | Registrazione nuovo utente |
| GET    | /api/me       | AuthController@me       | Sanctum        | Info utente corrente       |
| POST   | /api/logout   | AuthController@logout   | Sanctum        | Revoca token               |
| GET    | /api/user     | -                       | Sanctum        | User (default Laravel)     |

### Tabella Token

- `personal_access_tokens` - Token API utente

### Controller

- `app/Http/Controllers/Api/AuthController.php`

### Configurazione

- `config/sanctum.php`

### Trait User

- `Laravel\Sanctum\HasApiTokens` - Gestione token API

### Metodi Helper

- `$user->createToken('name')` - Crea token
- `$user->tokens` - Relazione token
- `$user->currentAccessToken()` - Token corrente

---

_Documento generato automaticamente il 1 maggio 2026_
