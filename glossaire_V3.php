<?php
session_start();

// Configuration de la base de données
$host = 'localhost';
$dbname = 'glossaire_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Création des tables si elles n'existent pas
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    role ENUM('etudiant', 'enseignant') DEFAULT 'etudiant',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$pdo->exec($sql_users);

$sql_terms = "CREATE TABLE IF NOT EXISTS terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    french_term VARCHAR(255) NOT NULL,
    english_term VARCHAR(255) NOT NULL,
    french_definition TEXT NOT NULL,
    english_definition TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    code_example TEXT,
    search_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_french (french_term),
    INDEX idx_english (english_term),
    INDEX idx_category (category)
)";
$pdo->exec($sql_terms);

$sql_suggestions = "CREATE TABLE IF NOT EXISTS term_suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    french_term VARCHAR(255) NOT NULL,
    english_term VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    notes TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
)";
$pdo->exec($sql_suggestions);

// Insérer des utilisateurs de test si la table est vide
$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($user_count == 0) {
    $test_users = [
        ['mourad.sehboub@collegelasalle.com', password_hash('Mourad', PASSWORD_DEFAULT), 'Sehboub', 'Mourad', 'enseignant'],
        ['ana.sofia@lcieducation.com', password_hash('sofia', PASSWORD_DEFAULT), 'Sofia', 'Ana', 'etudiant'],
        ['jean.dupont@lcieducation.com', password_hash('jean123', PASSWORD_DEFAULT), 'Dupont', 'Jean', 'etudiant']
    ];

    $stmt = $pdo->prepare("INSERT INTO users (email, password, nom, prenom, role) VALUES (?, ?, ?, ?, ?)");
    foreach ($test_users as $user) {
        $stmt->execute($user);
    }
}

// Insérer des termes de base si la table est vide
$term_count = $pdo->query("SELECT COUNT(*) FROM terms")->fetchColumn();
if ($term_count == 0) {
    $sample_terms = [
        ['API', 'Application Programming Interface', 'Ensemble de règles et de protocoles permettant à des applications de communiquer entre elles.', 'Set of rules and protocols allowing applications to communicate with each other.', '🌐 Programmation Web', 'fetch("https://api.example.com/data")\n  .then(response => response.json())\n  .then(data => console.log(data));'],
        ['Base de données', 'Database', 'Collection organisée de données structurées permettant le stockage efficace.', 'Organized collection of structured data allowing efficient storage.', '🗄️ Bases de Données', 'CREATE DATABASE glossaire_db;'],
        ['SQL', 'Structured Query Language', 'Langage standardisé pour gérer les bases de données relationnelles.', 'Standardized language for managing relational databases.', '🗄️ Bases de Données', 'SELECT * FROM users WHERE age > 18;']
    ];

    $stmt = $pdo->prepare("INSERT INTO terms (french_term, english_term, french_definition, english_definition, category, code_example) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($sample_terms as $term) {
        $stmt->execute($term);
    }
}

// Fonctions utilitaires
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
        'nom' => $_SESSION['user_nom'] ?? '',
        'prenom' => $_SESSION['user_prenom'] ?? ''
    ];
}

function isTeacher() {
    return isLoggedIn() && $_SESSION['user_role'] === 'enseignant';
}

// Traitement de la connexion
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password_input, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];

        $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = 'Email ou mot de passe incorrect';
    }
}

// Traitement de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Traitement de la proposition d'un terme par un étudiant
$suggestion_success = '';
$suggestion_error = '';
if (isLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suggest_term'])) {
    $french_term = trim($_POST['french_term']);
    $english_term = trim($_POST['english_term']);
    $category = trim($_POST['category']);
    $notes = trim($_POST['notes']);

    if (!empty($french_term) && !empty($english_term) && !empty($category)) {
        $stmt = $pdo->prepare("INSERT INTO term_suggestions (user_id, french_term, english_term, category, notes, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        if ($stmt->execute([$_SESSION['user_id'], $french_term, $english_term, $category, $notes])) {
            $suggestion_success = 'Merci ! Votre proposition a été envoyée aux enseignants.';
        } else {
            $suggestion_error = 'Erreur lors de l\'envoi de la proposition.';
        }
    } else {
        $suggestion_error = 'Veuillez remplir tous les champs obligatoires.';
    }
}

