@extends($activeTheme . 'layouts.auth')

@section('auth')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="custom--card">
                <div class="card-header">
                    <h3 class="title">@lang('Update Profile')</h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="row g-4">
                        @csrf
                        <div class="col-md-5">
                            <div class="profile-settings__sidebar">
                                <div class="upload__img">
                                    <label class="form--label">@lang('Upload Image')</label>
                                    <label for="imageUpload" class="upload__img__btn"><i class="ti ti-camera fz-1 transform-1"></i></label>
                                    <input type="file" id="imageUpload" name="image" accept=".jpeg, .jpg, .png">
                                    <div class="upload__img-preview image-preview">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, null, true) }}" alt="image">
                                    </div>
                                </div>
                                <ul class="user-profile-list">
                                    <li>
                                        <span><i class="ti ti-user fz-1 transform-0"></i> @lang('Username')</span> {{ '@' . $user->username }}
                                    </li>
                                    <li>
                                        <span><i class="ti ti-mail fz-1 transform-0"></i> @lang('Email Address')</span> {{ $user->email }}
                                    </li>
                                    <li>
                                        <span><i class="ti ti-device-mobile fz-1 transform-0"></i> @lang('Phone Number')</span> {{ $user->mobile }}
                                    </li>
                                    <li>
                                        <span><i class="ti ti-world fz-1 transform-0"></i> @lang('Country')</span> {{ $user->country_name }}
                                    </li>
                                    <li>
                                        <span><i class="ti ti-user-scan fz-1 transform-0"></i> @lang('KYC')</span> <span @class(['badge', 'badge--success' => $user->kc == ManageStatus::VERIFIED, 'badge--warning' => $user->kc == ManageStatus::UNVERIFIED, 'badge--info' => $user->kc == ManageStatus::PENDING])>{{ $user->kc == ManageStatus::VERIFIED ? trans('Verified') : ($user->kc == ManageStatus::UNVERIFIED ? trans('Unverified') : trans('Pending')) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('First Name')</label>
                                    <input type="text" class="form--control" name="firstname" value="{{ $user->firstname }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form--label required">@lang('Last Name')</label>
                                    <input type="text" class="form--control" name="lastname" value="{{ $user->lastname }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form--label">@lang('Address')</label>
                                    <input type="text" class="form--control" name="address" value="{{ $user->address?->address ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form--label">@lang('City')</label>
                                    <input type="text" class="form--control" name="city" value="{{ $user->address?->city ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form--label">@lang('State')</label>
                                    <input type="text" class="form--control" name="state" value="{{ $user->address?->state ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form--label">@lang('ZIP Code')</label>
                                    <input type="text" class="form--control" name="zip" value="{{ $user->address?->zip ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn--base w-100 mt-2">@lang('Submit')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
