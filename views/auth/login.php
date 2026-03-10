<?php
$title = 'Entrar - Mecânico Virtual';
ob_start();
?>

<div class="p-6">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Bem-vindo de volta!</h2>
        <p class="text-gray-500">Faça login para continuar seus diagnósticos.</p>
    </div>

    <?php if (isset($_GET['registered'])): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
            Conta criada com sucesso! Por favor, faça login.
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST" class="space-y-4">
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
                placeholder="••••••••">
        </div>
        <button type="submit"
            class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
            Entrar
        </button>
    </form>

    <div class="mt-6 text-center text-sm">
        <span class="text-gray-500">Ainda não tem uma conta?</span>
        <a href="/register" class="text-blue-600 font-medium hover:underline ml-1">Cadastre-se</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>