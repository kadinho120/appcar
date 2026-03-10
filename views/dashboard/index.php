<?php
$title = 'Mecânico Virtual - Diagnóstico';
ob_start();
?>

<div class="p-6 pb-24 h-full overflow-y-auto hide-scrollbar">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Diagnósticos</h2>
        <button onclick="openNewDiagnosticModal()"
            class="flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-2xl shadow-lg active:scale-95 transition-all text-sm font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Novo</span>
        </button>
    </div>

    <!-- History Section -->
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest pl-1">Histórico Recente</h3>

        <?php if (empty($history)): ?>
            <div class="bg-white rounded-3xl p-8 text-center border border-gray-100 shadow-sm">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">Nenhum diagnóstico ainda</h3>
                <p class="text-sm text-gray-500">Inicie seu primeiro diagnóstico para resolver os problemas do seu carro.
                </p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-4">
                <?php foreach ($history as $item): ?>
                    <?php
                    $resultText = $item['result_json']['text'] ?? '';
                    // Extract first problem name from Markdown **Problem Name**
                    preg_match('/\*\*(.*?)\*\*/', $resultText, $matches);
                    $problemTitle = $matches[1] ?? 'Diagnóstico Geral';
                    
                    // Simple cleaning if it starts with numbers or common prefixes
                    $problemTitle = preg_replace('/^\d+\.\s*/', '', $problemTitle);
                    ?>
                    <a href="/chat?diagnostic_id=<?= $item['id'] ?>"
                        class="bg-white p-4 rounded-3xl shadow-sm border border-gray-50 hover:border-blue-100 transition-all active:scale-[0.98] block group">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-full uppercase">
                                <?= date('d M, Y', strtotime($item['created_at'])) ?>
                            </span>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full uppercase">
                                Ver Chat
                            </span>
                        </div>
                        <h4 class="font-bold text-gray-800 line-clamp-1 mb-1 group-hover:text-blue-600 transition-colors">
                            <?= htmlspecialchars($problemTitle) ?>
                        </h4>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3 italic">"<?= htmlspecialchars($item['symptoms']) ?>"
                        </p>
 
                        <div class="flex items-center space-x-2 text-[10px] text-gray-400 border-t border-gray-50 pt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            <span><?= htmlspecialchars($item['vehicle_info']['make'] ?? 'Veículo') ?>
                                <?= htmlspecialchars($item['vehicle_info']['model'] ?? 'N/A') ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Seleção de Veículo -->
<div id="vehicleSelectionModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeNewDiagnosticModal()">
    </div>
    <div
        class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-[scale_0.2s_ease-out]">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Novo Diagnóstico</h3>
            <p class="text-sm text-gray-500 mb-6">Selecione qual veículo da sua garagem você deseja analisar agora:</p>

            <?php if (empty($vehicles)): ?>
                <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100 mb-4">
                    <p class="text-sm text-orange-800 font-medium text-center">
                        Você precisa cadastrar um veículo na Garagem antes de iniciar um diagnóstico.
                    </p>
                </div>
                <a href="/garage"
                    class="block w-full py-3 bg-blue-600 text-white font-bold rounded-2xl text-center shadow-lg shadow-blue-200">
                    Ir para Garagem
                </a>
            <?php else: ?>
                <div class="space-y-3 max-h-[300px] overflow-y-auto hide-scrollbar pr-1">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <a href="/chat?vehicle_id=<?= $vehicle['id'] ?>"
                            class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50 transition-all group active:scale-95">
                            <div
                                class="bg-white p-2 rounded-xl text-blue-600 mr-4 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 002 12v4c0 .6.4 1 1 1h2m2 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800">
                                    <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h4>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($vehicle['year']) ?> •
                                    <?= htmlspecialchars($vehicle['license_plate'] ?? '--') ?></p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300 group-hover:text-blue-600"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <button onclick="closeNewDiagnosticModal()"
                class="w-full mt-4 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-2xl transition-colors">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
    function openNewDiagnosticModal() {
        document.getElementById('vehicleSelectionModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeNewDiagnosticModal() {
        document.getElementById('vehicleSelectionModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<style>
    @keyframes scale {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>