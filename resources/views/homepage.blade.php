<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Chat APP</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/css/chatstyle.css">
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
                           @if($user->unread)
                               <span class="pending">{{ $user->unread }}</span>
                           @endif
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
                        var pending = parseInt($('#' + data.from).find('.pending').html());
                        if (pending) {
                            $('#' + data.from).find('.pending').html(pending + 1);
                        } else {
                            $('#' + data.from).append('<span class="pending">1</span>');
                        }
                    }
                }
            });


            $('.user').click(function(){
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.pending').remove();
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
                        url: "message",
                        data: datastr,
                        cache: false,
                        success: function (data) {

                        },
                        error: function (jqXHR, status, err) {
                            $(".input-text input").val(message);
                            alert("Can't send message because it contains harmful url!");
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
