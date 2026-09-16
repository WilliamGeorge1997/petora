<div class="modal fade text-start" id="changeModal" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="edit-title" class="modal-title" id="myModalLabel120">Update data of order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form action="{{ url('admin/orders/updateStatus') }}" method="POST" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" id="cancel-button" value="true" style="margin:10px"
                                    name="cancel" class="w-75 btn btn-outline-danger">
                                    Cancel order
                                </button>
                            </div>
                        </div>
                        <input type="hidden" value="" id="order_id" name="order_id">
                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="statuses">Order status</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select onchange="checkStatus()" class="form-select" id="statuses"
                                            name="order_status_id">
                                            @foreach ($viewModel->orderStatusesForEdit() as $key => $status)
                                                <option value="{{ $status->id }}">{{ $status->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: none;" class="col-12" id="drivers">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="driver_id">Driver</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group-merge">
                                        <select onchange="enableButton()" class="form-select" id="driver_id" name="driver_id">
                                            <option value="">Choose driver</option>
                                            @foreach ($viewModel->drivers() as $driver)
                                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1 row">
                                <div class="col-sm-3 text-center">
                                    <label class="col-form-label" for="notes">Note</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i data-feather="user"></i></span>
                                        <input type="text" class="form-control" id="notes" name="notes"
                                            placeholder="Note" />
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>
