<div class="col-md-3">
    <!-- Bouton pour afficher/masquer le menu sur petits écrans -->
    <button class="btn btn-outline-secondary d-md-none mb-3 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar navigation">
        <i class="fas fa-bars me-2"></i> Menu Espace
    </button>

    <div class="collapse d-md-block" id="sidebarMenu">
        <div class="list-group">
            <a href="/mon-compte/planning.html" class="list-group-item list-group-item-action <?php echo (isset($page_active) && $page_active === 'planning') ? 'active' : ''; ?>">Planning des passages</a>
            <a href="/mon-compte/profil.html" class="list-group-item list-group-item-action <?php echo (isset($page_active) && $page_active === 'profil') ? 'active' : ''; ?>">Gestion du profil</a>
        </div>
    </div>
</div>