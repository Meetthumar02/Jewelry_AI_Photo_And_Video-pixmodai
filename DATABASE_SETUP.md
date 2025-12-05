# Database Setup Guide

This project uses **two separate databases** for AI PhotoShoot and Creative AI features:

- `ai_photoshoot` - Database for AI PhotoShoot feature
- `creative_ai` - Database for Creative AI feature

## Setup Methods

### Method 1: Using SQL Script (Recommended)

1. Open MySQL command line or phpMyAdmin
2. Run the SQL script:
   ```sql
   source database/create_databases.sql
   ```
   Or copy and paste the contents of `database/create_databases.sql` into your MySQL client.

### Method 2: Using PHP Script

Run the PHP setup script:
```bash
php database/setup_databases.php
```

### Method 3: Manual Creation

Create the databases manually in MySQL:

```sql
CREATE DATABASE IF NOT EXISTS `ai_photoshoot` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS `creative_ai` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Environment Configuration

Add these to your `.env` file (optional - defaults will be used if not set):

```env
# AI PhotoShoot Database
AI_PHOTOSHOOT_DB_HOST=127.0.0.1
AI_PHOTOSHOOT_DB_PORT=3306
AI_PHOTOSHOOT_DB_DATABASE=ai_photoshoot
AI_PHOTOSHOOT_DB_USERNAME=your_username
AI_PHOTOSHOOT_DB_PASSWORD=your_password

# Creative AI Database
CREATIVE_AI_DB_HOST=127.0.0.1
CREATIVE_AI_DB_PORT=3306
CREATIVE_AI_DB_DATABASE=creative_ai
CREATIVE_AI_DB_USERNAME=your_username
CREATIVE_AI_DB_PASSWORD=your_password
```

If these are not set, the system will use the default database credentials from `DB_HOST`, `DB_USERNAME`, and `DB_PASSWORD`.

## Running Migrations

After creating the databases, run the migrations:

```bash
php artisan migrate
```

The migrations are configured to automatically use the correct database connections:
- `ai_photo_shoots` table → `ai_photoshoot` database
- `creative_ai_generations` table → `creative_ai` database

## Unified View

Both AI PhotoShoot and Creative AI screens are now combined in a single unified view file:
- **File**: `resources/views/user/ai_studio.blade.php`
- **Routes**: 
  - `/ai-photoshoot` - Shows unified view with PhotoShoot tab active
  - `/creative-ai` - Shows unified view with Creative tab active

## Verification

To verify the databases were created successfully:

```sql
SHOW DATABASES LIKE 'ai_photoshoot';
SHOW DATABASES LIKE 'creative_ai';
```

## Troubleshooting

1. **Connection Error**: Make sure your MySQL credentials are correct in `.env`
2. **Permission Denied**: Ensure your MySQL user has CREATE DATABASE privileges
3. **Migration Fails**: Check that both databases exist before running migrations

