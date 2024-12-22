<template>
    <div>
        <div class="row">
            <div class="col-12">
                <!-- filter -->
                <form style="margin: 0;" id="form-search-advance" action="" method="get" class="hidden">
                    <div id="search-advance" class="search-advance">
                        <div class="row">
                            <div class="col-sm-1">
                                <h4 class="page-title" style="margin-top: 5px;">{{ ware_houses.total }} Yêu cầu</h4>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="keyword" value="" placeholder="Nhập từ khóa" class="form-control" />
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="date" v-model="filterWareHouseForm.search_date" class="form-control" name="search_date" id="search_date" placeholder="Ngày yêu cầu">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="type" id="order_type" v-model="filterWareHouseForm.type" >
                                    <option value="111" selected>Yêu Cầu Dây Điện và Tanshi</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" v-model="filterWareHouseForm.required_department_id"  name="required_department_id" id="required_department_id">
                                    <option value="">Bộ phận</option>
                                    <option v-for="_department in department_list" v-bind:value="_department.id">
                                        {{ _department.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="status" v-model="filterWareHouseForm.status"  class="form-control" style="width: 100%;">
                                    <option value="0">Chưa Xuất</option>
                                    <option value="1">Đã xuất</option>
                                    <option value="111">Trạng thái</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <select name="locations"  v-model="filterWareHouseForm.locations" class="form-control" style="width: 100%;">
                                    <option value="">Vị trí kho</option>
                                    <option value="1">Z->A</option>
                                    <option value="0">A->Z</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End filter -->
                <div class="card">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Trạng thái</th>
                                        <th>Mã linh kiện</th>
                                        <th width="70">Kho</th>
                                        <th width="20">Máy yc</th>
                                        <th width="50">Số cuộn</th>
                                        <th width="60">Số lượng</th>
                                        <th width="60">Tồn kho</th>
                                        <th width="20">Kích thước</th>
                                        <th width="120">Ghi chú</th>
                                        <th>Bộ phận yêu cầu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="_ware_houses in ware_houses.data" class="list_content">
                                        <td style="width: 200px;">
                                            <div class="information-export">
                                                <div style="display: flex;gap: 0.2em;justify-content: center;">
                                                    <div v-if="_ware_houses.content_form?.confirm_by">
                                                        <button type="button" class="btn btn-outline-success"
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom"
                                                            title={{_ware_houses.content_form?.confirm_by_full_name + <br>"Duyệt lúc:" + _ware_houses.content_form?.confirm_date}}>
                                                            <i class="fa fa-check" style="color: green;"></i>
                                                        </button>
                                                    </div>
                                                    <div style="width: 100%;display: grid;">
                                                        <div v-if="_ware_houses.status == 0">
                                                            <div class="btn btn-sm btn btn-danger w-100" @click="modal_form(this,_ware_houses)" >Xuất hàng</div>
                                                        </div>
                                                        <div v-else>
                                                            <div v-if="_ware_houses.content_form[0].quantity < _ware_houses.quantity_detail">
                                                                <div class="btn btn-sm btn-success w-100">Đã xuất hàng lẻ</div>
                                                            </div>
                                                            <div v-else>
                                                                <div class="btn btn-sm btn-primary w-100">Đã xuất đủ hàng</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div >
                                                        <a class="btn btn-primary text-light expand-collapse-icon collapse-toggle" @click="infoStatus($event)"></a>
                                                    </div>
                                                </div>
                                                <div class="collapse">
                                                    <div v-if="_ware_houses.confirm_form">
                                                        <div><strong>Xuất lần 1:</strong></div>
                                                        <div>Số lượng: {{_ware_houses.quantity}} {{ _ware_houses.content_form.unit_price?'('+_ware_houses.content_form.unit_price+')':''}}</div>
                                                        <div>Người xuất: <strong :set="confirm_form = JSON.parse(_ware_houses.confirm_form)">{{ confirm_form[0]?.full_name }}</strong></div>
                                                        <div>{{ formatDate(confirm_form[0]?.date)}}</div>
                                                        <div>{{ confirm_form[0]?.note}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 200px;">
                                            <div>
                                                <span class="tooltip-text">
                                                    <div class="tooltip-text-action">
                                                        <a class="btn" @click="copyText($event)">
                                                            <i class="fa fa-copy" style="color: blueviolet;"></i>
                                                        </a>
                                                        <a class="tooltip-text-alert">Sao chép mã</a>
                                                    </div>
                                                    <strong class="tooltip-text-title"><a href="javascript:;" @click="showInvoice(_ware_houses.accessory)">{{  _ware_houses.code }}</a></strong>
                                                </span>
                                            </div>
                                            <!-- {{ _ware_houses.signature_submission.find(x=>x.type == 2) }} -->
                                        </td>
                                        <td>{{ _ware_houses.location }}</td>
                                        <td style="background-color: bisque;font-weight: bold">{{ JSON.parse(_ware_houses.content_form).machine }}</td>
                                        <td style="font-weight: bold">
                                            <div>{{ _ware_houses.quantity }}</div>
                                        </td>
                                        <td>
                                            <input type="text" class="quantity" :id="'quantity_detail_' + _ware_houses.id" :value="_ware_houses.quantity_detail" style="width:80px">
                                        </td>
                                        <td style="color: #cbcbcb">
                                           {{ new Intl.NumberFormat().format( _ware_houses.accessory.accessory_dept ? JSON.parse(_ware_houses.accessory.accessory_dept).find(x=>x.location_c == '0111')?.inventory : 0)}}
                                        </td>
                                        <td> {{_ware_houses.size }}</td>
                                        <td>
                                            <div v-if="_ware_houses.order == 1"><span class="badge badge-danger">Hàng gấp</span></div>
                                            <div>{{ _ware_houses.content }}</div>
                                        </td>
                                        <td>
                                            <strong v-bind:style= "[_ware_houses.signature_submission.find(x=>x.type == 1 ).status == 0 ? {'color' : 'red'} : '']" >{{_ware_houses.department.name}}-{{ formatDate(_ware_houses.created_at) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <pagination-record :page-length.sync="filterWareHouseForm.page_length"
                                           :records="ware_houses"
                                           @updateRecords="getWareHouses"
                                           @change.native="getWareHouses">
                        </pagination-record> -->
                </div>
            </div>
        </div>
        <div id="form_confirm" class="modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin INVOICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>linh kiện</th>
                                <th>vị trí</th>
                                <th>số lượng</th>
                                <th>ngày tạo</th>
                                <th>thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="list_item_invoice">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<style>
    .expand-collapse-icon {
        font-size: 200px;
        width: 100%;
        height: 100%;
        position: relative;
        display: inline-block;
    }

    .expand-collapse-icon::before, .expand-collapse-icon::after {
        content: "";
        position: absolute;
        width: 1em;
        height: 0.3rem;
        top: calc((1em / 2) - .08em);
        background-color: white;
        transition: 0.3s ease-in-out all;
        border-radius: 0.03em;
        top: 11px;
        left: 2px;
        font-size: 20px;
    }

    .expand-collapse-icon::after {
        transform: rotate(90deg);
    }

    .collapsed.expand-collapse-icon::after {
        transform: rotate(180deg);
    }


    .collapsed.expand-collapse-icon::before {
        transform: rotate(90deg) scale(0);
    }
    .content-selected{
         background-color:aqua;
    }
</style>
<script>
    import datepicker from 'vuejs-datepicker'
    export default {
        components: {datepicker},
        data() {
            return {
                postForm: new Form({
                    id:0,
                    quantity:0
                }),
                ware_houses: {
                    total: 0,
                    data: []
                },
                filterWareHouseForm: {
                    page_length: 400,
                    locations: '',
                    status: 0,
                    type: 111,
                    search_date: new Date().toISOString().slice(0,10),
                    keyword: '',
                    required_department_id:''
                },
                department_list:[]
            };
        },
        mounted() {
            this.getWareHouses();
            $("#test_btn").click(function(){
                alert("tao vừa click")
            })
            $('input.date_picker').datepicker({
                autoclose: true,
                dateFormat: "dd-mm-yy"
            });

        },
        methods: {
            getWareHouses(page) {
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterWareHouseForm);
                // this.$router.push({path: this.$route.fullPath, query: this.filterWareHouseForm });
                axios.get('/admin/vue/warehouses/index_data?page=' + page + url)
                    .then(response => response.data)
                    .then(response => {
                        this.ware_houses = response.lists;
                        this.department_list = response.departments;
                        // this.filterWareHouseForm.search_date = response.filter.search_date
                    })
                    .catch(error => {
                        //helper.showDataErrorMsg(error);
                    });
            },
            formatDate(date) {
                var d = new Date(date);
                return d.getHours()+':'+d.getMinutes()+':'+d.getSeconds() +' '+d.getDate()+'-'+(d.getMonth()+1 > 12 ? 1 : d.getMonth()+1)+'-'+d.getFullYear();
            },
            modal_form(event,item){
                console.log(item.id);
                let quantity_detail = $('#quantity_detail_'+item.id).val().replace(/,/g, "");
                quantity_detail = parseFloat(quantity_detail);
                if((0 < quantity_detail) && (quantity_detail <= item.remaining)){

                }else{
                    toastr.warning("Số lượng xuất phải nằm trong phạm vi 1 và "+item.remaining, 'Thông báo');
                    return false;
                }
                var $temp = $("<input>");
                $("body").append($temp);
                // Get the text from the HTML element and set it as the textarea value
                $temp.val(item.code).select();
                // Copy the text to the clipboard
                document.execCommand("copy");
                // Remove the temporary textarea
                $temp.remove();
                $(event).prop("disabled", true);
                this.postForm.id = item.id;
                this.postForm.quantity = quantity_detail;
                this.postForm.post('/admin/warehouses/complete')
                             .then(response => {
                                    if(response.status == true){
                                    // $(event).closest('.list_content').remove();
                                        toastr.success(response.message, 'Thông báo');
                                        this.getWareHouses();
                                    }
                                    if(response.status == false){
                                        toastr.error(response.message, 'Thông báo');
                                    }
                                })
                             .catch(error => {
                                toastr.error('đã có lỗi xảy ra', 'Thất bại');
                              })


            },
            showInvoice(invoice){
                let invoice_data = JSON.parse(invoice.invoice_data);
                let html='';
                if(invoice_data.length > 0){
                        invoice_data.forEach(function(item, index) {
                            html+=  '<tr>'+
                                    '     <td>'+item.item+'</td>'+
                                    '     <td>'+item.pl_no+'</td>'+
                                    '     <td>'+item.qty+'</td>'+
                                    '     <td>'+item.created_at+'</td>'+
                                    '     <td><input type="checkbox" data-pl_no="'+item.pl_no+'" data-accessory="'+invoice.id+'" onclick="checkLocaltion(this)"></td>'+
                                    ' </tr>';

                        });
                }
                $('.list_item_invoice').html(html);
                $('#form_confirm').modal('show');
            },
            copyText(event){
                event.target.closest('.list_content').classList.toggle("content-selected");
                var copyText = event.target.closest('.tooltip-text').querySelector(".tooltip-text-title").innerText;
                var $temp = $("<input>");
                $("body").append($temp);
                // Get the text from the HTML element and set it as the textarea value
                $temp.val(copyText).select();
                // Copy the text to the clipboard
                document.execCommand("copy");
                // Remove the temporary textarea
                $temp.remove();
                event.target.closest('.tooltip-text').querySelector(".tooltip-text-alert").innerText = "Đã sao chép";
            },
            infoStatus(event){
                event.target.classList.toggle("collapsed");
                $(event.target).closest(".information-export").find('.collapse').collapse('toggle')
            }
        },
        watch: {
            'filterWareHouseForm.keyword': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.search_date': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.required_department_id': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.type': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.status': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.locations': function (newVal, oldVal) {
                this.getWareHouses();
            },
            'filterWareHouseForm.page_length': function (newVal, oldVal) {
                this.getWareHouses();
            }
        }
    }
</script>
