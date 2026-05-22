/**
 * BookNest — Đọc PDF bằng giọng nói (Web Speech API + server text extraction)
 */
(function () {
    'use strict';

    const configEl = document.getElementById('read-aloud-config');
    if (!configEl) return;

    const config = {
        textUrl: configEl.dataset.textUrl,
        pdfUrl: configEl.dataset.pdfUrl,
        isPreview: configEl.dataset.isPreview === '1',
        maxPages: parseInt(configEl.dataset.maxPages || '5', 10),
        bookTitle: configEl.dataset.bookTitle || 'Sách',
        csrf: configEl.dataset.csrf || '',
    };

    const btnPlay = document.getElementById('btn-read-aloud');
    const btnStop = document.getElementById('btn-read-aloud-stop');
    const labelPlay = document.getElementById('read-aloud-text');
    const voiceSettings = document.getElementById('voice-settings');
    const voiceSelect = document.getElementById('voice-select');
    const voiceRate = document.getElementById('voice-rate');
    const progressContainer = document.getElementById('reading-progress-container');
    const progressBar = document.getElementById('reading-progress-bar');
    const readingStatus = document.getElementById('reading-status');
    const readingPercent = document.getElementById('reading-percent');
    const readingMeta = document.getElementById('reading-meta');

    if (!btnPlay || !window.speechSynthesis) return;

    const synth = window.speechSynthesis;
    let voices = [];
    let chunks = [];
    let chunkIndex = 0;
    let isPlaying = false;
    let isPaused = false;
    let fullTextLength = 0;

    function loadVoices() {
        voices = synth.getVoices();
        if (!voiceSelect || voices.length === 0) return;

        const preferred = voices
            .filter((v) => v.lang.includes('vi') || v.lang.includes('en'))
            .sort((a, b) => {
                const score = (v) => {
                    let s = 0;
                    if (v.lang.includes('vi')) s += 10;
                    if (/google|natural|online|premium/i.test(v.name)) s += 5;
                    if (v.localService) s += 1;
                    return s;
                };
                return score(b) - score(a);
            });

        voiceSelect.innerHTML = preferred
            .map((v, i) => `<option value="${v.name}"${i === 0 ? ' selected' : ''}>${v.name} (${v.lang})</option>`)
            .join('');
    }

    loadVoices();
    if (synth.onvoiceschanged !== undefined) synth.onvoiceschanged = loadVoices;
    setTimeout(loadVoices, 400);
    setTimeout(loadVoices, 1200);

    function updateProgress(percent, status, meta) {
        if (progressBar) progressBar.style.width = Math.min(100, percent) + '%';
        if (readingPercent) readingPercent.textContent = Math.round(Math.min(100, percent)) + '%';
        if (status && readingStatus) readingStatus.textContent = status;
        if (meta !== undefined && readingMeta) readingMeta.textContent = meta;
    }

    function splitIntoChunks(text) {
        const cleaned = text.replace(/\s+/g, ' ').trim();
        if (!cleaned) return [];

        const sentences = cleaned.match(/[^.!?…]+[.!?…]?/gu) || [cleaned];
        const result = [];
        let buffer = '';

        sentences.forEach((sentence) => {
            const part = sentence.trim();
            if (!part) return;
            if ((buffer + ' ' + part).length > 280) {
                if (buffer) result.push(buffer.trim());
                buffer = part.length > 280 ? part.match(/.{1,280}(\s|$)/g).join(' ').trim() : part;
            } else {
                buffer = buffer ? buffer + ' ' + part : part;
            }
        });
        if (buffer) result.push(buffer.trim());

        return result.length ? result : [cleaned.slice(0, 500)];
    }

    function getSelectedVoice() {
        if (!voiceSelect) return null;
        return voices.find((v) => v.name === voiceSelect.value) || voices.find((v) => v.lang.includes('vi')) || null;
    }

    function speakChunk(text) {
        return new Promise((resolve, reject) => {
            const u = new SpeechSynthesisUtterance(text);
            const voice = getSelectedVoice();
            if (voice) {
                u.voice = voice;
                u.lang = voice.lang;
            } else {
                u.lang = 'vi-VN';
            }
            u.rate = parseFloat(voiceRate?.value || '1') || 1;
            u.pitch = 1;
            u.volume = 1;
            u.onend = () => resolve();
            u.onerror = (e) => reject(e);
            synth.speak(u);
        });
    }

    function resetPlayer() {
        synth.cancel();
        isPlaying = false;
        isPaused = false;
        chunkIndex = 0;
        chunks = [];
        if (btnStop) btnStop.classList.add('d-none');
        if (labelPlay) labelPlay.textContent = 'Nghe sách';
        if (voiceSettings) voiceSettings.classList.add('d-none');
    }

    async function fetchTextFromServer() {
        const res = await fetch(config.textUrl, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error('Không tải được nội dung.');
        return res.json();
    }

    async function fetchTextFromPdfJs() {
        if (typeof pdfjsLib === 'undefined') throw new Error('PDF.js chưa sẵn sàng.');

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdf = await pdfjsLib.getDocument(config.pdfUrl).promise;
        const pagesToScan = Math.min(pdf.numPages, config.maxPages);
        let fullText = '';

        for (let i = 1; i <= pagesToScan; i++) {
            updateProgress(15 + (i / pagesToScan) * 35, `Đang quét PDF trang ${i}/${pagesToScan}...`);
            const page = await pdf.getPage(i);
            const content = await page.getTextContent();
            fullText += content.items.map((item) => item.str).join(' ') + ' ';
        }

        return {
            text: fullText.trim(),
            source: 'pdf_js',
            pages_read: pagesToScan,
            total_pages: pdf.numPages,
        };
    }

    async function resolveReadableText() {
        updateProgress(5, 'Đang tải nội dung từ máy chủ...');
        try {
            const data = await fetchTextFromServer();
            if (data.text && data.text.length >= 30) {
                const meta = data.is_preview
                    ? `Xem trước · ${data.pages_read || data.max_pages} trang`
                    : `Đã mua · ${data.pages_read || '?'} / ${data.total_pages || '?'} trang`;
                return { text: data.text, meta, source: data.source };
            }
        } catch (e) {
            console.warn('Server extract failed', e);
        }

        updateProgress(20, 'Thử trích xuất trên trình duyệt...');
        const pdfData = await fetchTextFromPdfJs();
        if (pdfData.text && pdfData.text.length >= 30) {
            return {
                text: pdfData.text,
                meta: `PDF · ${pdfData.pages_read} trang`,
                source: pdfData.source,
            };
        }

        const desc = document.querySelector('.book-description');
        const fallback = desc ? desc.innerText.trim() : '';
        if (fallback.length < 20) {
            throw new Error('Không trích xuất được chữ từ PDF. Sách có thể là bản scan ảnh.');
        }
        return { text: fallback, meta: 'Mô tả sách', source: 'description' };
    }

    async function playAllChunks() {
        isPlaying = true;
        if (btnStop) btnStop.classList.remove('d-none');
        if (voiceSettings) voiceSettings.classList.remove('d-none');
        if (labelPlay) labelPlay.textContent = 'Tạm dừng';

        for (; chunkIndex < chunks.length; chunkIndex++) {
            if (!isPlaying) break;

            while (isPaused && isPlaying) {
                await new Promise((r) => setTimeout(r, 200));
            }
            if (!isPlaying) break;

            const pct = 50 + ((chunkIndex + 1) / chunks.length) * 50;
            updateProgress(pct, `Đang đọc đoạn ${chunkIndex + 1}/${chunks.length}...`);

            await speakChunk(chunks[chunkIndex]);
        }

        if (isPlaying) {
            updateProgress(100, 'Hoàn thành');
            setTimeout(() => {
                resetPlayer();
                progressContainer?.classList.add('d-none');
            }, 2000);
        }
    }

    btnPlay.addEventListener('click', async () => {
        if (isPlaying && !isPaused) {
            synth.pause();
            isPaused = true;
            if (labelPlay) labelPlay.textContent = 'Tiếp tục';
            return;
        }

        if (isPlaying && isPaused) {
            synth.resume();
            isPaused = false;
            if (labelPlay) labelPlay.textContent = 'Tạm dừng';
            return;
        }

        synth.cancel();
        progressContainer?.classList.remove('d-none');
        if (labelPlay) labelPlay.textContent = 'Đang chuẩn bị...';

        try {
            const { text, meta } = await resolveReadableText();
            chunks = splitIntoChunks(text);
            fullTextLength = text.length;
            chunkIndex = 0;

            if (!chunks.length) throw new Error('Không có nội dung để đọc.');

            updateProgress(45, `Sẵn sàng · ${chunks.length} đoạn`, meta);
            await playAllChunks();
        } catch (err) {
            resetPlayer();
            updateProgress(0, err.message || 'Lỗi đọc sách');
            if (labelPlay) labelPlay.textContent = 'Thử lại';
        }
    });

    if (btnStop) {
        btnStop.addEventListener('click', () => {
            resetPlayer();
            progressContainer?.classList.add('d-none');
            updateProgress(0, 'Đã dừng');
        });
    }

    const readModal = document.getElementById('readOnlineModal');
    if (readModal) {
        readModal.addEventListener('hidden.bs.modal', resetPlayer);
    }
})();
