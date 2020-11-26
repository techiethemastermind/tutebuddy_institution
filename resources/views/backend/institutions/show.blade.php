@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    [dir=ltr] .chip {
        margin-bottom: .5rem;
    }
    [dir=ltr] .chip+.chip {
        margin-right: .5rem;
        margin-left: 0;
    }
</style>

@endpush


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="page-section bg-primary">
        <div class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">
            @if(!empty($institution->logo))
            <img src="{{ asset('/storage/logos/' . $institution->logo) }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @else
            <img src="{{ asset('/assets/img/logos/no-image.jpg') }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @endif
            <div class="flex mb-32pt mb-md-0">
                <h2 class="text-white mb-0">{{ $institution->name }}</h2>
                <p class="lead text-white-50 d-flex align-items-center">
                    
                </p>
            </div>
            <a href="" class="btn btn-outline-white">Follow</a>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>Verified Institution</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>Institution Profile verified</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="form-group">
                                <label class="form-label">Institution Name: </label>
                                <p class="font-size-24pt">{{ $institution->name }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Institution Tag: </label>
                                <p class="font-size-24pt">{{ $institution->prefix }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address: </label>
                                <p class="font-size-24pt">{{ $institution->email }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number: </label>
                                <p class="font-size-24pt">{{ $institution->phone_number }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Location: </label>
                                <p class="font-size-24pt">{{ $institution->address }}, {{ $institution->zip }}, {{ $institution->city }}, {{ $institution->state }}, {{ $institution->country }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Timezone: </label>
                                <p class="font-size-24pt">{{ $institution->timezone }}</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</div>

@endsection