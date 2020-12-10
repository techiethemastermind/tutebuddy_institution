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

                    @if($user->hasRole('Teacher'))
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Subjects offered by {{ $user->name }}</label>
                                    <div class="mt-8pt">
                                        @foreach($user->courses as $course)
                                        <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $course->title }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Professions</label>
                                    @if(!empty($user->profession))
                                    <div class="mt-8pt">
                                        @php $pros = json_decode($user->profession); @endphp
                                
                                        @foreach($pros as $pro)
                                        <?php
                                            $category = App\Models\Category::find($pro);
                                            $name = !empty($category) ? $category->name : $pro;
                                        ?>
                                        <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $name }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
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

                    @if($user->hasRole('Teacher'))
                        <div class="card">
                            <div class="card-body p-5">
                                <h4>{{ $user->headline }}</h4>
                                <p class="font-size-16pt">{{ $user->about }}</p>
                            </div>
                        </div>

                        @if(!empty($user->qualifications))
                            <div class="card">
                                <div class="card-body p-5">
                                    <h4>Professional Qualifications and Certifications</h4>
                                    @foreach(json_decode($user->qualifications) as $qualification)
                                    <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $qualification }}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($user->qualifications))
                            <div class="card">
                                <div class="card-body p-5">
                                    <h4>Achievements</h4>
                                    @foreach(json_decode($user->achievements) as $achievement)
                                    <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $achievement }}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($user->experience))
                            <div class="card">
                                <div class="card-body p-5">
                                    <h4>Experience</h4>
                                    <p class="font-size-16pt mb-1">{{ $user->experience }}</p>
                                </div>
                            </div>
                        @endif

                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection