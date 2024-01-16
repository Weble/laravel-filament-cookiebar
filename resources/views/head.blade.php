@if (config('cookiebar.enabled') && $consents)
    <script>
        window.dataLayer = window.dataLayer || [];
        if (typeof gtag === 'undefined') {function gtag() {dataLayer.push(arguments);} window.gtag = window.gtag || gtag;}
        gtag('consent', 'default', {!! json_encode($consents) !!})
    </script>
@endif

@include('googletagmanager::head')
