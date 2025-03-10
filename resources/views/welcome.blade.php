@extends('layout.app')

{{-- custom untuk bagian layout --}}

@section('subtitle', 'welcome')
@section('content_header_title','Home')
@section('content_header_subtitle', 'Fajrul17')

{{-- bagian body --}}
@section('content_body')
<p>Selamat Datang di website mrX17</p>
@stop

{{-- Push exra Css --}}

@push('css')
    {{-- Add here extra stlesheet  --}}
    {{-- <link rel = "stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push exra scripts --}}

@push('js')
    <script> console.log ("Hi, I'm using the laravel-adminLte Package!");</script>
@endpush