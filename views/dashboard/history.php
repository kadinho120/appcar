<?php
$title = 'Histórico de Diagnósticos - Mecânico Virtual';
ob_start();
?>

<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Seu Histórico</h2>
        <p class="text-gray-500">Veja seus diagnósticos anteriores.</p>
    </div>

    <?php if (empty($history)): ?>
        <div class="text-center py-12">
            <div class="bg-gray-100 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-gray-500">Nenhum diagnóstico encontrado.</p>
            <a href="/index" class="mt-4 inline-block text-blue-600 font-medium hover:underline">Iniciar novo chat</a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($history as $item): ?>
                <?php $result = json_decode($item['result_json'], true); ?>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 px-2 py-1 rounded">
                            <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                        </span>
                    </div>

                    <?php if ($item['symptoms']): ?>
                        <p class="text-xs text-gray-500 italic mb-3 line-clamp-2">
                            "
                            <?= htmlspecialchars($item['symptoms']) ?>"
                        </p>
                    <?php endif; ?>

                    <div class="text-sm text-gray-800 whitespace-pre-line bg-gray-50 p-3 rounded-lg border-l-4 border-blue-600">
                        <?= htmlspecialchars($result['text'] ?? 'Diagnóstico sem detalhes.') ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>