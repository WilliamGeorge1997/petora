@extends('common::layouts.master')

@section('title', __('admin::admin.edit'))

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}admin/vendors/css/forms/select/select2.min.css">
@endsection
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">تعديل البيانات الخاصه بالادمن {{ $admin['name'] }}</h4>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" action="{{ url('admin/admins/' . $admin->id) }}" method="POST"
                    enctype="multipart/form-data">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="fname-icon">الاسم</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" id="fname-icon" class="form-control"
                                            value="{{ $admin['name'] }}" name="name" placeholder="الاسم" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="email-icon">البريد الالكتروني</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="mail"></i></span>
                                        <input type="email" id="email-icon" value="{{ $admin['email'] }}"
                                            class="form-control" name="email" placeholder="البريد الالكتروني" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="contact-icon">رقم الجوال</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="smartphone"></i></span>
                                        <input type="number" id="contact-icon" value="{{ $admin['phone'] }}"
                                            class="form-control" name="phone" placeholder="رقم الجوال" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">كلمة المرور</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="lock"></i></span>
                                        <input type="password" id="pass-icon" class="form-control" name="password"
                                            placeholder="كلمة المرور" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="image">الصوره</label>
                                </div>
                                <div class="col-sm-{{ $admin->image ? '6' : '9' }}">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="image"></i></span>
                                        <input type="file" id="image" class="form-control" name="image"
                                            placeholder="image" accept="image/*" />
                                    </div>
                                    @error('image')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if ($admin->image != null)
                                    <div class="col-sm-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img class="rounded border width-100 height-100 cursor-pointer" role="button"
                                                data-bs-toggle="modal" data-bs-target="#adminImageModal"
                                                src="{{ $admin->image }}" alt="{{ $admin->name }}">
                                        </div>
                                    </div>
                                    <x-common::modal id="adminImageModal" :title="$admin->name" body-class="text-center">
                                        <img src="{{ $admin->image }}" class="img-fluid rounded border"
                                            alt="{{ $admin->name }}">
                                    </x-common::modal>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="pass-icon">الوظيفة</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select class="select2 form-select dt-role" id="select2-basic" name="role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role['id'] }}"
                                                    @if (in_array($role->name, $userRole)) selected @endif
                                                    data-role-name="{{ $role->name }}">{{ $role['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Branch Selection - Only for Branch Manager -->
                        <div class="col-12" id="branch-selection" style="display: none;">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="branch-select">الفرع</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select class="select2 form-select" id="branch-select" name="branch_id">
                                            <option value="">اختر الفرع</option>
                                            @foreach ($viewModel->branches() as $branch)
                                                <option value="{{ $branch->id }}"
                                                    @if ($admin->branch_id == $branch->id) selected @endif>{{ $branch->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 offset-sm-3">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" value="1" @if ($admin->is_active == 1) checked @endif
                                        name="is_active" class="form-check-input" id="customCheck2" />
                                    <label class="form-check-label" for="customCheck2">تفعيل</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-primary me-1"><i data-feather="edit"
                                    class="me-50"></i>تعديل بيانات المدير</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('') }}admin/vendors/js/forms/select/select2.full.min.js"></script>

    <script>
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

        // Handle branch selection visibility
        function toggleBranchSelection() {
            var selectedRole = $('#select2-basic option:selected');
            var roleId = selectedRole.val();
            var roleName = selectedRole.data('role-name');

            if (roleId == '2' || roleName == 'Branch Manager') {
                $('#branch-selection').show();
            } else {
                $('#branch-selection').hide();
                $('#branch-select').val('').trigger('change');
            }
        }

        // Check on page load
        toggleBranchSelection();

        // Check when role changes
        $('#select2-basic').on('change', function() {
            toggleBranchSelection();
        });
    </script>
@endsection
