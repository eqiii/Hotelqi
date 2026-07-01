<x-guest-layout title="FAQ">
    <section class="bg-gray-900 text-white py-24 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Help Center</p>
        <h1 class="font-playfair text-5xl font-bold">Frequently Asked Questions</h1>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center p-6 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <h3 class="font-semibold text-gray-900">{{ $faq->question }}</h3>
                                <span class="text-amber-600 text-xl group-open:rotate-45 transition-transform">+</span>
                            </summary>
                            <div class="p-6 text-gray-600 leading-relaxed border-t border-gray-200">
                                {{ $faq->answer }}
                            </div>
                        </details>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-guest-layout>