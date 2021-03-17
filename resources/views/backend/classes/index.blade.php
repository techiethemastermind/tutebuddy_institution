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
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#classCreateModal">New Class</a>
                </div>
            </div>
            @endcan

            @if($classes_count == 0)
            <div class="row" role="tablist">
                <div class="col-auto ml-2">
                    <a href="{{ route('admin.classes.generate') }}" class="btn btn-outline-secondary">Generate Classes</a>
                </div>
            </div>
            @endif
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

<!-- Modal for Class create -->
<div class="modal fade" id="classCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Class: <span class="course-title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="frm_class" method="POST" action="{{ route('admin.ajax.createClass') }}">@csrf
                    <div class="form-group mb-3">
                        <label for="class_name" class="form-label">Class Name:</label>
                        <input type="text" name="name" class="form-control" placeholder="Grade.." tute-no-empty>
                    </div>
                    <div class="form-group mb-3">
                        <label for="class_name" class="form-label">Class Order:</label>
                        <input type="number" name="value" min="1" class="form-control" placeholder="10" tute-no-empty>
                    </div>
                    <div class="form-group mb-3">
                        <label for="class_name" class="form-label">Class Type:</label>
                        <select name="class_type" name="type" class="form-control">
                            <option value="grade">Grade</option>
                            <option value="college">College</option>
                            <option value="graduation">Graduation</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_create" class="btn btn-outline-primary btn-create">Create</button>
                </div>
            </div>
        </div>
    </div>
</div>

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

        $('#btn_create').on('click', function(e) {

            var form = $('#frm_class');

            if(!isValidForm(form)) {
                return false;
            }

            form.ajaxSubmit({
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        swal({
                            title: "Successfully Created",
                            text: "New Class is created",
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel',
                            dangerMode: false,

                        }, function(val) {
                            if (val) {
                                window.location.reload();
                            }
                        });
                    } else {
                        swal('Error!', 'Something error happend, Please try again!', 'error');
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection