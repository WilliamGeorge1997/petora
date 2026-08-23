<?php

namespace Modules\Branch\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Branch\Entities\CallWaiter;
use Modules\Branch\Http\Requests\CallWaiterRequest;

class CallWaiterController extends Controller
{
    public function store(CallWaiterRequest $request)
    {
        $callWaiter = CallWaiter::create([
            'table' => $request->table,
            'branch_id' => $request->branch_id,
            'status' => 'pending'
        ]);

        pushCallWaiterNotify($callWaiter);

        return return_msg(true, 'Waiter called successfully', $callWaiter);
    }

    public function resolve($id)
    {
        $callWaiter = CallWaiter::findOrFail($id);
        $callWaiter->update(['status' => 'resolved']);
        return return_msg(true, 'Resolved successfully');
    }
}
