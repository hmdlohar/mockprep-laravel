<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100 text-slate-900 select-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'CBT Exam Session' }} - CAT/CMAT Emulation</title>
    @include('components.theme-head')
    @livewireStyles
</head>
<body class="h-full flex flex-col antialiased overflow-hidden bg-slate-100 text-slate-900">
    {{ $slot }}
    @livewireScripts
</body>
</html>
