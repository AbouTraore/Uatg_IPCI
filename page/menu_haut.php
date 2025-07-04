<?php
require_once("identifier.php");
?>

<style>
/* Styles spécifiques au menu du haut */
.modern-topbar {
    position: fixed;
    top: 0;
    left: 280px;
    right: 0;
    height: 80px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--gray-200);
    box-shadow: var(--shadow-lg);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    animation: slideInTop 0.6s ease-out;
}

@keyframes slideInTop {
    from {
        opacity: 0;
        transform: translateY(-100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.topbar-search {
    flex: 1;
    max-width: 400px;
    margin-right: 32px;
}

.search-container {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input {
    width: 100%;
    padding: 12px 20px 12px 48px;
    border: 2px solid var(--gray-200);
    border-radius: 16px;
    background: var(--gray-50);
    font-size: 0.95rem;
    transition: all 0.3s ease;
    outline: none;
}

.search-input:focus {
    border-color: var(--primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(0, 71, 171, 0.1);
}

.search-icon {
    position: absolute;
    left: 16px;
    color: var(--gray-400);
    transition: color 0.3s ease;
}

.search-input:focus + .search-icon {
    color: var(--primary);
}

.topbar-user {
    display: flex;
    align-items: center;
    position: relative;
}

.user-dropdown {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    background: var(--gray-50);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
}

.user-dropdown:hover {
    background: var(--gray-100);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    margin-right: 12px;
    object-fit: cover;
    border: 2px solid var(--primary-light);
}

.user-info {
    display: flex;
    flex-direction: column;
    margin-right: 8px;
}

.user-name {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.dropdown-arrow {
    color: var(--gray-400);
    transition: transform 0.3s ease;
}

.user-dropdown:hover .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--gray-200);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    margin-top: 8px;
    overflow: hidden;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: var(--gray-700);
    text-decoration: none;
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--gray-100);
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background: var(--gray-50);
    color: var(--primary);
    text-decoration: none;
}

.dropdown-item i {
    margin-right: 12px;
    width: 16px;
}

.sidebar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--gray-600);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.sidebar-toggle:hover {
    background: var(--gray-100);
    color: var(--primary);
}

@media (max-width: 768px) {
    .modern-topbar {
        left: 0;
        padding: 0 16px;
    }
    
    .sidebar-toggle {
        display: block;
    }
    
    .user-info {
        display: none;
    }
    
    .topbar-search {
        display: none;
    }
}
</style>

<!-- Menu du haut moderne -->
<nav class="modern-topbar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>

    <div class="topbar-search">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Rechercher un patient, dossier...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>

    <div class="topbar-user">
        <div class="user-dropdown" onclick="toggleDropdown()">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='%230047ab'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-size='16' font-weight='bold'%3EMr%3C/text%3E%3C/svg%3E" alt="User" class="user-avatar">
            <div class="user-info">
                <div class="user-name">Mr <?php echo $_SESSION['user']['Login_user'] ?? 'Admin'; ?></div>
                <div class="user-role">
                    <?php echo $_SESSION['user']['Type_user'] ?? 'Utilisateur'; ?>
                </div>
            </div>
            <i class="fas fa-chevron-down dropdown-arrow"></i>
        </div>
        
        <div class="dropdown-menu" id="userDropdown">
            <a class="dropdown-item" href="profil.php">
                <i class="fas fa-user"></i>
                Profile
            </a>
            <a class="dropdown-item" href="#">
                <i class="fas fa-cogs"></i>
                Paramètres
            </a>
            <a class="dropdown-item" href="#" onclick="showLogoutModal()">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </div>
    </div>
</nav>

<!-- Modal de déconnexion -->
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirmation de déconnexion</h5>
        </div>
        <div class="modal-body">
            Voulez-vous vraiment vous déconnecter ?
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="hideLogoutModal()">Annuler</button>
            <a href="deconnection.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>
</div>

<script>
// Gestion du dropdown utilisateur
function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Fermer le dropdown en cliquant ailleurs
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (!userDropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Gestion du modal de déconnexion
function showLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('show');
}

function hideLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('show');
}

// Gestion du sidebar mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}
</script> page/p