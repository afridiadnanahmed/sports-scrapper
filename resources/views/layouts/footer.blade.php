<!-- footer -->
<footer class="main-footer">
    <div class="custom-container">
        <div class="row">
            <div class="col-md-8 center-content">
                <div class="row">
                    <div class="col-xs-4 mobile-block">
                        <h3>Quick Links</h3>
                        <ul class="list-unstyled">
                            <li><a href="#"><img src="{{asset('images/feedback.svg')}}" alt="" class="icons">Feedback</a></li>
                            <li><a href="#"><img src="{{asset('images/about-icon-01.svg')}}" alt="" class="icons">About us</a></li>
                            <li><a href="#"><img src="{{asset('images/trems.svg')}}" alt="" class="icons">Terms and conditions</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-4 mobile-block">
                        <h3>Follow Us</h3>
                        <ul class="list-unstyled">
                            <li><a href="#"><img src="{{asset('images/facebook_footer.svg')}}" alt="" class="icons">Facebook</a></li>
                            <li><a href="#"><img src="{{asset('images/twitter_footer.svg')}}" alt="" class="icons">Twitter</a></li>
                        </ul>
                    </div>
                    <div class="col-xs-4 mobile-block">
                        <h3>Download App</h3>
                        <ul class="list-unstyled">
                            <li><a href="#"><img src="{{asset('images/android_footer.svg')}}" alt="" class="icons">Android App</a></li>
                            <li><a href="#"><img src="{{asset('images/iOS_footer.svg')}}" alt="" class="icons">iOS App</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p class="copy-rights">© All Right Reserved LTV Sports 2017</p>
            </div>
        </div>
    </div>
</footer>
<!-- /footer -->

<!--login modal-->
<div class="login-popup">
    <div class="modal fade primary-modal" id="login-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <button type="button" class="close-btn" data-dismiss="modal"><img src="images/close-icon.svg" alt=""></button>
                    <h2 class="modal-heading">Welcome</h2>
                    <p class="modal-para">Lorem ipsum asdlnalsk lknalsdkna lsndljasd Lorem ipsum asdlnalsk lknalsdkna lsnd</p>
                    <form action="" method="POST" class="form-horizontal text-center" role="form">
                        <div class="form-group">
                            <input type="text" name="user-name" id="input" class="form-control round-input" value="" required="required" pattern="" title="" placeholder="username">
                            <input type="password" name="passowrd" id="input" class="form-control round-input" value="" required="required" pattern="" title="" placeholder="Password">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="form-group remember-field">
                            <div class="col-sm-6">
                                <div class="checkbox checkbox-primary">
                                    <input id="checkbox2" type="checkbox" checked="">
                                    <label for="checkbox2">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="#">Forgot Password</a>
                            </div>
                        </div>

                    </form>
                    <h2 class="line-through m-t-47 m-b-30">
                        <span>Or Login with</span>
                    </h2>
                    <div class="clearfix text-left">
                        <a href="#" class="f-btn socail-btn ">
                            <img src="images/f-icon.svg" alt="">
                            Facebook
                        </a>
                        <a href="#" class="g-btn socail-btn pull-right">
                            <img src="images/g-plus-icon.svg" alt="">
                            Google
                        </a>
                    </div>
                    <p class="signin-para">Already have an account? <a href="#">Signin</a></p>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end login modal-->
