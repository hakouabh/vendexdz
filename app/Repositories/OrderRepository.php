<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Role;
use App\Models\Client;
use App\Models\UserStore;
use App\Models\OrderLog;
use App\Models\OrderItems;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;
/**
 * Class OrderRepository
 * @version January 14, 2026, 09:02 am UTC+1
 */
class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    public function store($input)
    {
        try {
            DB::beginTransaction();

            $client = Client::updateOrCreate(
                ['phone_number_1' => $input['phone1']],
                [
                    'full_name'     => $input['client_name'],
                    'phone_number_2'=> $input['phone2'],
                    'wilaya'        => $input['wilaya'],
                    'town'          => $input['city'],
                    'address'       => $input['address'],
                ]
            );

            $user = auth()->user();

            // Round-robin assignment
            $assignedUserId = null;
            if (!$user->hasRole(Role::AGENT)) {
                $activeConfirmatrices = UserStore::where('is_active', true)
                    ->where('store_id', $input['store_id'])
                    ->orderBy('id')
                    ->whereHas('user.roles', function ($query) {
                        $query->where('roles.rid', Role::AGENT);
                    })
                    ->get();

                $availableAgents = $activeConfirmatrices->filter(function ($userStore) use ($input) {
                    if (!$userStore->order_limit) return true;

                    $todayCount = Order::where('sid', $input['store_id'])
                        ->where('aid', $userStore->user_id)
                        ->whereDate('created_at', today())
                        ->count();

                    return $todayCount < $userStore->order_limit;
                });

                $agentIds = $availableAgents->pluck('user_id');

                if ($agentIds->count() === 1) {
                    $assignedUserId = $agentIds->first();
                } elseif ($agentIds->count() > 1) {
                    $lastOrder = Order::where('sid', $input['store_id'])
                        ->whereIn('aid', $agentIds)
                        ->latest()
                        ->first();

                    if (!$lastOrder?->aid) {
                        $assignedUserId = $agentIds->first();
                    } else {
                        $lastIndex = $agentIds->search($lastOrder->aid);

                        $nextIndex = $lastIndex !== false
                            ? ($lastIndex + 1) % $agentIds->count()
                            : 0;

                        $assignedUserId = $agentIds->values()[$nextIndex];
                    }
                }
            }

            $order = Order::create([
                'oid'    => time() . mt_rand(1000, 9999),
                'cid'    => $client->id,
                'sid'    => $input['store_id'],
                'app_id' => $input['app_id'], 
                'aid'    => $user->hasRole(4) ? $user->id : $assignedUserId,
                'type'   => $input['type'],
            ]);

            $order->details()->create([
                'oid'            => $order->oid,
                'price'          => $input['total'],
                'total'          => $input['total'],
                'discount'       => $input['discount'],
                'delivery_price' => $input['delivery_price'],
                'commenter'      => $input['comment'],
                'stopdesk'       => $input['delivery_type'],
            ]);

            OrderLog::create([
                'oid'       => $order->oid,
                'aid'       => $user->hasRole(4) ? $user->id : $assignedUserId ?? auth()->id(),
                'statu_old' => 1,
                'statu_new' => 1,
                'text'      => trans('Order created'),
            ]);

            foreach ($input['items'] as $item) {
                OrderItems::create([
                    'oid'        => $order->oid,
                    'product_id' => $item['product_id'],
                    'vid'        => $item['vid'],
                    'quantity'   => $item['quantity'],
                ]);
            }

            $order->Inconfirmation()->create([
                'fsid' => 1,
            ]);

            DB::commit();
            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (Throwable $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
