// Drawer navigasi seluler, garis bawah header, dan animasi reveal saat menggulir.

import './admin';

const drawer = document.querySelector('[data-drawer]');
const toggle = document.querySelector('[data-toggle]');

if (toggle && drawer) {
    toggle.addEventListener('click', () => {
        const open = drawer.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', String(!open));
    });

    drawer.querySelectorAll('a, [data-chat-toggle]').forEach((link) => {
        link.addEventListener('click', () => {
            drawer.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
}

// Navbar kaca: transparan di atas lalu berkaca gelap setelah menggulir (hanya
// beranda; halaman lain selalu berkaca dari server dan tidak diubah di sini).
const glassHeader = document.getElementById('glass-header');

if (glassHeader && glassHeader.hasAttribute('data-transparent-top')) {
    const glassClasses = ['bg-navy-950/80', 'backdrop-blur', 'shadow-sm', 'border-b', 'border-white/10'];

    const onGlassScroll = () => {
        const scrolled = window.scrollY > 24;
        glassClasses.forEach((cls) => glassHeader.classList.toggle(cls, scrolled));
    };

    window.addEventListener('scroll', onGlassScroll, { passive: true });
    onGlassScroll();
}

// --- Widget pesan live (bubble kiri bawah) ---
const chatWidget = document.querySelector('[data-chat-widget]');

if (chatWidget) {
    const chatPanel = chatWidget.querySelector('[data-chat-panel]');
    const chatToggles = document.querySelectorAll('[data-chat-toggle]');
    const chatClose = chatWidget.querySelector('[data-chat-close]');
    const chatMessages = chatWidget.querySelector('[data-chat-messages]');
    const chatForm = chatWidget.querySelector('[data-chat-form]');
    const chatBody = chatWidget.querySelector('[data-chat-body]');
    const chatError = chatWidget.querySelector('[data-chat-error]');
    const chatBadge = chatWidget.querySelector('[data-chat-badge]');
    const chatSubmit = chatForm?.querySelector('button[type="submit"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const messagesUrl = chatWidget.getAttribute('data-messages-url');
    const storeUrl = chatWidget.getAttribute('data-store-url');

    const pollInterval = 5000;
    let lastMessageId = 0;
    let pollTimer = null;
    let isOpen = false;

    const scrollToBottom = () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };

    const renderMessage = (message) => {
        if (chatMessages.querySelector(`[data-chat-message="${message.id}"]`)) {
            return;
        }

        chatMessages.querySelector('[data-chat-empty]')?.remove();

        const isOwn = message.sender !== 'admin';

        const row = document.createElement('div');
        row.setAttribute('data-chat-message', message.id);
        row.className = isOwn ? 'flex justify-end' : 'flex justify-start';

        const bubble = document.createElement('div');
        bubble.className = isOwn
            ? 'max-w-[80%] rounded-2xl rounded-br-sm bg-navy-800 px-4 py-2.5 text-sm leading-relaxed text-white'
            : 'max-w-[80%] rounded-2xl rounded-bl-sm bg-white px-4 py-2.5 text-sm leading-relaxed ring-1 ring-neutral-200';

        if (!isOwn) {
            const label = document.createElement('p');
            label.className = 'text-[11px] font-bold text-navy-700';
            label.textContent = message.is_auto ? 'Admin Nusakode (otomatis)' : (message.name || 'Tim Nusakode');
            bubble.appendChild(label);
        }

        const text = document.createElement('p');
        if (!isOwn) {
            text.className = 'mt-0.5';
        }
        text.textContent = message.body;
        bubble.appendChild(text);

        const time = document.createElement('p');
        time.className = isOwn
            ? 'mt-1 text-right text-[11px] text-neutral-300'
            : 'mt-1 text-[11px] text-neutral-400';
        time.textContent = message.time;
        bubble.appendChild(time);

        row.appendChild(bubble);
        chatMessages.appendChild(row);
    };

    const renderMessages = (messages) => {
        messages.forEach((message) => {
            renderMessage(message);

            if (message.id > lastMessageId) {
                lastMessageId = message.id;
            }
        });

        scrollToBottom();
    };

    const showEmptyState = () => {
        if (chatMessages.querySelector('[data-chat-message]')) {
            return;
        }

        const empty = chatMessages.querySelector('[data-chat-empty]');

        if (empty) {
            empty.textContent = 'Belum ada pesan. Mulai percakapan dengan mengirim pesan pertama Anda.';
        }
    };

    const hideBadge = () => {
        chatBadge?.classList.add('hidden');
    };

    const loadMessages = async () => {
        const response = await fetch(`${messagesUrl}?after=${lastMessageId}`, {
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            return;
        }

        const data = await response.json();
        renderMessages(data.messages ?? []);
        showEmptyState();
        hideBadge();
    };

    const stopPolling = () => {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    };

    const startPolling = () => {
        stopPolling();
        pollTimer = setInterval(() => {
            loadMessages().catch(() => {});
        }, pollInterval);
    };

    const openChat = async () => {
        isOpen = true;
        chatPanel.hidden = false;
        chatToggles.forEach((el) => el.setAttribute('aria-expanded', 'true'));

        try {
            await loadMessages();
        } catch (error) {
            // Diamkan; polling berikutnya akan mencoba lagi.
        }

        startPolling();
        chatBody?.focus();
    };

    const closeChat = () => {
        isOpen = false;
        chatPanel.hidden = true;
        chatToggles.forEach((el) => el.setAttribute('aria-expanded', 'false'));
        stopPolling();
    };

    chatToggles.forEach((el) => {
        el.addEventListener('click', () => {
            if (isOpen) {
                closeChat();
            } else {
                openChat();
            }
        });
    });

    chatClose?.addEventListener('click', closeChat);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen) {
            closeChat();
        }
    });

    chatForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        chatError?.classList.add('hidden');

        if (chatSubmit) {
            chatSubmit.disabled = true;
        }

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(chatForm),
            });

            if (response.status === 422) {
                const data = await response.json();
                const firstError = Object.values(data.errors ?? {}).flat()[0];
                throw new Error(firstError ?? 'Pesan belum valid. Periksa kembali isian Anda.');
            }

            if (!response.ok) {
                throw new Error('Pesan gagal terkirim. Coba lagi sebentar lagi.');
            }

            const data = await response.json();

            if (data.message) {
                renderMessages([data.message]);
            }

            chatBody.value = '';
        } catch (error) {
            if (chatError) {
                chatError.textContent = error.message;
                chatError.classList.remove('hidden');
            }
        } finally {
            if (chatSubmit) {
                chatSubmit.disabled = false;
            }
        }
    });
}

