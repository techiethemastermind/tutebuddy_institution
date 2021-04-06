@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    [dir=ltr] .preview-wrap {
        height: 215px;
    }

    [dir=ltr] .preview-wrap * {
        height: 100%;
        object-fit: cover;
        object-position: top;
    }

    [dir=ltr] .modal-content embed {
        height: 80vh;
    }

    [dir=ltr] .modal-content img {
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
                    <a href="{{ route('admin.timetables.class') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">

        @if($class->divisions->count() < 1)
        <div class="page-section">
            <div class="form-group text-center">
                <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-primary btn-block">Add Divisions to this class first</a>
            </div>
        </div>
        @endif

        {!! Form::model($class, ['method'=>'POST', 'files' => true, 'id' => 'frm_timetable', 'route'=>['admin.timetables.class.update', $class->id]]) !!}

        @foreach($class->divisions as $division)
        <div class="page-separator">
            <div class="page-separator__text">Division {{ $division->name }}</div>
        </div>

        <div class="card card-body">
        	
        	<div class="form-group">
        		<div class="media">
        			<div class="media-left mr-32pt">

						<div class="preview-wrap mb-16pt" id="file_preview_{{ $division->id }}">
                            @if($class->classTimeTableForDivision($division->id))

                                @if($class->classTimeTableForDivision($division->id)->type != 'pdf')
                                <img src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}"
                                    alt="people" width="350" class="rounded" />

                                <embed src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}" type="application/pdf"
                                    alt="people" width="350" class="rounded" style="display:none;" />

                                @else
                                <img src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}"
                                    alt="people" width="350" class="rounded" style="display:none;" />

                                <embed src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}" type="application/pdf"
                                    alt="people" width="350" class="rounded" />
                                @endif
                            @else
                            <img src="{{ asset('/assets/img/no-image.jpg') }}"
                                alt="people" width="350" class="rounded" />

                            <embed src="{{ asset('/assets/img/no-image.jpg') }}" type="application/pdf"
                                alt="people" width="350" class="rounded" style="display:none;" />
                            @endif
                        </div>
                    </div>
                    
                    <div class="media-body">
                        
                        @if($class->classTimeTableForDivision($division->id))
                            <div class="form-group">
                                <label class="form-label">File Name:</label>
                                <label for="file_name_{{ $division->id }}" class="form-label text-muted">{{ $class->classTimeTableForDivision($division->id)->name }}</label>
                            </div>

                            <div class="form-group">
                                <label class="form-label">File Type:</label>
                                <label for="file_type_{{ $division->id }}" class="form-label text-muted">{{ $class->classTimeTableForDivision($division->id)->type }}</label>
                            </div>
                        @else
                            <div class="form-group mb-0">
                                <label class="form-label">File Name:</label>
                                <label for="file_name_{{ $division->id }}" class="form-label text-muted">Not Set</label>
                            </div>

                            <div class="form-group">
                                <label class="form-label">File Type:</label>
                                <label for="file_type_{{ $division->id }}" class="form-label text-muted">Image</label>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Upload new Timetable Image or PDF file</label>
                            <div class="custom-file">
                                <input type="file" name="file_timetable_{{ $division->id }}" class="custom-file-input" id="avatar_file_{{ $division->id }}"
                                    file-preview="#file_preview_{{ $division->id }}" accept=".gif,.jpg,.jpeg,.png,.pdf" data-id="{{ $division->id }}">
                                <label class="custom-file-label" for="avatar_file_{{ $division->id }}">Choose file</label>
                            </div>
                            <small class="form-text text-muted">PFD or image files</small>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-accent" data-toggle="modal" data-target="#modal_{{ $division->id }}">View Timetable</button>
                            <button class="btn btn-primary">Save Changes</button>
                        </div>

                    </div>
        		</div>
        	</div>
        </div>

        @endforeach

        {!! Form::close() !!}
    </div>
</div>

<!-- Modal for Timetable view -->
@foreach($class->divisions as $division)
<div class="modal fade" id="modal_{{ $division->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TimeTable for {{ $division->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-content mb-16pt">
                    @if($class->classTimeTableForDivision($division->id))

                        @if($class->classTimeTableForDivision($division->id)->type != 'pdf')
                        <img src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}"
                            alt="people" width="100%" class="rounded" />

                        <embed src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}" type="application/pdf"
                            alt="people" width="100%" class="rounded" style="display:none;" />

                        @else
                        <img src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}"
                            alt="people" width="100%" class="rounded" style="display:none;" />

                        <embed src="{{ asset('/storage/attachments/' . $class->classTimeTableForDivision($division->id)->url) }}" type="application/pdf"
                            alt="people" width="100%" class="rounded" />
                        @endif
                    @else
                    <img src="{{ asset('/assets/img/no-image.jpg') }}"
                        alt="people" width="100%" class="rounded" />

                    <embed src="{{ asset('/assets/img/no-image.jpg') }}" type="application/pdf"
                        alt="people" width="100%" class="rounded" style="display:none;" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('after-scripts')

<script>
    $(function() {
        $('input[type="file"]').on('change', function(e) {
            var file = this.files[0];
            var file_type = file.type;
            $('label[for="'+ $(this).attr('id') +'"]').text(file.name);
            $('label[for="file_name_'+ $(this).attr('data-id') +'"]').text(file.name);
            $('label[for="file_type_'+ $(this).attr('data-id') +'"]').text(file.type);

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

        $('#frm_timetable').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        swal('Success!', 'Updated Successfully', 'success');
                    }
                }
            });
        });
    });
</script>
@endpush

@endsection