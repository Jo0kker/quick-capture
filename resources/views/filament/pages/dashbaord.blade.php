<x-filament-panels::page>

    {{--   show url of chrome extension chrome     --}}
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col space-y-2">
{{--      lien vers l'extension chrome a telecharger--}}
            <h1 class="text-2xl font-semibold">Chrome extension</h1>
            <p class="text-gray-500">Download the chrome extension to use the dashboard.</p>
            <a target="_blank" href={{ $chromeExtensionUrl }}
                class="btn-download">
                 Download
            </a>
        </div>
    </div>

</x-filament-panels::page>
