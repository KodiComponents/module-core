## KodiCMS Core

### Установка (Installation):

 * Запустить команду `composer require kodicms/core`
 
 * Добавить в `public/index.php` следующий код
 
 ```php
 ...
	|
	*/
	
	$app = require_once __DIR__.'/../bootstrap/app.php';
 
	/*
	|--------------------------------------------------------------------------
	| Tune up KodiCMS
	|--------------------------------------------------------------------------
	|
	*/
	require_once __DIR__.'/../vendor/kodicms/core/src/bootstrap/app.php';

	
	/*
	|--------------------------------------------------------------------------
	| Run The Application
	...
 ```
 
 * Добавить сервис провайдер в `app/config.php`
 
```php
'providers' => [
	...
	Illuminate\View\ViewServiceProvider::class,
	
	/*
	 * KodiCMS Service Providers...
	 */
	KodiCMS\CMS\Providers\ModuleLoaderServiceProvider::class,
	
	/*
	 * Application Service Providers...
	 */
	App\Providers\AppServiceProvider::class,
	...
]
```
 
 * Выполнить установку системы *(Install CMS)* `php artisan cms:modules:install`.
 * Выполнить миграцию таблиц модулей `php artisan modules:migrate --seed`.

Система готова к использованию