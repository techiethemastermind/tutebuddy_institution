@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    body {
        background-color: lightblue;
        background-image: url("{{ asset('/assets/img/backgrounds/tutebuddy-bg.jpg') }}");
        background-repeat: no-repeat;
        background-size: auto;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content login-bg">
    <div class="d-flex align-items-center vh-100 justify-content-center">
        <img src="{{ asset('/assets/img/logos/tutebuddy-logo-full.png') }}" style="width: 300px;" alt="" srcset="">
    </div>
</div>

@push('after-scripts')

@if(isset($message))

<script type="text/javascript">
    $(function() {
        swal("Warning!", "{{ $message }}", "warning");
    });
</script>

@endif

@endpush

@endsection