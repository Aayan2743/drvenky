<?php

namespace App\Exports;

use App\Libraries\AppLibrary;
use App\Services\OrderService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection, WithHeadings
{

    public OrderService $orderService;
    public PaginateRequest $request;

    public function __construct(OrderService $orderService, $request)
    {
        $this->orderService = $orderService;
        $this->request      = $request;
    }

    public function collection()
    {
        $orderArray  = [];
        $ordersArray = $this->orderService->list($this->request);

        foreach ($ordersArray as $order) {

           //dd( $order->user_data->email);
            $orderArray[] = [
                $order->order_serial_no,
                trans('orderType.' . $order->order_type),
                optional($order->user)->name,
                AppLibrary::flatAmountFormat($order->total),
                $order->payment_type,
                AppLibrary::datetime($order->order_datetime),
                trans('source.' . $order->source),
                $order->user_data->email,
                trans('orderStatus.' . $order->status),
            ];
        }
        return collect($orderArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.order_serial_no'),
            trans('all.label.order_type'),
            trans('all.label.customer'),
            trans('all.label.amount'),
            trans('payment type'),
            trans('all.label.date'),
            trans('all.label.source'),
            trans('Seller Id'),
            trans('all.label.status'),
        ];
    }
}
