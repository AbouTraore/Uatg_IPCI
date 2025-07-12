<?php
// Redirection pour gérer les deux noms de fichiers
// Ce fichier redirige automatiquement vers le bon fichier

// Déterminer le bon fichier à utiliser
$target_file = 'histoire_maladie.php';

// Rediriger vers le bon fichier en conservant tous les paramètres
$query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: ' . $target_file . $query_string);
exit;
?> 