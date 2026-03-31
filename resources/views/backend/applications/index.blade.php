@extends('backend.layouts.master')
@section('title', 'Job Applications | ' . $settings->site_name)

@section('content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Job Applications</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h4>All Job Applications</h4>
                    </div>

                    <div class="card-body table-responsive">
                        {!! $dataTable->table([
                            'class' => 'table table-striped table-bordered table-hover',
                            'id' => 'jobapplications-table'
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cover Letter Modal -->
<div class="modal fade" id="coverLetterModal" tabindex="-1" role="dialog" aria-labelledby="coverLetterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Cover Letter</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <pre id="coverLetterContent" style="white-space: pre-wrap; font-size: 14px;"></pre>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! $dataTable->scripts() !!}

<script>
    // Show cover letter in modal
    function showCoverLetter(text) {
        document.getElementById('coverLetterContent').innerText = text;
        $('#coverLetterModal').modal('show');
    }
</script>
@endpush
