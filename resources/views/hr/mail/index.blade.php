<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('Mail Templates') }}
    </x-slot:pageTitle>

    <x-slot:headerFiles>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </x-slot:headerFiles>

    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Email Templates (for testing)</h5>
                </div>
                <div class="card-body">
                    @php
                        $templates = \App\Models\EmailNotification::orderBy('id')->get();
                    @endphp

                    @if($templates->isEmpty())
                        <div class="text-muted mb-3">No email templates found. You can still test sending by filling the form below.</div>

                        <form action="{{ route('hr.mail.send') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Recipient email</label>
                                <input type="email" name="email" class="form-control" placeholder="recipient@example.com" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Content (HTML allowed)</label>
                                <textarea name="content" class="form-control" rows="6">Hello, this is a test email from E-Comma System.</textarea>
                            </div>

                            <button class="btn btn-primary">Send test mail</button>
                        </form>

                    @else
                        <div class="list-group">
                            @foreach($templates as $t)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $t->name ?? 'Template #' . $t->id }}</h5>
                                        <small class="text-muted">{{ $t->subject }}</small>
                                    </div>
                                    <p class="mb-1 text-truncate" style="max-width:70%;">{!! strip_tags($t->content) !!}</p>

                                    <div class="mt-2 d-flex gap-2">
                                        <form action="{{ route('hr.mail.send') }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            <input type="hidden" name="template_id" value="{{ $t->id }}">
                                            <input type="email" name="email" placeholder="recipient@example.com" required class="form-control" style="max-width:320px;">
                                            <textarea name="content" class="d-none">{{ $t->content }}</textarea>
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </form>

                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#preview-{{ $t->id }}">Preview</button>
                                    </div>

                                    <div class="collapse mt-2" id="preview-{{ $t->id }}">
                                        <div class="card card-body">
                                            {!! $t->content !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-base-layout>
