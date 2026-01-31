<div class="locale-tabs">
    <ul class="nav nav-tabs mb-3" id="localeTab-{{ $id }}" role="tablist">
        @foreach (config('app.locales') as $locale)
            @php $tabId = Str::slug($locale) . '-' . $id; @endphp
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($loop->first) active @endif" 
                   id="tab-{{ $tabId }}"
                   data-bs-toggle="tab"
                   href="#pane-{{ $tabId }}"
                   role="tab"
                   aria-controls="pane-{{ $tabId }}"
                   aria-selected="@if($loop->first) true @else false @endif">
                   {{ strtoupper($locale) }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="localeTabContent-{{ $id }}">
        @foreach (config('app.locales') as $locale)
            @php $tabId = Str::slug($locale) . '-' . $id; @endphp
            <div class="tab-pane fade @if($loop->first) show active @endif" 
                 id="pane-{{ $tabId }}" role="tabpanel" aria-labelledby="tab-{{ $tabId }}">

                @foreach($fields as $field)
                    @php
                        $values = is_array($field['value'] ?? null) ? $field['value'] : [];
                        foreach (config('app.locales') as $l) {
                            if (!array_key_exists($l, $values)) {
                                $values[$l] = '';
                            }
                        }

                        $fieldName = $field['name'] . '[' . $locale . ']';
                        $oldName = $field['name'] . '.' . $locale;
                        $label = $field['label'] . ' (' . strtoupper($locale) . ')';
                        $value = $values[$locale] ?? '';
                        $placeholder = $field['placeholder'] ?? '';
                    @endphp

                    @if ($field['type'] === 'input')
                        <x-form.form-input
                            :name="$fieldName"
                            :oldName="$oldName"
                            :label="$label"
                            :placeholder="$placeholder"
                            :value="$value"
                            :isRequired="$field['isRequired'] ?? false"
                        />

                    @elseif($field['type'] === 'textarea')
                        <x-form.form-textarea
                            :id="$field['name'] . '_' . $locale"
                            :name="$fieldName"
                            :oldName="$oldName"
                            :label="$label"
                            :placeholder="$placeholder"
                            :value="$value"
                            :isRequired="$field['isRequired'] ?? false"
                        />

                    @elseif($field['type'] === 'summernote')
                        <x-form.summernote
                            :id="$field['name'] . '_' . $locale"
                            :name="$fieldName"
                            :oldName="$oldName"
                            :label="$label"
                            :placeholder="$placeholder"
                            :value="$value"
                            :isRequired="$field['isRequired'] ?? false"
                        />
                    @endif

                @endforeach

            </div>
        @endforeach
    </div>
</div>

<script>
    (function(){
        function activateLink(link){
            const container = link.closest('.locale-tabs');
            if(!container) return;
            container.querySelectorAll('.nav-link').forEach(l=>l.classList.remove('active'));
            container.querySelectorAll('.tab-pane').forEach(p=>p.classList.remove('show','active'));
            link.classList.add('active');
            const href = link.getAttribute('href') || link.getAttribute('data-bs-target');
            if(!href) return;
            const paneId = href.startsWith('#') ? href.slice(1) : href;
            const pane = container.querySelector('#' + paneId.replace(/[#.:]/g,'\\$&'));
            if(pane) pane.classList.add('show','active');
        }

        document.addEventListener('click', function(e){
            const link = e.target.closest('.locale-tabs .nav-link');
            if(!link) return;
            e.preventDefault();
            activateLink(link);
            try { history.replaceState(null, '', link.getAttribute('href')); } catch(e){}
        });

        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('.locale-tabs').forEach(container => {
                const hash = window.location.hash && window.location.hash.slice(1);
                if(hash){
                    const target = container.querySelector('#' + hash.replace(/[#.:]/g,'\\$&'));
                    if(target){
                        const tab = container.querySelector('.nav-link[href="#' + hash + '"]');
                        if(tab) return activateLink(tab);
                    }
                }

                const active = container.querySelector('.nav-link.active');
                if(active) activateLink(active);
            });
        });
    })();
</script>
