@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">User Management</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            User Management
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Go To Home</a>
                </div>
            </div>

            @can('user_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>

                <table class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">No.</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a></th>
                            <th>Email</th>
                            <th>Institution</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="clients">
                    @foreach ($users as $user)
                    <tr>
                        <td class="pr-0"></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    @if($user->avatar)
                                    <img src="{{asset('/storage/avatars/' . $user->avatar )}}" alt="Avatar" class="avatar-img rounded-circle">
                                    @else
                                    <span class="avatar-title rounded-circle">{{ substr($user->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">{{ $user->name }}</strong></p>
                                            <small class="js-lists-values-email text-50">
                                            @if(!empty($user->getRoleNames()))
                                                {{ $user->getRoleNames()->first() }}
                                            @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->institution->name }}</td>
                        <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            <label class="badge badge-primary">{{ $v }}</label>
                            @endforeach
                        @endif
                        </td>
                        <td>
                            @include('layouts.buttons.show', ['show_route' => route('admin.users.show', $user->id)])
                            @include('layouts.buttons.edit', ['edit_route' => route('admin.users.edit', $user->id)])
                            @include('layouts.buttons.delete', ['delete_route' => route('admin.users.destroy', $user->id)])
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer p-8pt">
                @if($users->hasPages())
                {{ $users->links('layouts.parts.page') }}
                @else
                <ul class="pagination justify-content-start pagination-xsm m-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true" class="material-icons">chevron_left</span>
                            <span>Prev</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Page 1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span>Next</span>
                            <span aria-hidden="true" class="material-icons">chevron_right</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<!-- List.js -->
<script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/list.js') }}"></script>

<!-- Tables -->
<script src="{{ asset('assets/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('assets/js/check-selected-row.js') }}"></script>

@endpush