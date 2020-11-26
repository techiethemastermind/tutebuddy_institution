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
            @if(!empty($user->avatar))
            <img src="{{ asset('/storage/avatars/' . $user->avatar) }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @else
            <img src="{{ asset('/assets/img/avatars/no-avatar.jpg') }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @endif
            <div class="flex mb-32pt mb-md-0">
                <h2 class="text-white mb-0">{{ $user->name }}</h2>
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
                                <span class="flex form-label"><strong>User ID verified</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>User Profile verified</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-5">
                            <h4>{{ $user->name }}</h4>
                            <div class="form-group">
                                <label class="form-label">User Email: </label>
                                <p class="font-size-24pt">{{ $user->email }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Institution: </label>
                                <p class="font-size-24pt">{{ $user->institution->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection