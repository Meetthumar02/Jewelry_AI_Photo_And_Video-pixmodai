-- SQL Script to create ai-photoshoot and creative-ai databases
-- Run this script in MySQL to create both databases

-- Create ai-photoshoot database
CREATE DATABASE IF NOT EXISTS `ai_photoshoot` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create creative-ai database
CREATE DATABASE IF NOT EXISTS `creative_ai` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Grant privileges (adjust username as needed)
-- GRANT ALL PRIVILEGES ON `ai_photoshoot`.* TO 'your_username'@'localhost';
-- GRANT ALL PRIVILEGES ON `creative_ai`.* TO 'your_username'@'localhost';
-- FLUSH PRIVILEGES;

-- Show created databases
SHOW DATABASES LIKE 'ai_photoshoot';
SHOW DATABASES LIKE 'creative_ai';

