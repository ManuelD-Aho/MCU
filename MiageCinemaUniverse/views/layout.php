<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'MCU - Quartier Général'); ?></title>
    <link href="/assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-base-200">
    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <main class="container mx-auto p-4 md:p-8">
        <?php echo $pageContent ?? ''; ?>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>
    <script src="/assets/js/app.js"></script>
</body>
</html>