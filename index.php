<?php
    // Chargement des variables d'environnement à partir du fichier .env
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    try {
        // Initialiser le DSN (Data Source Name) pour la connexion à la base de données
        $dns = "mysql:host={$_ENV['hote']};port={$_ENV['port']};dbname={$_ENV['base_de_donnees']}";
        // Options de connexion pour PDO
        $options = [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Désactive la vérification du certificat SSL
            PDO::MYSQL_ATTR_SSL_CA => true, // Active l'utilisation de SSL
        ];

        // L'utilisateur de la base de données
        $utilisateur = $_ENV['utilisateur'];

        // Mot de passe de la base de données (vide par défaut)
        $motDePasse = $_ENV['mot_de_passe'];
        
        // Création de la connexion PDO
        $connection = new PDO($dns, $utilisateur, $motDePasse, $options);

    } catch (Exception $ex) {
        // Affichage de l'erreur de connexion
        echo "Erreur de connexion à la base de données : {$ex->getMessage()}";
    }

    // Exécution de la requête SQL pour récupérer les données de la table "pokemon"
    $select = $connection->query("SELECT * FROM pokemon");
    $pokemons = $select->fetchAll(PDO::FETCH_ASSOC);

    // Affiche les données en html
    foreach ($pokemons as $pokemon) {
        echo "
        <div>
            <img src='{$pokemon['pokemon_img']}' alt='Image de {$pokemon['pokemon_nom']}'>
            <h1>N° {$pokemon['pokemon_id']} : {$pokemon['pokemon_nom']}</h1>
            <h3>Poids : {$pokemon['pokemon_poids']}, Taille : {$pokemon['pokemon_taille']}</h3>
            <p>Description : {$pokemon['pokemon_description']}</p>

        </div>";
    }
?>