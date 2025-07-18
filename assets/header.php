<header class="bg-gray-800 text-white flex justify-evenly items-center p-4 w-full ">
  <div class="text-blue-500 px-4 py-2 font-bold capitalize hover:text-blue-300 text-xl cursor-pointer"><a href="../index.php">home</a></div>
  <div class="flex gap-4">
    <div class="text-blue-500 px-4 py-2 capitalize hover:text-blue-300 cursor-pointer text-xl font-bold"><a href="../Public/listeLivre.php">livres</a></div>
    <div class="text-blue-500 px-4 py-2 capitalize hover:text-blue-300 cursor-pointer text-xl font-bold"><a href="../Public/listeBibliotheque.php">bibliotheques</a></div>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <!-- Liens visibles uniquement par les admins -->
        <div class="text-yellow-400 px-4 py-2 capitalize hover:text-yellow-200 cursor-pointer text-xl font-bold"><a href="../assets/creerLivre.php">Ajouter Livre</a></div>
        <div class="text-yellow-400 px-4 py-2 capitalize hover:text-yellow-200 cursor-pointer text-xl font-bold"><a href="../assets/creerBibliothequeForm.php">Ajouter Biblio</a></div>
        <div class="text-yellow-400 px-4 py-2 capitalize hover:text-yellow-200 cursor-pointer text-xl font-bold"><a href="../Public/admin.php">Admin Panel</a></div>
    <?php endif; ?>
  </div>
  <div class="flex gap-4 items-center">
    <?php if (isset($_SESSION['user_id'])): ?>
        <span class="text-gray-300">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
        <a href="../logout.php" class="text-gray-50 px-4 py-2 hover:bg-red-600 cursor-pointer duration-300 uppercase font-bold bg-red-500 rounded">Déconnexion</a>
    <?php else: ?>
        <a href="../inscription.php" class="text-blue-300 hover:text-white">S'inscrire</a>
        <a href="../login.php" class="text-gray-50 px-4 py-2 hover:bg-blue-600 cursor-pointer duration-300 uppercase font-bold bg-blue-500 rounded">Se Connecter</a>
    <?php endif; ?>
  </div>
</header>


<!-- 77 876 50 69 -->