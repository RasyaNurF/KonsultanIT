import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';
import { runInNewContext } from 'node:vm';

class Element {
    children = [];
    attributes = new Map();
    selectors = new Map();
    listeners = new Map();
    classList = { add() {}, remove() {} };
    scrollTop = 0;
    scrollHeight = 100;

    setAttribute(name, value) { this.attributes.set(name, String(value)); }
    getAttribute(name) { return this.attributes.get(name); }
    addEventListener(name, listener) { this.listeners.set(name, listener); }
    querySelector(selector) {
        const id = selector.match(/^\[data-chat-message="(\d+)"\]$/)?.[1];
        return id ? this.children.find((child) => child.getAttribute('data-chat-message') === id) : this.selectors.get(selector) ?? null;
    }
    querySelectorAll(selector) {
        return selector === '[data-chat-message]'
            ? this.children.filter((child) => child.getAttribute('data-chat-message')) : [];
    }
    appendChild(child) { this.insertBefore(child, null); }
    insertBefore(child, reference) {
        child.parent = this;
        const index = reference ? this.children.indexOf(reference) : -1;
        if (index < 0) this.children.push(child);
        else this.children.splice(index, 0, child);
    }
    remove() { this.parent.children = this.parent.children.filter((child) => child !== this); }
    focus() {}
}

const settle = () => new Promise((resolve) => setImmediate(resolve));
const message = (id, sender = 'admin') => ({ id, sender, body: `Pesan ${id}`, time: '12:00' });

function widget(fetchResponse) {
    const root = new Element();
    const toggle = new Element();
    const messages = new Element();
    const form = new Element();
    const intervals = [];
    root.setAttribute('data-messages-url', '/pesan');
    root.setAttribute('data-store-url', '/pesan');

    for (const selector of ['panel', 'close', 'body', 'error', 'badge', 'typing']) {
        root.selectors.set(`[data-chat-${selector}]`, new Element());
    }
    root.selectors.set('[data-chat-messages]', messages);
    root.selectors.set('[data-chat-form]', form);
    form.selectors.set('button[type="submit"]', new Element());
    messages.appendChild(root.querySelector('[data-chat-typing]'));

    const document = {
        querySelector: (selector) => selector === '[data-chat-widget]' ? root : null,
        querySelectorAll: (selector) => selector === '[data-chat-toggle]' ? [toggle] : [],
        createElement: () => new Element(),
        getElementById: () => null,
        addEventListener() {},
    };
    const source = readFileSync(new URL('../../resources/js/app.js', import.meta.url), 'utf8')
        .replace(/^import '\.\/admin';\r?\n/m, '');

    runInNewContext(source, {
        document, window: {}, FormData: class {},
        fetch: async (url, options) => ({ ok: true, json: async () => fetchResponse(url, options) }),
        setInterval: (callback) => { intervals.push(callback); return intervals.length; },
        clearInterval() {},
    });

    return {
        messages,
        open: async () => { toggle.listeners.get('click')(); await settle(); },
        send: async () => { await form.listeners.get('submit')({ preventDefault() {} }); },
        poll: async () => { intervals[0](); await settle(); },
    };
}

test('balasan admin sebelum pesan kiriman tetap diterima dan ditampilkan berurutan', async () => {
    const requests = [];
    const chat = widget((url, options) => {
        if (options?.method === 'POST') return { message: message(3, 'user') };
        requests.push(url);
        return { messages: requests.length === 1 ? [message(1)] : [message(2), message(3, 'user')] };
    });

    await chat.open();
    await chat.send();
    await chat.poll();

    assert.equal(requests[1], '/pesan?after=1');
    assert.deepEqual(chat.messages.querySelectorAll('[data-chat-message]')
        .map((row) => row.getAttribute('data-chat-message')), ['1', '2', '3']);
});

test('polling tanpa pesan baru mempertahankan posisi scroll pengguna', async () => {
    let polls = 0;
    const chat = widget(() => ({ messages: ++polls === 1 ? [message(1)] : [] }));
    await chat.open();
    chat.messages.scrollTop = 10;
    await chat.poll();

    assert.equal(chat.messages.scrollTop, 10);
});
