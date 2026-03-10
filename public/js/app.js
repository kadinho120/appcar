// Mecânico Virtual - Client Side Logic (Puter.js)

document.addEventListener('DOMContentLoaded', () => {
    const chatMessages = document.getElementById('chat-messages');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const imageInput = document.getElementById('image-input');
    const audioBtn = document.getElementById('audio-btn');
    const loading = document.getElementById('loading');
    const previewContainer = document.getElementById('preview-container');
    const audioStatus = document.getElementById('audio-status');

    let selectedImage = null;
    let isRecording = false;
    let mediaRecorder = null;
    let audioChunks = [];

    const systemPrompt = `Você é o "Mecânico Virtual", um especialista em diagnóstico veicular por I.A. 
    Seu objetivo é ajudar o usuário a diagnosticar problemas no veículo através de uma conversa.
    
    DIRETRIZES:
    1. Seja profissional, prestativo e direto.
    2. Faça perguntas sobre os sintomas (barulhos, luzes no painel, vibrações, etc.) para afunilar as possibilidades.
    3. Quando tiver informações suficientes, forneça um "DIAGNÓSTICO FINAL PRECISO".
    
    FORMATO OBRIGATÓRIO DO DIAGNÓSTICO FINAL:
    - Liste os 3 diagnósticos mais prováveis (do mais provável para o menos provável).
    - Para cada um, inclua:
        * Nome do Problema
        * Porcentagem de Probabilidade (%)
        * Estimativa de Custo (Peças + Mão de Obra) em R$
    - Adicione uma breve explicação técnica de cada um.

    Se receber uma imagem, analise-a cuidadosamente para identificar componentes danificados ou vazamentos.
    Se o áudio for transcrito, trate o texto como a descrição dos sintomas do usuário.`;

    let messages = [
        { role: 'system', content: systemPrompt }
    ];

    // Auto-scroll to bottom
    const scrollToBottom = () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };

    // Add message to UI
    const addMessage = (role, text, isHtml = false) => {
        const div = document.createElement('div');
        div.className = `flex ${role === 'user' ? 'justify-end' : 'items-start'}`;

        const innerDiv = document.createElement('div');
        innerDiv.className = role === 'user'
            ? 'bg-blue-100 text-blue-900 p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[85%]'
            : 'bg-blue-600 text-white p-3 rounded-2xl rounded-tl-none shadow-md max-w-[85%]';

        if (isHtml) {
            innerDiv.innerHTML = text.replace(/\n/g, '<br>');
        } else {
            innerDiv.textContent = text;
        }

        div.appendChild(innerDiv);
        chatMessages.appendChild(div);
        scrollToBottom();
    };

    // Handle Image Input
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            selectedImage = file;
            previewContainer.innerHTML = `
                <div class="relative inline-block">
                    <img src="${URL.createObjectURL(file)}" class="h-16 w-16 object-cover rounded-lg border-2 border-blue-500 shadow-sm">
                    <button id="remove-img" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full p-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            `;
            document.getElementById('remove-img').onclick = () => {
                selectedImage = null;
                previewContainer.innerHTML = '';
            };
        }
    });

    // Handle Audio Recording
    audioBtn.addEventListener('click', async () => {
        if (!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    loading.classList.remove('hidden');
                    audioStatus.classList.add('hidden');

                    try {
                        const transcript = await puter.ai.speech2txt(audioBlob);
                        userInput.value = transcript.text || transcript;
                    } catch (err) {
                        console.error("Speech2Txt Error:", err);
                        addMessage('assistant', "Desculpe, não consegui entender o áudio. Pode tentar escrever?");
                    } finally {
                        loading.classList.add('hidden');
                    }
                };

                mediaRecorder.start();
                isRecording = true;
                audioStatus.classList.remove('hidden');
                audioBtn.classList.replace('bg-gray-100', 'bg-red-100');
                audioBtn.querySelector('svg').classList.add('text-red-600');
            } catch (err) {
                console.error("Mic Access Error:", err);
                alert("Permissão de microfone negada.");
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            audioBtn.classList.replace('bg-red-100', 'bg-gray-100');
            audioBtn.querySelector('svg').classList.remove('text-red-600');
        }
    });

    // Send Message
    const sendMessage = async () => {
        const text = userInput.value.trim();
        if (!text && !selectedImage) return;

        addMessage('user', text || "Estou enviando uma imagem para análise...");
        userInput.value = '';
        userInput.rows = 1;
        loading.classList.remove('hidden');

        try {
            // Ensure user is signed in to Puter for FS/AI access
            if (!puter.auth.isSignedIn()) {
                await puter.auth.signIn();
            }

            let userContent = [];
            if (text) userContent.push({ type: 'text', text: text });

            if (selectedImage) {
                // Use a very simple, sanitized filename
                const extension = selectedImage.name.split('.').pop() || 'jpg';
                const tempName = `diag_img_${Date.now()}.${extension}`;

                // Write to the app's root (home directory for this app context)
                await puter.fs.write(tempName, selectedImage);

                // Documentation suggests '~/path/to/file' for Puter paths
                const uploadedPath = `~/${tempName}`;
                userContent.push({ type: 'file', puter_path: uploadedPath });

                // Clear preview
                selectedImage = null;
                previewContainer.innerHTML = '';
            }

            messages.push({ role: 'user', content: userContent });

            const response = await puter.ai.chat(messages, { model: 'gpt-4o-mini' });
            const assistantText = response.message ? response.message.content : response;

            messages.push({ role: 'assistant', content: assistantText });
            addMessage('assistant', assistantText, true);

            // If the response looks like a diagnostic, save it
            if (assistantText.includes('DIAGNÓSTICO FINAL') || assistantText.includes('Diagnóstico 1')) {
                fetch('/save-diagnostic', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        result: { text: assistantText },
                        symptoms: messages.filter(m => m.role === 'user').map(m => Array.isArray(m.content) ? m.content.find(c => c.type === 'text')?.text || '' : m.content).join('\n')
                    })
                });
            }

        } catch (err) {
            console.error("AI/FS Error:", err);
            addMessage('assistant', `Erro: ${err.message || 'Ocorreu um problema ao processar sua solicitação.'}`);
        } finally {
            loading.classList.add('hidden');
        }
    };

    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Auto-resize textarea
    userInput.addEventListener('input', () => {
        userInput.style.height = 'auto';
        userInput.style.height = userInput.scrollHeight + 'px';
    });
});