// --- Intip kata sandi (halaman login) ---
document.querySelectorAll('[data-pw-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.getAttribute('aria-controls'));
        const eye = button.querySelector('[data-eye]');
        const eyeOff = button.querySelector('[data-eye-off]');

        if (!input) {
            return;
        }

        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(show));
        button.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        eye?.classList.toggle('hidden', show);
        eyeOff?.classList.toggle('hidden', !show);
    });
});

// --- Bilah pengumuman (dapat ditutup; padding konten menyesuaikan + cookie) ---
const announcementBar = document.querySelector('[data-promo]');

const setCookie = (name, value, maxAgeSeconds) => {
    const parts = [`${name}=${encodeURIComponent(value)}`, 'path=/', 'samesite=lax'];

    if (maxAgeSeconds) {
        parts.push(`max-age=${maxAgeSeconds}`);
    }

    document.cookie = parts.join(';');
};

document.querySelector('[data-promo-close]')?.addEventListener('click', () => {
    if (announcementBar) {
        const name = announcementBar.getAttribute('data-dismiss-cookie');
        const value = announcementBar.getAttribute('data-dismiss-value');

        if (name && value) {
            setCookie(name, value, 60 * 60 * 24 * 30);
        }

        announcementBar.remove();
    }

    document.getElementById('main')?.classList.replace('pt-[112px]', 'pt-[72px]');
    const hero = document.getElementById('beranda-hero');
    hero?.classList.replace('pt-60', 'pt-52');
    hero?.classList.replace('sm:pt-72', 'sm:pt-64');
});

// --- Pop-up pengumuman (frekuensi diatur admin) ---
const announcementPopup = document.querySelector('[data-announcement-popup]');

