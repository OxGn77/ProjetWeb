<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php foreach($tickets as $ticket){ ?>
    <div class="ticket">
        <div class="id"><?= $ticket->id ?></div>
        <div class="titre"><?= $ticket->titre ?></div>
        <div class="categorie"><?= $ticket->categorie ?></div>
        <div class="priorite"><?= $ticket->priorite ?></div>
        <div class="statut"><?= $ticket->statut ?></div>
    </div>
    <?php } ?>

</body>
</html>