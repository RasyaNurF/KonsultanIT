// Panel admin Nusakode: sidebar responsif, dropdown profil, toast, dialog konfirmasi,
// dan helper upload media (resize + konversi ke WebP di sisi klien).

const sidebar = document.querySelector('[data-admin-sidebar]');
const backdrop = document.querySelector('[data-admin-backdrop]');

const setSidebar = (open) => {
    if (!sidebar) {
        return;
    }

    sidebar.classList.toggle('-translate-x-full', !open);
    backdrop?.classList.toggle('hidden', !open);
    document.body.classList.toggle('overflow-hidden', open);
};

if (sidebar) {
    document.querySelectorAll('[data-admin-sidebar-open]').forEach((button) => {
        button.addEventListener('click', () => setSidebar(true));
    });

    document.querySelectorAll('[data-admin-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => setSidebar(false));
    });

    backdrop?.addEventListener('click', () => setSidebar(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebar(false);
            closeAllDropdowns();
        }
    });

    sidebar.querySelectorAll('nav a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                setSidebar(false);
            }
        });
    });
}

// --- Dropdown profil di topbar ---
function closeAllDropdowns(except = null) {
    document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
        if (dropdown === except) {
            return;
        }

        dropdown.querySelector('[data-dropdown-menu]')?.classList.add('hidden');
        dropdown.querySelector('[data-dropdown-toggle]')?.setAttribute('aria-expanded', 'false');
    });
}

document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
    const toggle = dropdown.querySelector('[data-dropdown-toggle]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');

    if (!toggle || !menu) {
        return;
    }

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();

        const willOpen = menu.classList.contains('hidden');

        closeAllDropdowns();
        menu.classList.toggle('hidden', !willOpen);
        toggle.setAttribute('aria-expanded', String(willOpen));
    });
});

document.addEventListener('click', () => closeAllDropdowns());

// --- Toast notifikasi ---
const toastContainer = document.querySelector('[data-toast-container]');

const toastIcons = {
    success: 'M5 13l4 4L19 7',
    error: 'M12 4 2.5 20h19L12 4Zm0 6v4m0 3h.01',
    info: 'M12 11v5m0-8h.01',
};

const showToast = (type, message) => {
    if (!toastContainer || !message) {
        return;
    }

    const isError = type === 'error';
    const toast = document.createElement('div');
    toast.className =
        'pointer-events-auto flex items-start gap-3 rounded-lg border bg-white p-4 shadow-lg shadow-neutral-900/5 transition duration-200 ' +
        (isError ? 'border-red-200' : 'border-neutral-200');
    toast.setAttribute('role', 'status');

    const icon = document.createElement('span');
    icon.className =
        'mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center ' + (isError ? 'text-red-600' : 'text-emerald-600');
    icon.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="' +
        (toastIcons[isError ? 'error' : 'success'] ?? toastIcons.info) +
        '"/></svg>';

    const text = document.createElement('p');
    text.className = 'flex-1 text-[13px] leading-relaxed text-neutral-700';
    text.textContent = message;

    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'text-neutral-400 transition hover:text-neutral-700';
    close.setAttribute('aria-label', 'Tutup notifikasi');
    close.innerHTML =
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="m6 6 12 12M18 6 6 18"/></svg>';
    close.addEventListener('click', () => toast.remove());

    toast.append(icon, text, close);
    toastContainer.append(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 200);
    }, isError ? 6000 : 4200);
};

document.querySelectorAll('[data-flash]').forEach((node) => {
    showToast(node.getAttribute('data-flash'), node.getAttribute('data-message'));
});

// --- Dialog konfirmasi untuk aksi destruktif (data-confirm) ---
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();

        if (window.confirm(form.dataset.confirm)) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
});

// --- Upload media: resize & kompres di browser ---
const MAX_EDGE = 1920;
const QUALITY = 0.78;

const fileToResizedBlob = async (file) => {
    if (!file.type.startsWith('image/') || file.type === 'image/svg+xml') {
        return file;
    }

    const bitmap = await createImageBitmap(file);
    const scale = Math.min(1, MAX_EDGE / Math.max(bitmap.width, bitmap.height));
    const width = Math.round(bitmap.width * scale);
    const height = Math.round(bitmap.height * scale);

    const canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(bitmap, 0, 0, width, height);
    bitmap.close?.();

    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/webp', QUALITY));

    if (!blob) {
        return file;
    }

    const base = file.name.replace(/\.[^.]+$/, '');

    return new File([blob], `${base}.webp`, { type: 'image/webp' });
};

