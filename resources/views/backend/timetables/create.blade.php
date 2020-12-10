@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create a timetable</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Create a timetable
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.timetables.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">
        <div class="page-separator">
            <div class="page-separator__text">TimeTable</div>
        </div>

        <div class="card card-body">
        	
        	<div class="form-group">
        		<div class="media">
        			<div class="media-left mr-32pt">
						<label class="form-label">Upload Timetable (image file or .pdf)</label>

						<div class="profile-avatar mb-16pt">
							<img src="{{ asset('/assets/img/no-image.jpg') }}"
                                id="file_preview" alt="people" width="330" class="rounded" />
                        </div>
                        <div>
                            <div class="custom-file">
                                <input type="file" name="file_timetable" class="custom-file-input" id="avatar_file"
                                    data-preview="#file_preview">
                                <label class="custom-file-label" for="avatar_file">Choose file</label>
                            </div>
                        </div>
        			</div>
        		</div>
        	</div>
        </div>

    </div>
</div>

@endsection