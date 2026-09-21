@php
    $iconMap = [
        'name_ar' => 'tag',
        'name_en' => 'tag',
        'phone' => 'phone',
        'email' => 'mail',
        'tax' => 'percent',
        'delivery_fee_per_km' => 'truck',
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'instagram' => 'instagram',
        'whatsapp' => 'message-circle',
        'telegram' => 'send',
        'snapchat' => 'camera',
        'tiktok' => 'video',
        'about_ar' => 'file-text',
        'about_en' => 'file-text',
        'terms_ar' => 'file-text',
        'terms_en' => 'file-text',
        'privacy_ar' => 'file-text',
        'privacy_en' => 'file-text',
    ];

    $isEn = app()->getLocale() === 'en';

    $tabGroups = [
        'general' => [
            'id' => 'general',
            'title' => __('common::general.general_settings'),
            'icon' => 'settings',
            'keys' => $isEn ? ['name_en', 'name_ar', 'phone', 'email'] : ['name_ar', 'name_en', 'phone', 'email'],
        ],
        'financial' => [
            'id' => 'financial',
            'title' => __('common::general.financial_settings'),
            'icon' => 'dollar-sign',
            'keys' => ['tax', 'delivery_fee_per_km'],
        ],
        'social' => [
            'id' => 'social',
            'title' => __('common::general.social_settings'),
            'icon' => 'share-2',
            'keys' => ['facebook', 'twitter', 'instagram', 'whatsapp', 'telegram', 'snapchat', 'tiktok'],
        ],
        'policies' => [
            'id' => 'policies',
            'title' => __('common::general.policies_settings'),
            'icon' => 'file-text',
            'keys' => $isEn
                ? ['about_en', 'about_ar', 'terms_en', 'terms_ar', 'privacy_en', 'privacy_ar']
                : ['about_ar', 'about_en', 'terms_ar', 'terms_en', 'privacy_ar', 'privacy_en'],
        ],
    ];

    $categories = collect($tabGroups)->pluck('keys')->flatten()->toArray();
    $settingsByKey = $settings->keyBy('key');
    $notInGroup = $settings->reject(fn($item) => in_array($item->key, $categories));
@endphp

@extends('common::layouts.master')

@section('title', __('common::sidebar.general_settings'))

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="card-title">{{ __('common::sidebar.general_settings') }}</h4>
            </div>
            <div class="card-body pt-2">
                {{-- Tabs Navigation --}}
                <ul class="nav nav-tabs mb-2" id="settingsTabs" role="tablist">
                    @foreach ($tabGroups as $group)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $group['id'] }}-tab"
                                data-bs-toggle="tab" data-bs-target="#{{ $group['id'] }}" type="button" role="tab"
                                aria-controls="{{ $group['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i data-feather="{{ $group['icon'] }}" class="me-50"></i> {{ $group['title'] }}
                            </button>
                        </li>
                    @endforeach

                    {{-- Default tab for any unclassified or newly added settings --}}
                    @if ($notInGroup->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other"
                                type="button" role="tab" aria-controls="other" aria-selected="false">
                                <i data-feather="more-horizontal" class="me-50"></i> {{ __('common::general.other_settings') }}
                            </button>
                        </li>
                    @endif
                </ul>

                {{-- Settings Form --}}
                <form action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf
                    <div class="tab-content" id="settingsTabsContent">
                        @foreach ($tabGroups as $group)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $group['id'] }}"
                                role="tabpanel" aria-labelledby="{{ $group['id'] }}-tab">
                                <div class="row">
                                    @foreach ($group['keys'] as $key)
                                        @if ($setting = $settingsByKey->get($key))
                                            <div class="{{ $setting->type === 'textarea' ? 'col-12' : 'col-md-6' }} mb-2">
                                                <label class="form-label fw-bold" for="setting_{{ $setting->key }}">
                                                    {{ $setting->display }}
                                                </label>

                                                @if ($setting->type === 'textarea')
                                                    <textarea class="form-control" id="setting_{{ $setting->key }}" name="{{ $setting->key }}" rows="5"
                                                        dir="{{ str_ends_with($setting->key, '_ar') ? 'rtl' : 'ltr' }}" placeholder="{{ $setting->display }}">{{ $setting->value }}</textarea>
                                                @else
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text">
                                                            <i data-feather="{{ $iconMap[$setting->key] ?? ($setting->type === 'number' ? 'hash' : 'edit-3') }}"></i>
                                                        </span>
                                                        <input
                                                            type="{{ $setting->type === 'number' ? 'number' : ($setting->key === 'email' ? 'email' : ($setting->key === 'phone' ? 'tel' : 'text')) }}"
                                                            @if ($setting->type === 'number') step="any" min="0" @endif
                                                            class="form-control" id="setting_{{ $setting->key }}"
                                                            name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                            dir="{{ str_ends_with($setting->key, '_ar') ? 'rtl' : 'ltr' }}"
                                                            placeholder="{{ $setting->display }}" />
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- Fallback tab content for unclassified/new settings with default icons and inputs --}}
                        @if ($notInGroup->isNotEmpty())
                            <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                                <div class="row">
                                    @foreach ($notInGroup as $setting)
                                        <div class="{{ $setting->type === 'textarea' ? 'col-12' : 'col-md-6' }} mb-2">
                                            <label class="form-label fw-bold" for="setting_{{ $setting->key }}">
                                                {{ $setting->display }}
                                            </label>

                                            @if ($setting->type === 'textarea')
                                                <textarea class="form-control" id="setting_{{ $setting->key }}" name="{{ $setting->key }}" rows="5"
                                                    dir="{{ str_ends_with($setting->key, '_ar') ? 'rtl' : 'ltr' }}" placeholder="{{ $setting->display }}">{{ $setting->value }}</textarea>
                                            @else
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i data-feather="{{ $iconMap[$setting->key] ?? ($setting->type === 'number' ? 'hash' : 'edit-3') }}"></i>
                                                    </span>
                                                    <input
                                                        type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                                        @if ($setting->type === 'number') step="any" min="0" @endif
                                                        class="form-control" id="setting_{{ $setting->key }}"
                                                        name="{{ $setting->key }}" value="{{ $setting->value }}"
                                                        dir="{{ str_ends_with($setting->key, '_ar') ? 'rtl' : 'ltr' }}"
                                                        placeholder="{{ $setting->display }}" />
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-end mt-2 pt-1 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save" class="me-50"></i> {{ __('common::general.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection