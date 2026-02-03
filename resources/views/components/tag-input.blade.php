@props(['name' => '', 'values' => [], 'availableOptions' => [], 'placeholder' => 'Add variant value...', 'attributeType' => 'text'])

<div class="tag-input-container w-100" data-name="{{ $name }}">
    <div class="tag-input-box form-control d-flex flex-wrap gap-2 align-items-center">
        <div class="tags-list d-flex flex-wrap gap-2" data-type="{{ $attributeType }}">
            @foreach ($values as $value)
                @php
                    $isColor = $attributeType === 'color';
                    $colorCode = $isColor ? $value : null; // Fallback, though usually hex is passed
                @endphp
                <span
                    class="tag-item badge d-flex align-items-center gap-2 py-2 px-3 fw-bold shadow-sm"
                    style="border-radius: 8px; font-size: 0.82rem; transition: all 0.2s;">
                    @if($isColor && $colorCode)
                        <div class="color-preview rounded-1 border border-white border-opacity-25" style="width: 14px; height: 14px; background-color: {{ $colorCode }};"></div>
                    @endif
                    <span>{{ $value }}</span>
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    <i class="ph ph-x-circle remove-tag-btn cursor-pointer"
                        style="font-size: 1rem; opacity: 0.6; transition: opacity 0.2s;"></i>
                </span>
            @endforeach
        </div>
        <div class="flex-grow-1 position-relative">
            <input type="text" class="tag-input-field border-0 outline-0 p-0 w-100 shadow-none bg-transparent fw-medium"
                placeholder="{{ count($values) === 0 ? $placeholder : 'Add another...' }}" style="font-size: 0.9rem;">
            <div class="tag-autocomplete-dropdown dropdown-menu shadow-lg border-0 mt-2 w-100"
                style="max-height: 200px; overflow-y: auto; border-radius: 10px;">
            </div>
        </div>
    </div>
</div>

