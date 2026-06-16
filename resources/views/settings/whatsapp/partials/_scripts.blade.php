@push('scripts')
<script>
    function whatsappSettingsApp(initial = {}) {
        const PAGE_POLL_MS = 12000;
        const QR_POLL_MS = 5000;
        const TICK_MS = 1000;

        return {
            openCreate: false,
            openQrPanel: false,
            qrSessionId: null,
            qrLabel: '',
            qrImage: null,
            qrStatus: '',
            qrError: null,
            qrReconnecting: false,
            qrPollTimer: null,
            pagePollTimer: null,
            tickTimer: null,
            tick: 0,
            pageRefreshing: false,
            liveClock: '',
            healthUptimeBase: 0,
            healthFetchedAt: 0,
            health: initial.health ?? null,
            sessions: initial.sessions ?? [],
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',

            get healthIsUp() {
                return this.health?.status === 'ok';
            },

            get displayUptime() {
                void this.tick;
                if (!this.healthIsUp) return '—';
                const base = Number(this.healthUptimeBase);
                if (!Number.isFinite(base) || base < 0) return '—';
                const elapsed = this.healthFetchedAt
                    ? Math.floor((Date.now() - this.healthFetchedAt) / 1000)
                    : 0;
                return this.formatUptime(base + elapsed);
            },

            init() {
                this.syncHealthUptime();
                this.startTickTimer();
                this.fetchPageData();
                this.startPagePolling();

                const params = new URLSearchParams(window.location.search);
                const pairId = params.get('pair');
                if (pairId) {
                    const session = this.sessions.find(s => s.id === pairId);
                    this.openQr(pairId, session?.name || 'Session baru');
                }
            },

            syncHealthUptime() {
                const seconds = Number(this.health?.uptime_seconds);
                this.healthUptimeBase = Number.isFinite(seconds) && seconds >= 0 ? seconds : 0;
                this.healthFetchedAt = Date.now();
            },

            updateLiveClock() {
                this.liveClock = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                });
            },

            startTickTimer() {
                this.stopTickTimer();
                this.updateLiveClock();
                this.tickTimer = setInterval(() => {
                    this.tick++;
                    this.updateLiveClock();
                }, TICK_MS);
            },

            stopTickTimer() {
                if (this.tickTimer) {
                    clearInterval(this.tickTimer);
                    this.tickTimer = null;
                }
            },

            formatUptime(seconds) {
                const value = Number(seconds);
                if (!Number.isFinite(value) || value < 0) return '—';
                const h = Math.floor(value / 3600);
                const m = Math.floor((value % 3600) / 60);
                const s = Math.floor(value % 60);
                return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
            },

            formatDate(iso) {
                if (!iso) return '—';
                try {
                    return new Intl.DateTimeFormat('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    }).format(new Date(iso));
                } catch {
                    return '—';
                }
            },

            truncateId(id) {
                if (!id) return '';
                return id.length > 18 ? id.slice(0, 18) + '…' : id;
            },

            sessionStatusClass(session) {
                if (session.connected) {
                    return 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800';
                }
                if (session.status === 'failed') {
                    return 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800';
                }
                return 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800';
            },

            sessionStatusLabel(session) {
                if (session.connected) return 'CONNECTED';
                return (session.status || 'unknown').toUpperCase().replace(/_/g, ' ');
            },

            qrButtonLabel(session) {
                return session.status === 'failed' ? 'Scan Ulang' : 'QR';
            },

            startPagePolling() {
                this.stopPagePolling();
                this.pagePollTimer = setInterval(() => this.fetchPageData(), PAGE_POLL_MS);
            },

            stopPagePolling() {
                if (this.pagePollTimer) {
                    clearInterval(this.pagePollTimer);
                    this.pagePollTimer = null;
                }
            },

            async refreshPageData() {
                await this.fetchPageData(true);
            },

            async fetchPageData(manual = false) {
                if (this.pageRefreshing) return;
                if (manual) this.pageRefreshing = true;

                try {
                    const [healthRes, sessionsRes] = await Promise.all([
                        fetch(`{{ url('settings/whatsapp/health') }}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        }),
                        fetch(`{{ url('settings/whatsapp/sessions') }}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        }),
                    ]);

                    const healthJson = await healthRes.json();
                    if (healthJson.success && healthJson.data) {
                        this.health = healthJson.data;
                    } else {
                        this.health = { status: 'error', sessions_connected: 0, sessions_total: 0, uptime_seconds: 0, queue_pending: 0 };
                    }

                    const sessionsJson = await sessionsRes.json();
                    if (sessionsJson.success && Array.isArray(sessionsJson.data)) {
                        this.sessions = sessionsJson.data;
                    }

                    this.syncHealthUptime();
                } catch {
                    // ignore transient network errors
                } finally {
                    if (manual) this.pageRefreshing = false;
                }
            },

            async confirmDeleteSession(sessionId, sessionName, phone = null) {
                const phoneLine = phone ? `\nNomor: ${phone}` : '';
                const ok = await Alpine.store('confirm').ask({
                    title: 'Hapus Session WhatsApp',
                    subtitle: sessionName,
                    message: `Session "${sessionName}" akan dihapus permanen.${phoneLine}\n\nNomor WhatsApp akan logout dari perangkat ini. Tindakan ini tidak dapat dibatalkan.`,
                    confirmLabel: 'Ya, Hapus',
                    cancelLabel: 'Batal',
                    variant: 'danger',
                });

                if (!ok) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('settings/whatsapp/sessions') }}/${sessionId}`;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = this.csrfToken;
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            },

            async openQr(sessionId, label) {
                this.qrSessionId = sessionId;
                this.qrLabel = label;
                this.qrImage = null;
                this.qrError = null;
                this.qrReconnecting = false;
                this.qrStatus = 'memeriksa status...';
                this.openQrPanel = true;
                this.stopQrPolling();

                const status = await this.fetchQrSessionStatus();
                if (this.needsReconnect(status)) {
                    const ok = await this.reconnectSession();
                    if (!ok) return;
                }

                await this.fetchQr();
                this.startQrPolling();
            },

            needsReconnect(status) {
                if (!status) return true;
                if (status.connected) return false;
                return ['failed', 'stopped'].includes(status.status);
            },

            closeQr() {
                this.openQrPanel = false;
                this.stopQrPolling();
                if (window.location.search.includes('pair=')) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('pair');
                    window.history.replaceState({}, '', url);
                }
            },

            startQrPolling() {
                this.stopQrPolling();
                this.qrPollTimer = setInterval(() => this.pollQrStatus(), QR_POLL_MS);
            },

            stopQrPolling() {
                if (this.qrPollTimer) {
                    clearInterval(this.qrPollTimer);
                    this.qrPollTimer = null;
                }
            },

            async fetchQrSessionStatus() {
                if (!this.qrSessionId) return null;
                try {
                    const res = await fetch(`{{ url('settings/whatsapp/sessions') }}/${this.qrSessionId}/status`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    const json = await res.json();
                    if (json.success && json.data) {
                        this.qrStatus = json.data.status || '';
                        return json.data;
                    }
                } catch {
                    // ignore
                }
                return null;
            },

            async reconnectSession() {
                if (!this.qrSessionId || this.qrReconnecting) return false;

                this.qrReconnecting = true;
                this.qrError = null;
                this.qrImage = null;
                this.qrStatus = 'memulai pairing ulang...';

                try {
                    const res = await fetch(`{{ url('settings/whatsapp/sessions') }}/${this.qrSessionId}/reconnect`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    const json = await res.json();

                    if (!json.success || !json.data?.id) {
                        this.qrError = json.message || 'Gagal pairing ulang';
                        this.qrReconnecting = false;
                        return false;
                    }

                    this.qrSessionId = json.data.id;
                    this.qrLabel = json.data.name || this.qrLabel;
                    this.qrStatus = 'qr_ready';
                    this.qrReconnecting = false;
                    await this.fetchPageData();
                    return true;
                } catch {
                    this.qrError = 'Gagal pairing ulang. Coba refresh halaman.';
                    this.qrReconnecting = false;
                    return false;
                }
            },

            async fetchQr() {
                if (!this.qrSessionId) return;

                try {
                    const res = await fetch(`{{ url('settings/whatsapp/sessions') }}/${this.qrSessionId}/qr`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    const json = await res.json();

                    if (json.success && json.data?.image_base64) {
                        this.qrImage = json.data.image_base64;
                        this.qrStatus = json.data.status || 'qr_ready';
                        this.qrError = null;
                        return;
                    }

                    const status = await this.fetchQrSessionStatus();
                    if (this.needsReconnect(status)) {
                        const ok = await this.reconnectSession();
                        if (ok) {
                            await this.fetchQr();
                            return;
                        }
                    }

                    this.qrError = json.message || 'QR tidak tersedia';
                } catch {
                    this.qrError = 'Gagal memuat QR';
                }
            },

            async onSessionConnected() {
                this.qrStatus = 'connected';
                this.stopQrPolling();
                await this.fetchPageData();
                setTimeout(() => this.closeQr(), 600);
            },

            async pollQrStatus() {
                if (!this.qrSessionId) return;

                const status = await this.fetchQrSessionStatus();
                if (!status) return;

                if (status.connected) {
                    await this.onSessionConnected();
                    return;
                }

                if (status.status === 'failed') {
                    this.qrError = 'QR kedaluwarsa. Memulai pairing ulang...';
                    this.qrImage = null;
                    const ok = await this.reconnectSession();
                    if (ok) {
                        this.qrError = null;
                        await this.fetchQr();
                    }
                    return;
                }

                if (!this.qrImage) {
                    await this.fetchQr();
                }
            },
        };
    }
</script>
@endpush