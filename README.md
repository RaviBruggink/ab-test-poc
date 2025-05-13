# Moonly A/B Test POC 🚀

[![Laravel](https://img.shields.io/badge/Laravel-10-red)](https://laravel.com/)
[![Docker](https://img.shields.io/badge/Docker-Enabled-blue)](https://www.docker.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

Proof of Concept voor A/B Testing binnen **Aurora**, ontwikkeld tijdens een stage bij **Moonly Software**.  
Vergelijk AI-modellen binnen specifieke use cases en stem live op de beste output!

---

## 📋 Inhoudsopgave
- [Demo](#demo)
- [Installatie](#installatie)
- [Gebruik](#gebruik)
- [Handige Artisan Commands](#handige-artisan-commands)
- [Toekomstige Uitbreidingen](#toekomstige-uitbreidingen)
- [Over](#over)

---

## 🖥️ Demo

De applicatie draait lokaal op:

[http://localhost](http://localhost)

In de applicatie kun je:
- Een use case kiezen
- Twee AI-modellen selecteren
- Outputs van beide modellen bekijken
- Stemmen op het beste antwoord  
Live resultaten worden weergegeven in een dynamische **Chart.js** grafiek, met opties voor filters en een **colorblind-vriendelijke modus**.

---

## ⚙️ Installatie

### 1. Repository clonen en projectmap openen
```bash
git clone https://github.com/jouw-gebruiker/ab-test-poc.git
cd ab-test-poc
```

### 2. PHP dependencies installeren
```bash
composer install
```

### 3. `.env` bestand aanmaken
```bash
cp .env.example .env
```

### 4. Docker containers starten via Laravel Sail
```bash
./vendor/bin/sail up -d
```

### 5. Applicatiesleutel genereren
```bash
./vendor/bin/sail artisan key:generate
```

### 6. Database aanmaken
Log in op de MySQL container:
```bash
docker exec -it ab-test-poc-mysql-1 bash
mysql -u root -p
```
Voer in MySQL de volgende commando's uit:
```sql
CREATE DATABASE ab_test_poc;
GRANT ALL PRIVILEGES ON ab_test_poc.* TO 'sail'@'%';
FLUSH PRIVILEGES;
EXIT;
```

### 7. Database migreren en seeden
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

---

## 🧪 Gebruik

- Navigeer naar [http://localhost](http://localhost).
- Selecteer een use case en twee AI-modellen.
- Vergelijk de outputs.
- Stem eenvoudig door op de beste output te klikken.
- Bekijk real-time grafieken en filteropties.

---

## 🛠️ Handige Artisan Commands

```bash
# Containers starten
./vendor/bin/sail up -d

# Containers stoppen
./vendor/bin/sail down

# Migraties + seeden opnieuw uitvoeren
./vendor/bin/sail artisan migrate:fresh --seed

# Artisan CLI gebruiken
./vendor/bin/sail artisan
```

---

## 🌟 Toekomstige Uitbreidingen

- Dynamisch laden van AI outputs
- Live updaten van grafieken zonder reload
- Admin dashboard voor modelbeheer
- API-koppelingen naar echte AI systemen

---

## 🏢 Over

Ontwikkeld binnen mijn stageproject bij **Moonly Software**.  

---

> © 2025 Moonly Software