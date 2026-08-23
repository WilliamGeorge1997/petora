<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-start d-block d-md-inline-block mt-25">COPYRIGHT &copy;
            {{ date('Y') }}
            <a class="ms-25" href="https://icontds.com/" target="_blank"> Icon Tech Digital Solution</a><span
                class="d-none d-sm-inline-block">, All rights reserved</span></span><span
            class="float-md-end d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->
<div class="disabled-backdrop-ex">
    <button type="button" class="btn btn-outline-primary" hidden id="orderModal" data-bs-toggle="modal"
        data-bs-target="#orderModal2"></button>
    <div class="modal fade text-start" id="orderModal2" tabindex="-1" aria-labelledby="myModalLabel4"
        data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style="text-align: center" class="modal-title" id="myModalLabel120">تم تسجيل طلب جديد</h3>
                </div>
                <div class="modal-body">
                    <h2 style="display: block;color:#54ea59">
                        رقم الطلب هو
                        <span id="OrderId"></span>
                    </h2>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('viewOrderNotify') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="OrderId" id="Orderval">
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light">
                            <span>الذهاب للطلب</span>
                        </button>
                    </form>
                    <button onclick="closemodal()" class="btn btn-danger waves-effect waves-float waves-light">
                        شكرا
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
