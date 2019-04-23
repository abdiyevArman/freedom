@extends('admin.layout.layout')

@section('content')


    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Статистика</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Опубликованные новости</h4>
                        <div class="text-right">
                            <h2 class="font-light m-b-0"><i class="ti-arrow-up text-success"></i> {{\App\Models\News::where('is_show',1)->count()}}</h2>
                            <span class="text-muted"></span>
                        </div>
                        <span class="text-success">100%</span>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">Неопубликованные новости</h4>
                        <div class="text-right">
                            <h2 class="font-light m-b-0"><i class="ti-arrow-up text-info"></i> {{\App\Models\News::where('is_show',0)->count()}}</h2>
                            <span class="text-muted"></span>
                        </div>
                        <span class="text-info">100%</span>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <!-- Column -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex flex-wrap">
                                    <div>
                                        <h3>Посещаемость</h3>
                                        <h6 class="card-subtitle">January 2017</h6> </div>
                                    <div class="ml-auto ">
                                        <ul class="list-inline">
                                            <li>
                                                <h6 class="text-muted"><i class="fa fa-circle m-r-5 text-success"></i>Product A</h6> </li>
                                            <li>
                                                <h6 class="text-muted"><i class="fa fa-circle m-r-5 text-info"></i>Product B</h6> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="total-revenue4" style="height: 350px;"></div>
                            </div>
                            <div class="col-lg-3 col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">$54578</h1>
                                <h6 class="text-muted">Total Revenue</h6></div>
                            <div class="col-lg-3 col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">$43451</h1>
                                <h6 class="text-muted">Online Revenue</h6></div>
                            <div class="col-lg-3 col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">$44578</h1>
                                <h6 class="text-muted">Product A</h6></div>
                            <div class="col-lg-3 col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">$12578</h1>
                                <h6 class="text-muted">Product B</h6></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                <div class="r-panel-body">
                    <ul id="themecolors" class="m-t-20">
                        <li><b>With Light sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                        <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                        <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                        <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                        <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                        <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                        <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                        <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                        <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                        <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                    </ul>
                    <ul class="m-t-20 chatonline">
                        <li><b>Chat option</b></li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><img src="/assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>

@endsection


@section('js')

<script src="/assets/plugins/chartist-js/dist/chartist.min.js"></script>
<script src="/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
        <!-- Chart JS -->
    <script src="/assets/plugins/echarts/echarts-all.js"></script>
    <script src="/assets/plugins/toast-master/js/jquery.toast.js"></script>
            <!-- Chart JS -->
    <script src="/js/dashboard1.js"></script>
    <script src="/js/toastr.js"></script>
    <script src="/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>

@endsection