@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading">Reactivate Your Account</h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    {!!Form::open(['route' => 'frontend.reactivation.link', 'method' => 'get', 'class' => 'form form-validate text-left'])!!}
                        <div class="form__row bzg">
                            <div class="bzg_c" data-col="m3, l3">
                                <label for="email" class="form-label">Your Email Address<span class="form__required">*</span></label>
                            </div>
                            <div class="bzg_c" data-col="m7, l8">
                                <input id="email" type="email" name="email" class="form-input form-input--block" required>
                            </div>
                        </div>
                        <!-- form__row -->
                        <div class="form__row bzg">
                            <div class="bzg_c" data-col="m3, l3">
                                &nbsp;
                            </div>
                            <div class="bzg_c" data-col="m7, l8">
                                <button class="btn btn--big btn--orange text--U" type="submit">
                                    Re-Activate
                                </button>
                            </div>
                        </div>
                        &nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
