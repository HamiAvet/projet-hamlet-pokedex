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

    // Vérifie si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupère les données du formulaire
        $nomNouveauPokemon = $_POST['pokemon_nom'];
        $imgNouveauPokemon = $_POST['pokemon_img'];
        $tailleNouveauPokemon = $_POST['pokemon_taille'];
        $poidsNouveauPokemon = $_POST['pokemon_poids'];
        $descriptionNouveauPokemon = $_POST['pokemon_description'];

        // Appelle la fonction pour ajouter le nouveau Pokémon à la base de données
        ajouterPokemon($connection, $nomNouveauPokemon, $imgNouveauPokemon, $tailleNouveauPokemon, $poidsNouveauPokemon, $descriptionNouveauPokemon);
    }
    
    // Fonction pour ajouter un nouveau Pokémon à la base de données
    function ajouterPokemon($connection, $nom, $img, $taille, $poids, $description) {
        // Prépare la requête d'insertion
        $insert = $connection->prepare("INSERT INTO pokemon (pokemon_nom, pokemon_img, pokemon_taille, pokemon_poids, pokemon_description) VALUES (:nom, :img, :taille, :poids, :description)");
        
        // Lie les valeurs aux paramètres de la requête
        $insert->bindValue(':nom', $nom);
        $insert->bindValue(':img', $img);
        $insert->bindValue(':taille', $taille);
        $insert->bindValue(':poids', $poids);
        $insert->bindValue(':description', $description);

        // Exécute la requête d'insertion
        $insert->execute();

        echo "<p>Le Pokémon a été ajouté avec succès !</p>";
    }

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <h1>Détails du Pokémon</h1>
        <form method="POST">
            <div>
                <label for="pokemon_nom">Nom du Pokémon :</label>
                <input type="text" id="pokemon_nom" name="pokemon_nom" placeholder="Pikachu">
            </div>
            <div>
                <label for="pokemon_img">Image du Pokémon :</label>
                <input type="text" id="pokemon_img" name="pokemon_img" placeholder="https://assets.pokemon.com/assets/cms2/img/pokedex/full/exemple.png">
            </div>
            <div>
                <label for="pokemon_taille">Taille du Pokémon :</label>
                <input type="text" id="pokemon_taille" name="pokemon_taille" placeholder="1.0 m">
            </div>
            <div>
                <label for="pokemon_poids">Poids du Pokémon :</label>
                <input type="text" id="pokemon_poids" name="pokemon_poids" placeholder="10.0 kg">
            </div>
            <div>
                <label for="pokemon_description">Description du Pokémon :</label>
                <textarea id="pokemon_description" name="pokemon_description" placeholder="Un Pokémon électrique célèbre qui..."></textarea>
            </div>
            <div>
                <button type="submit">Ajouter</button>
                <a href="index.php">Retour à la liste</a>
            </div>
            <?php?>
        </form>
    </main>
</body>
</html>