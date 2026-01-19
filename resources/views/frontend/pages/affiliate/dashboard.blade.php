@extends('frontend.layouts.user')
@section('title', 'Dashboard')
@section('content')
    <div class="p-6 space-y-6">
        <div class="rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-[1px]">
            <div class="rounded-xl bg-white/95 backdrop-blur px-5 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Affiliate Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600">Grow your earnings by sharing your unique referral link.</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M13.5 6a3.75 3.75 0 00-3.53 2.57.75.75 0 11-1.44-.46A5.25 5.25 0 1118.75 12H18a.75.75 0 010-1.5h.75A3.75 3.75 0 0013.5 6z"/><path d="M6 12.75A3.75 3.75 0 019.75 9H10a.75.75 0 000-1.5h-.25a5.25 5.25 0 100 10.5H10A.75.75 0 0010 16h-.25A3.75 3.75 0 016 12.75z"/><path d="M8.25 12A.75.75 0 019 11.25h6a.75.75 0 010 1.5H9A.75.75 0 018.25 12z"/></svg>
                        Share & Earn
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="col-span-1 md:col-span-2">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Your referral link</label>
                    <div class="flex items-stretch gap-2">
                        <input type="text" readonly value="{{ $link }}" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-indigo-400 focus:outline-none">
                        <button type="button" id="copyLinkBtn" data-link="{{ $link }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 active:bg-indigo-800">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M8.25 7.5A2.25 2.25 0 0110.5 5.25h6a2.25 2.25 0 012.25 2.25v6a2.25 2.25 0 01-2.25 2.25h-6A2.25 2.25 0 018.25 13.5v-6z"/><path d="M5.25 8.25A2.25 2.25 0 013 10.5v6A2.25 2.25 0 005.25 18.75h6a2.25 2.25 0 002.25-2.25V15H10.5A2.25 2.25 0 018.25 12.75V8.25H5.25z"/></svg>
                            <span id="copyLinkText">Copy</span>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Share this link. When someone purchases through it, you earn commission.</p>
                </div>
            </div>

            <div class="col-span-1">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Commission rate</span>
                        <span class="rounded-md bg-green-50 px-2 py-1 text-xs font-semibold text-green-700">Active</span>
                    </div>
                    <div class="mt-3 flex items-end gap-2">
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($settings->affiliate_commission_percent, 2) }}</span>
                        <span class="pb-1 text-sm font-semibold text-gray-500">%</span>
                    </div>
                    <div class="mt-4 rounded-lg bg-gray-50 p-3">
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-indigo-600"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-2.59a.75.75 0 10-1.22-.88l-2.91 4.02-1.26-1.26a.75.75 0 10-1.06 1.06l1.875 1.875a.75.75 0 001.14-.094l3.435-4.72z" clip-rule="evenodd"/></svg>
                            Earn commission on every successful order via your link.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="col-span-1">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <span class="text-sm font-medium text-gray-600">Your affiliate code</span>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="rounded-lg bg-gray-100 px-3 py-2 font-mono text-sm text-gray-800">{{ $user->affiliate_code }}</span>
                        <button type="button" id="copyCodeBtn" data-code="{{ $user->affiliate_code }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                            Copy code
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-2">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Quick share</span>
                        <span class="text-xs text-gray-500">Spread the word</span>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <a target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($link) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1877F2] px-3 py-2 text-xs font-semibold text-white hover:opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M13.5 9.75H15V7.5h-1.5a3 3 0 00-3 3V12H9v2.25h1.5V21h2.25v-6.75H15L15.375 12H12v-1.5a.75.75 0 01.75-.75z"/></svg>
                            Facebook
                        </a>
                        <a target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url={{ urlencode($link) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#1DA1F2] px-3 py-2 text-xs font-semibold text-white hover:opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.177 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743A11.651 11.651 0 013.149 4.67a4.106 4.106 0 001.27 5.477A4.073 4.073 0 012.8 9.713v.052a4.106 4.106 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                            Twitter
                        </a>
                        <a target="_blank" rel="noopener" href="https://api.whatsapp.com/send?text={{ urlencode($link) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-3 py-2 text-xs font-semibold text-white hover:opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M20.52 3.48A11.94 11.94 0 0012.07 0C5.62 0 .37 5.25.37 11.7c0 2.06.55 4.08 1.6 5.86L0 24l6.62-1.92a11.7 11.7 0 005.39 1.36h.01c6.45 0 11.7-5.25 11.7-11.7 0-3.13-1.22-6.08-3.2-8.26zM12.01 21.2h-.01a9.5 9.5 0 01-4.85-1.32l-.35-.21-3.93 1.14 1.12-3.83-.23-.39a9.48 9.48 0 01-1.45-5.1c0-5.25 4.27-9.52 9.52-9.52 2.54 0 4.93.99 6.72 2.79a9.45 9.45 0 012.78 6.74c0 5.25-4.27 9.52-9.52 9.52zm5.49-7.14c-.3-.15-1.78-.88-2.06-.98-.28-.1-.48-.15-.69.15-.2.3-.79.98-.96 1.18-.18.2-.35.23-.66.08-.3-.15-1.26-.46-2.4-1.48-.89-.8-1.49-1.78-1.66-2.08-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.69-1.65-.94-2.27-.25-.6-.5-.52-.69-.53h-.58c-.2 0-.53.07-.81.38-.28.3-1.07 1.05-1.07 2.56 0 1.5 1.1 2.96 1.25 3.17.15.2 2.16 3.3 5.23 4.62.73.32 1.3.5 1.75.64.74.24 1.41.2 1.94.12.59-.09 1.78-.73 2.03-1.44.25-.71.25-1.31.18-1.44-.08-.13-.28-.2-.58-.35z"/></svg>
                            WhatsApp
                        </a>
                        <button type="button" id="shareNativeBtn" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M7.5 12a4.5 4.5 0 018.62-1.5h1.13a5.999 5.999 0 10.006 3h-1.136A4.5 4.5 0 017.5 12z"/></svg>
                            Share
                </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const copyLinkBtn = document.getElementById('copyLinkBtn');
                const copyLinkText = document.getElementById('copyLinkText');
                const copyCodeBtn = document.getElementById('copyCodeBtn');
                const shareNativeBtn = document.getElementById('shareNativeBtn');

                if (copyLinkBtn) {
                    copyLinkBtn.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(copyLinkBtn.getAttribute('data-link'));
                            if (copyLinkText) {
                                const previous = copyLinkText.textContent;
                                copyLinkText.textContent = 'Copied!';
                                setTimeout(() => copyLinkText.textContent = previous, 1200);
                            }
                        } catch (e) {}
                    });
                }

                if (copyCodeBtn) {
                    copyCodeBtn.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(copyCodeBtn.getAttribute('data-code'));
                            const previous = copyCodeBtn.textContent;
                            copyCodeBtn.textContent = 'Copied!';
                            setTimeout(() => copyCodeBtn.textContent = previous, 1200);
                        } catch (e) {}
                    });
                }

                if (shareNativeBtn && navigator.share) {
                    shareNativeBtn.addEventListener('click', async () => {
                        try {
                            await navigator.share({
                                title: 'Check this out',
                                text: 'Shop with my referral link:',
                                url: '{{ $link }}',
                            });
                        } catch (e) {}
                    });
                }
            })();
        </script>
    </div>
@endsection
