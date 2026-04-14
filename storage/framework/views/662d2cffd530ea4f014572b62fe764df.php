<?php $__env->startSection('content'); ?>
    
    <div class="mx-auto max-w-screen-2xl 2xl:p-10" x-data="tashihApp" x-cloak>

        <div class="space-y-6">
            
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Tashih Soal</h2>
                    <nav>
                        <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                            <li>Monev Akademik /</li>
                            <li class="text-blue-600 dark:text-blue-400">Tashih Soal</li>
                        </ol>
                    </nav>
                </div>
                <?php if(Auth::user()->hasPermission(3, 'C')): ?>
                    <button @click="openSelectCourse = true; courseId = ''; courseName = '';"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-green-700 transition">
                        <i class="fas fa-file-circle-plus text-lg"></i> Buat Pengajuan
                    </button>
                <?php endif; ?>
            </div>
            <?php if(session('error')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg mb-4 mt-2">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-red-700 dark:text-red-400"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>
            <?php if(session('success')): ?>
                <div
                    class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                    <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-green-700 dark:text-green-400"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <button @click="activeTab = 'saya'"
                    :class="activeTab === 'saya' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700'"
                    class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                    <i class="fa-solid fa-folder-open"></i> Riwayat Pengajuan Saya
                </button>
                <?php if($isReviewer): ?>
                    <button @click="activeTab = 'review'"
                        :class="activeTab === 'review' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50'"
                        class="px-5 py-2 text-sm font-semibold rounded-full transition flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> Antrean Review
                        <span
                            class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold bg-white text-red-600 rounded-full dark:bg-red-200"><?php echo e($reviewQueue->count()); ?></span>
                    </button>
                <?php endif; ?>
            </div>

            
            <div x-show="activeTab === 'saya'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    
                    <button @click="filterStatus = 'all'"
                        :class="filterStatus === 'all' ? 'bg-gray-800 text-white shadow-md dark:bg-gray-200 dark:text-gray-900' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700'"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition">
                        Semua Dokumen
                    </button>

                    
                    <button @click="filterStatus = 'SUBMITTED'"
                        :class="filterStatus === 'SUBMITTED' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-amber-600 border border-amber-200 hover:bg-amber-50 dark:bg-gray-800 dark:border-amber-900/50 dark:hover:bg-amber-900/30'"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition">
                        <i class="fa-solid fa-clock mr-1"></i> Perlu Direview
                    </button>

                    
                    <button @click="filterStatus = 'APPROVED'"
                        :class="filterStatus === 'APPROVED' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50 dark:bg-gray-800 dark:border-green-900/50 dark:hover:bg-green-900/30'"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition">
                        <i class="fa-solid fa-check-circle mr-1"></i> Disetujui
                    </button>

                    
                    <button @click="filterStatus = 'REVISED'"
                        :class="filterStatus === 'REVISED' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/50 dark:hover:bg-red-900/30'"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Revisi
                    </button>
                </div>

                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 font-semibold w-1/3">Mata Kuliah</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jenis & Periode</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $myProposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr x-show="filterStatus === 'all' || filterStatus === '<?php echo e($prop->status); ?>'"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900 dark:text-white text-base">
                                                <?php echo e($prop->course->course_name); ?>

                                            </div>
                                            <div class="text-xs text-gray-500 mt-1"><?php echo e($prop->course_id); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-center"><span
                                                class="font-bold text-gray-700 dark:text-gray-300"><?php echo e($prop->exam_type); ?></span>
                                            <div class="text-xs text-gray-500 mt-0.5">2024/2025 Gasal</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <?php $statusClass = $prop->status == 'APPROVED' ? 'bg-green-100 text-green-800 border-green-200' : ($prop->status == 'REVISED' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-blue-100 text-blue-800 border-blue-200'); ?>
                                            <span
                                                class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider border <?php echo e($statusClass); ?>"><?php echo e($prop->status); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button type="button" @click="viewDetail('<?php echo e($prop->uuid); ?>', 'saya')"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-bold text-gray-700 border border-gray-200 hover:bg-gray-50 transition shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                                <i class="fa-solid fa-eye text-blue-500"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500"><i
                                                class="fas fa-folder-open text-3xl mb-3 opacity-50"></i><br>Belum ada riwayat
                                            pengajuan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <?php if($isReviewer): ?>
                <div x-show="activeTab === 'review'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                                <thead
                                    class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Dosen Pengaju</th>
                                        <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                        <th class="px-6 py-4 font-semibold text-center">Jenis</th>
                                        <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__empty_1 = true; $__currentLoopData = $reviewQueue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                            <td class="px-6 py-4 font-semibold"><?php echo e($queue->creator->name ?? 'Dosen'); ?></td>
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                <?php echo e($queue->course->course_name); ?>

                                            </td>
                                            <td class="px-6 py-4 text-center font-semibold"><?php echo e($queue->exam_type); ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" @click="viewDetail('<?php echo e($queue->uuid); ?>', 'review')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-orange-600 transition shadow-sm">
                                                    <i class="fa-solid fa-magnifying-glass"></i> Review
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada antrean review.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        
        <?php echo $__env->make('monevakademik::tashih.partials.modal-select-course', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('monevakademik::tashih.partials.modal-create-workspace', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('monevakademik::tashih.partials.modal-bank-soal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('monevakademik::tashih.partials.modal-detail', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

    
    <script>
        // Daftarkan komponen secara resmi biar nggak bentrok sama loading browser
        document.addEventListener('alpine:init', () => {
            Alpine.data('tashihApp', () => ({
                activeTab: 'saya', filterStatus: 'all',
                openSelectCourse: false, openCreate: false, openBankSoal: false,
                openDetail: false, openApprove: false, openRevise: false, openDelete: false, openSignature: false,

                courseId: '', courseName: '',
                isEditMode: false, editUuid: '',

                // STATE DEFAULT & BANK SOAL
                bankSoalList: [], searchQuery: '', isLoading: false,

                // STATE BARU BUAT BANK SOAL UNIVERSAL
                bankViewMode: 'courses', // 'courses' (lihat matkul) atau 'questions' (lihat soal)
                facultiesList: [],
                prodisList: [],
                coursesList: [],
                filterFakultas: '',
                filterProdi: '',
                selectedBankCourseName: '',
                searchCourseQuery: '', // State buat search bar Matkul

                // Variabel aman anti syntax error
                myProposals: <?php echo json_encode($myProposals ?? [], 15, 512) ?>,
                reviewQueue: <?php echo json_encode($reviewQueue ?? [], 15, 512) ?>,
                selectedProposal: null,
                userId: '<?php echo e(Auth::id() ?? 0); ?>',
                isReviewer: <?php echo e($isReviewer ? 'true' : 'false'); ?>,

                init() {
                    // 1. Bikin Radar buat ngecek URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const openModalUuid = urlParams.get('open_modal');

                    // 2. Kalau ada perintah buka modal di URL
                    if (openModalUuid) {
                        // Cari UUID-nya ada di tab mana (Reviewer atau Pengaju)
                        let foundInReview = this.reviewQueue.find(p => p.uuid === openModalUuid);
                        let foundInSaya = this.myProposals.find(p => p.uuid === openModalUuid);

                        // Kasih jeda 300ms biar halaman & animasi tab-nya kelar dulu
                        setTimeout(() => {
                            if (foundInReview) {
                                this.activeTab = 'review';
                                this.viewDetail(openModalUuid, 'review');
                            } else if (foundInSaya) {
                                this.activeTab = 'saya';
                                this.viewDetail(openModalUuid, 'saya');
                            }
                        }, 300);

                        // 3. (Opsional tapi Keren) Bersihin URL-nya biar param ?open_modal ilang
                        // Jadi kalau user nge-refresh web, modalnya ga kebuka-buka terus
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                },

                // ==========================================
                // FITUR BANK SOAL UNIVERSAL
                // ==========================================

                // 1. PANGGIL INI SAAT KLIK TOMBOL BUKA MODAL BANK SOAL
                openBankSoalModal() {
                    this.openBankSoal = true;
                    this.bankViewMode = 'courses'; // Set ke tampilan list matkul
                    this.filterFakultas = '';
                    this.filterProdi = '';
                    this.searchCourseQuery = ''; // Reset form search
                    this.searchQuery = '';

                    this.fetchFaculties();
                    this.fetchCourses(); // Tarik 20 matkul terbaru secara default
                },

                // 2. Fetch data Fakultas
                fetchFaculties() {
                    fetch("<?php echo e(url('monev-akademik/tashih/api/units')); ?>")
                        .then(res => res.json())
                        .then(data => { this.facultiesList = data; })
                        .catch(err => console.error('Gagal load fakultas', err));
                },

                // 3. Fetch data Prodi (kepanggil kalo Fakultas dipilih)
                fetchProdis() {
                    this.filterProdi = '';
                    this.prodisList = [];
                    if (!this.filterFakultas) return;

                    fetch(`<?php echo e(url('monev-akademik/tashih/api/units')); ?>?faculty_id=${this.filterFakultas}`)
                        .then(res => res.json())
                        .then(data => { this.prodisList = data; })
                        .catch(err => console.error('Gagal load prodi', err));
                },

                // 4. Fetch list Mata Kuliah berdasarkan filter & search
                fetchCourses() {
                    this.isLoading = true;
                    this.coursesList = [];

                    let url = `<?php echo e(url('monev-akademik/tashih/api/approved-courses')); ?>?`;
                    if (this.filterFakultas) url += `faculty_id=${this.filterFakultas}&`;
                    if (this.filterProdi) url += `prodi_id=${this.filterProdi}&`;
                    if (this.searchCourseQuery) url += `search=${encodeURIComponent(this.searchCourseQuery)}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.coursesList = data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Gagal load matkul', err);
                            this.isLoading = false;
                        });
                },

                // 5. Buka daftar soal pas kotak matkul di-klik
                openCourseQuestions(course) {
                    this.selectedBankCourseName = course.course_name;
                    this.bankViewMode = 'questions'; // Pindah mode ke list soal
                    this.isLoading = true;
                    this.bankSoalList = [];
                    this.searchQuery = '';

                    fetch(`<?php echo e(url('monev-akademik/tashih/api/bank-soal')); ?>/${course.id}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => {
                            this.bankSoalList = data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error('Gagal fetch soal', err);
                            this.isLoading = false;
                            if (typeof toastr !== 'undefined') toastr.error('Gagal memuat detail soal');
                        });
                },

                // 6. GETTER: FILTER SOAL LOKAL BERDASARKAN PENCARIAN
                get filteredBankSoal() {
                    if (!this.bankSoalList || this.bankSoalList.length === 0) return [];
                    if (this.searchQuery === '') return this.bankSoalList;

                    return this.bankSoalList.filter(q =>
                        q.question_text &&
                        q.question_text.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                // 7. GUNAKAN SOAL KE DALAM FORM
                useQuestion(q) {
                    if (typeof addQuestionCard === 'function') {
                        // Bawa path gambar juga ke form pas soal di-pilih!
                        addQuestionCard({
                            text: q.question_text,
                            cpmk: q.cpmk_id,
                            weight: '',
                            image_path: q.image_path || ''
                        });
                    }
                    this.openBankSoal = false;
                    this.searchQuery = '';

                    setTimeout(() => {
                        const mb = document.getElementById('modal-body-scroll');
                        if (mb) mb.scrollTo({ top: mb.scrollHeight, behavior: 'smooth' });
                    }, 100);
                },

                // ==========================================
                // FITUR UTAMA (KOMENTAR & DETAIL)
                // ==========================================

                // FUNGSI KOMENTAR
                saveComment(orderNo) {
                    const commentInput = document.getElementById('comment-' + orderNo);
                    const message = commentInput.value;

                    if (!message) return;

                    fetch("<?php echo e(route('monevakademik.tashih.comment')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            proposal_id: this.selectedProposal.id,
                            order_no: orderNo,
                            message: message
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (!this.selectedProposal.logs) this.selectedProposal.logs = [];
                                this.selectedProposal.logs.push(data.log);
                                commentInput.value = '';
                                if (typeof toastr !== 'undefined') toastr.success('Catatan berhasil disimpan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof toastr !== 'undefined') toastr.error('Gagal menyimpan catatan');
                        });
                },

                // LIHAT DETAIL
                viewDetail(uuid, source) {
                    this.selectedProposal = source === 'saya'
                        ? this.myProposals.find(p => p.uuid === uuid)
                        : this.reviewQueue.find(p => p.uuid === uuid);
                    this.openDetail = true;
                },

                // EDIT MODAL
                openEditModal() {
                    this.openDetail = false;
                    this.isEditMode = true;
                    this.editUuid = this.selectedProposal.uuid;
                    this.courseId = this.selectedProposal.course_id;
                    this.courseName = this.selectedProposal.course ? this.selectedProposal.course.course_name : '';

                    const questions = this.selectedProposal.exam_questions || this.selectedProposal.examQuestions || [];

                    let existingQuestions = questions.map(eq => ({
                        text: eq.question ? eq.question.question_text : '',
                        cpmk: eq.question ? eq.question.cpmk_id : [], // <-- Pastiin ini array defaultnya ya!
                        weight: eq.weight,
                        image_path: eq.question ? eq.question.image_path : ''
                    }));

                    this.openCreate = true;
                    setTimeout(() => {
                        if (typeof initCreateModal === 'function') {
                            initCreateModal(this.courseId, existingQuestions);
                        }
                    }, 100);
                }
            }));
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MonevAkademik\resources/views/tashih/index.blade.php ENDPATH**/ ?>