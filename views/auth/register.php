<?php
$title = 'Cadastrar - Mecânico Virtual';
ob_start();
?>

<div class="p-6">
    <div class="mb-8 text-center text-gray-800">
        <h2 class="text-2xl font-bold">Crie sua conta</h2>
        <p class="text-gray-500">Seja bem-vindo ao futuro do diagnóstico automotivo.</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
            <input type="text" name="name" required
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                placeholder="João Silva">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                placeholder="seu@email.com">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
            <input type="password" name="password" required
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                placeholder="Mínimo 6 caracteres">
        </div>
        <button type="submit"
            class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
            Cadastrar
        </button>
    </form>

    <div class="mt-6 text-center text-sm">
        <span class="text-gray-500">Já tem uma conta?</span>
        <a href="/login" class="text-blue-600 font-medium hover:underline ml-1">Entrar</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>