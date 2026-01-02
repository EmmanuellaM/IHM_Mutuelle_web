-- ============================================
-- Script de Nettoyage Partiel - Garder 12 Sessions
-- Pour tester le système d'exercices à 12 mois
-- ============================================

-- OPTION 1: Supprimer toutes les sessions sauf les 12 plus récentes
-- ============================================

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Identifier l'exercice actif
SET @active_exercise_id = (SELECT id FROM exercise WHERE active = 1 LIMIT 1);

-- Identifier les 12 sessions les plus récentes de l'exercice actif
CREATE TEMPORARY TABLE temp_sessions_to_keep AS
SELECT id FROM session 
WHERE exercise_id = @active_exercise_id
ORDER BY created_at DESC
LIMIT 12;

-- Identifier les sessions à supprimer
CREATE TEMPORARY TABLE temp_sessions_to_delete AS
SELECT id FROM session 
WHERE exercise_id = @active_exercise_id
AND id NOT IN (SELECT id FROM temp_sessions_to_keep);

-- Supprimer les données liées aux sessions à supprimer
DELETE FROM contribution WHERE help_id IN (
    SELECT id FROM help WHERE session_id IN (SELECT id FROM temp_sessions_to_delete)
);

DELETE FROM help WHERE session_id IN (SELECT id FROM temp_sessions_to_delete);

DELETE FROM agape WHERE session_id IN (SELECT id FROM temp_sessions_to_delete);

DELETE FROM refund WHERE session_id IN (SELECT id FROM temp_sessions_to_delete);

DELETE FROM borrowing_saving WHERE borrowing_id IN (
    SELECT id FROM borrowing WHERE session_id IN (SELECT id FROM temp_sessions_to_delete)
);

DELETE FROM borrowing WHERE session_id IN (SELECT id FROM temp_sessions_to_delete);

DELETE FROM saving WHERE session_id IN (SELECT id FROM temp_sessions_to_delete);

-- Supprimer les sessions en trop
DELETE FROM session WHERE id IN (SELECT id FROM temp_sessions_to_delete);

-- Nettoyer les tables temporaires
DROP TEMPORARY TABLE temp_sessions_to_keep;
DROP TEMPORARY TABLE temp_sessions_to_delete;

-- Réactiver les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- Vérification: Compter les sessions restantes
-- ============================================
SELECT 
    e.id AS exercise_id,
    e.year,
    e.active,
    COUNT(s.id) AS session_count
FROM exercise e
LEFT JOIN session s ON s.exercise_id = e.id
WHERE e.active = 1
GROUP BY e.id, e.year, e.active;

-- ============================================
-- Afficher les sessions restantes
-- ============================================
SELECT 
    s.id,
    s.date,
    s.created_at
FROM session s
WHERE s.exercise_id = @active_exercise_id
ORDER BY s.created_at ASC;
