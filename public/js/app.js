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
    Seu objetivo é ajudar o usuário a diagnosticar problemas no veículo através de uma conversa clara e organizada.
    
    DIRETRIZES DE FORMATAÇÃO:
    1. Use **negrito** para destacar termos importantes, nomes de componentes e valores.
    2. Use listas (•) para enumerar sintomas ou passos.
    3. Use ESPAÇAMENTO (pule linhas) entre os parágrafos para facilitar a leitura.
    4. Seja profissional, prestativo e direto.
    5. No "DIAGNÓSTICO FINAL PRECISO", use um formato de lista clara.
    
    FORMATO OBRIGATÓRIO DO DIAGNÓSTICO FINAL:
    ### 🏁 DIAGNÓSTICO FINAL PRECISO
    
    1. **[Nome do Problema]**
    • **Probabilidade:** [X]%
    • **Estimativa de Custo:** R$ [Valor]
    • **Explicação Técnica:** [Breve explicação]
    
    (Repita para os 3 itens)
    
    Se receber uma imagem, analise-a cuidadosamente para identificar componentes danificados ou vazamentos.`;

    let messages = [
        { role: 'system', content: systemPrompt }
    ];

    // If vehicle context is provided, inject it as the first context message
    if (typeof vehicleContext !== 'undefined' && vehicleContext !== null && vehicleContext.id) {
        messages.push({
            role: 'user',
            content: `INFORMAÇÃO DO VEÍCULO: Estou diagnosticando um ${vehicleContext.make} ${vehicleContext.model} ano ${vehicleContext.year}. Considere estas especificações técnicas em seus diagnósticos.`
        });
    }

    // Load historical messages if available
    if (typeof historicalMessages !== 'undefined' && historicalMessages.length > 0) {
        historicalMessages.forEach(msg => {
            const role = msg.role;
            const content = msg.content;
            messages.push({ role, content });
            addMessage(role, content, role === 'assistant');
        });
    }

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
            const processedText = text
                .replace(/\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            innerDiv.innerHTML = processedText;
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

    // Handle Audio Recording with Web Speech API (Native)
    let recognition = null;
    let silenceTimer = null;
    const silenceDelay = 3000; // 3 seconds as requested

    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.lang = 'pt-BR';
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onstart = () => {
            isRecording = true;
            audioStatus.classList.remove('hidden');
            audioBtn.classList.add('bg-red-50', 'text-red-600', 'shadow-inner');
            audioBtn.classList.remove('text-gray-500');
            userInput.value = '';
        };

        recognition.onresult = (event) => {
            let finalTranscript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                }
            }

            if (finalTranscript) {
                userInput.value = finalTranscript;
            }

            // Reset silence timer on every result (speech detected)
            clearTimeout(silenceTimer);
            silenceTimer = setTimeout(() => {
                recognition.stop();
            }, silenceDelay);
        };

        recognition.onerror = (event) => {
            console.error("Speech Recognition Error:", event.error);
            if (event.error === 'not-allowed') {
                alert("Permissão de microfone negada.");
            }
            stopRecognition();
        };

        recognition.onend = () => {
            stopRecognition();
            // Auto-send if there is text
            if (userInput.value.trim().length > 0) {
                sendMessage();
            }
        };
    }

    const stopRecognition = () => {
        isRecording = false;
        audioStatus.classList.add('hidden');
        audioBtn.classList.remove('bg-red-50', 'text-red-600', 'shadow-inner');
        audioBtn.classList.add('text-gray-500');
        clearTimeout(silenceTimer);
    };

    audioBtn.addEventListener('click', () => {
        if (!recognition) {
            alert("Seu navegador não suporta reconhecimento de voz.");
            return;
        }

        if (!isRecording) {
            recognition.start();
        } else {
            recognition.stop();
        }
    });

    // Send Message
    const sendMessage = async () => {
        const text = userInput.value.trim();

        // Mandatory text with image
        if (selectedImage && !text) {
            alert("Por favor, descreva o problema ou o que você gostaria que eu analisasse na foto.");
            return;
        }

        if (!text && !selectedImage) return;

        addMessage('user', text);
        userInput.value = '';
        userInput.rows = 1;
        loading.classList.remove('hidden');

        try {
            // Ensure user is signed in to Puter for FS/AI access
            if (!puter.auth.isSignedIn()) {
                await puter.auth.signIn();
            }

            let fullText = text;

            if (selectedImage) {
                try {
                    addMessage('assistant', "🔍 Analisando a imagem enviada...");
                    // Use img2txt for stable image description
                    const description = await puter.ai.img2txt(selectedImage);
                    const descriptionText = typeof description === 'string' ? description : (description.text || JSON.stringify(description));

                    fullText = `[O usuário enviou uma imagem. Descrição da imagem pela I.A. de visão: ${descriptionText}]\n\n${text}`;

                    // Clear image after getting description
                    selectedImage = null;
                    previewContainer.innerHTML = '';
                } catch (imgErr) {
                    console.error("Img2Txt Error:", imgErr);
                    addMessage('assistant', "⚠️ Não consegui analisar a imagem detalhadamente, mas vou tentar ajudar com base no seu texto.");
                }
            }

            if (!fullText.trim()) return;

            messages.push({ role: 'user', content: fullText });

            // Standard gpt-4o-mini is ultra-stable for text
            const response = await puter.ai.chat(messages, { model: 'gpt-4o-mini' });
            const assistantText = response.message ? response.message.content : response;

            messages.push({ role: 'assistant', content: assistantText });
            addMessage('assistant', assistantText, true);

            // Auto-scroll
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // If the response looks like a diagnostic, save it
            if (assistantText.includes('DIAGNÓSTICO FINAL') || assistantText.includes('Diagnóstico 1')) {
                const saveData = {
                    result: { text: assistantText },
                    symptoms: messages.filter(m => m.role === 'user').map(m => Array.isArray(m.content) ? m.content.find(c => c.type === 'text')?.text || '' : m.content).join('\n')
                };

                // Add vehicle context to save data if available
                if (typeof vehicleContext !== 'undefined' && vehicleContext !== null) {
                    saveData.vehicle_info = vehicleContext;
                }

                fetch('/save-diagnostic', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(saveData)
                });
            }

        } catch (err) {
            console.error("AI/FS Error Detailed:", err);
            const errorStr = typeof err === 'object' ? JSON.stringify(err, null, 2) : err;
            addMessage('assistant', `❌ Detalhes do Erro na I.A.:\n${errorStr}`);
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
});
