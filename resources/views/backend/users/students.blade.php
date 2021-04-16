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
                    <h2 class="mb-0">Students</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Students
                        </li>
                    </ol>
                </div>
            </div>

            @can('user_create')
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.create', ['t'=>'student']) }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#csvUploadModal">Upload by CSV</a>
                </div>
            </div>
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ asset('/assets/files/students.csv') }}" class="btn btn-outline-secondary">Download CSV Template</a>
                </div>
            </div>

            @endcan
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
                            <th>Division</th>
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

<!-- Modal Form -->
<div class="modal fade" id="csvUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Import CSV file</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="POST" action="{{ route('admin.users.import.csv', 'student') }}"
                    enctype="multipart/form-data">{{ csrf_field() }}
                <div class="modal-body mx-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bootstrap-touchspin-down" id="importCsvFilelbl">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="csv_file" class="custom-file-input" id="importCsvFile"
                                aria-describedby="importCsvFilelbl" accept=".xlsx, .xls, .csv">
                            <label class="custom-file-label" for="importCsvFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-right">
                        <button type="button" id="btn_csv_import" class="btn btn-primary">Import</button>
                    </div>
                </div>
            </form>
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
                    { data: 'division' },
                    { data: 'status' },
                    { data: 'actions' }
                ],
                oLanguage: {
                    sEmptyTable: "No Teachers added"
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

        $('#btn_csv_import').on('click', function(e) {
            
            e.preventDefault();

            $('#csvUploadModal form').ajaxSubmit({
                success: function(res) {
                    $('#csvUploadModal').modal('toggle');
                    if(res.success) {
                        table.ajax.reload();
                    } else {
                        swal("Error!", res.message, "error");
                    }
                }
            });
        });
    });
</script>

@endpush