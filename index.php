<?php
/**
 * Redirection automatique vers le dossier web/
 * Ceci permet d'accéder à l'application via http://votre-ip/Mutuelle_web/
 * au lieu de http://votre-ip/Mutuelle_web/web/
 */

header('Location: web/index.php');
exit;
