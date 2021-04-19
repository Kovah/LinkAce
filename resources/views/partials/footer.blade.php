<aside class="footer container text-center small pt-3 pb-5">
    <div>
        @lang('linkace.project_of') <a href="https://kovah.de/?utm_source=linkace" rel="noopener" target="_blank">Kovah.de</a>
    </div>
    @auth
        <div class="mt-1">
            @lang('linkace.version', ['version' => \App\Helper\UpdateHelper::currentVersion()]) -
            <x-update-check class="d-inline-block"/>
        </div>
    @endauth
</aside>
