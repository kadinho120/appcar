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
            height: 100dvh;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-100 overflow-hidden">
    <div class="mobile-container">
        <!-- Main Content -->
        <main class="flex-1 min-h-0 relative">
            <?= $content ?? '' ?>
        </main>

        <!-- Bottom Navigation Bar -->
        <nav
            class="bg-white border-t border-gray-100 flex items-center justify-around py-2 px-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-50">
            <?php
            $current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            ?>
            <a href="/garage"
                class="flex flex-col items-center space-y-1 transition-all <?= $current_uri === '/garage' ? 'text-blue-600' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-[10px] font-medium">Garagem</span>
            </a>

            <a href="/index"
                class="flex flex-col items-center space-y-1 transition-all <?= ($current_uri === '/index' || $current_uri === '/') ? 'text-blue-600' : 'text-gray-400' ?>">
                <div
                    class="p-2 -mt-6 bg-blue-600 rounded-full text-white shadow-lg shadow-blue-200 border-4 border-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <span class="text-[10px] font-medium mt-0.5">Diagnosticar</span>
            </a>

            <a href="/profile"
                class="flex flex-col items-center space-y-1 transition-all <?= $current_uri === '/profile' ? 'text-blue-600' : 'text-gray-400' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-medium">Perfil</span>
            </a>
        </nav>
    </div>
</body>

</html>