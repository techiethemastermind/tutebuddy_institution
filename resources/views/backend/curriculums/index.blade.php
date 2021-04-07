@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">Curriculums Management</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Curriculums Management
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Curriculums</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists">
                <table id="tbl_curriculums" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='10'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>Subject</th>
                            <th>Classes</th>
                            <th>Divisions</th>
                            <th>Teachers</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Curriculum Edit Modal -->
@foreach($subjects as $subject)
<div class="modal fade" id="modal_curriculum_{{ $subject->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Curriculum</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'PATCH', 'route' => ['admin.curriculums.update', $subject->id]]) !!}

                <div class="row">
                    <div class="col-12 mb-3">
                        <!-- <div class="form-group">
                            <label class="form-label">Curriculum Name:</label>
                            <input type="text" name="curriculum_name" class="form-control" value="" tute-no-empty>
                        </div> -->

                        <div class="form-group">
                            <label class="form-label">Grades:</label>
                            {!! Form::select('grade', $grades, $subject->grade->id, 
                            array('class' => 'form-control', 'data-toggle'=>'select')) !!}
                        </div>

                        <div class="form-group">
                            <label class="form-label">Teachers:</label>
                            {!! Form::select('teachers[]', $teachers, $subject->teachers, 
                            array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                        </div>
                    </div>
                </div>

                <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_curriculum" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script type="text/javascript">

	$(function() {

        var table = $('#tbl_curriculums').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: "{{ route('admin.ajax.getCurriculumsTableData') }}",
                columns: [
                    { data: 'index'},
                    { data: 'subject'},
                    { data: 'classes'},
                    { data: 'divisions' },
                    { data: 'teachers'},
                    { data: 'actions'}
                ],
                oLanguage: {
                    sEmptyTable: "You have no Classes"
                }
            }
        );

		$('div.modal').on('click', 'button.save_curriculum', function(e) {

            var form = $(this).closest('div.modal').find('form');

            if(!isValidForm(form)) {
                return false;
            }

            form.ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                    }
                }
            });
        });

        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
	});
</script>

@endpush

@endsection