# ztpai
Nalepiej pracować pod dockerem w środowisku linuksowym, albo z WSL2 (używając folderu pod systemem plikowym linuksowym np. ext4, a nie windowsowym ntfs).
Wtedy działa live reload (HMR) w vite, jest szybciej i jest mniej problemów z uprawnieniami do plików.

Instalowanie zależności backendu:
```bash
docker compose run --rm -it -u "$(id -u):$(id -g)" php composer install
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
docker compose run --rm -it node npm create vite@latest
```

Instalowanie zależności frontendu:
```bash
docker compose up node-install
```

Uruchomienie serwera frontendu vite w trybie deweloperskim:
```bash
docker compose up node-dev
```