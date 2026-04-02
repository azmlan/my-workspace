<section id="contact" class="py-20 lg:py-32 bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="reveal text-3xl sm:text-4xl font-bold text-white mb-4">
                تواصل معي
            </h2>
            <p class="reveal text-gray-400 text-lg" style="transition-delay: 0.1s;">
                هل لديك مشروع؟ دعنا نعمل معاً.
            </p>
        </div>

        <div class="reveal bg-gray-800/50 rounded-xl p-6 sm:p-8 lg:p-10 border border-gray-700" style="transition-delay: 0.2s;">
            <form
                x-data="{
                    submitting: false,
                    success: false,
                    error: null,
                    errors: {},
                    form: { name: '', email: '', message: '' },
                    async submit() {
                        this.submitting = true;
                        this.error = null;
                        this.errors = {};

                        try {
                            const response = await fetch('{{ route('contact.submit') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.form)
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.success = true;
                                this.form = { name: '', email: '', message: '' };
                            } else if (response.status === 422) {
                                this.errors = data.errors || {};
                            } else if (response.status === 429) {
                                this.error = 'لقد أرسلت رسائل كثيرة، يرجى الانتظار قليلاً';
                            } else {
                                this.error = 'حدث خطأ، يرجى المحاولة مرة أخرى';
                            }
                        } catch (e) {
                            this.error = 'حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى';
                        } finally {
                            this.submitting = false;
                        }
                    }
                }"
                @submit.prevent="submit"
            >
                <!-- Success Message -->
                <div
                    x-show="success"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg"
                >
                    <p class="text-green-400 text-center">شكراً لتواصلك، سأرد عليك قريباً</p>
                </div>

                <!-- Error Message -->
                <div
                    x-show="error"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg"
                >
                    <p class="text-red-400 text-center" x-text="error"></p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            الاسم
                        </label>
                        <input
                            type="text"
                            id="name"
                            x-model="form.name"
                            required
                            class="w-full px-4 py-3 bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                            :class="errors.name ? 'border-red-500' : 'border-gray-700'"
                            placeholder="أدخل اسمك"
                        >
                        <template x-if="errors.name">
                            <p class="mt-2 text-sm text-red-400" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            البريد الإلكتروني
                        </label>
                        <input
                            type="email"
                            id="email"
                            x-model="form.email"
                            required
                            class="w-full px-4 py-3 bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                            :class="errors.email ? 'border-red-500' : 'border-gray-700'"
                            placeholder="example@email.com"
                        >
                        <template x-if="errors.email">
                            <p class="mt-2 text-sm text-red-400" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                            الرسالة
                        </label>
                        <textarea
                            id="message"
                            x-model="form.message"
                            rows="5"
                            required
                            class="w-full px-4 py-3 bg-gray-900 border rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"
                            :class="errors.message ? 'border-red-500' : 'border-gray-700'"
                            placeholder="أخبرني عن مشروعك..."
                        ></textarea>
                        <template x-if="errors.message">
                            <p class="mt-2 text-sm text-red-400" x-text="errors.message[0]"></p>
                        </template>
                    </div>

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-800 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                    >
                        <span x-show="!submitting">إرسال الرسالة</span>
                        <span x-show="submitting" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري الإرسال...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
