@props(['id', 'title' => 'Confirmation', 'defaultMessage' => 'Êtes-vous sûr de vouloir effectuer cette action ?', 'confirmText' => 'Confirmer', 'cancelText' => 'Annuler', 'confirmClass' => 'bg-red-600 hover:bg-red-700'])

<div x-data="{ show: false, form: null, message: '{{ $defaultMessage }}' }" 
     x-on:open-confirmation.window="show = true; form = $event.detail.form; message = $event.detail.message || '{{ $defaultMessage }}'"
     x-on:close-confirmation.window="show = false"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0"
             style="background-color: rgba(0, 0, 0, 0.75); z-index: 40;"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="show = false"></div>

        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             style="z-index: 50;"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-12 sm:w-12">
                        <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-3">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 leading-relaxed" x-text="message">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row justify-end gap-3">
                <button type="button" 
                        @click="show = false"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 shadow-sm px-4 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm transition ease-in-out duration-150 min-w-[120px] h-[44px]">
                    <i class="fas fa-times mr-2"></i>
                    {{ $cancelText }}
                </button>
                <button type="button" 
                        @click="if(form) form.submit(); show = false;"
                        class="inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-4 {{ $confirmClass }} text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm transition ease-in-out duration-150 min-w-[120px] h-[44px]">
                    <i class="fas fa-check mr-2"></i>
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

