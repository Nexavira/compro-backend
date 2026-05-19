<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page['title'] }} - Frontend Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow p-4 mb-8">
        <div class="max-w-4xl mx-auto font-bold text-xl text-blue-600">
            [Logo Tenant] Website Klien
        </div>
    </nav>

    <main class="max-w-4xl mx-auto bg-white shadow-sm min-h-screen">
        
        {{-- ✨ LOOPING KEAJAIBAN FILAMENT BUILDER ✨ --}}
        @foreach($page['blocks'] as $block)

            {{-- JIKA BLOK = HERO SECTION --}}
            @if($block['type'] === 'hero_section')
                <div class="bg-blue-900 text-white p-12 text-center rounded-t-lg">
                    <h1 class="text-4xl font-extrabold mb-4">{{ $block['data']['heading'] }}</h1>
                    <p class="text-lg text-blue-200 mb-6">{{ $block['data']['subheading'] }}</p>
                    
                    @if(!empty($block['data']['button_text']))
                        <a href="{{ $block['data']['button_link'] }}" class="bg-white text-blue-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100">
                            {{ $block['data']['button_text'] }}
                        </a>
                    @endif
                </div>
            @endif

            {{-- JIKA BLOK = RICH TEXT --}}
            @if($block['type'] === 'rich_text')
                <div class="p-8 prose max-w-none border-b">
                    {!! $block['data']['content'] !!}
                </div>
            @endif

            {{-- JIKA BLOK = FAQ SECTION --}}
            @if($block['type'] === 'faq_section')
                <div class="p-8 bg-gray-50">
                    <h2 class="text-2xl font-bold mb-6 text-center">{{ $block['data']['section_title'] }}</h2>
                    <div class="space-y-4">
                        @foreach($block['data']['questions'] as $faq)
                            <div class="bg-white p-4 rounded shadow-sm border border-gray-100">
                                <h3 class="font-bold text-lg text-gray-800">Q: {{ $faq['question'] }}</h3>
                                <p class="text-gray-600 mt-2">A: {{ $faq['answer'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endforeach

    </main>

</body>
</html>