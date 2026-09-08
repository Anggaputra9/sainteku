import { readFileSync } from 'node:fs';
import vm from 'node:vm';
import test from 'node:test';
import assert from 'node:assert/strict';

const view = readFileSync(new URL('../Modules/MonevAkademik/resources/views/tashih/partials/modal-create-workspace.blade.php', import.meta.url), 'utf8');
const script = view.match(/<script>([\s\S]*?)<\/script>/)[1];

function setup() {
    const options = ['A', 'B', 'C', 'D', 'E'].map(option => ({
        value: '', dataset: { option }, setAttribute(name, value) { this[name] = value; },
    }));
    const radio = { value: 'A', setAttribute(name, value) { this[name] = value; } };
    const fields = { querySelectorAll: () => [...options, radio], setAttribute(name, value) { this[name] = value; } };
    const error = {};
    const type = { value: 'multiple_choice' };
    const weight = { value: '100' };
    const card = {
        querySelector(selector) {
            return ({ '.q-type': type, '.q-options': fields, '.error-options': error,
                '.q-text': { value: 'Question' }, '.q-weight': weight,
                'input[type="radio"]:checked': radio })[selector] || null;
        },
        querySelectorAll(selector) {
            if (selector === '.q-option') return options;
            if (selector === '.q-options input') return [...options, radio];
            if (selector === '.q-cpmk-checkbox:checked') return [{}];
            return [];
        },
    };
    const elements = Object.fromEntries(['form-pengajuan', 'weight-badge', 'action-buttons-container', 'weight-number', 'btn-submit'].map(id => [id, { style: {}, addEventListener() {} }]));
    const context = vm.createContext({
        document: { getElementById: id => elements[id], querySelectorAll: selector => selector === '.question-card' ? [card] : [weight] },
        setTimeout: () => 1, clearTimeout() {},
    });
    vm.runInContext(script, context);
    return { context, card, options, radio, fields, error, type, submit: elements['btn-submit'] };
}

test('MC validation explains missing options and keys, then clears errors when valid', () => {
    const s = setup();
    s.context.validateFormStates();
    assert.equal(s.error.hidden, false);
    assert.equal(s.submit.disabled, true);
    assert.equal(s.options[0]['aria-invalid'], 'true');
    s.options[0].value = 'First';
    s.options[1].value = 'Second';
    s.context.validateFormStates();
    assert.equal(s.error.hidden, true);
    assert.equal(s.fields['aria-invalid'], 'false');
    assert.equal(s.submit.disabled, false);
    s.radio.value = 'C';
    s.context.validateFormStates();
    assert.equal(s.error.hidden, false);
    assert.equal(s.submit.disabled, true);
});

test('switching to essay disables MC inputs, clears errors, and preserves option values', () => {
    const s = setup();
    s.options[0].value = 'Retained option';
    s.type.value = 'essay';
    s.context.toggleQuestionType(s.card);
    assert.equal(s.fields.hidden, true);
    assert.equal(s.error.hidden, true);
    assert.equal(s.submit.disabled, false);
    assert.ok(s.options.every(input => input.disabled));
    assert.equal(s.radio.disabled, true);
    s.type.value = 'multiple_choice';
    s.context.toggleQuestionType(s.card);
    assert.equal(s.fields.hidden, false);
    assert.ok(s.options.every(input => !input.disabled));
    assert.equal(s.options[0].value, 'Retained option');
    assert.equal(s.error.hidden, false);
});
