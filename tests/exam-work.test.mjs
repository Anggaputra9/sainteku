import { readFileSync } from 'node:fs';
import vm from 'node:vm';
import test from 'node:test';
import assert from 'node:assert/strict';

const view = readFileSync(new URL('../Modules/Ujian/resources/views/attempt/work.blade.php', import.meta.url), 'utf8');
const script = view.match(/<script>([\s\S]*?)<\/script>/)[1];

function setup(fetch) {
    let submitted = false;
    const context = vm.createContext({
        document: {
            querySelector: () => ({ content: 'csrf' }),
            getElementById: () => ({ submit: () => { submitted = true; } }),
        },
        fetch,
        window: { location: {} },
        console,
    });
    vm.runInContext(script, context);
    const app = context.examWork({ saveUrl: '/save', questions: [
        { id: 1, question_type: 'multiple_choice', selected_option: 'B', answer_text: '' },
        { id: 2, question_type: 'essay', answer_text: 'Essay answer' },
    ] });
    return { app, submitted: () => submitted };
}

test('submit flushes MC selections and essay text before submitting', async () => {
    const payloads = [];
    const { app, submitted } = setup(async (_, request) => {
        payloads.push(JSON.parse(request.body));
        return { json: async () => ({ ok: true }) };
    });
    await app.runSubmitConfirmation();
    assert.deepEqual(payloads, [
        { question_id: 1, selected_option: 'B' },
        { question_id: 2, answer_text: 'Essay answer' },
    ]);
    assert.equal(submitted(), true);
});

test('failed save prevents submission and permits retry', async () => {
    let online = false;
    const { app, submitted } = setup(async () => {
        if (!online) throw new Error('Offline');
        return { json: async () => ({ ok: true }) };
    });
    await app.runSubmitConfirmation();
    assert.equal(submitted(), false);
    assert.equal(app.confirmSubmitting, false);
    online = true;
    await app.runSubmitConfirmation();
    assert.equal(submitted(), true);
});

test('rapid MC changes are saved sequentially and clearing sends null', async () => {
    const payloads = [];
    let active = 0;
    const { app } = setup(async (_, request) => {
        assert.equal(active++, 0);
        await new Promise(resolve => setTimeout(resolve, 2));
        payloads.push(JSON.parse(request.body));
        active--;
        return { json: async () => ({ ok: true }) };
    });
    const first = app.saveAnswer(1, '');
    app.questions[0].selected_option = '';
    const second = app.saveAnswer(1, '');
    await Promise.all([first, second]);
    assert.deepEqual(payloads.map(p => p.selected_option), ['B', null]);
    assert.equal(app.questions[0].is_answered, false);
});

test('duplicate submit confirmation does not enqueue another flush', async () => {
    let saves = 0;
    const { app, submitted } = setup(async () => {
        saves++;
        return { json: async () => ({ ok: true }) };
    });
    await Promise.all([app.runSubmitConfirmation(), app.runSubmitConfirmation()]);
    assert.equal(saves, 2);
    assert.equal(submitted(), true);
});
