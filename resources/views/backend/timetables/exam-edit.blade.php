@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Nestable CSS -->
<link type="text/css" href="{{ asset('assets/css/nestable.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .preview-wrap {
        height: 215px;
    }
    [dir=ltr] .preview-wrap * {
        height: 100%;
        object-fit: cover;
        object-position: top;
    }
    [dir=ltr] .move-handle {
        cursor: pointer;
    }

    [dir=ltr] .modal-content.full-view embed {
        height: 80vh;
    }

    [dir=ltr] .modal-content.full-view img {
        height: 80vh;
    }
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Timetable for {{ $class->name }}</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Edit Timetable
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.timetables.exam') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">

        <div class="row">
            <div class="col-md-9">

                @if(count($class->examTimeTables()) < 1)
                <div class="page-separator">
                    <div class="page-separator__text">Add New</div>
                </div>
                @endif
                
                @foreach($class->examTimeTables() as $timetable)
                <div class="page-separator">
                    <div class="page-separator__text">{{ $timetable->name }}</div>
                </div>

                <div class="card card-body">

                {!! Form::model($class, ['method'=>'POST', 'files' => true, 'route'=>['admin.timetables.exam.update', $timetable->id]]) !!}
                    
                    <div class="form-group">
                        <div class="media">
                            <div class="media-left mr-32pt">

                                <div class="preview-wrap mb-16pt" id="file_preview_{{ $timetable->id }}">
                                    @if($timetable->type != 'pdf')
                                    <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                                        alt="people" width="300" class="rounded" />

                                    <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                                        alt="people" width="300" class="rounded" style="display:none;" />

                                    @else
                                    <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                                        alt="people" width="300" class="rounded" style="display:none;" />

                                    <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                                        alt="people" width="300" class="rounded" />
                                    @endif
                                </div>
                                <small class="text-muted">{{ $timetable->url }}</small>
                            </div>
                            
                            <div class="media-body">

                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $timetable->name }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Upload new Timetable Image or PDF file</label>
                                    <div class="custom-file">
                                        <input type="file" name="file_timetable" class="custom-file-input" id="avatar_file_{{ $timetable->id }}"
                                            file-preview="#file_preview_{{ $timetable->id }}" accept=".gif,.jpg,.jpeg,.png,.pdf">
                                        <label class="custom-file-label" for="avatar_file_{{ $timetable->id }}">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">PFD or image files</small>
                                </div>

                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-accent" data-toggle="modal" data-target="#modal_{{ $timetable->id }}">View Timetable</button>
                                    <button class="btn btn-primary">Save Changes</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}

                @endforeach

                <div class="form-group">
                    <button type="button" id="btn_add_new" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0"
                        data-toggle="modal" data-target="#modal_new">+ Add New</button>
                </div>
            </div>

            <div class="col-md-3">
                <div class="page-separator">
                    <div class="page-separator__text">Order</div>
                </div>

                <div class="nestable" id="table_order">
                    <ul class="nestable-list">
                        @foreach($class->examTimeTables() as $timetable)
                        <li class="nestable-item" data-id="{{ $timetable->id }}">
                            <div class="nestable-handle">
                                <div class="accordion__menu-link p-0">
                                    <span class="mr-8pt" data-order="{{ $loop->iteration }}">
                                        <strong class="timetable_no">{{ $loop->iteration }}</strong>
                                        <strong>.</strong>
                                    </span>
                                    <span class="flex">
                                        <small class="js-lists-values-project"><strong>{{ $timetable->name }}</strong></small>
                                    </span>
                                    <span class="text-muted move-handle">
                                        <i class="material-icons text-70 icon-16pt icon--left cursor-pointer">drag_handle</i>
                                    </span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Timetable view -->
@foreach($class->examTimeTables() as $timetable)
<div class="modal fade" id="modal_{{ $timetable->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TimeTable for {{ $timetable->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-content full-view mb-16pt">
                    @if($timetable->type != 'pdf')
                    <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                        alt="people" width="100%" class="rounded" />

                    <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                        alt="people" width="100%" class="rounded" style="display:none;" />

                    @else
                    <img src="{{ asset('/storage/attachments/' . $timetable->url) }}"
                        alt="people" width="100%" class="rounded" style="display:none;" />

                    <embed src="{{ asset('/storage/attachments/' . $timetable->url) }}" type="application/pdf"
                        alt="people" width="100%" class="rounded" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal for New Exam Timetable -->
<div class="modal fade" id="modal_new" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        {!! Form::open(['method' => 'POST', 'route' => ['admin.timetables.store'], 'files' => true, 'id' => 'frm_exam']) !!}

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TimeTable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="media">
                    <div class="media-left mr-32pt">
                        <div class="preview-wrap profile-avatar mb-16pt" id="file_preview">
                            <img src="{{ asset('/assets/img/no-image.jpg') }}"
                                alt="people" width="330" class="rounded" />
                            <embed src="{{ asset('/assets/img/no-image.jpg') }}" type="application/pdf"
                                alt="people" width="350" class="rounded" style="display:none;" />
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="form-group">
                            <label class="form-label">Title of Exam Timetable</label>
                            <input type="text" class="form-control" name="name" placeholder="Unit Test 1" tute-no-empty>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="file_timetable" class="custom-file-input" id="avatar_file"
                                file-preview="#file_preview" accept=".gif,.jpg,.jpeg,.png,.pdf" tute-no-empty>
                            <label class="custom-file-label" for="avatar_file">Choose file</label>
                        </div>
                        <small class="text-muted pl-8pt">Upload Timetable (image file or .pdf)</small>
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/js/jquery.nestable.js') }}"></script>
<script src="{{ asset('assets/js/nestable.js') }}"></script>

<script>
    $(function() {
        $('input[type="file"]').on('change', function(e) {
            var file = this.files[0];
            var file_type = file.type;
            $('label[for="'+ $(this).attr('id') +'"]').text(file.name);

            var ele_embed = $($(this).attr('file-preview')).find('embed');
            var ele_img = $($(this).attr('file-preview')).find('img');

            if(file_type.indexOf('pdf') > 0) { // PDF processing
                
                ele_img.hide();
                ele_embed.show();
                display_preview(file, ele_embed);
            } else {
                ele_img.show();
                ele_embed.hide();
                display_preview(file, ele_img);
            }
        });

        function display_preview(file, ele) {
            var reader  = new FileReader();
            reader.onload = function(e)  {
                ele.attr('src', e.target.result);
            }
            // declear file loading
            reader.readAsDataURL(file);
        }

        $('form').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        swal('Success!', 'Updated Successfully', 'success');
                    }
                }
            });
        });

        $('#table_order').on('change', function() {
            var order_json = $(this).nestable('serialize');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: "{{ route('admin.ajax.timetables.order') }}",
                method: 'POST',
                data: {data: order_json},
                success: function(res) {
                    console.log(res);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    });
</script>
@endpush

@endsection