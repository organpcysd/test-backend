<meta property="og:url"           content="{{url()->current()}}" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="@yield('ogtitle', setting('title'))" />
<meta property="og:description"   content="@yield('ogdesc', setting('seo_description'))" />
<meta property="og:image"         content="@yield('ogimg', asset(setting('img_og')))" />