@once
    @push('styles:after')
        <style>
            :root {
                --tag-primary: rgba(var(--primary), 1);
                --tag-bg: rgba(var(--primary), 0.05);
                --tag-border: rgba(var(--primary), 0.15);
            }
            .tag-autocomplete-dropdown.show {
                display: block;
            }
            .tag-item {
                background-color: var(--tag-bg) !important;
                color: var(--tag-primary) !important;
                border: 1px solid var(--tag-border) !important;
            }
            .tag-input-box {
                min-height: 45px;
                padding: 0.5rem 0.75rem;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                transition: all 0.2s;
                background-color: #fff;
            }
            .tag-input-box:focus-within {
                border-color: var(--tag-primary);
                box-shadow: 0 0 0 3px rgba(var(--primary), 0.08);
            }
            .remove-tag-btn {
                cursor: pointer;
            }
            .remove-tag-btn:hover {
                opacity: 1 !important;
                color: rgba(var(--danger), 1) !important;
            }
            .tag-input-field:focus {
                outline: none !important;
            }
        </style>
    @endpush

    @push('scripts:before')
        <script>
            if (typeof TagInput === 'undefined') {
                window.TagInput = class TagInput {
                    constructor(element, options = {}) {
                        this.container = element;
                        this.input = element.querySelector('.tag-input-field');
                        this.tagsList = element.querySelector('.tags-list');
                        this.dropdown = element.querySelector('.tag-autocomplete-dropdown');
                        this.name = element.dataset.name || options.name || '';
                        this.availableOptions = options.availableOptions || [];
                        this.attributeType = options.attributeType || this.tagsList.dataset.type || 'text';
                        this.onChanged = options.onChanged || (() => {});

                        this.init();
                    }

                    init() {
                        this.input.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                this.addTag(this.input.value);
                            } else if (e.key === 'Backspace' && !this.input.value) {
                                this.removeLastTag();
                            }
                        });

                        this.input.addEventListener('input', () => this.updateDropdown());
                        this.input.addEventListener('focus', () => this.updateDropdown());

                        document.addEventListener('click', (e) => {
                            if (!this.container.contains(e.target)) {
                                this.dropdown.classList.remove('show');
                            }
                        });

                        this.tagsList.addEventListener('click', (e) => {
                            if (e.target.classList.contains('remove-tag-btn')) {
                                e.target.closest('.badge').remove();
                                this.onChanged();
                            }
                        });
                    }

                    setAvailableOptions(options) {
                        this.availableOptions = options;
                        this.updateDropdown();
                    }

                    addTag(value, name = null, code = null) {
                        const tagValue = typeof value === 'object' ? (value.value || value.name) : value;
                        const tagName = name || (typeof value === 'object' ? (value.name || value.value) : value);
                        const tagCode = code || (typeof value === 'object' ? (value.code || value.value) : (this.attributeType === 'color' ? value : null));

                        if (!tagValue) return;

                        const existingTags = Array.from(this.tagsList.querySelectorAll('input')).map(i => i.value);
                        if (existingTags.includes(tagValue)) {
                            this.input.value = '';
                            return;
                        }

                        const badge = document.createElement('span');
                        badge.className =
                            'tag-item badge d-flex align-items-center gap-2 py-2 px-3 fw-bold shadow-sm';
                        badge.style.borderRadius = '8px';
                        badge.style.fontSize = '0.82rem';
                        badge.style.transition = 'all 0.2s';

                        let colorBox = '';
                        if (this.attributeType === 'color' && tagCode) {
                            colorBox = `<div class="color-preview rounded-1 border border-white border-opacity-25" style="width: 14px; height: 14px; background-color: ${tagCode};"></div>`;
                        }

                        badge.innerHTML = `
                    ${colorBox}
                    <span>${tagName}</span>
                    <input type="hidden" name="${this.name}" value="${tagValue}">
                    <i class="ph ph-x-circle remove-tag-btn cursor-pointer" style="font-size: 1rem; opacity: 0.6; transition: opacity 0.2s;"></i>
                `;

                        this.tagsList.appendChild(badge);
                        this.input.value = '';
                        this.dropdown.classList.remove('show');
                        this.onChanged();
                    }

                    removeLastTag() {
                        const badges = this.tagsList.querySelectorAll('.badge');
                        if (badges.length > 0) {
                            badges[badges.length - 1].remove();
                            this.onChanged();
                        }
                    }

                    updateDropdown() {
                        const query = this.input.value.toLowerCase();
                        const existingTags = Array.from(this.tagsList.querySelectorAll('input')).map(i => i.value);

                        const filtered = this.availableOptions.filter(opt => {
                            const optName = (typeof opt === 'object' ? (opt.name || opt.value) : opt).toString().toLowerCase();
                            const optValue = (typeof opt === 'object' ? (opt.value || opt.name) : opt).toString();
                            return optName.includes(query) && !existingTags.includes(optValue);
                        });

                        if (filtered.length > 0 || query.length > 0) {
                            let html = filtered.map(opt => {
                                const displayName = typeof opt === 'object' ? (opt.value || opt.name) : opt;
                                const tagValue = typeof opt === 'object' ? (opt.value || opt.name) : opt;
                                const originalName = typeof opt === 'object' ? (opt.name || opt.value) : opt;
                                const code = typeof opt === 'object' ? (opt.code || opt.value) : (this.attributeType === 'color' ? tagValue : null);

                                let iconHtml = '<i class="ph ph-tag text-muted"></i>';
                                if (this.attributeType === 'color' && code) {
                                    iconHtml = `<div class="rounded-circle border border-light shadow-sm" style="width: 16px; height: 16px; background-color: ${code};"></div>`;
                                }

                                return `<button type="button" class="dropdown-item d-flex align-items-center gap-3 py-2 px-3"
                                            data-value="${tagValue}" data-name="${displayName}" data-code="${code || ''}">
                                            ${iconHtml}
                                            <span class="text-dark fw-medium">${displayName}</span>
                                        </button>`;
                            }).join('');

                            if (query.length > 0 && !filtered.some(f => (typeof f === 'object' ? (f.value || f.name) : f).toString().toLowerCase() === query)) {
                                html += `<hr class="my-1 opacity-10">
                                         <button type="button" class="dropdown-item d-flex align-items-center gap-3 py-2 px-3 custom-add-opt"
                                            data-value="${this.escapeHtml(query)}">
                                            <i class="ph ph-plus-circle text-primary"></i>
                                            <span class="text-primary fw-bold">Add "${this.escapeHtml(query)}"</span>
                                         </button>`;
                            }

                            this.dropdown.innerHTML = html;

                            this.dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                                item.addEventListener('click', () => {
                                    if (item.classList.contains('custom-add-opt')) {
                                        this.addTag(item.dataset.value);
                                    } else {
                                        this.addTag(item.dataset.value, item.dataset.name, item.dataset.code);
                                    }
                                });
                            });

                            this.dropdown.classList.add('show');
                        } else {
                            this.dropdown.classList.remove('show');
                        }
                    }

                    getValues() {
                        return Array.from(this.tagsList.querySelectorAll('input')).map(i => i.value);
                    }

                    escapeHtml(text) {
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    }
                };
            }
        </script>
    @endpush
@endonce