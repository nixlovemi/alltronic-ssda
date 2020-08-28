<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?=SITE_NAME?> - Login</title>
        <?=echoHeader()?>
    </head>
    <body class="bg-gradient-login">
        <!-- Login Content -->
        <div class="container-login">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card shadow-sm my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="login-form">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                        </div>
                                        <form id='loginForm' class="user" method="post" action="<?=BASE_URL?>execLogin">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" />
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                                            </div>
											<?php
											/*
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck">
                                                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                                                </div>
                                            </div>
											*/
											?>
                                            <div class="form-group">
                                                <a href="javascript:;" onClick="$('#loginForm').submit()" class="btn btn-primary btn-block">Entrar</a>
                                            </div>
											<?php
											echo MessageBox::showMessage();
											?>
                                            <?php
											/*
											<hr>
                                            <a href="index.html" class="btn btn-google btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                            </a>
                                            <a href="index.html" class="btn btn-facebook btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                            </a>
											*/
											?>
                                        </form>
                                        <?php
										/*
										<hr>
                                        <div class="text-center">
                                            <a class="font-weight-bold small" href="register.html">Create an Account!</a>
                                        </div>
										*/
										?>
                                        <div class="text-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Login Content -->
        <?=echoFooter()?>
    </body>
</html>
