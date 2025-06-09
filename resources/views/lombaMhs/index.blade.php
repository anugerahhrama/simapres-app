@extends('layouts.app')

@section('content')
    @livewire('lomba-list', ['tingkatanLomba' => $tingkatanLomba])
@endsection

@push('css')
    @livewireStyles
@endpush

@push('js')
    @livewireScripts
@endpush
