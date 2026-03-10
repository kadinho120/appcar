<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>
        <?= $title ?? 'Mecânico Virtual' ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.puter.com/v2/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* Tailwind gray-100 */
        }

        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="mobile-container">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4 flex items-center justify-between sticky top-0 z-50 shadow-md">
            <h1 class="text-xl font-bold"><a href="/index">Mecânico Virtual</a></h1>
            <div class="flex items-center space-x-3">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/history" class="text-sm opacity-80 hover:opacity-100">Histórico</a>
                    <a href="/logout" class="text-sm opacity-80 hover:opacity-100">Sair</a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <?= $content ?? '' ?>
        </main>

        <!-- Footer (optional for app style) -->
        <footer class="p-4 text-center text-xs text-gray-400 border-t">
            &copy;
            <?= date('Y') ?> Mecânico Virtual | Powered by <a href="https://developer.puter.com" target="_blank"
                class="underline">Puter</a>
        </footer>
    </div>
</body>

</html>