document.querySelectorAll('[data-resize-upload]').forEach((form) => {
    const input = form.querySelector('input[type="file"]');

    if (!input) {
        return;
    }

    input.addEventListener('change', () => {
        const note = form.querySelector('[data-upload-note]');

        if (input.files?.[0] && note) {
            note.textContent = input.files[0].name;
        }
    });

    form.addEventListener('submit', async (event) => {
        const file = input.files?.[0];

        if (!file || file.size < 300 * 1024) {
            return;
        }

        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton?.setAttribute('disabled', 'true');

        try {
            const resized = await fileToResizedBlob(file);
            const data = new FormData();
            data.append('file', resized, resized.name);
            data.append('alt_text', form.querySelector('[name="alt_text"]')?.value ?? '');
            data.append('title', form.querySelector('[name="title"]')?.value ?? '');

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                body: data,
            });

            if (!response.ok) {
                throw new Error('Upload gagal. Coba lagi.');
            }

            window.location.reload();
        } catch (error) {
            showToast('error', error.message);
            submitButton?.removeAttribute('disabled');
        }
    });
});

// --- Salin slug otomatis dari judul ---
document.querySelectorAll('[data-slug-source]').forEach((source) => {
    const target = document.getElementById(source.getAttribute('data-slug-target'));

    if (!target) {
        return;
    }

    let manual = target.value.length > 0;

    target.addEventListener('input', () => {
        manual = true;
    });

    source.addEventListener('input', () => {
        if (manual) {
            return;
        }

        target.value = source.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    });
});

// --- Preview berkas gambar yang dipilih ---
document.querySelectorAll('[data-image-input]').forEach((input) => {
    input.addEventListener('change', () => {
        const preview = document.getElementById(input.getAttribute('data-image-preview'));
        const file = input.files?.[0];

        if (preview && file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
});

// --- Pesan live di panel admin (tanpa perlu refresh) ---
const unreadBadges = document.querySelectorAll('[data-admin-badge="messages"]');

const setUnreadBadges = (count) => {
    unreadBadges.forEach((badge) => {
        badge.textContent = count > 9 ? '9+' : String(count);
        badge.classList.toggle('hidden', count <= 0);
    });
};

const messageLiveList = document.querySelector('[data-messages-live]');

if (messageLiveList) {
    const liveUrl = messageLiveList.getAttribute('data-live-url');

    setInterval(async () => {
        try {
            const url = new URL(liveUrl, window.location.origin);
            url.search = window.location.search;

            const response = await fetch(url, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (typeof data.html === 'string') {
                messageLiveList.innerHTML = data.html;
            }

            if (typeof data.unread === 'number') {
                setUnreadBadges(data.unread);
            }
        } catch (error) {
            // Diamkan; percobaan berikutnya akan mencoba lagi.
        }
    }, 8000);
}

const adminThread = document.querySelector('[data-admin-thread]');

if (adminThread) {
    const pollUrl = adminThread.getAttribute('data-poll-url');
    const statusSelect = document.querySelector('[data-admin-status]');
    let lastId = Number(adminThread.getAttribute('data-last-id')) || 0;

    const isNearBottom = () => adminThread.scrollHeight - adminThread.scrollTop - adminThread.clientHeight < 80;

    const appendMessage = (message) => {
        if (adminThread.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        const isInbound = message.sender !== 'admin';

        const wrapper = document.createElement('div');
        wrapper.setAttribute('data-message-id', message.id);
        wrapper.className = `flex flex-col ${isInbound ? 'items-start' : 'items-end'}`;

        const bubble = document.createElement('div');
        bubble.className = `max-w-[85%] rounded-lg px-4 py-2.5 text-sm leading-relaxed ${isInbound ? 'bg-neutral-100 text-neutral-800' : 'bg-navy-800 text-white'}`;
        bubble.textContent = message.body;

        const meta = document.createElement('span');
        meta.className = 'mt-1 text-[11px] text-neutral-400';
        meta.textContent = `${message.name || (isInbound ? 'Pengunjung' : 'Admin')} · ${message.time}`;

        wrapper.appendChild(bubble);
        wrapper.appendChild(meta);
        adminThread.appendChild(wrapper);
    };

    setInterval(async () => {
        try {
            const url = new URL(pollUrl, window.location.origin);
            url.searchParams.set('after', String(lastId));

            const response = await fetch(url, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const messages = data.messages ?? [];
            const wasNearBottom = isNearBottom();

            messages.forEach((message) => {
                appendMessage(message);

                if (message.id > lastId) {
                    lastId = message.id;
                }
            });

            if (messages.length > 0 && wasNearBottom) {
                adminThread.scrollTop = adminThread.scrollHeight;
            }

            if (statusSelect && data.status) {
                statusSelect.value = data.status;
            }

            if (typeof data.unread === 'number') {
                setUnreadBadges(data.unread);
            }
        } catch (error) {
            // Diamkan.
        }
    }, 5000);
}

// Badge pesan belum dibaca di halaman admin lain (mis. dashboard / leads).
const unreadUrl = document.querySelector('[data-admin-unread-url]')?.getAttribute('data-admin-unread-url');

if (unreadUrl && unreadBadges.length > 0 && !messageLiveList && !adminThread) {
    setInterval(async () => {
        try {
            const response = await fetch(unreadUrl, { headers: { Accept: 'application/json' } });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (typeof data.unread === 'number') {
                setUnreadBadges(data.unread);
            }
        } catch (error) {
            // Diamkan.
        }
    }, 20000);
}
