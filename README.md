# ztpai
Nalepiej pracować pod dockerem w środowisku linuksowym, albo z WSL2 (używając folderu pod systemem plikowym linuksowym np. ext4, a nie windowsowym ntfs).
Wtedy działa live reload (HMR) w vite, jest szybciej i jest mniej problemów z uprawnieniami do plików.

Instalowanie zależności backendu:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php composer install
```

Czyszczenie cache:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console cache:clear
```

Tworzenie nowego kontrolera:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:controller SomeController
```

Tworzenie nowych modeli (entities):
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:entity
```

Generowanie struktury frontend:
```bash
docker compose run --rm -it node-base npm create vite@latest
```

Instalowanie nowych zależności frontendu:
```bash
docker compose run --rm -it node-base npm install react-router
```

Instalowanie zależności frontendu:
```bash
docker compose up node-install
```

Uruchomienie serwera frontendu vite w trybie deweloperskim:
```bash
docker compose up node-dev
```

Sprawdzenie poprawności mappingu i bazy danych:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:validate -v
```

Sprawdzenie poprawności mappingu:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:mapping:info
```

Wypisanie SQL z rozbieżnościami między bazą danych a mappingiem:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:update --dump-sql
```

Wypisanie pełnego SQL do stworzenia bazy danych na postawie mappingu:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:schema:create --dump-sql
```

Czyszczenie cache metadanych:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console doctrine:cache:clear-metadata
```

Stworzenie nowego procesora api-platform:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php php bin/console make:state-processor
```

Uruchomienie komendy w działającym kontenerze:
```bash
docker compose exec -it -u "$(id -u):$(id -g)" php bash
```
