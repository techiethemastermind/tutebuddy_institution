@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="py-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Review Test Submitted</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Test Submitted
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row">
            <div class="col-lg-8">

                @foreach($result->test->questions as $question)
                <div class="border-2 pl-32pt pb-64pt pr-32pt">
                    <div class="group-wrap py-32pt mb-16pt border-bottom-1">

                        <div class="d-flex align-items-center page-num-container">
                            <div class="page-num">{{ $loop->iteration }}</div>
                            <div class="flex">
                                <div class="d-flex">
                                    <h4 class="flex mb-0">Q : {{ $loop->iteration }}</h4>
                                    <h5 class="badge badge-pill font-size-16pt badge-accent">{{ $question->score }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="font-size-16pt text-black-100">{!! $question->question !!}</div>

                        @if(!empty($question->attachment))
                        <div class="form-group mb-24pt card card-body">
                            <label class="form-label">Attached Document:</label>
                            <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                                <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                    @php $ext = pathinfo($question->attachment, PATHINFO_EXTENSION); @endphp
                                    @if($ext == 'pdf')
                                    <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                    @else
                                    <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                    @endif
                                </div>
                                <div class="flex">
                                    <a href="{{ asset('/storage/attachments/' . $question->attachment) }}">
                                        <div class="form-label mb-4pt">{{ $question->attachment }}</div>
                                        <p class="card-subtitle text-black-70">Click to See Attached Document.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
                @endforeach

                <div class="page-section">
                    <div class="page-separator">
                        <div class="page-separator__text">Submitted Content</div>
                    </div>

                    <div class="pb-32pt">
                    <div id="submited_content" class="font-size-16pt text-black-100">{!! $result->content !!}</div>

                    @if(!empty($result->attachment))
                    <div class="form-group mb-24pt card card-body">
                        <label class="form-label">Attached Document:</label>
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                @php $ext = pathinfo($result->attachment, PATHINFO_EXTENSION); @endphp
                                @if($ext == 'pdf')
                                <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                @else
                                <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                @endif
                            </div>
                            <div class="flex">
                                <a href="{{ asset('/storage/attachments/' . $result->attachment) }}">
                                    <div class="form-label mb-4pt">{{ $result->attachment }}</div>
                                    <p class="card-subtitle text-black-70">Click to See Attached Document.</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="page-separator">
                    <div class="page-separator__text">Reply</div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="frm_result" method="POST" action="{{ route('admin.tests.result_answer') }}" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <label for="" class="form-label">test Mark</label>
                                <input type="number" name="mark" class="form-control" value="{{ $result->mark }}">
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Summary</label>
                                <textarea name="answer" rows="10" class="form-control">{{ $result->answer }}</textarea>
                            </div>

                            @if(!empty($result->answer_attach))
                            <div class="form-group mb-24pt card card-body">
                                <label class="form-label">Attached Document:</label>
                                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                                    <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                        @php $ext = pathinfo($result->answer_attach, PATHINFO_EXTENSION); @endphp
                                        @if($ext == 'pdf')
                                        <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                        @else
                                        <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                        @endif
                                    </div>
                                    <div class="flex">
                                        <a href="{{ asset('/storage/attachments/' . $result->answer_attach) }}">
                                            <div class="form-label mb-4pt">{{ $result->answer_attach }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="" class="form-label">Attachment</label>
                                <div class="custom-file">
                                    <input type="file" id="file_doc" name="answer_attach" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                                    <label for="file_doc" class="custom-file-label">Choose file</label>
                                </div>
                            </div>

                            <input type="hidden" name="result_id" value="{{ $result->id }}">

                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div class="d-none">
    <textarea id="a_text">{{ $result->test->content }}</textarea>
    <textarea id="s_text">{{ $result->content }}</textarea>
    <div id="a_editor"></div>
    <div id="s_editor"></div>
</div>


@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(function() {

        $('#frm_result').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        swal('Success!', 'Successfully Submitted!', 'success')
                    }
                }
            });
        });

    });
</script>

@endpush

@endsection