<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préférences - IUTables'O</title>
    <link rel="stylesheet" href="/static/css/preferences.css">
</head>
<body>
    <div class="preferences-container">
        <h2>Choisissez vos types de restaurants préférés</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form action="/index.php?action=preferences" method="POST">
            <div class="checkbox-group">
                <?php foreach ($restaurantTypes as $type): ?>
                    <?php 
                        $checked = in_array($type['id_type'], $selectedPreferences) ? 'checked' : ''; 
                        $selectedClass = in_array($type['id_type'], $selectedPreferences) ? 'selected' : '';
                    ?>
                    <input type="checkbox" id="type_<?php echo $type['id_type']; ?>" name="preferences[]" value="<?php echo $type['id_type']; ?>" <?php echo $checked; ?>>
                    <label for="type_<?php echo $type['id_type']; ?>" class="<?php echo $selectedClass; ?>">
                        <?php echo htmlspecialchars($type['nom_type']); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit">Valider mes préférences</button>
        </form>
    </div>
</body>
</html>
