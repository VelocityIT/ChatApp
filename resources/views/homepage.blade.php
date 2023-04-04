<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Chat APP</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <style type="text/css">
         body{
         background-color: #f4f7f6;
         margin-top:20px;
         }
         .card {
         background: #fff;
         transition: .5s;
         border: 0;
         margin-bottom: 30px;
         border-radius: .55rem;
         position: relative;
         width: 100%;
         box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
         }
         .chat-app .people-list {
         width: 280px;
         position: absolute;
         left: 0;
         top: 0;
         padding: 20px;
         z-index: 7
         }
         .chat-app .chat {
         margin-left: 280px;
         border-left: 1px solid #eaeaea
         }
         .people-list {
         -moz-transition: .5s;
         -o-transition: .5s;
         -webkit-transition: .5s;
         transition: .5s
         }
         .people-list .chat-list li {
         padding: 10px 15px;
         list-style: none;
         border-radius: 3px
         }
         .people-list .chat-list li:hover {
         background: #efefef;
         cursor: pointer
         }
         .people-list .chat-list li.active {
         background: #efefef
         }
         .people-list .chat-list li .name {
         font-size: 15px
         }
         .people-list .chat-list img {
         width: 45px;
         border-radius: 50%
         }
         .people-list img {
         float: left;
         border-radius: 50%
         }
         .people-list .about {
         float: left;
         padding-left: 8px
         }
         .people-list .status {
         color: #999;
         font-size: 10px;
         white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
         }
         .chat .chat-header {
         padding: 15px 20px;
         border-bottom: 2px solid #f4f7f6
         }
         .chat .chat-header img {
         float: left;
         border-radius: 40px;
         width: 40px
         }
         .chat .chat-header .chat-about {
         float: left;
         padding-left: 10px
         }
         .chat .chat-history {
         padding: 20px;
         border-bottom: 2px solid #fff;
         height: 500px;

    overflow: auto;
         }
         .chat .chat-history ul {
         padding: 0
         }
         .chat .chat-history ul li {
         list-style: none;
         margin-bottom: 30px
         }
         .chat .chat-history ul li:last-child {
         margin-bottom: 0px
         }
         .chat .chat-history .message-data {
         margin-bottom: 15px
         }
         .chat .chat-history .message-data img {
         border-radius: 40px;
         width: 40px
         }
         .chat .chat-history .message-data-time {
         color: #434651;
         padding-left: 6px
         }
         .chat .chat-history .message {
         color: #444;
         padding: 18px 20px;
         line-height: 26px;
         font-size: 16px;
         border-radius: 7px;
         display: inline-block;
         position: relative
         }
         .chat .chat-history .message:after {
         bottom: 100%;
         left: 7%;
         border: solid transparent;
         content: " ";
         height: 0;
         width: 0;
         position: absolute;
         pointer-events: none;
         border-bottom-color: #fff;
         border-width: 10px;
         margin-left: -10px
         }
         .chat .chat-history .my-message {
         background: #efefef
         }
         .chat .chat-history .my-message:after {
         bottom: 100%;
         left: 30px;
         border: solid transparent;
         content: " ";
         height: 0;
         width: 0;
         position: absolute;
         pointer-events: none;
         border-bottom-color: #efefef;
         border-width: 10px;
         margin-left: -10px
         }
         .chat .chat-history .other-message {
         background: #e8f1f3;
         text-align: right
         }
         .chat .chat-history .other-message:after {
         border-bottom-color: #e8f1f3;
         left: 93%
         }
         .chat .chat-message {
         padding: 20px
         }
         .online,
         .offline,
         .me {
         margin-right: 2px;
         font-size: 8px;
         vertical-align: middle
         }
         .online {
         color: #86c541
         }
         .offline {
         color: #e47297
         }
         .me {
         color: #1d8ecd
         }
         .float-right {
         float: right
         }
         .clearfix:after {
         visibility: hidden;
         display: block;
         font-size: 0;
         content: " ";
         clear: both;
         height: 0
         }
         @media only screen and (max-width: 767px) {
         .chat-app .people-list {
         height: 465px;
         width: 100%;
         overflow-x: auto;
         background: #fff;
         left: -400px;
         display: none
         }
         .chat-app .people-list.open {
         left: 0
         }
         .chat-app .chat {
         margin: 0
         }
         .chat-app .chat .chat-header {
         border-radius: 0.55rem 0.55rem 0 0
         }
         .chat-app .chat-history {
         height: 300px;
         overflow-x: auto
         }
         }
         @media only screen and (min-width: 768px) and (max-width: 992px) {
         .chat-app .chat-list {
         height: 650px;
         overflow-x: auto
         }
         .chat-app .chat-history {
         height: 600px;
         overflow-x: auto
         }
         }
         @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
         .chat-app .chat-list {
         height: 480px;
         overflow-x: auto
         }
         .chat-app .chat-history {
         height: calc(100vh - 350px);
         overflow-x: auto
         }
         }
      </style>
   </head>
   <body>
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
      <div class="container">
         <div class="row clearfix">
            <div class="col-lg-12">
               <div class="card chat-app">
                  <div id="plist" class="people-list">

                     <ul class="list-unstyled chat-list mt-2 mb-0">

                        @foreach ($users as $user)

                        <li class="clearfix user" id="{{ $user->id }}" name="{{ $user->name }}" phone="{{ $user->phone }}">
                           <div class="about">
                              <div class="name">{{ $user->name }}</div>
                              <div class="status">{{ $user->phone }}</div>
                           </div>
                        </li>

                        @endforeach


                     </ul>
                  </div>
                  <div class="chat">
                     <div class="chat-header clearfix">
                        <div class="row">
                           <div class="col-lg-6">
                              <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                              </a>
                              <div class="chat-about">
                                 <h6 class="m-b-0" id="name"></h6>
                                 <small id="phone"></small>
                              </div>
                           </div>
                           <div class="col-lg-6 hidden-sm text-right">
                              <a href="/logout" class="btn btn-outline-warning"><i class="fa fa-sign-out"></i></a>
                           </div>
                        </div>
                     </div>


                     <div class="chat-history">
                        <ul class="m-b-0" id="messages">



                        </ul>
                     </div>


                     <div class="chat-message clearfix">
                        <div class="input-group mb-0 input-text">
                           <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-send"></i></span>
                           </div>
                           <input type="text" class="form-control submit" name="message" placeholder="Enter text here...">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
      <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

        var recieverId = '';
        var myId = "{{ Auth::id() }}";

        $(document).ready(function(){


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Pusher.logToConsole = true;

            var pusher = new Pusher('23c85b686682373fa34d', {
                cluster: 'ap2',
                forceTLS: true
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function (data) {
                if (myId == data.from) {
                    $('#' + data.to).click();
                } else if (myId == data.to) {
                    if (recieverId == data.from) {
                        $('#' + data.from).click();
                    } else {
                        // var pending = parseInt($('#' + data.from).find('.pending').html());
                        // if (pending) {
                        //     $('#' + data.from).find('.pending').html(pending + 1);
                        // } else {
                        //     $('#' + data.from).append('<span class="pending">1</span>');
                        // }
                    }
                }
            });


            $('.user').click(function(){
                $('.user').removeClass('active');
                $(this).addClass('active');
                recieverId = $(this).attr('id');
                // console.log("Clicked: " + recieverId);
                $('#name').html( $(this).attr('name'));
                $('#phone').html( $(this).attr('phone'));
                $.ajax({
                    type:"get",
                    url: "message/"+recieverId,
                    data: "",
                    cache:false,
                    success:function(data){
                        // console.log(data);
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                });

            });



            $(document).on('keyup', '.input-text input', function (e) {
                var message = $(this).val();
                if (e.keyCode == 13 && message != '' && recieverId != '') {
                    $(this).val('');
                    var datastr = "receiver_id=" + recieverId + "&message=" + message;
                    $.ajax({
                        type: "post",
                        url: "message", // need to create this post route
                        data: datastr,
                        cache: false,
                        success: function (data) {

                        },
                        error: function (jqXHR, status, err) {
                        },
                        complete: function () {
                            scrollToBottomFunc();
                        }
                    })
                }
            });


        });

        function scrollToBottomFunc() {
            $('.chat-history').animate({
                scrollTop: $('.chat-history').get(0).scrollHeight
            }, 50);
        }

    </script>
   </body>
</html>
