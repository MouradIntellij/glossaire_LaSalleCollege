<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Glossaire Informatique – Loi 14</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #f7f9fb;
        }
    </style>
</head>

<body class="font-sans text-gray-800">

    <!-- HEADER -->
    <header class="bg-blue-700 text-white py-10 shadow-md">
        <div class="max-w-5xl mx-auto px-6">
            <h1 class="text-4xl font-bold">Glossaire informatique pour étudiants anglophones (Loi 14)</h1>
            <p class="text-lg mt-3">Projet éducatif bilingue visant à soutenir l’intégration linguistique en milieu collégial.</p>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10">

        <!-- OBJECTIFS -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">🎯 Objectifs du projet</h2>
            <ul class="list-disc ml-6 space-y-2">
                <li>Fournir un glossaire bilingue clair des termes informatiques.</li>
                <li>Réduire les difficultés linguistiques des étudiants anglophones/allophones.</li>
                <li>Soutenir la réussite académique dans un cadre francophone.</li>
                <li>Offrir un outil numérique libre, gratuit et évolutif.</li>
            </ul>
        </section>

        <!-- TECHNOLOGIES -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">🛠️ Technologies utilisées</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-white shadow rounded">HTML & TailwindCSS</div>
                <div class="p-4 bg-white shadow rounded">PHP</div>
                <div class="p-4 bg-white shadow rounded">PostgreSQL / Superbase</div>
                <div class="p-4 bg-white shadow rounded">SQL</div>
                <div class="p-4 bg-white shadow rounded">GitHub</div>
                <div class="p-4 bg-white shadow rounded">Vercel (déploiement)</div>
            </div>
        </section>

        <!-- FONCTIONNALITES -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">🧩 Fonctionnalités</h2>
            <ul class="list-disc ml-6 space-y-2">
                <li>Recherche en temps réel de termes informatiques.</li>
                <li>Glossaire bilingue français ↔ anglais.</li>
                <li>Catégories thématiques (programmation, réseaux, etc.).</li>
                <li>Ajout et modification de définitions (selon permissions).</li>
                <li>Interface responsive et accessible.</li>
            </ul>
        </section>

        <!-- CONTEXTE SOCIOLINGUISTIQUE -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">📌 Contexte sociolinguistique</h2>

            <h3 class="text-xl font-semibold mt-6 mb-2">Enjeux d’intégration sociale</h3>
            <ul class="list-disc ml-6 space-y-2">
                <li>Le soutien familial est inégal mais essentiel.</li>
                <li>Discrimination verbale et découragement dans certains milieux.</li>
                <li>Sentiment de rejet de la culture d’origine menant à une résistance linguistique.</li>
                <li>Attitudes négatives envers la communauté francophone.</li>
                <li>Isolement social et formation de bulles culturelles.</li>
                <li>Les immigrants rencontrent plus de difficultés dans un milieu scolaire francophone.</li>
                <li>Campus anglophones = intégration au français plus difficile.</li>
                <li>Le « Montreal switch » dans les quartiers bilingues freine l’intégration.</li>
                <li>56% des étudiants internationaux n’ont aucun ami canadien.</li>
                <li>Le français québécois perçu comme familier et peu prestigieux.</li>
                <li>Le choix d’une langue est souvent utilitaire, non culturel.</li>
            </ul>

            <h3 class="text-xl font-semibold mt-6 mb-2">Enjeux institutionnels</h3>
            <ul class="list-disc ml-6 space-y-2">
                <li>Utilisation non optimale des ressources par les allophones.</li>
                <li>Lourdeur administrative pour les nouveaux arrivants.</li>
                <li>Peu de données sur les parcours allophones → faible considération dans les politiques.</li>
            </ul>
        </section>

        <!-- DEPLOIEMENT -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">🚀 Déploiement</h2>

            <ol class="list-decimal ml-6 space-y-2">
                <li>Cloner le dépôt :</li>
                <pre class="bg-gray-900 text-white p-3 rounded mt-2 mb-4"><code>git clone https://github.com/ton-utilisateur/ton-repo.git</code></pre>

                <li>Installer Tailwind (optionnel si build local) :</li>
                <pre class="bg-gray-900 text-white p-3 rounded mt-2 mb-4"><code>npm install
npm run build</code></pre>

                <li>Configurer la base PostgreSQL / Superbase.</li>

                <li>Déployer via Vercel :</li>
                <pre class="bg-gray-900 text-white p-3 rounded mt-2"><code>vercel deploy</code></pre>
            </ol>
        </section>

        <!-- CONTRIBUTION -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">🤝 Contribution</h2>
            <p>Les contributions sont encouragées :</p>
            <ul class="list-disc ml-6 space-y-2">
                <li>Ajout ou révision de termes du glossaire</li>
                <li>Amélioration du design ou des fonctionnalités</li>
                <li>Suggestions pédagogiques ou linguistiques</li>
            </ul>
        </section>

        <!-- LICENCE -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">📜 Licence</h2>
            <p>Projet distribué sous licence MIT.</p>
        </section>

    </main>

    <footer class="bg-gray-200 py-4 text-center text-sm">
        © 2025 – Projet Glossaire Informatique – Loi 14
    </footer>

</body>
</html>
