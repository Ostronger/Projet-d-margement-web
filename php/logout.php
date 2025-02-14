<?php
session_start();
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Redirige vers index.html après la déconnexion
header("Location: ../index.html");
exit();
?>
