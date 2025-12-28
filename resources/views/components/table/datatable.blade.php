<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $title ?? '' }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="{{ $id ?? 'datatable' }}" class="display" style="min-width: 845px">
                        <thead>
                            {{ $tableHeader }}
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (isset($customScript))
    <script>
        $(function () {
            $('#{{ $id ?? 'datatable' }}').DataTable({
                {!! $customScript !!}
            });
        });
    </script>
@endif
