<?php
$title = 'Meu Perfil';
ob_start();
?>

<div class="p-6">
    <div class="text-center mb-8">
        <div
            class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">
            <span class="text-3xl font-bold text-blue-600">
                <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
            </span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">
            <?= htmlspecialchars($_SESSION['user_name']) ?>
        </h2>
        <p class="text-sm text-gray-500">
            <?= htmlspecialchars($_SESSION['user_email'] ?? 'usuario@email.com') ?>
        </p>
    </div>

    <div class="space-y-4">
        <!-- Menu Items -->
        <a href="/history"
            class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Meus Diagnósticos</span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd" />
            </svg>
        </a>

        <div
            class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 00-1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="font-medium text-gray-700">Configurações</span>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd" />
            </svg>
        </div>

        <a href="/logout"
            class="flex items-center justify-between p-4 bg-red-50 rounded-2xl shadow-sm border border-red-100 hover:bg-red-100 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white text-red-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <span class="font-medium text-red-700">Sair da Conta</span>
            </div>
        </a>
    </div>

    <div class="mt-8 text-center">
        <p class="text-xs text-gray-400">Versão 1.2.0 - Mecânico Virtual</p>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>