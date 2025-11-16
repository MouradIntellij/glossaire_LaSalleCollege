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
            background: linear-gradient(
                    to bottom,
                    rgba(0, 0, 0, 0.2) 0%,
                    rgba(0, 0, 0, 0.4) 100%
            );
            z-index: -1;
        }
        /* === Styles du diaporama d’arrière-plan === */
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
            filter: brightness(0.9) contrast(1.1); /* améliore le rendu */
        }

        .bg-slide.active {
            opacity: 0.4; /* visible mais doux */
        }

        /* Filigrane coloré léger et homogène */
        .bg-slide::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                    135deg,
                    rgba(102, 126, 234, 0.15) 0%,  /* bleu pâle */
                    rgba(118, 75, 162, 0.15) 100%  /* violet pâle */
            );
        }

        /* Optionnel : effet lent de zoom pour plus de dynamisme */
        .bg-slide.active {
            animation: subtleZoom 20s ease-in-out infinite alternate;
        }

        @keyframes subtleZoom {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
        .glass-effect { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); }
        .search-glow:focus { box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.5); }
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .pulse-dot { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .term-card { border-left: 4px solid #667eea; }
        .translation-badge { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .category-badge { transition: all 0.2s ease; }
        .category-badge:hover { transform: scale(1.05); }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 20px; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto; animation: slideDown 0.3s ease; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }


        /* === 1️⃣ Correction globale du contraste du contenu === */
        body {
            color: #1f1f1f; /* texte plus foncé par défaut */
        }

        /* === 2️⃣ Ajustement du texte dans les sections violettes === */
        .gradient-bg {
            background: linear-gradient(35deg, rgba(102, 126, 234, 0.97) 0%, rgba(118, 75, 162, 0.97) 100%);
            color: #ffffff;
        }

        .gradient-bg h1,
        .gradient-bg h2,
        .gradient-bg h3,
        .gradient-bg p,
        .gradient-bg span {
            color: #fff !important; /* texte bien blanc sur fond violet */
        }

        /* === 3️⃣ Amélioration de la lisibilité dans les cartes et blocs transparents === */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25); /* légèrement plus opaque */
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Textes dans les cartes et blocs */
        .card-hover,
        .term-card,
        .glass-effect,
        .bg-white {
            color: #222 !important; /* gris foncé pour meilleure lecture */
        }

        /* === 4️⃣ (Optionnel) renforcer contraste du texte violet clair === */
        .text-purple-200 { color: #e5d4ff !important; }
        .text-purple-300 { color: #d2b8ff !important; }
        .text-purple-400 { color: #b889ff !important; }

    </style>
</head>
<body class="bg-gray-50">

<!-- Background Slider -->
<div class="bg-slider">
    <div class="bg-slide active" style="background-image: url('lasalle-college-montreal-campus0.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.1.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.2.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.3.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.4.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.5.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.6.jpg');"></div>
    <div class="bg-slide" style="background-image: url('AZ-lasalle-college-montreal-campus.7.jpg');"></div>
</div>

<?php
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

// Création de la table si elle n'existe pas
$sql = "CREATE TABLE IF NOT EXISTS terms (
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
$pdo->exec($sql);

// Vérifier et ajouter les colonnes manquantes si la table existait déjà
try {
    // Vérifier si la colonne search_count existe
    $result = $pdo->query("SHOW COLUMNS FROM terms LIKE 'search_count'")->fetch();
    if (!$result) {
        $pdo->exec("ALTER TABLE terms ADD COLUMN search_count INT DEFAULT 0 AFTER category");
        echo '<script>console.log("Colonne search_count ajoutée");</script>';
    }
} catch (PDOException $e) {
    // La colonne existe déjà ou autre erreur
}

try {
    // Vérifier si la colonne code_example existe
    $result = $pdo->query("SHOW COLUMNS FROM terms LIKE 'code_example'")->fetch();
    if (!$result) {
        $pdo->exec("ALTER TABLE terms ADD COLUMN code_example TEXT AFTER category");
        echo '<script>console.log("Colonne code_example ajoutée");</script>';
    }
} catch (PDOException $e) {
    // La colonne existe déjà ou autre erreur
}

try {
    // Vérifier si la colonne created_at existe
    $result = $pdo->query("SHOW COLUMNS FROM terms LIKE 'created_at'")->fetch();
    if (!$result) {
        $pdo->exec("ALTER TABLE terms ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo '<script>console.log("Colonne created_at ajoutée");</script>';
    }
} catch (PDOException $e) {
    // La colonne existe déjà ou autre erreur
}

// Créer les index s'ils n'existent pas
try {
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_french ON terms(french_term)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_english ON terms(english_term)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_category ON terms(category)");
} catch (PDOException $e) {
    // Les index existent déjà
}

// Insertion de données d'exemple si la table est vide
$count = $pdo->query("SELECT COUNT(*) FROM terms")->fetchColumn();
if ($count == 0) {
    $sample_terms = [
        ['API', 'Application Programming Interface', 'Ensemble de règles et de protocoles permettant à des applications de communiquer entre elles. Une API définit les méthodes et les données disponibles pour interagir avec un service.', 'Set of rules and protocols allowing applications to communicate with each other. An API defines the methods and data available to interact with a service.', '🌐 Programmation Web', 'fetch("https://api.example.com/data")\n  .then(response => response.json())\n  .then(data => console.log(data));'],
        ['Base de données', 'Database', 'Collection organisée de données structurées permettant le stockage, la récupération et la manipulation efficace des informations.', 'Organized collection of structured data allowing efficient storage, retrieval, and manipulation of information.', '🗄️ Bases de Données', 'CREATE DATABASE glossaire_db;\nUSE glossaire_db;'],
        ['SQL', 'Structured Query Language', 'Langage standardisé pour gérer les bases de données relationnelles. Permet de créer, lire, mettre à jour et supprimer des données.', 'Standardized language for managing relational databases. Allows creating, reading, updating and deleting data.', '🗄️ Bases de Données', 'SELECT * FROM users WHERE age > 18;'],
        ['PDO', 'PHP Data Objects', 'Extension PHP fournissant une interface pour accéder aux bases de données de manière sécurisée avec des requêtes préparées.', 'PHP extension providing an interface to access databases securely with prepared statements.', '🗄️ Bases de Données', '$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");\n$stmt->execute([\'id\' => $userId]);'],
        ['HTTP', 'HyperText Transfer Protocol', 'Protocole de communication utilisé pour transférer des données sur le web entre clients et serveurs.', 'Communication protocol used to transfer data on the web between clients and servers.', '🔌 Réseaux Informatiques', 'GET /api/users HTTP/1.1\nHost: example.com'],
        ['MVC', 'Model-View-Controller', 'Pattern architectural séparant la logique métier (Model), l\'interface utilisateur (View) et la gestion des interactions (Controller).', 'Architectural pattern separating business logic (Model), user interface (View) and interaction management (Controller).', '🌐 Programmation Web', NULL],
        ['Encapsulation', 'Encapsulation', 'Principe de la POO qui consiste à cacher les détails d\'implémentation d\'une classe et à contrôler l\'accès aux données.', 'OOP principle that hides implementation details of a class and controls access to data.', '📦 Programmation Orientée Objet', 'class User {\n  private $password;\n  public function setPassword($pwd) {\n    $this->password = hash(\'sha256\', $pwd);\n  }\n}'],
        ['Firewall', 'Firewall', 'Système de sécurité réseau contrôlant le trafic entrant et sortant selon des règles de sécurité définies.', 'Network security system controlling incoming and outgoing traffic based on defined security rules.', '🔒 Sécurité Informatique', NULL],
        ['JSON', 'JavaScript Object Notation', 'Format léger d\'échange de données basé sur la syntaxe JavaScript, facile à lire pour les humains et les machines.', 'Lightweight data interchange format based on JavaScript syntax, easy for humans and machines to read.', '🌐 Programmation Web', '{"nom": "Dupont", "age": 21, "email": "dupont@example.com"}'],
        ['Algorithme', 'Algorithm', 'Ensemble d\'instructions finies et précises permettant de résoudre un problème ou d\'effectuer une tâche spécifique.', 'Finite and precise set of instructions to solve a problem or perform a specific task.', '🤖 Intelligence Artificielle', NULL]
    ];

    $stmt = $pdo->prepare("INSERT INTO terms (french_term, english_term, french_definition, english_definition, category, code_example) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($sample_terms as $term) {
        $stmt->execute($term);
    }
}

// Traitement de la recherche
$search_results = [];
$search_term = '';
$selected_category = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
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

        // Incrémenter le compteur de recherches
        foreach ($search_results as $result) {
            $pdo->prepare("UPDATE terms SET search_count = search_count + 1 WHERE id = ?")->execute([$result['id']]);
        }
    }
}

// Récupérer les catégories uniques
//$categories = $pdo->query("SELECT DISTINCT category FROM terms ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);


// 🌟 Liste des catégories autorisées et organisées
$allowed_categories = [
    '🌐 Programmation Web',
    '📦 Programmation Orientée Objet',
    '🗄️ Bases de Données',
    '🔌 Réseaux Informatiques',
    '🔒 Sécurité Informatique',
    '🎮 Programmation Jeux Vidéo',
    '🎮 Jeux Vidéo',
    '🎨 Design UI',
    '🤖 Intelligence Artificielle',
    '⚙️ Développement Logiciel',
    '🧠 Structures de Données & Algorithmes',
    '💻 Systèmes d’Exploitation'
];

// On récupère toutes les catégories présentes dans la base
$categories = $pdo->query("SELECT DISTINCT category FROM terms ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

// On garde uniquement celles figurant dans la liste autorisée
$categories = array_values(array_intersect($categories, $allowed_categories));

// Et on les trie selon l’ordre défini dans la liste
$categories = array_values(array_filter($allowed_categories, fn($cat) => in_array($cat, $categories)));


// Récupérer les termes populaires
$popular_terms = $pdo->query("SELECT * FROM terms ORDER BY search_count DESC LIMIT 4")->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$total_terms = $pdo->query("SELECT COUNT(*) FROM terms")->fetchColumn();
?>

<!-- Navigation -->
<nav class="gradient-bg shadow-xl sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-3">
<!--                <div class="text-3xl">📚</div>-->

                <div class="w-22 h-22 flex items-center justify-center">
                    <img src="logo.png" alt="Logo Collège LaSalle" class="h-16 w-auto object-contain rounded-lg">
                </div>
                <div>

                    <h1 class="text-white font-bold text-xl">Glossaire Collège LaSalle</h1>
                    <p class="text-purple-200 text-xs">Informatique Bilingue</p>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="#search" class="text-white hover:text-purple-200 transition font-medium">🔍 Recherche</a>
                <a href="#browse" class="text-white hover:text-purple-200 transition font-medium">📖 Parcourir</a>
                <a href="#favorites" class="text-white hover:text-purple-200 transition font-medium">⭐ Favoris</a>
                <button class="glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition font-medium">
                    👤 Connexion
                </button>
        </div>
    </div>
</nav>

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
        <div class="max-w-4xl mx-auto">
            <div class="glass-effect rounded-2xl p-8 shadow-2xl">
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
            </div>

            <!-- Stats Communautaires -->
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
                    <div class="text-3xl font-bold mb-1"><?= array_sum(array_column($popular_terms, 'search_count')) ?></div>
                    <div class="text-sm text-purple-200">Recherches totales</div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            <p class="text-sm font-semibold text-blue-900 mb-1">📝 Définition française :</p>
                            <p class="text-gray-700 leading-relaxed text-sm">
                                <?= htmlspecialchars($term['french_definition']) ?>
                            </p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm font-semibold text-green-900 mb-1">📝 English definition:</p>
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

                    <!-- Boutons de Traduction -->
                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="openTranslationModal('<?= htmlspecialchars($term['french_term']) ?>', 'fr')"
                                class="translation-badge text-white px-4 py-3 rounded-lg hover:opacity-90 transition font-medium text-sm">
                            🌍 Traduire depuis FR
                        </button>
                        <button onclick="openTranslationModal('<?= htmlspecialchars($term['english_term']) ?>', 'en')"
                                class="translation-badge text-white px-4 py-3 rounded-lg hover:opacity-90 transition font-medium text-sm">
                            🌍 Traduire depuis EN
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="container mx-auto px-4 py-12">
        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-8 text-center max-w-2xl mx-auto">
            <div class="text-6xl mb-4">🤔</div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Aucun terme trouvé</h2>
            <p class="text-gray-600 mb-6">
                Aucun résultat pour "<?= htmlspecialchars($search_term) ?>".
                Essayez avec d'autres mots-clés ou consultez les termes populaires ci-dessous.
            </p>
            <button onclick="document.querySelector('input[name=search]').focus()"
                    class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                🔍 Nouvelle recherche
            </button>
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

                <div class="grid grid-cols-2 gap-2">
                    <button onclick="openTranslationModal('<?= htmlspecialchars($term['french_term']) ?>', 'fr')"
                            class="translation-badge text-white px-4 py-3 rounded-lg hover:opacity-90 transition font-medium text-sm">
                        🌍 Traduire FR
                    </button>
                    <button onclick="openTranslationModal('<?= htmlspecialchars($term['english_term']) ?>', 'en')"
                            class="translation-badge text-white px-4 py-3 rounded-lg hover:opacity-90 transition font-medium text-sm">
                        🌍 Traduire EN
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de Traduction -->
<div id="translationModal" class="modal">
    <div class="modal-content">
        <div class="gradient-bg text-white p-6 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold">🌍 Traduction IA</h3>
                <button onclick="closeTranslationModal()" class="text-white hover:text-gray-200 text-3xl">&times;</button>
            </div>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">📝 Texte à traduire :</label>
                <div id="sourceText" class="bg-gray-50 p-4 rounded-lg text-gray-800 font-medium text-lg"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">🌐 Langue cible :</label>
                <select id="targetLanguage" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none">
                    <option value="">-- Sélectionnez une langue --</option>
                    <option value="fr">🇫🇷 Français</option>
                    <option value="en">🇬🇧 Anglais</option>
                    <option value="es">🇪🇸 Espagnol</option>
                    <option value="de">🇩🇪 Allemand</option>
                    <option value="it">🇮🇹 Italien</option>
                    <option value="pt">🇵🇹 Portugais</option>
                    <option value="ru">🇷🇺 Russe</option>
                    <option value="zh">🇨🇳 Chinois</option>
                    <option value="ja">🇯🇵 Japonais</option>
                    <option value="ar">🇸🇦 Arabe</option>
                </select>
            </div>

            <button onclick="translateText()" class="w-full gradient-bg text-white px-6 py-4 rounded-lg hover:opacity-90 transition font-bold text-lg">
                🚀 Traduire
            </button>

            <div id="translationResult" class="mt-6 hidden">
                <div class="bg-green-50 border-2 border-green-300 rounded-lg p-6">
                    <h4 class="text-lg font-bold text-green-900 mb-3">✅ Traduction :</h4>
                    <p id="translatedText" class="text-gray-800 text-lg leading-relaxed"></p>
                </div>
            </div>

            <div id="translationError" class="mt-6 hidden">
                <div class="bg-red-50 border-2 border-red-300 rounded-lg p-6">
                    <h4 class="text-lg font-bold text-red-900 mb-3">⚠️ Erreur :</h4>
                    <p id="errorMessage" class="text-gray-800"></p>
                </div>
            </div>

            <div id="translationLoading" class="mt-6 hidden text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                <p class="mt-3 text-gray-600">Traduction en cours...</p>
            </div>
        </div>
    </div>
</div>

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
                    Propulsé par l'IA et créé pour les étudiants.
                </p>
            </div>

            <div>
                <h4 class="font-bold mb-4">Liens Rapides</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#search" class="text-purple-200 hover:text-white transition">🔍 Recherche</a></li>
                    <li><a href="#browse" class="text-purple-200 hover:text-white transition">📖 Parcourir</a></li>
                    <li><a href="#about" class="text-purple-200 hover:text-white transition">ℹ️ À propos</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-4">À Propos</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-purple-200 hover:text-white transition">📜 Contexte Loi 14</a></li>
                    <li><a href="#" class="text-purple-200 hover:text-white transition">👨‍💻 L'équipe</a></li>
                    <li><a href="#" class="text-purple-200 hover:text-white transition">📊 Statistiques</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-purple-400 mt-8 pt-8 text-center">
            <p class="text-purple-200 text-sm">
                © 2024 Glossaire Informatique Collège LaSalle. Créé dans le cadre de la Loi 14 pour soutenir les étudiants anglophones.
            </p>
            <p class="text-purple-300 text-xs mt-2">
                🤖 Propulsé par MyMemory Translation API | 💻 Développé avec PHP, MySQL & Tailwind CSS
            </p>
        </div>
    </div>
</footer>

<script>
    let currentSourceLang = 'fr';
    let currentSourceText = '';

    // Background slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.bg-slide');
    const totalSlides = slides.length;

    function changeSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    }

    // Change background every 5 seconds
    setInterval(changeSlide, 5000);

    function openTranslationModal(text, sourceLang) {
        currentSourceText = text;
        currentSourceLang = sourceLang;

        document.getElementById('sourceText').textContent = text;
        document.getElementById('translationModal').classList.add('active');

        // Réinitialiser le formulaire
        document.getElementById('targetLanguage').value = '';
        document.getElementById('translationResult').classList.add('hidden');
        document.getElementById('translationError').classList.add('hidden');
        document.getElementById('translationLoading').classList.add('hidden');

        // Pré-sélectionner une langue cible différente de la source
        if (sourceLang === 'fr') {
            document.getElementById('targetLanguage').value = 'en';
        } else {
            document.getElementById('targetLanguage').value = 'fr';
        }
    }

    function closeTranslationModal() {
        document.getElementById('translationModal').classList.remove('active');
    }

    // Fermer la modal en cliquant à l'extérieur
    window.onclick = function(event) {
        const modal = document.getElementById('translationModal');
        if (event.target === modal) {
            closeTranslationModal();
        }
    }

    // Fermer avec la touche ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTranslationModal();
        }
    });

    function translateText() {
        const targetLang = document.getElementById('targetLanguage').value;

        if (!targetLang) {
            showError('Veuillez sélectionner une langue cible.');
            return;
        }

        if (targetLang === currentSourceLang) {
            showError('La langue cible doit être différente de la langue source.');
            return;
        }

        // Masquer les résultats précédents
        document.getElementById('translationResult').classList.add('hidden');
        document.getElementById('translationError').classList.add('hidden');

        // Afficher le loader
        document.getElementById('translationLoading').classList.remove('hidden');

        // Appel à l'API MyMemory
        const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(currentSourceText)}&langpair=${currentSourceLang}|${targetLang}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('translationLoading').classList.add('hidden');

                if (data.responseData && data.responseData.translatedText) {
                    const translation = data.responseData.translatedText;
                    document.getElementById('translatedText').textContent = translation;
                    document.getElementById('translationResult').classList.remove('hidden');
                } else {
                    showError('Impossible de récupérer la traduction. Veuillez réessayer.');
                }
            })
            .catch(error => {
                document.getElementById('translationLoading').classList.add('hidden');
                showError(`Erreur de connexion: ${error.message}`);
            });
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('translationError').classList.remove('hidden');
    }

    // Animation smooth scroll
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