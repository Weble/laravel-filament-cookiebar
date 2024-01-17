<div>
    @if($showCookieBar)
        <div class="fixed bottom-0 w-full py-4 shadow-top bg-black text-white z-50">
            <div class="container mx-auto flex flex-col items-center justify-center text-center lg:text-left space-y-4">
        <span class="cookiebar__message">
           {!! __('cookiebar::banner.message', ['href' => config('cookiebar.terms_url', '#')]) !!}
        </span>

                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="flex justify-center items-center space-x-4">
                        {{ $this->showCookieModalAction }}

                        {{ $this->agreeAction }}
                    </div>

                    {{ $this->dismissAction }}
                </div>
            </div>
        </div>
    @endif

    <x-filament-actions::modals/>
</div>
