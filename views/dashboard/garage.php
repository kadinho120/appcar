<?php
$title = 'Minha Garagem';
ob_start();
?>

<div class="p-6 pb-24 h-full overflow-y-auto hide-scrollbar">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Minha Garagem</h2>
        <button onclick="openModal()"
            class="p-2.5 bg-blue-600 text-white rounded-full shadow-lg active:scale-95 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <?php if (empty($vehicles)): ?>
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
            <button onclick="openModal()"
                class="px-6 py-2 bg-blue-600 text-white rounded-2xl font-medium shadow-md hover:bg-blue-700 transition-colors">
                Adicionar Veículo
            </button>
        </div>
    <?php else: ?>
        <!-- Vehicle List -->
        <div class="grid grid-cols-1 gap-4">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 p-3 rounded-2xl text-blue-600 group-hover:bg-blue-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 11l.24-2.16A2 2 0 016.22 7h11.56a2 2 0 011.98 1.84L20 11m-16 0h16m-16 0v5a2 2 0 002 2h12a2 2 0 002-2v-5M7 18v1m10-1v1" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">
                                <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h3>
                            <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                                <span
                                    class="bg-gray-100 px-2 py-0.5 rounded-full"><?= htmlspecialchars($vehicle['year']) ?></span>
                                <?php if ($vehicle['license_plate']): ?>
                                    <span
                                        class="bg-gray-100 px-2 py-0.5 rounded-full font-mono"><?= htmlspecialchars($vehicle['license_plate']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <a href="/delete-vehicle?id=<?= $vehicle['id'] ?>"
                        onclick="return confirm('Tem certeza que deseja remover este veículo?')"
                        class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Tips Section -->
    <div class="mt-8 mb-4">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Dicas do Mecânico</h3>
        <div class="space-y-4">
            <div class="bg-blue-50 p-4 rounded-3xl border border-blue-100">
                <p class="text-sm text-blue-800 font-bold">Troca de óleo próxima?</p>
                <p class="text-xs text-blue-600 mt-1">Lembre-se de verificar o nível do óleo a cada 1.000km para evitar
                    danos graves ao motor.</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-3xl border border-orange-100">
                <p class="text-sm text-orange-800 font-bold">Calibragem dos pneus</p>
                <p class="text-xs text-orange-600 mt-1">Pneus calibrados reduzem o consumo de combustível em até 3%.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar Veículo -->
<div id="addVehicleModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div
        class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden animate-[scale_0.2s_ease-out]">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Adicionar Veículo</h3>
            <form id="addVehicleForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Marca</label>
                    <input type="text" name="make" required placeholder="Ex: Toyota"
                        class="w-full p-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Modelo</label>
                    <input type="text" name="model" required placeholder="Ex: Corolla"
                        class="w-full p-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ano</label>
                        <input type="number" name="year" required placeholder="2024"
                            class="w-full p-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Placa
                            (Opcional)</label>
                        <input type="text" name="license_plate" placeholder="ABC-1234"
                            class="w-full p-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                </div>
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 py-3 text-gray-600 font-bold hover:bg-gray-100 rounded-2xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-[2] py-3 px-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all whitespace-nowrap">
                        Salvar Veículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('addVehicleModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('addVehicleModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('addVehicleForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch('/add-vehicle', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                window.location.reload();
            } else {
                alert(result.error || 'Erro ao adicionar veículo');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Erro na conexão com o servidor.');
        }
    });
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