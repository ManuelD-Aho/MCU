<header class="bg-base-100 shadow-lg sticky top-0 z-50">
    <div class="navbar container mx-auto">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="/films">Dossiers Films</a></li>
                    <li><a href="/actualites">Briefing</a></li>
                    <li><a href="/profil">Profil Agent</a></li>
                </ul>
            </div>
            <a href="/accueil" class="btn btn-ghost text-xl">
                <img src="/assets/images/logoo.png" alt="Logo MCU" class="h-8 w-auto">
                MiageCinemaUniverse
            </a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="/films">Dossiers Films</a></li>
                <li><a href="/actualites">Briefing</a></li>
                <li><a href="/profil">Profil Agent</a></li>
            </ul>
        </div>
        <div class="navbar-end">
             <?php if (isset($_SESSION['client_id'])): ?>
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img alt="Avatar" src="https://i.pravatar.cc/150?u=<?php echo $_SESSION['client_id']; ?>" />
                        </div>
                    </label>
                    <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                        <li><a href="/profil">Profil</a></li>
                        <li><a href="/deconnexion">Extraction (Déconnexion)</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/connexion" class="btn btn-primary">Accès Agent</a>
            <?php endif; ?>
        </div>
    </div>
</header>