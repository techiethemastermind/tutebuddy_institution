@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .avatar-xxl.avatar-4by3 {
        width: 12rem;
        height: 6rem;
    }
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Timetables</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Timetables
                        </li>

                    </ol>

                </div>
            </div>

            @can('timetable_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Class Timetables</div>
        </div>

        <div class="card">
        	<div class="table-responsive" data-toggle="lists">
	            <table id="tbl_results" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='10'>
	                <thead>
	                    <tr>
	                        <th style="width: 18px;" class="pr-0"></th>
                            <th> Timetable Preview </th>
	                        <th> Class </th>
                            <th> Divisions </th>
	                        <th> Data Type </th>
	                        <th> Actions </th>
	                    </tr>
	                </thead>
	                <tbody class="list" id="toggle"></tbody>
	            </table>
	        </div>
        </div>
    </div>

</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(function() {

    var table = $('#tbl_results').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: "{{ route('admin.ajax.getClassTimetableByAjax') }}",
            columns: [
                { data: 'index'},
                { data: 'preview'},
                { data: 'class'},
                { data: 'divisions'},
                { data: 'type' },
                { data: 'actions' }
            ],
            oLanguage: {
                sEmptyTable: "@lang('labels.backend.pre_enrolled.no_result')"
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