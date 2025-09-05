@extends('layouts.template', ['title' => 'Adjustment', 'breadcumbs' => ['Adjustment']])

@push('css')
    <link rel="stylesheet" href="{{ asset('kai/lib/datatable-new/datatables.min.css') }}">
@endpush

@section('contents')
    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title mb-0">Import Adjustment Stock </h5>
            </div>
            <div class="row">
                <form id="form_import" enctype="multipart/form-data">
                    <div class="col-12 mb-2">
                        @csrf
                        <div class="form-group">
                            <label class="control-label" for="import_file">Pilih File :</label>
                            <input class="form-control" name="file" type="file" id="import_file"
                                accept=".xlsx,.xls,.csv">
                            <div class="form-text">Format file : xlx,xlsx,csv</div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <a href="{{ asset('master/sample-adjustment.xlsx') }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-download me-1" title="Download Sample"></i>Download Sample
                                </a>
                                <button type="button" id="btn_import" class="btn btn-primary">
                                    <i class="fas fa-upload me-1" title="Upload File"></i>Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#btn_import').on('click', function() {
            var formData = new FormData($('#form_import')[0]);
            $.ajax({
                url: "{{ route('adjustments.save') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // 
                },
                success: function(res) {
                    // table.ajax.reload();
                    show_toast('success', 'Import berhasil!')
                },
                error: function(xhr) {
                    show_toast('error', xhr.responseJSON.message || 'Terjadi kesalahan saat import.')
                }
            });
        });
    </script>
@endpush
