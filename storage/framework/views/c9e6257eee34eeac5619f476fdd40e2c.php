<?php $__env->startPush('scripts'); ?>
<script>
    function aiSettingsApp() {
        return {
            openCreate: false,
            openEdit: false,
            openDetail: false,
            providers: <?php echo json_encode($providers, 15, 512) ?>,
            form: {
                name: '',
                provider: '',
                api_key: '',
                api_endpoint: '',
                model: '',
                temperature: 0.7,
                max_tokens: 2000,
                top_p: 1.0,
                frequency_penalty: 0,
                presence_penalty: 0,
                daily_limit: 0,
                cost_per_1k_tokens: 0,
                priority: 0,
                is_active: true,
                is_default: false,
                notes: ''
            },
            detail: {},
            editId: null,

            init() {
                // Initialization if needed
            },

            get formAction() {
                if (this.openEdit && this.editId) {
                    return `<?php echo e(route('settings.ai.index')); ?>/${this.editId}`;
                }
                return `<?php echo e(route('settings.ai.store')); ?>`;
            },

            get currentProvider() {
                return this.providers[this.form.provider] || null;
            },

            get currentModels() {
                return this.currentProvider?.models || {};
            },

            get currentKeyHint() {
                return this.currentProvider?.key_hint || '';
            },

            resetForm() {
                this.form = {
                    name: '',
                    provider: '',
                    api_key: '',
                    api_endpoint: '',
                    model: '',
                    temperature: 0.7,
                    max_tokens: 2000,
                    top_p: 1.0,
                    frequency_penalty: 0,
                    presence_penalty: 0,
                    daily_limit: 0,
                    cost_per_1k_tokens: 0,
                    priority: 0,
                    is_active: true,
                    is_default: false,
                    notes: ''
                };
                this.editId = null;
            },

            onProviderChange() {
                const provider = this.currentProvider;
                if (!provider) return;

                // Set default endpoint
                this.form.api_endpoint = provider.endpoint || '';

                // Set default model
                const models = Object.keys(provider.models || {});
                if (models.length > 0) {
                    this.form.model = provider.default_model || models[0];
                }
            },

            openDetailModal(row) {
                this.detail = {
                    ...row,
                    provider_label: this.providers[row.provider]?.label || row.provider
                };
                this.openDetail = true;
            },

            openEditModal(row) {
                this.openDetail = false;
                this.editId = row.id;
                this.form = {
                    name: row.name,
                    provider: row.provider,
                    api_key: '', // Don't populate for security
                    api_endpoint: row.api_endpoint,
                    model: row.model,
                    temperature: parseFloat(row.temperature),
                    max_tokens: parseInt(row.max_tokens),
                    top_p: parseFloat(row.top_p),
                    frequency_penalty: parseInt(row.frequency_penalty),
                    presence_penalty: parseInt(row.presence_penalty),
                    daily_limit: parseInt(row.daily_limit),
                    cost_per_1k_tokens: parseFloat(row.cost_per_1k_tokens || 0),
                    priority: parseInt(row.priority),
                    is_active: !!row.is_active,
                    is_default: !!row.is_default,
                    notes: row.notes || ''
                };
                this.openEdit = true;
            }
        };
    }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\sainteku\resources\views/settings/ai/partials/_scripts.blade.php ENDPATH**/ ?>