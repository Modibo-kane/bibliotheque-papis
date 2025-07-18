<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();// On démarre la session sur chaque page où le header est inclus
// pour avoir accès aux variables de session.
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>
<body class="bg-gray-100 flex items-center justify-start gap-12 min-h-screen flex-col">
  <?php 
      require_once "../assets/header.php";
      require_once "../assets/bibliotheque.php";
  ?>
</body>
</html>