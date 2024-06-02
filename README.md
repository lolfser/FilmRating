# FilmRating

Mit diesem Projekt können Filme, dessen Metadaten vorher in das System eingepflegt wurden, von verschiedenen Personen bewertet werden. Weiterhin ist es möglich ein Filmprogramm aus den Filmen zu erstellen.

## Installation
* checkout this project
* copy file .env.example to .env
  * set APP_URL to the target domain
  * set DB_CONNECTIOn
  * set DB_USERNAME
  * set DB_PASSWORD
* composer install
* in background or second screen: ./vendor/bin/sail up 
* ./vendor/bin/sail artisan migrate
* change in table keywords the COLLATE to 'utf8mb4_bin'
* optional: ./vendor/bin/sail artisan db:seed or set your own seeds in DB-Tables:
  * filmmodifications - no edit mask available, manipulation only via DB
  * filmsources - no edit mask available, manipulation only via DB
  * filmstatus - no edit mask available, manipulation only via DB
  * genres - no edit mask available, manipulation only via DB
  * languages - no edit mask available, manipulation only via DB
  * relationkinds - no edit mask available, manipulation only via DB
  * triggerkinds - no edit mask available, manipulation only via DB
  * days - no edit mask available, manipulation only via DB
  * programmblockmetas - no edit mask available, manipulation only via DB
  * programmblocks - no edit mask available, manipulation only via DB
  * programms - no edit mask available, manipulation only via DB
  * locations - no edit mask available, manipulation only via DB
* ./vendor/bin/sail npm run install
* in background or second screen:./vendor/bin/sail npm run dev // live vermutlich run build

## dev start

Einmal alles wie oben beschrieben einrichten, und dann bei jedem Start:
* ./vendor/bin/sail up
* ./vendor/bin/sail npm run dev

## Todos
* Keywords: -> 'filmsources.name' - kollation - utf8mb4_bin
* change in table keywords the COLLATE to 'utf8mb4_bin'
* Edit masks for all optional tables
* viewer/user permissions ausbauen
* translation files
* importer entfernen, ist zu spezifisch für das Repo / oder via config überschreibbar machen
* pkeidel/dbtolaravel entfernen
