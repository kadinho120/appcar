<?php
$title = 'Mecânico Virtual - Diagnóstico de ' . htmlspecialchars($selectedVehicle['make'] . ' ' . $selectedVehicle['model']);
ob_start();
?>

<!-- Pass vehicle context to JS -->
<script>
    const vehicleContext = <?= json_encode([
        'id' => $selectedVehicle['id'] ?? null,
        'make' => $selectedVehicle['make'] ?? 'N/A',
        'model' => $selectedVehicle['model'] ?? 'N/A',
        'year' => $selectedVehicle['year'] ?? 'N/A'
    ]) ?>;
    const historicalMessages = <?= json_encode($historicalMessages ?? []) ?>;
</script>

<div class="flex flex-col h-full bg-gray-50">
    <!-- Chat Header (Context) -->
    <div class="bg-white px-4 py-2 border-b border-gray-100 flex items-center space-x-3">
        <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 002 12v4c0 .6.4 1 1 1h2m2 0a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
        </div>
        <div>
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Diagnosticando</h3>
            <p class="text-sm text-gray-500 font-medium">
                <?= htmlspecialchars(($selectedVehicle['make'] ?? 'Veículo') . ' ' . ($selectedVehicle['model'] ?? '') . ' (' . ($selectedVehicle['year'] ?? '') . ')') ?>
            </p>
        </div>
    </div>

    <!-- Chat Container -->
    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
        <?php if (empty($historicalMessages)): ?>
            <!-- Welcome Message -->
            <div class="flex items-start">
                <div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tl-none shadow-md max-w-[85%]">
                    <p class="text-sm">Olá! Estou pronto para diagnosticar seu
                        <strong><?= htmlspecialchars($selectedVehicle['model'] ?? 'veículo') ?></strong>. 🚗</p>
                    <p class="text-sm mt-1">O que está acontecendo com ele? Se puder, me envie uma foto ou descreva os
                        sintomas/barulhos por áudio ou texto.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="hidden p-4 flex items-center space-x-2">
        <div class="animate-bounce bg-blue-400 h-2 w-2 rounded-full"></div>
        <div class="animate-bounce bg-blue-400 h-2 w-2 rounded-full delay-100"></div>
        <div class="animate-bounce bg-blue-400 h-2 w-2 rounded-full delay-200"></div>
        <span class="text-xs text-gray-400 font-medium">Analisando...</span>
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-white border-t border-gray-100 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex items-center space-x-2 mb-2" id="preview-container">
            <!-- Image Preview will appear here -->
        </div>

        <div class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-3xl border border-gray-100">
            <!-- Media Buttons -->
            <div class="flex items-center space-x-1 pl-1">
                <label for="image-input"
                    class="p-2 text-gray-500 hover:bg-white hover:text-blue-600 rounded-full cursor-pointer transition-all hover:shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <input type="file" id="image-input" accept="image/*" class="hidden">
                </label>
                <button id="audio-btn"
                    class="p-2 text-gray-500 hover:bg-white hover:text-red-600 rounded-full transition-all hover:shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </button>
            </div>

            <!-- Text Input -->
            <textarea id="user-input" rows="1"
                class="flex-1 bg-transparent py-2 px-1 text-sm outline-none resize-none h-[40px] hide-scrollbar"
                placeholder="Descreva o problema..."></textarea>

            <!-- Send Button -->
            <button id="send-btn"
                class="p-2.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all shadow-md active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
        <p id="audio-status" class="hidden text-[10px] text-red-500 font-bold mt-1 text-center animate-pulse">Ouvindo
            você...</p>
    </div>
</div>

<script src="/js/app.js"></script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>