// Traitement de l'ajout d'un terme par un enseignant
$add_term_success = '';
$add_term_error = '';
if (isTeacher() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_term'])) {
    $french_term = trim($_POST['french_term']);
    $english_term = trim($_POST['english_term']);
    $french_definition = trim($_POST['french_definition']);
    $english_definition = trim($_POST['english_definition']);
    $category = trim($_POST['category']);
    $code_example = trim($_POST['code_example']);

    if (!empty($french_term) && !empty($english_term) && !empty($french_definition) && !empty($english_definition) && !empty($category)) {
        $stmt = $pdo->prepare("INSERT INTO terms (french_term, english_term, french_definition, english_definition, category, code_example) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$french_term, $english_term, $french_definition, $english_definition, $category, $code_example])) {
            $add_term_success = 'Le terme a été ajouté avec succès au glossaire !';

            // Si c'était basé sur une suggestion, la marquer comme approuvée
            if (isset($_POST['suggestion_id']) && !empty($_POST['suggestion_id'])) {
                $pdo->prepare("UPDATE term_suggestions SET status = 'approved', approved_by = ?, approved_at = NOW() WHERE id = ?")
                    ->execute([$_SESSION['user_id'], $_POST['suggestion_id']]);
            }
        } else {
            $add_term_error = 'Erreur lors de l\'ajout du terme.';
        }
    } else {
        $add_term_error = 'Veuillez remplir tous les champs obligatoires.';
    }
}

// Traitement du rejet d'une suggestion
if (isTeacher() && isset($_GET['reject_suggestion'])) {
    $suggestion_id = (int)$_GET['reject_suggestion'];
    $pdo->prepare("UPDATE term_suggestions SET status = 'rejected', approved_by = ?, approved_at = NOW() WHERE id = ?")
        ->execute([$_SESSION['user_id'], $suggestion_id]);
    header('Location: ' . $_SERVER['PHP_SELF'] . '#suggestions');
    exit;
}

// Traitement de la recherche
$search_results = [];
$search_term = '';
$selected_category = '';

if (isLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_term = trim($_POST['search']);
    $selected_category = $_POST['category'] ?? '';

    if (!empty($search_term)) {
        $sql = "SELECT * FROM terms WHERE (french_term LIKE ? OR english_term LIKE ? OR french_definition LIKE ? OR english_definition LIKE ?)";
        $params = ["%$search_term%", "%$search_term%", "%$search_term%", "%$search_term%"];

        if (!empty($selected_category)) {
            $sql .= " AND category = ?";
            $params[] = $selected_category;
        }

        $sql .= " ORDER BY search_count DESC LIMIT 20";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($search_results as $result) {
            $pdo->prepare("UPDATE terms SET search_count = search_count + 1 WHERE id = ?")->execute([$result['id']]);
        }
    }
}

// Récupérer les catégories
$allowed_categories = [
    '🌐 Programmation Web',
    '📦 Programmation Orientée Objet',
    '🗄️ Bases de Données',
    '🔌 Réseaux Informatiques',
    '🔒 Sécurité Informatique',
    '🎮 Programmation Jeux Vidéo',
    '🎨 Design UI',
    '🤖 Intelligence Artificielle',
    '⚙️ Développement Logiciel',
    '🧠 Structures de Données & Algorithmes',
    '💻 Systèmes d\'Exploitation'
];

