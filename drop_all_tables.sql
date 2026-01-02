-- ============================================
-- Script de Réinitialisation Complète de la BD
-- Supprime TOUTES les tables et les recrée
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer toutes les tables
DROP TABLE IF EXISTS contribution_tontine;
DROP TABLE IF EXISTS contribution;
DROP TABLE IF EXISTS help;
DROP TABLE IF EXISTS help_type;
DROP TABLE IF EXISTS tontine;
DROP TABLE IF EXISTS tontine_type;
DROP TABLE IF EXISTS agape;
DROP TABLE IF EXISTS refund;
DROP TABLE IF EXISTS borrowing_saving;
DROP TABLE IF EXISTS borrowing;
DROP TABLE IF EXISTS saving;
DROP TABLE IF EXISTS session;
DROP TABLE IF EXISTS exercise;
DROP TABLE IF EXISTS registration;
DROP TABLE IF EXISTS social_fund;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS financial_aid;
DROP TABLE IF EXISTS chat_message;
DROP TABLE IF EXISTS member;
DROP TABLE IF EXISTS administrator;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS migration;

SET FOREIGN_KEY_CHECKS = 1;

-- Afficher les tables restantes
SHOW TABLES;
