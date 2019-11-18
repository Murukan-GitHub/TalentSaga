@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">Menu</button>
                </div>
                <div class="category-filter">
                    @include('frontend.partials.dashboard.menu')
                </div>
                <div class="category-content">
                    <h2>Account information</h2>

                    <div class="bzg">
                        <div class="bzg_c" data-col="l9">
                            {!!Form::open(['route' => 'user.dashboard.account.save', 'files' => true])!!}
                                <fieldset class="block">    
                                    <div class="edit-avatar-field-container">
                                        <div class="block-half">
                                        <!--
                                            <label class="form-label">Cover Photo</label>
                                        -->
                                            <figure class="floating-btn-container">
                                                <img class="edit-avatar-field-cover-img" id="previewBackground" src="{{ $user->background ? $user->background_medium_cover : asset('frontend/assets/img/category-header-image.jpg') }}" style="width: 100%;">
                                                <figcaption>
                                                    <input class="sr-only" id="inputProfileBackground" type="file" accept="image/png, image/jpg, image/jpeg" data-input-auto-preview="#previewBackground" name="background">
                                                    <label class="floating-btn is-absolute" for="inputProfileBackground">
                                                        <span class="fa fa-fw fa-camera"></span>
                                                        <span>Edit Cover Photo</span>
                                                    </label>
                                                </figcaption>
                                            </figure>
                                        </div>
                                        <div class="edit-avatar-field floating-btn-container">
                                            <img class="edit-avatar-field-img" src="{{ $user->picture ? $user->picture_medium_square : asset('frontend/assets/img/default-avatar.jpg') }}" alt="">
                                            <label class="edit-avatar-field-btn" for="inputAvatar">
                                                <span class="fa fa-fw fa-camera"></span>
                                                <small>Edit Avatar</small>
                                            </label>
                                            <input class="sr-only" id="inputAvatar" type="file" name="picture_raw" accept="image/png, image/jpg, image/jpeg">
                                            <input id="croppedInputAvatar" name="picture" type="hidden">
                                        </div>
                                    </div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputUsername">Username</label>
                                        <input class="form-input" id="inputUsername" type="text" name="username" value="{{ $user->username }}" readonly required>
                                        {{$errors->first('username')}}
                                    </div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputEmail">Email</label>
                                        <input class="form-input" id="inputEmail" type="text" name="email" value="{{ $user->email }}" readonly required>
                                        {{$errors->first('email')}}
                                    </div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputLangSetting">Language Setting</label><br>
                                        <input id="inputLangSetting" type="radio" name="language_setting" {{ empty($user->language_setting) || $user->language_setting == 'en' ? 'checked' : ''}} value="en"> English
                                        <input id="inputLangSetting2" type="radio" name="language_setting" {{ $user->language_setting == 'de' ? 'checked' : ''}} value="de"> Deutsch
                                    </div>
                                </fieldset>

                                <fieldset style="border-top: 1px solid #eeeeee;">
                                    <legend><strong>Change Password</strong></legend>

                                    <div class="block-half">
                                        <label class="form-label" for="inputCurrentPassword">Current password</label>
                                        <input class="form-input" id="inputCurrentPassword" type="password" name="current_password" value="">
                                        {{$errors->first('current_password')}}
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputNewPassword">New password</label>
                                        <input class="form-input" id="inputNewPassword" type="password" name="new_password" value="">
                                        {{$errors->first('new_password')}}
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputConfirmPassword">Confirm new password</label>
                                        <input class="form-input" id="inputConfirmPassword" type="password" name="new_password_confirm">
                                        {{$errors->first('new_password_confirm')}}
                                    </div>
                                </fieldset>

                                <button type="submit" class="btn btn--tosca">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- user account -->
@stop

@section('custom-modal')
    <div class="avatar-crop-modal">
        <div class="avatar-crop-modal-dialog">
            <button class="avatar-crop-modal-close">&times;</button>
            <div class="avatar-crop-field"></div>

            <div class="text-center">
                <button class="avatar-crop-modal-set-btn btn btn--tosca">Save</button>
            </div>
        </div>
    </div>
@stop
