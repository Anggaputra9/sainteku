import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import { runInNewContext } from 'node:vm';

const view = readFileSync(new URL('../resources/views/landing.blade.php', import.meta.url), 'utf8');
const script = view.match(/<script>([\s\S]*?)<\/script>/)[1]
    .replace(/@json\(__\('messages\.processing'\)\)/g, '"Processing"')
    .replace(/@json\(__\('messages\.error_occurred'\)\)/g, '"Request failed"')
    .replace(/@json\(url\('\/dashboard'\)\)/g, '"/dashboard"');

function setup(response) {
    const elements = new Map();
    const timers = [];
    const redirects = [];
    function element(id) {
        if (!elements.has(id)) elements.set(id, {
            hidden: false, disabled: false, textContent: 'Submit', type: 'password',
            action: `/${id}`, attributes: {}, listeners: {},
            addEventListener(event, callback) { this.listeners[event] = callback; },
            setAttribute(name, value) { this.attributes[name] = value; },
            removeAttribute(name) { delete this.attributes[name]; },
            querySelector() { return element(`${id}-submit`); },
            focus() { this.focused = true; },
            reset() { this.resetCalled = true; },
        });
        return elements.get(id);
    }
    runInNewContext(script, {
        document: { getElementById: element },
        window: { location: { assign: url => redirects.push(url) } },
        FormData: class {},
        fetch: async () => {
            if (response instanceof Error) throw response;
            return response;
        },
        setTimeout: (callback, delay) => timers.push({ callback, delay }),
    });
    return { element, timers, redirects };
}

test('recovery navigation and password visibility support keyboard state', () => {
    const { element } = setup();
    element('forgotPasswordLink').listeners.click({ preventDefault() {} });
    assert.equal(element('loginPanel').hidden, true);
    assert.equal(element('forgotPasswordPanel').hidden, false);
    assert.equal(element('forgotTitle').focused, true);
    element('backToLogin').listeners.click();
    assert.equal(element('loginPanel').hidden, false);
    assert.equal(element('loginTitle').focused, true);
    const toggle = element('togglePasswordBtn');
    toggle.listeners.click({ currentTarget: toggle });
    assert.equal(element('password').type, 'text');
    assert.equal(toggle.attributes['aria-pressed'], 'true');
    toggle.listeners.click({ currentTarget: toggle });
    assert.equal(element('password').type, 'password');
});

for (const formId of ['loginForm', 'forgotPasswordForm']) {
    const alertId = formId === 'loginForm' ? 'loginFormAlert' : 'forgotPasswordAlert';
    test(`${formId} preserves throttling and enables retry after cooldown`, async () => {
        const { element, timers } = setup({
            ok: false, status: 429,
            json: async () => ({ message: 'Wait before retrying', retry_after: 300 }),
        });
        await element(formId).listeners.submit({ preventDefault() {} });
        assert.equal(element(alertId).textContent, 'Wait before retrying');
        assert.equal(element(`${formId}-submit`).disabled, true);
        assert.equal(timers[0].delay, 300000);
        timers[0].callback();
        assert.equal(element(`${formId}-submit`).disabled, false);
    });

    test(`${formId} recovers from network and non-JSON failures`, async () => {
        for (const response of [new Error('offline'), { json: async () => { throw new Error('HTML'); } }]) {
            const { element } = setup(response);
            await element(formId).listeners.submit({ preventDefault() {} });
            assert.equal(element(alertId).textContent, 'Request failed');
            assert.equal(element(alertId).hidden, false);
            assert.equal(element(`${formId}-submit`).disabled, false);
            assert.equal(element(formId).attributes['aria-busy'], undefined);
        }
    });
}

test('successful login follows the server redirect and prevents duplicate submissions', async () => {
    const { element, redirects } = setup({
        ok: true, status: 200, json: async () => ({ success: true, redirect: '/dashboard' }),
    });
    await element('loginForm').listeners.submit({ preventDefault() {} });
    assert.deepEqual(redirects, ['/dashboard']);
    assert.equal(element('loginForm-submit').disabled, true);
});

test('recovery success remains visible and validation errors are text, not HTML', async () => {
    const { element } = setup({
        ok: true, status: 200, json: async () => ({ success: true, message: 'Check your email' }),
    });
    await element('forgotPasswordForm').listeners.submit({ preventDefault() {} });
    assert.equal(element('forgotPasswordAlert').textContent, 'Check your email');
    assert.equal(element('forgotPasswordForm').resetCalled, true);
    assert.equal(element('forgotPasswordForm-submit').disabled, false);

    const failed = setup({
        ok: false, status: 422,
        json: async () => ({ errors: { credential: ['<b>Invalid credential</b>'] } }),
    });
    await failed.element('loginForm').listeners.submit({ preventDefault() {} });
    assert.equal(failed.element('loginFormAlert').textContent, '<b>Invalid credential</b>');
    assert.equal(failed.element('loginForm-submit').disabled, false);
});
