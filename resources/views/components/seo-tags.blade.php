@php
    // Determine the current page's SEO metadata
    $seoTitle = trim(strip_tags(View::hasSection('title') ? trim(View::getSection('title')) : $settings?->seo_title));
    $seoDescription = trim(strip_tags(View::hasSection('meta_description') ? trim(View::getSection('meta_description')) : $settings?->seo_description));
    $seoKeywords = trim(strip_tags(View::hasSection('meta_keywords') ? trim(View::getSection('meta_keywords')) : $settings?->seo_keywords));
    $seoImage = View::hasSection('meta_image') ? trim(View::getSection('meta_image')) : asset($settings?->seo_banner_image);
@endphp

<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="robots" content="index, follow">

<!-- Social Media (Open Graph) Meta Tags -->
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">{!! View::hasSection('additional_meta_tags') ? View::getSection('additional_meta_tags') : '' !!}