if (announcementPopup) {
    const popupScope = announcementPopup.getAttribute('data-popup-scope');
    const popupDismissible = announcementPopup.getAttribute('data-popup-dismissible') !== '0';
    const popupCookie = announcementPopup.getAttribute('data-popup-cookie');
    const popupValue = announcementPopup.getAttribute('data-popup-value');

    // Frekuensi "setiap kunjungan" memakai sessionStorage per tab: tampil saat tab
    // dibuka, tidak berulang saat berpindah halaman, dan muncul lagi di tab baru.
    const readSeenInTab = () => {
        try {
            return sessionStorage.getItem(popupCookie);
        } catch (error) {
            return null;
        }
    };

    const markSeenInTab = () => {
        try {
            sessionStorage.setItem(popupCookie, popupValue);
        } catch (error) {
            // Mode privat bisa memblokir sessionStorage; pop-up tetap bisa ditutup.
        }
    };

    const openPopup = () => {
        announcementPopup.classList.remove('hidden');
        announcementPopup.classList.add('flex');
    };

    const closePopup = () => {
        if (popupScope === 'always') {
            markSeenInTab();
        } else if (popupScope === 'session') {
            setCookie(popupCookie, popupValue);
        } else if (popupScope === 'days') {
            const days = Number(announcementPopup.getAttribute('data-popup-days')) || 1;
            setCookie(popupCookie, popupValue, 60 * 60 * 24 * days);
        }

        announcementPopup.classList.add('hidden');
        announcementPopup.classList.remove('flex');
    };

    if (popupScope === 'always' && readSeenInTab() === popupValue) {
        // Sudah ditutup pada kunjungan tab ini.
    } else {
        openPopup();

        announcementPopup.querySelectorAll('[data-popup-close], [data-popup-dismiss]').forEach((element) => {
            element.addEventListener('click', closePopup);
        });

        if (popupDismissible) {
            announcementPopup.querySelector('[data-popup-backdrop]')?.addEventListener('click', closePopup);

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closePopup();
                }
            });
        }
    }
}

// --- Banner persetujuan cookie ---
const cookieNotice = document.querySelector('[data-cookie-notice]');

if (cookieNotice) {
    cookieNotice.querySelectorAll('[data-cookie-consent]').forEach((button) => {
        button.addEventListener('click', () => {
            setCookie('nusakode_cookie_consent', button.getAttribute('data-cookie-consent'), 60 * 60 * 24 * 365);
            cookieNotice.remove();
        });
    });
}

// --- Tab solusi interaktif (Beranda) ---
const solusiTabs = document.querySelectorAll('[data-solusi-tab]');
const solusiPanels = document.querySelectorAll('[data-solusi-panel]');
const solusiTabOn = ['bg-navy-950', 'text-white'];
const solusiTabOff = ['text-neutral-500', 'ring-1', 'ring-neutral-200'];

if (solusiTabs.length > 0) {
    solusiTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const index = tab.getAttribute('data-solusi-tab');

            solusiTabs.forEach((other) => {
                const active = other === tab;
                other.setAttribute('aria-selected', String(active));
                solusiTabOn.forEach((cls) => other.classList.toggle(cls, active));
                solusiTabOff.forEach((cls) => other.classList.toggle(cls, !active));
            });

            solusiPanels.forEach((panel) => {
                panel.hidden = panel.getAttribute('data-solusi-panel') !== index;
            });
        });
    });
}

// Reveal on scroll: blok section muncul halus, item daftar menyusul berurutan.
const listItems = document.querySelectorAll(
    '#solusi .solusi-tabs > button, #portofolio article, #insight .divide-y > a'
);

listItems.forEach((el) => {
    const siblings = [...el.parentElement.children];
    el.style.transitionDelay = `${(siblings.indexOf(el) % 4) * 80}ms`;
});

const revealTargets = document.querySelectorAll(
    'main section > div:not([data-no-reveal])'
);

if ('IntersectionObserver' in window && revealTargets.length > 0) {
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -48px 0px' }
    );

    revealTargets.forEach((el) => {
        el.classList.add('reveal');
        io.observe(el);
    });
}
