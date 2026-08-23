@extends('common::layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endsection

@section('content')

    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">انشاء كوبون خصم جديد</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/coupons/') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الكود</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" name="code" placeholder="الكود"
                                            value="{{ old('code') }}" />
                                        @error('code')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">نوع الخصم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select name="type" class="select2 form-select select2-hidden-accessible"
                                            id="select2" tabindex="-1" aria-hidden="true">
                                            <option value="1">قيمة ثابتة</option>
                                            <option value="2">نسبة مئوية</option>
                                        </select>
                                        @error('type')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> قيمة الخصم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" id="fname-icon" class="form-control"
                                            value="{{ old('value') }}" name="value" placeholder="قيمة الخصم" />
                                        @error('value')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> الحد الاقصي للخصم ان وجد</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="dollar-sign"></i></span>
                                        <input type="number" id="fname-icon" class="form-control"
                                            value="{{ old('limit') }}" name="limit" placeholder="الحد الاقصي للخصم" />
                                        @error('limit')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> عدد مرات الاستخدام</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="number" id="fname-icon" class="form-control"
                                            value="{{ old('num_of_uses') }}" name="num_of_uses"
                                            placeholder="عدد مرات الاستخدام" />
                                        @error('num_of_uses')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (auth()->guard('admin')->user()->hasRole('Super Admin'))
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3 text-center">
                                        <label class="col-form-label" for="branch-select">الفرع</label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group-merge">
                                            <select class="select2 form-select" id="branch-select" name="branch_id">
                                                <option value="">اختر الفرع</option>
                                                @foreach (\Modules\Branch\Entities\Branch::active()->get() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        @if (old('branch_id') == $branch->id) selected @endif>
                                                        {{ $branch->title ?? $branch->getTranslations('title')['ar'] . ' - ' . $branch->getTranslations('title')['en'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <p class="alert alert-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif



                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> تاريخ البدايه</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="text" name="date_from" value="{{ old('date_from') }}"
                                            id="fp-default" class="form-control flatpickr-basic flatpickr-input active"
                                            placeholder="YYYY-MM-DD" readonly="readonly">
                                        @error('date_from')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> تاريخ النهاية</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="calendar"></i></span>
                                        <input type="text" name="date_to" value="{{ old('date_to') }}"
                                            id="fp-default1" class="form-control flatpickr-basic flatpickr-input active"
                                            placeholder="YYYY-MM-DD" readonly="readonly">
                                        @error('date_to')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> وقت البدايه</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="time_from" value="{{ old('time_from') }}"
                                            id="fp-time"
                                            class="form-control flatpickr-time text-start flatpickr-input active"
                                            placeholder="HH:MM">
                                        @error('time_from')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon"> وقت النهاية</label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="time_to" value="{{ old('time_to') }}"
                                            id="fp-time"
                                            class="form-control flatpickr-time text-start flatpickr-input active"
                                            placeholder="HH:MM">
                                        @error('time_to')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" name="is_active" class="form-check-input"
                                        id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1">انشاء</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{ asset('') }}admin/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{ asset('') }}admin/js/scripts/forms/pickers/form-pickers.js"></script>



    <script>
        $("#fp-default").flatpickr({
            dateFormat: "Y-m-d", // Format for the selected date
            minDate: "today", // Set minimum date to today
            disableMobile: "true" // Disable mobile-friendly mode
        });
        $("#fp-default1").flatpickr({
            dateFormat: "Y-m-d", // Format for the selected date
            minDate: "today", // Set minimum date to today
            disableMobile: "true" // Disable mobile-friendly mode
        });
        var select = $('.select2');

        select.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });


        $(document).ready(function() {
            $("#checkbox").click(function() {
                if ($("#checkbox").is(':checked')) { //select all
                    $("#select2-multiple").find('option').prop("selected", true);
                    $("#select2-multiple").trigger('change');
                } else { //deselect all
                    $("#select2-multiple").find('option').prop("selected", false);
                    $("#select2-multiple").trigger('change');
                }
            });
        });
    </script>
@endsection