$categories = $pdo->query("SELECT DISTINCT category FROM terms ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$categories = array_values(array_intersect($categories, $allowed_categories));

// Récupérer les termes populaires
$popular_terms = [];
if (isLoggedIn()) {
    $popular_terms = $pdo->query("SELECT * FROM terms ORDER BY search_count DESC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les suggestions en attente (pour les enseignants)
$pending_suggestions = [];
if (isTeacher()) {
    $pending_suggestions = $pdo->query("
        SELECT ts.*, u.prenom, u.nom, u.email 
        FROM term_suggestions ts 
        JOIN users u ON ts.user_id = u.id 
        WHERE ts.status = 'pending' 
        ORDER BY ts.created_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}

// Statistiques
$total_terms = $pdo->query("SELECT COUNT(*) FROM terms")->fetchColumn();
$total_suggestions = 0;
if (isLoggedIn()) {
    if (isTeacher()) {
        $total_suggestions = $pdo->query("SELECT COUNT(*) FROM term_suggestions WHERE status = 'pending'")->fetchColumn();
    } else {
        $total_suggestions = $pdo->query("SELECT COUNT(*) FROM term_suggestions WHERE user_id = " . $_SESSION['user_id'])->fetchColumn();
    }
}

$current_user = getCurrentUser();

// Récupérer une suggestion pour pré-remplir le formulaire d'ajout
$suggestion_to_add = null;
if (isTeacher() && isset($_GET['add_from_suggestion'])) {
    $suggestion_id = (int)$_GET['add_from_suggestion'];
    $stmt = $pdo->prepare("SELECT * FROM term_suggestions WHERE id = ?");
    $stmt->execute([$suggestion_id]);
    $suggestion_to_add = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glossaire Informatique Bilingue - Collège LaSalle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.4) 100%);
            z-index: -1;
        }

        .bg-slider {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .bg-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 2s ease-in-out;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: brightness(0.9) contrast(1.1);
        }

        .bg-slide.active {
            opacity: 0.4;
            animation: subtleZoom 20s ease-in-out infinite alternate;
        }

        .bg-slide::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
        }

        @keyframes subtleZoom {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); }
        .search-glow:focus { box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.5); }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse-dot { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .gradient-bg {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.97) 0%, rgba(118, 75, 162, 0.97) 100%);
            color: #ffffff;
        }

        .gradient-bg h1, .gradient-bg h2, .gradient-bg h3, .gradient-bg p, .gradient-bg span {
            color: #fff !important;
        }

        body { color: #1f1f1f; }
        .card-hover, .term-card, .bg-white { color: #222 !important; }
        .term-card { border-left: 4px solid #667eea; }
        .translation-badge { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .category-badge { transition: all 0.2s ease; }
        .category-badge:hover { transform: scale(1.05); }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal.active { display: flex; align-items: center; justify-content: center; }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .login-modal {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .disabled-search {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .notification {
            animation: slideInDown 0.5s ease;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Background Slider -->
<div class="bg-slider">
    <div class="bg-slide active" style="background-image: url('lasalle-college-montreal-campus0.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.1.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.2.jpg');"></div>
</div>

<!-- Notifications -->
<?php if ($suggestion_success): ?>
    <div class="notification fixed top-20 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 max-w-md">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="font-medium"><?= htmlspecialchars($suggestion_success) ?></p>
        </div>
    </div>
    <script>setTimeout(() => document.querySelector('.notification').remove(), 5000);</script>
<?php endif; ?>

<?php if ($add_term_success): ?>
    <div class="notification fixed top-20 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl z-50 max-w-md">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="font-medium"><?= htmlspecialchars($add_term_success) ?></p>
        </div>
    </div>
    <script>setTimeout(() => document.querySelector('.notification').remove(), 5000);</script>
<?php endif; ?>

<!-- Navigation -->
<nav class="gradient-bg shadow-xl sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-3">
                <div>
                    <img src="CollegeLaSalle_Logo.png" alt="Logo Collège LaSalle" class="w-21 h-20 object-contain">
                </div>
                <div>
                    <!--<h1 class="text-white font-bold text-xl">Glossaire Collège LaSalle</h1>-->
                    <p class="text-purple-300 text-xl">Informatique Bilingue</p>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <?php if (isLoggedIn()): ?>
                    <a href="#search" class="text-white hover:text-purple-200 transition font-medium">🔍 Recherche</a>
                    <?php if (isTeacher()): ?>
                        <a href="#suggestions" class="text-white hover:text-purple-200 transition font-medium relative">
                            📝 Propositions
                            <?php if ($total_suggestions > 0): ?>
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                <?= $total_suggestions ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <button onclick="openAddTermModal()" class="text-white hover:text-purple-200 transition font-medium">
                            ➕ Ajouter un terme
                        </button>
                    <?php else: ?>
                        <button onclick="openSuggestModal()" class="text-white hover:text-purple-200 transition font-medium">
                            💡 Proposer un terme
                        </button>
                    <?php endif; ?>
                    <span class="text-white text-sm">
                        👤 <?= htmlspecialchars($current_user['prenom'] . ' ' . $current_user['nom']) ?>
                        <span class="ml-2 px-2 py-1 bg-white/20 rounded text-xs">
                            <?= $current_user['role'] === 'enseignant' ? '👨‍🏫 Enseignant' : '👨‍🎓 Étudiant' ?>
                        </span>
                    </span>
                    <a href="?logout=1" class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-medium">
                        🚪 Déconnexion
                    </a>
                <?php else: ?>
                    <button onclick="openLoginModal()" class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-medium">
                        👤 Connexion
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Modal de Connexion -->
<?php if (!isLoggedIn()): ?>
    <div id="loginModal" class="modal active">
        <div class="login-modal">
            <div class="text-center mb-6">
                <div class="text-5xl mb-3">🔐</div>
                <h2 class="text-2xl font-bold text-gray-800">Connexion Requise</h2>
                <p class="text-gray-600 mt-2">Veuillez vous connecter pour accéder au glossaire</p>
            </div>

            <?php if ($login_error): ?>
                <div class="bg-red-50 border-2 border-red-300 rounded-lg p-4 mb-4">
                    <p class="text-red-800 font-medium">❌ <?= htmlspecialchars($login_error) ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">📧 Email</label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="votre.email@collegelasalle.com">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">🔑 Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="••••••••">
                </div>

                <button type="submit" name="login"
                        class="w-full gradient-bg text-white px-6 py-4 rounded-lg hover:opacity-90 transition font-bold text-lg">
                    🚀 Se Connecter
                </button>
            </form>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-xs text-gray-600 font-semibold mb-2">📋 Comptes de test:</p>
                <div class="text-xs text-gray-700 space-y-1">
                    <p><strong>Enseignant:</strong> mourad.sehboub@collegelasalle.com / Mourad</p>
                    <p><strong>Étudiant:</strong> ana.sofia@lcieducation.com / sofia</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Proposition de Terme (Étudiants) -->
<div id="suggestModal" class="modal">
    <div class="modal-content">
        <div class="gradient-bg text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold">💡 Proposer un Nouveau Terme</h3>
                <button onclick="closeSuggestModal()" class="text-white hover:text-gray-200 text-3xl">&times;</button>
            </div>
        </div>

        <form method="POST" action="" class="p-6">
            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-900">
                    <strong>ℹ️ Information:</strong> Votre proposition sera envoyée aux enseignants pour validation avant d'être ajoutée au glossaire.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">🇫🇷 Terme en français *</label>
                    <input type="text" name="french_term" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="Ex: Algorithme">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">🇬🇧 Terme en anglais *</label>
                    <input type="text" name="english_term" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="Ex: Algorithm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">📁 Catégorie *</label>
                <select name="category" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <?php foreach ($allowed_categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">📝 Notes / Contexte (optionnel)</label>
                <textarea name="notes" rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                          placeholder="Expliquez pourquoi ce terme devrait être ajouté, donnez des exemples d'utilisation..."></textarea>
            </div>

            <button type="submit" name="suggest_term"
                    class="w-full gradient-bg text-white px-6 py-4 rounded-lg hover:opacity-90 transition font-bold text-lg">
                📤 Envoyer la Proposition
            </button>
        </form>
    </div>
</div>

<!-- Modal Ajout de Terme (Enseignants) -->
<div id="addTermModal" class="modal <?= $suggestion_to_add ? 'active' : '' ?>">
    <div class="modal-content">
        <div class="gradient-bg text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold">➕ Ajouter un Terme au Glossaire</h3>
                <button onclick="closeAddTermModal()" class="text-white hover:text-gray-200 text-3xl">&times;</button>
            </div>
        </div>

        <form method="POST" action="" class="p-6">
            <?php if ($suggestion_to_add): ?>
                <input type="hidden" name="suggestion_id" value="<?= $suggestion_to_add['id'] ?>">
                <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4 mb-6">
                    <p class="text-sm text-green-900">
                        <strong>✅ Proposition de:</strong> <?= htmlspecialchars($suggestion_to_add['prenom'] ?? '') ?> <?= htmlspecialchars($suggestion_to_add['nom'] ?? '') ?>
                    </p>
                    <?php if (!empty($suggestion_to_add['notes'])): ?>
                        <p class="text-xs text-green-800 mt-2"><strong>Notes:</strong> <?= htmlspecialchars($suggestion_to_add['notes']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">🇫🇷 Terme en français *</label>
                    <input type="text" name="french_term" required
                           value="<?= $suggestion_to_add ? htmlspecialchars($suggestion_to_add['french_term']) : '' ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="Ex: Algorithme">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">🇬🇧 Terme en anglais *</label>
                    <input type="text" name="english_term" required
                           value="<?= $suggestion_to_add ? htmlspecialchars($suggestion_to_add['english_term']) : '' ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                           placeholder="Ex: Algorithm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">📖 Définition française *</label>
                <textarea name="french_definition" rows="3" required
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                          placeholder="Définition complète en français..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">📖 Définition anglaise *</label>
                <textarea name="english_definition" rows="3" required
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none"
                          placeholder="Full English definition..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">📁 Catégorie *</label>
                <select name="category" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <?php foreach ($allowed_categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"
                            <?= ($suggestion_to_add && $suggestion_to_add['category'] === $cat) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">💻 Exemple de code (optionnel)</label>
                <textarea name="code_example" rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none font-mono text-sm"
                          placeholder="// Exemple de code..."></textarea>
            </div>

            <button type="submit" name="add_term"
                    class="w-full gradient-bg text-white px-6 py-4 rounded-lg hover:opacity-90 transition font-bold text-lg">
                ✅ Ajouter au Glossaire
            </button>
        </form>
    </div>
</div>

<!-- Hero Section avec Recherche -->
<div class="gradient-bg text-white py-16 animate-fade-in">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-bold mb-4">🌟 Ton Compagnon Bilingue</h2>
            <p class="text-xl text-purple-100 mb-2">Réussis tes cours d'informatique en français avec confiance</p>
            <div class="flex items-center justify-center space-x-2 text-sm text-purple-200">
                <span>🇬🇧 English</span>
                <span>↔</span>
                <span>Français 🇫🇷</span>
                <span class="ml-3">•</span>
                <span class="ml-3">🤖 Powered by AI Translation</span>
            </div>
        </div>

        <!-- Barre de Recherche -->
        <div class="max-w-4xl mx-auto <?= !isLoggedIn() ? 'disabled-search' : '' ?>">
            <div class="glass-effect rounded-2xl p-8 shadow-2xl">
                <?php if (!isLoggedIn()): ?>
                    <div class="text-center py-8">
                        <div class="text-5xl mb-4">🔒</div>
                        <p class="text-xl font-bold mb-2">Recherche Désactivée</p>
                        <p class="text-sm text-purple-200">Connectez-vous pour effectuer des recherches</p>
                    </div>
                <?php else: ?>
                    <form method="POST" action="#results">
                        <div class="relative mb-6">
                            <input
                                    type="text"
                                    name="search"
                                    value="<?= htmlspecialchars($search_term) ?>"
                                    placeholder="🔍 Cherche un terme... (ex: 'API', 'base de données', 'algorithm')"
                                    class="w-full px-6 py-5 pr-32 text-lg text-gray-800 rounded-xl search-glow border-0 focus:outline-none"
                            >
                            <button type="submit" class="absolute right-2 top-2 bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition font-semibold">
                                Chercher
                            </button>
                        </div>

                        <!-- Filtres par Catégorie -->
                        <div class="flex flex-wrap gap-2 justify-center">
                            <span class="text-sm text-white/80 self-center font-medium">Filtrer par :</span>
                            <?php foreach ($categories as $cat): ?>
                                <button type="submit" name="category" value="<?= htmlspecialchars($cat) ?>"
                                        class="category-badge px-4 py-2 <?= $selected_category === $cat ? 'bg-white text-purple-700' : 'bg-white/20 text-white' ?> hover:bg-white/30 rounded-full text-sm font-medium transition">
                                    <?= htmlspecialchars($cat) ?>
                                </button>
                            <?php endforeach; ?>
                            <button type="submit" name="category" value=""
                                    class="category-badge px-4 py-2 <?= empty($selected_category) ? 'bg-white text-purple-700 font-bold' : 'bg-white/20 text-white' ?> rounded-full text-sm font-medium transition">
                                ✨ Tous
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Stats Communautaires -->
            <?php if (isLoggedIn()): ?>
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div class="glass-effect rounded-xl p-4 text-center card-hover">
                        <div class="text-3xl font-bold mb-1"><?= $total_terms ?></div>
                        <div class="text-sm text-purple-200">Termes disponibles</div>
                    </div>
                    <div class="glass-effect rounded-xl p-4 text-center card-hover">
                        <div class="text-3xl font-bold mb-1"><?= count($categories) ?></div>
                        <div class="text-sm text-purple-200">Catégories</div>
                    </div>
                    <div class="glass-effect rounded-xl p-4 text-center card-hover">
                        <div class="text-3xl font-bold mb-1"><?= $total_suggestions ?></div>
                        <div class="text-sm text-purple-200"><?= isTeacher() ? 'Propositions en attente' : 'Mes propositions' ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isLoggedIn()): ?>

    <!-- Section Résultats de Recherche -->
    <?php if (!empty($search_results)): ?>
        <div id="results" class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        🔍 Résultats de Recherche
                        <span class="ml-3 text-sm font-normal text-gray-600">(<?= count($search_results) ?> termes trouvés)</span>
                    </h2>
                    <p class="text-gray-600 mt-1">Pour : "<?= htmlspecialchars($search_term) ?>"</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <?php foreach ($search_results as $index => $term): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 term-card card-hover animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">
                                <?= htmlspecialchars($term['category']) ?>
                            </span>
                                    <span class="text-xs text-gray-400"><?= $term['search_count'] ?> recherches</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                    🇫🇷 <?= htmlspecialchars($term['french_term']) ?>
                                </h3>
                                <p class="text-xl text-gray-600">
                                    🇬🇧 <?= htmlspecialchars($term['english_term']) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Définitions -->
                        <div class="space-y-3 mb-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-blue-900 mb-1">📖 Définition française :</p>
                                <p class="text-gray-700 leading-relaxed text-sm">
                                    <?= htmlspecialchars($term['french_definition']) ?>
                                </p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm font-semibold text-green-900 mb-1">📖 English definition:</p>
                                <p class="text-gray-700 leading-relaxed text-sm">
                                    <?= htmlspecialchars($term['english_definition']) ?>
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($term['code_example'])): ?>
                            <div class="bg-gray-900 rounded-lg p-3 mb-4">
                                <p class="text-xs text-gray-400 mb-2">💻 Exemple de code :</p>
                                <code class="text-green-400 text-xs block whitespace-pre-wrap"><?= htmlspecialchars($term['code_example']) ?></code>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])): ?>
        <div class="container mx-auto px-4 py-12">
            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-8 text-center max-w-2xl mx-auto">
                <div class="text-6xl mb-4">🤔</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Aucun terme trouvé</h2>
                <p class="text-gray-600 mb-6">
                    Aucun résultat pour "<?= htmlspecialchars($search_term) ?>".
                </p>
                <div class="space-y-3">
                    <button onclick="document.querySelector('input[name=search]').focus()"
                            class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                        🔍 Nouvelle recherche
                    </button>
                    <?php if (!isTeacher()): ?>
                        <button onclick="openSuggestModal()"
                                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium ml-3">
                            💡 Proposer ce terme
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section Propositions (Enseignants) -->
    <?php if (isTeacher() && !empty($pending_suggestions)): ?>
        <div id="suggestions" class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        📝 Propositions en Attente
                        <span class="ml-3 pulse-dot text-orange-500">●</span>
                    </h2>
                    <p class="text-gray-600 mt-1"><?= count($pending_suggestions) ?> proposition(s) à valider</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <?php foreach ($pending_suggestions as $suggestion): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="text-sm text-gray-600 mb-2">
                                    Proposé par: <strong><?= htmlspecialchars($suggestion['prenom'] . ' ' . $suggestion['nom']) ?></strong>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?= date('d/m/Y à H:i', strtotime($suggestion['created_at'])) ?>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-semibold">
                        En attente
                    </span>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                🇫🇷 <?= htmlspecialchars($suggestion['french_term']) ?>
                            </h3>
                            <p class="text-lg text-gray-600 mb-2">
                                🇬🇧 <?= htmlspecialchars($suggestion['english_term']) ?>
                            </p>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                        <?= htmlspecialchars($suggestion['category']) ?>
                    </span>
                        </div>

                        <?php if (!empty($suggestion['notes'])): ?>
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-gray-700 mb-1">📝 Notes de l'étudiant:</p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($suggestion['notes']) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="?add_from_suggestion=<?= $suggestion['id'] ?>"
                               class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition font-medium text-sm text-center">
                                ✅ Approuver & Ajouter
                            </a>
                            <a href="?reject_suggestion=<?= $suggestion['id'] ?>"
                               onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette proposition ?')"
                               class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition font-medium text-sm text-center">
                                ❌ Rejeter
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Section Termes Populaires -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                    🔥 Termes Populaires
                    <span class="ml-3 pulse-dot text-red-500">●</span>
                </h2>
                <p class="text-gray-600 mt-1">Les plus recherchés</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <?php foreach ($popular_terms as $index => $term): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 term-card card-hover animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">
                                <?= htmlspecialchars($term['category']) ?>
                            </span>
                                <span class="text-xs text-gray-400"><?= $term['search_count'] ?> recherches</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                🇫🇷 <?= htmlspecialchars($term['french_term']) ?>
                            </h3>
                            <p class="text-xl text-gray-600">
                                🇬🇧 <?= htmlspecialchars($term['english_term']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700 leading-relaxed text-sm">
                            <?= htmlspecialchars(substr($term['french_definition'], 0, 150)) ?>...
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php endif; // Fin isLoggedIn ?>

<!-- Footer -->
<footer class="gradient-bg text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">📚</span>
                    Glossaire Collège LaSalle
                </h3>
                <p class="text-purple-200 text-sm leading-relaxed">
                    Ton compagnon bilingue pour réussir tes cours d'informatique en français.
                </p>
            </div>

            <div>
                <h4 class="font-bold mb-4">Liens Rapides</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#search" class="text-purple-200 hover:text-white transition">🔍 Recherche</a></li>
                    <?php if (isTeacher()): ?>
                        <li><a href="#suggestions" class="text-purple-200 hover:text-white transition">📝 Propositions</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-4">Statistiques</h4>
                <ul class="space-y-2 text-sm text-purple-200">
                    <li>📊 <?= $total_terms ?> termes disponibles</li>
                    <li>📁 <?= count($categories) ?> catégories</li>
                    <?php if (isLoggedIn()): ?>
                        <li>👥 Connecté en tant que <?= $current_user['role'] ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="border-t border-purple-400 mt-8 pt-8 text-center">
            <p class="text-purple-200 text-sm">
                © 2024 Glossaire Informatique Collège LaSalle. Créé pour soutenir les étudiants.
            </p>
        </div>
    </div>
</footer>

<script>
    // Background slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.bg-slide');
    const totalSlides = slides.length;

    function changeSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    }

    setInterval(changeSlide, 5000);

    // Modal functions
    function openLoginModal() {
        document.getElementById('loginModal').classList.add('active');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.remove('active');
    }

    function openSuggestModal() {
        document.getElementById('suggestModal').classList.add('active');
    }

    function closeSuggestModal() {
        document.getElementById('suggestModal').classList.remove('active');
    }

    function openAddTermModal() {
        document.getElementById('addTermModal').classList.add('active');
    }

    function closeAddTermModal() {
        document.getElementById('addTermModal').classList.remove('active');
        window.location.href = '<?= $_SERVER['PHP_SELF'] ?>';
    }

    // Fermer les modals en cliquant à l'extérieur
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    }

    // Fermer avec la touche ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

</body>
</html>