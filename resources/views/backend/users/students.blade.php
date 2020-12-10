@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Teachers</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Teachers
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
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="#" class="btn btn-outline-secondary">Upload by CSV</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="#" class="btn btn-outline-secondary">Download CSV Template</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>

                <table id="tbl_users" class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">No.</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a></th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(function () {
        
        var table = $('#tbl_users').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: "{{ route('admin.users.studentsByAjax') }}",
                columns: [
                    { data: 'index'},
                    { data: 'no' },
                    { data: 'name' },
                    { data: 'email'},
                    { data: 'class' },
                    { data: 'status' },
                    { data: 'actions' }
                ],
                oLanguage: {
                    sEmptyTable: "No Teachers added"
                }
            }
        );
    });
</script>

@endpush