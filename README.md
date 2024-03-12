# FilmRating

Mit dieser Projekt können Filme, dessen Metadaten vorher in das System eingepflegt wurden, von verschiedenen Personen bewertet werden. Nachdem die Filme bewertet wurden, ist es möglich ein Filmprogramm damit zu planen und erstellen.

## dev start

./vendor/bin/sail up
./vendor/bin/sail npm run dev


## TODO

CREATE TABLE `sessions` (
	`id` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
	`ip_address` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_agent` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`payload` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_activity` INT NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `sessions_user_id_index` (`user_id`),
	INDEX `sessions_last_activity_index` (`last_activity`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
