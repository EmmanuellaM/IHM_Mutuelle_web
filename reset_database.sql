-- ============================================
-- Script de Nettoyage de la Base de Données
-- Pour tester le système d'exercices à 12 mois
-- ============================================

-- ATTENTION: Ce script va supprimer toutes les données liées aux exercices et sessions
-- Assurez-vous d'avoir une sauvegarde avant d'exécuter ce script!

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Supprimer les contributions (liées aux aides)
TRUNCATE TABLE contribution;

-- 2. Supprimer les aides
TRUNCATE TABLE help;

-- 3. Supprimer les agapes (liées aux sessions)
TRUNCATE TABLE agape;

-- 3b. Supprimer les renflouements
TRUNCATE TABLE renflouement;

-- 4. Supprimer les remboursements (liés aux emprunts et sessions)
TRUNCATE TABLE refund;

-- 5. Supprimer les relations emprunt-épargne
TRUNCATE TABLE borrowing_saving;

-- 6. Supprimer les emprunts (liés aux sessions)
TRUNCATE TABLE borrowing;

-- 7. Supprimer les épargnes (liées aux sessions)
TRUNCATE TABLE saving;

-- 8. Supprimer les sessions (liées aux exercices)
TRUNCATE TABLE session;

-- 9. Supprimer les exercices
TRUNCATE TABLE exercise;

-- 10. Réinitialiser les paiements des membres
UPDATE member SET inscription = 0, social_crown = 0;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- Vérification: Compter les enregistrements restants
-- ============================================
SELECT 'exercise' AS table_name, COUNT(*) AS count FROM exercise
UNION ALL
SELECT 'session', COUNT(*) FROM session
UNION ALL
SELECT 'saving', COUNT(*) FROM saving
UNION ALL
SELECT 'borrowing', COUNT(*) FROM borrowing
UNION ALL
SELECT 'refund', COUNT(*) FROM refund
UNION ALL
SELECT 'agape', COUNT(*) FROM agape
UNION ALL
SELECT 'help', COUNT(*) FROM help
UNION ALL
SELECT 'contribution', COUNT(*) FROM contribution;

-- ============================================
-- NOTE: Les membres ne sont PAS supprimés
-- Vous pouvez maintenant créer un nouvel exercice
-- avec 12 sessions pour tester le système
-- ============================================
