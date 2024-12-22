@extends('backend.layouts.master')

@section('admin-content')
    <div class="tension-container">
        <div class="tension">
            <div class="head">
                <h1 class="title">NHẬP DỮ LIỆU SỨC CĂNG</h1>
                <img src="/public/assets/images/logo/logo.png" alt="" class="tension-logo">
            </div>
            {{-- <form action="{{ url('admin/checkTension/complate') }}" method="POST"> --}}
            <form action="{{ route('admin.checkTension.complete') }}" method="POST">
                {{-- <form action="{{ route('admin.checkTension.view', ['id' => $id]) }}" method="POST"> --}}
                @csrf
                <div class="computer">
                    <span class="text">TÊN MÁY TÍNH</span>
                    <select name="selectComputer" class="computer-select" aria-label="Default select example">
                        <option value="MT-N013" selected>MT-N013</option>
                        <option value="MT-N014">MT-N014</option>
                        <option value="MT-N037">MT-N037</option>
                        <option value="MT-N038">MT-N038</option>
                        <option value="MT-N039">MT-N039</option>
                        <option value="MT-N040">MT-N040</option>
                    </select>
                    <button class="btn btn-reset">
                        <img src="/public/assets/images/pages/tension/reset.png" alt="" class="tension-logo">
                        <span>Reset</span>
                    </button>
                    <button class="btn btn-save">
                        <span>Lưu dữ liệu</span>
                    </button>
                    <a href="{{ url('admin/checkTension/view') }}" class="btn btn-export">
                        <span>Xem - Xuất</span>
                    </a>
                </div>
                <div class="tension-content">
                    <table>
                        <thead>
                            <tr>
                                <th class="clb">Chủng loại B</th>
                                <th>Kích cỡ dây</th>
                                <th class="tt-tc">Tải trọng TC<br>(Kg)</th>
                                <th class="tt-dd">Tải trọng DD</th>
                                <th class="visible-hide border-0">11111</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.25</td>
                                <td>0.5</td>
                                <td>&gt;=9</td>
                                <td class="bgc-C0FFC0 py-0">
                                    <input name="weight125" class="input-value" type="number" step="0.1"
                                        data-target="9">
                                </td>
                                <td class="td-color"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>0.85</td>
                                <td>&gt;=15</td>
                                <td class="bgc-C0FFFF">
                                    <input name="weight2" class="input-value" type="number" step="0.1"
                                        data-target="15">
                                </td>
                                <td class="td-color"></td>
                            </tr>
                            <tr>
                                <td>5.5</td>
                                <td>2</td>
                                <td>&gt;=29</td>
                                <td class="bgc-C0C0FF">
                                    <input name="weight55" class="input-value" type="number" step="0.1"
                                        data-target="29">
                                </td>
                                <td class="td-color"></td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="border-right-0"><strong>Áp lực khí:</strong></td>
                                <td rowspan="2" class="border-left-0">0.55~0.65Mpa</td>
                                <td class="border-bottom-0 pb-0">
                                    <input type="radio" id="checkOk" name="checkOk" value="OK">
                                    <label for="checkOk">OK</label>
                                </td>
                                <td class="result-all" colspan="2" rowspan="2">
                                    <input id="resultAll" class="resultAll" name="resultAll" type="text" value="">
                                </td>
                            </tr>
                            <tr>
                                <td class="border-top-0">
                                    <input type="radio" id="checkNg" name="checkNg" value="NG">
                                    <label for="checkNg">NG</label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    {{-- modal --}}
    <div id="loginmodal" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đăng nhập</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        <div class="form-group">
                            <label for="code">Mã code nhân viên</label>
                            <input type="text" class="form-control" id="code" placeholder="Nhập mã CODE">
                        </div>
                        <div class="form-group">
                            <label for="passw">Mật khẩu</label>
                            <input type="password" class="form-control" id="passw" placeholder="Mật khẩu"
                                autocomplete="false">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Thoát</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var allOK = true;
            $('.input-value').on('input', function() {
                $('.input-value').each(function() {
                    var inputValue = $(this).val();
                    var targetValue = $(this).data('target');
                    updateColor($(this), inputValue, targetValue);
                });

                var inputValue = $('.input-value').val();
                var targetValue = $('.input-value').data('target');
                 if (inputValue >= targetValue){
                    updateResult(true);
                 } else{
                    updateResult(false);
                 }
            });

            function updateColor(inputElement, inputValue, targetValue) {
                var color = (inputValue >= targetValue) ? '#0000ff' : '#ff0000';
                var text = (inputValue >= targetValue) ? 'OK' : 'NG';
                inputElement.closest('tr').find('.td-color').css('background-color', color);
                inputElement.closest('tr').find('.td-color').text(text);
            }

            $('input[type="radio"]').on('change', function() {
                var selectedValue = $(this).val();
                if ($(this).is(':checked') && selectedValue === 'OK') {
                    $('.input-value').on('input', function() {
                        var inputValue = $(this).val();
                        var targetValue = $(this).data('target');
                        var allOK = (inputValue >= targetValue);
                        if (allOK === true) {
                            allOK = true;
                            updateResult(allOK);
                        } else {
                            allOK = false;
                            updateResult(false);
                        }
                        updateResult(allOK);

                    });
                } else {
                    updateResult(false);
                // $('input[type="radio"]').not(this).prop('checked', false);

                }
                // updateResult(false);
                $('input[type="radio"]').not(this).prop('checked', false);
            });




            function updateResult(allOK) {
                var color = allOK ? '#0000ff' : '#ff0000';
                var text = allOK ? 'OK' : 'NG';
                $('.result-all').css('background-color', color);
                $('.resultAll').val(text);
            }

            $('.input-value').on('keydown', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    var inputs = $('.input-value');
                    var currentIndex = inputs.index(this);
                    var nextIndex = currentIndex + 1;
                    if (nextIndex < inputs.length) {
                        inputs[nextIndex].focus();
                    }
                }
            });
        });
    </script>
@endsection
