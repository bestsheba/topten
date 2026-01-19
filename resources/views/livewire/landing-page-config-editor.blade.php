<div class="p-3">
    <!-- Flash Messages -->
    @if (session('flash_message'))
        @php
            $flash = session('flash_message');
            $alertClass = match ($flash['type']) {
                'success' => 'alert-success',
                'error' => 'alert-danger',
                'info' => 'alert-info',
                'warning' => 'alert-warning',
                default => 'alert-info',
            };
        @endphp
        <div class="alert {{ $alertClass }} alert-dismissible fade show mb-3 bg-red-500" role="alert">
            {{ $flash['message'] }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">⚙️ Config Editor</h5>
        <div>
            <button wire:click="saveConfig" class="btn btn-success btn-sm mr-2">
                💾 Save
            </button>
            <button wire:click="resetToDefault" class="btn btn-warning btn-sm">
                🔄 Reset
            </button>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <!-- Common Tab -->
        <li class="nav-item" role="presentation">
            <button type="button" wire:click="setActiveTab('common')"
                class="nav-link {{ $activeTab === 'common' ? 'active' : '' }}" role="tab"
                style="background: {{ $activeTab === 'common' ? '#16a34a' : '#898989' }}; color:#fff;">
                ⚙️ Common
            </button>
        </li>
        @foreach ($sections as $section)
            <li class="nav-item" role="presentation">
                <button type="button" wire:click="setActiveTab('{{ $section['id'] }}')"
                    class="nav-link {{ $activeTab === $section['id'] ? 'active' : '' }} border" role="tab"
                    style="background: {{ $activeTab === $section['id'] ? '#16a34a' : '#898989' }}; color:#fff;">
                    {{ $section['name'] }}
                    @if (!($section['enabled'] ?? false))
                        <span class="badge badge-secondary ml-2">Disabled</span>
                    @endif
                </button>
            </li>
        @endforeach
    </ul>
    <div class="card">
        <div class="card-body">
            <!-- Common Settings Tab -->
            @if ($activeTab === 'common')
                <h6 class="card-title mb-4">Common Settings</h6>
                <br>
                <br>
                <div class="row">
                    <!-- Hero Title -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Hero Title</label>
                        <input type="text" wire:change="updateCommon('hero_title', $event.target.value)"
                            value="{{ $common['hero_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Hero Subtitle -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Hero Subtitle</label>
                        <input type="text" wire:change="updateCommon('hero_subtitle', $event.target.value)"
                            value="{{ $common['hero_subtitle'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Hero Highlight -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Hero Highlight</label>
                        <input type="text" wire:change="updateCommon('hero_highlight', $event.target.value)"
                            value="{{ $common['hero_highlight'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Offer Percent -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Offer Percent</label>
                        <input type="text" wire:change="updateCommon('offer_percent', $event.target.value)"
                            value="{{ $common['offer_percent'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Phone 1 -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Phone Number 1</label>
                        <input type="text" wire:change="updateCommon('phone_1', $event.target.value)"
                            value="{{ $common['phone_1'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Phone 2 -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Phone Number 2</label>
                        <input type="text" wire:change="updateCommon('phone_2', $event.target.value)"
                            value="{{ $common['phone_2'] ?? '' }}" class="form-control form-control-sm">
                    </div>

                    <!-- Primary Background Color -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Background Color</label>
                        <div class="d-flex gap-2">
                            <input type="color" wire:change="updateCommon('primary_bg_color', $event.target.value)"
                                value="{{ $common['primary_bg_color'] ?? '#dc2626' }}"
                                class="form-control form-control-sm" style="max-width: 60px;">
                            <input type="text" wire:change="updateCommon('primary_bg_color', $event.target.value)"
                                value="{{ $common['primary_bg_color'] ?? '#dc2626' }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Primary Text Color -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Text Color</label>
                        <div class="d-flex gap-2">
                            <input type="color" wire:change="updateCommon('primary_text_color', $event.target.value)"
                                value="{{ $common['primary_text_color'] ?? '#ffffff' }}"
                                class="form-control form-control-sm" style="max-width: 60px;">
                            <input type="text" wire:change="updateCommon('primary_text_color', $event.target.value)"
                                value="{{ $common['primary_text_color'] ?? '#ffffff' }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Secondary Background Color -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Background Color</label>
                        <div class="d-flex gap-2">
                            <input type="color" wire:change="updateCommon('secondary_bg_color', $event.target.value)"
                                value="{{ $common['secondary_bg_color'] ?? '#fbbf24' }}"
                                class="form-control form-control-sm" style="max-width: 60px;">
                            <input type="text"
                                wire:change="updateCommon('secondary_bg_color', $event.target.value)"
                                value="{{ $common['secondary_bg_color'] ?? '#fbbf24' }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Secondary Text Color -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Text Color</label>
                        <div class="d-flex gap-2">
                            <input type="color"
                                wire:change="updateCommon('secondary_text_color', $event.target.value)"
                                value="{{ $common['secondary_text_color'] ?? '#000000' }}"
                                class="form-control form-control-sm" style="max-width: 60px;">
                            <input type="text"
                                wire:change="updateCommon('secondary_text_color', $event.target.value)"
                                value="{{ $common['secondary_text_color'] ?? '#000000' }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <!-- Section Tabs -->
            @else
                @php
                    $activeSection = collect($sections)->firstWhere('id', $activeTab);
                @endphp

                @if ($activeSection)
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h6 class="mb-0">{{ $activeSection['name'] }}</h6>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:change="toggleSection('{{ $activeSection['id'] }}')"
                                {{ $activeSection['enabled'] ?? false ? 'checked' : '' }} class="custom-control-input"
                                id="enableSwitch-{{ $activeSection['id'] }}">
                            <label class="custom-control-label" for="enableSwitch-{{ $activeSection['id'] }}">
                                Enabled
                            </label>
                        </div>
                    </div>

                    @if ($activeSection['type'] === 'hero')
                        <livewire:landing-page-config.hero-section :section="$activeSection" :key="$activeTab" />
                    @elseif($activeSection['type'] === 'packages')
                        <livewire:landing-page-config.packages-section :section="$activeSection" :key="$activeTab" />
                    @elseif($activeSection['type'] === 'features')
                        <livewire:landing-page-config.features-section :section="$activeSection" :key="$activeTab" />
                    @elseif($activeSection['type'] === 'testimonials')
                        <livewire:landing-page-config.testimonials-section :section="$activeSection" :key="$activeTab" />
                    @elseif($activeSection['type'] === 'checkout')
                        <livewire:landing-page-config.checkout-section :section="$activeSection" :key="$activeTab" />
                    @endif
                @endif
            @endif
        </div>
    </div>

    <!-- Status Messages -->
    @if ($errors)
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
