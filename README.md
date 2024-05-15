# FilmRating

Mit dieser Projekt können Filme, dessen Metadaten vorher in das System eingepflegt wurden, von verschiedenen Personen bewertet werden. Nachdem die Filme bewertet wurden, ist es möglich ein Filmprogramm damit zu planen und erstellen.

## Installation
./vendor/bin/sail artisan migrate
--> keywords COLLATE 'utf8mb4_bin',
./vendor/bin/sail artisan db:seed

## dev start

./vendor/bin/sail up
./vendor/bin/sail npm run dev


## Todo - Keywords
-> 'filmsources.name' - kollation - utf8mb4_bin
