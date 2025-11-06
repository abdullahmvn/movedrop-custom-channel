<x-layouts.app :title="__('Product Details')">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                            {{ __('Products') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ __('Product Details') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Main Product Layout -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">

                    <!-- Left Side: Product Images -->
                    <div class="space-y-4">
                        <!-- Main Image Container -->
                        <div class="relative w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            @foreach($product->media as $index => $media)
                                <img
                                    id="slide-{{ $index }}"
                                    src="{{ $media->url }}"
                                    alt="{{ $product->title }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                            @endforeach

                            <!-- Navigation Buttons -->
                            <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <!-- Slide Counter -->
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                <span id="current-slide">1</span> / {{ $product->media->count() }}
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->media as $index => $media)
                                <button
                                    onclick="showSlide({{ $index }})"
                                    class="w-full h-20 bg-gray-200 dark:bg-gray-700 rounded-md overflow-hidden hover:opacity-75 transition {{ $index === 0 ? 'ring-2 ring-indigo-500' : '' }}"
                                    id="thumb-{{ $index }}">
                                    <img src="{{ $media->url }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right Side: Product Information -->
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->title }}</h1>
                        </div>

                        <!-- SKU -->
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('SKU') }}:</span>
                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-white">{{ $product->sku }}</span>
                        </div>

                        <!-- Categories -->
                        @if($product->categories->count() > 0)
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Categories') }}:</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($product->categories as $category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $category->name ?? 'Category #' . $category->id }}
                                </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Tags -->
                        @if($product->tags)
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Tags') }}:</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($product->tags as $tag)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $tag }}
                                </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Meta Information -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $product->updated_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Variations') }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $product->variations->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($product->description)
                <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Description') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="prose prose-sm max-w-none text-gray-900 dark:text-white dark:prose-invert">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Properties -->
            @if($product->properties->count() > 0)
                <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Product Properties') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($product->properties as $property)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3">{{ $property->name }}</h4>
                                    @if($property->propertyValues->count() > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($property->propertyValues as $value)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $value->value }}
                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Variations -->
            @if($product->variations->count() > 0)
                <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Product Variations') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($product->variations as $variation)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <div class="flex flex-col lg:flex-row gap-6">

                                        <!-- Variation Image -->
                                        <div class="flex-shrink-0">
                                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                <img src="{{ $variation->image }}" alt="Variation {{ $variation->sku }}" style="height: 300px" class="w-full object-center object-cover">
                                            </div>
                                        </div>

                                        <!-- Variation Details -->
                                        <div class="flex-1 space-y-4">
                                            <!-- Variation Info -->
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('SKU') }}:</span>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $variation->sku }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Regular Price') }}:</span>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                        ${{ number_format($variation->regular_price, 2) }}</p>
                                                </div>
                                                @if($variation->sale_price)
                                                    <div>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Sale Price') }}:</span>
                                                        <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                                            ${{ number_format($variation->sale_price, 2) }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Stock -->
                                            <div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Stock Quantity') }}:</span>
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $variation->stock_quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $variation->stock_quantity }}
                                        </span>
                                            </div>

                                            <!-- Sale Dates -->
                                            @if($variation->date_on_sale_from || $variation->date_on_sale_to)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    @if($variation->date_on_sale_from)
                                                        <div>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Sale From') }}:</span>
                                                            <p class="text-sm text-gray-900 dark:text-white">{{ $variation->date_on_sale_from }}</p>
                                                        </div>
                                                    @endif
                                                    @if($variation->date_on_sale_to)
                                                        <div>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Sale To') }}:</span>
                                                            <p class="text-sm text-gray-900 dark:text-white">{{ $variation->date_on_sale_to }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Variation Properties -->
                                            @if($variation->properties->count() > 0)
                                                <div>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 mb-2 block">{{ __('Properties') }}:</span>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($variation->properties as $variationProperty)
                                                            <span class="inline-flex items-center px-3 py-1 rounded-md text-sm bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $variationProperty->property->name }}: {{ $variationProperty->propertyValue->value ?? 'Value' }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Simple Slideshow JavaScript -->
    <script>
        let currentSlide = 0;
        const totalSlides = {{ $product->media->count() }};

        function showSlide(index) {
            // Hide all slides
            for (let i = 0; i < totalSlides; i++) {
                document.getElementById('slide-' + i).classList.remove('opacity-100');
                document.getElementById('slide-' + i).classList.add('opacity-0');
                document.getElementById('thumb-' + i).classList.remove('ring-2', 'ring-indigo-500');
            }

            // Show current slide
            document.getElementById('slide-' + index).classList.remove('opacity-0');
            document.getElementById('slide-' + index).classList.add('opacity-100');
            document.getElementById('thumb-' + index).classList.add('ring-2', 'ring-indigo-500');

            // Update counter
            document.getElementById('current-slide').textContent = index + 1;
            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % totalSlides;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(prev);
        }
    </script>
</x-layouts.app>
