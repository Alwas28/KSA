@if(session('success') || session('error') || session('warning') || session('info'))
<div id="toast-container" class="fixed top-20 right-6 z-[9999] space-y-3">
    @if(session('success'))
    <div class="toast-notification bg-white border-l-4 border-green-500 rounded-lg shadow-2xl p-4 flex items-start gap-3 transform transition-all duration-500 animate-slide-in-right max-w-md">
        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-900 text-sm">Berhasil!</h4>
            <p class="text-gray-600 text-sm mt-1">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="toast-notification bg-white border-l-4 border-red-500 rounded-lg shadow-2xl p-4 flex items-start gap-3 transform transition-all duration-500 animate-slide-in-right max-w-md">
        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-900 text-sm">Error!</h4>
            <p class="text-gray-600 text-sm mt-1">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="toast-notification bg-white border-l-4 border-yellow-500 rounded-lg shadow-2xl p-4 flex items-start gap-3 transform transition-all duration-500 animate-slide-in-right max-w-md">
        <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-900 text-sm">Peringatan!</h4>
            <p class="text-gray-600 text-sm mt-1">{{ session('warning') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="toast-notification bg-white border-l-4 border-blue-500 rounded-lg shadow-2xl p-4 flex items-start gap-3 transform transition-all duration-500 animate-slide-in-right max-w-md">
        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-900 text-sm">Info</h4>
            <p class="text-gray-600 text-sm mt-1">{{ session('info') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
</div>

<style>
@keyframes slide-in-right {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out-right {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.5s ease-out;
}

.animate-slide-out-right {
    animation: slide-out-right 0.5s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast-notification');

    toasts.forEach(toast => {
        setTimeout(() => {
            toast.classList.add('animate-slide-out-right');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endif
