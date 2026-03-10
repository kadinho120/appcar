<?php
$title = 'Configurações - Mecânico Virtual';
ob_start();
?>

<div class="p-6">
    <div class="flex items-center space-x-3 mb-8">
        <a href="/profile" class="p-2 bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Meus Dados</h2>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div
            class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center space-x-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div
            class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center space-x-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </span>
        </div>
    <?php endif; ?>

    <form action="/update-profile" method="POST" class="space-y-6">
        <div class="space-y-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-50">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 pl-1">Nome
                    Completo</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                    class="w-full p-4 bg-gray-50 border border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all text-gray-700 font-medium"
                    placeholder="Seu nome">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 pl-1">E-mail</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                    class="w-full p-4 bg-gray-50 border border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all text-gray-700 font-medium"
                    placeholder="seu@email.com">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 active:scale-[0.98] transition-all">
                Salvar Alterações
            </button>
        </div>
    </form>

    <div class="mt-8 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
        <p class="text-[10px] text-gray-400 text-center leading-relaxed">
            As alterações nos seus dados serão refletidas em todos os novos diagnósticos e na visualização da sua
            garagem.
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>