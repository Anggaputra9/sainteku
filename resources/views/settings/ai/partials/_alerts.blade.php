<template x-if="testFeedback">
    <div x-transition
        class="flex items-start w-full rounded-r-lg border-l-4 p-4 shadow-sm dark:bg-gray-800"
        :class="testFeedback.type === 'success'
            ? 'border-green-500 bg-green-50 dark:border-green-400'
            : 'border-red-500 bg-red-50 dark:border-red-400'">
        <i class="mr-3 mt-0.5 text-xl"
            :class="testFeedback.type === 'success'
                ? 'fa-solid fa-check-circle text-green-500'
                : 'fa-solid fa-circle-xmark text-red-500'"></i>
        <p class="text-sm font-bold"
            :class="testFeedback.type === 'success'
                ? 'text-green-700 dark:text-green-400'
                : 'text-red-700 dark:text-red-400'"
            x-text="testFeedback.message"></p>
    </div>
</template>

@if (session('success'))
    <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
        <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
        <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
        <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="flex items-start w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
        <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl mr-3 mt-0.5"></i>
        <div class="text-sm font-bold text-red-700 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif
