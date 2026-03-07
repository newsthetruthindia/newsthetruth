@include('layouts.header')
@include('layouts.sidebar')
<div class="section-admin container-fluid res-mg-t-15">
    <div class="row admin text-center">
        <div class="col-md-12">
            <div class="row">
                @if( !empty( $data['posts'] ) )
                    <?php
                        $project_differ = $data['posts']['month_total'] * 100;
                        $project_differ_percent = $project_differ && $data['posts']['prev_month_total']? $project_differ/$data['posts']['prev_month_total'] : 0;
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn">
                            <h4 class="text-left text-uppercase"><b>Posts</b></h4>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="col-xs-3 mar-bot-15 text-left">
                                    <label class="label bg-green">
                                        {{$project_differ_percent}}%
                                        @if( $data['posts']['month_total'] > $data['posts']['prev_month_total'] )
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-level-down" aria-hidden="true"></i>
                                        @endif
                                    </label>
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin">{{$data['posts']['month_total']}}</h2>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: {{$project_differ_percent}}%;" class="progress-bar bg-green"></div>
                            </div>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="col-xs-3 mar-bot-15 text-left">
                                    Total
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin">{{$data['posts']['total']}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if( !empty( $data['categories'] ) )
                    <?php
                        $tasks_differ = $data['categories']['month_total'] * 100;
                        $tasks_differ_percent = $tasks_differ && $data['categories']['prev_month_total']?$tasks_differ/$data['categories']['prev_month_total'] : 0;
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <h4 class="text-left text-uppercase"><b>Categories</b></h4>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="text-left col-xs-3 mar-bot-15">
                                    <label class="label bg-red">
                                        {{$tasks_differ_percent}}%
                                        @if( $data['categories']['month_total'] > $data['categories']['prev_month_total'] )
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-level-down" aria-hidden="true"></i>
                                        @endif
                                    </label>
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin"></h2>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: {{$tasks_differ_percent}}%;" class="progress-bar progress-bar-danger bg-red"></div>
                            </div>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="col-xs-3 mar-bot-15 text-left">
                                    Total
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin">{{$data['categories']['total']}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if( !empty( $data['user'] ) )
                    <?php
                        $user_differ = $data['user']['month_total'] * 100;
                        $user_differ_percent = $user_differ && $data['user']['prev_month_total']? $user_differ/$data['user']['prev_month_total']:0;
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <h4 class="text-left text-uppercase"><b>Users</b></h4>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="text-left col-xs-3 mar-bot-15">
                                    <label class="label bg-blue">
                                        {{$user_differ_percent}}%
                                        @if( $data['user']['month_total'] > $data['user']['prev_month_total'] )
                                            <i class="fa fa-level-up" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-level-down" aria-hidden="true"></i>
                                        @endif</label>
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin"></h2>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: {{$user_differ_percent}}%;" class="progress-bar bg-blue"></div>
                            </div>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="col-xs-3 mar-bot-15 text-left">
                                    Total
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin">{{$data['user']['total']}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if( !empty( $data['published_posts'] ) )
                    <?php
                        $completask_differ = $data['published_posts']['month_total'] * 100;
                        $completask_differ_percent = $completask_differ && $data['published_posts']['prev_month_total']? $completask_differ/$data['published_posts']['prev_month_total']:0;
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <h4 class="text-left text-uppercase"><b>Published Post</b></h4>
                            <div class="row vertical-center-box vertical-center-box-tablet">
                                <div class="text-left col-xs-3 mar-bot-15">
                                    <label class="label bg-purple">80% <i class="fa fa-level-up" aria-hidden="true"></i></label>
                                </div>
                                <div class="col-xs-9 cus-gh-hd-pro">
                                    <h2 class="text-right no-margin">{{ count($data['published_posts']) }}</h2>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 60%;" class="progress-bar bg-purple">{{$data['published_posts']['month_total']}}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="product-sales-area mg-tb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-sales-chart">
                    <div class="portlet-title">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="caption pro-sl-hd">
                                    <span class="caption-subject text-uppercase"><b>Posts Graph</b></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="actions graph-rp">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-grey active">
                                            <input type="radio" name="options" class="toggle" id="option1" checked="">Today</label>
                                       <!--  <label class="btn btn-grey">
                                            <input type="radio" name="options" class="toggle" id="option2">Week</label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="curved-line-chart" class="flot-chart-sts flot-chart curved-chart-statistic"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="calender-area mg-tb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="calender-inner">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer-copyright-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright © 2018 <a href="#">News The Truth</p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')