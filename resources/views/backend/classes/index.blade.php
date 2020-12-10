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
                    <h2 class="mb-0">Class Management</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Class Management
                        </li>

                    </ol>

                </div>
            </div>

            @can('class_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.classes.create') }}" class="btn btn-outline-secondary">New Class</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Classes</div>
        </div>
        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists">
                <table id="tbl_classes" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='10'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> Class Name </th>
                            <th> Number of Courses </th>
                            <th> Number of Divisions </th>
                            <th> Number of Students </th>
                            <th> Actions </th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(function() {
        var table = $('#tbl_classes').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: "{{ route('admin.ajax.getClassesTableData') }}",
                columns: [
                    { data: 'index'},
                    { data: 'name'},
                    { data: 'subjects'},
                    { data: 'divisions'},
                    { data: 'students'},
                    { data: 'action' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no Classes"
                }
            }
        );

        $(document).on('submit', 'form[name="delete_item"]', function(e) {

            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                    } else {
                        swal("Warning!", res.message, "warning");
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection