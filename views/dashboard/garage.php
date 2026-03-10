<?php
$title = 'Minha Garagem';
ob_start();
?>

<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Minha Garagem</h2>
        <button class="p-2 bg-blue-600 text-white rounded-full shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-3xl p-8 text-center border-2 border-dashed border-gray-200">
        <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Sua garagem está vazia</h3>
        <p class="text-sm text-gray-500 mb-6">Adicione seu primeiro veículo para ter diagnósticos ainda mais precisos e
            personalizados.</p>
        <button
            class="px-6 py-2 bg-blue-600 text-white rounded-2xl font-medium shadow-md hover:bg-blue-700 transition-colors">
            Adicionar Veículo
        </button>
    </div>

    <!-- Tips Section -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Dicas do Mecânico</h3>
        <div class="space-y-4">
            <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100">
                <p class="text-sm text-orange-800 font-medium">Troca de óleo próxima?</p>
                <p class="text-xs text-orange-600 mt-1">Lembre-se de verificar o nível do óleo a cada 1.000km para
                    evitar danos graves ao motor.</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                <p class="text-sm text-blue-800 font-medium">Calibragem dos pneus</p>
                <p class="text-xs text-blue-600 mt-1">Pneus calibrados reduzem o consumo de combustível em até 3%.</p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>