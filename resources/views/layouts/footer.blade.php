@if(auth()->check())
<div class="js-fix-footer2 bg-white border-top-2 position-relative">
    <div class="container page__container page-section d-flex flex-column p-3">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                    <div class="avatar avatar-xl mr-8pt">
                        <a href="{{ config('app.url') }}" class="">
                            <img src="{{ asset('/assets/img/logos/footer-bar-logo.png') }}" alt="logo" class="avatar-img rounded" style="width: 80%;" />
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="d-flex flex-column">
                            <small class="js-lists-values-project">Copyright 2020 &copy; All rights reserved.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="text-center pt-32pt">
                    <a href="{{ config('app.url') }}" class=" text-100">
                        <strong>www.tutebuddy.com</strong>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="text-center pt-32pt">
                    <a href="/page/terms-and-conditions" class="text-100">Terms of Service</a> | <a href="/page/support"  class="text-100">Support</a>
                </div>
            </div>
        </div> 
    </div>
</div>
@endif
