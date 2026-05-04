# AGENTS.md

## Istruzioni per AI Agent - Progetto Autosalon

### Comandi di verifica (eseguire dopo modifiche)
- **Code style**: `./vendor/bin/pint`
- **Test**: `./vendor/bin/pest`
- **Setup completo**: `composer setup`

### Comandi disponibili
- **Sviluppo**: `composer dev` (server + queue + vite)
- **Build frontend**: `npm run build`
- **Frontend dev**: `npm run dev`

### Stack tecnologico
- Laravel 12 con PHP ^8.2
- Spatie Permission (ruoli: admin, editor, user)
- Intervention Image (gestione media)
- Laravel Sanctum (API authentication)
- Pest PHP (testing)
- Tailwind CSS + Alpine.js + Vite

### Convenzioni progetto
- Modelli: `App\Models\` (User, Media)
- Trait personalizzati: `App\Traits\` (HasMedia)
- Controller API: `App\Http\Controllers\Api\`
- Controller Profile: `App\Http\Controllers\Profile\`
- Rotte web: `routes/web.php`
- Rotte API: `routes/api.php`
- Middleware: `auth`, `verified`, `auth:sanctum`

### Struttura database
- Migrations in `database/migrations/`
- Seeders: `AdminUserSeeder`, `RolePermissionSeeder`
- Tabelle Spatie: `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions`
- Tabella media: polimorfica (model_id + model_type)

### Note operative
- Non toccare `vendor/` (escluso in VS Code settings)
- Credenziali admin: admin@example.com / password123
- Sistema media usa pattern morph (HasMedia trait)
- `.env` configurato per MySQL (autosalone database)
- **IMPORTANTE**: In `routes/web.php`, le rotte statiche di creazione (`/shops/create`, `/cars/create`) devono essere definite **PRIMA** delle rotte dinamiche (`/shops/{shop}`, `/cars/{car}`) per evitare collisioni di routing che causano 404. Questo è testato in `tests/Feature/DashboardUserRouteTest.php`

### Controller e Route
- **ProfileController**: `app/Http/Controllers/ProfileController.php` (edit, update, destroy)
- **AvatarController**: `app/Http/Controllers/Profile/AvatarController.php` (edit, update, destroy)
- **SearchController**: `app/Http/Controllers/SearchController.php` (index → /cerca, search → /search)
- **ShopController**: `app/Http/Controllers/ShopController.php` (show, create, store, edit, update - prevent multiple shops, auto-assign editor role)
- **CarController**: `app/Http/Controllers/CarController.php` (create, store + image handling, show, edit, update)
  - `store()`: Converte `shop_id`/`location_id` vuoti in `null`, valida max 5 immagini, salva tramite `addMedia()` (Intervention Image ridimensiona a 800px + 80% qualità)
- **LocationController**: `app/Http/Controllers/LocationController.php` (store, destroy)
- **AdminController**: `app/Http/Controllers/AdminController.php` (usersIndex, shopsIndex, carsIndex, toggleUserStatus, toggleShopStatus, toggleCarStatus)
- **RegisteredUserController**: `app/Http/Controllers/Auth/RegisteredUserController.php` (assign 'user' role on registration)
- **AuthController API**: `app/Http/Controllers/Api/AuthController.php` (login, register, me, logout)
- **ShopApiController**: `app/Http/Controllers/Api/ShopApiController.php` (index, show, requestDealer)
- **CarApiController**: `app/Http/Controllers/Api/CarApiController.php` (index, show, store, update)
- **UserResource**: `app/Http/Resources/UserResource.php` per API JSON responses
- **ShopResource**: `app/Http/Resources/ShopResource.php` per API JSON responses
- **CarResource**: `app/Http/Resources/CarResource.php` per API JSON responses

### Flusso Home Page
- **Home** (`/`): welcome.blade.php con recent cars + search box → porta a `/search`
- **Cerca** (`/cerca`): SearchController@index - pagina ricerca avanzata
- **Search Results** (`/search`): SearchController@search - risultati ricerca
- **Navigation**: Logo → dashboard (logged in) o home (guest). Solo "Dashboard" link per utenti autenticati
- **Guest**: welcome mostra "Login" e "Register" (non "Dashboard")
- **Auth**: welcome mostra link profilo con avatar, "Dashboard" visibile

### Flusso Utenti e Ruoli
- **Registrazione**: tutti si registrano come `user` (ruolo automatico)
- **User semplice**: può creare annunci auto singoli (`cars.create`)
- **Editor/Dealer**: crea shop → auto-assign ruolo `editor` → può gestire shop, auto, locations
- **Admin**: controllo totale (`admin` ruolo) → gestione users/shops/cars da `/admin/*`

### Dashboard per Ruolo
- **Admin**: vede pannello admin con link a users, shops, cars
- **Editor**: vede "Il tuo Spazio" con link al shop, inserimento auto, gestione locations
- **User**: vede "I tuoi Annunci" con le proprie auto e possibilità di creare negozio

### Validazione
- **ProfileUpdateRequest**: `app/Http/Requests/ProfileUpdateRequest.php`
  - name: required, string, max:255
  - email: required, string, lowercase, email, max:255, unique

### API REST (Sanctum)
| Metodo | URI | Controller | Middleware | Descrizione |
|--------|-----|------------|------------|-------------|
| POST | /api/login | AuthController@login | - | Login e generazione token |
| POST | /api/register | AuthController@register | - | Registrazione utente |
| GET | /api/user | closure | auth:sanctum | Info utente corrente |
| GET | /api/me | AuthController@me | auth:sanctum | Info dettagliate utente |
| POST | /api/logout | AuthController@logout | auth:sanctum | Logout e revoca token |

### Sistema Ruoli e Permessi (Spatie)
- **Ruoli**: admin (tutti i permessi), editor (profile.view/edit, media.view/upload), user (profile.view/edit)
- **Permessi**: user.view/create/edit/delete, profile.view/edit, media.view/upload/delete
- **Seeder**: `database/seeders/RolePermissionSeeder.php`

### Sistema Media (Intervention Image)
- **Trait**: `app/Traits/HasMedia.php`
- **Modello**: `app/Models/Media.php` (polimorfico)
- **Metodi**: `addMedia()`, `primaryMedia()`, `getMediaByCollection()`, `deleteMedia()`
- **Validazione**: MIME image/jpeg, image/png, image/gif, image/webp
- **Ottimizzazione**: ridimensionamento automatico per avatar (300x300), gallery (800px larghezza + 80% qualità), post (1200px)
- **Note**: Nessun limite dimensione file in validazione (es. `max:2048`) - Intervention Image ottimizza automaticamente
- **Gallery auto**: max 5 immagini caricate, prima immagine = `is_primary=true`

### View Blade
- `resources/views/welcome.blade.php` - Landing page con search box + recent cars + CTA register
- `resources/views/dashboard.blade.php` - Dashboard differenziata per ruolo (admin/shop/user)
- `resources/views/profile/edit.blade.php` - Modifica profilo
- `resources/views/profile/avatar.blade.php` - Modifica avatar
- `resources/views/search/index.blade.php` - Pagina ricerca avanzata (/cerca)
- `resources/views/search/results.blade.php` - Risultati ricerca (/search)
- `resources/views/shops/show.blade.php` - Pagina pubblica negozio
- `resources/views/shops/create.blade.php` - Crea negozio (auth) - previene shop multipli
- `resources/views/shops/edit.blade.php` - Modifica negozio (auth)
- `resources/views/cars/show.blade.php` - Dettaglio auto
- `resources/views/cars/create.blade.php` - Inserisci auto (auth) - gestione errori validazione + anteprima 5 immagini + old()
- `resources/views/cars/edit.blade.php` - Modifica auto (auth)
- `resources/views/admin/users.blade.php` - Admin: gestione users (attiva/disattiva)
- `resources/views/admin/shops.blade.php` - Admin: gestione shops
- `resources/views/admin/cars.blade.php` - Admin: gestione auto

### Test
- Framework: Pest PHP
- Feature tests: `tests/Feature/ExampleTest.php`, `ProfileTest.php`
- Unit tests: `tests/Unit/ExampleTest.php`

### Configurazione VS Code
- File: `.vscode/settings.json` (esclusioni vendor per evitare lock)
- Impostazioni globali: `files.watcherExclude`, `search.exclude`, `php.languageServer.exclude`
- Estensioni: DevSense PHP Tools, IntelliPHP, Composer PHP

### Script Composer
- `composer setup`: install + key + migrate + npm install + build
- `composer dev`: server + queue + vite concurrently
- `composer test`: config:clear + artisan test

### Note importanti
- Campo `role` rimosso da users, usare Spatie Permission
- Evento `deleting` su User cancella avatar e gallery automaticamente
- `is_active` su User per attivare/disattivare account
- Login API verifica `is_active = true`
