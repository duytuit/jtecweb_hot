@extends('frontend.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ config('app.description') }}
@endsection

@section('main-content')
    <main class="main">
        <!-- Page Content -->
        <div class="container">
            <form id="examForm">
                {{-- <a href="javascript:;" class="abc123">test</a> --}}
                <input name="type" type="hidden" value="{{ Request::query('type') }}">
                <div>
                    <div>
                        <strong style=" text-transform: uppercase;">{{$arrayExamPd['title']}}</strong>
                    </div>
                </div>
                <div>
                    <div>
                        <strong>Tiêu chuẩn đánh giá</strong>
                    </div>
                    {!!$arrayExamPd['description']!!}
                    {{-- <i>Điểm đạt: 96->100 điểm</i><br>
                    <i>Từ 90->95 điểm: kiểm tra lại sau 2 ngày (nếu không đạt sẽ được đào tạo lại)</i><br>
                    <i>Dưới 90 điểm: Không đạt ( đào tạo lại màu dây 1 tuần)</i><br> --}}
                    <i>Thời gian làm bài <strong>{{$arrayExamPd['time']}}:00</strong></i><br>
                </div>
                <div>
                    {{-- <div>
                        Công đoạn:
                    </div>
                    <div>
                        <select class="form-control" name="congdoan">
                            <option value="1" selected>Cắm</option>
                        </select>
                    </div> --}}
                    <div>
                        Mã nhân viên:
                    </div>
                    <div class="form-group">
                        <input type="text" name="manhanvien" value="{{ Request::query('code') }}" class="form-control"
                            readonly>
                    </div>
                    @if ($arrayExamPd['newbie'])
                        <div>
                            <input name="newbie" id="quantityUnused"
                                style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                value="1" required checked
                                data-parsley-error-message="Vui lòng chọn một loại số lượng.">
                            <label for="quantityUnused">Công nhân kiểm tra định kỳ</label>
                            <input name="newbie" id="quantityUsed"
                                style="width:20px;height:20px; vertical-align: middle;" type="radio"
                                value="2" required>
                            <label for="quantityUsed">Công nhân mới gia nhập</label>
                        </div>
                    @endif
                    <div>
                        Ngày kiểm tra:
                    </div>
                    <div class="form-group">
                        <input type="date" id="datePicker" name="ngaykiemtra" value="{{ time() }}"
                            class="form-control" style="width:100%">
                        <input type="hidden" id="count_timer" name="count_timer" value="{{ date('Y-m-d H:i:s') }}">
                    </div>
                </div>
                <div class="cards map_question">
                    @php
                        $groupExam = $arrayExamPd['data'];
                        $newGroupExam = [];
                    @endphp
                    @foreach ($groupExam as $index_1 => $groupItem)
                        @php

                            $array_exam = $groupItem['questions'];
                            shuffle($array_exam);
                            $groupItem['questions'] = $array_exam;
                            $newGroupExam[] = $groupItem;
                        @endphp
                        @foreach ($array_exam as $index => $item)
                            <a href="javascript:;" id="label_{{ $item['id'] }}" class="map_item" data-id="{{ $item['id'] }}"
                                data-value="{{ $item['answer'] }}" onclick="getMapQuestion({{ $item['id'] }})">
                                <strong>{{($index_1+1).'.'}}</strong>{{$index + 1 }}
                            </a>
                        @endforeach
                    @endforeach
                    <div class="form-group ml-1">
                        <button class="btn btn-primary font-weight-bold btn-custom examSubmit">Nộp bài</button>
                    </div>
                </div>

                @foreach ($newGroupExam as $key_3 => $groupItem)
                    @php
                        $array_exam = $groupItem['questions'];
                    @endphp
                    @if ($groupItem['group'])
                        {!!$groupItem['group']!!}
                    @endif
                    <div class="cards">
                        @foreach ($array_exam as $index => $item)
                            <div class="cards_item" style="width:calc((100%/{{$groupItem['width']}}) - 0.1px)" id="{{ $item['id'] }}">
                                <div class="card_question">
                                    <div class="form-group">
                                        <div><strong>Câu {{ $index + 1 }} :
                                            </strong><strong>{!! $item['show_question'] == 1 ? $item['name'] : '' !!}</strong>
                                        </div>
                                        @if ($item['path_image'])
                                          <div> <img src="{{ asset($item['path_image']) }}" alt="" width="{{$item['width_image']}}%" /></div>
                                        @endif
                                    </div>
                                    @php
                                        $array_Answer = $item['answer_list'];
                                        shuffle($array_Answer);
                                    @endphp
                                    <div style="display: flex;flex-flow: column wrap;align-content: flex-start;justify-content: space-between;{{$item['max-height'] ? 'max-height: 250px;':'' }}">
                                        @foreach ($array_Answer as $index1 => $item1)
                                            <div @if ($item['answer'] == $item1) class="right_answer" @endif><label
                                                    for="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ $index1 }}" style="margin-right: 30px;">
                                                    @if ($groupItem['point'] == 24)
                                                      <span><strong>B</strong></span>
                                                    @endif
                                                    @php
                                                        $newarray_answer = array_filter($item['answer_list'], fn ($element_1) => $element_1 == $item1);
                                                        $new_multiple_answer = array_filter($item['multiple_answer'], fn ($element_1) => $element_1 == $item1);
                                                    @endphp
                                                    @if ( count($item['multiple_answer']) > 0)
                                                        @if ($item['type_input'] == 'number')
                                                                <input
                                                                type="number" min="1" max="50" value="{{ $item1 }}"
                                                                name="answer[{{ $item['id'] }}][{{$item1}}]"
                                                                onclick="getCheck({{ $item['id'] }})"
                                                                id="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ $index1 }}" class="multiple_answer" data-answer_resuls="{{key($newarray_answer)}}">
                                                        @else
                                                                <input
                                                                type="checkbox" value="{{ $item1 }}"
                                                                onclick="getCheck({{ $item['id'] }})"
                                                                name="answer[{{ $item['id'] }}][]"
                                                                id="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ $index1 }}"
                                                                class="largerCheckbox multiple_answer_checkbox" data-answer_resuls="{{ current($new_multiple_answer)}}">
                                                                <strong> {{ $index1 + 1 }}. </strong>
                                                        @endif
                                                    @else
                                                        <input
                                                        type="radio" value="{{ $item1 }}"
                                                        onclick="getCheck({{ $item['id'] }})"
                                                        name="answer[{{ $item['id'] }}]"
                                                        id="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ $index1 }}"
                                                        class="largerCheckbox">
                                                        <strong> {{ $index1 + 1 }}. </strong>
                                                    @endif
                                                    @if ( $item['answer_image_width'] > 0)
                                                        <img src="{{ asset($item1) }}" width="{{$item['answer_image_width']}}%" />
                                                    @else
                                                        {!! $item1 !!}
                                                    @endif
                                                    @if ( count($item['answer_image']) > 0)
                                                        <div>
                                                            <img src="{{ asset($item['answer_image'][key($newarray_answer)]) }}" width="{{$item['width_image']}}%" style="height:250px;" />
                                                        </div>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                        @if (count($item['answer_last']) > 0 )
                                        <div @if ($item['answer'] == $item['answer_last'][0]) class="right_answer" @endif>
                                            <label for="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ count($array_Answer) }}" style="margin-right: 30px;">
                                                <input
                                                type="radio" value="{{ $item['answer_last'][0] }}"
                                                onclick="getCheck({{ $item['id'] }})"
                                                name="answer[{{ $item['id'] }}]"
                                                id="group_{{$key_3}}_cau__{{ $item['id'] }}_answer_{{ count($array_Answer)  }}"
                                                class="largerCheckbox">
                                                <strong> {{ count($array_Answer) + 1 }}. </strong>
                                                {!! $item['answer_last'][0] !!}
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    <br>
                                </div>
                            </div>
                        @endforeach
                    </div>
                 @endforeach
                <div class="container">
                    <div class="form-group">
                        <button class="btn btn-secondary font-weight-bold examSubmit" style="margin-top: 50px">Nộp bài</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="time_count_down">
            <div id="countdown">
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script src="{{ asset('public/assets/frontend/js/confetti.browser.min.js') }}"></script>
    <script>
        $(".abc123").click(function() {
            swal("Chúc mừng bạn đã đạt: 100");
        });

        function getCheck(params) {
            $("#label_" + params).css("background-color", "blueviolet")
        }

        function getMapQuestion(id) {
            console.log(id);
            $('html, body').animate({
                scrollTop: $("#" + id).offset().top
            }, 1000);
            $(".cards_item").css("background-color", "transparent")
            $("#" + id).css("background-color", "#dee2e6")
        }
        $('.examSubmit').click(function(e) {
            e.preventDefault();
            // console.log($(this).text());
            if ($(this).text() == "Nộp bài") {
                submit_exam()
            } else {
                location.reload();
            }
        });
        document.getElementById('datePicker').valueAsDate = new Date();
        var timeInSecs;
        var ticker;

        function startTimer(secs) {
            timeInSecs = parseInt(secs);
            ticker = setInterval("tick()", 1000);
        }

        function submit_exam() {
            console.log('đã đóng');
            $('.examSubmit').prop("disabled", true);
            setTimeout(() => {
                $('.examSubmit').prop('disabled', false);
                console.log('đã mở');
            }, 2000)
            // Get all the forms elements and their values in one step
            var values = $('#examForm').serialize()
            console.log(values);
            $.ajax({
                url: "{{ route('exam.store') }}",
                method: 'POST',
                data: values,
                success: function(data) {
                    // if(data?.warning){
                    //     alert(data?.warning);
                    //     location.reload();
                    // }
                    $('.examSubmit').html('Làm lại');
                    $('.map_question a').each(function(i, obj) {
                        var member_answer = $('input[name="answer[' + $(this).data('id') +
                            ']"]:checked').val()
                        if (member_answer) {
                            if (member_answer != $(this).data('value')) {
                                $(this).css("background-color", "red")
                                $('input[name="answer[' + $(this).data('id') + ']"]:checked').css({
                                    "accent-color": "red"
                                })
                                $('input[name="answer[' + $(this).data('id') + ']"]:checked').parent()
                                    .css({
                                        "color": "red"
                                    })
                            } else {
                                $(this).css("background-color", "blue")
                            }
                        }
                    });
                    $('.multiple_answer').each(function(i, obj) {
                       var answer_resuls =  $(this).data('answer_resuls');
                       if(answer_resuls == $(this).val()){
                            $(this).parent()
                            .css({
                                "color": "blue"
                            })
                       }else{
                            $(this).parent()
                            .css({
                                "color": "red"
                            })
                       }

                    });
                    $('.multiple_answer_checkbox').each(function(i, obj) {
                       var answer_resuls =  $(this).data('answer_resuls');
                       if(answer_resuls == $(this).val()){
                            $(this).parent()
                            .css({
                                "color": "blue"
                            })
                       }else{
                            $(this).parent()
                            .css({
                                "color": "red"
                            })
                       }

                    });
                    console.log(data.groupQuestion);
                    if (data.status == "success") {
                        if(data.message){
                            swal(data.message);
                            return;
                        }
                        $(".right_answer").css("color", "blue");

                        if (data.exam.scores > data.groupQuestion.scores[0]) {
                            swal("SỐ ĐIỂM CỦA BẠN LÀ: " + data.exam.scores + " "+data.groupQuestion.description);

                            var end = Date.now() + (2 * 1000);
                            var colors = ['#bb0000', '#F7FF0B', '#D05DD1', '#0D9EE6', '#fff', '#8CFF68'];
                            var pumpkin = confetti.shapeFromPath({
                                path: 'M449.4 142c-5 0-10 .3-15 1a183 183 0 0 0-66.9-19.1V87.5a17.5 17.5 0 1 0-35 0v36.4a183 183 0 0 0-67 19c-4.9-.6-9.9-1-14.8-1C170.3 142 105 219.6 105 315s65.3 173 145.7 173c5 0 10-.3 14.8-1a184.7 184.7 0 0 0 169 0c4.9.7 9.9 1 14.9 1 80.3 0 145.6-77.6 145.6-173s-65.3-173-145.7-173zm-220 138 27.4-40.4a11.6 11.6 0 0 1 16.4-2.7l54.7 40.3a11.3 11.3 0 0 1-7 20.3H239a11.3 11.3 0 0 1-9.6-17.5zM444 383.8l-43.7 17.5a17.7 17.7 0 0 1-13 0l-37.3-15-37.2 15a17.8 17.8 0 0 1-13 0L256 383.8a17.5 17.5 0 0 1 13-32.6l37.3 15 37.2-15c4.2-1.6 8.8-1.6 13 0l37.3 15 37.2-15a17.5 17.5 0 0 1 13 32.6zm17-86.3h-82a11.3 11.3 0 0 1-6.9-20.4l54.7-40.3a11.6 11.6 0 0 1 16.4 2.8l27.4 40.4a11.3 11.3 0 0 1-9.6 17.5z',
                                matrix: [0.020491803278688523, 0, 0, 0.020491803278688523, -
                                    7.172131147540983, -5.9016393442622945
                                ]
                            });
                            var tree = confetti.shapeFromPath({
                                path: 'M120 240c-41,14 -91,18 -120,1 29,-10 57,-22 81,-40 -18,2 -37,3 -55,-3 25,-14 48,-30 66,-51 -11,5 -26,8 -45,7 20,-14 40,-30 57,-49 -13,1 -26,2 -38,-1 18,-11 35,-25 51,-43 -13,3 -24,5 -35,6 21,-19 40,-41 53,-67 14,26 32,48 54,67 -11,-1 -23,-3 -35,-6 15,18 32,32 51,43 -13,3 -26,2 -38,1 17,19 36,35 56,49 -19,1 -33,-2 -45,-7 19,21 42,37 67,51 -19,6 -37,5 -56,3 25,18 53,30 82,40 -30,17 -79,13 -120,-1l0 41 -31 0 0 -41z',
                                matrix: [0.03597122302158273, 0, 0, 0.03597122302158273, -
                                    4.856115107913669, -5.071942446043165
                                ]
                            });
                            var heart = confetti.shapeFromPath({
                                path: 'M167 72c19,-38 37,-56 75,-56 42,0 76,33 76,75 0,76 -76,151 -151,227 -76,-76 -151,-151 -151,-227 0,-42 33,-75 75,-75 38,0 57,18 76,56z',
                                matrix: [0.03333333333333333, 0, 0, 0.03333333333333333, -
                                    5.566666666666666, -5.533333333333333
                                ]
                            });
                            var star = confetti.shapeFromPath({
                                path: 'm21.15,26.34l-4.58-2.32c-.43-.22-.93-.21-1.35.02l-4.51,2.46c-1.06.58-2.32-.31-2.13-1.5l.79-5.08c.07-.47-.09-.95-.44-1.28l-3.73-3.53c-.88-.83-.42-2.3.77-2.49l5.07-.81c.47-.08.88-.38,1.08-.81l2.2-4.64c.52-1.09,2.06-1.11,2.61-.04l2.34,4.57c.22.43.63.72,1.11.78l5.09.66c1.19.16,1.69,1.61.84,2.47l-3.62,3.64c-.34.34-.49.82-.4,1.29l.94,5.05c.22,1.18-1.01,2.11-2.09,1.56Z',
                            });
                            (function frame() {
                                confetti({
                                    scalar: 1.5,
                                    particleCount: 5,
                                    angle: 60,
                                    spread: 155,
                                    origin: {
                                        x: 0,
                                        y: 1
                                    },
                                    colors: colors,
                                    zIndex: 999999,
                                    shapes: [heart, tree, pumpkin, star],
                                });
                                confetti({
                                    scalar: 1.5,
                                    particleCount: 5,
                                    angle: 120,
                                    spread: 155,
                                    origin: {
                                        x: 1,
                                        y: 1
                                    },
                                    colors: colors,
                                    zIndex: 999999,
                                    shapes: [heart, tree, pumpkin, star],
                                });

                                if (Date.now() < end) {
                                    requestAnimationFrame(frame);
                                }
                            }());
                        } else {
                            if(data.exam.scores > data.groupQuestion.scores[1]){
                                swal("SỐ ĐIỂM CỦA BẠN LÀ: " + data.exam.scores + " "+data.groupQuestion.messager[2]);
                            }else{
                                swal("SỐ ĐIỂM CỦA BẠN LÀ: " + data.exam.scores + " "+data.groupQuestion.messager[3]);
                            }

                        }
                    }
                }
            });
        }

        function tick() {
            var secs = timeInSecs;
            if (secs > 0) {
                timeInSecs--;
            } else {
                clearInterval(ticker);
                if ($('.examSubmit').first().text() == "Nộp bài") {
                    submit_exam()
                }
                //submit_exam()
                //$(".examSubmit").trigger('click');
                //startTimer(1*60); // 4 minutes in seconds
            }

            var mins = Math.floor(secs / 60);
            secs %= 60;
            var pretty = ((mins < 10) ? "0" : "") + mins + ":" + ((secs < 10) ? "0" : "") + secs;
            document.getElementById("countdown").innerHTML = pretty;
        }

        startTimer({{$arrayExamPd['time']}} * 60); // 4 minutes in seconds
    </script>
@endsection
