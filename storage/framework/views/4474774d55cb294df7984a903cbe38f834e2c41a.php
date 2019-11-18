<?php $__env->startSection('content'); ?>
    <div class="site-main-inner-padded">
        <div class="container">
            <h2 class="fancy-heading"><?php echo e(trans('label.login.title')); ?></h2>

            <div class="bzg">
                <div class="bzg_c" data-col="l6" data-offset="l3">
                    <?php echo Form::open(['route' => 'sessions.store', 'class' => 'text-left', 'data-validate']); ?>

                        <div class="block-half">
                            <label class="form-label sr-only" for="inputUsername"><?php echo e(trans('label.login.email')); ?></label>
                            <?php echo Form::email('email', null, ['id' => 'inputUsername', 'class'=>'form-input', 'required' => 'true', 'placeholder' => trans('label.login.email')]); ?>

                            <?php echo e($errors->first('email')); ?>

                        </div>
                        <div class="block-half">
                            <label class="form-label sr-only" for="inputPassword"><?php echo e(trans('label.login.password')); ?></label>
                            <?php echo Form::password('password', ['id' => 'inputPassword', 'class'=>'form-input', 'required' => 'true', 'placeholder' => trans('label.login.password')]); ?>

                            <?php echo e($errors->first('password')); ?>

                        </div>
                        <button class="btn btn--block btn--tosca block-half" type="submit"><?php echo e(trans('label.login.title')); ?></button>

                        <p class="text-center block-half"><small><?php echo e(trans('label.login.newuser')); ?>? <a href="<?php echo e(route('frontend.user.registration')); ?>"><?php echo e(trans('label.login.registerhere')); ?></a>.</small></p>
                        <p class="text-center block-half"><small><?php echo e(trans('label.login.forgotpassword')); ?>? <a href="<?php echo e(route('frontend.user.forgetpassword')); ?>"><?php echo e(trans('label.login.clickhere')); ?></a>.</small></p>
                    </form>

                    <hr>

                    <div class="bzg">
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--fb block-half" href="<?php echo e(route('sessions.auth', ['app' => 'facebook'])); ?>">
                                <span class="fa fa-fw fa-facebook-official"></span>
                                <?php echo e(trans('label.login.fblogin')); ?>

                            </a>
                        </div>
                        <div class="bzg_c" data-col="m6">
                            <a class="btn btn--block btn--outline btn--gplus block-half" href="<?php echo e(route('sessions.auth', ['app' => 'google'])); ?>">
                                <span class="fa fa-fw fa-google-plus-official"></span>
                                <?php echo e(trans('label.login.googlelogin')); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sign-in -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>