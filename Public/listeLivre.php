<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
  <title>Document</title>
</head>
<body class="bg-gray-100 flex items-center font-mono justify-start gap-12 min-h-screen flex-col">
  <?php 
      require_once __DIR__ . "/../assets/header.php";
      require_once "../assets/creerBibliotheque.php";
      require_once "../data/config.php";
      require_once "../Class/livre.php";

  ?>
    <div>
        <?php require_once __DIR__ . "/../assets/livre.php"; ?>
    </div>
</body>
</html>