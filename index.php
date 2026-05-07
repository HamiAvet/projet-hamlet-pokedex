<?php
    try {
        // Initialiser le DSN (Data Source Name) pour la connexion à la base de données
        $dns = 'mysql:host=gateway01.eu-central-1.prod.aws.tidbcloud.com;port=4000;dbname=hamlet_pokedex';

        // Options de connexion pour PDO
        $options = [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Désactive la vérification du certificat SSL
            PDO::MYSQL_ATTR_SSL_CA => true, // Active l'utilisation de SSL
        ];

        // L'utilisateur de la base de données
        $utilisateur = '2ze5GKmBxBG67a2.root';

        // Mot de passe de la base de données (vide par défaut)
        $motDePasse = 'ImECCfYlRahJ4JqB';
        
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
        echo "<h1>{$pokemon['pokemon_id']}</h1>";
        echo "<h2>{$pokemon['pokemon_nom']}</h2>";

    }
?>