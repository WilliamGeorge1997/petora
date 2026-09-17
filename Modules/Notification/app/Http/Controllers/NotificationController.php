<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\Enums\AdminRole;
use Modules\Client\Models\Client;
use Modules\Client\Services\ClientService;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Notification\DTOs\NotificationDto;
use Modules\Notification\Http\Requests\NotificationRequest;
use Modules\Notification\Jobs\SendNotification;
use Modules\Notification\Models\Notification;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\ViewModels\NotificationViewModel;

#[Middleware('auth:admin')]
#[Middleware('role:'.AdminRole::SuperAdmin->value)]
#[Middleware('permission:Index-notification|Create-notification|Delete-notification', only: ['index', 'store'])]
#[Middleware('permission:Create-notification', only: ['create', 'store'])]
#[Middleware('permission:Delete-notification', only: ['destroy'])]
class NotificationController extends Controller
{
    use UploaderHelper;

    public function __construct(private NotificationService $notificationService) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $notifications = $this->notificationService->notificationsInAdminPanel();

            return success(true, __('notification::message.fetched'), $notifications);
        }

        $notifications = $this->notificationService->findAll($request->merge(['paginated' => 50])->all());

        return view('notification::notifications.index', compact('notifications'));
    }

    public function create(): View
    {
        $viewModel = new NotificationViewModel;

        return view('notification::notifications.create', compact('viewModel'));
    }

    public function store(NotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['title'] = [
            'en' => $request->validated('title_en'),
            'ar' => $request->validated('title_ar'),
        ];
        $data['description'] = [
            'en' => $request->validated('description_en'),
            'ar' => $request->validated('description_ar'),
        ];
        $data['group_by'] = $this->getGroupByCounter();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'notification');
        }

        // All clients selected
        if ($request->boolean('all_clients')) {
            $builder = Client::query()->active()->whereNotNull('fcm_token')->where('allow_notification', true);

            if ($builder->doesntExist()) {
                return back()->with('error', __('notification::message.no_clients'));
            }

            $builder->chunk(300, function ($clients) use ($data) {
                SendNotification::dispatch($clients, $data)->onConnection('database');
            });

            return to_route('admin.notifications.index')->with('success', __('notification::message.created'));
        }

        // Specific clients selected
        $clientIds = $request->input('clients', []);
        if (empty($clientIds)) {
            return back()->with('error', __('notification::message.no_clients_selected'));
        }

        foreach ($clientIds as $clientId) {
            $this->notificationService->save(new NotificationDto(
                title: $data['title'],
                description: $data['description'],
                notifiableType: Client::class,
                notifiableId: (int) $clientId,
                imageName: $data['image'] ?? null,
                groupBy: (string) $data['group_by'],
            ));
        }

        $clients = Client::query()->whereIn('id', $clientIds)->whereNotNull('fcm_token')->get();
        SendNotification::dispatch($clients, $data);

        return to_route('admin.notifications.index')->with('success', __('notification::message.created'));
    }

    public function readNotification(int $id): RedirectResponse
    {
        $notification = $this->notificationService->findById($id);
        $notification->update(['read_at' => Carbon::now()]);

        return redirect('/admin/orders/'.($notification->subject_id ?? $notification->id));
    }

    public function destroy(int $notification_id): JsonResponse|RedirectResponse
    {
        $notification = $this->notificationService->findById($notification_id);

        if ($notification->group_by) {
            Notification::query()->where('group_by', $notification->group_by)->delete();
        } else {
            $notification->delete();
        }

        if (request()->ajax()) {
            return success(true, __('notification::message.deleted'));
        }

        return to_route('admin.notifications.index')->with('success', __('notification::message.deleted'));
    }

    public function getCityClients(?int $city_id = null): JsonResponse
    {
        $query = Client::query()->active()->whereNotNull('fcm_token');

        if ($city_id) {
            $query->where('city_id', $city_id);
        }

        $clients = $query->get(['id', 'name', 'phone']);

        return success(true, __('notification::message.fetched'), $clients);
    }

    private function getGroupByCounter(): int
    {
        $last = Notification::query()->whereNotNull('group_by')->latest('id')->first();

        return $last && is_numeric($last->group_by) ? ((int) $last->group_by + 1) : 1;
    }
}

