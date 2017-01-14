@extends('layout')

@section('content')

<link href="{{ $asset('assets/css/double.css') }}" rel="stylesheet">
<link href="{{ $asset('assets/css/ref.css') }}" rel="stylesheet">
<div class="content">

    <div class="page-content">
        <div class="game-info-wrap">
            <div class="game-info">
                <div class="game-info-title">
                    <div class="left-block" style="padding-top: 4px;">
                        <div class="text-wrap">
                            <span class="color-orange">игра</span>
                            <span class="weight-normal">#</span>
                            <span id="roundId" class="color-white">{{ $gameid }}</span>
                        </div>
                    </div>
                    <span class="divider weight-normal"></span>
                    <div class="right-block" style="padding-top: 5px;">
                        <div class="text-wrap">
                            <span class="color-orange">банк</span>
                            <span class="weight-normal">:</span>
                            <span id="roundBank" class="color-white">{{ $data['rb'] }} <span class="money" style="color: #b3e5ff;">руб</span></span>
                        </div>
                    </div>
                </div>

                <div id="barContainer" style="display:none;" class="bar-container">
                    <div class="item-bar-wrap" style="width: 820px;">
                    <div style="padding-top: 8px;" class="item-bar-text"><span id="double_txt" ></span></div>
                        <div class="item-bar" style="width: 0%;"></div>
                    </div>
                    <div class="timer-new" id="gameTimer">
                        <span style="padding-top: 18px;" class="countMinutes">00</span>
                        <span class="countDiv">:</span>
                        <span style="padding-top: 18px;" class="countSeconds">35</span>
                    </div>
                </div>


            </div>
        </div>
        @if(!Auth::guest())
            @if($u->is_admin==1)
            <div class="participate-block" style="margin-top: 2px; margin-bottom: 1px;">
                <div style="float: left">
                    <span class="icon-arrow-right" style="float: left;margin: 0px 20px 0px 0px;"></span>
                    <form action="/dwinner" method="GET" style="float: left;padding: 10px 20px 11px;">
                        <div class="form">
                            <input textarea placeholder="Выберите число" name="id" cols="50" maxlength="2" placeholder="" value="" autocomplete="off" style="height: 37px;" class="coupon_post">
                            <input type="submit" id="submit" class="btn-add-balance2 " style="height: 37px;width: 200px;font-size: 13px;font-weight: 400;letter-spacing: 0.2px;text-transform: uppercase;" value="Подкрутить">
                        </div>
                    </form>
                    <div class="card-or-item" style="padding: 0px 25px 0px 25px;">{{ $am }}</div>
                    <a id="depositButton" href="/admin" target="_blank" class=" add-deposit ">Админпанель</a>

                    <span class="icon-arrow-left" style="margin: 0px 0px 0px 25px;"></span>
                </div>
            </div>
            @else
                </br>
            @endif
        @endif
        <div id="DoubleCarouselConatiner" class="DoubleCarouselConatiner">
            <div class="DoubleCarouselLine" style="left: 10px;"></div>
            <div class="DoubleCarouselLine" style="left: 525px;"></div>
            <div class="DoubleCarouselLine" style="left: 1020px;"></div>
            <div id="DoubleCarousel" class="DoubleCarousel"></div>
        </div>
        @if(!Auth::guest())
        <div class="form-group" style="margin-top: 20px;">
            <form>
                <center id="dbuttons" style="padding-bottom: 40px;">
                    <span class="icon-arrow-right" style="left: 0px;top: 396px;position: absolute;"></span>
                    <div id="lastGames">
                        @foreach($games as $i)
                            @if ($i->type == 1)
                                <div class="redg">{{ $i->num }}</div>
                            @endif
                            @if ($i->type == 2)
                                <div class="greeng">{{ $i->num }}</div>
                            @endif
                            @if ($i->type == 3)
                                <div class="blackg">{{ $i->num }}</div>
                            @endif
                            
                        @endforeach
                    </div>
                    <input class="amount" style="float: left; margin-left:4px" placeholder="Ваша ставка..." id="betAmount">
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="clearr();">Clear</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="plus1();">+1</button>           
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="plus10();">+10</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="plus100();">+100</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="plus1000();">+1000</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="dev2();">1/2</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="x2();">x2</button>
                    <button type="button" style="float: left; margin-left:4px" class="balance-button" onclick="max();">Max</button>
                </center>
            </form>
            <form style="margin-top: 10px; margin-bottom: 15px;">
                <center>
                    <button type="button" style="width: 33%" class="red-button" onclick="red();">     1 - 7 <span style="font-size: 8px;">x2</span></button>
                    <button type="button" style="width: 33%" class="green-button" onclick="green();">     0 <span style="font-size: 8px;">x12</span></button>           
                    <button type="button" style="width: 33%" class="black-button" onclick="black();">     8 - 14 <span style="font-size: 8px;">x2</span></button>
                </center>
            </form>
        </div>
        @endif
        <div id="double-deposits">
            <div class="dep_block" id="dep_red">
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="mybetr">{{ $data['mr'] }}</div>
                </center>
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="tbetr">
                        Общая ставка: <span id="tbetcr">{{ $data['tr'] }}</span>
                    </div>
                </center>
                <ul class="dul" id="ddr">
                    @foreach($tr as $bet)
                        <li class="dl">
                            <div style="overflow: hidden;line-height:32px">
                                <a href="/user/{{ $users[$bet->user_id]->steamid64 }}" target="_blank">
                                    <div class="pull-left">
                                        <img class="rounded" src="{{ $users[$bet->user_id]->avatar }}"> <span style="color: white;">{{ $users[$bet->user_id]->username }}</span>
                                    </div>
                                    <div class="amount pull-right"><b style="color: white;">{{ $bet->price }}</b> </div>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="dep_block" id="dep_green">
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="mybetg">{{ $data['mg'] }}</div>
                </center>
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="tbetg">
                        Общая ставка: <span id="tbetcg">{{ $data['tg'] }}</span>
                    </div>
                </center>
                <ul class="dul" id="ddg">
                    @foreach($tg as $bet)
                        <li class="dl">
                            <div style="overflow: hidden;line-height:32px">
                                <a href="/user/{{ $users[$bet->user_id]->steamid64 }}" target="_blank">
                                    <div class="pull-left">
                                        <img class="rounded" src="{{ $users[$bet->user_id]->avatar }}"> <span style="color: white;">{{ $users[$bet->user_id]->username }}</span>
                                    </div>
                                    <div class="amount pull-right"><b style="color: white;">{{ $bet->price }}</b> </div>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="dep_block" id="dep_black">
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="mybetb">{{ $data['mb'] }}</div>
                </center>
                <center>
                    <div class="tbet" style="opacity: 1;visibility: visible;font-weight: initial;" id="tbetb">
                        Общая ставка: <span id="tbetcb">{{ $data['tb'] }}</span>
                    </div>
                </center>
                <ul class="dul" id="ddb">
                    @foreach($tb as $bet)
                        <li class="dl">
                            <div style="overflow: hidden;line-height:32px">
                                <a href="/user/{{ $users[$bet->user_id]->steamid64 }}" target="_blank">
                                    <div class="pull-left">
                                        <img class="rounded" src="{{ $users[$bet->user_id]->avatar }}"> <span style="color: white;">{{ $users[$bet->user_id]->username }}</span>
                                    </div>
                                    <div class="amount pull-right"><b style="color: white;">{{ $bet->price }}</b> </div>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
    </div>
</div>
@endsection