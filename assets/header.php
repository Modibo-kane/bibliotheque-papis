<header class="bg-gray-800 text-white shadow-md w-full sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo / Home link -->
            <a href="../index.php" class="text-blue-400 font-bold text-xl hover:text-blue-300">Ma Biblio</a>

            <!-- Desktop Menu (caché sur mobile) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="../Public/listeLivre.php" class="text-gray-300 hover:text-white">Livres</a>
                <a href="../Public/listeBibliotheque.php" class="text-gray-300 hover:text-white">Bibliothèques</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="../Public/admin.php" class="text-yellow-400 hover:text-yellow-200">Admin</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-300">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                    <a href="../logout.php" class="bg-red-500 px-3 py-2 rounded text-white font-semibold hover:bg-red-600">Déconnexion</a>
                <?php else: ?>
                    <a href="../login.php" class="bg-blue-500 px-3 py-2 rounded text-white font-semibold hover:bg-blue-600">Se Connecter</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button (visible uniquement sur mobile) -->
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu (déroulant) -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2">
            <a href="../Public/listeLivre.php" class="block px-2 py-1 text-gray-300 hover:bg-gray-700 rounded">Livres</a>
            <a href="../Public/listeBibliotheque.php" class="block px-2 py-1 text-gray-300 hover:bg-gray-700 rounded">Bibliothèques</a>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <hr class="border-gray-700">
                <a href="../Public/admin.php" class="block px-2 py-1 text-yellow-400 hover:bg-gray-700 rounded">Admin Panel</a>
                <a href="../assets/creerLivre.php" class="block px-2 py-1 text-gray-300 hover:bg-gray-700 rounded">Ajouter Livre</a>
                <a href="../assets/creerBibliothequeForm.php" class="block px-2 py-1 text-gray-300 hover:bg-gray-700 rounded">Ajouter Biblio</a>
            <?php endif; ?>
            <hr class="border-gray-600">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="px-2 py-1 text-gray-400">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></div>
                <a href="../logout.php" class="block w-full text-left bg-red-500 px-3 py-2 rounded text-white font-semibold hover:bg-red-600">Déconnexion</a>
            <?php else: ?>
                <a href="../login.php" class="block w-full text-left bg-blue-500 px-3 py-2 rounded text-white font-semibold hover:bg-blue-600">Se Connecter</a>
                <a href="../inscription.php" class="block px-2 py-1 text-gray-300 hover:bg-gray-700 rounded">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>
    <script>
        // Petit script pour gérer l'ouverture/fermeture du menu mobile
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</header>